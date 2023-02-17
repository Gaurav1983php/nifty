<?php

// phpcs:ignoreFile Generic.Files.OneObjectStructurePerFile.MultipleFound

namespace {

	use WPFormsGeolocation\Deprecated;

	/**
	 * The geolocation addon was refactored and the old class was dropped in 2.3.0 version.
	 *
	 * @since 2.0.0
	 */
	class_alias( Deprecated::class, '\WPForms_Geolocation' );
}

namespace WPFormsGeolocation\PlacesProviders {

	use WPFormsGeolocation\Deprecated;

	/**
	 * Algolia Places stopped work at 31 May 2022 and the class was removed in 2.3.0 version.
	 *
	 * @since 2.3.0
	 */
	class AlgoliaPlaces extends Deprecated {
	}
}

namespace WPFormsGeolocation\Admin\Settings {

	use WPFormsGeolocation\Deprecated;

	/**
	 * Algolia Places stopped work at 31 May 2022 and the class was removed in 2.3.0 version.
	 *
	 * @since 2.3.0
	 */
	class AlgoliaPlaces extends Deprecated {
	}
}
