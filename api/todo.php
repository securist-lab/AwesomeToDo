<?php
require_once 'db.php';

header("Content-Type: application/json");

$action = $_GET['action'] ?? '';

$db = Database::getInstance()->getConnection();

switch ($action) {
  case 'fetch':
    $stmt = $db->query("SELECT * FROM todos ORDER BY due_date ASC");
    $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($todos);
    break;
  case 'add':
    $input = json_decode(file_get_contents("php://input"), true);
    $stmt = $db->prepare("INSERT INTO todos (title, due_date) VALUES (:title, :due_date)");
    $stmt->execute([':title' => $input['title'], ':due_date' => $input['due_date']]);
    echo json_encode(['status' => 'success']);
    break;
  case 'delete':
    $id = $_GET['id'] ?? 0;
    $stmt = $db->prepare("DELETE FROM todos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    #echo json_encode(['status' => 'success?']);
    echo json_encode(['status' => 'success']);
    break;
  default:
    echo json_encode(['error' => 'Invalid action']);
    break;
}
?>
