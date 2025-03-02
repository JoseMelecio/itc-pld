<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VehicleSaleImport implements ToCollection, WithMultipleSheets
{
    private array $data = [];

    public function collection(Collection $collection): void
    {
        foreach ($collection->skip(3) as $row) {
            //MODIFICATORIO
            $m_modificatorio = [
                'folio' => trim(strtoupper($row[0])),
                'descripcion' => trim(strtoupper($row[1])),
                'prioridad' => trim(strtoupper($row[2])),
            ];

            if (strlen($m_modificatorio['prioridad']) > 0) {
                $tempprioridad = explode(',', $m_modificatorio['prioridad']);
                $m_modificatorio['prioridad'] = trim($tempprioridad[0]);
            }
            $m_datos['modificatorio'] = $m_modificatorio;

            //DATOS DE IDENTIFICACION DE LA PERSONA OBJETO DEL AVISO
            //Persona fisica
            $m_personaFisica = [
                'nombre' => trim(strtoupper($row[3])),
                'apellidoPaterno' => trim(strtoupper($row[4])),
                'apellidoMaterno' => trim(strtoupper($row[5])),
                'fechaNacimiento' => trim(strtoupper($row[6])),
                'rfc' => trim(strtoupper($row[7])),
                'curp' => trim(strtoupper($row[8])),
                'nacionalidad' => trim(strtoupper($row[9])),
                'actividadEconomica' => trim(strtoupper($row[10])),
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
                'razonSocial' => trim(strtoupper($row[11])),
                'fechaConstitucion' => trim(strtoupper($row[12])),
                'rfc' => trim(strtoupper($row[13])),
                'nacionalidad' => trim(strtoupper($row[14])),
                'giroMercantil' => trim(strtoupper($row[15])),
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

            //Persona representante
            $m_representante = [
                'nombre' => trim(strtoupper($row[16])),
                'apellidoPaterno' => trim(strtoupper($row[17])),
                'apellidoMaterno' => trim(strtoupper($row[18])),
                'fechaNacimiento' => trim(strtoupper($row[19])),
                'rfc' => trim(strtoupper($row[20])),
                'curp' => trim(strtoupper($row[21])),
            ];
            $m_datos['identificacionPersonaObjetoAviso']['representante'] = $m_representante;

            //Fideicomiso
            $m_fideicomiso = [
                'denominacion' => trim(strtoupper($row[22])),
                'rfc' => trim(strtoupper($row[23])),
                'identificador' => trim(strtoupper($row[24])),
            ];
            $m_datos['identificacionPersonaObjetoAviso']['fideicomiso'] = $m_fideicomiso;

            $m_apoderadoLegal = [
                'nombre' => trim(strtoupper($row[25])),
                'apellidoPaterno' => trim(strtoupper($row[26])),
                'apellidoMaterno' => trim(strtoupper($row[27])),
                'fechaNacimiento' => trim(strtoupper($row[28])),
                'rfc' => trim(strtoupper($row[29])),
                'curp' => trim(strtoupper($row[30])),
            ];
            $m_datos['identificacionPersonaObjetoAviso']['apoderadoLegal'] = $m_apoderadoLegal;

            //Datos domicilio nacional
            $m_domicilioNacional = [
                'cp' => trim(strtoupper($row[31])),   // AF -> 30
                'estado' => trim(strtoupper($row[32])),   // AG -> 31
                'municipio' => trim(strtoupper($row[33])),   // AH -> 32
                'colonia' => trim(strtoupper($row[34])),   // AI -> 33
                'calle' => trim(strtoupper($row[35])),   // AJ -> 34
                'numeroExterior' => trim(strtoupper($row[36])),   // AK -> 35
                'numeroInterior' => trim(strtoupper($row[37])),    // AL -> 36
            ];

            $m_datos['identificacionPersonaObjetoAviso']['domicilioNacional'] = $m_domicilioNacional;

            //Datos domicilio extranjero
            $m_domicilioExtranjero = [
                'pais' => trim(strtoupper($row[38])),   // AM -> 38
                'estado' => trim(strtoupper($row[39])),   // AN -> 39
                'municipio' => trim(strtoupper($row[40])),   // AO -> 40
                'colonia' => trim(strtoupper($row[41])),   // AP -> 41
                'calle' => trim(strtoupper($row[42])),   // AQ -> 42
                'numeroExterior' => trim(strtoupper($row[43])),   // AR -> 43
                'numeroInterior' => trim(strtoupper($row[44])),   // AS -> 44
                'cp' => trim(strtoupper($row[45])),    // AT -> 45
            ];

            if (strlen($m_domicilioExtranjero['pais']) > 0) {
                $temPais = explode(',', $m_domicilioExtranjero['pais']);
                $m_domicilioExtranjero['pais'] = $temPais[1];
            }

            $m_datos['identificacionPersonaObjetoAviso']['domicilioExtranjero'] = $m_domicilioExtranjero;

            //Datos de contacto
            $m_datosContacto = [
                'pais' => trim(strtoupper($row[46])),   // AU -> 46
                'telefono' => trim(strtoupper($row[47])),   // AV -> 47
                'correo' => trim(strtoupper($row[48])),    // AW -> 48
            ];

            if (strlen($m_datosContacto['pais']) > 0) {
                $temPais = explode(',', $m_datosContacto['pais']);
                $m_datosContacto['pais'] = $temPais[1];
            }

            $m_datos['identificacionPersonaObjetoAviso']['contacto'] = $m_datosContacto;

            //DATOS DE INFENTIFICACION DEL BENEFICIARIO O DUEÑO
            //Persona fisica
            $m_personaFisica = [
                'nombre' => trim(strtoupper($row[49])),   // AX -> 49
                'apellidoPaterno' => trim(strtoupper($row[50])),   // AY -> 50
                'apellidoMaterno' => trim(strtoupper($row[51])),   // AZ -> 51
                'fechaNacimiento' => trim(strtoupper($row[52])),   // BA -> 52
                'rfc' => trim(strtoupper($row[53])),   // BB -> 53
                'curp' => trim(strtoupper($row[54])),   // BC -> 54
                'nacionalidad' => trim(strtoupper($row[55])),    // BD -> 55
            ];

            if (strlen($m_personaFisica['nacionalidad']) > 0) {
                $tempNacionalidad = explode(',', $m_personaFisica['nacionalidad']);
                $m_personaFisica['nacionalidad'] = $tempNacionalidad[1];
            }

            $m_datos['identificacionPersonaBeneficiaria']['personaFisica'] = $m_personaFisica;

            //Persona moral
            $m_personaMoral = [
                'razonSocial' => trim(strtoupper($row[56])),   // BE -> 56
                'fechaConstitucion' => trim(strtoupper($row[57])),   // BF -> 57
                'rfc' => trim(strtoupper($row[58])),   // BG -> 58
                'nacionalidad' => trim(strtoupper($row[59])),    // BH -> 59
            ];

            if (strlen($m_personaMoral['nacionalidad']) > 0) {
                $tempNacionalidad = explode(',', $m_personaMoral['nacionalidad']);
                $m_personaMoral['nacionalidad'] = $tempNacionalidad[1];
            }

            $m_datos['identificacionPersonaBeneficiaria']['personaMoral'] = $m_personaMoral;

            //Fideicomiso
            $m_fideicomiso = [
                'denominacion' => trim(strtoupper($row[60])),   // BI -> 60
                'rfc' => trim(strtoupper($row[61])),   // BJ -> 61
                'identificador' => trim(strtoupper($row[62])),    // BK -> 62
            ];

            $m_datos['identificacionPersonaBeneficiaria']['fideicomiso'] = $m_fideicomiso;

            //DATOS DE OPERACION
            //Datos Operacion
            $m_operacion = [
                'fechaOperacion' => trim(strtoupper($row[63])),   // BL -> 63
                'cp' => trim(strtoupper($row[64])),   // BM -> 64
                'tipoOperacion' => trim(strtoupper($row[65])),    // BN -> 65
            ];

            if (strlen($m_operacion['tipoOperacion']) > 0) {
                $tempOperacion = explode(',', $m_operacion['tipoOperacion']);
                $m_operacion['tipoOperacion'] = $tempOperacion[0];
            }

            $m_datos['datosOperacion']['operacion'] = $m_operacion;

            //Datos de los vehiculos
            $m_datosVehiculo = [
                'tipoVehiculo' => trim(strtoupper($row[66])),   // BO -> 66
                'marcaFabricante' => trim(strtoupper($row[67])),   // BP -> 67
                'modelo' => trim(strtoupper($row[68])),   // BQ -> 68
                'anio' => trim(strtoupper($row[69])),   // BR -> 69
                'vinSerie' => trim(strtoupper($row[70])),   // BS -> 70
                'repuve' => trim(strtoupper($row[71])),   // BT -> 71
                'bandera' => trim(strtoupper($row[72])),   // BU -> 72
                'placasMatricula' => trim(strtoupper($row[73])),   // BV -> 73
                'nivelBlindaje' => trim(strtoupper($row[74])),    // BW -> 74
            ];

            if (strlen($m_datosVehiculo['bandera']) > 0) {
                $tempBandera = explode(',', $m_datosVehiculo['bandera']);
                $m_datosVehiculo['bandera'] = $tempBandera[1];
            }

            if (strlen($m_datosVehiculo['nivelBlindaje']) > 0) {
                $tempVehiculo = explode(',', $m_datosVehiculo['nivelBlindaje']);
                $m_datosVehiculo['nivelBlindaje'] = $tempVehiculo[0];
            }

            $m_datos['datosOperacion']['vehiculo'] = $m_datosVehiculo;

            //Datos liquidacion
            $m_datosLiquidacion = [
                'fechaPago' => trim(strtoupper($row[75])),   // BX -> 75
                'formaPago' => trim(strtoupper($row[76])),   // BY -> 76
                'instrumentoMonetario' => trim(strtoupper($row[77])),   // BZ -> 77
                'monedaDivisa' => trim(strtoupper($row[78])),   // CA -> 78
                'montoOperacion' => trim(strtoupper($row[79])),    // CB -> 79
            ];

            if (strlen($m_datosLiquidacion['formaPago']) > 0) {
                $tempFormaPago = explode(',', $m_datosLiquidacion['formaPago']);
                $m_datosLiquidacion['formaPago'] = $tempFormaPago[0];
            }

            if (strlen($m_datosLiquidacion['instrumentoMonetario']) > 0) {
                $tempInstrumentoMonetario = explode(',', $m_datosLiquidacion['instrumentoMonetario']);
                $m_datosLiquidacion['instrumentoMonetario'] = $tempInstrumentoMonetario[0];
            }

            if (strlen($m_datosLiquidacion['monedaDivisa']) > 0) {
                $tempMonedaDivisa = explode(',', $m_datosLiquidacion['monedaDivisa']);
                $m_datosLiquidacion['monedaDivisa'] = $tempMonedaDivisa[0];
            }

            $m_datos['datosOperacion']['liquidacion'] = $m_datosLiquidacion;

            //Metemos al arreglo los datos
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
