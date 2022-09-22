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


registerBlockType('goldencat/picked-post', {
  title:  __( 'Picked Post' ),
  description: __( 'Display a picked post.' ),
  icon,
  category: 'widgets',
  keywords: [ __( 'posts picked' ) ],
  supports: {
    align: true,
    html: false
  },
  example: {},
  attributes: {
    postId: {
      type: 'number',
    },
    postType: {
      type: 'string',
      default: 'post'
    },
		displayPostContent: {
			type: 'boolean',
			default: true
		},
    excerptLength: {
			type: 'number',
			default: 55
		},
    displayAuthor: {
			type: 'boolean',
			default: false
		},
		displayPostDate: {
			type: 'boolean',
			default: true
		},
    displayFeaturedImage: {
			type: 'boolean',
			default: true
		},
  },
  edit
});
