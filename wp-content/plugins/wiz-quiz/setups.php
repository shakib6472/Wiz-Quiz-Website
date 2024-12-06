<?php 

function wiz_quiz_main_template( $template ) {
    // Check if we're on the 'reading-practice' taxonomy archive page
    if ( is_tax( 'reading-practice' ) ) {
        // Define the path to your custom template  
        $quiz_template = WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/quiz.php'; 

        // Check if the template exists, then use it
        if ( file_exists( $quiz_template ) ) { 
            return $quiz_template;
        } else {
            // Log a message if the template is not found
           echo 'Template not found';
        }
    }
    
    // Return the default template if no custom template is found
    return $template;
}
add_filter( 'template_include', 'wiz_quiz_main_template' );
