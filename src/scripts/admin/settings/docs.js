/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import domReady from '@wordpress/dom-ready';
import {
  BaseControl,
  Button,
  ExternalLink,
  PanelBody,
  PanelRow,
  Panel,
  Placeholder,
  Spinner,
  TabPanel,
  ToggleControl
} from '@wordpress/components';

import {
  render,
  Component,
  Fragment,
  useState
} from '@wordpress/element';

/**
 * Internal dependencies
 */

const DocumentationSettings = ( props ) => {

  return (
    <Fragment>
      <div className="goldencat-theme-settings-docs">
        <h2>Documentation</h2>
        <p>Retrouver la documentation lié au thème.</p>
      </div>
    </Fragment>
  );
}

export default DocumentationSettings;
