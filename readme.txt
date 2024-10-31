=== Post Webhook - Send Post & Page data to any API or external service ===
Author URI: https://jonathan-wright.com/about-me
Plugin URI: https://jonathan-wright.com
Contributors: Wrightj2
Tags: webhooks, api, automation, zapier, integromat, make
Requires at least: 4.7
Tested up to: 6.0.2
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
 
Automate your content workflow by automatically sending post and page data to external services.
 
== Description ==
 
This plugin sends details of published and deleted Pages & Posts to any 3rd party webhook capable service e.g. AirTable, Zapier, Integromat/Make, Pabbly etc.

**Details sent - On publish:**
* Author display name
* Post ID
* Post title
* Post date
* Post modified date
* Post GUID
* Post slug
* Post permalink
* Post type
* Post status
* Post categories
* Post tags
* Post word count

**Details sent - On delete/trash:**
* Post ID
* Post modified date
* Post GUID
* Post status

**Please note:** The plugin only sends data when a post/page is published or when a post/page that was published is deleted/trashed. It does not send data for any other post status types e.g. draft

== Installation ==
 
1. Activate the plugin
1. Go to Settings > Post Webhook and add your webhook URL and click the Save Webhook URL button
 
== Frequently Asked Questions ==
 
= How do I use this plugin? =
 
Once you have installed the plugin and added your webhook URL in the Plugin Settings then the plugin will automatically start sending data for any newly published or deleted posts and pages to that URL.
You then need to configure what you want to do with that data from within your 3rd party service. A detailed walkthrough, using AirTable as an example, is available on my website [Jonathan-Wright.com](https://jonathan-wright.com)
 
== Screenshots ==
1. The plugin settings page where you add your webhook URL.
 
== Changelog ==
= 1.0.0 =
* Plugin released. 
