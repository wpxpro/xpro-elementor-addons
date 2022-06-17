<div class="xpro-block-quote-wrapper xpro-block-quote-<?php echo esc_attr( $settings['quote_position'] ); ?>">
	<div class="xpro-block-quote-inner">
		<?php if ( ( 'layout-3' !== $settings['quote_position'] && 'layout-6' !== $settings['quote_position'] ) ) : ?>
		<span class="xpro-block-quote-icon">
			<svg class="" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
			<path d="M13 14.725c0-5.141 3.892-10.519 10-11.725l.984 2.126c-2.215.835-4.163 3.742-4.38 5.746 2.491.392 4.396 2.547 4.396 5.149 0 3.182-2.584 4.979-5.199 4.979-3.015 0-5.801-2.305-5.801-6.275zm-13 0c0-5.141 3.892-10.519 10-11.725l.984 2.126c-2.215.835-4.163 3.742-4.38 5.746 2.491.392 4.396 2.547 4.396 5.149 0 3.182-2.584 4.979-5.199 4.979-3.015 0-5.801-2.305-5.801-6.275z"></path>
		</svg>
		</span>
		<?php endif; ?>

		<div class="xpro-block-quote-content">
			<?php if ( $settings['image'] || 'layout-4' === $settings['quote_position'] ) : ?>
				<span class="xpro-block-quote-content-img">
				<?php
				if ( $settings['image'] ) {
					echo wp_kses_post( \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
				}
				?>
			</span>
			<?php endif; ?>

			<div class="xpro-block-quote-content-wrap">

				<?php if ( $settings['quote_description'] ) : ?>
				<!-- Text -->
				<p class="xpro-block-quote-text"><?php xpro_elementor_kses( $settings['quote_description'] ); ?></p>
				<?php endif; ?>

				<div class="xpro-block-quote-desc">
				<?php if ( $settings['quote_title'] ) : ?>
				<!-- Title -->
				<span class="xpro-block-quote-title"><?php echo esc_html( $settings['quote_title'] ); ?></span>
				<?php endif; ?>

				<?php if ( $settings['quote_designation'] ) : ?>
				<!-- Designation -->
				<span class="xpro-block-quote-designation"><?php echo esc_html( $settings['quote_designation'] ); ?></span>
				<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
</div>
