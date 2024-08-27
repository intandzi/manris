<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontrol Risiko</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
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

        .center {
            text-align: center;
        }

        .red {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }

        .yellow {
            background-color: #ffc107;
            color: black;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>KONTROL RISIKO</h2>
        <div class="profile-info">
            <p><strong>Unit Kerja:</strong> Prodi Teknik Informatika</p>
            <p><strong>Pemilik Risiko:</strong> Aidil Primasetya Armin, S.Kom., M.T.</p>
            <p><strong>KPI:</strong> Target Kenaikan Mahasiswa Baru 30%</p>
            <p><strong>Periode:</strong> 2024</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Risiko</th>
                    <th>Tanggal Kontrol</th>
                    <th class="center">RPN</th>
                    <th class="center">Efektivitas Kontrol</th>
                    <th class="center">Jenis Perlakuan</th>
                    <th>Rencana Perlakuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kpis->konteks as $konteks)
                    @foreach ($konteks->risk as $risk)
                        @foreach ($risk->controlRisk as $controlRisk)
                            @if ($controlRisk->controlRisk_isControl)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $risk->risk_riskDesc }}</td>
                                    <td>{{ $controlRisk->controlRisk_tanggalKontrol }}</td>
                                    <td class="center {{ $controlRisk->controlRisk_RPN >= 400 ? 'red' : 'yellow' }}">
                                        {{ $controlRisk->controlRisk_RPN }}
                                    </td>
                                    <td class="center">
                                        @if ($controlRisk->efektifitasControl)
                                            {{ $controlRisk->efektifitasControl->efektifitasKontrol_totalEfektifitas }}
                                        @else
                                            - (Data tidak tersedia)
                                        @endif
                                    </td>
                                    <td class="center">
                                        @if ($controlRisk->perlakuanRisiko)
                                            {{ $controlRisk->perlakuanRisiko->jenisPerlakuan->jenisPerlakuan_nama }}
                                        @else
                                            - (Data tidak tersedia)
                                        @endif
                                    </td>
                                    <td>
                                        @if ($controlRisk->perlakuanRisiko && $controlRisk->perlakuanRisiko->rencanaPerlakuan)
                                            @foreach ($controlRisk->perlakuanRisiko->rencanaPerlakuan as $rencana)
                                                - {{ $rencana->rencanaPerlakuan_desc }}<br>
                                            @endforeach
                                        @else
                                            - (Data tidak tersedia)
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>


    </div>

</body>

</html>
