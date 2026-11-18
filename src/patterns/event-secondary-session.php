<?php
/**
 * Title: Event: Secondary Session
 * Slug: event-secondary-session
 * Categories: events, featured
 * Description: Adds a second event title, presenter group, and event description inside post content. Drop into a 'single-event' template when featuring more than one presentation.
 * Keywords: featured, secondary-event, second-event
 * Viewport Width: 800
 */
?><!-- wp:heading {"level":2,"placeholder":"Enter second session title","textColor":"brand-text-main","className":"event-title"} --><h2 class="wp-block-heading event-title has-brand-text-main-color has-text-color"></h2><!-- /wp:heading --><!-- wp:group {"className":"presenter-group","layout":{"type":"constrained","justifyContent":"left"}} --><div class="wp-block-group presenter-group"><!-- wp:paragraph {"textColor":"brand-text-main"} --><p class="has-brand-text-main-color has-text-color"><em>Presented by Speaker Name</em></p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"presenter-title-and-organization"} --><p class="presenter-title-and-organization">Title and <a href="#"><strong>Organization</strong></a></p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"event-description-group","layout":{"type":"constrained"}} --><div class="wp-block-group event-description-group"><!-- wp:paragraph {"placeholder":"Enter second session description...","textColor":"brand-text-main","className":"event-description"} --><p class="event-description has-brand-text-main-color has-text-color"></p><!-- /wp:paragraph --></div><!-- /wp:group -->