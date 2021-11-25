/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { postList as icon } from '@wordpress/icons';
import { registerBlockType } from '@wordpress/blocks';
 
/**
* Internal dependencies
*/
import edit from './edit';

registerBlockType('ecrannoir/faq-block-grid', {
  title:  __( 'FAQ Grid' ),
  description: __( 'Afficher une liste de FAQ' ),
  icon,
  category: 'widgets',
  keywords: [ __( 'recent posts' ) ],
  supports: {
    align: ['wide', 'full'],
    html: false
  },
  example: {},
  attributes: {
    postType: {
      type: 'string',
      default: 'gc_faq',
    },
    postsToShow: {
      type: 'number',
      default: 20
    },
    align: {
      type: "string",
      default: "wide"
    },
    // postLayout: {
		// 	type: 'string',
		// 	default: 'grid'
		// },
    showFilter: {
      type: 'boolean',
      default: true,
    }
  },
  edit
});
