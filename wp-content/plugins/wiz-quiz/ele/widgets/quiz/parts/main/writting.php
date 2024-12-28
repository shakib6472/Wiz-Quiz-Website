<?php

/**
 * Template 
 * 
 * @package Wiz Quiz
 * @ All $posts 
 */
$slide = isset($_GET['slide']) ? $_GET['slide'] : false;
?>
<?php
if (!$slide) {
?>


    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <div class="option-container scrollbar">
        <div id="editor"></div>
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
    $given_answer = $results[0]['answer_given']; 
?>
    <div class="option-container scrollbar desable">
        <h4 id="feedback_answer_u">Your Answer</h4>
        <div id="youranswer"><?php echo $given_answer; ?></div>
        <hr class="divider">
        <h4 id="feedback_answer_h">Feedback on Your Answer</h4>
        <div id="feedback_answer"><?php echo stripslashes($results[0]['updated_answer']); ?></div>
        <hr class="divider">
        <div class="points">
            <div class="comments">
                <h4>Comment</h4>
                <hr class="divider">
                <div class="comment"> <?php echo $results[0]['comment']; ?></div>
            </div>
            <div class="point">
                <h4 style="font-size:16px">Your Point</h4>
                <hr class="divider">
                <h3 class="point"><?php echo $results[0]['gain_point'] .'/'. $results[0]['total_point']; ?></h3>

            </div>
        </div>

        <?php //include answer explanatoion file
        include WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/main/explaination.php';
         ?>

    </div>
<?php
}
?>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    var quill = new Quill("#editor", {
        theme: "snow",
        modules: {
            toolbar: [
                [{
                        header: "1",
                    },
                    {
                        header: "2",
                    },
                    {
                        font: [],
                    },
                ],
                [{
                        list: "ordered",
                    },
                    {
                        list: "bullet",
                    },
                ],
                ["bold", "italic", "underline", "strike"],
                [{
                    align: [],
                }, ],
                ["link"],
                ["blockquote", "code-block"],
                [{
                        script: "sub",
                    },
                    {
                        script: "super",
                    },
                ],
                ["clean"],
                [{
                    'color': []
                }, {
                    'background': []
                }] // Correctly add 'color' and 'background' formats
            ],
        },
        placeholder: "Type your text here...",
        formats: ["bold", "italic", "underline", "strike", "list", "header", "link", "blockquote", "code-block", "color", "background"], // Include 'color' and 'background' formats
    });

    // Disable spellcheck to prevent Grammarly interference
    document.getElementById('editor').setAttribute('spellcheck', 'false');
</script>


<?php
