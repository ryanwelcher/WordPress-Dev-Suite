<?php
/**
 * wp_cli implementations for the dev suite
 */

class RW_DEV extends WP_CLI_Command {


	/**
	 * Counter for the number of replacements
	 * @var integer
	 */
	private $_total_replacments = 0;

	/**
	 * Checks the active theme or instances of dev functions
	 *
	 *
	 * ## EXAMPLES
	 *
	 * wp rwdev functions
	 *
	 * @synopsis
	 */
	public function functions() {

		$theme_dir = get_stylesheet_directory();
		WP_CLI::line('============== RW_DEV=============');
		WP_CLI::line('Starting scan of ' . $theme_dir . ' for dev references' );
		$this->open_and_scan_directory(	$theme_dir, 'find_and_replace_in_file' );

		//all done!
		WP_CLI::success('ALL DONE! Total number of items replaced: ' .$this->_total_replacments );

	}

	/**
	 * Checks the active theme for instances of php shortand tags
	 *
	 *
	 * ## EXAMPLES
	 *
	 * wp rwdev tags
	 *
	 * @synopsis
	 */
	public function tags() {
		$theme_dir = get_stylesheet_directory();
		WP_CLI::line('============== RW_DEV=============');
		WP_CLI::line('Starting scan of ' . $theme_dir . ' for shorthand php tags' );

		$this->open_and_scan_directory(	$theme_dir, 'find_shorthand_tags' );

		//all done!
		WP_CLI::success('ALL DONE! Total number bad php tags replaced: ' .$this->_total_replacments );
	}

	/**
	 * Checks the active theme for instances of php shortand tags
	 *
	 *
	 * ## EXAMPLES
	 *
	 * wp rwdev js
	 *
	 * @synopsis
	 */
	public function js() {

		$theme_dir = get_stylesheet_directory();
		WP_CLI::line('============== RW_DEV=============');
		WP_CLI::line('Starting scan of ' . $theme_dir . ' for left over js things' );

		$this->open_and_scan_directory(	$theme_dir, 'clean_js' );

		//all done!
		WP_CLI::success('ALL DONE! Total number of items fixed: ' .$this->_total_replacments );
	}


	//========
	//PRIVATE





	/**
	 * Recursively scans a directory and runs the passed method on any files inside.
	 *
	 * @since 1.0.0
	 * @access private
	 * 
	 * @param string $directory
	 * @param string $method
	 */
	private function open_and_scan_directory( $directory, $method ) {

		if( $handle = opendir( $directory) ) {
			while (false !== ($entry = readdir( $handle ) ) ) {

				if( !preg_match('/^\./', $entry) ) {

					if( is_file(  $directory . '/'.$entry  ) ) {
						//call the method passed
						call_user_func_array( array($this, $method), array( $directory . '/'.$entry ) );

					}elseif( is_dir( $directory . '/'.$entry ) ) {
						$this->open_and_scan_directory( $directory . '/'. $entry, $method );
					}
				}
		    }
		    closedir($handle);
		}
	}


	/**
	 * Finds instance of console.log() and replaces them with commented out versions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  string $filepath The path to the file to be chekced
	 */
	
	private function clean_js( $filepath ) {

		if( !preg_match('/.+\.min\.js/', $filepath ) ) {

			$contents = file_get_contents( $filepath );
			preg_match_all('/[^\/.+]console.log/',$contents, $matches);
			if( count( $matches[0]) > 0 ) {

				$count = count( $matches[0] );
				WP_CLI::warning('Commenting out ' . $count .' instance(s) in ' . basename( $filepath ) );
				$this->_total_replacments += $count;
				$new_content = preg_replace('/console.log/', '//console.log', $contents );
				file_put_contents( $filepath , $new_content);
			}

		}
	}


	/**
	 * Finds instances of rw_dump_array and comments them out.
	 *
	 * @since  1.0.0
	 * @access private
	 * @todo   As the list of helper methods grow, this will need to be refactored to looping a list.
	 * @param  string $filepath The filepath of the file to be scanned
	 */
	private function find_and_replace_in_file( $filepath ) {

		$contents = file_get_contents( $filepath );

		preg_match_all('/[^\/.+]\s+?rw_dump_array/',$contents, $matches);

		if( count( $matches[0]) > 0 ) {
			$count = count( $matches[0] );
			WP_CLI::warning('Replacing' . $count .' instance(s) in ' . basename( $filepath ) );
			$this->_total_replacments += $count;
			$patterns = array( '/rw_dump_array\((.*)\);/');
			$replace = array( "//rw_dump_array($1);");
			$new_content = preg_replace($patterns, $replace, $contents );
			file_put_contents( $filepath , $new_content);
		}
	}


	/**
	 * Finds and replaces shorthand php tags with the correct ones.
	 *
	 * 
	 * @since  1.0.0
	 * @access private
	 * 
	 * @param  string $filepath The filepath to the file we're checking
	 */
	private function find_shorthand_tags( $filepath ) {
		$contents = file_get_contents( $filepath );

		preg_match_all('/\s+?<\?=?\s/',$contents, $matches);

		if( count( $matches[0]) > 0 ) {
			$count = count( $matches[0] );
			WP_CLI::warning('Replacing ' . $count .' instance(s) in ' . basename( $filepath ) );
			$this->_total_replacments += $count;
			$patterns = array( '/<\?=?\s/');
			$replace = array( '<?php ');
			$new_content = preg_replace($patterns, $replace, $contents );
			file_put_contents( $filepath , $new_content);
		}
	}
}



//add the command to WP_CLI
WP_CLI::add_command( 'rwdev', 'RW_DEV' );