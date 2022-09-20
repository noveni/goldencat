/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { 
  TextControl,
  ColorPalette
} from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';
import { useEffect } from '@wordpress/element';
import { useSetting, getColorObjectByColorValue, getColorObjectByAttributeValues, getColorClassName } from '@wordpress/block-editor';


const addBackgroundColorSettingPanel = () => {
  // Get post type.
  const postType = useSelect(
    ( select ) => select( 'core/editor' ).getCurrentPostType(),
    []
  );

  const colors = useSetting('color.palette');

  // Get the value of meta and a function for updating meta from useEntityProp.
  const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

  // Define which post meta key to read from/save to.
  const metaKey = '_goldencat_bg_color';

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

  const setColor = ( key, value, colorsArray ) => {
    if ( value ) {
      const colorObject = getColorObjectByColorValue(colorsArray, value);
      setPostMeta( key, colorObject.slug );
    } else {
      setPostMeta( key, '' );
    }
  }

  const getColor = ( key, colorsArray) => {
    const colorSlug = getPostMeta( metaKey );
    const colorObject = getColorObjectByAttributeValues(colorsArray, colorSlug, '');
    return colorObject.color;
  }

  useEffect( () => {
    const colorObject = getColorObjectByAttributeValues(colors, meta[metaKey], false);
    const contentContainer = document.querySelector( '.is-root-container' );


    if ( contentContainer && meta[metaKey] ) {
      contentContainer.style.backgroundColor = colorObject.color;
    } else if (contentContainer) {
      contentContainer.style.backgroundColor = 'rgb(255, 255, 255)';
    }
  },
  [ meta, colors ]
);
  
  return (
    <PluginDocumentSettingPanel
			name="goldencat-bg-color"
			title={ __( 'Page Background-Color', 'goldencat' ) }
			className="goldencat-bg-color"
		>
      <ColorPalette
        __experimentalIsRenderedInSidebar
        disableCustomColors={true}
        colors={ colors }
        value={ getColor( metaKey, colors) }
        onChange={ ( value ) => setColor( metaKey, value, colors ) }
      />
    </PluginDocumentSettingPanel>
	);
}

registerPlugin( 'goldencat-bg-color', {
	render: addBackgroundColorSettingPanel,
  icon: false,
} );
