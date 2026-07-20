<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentNotPresentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_toggle_not_present_status()
    {
        $appointment = Appointment::factory()->create(['is_not_present' => false]);

        $response = $this->actingAs($this->user)
            ->postJson(route('appointments.toggleNotPresent', $appointment));

        $response->assertStatus(200)
            ->assertJson(['is_not_present' => true]);

        $this->assertTrue($appointment->fresh()->is_not_present);

        $response = $this->actingAs($this->user)
            ->postJson(route('appointments.toggleNotPresent', $appointment));

        $response->assertStatus(200)
            ->assertJson(['is_not_present' => false]);

        $this->assertFalse($appointment->fresh()->is_not_present);
    }

    public function test_can_update_remark()
    {
        $appointment = Appointment::factory()->create();
        $remark = "Test Remark Content";

        $response = $this->actingAs($this->user)
            ->postJson(route('appointments.updateRemark', $appointment), [
                'remark' => $remark
            ]);

        $response->assertStatus(200)
            ->assertJson(['remark' => $remark]);

        $this->assertEquals($remark, $appointment->fresh()->remark);
    }
}
