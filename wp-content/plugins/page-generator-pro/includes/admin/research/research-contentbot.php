<?php
/**
 * ContentBot research integration Class
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Registers ContentBot as a research integration.
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.9.1
 */
class Page_Generator_Pro_Research_ContentBot {

	/**
	 * Holds the base object.
	 *
	 * @since   3.9.1
	 *
	 * @var     object
	 */
	public $base;

	/**
	 * Constructor.
	 *
	 * @since   3.9.1
	 *
	 * @param   object $base    Base Plugin Class.
	 */
	public function __construct( $base ) {

		// Store base class.
		$this->base = $base;

		add_filter( 'page_generator_pro_research_get_providers', array( $this, 'register' ) );
		add_filter( 'page_generator_pro_research_get_providers_settings_fields', array( $this, 'get_settings_fields' ) );
		add_filter( 'page_generator_pro_research_research_contentbot', array( $this, 'research' ) );
		add_filter( 'page_generator_pro_research_get_status_contentbot', array( $this, 'get_status' ) );

	}

	/**
	 * Register this class as a research provider.
	 *
	 * @since   3.9.1
	 *
	 * @param   array $providers  Research Providers.
	 * @return  array               Research Providers
	 */
	public function register( $providers ) {

		$providers['contentbot'] = __( 'ContentBot', 'page-generator-pro' );
		return $providers;

	}

	/**
	 * Returns settings fields and their values to display at Settings > Spintax for this spintax provider.
	 *
	 * @since   3.9.1
	 *
	 * @param   array $settings_fields    Spintax Settings Fields.
	 * @return  array                       Spintax Settings Fields
	 */
	public function get_settings_fields( $settings_fields ) {

		// Define fields and their values.
		$settings_fields['contentbot'] = array(
			'contentbot_api_key' => array(
				'label'         => __( 'API Key', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'contentbot_api_key' ),
				'description'   => sprintf(
					'%s %s %s %s',
					esc_html__( 'Enter your ContentBot API key', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'contentbot' )->get_account_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'which can be found here', 'page-generator-pro' ) . '</a>',
					esc_html__( 'Don\'t have an account?', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'contentbot' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account', 'page-generator-pro' ) . '</a>'
				),
			),
		);

		return $settings_fields;

	}

	/**
	 * Sends the topic to AI Writer's research endpoint, for AI Writer to build content
	 * and return it later on asynchronously
	 *
	 * @since   3.9.1
	 *
	 * @param   string $topic            Topic.
	 * @return  mixed                     WP_Error | string
	 */
	public function research( $topic ) {

		// Get API key.
		$api_key = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'contentbot_api_key', false );

		// Bail if no API Key defined.
		if ( ! $api_key ) {
			return new WP_Error(
				'page_generator_pro_research_process_contentbot_error',
				__( 'No API key was configured in the Plugin\'s Settings', 'page-generator-pro' )
			);
		}

		// Set API Key.
		$this->base->get_class( 'contentbot' )->set_api_key( $api_key );

		// Send request.
		$result = $this->base->get_class( 'contentbot' )->get_topic_content( $topic );

		// If an error occured, bail.
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Return data.
		return array(
			'id'        => 0, // ContentBot doesn't use an ID.
			'completed' => true, // We get an immediate result, so return it.
			'content'   => $result,
			'message'   => __( 'Research completed successfully.', 'page-generator-pro' ),
		);

	}

}
