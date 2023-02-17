<?php
/**
 * ChimpRewriter spintax integration class
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Registers ChimpRewriter as a spintax integration.
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.9.1
 */
class Page_Generator_Pro_Spintax_ChimpRewriter {

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

		add_filter( 'page_generator_pro_spintax_get_providers', array( $this, 'register' ) );
		add_filter( 'page_generator_pro_spintax_get_providers_settings_fields', array( $this, 'get_settings_fields' ) );
		add_filter( 'page_generator_pro_spintax_add_spintax_chimprewriter', array( $this, 'add_spintax' ), 10, 2 );

	}

	/**
	 * Register this class as a spintax provider.
	 *
	 * @since   3.9.1
	 *
	 * @param   array $providers  Spintax Providers.
	 * @return  array               Spintax Providers
	 */
	public function register( $providers ) {

		$providers['chimprewriter'] = __( 'ChimpRewriter', 'page-generator-pro' );
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
		$settings_fields['chimprewriter'] = array(
			'chimprewriter_email_address'        => array(
				'label'         => __( 'Email Address', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_email_address' ),
				'description'   => sprintf(
					'%s %s %s',
					esc_html__( 'The email address you use when logging into ChimpRewriter.', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'chimprewriter' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account', 'page-generator-pro' ) . '</a>',
					esc_html__( 'if you don\'t have one.', 'page-generator-pro' )
				),
			),
			'chimprewriter_api_key'              => array(
				'label'         => __( 'API Key', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_api_key' ),
				'description'   => sprintf(
					'%s %s %s %s',
					esc_html__( 'Enter your ChimpRewriter API key,', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'chimprewriter' )->get_account_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'which can be found here.', 'page-generator-pro' ) . '</a>',
					esc_html__( 'Don\'t have an account?', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'chimprewriter' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account.', 'page-generator-pro' ) . '</a>'
				),
			),
			'chimprewriter_confidence_level'     => array(
				'label'         => __( 'Confidence Level', 'page-generator-pro' ),
				'type'          => 'select',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_confidence_level', 5 ),
				'values'        => $this->base->get_class( 'chimprewriter' )->get_confidence_levels(),
				'description'   => __( 'The higher the confidence level, the more readable the text and the less number of spins and variations produced.', 'page-generator-pro' ),
			),
			'chimprewriter_part_of_speech_level' => array(
				'label'         => __( 'Part of Speech Level', 'page-generator-pro' ),
				'type'          => 'select',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_part_of_speech_level', 5 ),
				'values'        => $this->base->get_class( 'chimprewriter' )->get_part_of_speech_levels(),
				'description'   => __( 'The higher the Part of Speech level, the more readable the text and the less number of spins and variations produced.', 'page-generator-pro' ),
			),
			'chimprewriter_verify_grammar'       => array(
				'label'         => __( 'Verify Grammar', 'page-generator-pro' ),
				'type'          => 'toggle',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_verify_grammar', 1 ),
				'description'   => __( 'If enabled, grammar is verified on the resulting text to produce a very high quality spin.', 'page-generator-pro' ),
			),
			'chimprewriter_nested_spintax'       => array(
				'label'         => __( 'Apply Nested Spintax', 'page-generator-pro' ),
				'type'          => 'toggle',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_nested_spintax', 1 ),
				'description'   => __( 'If enabled, ChimpRewriter will spin single words inside already spun phrases.', 'page-generator-pro' ),
			),
			'chimprewriter_change_phrase_sentence_structure' => array(
				'label'         => __( 'Change Phrase and Sentence Structure', 'page-generator-pro' ),
				'type'          => 'toggle',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_change_phrase_sentence_structure', 1 ),
				'description'   => __( 'If enabled, ChimpRewriter will change the entire structure of phrases and sentences.', 'page-generator-pro' ),
			),
		);

		return $settings_fields;

	}

	/**
	 * Adds spintax to the given content using the ChimpRewriter API
	 *
	 * @since   3.9.1
	 *
	 * @param   string $content            Content.
	 * @param   array  $protected_words    Protected Words.
	 * @return  mixed                       WP_Error | string
	 */
	public function add_spintax( $content, $protected_words ) {

		// Get credentials.
		$credentials = array(
			'email_address' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_email_address', false ),
			'api_key'       => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_api_key', false ),
		);

		// Get settings.
		$settings = array(
			'confidence_level'                 => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_confidence_level' ),
			'part_of_speech_level'             => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_part_of_speech_level' ),
			'verify_grammar'                   => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_verify_grammar' ),
			'nested_spintax'                   => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_nested_spintax' ),
			'change_phrase_sentence_structure' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_change_phrase_sentence_structure' ),
		);

		// Build API compatible parameters.
		$params = array(
			'quality'           => $settings['confidence_level'],
			'phrasequality'     => $settings['confidence_level'],
			'posmatch'          => $settings['part_of_speech_level'],
			'sentencerewrite'   => $settings['change_phrase_sentence_structure'],
			'grammarcheck'      => $settings['verify_grammar'],
			'reorderparagraphs' => $settings['change_phrase_sentence_structure'],
		);

		// Setup API.
		$this->base->get_class( 'chimprewriter' )->set_credentials(
			$credentials['email_address'],
			$credentials['api_key']
		);

		// Return result.
		return $this->base->get_class( 'chimprewriter' )->chimprewrite( $content, $params, $protected_words );

	}

}
