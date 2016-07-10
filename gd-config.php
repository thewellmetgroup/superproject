<?php

define( 'GD_VIP', '45.40.149.159' );
define( 'GD_RESELLER', 495469 );
define( 'GD_ASAP_KEY', 'e627303792e13cdfc56bc95573e52547' );
define( 'GD_STAGING_SITE', false );
define( 'GD_EASY_MODE', false );
define( 'GD_SITE_CREATED', 1468151141 );

// Newrelic tracking
if ( function_exists( 'newrelic_set_appname' ) ) {
	newrelic_set_appname( '50f2319f-0e73-427c-a1a1-cb25f00a2ec6;' . ini_get( 'newrelic.appname' ) );
}

/**
 * Is this is a mobile client?  Can be used by batcache.
 * @return array
 */
function is_mobile_user_agent() {
	return array(
	       "mobile_browser"             => !in_array( $_SERVER['HTTP_X_UA_DEVICE'], array( 'bot', 'pc' ) ),
	       "mobile_browser_tablet"      => false !== strpos( $_SERVER['HTTP_X_UA_DEVICE'], 'tablet-' ),
	       "mobile_browser_smartphones" => in_array( $_SERVER['HTTP_X_UA_DEVICE'], array( 'mobile-iphone', 'mobile-smartphone', 'mobile-firefoxos', 'mobile-generic' ) ),
	       "mobile_browser_android"     => false !== strpos( $_SERVER['HTTP_X_UA_DEVICE'], 'android' )
	);
}