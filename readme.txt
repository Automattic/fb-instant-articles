=== Instant Articles for WP ===
Contributors: trrine, olethomas, bjornjohansen, dekode
Tags: instant articles, facebook, mobile
Requires at least: 4.3
Tested up to: 4.4
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enable [Instant Articles for Facebook](https://developers.facebook.com/docs/instant-articles) on your WordPress site.

== Description ==

This plugin adds support for Instant Articles for Facebook, which is a new way for publishers to distribute stories on Facebook. Instant Articles are preloaded in the Facebook mobile app so they load instantly.

With the plugin active, a special RSS feed will be available at the URL `/feed/instant-articles`.

Developers: please note that this plugin is still in early stages and the underlying APIs (like filters, classes, etc.) may change.

= Feed submission to Facebook =

When you first submit your feed to Facebook for review, you must provide 100 real articles.
Yes, they will review this manually, and yes: this means you must have at least 100 posts.

Facebook will now import your 100 posts as Instant Articles.

After the inital import, Facebook will only import new articles or import articles modified within the last 24 hours.
You can set the constant `INSTANT_ARTICLES_LIMIT_POSTS` to `true` to limit the feed to only show posts that are modified within the last 24 hours.

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

Usually simply visiting the permalinks settings page in the WordPress dashboard will to the trick (/wp-admin/options-permalink.php)

== Changelog ==

= 0.1 =
* Initial version