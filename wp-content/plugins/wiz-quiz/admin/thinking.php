<?php
global $wpdb;
$table_name = $wpdb->prefix . "wiz_results";
$action = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : false;
$quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : false;






if (! $action) {
  $results = $wpdb->get_results("SELECT * FROM $table_name WHERE quiz_type = 'thinking-skll-practice'");
  $results = array_filter($results, function($result) {
    return $result->result !== '';
  }); 
?>
  <div class="wrap">
    <h1>Mathematical Test Results</h1>
    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th>User Name</th>
          <th>Device ID</th>
          <th>Quiz ID</th>
          <th>Date</th>
          <th>Total Time</th>
          <th>Total Mark</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($results)) : ?>
          <?php foreach ($results as $row) :
            $total_time = $row->total_time;
            $hours = floor($total_time / 3600);
            $minutes = floor(($total_time % 3600) / 60);
            $seconds = $total_time % 60;
            $formatted_time = sprintf("%d hours %d minutes %d seconds", $hours, $minutes, $seconds);
            $term_id = $row->quiz_type_id;
            $tax = $row->quiz_type;

            $args = array(
              'post_type'      => array('mathematical-reasoni', 'reading', 'thinking-skill', 'writing'),
              'tax_query'      => array(
                array(
                  'taxonomy' => $tax,
                  'field'    => 'term_id',
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
            foreach ($posts as $post) {
              $post_id = $post->ID;
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
            $results = json_decode($row->result, true); 
            if (is_array($results)) {
              // Loop through the results and log each result
              foreach ($results as $result) {
                $gain_point += $result['gain_point']; 
              }
            } 
            $percentage = ($total_point > 0) ? ($gain_point / $total_point) * 100 : 0; 

          ?>
            <tr>
              <td><?php echo esc_html($row->user_name); ?></td>
              <td><?php echo esc_html($row->device_id); ?></td>
              <td><?php echo esc_html($row->quiz_id); ?></td>
              <td><?php echo esc_html($row->date); ?></td>
              <td><?php echo esc_html($formatted_time); ?></td>
              <td><?php echo number_format($percentage, 1); ?>%</td>
              <td>
                <div class="btn-holders">
                  <div class="btn action-btn view_result" data-quiz_id="<?php echo $row->quiz_id ?>">Action</div>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan="15">No results found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
<?php
} else {
  $results = $wpdb->get_row("SELECT * FROM $table_name WHERE quiz_id = $action");
  //get term name from the term id $results->term_id;
  $practice_name = get_term_by('id', $results->quiz_type_id, $results->quiz_type)->name;
  $total_time = $results->total_time;
  $hours = floor($total_time / 3600);
  $minutes = floor(($total_time % 3600) / 60);
  $seconds = $total_time % 60;
  $formatted_time = sprintf("%d hours %d minutes %d seconds", $hours, $minutes, $seconds);
  $answers = json_decode($results->result, true);

  $term_id = $results->quiz_type_id;
  $term = get_term($term_id);
  $tax = $term->taxonomy;
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


?>
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />
  <div class="wiz_container writting_container">
    <div class="header">
      <h1>Review the answer</h1>
    </div>
    <div class="info">
      <div class="info-item"><strong>User Name:</strong> <?php echo $results->user_name; ?></div>
      <div class="info-item"><strong>User ID:</strong> <?php echo $results->user_id; ?> </div>
      <div class="info-item"><strong>Device ID:</strong> <?php echo $results->device_id; ?></div>
      <div class="info-item"><strong>Quiz ID:</strong> <?php echo $results->quiz_id; ?></div>
      <div class="info-item"><strong>Practice Name:</strong> <?php echo $practice_name; ?></div>
      <div class="info-item"><strong>Date:</strong> <?php echo $results->date; ?></div>
      <div class="info-item"><strong>Time Spent:</strong> <?php echo $formatted_time; ?></div>
      <!-- <div class="info-item"><strong>Total Points:</strong> <?php // echo $results->total_point; 
                                                                  ?></div> -->
      <div class="info-item"> <strong>User Agent:</strong> <?php echo $results->user_agent; ?> </div>
    </div>
    <div class="result">
      <h2>Answer Details</h2>
      <table>
        <thead>
          <tr>
            <th style="width: 50%">Answer Given by user (You can Edit) </th>
          </tr>
        </thead>
        <tbody>
          <?php

          foreach ($posts as $index => $post) {
            $mpost_id = $post->ID;
            foreach ($answers as $rindex => $answer) {
              $answer_given = '';
              $post_id = $answer['question_id'];
              if ($mpost_id == $post_id) {
                $question_type = get_post_meta($post_id, 'question_type', true);
                $question = get_post($post_id);
                $question_title = get_post_meta($post_id, 'question_texts', true);
                $answer_given = $answer['answer_given'];
                $post_id = $answer['question_id']; 
                break;
              } else { 
                $question_type = get_post_meta($mpost_id, 'question_type', true);
                $question = get_post($mpost_id);
                $question_title = get_post_meta($mpost_id, 'question_texts', true);
                $post_id = $mpost_id; 
              }
            }
          ?>
            <tr>
              <td>
                <?php if ('MCQ' === $question_type) {
                  include WIZ_QUIZ_PLUGIN_DIR . 'admin/reading-parts/mcq.php';
                } else if ('Drag & Drop' === $question_type) {
                  include WIZ_QUIZ_PLUGIN_DIR . 'admin/reading-parts/drag.php';
                } else if ('Multiple Drop Down' === $question_type) { 
                  include WIZ_QUIZ_PLUGIN_DIR . 'admin/reading-parts/multiple.php';
                } ?>
              </td>
            </tr>

          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

<?php
}
