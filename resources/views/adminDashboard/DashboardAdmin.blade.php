@extends('layouts.app')
@section('content')
<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>
  
  @include('adminDashboard.partials.statistic')
  @include('adminDashboard.partials.baris2')
  @include('adminDashboard.partials.baris3')
          
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  var ctx = document.getElementById("janjiChart").getContext('2d');
  var janjiChart = new Chart(ctx, {
    type: 'bar', // bisa diganti 'line' kalau mau garis
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul"], // label bulan
      datasets: [{
        label: 'Disetujui',
        data: [12, 19, 8, 15, 22, 30, 25], // contoh data dummy
        backgroundColor: '#6777ef', // warna batang
        borderColor: '#6777ef',
        borderWidth: 2,
        borderRadius: 6 // bikin rounded bar
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          labels: {
            color: '#252525'
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 5
          }
        }
      }
    }
  });
</script>
@endsection

