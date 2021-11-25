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

const addAttributes = (settings, name) => {
  if ( typeof settings.attributes !== 'undefined') {
    settings.attributes = Object.assign(settings.attributes, {
      goldencatHideBlock: {
        type: 'boolean',
      },
      goldencatHideBlockOnMobile: {
        type: 'boolean',
      },
      goldencatHideBlockOnLaptop: {
        type: 'boolean',
      }
    });
  }
  return settings;
}

const withHideChoice = createHigherOrderComponent( BlockEdit => {
	return ( props ) => {
    const { attributes, setAttributes, isSelected } = props;
		if (isSelected ) {
      
			return (
				<Fragment>
					<BlockEdit { ...props } />
          <InspectorAdvancedControls>
            <ToggleControl
              label='Hide Block'
              checked={!!attributes.goldencatHideBlock}
              onChange={() => setAttributes({ goldencatHideBlock: !attributes.goldencatHideBlock })}
            />
            <ToggleControl
              label='Hide Block on mobile'
              checked={!!attributes.goldencatHideBlockOnMobile}
              onChange={() => setAttributes({ goldencatHideBlockOnMobile: !attributes.goldencatHideBlockOnMobile })}
            />
            <ToggleControl
              label='Hide Block on Desktop'
              checked={!!attributes.goldencatHideBlockOnLaptop}
              onChange={() => setAttributes({ goldencatHideBlockOnLaptop: !attributes.goldencatHideBlockOnLaptop })}
            />
					</InspectorAdvancedControls>
				</Fragment>
			);
		}

		return <BlockEdit { ...props } />;
	};
}, 'withHideChoice' );

function applyExtraClass(extraProps, blockType, attributes) {
	const { goldencatHideBlock, goldencatHideBlockOnMobile, goldencatHideBlockOnLaptop } = attributes;
 
  if ( typeof goldencatHideBlock !== 'undefined' && goldencatHideBlock ) {

    extraProps.className = classnames( extraProps.className, 'goldencat-hide-block' );
	}
  if ( typeof goldencatHideBlockOnMobile !== 'undefined' && goldencatHideBlockOnMobile ) {

    extraProps.className = classnames( extraProps.className, 'goldencat-hide-block-on-mobile' );
	}
  if ( typeof goldencatHideBlockOnLaptop !== 'undefined' && goldencatHideBlockOnLaptop ) {

    extraProps.className = classnames( extraProps.className, 'goldencat-hide-block-on-laptop' );
	}
	return extraProps;
}


addFilter(
  'blocks.registerBlockType',
  'goldencat/hide-block-attributes',
  addAttributes
);

addFilter(
	'editor.BlockEdit',
	'goldencat/hide-block',
	withHideChoice
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'goldencat/hide-block-apply-class',
	applyExtraClass
);
