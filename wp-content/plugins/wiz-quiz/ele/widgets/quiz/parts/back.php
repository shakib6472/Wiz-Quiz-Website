<?php

/**
 * Template Footer
 * 
 * @package Wiz Quiz
 */

$post_edit_link = get_edit_post_link($post_id);
?>
<style>
    .middle {
        width: 100%;
        text-align: center;
    }
</style>
<footer>
    <div class="footer">
        <div class="middle">
            <div class="button">
                <a href="<?php echo $post_edit_link; ?>" class="wiz-btn btn-next next-quiz">
                    Back <i class="fas fa-caret-right"></i>
                </a>
            </div>
        </div>
    </div>
</footer>