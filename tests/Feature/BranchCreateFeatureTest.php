<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Constants\BranchColumns;

class BranchCreateFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create form is accessible and displays correctly
     */
    public function test_branch_create_form_displays_correctly(): void
    {
        $response = $this->get('/branches/create');

        $response->assertStatus(200)
                 ->assertSee('Formulir Tambah Cabang')
                 ->assertSee('Nama Cabang')
                 ->assertSee('Alamat')
                 ->assertSee('Telepon')
                 ->assertSee('Aktif')
                 ->assertSee('Simpan');
    }

    /**
     * Test successful branch creation via form submission
     */
    public function test_branch_creation_via_form_submission(): void
    {
        $branchData = [
            'branch_name' => 'Test Branch Created',
            'branch_address' => 'Jl. Test Create No. 123', 
            'branch_telephone' => '081234567890',
            'branch_status' => '1'
        ];

        $response = $this->post('/branches', $branchData);

        $response->assertRedirect('/branches');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('branches', [
            'branch_name' => 'Test Branch Created',
            'branch_address' => 'Jl. Test Create No. 123',
            'branch_telephone' => '081234567890',
            'is_active' => true
        ]);
    }

    /**
     * Test form validation for required fields
     */
    public function test_form_validation_for_required_fields(): void
    {
        $response = $this->post('/branches', []);

        $response->assertSessionHasErrors([
            'branch_name',
            'branch_address', 
            'branch_telephone'
        ]);

        $this->assertEquals(0, Branch::count());
    }

    /**
     * Test form validation for duplicate branch name
     */
    public function test_form_validation_for_duplicate_branch_name(): void
    {
        // Create existing branch
        Branch::factory()->create([
            BranchColumns::NAME => 'Existing Branch Name'
        ]);

        $response = $this->post('/branches', [
            'branch_name' => 'Existing Branch Name',
            'branch_address' => 'Test Address',
            'branch_telephone' => '081234567890'
        ]);

        $response->assertSessionHasErrors(['branch_name']);
        $this->assertEquals(1, Branch::count()); // Only the existing one
    }

    /**
     * Test form handles old input on validation errors
     */
    public function test_form_handles_old_input_on_validation_errors(): void
    {
        $invalidData = [
            'branch_name' => 'AB', // Too short
            'branch_address' => 'Valid Address', 
            'branch_telephone' => '123' // Too short
        ];

        $response = $this->post('/branches', $invalidData);

        $response->assertRedirect()
                 ->assertSessionHasErrors()
                 ->assertSessionHasInput('branch_address', 'Valid Address');
    }

    /**
     * Test successful creation redirects with success message
     */
    public function test_successful_creation_redirects_with_success_message(): void
    {
        $response = $this->post('/branches', [
            'branch_name' => 'Success Test Branch',
            'branch_address' => 'Success Test Address',
            'branch_telephone' => '081234567890'
        ]);

        $response->assertRedirect('/branches')
                 ->assertSessionHas('success', 'Cabang berhasil ditambahkan!');
    }

    /**
     * Test branch creation with inactive status
     */
    public function test_branch_creation_with_inactive_status(): void
    {
        $response = $this->post('/branches', [
            'branch_name' => 'Inactive Branch Test',
            'branch_address' => 'Inactive Test Address',
            'branch_telephone' => '081234567890',
            'branch_status' => '0'  // Explicitly set to 0 for inactive
        ]);

        $response->assertRedirect('/branches');

        $this->assertDatabaseHas('branches', [
            'branch_name' => 'Inactive Branch Test',
            'is_active' => 0  // Should be 0, not false
        ]);
    }

    /**
     * Test complete workflow: create -> verify in index
     */
    public function test_complete_create_workflow(): void
    {
        // Step 1: Create branch
        $this->post('/branches', [
            'branch_name' => 'Workflow Test Branch',
            'branch_address' => 'Workflow Test Address', 
            'branch_telephone' => '081234567890',
            'branch_status' => '1'
        ]);

        // Step 2: Verify appears in index
        $response = $this->get('/branches');
        
        $response->assertStatus(200)
                 ->assertSee('Workflow Test Branch')
                 ->assertSee('Workflow Test Address')
                 ->assertSee('081234567890');
    }
}
