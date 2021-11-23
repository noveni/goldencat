
import header from '../elements/header';
import { toggleMenu } from '../elements/toggle-menu';

export default {
  init() {
    header.init();
    toggleMenu('primary');
    toggleMenu('search');
  },
	finalize() {
    header.finalize();
  }
}
