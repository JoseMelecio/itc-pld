<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RealEstateAdministrationImport implements ToCollection, WithMultipleSheets
{
    private array $data = [];

    public function collection(Collection $collection): void
    {
        $noticeHash = '';
        foreach ($collection->skip(3) as $row) {
            $newRecord = false;
            //Validamos si es nuevo registro o continuacion del anterior
            if (strlen($row[3]) > 0 || //D - Nombre persona fisica
                strlen($row[11]) > 0 || //L - Nombre persona moral
                strlen($row[16]) > 0) { //Q - Fideicomiso
                $noticeHash = uniqid();
                $newRecord = true;
            }

            //Modification
            $modification = [
                'folio' => trim(strtoupper($row[0])), //A
                'description' => trim(strtoupper($row[1])),
                'priority' => trim(strtoupper($row[2])),
            ];

            if (strlen($modification['priority']) > 0) {
                $tempPriority = explode(',', $modification['priority']);
                $modification['priority'] = trim($tempPriority[0]);
            }

            if ($newRecord) {
                $dataRow['modification'] = $modification;
            }

            //Identification data of the person subject of the notice
            $physicalPerson = [
                'name' => trim(strtoupper($row[3])), //D
                'last_name' => trim(strtoupper($row[4])),
                'second_last_name' => trim(strtoupper($row[5])),
                'birthdate' => trim(strtoupper($row[6])),
                'tax_id' => trim(strtoupper($row[7])),
                'population_id' => trim(strtoupper($row[8])),
                'nationality' => trim(strtoupper($row[9])),
                'economic_activity' => trim(strtoupper($row[10])), //K
            ];

            if (strlen($physicalPerson['economic_activity']) > 0) {
                $tempActivity = explode('||', $physicalPerson['economic_activity']);
                $physicalPerson['economic_activity'] = $tempActivity[1];
            }

            if (strlen($physicalPerson['nationality']) > 0) {
                $tempNationality = explode(',', $physicalPerson['nationality']);
                $physicalPerson['nationality'] = $tempNationality[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['physicalPerson'] = $physicalPerson;
            }

            //Legal person
            $legalPerson = [
                'company_name' => trim(strtoupper($row[11])), //L
                'constitution_date' => trim(strtoupper($row[12])),
                'tax_id' => trim(strtoupper($row[13])),
                'nationality' => trim(strtoupper($row[14])),
                'commercial_business' => trim(strtoupper($row[15])), //P
            ];

            if (strlen($legalPerson['commercial_business']) > 0) {
                $tempCommercialBusiness = explode('||', $legalPerson['commercial_business']);
                $legalPerson['commercial_business'] = $tempCommercialBusiness[1];
            }

            if (strlen($legalPerson['nationality']) > 0) {
                $tempNationality = explode(',', $legalPerson['nationality']);
                $legalPerson['nationality'] = $tempNationality[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['legalPerson'] = $legalPerson;
            }

            //trust
            $trust = [
                'denomination' => trim(strtoupper($row[16])), //Q
                'tax_id' => trim(strtoupper($row[17])),
                'identification' => trim(strtoupper($row[18])),
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['trust'] = $trust;
            }

            //representative data
            $representative = [
                'name' => trim(strtoupper($row[19])), //T
                'last_name' => trim(strtoupper($row[20])),
                'second_last_name' => trim(strtoupper($row[21])),
                'birthdate' => trim(strtoupper($row[22])),
                'tax_id' => trim(strtoupper($row[23])),
                'population_id' => trim(strtoupper($row[24])),//Y
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['representativeData'] = $representative;
            }

            $nationalAddress = [
                'postal_code' => trim(strtoupper($row[25])), //Z
                'state' => trim(strtoupper($row[26])),
                'municipality' => trim(strtoupper($row[27])),
                'settlement' => trim(strtoupper($row[28])),
                'street' => trim(strtoupper($row[29])),
                'external_number' => trim(strtoupper($row[30])),
                'internal_number' => trim(strtoupper($row[31])),//AF
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['nationalAddress'] = $nationalAddress;
            }

            $foreignAddress = [
                'country' => trim(strtoupper($row[32])), //AG
                'state' => trim(strtoupper($row[33])),
                'municipality' => trim(strtoupper($row[34])),
                'settlement' => trim(strtoupper($row[35])),
                'street' => trim(strtoupper($row[36])),
                'external_number' => trim(strtoupper($row[37])),
                'internal_number' => trim(strtoupper($row[38])),
                'postal_code' => trim(strtoupper($row[39])), //AN
            ];

            if (strlen($foreignAddress['country']) > 0) {
                $temCountry = explode(',', $foreignAddress['country']);
                $foreignAddress['country'] = $temCountry[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['foreignAddress'] = $foreignAddress;
            }

            //Contact
            $contact = [
                'country' => trim(strtoupper($row[40])), //AO
                'phone' => trim(strtoupper($row[41])),
                'email' => trim(strtoupper($row[42])), //AQ
            ];

            if (strlen($contact['country']) > 0) {
                $temCountry = explode(',', $contact['country']);
                $contact['country'] = $temCountry[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['contact'] = $contact;
            }

            //Identification data of the beneficiary or owner
            //Physical person
            $physicalPerson = [
                'name' => trim(strtoupper($row[43])), //AR
                'last_name' => trim(strtoupper($row[44])),
                'second_last_name' => trim(strtoupper($row[45])),
                'birthdate' => trim(strtoupper($row[46])),
                'tax_id' => trim(strtoupper($row[47])),
                'population_id' => trim(strtoupper($row[48])),
                'nationality' => trim(strtoupper($row[49])), // AX
            ];

            if (strlen($physicalPerson['nationality']) > 0) {
                $tempNationality = explode(',', $physicalPerson['nationality']);
                $physicalPerson['nationality'] = $tempNationality[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonBeneficiary']['physicalPerson'] = $physicalPerson;
            }

            //legal person
            $legalPerson = [
                'company_name' => trim(strtoupper($row[50])), //AY
                'constitution_date' => trim(strtoupper($row[51])),
                'tax_id' => trim(strtoupper($row[52])),
                'nationality' => trim(strtoupper($row[53])), //BB
            ];

            if (strlen($legalPerson['nationality']) > 0) {
                $tempNationality = explode(',', $legalPerson['nationality']);
                $legalPerson['nationality'] = $tempNationality[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonBeneficiary']['legalPerson'] = $legalPerson;
            }

            //trust
            $trust = [
                'denomination' => trim(strtoupper($row[54])),//BC
                'tax_id' => trim(strtoupper($row[55])),
                'identification' => trim(strtoupper($row[56])), //BE
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonBeneficiary']['trust'] = $trust;
            }

            //Fecha de Operacion
            $dateOperation = trim(strtoupper($row[57])); //BF
            if (strlen($dateOperation) > 0) {
                $dataRow['operationDetails']['dateOperation'] = $dateOperation;
            }

            //Numero de Operaciones
            $operationCount = trim(strtoupper($row[58])); //BG
            if (strlen($operationCount) > 0) {
                $dataRow['operationDetails']['operationCount'] = $operationCount;
            }

            //Financial Operation
            $financialOperation = [
                'instrument' => trim(strtoupper($row[59])), //BG
                'currency' => trim(strtoupper($row[60])),
                'amount' => trim(strtoupper($row[61])),
            ];

            if (strlen($financialOperation['instrument']) > 0) {
                $tempInstrument = explode(',', $financialOperation['instrument']);
                $financialOperation['instrument'] = $tempInstrument[0];
            }

            if (strlen($financialOperation['currency']) > 0) {
                $tempCurrency = explode(',', $financialOperation['currency']);
                $financialOperation['currency'] = $tempCurrency[0];
            }

            if (strlen($dataRow['identificationDataPersonSubjectNotice']['physicalPerson']['name']) > 0 ||
                strlen($dataRow['identificationDataPersonSubjectNotice']['legalPerson']['company_name']) > 0 ||
                strlen($dataRow['identificationDataPersonSubjectNotice']['trust']['denomination']) > 0
            ) {
                $noticeHash = md5(json_encode($dataRow['identificationDataPersonSubjectNotice']));
            }

            if (strlen($financialOperation['instrument']) > 0) {
                $dataRow['operationDetails']['financialOperation'][$noticeHash][] = $financialOperation;
            }

            //Administrate real state
            $asset = [
                'asset_type' => trim(strtoupper($row[62])),
                'reference_value_mx' => trim(strtoupper($row[63])),
                'postal_code' => trim(strtoupper($row[64])),
                'state' => trim(strtoupper($row[65])),
                'municipality' => trim(strtoupper($row[66])),
                'settlement' => trim(strtoupper($row[67])),
                'street' => trim(strtoupper($row[68])),
                'external_number' => trim(strtoupper($row[69])),
                'internal_number' => trim(strtoupper($row[70])),
                'real_folio' => trim(strtoupper($row[71])),
            ];

            if (strlen($asset['asset_type']) > 0) {
                $tempAsset = explode(',', $asset['asset_type']);
                $asset['asset_type'] = $tempAsset[0];
            }

            if (strlen($asset['state']) > 0) {
                $tempState = explode(',', $asset['state']);
                $asset['state'] = $tempState[0];
            }

            if (strlen($asset['asset_type']) > 0) {
                $dataRow['operationDetails']['asset'][$noticeHash][] = $asset;
            }

            $this->data['items'][$noticeHash] = $dataRow;
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function sheets(): array
    {
        return [
            'Plantilla' => $this,
        ];
    }
}
