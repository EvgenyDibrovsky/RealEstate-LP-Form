import mobileMenu from './modules/mobileMenu';
import initSwiper from './modules/swiper';
import { initAnimateElements } from './modules/animationElements';
import backToTop from './modules/backToTop';
import contactFormDhow from './modules/contactFormShow';
import privacypolicy from './modules/privacy-policy';
import '../main.css';
require('fslightbox');
document.addEventListener('DOMContentLoaded', () => {
  mobileMenu();
  initSwiper();
  initAnimateElements();
  backToTop();
  contactFormDhow();
  privacypolicy();
});

if (import.meta.webpackHot) {
  import.meta.webpackHot.accept();
}
