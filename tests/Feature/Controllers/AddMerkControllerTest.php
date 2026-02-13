<?php

namespace Tests\Feature\Controllers;

use App\Models\Merk;
use App\Constants\Messages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddMerkControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['db_tables.merk' => 'merks']);
    }

    /**
     * Test Utama: Add merk dengan nama saja (default is_active = true)
     */
    public function test_can_add_merk_with_name_only_web_request()
    {
        // Arrange
        $data = ['merk' => 'Toyota'];

        // Act
        $response = $this->post(route('merk.addMerk'), $data);

        // Assert
        $response->assertRedirect(route('merk.index'));
        $response->assertSessionHas('success', Messages::MERK_CREATED);
        
        $this->assertDatabaseHas('merks', [
            'merk' => 'Toyota',
            'is_active' => 1,
        ]);
    }

    /**
     * Test Utama: Add merk dengan nama dan active status
     */
    public function test_can_add_merk_with_name_and_active_status_web_request()
    {
        // Arrange
        $data = [
            'merk' => 'Honda',
            'active' => false,
        ];

        // Act
        $response = $this->post(route('merk.addMerk'), $data);

        // Assert
        $response->assertRedirect(route('merk.index'));
        $response->assertSessionHas('success', Messages::MERK_CREATED);
        
        $this->assertDatabaseHas('merks', [
            'merk' => 'Honda',
            'is_active' => 0,
        ]);
    }

    /**
     * Test Utama: Validasi - Merk tidak boleh kosong (required)
     */
    public function test_cannot_add_merk_without_name_web_request()
    {
        // Arrange
        $data = ['merk' => ''];

        // Act
        $response = $this->post(route('merk.addMerk'), $data);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors('merk');
    }

    /**
     * Test Utama: Validasi - Nama merk tidak boleh lebih dari 100 karakter (max:100)
     */
    public function test_cannot_add_merk_with_name_exceeding_max_length_web_request()
    {
        // Arrange
        $data = ['merk' => str_repeat('a', 101)];

        // Act
        $response = $this->post(route('merk.addMerk'), $data);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors('merk');
    }
}
