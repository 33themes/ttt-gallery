# TTT Gallery

* **Contributors:** 33themes, gabrielperezs, lonchbox, tomasog, 11bits
* **Tags:** image gallery, custom gallery template, gallery markup, post gallery, slider, gallery lightbox
* **Requires at least:** 3.7
* **Tested up to:** 4.3
* **Stable tag:** 0.2 
* **License:** GPLv2 or later
* **License URI:** http://www.gnu.org/licenses/gpl-2.0.html

TTT Gallery is a WordPress gallery plugin fully customizable.

## Description

TTT Gallery allows you to use your own templates to show galleries. You have full control of how a gallery is displayed.

This plugin uses the WP Gallery creator from the core and you can use any thumbnail size set up in the theme or by plugins.

### How to show and set up the gallery

TTT Gallery uses shortcodes to display and configure the galleries.

* **Simple Shortcode:** `[tttgallery]` 

Insert this shortcode into the editor, and that´s it, no more configurations. If you have more than one gallery you can use the same shortcode, the plugin handle the position of the galleries according to the position they are in the metabox.

* **Select Gallery (by ID):** `[tttgallery id="1"]` 

If you have more than one gallery and you want to use just one of them in your post, use the ID of the gallery and only one shortcode.

* **Gallery Template:** `[tttgallery template="default"]`

The plugin have some templates included:

  * Default. No design, simple markup. It uses thickbox for lightbox animation.
  * Fancybox. Same as the default but using fancybox.
  * Lightbox. Same as the default but using lightbox.
  * Orbit (Foundation). It doesn´t include the foundation orbit javascript file so you have to include it into your theme files. Check it here: http://foundation.zurb.com/docs/components/orbit.html.
  * Responsiveslides. More info in http://responsiveslides.com/
  * Sly. One of the most easy and fun horizontal slider. Check http://darsa.in/sly/
  
  IMPORTANT: The name of your template folder is the one you should use in the shortcode parameter.

* **Thumbnail Size:** `[tttgallery thumbnail="medium"]`

To specify an image size just use the defined size of the thumbnail.

* **Single Image (always 1st):** `[ttt-gallery-image]` 

This is a very useful feature for editors. If you create a gallery and want to show each image separately, just paste this shortcode as many times as images have the gallery. The plugin will show the images in the same order than the gallery

* **Select Image (by position):** `[ttt-gallery-image position="1"]` 

With this option you can choose to show only the first image of the gallery. If you change the order of the images in the gallery, the shortcode always shows the first one.

* Examples:

`[tttgallery id="42" template="default"]` 

It means use the gallery with ID 42 and use the default gallery template. 

`[ttt-gallery-image id="42" position="4" template="fancybox"]` 

Only shows the 4th image from the gallery with ID 42 and use the fancybox template.


## Custom Gallery Template

If you have some knowledge of code it's easy to create your own customized template:

1. Create a folder in your theme inside ttt-gallery folder.

ie: `/wp-content/themes//my-theme/ttt-gallery/my-custom-template`

2. This folder contains all the files to create the gallery (HTML, CSS & JS) and a style.php file to load the javascript and stylesheet in your theme.

ie: 

`/my-custom-template/style.php
/my-custom-template/template.php
/my-custom-template/css/my-template.css
/my-custom-template/images/close.png
/my-custom-template/js/my-template.js`

This is a **style.php** example:

```php
wp_enqueue_script( 'my-template-styles', plugins_url('my-custom-template/js/my-template.js' , dirname(__FILE__) ), array('jquery'),'1.0', true);`
wp_enqueue_style( 'my-template-styles',  plugins_url('my-custom-template/css/my-template.css' , dirname(__FILE__) ) );
```

This is a **template.php** example:

```php
<div class="my-custom-template">
	<h2><?php $ttt_gallery->description; ?></h2>
	<?php foreach( $ttt_gallery->medias as $ttt_media ): ?>
	<a href="<?php echo $ttt_media['sizes']['full']['url']; ?>" title="<?php echo $ttt_media['description']; ?>" rel="<?php echo $ttt_gallery->rel; ?>">
		<img src="<?php echo $ttt_media['sizes']['thumbnail']['url']; ?>">
	</a>
	<?php endforeach; ?>
</div>
```

TTT Gallery use the same image metadata than WordPress Core. These are some of the parameters you can use in your template:

* **Gallery Description** `<?php $ttt_gallery->description; ?>`
* **Image Description** `<?php echo $ttt_media['description']; ?`
* **Image Rel HTML parameter** `<?php echo $ttt_gallery->rel; ?>`
* **Image Thumbnail URL** `<?php echo $ttt_media['sizes']['THUMBNAIL_SIZE']['url']; ?>` <

If you want to use an specific thumbnail size for your theme,simply write the thumbnail name you want to use, like: thumbnail, medium, large, full, my-custom-thumbnail-size. More info: https://codex.wordpress.org/Post_Thumbnails#Thumbnail_Sizes [Thumbnail Sizes](http://wordpress.org/ "Codex Wordpress.org")


## Hacks

`/* Remove Gallery Metabox from specific content */`


## Installation

This section describes how to install the plugin and get it working.

1. Upload `ttt-gallery` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


## Frequently Asked Questions

**Does TTT Gallery create a new folder for galleries?**

No. The images are where they have to be, in /uploads

**Does TTT Gallery create a Custom Post Type to manage galleries?**

No. You will find under the Media menu a new sectión to see all created galleries or create new ones. Media -> TTT Gallery

**Can I use my own thumbnail sizes in the galleries?**

Yes :)

**Is it possible to remove the Gallery metabox from a specific Custom Post Type?**

To accomplish it you have to add a hack code in your functions.php file. Check the Hacks section.

**Can I make my own Gallery Template?**

Yes, TTT Gallery was created to give total freedom to developers combined with the best integration with the code WP standards.

1. Create a new folder in your theme `/wp-content/themes/my-theme/ttt-gallery/`
2. Copy `default` folder & files from `/wp-content/plugins/ttt-gallery/template/front/default` to `/wp-content/themes/my-theme/ttt-gallery/my-custom-template/`

IMPORTANT: The name of your folder is the one you should use in the shortcode parameter.
