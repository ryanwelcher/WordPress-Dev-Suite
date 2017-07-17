<?php

/**
 * Plugin Name: WP Local
 * Description: A suite of tools for working with WordPress locally. Not for Production use. Drop into your mu-plugins folder.
 * Author: Ryan Welcher
 * Version: 1.0.0
 * Author URI: http://www.ryanwelcher.com
 */

/**
 * Helper method to output an array
 *
 * wraps a print_r() call in <pre> tags
 * 
 * @since 1.0.0
 * @param mixed $array The array or object to be output
 */
function rw_dump_array( $array ) {
	echo '<pre>' . print_r( $array , 1 ) .'</pre>';
}


/**
 * Register the shutdown function
 *
 * @since 1.0.0
 * @uses  rw_check_for_fatal_errors
 */
if( function_exists('register_shutdown_function') ) {
	register_shutdown_function( "rw_check_for_fatal_errors" );
}

/**
 * Handles any fatals errors to show the error, where they occured and give you a hard time about it :)
 * 
 * @since 1.0.0
 * @uses register_shutdown_function()
 */
function rw_check_for_fatal_errors() {

	$error = error_get_last();

	if ( $error["type"] == E_PARSE ) {
		$msg  = '<h2>Fatal Error - c\'mon you\'re better than that :) </h2>';
		$msg .= '<strong>Error Type : </strong><i>' . $error['type'] . '</i><br/>';
		$msg .= '<strong>Message : </strong><i>' . $error['message'] .'</i><br/>';
		$msg .= '<strong>File : </strong><i>' . $error['file'] . '</i><br/>';
		$msg .= '<strong>On Line : </strong><i>' . $error['line'] .'</i><br/>';
		echo $msg;

	}
}


//===============
// WP_CLI tools
//===============
/**
 * Require the WP_CLI classes
 */
if ( defined('WP_CLI') && WP_CLI ) {
	require_once 'rw-dev-suite/commands/class-wpcli-local-users.php';
	require_once 'rw-dev-suite/commands/class-wpcli-local-sites.php';
}