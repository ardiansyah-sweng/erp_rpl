<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AssortmentProduction;

class AssortProductionSearchTest extends TestCase
{
    protected $testRecord;

    protected function setUp(): void
    {
        parent::setUp();

        // Tambahkan satu data khusus untuk kebutuhan test
        $this->testRecord = AssortmentProduction::create([
            'production_number' => 'PRD001',
            'sku' => 'SKU-001',
            'in_production' => true,
            'branch_id' => 1,
            'rm_whouse_id' => 1,
            'fg_whouse_id' => 1,
            'production_date' => now()->subDays(3),
            'finished_date' => now(),
            'description' => 'Data untuk pengujian',
        ]);
    }

    protected function tearDown(): void
    {
        // Hapus data test setelah pengujian
        if ($this->testRecord) {
            $this->testRecord->delete();
        }

        parent::tearDown();
    }

    public function test_search_returns_correct_results()
    {
        $results = AssortmentProduction::SearchOfAssortmentProduction('PRD001');

        $match = $results->filter(function ($item) {
            return stripos($item->production_number, 'PRD001') !== false;
        });

        $this->assertGreaterThan(
            0,
            $match->count(),
            'Data dengan production_number "PRD001" tidak ditemukan dalam hasil pencarian.'
        );
    }

    public function test_search_with_no_match_returns_empty()
    {
        $results = AssortmentProduction::SearchOfAssortmentProduction('TIDAK_ADA');

        $this->assertCount(0, $results);
    }

    public function test_search_with_null_returns_data()
    {
        $results = AssortmentProduction::SearchOfAssortmentProduction(null);

        $this->assertGreaterThan(
            0,
            $results->count(),
            'Pencarian kosong seharusnya mengembalikan beberapa data.'
        );
    }
}
