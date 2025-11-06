<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MutualLoanCreditImport implements ToCollection, WithMultipleSheets
{
    private array $data = [];

    public function collection(Collection $collection): void
    {
        foreach ($collection->skip(3) as $row) {

            //Modification
            $modification = [
                'folio' => trim(strtoupper($row[0])), //A
                'description' => trim(strtoupper($row[1])),
                'priority' => trim(strtoupper($row[2])),
            ];

            if (strlen($modification['priority']) > 0) {
                $tempPriority = explode(' - ', $modification['priority']);
                $modification['priority'] = trim($tempPriority[0]);
            }

            $dataRow['modification'] = $modification;

            //Alert
            $alert = [
                'alert_type' => trim(strtoupper($row[3])),
                'alert_description' => trim(strtoupper($row[4])),
            ];

            if (strlen($alert['alert_type']) > 0) {
                $alertType = explode(' - ', $alert['alert_type']);
                $alert['alert_type'] = trim($alertType[0]);
            }

            $dataRow['alert'] = $alert;

            //Identification data of the person subject of the notice
            $physicalPerson = [
                'name' => trim(strtoupper($row[5])), //F
                'last_name' => trim(strtoupper($row[6])),
                'second_last_name' => trim(strtoupper($row[7])),
                'birthdate' => trim(strtoupper($row[8])),
                'tax_id' => trim(strtoupper($row[9])),
                'population_id' => trim(strtoupper($row[10])),
                'nationality' => trim(strtoupper($row[11])),
                'economic_activity' => trim(strtoupper($row[12])), //K
            ];

            if (strlen($physicalPerson['economic_activity']) > 0) {
                $tempActivity = explode('||', $physicalPerson['economic_activity']);
                $physicalPerson['economic_activity'] = $tempActivity[1];
            }

            if (strlen($physicalPerson['nationality']) > 0) {
                $tempNationality = explode(',', $physicalPerson['nationality']);
                $physicalPerson['nationality'] = $tempNationality[1];
            }

            $dataRow['identificationDataPersonSubjectNotice']['physicalPerson'] = $physicalPerson;

            //Legal person
            $legalPerson = [
                'company_name' => trim(strtoupper($row[13])), //N
                'constitution_date' => trim(strtoupper($row[14])),
                'tax_id' => trim(strtoupper($row[15])),
                'nationality' => trim(strtoupper($row[16])),
                'commercial_business' => trim(strtoupper($row[17])), //Q
            ];

            if (strlen($legalPerson['commercial_business']) > 0) {
                $tempCommercialBusiness = explode('||', $legalPerson['commercial_business']);
                $legalPerson['commercial_business'] = $tempCommercialBusiness[1];
            }

            if (strlen($legalPerson['nationality']) > 0) {
                $tempNationality = explode(',', $legalPerson['nationality']);
                $legalPerson['nationality'] = $tempNationality[1];
            }

            $dataRow['identificationDataPersonSubjectNotice']['legalPerson'] = $legalPerson;

            //trust
            $trust = [
                'denomination' => trim(strtoupper($row[18])), //s
                'tax_id' => trim(strtoupper($row[19])),
                'identification' => trim(strtoupper($row[20])),
            ];

            $dataRow['identificationDataPersonSubjectNotice']['trust'] = $trust;

            //representative data
            $representative = [
                'name' => trim(strtoupper($row[21])), //T
                'last_name' => trim(strtoupper($row[22])),
                'second_last_name' => trim(strtoupper($row[23])),
                'birthdate' => trim(strtoupper($row[24])),
                'tax_id' => trim(strtoupper($row[25])),
                'population_id' => trim(strtoupper($row[26])), //Y
            ];

            $dataRow['identificationDataPersonSubjectNotice']['representativeData'] = $representative;

            $nationalAddress = [
                'settlement' => trim(strtoupper($row[27])), //Z
                'street' => trim(strtoupper($row[28])),
                'external_number' => trim(strtoupper($row[29])),
                'internal_number' => trim(strtoupper($row[30])),
                'postal_code' => trim(strtoupper($row[31])), //AD
            ];

            $dataRow['identificationDataPersonSubjectNotice']['nationalAddress'] = $nationalAddress;

            $foreignAddress = [
                'country' => trim(strtoupper($row[32])), //AE
                'state' => trim(strtoupper($row[33])),
                'municipality' => trim(strtoupper($row[34])),
                'settlement' => trim(strtoupper($row[35])),
                'street' => trim(strtoupper($row[36])),
                'external_number' => trim(strtoupper($row[37])),
                'internal_number' => trim(strtoupper($row[38])),
                'postal_code' => trim(strtoupper($row[39])), //AL
            ];

            if (strlen($foreignAddress['country']) > 0) {
                $temCountry = explode(',', $foreignAddress['country']);
                $foreignAddress['country'] = $temCountry[1];
            }

            $dataRow['identificationDataPersonSubjectNotice']['foreignAddress'] = $foreignAddress;

            //Contact
            $contact = [
                'country' => trim(strtoupper($row[40])), //AM
                'phone' => trim(strtoupper($row[41])),
                'email' => trim(strtoupper($row[42])), //AO
            ];

            if (strlen($contact['country']) > 0) {
                $temCountry = explode(',', $contact['country']);
                $contact['country'] = $temCountry[1];
            }

            $dataRow['identificationDataPersonSubjectNotice']['contact'] = $contact;

            //Identification data of the beneficiary or owner
            //Physical person
            $physicalPerson = [
                'name' => trim(strtoupper($row[43])), //AP
                'last_name' => trim(strtoupper($row[44])),
                'second_last_name' => trim(strtoupper($row[45])),
                'birthdate' => trim(strtoupper($row[46])),
                'tax_id' => trim(strtoupper($row[47])),
                'population_id' => trim(strtoupper($row[48])),
                'nationality' => trim(strtoupper($row[49])), // AV
            ];

            if (strlen($physicalPerson['nationality']) > 0) {
                $tempNationality = explode(',', $physicalPerson['nationality']);
                $physicalPerson['nationality'] = $tempNationality[1];
            }

            $dataRow['identificationDataPersonBeneficiary']['physicalPerson'] = $physicalPerson;

            //legal person
            $legalPerson = [
                'company_name' => trim(strtoupper($row[50])), //AW
                'constitution_date' => trim(strtoupper($row[51])),
                'tax_id' => trim(strtoupper($row[52])),
                'nationality' => trim(strtoupper($row[53])), //AZ
            ];

            if (strlen($legalPerson['nationality']) > 0) {
                $tempNationality = explode(',', $legalPerson['nationality']);
                $legalPerson['nationality'] = $tempNationality[1];
            }

            $dataRow['identificationDataPersonBeneficiary']['legalPerson'] = $legalPerson;

            //trust
            $trust = [
                'tax_id' => trim(strtoupper($row[54])), //BA
                'denomination' => trim(strtoupper($row[55])),
                'identification' => trim(strtoupper($row[56])), //BC
            ];

            $dataRow['identificationDataPersonBeneficiary']['trust'] = $trust;

            //Operation data section
            //Operation data
            $operation = [
                'date_operation' => trim(strtoupper($row[57])), //BD
                'cp_place_operation' => trim(strtoupper($row[58])),
                'type_operation' => trim(strtoupper($row[59])),
                'guarantee_type' => trim(strtoupper($row[60])),
            ];

            if (strlen($operation['type_operation']) > 0) {
                $tempTypeOperation = explode(',', $operation['type_operation']);
                $operation['type_operation'] = $tempTypeOperation[0];
            }

            if (strlen($operation['guarantee_type']) > 0) {
                $tempTypeOperation = explode(',', $operation['guarantee_type']);
                $operation['guarantee_type'] = $tempTypeOperation[0];
            }

            $dataRow['operationDetails']['operationData'] = $operation;

            $guaranteeDetailsRealEstate = [
                'real_estate_type' => trim(strtoupper($row[61])), //BH
                'reference_value' => trim(strtoupper($row[62])),
                'cp' => trim(strtoupper($row[63])),
                'real_folio' => trim(strtoupper($row[64])),
            ];

            if (strlen($guaranteeDetailsRealEstate['real_estate_type']) > 0) {
                $tempTypeOperation = explode(',', $guaranteeDetailsRealEstate['real_estate_type']);
                $guaranteeDetailsRealEstate['real_estate_type'] = $tempTypeOperation[0];
            }

            $dataRow['operationDetails']['guaranteeDetailsRealEstate'] = $guaranteeDetailsRealEstate;

            $guaranteeDetailsOther = [
                'description' => trim(strtoupper($row[65])), //BL
            ];

            $dataRow['operationDetails']['guaranteeDetailsOther'] = $guaranteeDetailsOther;

            //Identification data of the guarantee
            //Physical person
            $physicalPerson = [
                'name' => trim(strtoupper($row[66])), //BM
                'last_name' => trim(strtoupper($row[67])),
                'second_last_name' => trim(strtoupper($row[68])),
                'birthdate' => trim(strtoupper($row[69])),
                'tax_id' => trim(strtoupper($row[70])),
                'population_id' => trim(strtoupper($row[71])),
            ];

            $dataRow['operationDetails']['identificationDataPersonGuarantee']['physicalPerson'] = $physicalPerson;

            //legal person
            $legalPerson = [
                'company_name' => trim(strtoupper($row[72])), //BS
                'constitution_date' => trim(strtoupper($row[73])),
                'tax_id' => trim(strtoupper($row[74])),
            ];

            $dataRow['operationDetails']['identificationDataPersonGuarantee']['legalPerson'] = $legalPerson;

            //trust
            $trust = [
                'denomination' => trim(strtoupper($row[75])), //BV
                'tax_id' => trim(strtoupper($row[76])),
                'identification' => trim(strtoupper($row[77])),
            ];

            $dataRow['operationDetails']['identificationDataPersonGuarantee']['trust'] = $trust;

            $operationSaleData = [
                'disposition_date' => trim(strtoupper($row[78])), //BY
                'monetary_instrument' => trim(strtoupper($row[79])),
                'currency' => trim(strtoupper($row[80])),
                'amount_operation' => trim(strtoupper($row[81])),
            ];

            if (strlen($operationSaleData['monetary_instrument']) > 0) {
                $tempMonetaryInstrument = explode(',', $operationSaleData['monetary_instrument']);
                $operationSaleData['monetary_instrument'] = $tempMonetaryInstrument[0];
            }

            if (strlen($operationSaleData['currency']) > 0) {
                $tempCurrency = explode(',', $operationSaleData['currency']);
                $operationSaleData['currency'] = $tempCurrency[0];
            }

            $dataRow['operationDetails']['operationSaleData'] = $operationSaleData;
            $this->data['items'][] = $dataRow;
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
