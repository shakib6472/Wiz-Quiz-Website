<?php

/**
 * Template for displaying the 'reading-practice' taxonomy archive page.
 * 
 * @package Wiz Quiz
 * @ main $post,  $post_id
 */
$slide = isset($_GET['slide']) ? $_GET['slide'] : false;
?>
<?php
if (!$slide) {
?>

    <div class="option-container dragable-container scrollbar">
        <!-- Options -->
        <div class="options" id="options">

            <?php
            $total_option = get_post_meta($post_id, 'drag_&_drop', true);
            $total_ext_option = get_post_meta($post_id, 'extra_dragable_option', true);

            $options = [];

            for ($i = 0; $i < $total_option; $i++) {
                $opt_key = 'drag_&_drop_' . $i . '_answer';
                $opt_name = get_post_meta($post_id, $opt_key, true);
                $options[] = [
                    'id' => $i,
                    'name' => $opt_name
                ];
            }

            for ($i = 0; $i < $total_ext_option; $i++) {
                $opt_key = 'extra_dragable_option_' . $i . '_extra_option';
                $opt_name = get_post_meta($post_id, $opt_key, true);
                $options[] = [
                    'id' => floatval($total_option) + $i,
                    'name' => $opt_name
                ];
            }

            // Shuffle the options array
            shuffle($options);
            shuffle($options); // Shuffle again to make sure

            foreach ($options as $option) {
            ?>
                <div class="drag_option" data-option-id="<?php echo $option['id']; ?>">
                    <?php echo $option['name']; ?>
                </div>
            <?php
            }
            ?>
        </div>
        <!-- Answer Boxes -->
        <div class="answer-boxes" id="answer-boxes">
            <?php
            for ($i = 0; $i < $total_option; $i++) {
                $opt_key = 'drag_&_drop_' . $i . '_question';
                $opt_name = get_post_meta($post_id, $opt_key, true);
            ?>

                <div class="ans-group">
                    <div class="group-name"> <?php echo $opt_name; ?> </div>
                    <div class="answer-box" data-que-id="<?php echo $i; ?>">
                        <span class="placeholder">Drop here</span>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>
    </div>

<?php
} else {
?>
    <div class="option-container dragable-container scrollbar desable">

        <?php
        $tax = $term->taxonomy;

        $existing_result = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wiz_results WHERE quiz_id = %d",
            $quiz_id
        ));

        $results = json_decode($existing_result->result, true);

        foreach ($results as $key => $value) {
            if ($value['question_id'] == $post_id) {
                $index = $key;
            } else {
                $index = '';
            }
        }
        if ($index != '') {
            $given_answers = $results[$index]['answer_given'];
        } else {
            $given_answers = [];
        }
        $total_option = get_post_meta($post_id, 'drag_&_drop', true);
        $total_ext_option = get_post_meta($post_id, 'extra_dragable_option', true);
        ?>
        <div class="answer-boxes" id="answer-boxes">
            <?php
            for ($i = 0; $i < $total_option; $i++) {
                $opt_key = 'drag_&_drop_' . $i . '_question';
                $opt_name = get_post_meta($post_id, $opt_key, true);
                $original_answer = get_post_meta($post_id, 'drag_&_drop_' . $i . '_answer', true);
                //remove all spaced from right answer & others like line beaks
                $formatted_original_answer = str_replace(array(" ", "\n", "\r"), '', $original_answer);
                $formatted_original_answer = preg_replace('/[^A-Za-z0-9\-]/', '', $formatted_original_answer);
                if ($index != '') {
                    $given_answer = $given_answers[$i]['option'];
                } else {
                    $given_answer = '';
                }
                $formatated_given_answer = str_replace(array(" ", "\n", "\r"), '', $given_answer);
                $formatated_given_answer = preg_replace('/[^A-Za-z0-9\-]/', '', $formatated_given_answer);

                if ($formatted_original_answer == $formatated_given_answer) {
                    $class = 'answer_right';
                } else {
                    $class = 'answer_wrong';
                }
            ?>

                <div class="ans-group <?php echo $class; ?>">
                    <div class="flex-starting-ans-group">
                        <div class="group-name"> <?php echo $opt_name; ?> </div>
                        <div class="answer-box" data-que-id="<?php echo $i; ?>">
                            <div class="drag_optiona" data-option-id="<?php echo $i; ?>">
                                <?php echo $given_answer; ?>
                            </div>
                        </div>
                    </div>

                    <p class="answer_feeback"> <strong>The Right Answer is </strong> <?php echo $original_answer; ?> </p>
                </div>
            <?php
            }

            //include answer explanatoion file
            include WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/main/explaination.php';
            ?>

        </div>
    </div>
<?php
}
?>