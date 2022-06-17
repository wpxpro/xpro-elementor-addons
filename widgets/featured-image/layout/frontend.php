<?php

global $post;

$animation = ( $settings['image_hover_animation'] ) ? ' elementor-animation-' . $settings['image_hover_animation'] : '';
$this->add_render_attribute( 'wrapper', 'class', 'xpro-featured-image' . $animation );
$placeholder = '';

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
	$placeholder = '<img src="' . esc_url( \Elementor\Utils::get_placeholder_image_src() ) . '" alt="placeholder">';
}
?>
<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
<?php echo wp_kses_post( ( get_post_thumbnail_id( $post->ID ) ) ? wp_get_attachment_image( get_post_thumbnail_id( get_the_ID() ), $settings['thumbnail_size'] ) : $placeholder ); ?>
</div>
<?php
