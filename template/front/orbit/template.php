<ul data-orbit data-options="timer_speed: 4000; bullets: false; slide_number_class:hide;next_class: hide; prev_class: hide;">
	<?php foreach( $ttt_gallery->medias as $ttt_media ): ?>
	<li>
		<?php $_attachement = wp_get_attachment_image_src( $ttt_media['id'], 'slider' ); ?>
		<img src="<?php echo $_attachement[0]; ?>" alt="<?php echo $ttt_media['title']; ?>" width="1000" height="720">
	</li>
	<?php endforeach; ?>
</ul>