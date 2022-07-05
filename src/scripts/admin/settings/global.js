/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
  Button,
  PanelBody,
  PanelRow,
  Panel,
  Placeholder,
  Spinner,
  Notice,
  ToggleControl
} from '@wordpress/components';

import { store as coreDataStore } from '@wordpress/core-data';
import { dispatch, useSelect, useDispatch } from '@wordpress/data';

import {
  Fragment,
  useState,
  useEffect
} from '@wordpress/element';

/**
 * Internal dependencies
 */
import { Notices } from './utils';

const GlobalSettings = ( props ) => {
  const {
    maintenanceModeIsActive,
    showAdminBarIsActive,
    stickyHeaderActive,
    comingSoonIsActive,
    comingSoonPage,
    hasResolvedComingSoonPage,
    globalSettings,
    isSaving,
    hasEdits
  } = useSelect( ( select ) => {
    const { getEntityRecords, getEditedEntityRecord, isSavingEntityRecord, hasEditsForEntityRecord, hasFinishedResolution } = select( coreDataStore );
    const siteSettings = getEditedEntityRecord( 'root', 'site' );

    const query = {};
    query.slug = 'coming-soon';
    const selectorArgs = [ 'postType', 'page', query ];
    const globalSettings = siteSettings?.goldencat_theme_global_settings;
    return {
      maintenanceModeIsActive: globalSettings?.maintenance_active,
      showAdminBarIsActive: globalSettings?.show_admin_bar_active,
      stickyHeaderActive: globalSettings?.sticky_header_active,
      comingSoonIsActive: globalSettings?.coming_soon_active,
      comingSoonPage: getEntityRecords( ...selectorArgs )?.[0],
      hasResolvedComingSoonPage: hasFinishedResolution( 'getEntityRecords', selectorArgs ),
      globalSettings,
      isSaving: isSavingEntityRecord( 'root', 'site' ),
      hasEdits: hasEditsForEntityRecord( 'root', 'site' )
    }
  });

  const { lastError, isSavingPage } = useSelect(
    ( select ) => ( {
        lastError: select( coreDataStore )
            .getLastEntitySaveError( 'postType', 'page' ),
        isSaving: select( coreDataStore )
            .isSavingEntityRecord( 'postType', 'page' ),
    } ),
    []
);

  const { saveEntityRecord, editEntityRecord, saveEditedEntityRecord } = useDispatch( coreDataStore );

  const [ isAPILoaded, setAPILoaded ] = useState(false);

  useEffect( () => {
    if ( globalSettings ) {
      setAPILoaded(true)
    }

	}, [ globalSettings ] );

  const createCommingSoonPage = async ( ) => {
    const savedRecord = await saveEntityRecord(
      'postType',
      'page',
      { title: 'Coming Soon', slug: 'coming-soon', status: 'publish' }
    );

    if ( savedRecord ) {
      dispatch('core/notices').createNotice(
        'success',
        __( 'Page created', 'goldencat' ),
        {
            type: 'snackbar',
            isDismissible: true,
            actions: [{
              label: 'Voir la page',
              url: savedRecord.link
            }]
        }
      );
    }
  }


  const handleToggleMaintenanceMode = ( activeStatus ) => {
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_global_settings': {...globalSettings, 'maintenance_active': activeStatus } })
  }

  const handleToggleShowAdminBar = ( activeStatus ) => {
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_global_settings': {...globalSettings, 'show_admin_bar_active': activeStatus } })
  }

  const handleToggleStickyHeader = ( activeStatus ) => {
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_global_settings': {...globalSettings, 'sticky_header_active': activeStatus } })
  }

  const handleToggleShowComingSoon = ( activeStatus ) => {
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_global_settings': {...globalSettings, 'coming_soon_active': activeStatus } })
  }

  const saveGlobalSettings = async () => {
    const updatedRecord = await saveEditedEntityRecord( 'root', 'site' );
    if ( updatedRecord ) {

      dispatch('core/notices').createNotice(
        'success',
        __( 'Settings Saved', 'goldencat' ),
        {
            type: 'snackbar',
            isDismissible: true,
        }
      );
    }
  }

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
      </div>
      <Panel header="Réglages globales du thème">
        <PanelBody>
          <PanelRow>
          <p>Permet d'activer la maintenance, afficher la bar d'admin</p>
          </PanelRow>
          {isAPILoaded === 'not-found' && (
            <PanelRow>
              Aucune données trouvée. 'goldencat_theme_global_settings'
            </PanelRow>
          )}
        </PanelBody>
        <PanelBody title="Sticky Header">
          <PanelRow>
            <p>Activer menu sticky ?</p>
          </PanelRow>
          <PanelRow>
            <ToggleControl
              label="Sticky header"
              checked={ stickyHeaderActive }
              onChange={ () => handleToggleStickyHeader(!stickyHeaderActive )}
            />
          </PanelRow>
        </PanelBody>
        <PanelBody title="Maintenance">
          <PanelRow>
            <p>Activer le mode maintenance ?</p>
          </PanelRow>
          <PanelRow>
            <ToggleControl
              label="Mode maintenance"
              checked={ maintenanceModeIsActive }
              onChange={ () => handleToggleMaintenanceMode(!maintenanceModeIsActive )}
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
              checked={ showAdminBarIsActive }
              onChange={ () => handleToggleShowAdminBar( !showAdminBarIsActive )}
            />
          </PanelRow>
        </PanelBody>
        <PanelBody title="Coming-soon">
          <PanelRow>
            <p>Afficher la page coming soon?</p>
            <p></p>
            { hasResolvedComingSoonPage && !comingSoonPage && (
              <Notice status="info" isDismissible={ false }>
                <p>Une page <em>coming soon</em> doit être créé pour pouvoir faire fonctionner le mode.</p>
                <Button variant="secondary" onClick={createCommingSoonPage}>Créer la page ?</Button>
              </Notice>
            )}
          </PanelRow>
          <PanelRow>
            <ToggleControl
              label="Coming Soon"
              checked={ comingSoonIsActive }
              disabled={!hasResolvedComingSoonPage || comingSoonPage == null }
              onChange={ () => handleToggleShowComingSoon( !comingSoonIsActive) }
            />
          </PanelRow>
        </PanelBody>
        <Button
          isPrimary
          isLarge
          onClick={saveGlobalSettings}
          disabled={ ! hasEdits || isSaving }
        >
          { isSaving ? (
            <>
                <Spinner/>
                Saving
            </>
          ) : __( 'Save', 'goldencat-theme' ) }
        </Button>
      </Panel>
      <div className="goldencat-theme-settings__notices">
        <Notices/>
      </div>
    </Fragment>
  )
}

export default GlobalSettings;
