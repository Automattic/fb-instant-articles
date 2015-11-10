=== Instant Articles for WP ===
Contributors: bjornjohansen
Tags: instant articles, facebook, mobile
Requires at least: 4.3
Tested up to: 4.3
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enable [Instant Articles for Facebook](https://developers.facebook.com/docs/instant-articles) on your WordPress site.

== Description ==

This plugin adds support for Instant Articles for Facebook, which is a new way for publishers to distribute stories on Facebook. Instant Articles are preloaded in the Facebook mobile app so they load instantly.

With the plugin active, all posts on your site will have dynamically generated Instant Articles-compatible versions, accessible by appending `/instant/` to the end your permalinks. (If you do not have pretty permalinks enabled, you can do the same thing by appending `?instant=1`.)

By default a special RSS feed will be available at the URL `/feed/instant-articles`. You can protect this with HTTP auth compatible with Facebook by using a filter.

Developers: please note that this plugin is still in early stages and the underlying APIs (like filters, classes, etc.) may change.

== Installation ==

1. Upload the folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I customize the output for my site? =

There are a number of filters available in the plugin for modifying the output. Advanced options like custom templates in the works. Note that these are not finalized and may change.

== Changelog ==

= 0.1 =
* Initial version