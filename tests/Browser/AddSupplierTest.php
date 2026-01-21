<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AddSupplierTest extends DuskTestCase
{
    protected $user;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations
        $this->artisan('migrate:fresh');

        // Create users table if not exists
        if (!Schema::hasTable('users')) {
            DB::statement('
                CREATE TABLE users (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    email_verified_at TIMESTAMP NULL,
                    password VARCHAR(255) NOT NULL,
                    remember_token VARCHAR(100) NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                )
            ');
        }

        // Create a user for authentication
        $this->user = User::factory()->create();
    }

    /**
     * Test user can add new supplier successfully.
     */
    public function test_user_can_add_new_supplier()
    {
        $supplierData = [
            'supplier_id' => 'SUP001',
            'company_name' => 'PT. Vendor Sejahtera',
            'address' => 'Jl. Uji Coba No. 42, Jakarta',
            'phone_number' => '081234567890',
            'bank_account' => '1234567890 (BCA)',
        ];

        $this->browse(function (Browser $browser) use ($supplierData) {
            $browser->loginAs($this->user)
                    // 1. Visit the add supplier page
                    ->visit('/supplier/add')
                    ->assertSee('Tambah Supplier')

                    // 2. Fill out the form using 'name' attribute
                    ->type('input[name="supplier_id"]', $supplierData['supplier_id'])
                    ->type('input[name="company_name"]', $supplierData['company_name'])
                    ->type('input[name="address"]', $supplierData['address'])
                    ->type('input[name="phone_number"]', $supplierData['phone_number'])
                    ->type('input[name="bank_account"]', $supplierData['bank_account'])

                    // 3. Click the Add button that triggers validateForm()
                    ->click('button[onclick="validateForm()"]')

                    // 4. Wait for success message (max 10 seconds)
                    ->waitForText('Supplier Berhasil Di Tambahkan', 10)
                    
                    // 5. Verify still on the same page after redirect back
                    ->assertPathIs('/supplier/add');
        });

        // 6. Assert database - IMPORTANT: kolom di database adalah 'telephone' bukan 'phone_number'
        $this->assertDatabaseHas('suppliers', [
            'supplier_id' => $supplierData['supplier_id'],
            'company_name' => $supplierData['company_name'],
            'address' => $supplierData['address'],
            'telephone' => $supplierData['phone_number'], // Database column is 'telephone'
            'bank_account' => $supplierData['bank_account'],
        ]);
    }

    /**
     * Test validation error when supplier_id is empty.
     */
    public function test_validation_error_when_supplier_id_empty()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/supplier/add')
                    
                    // Fill all fields except supplier_id
                    ->type('input[name="company_name"]', 'PT Test')
                    ->type('input[name="address"]', 'Jl. Test')
                    ->type('input[name="phone_number"]', '081234567890')
                    ->type('input[name="bank_account"]', '1234567890')
                    
                    // Click Add button
                    ->click('button[onclick="validateForm()"]')
                    
                    // Should show validation error
                    ->waitForText('ID Supplier harus diisi', 5)
                    ->assertSee('ID Supplier harus diisi');
        });
    }

    /**
     * Test validation error when phone format is invalid.
     */
    public function test_validation_error_when_phone_invalid()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/supplier/add')
                    
                    // Fill with invalid phone (less than 10 digits)
                    ->type('input[name="supplier_id"]', 'SUP002')
                    ->type('input[name="company_name"]', 'PT Test')
                    ->type('input[name="address"]', 'Jl. Test')
                    ->type('input[name="phone_number"]', '12345') // Invalid
                    ->type('input[name="bank_account"]', '1234567890')
                    
                    // Click Add button
                    ->click('button[onclick="validateForm()"]')
                    
                    // Should show phone validation error
                    ->waitForText('Nomor Telephone harus diisi(10-13 digit)', 5)
                    ->assertSee('Nomor Telephone harus diisi(10-13 digit)');
        });
    }
}