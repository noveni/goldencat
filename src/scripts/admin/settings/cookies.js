/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import domReady from '@wordpress/dom-ready';
import {
  BaseControl,
  Button,
  ExternalLink,
  Disabled,
  Notice,
  PanelBody,
  PanelRow,
  Panel,
  Placeholder,
  SelectControl,
  SnackbarList,
  Spinner,
  TabPanel,
  TextControl,
  TextareaControl,
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
  useState,
  useEffect
} from '@wordpress/element';

import { store as noticesStore } from '@wordpress/notices';
 
/**
* Internal dependencies
*/
import moment from 'moment';




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
 
const CookieSettings = ( props ) => {

  const [isAPILoaded, setAPILoaded] = useState(false);
  const [isCookieNoticeActive, setCookieNoticeActive] = useState(false);
  const [cookieNoticeMsg, setCookieNoticeMsg] = useState('');
  const [cookieNoticeButton, setCookieNoticeButton] = useState('');
  const [cookieNoticeDuration, setCookieNoticeDuration] = useState(6);

  useEffect(() => {
    api.loadPromise.then( () => {
      const settings = new api.models.Settings();

      if ( isAPILoaded === false ) {
        settings.fetch().then( ( response ) => {
          if ( response[ 'goldencat_theme_cookie_settings' ] != undefined ) {

            setCookieNoticeActive( response[ 'goldencat_theme_cookie_settings' ]['goldencat_cookie_settings_active'] )
            setCookieNoticeMsg( response[ 'goldencat_theme_cookie_settings' ]['goldencat_cookie_settings_msg'] )
            setCookieNoticeButton( response[ 'goldencat_theme_cookie_settings' ]['goldencat_cookie_settings_btn'] )
            setCookieNoticeDuration( response[ 'goldencat_theme_cookie_settings' ]['goldencat_cookie_settings_duration'] )
            setAPILoaded(true)
          }
        })
      }
    })
  });

  const saveCookiesSettings = () => {

    const settings = new api.models.Settings( {
      [ 'goldencat_theme_cookie_settings' ]: {
        goldencat_cookie_settings_active: isCookieNoticeActive,
        goldencat_cookie_settings_msg: cookieNoticeMsg,
        goldencat_cookie_settings_btn: cookieNoticeButton,
        goldencat_cookie_settings_duration: cookieNoticeDuration
      }
    } );

    settings.save().then( response => {
      setCookieNoticeActive( response[ 'goldencat_theme_cookie_settings' ]['goldencat_cookie_settings_active'] )
      setCookieNoticeMsg( response[ 'goldencat_theme_cookie_settings' ]['goldencat_cookie_settings_msg'] )
      setCookieNoticeButton( response[ 'goldencat_theme_cookie_settings' ]['goldencat_cookie_settings_btn'] )
      setCookieNoticeDuration( response[ 'goldencat_theme_cookie_settings' ]['goldencat_cookie_settings_duration'] )

      dispatch('core/notices').createNotice(
        'success',
        __( 'Settings Saved', 'goldencat' ),
        {
            type: 'snackbar',
            isDismissible: true,
        }
      );
    })
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
        {isAPILoaded}
      </div>
      <Panel header="Cookie Compliance">
        <PanelBody>
          <PanelRow>
            <p>Administrer ici la façon dont sont gérer les cookies sur le site coté client. Rappelons que la loi </p>
          </PanelRow>
          
          <PanelRow>
            <ToggleControl
              label="Activer la notice de cookie"
              checked={isCookieNoticeActive}
              onChange={() => setCookieNoticeActive(!isCookieNoticeActive)}
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
                  onChange={( newMsg ) => setCookieNoticeMsg(newMsg)}
                />
              </div>
            </PanelRow>
            <PanelRow>
              <TextControl
                help="Texte du bouton pour accepter la notice et la faire disparaître."
                label="Bouton"
                value={cookieNoticeButton}
                onChange={( newBtnText ) => setCookieNoticeButton(newBtnText)}
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
                onChange={( newDuration ) => setCookieNoticeDuration(newDuration)}
                
              />
            </PanelRow>
          </PanelBody>
        </Disabled>
        <Button
            isPrimary
            isLarge
            onClick={saveCookiesSettings}
          >
            { __( 'Save', 'goldencat-theme' ) }
        </Button>
      </Panel>
      <div className="goldencat-theme-settings__notices">
        <Notices/>
      </div>
    </Fragment>
  );
}
 
export default CookieSettings;
 