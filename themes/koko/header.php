<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header>
  <div class="container header-content">
    <!-- Logo + Title -->
    <div class="logo-title">
      <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Kokology Logo">
      <div class="site-title">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
      </div>
    </div>

    <!-- Hamburger Button -->
    <button class="menu-toggle" aria-label="Toggle Menu" onclick="openPopupMenu()">☰</button>

    <!-- Desktop Menu (hidden on mobile) -->
    <nav class="desktop-menu">
      <ul>
        <li><a href="#">خانه</a></li>
        <li><a href="#">بازی ها</a></li>
        <li><a href="#">پکیج ها</a></li>
        <li><a href="<?php echo get_permalink( get_page_by_title('about-us') ); ?>">درباره ما</a></li>
        <li><a href="<?php echo get_permalink( get_page_by_title('contact-us') ); ?>">ارتباط با ما</a></li>
      </ul>
    </nav>
  </div>
</header>

<!-- Fullscreen Popup Menu -->
<div class="popup-menu" id="popupMenu">
  <button class="popup-close" onclick="closePopupMenu()">×</button>
  <ul>
    <li><a href="#">خانه</a></li>
    <li><a href="#">بازی ها</a></li>
    <li><a href="#">پکیج ها</a></li>
    <li><a href="#">درباره ما</a></li>
    <li><a href="#">ارتباط با ما</a></li>
    <li><a class="PillButton ZodiacPillButton" href="#">درخواست ثبت نام</a></li>
  </ul>
  <div class="logo-title mt-5">
      <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Kokology Logo">
      <div class="site-title">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
      </div>
    </div>
</div>


