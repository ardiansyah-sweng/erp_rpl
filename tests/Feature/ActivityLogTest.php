<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ActivityLog;
use App\Constants\ActivityLogColumns;
use App\Enums\UserRole;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // create a test user
        $this->user = User::factory()->create([
            'role' => UserRole::ADMIN->value
        ]);
    }

    /** @test */
    public function it_can_log_an_activity()
    {
        $this->actingAs($this->user);

        $log = ActivityLog::logActivity(
            ActivityLogColumns::ACTION_CREATE,
            ActivityLogColumns::MODULE_BRANCH,
            'Test create branch activity',
            '1'
        );

        $this->assertDatabaseHas('activity_logs', [
            'id' => $log->id,
            'user_id' => $this->user->id,
            'action' => ActivityLogColumns::ACTION_CREATE,
            'module' => ActivityLogColumns::MODULE_BRANCH,
            'description' => 'Test create branch activity',
            'target_id' => '1',
        ]);
    }

    /** @test */
    public function it_can_display_activity_logs_page()
    {
        $this->actingAs($this->user);

        // buat dummy data
        ActivityLog::logActivity(
            ActivityLogColumns::ACTION_CREATE,
            ActivityLogColumns::MODULE_BRANCH,
            'Dummy activity 1'
        );

        $response = $this->get(route('activity-logs.index'));

        $response->assertStatus(200);
        $response->assertViewIs('activity_log.index');
        $response->assertSee('Riwayat Aktivitas');
        $response->assertSee('Dummy activity 1');
    }

    /** @test */
    public function it_can_filter_logs_by_module()
    {
        $this->actingAs($this->user);

        ActivityLog::logActivity(ActivityLogColumns::ACTION_CREATE, ActivityLogColumns::MODULE_BRANCH, 'Branch log');
        ActivityLog::logActivity(ActivityLogColumns::ACTION_CREATE, ActivityLogColumns::MODULE_WAREHOUSE, 'Warehouse log');

        $response = $this->get(route('activity-logs.index', ['module' => ActivityLogColumns::MODULE_BRANCH]));

        $response->assertStatus(200);
        $response->assertSee('Branch log');
        $response->assertDontSee('Warehouse log');
    }

    /** @test */
    public function it_can_filter_logs_by_action()
    {
        $this->actingAs($this->user);

        ActivityLog::logActivity(ActivityLogColumns::ACTION_CREATE, ActivityLogColumns::MODULE_BRANCH, 'Create log');
        ActivityLog::logActivity(ActivityLogColumns::ACTION_UPDATE, ActivityLogColumns::MODULE_BRANCH, 'Update log');

        $response = $this->get(route('activity-logs.index', ['action' => ActivityLogColumns::ACTION_CREATE]));

        $response->assertStatus(200);
        $response->assertSee('Create log');
        $response->assertDontSee('Update log');
    }

    /** @test */
    public function it_can_search_logs_by_description()
    {
        $this->actingAs($this->user);

        ActivityLog::logActivity(ActivityLogColumns::ACTION_CREATE, ActivityLogColumns::MODULE_BRANCH, 'UniqueDescription123');
        ActivityLog::logActivity(ActivityLogColumns::ACTION_CREATE, ActivityLogColumns::MODULE_BRANCH, 'Another log');

        $response = $this->get(route('activity-logs.index', ['search' => 'UniqueDescription123']));

        $response->assertStatus(200);
        $response->assertSee('UniqueDescription123');
        $response->assertDontSee('Another log');
    }

    /** @test */
    public function it_can_export_pdf()
    {
        $this->actingAs($this->user);
        
        ActivityLog::logActivity(ActivityLogColumns::ACTION_CREATE, ActivityLogColumns::MODULE_BRANCH, 'PDF Log Test');

        $response = $this->get(route('activity-logs.export-pdf'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
