<?php

/**
 * Template for displaying the 'reading-practice' taxonomy archive page.
 * 
 * @package Wiz Quiz
 */

// Get Header
wp_head();
global $wpdb;
// Get the current taxonomy term
$term = get_queried_object();
$term_id = $term->term_id;
$term_name = $term->name;
$tax = get_term($term_id)->taxonomy;

// Get the term meta data "
$publish_time = get_term_meta($term_id, 'publish_time', true); 
$publish_time = strtotime($publish_time);
$now = time();
if ($now < $publish_time) {
    get_header();
    // max Attempt check 
?>
    <div class="wiz_container time_container">
        <div class="timer-prebox">
            <h1>Tick Tock! The Quiz Clock Is Running!</h1>
            <h4>Your next test is only available for:</h4>
            <div class="timer-box">
                <div class="timer">
                    <div class="timer__header">
                        <h3 id="countdown-text">
                            0 Day 0 Hours 0 Minutes 0 Seconds
                        </h3>
                    </div>
                </div>
                <div class="image">
                    <img src="<?php echo WIZ_QUIZ_PLUGIN_URL . 'assets/css/timeleft.png'; ?>" alt="" />
                </div>
            </div>
        </div>
    </div>
    <?php
    get_footer();
    ?>
    <script>
        jQuery(document).ready(function($) {
            // Set the target date
            const targetDate = new Date("<?php echo $publish_time; ?>");

            function updateCountdown() {
                const now = new Date();
                const timeLeft = targetDate - now;

                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    $("#countdown-text").text("Time's up!");
                    return;
                }

                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor(
                    (timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
                );
                const minutes = Math.floor(
                    (timeLeft % (1000 * 60 * 60)) / (1000 * 60)
                );
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                // Format the countdown as a single text
                const countdownText = `${days} Day${days !== 1 ? "s" : ""} ${hours
            .toString()
            .padStart(2, "0")} Hours ${minutes
            .toString()
            .padStart(2, "0")} Minutes ${seconds
            .toString()
            .padStart(2, "0")} Seconds`;

                $("#countdown-text").text(countdownText);
            }

            // Update the countdown every second
            const countdownInterval = setInterval(updateCountdown, 1000);
            updateCountdown();
        });
    </script>

    <?php
} else {
    $max_attempt = get_term_meta($term_id, 'max_attempt', true);
    // get quiz from database for this user & check how many time he/she attempted with this term id (quiz_type_id)
    $tempcurrent_user = get_current_user_id();
    if ($tempcurrent_user) {
        $tempcurrent_use = get_user_by('id', $tempcurrent_user);
    } elseif (isset($_COOKIE['user_name'])) {
        $tempuser_name = $_COOKIE['user_name'];
    }
    $quiz_results_table = $wpdb->prefix . 'wiz_results';
    $quiz_wrttings_results_table = $wpdb->prefix . 'wiz_writting_results';
    $device_id = sanitize_text_field($_SERVER['REMOTE_ADDR']); // sanitize IP address
    $result_query = $wpdb->prepare(
        "SELECT * FROM $quiz_results_table WHERE user_id = %d AND quiz_type_id = %d ORDER BY id DESC",
        $tempcurrent_user,
        $term_id
    );
    $writting_result_query = $wpdb->prepare(
        "SELECT * FROM $quiz_wrttings_results_table WHERE user_id = %d AND quiz_type_id = %d ORDER BY id DESC",
        $tempcurrent_user,
        $term_id
    );

    $quiz_results = $wpdb->get_results($result_query);
    $writting_result = $wpdb->get_results($writting_result_query);
    $quiz_results = array_filter($quiz_results, function ($result) {
        return $result->result !== '';
    });
    $writting_result = array_filter($writting_result, function ($result) {
        return $result->result !== '';
    });
    // merger the two arrays
    $all_results = array_merge($quiz_results, $writting_result); 

    if (count($all_results) >= $max_attempt) {
        get_header();
    ?>
        <div class="wiz_container time_container">
            <h1>Sorry! You have reached the maximum number of attempts for this quiz.</h1>
            <h3>Maximum Allowed Number or Attempt is <?php echo $max_attempt; ?></h3>
            <h3>You have already taken attempted <?php echo count($all_results); ?></h3>
        </div>
<?php
        get_footer();
        return;
    }


    error_log('Taxonomy: ' . $tax);
    if ($tax == 'reading-practice') {
        $quiz_name = 'Reading';
    } else if ($tax == 'mathenatical-practice') {
        $quiz_name = 'Mathematical Reasoning';
    } else if ($tax == 'thinking-skill') {
        $quiz_name = 'Thinking Skill';
    } else if ($tax == 'writtings-practice') {
        $quiz_name = 'Writting';
    }

    //insert a new row in database for this quiz
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
    } else {
        $user_id = 0;
    }
    // Call the function to get the device ID
    $device_id = $_SERVER['REMOTE_ADDR'];
    $quiz_id = $_GET['quizid'];
    $quiz_type = $term->taxonomy;
    $total_time = 0;
    $current_time = time();
    $timezone = isset($_COOKIE['user_gmt_offset']) ? $_COOKIE['user_gmt_offset'] : '+0 UTC';
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $user_name =   $current_user->user_firstname . ' ' . $current_user->user_lastname;
    } elseif (isset($_COOKIE['user_name'])) {
        $user_name = $_COOKIE['user_name'];
    }

    if (is_tax('writtings-practice')) {
        $existing_result = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wiz_writting_results WHERE quiz_id = %d AND user_id = %d",
            $quiz_id,
            $user_id
        ));

        if (! $existing_result) {
            // Insert new data if no existing record
            $database = $wpdb->insert($wpdb->prefix . "wiz_writting_results", array(
                'user_id' => $user_id,
                'user_name' => $user_name,
                'device_id' => $device_id,
                'quiz_id' => $quiz_id,
                'total_time' => $total_time,
                'date' => current_time('mysql'),
                'quiz_type' => $quiz_type,
                'quiz_type_id' => $term->term_id,
                'time' => $current_time,
                'timezone' => $timezone,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ));

            // Check for errors
            if ($database === false) {
                // Log error if insert failed 
                echo "<script type='text/javascript'>alert('" . $wpdb->last_error . "');</script>";
                return;
            }
        }
    } else {
        $existing_result = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wiz_results WHERE quiz_id = %d AND user_id = %d",
            $quiz_id,
            $user_id
        ));
        if (! $existing_result) {
            // Insert new data if no existing record
            $database = $wpdb->insert($wpdb->prefix . "wiz_results", array(
                'user_id' => $user_id,
                'user_name' => $user_name,
                'device_id' => $device_id,
                'quiz_id' => $quiz_id,
                'total_time' => $total_time,
                'date' => current_time('mysql'),
                'quiz_type' => $quiz_type,
                'quiz_type_id' => $term->term_id,
                'time' => $current_time,
                'timezone' => $timezone,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ));

            // Check for errors
            if ($database === false) {
                // Log error if insert failed 
                echo "<script type='text/javascript'>alert('" . $wpdb->last_error . "');</script>";
                return;
            }
        }
    }
    // Arguments to retrieve all posts associated with the current taxonomy term
    $args = array(
        'post_type'      => array('mathematical-reasoni', 'reading', 'thinking-skill', 'writing'), // Include the post types
        'tax_query'      => array(
            array(
                'taxonomy' => $term->taxonomy, // Use the current taxonomy of the term
                'field'    => 'term_id', // You can also use 'slug' or 'name'
                'terms'    => $term->term_id,
            ),
        ),
        'orderby'        => 'date', // Sort by date
        'order'          => 'ASC',  // Oldest posts first
        'posts_per_page' => -1, // Get all posts, adjust the number if you want pagination
    );
    // Get the posts associated with the current term
    $posts = get_posts($args);
    // Count the number of posts
    $post_count = count($posts);


    // Localize the script with data
    wp_localize_script('wiz-quiz-script', 'wizQuizData', array(
        'post_count' => $post_count,
        'device_id' => $device_id,
        'user_id' => $user_id,
        'quiz_id' => $quiz_id,
        'user_name' => $user_name,
        'time' => $current_time,
        'term_id' => $term->term_id,
    ));

    /* ---------- Main Quiz contents Start ---------- */
    require_once WIZ_QUIZ_PLUGIN_DIR . 'ele/widgets/quiz/parts/body.php';

    /* ---------- Main Quiz contents End ---------- */
}
// Get Footer
wp_footer();
