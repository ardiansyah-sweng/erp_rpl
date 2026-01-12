<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Validator;

class CategoryAddValidationTest extends TestCase
{
    protected function tearDown(): void
    {
        // Ensure Mockery expectations are verified and closed
        Mockery::close();
        parent::tearDown();
    }

    public function test_validation_fails_when_category_is_empty()
    {
        $rules = [
            'category' => 'required|string|min:3|unique:category,category',
        ];

        $validator = Validator::make([], $rules);

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('category'));
    }

    public function test_validation_fails_when_category_too_short()
    {
        $rules = [
            'category' => 'required|string|min:3|unique:category,category',
        ];

        $validator = Validator::make([
            'category' => 'ab'
        ], $rules);

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('category'));
        $this->assertEquals('The category field must be at least 3 characters.', $validator->errors()->first('category'));
    }

    public function test_validation_fails_when_category_already_exists()
    {
        $rules = [
            'category' => 'required|string|min:3|unique:category,category',
        ];

        // Mock the DatabasePresenceVerifier so unique rule reports the value exists
    $verifierMock = Mockery::mock('Illuminate\\Validation\\DatabasePresenceVerifier');
    // Validator may call setConnection on the presence verifier; allow it
    $verifierMock->shouldReceive('setConnection')->andReturnNull();
    // Validator may call getCount when evaluating unique rule; return 1 to indicate exists
    $verifierMock->shouldReceive('getCount')->andReturn(1);
    $verifierMock->shouldReceive('exists')->andReturn(true);

        // Save current verifier and set the mock
        $current = Validator::getPresenceVerifier();
        Validator::setPresenceVerifier($verifierMock);

        try {
            $validator = Validator::make([
                'category' => 'Existing Category'
            ], $rules);

            $this->assertTrue($validator->fails());
            $this->assertTrue($validator->errors()->has('category'));
        } finally {
            // Restore original verifier
            Validator::setPresenceVerifier($current);
        }
    }
}
