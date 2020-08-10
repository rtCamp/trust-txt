=== Trust.txt Manager ===
Author URI: https://rtcamp.com
Contributors: rtcamp, mangeshp, scodtt, journallist
Plugin URI: https://github.com/rtcamp/trust-txt
Tags: Trust.txt
Requires at least: 4.9
Tested up to: 5.4
Requires PHP: 5.3
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: trust-txt

Create, manage, and validate your Trust.txt from within WordPress, just like any other content asset. Requires PHP 5.3+ and WordPress 4.9+.

== Description ==

Create, manage, and validate your Trust.txt from within WordPress, just like any other content asset. Requires PHP 5.3+ and WordPress 4.9+.
This plugin uses the [Ads.txt Manager](https://github.com/10up/ads-txt) codebase as a starting point.

=== What is Trust.txt? ===

Trust.txt is an effort by [JournalList](https://journallist.net/about) to systematically and transparently disclose connections between journalists, publishers & associations.
The concept of a trust.txt file borrows heavily from two previous very successful efforts improving the overall experience of the internet: robots.txt and ads.txt.
With both, website publishers are able to create a small and very manageable file that they have full control over that helps platforms and advertisers improve the overall ecosystem, and thereby the experience for users. So it is with trust.txt.
This plugin provides a way to create & manage your trust.txt from within WordPress, just like any other content asset. The validation logic baked into the plugin helps avoid malformed records.

=== Technical Notes ===

* Requires PHP 5.3+.
* Requires WordPress 4.9+. Older versions of WordPress will not display any syntax highlighting and may break JavaScript and/or be unable to localize the plugin.
* Rewrites need to be enabled. Without rewrites, WordPress cannot know to supply /trust.txt when requested.
* Your site URL must not contain a path (e.g. https://example.com/site/ or path-based multisite installs). While the plugin will appear to function in the WP admin, it will not display the contents at https://example.com/site/trust.txt. This is because the plugin enforces [the specification](https://journallist.net/reference-document-for-trust-txt-specifications) as defined by JournalList, which requires that the trust.txt file be located at the root of a domain or subdomain.

=== Can I use this with multisite? ===

Yes! However, if you are using a subfolder installation it will only work for the main site. This is because you can only have one Trust.txt for a given domain or subdomain per the [Trust.txt spec].  Our recommendation is to only activate Trust.txt Manager per-site.

== Screenshots ==

1. Example of editing a Trust.txt file with errors and a link to browse Trust.txt file revisions.
2. Example of comparing Trust.txt file revisions.
3. Example of comparing two disparate Trust.txt file revisions.

== Installation ==
1. Install and activate this plugin as per usual.
2. Go to Settings > Trust.txt and add the records you need. Ref: [trust.txt specification details](https://journallist.net/reference-document-for-trust-txt-specifications).
3. Your trust.txt file will appear at yoursite.com/trust.txt
4. Make sure to remove or rename any pre-existing trust.txt file from your web root as this plugin will NOT override it with the changes you make from the WordPress interface.

Note: If you already have an existing Trust.txt file in the web root, the plugin will not read in the contents of the respective files, and changes you make in WordPress admin will not overwrite contents of the physical files.

You will need to rename or remove the existing Trust.txt file (keeping a copy of the records it contains to put into the new settings screen) before you will be able to see any changes you make to Trust.txt inside the WordPress admin.

== Changelog ==

= 1.0 =
* Initial plugin release

== Credits ==

* [10up](https://10up.com/) for developing the [Ads.txt Manager](https://github.com/10up/ads-txt) this plugin is largely based off of.
* [mangeshp](http://profiles.wordpress.org/mangeshp), [scodtt](https://profiles.wordpress.org/scodtt), [journallist](https://profiles.wordpress.org/journallist) for their contributions to the codebase.