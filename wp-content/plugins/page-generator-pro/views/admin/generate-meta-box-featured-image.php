<?php
/**
 * Outputs the Featured Image metabox when adding/editing a Content Groups
 *
 * @package Page_Generator_Pro
 * @author WP Zinc
 */

?>
<div class="wpzinc-option">
	<div class="full">
		<label for="featured_image_source"><?php esc_html_e( 'Image Source', 'page-generator-pro' ); ?></label>
	</div>
	<div class="full">
		<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_source]" id="featured_image_source" size="1" class="widefat">
			<?php
			foreach ( $featured_image_sources as $featured_image_source => $label ) {
				?>
				<option value="<?php echo esc_attr( $featured_image_source ); ?>"<?php selected( $this->settings['featured_image_source'], $featured_image_source ); ?>><?php echo esc_attr( $label ); ?></option>
				<?php
			}
			?>
		</select>
	</div>
</div>

<div class="wpzinc-vertical-tabbed-ui no-border featured_image id url creative_commons pexels pixabay wikipedia">
	<!-- Tabs -->
	<ul class="wpzinc-nav-tabs wpzinc-js-tabs" data-panels-container="#featured-image-container" data-panel=".featured-image-panel" data-active="wpzinc-nav-tab-vertical-active">
		<li class="wpzinc-nav-tab link">
			<a href="#featured-image-search-parameters" class="wpzinc-nav-tab-vertical-active">
				<?php esc_html_e( 'Search Parameters', 'page-generator-pro' ); ?>
			</a>
		</li>
		<li class="wpzinc-nav-tab tag">
			<a href="#featured-image-output">
				<?php esc_html_e( 'Output', 'page-generator-pro' ); ?>
			</a>
		</li>
		<li class="wpzinc-nav-tab aperture">
			<a href="#featured-image-exif">
				<?php esc_html_e( 'EXIF', 'page-generator-pro' ); ?>
			</a>
		</li>
	</ul>

	<!-- Content -->
	<div id="featured-image-container" class="wpzinc-nav-tabs-content no-padding">
		<!-- Search Parameters -->
		<div id="featured-image-search-parameters" class="featured-image-panel">
			<div class="postbox">
				<header>
					<h3><?php esc_html_e( 'Search Parameters', 'page-generator-pro' ); ?></h3>
					<p class="description">
						<?php esc_html_e( 'Defines search query parameters to fetch an image.', 'page-generator-pro' ); ?>
					</p>
				</header>

				<!-- Media Library Image Options -->
				<div class="wpzinc-option featured_image id">
					<div class="left">
						<label for="featured_image_media_library_title"><?php esc_html_e( 'Title', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_media_library_title]" id="featured_image_media_library_title" value="<?php echo esc_attr( $this->settings['featured_image_media_library_title'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. building', 'page-generator-pro' ); ?>" class="widefat" />
					</div>

					<p class="description">
						<?php esc_html_e( 'Fetch an image at random with a partial or full match to the given Title.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image id">
					<div class="left">
						<label for="featured_image_media_library_caption"><?php esc_html_e( 'Caption', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_media_library_caption]" id="featured_image_media_library_caption" value="<?php echo esc_attr( $this->settings['featured_image_media_library_caption'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. building', 'page-generator-pro' ); ?>" class="widefat" />
					</div>
					<p class="description">
						<?php esc_html_e( 'Fetch an image at random with a partial or full match to the given Caption.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image id">
					<div class="left">
						<label for="featured_image_media_library_alt"><?php esc_html_e( 'Alt Text', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_media_library_alt]" id="featured_image_media_library_alt" value="<?php echo esc_attr( $this->settings['featured_image_media_library_alt'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. building', 'page-generator-pro' ); ?>" class="widefat" />
					</div>
					<p class="description">
						<?php esc_html_e( 'Fetch an image at random with a partial or full match to the given Alt Text.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image id">
					<div class="left">
						<label for="featured_image_media_library_description"><?php esc_html_e( 'Description', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_media_library_description]" id="featured_image_media_library_description" value="<?php echo esc_attr( $this->settings['featured_image_media_library_description'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. building', 'page-generator-pro' ); ?>" class="widefat" />
					</div>
					<p class="description">
						<?php esc_html_e( 'Fetch an image at random with a partial or full match to the given Description.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image id">
					<div class="left">
						<label for="featured_image_media_library_operator"><?php esc_html_e( 'Operator', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_media_library_operator]" id="featured_image_media_library_operator" size="1" class="widefat">
							<?php
							foreach ( $operators as $operator => $label ) {
								?>
								<option value="<?php echo esc_attr( $operator ); ?>"<?php selected( $this->settings['featured_image_media_library_operator'], $operator ); ?>><?php echo esc_attr( $label ); ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<p class="description">
						<?php esc_html_e( 'Determines whether images should match <b>all</b> or <b>any</b> of the Title, Caption, Alt Text and Descriptions specified above.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image id">
					<div class="left">
						<label for="featured_image_media_library_ids"><?php esc_html_e( 'Image IDs', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_media_library_ids]" id="featured_image_media_library_ids" value="<?php echo esc_attr( $this->settings['featured_image_media_library_ids'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. 100, 150, 200', 'page-generator-pro' ); ?>" class="widefat" />
					</div>
					<p class="description">
						<?php esc_html_e( 'Comma separated list of Media Library Image ID(s) to use.  If multiple image IDs are specified, one will be chosen at random for each generated Page', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image id">
					<div class="left">
						<label for="featured_image_media_library_min_id"><?php esc_html_e( 'Min. Image ID', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="number" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_media_library_min_id]" id="featured_image_media_library_min_id" value="<?php echo esc_attr( $this->settings['featured_image_media_library_min_id'] ); ?>" min="0" max="999999999" step="1" placeholder="<?php esc_attr_e( 'e.g. 100', 'page-generator-pro' ); ?>" class="widefat" />
					</div>
					<p class="description">
						<?php esc_html_e( 'Fetch an image whose ID matches or is greater than the given ID.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image id">
					<div class="left">
						<label for="featured_image_media_library_max_id"><?php esc_html_e( 'Max. Image ID', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="number" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_media_library_max_id]" id="featured_image_media_library_max_id" value="<?php echo esc_attr( $this->settings['featured_image_media_library_max_id'] ); ?>" min="0" max="999999999" step="1" placeholder="<?php esc_attr_e( 'e.g. 200', 'page-generator-pro' ); ?>" class="widefat" />
					</div>
					<p class="description">
						<?php esc_html_e( 'Fetch an image whose ID matches or is less than the given ID.', 'page-generator-pro' ); ?>
					</p>
				</div>

				<!-- URL, Creative Commons, Pexels, Pixabay, Wikipedia -->
				<div class="wpzinc-option featured_image url creative_commons pexels pixabay wikipedia">
					<div class="left">
						<label for="featured_image" class="featured_image url"><?php esc_html_e( 'URL', 'page-generator-pro' ); ?></label>
						<label for="featured_image" class="featured_image creative_commons pexels pixabay wikipedia"><?php esc_html_e( 'Term', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image]" id="featured_image" value="<?php echo esc_attr( $this->settings['featured_image'] ); ?>" class="widefat" />
					</div>

					<p class="featured_image description url">
						<?php esc_html_e( 'Enter an image URL. This can be a dynamic image URL; the contents will be saved in the WordPress Media Library as an image.', 'page-generator-pro' ); ?>
					</p>
					<p class="featured_image description creative_commons pexels pixabay">
						<?php esc_html_e( 'The search term to use. For example, "laptop" would return an image of a laptop. Each generated page will use a different image based on this tag. You can use keyword tags and spintax here.', 'page-generator-pro' ); ?>
					</p>
				</div>

				<!-- Creative Commons, Pexels and Pixabay -->
				<div class="wpzinc-option featured_image creative_commons pexels pixabay">
					<div class="left">
						<label for="featured_image_orientation"><?php esc_html_e( 'Image Orientation', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_orientation]" id="featured_image_orientation" size="1">
							<?php
							foreach ( $image_orientations as $image_orientation => $label ) {
								?>
								<option value="<?php echo esc_attr( $image_orientation ); ?>"<?php selected( $this->settings['featured_image_orientation'], $image_orientation ); ?>>
									<?php echo esc_attr( $label ); ?>
								</option>
								<?php
							}
							?>
						</select>
					</div>
					<p class="description">
						<?php esc_html_e( 'Restrict query to match images with the given orientation.', 'page-generator-pro' ); ?>
					</p>
				</div>

				<!-- Pixabay -->
				<div class="wpzinc-option featured_image pixabay">
					<div class="left">
						<label for="featured_image_pixabay_language"><?php esc_html_e( 'Language', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_pixabay_language]" id="featured_image_pixabay_language" size="1">
							<?php
							foreach ( $pixabay_languages as $language => $label ) {
								?>
								<option value="<?php echo esc_attr( $language ); ?>"<?php selected( $this->settings['featured_image_pixabay_language'], $language ); ?>>
									<?php echo esc_attr( $label ); ?>
								</option>
								<?php
							}
							?>
						</select>
					</div>
					<p class="description">
						<?php esc_html_e( 'The language the search term is in.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image pixabay">
					<div class="left">
						<label for="featured_image_pixabay_image_type"><?php esc_html_e( 'Image Type', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_pixabay_image_type]" id="featured_image_pixabay_image_type" size="1">
							<?php
							foreach ( $pixabay_image_types as $image_type => $label ) {
								?>
								<option value="<?php echo esc_attr( $image_type ); ?>"<?php selected( $this->settings['featured_image_pixabay_image_type'], $image_type ); ?>>
									<?php echo esc_attr( $label ); ?>
								</option>
								<?php
							}
							?>
						</select>
					</div>
					<p class="description">
						<?php esc_html_e( 'The image type to search.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image pixabay">
					<div class="left">
						<label for="featured_image_pixabay_image_category"><?php esc_html_e( 'Image Category', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_pixabay_image_category]" id="featured_image_pixabay_image_category" size="1">
							<?php
							foreach ( $pixabay_image_categories as $image_category => $label ) {
								?>
								<option value="<?php echo esc_attr( $image_category ); ?>"<?php selected( $this->settings['featured_image_pixabay_image_category'], $image_category ); ?>>
									<?php echo esc_attr( $label ); ?>
								</option>
								<?php
							}
							?>
						</select>
					</div>
					<p class="description">
						<?php esc_html_e( 'The image category to search.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image pixabay">
					<div class="left">
						<label for="featured_image_pixabay_image_color"><?php esc_html_e( 'Image Color', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_pixabay_image_color]" id="featured_image_pixabay_image_color" size="1">
							<?php
							foreach ( $pixabay_image_colors as $image_color => $label ) {
								?>
								<option value="<?php echo esc_attr( $image_color ); ?>"<?php selected( $this->settings['featured_image_pixabay_image_color'], $image_color ); ?>>
									<?php echo esc_attr( $label ); ?>
								</option>
								<?php
							}
							?>
						</select>
					</div>
					<p class="description">
						<?php esc_html_e( 'Returns an image primarily comprising of the given color.', 'page-generator-pro' ); ?>
					</p>
				</div>

				<!-- Wikipedia -->
				<div class="wpzinc-option featured_image wikipedia">
					<div class="left">
						<label for="featured_image_wikipedia_language"><?php esc_html_e( 'Language', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_wikipedia_language]" id="featured_image_wikipedia_language" size="1">
							<?php
							foreach ( $wikipedia_languages as $language => $label ) {
								?>
								<option value="<?php echo esc_attr( $language ); ?>"<?php selected( $this->settings['featured_image_wikipedia_language'], $language ); ?>>
									<?php echo esc_attr( $label ); ?>
								</option>
								<?php
							}
							?>
						</select>
					</div>
					<p class="description">
						<?php esc_html_e( 'The language the search term is in.', 'page-generator-pro' ); ?>
					</p>
				</div>
			</div>
			<!-- /.postbox -->
		</div>
		<!-- /Search Parameters -->

		<!-- Output -->
		<div id="featured-image-output" class="featured-image-panel">
			<div class="postbox">
				<header>
					<h3><?php esc_html_e( 'Output', 'page-generator-pro' ); ?></h3>
					<p class="description">
						<?php esc_html_e( 'Defines output parameters for the Featured Image.', 'page-generator-pro' ); ?>
					</p>
				</header>

				<!-- Media Library -->
				<div class="wpzinc-option featured_image id">
					<div class="left">
						<label for="featured_image_title"><?php esc_html_e( 'Create as Copy', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<select name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_copy]" id="featured_image_copy" size="1">
							<option value=""<?php selected( $this->settings['featured_image_copy'], '' ); ?>><?php esc_html_e( 'No', 'page-generator-pro' ); ?></option>
							<option value="1"<?php selected( $this->settings['featured_image_copy'], '1' ); ?>><?php esc_html_e( 'Yes', 'page-generator-pro' ); ?></option>
						</select>
					</div>

					<p class="description">
						<?php esc_html_e( 'Store the found image as a new copy in the Media Library. This is recommended if defining Output and EXIF data that is Keyword-specific.', 'page-generator-pro' ); ?>
					</p>
				</div>

				<div class="wpzinc-option featured_image id url creative_commons pexels pixabay wikipedia">
					<div class="left">
						<label for="featured_image_title"><?php esc_html_e( 'Title', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_title]" id="featured_image_title" value="<?php echo esc_attr( $this->settings['featured_image_title'] ); ?>" class="widefat" />
					</div>

					<p class="description">
						<?php esc_html_e( 'The title to assign to the image.  Note: it is up to your Theme to output this when it outputs your Featured Image.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image id url creative_commons pexels pixabay wikipedia">
					<div class="left">
						<label for="featured_image_caption"><?php esc_html_e( 'Caption', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_caption]" id="featured_image_caption" value="<?php echo esc_attr( $this->settings['featured_image_caption'] ); ?>" class="widefat" />
					</div>

					<p class="description">
						<?php esc_html_e( 'The caption to assign to the image.  Note: it is up to your Theme to output this when it outputs your Featured Image.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image id url creative_commons pexels pixabay wikipedia">
					<div class="left">
						<label for="featured_image_alt"><?php esc_html_e( 'Alt Tag', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_alt]" id="featured_image_alt" value="<?php echo esc_attr( $this->settings['featured_image_alt'] ); ?>" class="widefat" />
					</div>

					<p class="description">
						<?php esc_html_e( 'The alt tag to assign to the image.  Note: it is up to your Theme to output this when it outputs your Featured Image.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image id url creative_commons pexels pixabay wikipedia">
					<div class="left">
						<label for="featured_image_description"><?php esc_html_e( 'Description', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_description]" id="featured_image_description" value="<?php echo esc_attr( $this->settings['featured_image_description'] ); ?>" class="widefat" />
					</div>

					<p class="description">
						<?php esc_html_e( 'The description to assign to the image.  Note: it is up to your Theme to output this when it outputs your Featured Image.', 'page-generator-pro' ); ?>
					</p>
				</div>
				<div class="wpzinc-option featured_image id url creative_commons pexels pixabay wikipedia">
					<div class="left">
						<label for="featured_image_filename"><?php esc_html_e( 'Filename', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_filename]" id="featured_image_filename" value="<?php echo esc_attr( $this->settings['featured_image_filename'] ); ?>" class="widefat" />
					</div>

					<p class="description">
						<?php esc_html_e( 'Define the filename for the image, excluding the extension.', 'page-generator-pro' ); ?>
					</p>
				</div>

			</div>
			<!-- /.postbox -->
		</div>
		<!-- /Output -->

		<!-- EXIF -->
		<div id="featured-image-exif" class="featured-image-panel">
			<div class="postbox">
				<header>
					<h3><?php esc_html_e( 'EXIF', 'page-generator-pro' ); ?></h3>
					<p class="description">
						<?php esc_html_e( 'Defines EXIF metadata to store in the Featured Image.', 'page-generator-pro' ); ?>
					</p>
				</header>

				<div class="wpzinc-option featured_image id url creative_commons pexels pixabay wikipedia">
					<div class="left">
						<label for="featured_image_exif_latitude"><?php esc_html_e( 'Latitude', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_exif_latitude]" id="featured_image_exif_latitude" value="<?php echo esc_attr( $this->settings['featured_image_exif_latitude'] ); ?>" class="widefat" />
					</div>
				</div>

				<div class="wpzinc-option featured_image id url creative_commons pexels pixabay wikipedia">
					<div class="left">
						<label for="featured_image_exif_longitude"><?php esc_html_e( 'Longitude', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_exif_longitude]" id="featured_image_exif_longitude" value="<?php echo esc_attr( $this->settings['featured_image_exif_longitude'] ); ?>" class="widefat" />
					</div>
				</div>

				<div class="wpzinc-option featured_image id url creative_commons pexels pixabay wikipedia">
					<div class="left">
						<label for="featured_image_exif_comments"><?php esc_html_e( 'Comments', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_exif_comments]" id="featured_image_exif_comments" value="<?php echo esc_attr( $this->settings['featured_image_exif_comments'] ); ?>" class="widefat" />
					</div>
				</div>

				<div class="wpzinc-option featured_image id url creative_commons pexels pixabay wikipedia">
					<div class="left">
						<label for="featured_image_exif_description"><?php esc_html_e( 'Description', 'page-generator-pro' ); ?></label>
					</div>
					<div class="right">
						<input type="text" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[featured_image_exif_description]" id="featured_image_exif_description" value="<?php echo esc_attr( $this->settings['featured_image_exif_description'] ); ?>" class="widefat" />
					</div>
				</div>
			</div>
			<!-- /.postbox -->
		</div>
		<!-- /Output -->
	</div>
</div>
