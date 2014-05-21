<div class="wrap">
	<div class="scrollbar">
		<div class="handle" style="-webkit-transform: translateZ(0px) translateX(908px); width: 232px;">
			<div class="mousearea"></div>
		</div>
	</div>

	<div class="frame effects" id="effects" style="overflow: hidden;">
		<ul class="clearfix" style="-webkit-transform: translateZ(0px) translateX(-4946px); width: 5610px;">
		<?php $_count = 0; ?>
		<?php foreach( $ttt_gallery->medias as $ttt_media ): ?>
			<?php if ($_count <= 0): ?>
			<li class="single first">
			<?php elseif ( count($ttt_gallery->medias) == $_count+1 ): ?>
			<li class="single last">
			<?php else: ?>
			<li class="single">
			<?php endif; ?>
				<a href="<?php echo $ttt_media['sizes']['full']['url']; ?>" rel="lightbox[<?php echo $ttt_gallery->rel; ?>]" title="<?php echo $ttt_media['description']; ?>">
					<img src="<?php echo $ttt_media['sizes']['thumbnail']['url']; ?>" alt="Plants: image 1 0f 4 thumb" />
				</a>
			</li>
			<?php $_count++; ?>
		<?php endforeach; ?>
		</ul>
	</div>

	<div class="controls center">
		<button class="btn prev"><?php _e('prev','bullhotels'); ?></button>
		<button class="btn next disabled" disabled=""><?php _e('next','bullhotels'); ?></button>
	</div>
</div>