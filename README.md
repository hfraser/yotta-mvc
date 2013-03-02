Yotta-MVC
=========

Micro PHP-MVC fast development tool.

 * Copyright : Hans-Frederic Fraser hffraser@gmail.com
 * License : GPL-V3
 * Website : http://bitbucket.org/hfraser/yotta-mvc

 # Yotta-MVC

Yotta-MVC came out of the need to have a very fast structure unto which we can build very small websites and applications fastly.

The whole system is a very small MVC that relies on a lightweight extensible core with [RedBean](http://www.redbeanphp.com/ "RedBean") as the ORM. Redbean was chosen because of it's small size and ease of use.

## Why Yotta-MVC
Yotta-MVC Came out of the need to do really fast small development. Day to day we build all kinds of mini websites and prototypes from Facebook pages, contest forms to backend prototypes. The problem is you end up with 2 option
 * build from scratch
 * use a full featured framework

Building from scratch is time consuming, even if you re-use most of your code you still end up spending a big portion of your time refactoring it and cleaning it up. Using a clean base MVC saves that work.

Using a full featured framework is fun but it is overkill and long to get started, in most cases the implementation is prohibitive since they do not adapt fast enough for smaller needs.

All you need for very small projects is routing, form management, templating and multilingual capabilities. plus the ability to add your own features. The whole thing enhanced by a lightweight active record ORM [RedBean](http://www.redbeanphp.com/manual/ "RedBean"). Yotta-MVC has no CMS, or advanced backend features nor do I ever intend to put one or link one. It is made to stay as light and simple as possible.

## [Documentation](https://bitbucket.org/hfraser/yotta-mvc/wiki/Home "Yotta-MVC Documentation") 

## Contributing to Yotta-MVC
In order to contribute to Yotta it's very simple, since every projects and developpers have their quirks and requirements about contribution here are the ones for Yotta!

 1. Your code is properly documented
 1. Your code respects the [Coding standards](https://bitbucket.org/hfraser/cmantix-sniffer/wiki/cmantix-coding-standard "Coding standards") for the project. You tested the coding standard with code sniffer.
 1. There are proper unit tests for your contribution and you maximized code coverage on them.
 1. You generated a pull request!


## References
 * [Coding standards](https://bitbucket.org/hfraser/cmantix-sniffer/wiki/cmantix-coding-standard "Coding standards")
 * [RedBean ORM](http://www.redbeanphp.com/manual/ "RedBean ORM")
 * [YUI CSS compressor PHP port](https://github.com/tubalmartin/YUI-CSS-compressor-PHP-port/ "YUI CSS compressor PHP port")
 * [jsmin-php](https://github.com/rgrove/jsmin-php "jsmin-php") (yes, outdated and unmaintained but works just fine at the moment, looking for simple elegant alternatives)
 * [lessphp](https://github.com/leafo/lessphp/ "lessphp")