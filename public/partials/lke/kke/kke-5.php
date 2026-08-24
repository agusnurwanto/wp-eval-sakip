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
            <h5 class="mb-1 font-weight-bold">KKE EVALUASI PENGUKURAN KINERJA (Format 5)</h5>
            <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                Sub Komponen Kualitas : Data Kinerja yang Dikumpulkan Telah Relevan Untuk Mengukur Capaian Kinerja yang Diharapkan
            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="min-width: 1000px; font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Indikator Kinerja</th>
                            <th rowspan="2">Formula Pengukuran Kinerja</th>
                            <th colspan="4">Analisis Relevansi (Jika Ya =1, Tidak = 0)</th>
                            <th rowspan="2">Jumlah Nilai</th>
                            <th rowspan="2">Ket.</th>
                        </tr>
                        <tr>
                            <th>Komponen Pembentuk Indikator</th>
                            <th>Akurat</th>
                            <th>Tepat</th>
                            <th>Sumber Data</th>
                        </tr>
                        <tr>
                           <th>(1)</th>
                           <th>(2)</th>
                           <th>(3)</th>
                           <th>(4)</th>
                           <th>(5)</th>
                           <th>(6)</th>
                           <th>(7)</th>
                           <th>(8) = (4 s.d 7)</th>
                           <th>(9)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
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
                            <td></td>
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
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-left-custom">Jumlah</td>
                            <td>c</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-left-custom">Persentase Pemenuhan Kriteria - 2 = c/n x 100%</td>
                            <td>%</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>