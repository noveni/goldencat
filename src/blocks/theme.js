import coverSlider from './cover-slider-block/theme';
import quoteSlider from './quotesSliderBlock/theme';
import faqBlock from './faq-block-grid/theme';


// eslint-disable-next-line no-unused-vars
export default {
  init() {
    coverSlider.init();
    quoteSlider.init();
    faqBlock.init();
  },
  finalize() {
    quoteSlider.finalize();
    faqBlock.finalize();
  },
};
