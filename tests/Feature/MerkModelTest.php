<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Merk;

class MerkModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_new_merk()
    {
        $this->withoutExceptionHandling(); 

        $namaMerk = 'MerkUnitTest';
        $active = 1;

        $merk = Merk::addMerk($namaMerk, $active);

        $this->assertDatabaseHas('merks', [
            'merk' => $namaMerk,
            'active' => $active,
        ]);

        $this->assertInstanceOf(Merk::class, $merk);
        $this->assertEquals($namaMerk, $merk->merk);
        $this->assertEquals($active, $merk->active);
    }
}