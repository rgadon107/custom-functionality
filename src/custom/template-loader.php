<?php
/**
 * Block Template Loader for Custom Post-Types.
 *
 * Registers plugin-bundled `.html` block templates with WordPress's Site Editor
 * and Template Hierarchy so custom post-type layouts remain portable across
 * block-based themes.
 *
 * @package    gardenClubOfMpls\CustomFunctionalityPlugin
 * @subpackage Source\Custom
 * @since      2.4.0
 */

declare(strict_types=1);

namespace gardenClubOfMpls\CustomFunctionalityPlugin\Source\Custom;

/**
 * Registers block templates for custom post types from `/src/templates/`.
 *
 * @return void
 * @since 2.4.0
 *
 */
function register_cpt_block_templates(): void
{
	if (!function_exists('register_block_template')) {
		return;
	}

	$templates_dir = dirname(__DIR__) . '/templates/';

	$templates = [
		'custom-functionality//single-events' => [
			'title' => __('Single Event', 'custom-functionality'),
			'description' => __('Displays a single event with metadata and schedule details.', 'custom-functionality'),
			'post_types' => ['events'],
			'file' => $templates_dir . 'single-events.html',
		],
		'custom-functionality//archive-events' => [
			'title' => __('Events Archive', 'custom-functionality'),
			'description' => __('Displays a chronological listing of upcoming and past events.', 'custom-functionality'),
			'post_types' => ['events'],
			'file' => $templates_dir . 'archive-events.html',
		],
	];

	foreach ($templates as $template_name => $config) {
		if (!file_exists($config['file'])) {
			continue;
		}

		$content = file_get_contents($config['file']);
		if (false === $content) {
			continue;
		}

		register_block_template(
			$template_name,
			[
				'title' => $config['title'],
				'description' => $config['description'],
				'content' => $content,
				'post_types' => $config['post_types'],
			]
		);
	}
}
