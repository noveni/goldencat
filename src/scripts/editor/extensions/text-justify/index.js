import classnames from 'classnames';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Toolbar, ToolbarGroup, ToolbarButton } from '@wordpress/components';
import { createHigherOrderComponent} from '@wordpress/compose';
import { BlockControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import { addFilter } from '@wordpress/hooks';
import { alignJustify } from '@wordpress/icons'


const authorizedBlocks = [ 'core/paragraph', 'core/list' ];

const addAttributes = ( settings, name ) => {
  if ( typeof settings.attributes !== 'undefined' && authorizedBlocks.includes( settings.name ) ) {
    settings.attributes = Object.assign(settings.attributes, {
      textAlignJustify: {
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
          <BlockControls group="block">
            <Toolbar>
              <ToolbarGroup>
                <ToolbarButton
                    icon={alignJustify}
                    label={ __( 'Justifier', 'goldencat' ) }
                    isActive={ !!attributes.textAlignJustify }
                    onClick={() => setAttributes({ textAlignJustify: !attributes.textAlignJustify })}
                />
              </ToolbarGroup>
            </Toolbar>
          </BlockControls>
        </Fragment>
      )
    }
    return <BlockEdit { ...props } />;
  };
}, 'addControls');

const applyExtraClass = ( extraProps, blockType, attributes ) => {
  const { textAlignJustify } = attributes

  if ( typeof textAlignJustify !== 'undefined' && textAlignJustify && authorizedBlocks.includes( blockType.name ) ) {
    extraProps.className = classnames( extraProps.className, 'goldencat-text-justify' );
  }

  return extraProps;
}

addFilter(
  'blocks.registerBlockType',
  'goldencat/text-justify-attributes',
  addAttributes
);

addFilter(
	'editor.BlockEdit',
	'goldencat/text-justify-controls',
	addControls
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'goldencat/text-justify-apply-class',
	applyExtraClass
);
