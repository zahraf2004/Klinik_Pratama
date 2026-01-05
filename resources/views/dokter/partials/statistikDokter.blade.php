<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fa-solid fa-calendar-check fa-2xl" style="color: #ffffff;"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Jadwal Hari Ini</h4>
                </div>
                <div class="card-body">
                    {{ isset($statistikHariIni) ? $statistikHariIni['total_hari_ini'] : 0 }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="fa-solid fa-clock fa-2xl" style="color: #ffffff;"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Menunggu Konfirmasi</h4>
                </div>
                <div class="card-body">
                    {{ isset($statistikHariIni) ? $statistikHariIni['menunggu'] : 0 }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-info">
                <i class="fa-solid fa-check-circle fa-2xl" style="color: #ffffff;"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Sudah Disetujui</h4>
                </div>
                <div class="card-body">
                    {{ isset($statistikHariIni) ? $statistikHariIni['disetujui'] : 0 }}
                </div>
            </div>
        </div>
    </div> 
</div>