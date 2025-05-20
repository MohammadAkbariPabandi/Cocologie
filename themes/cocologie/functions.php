<?php
function your_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
    
    // Register menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'your-theme')
    ));
}
add_action('after_setup_theme', 'your_theme_setup');

// Enqueue scripts and styles
function your_theme_scripts() {
    wp_enqueue_style('your-theme-style', get_stylesheet_uri());
    wp_enqueue_script('your-theme-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'your_theme_scripts');