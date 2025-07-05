<table>
  <tr>
    <td>👨‍💼</td>
    <td colspan="4" style="font-size: 18px; font-weight: bold;">Metodología con Enfoque Basado en Riesgo - {{ $ebr->empresa ?? 'Entidad' }}</td>
  </tr>
  <tr><td colspan="5">AÑO: Enero a Diciembre {{ \Carbon\Carbon::now()->year }}</td></tr>

  <tr>
    <td style="background-color:#1F497D; color:white;">Monto total de Operación</td>
    <td style="background-color:#1F497D; color:white;">Número de Clientes</td>
    <td style="background-color:#1F497D; color:white;">Número total de Operaciones</td>
    <td style="background-color:#1F497D; color:white;">Concentración</td>
    <td style="background-color:#1F497D; color:white;">Características Presentes</td>
  </tr>

  <tr>
    <td>${{ number_format($ebr->total_operation_amount, 2) }}</td>
    <td>{{ $ebr->total_clients }}</td>
    <td>{{ number_format($ebr->total_operations) }}</td>
    <td>{{ number_format($ebr->concentration * 100, 2) }}%</td>
    <td>{{ number_format($ebr->present_features * 100, 2) }}%</td>
  </tr>

  <tr>
    <td colspan="5" style="font-weight:bold; color:red;">RIESGO INHERENTE ENTIDAD: {{ number_format($ebr->inherint_entity_risk * 100, 2) }}%</td>
  </tr>
</table>

<table border="1">
  <tr>
    <td>Riesgo Inherente</td>
  </tr>
  <tr>
    <td>Elementos de Riesgo</td>
  </tr>
  @foreach ($ebr->type->riskElements as $riskElement)
    <tr>
      <td>{{ $riskElement->order . '. ' . $riskElement->risk_element }}</td>
      <td colspan="3">{{ $riskElement->lateral_header }}</td>
      <td>Calcular 1</td>
      <td>Calcular 2</td>
      <td>Calcular 3</td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td>{{ $riskElement->sub_header }}</td>
      <td>Importe en MXN ($) asociados (Impacto)</td>
      <td>Número total de Clientes</td>
      <td>Número de Operaciones asociadas (Frecuencia)</td>
      <td>Peso impacto Rango 0:100</td>
      <td>Frecuencia Rango 0:100</td>
      <td>Riesgo Inherente por Concentración</td>
      <td>Nivel de riesgo por características presentes</td>
      <td>Riesgo Inherente Integrado</td>
    </tr>
    @foreach($riskElement->related()->where('ebr_id', $ebr->id)->get() as $related)
      <tr>
        <td>{{ $related->element }}</td>
        <td>{{ $related->amount_mxn }}</td>
        <td>{{ $related->total_clients }}</td>
        <td>{{ $related->total_operations }}</td>
        <td>{{ $related->weight_range_impact }}</td>
        <td>calcular</td>
        <td>calcular</td>
        <td>calcular</td>
        <td>calcular</td>
      </tr>
    @endforeach
    <tr>
      <td style="height: 20px"></td>
    </tr>
  @endforeach
</table>

