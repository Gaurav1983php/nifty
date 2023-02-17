<?php
/**
 * Outputs the Discussion metabox when adding/editing a Content Groups
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

?>
<div class="wpzinc-option">
	<div class="left">
		<label for="comments"><?php esc_html_e( 'Allow comments?', 'page-generator-pro' ); ?></label>
	</div>
	<div class="right">
		<input type="checkbox" id="comments" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[comments]" value="1"<?php checked( $this->settings['comments'], 1 ); ?> />

		<p class="description">
			<?php esc_html_e( 'If checked, a comments form will be displayed on every generated Page/Post.  It is your Theme\'s responsibility to honor this setting.', 'page-generator-pro' ); ?>
		</p>
	</div>
</div>
<div class="wpzinc-option">
	<div class="left">
		<label for="comments_generate"><?php esc_html_e( 'Generate Comments?', 'page-generator-pro' ); ?></label>
	</div>
	<div class="right">
		<input type="checkbox" id="comments_generate" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[comments_generate][enabled]" value="1" data-conditional="comments-generate" <?php checked( $this->settings['comments_generate']['enabled'], 1 ); ?> />

		<p class="description">
			<?php esc_html_e( 'If checked, options are displayed to generate comments with every generated Page/Post.', 'page-generator-pro' ); ?>
		</p>
	</div>
</div>

<div id="comments-generate">
	<div class="wpzinc-option">
		<div class="left">
			<label for="comments_generate_limit"><?php esc_html_e( 'No. Comments', 'page-generator-pro' ); ?></label>
		</div>
		<div class="right">
			<input type="number" id="comments_generate_limit" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[comments_generate][limit]" value="<?php echo esc_attr( $this->settings['comments_generate']['limit'] ); ?>" min="0" max="50" step="1" />

			<p class="description">
				<?php esc_html_e( 'The number of Comments to generate for each Page/Post generated. If zero or blank, a random number of Comments will be generated.', 'page-generator-pro' ); ?>
			</p>
		</div>
	</div>

	<div class="wpzinc-option">
		<div class="left">
			<label for="comments_generate_date_option"><?php esc_html_e( 'Date', 'page-generator-pro' ); ?></label>
		</div>
		<div class="right">
			<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>[comments_generate][date_option]" id="comments_generate_date_option" size="1" class="widefat">
				<?php
				if ( is_array( $date_options ) && count( $date_options ) > 0 ) {
					foreach ( $date_options as $date_option => $label ) {
						?>
						<option value="<?php echo esc_attr( $date_option ); ?>"<?php selected( $this->settings['comments_generate']['date_option'], $date_option ); ?>>
							<?php echo esc_attr( $label ); ?>
						</option>
						<?php
					}
				}
				?>
			</select>
		</div>
	</div>

	<div class="wpzinc-option specific">
		<div class="left">
			<label for="comments_generate_date_specific"><?php esc_html_e( 'Specific Date', 'page-generator-pro' ); ?></label>
		</div>
		<div class="right">
			<input type="date" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[comments_generate][date_specific]" id="comments_generate_date_specific" value="<?php echo esc_attr( $this->settings['comments_generate']['date_specific'] ); ?>" class="widefat" />

			<p class="description">
				<?php esc_html_e( 'Each generated comment will use this date as the comment date.', 'page-generator-pro' ); ?>
			</p>
		</div>
	</div>

	<div class="wpzinc-option random">
		<div class="left">
			<label for="comments_generate_date_min"><?php esc_html_e( 'Start', 'page-generator-pro' ); ?></label>
		</div>
		<div class="right">
			<input type="date" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[comments_generate][date_min]" id="comments_generate_date_min" value="<?php echo esc_attr( $this->settings['comments_generate']['date_min'] ); ?>" />
		</div>
	</div>
	<div class="wpzinc-option random">
		<div class="left">
			<label for="comments_generate_date_max"><?php esc_html_e( 'End', 'page-generator-pro' ); ?></label>
		</div>
		<div class="right">
			<input type="date" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[comments_generate][date_max]" id="comments_generate_date_max" value="<?php echo esc_attr( $this->settings['comments_generate']['date_max'] ); ?>" />

			<p class="description">
				<?php esc_html_e( 'Each generated comment will use a date and time between the above minimum and maximum dates.', 'page-generator-pro' ); ?>
			</p>
		</div>
	</div>

	<div class="wpzinc-option">
		<div class="left">
			<label for="comments_generate_firstname"><?php esc_html_e( 'First Name', 'page-generator-pro' ); ?></label>
		</div>
		<div class="right">
			<input type="text" id="comments_generate_firstname" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[comments_generate][firstname]" value="<?php echo esc_attr( $this->settings['comments_generate']['firstname'] ); ?>" class="widefat" />

			<p class="description">
				<?php esc_html_e( 'The Author\'s First Name for each Generated Comment. Supports Keywords and Spintax. We recommend using a Keyword comprising of all First Names, and using {keyword:random_different} to generate a random first name for each generated Comment.', 'page-generator-pro' ); ?>
			</p>
		</div>
	</div>

	<div class="wpzinc-option">
		<div class="left">
			<label for="comments_generate_firstname"><?php esc_html_e( 'Surname', 'page-generator-pro' ); ?></label>
		</div>
		<div class="right">
			<input type="text" id="comments_generate_surname" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[comments_generate][surname]" value="<?php echo esc_attr( $this->settings['comments_generate']['surname'] ); ?>" class="widefat" />

			<p class="description">
				<?php esc_html_e( 'The Author\'s Surname for each Generated Comment. Supports Keywords and Spintax. We recommend using a Keyword comprising of all Surnames, and using {keyword:random_different} to generate a random surname for each generated Comment.', 'page-generator-pro' ); ?>
			</p>
		</div>
	</div>

	<div class="wpzinc-option">
		<div class="left">
			<label for="comments_generate_comment"><?php esc_html_e( 'Comment', 'page-generator-pro' ); ?></label>
		</div>
		<div class="right">
			<textarea id="comments_generate_comment" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[comments_generate][comment]" class="widefat"><?php echo esc_attr( $this->settings['comments_generate']['comment'] ); ?></textarea>

			<p class="description">
				<?php esc_html_e( 'The Comment Text for each Generated Comment. Supports Keywords and Spintax.', 'page-generator-pro' ); ?>
			</p>
		</div>
	</div>
</div>
<div class="wpzinc-option">
	<div class="left">
		<label for="trackbacks"><?php esc_html_e( 'Allow track / pingbacks?', 'page-generator-pro' ); ?></label>
	</div>
	<div class="right">
		<input type="checkbox" id="trackbacks" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[trackbacks]" value="1"<?php checked( $this->settings['trackbacks'], 1 ); ?> />
	</div>
</div>
