<?php
if (!defined('WPINC')) {
    die;
}
?>

<style>
    .card-evaluasi .card-header {
        background-color: #f8f9fa;
        /* Light gray for header */
    }

    .table th,
    .table td {
        vertical-align: middle !important;
        text-align: center !important;
        /* Ensures text is centered vertically */
        padding: 0.65rem !important;
        /* Slightly adjust padding for better spacing */
    }

    .table thead th {
        font-weight: 600;
        /* Slightly bolder headers */
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #495057;
    }

    .table tfoot td {
        font-weight: 600;
        background-color: #f8f9fa;
    }

    /* Styling for Keterangan list */
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
            <h5 class="mb-1 font-weight-bold">KKE EVALUASI PERENCANAAN KINERJA (Format 1)</h5>
            <p class="mb-0 text-muted" style="font-size: 0.9rem;">Sub Komponen Kualitas: Ukuran Keberhasilan (Indikator Kinerja) telah Memenuhi Kriteria SMART-C</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="min-width: 900px; font-size: 0.85rem;">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="9" class="th-pertanyaan-12">Untuk Pertanyaan No 12</th>
                            <th colspan="4" class="th-pertanyaan-17">Untuk Pertanyaan No 17</th>
                        </tr>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Tujuan, Sasaran, Program, Kegiatan</th>
                            <th rowspan="2">Indikator Kinerja</th>
                            <th rowspan="2">Target</th>
                            <th colspan="5">Kinerja (T=1/F=0)</th>
                            <th rowspan="2">Jml Nilai (a&b)</th>
                            <th colspan="2">Kriteria (Y=1/T=0)</th>
                            <th rowspan="2">Ket.</th>
                        </tr>
                        <tr>
                            <th>Spesifik</th>
                            <th>Dapat Diukur</th>
                            <th>Dapat Dicapai</th>
                            <th>Relevan</th>
                            <th>Berbatas Waktu</th>
                            <th colspan="2">Menantang (Continuous Improvement)</th>
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
                            <th></th>
                            <th>(9)</th>
                            <th>(10)</th>
                            <th>(11)</th>
                            <th>(12)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Contoh Tujuan...</td>
                            <td>Contoh Indikator...</td>
                            <td>100%</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>0</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>0</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" class="text-left">Jumlah Nilai (jumlah kolom 9)</td>
                            <td>0</td>
                            <td rowspan="2" class="text-center"></td>
                            <td rowspan="2" class="text-center"></td>
                            <td rowspan="2" class="text-center"></td>
                        </tr>
                        <tr>
                            <td colspan="9" class="text-left">Persentase Pencapaian Kinerja = K32/(jumlah IK x 5) x 100%</td>
                            <td>0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-4">
                <h6 class="font-weight-bold">Keterangan:</h6>
                <ul class="keterangan-list">
                    <li><b>Kolom (1):</b> cukup jelas.</li>
                    <li><b>Kolom (2):</b> diisi dengan pernyataan sasaran/program/kegiatan sebagaimana yang dimuat dalam dokumen perencanaan kinerja.</li>
                    <li><b>Kolom (3):</b> diisi dengan pernyataan indikator kinerja sebagaimana yang dimuat dalam dokumen perencanaan kinerja.</li>
                    <li><b>Kolom (4), (5), (6), (7) dan (8):</b> diisi dengan nilai 1 apabila hasil penelaahan atas pernyataan indikator telah memenuhi syarat specific (spesifik), measurable (dapat diukur), attainable (dapat dicapai), relevance (relevan) dan times bound (berbatas waktu), dan diisi nilai 0 apabila dijawab "Tidak" memenuhi syarat tersebut.</li>
                    <li><b>Kolom (9):</b> diisi dengan penjumlahan dari kolom (4), (5), (6), (7) dan kolom (8).</li>
                    <li><b>Kolom (10):</b> diisi dengan nilai 1 apabila hasil penelaahan atas pernyataan indikator telah memenuhi syarat "menantang"/ perbaikan berkelanjutan" dan diisi nilai 0 apabila dijawab "Tidak" memenuhi syarat tersebut.</li>
                    <li><b>Kolom (11):</b> diisi dengan catatan untuk perbaikan.</li>
                </ul>
            </div>
        </div>
    </div>
</div>