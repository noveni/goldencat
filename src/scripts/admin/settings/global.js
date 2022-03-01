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
  Notice,
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
    this.createCommingSoonPage = this.createCommingSoonPage.bind( this );

    this.state = {
      isAPISaving: false,
      isAPILoaded: false,
      maintenanceOn: false,
      showAdminBarOn: true,
      comingsoonOn: false,
      isPageComingSoonExist: false
    };
  }

  componentDidMount() {
    api.loadPromise.then( () => {

      this.settings = new api.models.Settings();
      const Page = new api.models.Page( );

      const { isAPILoaded } = this.state;

      if ( isAPILoaded === false ) {
        Page.fetch( { data: { slug: "coming-soon" } } ).then( (response) => {
          this.setState( {
            isPageComingSoonExist: response.length > 0
          })
        })
				this.settings.fetch().then( ( response ) => {
          this.setState( {
            maintenanceOn: Boolean( response[ 'goldencat_theme_maintenance_on' ] ),
            showAdminBarOn: Boolean( response[ 'goldencat_theme_show_admin_bar_on' ] ),
            comingsoonOn: Boolean( response[ 'goldencat_theme_coming_soon_on' ] ),
            isAPILoaded: true,
          })
        })
      }
    })
  }

  saveSettings() {
    const {
      maintenanceOn,
      showAdminBarOn,
      comingsoonOn
    } = this.state;

    this.setState({ isAPISaving: true });

    const settings = new api.models.Settings( {
      [ 'goldencat_theme_maintenance_on' ]: maintenanceOn ? 'true' : 'false',
      [ 'goldencat_theme_coming_soon_on' ]: comingsoonOn ? 'true' : 'false',
      [ 'goldencat_theme_show_admin_bar_on' ]: showAdminBarOn ? 'true' : 'false',
    } );
    settings.save().then( response => {
      this.setState({
          ['goldencat_theme_maintenance_on']: response['goldencat_theme_maintenance_on'],
          ['goldencat_theme_coming_soon_on']: response['goldencat_theme_coming_soon_on'],
          ['goldencat_theme_show_admin_bar_on']: response['goldencat_theme_show_admin_bar_on'],
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

  createCommingSoonPage() {

    this.setState({ isAPISaving: true });

    const pageData = {
      title: 'Coming Soon',
      slug: 'coming-soon',
      status: 'publish'
    }

    
    // Check before to create if page is exisint
    const existingComingSoonPage = new api.models.Page();
    existingComingSoonPage.fetch( { data: { slug: "coming-soon" } } ).then( ( pages ) => {
      // If page exist we update the state dispatch the info
      if (pages.length > 0) {

        this.setState( {
          isPageComingSoonExist: true,
          isAPISaving: false
        })

        dispatch('core/notices').createNotice(
          'success',
          __( 'Page already created', 'goldencat' ),
          {
              type: 'snackbar',
              isDismissible: true,
              actions: [{
                label: 'Voir la page',
                url: pages[0].link
              }]
          }
        );
      } else {
        // Else we create the page
        const newComingSoonPage = new api.models.Page( pageData );
        newComingSoonPage.save().then( response => {

          this.setState( {
            isPageComingSoonExist: true,
            isAPISaving: false
          })
    
          dispatch('core/notices').createNotice(
            'success',
            __( 'Page created', 'goldencat' ),
            {
                type: 'snackbar',
                isDismissible: true,
                actions: [{
                  label: 'Voir la page',
                  url: response.link
                }]
            }
          );
        } )
      }
    });
  }

  render() {

    const {
			maintenanceOn,
      showAdminBarOn,
			isAPILoaded,
      comingsoonOn,
      isPageComingSoonExist
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
            <PanelRow>
              <ToggleControl
                label="Bar d'admin affichée"
                checked={ showAdminBarOn }
                onChange={ () => this.setState({ showAdminBarOn: !showAdminBarOn }) }
              />
            </PanelRow>
          </PanelBody>
          <PanelBody title="Coming-soon">
            <PanelRow>
              <div>
                <p>Afficher la page coming soon?</p>
                <p></p>
                {!isPageComingSoonExist && (
                  <Notice status="info" isDismissible={ false }>
                    <p>Une page <em>coming soon</em> doit être créé pour pouvoir faire fonctionner le mode.</p>
                    <Button variant="secondary" onClick={this.createCommingSoonPage}>Créer la page ?</Button>
                  </Notice>
                )}
              </div>
            </PanelRow>
            <PanelRow>
              <ToggleControl
                label="Coming Soon"
                checked={ comingsoonOn }
                disabled={!isPageComingSoonExist}
                onChange={ () => this.setState({ comingsoonOn: !comingsoonOn }) }
              />
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
