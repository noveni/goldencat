/**
 * WordPress dependencies
 */
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

const Save = ( props ) => {
  const {
		attributes: {
		  mediaID,
		  mediaURL,
		  focalPoint,
		  mediaSizes,
		  mediaMaxWidth,
		  mediaMaxWidthUnit
		},
	} = props;

  function mediaPosition( { x, y }, width, height ) {
    return { 
      top: `calc(${ Math.round( y * 100 ) }% - ${height / 2}px)`, 
      left: `calc(${ Math.round( x * 100 ) }% - ${width / 2}px)` 
    }
    // return `${ Math.round( x * 100 ) }% ${ Math.round( y * 100 ) }%`;
  }

  const mediaStyle = {
    ...( focalPoint && mediaSizes
      ? mediaPosition(focalPoint, mediaSizes.width, mediaSizes.height)
      : {} ),
    // ...( mediaURL ? { backgroundImage: `url(${ mediaURL })` } : {} ),
    ...( mediaMaxWidth && mediaMaxWidthUnit
      ? { width: `${mediaMaxWidth}${mediaMaxWidthUnit}`}
      : {} )
};

  const objectPosition =
		// prettier-ignore
		focalPoint && `${ Math.round( focalPoint.x * 100 ) }% ${ Math.round( focalPoint.y * 100 ) }%`

  const blockProps = useBlockProps.save();
  return (
    <div { ...blockProps }>
      <img
        className='block-wrapper-img__element'
        src={ mediaURL }
        style={ mediaStyle }
      />
      <InnerBlocks.Content />
    </div>
  );
};
export default Save;
