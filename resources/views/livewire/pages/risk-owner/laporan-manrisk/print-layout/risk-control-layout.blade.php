<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontrol Risiko - {{ ucfirst($kpis->kpi_kode) }}</title>
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
        <h2>KONTROL RISIKO</h2>
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
                $hasControlRisk = false;
                $index = 1;
            @endphp

            @foreach ($kpis->konteks as $konteks)
                @foreach ($konteks->risk as $risk)
                    @foreach ($risk->controlRisk as $controlRisk)
                        @if ($controlRisk->controlRisk_lockStatus == 1)
                            @php
                                $hasControlRisk = true;
                                // break 3; // Exit all loops once a controlRisk with controlRisk_isControl == 1 is found
                            @endphp
                        @endif
                    @endforeach
                @endforeach
            @endforeach

            @if ($hasControlRisk)
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
                                    <tr>
                                        @php
                                            $rpn = $controlRisk->controlRisk_RPN ?? 0;
                                            $rpnClass = '';

                                            if ($rpn >= 501 && $rpn <= 1000) {
                                                $rpnClass = 'rpn-high';
                                            } elseif ($rpn >= 251 && $rpn <= 500) {
                                                $rpnClass = 'rpn-medium';
                                            } elseif ($rpn >= 1 && $rpn <= 250) {
                                                $rpnClass = 'rpn-low';
                                            }
                                        @endphp
                                        <td>{{ $index++ }}.</td>
                                        <td>{{ $risk->risk_riskDesc }}</td>
                                        <td>{{ date('d-m-Y', strtotime($controlRisk->created_at)) }}</td>
                                        <td class="rpn {{ $rpnClass }}">{{ $rpn }}</td>
                                        <td class="center">
                                            @if ($controlRisk->efektifitasControl->isNotEmpty())
                                                @if ($controlRisk->efektifitasControl->first()->efektifitasKontrol_totalEfektifitas == 3)
                                                    <!-- Display Efektif -->
                                                    Efektif
                                                @elseif (
                                                    $controlRisk->efektifitasControl->first()->efektifitasKontrol_totalEfektifitas >= 4 &&
                                                        $controlRisk->efektifitasControl->first()->efektifitasKontrol_totalEfektifitas <= 7)
                                                    <!-- Display Sebagian Efektif -->
                                                    Sebagian Efektif
                                                @elseif (
                                                    $controlRisk->efektifitasControl->first()->efektifitasKontrol_totalEfektifitas >= 8 &&
                                                        $controlRisk->efektifitasControl->first()->efektifitasKontrol_totalEfektifitas <= 9)
                                                    <!-- Display Kurang Efektif -->
                                                    Kurang Efektif
                                                @elseif ($controlRisk->efektifitasControl->first()->efektifitasKontrol_totalEfektifitas >= 10)
                                                    <!-- Display Tidak Efektif -->
                                                    Tidak Efektif
                                                @else
                                                    <!-- Display message if no conditions are met -->
                                                    Data tidak tersedia
                                                @endif
                                            @else
                                                (Data tidak tersedia)
                                            @endif
                                        </td>
                                        <td class="center">
                                            @if ($controlRisk->perlakuanRisiko->isNotEmpty())
                                                {{ ucwords($controlRisk->perlakuanRisiko->first()->jenisPerlakuan->jenisPerlakuan_desc) }}
                                            @else
                                                (Data tidak tersedia)
                                            @endif
                                        </td>
                                        <td>
                                            @if ($controlRisk->perlakuanRisiko && $controlRisk->perlakuanRisiko->first()->rencanaPerlakuan)
                                                @foreach ($controlRisk->perlakuanRisiko->first()->rencanaPerlakuan as $rencana)
                                                    - {{ $rencana->rencanaPerlakuan_desc }}<br>
                                                @endforeach
                                            @else
                                                (Data tidak tersedia)
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
