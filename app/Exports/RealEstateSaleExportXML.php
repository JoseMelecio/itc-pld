<?php

namespace App\Exports;

class RealEstateSaleExportXML
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

        $xmlObject->writeAttribute('xmlns', 'http://www.uif.shcp.gob.mx/recepcion/inm');
        $xmlObject->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlObject->writeAttribute('xsi:schemaLocation', 'http://www.uif.shcp.gob.mx/recepcion/inm inm.xsd');

        $xmlObject->startElement('informe');
        $xmlObject->startElement('mes_reportado');
        $xmlObject->text($this->headers['month']);
        $xmlObject->endElement(); // Elemento mes_reportado

        $xmlObject->startElement('sujeto_obligado');

        if (strlen($this->headers['collegiate_entity_tax_id']) > 0) {
            $xmlObject->startElement('clave_entidad_colegiada');
            $xmlObject->text($this->headers['collegiate_entity_tax_id']);
            $xmlObject->endElement(); // Elemento clave_entidad_colegiada
        }

        $xmlObject->startElement('clave_sujeto_obligado');
        $xmlObject->text($this->headers['tax_id']);
        $xmlObject->endElement(); // Elemento clave_sujeto_obligado

        $xmlObject->startElement('clave_actividad');
        $xmlObject->text('INM');
        $xmlObject->endElement(); // Elemento clave_actividad

        if ($this->headers['exempt'] == 'yes') {
            $xmlObject->startElement('excento');
            $xmlObject->text('1');
            $xmlObject->endElement(); // Elemento excento
        }
        $xmlObject->endElement(); // Elemento sujeto_obligado

        foreach ($this->data['items'] as $notice) {
            $xmlObject->startElement('aviso');

            $xmlObject->startElement('referencia_aviso');
            $xmlObject->text($this->headers['notice_reference']);
            $xmlObject->endElement(); // Elemento referencia_aviso

            if (strlen($notice['modificatorio']['folio']) > 0) {
                $xmlObject->startElement('modificatorio');
                $xmlObject->startElement('folio_modificacion');
                $xmlObject->text($notice['modificatorio']['folio']);
                $xmlObject->endElement(); // Elemento folio_modificacion

                $xmlObject->startElement('descripcion_modificacion');
                $xmlObject->text($notice['modificatorio']['descripcion']);
                $xmlObject->endElement(); // Elemento descripcion_modificacion

                $xmlObject->startElement('prioridad');
                $xmlObject->text($notice['modificatorio']['prioridad']);
                $xmlObject->endElement(); // Elemento prioridad
                $xmlObject->endElement(); // Elemento modificatorio
            }

            $xmlObject->startElement('prioridad');
            $xmlObject->text('1');
            $xmlObject->endElement(); // Elemento prioridad

            $xmlObject->startElement('alerta');
            $xmlObject->startElement('tipo_alerta');
            $xmlObject->text($notice['alerta']['tipo_alerta']);
            $xmlObject->endElement(); // Elemento tipo_alerta

            if ($notice['alerta']['tipo_alerta'] == '9999') {
                $xmlObject->startElement('descripcion_alerta');
                $xmlObject->text($notice['alerta']['descripcion']);
                $xmlObject->endElement();
            }
            $xmlObject->endElement(); // Elemento alerta

            $xmlObject->startElement('persona_aviso');
            $xmlObject->startElement('tipo_persona');

            // Validamos si es persona Fisica, Moral o Fideicomiso
            // Persona Fisica
            if (strlen($notice['identificacionPersonaObjetoAviso']['personaFisica']['nombre']) > 0) {
                $xmlObject->startElement('persona_fisica');
                $xmlObject->startElement('nombre');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['nombre']);
                $xmlObject->endElement(); // Elemento nombre

                $xmlObject->startElement('apellido_paterno');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['apellidoPaterno']);
                $xmlObject->endElement(); // Elemento apellido_paterno

                $xmlObject->startElement('apellido_materno');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['apellidoMaterno']);
                $xmlObject->endElement(); // Elemento apellido_materno

                $xmlObject->startElement('fecha_nacimiento');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['fechaNacimiento']);
                $xmlObject->endElement(); // Elemento fecha_nacimiento

                if (strlen($notice['identificacionPersonaObjetoAviso']['personaFisica']['rfc']) > 0) {
                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['rfc']);
                    $xmlObject->endElement(); // Elemento rfc
                }

                if (strlen($notice['identificacionPersonaObjetoAviso']['personaFisica']['curp']) > 0) {
                    $xmlObject->startElement('curp');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['curp']);
                    $xmlObject->endElement(); // Elemento curp
                }

                $xmlObject->startElement('pais_nacionalidad');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['nacionalidad']);
                $xmlObject->endElement(); // Elemento pais_nacionalidad

                $xmlObject->startElement('actividad_economica');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['actividadEconomica']);
                $xmlObject->endElement(); // Elemento actividad_economica

                $xmlObject->endElement(); // Elemento persona_fisica
            }
            // Persona Moral
            elseif (strlen($notice['identificacionPersonaObjetoAviso']['personaMoral']['razonSocial']) > 0) {
                $xmlObject->startElement('persona_moral');
                $xmlObject->startElement('denominacion_razon');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['razonSocial']);
                $xmlObject->endElement(); // Elemento denominacion_razon

                $xmlObject->startElement('fecha_constitucion');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['fechaConstitucion']);
                $xmlObject->endElement(); // Elemento fecha_constitucion

                $xmlObject->startElement('rfc');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['rfc']);
                $xmlObject->endElement(); // Elemento rfc

                $xmlObject->startElement('pais_nacionalidad');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['nacionalidad']);
                $xmlObject->endElement(); // Elemento pais_nacionalidad

                $xmlObject->startElement('giro_mercantil');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['giroMercantil']);
                $xmlObject->endElement(); // Elemento giro_mercantil

                $xmlObject->startElement('representante_apoderado');
                $xmlObject->startElement('nombre');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesMoral']['nombre']);
                $xmlObject->endElement(); // Elemento nombre

                $xmlObject->startElement('apellido_paterno');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesMoral']['apellidoPaterno']);
                $xmlObject->endElement(); // Elemento apellido_paterno

                $xmlObject->startElement('apellido_materno');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesMoral']['apellidoMaterno']);
                $xmlObject->endElement(); // Elemento apellido_materno

                $xmlObject->startElement('fecha_nacimiento');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesMoral']['fechaNacimiento']);
                $xmlObject->endElement(); // Elemento fecha_nacimiento

                if (strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantesMoral']['rfc']) > 0) {
                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesMoral']['rfc']);
                    $xmlObject->endElement(); // Elemento rfc
                }

                if (strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantesMoral']['curp']) > 0) {
                    $xmlObject->startElement('curp');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesMoral']['curp']);
                    $xmlObject->endElement(); // Elemento curp
                }

                $xmlObject->endElement(); // Elemento representante_apoderado
                $xmlObject->endElement(); // Elemento persona_moral
            }
            // Fideicomiso
            else {
                $xmlObject->startElement('fideicomiso');
                $xmlObject->startElement('denominacion_razon');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['fideicomiso']['denominacion']);
                $xmlObject->endElement(); // Elemento denominacion_razon

                $xmlObject->startElement('rfc');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['fideicomiso']['rfc']);
                $xmlObject->endElement(); // Elemento rfc

                $xmlObject->startElement('identificador_fideicomiso');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['fideicomiso']['identificador']);
                $xmlObject->endElement(); // Elemento identificador_fideicomiso

                $xmlObject->startElement('apoderado_delegado');
                $xmlObject->startElement('nombre');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesFideicomiso']['nombre']);
                $xmlObject->endElement(); // Elemento nombre

                $xmlObject->startElement('apellido_paterno');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesFideicomiso']['apellidoPaterno']);
                $xmlObject->endElement(); // Elemento apellido_paterno

                $xmlObject->startElement('apellido_materno');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesFideicomiso']['apellidoMaterno']);
                $xmlObject->endElement(); // Elemento apellido_materno

                $xmlObject->startElement('fecha_nacimiento');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesFideicomiso']['fechaNacimiento']);
                $xmlObject->endElement(); // Elemento fecha_nacimiento

                if (strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantesFideicomiso']['rfc']) > 0) {
                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesFideicomiso']['rfc']);
                    $xmlObject->endElement(); // Elemento rfc
                }

                if (strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantesFideicomiso']['curp']) > 0) {
                    $xmlObject->startElement('curp');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantesFideicomiso']['curp']);
                    $xmlObject->endElement(); // Elemento curp
                }

                $xmlObject->endElement(); // Elemento representante_apoderado

                $xmlObject->endElement(); // Elemento fideicomiso
            }

            $xmlObject->endElement(); // Elemento tipo_persona

            // Tipo domicilio
            $xmlObject->startElement('tipo_domicilio');

            // Validamos si es domicilio nacional o extranjero
            if (strlen($notice['identificacionPersonaObjetoAviso']['domicilioNacionalPersonaAviso']['colonia']) > 0) {
                $xmlObject->startElement('nacional');

                $xmlObject->startElement('colonia');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioNacionalPersonaAviso']['colonia']);
                $xmlObject->endElement(); // Elemento colonia

                $xmlObject->startElement('calle');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioNacionalPersonaAviso']['calle']);
                $xmlObject->endElement(); // Elemento calle

                $xmlObject->startElement('numero_exterior');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioNacionalPersonaAviso']['numeroExterior']);
                $xmlObject->endElement(); // Elemento numero_exterior

                if (strlen($notice['identificacionPersonaObjetoAviso']['domicilioNacionalPersonaAviso']['numeroInterior']) > 0) {
                    $xmlObject->startElement('numero_interior');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioNacionalPersonaAviso']['numeroInterior']);
                    $xmlObject->endElement(); // Elemento numero_interior
                }

                $xmlObject->startElement('codigo_postal');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioNacionalPersonaAviso']['cp']);
                $xmlObject->endElement(); // Elemento codigo_postal

                $xmlObject->endElement(); // Elemento nacional
            } else {
                $xmlObject->startElement('extranjero');
                $xmlObject->startElement('pais');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso']['pais']);
                $xmlObject->endElement(); // Elemento pais

                $xmlObject->startElement('estado_provincia');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso']['estado']);
                $xmlObject->endElement(); // Elemento estado_provincia

                $xmlObject->startElement('ciudad_poblacion');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso']['ciudad']);
                $xmlObject->endElement(); // Elemento ciudad_poblacion

                $xmlObject->startElement('colonia');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso']['colonia']);
                $xmlObject->endElement(); // Elemento colonia

                $xmlObject->startElement('calle');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso']['calle']);
                $xmlObject->endElement(); // Elemento calle

                $xmlObject->startElement('numero_exterior');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso']['numeroExterior']);
                $xmlObject->endElement(); // Elemento numero_exterior

                if (strlen($notice['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso']['numeroInterior']) > 0) {
                    $xmlObject->startElement('numero_interior');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso']['numeroInterior']);
                    $xmlObject->endElement(); // Elemento numero_interior
                }

                $xmlObject->startElement('codigo_postal');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso']['cp']);
                $xmlObject->endElement(); // Elemento codigo_postal

                $xmlObject->endElement(); // Elemento extranjero
            }
            $xmlObject->endElement(); // Elemento tipo_domicilio

            // Datos del numero telefonico
            $xmlObject->startElement('telefono');
            $xmlObject->startElement('clave_pais');
            $xmlObject->text(trim($notice['identificacionPersonaObjetoAviso']['datosContactoAviso']['pais']));
            $xmlObject->endElement(); // Elemento clave_pais

            $xmlObject->startElement('numero_telefono');
            $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosContactoAviso']['telefono']);
            $xmlObject->endElement(); // Elemento numero_telefono

            if (strlen($notice['identificacionPersonaObjetoAviso']['datosContactoAviso']['correo']) > 0) {
                $xmlObject->startElement('correo_electronico');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosContactoAviso']['correo']);
                $xmlObject->endElement(); // Elemento correo_electronico
            }

            $xmlObject->endElement(); // Elemento telefono

            $xmlObject->endElement(); // Elemento persona_aviso


            //Benefiario persona fisica
            if (isset($notice['beneficiarioControlador']['personaFisica'])) {
                foreach ($notice['beneficiarioControlador']['personaFisica'] as $fisica) {
                    //Dueño Beneficiario
                    $xmlObject->startElement('dueno_beneficiario');

                    $xmlObject->startElement('tipo_persona');

                    // Persona Fisica
                    if (strlen($fisica['nombre']) > 0) {

                        $xmlObject->startElement('persona_fisica');
                        $xmlObject->startElement('nombre');
                        $xmlObject->text($fisica['nombre']);
                        $xmlObject->endElement(); // Elemento nombre

                        $xmlObject->startElement('apellido_paterno');
                        $xmlObject->text($fisica['apellidoPaterno']);
                        $xmlObject->endElement(); // Elemento apellido_paterno

                        $xmlObject->startElement('apellido_materno');
                        $xmlObject->text($fisica['apellidoMaterno']);
                        $xmlObject->endElement(); // Elemento apellido_materno

                        $xmlObject->startElement('fecha_nacimiento');
                        $xmlObject->text($fisica['fechaNacimiento']);
                        $xmlObject->endElement(); // Elemento fecha_nacimiento

                        if (strlen($fisica['rfc']) > 0) {
                            $xmlObject->startElement('rfc');
                            $xmlObject->text($fisica['rfc']);
                            $xmlObject->endElement(); // Elemento rfc
                        }

                        if (strlen($fisica['curp']) > 0) {
                            $xmlObject->startElement('curp');
                            $xmlObject->text($fisica['curp']);
                            $xmlObject->endElement(); // Elemento curp
                        }

                        $xmlObject->startElement('pais_nacionalidad');
                        $xmlObject->text($fisica['pais']);
                        $xmlObject->endElement(); // Elemento pais_nacionalidad

                        $xmlObject->endElement(); // Elemento persona_fisica
                    }
                    $xmlObject->endElement(); // persona fisica
                    $xmlObject->endElement(); // beneficiario
                }
            }

            //Benefiario persona moral
            if (isset($notice['beneficiarioControlador']['personaMoral'])) {
                foreach ($notice['beneficiarioControlador']['personaMoral'] as $moral) {
                    //Dueño Beneficiario
                    $xmlObject->startElement('dueno_beneficiario');

                    $xmlObject->startElement('tipo_persona');

                    // Persona moral
                    if (strlen($fisica['nombre']) > 0) {

                        $xmlObject->startElement('persona_moral');
                        $xmlObject->startElement('denominacion_razon');
                        $xmlObject->text($moral['razonSocial']);
                        $xmlObject->endElement(); // Elemento denominacion_razon

                        $xmlObject->startElement('fecha_constitucion');
                        $xmlObject->text($moral['fechaConstitucion']);
                        $xmlObject->endElement(); // Elemento fecha_constitucion

                        $xmlObject->startElement('rfc');
                        $xmlObject->text($moral['rfc']);
                        $xmlObject->endElement(); // Elemento rfc

                        $xmlObject->startElement('pais_nacionalidad');
                        $xmlObject->text($moral['nacionalidad']);
                        $xmlObject->endElement(); // Elemento pais_nacionalidad

                        $xmlObject->endElement(); // Elemento persona_moral
                    }
                    $xmlObject->endElement(); // persona fisica
                    $xmlObject->endElement(); // beneficiario
                }
            }

            if (isset($notice['beneficiarioControlador']['fideicomiso'])) {
                foreach ($notice['beneficiarioControlador']['fideicomiso'] as $fideicomiso) {
                    //Dueño Beneficiario
                    $xmlObject->startElement('dueno_beneficiario');

                    $xmlObject->startElement('tipo_persona');

                    // Persona moral
                    if (strlen($fideicomiso['denominacion_razon']) > 0) {

                        $xmlObject->startElement('fideicomiso');
                        $xmlObject->startElement('denominacion_razon');
                        $xmlObject->text($fideicomiso['denominacion']);
                        $xmlObject->endElement(); // Elemento denominacion_razon

                        $xmlObject->startElement('rfc');
                        $xmlObject->text($fideicomiso['rfc']);
                        $xmlObject->endElement(); // Elemento rfc

                        $xmlObject->startElement('identificador_fideicomiso');
                        $xmlObject->text($fideicomiso['identificador']);
                        $xmlObject->endElement(); // Elemento identificador_fideicomiso

                        $xmlObject->endElement(); // Elemento fideicomiso
                    }
                    $xmlObject->endElement(); // persona fisica
                    $xmlObject->endElement(); // beneficiario
                }
            }

            //Datos de la operacion
            $xmlObject->startElement('detalle_operaciones');
            $xmlObject->startElement('datos_operacion');

            $xmlObject->startElement('fecha_operacion');
            $xmlObject->text($notice['detallesOperacion']['datosOperacion']['fechaOperacion']);
            $xmlObject->endElement(); // Elemento fecha operacion

            $xmlObject->startElement('tipo_operacion');
            $xmlObject->text('501');
            $xmlObject->endElement(); // Elemento tipo_operacion

            $xmlObject->startElement('figura_cliente');
            $xmlObject->text($notice['detallesOperacion']['datosOperacion']['figuraClienteReportado']);
            $xmlObject->endElement(); // figura_cliente

            $xmlObject->startElement('figura_so');
            $xmlObject->text($notice['detallesOperacion']['datosOperacion']['figurapersonaRealizaActividad']);
            $xmlObject->endElement(); // figura_so

            if (strlen($notice['detallesOperacion']['contraparte']['personaFisica']['nombre']) > 0 ||
                strlen($notice['detallesOperacion']['contraparte']['personaMoral']['razonSocial']) > 0 ||
                strlen($notice['detallesOperacion']['contraparte']['fideicomiso']['denominacion']) > 0) {

                $xmlObject->startElement('datos_contraparte');

                $xmlObject->startElement('tipo_persona');
                // Persona Fisica
                if (strlen($notice['detallesOperacion']['contraparte']['personaFisica']['nombre']) > 0) {
                    $xmlObject->startElement('persona_fisica');
                    $xmlObject->startElement('nombre');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['personaFisica']['nombre']);
                    $xmlObject->endElement(); // Elemento nombre

                    $xmlObject->startElement('apellido_paterno');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['personaFisica']['apellidoPaterno']);
                    $xmlObject->endElement(); // Elemento apellido_paterno

                    $xmlObject->startElement('apellido_materno');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['personaFisica']['apellidoMaterno']);
                    $xmlObject->endElement(); // Elemento apellido_materno

                    $xmlObject->startElement('fecha_nacimiento');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['personaFisica']['fechaNacimiento']);
                    $xmlObject->endElement(); // Elemento fecha_nacimiento

                    if (strlen($notice['detallesOperacion']['contraparte']['personaFisica']['rfc']) > 0) {
                        $xmlObject->startElement('rfc');
                        $xmlObject->text($notice['detallesOperacion']['contraparte']['personaFisica']['rfc']);
                        $xmlObject->endElement(); // Elemento rfc
                    }

                    if (strlen($notice['detallesOperacion']['contraparte']['personaFisica']['curp']) > 0) {
                        $xmlObject->startElement('curp');
                        $xmlObject->text($notice['detallesOperacion']['contraparte']['personaFisica']['curp']);
                        $xmlObject->endElement(); // Elemento curp
                    }

                    $xmlObject->startElement('pais_nacionalidad');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['personaFisica']['pais']);
                    $xmlObject->endElement(); // Elemento pais_nacionalidad

                    $xmlObject->endElement(); // Elemento persona_fisica
                }
                // Persona Moral
                elseif (strlen($notice['detallesOperacion']['contraparte']['personaMoral']['razonSocial']) > 0) {
                    $xmlObject->startElement('persona_moral');
                    $xmlObject->startElement('denominacion_razon');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['personaMoral']['razonSocial']);
                    $xmlObject->endElement(); // Elemento denominacion_razon

                    $xmlObject->startElement('fecha_constitucion');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['personaMoral']['fechaConstitucion']);
                    $xmlObject->endElement(); // Elemento fecha_constitucion

                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['personaMoral']['rfc']);
                    $xmlObject->endElement(); // Elemento rfc

                    $xmlObject->startElement('pais_nacionalidad');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['personaMoral']['nacionalidad']);
                    $xmlObject->endElement(); // Elemento pais_nacionalidad

                    $xmlObject->endElement(); // Elemento persona_moral
                }
                // Fideicomiso
                else {
                    $xmlObject->startElement('fideicomiso');
                    $xmlObject->startElement('denominacion_razon');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['fideicomiso']['denominacion']);
                    $xmlObject->endElement(); // Elemento denominacion_razon

                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['fideicomiso']['rfc']);
                    $xmlObject->endElement(); // Elemento rfc

                    $xmlObject->startElement('identificador_fideicomiso');
                    $xmlObject->text($notice['detallesOperacion']['contraparte']['fideicomiso']['identificador']);
                    $xmlObject->endElement(); // Elemento identificador_fideicomiso

                    $xmlObject->endElement(); // Elemento fideicomiso
                }

                $xmlObject->endElement(); // Elemento tipo_persona
                $xmlObject->endElement(); // Elemento datos_contraparte
            }

            //Caracteristicas del inmueble
            $xmlObject->startElement('caracteristicas_inmueble');

            $xmlObject->startElement('tipo_inmueble');
            $xmlObject->text($notice['actoOperacion']['caracteristicasInmueble']['tipoInmueble']);
            $xmlObject->endElement(); // Elemento tipo_inmueble

            $xmlObject->startElement('valor_pactado');
            $xmlObject->text(number_format($notice['actoOperacion']['caracteristicasInmueble']['valorPactado'], 2, '.', ''));
            $xmlObject->endElement(); // Elemento valor_pactado

            $xmlObject->startElement('colonia');
            $xmlObject->text($notice['actoOperacion']['caracteristicasInmueble']['colonia']);
            $xmlObject->endElement(); // Elemento colonia

            $xmlObject->startElement('calle');
            $xmlObject->text($notice['actoOperacion']['caracteristicasInmueble']['calle']);
            $xmlObject->endElement(); // Elemento calle

            $xmlObject->startElement('numero_exterior');
            $xmlObject->text($notice['actoOperacion']['caracteristicasInmueble']['numeroExterior']);
            $xmlObject->endElement(); // Elemento numero_exterior

            if (strlen($notice['actoOperacion']['caracteristicasInmueble']['numeroInterior']) > 0) {
                $xmlObject->startElement('numero_interior');
                $xmlObject->text($notice['actoOperacion']['caracteristicasInmueble']['numeroInterior']);
                $xmlObject->endElement(); // Elemento numero_interior
            }

            $xmlObject->startElement('codigo_postal');
            $xmlObject->text($notice['actoOperacion']['caracteristicasInmueble']['cp']);
            $xmlObject->endElement(); // Elemento codigo_postal

            $xmlObject->startElement('dimension_terreno');
            $xmlObject->text(number_format($notice['actoOperacion']['caracteristicasInmueble']['dimensionTerreno'], 2, '.', ''));
            $xmlObject->endElement(); // Elemento dimension_terreno

            $xmlObject->startElement('dimension_construido');
            $xmlObject->text(number_format($notice['actoOperacion']['caracteristicasInmueble']['dimensionInmueble'], 2, '.', ''));
            $xmlObject->endElement(); // Elemento dimension_construido

            $xmlObject->startElement('folio_real');
            if (strlen($notice['actoOperacion']['caracteristicasInmueble']['antecedentesRegistrales']) > 0) {
                $xmlObject->text($notice['actoOperacion']['caracteristicasInmueble']['antecedentesRegistrales']);
            } else {
                $xmlObject->text('XXXX');
            }
            $xmlObject->endElement(); // Elemento folio_real

            $xmlObject->endElement(); // Elemento caracteristicas_inmueble

            $xmlObject->startElement('contrato_instrumento_publico');

            if (strlen($notice['actoOperacion']['instrumentoPublico']['numero']) > 0) {

                $xmlObject->startElement('datos_instrumento_publico');

                $xmlObject->startElement('numero_instrumento_publico');
                $xmlObject->text($notice['actoOperacion']['instrumentoPublico']['numero']);
                $xmlObject->endElement(); // Elemento numero_instrumento_publico

                $xmlObject->startElement('fecha_instrumento_publico');
                $xmlObject->text($notice['actoOperacion']['instrumentoPublico']['fecha']);
                $xmlObject->endElement(); // Elemento fecha_instrumento_publico

                $xmlObject->startElement('notario_instrumento_publico');
                $xmlObject->text($notice['actoOperacion']['instrumentoPublico']['numeroNotario']);
                $xmlObject->endElement(); // Elemento notario_instrumento_publico

                $xmlObject->startElement('entidad_instrumento_publico');
                $xmlObject->text($notice['actoOperacion']['instrumentoPublico']['entidadNotario']);
                $xmlObject->endElement(); // Elemento entidad_instrumento_publico

                $xmlObject->startElement('valor_avaluo_catastral');
                $xmlObject->text(number_format($notice['actoOperacion']['instrumentoPublico']['avaluo'], 2, '.', ''));
                $xmlObject->endElement(); // Elemento valor_avaluo_catastral

                $xmlObject->endElement(); // Elemento datos_instrumento_publico

            } else {
                $xmlObject->startElement('datos_contrato');

                $xmlObject->startElement('fecha_contrato');
                $xmlObject->text($notice['actoOperacion']['contratoPrivado']['fechaContrato']);
                $xmlObject->endElement(); // Elemento fecha_contrato

                $xmlObject->endElement(); // Elemento datos_contrato
            }

            $xmlObject->endElement(); // Elemento contrato_instrumento_publico

            /*
            if(strlen($notice['actoOperacion']['contratoPrivado']['fechaContrato']) > 0)
            {
                $xmlObject->startElement("datos_contrato");

                    $xmlObject->startElement("fecha_contrato");
                        $xmlObject->text($notice['actoOperacion']['contratoPrivado']['fechaContrato']);
                    $xmlObject->endElement(); # Elemento fecha_contrato

                $xmlObject->endElement(); # Elemento datos_contrato
            }
            */

            foreach ($notice['actoOperacion']['datosLiquidacion'] as $m_liquidacion) {
                $xmlObject->startElement('datos_liquidacion');

                $xmlObject->startElement('fecha_pago');
                $xmlObject->text($m_liquidacion['fechaPago']);
                $xmlObject->endElement(); // Elemento fecha_pago

                $xmlObject->startElement('forma_pago');
                $xmlObject->text($m_liquidacion['formaPago']);
                $xmlObject->endElement(); // Elemento forma_pago

                $xmlObject->startElement('instrumento_monetario');
                $xmlObject->text($m_liquidacion['instrumento']);
                $xmlObject->endElement(); // Elemento instrumento_monetario

                $xmlObject->startElement('moneda');
                $xmlObject->text($m_liquidacion['moneda']);
                $xmlObject->endElement(); // Elemento moneda

                $xmlObject->startElement('monto_operacion');
                $xmlObject->text(number_format($m_liquidacion['monto'], 2, '.', ''));
                $xmlObject->endElement(); // Elemento monto_operacion

                $xmlObject->endElement(); // Elemento datos_liquidacion
            }
            $xmlObject->endElement(); //datos_operacion
            $xmlObject->endElement(); //detalle_operaciones

            $xmlObject->endElement(); // Elemento archivo

        } // Fin foreach m_datos

        $xmlObject->endElement(); // Elemento Informe
        $xmlObject->endElement(); // archivo

        $xmlObject->endDocument();

        return $xmlObject->outputMemory();
    }
}
