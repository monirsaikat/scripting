import { qsa, qs } from './dom.js';

export function modal(selector) {
  const element = qs(selector);

  if (!element || !window.bootstrap || !window.bootstrap.Modal) {
    return null;
  }

  return window.bootstrap.Modal.getOrCreateInstance(element);
}

export function showModal(selector) {
  const instance = modal(selector);

  if (instance) {
    instance.show();
  }
}

export function hideModal(selector) {
  const instance = modal(selector);

  if (instance) {
    instance.hide();
  }
}

export function cleanupModals() {
  qsa('.modal-backdrop').forEach(function (backdrop) {
    backdrop.remove();
  });

  document.body.classList.remove('modal-open');
  document.body.style.removeProperty('overflow');
  document.body.style.removeProperty('padding-right');
}
