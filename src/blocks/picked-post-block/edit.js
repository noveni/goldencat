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
import SearchPost from './search-post'


const PickedPostEdit = ( props ) => {
  const {
    className,
    isSelected,
    attributes,
    setAttributes,
  } = props;

  const {
    postId,
    postType,
    displayPostContent,
    excerptLength,
    displayFeaturedImage,
    displayPostDate,
  } = attributes;

  const [ postTypeList, setPostTypeList ] = useState( [] );

  const pickedPost = useSelect(
    (select) => {
      const { getMedia, getEntityRecord } = select( 'core' );

			const post = getEntityRecord('postType', postType, postId);
			if (!post) {
				return post
			} else {
				if ( ! post.featured_media ) return post;
	
				const image = getMedia( post.featured_media );
				let url = get(
					image,
					[
						'media_details',
						'sizes',
						'full',
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
			}
    },
    [
      postId,
      postType,
      isSelected,
    ]
  );

  const isStillMounted = useRef();

  useEffect( () => {
		isStillMounted.current = true;

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

	const setPostId = (id) => {
		setAttributes( { postId: id } )
	}

  const postTypeListOptions = Object.keys(postTypeList).map((key) => {
    return {
      label: postTypeList[key].name,
      value: key,
    }
  });


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
					value={postType}
          onChange={ (value) => {
            setAttributes( { postType: value } )
          }}
        />				
			</PanelBody>
    </InspectorControls>
  )

  const blockProps = useBlockProps( {
		className: classnames( 
      className,
    ),
	} );

	if (postTypeList.length == 0) {
		return (
			<div { ...blockProps }>
        { inspectorControls }
				<Placeholder icon={ pin } label={ __( 'Picked Post' ) }>
						<Spinner />
				</Placeholder>
			</div>
		);
	}


	if (!postId && !pickedPost) {
		return (
			<div { ...blockProps }>
        { inspectorControls }
				<SearchPost
					postType={postTypeList[postType].rest_base}
					setPostId={setPostId}
				/>
				<Placeholder icon={ pin } label={ __( 'Picked Post' ) }>
				
				</Placeholder>
			</div>
		);
	}


  const hasPost = postId && typeof pickedPost === 'object' && pickedPost !== null
	if ( pickedPost === null || pickedPost === undefined ) {
		return (
			<div { ...blockProps }>
        { inspectorControls }
				<Placeholder icon={ pin } label={ __( 'Picked Post' ) }>
					{ !pickedPost ? (
						<Spinner />
					) : (
						__( 'No post found.' )
					) }
				</Placeholder>
			</div>
		);
	}


  const dateFormat = __experimentalGetSettings().formats.date;

	const titleTrimmed = invoke( pickedPost, [
		'title',
		'rendered',
		'trim',
	] );
	let excerpt = pickedPost.excerpt.rendered;

	const excerptElement = document.createElement( 'div' );
	excerptElement.innerHTML = excerpt;

	excerpt =
		excerptElement.textContent ||
		excerptElement.innerText ||
		'';

	const postExcerpt = excerpt
				.trim()
				.split( ' ', excerptLength )
				.join( ' ' );

	const {
		featuredImageInfo: {
			url: imageSourceUrl,
			alt: featuredImageAlt,
		} = {},
	} = pickedPost;

	const imageClasses = classnames( {
		'ecrannoir-block-picked-posts__featured-image': true,
	} );
	
	const renderFeaturedImage = displayFeaturedImage && imageSourceUrl;

	const featuredImage = renderFeaturedImage && (
		<img
			src={ imageSourceUrl }
			alt={ featuredImageAlt }
		/>
	);

  return (
    <>
      { inspectorControls }
      <div { ...blockProps }>
				{ renderFeaturedImage && (
					<div className={ imageClasses }>
						{ featuredImage }
					</div>
				) }
				<h2>{ titleTrimmed ? (
					<RawHTML>{ titleTrimmed }</RawHTML>
				) : (
					__( '(no title)' )
				) }</h2>
				{ displayPostDate && pickedPost.date_gmt && (
					<time
						dateTime={ format( 'c', pickedPost.date_gmt ) }
						className="wp-block-latest-post__post-date"
					>
						{ dateI18n( dateFormat, pickedPost.date_gmt ) }
					</time>
				) }
				{ displayPostContent && (
					<div className="ecrannoir-block-picked-post__post-content">
						{ postExcerpt }
					</div>
				) }
      </div>
    </>
  )
}


export default PickedPostEdit;
