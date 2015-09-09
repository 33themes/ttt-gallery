=== TTT Gallery ===

Contributors: 33themes, gabrielperezs, lonchbox, tomasog
Tags: image gallery, custom gallery template, gallery markup, post gallery, slider, gallery lightbox
Requires at least: 3.7
Tested up to: 4.3
Stable tag: 0.2 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

TTT Gallery is a WordPress gallery plugin fully customizable.

## Description

TTT Gallery allows you to use your own templates to show galleries. You have full control of how a gallery is displayed.

This plugin uses the WP Gallery creator from the core and you can use any thumbnail size set up in the theme or by plugins.

## Create a Gallery

After activate the plugin a new metabox it's shown at the bottom of all Posts, Pages & CPTs. Just click in "Create gallery" button, then add the images you want from the media uploader window to your new gallery.

## How to show and set up the gallery

TTT Gallery uses shortcodes to display and configure the galleries.

* Simple Shortcode

`[tttgallery]` 

Insert this shortcode into the editor, and that´s it, no more configurations. If you have more than one gallery you can use the same shortcode, the plugin handle the position of the galleries according to the position they are in the metabox.

* Select Gallery (by ID)

`[tttgallery id="1"]` 

If you have more than one gallery and you want to use just one of them in your post, use the ID of the gallery and only one shortcode.

* Gallery Template

`[tttgallery template="default"]`

The plugin have some templates included.

  * **Default.** No design, simple markup. It uses thickbox for lightbox animation.
  * **Fancybox.** Same as the default but using fancybox.
  * **Lightbox.** Same as the default but using lightbox.
  * **Orbit** For the user of foundation. It doesn´t include the foundation orbit javascript file so you have to include it into your theme files. Check it here: http://foundation.zurb.com/docs/components/orbit.html.
  * **Responsiveslides.** More info in http://responsiveslides.com/
  * **Sly.** One of the most easy and fun horizontal slider. Check http://darsa.in/sly/


* Thumbnail Size

`[tttgallery thumbnail="medium"]` Just use the thumbnail name and the plugin resolve to use that image size.


* Single Image (always 1st)

`[ttt-gallery-image]` This is a very usefull feature for editors, if you create a gallery and want to show each image separately between the post text just copy&paste this shortcode as many images the gallery have, ttt gallery will show the images from the gallery in the same order of the gallery.


* Selected Image (by position)

`[ttt-gallery-image position="1"]` With this option you can select to show just the image in the first position of the gallery, if you change the order of the images in the gallery the shortcode allways show the first one.


* Examples:

`[tttgallery id="42" template="default"]` Means use the Gallery with ID 42 and use the deafault gallery template. IMPORTANT: The name of your template folder is the one you should use in the shortcode paramater.

`[ttt-gallery-image id="42" position="4" template="fancybox"]` Only shows the 4th image in the gallery ID 42 and use the fancybox template.


= Custom Gallery Template =

Is very easy but you need to know a medium code knowledge, but if don´t have and are use to toucha bit of code just follow this steps and you can learn how it works :). Just need to create a folder in your Theme and inside locate the templates, each template need his own folder to locate the HTML markup, CSS & JS files. ie:

/wp-content/themes//my-theme/ttt-gallery/my-custom-template

Inside this Template folder need to locate all the files you gallery need. To load the javascript and stylesheets from the template to your theme <header> need two files, style.php to indicate where the css and js files are and template.php for the gallery html. ie:

/my-custom-template/style.php
/my-custom-template/template.php
/my-custom-template/css/my-template.css
/my-custom-template/images/close.png
/my-custom-template/js/my-template.js


This is the **style.php** code example:

`wp_enqueue_script( 'my-template-styles', plugins_url('my-custom-template/js/my-template.js' , dirname(__FILE__) ), array('jquery'),'1.0', true);`
`wp_enqueue_style( 'my-template-styles',  plugins_url('my-custom-template/css/my-template.css' , dirname(__FILE__) ) );`

This is the **template.php** code example:

`<div class="my-custom-template">
	<h2><?php $ttt_gallery->description; ?></h2>
	<?php foreach( $ttt_gallery->medias as $ttt_media ): ?>
	<a href="<?php echo $ttt_media['sizes']['full']['url']; ?>" title="<?php echo $ttt_media['description']; ?>" rel="<?php echo $ttt_gallery->rel; ?>">
		<img src="<?php echo $ttt_media['sizes']['thumbnail']['url']; ?>">
	</a>
	<?php endforeach; ?>
</div>`

TTT Gallery use the same image meta datas than WordPress Core use and save. This are some of the parameters you can use in your template:

**Gallery Description** `<?php $ttt_gallery->description; ?>`
**Image Description** `<?php echo $ttt_media['description']; ?`
**Image Rel HTML parameter** `<?php echo $ttt_gallery->rel; ?>`
**Image Thumbnail URL** `<?php echo $ttt_media['sizes']['THUMBNAIL_SIZE']['url']; ?>` <- If you want to use an specific Thumbnail Size for your Theme without the user need to set that size just write the thubmnail name you want to use, like: thumbnail, medium, large, full, my-custom-thubmnail-size. More info: https://codex.wordpress.org/Post_Thumbnails#Thumbnail_Sizes [Thumbnail Sizes](http://wordpress.org/ "Codex Wordpress.org")


== Hacks ==

`/* Remove Gallery Metabox from specific content */`


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `ttt-gallery` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= Do TTT Gallery create a new folder for galleries ? =

No. The images stays where they have to be, in /uploads

= Do TTT Gallery create a Custom Post Type to manage galleries ? =

No. You will find under the Media menu a new sectión to see all created galleries or create new ones. Media -> TTT Gallery

= Can I use my own Thumbnail Sizes in the Galleries ? =

Yes :)

= It´s possible to remove the Gallery metabox from specific Custom Post Type ? =

Yes it´s possible. For that you need to add a hack code in to your functions.php file. Check the Hacks Tab.

= Can I make my own Gallery Template ? =

Yes, TTT Gallery was created for give total freedom to developers combined with the best integration with the code WP standars.

1. Create a new folder in your Theme `/wp-content/themes/my-theme/ttt-gallery/`
1. Copy `default` folder & files from `/wp-content/plugins/ttt-gallery/template/front/default` to `/wp-content/themes/my-theme/ttt-gallery/my-custom-template/`

IMPORTANT: The name of your folder is the one you should use in the shortcode paramater.
