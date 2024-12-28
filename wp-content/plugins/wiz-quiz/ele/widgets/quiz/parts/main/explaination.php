<?php

/**
 * Answer Explanation
 */

$answer_explaination = get_post_meta($post_id, 'answer_explaination', true);
?>

<div class="answer-explain">
    <h4>Answer Explanation</h4>
    <hr class="divider">
    <div class="answer"><?php echo $answer_explaination; ?></div>
</div>