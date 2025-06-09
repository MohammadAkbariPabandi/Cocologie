<?php get_header(); ?>

<?php
// Get the page with homePage-content template
$home_page = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'homePage-content.php'
));

if($home_page) {
    // Set up post data
    global $post;
    $post = $home_page[0];
    setup_postdata($post);
    
    // Load the template
    get_template_part('homePage-content');
    
    // Reset post data
    wp_reset_postdata();
}
?>


<?php get_footer(); ?>

