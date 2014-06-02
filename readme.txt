=== YADWS ===
Contributors: AlexZabavsky, DmitryZabavsky
Donate link:
Tags: slider, image slider, responsive, gallery, carousel, slideshow, simple, developer
Requires at least: 3.0
Tested up to: 3.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple slider that alows putting several images on a slide. (YADWS - Yet Another Dynamic Wordpress Slider)

== Description ==

Such a clumsy name has been chosen on purpose. There are currently over 700 slider plugins in WordPress repository. YADWS is going to be just one of them.  Despite the great variety, neither of the available plugins provides one specific requirement that the authors had on one of their recent projects. We had to build a custom plugin that allowed adding several images on one slide. At the end of the project it grew into an independent product that we are proud to present.  

The first release fulfills the main requirement - allows putting an arbitrary amount of images on an unlimited amount of slides. It is intended for developers first of all. Think about it as a framework that provides basic JavaScript carousel functionality, hence flexibility. Here is the list of features YADWS provides:

* Flexible administration that allows adding of multiple images on an unlimited amount of slides. Choosing the images aspect ratio is administrator's responsibility for now. The plugin doesn't cut the images, it only resizes them. 
* Integration with Media Library - it is possible to choose existing images from the library.
* Simple reorganization of the images and slides.
* Support of an unlimited amount of sliders on a page.
* Two types of navigation: bullets and arrows.
* Basic theme, convenient for customization.

== Installation ==

1. Upload `yadws` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Create a slider in the plugin administration. Don't forget to specify "Expected initial size of the slider" - it will help the slider to resize the images correctly. 
4. After savig the slider, go to the slider list to get the shortcode.
5. Add the shortcode to the post or page (e.g. `[yadws slug='homepage-slider']`). In order to add the shortcode to a theme file, use function do_shortcode() - e.g. `<?php echo do_shortcode("[yadws slug='homepage-slider']"); ?>`

== Frequently Asked Questions ==

= How many images can be added per slide? =

The amount of images is virtually unlimited. So as the amount of slides. You can add different amount of images.

= I have used the shortcode in my theme, but it shows the shortcode instead of the slider. =

Make sure you use do_shortcode() function in themes `<?php echo do_shortcode("[yadws slug='homepage-slider']"); ?>`

= Why the controls are so ugly? =

At this point you should consider the plugin to be developer oriented. Its CSS is designed to be easily customizable. Simply override the bullets and arrows in our css. Theme support is planned for version v0.6 

== Screenshots ==

1. Plugin administration. Don't forget to specify the slider size. It will help WordPress to resize the images properly.
2. YADWS on the front-end.

== Changelog ==

= 0.5 =
* Initial alpha release.

== Project Roadmap ==

* v0.6 Themes support
* v0.7 Proper responsiveness 
* v0.8 Images aspect ratio correction
* v0.9 Free form HTML slides
* v1.0 Beta release
