import $ from 'jquery';

export default {
  init() {
    $('body').on('update_checkout', function(){
      $( '.goldencat-woocommerce-shipping-totals' ).block({
        message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
      
    });
    $('body').on('updated_checkout', function(){
      $( '.goldencat-woocommerce-shipping-totals' ).unblock();
      
    });
  },
  finalize() {},
};
