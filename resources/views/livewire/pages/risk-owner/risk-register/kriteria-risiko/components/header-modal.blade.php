<div class="row">
    <div class="col-md-12">
        <div class="table-responsive mb-2">
            <table>
                <tr>
                    <td style="width: 200px; vertical-align: top;">
                        <p class="card-text me-4" style="font-weight: bold">Unit Pemilik Risiko
                        </p>
                    </td>
                    <td style="vertical-align: top;">:</td>
                    <td style="width: calc(100% - 250px); vertical-align: bottom;">
                        <p class="card-text" style="word-wrap: break-word;">{{ $unit_nama }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 200px; vertical-align: top;">
                        <p class="card-text" style="font-weight: bold">Pemilik Risiko</p>
                    </td>
                    <td style="vertical-align: top;">:</td>
                    <td style="vertical-align: bottom;">
                        <p class="card-text">{{ ucwords($user_pemilik) }}</p>
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
                <tr>
                    <td style="width: 200px; vertical-align: top;">
                        <p class="card-text me-4" style="font-weight: bold;">
                            Risiko
                        </p>
                    </td>
                    <td style="vertical-align: top;">:</td>
                    <td style="width: 800px; vertical-align: bottom; word-break: break-word;">
                        <p style="word-break: break-word;">
                            {{ $risk_spesific }}
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
