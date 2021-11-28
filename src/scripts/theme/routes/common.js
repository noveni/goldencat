
import header from '../elements/header';
import { toggleMenu } from '../elements/toggle-menu';
import headerCart from '../elements/header-cart';

export default {
  init() {
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
