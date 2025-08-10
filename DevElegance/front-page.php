<?php get_header(); ?>

<section class="page-wrap">
  <div class="container">

    <?php echo do_shortcode('[social_links]'); ?>

    <h1><?php the_title(); ?></h1>
    <?php get_template_part('includes/section', 'content'); ?>

    <?php get_search_form(); ?>

  </div>
<div class="pre-footer-section container">

  <!-- Block 1: Services -->
  <?php if (get_theme_mod('enable_block_1')): ?>
  <div class="pre-footer-block" style="background-image: url('<?php echo esc_url(get_theme_mod('pre_footer_services_bg')); ?>');">
    <h4>What We Provide</h4>
    <ul>
      <?php foreach (explode("\n", get_theme_mod('pre_footer_services_text')) as $service): ?>
        <li><?php echo esc_html($service); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <!-- Block 2: Products/Works -->
<?php if (get_theme_mod('enable_block_2')) : ?>
<div class="pre-footer-block">
  <h2 class="works-heading"><?php echo esc_html(get_theme_mod('pre_footer_works_heading')); ?></h2>
  <p class="works-subheading"><?php echo esc_html(get_theme_mod('pre_footer_works_subheading')); ?></p>

  <div class="row">
    <?php for ($i = 1; $i <= 3; $i++): 
      $icon = get_theme_mod("works_{$i}_icon");
      $title = get_theme_mod("works_{$i}_title");
      $desc = get_theme_mod("works_{$i}_desc");
      $link = get_theme_mod("works_{$i}_link");
      if ($title): ?>
        <div class="col-12 col-md-4 mb-4">
          <div class="card h-100 text-center work-card p-3">
            <?php if ($icon): ?>
             <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($title); ?>" class="work-icon mb-3 mx-auto d-block" style="max-width: 100px;">

            <?php endif; ?>
            <h4><?php echo esc_html($title); ?></h4>
            <p><?php echo esc_html($desc); ?></p>
            <a href="<?php echo esc_url($link); ?>">Read More</a>
          </div>
        </div>
    <?php endif; endfor; ?>
  </div>
</div>
<?php endif; ?>



  <!-- Block 3: Projects -->
  <?php if (get_theme_mod('enable_block_3')): ?>
  <div class="pre-footer-block">
    <h4><?php echo esc_html(get_theme_mod('pre_footer_projects_title')); ?></h4>
    <div class="projects-gallery-scroll">
      <div class="projects-gallery">
        <?php for ($i = 1; $i <= 6; $i++) : ?>
          <?php if ($img = get_theme_mod("project_{$i}_image")) : ?>
            <div class="project-item">
              <img src="<?php echo esc_url($img); ?>" alt="">
              <p><?php echo esc_html(get_theme_mod("project_{$i}_name")); ?></p>
            </div>
          <?php endif; ?>
        <?php endfor; ?>
      </div>
    </div>
  </div>
<?php endif; ?>


  <!-- Block 4: About Us -->
  <?php if (get_theme_mod('enable_block_4')): ?>
  <div class="pre-footer-block">
    <h4>About Us</h4>
    <?php if ($logo = get_theme_mod('pre_footer_about_logo')): ?>
      <img src="<?php echo esc_url($logo); ?>" alt="About Logo" class="about-logo">
    <?php endif; ?>
    <p class="about-desc"><?php echo nl2br(esc_html(get_theme_mod('pre_footer_about_desc'))); ?></p>
    <p><strong>Phone:</strong> <?php echo esc_html(get_theme_mod('pre_footer_about_phone')); ?></p>
    <p><strong>Address:</strong><br> <?php echo nl2br(esc_html(get_theme_mod('pre_footer_about_address'))); ?></p>
    <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr(get_theme_mod('pre_footer_about_email')); ?>"><?php echo esc_html(get_theme_mod('pre_footer_about_email')); ?></a></p>
  </div>
  <?php endif; ?>

  <!-- Block 5: Leadership -->
  <?php if (get_theme_mod('enable_block_5')): ?>
  <div class="pre-footer-block">
    <h4><?php echo esc_html(get_theme_mod('pre_footer_team_title', 'Leadership Team')); ?></h4>
    <div class="team-carousel">
      <div class="team-carousel-track">
        <?php for ($i = 1; $i <= 5; $i++) :
          $photo = get_theme_mod("team_member_{$i}_photo");
          $name = get_theme_mod("team_member_{$i}_name");
          $role = get_theme_mod("team_member_{$i}_role");
          if ($name || $role) : ?>
            <div class="team-card">
              <?php if ($photo): ?>
                <img src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_attr($name); ?>" />
              <?php endif; ?>
              <div class="team-name"><?php echo esc_html($name); ?></div>
              <div class="team-role"><?php echo esc_html($role); ?></div>
            </div>
        <?php endif; endfor; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Block 6: Clients -->
  <?php if (get_theme_mod('enable_block_6')): ?>
  <div class="pre-footer-block">
    <h4><?php echo esc_html(get_theme_mod('pre_footer_clients_title', 'Our Clients')); ?></h4>
    <div class="clients-carousel">
      <div class="clients-track">
        <?php for ($i = 1; $i <= 6; $i++):
          $logo = get_theme_mod("client_{$i}_logo");
          $name = get_theme_mod("client_{$i}_name");
          if ($logo): ?>
            <div class="client-item">
              <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($name); ?>">
              <p><?php echo esc_html($name); ?></p>
            </div>
        <?php endif; endfor; ?>
        <?php for ($i = 1; $i <= 6; $i++):
          $logo = get_theme_mod("client_{$i}_logo");
          $name = get_theme_mod("client_{$i}_name");
          if ($logo): ?>
            <div class="client-item">
              <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($name); ?>">
              <p><?php echo esc_html($name); ?></p>
            </div>
        <?php endif; endfor; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Block 7: Customer Reviews -->
  <?php if (get_theme_mod('enable_block_7')): ?>
<div class="pre-footer-block">
  <h4><?php echo esc_html(get_theme_mod('pre_footer_reviews_title', 'Customer Reviews')); ?></h4>
  <div class="review-carousel">
    <div class="review-carousel-track">
      <?php for ($i = 1; $i <= 5; $i++) :
        $img = get_theme_mod("review_{$i}_image");
        $msg = get_theme_mod("review_{$i}_message");
        $stars = (int) get_theme_mod("review_{$i}_stars", 5);
        $client = get_theme_mod("review_{$i}_client_name");
        if ($msg || $client): ?>
          <div class="review-card">
            <?php if ($img): ?>
              <img src="<?php echo esc_url($img); ?>" alt="Client Photo" class="review-img">
            <?php endif; ?>
            <p class="review-message"><?php echo esc_html($msg); ?></p>
            <div class="review-stars">
              <?php for ($s = 1; $s <= $stars; $s++): ?>
                <span>★</span>
              <?php endfor; ?>
              <?php for ($s = $stars + 1; $s <= 5; $s++): ?>
                <span style="opacity: 0.3;">★</span>
              <?php endfor; ?>
            </div>
            <div class="review-client"><?php echo esc_html($client); ?></div>
          </div>
      <?php endif; endfor; ?>
    </div>
  </div>
</div>
<?php endif; ?>


</div>



</section>



<?php get_footer(); ?>
