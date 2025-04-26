<?php

namespace Database\Seeders;

use App\Models\EBRTemplateComposition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EBRTemplateCompositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $compositions = [
            'BDdeClientes' => [
                [
                    "label" => "ID de Cliente/ Usuario",
                    "var_name" => "id_client_user",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 1
                ],
                [
                    "label" => "Nombre completo o Razón  Social.",
                    "var_name" => "full_name_or_business_name",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 2
                ],
                [
                    "label" => "Tipo de Cliente (Persona física, Persona física con actividad empresarial, Persona moral o Fideicomiso)",
                    "var_name" => "client_type",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 3
                ],
                [
                    "label" => "Tipo de contrato del Cliente",
                    "var_name" => "contract_type",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 4
                ],
                [
                    "label" => "Tipo de Producto o Servicio utilizado por el Cliente.",
                    "var_name" => "product_service_type",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 5
                ],
                [
                    "label" => "Género (en caso de aplicar).",
                    "var_name" => "gender",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 6
                ],
                [
                    "label" => "Fecha de nacimiento o constitución del Cliente.",
                    "var_name" => "birth_or_incorporation_date",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 7
                ],
                [
                    "label" => "Entidad Federativa de nacimiento / origen del Cliente.",
                    "var_name" => "birth_state",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 8
                ],
                [
                    "label" => "Número telefónico en que se pueda localizar al Cliente.",
                    "var_name" => "contact_phone_number",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 9
                ],
                [
                    "label" => "LADA del número telefónico en que se pueda localizar al Cliente.",
                    "var_name" => "phone_area_code",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 10
                ],
                [
                    "label" => "País de nacimiento / origen del Cliente,",
                    "var_name" => "country_of_birth",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 11
                ],
                [
                    "label" => "País de Nacionalidad",
                    "var_name" => "nationality_country",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 12
                ],
                [
                    "label" => "Ocupación, profesión o giro del negocio al que se dedica el Cliente.",
                    "var_name" => "occupation_or_business_type",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 13
                ],
                [
                    "label" => "Teléfono",
                    "var_name" => "phone",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 14
                ],
                [
                    "label" => "Nombre de la calle, avenida o vía de que se trate del Domicilio particular del país de residencia en donde el Cliente puede recibir correspondencia dirigida a él.",
                    "var_name" => "residence_street_address",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 15
                ],
                [
                    "label" => "Colonia o urbanización del Domicilio",
                    "var_name" => "residence_neighborhood",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 16
                ],
                [
                    "label" => "Ciudad o población, entidad federativa, estado, provincia, departamento o demarcación política similar que corresponda del Domicilio.",
                    "var_name" => "residence_city_or_state",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 17
                ],
                [
                    "label" => "Alcaldía, delegación, municipio o demarcación política similar que corresponda del Domicilio",
                    "var_name" => "residence_municipality",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 18
                ],
                [
                    "label" => "Código postal del Domicilio",
                    "var_name" => "residence_postal_code",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 19
                ],
                [
                    "label" => "País del domicilio reportado por el Cliente.",
                    "var_name" => "residence_country",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 20
                ] ,
                [
                    "label" => "RFC (con homoclave), en caso de aplicar",
                    "var_name" => "rfc",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 21
                ],
                [
                    "label" => "CURP, en caso de aplicar",
                    "var_name" => "curp",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 22
                ],
                [
                    "label" => "Dirección de correo electrónico.",
                    "var_name" => "email_address",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 23
                ],
                [
                    "label" => "Dirección IP",
                    "var_name" => "ip_address",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 24
                ],
                [
                    "label" => "Sucursal, establecimiento o tercero a través del que operó el cliente/ Usuario.",
                    "var_name" => "operating_branch_or_third_party",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 25
                ],
                [
                    "label" => "Grado de Riesgo asignado.",
                    "var_name" => "assigned_risk_level",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 26
                ],
                [
                    "label" => "Clientes/ Usuarios que sean Personas Políticamente Expuestas PEP",
                    "var_name" => "is_pep",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 27
                ],
                [
                    "label" => "Clientes/ Usuarios que sean Asimilados de PEP",
                    "var_name" => "is_related_pep",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 28
                ],
                [
                    "label" => "Clientes/ Usuarios que hayan sido alertados como “Reporte de 24 horas”",
                    "var_name" => "reported_24h_alert",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 29
                ],
                [
                    "label" => "Clientes/ Usuarios que hayan sido alertados como Personas Bloqueadas",
                    "var_name" => "blocked_person_alert",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 30
                ]  ,
                [
                    "label" => "Clientes/ Usuarios que sean Fideicomisos.",
                    "var_name" => "is_trust",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 31
                ],
                [
                    "label" => "Clientes/ Usuarios, activos que sean Sujetos Obligados en materia de PLD/FT supervisados por la CNBV",
                    "var_name" => "is_subject_cnbv_supervised",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 32
                ],
                [
                    "label" => "Clientes/ Usuarios activos que sean sociedades y dependencias a las que se refiere el Anexo 1 de las Disposiciones",
                    "var_name" => "is_annex1_entity",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 33
                ],
                [
                    "label" => "Cliente con alertamientos en el sistema de alertas",
                    "var_name" => "has_alert_system_flags",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 34
                ],
                [
                    "label" => "Cliente relacionado a Operaciones Inusuales.",
                    "var_name" => "related_to_unusual_ops",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 35
                ],
                [
                    "label" => "Cliente relacionado a una alerta u Operaciones Internas Preocupantes",
                    "var_name" => "related_to_internal_concern",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 36
                ],
                [
                    "label" => "Cliente relacionado a una alerta u Operaciones 24 horas",
                    "var_name" => "related_to_24h_ops",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 37
                ],
                [
                    "label" => "Cliente relacionado a una alerta u Operaciones Inusuales 24 horas por Lista de Personas Bloqueadas",
                    "var_name" => "related_to_24h_blocked_list",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 38
                ],
                [
                    "label" => "Cliente relacionado a una alerta u Operaciones Inusuales de 24 por suspensión del proceso de identificación",
                    "var_name" => "related_to_24h_id_suspension",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 39
                ],
                [
                    "label" => "Cliente con Cotitulares o terceros autorizados en la Operación o contrato.",
                    "var_name" => "has_coholders_or_authorized",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 40
                ] ,
                [
                    "label" => "Cliente relacionado con Beneficiarios.",
                    "var_name" => "related_to_beneficiaries",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 41
                ],
                [
                    "label" => "Cliente relacionado con Proveedores de recursos.",
                    "var_name" => "related_to_resource_providers",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 42
                ],
                [
                    "label" => "Propietarios Reales, que el Cliente/ Usuario Persona Física haya declarado.",
                    "var_name" => "declared_real_owners",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 43
                ],
                [
                    "label" => "Cliente con Transferencias que provienen o van dirigidos a países o Entidades Financieras extranjeras situadas en Listas GAFI (negra y gris).",
                    "var_name" => "transfers_gafi_lists",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 44
                ],
                [
                    "label" => "Cliente con Transferencias que van dirigidas a países o a Entidades Financieras extranjeras situadas en Regímenes Fiscales Preferentes/Paraísos fiscales.",
                    "var_name" => "transfers_tax_havens",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 45
                ],
                [
                    "label" => "Clave Bancaria Estandarizada (CLABE).",
                    "var_name" => "bank_clabe",
                    "rules" => ['required' => true],
                    "type" => "string",
                    "order" => 46
                ]
            ],
            'BDdeOperaciones' => [
                [
                    "label" => "Folio de la operación",
                    "var_name" => "operation_folio",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 1
                ],
                [
                    "label" => "Fecha de la operación",
                    "var_name" => "operation_date",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 2
                ],
                [
                    "label" => "Hora de la Operación",
                    "var_name" => "operation_time",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 3
                ],
                [
                    "label" => "ID del Cliente/ Usuario que realizó la operación",
                    "var_name" => "client_user_id",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 4
                ],
                [
                    "label" => "Número de cuenta o contrato",
                    "var_name" => "account_or_contract_number",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 5
                ],
                [
                    "label" => "Tipo de operación (abono, liquidación, pago de intereses, captación, etc.)",
                    "var_name" => "operation_type",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 6
                ],
                [
                    "label" => "Monto de la operación (monto monetario del abono, pago realizado)",
                    "var_name" => "operation_amount",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 7
                ],
                [
                    "label" => "Tipo de moneda o Divisa utilizada (MXN, USD, otros)",
                    "var_name" => "currency_type",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 8
                ] ,
                [
                    "label" => "En caso de que la Operación no se haya realizado en pesos mexicanos, incluir el monto equivalente en moneda nacional",
                    "var_name" => "equivalent_amount_mxn",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 9
                ],
                [
                    "label" => "Tipo de cambio utilizado",
                    "var_name" => "exchange_rate_used",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 10
                ],
                [
                    "label" => "Instrumento monetario (efectivo, cheque, monedas acuñadas, transferencia mismo banco, transferencia interbancaria, transferencia internacional, otros)",
                    "var_name" => "monetary_instrument",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 11
                ],
                [
                    "label" => "Oficina matriz, Sucursal o establecimiento en el que sea alojan los servidores o lugar donde se registró la operación",
                    "var_name" => "operation_location",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 12
                ],
                [
                    "label" => "Cuenta bancaria o de depósito de dinero abierta en otra Entidad Financiera a través de la que se realizó la Operación",
                    "var_name" => "external_bank_account",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 13
                ],
                [
                    "label" => "Operación de recepción de recursos de Clientes o Deudores o Pagadores u Operación Realizada a través de un Fideicomiso",
                    "var_name" => "resource_reception_operation",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 14
                ],
                [
                    "label" => "Estado de la operación (cancelada, realizada, rechazada, aprobada, otros)",
                    "var_name" => "operation_status",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 15
                ],
                [
                    "label" => "Frecuencia de pago (mensual, semestral, otros)",
                    "var_name" => "payment_frequency",
                    "rules" => ['require' => true],
                    "type" => "string",
                    "order" => 16
                ]
            ]
        ];

        DB::table('ebr_template_compositions')->truncate();

        foreach ($compositions as $spreadsheet => $client) {
            foreach ($client as $record) {
                EBRTemplateComposition::create([
                    'spreadsheet' => $spreadsheet,
                    'label' => $record['label'],
                    'var_name' => $record['var_name'],
                    'rules' => $record['rules'],
                    'type' => $record['type'],
                    'order' => $record['order']
                ]);
            }
        }
    }
}
