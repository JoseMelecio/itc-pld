<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonListFindRequest;
use App\Imports\PersonListFinderImport;
use App\Models\PersonList;
use App\Services\PersonBlockedFinderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PersonListController extends Controller
{
    public function index(): \Inertia\Response
    {
        $data = [];

        $persons = PersonList::orderBy('first_name')->get();

        foreach ($persons as $person) {
            $record = [
                'name' => "{$person->first_name} {$person->second_name} {$person->third_name}",
                'origin' => $person->id . "-" . $person->origin,
                'record_type' => $person->record_type,
                'un_list_type' => $person->un_list_type,
                'birth_place' => null,
                'document' => null,
            ];

            if ($person->aliases) {
                $text = '';
                $count = 1;
                foreach ($person->aliases as $alias) {
                    $text .= $count . ": " . $alias->alias. "<br>";
                    $count++;
                }
                $record['alias'] = $text;
            }

            if ($person->birthDates) {
                $date = '';
                $count = 1;
                foreach ($person->birthDates as $birthDate) {
                    $year = $birthDate->year ?? 'AAAA';
                    $month = $birthDate->month ?? 'MM';
                    $day = $birthDate->day ?? 'DD';
                    $date .= $count . ": " . $year . "-" . $month . "-" . $day . "<br>";
                    $count++;
                }
                $record['birth_date'] = $date;
            }

            if ($person->birthPlaces) {
                $places = '';
                $count = 1;
                foreach ($person->birthPlaces as $place) {
                    $places .= $count . ": " . $place->place . "<br>";
                    $count++;
                }
                $record['birth_place'] = $places;
            }

            if ($person->documents) {
                $documents = '';
                $count = 1;
                foreach ($person->documents as $document) {
                    $documents .= $count . ": " . $document->document . "<br>";
                    $count++;
                }
                $record['documents'] = $documents;
            }

            $data[] = $record;
        }

        return Inertia::render('person-list/Index', ['person_list' => $data]);
    }

    public function formFind(): \Inertia\Response
    {
        return Inertia::render('person-list/ShowForm');
    }

    public function find(PersonListFindRequest $request): \Illuminate\Http\Response
    {
        $dataToFind = $request->validated();
        $findService = new PersonBlockedFinderService();


        if ($dataToFind['name']) {
            $data[] = [
                'name' => $dataToFind['name'],
                'alias' => $dataToFind['alias'],
                'date' => $dataToFind['date'],
            ];

            $dataResult = $findService->finder($data);
        } else {
            $import = new PersonListFinderImport();
            Excel::import($import, $request->file('file'));
            $data = $import->getData();
            $dataResult = $findService->finder($data);
        }

        $html = View::make('person-list/pdf-person-list-found', ['data' => $dataResult])->render();
        $pdf = Pdf::loadHTML($html);
        return $pdf->download('resultado.pdf');

    }

    public function downloadTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filePath = public_path('templates/plantillaBuscarPersonasBloqueadas.xlsx');
        return response()->download($filePath);
    }
}
