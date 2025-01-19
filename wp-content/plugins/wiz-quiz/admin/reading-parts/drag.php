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
<div class="q-options">
    <div class="option-container drag_drop_coontainer scrollbar desable">
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

        ?>
        <div class="left-side"> 
        <div class="answer-boxes" id="answer-boxes">
            <?php
            for ($i = 0; $i < $total_option; $i++) {
                $opt_key = 'drag_&_drop_' . $i . '_question';
                $opt_name = get_post_meta($post_id, $opt_key, true);
                $original_answer = get_post_meta($post_id, 'drag_&_drop_' . $i . '_answer', true);
                $formatted_original_answer = str_replace(array(" ", "\n", "\r"), '', $original_answer);
                $formatted_original_answer = preg_replace('/[^A-Za-z0-9\-]/', '', $formatted_original_answer);
                if ($answer_given != '') {
                    if (isset($answer_given[$i])) {
                        $given_answer = $answer_given[$i]['option'];
                        $trimed_given_answer = str_replace(array(" ", "\n", "\r"), '', $given_answer); // trim the given answer
                        // unset it from $options
                        foreach ($options as $key => $option) {
                            $trimed_option = str_replace(array(" ", "\n", "\r"), '', $option['name']);

                            if ($trimed_option == $trimed_given_answer) {
                                unset($options[$key]);
                                break;
                            }
                        }
                    } else {
                        $given_answer = '';
                    }
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
                            <div class="drag_option" data-option-id="<?php echo $i; ?>">
                                <?php echo $given_answer; ?>
                            </div>
                        </div>
                    </div>

                    <p class="answer_feeback"> <strong>The Right Answer is </strong> <?php echo $original_answer; ?> </p>
                </div>
            <?php
            }
            ?>


        </div>
        </div>
        <div class="right-side"> 
            <div class="" id="options">
                <?php
                foreach ($options as $option) {
                ?>
                    <div class="drag_option" data-option-id="<?php echo $option['id']; ?>">
                        <?php echo $option['name']; ?>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="savethis">
    <div class="wiz-btn">
        <div class="btn update_drag_answer" data-quiz_id="<?php echo $_GET['quiz_id']; ?>" data-que="<?php echo $post_id;  ?>">Update This Answer</div>
    </div>
</div>