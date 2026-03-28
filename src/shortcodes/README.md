# `[expire][/expire]` Shortcode

## What is a shortcode?

A small program registered with WordPress that manages content on the front end of a website. Shortcodes are assigned a name ( tag ) and can accept one or more user-defined attributes ( properties ). They typically enclose content on which they act. For example, the `expire` shortcode would be applied in the editor as follows:

[expire]
	content here.
[/expire]

Content can be _any content_; a portion of an event, or even the entire event.  A single event could contain 2 `[expire]` shorcodes. One shortcode could wrap a time-sensitive section of the event ( e.g. dinner meeting reservartion which starts and stops on different dates ). Another shortcode could wrap _the entire event_. When the current date and time exceeds the shortcode's `stop_date_and_time` setting, the entire event no longer display on the web page. These attributes allow an editor to automate in advance when to display and hide content on the site.

Notice the opening ( [expire] ) and closing ( [/expire] ) tags surrounding the content. The shortcode will not work if one of the two tags is missing, or the shortcode syntax is incorrect ( e.g. using { } instead of [ ]; missing the forward-slash before the shortcode tag ( [/expire] ) in the closing bracket

## The `expire` shortcode

### Default shortcode attributes

The `expire` shortcode registers 4 attributes with Wordpress:

1. start_date_and_time,
2. stop_date_and_time,
3. pre_start_message, // a message to display before an event start date and time.
4. message // a message to display after an event stop date and time.

### Default attribute values

Their default values are as follows:

1. start_date_and_time='1970-01-01 00:00',
2. stop_date_and_time='2050-01-01 00:00',
3. pre_start_message='', // an empty string; that is, no message.
4. message=''. // an empty string; that is, no message.

### Substituting user-defined for default attribute values

If content is wrapped with the shortcode as `[expire]some content[/expire]`, by default it won't do anything. The default `start_date_and_time` and `stop_date_and_time` values are so far in the past and the future, the shortcode won't have any effect on the content in the near term.

When the default `start_date_and_time` and `stop_date_and_time` attributes are overwritten with values closer to the present, then the shortcode becomes useful.

If the default value of the `start_date_and_time` attribute is overwritten as `start_date_and_time='2026-04-01 00:00` ( April 1, 2026 at 12:00 AM ), then the shortcode will display the wrapped content starting on the new date and time. If the default value for `stop_date_and_time` is overwritten as `stop_date_and_time='2026-05-01 00:00'`, then the shortcode will stop displaying wrapped content on the stop date and time.

### Examples of applying the [expire] shortcode

1. Wrapping content with a start date and time
2. Wrapping content with a start and stop date and time.
3. Wrapping content with a start date and time and pre start message.
4. Wrapping content with a stop date and time and message.
5. Wrapping content with a start date and time and pre-start message, and a stop date and time and message.

Describe the effect of each use case and when they might be applied.
