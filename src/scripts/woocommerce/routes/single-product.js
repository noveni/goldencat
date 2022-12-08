import $ from 'jquery';
const addToggleToMessageCheckbox = () => {
  const checkboxGroup = document.querySelector('.wc-pao-addon-message-check .wc-pao-addon-checkbox');
  const messageGroup = document.querySelector('.wc-pao-addon-message'),
    messageInput = messageGroup.querySelector('.input-text');


  if (checkboxGroup) {

    checkboxGroup.addEventListener('change', (e) => {
      messageGroup.classList.toggle('active');

      if (messageInput) {
        if (messageGroup.classList.contains('active')) {
          messageInput.focus();
        } else {
          messageInput.blur();
        }
      }
    })
  }
}


export default {
  init() {
    // JavaScript to be fired on the product, after the init JS
    if (document.querySelector('.wc-pao-addon-message-check')) {
      addToggleToMessageCheckbox();
    }

    $( ".variations_form" ).on('found_variation', function(form, variation) {
      if ( variation.price_html.length) {
        $('.product_title + .price').html(variation.price_html);
      }
    });
    $('body').on('woocommerce-product-addons-update', function () {
      const price = document.querySelector('#product-addons-total .product-addon-totals .wc-pao-subtotal-line .amount');
      if (price) {
        const newPrice = price.cloneNode(true)
        $('.product_title + .price').html( newPrice );
      }
    })

    // Handle Custom swatch button 
    $( '.variations_form' ).on( 'click', '.generatedRadios', function ( e ) {
      // clicked swatch
      const el = $( this );
      // original select dropdown with variations
      const select = el.closest( '.value' ).find( 'select' );
      // this specific term slug, i.e color slugs, like "coral", "grey" etc
      const value = el.data( 'value' );
    
      // do three things
      el.addClass( 'selected' ).siblings( '.selected' ).removeClass( 'selected' );
      select.val( value );
      select.change();
    } );
  },
  finalize() {
  }
}

