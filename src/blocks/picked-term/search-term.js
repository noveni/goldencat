import { useState, RawHTML, useEffect, useRef } from '@wordpress/element';
import { FormTokenField } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { find } from 'lodash';

const SearchTerm = ( props ) => {

  const {
    taxonomy,
    setTermId
  } = props

  const [ tokens, setTokens ] = useState( [] );
  const [ availableTerms, setAvailableTerms ] = useState( [] );

  const isStillMounted = useRef();

  const termNameToIds = ( terms, availableTerms ) => {
    return terms
      .map( ( termTitle ) =>
        find( availableTerms, ( term ) => term.name === termTitle ).id
      );
  };

  const saveTerm = ( terms ) => {
    setTokens(terms)
    const ids = termNameToIds(terms, availableTerms);
    setTermId(ids[0]);
  }

  const searchTerms = (searchValue) => {
    const query = {
			taxonomy: taxonomy,
			search: searchValue,
		};

		apiFetch( { 
      path: addQueryArgs( `/wp/v2/${ taxonomy }`, query ),
      } )
			.then( ( terms ) => {
        const newAvailableTerms = availableTerms.concat(
            terms.filter( ( term ) => ! find( availableTerms, ( availableTerm ) => availableTerm.id === term.id ) ))
            setAvailableTerms( newAvailableTerms );
			} )
      .catch( () => {
					setAvailablePosts( [] );
			} );
  }
  
  let suggestions = [];
  if ( availableTerms.length ) {
    suggestions = availableTerms.map( (post) => {
      return post.name
    } );
  }

  return <FormTokenField 
			value={ tokens } 
			suggestions={ suggestions } 
			onChange={ tokens => saveTerm( tokens ) }
			onInputChange={ (value) => searchTerms( value ) }
			placeholder="Type a term title" 
		/>;
}

export default SearchTerm;
