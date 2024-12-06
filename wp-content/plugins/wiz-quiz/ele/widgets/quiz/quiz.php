<?php

/**
 * Template for displaying the 'reading-practice' taxonomy archive page.
 * 
 * @package Wiz Quiz
 */

// Get Header
wp_head();

// Get the current taxonomy term
$term = get_queried_object();

// Log the term information to debug
error_log('Current Term: ' . print_r($term, true));

// Arguments to retrieve all posts associated with the current taxonomy term
$args = array(
    'post_type'      => array('mathematical-reasoning', 'reading', 'thinking-skill', 'writing'), // Include the post types
    'tax_query'      => array(
        array(
            'taxonomy' => $term->taxonomy, // Use the current taxonomy of the term
            'field'    => 'term_id', // You can also use 'slug' or 'name'
            'terms'    => $term->term_id,
        ),
    ),
    'posts_per_page' => -1, // Get all posts, adjust the number if you want pagination
);

// Get the posts associated with the current term
$posts = get_posts($args);
//error log count posts
// error_lg post for each 
foreach ($posts as $post) {
    # code...
    error_log('' . print_r($post, true));
}
// Log the posts to debug

/* ---------- Main Quiz contents Start ---------- */
require_once WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/body.php';

/* ---------- Main Quiz contents End ---------- */

// Get Footer
wp_footer();
