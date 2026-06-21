import { cleanupModals } from './bootstrap-modals.js';

const DEFAULT_LINK_SELECTOR = [
  'a[href]',
  ':not([href^="#"])',
  ':not([target])',
  ':not([download])',
  ':not([up-follow=false])',
  ':not([data-no-up])'
].join('');

const DEFAULT_FORM_SELECTOR = [
  'form[method]',
  ':not([up-submit=false])',
  ':not([data-no-up])'
].join('');

export function configureUnpoly() {
  if (!window.up) {
    return;
  }

  pushUnique(up.link.config.followSelectors, DEFAULT_LINK_SELECTOR);
  pushUnique(up.form.config.submitSelectors, DEFAULT_FORM_SELECTOR);

  if (up.link.config.preloadSelectors) {
    pushUnique(up.link.config.preloadSelectors, 'a[href][data-up-preload]');
  }

  up.on('up:fragment:inserted', cleanupModals);
}

function pushUnique(list, value) {
  if (!list.includes(value)) {
    list.push(value);
  }
}
