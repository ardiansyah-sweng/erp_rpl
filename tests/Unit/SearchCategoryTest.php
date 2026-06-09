<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class SearchCategoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();

        parent::tearDown();
    }

    #[Test]
    public function it_can_search_existing_category()
    {
        $keyword = 'produk';

        Category::create([
            'category' => 'Produk Testing',
            'parent_id' => null,
            'is_active' => true,
        ]);

        $results = Category::searchCategory($keyword);

        // Pastikan hasil tidak kosong
        $this->assertNotEmpty($results);

        // Setiap hasil harus mengandung keyword (tidak case-sensitive)
        foreach ($results as $category) {
            $this->assertStringContainsStringIgnoringCase($keyword, $category->category);
        }
    }

    #[Test]
    public function it_returns_empty_when_no_match_found()
    {
        $results = Category::searchCategory('tidak-ada-kategori-ini');

        // Pastikan hasil kosong
        $this->assertTrue($results->isEmpty());
    }
}
