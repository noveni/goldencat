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
   InnerBlocks,
   useBlockProps,
  useInnerBlocksProps,
 } from '@wordpress/block-editor';
 import { select, useSelect } from '@wordpress/data';
 import { pin, list, grid } from '@wordpress/icons';

// const ALLOWED_BLOCKS = [ 'ecrannoirtwentyone/picked-post' ];

/**
* Module Constants
*/

export default function FaqPostsEdit( { isSelected, attributes, setAttributes, className } ) {
  const {
    postType,
    postsToShow,
    postLayout,
    showFilter,
  } = attributes;

  const {
    quotePosts,
  } = useSelect(
    ( select ) => {
      const { getEntityRecords } = select( 'core' );
      const postsQuery = pickBy(
        {
          per_page: postsToShow,
        },
        ( value ) => ! isUndefined( value )
      );

      const posts = getEntityRecords(
        'postType',
        postType,
        postsQuery
      );

      return {
        quotePosts: posts
      };
    },
    [
      postType,
      postsToShow,
    ]
  );


  const inspectorControls = (
    <InspectorControls>

      <PanelBody title={ __( 'Réglages' ) }>
        <QueryControls
          numberOfItems={ postsToShow }
          onNumberOfItemsChange={ ( value ) =>
            setAttributes( { postsToShow: value } )
          }
        />

        <ToggleControl
          label={ __( 'Afficher le tri' ) }
          checked={ showFilter }
          onChange={ ( value ) =>
            setAttributes( { showFilter: value } )
          }
        />
      </PanelBody>
    </InspectorControls>
  );

  const blockProps = useBlockProps( {
    className: classnames( 
      className,
      {
        'is-grid': postLayout === 'grid',
      } 
    ),
  } );

  // const innerBlocksProps = useInnerBlocksProps( blockProps,	{
  //   allowedBlocks: ALLOWED_BLOCKS,
  //   templateLock: false,
  //   // renderAppender: hasInnerBlocks
  //   // 	? undefined
  //   // 	: InnerBlocks.ButtonBlockAppender,
  // } );

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

  const hasPosts = Array.isArray( quotePosts ) && quotePosts.length;
  if ( ! hasPosts ) {
    return (
      <div { ...blockProps }>
        { inspectorControls }
        {/* <BlockControls>
          <ToolbarGroup controls={ layoutControls } />
        </BlockControls> */}
        <Placeholder icon={ pin } label={ __( 'Latest FAQs' ) }>
          { ! Array.isArray( quotePosts ) ? (
            <Spinner />
          ) : (
            __( 'No FAQs found.' )
          ) }
        </Placeholder>
      </div>
    );
  }

  // Removing posts from display should be instant.
  let displayPosts =
    quotePosts.length > postsToShow
      ? quotePosts.slice( 0, postsToShow )
      : quotePosts;

  let unShowPost = 0;
  if (!isSelected && displayPosts.length > 1 ) {
    unShowPost = displayPosts.length - 1;
    displayPosts = displayPosts.slice(0, 1);
  }

  return (
    <div { ...blockProps }>
      { inspectorControls }
      <div className="faq-block-grid wp-block-group">
        { displayPosts.map( ( post, i ) => {

          {/* const excerptElement = document.createElement( 'div' );
           excerptElement.innerHTML = excerpt;
 
           excerpt =
             excerptElement.textContent ||
             excerptElement.innerText ||
             ''; */}

          return (
            <div key={ i } className="has-text-align-center">
              <div className="faq-block-item">
                <RawHTML key="html">
                  { post.content.rendered }
                </RawHTML>
              </div>              
            </div>
          );
        } ) }
      </div>
      {!isSelected &&
        <p className="has-text-align-center"><em> + {unShowPost} FAQ.</em>  Cliquer pour afficher.</p>
      }
    </div>
  );
}
