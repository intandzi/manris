<div>

    @include('livewire.pages.risk-owner.risk-register.identifikasi-risiko.identifikasi-risiko-modal')

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 p-2">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                        App</a></li>

                {{-- <li class="breadcrumb-item"><a href="{{ route('listKPIUMR.index', ['role' => $encryptedRole]) }}"
                        wire:navigate>
                        Risk Register</a></li> --}}
                <li class="breadcrumb-item">
                    Risk Register</li>
                <li class="breadcrumb-item active"><a
                        href="{{ route('logKonteksRisiko.index', ['role' => $encryptedRole, 'kpi' => $encryptedKPI]) }}"
                        wire:navigate>Konteks Risiko</a></li>
                <li class="breadcrumb-item active"><a href="#">{{ $title2 }}</a></li>
                <li class="breadcrumb-item active"><a href="#">Konteks Risiko {{ $konteks_kode }}</a></li>
            </ol>
        </nav>

        <ul class="nav nav-pills bg-nav-pills nav-justified mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tabActive === 'identifikasiContent' ? 'active' : 'bg-white' }}"
                    wire:click="toggleTab('identifikasiContent')" type="button" role="tab"
                    aria-controls="identifikasiContent"
                    aria-selected="{{ $tabActive === 'identifikasiContent' ? 'true' : 'false' }}">
                    Identifikasi Risiko</button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link {{ $tabActive === 'kriteriaContent' ? 'active' : '' }} {{ $tabActive !== 'kriteriaContent' ? 'bg-white' : '' }}"
                    wire:click="toggleTab('kriteriaContent')" type="button" role="tab"
                    aria-controls="kriteriaContent"
                    aria-selected="{{ $tabActive === 'kriteriaContent' ? 'true' : 'false' }}">Kriteria Risiko</button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link {{ $tabActive === 'analisisContent' ? 'active' : '' }} {{ $tabActive !== 'analisisContent' ? 'bg-white' : '' }}"
                    wire:click="toggleTab('analisisContent')" type="button" role="tab"
                    aria-controls="analisisContent"
                    aria-selected="{{ $tabActive === 'analisisContent' ? 'true' : 'false' }}">Analisis Risiko</button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link {{ $tabActive === 'evaluasiContent' ? 'active' : '' }} {{ $tabActive !== 'evaluasiContent' ? 'bg-white' : '' }}"
                    wire:click="toggleTab('evaluasiContent')" type="button" role="tab"
                    aria-controls="evaluasiContent"
                    aria-selected="{{ $tabActive === 'evaluasiContent' ? 'true' : 'false' }}">Evaluasi Risiko</button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link {{ $tabActive === 'rencanaPerlakuanContent' ? 'active' : '' }} {{ $tabActive !== 'rencanaPerlakuanContent' ? 'bg-white' : '' }}"
                    wire:click="toggleTab('rencanaPerlakuanContent')" type="button" role="tab"
                    aria-controls="rencanaPerlakuanContent"
                    aria-selected="{{ $tabActive === 'rencanaPerlakuanContent' ? 'true' : 'false' }}">Rencana Perlakuan
                    Risiko</button>
            </li>
            <!-- Add similar buttons for other tabs -->
        </ul>


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title2 }}</h3>
                        <div class="table-responsive mb-2">
                            <table>
                                <tr>
                                    <td style="width: 200px;">
                                        <p class="card-text me-4" style="font-weight: bold">Unit Pemilik Risiko
                                        </p>
                                    </td>
                                    <td>:</td>
                                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $unit_nama }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; vertical-align: top;">
                                        <p class="card-text" style="font-weight: bold">Deskripsi Konteks</p>
                                    </td>
                                    <td style="vertical-align: top;">:</td>
                                    <td style="vertical-align: bottom;">
                                        <p class="card-text" style="word-wrap: break-word;">{{ $konteks_desc }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-success">
                        <p class="card-text d-inline"><i class="ri-information-line d-inline"></i></p>
                        <span>&nbsp;</span>
                        <p class="card-text d-inline">{!! $titleDesc !!}</p>
                    </div>
                </div> <!-- end card-->
            </div>
        </div>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade {{ $tabActive === 'identifikasiContent' ? 'show active' : '' }}"
                id="identifikasiContent" role="tabpanel" aria-labelledby="identifikasi-tab">
                @if ($showIdentifikasiContent)
                    @include('livewire.pages.umr.validasi-kpi.risk-register-umr.identifikasi-risiko.identifikasi-content')
                    <!-- Identifikasi Risiko content goes here -->
                @endif
            </div>
            <div class="tab-pane fade {{ $tabActive === 'kriteriaContent' ? 'show active' : '' }}" id="kriteriaContent"
                role="tabpanel" aria-labelledby="kriteria-tab">
                @if ($showKriteriaContent)
                    @include('livewire.pages.umr.validasi-kpi.risk-register-umr.kriteria-risiko.kriteria-content')
                    <!-- Kriteria Risiko content goes here -->
                @endif
            </div>
            <div class="tab-pane fade {{ $tabActive === 'analisisContent' ? 'show active' : '' }}" id="analisisContent"
                role="tabpanel" aria-labelledby="analisis-tab">
                @if ($showAnalisisContent)
                    @include('livewire.pages.umr.validasi-kpi.risk-register-umr.analisis-risiko.analisis-content')
                    <!-- Analisis Risiko content goes here -->
                @endif
            </div>
            <div class="tab-pane fade {{ $tabActive === 'evaluasiContent' ? 'show active' : '' }}" id="evaluasiContent"
                role="tabpanel" aria-labelledby="evaluasi-tab">
                @if ($showEvaluasiContent)
                    @include('livewire.pages.umr.validasi-kpi.risk-register-umr.evaluasi-risiko.evaluasi-content')
                    <!-- Evaluasi Risiko content goes here -->
                @endif
            </div>
            <div class="tab-pane fade {{ $tabActive === 'rencanaPerlakuanContent' ? 'show active' : '' }}"
                id="rencanaPerlakuanContent" role="tabpanel" aria-labelledby="rencanaPerlakuan-tab">
                @if ($showRencanaPerlakuanContent)
                    @include('livewire.pages.umr.validasi-kpi.risk-register-umr.rencana-perlakuan-risiko.rencana-perlakuan-content')
                    <!-- Rencana Perlakuan Risiko content goes here -->
                @endif
            </div>
            <!-- Add similar tab panes for other tabs -->
        </div>

    </div> <!-- container -->

</div>
