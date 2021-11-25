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
 
     this.state = {
       isAPISaving: false,
       isAPILoaded: false,
       faqOn: false,
       quoteOn: true,
     };
   }
 
   componentDidMount() {
     api.loadPromise.then( () => {
 
       this.settings = new api.models.Settings();
 
       const { isAPILoaded } = this.state;
 
       if ( isAPILoaded === false ) {
         this.settings.fetch().then( ( response ) => {
           this.setState( {
             faqOn: Boolean( response[ 'goldencat_theme_posttype_faq_on' ] ),
             quoteOn: Boolean( response[ 'goldencat_theme_posttype_quote_on' ] ),
             isAPILoaded: true,
           })
         })
       }
     })
   }
 
   saveSettings() {
     const {
       faqOn,
       quoteOn
     } = this.state;
 
     this.setState({ isAPISaving: true });
 
     const settings = new api.models.Settings( {
       [ 'goldencat_theme_posttype_faq_on' ]: faqOn ? 'true' : 'false',
       [ 'goldencat_theme_posttype_quote_on' ]: quoteOn ? 'true' : 'false',
     } );
     settings.save().then( response => {
       this.setState({
           ['goldencat_theme_posttype_faq_on']: response['goldencat_theme_posttype_faq_on'],
           ['goldencat_theme_posttype_quote_on']: response['goldencat_theme_posttype_quote_on'],
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
       faqOn,
       quoteOn,
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
         <div className="goldencat-theme-settings-posttype">
           <h2>Type de post du thème</h2>
           <p>Permet d'activer des types de contenus supplémentaires</p>
         </div>
         <Panel>
           <PanelBody title="Type de contenus ?">
             <PanelRow>
               <p>Type de contenus activés</p>
             </PanelRow>
             <PanelRow>
               <ToggleControl
                 label="FAQ"
                 checked={ faqOn }
                 onChange={ () => this.setState({ faqOn: !faqOn }) }
               />
             </PanelRow>
             <PanelRow>
               <ToggleControl
                 label="Témoignages"
                 checked={ quoteOn }
                 onChange={ () => this.setState({ quoteOn: !quoteOn }) }
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
 