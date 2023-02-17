# Change Log
All notable changes to this project will be documented in this file, formatted via [this recommendation](https://keepachangelog.com/).

## [2.3.1] - 2022-08-30
### Fixed
- Error when using location with autocomplete.

## [2.3.0] - 2022-07-05
### IMPORTANT
- Algolia Places has been discontinued by Algolia. All Algolia functionality in the addon has been deprecated and removed.

### Added
- New Places Provider: Mapbox.
- Added Preview area on the Settings > Geolocation admin page.
- Added new filters to change the map appearance and location sources.

### Changed
- Increased minimum WPForms supported version to 1.7.5.
- Browser no longer automatically completes the Text field if Address Autocomplete is enabled.
- Improved detection of the user's current location.
- In the address and text field search, users can now hit the Enter key to select an address.

### Fixed
- Fixed map styling inside the Full Site Editor in WordPress 6.0.
- Geolocation coordinates are correct for Address Autocomplete with custom scheme.
- Address autocomplete fills in the Address > City subfield.
- `{entry_geolocation}` smart tag works in Confirmation messages.
- Compatibility with the Conversational Forms addon has been improved.

## [2.2.0] - 2022-05-11
### IMPORTANT
- Algolia Places has been discontinued by Algolia. If you are using it you need to switch to Google Places to prevent disruptions in form geolocation features.

### Added
- New filter `wpforms_geolocation_places_providers_google_places_query_args` that can be used to improve multi-language support.

### Fixed
- Users geolocation detection on the Entry page was working incorrectly with KeyCDN API.

## [2.1.0] - 2022-03-16
### Added
- Compatibility with WPForms 1.6.8 and the updated Form Builder.
- Compatibility with WPForms 1.7.3 and Form Revisions.

### Changed
- Minimum WPForms version supported is 1.6.7.1.

### Fixed
- Address field filling.
- Value with mask is not saved in a Text field when Address Autocomplete is enabled.
- Various typos reported by translators.

## [2.0.0] - 2021-02-18
### Added
- New Places Providers selection: Google Places, Algolia Places.
- Address and Text fields can have address autocomplete enabled on typing.
- Display a map before or after the field to select location on a map without typing.
- Retrieve user's current location with a browser prompt and prefill address/text fields with address autocomplete enabled.
- Added own WPForms geolocation API endpoint to retrieve users geolocation based their IP address.

### Changed
- Removed map image preview from email notifications due to Google API restrictions.

### Fixed
- Geolocation: display and save only existing data (sometimes ZIP code may be missing).

## [1.2.0] - 2019-07-23
### Added
- Complete translations for French and Portuguese (Brazilian).

## [1.1.1] - 2019-02-26
### Fixed
- Geolocation provider fallback logic.
- Referencing geolocation providers no longer accessible.

## [1.1.0] - 2019-02-06
### Added
- Complete translations for Spanish, Italian, Japanese, and German.

### Fixed
- Typos, grammar, and other i18n related issues.

## [1.0.3] - 2017-09-28
### Changed
- Use HTTPS when requesting location data via ipinfo.io
- Use bundled SSL certificates (since WordPress 3.7) to verify properly target sites SSL certificates

## [1.0.2]
### Changed
- Always use SSL connection to check user IPs location data
- Always verify SSL certificates of the services we use to get location data

## [1.0.1] - 2016-08-04
### Fixed
- Bug preventing IP addresses from processing

## [1.0.0] - 2016-08-03
### Added
- Initial release
