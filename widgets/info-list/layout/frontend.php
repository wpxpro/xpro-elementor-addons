<?php
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
?>
<ul class="xpro-infolist-wrapper xpro-infolist-layout-<?php echo esc_attr( $settings['layout'] ); ?>">
	<?php foreach ( $settings['item'] as $i => $item ) { ?>
		<li class="xpro-infolist-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
			<?php
			$target   = $item['link']['is_external'] ? ' target="_blank"' : '';
			$nofollow = $item['link']['nofollow'] ? ' rel="nofollow"' : '';
			echo ( $item['link']['url'] ) ? '<a href="' . esc_url( $item['link']['url'] ) . '" ' . esc_attr( $target ) . esc_attr( $nofollow ) . '>' : '';
			?>
			<?php if ( 'none' !== $item['media_type'] ) : ?>
				<div class="xpro-infolist-media xpro-infolist-media-type-<?php echo esc_attr( $item['media_type'] ); ?>">
					<?php
					if ( 'icon' === $item['media_type'] && $item['icon'] ) {
						Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) );
					}

					if ( 'image' === $item['media_type'] && $item['image'] ) {
						echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'image' ) );
					}

					if ( 'custom' === $item['media_type'] && $item['custom'] ) {
						echo '<i class="xpro-infolist-custom">' . esc_html( $item['custom'] ) . '</i>';
					}
					?>
				</div>
			<?php endif; ?>

			<div class="xpro-infolist-content">
				<?php if ( $item['title'] ) : ?>
					<h3 class="xpro-infolist-title"><?php echo esc_html( $item['title'] ); ?></h3>
				<?php endif; ?>
				<?php if ( $item['description'] ) : ?>
					<p class="xpro-infolist-desc"><?php echo wp_kses_post( $item['description'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php echo ( $item['link']['url'] ) ? '</a>' : ''; ?>
		</li>
	<?php } ?>
</ul>
