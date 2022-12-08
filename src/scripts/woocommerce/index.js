import { goldencatDomReady } from '../utils/dom';

import Router from '../utils/Router';

import woocommerceCheckout from './routes/checkout';
import singleProduct from './routes/single-product';

const routes = new Router({
  woocommerceCheckout,
  singleProduct,
});

goldencatDomReady( () => routes.loadEvents());
