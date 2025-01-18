<?php
global $wpdb;
$table_name = $wpdb->prefix . "wiz_writting_results";
$action = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : false;

if (! $action) {
  $results = $wpdb->get_results("SELECT * FROM $table_name");
  $results = array_filter($results, function($result) {
    return $result->result !== '';
  }); 
?>
  <div class="wrap">
    <h1>Writting Test Results</h1>
    <table class="wp-list-table widefat fixed striped result-table">
      <thead>
        <tr>
          <th>User Name</th>
          <th>Device ID</th>
          <th>Quiz ID</th>
          <th>Date</th>
          <th>Total Time</th>
          <th>Results</th>
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
              'meta_key'       => 'the_question_number',
              'orderby'        => 'meta_value_num', // Sort by date
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

?>
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />
  <div class="wiz_container writting_container">
    <?php include WIZ_QUIZ_PLUGIN_DIR . 'admin/parts/header.php' ?>
    <div class="result">
      <h2>Answer Details</h2>
      <table>
        <thead>
          <tr>
            <th style="width: 50%">Answer Given by user</th>
            <th style="width: 50%">Feedback on answer by admin</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($answers as $answer) {
            $post_id = $answer['question_id'];
            $question = get_post($post_id);
            $question_title = get_post_meta($post_id, 'question_texts', true);
            $max_point = get_post_meta($post_id, 'max_point', true);
            if(!$max_point) {
                $max_point = 50;
            }
            $answer_given = $answer['answer_given'];
            if (isset($answer['updated_answer']) && $answer['updated_answer']) {
              $updated_answer = $answer['updated_answer'];
            } else {
              $updated_answer = $answer['answer_given'];
            }

          ?>
            <tr>
              <td>
                <div class="question">
                  <h3><?php echo $question_title; ?></h3>
                </div>
                <div class="option-container scrollbar">
                  <?php echo $answer_given; ?>
                </div>
              </td>
              <td>
                <div class="question">
                  <h3><?php echo $question_title; ?></h3>
                </div>
                <div class="option-container scrollbar">
                  <div id="editor"></div>
                </div>
              </td>

            </tr>
            <tr>
              <th>Comment on this answer</th>
              <th>Give Score</th>
            </tr>
            <tr>
              <td> <textarea name="comment" id="comment" style="width: 100%;" rows="10"> <?php echo isset($answer['comment']) ? $answer['comment'] : ''; ?></textarea>
              </td>
              <td>
                <div class="score">
                  <input
                    type="number"
                    name="score"
                    id="score"
                    value="<?php echo $answer['gain_point']; ?>"
                    max="<?php echo $max_point; ?>"
                    min="0" />
                    <div class="hint">Max Score : <?php echo  $max_point; ?></div>
                </div>
                <div class="action">
                  <div class="btn-update_result" data-quiz_id="<?php echo $results->quiz_id; ?>" data-que="<?php echo $post_id; ?>">Update Everything</div>
                </div>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src='https://code.jquery.com/jquery-3.7.1.min.js' integrity='sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=' crossorigin='anonymous'></script>
  <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

  <script>
    var quill = new Quill("#editor", {
      theme: "snow",
      modules: {
        toolbar: [
          [{
            header: "1"
          }, {
            header: "2"
          }, {
            font: []
          }],
          [{
            list: "ordered"
          }, {
            list: "bullet"
          }],
          ["bold", "italic", "underline", "strike"],
          [{
            align: []
          }],
          ["link"],
          ["blockquote", "code-block"],
          [{
            script: "sub"
          }, {
            script: "super"
          }],
          ["image"],
          ["clean"],
          [{
            'color': []
          }, {
            'background': []
          }] // Allow background color in Quill
        ]
      },
      placeholder: "Type your text here...",
      formats: ["bold", "italic", "underline", "strike", "list", "header", "link", "blockquote", "code-block", "color", "background"]
    });

    var updatedAnswer = "<?php echo $updated_answer; ?>"; // Output the raw HTML without addslashes
    quill.clipboard.dangerouslyPasteHTML(updatedAnswer);


    // Disable spellcheck to prevent Grammarly interference
    document.getElementById("editor").setAttribute("spellcheck", "false");
  </script>
<?php
}
