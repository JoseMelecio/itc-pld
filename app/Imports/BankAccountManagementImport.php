<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BankAccountManagementImport implements ToCollection, WithMultipleSheets
{
    private array $data = [];

    public function collection(Collection $collection): void
    {
        foreach ($collection->skip(3) as $row) {

            // Modificatorio
            $m_modificatorio = [
                'folioModificatorio' => trim(strtoupper($row[0])),
                'descripcionModificatorio' => trim(strtoupper($row[1])),
                'prioridadAviso' => trim(strtoupper($row[2])),
            ];

            if (strlen($m_modificatorio['prioridadAviso']) > 0) {
                $tempPrioridad = explode(',', $m_modificatorio['prioridadAviso']);
                $m_modificatorio['prioridadAviso'] = trim($tempPrioridad[0]);
            }

            $m_datos['modificatorio'] = $m_modificatorio;

            // Alerta
            $m_alerta = [
                'tipoAlerta' => trim(strtoupper($row[3])),
                'descripcionAlerta' => trim(strtoupper($row[4])),
            ];

            $tipoAlerta = explode(',', $m_alerta['tipoAlerta']);
            $m_alerta['tipoAlerta'] = $tipoAlerta[0];

            $m_datos['alerta'] = $m_alerta;

            //IDENTIFICACION DE LA PERSONA OFICIO
            //Persona fisica
            $m_personaFisica = [
                'nombre' => trim(strtoupper($row[5])),
                'apellidoPaterno' => trim(strtoupper($row[6])),
                'apellidoMaterno' => trim(strtoupper($row[7])),
                'fechaNacimiento' => trim(strtoupper($row[8])),
                'rfc' => trim(strtoupper($row[9])),
                'curp' => trim(strtoupper($row[10])),
                'nacionalidad' => trim(strtoupper($row[11])),
                'actividadEconomica' => trim(strtoupper($row[12])),
            ];

            if (strlen($m_personaFisica['actividadEconomica']) > 0) {
                $tempActividad = explode(',', $m_personaFisica['actividadEconomica']);
                $m_personaFisica['actividadEconomica'] = $tempActividad[0];
            }

            if (strlen($m_personaFisica['nacionalidad']) > 0) {
                $tempNacionalidad = explode(',', $m_personaFisica['nacionalidad']);
                $m_personaFisica['nacionalidad'] = $tempNacionalidad[0];
            }

            $m_datos['identificacionPersonaObjetoAviso']['personaFisica'] = $m_personaFisica;

            //Persona moral
            $m_personaMoral = [
                'razonSocial' => trim(strtoupper($row[13])),
                'fechaConstitucion' => trim(strtoupper($row[14])),
                'rfc' => trim(strtoupper($row[15])),
                'nacionalidad' => trim(strtoupper($row[16])),
                'giroMercantil' => trim(strtoupper($row[17])),
            ];

            if (strlen($m_personaMoral['giroMercantil']) > 0) {
                $tempGiro = explode(',', $m_personaMoral['giroMercantil']);
                $m_personaMoral['giroMercantil'] = $tempGiro[0];
            }

            if (strlen($m_personaMoral['nacionalidad']) > 0) {
                $tempNacionalidad = explode(',', $m_personaMoral['nacionalidad']);
                $m_personaMoral['nacionalidad'] = $tempNacionalidad[0];
            }

            $m_datos['identificacionPersonaObjetoAviso']['personaMoral'] = $m_personaMoral;

            //Fideicomiso
            $m_fideicomiso = [
                'denominacion' => trim(strtoupper($row[18])),
                'rfc' => trim(strtoupper($row[19])),
                'identificador' => trim(strtoupper($row[20])),
            ];

            $m_datos['identificacionPersonaObjetoAviso']['fideicomiso'] = $m_fideicomiso;

            //Datos representante
            $m_representante = [
                'nombre' => trim(strtoupper($row[21])),
                'apellidoPaterno' => trim(strtoupper($row[22])),
                'apellidoMaterno' => trim(strtoupper($row[23])),
                'fechaNacimiento' => trim(strtoupper($row[24])),
                'rfc' => trim(strtoupper($row[25])),
                'curp' => trim(strtoupper($row[26])),
            ];

            $m_datos['identificacionPersonaObjetoAviso']['datosRepresentantes'] = $m_representante;

            //Domicilio Nacional de la persona objeto del aviso
            $m_domicilioNacionalPersonaAviso = [
                'colonia' => trim(strtoupper($row[27])),
                'calle' => trim(strtoupper($row[28])),
                'numeroExterior' => trim(strtoupper($row[29])),
                'numeroInterior' => trim(strtoupper($row[30])),
                'cp' => trim(strtoupper($row[31])),
            ];

            $m_datos['identificacionPersonaObjetoAviso']['domicilioNacionalPersonaAviso'] = $m_domicilioNacionalPersonaAviso;

            //Domicilio Internacional de la persona objeto del aviso
            $m_domicilioInternacionalPersonaAviso = [
                'pais' => trim(strtoupper($row[32])),
                'estado' => trim(strtoupper($row[33])),
                'ciudad' => trim(strtoupper($row[34])),
                'colonia' => trim(strtoupper($row[35])),
                'calle' => trim(strtoupper($row[36])),
                'numeroExterior' => trim(strtoupper($row[37])),
                'numeroInterior' => trim(strtoupper($row[38])),
                'cp' => trim(strtoupper($row[39])),
            ];

            if (strlen($m_domicilioInternacionalPersonaAviso['pais']) > 0) {
                $tempPais = explode(',', $m_domicilioInternacionalPersonaAviso['pais']);
                $m_domicilioInternacionalPersonaAviso['pais'] = $tempPais[0];
            }
            $m_datos['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso'] = $m_domicilioInternacionalPersonaAviso;

            //Datos del contacto
            $m_datosContactoAviso = [
                'pais' => trim(strtoupper($row[40])),
                'telefono' => trim(strtoupper($row[41])),
                'correo' => trim(strtoupper($row[42])),
            ];

            if (strlen($m_datosContactoAviso['pais']) > 0) {
                $tempPais = explode(',', $m_datosContactoAviso['pais']);
                $m_datosContactoAviso['pais'] = $tempPais[0];
            }
            $m_datos['identificacionPersonaObjetoAviso']['datosContactoAviso'] = $m_datosContactoAviso;

            //Beneficiario
            //Persona Fisica
            $m_personaFisica = [
                'nombre' => trim(strtoupper($row[43])),
                'apellidoPaterno' => trim(strtoupper($row[44])),
                'apellidoMaterno' => trim(strtoupper($row[45])),
                'fechaNacimiento' => trim(strtoupper($row[46])),
                'rfc' => trim(strtoupper($row[47])),
                'curp' => trim(strtoupper($row[48])),
                'paisNacionalidad' => trim(strtoupper($row[49])),
            ];

            if (strlen($m_personaFisica['paisNacionalidad']) > 0) {
                $temppaisNacionalidad = explode(',', $m_personaFisica['paisNacionalidad']);
                $m_personaFisica['paisNacionalidad'] = $temppaisNacionalidad[0];
            }

            $m_datos['beneficiarioControlador']['personaFisica'] = $m_personaFisica;

            //Persona moral
            $m_personaMoral = [
                'razonSocial' => trim(strtoupper($row[50])),
                'fechaConstitucion' => trim(strtoupper($row[51])),
                'rfc' => trim(strtoupper($row[52])),
                'nacionalidad' => trim(strtoupper($row[53])),
            ];

            if (strlen($m_personaMoral['nacionalidad']) > 0) {
                $tempnacionalidad = explode(',', $m_personaMoral['nacionalidad']);
                $m_personaMoral['nacionalidad'] = $tempnacionalidad[0];
            }

            $m_datos['beneficiarioControlador']['personaMoral'] = $m_personaMoral;

            //Fideicomiso
            $m_fideicomiso = [
                'denominacion' => trim(strtoupper($row[54])),
                'rfc' => trim(strtoupper($row[55])),
                'identificador' => trim(strtoupper($row[56])),
            ];

            $m_datos['beneficiarioControlador']['fideicomiso'] = $m_fideicomiso;

            //Cuenta bancaria
            $m_cuentaBancaria = [
                'numeroOperaciones' => trim(strtoupper($row[57])),
                'estatusManejo' => trim(strtoupper($row[58])),
                'tipoInstitucion' => trim(strtoupper($row[59])),
                'nombreInstitucion' => trim(strtoupper($row[60])),
                'numeroCuenta' => trim(strtoupper($row[61])),
            ];

            if (strlen($m_cuentaBancaria['estatusManejo'] > 0)) {
                $m_estatusManejo = explode(',', $m_cuentaBancaria['estatusManejo']);
                $m_cuentaBancaria['estatusManejo'] = $m_estatusManejo[0];
            }

            if (strlen($m_cuentaBancaria['tipoInstitucion'] > 0)) {
                $m_tipoInstitucion = explode(',', $m_cuentaBancaria['tipoInstitucion']);
                $m_cuentaBancaria['tipoInstitucion'] = $m_tipoInstitucion[0];
            }

            $m_datos['operacionFinanciera']['datosOperacion'] = $m_cuentaBancaria;

            //Datos de la operacion
            $m_operacionFinanciera = [
                'fechaOperacion' => trim(strtoupper($row[62])),
                'instrumentoMonetario' => trim(strtoupper($row[63])),
                'tipoActivoVirtual' => trim(strtoupper($row[64])),
                'descripcionActivoVirtual' => trim(strtoupper($row[65])),
                'cantidadActivoVirtual' => number_format((float) trim(strtoupper($row[66])), 2, '.', ''),
                'moenda' => trim(strtoupper($row[67])),
                'montoOperacion' => number_format((float) trim(strtoupper($row[68])), 2, '.', ''),
            ];

            if (strlen($m_operacionFinanciera['instrumentoMonetario'] > 0)) {
                $m_tempInmueble = explode(',', $m_operacionFinanciera['instrumentoMonetario']);
                $m_operacionFinanciera['instrumentoMonetario'] = $m_tempInmueble[0];
            }

            if (strlen($m_operacionFinanciera['tipoActivoVirtual'] > 0)) {
                $m_tempInmueble = explode(',', $m_operacionFinanciera['tipoActivoVirtual']);
                $m_operacionFinanciera['tipoActivoVirtual'] = $m_tempInmueble[0];
            }

            if (strlen($m_operacionFinanciera['moenda'] > 0)) {
                $m_tempInmueble = explode(',', $m_operacionFinanciera['moenda']);
                $m_operacionFinanciera['moenda'] = $m_tempInmueble[0];
            }

            $m_datos['operacionFinanciera']['operacionFinanciera'] = $m_operacionFinanciera;

            $this->data['items'][] = $m_datos;
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
