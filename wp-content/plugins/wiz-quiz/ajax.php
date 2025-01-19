<?php

function wiz_update_result_database_mcq()
{
    global $wpdb;
    $quiz_id = $_POST['wizQuizData']['quiz_id'];
    $user_name = $_POST['wizQuizData']['user_name'];
    $current_time = time();
    $given_answer = $_POST['answer'];
    $question_id = $_POST['question_id'];
    $total_option = get_post_meta($question_id, 'mcq_options_', true);

    for ($i = 0; $i < $total_option; $i++) {
        $opt_key = 'mcq_options__' . $i . '_right';
        $opt_name = get_post_meta($question_id, $opt_key, true);
        if ($opt_name) {
            $actual_ans = get_post_meta($question_id, 'mcq_options__' . $i . '_mcq_option', true);
        }
    }
    if ($given_answer == $actual_ans) {
        $correct = 'correct';
        $gain_point = 1;
    } else {
        $correct = 'incorrect';
        $gain_point = 0;
    }

    $new_result = array(
        'question_id'  => $_POST['question_id'],
        'answer_given' => $_POST['answer'],
        'correct'      => $correct,
        'total_point'  => 1,
        'gain_point'   => $gain_point,
    );

    // Prepare SQL query to get the row from the database
    $table_name = $wpdb->prefix . "wiz_results";
    $query = "
        SELECT * 
        FROM $table_name 
        WHERE quiz_id = %d 
        AND user_name = %s
    ";
    $row = $wpdb->get_row($wpdb->prepare($query,  $quiz_id, $user_name));
    if ($row) {
        $total_time = $current_time - $row->time;
        $existing_result = json_decode($row->result, true);
        if (is_array($existing_result)) {
            $question_found = false;
            foreach ($existing_result as &$existing_entry) {
                if ($existing_entry['question_id'] == $new_result['question_id']) {
                    $existing_entry = $new_result;
                    $question_found = true;
                    break;
                }
            }
            if (!$question_found) {
                $existing_result[] = $new_result;
            }
        } else {
            $existing_result = array($new_result);
        }
        $updated = $wpdb->update(
            $table_name,
            array(
                'total_time' => $total_time,
                'result'     => json_encode($existing_result), // Encode as JSON
            ),
            array('id' => $row->id) // Use the row's ID to identify it
        );

        // Log the update result
        if ($updated !== false) {
            wp_send_json_success('success');
        } else {
            wp_send_json_error('Error');
        }
    }
    wp_die();
}
add_action('wp_ajax_wiz_update_result_database_mcq', 'wiz_update_result_database_mcq');
add_action('wp_ajax_nopriv_wiz_update_result_database_mcq', 'wiz_update_result_database_mcq');


function wiz_update_result_database_drag()
{
    global $wpdb;


    $quiz_id = $_POST['wizQuizData']['quiz_id'];
    $user_name = $_POST['wizQuizData']['user_name'];
    $current_time = time();
    $result = $_POST['results'];
    $question_id = $_POST['question_id'];

    $total_option = get_post_meta($question_id, 'drag_&_drop', true);
    $gain_point = 0;

    for ($i = 0; $i < $total_option; $i++) {
        $answer_key = 'drag_&_drop_' . $i . '_answer';
        $answer = str_replace(' ', '', trim(get_post_meta($question_id, $answer_key, true)));
        $given_answer = str_replace(' ', '', trim($result[$i]['option']));

        if ($answer == $given_answer) {
            $gain_point++;
        }
    }


    if ($gain_point == $total_option) {
        $correct = 'correct';
    } else {
        $correct = 'incorrect';
    }

    $new_result = array(
        'question_id'  => $_POST['question_id'],
        'answer_given' => $_POST['results'],
        'correct'      => $correct,
        'total_point'  => $total_option,
        'gain_point'   => $gain_point,
    );


    $table_name = $wpdb->prefix . "wiz_results";
    $query = " SELECT *  FROM $table_name  WHERE quiz_id = %d  AND user_name = %s";

    $row = $wpdb->get_row($wpdb->prepare($query, $quiz_id, $user_name));


    if ($row) {
        $total_time = $current_time - $row->time;
        $existing_result = json_decode($row->result, true);


        if (is_array($existing_result)) {
            $question_found = false;
            foreach ($existing_result as &$existing_entry) {
                if ($existing_entry['question_id'] == $new_result['question_id']) {
                    $existing_entry = $new_result;
                    $question_found = true;
                    break;
                }
            }
            if (!$question_found) {
                $existing_result[] = $new_result;
            }
        } else {
            $existing_result = array($new_result);
        }

        $updated = $wpdb->update(
            $table_name,
            array(
                'total_time' => $total_time,
                'result'     => json_encode($existing_result),
            ),
            array('id' => $row->id)
        );


        if ($updated !== false) {
            wp_send_json_success('success');
        } else {
            wp_send_json_error('Error');
        }
    }

    wp_die();
}
add_action('wp_ajax_wiz_update_result_database_drag', 'wiz_update_result_database_drag');
add_action('wp_ajax_nopriv_wiz_update_result_database_drag', 'wiz_update_result_database_drag');


function wiz_update_result_database_writting()
{
    global $wpdb;
    // Extract relevant data from $_POST 
    $quiz_id = $_POST['wizQuizData']['quiz_id'];
    $user_name = $_POST['wizQuizData']['user_name'];
    $term_id = $_POST['wizQuizData']['term_id'];
    $current_time = time();
    $question_id = $_POST['question_id'];  
    $max_point = get_post_meta($question_id, 'max_point', true);
    if (!$max_point) {
        $max_point = 50;
    } 

    // Prepare SQL query to get the row from the database
    $new_result = array(
        'question_id'  => $_POST['question_id'],
        'answer_given' => $_POST['answer'],
        'correct'      => 'incorrect',
        'total_point'  => $max_point,
        'gain_point'   => 0,
        'updated_answer' => false,
        'comment' => '',
    );

    $table_name = $wpdb->prefix . "wiz_writting_results";
    $query = "
    SELECT * 
    FROM $table_name 
    WHERE quiz_id = %d 
    AND user_name = %s
    ";
    $row = $wpdb->get_row($wpdb->prepare($query,  $quiz_id, $user_name));
    if ($row) {
        $total_time = $current_time - $row->time;
        // Update the row in the database with the new result
        $existing_result = json_decode($row->result, true);
        if (is_array($existing_result)) {
            $existing_result[] = $new_result;  // Add the new result
        } else {
            $existing_result = array($new_result);  // If there's no existing result, start a new array
        }

        $updated = $wpdb->update(
            $table_name,
            array(
                'total_time' => $total_time,
                'result'     =>  json_encode($existing_result), // Encode as JSON
            ),
            array('id' => $row->id) // Use the row's ID to identify it
        );
        // Log the update result
        if ($updated !== false) {
            wp_send_json_success('success');
        } else {
            wp_send_json_error('Error');
        }
    }
    wp_die();
}
add_action('wp_ajax_wiz_update_result_database_writting', 'wiz_update_result_database_writting');
add_action('wp_ajax_nopriv_wiz_update_result_database_writting', 'wiz_update_result_database_writting');


function wiz_update_result_database_multiple()
{
    global $wpdb;
 
    $quiz_id = $_POST['wizQuizData']['quiz_id'];
    $user_name = $_POST['wizQuizData']['user_name'];
    $term_id = $_POST['wizQuizData']['term_id'];
    $current_time = time();
    $answers = $_POST['answers'];
    $question_id = $_POST['question_id'];
 

    $gain_point = 0;
    $total_option = get_post_meta($question_id, 'drop_down_options', true);
  

    for ($i = 0; $i < $total_option; $i++) {
        $answer_key = 'drop_down_options_' . $i . '_select_extract';
        $actual_answer = str_replace(' ', '', trim(get_post_meta($question_id, $answer_key, true)));
        $given_answer = str_replace(' ', '', trim($answers[$i]['answer']));
 

        if ($actual_answer == $given_answer) {
            $gain_point++;
        }
    }
 

    if ($gain_point == $total_option) {
        $correct = 'correct';
    } else {
        $correct = 'incorrect';
    }

    $new_result = array(
        'question_id'  => $_POST['question_id'],
        'answer_given' => $_POST['answers'],
        'correct'      => $correct,
        'total_point'  => $total_option,
        'gain_point'   => $gain_point,
    );
 

    $table_name = $wpdb->prefix . "wiz_results";
    $query = "
        SELECT * 
        FROM $table_name 
        WHERE quiz_id = %d 
        AND user_name = %s
    ";

    $row = $wpdb->get_row($wpdb->prepare($query, $quiz_id, $user_name));
 

    if ($row) {
        $total_time = $current_time - $row->time;
        $existing_result = json_decode($row->result, true);
 
        if (is_array($existing_result)) {
            $question_found = false;
            foreach ($existing_result as &$existing_entry) {
                if ($existing_entry['question_id'] == $new_result['question_id']) {
                    $existing_entry = $new_result;
                    $question_found = true; 
                    break;
                }
            }
            if (!$question_found) {
                $existing_result[] = $new_result; 
            }
        } else {
            $existing_result = array($new_result); 
        }

        $updated = $wpdb->update(
            $table_name,
            array(
                'total_time' => $total_time,
                'result'     => json_encode($existing_result),
            ),
            array('id' => $row->id)
        );
 

        if ($updated !== false) { 
            wp_send_json_success('success');
        } else { 
            wp_send_json_error('Error');
        }
    }  

    wp_die();
}
add_action('wp_ajax_wiz_update_result_database_multiple', 'wiz_update_result_database_multiple');
add_action('wp_ajax_nopriv_wiz_update_result_database_multiple', 'wiz_update_result_database_multiple');



// For Backedn 
function update_wittings_result()
{
    global $wpdb;

    // Extract relevant data from $_POST
    $quiz_id = $_POST['quiz_id'];
    $question_id = $_POST['question_id'];
    $score = $_POST['score'];
    $updated_answer = $_POST['updated_answer'];
    $comment = $_POST['comment'];

    $table_name = $wpdb->prefix . "wiz_writting_results";
    $query = "
    SELECT * 
    FROM $table_name 
    WHERE quiz_id = %d 
    ";
    $row = $wpdb->get_row($wpdb->prepare($query, $quiz_id));

    // Prepare SQL query to get the row from the database
    if ($row) {
        // Update the row in the database with the new result
        $existing_result = json_decode($row->result, true);

        if (is_array($existing_result)) {
            $question_found = false;
            $updated = false; // Initialize updated flag

            foreach ($existing_result as &$existing_entry) { // Use reference to modify the original array
                if ($existing_entry['question_id'] == $question_id) {
                    $existing_entry = array(
                        'question_id'  => $existing_entry['question_id'],
                        'answer_given'  => $existing_entry['answer_given'],
                        'correct'      => 'incorrect',
                        'total_point'  => $existing_entry['total_point'],
                        'gain_point'  => $score,
                        'updated_answer'  => $updated_answer,
                        'comment'  => $comment,
                    );
                    $question_found = true;
                    $updated = true;
                    break; // Exit loop after update
                }
            }

            // If we updated the result
            if ($updated) {
                $updated = $wpdb->update(
                    $table_name,
                    array(
                        'result'     =>  json_encode($existing_result), // Encode as JSON
                    ),
                    array('id' => $row->id) // Use the row's ID to identify it
                );
            }
        }

        // Send response based on update status
        if ($updated !== false) {
            wp_send_json_success('success');
        } else {
            wp_send_json_error('Error');
        }
    } else {
        wp_send_json_error('Quiz not found');
    }
    wp_die();
}
add_action('wp_ajax_update_wittings_result', 'update_wittings_result');
add_action('wp_ajax_nopriv_update_wittings_result', 'update_wittings_result');


function update_mcq_result()
{
    global $wpdb;
    // Extract relevant data from $_POST
    $quiz_id = $_POST['quiz_id'];
    $question_id = $_POST['question_id'];
    $updated_answer = $_POST['updated_answer'];
    $given_answer = $_POST['updated_answer'];

    $total_option = get_post_meta($question_id, 'mcq_options_', true);

    for ($i = 0; $i < $total_option; $i++) {
        $opt_key = 'mcq_options__' . $i . '_right';
        $opt_name = get_post_meta($question_id, $opt_key, true);
        if ($opt_name) {
            $actual_ans = get_post_meta($question_id, 'mcq_options__' . $i . '_mcq_option', true);
        }
    }
    if ($given_answer == $actual_ans) {
        $correct = 'correct';
        $gain_point = 1;
    } else {
        $correct = 'incorrect';
        $gain_point = 0;
    }

    $new_result = array(
        'question_id'  => $_POST['question_id'],
        'answer_given' => $given_answer,
        'correct'      => $correct,
        'total_point'  => 1,
        'gain_point'   => $gain_point,
    );

    // Prepare SQL query to get the row from the database
    $table_name = $wpdb->prefix . "wiz_results";
    $query = "
        SELECT * 
        FROM $table_name 
        WHERE quiz_id = %d  
    ";
    $row = $wpdb->get_row($wpdb->prepare($query,  $quiz_id));
    if ($row) {
        $existing_result = json_decode($row->result, true);
        if (is_array($existing_result)) {
            $question_found = false;
            foreach ($existing_result as &$existing_entry) {
                if ($existing_entry['question_id'] == $new_result['question_id']) {
                    $existing_entry = $new_result;
                    $question_found = true;
                    break;
                }
            }
            if (!$question_found) {
                $existing_result[] = $new_result;
            }
        } else {
            $existing_result = array($new_result);
        }
        $updated = $wpdb->update(
            $table_name,
            array(
                'result'     => json_encode($existing_result), // Encode as JSON
            ),
            array('id' => $row->id) // Use the row's ID to identify it
        );

        // Log the update result
        if ($updated !== false) {
            wp_send_json_success('success');
        } else {
            wp_send_json_error('Error');
        }
    }
    wp_die();
}
add_action('wp_ajax_update_mcq_result', 'update_mcq_result');
add_action('wp_ajax_nopriv_update_mcq_result', 'update_mcq_result');


function update_multiple_result()
{
    global $wpdb;
    // Extract relevant data from $_POST
    error_log(print_r($_POST, true));
    $quiz_id = $_POST['quiz_id'];
    $question_id = $_POST['question_id'];
    $updated_answer = $_POST['updated_answer'];
    $answers = $_POST['updated_answer'];
    $gain_point = 0;
 $total_option = get_post_meta($question_id, 'drop_down_options', true);

 

    for ($i = 0; $i < $total_option; $i++) {
        $answer_key = 'drop_down_options_' . $i . '_select_extract';
        $actual_answer = str_replace(' ', '', trim(get_post_meta($question_id, $answer_key, true)));
        $given_answer = str_replace(' ', '', trim($answers[$i]['answer'])); 

        if ($actual_answer == $given_answer) {
            $gain_point++;
        }
    }



    if ($given_answer == $actual_answer) {
        $correct = 'correct'; 
    } else {
        $correct = 'incorrect'; 
    }

    $new_result = array(
        'question_id'  => $_POST['question_id'],
        'answer_given' => $answers,
        'correct'      => $correct,
        'total_point'  => $total_option,
        'gain_point'   => $gain_point,
    );

    // Prepare SQL query to get the row from the database
    $table_name = $wpdb->prefix . "wiz_results";
    $query = "
        SELECT * 
        FROM $table_name 
        WHERE quiz_id = %d  
    ";
    $row = $wpdb->get_row($wpdb->prepare($query,  $quiz_id));
    if ($row) {
        $existing_result = json_decode($row->result, true);
        if (is_array($existing_result)) {
            $question_found = false;
            foreach ($existing_result as &$existing_entry) {
                if ($existing_entry['question_id'] == $new_result['question_id']) {
                    $existing_entry = $new_result;
                    $question_found = true;
                    break;
                }
            }
            if (!$question_found) {
                $existing_result[] = $new_result;
            }
        } else {
            $existing_result = array($new_result);
        }
        $updated = $wpdb->update(
            $table_name,
            array(
                'result'     => json_encode($existing_result), // Encode as JSON
            ),
            array('id' => $row->id) // Use the row's ID to identify it
        );

        // Log the update result
        if ($updated !== false) {
            wp_send_json_success('success');
        } else {
            wp_send_json_error('Error');
        }
    }
    wp_die();
}
add_action('wp_ajax_update_multiple_result', 'update_multiple_result');
add_action('wp_ajax_nopriv_update_multiple_result', 'update_multiple_result');


function update_drag_result()
{
    global $wpdb;
    // Extract relevant data from $_POST 
    $quiz_id = $_POST['quiz_id'];
    $question_id = $_POST['question_id'];
    $result = $_POST['updated_answer'];
    $answers = $_POST['updated_answer'];
    $gain_point = 0;
    $total_option = get_post_meta($question_id, 'drag_&_drop', true);

 

    for ($i = 0; $i < $total_option; $i++) {
        $answer_key = 'drag_&_drop_' . $i . '_answer';
        $answer = str_replace(' ', '', trim(get_post_meta($question_id, $answer_key, true)));
        $given_answer = str_replace(' ', '', trim($result[$i]['option']));

        if ($answer == $given_answer) {
            $gain_point++;
        }
    }



    if ($gain_point == $total_option) {
        $correct = 'correct';
    } else {
        $correct = 'incorrect';
    }

    $new_result = array(
        'question_id'  => $_POST['question_id'],
        'answer_given' => $result,
        'correct'      => $correct,
        'total_point'  => $total_option,
        'gain_point'   => $gain_point,
    );


    // Prepare SQL query to get the row from the database
    $table_name = $wpdb->prefix . "wiz_results";
    $query = "
        SELECT * 
        FROM $table_name 
        WHERE quiz_id = %d  
    ";
    $row = $wpdb->get_row($wpdb->prepare($query,  $quiz_id));
    if ($row) {
        $existing_result = json_decode($row->result, true);
        if (is_array($existing_result)) {
            $question_found = false;
            foreach ($existing_result as &$existing_entry) {
                if ($existing_entry['question_id'] == $new_result['question_id']) {
                    $existing_entry = $new_result;
                    $question_found = true;
                    break;
                }
            }
            if (!$question_found) {
                $existing_result[] = $new_result;
            }
        } else {
            $existing_result = array($new_result);
        }
        $updated = $wpdb->update(
            $table_name,
            array(
                'result'     => json_encode($existing_result), // Encode as JSON
            ),
            array('id' => $row->id) // Use the row's ID to identify it
        );

        // Log the update result
        if ($updated !== false) {
            wp_send_json_success('success');
        } else {
            wp_send_json_error('Error');
        }
    }
    wp_die();
}
add_action('wp_ajax_update_drag_result', 'update_drag_result');
add_action('wp_ajax_nopriv_update_drag_result', 'update_drag_result');





function save_font_settings()
{

    $font_family = $_POST['fonts'];
    update_option('font_family', $font_family);
    wp_send_json_success('success');
    wp_die();
}
add_action('wp_ajax_save_font_settings', 'save_font_settings');
add_action('wp_ajax_nopriv_save_font_settings', 'save_font_settings');
function get_practice_test_names()
{
    $post_type = $_POST['post_type'];
    $terms = get_terms([
        'taxonomy' => $post_type,
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => false,
    ]);
    $test_names = [];
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $test_names[] = [
                'id' => $term->term_id,
                'name' => $term->name,
                'tax' => $term->taxonomy,
            ];
        }
    }
    wp_send_json_success($test_names);
}
add_action('wp_ajax_get_practice_test_names', 'get_practice_test_names');
add_action('wp_ajax_nopriv_get_practice_test_names', 'get_practice_test_names');


