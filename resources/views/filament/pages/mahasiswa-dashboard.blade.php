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
            --info-500: #8b5cf6;
            --info-600: #7c3aed;
            --info-100: #ede9fe;
        }

        .mahasiswa-dashboard {
            font-family: 'Outfit', system-ui, sans-serif;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            padding: 1.5rem;
            border-radius: 1rem;
            color: #fff;
            margin-bottom: 1.5rem;
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3);
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
        .metric-card.info { border-top-color: var(--info-500); }

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
        .metric-card.info .metric-icon { background: linear-gradient(135deg, var(--info-100), #c4b5fd); }

        .dark .metric-card .metric-icon { background: linear-gradient(135deg, rgb(30 58 138), rgb(29 78 216)); }
        .dark .metric-card.success .metric-icon { background: linear-gradient(135deg, rgb(13 148 136 / 0.3), rgb(20 184 166 / 0.3)); }
        .dark .metric-card.warning .metric-icon { background: linear-gradient(135deg, rgb(217 119 6 / 0.3), rgb(245 158 11 / 0.3)); }
        .dark .metric-card.info .metric-icon { background: linear-gradient(135deg, rgb(124 58 237 / 0.3), rgb(139 92 246 / 0.3)); }

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
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1rem;
        }

        .bimbingan-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .bimbingan-item {
            padding: 1rem;
            background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
            border: 1px solid #e5e5e5;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }

        .dark .bimbingan-item {
            background: linear-gradient(135deg, rgb(51 65 85) 0%, rgb(30 41 59) 100%);
            border-color: rgb(71 85 105);
        }

        .bimbingan-item:hover {
            border-color: #a5b4fc;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .bimbingan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .bimbingan-topik {
            font-weight: 600;
            color: #171717;
        }

        .dark .bimbingan-topik {
            color: #fff;
        }

        .bimbingan-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .bimbingan-badge.success {
            background: var(--success-100);
            color: var(--success-600);
        }

        .dark .bimbingan-badge.success {
            background: rgb(13 148 136 / 0.2);
            color: rgb(94 234 212);
        }

        .bimbingan-badge.warning {
            background: var(--warning-100);
            color: var(--warning-600);
        }

        .dark .bimbingan-badge.warning {
            background: rgb(217 119 6 / 0.2);
            color: rgb(253 186 116);
        }

        .bimbingan-date {
            font-size: 0.875rem;
            color: #737373;
        }

        .dark .bimbingan-date {
            color: rgb(148 163 184);
        }

        .dosen-card {
            padding: 1.5rem;
            background: linear-gradient(135deg, #ede9fe 0%, #c4b5fd 100%);
            border-radius: 0.75rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .dark .dosen-card {
            background: linear-gradient(135deg, rgb(124 58 237 / 0.2), rgb(139 92 246 / 0.2));
        }

        .dosen-avatar {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 1.5rem;
            color: #fff;
        }

        .dosen-name {
            font-weight: 700;
            color: #171717;
            font-size: 1rem;
        }

        .dark .dosen-name {
            color: #fff;
        }

        .dosen-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dark .dosen-label {
            color: rgb(148 163 184);
        }

        .progress-section {
            margin-top: 1rem;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .progress-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #171717;
        }

        .dark .progress-label {
            color: #fff;
        }

        .progress-percent {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--success-600);
        }

        .dark .progress-percent {
            color: rgb(94 234 212);
        }

        .progress-bar {
            height: 8px;
            background: #e5e5e5;
            border-radius: 4px;
            overflow: hidden;
        }

        .dark .progress-bar {
            background: rgb(71 85 105);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success-500), var(--success-600));
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        .empty-text {
            color: #737373;
            text-align: center;
            padding: 2rem;
        }

        .dark .empty-text {
            color: rgb(148 163 184);
        }

        .laporan-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .laporan-badge.active {
            background: var(--success-100);
            color: var(--success-600);
        }

        .dark .laporan-badge.active {
            background: rgb(13 148 136 / 0.2);
            color: rgb(94 234 212);
        }

        .laporan-badge.inactive {
            background: #f5f5f5;
            color: #737373;
        }

        .dark .laporan-badge.inactive {
            background: rgb(51 65 85);
            color: rgb(148 163 184);
        }
    </style>

    <div class="mahasiswa-dashboard">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h2>👋 Selamat Datang, {{ $userName }}</h2>
            <p>Dashboard Monitoring Bimbingan Anda</p>
        </div>

        <!-- Metrics Grid -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon">📋</div>
                <div class="metric-label">Total Bimbingan</div>
                <div class="metric-value">{{ $totalBimbingan }}</div>
                <div class="metric-subtitle">Sesi bimbingan</div>
            </div>

            <div class="metric-card success">
                <div class="metric-icon">✅</div>
                <div class="metric-label">Terverifikasi</div>
                <div class="metric-value">{{ $bimbinganTerverifikasi }}</div>
                <div class="metric-subtitle">Disetujui dosen</div>
            </div>

            <div class="metric-card warning">
                <div class="metric-icon">⏳</div>
                <div class="metric-label">Menunggu Review</div>
                <div class="metric-value">{{ $bimbinganMenunggu }}</div>
                <div class="metric-subtitle">Belum di-review</div>
            </div>

            <div class="metric-card info">
                <div class="metric-icon">📄</div>
                <div class="metric-label">Total Laporan</div>
                <div class="metric-value">{{ $totalLaporan }}</div>
                <div class="metric-subtitle">Laporan aktif</div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Bimbingan Terakhir -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="card-title-icon">📝</span>
                        Bimbingan Terakhir
                    </h3>
                </div>

                <div class="bimbingan-list">
                    @forelse($bimbinganTerakhir as $bimbingan)
                        @php
                            $isVerified = in_array($bimbingan->status_domen, ['fix', 'acc', 'selesai']);
                        @endphp
                        <div class="bimbingan-item">
                            <div class="bimbingan-header">
                                <div class="bimbingan-topik">{{ $bimbingan->topik ?? 'Bimbingan #' . $bimbingan->id }}</div>
                                <div class="bimbingan-badge {{ $isVerified ? 'success' : 'warning' }}">
                                    {{ $isVerified ? '✓ Terverifikasi' : '⏳ Menunggu' }}
                                </div>
                            </div>
                            <div class="bimbingan-date">
                                📅 {{ $bimbingan->tanggal?->format('d M Y') ?? '-' }}
                            </div>
                        </div>
                    @empty
                        <p class="empty-text">Belum ada data bimbingan</p>
                    @endforelse
                </div>
            </div>

            <!-- Sidebar -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="card-title-icon">👨‍🏫</span>
                        Dosen Pembimbing
                    </h3>
                </div>

                @if($dosenPembimbing)
                    <div class="dosen-card">
                        <div class="dosen-avatar">👨‍🏫</div>
                        <div class="dosen-label">Dosen Pembimbing</div>
                        <div class="dosen-name">{{ $dosenPembimbing->name }}</div>
                    </div>
                @else
                    <p class="empty-text">Belum ada dosen pembimbing</p>
                @endif

                <!-- Progress -->
                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-label">Progress Bimbingan</span>
                        <span class="progress-percent">{{ $progressPercent }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>

                <!-- Laporan Status -->
                <div style="margin-top: 1.5rem;">
                    <div class="progress-label" style="margin-bottom: 0.75rem;">Status Laporan</div>
                    <div>
                        <span class="laporan-badge {{ $laporanSkripsi ? 'active' : 'inactive' }}">
                            📚 Skripsi {{ $laporanSkripsi ? '✓' : '' }}
                        </span>
                        <span class="laporan-badge {{ $laporanPkl ? 'active' : 'inactive' }}">
                            💼 PKL {{ $laporanPkl ? '✓' : '' }}
                        </span>
                        <span class="laporan-badge {{ $laporanMagang ? 'active' : 'inactive' }}">
                            🏢 Magang {{ $laporanMagang ? '✓' : '' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
