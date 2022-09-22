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

const ALLOWED_BLOCKS = [ 'goldencat/picked-post' ];

const PickedPostsEdit = ( props ) => {
  const {
    className,
    isSelected,
    attributes,
    setAttributes,
    clientId
  } = props;

  const {
    postIds,
    postType,
    postLayout,
    displayPostContent,
    excerptLength,
    displayFeaturedImage,
    displayPostDate,
    order,
    orderBy,
  } = attributes;

  const [ postTypeList, setPostTypeList ] = useState( [] );

	const hasInnerBlocks = useSelect(
		( select ) => {
			const { getBlock } = select( 'core/block-editor' );
			const block = getBlock( clientId );
			return !! ( block && block.innerBlocks.length );
		},
		[ clientId ]
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
      {
        'is-grid': postLayout === 'grid',
		  } 
    ),
	} );

	const innerBlocksProps = useInnerBlocksProps( blockProps,	{
		allowedBlocks: ALLOWED_BLOCKS,
		templateLock: false,
		// renderAppender: hasInnerBlocks
		// 	? undefined
		// 	: InnerBlocks.ButtonBlockAppender,
	} );

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
      <div { ...blockProps }>
				<div { ...innerBlocksProps } />
      </div>
    </>
  )
}


export default PickedPostsEdit;
