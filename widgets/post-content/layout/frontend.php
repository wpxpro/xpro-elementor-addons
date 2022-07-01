<?php

use Elementor\Plugin;

$limit = ( 'excerpt' === $settings['content_type'] && $settings['limit']['size'] ) ? $settings['limit']['size'] : '';

if ( is_home() || is_archive() || is_singular( array( 'post', 'page' ) ) ) : ?>
	<div class="xpro-elementor-content">
		<?php
		if ( 'excerpt' === $settings['content_type'] ) {
			echo wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), $limit );
		} else {
			$content = apply_filters( 'the_content', get_post_field( 'post_content', get_the_ID() ) );
			if ( Plugin::$instance->editor->is_edit_mode() && empty( $content ) ) {
				$content = 'This is a dummy text to demonstration purposes. It will be replaced with the post content or excerpt.';
			}
			echo $content;
		}
		?>
	</div>
<?php else : ?>
	<div class="xpro-elementor-content">
		<?php
		$content = 'This is a dummy text to demonstration purpose. It will be replaced with the post content or excerpt.';
		if ( 'excerpt' === $settings['content_type'] ) {
			echo wp_trim_words( wp_strip_all_tags( $content ), $limit );
		} else {
			echo $content;
		}
		?>
	</div>
	<?php
endif;
