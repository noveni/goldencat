/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
  Button,
  Disabled,
  PanelBody,
  PanelRow,
  Panel,
  Placeholder,
  SelectControl,
  Spinner,
  TextControl,
  TextareaControl,
  ToggleControl
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
import moment from 'moment';
import { Notices } from './utils';

 
const CookieSettings = ( props ) => {

  const {
    isCookieNoticeActive,
    cookieNoticeMsg,
    cookieNoticeButtonLabel,
    cookieNoticeDuration,
    cookieSettings,
    isSaving,
    hasEdits
  } = useSelect( (select) => {
    const { getEditedEntityRecord, isSavingEntityRecord, hasEditsForEntityRecord } = select( coreDataStore );
    const siteSettings = getEditedEntityRecord( 'root', 'site' );
    const cookieSettings = siteSettings?.goldencat_theme_cookie_settings;
    return {
      isCookieNoticeActive: cookieSettings?.goldencat_cookie_settings_active,
      cookieNoticeMsg: cookieSettings?.goldencat_cookie_settings_msg,
      cookieNoticeButtonLabel: cookieSettings?.goldencat_cookie_settings_btn,
      cookieNoticeDuration: cookieSettings?.goldencat_cookie_settings_duration,
      cookieSettings,
      isSaving: isSavingEntityRecord( 'root', 'site' ),
      hasEdits: hasEditsForEntityRecord( 'root', 'site' )
    }
  });
  const { editEntityRecord, saveEditedEntityRecord } = useDispatch( coreDataStore );

  const [isAPILoaded, setAPILoaded] = useState(false);

  useEffect( () => {
    if ( cookieSettings ) {
      setAPILoaded(true)
    }

	}, [ cookieSettings ] );

  const handleToggleCookieNoticeActive = ( isActive ) => {
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_cookie_settings': {...cookieSettings, 'goldencat_cookie_settings_active': isActive } })
  }

  const handleChangeCookieNoticeMsg = ( newMsg ) => {
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_cookie_settings': {...cookieSettings, 'goldencat_cookie_settings_msg': newMsg } })
  }

  const handleChangeCookieNoticeButtonLabel = ( newLabelButton ) => {
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_cookie_settings': {...cookieSettings, 'goldencat_cookie_settings_btn': newLabelButton } })
  }

  const handleChangeNoticeDuration = ( newDuration ) => {
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_cookie_settings': {...cookieSettings, 'goldencat_cookie_settings_duration': newDuration } })
  }

  const saveCookiesSettings = async () => {
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
      <div className="goldencat-theme-settings-cookie">
      </div>
      <Panel header="Cookie Compliance">
        <PanelBody>
          <PanelRow>
            <p>Administrer ici la façon dont sont gérer les cookies sur le site coté client. Rappelons que la loi </p>
          </PanelRow>
          {isAPILoaded === 'not-found' && (
            <PanelRow>
              Aucune data trouvée. 'goldencat_theme_cookie_settings'
            </PanelRow>
          )}
          
          <PanelRow>
            <ToggleControl
              label="Activer la notice de cookie"
              checked={isCookieNoticeActive}
              onChange={() => handleToggleCookieNoticeActive(!isCookieNoticeActive)}
            />
          </PanelRow>

        </PanelBody>
        <Disabled isDisabled={ !isCookieNoticeActive }>
          <PanelBody title="Settings" opened={ isCookieNoticeActive }>
            <PanelRow>
              <div style={{width:'100%'}}>
                <TextareaControl
                  help="Entrez le message de la bannière de cookie."
                  label="Message"
                  rows={ 4 }
                  value={cookieNoticeMsg}
                  onChange={( newMsg ) => handleChangeCookieNoticeMsg(newMsg)}
                />
              </div>
            </PanelRow>
            <PanelRow>
              <TextControl
                help="Texte du bouton pour accepter la notice et la faire disparaître."
                label="Bouton"
                value={cookieNoticeButtonLabel}
                onChange={( newBtnText ) => handleChangeCookieNoticeButtonLabel(newBtnText)}
              />
            </PanelRow>
            <PanelRow>
              <SelectControl
                label="Délai de conservation"
                labelPosition="top"
                help="La durée pendant laquelle le cookie doit être stocké lorsque l'utilisateur accepte la notice"
                options={[
                  { value: null, label: 'Sélectionnez la durée', disabled: true },
                  { value: moment.duration(10, 'minutes').asSeconds(), label: '10 minutes' },
                  { value: moment.duration(1, 'hours').asSeconds(), label: '1 heure' },
                  { value: moment.duration(24, 'hours').asSeconds(), label: '24 heures' },
                  { value: moment.duration(1, 'months').asSeconds(), label: '1 mois' },
                  { value: moment.duration(3, 'months').asSeconds(), label: '3 mois' },
                  { value: moment.duration(6, 'months').asSeconds(), label: '6 mois (Maximum)' },
                ]}
                value={cookieNoticeDuration}
                onChange={( newDuration ) => handleChangeNoticeDuration(newDuration)}
                
              />
            </PanelRow>
          </PanelBody>
        </Disabled>
        <Button
            isPrimary
            isLarge
            onClick={saveCookiesSettings}
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
 
export default CookieSettings;
 