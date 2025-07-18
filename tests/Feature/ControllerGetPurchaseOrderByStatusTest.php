<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ControllerGetPurchaseOrderByStatusTest extends TestCase
{
    public function test_all_existing_statuses_return_success()
    {
        $statuses = DB::table('purchase_order')->distinct()->pluck('status');

        foreach ($statuses as $status) {
            $response = $this->get("/purchase-order/status/{$status}");

            $response->assertStatus(200, "Status '{$status}' seharusnya mengembalikan 200 OK");
            $response->assertJson([
                'status' => $status,
            ]);
            $response->assertJsonStructure([
                'status',
                'count',
                'data' => [
                    '*' => ['supplier_id', 'status', 'total']
                ]
            ]);
        }
    }

    public function test_unknown_status_returns_404()
    {
        $unknownStatus = 'this_status_does_not_exist';

        // Pastikan status ini tidak ada di tabel
        $this->assertFalse(
            DB::table('purchase_order')->where('status', $unknownStatus)->exists(),
            "Status '{$unknownStatus}' harus tidak ada di database untuk test ini."
        );

        $response = $this->get("/purchase-order/status/{$unknownStatus}");

        $response->assertStatus(404);
        $response->assertJson([
            'message' => "No purchase orders found with status: {$unknownStatus}"
        ]);
    }
}
