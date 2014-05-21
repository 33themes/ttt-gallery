
<div class="imageRow">
	<div class="set">
		<?php $_count = 0; ?>
		<?php foreach( $ttt_gallery->medias as $ttt_media ): ?>
			<?php if ($_count <= 0): ?>
			<div class="single first">
			<?php elseif ( count($ttt_gallery->medias) == $_count+1 ): ?>
			<div class="single last">
			<?php else: ?>
			<div class="single">
			<?php endif; ?>
				<a href="<?php echo $ttt_media['sizes']['full']['url']; ?>" rel="lightbox[<?php echo $ttt_gallery->rel; ?>]" title="<?php echo $ttt_media['description']; ?>">
					<img src="<?php echo $ttt_media['sizes']['thumbnail']['url']; ?>" />
				</a>
			</div>
			<?php $_count++; ?>
		<?php endforeach; ?>
	</div>
</div>
