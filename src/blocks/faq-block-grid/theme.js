
import faq from './faq';

const uncheckAll = (selector) => {
  let checked;
  checked = document.querySelectorAll(`${selector}:checked`).forEach(el => el.checked = false);
}

const filteringPosts = ( args ) => {
  const {
    postType,
    taxonomy,
    filterSelector,
    action,
  } = args

  const jsFilterSelector = `.${filterSelector}`;
  
  const termFilterCheckbox = document.querySelectorAll(jsFilterSelector);

  const getAllCheckedValues = () => {
    let checked;
    checked = document.querySelectorAll(`${jsFilterSelector}:checked`);
    return Array.prototype.map.call(checked, (e) => {
      return {
        id: e.value,
        slug: e.dataset.slug,
        term: e.dataset.taxonomy
      }
    });
  }

  const filterPosts = () => {
    

    const taxos = [taxonomy];
    const request = Array.prototype.reduce.call(document.querySelectorAll('.taxo-collecteur'), (carry, e) => {
      if (e.value == '') {
        return carry;
      }
      return { ...carry, [e.parentNode.dataset.taxonomy]: [...e.value.split(',')] }
    }, {});

    const postWrapper = document.querySelector('.filter-content-to-refresh');

    jQuery.ajax({
      type: 'POST',
      url: '/wp-admin/admin-ajax.php',
      dataType: 'json',
      data: {
        postType: postType,
        action: action,
        the_taxos: [...taxos],
        the_selected: request,
      },
      success: function(res) {
        postWrapper.innerHTML = res.html;
        faq.init();
        faq.finalize();
        // pageinateWrapper.innerHTML = res.pagination
      },
      error: function(err) {
        console.error(err);
      }
    })
  }

  const checkFilter = (e) => {
    if(e.currentTarget.dataset.slug === 'all') {
      uncheckAll(jsFilterSelector);
      e.currentTarget.checked = true;
    }

    let checkedItems = getAllCheckedValues();
    const simpleCheckedItems = checkedItems.map((chItem) => chItem.id);

    if (simpleCheckedItems.includes('all') && simpleCheckedItems.length > 1) {
      const all_check = document.querySelector(`${jsFilterSelector}[data-slug="all"]`);
      all_check.checked = false;
    }

    document.querySelectorAll('.taxo-collecteur').forEach(el => el.value = '') 

    checkedItems = getAllCheckedValues();
    // Filter all by Taxo
    const sortedItems = checkedItems.reduce((carry, item) => {
      let ids = [];
      if (carry[item.term]) {
        ids = carry[item.term];
      }
      return { ...carry, [item.term]: [...ids, item.id] }
    }, {});

    Object.entries(sortedItems).forEach(element => {
      document.getElementById(`collected-${element[0]}`).value = element[1].join();
    });

    filterPosts();
  }


  if (termFilterCheckbox) {
    termFilterCheckbox.forEach((cbx) => {
      cbx.addEventListener('click', checkFilter);
    })
  }
}

export default {
  init() {

    const faqFilter = document.querySelector('.goldencat-faq-filter');
    if (faqFilter) {
      filteringPosts( { 
        postType: faqFilter.dataset.postType, 
        taxonomy: faqFilter.dataset.taxonomy,
        filterSelector: faqFilter.dataset.selector,
        action: faqFilter.dataset.action
      } )
    }
  },
  finalize() {
  },
};
