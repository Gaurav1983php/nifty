<?php
/**
 * Spin Rewriter spintax integration class
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Registers Spin Rewriter as a spintax integration.
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.9.1
 */
class Page_Generator_Pro_Spintax_Spin_Rewriter {

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
		add_filter( 'page_generator_pro_spintax_add_spintax_spin_rewriter', array( $this, 'add_spintax' ), 10, 2 );

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

		$providers['spin_rewriter'] = __( 'Spin Rewriter', 'page-generator-pro' );
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
		$settings_fields['spin_rewriter'] = array(
			'spin_rewriter_email_address'       => array(
				'label'         => __( 'Email Address', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_email_address' ),
				'description'   => sprintf(
					'%s %s %s',
					esc_html__( 'The email address you use when logging into Spin Rewriter.', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'spin_rewriter' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account', 'page-generator-pro' ) . '</a>',
					esc_html__( 'if you don\'t have one.', 'page-generator-pro' )
				),
			),
			'spin_rewriter_api_key'             => array(
				'label'         => __( 'API Key', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_api_key' ),
				'description'   => sprintf(
					'%s %s %s %s',
					esc_html__( 'Enter your Spin Rewriter API key,', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'spin_rewriter' )->get_account_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'which can be found here.', 'page-generator-pro' ) . '</a>',
					esc_html__( 'Don\'t have an account?', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'spin_rewriter' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account.', 'page-generator-pro' ) . '</a>'
				),
			),
			'spin_rewriter_confidence_level'    => array(
				'label'         => __( 'Confidence Level', 'page-generator-pro' ),
				'type'          => 'select',
				'values'        => $this->base->get_class( 'spin_rewriter' )->get_confidence_levels(),
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_confidence_level' ),
				'description'   => __( 'The higher the confidence level, the more readable the text and the less number of spins and variations produced.', 'page-generator-pro' ),
			),
			'spin_rewriter_nested_spintax'      => array(
				'label'         => __( 'Apply Nested Spintax', 'page-generator-pro' ),
				'type'          => 'toggle',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_nested_spintax' ),
				'description'   => __( 'If enabled, Spin Rewriter will spin single words inside already spun phrases.', 'page-generator-pro' ),
			),
			'spin_rewriter_auto_sentences'      => array(
				'label'         => __( 'Spin Sentences', 'page-generator-pro' ),
				'type'          => 'toggle',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_sentences' ),
				'description'   => __( 'If enabled, Spin Rewriter will spin sentences.', 'page-generator-pro' ),
			),
			'spin_rewriter_auto_paragraphs'     => array(
				'label'         => __( 'Spin Paragraphs', 'page-generator-pro' ),
				'type'          => 'toggle',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_paragraphs' ),
				'description'   => __( 'If enabled, Spin Rewriter will spin paragraphs.', 'page-generator-pro' ),
			),
			'spin_rewriter_auto_new_paragraphs' => array(
				'label'         => __( 'Add Paragraphs', 'page-generator-pro' ),
				'type'          => 'toggle',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_new_paragraphs' ),
				'description'   => __( 'If enabled, Spin Rewriter may add additional paragraphs.', 'page-generator-pro' ),
			),
			'spin_rewriter_auto_sentence_trees' => array(
				'label'         => __( 'Change Phrase and Sentence Structure', 'page-generator-pro' ),
				'type'          => 'toggle',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_sentence_trees' ),
				'description'   => __( 'If enabled, Spin Rewriter may change the entire structure of phrases and sentences.', 'page-generator-pro' ),
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
			'email_address' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_email_address', false ),
			'api_key'       => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_api_key', false ),
		);

		// Build API compatible parameters.
		$params = array(
			'auto_protected_terms' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'skip_capitalized_words', 1 ),
			'confidence_level'     => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_confidence_level', 'low' ),
			'nested_spintax'       => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_nested_spintax', 1 ),
			'auto_sentences'       => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_sentences', 1 ),
			'auto_paragraphs'      => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_paragraphs', 1 ),
			'auto_new_paragraphs'  => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_new_paragraphs', 1 ),
			'auto_sentence_trees'  => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_sentence_trees', 1 ),
		);

		// Setup API.
		$this->base->get_class( 'spin_rewriter' )->set_credentials(
			$credentials['email_address'],
			$credentials['api_key']
		);

		// Return result.
		return $this->base->get_class( 'spin_rewriter' )->text_with_spintax( $content, $params, $protected_words );

	}

}
