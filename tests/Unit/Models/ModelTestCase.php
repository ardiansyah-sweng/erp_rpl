<?php
// tests/Unit/Models/ModelTestCase.php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Faker\Factory as Faker;

abstract class ModelTestCase extends TestCase
{
    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialize Faker for use in tests
        $this->faker = Faker::create('id_ID');

        // Set all table configurations
        config([
            'db_tables.branch' => 'branches',
            // Add all your table configs here
        ]);
    }
}