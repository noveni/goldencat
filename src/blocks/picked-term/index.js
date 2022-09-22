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


registerBlockType('goldencat/picked-term', {
  title:  __( 'Picked term' ),
  description: __( 'Display a picked term.' ),
  icon,
  category: 'widgets',
  keywords: [ __( 'term picked' ) ],
  supports: {
    align: true,
    html: false
  },
  example: {},
  attributes: {
    termId: {
      type: 'number',
    },
    taxonomy: {
      type: 'string',
      default: 'category'
    },
		displayDescription: {
			type: 'boolean',
			default: true
		},
    descriptionLength: {
			type: 'number',
			default: 55
		},
    displayImage: {
			type: 'boolean',
			default: true
		},
    showPostCounts: {
      type: 'boolean',
      default: false
    },
  },
  edit
});
