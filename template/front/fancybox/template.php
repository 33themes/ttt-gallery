
<?php $n = 0; ?>
<div class="fancybox">
	<?php foreach( $ttt_gallery->medias as $n => $ttt_media ): ?>
	
		<?php if ($n == 0): ?>

		<a class="tttgallery-fancybox" href="<?php echo $ttt_media['sizes']['full']['url']; ?>" data-fancybox-group="<?php echo $ttt_gallery->rel; ?>" title="<?php echo $ttt_media['description']; ?>">
			<img src="<?php echo $ttt_media['sizes']['medium']['url']; ?>" />
		</a>
		<br>
		<br>

		<?php else: ?>
		<a class="tttgallery-fancybox" href="<?php echo $ttt_media['sizes']['full']['url']; ?>" data-fancybox-group="<?php echo $ttt_gallery->rel; ?>" title="<?php echo $ttt_media['description']; ?>">
			<img src="<?php echo $ttt_media['sizes']['thumbnail']['url']; ?>" />
		</a>
		<?php endif; ?>

	<?php endforeach; ?>
</div>
