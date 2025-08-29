<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Branch;

class BranchCreateTest extends DuskTestCase
{
    /**
     * Test create branch with maximum length address (100 chars)
     */
    public function test_create_branch_with_maximum_length_address(): void
    {
        $name = 'MAXADDR';
        $maxLengthAddress = str_repeat('A', 100); // 100 karakter
        $telephone = '0812345678';

        $this->browse(function (Browser $browser) use ($name, $maxLengthAddress, $telephone) {
            $browser->visit('/branches/create')
                ->waitFor('form[id="branchForm"]', 10)
                ->type('branch_name', $name)
                ->type('branch_address', $maxLengthAddress)
                ->type('branch_telephone', $telephone);

            // Check if checkbox exists and check it (if not already checked)
            if ($browser->element('input[name="branch_status"]')) {
                $browser->check('branch_status');
            }

            // Submit form
            $browser->press('Simpan')
                    ->pause(3000); // Wait for form processing

            // Skenario sukses: redirect ke index dan data tersimpan
            $browser->assertPathIs('/branches')
                ->assertSee('berhasil ditambahkan');

            $perPage = 10; // atau sesuai pagination kamu
            $lastPage = ceil(Branch::count() / $perPage);

            // Database assertion
            $this->assertDatabaseHas('branches', [
                'branch_name' => $name,
                'branch_address' => $maxLengthAddress,
                'branch_telephone' => $telephone,
            ]);

            // Cleanup
            Branch::where('branch_name', $name)->delete();
        });
    }

    /**
     * Test create branch with minimum length branch address (3 chars)
     */
    public function test_create_branch_with_minimum_length_address(): void
    {
        $name = 'MINADDR';
        $minLengthAddress = 'JL.'; // 3 karakter
        $telephone = '0812345678';

        $this->browse(function (Browser $browser) use ($name, $minLengthAddress, $telephone) {
            $browser->visit('/branches/create')
                ->waitFor('form[id="branchForm"]', 10)
                ->type('branch_name', $name)
                ->type('branch_address', $minLengthAddress)
                ->type('branch_telephone', $telephone);

        // Check if checkbox exists and check it (if not already checked)
        if ($browser->element('input[name="branch_status"]')) {
            $browser->check('branch_status');
        }

        // Submit form
        $browser->press('Simpan')
                ->pause(3000); // Wait for form processing

        // Skenario sukses: redirect ke index dan data tersimpan
        $browser->assertPathIs('/branches')
            ->assertSee('berhasil ditambahkan');

        $perPage = 10; // atau sesuai pagination kamu
        $lastPage = ceil(Branch::count() / $perPage);

        $browser->visit('/branches?page=' . $lastPage)
            ->assertSee($name)
            ->assertSee($minLengthAddress)
            ->assertSee($telephone);

            // Database assertion
            $this->assertDatabaseHas('branches', [
                'branch_name' => $name,
                'branch_address' => $minLengthAddress,
                'branch_telephone' => $telephone,
            ]);

            // Cleanup
            Branch::where('branch_name', $name)->delete();
        });
    }

    /**
     * Test create branch with empty name
     */
    public function test_create_branch_with_empty_name(): void
    {
        $name = '';
        $address = 'Jl. Minimal 123';
        $telephone = '0812345678';

        $this->browse(function (Browser $browser) use ($name, $address, $telephone) {
            $browser->visit('/branches/create')
                ->waitFor('form[id="branchForm"]', 10)
                ->type('branch_name', $name)
                ->type('branch_address', $address)
                ->type('branch_telephone', $telephone)
                ->check('is_active')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/branches/create')
                ->assertSee('Nama cabang wajib diisi');
        });
    }

    /**
     * Test create branch with duplicate name
     */
    public function test_create_branch_with_duplicate_name(): void
    {
        $name = 'DUPLICATE CABANG';
        $address = 'Jl. Minimal 123';
        $telephone = '0812345678';

        // Buat branch pertama
        Branch::create([
            'branch_name' => $name,
            'branch_address' => $address,
            'branch_telephone' => $telephone,
            'is_active' => 1
        ]);

        $this->browse(function (Browser $browser) use ($name, $address, $telephone) {
            $browser->visit('/branches/create')
                ->waitFor('form[id="branchForm"]', 10)
                ->type('branch_name', $name)
                ->type('branch_address', $address)
                ->type('branch_telephone', $telephone)
                ->check('is_active')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/branches/create')
                ->assertSee('Nama cabang sudah ada, silakan gunakan nama lain');
        });

        // Cleanup
        Branch::where('branch_name', $name)->delete();
    }

    /**
     * Test create branch with name too long (>50 karakter)
     */
    public function test_create_branch_with_name_too_long(): void
    {
        $name = str_repeat('A', 51); // 51 karakter
        $address = 'Jl. Minimal 123';
        $telephone = '0812345678';

        $this->browse(function (Browser $browser) use ($name, $address, $telephone) {
            $browser->visit('/branches/create')
                ->waitFor('form[id="branchForm"]', 10)
                ->type('branch_name', $name)
                ->type('branch_address', $address)
                ->type('branch_telephone', $telephone)
                ->check('is_active')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/branches/create')
                ->assertSee('Nama cabang maksimal 50 karakter');
        });
    }
    /**
     * Test create branch with name too short (<3 karakter)
     */
    public function test_create_branch_with_name_too_short(): void
    {
        $name = 'AB'; // 2 karakter
        $address = 'Jl. Minimal 123';
        $telephone = '0812345678';

        $this->browse(function (Browser $browser) use ($name, $address, $telephone) {
            $browser->visit('/branches/create')
                ->waitFor('form[id="branchForm"]', 10)
                ->type('branch_name', $name)
                ->type('branch_address', $address)
                ->type('branch_telephone', $telephone)
                ->check('is_active')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/branches/create')
                ->assertSee('Nama cabang minimal 3 karakter');
        });
    }
    /**
     * Test user can access branch create form
     */
    public function test_user_can_access_branch_create_form(): void
    {
        $this->browse(function (Browser $browser) {
        $browser->visit('/branches/create')
            ->assertSee('Formulir Tambah Cabang')
            ->assertPresent('input[name="branch_name"]')
            ->assertPresent('textarea[name="branch_address"]')
            ->assertPresent('input[name="branch_telephone"]')
            ->assertPresent('input[name="is_active"]')
            ->assertPresent('button[type="submit"]');
        });
    }

    /**
     * Test create branch with minimum length name (3 chars)
     */
    public function test_create_branch_with_minimum_length_name(): void
    {
        $minLengthName = 'AMX'; // 3 karakter
        $address = 'Jl. Minimal 123';
        $telephone = '0812345678';

        $this->browse(function (Browser $browser) use ($minLengthName, $address, $telephone) {
            $browser->visit('/branches/create')
                ->waitFor('form[id="branchForm"]', 10)
                ->assertSee('Tambah Cabang')
                ->assertSee('Simpan')
                ->type('branch_name', $minLengthName)
                ->type('branch_address', $address)
                ->type('branch_telephone', $telephone);

            // Check if checkbox exists and check it (if not already checked)
            if ($browser->element('input[name="branch_status"]')) {
                $browser->check('branch_status');
            }

            // Submit form
            $browser->press('Simpan')
                    ->pause(3000); // Wait for form processing

        // Skenario sukses: redirect ke index dan data tersimpan
        $browser->assertPathIs('/branches')
            ->assertSee('berhasil ditambahkan');

        $perPage = 10; // atau sesuai pagination kamu
        $lastPage = ceil(Branch::count() / $perPage);

        $browser->visit('/branches?page=' . $lastPage)
            ->assertSee($minLengthName)
            ->assertSee($address)
            ->assertSee($telephone);

        // Database assertion
        $this->assertDatabaseHas('branches', [
        'branch_name' => $minLengthName,
        'branch_address' => $address,
        'branch_telephone' => $telephone,
        ]);

        // Cleanup
        Branch::where('branch_name', $minLengthName)->delete();
        });
    }

    /**
     * Test create branch with maximum length name (50 chars)
     */
    public function test_create_branch_with_maximum_length_name(): void
    {
        $maxLengthName = str_repeat('A', 50); // 50 karakter
        $address = 'Jl. Minimal 123';
        $telephone = '0812345678';

        $this->browse(function (Browser $browser) use ($maxLengthName, $address, $telephone) {
            $browser->visit('/branches/create')
                ->waitFor('form[id="branchForm"]', 10)
                ->assertSee('Tambah Cabang')
                ->assertSee('Simpan')
                ->type('branch_name', $maxLengthName)
                ->type('branch_address', $address)
                ->type('branch_telephone', $telephone);

            // Check if checkbox exists and check it (if not already checked)
            if ($browser->element('input[name="branch_status"]')) {
                $browser->check('branch_status');
            }

            // Submit form
            $browser->press('Simpan')
                    ->pause(3000); // Wait for form processing

        // Skenario sukses: redirect ke index dan data tersimpan
        $browser->assertPathIs('/branches')
            ->assertSee('berhasil ditambahkan');

        $perPage = 10; // atau sesuai pagination kamu
        $lastPage = ceil(Branch::count() / $perPage);

        $browser->visit('/branches?page=' . $lastPage)
            ->assertSee($maxLengthName)
            ->assertSee($address)
            ->assertSee($telephone);

        // Database assertion
        $this->assertDatabaseHas('branches', [
            'branch_name' => $maxLengthName,
            'branch_address' => $address,
            'branch_telephone' => $telephone,
        ]);

        // Cleanup
        Branch::where('branch_name', $maxLengthName)->delete();
        });
    }

    /**
     * Test create branch with valid data
     */
    public function test_create_branch_with_valid_data(): void
    {
        // Generate unique branch name to avoid unique constraint
        $uniqueName = 'Test Branch Dusk ' . now()->format('Y-m-d H:i:s');
        $uniqueAddress = 'Jl. Testing Dusk No. 123 ' . now()->format('H:i:s');
        $telephone  = '081234567890';

        $this->browse(function (Browser $browser) use ($uniqueName, $uniqueAddress, $telephone) {
            $browser->visit('/branches/create')
                    ->waitFor('form[id="branchForm"]', 10)
                    ->assertSee('Tambah Cabang')
                    ->assertSee('Simpan');

            // Fill form with valid data
            $browser->type('branch_name', $uniqueName)
                    ->type('branch_address', $uniqueAddress)
                    ->type('branch_telephone', $telephone);
            
            // Check if checkbox exists and check it (if not already checked)
            if ($browser->element('input[name="branch_status"]')) {
                $browser->check('branch_status');
            }
            
            // Submit form
            $browser->press('Simpan')
                    ->pause(3000); // Wait for form processing
            
            // Assert redirect ke halaman index
            $browser->assertPathIs('/branches')
                    ->assertSee('berhasil ditambahkan');

            // **🔥 VERIFY DATA APPEARS ON INDEX PAGE**
            // NOTE: Database assertion sudah PASS, UI assertion optional
            // Uncomment below when UI display issue resolved

            $perPage = 10; // atau sesuai pagination kamu
            $lastPage = ceil(Branch::count() / $perPage);

            $browser->visit('/branches?page=' . $lastPage)
                    ->assertSee($uniqueName)
                    ->assertSee($uniqueAddress)
                    ->assertSee($telephone);

            // **🎯 SUCCESS: Verify data was saved in database**
            $this->assertDatabaseHas('branches', [
                'branch_name' => $uniqueName,
                'branch_address' => $uniqueAddress,
                'branch_telephone' => $telephone
            ]);

        });

        // Cleanup after test
        Branch::where('branch_name', 'LIKE', 'Test Branch Dusk%')->delete();
    }
}
