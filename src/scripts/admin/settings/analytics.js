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
  TextControl
} from '@wordpress/components';

import { store as coreDataStore } from '@wordpress/core-data';

import {
  dispatch,
  useDispatch,
  useSelect,
} from '@wordpress/data';

import {
  Fragment,
  useState,
  useEffect
} from '@wordpress/element';

/**
 * Internal dependencies
 */
import { Notices } from './utils';

const MetricsSettings = ( props ) => {
  const {
    gaId,
    metricsSettings,
    isSaving,
    hasEdits
  } = useSelect( ( select ) => {
    const { getEditedEntityRecord, isSavingEntityRecord, hasEditsForEntityRecord } = select( coreDataStore );
    const siteSettings = getEditedEntityRecord( 'root', 'site' );
    const metricsSettings = siteSettings?.goldencat_theme_metrics_settings;
    return {
      gaId: metricsSettings?.ga_measurement_id,
      metricsSettings,
      isSaving: isSavingEntityRecord( 'root', 'site' ),
      hasEdits: hasEditsForEntityRecord( 'root', 'site' )
    }
  } );

  const { editEntityRecord, saveEditedEntityRecord } = useDispatch( coreDataStore );

  const [ isAPILoaded, setAPILoaded ] = useState(false);

  useEffect( () => {
    if ( metricsSettings ) {
      setAPILoaded(true)
    }

	}, [ metricsSettings ] );

  const handleChangeMetricsSettings = ( newGaId ) => {
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_metrics_settings': {...metricsSettings, 'ga_measurement_id': newGaId } })
  }

  const saveMetricsSettings = async () => {
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
      <div className="goldencat-theme-settings-analytics">
      </div>
      <Panel header="Analytics">
        <PanelBody>
          <PanelRow>
          <p>Permet de gérer l'aspect tracking du site</p>
          </PanelRow>
          {isAPILoaded === 'not-found' && (
            <PanelRow>
              Aucune données trouvée. 'goldencat_theme_metrics_settings'
            </PanelRow>
          )}
        </PanelBody>
        <PanelBody title="Google Analytics">
          <PanelRow>
            <p>Ajouter l'id de Google Analytics pour lier le site.</p>
          </PanelRow>
          <PanelRow>
            <TextControl
              // help={ __( 'This is an example text field.', 'goldencat' ) }
              label={ __( 'GA ID', 'goldencat' ) }
              onChange={ ( newID ) => handleChangeMetricsSettings(newID) }
              value={ gaId }
            />
          </PanelRow>
        </PanelBody>
        <Button
          isPrimary
          isLarge
          onClick={saveMetricsSettings}
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

export default MetricsSettings;
