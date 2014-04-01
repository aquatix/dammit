dammIT
======

This is the source of the weblog on [dammIT.nl](http://dammit.nl), with homegrown software internally called smplog.

It's a full-fletched weblog supporting commenting (with moderation tools), drafts, statistics and multiple formats to type posts in (plain text, html, markdown).


## PHP Markdown

The [Markdown](https://daringfireball.net/projects/markdown/) rendering is done by [php-markdown](https://github.com/michelf/php-markdown) by [Michel Fortin](http://michelf.ca/). The library is included in the source for convenience, but the code is not modified and copyright is completely Michel's.


## Installation

Create a database with the MySQL dump from `install/smplog.sql`. Be sure to *not* have MultiViews in your Apache config, as otherwise the /search page will not be found.
