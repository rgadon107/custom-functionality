<?php
/**
 * Title: Event: Secondary Session
 * Slug: event-secondary-session
 * Categories: events, featured
 * Description: Adds a second event title, presenter group, and event description inside post content. Drop into a 'single-event' template when featuring more than one presentation.
 * Keywords: featured, secondary-event, second-event
 * Viewport Width: 800
 */
?><!-- wp:heading {"placeholder":"Enter second session title","className":"event-title","textColor":"brand-text-main","metadata":{"name":"Event Title"}} --><h2 class="wp-block-heading event-title has-brand-text-main-color has-text-color"></h2><!-- /wp:heading --><!-- wp:group {"className":"presenter-group","layout":{"type":"constrained","justifyContent":"left"},"metadata":{"name":"Presenter Info Group Block"}} --><div class="wp-block-group presenter-group"><!-- wp:paragraph {"placeholder":"Presented by Speaker Name","className":"presenter-name","textColor":"brand-text-main"} --><p class="has-brand-text-main-color has-text-color presenter-name"></p><!-- /wp:paragraph --><!-- wp:paragraph {"placeholder":"Title, Organization and URL Link if available.","className":"presenter-title-and-organization"} --><p class="presenter-title-and-organization"></p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"event-description-group","layout":{"type":"constrained"},"metadata":{"name":"Event Description Group Block"}} --><div class="wp-block-group event-description-group"><!-- wp:paragraph {"placeholder":"Enter second session description...","className":"event-description","textColor":"brand-text-main"} --><p class="event-description has-brand-text-main-color has-text-color"></p><!-- /wp:paragraph --></div><!-- /wp:group -->