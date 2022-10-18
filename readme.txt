=== Instant Articles for WP ===
Contributors: trrine, olethomas, bjornjohansen, dekode, GaryJ, automattic, fb
Tags: instant-articles, fb, mobile, instant articles
Requires at least: 4.7
Requires PHP: 7.1
Tested up to: 6.0.3
Stable tag: 5.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enable Instant Articles for Facebook on your WordPress site.

== Description ==

This plugin adds support for Instant Articles for Facebook, which is a new way for publishers to distribute fast, interactive stories on Facebook. Instant Articles are preloaded in the Facebook mobile app so they load instantly.

With the plugin active, a new menu will be available for you to connect to your Facebook Page and start publishing your Instant Articles. You'll also see the status of each Instant Articles submission on the edit page of your posts.

A best effort is made to generate valid Instant Article markup from your posts' content/metadata and publish it to Facebook. The plugin knows how to transform your posts' markup from a set of rules which forms a mapping between elements in you *source markup* and the valid *Instant Article components*. We refer to this “glue” between the two as the ***Transformer Rules***.

Built-in to the plugin are many [pre-defined transformer rules](https://github.com/Automattic/fb-instant-articles/blob/master/rules-configuration.json) which aims to cover standard WordPress installations. If your WordPress content contains elements which are not covered by the built-in ruleset, you can define your own additional rules to extend or override the defaults in the Settings of this plugin, under: **Plugin Configuration** > **Publishing Settings** > **Custom transformer rules**.

= Maintenance =
Support for this plugin is provided for PHP 8 migrations, but both Automattic/WPVIP and Facebook/Meta have stopped all active development. At this point in the product lifecycle, publishers should be advised to stop using this plugin, and instead, use direct site traffic for Facebook content.
Meta have announced that Instant Articles will have all support dropped at April 2023.

= Access to Instant Articles =

The current criteria for access to Instant Articles are:
  - your Facebook Page must have an established presence of at least 90 days
  - your content adheres to the [Instant Article Policies](https://developers.facebook.com/docs/instant-articles/policy/)

Before your Instant Articles can be published on Facebook, your feed must undergo an initial review and approval. Facebook requires a minimum number of 10 articles in your feed before being eligible for review. The review process checks that your draft Instant Articles are properly formatted, have content consistency with their mobile web counterparts, and adhere to the [community standards](https://www.facebook.com/communitystandards/) and [content policies](https://www.facebook.com/help/publisher/1348682518563619).

It's important to note that if you use meta fields to add extra text, images or videos to your Posts, Facebook will expect you to add these to your Instant Articles output too. This plugin includes hooks to help you do that.

[See Facebook's documentation for full details of the submission process.](https://developers.facebook.com/docs/instant-articles)

Once your feed has been approved, new posts will automatically be taken live on Instant Articles, and existing posts will be taken live once you update them. In order to stay in the Instant Articles program, you must remain active by creating new content and maintaining a minimal readership.

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

This plugin transforms your post into an Instant Article by matching markup in your content to one of the [components available](https://github.com/facebook/facebook-instant-articles-sdk-php/blob/master/docs/QuickStart.md#transformer-classes) in Instant Articles markup. Although the plugin contains many [built-in rules](https://github.com/Automattic/fb-instant-articles/blob/master/rules-configuration.json) to handle common cases, there may be markup in your post which is not recognized by these existing rules. In this case, you may be required to define some of your own rules. See below for more details about where and how.

= How do I define my own transformer rules so that content from my site is rendered appropriately in an Instant Article? =

Your custom rules can be defined in the Settings of this plugin, under: **Plugin Configuration** > **Publishing Settings** > **Custom transformer rules**. More detailed instructions about all the options available is documented in the [Custom Transformer Rules](https://github.com/facebook/facebook-instant-articles-sdk-php/blob/master/docs/QuickStart.md#custom-transformer-rules) section of the Facebook Instant Articles SDK.

= I know of a custom transformer rule which is pretty common in the community. How can it be included by default in the plugin? =

You can propose popular transformer rules to be included in the plugin by [suggesting it on GitHub](https://github.com/Automattic/fb-instant-articles/issues/new).

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
### 5.0.1 - 2022-10-18

- Refresh dependency that handles fixing of transformer encodings.

### 5.0.0 - 2022-10-18
**Minimum PHP requirement is increased from PHP 5.4 to PHP 7.1.**
**Minimum WordPress requirement is increased from WP 4.3.0 to WP 4.7.0.**

- [#683](https://github.com/Automattic/fb-instant-articles/pull/683) Fix wizard option dynamic properties for PHP 8.2.
- [#831](https://github.com/Automattic/fb-instant-articles/pull/831) Fix the way filters are applied in get_cover_media().
- [#1025](https://github.com/Automattic/fb-instant-articles/pull/1025) Support for installing as Composer Package.
- [#1058](https://github.com/Automattic/fb-instant-articles/pull/1058) Allow post content caching to be disabled.
- [#1073](https://github.com/Automattic/fb-instant-articles/pull/1073) Update release.sh.
- [#1076](https://github.com/Automattic/fb-instant-articles/pull/1076) Reduce plugin's cache burden for remote requests.
- [#1082](https://github.com/Automattic/fb-instant-articles/pull/1082) Fix: pass the missing $post param to the get_the_excerpt filter.
- [#1092](https://github.com/Automattic/fb-instant-articles/pull/1092) Add support for PHP 8.
- [#1095](https://github.com/Automattic/fb-instant-articles/pull/1095) Add GitHub workflow to mark issues and PR as stale.
- [#1097](https://github.com/Automattic/fb-instant-articles/pull/1097) Actions: Remove separate PHP 8.2 composer install.
- [#1098](https://github.com/Automattic/fb-instant-articles/pull/1098) Improve tests.
- [#1099](https://github.com/Automattic/fb-instant-articles/pull/1099) Basic code maintenance improvements.

### 4.2.1 - 2020-06-10
- [#1049](https://github.com/automattic/fb-instant-articles/pull/1049) Add support for new guttenberg layout elements (@diegoquinteiro)
- [#1012](https://github.com/automattic/fb-instant-articles/pull/1012) update composer modules (@paulschreiber)
- [#1001](https://github.com/automattic/fb-instant-articles/pull/1001) Load functions which rely on `plugins_loaded` action hook in VIP Go environment (@paulschreiber)
- [#990](https://github.com/automattic/fb-instant-articles/pull/990) avoid undefined index error in $display_warning_column (@paulschreiber)
- [#1002](https://github.com/automattic/fb-instant-articles/pull/1002) add Comscore plugin (@paulschreiber)
- [#1007](https://github.com/automattic/fb-instant-articles/pull/1007) Update Facebook SDK versions (@paulschreiber)

### 4.2.0 - 2018-11-29
- [#997](https://github.com/automattic/fb-instant-articles/pull/997) Removed fields from deprecated feature to enable comments/likes/share from media content (@everton-rosario)

### 4.1.1 - 2018-07-17
- [#962](https://github.com/automattic/fb-instant-articles/pull/962) Updating description for the plugin on website (@Blakomen)
- [#1](https://github.com/automattic/fb-instant-articles/pull/1) Merging into local (@diegoquinteiro, @everton-rosario, @algmelo)
- [#957](https://github.com/automattic/fb-instant-articles/pull/957) Delete current transient with autoload=yes. (@everton-rosario, @algmelo)

### 4.1.0 2018-07-02
- [#937](https://github.com/automattic/fb-instant-articles/pull/937) Add token-less re-scrape with request signature (@diegoquinteiro)
- [#952](https://github.com/automattic/fb-instant-articles/pull/952) Updating description for the plugin (@Blakomen)
- [#910](https://github.com/automattic/fb-instant-articles/pull/910) Add PassThroughRule for Grammarly (@robbiet480)
- [#941](https://github.com/automattic/fb-instant-articles/pull/941) Add support for GTM4WP (@robbiet480)
- [#898](https://github.com/automattic/fb-instant-articles/pull/898) Added tranformation warning indicator column (@Kluny)
- [#904](https://github.com/automattic/fb-instant-articles/pull/904) Enables setup for updating/invalidating articles cache from Facebook scraper (@everton-rosario, @diegoquinteiro)
- [#896](https://github.com/automattic/fb-instant-articles/pull/896) feat(playbuzz): support for the next major playbuzz plugin version (@playbuzz)
- [#740](https://github.com/automattic/fb-instant-articles/pull/740) (wizard) Update wording about AMP markup gen (@Djiit)
- [#870](https://github.com/automattic/fb-instant-articles/pull/870) [bugfix] calling get_article_style to apply the style filter (@vkama)

### 4.0.6 - 2017-12-04
- [#814](https://github.com/automattic/fb-instant-articles/pull/814) Add default rule for Twitter blockquote (@pestevez)
- [#806](https://github.com/automattic/fb-instant-articles/pull/806) Clarify requirement of site connection in README (@pestevez)
- [#798](https://github.com/automattic/fb-instant-articles/pull/798) remove article rescrape code (@timjacobi)
- [#777](https://github.com/automattic/fb-instant-articles/pull/777) update instructions (@timjacobi)
- [#778](https://github.com/automattic/fb-instant-articles/pull/778) Add label definitions (@timjacobi)
- [#792](https://github.com/automattic/fb-instant-articles/pull/792) Change meta box and readme so user doesn't think IAs are actually submitted to Facebook (@timjacobi)
- [#797](https://github.com/automattic/fb-instant-articles/pull/797) Display error message if meta box can't be loaded (@timjacobi)
- [#775](https://github.com/automattic/fb-instant-articles/pull/775) Adds caching to instant_articles_embed_oembed_html (@emrikol)

### 4.0.5 - 2017-08-24
- [#750](https://github.com/automattic/fb-instant-articles/pull/750) Fix query limit and escaping on AMP generation (@diegoquinteiro)

### 4.0.4 - 2017-07-27
- [#738](https://github.com/automattic/fb-instant-articles/pull/738) Add changelog to script (@diegoquinteiro)
- [#737](https://github.com/automattic/fb-instant-articles/pull/737) Updates IA SDK to 1.6.2 and dependencies (@diegoquinteiro)

### 4.0.3 - 2017-07-20
- [#725](https://github.com/automattic/fb-instant-articles/pull/725) Restore the $current_blog global and fix the post ID reference (@kasparsd)

### 4.0.2 - 2017-06-30
- [#708](https://github.com/automattic/fb-instant-articles/pull/708) Do not process non-post pages. Fixes #707 (@diegoquinteiro)
- [#709](https://github.com/automattic/fb-instant-articles/pull/709) Add cache layer for avoiding transforming the article at page render (@diegoquinteiro)

### 4.0.1 - 2017-06-28
- [#706](https://github.com/automattic/fb-instant-articles/pull/706) Check for array index before using it (@diegoquinteiro)
- [#705](https://github.com/automattic/fb-instant-articles/pull/705) Enable deletion of JSON AMP Style and removes an undef index (@vkama)
- [#704](https://github.com/automattic/fb-instant-articles/pull/704) Fixed several php notices. Also fixed a bug in should_subit_post() (@vkama)

### 4.0.0 - 2017-06-27
- [#702](https://github.com/automattic/fb-instant-articles/pull/702) V4.0 (@vkama, @diegoquinteiro, @everton-rosario, @sakatam, @abdusfauzi)
- [#647](https://github.com/automattic/fb-instant-articles/pull/647) Fix "Empty string supplied as input" bug (@abdusfauzi)
- [#680](https://github.com/automattic/fb-instant-articles/pull/680) Adding support to IA->AMP conversion (@vkama)
- [#649](https://github.com/automattic/fb-instant-articles/pull/649) allow markups in footer/copyright (@sakatam)
- [#643](https://github.com/automattic/fb-instant-articles/pull/643) Open Graph Ingestion Flow (@diegoquinteiro, @everton-rosario)
- [#629](https://github.com/automattic/fb-instant-articles/pull/629) Update Google Analytics compat (@dlackty)

### 3.3.4 - 2017-05-10
- [#602](https://github.com/automattic/fb-instant-articles/pull/602) Add post ID to `instant_articles_content` filter (@carlalexander)
- [#622](https://github.com/automattic/fb-instant-articles/pull/622) Adds ver=<VERSION_TOKEN> to invalidate plugin resource files (@everton-rosario)
- [#645](https://github.com/automattic/fb-instant-articles/pull/645) Fix wp_is_post_autosave() parameter value (@abdusfauzi)
- [#623](https://github.com/automattic/fb-instant-articles/pull/623) Allow for alternative file path for json rules (@andykillen)
- [#624](https://github.com/automattic/fb-instant-articles/pull/624) fix merge damage (@sakatam)
- [#616](https://github.com/automattic/fb-instant-articles/pull/616) Extracted anonymous inline function (@everton-rosario)
- [#609](https://github.com/automattic/fb-instant-articles/pull/609) Apester plugin support (@oraricha)
- [#617](https://github.com/automattic/fb-instant-articles/pull/617) Switch on/off configuration for enabling comments/likes by default (@everton-rosario)
- [#612](https://github.com/automattic/fb-instant-articles/pull/612) Add an option to set a footer (@sakatam)

### 3.3.2 - 2017-02-13
- [#418](https://github.com/automattic/fb-instant-articles/pull/418) Call instant_articles_post_published hook to determine published status (@apeschar)
- [#611](https://github.com/automattic/fb-instant-articles/pull/611) capability to toggle RTL option (@sakatam)
- [#604](https://github.com/automattic/fb-instant-articles/pull/604) Fix typo for $provider_url at L42, L44 and L46 (@abdusfauzi)

### 3.3.1 - 2017-01-31
- [#598](https://github.com/automattic/fb-instant-articles/pull/598) Fix typo on script (@diegoquinteiro)
- [#595](https://github.com/automattic/fb-instant-articles/pull/595) Add transformer rule for cite element (@davebonds)
- [#59](https://github.com/automattic/fb-instant-articles/pull/59) add vimeo.com into oEmbed $provider_name (@abdusfauzi)
- [#597](https://github.com/automattic/fb-instant-articles/pull/597) Don't try to load embeds compat unless we're in Instant Articles context (@diegoquinteiro, @rinatkhaziev)
- [#568](https://github.com/automattic/fb-instant-articles/pull/568) Add `is_transforming_instant_article()` conditional (@goldenapples)
- [#287](https://github.com/automattic/fb-instant-articles/pull/287) Ad de_DE (@swissky)
- [#578](https://github.com/automattic/fb-instant-articles/pull/578) Change save_post priority (@cmjaimet)
- [#592](https://github.com/automattic/fb-instant-articles/pull/592) Add missing information to composer.json (@onnimonni)
- [#562](https://github.com/automattic/fb-instant-articles/pull/562) Add release script (@diegoquinteiro)

### 3.3 - 2016-12-16
- [#560](https://github.com/automattic/fb-instant-articles/pull/560) Remove deprecated rules and add rules for imgs inside links (@diegoquinteiro)
- [#525](https://github.com/automattic/fb-instant-articles/pull/525) Update WordPress.com stats code to use FBIA SDK. (@Automattic)
- [#535](https://github.com/automattic/fb-instant-articles/pull/535) Fixes #515 - Enables Playbuzz plugin out of the box (@everton-rosario)

### 3.2.2 - 2016-11-29
- [#543](https://github.com/automattic/fb-instant-articles/pull/543) Fix variable name (@diegoquinteiro)

### 3.2.1 - 2016-11-25
- [#538](https://github.com/automattic/fb-instant-articles/pull/538) Show message on meta-box when post is filtered out (@diegoquinteiro)
- [#504](https://github.com/automattic/fb-instant-articles/pull/504) Add instant_articles_should_submit_post filter hook  to allow developers control of whether the post should be submitted to IA (@rinatkhaziev)
- [#498](https://github.com/automattic/fb-instant-articles/pull/498) Release v3.2 (@Automattic)
- [#437](https://github.com/automattic/fb-instant-articles/pull/437) Blocks publishing of articles with transformation warnings (@diegoquinteiro, @everton-rosario)
- [#423](https://github.com/automattic/fb-instant-articles/pull/423) Fixes #205 - Caption in <p> tag (@everton-rosario)
- [#435](https://github.com/automattic/fb-instant-articles/pull/435) Setup Wizard copy updates (@demoive)
- [#497](https://github.com/automattic/fb-instant-articles/pull/497) Getter was renamed on SDK, updating here too (@diegoquinteiro)
- [#417](https://github.com/automattic/fb-instant-articles/pull/417) Support for Playbuzz plugin (@everton-rosario)
- [#416](https://github.com/automattic/fb-instant-articles/pull/416) Use the instant_articles_post_types filter when registering metaboxes (@technosailor)

### 3.2 - 2016-10-05
- [#484](https://github.com/automattic/fb-instant-articles/pull/484) Apply the second argument to `the_title` (@srtfisher)
- [#481](https://github.com/automattic/fb-instant-articles/pull/481) Token Invalidation flow (@everton-rosario)
- [#462](https://github.com/automattic/fb-instant-articles/pull/462) Migration from old fb sdk (@evertonsario)
- [#436](https://github.com/automattic/fb-instant-articles/pull/436) Wp tests migration (@everton-rosario)
- [#421](https://github.com/automattic/fb-instant-articles/pull/421) Fixes #408 adding rules for gallery (@everton-rosario)
- [#376](https://github.com/automattic/fb-instant-articles/pull/376) Added rule configuration for Instagram embed (@everton-rosario)
- [#316](https://github.com/automattic/fb-instant-articles/pull/316) Add development mode support to status meta box (@simonengelhardt)

### 3.1.3 - 2016-09-19
- [#469](https://github.com/automattic/fb-instant-articles/pull/469) Release stuff for v3.1.3 (@Automattic)
- [#450](https://github.com/automattic/fb-instant-articles/pull/450) Do not block page selection if URL is not claimed (@diegoquinteiro)
- [#464](https://github.com/automattic/fb-instant-articles/pull/464) Fixes #452 - Use of empty() on PHP prior to v5.5 (@demoive)
- [#465](https://github.com/automattic/fb-instant-articles/pull/465) Relative CSS to fix missing icons (@everton-rosario)
- [#458](https://github.com/automattic/fb-instant-articles/pull/458) Fix expiring token blocking users on page selection state (@diegoquinteiro)
- [#403](https://github.com/automattic/fb-instant-articles/pull/403) fix travis.ci build (@sakatam)
- [#425](https://github.com/automattic/fb-instant-articles/pull/425) Final release of v3.1 (@Automattic)
- [#385](https://github.com/automattic/fb-instant-articles/pull/385) Implemented compat for the get-the-image plugin (@everton-rosario)
- [#392](https://github.com/automattic/fb-instant-articles/pull/392) Load functions which rely on `plugins_loaded` action hook in wp.com e… (@nprasath002)
- [#402](https://github.com/automattic/fb-instant-articles/pull/402) Properly implemented the get_cover_media function. (@menzow)
- [#420](https://github.com/automattic/fb-instant-articles/pull/420) Article with password protection (@everton-rosario)
- [#409](https://github.com/automattic/fb-instant-articles/pull/409) Check for undefined index (@gemedet)

### 3.1 - 2016-08-17
- [#413](https://github.com/automattic/fb-instant-articles/pull/413) Fix lingering reference to the old settings page on meta-box (@diegoquinteiro)
- [#412](https://github.com/automattic/fb-instant-articles/pull/412) Content update (@diegoquinteiro)
- [#400](https://github.com/automattic/fb-instant-articles/pull/400) New flow (@diegoquinteiro, @everton-rosario)
- [#399](https://github.com/automattic/fb-instant-articles/pull/399) Revert "Redirect settings" (@philipjohn)
- [#394](https://github.com/automattic/fb-instant-articles/pull/394) Apply "the_title" filter (@everton-rosario)
- [#389](https://github.com/automattic/fb-instant-articles/pull/389) Redirect settings (@everton-rosario)
- [#387](https://github.com/automattic/fb-instant-articles/pull/387) Fix loadHTML warning (@gemedet)
- [#378](https://github.com/automattic/fb-instant-articles/pull/378) Added rules for jetpack carousel component (@everton-rosario)
- [#379](https://github.com/automattic/fb-instant-articles/pull/379) Don't call wpautop directly when trying to prepare content for transformation. (@rinatkhaziev)
- [#383](https://github.com/automattic/fb-instant-articles/pull/383) Fix updating published posts result in error (@chrisadas)
- [#375](https://github.com/automattic/fb-instant-articles/pull/375) Commit blocker option - No warnings in transformer to publish (@everton-rosario)
- [#374](https://github.com/automattic/fb-instant-articles/pull/374) Jetpack plugin compatibility - Transformation rules (@everton-rosario)
- [#371](https://github.com/automattic/fb-instant-articles/pull/371) Migrated take_live parameter to the published (@everton-rosario)
- [#366](https://github.com/automattic/fb-instant-articles/pull/366) InteractiveInsideParagraphRule support and configuration (@everton-rosario)
- [#367](https://github.com/automattic/fb-instant-articles/pull/367) Add subtitle to $header when rendering post to IA (@jacobarriola)
- [#360](https://github.com/automattic/fb-instant-articles/pull/360) Improve rules for Images inside Paragraphs and Interactive elements (@everton-rosario)
- [#356](https://github.com/automattic/fb-instant-articles/pull/356) Ensure that relative URL checking has a host and a path. (@srtfisher)

### 3.0.1 - 2016-07-13
- [#359](https://github.com/automattic/fb-instant-articles/pull/359) Release 3.0.1 (@Automattic)
- [#358](https://github.com/automattic/fb-instant-articles/pull/358) Revert "escaping and sanitization fixes" (@diegoquinteiro)

### 3.0 - 2016-07-12
- [#355](https://github.com/automattic/fb-instant-articles/pull/355) Release v3.0 (@Automattic)
- [#354](https://github.com/automattic/fb-instant-articles/pull/354) Remove  to avoid breaking  elements. See #331 (@Automattic)
- [#353](https://github.com/automattic/fb-instant-articles/pull/353) Store and use submission ID when available to fetch the status (@diegoquinteiro)
- [#329](https://github.com/automattic/fb-instant-articles/pull/329) always use HTTPS to load JavaScript SDK; update to 2.6 (@paulschreiber)
- [#327](https://github.com/automattic/fb-instant-articles/pull/327) use v2.6 of Facebook API (@paulschreiber)
- [#263](https://github.com/automattic/fb-instant-articles/pull/263) Escape ampersands before displaying on settings page (@gemedet)
- [#301](https://github.com/automattic/fb-instant-articles/pull/301) Check if the global $post object has been set (@srtfisher)
- [#290](https://github.com/automattic/fb-instant-articles/pull/290) Fix relative URL issue with oembeds not using absolute URLs (@stuartshields)
- [#255](https://github.com/automattic/fb-instant-articles/pull/255) Support <mark> tag (@demoive)
- [#313](https://github.com/automattic/fb-instant-articles/pull/313) Fixed statically-called loadHTML() (@gemedet)
- [#258](https://github.com/automattic/fb-instant-articles/pull/258) Width and Height for Interactive in default rules (@gemedet)
- [#343](https://github.com/automattic/fb-instant-articles/pull/343) Only publish posts (ignores pages and other post types) (@diegoquinteiro)
- [#342](https://github.com/automattic/fb-instant-articles/pull/342) Replace SocialEmbed rules with Interactive rules (@diegoquinteiro)
- [#330](https://github.com/automattic/fb-instant-articles/pull/330) fix whitespace in select/optgroup tags (@paulschreiber)
- [#331](https://github.com/automattic/fb-instant-articles/pull/331) WordPress coding standards fixes (@paulschreiber)
- [#328](https://github.com/automattic/fb-instant-articles/pull/328) Fix whitespace to match WordPress coding standards (@paulschreiber)
- [#285](https://github.com/automattic/fb-instant-articles/pull/285) Remove hyperlinks beginning with a # (@markbarnes)
- [#341](https://github.com/automattic/fb-instant-articles/pull/341) Avoid crawler to cache a 404 (@diegoquinteiro)
- [#305](https://github.com/automattic/fb-instant-articles/pull/305) Allow filtering of the post types used in the feed (@Automattic)
- [#308](https://github.com/automattic/fb-instant-articles/pull/308) Fixed error message: Deprecated: Non-static method DOMDocument::loadH… (@everton-rosario)
- [#286](https://github.com/automattic/fb-instant-articles/pull/286) Add GitHub Issue and PR templates (@simonengelhardt)
- [#177](https://github.com/automattic/fb-instant-articles/pull/177) Use H1 for header title and cover image caption (@gemedet)

### 2.11 - 2016-05-17
- [#257](https://github.com/automattic/fb-instant-articles/pull/257) Fix scheduled posts with empty IA content (@Aioros)
- [#273](https://github.com/automattic/fb-instant-articles/pull/273) Copy updated to align better with scheduled posts (@demoive)
- [#271](https://github.com/automattic/fb-instant-articles/pull/271) Checks if it have the node to be shown as warning body (@everton-rosario)
- [#264](https://github.com/automattic/fb-instant-articles/pull/264) Add new application header (@diegoquinteiro)

### 2.10 - 2016-04-29
- [#179](https://github.com/automattic/fb-instant-articles/pull/179) Graphics for banner and icon (@demoive)
- [#183](https://github.com/automattic/fb-instant-articles/pull/183) Wordpress -> WordPress (@lesterchan)
- [#213](https://github.com/automattic/fb-instant-articles/pull/213) Labels and documentation changes re: custom transformer rules (@demoive)

### 2.9 - 2016-04-19
- [#174](https://github.com/automattic/fb-instant-articles/pull/174) Prevents <figcaptions> from being added to images that were not saved with captions (@bobderrico80)
- [#176](https://github.com/automattic/fb-instant-articles/pull/176) Remove unused image.caption property from ImageRule (@demoive)
- [#180](https://github.com/automattic/fb-instant-articles/pull/180) Fix empty articles being uploaded on bulk update (@diegoquinteiro)
- [#167](https://github.com/automattic/fb-instant-articles/pull/167) Fixes #116 encoding problem (@everton-rosario)
- [#173](https://github.com/automattic/fb-instant-articles/pull/173) Fixed empty link to Instant Articles signup (@demoive)

### 2.8 - 2016-04-14
- [#151](https://github.com/automattic/fb-instant-articles/pull/151) Stop publishing drafts (@diegoquinteiro)

### 2.6 - 2016-04-13
- [#115](https://github.com/automattic/fb-instant-articles/pull/115) Added information about initial publishing (@msurguy)
- [#105](https://github.com/automattic/fb-instant-articles/pull/105) Update template-settings-info.php (@piscis)

### 2.1 - 2016-04-11
- [#98](https://github.com/automattic/fb-instant-articles/pull/98) Fixes #72 (@diegoquinteiro)
- [#90](https://github.com/automattic/fb-instant-articles/pull/90) Change readme.txt to reflect changes in 2.0 (@diegoquinteiro)
- [#87](https://github.com/automattic/fb-instant-articles/pull/87) Clean unused files + fixes for 5.4 compatibility (@diegoquinteiro)

### 2.0 - 2016-04-06
- [#70](https://github.com/automattic/fb-instant-articles/pull/70) Use SDK engine (@diegoquinteiro)

### 0.2 - 2016-03-09
- [#41](https://github.com/automattic/fb-instant-articles/pull/41) Add support for subtitles through the filter instant_articles_subtitle (@bjornjohansen)
- [#39](https://github.com/automattic/fb-instant-articles/pull/39) Jetpack compat: YouTube and Facebook embeds (@bjornjohansen)
- [#22](https://github.com/automattic/fb-instant-articles/pull/22) Migrate the wpcom-helper.php from WordPress.com. (@Automattic)

== Upgrade Notice ==

### 5.0.0
- Minimum PHP requirement is increased from PHP 5.4 to PHP 7.1.
- Minimum WordPress requirement is increased from WP 4.3.0 to WP 4.7.0.
