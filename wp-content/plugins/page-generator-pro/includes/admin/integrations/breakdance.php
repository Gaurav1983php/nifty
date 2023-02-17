<?php
/**
 * Breakdance Builder Integration Class
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Registers Breakdance Builder as a Plugin integration:
 * - Copy / don't copy metadata to generated Pages, depending on if the integration is active
 * - Encode/decode Page Builder data when generating Pages
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.9.5
 */
class Page_Generator_Pro_Breakdance extends Page_Generator_Pro_Integration {

	/**
	 * Holds the base object.
	 *
	 * @since   3.9.5
	 *
	 * @var     object
	 */
	public $base;

	/**
	 * Constructor
	 *
	 * @since   3.9.5
	 *
	 * @param   object $base    Base Plugin Class.
	 */
	public function __construct( $base ) {

		// Store base class.
		$this->base = $base;

		// Set Plugin.
		$this->plugin_folder_filename = array(
			'breakdance/plugin.php',
		);

		// Set Meta Keys used by this Plugin.
		$this->meta_keys = array(
			'/^breakdance_(.*)/i',
		);

		// Decode/encode JSON metadata when generating content.
		add_filter( 'page_generator_pro_groups_get_post_meta_breakdance_data', array( $this, 'decode_json_meta' ) );
		add_filter( 'page_generator_pro_generate_set_post_meta_breakdance_data', array( $this, 'encode_json_meta' ) );

		// Remove Page Builder data from Group Settings if overwriting content is disabled, and an existing generated page already exists.
		add_filter( 'page_generator_pro_generate_remove_content_from_settings_ignored_keys', array( $this, 'remove_post_meta_from_content_group' ), 10, 2 );

		// Remove Plugin data from Group Settings if Plugin isn't active on Generation.
		add_filter( 'page_generator_pro_groups_get_settings_remove_orphaned_settings', array( $this, 'remove_orphaned_settings' ) );

	}

	/**
	 * JSON decodes Breakdance Builder metadata into an array for a Content Group,
	 * so that the Generate Routine can iterate through it, replacing Keywords, Shortcodes etc.
	 *
	 * @since   3.9.5
	 *
	 * @param   string $value  Breakdance Builder JSON string.
	 * @return  string          Breakdance Builder Data
	 */
	public function decode_json_meta( $value ) {

		// JSON decode data.
		if ( is_string( $value ) && ! empty( $value ) ) {
			$value = json_decode( $value, true );
		}
		if ( empty( $value ) ) {
			$value = array();
		}

		return $value;

	}

	/**
	 * JSON encodes Breakdance Builder metadata into a string immediately before it's
	 * copied to the Generated Page.
	 *
	 * @since   3.9.5
	 *
	 * @param   array $value   Breakdance Builder Data.
	 * @return  string          Breakdance Builder JSON string
	 */
	public function encode_json_meta( $value ) {

		// Bail if the value has already been JSON encoded.
		if ( is_string( $value ) ) {
			return $value;
		}

		// Encode with slashes, just how Breakdance does in encode_before_writing_to_wp().
		return wp_slash( wp_json_encode( $value ) );

	}

	/**
	 * Removes orphaned Breakdance metadata in the Group Settings during Generation,
	 * if Breakdance is not active
	 *
	 * @since   3.9.5
	 *
	 * @param   array $settings   Group Settings.
	 * @return  array               Group Settings
	 */
	public function remove_orphaned_settings( $settings ) {

		// Don't remove settings if the Plugin is active.
		if ( $this->is_active() ) {
			return $settings;
		}

		// Remove Breakdance Meta Keys from the Group Settings during Generation.
		return $this->remove_orphaned_settings_metadata( $settings, $this->meta_keys );

	}

}
