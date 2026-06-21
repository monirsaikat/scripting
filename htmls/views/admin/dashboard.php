<?php $this->layout('layouts/admin', ['title' => $pageTitle, 'page' => $page]) ?>

<!-- Visitor hero -->
<div class="gap-col">
  <div class="visitors-hero">
    <div class="visitors-info">
      <div class="visitors-label">Total Visitors</div>
      <div class="visitors-value">93,456</div>
      <div class="visitors-change up"><i class="bi bi-arrow-up-short"></i> +12.4% vs last month</div>
    </div>
    <div class="visitors-chart"><canvas id="visitorsChart"></canvas></div>
  </div>
</div>

<!-- KPI row -->
<div class="gap-col">
  <div class="kpi-row">
    <div class="kpi-card">
      <div class="kpi-icon" style="background:#e8f0fe"><i class="bi bi-people" style="color:#4472ca"></i></div>
      <div class="kpi-value"><?= number_format($stats['users']) ?></div>
      <div class="kpi-label">Registered Users</div>
      <div class="kpi-change up"><i class="bi bi-arrow-up-short"></i> Live count</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon" style="background:#e8f7ef"><i class="bi bi-bag-check" style="color:#1a9e5c"></i></div>
      <div class="kpi-value">1,284</div>
      <div class="kpi-label">Orders This Month</div>
      <div class="kpi-change up"><i class="bi bi-arrow-up-short"></i> +8.3%</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon" style="background:#fff8e7"><i class="bi bi-currency-dollar" style="color:#d97706"></i></div>
      <div class="kpi-value">$48,290</div>
      <div class="kpi-label">Revenue</div>
      <div class="kpi-change up"><i class="bi bi-arrow-up-short"></i> +15.7%</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon" style="background:#fef2f2"><i class="bi bi-graph-down" style="color:#dc2626"></i></div>
      <div class="kpi-value">3.2%</div>
      <div class="kpi-label">Bounce Rate</div>
      <div class="kpi-change down"><i class="bi bi-arrow-down-short"></i> -0.4%</div>
    </div>
  </div>
</div>

<!-- Charts row -->
<div class="gap-col" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
  <!-- Conversions -->
  <div class="chart-card">
    <div class="chart-card-head">
      <div class="chart-card-title">Conversions</div>
      <div class="chart-tabs" id="convTabs">
        <button class="chart-tab active" data-year="2024">2024</button>
        <button class="chart-tab" data-year="2023">2023</button>
      </div>
    </div>
    <div class="chart-canvas-wrap"><canvas id="convChart"></canvas></div>
  </div>
  <!-- Device split -->
  <div class="chart-card">
    <div class="chart-card-head"><div class="chart-card-title">Device Split</div></div>
    <div style="display:flex;align-items:center;gap:20px;padding:8px 0">
      <div style="position:relative;width:120px;height:120px;flex-shrink:0">
        <canvas id="deviceChart"></canvas>
      </div>
      <div id="deviceLegend" class="device-legend"></div>
    </div>
  </div>
</div>

<!-- Impressions / PageViews row -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
  <div class="chart-card">
    <div class="chart-card-head">
      <div class="chart-card-title">Impressions</div>
      <div class="chart-tabs" id="impTabs">
        <button class="chart-tab active" data-year="2024">2024</button>
        <button class="chart-tab" data-year="2023">2023</button>
      </div>
    </div>
    <div class="chart-canvas-wrap"><canvas id="impChart"></canvas></div>
  </div>
  <div class="chart-card">
    <div class="chart-card-head">
      <div class="chart-card-title">Page Views</div>
      <div class="chart-tabs" id="pvToggle">
        <button class="chart-tab active" data-period="today">Today</button>
        <button class="chart-tab" data-period="yesterday">Yesterday</button>
      </div>
    </div>
    <div class="chart-canvas-wrap"><canvas id="pvChart"></canvas></div>
  </div>
</div>

<?php $this->start('scripts') ?>
<script src="<?= baseUrl() ?>/admin/assets/vendor/chart.js/chart.umd.js"></script>
<script src="<?= baseUrl() ?>/admin/assets/js/charts.js"></script>
<?php $this->stop() ?>
