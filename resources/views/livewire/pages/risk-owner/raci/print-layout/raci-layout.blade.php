<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RACI Layout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px;
        }

        .info-table td {
            border: none;
            /* Remove borders from info table */
        }

        .raci-table {
            width: 100%;
            border-collapse: collapse;
        }

        .raci-table th,
        .raci-table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .raci-table th {
            background-color: #f4f4f4;
            text-align: center;
        }

        .raci-table td:first-child {
            text-align: left;
        }

        .raci-table td:not(:first-child) {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>RACI</h1>
        </div>
        <table class="info-table">
            <tr>
                <td><strong>Unit Kerja</strong></td>
                <td>: {{ ucfirst($unit_nama) }}</td>
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

        @if ($kpis)
            @php
                // Initialize an array to collect stakeholders
                $stakeholders = [];
                $raciData = [];

                // Iterate through each konteks and risk to collect data
                foreach ($kpis->konteks as $konteks) {
                    foreach ($konteks->risk as $risk) {
                        foreach ($risk->raci as $raci) {
                            $stakeholderId = $raci->stakeholder->stakeholder_id;
                            $stakeholderAbbreviation = $raci->stakeholder->stakeholder_singkatan;

                            // Group RACI data by risk description and stakeholder
                            if (!isset($raciData[$risk->risk_riskDesc])) {
                                $raciData[$risk->risk_riskDesc] = [];
                            }

                            $raciData[$risk->risk_riskDesc][$stakeholderAbbreviation][] = strtoupper($raci->raci_desc);

                            // Add stakeholder to the list
                            $stakeholders[$stakeholderId] = $stakeholderAbbreviation;
                        }
                    }
                }

                // Sort stakeholders alphabetically or by ID, if needed
                ksort($stakeholders);
            @endphp

            @if (!empty($raciData))
                <table class="raci-table">
                    <thead>
                        <tr>
                            <th style="cursor: pointer; width:450px;">Risiko</th>
                            @foreach ($stakeholders as $stakeholderAbbreviation)
                                <th>{{ $stakeholderAbbreviation }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($raciData as $riskDesc => $stakeholderRaci)
                            <tr>
                                <td style="word-break: break-word;">{{ $riskDesc }}</td>
                                @foreach ($stakeholders as $stakeholderAbbreviation)
                                    <td style="text-align: center">
                                        @if (isset($stakeholderRaci[$stakeholderAbbreviation]))
                                            {{ implode('/', $stakeholderRaci[$stakeholderAbbreviation]) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div>
                    No data available.
                </div>
            @endif
        @endif

    </div>
</body>

</html>
