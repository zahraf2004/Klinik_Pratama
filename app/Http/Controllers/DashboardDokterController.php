<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenagaKesehatan;
use App\Models\Obat;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardDokterController extends Controller
{
    public function dokter()
    {
        $totalNakes = TenagaKesehatan::count();
        $totalJanji = Appointment::count();
        $totalObat = Obat::count();

        // Ambil jadwal hari ini
        $jadwalHariIni = $this->getJadwalHariIni();
        
        // Ambil riwayat janji temu terakhir
        $riwayatJanjiTemu = $this->getRiwayatJanjiTemu();

        // Statistik tambahan untuk dashboard
        $statistikHariIni = $this->getStatistikHariIni();

        return view('dokter.DashboardDokter', compact(
            'totalNakes', 
            'totalObat',
            'totalJanji',
            'jadwalHariIni',
            'riwayatJanjiTemu',
            'statistikHariIni'
        ));
    }

    /**
     * Mengambil jadwal appointment hari ini
     */
    private function getJadwalHariIni()
    {
        $today = Carbon::today();
        
        return Appointment::whereDate('tanggal', $today)
            ->whereIn('status', ['Menunggu', 'Disetujui'])
            ->orderBy('jam', 'asc')
            ->get()
            ->map(function ($appointment) {
                // Format jam untuk ditampilkan
                $jamMulai = Carbon::parse($appointment->jam)->format('H:i');
                $jamSelesai = Carbon::parse($appointment->jam)->addMinutes(30)->format('H:i');
                
                return [
                    'id' => $appointment->id,
                    'jam_range' => $jamMulai . ' - ' . $jamSelesai,
                    'nama_pasien' => $appointment->nama,
                    'status' => $appointment->status,
                    'keluhan' => $appointment->keluhan,
                    'no_hp' => $appointment->no_hp
                ];
            });
    }

    /**
     * Mengambil riwayat janji temu terakhir (5 data terbaru)
     */
    private function getRiwayatJanjiTemu()
    {
        return Appointment::whereIn('status', ['Disetujui', 'Selesai', 'Dibatalkan'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'nama_pasien' => $appointment->nama,
                    'tanggal' => Carbon::parse($appointment->tanggal)->format('d M Y'),
                    'status' => $appointment->status,
                    'keluhan' => $appointment->keluhan
                ];
            });
    }

    /**
     * API endpoint untuk mendapatkan jadwal hari ini (AJAX)
     */
    public function getJadwalHariIniApi()
    {
        $jadwal = $this->getJadwalHariIni();
        return response()->json($jadwal);
    }

    /**
     * API endpoint untuk mendapatkan riwayat janji temu (AJAX)
     */
    public function getRiwayatJanjiTemuApi()
    {
        $riwayat = $this->getRiwayatJanjiTemu();
        return response()->json($riwayat);
    }

    /**
     * Mendapatkan statistik hari ini
     */
    private function getStatistikHariIni()
    {
        $today = Carbon::today();
        
        return [
            'total_hari_ini' => Appointment::whereDate('tanggal', $today)->count(),
            'menunggu' => Appointment::whereDate('tanggal', $today)->where('status', 'Menunggu')->count(),
            'disetujui' => Appointment::whereDate('tanggal', $today)->where('status', 'Disetujui')->count(),
            'selesai' => Appointment::whereDate('tanggal', $today)->where('status', 'Selesai')->count(),
        ];
    }

    /**
     * Mendapatkan detail appointment berdasarkan ID
     */
    public function getDetailAppointment($id)
    {
        $appointment = Appointment::find($id);
        
        if (!$appointment) {
            return response()->json(['error' => 'Appointment tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $appointment->id,
            'nama' => $appointment->nama,
            'no_hp' => $appointment->no_hp,
            'tanggal_lahir' => $appointment->tanggal_lahir ? Carbon::parse($appointment->tanggal_lahir)->format('d M Y') : null,
            'alamat' => $appointment->alamat,
            'keluhan' => $appointment->keluhan,
            'tanggal' => Carbon::parse($appointment->tanggal)->format('d M Y'),
            'jam' => Carbon::parse($appointment->jam)->format('H:i'),
            'status' => $appointment->status,
            'admin_notes' => $appointment->admin_notes
        ]);
    }

    /**
     * Mendapatkan jadwal berdasarkan range tanggal
     */
    public function getJadwalByDateRange(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today());
        $endDate = $request->input('end_date', Carbon::today()->addDays(7));

        $appointments = Appointment::whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam', 'asc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'nama_pasien' => $appointment->nama,
                    'tanggal' => Carbon::parse($appointment->tanggal)->format('d M Y'),
                    'jam' => Carbon::parse($appointment->jam)->format('H:i'),
                    'status' => $appointment->status,
                    'keluhan' => $appointment->keluhan
                ];
            });

        return response()->json($appointments);
    }

    /**
     * Mendapatkan statistik mingguan
     */
    public function getStatistikMingguan()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $statistik = [
            'total_minggu_ini' => Appointment::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->count(),
            'menunggu' => Appointment::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->where('status', 'Menunggu')->count(),
            'disetujui' => Appointment::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->where('status', 'Disetujui')->count(),
            'selesai' => Appointment::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->where('status', 'Selesai')->count(),
            'dibatalkan' => Appointment::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->where('status', 'Dibatalkan')->count(),
        ];

        return response()->json($statistik);
    }
}
