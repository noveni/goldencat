// import domReady from '@wordpress/dom-ready';

import './assets';
import { goldencatDomReady } from '../utils/dom';

import Router from '../utils/Router';

import common from './routes/common';
// import home from './routes/home'
import blocks from '../../blocks/theme';
const routes = new Router({
  // All pages
  common,
  blocks,
  // Home page
  // home,
});

goldencatDomReady( () => routes.loadEvents());
