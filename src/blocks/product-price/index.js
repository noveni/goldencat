/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { postTitle as icon } from '@wordpress/icons';

 /**
  * Internal dependencies
  */
 import metadata from './block.json';
 import edit from './edit';
 
 const { name } = metadata;
 export { metadata, name };
 
 export const settings = {
   icon,
   edit,
 };


registerBlockType( {
  name,
  ...metadata,
  },
  settings,
);
