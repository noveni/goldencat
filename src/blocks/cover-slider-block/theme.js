import Glide, { Breakpoints } from '@glidejs/glide'

// eslint-disable-next-line no-unused-vars
export default {
  init() {
    if (document.querySelector('.block-cover-slider-gallery')) {
      new Glide('.block-cover-slider-gallery', {
        type: 'carousel',
        startAt: 0,
        perView: 1,
        animationDuration: 1000,
        hoverpause: true,
        autoplay: 4000
      }).mount();
    }
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
