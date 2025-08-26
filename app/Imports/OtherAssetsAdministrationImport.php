<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OtherAssetsAdministrationImport implements ToCollection, WithMultipleSheets
{
    private array $data = [];

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection): void
    {
        $noticeHash = '';
        $newOperationHash = uniqid();
        foreach ($collection->skip(3) as $row) {
            $newRecord = false;
            //Validamos si es nuevo registro o continuacion del anterior
            if (strlen($row[3]) > 0 || //D - Nombre persona fisica
                strlen($row[11]) > 0 || //L - Nombre persona moral
                strlen($row[16]) > 0) { //Q - Fideicomiso
                $noticeHash = uniqid();
                $newRecord = true;
            }

            // MODIFICATION
            $modification = [
                'folio' => trim($row[0]),
                'description' => trim($row[1]),
                'priority' => trim($row[2]),
            ];

            if (strlen($modification['priority']) > 0) {
                $tempPriority = explode(",", $modification['priority']);
                $modification['priority'] = trim($tempPriority[0]);
            }

            if ($newRecord) {
                $dataRow = [];
                $dataRow['modification'] = $modification;
            }
            // IDENTIFICATION OF THE PERSON SUBJECT TO THE NOTICE
            // Physical Person
            $physicalPerson = [
                'name' => trim($row[3]),
                'last_name' => trim($row[4]),
                'second_last_name' => trim($row[5]),
                'birth_date' => trim($row[6]),
                'tax_id' => trim($row[7]),
                'population_id' => trim($row[8]),
                'nationality' => trim($row[9]),
                'economic_activity' => trim($row[10]),
            ];

            if (strlen($physicalPerson['economic_activity']) > 0) {
                $tempActivity = explode("||", $physicalPerson['economic_activity']);
                $physicalPerson['economic_activity'] = $tempActivity[1];
            }
            if (strlen($physicalPerson['nationality']) > 0) {
                $tempNationality = explode(",", $physicalPerson['nationality']);
                $physicalPerson['nationality'] = $tempNationality[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['physicalPerson'] = $physicalPerson;
            }

            // Legal Person
            $legalPerson = [
                'company_name' => trim($row[11]),
                'constitution_date' => trim($row[12]),
                'tax_id' => trim($row[13]),
                'nationality' => trim($row[14]),
                'commercial_business' => trim($row[15]),
            ];

            if (strlen($legalPerson['commercial_business']) > 0) {
                $tempBusiness = explode("||", $legalPerson['commercial_business']);
                $legalPerson['commercial_business'] = $tempBusiness[1];
            }
            if (strlen($legalPerson['nationality']) > 0) {
                $tempNationality = explode(",", $legalPerson['nationality']);
                $legalPerson['nationality'] = $tempNationality[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['legalPerson'] = $legalPerson;
            }

            // Trust
            $trust = [
                'denomination'  => trim($row[16]),
                'tax_id' => trim($row[17]),
                'identifier' => trim($row[18]),
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['trust'] = $trust;
            }

            // Representative Data (Legal Person)
            $representative = [
                'name' => trim($row[19]),
                'last_name' => trim($row[20]),
                'second_last_name' => trim($row[21]),
                'birth_date' => trim($row[22]),
                'tax_id' => trim($row[23]),
                'population_id' => trim($row[24]),
            ];

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['representativeData'] = $representative;
            }

            // National Address of the person subject to the notice
            $nationalAddress = [
                'postal_code' => trim($row[25]),
                'state' => trim($row[26]),
                'municipality' => trim($row[27]),
                'settlement' => trim($row[28]),
                'street' => trim($row[29]),
                'external_number' => trim($row[30]),
                'internal_number' => trim($row[31]),
            ];

            if (strlen($nationalAddress['state']) > 0) {
                $tempState = explode(",", $nationalAddress['state']);
                $nationalAddress['state'] = $tempState[0];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['nationalAddress'] = $nationalAddress;
            }

            // Foreign Address of the person subject to the notice
            $foreignAddress = [
                'country' => trim($row[32]),
                'state' => trim($row[33]),
                'city' => trim($row[34]),
                'settlement' => trim($row[35]),
                'street' => trim($row[36]),
                'external_number' => trim($row[37]),
                'internal_number' => trim($row[38]),
                'postal_code' => trim($row[39]),
            ];

            if (strlen($foreignAddress['country']) > 0) {
                $tempCountry = explode(",", $foreignAddress['country']);
                $foreignAddress['country'] = $tempCountry[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['foreignAddress'] = $foreignAddress;
            }

            // Contact Data
            $contactData = [
                'country'  => trim($row[40]),
                'phone' => trim($row[41]),
                'email' => trim($row[42]),
            ];

            if (strlen($contactData['country']) > 0) {
                $tempCountry = explode(",", $contactData['country']);
                $contactData['country'] = $tempCountry[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonSubjectNotice']['contact'] = $contactData;
            }

            // BENEFICIARY CONTROLLER
            // Physical Person
            $beneficiaryPhysical = [
                'name' => trim($row[43]),
                'last_name' => trim($row[44]),
                'second_last_name' => trim($row[45]),
                'birth_date' => trim($row[46]),
                'tax_id' => trim($row[47]),
                'population_id' => trim($row[48]),
                'nationality_country' => trim($row[49]),
            ];

            if (strlen($beneficiaryPhysical['nationality_country']) > 0) {
                $tempNationalityCountry = explode(",", $beneficiaryPhysical['nationality_country']);
                $beneficiaryPhysical['nationality_country'] = $tempNationalityCountry[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonBeneficiary']['physicalPerson'] = $beneficiaryPhysical;
            }

            // Legal Person
            $beneficiaryLegal = [
                'company_name' => trim($row[50]),
                'constitution_date' => trim($row[51]),
                'tax_id' => trim($row[52]),
                'nationality' => trim($row[53]),
            ];

            if (strlen($beneficiaryLegal['nationality']) > 0) {
                $tempNationality = explode(',', $beneficiaryLegal['nationality']);
                $beneficiaryLegal['nationality'] = $tempNationality[1];
            }

            if ($newRecord) {
                $dataRow['identificationDataPersonBeneficiary']['legalPerson'] = $beneficiaryLegal;
            }

            // Trust
            $beneficiaryTrust = [
                'denomination' => trim($row[54]),
                'tax_id' => trim($row[55]),
                'identifier' => trim($row[56]),
            ];


            if ($newRecord) {
                $dataRow['identificationDataPersonBeneficiary']['trust'] = $beneficiaryTrust;
            }

            // ACT OR OPERATION
            // Managed Asset
            $otherAssets = [
                'operation_date' => trim($row[57]),
                'other_assets' => trim($row[58]),
                'operation_number' => trim($row[59]),
            ];

            if (strlen($otherAssets['operation_date']) > 0) {
                $newOperationHash = uniqid();
                $dataRow['actOrOperation'][$newOperationHash]['otherAssets'][] = $otherAssets;
            }

            //if ($newRecord) {
            //$dataRow['actOrOperation'][$newOperationHash]['otherAssets'][] = $otherAssets;
            //}

            //Financial Operations
            $financialOperations = [
                'payment_date' => trim($row[60]),
                'monetary_instrument' => trim($row[61]),
                'virtual_asset_type' => trim($row[62]),
                'virtual_asset_description' => trim($row[63]),
                'virtual_asset_amount' => trim($row[64]),
                'currency' => trim($row[65]),
                'operation_amount' => trim($row[66]),
            ];

            if (strlen($financialOperations['monetary_instrument']) > 0) {
                $tempInstrument = explode(',', $financialOperations['monetary_instrument']);
                $financialOperations['monetary_instrument'] = $tempInstrument[0];
            }

            if (strlen($financialOperations['virtual_asset_type']) > 0) {
                $tempVirtualAssetType = explode(',', $financialOperations['virtual_asset_type']);
                $financialOperations['virtual_asset_type'] = $tempVirtualAssetType[0];
            }

            if (strlen($financialOperations['currency']) > 0) {
                $tempCurrency = explode(',', $financialOperations['currency']);
                $financialOperations['currency'] = $tempCurrency[0];
            }

            //if ($newRecord) {
                $dataRow['actOrOperation'][$newOperationHash]['financialOperation'][] = $financialOperations;
            //}


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
