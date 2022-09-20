import coverSlider from './cover-slider-block/theme';
import quoteSlider from './quotesSliderBlock/theme';
import faqBlock from './faq-block-grid/theme';
import queryFilters from './query-filters/theme';


// eslint-disable-next-line no-unused-vars
export default {
  init() {
    coverSlider.init();
    quoteSlider.init();
    faqBlock.init();
    queryFilters.init();
  },
  finalize() {
    quoteSlider.finalize();
    faqBlock.finalize();
    queryFilters.finalize();
  },
};
