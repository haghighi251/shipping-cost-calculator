<?php

/**
 * Plugin Name: Shipping cost calculator
 * Description: The shipping cost calculator plugin is a free WordPress Woocommerce plugin that helps you to calculate shipping prices per different conditions and distances.
 * Plugin URI: http://scctech.ca/
 * Author: Amir Haghighi
 * Version: 1.0.0
 * Author URI: haghighi251@gmail.com
 * Requires at least: 5.4
 * Requires PHP: 7.3.12
 * Text Domain: scc
 *
 * @package scc
 * @category Core
 *
 */
session_start();

// Main constants
defined('ABSPATH') || exit;
define('scc_dir', plugin_dir_path(__FILE__));
define('scc_url', plugin_dir_url(__FILE__));

// Load core packages and the autoloader.
include_once 'include/main_includes.php';


//These actions must to be call after $_SESSION['uniqe_location_code']
add_action('wp_enqueue_scripts', 'scc_scripts_and_styles', 10000);
add_action('admin_enqueue_scripts', 'scc_scripts_and_styles', 10000);
//Don't move these actions from here 
