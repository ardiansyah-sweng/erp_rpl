<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Merk;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MerkTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed beberapa data Merk contoh
        Merk::create(['id' => '1', 'merk' => 'Samsung']);
        Merk::create(['id' => '2', 'merk' => 'Sony']);
        Merk::create(['id' => '3', 'merk' => 'Apple']);
        Merk::create(['id' => '4', 'merk' => 'Asus']);
    }

    /** @test */
    public function it_can_search_merk_with_keyword()
    {
        $keyword = 's'; // Harus mengembalikan Samsung, Sony, Asus (case insensitive)

        $result = Merk::searchMerk($keyword);

        // Pastikan hasil berupa Length > 0
        $this->assertNotEmpty($result);

        // Pastikan tiap merk hasil pencarian mengandung keyword (case insensitive)
        foreach ($result as $merk) {
            $this->assertStringContainsStringIgnoringCase($keyword, $merk->merk);
        }
    }

    /** @test */
    public function it_returns_empty_when_no_match()
    {
        $keyword = 'nonexistent';

        $result = Merk::searchMerk($keyword);

        $this->assertTrue($result->isEmpty());
    }
}
