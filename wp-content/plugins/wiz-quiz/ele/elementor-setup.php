<?php 

//Elementor Setup
function wiz_quiz_register_widgets($widgets_manager)
{
    require_once(WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/taxonomy.php');
    require_once(WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/quiz_widget.php');
    $widgets_manager->register(new \Elementor_wiz_taxonomy());
    $widgets_manager->register(new \Elementor_wiz_main_quiz());
}
add_action('elementor/widgets/register', 'wiz_quiz_register_widgets');
