<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PersonBlockedFinderService
{
    public function finder(array $array): array
    {
        foreach ($array as $key => $item) {
            $result = $this->singleSearch(
                name: $item['name'],
                alias: $item['alias'],
                date: $item['date'],
            );

            $array[$key]['result'] = $result;
        }

        return $array;
    }

    public function singleSearch(string $name, ?string $alias = null, ?string $date = null): string
    {
        $nameToSearch = $this->clearText($name);
        $aliasToSearch = $this->clearText($alias);

        $countExact = 0;
        $countApproximate = 0;

        $results = DB::table('person_lists')
            ->leftJoin('aliases', 'person_lists.id', '=', 'aliases.person_list_id')
            ->leftJoin('birth_dates', 'person_lists.id', '=', 'birth_dates.person_list_id')
            ->select('person_lists.*', 'aliases.*', 'birth_dates.*')
            ->whereRaw("
                    REGEXP_REPLACE(
                        CONCAT(
                            IFNULL(person_lists.first_name, ''),
                            IFNULL(person_lists.second_name, ''),
                            IFNULL(person_lists.third_name, '')
                        ), '[^a-zA-Z0-9]', ''
                    ) = ?
                    OR REGEXP_REPLACE(aliases.alias, '[^a-zA-Z0-9]', '') = ?
                ", [$nameToSearch, $aliasToSearch])
            ->get();

        if ($results->count() === 0) {
            return 'Sin coincidencias';
        }

        foreach ($results as $result) {
            $nameFounded = null;
            $aliasFounded = null;
            $dateFounded = null;

            $resultName = $this->clearText($result->first_name.$result->second_name.$result->third_name);
            $resultAlias = $this->clearText($result->alias);
            $resultDateYear = str_pad($this->clearText($result->year), 4, '0', STR_PAD_LEFT);
            $resultDateMonth = str_pad($this->clearText($result->month), 2, '0', STR_PAD_LEFT);
            $resultDateDay = str_pad($this->clearText($result->day), 2, '0', STR_PAD_LEFT);
            $resultDateConcat = $resultDateYear.$resultDateMonth.$resultDateDay;

            if ($resultName === $nameToSearch) {
                $nameFounded = true;
            }

            if ($resultAlias === $aliasToSearch) {
                $aliasFounded = true;
            }

            if ($date) {
                if ($resultDateConcat === $date) {
                    $dateFounded = true;
                }
            }

            if ($nameFounded && $aliasFounded && $dateFounded) {
                return 'Coincidencia estricta';
            }

            if (($nameFounded && $dateFounded) || ($aliasFounded && $dateFounded)) {
                $countExact++;
            }

            if (($nameFounded && ! $aliasFounded) || ($aliasFounded && ! $nameFounded)) {
                $countApproximate++;
            }
        }

        if ($countExact >= $countApproximate) {
            return 'Coincidencia exacta';
        } else {
            return 'Coincidencia aproximada';
        }
    }

    public function exactSearch(string $name, string $alias, string $date) {}

    public function aproximateSearch(string $name, string $alias) {}

    public function clearText(?string $input): array|string|null
    {
        if (! $input) {
            return null;
        }

        return preg_replace('/[^a-zA-Z0-9]/', '', $input);
    }
}
