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

registerBlockType('ecrannoirtwentyone/term-of-taxonomy', {
  title:  __( 'Terms of taxonomy' ),
  description: __( 'Display the terms of choosed taxonomy.' ),
  icon,
  category: 'widgets',
  keywords: [ __( 'taxonomy term' ) ],
  supports: {
    align: true,
    html: false
  },
  example: {},
  attributes: {
    taxonomy: {
      type: 'string',
      default: 'category'
    },
    termsToShow: {
      type: 'number',
      default: 9
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
      default: false
    },
    showPostCounts: {
      type: 'boolean',
      default: false
    },
    hideEmpty: {
      type: 'boolean',
      default: false
    },
    postLayout: {
      type: 'string',
      default: 'list'
    },
    order: {
      type: 'string',
      default: 'desc'
    },
    orderBy: {
      type: 'string',
      default: 'name'
    },
  },
  edit
});
