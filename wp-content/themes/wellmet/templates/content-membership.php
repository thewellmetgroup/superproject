<?php
// Loop through rows.
while( have_rows('membership_benefits') ) : the_row();
	$membership_impact_area = get_sub_field('membership_impact_area');
	$impact_area_description = get_sub_field('impact_area_description');
	$membership_impact_icon = get_sub_field('membership_impact_icon');

	echo '<div class="col-md-6 memership-impact-area">';
		echo '<div class="item-wrapper content-padding">';
			if($membership_impact_icon) {
				echo '<img src="'.$membership_impact_icon['url'].'" alt="'.$membership_impact_icon['alt'].'">';
			}
			echo '<h2>'.$membership_impact_area.'</h2>';
		    echo '<p>'.$impact_area_description.'</p>';
		echo '</div>';
	echo '</div>';
endwhile;

?>