
<?php get_header(); ?>

<div class="single-post-container">
  <article class="single-post">

    <div >
      <h1 class="base"><?php the_title(); ?></h1>
      <p class="body-small base"> 
        <?php the_title(); ?>
      </p>
    </div>
    
    <div class="post-content">
      <?php the_content(); ?>
    </div>
  </article>
</div>

<?php get_footer(); ?>
