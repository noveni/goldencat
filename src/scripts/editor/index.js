import domReady from '@wordpress/dom-ready';

import { unregisterBlockStyle, registerBlockStyle } from '@wordpress/blocks';


// import './extensions/disable-title';
import './extensions/block-full-height';
import './extensions/block-links';
import './extensions/center-on-mobile';
import './extensions/hide-block';
import './extensions/narrow-align';
import './extensions/no-padding-block';
import './extensions/no-gap';
import './extensions/text-justify';
import './extensions/space-between';



import './variations';
import '../../blocks';

/**
 * Remove unused blocks
 *
 * @since 1.0.0
 */
 domReady( () => {
  unregisterBlockStyle( 'core/button', [ 'fill', 'outline' ] );
  unregisterBlockStyle( 'core/quote', [ 'default', 'large' ] );
  unregisterBlockStyle( 'core/image', [ 'default', 'rounded', 'editorskit-circular', 'editorskit-rounded', 'editorskit-diagonal', 'editorskit-inverted-diagonal', 'editorskit-shadow' ] );
  unregisterBlockStyle( 'core/columns', ['default', 'gapless']);

});
