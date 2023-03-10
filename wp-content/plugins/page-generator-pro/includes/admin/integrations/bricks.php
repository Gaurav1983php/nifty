<?php
/**
 * Bricks Visual Website Builder Integration Class
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Registers Bricks Visual Website Builder as a Plugin integration:
 * - Copy / don't copy metadata to generated Pages, depending on if the integration is active
 * - Decode/encode Page Builder metadata when generating Pages
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.8.0
 */
class Page_Generator_Pro_Bricks extends Page_Generator_Pro_Integration {

	/**
	 * Holds the base object.
	 *
	 * @since   3.8.0
	 *
	 * @var     object
	 */
	public $base;

	/**
	 * Constructor
	 *
	 * @since   3.8.0
	 *
	 * @param   object $base    Base Plugin Class.
	 */
	public function __construct( $base ) {

		// Store base class.
		$this->base = $base;

		// Set Theme Name.
		$this->theme_name = 'Bricks';

		// Set Meta Keys used by this Theme.
		$this->meta_keys = array(
			'/^_bricks_(.*)/i',
		);

		// Remove Page Builder data from Group Settings if overwriting content is disabled, and an existing generated page already exists.
		add_filter( 'page_generator_pro_generate_remove_content_from_settings_ignored_keys', array( $this, 'remove_post_meta_from_content_group' ), 10, 2 );

		// Remove Plugin data from Group Settings if Plugin isn't active on Generation.
		add_filter( 'page_generator_pro_groups_get_settings_remove_orphaned_settings', array( $this, 'remove_orphaned_settings' ) );

	}

	/**
	 * Removes orphaned metadata in the Group Settings during Generation,
	 * if Bricks is not active.
	 *
	 * @since   3.8.0
	 *
	 * @param   array $settings   Group Settings.
	 * @return  array               Group Settings
	 */
	public function remove_orphaned_settings( $settings ) {

		// Don't remove settings if the Theme is active.
		if ( $this->is_theme_active() ) {
			return $settings;
		}

		// Remove Meta Keys from the Group Settings during Generation.
		return $this->remove_orphaned_settings_metadata( $settings, $this->meta_keys );

	}

}
