<?php
/**
 * Template Name: Grantees
 */
?>
<?php
//select grantees
//fill these with post data from the select dropdowns
global $post;

$cat='';
$meta_array=array();
$selected_year='';
$selected_boro='';

if (!empty($_POST)):
	if (isset($_POST["sector"]) && $_POST["sector"]!="0") {
		$cat=$_POST["sector"];
	}
	if (isset($_POST["borough"]) && $_POST["borough"]!="Borough") {
		$borough_data =  array (
			'key' => 'borough',
			'value' => $_POST["borough"]
      	);
      	$selected_boro = $_POST["borough"];
      	//push into the meta_array
      	array_push($meta_array,$borough_data);
	}
	if (isset($_POST["grantee-year"]) && $_POST["grantee-year"]!="Year") {
		$year_data =  array (
			'key' => 'year',
			'value' => $_POST["grantee-year"]
      	);
      	$selected_year = $_POST["grantee-year"];
      	//push into the meta_array
      	array_push($meta_array,$year_data);
	}
endif;
?>

<div class="row page-leader">
	<div class="col-sm-4">
		<h1><?php echo get_the_title(); ?></h1>
	</div>
	<div class="col-sm-8 grantee-filters">
		<form id="grantee-search" name="grantee-search" class="grantee-search" method="post" action="<?php echo get_page_link(); ?>">
			<labeL>Filter by:</labeL> &nbsp;
       				<select name="grantee-year">
                    	<?php
                    		// must add field key of the field you want
        					$args = array(
								'posts_per_page'   => -1,
								'offset'           => 0,
								'category'         => '',
								'category_name'    => '',
								'orderby'          => '',
								'order'            => 'DESC',
								'post_type'        => 'post',
								'post_mime_type'   => '',
								'post_parent'      => '',
								'author'	   	   => '',
								'post_status'      => 'publish',
								'suppress_filters' => true 
							);
        					$myposts = get_posts( $args );
        					$year_array = array();
        					$total_grants = ''; //array();
        					$year_total = 0;
        					//function to check if value is in our multidimensional array
        					function in_array_r($needle, $haystack, $strict = false) {
    							foreach ($haystack as $item) {
        							if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            							return true;
            						}
            					}
            					return false;
            				}
            				
							$curr_year = 0;
        					foreach ( $myposts as $post ) : setup_postdata( $post );
        						$my_year = get_field('year');
        						//since we're doing this already, calculate total grant amounts given in each year and push into array for use later on this page
        						if ($curr_year!=$my_year) {
        							 $year_total=0;
        							 $curr_year = $my_year;
        						}
        						$year_total = $year_total +intval(str_replace(str_split('$,'),'',get_field('grant_amount')));
        						
        						//echo $year_total;
        						
        						if (in_array_r($my_year, $year_array)) {
        							//found
        							//just update the grants total
        							foreach($year_array as &$value){
    									if($value['year'] === $my_year){
        									$value['grants_total'] = $year_total;
        									// Stop the loop after we've found the year
        									break;
    									}
									}
        							
        						} else {
        							$this_year = array(
        								'year' => get_field('year'),
										'grants_total' => $year_total
									);
        							array_push($year_array,$this_year );
        						
        						}
        						
        						
        						//echo $year_total;
        					endforeach; 
        					
        					wp_reset_postdata();
        					
        					//print_r($year_array);
        					
        					//loop through array of available years and print options
        					//sort it by year
        					
        					/*foreach ($year_array as $key => $row) {
    							// replace 0 with the field's index/key
    							$dates[$key]  = $row['year'];
    						}
    						array_multisort($dates, SORT_DESC, $year_array);*/
    						//print_r($year_array);
    						
    						
    						
        					//rsort($year_array, SORT_NUMERIC);
        					echo '<option value="Year">'.__('Year','sage').'</option>';
        					foreach ($year_array as &$value) {
        						echo '<option value="'.$value['year'].'"'.(($value['year']==$selected_year)?'selected="selected"':"").'>'.$value['year'].'</option>';
        					}
						?>
						
                        
                    </select> 
                    &nbsp;
                    <select name="borough">
                    	<option value="Borough">Borough</option>
                    	<?php
                    	$boro_array = array('Brooklyn','Manhattan','Queens','Staten Island','The Bronx');
                    	foreach ($boro_array as &$value) {
        					echo '<option value="'.$value.'"'.(($value==$selected_boro)?'selected="selected"':"").'>'.$value.'</option>';
        					}
        				?>
                    </select>
                    &nbsp;
                    
                    	<?php wp_dropdown_categories( array(
        						'show_option_all'    => __('Sector','sage'),
								'show_option_none'   => '',
								'option_none_value'  => 'Sector',
								'orderby'            => 'name', 
								'order'              => 'ASC',
								'show_count'         => 0,
								'hide_empty'         => 1, 
								'selected'           => $cat,
								'name'               => 'sector',
								'id'                 => 'sector',
								'class'              => 'postform',
								'depth'              => 0,
								'tab_index'          => 0,
								'taxonomy'           => 'category',
								'hide_if_empty'      => false,
								'value_field'	     => 'term_id',
    							) ); ?>
    				&nbsp;
    				<a href="javascript:void(0);" onclick="document.getElementById('grantee-search').submit(); return false;">Go!</a>

		</form>
	</div>
</div>

<?php
$args = array(
	'posts_per_page'   => -1,
	'offset'           => 0,
	'category'         => $cat,
	'category_name'    => '',
	'orderby'          => 'meta_value_num',
	'order'            => 'DESC',
	'include'          => '',
	'exclude'          => '',
	'meta_query'       => $meta_array,
	'post_type'        => 'post',
	'post_mime_type'   => '',
	'post_parent'      => '',
	'author'	   	   => '',
	'post_status'      => 'publish',
	'suppress_filters' => true 
);

$myposts = get_posts( $args );
$counter=0;
$curr_year=0;
$reset_row=false;

if (!empty($myposts)) :
	foreach ( $myposts as $post ) : setup_postdata( $post );
		//print the year divider
		//only if meta array isn't empty and the form has not yet been submitted
		if ($curr_year!=get_field('year') && (!empty($meta_array) || empty($_POST))) {
			//close the item row only if it hasn't been already
			if ($counter>0) {
				echo '</div>';
				//reset counter
				$counter=0;
			}
			$curr_year = get_field('year');
			//find the grants total for this year
        	foreach($year_array as &$value){
    			if($value['year'] === $curr_year){
    				setlocale(LC_MONETARY, 'en_US');
					$total_grants = money_format('%.0n', floor($value['grants_total']));
        			//$total_grants = $value['grants_total'];
        			// Stop the loop after we've found the year
        			break;
    			}
			}
			/*print_r($borough_data);
			if (count($borough_data)==0 && empty($cat)) {
				$rowheader = $curr_year.' / '.$total_grants;
			} else {
				$rowheader = $curr_year;
			}*/
			
			echo '<div class="row divider">
					<div class="col-xs-12">
						<h3 class="fancy"><span>'.$curr_year.' / '.$total_grants.'</span></h3>
					</div>
				</div>';	
		}
		
		
		if ($counter==0) :
			echo '<div class="row grantee-row">';
		endif;
				echo '<div class="col-sm-6 col-md-3 grantee-item">';
					//load the grantee template
					include( locate_template( 'templates/content-grantee-item.php' ) );				
				echo '</div>';
		if ($counter<4) :
			$counter++;
			//do math on the total grants for this year
			$total_grants = $total_grants + get_field('grant_amount');
		endif;
		if ($counter==4) :
			$counter=0;
			$reset_row=false;
			echo '</div>';		
		endif;
	endforeach; 
	wp_reset_postdata();
else:
	echo '<div class="no-results">';
	echo '<h3>'.__('Oh no! We couldn’t find anything!','sage').'</h3>';
	echo '<p>'.__('Try different filters?','sage').'</p>';
	echo '<i class="fa fa-frown-o" aria-hidden="true"></i>';
	echo '</div>';
endif;

?>

