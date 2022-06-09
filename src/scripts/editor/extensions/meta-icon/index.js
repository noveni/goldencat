/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
  Button,
} from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';

const ALLOWED_MEDIA_TYPES = [ 'image' ];


const metaIconSettingPanel = () => {

  // Define which post meta key to read from/save to.
	const metaKey = '_goldencat_post_icon';
  
  // Get post type.
	const postInfo = useSelect(
		( select ) => {
      const postType = select( 'core/editor' ).getCurrentPostType();
      const postMeta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
      const { getMedia } = select( 'core' );
      
      const mediaId = postMeta[ metaKey ] || false;

      return {
        iconMedia: mediaId 
          ? getMedia( mediaId , { context: 'view' } )
          : null,
        postType,
        iconId: mediaId
      }
    },
		[
      metaKey
    ]
	);
  
  if ( [ 'wp_block' ].includes( postInfo.postType ) ) {
    return false;
  }

  if ( ![ 'gc_formation', 'post', 'page' ].includes( postInfo.postType ) ) {
    return false;
  }

	/**
	 * A helper function for getting post meta by key.
	 *
	 * @param {string} key - The meta key to read.
	 * @return {*} - Meta value.
	 */
	const getPostMeta = ( key ) => meta[ key ] || '';

  /**
	 * A helper function for updating post meta that accepts a meta key and meta
	 * value rather than entirely new meta object.
	 *
	 * Important! Don't forget to register_post_meta (see ../index.php).
	 *
	 * @param {string} key   - The meta key to update.
	 * @param {*}      value - The meta value to update.
	 */
	const setPostMeta = ( key, value ) => {
    setMeta( {
      ...meta,
      [ key ]: value,
    } );
  }
  
  // Get the value of meta and a function for updating meta from useEntityProp.
  
  const {
    iconMedia,
    iconId,
    postType
  } = postInfo;
  
  const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

  return (
		<PluginDocumentSettingPanel
			name="goldencat-meta-icon"
			title={ __( 'Icone', 'goldencat' ) }
			className="goldencat-meta-icon"
		>
      <MediaUploadCheck>
        <MediaUpload
          onSelect={ ( media ) =>
            setPostMeta( metaKey, media.id )
          }
          allowedTypes={ ALLOWED_MEDIA_TYPES }
          value={ getPostMeta( metaKey ) }
          render={ ( { open } ) => (
            <div className="editor-post-featured-image__container">
              <Button
                className={
                  ! iconId
                    ? 'editor-post-featured-image__toggle'
                    : 'editor-post-featured-image__preview'
                }
                onClick={ open }
              >
                { !! iconId && iconMedia && (
                  <img
                    src={ iconMedia.source_url }
                    alt=""
                  />
                ) }
                { ! iconId && `Ajouter une icône` }
              </Button>

            </div>
          ) }
        />
      </MediaUploadCheck>
		</PluginDocumentSettingPanel>
	);
}

registerPlugin( 'goldencat-meta-icon', {
  render: metaIconSettingPanel,
  icon: false
} );
 