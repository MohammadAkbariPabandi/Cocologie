<?php
/**
 * Single Play Template with singlePlay- classes
 * 
 * @package your-theme-name
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('singlePlay-container'); ?>>
            <header class="singlePlay-header">
                <h1 class="singlePlay-title"><?php the_title(); ?></h1>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="singlePlay-featuredImage">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="singlePlay-meta">
                    <?php
                    // Display categories
                    $categories = get_the_terms(get_the_ID(), 'category');
                    if ($categories && !is_wp_error($categories)) :
                        echo '<div class="singlePlay-categories">';
                        foreach ($categories as $category) {
                            echo '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                        }
                        echo '</div>';
                    endif;
                    
                    // Display tags
                    $tags = get_the_terms(get_the_ID(), 'post_tag');
                    if ($tags && !is_wp_error($tags)) :
                        echo '<div class="singlePlay-tags">';
                        foreach ($tags as $tag) {
                            echo '<a href="' . esc_url(get_term_link($tag)) . '">#' . esc_html($tag->name) . '</a>';
                        }
                        echo '</div>';
                    endif;
                    ?>
                </div>
            </header>
            
            <div class="singlePlay-content">
                <?php the_content(); ?>
            </div>
            
            <?php
            // Display custom fields
            $developer = get_post_meta(get_the_ID(), 'developer', true);
            $release_date = get_post_meta(get_the_ID(), 'release_date', true);
            
            if ($developer || $release_date) :
                echo '<div class="singlePlay-details">';
                if ($developer) echo '<p><strong>Developer:</strong> ' . esc_html($developer) . '</p>';
                if ($release_date) echo '<p><strong>Release Date:</strong> ' . esc_html($release_date) . '</p>';
                echo '</div>';
            endif;
            ?>
            
            <footer class="singlePlay-footer">
                <?php
                // Navigation to next/previous play
                the_post_navigation(array(
                    'prev_text' => '<span class="nav-subtitle">Previous Play</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">Next Play</span> <span class="nav-title">%title</span>',
                ));
                
                // Comments template
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            </footer>
        </article>
        
        <?php
    endwhile;
    ?>
</main>

<?php
get_sidebar();
get_footer();