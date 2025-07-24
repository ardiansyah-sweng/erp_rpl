<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\SupplierMaterialController;
use Illuminate\Testing\TestResponse;

class SupplierMaterialSearchTest extends TestCase
{
    /** @test */
    public function it_directly_calls_controller_method_and_returns_json()
    {
        $request = Request::create(
            '/supplier/material/search',
            'GET',
            ['keyword' => 'SUP010'],
            [],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );

        $controller = new SupplierMaterialController();
        $response = $controller->searchSupplierMaterial($request);

        $testResponse = TestResponse::fromBaseResponse($response);

        $testResponse->assertStatus(200);
        $testResponse->assertJsonFragment([
            'company_name' => 'CV Telecom Membangun Lemindo Titan Tbk'
        ]);
    }
}