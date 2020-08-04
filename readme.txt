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
This plugin uses the [Ads.txt manager](https://github.com/10up/ads-txt) codebase as a starting point.

=== What is Trust.txt? ===

Trust.txt is an underlying system to systematically publish the connection between website Publishers and Associations those Publishers choose to make.

=== Technical Notes ===

* Requires PHP 5.3+.
* Requires WordPress 4.9+. Older versions of WordPress will not display any syntax highlighting and may break JavaScript and/or be unable to localize the plugin.
* Ad blockers may break syntax highlighting and pre-save error checking on the edit screen.
* Rewrites need to be enabled. Without rewrites, WordPress cannot know to supply `/Trust.txt` when requested.
* Your site URL must not contain a path (e.g. `https://example.com/site/` or path-based multisite installs). While the plugin will appear to function in the admin, it will not display the contents at `https://example.com/site/Trust.txt`.

=== Can I use this with multisite? ===

Yes! However, if you are using a subfolder installation it will only work for the main site. This is because you can only have one Trust.txt for a given domain or subdomain per the [Trust.txt spec].  Our recommendation is to only activate Trust.txt Manager per-site.

== Screenshots ==

1. Example of editing a Trust.txt file with errors and a link to browse Trust.txt file revisions.
2. Example of comparing Trust.txt file revisions.
3. Example of comparing two disparate Trust.txt file revisions.

== Installation ==
1. Install the plugin via the plugin installer, either by searching for it or uploading a .zip file.
2. Activate the plugin.
3. Head to Settings → Trust.txt or App-Trust.txt and add the records you need.
4. Check it out at yoursite.com/Trust.txt!

Note: If you already have an existing Trust.txt file in the web root, the plugin will not read in the contents of the respective files, and changes you make in WordPress admin will not overwrite contents of the physical files.

You will need to rename or remove the existing Trust.txt file (keeping a copy of the records it contains to put into the new settings screen) before you will be able to see any changes you make to Trust.txt inside the WordPress admin.

== Changelog ==

= 1.0 =
* Initial plugin release
