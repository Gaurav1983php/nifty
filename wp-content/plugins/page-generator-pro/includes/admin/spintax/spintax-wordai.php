<?php
/**
 * WordAI spintax integration class
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Registers WordAI as a spintax integration.
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.9.1
 */
class Page_Generator_Pro_Spintax_WordAI {

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
		add_filter( 'page_generator_pro_spintax_add_spintax_wordai', array( $this, 'add_spintax' ), 10, 2 );

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

		$providers['wordai'] = __( 'WordAI', 'page-generator-pro' );
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
		$settings_fields['wordai'] = array(
			'wordai_email_address'    => array(
				'label'         => __( 'Email Address', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_email_address' ),
				'description'   => sprintf(
					'%s %s %s',
					esc_html__( 'The email address you use when logging into WordAI.', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'wordai' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account', 'page-generator-pro' ) . '</a>',
					esc_html__( 'if you don\'t have one.', 'page-generator-pro' )
				),
			),
			'wordai_api_key'          => array(
				'label'         => __( 'API Key', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_api_key' ),
				'description'   => sprintf(
					'%s %s %s %s',
					esc_html__( 'Enter your WordAI API key,', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'wordai' )->get_account_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'which can be found here.', 'page-generator-pro' ) . '</a>',
					esc_html__( 'Don\'t have an account?', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'wordai' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account.', 'page-generator-pro' ) . '</a>'
				),
			),
			'wordai_confidence_level' => array(
				'label'         => __( 'Confidence Level', 'page-generator-pro' ),
				'type'          => 'select',
				'values'        => $this->base->get_class( 'wordai' )->get_confidence_levels(),
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_confidence_level' ),
				'description'   => __( 'More Conservative will result in more readable text, but less spun.', 'page-generator-pro' ),
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
			'email_address' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_email_address', false ),
			'api_key'       => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_api_key', false ),
		);

		// Build API compatible parameters.
		$params = array(
			'rewrite_num' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_rewrite_num', 1 ),
			'uniqueness'  => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_confidence_level', 1 ),
		);

		// Setup API.
		$this->base->get_class( 'wordai' )->set_credentials(
			$credentials['email_address'],
			$credentials['api_key']
		);

		// Add spintax to content.
		return $this->base->get_class( 'wordai' )->text_with_spintax( $content, $params, $protected_words );

	}

}
