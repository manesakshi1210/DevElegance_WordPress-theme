<?php
/*
Template Name: Get Quotation
*/
get_header(); ?>

<section class="page-wrap">
  <div class="container">
    <h1 class="mb-4"><?php the_title(); ?></h1>

    <div class="quotation-form-wrapper">
      <form action="" method="post" class="quotation-form">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" name="name" id="name" class="form-control" required />
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" name="email" id="email" class="form-control" required />
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" name="phone" id="phone" class="form-control" required />
        </div>

        <div class="form-group">
          <label for="message">Project Details</label>
          <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Request</button>
      </form>
    </div>
  </div>
</section>

<?php get_footer(); ?>
