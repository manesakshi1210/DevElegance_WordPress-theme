<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <!-- Post Date -->
    <p><?php echo get_the_date('l jS F, Y'); ?></p>

    <!-- Post Content -->
    <div><?php the_content(); ?></div>

    <!-- Tags -->
    <?php 
    $tags = get_the_tags();
    if ($tags && is_array($tags)) :
        foreach ($tags as $tag) : ?>
            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="badge bg-success">
                <?php echo esc_html($tag->name); ?>
            </a>
        <?php endforeach;
    endif;
    ?>

    <!-- Categories -->
    <?php 
    $categories = get_the_category();
    if ($categories && is_array($categories)) :
        foreach ($categories as $cat) : ?>
            <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="badge bg-primary">
                <?php echo esc_html($cat->name); ?>
            </a>
        <?php endforeach;
    endif;
    ?>

    <!-- Comments Section (Uncomment if needed) -->
    <?php // comments_template(); ?>

<?php endwhile; endif; ?>
