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

const addAttributes = (settings, name) => {
  if ( typeof settings.attributes !== 'undefined') {
    settings.attributes = Object.assign(settings.attributes, {
      goldencatNoMargin: {
        type: 'boolean',
      }
    });
  }
  return settings;
}

const withAttributesControls = createHigherOrderComponent( BlockEdit => {
	return ( props ) => {
    const { attributes, setAttributes, isSelected } = props;
		if (isSelected ) {
      
			return (
				<Fragment>
					<BlockEdit { ...props } />
          <InspectorAdvancedControls>
            <ToggleControl
              label='No Margin'
              checked={!!attributes.goldencatNoMargin}
              onChange={() => setAttributes({ goldencatNoMargin: !attributes.goldencatNoMargin })}
            />
					</InspectorAdvancedControls>
				</Fragment>
			);
		}

		return <BlockEdit { ...props } />;
	};
}, 'withAttributesControls' );

function applyExtraClass(extraProps, blockType, attributes) {
	const { goldencatNoMargin } = attributes;
 
  if ( typeof goldencatNoMargin !== 'undefined' && goldencatNoMargin ) {

    extraProps.className = classnames( extraProps.className, 'goldencat-no-margin' );
	}
	return extraProps;
}


addFilter(
  'blocks.registerBlockType',
  'goldencat/no-margin-block-attributes',
  addAttributes
);

addFilter(
	'editor.BlockEdit',
	'goldencat/no-margin-block',
	withAttributesControls
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'goldencat/no-margin-block-apply-class',
	applyExtraClass
);
