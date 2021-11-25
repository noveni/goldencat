import classnames from 'classnames';

import { addFilter } from '@wordpress/hooks';
import { Fragment } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';

const authorizedBlocks = [ 'core/image', 'core/cover', 'core/group', 'core/columns', 'core/column', 'core/media-text', 'core/heading', 'core/paragraph', 'core/image', 'core/video', 'core/pullquote', 'core/social-links', 'core/list', 'goldencat/atelier-posts', 'goldencat/latest-posts', 'goldencat/quotes-slider', 'goldencat/cover-slider' ];



const availableApparition = [
  {
    label: 'None',
    value: '',
  },
  {
    label: 'Banner Text Slider',
    value: 'banner-text-slider'
  },
  {
    label: 'Block Media Reveal',
    value: 'block-media-reveal',
  },
  {
    label: 'Image Reveal',
    value: 'img-reveal',
  },
  {
    label: 'Simple Reveal',
    value: 'block-simple-reveal',
  },
  {
    label: 'Reveal From Right',
    value: 'reveal-from-right',
  },
  {
    label: 'Reveal From Left',
    value: 'reveal-from-left',
  },
  {
    label: 'Reveal From Bottom',
    value: 'reveal-from-bottom',
  },
  {
    label: 'Reveal Multiple items From Bottom With Stagger',
    value: 'reveal-lists-from-bottom-stagger',
  },
  {
    label: 'Reveal Text',
    value: 'reveal-text-from-bottom',
  },

];

const addAttributes = (settings, name) => {

  if ( typeof settings.attributes !== 'undefined' && authorizedBlocks.includes( settings.name ) ) {
    settings.attributes = Object.assign(settings.attributes, {
      themeApparitionEffect: {
        type: 'string',
      }
    });
  }
  return settings;
}

const withAttributesControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { attributes, setAttributes, isSelected } = props;
		return (
			<Fragment>
				<BlockEdit {...props} />
				{isSelected && (authorizedBlocks.includes( props.name )) && 
					<InspectorControls>
            <PanelBody title='Effects'>
              <SelectControl
                key="block-apparition-select"
                label='Apparition'
                value={ attributes.themeApparitionEffect }
                options={ availableApparition }
                onChange={ ( value ) => setAttributes( { themeApparitionEffect: value } ) }
              />
            </PanelBody>
					</InspectorControls>
				}
			</Fragment>
		);
	};
}, 'withAttributesControls');
 


function applyExtraClass(extraProps, blockType, attributes) {
	const { themeApparitionEffect } = attributes;
 
  if ( typeof themeApparitionEffect !== 'undefined' && themeApparitionEffect.length && authorizedBlocks.includes( blockType.name ) ) {
    extraProps.className = classnames( extraProps.className, 'theme-anim-' + themeApparitionEffect );
		// extraProps.className = extraProps.className + ' apparition' + themeApparitionEffect;
	}
	return extraProps;
}


addFilter(
  'blocks.registerBlockType', 
  'goldencat/blocks-apparition-effect-attributes', 
  addAttributes
)

addFilter(
	'editor.BlockEdit',
	'goldencat/blocks-apparition-effect-controls',
	withAttributesControls
);
 
addFilter(
	'blocks.getSaveContent.extraProps',
	'goldencat/blocks-apparition-effect-attributes-apply-class',
	applyExtraClass
);

