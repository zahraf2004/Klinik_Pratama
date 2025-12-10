@extends('layouts.app')

@section('title', 'Pembayaran Gagal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white text-center">
                    <i class="fas fa-times-circle fa-3x mb-3"></i>
                    <h3 class="mb-0">Pembayaran Gagal</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <p class="lead">Maaf, pembayaran Anda tidak dapat diproses.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Detail Transaksi</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Order ID:</strong></td>
                                    <td>{{ $transaction->order_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah:</strong></td>
                                    <td><strong>Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ ucfirst($transaction->transaction_status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal:</strong></td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Deskripsi</h5>
                            <p class="bg-light p-3 rounded">{{ $transaction->description }}</p>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Kemungkinan Penyebab:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Saldo tidak mencukupi</li>
                                    <li>Kartu kredit/debit bermasalah</li>
                                    <li>Koneksi internet terputus</li>
                                    <li>Pembayaran dibatalkan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('payment.index') }}" class="btn btn-primary me-2">
                            <i class="fas fa-redo me-2"></i>Coba Lagi
                        </a>
                        <a href="{{ route('payment.history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-history me-2"></i>Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Butuh Bantuan?</h5>
                </div>
                <div class="card-body">
                    <p>Jika Anda mengalami kesulitan dengan pembayaran, silakan hubungi customer service kami:</p>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <i class="fas fa-phone fa-2x text-primary mb-2"></i>
                            <p><strong>Telepon</strong><br>(021) 123-4567</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                            <p><strong>Email</strong><br>support@klinik.com</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-comments fa-2x text-primary mb-2"></i>
                            <p><strong>Live Chat</strong><br>24/7 Online</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.table td {
    padding: 0.5rem 0;
}

.alert {
    border: none;
    border-radius: 10px;
}

.btn {
    border-radius: 25px;
    padding: 10px 25px;
}

.alert ul {
    padding-left: 1.2rem;
}
</style>
@endsection