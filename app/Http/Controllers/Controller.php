<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel PT FAN TEST Documentation",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="admin@admin.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints of Authentication"
 * )

 * @OA\Tag(
 *     name="Presences",
 *     description="API Endpoints of Epresences"
 * )

 * @OA\Tag(
 *     name="Test",
 *     description="API Endpoints of Logic Test"
 * )
 * 
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   in="header",
 *   name="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
