<ul class="rslides" id="slider1">
	<?php foreach( $ttt_gallery->medias as $ttt_media ): ?>
	<li>
		<?php $_attachement = wp_get_attachment_image_src( $ttt_media['id'], 'slider' ); ?>
		<img src="<?php echo $_attachement[0]; ?>" alt="<?php echo $ttt_media['title']; ?>" width="1000" height="720">
	</li>
	<?php endforeach; ?>
</ul>

