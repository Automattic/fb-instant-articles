=== Instant Articles for WP ===
Contributors: trrine, olethomas, bjornjohansen, dekode, automattic
Tags: instant articles, facebook, mobile
Requires at least: 4.3
Tested up to: 4.4
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enable [Instant Articles for Facebook](https://developers.facebook.com/docs/instant-articles) on your WordPress site.

== Description ==

This plugin adds support for Instant Articles for Facebook, which is a new way for publishers to distribute fast, interactive stories on Facebook. Instant Articles are preloaded in the Facebook mobile app so they load instantly.

With the plugin active, a special RSS feed will be available at the URL `/feed/instant-articles`.

Developers: please note that this plugin is still in early stages and the underlying APIs (like filters, classes, etc.) may change.

= Feed submission to Facebook =

Facebook has a review process where they verify that all Instant Articles are properly formatted, have content consistency with their mobile web counterparts, and adhere to their community standards and content policies. You will not be able to publish Instant Articles in Facebook until your feed has been approved.

It's important to note that if you use meta fields to add extra text, images or videos to your Posts, Facebook will expect you to add these to your Instant Articles output too. This plugin includes hooks to help you do that.

[See Facebook's documentation for full details of the submission process.](https://developers.facebook.com/docs/instant-articles)

Facebook requires a minimum number of articles in your feed before they will review it. Once your feed has been approved, you can set the constant `INSTANT_ARTICLES_LIMIT_POSTS` to `true` to limit the feed to only show posts that have been modified within the last 24 hours. (Facebook will ignore any articles which were last modified more than 24 hours ago.)

Facebook will fetch your feed every 3 minutes.

== Installation ==

1. Upload the folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I customize the output for my site? =

There are a number of filters available in the plugin for modifying the output. Note that these are not finalized and may change.

= How do I change the feed slug/URL? =

To change the feed slug, set the constant INSTANT_ARTICLES_SLUG to whatever you like. If you do, remember to flush the rewrite rules afterwards.
By default it is set to `instant-articles` which usually will give you a feed URL set to `/feed/instant-articles`

= How do I flush the rewrite rules after changing the feed slug? =

Usually simply visiting the permalinks settings page in the WordPress dashboard will do the trick (/wp-admin/options-permalink.php)

== Changelog ==

= 0.1 =
* Initial version