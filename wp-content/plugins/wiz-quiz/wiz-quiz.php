<?php

/*
 * Plugin Name:      Wiz Quiz
* Plugin URI:        https://github.com/shakib6472/
* Description:       This plugin is to be used for Quiz Quiz
* Version:           2.0.2
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
require_once WIZ_QUIZ_PLUGIN_DIR . 'setups.php';
require_once WIZ_QUIZ_PLUGIN_DIR . 'ajax.php';
require_once WIZ_QUIZ_PLUGIN_DIR . 'admin.php';

/* Enqueue Assets */
function wiz_enqueue_assets()
{
    // Enqueue CSS
    wp_enqueue_style('wiz-quiz-toast-style', plugin_dir_url(__FILE__) . 'assets/css/toast.css', array(), '1.0.0', 'all');
    wp_enqueue_style('wiz-quiz-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), '1.0.0', 'all');

    wp_enqueue_script('jquery');
    wp_enqueue_script('wiz-quiz-script', plugin_dir_url(__FILE__) . 'assets/js/scripts.js', array('jquery'), '1.0.0', true); // Load after jQuery
    wp_enqueue_script('wiz-quiz-font-owsome-script',  'https://kit.fontawesome.com/46882cce5e.js', array('jquery'), '1.0.0', true); // Load after jQuery
    wp_enqueue_script('wiz-quiz-toast-script',  'https://cdn.jsdelivr.net/npm/jquery-toast-plugin@1.3.2/dist/jquery.toast.min.js', array('jquery'), '1.0.0', true); // Load after jQuery
    wp_enqueue_script('wiz-quiz-jq-ui-script',  'https://code.jquery.com/ui/1.13.2/jquery-ui.min.js', array('jquery'), '1.0.0', true); // Load after jQuery
    wp_enqueue_script('wiz-quiz-math-script',  'https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-chtml.js', array('jquery'), '1.0.0', true); // Load after jQuery

    // Localize the script with data
    wp_localize_script('wiz-quiz-script', 'ajax_object', array(
        'ajax_url'   => admin_url('admin-ajax.php'),  // Correct Ajax URL
        'result_url'   => home_url('/results'),                    // Correct Result page URL
        'home_url'   => home_url()                    // Correct Home URL
    ));
}
add_action('wp_enqueue_scripts', 'wiz_enqueue_assets');

//enquew admin scripts  
function wiz_admin_enqueue_scripts()
{
    wp_enqueue_style('wiz-quiz-toast-style', plugin_dir_url(__FILE__) . 'assets/css/toast.css', array(), '1.0.0', 'all');
    wp_enqueue_style('wiz-quiz-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css', array(), '1.0.0', 'all');

    wp_enqueue_script('jquery');
    wp_enqueue_script('wiz-quiz-admin-script', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery'), '1.0.0', true); // Load after jQuery
    wp_enqueue_script('wiz-quiz-toast-script',  'https://cdn.jsdelivr.net/npm/jquery-toast-plugin@1.3.2/dist/jquery.toast.min.js', array('jquery'), '1.0.0', true); // Load after jQuery

    wp_localize_script('wiz-quiz-admin-script', 'ajax_object', array(
        'ajax_url'   => admin_url('admin-ajax.php'),  // Correct Ajax URL
        'result_url'   => home_url('/results'),                    // Correct Result page URL
        'home_url'   => home_url()                    // Correct Home URL
    ));
}
add_action('admin_enqueue_scripts', 'wiz_admin_enqueue_scripts');

// Activation hook
function wiz_activate_plugin()
{

    //create a table in the database
    global $wpdb;
    $table_name = $wpdb->prefix . "wiz_results";
    $sql = "CREATE TABLE $table_name (
    id int(11) NOT NULL AUTO_INCREMENT,
    user_name TEXT NOT NULL,
    user_id int(11) NOT NULL,
    device_id varchar(255) NOT NULL,
    quiz_id int(11) NOT NULL, 
    date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    quiz_type varchar(255) NOT NULL,
    quiz_type_id int(11) NOT NULL,
    total_time int(11) NOT NULL,
    time int(100) NOT NULL,
    timezone varchar(255) NOT NULL,
    result TEXT NOT NULL,
    user_agent TEXT NOT NULL,
    PRIMARY KEY  (id)
    );";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Writting Test
    $table_name = $wpdb->prefix . "wiz_writting_results";
    $sql = "CREATE TABLE $table_name (
    id int(11) NOT NULL AUTO_INCREMENT,
    user_name TEXT NOT NULL,
    user_id int(11) NOT NULL,
    device_id varchar(255) NOT NULL,
    quiz_id int(11) NOT NULL, 
    date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    quiz_type varchar(255) NOT NULL,
    quiz_type_id int(11) NOT NULL,
    total_time int(11) NOT NULL,
    time int(100) NOT NULL, 
    timezone varchar(255) NOT NULL,
    result TEXT NOT NULL,
    user_agent TEXT NOT NULL,
    PRIMARY KEY  (id)
    );";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    //get page by slug
    $page = get_page_by_path('dashboard'); // that's depreciate 
    if (!$page) {
        $page = [
            'post_title' => 'Dashboard',
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'page'
        ];
        wp_insert_post($page);
    }
    $page = get_page_by_path('results'); // that's depreciate 
    if (!$page) {
        $page = [
            'post_title' => 'Results',
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'page'
        ];
        wp_insert_post($page);
    }
    $page = get_page_by_path('results-details'); // that's depreciate 
    if (!$page) {
        $page = [
            'post_title' => 'Results Details',
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'page'
        ];
        wp_insert_post($page);
    }
}
register_activation_hook(__FILE__, 'wiz_activate_plugin');

// Deactivation hook
function wiz_deactivate_plugin()
{
    // Delete the database tables
    global $wpdb;
    $tables = [
        $wpdb->prefix . "wiz_results",
        $wpdb->prefix . "wiz_writting_results"
    ];

    foreach ($tables as $table) {
        $sql = "DROP TABLE IF EXISTS $table";
        $wpdb->query($sql);
    }
    //delete the pages
    $pages = [
        'dashboard',
        'results',
        'results-details'
    ];
    foreach ($pages as $page) {
        $page = get_page_by_path($page);
        if ($page) {
            wp_delete_post($page->ID, true);
        }
    }
}
register_deactivation_hook(__FILE__, 'wiz_deactivate_plugin');

// Uninstall hook (if you want to clean up everything when the plugin is deleted)
function wiz_uninstall_plugin()
{
    // Clean up everything (e.g., database tables, options, etc.)

}
register_uninstall_hook(__FILE__, 'wiz_uninstall_plugin');

//desable admin bar
// add_filter('show_admin_bar', '__return_false');



$font_family = get_option('font_family');
 

