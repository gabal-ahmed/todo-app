// ============================================================
//  STATE
// ============================================================
const state = {
  filter: 'all',
  priority: '',
  category: '',
  search: '',
  tasks: [],
};

// ============================================================
//  API HELPERS
// ============================================================
async function api(method, action, body = null, params = {}) {
  const url = new URL('api.php', location.href);
  url.searchParams.set('action', action);
  Object.entries(params).forEach(([k, v]) => url.searchParams.set(k, v));

  const opts = { method, headers: { 'Content-Type': 'application/json' } };
  if (body) opts.body = JSON.stringify(body);

  const res = await fetch(url, opts);
  return res.json();
}

// ============================================================
//  TOAST
// ============================================================
function showToast(msg, type = 'success') {
  const icons = { success: '✅', error: '❌', info: 'ℹ️' };
  const el = document.createElement('div');
  el.className = `toast ${type}`;
  el.innerHTML = `<span>${icons[type]}</span> ${msg}`;
  document.getElementById('toast-container').appendChild(el);
  setTimeout(() => el.remove(), 3200);
}

// ============================================================
//  RENDER
// ============================================================
function renderTasks() {
  const list = document.getElementById('tasks-list');
  let tasks = state.tasks;

  // search filter
  if (state.search.trim()) {
    const q = state.search.toLowerCase();
    tasks = tasks.filter(t => t.title.toLowerCase().includes(q) || (t.description || '').toLowerCase().includes(q));
  }

  if (!tasks.length) {
    list.innerHTML = `
      <div class="empty-state">
        <div class="empty-icon">📋</div>
        <h3>No tasks found</h3>
        <p>Add a new task or adjust your filters.</p>
      </div>`;
    return;
  }

  list.innerHTML = tasks.map(t => buildTaskCard(t)).join('');

  // bind checkbox
  list.querySelectorAll('.checkbox').forEach(box => {
    box.addEventListener('click', () => toggleComplete(+box.dataset.id, box.classList.contains('checked')));
  });

  // bind delete
  list.querySelectorAll('.btn-del').forEach(btn => {
    btn.addEventListener('click', () => deleteTask(+btn.dataset.id));
  });

  // bind edit
  list.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', () => openEditModal(+btn.dataset.id));
  });
}

function buildTaskCard(t) {
  const checked  = t.is_completed == 1;
  const overdue  = t.due_date && !checked && new Date(t.due_date) < new Date();
  const dateTag  = t.due_date
    ? `<span class="tag tag-date ${overdue ? 'overdue' : ''}">📅 ${formatDate(t.due_date)}</span>`
    : '';

  return `
    <div class="task-card priority-${t.priority} ${checked ? 'completed' : ''}">
      <div class="checkbox-wrap">
        <div class="checkbox ${checked ? 'checked' : ''}" data-id="${t.id}"></div>
      </div>
      <div class="task-body">
        <div class="task-title">${escHtml(t.title)}</div>
        ${t.description ? `<div class="task-desc">${escHtml(t.description)}</div>` : ''}
        <div class="task-meta">
          <span class="tag tag-category">${escHtml(t.category)}</span>
          <span class="tag tag-${t.priority}">${priorityLabel(t.priority)}</span>
          ${dateTag}
        </div>
      </div>
      <div class="task-actions">
        <button class="icon-btn edit btn-edit" data-id="${t.id}" title="Edit">✏️</button>
        <button class="icon-btn delete btn-del" data-id="${t.id}" title="Delete">🗑️</button>
      </div>
    </div>`;
}

function updateHeaderStats() {
  const total   = state.tasks.length;
  const done    = state.tasks.filter(t => t.is_completed == 1).length;
  const pending = total - done;
  document.getElementById('stat-total').textContent   = total;
  document.getElementById('stat-pending').textContent = pending;
  document.getElementById('stat-done').textContent    = done;
}

function updateSidebarBadges() {
  const all       = state.tasks.length;
  const active    = state.tasks.filter(t => t.is_completed == 0).length;
  const completed = state.tasks.filter(t => t.is_completed == 1).length;
  const high      = state.tasks.filter(t => t.priority === 'high' && t.is_completed == 0).length;
  const medium    = state.tasks.filter(t => t.priority === 'medium' && t.is_completed == 0).length;
  const low       = state.tasks.filter(t => t.priority === 'low' && t.is_completed == 0).length;

  document.getElementById('badge-all').textContent       = all;
  document.getElementById('badge-active').textContent    = active;
  document.getElementById('badge-completed').textContent = completed;
  document.getElementById('badge-high').textContent      = high;
  document.getElementById('badge-medium').textContent    = medium;
  document.getElementById('badge-low').textContent       = low;
}

// ============================================================
//  LOAD TASKS
// ============================================================
async function loadTasks() {
  const params = { filter: state.filter };
  if (state.priority) params.priority = state.priority;
  if (state.category) params.category = state.category;

  const data = await api('GET', 'list', null, params);
  state.tasks = data.tasks || [];

  renderTasks();
  updateHeaderStats();
  updateSidebarBadges();
}

// ============================================================
//  ADD TASK
// ============================================================
document.getElementById('add-form').addEventListener('submit', async e => {
  e.preventDefault();
  const fd = Object.fromEntries(new FormData(e.target));
  if (!fd.title.trim()) { showToast('Title is required', 'error'); return; }

  const res = await api('POST', 'create', fd);
  if (res.error) { showToast(res.error, 'error'); return; }

  e.target.reset();
  showToast('Task added!');
  await loadTasks();
});

// ============================================================
//  TOGGLE COMPLETE
// ============================================================
async function toggleComplete(id, currentlyChecked) {
  await api('PUT', 'update', { id, is_completed: currentlyChecked ? 0 : 1 });
  await loadTasks();
}

// ============================================================
//  DELETE
// ============================================================
async function deleteTask(id) {
  if (!confirm('Delete this task?')) return;
  await api('DELETE', 'delete', null, { id });
  showToast('Task deleted', 'info');
  await loadTasks();
}

// ============================================================
//  EDIT MODAL
// ============================================================
function openEditModal(id) {
  const t = state.tasks.find(x => x.id == id);
  if (!t) return;

  document.getElementById('edit-id').value          = t.id;
  document.getElementById('edit-title').value       = t.title;
  document.getElementById('edit-description').value = t.description || '';
  document.getElementById('edit-priority').value    = t.priority;
  document.getElementById('edit-category').value    = t.category;
  document.getElementById('edit-due_date').value    = t.due_date || '';

  document.getElementById('modal').classList.add('open');
}

document.getElementById('close-modal').addEventListener('click', () => {
  document.getElementById('modal').classList.remove('open');
});

document.getElementById('modal').addEventListener('click', e => {
  if (e.target === e.currentTarget) e.currentTarget.classList.remove('open');
});

document.getElementById('edit-form').addEventListener('submit', async e => {
  e.preventDefault();
  const fd = Object.fromEntries(new FormData(e.target));
  const res = await api('PUT', 'update', fd);
  if (res.error) { showToast(res.error, 'error'); return; }

  document.getElementById('modal').classList.remove('open');
  showToast('Task updated!');
  await loadTasks();
});

// ============================================================
//  SIDEBAR FILTERS
// ============================================================
document.querySelectorAll('[data-filter]').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    state.filter = btn.dataset.filter;
    state.priority = '';
    state.category = '';
    document.querySelectorAll('[data-priority]').forEach(b => b.classList.remove('active'));
    loadTasks();
  });
});

document.querySelectorAll('[data-priority]').forEach(btn => {
  btn.addEventListener('click', () => {
    const same = btn.classList.contains('active');
    document.querySelectorAll('[data-priority]').forEach(b => b.classList.remove('active'));
    if (!same) { btn.classList.add('active'); state.priority = btn.dataset.priority; }
    else state.priority = '';
    loadTasks();
  });
});

// ============================================================
//  SEARCH
// ============================================================
document.getElementById('search-input').addEventListener('input', e => {
  state.search = e.target.value;
  renderTasks();
});

// ============================================================
//  UTILS
// ============================================================
function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function priorityLabel(p) {
  return { high: '🔴 High', medium: '🟡 Medium', low: '🟢 Low' }[p] || p;
}

function formatDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr + 'T00:00:00');
  return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

// ============================================================
//  INIT
// ============================================================
loadTasks();
