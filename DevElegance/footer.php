
<footer class="site-footer">
<!-- 
    <div class="menu-footer">
  <?php
      // wp_nav_menu(array(
      //   'theme_location' => 'footer-menu',
      //   'menu_class' => 'footer-menu',
      //   'fallback_cb' => false,
      // ));
      // ?>
     </div> -->
  <div class="footer-top container">
    <div class="row">

  <!-- Column 1: Logo + Site Name + Tagline -->
  <div class="col-md-3 footer-col footer-about">
    <?php if ($logo = get_theme_mod('lansinfosys_footer_logo')): ?>
      <img src="<?php echo esc_url($logo); ?>" alt="Footer Logo" class="footer-logo">
    <?php endif; ?>
    <h4 class="site-name"><?php bloginfo('name'); ?></h4>
    <p class="footer-tagline"><?php echo esc_html(get_theme_mod('lansinfosys_footer_tagline')); ?></p>
  </div>

  <!-- Column 2: NEW Vertical Menu -->
  <div class="col-md-3 footer-col footer-links">
    <h5><?php _e('Quick Links', 'lansinfosys'); ?></h5>
    <?php
      wp_nav_menu(array(
        'theme_location' => 'footer-links-menu',
        'menu_class' => 'footer-vertical-menu',
        'fallback_cb' => false,
      ));
    ?>
  </div>

  <!-- Column 3: Contact Info -->
  <div class="col-md-3 footer-col footer-contact">
    <h5><?php _e('Contact Us', 'lansinfosys'); ?></h5>
    <p><?php echo nl2br(esc_html(get_theme_mod('lansinfosys_footer_address'))); ?></p>
    <p><i class="fa-solid fa-phone"></i> <?php _e('Phone:', 'lansinfosys'); ?> <?php echo esc_html(get_theme_mod('lansinfosys_footer_phone')); ?></p>
    <p><i class="fa-solid fa-envelope"></i> <?php _e('Email:', 'lansinfosys'); ?>
      <a href="mailto:<?php echo esc_attr(get_theme_mod('lansinfosys_footer_email')); ?>"><?php echo esc_html(get_theme_mod('lansinfosys_footer_email')); ?></a>
    </p>
  </div>

  <!-- Column 4: Social Icons + Newsletter -->
  <div class="col-md-3 footer-col footer-social-newsletter">
    <h5><?php echo esc_html(get_theme_mod('lansinfosys_footer_headline', 'Follow Us')); ?></h5>
    <div class="social-icons mb-3">
     
    <?php echo do_shortcode('[social_links]'); ?>
    </div>
    <p class="newsletter-tagline"><?php echo esc_html(get_theme_mod('lansinfosys_footer_newsletter_tagline', 'Subscribe for latest updates')); ?></p>
    <form class="newsletter-form" action="#" method="post">
      <input type="email" name="newsletter_email" placeholder="Enter your email" required>
      <button type="submit"><i class="fas fa-paper-plane"></i></button>
    </form>
  </div>

</div>



 <!-- Footer Bottom -->
<div class="footer-bottom container d-flex justify-content-between align-items-center flex-wrap">
  <div class="left-footer">
    <a href="<?php echo esc_url( get_permalink( get_page_by_path('privacy-policy') ) ); ?>">
      <?php _e('Privacy Policy', 'lansinfosys'); ?>
    </a> |
    &copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?>. All rights reserved.
  </div>
  <div class="right-footer">
    Developed by Lans Info System
  </div>
</div>
</footer>


<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
