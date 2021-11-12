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
  TextControl,
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

class AnalyticsSettings extends Component {


  constructor() {
    super( ...arguments );

    this.saveSettings = this.saveSettings.bind( this );

    this.state = {
      isAPISaving: false,
      isAPILoaded: false,
      gaId: '',
      openGraphOn: true,
    };
  }

  componentDidMount() {
    api.loadPromise.then( () => {

      this.settings = new api.models.Settings();

      const { isAPILoaded } = this.state;

      if ( isAPILoaded === false ) {
				this.settings.fetch().then( ( response ) => {
          this.setState( {
            gaId: response[ 'goldencat_theme_ga_measurement_id' ],
            openGraphOn: Boolean( response[ 'goldencat_theme_opengraph_on' ] ),
            isAPILoaded: true,
          })
        })
      }
    })
  }

  saveSettings() {
    const {
      gaId,
      openGraphOn,
    } = this.state;

    this.setState({ isAPISaving: true });

    const settings = new api.models.Settings( {
      [ 'goldencat_theme_ga_measurement_id' ]: gaId,
      [ 'goldencat_theme_opengraph_on' ]: openGraphOn ? 'true' : 'false',
    } );
    settings.save().then( response => {
      this.setState({
          ['goldencat_theme_ga_measurement_id']: response['goldencat_theme_ga_measurement_id'],
          ['goldencat_theme_opengraph_on']: response['goldencat_theme_opengraph_on'],
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
			gaId,
      openGraphOn,
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
        <div className="goldencat-theme-settings-analytics">
          <h2>Analytics</h2>
          <p>Permet de gérer l'aspect tracking du site</p>
        </div>
        <Panel>
          <PanelBody title="Google Analytics">
            <PanelRow>
              <p>Ajouter l'id de Google Analytics pour lier le site.</p>
            </PanelRow>
            <PanelRow>
              <TextControl
                // help={ __( 'This is an example text field.', 'goldencat' ) }
                label={ __( 'GA ID', 'goldencat' ) }
                onChange={ ( newID ) => this.setState( { gaId: newID } ) }
                value={ gaId }
              />
            </PanelRow>
          </PanelBody>
          <PanelBody title="OG Tag">
            <PanelRow>
              <p>Insérer les meta tag Open Graph pour le partage sur les réseaux sociaux</p>
            </PanelRow>
            <PanelRow>
              <ToggleControl
                label="Ajouter les tags"
                checked={ openGraphOn }
                onChange={ () => this.setState({ openGraphOn: !openGraphOn }) }
              />
            </PanelRow>
          </PanelBody>
          <Button
            isPrimary
            isLarge
            onClick={this.saveSettings}
          >
            { __( 'Save', 'goldencat' ) }
          </Button>
        </Panel>

        <div className="goldencat-theme-settings__notices">
            <Notices/>
        </div>
      </Fragment>
    );
  }
}

export default AnalyticsSettings;
