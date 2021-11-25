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
	closeSmall,
	chevronLeft,
	chevronRight,
	edit,
	image as imageIcon,
} from '@wordpress/icons';

export const SliderImage = ( props ) => {
  const {
    url,
    id,
    isFirstItem,
    isLastItem,
    isSelected,
    onSelect,
    onDeselect,
    onRemove,
    onMoveBackward,
    onMoveForward
  } = props;
  
  const className = classnames( {
    'block-cover-slider-image': true,
    'is-selected': isSelected,
  } );

  return (
    <div className={className}>
      <figure>
        <img
          src={url}
          data-id={ id }
          onClick={ onSelect }
          onFocus={ onSelect }
          tabIndex="0"
        />
        <ButtonGroup className="block-cover-slider-image__inline-menu is-left">
					<Button
						icon={ chevronLeft }
						onClick={ isFirstItem ? undefined : onMoveBackward }
						label={ __( 'Move image backward' ) }
						aria-disabled={ isFirstItem }
						disabled={ ! isSelected }
					/>
					<Button
						icon={ chevronRight }
						onClick={ isLastItem ? undefined : onMoveForward }
						label={ __( 'Move image forward' ) }
						aria-disabled={ isLastItem }
						disabled={ ! isSelected }
					/>
				</ButtonGroup>
				<ButtonGroup className="block-cover-slider-image__inline-menu is-right">
					<Button
						icon={ closeSmall }
						onClick={ onRemove }
						label={ __( 'Remove image' ) }
						disabled={ ! isSelected }
					/>
				</ButtonGroup>
      </figure>
    </div>
  )
}

export default SliderImage;
