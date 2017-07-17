<?php

/**
 * Created by PhpStorm.
 * User: ryanwelcher
 * Date: 2017-07-13
 * Time: 1:19 PM
 */
class LocalSites extends WP_CLI_COMMAND {
	
	
	/**
	 * Update all of the urls in a multisite
	 *
	 * Will update tag display name.
	 *
	 * ## OPTIONS
	 *
	 * [<username>]
	 * : Username we want to create. Defaults to `admin`.
	 *
	 * [--pass=<password>]
	 * : The password to set. Defaults to `password`
	 *
	 * ## EXAMPLES
	 *
	 *     wp local-sites update-ms-urls
	 *
	 * @subcommand update-ms-urls
	 */
	function update_ms_urls() {
		$sites = get_sites( ['fields' => 'ids' ] );
		
		WP_CLI::runcommand( 'site option list --site_id=1' );
		
		//wp search-replace www.northeastern.edu ne-cos.dev --network --dry-run
		
		
	}
	
	function api_test() {
		

		
		$url = 'http://ne-cos.dev/wp-json/wp/v2/faculty/22387?context=edit';
		$headers = array(
			'headers'=> [ 'Authorization' => 'Basic ' . base64_encode( 'ryan:password' ) ]
		);
		$request = wp_remote_get( $url, $headers );
		
		$data = json_decode( wp_remote_retrieve_body( $request ) );
		
		var_dump( $data->content );
		
	}
}

WP_CLI::add_command( 'local-sites', 'LocalSites' );