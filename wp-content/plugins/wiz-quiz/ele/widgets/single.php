<?php 
/* 
* The Single QUiz Preview Page
*/
wp_head();
$post_id = get_the_ID();
?>
<div class="wiz_container">
    <!-- Main Quiz - Header   Area  -->
    <?php // require_once WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/header.php'; ?>
    <!-- Main Quiz - Main Area  -->
    <?php  require_once WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/preview.php'; ?>
    <!-- Main Quiz - Footer Area  -->
    <?php  require_once WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/back.php'; ?>

</div>

<?php
wp_footer();