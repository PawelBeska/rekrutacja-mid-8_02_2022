<?php

namespace Tests\Feature;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddingNewMessage(): void
    {
        $response = $this->post(
            '/api/v1/message',
            [
                'title' => 'Message title.',
                'email' => 'user@example.com',
                'content' => 'This is test content.'
            ]
        );
        $response->assertStatus(201);

        $this->assertDatabaseHas(Message::class, [
            'title' => 'Message title.',
            'email' => 'user@example.com',
            'content' => 'This is test content.'
        ]);
    }

    public function testFetchMessageList(): void
    {

        Message::factory()->count(30);

        $response = $this->get('/api/v1/message');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'data' => [
                    '*' => [
                        "uuid",
                        "title",
                        "email",
                        "content"
                    ],
                ],
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages'
                ],
            ],
            'code'
        ]);
    }
}
