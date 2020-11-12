<div class="news-item col-md-6">
	<div class="item-wrapper">
		<div class="thumb">
			<?php
			// Check if the post has a Post Thumbnail assigned to it.
			if ( has_post_thumbnail() ) {
				if (has_excerpt()): 
	    			echo '<a href="'. esc_url( get_permalink() ) .'">';
	    		endif;
	    		the_post_thumbnail('medium');
	    		if (has_excerpt()):
	    			echo '</a>';
	    		endif;
			} 
			?>
		</div>
		<div class="title content-padding">
			<h3>
				<?php if (has_excerpt()): ?>
					<a href="<?php echo esc_url( get_permalink() ); ?>">
				<?php endif; ?>
					<?php echo get_the_title(); ?>
				<?php if (has_excerpt()): ?>	
					</a>
				<?php endif; ?>
			</h3>
		</div>
		<div class="excerpt content-padding" style="padding-top: 0;">
			<?php 	if (has_excerpt()): 
						the_excerpt();
						echo '<a href="<?php echo esc_url( get_permalink() ); ?>">'. __( 'Read more', 'sage' ).'</a>';

					else:
						echo trunc(get_the_content(), '100');
					endif;
			 ?>
		</div>
		
	</div>
</div>