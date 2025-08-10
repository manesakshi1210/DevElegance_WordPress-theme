<?php
/* Template Name: About Us Page */
get_header();
?>

<div class="about-us-section container">
  <h1><?php echo get_theme_mod('aboutus_title', 'About Us'); ?></h1>
  <p><?php echo get_theme_mod('aboutus_intro', 'Your company introduction goes here...'); ?></p>

  <section class="mission-vision">
    <h2>Our Mission</h2>
    <p><?php echo get_theme_mod('aboutus_mission', 'Mission text'); ?></p>

    <h2>Our Vision</h2>
    <p><?php echo get_theme_mod('aboutus_vision', 'Vision text'); ?></p>
  </section>

  <section>
    <?php if ($bg_img = get_theme_mod('leadership_bg_img')): ?>
      <div class="leadership-cover position-relative mb-5">
        <img src="<?php echo esc_url($bg_img); ?>" class="img-fluid w-100" style="height: 300px; object-fit: cover;" alt="Leadership Background">
        <?php if ($bg_text = get_theme_mod('leadership_bg_text')): ?>
          <div class="cover-text-top position-absolute start-50 translate-middle-x text-white text-center">
            <h3 class="fw-bold"><?php echo esc_html($bg_text); ?></h3>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </section>

  <section class="our-leadership" style="padding: 60px 20px;">
    <div class="container">
      <h2 class="mb-5 text-center text-white">Our Leadership</h2>
      <?php for ($i = 1; $i <= 2; $i++): ?>
        <div class="row align-items-center leadership-card mb-5 p-4 rounded shadow-sm">
          <div class="col-md-3 text-center mb-3 mb-md-0">
            <?php if ($img = get_theme_mod("leader_img_$i")): ?>
              <img src="<?php echo esc_url($img); ?>" class="rounded-circle" alt="Leader <?php echo $i; ?>" width="150" height="150">
            <?php endif; ?>
          </div>
          <div class="col-md-9 text-dark">
            <p class="lead mb-2" style="font-size: 1.1rem;">
              <span style="font-size: 2rem;   color: var(--hover-dark);">❝</span>
              <?php echo get_theme_mod("leader_desc_$i"); ?>
              <span style="font-size: 2rem;   color: var(--hover-dark);">❞</span>
            </p>
            <h5 class="fw-bold mt-3">— <?php echo get_theme_mod("leader_name_$i"); ?></h5>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  </section>

  <section class="awards-gallery py-5">
    <div class="container">
      <h2 class="text-center mb-4"><?php echo esc_html(get_theme_mod('awards_section_title', 'Our Awards')); ?></h2>
      <div class="row">
        <?php for ($i = 1; $i <= 6; $i++): ?>
          <?php
            $img = get_theme_mod("award_image_$i");
            $title = get_theme_mod("award_title_$i");
          ?>
          <?php if ($img): ?>
            <div class="col-6 col-md-4 mb-4 text-center ">
              <div class="award-item p-2 shadow-sm bg-white rounded">
                <img src="<?php echo esc_url($img); ?>" class="img-fluid mb-2 cursor-pointer"
                     data-bs-toggle="modal" data-bs-target="#awardModal<?php echo $i; ?>"
                     alt="Award <?php echo $i; ?>" style="max-height: 150px; object-fit: contain; ">
                <?php if ($title): ?>
                  <p class="fw-bold mb-0"><?php echo esc_html($title); ?></p>
                <?php endif; ?>
              </div>
            </div>

            <!-- Award Modal -->
            <div class="modal fade" id="awardModal<?php echo $i; ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-transparent border-0">
                  <div class="modal-header border-0">
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center">
                    <img src="<?php echo esc_url($img); ?>" class="img-fluid rounded" alt="Zoomed Award Image">
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        <?php endfor; ?>
      </div>
    </div>
  </section>

</div>

<section class="social-works py-5">
  <div class="container">
    <h2 class="text-center mb-4"><?php echo esc_html(get_theme_mod('social_section_title', 'Our Social Works')); ?></h2>
    <div class="row">
      <?php for ($i = 1; $i <= 6; $i++): ?>
        <?php $img = get_theme_mod("social_image_$i"); ?>
        <?php if ($img): ?>
          <div class="col-6 col-md-4 mb-4">
            <div class="social-item p-2 shadow-sm bg-white rounded">
              <img src="<?php echo esc_url($img); ?>" class="img-fluid w-100 cursor-pointer"
                   data-bs-toggle="modal" data-bs-target="#socialModal<?php echo $i; ?>"
                   alt="Social Work <?php echo $i; ?>" style="object-fit: cover; height: 200px;">
            </div>
          </div>

          <!-- Social Modal -->
          <div class="modal fade" id="socialModal<?php echo $i; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0">
                  <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                  <img src="<?php echo esc_url($img); ?>" class="img-fluid rounded" alt="Zoomed Social Work Image">
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endfor; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
