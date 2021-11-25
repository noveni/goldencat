/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { Button, Spinner, ButtonGroup } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import {
	chevronLeft,
	chevronRight,
} from '@wordpress/icons';
import { useState } from '@wordpress/element';


import SliderImage from './slider-image';

export const Slider = ( props ) => {
  const {
    isSelected,
    isEditingSlider,
    images,
    selectedImage,
    onMoveBackward,
		onMoveForward,
		onRemoveImage,
		onSelectImage,
		onDeselectImage,
    mediaPlaceholder,
  } = props;


  const [ translateX, setTranslateX ] = useState( 0 );
    
  const className = classnames( {
    'block-cover-slider-gallery': true,
    'is-selected': isSelected,
    'is-editing-slider': isEditingSlider,
  } );


  const slideLeft = () => {
    if (Math.abs(translateX - 1) === images.length) {
      setTranslateX(0)
    } else {
      setTranslateX(translateX - 1)
    }
  }
  const slideRight = () => {
    if (translateX + 1 === 1) {
      setTranslateX((images.length - 1) * -1)
    } else {
      let newTranslateX = translateX;
      setTranslateX(newTranslateX + 1)
  }
  }

  let style = {
    transform: `translateX(${translateX * 100}%)`
  };

  if (isEditingSlider) {
    style.transform = false;
  }

  return (
    <>
      { isSelected && !isEditingSlider &&  (
        <>
          <ButtonGroup className="block-cover-slider-arrow__inline-menu is-left">
            <Button
              icon={ chevronLeft }
              label={ __( 'View image backward' ) }
              onClick={slideLeft}
            />
          </ButtonGroup>
          <ButtonGroup className="block-cover-slider-arrow__inline-menu is-right">
            <Button
              icon={ chevronRight }
              label={ __( 'View image forward' ) }
              onClick={slideRight}
            />
          </ButtonGroup>
        </>
      )}
      <ul 
        className={className} 
        style={style}
      >
        { images.map( ( img, index ) => { 
          return (
            <li className={ img.id || img.url }>
              <SliderImage
                url={img.url}
                id={img.id }
                isFirstItem={ index === 0 }
                isLastItem={ index + 1 === images.length }
                isSelected={
                  isSelected && isEditingSlider && selectedImage === index
                }
                onMoveBackward={ onMoveBackward( index ) }
                onMoveForward={ onMoveForward( index ) }
                onRemove={ onRemoveImage( index ) }
                onSelect={ onSelectImage( index ) }
                onDeselect={ onDeselectImage( index ) }
              />
            </li>
          )
        })}
        {isEditingSlider && ( 
          <li>{ mediaPlaceholder }</li>
        )}
      </ul>
    </>
  )
}
export default Slider;
