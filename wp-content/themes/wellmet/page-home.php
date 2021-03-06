<?php
/**
 * Template Name: Home
 */
?>
<?php 
//select featured grantees
$posts = get_posts(array(
	'post_type'			=> 'post',
	'meta_key'			=> 'featured',
	'meta_value'		=> 'a:1:{i:0;s:8:"Featured";}'
));
//randomize the array
shuffle( $posts );
if( $posts ):
	foreach( $posts as $post ): 
		//load the grantee template
		include( locate_template( 'templates/content-grantee.php' ) );
		break;
	endforeach;
	wp_reset_postdata();

endif; ?>
<div class="cr_callouts">
    <div class="row">
    	   
            <div class="col-sm-4 cr_cta_sec_item">
                <div class="cr_cta_sec_item_inner">
                    <i class="icon icon_handshake"></i>
                    <h2 class="headline"><?php _e('Who are our <br>grantees?','sage'); ?></h2>
                    <a href="/grantees/" class="cr_btn callout"><?php _e('Learn more','sage'); ?></a>
                </div>
            </div>
            
    
            <div class="col-sm-4 cr_cta_sec_item cr_cta_sec_item_2">
                <div class="cr_cta_sec_item_inner">
                    <i class="icon icon_stats-lines"></i>
                    <h2 class="headline"><?php _e('WellMet’s<br>impact','sage'); ?></h2>
                    <a href="/empowering-communities/" class="cr_btn callout"><?php _e('Learn more','sage'); ?></a>
                </div>
            </div>
            
    
            <div class="col-sm-4 cr_cta_sec_item cr_cta_sec_item_3">
                <div class="cr_cta_sec_item_inner">
                    <i class="icon icon_team-women"></i>
                    <h2 class="headline"><?php _e('About WellMet Philanthropy','sage'); ?></h2>
                    <a href="/membership/" class="cr_btn callout"><?php _e('Learn more','sage'); ?></a>
                </div>
            </div>
    
        </div>
</div>
            
