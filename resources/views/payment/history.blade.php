@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Riwayat Pembayaran</h4>
                    <a href="{{ route('payment.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Pembayaran Baru
                    </a>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Deskripsi</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <code>{{ $transaction->order_id }}</code>
                                        </td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>
                                            <strong>Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            @if($transaction->payment_type)
                                                <span class="badge bg-info">{{ ucfirst($transaction->payment_type) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->isSuccess())
                                                <span class="badge bg-success">Berhasil</span>
                                            @elseif($transaction->isPending())
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($transaction->isFailed())
                                                <span class="badge bg-danger">Gagal</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($transaction->transaction_status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if($transaction->isSuccess())
                                                    <a href="{{ route('payment.success', $transaction->order_id) }}" 
                                                       class="btn btn-outline-success" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @elseif($transaction->isFailed())
                                                    <a href="{{ route('payment.failed', $transaction->order_id) }}" 
                                                       class="btn btn-outline-danger" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-outline-secondary" disabled title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                @endif
                                                
                                                @if($transaction->isPending() || $transaction->isFailed())
                                                    <button class="btn btn-outline-primary" 
                                                            onclick="retryPayment('{{ $transaction->order_id }}', {{ $transaction->gross_amount }}, '{{ $transaction->description }}')"
                                                            title="Coba Lagi">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada riwayat pembayaran</h5>
                            <p class="text-muted">Mulai lakukan pembayaran pertama Anda</p>
                            <a href="{{ route('payment.index') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Pembayaran Baru
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Summary Cards -->
            @if($transactions->count() > 0)
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h5>{{ $transactions->where('transaction_status', 'settlement')->count() }}</h5>
                            <p class="mb-0">Berhasil</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h5>{{ $transactions->where('transaction_status', 'pending')->count() }}</h5>
                            <p class="mb-0">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                            <h5>{{ $transactions->whereIn('transaction_status', ['deny', 'cancel', 'expire', 'failure'])->count() }}</h5>
                            <p class="mb-0">Gagal</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                            <h5>Rp {{ number_format($transactions->where('transaction_status', 'settlement')->sum('gross_amount'), 0, ',', '.') }}</h5>
                            <p class="mb-0">Total Berhasil</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function retryPayment(orderId, amount, description) {
    if (confirm('Apakah Anda ingin mencoba pembayaran ulang?')) {
        // Redirect to payment page with pre-filled data
        const url = new URL('{{ route("payment.index") }}');
        url.searchParams.set('amount', amount);
        url.searchParams.set('description', description);
        url.searchParams.set('retry', orderId);
        
        window.location.href = url.toString();
    }
}
</script>

<style>
.card {
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border: none;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

code {
    background-color: #f8f9fa;
    color: #e83e8c;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
}

.summary-card {
    transition: transform 0.2s;
}

.summary-card:hover {
    transform: translateY(-2px);
}
</style>
@endsection