<?php
/**
 * Server-side rendering of the `goldencat/query-filters` block.
 *
 * @package WordPress
 */

function goldencat_query_filters_dropdown_list( $taxonomy, $tax_query ) {

	$terms = get_terms( array(
		'taxonomy' => $taxonomy,
		'orderby'    => 'count',
		'order' => 'DESC',
		'hide_empty' => true,
	));

	$list = [];
	foreach ( $terms as $term ) {
		$checked = '';
		if ( isset( $tax_query[$taxonomy]) && !empty($tax_query[$taxonomy]) ) {
			if ( in_array( $term->term_id, $tax_query[$taxonomy] ) ) {
				$checked = ' checked';
			}
		}

		$checkbox = sprintf(
			'<li><label><input 
				type="checkbox" 
				name="%1$s-term-query-filter" 
				value="%2$s" 
				data-taxonomy="%1$s" 
				data-slug="%3$s" 
				%4$s />&nbsp;%5$s</label></li>',
			$taxonomy,
			$term->term_id,
			$term->slug, 
			$checked,
			$term->name
		);

		$list[] = $checkbox;
	}

	return sprintf(
		'<ul>%1$s</ul>',
		implode('', $list)
	);
}

function goldencat_query_filters_dropdown( $taxonomy, $tax_query ) {


	$tax = get_taxonomy( $taxonomy );
	$label = $tax->label;

	// return goldencat_query_filters_dropdown_list( $taxonomy );
	return sprintf(
		'<div class="query-filters-terms-wrapper"><div
			class="theme-multiple-select goldencat-query-filters-select-label"
			data-taxonomy_slug="%1$s"
			data-taxonomy_name="%2$s"
		>%3$s %4$s%5$s</div></div>',
		$taxonomy,
		$tax->label,
		sprintf( 
			'<input type="hidden" class="taxo-collecteur" id="collected-%1$s" />',
			$taxonomy
		),
		sprintf( 
			'<span class="query-taxonomy-label">%1$s<span class="count"></span></span>',
			$tax->label
		),
		goldencat_query_filters_dropdown_list( $taxonomy, $tax_query )
	);
}

/**
 * Renders the `goldencat/query-filters` filters on the server.
 *
 * @param array $attributes Block attributes.
 *
 * @return string Returns the query filters based on the queried object.
 */

function render_block_goldencat_query_filters( $attributes, $content, $block ) {

	$tax_query = [];
	if ( isset( $block->context['query']['inherit'] ) && $block->context['query']['inherit'] ) {

		if ( isset ( get_queried_object()->taxonomy ) && isset( get_queried_object()->term_id ) ) {
			$initial_tax_query = array(
				get_queried_object()->taxonomy => array(
					get_queried_object()->term_id
				)
			);
			$tax_query = $initial_tax_query;//$query_args
		} else {
			$tax_query = isset( $block->context['query']['taxQuery'] ) ? $block->context['query']['taxQuery'] : [];//$query_args
		}
	}
	

	$taxonomies = [];
	if ( isset( $attributes['taxonomies'] ) ) {
		$taxonomies =  $attributes['taxonomies'];
	}

	$dropdowns = '';
	foreach ( $taxonomies as $taxonomy ) {
		$dropdowns .= goldencat_query_filters_dropdown( $taxonomy, $tax_query );
	}

	wp_add_inline_script( 'goldencat-front-scripts', 'const block_goldencat_query_filters_store = ' . json_encode( array(
		'filters_taxonomies' => array_map( function( $taxonomy ) {
			$tax = get_taxonomy( $taxonomy );
			return [
				'label' => $tax->label,
				'slug' => $tax->name,
				'terms' => array()
			];
		}, $taxonomies),
		'filters_ajax_url' => admin_url( 'admin-ajax.php' ),

	) ), 'before' );

	$classnames = '';
	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $classnames ) );

	$clearer = '<div class="wp-block-button is-style-goldencat-button-link"><button class="query-filter-clear hide wp-block-button__link">Clear All</button></div>';

	$spinner = '<div class="components-spinner"></div>';

    return sprintf(
		'<div %1$s>%2$s%3$s%4$s</div>',
		$wrapper_attributes,
		$dropdowns,
		$clearer,
		$spinner
	);
}

/**
 * Registers the `goldencat/query-filters` block on the server.
 */
function register_block_goldencat_query_filters() {

    register_block_type(
		'goldencat/query-filters',
		array(
            'name' => 'goldencat/query-filters',
			'title' => 'Query Filters',
			'uses_context' => [
				'queryId',
				'query',
				'queryContext',
				'displayLayout',
				'templateSlug'
			],
			'render_callback' => 'render_block_goldencat_query_filters',
		)
	);
}
add_action( 'init', 'register_block_goldencat_query_filters' );



/**
 * Ajax Requests
 */
function goldencat_query_block_filters_ajax_action() {
	$terms = goldencat_query_block_filters_ajax_sanitize_taxonomies_and_terms( $_POST['terms'] );
	// $block = $_POST['attrs'];
	$block = json_decode( stripslashes( $_POST['attrs'] ), true );

	

    if ( $block ) {
		$initial_tax_query = [];
		if ( isset( $block['attrs']['query']['inherit'] ) && $block['attrs']['query']['inherit'] ) {
			$block['attrs']['query']['inherit'] = false;
			if ( isset ( get_queried_object()->taxonomy ) && isset( get_queried_object()->term_id ) ) {
				$initial_tax_query = array(
					get_queried_object()->taxonomy => array(
						get_queried_object()->term_id
					)
				);
			} else {
				$initial_tax_query = isset( $block['attrs']['query']['taxQuery'] ) ? $block['attrs']['query']['taxQuery'] : [];//$query_args
			}
		}
		$block['attrs']['query']['taxQuery'] = $terms;
		$result = [
			'html' => render_block( $block ),
		];
		
		echo json_encode($result);
	}

    exit;
}

/** Sanitize value */
function goldencat_query_block_filters_ajax_sanitize_taxonomies_and_terms( $inputDatasArray ) {

	$inputsSanitized = array();
	$rawValues = rest_sanitize_array( $inputDatasArray );

	foreach ( $inputDatasArray as $rawKey => $rawValue ) {
		$inputsSanitized[ sanitize_text_field($rawKey) ] =  array_map( function( $termId ) {
			return ! empty( $termId ) ? absint( $termId ) : '';
		}, $rawValue );
	}
	return $inputsSanitized;
}
add_action( 'wp_ajax_goldencat_block_query_filters_action', 'goldencat_query_block_filters_ajax_action');
add_action( 'wp_ajax_nopriv_goldencat_block_query_filters_action', 'goldencat_query_block_filters_ajax_action');

// Inspired by https://gist.github.com/dkjensen/5190c554420ea2f19987a3f31ac95785
function goldencat_query_filters_render_block_core_query( $block_content, $block ) {
	if ( 'core/query' === $block['blockName'] ) {
		$query_id      = $block['attrs']['queryId'];
		$container_end = strpos( $block_content, '>' );

		$paged = absint( $_GET[ 'query-' . $query_id . '-page' ] ?? 1 );

		$block_content = substr_replace( $block_content, ' data-paged="' . esc_attr( $paged ) . '" data-attrs="' . esc_attr( json_encode( $block ) ) . '"', $container_end, 0 );

		wp_add_inline_script( 'goldencat-front-scripts', 'const block_goldencat_core_query_store = ' . json_encode( array(
			'blockAttributes' => $block
		) ), 'before' );
	}

	return $block_content;
}
add_filter( 'render_block', 'goldencat_query_filters_render_block_core_query', 10, 2 );
