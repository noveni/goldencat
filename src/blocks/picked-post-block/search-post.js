import { useState, RawHTML, useEffect, useRef } from '@wordpress/element';
import { FormTokenField } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { find } from 'lodash';

const SearchPost = ( props ) => {

  const {
    postType,
    setPostId
  } = props

  const [ tokens, setTokens ] = useState( [] );
  const [ availablePosts, setAvailablePosts ] = useState( [] );

  const isStillMounted = useRef();

  const postTitleToIds = ( posts, availablePosts ) => {
    return posts
      .map( ( postTitle ) =>
        find( availablePosts, ( post ) => post.title.rendered === postTitle ).id
      );
  };

  const savePost = ( posts ) => {
    setTokens(posts)
    const ids = postTitleToIds(posts, availablePosts);
    setPostId(ids[0]);
  }

  const searchPosts = (searchValue) => {
    const query = {
			per_page: -1,
			type: postType,
			search: searchValue,
		};

		apiFetch( { 
      path: addQueryArgs( `/wp/v2/${ postType }`, query ),
      } )
			.then( ( posts ) => {
        const newAvailablePosts = availablePosts.concat(
            posts.filter( ( post ) => ! find( availablePosts, ( availablePost ) => availablePost.id === post.id ) ))
        setAvailablePosts( newAvailablePosts );
			} )
      .catch( () => {
					setAvailablePosts( [] );
			} );
  }
  
  let suggestions = [];
  if ( availablePosts.length ) {
    suggestions = availablePosts.map( (post) => {
      return post.title.rendered
    } );
  }

  return <FormTokenField 
			value={ tokens } 
			suggestions={ suggestions } 
			onChange={ tokens => savePost( tokens ) }
			onInputChange={ (value) => searchPosts( value ) }
			placeholder="Type a post title" 
		/>;
}

export default SearchPost;
