<?php

/**
 * On this page, we will call important PHP files and some actions of WordPress or VC.
 * In fact, this page will run the plugin. 
 * We need to call autoload page at the beginning.
 * You mustn't change the order of these files.
 */
require_once scc_vendor_dir . 'autoload.php';
require_once scc_include . 'functions.php';
require_once scc_include . 'twig.php';
require_once scc_include . 'ajax.php';

/**
 * These actions are SCC plugin actions.
 * You can get more information about WP action on the link below:
 * https://developer.wordpress.org/reference/functions/add_action/
 * You can find the actions functions on the function.php file.
 */
//Activate action will just run one time after activating the plugin. This action will be made database tables.
//We won't remove anything from WP databases after deactivating the plugin on this version.
add_action('activate_scc/index.php', 'scc_activation');

//This action will make the admin menu on the WP admin dashboard.
add_action('admin_menu', 'scc_add_admin_menu');


/**
 * This action will call the CheckUserDistance function for calculating the shipping price.
 * The CheckUserDistance function just works on the cart and checkout page on the WC.
 * If you need to calculate the shipping price on any other page on the WP, You have to change the CheckUserDistance function.
 */
add_action('the_content', 'CheckUserDistance');

/**
 * These actions are WC plugin actions.
 * You can get more information about WC action on the link below:
 * https://woocommerce.com/document/introduction-to-hooks-actions-and-filters/
 * You can find the actions functions on the function.php file.
 */
//Calculating shipping costs. In this filter, we will check the minimum price of orders.
add_filter('woocommerce_package_rates', 'custom_shipping_costs', 20000, 2);
