<?php

/**
 * Template for displaying the 'reading-practice' taxonomy archive page.
 * 
 * @package Wiz Quiz
 * @ All $posts 
 */

$slide = isset($_GET['slide']) ? $_GET['slide'] : false;
if ($slide) {
    $style = 'style="display: none;"';
} else {
    $style = '';
}
?>

<main>
    <?php
    $post = get_post(get_the_ID());

    if ($post) {
        $post_id = get_the_ID();
        $post_title = get_the_title($post_id);
        // Get all meta data for the post
        $post_meta = get_post_meta($post_id);
        foreach ($post_meta as $meta_key => $meta_values) {
            // error_log('Meta Key: ' . $meta_key . ' | Meta Value: ' . implode(', ', $meta_values));
        }
        $question_type = get_post_meta($post_id, 'question_type', true);
        $total_extract = get_post_meta($post_id, 'add_extracts', true);
        $total_option = get_post_meta($post_id, 'mcq_options_', true);

        $actiavtion_class_slider = 'active';


    ?>
        <div class="slides slide-1 <?php echo $actiavtion_class_slider; ?>" data-que-type="<?php echo $question_type; ?>" data-slide="1" data-que="<?php echo $post_id; ?>">
            <div class="contents" tabindex="0">
                <div class="left" tabindex="1">
                    <div class="extracts" tabindex="2">
                        <?php
                        if ($total_extract > 1) {
                        ?>

                            <div class="extract-heads">
                                <?php
                                for ($i = 0; $i < $total_extract; $i++) {
                                    $ext_key = 'add_extracts_' . $i . '_extract_name';
                                    $ext_name = get_post_meta($post_id, $ext_key, true);
                                ?>
                                    <div class="heads<?php echo ($i == 0) ? ' active' : ''; ?>" data-extract="add_extracts_<?php echo $i; ?>"><?php echo $ext_name; ?></div>
                                <?php
                                }

                                ?>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="extract-bodys scrollbar">
                            <?php
                            for ($i = 0; $i < $total_extract; $i++) {
                                $extb_key = 'add_extracts_' . $i . '_extract_details';
                                $extb_name = get_post_meta($post_id, $extb_key, true);
                            ?>
                                <div class="bodys<?php echo ($i == 0) ? ' active' : ''; ?>" data-extract="add_extracts_<?php echo $i; ?>">
                                    <?php echo $extb_name; ?>
                                </div>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>

                <div class="middle">
                    <div class="close-icon">
                        <i class="fas fa-caret-right"></i>
                    </div>
                    <div class="open-icon">
                        <i class="fas fa-caret-left"></i>
                    </div>
                </div>
                <div class="right">

                    <div class="question">
                        <h3><?php echo get_post_meta($post_id, 'question_texts', true); ?></h3>
                    </div>
                    <div class="q-options">
                        <?php
                        if ('MCQ' === $question_type) {
                            include WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/main/mcq.php';
                        } else if ('Drag & Drop' === $question_type) {
                            include WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/main/drag.php';
                        } else if ('Multiple Drop Down' === $question_type) {
                            include WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/main/multiple.php';
                        } else if ('Writings' === $question_type) {
                            include WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/main/writting.php';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</main>
 