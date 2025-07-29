<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;

class GetCategoryByParentTest extends TestCase
{
    /** @test */
    public function it_returns_categories_with_existing_parent_id()
    {
        // Ambil salah satu parent_id yang sudah ada di tabel dan punya anak
        $existingParent = Category::whereNotNull('parent_id')->first();

        // Jika tidak ditemukan, tes dianggap gagal
        $this->assertNotNull($existingParent, 'Tidak ada kategori anak yang memiliki parent_id.');

        // Ambil ID parent-nya
        $parentId = $existingParent->parent_id;

        // Hit endpoint
        $response = $this->getJson("/category/parent/{$parentId}");

        // Ambil jumlah anak dari parent_id tersebut
        $expectedCount = Category::where('parent_id', $parentId)->count();

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount($expectedCount);
    }

    /** @test */
    public function it_returns_404_for_non_existing_parent_id()
    {
        // Ambil ID yang tidak ada (pastikan tidak ada kategori dengan parent_id = 99999)
        $invalidParentId = 99999;

        // Hit endpoint
        $response = $this->getJson("/category/parent/{$invalidParentId}");

        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Tidak ada kategori dengan parent ID tersebut',
        ]);
    }
}
