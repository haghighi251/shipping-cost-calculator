<?php

/**
 * Plugin Name: Shipping cost calculator
 * Description: The shipping cost calculator plugin is a free WordPress Woocommerce plugin that helps you to calculate shipping prices per different conditions and distances.
 * Plugin URI: https://github.com/haghighi251/shipping-cost-calculator
 * Author: Amir Haghighi
 * Version: 1.0.0
 * Author URI: haghighi251@gmail.com
 * Requires PHP at least: 5.4
 * Requires PHP: 7.3.12
 * Text Domain: scc
 *
 * @package scc
 * @category Core
 *
 */

if (!session_id()) {
    session_start();
}

/**
 * The constants for SCC directory and URL for fixing setting the directories route or URLs problems.
 * Main constants that are used in this plugin. If you have activated this plugin on your WordPress,
 * Then you can use these constants on other plugins on WordPress.
 */
//ABSPATH is a PHP constant defined by WordPress at the bottom of wp-config.php
defined('ABSPATH') || exit;

//Directories constants
define('scc_dir', plugin_dir_path(__FILE__));
define('scc_include', trailingslashit(scc_dir . 'include'));
define('scc_vendor_dir', trailingslashit(scc_dir . 'vendor'));
define('scc_classes', trailingslashit(scc_dir . 'class'));
define('scc_widget_dir', trailingslashit(scc_dir . 'widgets'));
define('scc_template_dir', trailingslashit(scc_dir . 'template'));

//URLs constants
define('scc_url', plugin_dir_url(__FILE__));
define('scc_template_url', trailingslashit(scc_url . 'template'));
define('scc_upload_url', trailingslashit(scc_url . 'upload'));

// Load core packages and the autoloader.
include_once 'include/main_includes.php';

/**
 * 
  These actions must to be call after $_SESSION['unique_location_code'].
  Don't move these actions from here.
 */
add_action('wp_enqueue_scripts', 'scc_scripts_and_styles', 10000);
add_action('admin_enqueue_scripts', 'scc_scripts_and_styles', 10000);

