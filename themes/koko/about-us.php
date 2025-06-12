<?php
/**
 * Template Name: About Us
 */
get_header(); ?>

<div class="container">
  <!-- بخش معرفی -->
  <section class="about-section">
    <div class="row">
      <div class="col-md-6 herosection-right">
          <p></p>
          <h1 style="color: #04446a;">
            <?php echo get_theme_field('abouttitle', '', $post->ID); ?>
          </h1>
          <h1 class="mb-5" style="color: #d5752c ;"><?php echo get_theme_field('aboutsubtitle', '', $post->ID); ?></h1>
          <h4 class="base"> 
            <?php echo get_theme_field('aboutsummary', '', $post->ID); ?>
          </h4>
      </div>
      <div class="col-md-6">
        <div class="herosection-left">
           <img class="scale-on-scroll" src="<?php echo get_template_directory_uri(); ?>/images/ana.jpg" >
        </div>
      </div>
    </section>


  <section class="container about-mission">
    <h2 class="mb-3 base" style="color: #d5752c;"><?php echo get_theme_field('about-missiontitle', '', $post->ID); ?></h2>
    <h2 class="base" style="color: #04446a;"><?php echo get_theme_field('about-missionsubtitle', '', $post->ID); ?></h2>
    <div class="row mt-5">
      <div class="col-md-6">
        <div class="about-mission-card">
          <div class="about-mission-card-title mb-4">
            <svg version="1.1" id="Icons" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-3.2 -3.2 38.40 38.40" xml:space="preserve" width="35px" height="35px" fill="#04446a" stroke="#04446a"><g id="SVGRepo_bgCarrier" stroke-width="0"><path transform="translate(-3.2, -3.2), scale(1.2)" d="M16,27.572945227846503C17.711326846802862,26.72160865341225,18.07853381431041,23.89500686307994,19.956310468160787,23.538119797172303C23.052565593645227,22.949650766646343,26.42760134034098,26.66836013618967,29.146465740938865,25.07435010700593C31.31288546141375,23.80422616809268,30.141312274521116,20.193607471315175,29.929093002002134,17.691298096790412C29.74235403955095,15.489430892251638,29.056382542009537,13.388434626386797,27.991191764451926,11.452340818244497C27.01204799862843,9.672646305080654,25.43761789121814,8.402263437661151,24.01877057315184,6.948679122131919C22.498538643458915,5.391228041363043,21.481557624320114,2.3835592069144407,19.312398976961543,2.5610690704025085C16.797173714361207,2.7668987793311786,16.460993761463236,7.3559638740478706,13.971700663466507,7.770866107069629C11.146198175532904,8.241805946414225,8.475259881788572,3.2435181779576525,6.052164505367141,4.771214953618967C3.855835711945597,6.15594146857668,7.314259621818776,9.937355219150561,6.583634946173616,12.428843784923616C5.937780003147659,14.63126078415276,1.8094470365727409,15.418684281676548,2.270478771195794,17.667065696293523C2.7524765421706756,20.017695270704046,7.123698760194632,19.214424507383516,8.392621336873326,21.250994354384414C9.763173746431017,23.450676007417517,7.2544870830560075,27.208854182943604,9.247758913600546,28.865320509674234C11.036197269609833,30.35156431684907,13.918007808904644,28.608677299295834,16,27.572945227846503" fill="#d5752c" strokewidth="0"></path></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.32"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:none;stroke:#04446a;stroke-width:3.2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;} </style> <line class="st0" x1="16" y1="16" x2="22" y2="10"></line> <polygon class="st0" points="30,6 26,6 26,2 22,6 22,10 26,10 "></polygon> <circle class="st0" cx="16" cy="16" r="6"></circle> <path class="st0" d="M27,9c1.3,2,2,4.4,2,7c0,7.2-5.8,13-13,13S3,23.2,3,16S8.8,3,16,3c2.6,0,5,0.7,7,2"></path> </g></svg>
            <h2 class="base" style="color: #04446a;"><?php echo get_theme_field('about-missionrighttitle', '', $post->ID); ?></h2>
          </div>
          <p class="body-base base">
            <?php echo get_theme_field('about-missionrightcontent', '', $post->ID); ?>
          </p>
        </div>
      </div>
      <div class="col-md-6">
         <div class="about-mission-card">
          <div class="about-mission-card-title mb-4">
            <svg fill="#04446a" width="35px" height="35px" viewBox="0 0 24.00 24.00" id="telescope-2" data-name="Line Color" xmlns="http://www.w3.org/2000/svg" class="icon line-color"><g id="SVGRepo_bgCarrier" stroke-width="0" transform="translate(0,0), scale(1)"><path transform="translate(0, 0), scale(0.75)" d="M16,31.581220960930775C18.15102714591882,31.683993389860284,20.814726216717464,31.46110551274298,22.12171521326279,29.749597488357118C23.647441108472687,27.75165250215808,21.473389247763308,24.305947577452674,23.050617873231737,22.34840484785649C24.500069809168657,20.549449355116966,28.92941291452629,22.630102400973104,29.63140415643597,20.429111697737152C30.335865341299176,18.220376850196153,26.646433598764173,16.961539120721696,25.39017941038506,15.01305237406398C24.684224266404883,13.918095434214875,24.139676074623825,12.737036409774273,23.852731897675852,11.466223125002887C23.438204439586112,9.630371266129053,24.42033081462221,7.442640847218279,23.333816292545084,5.905867846884018C22.331197077024697,4.487756897862876,20.261169518510513,3.644533812697867,18.556077357781,3.9746015004274042C16.673675579296034,4.338992362524074,16.0864066024181,7.192476286937934,14.229052359543132,7.668346408396781C12.484686658844522,8.115267894184655,10.55359374720118,5.445753158703103,9.035296113238761,6.413907488826515C7.5700580192768046,7.348227989365579,8.65410442014433,9.823401498328327,8.213292731577345,11.50434246247536C7.88775692340931,12.745703545765252,7.542297286901771,14.00128924848602,6.775676091790112,15.030484489784696C5.179443623122229,17.173439669259146,1.3047572791800233,18.101275209129547,1.3090165268882252,20.773389887446122C1.3125156418238246,22.968621773708275,5.200726764677645,22.778433557835978,6.790439109635331,24.292325871891556C8.234967344924648,25.66795851371958,8.569164302292647,27.938874075964723,10.133305492973145,29.176811604044154C11.808814407327025,30.502890973393093,13.865657557510387,31.479245698834163,16,31.581220960930775" fill="#d5752c" strokewidth="0"></path></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.048"></g><g id="SVGRepo_iconCarrier"><path id="secondary" d="M14,21l-3-9M8,21l3-9" style="fill: none; stroke: #04446a; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path><path id="primary" d="M4.81,4,15.44,6.81A1,1,0,0,1,16.14,8l-1,3.86a1,1,0,0,1-1.23.71L3.26,9.76ZM21,9.34,16.14,8l-1,3.86L20,13.2ZM5.07,3,3,10.73" style="fill: none; stroke: #04446a; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path></g></svg>
            <h2 class="base" style="color: #04446a;"><?php echo get_theme_field('about-missionlefttitle', '', $post->ID); ?></h2>
          </div>
          <p class="body-base base">
            <?php echo get_theme_field('about-missionleftcontent', '', $post->ID); ?>
          </p>
        </div>
      </div>
    </div>
 
  </section>

  <section class="container">
    <h1 class="base mb-5 py-3" style="color: #04446a;"><?php echo get_theme_field('abouttimetitle', '', $post->ID); ?></h1>
    <div class="aboutTime">
      <div class="aboutTime-card">
        <img  src="<?php echo get_template_directory_uri(); ?>/images/ana.jpg" alt="">
        <div class="aboutTime-card-content">
            <h4 class="base" style="color: #000000;">آناهیتا موگویی</h4>
            <h4 class="base" style="color: #d5752c;">متخصص طراحی بازی</h4>
            <p class="base m-0" style="color: #70798B;">
              کارشناسی ارشد سینما، کارگردان و تهیه کننده   
            </p>
        </div>
      </div>
      <div class="aboutTime-card">
        <img  src="<?php echo get_template_directory_uri(); ?>/images/sina.png" alt="">
        <div class="aboutTime-card-content">
            <h4 class="base" style="color: #000000;">سینا راد</h4>
            <h4 class="base" style="color: #d5752c;">متخصص فناوری اطلاعات</h4>
            <p class="base m-0" style="color: #70798B;">
              طراحی حوزه دیجیتال   
            </p>
        </div>
      </div>
      <div class="aboutTime-card">
        <img  src="<?php echo get_template_directory_uri(); ?>/images/heroLeft.jpg" alt="">
        <div class="aboutTime-card-content">
            <h4 class="base" style="color: #000000;">مهدی مسلمی فر</h4>
            <h4 class="base" style="color: #d5752c;">متخصص روانشناسی</h4>
            <p class="base m-0" style="color: #70798B;">
              دکترای علم روانشناسی   
            </p>
        </div>
      </div>
    </div>
    
  </section>

  <section class="container aboutHistory">
      <h2 class="base mb-5" style="color: #04446a;"><?php echo get_theme_field('abouttimehestorytitle', '', $post->ID); ?></h2>
      <div class="row">
        <div class="col-md-6 aboutHistory-right">
            <div class="aboutHistory-card">
                <h3 class="base">1399</h3>
                <p class="body-base base">
                  کوکولوژی با ایده ترکیب روانشناسی و بازی توسط دکتر نیلوفر احمدی تأسیس شد. اولین پکیج بازی با تمرکز بر مدیریت هیجانات برای کودکان طراحی شد.
                </p>
            </div>
            <span><svg width="45px" height="45px" viewBox="0 0 100.00 100.00" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--gis" preserveAspectRatio="xMidYMid meet" fill="#d5752c" stroke="#d5752c" stroke-width="0.001"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#04446a" stroke-width="20"><path d="M50 37.45c-6.89 0-12.55 5.66-12.55 12.549c0 6.89 5.66 12.55 12.55 12.55c6.655 0 12.112-5.294 12.48-11.862a3.5 3.5 0 0 0 .07-.688a3.5 3.5 0 0 0-.07-.691C62.11 42.74 56.653 37.45 50 37.45zm0 7c3.107 0 5.55 2.442 5.55 5.549s-2.443 5.55-5.55 5.55c-3.107 0-5.55-2.443-5.55-5.55c0-3.107 2.443-5.549 5.55-5.549z" fill="#d5752c"></path></g><g id="SVGRepo_iconCarrier"><path d="M50 37.45c-6.89 0-12.55 5.66-12.55 12.549c0 6.89 5.66 12.55 12.55 12.55c6.655 0 12.112-5.294 12.48-11.862a3.5 3.5 0 0 0 .07-.688a3.5 3.5 0 0 0-.07-.691C62.11 42.74 56.653 37.45 50 37.45zm0 7c3.107 0 5.55 2.442 5.55 5.549s-2.443 5.55-5.55 5.55c-3.107 0-5.55-2.443-5.55-5.55c0-3.107 2.443-5.549 5.55-5.549z" fill="#d5752c"></path></g></svg></span>
        </div>
        <div class="col-md-6 aboutHistory-left">
          
        </div>

        <div class="col-md-6 aboutHistory-right">
          <span><svg width="45px" height="45px" viewBox="0 0 100.00 100.00" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--gis" preserveAspectRatio="xMidYMid meet" fill="#d5752c" stroke="#d5752c" stroke-width="0.001"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#04446a" stroke-width="20"><path d="M50 37.45c-6.89 0-12.55 5.66-12.55 12.549c0 6.89 5.66 12.55 12.55 12.55c6.655 0 12.112-5.294 12.48-11.862a3.5 3.5 0 0 0 .07-.688a3.5 3.5 0 0 0-.07-.691C62.11 42.74 56.653 37.45 50 37.45zm0 7c3.107 0 5.55 2.442 5.55 5.549s-2.443 5.55-5.55 5.55c-3.107 0-5.55-2.443-5.55-5.55c0-3.107 2.443-5.549 5.55-5.549z" fill="#d5752c"></path></g><g id="SVGRepo_iconCarrier"><path d="M50 37.45c-6.89 0-12.55 5.66-12.55 12.549c0 6.89 5.66 12.55 12.55 12.55c6.655 0 12.112-5.294 12.48-11.862a3.5 3.5 0 0 0 .07-.688a3.5 3.5 0 0 0-.07-.691C62.11 42.74 56.653 37.45 50 37.45zm0 7c3.107 0 5.55 2.442 5.55 5.549s-2.443 5.55-5.55 5.55c-3.107 0-5.55-2.443-5.55-5.55c0-3.107 2.443-5.549 5.55-5.549z" fill="#d5752c"></path></g></svg></span>
        </div>
        <div class="col-md-6 aboutHistory-left">
          <div class="aboutHistory-card">
                <h3 class="base">1400</h3>
                <p class="body-base base">
                  گسترش تیم و توسعه سه پکیج بازی جدید. شروع همکاری با مدارس و مراکز مشاوره در تهران.
                </p>
            </div>
        </div>

        <div class="col-md-6 aboutHistory-right">
          <div class="aboutHistory-card">
                <h3 class="base">1401</h3>
                <p class="body-base base">
                  راه‌اندازی وب‌سایت و فروشگاه آنلاین. توسعه اولین پکیج بازی برای بزرگسالان با تمرکز بر مدیریت استرس.
                </p>
          </div>
          <span><svg width="45px" height="45px" viewBox="0 0 100.00 100.00" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--gis" preserveAspectRatio="xMidYMid meet" fill="#d5752c" stroke="#d5752c" stroke-width="0.001"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#04446a" stroke-width="20"><path d="M50 37.45c-6.89 0-12.55 5.66-12.55 12.549c0 6.89 5.66 12.55 12.55 12.55c6.655 0 12.112-5.294 12.48-11.862a3.5 3.5 0 0 0 .07-.688a3.5 3.5 0 0 0-.07-.691C62.11 42.74 56.653 37.45 50 37.45zm0 7c3.107 0 5.55 2.442 5.55 5.549s-2.443 5.55-5.55 5.55c-3.107 0-5.55-2.443-5.55-5.55c0-3.107 2.443-5.549 5.55-5.549z" fill="#d5752c"></path></g><g id="SVGRepo_iconCarrier"><path d="M50 37.45c-6.89 0-12.55 5.66-12.55 12.549c0 6.89 5.66 12.55 12.55 12.55c6.655 0 12.112-5.294 12.48-11.862a3.5 3.5 0 0 0 .07-.688a3.5 3.5 0 0 0-.07-.691C62.11 42.74 56.653 37.45 50 37.45zm0 7c3.107 0 5.55 2.442 5.55 5.549s-2.443 5.55-5.55 5.55c-3.107 0-5.55-2.443-5.55-5.55c0-3.107 2.443-5.549 5.55-5.549z" fill="#d5752c"></path></g></svg></span>
        </div>
        <div class="col-md-6 aboutHistory-left">
          
        </div>

        <div class="col-md-6 aboutHistory-right">
          <span><svg width="45px" height="45px" viewBox="0 0 100.00 100.00" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--gis" preserveAspectRatio="xMidYMid meet" fill="#d5752c" stroke="#d5752c" stroke-width="0.001"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#04446a" stroke-width="20"><path d="M50 37.45c-6.89 0-12.55 5.66-12.55 12.549c0 6.89 5.66 12.55 12.55 12.55c6.655 0 12.112-5.294 12.48-11.862a3.5 3.5 0 0 0 .07-.688a3.5 3.5 0 0 0-.07-.691C62.11 42.74 56.653 37.45 50 37.45zm0 7c3.107 0 5.55 2.442 5.55 5.549s-2.443 5.55-5.55 5.55c-3.107 0-5.55-2.443-5.55-5.55c0-3.107 2.443-5.549 5.55-5.549z" fill="#d5752c"></path></g><g id="SVGRepo_iconCarrier"><path d="M50 37.45c-6.89 0-12.55 5.66-12.55 12.549c0 6.89 5.66 12.55 12.55 12.55c6.655 0 12.112-5.294 12.48-11.862a3.5 3.5 0 0 0 .07-.688a3.5 3.5 0 0 0-.07-.691C62.11 42.74 56.653 37.45 50 37.45zm0 7c3.107 0 5.55 2.442 5.55 5.549s-2.443 5.55-5.55 5.55c-3.107 0-5.55-2.443-5.55-5.55c0-3.107 2.443-5.549 5.55-5.549z" fill="#d5752c"></path></g></svg></span>
        </div>
        <div class="col-md-6 aboutHistory-left">
          <div class="aboutHistory-card">
                <h3 class="base">1402</h3>
                <p class="body-base base">
                  دریافت جایزه نوآوری در آموزش روانشناسی. گسترش فعالیت به سراسر کشور و برگزاری اولین کنفرانس سالانه "بازی و سلامت روان".
                </p>
          </div>
        </div>

        <div class="col-md-6 aboutHistory-right">
          <div class="aboutHistory-card">
                <h3 class="base">1403</h3>
                <p class="body-base base">
                   امروز، کوکولوژی با بیش از ۲۰ پکیج بازی مختلف و تیمی متشکل از ۱۵ متخصص روانشناسی و طراحی بازی، به هزاران خانواده و مرکز آموزشی در سراسر کشور خدمات ارائه می‌دهد.
                </p>
          </div>
          <span><svg width="45px" height="45px" viewBox="0 0 100.00 100.00" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--gis" preserveAspectRatio="xMidYMid meet" fill="#d5752c" stroke="#d5752c" stroke-width="0.001"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#04446a" stroke-width="20"><path d="M50 37.45c-6.89 0-12.55 5.66-12.55 12.549c0 6.89 5.66 12.55 12.55 12.55c6.655 0 12.112-5.294 12.48-11.862a3.5 3.5 0 0 0 .07-.688a3.5 3.5 0 0 0-.07-.691C62.11 42.74 56.653 37.45 50 37.45zm0 7c3.107 0 5.55 2.442 5.55 5.549s-2.443 5.55-5.55 5.55c-3.107 0-5.55-2.443-5.55-5.55c0-3.107 2.443-5.549 5.55-5.549z" fill="#d5752c"></path></g><g id="SVGRepo_iconCarrier"><path d="M50 37.45c-6.89 0-12.55 5.66-12.55 12.549c0 6.89 5.66 12.55 12.55 12.55c6.655 0 12.112-5.294 12.48-11.862a3.5 3.5 0 0 0 .07-.688a3.5 3.5 0 0 0-.07-.691C62.11 42.74 56.653 37.45 50 37.45zm0 7c3.107 0 5.55 2.442 5.55 5.549s-2.443 5.55-5.55 5.55c-3.107 0-5.55-2.443-5.55-5.55c0-3.107 2.443-5.549 5.55-5.549z" fill="#d5752c"></path></g></svg></span>
        </div>
        <div class="col-md-6 aboutHistory-left">

        </div>
      </div>
  </section>

</div>


<?php get_footer(); ?>
