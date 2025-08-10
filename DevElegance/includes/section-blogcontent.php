<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <p><?php echo get_the_date('l jS F , Y'); ?></p>
    <div><?php the_content(); ?></div>

    <?php
    $fname = get_the_author_meta('first_name');
    $lname = get_the_author_meta('last_name');
    ?>

    <p>Posted By <?php echo $fname . ' ' . $lname; ?></p>
 
    <?php
//    $tags = get_the_tags();
//     foreach ($tags as $tag):
     ?>
        <!-- <a href="<?php echo get_tag_link($tag->term_id); ?>">
            <?php echo $tag->name; ?>
        </a> -->
    <?php
//  endforeach;
 ?> 

    <?php
$tags = get_the_tags();

if ($tags && is_array($tags)) :
    foreach ($tags as $tag) : ?>
        <a href="<?php echo get_tag_link($tag->term_id); ?>" class="badge bg-success">
            <?php echo esc_html($tag->name); ?>
        </a>
    <?php endforeach;
endif;
?>


<?php
   $categories= get_the_category();
   foreach($categories as $cat):?>
   <a href="<?php echo get_category_link($cat->term_id);?>">
   <?php echo $cat->name;?>
   </a>
   <?php endforeach;?>


 <?php 
//  comments_template();
 ?>


       

<?php endwhile; endif; ?>
