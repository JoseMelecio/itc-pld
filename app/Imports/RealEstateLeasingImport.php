<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RealEstateLeasingImport implements ToCollection, WithMultipleSheets
{
    private array $data = [];

    public function collection(Collection $collection): void
    {
        $isLeasing = false;
        $numLeasing = 0;

        foreach ($collection->skip(3) as $row) {
            /**
             * We read the type of lease to know if there is registration or only payments
             * if there is a type of lease it means that it is a new registration,
             * If there is no type of lease it means that they are only payments.
             */
            if (strlen(trim(strtoupper($row[58]))) > 0) { //BG
                $isLeasing = true;
                $numLeasing++;
            } else {
                $isLeasing = false;
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

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['modification'] = $modification;
            }

            $alert = [
                'alert_type' => trim(strtoupper($row[3])), //D
                'alert_description' => trim(strtoupper($row[4])),
            ];

            if (strlen($alert['alert_type']) > 0) {
                $tempAlertType = explode(',', $alert['alert_type']);
                $alert['alert_type'] = trim($tempAlertType[0]);
            }


            if ($isLeasing) {
                $this->data['items'][$numLeasing]['alert'] = $alert;
            }

            //Identification data of the person subject of the notice
            $physicalPerson = [
                'name' => trim(strtoupper($row[5])), //D
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

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['identificationDataPersonSubjectNotice']['physicalPerson'] = $physicalPerson;
            }

            //Legal person
            $legalPerson = [
                'company_name' => trim(strtoupper($row[13])), //L
                'constitution_date' => trim(strtoupper($row[14])),
                'tax_id' => trim(strtoupper($row[15])),
                'nationality' => trim(strtoupper($row[16])),
                'commercial_business' => trim(strtoupper($row[17])), //P
            ];

            if (strlen($legalPerson['commercial_business']) > 0) {
                $tempCommercialBusiness = explode('||', $legalPerson['commercial_business']);
                $legalPerson['commercial_business'] = $tempCommercialBusiness[1];
            }

            if (strlen($legalPerson['nationality']) > 0) {
                $tempNationality = explode(',', $legalPerson['nationality']);
                $legalPerson['nationality'] = $tempNationality[1];
            }

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['identificationDataPersonSubjectNotice']['legalPerson'] = $legalPerson;
            }

            //trust
            $trust = [
                'denomination' => trim(strtoupper($row[18])), //Q
                'tax_id' => trim(strtoupper($row[19])),
                'identification' => trim(strtoupper($row[20])),
            ];

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['identificationDataPersonSubjectNotice']['trust'] = $trust;
            }

            //representative data
            $representative = [
                'name' => trim(strtoupper($row[21])), //T
                'last_name' => trim(strtoupper($row[22])),
                'second_last_name' => trim(strtoupper($row[23])),
                'birthdate' => trim(strtoupper($row[24])),
                'tax_id' => trim(strtoupper($row[25])),
                'population_id' => trim(strtoupper($row[26])), //Y
            ];

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['identificationDataPersonSubjectNotice']['representativeData'] = $representative;
            }

            $nationalAddress = [
                'settlement' => trim(strtoupper($row[27])), //Z
                'street' => trim(strtoupper($row[28])),
                'external_number' => trim(strtoupper($row[29])),
                'internal_number' => trim(strtoupper($row[30])),
                'postal_code' => trim(strtoupper($row[31])), //AD
            ];

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['identificationDataPersonSubjectNotice']['nationalAddress'] = $nationalAddress;
            }

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

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['identificationDataPersonSubjectNotice']['foreignAddress'] = $foreignAddress;
            }

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

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['identificationDataPersonSubjectNotice']['contact'] = $contact;
            }

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

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['identificationDataPersonBeneficiary']['physicalPerson'] = $physicalPerson;
            }

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

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['identificationDataPersonBeneficiary']['legalPerson'] = $legalPerson;
            }

            //trust
            $trust = [
                'tax_id' => trim(strtoupper($row[54])), //BA
                'denomination' => trim(strtoupper($row[55])),
                'identification' => trim(strtoupper($row[56])), //BC
            ];

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['identificationDataPersonBeneficiary']['trust'] = $trust;
            }

            //Operation data section
            //Operation data
            $operation = [
                'date_operation' => trim(strtoupper($row['57'])), //BD
                'type_operation' => '1501',
            ];

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['operationDetails']['operationData'] = $operation;
            }

            //leasing characteristic
            $leasingCharacteristic = [
                'start_date' => trim(strtoupper($row[58])), //BE
                'end_date' => trim(strtoupper($row[59])),
                'property_type' => trim(strtoupper($row[60])),
                'reference_value' => trim(strtoupper($row[61])),
                'settlement' => trim(strtoupper($row[62])),
                'street' => trim(strtoupper($row[63])),
                'external_number' => trim(strtoupper($row[64])),
                'internal_number' => trim(strtoupper($row[65])),
                'postal_code' => trim(strtoupper($row[66])),
                'real_folio' => trim(strtoupper($row[67])), //BN
            ];

            if (strlen($leasingCharacteristic['property_type']) > 0) {
                $tempPropertyType = explode(',', $leasingCharacteristic['property_type']);
                $leasingCharacteristic['property_type'] = $tempPropertyType[0];
            }

            if ($isLeasing) {
                $this->data['items'][$numLeasing]['operationDetails']['leasingCharacteristic'] = $leasingCharacteristic;
            }

            //sale data
            $saleData = [
                'payment_date' => trim(strtoupper($row[68])), //BO
                'payment_way' => trim(strtoupper($row[69])),
                'monetary_instrument' => trim(strtoupper($row[70])),
                'currency' => trim(strtoupper($row[71])),
                'amount_operation' => trim(strtoupper($row[72])),
            ];

            if (strlen($saleData['payment_way']) > 0) {
                $tempPaymentWay = explode(',', $saleData['payment_way']);
                $saleData['payment_way'] = $tempPaymentWay[0];
            }

            if (strlen($saleData['monetary_instrument']) > 0) {
                $tempMonetaryInstrument = explode(',', $saleData['monetary_instrument']);
                $saleData['monetary_instrument'] = $tempMonetaryInstrument[0];
            }

            if (strlen($saleData['currency']) > 0) {
                $tempCurrency = explode(',', $saleData['currency']);
                $saleData['currency'] = $tempCurrency[0];
            }

            $this->data['items'][$numLeasing]['operationDetails']['saleData'][] = $saleData;
        }
    }

    /**
     * Retorna los datos procesados.
     */
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
