/**
 * External dependencies
 */

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
 
import {
  Fragment,
  useState,
  useEffect
} from '@wordpress/element';
import { store as coreDataStore } from '@wordpress/core-data';
import { dispatch, useSelect, useDispatch } from '@wordpress/data';

/**
 * Internal dependencies
 */
import { usePostTypes, Notices } from './utils';
 
const ShareSettings = ( props ) => {

  const {
    activeServices,
    activePostTypes,
    sharingSettings,
    isOpenGraphActive,
    isSaving,
    hasEdits
  } = useSelect( ( select ) => {
    const { getEditedEntityRecord, isSavingEntityRecord, hasEditsForEntityRecord } = select( coreDataStore );
    const siteSettings = getEditedEntityRecord( 'root', 'site' );
    const sharingSettings = siteSettings?.goldencat_theme_sharing_settings;
    return {
      activeServices: sharingSettings?.goldencat_sharing_services,
      activePostTypes: sharingSettings?.goldencat_sharing_posttype,
      isOpenGraphActive: sharingSettings?.goldencat_sharing_opengraph_active,
      sharingSettings,
      isSaving: isSavingEntityRecord( 'root', 'site' ),
      hasEdits: hasEditsForEntityRecord( 'root', 'site' )
    }
  } );


  const { editEntityRecord, saveEditedEntityRecord } = useDispatch( coreDataStore );

  const [ isAPILoaded, setAPILoaded ] = useState(false);

  useEffect( () => {
    if ( activeServices && activePostTypes ) {
      setAPILoaded(true)
    }

	}, [ activeServices, activePostTypes ] );
  
  const { postTypes } = usePostTypes();

  const socialServices = [
    {
      name: 'facebook',
      label: 'Facebook',
    },
    {
      name: 'linkedin',
      label: 'LinkedIn',
    },
    {
      name: 'pinterest',
      label: 'Pinterest',
    },
  ];
  

  const saveSharingSettings = async () => {
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
  

  const handleTogglePostType = (postType) => {
    let newActivePostType;
    if ( !activePostTypes.includes(postType) ) {
      newActivePostType = [...activePostTypes, postType];
    } else {
      newActivePostType = activePostTypes.filter( type => type != postType);
    }
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_sharing_settings': {...sharingSettings, 'goldencat_sharing_posttype': newActivePostType } })
  }

  const handleToggleService = ( service ) => {
    let newActiveService;
    if ( !activeServices.includes(service) ) {
      newActiveService = [...activeServices, service];
    } else {
      newActiveService = activeServices.filter( name => name != service);
    }
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_sharing_settings': {...sharingSettings, 'goldencat_sharing_services': newActiveService } })
  }

  const handleToggleOpenGraph = ( activeStatus ) => {
    editEntityRecord( 'root', 'site', undefined, { 'goldencat_theme_sharing_settings': {...sharingSettings, 'goldencat_sharing_opengraph_active': activeStatus } })
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
      <div className="goldencat-theme-settings-share">
      </div>
      <Panel header="Social Share">
        <PanelBody>
          <PanelRow>
          <p>Activer les partages vers les réseaux sociaux</p>
          </PanelRow>
          {isAPILoaded === 'not-found' && (
            <PanelRow>
              Aucune données trouvée. 'goldencat_theme_sharing_settings'
            </PanelRow>
          )}
        </PanelBody>
        {postTypes && postTypes.length > 0 && (
          <PanelBody title="Type de contenus">
            <PanelRow>
              <p>Activer les boutons de partage pour les contenus suivant</p>
            </PanelRow>
            
            {postTypes.map(( { labels, slug }, index) => {
              return (
                <PanelRow>
                  <ToggleControl
                    label={labels.singular_name}
                    checked={ activePostTypes.includes(slug) }
                    onChange={ () => handleTogglePostType(slug) }
                  />
                </PanelRow>
              )
            })}
            
          </PanelBody>
        )}
        {socialServices.length > 0 && (
          <PanelBody title="Réseaux sociaux">
            <PanelRow>
              <p>Activer les réseaux sociaux vers lesquelles vous voulez partager vos contenus</p>
            </PanelRow>
            
            {socialServices.map((service, index) => {
              return (
                <PanelRow>
                  <ToggleControl
                    label={service.label}
                    checked={ activeServices.includes( service.name ) }
                    onChange={ () => handleToggleService(service.name) }
                  />
                </PanelRow>
              )
            })}
            
          </PanelBody>
        )}
        <PanelBody title="OG Tag">
          <PanelRow>
            <p>Insérer les meta tag Open Graph pour le partage sur les réseaux sociaux</p>
          </PanelRow>
          <PanelRow>
            <ToggleControl
              label="Ajouter les tags"
              checked={ isOpenGraphActive }
              onChange={ () => handleToggleOpenGraph(!isOpenGraphActive) }
            />
          </PanelRow>
        </PanelBody>
        <Button
            isPrimary
            isLarge
            onClick={saveSharingSettings}
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
 
 export default ShareSettings;
 