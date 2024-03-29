/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { buttons as icon } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import edit from './edit';
// import save from './save';
// import deprecated from './deprecated';

const { name } = metadata;
export { metadata, name };

export const settings = {
	icon,
	edit,
};

registerBlockType( name, {
  ...settings,
  ...metadata,
  }
);
