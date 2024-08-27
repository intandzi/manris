<div>
    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            @if ($this->role === 'risk owner')
                <ol class="breadcrumb mb-0 p-2">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                            App</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('liskKpiRaciOw.index', ['role' => $encryptedRole]) }}"
                            wire:navigate>
                            List KPI RACI</a></li>
                    <li class="breadcrumb-item active"><a href="#">Matriks RACI</a>
                    </li>
                </ol>
            @else
                <ol class="breadcrumb mb-0 p-2">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                            App</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('liskKpiRaciOf.index', ['role' => $encryptedRole]) }}" wire:navigate>
                            List KPI RACI</a></li>
                    <li class="breadcrumb-item active"><a href="#">Matriks RACI</a>
                    </li>
                </ol>
            @endif
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Matriks RACI</h3>
                        <p class="card-text d-inline" style="font-weight: bold">{{ $kpi_kode }}</p>
                        <span>&nbsp;</span>
                        <p class="card-text d-inline">{{ ucwords($kpi_nama) }}</p>
                    </div> <!-- end card body-->
                    <div class="card-footer bg-transparent border-success">
                        <table>
                            <tr>
                                <td>R (Responsible)</td>
                                <td>:</td>
                                <td>Bertanggung jawab langsung melakukan pekerjaan atau tugas</td>
                            </tr>
                            <tr>
                                <td>A (Accountable)</td>
                                <td>:</td>
                                <td>Bertanggung jawab atas keseluruhan keberhasilan </td>
                            </tr>
                            <tr>
                                <td>C (Consulted)</td>
                                <td>:</td>
                                <td>Tim yang perlu dikonsultasikan terkait tindakan atau keputusan </td>
                            </tr>
                            <tr>
                                <td>I (Informed)</td>
                                <td>:</td>
                                <td>Tim yang perlu diberi tahu tentang perkembangan keputusan </td>
                            </tr>
                        </table>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- @php
                            $hasRaciData = false;

                            // Check if there's any RACI data available
                            foreach ($kpis->konteks as $konteks) {
                                foreach ($konteks->risk as $risk) {
                                    if ($risk->raci && count($risk->raci) > 0) {
                                        $hasRaciData = true;
                                        break 2; // Exit both loops if RACI data is found
                                    }
                                }
                            }
                        @endphp

                        @if ($hasRaciData)
                            <div class="table-responsive mt-2">
                                <table class="table table-centered mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="cursor: pointer; width:500px;">Risk</th>
                                            @php
                                                // Get a unique list of all stakeholders involved across all risks
                                                $stakeholders = [];
                                                foreach ($kpis->konteks as $konteks) {
                                                    foreach ($konteks->risk as $risk) {
                                                        foreach ($risk->raci as $data) {
                                                            $stakeholders[$data->stakeholder->stakeholder_id] =
                                                                $data->stakeholder->stakeholder_singkatan;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @foreach ($stakeholders as $stakeholder_singkatan)
                                                <th>{{ $stakeholder_singkatan }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kpis->konteks as $konteks)
                                            @foreach ($konteks->risk as $risk)
                                                <tr>
                                                    <td style="word-break: break-word;">{{ $risk->risk_riskDesc }}</td>
                                                    @foreach ($stakeholders as $stakeholder_id => $stakeholder_singkatan)
                                                        <td>
                                                            @php
                                                                $raci_desc = '-'; // Default value if no RACI is found
                                                                foreach ($risk->raci as $data) {
                                                                    if (
                                                                        $data->stakeholder->stakeholder_id ==
                                                                        $stakeholder_id
                                                                    ) {
                                                                        $raci_desc = ucwords($data->raci_desc);
                                                                        break;
                                                                    }
                                                                }
                                                            @endphp
                                                            {{ $raci_desc }}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-danger mt-2 mb-2">
                                No data available.
                            </div>
                        @endif --}}

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

                                            $raciData[$risk->risk_riskDesc][$stakeholderAbbreviation][] = strtoupper(
                                                $raci->raci_desc,
                                            );

                                            // Add stakeholder to the list
                                            $stakeholders[$stakeholderId] = $stakeholderAbbreviation;
                                        }
                                    }
                                }

                                // Sort stakeholders alphabetically or by ID, if needed
                                ksort($stakeholders);
                            @endphp

                            @if (!empty($raciData))
                                <div class="table-responsive mt-2">
                                    <table class="table table-centered mb-0">
                                        <thead class="table-dark">
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
                                                        <td>
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
                                </div> <!-- end table-responsive-->
                            @else
                                <div class="alert alert-danger mt-2 mb-2">
                                    No data available.
                                </div>
                            @endif
                        @endif


                        <div class="row mt-2">
                            <div class="col-md-12 text-end">
                                {{-- {!! $konteksRisikos->links() !!} --}}
                            </div>
                        </div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div><!-- end row -->

    </div> <!-- container -->
</div>
