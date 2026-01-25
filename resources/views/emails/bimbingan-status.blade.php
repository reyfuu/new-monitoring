<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Bimbingan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header.disetujui {
            border-bottom: 2px solid #10b981;
        }
        .header.revisi {
            border-bottom: 2px solid #f59e0b;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header.disetujui h1 {
            color: #10b981;
        }
        .header.revisi h1 {
            color: #f59e0b;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 10px;
        }
        .status-badge.disetujui {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-badge.revisi {
            background-color: #fef3c7;
            color: #92400e;
        }
        .content {
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #f8fafc;
            border-left: 4px solid #64748b;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
        }
        .info-box.komentar {
            border-left-color: #f59e0b;
            background-color: #fffbeb;
        }
        .info-label {
            font-weight: 600;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .info-value {
            color: #1e293b;
            font-size: 16px;
        }
        .komentar-box {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .komentar-box .info-label {
            color: #92400e;
        }
        .komentar-box .info-value {
            color: #78350f;
            font-style: italic;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $status }}">
            @if($status === 'disetujui')
                <h1>✅ Bimbingan Disetujui</h1>
            @else
                <h1>📝 Bimbingan Perlu Revisi</h1>
            @endif
            <span class="status-badge {{ $status }}">
                {{ $status === 'disetujui' ? 'DISETUJUI' : 'REVISI' }}
            </span>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $mahasiswa->name }}</strong>,</p>
            
            <p>Bimbingan Anda telah ditinjau oleh dosen pembimbing dengan hasil:</p>

            <div class="info-box">
                <div class="info-label">Topik Bimbingan</div>
                <div class="info-value">{{ $bimbingan->topik }}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Jenis Bimbingan</div>
                <div class="info-value">{{ ucfirst($bimbingan->type) }}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Tanggal</div>
                <div class="info-value">{{ $bimbingan->tanggal->format('d F Y') }}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Dosen Pembimbing</div>
                <div class="info-value">{{ $dosen->name }}</div>
            </div>

            @if($komentar)
            <div class="komentar-box">
                <div class="info-label">💬 Komentar dari Dosen</div>
                <div class="info-value">"{{ $komentar }}"</div>
            </div>
            @endif

            @if($status === 'disetujui')
                <p>Selamat! Bimbingan Anda telah disetujui. Silakan lanjutkan ke tahap berikutnya.</p>
            @else
                <p>Mohon perhatikan komentar dari dosen dan lakukan perbaikan sesuai arahan.</p>
            @endif
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh Sistem Monitoring Bimbingan.</p>
            <p>Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
