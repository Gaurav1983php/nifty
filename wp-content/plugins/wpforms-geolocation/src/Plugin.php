<?php

namespace WPFormsGeolocation;

use WPFormsGeolocation\Admin\Entry;
use WPFormsGeolocation\Front\Fields;
use WPFormsGeolocation\Admin\Builder;
use WPFormsGeolocation\Admin\Settings\Settings;
use WPFormsGeolocation\Admin\Settings\Preview;
use WPFormsGeolocation\PlacesProviders\ProvidersFactory;
use WPFormsGeolocation\Tasks\EntryGeolocationUpdateTask;

/**
 * Class Plugin.
 *
 * @since 2.0.0
 */
final class Plugin {

	/**
	 * Plugin constructor.
	 *
	 * @since 2.0.0
	 */
	private function __construct() {
	}

	/**
	 * Get a single instance of the addon.
	 *
	 * @since 2.0.0
	 *
	 * @return Plugin
	 */
	public static function get_instance() {

		static $instance = null;

		if (
			$instance === null ||
			! $instance instanceof self
		) {
			$instance = new self();

			$instance->run();
		}

		return $instance;
	}

	/**
	 * Run plugin.
	 *
	 * @since 2.0.0
	 */
	private function run() {

		$settings          = new Settings();
		$providers_factory = new ProvidersFactory( $settings );
		$map               = new Map();
		$retrieve_geo_data = new RetrieveGeoData();
		$fields            = new Fields( $providers_factory, $map );
		$smart_tags        = new SmartTags();

		$settings->hooks();
		$smart_tags->hooks();
		$retrieve_geo_data->hooks();
		( new SmartTags() )->hooks();
		( new Entry( $retrieve_geo_data, $smart_tags ) )->hooks();
		( new EntryGeolocationUpdateTask( $retrieve_geo_data ) )->hooks();
		( new Integrations() )->hooks();
		( new Builder( $providers_factory, $map ) )->hooks();
		$fields->hooks();
		( new Preview( $providers_factory, $fields, $settings, $map ) )->hooks();
	}
}
