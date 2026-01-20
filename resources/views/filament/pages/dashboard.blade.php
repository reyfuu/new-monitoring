<x-filament-panels::page>
    @if($role === 'admin')
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

            .admin-dashboard {
                font-family: 'Outfit', system-ui, sans-serif;
            }

            .welcome-banner {
                background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
                padding: 1.5rem;
                border-radius: 1rem;
                color: #fff;
                margin-bottom: 1.5rem;
                box-shadow: 0 20px 40px rgba(220, 38, 38, 0.3);
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

            .content-grid {
                display: grid;
                grid-template-columns: 1.5fr 1fr;
                gap: 1.5rem;
            }

            @media (max-width: 1024px) {
                .content-grid { grid-template-columns: 1fr; }
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
                background: linear-gradient(135deg, #dc2626, #ef4444);
                border-radius: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 1rem;
            }

            .dosen-list {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .dosen-item {
                padding: 1rem;
                background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
                border: 1px solid #e5e5e5;
                border-radius: 0.75rem;
                transition: all 0.2s ease;
            }

            .dark .dosen-item {
                background: linear-gradient(135deg, rgb(51 65 85) 0%, rgb(30 41 59) 100%);
                border-color: rgb(71 85 105);
            }

            .dosen-item:hover {
                border-color: #fca5a5;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            }

            .dosen-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 0.5rem;
            }

            .dosen-name {
                font-weight: 600;
                color: #171717;
            }

            .dark .dosen-name {
                color: #fff;
            }

            .dosen-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 0.25rem;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .dosen-badge.good {
                background: var(--success-100);
                color: var(--success-600);
            }

            .dark .dosen-badge.good {
                background: rgb(13 148 136 / 0.2);
                color: rgb(94 234 212);
            }

            .dosen-badge.busy {
                background: var(--warning-100);
                color: var(--warning-600);
            }

            .dark .dosen-badge.busy {
                background: rgb(217 119 6 / 0.2);
                color: rgb(253 186 116);
            }

            .dosen-stats {
                display: flex;
                gap: 1.5rem;
                font-size: 0.875rem;
            }

            .dosen-stat-label {
                color: #737373;
            }

            .dark .dosen-stat-label {
                color: rgb(148 163 184);
            }

            .dosen-stat-value {
                font-weight: 600;
                color: #171717;
            }

            .dark .dosen-stat-value {
                color: #fff;
            }

            .workload-bar {
                height: 6px;
                background: #e5e5e5;
                border-radius: 3px;
                margin-top: 0.75rem;
                overflow: hidden;
            }

            .dark .workload-bar {
                background: rgb(71 85 105);
            }

            .workload-fill {
                height: 100%;
                background: linear-gradient(90deg, var(--primary-500), var(--primary-700));
                border-radius: 3px;
                transition: width 0.6s ease;
            }

            .workload-fill.warning {
                background: linear-gradient(90deg, var(--warning-500), var(--warning-600));
            }

            .quick-stats {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .quick-stat-item {
                padding: 1rem;
                border-radius: 0.75rem;
            }

            .quick-stat-item.success {
                background: linear-gradient(135deg, var(--success-100), #99f6e4);
            }

            .dark .quick-stat-item.success {
                background: linear-gradient(135deg, rgb(13 148 136 / 0.2), rgb(20 184 166 / 0.2));
            }

            .quick-stat-item.primary {
                background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            }

            .dark .quick-stat-item.primary {
                background: linear-gradient(135deg, rgb(30 58 138 / 0.3), rgb(29 78 216 / 0.3));
            }

            .quick-stat-label {
                font-size: 0.875rem;
                font-weight: 600;
                margin-bottom: 0.25rem;
            }

            .quick-stat-item.success .quick-stat-label { color: var(--success-600); }
            .quick-stat-item.primary .quick-stat-label { color: var(--primary-700); }

            .dark .quick-stat-item.success .quick-stat-label { color: rgb(94 234 212); }
            .dark .quick-stat-item.primary .quick-stat-label { color: rgb(147 197 253); }

            .quick-stat-value {
                font-size: 1.75rem;
                font-weight: 800;
            }

            .quick-stat-item.success .quick-stat-value { color: var(--success-600); }
            .quick-stat-item.primary .quick-stat-value { color: var(--primary-700); }

            .dark .quick-stat-item.success .quick-stat-value { color: rgb(94 234 212); }
            .dark .quick-stat-item.primary .quick-stat-value { color: rgb(147 197 253); }

            .empty-text {
                color: #737373;
                text-align: center;
                padding: 2rem;
            }

            .dark .empty-text {
                color: rgb(148 163 184);
            }
        </style>

        <div class="admin-dashboard">
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <h2>👋 Selamat Datang, {{ $userName }}</h2>
                <p>Dashboard Administrasi Monitoring</p>
            </div>

            <!-- Metrics Grid -->
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-icon">👥</div>
                    <div class="metric-label">Total Pengguna Sistem</div>
                    <div class="metric-value">{{ $totalUsers }}</div>
                    <div class="metric-subtitle">Semua pengguna aktif</div>
                </div>

                <div class="metric-card success">
                    <div class="metric-icon">✅</div>
                    <div class="metric-label">Mahasiswa On Track</div>
                    <div class="metric-value">{{ $mahasiswaOnTrack }}</div>
                    <div class="metric-subtitle">{{ $onTrackPercent }}% dari total</div>
                </div>

                <div class="metric-card warning">
                    <div class="metric-icon">⚠️</div>
                    <div class="metric-label">Mahasiswa At Risk</div>
                    <div class="metric-value">{{ $mahasiswaAtRisk }}</div>
                    <div class="metric-subtitle">{{ $atRiskPercent }}% dari total</div>
                </div>

                <div class="metric-card danger">
                    <div class="metric-icon">🔴</div>
                    <div class="metric-label">Mahasiswa Overdue</div>
                    <div class="metric-value">{{ $mahasiswaOverdue }}</div>
                    <div class="metric-subtitle">{{ $overduePercent }}% dari total</div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Dosen Workload -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="card-title-icon">👥</span>
                            Beban Kerja Dosen
                        </h3>
                    </div>

                    <div class="dosen-list">
                        @forelse($dosenList as $dosen)
                            @php
                                $maxMahasiswa = 15;
                                $workloadPercent = min(($dosen->mahasiswa_bimbingan_count / $maxMahasiswa) * 100, 100);
                                $isBusy = $workloadPercent > 60;
                            @endphp
                            <div class="dosen-item">
                                <div class="dosen-header">
                                    <div class="dosen-name">{{ $dosen->name }}</div>
                                    <div class="dosen-badge {{ $isBusy ? 'busy' : 'good' }}">
                                        {{ $isBusy ? '⚠️ Sibuk' : '✓ Baik' }}
                                    </div>
                                </div>
                                <div class="dosen-stats">
                                    <div>
                                        <span class="dosen-stat-label">Mahasiswa: </span>
                                        <span class="dosen-stat-value">{{ $dosen->mahasiswa_bimbingan_count }} mhs</span>
                                    </div>
                                </div>
                                <div class="workload-bar">
                                    <div class="workload-fill {{ $isBusy ? 'warning' : '' }}" style="width: {{ $workloadPercent }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="empty-text">Belum ada data dosen</p>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="card-title-icon">📊</span>
                            Statistik Ringkas
                        </h3>
                    </div>

                    <div class="quick-stats">
                        <div class="quick-stat-item primary">
                            <div class="quick-stat-label">Total Mahasiswa</div>
                            <div class="quick-stat-value">{{ $totalMahasiswa }}</div>
                        </div>

                        <div class="quick-stat-item success">
                            <div class="quick-stat-label">Total Dosen Pembimbing</div>
                            <div class="quick-stat-value">{{ $totalDosen }}</div>
                        </div>

                        <div class="quick-stat-item success">
                            <div class="quick-stat-label">Total Bimbingan</div>
                            <div class="quick-stat-value">{{ $totalBimbingan }}</div>
                        </div>

                        <div class="quick-stat-item success">
                            <div class="quick-stat-label">Bimbingan Selesai</div>
                            <div class="quick-stat-value">{{ $bimbinganSelesai }}</div>
                        </div>

                        <div class="quick-stat-item primary">
                            <div class="quick-stat-label">Bimbingan Review</div>
                            <div class="quick-stat-value">{{ $bimbinganReview }}</div>
                        </div>


                        <div class="quick-stat-item success">
                            <div class="quick-stat-label">Laporan Proposal</div>
                            <div class="quick-stat-value">{{ $laporanProposal }}</div>
                        </div>

                        <div class="quick-stat-item success">
                            <div class="quick-stat-label">Laporan Magang</div>
                            <div class="quick-stat-value">{{ $laporanMagang }}</div>
                        </div>

                        <div class="quick-stat-item success">
                            <div class="quick-stat-label">Laporan Skripsi</div>
                            <div class="quick-stat-value">{{ $laporanSkripsi }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div style="padding: 2rem; text-align: center;">
            <h2>Welcome, {{ $userName }}</h2>
            <p>Please contact administrator for access.</p>
        </div>
    @endif
</x-filament-panels::page>
