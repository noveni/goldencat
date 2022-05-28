import classnames from 'classnames';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { ToggleControl, PanelBody } from '@wordpress/components';
import { createHigherOrderComponent} from '@wordpress/compose';
import { InspectorControls, InspectorAdvancedControls, BlockControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import { addFilter } from '@wordpress/hooks';


const authorizedBlocks = [ 'core/columns', 'core/column' ];

const addAttributes = (settings, name) => {
  if ( typeof settings.attributes !== 'undefined' && authorizedBlocks.includes( settings.name ) ) {
    settings.attributes = Object.assign(settings.attributes, {
      goldencatNoGap: {
        type: 'boolean',
      }
    });
  }
  return settings;
}

const withAttributesControls = createHigherOrderComponent( BlockEdit => {
	return ( props ) => {
    const { attributes, setAttributes, isSelected } = props;
		if (isSelected && authorizedBlocks.includes( props.name ) ) {
      
			return (
				<Fragment>
					<BlockEdit { ...props } />
          <InspectorControls key="setting">
            <PanelBody>
              <ToggleControl
                label='No Gap'
                checked={!!attributes.goldencatNoGap}
                onChange={() => setAttributes({ goldencatNoGap: !attributes.goldencatNoGap })}
              />
            </PanelBody>
					</InspectorControls>
				</Fragment>
			);
		}

		return <BlockEdit { ...props } />;
	};
}, 'withAttributesControls' );

function applyExtraClass(extraProps, blockType, attributes) {
	const { goldencatNoGap } = attributes;
 
  if ( typeof goldencatNoGap !== 'undefined' && goldencatNoGap && authorizedBlocks.includes( blockType.name )) {

    extraProps.className = classnames( extraProps.className, 'goldencat-no-gap' );
	}
	return extraProps;
}


addFilter(
  'blocks.registerBlockType',
  'goldencat/no-gap-block-attributes',
  addAttributes
);

addFilter(
	'editor.BlockEdit',
	'goldencat/no-gap-block',
	withAttributesControls
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'goldencat/no-gap-block-apply-class',
	applyExtraClass
);
