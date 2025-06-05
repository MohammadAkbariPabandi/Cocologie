<?php
/**
 * Template Name: play
 */
get_header(); ?>


<section class="container playsSection">
    <h2 class="base ئذ-5">بازی های کوکولوژی</h2>
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
                        $hero_image = get_field('hero');
                        if ($hero_image) : ?>
                            <img src="<?php echo esc_url($hero_image['url']); ?>" 
                                alt="<?php echo esc_attr($hero_image['alt']); ?>" 
                                class="play-card__image">
                        <?php endif; ?>
                        
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
</section>


<?php get_footer(); ?>