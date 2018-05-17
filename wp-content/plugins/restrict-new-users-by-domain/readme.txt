=== Restrict New Users by Domain ===
Contributors: exportsmedia
Donate link: https://exportsmedia.com/buy-me-a-chocolate-milk/
Tags: restrict, new users, sign up, restrict domain, restrict email
Requires at least: 4.6
Tested up to: 4.7
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Whitelist or Blacklist email domains used for new user registration.

== Description ==

Restrict New Users by Domain makes it easy to whitelist or blacklist email domains that new users can use when registering. If using the whitelist, only new users who enter an email domain on the whitelist will be allowed to create an account. If using the blacklist, a user who enters an email domain on the blacklist will not be allowed to register. This plugin is NOT multisite compatible.


= Features =

* Easy to use settings page for adding domains
* No limit on domains to restrict
* Customize the error message for restricted domains
* Uses built in WordPress actions, no CSS or JS

= THIS PLUGIN DOES NOT WORK WITH MULTISITE =


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/restrict-new-users-by-domain` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress


== Frequently Asked Questions ==

= Can I use both the Whitelist and the Blacklist =

No. Only one can be used. If both lists have domains saved in them, then only the whitelist will be used.

= Can I change the default error message =

Yes. You can customize the error message on the settings page.

= Does this plugin work with Multisite =

No. Multisite already has this ability built in. You do not need this plugin if you are running a multisite.


== Screenshots ==

1. The Restrict New Users by Domain settings page. It is accessed by going to Settings -> Restrict New Users by Domain
2. The default error message for a restricted domain.


== Other Notes ==

Restrict New Users by Domain makes it easy to whitelist or blacklist email domains that new users can use when registering. If using the whitelist, only new users who enter an email domain on the whitelist will be allowed to create an account. If using the blacklist, a user who enters an email domain on the blacklist will not be allowed to register.


== Changelog ==

= 1.0.2 =
* Fix case sensitivity when validating domains as suggested by user @john168

= 1.0.1 =
* Fix multisite activation error

= 1.0 =
* Initial Release

