<?php

namespace WPFormsUserJourney;

/**
 * User Journey addon install.
 *
 * @since 1.0.0
 */
class Install {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->hooks();
	}

	/**
	 * Add hooks.
	 *
	 * @since 1.0.6
	 */
	public function hooks() {

		/**
		 * Check if wpforms plugin is active.
		 * is_plugin_active() is unavailable on plugin activation.
		 */
		if ( ! $this->is_core_plugin_active() ) {
			/**
			 * We have to deactivate addon when WPForms is not active.
			 * This addon must be activated in presence of core plugin only,
			 * as it requires core class on activation hook.
			 */
			add_action( 'admin_init', 'wpforms_user_journey_deactivation' );
			add_action( 'admin_notices', 'wpforms_user_journey_fail_wpforms_version' );

			if ( $this->is_plugin_activation() ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				unset( $_GET['activate'], $_GET['activate-multi'] );
			}

			return;
		}

		register_activation_hook( WPFORMS_USER_JOURNEY_FILE, [ $this, 'install' ] );

		add_action( 'wpmu_new_blog', [ $this, 'new_multisite_blog' ], 10, 6 );
		add_action( 'wpforms_loaded', [ $this, 'check_table' ] );
	}

	/**
	 * Detect whether plugin is active.
	 *
	 * @since 1.0.6
	 *
	 * @return bool
	 */
	private function is_core_plugin_active() {

		$plugin = 'wpforms/wpforms.php';

		if ( function_exists( 'is_plugin_active' ) ) {
			return is_plugin_active( $plugin );
		}

		return preg_grep( '#' . $plugin . '#', wp_get_active_and_valid_plugins() );
	}

	/**
	 * Check if we are in the plugin activation request.
	 *
	 * @since 1.0.6
	 *
	 * @return bool
	 */
	private function is_plugin_activation() {

		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$request_uri = wp_parse_url( $request_uri, PHP_URL_PATH );
		$plugins_uri = wp_parse_url( admin_url( 'plugins.php' ), PHP_URL_PATH );

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return $request_uri === $plugins_uri && ( isset( $_GET['activate'] ) || isset( $_GET['activate-multi'] ) );
	}

	/**
	 * Perform certain actions on plugin activation.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $network_wide Whether to enable the plugin for all sites in the network
	 *                           or just the current site. Multisite only. Default is false.
	 */
	public function install( $network_wide = false ) {

		// Check if we are on multisite and network activating.
		if ( is_multisite() && $network_wide ) {

			// Multisite - go through each subsite and run the installer.
			$sites = get_sites(
				[
					'fields' => 'ids',
					'number' => 0,
				]
			);

			foreach ( $sites as $blog_id ) {
				switch_to_blog( $blog_id );
				$this->run();
				restore_current_blog();
			}
		} else {

			// Normal single site.
			$this->run();
		}
	}

	/**
	 * Run the actual installer.
	 *
	 * @since 1.0.0
	 */
	protected function run() {

		$db = new DB();

		// Create the table if it doesn't exist.
		if ( ! $db->table_exists() ) {
			$db->create_table();
		}

		update_option( 'wpforms_user_journey_version', WPFORMS_USER_JOURNEY_VERSION );
	}

	/**
	 * When a new site is created in multisite, see if we are network activated,
	 * and if so run the installer.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $blog_id Blog ID.
	 * @param int    $user_id User ID.
	 * @param string $domain  Site domain.
	 * @param string $path    Site path.
	 * @param int    $site_id Site ID. Only relevant on multi-network installs.
	 * @param array  $meta    Meta data. Used to set initial site options.
	 */
	public function new_multisite_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

		if ( is_plugin_active_for_network( plugin_basename( WPFORMS_USER_JOURNEY_FILE ) ) ) {
			switch_to_blog( $blog_id );
			$this->run();
			restore_current_blog();
		}
	}

	/**
	 * Check that addon table exists.
	 *
	 * @since 1.0.6
	 */
	public function check_table() {

		if ( WPFORMS_USER_JOURNEY_VERSION !== get_option( 'wpforms_user_journey_version' ) ) {
			$this->run();
		}
	}
}
