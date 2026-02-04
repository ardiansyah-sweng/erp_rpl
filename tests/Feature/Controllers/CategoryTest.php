<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CategoryController;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_category_using_custom_route()
    {
        $this->withoutMiddleware();

        DB::statement("DROP VIEW IF EXISTS category");
        DB::statement("CREATE VIEW category AS SELECT * FROM categories");

        $router = $this->app['router'];

        $router->post('/test-add-category', [CategoryController::class, 'addCategory']);

        $router->get('/dummy-page', function() { 
            return 'Halaman Sukses'; 
        })->name('category.list');

        $router->getRoutes()->refreshNameLookups();
        $router->getRoutes()->refreshActionLookups();
        // ---------------------------------------------------

        $payload = [
            'category'  => 'Kategori Final Banget',
            'parent_id' => 0,
            'active'    => 1,
        ];

        $response = $this->post('/test-add-category', $payload);

        $response->assertStatus(302);
        
        $response->assertRedirect(route('category.list'));

        $this->assertDatabaseHas('categories', [ 
            'category' => 'Kategori Final Banget',
            'is_active' => 1
        ]);
    }
}