<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CategoryControllerTest extends TestCase
{
    #[Test]
    public function it_calls_get_category_by_parent_route()
    {
        $response = $this->get('/categories/by-parent/1');

        // Jika response 500, kita ingin melihat error lebih jelas
        if ($response->status() !== 200) {
            dd($response->getContent()); // Tampilkan error isi
        }

        $response->assertStatus(200);
    }
}
