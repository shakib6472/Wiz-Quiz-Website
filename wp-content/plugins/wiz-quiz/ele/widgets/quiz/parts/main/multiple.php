<?php

/**
 * Template 
 * 
 * @package Wiz Quiz
 * @ All $post, $post_id
 */
$slide = isset($_GET['slide']) ? $_GET['slide'] : false;

if (!$slide) {
?>

    <!-- Multiple  -->

    <div class="option-container scrollbar">
        <?php
        $total_option = get_post_meta($post_id, 'drop_down_options', true);

        for ($i = 0; $i < $total_option; $i++) {
            $opt_key = 'drop_down_options_' . $i . '_question_text';
            $opt_name = get_post_meta($post_id, $opt_key, true);
        ?>
            <div class="q-multipl-bg">
                <div class="question-row">
                    <label for="q<?php echo $post_id . $i; ?>"> <?php echo $opt_name; ?> </label>
                    <select data-index="<?php echo $i; ?>" id="q<?php echo $post_id . $i; ?>" name="q<?php echo $post_id . $i; ?>">
                        <option value="" selected disabled> -- Select --</option>
                        <?php
                        for ($j = 0; $j < $total_extract; $j++) {
                            $ext_key = 'add_extracts_' . $j . '_extract_name';
                            $ext_name = get_post_meta($post_id, $ext_key, true); ?>
                            <option value="<?php echo $ext_name; ?>"><?php echo $ext_name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
<?php
} else {
    $tax = $term->taxonomy;

    $existing_result = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}wiz_results WHERE quiz_id = %d",
        $quiz_id
    )); 

    $results = json_decode($existing_result->result, true);
    // $given_answer = $results[0]['answer_given'];
    //result lentgh
    $result_length = count($results);
    for ($i = 0; $i < $result_length; $i++) {
        if ($results[$i]['question_id'] == $post_id) {
            $index = $i;
            break;
        }
    }
    // error_log(print_r($results, true));
    $given_answer = $results[$index]['answer_given'];
?>
    <div class="option-container scrollbar desable">
        <?php
        $total_option = get_post_meta($post_id, 'drop_down_options', true);

        for ($i = 0; $i < $total_option; $i++) {
            $opt_key = 'drop_down_options_' . $i . '_question_text';
            $opt_name = get_post_meta($post_id, $opt_key, true);
            $this_answer = $given_answer[$i];
            $original_answer = get_post_meta($post_id, 'drop_down_options_' . $i . '_select_extract', true);
            if ($this_answer['answer'] == $original_answer) {
                $answer_correct = 'right';
            } else {
                $answer_correct = 'wrong';
            }

        ?>
            <div class="q-multipl-bg answer_<?php echo $answer_correct; ?>">
                <div class="question-row">
                    <label for="q<?php echo $post_id . $i; ?>"> <?php echo $opt_name; ?> </label>
                    <select disabled data-index="<?php echo $i; ?>" id="q<?php echo $post_id . $i; ?>" name="q<?php echo $post_id . $i; ?>">

                        <?php
                        for ($j = 0; $j < $total_extract; $j++) {
                            $ext_key = 'add_extracts_' . $j . '_extract_name';
                            $ext_name = get_post_meta($post_id, $ext_key, true);

                            if ($ext_name == $this_answer['answer']) {
                                $selected = 'selected';
                            } else {
                                $selected = '';
                            }
                        ?>
                            <option value="<?php echo $ext_name; ?>" <?php echo $selected; ?>><?php echo $ext_name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <p class="answer_feeback"> <strong>The Right Answer is </strong> <?php echo $original_answer; ?> </p>
            </div>
        <?php
        }
        
        include WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/main/explaination.php';
         
        ?>

    </div>
<?php } ?>