# Source Directory ( `/src` )

This directory holds all of the source code for the plugin.

Organize your plugin's source code by it's single purpose, grouping like features and functionality into individual directories.  This architectural structure improves the readability and reusability of your code.

For example, if you need:

- For custom post type and taxonomy, place those into a `custom` folder.
- For metaboxes and settings/options screens, add those into an `admin` folder.
- For the registration of shortcodes, add those into a `shortcodes` folder.
- For block registration, add those into a `blocks` folder.
- For plugin served templates, add a `templates` folder.
- For plugin served patterns accessible from the block inserter, add a `patterns` folder.
- For custom callbacks hooked to WordPress actions or filters, add a `hooks` folder.

Don't forget to separate concerns by keeping the business logic and HTML separate. Add a `src/patterns` folder to store `{custom-pattern}.php` files that manage frontend content.

### Crucial Detail: The "Double" Classing in Pattern Files

When you manually write pattern files, you must be careful to include any custom CSS classes in two places:

    The JSON Metadata: {"className":"registration-closed-notice"} — This tells the Block Editor's sidebar that this class is active. The JSON is wrapped in HTML comment tags.

    The HTML Attribute: class="... registration-closed-notice" — Add in a custom class to the HTML element. The class must exactly match what was added in to the JSON metadata.

How about the root of the directory?

At the root of this directory, you typically place the plugin's main controller.  This controller handles the routing of various tasks.
