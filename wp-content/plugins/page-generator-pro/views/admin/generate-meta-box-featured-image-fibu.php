<?php
/**
 * Outputs the Featured Image metabox for the FIBU Plugin when adding/editing a Content Groups
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

?>
<div class="wpzinc-option featured_image fibu">
	<div class="left">
		<label for="featured_image_fibu_url"><?php esc_html_e( 'URL', 'page-generator-pro' ); ?></label>
	</div>
	<div class="right">
		<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_fibu_url]" id="featured_image_fibu_url" value="<?php echo esc_attr( $settings['featured_image_fibu_url'] ); ?>" class="widefat" />
	</div>

	<p class="description">
		<?php esc_html_e( 'Enter an image URL. This can be a dynamic image URL.', 'page-generator-pro' ); ?>
	</p>
</div>

<div class="wpzinc-option featured_image fibu">
	<div class="left">
		<label for="featured_image_fibu_alt"><?php esc_html_e( 'Alt Text', 'page-generator-pro' ); ?></label>
	</div>
	<div class="right">
		<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_fibu_alt]" id="featured_image_fibu_alt" value="<?php echo esc_attr( $settings['featured_image_fibu_alt'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. building', 'page-generator-pro' ); ?>" class="widefat" />
	</div>
	<p class="description">
		<?php esc_html_e( 'The alt text.', 'page-generator-pro' ); ?>
	</p>
</div>
