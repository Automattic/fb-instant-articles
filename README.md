[![CS & Lint](https://github.com/Automattic/fb-instant-articles/actions/workflows/cs-lint.yml/badge.svg)](https://github.com/Automattic/fb-instant-articles/actions/workflows/cs-lint.yml) [![Integration Tests](https://github.com/Automattic/fb-instant-articles/actions/workflows/tests.yml/badge.svg)](https://github.com/Automattic/fb-instant-articles/actions/workflows/tests.yml) [![Stale monitor](https://github.com/Automattic/fb-instant-articles/actions/workflows/stale.yml/badge.svg)](https://github.com/Automattic/fb-instant-articles/actions/workflows/stale.yml)
# Instant Articles for WP

Enable [Instant Articles for Facebook](https://developers.facebook.com/docs/instant-articles) on your WordPress site.

## Maintenance 🚩
Support for this plugin is provided for PHP 8 migrations, but both Automattic/WPVIP and Facebook/Meta have stopped all active development. At this point in the product lifecycle, publishers should be advised to stop using this plugin, and instead, use direct site traffic for Facebook content.
Meta have announced that Instant Articles will have all support dropped at April 2023.

## Description

This plugin adds support for Instant Articles for Facebook, which is a new way for publishers to distribute fast, interactive stories on Facebook. Instant Articles are preloaded in the Facebook mobile app so they load instantly.

With the plugin active, a new menu will be available for you to connect to your Facebook Page and start publishing your Instant Articles. You'll also see the status of each Instant Articles submission on the edit page of your posts.

A best effort is made to generate valid Instant Article markup from your posts' content/metadata and publish it to Facebook. The plugin knows how to transform your posts' markup from a set of rules which forms a mapping between elements in you *source markup* and the valid *Instant Article components*. We refer to this “glue” between the two as the ***Transformer Rules***.

Built-in to the plugin are many [pre-defined transformer rules](https://github.com/Automattic/facebook-instant-articles-wp/blob/master/rules-configuration.json) which aims to cover standard WordPress installations. If your WordPress content contains elements which are not covered by the built-in ruleset, you can define your own additional rules to extend or override the defaults in the Settings of this plugin, under: **Plugin Configuration** > **Publishing Settings** > **Custom transformer rules**.

## Access to Instant Articles

The current criteria for access to Instant Articles are:

- your Facebook Page must have an established presence of at least 90 days
- your content adheres to the [Instant Article Policies](https://developers.facebook.com/docs/instant-articles/policy/)

Before your Instant Articles can be published on Facebook, your feed must undergo an initial review and approval. Facebook requires a minimum number of 10 articles in your feed before being eligible for review. The review process checks that your draft Instant Articles are properly formatted, have content consistency with their mobile web counterparts, and adhere to the [community standards](https://www.facebook.com/communitystandards/) and [content policies](https://www.facebook.com/help/publisher/1348682518563619).

It's important to note that if you use meta fields to add extra text, images or videos to your Posts, Facebook will expect you to add these to your Instant Articles output too. This plugin includes hooks to help you do that.

[See Facebook's documentation for full details of the submission process.](https://developers.facebook.com/docs/instant-articles)

Once your feed has been approved, new posts will automatically be taken live on Instant Articles, and existing posts will be taken live once you update them. In order to stay in the Instant Articles program, you must remain active by creating new content and maintaining a minimal readership.

## Installation

1. Run `composer install` on the root of the plugin folder. Make sure you have [Composer](https://github.com/composer/composer) installed.
2. Upload the folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

## Frequently Asked Questions

**Why is there content from my post missing in the generated Instant Article?**

More likely than not, this is because there is markup in the body of your post that is not mapped to a recognized Instant Article component. On the “Edit Post” screen for your post, look for additional information about the *transformed* output shown within the **Facebook Instant Articles** module located at the bottom of the screen.

**Why doesn't my post appear in the list of Instant Articles in the publisher tools?**
Your posts are imported to your library when they are shared on Facebook for the first time.

Alternatively, you can trigger a manual scrape by pasting your URL on our [Share Debugger](http://developers.facebook.com/tools/debug)

Only Instant Articles with URLs in [domains you have claimed](https://developers.facebook.com/docs/instant-articles/guides/publishertools#connect) will show up in the Publishing Tools section.
**In the Instant Articles module for my post, what does the “This post was transformed into an Instant Article with some warnings” message mean?**

When transforming your post into an Instant Article, this plugin will show warnings when it encounters content which might not be valid when published to Facebook. When you see this message, it is recommended to resolve each warning individually.

**What does the “No rules defined for ____ in the context of  ____” warning mean?**

This plugin transforms your post into an Instant Article by matching markup in your content to one of the [components available](https://github.com/facebook/facebook-instant-articles-sdk-php/blob/master/docs/QuickStart.md#transformer-classes) in Instant Articles markup. Although the plugin contains many [built-in rules](https://github.com/Automattic/facebook-instant-articles-wp/blob/master/rules-configuration.json) to handle common cases, there may be markup in your post which is not recognized by these existing rules. In this case, you may be required to define some of your own rules. See below for more details about where and how.

**How do I define my own transformer rules so that content from my site is rendered appropriately in an Instant Article?**

Your custom rules can be defined in the Settings of this plugin, under: **Plugin Configuration** > **Publishing Settings** > **Custom transformer rules**. More detailed instructions about all the options available is documented in the [Custom Transformer Rules](https://github.com/facebook/facebook-instant-articles-sdk-php/blob/master/docs/QuickStart.md#custom-transformer-rules) section of the Facebook Instant Articles SDK.

**I know of a custom transformer rule which is pretty common in the community. How can it be included by default in the plugin?**

You can propose popular transformer rules to be included in the plugin by [suggesting it on GitHub](https://github.com/Automattic/facebook-instant-articles-wp/issues/new).

**How do I post articles to Instant Articles after plugin is installed?**

In order to import your posts to your Instant Articles library on Facebook you need to [Connect Your Site](https://developers.facebook.com/docs/instant-articles/guides/publishertools#connect) first. Then either share your posts on your page or use the [Sharing Debugger](https://developers.intern.facebook.com/tools/debug/sharing/) to scrape them. After you have 10 articles imported, you will be able to submit them for review.

**How do I change the feed slug/URL if I'm using the RSS integration?**

To change the feed slug, set the constant INSTANT_ARTICLES_SLUG to whatever you like. If you do, remember to flush the rewrite rules afterwards.
By default it is set to `instant-articles` which usually will give you a feed URL set to `/feed/instant-articles`

**How do I flush the rewrite rules after changing the feed slug?**

Usually simply visiting the permalinks settings page in the WordPress dashboard will do the trick (/wp-admin/options-permalink.php)

## Changelog

Please visit the [changelog](https://github.com/Automattic/fb-instant-articles/blob/develop/CHANGELOG.md).
