
import header from '../elements/header';
import { toggleMenu } from '../elements/toggle-menu';
import headerCart from '../elements/header-cart';
import Animations from '../elements/animations';

export default {
  init() {
    document.body.classList.remove('no-js');
    header.init();
    toggleMenu('primary');
    toggleMenu('search');

    headerCart.init();
    Animations.init();
  },
	finalize() {
    header.finalize();
    headerCart.finalize();
    Animations.finalize();
  }
}
