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
	}
}

WP_CLI::add_command( 'local-sites', 'LocalSites' );