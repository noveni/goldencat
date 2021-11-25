/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

import { TextControl } from '@wordpress/components';

registerBlockType( 'ecrannoirtwentyone/quote-meta', {
  title:  __( 'Meta Quote' ),
  description: __( "Nom et titre d'un témoignage" ),
  category: 'widgets',
  keywords: [ __( 'meta quote' ) ],
  attributes: {
		userName: {
			type: 'string',
			source: 'meta',
			meta: '_ecrannoirtwentyone_quotes_user_name',
		}
  },
  edit: props => {
    const { className, attributes, setAttributes } = props;

    return (
      <div className={ className }>
        <div className="name-title">
          <TextControl
            label="Prénom & Nom"
            value={ attributes.userName }
            onChange={(value) => { setAttributes( { userName: value } ) } }
          />
        </div>
      </div>
    )
    
  }

})
