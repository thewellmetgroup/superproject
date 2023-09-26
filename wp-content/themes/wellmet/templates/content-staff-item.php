<?php if (!empty($staff_bio)): ?>
<a href="#inline-<?php echo $staff_ID; ?>" class="bio-link" data-lity>
<?php endif; ?>
	<div class="item-wrapper">
		<div class="thumb">
			<?php
			// Check if the post has a Post Thumbnail assigned to it.
			if ( !empty($staff_pic) ) {
	    		//echo get_avatar( $staff_ID, '512', null, $staff_first_name.' '.$staff_last_name, array('scheme' => 'https'));
				//print_r($staff_pic);
	    		// Image variables.
			    $url = $staff_pic['url'];
			    $title = $staff_pic['title'];
			    $alt = $staff_pic['alt'];
			    $caption = $staff_pic['caption'];

			    // Thumbnail size attributes.
			    $size = 'thumbnail';
			    $thumb = $staff_pic['sizes'][ $size ];
			    $width = $staff_pic['sizes'][ $size . '-width' ];
			    $height = $staff_pic['sizes'][ $size . '-height' ];
			    echo '<img src="'.esc_url($thumb).'" alt="'.esc_attr($alt).'" width="'.$width.'" height="'.$height.'">';

			}

			

			?>
		</div>
		<div class="title content-padding">
			<h3><?php echo $staff_first_name.' '.$staff_last_name; ?></h3>
		</div>
		
		<?php 	
			if (!empty($staff_bio)):
				echo '<div id="inline-'.$staff_ID.'" class="excerpt content-padding lity-hide">';
					echo '<h3>'.$staff_first_name.' '.$staff_last_name .'</h3>';
					echo '<p>'.$staff_bio.'</p>';
				echo '</div>';
			endif;
		 ?>
	</div>
<?php if (!empty($staff_bio)): ?>
</a>
<?php endif; ?>