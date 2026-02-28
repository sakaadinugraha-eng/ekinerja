<?php

use Livewire\Volt\Component;
use App\Models\Activity;
use Carbon\Carbon;

new class extends Component {
    public function with(): array
    {
        $user = auth()->user();
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $activities = Activity::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->get();

        $totalMenit = $activities->sum('menit');
        $totalJam = ($totalMenit > 0) ? round($totalMenit / 60, 1) : 0;
        
        // Logika Capaian Target
        $hariKerjaSdhLewat = 0;
        $dateRange = Carbon::now()->startOfMonth();
        while ($dateRange <= Carbon::now()) {
            if (!$dateRange->isWeekend()) $hariKerjaSdhLewat++;
            $dateRange->addDay();
        }
        
        $targetMenitBulanIni = $hariKerjaSdhLewat * 465;
        $persentaseCapaian = ($targetMenitBulanIni > 0) ? round(($totalMenit / $targetMenitBulanIni) * 100) : 0;

        // --- LOGIKA GRAFIK (7 HARI TERAKHIR) ---
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $chartLabels[] = $day->translatedFormat('d M');
            $chartData[] = Activity::where('user_id', $user->id)
                            ->whereDate('tanggal', $day->format('Y-m-d'))
                            ->sum('menit');
        }

        return [
            'totalJam' => $totalJam,
            'jumlahKegiatan' => $activities->count(),
            'persentaseCapaian' => min(100, $persentaseCapaian),
            'recentActivities' => $activities->sortByDesc('tanggal')->sortByDesc('created_at')->take(5),
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ];
    }
}; ?>

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="h-14 w-14 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Durasi</p>
                <p class="text-2xl font-black text-indigo-900">{{ $totalJam }} <span class="text-xs font-normal text-gray-400">Jam</span></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="h-14 w-14 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2l4-4"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Kegiatan</p>
                <p class="text-2xl font-black text-emerald-900">{{ $jumlahKegiatan }} <span class="text-xs font-normal text-gray-400">Item</span></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Capaian Target</p>
            <div class="flex items-end justify-between mb-2">
                <p class="text-2xl font-black text-orange-600">{{ $persentaseCapaian }}%</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase italic">Bulan Ini</p>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="bg-orange-500 h-2 rounded-full transition-all duration-1000" style="width: {{ $persentaseCapaian }}%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="font-black text-gray-800 uppercase tracking-tighter text-sm mb-6 flex items-center gap-2">
            <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
            Tren Menit Kerja (7 Hari Terakhir)
        </h3>
        <div class="h-72 w-full">
            <canvas id="kinerjaChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="font-black text-gray-800 uppercase tracking-tighter text-sm">Log Aktivitas Terbaru</h3>
            <a href="{{ route('kinerja.index') }}" class="text-[10px] font-black text-indigo-600 hover:underline uppercase tracking-widest">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Nama Kegiatan</th>
                        <th class="px-6 py-4 text-right">Durasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentActivities as $act)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 text-xs font-bold text-gray-500">
                            {{ \Carbon\Carbon::parse($act->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-800">{{ $act->nama_kegiatan }}</p>
                            <p class="text-[10px] text-gray-400 italic leading-none">{{ Str::limit($act->keterangan, 45) }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                {{ $act->menit }} m
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-400 italic text-sm">Belum ada aktivitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT CHART.JS --}}
@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script
<script>
    const ctx = document.getElementById('kinerjaChart');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Menit Kerja',
                data: @json($chartData),
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e1b4b',
                    titleFont: { size: 10 },
                    bodyFont: { size: 12, weight: 'bold' },
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' Menit';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: { font: { size: 10, weight: 'bold' }, color: '#9ca3af' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: 'bold' }, color: '#9ca3af' }
                }
            }
        }
    });
</script>
@endscript