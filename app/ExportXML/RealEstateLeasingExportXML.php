<?php

namespace App\ExportXML;
use Illuminate\Support\Carbon;

class RealEstateLeasingExportXML
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
        $fileName = "ArrendamientoInmuebles" . Carbon::now() .".xml";

        //make xml object
        $xmlObject = new XMLWriter();
        $xmlObject->openURI($fileName);
        $xmlObject->setIndent(true);
        $xmlObject->setIndentString("\t");

        //Add headers
        $xmlObject->startDocument('1.0', 'utf-8');
        $xmlObject->startElement("archivo");
        $xmlObject->writeAttribute("xsi:schemaLocation","http://www.uif.shcp.gob.mx/recepcion/ari ari.xsd");
        $xmlObject->writeAttribute("xmlns","http://www.uif.shcp.gob.mx/recepcion/ari");
        $xmlObject->writeAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");


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
        $xmlObject->text("ARI");
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

            if(strlen($notice['modification']['folio']) > 0){
                $xmlObject->startElement("modificatorio");
                $xmlObject->startElement("folio_modificacion");
                $xmlObject->text($notice['modification']['folio']);
                $xmlObject->endElement(); # folio_modificacion

                $xmlObject->startElement("descripcion_modificacion");
                $xmlObject->text($notice['modification']['description']);
                $xmlObject->endElement(); # descripcion_modificacion

                $xmlObject->startElement("prioridad");
                $xmlObject->text($notice['modification']['priority']);
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
            if(strlen($notice['identificationDataPersonSubjectNotice']['physicalPerson']['nombre']) > 0 )
            {
                $xmlObject->startElement("persona_fisica");
                $xmlObject->startElement("nombre");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['nombre']);
                $xmlObject->endElement(); // nombre

                $xmlObject->startElement("apellido_paterno");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['apellidoPaterno']);
                $xmlObject->endElement(); // apellido_paterno

                $xmlObject->startElement("apellido_materno");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['apellidoMaterno']);
                $xmlObject->endElement(); // apellido_materno

                $xmlObject->startElement("fecha_nacimiento");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['fechaNacimiento']);
                $xmlObject->endElement(); // fecha_nacimiento

                if(strlen($notice['identificationDataPersonSubjectNotice']['physicalPerson']['rfc']) > 0){
                    $xmlObject->startElement("rfc");
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['rfc']);
                    $xmlObject->endElement(); // rfc
                }

                if(strlen($notice['identificationDataPersonSubjectNotice']['physicalPerson']['curp']) > 0){
                    $xmlObject->startElement("curp");
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['curp']);
                    $xmlObject->endElement(); // curp
                }

                $xmlObject->startElement("pais_nacionalidad");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['nacionalidad']);
                $xmlObject->endElement(); // pais_nacionalidad

                $xmlObject->startElement("actividad_economica");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['physicalPerson']['actividadEconomica']);
                $xmlObject->endElement(); // actividad_economica

                $xmlObject->endElement(); // persona_fisica
            }
            # Persona Moral
            elseif(strlen($notice['identificationDataPersonSubjectNotice']['personaMoral']['razonSocial']) > 0){
                $xmlObject->startElement("persona_moral");
                $xmlObject->startElement("denominacion_razon");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['personaMoral']['razonSocial']);
                $xmlObject->endElement(); // denominacion_razon

                $xmlObject->startElement("fecha_constitucion");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['personaMoral']['fechaConstitucion']);
                $xmlObject->endElement(); // fecha_constitucion

                $xmlObject->startElement("rfc");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['personaMoral']['rfc']);
                $xmlObject->endElement(); // rfc

                $xmlObject->startElement("pais_nacionalidad");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['personaMoral']['nacionalidad']);
                $xmlObject->endElement(); // pais_nacionalidad

                $xmlObject->startElement("giro_mercantil");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['personaMoral']['giroMercantil']);
                $xmlObject->endElement(); // giro_mercantil

                $xmlObject->startElement("representante_apoderado");
                $xmlObject->startElement("nombre");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['nombre']);
                $xmlObject->endElement(); // nombre

                $xmlObject->startElement("apellido_paterno");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['apellidoPaterno']);
                $xmlObject->endElement(); // apellido_paterno

                $xmlObject->startElement("apellido_materno");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['apellidoMaterno']);
                $xmlObject->endElement(); // apellido_materno

                $xmlObject->startElement("fecha_nacimiento");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['fechaNacimiento']);
                $xmlObject->endElement(); // fecha_nacimiento

                if(strlen($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['rfc']) > 0){
                    $xmlObject->startElement("rfc");
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['rfc']);
                    $xmlObject->endElement(); // rfc
                }

                if(strlen($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['curp']) > 0){
                    $xmlObject->startElement("curp");
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['curp']);
                    $xmlObject->endElement(); // curp
                }
                $xmlObject->endElement(); // representante_apoderado

                $xmlObject->endElement(); // persona_moral
            }
            # Fideicomiso
            else{
                $xmlObject->startElement("fideicomiso");
                $xmlObject->startElement("denominacion_razon");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['fideicomiso']['denominacion']);
                $xmlObject->endElement(); // denominacion_razon

                $xmlObject->startElement("rfc");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['fideicomiso']['rfc']);
                $xmlObject->endElement(); // rfc

                $xmlObject->startElement("identificador_fideicomiso");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['fideicomiso']['identificador']);
                $xmlObject->endElement(); // identificador_fideicomiso

                $xmlObject->endElement(); // fideicomiso

                $xmlObject->startElement("representante_apoderado");
                $xmlObject->startElement("nombre");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['nombre']);
                $xmlObject->endElement(); // nombre

                $xmlObject->startElement("apellido_paterno");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['apellidoPaterno']);
                $xmlObject->endElement(); // apellido_paterno

                $xmlObject->startElement("apellido_materno");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['apellidoMaterno']);
                $xmlObject->endElement(); // apellido_materno

                $xmlObject->startElement("fecha_nacimiento");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['fechaNacimiento']);
                $xmlObject->endElement(); // fecha_nacimiento

                if(strlen($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['rfc']) > 0){
                    $xmlObject->startElement("rfc");
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['rfc']);
                    $xmlObject->endElement(); // rfc
                }

                if(strlen($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['curp']) > 0){
                    $xmlObject->startElement("curp");
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['datosRepresentantes']['curp']);
                    $xmlObject->endElement(); // curp
                }

                $xmlObject->endElement(); // representante_apoderado
            }

            $xmlObject->endElement(); // tipo_persona

            # Tipo domicilio
            $xmlObject->startElement("tipo_domicilio");

            # Validamos si es domicilio nacional o extranjero
            if(strlen($notice['identificationDataPersonSubjectNotice']['domicilioNacional']['cp']) > 0){
                $xmlObject->startElement("nacional");

                $xmlObject->startElement("colonia");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioNacional']['colonia']);
                $xmlObject->endElement(); // colonia

                $xmlObject->startElement("calle");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioNacional']['calle']);
                $xmlObject->endElement(); // calle

                $xmlObject->startElement("numero_exterior");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioNacional']['numeroExterior']);
                $xmlObject->endElement(); // numero_exterior

                if(strlen($notice['identificationDataPersonSubjectNotice']['domicilioNacional']['numeroInterior']) > 0){
                    $xmlObject->startElement("numero_interior");
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioNacional']['numeroInterior']);
                    $xmlObject->endElement(); // numero_interior
                }

                $xmlObject->startElement("codigo_postal");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioNacional']['cp']);
                $xmlObject->endElement(); // codigo_postal

                $xmlObject->endElement(); // nacional
            }
            else{
                $xmlObject->startElement("extranjero");
                $xmlObject->startElement("pais");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioExtranjero']['pais']);
                $xmlObject->endElement(); // pais

                $xmlObject->startElement("estado_provincia");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioextranjero']['estado']);
                $xmlObject->endElement(); // estado_provincia

                $xmlObject->startElement("ciudad_poblacion");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioextranjero']['municipio']);
                $xmlObject->endElement(); // ciudad_poblacion

                $xmlObject->startElement("colonia");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioextranjero']['colonia']);
                $xmlObject->endElement(); // colonia

                $xmlObject->startElement("calle");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioextranjero']['calle']);
                $xmlObject->endElement(); // calle


                $xmlObject->startElement("numero_exterior");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioextranjero']['numeroExterior']);
                $xmlObject->endElement(); // numero_exterior

                if(strlen($notice['identificationDataPersonSubjectNotice']['domicilioextranjero']['numeroInterior']) > 0){
                    $xmlObject->startElement("numero_interior");
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioextranjero']['numeroInterior']);
                    $xmlObject->endElement(); // numero_interior
                }

                $xmlObject->startElement("codigo_postal");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['domicilioextranjero']['cp']);
                $xmlObject->endElement(); // codigo_postal

                $xmlObject->endElement(); // extranjero
            }
            $xmlObject->endElement(); // tipo_domicilio

            # Datos del numero telefonico
            if (strlen($notice['identificationDataPersonSubjectNotice']['physicalPerson']['nombre']) > 0
                || strlen($notice['identificationDataPersonSubjectNotice']['personaMoral']['razonSocial']) > 0) {
                $xmlObject->startElement("telefono");
                $xmlObject->startElement("clave_pais");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['contacto']['pais']);
                $xmlObject->endElement(); // clave_pais

                $xmlObject->startElement("numero_telefono");
                $xmlObject->text($notice['identificationDataPersonSubjectNotice']['contacto']['telefono']);
                $xmlObject->endElement(); // numero_telefono

                if(strlen($notice['identificationDataPersonSubjectNotice']['contacto']['correo']) > 0){
                    $xmlObject->startElement("correo_electronico");
                    $xmlObject->text($notice['identificationDataPersonSubjectNotice']['contacto']['correo']);
                    $xmlObject->endElement(); // correo_electronico
                }
                $xmlObject->endElement(); // telefono
            }


            $xmlObject->endElement(); // persona_aviso

            # Datos del dueño o beneficiario
            # Validamos si hay datos del dueño o beneficiario
            if(strlen($notice['identificacionPersonaBeneficiaria']['physicalPerson']['nombre']) > 0 ||
                strlen($notice['identificacionPersonaBeneficiaria']['personaMoral']['razonSocial']) > 0 ||
                strlen($notice['identificacionPersonaBeneficiaria']['fideicomiso']['denominacion']) > 0){

                $xmlObject->startElement("dueno_beneficiario");

                # Persona Fisica
                if(strlen($notice['identificacionPersonaBeneficiaria']['physicalPerson']['nombre']) > 0 )
                {
                    $xmlObject->startElement("persona_fisica");
                    $xmlObject->startElement("nombre");
                    $xmlObject->text($notice['identificacionPersonaBeneficiaria']['physicalPerson']['nombre']);
                    $xmlObject->endElement(); // nombre

                    $xmlObject->startElement("apellido_paterno");
                    $xmlObject->text($notice['identificacionPersonaBeneficiaria']['physicalPerson']['apellidoPaterno']);
                    $xmlObject->endElement(); // apellido_paterno

                    $xmlObject->startElement("apellido_materno");
                    $xmlObject->text($notice['identificacionPersonaBeneficiaria']['physicalPerson']['apellidoMaterno']);
                    $xmlObject->endElement(); // apellido_materno

                    $xmlObject->startElement("fecha_nacimiento");
                    $xmlObject->text($notice['identificacionPersonaBeneficiaria']['physicalPerson']['fechaNacimiento']);
                    $xmlObject->endElement(); // fecha_nacimiento

                    if(strlen($notice['identificacionPersonaBeneficiaria']['physicalPerson']['rfc']) > 0){
                        $xmlObject->startElement("rfc");
                        $xmlObject->text($notice['identificacionPersonaBeneficiaria']['physicalPerson']['rfc']);
                        $xmlObject->endElement(); // rfc
                    }

                    if(strlen($notice['identificacionPersonaBeneficiaria']['physicalPerson']['curp']) > 0){
                        $xmlObject->startElement("curp");
                        $xmlObject->text($notice['identificacionPersonaBeneficiaria']['physicalPerson']['curp']);
                        $xmlObject->endElement(); // curp
                    }

                    if(strlen($notice['identificacionPersonaBeneficiaria']['physicalPerson']['nacionalidad'] > 0)){
                        $xmlObject->startElement("pais_nacionalidad");
                        $xmlObject->text($notice['identificacionPersonaBeneficiaria']['physicalPerson']['nacionalidad']);
                        $xmlObject->endElement(); // pais_nacionalidad
                    }

                    $xmlObject->endElement(); // persona_fisica
                }
                # Persona Moral
                elseif(strlen($notice['identificacionPersonaBeneficiaria']['personaMoral']['razonSocial']) > 0){
                    $xmlObject->startElement("persona_moral");
                    $xmlObject->startElement("denominacion_razon");
                    $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaMoral']['razonSocial']);
                    $xmlObject->endElement(); // denominacion_razon

                    $xmlObject->startElement("fecha_constitucion");
                    $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaMoral']['fechaConstitucion']);
                    $xmlObject->endElement(); // fecha_constitucion

                    $xmlObject->startElement("rfc");
                    $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaMoral']['rfc']);
                    $xmlObject->endElement(); // rfc

                    $xmlObject->startElement("pais_nacionalidad");
                    $xmlObject->text($notice['identificacionPersonaBeneficiaria']['personaMoral']['nacionalidad']);
                    $xmlObject->endElement(); // pais_nacionalidad

                    $xmlObject->endElement(); // persona_moral
                }
                # Fideicomiso
                else{
                    $xmlObject->startElement("fideicomiso");
                    $xmlObject->startElement("denominacion_razon");
                    $xmlObject->text($notice['identificacionPersonaBeneficiaria']['fideicomiso']['denominacion']);
                    $xmlObject->endElement(); // denominacion_razon

                    $xmlObject->startElement("rfc");
                    $xmlObject->text($notice['identificacionPersonaBeneficiaria']['fideicomiso']['rfc']);
                    $xmlObject->endElement(); // rfc

                    $xmlObject->startElement("identificador_fideicomiso");
                    $xmlObject->text($notice['identificacionPersonaBeneficiaria']['fideicomiso']['identificador']);
                    $xmlObject->endElement(); // identificador_fideicomiso

                    $xmlObject->endElement(); // fideicomiso
                }

                $xmlObject->endElement(); // dueno_beneficiario
            }

            $xmlObject->startElement("detalle_operaciones");
            $xmlObject->startElement("datos_operacion");

            $xmlObject->startElement("fecha_operacion");
            $xmlObject->text($notice['detallesOperacion']['datosOperacion']['fechaOperacion']);
            $xmlObject->endElement(); // fecha operacion

            $xmlObject->startElement("tipo_operacion");
            $xmlObject->text($notice['detallesOperacion']['datosOperacion']['tipoOperacion']);
            $xmlObject->endElement(); // tipo_operacion

            #Caracteristicas de arrendamiento
            $xmlObject->startElement("caracteristicas");

            $xmlObject->startElement("fecha_inicio");
            $xmlObject->text($notice['detallesOperacion']['caracteristicasArrendamiento']['fechaInicio']);
            $xmlObject->endElement(); // fecha_inicio

            $xmlObject->startElement("fecha_termino");
            $xmlObject->text($notice['detallesOperacion']['caracteristicasArrendamiento']['fechaTermino']);
            $xmlObject->endElement(); // fecha_termino

            $xmlObject->startElement("tipo_inmueble");
            $xmlObject->text($notice['detallesOperacion']['caracteristicasArrendamiento']['tipoInmueble']);
            $xmlObject->endElement(); // tipo_inmueble

            $xmlObject->startElement("valor_referencia");
            $xmlObject->text(number_format($notice['detallesOperacion']['caracteristicasArrendamiento']['valorReferencia'],2,'.',''));
            $xmlObject->endElement(); // valor_referencia

            $xmlObject->startElement("colonia");
            $xmlObject->text($notice['detallesOperacion']['caracteristicasArrendamiento']['colonia']);
            $xmlObject->endElement(); // colonia

            $xmlObject->startElement("calle");
            $xmlObject->text($notice['detallesOperacion']['caracteristicasArrendamiento']['calle']);
            $xmlObject->endElement(); // calle

            $xmlObject->startElement("numero_exterior");
            $xmlObject->text($notice['detallesOperacion']['caracteristicasArrendamiento']['numeroExterior']);
            $xmlObject->endElement(); // numero_exterior

            if(strlen($notice['detallesOperacion']['caracteristicasArrendamiento']['numeroInterior'])> 0){
                $xmlObject->startElement("numero_interior");
                $xmlObject->text($notice['detallesOperacion']['caracteristicasArrendamiento']['numeroInterior']);
                $xmlObject->endElement(); // numero_interior
            }

            $xmlObject->startElement("codigo_postal");
            $xmlObject->text($notice['detallesOperacion']['caracteristicasArrendamiento']['cp']);
            $xmlObject->endElement(); // codigo_postal

            $xmlObject->startElement("folio_real");
            $xmlObject->text($notice['detallesOperacion']['caracteristicasArrendamiento']['folioReal']);
            $xmlObject->endElement(); // folio_real

            $xmlObject->endElement(); // caracteristicas

            #Datos de liquidacion
            foreach ($notice['detallesOperacion']['liquidacion'] as $liquidacion) {
                $xmlObject->startElement("datos_liquidacion");

                $xmlObject->startElement("fecha_pago");
                $xmlObject->text($liquidacion['fechaPago']);
                $xmlObject->endElement(); // fecha_pago

                $xmlObject->startElement("forma_pago");
                $xmlObject->text($liquidacion['formaPago']);
                $xmlObject->endElement(); // forma_pago

                $xmlObject->startElement("instrumento_monetario");
                $xmlObject->text($liquidacion['instrumentoMonetario']);
                $xmlObject->endElement(); // instrumento_monetario

                $xmlObject->startElement("moneda");
                $xmlObject->text($liquidacion['moneda']);
                $xmlObject->endElement(); // moneda

                $xmlObject->startElement("monto_operacion");
                $xmlObject->text(number_format($liquidacion['montoOperacion'],2,'.',''));
                $xmlObject->endElement(); // monto_operacion

                $xmlObject->endElement(); // datos_liquidacion
            }

            $xmlObject->endElement(); // datos_operacion
            $xmlObject->endElement(); // detalle_operacion

            $xmlObject->endElement(); // aviso
        }

        $xmlObject->endElement(); // Informe
        $xmlObject->endElement(); // archivo

        $xmlObject->endDocument();
        return $fileName;

    }
}
