<?php

/* 
Template Name: Admin Result Sheet Page
plugin name: Wiz Quiz
Package: Wiz Quiz
Version: 1.0
subpackage: admin/result-sheet.php
*/
?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">
<style>
    .wiz_container {
        width: 100%;
        max-width: 1200px;
        margin: 20px auto 0 auto;

    }

    .wiz_header {
        text-align: center;
        margin-bottom: 20px;
    }

    .wiz_sub_header {
        text-align: center;
        margin-bottom: 20px;
    }

    .filter-bar {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .selcte_post_type {
        width: 45%;
    }

    .seletec_test {
        width: 45%;
    }

    .result_sheet .result {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
    }

    .result_sheet .result h3 {
        margin-bottom: 10px;
    }

    .result_sheet .result p {
        margin-bottom: 10px;
    }

    .get_result button {
        background: #0073aa;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .get_csv button {
        margin-top: 40px;
        background: #0073aa;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Style for table border 1px solid black */
    table {
        border-collapse: collapse;
        width: 100%;
    }

    table tr {
        border: 1px solid black;
    }

    table th,
    table td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }

    select#dt-length-0 {
        width: 62px;
        margin-right: 17px;
    }
</style>

<div class="wiz_container header_container">
    <div class="wiz_header">
        <h1>Result Sheet</h1>
    </div>
    <div class="wiz_sub_header">
        <p>View and manage all the results of the quiz</p>
    </div>
</div>
<div class="wiz_container">
    <?php

    $action = isset($_GET['action']) ? $_GET['action'] : '';
    if ($action != 'result_view') {


    ?>
        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="selcte_post_type">
                <select name="post_type" id="post_type">
                    <option value="all">All</option>
                    <option value="writtings-practice">Writing</option>
                    <option value="reading-practice">Reading</option>
                    <option value="thinking-skll-practice">Thinking Skill</option>
                    <option value="mathenatical-practice">Mathematical Resoaning</option>
                </select>
            </div>
            <div class="seletec_test">
                <select name="test-name" id="test-name">
                    <!-- Option will get dynamicly -->
                </select>
            </div>
        </div>

        <!-- Get Result button -->
        <div class="get_result">
            <button>Get Result</button>
        </div>

    <?php } else { ?>
        <div class="result_sheet">
            <?php

            $quiz_type = $_GET['quiz_type'];
            $term_id = $_GET['quiz_id'];
            $term = get_term($term_id);
            global $wpdb;
            $table_name = $wpdb->prefix . "wiz_results";
            if ($quiz_type == 'all') {
                $args = array(
                    'post_type'      =>  array('mathematical-reasoni', 'reading', 'thinking-skill', 'writing'),
                    'orderby'        => 'date', // Sort by date
                    'order'          => 'ASC',  // Oldest posts first
                    'posts_per_page' => -1, // Get all posts, adjust the number if you want pagination
                );
                $results = $wpdb->get_results("SELECT * FROM $table_name");
            } else {
                $args = array(
                    'post_type'      => array('mathematical-reasoni', 'reading', 'thinking-skill', 'writing'), // Include the post types
                    'tax_query'      => array(
                        array(
                            'taxonomy' => $quiz_type, // Use the current taxonomy of the term
                            'field'    => 'term_id', // You can also use 'slug' or 'name'
                            'terms'    => $term_id,
                        ),
                    ),
                    'meta_key'       => 'the_question_number',
                    'orderby'        => 'meta_value_num', // Sort by date
                    'order'          => 'ASC',  // Oldest posts first
                    'posts_per_page' => -1, // Get all posts, adjust the number if you want pagination
                );
                $results = $wpdb->get_results("SELECT * FROM $table_name WHERE quiz_type_id = '$term_id'");
            }

            $results = array_filter($results, function ($result) {
                return $result->result !== '';
            });



            $posts = get_posts($args);
            if ($results) {
            ?>
                <table id="result-sheet-table" class="display" style="width:100%">
                    <tr>
                        <td></td>
                        <?php
                        //$results->user_name
                        foreach ($results as $result) {
                            echo '<td>' . $result->user_name . '</td>';
                        }
                        ?>
                        <td>Category</td>
						<td>Total Correct</td>
                    </tr>
                    <?php
                    foreach ($posts as $post) {
                        $post_id = $post->ID;
                        $total_correct = 0;
                        $question_category = get_the_terms($question_id, 'question-category');
                        if ($question_category) {
                            $question_category = $question_category[0]->name;
                        } else {
                            $question_category = 'Normal';
                        }
                        echo '<tr>';
                        echo '<td>' . $post->post_title . '</td>';
                        foreach ($results as $result) {
                            $result_array = json_decode($result->result, true);
                            $found = false;
                            foreach ($result_array as $index => $single_result) {
                                if ($single_result['question_id'] == $post_id) {
                                    $correct = $single_result['correct'];
                                    if ($correct == 'correct') {
                                        echo '<td style="">✅</td>';
                                        $total_correct++;
                                    } else {
                                        echo '<td style=""> ❌ </td>';
                                    }
                                    $found = true;
                                    break;
                                } // if not match in full loop, then print wrong td. 
                            }
                            if (!$found) {
                                echo '<td style="">  ❌ </td>';
                            }
                        }
                        echo '<td>' . $question_category . '</td>';
                        echo '<td>' . $total_correct . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>

                <div class="get_csv">
                    <button>Export CSV</button>
                </div>

            <?php } else {
                echo '<h3>No Results Found</h3>';
            }  ?>
        </div>
</div>
<?php } ?>
<script src='https://cdn.datatables.net/2.0.5/js/dataTables.min.js'></script>
<!-- PLease use This to Integrate
// let table = new DataTable('#my-boooks-table')
// CSS- https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css
//custom Css
select#dt-length-0 {
width: 62px;
margin-right: 17px;
} -->
<script>
    jQuery(document).ready(function($) {
        // let table = new DataTable('#result-sheet-table');
        $('#post_type').change(function() {
            var post_type = $(this).val();
            console.log(post_type);
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>', // WordPress AJAX URL provided via wp_localize_script
                data: {
                    action: 'get_practice_test_names', // Action hook to handle the AJAX request in your functions.php
                    post_type: post_type
                },
                dataType: 'json',
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    var options = '<option value="all">All</option>';
                    $.each(response.data, function(index, value) {
                        options += '<option data-id="' + value.id + '" value="' + value.tax + '">' + value.name + '</option>';
                    });
                    $('#test-name').html(options);

                },
                error: function(xhr, textStatus, errorThrown) {
                    // Handle error
                    console.error('Error:', errorThrown);
                }
            });
        });

        $('.get_result').click(function() {
            var post_type = $('#post_type').val();
            var test_name = $('#test-name').val();
            var test_id = $('#test-name option:selected').attr('data-id');
            if (post_type != null) {
                if (test_name != null) {
                    console.log(post_type);
                    console.log(test_name);
                    //reload this urlwith query string
                    window.location.href = '<?php echo admin_url('admin.php?page=wiz-quiz-result-sheet'); ?>&action=result_view&post_type=' + post_type + '&quiz_type=' + test_name + '&quiz_id=' + test_id;
                } else {
                    alert('Please select test name');
                }

            } else {
                alert('Please select post type');
            }
        });

        $('.get_csv').click(function() {
            // export the table to csv
            var csv = [];
            var rows = document.querySelectorAll("table tr");
            for (var i = 0; i < rows.length; i++) {
                var row = [],
                    cols = rows[i].querySelectorAll("td, th");
                for (var j = 0; j < cols.length; j++) {
                    var cellText = cols[j].innerText;
                    // Escape double quotes and wrap text in double quotes if it contains a comma
                    if (cellText.includes(',')) {
                        cellText = '"' + cellText.replace(/"/g, '""') + '"';
                    }
                    row.push(cellText);
                }
                csv.push(row.join(","));
            }
            // Download CSV file
            downloadCSV(csv.join("\n"), 'result-sheet.csv');
        });

        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;
            // CSV file
            csvFile = new Blob(["\uFEFF" + csv], {
                type: "text/csv;charset=utf-8;"
            });
            // Download link
            downloadLink = document.createElement("a");
            // File name
            downloadLink.download = filename;
            // Create a link to the file
            downloadLink.href = window.URL.createObjectURL(csvFile);
            // Hide download link
            downloadLink.style.display = "none";
            // Add the link to DOM
            document.body.appendChild(downloadLink);
            // Click download link
            downloadLink.click();
        }

    });
</script>