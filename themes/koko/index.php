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
            <div class="mt-4">
                <a class="PillButton ZodiacPillButton" href="#">درخواست ثبت نام</a>
                <a class="PillButton ToryPillButton" href="#">مشاهده پکیج</a>
            </div>

        </div>
        <div class="col-md-6 herosection-left">
           <img src="<?php echo get_template_directory_uri(); ?>/images/heroLeft.jpg" class="scale-on-scroll">
        </div>
    </div>
</section>

<section class="container kokoGames" >
    <div class="kokoGames-header">
        <h3>بازی های موجود در هر پکیج</h3>
    </div>
    <div class=" posts-cards">
         <div class="row post-card-container">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <div class="col-md-4">
                <div class="post-card">
                    <a href="<?php the_permalink(); ?>">
                        <div class="post-image" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');"></div>
                    </a>
                    <div class="post-content">
                        <h2 class="post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="post-excerpt"><?php echo get_the_excerpt(); ?></p>
                        <a class="read-more" href="<?php the_permalink(); ?>">Read More</a>
                    </div>
                </div>
            </div>
            <?php endwhile; endif; ?>
        </div>
    </div>
</section>


</div>

<?php get_footer(); ?>

