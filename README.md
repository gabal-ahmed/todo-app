# ✅ TaskFlow — Todo App

A clean, modern **Todo application** built with vanilla HTML, CSS, JavaScript and PHP with MySQL as the database.

---

## 🖼️ Preview

> Dark-themed UI with sidebar filters, priority tags, due dates, and smooth animations.

---

## ✨ Features

- ➕ Add tasks with title, description, priority, category, and due date
- ✅ Mark tasks as complete / incomplete
- ✏️ Edit any task via a modal popup
- 🗑️ Delete tasks with confirmation
- 🔍 Live search across title and description
- 🎨 Filter by status (All / Active / Completed)
- 🔴🟡🟢 Filter by priority (High / Medium / Low)
- 📅 Overdue date highlighting
- 📊 Live stats in the header (Total / Pending / Done)
- 🌙 Dark mode UI with smooth animations

---

## 🛠️ Tech Stack

| Layer    | Technology          |
|----------|---------------------|
| Frontend | HTML5, CSS3, JavaScript (ES6+) |
| Backend  | PHP 8+              |
| Database | MySQL (via MySQLi)  |
| Server   | Apache (XAMPP)      |

---

## 🚀 Getting Started

### Requirements
- [XAMPP](https://www.apachefriends.org/) (or any Apache + PHP + MySQL stack)
- PHP 8+
- MySQL 5.7+

### Installation

**1. Clone the repository**
```bash
git clone https://github.com/gabal-ahmed/todo-app.git
```

**2. Move to XAMPP's web root**
```bash
mv todo-app C:/xampp/htdocs/todo-app
```

**3. Set up the config file**
```bash
cp config.example.php config.php
```
Open `config.php` and update your database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');        // your MySQL password
define('DB_NAME', 'todo_app');
```

**4. Create the database**

Open your browser and visit:
```
http://localhost/todo-app/setup.php
```
You should see: ✅ Database & table created successfully!

**5. Open the app**
```
http://localhost/todo-app/
```

---

## 📁 Project Structure

```
todo-app/
├── index.php            # Main page (UI)
├── api.php              # REST API — handles all CRUD operations
├── db.php               # Database connection helper
├── config.php           # DB credentials (not committed)
├── config.example.php   # Config template
├── setup.php            # One-time DB & table setup
└── assets/
    ├── style.css        # Full dark-theme styling
    └── script.js        # All frontend logic & API calls
```

---

## 🔌 API Reference

All endpoints are in `api.php`.

| Method | `?action=` | Description            |
|--------|-----------|------------------------|
| GET    | `list`    | Get all tasks (supports `filter`, `priority`, `category` params) |
| POST   | `create`  | Create a new task      |
| PUT    | `update`  | Update an existing task |
| DELETE | `delete`  | Delete a task (`?id=`) |

---

## 📝 License

MIT — free to use and modify.
