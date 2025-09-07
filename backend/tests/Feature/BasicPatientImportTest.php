<?php

namespace Tests\Feature;

use App\Enums\PatientTypeEnum;
use App\Imports\BasicPatientImport;
use App\Models\Order;
use App\Models\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BasicPatientImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_imports_patient_order_and_test_correctly()
    {
        // Configuration
        Storage::fake('local');

        // Arrange
        $data = new Collection([
            collect([
                'patientid' => 1001,
                'patientname' => 'Anna',
                'patientsurname' => 'Kowalska',
                'patientbirthdate' => '01.01.2000',
                'patientsex' => 'female',
                'orderid' => 1001,
                'testname' => 'Morfologia',
                'testvalue' => '4.2',
                'testreference' => '3.5-5.5',
            ])
        ]);

        // Action
        $import = new BasicPatientImport();
        $import->collection($data);

        // Assert
        $this->assertDatabaseHas('patients', [
            'patient_id' => 1001,
            'name' => 'Anna',
            'surname' => 'Kowalska',
            'sex' => PatientTypeEnum::FEMALE,
        ]);

        $this->assertDatabaseHas('orders', [
            'order_id' => 1001,
            'patient_id' => 1001,
        ]);

        $this->assertDatabaseHas('tests', [
            'name' => 'Morfologia',
            'value' => '4.2',
            'reference' => '3.5-5.5',
        ]);

        $order = Order::query()
            ->where('order_id', '=', 1001)->first();
        $test = Test::query()
            ->where('name', '=', 'Morfologia')->first();

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->order_id,
            'item_type' => Test::class,
            'item_id' => $test->id,
        ]);

        $files = Storage::disk('local')->allFiles('import_logs');

        $this->assertTrue(
            collect($files)->contains(function ($path) {
                return str_contains($path, 'success.csv');
            }),
            'Plik success.csv nie został znaleziony w katalogu import_logs.'
        );
    }
}
