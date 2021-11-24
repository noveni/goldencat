/**
 * File primary-navigation.js.
 *
 * Required to open and close the menu.
 */

/**
 * Toggle an attribute's value
 *
 * @param {Element} el - The element.
 * @param {boolean} withListeners - Whether we want to add/remove listeners or not.
 */
const toggleAriaExpanded = ( el, withListeners ) => {
	if ( 'true' !== el.getAttribute( 'aria-expanded' ) ) {
		el.setAttribute( 'aria-expanded', 'true' );
		submenuPosition( el.parentElement );
		if ( withListeners ) {
			// document.addEventListener( 'click', collapseMenuOnClickOutside );
		}
	} else {
		el.setAttribute( 'aria-expanded', 'false' );
		if ( withListeners ) {
			// document.removeEventListener( 'click', collapseMenuOnClickOutside );
		}
	}
}

// const collapseMenuOnClickOutside = ( event ) => {
// 	console.log(event.target, document.querySelector( '.goldencat-toggle-menu' ))
// 	console.log(! document.querySelector( '.goldencat-toggle-menu' ).contains( event.target ));
// 	if ( ! document.querySelector( '.goldencat-toggle-menu' ).contains( event.target ) ) {
// 		document.getElementById( 'site-navigation' ).querySelectorAll( '.sub-menu-toggle' ).forEach( function( button ) {
// 			button.setAttribute( 'aria-expanded', 'false' );
// 		} );
// 	}
// }

/**
 * Changes the position of submenus so they always fit the screen horizontally.
 *
 * @param {Element} li - The li element.
 */
function submenuPosition( li ) {
	var subMenu = li.querySelector( 'ul.sub-menu' ),
		rect,
		right,
		left,
		windowWidth;

	if ( ! subMenu ) {
		return;
	}

	rect = subMenu.getBoundingClientRect();
	right = Math.round( rect.right );
	left = Math.round( rect.left );
	windowWidth = Math.round( window.innerWidth );

	if ( right > windowWidth ) {
		subMenu.classList.add( 'submenu-reposition-right' );
	} else if ( document.body.classList.contains( 'rtl' ) && left < 0 ) {
		subMenu.classList.add( 'submenu-reposition-left' );
	}
}

/**
 * Handle clicks on submenu toggles.
 *
 * @param {Element} el - The element.
 */
const expandSubMenu = ( el ) => { // jshint ignore:line
	// Close other expanded items.
	el.closest( 'nav' ).querySelectorAll( '.sub-menu-toggle' ).forEach( function( button ) {
		if ( button !== el ) {
			button.setAttribute( 'aria-expanded', 'false' );
		}
	} );

	// Toggle aria-expanded on the button.
	toggleAriaExpanded( el, true );

	// On tab-away collapse the menu.
	el.parentNode.querySelectorAll( 'ul > li:last-child > a' ).forEach( function( linkEl ) {
		linkEl.addEventListener( 'blur', function( event ) {
			if ( ! el.parentNode.contains( event.relatedTarget ) ) {
				el.setAttribute( 'aria-expanded', 'false' );
			}
		} );
	} );
}

/**
* Menu Toggle Behaviors
*
* @param {string} id - The ID.
*/
export const toggleMenu = ( id ) => {
 	var wrapper = document.body, // this is the element to which a CSS class is added when a mobile nav menu is open
	 toggleMenu = document.getElementById( id + '-toggle-menu' );

 	if ( toggleMenu ) {
	 	toggleMenu.onclick = function() {
			wrapper.classList.toggle( id + '-toggle-menu-open' );
			wrapper.classList.toggle( 'lock-scrolling' );
			// toggleAriaExpanded( toggleMenu );
			if ( 'true' !== toggleMenu.getAttribute( 'aria-expanded' ) ) {
				toggleMenu.setAttribute( 'aria-expanded', 'true' );
				submenuPosition( toggleMenu.parentElement );
				// document.addEventListener(  'click', closeMenuOnClickOutside );
			} else {
				toggleMenu.setAttribute( 'aria-expanded', 'false' );
				// document.removeEventListener( 'click', closeMenuOnClickOutside );
			}
			toggleMenu.focus();
	 	};
 	}

	const closeMenuOnClickOutside = ( event ) => {
		// if ( toggleMenu.contains( event.target ) ) {
		// 	return;
		// }
		console.log(event.target, document.querySelector( '.' + id + '-menu-container' ))
		console.log(! document.querySelector( '.' + id + '-menu-container').contains( event.target ));
		if ( ! document.querySelector( '.' + id + '-menu-container').contains( event.target ) ) {
			wrapper.classList.toggle( id + '-toggle-menu-open' );
			wrapper.classList.toggle( 'lock-scrolling' );
			// document.getElementById( 'site-navigation' ).querySelectorAll( '.sub-menu-toggle' ).forEach( function( button ) {
			// 	button.setAttribute( 'aria-expanded', 'false' );
			// } );
		}
	}
 	/**
	* Trap keyboard navigation in the menu modal.
	* Adapted from TwentyTwenty
	*/
 	document.addEventListener( 'keydown', function( event ) {
		
		var modal, elements, selectors, lastEl, firstEl, activeEl, tabKey, shiftKey, escKey;
		if ( ! wrapper.classList.contains( id + '-toggle-menu-open' ) ) {
			return;
		}

		modal = document.querySelector( '.goldencat-toggle-menu' );
		selectors = 'input, a, button';
		elements = modal.querySelectorAll( selectors );
		elements = Array.prototype.slice.call( elements );
		tabKey = event.keyCode === 9;
		shiftKey = event.shiftKey;
		escKey = event.keyCode === 27;
		activeEl = document.activeElement; // eslint-disable-line @wordpress/no-global-active-element
		lastEl = elements[ elements.length - 1 ];
		firstEl = elements[0];

		if ( escKey ) {
			event.preventDefault();
			wrapper.classList.remove( id + '-toggle-menu-open', 'lock-scrolling' );
			toggleAriaExpanded( toggleMenu, true );
			toggleMenu.focus();
		}

		if ( ! shiftKey && tabKey && lastEl === activeEl ) {
			event.preventDefault();
			firstEl.focus();
		}

		if ( shiftKey && tabKey && firstEl === activeEl ) {
			event.preventDefault();
			lastEl.focus();
		}

		// If there are no elements in the menu, don't move the focus
		if ( tabKey && firstEl === lastEl ) {
			event.preventDefault();
		}
	} );

	/**
		* Close menu and scroll to anchor when an anchor link is clicked.
		* Adapted from TwentyTwenty.
		*/
	if (document.getElementById( 'site-navigation' )) {
		document.addEventListener( 'click', function( event ) {
			// If target onclick is <a> with # within the href attribute
			if ( event.target.hash && event.target.hash.includes( '#' ) ) {
				wrapper.classList.remove( id + '-navigation-open', 'lock-scrolling' );
				toggleAriaExpanded( toggleMenu );
				// Wait 550 and scroll to the anchor.
				setTimeout(function () {
					var anchor = document.getElementById(event.target.hash.slice(1));
					anchor.scrollIntoView();
				}, 550);
			}
		} );

		document.getElementById( 'site-navigation' ).querySelectorAll( '.menu-wrapper > .menu-item-has-children' ).forEach( function( li ) {
			li.addEventListener( 'mouseenter', function() {
				this.querySelector( '.sub-menu-toggle' ).setAttribute( 'aria-expanded', 'true' );
				submenuPosition( li );
			} );
			li.addEventListener( 'mouseleave', function() {
				this.querySelector( '.sub-menu-toggle' ).setAttribute( 'aria-expanded', 'false' );
			} );
		} );
	}

};
