<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Patient;
use App\Models\Test;
use App\Services\BasicPatientImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasicPatientImportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_test_from_collection()
    {
        $row = collect([
            'testname' => 'Glukoza',
            'testvalue' => '99',
            'testreference' => '70-100',
        ]);

        $service = new BasicPatientImportService();
        $test = $service->createTest($row);

        $this->assertDatabaseHas('tests', [
            'name' => 'Glukoza',
            'value' => '99',
            'reference' => '70-100',
        ]);

        $this->assertInstanceOf(Test::class, $test);
    }

    public function test_assigns_test_to_order()
    {
        // Arrange
        $patient = Patient::factory()->create();
        $order = Order::factory()->create([
            'patient_id' => $patient->patient_id,
        ]);

        $test = Test::factory()->create();

        // Action
        $service = new BasicPatientImportService();
        $service->assignTestToOrder($order->order_id, $test->id);

        // Assert
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->order_id,
            'item_type' => Test::class,
            'item_id' => $test->id,
        ]);
    }
}
