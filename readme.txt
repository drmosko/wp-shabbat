=== WP-Shabbat ===
Contributors: drmosko
Tags: 
Requires at least: 3.7.0
Tested up to: 3.7.0
Stable tag: 0.04
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Close site on Shabbat and Holidays by identifying the address of the user IP and close to 40 km



== Description ==

[WP-Shabbat](http://www.dossihost.net/%D7%AA%D7%95%D7%A1%D7%A3-%D7%95%D7%95%D7%A8%D7%93%D7%A4%D7%A1-%D7%A1%D7%95%D7%92%D7%A8-%D7%90%D7%AA%D7%A8-%D7%91%D7%A9%D7%91%D7%AA%D7%95%D7%AA-%D7%95%D7%97%D7%92%D7%99%D7%9D/) is a WordPress plugin that will help you and your visitors observe the shabbat.

For more information in hebrew, check out [WP-Shabbat](http://www.dossihost.net/%D7%AA%D7%95%D7%A1%D7%A3-%D7%95%D7%95%D7%A8%D7%93%D7%A4%D7%A1-%D7%A1%D7%95%D7%92%D7%A8-%D7%90%D7%AA%D7%A8-%D7%91%D7%A9%D7%91%D7%AA%D7%95%D7%AA-%D7%95%D7%97%D7%92%D7%99%D7%9D/).

Features include:

* close the site by setting the Shabbat and holiday enter time in minutes when the minimum is 20 minutes.
* close the site by setting the Shabbat and holiday exit time in temporary minutes from 18 minutes to 72 minutes.
* Ip databse is updated automatically every month. (est. size : 15Mb)
* search engine bots get http header 503.(SEO-Friendly) :
[Answer from Google about WP-Shabbat](https://productforums.google.com/forum/#!topic/webmasters/bjpQtTwzadI/discussion)
* plugin languages : English,Hebrew.
* plugin will generate on fly page with your template for visitor to come back later.

Notes:

* Shabbat and holiday exit time is temporary minutes that calculated from sunrise to sunset and divided into 12 hours.
* The sunrise and sunset times is calculated for each user by his location. 
* Identification place of the user is by its IP address close to 40 km.
* This script uses GeoLite Country from MaxMind (http://www.maxmind.com) which is available under terms of GPL/LGPL 
* DB file GeoLiteCity.dat downloaded every month from maxmind servers to plugin directory.

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'WP-Shabbat'
3. Activate WP-Shabbat from your Plugins page. 
4. Visit 'Setting > WP-Shabbat' and set the times you  want.

= From WordPress.org =

1. Download WP-Shabbat.
2. Upload the 'WP-Shabbat' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate WP-Shabbat from your Plugins page. 
4. Visit 'Setting > WP-Shabbat' and set the times you  want.

== Screenshots ==

1. WP-Shabbat Setting Page.
2. WP-Shabbat on fly page (with Twenty Thirteen template).
3. Confirm of Http header status 503.

== Changelog ==

= 0.04 =
* fixed Collision functions

= 0.03 =
* closed page set to 503 http like google advise (http://productforums.google.com/forum/#!topic/webmasters/theUs8RCvDg/discussion
and http://www.seroundtable.com/archives/020729.html)
* remove bot cloack

= 0.02 =
* check if template base files exists