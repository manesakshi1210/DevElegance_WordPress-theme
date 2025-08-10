<?php
$background_image = get_background_image();
$background_color = get_background_color();
$primary_color    = get_theme_mod('primary_theme_color', '#2563eb');
$secondary_color  = get_theme_mod('secondary_theme_color', '#facc15');
$tertiary_color   = get_theme_mod('tertiary_theme_color', '#0f172a');
$overlay_color    = get_theme_mod('background_overlay_color', 'transparent');
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    :root {
      --primary-color: <?php echo esc_html($primary_color); ?>;
      --secondary-color: <?php echo esc_html($secondary_color); ?>;
      --bg-color-3: <?php echo esc_html($tertiary_color); ?>;
    }

    body.custom-background {
      <?php if ($background_image) : ?>
        background-image: url('<?php echo esc_url($background_image); ?>');
        background-size: cover;
        background-repeat: <?php echo get_theme_mod('background_repeat', 'no-repeat'); ?>;
        background-position: <?php echo get_theme_mod('background_position_x', 'center'); ?> top;
        background-attachment: <?php echo get_theme_mod('background_attachment', 'scroll'); ?>;
      <?php else : ?>
        background-image: none;
      <?php endif; ?>
      background-color: #<?php echo esc_attr($background_color); ?>;
    }

    <?php if (!empty($overlay_color) && $overlay_color !== 'transparent') : ?>
    .background-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: <?php echo esc_attr($overlay_color); ?>;
      z-index: -1;
    }
    <?php endif; ?>

    .mobile-sidebar {
      position: fixed;
      /* top: 150px; */
      top: 0;
      right: -280px;
      width: 280px;
      height: 100%;
      background-color: #ffffff;
      box-shadow: 2px 0 10px rgba(0,0,0,0.3);
      z-index: 1050;
      transition: right 0.3s ease;
      overflow-y: auto;
    }

    .mobile-sidebar.show {
      right: 0;
    }

    #mobileMenuToggle {
      background: var(--primary-color);
      border: none;
    }

    #mobileMenuToggle:focus,
    #closeMobileMenu:focus {
      outline: none;
      box-shadow: none;
    }

    .mobile-sidebar .navbar-nav > li {
      margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
      .site-header {
        position: relative;
        overflow: visible;
      }

      .site-title {
        font-size: 1.5rem;
      }

      .carousel-caption {
        font-size: 0.9rem;
      }
    }
  </style>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if (!empty($overlay_color) && $overlay_color !== 'transparent') : ?>
  <div class="background-overlay"></div>
<?php endif; ?>

<?php lansinfosys_display_header_top(); ?>

<!-- Site Header -->
<header class="site-header border-bottom" style="background-color: <?php echo esc_attr($primary_color); ?>;">
  <div class="container py-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between">
      <!-- Logo + Title -->
      <div class="d-flex align-items-center">
        <?php if (has_custom_logo()) : ?>
          <div class="me-3"><?php the_custom_logo(); ?></div>
        <?php endif; ?>
        <div class="site-title-description d-none d-md-block">
          <h1 class="site-title mb-0">
            <a href="<?php echo esc_url(home_url()); ?>" class="text-decoration-none text-dark">
              <?php bloginfo('name'); ?>
            </a>
          </h1>
          <p class="site-description mb-0 small text-muted"><?php bloginfo('description'); ?></p>
        </div>
      </div>

      <!-- Mobile Menu Button -->
      <button id="mobileMenuToggle" class="btn d-md-none" aria-label="Open menu">
        <i class="fas fa-bars fs-4 text-white"></i>
      </button>

      <!-- Desktop Navigation -->
      <nav class="navbar d-none d-md-flex">
        <?php
          wp_nav_menu(array(
            'theme_location' => 'top-menu',
            'container'      => false,
            'menu_class'     => 'navbar-nav flex-row gap-4',
            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'depth'          => 1,
            'fallback_cb'    => false,
          ));
        ?>
      </nav>
    </div>
  </div>
</header>

<!-- Tagline -->
<div class="header-tagline-wrapper text-center py-3 px-2">
  <p class="header-tagline fs-4 fw-semibold">
    <?php echo esc_html(get_theme_mod('lansinfosys_header_tagline', 'Building Digital Solutions That Matter')); ?>
  </p>
  <p class="header-subtagline fs-6 text-muted">
    <?php echo esc_html(get_theme_mod('lansinfosys_header_subtagline', 'Trusted by Businesses Since 2005')); ?>
  </p>
</div>

<!-- Carousel -->
<?php
$slides = [];
for ($i = 1; $i <= 4; $i++) {
  $image  = get_theme_mod("carousel_slide_$i");
  $title  = get_theme_mod("carousel_slide_{$i}_title");
  $subtag = get_theme_mod("carousel_slide_{$i}_subtag");
  if ($image) {
    $slides[] = compact('image', 'title', 'subtag');
  }
}
if (!empty($slides)) :
?>
<div id="customHeaderCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <?php foreach ($slides as $index => $slide) : ?>
      <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
        <img src="<?php echo esc_url($slide['image']); ?>" class="d-block w-100 img-fluid" alt="Slide <?php echo $index + 1; ?>">
        <div class="carousel-caption d-none d-md-block text-start text-white bg-dark bg-opacity-50 p-3 rounded">
          <h2 class="fs-3"><?php echo esc_html($slide['title']); ?></h2>
          <p class="fs-6"><?php echo esc_html($slide['subtag']); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#customHeaderCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#customHeaderCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
<?php endif; ?>

<!-- Mobile Sidebar Menu -->
<div id="mobileSidebar" class="mobile-sidebar">
  <div class="sidebar-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
    <span class="fw-bold">Menu</span>
    <button id="closeMobileMenu" class="btn" aria-label="Close menu">
      <i class="fas fa-times fs-4 text-dark"></i>
    </button>
  </div>
  <div class="px-3 py-2">
    <?php
      wp_nav_menu(array(
        'theme_location' => 'top-menu',
        'container'      => false,
        'menu_class'     => 'navbar-nav flex-column',
        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'depth'          => 2,
        'fallback_cb'    => false,
      ));
    ?>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const mobileSidebar = document.getElementById('mobileSidebar');
    const toggleBtn = document.getElementById('mobileMenuToggle');
    const closeBtn = document.getElementById('closeMobileMenu');

    toggleBtn.addEventListener('click', function () {
      mobileSidebar.classList.add('show');
    });

    closeBtn.addEventListener('click', function () {
      mobileSidebar.classList.remove('show');
    });
  });
</script>
<?php wp_footer(); ?>
</body>
</html>
