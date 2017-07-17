# WP Local

A plugin that adds simple helper methods and WP_CLI commands when developing for WordPress


## Methods

Just one for now.

* rw_dump_array( $thing ) -  wraps print_r( $thing ) in pre tags for a prettier output


## WP_CLI


Currently there are three commands:

### These have been deprecated - use Grunt instead ###
* wp rwdev js - scans files for console.log() and comments them out

* wp rwdev tags - scans files for shorthand php tags and replaces them with proper ones

* wp rwdev functions - scans for the helper methods and comments them out

