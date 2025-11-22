<?php
namespace App\Swagger;
/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="My API",
 *         version="1.0.0",
 *         description="API documentation"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class OpenApi {}
