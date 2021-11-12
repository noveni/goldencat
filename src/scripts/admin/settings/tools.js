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

const ToolsSettings = ( props ) => {

  return (
    <Fragment>
      <div className="goldencat-theme-settings-global">
        <h2>Outils de gestion du thème</h2>
        <p>Permet d'effectuer certaine actions sur le site</p>
      </div>
    </Fragment>
  );
}

export default ToolsSettings;
