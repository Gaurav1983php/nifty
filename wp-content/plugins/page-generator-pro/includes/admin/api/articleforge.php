<?php
/**
 * ArticleForge API class.
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

/**
 * Generate articles based on keywords using articleforge.com
 *
 * @package Page_Generator_Pro
 * @author  WP Zinc
 * @version 3.9.1
 */
class Page_Generator_Pro_ArticleForge extends Page_Generator_Pro_API {

	/**
	 * Holds the base object.
	 *
	 * @since   3.9.1
	 *
	 * @var     object
	 */
	public $base;

	/**
	 * Holds the API endpoint
	 *
	 * @since   3.9.1
	 *
	 * @var     string
	 */
	public $api_endpoint = 'https://af.articleforge.com/api';

	/**
	 * Holds the account URL where users can obtain their API key
	 *
	 * @since   3.9.1
	 *
	 * @var     string
	 */
	public $account_url = 'https://af.articleforge.com/api_info';

	/**
	 * Holds the referal URL to use for users wanting to sign up
	 * to the API service.
	 *
	 * @since   3.9.1
	 *
	 * @var     string
	 */
	public $referral_url = 'https://www.articleforge.com/?ref=c34417';

	/**
	 * Holds the flag determining if the request data should be encoded
	 * into a JSON string
	 *
	 * If false, data is encoded using http_build_query()
	 *
	 * @since   3.9.1
	 *
	 * @var     bool
	 */
	public $is_json_request = false;

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

	}

	/**
	 * Returns the valid values for content lengths,
	 * which can be used on API calls.
	 *
	 * @since   3.9.1
	 *
	 * @return  array   Content Lengths
	 */
	public function get_lengths() {

		$lengths = array(
			'very_short' => __( 'Very Short (approx. 50 words)', 'page-generator-pro' ),
			'short'      => __( 'Short (approx. 250 words)', 'page-generator-pro' ),
			'medium'     => __( 'Medium (approx. 500 words)', 'page-generator-pro' ),
			'long'       => __( 'Long (approx. 750 words)', 'page-generator-pro' ),
			'very_long'  => __( 'Very Long (approx. 1,500 words)', 'page-generator-pro' ),
		);

		return $lengths;

	}

	/**
	 * Returns usage information.
	 *
	 * @since   3.9.1
	 *
	 * @return  mixed               WP_Error | object
	 */
	public function check_usage() {

		return $this->response(
			$this->post(
				'check_usage',
				array(
					'key' => $this->api_key,
				)
			)
		);

	}

	/**
	 * Returns a list of all articles
	 *
	 * @since   3.9.1
	 *
	 * @param   int $limit      Limit.
	 * @return  mixed               WP_Error | object
	 */
	public function view_articles( $limit = 100 ) {

		return $this->response(
			$this->post(
				'view_articles',
				array(
					'key'   => $this->api_key,
					'limit' => $limit,
				)
			)
		);

	}

	/**
	 * Submits a new research request for the given topic
	 *
	 * @since   3.9.1
	 *
	 * @param   string $topic              Topic.
	 * @param   string $length             Length (very_short,short,medium,long,very_long. default: short).
	 * @param   bool   $include_titles     Include titles in researched content.
	 * @param   float  $include_images     Probability of including images in researched content (0.00 to 1.00).
	 * @param   float  $include_videos     Probability of including videos in researched content (0.00 to 1.00).
	 * @return  mixed                       WP_Error | object
	 */
	public function initiate_article( $topic, $length = 'short', $include_titles = false, $include_images = 0, $include_videos = 0 ) {

		return $this->response(
			$this->post(
				'initiate_article',
				array(
					'key'     => $this->api_key,
					'keyword' => $topic,
					'length'  => $length,
					'title'   => ( $include_titles ? '1' : '0' ),
					'image'   => $include_images,
					'video'   => $include_videos,
				)
			)
		);

	}

	/**
	 * Get progress of a research request
	 *
	 * @since   3.9.1
	 *
	 * @param   string $ref_key         Reference Key.
	 * @return  mixed                   WP_Error | object
	 */
	public function get_api_progress( $ref_key ) {

		return $this->response(
			$this->post(
				'get_api_progress',
				array(
					'key'     => $this->api_key,
					'ref_key' => $ref_key,
				)
			)
		);

	}

	/**
	 * Get the given article by Reference Key.
	 *
	 * @since   3.9.1
	 *
	 * @param   string $ref_key         Reference Key.
	 * @return  mixed                   WP_Error | object
	 */
	public function get_api_article_result( $ref_key ) {

		return $this->response(
			$this->post(
				'get_api_article_result',
				array(
					'key'     => $this->api_key,
					'ref_key' => $ref_key,
				)
			)
		);

	}

	/**
	 * Inspects the response from the API call, returning an error
	 * or data
	 *
	 * @since   3.9.1
	 *
	 * @param   mixed $response   Response (WP_Error | object).
	 * @return  mixed               WP_Error | object
	 */
	private function response( $response ) {

		// If the response is an error, return it.
		if ( is_wp_error( $response ) ) {
			return new WP_Error(
				'page_generator_pro_articleforge_error',
				sprintf(
					/* translators: Error message */
					__( 'ArticleForge: %s', 'page-generator-pro' ),
					$response->get_error_message()
				)
			);
		}

		// If the response's status key is 'Fail', bail.
		if ( isset( $response->status ) && $response->status === 'Fail' ) {
			// If an error message is supplied, return it.
			if ( isset( $response->error_message ) ) {
				return new WP_Error(
					'page_generator_pro_articleforge_error',
					sprintf(
						/* translators: API error message */
						__( 'ArticleForge: %s', 'page-generator-pro' ),
						$response->error_message
					)
				);
			}

			// Generic error occured.
			return new WP_Error(
				'page_generator_pro_articleforge_error',
				__( 'ArticleForge: An error occured', 'page-generator-pro' )
			);
		}

		return $response;

	}

}
