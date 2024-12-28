<?php

/**
 * Template results in Details.
 * 
 * @package Wiz Quiz
 */

// Get Header
wp_head();
global $wpdb;
// Get the current taxonomy term
$quiz_id = $_GET['quizid'];
$term_id = $_GET['term'];
$term = get_term($term_id);
//insert a new row in database for this quiz
if (is_user_logged_in()) {
    $user_id = get_current_user_id();
} else {
    $user_id = 0;
}
// Call the function to get the device ID
$device_id = $_SERVER['REMOTE_ADDR'];
$quiz_type = $term->taxonomy;
$total_time = 0;
$current_time = time(); 
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $user_name =   $current_user->user_firstname . ' ' . $current_user->user_lastname;
} elseif (isset($_COOKIE['user_name'])) {
    $user_name = $_COOKIE['user_name'];
}
 
if (is_tax('writtings-practice')) {
    $existing_result = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}wiz_writting_results WHERE quiz_id = %d AND user_id = %d",
        $quiz_id,
        $user_id
    ));

    if (! $existing_result) {
        // Insert new data if no existing record
        $database = $wpdb->insert($wpdb->prefix . "wiz_writting_results", array(
            'user_id' => $user_id,
            'user_name' => $user_name,
            'device_id' => $device_id,
            'quiz_id' => $quiz_id,
            'total_time' => $total_time,
            'date' => current_time('mysql'),
            'quiz_type' => $quiz_type, 
            'quiz_type_id' => $term->term_id, 
            'time' => $current_time,
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ));
    
        // Check for errors
        if ($database === false) {
            // Log error if insert failed 
            echo "<script type='text/javascript'>alert('" . $wpdb->last_error . "');</script>";
            return;
        }
    }


} else {
    $existing_result = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}wiz_results WHERE quiz_id = %d AND user_id = %d",
        $quiz_id,
        $user_id
    ));
    if (! $existing_result) {
        // Insert new data if no existing record
        $database = $wpdb->insert($wpdb->prefix . "wiz_results", array(
            'user_id' => $user_id,
            'user_name' => $user_name,
            'device_id' => $device_id,
            'quiz_id' => $quiz_id,
            'total_time' => $total_time,
            'date' => current_time('mysql'),
            'quiz_type' => $quiz_type,
            'quiz_type_id' => $term->term_id,
            'time' => $current_time,
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ));

        // Check for errors
        if ($database === false) {
            // Log error if insert failed 
            echo "<script type='text/javascript'>alert('" . $wpdb->last_error . "');</script>";
            return;
        }
    }
}
 
// Arguments to retrieve all posts associated with the current taxonomy term
$args = array(
    'post_type'      => array('mathematical-reasoni', 'reading', 'thinking-skill', 'writing'), // Include the post types
    'tax_query'      => array(
        array(
            'taxonomy' => $term->taxonomy, // Use the current taxonomy of the term
            'field'    => 'term_id', // You can also use 'slug' or 'name'
            'terms'    => $term->term_id,
        ),
    ),
    'orderby'        => 'date', // Sort by date
    'order'          => 'ASC',  // Oldest posts first
    'posts_per_page' => -1, // Get all posts, adjust the number if you want pagination
); 
// Get the posts associated with the current term
$posts = get_posts($args);
// Count the number of posts
$post_count = count($posts);

// Localize the script with data
wp_localize_script('wiz-quiz-script', 'wizQuizData', array(
    'post_count' => $post_count,
    'device_id' => $device_id,
    'user_id' => $user_id,
    'quiz_id' => $quiz_id,
    'user_name' => $user_name,
    'time' => $current_time,
    'term_id' => $term->term_id,
));

/* ---------- Main Quiz contents Start ---------- */
require_once WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/body.php';

/* ---------- Main Quiz contents End ---------- */

// Get Footer
wp_footer();
