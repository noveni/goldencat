import classnames from 'classnames';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { createHigherOrderComponent} from '@wordpress/compose';
import { BlockControls, InspectorControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import { addFilter } from '@wordpress/hooks';

const authorizedBlocks = [ 'core/group', 'core/columns', 'core/media-text' ];

const addAttributes = ( settings, name ) => {
  if ( typeof settings.attributes !== 'undefined' && authorizedBlocks.includes( settings.name ) ) {
    settings.attributes = Object.assign(settings.attributes, {
      spaceBetween: {
        type: 'boolean',
      }
    });
  }
  return settings;
}
const addControls = createHigherOrderComponent( (BlockEdit) => {

  return ( props ) => {
    const { attributes, setAttributes, isSelected } = props;
    if (isSelected && authorizedBlocks.includes( props.name ) ) {
      return (
        <Fragment>
          <BlockEdit { ...props } />
          <InspectorControls>
            <PanelBody title='Alignement'>
              <ToggleControl
                label='Space Between'
                checked={!!attributes.spaceBetween}
                onChange={() => setAttributes({ spaceBetween: !attributes.spaceBetween })}
              />
            </PanelBody>
          </InspectorControls>
        </Fragment>
      )
    }
    return <BlockEdit { ...props } />;
  };
}, 'addControls');

const applyExtraClass = ( extraProps, blockType, attributes ) => {
  const { spaceBetween } = attributes

  if ( typeof spaceBetween !== 'undefined' && spaceBetween && authorizedBlocks.includes( blockType.name ) ) {
    extraProps.className = classnames( extraProps.className, 'goldencat-space-between' );
  }

  return extraProps;
}

addFilter(
  'blocks.registerBlockType',
  'goldencat/space-between-attributes',
  addAttributes
);

addFilter(
	'editor.BlockEdit',
	'goldencat/space-between-controls',
	addControls
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'goldencat/space-between-apply-class',
	applyExtraClass
);
