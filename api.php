<?php
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

$conn = getConnection();

// GET all tasks
if ($method === 'GET' && $action === 'list') {
    $filter    = $_GET['filter'] ?? 'all';
    $category  = $_GET['category'] ?? '';
    $priority  = $_GET['priority'] ?? '';

    $where = [];
    if ($filter === 'active')    $where[] = 'is_completed = 0';
    if ($filter === 'completed') $where[] = 'is_completed = 1';
    if ($category !== '')        $where[] = "category = '" . $conn->real_escape_string($category) . "'";
    if ($priority !== '')        $where[] = "priority = '" . $conn->real_escape_string($priority) . "'";

    $sql = 'SELECT * FROM tasks' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY is_completed ASC, created_at DESC';
    $result = $conn->query($sql);
    $tasks = [];
    while ($row = $result->fetch_assoc()) $tasks[] = $row;
    echo json_encode(['tasks' => $tasks]);
}

// GET categories list
elseif ($method === 'GET' && $action === 'categories') {
    $result = $conn->query("SELECT DISTINCT category FROM tasks ORDER BY category");
    $cats = [];
    while ($row = $result->fetch_assoc()) $cats[] = $row['category'];
    echo json_encode(['categories' => $cats]);
}

// POST create task
elseif ($method === 'POST' && $action === 'create') {
    $data     = json_decode(file_get_contents('php://input'), true);
    $title    = trim($conn->real_escape_string($data['title'] ?? ''));
    $desc     = $conn->real_escape_string($data['description'] ?? '');
    $priority = in_array($data['priority'] ?? '', ['low','medium','high']) ? $data['priority'] : 'medium';
    $category = $conn->real_escape_string($data['category'] ?? 'General');
    $due_date = !empty($data['due_date']) ? "'" . $conn->real_escape_string($data['due_date']) . "'" : 'NULL';

    if (!$title) { echo json_encode(['error' => 'Title is required']); exit; }

    $conn->query("INSERT INTO tasks (title, description, priority, category, due_date) VALUES ('$title','$desc','$priority','$category',$due_date)");
    echo json_encode(['success' => true, 'id' => $conn->insert_id]);
}

// PUT update task
elseif ($method === 'PUT' && $action === 'update') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id   = (int)($data['id'] ?? 0);
    if (!$id) { echo json_encode(['error' => 'Invalid ID']); exit; }

    $sets = [];
    if (isset($data['title']))        $sets[] = "title='" . $conn->real_escape_string($data['title']) . "'";
    if (isset($data['description']))  $sets[] = "description='" . $conn->real_escape_string($data['description']) . "'";
    if (isset($data['priority']) && in_array($data['priority'],['low','medium','high'])) $sets[] = "priority='{$data['priority']}'";
    if (isset($data['category']))     $sets[] = "category='" . $conn->real_escape_string($data['category']) . "'";
    if (isset($data['due_date']))     $sets[] = "due_date=" . (!empty($data['due_date']) ? "'" . $conn->real_escape_string($data['due_date']) . "'" : 'NULL');
    if (isset($data['is_completed'])) $sets[] = "is_completed=" . ($data['is_completed'] ? 1 : 0);

    if ($sets) $conn->query("UPDATE tasks SET " . implode(',', $sets) . " WHERE id=$id");
    echo json_encode(['success' => true]);
}

// DELETE task
elseif ($method === 'DELETE' && $action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) { echo json_encode(['error' => 'Invalid ID']); exit; }
    $conn->query("DELETE FROM tasks WHERE id=$id");
    echo json_encode(['success' => true]);
}

else {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown action']);
}

$conn->close();
