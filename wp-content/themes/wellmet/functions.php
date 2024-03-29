<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php', // Theme customizer
  'lib/wp_bootstrap_navwalker.php' // Bootstrap nav walker
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);


//Modify POSTS pages for Grantees
function wellmet_change_post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Grantees';
    $submenu['edit.php'][5][0] = 'Grantees';
    $submenu['edit.php'][10][0] = 'Add Grantee';
    $submenu['edit.php'][16][0] = 'Grantee Tags';
    echo '';
}
function wellmet_change_post_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Grantees';
    $labels->singular_name = 'Grantees';
    $labels->add_new = 'Add Grantee';
    $labels->add_new_item = 'Add Grantee';
    $labels->edit_item = 'Edit Grantee';
    $labels->new_item = 'Grantees';
    $labels->view_item = 'View Grantees';
    $labels->search_items = 'Search Grantees';
    $labels->not_found = 'No Grantees found';
    $labels->not_found_in_trash = 'No Grantees found in Trash';
    $labels->all_items = 'All Grantees';
    $labels->menu_name = 'Grantees';
    $labels->name_admin_bar = 'Grantees';
}
 
add_action( 'admin_menu', 'wellmet_change_post_label' );
add_action( 'init', 'wellmet_change_post_object' );


//add custom columns to the grantee listing
add_filter('manage_posts_columns', 'my_columns', 'post');
function my_columns($columns) {
	$post_type = get_post_type();
	if ( $post_type == 'post' ) {
		//remove these from view
		unset($columns['author']);
	    unset($columns['tags']);
	    unset($columns['comments']);
	    
		//re-order the others
		$date = $columns['date'];
		unset($columns['date']);
		$categories = $columns['categories'];
		unset($columns['categories']);
	    $columns['year'] = __('Year','sage');
	    $columns['grant_amount'] = __('Grant Amount','sage');
	    $columns['borough'] = __('Borough','sage');
	    $columns['categories'] = $categories;
	    $columns['date'] = $date;
	 
	    return $columns;
	} else {
		$columns['author'] = __('Author','sage');
		return $columns;
	}
}

add_action('manage_posts_custom_column',  'my_show_columns', 'post');
function my_show_columns($name) {
    global $post;
    switch ($name) {
        case 'year':
            $year = get_post_meta($post->ID, 'year', true);
            echo $year;
            break;
        
        case 'borough':
            $borough = get_post_meta($post->ID, 'borough', true);
            echo $borough;
            break;
        case 'grant_amount':
            $grant_amount = get_post_meta($post->ID, 'grant_amount', true);
            echo $grant_amount;
            break;
    }
   
}

//******************************************************************//
// REGISTER CUSTOM POST TYPES ////////////////////////////////////////
function wellmet_create_post_types()
{
    // Register events
    $news_labels = array(
        'name' => 'News',
        'singular_name' => 'News',
        'add_new' => 'Add News',
        'add_new_item' => 'Add News Item',
        'edit_item' => 'Edit News',
        'new_item' => 'New',
        'all_items' => 'All News',
        'view_item' => 'View News',
        'search_items' => 'Search News',
        'not_found' =>  'No News Found',
        'not_found_in_trash' => 'No News Found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'News',
    );
    register_post_type('news', array(
            'labels' => $news_labels,
            'menu_icon' => 'dashicons-megaphone',
            'has_archive' => true,
            'public' => true,
            'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail','page-attributes' ),
            'exclude_from_search' => false,
            'hierarchical' => true,
            'capability_type' => 'post',
            //'rewrite' => array( 'slug' => 'news-and-updates' ),
            'menu_position' => 20,
            'show_in_rest' => true,
        ));
    
}
add_action('init', 'wellmet_create_post_types');
// END CUSTOM POST TYPES ////////////////////////////////////////


function current_template() {
	$pageTemplate = get_page_template();
	$pageArray = explode("/", $pageTemplate);
	$pageTemplate = end($pageArray);
	return $pageTemplate;	
}

function trunc($phrase, $max_words) {
   	$phrase_array = explode(' ',$phrase);
	if(count($phrase_array) > $max_words && $max_words > 0)
     	$phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';
   	return $phrase;
}
//create div arrays so we can randomize block positions
function fuller($picked_color,$full_img_url,$full_img_alt) {	 
	$html = '<div class="full bgstyle" style="background-image:url(\''.$full_img_url.'\')">';
	$html .= '<div class="title">';
	if ( is_front_page() ) {
	  $html .= '<span class="header" style="color:'.$picked_color.';">Recent Grantee</span><br>';
	}
	$html .= '<span class="name">'.get_the_title().'</span>';
	$html .= '<span class="image-description">'.$full_img_alt.'</span>';
	$html .= '</div>';
	$html .= '</div>';
	  					 
    return $html;
}
function halfy($pos,$bg_type,$picked_color,$halfy_img_url,$halfy_img_alt) {
    $get_grantee_mission = new Grantee_Mission();
	$grantee_mission = $get_grantee_mission->get_mission();
    if ($bg_type=='image') {
    	$css_value = 'background-image:url(\''.$halfy_img_url.'\')';
    	$halfy_content='<span class="image-description">'.$halfy_img_alt.'</span>';
    } else {
    	$css_value = 'background-color:'.$picked_color.''; //must be the color
		$halfy_content=$grantee_mission;
		$pageTemplate = current_template();
		if ( is_front_page() || $pageTemplate == 'page-empowering-communities.php' ) {
			$halfy_content.='<p><a href="'.get_the_permalink().'" class="cr_btn">'.__('See more','sage').'</a></p>';
		}
	}
	return 
	'<div class="halfy '.$pos.' bgstyle" style="'.$css_value.'"><div class="halfy-content">'.$halfy_content.'</div></div>';
}
class Random_color {
			
	public function get_color($rand) {
		//#79b118; //green
		//#00c3df; //blue
		//#ca195c; //purple
		//#f4c021; //orange
		$colors = array('#79b118','#00c3df','#ca195c','#f4c021');
		$picked_color=$colors[$rand-1];
		return $picked_color;
	}
}
		
class Grantee_Mission {
	
	public function get_mission() {
		$grantee_mission=get_field('featured_mission');
		$grantee_description=get_field('description');
		//if the grantee mission if empty
		if (empty($grantee_mission)) {
			//set description excerpt as mission
			$grantee_mission = trunc($grantee_description,10);
		}
		return $grantee_mission;
	}
}

//function to replace depricated money_format in 7.4
$fmt = numfmt_create( 'en-US', NumberFormatter::CURRENCY );
$symbol = $fmt->getSymbol(NumberFormatter::INTL_CURRENCY_SYMBOL);

function money_format($ignore,$value) {
        global $fmt,$symbol;

        return $fmt->formatCurrency($value,$symbol);
}
/**** END grantees *************/


//add gallery shortcode
function show_gallery( $atts ){
	$html = '<div class="grantee-detail">';
		$html .= '<div class="row gutter-15">';
			$html .= '<div class="col-sm-6">';
				$html .= '<div class="full bgstyle" style="background-image:url(\''.$atts['full_img_url'].'\')">';
					$html .= '<div class="title" style="text-transform:none">';
						$html .= $atts['caption'];
					$html .= '</div>';
				$html .= '</div>';
    		$html .= '</div>';
    		$html .= '<div class="col-sm-6 right">';
    			$css_value = 'background-image:url(\''.$atts['halfy_img_url'].'\')';
    			$html .= '<div class="halfy top bgstyle" style="'.$css_value.'">';
    			$html .= '</div>';
    			
    			$css_value = 'background-color:'.$atts['picked_color'];
    			$html .= '<div class="halfy bottom bgstyle" style="'.$css_value.'">';
    				$html .= '<div class="halfy-content">'.$atts['quote'].'</div>';
    			$html .= '</div>';
    			
   			 $html .= '</div>';
    	$html .= '</div>';
	$html .= '</div>';
	
	return $html;
}
add_shortcode( 'wellmet_gallery', 'show_gallery' );


//add Login shortcode
/*function show_login( $atts ){
	if (!is_user_logged_in()) {
		if (isset($_GET['redirect'])) {
			$whereto = $_GET['redirect'];
		} else {
			$whereto = home_url();
		}
		$args = array(
			'redirect'       => $whereto,
			'form_id'        => 'wellmet-loginform',
			'echo'           => true
		);
		$html = wp_login_form($args);
		$html .= '<p><a href="'.wp_lostpassword_url().'">'. __('Lost your password?','sage'). '</a><br>';
	} else { 
		$html = '<h3>'.__('What would you like to do?','sage').'</h3>';
		if ( current_user_can( 'manage_options' ) ) {
			$html .= '<a href="'.admin_url().'">'.__('Go to admin dashboard','sage').' &#8594;</a><br>';
		}
		$html .= '<a href="'.admin_url('profile.php').'">'.__('Update my profile','sage').' &#8594;</a><br>';
		$html .= '<a href="/member-resources">'.__('View member resources','sage').' &#8594;</a><br>';
		//$html .= '<a href="'.admin_url('users.php').'">'.__('View member directory','sage').' &#8594;</a><br>';
		//$html .= '<a href="/nominations/">'.__('Submit a nomination form','sage').' &#8594;</a><br>';
		//$html .= '<a href="'.admin_url('admin.php?page=formidable-entries').'">'.__('Review nominations','sage').' &#8594;</a><br><br>';
		$html .= '<a href="'.wp_logout_url().'">'.__('Log out','sage').' &#8594;</a><br>';
	}
	
	return $html;
}
add_shortcode( 'login', 'show_login' );
*/


//customize login page
function wellmet_login_logo() { ?>
    <style type="text/css">
        body.login div#login {
        	padding:4% 0 0;
        	width:90%;
        	max-width:500px;
        }
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/dist/images/wellmet-logo.png) !important;
            padding-bottom: 30px !important;
            background-size:400px !important;
            width:400px !important;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'wellmet_login_logo' );

function wellmet_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'wellmet_login_logo_url' );

function wellmet_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertitle', 'wellmet_login_logo_url_title' );



/********************************************
/* Adding open graph sharing meta tags *****/
add_action('wp_head','hook_meta');
function hook_meta() {
	global $post;
	$featured_img_url = get_stylesheet_directory_uri().'/dist/images/facebook.png';
	$output='<meta property="og:type" content="website">';
	$output.='<meta property="og:site_name" content="'.get_bloginfo("name").'">';
	//if single grantee, use the content of the grantee
	if (is_single()) {
		//check if featured image is set
		$featured = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "full" );
		if( $featured ) {
			$featured_img_url = $featured[0];
		} 
		$output.='<meta property="og:title" content="'.get_the_title($post->ID).'">';
		$output.='<meta property="og:url" content="'.get_post_permalink($post->ID).'">';
		$output.='<meta property="og:image" content="'.$featured_img_url.'">';
		$output.='<meta property="og:description" content="'.substr(strip_tags(get_field('description')),0,157).'...">';
	} else {
	//otherwise show general blog description and image
		$output.='<meta property="og:title" content="'.get_bloginfo("name").'">';
		$output.='<meta property="og:url" content="'.get_bloginfo("url").'">';
		$output.='<meta property="og:image" content="'.$featured_img_url.'">';
		$output.='<meta property="og:description" content="'.get_bloginfo("description").'">';
	}
	
	echo $output;
}

/* END meta tags ****************************/

/*hide generator for security */
remove_action('wp_head', 'wp_generator'); 

