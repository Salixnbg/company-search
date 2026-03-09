<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;

class ImportCompaniesCsv extends Command
{
    protected $signature = 'companies:import';
    protected $description = 'Import companies from CSV file';

    public function handle()
    {
        $path = storage_path('app/data/companies.csv');

        if (!file_exists($path)) {
            $this->error('CSV file not found: ' . $path);
            return Command::FAILURE;
        }

        $file = fopen($path, 'r');

        $header = fgetcsv($file);

        if (!$header) {
            $this->error('CSV header not found.');
            fclose($file);
            return Command::FAILURE;
        }

        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            $data = array_combine($header, $row);

            Company::updateOrCreate(
                [
                    'enterprise_number' => $data['enterprise_number'],
                ],
                [
                    'vat_number' => $data['vat_number'] ?? null,
                    'name' => $data['name'] ?? '',
                    'status' => $data['status'] ?? null,
                    'legal_form' => $data['legal_form'] ?? null,
                    'street' => $data['street'] ?? null,
                    'postal_code' => $data['postal_code'] ?? null,
                    'city' => $data['city'] ?? null,
                    'start_date' => $data['start_date'] ?? null,
                ]
            );
        }

        fclose($file);

        $this->info('Import completed successfully.');
        return Command::SUCCESS;
    }
}