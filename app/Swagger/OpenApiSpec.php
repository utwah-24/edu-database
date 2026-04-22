<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Event Management API',
    description: 'API documentation for the Event Management System.'
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Local Development Server'
)]
class OpenApiSpec
{
    #[OA\Get(
        path: '/api/health',
        tags: ['Health'],
        summary: 'Health check endpoint',
        responses: [
            new OA\Response(response: 200, description: 'OK'),
        ]
    )]
    public function health(): void
    {
    }
}
