<?php
class Posts_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'posts_widget', // Base ID
            'Posts Widget', // Name
            array('description' => __('A widget to display published posts with pagination', 'text_domain'))
        );
    }

    public function widget($args, $instance)
    {
        $posts_per_page = !empty($instance['posts_per_page']) ? $instance['posts_per_page'] : 5;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged
        );

        $query = new WP_Query($query_args);

        if ($query->have_posts()) {
            echo $args['before_widget'];

            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];

            echo '<ul>';
            while ($query->have_posts()):
                $query->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            endwhile;
            echo '</ul>';

            $big = 999999999; // need an unlikely integer
            echo paginate_links(
                array(
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?paged=%#%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $query->max_num_pages
                )
            );

            echo $args['after_widget'];

            wp_reset_postdata();
        }
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('New title', 'text_domain');
        $posts_per_page = !empty($instance['posts_per_page']) ? $instance['posts_per_page'] : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e(esc_attr('Title:')); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label
                for="<?php echo esc_attr($this->get_field_id('posts_per_page')); ?>"><?php _e(esc_attr('Posts per page:')); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('posts_per_page')); ?>"
                name="<?php echo esc_attr($this->get_field_name('posts_per_page')); ?>" type="number"
                value="<?php echo esc_attr($posts_per_page); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['posts_per_page'] = (!empty($new_instance['posts_per_page'])) ? intval($new_instance['posts_per_page']) : 5;
        return $instance;
    }
}

function register_posts_widget()
{
    register_widget('Posts_Widget');
}
add_action('widgets_init', 'register_posts_widget');
?>