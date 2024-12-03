<?php 

//Elementor Setup
function wiz_quiz_register_widgets($widgets_manager)
{
    require_once(WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/taxonomy.php');
    $widgets_manager->register(new \Elementor_wiz_taxonomy());
}
add_action('elementor/widgets/register', 'wiz_quiz_register_widgets');
