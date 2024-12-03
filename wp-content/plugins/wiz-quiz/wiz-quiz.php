<?php

/*
 * Plugin Name:      Wiz Quiz
* Plugin URI:        https://github.com/shakib6472/
* Description:       This plugin is to be used for Quiz Quiz
* Version:           1.0.0
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Shakib Shown
* Author URI:        https://github.com/shakib6472/
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:       wiz-quiz
* Domain Path:       /languages
*/
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('WIZ_QUIZ_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WIZ_QUIZ_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once WIZ_QUIZ_PLUGIN_DIR . 'ele/elementor-setup.php';



/* Enqueue Assets */
function wiz_enqueue_assets()
{
    // Enqueue CSS
    wp_enqueue_style('wiz-quiz-toast-style', plugin_dir_url(__FILE__) . 'assets/css/toast.css', array(), '1.0.0', 'all');
    wp_enqueue_style('wiz-quiz-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), '1.0.0', 'all');

    wp_enqueue_script('jquery');
    wp_enqueue_script('wiz-quiz-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), '1.0.0', true); // Load after jQuery
}
add_action('wp_enqueue_scripts', 'wiz_enqueue_assets');


// Activation hook
function wiz_activate_plugin()
{
    // Code to run when the plugin is activated

}
register_activation_hook(__FILE__, 'wiz_activate_plugin');

// Deactivation hook
function wiz_deactivate_plugin()
{
    // Code to run when the plugin is deactivated

}
register_deactivation_hook(__FILE__, 'wiz_deactivate_plugin');

// Uninstall hook (if you want to clean up everything when the plugin is deleted)
function wiz_uninstall_plugin()
{
    // Clean up everything (e.g., database tables, options, etc.)

}
register_uninstall_hook(__FILE__, 'wiz_uninstall_plugin');
