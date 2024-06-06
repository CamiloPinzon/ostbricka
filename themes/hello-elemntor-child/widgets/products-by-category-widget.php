<?php
class Products_By_Category_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'products_by_category_widget',
            __('Products By Category Widget', 'text_domain'),
            array('description' => __('A widget that displays products from a selected category', 'text_domain'))
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        $title = apply_filters('widget_title', $instance['title']);
        $category = !empty($instance['category']) ? $instance['category'] : '';
        $number_of_products = !empty($instance['number_of_products']) ? $instance['number_of_products'] : 5;

        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Query products
        if ($category) {
            $query_args = array(
                'post_type' => 'product',
                'posts_per_page' => $number_of_products,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $category,
                    ),
                ),
            );
            $products = new WP_Query($query_args);

            if ($products->have_posts()) {
                echo '<div class="custom-product-list">';
                while ($products->have_posts()) {
                    $products->the_post();
                    global $product;
                    echo '<div>';
                    echo '<a href="' . get_the_permalink() . '">';
                    echo '<div class="custom-product-list-item">';
                    echo get_the_post_thumbnail($product->get_id(), 'thumbnail');
                    echo '<div >' . get_the_title() . '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<p>' . __('No products found', 'text_domain') . '</p>';
            }

            wp_reset_postdata();
        } else {
            echo '<p>' . __('No category selected', 'text_domain') . '</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('New title', 'text_domain');
        $category = !empty($instance['category']) ? $instance['category'] : '';
        $number_of_products = !empty($instance['number_of_products']) ? $instance['number_of_products'] : 5;

        // Get existing product categories
        $categories = get_terms(
            array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
            )
        );
        ?>
        <p>
            <label
                for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'text_domain'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label
                for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_attr_e('Category:', 'text_domain'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('category')); ?>"
                name="<?php echo esc_attr($this->get_field_name('category')); ?>">
                <option value=""><?php esc_attr_e('Select Category', 'text_domain'); ?></option>
                <?php
                if (!empty($categories) && !is_wp_error($categories)) {
                    foreach ($categories as $cat) {
                        echo '<option value="' . esc_attr($cat->slug) . '" ' . selected($category, $cat->slug, false) . '>' . esc_html($cat->name) . '</option>';
                    }
                } else {
                    echo '<option value="">' . __('No categories found', 'text_domain') . '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <label
                for="<?php echo esc_attr($this->get_field_id('number_of_products')); ?>"><?php esc_attr_e('Number of Products:', 'text_domain'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number_of_products')); ?>"
                name="<?php echo esc_attr($this->get_field_name('number_of_products')); ?>" type="number" step="1" min="1"
                value="<?php echo esc_attr($number_of_products); ?>" size="3">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['category'] = (!empty($new_instance['category'])) ? strip_tags($new_instance['category']) : '';
        $instance['number_of_products'] = (!empty($new_instance['number_of_products'])) ? intval($new_instance['number_of_products']) : 5;

        return $instance;
    }
}
?>