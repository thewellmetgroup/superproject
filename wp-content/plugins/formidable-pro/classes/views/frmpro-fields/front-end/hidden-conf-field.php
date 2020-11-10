<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<input type="hidden" name="item_meta[conf_<?php echo esc_attr( $field['id'] ) ?>]" id="<?php echo esc_attr( $field['html_id'] . '-conf' ) ?>" value="<?php echo esc_attr( $value ); ?>" />
