<?php

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

?>
<div class="xpro-horizontal-timeline-wrapper 
<?php echo esc_attr( ( 'yes' === $settings['reverse'] ) ? ' xpro-horizontal-timeline-reverse-yes' : '' ); ?> xpro-horizontal-timeline-<?php echo esc_attr( $settings['direction'] ); ?>">
	<div class="xpro-horizontal-timeline-inner">
		<div class="xpro-horizontal-timeline owl-carousel">
			<?php foreach ( $settings['horizontal_timeline_item'] as $i => $item ) : ?>
				<div class="xpro-horizontal-timeline-item">
					<div class="xpro-horizontal-timeline-date<?php echo esc_attr( ( 'yes' === $settings['reverse'] ) ? ' xpro-horiz-equal-height' : '' ); ?>">
						<div class="xpro-horizontal-timeline-dates">
							<?php if ( 'image' === $item['date_media_type'] || 'custom' === $item['date_media_type'] ) : ?>
								<!-- Media Type -->
								<?php
								if ( 'image' === $item['date_media_type'] && $item['date_image'] ) {
									echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $item, 'date_image_thumbnail', 'date_image' ) );
								}

								if ( 'custom' === $item['date_media_type'] && $item['title'] && $item['date_custom'] ) {
									?>
									<div class="xpro-horizontal-timeline-title"><?php xpro_elementor_kses( $item['title'] ); ?></div>
									<span class="xpro-horizontal-timeline-time"><?php xpro_elementor_kses( $item['date_custom'] ); ?></span>
									<?php
								}
								?>
							<?php endif; ?>
						</div>
					</div>

					<div class="xpro-horizontal-timeline-media-box">
						<span class="xpro-horizontal-timeline-bullet-line"></span>
						<?php if ( 'icon' === $item['bullet_media_type'] || 'image' === $item['bullet_media_type'] || 'custom' === $item['bullet_media_type'] ) : ?>
							<!-- Media Type -->
							<div class="xpro-horizontal-timeline-media">
								<?php
								if ( 'icon' === $item['bullet_media_type'] && $item['icon'] ) {
									Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) );
								}

								if ( 'image' === $item['bullet_media_type'] && $item['image'] ) {
									echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $item, 'bullet_image_thumbnail', 'image' ) );
								}

								if ( 'custom' === $item['bullet_media_type'] && $item['custom'] ) {
									?>
									<span class="xpro-horizontal-timeline-media-custom"><?php xpro_elementor_kses( $item['custom'] ); ?></span>
								<?php } ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="xpro-horizontal-timeline-content xpro-horiz-equal-height">
						<div class="xpro-horizontal-timeline-content-inner">
							<?php if ( 'none' !== $item['content_media_type'] ) : ?>
								<!-- Media Type -->
								<div class="xpro-horizontal-timeline-content-media">
									<?php
									if ( 'image' === $item['content_media_type'] && $item['content_image'] ) {
										echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $item, 'content_image_thumbnail', 'content_image' ) );
									}

									?>
								</div>
							<?php endif; ?>

							<div class="xpro-horizontal-timeline-content-desc">

								<?php if ( $item['sub_title'] ) : ?>
									<!-- Title -->
									<h2 class="xpro-horizontal-timeline-sub-title"><?php echo esc_html( $item['sub_title'] ); ?></h2>
								<?php endif; ?>

								<?php if ( $item['description'] ) : ?>
									<p class="xpro-horizontal-timeline-text"><?php echo wp_kses_post( $item['description'] ); ?></p>
								<?php endif; ?>

							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( $settings['nav'] ) : ?>
			<button type="button" class="xpro-horizontal-timeline-prev">
				<i class="eicon-chevron-left" aria-hidden="true"></i>
			</button>
			<button type="button" class="xpro-horizontal-timeline-next">
				<i class="eicon-chevron-right" aria-hidden="true"></i>
			</button>
		<?php endif; ?>
	</div>
</div>
