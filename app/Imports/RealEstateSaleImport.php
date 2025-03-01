<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RealEstateSaleImport implements ToCollection, WithMultipleSheets
{
    private array $data = [];

    public function collection(Collection $collection): void
    {
        $e_posicion = 0;
        foreach ($collection->skip(3) as $row) {
            //Modificatorio
            $m_modificatorio = [
                'folio' => trim(strtoupper($row[0])),   // A -> 0
                'descripcion' => trim(strtoupper($row[1])),   // B -> 1
                'prioridad' => trim(strtoupper($row[2])),    // C -> 2
            ];

            if (strlen($m_modificatorio['prioridad']) > 0) {
                $tempprioridad = explode(',', $m_modificatorio['prioridad']);
                $m_modificatorio['prioridad'] = trim($tempprioridad[0]);
            }

            $m_datos['modificatorio'] = $m_modificatorio;

            //IDENTIFICACION DE LA PERSONA OFICIO
            //Persona fisica
            $m_personaFisica = [
                'nombre' => trim(strtoupper($row[3])),   // D -> 3
                'apellidoPaterno' => trim(strtoupper($row[4])),   // E -> 4
                'apellidoMaterno' => trim(strtoupper($row[5])),   // F -> 5
                'fechaNacimiento' => trim(strtoupper($row[6])),   // G -> 6
                'rfc' => trim(strtoupper($row[7])),   // H -> 7
                'curp' => trim(strtoupper($row[8])),   // I -> 8
                'nacionalidad' => trim(strtoupper($row[9])),   // J -> 9
                'actividadEconomica' => trim(strtoupper($row[10])),   // K -> 10
            ];

            if (strlen($m_personaFisica['actividadEconomica']) > 0) {
                $tempActividad = explode('||', $m_personaFisica['actividadEconomica']);
                $m_personaFisica['actividadEconomica'] = $tempActividad[1];
            }

            if (strlen($m_personaFisica['nacionalidad']) > 0) {
                $tempNacionalidad = explode(',', $m_personaFisica['nacionalidad']);
                $m_personaFisica['nacionalidad'] = $tempNacionalidad[1];
            }

            $m_datos['identificacionPersonaObjetoAviso']['personaFisica'] = $m_personaFisica;

            //Persona moral
            $m_personaMoral = [
                'razonSocial' => trim(strtoupper($row[11])),   // L -> 11
                'fechaConstitucion' => trim(strtoupper($row[12])),   // M -> 12
                'rfc' => trim(strtoupper($row[13])),   // N -> 13
                'nacionalidad' => trim(strtoupper($row[14])),   // O -> 14
                'giroMercantil' => trim(strtoupper($row[15])),    // P -> 15
            ];

            if (strlen($m_personaMoral['giroMercantil']) > 0) {
                $tempGiro = explode('||', $m_personaMoral['giroMercantil']);
                $m_personaMoral['giroMercantil'] = $tempGiro[1];
            }

            if (strlen($m_personaMoral['nacionalidad']) > 0) {
                $tempNacionalidad = explode(',', $m_personaMoral['nacionalidad']);
                $m_personaMoral['nacionalidad'] = $tempNacionalidad[1];
            }

            $m_datos['identificacionPersonaObjetoAviso']['personaMoral'] = $m_personaMoral;

            //Datos representante
            $m_representante = [
                'nombre' => trim(strtoupper($row[16])),   // Q -> 16
                'apellidoPaterno' => trim(strtoupper($row[17])),   // R -> 17
                'apellidoMaterno' => trim(strtoupper($row[18])),   // S -> 18
                'fechaNacimiento' => trim(strtoupper($row[19])),   // T -> 19
                'rfc' => trim(strtoupper($row[20])),   // U -> 20
                'curp' => trim(strtoupper($row[21])),    // V -> 21
            ];

            $m_datos['identificacionPersonaObjetoAviso']['datosRepresentantesMoral'] = $m_representante;

            //Fideicomiso
            $m_fideicomiso = [
                'denominacion' => trim(strtoupper($row[22])),   // W -> 22
                'rfc' => trim(strtoupper($row[23])),   // X -> 23
                'identificador' => trim(strtoupper($row[24])),    // Y -> 24
            ];

            $m_datos['identificacionPersonaObjetoAviso']['fideicomiso'] = $m_fideicomiso;

            //Datos representante fideicomiso
            $m_representante = [
                'nombre' => trim(strtoupper($row[25])),   // Z -> 25
                'apellidoPaterno' => trim(strtoupper($row[26])),   // AA -> 26
                'apellidoMaterno' => trim(strtoupper($row[27])),   // AB -> 27
                'fechaNacimiento' => trim(strtoupper($row[28])),   // AC -> 28
                'rfc' => trim(strtoupper($row[29])),   // AD -> 29
                'curp' => trim(strtoupper($row[30])),    // AE -> 30
            ];

            $m_datos['identificacionPersonaObjetoAviso']['datosRepresentantesFideicomiso'] = $m_representante;

            //Domicilio Nacional de la persona objeto del aviso
            $m_domicilioNacionalPersonaAviso = [
                'colonia' => trim(strtoupper($row[31])),   // AF -> 31
                'calle' => trim(strtoupper($row[32])),   // AG -> 32
                'numeroExterior' => trim(strtoupper($row[33])),   // AH -> 33
                'numeroInterior' => trim(strtoupper($row[34])),   // AI -> 34
                'cp' => trim(strtoupper($row[35])),   // AJ -> 35
                'municipio' => trim(strtoupper($row[36])),    // AK -> 36
            ];

            $m_datos['identificacionPersonaObjetoAviso']['domicilioNacionalPersonaAviso'] = $m_domicilioNacionalPersonaAviso;

            //Domicilio Internacional de la persona objeto del aviso
            $m_domicilioInternacionalPersonaAviso = [
                'pais' => trim(strtoupper($row[37])),   // AL -> 37
                'estado' => trim(strtoupper($row[38])),   // AM -> 38
                'ciudad' => trim(strtoupper($row[39])),   // AN -> 39
                'colonia' => trim(strtoupper($row[40])),   // AO -> 40
                'calle' => trim(strtoupper($row[41])),   // AP -> 41
                'numeroExterior' => trim(strtoupper($row[42])),   // AQ -> 42
                'numeroInterior' => trim(strtoupper($row[43])),   // AR -> 43
                'cp' => trim(strtoupper($row[44])),    // AS -> 44
            ];

            if (strlen($m_domicilioInternacionalPersonaAviso['pais']) > 0) {
                $tempPais = explode(',', $m_domicilioInternacionalPersonaAviso['pais']);
                $m_domicilioInternacionalPersonaAviso['pais'] = $tempPais[1];
            }

            $m_datos['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso'] = $m_domicilioInternacionalPersonaAviso;

            //Datos del contacto
            $m_datosContactoAviso = [
                'pais' => trim(strtoupper($row[45])),   // AT -> 45
                'telefono' => trim(strtoupper($row[46])),   // AU -> 46
                'correo' => trim(strtoupper($row[47])),    // AV -> 47
            ];

            if (strlen($m_datosContactoAviso['pais']) > 0) {
                $tempPais = explode(',', $m_datosContactoAviso['pais']);
                $m_datosContactoAviso['pais'] = $tempPais[1];
            }

            $m_datos['identificacionPersonaObjetoAviso']['datosContactoAviso'] = $m_datosContactoAviso;

            //DATOS DEL BENEFICIARIO
            //Persona fisica
            $m_personaFisica = [
                'nombre' => trim(strtoupper($row[48])),   // AW -> 48
                'apellidoPaterno' => trim(strtoupper($row[49])),   // AX -> 49
                'apellidoMaterno' => trim(strtoupper($row[50])),   // AY -> 50
                'fechaNacimiento' => trim(strtoupper($row[51])),   // AZ -> 51
                'rfc' => trim(strtoupper($row[52])),   // BA -> 52
                'curp' => trim(strtoupper($row[53])),   // BB -> 53
                'pais' => trim(strtoupper($row[54])),    // BC -> 54
            ];

            if (strlen($m_personaFisica['pais']) > 0) {
                $temppais = explode(',', $m_personaFisica['pais']);
                $m_personaFisica['pais'] = $temppais[1];
            }

            $m_datos['beneficiarioControlador']['personaFisica'] = $m_personaFisica;

            //Persona moral
            $m_personaMoral = [
                'razonSocial' => trim(strtoupper($row[55])),   // BD -> 55
                'fechaConstitucion' => trim(strtoupper($row[56])),   // BE -> 56
                'rfc' => trim(strtoupper($row[57])),   // BF -> 57
                'nacionalidad' => trim(strtoupper($row[58])),    // BG -> 58
            ];

            if (strlen($m_personaMoral['nacionalidad']) > 0) {
                $tempGiro = explode(',', $m_personaMoral['nacionalidad']);
                $m_personaMoral['nacionalidad'] = $tempGiro[1];
            }

            $m_datos['beneficiarioControlador']['personaMoral'] = $m_personaMoral;

            //Fideicomiso
            $m_fideicomiso = [
                'denominacion' => trim(strtoupper($row[59])),   // BH -> 59
                'rfc' => trim(strtoupper($row[60])),   // BI -> 60
                'identificador' => trim(strtoupper($row[61])),    // BJ -> 61
            ];
            $m_datos['beneficiarioControlador']['fideicomiso'] = $m_fideicomiso;

            //Datos de la operacion
            $m_datosOperacion = [
                'fechaOperacion' => trim(strtoupper($row[62])),   // BK -> 63
                'figuraClienteReportado' => trim(strtoupper($row[63])),   // BL -> 63
                'figurapersonaRealizaActividad' => trim(strtoupper($row[64])),    // BM -> 64
            ];

            if (strlen($m_datosOperacion['figuraClienteReportado']) > 0) {
                $tempfiguraClienteReportado = explode(',', $m_datosOperacion['figuraClienteReportado']);
                $m_datosOperacion['figuraClienteReportado'] = $tempfiguraClienteReportado[0];
            }

            if (strlen($m_datosOperacion['figurapersonaRealizaActividad']) > 0) {
                $tempfigurapersonaRealizaActividad = explode(',', $m_datosOperacion['figurapersonaRealizaActividad']);
                $m_datosOperacion['figurapersonaRealizaActividad'] = $tempfigurapersonaRealizaActividad[0];
            }

            $m_datos['detallesOperacion']['datosOperacion'] = $m_datosOperacion;

            //DATOS DE LA CONTRAPARTE
            //Persona fisica
            $m_personaFisica = [
                'nombre' => trim(strtoupper($row[65])),   // BN -> 65
                'apellidoPaterno' => trim(strtoupper($row[66])),   // BO -> 66
                'apellidoMaterno' => trim(strtoupper($row[67])),   // BP -> 67
                'fechaNacimiento' => trim(strtoupper($row[68])),   // BQ -> 68
                'rfc' => trim(strtoupper($row[69])),   // BR -> 69
                'curp' => trim(strtoupper($row[70])),   // BS -> 70
                'pais' => trim(strtoupper($row[71])),    // BT -> 71
            ];

            if (strlen($m_personaFisica['pais']) > 0) {
                $temppais = explode(',', $m_personaFisica['pais']);
                $m_personaFisica['pais'] = $temppais[1];
            }
            $m_datos['detallesOperacion']['contraparte']['personaFisica'] = $m_personaFisica;

            //Persona moral
            $m_personaMoral = [
                'razonSocial' => trim(strtoupper($row[72])),   // BU -> 72
                'fechaConstitucion' => trim(strtoupper($row[73])),   // BV -> 73
                'rfc' => trim(strtoupper($row[74])),   // BW -> 74
                'nacionalidad' => trim(strtoupper($row[75])),    // BX -> 75
            ];

            if (strlen($m_personaMoral['nacionalidad']) > 0) {
                $tempGiro = explode(',', $m_personaMoral['nacionalidad']);
                $m_personaMoral['nacionalidad'] = $tempGiro[1];
            }
            $m_datos['detallesOperacion']['contraparte']['personaMoral'] = $m_personaMoral;

            //Fideicomiso
            $m_fideicomiso = [
                'denominacion' => trim(strtoupper($row[76])),   // BY -> 76
                'rfc' => trim(strtoupper($row[77])),   // BZ -> 77
                'identificador' => trim(strtoupper($row[78])),    // CA -> 78
            ];

            $m_datos['detallesOperacion']['contraparte']['fideicomiso'] = $m_fideicomiso;

            //CARACTERISTICAS DEL INMUEBLE OBJETO
            //Caracteristicas del Inmueble
            $m_caracteristicasInmueble = [
                'tipoInmueble' => trim(strtoupper($row[79])),   // CB -> 79
                'valorPactado' => trim(strtoupper($row[80])),   // CC -> 80
                'colonia' => trim(strtoupper($row[81])),   // CD -> 81
                'calle' => trim(strtoupper($row[82])),   // CE -> 82
                'numeroExterior' => trim(strtoupper($row[83])),   // CF -> 83
                'numeroInterior' => trim(strtoupper($row[84])),   // CG -> 84
                'cp' => trim(strtoupper($row[85])),   // CH -> 85
                'estado' => trim(strtoupper($row[86])),   // CI -> 86
                'municipio' => trim(strtoupper($row[87])),   // CJ -> 87
                'dimensionTerreno' => trim(strtoupper($row[88])),   // CK -> 88
                'dimensionInmueble' => trim(strtoupper($row[89])),   // CL -> 89
                'antecedentesRegistrales' => trim(strtoupper($row[90])),    // CM -> 90
            ];

            if (strlen($m_caracteristicasInmueble['tipoInmueble']) > 0) {
                $m_tempCaracteristicas = explode(',', $m_caracteristicasInmueble['tipoInmueble']);
                $m_caracteristicasInmueble['tipoInmueble'] = $m_tempCaracteristicas[0];
            }

            if (strlen($m_caracteristicasInmueble['estado']) > 0) {
                $m_tempCaracteristicas = explode(',', $m_caracteristicasInmueble['estado']);
                $m_caracteristicasInmueble['estado'] = $m_tempCaracteristicas[0];
            }

            $m_datos['actoOperacion']['caracteristicasInmueble'] = $m_caracteristicasInmueble;

            //Instrumento Publico
            $m_instrumentoPublico = [
                'numero' => trim(strtoupper($row[91])),   // CN -> 91
                'fecha' => trim(strtoupper($row[92])),   // CO -> 92
                'numeroNotario' => trim(strtoupper($row[93])),   // CP -> 93
                'entidadNotario' => trim(strtoupper($row[94])),   // CQ -> 94
                'avaluo' => trim(strtoupper($row[95])),    // CR -> 95
            ];

            if (strlen($m_instrumentoPublico['entidadNotario']) > 0) {
                $m_tempCaracteristicas = explode(',', $m_instrumentoPublico['entidadNotario']);
                $m_instrumentoPublico['entidadNotario'] = $m_tempCaracteristicas[0];
            }

            $m_datos['actoOperacion']['instrumentoPublico'] = $m_instrumentoPublico;

            //Instrumento Privado
            $m_contratoPrivado = [
                'fechaContrato' => trim(strtoupper($row[96])),   // CS -> 96
            ];
            $m_datos['actoOperacion']['contratoPrivado'] = $m_contratoPrivado;

            //Datos de liquidacion
            $m_datosLiquidacion = [
                'fechaPago' => trim($row[97]),   // CT -> 97
                'formaPago' => trim($row[98]),  // CU -> 98
                'instrumento' => trim($row[99]),  // CV -> 99
                'moneda' => trim($row[100]),  // CW -> 100
                'monto' => trim($row[101]),   // CX -> 101
            ];

            if (strlen($m_datosLiquidacion['formaPago'] > 0)) {
                $m_tempLiquidacion = explode(',', $m_datosLiquidacion['formaPago']);
                $m_datosLiquidacion['formaPago'] = $m_tempLiquidacion[0];
            }

            if (strlen($m_datosLiquidacion['instrumento'] > 0)) {
                $m_tempLiquidacion = explode(',', $m_datosLiquidacion['instrumento']);
                $m_datosLiquidacion['instrumento'] = $m_tempLiquidacion[0];
            }

            if (strlen($m_datosLiquidacion['moneda'] > 0)) {
                $m_tempLiquidacion = explode(',', $m_datosLiquidacion['moneda']);
                $m_datosLiquidacion['moneda'] = $m_tempLiquidacion[0];
            }

            unset($m_datos['actoOperacion']['datosLiquidacion']);
            if (strlen($m_datos['identificacionPersonaObjetoAviso']['personaFisica']['nombre']) == 0 &&
                strlen($m_datos['identificacionPersonaObjetoAviso']['personaMoral']['razonSocial']) == 0 &&
                strlen($m_datos['identificacionPersonaObjetoAviso']['fideicomiso']['denominacion']) == 0 &&
                strlen($m_datosLiquidacion['fechaPago']) > 0) {
                $this->data['items'][$e_posicion - 1]['actoOperacion']['datosLiquidacion'][] = $m_datosLiquidacion;
            } else {

                $m_datos['actoOperacion']['datosLiquidacion'][] = $m_datosLiquidacion;
                $this->data['items'][] = $m_datos;
                $e_posicion++;
            }

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
