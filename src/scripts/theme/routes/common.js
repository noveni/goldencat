
import header from '../elements/header';
import { toggleMenu } from '../elements/toggle-menu';
import headerCart from '../elements/header-cart';

export default {
  init() {
    document.body.classList.remove('no-js');
    header.init();
    toggleMenu('primary');
    toggleMenu('search');

    headerCart.init();
  },
	finalize() {
    header.finalize();
    headerCart.finalize();
  }
}
