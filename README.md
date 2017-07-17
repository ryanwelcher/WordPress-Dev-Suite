# WP Local

A plugin that adds simple helper methods and WP_CLI commands when developing for WordPress


## Methods

Just one for now.

* rw_dump_array( $thing ) -  wraps print_r( $thing ) in pre tags for a prettier output


## WP_CLI

* wp rwdev functions - scans for the helper methods and comments them out
* wp local-users create-me:Creates a new user locally. Uses `admin`/`password` as the default username/password and will prompt if in multisite to make user and super-admin and assign to all sites.
    *     wp local-users create-me
    *     wp local-users create-me ryan
    *     wp local-users create-me ryan --pass=1234
    *     wp local-users add ryan --pass=1234 
* wp local-users add-to-sites - Add a user to all sites in the network as admin. Accepts username as param.
    *     wp local-users add-to-sites ryan


### These have been deprecated - use Grunt instead ###
* wp rwdev js - scans files for console.log() and comments them out
* wp rwdev tags - scans files for shorthand php tags and replaces them with proper ones
