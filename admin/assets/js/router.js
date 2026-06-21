/* ──────────────────────────────────────────────
   SPA ROUTER
────────────────────────────────────────────── */
const ROUTES = {
  dashboard:  { page:'page-dashboard', title:'Analytics',     showAvatars:true  },
  project:    { page:'page-placeholder', title:'Project',     showAvatars:false, icon:'bi-kanban',            desc:'Project dashboard coming soon.' },
  calendar:   { page:'page-placeholder', title:'Calendar',    showAvatars:false, icon:'bi-calendar3',         desc:'Calendar feature is under development.' },
  orders:     { page:'page-orders',    title:'E-Commerce',    showAvatars:false },
  academy:    { page:'page-placeholder', title:'Academy',     showAvatars:false, icon:'bi-mortarboard',       desc:'Learning academy coming soon.' },
  mail:       { page:'page-placeholder', title:'Mail',        showAvatars:false, icon:'bi-envelope-paper',    desc:'Mail client coming soon.' },
  mailngx:    { page:'page-placeholder', title:'Mail Ngx',    showAvatars:false, icon:'bi-layout-text-window',desc:'Mail Ngx coming soon.' },
  chat:       { page:'page-placeholder', title:'Chat',        showAvatars:false, icon:'bi-chat-dots',         desc:'Real-time chat coming soon.' },
  files:      { page:'page-placeholder', title:'File Manager',showAvatars:false, icon:'bi-folder2-open',      desc:'File manager coming soon.' },
  contacts:   { page:'page-placeholder', title:'Contacts',    showAvatars:false, icon:'bi-person-lines-fill', desc:'Contacts module coming soon.' },
  todo:       { page:'page-placeholder', title:'To-Do',       showAvatars:false, icon:'bi-check2-square',     desc:'Task manager coming soon.' },
  scrumboard: { page:'page-placeholder', title:'Scrumboard',  showAvatars:false, icon:'bi-columns-gap',       desc:'Scrumboard coming soon.' },
  users:      { page:'page-users',     title:'Users',          showAvatars:false },
  settings:   { page:'page-settings',  title:'Settings',       showAvatars:false },
  'coming-soon': { page:'page-placeholder', title:'Coming Soon', showAvatars:false, icon:'bi-hourglass-split', desc:'This page is under construction.' },
  errors:     { page:'page-placeholder', title:'Errors',      showAvatars:false, icon:'bi-exclamation-octagon', desc:'Error pages preview coming soon.' },
};

const tablesInited = { users: false, orders: false };

function navigate(route) {
  if (!route) route = 'dashboard';
  const cfg = ROUTES[route] || ROUTES['dashboard'];

  /* hide all pages */
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));

  /* show target page */
  const pageEl = document.getElementById(cfg.page);
  if (pageEl) pageEl.classList.add('active');

  /* placeholder content */
  if (cfg.page === 'page-placeholder') {
    document.getElementById('placeholderIcon').className   = `bi ${cfg.icon} placeholder-icon`;
    document.getElementById('placeholderTitle').textContent = cfg.title;
    document.getElementById('placeholderDesc').textContent  = cfg.desc || '';
  }

  /* right avatars */
  document.getElementById('rightAvatars').style.display = cfg.showAvatars ? '' : 'none';

  /* breadcrumb */
  document.getElementById('topbarBreadcrumb').textContent = cfg.title;

  /* sidebar active highlight */
  document.querySelectorAll('[data-route]').forEach(el => el.classList.remove('active'));
  document.querySelectorAll(`[data-route="${route}"]`).forEach(el => el.classList.add('active'));

  /* init tables lazily (only once) */
  if (cfg.page === 'page-users' && !tablesInited.users) {
    initUsersTable();
    tablesInited.users = true;
  }
  if (cfg.page === 'page-orders' && !tablesInited.orders) {
    initOrdersTable();
    tablesInited.orders = true;
  }

  /* update hash without triggering hashchange loop */
  if (location.hash !== '#' + route) {
    history.replaceState(null, '', '#' + route);
  }
}

/* ── Wire up sidebar links ── */
document.querySelectorAll('[data-route]').forEach(el => {
  el.addEventListener('click', () => navigate(el.dataset.route));
});

/* ── Hamburger toggle ── */
document.querySelector('.hamburger').addEventListener('click', () => {
  document.querySelector('.sidebar').classList.toggle('collapsed');
});

/* ── Hash-based deep link ── */
window.addEventListener('hashchange', () => {
  navigate(location.hash.replace('#', ''));
});

/* ── Initial load ── */
navigate(location.hash.replace('#', '') || 'dashboard');

/* ── Add user button stub ── */
document.getElementById('addUserBtn')?.addEventListener('click', () => showToast('Add user modal coming soon'));
