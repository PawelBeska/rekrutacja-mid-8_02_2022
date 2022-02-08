<?php

namespace Tests\Feature;

use App\Http\Resources\ProductResource;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddingNewProduct(): void
    {
        $response = $this->post(
            '/api/v1/product',
            [
                'name' => 'Message title.',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ultrices tristique quam eu scelerisque. Cras et nunc aliquet, dignissim ipsum non, commodo quam. Morbi venenatis dolor et dolor gravida, id vehicula tortor rutrum. Proin accumsan vitae elit quis pulvinar. Aenean vel luctus neque, eu egestas tortor. Proin suscipit nisi sem, in aliquam augue molestie vitae. Phasellus vehicula commodo porta. Maecenas scelerisque dapibus velit, id volutpat tortor. Etiam ac ex arcu.',
            ]
        );
        $response->assertStatus(201);

        $this->assertDatabaseHas(Product::class, [
            'name' => 'Message title.',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ultrices tristique quam eu scelerisque. Cras et nunc aliquet, dignissim ipsum non, commodo quam. Morbi venenatis dolor et dolor gravida, id vehicula tortor rutrum. Proin accumsan vitae elit quis pulvinar. Aenean vel luctus neque, eu egestas tortor. Proin suscipit nisi sem, in aliquam augue molestie vitae. Phasellus vehicula commodo porta. Maecenas scelerisque dapibus velit, id volutpat tortor. Etiam ac ex arcu.',
        ]);
    }

    public function testFetchProductsList(): void
    {

        Message::factory()->count(30);

        $response = $this->get('/api/v1/product');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'data' => [
                    '*' => [
                        "id",
                        "name",
                        "description",
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

    public function testDeleteProduct(): void
    {
        $product = Product::factory()->createOne();
        $response = $this->delete("/api/v1/product/$product->id");
        $response->assertStatus(200);

    }

    public function testUpdateProduct(): void
    {
        $product = Product::factory()->createOne();
        $response = $this->put("/api/v1/product/$product->id", [
            'name' => 'Message title.',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ultrices tristique quam eu scelerisque. Cras et nunc aliquet, dignissim ipsum non, commodo quam. Morbi venenatis dolor et dolor gravida, id vehicula tortor rutrum. Proin accumsan vitae elit quis pulvinar. Aenean vel luctus neque, eu egestas tortor. Proin suscipit nisi sem, in aliquam augue molestie vitae. Phasellus vehicula commodo porta. Maecenas scelerisque dapibus velit, id volutpat tortor. Etiam ac ex arcu.',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas(Product::class,
            [
                'name' => 'Message title.',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ultrices tristique quam eu scelerisque. Cras et nunc aliquet, dignissim ipsum non, commodo quam. Morbi venenatis dolor et dolor gravida, id vehicula tortor rutrum. Proin accumsan vitae elit quis pulvinar. Aenean vel luctus neque, eu egestas tortor. Proin suscipit nisi sem, in aliquam augue molestie vitae. Phasellus vehicula commodo porta. Maecenas scelerisque dapibus velit, id volutpat tortor. Etiam ac ex arcu.',
            ]

        );
    }

    public function testFetchSingleProduct(): void
    {
        $product = Product::factory()->createOne();
        $response = $this->get("/api/v1/product/$product->id");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                "id",
                "name",
                "description",
            ],
            'code'
        ]);

        $response->assertJson([
            "status" => "ok",
            "data" => [
                "id" => $product->id,
                "name" => $product->name,
                "description" => $product->description
            ],
            "code" => 200
        ]);

    }
}
