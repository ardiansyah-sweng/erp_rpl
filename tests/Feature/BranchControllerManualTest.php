<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BranchControllerManualTest extends TestCase
{
    use DatabaseTransactions;

    public function test_manual_branch_creation_with_same_database()
    {
        // Test with same database as Dusk (erp_rpl_test) 
        config(['database.default' => 'mysql']);
        config(['database.connections.mysql.database' => 'erp_rpl_test']);
        
        $response = $this->post('/branches', [
            'branch_name' => 'Manual Test Branch',
            'branch_address' => 'Manual Test Address', 
            'branch_telephone' => '08123456789'
        ]);

        $response->assertRedirect('/branches');
        
        $this->assertDatabaseHas('branches', [
            'branch_name' => 'Manual Test Branch',
            'branch_address' => 'Manual Test Address',
            'branch_telephone' => '08123456789'
        ]);
    }
}
