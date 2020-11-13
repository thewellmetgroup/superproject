<?php
/**
 * Template Name: Leadership
 */
?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  
	<?php 
	$staff = get_field('staff');
	//print_r($staff);
	$counter=0;
	if (!empty($staff)) {
		echo '<div class="staff-wrapper">';
			foreach ($staff as $value) {
	    
	    		$staff_ID = $value['ID'];
	    		$staff_pic = $value['user_avatar'];
	    		$staff_first_name = $value['user_firstname'];
	    		$staff_last_name = $value['user_lastname'];
	    		$staff_bio = $value['user_description'];
	    		
	    		echo '<div class="col-xs-6 col-sm-3 col-md-2 staff-item">';
					//load the leadership/staff template
					include( locate_template( 'templates/content-staff-item.php' ) );				
				echo '</div>';
				
				}
			}
		echo '</div>';
	?>




<?php endwhile; ?>
