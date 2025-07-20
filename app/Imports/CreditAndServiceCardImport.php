<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CreditAndServiceCardImport implements ToCollection, WithMultipleSheets
{
    private array $data = [];

    public function collection(Collection $collection): void
    {
        $noticeHash = '';
        foreach ($collection->skip(3) as $row) {
            $newRecord = false;
            //Validamos si es nuevo registro o continuacion del anterior
            if (strlen($row[3]) > 0 ||
                strlen($row[11]) > 0 ||
                strlen($row[16]) > 0) {
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
                'population_id' => trim(strtoupper($row[24])), //Y
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['representativeData'] = $representative;
            }

            $nationalAddress = [
                'settlement' => trim(strtoupper($row[25])), //Z
                'street' => trim(strtoupper($row[26])),
                'external_number' => trim(strtoupper($row[27])),
                'internal_number' => trim(strtoupper($row[28])),
                'postal_code' => trim(strtoupper($row[29])), //AD
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['nationalAddress'] = $nationalAddress;
            }

            $foreignAddress = [
                'country' => trim(strtoupper($row[30])), //AE
                'state' => trim(strtoupper($row[31])),
                'municipality' => trim(strtoupper($row[32])),
                'settlement' => trim(strtoupper($row[33])),
                'street' => trim(strtoupper($row[34])),
                'external_number' => trim(strtoupper($row[35])),
                'internal_number' => trim(strtoupper($row[36])),
                'postal_code' => trim(strtoupper($row[37])), //AL
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
                'country' => trim(strtoupper($row[38])), //AM
                'phone' => trim(strtoupper($row[39])),
                'email' => trim(strtoupper($row[40])), //AO
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
                'name' => trim(strtoupper($row[41])), //AP
                'last_name' => trim(strtoupper($row[42])),
                'second_last_name' => trim(strtoupper($row[43])),
                'birthdate' => trim(strtoupper($row[44])),
                'tax_id' => trim(strtoupper($row[45])),
                'population_id' => trim(strtoupper($row[46])),
                'nationality' => trim(strtoupper($row[47])), // AV
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
                'company_name' => trim(strtoupper($row[48])), //AW
                'constitution_date' => trim(strtoupper($row[49])),
                'tax_id' => trim(strtoupper($row[50])),
                'nationality' => trim(strtoupper($row[51])), //AZ
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
                'tax_id' => trim(strtoupper($row[52])), //BA
                'denomination' => trim(strtoupper($row[53])),
                'identification' => trim(strtoupper($row[54])), //BC
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonBeneficiary']['trust'] = $trust;
            }

            //Operation data section
            //Operation data
            $operation = [
                'period_report' => trim(strtoupper($row['55'])), //BD
                'operation_type' => trim(strtoupper($row['56'])),
                'card_type' => trim(strtoupper($row['57'])),
                'account' => trim(strtoupper($row['58'])),
                'amount' => trim(strtoupper($row['58'])),
            ];

            if (strlen($operation['operation_type']) > 0) {
                $tempTypeOperation = explode(',', $operation['operation_type']);
                $operation['operation_type'] = $tempTypeOperation[0];
            }

            if (strlen($operation['card_type']) > 0) {
                $tempTypeOperation = explode(',', $operation['card_type']);
                $operation['card_type'] = $tempTypeOperation[0];
            }


            if (strlen($dataRow['identificationDataPersonSubjectNotice']['physicalPerson']['name']) > 0 ||
                strlen($dataRow['identificationDataPersonSubjectNotice']['legalPerson']['company_name']) > 0 ||
                strlen($dataRow['identificationDataPersonSubjectNotice']['trust']['denomination']) > 0
            ) {
                $noticeHash = md5(json_encode($dataRow['identificationDataPersonSubjectNotice']));
            }
            $dataRow['operationDetails'][$noticeHash][] = $operation;
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
