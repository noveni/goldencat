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

registerBlockType('goldencat/quotes-slider', {
  title:  __( 'Témoignages Slider' ),
  description: __( 'Display a slider quotes.' ),
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
      default: 'gc_temoignage',
    },
    postsToShow: {
			type: 'number',
			default: 3
		},
    align: {
      type: "string",
      default: "full"
}
  },
  edit
});
