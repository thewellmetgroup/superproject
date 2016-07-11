<?php
/**
 * Template Name: Custom Login
 */
?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  
			<?php 
			show_login(); ?>




<?php endwhile; ?>
