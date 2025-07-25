<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VirtualAssetsImport implements ToCollection, WithMultipleSheets
{
    private array $data = [];

    public function collection(Collection $collection): void
    {
        $noticeHash = '';
        foreach ($collection->skip(3) as $row) {
            $newRecord = false;
            //Validamos si es nuevo registro o continuacion del anterior
            if (strlen($row[7]) > 0 ||
                strlen($row[16]) > 0 ||
                strlen($row[20]) > 0) {
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

            //Platform data
            $platform = [
                'id_user' => trim(strtoupper($row[3])), //D
                'account_related' => trim(strtoupper($row[4])),
                'clabe' => trim(strtoupper($row[5])),
                'currency' => trim(strtoupper($row[6])),
            ];

            if (strlen($platform['currency']) > 0) {
                $tempCurrency = explode(',', $platform['currency']);
                $platform['currency'] = $tempCurrency[0];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['platformData'] = $platform;
            }

            //Identification data of the person subject of the notice
            $physicalPerson = [
                'name' => trim(strtoupper($row[7])), //H
                'last_name' => trim(strtoupper($row[8])),
                'second_last_name' => trim(strtoupper($row[9])),
                'birthdate' => trim(strtoupper($row[10])),
                'tax_id' => trim(strtoupper($row[11])),
                'population_id' => trim(strtoupper($row[12])),
                'nationality' => trim(strtoupper($row[13])),
                'economic_activity' => trim(strtoupper($row[14])),
                'identification_type' => trim(strtoupper($row[15])),
                'identification_id' => trim(strtoupper($row[16])), //Q
            ];

            if (strlen($physicalPerson['economic_activity']) > 0) {
                $tempActivity = explode('||', $physicalPerson['economic_activity']);
                $physicalPerson['economic_activity'] = $tempActivity[1];
            }

            if (strlen($physicalPerson['nationality']) > 0) {
                $tempNationality = explode(',', $physicalPerson['nationality']);
                $physicalPerson['nationality'] = $tempNationality[1];
            }

            if (strlen($physicalPerson['identification_type']) > 0) {
                $tempIdentification = explode(',', $physicalPerson['identification_type']);
                $physicalPerson['identification_type'] = $tempIdentification[0];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['physicalPerson'] = $physicalPerson;
            }

            //Legal person
            $legalPerson = [
                'company_name' => trim(strtoupper($row[17])), //R
                'constitution_date' => trim(strtoupper($row[18])),
                'tax_id' => trim(strtoupper($row[19])),
                'nationality' => trim(strtoupper($row[20])),
                'commercial_business' => trim(strtoupper($row[21])), //V
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
                'denomination' => trim(strtoupper($row[22])), //W
                'tax_id' => trim(strtoupper($row[23])),
                'identification' => trim(strtoupper($row[24])),
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['trust'] = $trust;
            }

            //representative data
            $representative = [
                'name' => trim(strtoupper($row[25])), //Z
                'last_name' => trim(strtoupper($row[26])),
                'second_last_name' => trim(strtoupper($row[27])),
                'birthdate' => trim(strtoupper($row[28])),
                'tax_id' => trim(strtoupper($row[29])),
                'population_id' => trim(strtoupper($row[30])),
                'identification_type' => trim(strtoupper($row[31])),
                'identification_id' => trim(strtoupper($row[32])), //AG
            ];

            if (strlen($representative['identification_type']) > 0) {
                $tempIdentificationType = explode(',', $representative['identification_type']);
                $representative['identification_type'] = $tempIdentificationType[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['representativeData'] = $representative;
            }

            $nationalAddress = [
                'settlement' => trim(strtoupper($row[33])), //AH
                'street' => trim(strtoupper($row[34])),
                'external_number' => trim(strtoupper($row[35])),
                'internal_number' => trim(strtoupper($row[36])),
                'postal_code' => trim(strtoupper($row[37])), //AL
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['nationalAddress'] = $nationalAddress;
            }

            $foreignAddress = [
                'country' => trim(strtoupper($row[38])), //AM
                'state' => trim(strtoupper($row[39])),
                'municipality' => trim(strtoupper($row[40])),
                'settlement' => trim(strtoupper($row[41])),
                'street' => trim(strtoupper($row[42])),
                'external_number' => trim(strtoupper($row[43])),
                'internal_number' => trim(strtoupper($row[44])),
                'postal_code' => trim(strtoupper($row[45])), //AT
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
                'country' => trim(strtoupper($row[46])), //AU
                'phone' => trim(strtoupper($row[47])),
                'email' => trim(strtoupper($row[48])), //AW
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
                'name' => trim(strtoupper($row[49])), //AX
                'last_name' => trim(strtoupper($row[50])),
                'second_last_name' => trim(strtoupper($row[51])),
                'birthdate' => trim(strtoupper($row[52])),
                'tax_id' => trim(strtoupper($row[53])),
                'population_id' => trim(strtoupper($row[54])),
                'nationality' => trim(strtoupper($row[55])), // BD
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
                'company_name' => trim(strtoupper($row[56])), //BE
                'constitution_date' => trim(strtoupper($row[57])),
                'tax_id' => trim(strtoupper($row[58])),
                'nationality' => trim(strtoupper($row[59])), //BH
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
                'tax_id' => trim(strtoupper($row[60])), //BI
                'denomination' => trim(strtoupper($row[61])),
                'identification' => trim(strtoupper($row[62])), //BK
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonBeneficiary']['trust'] = $trust;
            }

            //Operation data section
            //Operation data
            $operationBuySell = [
                'buy_sell' => trim(strtoupper($row['63'])), //BL
                'date_time_operation' => trim(strtoupper($row['64'])),
                'operation_currency' => trim(strtoupper($row['65'])),
                'operation_amount' => trim(strtoupper($row['66'])),
                'virtual_asset' => trim(strtoupper($row['67'])),
                'virtual_asset_description' => trim(strtoupper($row['68'])),
                'exchange' => trim(strtoupper($row['69'])),
                'quantity_virtual_asset' => trim(strtoupper($row['70'])),
                'has_operator' => trim(strtoupper($row['71'])),
            ];

            if (strlen($operationBuySell['operation_currency']) > 0) {
                $tempTypeOperationBuySell = explode(',', $operationBuySell['operation_currency']);
                $operationBuySell['operation_currency'] = $tempTypeOperationBuySell[0];
            }

            if (strlen($operationBuySell['virtual_asset']) > 0) {
                $tempVirtualAsset = explode(',', $operationBuySell['virtual_asset']);
                $operationBuySell['virtual_asset'] = $tempVirtualAsset[0];
            }


            if (strlen($dataRow['identificationDataPersonSubjectNotice']['physicalPerson']['name']) > 0 ||
                strlen($dataRow['identificationDataPersonSubjectNotice']['legalPerson']['company_name']) > 0 ||
                strlen($dataRow['identificationDataPersonSubjectNotice']['trust']['denomination']) > 0
            ) {
                $noticeHash = md5(json_encode($dataRow['identificationDataPersonSubjectNotice']));
            }
            $dataRow['operationDetails'][$noticeHash]['buysell'][$operationBuySell['buy_sell']][] = $operationBuySell;
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
