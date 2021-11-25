import classnames from 'classnames';
/**
 * Internal dependencies
 */
 import LinkToolbar from './controls';

/**
* WordPress Dependencies
*/
import { addFilter } from '@wordpress/hooks';
import { Fragment } from '@wordpress/element';
import { createHigherOrderComponent } from '@wordpress/compose';
import { hasBlockSupport } from "@wordpress/blocks";

const allowedBlocks = [ 'core/group', 'core/column', 'core/cover' ];


const addAttributes = (settings, name) => {

  if ( typeof settings.attributes !== 'undefined' && allowedBlocks.includes( settings.name ) ) {
    settings.attributes = Object.assign(settings.attributes, {
      goldencatBlockLink_href: {
        type: 'string',
      },
      goldencatBlockLink_opensInNewTab: {
        type: 'boolean',
        default: false,
      },
      goldencatBlockLink_linkNoFollow: {
        type: 'boolean',
        default: false,
      },
    });
  }
  return settings;
}

/**
* Override the default edit UI to include a new block toolbar control
*
* @param {Function} BlockEdit Original component.
* @return {string} Wrapped component.
*/
const withLinkToolbar = createHigherOrderComponent( ( BlockEdit ) => {
  return ( props ) => {
    return (
      <Fragment>
        <BlockEdit { ...props } />
        { props.isSelected && allowedBlocks.includes( props.name ) && <LinkToolbar { ...{ ...props } } /> }
      </Fragment>
    );
  };
}, 'withLinkToolbar' );

const applyExtraClass = (extraProps, blockType, attributes) => {
	const { goldencatBlockLink_href } = attributes;
 
  if ( typeof goldencatBlockLink_href !== 'undefined' && goldencatBlockLink_href && allowedBlocks.includes( blockType.name ) ) {
    extraProps.className = classnames( extraProps.className, 'goldencat-block-link-block' );
	}
	return extraProps;
}


addFilter(
  'blocks.registerBlockType', 
  'goldencat/block-link-toolbar',
  addAttributes
)

addFilter(
  'editor.BlockEdit',
  'goldencat/block-link-toolbar-controls',
  withLinkToolbar
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'goldencat/block-link-toolbar-apply-class',
	applyExtraClass
);

