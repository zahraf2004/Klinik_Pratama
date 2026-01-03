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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById("janjiChart").getContext('2d');
    
    // Data dari controller
    var chartLabels = @json($chartData['labels']);
    var chartData = @json($chartData['data']);
    
    var janjiChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: chartLabels,
        datasets: [{
          label: 'Janji Selesai',
          data: chartData,
          backgroundColor: '#28a745',
          borderColor: '#28a745',
          borderWidth: 2,
          borderRadius: 6
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
          },
          title: {
            display: true,
            text: 'Statistik Janji Berobat Selesai (12 Bulan Terakhir)',
            color: '#252525'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              callback: function(value) {
                return Number.isInteger(value) ? value : '';
              }
            }
          },
          x: {
            ticks: {
              maxRotation: 45,
              minRotation: 45
            }
          }
        }
      }
    });
  });
</script>
@endpush

