<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Baru</title>
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
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #4f46e5;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #f8fafc;
            border-left: 4px solid #4f46e5;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
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
        <div class="header">
            <h1>📚 Laporan Baru</h1>
        </div>

        <div class="content">
            <p>Yth. <strong>{{ $dosen->name }}</strong>,</p>

            <p>Anda menerima permintaan laporan baru dari mahasiswa:</p>

            <div class="info-box">
                <div class="info-label">Nama Mahasiswa</div>
                <div class="info-value">{{ $mahasiswa->name }}</div>
            </div>


            <div class="info-box">
                <div class="info-label">Tanggal</div>
                <div class="info-value">{{ now()->format('d F Y') }}</div>
            </div>

            <p>Silakan login ke sistem untuk memberikan respons pada laporan ini.</p>
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh Sistem Monitoring Magang dan Tugas Akhir.</p>
            <p>Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
