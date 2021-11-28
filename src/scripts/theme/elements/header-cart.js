


export default {
  init() {
   
    localStorage
    const headerCartButton = document.querySelector('.site-header-cart');
    if (headerCartButton) {
      
      const cart = headerCartButton.querySelector('.widget_shopping_cart');
      if (cart) {
        cart.setAttribute( 'aria-expanded', 'true' );
        headerCartButton.addEventListener('mouseenter', () => {
          cart.setAttribute( 'aria-expanded', 'true' );
          headerCartButton.classList.add( 'hover-cart' );
        })
        headerCartButton.addEventListener('mouseleave', () => {
          cart.setAttribute( 'aria-expanded', 'false' );
          headerCartButton.classList.remove( 'hover-cart' );
        })
      }
    }
  },
	finalize() {

    // document.getElementById( 'site-navigation' ).querySelectorAll( '.menu-wrapper > .menu-item-has-children' ).forEach( function( li ) {
		// 	li.addEventListener( 'mouseenter', function() {
		// 		this.querySelector( '.sub-menu' ).setAttribute( 'aria-expanded', 'true' );
		// 		submenuPosition( li );
		// 	} );
		// 	li.addEventListener( 'mouseleave', function() {
		// 		this.querySelector( '.sub-menu' ).setAttribute( 'aria-expanded', 'false' );
		// 	} );
		// } );
  }
}
