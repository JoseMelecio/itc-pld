<?php

namespace App\Exports;

class BankAccountManagementExportXML
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
        $xmlObject->writeAttribute('xmlns', 'http://www.uif.shcp.gob.mx/recepcion/spr');
        $xmlObject->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlObject->writeAttribute('xsi:schemaLocation', 'http://www.uif.shcp.gob.mx/recepcion/spr spr.xsd');

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
        $xmlObject->endElement(); // tipo_ocupacion

        if ($this->headers['occupation_type'] == 99) {
            $xmlObject->startElement('descripcion_otra_ocupacion');
            $xmlObject->text($this->headers['occupation_description']);
            $xmlObject->endElement(); // descripcion_otra_ocupacion
        }

        $xmlObject->endElement(); // tipo_ocupacion

        $xmlObject->startElement('clave_actividad');
        $xmlObject->text('SPR');
        $xmlObject->endElement(); // clave_actividad

        if ($this->headers['exempt'] == 'yes') {
            $xmlObject->startElement('excento');
            $xmlObject->text('1');
            $xmlObject->endElement(); // exempt
        }

        $xmlObject->endElement(); // sujeto_obligado

        foreach ($this->data['items'] as $key => $notice) {
            $xmlObject->startElement('aviso');
            $xmlObject->startElement('referencia_aviso');
            $xmlObject->text($this->headers['notice_reference']);
            $xmlObject->endElement(); // referencia_aviso

            if (strlen($notice['modificatorio']['folioModificatorio']) > 0) {
                $xmlObject->startElement('modificatorio');
                $xmlObject->startElement('folio_modificacion');
                $xmlObject->text($notice['modificatorio']['folioModificatorio']);
                $xmlObject->endElement(); // folio_modificacion

                $xmlObject->startElement('descripcion_modificacion');
                $xmlObject->text($notice['modificatorio']['descripcionModificatorio']);
                $xmlObject->endElement(); // descripcion_modificacion

                $xmlObject->endElement(); // modificatorio
            }

            $xmlObject->startElement('prioridad');
            $xmlObject->text(1);
            $xmlObject->endElement(); //

            $xmlObject->startElement('alerta');
            $xmlObject->startElement('tipo_alerta');
            $xmlObject->text($notice['alerta']['tipoAlerta']);
            $xmlObject->endElement(); // tipo_alerta

            if ($notice['alerta']['tipoAlerta'] === 9999) {
                $xmlObject->startElement('descripcion_alerta');
                $xmlObject->text($notice['alerta']['descripcionAlerta']);
                $xmlObject->endElement(); // descripcion_alerta
            }

            $xmlObject->endElement(); // alerta

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

                $xmlObject->endElement(); // Elemento persona_moral

                $xmlObject->startElement('representante_apoderado');
                $xmlObject->startElement('nombre');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['nombre']);
                $xmlObject->endElement(); // Elemento nombre

                $xmlObject->startElement('apellido_paterno');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['apellidoPaterno']);
                $xmlObject->endElement(); // Elemento apellido_paterno

                $xmlObject->startElement('apellido_materno');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['apellidoMaterno']);
                $xmlObject->endElement(); // Elemento apellido_materno

                $xmlObject->startElement('fecha_nacimiento');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['fechaNacimiento']);
                $xmlObject->endElement(); // Elemento fecha_nacimiento

                if (strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['rfc']) > 0) {
                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['rfc']);
                    $xmlObject->endElement(); // Elemento rfc
                }

                if (strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['curp']) > 0) {
                    $xmlObject->startElement('curp');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['curp']);
                    $xmlObject->endElement(); // Elemento curp
                }

                $xmlObject->endElement(); // Elemento representante_apoderado
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
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['nombre']);
                $xmlObject->endElement(); // Elemento nombre

                $xmlObject->startElement('apellido_paterno');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['apellidoPaterno']);
                $xmlObject->endElement(); // Elemento apellido_paterno

                $xmlObject->startElement('apellido_materno');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['apellidoMaterno']);
                $xmlObject->endElement(); // Elemento apellido_materno

                $xmlObject->startElement('fecha_nacimiento');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['fechaNacimiento']);
                $xmlObject->endElement(); // Elemento fecha_nacimiento

                if (strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['rfc']) > 0) {
                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['rfc']);
                    $xmlObject->endElement(); // Elemento rfc
                }

                if (strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['curp']) > 0) {
                    $xmlObject->startElement('curp');
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['curp']);
                    $xmlObject->endElement(); // Elemento curp
                }

                $xmlObject->endElement(); // Elemento representante_apoderado

                $xmlObject->endElement(); // Elemento fideicomiso
            }

            $xmlObject->endElement(); // Elemento tipo_persona

            // Tipo domicilio
            $xmlObject->startElement('tipo_domicilio');

            // Validamos si es domicilio nacional o extranjero
            if (strlen($notice['identificacionPersonaObjetoAviso']['domicilioNacionalPersonaAviso']['cp']) > 0) {
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
            $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosContactoAviso']['pais']);
            $xmlObject->endElement(); // Elemento clave_pais

            $xmlObject->startElement('numero_telefono');
            $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosContactoAviso']['telefono']);
            $xmlObject->endElement(); // Elemento numero_telefono

            if (strlen($notice['identificacionPersonaObjetoAviso']['datosContactoAviso']['correo'] > 0)) {
                $xmlObject->startElement('correo_electronico');
                $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosContactoAviso']['correo']);
                $xmlObject->endElement(); // Elemento correo_electronico
            }

            $xmlObject->endElement(); // Elemento telefono

            $xmlObject->endElement(); // Persona aviso

            // Datos del dueño o beneficiario
            // Validamos si hay datos del dueño o beneficiario
            if (strlen($notice['beneficiarioControlador']['personaFisica']['nombre']) > 0 ||
                strlen($notice['beneficiarioControlador']['personaMoral']['nombre']) > 0 ||
                strlen($notice['beneficiarioControlador']['fideicomiso']['denominacion']) > 0) {

                $xmlObject->startElement('dueno_beneficiario');
                $xmlObject->startElement('tipo_persona');

                // Persona Fisica
                if (strlen($notice['beneficiarioControlador']['personaFisica']['nombre']) > 0) {
                    $xmlObject->startElement('persona_fisica');
                    $xmlObject->startElement('nombre');
                    $xmlObject->text($notice['beneficiarioControlador']['personaFisica']['nombre']);
                    $xmlObject->endElement(); // Elemento nombre

                    $xmlObject->startElement('apellido_paterno');
                    $xmlObject->text($notice['beneficiarioControlador']['personaFisica']['apellidoPaterno']);
                    $xmlObject->endElement(); // Elemento apellido_paterno

                    $xmlObject->startElement('apellido_materno');
                    $xmlObject->text($notice['beneficiarioControlador']['personaFisica']['apellidoMaterno']);
                    $xmlObject->endElement(); // Elemento apellido_materno

                    $xmlObject->startElement('fecha_nacimiento');
                    $xmlObject->text($notice['beneficiarioControlador']['personaFisica']['fechaNacimiento']);
                    $xmlObject->endElement(); // Elemento fecha_nacimiento

                    if (strlen($notice['beneficiarioControlador']['personaFisica']['rfc']) > 0) {
                        $xmlObject->startElement('rfc');
                        $xmlObject->text($notice['beneficiarioControlador']['personaFisica']['rfc']);
                        $xmlObject->endElement(); // Elemento rfc
                    }

                    if (strlen($notice['beneficiarioControlador']['personaFisica']['curp']) > 0) {
                        $xmlObject->startElement('curp');
                        $xmlObject->text($notice['beneficiarioControlador']['personaFisica']['curp']);
                        $xmlObject->endElement(); // Elemento curp
                    }

                    $xmlObject->startElement('pais_nacionalidad');
                    $xmlObject->text($notice['beneficiarioControlador']['personaFisica']['paisNacionalidad']);
                    $xmlObject->endElement(); // Elemento pais_nacionalidad

                    $xmlObject->endElement(); // Elemento persona_fisica
                }
                // Persona Moral
                elseif (strlen($notice['beneficiarioControlador']['personaMoral']['razonSocial']) > 0) {
                    $xmlObject->startElement('persona_moral');
                    $xmlObject->startElement('denominacion_razon');
                    $xmlObject->text($notice['beneficiarioControlador']['personaMoral']['razonSocial']);
                    $xmlObject->endElement(); // Elemento denominacion_razon

                    $xmlObject->startElement('fecha_constitucion');
                    $xmlObject->text($notice['beneficiarioControlador']['personaMoral']['fechaConstitucion']);
                    $xmlObject->endElement(); // Elemento fecha_constitucion

                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['beneficiarioControlador']['personaMoral']['rfc']);
                    $xmlObject->endElement(); // Elemento rfc

                    $xmlObject->startElement('pais_nacionalidad');
                    $xmlObject->text($notice['beneficiarioControlador']['personaMoral']['nacionalidad']);
                    $xmlObject->endElement(); // Elemento pais_nacionalidad

                    $xmlObject->endElement(); // Elemento persona_moral
                }
                // Fideicomiso
                else {
                    $xmlObject->startElement('fideicomiso');
                    $xmlObject->startElement('denominacion_razon');
                    $xmlObject->text($notice['beneficiarioControlador']['fideicomiso']['denominacion']);
                    $xmlObject->endElement(); // Elemento denominacion_razon

                    $xmlObject->startElement('rfc');
                    $xmlObject->text($notice['beneficiarioControlador']['fideicomiso']['rfc']);
                    $xmlObject->endElement(); // Elemento rfc

                    $xmlObject->startElement('identificador_fideicomiso');
                    $xmlObject->text($notice['beneficiarioControlador']['fideicomiso']['identificador']);
                    $xmlObject->endElement(); // Elemento identificador_fideicomiso

                    $xmlObject->endElement(); // Elemento fideicomiso
                }
                $xmlObject->endElement(); // tipo_persona

                $xmlObject->endElement(); // Elemento dueno_beneficiario
            }

            $xmlObject->startElement('detalle_operaciones');
            $xmlObject->startElement('datos_operacion');

            $xmlObject->startElement('fecha_operacion');
            $xmlObject->text($notice['operacionFinanciera']['operacionFinanciera']['fechaOperacion']);
            $xmlObject->endElement(); // fecha_operacion

            $xmlObject->startElement('tipo_actividad');
            $xmlObject->startElement('administracion_recursos');
            $xmlObject->startElement('tipo_activo');
            $xmlObject->startElement('activo_banco');

            $xmlObject->startElement('estatus_manejo');
            $xmlObject->text($notice['operacionFinanciera']['datosOperacion']['estatusManejo']);
            $xmlObject->endElement(); // estatus_manejo

            $xmlObject->startElement('clave_tipo_institucion');
            $xmlObject->text($notice['operacionFinanciera']['datosOperacion']['tipoInstitucion']);
            $xmlObject->endElement(); // clave_tipo_institucion

            $xmlObject->startElement('nombre_institucion');
            $xmlObject->text($notice['operacionFinanciera']['datosOperacion']['nombreInstitucion']);
            $xmlObject->endElement(); // nombre_institucion

            $xmlObject->startElement('numero_cuenta');
            $xmlObject->text($notice['operacionFinanciera']['datosOperacion']['numeroCuenta']);
            $xmlObject->endElement(); // numero_cuenta

            $xmlObject->endElement(); // activo_banco
            $xmlObject->endElement(); // tipo_activo

            $xmlObject->startElement('numero_operaciones');
            $xmlObject->text($notice['operacionFinanciera']['datosOperacion']['numeroOperaciones']);
            $xmlObject->endElement(); // numero_operaciones

            $xmlObject->endElement(); // administracion_recursos
            $xmlObject->endElement(); // tipo_actividad

            $xmlObject->startElement('datos_operacion_financiera');
            $xmlObject->startElement('fecha_pago');
            $xmlObject->text($notice['operacionFinanciera']['operacionFinanciera']['fechaOperacion']);
            $xmlObject->endElement(); // fecha_pago

            $xmlObject->startElement('instrumento_monetario');
            $xmlObject->text($notice['operacionFinanciera']['operacionFinanciera']['instrumentoMonetario']);
            $xmlObject->endElement(); // instrumento_monetario

            if (strlen($notice['operacionFinanciera']['operacionFinanciera']['tipoActivoVirtual'] > 1)) {
                $xmlObject->startElement('activo_virtual');
                $xmlObject->startElement('tipo_activo_virtual');
                $xmlObject->text($notice['operacionFinanciera']['operacionFinanciera']['tipoActivoVirtual']);
                $xmlObject->endElement(); // tipo_activo_virtual

                if ($notice['operacionFinanciera']['operacionFinanciera']['tipoActivoVirtual'] == '999999') {
                    $xmlObject->startElement('descripcion_activo_virtual');
                    $xmlObject->text($notice['operacionFinanciera']['operacionFinanciera']['descripcionActivoVirtual']);
                    $xmlObject->endElement(); // descripcion_activo_virtual
                }

                $xmlObject->startElement('cantidad_activo_virtual');
                $xmlObject->text($notice['operacionFinanciera']['operacionFinanciera']['cantidadActivoVirtual']);
                $xmlObject->endElement(); // cantidad_activo_virtual
                $xmlObject->endElement(); // activo_virtual
            }

            $xmlObject->startElement('moneda');
            $xmlObject->text($notice['operacionFinanciera']['operacionFinanciera']['moenda']);
            $xmlObject->endElement(); // moneda

            $xmlObject->startElement('monto_operacion');
            $xmlObject->text($notice['operacionFinanciera']['operacionFinanciera']['montoOperacion']);
            $xmlObject->endElement(); // monto_operacion

            $xmlObject->endElement(); // datos_operacion_financiera

            $xmlObject->endElement(); // datos_operacion
            $xmlObject->endElement(); // Elemento detalle_operaciones
        }

        $xmlObject->endElement(); // Informe
        $xmlObject->endElement(); // archivo

        $xmlObject->endDocument();

        return $xmlObject->outputMemory();
    }
}
