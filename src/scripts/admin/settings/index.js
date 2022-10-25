
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import domReady from '@wordpress/dom-ready';
import {
  TabPanel
} from '@wordpress/components';

import {
	render,
	Fragment,
  useState
} from '@wordpress/element';


/**
 * Internal dependencies
 */

import GlobalSettings from "./global";
import ToolsSettings from "./tools";
import AnalyticsSettings from "./analytics";
import DocumentationSettings from "./docs";
import PosttypeSettings from "./posttype";
import CookieSettings from "./cookies";
import LabelSettings from "./label";
import ShareSettings from "./share";


const ThemeSettings = ( props ) => {
  const [ activeTab, setActiveTab ] = useState('tab-global');

  return (
    <Fragment>
      <div className="goldencat-settings-header">
        <div className="goldencat-settings-title-section">
          <h1 className="admin-title">Theme Settings</h1>
        </div> 
        <TabPanel
          className="goldencat-theme-settings-tab-panel"
          onSelect={ (tab) => setActiveTab(tab) }
          tabs={ [
              {
                  name: 'tab-global',
                  title: 'Réglages global',
              },
              {
                  name: 'tab-analytics',
                  title: 'Analytics',
              },
              {
                  name: 'tab-posttype',
                  title: 'Types de postes',
              },
              {
                  name: 'tab-cookie',
                  title: 'Cookies',
              },
              {
                  name: 'tab-label',
                  title: 'Labels',
              },
              {
                  name: 'tab-share',
                  title: 'Sharing',
              },
          ] }
        >
          { ( tab ) => '' }
        </TabPanel>
      </div>
      <div className="goldencat-settings-body">
        { activeTab == 'tab-global' && <GlobalSettings /> }
        { activeTab == 'tab-analytics' && <AnalyticsSettings /> }
        { activeTab == 'tab-posttype' && <PosttypeSettings /> }
        { activeTab == 'tab-cookie' && <CookieSettings /> }
        { activeTab == 'tab-label' && <LabelSettings /> }
        { activeTab == 'tab-share' && <ShareSettings /> }
      </div>
    </Fragment>
  );
}

domReady( () => {

	render(
		<ThemeSettings/>,
		document.getElementById( 'goldencat-theme-settings' )
	);
})

