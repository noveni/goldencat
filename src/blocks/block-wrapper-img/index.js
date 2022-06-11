/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

 /**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';

// Register the block
registerBlockType( 'goldencat/block-wrapper-img', {
    title:  __( 'Wrapper Image' ),
    description: __( 'Display a image over a block.' ),
    icon: 'universal-access-alt',
    category: 'layout',
    keywords: [ __( 'img' ), __( 'wrapper' ) ],
    supports: {
        html: false,
        align: [ "wide", "full" ],
    },
    attributes: {
        tagName: {
			type: 'string',
			default: 'div'
		},
		templateLock: {
			type: [ 'string', 'boolean' ],
			enum: [ 'all', 'insert', false ]
		},
        mediaSizes: {
            type: 'object'
        },
        mediaID: {
            type: 'number'
        },
        mediaMaxWidth: {
            type: 'number'
        },
        mediaMaxWidthUnit: {
            type: 'string',
            default: 'px'
        },
        mediaURL: {
            type: 'string',
        },
        focalPoint: {
			type: 'object'
		},
    },
    edit: Edit,
    save
} );
