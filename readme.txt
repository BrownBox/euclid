Release Notes
=============

7.2.1
New search functionality can now be turned on or off as desired
New bb_get_post_meta() for nicer meta handling
Major refactoring of section boilerplate, particularly around transient naming conventions 
Relevant transients are now auto-refreshed when saving/deleting posts, saving menus or any time dynamic styles are generated
All default sections now use section boilerplate
Bug fixes

7.2
Added support for loading sections from outside the theme (e.g. plugins)
Better abstraction of pagination logic to allow for use beyond post archives
Updated hero logic to make better use of hierarchy
Added helper functions to lighten or darken colours
Added 2 new custom Customizer controls - Multiple Checkboxes and WP Editor
Added 3 new Children as... templates - Accordion, Tiles and Tabs
Added BB_Random_* classes for displaying random items, with caching if desired
Added funky new search functionality (also replaces 404)
Added support for child menus
Added logging helper function
Added BB_Transients class for easier working with multiple transients
Added new section boilerplate integrated with transient framework
Added Site Max Width setting
Customizer now has support for borders in colour scheme and auto-generated CSS classes
Various tweaks and bug fixes

7.1
Added support for panels on archive pages
Added ability to connect panels to post types
Map function now supports multiple markers
Use WP_Filesystem to write dynamic styles
Extended array to columns logic to support returning a sorted array
Added support for auto-columnised menus
Bug fixes

7.0.5
Responsive hero support

7.0.4
Improved performance

7.0.3
Featured image now uses hierarchy with customizer default as fallback
New edit button to panels on front end
New copyright row below footer
Better default styling for offcanvas menu
New customizer fields for custom heading styles
Extended handling of archive content
Various tweaks and bug fixes

7.0.2
Big panels update - 4 new recipes, tiles option and a bunch of other tweaks and improvements
New hierarchy walker function
New Customizer option for heading font
Various bug fixes

7.0.1
New bb_theme::srcset() function        
Font settings moved to Customizer        
Returned to offcanvas as default menu style for small screens        
Replaced Zurb hamburger with FontAwesome  
Nicer default styling of GF validation errors

7.0
Euclid is here! Includes Zurb 6.2.3, powerful row-based layout ("panels"), much cleaner CSS media queries than predecessors, and much more