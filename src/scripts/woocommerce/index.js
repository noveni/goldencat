import { goldencatDomReady } from '../utils/dom';

import Router from '../utils/Router';

import woocommerceCheckout from './routes/checkout';

const routes = new Router({
  woocommerceCheckout,
});

goldencatDomReady( () => routes.loadEvents());
