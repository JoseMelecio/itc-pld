<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resultado Busqueda PLD</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 20px;">
<!-- Encabezado -->
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
  <tr>
    <td style="text-align: left; vertical-align: top;">
      <h2 style="margin: 0;">
        Coincidencias personas y entidades bloqueadas
      </h2>
      <p style="margin: 0; font-size: 14px;">
        <br>Fecha de busqueda: <strong>{{ \Carbon\Carbon::now() }}</strong>
      </p>
    </td>
  </tr>
</table>

<!-- Tabla de productos -->
<table width="100%" border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; text-align: left;">
  <thead style="background-color: #f5f5f5;">
  <tr>
    <th style="border: 1px solid #ddd;">&nbsp;</th>
    <th style="border: 1px solid #ddd;">Nombre</th>
    <th style="border: 1px solid #ddd;">Alias</th>
    <th style="border: 1px solid #ddd;">Feche Nac/Const</th>
    <th style="border: 1px solid #ddd;">Coincidencia</th>
  </tr>
  </thead>
  <tbody>
  @foreach($data as $index => $result)
    <tr>
      <td style="border: 1px solid #ddd;">{{ $index + 1 }}</td>
      <td style="border: 1px solid #ddd;">
        {{ $result['name'] }}
      </td>
      <td style="border: 1px solid #ddd;">
        {{ $result['alias'] }}
      </td>
      <td style="border: 1px solid #ddd;">
        {{ $result['date'] }}
      </td>
      <td style="border: 1px solid #ddd;">
        {{ $result['result'] }}
      </td>
    </tr>
  @endforeach
  </tbody>
</table>

</body>
</html>
