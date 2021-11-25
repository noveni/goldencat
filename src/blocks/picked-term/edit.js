/**
 * External dependencies
 */
import { get, includes, invoke, isUndefined, pickBy } from 'lodash';
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { useState, RawHTML, useEffect, useRef } from '@wordpress/element';
import {
	BaseControl,
  SelectControl,
	PanelBody,
	Placeholder,
	QueryControls,
	RadioControl,
	RangeControl,
	Spinner,
	ToggleControl,
	ToolbarGroup,
} from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { __, sprintf } from '@wordpress/i18n';
import { dateI18n, format, __experimentalGetSettings } from '@wordpress/date';
import {
	InspectorControls,
	BlockAlignmentToolbar,
	BlockControls,
	__experimentalImageSizeControl as ImageSizeControl,
	useBlockProps,
  ButtonBlockerAppender,
} from '@wordpress/block-editor';
import { select, useSelect } from '@wordpress/data';
import { pin, list, grid } from '@wordpress/icons';


/**
 * Internal dependencies
 */
import SearchTerm from './search-term'

const CATEGORIES_LIST_QUERY = {
  per_page: -1,
};
const MIN_DESCRIPTION_LENGTH = 10;
const MAX_DESCRIPTION_LENGTH = 100;


const PickedTermEdit = ( props ) => {
  const {
    className,
    isSelected,
    attributes,
    setAttributes,
  } = props;

  const {
    termId,
    taxonomy,
    displayDescription,
		descriptionLength,
    displayImage,
		showPostCounts,
  } = attributes;

	const { pickedTerm, isRequesting } = useSelect( ( select ) => {
		const { getEntityRecord } = select( 'core' );
		const { isResolving } = select( 'core/data' );
		return {
			pickedTerm: getEntityRecord( 'taxonomy', taxonomy, termId ),
			isRequesting: isResolving( 'core', 'getEntityRecord', [
				'taxonomy',
				taxonomy,
				termId,
			] ),
		};
	}, [termId, taxonomy] );


	const [ taxonomyList, setTaxonomyList ] = useState( false );

  const isStillMounted = useRef();

  useEffect( () => {
    isStillMounted.current = true;

    apiFetch( {
      path: addQueryArgs( `/wp/v2/taxonomies`, CATEGORIES_LIST_QUERY ),
    } )
      .then( ( data ) => {
        if ( isStillMounted.current ) {
          setTaxonomyList( data );
        }
      } )
      .catch( () => {
        if ( isStillMounted.current ) {
          setTaxonomyList( false );
        }
      } );

    return () => {
      isStillMounted.current = false;
    };
  }, [] );


  const taxonomyListOptions = Object.keys(taxonomyList).map((key) => {
    return {
      label: taxonomyList[key].name,
      value: key,
    }
  });

	const setTermId = (id) => {
		setAttributes( { termId: id } )
	}

	const inspectorControls = (
    <InspectorControls>
      <PanelBody title={ __( 'Term Settings' ) }>
        <ToggleControl
          label={ __( 'Display Description' ) }
          checked={ displayDescription }
          onChange={ ( value ) => setAttributes( { displayDescription: value } ) }
        />
        { displayDescription && (
          <RangeControl
            label={ __( 'Max number of words in excerpt' ) }
            value={ descriptionLength }
            onChange={ ( value ) => setAttributes( { descriptionLength: value } ) }
            min={ MIN_DESCRIPTION_LENGTH }
            max={ MAX_DESCRIPTION_LENGTH }
          />
        ) }
        <ToggleControl
					label={ __( 'Display image' ) }
					checked={ displayImage }
					onChange={ ( value ) => setAttributes( { displayImage: value } ) }
				/>
        <ToggleControl
          label={ __( 'Show post counts' ) }
          checked={ showPostCounts }
          onChange={ (value) => setAttributes( { showPostCounts: value } ) }
        />        
      </PanelBody>    

      <PanelBody title={ __( 'Sorting and filtering' ) }>
        <SelectControl
          key="card-types-select"
          label="Post Type: "
          options={taxonomyListOptions}
          value={taxonomy}
          onChange={ (value) => {
            setAttributes( { taxonomy: value } )
          }}
        />
      </PanelBody>
    </InspectorControls>
  );

  const blockProps = useBlockProps( {
		className: classnames( 
      className,
    ),
	} );

	if (termId && isRequesting) {
		return (
			<div { ...blockProps }>
        { inspectorControls }
				<Placeholder icon={ pin } label={ __( 'Picked Term' ) }>
						<Spinner />
				</Placeholder>
			</div>
		);
	}


	if (!termId && !pickedTerm && taxonomyList) {
		return (
			<div { ...blockProps }>
        { inspectorControls }
				<SearchTerm
					taxonomy={taxonomyList[taxonomy].rest_base}
					setTermId={setTermId}
				/>
			</div>
		);
	}


  const hasTerm = termId && typeof pickedTerm === 'object' && pickedTerm !== null
	if ( !hasTerm ) {
		return (
			<div { ...blockProps }>
        { inspectorControls }
				<Placeholder icon={ pin } label={ __( 'Picked Term' ) }>
					{ !pickedTerm ? (
						<Spinner />
					) : (
						__( 'No post found.' )
					) }
				</Placeholder>
			</div>
		);
	}

	const title = pickedTerm.name
	let description = pickedTerm.description;

	const {
		imageInfo: {
			url: imageSourceUrl,
		} = {},
	} = pickedTerm;
	const imageClasses = classnames( {
		'wp-block-term-of-taxonomy__image': true,
	} );

	const renderImage =
		displayImage && imageSourceUrl;
	const featuredImage = renderImage && (
		<img
			src={ imageSourceUrl }
		/>
	);

	const termDescription = (
		<>
			{ description
				.trim()
				.split( ' ', descriptionLength )
				.join( ' ' ) }
			{ /* translators: description truncation character, default …  */ }
			{ __( ' … ' ) }
		</>
	)

  return (
    <>
      { inspectorControls }
      <div { ...blockProps }>
			{ renderImage && (
					<div className={ imageClasses }>
						<a
							href={ pickedTerm.link }
							target="_blank"
							rel="noreferrer noopener"
						>
							{ featuredImage }
						</a>
					</div>
				) }
				<a
					href={ pickedTerm.link }
					target="_blank"
					rel="noreferrer noopener"
				>
					{ title ? (
						<RawHTML>{ title }</RawHTML>
					) : (
						__( '(no title)' )
					) }
				</a>
				{ displayDescription && (
					<div className="wp-block-latest-posts__post-excerpt">
						{ termDescription }
					</div>
				) }
      </div>
    </>
  )
}


export default PickedTermEdit;
