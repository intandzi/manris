<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pematauan dan Tinjauan - {{ ucfirst($kpis->kpi_kode) }}</title>
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
        <h2>PEMANTAUAN DAN TINJAUAN</h2>
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
                $hasPemantauanTinjauan = false;
                $index = 1;
            @endphp

            @foreach ($kpis->konteks as $konteks)
                @foreach ($konteks->risk as $risk)
                    @foreach ($risk->controlRisk as $controlRisk)
                        @foreach ($controlRisk->perlakuanRisiko as $perlakuanRisiko)
                            @if ($perlakuanRisiko->pemantauanTinjauan->isNotempty() && $perlakuanRisiko->pemantauanKajian_lockStatus)
                                @php
                                    $hasPemantauanTinjauan = true;
                                    // break 3; // Exit all loops once a controlRisk with controlRisk_isControl == 1 is found
                                @endphp
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach

            @if ($hasPemantauanTinjauan)
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Kontrol</th>
                            <th class="center">RPN</th>
                            <th class="center">Jenis Perlakuan</th>
                            <th>Frekuensi pemantauan/tahun</th>
                            <th>Pemantauan</th>
                            <th>Frekuensi pelaporan/tahun</th>
                            <th>Tinjauan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kpis->konteks as $konteks)
                            @foreach ($konteks->risk as $risk)
                                @if ($risk->risk_id == $risk_id)
                                    @foreach ($risk->controlRisk as $controlRisk)
                                        @php
                                            // FIND RPN LEVELS
                                            $rpn = $controlRisk->controlRisk_RPN ?? 0;
                                            $rpnClass = '';

                                            if ($rpn >= 501 && $rpn <= 1000) {
                                                $rpnClass = 'rpn-high';
                                            } elseif ($rpn >= 251 && $rpn <= 500) {
                                                $rpnClass = 'rpn-medium';
                                            } elseif ($rpn >= 1 && $rpn <= 250) {
                                                $rpnClass = 'rpn-low';
                                            }

                                            // Get the first PerlakuanRisiko related to the ControlRisk
                                            $perlakuanRisiko = $controlRisk->perlakuanRisiko->first();

                                            // Check if pemantauanKajian_lockStatus is true (1)
                                            $pemantauanKajianLockStatus = $perlakuanRisiko
                                                ? $perlakuanRisiko->pemantauanKajian_lockStatus
                                                : null;

                                            // Initialize variables to store Pemantauan Tinjauan data
                                            $pemantauanDesc = '(Data tidak tersedia)';
                                            $pemantauanFreq = '(Data tidak tersedia)';
                                            $tinjauanDesc = '(Data tidak tersedia)';
                                            $tinjauanFreq = '(Data tidak tersedia)';

                                            // Retrieve the Pemantauan Tinjauan data if pemantauanKajianLockStatus is true
                                            if ($pemantauanKajianLockStatus) {
                                                $pemantauanTinjauan = $perlakuanRisiko->pemantauanTinjauan->first();

                                                if ($pemantauanTinjauan) {
                                                    $pemantauanDesc =
                                                        $pemantauanTinjauan->pemantauanTinjauan_pemantauanDesc;
                                                    $pemantauanFreq =
                                                        $pemantauanTinjauan->pemantauanTinjauan_freqPemantauan;
                                                    $tinjauanDesc =
                                                        $pemantauanTinjauan->pemantauanTinjauan_tinjauanDesc;
                                                    $tinjauanFreq =
                                                        $pemantauanTinjauan->pemantauanTinjauan_freqPelaporan;
                                                }
                                            }

                                        @endphp

                                        @if ($pemantauanKajianLockStatus)
                                            <tr>
                                                <td>{{ $index++ }}.</td>
                                                <td>{{ date('d-m-Y', strtotime($controlRisk->created_at)) }}</td>
                                                <td class="rpn {{ $rpnClass }}">{{ $rpn }}</td>
                                                <td class="center">
                                                    @if ($controlRisk->perlakuanRisiko->isNotEmpty())
                                                        {{ ucwords($controlRisk->perlakuanRisiko->first()->jenisPerlakuan->jenisPerlakuan_desc) }}
                                                    @else
                                                        (Data tidak tersedia)
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $pemantauanFreq }}
                                                </td>
                                                <td>
                                                    {{ $pemantauanDesc }}
                                                </td>
                                                <td>
                                                    {{ $tinjauanDesc }}
                                                </td>
                                                <td>
                                                    {{ $tinjauanFreq }}
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
