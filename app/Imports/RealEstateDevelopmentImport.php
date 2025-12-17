<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RealEstateDevelopmentImport implements ToCollection, WithMultipleSheets
{
    private array $data = [];

    public function collection(Collection $collection): void
    {

        foreach ($collection->skip(4) as $row) {
            //DATOS DE IDENTIFICACION DE LA PERSONA OBJETO DEL AVISO

            //Modificatorio
            $m_modificatorio = [
                'folioModificacion' => trim(strtoupper($row[0])),   // A -> 0
                'descripcionModificacion' => trim(strtoupper($row[1])),   // B -> 1
                'prioridad' => trim(strtoupper($row[2])),    // C -> 2
            ];

            if (strlen($m_modificatorio['prioridad']) > 0) {
                $tempprioridad = explode(',', $m_modificatorio['prioridad']);
                $m_modificatorio['prioridad'] = trim($tempprioridad[0]);
            }

            $m_datos['modificatorio'] = $m_modificatorio;

            //Alerta
            $m_alerta = [
                'tipo_alerta' => trim(strtoupper($row[3])),
                'descripcion_alerta' => trim(strtoupper($row[4])),
            ];

            if (strlen($m_alerta['tipo_alerta']) > 0) {
                $temptipo_alerta = explode('-', $m_alerta['tipo_alerta']);
                $m_alerta['tipo_alerta'] = trim($temptipo_alerta[0]);
            }

            $m_datos['alerta'] = $m_alerta;

            //Desarrollo Inmobiliario
            $m_desarrolloInmobiliario = [
                'objetoAvisoAnterior' => trim(strtoupper($row[5])),   // D -> 3
                'modificacion' => trim(strtoupper($row[6])),   // E -> 4
                'entidadFederativa' => trim(strtoupper($row[7])),   // F -> 5
                'registroLicencia' => trim(strtoupper($row[8])),    // G -> 6
            ];

            if (strlen($m_desarrolloInmobiliario['entidadFederativa']) > 0) {
                $tempEntidad = explode(',', $m_desarrolloInmobiliario['entidadFederativa']);
                $m_desarrolloInmobiliario['entidadFederativa'] = $tempEntidad[0];
            }

            $m_datos['desarrolloInmobiliario'] = $m_desarrolloInmobiliario;

            //Caracteristicas del desarrollo
            $m_caracteristicas_desarrollo = [
                'codigoPostal' => trim(strtoupper($row[9])),   // H -> 7
                'colonia' => trim(strtoupper($row[10])),   // I -> 8
                'calle' => trim(strtoupper($row[11])),   // J -> 9
                'tipoDesarrollo' => trim(strtoupper($row[12])),  // K -> 10
                'descripcionDesarrollo' => trim(strtoupper($row[13])),  // L -> 11
                'montoDesarrollo' => trim(strtoupper($row[14])),  // M -> 12
                'unidadesComercializadas' => trim(strtoupper($row[15])),  // N -> 13
                'costoUnidad' => trim(strtoupper($row[16])),  // O -> 14
                'otrasEmpresas' => trim(strtoupper($row[17])),   // P -> 15
            ];

            if (strlen($m_caracteristicas_desarrollo['tipoDesarrollo']) > 0) {
                $tempTipoDesarrollo = explode(',', $m_caracteristicas_desarrollo['tipoDesarrollo']);
                $m_caracteristicas_desarrollo['tipoDesarrollo'] = $tempTipoDesarrollo[0];
            }

            $m_caracteristicas_desarrollo['montoDesarrollo'] = strlen($m_caracteristicas_desarrollo['montoDesarrollo'] > 0) ? $m_caracteristicas_desarrollo['montoDesarrollo'] : 0;
            $m_datos['caracteristicasDesarrollo'] = $m_caracteristicas_desarrollo;

            //Fecha Aportacion
            $m_fechaAportacion = trim(strtoupper($row[18]));   // Q -> 16
            $m_datos['aportaciones']['fechaAportacion'] = $m_fechaAportacion;

            //Recursos propios, aportacion numerario
            $m_recursosPropiosNumerario = [
                'instrumentoMonetario' => trim(strtoupper($row[19])),   // R -> 17
                'moneda' => trim(strtoupper($row[20])),   // S -> 18
                'montoAportacion' => trim(strtoupper($row[21])),   // T -> 19
                'aportacionFideicomiso' => trim(strtoupper($row[22])),   // U -> 20
                'nombreInstitucion' => trim(strtoupper($row[23])),    // V -> 21
            ];

            if (strlen($m_recursosPropiosNumerario['instrumentoMonetario']) > 0) {
                $tempinstrumentoMonetario = explode(',', $m_recursosPropiosNumerario['instrumentoMonetario']);
                $m_recursosPropiosNumerario['instrumentoMonetario'] = $tempinstrumentoMonetario[0];
            }

            if (strlen($m_recursosPropiosNumerario['moneda']) > 0) {
                $tempmoneda = explode(',', $m_recursosPropiosNumerario['moneda']);
                $m_recursosPropiosNumerario['moneda'] = $tempmoneda[0];
            }

            $m_datos['aportaciones']['recursosPropios']['numerario'] = $m_recursosPropiosNumerario;

            //Recursos propios, aportacion en especie
            $m_recursosPropiosEspecie = [
                'descripcionBien' => trim(strtoupper($row[24])),   // W -> 22
                'montoEstimado' => trim(strtoupper($row[25])),    // X -> 23
            ];

            $m_datos['aportaciones']['recursosPropios']['especie'] = $m_recursosPropiosEspecie;

            //Recursos socios, aportacion numerario
            $e_cantidadSocios = trim(strtoupper($row[26]));   // Y -> 24
            $m_datos['aportaciones']['socios']['cantidadSocios'] = $e_cantidadSocios;
            $m_socios = [];

            for ($e_contador = 1; $e_contador <= $e_cantidadSocios; $e_contador++) {
                $m_unSocio = [];
                $m_datosGeneralesSocios = [
                    'aportacionAnterior' => trim(strtoupper($row[27])),   // Z -> 25
                    'rfcSocio' => trim(strtoupper($row[28])),    // AA -> 26
                ];

                $m_unSocio['datosGenerales'] = $m_datosGeneralesSocios;
                //Socio Persona Fisica
                $m_socioPersonaFisica = [
                    'nombre' => trim(strtoupper($row[29])),   // AB -> 27
                    'apellidoMaterno' => trim(strtoupper($row[30])),   // AC -> 28
                    'apellidoPaterno' => trim(strtoupper($row[31])),   // AD -> 29
                    'fechaNacimiento' => trim(strtoupper($row[32])),   // AE -> 30
                    'curp' => trim(strtoupper($row[33])),   // AF -> 31
                    'pais' => trim(strtoupper($row[34])),   // AG -> 32
                    'actividadEconomica' => trim(strtoupper($row[35])),    // AH -> 33
                ];

                if (strlen($m_socioPersonaFisica['pais']) > 0) {
                    $temppais = explode(',', $m_socioPersonaFisica['pais']);
                    $m_socioPersonaFisica['pais'] = $temppais[1];
                }

                if (strlen($m_socioPersonaFisica['actividadEconomica']) > 0) {
                    $tempactividadEconomica = explode('||', $m_socioPersonaFisica['actividadEconomica']);
                    $m_socioPersonaFisica['actividadEconomica'] = $tempactividadEconomica[1];
                }

                $m_unSocio['personaFisica'] = $m_socioPersonaFisica;

                //Socio Persona Moral
                $m_socioPersonaMoral = [
                    'razonSocial' => trim(strtoupper($row[36])),   // AI -> 34
                    'fechaConstitucion' => trim(strtoupper($row[37])),   // AJ -> 35
                    'pais' => trim(strtoupper($row[38])),   // AK -> 36
                    'giroMercantil' => trim(strtoupper($row[39])),    // AL -> 37
                ];

                if (strlen($m_socioPersonaMoral['pais']) > 0) {
                    $temppais = explode(',', $m_socioPersonaMoral['pais']);
                    $m_socioPersonaMoral['pais'] = $temppais[1];
                }

                if (strlen($m_socioPersonaMoral['giroMercantil']) > 0) {
                    $tempgiroMercantil = explode('||', $m_socioPersonaMoral['giroMercantil']);
                    $m_socioPersonaMoral['giroMercantil'] = $tempgiroMercantil[1];
                }

                $m_unSocio['personaMoral'] = $m_socioPersonaMoral;

                //Socio Representante Legal
                $m_socioRepresentante = [
                    'nombre' => trim(strtoupper($row[40])),   // AM -> 38
                    'apellidoMaterno' => trim(strtoupper($row[41])),   // AN -> 39
                    'apellidoPaterno' => trim(strtoupper($row[42])),   // AO -> 40
                    'fechaNacimiento' => trim(strtoupper($row[43])),   // AP -> 41
                    'rfc' => trim(strtoupper($row[44])),   // AQ -> 42
                    'curp' => trim(strtoupper($row[45])),    // AR -> 43
                ];

                $m_unSocio['representante'] = $m_socioRepresentante;

                //Socio Fideicomiso
                $m_socioFideicomiso = [
                    'denominacion' => trim(strtoupper($row[46])),   // AS -> 44
                    'rfc' => trim(strtoupper($row[47])),   // AT -> 45
                    'identificador' => trim(strtoupper($row[48])),    // AU -> 46
                ];

                $m_unSocio['fideicomiso'] = $m_socioFideicomiso;

                //Datos Socio domicilio nacional
                $m_socioDomicilioNacional = [
                    'colonia' => trim(strtoupper($row[49])),   // AV -> 47
                    'calle' => trim(strtoupper($row[50])),   // AW -> 48
                    'numeroExterior' => trim(strtoupper($row[51])),   // AX -> 49
                    'numeroInterior' => trim(strtoupper($row[52])),   // AY -> 50
                    'cp' => trim(strtoupper($row[53])),    // AZ -> 51
                ];

                $m_unSocio['domicilioNacional'] = $m_socioDomicilioNacional;

                //Datos  Socio domicilio extranjero
                $m_socioDomicilioExtranjero = [
                    'pais' => trim(strtoupper($row[54])),   // BA -> 52
                    'estado' => trim(strtoupper($row[55])),   // BB -> 53
                    'municipio' => trim(strtoupper($row[56])),   // BC -> 54
                    'colonia' => trim(strtoupper($row[57])),   // BD -> 55
                    'calle' => trim(strtoupper($row[58])),   // BE -> 56
                    'numeroExterior' => trim(strtoupper($row[59])),   // BF -> 57
                    'numeroInterior' => trim(strtoupper($row[60])),   // BG -> 58
                    'cp' => trim(strtoupper($row[61])),    // BH -> 59
                ];

                if (strlen($m_socioDomicilioExtranjero['pais']) > 0) {
                    $temPais = explode(',', $m_socioDomicilioExtranjero['pais']);
                    $m_socioDomicilioExtranjero['pais'] = $temPais[1];
                }

                $m_unSocio['domicilioExtranjero'] = $m_socioDomicilioExtranjero;

                //Socio Contacto
                $m_socioContacto = [
                    'pais' => trim(strtoupper($row[62])),   // BI -> 60
                    'telefono' => trim(strtoupper($row[63])),   // BJ -> 61
                    'correo' => trim(strtoupper($row[64])),    // BK -> 62
                ];

                if (strlen($m_socioContacto['pais']) > 0) {
                    $temPais = explode(',', $m_socioContacto['pais']);
                    $m_socioContacto['pais'] = $temPais[1];
                }

                $m_unSocio['contacto'] = $m_socioContacto;

                //Recursos socios, aportacion numerario
                $m_recursosSociosNumeral = [
                    'instrumentoMonetario' => trim(strtoupper($row[65])),   // BL -> 63
                    'moneda' => trim(strtoupper($row[66])),   // BM -> 64
                    'montoAportacion' => trim(strtoupper($row[67])),   // BN -> 65
                    'aportacionFideicomiso' => trim(strtoupper($row[68])),   // BO -> 66
                    'nombreInstitucion' => trim(strtoupper($row[69])),    // BP -> 67
                ];

                if (strlen($m_recursosSociosNumeral['instrumentoMonetario']) > 0) {
                    $tempinstrumentoMonetario = explode(',', $m_recursosSociosNumeral['instrumentoMonetario']);
                    $m_recursosSociosNumeral['instrumentoMonetario'] = $tempinstrumentoMonetario[0];
                }

                if (strlen($m_recursosSociosNumeral['moneda']) > 0) {
                    $tempmoneda = explode(',', $m_recursosSociosNumeral['moneda']);
                    $m_recursosSociosNumeral['moneda'] = $tempmoneda[0];
                }

                $m_unSocio['recursoNumeral'] = $m_recursosSociosNumeral;

                //Recursos socios, aportacion en especie
                $m_recursosSociosEspecie = [
                    'descripcionBien' => trim(strtoupper($row[70])),   // BQ -> 68
                    'montoEstimado' => trim(strtoupper($row[71])),    // BR -> 69
                ];

                $m_unSocio['recursoEspecie'] = $m_recursosSociosEspecie;

                $m_datos['aportaciones']['socios'][$e_contador] = $m_unSocio;

            }

            //$m_datos['aportaciones']['socios'][] = $m_socios;

            //Recursos tercero, aportacion numerario
            $e_cantidadTercero = trim(strtoupper($row[72]));   // BS -> 70
            $m_datos['aportaciones']['terceros']['cantidadTerceros'] = $e_cantidadTercero;
            $m_terceros = [];

            for ($e_contador = 1; $e_contador <= $e_cantidadTercero; $e_contador++) {
                $m_unTercero = [];
                $m_datosGeneralesTercero = [
                    'tipoTercero' => trim(strtoupper($row[73])),   // BT -> 71
                    'descripcionTercero' => trim(strtoupper($row[74])),    // BU -> 72
                ];

                if (strlen($m_datosGeneralesTercero['tipoTercero']) > 0) {
                    $temptipoTercero = explode(',', $m_datosGeneralesTercero['tipoTercero']);
                    $m_datosGeneralesTercero['tipoTercero'] = $temptipoTercero[0];
                }

                $m_unTercero['datosGenerales'] = $m_datosGeneralesTercero;

                //Tercero Persona Fisica
                $m_terceroPersonaFisica = [
                    'nombre' => trim(strtoupper($row[75])),   // BV -> 73
                    'apellidoMaterno' => trim(strtoupper($row[76])),   // BW -> 74
                    'apellidoPaterno' => trim(strtoupper($row[77])),   // BX -> 75
                    'fechaNacimiento' => trim(strtoupper($row[78])),   // BY -> 76
                    'rfc' => trim(strtoupper($row[79])),   // BZ -> 77
                    'curp' => trim(strtoupper($row[80])),   // CA -> 78
                    'pais' => trim(strtoupper($row[81])),   // CB -> 79
                    'actividadEconomica' => trim(strtoupper($row[82])),    // CC -> 80
                ];

                if (strlen($m_terceroPersonaFisica['pais']) > 0) {
                    $temppais = explode(',', $m_terceroPersonaFisica['pais']);
                    $m_terceroPersonaFisica['pais'] = $temppais[1];
                }

                if (strlen($m_terceroPersonaFisica['actividadEconomica']) > 0) {
                    $tempactividadEconomica = explode('||', $m_terceroPersonaFisica['actividadEconomica']);
                    $m_terceroPersonaFisica['actividadEconomica'] = $tempactividadEconomica[1];
                }

                $m_unTercero['personaFisica'] = $m_terceroPersonaFisica;

                //Tercero Persona Moral
                $m_terceroPersonaMoral = [
                    'razonSocial' => trim(strtoupper($row[83])),   // CD -> 81
                    'fechaConstitucion' => trim(strtoupper($row[84])),   // CE -> 82
                    'rfc' => trim(strtoupper($row[85])),   // CF -> 83
                    'pais' => trim(strtoupper($row[86])),   // CG -> 84
                    'giroMercantil' => trim(strtoupper($row[87])),    // CH -> 85
                ];

                if (strlen($m_terceroPersonaMoral['pais']) > 0) {
                    $temppais = explode(',', $m_terceroPersonaMoral['pais']);
                    $m_terceroPersonaMoral['pais'] = $temppais[1];
                }

                if (strlen($m_terceroPersonaMoral['giroMercantil']) > 0) {
                    $tempgiroMercantil = explode('||', $m_terceroPersonaMoral['giroMercantil']);
                    $m_terceroPersonaMoral['giroMercantil'] = $tempgiroMercantil[1];
                }

                $m_unTercero['personaMoral'] = $m_terceroPersonaMoral;

                //Tercero Representante Legal
                $m_terceroRepresentanteLegal = [
                    'nombre' => trim(strtoupper($row[88])),   // CI -> 86
                    'apellidoMaterno' => trim(strtoupper($row[89])),   // CJ -> 87
                    'apellidoPaterno' => trim(strtoupper($row[90])),   // CK -> 88
                    'fechaNacimiento' => trim(strtoupper($row[91])),   // CL -> 89
                    'rfc' => trim(strtoupper($row[92])),   // CM -> 90
                    'curp' => trim(strtoupper($row[93])),    // CN -> 91
                ];

                $m_unTercero['representanteLegal'] = $m_terceroRepresentanteLegal;

                //Tercero Fideicomiso
                $m_terceroFideicomiso = [
                    'denominacion' => trim(strtoupper($row[94])),   // CO -> 92
                    'rfc' => trim(strtoupper($row[95])),   // CP -> 93
                    'identificador' => trim(strtoupper($row[96])),    // CQ -> 94
                ];

                $m_unTercero['fideicomiso'] = $m_terceroFideicomiso;

                //Recursos Terceros, aportacion numerario
                $m_recursosTercerosNumeral = [
                    'instrumentoMonetario' => trim(strtoupper($row[97])),   // CR -> 95
                    'moneda' => trim(strtoupper($row[98])),   // CS -> 96
                    'montoAportacion' => trim(strtoupper($row[99])),  // CT -> 97
                    'aportacionFideicomiso' => trim(strtoupper($row[100])),  // CU -> 98
                    'nombreInstitucion' => trim(strtoupper($row[101])),  // CV -> 99
                    'valorInmueblePreVenta' => trim(strtoupper($row[102])),   // CW -> 100
                ];

                if (strlen($m_recursosTercerosNumeral['instrumentoMonetario']) > 0) {
                    $tempinstrumentoMonetario = explode(',', $m_recursosTercerosNumeral['instrumentoMonetario']);
                    $m_recursosTercerosNumeral['instrumentoMonetario'] = $tempinstrumentoMonetario[0];
                }

                if (strlen($m_recursosTercerosNumeral['moneda']) > 0) {
                    $tempmoneda = explode(',', $m_recursosTercerosNumeral['moneda']);
                    $m_recursosTercerosNumeral['moneda'] = $tempmoneda[0];
                }

                $m_unTercero['recursoNumeral'] = $m_recursosTercerosNumeral;

                //Recursos Terceros, aportacion en especie
                $m_recursosTercerosEspecie = [
                    'descripcionBien' => trim(strtoupper($row[103])),   // CX -> 101
                    'montoEstimado' => trim(strtoupper($row[104])),    // CY -> 102
                ];

                $m_unTercero['recursoEspecie'] = $m_recursosTercerosEspecie;

                $m_Terceros[$e_contador] = $m_unTercero;

                $m_datos['aportaciones']['terceros'][$e_contador] = $m_unTercero;

            }

            //Presatamo financiero
            $m_prestamoFinanciero = [
                'tipoInstitucion' => trim(strtoupper($row[105])),   // CZ -> 103
                'institucion' => trim(strtoupper($row[106])),   // DA -> 104
                'tipoCredito' => trim(strtoupper($row[107])),   // DB -> 105
                'montoPrestamo' => trim(strtoupper($row[108])),   // DC -> 106
                'moneda' => trim(strtoupper($row[109])),   // DD -> 107
                'plazo' => trim(strtoupper($row[110])),    // DE -> 108
            ];

            if (strlen($m_prestamoFinanciero['tipoInstitucion']) > 0) {
                $temptipoInstitucion = explode(',', $m_prestamoFinanciero['tipoInstitucion']);
                $m_prestamoFinanciero['tipoInstitucion'] = $temptipoInstitucion[0];
            }

            if (strlen($m_prestamoFinanciero['tipoCredito']) > 0) {
                $temptipoCredito = explode(',', $m_prestamoFinanciero['tipoCredito']);
                $m_prestamoFinanciero['tipoCredito'] = $temptipoCredito[0];
            }

            if (strlen($m_prestamoFinanciero['moneda']) > 0) {
                $tempmoneda = explode(',', $m_prestamoFinanciero['moneda']);
                $m_prestamoFinanciero['moneda'] = $tempmoneda[0];
            }

            $m_datos['aportaciones']['prestamoFinanciero'] = $m_prestamoFinanciero;

            //Prestamo No Financiero
            $m_datosPrestamoNoFinanciero = [
                'montoPrestamo' => trim(strtoupper($row[111])),   // DF -> 109
                'moneda' => trim(strtoupper($row[112])),   // DG -> 110
                'plazo' => trim(strtoupper($row[113])),    // DH -> 111
            ];

            if (strlen($m_datosPrestamoNoFinanciero['moneda']) > 0) {
                $tempmoneda = explode(',', $m_datosPrestamoNoFinanciero['moneda']);
                $m_datosPrestamoNoFinanciero['moneda'] = $tempmoneda[0];
            }

            $m_datos['aportaciones']['prestamoNoFinanciero']['generales'] = $m_datosPrestamoNoFinanciero;

            //Prestamo no financiero, Acreedor persona fisica
            $m_terceroPersonaFisica = [
                'nombre' => trim(strtoupper($row[114])),   // DI -> 112
                'apellidoMaterno' => trim(strtoupper($row[115])),   // DJ -> 113
                'apellidoPaterno' => trim(strtoupper($row[116])),   // DK -> 114
                'fechaNacimiento' => trim(strtoupper($row[117])),   // DL -> 115
                'rfc' => trim(strtoupper($row[118])),   // DM -> 116
                'curp' => trim(strtoupper($row[119])),   // DN -> 117
                'pais' => trim(strtoupper($row[120])),   // DO -> 118
                'actividadEconomica' => trim(strtoupper($row[121])),    // DP -> 119
            ];

            if (strlen($m_terceroPersonaFisica['pais']) > 0) {
                $temppais = explode(',', $m_terceroPersonaFisica['pais']);
                $m_terceroPersonaFisica['pais'] = $temppais[1];
            }

            if (strlen($m_terceroPersonaFisica['actividadEconomica']) > 0) {
                $tempactividadEconomica = explode('||', $m_terceroPersonaFisica['actividadEconomica']);
                $m_terceroPersonaFisica['actividadEconomica'] = $tempactividadEconomica[1];
            }

            $m_datos['aportaciones']['prestamoNoFinanciero']['acreedorFisico'] = $m_terceroPersonaFisica;

            //Prestamo no financiero, Acreedor persona moral
            $m_terceroPersonaMoral = [
                'denominacion' => trim(strtoupper($row[122])),   // DQ -> 120
                'fechaConstitucion' => trim(strtoupper($row[123])),   // DR -> 121
                'rfc' => trim(strtoupper($row[124])),   // DS -> 122
                'pais' => trim(strtoupper($row[125])),   // DT -> 123
                'actividadEconomica' => trim(strtoupper($row[126])),    // DU -> 124
            ];

            if (strlen($m_terceroPersonaMoral['pais']) > 0) {
                $temppais = explode(',', $m_terceroPersonaMoral['pais']);
                $m_terceroPersonaMoral['pais'] = $temppais[1];
            }

            if (strlen($m_terceroPersonaMoral['actividadEconomica']) > 0) {
                $tempactividadEconomica = explode('||', $m_terceroPersonaMoral['actividadEconomica']);
                $m_terceroPersonaMoral['actividadEconomica'] = $tempactividadEconomica[1];
            }

            $m_datos['aportaciones']['prestamoNoFinanciero']['acreedorMoral'] = $m_terceroPersonaMoral;

            //Prestamo no financiero, Apoderado Legal
            $m_terceroPersonaMoral = [
                'nombre' => trim(strtoupper($row[127])),   // DV -> 125
                'apellidoPaterno' => trim(strtoupper($row[128])),   // DW -> 126
                'apellidoMaterno' => trim(strtoupper($row[129])),   // DX -> 127
                'fechaNacimiento' => trim(strtoupper($row[130])),   // DY -> 128
                'rfc' => trim(strtoupper($row[131])),   // DZ -> 129
                'curp' => trim(strtoupper($row[132])),    // EA -> 130
            ];

            $m_datos['aportaciones']['prestamoNoFinanciero']['acreedorApoderado'] = $m_terceroPersonaMoral;

            //Prestamo no financiero, fideicomiso
            $m_prestamoNoFinanciero = [
                'denominacion' => trim(strtoupper($row[133])),   // EB -> 131
                'rfc' => trim(strtoupper($row[134])),   // EC -> 132
                'identificador' => trim(strtoupper($row[135])),    // ED -> 133
            ];

            $m_datos['aportaciones']['prestamoNoFinanciero']['acreedorFideicomiso'] = $m_prestamoNoFinanciero;

            //Prestamo bursatil
            $m_prestamoBursatil = [
                'fechaEmision' => trim(strtoupper($row[136])),   // EE -> 136
                'montoSolicitado' => trim(strtoupper($row[137])),   // EF -> 137
                'montoRecibido' => trim(strtoupper($row[138])),    // EG -> 138
            ];

            $m_datos['aportaciones']['prestamoBursatil'] = $m_prestamoBursatil;

            $this->data['items'][] = $m_datos;
        }
    }

    /**
     * Retorna los datos procesados.
     */
    public function getData(): array
    {
        Log::info(json_encode($this->data));
        return $this->data;
    }

    public function sheets(): array
    {
        return [
            'Plantilla' => $this,
        ];
    }
}
