/**
 * WordPress dependencies
 */

import { Component, Fragment } from '@wordpress/element';
import { BlockControls } from '@wordpress/block-editor';
import { Toolbar, withSpokenMessages } from '@wordpress/components';
import { withSelect } from '@wordpress/data';
import { compose, ifCondition } from '@wordpress/compose';

/**
* Internal dependencies
*/
import URLInputUI from './URLInput';

class withLinkToolbar extends Component {
  constructor() {
    super( ...arguments );

    this.onSetHref = this.onSetHref.bind( this );
  }

  onSetHref( props ) {
    this.props.setAttributes( props );
  }

  render() {
    const {
      attributes,
    } = this.props;

    const {
      goldencatBlockLink_href,
      goldencatBlockLink_opensInNewTab,
      goldencatBlockLink_linkNoFollow
    } = attributes;

    return (
      <Fragment>
        <BlockControls>
          <Toolbar>
            <URLInputUI
              url={ goldencatBlockLink_href || '' }
              opensInNewTab={ goldencatBlockLink_opensInNewTab || false }
              linkNoFollow={ goldencatBlockLink_linkNoFollow || false }
              onChangeUrl={ this.onSetHref }
            />
          </Toolbar>
        </BlockControls>
      </Fragment>
    );
  }
}

// export default compose(
//   withSelect( ( select, props ) => {
//     const {
//       attributes,
//     } = props;

//     return {
//       attributes,
//       isDisabled: select( 'core/edit-post' ).isFeatureActive( 'disableEditorsKitLinkBlockToolbarOptions' ),
//     };
//   } ),
//   ifCondition( ( props ) => {
//     return ! props.isDisabled;
//   } ),
//   withSpokenMessages
// )( withLinkToolbar );
export default withLinkToolbar;
