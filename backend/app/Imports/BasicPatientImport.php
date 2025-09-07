<?php

namespace App\Imports;

use App\Exceptions\Imports\InvalidBirthDateException;
use App\Exceptions\Imports\InvalidSexException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Patient;
use App\Enums\PatientTypeEnum;
use App\Services\BasicPatientImportService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Mockery\Exception;

class BasicPatientImport implements ToCollection, WithHeadingRow
{
    private readonly BasicPatientImportService $service;
    private array $successes = [];
    private array $failures = [];

    public function __construct()
    {
        $this->service = new BasicPatientImportService();
    }

    public function collection(Collection $collection): void
    {
        foreach ($collection as $index => $row) {
            try {
                if ($row->filter()->isEmpty()) {
                    $this->failures[] = array_merge($row->toArray(), [
                        'error' => "Pusty wiersz: $index"
                    ]);
                    continue;
                }

                $sexRaw = strtolower(trim($row['patientsex'] ?? ''));
                if (!PatientTypeEnum::tryFrom($sexRaw)) {
                    $this->failures[] = array_merge($row->toArray(), [
                        'error_type' => new InvalidSexException(),
                        'error' => "Nieprawidłowa płeć w wierszu $index"
                    ]);
                    continue;
                }

                $birthRaw = $row['patientbirthdate'] ?? null;
                $birthDate = $this->service->parseDate($birthRaw);
                if (!$birthDate) {
                    $this->failures[] = array_merge($row->toArray(), [
                        'error_type' => new InvalidBirthDateException(),
                        'error' => "Nieprawidłowy format daty w wierszu $index"
                    ]);
                    continue;
                }

                $patient = Patient::query()
                    ->where('patient_id', '=', $row['patientid'])
                    ->where('name', '=', $row['patientname'])
                    ->where(
                        'birth',
                        '=',
                        date_create($row['patientbirthdate'])->format('Y-m-d'))
                    ->first();

                if (null === $patient) {
                    $patient = Patient::query()
                        ->create([
                            'patient_id' => $row['patientid'],
                            'name' => $row['patientname'],
                            'surname' => $row['patientsurname'],
                            'birth' => $birthDate,
                            'sex' => PatientTypeEnum::from($sexRaw),
                        ]);
                } else {
                    $wasChanged = false;
                    if ($patient->surname !== $row['patientsurname']) {
                        $this->successes[] = [sprintf(
                            "Dla pacjenta %s nazwisko zmienione z %s na %s. Wiersz w imporcie to: $index",
                            $patient->name . ' ' . $patient->surname . ' - ' . $patient->birth,
                            $patient->surname,
                            $row['surname']
                        )];
                        $patient->surname = $row['patientsurname'];
                        $wasChanged = true;
                    }

                    if ($patient->sex->name !== strtoupper($row['patientsex'])) {
                        $this->successes[] = [sprintf(
                            "Dla pacjenta %s płeć zmieniona z %s na %s. Wiersz w imporcie to: $index",
                            $patient->name . ' ' . $patient->surname . ' - ' . $patient->birth,
                            $patient->sex->name,
                            strtoupper($row['patientsex'])
                        )];
                        $patient->sex = $row['patientsex'];
                        $wasChanged = true;
                    }

                    if (true === $wasChanged) {
                        $patient->save();
                    }
                }

                $order = Order::query()
                    ->where('order_id', '=', $row['orderid'])
                    ->first();

                if (null === $order) {
                    $order = Order::query()
                        ->create([
                            'order_id' => $row['orderid'],
                            'patient_id' => $patient->patient_id,
                        ]);
                }

                $orderItems = OrderItem::query()
                    ->where('order_id', '=', $order->order_id)
                    ->get();
                $matched = false;
                foreach ($orderItems as $orderItem) {
                    $test = $orderItem->item;

                    if ($test->name === $row['testname']) {
                        $test->update([
                            'value' => $row['testvalue'],
                            'reference' => $row['testreference'] ?? 'Brak specjalnych zaleceń'
                        ]);

                        $matched = true;
                        break;
                    }
                }

                if (false === $matched) {
                    $test = $this->service->createTest($row);
                    $this->service->assignTestToOrder($order->order_id, $test->id);
                }

                $this->successes[] = $row->toArray();
            } catch (Exception $e) {
                $this->failures[] = array_merge($row->toArray(), [
                    'error_type' => class_basename($e),
                    'error' => $e->getMessage(),
                ]);
                continue;
            }
        }
        $this->service->writeLogFiles(
            $this->successes,
            $this->failures
        );
    }
}
