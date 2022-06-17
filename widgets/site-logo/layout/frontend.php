<?php

use Elementor\Utils;

$image = ( 'custom' === $settings['logo_type'] ) ? $settings['custom_logo']['id'] : get_theme_mod( 'custom_logo' );
$url   = ( 'custom' === $settings['link_type'] && $settings['link']['url'] ) ? $settings['link']['url'] : get_home_url();
$attr  = ( 'custom' === $settings['link_type'] && $settings['link']['is_external'] ) ? ' target="_blank"' : '';
$attr .= ( 'custom' === $settings['link_type'] && $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '';
?>
<a href="<?php echo esc_url( $url ); ?>"<?php xpro_elementor_kses( $attr ); ?>>
	<div class="xpro-site-logo">
		<?php
		if ( $image ) {
			echo wp_get_attachment_image( $image, $settings['thumbnail_size'] );
		} else {
			echo '<img src="' . esc_url( Utils::get_placeholder_image_src() ) . '" alt="placeholder"/>';
		}
		?>
	</div>
</a>
