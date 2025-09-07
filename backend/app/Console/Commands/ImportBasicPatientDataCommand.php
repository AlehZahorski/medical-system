<?php

namespace App\Console\Commands;

use App\Imports\BasicPatientImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;

class ImportBasicPatientDataCommand extends Command
{
    protected $signature = 'import:basic-patient-data {fileName}';

    protected $description = 'This command importing basic patients data from imports/basic folder';

    public function handle(): int
    {
        $this->line('Rozpoczynam operacje importu danych pacjenta.');
        $file = Storage::disk('public')->path(
            sprintf('imports/basic/%s.csv', $this->argument('fileName'))
        );

        if (!file_exists($file)) {
            $this->error("Plik nie istnieje: $file");
            return self::FAILURE;
        }

        try {
            Excel::import(new BasicPatientImport, $file, null, \Maatwebsite\Excel\Excel::CSV);
            $this->info('Import zakończony sukcesem.');
            return self::SUCCESS;
        } catch (Exception $e) {
            $this->error('Błąd podczas importu: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
