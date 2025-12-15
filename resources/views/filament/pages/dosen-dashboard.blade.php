<x-filament-panels::page>
    <style>
        :root {
            --primary-500: #3b82f6;
            --primary-700: #1d4ed8;
            --success-500: #14b8a6;
            --success-600: #0d9488;
            --success-100: #ccfbf1;
            --warning-500: #f59e0b;
            --warning-600: #d97706;
            --warning-100: #fef3c7;
            --danger-500: #ef4444;
            --danger-600: #dc2626;
            --danger-100: #fee2e2;
        }

        .dosen-dashboard {
            font-family: 'Outfit', system-ui, sans-serif;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            padding: 1.5rem;
            border-radius: 1rem;
            color: #fff;
            margin-bottom: 1.5rem;
            box-shadow: 0 20px 40px rgba(5, 150, 105, 0.3);
        }

        .welcome-banner h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .welcome-banner p {
            opacity: 0.9;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 1200px) {
            .metrics-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .metrics-grid { grid-template-columns: 1fr; }
        }

        .metric-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border: 1px solid #e5e5e5;
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary-500);
        }

        .dark .metric-card {
            background: rgb(30 41 59);
            border-color: rgb(51 65 85);
        }

        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }

        .metric-card.success { border-top-color: var(--success-500); }
        .metric-card.warning { border-top-color: var(--warning-500); }
        .metric-card.danger { border-top-color: var(--danger-500); }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .metric-card .metric-icon { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
        .metric-card.success .metric-icon { background: linear-gradient(135deg, var(--success-100), #99f6e4); }
        .metric-card.warning .metric-icon { background: linear-gradient(135deg, var(--warning-100), #fde68a); }
        .metric-card.danger .metric-icon { background: linear-gradient(135deg, var(--danger-100), #fecaca); }

        .dark .metric-card .metric-icon { background: linear-gradient(135deg, rgb(30 58 138), rgb(29 78 216)); }
        .dark .metric-card.success .metric-icon { background: linear-gradient(135deg, rgb(13 148 136 / 0.3), rgb(20 184 166 / 0.3)); }
        .dark .metric-card.warning .metric-icon { background: linear-gradient(135deg, rgb(217 119 6 / 0.3), rgb(245 158 11 / 0.3)); }
        .dark .metric-card.danger .metric-icon { background: linear-gradient(135deg, rgb(220 38 38 / 0.3), rgb(239 68 68 / 0.3)); }

        .metric-label {
            font-size: 0.75rem;
            color: #737373;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dark .metric-label {
            color: rgb(148 163 184);
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
            color: #171717;
            margin: 0.5rem 0;
        }

        .dark .metric-value {
            color: #fff;
        }

        .metric-subtitle {
            font-size: 0.875rem;
            color: #737373;
        }

        .dark .metric-subtitle {
            color: rgb(148 163 184);
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 1024px) {
            .charts-grid { grid-template-columns: 1fr; }
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border: 1px solid #e5e5e5;
        }

        .dark .card {
            background: rgb(30 41 59);
            border-color: rgb(51 65 85);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f5f5f5;
        }

        .dark .card-header {
            border-bottom-color: rgb(51 65 85);
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #171717;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dark .card-title {
            color: #fff;
        }

        .card-title-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #059669, #10b981);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1rem;
        }

        .chart-container {
            position: relative;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mahasiswa-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .mahasiswa-item {
            padding: 1rem;
            background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
            border: 1px solid #e5e5e5;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark .mahasiswa-item {
            background: linear-gradient(135deg, rgb(51 65 85) 0%, rgb(30 41 59) 100%);
            border-color: rgb(71 85 105);
        }

        .mahasiswa-item:hover {
            border-color: #6ee7b7;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .mahasiswa-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .mahasiswa-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #059669, #10b981);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
        }

        .mahasiswa-name {
            font-weight: 600;
            color: #171717;
        }

        .dark .mahasiswa-name {
            color: #fff;
        }

        .mahasiswa-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            background: var(--success-100);
            color: var(--success-600);
        }

        .dark .mahasiswa-badge {
            background: rgb(13 148 136 / 0.2);
            color: rgb(94 234 212);
        }

        .empty-text {
            color: #737373;
            text-align: center;
            padding: 2rem;
        }

        .dark .empty-text {
            color: rgb(148 163 184);
        }
    </style>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="dosen-dashboard">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h2>👋 Selamat Datang, {{ $userName }}</h2>
            <p>Dashboard Monitoring Bimbingan & Skripsi</p>
        </div>

        <!-- Metrics Grid -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon">👥</div>
                <div class="metric-label">Total Mahasiswa</div>
                <div class="metric-value">{{ $totalMahasiswa }}</div>
                <div class="metric-subtitle">Mahasiswa bimbingan</div>
            </div>

            <div class="metric-card warning">
                <div class="metric-icon">⏳</div>
                <div class="metric-label">Perlu Review</div>
                <div class="metric-value">{{ $bimbinganReview }}</div>
                <div class="metric-subtitle">Menunggu review</div>
            </div>

            <div class="metric-card success">
                <div class="metric-icon">✅</div>
                <div class="metric-label">Bimbingan Selesai</div>
                <div class="metric-value">{{ $bimbinganSelesai }}</div>
                <div class="metric-subtitle">Terverifikasi</div>
            </div>

            <div class="metric-card">
                <div class="metric-icon">📄</div>
                <div class="metric-label">Total Laporan</div>
                <div class="metric-value">{{ $totalLaporan }}</div>
                <div class="metric-subtitle">Laporan dibimbing</div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <!-- Pie Chart: Status Bimbingan -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="card-title-icon">📊</span>
                        Status Bimbingan
                    </h3>
                </div>
                <div class="chart-container">
                    <canvas id="statusBimbinganChart"></canvas>
                </div>
            </div>

            <!-- Pie Chart: Jenis Laporan -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="card-title-icon">📚</span>
                        Jenis Laporan
                    </h3>
                </div>
                <div class="chart-container">
                    <canvas id="jenisLaporanChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Mahasiswa List -->
        <div class="content-grid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="card-title-icon">🎓</span>
                        Mahasiswa Bimbingan
                    </h3>
                </div>

                <div class="mahasiswa-list">
                    @forelse($mahasiswaList as $mahasiswa)
                        <div class="mahasiswa-item">
                            <div class="mahasiswa-info">
                                <div class="mahasiswa-avatar">
                                    {{ strtoupper(substr($mahasiswa->name, 0, 1)) }}
                                </div>
                                <div class="mahasiswa-name">{{ $mahasiswa->name }}</div>
                            </div>
                            <div class="mahasiswa-badge">
                                {{ $mahasiswa->bimbingans_count }} bimbingan
                            </div>
                        </div>
                    @empty
                        <p class="empty-text">Belum ada mahasiswa bimbingan</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status Bimbingan Pie Chart
            const statusData = @json($statusBimbinganData);
            const statusCtx = document.getElementById('statusBimbinganChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Review', 'Fix', 'ACC', 'Selesai'],
                    datasets: [{
                        data: [statusData.review, statusData.fix, statusData.acc, statusData.selesai],
                        backgroundColor: [
                            '#f59e0b',
                            '#3b82f6',
                            '#8b5cf6',
                            '#10b981'
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    }
                }
            });

            // Jenis Laporan Pie Chart
            const laporanData = @json($jenisLaporanData);
            const laporanCtx = document.getElementById('jenisLaporanChart').getContext('2d');
            new Chart(laporanCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Skripsi', 'PKL', 'Magang'],
                    datasets: [{
                        data: [laporanData.skripsi, laporanData.pkl, laporanData.magang],
                        backgroundColor: [
                            '#6366f1',
                            '#14b8a6',
                            '#f97316'
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-filament-panels::page>
