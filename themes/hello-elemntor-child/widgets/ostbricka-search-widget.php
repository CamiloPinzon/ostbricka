<?php
class Ostbricka_Search_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'ostbricka_search_widget',
            'description' => 'A custom search widget',
        );
        parent::__construct('ostbricka_search_widget', 'Ostbricka Search Widget', $widget_ops);
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        ?>
        <form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
            <label>
                <span class="screen-reader-text"><?php echo _x('Search for:', 'label'); ?></span>
                <input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Search …', 'placeholder'); ?>"
                    value="<?php echo get_search_query(); ?>" name="s" />
            </label>
            <button type="submit" class="search-submit"><?php echo esc_html_x('Search', 'submit button'); ?></button>
        </form>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Search', 'text_domain');
        ?>
        <p>
            <label
                for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'text_domain'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

function register_ostbricka_search_widget()
{
    register_widget('Ostbricka_Search_Widget');
}
add_action('widgets_init', 'register_ostbricka_search_widget');
