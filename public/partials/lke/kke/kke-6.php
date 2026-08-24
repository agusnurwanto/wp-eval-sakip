<?php
if (!defined('WPINC')) {
    die;
}
?>

<style>
    .card-evaluasi .card-header {
        background-color: #f8f9fa;
    }

    .table th, .table td {
        vertical-align: middle;
        padding: 0.65rem;
        text-align: center; /* Center align text for this format */
    }

    .table .text-left-custom {
        text-align: left !important; /* For specific left-aligned cells */
    }
    
    .table thead th {
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #495057;
        background-color: #e9ecef; /* Light header background */
    }

    .table tfoot td {
        font-weight: 600;
        background-color: #f8f9fa;
    }
</style>

<div class="container-fluid py-4">
    <div class="card card-evaluasi shadow-sm">
        <div class="card-header text-center py-3">
            <h5 class="mb-1 font-weight-bold">KKE EVALUASI AKUNTABILITAS KINERJA INTERNAL (Format 6)</h5>
            <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                Sub Komponen Kualitas : Seluruh rekomendasi atas hasil evaluasi akuntabilitas kinerja (internal dan LHE SAKIP Perangkat Daerah) telah ditindaklanjuti.
            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="min-width: 1000px; font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Judul/ Nomor/Tanggal LHE</th>
                            <th rowspan="2">Rekomendasi Hasil Evaluasi</th>
                            <th rowspan="2">Tindak Lanjut</th>
                            <th colspan="3">Status Tindak Lanjut (Diisikan pada 1 dari 3 kolom, nilai "1", jika ya, dan "0" jika Tidak)</th>
                            <th rowspan="2">Jumlah</th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th>Belum Ada</th>
                            <th>Dalam Proses</th>
                            <th>Tuntas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
                        </tr>
                         <tr>
                            <td>2.</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
                        </tr>
                         <tr>
                            <td>3.</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
                        </tr>
                         <tr>
                            <td>4.</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
                        </tr>
                         <tr>
                            <td>5.</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
                        </tr>
                        <tr>
                            <td>...</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
                        </tr>
                        <tr>
                            <td>N</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-left-custom">Jumlah (Y)</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-left-custom">Persentase pemenuhan Kriteria 1 = Y/(N) x 100%</td>
                            <td>...%</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>