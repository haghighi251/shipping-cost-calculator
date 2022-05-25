<?php

namespace scc\admin\admin;

use scc\functions\functions;

class admin {

    private $functions;

    public function __construct() {
        $this->functions = new functions();
    }

    public function SCCAdminIndex() {
        //Global variables
        $post = filter_input_array(INPUT_POST);
        $get = filter_input_array(INPUT_GET);
        global $twig;
        
        //Error message variable
        $message = "";
        
        /** Updating plugin settings */
        if (isset($post['set_settings'])) {

            // Minimum order value.
            if (is_numeric($post['scc_minimum_order'])) {
                update_option('scc_minimum_order', $post['scc_minimum_order']);
            }

            // Maximum value for the first distance based on meters.
            if (is_numeric($post['scc_distance_less_than_1'])) {
                update_option('scc_distance_less_than_1', $post['scc_distance_less_than_1']);
            }

            // Maximum value for the second distance based on meters.
            if (is_numeric($post['scc_distance_less_than_2'])) {
                update_option('scc_distance_less_than_2', $post['scc_distance_less_than_2']);
            }

            // Maximum value for the third distance based on meters.
            if (is_numeric($post['scc_distance_less_than_3'])) {
                update_option('scc_distance_less_than_3', $post['scc_distance_less_than_3']);
            }

            // Maximum value for the fourth distance based on meters.
            if (is_numeric($post['scc_distance_less_than_4'])) {
                update_option('scc_distance_less_than_4', $post['scc_distance_less_than_4']);
            }

            // Minimum value for the fifth distance based on meters.
            if (is_numeric($post['scc_distance_more_than'])) {
                update_option('scc_distance_more_than', $post['scc_distance_more_than']);
            }

            // Cost for the first distance.
            if (is_numeric($post['scc_distance_less_than_1_price'])){
                update_option('scc_distance_less_than_1_price', $post['scc_distance_less_than_1_price']);
            }
            
            // Cost for the second distance.
            if (is_numeric($post['scc_distance_less_than_2_price'])){
                update_option('scc_distance_less_than_2_price', $post['scc_distance_less_than_2_price']);
            }
            
            // Cost for the third distance.
            if (is_numeric($post['scc_distance_less_than_3_price'])){
                update_option('scc_distance_less_than_3_price', $post['scc_distance_less_than_3_price']);
            }
            
            // Cost for the fourth distance.
            if (is_numeric($post['scc_distance_less_than_4_price'])){
                update_option('scc_distance_less_than_4_price', $post['scc_distance_less_than_4_price']);
            }
            
            // Cost for the fifth distance.
            if (is_numeric($post['scc_distance_more_than_price'])){
                update_option('scc_distance_more_than_price', $post['scc_distance_more_than_price']);
            }  
            
            $message = "<p style='color:red; font-weith:700;font-size:25px;'>Settings has been updated.</p>";
        }

        echo $twig->render('admin/SCCAdminIndex.twig', array(
            'post' => $post,
            'get' => $get,
            'message'=>$message,
            'scc_minimum_order' => get_option('scc_minimum_order'),
            'scc_distance_less_than_1' => get_option('scc_distance_less_than_1'),
            'scc_distance_less_than_2' => get_option('scc_distance_less_than_2'),
            'scc_distance_less_than_3' => get_option('scc_distance_less_than_3'),
            'scc_distance_less_than_4' => get_option('scc_distance_less_than_3'),
            'scc_distance_more_than' => get_option('scc_distance_more_than'),
            'scc_distance_less_than_1_price' => get_option('scc_distance_less_than_1_price'),
            'scc_distance_less_than_2_price' => get_option('scc_distance_less_than_2_price'),
            'scc_distance_less_than_3_price' => get_option('scc_distance_less_than_3_price'),
            'scc_distance_less_than_4_price' => get_option('scc_distance_less_than_4_price'),
            'scc_distance_more_than_price' => get_option('scc_distance_more_than_price'),
        ));
    }

}
