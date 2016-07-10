<?php 	

		$rand=rand(1,4);
		$grantee_images= get_field('images');
		$grantee_mission=get_field('featured_mission');
		$grantee_description=get_field('description');
		$quote=get_field('quote');
		$website_url=get_field('website_url');
		$grant_amount=get_field('grant_amount');
		$borough=get_field('borough');
		$year=get_field('year');
		
		
		
		$color = new Random_color();
		$picked_color = $color->get_color($rand);

		
		//if there are images for this grantee
		if (!empty($grantee_images)) {
			//if more than 1
			if (count($grantee_images)>1) {
				shuffle( $grantee_images );
				$i=0;
	    		//print_r($grantee_images);
	    		foreach( $grantee_images as $image ): 
					if ($i==0) {
						$full_img_url = $image['sizes']['large'];
						$full_img_alt = $image['alt'];
					}
					if ($i==1) {
						$halfy_img_url = $image['sizes']['large'];
						$halfy_img_alt = $image['alt'];
						break;
					}
					$i++;
	  			endforeach;
	  		} else {
	  			//if just one
	  			$full_img_url = $grantee_images[0]['sizes']['large'];
				$full_img_alt = $grantee_images[0]['alt'];
	  		}
	  	}
	  	
        
?>

<!--<div class="grantee-detail">
	<div class="row ">
		<div class="col-xs-12">
			<h2 class="entry-title"><?php the_title(); ?></h2>
		</div>
	</div>
</div>-->

<div class="grantee-detail">
    <div class="row gutter-15">
        <?php
        //do this switcharoo if there are images and there's more than 1
        if ( count($grantee_images)>1 ) { ?>
        <div class="col-sm-6">
        	<?php 
        	switch ($rand) {
    			case "1":
        			echo fuller($picked_color,$full_img_url);
        			break;
    			case "2":
        			echo halfy('top','image',$picked_color,$halfy_img_url);
        			echo halfy('bottom','color',$picked_color,$halfy_img_url);
        			break;
    			case "3":
        			echo halfy('top','color',$picked_color,$halfy_img_url);
        			echo halfy('bottom','image',$picked_color,$halfy_img_url);
        			break;
        		case "4":
        			echo fuller($picked_color,$full_img_url);
        			break;
			} ?> 	
        </div>
        <div class="col-sm-6 right">
			<?php 
        	switch ($rand) {
    			case "1":
        			echo halfy('top','image',$picked_color,$halfy_img_url);
        			echo halfy('bottom','color',$picked_color,$halfy_img_url);
        			break;
    			case "2":
        			echo fuller($picked_color,$full_img_url);
        			break;
    			case "3":
        			echo fuller($picked_color,$full_img_url); 
        			break;
        		case "4":
        			echo halfy('top','color',$picked_color,$halfy_img_url);
        			echo halfy('bottom','image',$picked_color,$halfy_img_url);
        			break;
			} ?>
        </div>
        <?php } else { ?>
        	<div class="col-xs-12">
        		<?php 
        			if (count($grantee_images)==1) {
        				echo fuller($picked_color,$full_img_url);
        			}
        			echo halfy('bottom','color',$picked_color,''); 
        		?>
        		
        	</div>
        
        <?php }  ?>
    </div>
</div>

<?php 
//only details if not homepage
if ( !is_front_page() ) { ?>
<div class="grantee-detail">
    <div class="row">
    	<div class="col-md-2">
		</div>
		<div class="col-md-8">
			<article <?php post_class(); ?>>
 			 
    			<?php if (!empty($quote)) { ?>
    				<div class="quote">
    					<?php echo $quote; ?>&#8221;
    				</div>
    			<?php } ?>
    			
    			<div class="main-desc">
    				<div class="row stats">
    					<div class="col-xs-4">
    						<i class="fa fa-calendar-o" aria-hidden="true"></i>
    						<br>
    						<?php echo $year; ?>
    					</div>
    					<div class="col-xs-4">
    						<i class="fa fa-usd" aria-hidden="true"></i>
							<br>
    						<?php echo $grant_amount; ?>
    					</div>
    					<div class="col-xs-4">
    						<i class="fa fa-map-marker" aria-hidden="true"></i>
    						<br>
    						<?php echo $borough; ?>
    					</div>
    				</div>
    				<?php echo $grantee_description; ?>
    			</div>
    			
    			<?php if (!empty($website_url)) { ?>
    				<div class="website_url">
    					<h3><?php _e('Learn more about this organization:','sage'); ?></h3>
    					<a href="<?php echo $website_url; ?>" target="_blank" title="<?php echo get_the_title(); ?>"><?php echo $website_url; ?></a>
    				</div>
    			<?php } ?>
    			
    			
    			
			</article>
		</div>
		<div class="col-md-2">
		</div>
	</div>
</div>
<?php } ?>