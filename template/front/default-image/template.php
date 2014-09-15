<div class="tttgallery oneimage">
	<h2><?php $ttt_gallery->description; ?></h2>
	<?php foreach( $ttt_gallery->medias as $ttt_media ): ?>
	<a class="thickbox" href="<?php echo $ttt_media['sizes']['full']['url']; ?>" title="<?php echo $ttt_media['description']; ?>" rel="<?php echo $ttt_gallery->rel; ?>">
		<img src="<?php echo $ttt_media['sizes']['thumbnail']['url']; ?>">
	</a>
	<?php endforeach; ?>
</div>
