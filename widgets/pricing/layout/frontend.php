<div class="xpro-pricing-item">

	<?php

	use Elementor\Group_Control_Image_Size;
	use Elementor\Icons_Manager;

	if ( 'yes' === $settings['show_badge'] && ! empty( $settings['badge_text'] ) ) : ?>
		<span class="xpro-badge xpro-badge-<?php echo esc_attr( $settings['badge_position'] ); ?>"><?php echo esc_html( $settings['badge_text'] ); ?></span>
	<?php endif; ?>

	<?php if ( 'before_header' === $settings['media_position'] ) { ?>
		<?php if ( 'icon' === $settings['media_type'] && $settings['icon']['value'] ) : ?>
			<div class="xpro-pricing-icon">
				<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( 'image' === $settings['media_type'] && $settings['image']['url'] ) : ?>
			<div class="xpro-pricing-media">
				<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) ); ?>
			</div>
		<?php endif; ?>
	<?php } ?>

	<?php if ( ! empty( $settings['title'] ) ) : ?>
		<div class="xpro-pricing-title-wrapper">
			<h2 class="xpro-pricing-title"><?php echo esc_html( $settings['title'] ); ?></h2>
		</div>
	<?php endif; ?>

	<?php if ( 'after_header' === $settings['media_position'] ) { ?>
		<?php if ( 'icon' === $settings['media_type'] && $settings['icon']['value'] ) : ?>
			<div class="xpro-pricing-icon">
				<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( 'image' === $settings['media_type'] && $settings['image']['url'] ) : ?>
			<div class="xpro-pricing-media">
				<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) ); ?>
			</div>
		<?php endif; ?>
	<?php } ?>

	<?php if ( 'before_features' === $settings['price_position'] ) { ?>
		<div class="xpro-pricing-price-box xpro-pricing-price-box-style-<?php echo esc_attr( $settings['price_style'] ); ?>">
			<div class="xpro-pricing-price-tag">
				<span class="xpro-pricing-currency">
				<?php echo( ( 'none' !== $settings['currency'] ) ? self::get_currency_symbol( $settings['currency'] ) : esc_html( $settings['currency_custom'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
				<span class="xpro-pricing-price">
					<?php echo esc_html( $settings['price'] ); ?>
				</span>
			</div>

			<?php if ( ! empty( $settings['period'] ) ) : ?>
				<p class="xpro-pricing-price-period"><?php echo esc_html( $settings['period'] ); ?></p>
			<?php endif; ?>

		</div>
	<?php } ?>

	<?php if ( 'before_features' === $settings['description_position'] && $settings['item_description'] ) { ?>
		<div class="xpro-pricing-description-wrapper">
			<div class="xpro-pricing-description">
				<?php echo wp_kses_post( $settings['item_description'] ); ?>
			</div>
		</div>
	<?php } ?>

	<?php
	if ( 'before_features' === $settings['button_position'] && $settings['button_title'] ) {
		$target   = $settings['button_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['button_link']['nofollow'] ? ' rel="nofollow"' : '';
		echo '<div class="xpro-pricing-btn-wrapper"><a class="xpro-pricing-btn" href="' . esc_url( $settings['button_link']['url'] ) . '"' . esc_attr( $target ) . esc_attr( $nofollow ) . '>' . $settings['button_title'] . '</a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>

	<?php if ( 'yes' === $settings['show_feature'] ) : ?>
		<div class="xpro-pricing-features">

			<?php if ( ! empty( $settings['features_title'] ) ) : ?>
				<h4 class="xpro-pricing-features-title"><?php echo esc_html( $settings['features_title'] ); ?></h4>
			<?php endif; ?>

			<ul class="xpro-pricing-features-list">
				<?php foreach ( $settings['feature_items'] as $i => $item ) : ?>
					<li class="<?php echo esc_attr( $item['status'] ); ?>">

						<?php if ( $item['icon'] ) : ?>
							<span class="xpro-pricing-feature-icon"><?php Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) ); ?></span>
						<?php endif; ?>

						<?php if ( $item['title_text'] ) : ?>
							<span class="xpro-pricing-feature-title">
							<?php echo esc_html( $item['title_text'] ); ?>
								<?php if ( $item['tooltip_text'] ) : ?>
								<i class="fas fa-question xpro-pricing-tooltip-toggle">
								<span class="xpro-pricing-tooltip">
									<?php echo wp_kses_post( $item['tooltip_text'] ); ?>
								 </span>
							</i>
								<?php endif; ?>
					</span>
						<?php endif; ?>

					</li>
				<?php endforeach; ?>
			</ul>

		</div>
	<?php endif; ?>

	<?php if ( 'after_features' === $settings['description_position'] && $settings['item_description'] ) { ?>
		<div class="xpro-pricing-description-wrapper">
			<div class="xpro-pricing-description">
				<?php echo wp_kses_post( $settings['item_description'] ); ?>
			</div>
		</div>
	<?php } ?>

	<?php if ( 'yes' === $settings['show_separator'] ) { ?>
		<div class="xpro-pricing-separator"></div>
	<?php } ?>

	<?php if ( 'after_features' === $settings['price_position'] ) { ?>
		<div class="xpro-pricing-price-box xpro-pricing-price-box-style-<?php echo esc_attr( $settings['price_style'] ); ?>">
			<div class="xpro-pricing-price-tag">
				<span class="xpro-pricing-currency">
		<?php echo( ( 'none' !== $settings['currency'] ) ? self::get_currency_symbol( $settings['currency'] ) : esc_html( $settings['currency_custom'] ) );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
				<span class="xpro-pricing-price">
					<?php echo esc_html( $settings['price'] ); ?>
				</span>
			</div>

			<?php if ( ! empty( $settings['period'] ) ) : ?>
				<p class="xpro-pricing-price-period"><?php echo esc_html( $settings['period'] ); ?></p>
			<?php endif; ?>

		</div>
	<?php } ?>

	<?php
	if ( 'after_features' === $settings['button_position'] && $settings['button_title'] ) {
		$target   = $settings['button_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['button_link']['nofollow'] ? ' rel="nofollow"' : '';
		echo '<div class="xpro-pricing-btn-wrapper"><a class="xpro-pricing-btn" href="' . esc_url( $settings['button_link']['url'] ) . '"' . esc_attr( $target ) . esc_attr( $nofollow ) . '>' . esc_html( $settings['button_title'] ) . '</a></div>';
	}
	?>

</div>

