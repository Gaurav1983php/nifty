<?php

namespace WPFormsGeolocation;

/**
 * Class SmartTags.
 *
 * @since 2.0.0
 */
class SmartTags {

	/**
	 * Hooks.
	 *
	 * @since 2.0.0
	 */
	public function hooks() {

		add_filter( 'wpforms_email_message', [ $this, 'email_message' ], 10, 2 );
		add_filter( 'wpforms_frontend_confirmation_message', [ $this, 'confirmation_message' ], 10, 4 );
		add_filter( 'wpforms_smart_tags', [ $this, 'register_tag' ] );
	}

	/**
	 * Register the new {entry_geolocation} smart tag.
	 *
	 * @since 2.0.0
	 *
	 * @param array $tags List of tags.
	 *
	 * @return array $tags List of tags.
	 */
	public function register_tag( $tags ) {

		$tags['entry_geolocation'] = esc_html__( 'Entry Geolocation', 'wpforms-geolocation' );

		return $tags;
	}

	/**
	 * Process the {entry_geolocation} smart tag inside email messages.
	 *
	 * @since 2.0.0
	 * @deprecated 2.3.0
	 *
	 * @param string $message Theme email message.
	 * @param object $email   WPForms_WP_Emails.
	 *
	 * @return string
	 */
	public function entry_location( $message, $email ) {

		_deprecated_function( __METHOD__, '2.3.0 of the WPForms Geolocation addon', __CLASS__ . '::email_message()' );

		return $this->email_message( $message, $email );
	}

	/**
	 * Process the {entry_geolocation} smart tag inside email messages.
	 *
	 * @since 2.3.0
	 *
	 * @param string $message Theme email message.
	 * @param object $email   WPForms_WP_Emails.
	 *
	 * @return string
	 */
	public function email_message( $message, $email ) {

		$location = $this->get_location( $email->entry_id );

		if ( empty( $location ) ) {
			return $this->replace_smart_tag( $message, '' );
		}

		$geo = $email->get_content_type() === 'text/plain'
			? $this->plain_entry_location( $location )
			: $this->html_entry_location( $location, $email );

		return $this->replace_smart_tag( $message, $geo );
	}

	/**
	 * Process the {entry_geolocation} smart tag inside confirmation messages.
	 *
	 * @since 2.3.0
	 *
	 * @param string $confirmation_message Confirmation message.
	 * @param array  $form_data            Form data and settings.
	 * @param array  $fields               Sanitized field data.
	 * @param int    $entry_id             Entry ID.
	 *
	 * @return string
	 */
	public function confirmation_message( $confirmation_message, $form_data, $fields, $entry_id ) {

		$location = $this->get_location( $entry_id );

		if ( empty( $location ) ) {
			return $this->replace_smart_tag( $confirmation_message, '' );
		}

		return $this->replace_smart_tag( $confirmation_message, $this->html_entry_location_value( $location ) );
	}

	/**
	 * Replace smart tags.
	 *
	 * @since 2.3.0
	 *
	 * @param string $content Content.
	 * @param string $value   Smart tag value.
	 *
	 * @return string
	 */
	private function replace_smart_tag( $content, $value ) {

		if ( ! $this->has_smart_tag( $content ) ) {
			return $content;
		}

		return str_replace( '{entry_geolocation}', $value, $content );
	}

	/**
	 * Determine whether the content contains the {entry_geolocation} tag.
	 *
	 * @since 2.3.0
	 *
	 * @param string $content Content.
	 *
	 * @return bool
	 */
	public function has_smart_tag( $content ) {

		return strpos( $content, '{entry_geolocation}' ) !== false;
	}

	/**
	 * Get location.
	 *
	 * @since 2.3.0
	 *
	 * @param int $entry_id Entry ID.
	 *
	 * @return array
	 */
	private function get_location( $entry_id ) {

		$location = wpforms()->get( 'entry_meta' )->get_meta(
			[
				'entry_id' => $entry_id,
				'type'     => 'location',
				'number'   => 1,
			]
		);

		if ( empty( $location[0] ) || ! property_exists( $location[0], 'data' ) ) {
			return [];
		}

		return json_decode( $location[0]->data, true );
	}

	/**
	 * Entry geolocation for plain/text content type mail.
	 *
	 * @since 2.0.0
	 *
	 * @param array $location Location information.
	 *
	 * @return string
	 */
	private function plain_entry_location( $location ) {

		$geo = '--- ' . esc_html__( 'Entry Geolocation', 'wpforms-geolocation' ) . " ---\r\n";

		$geo .= $location['city'] . ', ' . $location['region'] . ', ' . $location['country'] . "\r\n";

		return $geo . $location['latitude'] . ', ' . $location['longitude'] . "\r\n\r\n";
	}

	/**
	 * Entry geolocation for html content type mail.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $location Location information.
	 * @param object $email    WPForms_WP_Emails.
	 *
	 * @return string
	 */
	private function html_entry_location( $location, $email ) {

		ob_start();
		$email->get_template_part( 'field', $email->get_template(), true );

		$geo   = ob_get_clean();
		$geo   = str_replace( '{field_name}', esc_html__( 'Entry Geolocation', 'wpforms-geolocation' ), $geo );
		$value = $this->html_entry_location_value( $location );

		return (string) str_replace( '{field_value}', $value, $geo );
	}

	/**
	 * Get entry location HTML value.
	 *
	 * @since 2.3.0
	 *
	 * @param array $location Location data.
	 *
	 * @return string
	 */
	private function html_entry_location_value( $location ) {

		$value = implode(
			', ',
			array_filter(
				[
					! empty( $location['city'] ) ? $location['city'] : '',
					! empty( $location['region'] ) ? $location['region'] : '',
					! empty( $location['country'] ) ? $location['country'] : '',
				]
			)
		);

		if ( ! empty( $location['latitude'] ) && ! empty( $location['longitude'] ) ) {
			$value .= '<br>' . $location['latitude'] . ', ' . $location['longitude'];
		}

		return $value;
	}
}
