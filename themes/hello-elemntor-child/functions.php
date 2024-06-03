<?php
require_once get_stylesheet_directory() . '/widgets/ostbricka-search-widget.php';
require_once get_stylesheet_directory() . '/widgets/products-by-category-widget.php';
require_once get_stylesheet_directory() . '/widgets/posts-widget.php';



function hello_elementor_child_enqueue_styles()
{
    wp_enqueue_style('hello-elementor-parent', get_template_directory_uri() . '/style.css');

    $child_style = get_stylesheet_directory_uri() . '/style.css';
    $child_min_style = get_stylesheet_directory_uri() . '/style.min.css';

    if (file_exists(get_stylesheet_directory() . '/style.min.css')) {
        wp_enqueue_style('hello-elementor-child', $child_min_style, array('hello-elementor-parent'), wp_get_theme()->get('Version'));
    } else {
        wp_enqueue_style('hello-elementor-child', $child_style, array('hello-elementor-parent'), wp_get_theme()->get('Version'));
    }
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_enqueue_styles');

function hello_elementor_custom_customizer($wp_customize) {
    // Add Section for Header and Hero Background Image
    $wp_customize->add_section('header_hero_background_section', array(
        'title' => __('Header and Hero Background', 'hello-elementor'),
        'priority' => 30,
        'description' => 'Upload an image for the header and hero background',
    ));

    // Add Setting for Header and Hero Background Image
    $wp_customize->add_setting('header_hero_background', array(
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));

    // Add Control for Header and Hero Background Image
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_hero_background', array(
        'label' => __('Header and Hero Background', 'hello-elementor'),
        'section' => 'header_hero_background_section',
        'settings' => 'header_hero_background',
    )));
}
add_action('customize_register', 'hello_elementor_custom_customizer');

function register_products_by_category_widget() {
    register_widget('Products_By_Category_Widget');
}
add_action('widgets_init', 'register_products_by_category_widget');


