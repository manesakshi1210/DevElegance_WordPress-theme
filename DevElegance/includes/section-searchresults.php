<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <div class="card mb-4 shadow-sm">
            <div class="row g-0">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="col-md-4">
                        <img src="<?php the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" 
                             class="img-fluid rounded-start h-100 w-100 object-fit-cover" 
                             alt="<?php the_title_attribute(); ?>">
                    </div>
                <?php endif; ?>

                <div class="col-md-8">
                    <div class="card-body">
                        <h3 class="card-title"><?php the_title(); ?></h3>
                        <p class="card-text"><?php the_excerpt(); ?></p>
                        <a href="<?php the_permalink(); ?>" class="btn btn-success">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else : ?>
    <div class="alert alert-warning mt-4" role="alert">
        ❌ No results found for your search.
    </div>
<?php endif; ?>
