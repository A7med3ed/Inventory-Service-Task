<?php

namespace App\Modules\Core\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'Product Inventory Microservice API',
    title: 'Product Inventory API'
)]
#[OA\Server(
    url: 'http://localhost',
    description: 'Local server'
)]
class ApiInfo {}
