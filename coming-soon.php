<?php
/**
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Golden_Cat
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>><?php

wp_body_open();	

if ( have_posts() ) :

	/* Start the Loop */
	while ( have_posts() ) :
		the_post();
		
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <div class="entry-content">
                <?php
                the_content();

                ?>
            </div><!-- .entry-content -->

        </article><!-- #post-<?php the_ID(); ?> -->

        <?php

	endwhile;

else :

	get_template_part( 'template-parts/content/content', 'none' );

endif;

wp_footer(); ?>

</body>
</html>
