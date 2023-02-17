<?php
/**
 * OpenAI research integration Class
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Registers OpenAI as a research integration.
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.9.2
 */
class Page_Generator_Pro_Research_OpenAI {

	/**
	 * Holds the base object.
	 *
	 * @since   3.9.2
	 *
	 * @var     object
	 */
	public $base;

	/**
	 * Constructor.
	 *
	 * @since   3.9.2
	 *
	 * @param   object $base    Base Plugin Class.
	 */
	public function __construct( $base ) {

		// Store base class.
		$this->base = $base;

		add_filter( 'page_generator_pro_research_get_providers', array( $this, 'register' ) );
		add_filter( 'page_generator_pro_research_get_providers_settings_fields', array( $this, 'get_settings_fields' ) );
		add_filter( 'page_generator_pro_research_research_openai', array( $this, 'research' ) );
		add_filter( 'page_generator_pro_research_get_status_openai', array( $this, 'get_status' ) );

	}

	/**
	 * Register this class as a research provider.
	 *
	 * @since   3.9.2
	 *
	 * @param   array $providers  Research Providers.
	 * @return  array               Research Providers
	 */
	public function register( $providers ) {

		$providers['openai'] = __( 'OpenAI', 'page-generator-pro' );
		return $providers;

	}

	/**
	 * Returns settings fields and their values to display at Settings > Spintax for this spintax provider.
	 *
	 * @since   3.9.2
	 *
	 * @param   array $settings_fields    Spintax Settings Fields.
	 * @return  array                       Spintax Settings Fields
	 */
	public function get_settings_fields( $settings_fields ) {

		// Define fields and their values.
		$settings_fields['openai'] = array(
			'openai_api_key' => array(
				'label'         => __( 'API Key', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'openai_api_key' ),
				'description'   => sprintf(
					'%s %s %s %s',
					esc_html__( 'Enter your OpenAI API key', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'openai' )->get_account_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'which can be found here', 'page-generator-pro' ) . '</a>',
					esc_html__( 'Don\'t have an account?', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'openai' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account', 'page-generator-pro' ) . '</a>'
				),
			),
		);

		return $settings_fields;

	}

	/**
	 * Sends the topic to OpenAI's research endpoint, for OpenAI to build content
	 * and return it later on asynchronously
	 *
	 * @since   3.9.2
	 *
	 * @param   string $topic            Topic.
	 * @return  mixed                     WP_Error | string
	 */
	public function research( $topic ) {

		// Get API key.
		$api_key = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'openai_api_key', false );

		// Bail if no API Key defined.
		if ( ! $api_key ) {
			return new WP_Error(
				'page_generator_pro_research_process_openai_error',
				__( 'No API key was configured in the Plugin\'s Settings', 'page-generator-pro' )
			);
		}

		// Set API Key.
		$this->base->get_class( 'openai' )->set_api_key( $api_key );

		// Send request.
		$result = $this->base->get_class( 'openai' )->create_completion( 'Write a blog post on ' . $topic );

		// If an error occured, bail.
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Convert text into array of paragraphs, removing blank entries.
		$paragraphs = explode( "\n", $result->choices[0]->text );
		foreach ( $paragraphs as $key => $paragraph ) {
			if ( empty( $paragraph ) ) {
				unset( $paragraphs[ $key ] );
			}
		}
		$paragraphs = array_values( $paragraphs );

		// Return data.
		return array(
			'id'        => $result->id,
			'completed' => true, // We get an immediate result, so return it.
			'content'   => $paragraphs,
			'message'   => __( 'Research completed successfully.', 'page-generator-pro' ),
		);

	}

}
