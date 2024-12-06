<?php
class Elementor_wiz_main_quiz extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'wiz_quiz_main';
    }

    public function get_title()
    {
        return esc_html__('Quiz', 'wiz-quiz');
    }

    public function get_icon()
    {
        return 'fas fa-infinity';
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

        if (!empty($terms) && !is_wp_error($terms)) {

            foreach ($terms as $term) {
                // Log the term details and the term URL
                error_log(print_r($term, true));
                error_log('Term URL: ' . get_term_link($term)); 
?>
                <div class="wiz-btn">
                    <a href="<?php echo esc_url(get_term_link($term)); ?>">
                        <?php echo esc_html($term->name); ?>
                    </a>
                </div>
<?php
            }
        } else {
            echo '<p>' . esc_html__('No terms found.', 'wiz-quiz') . '</p>';
        }
    }
}
