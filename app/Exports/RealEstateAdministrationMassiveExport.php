<?php

namespace App\Exports;

use App\Models\PLDNoticeAddress;
use App\Models\PLDNoticeContact;
use App\Models\PLDNoticeEstate;
use App\Models\PLDNoticeFinancialOperation;
use App\Models\PLDNoticeMassive;
use App\Models\PLDNoticePerson;
use App\Models\PLDNoticeUniqueDataPerson;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\ArrayToXml\ArrayToXml;

class RealEstateAdministrationMassiveExport
{
    protected string $pldNoticeMassiveId;
    protected PLDNoticeMassive $noticeMassive;
    protected Collection $noticeNotices;
    protected array $formData = [];
    public function __construct(string $pldNoticeMassiveId)
    {
        $this->pldNoticeMassiveId = $pldNoticeMassiveId;
        $this->noticeMassive = PLDNoticeMassive::find($pldNoticeMassiveId);
        $this->noticeNotices = $this->noticeMassive->notices;
        $this->formData = $this->noticeMassive->form_data;
    }

    /**
     * @return string
     */
    public function toXml()
    {
        $avisos = [];
        foreach ($this->noticeNotices as $notice) {
            $aviso = [
                'referencia_aviso' => $this->formData['notice_reference'],
            ];

            if (!empty($notice->modification_folio)) {
                $aviso['modificatorio'] = [
                    'folio_modificacion' => $notice->modification_folio,
                    'descripcion_modificacion' => $notice->modification_description,
                ];
            }

            $aviso['prioridad'] = $notice->priority;

            $aviso['alerta'] = [
                'tipo_alerta' => array_merge([$notice->alert_type],
                    $notice->alert_type == '9999' ? ['descripcion_otra_alerta' => $notice->alert_description] : []),
            ];

            $aviso['persona_aviso'] = $this->personObject(
                $notice->objectPerson,
                $notice->address,
                $notice->contact,
                $notice->legalRepresentativePerson);

            $aviso['dueno_beneficiario'] = $this->personBeneficiary($notice->beneficiaryPerson);
            $aviso['detalle_operaciones']['datos_operacion'] = $this->operationData($notice->uniqueData, $notice->financialOperation, $notice->estateOperation);

            $avisos[] = $aviso;
        }

        $tipoOcupacion = $this->formData['occupation_type'];

        $data = [
            'informe' => [
                'mes_reportado' => $this->formData['month'],
                'sujeto_obligado' => [
                    // Solo se agrega si tiene valor
                    ...(!empty($this->formData['collegiate_entity_tax_id']) ? [
                        'clave_entidad_colegiada' => $this->formData['collegiate_entity_tax_id'],
                    ] : []),

                    'clave_sujeto_obligado' => $this->formData['obligated_subject'],
                    'ocupacion' => array_merge(
                        ['tipo_ocupacion' => $tipoOcupacion],
                        $tipoOcupacion == '99'
                            ? ['descripcion_otra_ocupacion' => $this->formData['occupation_description']]
                            : []
                    ),
                    'clave_actividad' => 'SPR',
                ],
                'aviso' => $avisos,
            ],
        ];

        $xmlString = ArrayToXml::convert($data, [
            'rootElementName' => 'archivo',
            '_attributes' => [
                'xsi:schemaLocation' => 'http://www.uif.shcp.gob.mx/recepcion/spr spr.xsd',
                'xmlns' => 'http://www.uif.shcp.gob.mx/recepcion/spr',
                'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            ]
        ], true, 'UTF-8');

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlString);

        return $dom->saveXML(); // devuelve el XML con tabulaciones
    }

    public function operationData(PLDNoticeUniqueDataPerson $uniqueData, Collection $financialOperation, Collection $estates): array
    {
        $data['fecha_operacion'] = $uniqueData->operation_date;

        foreach ($estates as $estate) {
            $estateData = [
                'tipo_inmueble' => $estate->estate_type,
                'valor_referencia' => number_format($estate->reference_value, 2, '.', ''),
                'colonia' => $estate->settlement,
                'calle' => $estate->street,
                'numero_exterior' => $estate->external_number,
                'numero_interior' => $estate->internal_number,
                'codigo_postal' => str_pad($estate->postal_code, 5, '0', STR_PAD_LEFT),
                'folio_real' => $estate->real_folio,
            ];

            if (empty($estate->internal_number)) {
                unset($estateData['numero_interior']);
            }
            $data['tipo_actividad']['administracion_recursos']['tipo_activo'][] = ['activo_inmobiliario' => $estateData];
            $data['tipo_actividad']['administracion_recursos']['numero_operaciones'] = $uniqueData->reported_operations;
        }

        foreach ($financialOperation as $financial) {
            $financialData = [
                'instrumento_monetario' => $financial->monetary_instrument,
                'moneda' => $financial->currency,
                'monto_operacion' => number_format($financial->amount, 2, '.', ''),
            ];
            $data['datos_operacion_financiera'][] = $financialData;
        }

        return $data;

    }
    public function personBeneficiary(PLDNoticePerson $beneficiaryPerson): array {
        $data['tipo_persona'] = [];
        if ($beneficiaryPerson->person_type == 'individual') {
            $individual = [
                'persona_fisica' => [
                    'nombre' => $beneficiaryPerson->name_or_company,
                    'apellido_paterno' => $beneficiaryPerson->paternal_last_name,
                    'apellido_materno' => $beneficiaryPerson->maternal_last_name,
                    'fecha_nacimiento' => $beneficiaryPerson->birth_or_constitution_date,
                    'rfc' => $beneficiaryPerson->tax_id,
                    'curp' => $beneficiaryPerson->personal_id,
                    'pais_nacionalidad' => $beneficiaryPerson->nationality,
                ]
            ];
            $data['tipo_persona'] = $individual;
        }

        if ($beneficiaryPerson->person_type == 'legal') {
            $legal = [
                'persona_moral' => [
                    'denominacion_razon' => $beneficiaryPerson->name_or_company,
                    'fecha_constitucion' => $beneficiaryPerson->birth_or_constitution_date,
                    'rfc' => $beneficiaryPerson->tax_id,
                    'pais_nacionalidad' => $beneficiaryPerson->nationality,
                ]
            ];
            $data['tipo_persona'] = $legal;
        }

        if ($beneficiaryPerson->person_type == 'trust') {
            $trust = [
                'fideicomiso' => [
                    'denominacion_razon' => $beneficiaryPerson->name_or_company,
                    'rfc' => $beneficiaryPerson->tax_id,
                    'identificador_fideicomiso' => $beneficiaryPerson->trust_identification
                ]
            ];
            $data['tipo_persona'] = $trust;
        }

        return $data;
    }
    public function personObject(
        PLDNoticePerson $personObject,
        PLDNoticeAddress $personAddress,
        PLDNoticeContact $contact,
        ?PLDNoticePerson $legalRepresentativePerson = null): array
    {
        $data['tipo_persona'] = [];
        if ($personObject->person_type == 'individual') {
            $individual = [
                'persona_fisica' => [
                    'nombre' => $personObject->name_or_company,
                    'apellido_paterno' => $personObject->paternal_last_name,
                    'apellido_materno' => $personObject->maternal_last_name,
                    'fecha_nacimiento' => $personObject->birth_or_constitution_date,
                    'rfc' => $personObject->tax_id,
                    'curp' => $personObject->personal_id,
                    'pais_nacionalidad' => $personObject->nationality,
                    'actividad_economica' => $personObject->business_activity
                ]
            ];

            if ($legalRepresentativePerson) {
                $individual['persona_fisica']['representante_apoderado'] = [
                    'nombre' => $legalRepresentativePerson->name_or_company,
                    'apellido_paterno' => $legalRepresentativePerson->paternal_last_name,
                    'apellido_materno' => $legalRepresentativePerson->maternal_last_name,
                    'fecha_nacimiento' => $legalRepresentativePerson->birth_or_constitution_date,
                    'rfc' => $legalRepresentativePerson->tax_id,
                    'curp' => $legalRepresentativePerson->personal_id,
                ];
            }
            $data['tipo_persona'] = $individual;
        }

        if ($personObject->person_type == 'legal') {
            $legal = [
                'persona_moral' => [
                    'denominacion_razon' => $personObject->name_or_company,
                    'fecha_constitucion' => $personObject->birth_or_constitution_date,
                    'rfc' => $personObject->tax_id,
                    'pais_nacionalidad' => $personObject->nationality,
                    'giro_mercantil' => $personObject->business_activity,
                ]
            ];

            if ($legalRepresentativePerson) {
                $legal['persona_moral']['representante_apoderado'] = [
                    'nombre' => $legalRepresentativePerson->name_or_company,
                    'apellido_paterno' => $legalRepresentativePerson->paternal_last_name,
                    'apellido_materno' => $legalRepresentativePerson->maternal_last_name,
                    'fecha_nacimiento' => $legalRepresentativePerson->birth_or_constitution_date,
                    'rfc' => $legalRepresentativePerson->tax_id,
                    'curp' => $legalRepresentativePerson->personal_id,
                ];
            }
            $data['tipo_persona'] = $legal;
        }

        if ($personObject->person_type == 'trust') {
            $trust = [
                'fideicomiso' => [
                    'denominacion_razon' => $personObject->name_or_company,
                    'rfc' => $personObject->tax_id,
                    'identificador_fideicomiso' => $personObject->trust_identification
                ]
            ];

            if ($legalRepresentativePerson) {
                $trust['fideicomiso']['representante_apoderado'] = [
                    'nombre' => $legalRepresentativePerson->name_or_company,
                    'apellido_paterno' => $legalRepresentativePerson->paternal_last_name,
                    'apellido_materno' => $legalRepresentativePerson->maternal_last_name,
                    'fecha_nacimiento' => $legalRepresentativePerson->birth_or_constitution_date,
                    'rfc' => $legalRepresentativePerson->tax_id,
                    'curp' => $legalRepresentativePerson->personal_id,
                ];
            }
            $data['tipo_persona'] = $trust;
        }

        $data['tipo_domicilio'] = $this->personObjectAddress($personAddress);
        $data['telefono'] = $this->personObjectContact($contact);
        return $data;
    }

    public function personObjectContact(PLDNoticeContact $contact): array
    {
        $data = ['clave_pais' => $contact->country];

        if (!empty($contact->phone_number) && !empty($contact->email))  {
            $data['numero_telefono'] = $contact->phone_number;
            $data['correo_electronico'] = $contact->email;
        }

        if (empty($contact->phone_number)){
            $data['correo_electronico'] = $contact->email;
        }

        if (empty($contact->email)){
            $data['numero_telefono'] = $contact->phone_number;
        }

        return $data;
    }

    public function personObjectAddress(PLDNoticeAddress $addresse): array
    {
        $data = [];
        if ($addresse->type == 'national') {
            $data['nacional'] = [
                'colonia' => $addresse->settlement,
                'calle' => $addresse->street,
                'numero_exterior' => $addresse->external_number,
                'numero_interior' => $addresse->internal_number,
                'codigo_postal' => str_pad($addresse->postal_code, 5, '0', STR_PAD_LEFT),
            ];

            if (empty($addresse->internal_number)) {
                unset($data['nacional']['numero_interior']);
            }
        }

        if ($addresse->type == 'foreign') {
            $data['extranjero'] = [
                'pais' => $addresse->country,
                'estado_provincia' => $addresse->state,
                'ciudad_poblacion' => $addresse->city,
                'colonia' => $addresse->settlement,
                'calle' => $addresse->street,
                'numero_exterior' => $addresse->external_number,
                'numero_interior' => $addresse->internal_number,
                'codigo_postal' => str_pad($addresse->postal_code, 5, '0', STR_PAD_LEFT),
            ];

            if (empty($addresse->internal_number)) {
                unset($data['extranjero']['numero_interior']);
            }
        }

        return $data;
    }
}
