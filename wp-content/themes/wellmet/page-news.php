<?php
/**
 * Template Name: News & Updates
 */
?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'news'); ?>
<?php endwhile; ?>
