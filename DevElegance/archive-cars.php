Test<?php get_header(); ?>

<section class="page-wrap py-5">
    <div class="container">
        <div class="row">
            
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php if (is_active_sidebar('blog-sidebar')) : ?>
                    <?php dynamic_sidebar('blog-sidebar'); ?>
                <?php endif; ?>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <h1 class="mb-4"><?php echo single_cat_title('', false); ?></h1>

                <?php if (have_posts()) : ?>
                    <?php get_template_part('includes/section', 'archive'); ?>
                    
                    <!-- Pagination -->
                    <div class="pagination my-4">
                        <?php
                        global $wp_query;
                        echo paginate_links(array(
                            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged')),
                            'total' => $wp_query->max_num_pages
                        ));
                        ?>
                    </div>

                <?php else : ?>
                    <p>No posts found in this archive.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
