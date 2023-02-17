# Changelog
All notable changes to this project will be documented in this file, formatted via [this recommendation](https://keepachangelog.com/).

## [1.0.6] - 2022-10-20
### Fixed
- No user journey details were added to new entries after clean installation.

## [1.0.5] - 2022-10-04
### Fixed
- The `{entry_user_journey}` smart tag was not rendered in re-sent email notifications when this action was performed on the single Entry view page.
- A fatal error occurred during the addon activation when the core plugin was inactive.

## [1.0.4] - 2022-09-29
### Changed
- Minimum WPForms version supported is 1.7.7.

### Fixed
- The new `{entry_user_journey}` smart tag wasn't displayed in a list of available smart tags.

## [1.0.3] - 2022-09-21
### Added
- User Journey smart tag for Confirmation message and Notification Email (HTML and plain text).

### Fixed
- The addon was generating too big cookies that in certain cases resulted in site being non-operational.

## [1.0.2] - 2022-02-10
### Changed
- Improved compatibility with PHP 8.

### Fixed
- Issue with JavaScript code on front-end in Internet Explorer 11.

## [1.0.1] - 2021-03-31
### Fixed
- Issue with JavaScript code for collecting data in cookies in Safari v14.

## [1.0.0] - 2020-11-12
### Added
- Initial release.
