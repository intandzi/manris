<div>

    @include('livewire.pages.risk-owner.risk-control.control-perlakuan-risiko.control-risiko-modal')

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            @if ($this->role === 'risk owner')
                <ol class="breadcrumb mb-0 p-2">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                            App</a></li>

                    <li class="breadcrumb-item"><a href="{{ route('riskControlOw.index', ['role' => $encryptedRole]) }}"
                            wire:navigate>
                            Risk Control</a></li>
                    <li class="breadcrumb-item active"><a
                            href="{{ route('controlKonteksRisikoOw.index', ['role' => $encryptedRole, 'kpi' => $encryptedKPI]) }}"
                            wire:navigate>Konteks Risiko</a></li>
                    <li class="breadcrumb-item active"><a
                            href="{{ route('listRiskOw.index', ['role' => $encryptedRole, 'kpi' => $encryptedKPI, 'konteks' => $encryptedKonteks]) }}"
                            wire:navigate>Daftar Risiko</a></li>
                    <li class="breadcrumb-item active"><a href="#">{{ $title2 }}</a></li>
                </ol>
            @else
                <ol class="breadcrumb mb-0 p-2">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-apps"></i>
                            App</a></li>

                    <li class="breadcrumb-item"><a href="{{ route('officerRiskControl.index', ['role' => $encryptedRole]) }}"
                            wire:navigate>
                            Risk Control</a></li>
                    <li class="breadcrumb-item active"><a
                            href="{{ route('officerControlKonteksRisiko.index', ['role' => $encryptedRole, 'kpi' => $encryptedKPI]) }}"
                            wire:navigate>Konteks Risiko</a></li>
                    <li class="breadcrumb-item active"><a
                            href="{{ route('officerListRisk.index', ['role' => $encryptedRole, 'kpi' => $encryptedKPI, 'konteks' => $encryptedKonteks]) }}"
                            wire:navigate>Daftar Risiko</a></li>
                    <li class="breadcrumb-item active"><a href="#">{{ $title2 }}</a></li>
                </ol>
            @endif
        </nav>

        <ul class="nav nav-pills bg-nav-pills nav-justified mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tabActive === 'kontrolPerlakuanRisikoContent' ? 'active' : 'bg-white' }}"
                    wire:click="toggleTab('kontrolPerlakuanRisikoContent')" type="button" role="tab"
                    aria-controls="kontrolPerlakuanRisikoContent"
                    aria-selected="{{ $tabActive === 'kontrolPerlakuanRisikoContent' ? 'true' : 'false' }}">
                    Kontrol Perlakuan Risiko</button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link {{ $tabActive === 'evaluasiKontrolRisikoContent' ? 'active' : '' }} {{ $tabActive !== 'evaluasiKontrolRisikoContent' ? 'bg-white' : '' }}"
                    wire:click="toggleTab('evaluasiKontrolRisikoContent')" type="button" role="tab"
                    aria-controls="evaluasiKontrolRisikoContent"
                    aria-selected="{{ $tabActive === 'evaluasiKontrolRisikoContent' ? 'true' : 'false' }}">Evaluasi
                    Kontrol Risiko</button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link {{ $tabActive === 'pemantauanTinjauanContent' ? 'active' : '' }} {{ $tabActive !== 'pemantauanTinjauanContent' ? 'bg-white' : '' }}"
                    wire:click="toggleTab('pemantauanTinjauanContent')" type="button" role="tab"
                    aria-controls="pemantauanTinjauanContent"
                    aria-selected="{{ $tabActive === 'pemantauanTinjauanContent' ? 'true' : 'false' }}">Pemantauan dan
                    Tinjauan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link {{ $tabActive === 'RACIContent' ? 'active' : '' }} {{ $tabActive !== 'RACIContent' ? 'bg-white' : '' }}"
                    wire:click="toggleTab('RACIContent')" type="button" role="tab" aria-controls="RACIContent"
                    aria-selected="{{ $tabActive === 'RACIContent' ? 'true' : 'false' }}">RACI</button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link {{ $tabActive === 'komunikasiKonsultasiContent' ? 'active' : '' }} {{ $tabActive !== 'komunikasiKonsultasiContent' ? 'bg-white' : '' }}"
                    wire:click="toggleTab('komunikasiKonsultasiContent')" type="button" role="tab"
                    aria-controls="komunikasiKonsultasiContent"
                    aria-selected="{{ $tabActive === 'komunikasiKonsultasiContent' ? 'true' : 'false' }}">Komunikasi
                    dan Konsultasi</button>
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
                </div> <!-- end card-->
            </div>
        </div>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade {{ $tabActive === 'kontrolPerlakuanRisikoContent' ? 'show active' : '' }}"
                id="kontrolPerlakuanRisikoContent" role="tabpanel" aria-labelledby="identifikasi-tab">
                @if ($showKontrolPerlakuanRisikoContent)
                    @include('livewire.pages.risk-owner.risk-control.control-perlakuan-risiko.control-perlakuan-risiko-content')
                    <!-- Control Risiko content goes here -->
                @endif
            </div>
            <div class="tab-pane fade {{ $tabActive === 'evaluasiKontrolRisikoContent' ? 'show active' : '' }}"
                id="evaluasiKontrolRisikoContent" role="tabpanel" aria-labelledby="kriteria-tab">
                @if ($showEvaluasiKontrolRisikoContent)
                    @include('livewire.pages.risk-owner.risk-control.evaluasi-control-risiko.evaluasi-control-risiko-content')
                    <!-- Evaluasi Risiko content goes here -->
                @endif
            </div>
            <div class="tab-pane fade {{ $tabActive === 'pemantauanTinjauanContent' ? 'show active' : '' }}"
                id="pemantauanTinjauanContent" role="tabpanel" aria-labelledby="analisis-tab">
                @if ($showPemantauanTinjauanContent)
                    @include('livewire.pages.risk-owner.risk-control.pemantauan-tinjauan.pemantauan-tinjauan-content')
                    <!-- Pemantauan dan Tinjauan content goes here -->
                @endif
            </div>
            <div class="tab-pane fade {{ $tabActive === 'RACIContent' ? 'show active' : '' }}" id="RACIContent"
                role="tabpanel" aria-labelledby="evaluasi-tab">
                @if ($showRACIContent)
                    @include('livewire.pages.risk-owner.risk-control.raci.raci-content')
                    <!-- Evaluasi Risiko content goes here -->
                @endif
            </div>
            <div class="tab-pane fade {{ $tabActive === 'komunikasiKonsultasiContent' ? 'show active' : '' }}"
                id="komunikasiKonsultasiContent" role="tabpanel" aria-labelledby="rencanaPerlakuan-tab">
                @if ($showKomunikasiKonsultasiContent)
                    @include('livewire.pages.risk-owner.risk-control.komunikasi-konsultasi.komunikasi-konsultasi-content')
                    <!-- Rencana Perlakuan Risiko content goes here -->
                @endif
            </div>
            <!-- Add similar tab panes for other tabs -->
        </div>

    </div> <!-- container -->

</div>
