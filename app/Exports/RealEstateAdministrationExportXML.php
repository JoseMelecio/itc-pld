<?php

namespace App\Exports;

use Illuminate\Support\Str;

class RealEstateAdministrationExportXML
{
    private array $data;

    private array $headers;

    public function __construct(array $data, array $headers)
    {
        $this->data = $data;
        $this->headers = $headers;
    }

    public function makeXML()
    {
        //make xml object
        $xmlObject = new \XMLWriter;
        $xmlObject->openMemory();
        $xmlObject->setIndent(true);
        $xmlObject->setIndentString("\t");

        //Add headers
        $xmlObject->startDocument('1.0', 'utf-8');
        $xmlObject->startElement('archivo');
            $xmlObject->writeAttribute('xsi:schemaLocation', 'http://www.uif.shcp.gob.mx/recepcion/spr spr.xsd');
            $xmlObject->writeAttribute('xmlns', 'http://www.uif.shcp.gob.mx/recepcion/spr');
            $xmlObject->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

            $xmlObject->startElement('informe');
                $xmlObject->startElement('mes_reportado');
                    $xmlObject->text($this->headers['month']);
                $xmlObject->endElement(); // mes_reportado

        $xmlObject->startElement('sujeto_obligado');

        if (strlen($this->headers['collegiate_entity_tax_id']) > 0) {
            $xmlObject->startElement('clave_entidad_colegiada');
                $xmlObject->text($this->headers['collegiate_entity_tax_id']);
            $xmlObject->endElement(); // clave_entidad_colegiada
        }

        $xmlObject->startElement('clave_sujeto_obligado');
            $xmlObject->text($this->headers['tax_id']);
        $xmlObject->endElement(); // clave_sujeto_obligado

        $xmlObject->startElement('ocupacion');
            $xmlObject->startElement('tipo_ocupacion');
                $xmlObject->text($this->headers['occupation_type']);
            $xmlObject->endElement(); // clave_sujeto_obligado

            if($this->headers['occupation_type'] == '99'){
                $xmlObject->startElement('descripcion_otra_ocupacion');
                    $xmlObject->text(Str::upper($this->headers['occupation_description']));
                $xmlObject->endElement(); // descripcion_otra_ocupacion
            }
        $xmlObject->endElement(); // ocupacion

        $xmlObject->startElement('clave_actividad');
            $xmlObject->text('SPR');
        $xmlObject->endElement(); // clave_actividad

        if ($this->headers['exempt'] == 'yes') {
            $xmlObject->startElement('exento');
                $xmlObject->text('1');
            $xmlObject->endElement(); // exempt
        }

        $xmlObject->endElement(); // sujeto_obligado

        foreach ($this->data['items'] as $key => $notice) {
            $xmlObject->startElement('aviso');
                $xmlObject->startElement('referencia_aviso');
                    $xmlObject->text($this->headers['notice_reference']);
                $xmlObject->endElement(); // referencia_aviso

                if (strlen($notice['modification']['folio']) > 0) {
                    $xmlObject->startElement('modificatorio');
                        $xmlObject->startElement('folio_modificacion');
                            $xmlObject->text($notice['modification']['folio']);
                        $xmlObject->endElement(); // folio_modificacion

                        $xmlObject->startElement('descripcion_modificacion');
                            $xmlObject->text($notice['modification']['description']);
                        $xmlObject->endElement(); // descripcion_modificacion

                    $xmlObject->endElement(); // modificatorio
                }

                $xmlObject->startElement('prioridad');
                $xmlObject->text($notice['modification']['priority']);
                $xmlObject->endElement(); // prioridad

                $xmlObject->startElement('alerta');
                    $xmlObject->startElement('tipo_alerta');
                        $xmlObject->text('100');
                    $xmlObject->endElement(); // tipo_alerta
                $xmlObject->endElement(); // alerta


            $xmlObject->startElement('persona_aviso');

            $xmlObject->startElement('tipo_persona');

            // Validamos si es persona Fisica, Moral o Fideicomiso
            // Persona Fisica
            if (strlen($notice['identificationDataPersonSubjectNotice']['physicalPerson']['name']) > 0) {
                $xmlObject->startElement('persona_fisica');
                $xmlObject->startElement('nombre');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['name']);
                $xmlObject->endElement(); // nombre

                $xmlObject->startElement('apellido_paterno');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['last_name']);
                $xmlObject->endElement(); // apellido_paterno

                $xmlObject->startElement('apellido_materno');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['second_last_name']);
                $xmlObject->endElement(); // apellido_materno

                $xmlObject->startElement('fecha_nacimiento');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['birthdate']);
                $xmlObject->endElement(); // fecha_nacimiento

                if (strlen($notice['identificationDataPersonSubjectNotice']['physicalPerson']['tax_id']) > 0) {
                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['tax_id']);
                    $xmlObject->endElement(); // rfc
                }

                if (strlen($notice['identificationDataPersonSubjectNotice']['physicalPerson']['population_id']) > 0) {
                    $xmlObject->startElement('curp');
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['population_id']);
                    $xmlObject->endElement(); // curp
                }

                $xmlObject->startElement('pais_nacionalidad');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['nationality']);
                $xmlObject->endElement(); // pais_nacionalidad

                $xmlObject->startElement('actividad_economica');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['economic_activity']);
                $xmlObject->endElement(); // actividad_economica

                if(strlen($notice['identificationDataPersonSubjectNotice']['representativeData']['name']) > 0) {
                    $xmlObject->startElement('representante_apoderado');
                    $xmlObject->startElement('nombre');
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['name']);
                    $xmlObject->endElement(); // nombre

                    $xmlObject->startElement('apellido_paterno');
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['last_name']);
                    $xmlObject->endElement(); // apellido_paterno

                    $xmlObject->startElement('apellido_materno');
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['second_last_name']);
                    $xmlObject->endElement(); // apellido_materno

                    $xmlObject->startElement('fecha_nacimiento');
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['birthdate']);
                    $xmlObject->endElement(); // fecha_nacimiento

                    if (strlen($notice['identificationDataPersonSubjectNotice']['representativeData']['tax_id']) > 0) {
                        $xmlObject->startElement('rfc');
                        $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['tax_id']);
                        $xmlObject->endElement(); // rfc
                    }

                    if (strlen($notice['identificationDataPersonSubjectNotice']['representativeData']['population_id']) > 0) {
                        $xmlObject->startElement('curp');
                        $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['population_id']);
                        $xmlObject->endElement(); // curp
                    }

                    $xmlObject->endElement(); // representante_apoderado
                }

                $xmlObject->endElement(); // persona_fisica

            }
            // Persona Moral
            elseif (strlen($notice['identificationDataPersonSubjectNotice']['legalPerson']['company_name']) > 0) {
                $xmlObject->startElement('persona_moral');
                $xmlObject->startElement('denominacion_razon');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['legalPerson']['company_name']);
                $xmlObject->endElement(); // denominacion_razon

                $xmlObject->startElement('fecha_constitucion');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['legalPerson']['constitution_date']);
                $xmlObject->endElement(); // fecha_constitucion

                $xmlObject->startElement('rfc');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['legalPerson']['tax_id']);
                $xmlObject->endElement(); // rfc

                $xmlObject->startElement('pais_nacionalidad');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['legalPerson']['nationality']);
                $xmlObject->endElement(); // pais_nacionalidad

                $xmlObject->startElement('giro_mercantil');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['legalPerson']['commercial_business']);
                $xmlObject->endElement(); // giro_mercantil

                $xmlObject->startElement('representante_apoderado');
                    $xmlObject->startElement('nombre');
                        $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['name']);
                    $xmlObject->endElement(); // nombre

                    $xmlObject->startElement('apellido_paterno');
                        $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['last_name']);
                    $xmlObject->endElement(); // apellido_paterno

                    $xmlObject->startElement('apellido_materno');
                        $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['second_last_name']);
                    $xmlObject->endElement(); // apellido_materno

                    $xmlObject->startElement('fecha_nacimiento');
                        $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['birthdate']);
                    $xmlObject->endElement(); // fecha_nacimiento

                    if (strlen($notice['identificationDataPersonSubjectNotice']['representativeData']['tax_id']) > 0) {
                        $xmlObject->startElement('rfc');
                            $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['tax_id']);
                        $xmlObject->endElement(); // rfc
                    }

                    if (strlen($notice['identificationDataPersonSubjectNotice']['representativeData']['population_id']) > 0) {
                        $xmlObject->startElement('curp');
                            $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['population_id']);
                        $xmlObject->endElement(); // curp
                    }

                    $xmlObject->endElement(); // representante_apoderado

                $xmlObject->endElement(); // persona_moral
            }
            // Fideicomiso
            else {
                $xmlObject->startElement('fideicomiso');
                    $xmlObject->startElement('denominacion_razon');
                        $xmlObject->text($notice['identificationDataPersonSubjectNotice']['trust']['denomination']);
                    $xmlObject->endElement(); // denominacion_razon

                    $xmlObject->startElement('rfc');
                        $xmlObject->text($notice['identificationDataPersonSubjectNotice']['trust']['tax_id']);
                    $xmlObject->endElement(); // rfc

                    $xmlObject->startElement('identificador_fideicomiso');
                        $xmlObject->text($notice['identificationDataPersonSubjectNotice']['trust']['identification']);
                    $xmlObject->endElement(); // identificador_trust

                    $xmlObject->startElement('representante_apoderado');
                        $xmlObject->startElement('nombre');
                            $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['name']);
                        $xmlObject->endElement(); // nombre

                        $xmlObject->startElement('apellido_paterno');
                            $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['last_name']);
                        $xmlObject->endElement(); // apellido_paterno

                        $xmlObject->startElement('apellido_materno');
                            $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['second_last_name']);
                        $xmlObject->endElement(); // apellido_materno

                        $xmlObject->startElement('fecha_nacimiento');
                            $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['birthdate']);
                        $xmlObject->endElement(); // fecha_nacimiento

                        if (strlen($notice['identificationDataPersonSubjectNotice']['representativeData']['tax_id']) > 0) {
                            $xmlObject->startElement('rfc');
                                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['tax_id']);
                            $xmlObject->endElement(); // rfc
                        }

                        if (strlen($notice['identificationDataPersonSubjectNotice']['representativeData']['population_id']) > 0) {
                            $xmlObject->startElement('curp');
                                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['representativeData']['population_id']);
                            $xmlObject->endElement(); // curp
                        }

                    $xmlObject->endElement(); // representante_apoderado

                $xmlObject->endElement(); // fideicomiso

            }

            $xmlObject->endElement(); // tipo_persona

            // Tipo domicilio
            $xmlObject->startElement('tipo_domicilio');

            // Validamos si es domicilio nacional o extranjero
            if (strlen($notice['identificationDataPersonSubjectNotice']['nationalAddress']['postal_code']) > 0) {
                $xmlObject->startElement('nacional');

                $xmlObject->startElement('colonia');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['nationalAddress']['settlement']);
                $xmlObject->endElement(); // colonia

                $xmlObject->startElement('calle');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['nationalAddress']['street']);
                $xmlObject->endElement(); // calle

                $xmlObject->startElement('numero_exterior');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['nationalAddress']['external_number']);
                $xmlObject->endElement(); // numero_exterior

                if (strlen($notice['identificationDataPersonSubjectNotice']['nationalAddress']['internal_number']) > 0) {
                    $xmlObject->startElement('numero_interior');
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['nationalAddress']['internal_number']);
                    $xmlObject->endElement(); // numero_interior
                }

                $xmlObject->startElement('codigo_postal');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['nationalAddress']['postal_code']);
                $xmlObject->endElement(); // codigo_postal

                $xmlObject->endElement(); // nacional
            } else {
                $xmlObject->startElement('extranjero');
                $xmlObject->startElement('pais');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['foreignAddress']['country']);
                $xmlObject->endElement(); // pais

                $xmlObject->startElement('estado_provincia');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['foreignAddress']['state']);
                $xmlObject->endElement(); // estado_provincia

                $xmlObject->startElement('ciudad_poblacion');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['foreignAddress']['municipality']);
                $xmlObject->endElement(); // ciudad_poblacion

                $xmlObject->startElement('colonia');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['foreignAddress']['settlement']);
                $xmlObject->endElement(); // colonia

                $xmlObject->startElement('calle');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['foreignAddress']['street']);
                $xmlObject->endElement(); // calle

                $xmlObject->startElement('numero_exterior');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['foreignAddress']['external_number']);
                $xmlObject->endElement(); // numero_exterior

                if (strlen($notice['identificationDataPersonSubjectNotice']['foreignAddress']['internal_number']) > 0) {
                    $xmlObject->startElement('numero_interior');
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['foreignAddress']['internal_number']);
                    $xmlObject->endElement(); // numero_interior
                }

                $xmlObject->startElement('codigo_postal');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['foreignAddress']['postal_code']);
                $xmlObject->endElement(); // codigo_postal

                $xmlObject->endElement(); // extranjero
            }
            $xmlObject->endElement(); // tipo_domicilio

            // Datos del numero telefonico
            if (strlen($notice['identificationDataPersonSubjectNotice']['physicalPerson']['name']) > 0
                || strlen($notice['identificationDataPersonSubjectNotice']['legalPerson']['company_name']) > 0) {
                $xmlObject->startElement('telefono');
                $xmlObject->startElement('clave_pais');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['contact']['country']);
                $xmlObject->endElement(); // clave_pais

                $xmlObject->startElement('numero_telefono');
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['contact']['phone']);
                $xmlObject->endElement(); // numero_telefono

                if (strlen($notice['identificationDataPersonSubjectNotice']['contact']['email']) > 0) {
                    $xmlObject->startElement('correo_electronico');
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['contact']['email']);
                    $xmlObject->endElement(); // correo_electronico
                }
                $xmlObject->endElement(); // telefono
            }

            $xmlObject->endElement(); // persona_aviso

            // Datos del dueño o beneficiario
            // Validamos si hay datos del dueño o beneficiario
            if (strlen($notice['identificationDataPersonBeneficiary']['physicalPerson']['name']) > 0 ||
                strlen($notice['identificationDataPersonBeneficiary']['legalPerson']['company_name']) > 0 ||
                strlen($notice['identificationDataPersonBeneficiary']['trust']['denomination']) > 0) {

                $xmlObject->startElement('dueno_beneficiario');

                $xmlObject->startElement('tipo_persona');

                // Persona Fisica
                if (strlen($notice['identificationDataPersonBeneficiary']['physicalPerson']['name']) > 0) {
                    $xmlObject->startElement('persona_fisica');
                    $xmlObject->startElement('nombre');
                    $xmlObject->text($notice['identificationDataPersonBeneficiary']['physicalPerson']['name']);
                    $xmlObject->endElement(); // nombre

                    $xmlObject->startElement('apellido_paterno');
                    $xmlObject->text($notice['identificationDataPersonBeneficiary']['physicalPerson']['last_name']);
                    $xmlObject->endElement(); // apellido_paterno

                    $xmlObject->startElement('apellido_materno');
                    $xmlObject->text($notice['identificationDataPersonBeneficiary']['physicalPerson']['second_last_name']);
                    $xmlObject->endElement(); // apellido_materno

                    $xmlObject->startElement('fecha_nacimiento');
                    $xmlObject->text($notice['identificationDataPersonBeneficiary']['physicalPerson']['birthdate']);
                    $xmlObject->endElement(); // fecha_nacimiento

                    if (strlen($notice['identificationDataPersonBeneficiary']['physicalPerson']['tax_id']) > 0) {
                        $xmlObject->startElement('rfc');
                        $xmlObject->text($notice['identificationDataPersonBeneficiary']['physicalPerson']['tax_id']);
                        $xmlObject->endElement(); // rfc
                    }

                    if (strlen($notice['identificationDataPersonBeneficiary']['physicalPerson']['population_id']) > 0) {
                        $xmlObject->startElement('curp');
                        $xmlObject->text($notice['identificationDataPersonBeneficiary']['physicalPerson']['population_id']);
                        $xmlObject->endElement(); // curp
                    }

                    if (strlen($notice['identificationDataPersonBeneficiary']['physicalPerson']['nationality'] > 0)) {
                        $xmlObject->startElement('pais_nacionalidad');
                        $xmlObject->text($notice['identificationDataPersonBeneficiary']['physicalPerson']['nationality']);
                        $xmlObject->endElement(); // pais_nacionalidad
                    }

                    $xmlObject->endElement(); // persona_fisica
                }
                // Persona Moral
                elseif (strlen($notice['identificationDataPersonBeneficiary']['legalPerson']['company_name']) > 0) {
                    $xmlObject->startElement('persona_moral');
                    $xmlObject->startElement('denominacion_razon');
                    $xmlObject->text($notice['identificationDataPersonBeneficiary']['legalPerson']['company_name']);
                    $xmlObject->endElement(); // denominacion_razon

                    $xmlObject->startElement('fecha_constitucion');
                    $xmlObject->text($notice['identificationDataPersonBeneficiary']['legalPerson']['constitution_date']);
                    $xmlObject->endElement(); // fecha_constitucion

                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['identificationDataPersonBeneficiary']['legalPerson']['tax_id']);
                    $xmlObject->endElement(); // rfc

                    $xmlObject->startElement('pais_nacionalidad');
                    $xmlObject->text($notice['identificationDataPersonBeneficiary']['legalPerson']['nationality']);
                    $xmlObject->endElement(); // pais_nacionalidad

                    $xmlObject->endElement(); // persona_moral
                }
                // Fideicomiso
                else {
                    $xmlObject->startElement('fideicomiso');
                        $xmlObject->startElement('denominacion_razon');
                            $xmlObject->text($notice['identificationDataPersonBeneficiary']['trust']['denomination']);
                        $xmlObject->endElement(); // denominacion_razon

                        $xmlObject->startElement('rfc');
                            $xmlObject->text($notice['identificationDataPersonBeneficiary']['trust']['tax_id']);
                        $xmlObject->endElement(); // rfc

                        $xmlObject->startElement('identificador_fideicomiso');
                            $xmlObject->text($notice['identificationDataPersonBeneficiary']['trust']['identification']);
                        $xmlObject->endElement(); // identificador_fideicomiso

                    $xmlObject->endElement(); // fideicomiso
                }

                $xmlObject->endElement(); // tipo_persona

                $xmlObject->endElement(); // dueno_beneficiario
            }

            $xmlObject->startElement('detalle_operaciones');

                $xmlObject->startElement('datos_operacion');

                    $xmlObject->startElement('fecha_operacion');
                        $xmlObject->text($notice['operationDetails']['dateOperation']);
                    $xmlObject->endElement(); // fecha_operacion

                    $xmlObject->startElement('tipo_actividad');
                        $xmlObject->startElement('administracion_recursos');

                        foreach ($notice['operationDetails']['asset'] as $asset)
                        {
                            //foreach ($assets as $asset) {

                                $xmlObject->startElement('tipo_activo');

                                    $xmlObject->startElement('activo_inmobiliario');

                                    $xmlObject->startElement('tipo_inmueble');
                                    $xmlObject->text($asset['asset_type']);
                                    $xmlObject->endElement(); // tipo_inmueble

                                    $xmlObject->startElement('valor_referencia');
                                    $xmlObject->text(number_format($asset['reference_value_mx'], 2, '.', ''));
                                    $xmlObject->endElement(); // valor_referencia

                                    $xmlObject->startElement('colonia');
                                    $xmlObject->text($asset['settlement']);
                                    $xmlObject->endElement(); // colonia

                                    $xmlObject->startElement('calle');
                                    $xmlObject->text($asset['street']);
                                    $xmlObject->endElement(); // calle

                                    $xmlObject->startElement('numero_exterior');
                                    $xmlObject->text($asset['external_number']);
                                    $xmlObject->endElement(); // numero_exterior

                                    if (strlen($asset['internal_number']) > 0) {
                                        $xmlObject->startElement('numero_interior');
                                        $xmlObject->text($asset['internal_number']);
                                        $xmlObject->endElement(); // numero_interior
                                    }

                                    $xmlObject->startElement('codigo_postal');
                                    $xmlObject->text($asset['postal_code']);
                                    $xmlObject->endElement(); // codigo_postal

                                    $xmlObject->startElement('folio_real');
                                    $xmlObject->text($asset['real_folio']);
                                    $xmlObject->endElement(); // folio_real

                                $xmlObject->endElement(); // activo_inmobiliario

                                $xmlObject->endElement(); // tipo_activo
                            //}
                        }

                        $xmlObject->startElement('numero_operaciones');
                            $xmlObject->text($notice['operationDetails']['operationCount']);
                        $xmlObject->endElement(); // numero_operaciones

                        $xmlObject->endElement(); // adminitracion_recursos
                    $xmlObject->endElement(); // tipo_actividad

                    foreach ($notice['operationDetails']['financialOperation'] as $operation) {
                        //foreach ($operations as $operation) {
                            $xmlObject->startElement('datos_operacion_financiera');

                            $xmlObject->startElement('instrumento_monetario');
                            $xmlObject->text($operation['instrument']);
                            $xmlObject->endElement(); // instrumento_monetario

                            $xmlObject->startElement('moneda');
                            $xmlObject->text($operation['currency']);
                            $xmlObject->endElement(); // moneda

                            $xmlObject->startElement('monto_operacion');
                            $xmlObject->text(number_format($operation['amount'], 2 , '.', ''));
                            $xmlObject->endElement(); // monto_operacion

                            $xmlObject->endElement(); // datos_operacion_financiera
                        //}
                    }

                $xmlObject->endElement(); // datos_operacion

            $xmlObject->endElement(); // detalle_operacion

            $xmlObject->endElement(); // aviso
        }

        $xmlObject->endElement(); // Informe
        $xmlObject->endElement(); // archivo

        $xmlObject->endDocument();

        return $xmlObject->outputMemory();
    }
}
