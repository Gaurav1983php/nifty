<?php
/**
 * AI Writer research integration Class
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Registers AI Writer as a research integration.
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.9.1
 */
class Page_Generator_Pro_Research_AI_Writer {

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
		add_filter( 'page_generator_pro_research_research_ai_writer', array( $this, 'research' ) );
		add_filter( 'page_generator_pro_research_get_status_ai_writer', array( $this, 'get_status' ) );

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

		$providers['ai_writer'] = __( 'AI Writer', 'page-generator-pro' );
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
		$settings_fields['ai_writer'] = array(
			'ai_writer_api_key' => array(
				'label'         => __( 'API Key', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'ai_writer_api_key' ),
				'description'   => sprintf(
					'%s %s %s %s',
					esc_html__( 'Enter your AI Writer API key', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'ai_writer' )->get_account_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'which can be found here', 'page-generator-pro' ) . '</a>',
					esc_html__( 'Don\'t have an account?', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'ai_writer' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account', 'page-generator-pro' ) . '</a>'
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
		$api_key = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'ai_writer_api_key', false );

		// Bail if no API Key defined.
		if ( ! $api_key ) {
			return new WP_Error(
				'page_generator_pro_research_process_ai_writer_error',
				__( 'No API key was configured in the Plugin\'s Settings', 'page-generator-pro' )
			);
		}

		// Set API Key.
		$this->base->get_class( 'ai_writer' )->set_api_key( $api_key );

		// Send request.
		$result = $this->base->get_class( 'ai_writer' )->put_research_request( $topic );

		// If an error occured, bail.
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Return data of request ID and estimated time needed.
		return array(
			'id'             => $result->id,
			'estimated_time' => $result->estimated_time_needed,
			'message'        => sprintf(
				/* translators: Calculated human readable duration/time */
				__( 'Estimated time for completion is %s. Please wait whilst AI Writer completes this process.', 'page-generator-pro' ),
				human_readable_duration( gmdate( 'i:s', $result->estimated_time_needed ) )
			),
		);

	}

	/**
	 * Returns the status of a previously researched topic from AI Writer
	 *
	 * @since   3.9.1
	 *
	 * @param   string $id     ID.
	 * @return  mixed           WP_Error | string
	 */
	public function get_status( $id ) {

		// Get API key.
		$api_key = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'ai_writer_api_key', false );

		// Bail if no API Key defined.
		if ( ! $api_key ) {
			return new WP_Error(
				'page_generator_pro_research_get_status_ai_writer_error',
				__( 'No API key was configured in the Plugin\'s Settings', 'page-generator-pro' )
			);
		}

		// Set API Key.
		$this->base->get_class( 'ai_writer' )->set_api_key( $api_key );

		// Send request.
		$result = $this->base->get_class( 'ai_writer' )->get_research_result( $id );

		// If an error occured, bail.
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Define default status array.
		$status = array(
			'id'        => $result->request->id,
			'completed' => $result->request->done,
			'content'   => '',
			'message'   => '',
		);

		// If the research isn't completed, return.
		if ( ! $status['completed'] ) {
			$status['message'] = sprintf(
				/* translators: Calculated human readable duration/time */
				__( 'Estimated time for completion is %s. Please wait whilst AI Writer completes this process.', 'page-generator-pro' ),
				human_readable_duration( gmdate( 'i:s', $result->request->estimated_time_needed ) )
			);
			return $status;
		}

		// Build paragraphs.
		$paragraphs = array();
		foreach ( $result->result->article as $index => $paragraph ) {
			$paragraphs[] = $paragraph->paragraph_text;
		}
		$status['content'] = $paragraphs;
		$status['message'] = __( 'Research completed successfully.', 'page-generator-pro' );

		// Return.
		return $status;

	}

}
