<?php

namespace obs\admin\admin;

use obs\functions\functions;

class admin {

    private $functions;

    public function UpdateCustomPizzaPrices() {
        $this->functions = new functions();
        global $twig, $wpdb;
        if (isset($_POST['set_settings'])) {
            if ($_POST['happy_hour_percentage'] != '')
                update_option('happy_hour_percentage', $_POST['happy_hour_percentage']);
            if ($_POST['cheese_pizza_personal'] != '')
                update_option('cheese_pizza_personal', $_POST['cheese_pizza_personal']);
            if ($_POST['cheese_pizza_small'] != '')
                update_option('cheese_pizza_small', $_POST['cheese_pizza_small']);
            if ($_POST['cheese_pizza_medium'] != '')
                update_option('cheese_pizza_medium', $_POST['cheese_pizza_medium']);
            if ($_POST['cheese_pizza_large'] != '')
                update_option('cheese_pizza_large', $_POST['cheese_pizza_large']);

            if ($_POST['pepperoni_pizza_personal'] != '')
                update_option('pepperoni_pizza_personal', $_POST['pepperoni_pizza_personal']);
            if ($_POST['pepperoni_pizza_small'] != '')
                update_option('pepperoni_pizza_small', $_POST['pepperoni_pizza_small']);
            if ($_POST['pepperoni_pizza_medium'] != '')
                update_option('pepperoni_pizza_medium', $_POST['pepperoni_pizza_medium']);
            if ($_POST['pepperoni_pizza_large'] != '')
                update_option('pepperoni_pizza_large', $_POST['pepperoni_pizza_large']);

            if ($_POST['ham_pinepple_personal'] != '')
                update_option('ham_pinepple_personal', $_POST['ham_pinepple_personal']);
            if ($_POST['ham_pinepple_small'] != '')
                update_option('ham_pinepple_small', $_POST['ham_pinepple_small']);
            if ($_POST['ham_pinepple_medium'] != '')
                update_option('ham_pinepple_medium', $_POST['ham_pinepple_medium']);
            if ($_POST['ham_pinepple_large'] != '')
                update_option('ham_pinepple_large', $_POST['ham_pinepple_large']);

            if ($_POST['speciality_toppings_personal'] != '')
                update_option('speciality_toppings_personal', $_POST['speciality_toppings_personal']);
            if ($_POST['speciality_toppings_small'] != '')
                update_option('speciality_toppings_small', $_POST['speciality_toppings_small']);
            if ($_POST['speciality_toppings_medium'] != '')
                update_option('speciality_toppings_medium', $_POST['speciality_toppings_medium']);
            if ($_POST['speciality_toppings_large'] != '')
                update_option('speciality_toppings_large', $_POST['speciality_toppings_large']);

            if ($_POST['gourmet_toppings_personal'] != '')
                update_option('gourmet_toppings_personal', $_POST['gourmet_toppings_personal']);
            if ($_POST['gourmet_toppings_small'] != '')
                update_option('gourmet_toppings_small', $_POST['gourmet_toppings_small']);
            if ($_POST['gourmet_toppings_medium'] != '')
                update_option('gourmet_toppings_medium', $_POST['gourmet_toppings_medium']);
            if ($_POST['gourmet_toppings_large'] != '')
                update_option('gourmet_toppings_large', $_POST['gourmet_toppings_large']);

            if ($_POST['ultimate_toppings_personal'] != '')
                update_option('ultimate_toppings_personal', $_POST['ultimate_toppings_personal']);
            if ($_POST['ultimate_toppings_small'] != '')
                update_option('ultimate_toppings_small', $_POST['ultimate_toppings_small']);
            if ($_POST['ultimate_toppings_medium'] != '')
                update_option('ultimate_toppings_medium', $_POST['ultimate_toppings_medium']);
            if ($_POST['ultimate_toppings_large'] != '')
                update_option('ultimate_toppings_large', $_POST['ultimate_toppings_large']);

            if ($_POST['extra_cheese_personal'] != '')
                update_option('extra_cheese_personal', $_POST['extra_cheese_personal']);
            if ($_POST['extra_cheese_small'] != '')
                update_option('extra_cheese_small', $_POST['extra_cheese_small']);
            if ($_POST['extra_cheese_medium'] != '')
                update_option('extra_cheese_medium', $_POST['extra_cheese_medium']);
            if ($_POST['extra_cheese_large'] != '')
                update_option('extra_cheese_large', $_POST['extra_cheese_large']);

            if ($_POST['daiya_cheese_personal'] != '')
                update_option('daiya_cheese_personal', $_POST['daiya_cheese_personal']);
            if ($_POST['daiya_cheese_small'] != '')
                update_option('daiya_cheese_small', $_POST['daiya_cheese_small']);
            if ($_POST['daiya_cheese_medium'] != '')
                update_option('daiya_cheese_medium', $_POST['daiya_cheese_medium']);
            if ($_POST['daiya_cheese_large'] != '')
                update_option('daiya_cheese_large', $_POST['daiya_cheese_large']);
        }
        $message = false;
        echo $twig->render('admin/UpdateCustomPizzaPrices.twig', array(
            'message' => $message,
            'post' => $_POST,
            'get' => $_GET,
            'ats_url' => fw_url,
            'happy_hour_percentage' => get_option('happy_hour_percentage'),
            'cheese_pizza_personal' => get_option('cheese_pizza_personal'),
            'cheese_pizza_small' => get_option('cheese_pizza_small'),
            'cheese_pizza_medium' => get_option('cheese_pizza_medium'),
            'cheese_pizza_large' => get_option('cheese_pizza_large'),
            'pepperoni_pizza_personal' => get_option('pepperoni_pizza_personal'),
            'pepperoni_pizza_small' => get_option('pepperoni_pizza_small'),
            'pepperoni_pizza_medium' => get_option('pepperoni_pizza_medium'),
            'pepperoni_pizza_large' => get_option('pepperoni_pizza_large'),
            'ham_pinepple_personal' => get_option('ham_pinepple_personal'),
            'ham_pinepple_small' => get_option('ham_pinepple_small'),
            'ham_pinepple_medium' => get_option('ham_pinepple_medium'),
            'ham_pinepple_large' => get_option('ham_pinepple_large'),
            'speciality_toppings_personal' => get_option('speciality_toppings_personal'),
            'speciality_toppings_small' => get_option('speciality_toppings_small'),
            'speciality_toppings_medium' => get_option('speciality_toppings_medium'),
            'speciality_toppings_large' => get_option('speciality_toppings_large'),
            'gourmet_toppings_personal' => get_option('gourmet_toppings_personal'),
            'gourmet_toppings_small' => get_option('gourmet_toppings_small'),
            'gourmet_toppings_medium' => get_option('gourmet_toppings_medium'),
            'gourmet_toppings_large' => get_option('gourmet_toppings_large'),
            'ultimate_toppings_personal' => get_option('ultimate_toppings_personal'),
            'ultimate_toppings_small' => get_option('ultimate_toppings_small'),
            'ultimate_toppings_medium' => get_option('ultimate_toppings_medium'),
            'ultimate_toppings_large' => get_option('ultimate_toppings_large'),
            'extra_cheese_personal' => get_option('extra_cheese_personal'),
            'extra_cheese_small' => get_option('extra_cheese_small'),
            'extra_cheese_medium' => get_option('extra_cheese_medium'),
            'extra_cheese_large' => get_option('extra_cheese_large'),
            'daiya_cheese_personal' => get_option('daiya_cheese_personal'),
            'daiya_cheese_small' => get_option('daiya_cheese_small'),
            'daiya_cheese_medium' => get_option('daiya_cheese_medium'),
            'daiya_cheese_large' => get_option('daiya_cheese_large'),
        ));
    }

    public function popup() {
        $this->functions = new functions();
        global $twig, $wpdb;
        $message = false;
        if (isset($_POST['popup_submit'])) {
            if (!function_exists('wp_handle_upload')) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }
            if ($_POST['active_popup'] == 1)
                update_option("active_popup", 1);
            else
                update_option("active_popup", 0);
            update_option("popup_title", $_POST['popup_title']);
            update_option("popup_url", $_POST['popup_url']);
            if (isset($_FILES['popup_file'])) {
                $uploadedfile = $_FILES['popup_file'];
                $upload_overrides = array(
                    'test_form' => false,
                    'mimes' => array(
                        'jpg|jpeg|jpe' => 'image/jpeg',
                        'gif' => 'image/gif',
                        'png' => 'image/png',
                        'bmp' => 'image/bmp',
                        'tif|tiff' => 'image/tiff',
                        'ico' => 'image/x-icon'
                ));
                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
                if ($movefile && !isset($movefile['error'])) {
                    update_option('popup_file', $movefile['url']);
                    $message .= "<h1>Popuo Has Activated.</h1>";
                } else {
                    $message .= '<h1>Popuo Has Not Activated.</h1>' . $movefile['error'];
                }
            }
        }
        echo $twig->render('admin/popup.twig', array(
            'message' => $message,
            'post' => $_POST,
            'get' => $_GET,
            'ats_url' => fw_url,
            'popup_image' => get_option('popup_file'),
            'popup_title' => get_option('popup_title'),
            'popup_url' => get_option('popup_url'),
            
        ));
    }

}
