<?php

namespace App\Http\Controllers;

use App\Models\PersonList;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
}
