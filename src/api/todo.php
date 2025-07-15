<?php
require_once 'db.php';

header("Content-Type: application/json");

$db = Database::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$path = strtok($_SERVER['REQUEST_URI'], '?'); // クエリパラメータを除外したパス
$params = $_GET;

// ルーティング定義
$routes = [
    'GET'  => [
        '/api/todo.php/fetch'  => 'fetchTodos',
        '/api/todo.php/delete' => 'deleteTodo'
    ],
    'POST' => [
        '/api/todo.php/add' => 'addTodo'
    ]
];

// ルートが存在するかチェック
if (isset($routes[$method][$path])) {
    call_user_func($routes[$method][$path], $db, $params);
} else {
    handleError(404, 'Invalid API request');
}

// 関数定義

function fetchTodos($db) {
    $stmt = $db->query("SELECT * FROM todos ORDER BY due_date ASC");
    jsonResponse(200, $stmt->fetchAll(PDO::FETCH_ASSOC));
}

function deleteTodo($db, $params) {
    if (empty($params['id'])) {
        handleError(400, 'Missing ID parameter');
    }
    $stmt = $db->prepare("DELETE FROM todos WHERE id = :id");
    $stmt->execute([':id' => $params['id']]);
    jsonResponse(200, ['status' => 'success']);
}

function addTodo($db) {
    $input = json_decode(file_get_contents("php://input"), true);
    if (empty($input['title']) || empty($input['due_date'])) {
        handleError(400, 'Missing required fields');
    }
    $stmt = $db->prepare("INSERT INTO todos (title, due_date) VALUES (:title, :due_date)");
    $stmt->execute([':title' => $input['title'], ':due_date' => $input['due_date']]);
    jsonResponse(201, ['status' => 'success']);
}

function jsonResponse($status, $data) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function handleError($status, $message) {
    jsonResponse($status, ['error' => $message]);
}
?>
