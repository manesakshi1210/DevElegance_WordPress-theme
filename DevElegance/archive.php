<?php get_header(); ?>

<section class="page-wrap py-5">
  <div class="container">
    <div class="row g-4">
      
      <!-- Sidebar -->
      <div class="col-lg-3 order-lg-1">
        <?php if (is_active_sidebar('blog-sidebar')) : ?>
          <?php dynamic_sidebar('blog-sidebar'); ?>
        <?php endif; ?>
      </div>

      <!-- Main Content -->
      <div class="col-lg-9 order-lg-2">
        <h1 class="mb-4 border-bottom pb-2"><?php echo single_cat_title('', false); ?></h1>

        <?php if (have_posts()) : ?>
          <?php get_template_part('includes/section', 'archive'); ?>

          <!-- Pagination -->
          <div class="pagination mt-4">
            <?php
            global $wp_query;
            echo paginate_links(array(
              'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
              'format' => '?paged=%#%',
              'current' => max(1, get_query_var('paged')),
              'total' => $wp_query->max_num_pages,
              'prev_text' => '&laquo;',
              'next_text' => '&raquo;',
              'type' => 'list'
            ));
            ?>
          </div>

        <?php else : ?>
          <p class="text-muted">No posts found in this archive.</p>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<?php get_footer(); ?>
