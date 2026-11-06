<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Pengaduan Sarana Sekolah</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000000;
            background-color: #ffffff;
            margin: 0;
            padding: 10px;
            font-size: 11pt;
            line-height: 1.3;
        }

        /* Kop Surat Generik */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000000;
            padding-bottom: 8px;
            margin-bottom: 16px;
            position: relative;
        }

        .kop-surat h4 {
            margin: 0;
            font-size: 12pt;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .kop-surat h2 {
            margin: 2px 0;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-surat p {
            margin: 0;
            font-size: 9.5pt;
            font-style: italic;
        }

        /* Report Title */
        .report-title {
            text-align: center;
            margin-bottom: 16px;
        }

        .report-title h3 {
            margin: 0;
            font-size: 13pt;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .report-title p {
            margin: 4px 0 0 0;
            font-size: 10pt;
        }

        /* Summary Info */
        .summary-box {
            display: flex;
            justify-content: space-between;
            font-size: 10pt;
            margin-bottom: 12px;
            border-bottom: 1px solid #cccccc;
            padding-bottom: 6px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 24px;
        }

        th, td {
            border: 1px solid #000000;
            padding: 6px 8px;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0 !important;
            font-weight: bold;
            text-align: center;
        }

        /* Signature Section */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 250px;
            text-align: center;
            font-size: 10.5pt;
        }

        .signature-space {
            height: 65px;
        }

        .print-btn-bar {
            background-color: #350713;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-radius: 6px;
            font-family: sans-serif;
        }

        .btn-print {
            background-color: #ffffff;
            color: #350713;
            border: none;
            padding: 8px 18px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-back {
            background-color: transparent;
            color: #ffffff;
            border: 1px solid #ffffff;
            padding: 7px 14px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Aksi (Hanya Tampil di Layar / Tidak Tercetak) -->
    <div class="print-btn-bar no-print">
        <div>
            <strong>Mode Cetak Laporan Rekapitulasi Sarpras</strong>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.reports.index') }}" class="btn-back">&larr; Kembali ke Filter</a>
            <button onclick="window.print()" class="btn-print">&#128438; Cetak / Simpan PDF</button>
        </div>
    </div>

    <!-- Kop Surat Generik -->
    <div class="kop-surat">
        <h4>DINAS PENDIDIKAN &amp; KEBUDAYAAN</h4>
        <h2>SISTEM SARANA &amp; PRASARANA SEKOLAH</h2>
        <p>Laporan Rekapitulasi Pengaduan &amp; Pemeliharaan Fasilitas Sekolah</p>
    </div>

    <!-- Judul Laporan -->
    <div class="report-title">
        <h3>LAPORAN REKAPITULASI PENGADUAN SARPRAS</h3>
        <p>
            Periode: 
            <strong>
                {{ !empty($filterInfo['start_date']) ? \Carbon\Carbon::parse($filterInfo['start_date'])->format('d/m/Y') : 'Semua Data' }}
                s/d 
                {{ !empty($filterInfo['end_date']) ? \Carbon\Carbon::parse($filterInfo['end_date'])->format('d/m/Y') : 'Sekarang' }}
            </strong>
        </p>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="summary-box">
        <div>
            <strong>Total Aduan:</strong> {{ $stats['total'] }} &nbsp;|&nbsp;
            <strong>Menunggu:</strong> {{ $stats['pending'] }} &nbsp;|&nbsp;
            <strong>Proses:</strong> {{ $stats['in_progress'] }} &nbsp;|&nbsp;
            <strong>Selesai:</strong> {{ $stats['resolved'] }} &nbsp;|&nbsp;
            <strong>Ditolak:</strong> {{ $stats['rejected'] }}
        </div>
        <div>
            <em>Dicetak pada: {{ date('d/m/Y H:i') }} WIB</em>
        </div>
    </div>

    <!-- Tabel Data Laporan -->
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 110px;">No. Tiket</th>
                <th style="width: 80px;">Tgl Lapor</th>
                <th style="width: 140px;">Nama Pelapor</th>
                <th style="width: 130px;">Kategori Sarana</th>
                <th style="width: 130px;">Lokasi Ruang</th>
                <th>Uraian Kerusakan</th>
                <th style="width: 95px;">Kondisi</th>
                <th style="width: 85px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $index => $c)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold; text-align: center;">{{ $c->ticket_number }}</td>
                    <td style="text-align: center;">{{ $c->created_at->format('d/m/Y') }}</td>
                    <td>{{ $c->user->name }}</td>
                    <td>{{ $c->category->name }}</td>
                    <td>{{ $c->location->name }}</td>
                    <td>
                        <strong>{{ $c->title }}</strong>
                        <div style="font-size: 8.5pt; color: #333333;">{{ Str::limit($c->description, 70) }}</div>
                    </td>
                    <td style="text-align: center;">{{ $c->urgency_label }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $c->status_label }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">
                        Tidak ada data laporan pengaduan pada kriteria ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Pengesahan Generik -->
    <div class="signature-section">
        <div class="signature-box">
            <div>Mengetahui,</div>
            <div><strong>Kepala Sekolah</strong></div>
            <div class="signature-space"></div>
            <div><strong><u>( .................................................. )</u></strong></div>
            <div>NIP. ..................................................</div>
        </div>

        <div class="signature-box">
            <div>{{ date('d F Y') }}</div>
            <div><strong>Koordinator Sarpras</strong></div>
            <div class="signature-space"></div>
            <div><strong><u>( .................................................. )</u></strong></div>
            <div>NIP. ..................................................</div>
        </div>
    </div>

</body>
</html>
