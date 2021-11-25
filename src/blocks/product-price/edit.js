/**
 * External dependencies
 */
 import classnames from 'classnames';

/**
* WordPress dependencies
*/
import apiFetch from '@wordpress/api-fetch';
import {
  AlignmentControl,
  BlockControls,
  useBlockProps
} from '@wordpress/block-editor';
import { RawHTML } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';


/**
* Internal dependencies
*/
//  import HeadingLevelDropdown from '../heading/heading-level-dropdown';
//  import { useCanEditEntity } from '../utils/hooks';

export default function ProductPriceEdit( {
  attributes: { textAlign },
  setAttributes,
  context: { postId },
} ) {
  const [ product, setProduct ] = useState( null );
  const [ isLoading, setIsLoading ] = useState( true );
  const TagName =  'p';

  useEffect( () => {
		if ( postId > 0 ) {
			setIsLoading( true );
			apiFetch( {
				path: `/wc/store/products/${ postId }`,
			} )
				.then( ( receivedProduct ) => {
					setProduct( receivedProduct );
				} )
				.catch( async () => {
					setProduct( null );
				} )
				.finally( () => {
					setIsLoading( false );
				} );
		}
	}, [ postId ] );

  const blockProps = useBlockProps( {
    className: classnames( {
      [ `has-text-align-${ textAlign }` ]: textAlign,
    } ),
  } );

  let titleElement = (
    <TagName { ...blockProps }>
      { __( 'An example title' ) }
    </TagName>
  );

  console.log(product, isLoading);
  if ( product && !isLoading ) {
    console.log(product);
    titleElement = (
      <TagName { ...blockProps }>
        <RawHTML key="html">{ product.price_html }</RawHTML>
      </TagName>
    )
  }

  return (
    <>
      <BlockControls group="block">
        <AlignmentControl
          value={ textAlign }
          onChange={ ( nextAlign ) => {
            setAttributes( { textAlign: nextAlign } );
          } }
        />
      </BlockControls>
      { titleElement }
    </>
  );
}
