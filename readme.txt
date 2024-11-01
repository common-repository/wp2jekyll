=== WordPress2Jekyll ===
Contributors: liam_bowers
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=A5BPK7XBH54UY
Tags: jekyll, export, flat, blog, github, yaml
Requires at least: 4.3
Tested up to: 4.4.1
Stable tag: 0.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This allows you to use WordPress as an interface to Jekyll. It will save posts, taxonomies and author information out in a Jekyll friendly format.

== Description ==

Jekyll is a great tool that will build a full blog site using text files written in a specific format. It gives you the bonus of having a very light and quick site that can't be hacked through script vulnerabilities. The downside of this is that it's not always easy to edit. It's harder still when using a mobile device.

This is where WordPress comes in. WordPress can be used to modify the posts (both standard posts and pages) and these changes will automatically be exported. The same applies for taxonomies and users if desired. This means that it is possible to edit content using a mobile device and the WordPress app.

WordPress2Jekyll attempts to marry these two systems together in order to make a quick, secure website that can be easily managed from all devices.

= What can it do? =

* Exports posts and pages with all the information Jekyll needs to build a page including categories and tags.
* Converts page content to Markdown format (assumes it is HTML).
* Has option to use the Wordpress Permalinks patterns rather than using the configured Jekyll method.
* Ability to allow WordPress to run content through the_content filter to allow plugins to interact with the body content.
* Automatically builds individual pages (or full build) depending on what has changed in the post.
* Configurable assets, data and posts directories.
* Ability to export meta data in posts.
* Export authors.
* Export taxonomies.
* On demand mass export.

== Installation ==

Installing this module is simple enough:

1. Upload the plugin files to the `/wp-content/plugins/wp2jekyll` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Use the Settings->WordPress2Jekyll screen to configure the plugin. This Jekyll path must point to an existing Jekyll site directory. If you're using it for testing purposes then make sure the assets, data and posts directories exist. It will complain if it doesn't find them.
1. Use Tools->Jekyll to perform a full export of the current content as defined in the settings.

== Frequently Asked Questions ==

= What is Jekyll? =

Check out https://jekyllrb.com/ It should give you all the answers you need right here.

= What do I do after the site has exported? =

That completely depends on your setup. If you have Jekyll set up to watch the directory for changes then it will build or serve the site automatically.

Future versions will trigger Jekyll in to building the site.

= How can I use the data it exports? =

Well the posts are self explanatory.

The taxonomy and author data is stored in <data directory> as yaml files which can be used by Jekyll very easily. Check out http://jekyllrb.com/docs/datafiles/.
I personally use it to load in author information and build menus.

= This is great/terrible =

Let me know! I'd love to get some feedback on this. This is the first plugin I've created so go easy!

= I would really like it if it did 'x' =

How about you get in touch and I can see what I can do?

== Changelog ==

= 0.4 =

* Fixed an issue where the options page would error if a taxonomy wasn't selected.

= 0.3 =

* Fixed issue relating to the default settings not being set correctly after plugin activation or upgrade.
* Fixed an issue regarding the check for Jekyll returning a false positive.

= 0.2.1 =

Fixing versioning issue.

= 0.2 =

* Added a more comprehensive settings pages.
* Added ability to export post meta information.
* Added ability to export users.    (Experimental - format may change)
* Added ability to export taxonomies. (Experimental - format may change)
* A few bug fixes.

= 0.1 =

* Initial version.

== Upgrade Notice ==

I aim to make upgrades as painless as possible. Currently there's nothing that should cause upgrade issues although it's advisable to check out the new settings at Settings->Wordpress2Jekyll to make sure everything still looks good.

== Limitations ==

* This plugin assumes that all Jekyll posts are stored in WordPress and any files in the posts directory can be deleted. This is a necessary for maintenance.
* Featured images will be exported and linked accordingly.
* Doesn't support password protected posts at this time (I'm not sure if it ever will).
* Doesn't support inline media at this time.
* Supports categories (exported in order)
* Supports tags (exported in order)
* Ideally the WordPress installation should be hidden away and not publicly accessible. I'm working on a separate plugin to do this easily.

== Wishlist ==

* Add full builds to a cron job.
* Export inline media.
* Modify config.yml - User selectable output options.
* CLI usage.
* A more aesthetically pleasing configuration page.
