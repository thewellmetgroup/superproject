# Formidable User Login Plugin
This plugin will enhance default wordpress user journey but is not required for the application forms to work. If you want to require user registration on your site for form submissions, enable the “formidable-user-login” plugin.  This plugins adds:
* Adds spam protection via the honeypot method on user registration form
* Adds first and last name fields to registration form
* Modifies default styling of wordpres registration, login and password reset forms. (add “images/logo.png” to your theme directory to replace default wordpress logo with your organization’s logo)
* Replaces links to wordpress.org with your website home URL
* Modifies default user registration screen copy (edit text on additional settings page "User login settings" in admin)
* Removes dashboard link and top bar for non-admin users
* Enforces lowercase and removes special characters from usernames on input
* Enables you to add a settings menu to password protect specific forms and redirect users to a speicific application
* Sets custom registration welcome email
* Enables you to set a custom login redirect rather than using default wordpress login. Set your custom login page slug under the “User login settings” menu. Create a new template called "template-login.php" with the following PHP code to change links to the /login/ page. If not set, plugin will point to default wordpress login URL (wp-login.php).
```
<?php
	if ( !is_user_logged_in() ) {
?>
<h2>Log in to apply</h2>
<p>If you already have a user account, please log in, otherwise <a href="<?php echo wp_registration_url(); ?>">"><strong>register</strong></a> for a new user account.</p>
<?php 
	if (isset($_GET['redirect'])) {
		$whereto = $_GET['redirect'];
	} else {
		$whereto = home_url();
	}
	$args = array(
	'redirect'       => $whereto
	);
	wp_login_form($args); ?>
    <p><a href="<?php echo wp_lostpassword_url(); ?>">Lost your password?</a><br>
    <a href="<?php echo wp_registration_url(); ?>">">To start your application, <strong>register</strong> for a new account.</a></p>
<?php } else { ?>
   	<div class='login-status'>You are already logged in!</div>
<?php } ?>
```


## Installation
* Install the “formidable-user-login” plugin in your plugins directory
* Go to your Plugins
* Activate the plugin through the Plugins menu
