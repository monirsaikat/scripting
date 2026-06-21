Chart.defaults.font.family = "'Segoe UI', system-ui, sans-serif";
Chart.defaults.plugins.legend.display = false;
Chart.defaults.plugins.tooltip.enabled = true;

const MONTHS = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];

/* ── 1. Visitors hero ─────────────────────────── */
up.compiler('#visitorsChart', canvas => {
  const DATA = {
    2016: [1200,1800,2100,1600,2200,2800,2500,3000,2600,2100,1900,2400],
    2017: [1600,2400,2700,1900,2600,3200,2900,3500,3100,2700,2300,2900],
    2018: [1900,3000,3400,2200,2900,3900,3500,4100,3800,3200,2800,3600],
  };
  const chart = new Chart(canvas, {
    type: 'line',
    data: {
      labels: MONTHS,
      datasets: [{
        data: DATA[2018],
        borderColor: 'rgba(255,255,255,0.9)',
        backgroundColor: 'rgba(255,255,255,0.12)',
        borderWidth: 2,
        pointBackgroundColor: 'rgba(255,255,255,0)',
        pointBorderColor: 'rgba(255,255,255,0)',
        pointHoverBackgroundColor: '#fff',
        pointHoverRadius: 5,
        tension: 0.4,
        fill: true,
      }],
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      layout: { padding: { top: 10, bottom: 0 } },
      scales: {
        x: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: 'rgba(255,255,255,0.65)', font: { size: 10 } } },
        y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: 'rgba(255,255,255,0.65)', font: { size: 10 }, callback: v => v >= 1000 ? (v/1000).toFixed(1)+'k' : v } },
      },
    },
  });
  canvas.closest('.visitors-hero')?.querySelectorAll('.year-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      canvas.closest('.visitors-hero')?.querySelectorAll('.year-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      chart.data.datasets[0].data = DATA[parseInt(tab.dataset.year, 10)];
      chart.update();
    });
  });
  return () => chart.destroy();
});

/* ── 2. Conversions bar ───────────────────────── */
up.compiler('#convChart', canvas => {
  const chart = new Chart(canvas, {
    type: 'bar',
    data: {
      labels: ['1','2','3','4','5','6','7','8','9'],
      datasets: [{ data: [3,5,4,7,6,5,8,4,6], backgroundColor: '#4472ca', borderRadius: 2, barPercentage: 0.6 }],
    },
    options: { responsive: true, maintainAspectRatio: false, scales: { x: { display: false }, y: { display: false } } },
  });
  document.querySelectorAll('#convTabs .chart-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('#convTabs .chart-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    });
  });
  return () => chart.destroy();
});

/* ── 3. Device doughnut ───────────────────────── */
up.compiler('#deviceChart', canvas => {
  const labels = ['Desktop', 'Mobile', 'Tablet'];
  const values = [92.8, 6.1, 1.1];
  const colors = ['#4472ca', '#29b6d2', '#e8eaf0'];
  const chart = new Chart(canvas, {
    type: 'doughnut',
    data: { labels, datasets: [{ data: values, backgroundColor: colors, borderWidth: 0, hoverOffset: 4 }] },
    options: { responsive: true, maintainAspectRatio: false, cutout: '72%', plugins: { legend: { display: false } } },
  });
  const legend = document.getElementById('deviceLegend');
  if (legend) {
    legend.innerHTML = labels.map((l, i) =>
      `<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
        <span style="width:10px;height:10px;border-radius:50%;background:${colors[i]};display:inline-block;flex-shrink:0"></span>
        <span style="font-size:11px;color:var(--text-muted);flex:1">${l}</span>
        <span style="font-size:12px;font-weight:700;color:var(--text-main)">${values[i]}%</span>
      </div>`
    ).join('');
  }
  return () => chart.destroy();
});

/* ── 4. Impressions line ──────────────────────── */
up.compiler('#impChart', canvas => {
  const chart = new Chart(canvas, {
    type: 'line',
    data: {
      labels: ['1','2','3','4','5','6','7','8','9'],
      datasets: [{ data: [40,55,48,70,60,75,58,80,65], borderColor: '#4472ca', backgroundColor: 'transparent', borderWidth: 2, tension: 0.4, pointRadius: 0 }],
    },
    options: { responsive: true, maintainAspectRatio: false, scales: { x: { display: false }, y: { display: false } } },
  });
  document.querySelectorAll('#impTabs .chart-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('#impTabs .chart-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    });
  });
  return () => chart.destroy();
});

/* ── 5. Page views area ───────────────────────── */
up.compiler('#pvChart', canvas => {
  const TODAY     = [3200,2800,3600,4200,3100,2600,3800,4500,3900,3200,2900,3600,4100,3700,3300,4000,4400,3800,3500,4200];
  const YESTERDAY = [2800,2400,3100,3700,2800,2300,3400,4000,3500,2900,2600,3200,3800,3400,3000,3700,4000,3500,3200,3800];
  const chart = new Chart(canvas, {
    type: 'line',
    data: {
      labels: Array.from({ length: 20 }, (_, i) => String(i+1)),
      datasets: [{
        data: TODAY,
        borderColor: '#4472ca', backgroundColor: 'rgba(68,114,202,0.15)',
        borderWidth: 2.5, tension: 0.4, fill: true,
        pointRadius: 3, pointBackgroundColor: '#fff', pointBorderColor: '#4472ca', pointBorderWidth: 2,
      }],
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      layout: { padding: { top: 6 } },
      scales: {
        x: { grid: { display: false }, ticks: { display: false } },
        y: { grid: { color: '#eef0f5' }, ticks: { color: '#9098ad', font: { size: 10 }, callback: v => v >= 1000 ? (v/1000)+'k' : v } },
      },
    },
  });
  document.querySelectorAll('#pvToggle .chart-tab').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#pvToggle .chart-tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      chart.data.datasets[0].data = btn.dataset.period === 'today' ? TODAY : YESTERDAY;
      chart.update();
    });
  });
  return () => chart.destroy();
});
