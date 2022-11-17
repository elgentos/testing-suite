# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 3.5.1
### Changed
- ESLint conflict between `no-mixed-operators` and `no-extra-parens` resolved.

## 3.5.0
### Added
- Args to show phpcs warnings/errors in color and show the correct class that
  renders the warning/error. This way it's easier to ignore if necessary.

### Removed
- Rule `Generic.Formatting.MultipleStatementAlignment`, since this did not help for the readability 
  of the code.

## 3.4.0
### Added
- Constraint for `squizlabs/php_codesniffer` to be compatible with 
  magento 2.4.4 and higher coding standards.

## 3.3.1 - 2022-05-27
### Changed
- Package is now also installable in PHP 8.

## 3.3.0 - 2021-03-25
### Added
- Copyrights.
- Declare strict types.
- Updated squizlabs/php_codesniffer to resolve phpunit error.

### Changed
- Vendor to Youwe.
- Changed MediaCT rule names to Global.

## 3.1.0 - 2021-03-10
### Removed
- PHP 5.x support.
- Phpunit is required by mediact testing suite and is not a requirement for this module.

### Fixed
- PhpStorm 2020 has changed there config folder to a different path.

### Changed
- Refactored code to fix testing suite issues.

### Added
- [Dev Docker](https://github.com/mediact/docker-compose-development-manager)
- Compatibility with latest testing suite module.
