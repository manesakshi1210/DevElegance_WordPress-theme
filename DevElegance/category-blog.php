<?php get_header(); ?>

<section class="page-wrap py-5">
  <div class="container">
    <div class="row g-4">
      
      <!-- Main Content -->
      <div class="col-12">
        <h1 class="mb-4 border-bottom pb-2">
          <?php echo single_cat_title('', false); ?>
        </h1>

        <?php get_template_part('includes/section', 'archive'); ?>

        <!-- Pagination -->
        <div class="pagination mt-4 d-flex justify-content-between">
          <div class="previous">
            <?php previous_posts_link('<i class="fa fa-arrow-left me-1"></i> Newer Posts'); ?>
          </div>
          <div class="next">
            <?php next_posts_link('Older Posts <i class="fa fa-arrow-right ms-1"></i>'); ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php get_footer(); ?>
