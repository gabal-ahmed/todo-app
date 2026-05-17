<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TaskFlow — Todo App</title>
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>

<div class="app-wrapper">

  <!-- ===== HEADER ===== -->
  <header class="header">
    <div class="header-brand">
      <div class="header-icon">✅</div>
      <div>
        <h1>TaskFlow</h1>
        <p>Stay organized, stay productive</p>
      </div>
    </div>
    <div class="header-stats">
      <div class="stat-pill">📋 Total <span id="stat-total">0</span></div>
      <div class="stat-pill">⏳ Pending <span id="stat-pending">0</span></div>
      <div class="stat-pill">🎉 Done <span id="stat-done">0</span></div>
    </div>
  </header>

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">

    <div class="sidebar-section">
      <div class="sidebar-title">Views</div>
      <button class="filter-btn active" data-filter="all">
        <span class="icon">📋</span> All Tasks
        <span class="badge" id="badge-all">0</span>
      </button>
      <button class="filter-btn" data-filter="active">
        <span class="icon">⏳</span> Active
        <span class="badge" id="badge-active">0</span>
      </button>
      <button class="filter-btn" data-filter="completed">
        <span class="icon">✅</span> Completed
        <span class="badge" id="badge-completed">0</span>
      </button>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-title">Priority</div>
      <button class="filter-btn" data-priority="high">
        <span class="priority-dot dot-high"></span> High
        <span class="badge" id="badge-high">0</span>
      </button>
      <button class="filter-btn" data-priority="medium">
        <span class="priority-dot dot-medium"></span> Medium
        <span class="badge" id="badge-medium">0</span>
      </button>
      <button class="filter-btn" data-priority="low">
        <span class="priority-dot dot-low"></span> Low
        <span class="badge" id="badge-low">0</span>
      </button>
    </div>

  </aside>

  <!-- ===== MAIN ===== -->
  <main class="main">

    <!-- Add Task Card -->
    <div class="add-card">
      <h2>➕ New Task</h2>
      <form id="add-form" autocomplete="off">

        <div class="form-row">
          <div class="form-group form-full">
            <label>Task Title *</label>
            <input type="text" name="title" placeholder="What needs to be done?" required />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group form-full">
            <label>Description</label>
            <textarea name="description" placeholder="Optional details…"></textarea>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Priority</label>
            <select name="priority">
              <option value="medium">🟡 Medium</option>
              <option value="high">🔴 High</option>
              <option value="low">🟢 Low</option>
            </select>
          </div>
          <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" placeholder="General" value="General" />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" />
          </div>
        </div>

        <button type="submit" class="btn-add">➕ Add Task</button>
      </form>
    </div>

    <!-- Search -->
    <div class="search-bar">
      <span class="search-icon">🔍</span>
      <input type="text" id="search-input" placeholder="Search tasks…" />
    </div>

    <!-- Tasks Header -->
    <div class="tasks-header">
      <h2 id="list-label">All Tasks</h2>
    </div>

    <!-- Task List -->
    <div class="tasks-list" id="tasks-list">
      <div class="empty-state">
        <div class="empty-icon">📋</div>
        <h3>No tasks yet</h3>
        <p>Add your first task above!</p>
      </div>
    </div>

  </main>
</div>

<!-- ===== EDIT MODAL ===== -->
<div class="modal-overlay" id="modal">
  <div class="modal">
    <div class="modal-header">
      <h2>✏️ Edit Task</h2>
      <button class="close-btn" id="close-modal">✕</button>
    </div>
    <form id="edit-form" autocomplete="off">
      <input type="hidden" name="id" id="edit-id" />

      <div class="form-row">
        <div class="form-group form-full">
          <label>Task Title *</label>
          <input type="text" name="title" id="edit-title" required />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group form-full">
          <label>Description</label>
          <textarea name="description" id="edit-description"></textarea>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Priority</label>
          <select name="priority" id="edit-priority">
            <option value="high">🔴 High</option>
            <option value="medium">🟡 Medium</option>
            <option value="low">🟢 Low</option>
          </select>
        </div>
        <div class="form-group">
          <label>Category</label>
          <input type="text" name="category" id="edit-category" />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Due Date</label>
          <input type="date" name="due_date" id="edit-due_date" />
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-cancel" id="close-modal-2" onclick="document.getElementById('modal').classList.remove('open')">Cancel</button>
        <button type="submit" class="btn-save">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- Toast container -->
<div id="toast-container"></div>

<script src="assets/script.js"></script>
</body>
</html>
