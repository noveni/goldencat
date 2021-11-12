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
  SnackbarList,
  Spinner,
  TabPanel,
  ToggleControl
} from '@wordpress/components';

import api from '@wordpress/api';

import {
  dispatch,
  useDispatch,
  useSelect,
} from '@wordpress/data';

import {
  render,
  Component,
  Fragment,
  useState
} from '@wordpress/element';

import { store as noticesStore } from '@wordpress/notices';

/**
 * Internal dependencies
 */


const Notices = () => {
  const notices = useSelect(
    ( select ) =>
      select( noticesStore )
        .getNotices()
        .filter( ( notice ) => notice.type === 'snackbar' ),
    []
  );
  const { removeNotice } = useDispatch( noticesStore );
  return (
    <SnackbarList
      className="edit-site-notices"
      notices={ notices }
      onRemove={ removeNotice }
    />
  );
};

class GlobalSettings extends Component {

  constructor() {
    super( ...arguments );

    this.saveSettings = this.saveSettings.bind( this );

    this.state = {
      isAPISaving: false,
      isAPILoaded: false,
      maintenanceOn: false,
    };
  }

  componentDidMount() {
    api.loadPromise.then( () => {

      this.settings = new api.models.Settings();

      const { isAPILoaded } = this.state;

      if ( isAPILoaded === false ) {
				this.settings.fetch().then( ( response ) => {
          this.setState( {
            maintenanceOn: Boolean( response[ 'goldencat_theme_maintenance_on' ] ),
            isAPILoaded: true,
          })
        })
      }
    })
  }

  saveSettings() {
    const {
      maintenanceOn,
    } = this.state;

    this.setState({ isAPISaving: true });

    const settings = new api.models.Settings( {
      [ 'goldencat_theme_maintenance_on' ]: maintenanceOn ? 'true' : 'false',
    } );
    settings.save().then( response => {
      this.setState({
          ['goldencat_theme_maintenance_on']: response['goldencat_theme_maintenance_on'],
          isAPISaving: false
      });

      dispatch('core/notices').createNotice(
        'success',
        __( 'Settings Saved', 'goldencat' ),
        {
            type: 'snackbar',
            isDismissible: true,
        }
      );
    });
  }

  render() {

    const {
			maintenanceOn,
			isAPILoaded,
		} = this.state;

    if ( ! isAPILoaded ) {
			return (
				<Placeholder>
					<Spinner />
				</Placeholder>
			);
		}

    return (
      <Fragment>
        <div className="goldencat-theme-settings-global">
          <h2>Réglages globales du thème</h2>
          <p>Permet d'activer la maintenance, afficher la bar d'admin</p>
        </div>
        <Panel>
          <PanelBody title="Maintenance">
            <PanelRow>
              <p>Activer le mode maintenance ?</p>
            </PanelRow>
            <PanelRow>
              <ToggleControl
                label="Mode maintenance"
                checked={ maintenanceOn }
                onChange={ () => this.setState({ maintenanceOn: !maintenanceOn }) }
              />
            </PanelRow>
          </PanelBody>
          <PanelBody title="Admin-Bar">
            <PanelRow>
            Afficher la bar d'admin sur le site?
            </PanelRow>
          </PanelBody>
          <Button
            isPrimary
            isLarge
            onClick={this.saveSettings}
          >
            { __( 'Save', 'wholesome-plugin' ) }
          </Button>
        </Panel>

        <div className="goldencat-theme-settings__notices">
            <Notices/>
        </div>
      </Fragment>
    );
  }
}

export default GlobalSettings;
