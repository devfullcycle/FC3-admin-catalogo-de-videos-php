<?php

namespace App\Adapters;

use App\Http\Resources\DefaultResource;
use Core\Domain\Repository\PaginationInterface;

class ApiAdapter
{
    public function __construct(
        // private ?PaginationInterface $response = null
        private PaginationInterface $response
    ) {}

    public function toJson()
    {
        // if (!$this->response) {
        //     throw new \Exception('Response is null');
        // }

        return DefaultResource::collection($this->response->items())
                                ->additional([
                                    'meta' => [
                                        'total' => $this->response->total(),
                                        'current_page' => $this->response->currentPage(),
                                        'last_page' => $this->response->lastPage(),
                                        'first_page' => $this->response->firstPage(),
                                        'per_page' => $this->response->perPage(),
                                        'to' => $this->response->to(),
                                        'from' => $this->response->from(),
                                    ]
                                ]);
    }

    public function toXml()
    {
        // 
    }

    public static function json(object $data, int $statusCode = 200)
    {
        return (new DefaultResource($data))
                ->response()
                ->setStatusCode($statusCode);
    }
}
