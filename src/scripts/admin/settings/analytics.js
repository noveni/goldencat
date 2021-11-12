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

const AnalyticsSettings = ( props ) => {

  return (
    <Fragment>
      <div className="goldencat-theme-settings-analytics">
        <h2>Analytics</h2>
        <p>Permet de gérer l'aspect tracking du site</p>
      </div>
    </Fragment>
  );
}

export default AnalyticsSettings;
