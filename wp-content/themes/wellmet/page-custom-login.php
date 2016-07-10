<?php
/**
 * Template Name: Custom Login
 */
?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  
			<?php 
			if (!is_user_logged_in()) {
				if (isset($_GET['redirect'])) {
					$whereto = $_GET['redirect'];
				} else {
					$whereto = home_url();
				}
				$args = array(
				'redirect'       => $whereto
				);
				wp_login_form($args); ?>
				<p><a href="<?php echo wp_lostpassword_url(); ?>">Lost your password?</a><br>
				<a href="<?php echo wp_registration_url(); ?>">To start your application, <strong>register</strong> for a new account.</a></p>
			<?php } else { ?>
				<div class='login-status'>You are already logged in!</div>
			<?php } ?>




<?php endwhile; ?>
