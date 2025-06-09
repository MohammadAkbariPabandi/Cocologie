<?php
/**
 * Template Name: homePage-content
 */
?>


<section class="container herosection">
    <div class="row">
        <div class="col-md-6 herosection-right">
            <p></p>
            <h1 class="mb-5 base" style="color: #091972;">
                <?php echo get_theme_field('homepagetitle'); ?>
            <span><h1 class="base" style="color: #d5752c;" ><?php echo get_theme_field('homepagesubtitle'); ?></h1></span> </h1>
            <h4 class="base">
                <?php echo get_theme_field('herosummary'); ?> 
            </h4>
            <div class="mt-4 d-flex gap-5">
                <a class="PillButton ZodiacPillButton" href="<?php echo get_permalink( get_page_by_title('contact-us') ); ?>">درخواست ثبت نام</a>
                <a class="PillButton ToryPillButton" href="#">مشاهده پکیج</a>
            </div>

        </div>
        <div class="col-md-6 herosection-left">
           <img class="scale-on-scroll" src="<?php echo get_template_directory_uri(); ?>/images/heroLeft.jpg" alt="kokology">
        </div>
    </div>
</section>


<section class="container kokoGames-section">
    <h2 class="mb-3 base" style="color: #d5752c;"><?php echo get_theme_field('kokogames-section-title', '', $post->ID); ?></h2>
    <h2 class="base" style="color: #04446a;">
        <?php echo get_theme_field('kokogames-section-subtitle'); ?>
    </h2>
    <div class="kokoGames-process-cards">
        <div class="row kokoGames-card">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <div class="col-md-4 kokoGames-card-content">
                    <div class="kokoGame-card-btn">
                        <a href="<?php the_permalink(); ?>" class="ZodiacButton"><?php echo $wp_query->current_post + 1; ?></a>
                    </div>
                    <h2 class="base kokoGames-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p class="base"><?php echo get_the_excerpt(); ?></p>
                </div>
            <?php endwhile; endif; ?>
        </div>   
        <img src="<?php echo get_template_directory_uri(); ?>/images/dash.png" >
    </div>
</section>

<section class="container kokoPlay-section">
    <div class="row">
        <div class="col-md-4 kokoPlay-header">
            <h4 class="base"><?php echo get_theme_field('kokoplay-section-headtitle', '', $post->ID); ?></h4>
            <h2 class="base" style="color: #70798B;"><?php echo get_theme_field('kokoplay-section-title', '', $post->ID); ?><span>
            <h1 style="color: #04446a;"><?php echo get_theme_field('kokoplay-section-subtitle', '', $post->ID); ?></h1></span></h2>
            <p class="mt-5 base body-base" style="color: #70798B;">
                <?php echo get_theme_field('kokoplay-section-summary', '', $post->ID); ?>
            </p>
        </div>
        <div class="col-md-5 kokoPlay-image">
            <img src="<?php echo get_template_directory_uri(); ?>/images/games.png" >
        </div>
        <div class="col-md-3 kokoPlay-list">
            <?php
            // Query for Games posts
            $games_args = array(
                'post_type'      => 'Game',
                'posts_per_page' => 3,
                'orderby'        => 'title',
                'order'          => 'ASC'
            );

            $games_query = new WP_Query($games_args);

            if ($games_query->have_posts()) :
                echo '<div class="plays-card-container">';
                while ($games_query->have_posts()) : $games_query->the_post();
                    ?>
                    <a href="<?php the_permalink(); ?>">
                        <div class="play-card">
                            <h3 class="base play-title" style="color: #04446a;"><?php the_title(); ?></h3>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="play-thumbnail">
                                    <?php the_post_thumbnail('medium'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="play-content base body-small" style="color: #70798B;">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    </a>
                    <?php
                endwhile;
                echo '</div>';
                wp_reset_postdata();
            else :
                echo '<p>No plays found.</p>';
            endif;
            ?>
        </div>
    </div>

</section>

<section class="faq-section base container">
    <h2 class="base mb-4">سوالات متداول</h2>
    <?php echo do_shortcode('[faq category="payment" toggle="true" count="3"]'); ?>
</section>
