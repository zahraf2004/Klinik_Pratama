@extends('layouts.dokter')
@section('content')
<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>
  @include('dokter.partials.statistikDokter')
  @include('dokter.partials.bagian2')
          
</section>
@endsection