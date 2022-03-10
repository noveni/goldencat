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
const LabelSettings = ( props ) => {

  const [isAPILoaded, setAPILoaded] = useState(false);
  const [labelBtnProductShop, setLabelBtnProductShop] = useState('Découvrir');

  useEffect(() => {
    api.loadPromise.then( () => {
      const settings = new api.models.Settings();

      if ( isAPILoaded === false ) {
        settings.fetch().then( ( response ) => {

          if ( response[ 'goldencat_theme_label_settings' ] != undefined ) {

            setLabelBtnProductShop( response[ 'goldencat_theme_label_settings' ]['goldencat_label_btn_product_shop'] )
            
            setAPILoaded(true)
          } else {
            setAPILoaded('not-found')
          }
        })
      }
    })
  });

  const saveLabelSettings = () => {
    const settings = new api.models.Settings( {
      [ 'goldencat_theme_label_settings' ]: {
        goldencat_label_btn_product_shop: labelBtnProductShop,
      }
    } );

    settings.save().then( response => {
      setLabelBtnProductShop( response[ 'goldencat_theme_label_settings' ]['goldencat_label_btn_product_shop'] )

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
        <Disabled isDisabled={ isAPILoaded === 'not-found' }>
          <PanelBody title="Boutons">
            <PanelRow>
              <TextControl
                  help="Texte du bouton qui apparaît sur la page boutique"
                  label="Bouton des vignettes produits"
                  value={labelBtnProductShop}
                  onChange={( newBtnText ) => setLabelBtnProductShop(newBtnText)}
                />
            </PanelRow>
          </PanelBody>
        </Disabled>
        <Button
            isPrimary
            isLarge
            onClick={saveLabelSettings}
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

export default LabelSettings;
 