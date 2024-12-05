<?php

namespace App\Console\Commands;

use App\Models\PersonList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ProcessPersonBlockedListJsonFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:jsonfile {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import person blocked list json file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filename = $this->argument('filename');

        if (!Storage::exists('Imports/' . $filename)) {
            $this->error("File '{$filename}' does not exist.");
            return CommandAlias::FAILURE;
        }

        $content = Storage::get('Imports/' . $filename);

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error(json_last_error_msg());
            return CommandAlias::FAILURE;
        }

        $this->info('Processing person blocked list json file...:');
        foreach ($data as $key => $record) {

            $nameToFind = $this->clearText($record['nombre']);
            $personExist = PersonList::where('first_name', $nameToFind)->first();

            if (!$personExist) {
                $person = PersonList::create([
                    'origin' => $this->clearText($record['origen']),
                    'record_type' => $record['tipo_registro'],
                    'un_list_type' => $this->clearText($record['tipo_lista_onu']),
                    'first_name' => $this->clearText($record['nombre']),
                    'file_name_from_import' => $record['file_imported'],
                ]);
            } else {
                $person = $personExist;
            }

            if ($record['alias']) {
                $person->aliases()->create([
                    'alias' => $record['alias'],
                    'quality' => $record['calidad'],
                ]);
            }

            if ($record['fecha_nacimiento']) {

                $dataExplode = explode('-', $record['fecha_nacimiento']);
                $year = null;
                $month = null;
                $day = null;

                if ($dataExplode[0]) {
                    $year = $dataExplode[0];
                }

                if ($dataExplode[1]) {
                    $month = str_pad($dataExplode[1], 2, '0', STR_PAD_LEFT);
                }

                if ($dataExplode[2]) {
                    $day = str_pad($dataExplode[2], 2, '0', STR_PAD_LEFT);
                }

                $person->birthDates()->create([
                    'date_type' => $record['tipoFecha'],
                    'final_year' => $record['anio_final'],
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                ]);
            }

            $birthPlace = $this->clearText($record['lugar_nacimiento']);
            if (strlen($birthPlace) > 3) {
                $person->birthPlaces()->create([
                    'city' => $this->clearText($record['lugar_nacimiento']),
                ]);
            }

            if ($record['nacionalidad']) {
                $person->nationalities()->create([
                    'nationality' => $this->clearText($record['nacionalidad']),
                ]);
            }

            if ($record['documento'] || $record['documento2']) {
                $person->documents()->create([
                    'document' => $record['documento'],
                    'secondary_document' => $record['documento2'],
                    'number' => $record['numero'],
                    'country' => $record['pais'],
                    'notes' => $record['notas'],
                ]);
            }


        }

        $this->info('Process finished.');

        return CommandAlias::SUCCESS;
    }

    public function clearText(string $text): string
    {
        $text = trim(preg_replace('/\s+/', ' ', $text));
        $text = str_replace('#', '', $text);

        return $text;
    }
}
