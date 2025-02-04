<?php

namespace App\Exports;

class VehicleSaleExportXML
{
    private array $data;
    private array $headers;

    public function __construct(array $data, array $headers)
    {
        $this->data = $data;
        $this->headers = $headers;
    }
    function makeXML()
    {
        //make xml object
        $xmlObject = new \XMLWriter();
        $xmlObject->openMemory();
        $xmlObject->setIndent(true);
        $xmlObject->setIndentString("\t");

        //Add headers
        $xmlObject->startDocument('1.0', 'utf-8');
        $xmlObject->startElement("archivo");
            $xmlObject->writeAttribute("xmlns","http://www.uif.shcp.gob.mx/recepcion/veh");
            $xmlObject->writeAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
            $xmlObject->writeAttribute("xsi:schemaLocation","http://www.uif.shcp.gob.mx/recepcion/veh veh.xsd");


            $xmlObject->startElement("informe");
                $xmlObject->startElement("mes_reportado");
                    $xmlObject->text($this->headers['month']);
                $xmlObject->endElement(); // mes_reportado

                $xmlObject->startElement("sujeto_obligado");

                if(strlen($this->headers['collegiate_entity_tax_id']) > 0){
                    $xmlObject->startElement("clave_entidad_colegiada");
                        $xmlObject->text($this->headers['collegiate_entity_tax_id']);
                    $xmlObject->endElement(); // clave_entidad_colegiada
                }

                $xmlObject->startElement("clave_sujeto_obligado");
                    $xmlObject->text($this->headers['tax_id']);
                $xmlObject->endElement(); // clave_sujeto_obligado

                $xmlObject->startElement("clave_actividad");
                    $xmlObject->text("VEH");
                $xmlObject->endElement(); // clave_actividad

                if($this->headers['exempt'] == "yes"){
                    $xmlObject->startElement("excento");
                        $xmlObject->text('1');
                    $xmlObject->endElement(); // exempt
                }

                $xmlObject->endElement(); // sujeto_obligado

                foreach($this->data['items'] as $key => $notice){
                    $xmlObject->startElement("aviso");
                        $xmlObject->startElement("referencia_aviso");
                            $xmlObject->text($this->headers['notice_reference']);
                        $xmlObject->endElement(); // referencia_aviso

                        $xmlObject->startElement("prioridad");
                            $xmlObject->text(1);
                        $xmlObject->endElement(); #

                    if(strlen($notice['modificatorio']['folio']) > 0){
                        $xmlObject->startElement("modificatorio");
                            $xmlObject->startElement("folio_modificacion");
                                $xmlObject->text($notice['modificatorio']['folio']);
                            $xmlObject->endElement(); # folio_modificacion

                            $xmlObject->startElement("descripcion_modificacion");
                                $xmlObject->text($notice['modificatorio']['descripcion']);
                            $xmlObject->endElement(); # descripcion_modificacion

                            $xmlObject->startElement("prioridad");
                                $xmlObject->text($notice['modificatorio']['prioridad']);
                            $xmlObject->endElement(); # prioridad

                        $xmlObject->endElement(); # modificatorio
                    }

                    $xmlObject->startElement("alerta");
                        $xmlObject->startElement("tipo_alerta");
                            $xmlObject->text("100");
                        $xmlObject->endElement(); // tipo_alerta
                    $xmlObject->endElement(); // alerta

                    $xmlObject->startElement("persona_aviso");
                    $xmlObject->startElement("tipo_persona");

                    # Validamos si es persona Fisica, Moral o Fideicomiso
                    # Persona Fisica
                    if(strlen($notice['identificacionPersonaObjetoAviso']['personaFisica']['nombre']) > 0 )
                    {
                        $xmlObject->startElement("persona_fisica");
                        $xmlObject->startElement("nombre");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['nombre']);
                        $xmlObject->endElement(); # Elemento nombre

                        $xmlObject->startElement("apellido_paterno");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['apellidoPaterno']);
                        $xmlObject->endElement(); # Elemento apellido_paterno

                        $xmlObject->startElement("apellido_materno");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['apellidoMaterno']);
                        $xmlObject->endElement(); # Elemento apellido_materno

                        $xmlObject->startElement("fecha_nacimiento");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['fechaNacimiento']);
                        $xmlObject->endElement(); # Elemento fecha_nacimiento

                        if(strlen($notice['identificacionPersonaObjetoAviso']['personaFisica']['rfc']) > 0){
                            $xmlObject->startElement("rfc");
                            $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['rfc']);
                            $xmlObject->endElement(); # Elemento rfc
                        }

                        if(strlen($notice['identificacionPersonaObjetoAviso']['personaFisica']['curp']) > 0){
                            $xmlObject->startElement("curp");
                            $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['curp']);
                            $xmlObject->endElement(); # Elemento curp
                        }

                        $xmlObject->startElement("pais_nacionalidad");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['nacionalidad']);
                        $xmlObject->endElement(); # Elemento pais_nacionalidad

                        $xmlObject->startElement("actividad_economica");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaFisica']['actividadEconomica']);
                        $xmlObject->endElement(); # Elemento actividad_economica

                        $xmlObject->endElement(); # Elemento persona_fisica
                    }
                    # Persona Moral
                    elseif(strlen($notice['identificacionPersonaObjetoAviso']['personaMoral']['nombre']) > 0){
                        $xmlObject->startElement("persona_moral");
                        $xmlObject->startElement("denominacion_razon");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['denominacion_razon']);
                        $xmlObject->endElement(); # Elemento denominacion_razon

                        $xmlObject->startElement("fecha_constitucion");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['apellidoPaterno']);
                        $xmlObject->endElement(); # Elemento fecha_constitucion

                        $xmlObject->startElement("rfc");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['apellidoMaterno']);
                        $xmlObject->endElement(); # Elemento rfc

                        $xmlObject->startElement("pais_nacionalidad");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['fechaNacimiento']);
                        $xmlObject->endElement(); # Elemento pais_nacionalidad

                        $xmlObject->startElement("giro_mercantil");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['giro_mercantil']);
                        $xmlObject->endElement(); # Elemento giro_mercantil

                        $xmlObject->endElement(); # Elemento persona_moral

                        $xmlObject->startElement("representante_apoderado");
                        $xmlObject->startElement("nombre");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['nombre']);
                        $xmlObject->endElement(); # Elemento nombre

                        $xmlObject->startElement("apellido_paterno");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['apellidoPaterno']);
                        $xmlObject->endElement(); # Elemento apellido_paterno

                        $xmlObject->startElement("apellido_materno");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['apellidoMaterno']);
                        $xmlObject->endElement(); # Elemento apellido_materno

                        $xmlObject->startElement("fecha_nacimiento");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['fechaNacimiento']);
                        $xmlObject->endElement(); # Elemento fecha_nacimiento

                        if(strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['rfc']) > 0){
                            $xmlObject->startElement("rfc");
                            $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['rfc']);
                            $xmlObject->endElement(); # Elemento rfc
                        }

                        if(strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['curp']) > 0){
                            $xmlObject->startElement("curp");
                            $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['curp']);
                            $xmlObject->endElement(); # Elemento curp
                        }

                        $xmlObject->endElement(); # Elemento representante_apoderado
                    }
                    # Fideicomiso
                    else{
                        $xmlObject->startElement("fideicomiso");
                        $xmlObject->startElement("denominacion_razon");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['denominacion_razon']);
                        $xmlObject->endElement(); # Elemento denominacion_razon

                        $xmlObject->startElement("rfc");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['apellidoPaterno']);
                        $xmlObject->endElement(); # Elemento rfc

                        $xmlObject->startElement("identificador_fideicomiso");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['personaMoral']['apellidoMaterno']);
                        $xmlObject->endElement(); # Elemento identificador_fideicomiso

                        $xmlObject->endElement(); # Elemento fideicomiso

                        $xmlObject->startElement("representante_apoderado");
                        $xmlObject->startElement("nombre");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['nombre']);
                        $xmlObject->endElement(); # Elemento nombre

                        $xmlObject->startElement("apellido_paterno");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['apellidoPaterno']);
                        $xmlObject->endElement(); # Elemento apellido_paterno

                        $xmlObject->startElement("apellido_materno");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['apellidoMaterno']);
                        $xmlObject->endElement(); # Elemento apellido_materno

                        $xmlObject->startElement("fecha_nacimiento");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['fechaNacimiento']);
                        $xmlObject->endElement(); # Elemento fecha_nacimiento

                        if(strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['rfc']) > 0){
                            $xmlObject->startElement("rfc");
                            $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['rfc']);
                            $xmlObject->endElement(); # Elemento rfc
                        }

                        if(strlen($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['curp']) > 0){
                            $xmlObject->startElement("curp");
                            $xmlObject->text($notice['identificacionPersonaObjetoAviso']['datosRepresentantes']['curp']);
                            $xmlObject->endElement(); # Elemento curp
                        }

                        $xmlObject->endElement(); # Elemento representante_apoderado
                    }

                    $xmlObject->endElement(); # Elemento tipo_persona

                    # Tipo domicilio
                    $xmlObject->startElement("tipo_domicilio");

                    # Validamos si es domicilio nacional o extranjero
                    if(strlen($notice['identificacionPersonaObjetoAviso']['domicilioNacional']['cp']) > 0){
                        $xmlObject->startElement("nacional");

                        $xmlObject->startElement("colonia");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioNacional']['colonia']);
                        $xmlObject->endElement(); # Elemento colonia

                        $xmlObject->startElement("calle");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioNacional']['calle']);
                        $xmlObject->endElement(); # Elemento calle

                        $xmlObject->startElement("numero_exterior");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioNacional']['numeroExterior']);
                        $xmlObject->endElement(); # Elemento numero_exterior

                        if(strlen($notice['identificacionPersonaObjetoAviso']['domicilioNacional']['numeroInterior']) > 0){
                            $xmlObject->startElement("numero_interior");
                            $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioNacional']['numeroInterior']);
                            $xmlObject->endElement(); # Elemento numero_interior
                        }

                        $xmlObject->startElement("codigo_postal");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioNacional']['cp']);
                        $xmlObject->endElement(); # Elemento codigo_postal

                        $xmlObject->endElement(); # Elemento nacional
                    }
                    else{
                        $xmlObject->startElement("extranjero");
                        $xmlObject->startElement("pais");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioextranjero']['pais']);
                        $xmlObject->endElement(); # Elemento pais

                        $xmlObject->startElement("estado_provincia");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioextranjero']['estado']);
                        $xmlObject->endElement(); # Elemento estado_provincia

                        $xmlObject->startElement("ciudad_poblacion");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioextranjero']['ciudad']);
                        $xmlObject->endElement(); # Elemento ciudad_poblacion

                        $xmlObject->startElement("colonia");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioextranjero']['colonia']);
                        $xmlObject->endElement(); # Elemento colonia

                        $xmlObject->startElement("calle");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioextranjero']['calle']);
                        $xmlObject->endElement(); # Elemento calle

                        $xmlObject->startElement("numero_exterior");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioextranjero']['numeroExterior']);
                        $xmlObject->endElement(); # Elemento numero_exterior

                        if(strlen($notice['identificacionPersonaObjetoAviso']['domicilioextranjero']['numeroInterior']) > 0){
                            $xmlObject->startElement("numero_interior");
                            $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioextranjero']['numeroInterior']);
                            $xmlObject->endElement(); # Elemento numero_interior
                        }

                        $xmlObject->startElement("codigo_postal");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['domicilioextranjero']['cp']);
                        $xmlObject->endElement(); # Elemento codigo_postal

                        $xmlObject->endElement(); # Elemento extranjero
                    }
                    $xmlObject->endElement(); # Elemento tipo_domicilio

                    # Datos del numero telefonico
                    $xmlObject->startElement("telefono");
                    $xmlObject->startElement("clave_pais");
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['contacto']['pais']);
                    $xmlObject->endElement(); # Elemento clave_pais

                    $xmlObject->startElement("numero_telefono");
                    $xmlObject->text($notice['identificacionPersonaObjetoAviso']['contacto']['telefono']);
                    $xmlObject->endElement(); # Elemento numero_telefono

                    if(strlen($notice['identificacionPersonaObjetoAviso']['contacto']['correo'] > 0)){
                        $xmlObject->startElement("correo_electronico");
                        $xmlObject->text($notice['identificacionPersonaObjetoAviso']['contacto']['correo']);
                        $xmlObject->endElement(); # Elemento correo_electronico
                    }

                    $xmlObject->endElement(); # Elemento telefono

                    $xmlObject->endElement(); # Elemento persona_aviso

                    # Datos del dueño o beneficiario
                    # Validamos si hay datos del dueño o beneficiario
                    if(strlen($notice['identificacionPersonaBeneficiaria']['personaFisica']['nombre']) > 0 ||
                        strlen($notice['identificacionPersonaBeneficiaria']['personaMoral']['nombre']) > 0 ||
                        strlen($notice['identificacionPersonaBeneficiaria']['fideicomiso']['denominacion']) > 0){

                        $xmlObject->startElement("dueno_beneficiario");

                        # Persona Fisica
                        if(strlen($notice['identificacionPersonaBeneficiaria']['personaFisica']['nombre']) > 0 )
                        {
                            $xmlObject->startElement("persona_fisica");
                            $xmlObject->startElement("nombre");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaFisica']['nombre']);
                            $xmlObject->endElement(); # Elemento nombre

                            $xmlObject->startElement("apellido_paterno");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaFisica']['apellidoPaterno']);
                            $xmlObject->endElement(); # Elemento apellido_paterno

                            $xmlObject->startElement("apellido_materno");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaFisica']['apellidoMaterno']);
                            $xmlObject->endElement(); # Elemento apellido_materno

                            $xmlObject->startElement("fecha_nacimiento");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaFisica']['fechaNacimiento']);
                            $xmlObject->endElement(); # Elemento fecha_nacimiento

                            if(strlen($notice['identificacionPersonaBeneficiaria']['personaFisica']['rfc']) > 0){
                                $xmlObject->startElement("rfc");
                                $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaFisica']['rfc']);
                                $xmlObject->endElement(); # Elemento rfc
                            }

                            if(strlen($notice['identificacionPersonaBeneficiaria']['personaFisica']['curp']) > 0){
                                $xmlObject->startElement("curp");
                                $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaFisica']['curp']);
                                $xmlObject->endElement(); # Elemento curp
                            }

                            $xmlObject->startElement("pais_nacionalidad");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaFisica']['nacionalidad']);
                            $xmlObject->endElement(); # Elemento pais_nacionalidad



                            $xmlObject->endElement(); # Elemento persona_fisica
                        }
                        # Persona Moral
                        elseif(strlen($notice['identificacionPersonaBeneficiaria']['personaMoral']['nombre']) > 0){
                            $xmlObject->startElement("persona_moral");
                            $xmlObject->startElement("denominacion_razon");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaMoral']['denominacion_razon']);
                            $xmlObject->endElement(); # Elemento denominacion_razon

                            $xmlObject->startElement("fecha_constitucion");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaMoral']['apellidoPaterno']);
                            $xmlObject->endElement(); # Elemento fecha_constitucion

                            $xmlObject->startElement("rfc");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaMoral']['apellidoMaterno']);
                            $xmlObject->endElement(); # Elemento rfc

                            $xmlObject->startElement("pais_nacionalidad");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaMoral']['fechaNacimiento']);
                            $xmlObject->endElement(); # Elemento pais_nacionalidad



                            $xmlObject->endElement(); # Elemento persona_moral
                        }
                        # Fideicomiso
                        else{
                            $xmlObject->startElement("fideicomiso");
                            $xmlObject->startElement("denominacion_razon");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaMoral']['denominacion_razon']);
                            $xmlObject->endElement(); # Elemento denominacion_razon

                            $xmlObject->startElement("rfc");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaMoral']['apellidoPaterno']);
                            $xmlObject->endElement(); # Elemento rfc

                            $xmlObject->startElement("identificador_fideicomiso");
                            $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaMoral']['apellidoMaterno']);
                            $xmlObject->endElement(); # Elemento identificador_fideicomiso

                            $xmlObject->endElement(); # Elemento fideicomiso
                        }

                        $xmlObject->endElement(); # Elemento dueno_beneficiario
                    }

                    $xmlObject->startElement("detalle_operacion");
                    $xmlObject->startElement("datos_operacion");

                    $xmlObject->startElement("fecha_operacion");
                    $xmlObject->text($notice['datosOperacion']['operacion']['fechaOperacion']);
                    $xmlObject->endElement(); # Elemento fecha operacion

                    $xmlObject->startElement("codigo_postal");
                    $xmlObject->text($notice['datosOperacion']['operacion']['cp']);
                    $xmlObject->endElement(); # Elemento codigo_postal

                    $xmlObject->startElement("tipo_operacion");
                    $xmlObject->text($notice['datosOperacion']['operacion']['tipoOperacion']);
                    $xmlObject->endElement(); # Elemento tipo_operacion

                    $xmlObject->startElement("tipo_vehiculo");

                    $e_tipoVehiculo = strtolower($notice['datosOperacion']['vehiculo']['tipoVehiculo']);
                    $xmlObject->startElement("datos_vehiculo_{$e_tipoVehiculo}");

                    $xmlObject->startElement("marca_fabricante");
                    $xmlObject->text($notice['datosOperacion']['vehiculo']['marcaFabricante']);
                    $xmlObject->endElement(); # Elemento marca_fabricante

                    $xmlObject->startElement("modelo");
                    $xmlObject->text($notice['datosOperacion']['vehiculo']['modelo']);
                    $xmlObject->endElement(); # Elemento modelo

                    $xmlObject->startElement("anio");
                    $xmlObject->text($notice['datosOperacion']['vehiculo']['anio']);
                    $xmlObject->endElement(); # Elemento anio

                    if($e_tipoVehiculo == "terreste"){
                        $e_numeroSerieVim = "vin";
                        $e_repuveBandera  = "repube";
                        $e_placaMatricula = "placa";
                    }
                    else{
                        $e_numeroSerieVim = "numero_serie";
                        $e_repuveBandera  = "bandera";
                        $e_placaMatricula = "matricula";
                    }

                    $xmlObject->startElement("{$e_numeroSerieVim}");
                    $xmlObject->text($notice['datosOperacion']['vehiculo']['vinSerie']);
                    $xmlObject->endElement(); # Elemento numeroSerieVin

                    $xmlObject->startElement("{$e_repuveBandera}");

                    if($e_tipoVehiculo == "terreste")
                        $xmlObject->text($notice['datosOperacion']['vehiculo']['repuve']);
                    else
                        $xmlObject->text($notice['datosOperacion']['vehiculo']['bandera']);

                    $xmlObject->endElement(); # Elemento repuveBandera

                    $xmlObject->startElement("{$e_placaMatricula}");
                    $xmlObject->text($notice['datosOperacion']['vehiculo']['placasMatricula']);
                    $xmlObject->endElement(); # Elemento placaMatricula

                    $xmlObject->startElement("nivel_blindaje");
                    $xmlObject->text($notice['datosOperacion']['vehiculo']['nivelBlindaje']);
                    $xmlObject->endElement(); # Elemento nivel_blindaje

                    $xmlObject->endElement(); # Elemento dato_vehiculo

                    $xmlObject->endElement(); # Elemento tipo_vehiculo

                    #Datos de liquidacion
                    $xmlObject->startElement("datos_liquidacion");

                    $xmlObject->startElement("fecha_pago");
                    $xmlObject->text($notice['datosOperacion']['liquidacion']['fechaPago']);
                    $xmlObject->endElement(); # Elemento fecha_pago

                    $xmlObject->startElement("forma_pago");
                    $xmlObject->text($notice['datosOperacion']['liquidacion']['formaPago']);
                    $xmlObject->endElement(); # Elemento forma_pago

                    $xmlObject->startElement("instrumento_monetario");
                    $xmlObject->text($notice['datosOperacion']['liquidacion']['instrumentoMonetario']);
                    $xmlObject->endElement(); # Elemento instrumento_monetario

                    $xmlObject->startElement("moneda");
                    $xmlObject->text($notice['datosOperacion']['liquidacion']['monedaDivisa']);
                    $xmlObject->endElement(); # Elemento moneda

                    $xmlObject->startElement("monto_operacion");
                    $xmlObject->text(number_format($notice['datosOperacion']['liquidacion']['montoOperacion'],2,'.',''));
                    $xmlObject->endElement(); # Elemento monto_operacion

                    $xmlObject->endElement(); # Elemento datos_liquidacion

                    $xmlObject->endElement(); # Elemento datos_operacion
                    $xmlObject->endElement(); # Elemento detalle_operacion


                    $xmlObject->endElement(); # Elemento aviso
                }

            $xmlObject->endElement(); // Informe
        $xmlObject->endElement(); // archivo

        $xmlObject->endDocument();

        return $xmlObject->outputMemory();
    }
}
