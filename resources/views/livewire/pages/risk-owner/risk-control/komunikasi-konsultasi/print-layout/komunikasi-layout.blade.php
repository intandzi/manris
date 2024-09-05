<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunikasi - {{ ucfirst($kpis->kpi_kode) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            /* background-color: #f4f4f4; */
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .profile-info {
            margin-bottom: 30px;
        }

        .profile-info p {
            margin: 5px 0;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border: none;
        }

        .info-table td {
            padding: 8px;
            border: none;
        }

        .center {
            text-align: center;
        }

        .rpn-low {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }

        .rpn-medium {
            background-color: #ffc107;
            color: white;
            font-weight: bold;
        }

        .rpn-high {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>KOMUNIKASI</h2>
        <table class="info-table">
            <tr>
                <td><strong>Unit Kerja</strong></td>
                <td>: {{ ucwords($unit_nama) }}</td>
            </tr>
            <tr>
                <td><strong>Pemilik Risiko</strong></td>
                <td>: {{ ucfirst($user_pemilik) }}</td>
            </tr>
            <tr>
                <td><strong>KPI</strong></td>
                <td>: {{ ucfirst($kpis->kpi_nama) }}</td>
            </tr>
            <tr>
                <td><strong>Periode</strong></td>
                <td>: {{ $kpis->kpi_periode }}</td>
            </tr>
        </table>

        @if ($kpis && $kpis->konteks->isNotEmpty())
            @php
                $hasKomunikasi = false;
                $index = 1;
            @endphp

            @foreach ($kpis->konteks as $konteks)
                @foreach ($konteks->risk as $risk)
                    @if ($risk->komunikasi->isNotEmpty() && $risk->komunikasi->first()->komunikasi_lockStatus)
                        @php
                            $hasKomunikasi = true;
                            // break 3; // Exit all loops once a controlRisk with controlRisk_isControl == 1 is found
                        @endphp
                    @endif
                @endforeach
            @endforeach

            @if ($hasKomunikasi)
                <table>
                    <thead>
                        <tr>
                            <th>Pemangku Kepentingan</th>
                            <th>Perantara</th>
                            <th>Disiapkan oleh</th>
                            <th>Tujuan</th>
                            <th>Konten</th>
                            <th>Media</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kpis->konteks as $konteks)
                            @foreach ($konteks->risk as $risk)
                                @if ($risk->risk_id == $risk_id)
                                    @foreach ($risk->komunikasi as $komunikasi)
                                        @php
                                            // Initialize arrays to group RACI data
                                            $responsible = [];
                                            $perantara = [];
                                            $fasil = [];
                                        @endphp
                                        @if ($komunikasi->komunikasi_lockStatus)
                                            @foreach ($komunikasi->komunikasiStakeholder as $item)
                                                @php
                                                    $stakeholderInfo =
                                                        ucwords($item->stakeholder->stakeholder_jabatan) .
                                                        '  (' .
                                                        strtoupper($item->stakeholder->stakeholder_singkatan) .
                                                        ')';

                                                    switch ($item->komunikasiStakeholder_ket) {
                                                        case 'stakeholder':
                                                            $responsible[] = $stakeholderInfo;
                                                            break;
                                                        case 'perantara':
                                                            $perantara[] = $stakeholderInfo;
                                                            break;
                                                        case 'fasil':
                                                            $fasil[] = $stakeholderInfo;
                                                            break;
                                                    }
                                                @endphp
                                            @endforeach
                                            @php
                                                // Set default text if no data is available
                                                if (empty($responsible)) {
                                                    $responsible[] = '(Data tidak tersedia)';
                                                }
                                                if (empty($perantara)) {
                                                    $perantara[] = '(Data tidak tersedia)';
                                                }
                                                if (empty($fasil)) {
                                                    $fasil[] = '(Data tidak tersedia)';
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    - {!! implode('<br>- ', $responsible) !!}<br>
                                                </td>
                                                <td>
                                                    - {!! implode('<br>- ', $perantara) !!}<br>
                                                </td>
                                                <td>
                                                    - {!! implode('<br>- ', $fasil) !!}<br>
                                                </td>
                                                <td>
                                                    {{ ucfirst($komunikasi->komunikasi_tujuan) }}
                                                </td>
                                                <td>
                                                    {{ ucfirst($komunikasi->komunikasi_konten) }}
                                                </td>
                                                <td>
                                                    {{ ucfirst($komunikasi->komunikasi_media) }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @else
                <div>
                    (Data tidak tersedia)
                </div>
            @endif
        @else
            <div>
                (Data tidak tersedia)
            </div>
        @endif
    </div>
</body>

</html>