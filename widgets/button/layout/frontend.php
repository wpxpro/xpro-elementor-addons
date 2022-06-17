<?php

use Elementor\Icons_Manager;

$html_tag = ( $settings['link']['url'] ) ? 'a' : 'span';
$attr     = ( $settings['button_css_id'] ) ? ' id="' . $settings['button_css_id'] . '"' : '';
$attr    .= $settings['link']['is_external'] ? ' target="_blank"' : '';
$attr    .= $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
$attr    .= $settings['link']['url'] ? ' href="' . $settings['link']['url'] . '"' : '';

$hover_animation = ( '2d-transition' === $settings['hover_animation'] ) ? 'xpro-button-2d-animation ' . $settings['hover_2d_css_animation'] : ( ( 'background-transition' === $settings['hover_animation'] ) ? 'xpro-button-bg-animation ' . $settings['hover_background_css_animation'] : ( ( 'unique' === $settings['hover_animation'] ) ? 'xpro-elementor-button-hover-style-' . $settings['hover_unique_animation'] : 'xpro-elementor-button-animation-none' ) );

?>

<<?php echo esc_attr( $html_tag ); ?> <?php xpro_elementor_kses( $attr ); ?> class="xpro-elementor-button <?php echo esc_attr( $hover_animation ); ?> xpro-align-icon-<?php echo ( 'left' === $settings['icon_align'] ) ? 'left' : 'right'; ?>">
<span class="xpro-elementor-button-inner">
<?php if ( $settings['icon']['value'] ) { ?>
<span class="xpro-elementor-button-media"><?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?></span>
<?php } ?>
<span class="xpro-button-text"><?php echo esc_html( $settings['text'] ); ?></span>
</span>
</<?php echo esc_attr( $html_tag ); ?>>
