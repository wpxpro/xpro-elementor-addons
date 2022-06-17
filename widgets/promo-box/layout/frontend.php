<?php

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

$html_tag = ( $settings['title_link']['url'] ) ? 'a' : 'h3';
$attr     = $settings['title_link']['url'] ? ' href="' . $settings['title_link']['url'] . '"' : '';
$attr    .= $settings['title_link']['is_external'] ? ' target="_blank"' : '';
$attr    .= $settings['title_link']['nofollow'] ? ' rel="nofollow"' : '';
?>
<div class="xpro-promo-box-wrapper">
	<div class="xpro-promo-box-inner">

		<?php if ( $settings['badge_text'] ) : ?>
			<span class="xpro-promo-box-badge xpro-badge xpro-badge-<?php echo esc_attr( $settings['badge_position'] ); ?>"><?php echo esc_attr( $settings['badge_text'] ); ?></span>
		<?php endif; ?>

		<div class="xpro-promo-box-content">
			<?php if ( 'none' !== $settings['media_type'] && 'before' === $settings['image_position'] ) : ?>
				<div class="xpro-promo-box-media">
					<?php
					if ( 'image' === $settings['media_type'] ) {
						echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
					}
					?>
				</div>
			<?php endif; ?>

			<?php if ( $settings['sub_title'] && 'before' === $settings['sub_title_position'] ) : ?>
				<h4 class="xpro-promo-box-sub-title"><?php echo esc_html( $settings['sub_title'] ); ?></h4>
			<?php endif; ?>

			<?php if ( $settings['title'] ) : ?>
			<<?php echo esc_attr( $html_tag ); ?> <?php xpro_elementor_kses( $attr ); ?> class="xpro-promo-box-title">
				<?php echo esc_html( $settings['title'] ); ?>
		</<?php echo esc_attr( $html_tag ); ?>>
	<?php endif; ?>

		<?php if ( $settings['sub_title'] && 'after' === $settings['sub_title_position'] ) : ?>
			<h4 class="xpro-promo-box-sub-title"><?php echo esc_html( $settings['sub_title'] ); ?></h4>
		<?php endif; ?>

		<?php if ( $settings['description'] ) : ?>
			<div class="xpro-promo-box-desc"><?php echo wp_kses_post( $settings['description'] ); ?></div>
		<?php endif; ?>

		<?php if ( 'none' !== $settings['media_type'] && 'after' === $settings['image_position'] ) : ?>
			<div class="xpro-promo-box-media">
				<?php
				if ( 'image' === $settings['media_type'] ) {
					echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
				}
				?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $settings['button_text'] ) : ?>
		<?php
		$html_tag = ( $settings['button_link']['url'] ) ? 'a' : 'div';
		$attr     = $settings['button_link']['url'] ? ' href="' . $settings['button_link']['url'] . '"' : '';
		$attr    .= $settings['button_link']['is_external'] ? ' target="_blank"' : '';
		$attr    .= $settings['button_link']['nofollow'] ? ' rel="nofollow"' : '';
		?>

	<<?php echo esc_attr( $html_tag ); ?> <?php xpro_elementor_kses( $attr ); ?> class="xpro-promo-box-btn
	xpro-promo-box-align-<?php echo esc_attr( $settings['icon_align'] ); ?>">
		<?php if ( $settings['icon']['value'] ) : ?>
			<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
	<?php endif; ?>

	<span class="xpro-promo-box-btn-text"><?php echo esc_html( $settings['button_text'] ); ?></span>
</<?php echo esc_attr( $html_tag ); ?>>
<?php endif; ?>
</div>
</div>
