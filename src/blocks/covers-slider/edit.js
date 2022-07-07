/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEntityProp, store as coreStore } from '@wordpress/core-data';
import {
  Fragment,
  useEffect,
  useRef,
  useState,
  useMemo,
} from '@wordpress/element';
import {
  InnerBlocks,
	InspectorControls,
	useInnerBlocksProps,
	BlockControls,
	BlockVerticalAlignmentToolbar,
	__experimentalBlockVariationPicker,
	useBlockProps,
  useSetting,
	store as blockEditorStore,
} from '@wordpress/block-editor';

import { withDispatch, useDispatch, useSelect } from '@wordpress/data';

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
 * Allowed blocks constant is passed to InnerBlocks precisely as specified here.
 * The contents of the array should never change.
 * The array should contain the name of each block that is allowed.
 * In columns block, the only block we allow is 'core/column'.
 *
 * @constant
 * @type {string[]}
 */
const ALLOWED_BLOCKS = [ 'core/cover' ];
const COVER_MIN_HEIGHT = 50;
// const innerBlocksProps = useInnerBlocksProps( blockProps, {
//   allowedBlocks: ALLOWED_BLOCKS,
//   // orientation: 'horizontal',
//   // renderAppender: false,
// } );

function SliderHeightInput( {
	onChange,
	onUnitChange,
	unit = 'px',
	value = '',
} ) {
	const instanceId = useInstanceId( UnitControl );
	const inputId = `block-covers-slide-height-input-${ instanceId }`;
	const isPx = unit === 'px';

	const units = useCustomUnits( {
		availableUnits: useSetting( 'spacing.units' ) || [
			'px',
			'em',
			'rem',
			'vw',
			'vh',
		],
		defaultValues: { px: 430, '%': 20, em: 20, rem: 20, vw: 20, vh: 50 },
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

	const min = isPx ? COVER_MIN_HEIGHT : 0;

	return (
		<BaseControl label={ __( 'Minimum height of cover' ) } id={ inputId }>
			<UnitControl
				id={ inputId }
				isResetValueOnUnitChange
				min={ min }
				onChange={ handleOnChange }
				onUnitChange={ onUnitChange }
				style={ { maxWidth: 80 } }
				units={ units }
				value={ computedValue }
			/>
		</BaseControl>
	);
}

const Edit = ( {
	attributes,
	clientId,
  isSelected,
	setAttributes,
	updateAlignment,
} ) => {

  const {
		id,
		minHeight,
		minHeightUnit,
	} = attributes;
  
  // const { count } = useSelect(
	// 	( select ) => {
	// 		return {
	// 			count: select( blockEditorStore ).getBlockCount( clientId ),
	// 		};
	// 	},
	// 	[ clientId ]
	// );

  const [ prevMinHeightValue, setPrevMinHeightValue ] = useState( minHeight );
	const [ prevMinHeightUnit, setPrevMinHeightUnit ] = useState(
		minHeightUnit
	);
	const isMinFullHeight = minHeightUnit === 'vh' && minHeight === 100;

  const minHeightWithUnit =
		minHeight && minHeightUnit
			? `${ minHeight }${ minHeightUnit }`
			: minHeight;

  const style = {
    minHeight: minHeightWithUnit || undefined,
  };

  const blockProps = useBlockProps();


	const hasInnerBlocks = useSelect(
		( select ) =>
			select( blockEditorStore ).getBlocks( clientId ).length > 0,
		[ clientId ]
	);

  const controls = (
		<>
      <InspectorControls __experimentalGroup="dimensions">
				<ToolsPanelItem
					hasValue={ () => !! minHeight }
					label={ __( 'Minimum height' ) }
					onDeselect={ () =>
						setAttributes( {
							minHeight: undefined,
							minHeightUnit: undefined,
						} )
					}
					resetAllFilter={ () => ( {
						minHeight: undefined,
						minHeightUnit: undefined,
					} ) }
					isShownByDefault={ true }
					panelId={ clientId }
				>
					<SliderHeightInput
						value={ minHeight }
						unit={ minHeightUnit }
						onChange={ ( newMinHeight ) =>
							setAttributes( { minHeight: newMinHeight } )
						}
						onUnitChange={ ( nextUnit ) =>
							setAttributes( {
								minHeightUnit: nextUnit,
							} )
						}
					/>
				</ToolsPanelItem>
			</InspectorControls>
    </>
  )

  const innerBlocksProps = useInnerBlocksProps(
		{
			allowedBlocks: ALLOWED_BLOCKS,
		}
	);

	return (
    <>
      { controls }
			<div
				{ ...blockProps }
				className={ blockProps.className }
				style={ { ...style, ...blockProps.style } }
			>
        <div { ...innerBlocksProps } />
      </div>
    </>
	);
};
export default Edit;
