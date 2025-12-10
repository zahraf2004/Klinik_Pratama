@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white text-center">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h3 class="mb-0">Pembayaran Berhasil!</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <p class="lead">Terima kasih! Pembayaran Anda telah berhasil diproses.</p>
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
                                    <td><strong>Transaction ID:</strong></td>
                                    <td>{{ $transaction->transaction_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah:</strong></td>
                                    <td><strong class="text-success">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Metode Pembayaran:</strong></td>
                                    <td>{{ ucfirst($transaction->payment_type ?? '-') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-success">
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
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Catatan:</strong> Simpan informasi ini sebagai bukti pembayaran Anda.
                            </div>
                        </div>
                    </div>

                    @if(strpos($transaction->description, 'Subscription') !== false || strpos($transaction->description, 'Berlangganan') !== false)
                        {{-- Subscription Success Actions --}}
                        <div class="alert alert-success mt-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="mb-1">
                                        <i class="fas fa-crown me-2"></i>Selamat! Anda Sekarang Premium Member
                                    </h5>
                                    <p class="mb-0">Chat unlimited dengan dokter sudah aktif. Mulai konsultasi sekarang!</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="/chatify" class="btn btn-success">
                                        <i class="fas fa-comments me-2"></i>Mulai Chat
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="/chatify" class="btn btn-success btn-lg me-2">
                                <i class="fas fa-comments me-2"></i>Mulai Chat Premium
                            </a>
                            <a href="{{ route('subscription.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-crown me-2"></i>Kelola Subscription
                            </a>
                        </div>
                    @else
                        {{-- Regular Payment Actions --}}
                        <div class="text-center mt-4">
                            <a href="{{ route('payment.history') }}" class="btn btn-primary me-2">
                                <i class="fas fa-history me-2"></i>Lihat Riwayat
                            </a>
                            <a href="{{ route('payment.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-2"></i>Pembayaran Baru
                            </a>
                        </div>
                    @endif
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
</style>
@endsection