<?php get_header(); ?>

<div class="container herosection">
    <div class="row">
        <div class="col-md-6 herosection-right">
            <p class="body-lorge">به کوکولوژِی خوش آمدید</p>
            <h3>کوکولوژی چیست</h5>
            <h4>سفر به درون خود با استفاده از بازی</h6>
            <h5>فندک کو فنک نیست</h5>
            <a class="PillButton ZodiacPillButton" href="#">درخواست ثبت نام</a>
        </div>
        <div class="col-md-6 herosection-left">
            <h5>تخفیف 80 درصد برای مشارکت </h5>
        </div>
    </div>
</div>


<div class="container posts-cards">
    <div class="posts-cards-container">
        <?php
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1 // Show all posts
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post(); ?>
                <div class="post-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-card-image">
                            <?php the_post_thumbnail('medium'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="post-card-content">
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="post-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile;
            wp_reset_postdata();
        else :
            echo '<p>No posts found</p>';
        endif; ?>
    </div>
</div>

<?php get_footer(); ?>
