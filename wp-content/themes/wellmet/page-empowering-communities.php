<?php
/**
 * Template Name: Empowering communities
 */
?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  
	<?php 
	//loop through selected grantees and print them
	$posts = get_field('featured_grantees');

	if( $posts ):
    	
    	foreach( $posts as $post): // variable must be called $post (IMPORTANT)
        	setup_postdata($post);
        	echo '<div class="grantee-empower-listing">';
        		//load the grantee template
				include( locate_template( 'templates/content-grantee.php' ) );
			echo '</div>';
    	endforeach;
    	wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
	<?php endif; ?>




<?php endwhile; ?>
