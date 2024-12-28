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

        if (is_user_logged_in() || isset($_COOKIE['user_name'])) {
            // Check if the user is logged in
            if (is_user_logged_in()) {
                $current_user = wp_get_current_user();
                // Display first and last name of the logged-in user
                echo '<h4 class="wel-name">Welcome, ' . esc_html($current_user->user_firstname) . ' ' . esc_html($current_user->user_lastname) . '</h4>';
            } elseif (isset($_COOKIE['user_name'])) {
                // If the user is not logged in but the cookie is set
                $user_name = $_COOKIE['user_name'];
                echo '<h4 class="wel-name">Welcome, ' . esc_html($user_name) . '</h4>';
            } 

            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    // Log the term details and the term URL 
?>
                    <div class="wiz-btn">
                        <a href="<?php echo esc_url(get_term_link($term) . '?quizid=' . time()); ?>">
                            <?php echo esc_html($term->name); ?>
                        </a>
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
                    <div class="anonymus">
                        <h3>Want to procced without login?</h3>
                        <div class="name-box">
                            <input type="text" name="name" id="name"  required placeholder="Full Name">
                        </div>
                        <div class="wiz-btn set-cockies">
                            <a href=""> View Prectices</a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="wiz-tax-hidden"> 
                <?php

                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        // Log the term details and the term URL 
                ?>
                        <div class="wiz-btn">
                            <a href="<?php echo esc_url(get_term_link($term) . '?quizid=' . time()); ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
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
