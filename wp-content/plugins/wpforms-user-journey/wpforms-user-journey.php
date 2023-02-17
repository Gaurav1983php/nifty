<?php
/**
 * Plugin Name:       WPForms User Journey
 * Plugin URI:        https://wpforms.com
 * Description:       User Journey addon for WPForms.
 * Requires at least: 4.9
 * Requires PHP:      5.6
 * Author:            WPForms
 * Author URI:        https://wpforms.com
 * Version:           1.0.6
 * Text Domain:       wpforms-user-journey
 * Domain Path:       languages
 *
 * WPForms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WPForms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WPForms. If not, see <https://www.gnu.org/licenses/>.
 */

// phpcs:ignore Generic.Commenting.DocComment.MissingShort
/** @noinspection PhpDefineCanBeReplacedWithConstInspection */

use WPFormsUserJourney\Install;
use WPFormsUserJourney\Loader;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants.
// phpcs:disable WPForms.Comments.PHPDocDefine.MissPHPDoc
define( 'WPFORMS_USER_JOURNEY_VERSION', '1.0.6' );
define( 'WPFORMS_USER_JOURNEY_PATH', __DIR__ . '/' );
define( 'WPFORMS_USER_JOURNEY_FILE', __FILE__ );
// phpcs:enable WPForms.Comments.PHPDocDefine.MissPHPDoc

/**
 * Load the addon.
 *
 * @since 1.0.5
 */
function wpforms_user_journey_load() {

	// Check requirements.
	if ( ! wpforms_user_journey_required() ) {
		return;
	}

	// Load the addon.
	wpforms_user_journey();
}

add_action( 'wpforms_loaded', 'wpforms_user_journey_load' );

/**
 * Check addon requirements.
 *
 * @since 1.0.2
 *
 * @return bool
 */
function wpforms_user_journey_required() {

	if ( PHP_VERSION_ID < 50600 ) {
		add_action( 'admin_init', 'wpforms_user_journey_deactivation' );
		add_action( 'admin_notices', 'wpforms_user_journey_fail_php_version' );

		return false;
	}

	if ( ! function_exists( 'wpforms' ) ) {
		return false;
	}

	if ( version_compare( wpforms()->version, '1.7.7', '<' ) ) {
		add_action( 'admin_init', 'wpforms_user_journey_deactivation' );
		add_action( 'admin_notices', 'wpforms_user_journey_fail_wpforms_version' );

		return false;
	}

	if ( ! in_array( wpforms_get_license_type(), [ 'pro', 'elite', 'agency', 'ultimate' ], true ) ) {
		return false;
	}

	return true;
}

/**
 * Deactivate the plugin.
 *
 * @since 1.0.0
 */
function wpforms_user_journey_deactivation() {

	deactivate_plugins( plugin_basename( WPFORMS_USER_JOURNEY_FILE ) );
}

/**
 * Display notice after deactivation.
 *
 * @since 1.0.0
 */
function wpforms_user_journey_fail_php_version() {

	echo '<div class="notice notice-error"><p>';
	printf(
		wp_kses( /* translators: %s - WPForms.com documentation page URL. */
			__( 'The WPForms User Journey plugin has been deactivated. Your site is running an outdated version of PHP that is no longer supported and is not compatible with the User Journey plugin. <a href="%s" target="_blank" rel="noopener noreferrer">Read more</a> for additional information.', 'wpforms-user-journey' ),
			[
				'a' => [
					'href'   => [],
					'rel'    => [],
					'target' => [],
				],
			]
		),
		esc_url( wpforms_utm_link( 'https://wpforms.com/docs/supported-php-version/', 'all-plugins', 'User Journey PHP Notice' ) )
	);
	echo '</p></div>';

	if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
}

/**
 * Admin notice for minimum WPForms version.
 *
 * @since 1.0.2
 */
function wpforms_user_journey_fail_wpforms_version() {

	echo '<div class="notice notice-error"><p>';
	esc_html_e( 'The WPForms User Journey plugin has been deactivated because it requires WPForms v1.7.7 or later to work.', 'wpforms-user-journey' );
	echo '</p></div>';

	// phpcs:disable
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
	// phpcs:enable
}

/**
 * Get the instance of the plugin main class,
 * which actually loads all the code.
 *
 * @since 1.0.0
 *
 * @return WPFormsUserJourney\Loader
 */
function wpforms_user_journey() {

	return Loader::get_instance();
}

require_once WPFORMS_USER_JOURNEY_PATH . 'vendor/autoload.php';

// Load installation things immediately for a reason how activation hook works.
new Install();
