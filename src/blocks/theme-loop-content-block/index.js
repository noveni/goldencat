/**
* External dependencies
*/
import { v4 as uuidv4 } from 'uuid';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { RichText, InnerBlocks } from '@wordpress/block-editor';
/**
* Internal dependencies
*/
// import edit from './edit';


const BLOCKS_TEMPLATE = [
  [ 'core/heading', {} ],
  [ 'core/group', {}, [
    [ 'core/list', {} ],
  ] ],
];

registerBlockType( 'goldencat/theme-loop-content-block', {
  title:  __( 'Loop Content' ),
  description: __( "Print the content above and bellow this block on a page." ),
  category: 'theme',
  keywords: [ __( 'loop-content' ) ],
  supports: {
    html: false,
    color: {
      background: true,
    }
  },
  attributes: {},
  edit: props => {
    const { className } = props;
    return (
      <div className={className}>
        Loop content
      </div>
    );
  },
  save: props => {
    const { className } = props;
    return (
      <div className={className}>
        Loop content
      </div>
    )
  },
});
