<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $category;

    protected function setUp(): void
    {
       parent::setUp();
       $this->category = Category::factory(1)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('categories.index'));
        $response
            ->assertStatus(200)
            ->assertJson($this->category->toArray());
    }

    public function testShow()
    {
        $response = $this->get(route('categories.show', ['category' => $this->category[0]->id]));
        $response
            ->assertStatus(200)
            ->assertJson($this->category[0]->toArray());
    }

    // public function testStore()
    // {
    //     $data = ['name' => 'teste_category'];
    //     $response = $this->createCategory($data);

    //     /** @var $category Category */
    //     $category = Category::find($response->json('id'));

    //     $response->assertStatus(201)->assertsto assertJson($category->toArray());
    //     $this->assertNull($response->json('description'));
    //     $this->assertTrue($response->json('is_active'));

    //     $data = [
    //         'name' => 'teste_nome_category',
    //         'description' => 'teste_descricao_category',
    //         'is_active' => false
    //     ];
    //     $response = $this->createCategory($data);
    //     $response->assertJsonFragment($data);
    // }

    public function testUpdate()
    {
        $category = Category::factory()->create([
            'description' => 'teste_descricao_category',
            'is_active' => false
        ]);
        $response = $this->updateCategory($category, [
            'name' => 'teste',
            'description' => 'descricao',
            'is_active' => true
        ]);
        $id = $response->json('id');
        $category = Category::find($id);
        $response
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'description' => 'descricao',
                'is_active' => true
            ]);
    }

    public function testDelete()
   {
       $category = Category::factory()->create();

       $response = $this->json('DELETE', route('categories.destroy', ['category' => $category]));
       $response->assertStatus(204 );

       $response = $this->get(route('categories.show', ['category' => $category->id]));
       $response->assertStatus(404 );
   }

    public function testInvalidationData()
    {
        $response = $this->createCategory([]);
        $this->assertInvalidationDataRequired($response);

        $data = [
           'name' => str_repeat('a', 256),
           'is_active' => 'a',
           'description' => str_repeat('a', 651)
        ];
        $response = $this->createCategory($data);
        $this->assertInvalidationDataMax($response);
        $this->assertInvalidationDataBoolean($response);

        $category = Category::factory()->create();

        $response = $this->updateCategory($category, []);
        $this->assertInvalidationDataRequired($response);

        $response = $this->updateCategory($category, [
            'name' => str_repeat('a', 256),
            'description' => str_repeat('a', 651),
            'is_active' => 'a'
        ]);
        $this->assertInvalidationDataMax($response);
        $this->assertInvalidationDataBoolean($response);
    }

    protected function assertInvalidationDataRequired(TestResponse $response)
    {
        $response
            ->assertStatus(422 )
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                trans('validation.required', ['attribute' => 'name'])
            ]);
    }

    protected function assertInvalidationDataMax(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                trans('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ]);

        $response
            ->assertStatus(422 )
            ->assertJsonValidationErrors(['description'])
            ->assertJsonFragment([
                trans('validation.max.string', ['attribute' => 'description', 'max' => 650])
            ]);
    }

    protected function assertInvalidationDataBoolean(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                trans('validation.boolean', ['attribute' => 'is active'])
            ]);
    }

    public function createCategory($data)
    {
        $method = 'POST';
        $route = 'categories.store';
        return $this->json($method, route($route), $data);
    }

    public function updateCategory($oldData, $newData)
    {
        $method = 'PUT';
        $route = 'categories.update';
        $oldCategory =  ['category' => $oldData->id];
        return $this->json($method, route($route, $oldCategory), $newData);
    }
}
