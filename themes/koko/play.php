<?php
/**
 * Template Name: play
 */
get_header(); ?>

<section class="container playsSection">
    <?php
    // Get all terms in the 'game_category' taxonomy
    $terms = get_terms(array(
        'taxonomy' => 'category', // Replace with your taxonomy name if different
        'hide_empty' => true,
    ));
    echo '<h2 class="base mb-5">بازی های کوکولوژی بر اساس دسته بندی</h2>';
    
    if (!empty($terms) && !is_wp_error($terms)) :
        foreach ($terms as $term) :

            // Query posts in this category
            $plays_query = new WP_Query(array(
                'post_type' => 'game',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category', // Same taxonomy
                        'field' => 'term_id',
                        'terms' => $term->term_id,
                    ),
                ),
            ));

            if ($plays_query->have_posts()) :
                // Category title
                echo '<h3 class="base my-4">' . esc_html($term->name) . '</h3>';
                echo '<div class="plays-collection">';
                while ($plays_query->have_posts()) : $plays_query->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="base play-card__link">
                        <div class="play-card">
                            <?php
                            $hero_image = get_field('heroimage');
                            if ($hero_image) {
                                echo '<img src="' . esc_url($hero_image['url']) . '" alt="' . esc_attr($hero_image['alt']) . '">';
                            } else {
                                echo '<img src="' . get_template_directory_uri() . '/images/default.jpg" alt="Default hero image">';
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
                <?php endwhile;
                echo '</div>';
                wp_reset_postdata();
            endif;

        endforeach;
    else :
        echo '<p class="plays-not-found">No game categories found.</p>';
    endif;
    ?>
</section>

<?php get_footer(); ?>
