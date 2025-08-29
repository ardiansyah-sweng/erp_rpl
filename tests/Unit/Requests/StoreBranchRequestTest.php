<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\StoreBranchRequest;
use App\Constants\BranchColumns;
use Illuminate\Support\Facades\Validator;

class StoreBranchRequestTest extends TestCase
{
    /**
     * Test validation rules with valid data
     */
    public function test_validation_passes_with_valid_data()
    {
        $request = new StoreBranchRequest();
        
        $validator = Validator::make([
            BranchColumns::NAME => 'Cabang Jakarta Pusat',
            BranchColumns::ADDRESS => 'Jl. Sudirman No. 123, Jakarta',
            BranchColumns::PHONE => '021-12345678'
        ], $request->rules());

        $this->assertTrue($validator->passes());
        $this->assertCount(0, $validator->errors());
    }

    /**
     * Test validation fails with empty required fields
     */
    public function test_validation_fails_with_empty_required_fields()
    {
        $request = new StoreBranchRequest();
        
        $validator = Validator::make([
            BranchColumns::NAME => '',
            BranchColumns::ADDRESS => '',
            BranchColumns::PHONE => ''
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has(BranchColumns::NAME));
        $this->assertTrue($validator->errors()->has(BranchColumns::ADDRESS));
        $this->assertTrue($validator->errors()->has(BranchColumns::PHONE));
    }

    /**
     * Test validation fails with too short data
     */
    public function test_validation_fails_with_too_short_data()
    {
        $request = new StoreBranchRequest();
        
        $validator = Validator::make([
            BranchColumns::NAME => 'AB', // Too short (min:3)
            BranchColumns::ADDRESS => 'XY', // Too short (min:3)
            BranchColumns::PHONE => '12' // Too short (min:3)
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has(BranchColumns::NAME));
        $this->assertTrue($validator->errors()->has(BranchColumns::ADDRESS));
        $this->assertTrue($validator->errors()->has(BranchColumns::PHONE));
    }

    /**
     * Test validation fails with too long data
     */
    public function test_validation_fails_with_too_long_data()
    {
        $request = new StoreBranchRequest();
        
        $validator = Validator::make([
            BranchColumns::NAME => str_repeat('A', 51), // Too long (max:50)
            BranchColumns::ADDRESS => str_repeat('B', 101), // Too long (max:100)
            BranchColumns::PHONE => str_repeat('1', 31) // Too long (max:30)
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has(BranchColumns::NAME));
        $this->assertTrue($validator->errors()->has(BranchColumns::ADDRESS));
        $this->assertTrue($validator->errors()->has(BranchColumns::PHONE));
    }

    /**
     * Test custom error messages are returned
     */
    public function test_custom_error_messages_are_returned()
    {
        $request = new StoreBranchRequest();
        
        $validator = Validator::make([
            BranchColumns::NAME => '',
        ], $request->rules(), $request->messages());

        $this->assertTrue($validator->fails());
        $this->assertEquals(
            'Nama cabang wajib diisi.',
            $validator->errors()->first(BranchColumns::NAME)
        );
    }

    /**
     * Test authorize method returns true
     */
    public function test_authorize_returns_true()
    {
        $request = new StoreBranchRequest();
        $this->assertTrue($request->authorize());
    }
}
