/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import {
	PanelBody,
	TextControl,
	SelectControl,
	RangeControl,
	ToggleControl,
	Notice,
	FormTokenField,
	CheckboxControl
} from '@wordpress/components';
import {
	InspectorControls,
	useBlockProps,
	Warning,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';


/**
 * Returns a helper object with mapping from Objects that implement
 * the `IHasNameAndId` interface. The returned object is used for
 * integration with `FormTokenField` component.
 *
 * @param {IHasNameAndId[]} entities The entities to extract of helper object.
 * @return {QueryEntitiesInfo} The object with the entities information.
 */
 export const getEntitiesInfo = ( entities ) => {
	const mapping = entities?.reduce(
		( accumulator, entity ) => {
			const { mapById, mapByName, names } = accumulator;
			mapById[ entity.id ] = entity;
			mapByName[ entity.name ] = entity;
			names.push( entity.name );
			return accumulator;
		},
		{ mapById: {}, mapByName: {}, names: [] }
	);
	return {
		entities,
		...mapping,
	};
};

export default function QueryFiltersEdit( {
	attributes: { taxonomies },
	setAttributes,
	context: { query }
} ) {

	const {
		postType 
	} = query;

	const taxonomiesInfo = useSelect( 
		( select ) => {
			const { getEntityRecords, getTaxonomies } = select( coreStore );
			const taxos = getTaxonomies( {
				type: postType,
				per_page: -1,
				context: 'view',
			} );

			const rawTaxonomiesInfo = taxos?.map( ( { slug, name } ) => {
				const _terms = getEntityRecords( 'taxonomy', slug, { per_page: 1 } );
				return {
					slug,
					name,
					hasTerms: _terms?.length > 0,
				};
			} );

			const _taxonomiesInfo = rawTaxonomiesInfo?.filter( ( { hasTerms } ) => hasTerms)

			return {
				taxonomies: _taxonomiesInfo,
				names: _taxonomiesInfo?.map( ( { name } ) => name ),
				slugs:  _taxonomiesInfo?.map( ( { slug } ) => slug )
			};
		},
		[ postType ]
	);


	const onTaxonomiesChange = ( taxonomySlug ) => ( checked ) => {
		const taxonomyInfo = taxonomiesInfo.taxonomies.find(
			( { slug } ) => slug === taxonomySlug
		);
		if ( ! taxonomyInfo ) return;

		let newtaxonomies = taxonomies

		if ( checked ) {
			if ( ! taxonomies.includes(taxonomySlug) ) {
				newtaxonomies = [ ...newtaxonomies, taxonomyInfo.slug];
			}
		} else {
			// Remove from attributes array
			newtaxonomies = taxonomies.filter( slug => slug !== taxonomyInfo.slug);
		}

		newtaxonomies = newtaxonomies.filter( slug => taxonomiesInfo?.slugs?.includes(slug) )
		setAttributes( { taxonomies: newtaxonomies } )
	};

	const blockProps = useBlockProps();

	const selectedTaxonomies = taxonomiesInfo?.taxonomies?.filter( ( { slug } ) => taxonomies.includes(slug) );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Filters' ) }>
					{ taxonomiesInfo?.names?.length > 0 && taxonomiesInfo.taxonomies.map( ( { slug, name, hasTerms } ) => { 
						if ( ! hasTerms ) {
							return null;
						}
						return (
							<CheckboxControl
								label={ name }
								checked={ taxonomies?.includes(slug)  }
								onChange={ onTaxonomiesChange( slug ) }
							/>
						)
					} ) }						
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
			{ selectedTaxonomies && selectedTaxonomies.map( ( { name, slug } ) => { 
				return (
					<div>
						{ name }
					</div>
				)
			} ) }
			{ !selectedTaxonomies || (!selectedTaxonomies.length) && (
				<h4 { ...blockProps }>Veuillez choisir des taxonomies dans le menu de contrôle</h4>
			)}
			</div>
		</>
	);
}
