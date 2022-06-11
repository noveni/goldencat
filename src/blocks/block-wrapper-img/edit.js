/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
  InnerBlocks,
  useBlockProps,
  useInnerBlocksProps,
  MediaUpload,
  InspectorControls,
  useSetting,
  store as blockEditorStore,
} from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import {
  useCallback,
  useEffect,
  useMemo,
} from '@wordpress/element';
import {
  BaseControl,
  Button,
  PanelBody,
  FocalPointPicker,
  __experimentalUnitControl as UnitControl,
	__experimentalToolsPanelItem as ToolsPanelItem,
  __experimentalUseCustomUnits as useCustomUnits,
	__experimentalParseQuantityAndUnitFromRawValue as parseQuantityAndUnitFromRawValue,
} from '@wordpress/components';
import { compose, useInstanceId } from '@wordpress/compose';

/**
 * External dependencies
 */
import { useResizeDetector } from 'react-resize-detector';

const ALLOWED_MEDIA_TYPES = [ 'image' ];

function ImageWidthInput( {
	onChange,
	onUnitChange,
	unit = 'px',
	value = '',
} ) {
	const instanceId = useInstanceId( UnitControl );
	const inputId = `block-wrapper-image-width-input-${ instanceId }`;
	const isPx = unit === 'px';

	const units = useCustomUnits( {
		availableUnits: useSetting( 'spacing.units' ) || [
			'px',
			'%',
			'vw',
			'vh',
		],
	} );

	const handleOnChange = ( unprocessedValue ) => {
		const inputValue =
			unprocessedValue !== ''
				? parseFloat( unprocessedValue )
				: undefined;

		if ( isNaN( inputValue ) && inputValue !== undefined ) {
			return;
		}
		onChange( inputValue );
	};

	const computedValue = useMemo( () => {
		const [ parsedQuantity ] = parseQuantityAndUnitFromRawValue( value );
		return [ parsedQuantity, unit ].join( '' );
	}, [ unit, value ] );

	// const min = isPx ? COVER_MIN_HEIGHT : 0;

	return (
		<BaseControl label={ __( 'Maximum Width of image' ) } id={ inputId }>
			<UnitControl
				id={ inputId }
				isResetValueOnUnitChange
				onChange={ handleOnChange }
				onUnitChange={ onUnitChange }
				style={ { maxWidth: 80 } }
				units={ units }
				value={ computedValue }
			/>
		</BaseControl>
	);
}

function mediaPosition( { x, y }, width, height ) {
  return { 
    top: `calc(${ Math.round( y * 100 ) }% - ${height / 2}px)`, 
    left: `calc(${ Math.round( x * 100 ) }% - ${width / 2}px)` 
  }
	// return `${ Math.round( x * 100 ) }% ${ Math.round( y * 100 ) }%`;
}

const Edit = ( { attributes, setAttributes, clientId } ) => {

  const {
    templateLock,
    mediaURL,
    mediaID,
    focalPoint,
    mediaMaxWidth,
    mediaMaxWidthUnit
  } = attributes;

  const { width: wrapperWidth, height: wrapperHeight, ref: measuredRef } = useResizeDetector();

  const onResizeImage = useCallback(( width, height ) => {
    setAttributes( {
      mediaSizes: {
        width: width,
        height: height,
      }
    } )
  }, []);

  const { width: imageWidth, height: imageHeight, ref: imageMeasuredRef } = useResizeDetector( { onResize: onResizeImage });


  const defaultImageMaxWidth = imageWidth || mediaMaxWidth;
  const defaultImageMaxWidthUnit = mediaMaxWidthUnit;

  const focalDimensions = {
    width: wrapperWidth,
    height: wrapperHeight,
  };

  // const measuredRef = useCallback(node => {
  //   if (node !== null) {
  //     console.log(node.getBoundingClientRect());
  //     // setHeight(node.getBoundingClientRect().height);
  //   }
  // }, []);

  // useEffect(() => {
  //   // Update the document title using the browser API
  //   document.title = `You clicked ${count} times`;
  // });

  const { hasInnerBlocks, image } = useSelect(
		( select ) => {
			const { getBlock } = select( blockEditorStore );
			const { getMedia } = select( 'core' );
			const block = getBlock( clientId );
			return {
				hasInnerBlocks: !! ( block && block.innerBlocks.length ),
        image: mediaID 
          ? getMedia( mediaID , { context: 'view' } )
          : null
			};
		},
		[ mediaID, clientId ]
	);

  const mediaStyle = {
		  ...( focalPoint && imageWidth && imageHeight
        ? mediaPosition(focalPoint, imageWidth, imageHeight)
        : {} ),
      // ...( mediaURL ? { backgroundImage: `url(${ mediaURL })` } : {} ),
      ...( mediaMaxWidth && mediaMaxWidthUnit
        ? { width: `${mediaMaxWidth}${mediaMaxWidthUnit}`}
        : {} )
	};

  const imperativeFocalPointPreview = ( value ) => {
    const newValu = mediaPosition(value, imageWidth, imageHeight);
    const styleOfRef = imageMeasuredRef.current.style;
    styleOfRef['top'] = newValu.top;
    styleOfRef['left'] =newValu.left;
    // console.log(styleOfRef['top'], styleOfRef['left']);
  }

  const ref = measuredRef;

  const blockProps = useBlockProps( { ref } );

  const innerBlocksProps = useInnerBlocksProps(
		{
			templateLock,
			renderAppender: hasInnerBlocks
				? undefined
				: InnerBlocks.ButtonBlockAppender,
		}
	);


  const onSelectImage = ( media ) => {
		setAttributes( {
			mediaURL: media.url,
			mediaID: media.id,
		} );
	};

  return (
    <>
      <InspectorControls key="setting">
        <PanelBody>
          <MediaUpload
            onSelect={ onSelectImage }
            allowedTypes={ ALLOWED_MEDIA_TYPES }
            value={ mediaID }
            render={ ( { open } ) => (
              <div className="editor-post-featured-image__container">
                <Button
                  className={
                    ! mediaID
                      ? 'editor-post-featured-image__toggle'
                      : 'editor-post-featured-image__preview'
                  }
                  onClick={ open }
                >
                  { !! mediaID && image && (
                    <img
                      src={ image.source_url }
                      alt=""
                    />
                  ) }
                  { ! mediaID && `Ajouter une Image` }
                </Button>
              </div>
            ) }
          />
          {!! mediaURL && (
            <FocalPointPicker
              label={ __( 'Focal point picker' ) }
              dimensions={focalDimensions}
              // url={ mediaURL }
              value={ focalPoint }
              onDragStart={ imperativeFocalPointPreview }
              onDrag={ imperativeFocalPointPreview }
              onChange={ ( newFocalPoint ) =>
                setAttributes( {
                  focalPoint: newFocalPoint,
                } )
              }
            />
          ) }
        </PanelBody>
      </InspectorControls>
      <InspectorControls __experimentalGroup="dimensions">
				<ToolsPanelItem
					hasValue={ () => !! mediaMaxWidth }
					label={ __( 'Maximum Width' ) }
					onDeselect={ () =>
						setAttributes( {
							mediaMaxWidth: undefined,
							mediaMaxWidthUnit: undefined,
						} )
					}
					resetAllFilter={ () => ( {
						mediaMaxWidth: undefined,
						mediaMaxWidthUnit: undefined,
					} ) }
					isShownByDefault={ true }
					panelId={ clientId }
				>
					<ImageWidthInput
						value={ defaultImageMaxWidth }
						unit={ defaultImageMaxWidthUnit }
						onChange={ ( newMaxWidth ) =>
							setAttributes( { mediaMaxWidth: newMaxWidth } )
						}
						onUnitChange={ ( nextUnit ) =>
							setAttributes( {
								mediaMaxWidthUnit: nextUnit,
							} )
						}
					/>
				</ToolsPanelItem>
			</InspectorControls>
      <div { ...blockProps } className='block-wrapper-img__container'>
        {!! mediaURL && (
          <img
            className='block-wrapper-img__element'
            src={ mediaURL }
            style={ mediaStyle }
            ref={ imageMeasuredRef }
          />
        ) }
        <div { ...innerBlocksProps } />
      </div>
    </>
  );
};
export default Edit;
