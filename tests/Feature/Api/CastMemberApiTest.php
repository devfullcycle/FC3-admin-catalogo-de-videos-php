<?php

namespace Tests\Feature\Api;

use App\Models\CastMember;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\WithoutMiddlewareTrait;

class CastMemberApiTest extends TestCase
{
    use WithoutMiddlewareTrait;

    private $endpoint = '/api/cast_members';

    public function test_get_all_empty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function test_pagination()
    {
        CastMember::factory()->count(50)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from',
            ],
        ]);
    }

    public function test_pagination_page_two()
    {
        CastMember::factory()->count(20)->create();

        $response = $this->getJson("$this->endpoint?page=2");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');

        $this->assertEquals(20, $response['meta']['total']);
        $this->assertEquals(2, $response['meta']['current_page']);
    }

    public function test_pagination_with_filter()
    {
        CastMember::factory()->count(10)->create();
        CastMember::factory()->count(10)->create([
            'name' => 'teste',
        ]);

        $response = $this->getJson("$this->endpoint?filter=teste");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(10, 'data');
    }

    public function test_show_not_found()
    {
        $response = $this->getJson("{$this->endpoint}/fake_id");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_show()
    {
        $castMember = CastMember::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$castMember->id}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
            ],
        ]);
    }

    public function test_store_validations()
    {
        $response = $this->postJson($this->endpoint, []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'type',
            ],
        ]);
    }

    public function test_store()
    {
        $response = $this->postJson($this->endpoint, [
            'name' => 'teste',
            'type' => 1,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
            ],
        ]);
        $this->assertDatabaseHas('cast_members', [
            'name' => 'teste',
        ]);
    }

    public function test_update_not_found()
    {
        $response = $this->putJson("{$this->endpoint}/fake_id", [
            'name' => 'teste',
            'type' => 1,
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_validations()
    {
        $castMember = CastMember::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$castMember->id}", []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
    }

    public function test_update()
    {
        $castMember = CastMember::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$castMember->id}", [
            'name' => 'new name',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
            ],
        ]);
        $this->assertDatabaseHas('cast_members', [
            'name' => 'new name',
        ]);
    }

    public function test_delete_not_found()
    {
        $response = $this->deleteJson("{$this->endpoint}/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete()
    {
        $castMember = CastMember::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/{$castMember->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('cast_members', [
            'id' => $castMember->id,
        ]);
    }
}
