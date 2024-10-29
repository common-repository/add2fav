=== add2fav ===
Contributors: Christian Salazar
Donate link: http://www.ascinformatix.com/www/donate/
Tags: favorites widget
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: Stable
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Save favorite URL's using an "Add to Favorites" and a "My Saved Favorites" widget in your post/page

== Description ==

Insert a shortcode into your page: [add2fav-link] or insert a widget "Add to Favorites" in your sidebar o somewhere else, this will show an "Add to Favorites" ajax link. This link will automatically show the appropiated label "Add to Favorites" or "Remove from Favorites" depending on the current logged on user, you can edit those text via widget admin panel.

When the current user clicks over it then the current browser URL is saved into the user metadata, so you can show the saved URL's for this user using the "My Favorites" widget provided by this plugin too.

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place a shortcode [add2fav-link] in your page/post or use the Widgets menu to locate the "Add to Favorites" widget it in your sidebar or somewhere else.
4. Aditionally you can insert the "Add to Favorites List" widget into your side bar to show the current user added favorites.

== Frequently asked questions ==

Shortcodes available:

[add2fav-link]	inserts the "Add to Favorites" link.
[add2fav-list]  inserts a "My Favorites" list. available options: icon=star (or heart), height=100, title="my title", cssname=a_css_class_name

== Screenshots ==

1. Add to Favorites Widget
2. My Favorites Widget

== Changelog ==



== Upgrade notice ==

you can update it via bitbucket (GIT), using this repository:
https://bitbucket.org/christiansalazarh/add2fav
