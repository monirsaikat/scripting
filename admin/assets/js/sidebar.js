/* ──────────────────────────────────────────────
   SIDEBAR COLLAPSE TOGGLE
────────────────────────────────────────────── */
(function () {
  const sidebar   = document.querySelector('.sidebar');
  const hamburger = document.querySelector('.hamburger');

  hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
  });
})();

/* ──────────────────────────────────────────────
   SIDEBAR NAV – active link tracking
────────────────────────────────────────────── */
(function () {
  const links = document.querySelectorAll('.sidebar-nav-link:not(.sub-nav .sidebar-nav-link)');

  links.forEach(link => {
    link.addEventListener('click', () => {
      links.forEach(l => {
        l.classList.remove('active');
        const sub = l.closest('.nav-item-wrapper')?.querySelector('.sub-nav');
        if (sub) sub.style.display = 'none';
      });

      link.classList.add('active');

      const sub = link.closest('.nav-item-wrapper')?.querySelector('.sub-nav');
      if (sub) sub.style.display = sub.style.display === 'none' ? '' : 'none';
    });
  });
})();
