/**
 * External dependencies
 */
import { get, invoke, isUndefined, pickBy } from 'lodash';

/**
 * WordPress dependencies
 */
import { RawHTML } from '@wordpress/element';
import {
	PanelBody,
	Placeholder,
	QueryControls,
	Spinner,
} from '@wordpress/components';

import { __, sprintf } from '@wordpress/i18n';

import {
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { pin } from '@wordpress/icons';


/**
 * Module Constants
 */

export default function QuotePostsEdit( { isSelected, attributes, setAttributes, className } ) {
	const {
    postType,
		postsToShow,
	} = attributes;

  const {
		quotePosts,
	} = useSelect(
		( select ) => {
			const { getEntityRecords } = select( 'core' );
			const postsQuery = pickBy(
				{
					per_page: postsToShow,
				},
				( value ) => ! isUndefined( value )
			);

			const posts = getEntityRecords(
				'postType',
				postType,
				postsQuery
			);

			return {
				quotePosts: posts
			};
		},
		[
      postType,
			postsToShow,
		]
	);


  const inspectorControls = (
		<InspectorControls>

			<PanelBody title={ __( 'Sorting and filtering' ) }>
				<QueryControls
					numberOfItems={ postsToShow }
					onNumberOfItemsChange={ ( value ) =>
						setAttributes( { postsToShow: value } )
					}
				/>
			</PanelBody>
		</InspectorControls>
	);

  const blockProps = useBlockProps( {
		className: className
	} );

	const hasPosts = Array.isArray( quotePosts ) && quotePosts.length;
	if ( ! hasPosts ) {
		return (
			<div { ...blockProps }>
				{ inspectorControls }
				<Placeholder icon={ pin } label={ __( 'Latest Posts' ) }>
					{ ! Array.isArray( quotePosts ) ? (
						<Spinner />
					) : (
						__( 'No posts found.' )
					) }
				</Placeholder>
			</div>
		);
	}

	// Removing posts from display should be instant.
	let displayPosts =
		quotePosts.length > postsToShow
			? quotePosts.slice( 0, postsToShow )
			: quotePosts;

	let unShowPost = 0;
	if (!isSelected && displayPosts.length > 1 ) {
		unShowPost = displayPosts.length - 1;
		displayPosts = displayPosts.slice(0, 1);
	}

  return (
		<div { ...blockProps }>
			{ inspectorControls }
			<div className="quotes-slider">
				{ displayPosts.map( ( post, i ) => {

					return (
						<div key={ i } className="glide__slide has-text-align-center">
              <div className="quote-content h3">
                <RawHTML key="html">
                  { post.content.raw.trim() }
                </RawHTML>
              </div>
              <p className="quote-customer-name is-sous-titre has-moutarde-color has-text-color"><strong>{ post.meta._goldencat_quotes_user_name }</strong></p>
              
						</div>
					);
				} ) }
			</div>
			{!isSelected &&
				<p className="has-text-align-center"><em> + {unShowPost} témoignage.</em>  Cliquer pour afficher.</p>
			}
		</div>
	);
}
