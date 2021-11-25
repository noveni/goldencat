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
      goldencatAlignOnMobile: {
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
              label='Align center on mobile ?'
              checked={!!attributes.goldencatAlignOnMobile}
              onChange={() => setAttributes({ goldencatAlignOnMobile: !attributes.goldencatAlignOnMobile })}
            />
					</InspectorAdvancedControls>
				</Fragment>
			);
		}

		return <BlockEdit { ...props } />;
	};
}, 'withAttributesControls' );

function applyExtraClass(extraProps, blockType, attributes) {
	const { goldencatAlignOnMobile } = attributes;
 
  if ( typeof goldencatAlignOnMobile !== 'undefined' && goldencatAlignOnMobile ) {

    extraProps.className = classnames( extraProps.className, 'goldencat-align-center-on-mobile' );
	}
	return extraProps;
}


addFilter(
  'blocks.registerBlockType',
  'goldencat/align-on-mobile-attributes',
  addAttributes
);

addFilter(
	'editor.BlockEdit',
	'goldencat/align-on-mobile',
	withAttributesControls
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'goldencat/align-on-mobile-apply-class',
	applyExtraClass
);
