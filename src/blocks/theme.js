import quoteSlider from './quotesSliderBlock/theme';
import faqBlock from './faq-block-grid/theme';
import queryFilters from './query-filters/theme';


export default {
  init() {
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
