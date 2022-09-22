<?php
/**
 * Displays the search icon and modal
 *
 * @package WordPress
 
 * @since 1.0.0
 */

?>
<div class="search-inline search-menu-container goldencat-toggle-menu">
	<div class="search-inline-inner">
		<button class="toggle search-untoggle close-search-toggle fill-children-current-color search-alt-toggle-menu">
			<span class="screen-reader-text"><?php _e( 'Close search', 'goldencat' ); ?></span>
			<?php echo goldencat_icon( 'ui', 'close' ); ?>
		</button>

		<form class="search-inline-form" action="<?php echo home_url( '/' ) ?>" method="get">

			<label for="search">
			<input class="search-input" type="text" name="s" id="s" value="<?php the_search_query(); ?>" /></label>
			<input type="submit" alt="Search" value="Rechercher" />
		</form>

	</div>

</div>
