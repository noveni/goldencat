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
import { Notices } from './utils';

const PostTypeSettings = ( props ) => {

  const {
    isFaqActive,
    isQuoteActive,
    postTypeSettings,
    isSaving,
    hasEdits
  } = useSelect( (select) => {
    const { getEditedEntityRecord, isSavingEntityRecord, hasEditsForEntityRecord } = select( coreDataStore );
    const siteSettings = getEditedEntityRecord( 'root', 'site' );
    const postTypeSettings = siteSettings?.goldencat_theme_posttype_settings;
    return {
      isFaqActive: postTypeSettings?.posttype_faq_on,
      isQuoteActive: postTypeSettings?.posttype_quote_on,
      postTypeSettings,
      isSaving: isSavingEntityRecord( 'root', 'site' ),
      hasEdits: hasEditsForEntityRecord( 'root', 'site' )
    }
  });

  const { editEntityRecord, saveEditedEntityRecord } = useDispatch( coreDataStore );

  const [ isAPILoaded, setAPILoaded ] = useState(false);

  useEffect( () => {
    if ( postTypeSettings ) {
      setAPILoaded(true)
    }

	}, [ postTypeSettings ] );

  const handleTogglePostType = ( postType, activeStatus ) => {
    switch (postType) {
      case 'faq':
        editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_posttype_settings': {...postTypeSettings, 'posttype_faq_on': activeStatus } })
        break;
      case 'quote':
        editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_posttype_settings': {...postTypeSettings, 'posttype_quote_on': activeStatus } })
        break;
      default:
        break;
    }
  }

  const savePostTypeSettings = async () => {
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
      <div className="goldencat-theme-settings-posttype">
      </div>
      <Panel>
        <PanelBody title="Type de post du thème">
          <PanelRow>
            <p>Permet d'activer des types de contenus supplémentaires</p>
          </PanelRow>
          {isAPILoaded === 'not-found' && (
            <PanelRow>
              Aucune données trouvée. 'goldencat_theme_posttype_settings'
            </PanelRow>
          )}
        </PanelBody>
        {postTypeSettings && (
          <PanelBody title="Type de post">
            <PanelRow>
              <ToggleControl
                label="FAQ"
                checked={ isFaqActive }
                onChange={ () => handleTogglePostType( 'faq', !isFaqActive ) }
              />
            </PanelRow>
            <PanelRow>
              <ToggleControl
                label="Témoignages"
                checked={ isQuoteActive }
                onChange={ () => handleTogglePostType( 'quote', !isQuoteActive ) }
              />
            </PanelRow>
          </PanelBody>
        )}
        <Button
          isPrimary
          isLarge
          onClick={savePostTypeSettings}
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

export default PostTypeSettings;
 