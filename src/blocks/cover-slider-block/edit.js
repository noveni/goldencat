/**
 * External dependencies
 */
import classnames from 'classnames';
import {
	every,
	filter,
	find,
	forEach,
	get,
	isEmpty,
	map,
	reduce,
	some,
	toString,
} from 'lodash';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import {
  BlockControls,
  MediaPlaceholder,
  InspectorControls,
	InnerBlocks,
	useBlockProps,
	useInnerBlocksProps,
  __experimentalUnitControl as UnitControl,
} from '@wordpress/block-editor';
import {
  BaseControl,
	Button,
	PanelBody,
	PanelRow,
	ToolbarGroup,
	ToolbarButton,
} from '@wordpress/components';
import { Platform, useEffect, useState } from '@wordpress/element';
import { compose, withInstanceId, useInstanceId } from '@wordpress/compose';
import {
	edit as editIcon,
  check as checkIcon,
} from '@wordpress/icons';

/**
 * Internal dependencies
 */
import { CSS_UNITS, COVER_MIN_HEIGHT } from './shared';
import Slider from './slider';

/**
 * Module Constants
 */
const ALLOWED_MEDIA_TYPES = [ 'image', 'video' ];
const ALLOWED_INNER_BLOCKS = [ 'core/image', 'core/button', 'core/heading', 'core/paragraph']
const INNER_BLOCKS_TEMPLATE = [
	[
		'core/paragraph',
		{
			align: 'center',
			fontSize: 'large',
			placeholder: __( 'Write title…' ),
		},
	],
];

const CoverHeightInput = ( props ) => {
  const {
    onChange,
    onUnitChange,
    unit = 'px',
    value = '',
  } = props;

	const [ temporaryInput, setTemporaryInput ] = useState( null );
	const instanceId = useInstanceId( UnitControl );
	const inputId = `block-cover-slider-height-input-${ instanceId }`;
	const isPx = unit === 'px';

	const handleOnChange = ( unprocessedValue ) => {
		const inputValue =
			unprocessedValue !== ''
				? parseInt( unprocessedValue, 10 )
				: undefined;

		if ( isNaN( inputValue ) && inputValue !== undefined ) {
			setTemporaryInput( unprocessedValue );
			return;
		}
		setTemporaryInput( null );
		onChange( inputValue );
		if ( inputValue === undefined ) {
			onUnitChange();
		}
	};

	const handleOnBlur = () => {
		if ( temporaryInput !== null ) {
			setTemporaryInput( null );
		}
	};

	const inputValue = temporaryInput !== null ? temporaryInput : value;
	const min = isPx ? COVER_MIN_HEIGHT : 0;

	return (
		<BaseControl label={ __( 'Minimum height of cover' ) } id={ inputId }>
			<UnitControl
				id={ inputId }
				isResetValueOnUnitChange
				min={ min }
				onBlur={ handleOnBlur }
				onChange={ handleOnChange }
				onUnitChange={ onUnitChange }
				step="1"
				style={ { maxWidth: 80 } }
				unit={ unit }
				units={ CSS_UNITS }
				value={ inputValue }
			/>
		</BaseControl>
	);
}

const GroupEdit = ( props ) => {

  const {
    className,
    isSelected,
    attributes,
    setAttributes,
    clientId,
  } = props;
  const { 
    imageIds,
    minHeight,
    minHeightUnit
  } = attributes;

  const [ selectedImage, setSelectedImage ] = useState();
  const [ isEditingSlider, setEditingSlider ] = useState( false );
  const [ temporaryMinHeight, setTemporaryMinHeight ] = useState( null );

	const blockProps = useBlockProps();

  const hasInnerBlocks = useSelect(
		( select ) => {
			const { getBlock } = select( 'core/block-editor' );
			const block = getBlock( clientId );
			return !! ( block && block.innerBlocks.length );
		},
		[ clientId ]
	);

  const images = useSelect(( select ) => {
      const { getEntityRecords, getMedia } = select( 'core' );
      const { getSettings } = select( 'core/block-editor' );
		  const { imageSizes, mediaUpload } = getSettings();

      const retrievedImg = reduce(
        imageIds,
        ( currentImages, id ) => {
          if ( ! id ) {
            return currentImages;
          }
          const image = getMedia( id );

          if (!image) {
            return currentImages;
          }

          const defaultUrl = get( image, [
            'sizes',
            'full',
            'url',
          ] );
          const mediaUrl = get( image, [
            'media_details',
            'sizes',
            'full',
            'source_url',
          ] );

          const theImg = {
            id: id,
            url: defaultUrl || mediaUrl,
            link: image.link,
            alt: image.alt_text,
            caption: image.caption && image.caption.rendered,
          }

          currentImages.push(theImg)
          return currentImages;
        },
        []
      );

      return retrievedImg;
    }, 
    [
      imageIds,
      isSelected,
    ]
  )

  const onSelectImage = ( index ) => {
		return () => {
			setSelectedImage( index );
		};
	}

	const onDeselectImage = () => {
		return () => {
			setSelectedImage();
		};
	}

	const onMove = ( oldIndex, newIndex ) => {
		const newImages = [ ...imageIds ];
		newImages.splice( newIndex, 1, images[ oldIndex ].id );
		newImages.splice( oldIndex, 1, images[ newIndex ].id );
		setSelectedImage( newIndex );
		setAttributes( { imageIds: newImages } );
	}

  const onMoveForward = ( oldIndex ) => {
		return () => {
			if ( oldIndex === images.length - 1 ) {
				return;
			}
			onMove( oldIndex, oldIndex + 1 );
		};
	}

	const onMoveBackward = ( oldIndex ) => {
		return () => {
			if ( oldIndex === 0 ) {
				return;
			}
			onMove( oldIndex, oldIndex - 1 );
		};
	}

  const onRemoveImage = ( index ) => {
		return () => {
			const newImages = filter( imageIds, ( img, i ) => index !== i );
			setSelectedImage();
			setAttributes( {
				imageIds: newImages,
			} );
		};
	}

  const onSelectImages = ( newImages ) => {
		setAttributes( {
			imageIds: newImages.map( ( newImage ) => ( toString( newImage.id ) ) ),
		} );
	}

  useEffect( () => {
		// Deselect images when deselecting the block
		if ( ! isSelected ) {
			setSelectedImage();
		}
	}, [ isSelected ] );

  

	

  const hasImages = ! isEmpty(images);

	const mediaPlaceholder = (
		<MediaPlaceholder
			addToGallery={ hasImages }
			isAppender={ hasImages }
      className="block-cover-slider__media-placeholder"
      disableMediaButtons={ hasImages && ! isSelected }
			labels={ {
				title: ! hasImages && __( 'Gallery' ),
				instructions: ! hasImages && 'Add image',
			} }
			onSelect={ onSelectImages }
			accept="image/*"
			allowedTypes={ ALLOWED_MEDIA_TYPES }
			multiple
			value={ images }
		/>
	);

	const innerBlocksProps = useInnerBlocksProps(
		{
			className: 'wp-block-cover-slider__inner-container',
		},
		{
			templateLock: false,
      allowedBlocks:  ALLOWED_INNER_BLOCKS,
      template: INNER_BLOCKS_TEMPLATE,
      // renderAppender: hasInnerBlocks
      // ? InnerBlocks.ButtonBlockAppender
      // : InnerBlocks.ButtonBlockAppender,
		}
	);

  const blockClassName = classnames(
    className,
    {
      'is-selected': isSelected,
      'is-editing-slider': isEditingSlider,
      'has-images': hasImages,
    }
  );

  const minHeightWithUnit = minHeightUnit
		? `${ minHeight }${ minHeightUnit }`
		: minHeight;

  const style = {
		minHeight: temporaryMinHeight || minHeightWithUnit || undefined,
	};

	return (
    <>
      <BlockControls>
        <ToolbarGroup>
          { !isEditingSlider && (
            <ToolbarButton
              onClick={ () => setEditingSlider( true ) }
            >
              Modifier les images
            </ToolbarButton>

          )}
          { isEditingSlider && (
            <ToolbarButton
              title="Valider"
              icon={ checkIcon }
              onClick={ () => setEditingSlider( false ) }
            />
          )}
        </ToolbarGroup>
      </BlockControls>
      <InspectorControls>
        <PanelBody title={ __( 'Dimensions' ) }>
          <CoverHeightInput
            value={ temporaryMinHeight || minHeight }
            unit={ minHeightUnit }
            onChange={ ( newMinHeight ) =>
              setAttributes( { minHeight: newMinHeight } )
            }
            onUnitChange={ ( nextUnit ) => {
              setAttributes( {
                minHeightUnit: nextUnit,
              } );
            } }
          />
        </PanelBody>
      </InspectorControls>
      <div
        { ...blockProps }
        className={ classnames( blockClassName, blockProps.className ) }
        style={ { ...style, ...blockProps.style } }
      >
        <div className="block-cover-slider-wrapper">
          { hasImages && (
            <Slider
              images={ images }
              isSelected={isSelected}
              isEditingSlider={isEditingSlider}
              selectedImage={ selectedImage }
              onMoveBackward={ onMoveBackward }
              onMoveForward={ onMoveForward }
              onRemoveImage={ onRemoveImage }
              onSelectImage={ onSelectImage }
              onDeselectImage={ onDeselectImage }
              mediaPlaceholder={mediaPlaceholder}
            />
          )}
          { !hasImages && mediaPlaceholder }
        </div>
        <div { ...innerBlocksProps } />
      </div>
    </>
	);
}

export default GroupEdit;
