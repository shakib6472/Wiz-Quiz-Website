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
     <h3><?php echo  ($index + 1). '. ' . $question_title; ?></h3>
 </div>
 <div class="q-options">
     <div class="option-container scrollbar desable">
         <?php 
            $total_option = get_post_meta($post_id, 'mcq_options_', true);
            $given_answer = $answer['answer_given']; 
            for ($mcindex = 0; $mcindex < $total_option; $mcindex++) {
                $opt_key = 'mcq_options__' . $mcindex . '_mcq_option';
                $optimg_key = 'mcq_options__' . $mcindex . '_image'; 
                $opt_name = get_post_meta($post_id, $opt_key, true) ? get_post_meta($post_id, $opt_key, true) : get_post_meta($post_id, $optimg_key, true);
                
                $right_anser = get_post_meta($post_id, 'mcq_options__' . $mcindex . '_right', true) ? get_post_meta($post_id, 'mcq_options__' . $mcindex . '_right', true) : false;
                if ($right_anser) { 
                    $right_anser_text = get_post_meta($post_id, $opt_key, true) ? get_post_meta($post_id, $opt_key, true) : get_post_meta($post_id, $optimg_key, true);
                 
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
                 <input class="<?php echo 'answer_' . $answer_correct; ?>"  <?php echo $answer_match; ?> type="radio" value="<?php echo $opt_name; ?>" id="q_<?php echo $post_id; ?>_option_<?php echo $mcindex; ?>" name="q<?php echo $post_id; ?>-ans" />
                 <?php echo get_post_meta($post_id, $opt_key, true) ? $opt_name : ''; ?>
                 <?php if ($img_id) { ?>
                     <img src="<?php echo $img_url; ?>" alt="<?php echo $opt_name; ?>" height="<?php echo $img_height; ?>" width="<?php echo $img_width; ?>" />
                 <?php } ?>
             </label>

         <?php

            } 
            ?> 
     </div>
 </div>
 <div class="savethis">
     <div class="wiz-btn">
         <div class="btn update_mcq_answer" data-quiz_id="<?php echo $_GET['quiz_id']; ?>" data-que="<?php echo $post_id; ?>">Update This Answer</div>
     </div>
 </div>