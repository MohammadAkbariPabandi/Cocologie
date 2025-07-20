<?php
/**
 * Single Play Template with singlePlay- classes
 * 
 * @package your-theme-name
 */

get_header();
?>

<main id="primary" class="site-main">    
    <article class="" id="post-<?php the_ID(); ?>" <?php post_class('singlePlay-container'); ?>>
        <header class="singlePlay-header">
             <div class="container singlePlay-meta">
                <p class="point"></p>
                <?php
                // Display categories
                $categories = get_the_terms(get_the_ID(), 'category');
                if ($categories && !is_wp_error($categories)) {
                    echo '<div class="singlePlay-categories">';
                    foreach ($categories as $category) {
                        // echo '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                        echo '<p class="base"> ' . esc_html($category->name) . ' </p>';
                    }
                }
                else {
                    echo '<p class="base" > بدون دسته بندی </p>';
                }
                echo '</div>';
                
                // // Display tags
                // $tags = get_the_terms(get_the_ID(), 'post_tag');
                // if ($tags && !is_wp_error($tags)) :
                //     echo '<div class="singlePlay-tags">';
                //     foreach ($tags as $tag) {
                //         echo '<a href="' . esc_url(get_term_link($tag)) . '">#' . esc_html($tag->name) . '</a>';
                //     }
                //     echo '</div>';
                // endif;
                ?>
            </div>
            <h1 class="container singlePlay-title base"><?php the_title(); ?></h1>    
            
            <div class="container">
                <h4 class="body-small base">
                    <?php
                    if (function_exists('get_field')) {
                        $summary = get_field('summaryPlay');
                        if ($summary) {
                            echo $summary;
                        } else {
                            echo "Field is empty or doesn't exist";
                        }
                    } else {
                        echo "ACF function not available";
                    }
                    ?>
                </h4>
            </div>

            <div class="container singlePlay-hero">
                <?php
                    // For image (heroPlayImage)
                    $image = get_field('heroimage');
                    if ($image) {
                        echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '">';
                    } else {
                        // Fallback image options:
                        echo '<img src="' . get_template_directory_uri() . '/images/default.jpg" alt="Default hero image">';
                        // OR use a placeholder service
                        // echo '<img src="https://via.placeholder.com/1200x600" alt="Placeholder image">';
                    }
                ?>
            </div>
        </header>

        <div class="container singlePlay-cover">
           <img class="scale-on-scroll" src="<?php echo get_template_directory_uri(); ?>/images/singlePlayCover.jpg" >
        </div>


        
        <div class="container singlePlay-body">
            <div class="row">
                <div class="col-md-8 singlePlay-content-right">
                    <div class="singlePlay-content base">
                        <?php the_content(); ?>
                    </div>
                </div>
                <div class="col-md-4 singlePlay-content-left">
                    <?php
                        // Query Game posts
                        $plays_query = new WP_Query(array(
                            'post_type' => 'game', // Change to your custom post type if different
                            'posts_per_page' => -1, // Show all games, or set a number like 6
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));

                        if ($plays_query->have_posts()) : ?>
                            <div class="plays-collection">
                                <?php while ($plays_query->have_posts()) : $plays_query->the_post(); ?>
                                    <a href="<?php the_permalink(); ?>" class="base play-card__link">
                                        <div class="play-card">
                                            <?php 
                                                $hero_image = get_field('heroimage');
                                                if ($hero_image) {
                                                    echo '<img src="' . esc_url($hero_image['url']) . '" alt="' . esc_attr($hero_image['alt']) . '">';
                                                } else {
                                                    // Fallback image options:
                                                    echo '<img src="' . get_template_directory_uri() . '/images/default.jpg" alt="Default hero image">';
                                                    // OR use a placeholder service
                                                    // echo '<img src="https://via.placeholder.com/1200x600" alt="Placeholder image">';
                                                }
                                            ?>                       
                                            <div class="play-card__content">
                                                <h3 class="base play-card__title"><?php the_title(); ?></h3>
                                                
                                                <?php $summary = get_field('summaryPlay'); ?>
                                                <?php if ($summary) : ?>
                                                    <p class="base play-card__summary"><?php echo esc_html($summary); ?></p>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                            <?php wp_reset_postdata(); ?>
                        <?php else : ?>
                            <p class="plays-not-found">No plays found.</p>
                        <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="container mb-5">
            <?php
            // Load the comments template
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            ?>            
        </div>

        
        
    </article>
</main>

<?php
get_footer();