<?php
class Elementor_wiz_taxonomy extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'wiz_taxonomy';
    }

    public function get_title()
    {
        return esc_html__('Taxonomy Loop', 'wiz-quiz');
    }

    public function get_icon()
    {
        return 'fas fa-sitemap';
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'wiz-quiz'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Select only the specific taxonomies
        $this->add_control(
            'taxonomy',
            [
                'label' => esc_html__('Select Taxonomy', 'wiz-quiz'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => [
                    'reading-practice' => esc_html__('Reading Practice', 'wiz-quiz'),
                    'mathenatical-practice' => esc_html__('Mathematical Practice', 'wiz-quiz'),
                    'thinking-skll-practice' => esc_html__('Thinking Skills Practice', 'wiz-quiz'),
                    'writtings-practice' => esc_html__('Writings Practice', 'wiz-quiz'),
                ],
                'default' => 'reading-practice',
            ]
        );

        $this->end_controls_section();
    }

    public function get_categories()
    {
        return ['basic'];
    }

    public function get_keywords()
    {
        return ['taxonomy', 'categories', 'terms'];
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $taxonomy = isset($settings['taxonomy']) ? $settings['taxonomy'] : 'reading-practice';
        // Get terms of the selected taxonomy
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
        ]);
        usort($terms, function($a, $b) {
    return strnatcmp($a->name, $b->name); // Natural order comparison
});

        if (is_user_logged_in() ) {

                $current_user = wp_get_current_user();
                // Display first and last name of the logged-in user
                echo '<h4 class="wel-name">Welcome, ' . esc_html($current_user->user_firstname) . ' ' . esc_html($current_user->user_lastname) . '</h4>';
            

            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    global $wpdb;
                    $term_id = $term->term_id;
                    $max_attempt = get_term_meta($term_id, 'max_attempt', true);
                    if($max_attempt == '') {
                        $max_attempt = 1;
                    }
                    // get quiz from database for this user & check how many time he/she attempted with this term id (quiz_type_id)
                    $tempcurrent_user = get_current_user_id();
                    if ($tempcurrent_user) {
                        $tempcurrent_use = get_user_by('id', $tempcurrent_user);
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
                    $total_attempt = count($all_results);
                    $remaining_attempt = $max_attempt - $total_attempt;
                    if ($remaining_attempt == 0) {
                        $remaining_class = 'no-retry';
                    }else {
                        $remaining_class = 'retry';
                    }
                    // Log the term details and the term URL 
                    $publisha_time = get_term_meta($term_id, 'publish_time', true);
                    $publish_time = strtotime($publisha_time);
                    $now = time();
                    if ($now < $publish_time) {
                        $available = 'Available Soon';
                        $available_class= 'available-soon';
                    } else {
                        $available = 'Available Now';
                        $available_class= 'available-now';
                    }
?>
                    <div class="wiz-btn">
                        <a href="<?php echo esc_url(get_term_link($term) . '?quizid=' . time()); ?>">
                            <?php echo esc_html($term->name); ?>
                        </a>
                        <div class="available <?php echo $available_class; ?>"><?php echo $available; ?></div>
                        <div class="reamining <?php echo $remaining_class; ?>"><?php echo $remaining_attempt; ?> Retry</div>
                    </div>
            <?php
                }
            } else {
                echo '<p>' . esc_html__('No terms found.', 'wiz-quiz') . '</p>';
            }
        } else {

            ?>
            <div class="wiz-popup">
                <div class="name-popup">
                    <div class="login-link">
                        Already Have an Account? <a href="<?php echo home_url('/login') ?>" class="login-link">Login Here</a>
                    </div>
                    
                </div>

            </div>
            <div class="wiz-tax-hidden">
                <?php

                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        global $wpdb;
                        $term_id = $term->term_id;
                        $max_attempt = get_term_meta($term_id, 'max_attempt', true);
                        if($max_attempt == '') {
                            $max_attempt = 1;
                        }
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
                        $total_attempt = count($all_results);
                        $remaining_attempt = $max_attempt - $total_attempt;
                        if ($remaining_attempt == 0) {
                            $remaining_class = 'no-retry';
                        }else {
                            $remaining_class = 'retry';
                        }
                        // Log the term details and the term URL 
                        $publisha_time = get_term_meta($term_id, 'publish_time', true);
                        $publish_time = strtotime($publisha_time);
                        $now = time();
                        if ($now < $publish_time) {
                            $available = 'Available Soon';
                            $available_class= 'available-soon';
                        } else {
                            $available = 'Available Now';
                            $available_class= 'available-now';
                        }
                ?>
                        <div class="wiz-btn">
                            <a href="<?php echo esc_url(get_term_link($term) . '?quizid=' . time()); ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
                            <div class="available <?php echo $available_class; ?>"><?php echo $available; ?></div>
                            <div class="reamining <?php echo $remaining_class; ?>"><?php echo $remaining_attempt; ?> Retry</div>
                        </div>
            <?php
                    }
                } else {
                    echo '<p>' . esc_html__('No terms found.', 'wiz-quiz') . '</p>';
                }
            }
            ?>
            </div>
    <?php
    }
}
