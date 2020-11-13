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
			//Member Impact Areas
			if( have_rows('membership_benefits') ):
				echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
					$tab_num = 0;
					// Loop through rows.
				    while( have_rows('membership_benefits') ) : the_row();
						$membership_impact_area = get_sub_field('membership_impact_area');
						$impact_area_description = get_sub_field('impact_area_description');
						echo '<div class="panel panel-default">';
							echo '<div class="panel-heading" role="tab" id="heading-'.$tab_num.'">';
								echo '<div class="panel-title">';
									echo '<a class="accordion-trigger" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-'.$tab_num.'" aria-expanded="true" aria-controls="collapse-'.$tab_num.'">';
										echo '<h3><strong>'.$membership_impact_area.'</strong></h3>';
						    		echo '</a>';
								echo '</div>'; 
							echo '</div>';
							echo '<div id="collapse-'.$tab_num.'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-'.$tab_num.'">';
								echo '<div class="panel-body">';
									echo '<p>'.$impact_area_description.'</p>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
						$tab_num = $tab_num+1;
				    endwhile;
				    
				echo '</div>';
			endif;
			//END

			//Grantee Resources
			if( have_rows('resource_section') ):

				echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';

						  
				$tab_num = 0;
				// Loop through rows.
			    while( have_rows('resource_section') ) : the_row();
			    	
			        // Load sub field value.
			        $resource_section_header = get_sub_field('resource_section_header');
			        $resource_section_description_bool = get_sub_field('resource_section_description_bool');
			        $resource_section_description = get_sub_field('resource_section_description');

			        echo '<div class="panel panel-default">';
			        	echo '<div class="panel-heading" role="tab" id="heading-'.$tab_num.'">';

					        echo '<div class="panel-title">';
					        	echo '<a class="accordion-trigger" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-'.$tab_num.'" aria-expanded="true" aria-controls="collapse-'.$tab_num.'">';
					        		echo '<h3><strong>'.$resource_section_header.'</strong></h3>';
					        	echo '</a>';
					        	if($resource_section_description_bool) {
						        	//echo '<h4>';
							        echo $resource_section_description;
							        //echo '</h4>';
						        }
					        echo '</div>';
					    echo '</div>';
					        

				        echo '<div id="collapse-'.$tab_num.'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-'.$tab_num.'">';
				        	echo '<div class="panel-body">';
				        		echo '<ul>';

						        // Loop over sub repeater rows.
						        if( have_rows('resources') ):
						            while( have_rows('resources') ) : the_row();

						                // Get sub value.
						                $resource_link = get_sub_field('resource_link');
						                $resource_item_description = get_sub_field('resource_item_description');
						                if($resource_link) {    	
						                	$link_target = $resource_link['target'] ? $resource_link['target'] : '_self';
						                }

						                if($resource_link && $resource_item_description) {
							                echo '<li>';
								                echo '<strong>';
								                	echo '<a href="'.$resource_link['url'].'" target="'.$link_target.'">';
								                		echo $resource_link['title'];
								                	echo '</a>';
									            echo '</strong>';
										        echo '<p>'.$resource_item_description.'</p>';
									        echo '</li>';
									    }
								        if(!$resource_link && $resource_item_description) {
								            echo '<p>'.$resource_item_description.'</p>';
								        }

						            endwhile;
						        endif;
						        echo '</ul>';

						    echo '</div>';
						echo '</div>';

				    echo '</div>';
				    $tab_num = $tab_num+1;
			    // End loop.
			    endwhile;

			    echo '</div>';

			endif; ?>

			<?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
		</div>
		<div class="col-md-2">
		</div>
	</div>
</div>
