<div class="xpro-hotspot-wrapper">
	<!-- Image -->
	<figure class="xpro-hotspot-image">
		<?php if ( $settings['image'] ) {
			echo wp_kses_post( \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
		} ?>
	</figure>

	<?php foreach ( $settings['hotspot_items'] as $i => $item ) : ?>
	<span class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> xpro-hotspot-item">

		<span class="xpro-hotspot-item-wrap xpro-hotspot-type-<?php echo esc_attr( $settings['type'] ); ?> ">

			<?php if ( 'yes' === $item['show_tooltip'] ) : ?>
			<!-- Tooltip -->
			<span class="xpro-hotspot-tooltip-text xpro-hotspot-<?php echo esc_attr( $item['position'] ); ?>">
				<?php echo wp_kses_post( $item['tooltip_text'] ); ?>
			</span>
			<?php endif; ?>

			<?php
			if ( 'icon' === $item['hot_media_type'] ) {
				\Elementor\Icons_Manager::render_icon( $item['hot_icon'], array( 'aria-hidden' => 'true' ) );
			}
			if ( 'image' === $item['hot_media_type'] ) {
				echo wp_kses_post( \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'spots_thumbnail', 'spots_image' ) );
			}
			?>
		</span>
	</span>
	<?php endforeach; ?>

</div>
