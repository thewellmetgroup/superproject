<div class="item-wrapper">
	<div class="thumb">
		<?php
		// Check if the post has a Post Thumbnail assigned to it.
		if ( !empty($staff_pic) ) {
    		echo get_avatar( $staff_ID, '512' );
		} 
		?>
	</div>
	<div class="title content-padding">
		<h2><?php echo $staff_first_name.' '.$staff_last_name; ?></h2>
	</div>
	<div class="excerpt content-padding">
		<?php 	if (!empty($staff_bio)):
					echo $staff_bio;
				endif;
		 ?>
	</div>
</div>