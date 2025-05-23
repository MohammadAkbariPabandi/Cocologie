
<?php
function koko_theme_setup() {
    // Add theme support (optional features)
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'koko_theme_setup');

function koko_enqueue_styles() {
    wp_enqueue_style('koko-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'koko_enqueue_styles');

