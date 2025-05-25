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
    <h4 class="mb-3" style="color: #d5752c;">چرا کوکولوژی؟</h5>
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

</div>

<?php get_footer(); ?>

