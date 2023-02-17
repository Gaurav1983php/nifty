<?php
/**
 * Outputs an error message at Keywords > Generate Phone Area Codes
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo esc_html( $this->base->plugin->displayName ); ?>

		<span>
			<?php esc_html_e( 'Generate Phone Area Codes', 'page-generator-pro' ); ?>
		</span>
	</h1>

	<?php
	// Button Links.
	require_once 'keywords-links.php';

	// Output Success and/or Error Notices, if any exist.
	$this->base->get_class( 'notices' )->output_notices();
	?>
</div>
