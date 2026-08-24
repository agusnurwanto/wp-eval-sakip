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
            <h5 class="mb-1 font-weight-bold">KKE EVALUASI PERENCANAAN KINERJA (Format 2)</h5>
            <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                Sub Komponen Kualitas : Setiap Dokumen Perencanaan Kinerja Menggambarkan Hubungan yang Berkesinambungan, Serta Selaras Antara Kondisi/Hasil yang Akan Dicapai Di Setiap Level Jabatan (Cascading)
            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="min-width: 1000px; font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 5%;">No.</th>
                            <th rowspan="2" style="width: 20%;">Indikator Kinerja</th>
                            <th colspan="3">Pernyataan IKU/IK</th>
                            <th rowspan="2" style="width: 10%;">Level Jabatan</th>
                            <th rowspan="2" style="width: 20%;">Ketepatan Cascading Dengan Menganalisis Keselarasan IKU/IK Disetiap Level Jabatan Secara Logis (Y=1, T=0)</th>
                            <th rowspan="2" style="width: 15%;">Keterangan</th>
                        </tr>
                        <tr>
                            <th>Renstra</th>
                            <th>Renja</th>
                            <th>PK</th>
                        </tr>
                        <tr>
                            <th>(1)</th>
                            <th>(2)</th>
                            <th colspan="3">(3)</th>
                            <th>(4)</th>
                            <th>(5)</th>
                            <th>(6)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.a</td>
                            <td class="text-left-custom">Ultimate Outcome;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>1.b</td>
                            <td class="text-left-custom">Intermediate Outcome;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>1.c</td>
                            <td class="text-left-custom">Immediate Outcome;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>1.d</td>
                            <td class="text-left-custom">Output;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2.a</td>
                            <td class="text-left-custom">Ultimate Outcome;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2.b</td>
                            <td class="text-left-custom">Intermediate Outcome;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>...dst</td>
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
                            <td colspan="3"></td>
                            <td></td>
                            <td class="text-center">a</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-left-custom">Persentase Pemenuhan Kriteria = a/(Jumlah IK yang Tersedia) X 100%</td>
                            <td colspan="3"></td>
                            <td></td>
                            <td class="text-center">%</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-4">
                <h6 class="font-weight-bold">Keterangan:</h6>
                <ul class="keterangan-list">
                    <li></span><b>Kolom (1), dan (3):</b> cukup jelas.</li>
                    <li></span><b>Kolom (2):</b> diisi sesuai dengan indikator yang disajikan dalam dokumen Pohon Kinerja.</li>
                    <li></span><b>Kolom (4):</b> diisi sesuai uraian tugas pegawai yang mempunyai tanggung jawab atas pelaksanaan program/kegiatan.</li>
                    <li></span><b>Kolom (5):</b> diisi dengan level jabatan yang tertuang dalam dokumen Pohon Kinerja.</li>
                    <li></span><b>Kolom (6):</b> diisi sesuai hasil penilaian atas dokumen Pohon Kinerja, apakah cascading (penjenjangan) kinerja telah selaras dan logis disetiap level jabatan. Diisi nilai = 1 jika telah logis (Y), diisi nilai = 0.5, jika kurang logis (K), dan diisi nilai = 0 jika tidak logis (T).</li>
                    <li></span><b>Kolom (7):</b> diisi dengan catatan untuk perbaikan.</li>
                </ul>
            </div>
        </div>
    </div>
</div>