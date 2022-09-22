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
import save from './save';


registerBlockType('goldencat/picked-posts', {
  title:  __( 'Picked Posts' ),
  description: __( 'Display a list/grid of picked posts.' ),
  icon,
  category: 'widgets',
  keywords: [ __( 'posts picked' ) ],
  supports: {
    align: true,
    html: false
  },
  example: {},
  attributes: {
		postType: {
			type: 'string',
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
    postLayout: {
			type: 'string',
			default: 'list'
		},
    displayFeaturedImage: {
			type: 'boolean',
			default: true
		},
  },
  edit,
	save
});
