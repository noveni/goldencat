import Glide, { Breakpoints } from '@glidejs/glide'

export default {
  init() {
    if (document.querySelector('.quotes-slider')) {
      new Glide('.quotes-slider', {
        type: 'carousel',
        startAt: 0,
        perView: 1,
        animationDuration: 500,
        hoverpause: true,
      }).mount();
    }
  },
  finalize() {
  },
};
