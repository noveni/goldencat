<?php
/**
 * A template partial to output pagination
 * 
 */


// Previous/next post navigation.
$goldencat_next = is_rtl() ? goldencat_icon( 'ui', 'arrow_left' ) : goldencat_icon( 'ui', 'arrow_right' );
$goldencat_prev = is_rtl() ? goldencat_icon( 'ui', 'arrow_right' ) : goldencat_icon( 'ui', 'arrow_left' );

$goldencat_next_label     = esc_html__( 'Newer posts', 'goldencat' );
$goldencat_previous_label = esc_html__( 'Older posts', 'goldencat' );

$prev_text = sprintf(
	'%s <span class="screen-reader-text">%s</span>',
	$goldencat_prev,
	$goldencat_previous_label
);

$next_text = sprintf(
	'<span class="screen-reader-text">%s</span> %s',
	$goldencat_next_label,
	$goldencat_next
);

the_posts_pagination(
	array(
		'before_page_number' => '',
		'mid_size'           => 1,
		'prev_text'          => $prev_text,
		'next_text'          => $next_text
	)
);
