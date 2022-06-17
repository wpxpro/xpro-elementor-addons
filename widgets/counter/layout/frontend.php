<?php
$html_tag = ( $settings['link']['url'] ) ? 'a' : 'div';
$attr     = $settings['link']['url'] ? ' href="' . $settings['link']['url'] . '"' : '';
$attr    .= $settings['link']['is_external'] ? ' target="_blank"' : '';
$attr    .= $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
?>

<<?php echo esc_attr( $html_tag ); ?> <?php xpro_elementor_kses( $attr ); ?> class="xpro-counter-wrapper">
<div class="xpro-counter-wrapper-inner">
	<?php if ( $settings['badge_text'] ) : ?>
		<div <?php $this->print_render_attribute_string( 'badge_text' ); ?>><?php echo esc_html( $settings['badge_text'] ); ?></div>
	<?php endif; ?>

	<?php if ( ! empty( $settings['value'] ) ) : ?>
		<div class="xpro-counter-item"><?php echo esc_html( $settings['value'] ); ?><?php echo esc_html( $settings['symbol'] ); ?></div>
	<?php endif; ?>

	<div class="xpro-counter-content">
	<?php
	if ( $settings['title'] ) :
		printf( '<%1$s %2$s>%3$s</%1$s>', tag_escape( $settings['title_tag'] ), $this->get_render_attribute_string( 'title' ), $settings['title'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	endif;
	if ( $settings['description'] ) :
		?>
		<p <?php $this->print_render_attribute_string( 'description' ); ?>><?php echo esc_html( $settings['description'] ); ?></p>
	<?php endif; ?>
	</div>
</div>
</<?php echo esc_attr( $html_tag ); ?>>
