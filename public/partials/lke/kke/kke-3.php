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

    .keterangan-list {
        padding-left: 0;
        font-size: 0.85rem;
    }

    .keterangan-list li {
        margin-bottom: 5px;
    }
</style>

<div class="container-fluid py-4">
    <div class="card card-evaluasi shadow-sm">
        <div class="card-header text-center py-3">
            <h5 class="mb-1 font-weight-bold">KKE EVALUASI PERENCANAAN KINERJA (Format 3)</h5>
            <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                Sub Komponen Pemanfaatan: Anggaran yang Ditetapkan Telah Mengacu Pada Kinerja yang Ingin Dicapai
            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="min-width: 1000px; font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th colspan="3">Kegiatan dalam DIPA/DPA</th>
                            <th colspan="3">Kesesuaian Data DIPA/DPA dengan Renstra dan Renja (Ya = 1, Tidak = 0)</th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th>Uraian Program dan Kegiatan</th>
                            <th>Target Kinerja</th>
                            <th>Anggaran (Rp)</th>
                            <th>Target Kinerja Renstra</th>
                            <th>Target Kinerja Renja</th>
                            <th>Jumlah</th>
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>...</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>n</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-left-custom">Jumlah Nilai</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>a</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-left-custom">Persentase Pemenuhan Kriteria - 1 = a/(n X 2) X 100%</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>%</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-4">
                <h6 class="font-weight-bold">Keterangan:</h6>
                <ul class="keterangan-list">
                    <li><b>Kolom (1):</b> cukup jelas.</li>
                    <li><b>Kolom (2), (3) dan (4):</b> diisi sesuai data yang dimuat dalam DIPA/DPA terakhir (jika ada revisi).</li>
                    <li><b>Kolom (5):</b> diisi dengan nilai = 1, jika target kinerja program/kegiatan yang dimuat dalam DIPA/DPA sesuai dengan substansi yang dimuat dalam dokumen Renja. Diisi nilai = 0, jika program/kegiatan yang dimuat dalam DIPA/DPA tidak sesuai dengan substansi yang dimuat dalam dokumen Renja.</li>
                    <li><b>Kolom (6):</b> diisi dengan nilai = 1, jika target kinerja yang dimuat dalam DIPA/DPA sesuai dengan substansi yang dimuat dalam dokumen Renja. Diisi nilai = 0, jika target kinerja yang dimuat dalam DIPA/DPA tidak sesuai dengan substansi yang dimuat dalam dokumen Renja.</li>
                    <li><b>Kolom (7):</b> hasil penjumlahan dari Kolom (5) dan Kolom (6).</li>
                    <li><b>Kolom (8):</b> diisi dengan catatan untuk perbaikan.</li>
                </ul>
            </div>
        </div>
    </div>
</div>