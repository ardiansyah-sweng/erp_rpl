<?php

namespace Tests\Browser;

use App\Models\Merk;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

class MerkTest extends DuskTestCase
{
    /**
     * Setup - Run migrations before each test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate', ['--env' => 'testing']);
        
        // Clean up merks table before each test
        $tableName = config('db_tables.merk', 'merks');
        Schema::disableForeignKeyConstraints();
        DB::table($tableName)->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test 1: View Merk List and Search Functionality
     */
    #[Test]
    public function testViewMerkListAndSearch()
    {
        // Create test data
        Merk::create(['merk' => 'Samsung', 'is_active' => 1]);
        Merk::create(['merk' => 'Apple', 'is_active' => 1]);
        Merk::create(['merk' => 'LG', 'is_active' => 0]);

        $this->browse(function (Browser $browser) {
            // Test: View list page with all data
            $browser->visit('/merks')
                    ->assertSee('List Merk')
                    ->assertSee('Samsung')
                    ->assertSee('Apple')
                    ->assertSee('LG')
                    ->assertPresent('table.table-bordered')
                    ->screenshot('01-merk-list-page');
            
            // Test: Search functionality
            $browser->type('search', 'Samsung')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee('Samsung')
                    ->assertDontSee('Apple')
                    ->assertDontSee('LG')
                    ->screenshot('02-merk-search-result');
        });
    }

    /**
     * Test 2: Create and Update Merk
     */
    #[Test]
    public function testCreateAndUpdateMerk()
    {
        $this->browse(function (Browser $browser) {
            // Test: Navigate to create page
            $browser->visit('/merks/create')
                    ->assertPresent('input[name="merk"]')
                    ->assertPresent('input[type="checkbox"]')
                    ->screenshot('03-create-merk-form');
            
            // Test: Create new merk
            $browser->type('merk', 'Sony Electronics')
                    ->check('is_active')
                    ->click('button[type="submit"]')
                    ->pause(2000)
                    ->screenshot('04-after-create');
        });

        // Verify data in database
        $this->assertDatabaseHas('merks', [
            'merk' => 'Sony Electronics',
            'is_active' => 1
        ]);

        // Get created merk
        $merk = Merk::where('merk', 'Sony Electronics')->first();
        $this->assertNotNull($merk);
        
        // Test: Update merk
        $this->browse(function (Browser $browser) use ($merk) {
            $browser->visit('/merks/' . $merk->id . '/edit')
                    ->assertInputValue('merk', 'Sony Electronics')
                    ->screenshot('05-edit-merk-form');
            
            $browser->clear('merk')
                    ->type('merk', 'Sony Corp')
                    ->uncheck('is_active')
                    ->click('button[type="submit"]')
                    ->pause(2000)
                    ->screenshot('06-after-update');
        });

        // Verify updated data in database
        $this->assertDatabaseHas('merks', [
            'id' => $merk->id,
            'merk' => 'Sony Corp',
            'is_active' => 0
        ]);
    }

    /**
     * Test 3: View Detail and Delete Merk
     */
    #[Test]
    public function testViewDetailAndDeleteMerk()
    {
        // Create test merk
        $merk = Merk::create([
            'merk' => 'Panasonic',
            'is_active' => 1
        ]);

        // Test: View detail page
        $this->browse(function (Browser $browser) use ($merk) {
            $browser->visit('/merks/' . $merk->id)
                    ->assertSee('Panasonic')
                    ->assertSee($merk->id)
                    ->screenshot('07-merk-detail-page');
        });

        // Test: Delete merk
        $this->browse(function (Browser $browser) use ($merk) {
            $browser->visit('/merks')
                    ->pause(1000)
                    ->screenshot('08-before-delete')
                    ->press('Delete')
                    ->acceptDialog()
                    ->pause(2000)
                    ->screenshot('09-after-delete')
                    ->assertDontSee('Panasonic');
        });

        // Verify data deleted from database
        $this->assertDatabaseMissing('merks', [
            'id' => $merk->id
        ]);
    }

    /**
     * Clean up after tests
     */
    protected function tearDown(): void
    {
        // Truncate merks table
        $tableName = config('db_tables.merk', 'merks');
        Schema::disableForeignKeyConstraints();
        DB::table($tableName)->truncate();
        Schema::enableForeignKeyConstraints();
        
        parent::tearDown();
    }
}