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
 } from '@wordpress/block-editor';
 import { useSelect } from '@wordpress/data';
 import { pin, list, grid } from '@wordpress/icons';
 
 
 /**
  * Module Constants
  */
 
 const CATEGORIES_LIST_QUERY = {
   per_page: -1,
 };
 
 export default function LatestPostsEdit( { attributes, setAttributes } ) {
   const {
     postType,
     postsToShow,
     order,
     orderBy,
     categories,
     displayFeaturedImage,
     displayPostContentRadio,
     displayPostContent,
     displayPostDate,
     displayAuthor,
     postLayout,
     columns,
     excerptLength,
     featuredImageAlign,
     featuredImageSizeSlug,
     featuredImageSizeWidth,
     featuredImageSizeHeight,
     addLinkToFeaturedImage,
   } = attributes;
 
   const {
     latestPosts,
   } = useSelect(
     ( select ) => {
       const { getEntityRecords, getMedia } = select( 'core' );
       const catIds =
         categories && categories.length > 0
           ? categories.map( ( cat ) => cat.id )
           : [];
       const latestPostsQuery = pickBy(
         {
           categories: catIds,
           order,
           orderby: orderBy,
           per_page: postsToShow,
         },
         ( value ) => ! isUndefined( value )
       );
 
       const posts = getEntityRecords(
         'postType',
         postType,
         latestPostsQuery
       );
 
       return {
         latestPosts: ! Array.isArray( posts )
           ? posts
           : posts.map( ( post ) => {
               if ( ! post.featured_media ) return post;
 
               const image = getMedia( post.featured_media );
               let url = get(
                 image,
                 [
                   'media_details',
                   'sizes',
                   'large',
                   'source_url',
                 ],
                 null
               );
               if ( ! url ) {
                 url = get( image, 'source_url', null );
               }
               const featuredImageInfo = {
                 url,
                 // eslint-disable-next-line camelcase
                 alt: image?.alt_text,
               };
               return { ...post, featuredImageInfo };
             } ),
       };
     },
     [
       postType,
       postsToShow,
       order,
       orderBy,
       categories,
     ]
   );
 
   const [ categoriesList, setCategoriesList ] = useState( [] );
   const [ postTypeList, setPostTypeList ] = useState( [] );
   const categorySuggestions = categoriesList.reduce(
     ( accumulator, category ) => ( {
       ...accumulator,
       [ category.name ]: category,
     } ),
     {}
   );
 
   const postTypeListOptions = Object.keys(postTypeList).map((key) => {
     return {
       label: postTypeList[key].name,
       value: key,
     }
   });
 
 
   const selectCategories = ( tokens ) => {
     const hasNoSuggestion = tokens.some(
       ( token ) =>
         typeof token === 'string' && ! categorySuggestions[ token ]
     );
     if ( hasNoSuggestion ) {
       return;
     }
     // Categories that are already will be objects, while new additions will be strings (the name).
     // allCategories nomalizes the array so that they are all objects.
     const allCategories = tokens.map( ( token ) => {
       return typeof token === 'string'
         ? categorySuggestions[ token ]
         : token;
     } );
     // We do nothing if the category is not selected
     // from suggestions.
     if ( includes( allCategories, null ) ) {
       return false;
     }
     setAttributes( { categories: allCategories } );
   };
 
 
   const isStillMounted = useRef();
 
   useEffect( () => {
     isStillMounted.current = true;
 
     apiFetch( {
       path: addQueryArgs( `/wp/v2/categories`, CATEGORIES_LIST_QUERY ),
     } )
       .then( ( data ) => {
         if ( isStillMounted.current ) {
           setCategoriesList( data );
         }
       } )
       .catch( () => {
         if ( isStillMounted.current ) {
           setCategoriesList( [] );
         }
       } );
     apiFetch( { path: `/wp/v2/types` } )
       .then( ( data ) => {
         if ( isStillMounted.current ) {
           setPostTypeList( data );
         }
       } )
       .catch( () => {
         if ( isStillMounted.current ) {
           setPostTypeList( [] );
         }
       } );
 
     return () => {
       isStillMounted.current = false;
     };
   }, [] );
 
   const inspectorControls = (
     <InspectorControls>
       <PanelBody title={ __( 'Post content settings' ) }>
         <ToggleControl
           label={ __( 'Post content' ) }
           checked={ displayPostContent }
           onChange={ ( value ) =>
             setAttributes( { displayPostContent: value } )
           }
         />
       </PanelBody>
 
       <PanelBody title={ __( 'Post meta settings' ) }>
         <ToggleControl
           label={ __( 'Display author name' ) }
           checked={ displayAuthor }
           onChange={ ( value ) =>
             setAttributes( { displayAuthor: value } )
           }
         />
         <ToggleControl
           label={ __( 'Display post date' ) }
           checked={ displayPostDate }
           onChange={ ( value ) =>
             setAttributes( { displayPostDate: value } )
           }
         />
       </PanelBody>
 
       <PanelBody title={ __( 'Featured image settings' ) }>
         <ToggleControl
           label={ __( 'Display featured image' ) }
           checked={ displayFeaturedImage }
           onChange={ ( value ) =>
             setAttributes( { displayFeaturedImage: value } )
           }
         />
       </PanelBody>
 
       <PanelBody title={ __( 'Sorting and filtering' ) }>
         <SelectControl
           key="card-types-select"
           label="Post Type: "
           options={postTypeListOptions}
           onChange={ (value) => {
             setAttributes( { postType: value } )
           }}
         />
         <QueryControls
           { ...{ order, orderBy } }
           numberOfItems={ postsToShow }
           onOrderChange={ ( value ) =>
             setAttributes( { order: value } )
           }
           onOrderByChange={ ( value ) =>
             setAttributes( { orderBy: value } )
           }
           onNumberOfItemsChange={ ( value ) =>
             setAttributes( { postsToShow: value } )
           }
           categorySuggestions={ categorySuggestions }
           onCategoryChange={ selectCategories }
           selectedCategories={ categories }
         />
       </PanelBody>
     </InspectorControls>
   );
 
   const blockProps = useBlockProps( {
     className: classnames( {
       'wp-block-latest-posts__list': true,
       'is-grid': postLayout === 'grid',
       'has-dates': displayPostDate,
       'has-author': displayAuthor,
     } ),
   } );
 
   const hasPosts = Array.isArray( latestPosts ) && latestPosts.length;
   if ( ! hasPosts ) {
     return (
       <div { ...blockProps }>
         { inspectorControls }
         <Placeholder icon={ pin } label={ __( 'Latest Posts' ) }>
           { ! Array.isArray( latestPosts ) ? (
             <Spinner />
           ) : (
             __( 'No posts found.' )
           ) }
         </Placeholder>
       </div>
     );
   }
 
   // Removing posts from display should be instant.
   const displayPosts =
     latestPosts.length > postsToShow
       ? latestPosts.slice( 0, postsToShow )
       : latestPosts;
 
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
 
   const dateFormat = __experimentalGetSettings().formats.date;
 
   return (
     <>
       { inspectorControls }
       <BlockControls>
         <ToolbarGroup controls={ layoutControls } />
       </BlockControls>
       <ul { ...blockProps }>
         { displayPosts.map( ( post, i ) => {
           const titleTrimmed = invoke( post, [
             'title',
             'rendered',
             'trim',
           ] );
           let excerpt = post.excerpt;
 
           const excerptElement = document.createElement( 'div' );
           excerptElement.innerHTML = excerpt;
 
           excerpt =
             excerptElement.textContent ||
             excerptElement.innerText ||
             '';
 
           const {
             featuredImageInfo: {
               url: imageSourceUrl,
               alt: featuredImageAlt,
             } = {},
           } = post;
           const imageClasses = classnames( {
             'wp-block-latest-posts__featured-image': true,
           } );
           const renderFeaturedImage =
             displayFeaturedImage && imageSourceUrl;
           const featuredImage = renderFeaturedImage && (
             <img
               src={ imageSourceUrl }
               alt={ featuredImageAlt }
             />
           );
 
           const needsReadMore =
             excerptLength < excerpt.trim().split( ' ' ).length &&
             post.excerpt.raw === '';
 
           const postExcerpt = needsReadMore ? (
             <>
               { excerpt
                 .trim()
                 .split( ' ', excerptLength )
                 .join( ' ' ) }
               { /* translators: excerpt truncation character, default …  */ }
               { __( ' … ' ) }
               <a
                 href={ post.link }
                 target="_blank"
                 rel="noopener noreferrer"
               >
                 { __( 'Read more' ) }
               </a>
             </>
           ) : (
             excerpt
           );
 
           return (
             <li key={ i }>
               { renderFeaturedImage && (
                 <div className={ imageClasses }>
                   { addLinkToFeaturedImage ? (
                     <a
                       href={ post.link }
                       target="_blank"
                       rel="noreferrer noopener"
                     >
                       { featuredImage }
                     </a>
                   ) : (
                     featuredImage
                   ) }
                 </div>
               ) }
               <a
                 href={ post.link }
                 target="_blank"
                 rel="noreferrer noopener"
               >
                 { titleTrimmed ? (
                   <RawHTML>{ titleTrimmed }</RawHTML>
                 ) : (
                   __( '(no title)' )
                 ) }
               </a>
               { displayPostDate && post.date_gmt && (
                 <time
                   dateTime={ format( 'c', post.date_gmt ) }
                   className="wp-block-latest-posts__post-date"
                 >
                   { dateI18n( dateFormat, post.date_gmt ) }
                 </time>
               ) }
               {/* { displayPostContent &&
                 displayPostContentRadio === 'excerpt' && (
                   <div className="wp-block-latest-posts__post-excerpt">
                     { postExcerpt }
                   </div>
                 ) } */}
               {/* { displayPostContent &&
                 displayPostContentRadio === 'full_post' && (
                   <div className="wp-block-latest-posts__post-full-content">
                     <RawHTML key="html">
                       { post.content.raw.trim() }
                     </RawHTML>
                   </div>
                 ) } */}
             </li>
           );
         } ) }
       </ul>
     </>
   );
 }
 