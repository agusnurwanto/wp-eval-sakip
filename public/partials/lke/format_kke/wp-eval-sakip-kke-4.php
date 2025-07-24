<?php
if (!defined('WPINC')) {
    die;
}
?>

<style>
    .card-evaluasi .card-header {
        background-color: #f8f9fa;
    }

    .table th,
    .table td {
        vertical-align: middle;
        padding: 0.65rem;
        text-align: center;
        /* Center align text for this format */
    }

    .table .text-left-custom {
        text-align: left !important;
        /* For specific left-aligned cells */
    }

    .table thead th {
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #495057;
        background-color: #e9ecef;
        /* Light header background */
    }

    .table tfoot td {
        font-weight: 600;
        background-color: #f8f9fa;
    }
</style>

<div class="container-fluid py-4">
    <div class="card card-evaluasi shadow-sm">
        <div class="card-header text-center py-3">
            <h5 class="mb-1 font-weight-bold">KKE EVALUASI PENGUKURAN KINERJA (Format 4)</h5>
            <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                Sub Komponen Kualitas : Pimpinan Selalu Terlibat Sebagai Pengambil Keputusan (Decision Maker) Dalam Mengukur Capaian Kinerja
            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="min-width: 1000px; font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th rowspan="3">No</th>
                            <th>Pengukuran Capaian Kinerja Periodik</th>
                            <th colspan="5">Bentuk Keterlibatan Pimpinan (Ada = 1/ Sebagian = 0,5 / Tidak Ada = 0)</th>
                            <th rowspan="3">Jumlah Nilai: kolom (3) sd (7)</th>
                            <th rowspan="3">Ket.</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Jadwal Kegiatan</th>
                            <th rowspan="2">Reviu Dokumen</th>
                            <th rowspan="2">Atensi Pimpinan</th>
                            <th colspan="3">Rapat</th>
                        </tr>
                        <tr>
                            <th>Undangan</th>
                            <th>Daftar Hadir</th>
                            <th>Notulensi</th>
                        </tr>
                        <tr>
                            <th>(1)</th>
                            <th>(2)</th>
                            <th>(3)</th>
                            <th>(4)</th>
                            <th>(5)</th>
                            <th>(6)</th>
                            <th>(7)</th>
                            <th>(8)</th>
                            <th>(9)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>TW-1</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>0</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>TW-2</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>0</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>TW-3</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>0</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>TW-4</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>0</td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-left-custom">Jumlah Nilai</td>
                            <td>b</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-left-custom">Nilai total = b/5*100%</td>
                            <td>%</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>