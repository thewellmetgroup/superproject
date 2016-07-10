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
		foreach ($staff as $value) {
    
    		$staff_ID = $value['ID'];
    		$staff_pic = $value['user_avatar'];
    		$staff_first_name = $value['user_firstname'];
    		$staff_last_name = $value['user_lastname'];
    		$staff_bio = $value['user_description'];
    		
    		if ($counter==0) :
				echo '<div class="row">';
			endif;
					echo '<div class="col-sm-6 col-md-3 staff-item">';
					//load the leadership/staff template
					include( locate_template( 'templates/content-staff-item.php' ) );				
					echo '</div>';
			if ($counter<4) :
				$counter++;
			endif;
			if ($counter==4) :
				$counter=0;
				echo '</div>';		
			endif;
			}
		}
	?>




<?php endwhile; ?>
