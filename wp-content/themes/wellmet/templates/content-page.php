<div class="page">
	<div class="row">
		<div class="col-md-2">
		</div>
		<div class="col-md-8">

			<?php
			if( have_rows('quotes') ): 
				echo '<div id="quotes" class="quotes">';
				// Loop through rows.
			    while( have_rows('quotes') ) : the_row();
			        // Load sub field value.
			        $quote = get_sub_field('quote');
			        echo '<blockquote>';
						echo $quote;
						echo '”';
					echo '</blockquote>';
			    // End loop.
			    endwhile;
			    echo '</div>';
			endif;
			?>	
					
			<?php the_content(); ?>

			<?php 

			//Grantee Resources
			if( have_rows('resource_section') ):
				include( locate_template( 'templates/content-resources.php' ) );
			endif; ?>

			<?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
		</div>
		<div class="col-md-2">
		</div>
	</div>
	<?php
	//Member Impact Areas
		if( have_rows('membership_benefits') ):
			echo '<div class="row membership-areas">';
				include( locate_template( 'templates/content-membership.php' ) );
			echo '</div>';
		endif;
		//END
	?>
</div>
