<?php
/**
 * OpenAI API class.
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Generate articles based on keywords using ai-writer.com
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.9.2
 */
class Page_Generator_Pro_OpenAI extends Page_Generator_Pro_API {

	/**
	 * Holds the base object.
	 *
	 * @since   3.9.2
	 *
	 * @var     object
	 */
	public $base;

	/**
	 * Holds the API endpoint
	 *
	 * @since   3.9.2
	 *
	 * @var     string
	 */
	public $api_endpoint = 'https://api.openai.com/v1';

	/**
	 * Holds the account URL where users can obtain their API key
	 *
	 * @since   3.9.2
	 *
	 * @var     string
	 */
	public $account_url = 'https://beta.openai.com/account/api-keys';

	/**
	 * Holds the referal URL to use for users wanting to sign up
	 * to the API service.
	 *
	 * @since   3.9.2
	 *
	 * @var     string
	 */
	public $referral_url = 'https://beta.openai.com/signup/';

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

	}


	/**
	 * Submits a new completion request for the given topic
	 *
	 * @since   3.9.2
	 *
	 * @param   string $topic          Topic.
	 * @return  mixed                   WP_Error | object
	 */
	public function create_completion( $topic ) {

		// Set Headers.
		$this->set_headers(
			array(
				'Authorization' => 'Bearer ' . $this->api_key,
				'Content-Type'  => 'application/json',
			)
		);

		// Calculate maximum tokens, which is 4000 minus the topic length.
		// One token = ~ 4 characters.
		$tokens = ( 4000 - ceil( strlen( $topic ) / 4 ) );

		return $this->response(
			$this->post(
				'completions',
				array(
					'model'       => 'text-davinci-002',
					'prompt'      => $topic,
					'max_tokens'  => $tokens,
					'temperature' => 0,
				)
			)
		);

	}

	/**
	 * Inspects the response from the API call, returning an error
	 * or data
	 *
	 * @since   3.9.2
	 *
	 * @param   mixed $response   Response (WP_Error | object).
	 * @return  mixed               WP_Error | object
	 */
	private function response( $response ) {

		// If the response is an error, return it.
		if ( is_wp_error( $response ) ) {
			return new WP_Error(
				'page_generator_pro_openai_error',
				sprintf(
					/* translators: Error message */
					__( 'OpenAI: %s', 'page-generator-pro' ),
					$response->get_error_message()
				)
			);
		}

		// If the response wasn't successful, bail.
		if ( isset( $response->success ) && ! $response->success ) {
			return new WP_Error(
				'page_generator_pro_openai_error',
				__( 'OpenAI: An error occured', 'page-generator-pro' )
			);
		}

		return $response;

	}

}
