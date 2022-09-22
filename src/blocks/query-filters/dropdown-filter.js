
const initToggleDropdown = ( blockQueryFilters ) => {

  document.addEventListener(  'click', (e) => closeDropdownOnOutside(e, blockQueryFilters) );
  // blockQueryFilters?.querySelectorAll('.goldencat-query-filters-select-label')?.forEach((el) => el.addEventListener( 'click', toggleDropdown));
}

const toggleDropdown = (e) => {
  if (e.currentTarget) {
    clearDropdown()
    const select = e.currentTarget;
    select.classList.toggle('show-dropdown');
  }
}

/**
 * Clear DropDown if we click outside
 */ 
const closeDropdownOnOutside = (e, blockQueryFilters) => {

  if (!$(e.target).closest('.query-filters-terms-wrapper').length) {
    clearDropdown();
  } else {
    if (e.target.classList.contains('query-taxonomy-label')) {
      if (e.target.parentNode.classList.contains('show-dropdown')) {
        e.target.parentNode.classList.remove('show-dropdown')
      } else {
        clearDropdown()
        e.target.parentNode.classList.add('show-dropdown')
      }
    }
  }  
} 

/**
 * Clear dropDown
 */

const clearDropdown = (blockQueryFilters) => {
  document.querySelectorAll('.goldencat-query-filters-select-label.show-dropdown')?.forEach((el) => {
    el.classList.remove('show-dropdown');
  })
}

export default initToggleDropdown;
