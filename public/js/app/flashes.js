import { qsa } from './dom.js';

const DEFAULT_DELAY = 4000;

export function initFlashes() {
  scheduleAutoDismiss();

  if (window.up) {
    up.on('up:fragment:inserted', function (event) {
      scheduleAutoDismiss(event.target);
    });
  }
}

export function scheduleAutoDismiss(root = document) {
  qsa('.alert[data-auto-dismiss]', root).forEach(function (alert) {
    if (alert.dataset.dismissScheduled) {
      return;
    }

    alert.dataset.dismissScheduled = 'true';

    window.setTimeout(function () {
      alert.remove();
    }, Number(alert.dataset.autoDismissDelay || DEFAULT_DELAY));
  });
}
