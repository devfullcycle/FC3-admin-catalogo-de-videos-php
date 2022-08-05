<?php

namespace Tests\Feature\Api;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class VideoApiTest extends TestCase
{
    protected $endpoint = '/api/videos';
    
    /**
     * @test
     */
    public function empty()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertOk();
    }

    /**
     * @test
     * @dataProvider dataProviderPagination
     */
    public function pagination(
        int $total,
        int $totalCurrentPage,
        int $page = 1,
        int $perPage = 15,
    ) {
        Video::factory()->count($total)->create();
        $response = $this->getJson($this->endpoint);
        
        $response->assertOk();
        $response->assertJsonCount($totalCurrentPage, 'data');
        $response->assertJsonPath('meta.current_page', $page);
        $response->assertJsonPath('meta.per_page', $perPage);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'year_launched',
                    'opened',
                    'rating',
                    'duration',
                    'created_at',
                ]
            ], 
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from',
            ]
        ]);
    }

    protected function dataProviderPagination(): array
    {
        return [
            'test empty' => [
                'total' => 0,
                'totalCurrentPage' => 10,
                'page' => 1,
                'perPage' => 15,
            ], 
            'test with total two pages' => [
                'total' => 20,
                'totalCurrentPage' => 15,
                'page' => 1,
                'perPage' => 15,
            ], 
            [
                'total' => 10,
                'totalCurrentPage' => 10,
                'page' => 1,
                'perPage' => 15,
            ]
        ];
    }
}
