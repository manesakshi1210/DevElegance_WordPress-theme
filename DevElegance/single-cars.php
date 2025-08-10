<?php get_header(); ?>

<section class="container">
    <div class="container">

        <?php if (has_post_thumbnail()): ?>
            <h1 class="text-center"><?php the_title(); ?></h1>
            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'blog-large'); ?>" alt="<?php the_title(); ?>"
                class="card-img-top mb-3 img-thumbnail">
        <?php endif; ?>

        <div class="row">
            <!-- Left Column: Content -->
            <div class="col-lg-6">
                <?php get_template_part('includes/section', 'cars'); ?>
                <?php wp_link_pages(); ?>
            </div>

            <!-- Right Column: Meta Details -->
            <div class="col-lg-6">
                Here 
                <ul>
                    <li><strong>Colour:</strong> <?php the_field('colour'); ?></li>
                    <li><strong>Registration:</strong> <?php the_field('register'); ?></li>

                </ul>

                <h3>Features</h3>
                <ul>
                    <?php if (have_rows('features')): ?>
                        <?php while (have_rows('features')):
                            the_row();
                            $features = get_sub_field('features');
                            ?>
                            <li>
                                <?php echo $features; ?>
                            </li>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </ul>
                <?php

                $gallary = get_field('gallary');
                if ($gallary): ?>
                    <img src="<?php echo esc_url($gallary); ?>" alt="">
                <?php endif; ?>

            </div>
        </div>

    </div>
</section>

<?php get_footer(); ?>