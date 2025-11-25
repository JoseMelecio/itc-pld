<?php

namespace App\Exports;

class RealEstateDevelopmentExportXML
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

        $xmlObject->writeAttribute('xmlns', 'http://www.uif.shcp.gob.mx/recepcion/din');
        $xmlObject->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlObject->writeAttribute('xsi:schemaLocation', 'http://www.uif.shcp.gob.mx/recepcion/din din.xsd');

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
        $xmlObject->text('DIN');
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
            $xmlObject->endElement(); //referencia_aviso

            $xmlObject->startElement('prioridad');
            $xmlObject->text($notice['modificatorio']['prioridad']);
            $xmlObject->endElement(); //prioridad

            if (strlen($notice['modificatorio']['folioModificacion']) > 0 && strlen($notice['modificatorio']['descripcionModificacion']) > 0) {
                $xmlObject->startElement('modificatorio');
                $xmlObject->startElement('folio_modificacion');
                $xmlObject->text($notice['modificatorio']['folioModificacion']);
                $xmlObject->endElement(); // folio_modificacion

                $xmlObject->startElement('descripcion_modificacion');
                $xmlObject->text($notice['modificatorio']['descripcionModificacion']);
                $xmlObject->endElement(); // descripcion_modificacion

                $xmlObject->startElement('prioridad');
                $xmlObject->text($notice['modificatorio']['prioridad']);
                $xmlObject->endElement(); // prioridad

                $xmlObject->endElement(); // modificatorio
            }

            $xmlObject->startElement('alerta');
            $xmlObject->startElement('tipo_alerta');
            $xmlObject->text($notice['alerta']['tipo_alerta']);;
            $xmlObject->endElement(); // tipo_alerta

            if ($notice['alerta']['tipo_alerta'] == '9999') {
                $xmlObject->startElement('descripcion_alerta');
                $xmlObject->text($notice['alerta']['descripcion_alerta']);;
                $xmlObject->endElement(); // tipo_alerta
            }
            $xmlObject->endElement(); // alerta

            $xmlObject->startElement('detalle_operaciones');
            $xmlObject->startElement('datos_operacion');

            $xmlObject->startElement('tipo_operacion');
            $xmlObject->text('1601');
            $xmlObject->endElement(); // tipo_operacion

            $xmlObject->startElement('desarrollos_inmobiliarios');
            $xmlObject->startElement('datos_desarrollo');

            $xmlObject->startElement('objeto_aviso_anterior');
            $xmlObject->text($notice['desarrolloInmobiliario']['objetoAvisoAnterior']);
            $xmlObject->endElement(); // objeto_aviso_anterior

            $xmlObject->startElement('modificacion');
            $xmlObject->text($notice['desarrolloInmobiliario']['modificacion']);
            $xmlObject->endElement(); // modificacion

            $xmlObject->startElement('entidad_federativa');
            $xmlObject->text($notice['desarrolloInmobiliario']['entidadFederativa']);
            $xmlObject->endElement(); // entidad_federativa

            $xmlObject->startElement('registro_licencia');
            $xmlObject->text($notice['desarrolloInmobiliario']['registroLicencia']);
            $xmlObject->endElement(); // registro_licencia

            $xmlObject->startElement('caracteristicas_desarrollo');

            $xmlObject->startElement('codigo_postal');
            $xmlObject->text($notice['caracteristicasDesarrollo']['codigoPostal']);
            $xmlObject->endElement(); // codigo_postal

            $xmlObject->startElement('colonia');
            $xmlObject->text($notice['caracteristicasDesarrollo']['colonia']);
            $xmlObject->endElement(); // colonia

            $xmlObject->startElement('calle');
            $xmlObject->text($notice['caracteristicasDesarrollo']['calle']);
            $xmlObject->endElement(); // calle

            $xmlObject->startElement('tipo_desarrollo');
            $xmlObject->text($notice['caracteristicasDesarrollo']['tipoDesarrollo']);
            $xmlObject->endElement(); // tipo_desarrollo

            if ($notice['caracteristicasDesarrollo']['tipoDesarrollo'] == '99') {
                $xmlObject->startElement('descripcion_desarrollo');
                $xmlObject->text($notice['caracteristicasDesarrollo']['descripcionDesarrollo']);
                $xmlObject->endElement(); // descripcion_desarrollo
            }

            $xmlObject->startElement('monto_desarrollo');
            $xmlObject->text(number_format($notice['caracteristicasDesarrollo']['montoDesarrollo'], 2, '.', ''));
            $xmlObject->endElement(); // monto_desarrollo

            $xmlObject->startElement('unidades_comercializadas');
            $xmlObject->text(number_format($notice['caracteristicasDesarrollo']['unidadesComercializadas'], 2, '.', ''));
            $xmlObject->endElement(); // unidades_comercializadas

            $xmlObject->startElement('costo_unidad');
            $xmlObject->text(number_format($notice['caracteristicasDesarrollo']['costoUnidad'], 2, '.', ''));
            $xmlObject->endElement(); // costo_unidad

            $xmlObject->startElement('otras_empresas');
            $xmlObject->text($notice['caracteristicasDesarrollo']['otrasEmpresas']);
            $xmlObject->endElement(); // otras_empresas

            $xmlObject->endElement(); // caracteristicas_desarrollo

            $xmlObject->endElement(); // datos_desarrollo

            $xmlObject->endElement(); // desarrollos_inmobiliarios

            $xmlObject->startElement('aportaciones');

            $xmlObject->startElement('fecha_aportacion');
            $xmlObject->text($notice['aportaciones']['fechaAportacion']);
            $xmlObject->endElement(); // fecha_aportacion

            $xmlObject->startElement('tipo_aportacion');

            if (strlen($notice['aportaciones']['recursosPropios']['numerario']['instrumentoMonetario']) > 0) {
                $xmlObject->startElement('recursos_propios');

                $xmlObject->startElement('datos_aportacion');

                if (strlen($notice['aportaciones']['recursosPropios']['numerario']['instrumentoMonetario']) > 0) {

                    $xmlObject->startElement('aportacion_numerario');

                    $xmlObject->startElement('instrumento_monetario');
                    $xmlObject->text($notice['aportaciones']['recursosPropios']['numerario']['instrumentoMonetario']);
                    $xmlObject->endElement(); // instrumento_monetario

                    $xmlObject->startElement('moneda');
                    $xmlObject->text($notice['aportaciones']['recursosPropios']['numerario']['moneda']);
                    $xmlObject->endElement(); // moneda

                    $xmlObject->startElement('monto_aportacion');
                    $xmlObject->text(number_format($notice['aportaciones']['recursosPropios']['numerario']['montoAportacion'], 2, '.', ''));
                    $xmlObject->endElement(); // monto_aportacion

                    $xmlObject->startElement('aportacion_fideicomiso');
                    $xmlObject->text($notice['aportaciones']['recursosPropios']['numerario']['aportacionFideicomiso']);
                    $xmlObject->endElement(); // aportacion_fideicomiso

                    if ($notice['aportaciones']['recursosPropios']['numerario']['aportacionFideicomiso'] == 'SI') {
                        $xmlObject->startElement('nombre_institucion');
                        $xmlObject->text($notice['aportaciones']['recursosPropios']['numerario']['nombreInstitucion']);
                        $xmlObject->endElement(); // nombre_institucion
                    }
                    $xmlObject->endElement(); // aportacion_numerario

                } else {

                    $xmlObject->startElement('aportacion_especie');

                    $xmlObject->startElement('descripcion_bien');
                    $xmlObject->text($notice['aportaciones']['recursosPropios']['especie']['descripcionBien']);
                    $xmlObject->endElement(); // descripcion_bien

                    $xmlObject->startElement('monto_estimado');
                    $xmlObject->text(number_format($notice['aportaciones']['recursosPropios']['especie']['montoEstimado'], 2, '.', ''));
                    $xmlObject->endElement(); // monto_estimado
                    $xmlObject->endElement(); // aportacion_especie

                }

                $xmlObject->endElement(); // datos_aportacion
                $xmlObject->endElement(); // recursos_propios
            }

            if ($notice['aportaciones']['socios']['cantidadSocios'] > 0) {
                $xmlObject->startElement('socios');
                $xmlObject->startElement('numero_socios');
                $xmlObject->text($notice['aportaciones']['socios']['cantidadSocios']);
                $xmlObject->endElement(); // numero_socios

                for ($e_contadorSocio = 1; $e_contadorSocio <= $notice['aportaciones']['socios']['cantidadSocios']; $e_contadorSocio++) {
                    $xmlObject->startElement('detalle_socios');
                    $xmlObject->startElement('datos_socio');

                    $xmlObject->startElement('aportacion_anterior_socio');
                    $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['datosGenerales']['aportacionAnterior']);
                    $xmlObject->endElement(); // aportacion_anterior_socio

                    $xmlObject->startElement('rfc_socio');
                    $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['datosGenerales']['rfcSocio']);
                    $xmlObject->endElement(); // rfc_socio

                    $xmlObject->startElement('tipo_persona_socio');

                    if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['personaFisica']['nombre']) > 0) {
                        $xmlObject->startElement('persona_fisica');

                        $xmlObject->startElement('nombre');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['personaFisica']['nombre']);
                        $xmlObject->endElement(); // nombre

                        $xmlObject->startElement('apellido_paterno');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['personaFisica']['apellidoMaterno']);
                        $xmlObject->endElement(); // apellido_paterno

                        $xmlObject->startElement('apellido_materno');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['personaFisica']['apellidoPaterno']);
                        $xmlObject->endElement(); // apellido_materno

                        $xmlObject->startElement('fecha_nacimiento');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['personaFisica']['fechaNacimiento']);
                        $xmlObject->endElement(); // fecha_nacimiento

                        if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['personaFisica']['curp']) > 0) {
                            $xmlObject->startElement('curp');
                            $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['personaFisica']['curp']);
                            $xmlObject->endElement(); // curp
                        }

                        $xmlObject->startElement('pais_nacionalidad');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['personaFisica']['pais']);
                        $xmlObject->endElement(); // pais_nacionalidad

                        $xmlObject->startElement('actividad_economica');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['personaFisica']['actividadEconomica']);
                        $xmlObject->endElement(); // actividad_economica

                        $xmlObject->endElement(); // persona_fisica
                    }

                    if ($notice['aportaciones']['socios'][$e_contadorSocio]['personaMoral']['razonSocial'] > 0) {
                        $xmlObject->startElement('persona_moral');

                        $xmlObject->startElement('denominacion_razon');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['personaMoral']['razonSocial']);
                        $xmlObject->endElement(); // denominacion_razon

                        $xmlObject->startElement('fecha_constitucion');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['personaMoral']['fechaConstitucion']);
                        $xmlObject->endElement(); // fecha_constitucion

                        $xmlObject->startElement('pais_nacionalidad');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['personaMoral']['pais']);
                        $xmlObject->endElement(); // pais_nacionalidad

                        $xmlObject->startElement('giro_mercantil');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['personaMoral']['giroMercantil']);
                        $xmlObject->endElement(); // giro_mercantil

                        $xmlObject->startElement('representante_apoderado');

                        $xmlObject->startElement('nombre');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['representante']['nombre']);
                        $xmlObject->endElement(); // nombre

                        $xmlObject->startElement('apellido_paterno');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['representante']['apellidoMaterno']);
                        $xmlObject->endElement(); // apellido_paterno

                        $xmlObject->startElement('apellido_materno');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['representante']['apellidoPaterno']);
                        $xmlObject->endElement(); // apellido_materno

                        $xmlObject->startElement('fecha_nacimiento');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['representante']['fechaNacimiento']);
                        $xmlObject->endElement(); // fecha_nacimiento

                        if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['representante']['rfc']) > 0) {
                            $xmlObject->startElement('rfc');
                            $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['representante']['rfc']);
                            $xmlObject->endElement(); // rfc
                        }

                        if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['representante']['curp']) > 0) {
                            $xmlObject->startElement('curp');
                            $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['representante']['curp']);
                            $xmlObject->endElement(); // curp
                        }
                        $xmlObject->endElement(); // representante_apoderado

                        $xmlObject->endElement(); // persona_moral
                    }// Fin persona moral

                    if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['fideicomiso']['denominacion']) > 0) {
                        $xmlObject->startElement('fideicomiso');

                        $xmlObject->startElement('denominacion_razon');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['fideicomiso']['denominacion']);
                        $xmlObject->endElement(); // denominacion_razon

                        $xmlObject->startElement('rfc');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['fideicomiso']['rfc']);
                        $xmlObject->endElement(); // rfc

                        $xmlObject->startElement('identificador_fideicomiso');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['fideicomiso']['identificador']);
                        $xmlObject->endElement(); // identificador_fideicomiso

                        $xmlObject->endElement(); // fideicomiso
                    }// Fin de fideicomiso

                    $xmlObject->endElement(); // tipo_persona_socio

                    $xmlObject->startElement('tipo_domicilio_socio');

                    if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioNacional']['colonia']) > 0) {
                        $xmlObject->startElement('nacional');

                        $xmlObject->startElement('colonia');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioNacional']['colonia']);
                        $xmlObject->endElement(); // colonia

                        $xmlObject->startElement('calle');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioNacional']['calle']);
                        $xmlObject->endElement(); // calle

                        $xmlObject->startElement('numero_exterior');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioNacional']['numeroExterior']);
                        $xmlObject->endElement(); // numero_exterior

                        if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioNacional']['numeroInterior']) > 0) {
                            $xmlObject->startElement('numero_interior');
                            $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioNacional']['numeroInterior']);
                            $xmlObject->endElement(); // numero_interior
                        }

                        $xmlObject->startElement('codigo_postal');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioNacional']['cp']);
                        $xmlObject->endElement(); // codigo_postal

                        $xmlObject->endElement(); // nacional
                    }

                    if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioExtranjero']['pais']) > 0) {
                        $xmlObject->startElement('extranjero');

                        $xmlObject->startElement('pais');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioExtranjero']['pais']);
                        $xmlObject->endElement(); // pais

                        $xmlObject->startElement('estado_provincia');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioExtranjero']['estado']);
                        $xmlObject->endElement(); // estado_provincia

                        $xmlObject->startElement('ciudad_poblacion');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioExtranjero']['municipio']);
                        $xmlObject->endElement(); // ciudad_poblacion

                        $xmlObject->startElement('colonia');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioExtranjero']['colonia']);
                        $xmlObject->endElement(); // colonia

                        $xmlObject->startElement('calle');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioExtranjero']['calle']);
                        $xmlObject->endElement(); // calle

                        $xmlObject->startElement('numero_exterior');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioExtranjero']['numeroExterior']);
                        $xmlObject->endElement(); // numero_exterior

                        if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioExtranjero']['numeroInterior']) > 0) {
                            $xmlObject->startElement('numero_interior');
                            $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioExtranjero']['numeroInterior']);
                            $xmlObject->endElement(); // numero_interior
                        }
                        $xmlObject->startElement('codigo_postal');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['domicilioExtranjero']['cp']);
                        $xmlObject->endElement(); // codigo_postal

                        $xmlObject->endElement(); // extranjero
                    }// Fin domicilio extranjero

                    $xmlObject->endElement(); // tipo_domicilio_socio

                    $xmlObject->startElement('telefono');

                    $xmlObject->startElement('clave_pais');
                    $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['contacto']['pais']);
                    $xmlObject->endElement(); // clave_pais

                    $xmlObject->startElement('numero_telefono');
                    $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['contacto']['telefono']);
                    $xmlObject->endElement(); // numero_telefono

                    if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['contacto']['correo']) > 0) {
                        $xmlObject->startElement('correo_electronico');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['contacto']['correo']);
                        $xmlObject->endElement(); // correo_electronico
                    }

                    $xmlObject->endElement(); // telefono

                    $xmlObject->startElement('detalle_aportaciones');
                    $xmlObject->startElement('datos_aportacion');

                    if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['recursoNumeral']['instrumentoMonetario']) > 0) {
                        $xmlObject->startElement('aportacion_numerario');

                        $xmlObject->startElement('instrumento_monetario');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['recursoNumeral']['instrumentoMonetario']);
                        $xmlObject->endElement(); // instrumento_monetario

                        $xmlObject->startElement('moneda');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['recursoNumeral']['moneda']);
                        $xmlObject->endElement(); // moneda

                        $xmlObject->startElement('monto_aportacion');
                        $xmlObject->text(number_format($notice['aportaciones']['socios'][$e_contadorSocio]['recursoNumeral']['montoAportacion'], 2, '.', ''));
                        $xmlObject->endElement(); // monto_aportacion

                        $xmlObject->startElement('aportacion_fideicomiso');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['recursoNumeral']['aportacionFideicomiso']);
                        $xmlObject->endElement(); // aportacion_fideicomiso

                        if ($notice['aportaciones']['socios'][$e_contadorSocio]['recursoNumeral']['aportacionFideicomiso'] == 'SI') {
                            $xmlObject->startElement('nombre_institucion');
                            $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['recursoNumeral']['nombreInstitucion']);
                            $xmlObject->endElement(); // nombre_institucion
                        }

                        $xmlObject->endElement(); // aportacion_numerario
                    }//Fin aportacion nuimerario

                    if (strlen($notice['aportaciones']['socios'][$e_contadorSocio]['recursoEspecie']['descripcionBien']) > 0) {
                        $xmlObject->startElement('aportacion_especie');

                        $xmlObject->startElement('descripcion_bien');
                        $xmlObject->text($notice['aportaciones']['socios'][$e_contadorSocio]['recursoEspecie']['descripcionBien']);
                        $xmlObject->endElement(); // descripcion_bien

                        $xmlObject->startElement('monto_estimado');
                        $xmlObject->text(number_format($notice['aportaciones']['socios'][$e_contadorSocio]['recursoEspecie']['montoEstimado'], 2, '.', ''));
                        $xmlObject->endElement(); // monto_estimado

                        $xmlObject->endElement(); // aportacion_especie
                    }
                    $xmlObject->endElement(); // datos_aportacion
                    $xmlObject->endElement(); // detalle_aportaciones

                    $xmlObject->endElement(); // datos_socio
                    $xmlObject->endElement(); // detalle_socios
                }// Fin del for

                $xmlObject->endElement(); // socios
            } // Fin socios

            if (strlen($notice['aportaciones']['terceros']['cantidadTerceros']) > 0) {
                $xmlObject->startElement('terceros');

                $xmlObject->startElement('numero_terceros');
                $xmlObject->text($notice['aportaciones']['terceros']['cantidadTerceros']);
                $xmlObject->endElement(); // numero_terceros

                $xmlObject->startElement('detalle_terceros');

                for ($e_contadorTerceros = 1; $e_contadorTerceros <= $notice['aportaciones']['terceros']['cantidadTerceros']; $e_contadorTerceros++) {
                    $xmlObject->startElement('datos_tercero');

                    $xmlObject->startElement('tipo_tercero');
                    $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['datosGenerales']['tipoTercero']);
                    $xmlObject->endElement(); // tipo_tercero

                    if ($notice['aportaciones']['terceros'][$e_contadorTerceros]['datosGenerales']['tipoTercero'] == 99) {
                        $xmlObject->startElement('descripcion_tercero');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['datosGenerales']['descripcionTercero']);
                        $xmlObject->endElement(); // descripcion_tercero
                    }

                    $xmlObject->startElement('tipo_persona_tercero');

                    if (strlen($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaFisica']['nombre']) > 0) {
                        $xmlObject->startElement('persona_fisica');

                        $xmlObject->startElement('nombre');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaFisica']['nombre']);
                        $xmlObject->endElement(); // nombre

                        $xmlObject->startElement('apellido_paterno');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaFisica']['apellidoMaterno']);
                        $xmlObject->endElement(); // apellido_paterno

                        $xmlObject->startElement('apellido_materno');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaFisica']['apellidoPaterno']);
                        $xmlObject->endElement(); // apellido_materno

                        $xmlObject->startElement('fecha_nacimiento');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaFisica']['fechaNacimiento']);
                        $xmlObject->endElement(); // fecha_nacimiento

                        if (strlen($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaFisica']['rfc']) > 0) {
                            $xmlObject->startElement('rfc');
                            $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaFisica']['rfc']);
                            $xmlObject->endElement(); // rfc
                        }

                        if (strlen($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaFisica']['curp']) > 0) {
                            $xmlObject->startElement('curp');
                            $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaFisica']['curp']);
                            $xmlObject->endElement(); // curp
                        }

                        $xmlObject->startElement('pais_nacionalidad');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaFisica']['pais']);
                        $xmlObject->endElement(); // pais_nacionalidad

                        $xmlObject->startElement('actividad_economica');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaFisica']['actividadEconomica']);
                        $xmlObject->endElement(); // actividad_economica

                        $xmlObject->endElement(); // persona_fisica

                    }// fin persona fisica

                    if (strlen($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaMoral']['razonSocial']) > 0) {
                        $xmlObject->startElement('persona_moral');

                        $xmlObject->startElement('denominacion_razon');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaMoral']['razonSocial']);
                        $xmlObject->endElement(); // denominacion_razon

                        $xmlObject->startElement('fecha_constitucion');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaMoral']['fechaConstitucion']);
                        $xmlObject->endElement(); // fecha_constitucion

                        $xmlObject->startElement('rfc');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaMoral']['rfc']);
                        $xmlObject->endElement(); // rfc

                        $xmlObject->startElement('pais_nacionalidad');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaMoral']['pais']);
                        $xmlObject->endElement(); // pais_nacionalidad

                        $xmlObject->startElement('giro_mercantil');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['personaMoral']['giroMercantil']);
                        $xmlObject->endElement(); // giro_mercantil

                        $xmlObject->startElement('representante_apoderado');

                        $xmlObject->startElement('nombre');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['representanteLegal']['nombre']);
                        $xmlObject->endElement(); // nombre

                        $xmlObject->startElement('apellido_paterno');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['representanteLegal']['apellidoMaterno']);
                        $xmlObject->endElement(); // apellido_paterno

                        $xmlObject->startElement('apellido_materno');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['representanteLegal']['apellidoPaterno']);
                        $xmlObject->endElement(); // apellido_materno

                        $xmlObject->startElement('fecha_nacimiento');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['representanteLegal']['fechaNacimiento']);
                        $xmlObject->endElement(); // fecha_nacimiento

                        if (strlen($notice['aportaciones']['terceros'][$e_contadorTerceros]['representanteLegal']['rfc']) > 0) {
                            $xmlObject->startElement('rfc');
                            $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['representanteLegal']['rfc']);
                            $xmlObject->endElement(); // rfc
                        }

                        if (strlen($notice['aportaciones']['terceros'][$e_contadorTerceros]['representanteLegal']['curp']) > 0) {
                            $xmlObject->startElement('curp');
                            $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['representanteLegal']['curp']);
                            $xmlObject->endElement(); // curp
                        }

                        $xmlObject->endElement(); // representante_apoderado

                        $xmlObject->endElement(); // persona_moral
                    }

                    if (strlen($notice['aportaciones']['terceros'][$e_contadorTerceros]['fideicomiso']['denominacion']) > 0) {
                        $xmlObject->startElement('fideicomiso');

                        $xmlObject->startElement('denominacion_razon');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['fideicomiso']['denominacion']);
                        $xmlObject->endElement(); // denominacion_razon

                        $xmlObject->startElement('rfc');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['fideicomiso']['rfc']);
                        $xmlObject->endElement(); // rfc

                        $xmlObject->startElement('identificador_fideicomiso');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['fideicomiso']['identificador']);
                        $xmlObject->endElement(); // identificador_fideicomiso

                        $xmlObject->endElement(); // fideicomiso
                    }
                    $xmlObject->endElement(); // tipo_persona_tercero

                    $xmlObject->startElement('detalle_aportaciones');
                    $xmlObject->startElement('datos_aportacion');

                    if (strlen($notice['aportaciones']['terceros'][$e_contadorTerceros]['recursoNumeral']['instrumentoMonetario']) > 0) {
                        $xmlObject->startElement('aportacion_numerario');

                        $xmlObject->startElement('instrumento_monetario');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['recursoNumeral']['instrumentoMonetario']);
                        $xmlObject->endElement(); // instrumento_monetario

                        $xmlObject->startElement('moneda');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['recursoNumeral']['moneda']);
                        $xmlObject->endElement(); // moneda

                        $xmlObject->startElement('monto_aportacion');
                        $xmlObject->text(number_format($notice['aportaciones']['terceros'][$e_contadorTerceros]['recursoNumeral']['montoAportacion'], 2, '.', ''));
                        $xmlObject->endElement(); // monto_aportacion

                        $xmlObject->startElement('aportacion_fideicomiso');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['recursoNumeral']['aportacionFideicomiso']);
                        $xmlObject->endElement(); // aportacion_fideicomiso

                        if ($notice['aportaciones']['terceros'][$e_contadorTerceros]['recursoNumeral']['aportacionFideicomiso'] == 'SI') {
                            $xmlObject->startElement('nombre_institucion');
                            $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['recursoNumeral']['nombreInstitucion']);
                            $xmlObject->endElement(); // nombre_institucion
                        }

                        $xmlObject->startElement('valor_inmueble_preventa');
                        $xmlObject->text(number_format($notice['aportaciones']['terceros'][$e_contadorTerceros]['recursoNumeral']['valorInmueblePreVenta'], 2, '.', ''));
                        $xmlObject->endElement(); // valor_inmueble_preventa

                        $xmlObject->endElement(); // aportacion_numerario
                    }// Fin aportacion numeral

                    if (strlen($notice['aportaciones']['terceros'][$e_contadorTerceros]['recursoEspecie']['descripcionBien']) > 0) {
                        $xmlObject->startElement('aportacion_especie');

                        $xmlObject->startElement('descripcion_bien');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['recursoEspecie']['descripcionBien']);
                        $xmlObject->endElement(); // descripcion_bien

                        $xmlObject->startElement('monto_estimado');
                        $xmlObject->text($notice['aportaciones']['terceros'][$e_contadorTerceros]['recursoEspecie']['montoEstimado']);
                        $xmlObject->endElement(); // monto_estimado

                        $xmlObject->endElement(); // aportacion_especie
                    }
                    $xmlObject->endElement(); // datos_aportacion
                    $xmlObject->endElement(); // detalle_aportaciones

                    $xmlObject->endElement(); // datos_terceros
                }// Fin del for
                $xmlObject->endElement(); // detalle_terceros

                $xmlObject->endElement(); // terceros
            }// FIn de terceros

            //prestamo Fianciero
            if (strlen($notice['aportaciones']['prestamoFinanciero']['tipoInstitucion']) > 0) {
                $xmlObject->startElement('prestamo_financiero');
                $xmlObject->startElement('datos_prestamo');

                $xmlObject->startElement('tipo_institucion');
                $xmlObject->text($notice['aportaciones']['prestamoFinanciero']['tipoInstitucion']);
                $xmlObject->endElement(); // tipo_institucion

                $xmlObject->startElement('institucion');
                $xmlObject->text($notice['aportaciones']['prestamoFinanciero']['institucion']);
                $xmlObject->endElement(); // institucion

                $xmlObject->startElement('tipo_credito');
                $xmlObject->text($notice['aportaciones']['prestamoFinanciero']['tipoCredito']);
                $xmlObject->endElement(); // tipo_credito

                $xmlObject->startElement('monto_prestamo');
                $xmlObject->text(number_format($notice['aportaciones']['prestamoFinanciero']['montoPrestamo'], 2, '.', ''));
                $xmlObject->endElement(); // monto_prestamo

                $xmlObject->startElement('moneda');
                $xmlObject->text($notice['aportaciones']['prestamoFinanciero']['moneda']);
                $xmlObject->endElement(); // moneda

                $xmlObject->startElement('plazo_meses');
                $xmlObject->text($notice['aportaciones']['prestamoFinanciero']['plazo']);
                $xmlObject->endElement(); // plazo_meses

                $xmlObject->endElement(); // datos_prestamo
                $xmlObject->endElement(); // prestamo_financiero
            }// Fin prestamo financiero

            //Prestamo No financiero
            if (strlen($notice['aportaciones']['prestamoNoFinanciero']['generales']['montoPrestamo']) > 0) {
                $xmlObject->startElement('prestamo_no_financiero');
                $xmlObject->startElement('datos_prestamo');

                $xmlObject->startElement('monto_prestamo');
                $xmlObject->text(number_format($notice['aportaciones']['prestamoNoFinanciero']['generales']['montoPrestamo'], 2, '.', ''));
                $xmlObject->endElement(); // monto_prestamo

                $xmlObject->startElement('moneda');
                $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['generales']['moneda']);
                $xmlObject->endElement(); // moneda

                $xmlObject->startElement('plazo_meses');
                $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['generales']['plazo']);
                $xmlObject->endElement(); // plazo_meses

                // Detalle acreedores
                $xmlObject->startElement('detalle_acreedores');

                $xmlObject->startElement('tipo_persona_acreedor');

                // Persona Fisica
                if (strlen($notice['aportaciones']['prestamoNoFinanciero']['acreedorFisico']['nombre']) > 0) {
                    $xmlObject->startElement('persona_fisica');

                    $xmlObject->startElement('nombre');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorFisico']['nombre']);
                    $xmlObject->endElement(); // nombre

                    $xmlObject->startElement('apellido_paterno');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorFisico']['apellidoMaterno']);
                    $xmlObject->endElement(); // apellido_paterno

                    $xmlObject->startElement('apellido_materno');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorFisico']['apellidoPaterno']);
                    $xmlObject->endElement(); // apellido_materno

                    $xmlObject->startElement('fecha_nacimiento');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorFisico']['fechaNacimiento']);
                    $xmlObject->endElement(); // fecha_nacimiento

                    if (strlen($notice['aportaciones']['prestamoNoFinanciero']['acreedorFisico']['rfc']) > 0) {
                        $xmlObject->startElement('rfc');
                        $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorFisico']['rfc']);
                        $xmlObject->endElement(); // rfc
                    }

                    if (strlen($notice['aportaciones']['prestamoNoFinanciero']['acreedorFisico']['curp']) > 0) {
                        $xmlObject->startElement('curp');
                        $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorFisico']['curp']);
                        $xmlObject->endElement(); // curp
                    }

                    $xmlObject->startElement('pais_nacionalidad');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorFisico']['pais']);
                    $xmlObject->endElement(); // pais_nacionalidad

                    $xmlObject->startElement('actividad_economica');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorFisico']['actividadEconomica']);
                    $xmlObject->endElement(); // actividad_economica

                    $xmlObject->endElement(); // persona_fisica
                }
                // Persona Moral

                if (strlen($notice['aportaciones']['prestamoNoFinanciero']['acreedorMoral']['denominacion']) > 0) {
                    $xmlObject->startElement('persona_moral');

                    $xmlObject->startElement('denominacion_razon');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorMoral']['denominacion']);
                    $xmlObject->endElement(); // denominacion_razon

                    $xmlObject->startElement('fecha_constitucion');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorMoral']['fechaConstitucion']);
                    $xmlObject->endElement(); // fecha_constitucion

                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorMoral']['rfc']);
                    $xmlObject->endElement(); // rfc

                    $xmlObject->startElement('pais_nacionalidad');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorMoral']['pais']);
                    $xmlObject->endElement(); // pais_nacionalidad

                    $xmlObject->startElement('giro_mercantil');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorMoral']['actividadEconomica']);
                    $xmlObject->endElement(); // giro_mercantil

                    $xmlObject->startElement('representante_apoderado');

                    $xmlObject->startElement('nombre');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorApoderado']['nombre']);
                    $xmlObject->endElement(); // nombre

                    $xmlObject->startElement('apellido_paterno');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorApoderado']['apellidoMaterno']);
                    $xmlObject->endElement(); // apellido_paterno

                    $xmlObject->startElement('apellido_materno');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorApoderado']['apellidoPaterno']);
                    $xmlObject->endElement(); // apellido_materno

                    $xmlObject->startElement('fecha_nacimiento');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorApoderado']['fechaNacimiento']);
                    $xmlObject->endElement(); // fecha_nacimiento

                    if (strlen($notice['aportaciones']['prestamoNoFinanciero']['acreedorApoderado']['rfc']) > 0) {
                        $xmlObject->startElement('rfc');
                        $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorApoderado']['rfc']);
                        $xmlObject->endElement(); // rfc
                    }

                    if (strlen($notice['aportaciones']['prestamoNoFinanciero']['acreedorApoderado']['curp']) > 0) {
                        $xmlObject->startElement('curp');
                        $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorApoderado']['curp']);
                        $xmlObject->endElement(); // curp
                    }

                    $xmlObject->endElement(); // representante_apoderado

                    $xmlObject->endElement(); // persona_moral
                }// Fin persona moral

                // Fideicomiso
                if (strlen($notice['aportaciones']['prestamoNoFinanciero']['acreedorFideicomiso']['denominacion']) > 0) {
                    $xmlObject->startElement('fideicomiso');

                    $xmlObject->startElement('denominacion_razon');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorFideicomiso']['denominacion']);
                    $xmlObject->endElement(); // denominacion_razon

                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorFideicomiso']['rfc']);
                    $xmlObject->endElement(); // rfc

                    $xmlObject->startElement('identificador_fideicomiso');
                    $xmlObject->text($notice['aportaciones']['prestamoNoFinanciero']['acreedorFideicomiso']['identificador']);
                    $xmlObject->endElement(); // identificador_fideicomiso

                    $xmlObject->endElement(); // fideicomiso
                } //Fin fideicomiso
                $xmlObject->endElement(); // tipo_persona_acreedor

                $xmlObject->endElement(); // detalle_acreedores

                $xmlObject->endElement(); // datos_prestamo
                $xmlObject->endElement(); // prestamo_no_financiero
            }// Fin prestamo no financiero

            // Financiamiento bursatil
            if (strlen($notice['aportaciones']['prestamoBursatil']['fechaEmision']) > 0) {
                $xmlObject->startElement('financiamiento_bursatil');

                $xmlObject->startElement('fecha_emision');
                $xmlObject->text($notice['aportaciones']['prestamoBursatil']['fechaEmision']);
                $xmlObject->endElement(); // fecha_emision

                $xmlObject->startElement('monto_solicitado');
                $xmlObject->text(number_format($notice['aportaciones']['prestamoBursatil']['montoSolicitado'], 2, '.', ''));
                $xmlObject->endElement(); // monto_solicitado

                $xmlObject->startElement('monto_recibido');
                $xmlObject->text(number_format($notice['aportaciones']['prestamoBursatil']['montoRecibido'], 2, '.', ''));
                $xmlObject->endElement(); // monto_recibido

                $xmlObject->endElement(); // financiamiento_bursatil
            }

            $xmlObject->endElement(); // tipo_aportacion

            $xmlObject->endElement(); // aportaciones

            $xmlObject->endElement(); // datos_operacion
            $xmlObject->endElement(); // detalle_operaciones

            $xmlObject->endElement(); // aviso

        } // Fin foreach m_datos

        $xmlObject->endElement(); // Elemento Informe
        $xmlObject->endElement(); // archivo

        $xmlObject->endDocument();

        return $xmlObject->outputMemory();
    }
}
