<?php
/**
 * SpinnerChief spintax integration class
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Registers SpinnerChief as a spintax integration.
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.9.1
 */
class Page_Generator_Pro_Spintax_SpinnerChief {

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
		add_filter( 'page_generator_pro_spintax_add_spintax_spinnerchief', array( $this, 'add_spintax' ), 10, 2 );

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

		$providers['spinnerchief'] = __( 'SpinnerChief', 'page-generator-pro' );
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
		$settings_fields['spinnerchief'] = array(
			'spinnerchief_username' => array(
				'label'         => __( 'Username', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spinnerchief_username' ),
				'description'   => sprintf(
					'%s %s %s',
					esc_html__( 'The username you use when logging into SpinnerChief.', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'spinnerchief' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account', 'page-generator-pro' ) . '</a>',
					esc_html__( 'if you don\'t have one.', 'page-generator-pro' )
				),
			),
			'spinnerchief_password' => array(
				'label'         => __( 'Password', 'page-generator-pro' ),
				'type'          => 'text',
				'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spinnerchief_password' ),
				'description'   => sprintf(
					'%s %s %s',
					esc_html__( 'The password you use when logging into SpinnerChief.', 'page-generator-pro' ),
					'<a href="' . esc_attr( $this->base->get_class( 'spinnerchief' )->get_registration_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'Register an account', 'page-generator-pro' ) . '</a>',
					esc_html__( 'if you don\'t have one.', 'page-generator-pro' )
				),
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
			'username' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spinnerchief_username', false ),
			'password' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spinnerchief_password', false ),
		);

		// Setup API.
		$this->base->get_class( 'spinnerchief' )->set_credentials(
			$credentials['username'],
			$credentials['password']
		);

		// Return result.
		return $this->base->get_class( 'spinnerchief' )->text_with_spintax( $content, array(), $protected_words );

	}

}
