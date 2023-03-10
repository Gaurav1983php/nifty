<?php

namespace WPFormsGeolocation\Admin;

use WPFormsGeolocation\SmartTags;
use WPFormsGeolocation\RetrieveGeoData;
use WPFormsGeolocation\Tasks\EntryGeolocationUpdateTask;

/**
 * Class Entry.
 *
 * @since 2.0.0
 */
class Entry {

	/**
	 * Retrieve Geolocation Data.
	 *
	 * @since 2.0.0
	 *
	 * @var RetrieveGeoData
	 */
	private $retrieve_geo_data;

	/**
	 * Smart tags.
	 *
	 * @since 2.3.0
	 *
	 * @var SmartTags
	 */
	private $smart_tags;

	/**
	 * Entry constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param RetrieveGeoData $retrieve_geo_data Retrieve Geolocation Data.
	 * @param SmartTags       $smart_tags        Smart tags.
	 */
	public function __construct( RetrieveGeoData $retrieve_geo_data, SmartTags $smart_tags ) {

		$this->retrieve_geo_data = $retrieve_geo_data;
		$this->smart_tags        = $smart_tags;
	}

	/**
	 * Hooks.
	 *
	 * @since 2.0.0
	 */
	public function hooks() {

		add_action( 'wpforms_entry_details_init', [ $this, 'entry_details_init' ] );
		add_action( 'wpforms_entry_details_content', [ $this, 'entry_details_location' ], 20 );
		add_action( 'wpforms_process_entry_save', [ $this, 'entry_save_location' ], 20, 4 );
		add_filter( 'wpforms_helpers_templates_include_html_located', [ $this, 'templates' ], 10, 2 );
	}

	/**
	 * Maybe fetch geolocation data.
	 *
	 * If a form is using the location smart tag in an email notification, then
	 * we need to process the geolocation data before emails are sent. Otherwise
	 * geolocation data is processed on-demand when viewing an individual entry.
	 *
	 * @since 2.0.0
	 *
	 * @param array $fields    List of form fields.
	 * @param array $entry     User submitted data.
	 * @param int   $form_id   Form ID.
	 * @param array $form_data Form data and settings.
	 */
	public function entry_save_location( $fields, $entry, $form_id, $form_data ) {

		if ( empty( wpforms()->process->entry_id ) ) {
			return;
		}

		if ( ! wpforms_is_collecting_ip_allowed( $form_data ) ) {
			return;
		}

		if ( $this->has_entry_geolocation_smart_tag( $form_data ) ) {
			$loc = $this->retrieve_geo_data->get_location( wpforms_get_ip() );

			if ( $loc ) {
				wpforms()->get( 'entry_meta' )->add(
					[
						'entry_id' => absint( wpforms()->process->entry_id ),
						'form_id'  => absint( $form_id ),
						'type'     => 'location',
						'data'     => wp_json_encode( $loc ),
					],
					'entry_meta'
				);
			}

			return;
		}

		wpforms()
			->get( 'tasks' )
			->create( EntryGeolocationUpdateTask::ACTION )
			->async()
			->params(
				absint( wpforms()->process->entry_id ),
				absint( $form_id ),
				wpforms_get_ip()
			)
			->register();
	}

	/**
	 * Find the {entry_location} smart tag inside the form.
	 *
	 * @since 2.3.0
	 *
	 * @param array $form_data Form data and settings.
	 *
	 * @return bool
	 */
	private function has_entry_geolocation_smart_tag( $form_data ) {

		return $this->has_entry_geolocation_smart_tag_in_notifications( $form_data ) || $this->has_entry_geolocation_smart_tag_in_confirmations( $form_data );
	}

	/**
	 * Find the {entry_location} smart tag inside form notifications.
	 *
	 * @since 2.3.0
	 *
	 * @param array $form_data Form data and settings.
	 *
	 * @return bool
	 */
	private function has_entry_geolocation_smart_tag_in_notifications( $form_data ) {

		if ( ! empty( $form_data['settings']['notifications'] ) ) {
			foreach ( $form_data['settings']['notifications'] as $notification ) {
				if ( $this->smart_tags->has_smart_tag( $notification['message'] ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Find the {entry_location} smart tag inside form confirmations.
	 *
	 * @since 2.3.0
	 *
	 * @param array $form_data Form data and settings.
	 *
	 * @return bool
	 */
	private function has_entry_geolocation_smart_tag_in_confirmations( $form_data ) {

		if ( ! empty( $form_data['settings']['confirmations'] ) ) {
			foreach ( $form_data['settings']['confirmations'] as $confirmation ) {
				if ( $this->smart_tags->has_smart_tag( $confirmation['message'] ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Maybe process geolocation data when an individual entry is viewed.
	 *
	 * @since 2.0.0
	 *
	 * @param object $entries WPForms_Entries_Single.
	 */
	public function entry_details_init( $entries ) {

		$location = wpforms()->get( 'entry_meta' )->get_meta(
			[
				'entry_id' => $entries->entry->entry_id,
				'type'     => 'location',
				'number'   => 1,
			]
		);

		if ( empty( $location ) ) {
			$location = $this->retrieve_geo_data->get_location( $entries->entry->ip_address );

			if ( $location ) {
				$data = [
					'entry_id' => absint( $entries->entry->entry_id ),
					'form_id'  => absint( $entries->entry->form_id ),
					'type'     => 'location',
					'data'     => wp_json_encode( $location ),
				];

				wpforms()->get( 'entry_meta' )->add( $data, 'entry_meta' );
			}
		} else {
			$location = json_decode( $location[0]->data, true );
		}

		$entries->entry->entry_location = $location;
	}

	/**
	 * Change a template location.
	 *
	 * @since 1.0.0
	 *
	 * @param string $located  Template location.
	 * @param string $template Template.
	 *
	 * @return string
	 */
	public function templates( $located, $template ) {

		// Checking if `$template` is an absolute path and passed from this plugin.
		if (
			( 0 === strpos( $template, WPFORMS_GEOLOCATION_PATH ) ) &&
			is_readable( $template )
		) {
			return $template;
		}

		return $located;
	}

	/**
	 * Entry details location metabox, display the info and make it look fancy.
	 *
	 * @since 2.0.0
	 *
	 * @param object $entry Entry.
	 */
	public function entry_details_location( $entry ) {

		echo wpforms_render( //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			WPFORMS_GEOLOCATION_PATH . 'templates/metabox/entry-details-location',
			[
				'entry'   => $entry,
				'map_url' => $this->get_map_preview_url( $entry ),
			],
			true
		);
	}

	/**
	 * Get url for map preview.
	 *
	 * @since 2.0.0
	 *
	 * @param object $entry Entry.
	 *
	 * @return string
	 */
	private function get_map_preview_url( $entry ) {

		$entry->entry_location = array_map( 'sanitize_text_field', $entry->entry_location );
		$latitude              = ! empty( $entry->entry_location['latitude'] ) ? $entry->entry_location['latitude'] : '';
		$longitude             = ! empty( $entry->entry_location['longitude'] ) ? $entry->entry_location['longitude'] : '';

		if ( empty( $latitude ) || empty( $longitude ) ) {
			return '';
		}

		$latlong    = "$latitude, $longitude";
		$location   = '';
		$loc_city   = ! empty( $entry->entry_location['city'] ) ? $entry->entry_location['city'] : '';
		$loc_region = ! empty( $entry->entry_location['region'] ) ? $entry->entry_location['region'] : '';

		if ( ! empty( $loc_city ) && ! empty( $loc_region ) ) {
			$location = "$loc_city, $loc_region";
		}

		return add_query_arg(
			[
				'q'      => str_replace( ' ', '', $location ),
				'll'     => str_replace( ' ', '', $latlong ),
				'z'      => absint( apply_filters( 'wpforms_geolocation_map_zoom', 6, 'entry' ) ),
				'output' => 'embed',
			],
			'https://maps.google.com/maps'
		);
	}
}
