=== WP Simple Rss Feed Reader ===
Contributors: Viancen
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=nieuwenhuizen%40gmail%2ecom&lc=NL&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: shortcodes, rssfeed, simple rss, rss reader, twitter
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 0.6.3

Plugin to view an rss feed on your page or post. Or add a feed as widget. Clean and simple.

== Description ==

Plugin to view an rss feed on your page or post. Or add a feed as widget. Clean and simple.


== Installation ==

1. Upload `wp-simple-rss-feed-reader.php` to your `/wp-content/plugins` folder
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the shortcode in your posts or pages: [simple-rss feed="http://www.yourfeed.com/myfeed.xml" limit=10]
4. Use the widget from the widget menu in the Wordpress admin pages
5. Use add your twitter tweets use url = http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=[Account Name]

== Changelog ==

= 0.1 =
* The first release
= 0.5 =
* first complete stable release with a widget functionality
= 0.6 =
* fixed the bug where feeds with oldschool & and ? sighns weren't working
= 0.6.1 =
* removed utf8 decoding, was causing trouble on special characters
= 0.6.2 =
* added a limit function to the shortcode
= 0.6.3 =
* added the option to shorten read more link (widget)
* added the option to not display descriptions (widget)
