/**
 * This script sets the Backstretch arguments in the Digital Pro Theme.
 *
 * @package Digital\JS
 * @author StudioPress
 * @license GPL-2.0+
 */

jQuery(document).ready(function($) {

	// $(".front-page-1").backstretch([BackStretchImg.src]);
	$(".about-page-1").backstretch([BackStretchImg.src]);

});

jQuery(document).ready(function($) {
 $(".front-page-1").backstretch([ // Target your HTML element
 '/wp-content/uploads/front-images/front-page-1.jpg', // Add in your images
 '/wp-content/uploads/front-images/front-page-2.jpg', // Add in your images
 ],{
 duration:3000, // Time between transitions
 fade:750, // Transition effect
 });
});