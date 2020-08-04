# Trust.txt Manager for WordPress

> Create, manage, and validate your trust.txt from within WordPress, just like any other content asset. This plugin uses the [Ads.txt manager](https://github.com/10up/ads-txt) codebase as a starting point.

* **Contributors:** [rtcamp](http://profiles.wordpress.org/rtcamp), [mangeshp](http://profiles.wordpress.org/mangeshp), [scodtt](https://profiles.wordpress.org/scodtt), [journallist](https://profiles.wordpress.org/journallist)

## Features

![Screenshot of trust.txt editor](.wordpress-org/screenshot-1.png "Example of editing a trust.txt file with errors and a link to browse trust.txt file revisions.")

Trust.txt is an underlying system to systematically publish the connection between website Publishers and Associations those Publishers choose to make. Through our work at rtCamp, we've created a way to manage and validate your trust.txt file from within WordPress, eliminating the need to upload a file. The validation baked into the plugin helps avoid malformed records, which can cause issues that end up cached for up to 24 hours and can lead to a drop in ad revenue.

### Can I use this with multisite?

Yes! However, if you are using a subfolder installation it will only work for the main site. This is because you can only have one trust.txt for a given domain or subdomain per the trust.txt spec. Our recommendation is to only activate Trust.txt Manager per-site.

## Requirements

* Requires PHP 5.3+.
* Requires WordPress 4.9+. Older versions of WordPress will not display any syntax highlighting and may break JavaScript and/or be unable to localize the plugin.
* Rewrites need to be enabled. Without rewrites, WordPress cannot know to supply `/trust.txt` when requested.
* Your site URL must not contain a path (e.g. `https://example.com/site/` or path-based multisite installs). While the plugin will appear to function in the admin, it will not display the contents at `https://example.com/site/trust.txt`. This is because the plugin follows the specific spec, which requires that the trust.txt file be located at the root of a domain or subdomain.

## Installation

1. Install the plugin via the plugin installer, either by searching for it or uploading a .zip file.
1. Activate the plugin.
1. Head to Settings → Trust.txt and add the records you need.
1. Check it out at yoursite.com/trust.txt!

Note: If you already have an existing trust.txt file in the web root, the plugin will not read in the contents of the respective files, and changes you make in WordPress admin will not overwrite contents of the physical files.

You will need to rename or remove the existing trust.txt file (keeping a copy of the records it contains to put into the new settings screen) before you will be able to see any changes you make to trust.txt inside the WordPress admin.

## Screenshots

### 1. Example of editing a trust.txt file with errors and a link to browse trust.txt file revisions.

![Screenshot of trust.txt editor](.wordpress-org/screenshot-1.png "Example of editing a trust.txt file with errors and a link to browse trust.txt file revisions.")

### 2. Example of comparing trust.txt file revisions.

![Screenshot of trust.txt in Revisions editor](.wordpress-org/screenshot-2.png "Example of comparing trust.txt file revisions.")

### 3. Example of comparing two disparate trust.txt file revisions.

![Screenshot of trust.txt in Revisions editor](.wordpress-org/screenshot-3.png "Example of comparing two disparate trust.txt file revisions.")

## Support Level

**Active:** rtCamp is actively working on this, and we expect to continue work for the foreseeable future including keeping tested up to the most recent version of WordPress.  Bug reports, feature requests, questions, and pull requests are welcome.
