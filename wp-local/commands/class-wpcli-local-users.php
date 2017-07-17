<?php

/**
 * Created by PhpStorm.
 * User: ryanwelcher
 * Date: 2017-07-13
 * Time: 11:42 AM
 */
class LocalUsers extends WP_CLI_Command {
	
	/** Default username */
	const USERNAME = 'admin';
	
	/** Default password */
	const PASSWORD = 'password';

	/**
	 * Create a user for me to use locally.
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
	 *     wp local-users create-me
	 *     wp local-users create-me ryan
	 *     wp local-users create-me ryan --pass=1234
	 *     wp local-users add ryan --pass=1234
	 *
	 * @subcommand create-me
	 * @alias add
	 */
	function create_me( $args, $assoc_arg ) {
		$username            = $args[0]           ?? self::USERNAME;
		$password            = $assoc_arg['pass'] ?? self::PASSWORD;
		$run_command_options = [ 'return' => true, 'exit_error' => false ];
		$status              = 'created';
		$email               = 'test_' . rand() . '@test.com';
		
		$user_id = WP_CLI::runcommand( "user create {$username} {$email} --user_pass={$password} --role=administrator --porcelain", $run_command_options ) ;
		if ( empty( $user_id ) ) {
			$existing_user_id = WP_CLI::runcommand( "user get {$username} --field=ID", $run_command_options );
			if ( $existing_user_id ) {
				$user_id = intval( $existing_user_id );
				WP_CLI::error( "The user `{$username}` already exists with ID {$user_id}" );

			} else {
				WP_CLI::error( "There was an issue creating the user `{$username }` and a user by that name was not found." );
			}
		}
		WP_CLI::success( "The user {$username} was create with an id of {$user_id}." );
		
		// If we're a multisite, we want the user to be a super-admin
		if ( is_multisite() ) {
			WP_CLI::confirm( "This is a multisite install. Should I give `{$username}` super-admin status?" );
			WP_CLI::runcommand( "super-admin add {$user_id}" );
			WP_CLI::runcommand( "local-users add-to-sites {$username}" );
		}
	}
	
	
	/**
	 * Adding a user to all of the sites as administrator
	 *
	 * Will update tag display name.
	 *
	 * ## OPTIONS
	 *
	 * <username>
	 * : The user id we need to add.
	 *
	 *
	 * ## EXAMPLES
	 *
	 *     wp local-users add-to-sites ryan
	 *
	 * @subcommand add-to-sites
	 *
	 */
	function add_to_sites( $args, $assoc_arg ) {
		$user_id = $args[0];
		$dry_run = $assoc_arg['dry-run'] ?? false;
		if ( $user = get_user_by( 'login', $user_id ) ) {
			$sites = get_sites();
			
			foreach ( $sites as $blog ) {
				WP_CLI::line('Adding to ' . $blog->blogname );
				if ( ! $dry_run ) {
					add_user_to_blog( $blog->ID, $user->ID, 'administrator' );
				}
			}
		}
		
	}
}

WP_CLI::add_command( 'local-users', 'LocalUsers' );