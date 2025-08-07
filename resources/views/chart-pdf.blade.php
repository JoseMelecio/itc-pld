<!-- resources/views/chart-pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
  <title>Gráfico PDF</title>
  <style>
    body { font-family: sans-serif; text-align: center; }
    img { max-width: 100%; height: auto; }
  </style>
</head>
<body>
<h2>Reporte con Gráfico</h2>
<img src="{{ $chartImage }}" alt="Gráfico de Dispersión">
</body>
</html>
