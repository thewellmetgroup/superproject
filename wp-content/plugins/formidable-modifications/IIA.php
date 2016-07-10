<?php
/*
Plugin Name: Formidable Modifications
Plugin URI: http://cloudred.com
Description: Plugin modifies/enhances default formidable plugin functionality, developed for IIA. (1) Organizes file uploads by creating directories named after user names. (2) Creates better UX and presentation layer. (3) Creates word counters for fields where word count needs to be enforced (To enable: add field description with instructions, then add two classes to fields where you want word count enforced: "enforcecount" "20" where 20 is the number you wish to enforce, separate these class name values with a space, omit quotes).
Version: 1.0
Author: cloudred
Author URI: http://cloudred.com
*/

/* ------------------------- Actions --------------------------- */

/**
 * Hooks into the `wp_head` action.
 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
*/
add_action('wp_head', 'add_to_header');
function add_to_header(){ ?>
	<link rel='stylesheet' href='<?php echo plugins_url( 'formidable-modifications/styles.css', dirname(__FILE__)); ?>' type='text/css' media='all' />
    <script type="text/javascript" src="<?php echo plugins_url( 'formidable-modifications/waypoints/lib/jquery.waypoints.min.js', dirname(__FILE__)); ?>"></script>
	<script type="text/javascript" src="<?php echo plugins_url( 'formidable-modifications/functions.js', dirname(__FILE__)); ?>"></script>
   
<?php }

//modify the default direcotry where files are uploaded from application form
add_filter('frm_upload_folder', 'frm_custom_upload');
function frm_custom_upload($folder){
    global $current_user;    
    $folder = 'applications/'.date("Y").'/'.sanitize_title_with_dashes($current_user->user_lastname)."-".sanitize_title_with_dashes($current_user->user_firstname)."-".$current_user->ID;
    return $folder;
}

add_action('frm_after_create_entry', 'generate_application_pdf', 30, 2);
add_action('frm_after_update_entry', 'generate_application_pdf', 10, 2);
function generate_application_pdf($entry_id, $form_id){
   if(!empty($form_id) && !empty($entry_id) && empty($_POST['frm_saving_draft']) ){
		/*Get entry and form information*/
		$entry = FrmEntry::getOne($entry_id, true);
		$fields = FrmField::get_all_for_form( $entry->form_id, '', 'include' );
		/*Create the html markup*/
		if( !empty($entry)  && !empty($entry->form_name)  &&!empty($fields)){
			$user_info = get_userdata($entry->user_id);
			$html ='<html><body style="font-family:Helvetica, Arial, sans-serif; font-size:12px">';
			$html .='<div><img src="'.get_stylesheet_directory_uri().'/images/logo.png"></div>';
			$html .='<h1>Application Form #'.$user_info->ID.'</h1>';
			$html .='<table class="form-table" cellpadding="2" cellspacing="2"><tbody>';
			$i=0;
			$highlight_form_id = get_option( 'formidable_my_highlight_form_id', '' );
			$highlight_options =  explode(",",get_option( 'formidable_my_highlight_options', '' ));
			$highlight_text_color = get_option( 'formidable_my_highlight_text_color' );
			$highlight_bg_color =  get_option( 'formidable_my_highlight_row_color' );

			foreach ( $fields as $field ) {
				if ( in_array( $field->type, array( 'captcha', 'end_divider', 'form', 'break', 'html' ) ) ) { //divider is section title
					continue;
				}
				
			
				if ($field->type == 'divider') {
					$rowColor = "#d4d4d4";
					$textColor = "#000000";
					$html .= '<tr style="background-color:'.$rowColor.'; color:'.$textColor.';"><th scope="row" colspan="2" valign="top" style="padding:0 5px; text-align:left;"><h3>'.esc_html( $field->name ).'</h3></th></tr>';
				} else {
					$textColor = "#000000";
					if ($i % 2 == 0){
						$rowColor = "#F2F2F2";
					} else {
						$rowColor = "#FFFFFF";
					}
					
					if ( in_array( $field->field_key, $highlight_options ) ) {
						//if row highlight color is set, use that, otherwis, user default
						if ($highlight_bg_color !='' && $highlight_bg_color!='inherit') {
							$rowColor = $highlight_bg_color;
						} else if ($highlight_bg_color!='inherit'){
							$rowColor = '#fffc00';
						}
						//if highlight row text color is set, use that, otherwise, use default
						if ($highlight_text_color!="") {
							$textColor = $highlight_text_color;
						} else {
							$textColor = '#000000';
						}
					} 
					
					$html .= '<tr style="background-color:'.$rowColor.'; color:'.$textColor.';"><th scope="row" style="vertical-align:top; width:25%; text-align:left;">'.esc_html( $field->name ).'</th>';
					$html .= '<td valign="top" align="left" style="width:75%">';                  
					$embedded_field_id = ( $entry->form_id != $field->form_id ) ? 'form' . $field->form_id : 0;
					$atts = array(
						'type' => $field->type, 'post_id' => $entry->post_id,
						'show_filename' => true, 'show_icon' => false, 'entry_id' => $entry->id,
						'embedded_field_id' => $embedded_field_id,
					);
					$display_value = FrmEntriesHelper::prepare_display_value($entry, $field, $atts);
					$html.=$display_value;
					$html.='</td></tr>'; 
				}
				$i++;           
			}
			$html.='</tbody></table></body></html>';	
			//echo $html;
			/*Generate the pdf using dompdf*/
			$frm_path = dirname(__FILE__);
			define("DOMPDF_ENABLE_REMOTE", true);
			require_once( $frm_path.'/dompdf/dompdf_config.inc.php');
			$dompdf = new DOMPDF();
			$dompdf->load_html($html);
			$dompdf->render();
			$output = $dompdf->output();
			
			/*Get user info for entry and save pdf to users directory*/
			$upload_dir = wp_upload_dir();
			//$file_path= $upload_dir['basedir'].'/applications/'.date("Y").'/'.$user_info->ID.'-'.sanitize_title_with_dashes($user_info->user_lastname)."-".sanitize_title_with_dashes($user_info->user_firstname)."/";			
			//$file_name = sanitize_title_with_dashes($user_info->user_lastname)."_".sanitize_title_with_dashes($user_info->user_firstname)."_".sanitize_title_with_dashes($entry->form_name).".pdf";
			
			$file_path= $upload_dir['basedir'].'/applications/'.date("Y").'/'.$user_info->ID.'/';
			$file_name = $user_info->ID."-".sanitize_title_with_dashes($entry->form_name).".pdf";
			if( !file_exists( $file_path )){
				if( !mkdir($file_path) ){
					return false;
				}
			}
			$file_to_save = $file_path.$file_name;
			file_put_contents($file_to_save, $output);
		}
	}
}


//* Add email reminder form to remind users who have saved application drafts to come back and finish the application
/** Step 1. */

function email_reminder_menu() {
	add_submenu_page( 'formidable', 'Application reminders', 'Send email reminder', 'manage_options', 'email_reminder', 'my_plugin_options' );
}
/** Step 2 (from text above). */
add_action( 'admin_menu', 'email_reminder_menu' );

/** Step 3. */
function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	global $wpdb;
	if( !empty($_POST['notification_submit']) ){
		if( !empty($_POST['notification_subject']) && !empty($_POST['notification_message']) && is_numeric($_POST['form_id']) ){
			$form_id = $_POST['form_id'];
			
			$query= 'SELECT it.*,wu.*, fr.name as form_name,fr.form_key as form_key FROM wp_frm_items it LEFT OUTER JOIN wp_frm_forms fr ON it.form_id=fr.id INNER JOIN wp_users wu ON (wu.ID=it.user_id) WHERE it.form_id='.$form_id.' and it.is_draft=1';
			$entries = $wpdb->get_results($query);
			$emailsHash = array();
			if( !empty($entries) ){
				$subject = $_POST['notification_subject'];
				$body = $_POST['notification_message'];
				$headers = 'From: '.get_option('blogname').' <'.get_option('admin_email').'>'. "\r\n";
				foreach($entries AS $entry){
					if( !in_array($entry->user_email, $emailsHash ) ){
						wp_mail( $entry->user_email, $subject, $body, $headers );
					}
				}
				$successMsg ="Emails sucessfully sent to users.";
			}else{
				$errorMsg ="No draft application owners found.";
			}
		}else{
			$errorMsg ="Please fill in all the fields to send the email reminder";
		}
	}
	echo '<div class="wrap">';
	if(!empty($errorMsg)){
		echo '<div class="error">'.$errorMsg.'</div>';
	}
	if(!empty($successMsg)){
		echo '<div class="notice">'.$successMsg.'</div>';
	}
	echo '<h2>Send email reminder</h2>';
	echo '<p>Send a messsage to all users who have saved a draft of the application but have not yet completed it.</p>';
	echo '<form id="application_notification" name="application_notification" method="post">';
	
	$query= 'SELECT fr.*, fr.form_key as form_key FROM wp_frm_forms fr WHERE status="published" AND default_template=0 ORDER BY fr.name ASC';
	$forms = $wpdb->get_results($query);
	//var_dump($forms);
	
	echo '<p>Message applicants who have started:<br></p>  ';
	//print form selector
	echo form_selector($_POST['form_id']);

	echo '<p>To: <strong>all application draft owners</strong></p>';
	echo '<label for="notification_subject">Subject</label><input type="text" name="notification_subject" id="notification_subject" value='.$_POST['notification_subject'].'>';
	echo '<label for="notification_message">Message</label><textarea name="notification_message" id="notification_message" rows="10">'.$_POST['notification_message'].'</textarea>';
	echo '<input type="submit" name="notification_submit" value="Send!">';
	
	echo '</form>';
	echo '</div>';
}

//function to loop through all available forms and show options to user
function form_selector($current) {
	global $wpdb;
	$query= 'SELECT fr.*, fr.form_key as form_key FROM wp_frm_forms fr WHERE status="published" AND default_template=0 ORDER BY fr.name ASC';
	$forms = $wpdb->get_results($query);
	
	$formselector = '<select name="form_id">';
	foreach($forms AS $form){
		if ($form->name) {
			$selected='';
			if ($current && $current == $form->id) {
				$selected = 'selected="selected"';
			}
			$formselector .= '<option value="'.$form->id.'" '.$selected.'>'.$form->name.'</option>';
		}
	}
	$formselector .= '</select>';
	return $formselector;
	
}


/************** Adding an additional configuration menu to plugin menu ****************/
function highlight_menu() {
	add_submenu_page( 'formidable', 'Highlight', 'Field highlighter', 'manage_options', 'highlight', 'my_highlight_options' );
}
/** Step 2 (from text above). */
add_action( 'admin_menu', 'highlight_menu' );

/** Step 3. */
function my_highlight_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	if( !empty($_POST['highlight_submit']) ){
		//update_option( 'formidable_my_highlight_form_id', str_replace(" ", "", $_POST['form_id']) );
		update_option( 'formidable_my_highlight_options', str_replace(" ", "", $_POST['highlight_values']) );
		update_option( 'formidable_my_highlight_text_color', str_replace(" ", "", $_POST['highlight_text_color']) );
		update_option( 'formidable_my_highlight_row_color',  str_replace(" ", "", $_POST['highlight_bg_color']) );
		$successMsg ="Field keys successfully set.";
	}
	
	
	echo '<div class="wrap">';
	if (isset($successMsg)) {
		echo '<div class="notice">'.$successMsg.'</div>';
	}
	echo '<h2>Field highlighter</h2>';
	echo '<p>If you’d like to highlight specific fields on the PDF output of your form, please enter the field keys below, use commas to separate each field key.<br>To obtain field keys, go to <strong>Forms</strong>, select the form, then choose “Field Options” for the field you wish to highlight, copy and paste its Field Key below. </p>';
	
	echo '<form id="application_notification" name="highlight" method="post">';
	//echo form_selector( get_option( 'formidable_my_highlight_form_id', '' ) );
	echo '<textarea name="highlight_values" placeholder="k7p6yy, n7ir29, bbk21x...">'.get_option( 'formidable_my_highlight_options', '' ).'</textarea>';
	echo '<label for="highlight_text_color">Enter the highlighted text color as hex value:</label><br>';
	echo '<input id="highlight_text_color" type="text" name="highlight_text_color" placeholder="Example: #FFFFFF" style="width:50%;" value="'.get_option( 'formidable_my_highlight_text_color', '' ).'">';
	echo '<label for="highlight_bg_color">Enter the highlighted row background color as hex value (use “inherit” to keep alternating row colors):</label><br>';
	echo '<input id="highlight_bg_color" type="text" name="highlight_bg_color" placeholder="Example: #000000" style="width:50%;" value="'.get_option( 'formidable_my_highlight_row_color', '' ).'">';
	echo '<input type="submit" name="highlight_submit" value="Set highlight">';
	echo '</form>';
	echo '</div>';
}
/******** END congig menu item ********************************************************/


function hide_dupe() {
	echo '<style>
    li.toplevel_page_formidable ul li.wp-first-item {
		display:none;
	}
	#application_notification input[type="text"],
	#application_notification textarea {
		width:100%;
		display:block;
		padding:10px;
		margin:10px 0;
	}
	.notice, .error {
		padding:20px 10px;
	}
	.notice {
		border-left:5px #6fe10a solid;
	}
	.error {
		border-left:5px #e13c0a solid;
	}
  	</style>';
}
add_action('admin_head', 'hide_dupe');


//strip linebreaks from all form inputs
add_filter('frm_validate_field_entry', 'strip_line_breaks', 10, 3);
function strip_line_breaks($errors, $posted_field, $posted_value){
  if (is_string($_POST['item_meta'][$posted_field->id])) {
  	$_POST['item_meta'][$posted_field->id] = preg_replace( "/\r|\n/", "", $_POST['item_meta'][$posted_field->id]);
  }
 return $errors;
}