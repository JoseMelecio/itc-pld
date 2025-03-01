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
                $tempPriority = explode(',', $modification['priority']);
                $modification['priority'] = trim($tempPriority[0]);
            }

            $dataRow['modification'] = $modification;

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

            $dataRow['identificationDataPersonSubjectNotice']['physicalPerson'] = $physicalPerson;

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

            $dataRow['identificationDataPersonSubjectNotice']['legalPerson'] = $legalPerson;

            //trust
            $trust = [
                'denomination' => trim(strtoupper($row[16])), //Q
                'tax_id' => trim(strtoupper($row[17])),
                'identification' => trim(strtoupper($row[18])),
            ];

            $dataRow['identificationDataPersonSubjectNotice']['trust'] = $trust;

            //representative data
            $representative = [
                'name' => trim(strtoupper($row[19])), //T
                'last_name' => trim(strtoupper($row[20])),
                'second_last_name' => trim(strtoupper($row[21])),
                'birthdate' => trim(strtoupper($row[22])),
                'tax_id' => trim(strtoupper($row[23])),
                'population_id' => trim(strtoupper($row[24])), //Y
            ];

            $dataRow['identificationDataPersonSubjectNotice']['representativeData'] = $representative;

            $nationalAddress = [
                'settlement' => trim(strtoupper($row[25])), //Z
                'street' => trim(strtoupper($row[26])),
                'external_number' => trim(strtoupper($row[27])),
                'internal_number' => trim(strtoupper($row[28])),
                'postal_code' => trim(strtoupper($row[29])), //AD
            ];

            $dataRow['identificationDataPersonSubjectNotice']['nationalAddress'] = $nationalAddress;

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

            $dataRow['identificationDataPersonSubjectNotice']['foreignAddress'] = $foreignAddress;

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

            $dataRow['identificationDataPersonSubjectNotice']['contact'] = $contact;

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

            $dataRow['identificationDataPersonBeneficiary']['physicalPerson'] = $physicalPerson;

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

            $dataRow['identificationDataPersonBeneficiary']['legalPerson'] = $legalPerson;

            //trust
            $trust = [
                'tax_id' => trim(strtoupper($row[52])), //BA
                'denomination' => trim(strtoupper($row[53])),
                'identification' => trim(strtoupper($row[54])), //BC
            ];

            $dataRow['identificationDataPersonBeneficiary']['trust'] = $trust;

            //Operation data section
            //Operation data
            $operation = [
                'date_operation' => trim(strtoupper($row['55'])), //BD
                'cp_place_operation' => trim(strtoupper($row['56'])),
                'type_operation' => trim(strtoupper($row['57'])),
                'guarantee_type' => trim(strtoupper($row['58'])),
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
                'real_estate_type' => trim(strtoupper($row['59'])), //BH
                'reference_value' => trim(strtoupper($row['60'])),
                'cp' => trim(strtoupper($row['61'])),
                'real_folio' => trim(strtoupper($row['62'])),
            ];

            if (strlen($guaranteeDetailsRealEstate['real_estate_type']) > 0) {
                $tempTypeOperation = explode(',', $guaranteeDetailsRealEstate['real_estate_type']);
                $guaranteeDetailsRealEstate['real_estate_type'] = $tempTypeOperation[0];
            }

            $dataRow['operationDetails']['guaranteeDetailsRealEstate'] = $guaranteeDetailsRealEstate;

            $guaranteeDetailsOther = [
                'description' => trim(strtoupper($row[63])), //BL
            ];

            $dataRow['operationDetails']['guaranteeDetailsOther'] = $guaranteeDetailsOther;

            //Identification data of the guarantee
            //Physical person
            $physicalPerson = [
                'name' => trim(strtoupper($row[64])), //BM
                'last_name' => trim(strtoupper($row[65])),
                'second_last_name' => trim(strtoupper($row[66])),
                'birthdate' => trim(strtoupper($row[67])),
                'tax_id' => trim(strtoupper($row[68])),
                'population_id' => trim(strtoupper($row[69])),
            ];

            $dataRow['operationDetails']['identificationDataPersonGuarantee']['physicalPerson'] = $physicalPerson;

            //legal person
            $legalPerson = [
                'company_name' => trim(strtoupper($row[70])), //BS
                'constitution_date' => trim(strtoupper($row[71])),
                'tax_id' => trim(strtoupper($row[72])),
            ];

            $dataRow['operationDetails']['identificationDataPersonGuarantee']['legalPerson'] = $legalPerson;

            //trust
            $trust = [
                'tax_id' => trim(strtoupper($row[73])), //BV
                'denomination' => trim(strtoupper($row[74])),
                'identification' => trim(strtoupper($row[75])),
            ];

            $dataRow['operationDetails']['identificationDataPersonGuarantee']['trust'] = $trust;

            $operationSaleData = [
                'disposition_date' => trim(strtoupper($row[76])), //BY
                'monetary_instrument' => trim(strtoupper($row[77])),
                'currency' => trim(strtoupper($row[78])),
                'amount_operation' => trim(strtoupper($row[79])),
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
