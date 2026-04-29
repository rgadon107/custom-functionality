# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org).

## [Unreleased Version 1.7 - 2026-XX-XX]()
### Added
- `/assets/styles/variables.css`: Added file to plugin and assigned color values to CSS variables.
- `/src/asset/handler.php`: Enqueue the `variables.css` stylesheet with `enqueue_plugin_styles()`.
- `/assets/styles/coblocks-accordion-fix.css`: Added color variables to `<svg>` fonts declared in file.

### Changed

### Fixed

### Corrections

## [Version 1.6 - 2026-04-27](https://github.com/rgadon107/custom-functionality/pull/8)
### Added
- `assets/styles/coblocks-accordian-fix.css`: Added styles to fix the accordian caret in the Coblocks plugin.
  - This includes the svgs `caret-right` and `caret-down`.
- `src/asset/handler.php`: Updated `enqueue_plugin_styles()` to enqueue the `coblocks-accordian-fix.css` stylesheet.

### Changed
- `src/asset/handler.php`: Refactored `function __NAMESPACE__ . '\enqueue_plugin_styles'` to loop through stylesheets and enqueue them.
- `/bootstrap.php`: Updated header values:
  - plugin version bump to `1.6.0`;
  - requires WP increased to `6.9.4`, and
  - requires PHP to `8.3`.

### Fixed
- Used WP-CLI to search and remove all instances of the `?` icon in the `wp_posts` db table preceding the `.wp-block-coblocks-accordion-item summary::before` selector.

## [Version 1.5.1 - 2026-04-26](https://github.com/rgadon107/custom-functionality/pull/7)
### Fixed
- `/bootstrap.php`: Updated plugin version number to `1.5.1`.

## [Version 1.5 - 2026-04-25](https://github.com/rgadon107/custom-functionality/pull/6)
### Added
- `/assets/styles/ninja-form-styles.css`: Added styles for `Email Signup Form` rendered in popup opened from call-to-action button in footer.
- `/Changelog.md`: Add a file to track changes in the plugin over time.

### Changed
- `/src/asset/handler.php`: Changed `wp_register_script()` to `wp_enqueue_script()` inside custom function `__NAMESPACE__` . `\enqueue_plugin_styles`.
  - Registered stylesheet `ninja-form-styles.css` now loads on the front end.

## [Version 1.4 - 2026-04-22](https://github.com/rgadon107/custom-functionality/pull/5)
### Added
- `/src/shortcodes/current-year.php`: Register the shortcode [current_year] to display the current year following the copyright notice in the site footer.
- `/src/controller.php`: Register the shortcode [current_year] with WordPress.

## [Version 1.3 - 2026-04-16](https://github.com/rgadon107/custom-functionality/pull/4)
### Added
- `/src/hoops.php` - Filter `http_request_args` to handle latent responses from Constant Contact during household membership applications.

### Fixed
- Extend the maximum duration of an http request to Constant Contact to register email addresses for new household memberships.

## [Version 1.2 - 2026-03-31](https://github.com/rgadon107/custom-functionality/pull/3)
### Added
- `/src/shortcodes/expire-content.php`: Added the business logic to register two shortcodes with WordPress; [expire], and [expire_inner].
- `/src/controller.php`: Registered the shortcode `expire-content.php` with WordPress.
- `/assets/styles/event-notices.css`: Style the shortcode messages.
- `/src/asset/handler.php`: Enqueue the shortcode message styles.
- `/src/patterns/*`: Register the shortcode messages with WordPress as design patterns.
  - Useful for when the shortcodes are converted to blocks in the next development phase.
- `/bootstrap.php`: Autoload the shortcode with the plugin.
- `/src/shortcodes/README.md`: [Work in Progress] – Document the shortcodes.

## [Version 1.1 - 2026-02-13](https://github.com/rgadon107/custom-functionality/pull/2)
### Added
- Enqueue jquery script files:
  - `/assets/scripts/nf-checkbox-toggle.js`,
  - `/assets/scripts/nf-prevent-early-form-submit-while-using-return-key.js`.
- The `nf-checkbox-toggle.js` enables Ninja Forms to display a boolean response (Yes / No) when a checkbox is checked or unchecked.
  - This feature is needed for club dinner meeting registrations when offering a vegetarian or gluten-free meal option.
- The `nf-prevent-early-form-submit-while-using-return-key.js` script prevents the form from submitting early when a visitor uses the 'Return' key to navigate through the form.
- `/src/hook.php`:  Filter `post_password_expires` to change the password cookie set by WordPress on the member's only login page to a session cookie.

### Changed
- `/bootstrap.php`: Update file functions to conform to PHP v8.+ syntax.
- `/src/asset/handler.php`: Remove `enqueue_custom_styles()` as the plugin does not enqueue any styles.

### Fixed
- Mobile navigation menu: Sub-navigation list items (2nd and 3rd level) are now closed by default. This declutters the mobile navigation view.
- Moved the jquery script used to prevent an early return on the membership application form from the 'Scripts and Styles' plugin to a module in the `custom-functionality` plugin.
- Filtered WordPress' default session cookie on password-protected pages from 10 to 0 days. Members must now log back into the member-only content area on each new browser session.

## [Version 1.0 - 2026-02-03](https://github.com/rgadon107/custom-functionality/pull/1)

Initial commit of the plugin.

### Added
- Added `/src/hooks.php` file to plugin autoloader.

### Changed
- Bumped the minimum PHP version from '5.6.0' to '7.4'.

### Fixed
- `bootstrap.php`:
	- Fixed WP_DEBUG declaration for activating plugin development mode.
	- Fixed functions `register_plugin()` and `delete_rewrite_rules()`
