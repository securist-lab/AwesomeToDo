<?php
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
class TodoApiTest extends TestCase
{
    # private $baseUrl = 'http://nginx-server/api/todo.php';
    private $baseUrl = 'http://localhost:8000/api/todo.php';

    public function testAddTodoViaApi()
    {
        $newTodo = [
            'title' => 'API Test ToDo',
            'due_date' => '2025-12-31 23:59:59'
        ];

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => json_encode($newTodo),
            ]
        ]);

        $response = @file_get_contents($this->baseUrl . '/add', false, $context);
        if ($response === false) {
            $error = error_get_last();
            $this->fail('API call failed: ' . $error['message']);
        }
        $responseData = json_decode($response, true);
        $this->assertEquals('success', $responseData['status']);
    }

    public function testFetchTodosViaApi()
    {
        $response = @file_get_contents($this->baseUrl . '/fetch');
        if ($response === false) {
            $error = error_get_last();
            $this->fail('API call failed: ' . $error['message']);
        }

        $todos = json_decode($response, true);
        $this->assertIsArray($todos);
    }

    public function testDeleteTodoViaApi()
    {
        // Insert a test ToDo item first
        $newTodo = [
            'title' => 'API ToBeDeleted',
            'due_date' => '2024-12-31 23:59:59'
        ];

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => json_encode($newTodo),
            ]
        ]);
        file_get_contents($this->baseUrl . '/add', false, $context);

        // Fetch the added ToDo to get its ID
        $response = @file_get_contents($this->baseUrl . '/fetch');
        if ($response === false) {
            $error = error_get_last();
            $this->fail('API call failed: ' . $error['message']);
        }
        $todos = json_decode($response, true);
        $this->assertNotEmpty($todos, 'No ToDo items found');

        $todoToDelete = end($todos);
        $todoId = $todoToDelete['id'] ?? null;
        $this->assertNotNull($todoId, 'ToDo ID not found');

        // Delete the ToDo item via API
        $response = @file_get_contents($this->baseUrl . "/delete?id=" . $todoId);
        if ($response === false) {
            $error = error_get_last();
            $this->fail('API call failed: ' . $error['message']);
        }

        $responseData = json_decode($response, true);
        $this->assertEquals('success', $responseData['status']);
    }
}
?>
