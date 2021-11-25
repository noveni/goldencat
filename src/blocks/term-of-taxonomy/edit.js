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
  BlockControls,
  __experimentalImageSizeControl as ImageSizeControl,
  useBlockProps,
} from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { pin, list, grid } from '@wordpress/icons';


/**
* Module Constants
*/

const CATEGORIES_LIST_QUERY = {
  per_page: -1,
};
const MIN_DESCRIPTION_LENGTH = 10;
const MAX_DESCRIPTION_LENGTH = 100;


export default function TermOfTaxonomyEdit( { attributes, setAttributes } ) {
  const {
    taxonomy,
    termsToShow,
    order,
    orderBy,
    displayDescription,
    descriptionLength,
    displayImage,
    showPostCounts,
    hideEmpty,
    postLayout,
  } = attributes;

  const { terms, isRequesting } = useSelect( ( select ) => {
		const { getEntityRecords } = select( 'core' );
		const { isResolving } = select( 'core/data' );
		const query = { per_page: termsToShow, hide_empty: hideEmpty, order: order };
		return {
			terms: getEntityRecords( 'taxonomy', taxonomy, query ),
			isRequesting: isResolving( 'core', 'getEntityRecords', [
				'taxonomy',
				taxonomy,
				query,
			] ),
		};
	}, [taxonomy, termsToShow, hideEmpty, order, orderBy] );

  const [ taxonomyList, setTaxonomyList ] = useState( [] );

  const taxonomyListOptions = Object.keys(taxonomyList).map((key) => {
    return {
      label: taxonomyList[key].name,
      value: key,
    }
  });


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
          setTaxonomyList( [] );
        }
      } );

    return () => {
      isStillMounted.current = false;
    };
  }, [] );

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
					label={ __( 'Display featured image' ) }
					checked={ displayImage }
					onChange={ ( value ) => setAttributes( { displayImage: value } ) }
				/>
        <ToggleControl
          label={ __( 'Show post counts' ) }
          checked={ showPostCounts }
          onChange={ (value) => setAttributes( { showPostCounts: value } ) }
        />
        <ToggleControl
          label={ __( 'Hide empty' ) }
          checked={ hideEmpty }
          onChange={ (value) => setAttributes( { hideEmpty: value } ) }
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
        <QueryControls
          { ...{ order, orderBy } }
          numberOfItems={ termsToShow }
          onOrderChange={ ( value ) =>
            setAttributes( { order: value } )
          }
          onOrderByChange={ ( value ) =>
            setAttributes( { orderBy: value } )
          }
          onNumberOfItemsChange={ ( value ) =>
            setAttributes( { termsToShow: value } )
          }
        />
      </PanelBody>
    </InspectorControls>
  );

  const blockProps = useBlockProps( {
    className: classnames( {
      'wp-block-term-of-taxonomy__list': true,
      'is-grid': postLayout === 'grid',
    } ),
  } );

  const hasTerms = Array.isArray( terms ) && terms.length;
  if ( ! hasTerms ) {
    return (
      <div { ...blockProps }>
        { inspectorControls }
        <Placeholder icon={ pin } label={ __( 'Latest Posts' ) }>
          { isRequesting ? (
            <Spinner />
          ) : (
            __( 'No term found.' )
          ) }
        </Placeholder>
      </div>
    );
  }

  // Removing posts from display should be instant.
  const displayTerms =
    terms.length > termsToShow
      ? terms.slice( 0, termsToShow )
      : terms;

  const layoutControls = [
    {
      icon: list,
      title: __( 'List view' ),
      onClick: () => setAttributes( { postLayout: 'list' } ),
      isActive: postLayout === 'list',
    },
    {
      icon: grid,
      title: __( 'Grid view' ),
      onClick: () => setAttributes( { postLayout: 'grid' } ),
      isActive: postLayout === 'grid',
    },
  ];


  return (
    <>
      { inspectorControls }
      <BlockControls>
        <ToolbarGroup controls={ layoutControls } />
      </BlockControls>
      <ul { ...blockProps }>
        { displayTerms.map( ( term, i ) => {
          const title = term.name
          let description = term.description;

          const {
            imageInfo: {
              url: imageSourceUrl,
            } = {},
          } = term;
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
            <li key={ i }>
              { renderImage && (
                <div className={ imageClasses }>
                  <a
                    href={ term.link }
                    target="_blank"
                    rel="noreferrer noopener"
                  >
                    { featuredImage }
                  </a>
                </div>
              ) }
              <a
                href={ term.link }
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
            </li>
          );
        } ) }
      </ul>
    </>
  );
}
