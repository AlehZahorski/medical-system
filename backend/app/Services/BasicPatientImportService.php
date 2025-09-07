<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BasicPatientImportService
{
    public function parseDate(?string $value): ?Carbon
    {
        try {
            return Carbon::createFromFormat('d.m.Y', trim($value));
        } catch (\Exception) {
            return null;
        }
    }

    public function writeLogFiles(
        ?array $successes,
        ?array $failures
    ): void
    {
        $timestamp = now()->format('Ymd_His');

        if (!empty($successes)) {
            $this->writeCsv("import_logs/basicPatientImport_at_$timestamp/success.csv", $successes);
        }

        if (!empty($failures)) {
            $this->writeCsv("import_logs/basicPatientImport_at_$timestamp/failures.csv", $failures);
        }
    }

    public function writeCsv(
        string $path,
        array  $rows
    ): void
    {
        $fullPath = Storage::disk('local')->path($path);

        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $handle = fopen($fullPath, 'w');

        fputcsv($handle, array_keys($rows[0]));

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        Log::info("Zapisano log importu do pliku: $fullPath");
    }

    public function createTest(Collection $row): Test|Model
    {
        return Test::query()
            ->create([
                'name' => $row['testname'],
                'value' => $row['testvalue'],
                'reference' => $row['testreference'] ?? 'Brak specjalnych poleceń',
            ]);
    }

    public function assignTestToOrder(
        int $orderId,
        int $testId
    ): void
    {
        OrderItem::query()
            ->create([
                'order_id' => $orderId,
                'item_type' => Test::class,
                'item_id' => $testId,
            ]);
    }
}
