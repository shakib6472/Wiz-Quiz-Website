<?php

/**
 * Template Dashboard Page
 * 
 * @package Dashboard
 */

// Get Header (this will include the <head> section and wp_head())
get_header();
global $wpdb;
$quiz_results_table = $wpdb->prefix . 'wiz_results';
$quiz_wrttings_results_table = $wpdb->prefix . 'wiz_writting_results'; 
$prname = 'Guest';
// Get the current user if logged in
if (is_user_logged_in()) {
    // user id
    $current_user = get_current_user_id();
    if ($current_user) {
        $current_use = get_user_by('id', $current_user);
        $prname = $current_use->user_firstname . ' ' . $current_use->user_lastname;
    } elseif (isset($_COOKIE['user_name'])) {
        $user_name = $_COOKIE['user_name'];
        $prname = $user_name;
    }
} else {
    $current_user = 0;
}
$device_id = sanitize_text_field($_SERVER['REMOTE_ADDR']); // sanitize IP address
$result_query = $wpdb->prepare(
    "SELECT * FROM $quiz_results_table WHERE user_id = %d AND device_id = %s ORDER BY id DESC",
    $current_user,
    $device_id
);

$writting_result_query = $wpdb->prepare(
    "SELECT * FROM $quiz_wrttings_results_table WHERE user_id = %d AND device_id = %s ORDER BY id DESC",
    $current_user,
    $device_id
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
// sort the array by date
usort($all_results, function ($a, $b) {
    return strtotime($a->date) - strtotime($b->date);
});
  
?>

<div class="wiz_container dashbaord_container">
    <div class="welcome-header">
        <h1 class="welcoming"> GOOD MORNING, <?php echo $prname; ?></h1>
        <?php
        if (!$all_results) {
        ?>
            <div class="dashbaord_container" style="text-align: center;">
                <h4>You Didn't takes any Attemp Yet</h4>
            </div> <?php
                    // Get Footer ( this will include wp_footer() and closing tags )
                    get_footer();
                    return;
                }
                    ?>
        <h4>My Attepmts</h4>
    </div>


    <table>
        <thead>
            <tr>
                <th>Quiz ID</th>
                <th>Date</th>
                <th>Total Time</th>
                <th>Quiz Type</th>
                <th>Practice Name</th>
                <th>Results</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($all_results as $result) {
                $quiz_id = $result->quiz_id;
                $date = $result->date;
                $date_obj = new DateTime($date);
                $formatted_date = $date_obj->format('D, d M Y @ g:ia');
                $total_time = $result->total_time;
                $hours = floor($total_time / 3600);
                $minutes = floor(($total_time % 3600) / 60);
                $seconds = $total_time % 60;
                $formatted_time = sprintf("%d hours %d minutes %d seconds", $hours, $minutes, $seconds);
                $quiz_type_id = isset($result->quiz_type_id) ? $result->quiz_type_id : 5;
                $practice_name = get_term($quiz_type_id)->name;
                $tax = get_term($quiz_type_id)->taxonomy;
                //Define Post Type name
                $quiz_type = 'Reading';
                if ($tax == 'reading-practice') {
                    $quiz_type = 'Reading';
                } else if ($tax == 'mathenatical-practice') {
                    $quiz_type = 'Mathematical Reasoning';
                } else if ($tax == 'thinking-skll-practice') {
                    $quiz_type = 'Thinking Skill';
                } else if ($tax == 'writtings-practice') {
                    $quiz_type = 'Writting';
                }
                $result_id = $result->quiz_id;
                $result_link = get_site_url() . '/results/?quiz_id=' . $result_id . '&term=' . $quiz_type_id;
            ?>
                <tr>
                    <td>#<?php echo $quiz_id; ?></td>
                    <td><?php echo $formatted_date; ?></td>
                    <td><?php echo $formatted_time; ?></td>
                    <td><?php echo $quiz_type; ?></td>
                    <td><?php echo $practice_name; ?></td>
                    <td><a href="<?php echo $result_link; ?>" class="results">View Result</a></td>
                </tr>
            <?php
            }


            ?>

        </tbody>
    </table>
</div>

<?php
// Get Footer ( this will include wp_footer() and closing tags )
get_footer();
