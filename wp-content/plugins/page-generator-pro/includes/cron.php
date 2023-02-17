<?php
/**
 * Cron Functions
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Define the WP Cron function to perform the log cleanup
 *
 * @since   2.6.1
 */
function page_generator_pro_log_cleanup_cron() {

	// Initialise Plugin.
	$page_generator_pro = Page_Generator_Pro::get_instance();
	$page_generator_pro->initialize();

	// Call CRON Log Cleanup function.
	$page_generator_pro->get_class( 'cron' )->log_cleanup();

	// Shutdown.
	unset( $page_generator_pro );

}
add_action( 'page_generator_pro_log_cleanup_cron', 'page_generator_pro_log_cleanup_cron' );
