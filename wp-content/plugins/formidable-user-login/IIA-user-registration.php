<?php
/*
Plugin Name: Formidable User Login
Plugin URI: http://cloudred.com
Description: If you want to require user registration on your site, enable the "Formidable User Login" plugin. It is not required for the application forms. This plugin will enhance default wordpress user journey. It will also allow your users to save “in-progress” or draft applications.
Version: 1.0
Author: cloudred
Author URI: http://cloudred.com
*/

/* ------------------------- Actions --------------------------- */

//Change Login Logo Link URL
function my_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return get_option('blogname');
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

function my_page_template_redirect(){
    //convert my option to array
	$redirects = explode(",",get_option( 'formidable_my_user_login_settings', '' ));
	foreach ($redirects as $redirect) {
		if( is_page( $redirect ) && ! is_user_logged_in() ){
			if (get_option( 'formidable_my_login_page_template', '' ) && strlen(get_option( 'formidable_my_login_page_template', '' )) > 0) {
				wp_redirect( home_url( '/'.get_option( 'formidable_my_login_page_template', '' ).'/?redirect='.get_option( 'formidable_my_user_login_redirect', '' ).'' ) );
			} else {
				//use default login URL
				wp_redirect( wp_login_url("/".get_option( 'formidable_my_user_login_redirect', '' )."/") );
			}
			exit();
		}
	}
}
add_action( 'template_redirect', 'my_page_template_redirect' );


//************ Add first name and last name to user registration form **********/
add_action( 'register_form', 'ac_extra_reg_fields', 1 );
function ac_extra_reg_fields() { 

	?>
	<script>
	//remove special characters and force to lowercase the input of username
	jQuery('#user_login').keyup(function() {
    	this.value = this.value.toLowerCase().replace(/[^0-9a-z-]/g,"");
	});
	</script> 
	<p>
	<label>First Name<br/>
	<input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( $_POST['first_name'] ); ?>" size="25" />
	</label>
	</p>
	
	<p>
	<label>Last Name<br/>
	<input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( $_POST['last_name'] ); ?>" size="25"  />
	</label>
	</p>
    <p>
    <input type="hidden" title="leave me blank" name="middle_name" id="middle_name" class="input" size="25" />
    </p>
    
    
    
<?php }

add_filter( 'registration_errors', 'user_registration_errors', 10, 3 );
    function user_registration_errors( $errors, $sanitized_user_login, $user_email ) {
        
        if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
            $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: You must include first name.', 'mydomain' ) );
        }
		if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
            $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: You must include your last name.', 'mydomain' ) );
        }
		//this is for spam bots, if filled in, reject regisgtration
		if ( !empty( $_POST['middle_name'] ) ) {
            wp_redirect( "http://www.un.org/en/aboutun/terms/" );
			die;
        }

        return $errors;
    }

add_action( 'user_register', 'user_registration_save', 10, 1 );
function user_registration_save( $user_id ) {
	if ( isset( $_POST['first_name'] ) )
        update_user_meta($user_id, 'first_name', $_POST['first_name']);
	if ( isset( $_POST['last_name'] ) )
        update_user_meta($user_id, 'last_name', $_POST['last_name']);
}

//modify register screen styles
add_action('login_head', 'modify_custom_reg');
function modify_custom_reg() {
  	?>
	<style>
	#login {
		width:50%;
	}
	#login h1 a {
		background-image:url(<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png);
		background-size:auto;
		width:auto;
		height:auto;
		min-height:100px;
	}
	</style>
<?php }

/* changes the "Register For This Site" text on the Wordpress login screen (wp-login.php) */
function change_login_message($message) {
	// change messages that contain 'Register'
	if (strpos($message, 'Register') !== FALSE) {
		//see if login page is set
		if (get_option( 'formidable_my_login_page_template', '' ) && strlen(get_option( 'formidable_my_login_page_template', '' )) > 0) {
			 $login_url = home_url( '/'.get_option( 'formidable_my_login_page_template', '' ).'/?redirect='.get_option( 'formidable_my_user_login_redirect', '' ).'' );
		} else {
			//use default login URL
			$login_url = wp_login_url("/".get_option( 'formidable_my_user_login_redirect', '' )."/");
		}
		$newMessage = 'Please register for a new account to start your application process. If you are already registered, <a href="'.$login_url.'">log in</a> to continue your application process.';
		return '<p class="message register">' . $newMessage . '</p>';
	} else if (strpos($message, 'Register') == FALSE) {
		return $message;
	} else {
		return $message;
	}
}
// add our new function to the login_message hook
add_action('login_message', 'change_login_message');

// Redefine user notification function
if ( !function_exists('wp_new_user_notification') ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);
		$headers = 'From: '.get_option('blogname').' <'.get_option('admin_email').'>'. "\r\n";

        $message  = sprintf(__('New user registration on your site %s:'), get_option('blogname')) . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

        @wp_mail(get_option('admin_email'), 'New User Registration', $message, $headers);

        if ( empty($plaintext_pass) )
            return;

        //$message  = __('Hi there,') . "\r\n\r\n";
        $message = sprintf(__("Thank you for registering to apply for %s! Here’s how to log in:"), get_option('blogname')) . "\r\n\r\n";
        $message .= wp_login_url() . "\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n";
        $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n";
        $message .= __('Please save this email for future reference. You will need this password to log in.') . "\r\n";
        $message .= __('Good luck with your application!');

        wp_mail($user_email, 'Your username and password', $message, $headers);

    }
}

// Redefine password from name and email, globally
add_filter( 'wp_mail_from', 'wpse_new_mail_from' );     
function wpse_new_mail_from( $old ) {
    return get_option('admin_email');
}

add_filter('wp_mail_from_name', 'wpse_new_mail_from_name');
function wpse_new_mail_from_name( $old ) {
    return get_option('blogname');
}

//redirect admins to dashboard, and all others to application form page
/**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */
function my_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	global $user;
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			return $redirect_to;
		} else {
			return '/'.get_option( 'formidable_my_user_login_redirect', '' ).'/';
		}
	} else {
		return $redirect_to;
	}
}

add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );


//hide admin dashboard bar from subscriber level users
function hide_admin_bar() {
	if ( is_user_logged_in() ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			show_admin_bar( false );
		}
	}
}
add_action('init', 'hide_admin_bar');


/************** Adding an additional configuration menu to plugin menu ****************/
function user_login_menu() {
	add_submenu_page( 'formidable', 'User Login Settings', 'User login settings', 'manage_options', 'user_login_settings', 'user_login_settings' );
}
/** Step 2 (from text above). */
add_action( 'admin_menu', 'user_login_menu' );

/** Step 3. */
function user_login_settings() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	if( !empty($_POST['user_login_settings_submit']) ){
		update_option( 'formidable_my_user_login_settings', str_replace(" ", "", $_POST['page_slugs']) );
		update_option( 'formidable_my_user_login_redirect', str_replace(" ", "", $_POST['page_slug_redirect']) );
		update_option( 'formidable_my_login_page_template', str_replace(" ", "", $_POST['login_page_template']) );
		
		//save reg copy
		//update_option( 'formidable_my_reg_subject', $_POST['reg_subject']);
		//update_option( 'formidable_my_reg_message', $_POST['reg_message']);
		$successMsg ="Login rules successfully set.";
	}
	
	
	echo '<div class="wrap">';
	if (isset($successMsg)) {
		echo '<div class="notice">'.$successMsg.'</div>';
	}
	echo '<h2>User login settings</h2>';
	echo '<p>Specify what forms should be password protected and where to redirect users upon successful login.</p>';
	
	echo '<form id="application_notification" name="user_login_settings" method="post">';
	//echo form_selector( get_option( 'formidable_my_highlight_form_id', '' ) );
	echo '<label for="page_slugs">Enter slugs of pages that should be password protected, separate with commas:</label>';
	echo '<textarea name="page_slugs" id="page_slugs">'.get_option( 'formidable_my_user_login_settings', '' ).'</textarea>';
	echo '<label for="page_slug_redirect">Enter the slug of page where a user should be redirected upon successful log in:</label><br>';
	echo '<input id="page_slug_redirect" type="text" name="page_slug_redirect" value="'.get_option( 'formidable_my_user_login_redirect', '' ).'" style="width:35%;">';
	echo '<label for="login_page_template">Custom login page slug (if you want to redirect users to a custom login page):</label><br>';
	echo '<input id="login_page_template" type="text" name="login_page_template" value="'.get_option( 'formidable_my_login_page_template', '' ).'" style="width:35%;">';
	
	/*echo '<h2>Registration email copy</h2>';
	echo '<p>Set custom registration copy for welcome email.</p>';
	
	echo '<label for="reg_subject">Subject:</label><br>';
	echo '<input id="reg_subject" type="text" name="reg_subject" value="'.get_option( 'formidable_my_reg_subject', '' ).'" style="width:35%;">';
	
	echo '<label for="reg_message">Message:</label>';
	echo '<p>You can use these placeholders: [LOGINURL], [USERNAME], [PASSWORD]</p>';
	echo '<textarea name="reg_message" id="reg_message" rows="5">'.get_option( 'formidable_my_reg_message', '' ).'</textarea>';*/
	
	
	echo '<input type="submit" name="user_login_settings_submit" value="Save">';
	echo '</form>';
		
	echo '</div>';
}
/******** END congig menu item ********************************************************/

