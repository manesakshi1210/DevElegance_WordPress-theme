<?php get_header(); ?>

<section class="page-wrap">
  <div class="container">
    <section class="row">

      <!-- Left Sidebar -->
      <div class="col-lg-3">
        <?php 
        if (is_active_sidebar('page-sidebar')): 
          dynamic_sidebar('page-sidebar'); 
        endif;
        ?>
      </div>

      <!-- Main Content -->
      <div class="col-lg-9">
        <h1><?php the_title(); ?></h1>

        <?php if (has_post_thumbnail()): ?>
          <img src="<?php the_post_thumbnail_url(get_the_ID(), 'blog-small'); ?>" alt="<?php the_title(); ?>" class="card-img-top mb-3 img-thumbnail">
        <?php endif; ?>

        <?php get_template_part('includes/section', 'content'); ?>
        <?php echo do_shortcode('[local_business_card]'); ?>
      </div>

    </section>
  </div>
</section>

<?php get_footer(); ?>
