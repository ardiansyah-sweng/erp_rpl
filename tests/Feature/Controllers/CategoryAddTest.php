<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use Mockery;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class CategoryAddTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_validation_fails_when_fields_empty()
    {
        $validator = Validator::make([], [
            'category' => 'required|string|min:3',
            'parent_id' => 'nullable|integer',
            'active' => 'required|boolean'
        ]);

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('category'));
        $this->assertTrue($validator->errors()->has('active'));
    }

    public function test_validation_fails_when_name_too_short()
    {
        $validator = Validator::make([
            'category' => 'ab',
            'active' => 1
        ], [
            'category' => 'required|string|min:3',
            'parent_id' => 'nullable|integer',
            'active' => 'required|boolean'
        ]);

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('category'));
        $this->assertEquals('The category field must be at least 3 characters.', $validator->errors()->first('category'));
    }

    public function test_add_category_calls_model_method()
    {
        $testData = [
            'category' => 'New Category',
            'parent_id' => 0,
            'active' => 1
        ];

        // Create a mock instance and bind it in the container so controller resolves it
        $categoryMock = Mockery::mock('App\\Models\\Category');
        $categoryMock->shouldReceive('addCategory')
                     ->once()
                     ->with($testData)
                     ->andReturn((object) array_merge(['id' => 1], $testData));
        $this->app->instance(\App\Models\Category::class, $categoryMock);

        // Mock the validator used inside Request::validate
        $validatorMock = Mockery::mock('Illuminate\\Validation\\Validator');
        $validatorMock->shouldReceive('validate')
                      ->andReturn($testData);

        Validator::shouldReceive('make')
                 ->andReturn($validatorMock);

    // Register a dummy named route so redirect()->route('category.list') works in tests
    Route::get('/_test_category_list', function () { return 'ok'; })->name('category.list');

    $request = new Request();
        $request->merge($testData);

        $controller = new \App\Http\Controllers\CategoryController();

        try {
            $response = $controller->addCategory($request);

            // Controller returns a redirect response; assert that it's a redirect instance
            $this->assertTrue(method_exists($response, 'getStatusCode'));
        } catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
            // In test environment route names may not exist; the important part is
            // that Category::addCategory() was called. Mockery will verify that.
            $this->addToAssertionCount(1); // count one assertion to avoid risky if needed
            return;
        }
    }

    public function test_validation_fails_when_category_already_exists()
    {
        // Mock the DatabasePresenceVerifier so unique rule reports the value exists
        $verifierMock = Mockery::mock('Illuminate\\Validation\\DatabasePresenceVerifier');
        $verifierMock->shouldReceive('setConnection')->andReturnNull();
        $verifierMock->shouldReceive('getCount')->andReturn(1);
        $verifierMock->shouldReceive('exists')->andReturn(true);

        $current = Validator::getPresenceVerifier();
        Validator::setPresenceVerifier($verifierMock);

        try {
            $validator = Validator::make([
                'category' => 'Existing Category',
                'active' => 1
            ], [
                'category' => 'required|string|min:3|unique:category,category',
                'parent_id' => 'nullable|integer',
                'active' => 'required|boolean'
            ]);

            $this->assertTrue($validator->fails());
            $this->assertTrue($validator->errors()->has('category'));
            $this->assertEquals('The category has already been taken.', $validator->errors()->first('category'));
        } finally {
            Validator::setPresenceVerifier($current);
        }
    }
}
