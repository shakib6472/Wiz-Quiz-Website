<?php
// Hook to add admin menu
add_action('admin_menu', 'wiz_quiz_admin_menu');

function wiz_quiz_admin_menu()
{
    add_menu_page(
        ' Student Test Attempts', // Page title
        'Student Test Attempts', // Menu title
        'manage_options', // Capability
        'wiz-quiz', // Menu slug
        'wiz_quiz_admin_page', // Function to display the page content
        'dashicons-welcome-learn-more', // Icon URL
        6 // Position
    );

    // Add subpage under the main menu for Reading Test

    add_submenu_page(
        'wiz-quiz', // Parent slug
        'Reading Results', // Page title
        'Reading Results', // Menu title
        'manage_options', // Capability
        'wiz-quiz-reading', // Menu slug
        'wiz_quiz_reading_page' // Function to display the page content
    );
    // Add subpage under the main menu for Mathematical Resoaning Tes 
    add_submenu_page(
        'wiz-quiz', // Parent slug
        'Math Reasoning Results', // Page title
        'Math Reasoning Results', // Menu title
        'manage_options', // Capability
        'wiz-quiz-mathematical', // Menu slug
        'wiz_quiz_mathematical_page' // Function to display the page content
    );
    // Add subpage under the main menu for Thinking Skil Test
    add_submenu_page(
        'wiz-quiz', // Parent slug
        'Think Skill Results', // Page title
        'Think Skill Results', // Menu title
        'manage_options', // Capability
        'wiz-quiz-thinking', // Menu slug
        'wiz_quiz_thinking_page' // Function to display the page content
    );

    // Add subpage under the main menu for Writting Test
    add_submenu_page(
        'wiz-quiz', // Parent slug
        'Writing Results', // Page title
        'Writing Results', // Menu title
        'manage_options', // Capability
        'wiz-quiz-writtings', // Menu slug
        'wiz_quiz_writting_page' // Function to display the page content
    );
    //add settings submenu
    add_submenu_page(
        'wiz-quiz', // Parent slug
        'Settings', // Page title
        'Settings', // Menu title
        'manage_options', // Capability
        'wiz-quiz-settings', // Menu slug
        'wiz_quiz_settings_page' // Function to display the page content
    );
}
function wiz_quiz_admin_page()
{
    //include All Writtings;
    include WIZ_QUIZ_PLUGIN_DIR . 'admin/main.php';
}
function wiz_quiz_writting_page()
{
    //include All Writtings;
    include WIZ_QUIZ_PLUGIN_DIR . 'admin/writting.php';
}
function wiz_quiz_reading_page()
{
    //include All Writtings;
    include WIZ_QUIZ_PLUGIN_DIR . 'admin/reading.php';
}
function wiz_quiz_thinking_page()
{
    //include All Writtings;
    include WIZ_QUIZ_PLUGIN_DIR . 'admin/thinking.php';
}
function wiz_quiz_mathematical_page()
{
    //include All Writtings;
    include WIZ_QUIZ_PLUGIN_DIR . 'admin/mathematical.php';
}

function wiz_quiz_settings_page()
{
    //include All Writtings;
    include WIZ_QUIZ_PLUGIN_DIR . 'admin/settings.php';
}
