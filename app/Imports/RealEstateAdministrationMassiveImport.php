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


class RealEstateAdministrationMassiveImport implements ToCollection, WithChunkReading, WithStartRow, WithMultipleSheets
{
    protected string $pldNoticeMassiveId;
    protected PLDNoticeMassive $noticeMassive;
    protected ?PLDNoticeNotice $currentNotice = null;

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
            $this->sRow = $this->sanitizeRow($row->toArray());

            $this->notice();
            $this->financialData();
            $this->estateData();
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
        }

        return $sanitizedRow;
    }
    public function hash(): string
    {

        if (strlen($this->sRow[5]) > 0) {
            return md5($this->sRow[5] . $this->sRow[6] . $this->sRow[7] . $this->sRow[8] . $this->sRow[9] . $this->sRow[10]);
        }

        if (strlen($this->sRow[13]) > 0) {
            return md5($this->sRow[13] . $this->sRow[14] . $this->sRow[15]);
        }

        return md5($this->sRow[18] . $this->sRow[19]);
    }

    public function notice(): void
    {
        $this->currentNotice = PLDNoticeNotice::where('pld_notice_massive_id', $this->pldNoticeMassiveId)
            ->where('hash', $this->hash())
            ->first();

        Log::info($this->sRow[3]);
        if (!$this->currentNotice) {
            $this->currentNotice = PLDNoticeNotice::create([
                'pld_notice_id' => $this->noticeMassive->pld_notice_id,
                'pld_notice_massive_id' => $this->noticeMassive->id,
                'hash' => $this->hash(),
                'modification_folio' => $this->sRow[0],
                'modification_description' => $this->sRow[1],
                'priority' => $this->sRow[2],
                'alert_type' => $this->sRow[3],
                'alert_description' => $this->sRow[4],
            ]);

            // Se agregan aqui porque son unicos por aviso
            $this->objectPerson();
            $this->address();
            $this->contact();
            $this->beneficiaryPerson();
            $this->uniqueData();
        }
    }

    public function objectPerson(): void
    {
        $personType = '';
        if (strlen($this->sRow[5]) > 0) {
            $personType = 'individual';
        }

        if (strlen($this->sRow[13]) > 0) {
            $personType = 'legal';
        }

        if (strlen($this->sRow[19]) > 0) {
            $personType = 'trust';
        }

        $data = [
            'pld_notice_notice_id' => $this->currentNotice->id,
            'person_notice_type' => 'object',
            'person_type' => $personType,
        ];

        if ($personType === 'individual') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[5],
                'paternal_last_name' => $this->sRow[6],
                'maternal_last_name' => $this->sRow[7],
                'birth_or_constitution_date' => $this->sRow[8],
                'tax_id' => $this->sRow[9],
                'personal_id' => $this->sRow[10],
                'nationality' => $this->sRow[11],
                'business_activity' => $this->sRow[12],
            ]);
        }

        if ($personType === 'legal') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[13],
                'birth_or_constitution_date' => $this->sRow[14],
                'tax_id' => $this->sRow[15],
                'nationality' => $this->sRow[16],
                'business_activity' => $this->sRow[17],
            ]);
        }

        if ($personType === 'trust') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[18],
                'tax_id' => $this->sRow[19],
                'trust_identification' => $this->sRow[20]
            ]);
        }

        $this->currentNotice->objectPerson()->create($data);

        if($personType === 'legal' || $personType === 'trust' ) {
            $representativeData = [
                'pld_notice_notice_id' => $this->currentNotice->id,
                'person_notice_type' => 'representative',
                'person_type' => 'individual',
                'name_or_company' => $this->sRow[21],
                'paternal_last_name' => $this->sRow[22],
                'maternal_last_name' => $this->sRow[23],
                'birth_or_constitution_date' => $this->sRow[24],
                'tax_id' => $this->sRow[25],
                'personal_id' => $this->sRow[26],
            ];
            $this->currentNotice->objectPerson()->create($representativeData);
        }
    }

    public function address(): void
    {
        if (strlen($this->sRow[27]) > 0) {
            $this->currentNotice->address()->create([
                'pld_notice_notice_id' => $this->currentNotice->id,
                'type' => 'national',
                'state' => $this->sRow[27],
                'city' => $this->sRow[28],
                'settlement' => $this->sRow[29],
                'postal_code' => $this->sRow[30],
                'street' => $this->sRow[31],
                'external_number' => $this->sRow[32],
                'internal_number' => $this->sRow[33],
            ]);
        } else {
            $this->currentNotice->address()->create([
                'pld_notice_notice_id' => $this->currentNotice->id,
                'type' => 'foreign',
                'country' => $this->sRow[34],
                'state' => $this->sRow[35],
                'city' => $this->sRow[36],
                'settlement' => $this->sRow[37],
                'street' => $this->sRow[38],
                'external_number' => $this->sRow[39],
                'internal_number' => $this->sRow[40],
                'postal_code' => $this->sRow[41],
            ]);
        }
    }

    public function contact(): void
    {
        $this->currentNotice->contact()->create([
            'pld_notice_notice_id' => $this->currentNotice->id,
            'country' => $this->sRow[42],
            'phone_number' => $this->sRow[43],
            'email' => $this->sRow[44],
        ]);
    }

    public function beneficiaryPerson(): void
    {
        $personType = '';
        if (strlen($this->sRow[45]) > 0) {
            $personType = 'individual';
        }

        if (strlen($this->sRow[52]) > 0) {
            $personType = 'legal';
        }

        if (strlen($this->sRow[56]) > 0) {
            $personType = 'trust';
        }

        $data = [
            'pld_notice_notice_id' => $this->currentNotice->id,
            'person_notice_type' => 'beneficiary',
            'person_type' => $personType,
        ];

        if ($personType === 'individual') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[45],
                'paternal_last_name' => $this->sRow[46],
                'maternal_last_name' => $this->sRow[47],
                'birth_or_constitution_date' => $this->sRow[48],
                'tax_id' => $this->sRow[49],
                'personal_id' => $this->sRow[50],
                'nationality' => $this->sRow[51],
            ]);
        }

        if ($personType === 'legal') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[52],
                'birth_or_constitution_date' => $this->sRow[53],
                'tax_id' => $this->sRow[54],
                'nationality' => $this->sRow[55],
            ]);
        }

        if ($personType === 'trust') {
            $data = array_merge($data, [
                'name_or_company' => $this->sRow[56],
                'tax_id' => $this->sRow[57],
                'trust_identification' => $this->sRow[58]
            ]);
        }

        $this->currentNotice->beneficiaryPerson()->create($data);
    }

    public function uniqueData(): void
    {
        $this->currentNotice->uniqueData()->create([
            'pld_notice_notice_id' => $this->currentNotice->id,
            'operation_date' => $this->sRow[59],
            'reported_operations' => $this->sRow[60],
        ]);
    }

    public function financialData(): void
    {
        $this->currentNotice->financialOperation()->create([
            'pld_notice_notice_id' => $this->currentNotice->id,
            'monetary_instrument' => $this->sRow[61],
            'currency' => $this->sRow[62],
            'amount' => $this->sRow[63],
        ]);
    }

    public function estateData(): void
    {
        $this->currentNotice->estateOperation()->create([
            'pld_notice_notice_id' => $this->currentNotice->id,
            'estate_type' => $this->sRow[64],
            'reference_value' => $this->sRow[65],
            'postal_code' => $this->sRow[66],
            'state' => $this->sRow[67],
            'city' => $this->sRow[68],
            'settlement' => $this->sRow[69],
            'street' => $this->sRow[70],
            'external_number' => $this->sRow[71],
            'internal_number' => $this->sRow[72],
            'real_folio' => $this->sRow[73],
        ]);
    }
}
