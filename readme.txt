=== Quick Post Management ===

Contributors: Name.ly, Namely
Donate link: http://name.ly/plugins/donations/
Tags: custom,customisation,customization,quick,instant,short,shortcut,shortcuts,link,links,author,page,pages,post,posts,admin,administration,administrator,edit,editor,change,open,publish,draft,private,password,protected,open,close,comment,comments,trackback,trackbacks,erase,erasing,trash,trashing,delete,deleting,force,convert,management,plugin,plugins,tool,tools,wpmu,wordpress,wp,mu,multi,multisite,multiuser,frontend,front-end
Requires at least: 3.0.0
Tested up to: 3.4.2
Stable tag: trunk
License: GPLv2 or later

QPM adds page/post management links for one-click actions as drafting, private/public, opening/closing comments/trackbacks, trashing/deleting, etc

== Description ==

Quick Post Management adds the following links visible to editors at the top/bottom of every page/post enabling one-click quick shortcuts as:

* Set as draft
* Set as private/public
* Close/open comments
* Close/open trackbacks
* Move to trash
* Force delete
* Convert into page/post
* Any number of other custom links

If you an editor or moderator on a big site and you often need quickly set post/page as draft or private, this plugin will come pretty handy. It will save you time, as many actions that did require several clicks and significant loading time will now be done with just one click.

For installation please see the [corresponding section](http://wordpress.org/extend/plugins/quick-post-management/installation/). It is as trivial as copying the plugin folder in your WordPress.

To get the flavour of what the plugin actually does, see the [screen shots](http://wordpress.org/extend/plugins/quick-post-management/screenshots/).

Once installed and activated, the plugin back-end will be accessible via a separate menu in the admin panel (WP Admin -> Settings -> QPM / Quick Post Management).

== Installation ==

= As easy, as 1-2-3 =

1. Upload `quick-post-management` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Start using the quick management links, voila!

= Usage =

Activate the plugin. Settings can be accessed via WP Admin -> Settings -> QPM / Quick Post Management. The rest is done automatically.

== Frequently Asked Questions ==

= How can I change the way the added links look? =

You can define it via CSS of `qpm` class.

E.g., add the following lines into your theme's `style.css`:
`.qpm {
	color: #000000;
}
.qpm a {
	color: #E60000;
	font-weight: bold;
	text-decoration:none;
}
.qpm a:active {
	text-decoration:underline;
}
.qpm a:hover {
	text-decoration:underline;
}
.qpm a:link {
	text-decoration:none;
}
.qpm a:visited {
	text-decoration:none;
}`

= Why is email notification disabled? =

You will be able to notify post authors via the email in the coming versions of this plugin.

The functionality is not yet in the code.

== Screenshots ==

1. Admin page
2. Post page

== Changelog ==

= 0.0.6 =

* Finished translation and added Spanish translation thanks to [Maria Ramos](http://webhostinghub.com/)

= 0.0.5 =

* Fixed warning generated when WP_DEBUG is set to true

= 0.0.4 =

* Added shortcode `[QuickPostManagement]` [requested by Eckstein](http://wordpress.org/support/topic/use-in-other-places-in-my-template).

= 0.0.3 =

* Fixed rare case when `current_user_can ( "editor" )` was returning `false`, even for site admins. That was noticed on some localised blogs. Special thank to Jean-Michel Meyer for the tips, help and patience.

= 0.0.2 =

* Added options to enable custom links.

= 0.0.1 =

* Initial version.
* Created and tested.

== Upgrade Notice ==

= 0.0.1 =

This is a great plugin, give it a try.

== Translations ==

* English UK - [_Name.ly_](http://name.ly/)
* English US - [_Name.ly_](http://name.ly/)
* Spanish - [Maria Ramos](http://webhostinghub.com/)
* Russian - [Kreml.in](http://kreml.in/)
* Ukrainian - [Lviv.PRO](http://siohodni.com/)

If you want to translate this plugin, please [contact](http://name.ly/about/contact/) the _Name.ly_ team.

For quick translation, you can use [Quick Localization](http://wordpress.org/extend/plugins/quick-localization/) plugin.

== Recommendations ==

Check out our other cool and practical plugins:

* [Feed2Tabs](http://wordpress.org/extend/plugins/feed2tabs/)
* [Links2Tabs](http://wordpress.org/extend/plugins/links2tabs/)
* [Quick Localization](http://wordpress.org/extend/plugins/quick-localization/)

== About Name.ly ==

_Name.ly_ offers WordPress blogs and many other services allowing to consolidate multiple sites, pages and profiles.

All on catchy domain names, like many.at, brief.ly, sincere.ly, links2.me, thatis.me, of-cour.se, ... and hundreds more.

_Name.ly/PRO_ platform allows domain name owners to run similar sites under their own brand.

[_Name.ly/PRO_](http://namely.pro/) is most known for being first WordPress driven product allowing reselling emails and sub-domains.
