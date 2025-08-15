<?php
/**
 * Template Name: homePage-content
 */
?>


<section class="container herosection">
    <div class="row">
        <div class="col-md-6 herosection-right">
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo01.png" alt="Kokology Logo">
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

<section class="container keywordeSection">  
    
        <div class="row">
            <div class="col-md-3">
                <div class="keyword-container">
                    <div class="keyword-card">
                        <div class="keyword-face keyword-face1">
                            <div class="keyword-content">
                                
                                <h3 class="base">خودشناسی</h3>
                            </div>
                        </div>
                        <div class="keyword-face keyword-face2">
                            <div class="keyword-content">
                                <p class="base body-base">
                                    ۱. شناخت عمیق نقاط قوت و ضعف خود
                                    ۲. درک واقعی از ارزش‌ها، علایق و اهداف شخصی
                                    ۳. پذیرش خود بدون قضاوت و تلاش برای رشد فردی
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="keyword-container">
                    <div class="keyword-card">
                        <div class="keyword-face keyword-face1">
                            <div class="keyword-content">
                                
                                <h3 class="base">یادگیری</h3>
                            </div>
                        </div>
                        <div class="keyword-face keyword-face2">
                            <div class="keyword-content">
                                <p class="base">
                                    ۱. کسب دانش و مهارت‌های جدید (چه از طریق مطالعه، تجربه یا آموزش)
                                    ۲. تغییر پایدار در رفتار و تفکر بر اساس آموخته‌ها
                                    ۳. فرآیندی مادام‌العمر که انسان را به رشد و انعطاف‌پذیری فکری می‌رساند.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                 <div class="keyword-container">
                    <div class="keyword-card">
                        <div class="keyword-face keyword-face1">
                            <div class="keyword-content">
                               
                                <h3 class="base">سرگرمی</h3>
                            </div>
                        </div>
                        <div class="keyword-face keyword-face2">
                            <div class="keyword-content">
                                <p class="base">
                                    ۱. فعالیتی لذت‌بخش و اختیاری که انرژی ذهنی را بازمی‌گرداند و استرس را کاهش می‌دهد.
                                    ۲. فرصتی برای کشف خلاقیت، مهارت‌های جدید یا ارتباط با دیگران (مثل ورزش، هنر، بازی).
                                    ۳. نیاز طبیعی انسان برای تنوع و تجربه لحظات شاد، فارغ از دغدغه‌های روزمره.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                 <div class="keyword-container">
                    <div class="keyword-card">
                        <div class="keyword-face keyword-face1">
                            <div class="keyword-content">
                               
                                <h3 class="base">توسعه فردی</h3>
                            </div>
                        </div>
                        <div class="keyword-face keyword-face2">
                            <div class="keyword-content">
                                <p class="base">
                                    ۱. تلاش آگاهانه برای بهبود مهارت‌ها، دانش و توانایی‌های شخصی در ابعاد مختلف زندگی (ذهنی، عاطفی، حرفه‌ای).
                                    ۲. خروج از منطقه امن برای تبدیل نقاط ضعف به قوت و تقویت استعدادهای ذاتی.
                                    ۳. سفر مادام‌العمر به سوی نسخهٔ بهتر خود، با تعریف اهداف SMART و پیگیری مستمر آنها.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
            <h4 class="base">رویکرد ما</h4>
            <h2 class="base" style="color: #70798B;">راه حل های<span>
            <h1 style="color: #04446a;">متناسب با نیاز شما</h1></span></h2>
            <p class="mt-5 base body-base" style="color: #70798B;">
               کوکولوژی با هدف ترکیب علم روانشناسی و سرگرمی تأسیس شده است. ما معتقدیم که یادگیری و خودشناسی می‌تواند لذت‌بخش و سرگرم‌کننده باشد. تیم ما متشکل از روانشناسان متخصص و طراحان بازی است که با همکاری یکدیگر، تجربه‌ای منحصر به فرد برای شما فراهم کرده‌اند. هدف ما کمک به افراد برای شناخت بهتر خود و بهبود روابط بین فردی از طریق بازی‌ها روانشناسی است.
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
