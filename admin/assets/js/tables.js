/* ──────────────────────────────────────────────
   SHARED DATA
────────────────────────────────────────────── */
const USERS = [
  { id:1, initials:'AJ', color:'#667eea,#764ba2', name:'Alice Johnson',  email:'alice@fuse.io',  role:'Admin',  status:'Active',   joined:'2024-01-15' },
  { id:2, initials:'MR', color:'#f093fb,#f5576c', name:'Marcus Reed',    email:'marcus@fuse.io', role:'Editor', status:'Active',   joined:'2024-02-03' },
  { id:3, initials:'JD', color:'#4facfe,#00f2fe', name:'Jane Davis',     email:'jane@fuse.io',   role:'Viewer', status:'Inactive', joined:'2024-03-11' },
  { id:4, initials:'SL', color:'#43e97b,#38f9d7', name:'Sam Lee',        email:'sam@fuse.io',    role:'Editor', status:'Active',   joined:'2024-01-28' },
  { id:5, initials:'AB', color:'#fa709a,#fee140', name:'Amy Brown',      email:'amy@fuse.io',    role:'Viewer', status:'Pending',  joined:'2024-04-05' },
  { id:6, initials:'KW', color:'#a18cd1,#fbc2eb', name:'Kevin Wilson',   email:'kevin@fuse.io',  role:'Admin',  status:'Active',   joined:'2023-11-20' },
  { id:7, initials:'TP', color:'#fccb90,#d57eeb', name:'Tina Park',      email:'tina@fuse.io',   role:'Editor', status:'Active',   joined:'2024-05-01' },
  { id:8, initials:'NM', color:'#a1c4fd,#c2e9fb', name:'Noah Martinez',  email:'noah@fuse.io',   role:'Viewer', status:'Inactive', joined:'2024-02-17' },
  { id:9, initials:'GH', color:'#fd7043,#ff8f00', name:'Grace Harris',   email:'grace@fuse.io',  role:'Editor', status:'Active',   joined:'2023-12-08' },
  { id:10,initials:'RK', color:'#00c853,#64dd17', name:'Ryan Kim',       email:'ryan@fuse.io',   role:'Viewer', status:'Active',   joined:'2024-06-02' },
  { id:11,initials:'EL', color:'#304ffe,#42a5f5', name:'Emma Lopez',     email:'emma@fuse.io',   role:'Admin',  status:'Active',   joined:'2023-10-15' },
  { id:12,initials:'PQ', color:'#ef6c00,#ffa000', name:'Peter Quinn',    email:'peter@fuse.io',  role:'Editor', status:'Pending',  joined:'2024-05-22' },
  { id:13,initials:'DM', color:'#11998e,#38ef7d', name:'Diana Moore',    email:'diana@fuse.io',  role:'Viewer', status:'Active',   joined:'2024-03-30' },
  { id:14,initials:'CT', color:'#f7971e,#ffd200', name:'Chris Turner',   email:'chris@fuse.io',  role:'Editor', status:'Inactive', joined:'2024-01-09' },
  { id:15,initials:'LH', color:'#4568dc,#b06ab3', name:'Laura Hill',     email:'laura@fuse.io',  role:'Viewer', status:'Active',   joined:'2024-06-10' },
];

const ORDERS = [
  { id:'#ORD-001', customer:'Alice Johnson',  product:'FUSE Pro License',    amount:299.00, status:'Completed',  date:'2024-06-01' },
  { id:'#ORD-002', customer:'Marcus Reed',    product:'Analytics Plugin',    amount:89.00,  status:'Pending',    date:'2024-06-02' },
  { id:'#ORD-003', customer:'Jane Davis',     product:'Premium Dashboard',   amount:149.00, status:'Processing', date:'2024-06-02' },
  { id:'#ORD-004', customer:'Sam Lee',        product:'FUSE Pro License',    amount:299.00, status:'Completed',  date:'2024-06-03' },
  { id:'#ORD-005', customer:'Amy Brown',      product:'API Access (Monthly)',  amount:49.00, status:'Cancelled',  date:'2024-06-03' },
  { id:'#ORD-006', customer:'Kevin Wilson',   product:'FUSE Pro License',    amount:299.00, status:'Completed',  date:'2024-06-04' },
  { id:'#ORD-007', customer:'Tina Park',      product:'Analytics Plugin',    amount:89.00,  status:'Completed',  date:'2024-06-05' },
  { id:'#ORD-008', customer:'Noah Martinez',  product:'Premium Dashboard',   amount:149.00, status:'Pending',    date:'2024-06-05' },
  { id:'#ORD-009', customer:'Grace Harris',   product:'API Access (Monthly)',  amount:49.00, status:'Processing', date:'2024-06-06' },
  { id:'#ORD-010', customer:'Ryan Kim',       product:'FUSE Pro License',    amount:299.00, status:'Completed',  date:'2024-06-07' },
  { id:'#ORD-011', customer:'Emma Lopez',     product:'Premium Dashboard',   amount:149.00, status:'Completed',  date:'2024-06-08' },
  { id:'#ORD-012', customer:'Peter Quinn',    product:'Analytics Plugin',    amount:89.00,  status:'Pending',    date:'2024-06-09' },
  { id:'#ORD-013', customer:'Diana Moore',    product:'API Access (Monthly)',  amount:49.00, status:'Completed',  date:'2024-06-10' },
  { id:'#ORD-014', customer:'Chris Turner',   product:'FUSE Pro License',    amount:299.00, status:'Cancelled',  date:'2024-06-11' },
  { id:'#ORD-015', customer:'Laura Hill',     product:'Premium Dashboard',   amount:149.00, status:'Pending',    date:'2024-06-12' },
];

/* ──────────────────────────────────────────────
   PAGINATION HELPER
────────────────────────────────────────────── */
function renderPagination(containerId, currentPage, totalPages, onPage) {
  const el = document.getElementById(containerId);
  if (!el) return;

  const pages = [];
  if (totalPages <= 7) {
    for (let i = 1; i <= totalPages; i++) pages.push(i);
  } else {
    pages.push(1);
    if (currentPage > 3) pages.push('…');
    for (let i = Math.max(2, currentPage - 1); i <= Math.min(totalPages - 1, currentPage + 1); i++) pages.push(i);
    if (currentPage < totalPages - 2) pages.push('…');
    pages.push(totalPages);
  }

  el.innerHTML =
    `<button class="page-btn" ${currentPage === 1 ? 'disabled' : ''} data-p="${currentPage - 1}"><i class="bi bi-chevron-left"></i></button>` +
    pages.map(p => p === '…'
      ? `<button class="page-btn" disabled>…</button>`
      : `<button class="page-btn ${p === currentPage ? 'active' : ''}" data-p="${p}">${p}</button>`
    ).join('') +
    `<button class="page-btn" ${currentPage === totalPages ? 'disabled' : ''} data-p="${currentPage + 1}"><i class="bi bi-chevron-right"></i></button>`;

  el.querySelectorAll('[data-p]').forEach(btn => {
    btn.addEventListener('click', () => onPage(parseInt(btn.dataset.p)));
  });
}

/* ──────────────────────────────────────────────
   TOAST HELPER
────────────────────────────────────────────── */
function showToast(msg) {
  const t = document.getElementById('fuseToast');
  t.querySelector('.toast-msg').textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2800);
}

/* ──────────────────────────────────────────────
   USERS TABLE
────────────────────────────────────────────── */
let usersPage = 1, usersSearch = '', usersRole = '', usersStatus = '';
const USERS_PER_PAGE = 8;

function renderUsersTable() {
  let rows = USERS.filter(u => {
    const q = usersSearch.toLowerCase();
    return (!q || u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q))
        && (!usersRole   || u.role   === usersRole)
        && (!usersStatus || u.status === usersStatus);
  });

  const total = rows.length;
  const totalPages = Math.max(1, Math.ceil(total / USERS_PER_PAGE));
  if (usersPage > totalPages) usersPage = totalPages;
  const slice = rows.slice((usersPage - 1) * USERS_PER_PAGE, usersPage * USERS_PER_PAGE);

  const body = document.getElementById('usersTableBody');
  if (!body) return;

  if (!slice.length) {
    body.innerHTML = `<tr><td colspan="5"><div class="table-empty"><i class="bi bi-people"></i>No users match your filters</div></td></tr>`;
  } else {
    body.innerHTML = slice.map(u => `
      <tr>
        <td>
          <div class="table-user">
            <div class="table-avatar" style="background:linear-gradient(135deg,${u.color})">${u.initials}</div>
            <div>
              <div class="u-name">${u.name}</div>
              <div class="u-email">${u.email}</div>
            </div>
          </div>
        </td>
        <td><span class="role-badge role-${u.role.toLowerCase()}">${u.role}</span></td>
        <td><span class="badge-status badge-${u.status.toLowerCase()}">${u.status}</span></td>
        <td>${u.joined}</td>
        <td>
          <div class="action-btns">
            <div class="action-btn" title="View user" onclick="showToast('Viewing ${u.name}')"><i class="bi bi-eye"></i></div>
            <div class="action-btn" title="Edit user" onclick="showToast('Editing ${u.name}')"><i class="bi bi-pencil"></i></div>
            <div class="action-btn danger" title="Delete user" onclick="showToast('Deleted ${u.name}')"><i class="bi bi-trash"></i></div>
          </div>
        </td>
      </tr>`).join('');
  }

  document.getElementById('usersInfo').textContent =
    `Showing ${Math.min((usersPage-1)*USERS_PER_PAGE+1, total)}–${Math.min(usersPage*USERS_PER_PAGE, total)} of ${total} users`;

  renderPagination('usersPagination', usersPage, totalPages, p => { usersPage = p; renderUsersTable(); });
}

function initUsersTable() {
  const search = document.getElementById('userSearch');
  const roleEl = document.getElementById('userRoleFilter');
  const statEl = document.getElementById('userStatusFilter');
  if (!search) return;

  search.addEventListener('input', () => { usersSearch = search.value; usersPage = 1; renderUsersTable(); });
  roleEl.addEventListener('change', () => { usersRole = roleEl.value; usersPage = 1; renderUsersTable(); });
  statEl.addEventListener('change', () => { usersStatus = statEl.value; usersPage = 1; renderUsersTable(); });
  renderUsersTable();
}

/* ──────────────────────────────────────────────
   ORDERS TABLE
────────────────────────────────────────────── */
let ordersPage = 1, ordersSearch = '', ordersStatus = '';
const ORDERS_PER_PAGE = 8;

function renderOrdersTable() {
  let rows = ORDERS.filter(o => {
    const q = ordersSearch.toLowerCase();
    return (!q || o.id.toLowerCase().includes(q) || o.customer.toLowerCase().includes(q) || o.product.toLowerCase().includes(q))
        && (!ordersStatus || o.status === ordersStatus);
  });

  const total = rows.length;
  const totalPages = Math.max(1, Math.ceil(total / ORDERS_PER_PAGE));
  if (ordersPage > totalPages) ordersPage = totalPages;
  const slice = rows.slice((ordersPage - 1) * ORDERS_PER_PAGE, ordersPage * ORDERS_PER_PAGE);

  const body = document.getElementById('ordersTableBody');
  if (!body) return;

  const STATUS_COLOR = { Completed:'#1a9e5c', Pending:'#d97706', Processing:'#1a6bbf', Cancelled:'#dc2626' };

  if (!slice.length) {
    body.innerHTML = `<tr><td colspan="6"><div class="table-empty"><i class="bi bi-bag"></i>No orders match your filters</div></td></tr>`;
  } else {
    body.innerHTML = slice.map(o => `
      <tr>
        <td><strong>${o.id}</strong></td>
        <td>${o.customer}</td>
        <td>${o.product}</td>
        <td><strong>$${o.amount.toFixed(2)}</strong></td>
        <td><span class="badge-status badge-${o.status.toLowerCase()}">${o.status}</span></td>
        <td>${o.date}</td>
        <td>
          <div class="action-btns">
            <div class="action-btn" title="View" onclick="showToast('Viewing ${o.id}')"><i class="bi bi-eye"></i></div>
            <div class="action-btn success" title="Mark complete" onclick="showToast('${o.id} marked complete')"><i class="bi bi-check-lg"></i></div>
            <div class="action-btn danger" title="Cancel" onclick="showToast('${o.id} cancelled')"><i class="bi bi-x-lg"></i></div>
          </div>
        </td>
      </tr>`).join('');
  }

  document.getElementById('ordersInfo').textContent =
    `Showing ${Math.min((ordersPage-1)*ORDERS_PER_PAGE+1, total)}–${Math.min(ordersPage*ORDERS_PER_PAGE, total)} of ${total} orders`;

  renderPagination('ordersPagination', ordersPage, totalPages, p => { ordersPage = p; renderOrdersTable(); });
}

function initOrdersTable() {
  const search = document.getElementById('orderSearch');
  const statEl = document.getElementById('orderStatusFilter');
  if (!search) return;

  search.addEventListener('input', () => { ordersSearch = search.value; ordersPage = 1; renderOrdersTable(); });
  statEl.addEventListener('change', () => { ordersStatus = statEl.value; ordersPage = 1; renderOrdersTable(); });
  renderOrdersTable();
}
