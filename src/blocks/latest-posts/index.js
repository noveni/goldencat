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
 
 registerBlockType('ecrannoirtwentyone/latest-posts', {
   title:  __( 'Theme Latest Type Posts' ),
   description: __( 'Display a list of your most recent content.' ),
   icon,
   category: 'widgets',
   keywords: [ __( 'recent posts' ) ],
   supports: {
     align: true,
     html: false
   },
   example: {},
   attributes: {
     categories: {
       type: 'array',
       items: {
         type: 'object'
       }
     },
     postType: {
       type: 'string',
       default: 'post',
     },
     postsToShow: {
       type: 'number',
       default: 5
     },
     displayPostContent: {
       type: 'boolean',
       default: false
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
       default: 'date'
     },
     displayFeaturedImage: {
       type: 'boolean',
       default: false
     },
   },
   edit
 });
 