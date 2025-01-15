<?php $slide = isset($_GET['slide']) ? $_GET['slide'] : false;
if ($slide) {
    $instruction_text = 'Question No. ' . $slide;
} else {
    $instruction_text = 'Instructions';
}

$total_munites = get_term_meta($term->term_id, 'add_time_munites', true) ? get_term_meta($term->term_id, 'add_time_munites', true) : 40;
$total_seconds = $total_munites * 60;
if ($existing_result) {
    $start_time = $existing_result->time;
    $now = time();
    $spent_time = $now - $start_time;
} else {
    $spent_time = 0;
}
$remaining_time = $total_seconds - $spent_time;
// format the remaining time like this 40:00 munites
$remaining_minutes = floor($remaining_time / 60);
$remaining_seconds = $remaining_time % 60;

$remaining_time_formatted = sprintf("%d:%d", $remaining_minutes, $remaining_seconds);

?>



<header>
    <div class="header">
        <div class="zoom">
            <div class="btn">
                <i class="fas fa-magnifying-glass-plus"></i>
                <div class="zoom-options">
                    <div class="option active" data-per="100">100%</div>
                    <div class="option" data-per="150">150%</div>
                    <div class="option" data-per="175">175%</div>
                    <div class="option" data-per="200">200%</div>
                    <div class="option" data-per="250">250%</div>
                    <div class="option" data-per="275">275%</div>
                    <div class="option" data-per="300">300%</div>
                </div>
            </div>
            <?php
            if (!$slide) {

            ?>
                <div class="timer">
                    <!-- add 40 munites timer -->
                    <div class="timer-holder">
                        <div class="timer-icon">
                            <i class="fas fa-stopwatch"></i>
                            <div class="times" id="timer"><?php echo $remaining_time_formatted; ?> </div> Minutes

                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="title-area">
            <h2 class="instruction"><?php echo $instruction_text; ?></h2>
            <i class="fas fa-grip pagination-page"> </i>
        </div>
        <div class="logo">
            <?php
            if (function_exists('the_custom_logo')) {
                the_custom_logo();
            }
            ?>
        </div>
    </div>
</header>
<?php
if (!$slide) {  
?>
    <script>
        let time = <?php echo $remaining_time; ?>;
        const timerElement = document.getElementById('timer');

        function formatTime(num) {
            return num < 10 ? `0${num}` : num;
        }
        const timerInterval = setInterval(function updateTimer() {
            if (time <= 0) {
                clearInterval(timerInterval);
                alert('Time is up! let\'s see the result');
                var url = '<?php echo get_home_url() . '/results?quiz_id=' . $quiz_id . '&term=' . $term_id; ?>';
                console.log(url);
                window.location.href = url;
            }
            const minutes = Math.floor(time / 60);
            const seconds = time % 60;
            timerElement.innerHTML = `${formatTime(minutes)}:${formatTime(seconds)}`;
            time--;
        }, 1000);
    </script>

<?php
}
