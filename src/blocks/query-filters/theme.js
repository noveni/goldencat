import initToggleDropdown from './dropdown-filter';

const spinner = (active, queryFiltersBlock) => {
  const spinner = queryFiltersBlock.querySelector( '.components-spinner' );
  if ( !spinner ) {
    return;
  }
  if ( active ) {
    spinner.classList.add( 'active');
  } else {
    spinner.classList.remove( 'active');
  }
}

const loadingPost = ( blockQuery ) => {
  const posts = blockQuery.querySelectorAll( '.wp-block-post' );
  if ( !posts ) {
    return;
  }
  posts.forEach( post => {
    post.classList.add( 'loading');
  })
}

const displayCount = (count, taxonomy, queryFiltersBlock) => {
  const dropDownGroup = queryFiltersBlock.querySelector(`.theme-multiple-select[data-taxonomy_slug=${taxonomy}]`);
  const label = dropDownGroup.querySelector('.query-taxonomy-label .count');

  if ( label ) {
    label.innerHTML = count > 0 ? `&nbsp;(${count})` : '';
  }

}

const formatTermsPayload = (taxonomiesWithTerms) => {
  return Array.prototype.reduce.call(taxonomiesWithTerms, (accumulator, { slug, terms }) => {
    if ( terms.length > 0 ) {
      accumulator[ slug ] = terms.map(({id}) => parseInt(id));
    }
    return accumulator;
  }, {});

}

const ajaxFiltering = ( { blockQueryFilters, attributes, blockQueryAttributes }) => {

  const blockQuery = blockQueryFilters.closest( '.wp-block-query' );
  const blockPostTemplate = blockQuery.querySelector( '.wp-block-post-template' );
  const blockQueryPagination = blockQuery.querySelector( '.wp-block-query-pagination' );


  let taxonomiesWithTermsFilled;
  const cleanerBtn = blockQueryFilters.querySelector('.query-filter-clear');
  if ( cleanerBtn ) {
    cleanerBtn.addEventListener( 'click', () => { 
      blockQueryFilters.querySelectorAll(`input:checked`).forEach(el => el.checked = false);
      checkFilter(true)
    })
  }

  const filtersTaxonomiesAttributes = attributes?.filters_taxonomies;

  const getAllCheckedValues = () => {
    let checked;
    checked = blockQueryFilters.querySelectorAll(`input:checked`);
    return Array.prototype.map.call(checked, (e) => {
      return {
        id: e.value,
        term_slug: e.dataset.slug,
        taxonomy: e.dataset.taxonomy
      }
    });
  }

  const runCheckFilter = (e) => {
    checkFilter(true);
  }

  const checkFilter = (runAjax) => {

    let checkedItems = getAllCheckedValues();
    // Hide the CleanerBtn All Btn
    if ( ! checkedItems?.length ) {
      cleanerBtn.classList.add('hide');
    } else {
      cleanerBtn.classList.remove('hide');      
    }


    taxonomiesWithTermsFilled = filtersTaxonomiesAttributes.map( (taxonomyAttribute) => {
      taxonomyAttribute['terms'] = checkedItems.filter(({taxonomy}) => taxonomy === taxonomyAttribute.slug )
      return taxonomyAttribute;
    });
    taxonomiesWithTermsFilled.forEach((taxonomy) => {
      displayCount( taxonomy.terms.length, taxonomy.slug, blockQueryFilters);
    })

    if ( runAjax ) {
      filterPosts(taxonomiesWithTermsFilled)
    }
  }

  const filterPosts = (taxonomiesWithTerms) => {
    
    const ajaxFormattedTerms = formatTermsPayload(taxonomiesWithTerms);

    spinner(true, blockQueryFilters);
    loadingPost(blockQuery);

    jQuery.ajax({
      type: 'POST',
      url: attributes?.filters_ajax_url,
      dataType: 'json',
      data: {
        action: 'goldencat_block_query_filters_action',
        terms: ajaxFormattedTerms,
        attrs: blockQuery.dataset.attrs
      },
      success: function(res) {
        spinner(false, blockQueryFilters);
        renderAjaxPosts(res.html)
      },
      error: function(err) {
        console.error(err);
      }
    })
  } ;

  const renderAjaxPosts = (responseHtml) => {
    const htmlEl = jQuery( responseHtml );

    if ( htmlEl.length ) {
      const responseBlockTemplate = htmlEl.find( '.wp-block-post-template' );
      responseBlockTemplate.find( '.wp-block-post' ).addClass('initial-load');
      const html =  responseBlockTemplate.html() || '';
      const htmlPagination = htmlEl.find( '.wp-block-query-pagination' ).html() || '';

      if ( html.length ) {
        blockPostTemplate.innerHTML = html;
        blockPostTemplate.setAttribute( 'class', responseBlockTemplate.attr("class") )
        setTimeout(() => {
          blockPostTemplate.querySelectorAll('.wp-block-post.initial-load')?.forEach((post) => {
            post.classList.remove('initial-load')
          })
        }, 500);
      }
      if ( blockQuery.querySelector( '.wp-block-query-pagination' )) {
        blockQuery.querySelector( '.wp-block-query-pagination' ).innerHTML = htmlPagination;
        blockQuery.querySelector( '.wp-block-query-pagination' ).setAttribute('class',  htmlEl.find( '.wp-block-query-pagination' ).attr('class'))
      } else if (htmlPagination) {
        let div = document.createElement("div");
        div.classList.add('wp-block-query-pagination');
        blockPostTemplate.after(div);
        div.outerHTML = htmlEl.find( '.wp-block-query-pagination' ).prop('outerHTML');
      }

      initPaginationEvents();
      return;
    }

  }

  const initPaginationEvents = () => {
    const pagination = blockQuery.querySelector('.wp-block-query-pagination');
    if ( pagination ) {
      pagination.querySelectorAll('a')?.forEach(el => el.addEventListener( 'click', runPagination))
    }
  }

  const runPagination = (e) => {
    e.preventDefault();

    checkFilter(false);
    if (e.target) {
      const destination = e.target.href;
      const paramString = destination.split('?')[1];
      runAjaxPaginateAndFilterPosts(taxonomiesWithTermsFilled, paramString)
    }
  }

  const runAjaxPaginateAndFilterPosts = (taxonomiesWithTerms, urlParamString) => {

    const ajaxFormattedTerms = formatTermsPayload(taxonomiesWithTerms);

    spinner(true, blockQueryFilters);
    loadingPost(blockQuery);

    if (!attributes) {
      return;
    }

    jQuery.ajax({
      type: 'POST',
      url: `${attributes.filters_ajax_url}?${urlParamString}`,
      dataType: 'json',
      data: {
        action: 'goldencat_block_query_filters_action',
        terms: ajaxFormattedTerms,
        attrs: blockQuery.dataset.attrs,
      },
      success: function(res) {
        spinner(false, blockQueryFilters);
        renderAjaxPosts(res.html)
      },
      error: function(err) {
        console.error(err);
      }
    });

  }

  initPaginationEvents();
  const checkbox = blockQueryFilters.querySelectorAll('input[type=checkbox]');
  if ( checkbox ) {
    checkbox.forEach(el => el.addEventListener( 'click', runCheckFilter));
    // Update and check dropdown filter if query with taxQuery, don't run ajax at init
    checkFilter(false);
  }
}


export default {
  init() {

    
    const queryFiltersBlock = document.querySelector('.wp-block-goldencat-query-filters');
    if ( queryFiltersBlock && block_goldencat_query_filters_store && block_goldencat_core_query_store ) {
      ajaxFiltering( { 
        blockQueryFilters: queryFiltersBlock, 
        attributes: block_goldencat_query_filters_store, 
        blockQueryAttributes: block_goldencat_core_query_store.blockAttributes 
      } );
    }

    initToggleDropdown( queryFiltersBlock );
   
  },
  finalize() {
  },
};
