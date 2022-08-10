/**
 * WordPress dependencies
 */
 import { __ } from '@wordpress/i18n';
 import { useSelect } from '@wordpress/data';
 import { useEntityProp } from '@wordpress/core-data';
 import { registerPlugin } from '@wordpress/plugins';
 import { PluginPostStatusInfo } from '@wordpress/edit-post';
 import { PostTypeSupportCheck } from '@wordpress/editor';
 import { CheckboxControl } from '@wordpress/components';
 import { useEffect } from '@wordpress/element';
 
 const disableTitleSettingPanel = () => {
 
	 const metaKey = '_goldencat_title_hidden';
 
	 const postInfo = useSelect(
		 ( select ) => {
			 const postType = select( 'core/editor' ).getCurrentPostType();
			 return {
				 postType
			 }
		 },
		 []
	 );
 
	 const {
		 postType
	 } = postInfo;
 
	 const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );
 
	 useEffect( () => {
			 const titleBlock = document.querySelector( '.editor-post-title' );

			 if ( titleBlock && meta[metaKey] == true ) {
				 document.body.classList.add( 'goldencat-title-hidden' );
			 } else {
				 document.body.classList.remove( 'goldencat-title-hidden' );
			 }
		 },
		 [
			 meta
		 ]
	 );
 
	 const isHidden = meta[metaKey];
	 const updateMetaValue = ( newValue ) => {
		 setMeta( { ...meta, [ metaKey ]: newValue } );
	 };
 
	 return (
		 <PluginPostStatusInfo>
			 <PostTypeSupportCheck supportKeys="title">
				 <CheckboxControl
					 className="goldencat-hide-title-label"
					 label={ __( 'Cacher le titre', 'goldencat' ) }
					 help={ isHidden ? __( 'Le titre est masqué sur votre site Web.', 'goldencat' ) : null }
					 checked={ isHidden }
					 onChange={ updateMetaValue }
				 />
			 </PostTypeSupportCheck>
		 </PluginPostStatusInfo>
	 )
 }
 
 registerPlugin( 'goldencat-disable-title', {
	 render: disableTitleSettingPanel,
	 icon: false
 } );
 