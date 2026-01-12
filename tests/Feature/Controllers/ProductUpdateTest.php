<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Mockery;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductUpdateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test validation fails when required fields are empty
     */
    public function test_validation_fails_when_fields_empty()
    {
        $validator = Validator::make([], [
            'product_name' => 'required|string|min:3|max:35',
            'product_type' => 'required|string|max:12',
            'product_category' => 'required|integer',
            'product_description' => 'nullable|string|max:255',
        ]);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());

        $errors = $validator->errors();
        $this->assertTrue($errors->has('product_name'));
        $this->assertTrue($errors->has('product_type'));
        $this->assertTrue($errors->has('product_category'));
    }

    /**
     * Test validation fails when product_name is less than 3 characters
     */
    public function test_validation_fails_when_name_too_short()
    {
        $validator = Validator::make([
            'product_name' => 'ab', // Too short
            'product_type' => 'FG',
            'product_category' => 1,
        ], [
            'product_name' => 'required|string|min:3|max:35',
            'product_type' => 'required|string|max:12',
            'product_category' => 'required|integer',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('product_name'));
        $this->assertEquals('The product name field must be at least 3 characters.', $validator->errors()->first('product_name'));
    }

    /**
     * Test that updateProduct calls the model method correctly
     */
    public function test_update_product_calls_model_method()
    {
        // Create test data
        $testData = [
            'product_name' => 'Valid Product Name',
            'product_type' => 'FG',
            'product_category' => 1,
            'product_description' => 'Test description'
        ];

    // Create a mock instance of Product and bind to container so controller resolves it
    $productMock = Mockery::mock('App\\Models\\Product');
    $productMock->shouldReceive('updateProduct')
           ->once()
           ->with(1, $testData)
           ->andReturn((object)array_merge(['id' => 1], $testData));
    $this->app->instance(\App\Models\Product::class, $productMock);
        
        // Mock the validator facade
        $validatorMock = Mockery::mock('Illuminate\Validation\Validator');
        $validatorMock->shouldReceive('validate')
                     ->andReturn($testData);
        
        Validator::shouldReceive('make')
                ->andReturn($validatorMock);

        // Create request with test data
        $request = new Request();
        $request->merge($testData);

        // Create controller instance
        $controller = new \App\Http\Controllers\ProductController();

        // Call the method
        $result = $controller->updateProduct($request, 1);

        // Assert the result contains updated data
        $this->assertEquals('Valid Product Name', $result->product_name);
    }
}