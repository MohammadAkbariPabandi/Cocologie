<?php
/**
 * Template Name: Contact Us
 */
get_header(); ?>

<div class="page-container">
  <h1>Contact Us</h1>
  <p>If you have any questions or inquiries, feel free to reach out to us using the form below or email us directly at <a href="mailto:info@example.com">info@example.com</a>.</p>

  <form class="contact-form" action="#" method="post">
    <input type="text" name="name" placeholder="Your Name" required />
    <input type="email" name="email" placeholder="Your Email" required />
    <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
    <button type="submit">Send Message</button>
  </form>
</div>

<?php get_footer(); ?>
