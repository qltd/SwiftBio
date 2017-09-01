# 1.4.0 (2017-08-17)
* Support backend order calculations for both WooCommerce 2.6.x and 3.x
* Fix backend rate display for orders with multiple tax classes

# 1.3.3 (2017-08-01)
* Fix initial calculation for recurring subscriptions with a trial period

# 1.3.2 (2017-07-20)
* Fix local pickup error for WooCommerce < 2.6.2

# 1.3.1 (2017-06-18)
* Include tlc_transient hotfix

# 1.3.0 (2017-06-16)
* Product taxability support for exemptions such as clothing.
* Line item taxability with support for recurring subscriptions.
* Fully exempt non-taxable items when tax status is set to "None".
* Fix calculations to use shipping origin when local pickup selected.
* Fix caching issues with API requests.

# 1.2.4 (2016-10-19)
* Add fallbacks to still calculate sales tax if nexus list is not populated.

# 1.2.3 (2016-09-21)
* Limit API calls for tax calculations to nexus areas.

# 1.2.2 (2016-08-29)
* Fix issue where uncached shipping tax was not displayed

# 1.2.1 (2016-06-27)
* Fix bug causing sales tax to not be calculated when shipping is disabled
* Pass home_url rather than site_url when linking to TaxJar

# 1.2.0 (2016-01-19)
* Changes for WooCommerce 2.5 compatibility around transients

# 1.1.8 (2015-12-30)
* Shipping tax bugfix

# 1.1.7 (2015-12-23)
* Bump version, wordpress.org failed to create 1.1.6 zip file

# 1.1.6 (2015-12-22)
* Change wording for connection

# 1.1.5 (2015-12-14)
* Display Nexus States/Region list on TaxJar panel
* Allow 1-Click TaxJar connection setup
* Bug fixes around order editing in order admin screens.

# 1.1.4 (2015-10-30)
* Better warnings about connection errors on plugin panel

# 1.1.3 (2015-09-09)
* Better support for generating API keys in WooCommerce 2.4+
* Warnings for PHP version

# 1.1.2 (2015-07-30)
* Handling Shipping tax more accurately

# 1.1.1 (2015-07-21)
* Fix transient key bug with city (suggest to clear transients in WooCommerce)
* Label text change
* Improve handling of Shipping taxes

# 1.1.0 (2015-06-26)
* Switch to v2 TaxJar API
* Bug fixes and code cleanups

# (2015-04-30)
* WooCommerce compatible note 2.3.x is now required

# 1.0.8 (2015-03-10)
* Bug fixes in the handling of persisted rates

# 1.0.7 (2014-12-24)
## Fixed
* Fixed a bug encountered when local shipping options were selected

## New
* Adds tax calculation support to WooCommerce for local shipping options
* WooCommerce can now calculate taxes for local pickup shipping option

# 1.0.6 (2014-11-17)
* Fixed a bug encountered on some hosting providers

# 1.0.5.2 (2014-11-13)
* Fixed a bug where coupons where being applied on the cart twice

# 1.0.5.1 (2014-11-06)
* Bug fixes

# 1.0.5 (2014-09-26)
## Updated
* New way of handling taxes on orders compatible with WooCommerce 2.2
* Uses new API (with support for Canada): [read the docs](http://www.taxjar.com/api/docs/)

## New
* Ability to download orders easily into TaxJar
* Shortcuts to access TaxJar Settings
* Freezes settings for WooCommerce Tax (we set everything up for your store's sales tax needs)

# 1.0.3 (2014-08-27)
* Fix api url param for woo

# 1.0.2 (2014-08-26)
* use taxable_address from wooCommerce customer

# 1.0.1 (2014-08-25)
* TaxJar calc overrides all other taxes
* Hide order admin calculate tax button

# 1.0 (2014-08-11)
* Initial release
