<?php
/* 
  * This is the MCQ part of the reading practice quiz
  * 
  * @package WizIQ Quiz / Admin / Reading
  * @subpackage MCQ
  * @since 1.0
  */

?>
<div class="question">
    <h3><?php echo ($index + 1) . '. ' . $question_title; ?></h3>
</div>
<?php
$existing_result = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}wiz_results WHERE quiz_id = %d",
    $action
));
$results = json_decode($existing_result->result, true);
foreach ($results as $key => $value) {
    if ($value['question_id'] == $post_id) {
        $index = $key;
    } else {
        $index = '';
    }
}

$given_answer = $results[$index]['answer_given']; 
$total_extract = get_post_meta($post_id, 'add_extracts', true);
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
                <select data-index="<?php echo $i; ?>" id="q<?php echo $post_id . $i; ?>" name="q<?php echo $post_id . $i; ?>">
 
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
    ?>
</div>

<div class="savethis">
    <div class="wiz-btn">
        <div class="btn update_multiple_answer" data-quiz_id="<?php  echo $_GET['quiz_id']; ?>" data-que="<?php  echo $post_id; ?>">Update This Answer</div>
    </div>
</div>