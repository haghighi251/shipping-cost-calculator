<?php

/**
 * 
 * On the AJAX page, we just call the ajax method that needs to be called with an ajax request. These methods return JSON format.
 */
session_start();

/**
 * 
 * This action is used for handling an ajax request. You can get more information from the link below:
 * https://developer.wordpress.org/reference/hooks/wp_ajax_action/
 */
add_action('wp_ajax_set_user_location', 'set_user_location');
add_action('wp_ajax_nopriv_set_user_location', 'set_user_location');

/**
 * 
 * With this method we will update lat and lon and distance not ZipCode
 * 
 * @global type $wpdb
 * @return type
 */
function set_user_location() {
    global $wpdb;

    //rejecting the request if it has not had all the necessary data.
    if (!isset($_POST['Latitude']) || !isset($_POST['Longitude']) || !isset($_POST['unique_location_code'])) {
        return die(json_encode(array(
            'success' => FALSE,
            "distance" => "",
            "message" => "Incorrect data."
        )));
    }

    //updating use_location table data with the sent data.
    $wpdb->update("{$wpdb->prefix}users_location", array(
        'lat' => $_POST['Latitude'],
        'lon' => $_POST['Longitude'],
            ), array(
        'ip' => $_POST['user_ip'],
    ));

    //Now we have to get user address from google API. Then we can get user distance from the user address. 
    $google_api_key = get_option('scc_google_api_key');
    $address = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng={$_POST['Latitude']},{$_POST['Longitude']}&key=$google_api_key"));
    $user_address = $address->results[0]->formatted_address;
    $address_url_encode = urlencode($user_address);
    $shop_address = get_option('scc_shop_adress');
    $q = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$shop_address}&destinations={$address_url_encode}&mode=driving&sensor=false&key=$google_api_key";
    $json = file_get_contents($q);
    if ($json != "" && $json != FALSE && $json != NULL) {
        $details = json_decode($json);
        //updating use_location table data with the sent data.
        $wpdb->update(
                "{$wpdb->prefix}users_location",
                array('distance' => $details->rows[0]->elements[0]->distance->value,), //data
                array('ip' => $_POST['user_ip'],)//WHERE condition
        );
        if (!is_numeric($details->rows[0]->elements[0]->distance->value)) {
            //Default distance if we can't get distance from the google API.
            set_user_distance(get_option('scc_distance_less_than_1'));
        } else {
            set_user_distance($details->rows[0]->elements[0]->distance->value);
        }
    } else {
        //Default distance if we can't get distance from the google API.
        set_user_distance(get_option('scc_distance_less_than_1'));
    }
}

/**
 * 
 * This action is used for handling an ajax request. You can get more information from the link below:
 * https://developer.wordpress.org/reference/hooks/wp_ajax_action/
 */
add_action('wp_ajax_get_address_with_zipcode', 'get_address_with_zipcode');
add_action('wp_ajax_nopriv_get_address_with_zipcode', 'get_address_with_zipcode');

/**
 * 
 * This function returns distance based on zip code.
 * With this method we will update ZipCode not lat and lon.
 * We will do this action in set_user_distance method.
 * 
 * @global type $wpdb
 * @return type
 */
function get_address_with_zipcode() {
    //rejecting the request if it has not had all the necessary data.
    if (!isset($_POST['zipcode']) || !isset($_POST['unique_location_code'])) {
        return die(json_encode(array(
            'success' => FALSE,
            "distance" => "",
            "message" => "Incorrect data."
        )));
    }

    $google_api_key = get_option('scc_google_api_key');
    $shop_address = get_option('scc_shop_adress');
    $q = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$shop_address}&destinations={$_POST['zipcode']}&mode=driving&sensor=false&key=$google_api_key";

    //getting result from the Google API.
    $json = file_get_contents($q);

    //Converting data from json to a PHP onject
    $details = json_decode($json);

    //Don't convert distance to km. It has to be as just meters otherwise location.js will be crashed.
    $distance = $details->rows[0]->elements[0]->distance->value;
    set_user_distance($distance);
}

add_action('wp_ajax_set_user_distance', 'set_user_distance');
add_action('wp_ajax_nopriv_set_user_distance', 'set_user_distance');

function set_user_distance($distance) {
    global $wpdb;

    //setting default distance if we have not had it.
    if (!is_numeric($distance)) {
        $distance = get_option('scc_distance_less_than_1');
    }

    //Getting user IP
    $functions = new \obs\functions\functions();
    $user_ip = $functions->get_user_ip();

    $location_query = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}users_location WHERE ip='{$user_ip}'");
    if (!$location_query) {
        return die(json_encode(array(
            'success' => FALSE,
            "distance" => "",
            "message" => "Your ip address has changed and we can't set your location. Plese refresh the page."
        )));
    } else {
        $wpdb->update("{$wpdb->prefix}users_location",
                array("distance" => $distance,'ZipCode' => $_POST['zipcode']),//data
                array("ip" => $user_ip)//Where condition
        );
        return die(json_encode(array(
            'success' => TRUE,
            "message" => ""
        )));
    }
}
