<div>

    @push('style-alt')
        <style>
            #dash {
                border-collapse: collapse;
                width: 100%;
                text-align: center;
                font-family: Arial, sans-serif;
            }

            #dash th,
            #dash td {
                border: 1px solid #000;
                padding: 10px;
                width: 40px;
                height: 40px;
            }

            .low {
                background-color: #00ff00;
            }

            .medium-low {
                background-color: #adff2f;
            }

            .medium {
                background-color: #ffff00;
            }

            .medium-high {
                background-color: #ff9900;
            }

            .high {
                background-color: #ff0000;
            }

            .header-vertical {
                writing-mode: vertical-rl;
                transform: rotate(180deg);
            }
        </style>
    @endpush

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 p-2">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i>
                        Main</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('lembaga.dashboard') }}">Dashboard</a></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-12">
                <div class="card py-2">
                    <div class="">
                        <div class="table-responsive mx-lg-5">
                            <table class="table-centered mb-2" id="dash">
                                <thead>
                                    <tr class="border-0">
                                        <th colspan="12" class="border-0">RISK MATRIKS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-0" style="width: 50px;">
                                        <th rowspan="11" class="header-vertical border-0" style="width: 50px;">Tingkat Keparahan Peristiwa</th>
                                    </tr>
                                    <tr>
                                        <th>10</th>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">1</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>9</th>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>8</th>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>7</th>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>6</th>
                                        <td class="low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>5</th>
                                        <td class="low">-</td>
                                        <td class="low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>4</th>
                                        <td class="low">-</td>
                                        <td class="low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>3</th>
                                        <td class="low">-</td>
                                        <td class="low">1</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>2</th>
                                        <td class="low">-</td>
                                        <td class="low">-</td>
                                        <td class="low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="high">-</td>
                                    </tr>
                                    <tr>
                                        <th>1</th>
                                        <td class="low">1</td>
                                        <td class="low">-</td>
                                        <td class="low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium-low">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium">-</td>
                                        <td class="medium-high">-</td>
                                        <td class="medium-high">-</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="border-0">
                                        <th class="border-0"></th>
                                        <th></th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                        <th>6</th>
                                        <th>7</th>
                                        <th>8</th>
                                        <th>9</th>
                                        <th>10</th>
                                    </tr>
                                    <tr class="border-0">
                                        <th colspan="13" class="border-0">Kemungkinan Terjadinya dari Penyebab</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div><!-- end row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="">
                            <h4 class="card-title">Daftar Ranking Resiko</h4>
                            <p class="text-muted fw-semibold mb-0">Your Revenue This Week</p>
                        </div><!-- end card-header -->
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kode Peristiwa Resiko</th>
                                        <th>Peristiwa Resiko</th>
                                        <th>Dampak</th>
                                        <th>Deteksi</th>
                                        <th>Kemungkinan</th>
                                        <th>RPN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>E1</td>
                                        <td>Biaya Operasional Tinggi</td>
                                        <td>9</td>
                                        <td>8</td>
                                        <td>7</td>
                                        <td>
                                            <span class="badge bg-danger">504</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div><!-- end row -->
    </div>
</div>
