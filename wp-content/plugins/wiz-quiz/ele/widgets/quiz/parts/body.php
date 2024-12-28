<?php

/**
 * Template for displaying the 'reading-practice' taxonomy archive page.
 * 
 * @package Wiz Quiz
 * @ $post,  $post_id , $device_id, $quiz_id,$user_id,$term_id
 */

?> 
<!-- Initializing Pre loader -->
 

<div class="preloader">
    <span class="loader">
        <h2>Wiz Quiz</h2>
    </span>
</div>

<!-- Pagination Area -->

<?php require_once WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/pagination.php'; ?>

<!-- Main Quiz Area -->

<div class="wiz_container">
    <!-- Main Quiz - Header   Area  -->
    <?php require_once WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/header.php'; ?>
    <!-- Main Quiz - Main Area  -->
    <?php  require_once WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/main.php'; ?>
    <!-- Main Quiz - Footer Area  -->
    <?php require_once WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/footer.php'; ?>

</div>