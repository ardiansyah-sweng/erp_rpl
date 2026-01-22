<?php
namespace Tests\Feature\Controllers;
use Tests\TestCase;

class UasTestingTest extends TestCase
{
    public function test_dashboard_page_can_be_accessed()
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }
}