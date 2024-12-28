<?php

function wiz_quiz_main_template($template)
{
    // Check if we're on the 'reading-practice' taxonomy archive page
    if (is_tax('reading-practice') || is_tax('mathenatical-practice') || is_tax('thinking-skll-practice') || is_tax('writtings-practice')) {
        // Define the path to your custom template  
        $quiz_template = WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/quiz.php';

        // Check if the template exists, then use it
        if (file_exists($quiz_template)) {
            return $quiz_template;
        } else {
            // Log a message if the template is not found
            echo 'Template not found';
        }
    }
    if (is_page('results')) {
        $result_template = WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/result.php'; 
        // Check if the result template exists
        if (file_exists($result_template)) {  
            return $result_template; 
        } else {
            // Log a message if the template is not found
            echo 'Result template not found';
        }
    }
    if(is_page('dashboard')){
        $dashboard_template = WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/dashboard.php';
        if(file_exists($dashboard_template)){
            return $dashboard_template;
        }else{
            echo 'Dashboard template not found';
        }
    }
    if(is_page('results-details')){
        $dashboard_template = WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/results_details.php';
        if(file_exists($dashboard_template)){
            return $dashboard_template;
        }else{
            echo 'Dashboard template not found';
        }
    }
    //if single quiz page. Quiz post types are 'mathematical-reasoni', 'reading', 'thinking-skill', 'writing'
    if(is_singular(array('mathematical-reasoni', 'reading', 'thinking-skill', 'writing'))){
        $single_quiz_template = WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/single.php';
        if(file_exists($single_quiz_template)){
            return $single_quiz_template;
        }else{
            echo 'Single quiz template not found';
        }
    }


    // Return the default template if no custom template is found
    return $template;
}
add_filter('template_include', 'wiz_quiz_main_template',99); 

//hide admin bar
// add_filter('show_admin_bar', '__return_false');
