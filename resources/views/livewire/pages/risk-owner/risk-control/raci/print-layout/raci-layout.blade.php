<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RACI - {{ ucfirst($kpis->kpi_kode) }}</title>
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
        <h2>PENGELOLAAN RACI</h2>
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
                $hasRaci = false;
                $index = 1;
            @endphp

            @foreach ($kpis->konteks as $konteks)
                @foreach ($konteks->risk as $risk)
                    @if ($risk->raci->isNotEmpty() && $risk->raci->first()->raci_lockStatus)
                        @php
                            $hasRaci = true;
                            // break 3; // Exit all loops once a controlRisk with controlRisk_isControl == 1 is found
                        @endphp
                    @endif
                @endforeach
            @endforeach

            @if ($hasRaci)
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal Kontrol</th>
                            <th>Responsible</th>
                            <th>Accountable</th>
                            <th>Consulted</th>
                            <th>Informed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kpis->konteks as $konteks)
                            @foreach ($konteks->risk as $risk)
                                @if ($risk->risk_id == $risk_id)
                                    @foreach ($risk->raci as $raci)
                                        @php
                                            // Initialize arrays to group RACI data
                                            $responsible = [];
                                            $accountable = [];
                                            $consulted = [];
                                            $informed = [];
                                        @endphp

                                        @foreach ($risk->raci as $raci)
                                            @if ($raci->raci_lockStatus)
                                                @php
                                                    $stakeholderInfo =
                                                        ucwords($raci->stakeholder->stakeholder_jabatan) .
                                                        '  (' .
                                                        strtoupper($raci->stakeholder->stakeholder_singkatan) .
                                                        ')';

                                                    switch ($raci->raci_desc) {
                                                        case 'r':
                                                            $responsible[] = $stakeholderInfo;
                                                            break;
                                                        case 'a':
                                                            $accountable[] = $stakeholderInfo;
                                                            break;
                                                        case 'c':
                                                            $consulted[] = $stakeholderInfo;
                                                            break;
                                                        case 'i':
                                                            $informed[] = $stakeholderInfo;
                                                            break;
                                                    }
                                                @endphp
                                            @endif
                                        @endforeach

                                        @php
                                            // Set default text if no data is available
                                            if (empty($responsible)) {
                                                $responsible[] = '(Data tidak tersedia)';
                                            }
                                            if (empty($accountable)) {
                                                $accountable[] = '(Data tidak tersedia)';
                                            }
                                            if (empty($consulted)) {
                                                $consulted[] = '(Data tidak tersedia)';
                                            }
                                            if (empty($informed)) {
                                                $informed[] = '(Data tidak tersedia)';
                                            }
                                        @endphp
                                    @endforeach
                                    @foreach ($risk->controlRisk as $controlRisk)
                                        <tr>
                                            <td>{{ date('d-m-Y', strtotime($controlRisk->created_at)) }}</td>
                                            <td>
                                                - {!! implode('<br>- ', $responsible) !!}<br>
                                            </td>
                                            <td>
                                                - {!! implode('<br>- ', $accountable) !!}<br>
                                            </td>
                                            <td>
                                                - {!! implode('<br>- ', $consulted) !!}<br>
                                            </td>
                                            <td>
                                                - {!! implode('<br>- ', $informed) !!}<br>
                                            </td>
                                        </tr>
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
