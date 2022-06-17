<?php
use Elementor\Icons_Manager;
?>
<div class="xpro-elementor-scroll-top-btn animated <?php echo esc_attr( $settings['scroll_top_animation'] ); ?> xpro-elementor-scroll-top-btn-<?php echo esc_attr( $settings['layout'] ); ?> xpro-elementor-scroll-top-btn-fixed-align-<?php echo esc_attr( $settings['fixed_align'] ); ?>">
	<div class="xpro-elementor-scroll-top-btn-inner">
		<?php if ( $settings['icon']['value'] && 'left' === $settings['icon_align'] ) : ?>
			<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
		<?php endif; ?>
		<?php if ( $settings['text'] ) : ?>
			<span class="xpro-elementor-scroll-top-btn-txt"><?php echo esc_attr( $settings['text'] ); ?></span>
		<?php endif; ?>
		<?php if ( $settings['icon']['value'] && 'right' === $settings['icon_align'] ) : ?>
			<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
		<?php endif; ?>
	</div>
</div>
