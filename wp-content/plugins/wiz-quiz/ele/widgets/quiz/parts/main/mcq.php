<?php

/**
 * Template 
 * 
 * @package Wiz Quiz
 * @ All $posts $post_id
 */
$slide = isset($_GET['slide']) ? $_GET['slide'] : false;

if (!$slide) {
?>

    <div class="option-container scrollbar">
        <?php
        for ($mcindex = 0; $mcindex < $total_option; $mcindex++) {
            $opt_key = 'mcq_options__' . $mcindex . '_mcq_option';
            $optimg_key = 'mcq_options__' . $mcindex . '_image';
            $opt_name = get_post_meta($post_id, $opt_key, true);
            $img_id = get_post_meta($post_id, $optimg_key, true);
            $img_url = wp_get_attachment_url($img_id);
            $img_height = get_post_meta($post_id, 'mcq_options__' . $mcindex . '_image_height', true);
            $img_width = get_post_meta($post_id, 'mcq_options__' . $mcindex . '_image_width', true);
        ?>
            <label for="q_<?php echo $post_id; ?>_option_<?php echo $mcindex; ?>" class="option" data-ans="1" data-que="<?php echo $post_id; ?>">
                <input type="radio" value="<?php echo $opt_name; ?>" id="q_<?php echo $post_id; ?>_option_<?php echo $mcindex; ?>" name="q<?php echo $post_id; ?>-ans" />
                <?php echo $opt_name; ?>
                <?php if ($img_id) { ?>
                    <img src="<?php echo $img_url; ?>" alt="<?php echo $opt_name; ?>" height="<?php echo $img_height; ?>" width="<?php echo $img_width; ?>" />
                <?php } ?>
            </label>
        <?php
        }
        ?>
    </div>



<?php
} else { 
    $tax = $term->taxonomy;
    if ($tax != 'writtings-practice') {
        $existing_result = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wiz_results WHERE quiz_id = %d",
            $quiz_id
        ));
    } else {
        $existing_result = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wiz_writting_results WHERE quiz_id = %d",
            $quiz_id
        ));
    }
    $results = json_decode($existing_result->result, true);

    foreach ($results as $rindex => $result) {
        $question_id = $result['question_id'];
        if ($post_id == $question_id) { 
            $given_answer = $result['answer_given'];
            break;
        }
    }
?>
    <div class="option-container scrollbar desable">
        <?php
        for ($mcindex = 0; $mcindex < $total_option; $mcindex++) {
            $opt_key = 'mcq_options__' . $mcindex . '_mcq_option';
            $optimg_key = 'mcq_options__' . $mcindex . '_image';
            $opt_name = get_post_meta($post_id, $opt_key, true);
            $right_anser = get_post_meta($post_id, 'mcq_options__' . $mcindex . '_right', true) ? get_post_meta($post_id, 'mcq_options__' . $mcindex . '_right', true) : false;
            if ($right_anser) { 
                $right_anser_text = get_post_meta($post_id, $opt_key, true);
            } else { 
                $right_anser_text = '';
            }
            if ($opt_name == $right_anser_text) {
                $answer_correct = 'right';
            } else {
                $answer_correct = 'wrong';
            }
            if ($given_answer == $opt_name) {
                $answer_match = 'checked';
            } else {
                $answer_match = '';
            }

            $img_id = get_post_meta($post_id, $optimg_key, true);
            $img_url = wp_get_attachment_url($img_id);
            $img_height = get_post_meta($post_id, 'mcq_options__' . $mcindex . '_image_height', true);
            $img_width = get_post_meta($post_id, 'mcq_options__' . $mcindex . '_image_width', true);
        ?>
            <label for="q_<?php echo $post_id; ?>_option_<?php echo $mcindex; ?>" class="option <?php echo 'answer_' . $answer_correct; ?>" data-ans="1" data-que="<?php echo $post_id; ?>">
                <input class="<?php echo 'answer_' . $answer_correct; ?>" disabled <?php echo $answer_match; ?> type="radio" value="<?php echo $opt_name; ?>" id="q_<?php echo $post_id; ?>_option_<?php echo $mcindex; ?>" name="q<?php echo $post_id; ?>-ans" />
                <?php echo $opt_name; ?>
                <?php if ($img_id) { ?>
                    <img src="<?php echo $img_url; ?>" alt="<?php echo $opt_name; ?>" height="<?php echo $img_height; ?>" width="<?php echo $img_width; ?>" />
                <?php } ?>
            </label>

        <?php

        }
         //include answer explanatoion file
        include WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/main/explaination.php';
         ?>
    </div>
<?php
}
