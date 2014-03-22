ITPMeta Lite for Joomla! 
==========================
( Version 3.8 )
- - -

It is a Joomla! extension that puts meta tags into the site code. The component provides a list with predefined and popular meta tags. There are Open Graph, Facebook, Google, Twitter Card and other semantic tags.

Changelog
---------

###v3.8
* It was copied all tags from Pro release to Lite one.
* The functionality that adding urls automatically was moved from the plugin to another one.
* Langauge files were moved to the component folder.
* Added option to select default image.
* Added option to enable and disable generating tags for extensions.
* Improved collecting data from K2 (com_k2).
* Improved collecting data from Cobalt.
* Added option for generating meta description from article text, if metadesc missing. 
* Added new tags
    * Dublin Core tags.
    * OpenGraph Product
* Some tags sections were updated with new tags.
    * Article
    * Book
    * Locale
    * Business
    * Places ( now is Place )
    * Misc
* Fixed some issues.

###v3.7.2

* Fix an issue with overriding Google Alternate tag.

###v3.7.1

* Added Google Alternate (rel=alternate) meta tag.

###v3.7

* Merged Tags Manager with URL form
* Added tags og:video:url, twitter:image:src
* Fixed some issues

###v3.6

* Added Google Author and Publisher meta tags.
* Added a new way of collecting URLs. Now they are two - Strict and Full.
* Added filter by "Auto Update" state.
* Fixed filter by State
* Added Tags Manager
* Changed interface for managing Tags.
* Improved usability

###v3.5

* Added K2 tags generator
* Improved plugins "System - ITPMeta" and "System - ITPMeta - Tags" 
* Improved  

###v3.4

* Moved options from component to plugins
* Added option "autoupdate" to urls. Now, you are able to disable updating for URLs.
* Improved auto adding urls. Now, invalid URLs will not be added.

###v3.3

* Added ordering to tags.
* Added publishing functionality to tags.
* Added new state for URLs - suspicious.
* Now works with enabled magic quotes.
* Added site verification tags for Alexa and Bing
* Improved content plugin - added tags "Article Author", "Article Published Time", "Article Modified Time". Now it collects information about categories and creates tags.
* The content plugin was removed. Now the plugin "System - ITPMeta - Tags" is used for generating tags for Joomla! Content (com_content).
* Improved

###v3.2

* Added Open Graph Music tags
* Added Plugin that generates tags for extension Content (com_content)
* Improved

###v3.1

* Added a functionality that collect links automatically.
* Added a functionality that generate canonical URL automatically.
* Added new OpenGraph tags
 * Facebook restrictions tags
 * article tags
 * book tags
 * music tags
 * profile tags
 * new image tags
 * new video tags
* Added options using to setup the loading of namespace schemes
* Improved

###v3.0

* Ported to Joomla! 2.5
* Added global tags
* Added a new video tag - og:secure_url
* Added a locale tags - og:locale, og:locale:alternate
* Improved

###v2.2

* Added Open Graph URL tag
* Improved

###v2.1

* Fixed a bug in the plugin