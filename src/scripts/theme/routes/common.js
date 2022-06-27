
import header from '../elements/header';
import { toggleMenu } from '../elements/toggle-menu';
import headerCart from '../elements/header-cart';
import Animations from '../elements/animations';
import SocialSharing from '../elements/social-share';

export default {
  init() {
    document.body.classList.remove('no-js');
    header.init();
    toggleMenu('primary');
    toggleMenu('search');

    headerCart.init();
    Animations.init();
    SocialSharing.init();
  },
	finalize() {
    header.finalize();
    headerCart.finalize();
    Animations.finalize();
    SocialSharing.finalize();
  }
}
