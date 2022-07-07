/**
 * WordPress dependencies
 */
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

const Save = ( { attributes } ) => {
  const {
		minHeight: minHeightProp,
		minHeightUnit,
	} = attributes;

  const minHeight =
		minHeightProp && minHeightUnit
			? `${ minHeightProp }${ minHeightUnit }`
			: minHeightProp;

  const style = {
    minHeight: minHeight || undefined,
  };
  
	return (
		<div { ...useBlockProps.save( { style } ) }>
			<InnerBlocks.Content />
		</div>
	);
};
export default Save;
