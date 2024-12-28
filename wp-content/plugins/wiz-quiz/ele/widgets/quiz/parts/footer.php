<?php

/**
 * Template Footer
 * 
 * @package Wiz Quiz
 */

$quiz_id = $_GET['quizid'];
$slide = isset($_GET['slide']) ? $_GET['slide'] : false; 

if (!$slide) {
?>

    <footer>
        <div class="footer">
            <div class="left">
                <div class="button">
                    <div class="wiz-btn btn-next prev-quiz">
                        <i class="fas fa-caret-left"></i>Prev
                    </div>
                </div>
            </div>

            <div class="right">
                <div class="button">
                    <div class="wiz-btn btn-flag flag-quiz" data-que="i">
                        <span>Flag</span> <i class="fas fa-flag"></i>
                    </div>
                </div>
                <div class="button">
                    <div class="wiz-btn btn-next next-quiz">
                        Next <i class="fas fa-caret-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>

<?php
} else {
?>
    <footer>
        <div class="footer">
            <div class="left">
                <!-- <div class="button">
                    <a href="<?php // echo home_url('results/?quiz_id=') . $_GET['quizid'] . '&term=' . $_GET['term'];  ?>" class="wiz-btn btn-next back-quiz">
                        <i class="fas fa-caret-left"></i> Back to results
                    </a>
                </div> -->
            </div>

            <div class="right">
                <!-- <div class="button">
                    <div class="wiz-btn btn-flag flag-quiz">
                        Flag <i class="fas fa-flag"></i>
                    </div>
                </div> -->
                <!-- <div class="button">
                    <div class="wiz-btn btn-next next-quiz-non-update">
                        Next <i class="fas fa-caret-right"></i>
                    </div>
                </div> -->
                <div class="button">
                    <a href="<?php echo home_url('results/?quiz_id=') . $_GET['quizid'] . '&term=' . $_GET['term'];  ?>" class="wiz-btn btn-next back-quiz">
                         Back to results <i class="fas fa-caret-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
<?php
}
