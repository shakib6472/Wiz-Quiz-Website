<?php

/**
 * Template result Page
 * 
 * @package Results
 */

// Get Header (this will include the <head> section and wp_head())
get_header();
global $wpdb;
// Main Section
$quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : 0;

if ($quiz_id != 0) {
    $term_id = $_GET['term'];
    //get term name( key ) by term id. 
    $term_name = get_term($term_id)->name;
    $term = get_term($term_id);
    $tax = get_term($term_id)->taxonomy;
    $result_open_time = get_term_meta($term_id, 'result_open_time', true);
    $result_open_time = strtotime($result_open_time);
    $now = time();
    if ($now < $result_open_time) {
?>
        <div class="no-result-container"> 
                <h1 class="text-center not-available">Result Is not available now</h1>
            <h3 class="text-center">Result will be available after  <?php echo date('D, d M Y @ g:ia', $result_open_time); ?> </h3>;
        </div>
    <?php
        get_footer();
        return;
    } 
    
    //Define Post Type name
    if ($tax == 'reading-practice') {
        $quiz_name = 'Reading';
    } else if ($tax == 'mathenatical-practice') {
        $quiz_name = 'Mathematical Reasoning';
    } else if ($tax == 'thinking-skill') {
        $quiz_name = 'Thinking Skill';
    } else if ($tax == 'writtings-practice') {
        $quiz_name = 'Writting';
    }

    $user_id = is_user_logged_in() ? get_current_user_id() : 0;
    if ($tax != 'writtings-practice') {
        $existing_result = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wiz_results WHERE quiz_id = %d AND user_id = %d",
            $quiz_id,
            $user_id
        ));
    } else {
        $existing_result = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wiz_writting_results WHERE quiz_id = %d AND user_id = %d",
            $quiz_id,
            $user_id
        ));
    }


    $args = array(
        'post_type'      => array('mathematical-reasoni', 'reading', 'thinking-skill', 'writing'), // Include the post types
        'tax_query'      => array(
            array(
                'taxonomy' => $tax, // Use the current taxonomy of the term
                'field'    => 'term_id', // You can also use 'slug' or 'name'
                'terms'    => $term_id,
            ),
        ),
        'orderby'        => 'date', // Sort by date
        'order'          => 'ASC',  // Oldest posts first
        'posts_per_page' => -1, // Get all posts, adjust the number if you want pagination
    );
    $posts = get_posts($args);

    $total_point = 0;
    $gain_point = 0;
    $post_count = count($posts);
    //toal point count
    foreach ($posts as $post) {
        $post_id = get_the_ID();
        $question_type = get_post_meta($post_id, 'question_type', true);
        $max_point = get_post_meta($post_id, 'max_point', true);
        if (!$max_point) {
            $max_point = 50;
        }
        if ('MCQ' === $question_type) {
            $total_point++;
        } else if ('Drag & Drop' === $question_type) {
            $each_p = get_post_meta($post_id, 'drag_&_drop', true);
            $total_point +=  $each_p;
        } else if ('Multiple Drop Down' === $question_type) {
            $each_p = get_post_meta($post_id, 'drop_down_options', true);
            $total_point +=  $each_p;
        } else if ('Writings' === $question_type) {

            $total_point =  $total_point + $max_point;
        }
    }

    $submitted_date = $existing_result->date;
    $date_obj = new DateTime($submitted_date);
    $formatted_date = $date_obj->format('D, d M Y @ g:ia');
    $timezone = $existing_result->timezone;
    $total_time = $existing_result->total_time;
    $hours = floor($total_time / 3600);
    $minutes = floor(($total_time % 3600) / 60);
    $seconds = $total_time % 60;
    $formatted_time = sprintf("%d hours %d minutes %d seconds", $hours, $minutes, $seconds);
    $results = json_decode($existing_result->result, true);
    // Check if decoding was successful

    // if ('Writings' !== $question_type) {
    if (is_array($results)) {
        // Loop through the results and log each result
        foreach ($results as $result) {
            $gain_point += $result['gain_point'];
        }
    }
    // }
    $percentage = ($total_point > 0) ? ($gain_point / $total_point) * 100 : 0;

    ?>

    <div class="result_container">
        <!-- Header Section -->
        <header class="header">
            <h1 class="test-name text-center w-100">
                Selective High School Placement Test - <?php echo $term_name; ?> - <?php echo $quiz_name; ?>
            </h1>
        </header>
        <!-- Main Content -->
        <main>
            <!-- Summary Section -->
            <section class="summary-section">
                <?php

                if (is_user_logged_in() || isset($_COOKIE['user_name'])) {
                    // Check if the user is logged in
                    if (is_user_logged_in()) {
                        $current_user = wp_get_current_user();
                        // Display first and last name of the logged-in user
                        echo '<h2 class="wel-n">Welcome to result board, ' . esc_html($current_user->user_firstname) . ' ' . esc_html($current_user->user_lastname) . '</h2>';
                    } elseif (isset($_COOKIE['user_name'])) {
                        // If the user is not logged in but the cookie is set
                        $user_name = $_COOKIE['user_name'];
                        echo '<h2 class="wel-n">Welcome to result board, ' . esc_html($user_name) . '</h2>';
                    }
                }
                ?>
                <!-- <h2 class="user_name">Summary</h2> -->
                <h4 class="summary-header">Summary</h4>
                <p class="wiz_bold-text">Started <?php echo $formatted_date . '( ' . $timezone . ' )'; ?></p>
                <p class="wiz_bold-text">Total Time taken: <?php echo $formatted_time; ?></p>
                <div class="score-summary">
                    <h2 class="score"><?php echo $gain_point . '/' . $total_point; ?></h2>
                    <h3 class="percentage"><?php echo number_format($percentage, 1); ?>%</h3>
                </div>
            </section>

            <!-- Question Results Section -->
            <section class="question-results">
                <h2>Question Results</h2>
                <p class="hint">Click a question to view your response in detail.</p>
                <div class="results-grid">
                    <?php
                    $q_found = false;

                    foreach ($posts as $pindex => $post) {
                        $post_id = get_the_ID();

                        foreach ($results as $rindex => $result) {
                            $question_id = $result['question_id'];
                            $correction_class = 'question-box incorrect';
                            if ($post_id == $question_id) {
                                $correction_class = $result['correct'];
                                break;
                            }
                        }
                        $url = home_url('/results-details?quizid=' . $quiz_id . '&slide=' . $pindex + 1 . '&term=' . $term_id);
                    ?>
                        <a href="<?php echo $url; ?>">
                            <div class="question-box <?php echo $correction_class; ?>" data-slide="<?php echo $pindex + 1; ?>" data-que="<?php echo $post_id; ?>"><?php echo $pindex + 1; ?></div>
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </section>

            <!-- Detailed Results Section -->
            <section class="detailed-results">
                <h2>Detailed Results</h2>
                <p>Click a question to view your response in detail.</p>
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Question</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $qq_found = false;

                        foreach ($posts as $pindex => $post) {
                            $post_id = get_the_ID();
                            $post_title = get_the_title();

                            $question_type = get_post_meta($post_id, 'question_type', true);
                            $question_text = get_post_meta($post_id, 'question_texts', true);
                            $correction_class = 'incorrect';
                            $answer_text = '✘';
                            foreach ($results as $rindex => $result) {
                                $question_id = $result['question_id'];
                                //get this post taxanomy name. tax key "question-category"
                                $question_category = get_the_terms($question_id, 'question-category');
                                if ($question_category) {
                                    $question_category = $question_category[0]->name;
                                } else {
                                    $question_category = 'Normal';
                                }

                                if ($post_id == $question_id) {
                                    $gain_point = $result['gain_point'];
                                    if ('Drag & Drop' === $question_type) {
                                        $icon_class = 'fab fa-hashnode question-type';
                                        $each_p = get_post_meta($post_id, 'drag_&_drop', true);
                                        $answer_text = $gain_point . '/' . $each_p;
                                        if ($gain_point  == $each_p) {
                                            $result['correct'] = 1;
                                            $correction_class = 'correct';
                                        } else {
                                            $result['correct'] = false;
                                            $correction_class = 'incorrect';
                                        }
                                    } else if ('Multiple Drop Down' === $question_type) {
                                        $icon_class = 'fab fa-stack-exchange question-type';
                                        $each_p = get_post_meta($post_id, 'drop_down_options', true);
                                        $answer_text = $gain_point . '/' . $each_p;
                                        if ($gain_point  == $each_p) {
                                            $result['correct'] = 1;
                                            $correction_class = 'correct';
                                        } else {
                                            $result['correct'] = false;
                                            $correction_class = 'incorrect';
                                        }
                                    } else if ('Writings' === $question_type) {
                                        $icon_class = 'fas fa-pen-to-square question-type';
                                        $max_point = get_post_meta($question_id, 'max_point', true);
                                        if (!$max_point) {
                                            $max_point = 50;
                                        }
                                        $each_p = $max_point;
                                        $answer_text = $gain_point . '/' . $each_p;
                                        if ($gain_point  == $each_p) {
                                            $result['correct'] = 1;
                                            $correction_class = 'correct';
                                        } else {
                                            $result['correct'] = false;
                                            $correction_class = 'incorrect';
                                        }
                                        $question_category = 'Text';
                                    } else if ('MCQ' === $question_type) {
                                        $icon_class = 'fas fa-list-check question-type';
                                        $each_p = 1;
                                        if ($gain_point  == $each_p) {
                                            $result['correct'] = 1;
                                            $correction_class = 'correct';
                                            $answer_text = '✔';
                                        } else {
                                            $result['correct'] = false;
                                            $correction_class = 'incorrect';
                                            $answer_text = '✘';
                                        }
                                    }
                                    break;
                                }
                            }
                            $url = home_url('/results-details?quizid=' . $quiz_id . '&slide=' . $pindex + 1 . '&term=' . $term_id);
                        ?>
                            <tr>
                                <td><?php echo $pindex + 1; ?></td>
                                <td><i class="<?php echo $icon_class; ?>"></i></td>
                                <td class="question-text"><?php echo $question_category; ?></td>
                                <td class="question-text"><a href="<?php echo $url; ?>" data-slide="<?php echo $pindex + 1; ?>" data-que="<?php echo $post_id; ?>"><?php echo $question_text; ?></a></td>
                                <td class="result <?php echo $correction_class; ?>"><?php echo $answer_text; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
<?php
} else {
    echo '<h1 class="text-center">No results Found</h1>';
}
// Get Footer (this will include wp_footer() and closing tags)
get_footer();
