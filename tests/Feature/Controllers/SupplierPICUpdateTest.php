<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SupplierPIController;

class SupplierPICUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_supplier_pic_successfully()
    {
        $router = $this->app['router'];
        $router->put('/test-update-pic/{id}', [SupplierPIController::class, 'updateSupplierPICDetail']);
        $router->getRoutes()->refreshNameLookups();

        DB::statement("DROP VIEW IF EXISTS supplier");
        DB::statement("CREATE VIEW supplier AS SELECT * FROM suppliers");

        DB::statement("DROP VIEW IF EXISTS supplier_pic");
        DB::statement("CREATE VIEW supplier_pic AS SELECT * FROM supplier_pics");

        $shortSupplierId = 'S001'; 

        DB::table('suppliers')->insert([
            'supplier_id'   => $shortSupplierId, 
            'company_name'  => 'PT Mencari Bug Abadi',
            'address'       => 'Jl. Test No. 1',
            'telephone'     => '08123456789',
            'bank_account'  => '123-456-7890', 
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $picId = DB::table('supplier_pics')->insertGetId([
            'supplier_id'   => $shortSupplierId,
            'name'          => 'Budi Versi Lama',
            'email'         => 'budi@lama.com',
            'phone_number'  => '0811111111',
            'is_active'     => 1,
            'assigned_date' => now()->toDateString(), 
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $payload = [
            'supplier_id'   => $shortSupplierId,
            'name'          => 'Budi Versi Baru',
            'email'         => 'budi@baru.com',
            'phone_number'  => '0899999999',
            'assigned_date' => now()->toDateString(),
        ];

        $response = $this->put("/test-update-pic/{$picId}", $payload);

        $response->assertStatus(200); 

        $this->assertDatabaseHas('supplier_pics', [
            'id'    => $picId,
            'name'  => 'Budi Versi Baru',
            'email' => 'budi@baru.com',
        ]);
    }
}