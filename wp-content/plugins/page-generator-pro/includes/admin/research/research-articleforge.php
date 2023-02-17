<?php
/**
 * ArticleForge research integration Class
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Registers ArticleForge as a research integration.
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.9.1
 */
class Page_Generator_Pro_Research_ArticleForge {

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
		add_filter( 'page_generator_pro_research_research_articleforge', array( $this, 'research' ) );
		add_filter( 'page_generator_pro_research_get_status_articleforge', array( $this, 'get_status' ) );

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

		$providers['articleforge'] = __( 'ArticleForge', 'page-generator-pro' );
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
		$settings_fields['articleforge'] = array(
			'articleforge_api_key'        => array(
				'label'         => __( 'API Key', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'articleforge_api_key' ),
				'description'   => sprintf(
					'%s %s %s %s',
					esc_html__( 'Enter your ArticleForge API key', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'articleforge' )->get_account_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'which can be found here', 'page-generator-pro' ) . '</a>',
					esc_html__( 'Don\'t have an account?', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'articleforge' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account', 'page-generator-pro' ) . '</a>'
				),
			),
			'articleforge_length'         => array(
				'label'         => __( 'Content Length', 'page-generator-pro' ),
				'type'          => 'select',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'articleforge_length', 'short' ),
				'values'        => $this->base->get_class( 'articleforge' )->get_lengths(),
				'description'   => __( 'The length of content to produce.', 'page-generator-pro' ),
			),
			'articleforge_include_titles' => array(
				'label'         => __( 'Include Titles?', 'page-generator-pro' ),
				'type'          => 'toggle',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'articleforge_include_titles', 0 ),
				'description'   => __( 'Whether to include headings and subheadings in the content.', 'page-generator-pro' ),
			),
			'articleforge_include_images' => array(
				'label'         => __( 'Include Images?', 'page-generator-pro' ),
				'type'          => 'number',
				'min'           => 0,
				'max'           => 1,
				'step'          => '0.01',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'articleforge_include_images', 0 ),
				'description'   => __( 'The probability of images being included in the content, between 0.00 and 1.00.', 'page-generator-pro' ),
			),
			'articleforge_include_videos' => array(
				'label'         => __( 'Include Videos?', 'page-generator-pro' ),
				'type'          => 'number',
				'min'           => 0,
				'max'           => 1,
				'step'          => '0.01',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'articleforge_include_videos', 0 ),
				'description'   => __( 'The probability of videos being included in the content, between 0.00 and 1.00.', 'page-generator-pro' ),
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
		$api_key = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'articleforge_api_key', false );

		// Bail if no API Key defined.
		if ( ! $api_key ) {
			return new WP_Error(
				'page_generator_pro_research_process_articleforge_error',
				__( 'No API key was configured in the Plugin\'s Settings', 'page-generator-pro' )
			);
		}

		// Set API Key.
		$this->base->get_class( 'articleforge' )->set_api_key( $api_key );

		// Send request.
		$result = $this->base->get_class( 'articleforge' )->initiate_article(
			$topic,
			$this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'articleforge_length', 'short' ),
			$this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'articleforge_include_titles', 0 ),
			$this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'articleforge_include_images', 0 ),
			$this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'articleforge_include_videos', 0 )
		);

		// If an error occured, bail.
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Return data of request ID and estimated time needed.
		return array(
			'id'      => $result->ref_key,
			'message' => __( 'Please wait whilst ArticleForge completes this process.', 'page-generator-pro' ),
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
		$api_key = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-research', 'articleforge_api_key', false );

		// Bail if no API Key defined.
		if ( ! $api_key ) {
			return new WP_Error(
				'page_generator_pro_research_get_status_articleforge_error',
				__( 'No API key was configured in the Plugin\'s Settings', 'page-generator-pro' )
			);
		}

		// Set API Key.
		$this->base->get_class( 'articleforge' )->set_api_key( $api_key );

		// Send request.
		$result = $this->base->get_class( 'articleforge' )->get_api_progress( $id );

		// If an error occured, bail.
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Define default status array.
		$status = array(
			'id'        => $id,
			'completed' => ( $result->api_status === 201 ? true : false ),
			'content'   => '',
			'message'   => '',
		);

		// If the research isn't completed, return.
		if ( ! $status['completed'] ) {
			$status['message'] = sprintf(
				/* translators: Percentage */
				__( '%s percent complete. Please wait whilst ArticleForge completes this process.', 'page-generator-pro' ),
				round( ( $result->progress * 100 ) )
			);
			return $status;
		}

		// If here, research is complete.
		// Get Article by Reference Key.
		$result = $this->base->get_class( 'articleforge' )->get_api_article_result( $id );

		// If an error occured, bail.
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Add content to status.
		$status['content'] = array( $result->article );
		$status['message'] = __( 'Research completed successfully.', 'page-generator-pro' );

		// Return.
		return $status;

	}

}
