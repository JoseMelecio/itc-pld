<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonListFindMassiveRequest;
use App\Http\Requests\PersonListFindRequest;
use App\Http\Requests\PersonListStoreRequest;
use App\Imports\BlockedPersonMassiveSearchImport;
use App\Imports\PersonListFinderImport;
use App\Models\Alias;
use App\Models\BirthDate;
use App\Models\BirthPlace;
use App\Models\BlockedPersonMassive;
use App\Models\PersonList;
use App\Services\PersonBlockedFinderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
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
                'origin' => $person->id.'-'.$person->origin,
                'record_type' => $person->record_type,
                'un_list_type' => $person->un_list_type,
                'birth_place' => null,
                'document' => null,
            ];

            if ($person->aliases) {
                $text = '';
                $count = 1;
                foreach ($person->aliases as $alias) {
                    $text .= $count.': '.$alias->alias.'<br>';
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
                    $date .= $count.': '.$year.'-'.$month.'-'.$day.'<br>';
                    $count++;
                }
                $record['birth_date'] = $date;
            }

            if ($person->birthPlaces) {
                $places = '';
                $count = 1;
                foreach ($person->birthPlaces as $place) {
                    $placeConcat = [$place->city, $place->state_province, $place->country];
                    $places .= $count.': '. implode(', ', $placeConcat) . '<br>';
                    $count++;
                }
                $record['birth_place'] = $places;
            }

            if ($person->documents) {
                $documents = '';
                $count = 1;
                foreach ($person->documents as $document) {
                    $documents .= $count.': '.$document->document.'<br>';
                    $count++;
                }
                $record['documents'] = $documents;
            }

            $data[] = $record;
        }

        return Inertia::render('person-list/Index', ['person_list' => $data]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('person-list/Create');
    }

    public function store(PersonListStoreRequest $request): \Inertia\Response
    {
        $data = $request->validated();
        $filePath = $request->file('file')->storeAs('person-list', uniqid().'.pdf', 'public' );

        $personList = PersonList::create([
            'origin' => Str::upper($data['origin']),
            'record_type' => Str::upper($data['record_type']),
            'un_list_type' => Str::upper($data['un_list_type']),
            'first_name' => Str::upper($data['first_name']),
            'second_name' => Str::upper($data['second_name']),
            'third_name' => Str::upper($data['third_name']),
            'file' => $filePath,
        ]);

        $birthDates = array_filter(
            preg_split('/\r\n|\r|\n/', $data['birth_date']),
            fn($value) => trim($value) !== ''
        );

        foreach ($birthDates as $birthDate) {

            $year = substr($birthDate, 0, 4);   // 2025
            $month = substr($birthDate, 4, 2);  // 02
            $day = substr($birthDate, 6, 2);    // 26

            BirthDate::create([
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'person_list_id' => $personList->id,
            ]);
        }

        $alias = array_filter(
            preg_split('/\r\n|\r|\n/', $data['alias']),
            fn($value) => trim($value) !== ''
        );

        foreach ($alias as $value) {
            Alias::create([
                'alias' => $value,
                'person_list_id' => $personList->id,
            ]);
        }

        $birthPlaces = array_filter(
            preg_split('/\r\n|\r|\n/', $data['birth_place']),
            fn($value) => trim($value) !== ''
        );

        foreach ($birthPlaces as $place) {
            $place = explode('-', $place);
            BirthPlace::create([
                'city' => $place[0],
                'state_province' => $place[1],
                'country' => $place[2],
                'person_list_id' => $personList->id,
            ]);
        }

        return Inertia::render('person-list/Create');
    }

    public function formFind(): \Inertia\Response
    {
        return Inertia::render('person-list/ShowForm');
    }

    public function find(PersonListFindRequest $request): \Illuminate\Http\Response
    {
        $dataToFind = $request->validated();
        $findService = new PersonBlockedFinderService;

        if ($dataToFind['name']) {
            $data[] = [
                'name' => $dataToFind['name'],
                'alias' => $dataToFind['alias'],
                'date' => $dataToFind['date'],
            ];

            $dataResult = $findService->finder($data);
        } else {
            $import = new PersonListFinderImport;
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

    public function formFindMassive()
    {
        $massiveFinds = BlockedPersonMassive::where('user_id', auth()->user()->id)->get();



        return Inertia::render('person-list/Massive', [
            'massiveFinds' => $massiveFinds
        ]);
    }

    public function storeMassive(PersonListFindMassiveRequest $request)
    {
        $file = $request->file('file');
        $newSearch = BlockedPersonMassive::create([
            'user_id' => auth()->user()->id,
            'file_uploaded' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);

        $fileName = $newSearch->id . '_massive_search.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('massive_search', $fileName, 'local');

        Excel::queueImport(new BlockedPersonMassiveSearchImport(
            $newSearch->id
        ), $filePath);

        return redirect()->route('person-blocked-form-finder-store-massive');
    }
}
