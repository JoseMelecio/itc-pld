<?php

namespace App\Imports;

use App\Models\PLDNoticeMassive;
use App\Models\PLDNoticeNotice;
use App\Models\PLDNoticePerson;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class RealEstateAdministrationMassiveImport implements ToCollection, WithChunkReading, ShouldQueue, WithStartRow, WithMultipleSheets
{
    protected string $pldNoticeMassiveId;
    protected PLDNoticeMassive $noticeMassive;
    protected PLDNoticeNotice $currentNotice;

    protected array $sRow = [];
    protected $hash = '';

    public function __construct(string $pldNoticeMassiveId)
    {
        $this->pldNoticeMassiveId = $pldNoticeMassiveId;
        $this->noticeMassive = PLDNoticeMassive::find($pldNoticeMassiveId);
    }

    public function collection(Collection $collection): void
    {
        foreach ($collection as $row) {
            $this->sRow = $this->sanitizeRow($row);
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function startRow(): int
    {
        return 4;
    }

    public function sheets(): array
    {
        return [
            'Plantilla' => $this,
        ];
    }

    public function sanitizeRow(array $row): array
    {
        $sanitizedRow = [];
        foreach ($row as $key => $value) {
            if (Str::contains($value, " - ")) {
                $temp = explode(" - ", $value);
                $value = trim($temp[0]);
            }

            $sanitizedRow[$key] = Str::upper(trim($value));
            $this->hash();
            $this->objectPerson();
            $this->address();
            $this->contact();
            $this->beneficiaryPerson();
        }

        return $sanitizedRow;
    }
    public function hash(): string
    {

        if (strlen($this->sRow[3]) > 0) {
            return md5($this->sRow[3] . $this->sRow[4] . $this->sRow[5] . $this->sRow[6] . $this->sRow[7] . $this->sRow[8]);
        }

        if (strlen($this->sRow[11]) > 0) {
            return md5($this->sRow[11] . $this->sRow[12] . $this->sRow[13]);
        }

        return md5($this->sRow[16] . $this->sRow[17]);
    }

    public function notice(): void
    {
        $this->currentNotice = PLDNoticeNotice::where('notice_massive_id', $this->pldNoticeMassiveId)
            ->where('hash', $this->hash())
            ->first();

        if (!$this->currentNotice) {
            $this->currentNotice = PLDNoticeNotice::create([
                'pld_notice_id' => $this->noticeMassive->id,
                'hash' => $this->hash(),
                'modification_folio' => $this->sRow[0],
                'modification_description' => $this->sRow[1],
                'priority' => $this->sRow[2],
                'alert_type' => null,
                'alert_description' => null,
            ]);
        }
    }

    public function objectPerson(): void
    {
        $personType = '';
        if (strlen($this->sRow[3]) > 0) {
            $personType = 'individual';
        }

        if (strlen($this->sRow[11]) > 0) {
            $personType = 'legal';
        }

        if (strlen($this->sRow[16]) > 0) {
            $personType = 'trust';
        }

        $data = [
            'pld_notice_notice_id' => $this->currentNotice->id,
            'person_notice_type' => 'object',
            'person_type' => $personType,
        ];

        if ($personType === 'individual') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[3],
                'paternal_last_name' => $this->sRow[4],
                'maternal_last_name' => $this->sRow[5],
                'birth_or_constitution_date' => $this->sRow[6],
                'tax_id' => $this->sRow[7],
                'personal_id' => $this->sRow[8],
                'nationality' => $this->sRow[9],
                'economic_activity' => $this->sRow[10],
            ]);
        }

        if ($personType === 'legal') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[11],
                'birth_or_constitution_date' => $this->sRow[12],
                'tax_id' => $this->sRow[13],
                'nationality' => $this->sRow[14],
                'economic_activity' => $this->sRow[15],
            ]);
        }

        if ($personType === 'trust') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[16],
                'tax_id' => $this->sRow[17],
                'trust_identification' => $this->sRow[18]
            ]);
        }

        $this->currentNotice->objectPerson()->create($data);

        if($personType === 'legal' || $personType === 'trust' ) {
            $representativeData = [
                'pld_notice_notice_id' => $this->currentNotice->id,
                'person_notice_type' => 'representative',
                'person_type' => 'individual',
                'name_or_company' => $this->sRow[19],
                'paternal_last_name' => $this->sRow[20],
                'maternal_last_name' => $this->sRow[21],
                'birth_or_constitution_date' => $this->sRow[22],
                'tax_id' => $this->sRow[23],
                'personal_id' => $this->sRow[24],
            ];
            $this->currentNotice->objectPerson()->create($representativeData);
        }

    }

    public function address(): void
    {
        $data = [];
        if (strlen($this->sRow[25]) > 0) {
            $data = [
                'pld_notice_notice_id' => $this->currentNotice->id,
                'type' => 'national',
                'postal_code' => $this->sRow[25],
                'state' => $this->sRow[25],
                'city' => $this->sRow[27],
                'settlement' => $this->sRow[28],
                'street' => $this->sRow[29],
                'external_number' => $this->sRow[30],
                'internal_number' => $this->sRow[31],
            ];
        } else {
            $data = [
                'pld_notice_notice_id' => $this->currentNotice->id,
                'type' => 'foreign',
                'country' => $this->sRow[32],
                'state' => $this->sRow[33],
                'city' => $this->sRow[34],
                'settlement' => $this->sRow[35],
                'street' => $this->sRow[36],
                'external_number' => $this->sRow[37],
                'internal_number' => $this->sRow[37],
                'postal_code' => $this->sRow[39],
            ];
        }
        $this->currentNotice->address()->create($data);
    }

    public function contact(): void
    {
        $this->currentNotice->contact()->create([
            'pld_notice_notice_id' => $this->currentNotice->id,
            'country' => $this->sRow[40],
            'phone_number' => $this->sRow[41],
            'email' => $this->sRow[42],
        ]);
    }

    public function beneficiaryPerson(): void
    {
        $personType = '';
        if (strlen($this->sRow[43]) > 0) {
            $personType = 'individual';
        }

        if (strlen($this->sRow[50]) > 0) {
            $personType = 'legal';
        }

        if (strlen($this->sRow[54]) > 0) {
            $personType = 'trust';
        }

        $data = [
            'pld_notice_notice_id' => $this->currentNotice->id,
            'person_notice_type' => 'beneficiary',
            'person_type' => $personType,
        ];

        if ($personType === 'individual') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[43],
                'paternal_last_name' => $this->sRow[44],
                'maternal_last_name' => $this->sRow[45],
                'birth_or_constitution_date' => $this->sRow[46],
                'tax_id' => $this->sRow[47],
                'personal_id' => $this->sRow[48],
                'nationality' => $this->sRow[49],
            ]);
        }

        if ($personType === 'legal') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[50],
                'birth_or_constitution_date' => $this->sRow[51],
                'tax_id' => $this->sRow[52],
                'nationality' => $this->sRow[53],
            ]);
        }

        if ($personType === 'trust') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[54],
                'tax_id' => $this->sRow[55],
                'trust_identification' => $this->sRow[56]
            ]);
        }

        $this->currentNotice->objectPerson()->create($data);
    }

    public function unitData(): void
    {

    }

    public function operationData(): void
    {

    }

    public function estateData(): void
    {

    }
}
