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

            //ALERTA
            $m_alerta = [
                'tipo_alerta' => trim(strtoupper($row[3])),
                'descripcion' => trim(strtoupper($row[4])),
            ];

            if (strlen($m_alerta['tipo_alerta']) > 0) {
                $temptipo_alerta = explode('-', $m_alerta['tipo_alerta']);
                $m_alerta['tipo_alerta'] = trim($temptipo_alerta[0]);
            }

            $m_datos['alerta'] = $m_alerta;

            //IDENTIFICACION DE LA PERSONA OFICIO
            //Persona fisica
            $m_personaFisica = [
                'nombre' => trim(strtoupper($row[5])),   // D -> 3
                'apellidoPaterno' => trim(strtoupper($row[6])),   // E -> 4
                'apellidoMaterno' => trim(strtoupper($row[7])),   // F -> 5
                'fechaNacimiento' => trim(strtoupper($row[8])),   // G -> 6
                'rfc' => trim(strtoupper($row[9])),   // H -> 7
                'curp' => trim(strtoupper($row[10])),   // I -> 8
                'nacionalidad' => trim(strtoupper($row[11])),   // J -> 9
                'actividadEconomica' => trim(strtoupper($row[12])),   // K -> 10
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
                'razonSocial' => trim(strtoupper($row[13])),   // L -> 11
                'fechaConstitucion' => trim(strtoupper($row[14])),   // M -> 12
                'rfc' => trim(strtoupper($row[15])),   // N -> 13
                'nacionalidad' => trim(strtoupper($row[16])),   // O -> 14
                'giroMercantil' => trim(strtoupper($row[17])),    // P -> 15
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
                'nombre' => trim(strtoupper($row[18])),   // Q -> 16
                'apellidoPaterno' => trim(strtoupper($row[19])),   // R -> 17
                'apellidoMaterno' => trim(strtoupper($row[20])),   // S -> 18
                'fechaNacimiento' => trim(strtoupper($row[21])),   // T -> 19
                'rfc' => trim(strtoupper($row[22])),   // U -> 20
                'curp' => trim(strtoupper($row[23])),    // V -> 21
            ];

            $m_datos['identificacionPersonaObjetoAviso']['datosRepresentantesMoral'] = $m_representante;

            //Fideicomiso
            $m_fideicomiso = [
                'denominacion' => trim(strtoupper($row[24])),   // W -> 22
                'rfc' => trim(strtoupper($row[25])),   // X -> 23
                'identificador' => trim(strtoupper($row[26])),    // Y -> 24
            ];

            $m_datos['identificacionPersonaObjetoAviso']['fideicomiso'] = $m_fideicomiso;

            //Datos representante fideicomiso
            $m_representante = [
                'nombre' => trim(strtoupper($row[27])),   // Z -> 25
                'apellidoPaterno' => trim(strtoupper($row[28])),   // AA -> 26
                'apellidoMaterno' => trim(strtoupper($row[29])),   // AB -> 27
                'fechaNacimiento' => trim(strtoupper($row[30])),   // AC -> 28
                'rfc' => trim(strtoupper($row[31])),   // AD -> 29
                'curp' => trim(strtoupper($row[32])),    // AE -> 30
            ];

            $m_datos['identificacionPersonaObjetoAviso']['datosRepresentantesFideicomiso'] = $m_representante;

            //Domicilio Nacional de la persona objeto del aviso
            $m_domicilioNacionalPersonaAviso = [
                'colonia' => trim(strtoupper($row[33])),   // AF -> 31
                'calle' => trim(strtoupper($row[34])),   // AG -> 32
                'numeroExterior' => trim(strtoupper($row[35])),   // AH -> 33
                'numeroInterior' => trim(strtoupper($row[36])),   // AI -> 34
                'cp' => trim(strtoupper($row[37])),   // AJ -> 35
                'municipio' => trim(strtoupper($row[38])),    // AK -> 36
            ];

            $m_datos['identificacionPersonaObjetoAviso']['domicilioNacionalPersonaAviso'] = $m_domicilioNacionalPersonaAviso;

            //Domicilio Internacional de la persona objeto del aviso
            $m_domicilioInternacionalPersonaAviso = [
                'pais' => trim(strtoupper($row[39])),   // AL -> 37
                'estado' => trim(strtoupper($row[40])),   // AM -> 38
                'ciudad' => trim(strtoupper($row[41])),   // AN -> 39
                'colonia' => trim(strtoupper($row[42])),   // AO -> 40
                'calle' => trim(strtoupper($row[43])),   // AP -> 41
                'numeroExterior' => trim(strtoupper($row[44])),   // AQ -> 42
                'numeroInterior' => trim(strtoupper($row[45])),   // AR -> 43
                'cp' => trim(strtoupper($row[46])),    // AS -> 44
            ];

            if (strlen($m_domicilioInternacionalPersonaAviso['pais']) > 0) {
                $tempPais = explode(',', $m_domicilioInternacionalPersonaAviso['pais']);
                $m_domicilioInternacionalPersonaAviso['pais'] = $tempPais[1];
            }

            $m_datos['identificacionPersonaObjetoAviso']['domicilioInternacionalPersonaAviso'] = $m_domicilioInternacionalPersonaAviso;

            //Datos del contacto
            $m_datosContactoAviso = [
                'pais' => trim(strtoupper($row[47])),   // AT -> 45
                'telefono' => trim(strtoupper($row[48])),   // AU -> 46
                'correo' => trim(strtoupper($row[49])),    // AV -> 47
            ];

            if (strlen($m_datosContactoAviso['pais']) > 0) {
                $tempPais = explode(',', $m_datosContactoAviso['pais']);
                $m_datosContactoAviso['pais'] = $tempPais[1];
            }

            $m_datos['identificacionPersonaObjetoAviso']['datosContactoAviso'] = $m_datosContactoAviso;

            //DATOS DEL BENEFICIARIO
            //Persona fisica
            $m_personaFisica = [
                'nombre' => trim(strtoupper($row[50])),   // AW -> 48
                'apellidoPaterno' => trim(strtoupper($row[51])),   // AX -> 49
                'apellidoMaterno' => trim(strtoupper($row[52])),   // AY -> 50
                'fechaNacimiento' => trim(strtoupper($row[53])),   // AZ -> 51
                'rfc' => trim(strtoupper($row[54])),   // BA -> 52
                'curp' => trim(strtoupper($row[55])),   // BB -> 53
                'pais' => trim(strtoupper($row[56])),    // BC -> 54
            ];

            if (strlen($m_personaFisica['pais']) > 0) {
                $temppais = explode(',', $m_personaFisica['pais']);
                $m_personaFisica['pais'] = $temppais[1];
            }

            $m_datos['beneficiarioControlador']['personaFisica'] = $m_personaFisica;

            //Persona moral
            $m_personaMoral = [
                'razonSocial' => trim(strtoupper($row[57])),   // BD -> 55
                'fechaConstitucion' => trim(strtoupper($row[58])),   // BE -> 56
                'rfc' => trim(strtoupper($row[59])),   // BF -> 57
                'nacionalidad' => trim(strtoupper($row[60])),    // BG -> 58
            ];

            if (strlen($m_personaMoral['nacionalidad']) > 0) {
                $tempGiro = explode(',', $m_personaMoral['nacionalidad']);
                $m_personaMoral['nacionalidad'] = $tempGiro[1];
            }

            $m_datos['beneficiarioControlador']['personaMoral'] = $m_personaMoral;

            //Fideicomiso
            $m_fideicomiso = [
                'denominacion' => trim(strtoupper($row[61])),   // BH -> 59
                'rfc' => trim(strtoupper($row[62])),   // BI -> 60
                'identificador' => trim(strtoupper($row[63])),    // BJ -> 61
            ];
            $m_datos['beneficiarioControlador']['fideicomiso'] = $m_fideicomiso;

            //Datos de la operacion
            $m_datosOperacion = [
                'fechaOperacion' => trim(strtoupper($row[64])),   // BK -> 63
                'figuraClienteReportado' => trim(strtoupper($row[65])),   // BL -> 63
                'figurapersonaRealizaActividad' => trim(strtoupper($row[66])),    // BM -> 64
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
                'nombre' => trim(strtoupper($row[67])),   // BN -> 65
                'apellidoPaterno' => trim(strtoupper($row[68])),   // BO -> 66
                'apellidoMaterno' => trim(strtoupper($row[69])),   // BP -> 67
                'fechaNacimiento' => trim(strtoupper($row[70])),   // BQ -> 68
                'rfc' => trim(strtoupper($row[71])),   // BR -> 69
                'curp' => trim(strtoupper($row[72])),   // BS -> 70
                'pais' => trim(strtoupper($row[73])),    // BT -> 71
            ];

            if (strlen($m_personaFisica['pais']) > 0) {
                $temppais = explode(',', $m_personaFisica['pais']);
                $m_personaFisica['pais'] = $temppais[1];
            }
            $m_datos['detallesOperacion']['contraparte']['personaFisica'] = $m_personaFisica;

            //Persona moral
            $m_personaMoral = [
                'razonSocial' => trim(strtoupper($row[74])),   // BU -> 72
                'fechaConstitucion' => trim(strtoupper($row[75])),   // BV -> 73
                'rfc' => trim(strtoupper($row[76])),   // BW -> 74
                'nacionalidad' => trim(strtoupper($row[77])),    // BX -> 75
            ];

            if (strlen($m_personaMoral['nacionalidad']) > 0) {
                $tempGiro = explode(',', $m_personaMoral['nacionalidad']);
                $m_personaMoral['nacionalidad'] = $tempGiro[1];
            }
            $m_datos['detallesOperacion']['contraparte']['personaMoral'] = $m_personaMoral;

            //Fideicomiso
            $m_fideicomiso = [
                'denominacion' => trim(strtoupper($row[78])),   // BY -> 76
                'rfc' => trim(strtoupper($row[79])),   // BZ -> 77
                'identificador' => trim(strtoupper($row[80])),    // CA -> 78
            ];

            $m_datos['detallesOperacion']['contraparte']['fideicomiso'] = $m_fideicomiso;

            //CARACTERISTICAS DEL INMUEBLE OBJETO
            //Caracteristicas del Inmueble
            $m_caracteristicasInmueble = [
                'tipoInmueble' => trim(strtoupper($row[81])),   // CB -> 79
                'valorPactado' => trim(strtoupper($row[82])),   // CC -> 80
                'colonia' => trim(strtoupper($row[83])),   // CD -> 81
                'calle' => trim(strtoupper($row[84])),   // CE -> 82
                'numeroExterior' => trim(strtoupper($row[85])),   // CF -> 83
                'numeroInterior' => trim(strtoupper($row[86])),   // CG -> 84
                'cp' => trim(strtoupper($row[87])),   // CH -> 85
                'estado' => trim(strtoupper($row[88])),   // CI -> 86
                'municipio' => trim(strtoupper($row[89])),   // CJ -> 87
                'dimensionTerreno' => trim(strtoupper($row[90])),   // CK -> 88
                'dimensionInmueble' => trim(strtoupper($row[91])),   // CL -> 89
                'antecedentesRegistrales' => trim(strtoupper($row[92])),    // CM -> 90
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
                'numero' => trim(strtoupper($row[93])),   // CN -> 91
                'fecha' => trim(strtoupper($row[94])),   // CO -> 92
                'numeroNotario' => trim(strtoupper($row[95])),   // CP -> 93
                'entidadNotario' => trim(strtoupper($row[96])),   // CQ -> 94
                'avaluo' => trim(strtoupper($row[97])),    // CR -> 95
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
                'fechaPago' => trim($row[98]),   // CT -> 97
                'formaPago' => trim($row[99]),  // CU -> 98
                'instrumento' => trim($row[100]),  // CV -> 99
                'moneda' => trim($row[101]),  // CW -> 100
                'monto' => trim($row[102]),   // CX -> 101
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
