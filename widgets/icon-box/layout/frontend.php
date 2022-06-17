<?php

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

$html_tag = ( $settings['link'] ) ? 'a' : 'div';
$attr     = $settings['link']['url'] ? ' href="' . $settings['link']['url'] . '"' : '';
$attr    .= $settings['link']['is_external'] ? ' target="_blank"' : '';
$attr    .= $settings['link']['nofollow'] ? ' rel="nofollow"' : '';

?>

<<?php echo esc_attr( $html_tag ); ?> <?php xpro_elementor_kses( $attr ); ?> class="xpro-box-icon-wrapper">
<div class="xpro-box-icon-wrapper-inner">
	<?php if ( $settings['badge_text'] ) : ?>
		<span <?php $this->print_render_attribute_string( 'badge_text' ); ?>><?php echo esc_html( $settings['badge_text'] ); ?></span>
	<?php endif; ?>

	<?php if ( 'icon' === $settings['media_type'] || 'image' === $settings['media_type'] ) : ?>
		<span class="xpro-box-icon-item">
				<?php
				if ( 'icon' === $settings['media_type'] && $settings['icon'] ) {
					Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) );
				}
				if ( 'image' === $settings['media_type'] ) {
					echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
				}
				?>
			</span>
	<?php endif; ?>

	<span class="xpro-box-icon-content">
	<?php
	if ( $settings['title'] ) :
		printf( '<%1$s %2$s>%3$s</%1$s>', tag_escape( $settings['title_tag'] ), $this->get_render_attribute_string( 'title' ), $settings['title'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	endif;
	?>

		<?php if ( $settings['description'] ) : ?>
			<p <?php $this->print_render_attribute_string( 'description' ); ?>><?php xpro_elementor_kses( $settings['description'] ); ?></p>
		<?php endif; ?>
			</span>

</div>
</<?php echo esc_attr( $html_tag ); ?>>
