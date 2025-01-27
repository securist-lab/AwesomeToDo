<?php
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
class TodoApiTest extends TestCase
{
    private $baseUrl = 'http://nginx-server/api/todo.php';

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

        $response = file_get_contents($this->baseUrl . '?action=add', false, $context);
        $this->assertNotFalse($response, 'API call failed');

        $responseData = json_decode($response, true);
        $this->assertEquals('success', $responseData['status']);
    }

    public function testFetchTodosViaApi()
    {
        $response = file_get_contents($this->baseUrl . '?action=fetch');
        $this->assertNotFalse($response, 'API call failed');

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
        file_get_contents($this->baseUrl . '?action=add', false, $context);

        // Fetch the added ToDo to get its ID
        $response = file_get_contents($this->baseUrl . '?action=fetch');
        $todos = json_decode($response, true);
        $todoToDelete = end($todos);

        // Delete the ToDo item via API
        $response = file_get_contents($this->baseUrl . '?action=delete&id=' . $todoToDelete['id']);
        $this->assertNotFalse($response, 'API call failed');

        $responseData = json_decode($response, true);
        $this->assertEquals('success', $responseData['status']);
    }
}
?>
