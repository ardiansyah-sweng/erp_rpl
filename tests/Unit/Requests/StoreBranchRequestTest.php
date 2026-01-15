<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\StoreBranchRequest;
use App\Constants\BranchColumns;

use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;

class StoreBranchRequestTest extends TestCase
{
    use RefreshDatabase;

    private function getValidationResult(array $data): \Illuminate\Validation\Validator
    {
        $request = new StoreBranchRequest();
        return Validator::make($data, $request->rules(), $request->messages());
    }

    #[Test]
    public function it_passes_validation_with_valid_data()
    {
        // Arrange
        $validData = [
            BranchColumns::NAME => 'Valid Branch Name',
            BranchColumns::ADDRESS => 'Valid Address',
            BranchColumns::PHONE => 'Valid Phone'
        ];

        // Act
        $validator = $this->getValidationResult($validData);

        // Assert
        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    #[Test]
    public function it_fails_validation_when_name_is_required()
    {
        // Arrange
        $invalidData = [
            BranchColumns::NAME => '',
            BranchColumns::ADDRESS => 'Valid Address',
            BranchColumns::PHONE => 'Valid Phone'
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertContains('Nama cabang wajib diisi.', $validator->errors()->all());
    }

    #[Test]
    public function it_fails_validation_when_address_is_required()
    {
        // Arrange
        $invalidData = [
            BranchColumns::NAME => 'Valid Name',
            BranchColumns::ADDRESS => '',
            BranchColumns::PHONE => 'Valid Phone'
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertContains('Alamat cabang wajib diisi.', $validator->errors()->all());
    }

    #[Test]
    public function it_fails_validation_when_phone_is_required()
    {
        // Arrange
        $invalidData = [
            BranchColumns::NAME => 'Valid Name',
            BranchColumns::ADDRESS => 'Valid Address',
            BranchColumns::PHONE => ''
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertContains('Telepon cabang wajib diisi.', $validator->errors()->all());
    }

    #[Test]
    public function it_fails_validation_when_name_is_too_short()
    {
        // Arrange
        $invalidData = [
            BranchColumns::NAME => 'AB', // Only 2 characters
            BranchColumns::ADDRESS => 'Valid Address',
            BranchColumns::PHONE => 'Valid Phone'
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertContains('Nama cabang minimal 3 karakter.', $validator->errors()->all());
    }

    #[Test]
    public function it_fails_validation_when_address_is_too_short()
    {
        // Arrange
        $invalidData = [
            BranchColumns::NAME => 'Valid Name',
            BranchColumns::ADDRESS => 'AB', // Only 2 characters
            BranchColumns::PHONE => 'Valid Phone'
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertContains('Alamat cabang minimal 3 karakter.', $validator->errors()->all());
    }

    #[Test]
    public function it_fails_validation_when_phone_is_too_short()
    {
        // Arrange
        $invalidData = [
            BranchColumns::NAME => 'Valid Name',
            BranchColumns::ADDRESS => 'Valid Address',
            BranchColumns::PHONE => 'AB' // Only 2 characters
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertContains('Telepon cabang minimal 3 karakter.', $validator->errors()->all());
    }

    #[Test]
    public function it_fails_validation_when_name_is_too_long()
    {
        // Arrange
        $invalidData = [
            BranchColumns::NAME => str_repeat('A', 51), // 51 characters
            BranchColumns::ADDRESS => 'Valid Address',
            BranchColumns::PHONE => 'Valid Phone'
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertContains('Nama cabang maksimal 50 karakter.', $validator->errors()->all());
    }

    #[Test]
    public function it_fails_validation_when_address_is_too_long()
    {
        // Arrange
        $invalidData = [
            BranchColumns::NAME => 'Valid Name',
            BranchColumns::ADDRESS => str_repeat('B', 101), // 101 characters
            BranchColumns::PHONE => 'Valid Phone'
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertContains('Alamat cabang maksimal 100 karakter.', $validator->errors()->all());
    }

    #[Test]
    public function it_fails_validation_when_phone_is_too_long()
    {
        // Arrange
        $invalidData = [
            BranchColumns::NAME => 'Valid Name',
            BranchColumns::ADDRESS => 'Valid Address',
            BranchColumns::PHONE => str_repeat('1', 31) // 31 characters
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertContains('Telepon cabang maksimal 30 karakter.', $validator->errors()->all());
    }

    #[Test]
    public function it_fails_validation_when_name_is_not_unique()
    {
        // Arrange
        Branch::factory()->create([
            BranchColumns::NAME => 'Existing Branch'
        ]);

        $invalidData = [
            BranchColumns::NAME => 'Existing Branch', // Duplicate name
            BranchColumns::ADDRESS => 'Valid Address',
            BranchColumns::PHONE => 'Valid Phone'
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertContains('Nama cabang sudah ada, silakan gunakan nama lain.', $validator->errors()->all());
    }

    #[Test]
    public function it_passes_validation_with_minimum_valid_lengths()
    {
        // Arrange
        $validData = [
            BranchColumns::NAME => 'ABC', // Exactly 3 characters
            BranchColumns::ADDRESS => 'XYZ', // Exactly 3 characters
            BranchColumns::PHONE => '123' // Exactly 3 characters
        ];

        // Act
        $validator = $this->getValidationResult($validData);

        // Assert
        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    #[Test]
    public function it_passes_validation_with_maximum_valid_lengths()
    {
        // Arrange
        $validData = [
            BranchColumns::NAME => str_repeat('A', 50), // Exactly 50 characters
            BranchColumns::ADDRESS => str_repeat('B', 100), // Exactly 100 characters
            BranchColumns::PHONE => str_repeat('1', 30) // Exactly 30 characters
        ];

        // Act
        $validator = $this->getValidationResult($validData);

        // Assert
        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    #[Test]
    public function it_validates_string_type_for_all_fields()
    {
        // Arrange
        $invalidData = [
            BranchColumns::NAME => 123, // Not a string
            BranchColumns::ADDRESS => ['array'], // Not a string
            BranchColumns::PHONE => true // Not a string
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertContains('Nama cabang harus berupa teks.', $validator->errors()->all());
        $this->assertContains('Alamat cabang harus berupa teks.', $validator->errors()->all());
        $this->assertContains('Telepon cabang harus berupa teks.', $validator->errors()->all());
    }

    #[Test]
    public function it_has_correct_authorization()
    {
        // Arrange
        $request = new StoreBranchRequest();

        // Act
        $authorized = $request->authorize();

        // Assert
        $this->assertTrue($authorized);
    }

    #[Test]
    public function it_has_correct_custom_attributes()
    {
        // Arrange
        $request = new StoreBranchRequest();
        $expectedAttributes = [
            BranchColumns::NAME => 'nama cabang',
            BranchColumns::ADDRESS => 'alamat cabang',
            BranchColumns::PHONE => 'telepon cabang'
        ];

        // Act
        $attributes = $request->attributes();

        // Assert
        $this->assertEquals($expectedAttributes, $attributes);
    }

    #[Test]
    public function it_trims_input_data_during_preparation()
    {
        // Arrange
        $request = new StoreBranchRequest();
        $request->replace([
            BranchColumns::NAME => '  Test Name  ',
            BranchColumns::ADDRESS => '  Test Address  ',
            BranchColumns::PHONE => '  Test Phone  '
        ]);

        // Act - Call the protected method using reflection
        $reflection = new \ReflectionClass($request);
        $method = $reflection->getMethod('prepareForValidation');
        $method->setAccessible(true);
        $method->invoke($request);

        // Assert
        $this->assertEquals('Test Name', $request->input(BranchColumns::NAME));
        $this->assertEquals('Test Address', $request->input(BranchColumns::ADDRESS));
        $this->assertEquals('Test Phone', $request->input(BranchColumns::PHONE));
    }

    #[Test]
    public function it_handles_null_values_during_preparation()
    {
        // Arrange
        $request = new StoreBranchRequest();
        $request->replace([
            BranchColumns::NAME => null,
            BranchColumns::ADDRESS => null,
            BranchColumns::PHONE => null
        ]);

        // Act - Call the protected method using reflection
        $reflection = new \ReflectionClass($request);
        $method = $reflection->getMethod('prepareForValidation');
        $method->setAccessible(true);
        $method->invoke($request);

        // Assert - trim() converts null to empty string, so we expect empty strings
        $this->assertEquals('', $request->input(BranchColumns::NAME));
        $this->assertEquals('', $request->input(BranchColumns::ADDRESS));
        $this->assertEquals('', $request->input(BranchColumns::PHONE));
    }

    #[Test]
    public function it_validates_multiple_errors_simultaneously()
    {
        // Arrange
        $invalidData = [
            BranchColumns::NAME => '', // Required error
            BranchColumns::ADDRESS => 'AB', // Min length error
            BranchColumns::PHONE => str_repeat('1', 31) // Max length error
        ];

        // Act
        $validator = $this->getValidationResult($invalidData);

        // Assert
        $this->assertTrue($validator->fails());
        $errors = $validator->errors()->all();
        
        $this->assertContains('Nama cabang wajib diisi.', $errors);
        $this->assertContains('Alamat cabang minimal 3 karakter.', $errors);
        $this->assertContains('Telepon cabang maksimal 30 karakter.', $errors);
        $this->assertCount(3, $errors);
    }

    #[Test]
    public function it_handles_special_characters_in_validation()
    {
        // Arrange
        $validData = [
            BranchColumns::NAME => 'Branch & Co. Ltd.',
            BranchColumns::ADDRESS => 'Jl. Sudirman No. 123/A-B',
            BranchColumns::PHONE => '+62-21-123-4567'
        ];

        // Act
        $validator = $this->getValidationResult($validData);

        // Assert
        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }
}
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

