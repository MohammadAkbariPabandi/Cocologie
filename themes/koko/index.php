<?php get_header(); ?>

<section class="container herosection">
    <div class="row">
        <div class="col-md-6 herosection-right">
            <p></p>
            <h3 class="mb-5" style="color: #091972;">کشف دنیای شگفت انگیز <span><h3 style="color: #d5752c;" >روانشناسی با بازی</h3></span> </h3>
            <h6>**با کوکولوژی، روانشناسی را به شیوه‌ای سرگرم‌کننده و جذاب کشف کنید!**  
                پکیج‌های متنوع ما برای تمام سنین طراحی شده‌اند تا یادگیری روانشناسی را لذت‌بخش کنند.  
                چه کودک باشید، چه نوجوان یا بزرگسال، کوکولوژی برای شما تجربه‌ای منحصربه‌فرد دارد.  
                با بازی، تست‌ها و فعالیت‌های خلاقانه، مفاهیم روانشناسی را به سادگی درک کنید.  
                همین حالا به دنیای رنگارنگ کوکولوژی وارد شوید و روانشناسی را با هیجان بیاموزید!
            </h6>
            <div class="mt-4 d-flex gap-5">
                <a class="PillButton ZodiacPillButton" href="#">درخواست ثبت نام</a>
                <a class="PillButton ToryPillButton" href="#">مشاهده پکیج</a>
            </div>

        </div>
        <div class="col-md-6 herosection-left">
           <img src="<?php echo get_template_directory_uri(); ?>/images/heroLeft.jpg" class="scale-on-scroll">
        </div>
    </div>
</section>


<section class="container kokoGames-section">
    <h4 class="mb-3" style="color: #d5752c;">رازهایت را با کوکولوژی کشف کن</h5>
    <h4 style="color: #04446a;">ما با ترکیب علم روانشناسی و سرگرمی، تجربه‌ای منحصر به فرد برای شما فراهم کرده‌ایم</h4>
    <div class="kokoGames-process-cards">
        <div class="row kokoGames-card">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <div class="col-md-4 kokoGames-card-content">
                    <div class="kokoGame-card-btn">
                        <a href="<?php the_permalink(); ?>" class="ZodiacButton"><?php echo $wp_query->current_post + 1; ?></a>
                    </div>
                    <h3 class="kokoGames-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php echo get_the_excerpt(); ?></p>
                </div>
            <?php endwhile; endif; ?>
        </div>   
        <img src="<?php echo get_template_directory_uri(); ?>/images/dash.png" >
    </div>
</section>

<section class="container kokoPlay-section">
    <div class="row">
        <div class="col-md-4 kokoPlay-header">
            <h6>رویکرد ما</h6>
            <h1 style="color: #70798B;">راه حل های<span><h1 style="color: #04446a;"> متناسب با نیاز شما</h1></span></h1>
            <p class="body-base" style="color: #70798B;">کوکولوژی با هدف ترکیب علم روانشناسی و سرگرمی تأسیس شده است. ما معتقدیم که یادگیری و خودشناسی می‌تواند لذت‌بخش و سرگرم‌کننده باشد.
                    تیم ما متشکل از روانشناسان متخصص و طراحان بازی است که با همکاری یکدیگر، تجربه‌ای منحصر به فرد برای شما فراهم کرده‌اند.
                    هدف ما کمک به افراد برای شناخت بهتر خود و بهبود روابط بین فردی از طریق بازی‌ها روانشناسی است.
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
                            <h5 class="play-title" style="color: #04446a;"><?php the_title(); ?></h5>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="play-thumbnail">
                                    <?php the_post_thumbnail('medium'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="play-content body-small" style="color: #70798B;">
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

</div>

<?php get_footer(); ?>

