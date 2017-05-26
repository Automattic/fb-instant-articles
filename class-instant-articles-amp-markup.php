<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 * @since 4.0
 */

use Facebook\InstantArticles\AMP\AMPArticle;
use Facebook\InstantArticles\Validators\Type;

class Instant_Articles_Amp_Markup {

	const SETTING_AMP_MARKUP = 'amp_markup'; /// Setting to check if AMP Markup generation is enabled
	const SETTING_STYLE      = 'amp_stylesheet'; /// Setting that stores the JSON stylesheet

	const QUERY_ARG          = 'amp_markup'; /// Query argument that will trigger the AMP markup generation

	// To memoize the settings
	static $settings = null;

	/**
	 * Gets the settings
	 *
	 * @return array The settings, check Instant_Articles_Option::get_option_decoded()
	 * @since 4.0
	 */
	static function get_settings() {
		if (self::$settings === null)
			self::$settings = Instant_Articles_Option_Amp::get_option_decoded();

		return self::$settings;
	 }
	/**
	 * Checks if the AMP markup is enabled
	 *
	 * @return bool true if markup is enabled
	 * @since 4.0
	 */
	static function is_markup_enabled() {
		$settings = self::get_settings();

		return
			isset( $settings[self::SETTING_AMP_MARKUP] )
			? (bool) $settings[self::SETTING_AMP_MARKUP]
			: false;
	}
	/**
	 * Adds the meta tag for AMP Markup if enabled
	 *
	 * @since 4.0
	 */
	static function inject_link_rel() {

		if (!self::is_markup_enabled())
			return;

		// Transform the post to an Instant Article.
		$adapter = new Instant_Articles_Post( get_post() );

		if (!$adapter->should_submit_post())
			return;

		$url = $adapter->get_canonical_url();
		$url = add_query_arg(self::QUERY_ARG, '1', $url );

		echo '<link rel="amphtml" href="' . $url . '">';

	}

	/**
	 * Generates the AMP markup if post has amp_markup
	 *
	 * NOTE: side-effect: function calls die() in the end.
	 * @since 4.0
	 */
	static function markup_version( ) {
		if (!(isset($_GET[ self::QUERY_ARG ]) && $_GET[ self::QUERY_ARG ]))
			return;

		$settings = self::get_settings();

		if (!self::is_markup_enabled())
			return;

		$has_stylesheet =
			isset($settings[self::SETTING_STYLE])
			? !Type::isTextEmpty($settings[self::SETTING_STYLE])
			: false;

		$styles_array = json_decode($settings[self::SETTING_STYLE], true);
		if (json_last_error() != JSON_ERROR_NONE)
			$has_stylesheet = false;

		$properties = array();

		// download images to get width and height
		// TODO: change this to an option
		// TODO: use the new consts in the AMPArticle
		$properties[AMPArticle::ENABLE_DOWNLOAD_FOR_MEDIA_SIZING_KEY] = false;

		if ($has_stylesheet) {
			$properties[AMPArticle::OVERRIDE_STYLES_KEY] = $styles_array;
		}

		$post = get_post();

		// This array will hold the image sizes
		$media_sizes = array();

		// Get all children with mime type image that are attached to the posts
		// NOTE: this will not get images that are not hosted in the WP
		$image_children = get_children('post_type=attachment&post_mime_type=image&post_parent=' . $post->ID );

		foreach ($image_children as $img_id => $img) {
			$meta = wp_get_attachment_metadata($img_id);

			// Removes the file name from the URL
			$url_chunks = explode('/', $img->guid);
			array_pop($url_chunks);
			$base_image_url = implode('/', $url_chunks) . '/';

			// This is the uploaded original file
			$media_sizes[$img->guid] = array($meta['width'], $meta['height']);

			// These are the possible redimensions
			foreach ($meta['sizes'] as $size) {
				$size_url = $base_image_url.$size['file'];
				$media_sizes[$size_url] = array($size['width'], $size['height']);
			}
		}

		$properties[AMPArticle::MEDIA_SIZES_KEY] = $media_sizes;

		// Transform the post to an Instant Article.
		$adapter = new Instant_Articles_Post( $post );
		$article = $adapter->to_instant_article();
		$article_html = $article->render();
		$amp = AMPArticle::create($article_html, $properties);
		echo $amp->render();

		die();
	 }
 }
