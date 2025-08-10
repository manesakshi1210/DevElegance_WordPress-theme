<?php
/*
Template Name: Contact Us
*/
get_header();
?>

<div class="container py-5">
  <h1 class="mb-4 text-center"><?php the_title(); ?></h1>

  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">

      <!-- Top Info Block -->
      <div class="text-center mb-4">
        <h4>Have questions? We're here to help.</h4>
        <p>Fill out the form below and our team will get back to you within 24 hours.</p>
      </div>

      <!-- Contact Form -->
      <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" class="p-4 border rounded bg-light">
        <div class="mb-3">
          <label for="cf_name" class="form-label">Name</label>
          <input type="text" name="cf_name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="cf_email" class="form-label">Email</label>
          <input type="email" name="cf_email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="cf_subject" class="form-label">Subject</label>
          <input type="text" name="cf_subject" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="cf_message" class="form-label">Message</label>
          <textarea name="cf_message" rows="5" class="form-control" required></textarea>
        </div>

        <div class="text-center">
          <button type="submit" name="cf_submit" class="btn btn-primary">Send Message</button>
        </div>
      </form>

      <!-- Bottom Contact Info -->
      <div class="mt-5 text-center">
        <h5>Other ways to reach us:</h5>
        <ul class="list-unstyled">
          <li><i class="fas fa-phone-alt"></i> +91-9876543210</li>
          <li><i class="fas fa-envelope"></i> info@example.com</li>
          <li><i class="fas fa-map-marker-alt"></i> 123, Your Company Address, City</li>
        </ul>
      </div>

      <!-- Post Submission Message -->
      <?php
      if (isset($_POST['cf_submit'])) {
        $name = sanitize_text_field($_POST['cf_name']);
        $email = sanitize_email($_POST['cf_email']);
        $subject = sanitize_text_field($_POST['cf_subject']);
        $message = esc_textarea($_POST['cf_message']);

        $to = get_option('admin_email');
        $headers = "From: $name <$email>\r\n";

        if (wp_mail($to, $subject, $message, $headers)) {
          echo '<div class="alert alert-success mt-3 text-center">Thanks! Your message has been sent.</div>';
        } else {
          echo '<div class="alert alert-danger mt-3 text-center">There was an issue sending your message.</div>';
        }
      }
      ?>

      <!-- Optional Page Editor Content Below -->
      <div class="mt-5 text-center">
        <?php get_template_part('includes/section', 'content'); ?>
      </div>

      <!-- Google Map Section -->

      <?php $map = get_theme_mod('google_map_embed_code'); ?>
      <?php if (!empty($map)): ?>
        <div class="mt-5">
          <h5 class="text-center mb-3">Our Location</h5>
          <div class="ratio ratio-16x9">
            <?php echo $map; ?>
          </div>
        </div>
      <?php endif; ?>








    </div>

  </div>
</div>
</div>

<?php get_footer(); ?>