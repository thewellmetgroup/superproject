<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

//remove WP version from header
remove_action('wp_head', 'wp_generator');

/* Show Password Protected Content To Logged In Users */
add_filter( 'post_password_required', __NAMESPACE__ . '\\logged_in_show_password_protected', 10, 2 );
function logged_in_show_password_protected( $returned, $post ) {
  
    if ( $returned && is_user_logged_in() )
        $returned = false;

    return $returned;
}


//REdirect on logout
add_action('wp_logout',__NAMESPACE__ . '\\ps_redirect_after_logout');
function ps_redirect_after_logout(){
         wp_redirect( '/login/' );
         exit();
}

//Redirect on login
function my_login_redirect( $redirect_to, $request, $user ) {
    $redirect_to =  '/member-resources/';
 
    return $redirect_to;
}
 
add_filter( 'login_redirect', __NAMESPACE__ . '\\my_login_redirect', 10, 3 );