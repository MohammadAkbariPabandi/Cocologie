
<?php get_header(); ?>

<div class="single-post-container">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article class="single-post">
      <h1 class="post-title"><?php the_title(); ?></h1>

      <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-featured-image">
          <?php the_post_thumbnail('large'); ?>
        </div>
      <?php endif; ?>

      <div class="post-meta">
        Posted on <?php echo get_the_date(); ?> by <?php the_author(); ?>
      </div>

      <div class="post-content">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
