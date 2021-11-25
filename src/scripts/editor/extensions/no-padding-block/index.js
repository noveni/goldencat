import classnames from 'classnames';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { ToggleControl } from '@wordpress/components';
import { createHigherOrderComponent} from '@wordpress/compose';
import { InspectorAdvancedControls, BlockControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import { addFilter } from '@wordpress/hooks';

const authorizedBlocks = [ 'core/cover', 'core/group', 'core/columns', 'core/column', 'core/media-text', 'core/heading', 'goldencat/cover-slider' ];

const addAttributes = (settings, name) => {
  if ( typeof settings.attributes !== 'undefined' && authorizedBlocks.includes( settings.name ) ) {
    settings.attributes = Object.assign(settings.attributes, {
      goldencatNoPadding: {
        type: 'boolean',
      }
    });
  }
  return settings;
}

const withPaddingChoice = createHigherOrderComponent( BlockEdit => {
	return ( props ) => {
    const { attributes, setAttributes, isSelected } = props;
		if (isSelected && authorizedBlocks.includes( props.name )) {
      
			return (
				<Fragment>
					<BlockEdit { ...props } />
          <InspectorAdvancedControls>
            <ToggleControl
              label='No Padding'
              checked={!!attributes.goldencatNoPadding}
              onChange={() => setAttributes({ goldencatNoPadding: !attributes.goldencatNoPadding })}
            />
					</InspectorAdvancedControls>
				</Fragment>
			);
		}

		return <BlockEdit { ...props } />;
	};
}, 'withPaddingChoice' );

function applyExtraClass(extraProps, blockType, attributes) {
	const { goldencatNoPadding } = attributes;
 
  if ( typeof goldencatNoPadding !== 'undefined' && goldencatNoPadding && authorizedBlocks.includes( blockType.name ) ) {

    extraProps.className = classnames( extraProps.className, 'goldencat-no-padding' );
	}
	return extraProps;
}


addFilter(
  'blocks.registerBlockType',
  'goldencat/no-padding-block-attributes',
  addAttributes
);

addFilter(
	'editor.BlockEdit',
	'goldencat/no-padding-block',
	withPaddingChoice
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'goldencat/no-padding-block-apply-class',
	applyExtraClass
);
