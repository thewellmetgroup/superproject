<div class="page">
<div class="row">
		<div class="col-md-12">
			<?php 
			$twitter_handle = get_field('twitter_handle');
			$twitter_header = get_field('twitter_header');
			$instagram_handle = get_field('instagram_handle');
			$instagram_header = get_field('instagram_header');

			$newsletter_archives_header = get_field('newsletter_archives_header');
			$newsletter_links = get_field('newsletter_links');
			/*
			if($twitter_handle) {
				if($twitter_header) {
					echo '<h3 style="margin-top:0;">'.$twitter_header.'</h3>';
				}
				//echo '<strong>'.$twitter_header.'</strong><br>';
				echo '<p><a href="https://twitter.com/'.$twitter_handle.'?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-size="large" data-dnt="true" data-show-count="false">Follow @'.$twitter_handle.'</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></p>';

				include( locate_template( 'templates/twitter-feed.php' ) );
			}
			if($instagram_handle) {
				if($instagram_header) {
					echo '<h3>'.$instagram_header.'</h3>';
				}
				include( locate_template( 'templates/instagram-feed.php' ) );
			}*/

			if( have_rows('newsletter_links') ) {
				if($newsletter_archives_header) {
					echo '<h3>'.$newsletter_archives_header.'</h3>';
				}
				echo '<ul>';
				// Loop through rows.
			    while( have_rows('newsletter_links') ) : the_row();
			        // Load sub field value.
			        $newsletter_link = get_sub_field('newsletter_link');
			        $link_url = $newsletter_link['url'];
				    $link_title = $newsletter_link['title'];
				    $link_target = $newsletter_link['target'] ? $newsletter_link['target'] : '_self';    
				    echo '<li>';
				    	echo '<a href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'">'.esc_html( $link_title ).'</a>';
				    echo '</li>';

			    // End loop.
			    endwhile;
				
			    echo '</ul>';
			}


			?>
			
		</div>
	</div>

	<div class="row">
		
		<div class="col-md-12">
			<?php the_content(); ?>

			<!-- News listing -->
			
				<?php
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$args = array(
					'posts_per_page'   => 6,
					'post_type'        => 'news',
					'post_status'      => 'publish',
					'paged' 		   => $paged
				);

				$postslist = new WP_Query( $args );
				$total_pages = $postslist->max_num_pages;
				if ( $postslist->have_posts() ) :
					echo '<div class="news-listing row">';
        			while ( $postslist->have_posts() ) : $postslist->the_post(); 
						//load the news item template
						include( locate_template( 'templates/content-news-item.php' ) );	
					endwhile;
					echo '</div>';
					echo '<div class="pagination">';
						pagination_bar( $postslist );
					echo '</div>';
					wp_reset_postdata();

				endif;

				function pagination_bar( $custom_query ) {

				    $total_pages = $custom_query->max_num_pages;
				    $big = 999999999; // need an unlikely integer

				    if ($total_pages > 1){
				        $current_page = max(1, get_query_var('paged'));

				        echo paginate_links(array(
				            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				            'format' => '?paged=%#%',
				            'current' => $current_page,
				            'total' => $total_pages,
				            'prev_text' => false,
				            'next_text' => false,
				        ));
				    }
				}

				?>


			</div>

			<!-- //END -->
			
			<?php //wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
		</div>
	</div>
</div>
