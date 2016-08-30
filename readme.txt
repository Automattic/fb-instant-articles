=== Instant Articles for WP ===
Contributors: trrine, olethomas, bjornjohansen, dekode, automattic, facebook
Tags: instant articles, facebook, mobile
Requires at least: 4.3
Tested up to: 4.6
Stable tag: 3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enable Instant Articles for Facebook on your WordPress site.

== Description ==

This plugin adds support for Instant Articles for Facebook, which is a new way for publishers to distribute fast, interactive stories on Facebook. Instant Articles are preloaded in the Facebook mobile app so they load instantly.

With the plugin active, a new menu will be available for you to connect to your Facebook Page and start publishing your Instant Articles. You'll also see the status of each Instant Articles submission on the edit page of your posts.

A best effort is made to generate valid Instant Article markup from your posts' content/metadata and publish it to Facebook. The plugin knows how to transform your posts' markup from a set of rules which forms a mapping between elements in you *source markup* and the valid *Instant Article components*. We refer to this “glue” between the two as the ***Transformer Rules***.

Built-in to the plugin are many [pre-defined transformer rules](https://github.com/Automattic/facebook-instant-articles-wp/blob/master/rules-configuration.json) which aims to cover standard WordPress installations. If your WordPress content contains elements which are not covered by the built-in ruleset, you can define your own additional rules to extend or override the defaults in the Settings of this plugin, under: **Plugin Configuration** > **Publishing Settings** > **Custom transformer rules**.

= Feed submission to Facebook =

Facebook has a review process where they verify that all Instant Articles are properly formatted, have content consistency with their mobile web counterparts, and adhere to their community standards and content policies. You will not be able to publish Instant Articles in Facebook until your feed has been approved.

It's important to note that if you use meta fields to add extra text, images or videos to your Posts, Facebook will expect you to add these to your Instant Articles output too. This plugin includes hooks to help you do that.

[See Facebook's documentation for full details of the submission process.](https://developers.facebook.com/docs/instant-articles)

Facebook requires a minimum number of articles in your feed before they will review it. Once your feed has been approved, new posts will automatically be taken live on Instant Articles, and existing posts will be taken live once you update them.

== Installation ==

= From your WordPress dashboard =
* Visit 'Plugins > Add New'
* Search for 'Facebook Instant Articles for WP'
* Activate the plugin on your Plugins page

= From WordPress.org =
* Download Facebook Instant Articles for WP
* Upload the uncompressed directory to '/wp-content/plugins/'
* Activate the plugin on your Plugins page

= Once Activated =
* Click on the 'Instant Articles' menu and follow the instructions to activate the plugin

== Frequently Asked Questions ==

= Why is there content from my post missing in the generated Instant Article? =

More likely than not, this is because there is markup in the body of your post that is not mapped to a recognized Instant Article component. On the “Edit Post” screen for your post, look for additional information about the *transformed* output shown within the **Facebook Instant Articles** module located at the bottom of the screen.

= In the Instant Articles module for my post, what does the “This post was transformed into an Instant Article with some warnings” message mean? =

When transforming your post into an Instant Article, this plugin will show warnings when it encounters content which might not be valid when published to Facebook. When you see this message, it is recommended to resolve each warning individually.

= What does the “No rules defined for ____ in the context of  ____” warning mean? =

This plugin transforms your post into an Instant Article by matching markup in your content to one of the [components available](https://github.com/facebook/facebook-instant-articles-sdk-php/blob/master/docs/QuickStart.md#transformer-classes) in Instant Articles markup. Although the plugin contains many [built-in rules](https://github.com/Automattic/facebook-instant-articles-wp/blob/master/rules-configuration.json) to handle common cases, there may be markup in your post which is not recognized by these existing rules. In this case, you may be required to define some of your own rules. See below for more details about where and how.

= How do I define my own transformer rules so that content from my site is rendered appropriately in an Instant Article? =

Your custom rules can be defined in the Settings of this plugin, under: **Plugin Configuration** > **Publishing Settings** > **Custom transformer rules**. More detailed instructions about all the options available is documented in the [Custom Transformer Rules](https://github.com/facebook/facebook-instant-articles-sdk-php/blob/master/docs/QuickStart.md#custom-transformer-rules) section of the Facebook Instant Articles SDK.

= I know of a custom transformer rule which is pretty common in the community. How can it be included by default in the plugin? =

You can propose popular transformer rules to be included in the plugin by [suggesting it on GitHub](https://github.com/Automattic/facebook-instant-articles-wp/issues/new).

= How do I post articles to Instant Articles after plugin is installed? =

You can re-publish existing articles (simply edit + save) or post new articles in order to submit them to Instant Articles. After you have 10 articles added, you will be able to submit them for review.

= How do I change the feed slug/URL if I'm using the RSS integration? =

To change the feed slug, set the constant INSTANT_ARTICLES_SLUG to whatever you like. If you do, remember to flush the rewrite rules afterwards.
By default it is set to `instant-articles` which usually will give you a feed URL set to `/feed/instant-articles`

= How do I flush the rewrite rules after changing the feed slug? =

Usually simply visiting the permalinks settings page in the WordPress dashboard will do the trick (/wp-admin/options-permalink.php)

== Screenshots ==

1. Customized transformer rules enabled on the main Settings page. The particular configuration shown here would cause `<u>` and `<bold>` tags in the source markup to be rendered in *italics* and **bold**, respectively, in the generated Instant Article.

== Changelog ==

= 3.1 =
* New on-boarding flow wizard
* Automattic URL claiming
* Submit for review from wizard
* Improved transformation rules
* Option to submit only articles without warnings
* Jetpack compatibility
* Added Jetpack carousel rules
* Compatibility layer for Get The Image plugin
* Fix for relative URL checking
* Fix for missing subtitles
* Fix for double call of wpautop
* Fix for loadHTML warnings
* Fix for get_cover_media function
* Fix to prevent publishing of password protected posts

props diegoquinteiro everton-rosario gemedet jacobarriola menzow rinatkhaziev srtfisher

= 3.0.1 =

* Fix overzealous escaping

= 3.0 =

* Fix versioning - use WP style
* Ignore hyperlinks with a #
* Allow filtering of post types used in the feed
* Use v2.6 of the Facebook API
* Coding standards & whitespace fixes
* Load Facebook SDK over HTTPS
* Avoid crawling of 404s
* Replace social embeds with interactives
* Use submission ID to check status in the admin
* Fix over-zealous escaping

props markbarnes, paulschreiber, diegoquinteiro, gemedet

= 2.1 =
* Fixes compatibility with PHP 5.4+
* Bug fixes

= 2.0 =
* Using Facebook Instant Articles SDK for PHP
* Added API integration

= 0.1 =
* Initial version
