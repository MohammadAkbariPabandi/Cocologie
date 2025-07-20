
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

// Register Custom Post Type for Games
function custom_post_type_Games() {

    $labels = array(
        'name'                  => _x( 'Games', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Game', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Games', 'text_domain' ),
        'name_admin_bar'        => __( 'Game', 'text_domain' ),
        'archives'              => __( 'Game Archives', 'text_domain' ),
        'attributes'            => __( 'Game Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Game:', 'text_domain' ),
        'all_items'             => __( 'All Games', 'text_domain' ),
        'add_new_item'          => __( 'Add New Game', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Game', 'text_domain' ),
        'edit_item'             => __( 'Edit Game', 'text_domain' ),
        'update_item'           => __( 'Update Game', 'text_domain' ),
        'view_item'             => __( 'View Game', 'text_domain' ),
        'view_items'            => __( 'View Games', 'text_domain' ),
        'search_items'          => __( 'Search Game', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Game', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Game', 'text_domain' ),
        'items_list'            => __( 'Games list', 'text_domain' ),
        'items_list_navigation' => __( 'Games list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter Games list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Game', 'text_domain' ),
        'description'           => __( 'Post Type for Games', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ,'page-attributes' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( 'Game', $args );

}
add_action( 'init', 'custom_post_type_Games', 0 );


/**
 * Safely get ACF field from current post
 */
// function get_theme_field($field_name, $default = '', $post_id = null) {
//     $value = get_field($field_name, $post_id);
//     return (!empty($value)) ? $value : $default;
// }
function get_theme_field($field_name, $default = '', $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $value = get_field($field_name, $post_id);
    if (empty($value)) {
        // For debugging - remove in production
        error_log("ACF Field '{$field_name}' not found for post {$post_id}");
        return $default;
    }
    return $value;
}

?>