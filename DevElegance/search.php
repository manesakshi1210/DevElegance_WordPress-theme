<?php get_header(); ?>

<section class="page-wrap py-5">
    <div class="container">
        <div class="row">

            <!-- Main Content -->
            <div class="col-lg-9">
                <h1 class="mb-4">Search Results for: <?php echo get_search_query(); ?></h1>

                <?php get_template_part('includes/section', 'searchresults'); ?>


                <!-- Pagination -->
                <div class="d-flex justify-content-between mt-4">
                    <div><?php previous_posts_link('← Newer Posts'); ?></div>
                    <div><?php next_posts_link('Older Posts →'); ?></div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php if (is_active_sidebar('page-sidebar')): ?>
                    <?php dynamic_sidebar('page-sidebar'); ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<?php get_footer(); ?>