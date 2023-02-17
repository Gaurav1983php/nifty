<?php

namespace WPFormsUserJourney;

/**
 * Class SmartTags.
 *
 * @since 1.0.3
 */
class SmartTags {

	/**
	 * Smart tag.
	 *
	 * @since 1.0.3
	 *
	 * @var string
	 */
	const SMART_TAG = 'entry_user_journey';

	/**
	 * Init.
	 *
	 * @since 1.0.3
	 */
	public function init() {

		$this->hooks();
	}

	/**
	 * Hooks.
	 *
	 * @since 1.0.3
	 */
	private function hooks() {

		add_filter( 'wpforms_email_message', [ $this, 'email_message' ], 10, 2 );
		add_filter( 'wpforms_frontend_confirmation_message', [ $this, 'confirmation_message' ], 10, 4 );
		add_filter( 'wpforms_smart_tags', [ $this, 'register_tag' ] );
	}

	/**
	 * Register the new {entry_user_journey} smart tag.
	 *
	 * @since 1.0.3
	 *
	 * @param array $tags List of tags.
	 *
	 * @return array $tags List of tags.
	 */
	public function register_tag( $tags ) {

		$tags[ self::SMART_TAG ] = esc_html__( 'Entry User Journey', 'wpforms-user-journey' );

		return $tags;
	}

	/**
	 * Process the {entry_user_journey} smart tag inside email messages.
	 *
	 * @since 1.0.3
	 *
	 * @param string $message Theme email message.
	 * @param object $email   WPForms_WP_Emails.
	 *
	 * @return string
	 */
	public function email_message( $message, $email ) {

		$entry = $this->get_entry_with_journey( $email->entry_id );

		if ( empty( $entry->user_journey ) ) {
			return $this->replace_smart_tag( $message, '' );
		}

		$journey_text = $email->get_content_type() === 'text/plain'
			? $this->plain_email_entry_journey( $entry )
			: $this->html_email_entry_journey( $entry, $email );

		return $this->replace_smart_tag( $message, $journey_text );
	}

	/**
	 * Process the {entry_user_journey} smart tag inside confirmation messages.
	 *
	 * @since 1.0.3
	 *
	 * @param string $confirmation_message Confirmation message.
	 * @param array  $form_data            Form data and settings.
	 * @param array  $fields               Sanitized field data.
	 * @param int    $entry_id             Entry ID.
	 *
	 * @return string
	 */
	public function confirmation_message( $confirmation_message, $form_data, $fields, $entry_id ) {

		$entry = $this->get_entry_with_journey( $entry_id );

		if ( empty( $entry->user_journey ) ) {
			return $this->replace_smart_tag( $confirmation_message, '' );
		}

		$output  = sprintf(
			'<h4>%s</h4>',
			esc_html__( 'Entry User Journey', 'wpforms-user-journey' )
		);
		$output .= wpforms_user_journey()->view->get_entry_journey_table( $entry, 'confirmation' );

		return $this->replace_smart_tag( $confirmation_message, $output );
	}

	/**
	 * Replace smart tags.
	 *
	 * @since 1.0.3
	 *
	 * @param string $content Content.
	 * @param string $value   Smart tag value.
	 *
	 * @return string
	 */
	private function replace_smart_tag( $content, $value ) {

		return str_replace( '{' . self::SMART_TAG . '}', $value, $content );
	}

	/**
	 * Get journey.
	 *
	 * @since 1.0.3
	 *
	 * @param int $entry_id Entry ID.
	 *
	 * @return object
	 */
	private function get_entry_with_journey( $entry_id ) {

		$entry = wpforms()->get( 'entry' )->get( $entry_id );

		if ( empty( $entry ) ) {
			return $entry;
		}

		$journey = wpforms_user_journey()->db->get_rows( [ 'entry_id' => $entry_id ] );

		if ( ! empty( $journey ) ) {
			$entry->user_journey = $journey;
		}

		return $entry;
	}

	/**
	 * Entry user journey for plain/text content type mail.
	 *
	 * @since 1.0.3
	 *
	 * @param object $entry Entry with journey data.
	 *
	 * @return string
	 */
	private function plain_email_entry_journey( $entry ) {

		$text  = '--- ' . esc_html__( 'Entry User Journey', 'wpforms-user-journey' ) . ' ---' . PHP_EOL;
		$text .= wpforms_user_journey()->view->get_entry_journey_plain_text( $entry );

		return $text . "\r\n\r\n";
	}

	/**
	 * Entry user journey for HTML content type mail.
	 *
	 * @since 1.0.3
	 *
	 * @param object $entry Entry with journey data.
	 * @param object $email WPForms_WP_Emails.
	 *
	 * @return string
	 */
	private function html_email_entry_journey( $entry, $email ) {

		ob_start();
		$email->get_template_part( 'field', $email->get_template(), true );

		$html  = ob_get_clean();
		$html  = str_replace( '{field_name}', esc_html__( 'Entry User Journey', 'wpforms-user-journey' ), $html );
		$value = wpforms_user_journey()->view->get_entry_journey_table( $entry, 'email' );

		return (string) str_replace( '{field_value}', $value, $html );
	}
}
