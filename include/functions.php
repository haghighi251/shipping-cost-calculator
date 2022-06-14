<?php

/**
 * All PHP functions that belong to the SCC plugin, Are listed here.
 * Please pay attention to you can not find the AJAX functions on this file.
 * The AJAX functions are listed on the ajax.php file.
 */

//The namespaces that we need to use on this file.
use scc\admin\admin\admin;
use scc\functions\functions;

/**
 * 
 * This function will be loaded just one time after installing the plugin.
 * This function will make database tables.
 * We won't remove anything from WP databases after deactivating the plugin on this version.
 * 
 * @global type $wpdb
 */
function scc_activation() {
    //$wpdb is used for working with WP db.
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    /**
     * Table important fields:
     * user_id: WP user id. ID column from "users" table.
     * ip: user IP that represents from get_user_ip method in function.php file in the class folder.
     * date: Insert/Update time.
     * lat: The latitude of the user location.
     * lon: The longitude of the user location.
     * SessionId: Keep session-id for each user. it's a unique field.
     * distance: This field will keep the user distance as meters from the shop location.
     */
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}users_location` (
  `users_location_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `lat` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `lon` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `SessionId` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `distance` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=$charset_collate COLLATE=utf8_unicode_ci ;";
    dbDelta($sql);

    //It sets the minimum order price to $10. You can change it from the admin dashboard panel.
    update_option('scc_minimum_order', 10);

    //Setting default quantities for distance and price per $ for each distance
    update_option('scc_distance_less_than_1', 3000);
    update_option('scc_distance_less_than_1_price', 5);
    update_option('scc_distance_less_than_2', 10000);
    update_option('scc_distance_less_than_2_price', 10);
    update_option('scc_distance_less_than_3', 50000);
    update_option('scc_distance_less_than_3_price', 20);
    update_option('scc_distance_less_than_4', 100000);
    update_option('scc_distance_less_than_4_price', 30);
    update_option('scc_distance_more_than', 100000);
    update_option('scc_distance_more_than_price', 50);
}

/**
 * 
 * This function will load JavaScript and CSS files.
 * @global type $wpdb
 */
function scc_scripts_and_styles() {
    //Make an instance from function class.
    $functions = new functions();

    //Getting user IP
    $user_ip = $functions->get_user_ip();

    //$is_cart page helps us to know in js files whether we are on the cart page or not.
    if ($_SERVER['REDIRECT_URL'] == '/cart/') {
        $is_cart_page = true;
    } else {
        $is_cart_page = false;
    }

    //$arr_options it's an array that we will send for the js file as an object.
    $arr_options = array(
        'jsurl' => scc_url . 'asset/js/',
        'imgurl' => scc_url . 'asset/img/',
        'ajaxurl' => admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')),
        'site_url' => site_url(),
        'user_id' => get_current_user_id(),
        'unique_location_code' => $_SESSION['unique_location_code'],
        'user_ip' => $user_ip,
        'is_admin' => is_admin() ? 1 : 0,
        'popup' => get_option("active_popup"),
        'popup_file' => get_option('popup_file'),
        'popup_title' => get_option('popup_title'),
        'popup_url' => get_option('popup_url'),
        'cart_page' => $is_cart_page,
    );

    //Loading js and css files.
    wp_enqueue_script('mainheadjs', scc_url . 'asset/js/scc.js', array('jquery'), '', false);
    wp_localize_script('mainheadjs', 'scc', $arr_options);
    wp_enqueue_script('sweetalert2.min.js', scc_url . 'asset/js/sweetalert2.min.js', array('jquery'), '', true);
    wp_enqueue_style('scc', scc_url . 'asset/css/scc.css');
    wp_enqueue_style('sweetalert2.min.css', scc_url . 'asset/css/sweetalert2.min.css');

    //Current user id. It will return zero for unlogged users.
    $user_id = get_current_user_id();

    if (is_null($_SESSION['unique_location_code'])) {
        $_SESSION['unique_location_code'] = time() . str_replace(".", "", $user_ip);
    }

    //The below part just is used when the admin is logged.
    if (!is_admin()) {
        global $wpdb;
        $location_query = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}users_location WHERE ip='{$user_ip}'");
        if (!$location_query) {
            //open get location window and set into the db;
            $wpdb->insert("{$wpdb->prefix}users_location", array(
                'user_id' => $user_id,
                'ip' => $user_ip,
                'date' => date("Y-m-d H:i:s"),
                'SessionId' => $_SESSION['unique_location_code'],
            ));
            wp_enqueue_script('location', scc_url . 'asset/js/location.js', array('jquery'), '', true);
        } else if (
                ($location_query->lat == "" && $location_query->lon == "") ||
                (is_null($location_query->lat) || is_null($location_query->lon))
        ) {
            wp_enqueue_script('location', scc_url . 'asset/js/location.js', array('jquery'), '', true);
        }
    }
}

/**
 * Making admin dashboard panel menu.
 */
function scc_add_admin_menu() {
    add_menu_page('SCC', 'Shipping Cost Calculator', 'administrator', "scc_system", 'scc_admin_menu_actions', '', 3);
}

/**
 * Admin menu action recognizes which file needs to run in the admin panel.
 */
function scc_admin_menu_actions() {
    $admin = new admin();
    $admin->SCCAdminIndex();
}

/**
 * 
 * This function will calculate shipping coasts per user distance from the shop location.
 * 
 * @global type $wpdb
 * @return boolean
 */
function GetDeliveryPrice() {
    $post = filter_input_array(INPUT_POST);
    //If we had calc_shipping_postcode or postcode parameters, We get distance from online google API, otherwise, we will get it from the "users_location" table.
    if ($post['calc_shipping_postcode'] != "") {
        $distance = GetDistanceAsZipCode($post['calc_shipping_postcode']);
    } else if ($post['postcode'] != "") {
        $distance = GetDistanceAsZipCode($post['postcode']);
    } else {
        //Getting distance from the db.
        global $wpdb;

        //Getting user ip
        $functions = new functions();
        $user_ip = $functions->get_user_ip();

        //Getting user information from the "users_location" table.
        $location_query = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}users_location WHERE ip='{$user_ip}'");
        if (!$location_query) {
            $distance = 0;
        } else if ($location_query->lat == null && $location_query->lon == null && !is_numeric($location_query->distance) && $location_query->ZipCode != null) {
            $distance = GetDistanceAsZipCode($location_query->ZipCode);
        } else if ($location_query->lat == null && $location_query->lon == null && !is_numeric($location_query->distance) && $location_query->ZipCode == null) {
            $distance = 0;
        } else {
            $distance = $location_query->distance;
        }
    }
    $price = 0;
    if (!is_numeric($distance) || $distance == 0) {
        wc_add_notice("Unfortunately, We don't have your distance and without distance, we can't calculate the shipping cost. Please turn on your GPS and refresh the page.", 'error');
        return FALSE;
    }

    //Getting shipping distance and cost.
    $scc_distance_less_than_1 = get_option('scc_distance_less_than_1');
    $scc_distance_less_than_1_price = get_option('scc_distance_less_than_1_price');
    $scc_distance_less_than_2 = get_option('scc_distance_less_than_2');
    $scc_distance_less_than_2_price = get_option('scc_distance_less_than_2_price');
    $scc_distance_less_than_3 = get_option('scc_distance_less_than_3');
    $scc_distance_less_than_3_price = get_option('scc_distance_less_than_3_price');
    $scc_distance_less_than_4 = get_option('scc_distance_less_than_4');
    $scc_distance_less_than_4_price = get_option('scc_distance_less_than_4_price');
    $scc_distance_more_than = get_option('scc_distance_more_than');
    $scc_distance_more_than_price = get_option('scc_distance_more_than_price');

    if (!is_numeric($scc_distance_less_than_1) || !is_numeric($scc_distance_less_than_1_price)) {
        wc_add_notice("The admin of the site has to declare the distance of shipping and cost.", 'error');
        return false;
    }
    if ($distance < $scc_distance_less_than_1) {
        $price = $scc_distance_less_than_1_price;
    } elseif ($distance > $scc_distance_less_than_1 && $distance < $scc_distance_less_than_2) {
        $km = round(($scc_distance_less_than_2 / 1000));
        $price = ($km * $scc_distance_less_than_2_price);
    } elseif ($distance > $scc_distance_less_than_2 && $distance < $scc_distance_less_than_3) {
        $km = round(($scc_distance_less_than_3 / 1000));
        $price = ($km * $scc_distance_less_than_3_price);
    } elseif ($distance > $scc_distance_less_than_3 && $distance < $scc_distance_less_than_4) {
        $km = round(($scc_distance_less_than_4 / 1000));
        $price = ($km * $scc_distance_less_than_4_price);
    } else if ($distance > $scc_distance_more_than) {
        $km = round(($scc_distance_more_than / 1000));
        $price = ($km * $scc_distance_more_than_price);
    }
    return $price;
}

/**
 * 
 * َAdding custom delivery price to the cart.
 * 
 * @param type $rates
 * @param type $package
 * @return type
 */
function custom_shipping_costs($rates) {
    //We don't have a delivery for orders that have prices less than the minimum price that the admin has defined.
    //Getting the minimum price of orders.
    $minumum_orders_price = get_option('scc_minimum_order');

    //Getting cart total prices
    if (WC()->cart->cart_contents_total > $minumum_orders_price) {
        $new_cost = GetDeliveryPrice();
        $tax_rate = 0;
        foreach ($rates as $rate_key => $rate) {
            // Excluding free shipping methods
            if ($rate->method_id != 'local_pickup') {
                if ($new_cost === FALSE) {
                    unset($rates[$rate_key]);
                } else {
                    // Set rate cost
                    $rates[$rate_key]->cost = $new_cost;
                    // Set taxes rate cost (if enabled)
                    $taxes = array();
                    foreach ($rates[$rate_key]->taxes as $key => $tax) {
                        if ($rates[$rate_key]->taxes[$key] > 0)
                            $taxes[$key] = $new_cost * $tax_rate;
                    }
                    $rates[$rate_key]->taxes = $taxes;
                }
            }
        }
    } else {
        wc_add_notice("Unfortunately, We don't have a delivery for orders less than ${$minumum_orders_price}", 'error');
    }
    return $rates;
}

/**
 * 
 * This function will return the distance per meter from google API.
 * It just needs the postal code.
 * For using this plugin and function you have to set your google API key to the admin panel.
 * 
 * @param type $ZipCode
 * @return boolean
 */
function GetDistanceAsZipCode($zip_code) {
    //For converting any space character to an acceptable URL value.
    $ZipCode = urlencode($zip_code);
    $google_api_key = get_option('scc_google_api_key');
    $q = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=8679+E+10th+Avenue,+Burnaby,+BC&destinations={$ZipCode}&mode=driving&sensor=false&key={$$google_api_key}";
    $json = file_get_contents($q);
    $details = json_decode($json);
    //Distance is based on meters.
    $distance = $details->rows[0]->elements[0]->distance->value;
    if (is_numeric($distance) && $distance > 0) {
        return $distance;
    } else {
        return FALSE;
    }
}

/**
 * 
 * In this function, we check that we are in the cart or checkout page, 
 * if yes will add a js file for calculating distance with the browser GPS feature.
 * 
 * @param type $content
 * @return type
 */
function CheckUserDistance($content) {
    $pages_name = array("[woocommerce_checkout]", "[woocommerce_cart]");
    if (in_array(trim($content), $pages_name)) {
        //We are in the cart or checkout page in the WooCommerce.
        $delivery_fee = GetDeliveryPrice();
        if ($delivery_fee === FALSE || $delivery_fee == 0) {
            add_action('wp_footer', 'my_footer_scripts');
        }
    }
    return $content;
}

/**
 * Making a session if it has not been made.
 */
function start_session_wp() {
    if (!session_id()) {
        session_start();
    }
}
