# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org).

## [Unreleased Version 1.8.2 - 2026-05-21](https://github.com/rgadon107/custom-functionality/pull/15)
### Added
- `/assets/styles/ninja-form-styles.css`: Added styles to target links in a Ninja Forms success message. These styles override the parent theme link styles.

[//]: # (### Changed)

[//]: # ()
[//]: # (### Fixed)

[//]: # ()
[//]: # (### Corrections)


## [Version 1.8.1 - 2026-05-16](https://github.com/rgadon107/custom-functionality/pull/14)
### Added
- `/.editorconfig`: Configured editor settings targeting design pattern files and PHPStorm-specific settings.
  - Eliminate newlines at the end of files, and markup indentation.
  - This will allow the WP parser to read the file and not insert `Classic` before patterns inserted into the editor.

### Changed
- `/includes/patterns/pattern-loader.php`: Added filter to `register_plugin_block_patterns()` to exclude pattern file docblock from being parsed by the WP parser.
- Minified delimiters and HTML in design pattern files.
  - `accordion-meeting-topics.php`;
  - `message-after-registration-stop.php`; and
  - `message-before-registration-start.php`.
- Modified IDE settings to target design pattern files.
- `/bootstrap.php`: Plugin version bump to `1.8.1`.

### Fixed
- Eliminated leading whitespace before delimiters and newline at end of each file.
  - `accordion-meeting-topics.php`;
  - `message-after-registration-stop.php`; and
  - `message-before-registration-start.php`.
- `/assets/styles/coblocks-accordion-styles.css`: Fix color contrast of link in accordion table row.

### Corrections
- `/assets/styles/coblocks-accordion-styles.css`: In version `1.8.0`, changed the file name from `/coblocks-accordion-fix.css` to `/coblocks-accordion-styles.css`.
- `/Changelog.md`: Fix reference in version 1.3.0 release notes to `/src/hooks.php`.

## [Version 1.8.0 - 2026-05-10](https://github.com/rgadon107/custom-functionality/pull/13)
### Added
- `/includes/patterns/`: Added directory to manage logic to register design patterns with WordPress.
- `/includes/patterns/pattern-loader.php`: Added file to register block patterns and categories with WordPress.
- `/src/patterns/accordion-meeting-topics.php`: Added a design pattern to register the accordion meeting topics block.
  - Added a `templateLock` property to the block attributes to prevent site editors from accidentally deleting or moving any portion of the block.
  - Added placeholder text to the accordion label and the table fields.
- `/assets/styles/coblocks-accordion-fix.css`: Updates to style sheet.
  - Prevent the browser from jumping when accordion is opened.
  - Change the background-color of the accordion from dark green to dark blue.
  - Change the link visibility inside an accordion item.
  - Add horizontal 'zebra-striping' colors to multi-row tables.
  - Adjust column widths to prevent character wrapping.
  - Add styles for mobile, tablet, and desktop view.
  - Add blue color variables.
- `/assets/scripts/coblocks-accordion-prevent-vertical-scroll.js`: Add and enqueue script to prevent the page from jumping when an accordion item is opened.
- `src/integrations/ninja-forms.php`: Added street suffix abbreviations to the `standardize_location_data()` function.

### Changed
- `/bootstrap.php`: Modified `autoload_files()` to serve as a module controller for the entire plugin.
  - Moved all module and file loading from `/src/controller.php` to `/bootstrap.php`.
  - Plugin version bump to `1.8.0`.
- `/src/controller.php`: Removed and relocated the `register_design_patterns()` function to `/includes/patterns/pattern-loader.php`.
- `/includes/patterns/pattern-loader.php`: Refactored `\\register_plugin_block_patterns()` to abstract away a new helper function `load_and_register_pattern()`.
  - `function load_and_register_pattern()`: Added the `$categories` variable to register with `register_block_pattern()`.
- `/src/controller.php`: Deleted the file from the project.
- `/src/asset/handler.php`: Refactor function`enqueue_plugin_scripts()` to add a custom configuration for new script files.
- `/src/README.md`:  Update the `/src/README.md` file to document the addition of an `integrations` directory.

### Fixed
- `/includes/patterns/pattern-loader.php`: In `$patterns_to_register` array, updated the reference to `'message-after-registration-start.php'` pattern so it will now load.
- `/src/patterns/message-after-registration-stop.php`: Fixed category in file header to correctly register the pattern with WordPress.
- `/src/patterns/message-before-registration-start.php`: Fixed category in file header to correctly register the pattern with WordPress.

## [Version 1.7.3 - 2026-05-03](https://github.com/rgadon107/custom-functionality/pull/12)
### Added
- `/src/integrations/ninja-forms.php`: Added filter for 'phone' field key to remove the `+1 ` preceding the phone number.

### Changed
- `/src/integrations/ninja-forms.php`: Replaced complex `if elseif` control statement with `match` expression ( valid in PHP 8 ).
- `/boostrap.php`: Plugin version bump to `1.7.3`.

## [Version 1.7.2 - 2026-05-02](https://github.com/rgadon107/custom-functionality/pull/11)
This version update filters Ninja Forms submission data for key fields that contain the term `name`, `email`, `zip`, `address`, `city`, and `county`.
It eliminates the need to filter form submission data in Zapier.
All Ninja Forms used on the `gardenclubmpls.org` website will now be filtered before they are submitted to the database, rendered to the front end, and sent to third party applications.

### Added
- `src/integrations/ninja-forms.php`:
  - Update the sorting machine in `cleanup_form_submission_data()` to use 'fuzzy' key matching.
    - This evaluates `zip`, `address`, `city`, and `county` key fields.
  - Add helper function `standardize_location_data()` to match regular expressions and expand street abbreviations.
  - Add and update function docblocks.

### Changed
- `/bootstrap.php`:
  - Modify path parameter in `plugins_url()` to an empty string to satisfy PHP 8.1+ type requirements.
  - Plugin version bump to `1.7.2`.

## [Version 1.7.1 - 2026-05-02](https://github.com/rgadon107/custom-functionality/pull/10)
### Fixed
- `/src/controller.php`: Fix string in file path to resolve `500 Internal Server Error` on live staging server.

## [Version 1.7.0 - 2026-05-02](https://github.com/rgadon107/custom-functionality/pull/9)
### Added
- `/assets/styles/variables.css`: Added CSS file to plugin and assigned color values to variables.
- `/src/asset/handler.php`: Enqueue the `variables.css` stylesheet with `enqueue_plugin_styles()`.
- `/assets/styles/coblocks-accordion-fix.css`: Added color variables to `<svg>` fonts declared in file.
- `/src/integrations/ninja-forms.php`: Added a file to filter and cleanup all name and email fields in Ninja Forms before submitting data.
- `/src/controller.php`: Load the Ninja Forms integration file only when the Ninja Forms plugin is active.

### Fixed
- Clean up Ninja Forms name and email address fields before form submission.

## [Version 1.6 - 2026-04-27](https://github.com/rgadon107/custom-functionality/pull/8)
### Added
- `/assets/styles/coblocks-accordion-fix.css`: Added styles to fix the accordion caret in the Coblocks plugin.
  - This includes the svgs `caret-right` and `caret-down`.
- `/src/asset/handler.php`: Updated `enqueue_plugin_styles()` to enqueue the `coblocks-accordion-fix.css` stylesheet.

### Changed
- `/src/asset/handler.php`: Refactored `function __NAMESPACE__ . '\enqueue_plugin_styles'` to loop through stylesheets and enqueue them.
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
- `/src/hooks.php` - Filter `http_request_args` to handle latent responses from Constant Contact during household membership applications.

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
- `/bootstrap.php`:
	- Fixed WP_DEBUG declaration for activating plugin development mode.
	- Fixed functions `register_plugin()` and `delete_rewrite_rules()`
