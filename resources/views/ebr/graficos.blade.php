<!-- resources/views/chart.blade.php -->
<!DOCTYPE html>
<html>
<head>
  <title>Gráfico de Dispersión</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h1>Gráfico de Dispersión</h1>

<canvas id="scatterChart" width="600" height="400"></canvas>

<form id="pdfForm" method="POST" action="{{ route('grafico.pdf') }}">
  @csrf
  <input type="hidden" name="chart_image" id="chartImageInput">
  <button type="submit" onclick="preparePDF()">Exportar a PDF</button>
</form>

<script>
  const ctx = document.getElementById('scatterChart').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'scatter',
    data: {
      datasets: [{
        label: 'Datos de Riesgo',
        data: [
          { x: 1, y: 10 },
          { x: 2, y: 20 },
          { x: 3, y: 15 },
          { x: 4, y: 25 },
          { x: 5, y: 19 }
        ],
        backgroundColor: 'rgba(255, 99, 132, 1)',
      }]
    },
    options: {
      scales: {
        x: {
          title: { display: true, text: 'Eje X' }
        },
        y: {
          title: { display: true, text: 'Eje Y' }
        }
      }
    }
  });

  function preparePDF() {
    const canvas = document.getElementById('scatterChart');
    const imgData = canvas.toDataURL('image/png');
    document.getElementById('chartImageInput').value = imgData;
  }
</script>
</body>
</html>
