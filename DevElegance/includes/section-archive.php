<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <div class="card md-3">

    <div class="card-body d-flex justify-content-center align-items-center">
       <?php if(has_post_thumbnail()): ?>
    <img src="<?php the_post_thumbnail_url(get_the_ID(), 'blog-small');?>" alt="<?php the_title();?>" class="card-img-top md-3 img-thumnbnail">
    <?php endif;?>

    <div class="blog-content">
    <h3><?php the_title(); ?></h3>
    
    <?php the_excerpt();?>
    <a href="<?php the_permalink();?>" class="btn btn-success"> Read More</a>
     </div>
     </div>
  </div>
<?php endwhile; endif; ?> 