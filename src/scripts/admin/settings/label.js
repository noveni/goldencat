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
  TextControl,
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

const LabelSettings = ( props ) => {

  const {
    buttonLabels,
    labelSettings,
    isSaving,
    hasEdits
  } = useSelect( ( select ) => {
    const { getEditedEntityRecord, isSavingEntityRecord, hasEditsForEntityRecord } = select( coreDataStore );
    const siteSettings = getEditedEntityRecord( 'root', 'site' );
    const labelSettings = siteSettings?.goldencat_theme_label_settings;
    return {
      buttonLabels: siteSettings.goldencat_theme_label_settings && siteSettings.goldencat_theme_label_settings.filter(label => label.type === 'button'),
      labelSettings,
      isSaving: isSavingEntityRecord( 'root', 'site' ),
      hasEdits: hasEditsForEntityRecord( 'root', 'site' )
    }
  })

  const { editEntityRecord, saveEditedEntityRecord } = useDispatch( coreDataStore );


  const [ isAPILoaded, setAPILoaded ] = useState(false);

  useEffect( () => {
    if ( labelSettings && buttonLabels ) {
      setAPILoaded(true)
    }

	}, [ labelSettings, buttonLabels ] );

  const setLabel = ( newLabel, id ) => {

    const newLabels = labelSettings.map( labelItem => {
      if ( labelItem.id == id ) {
        return { ...labelItem, label: newLabel }
      }
      return labelItem;
    })

    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_label_settings': newLabels })
  }

  const saveLabelSettings = async () => {
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
      <Panel header="Labeling">
        <PanelBody>
          <PanelRow>
            <p>Édité ici les différents mot utilisé dans le site</p>
          </PanelRow>
          {isAPILoaded === 'not-found' && (
            <PanelRow>
              Aucune data trouvée. 'goldencat_theme_label_settings'
            </PanelRow>
          )}
        </PanelBody>
        {buttonLabels.length > 0 && (
          <PanelBody title="Boutons">
          {buttonLabels.map(( { id, location, label }, index) => {
            return (
              <PanelRow>
                <TextControl
                    help={`Texte du bouton qui apparaît sur la page ${location}`}
                    label={`Bouton des vignettes ${location}`}
                    value={label}
                    onChange={( newLabel ) => setLabel(newLabel, id )}
                  />
                </PanelRow>
            )
          })}
          </PanelBody>
        )}
        <Button
            isPrimary
            isLarge
            onClick={saveLabelSettings}
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
  );
}

export default LabelSettings;
 