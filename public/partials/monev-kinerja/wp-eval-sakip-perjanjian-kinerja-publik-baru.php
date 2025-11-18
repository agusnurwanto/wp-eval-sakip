<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}
$input = shortcode_atts(array(
    'tahun_anggaran' => ''
), $atts);
$id_skpd = $_GET['id_skpd'];

$pk_pisah_page = $this->functions->generatePage(array(
    'nama_page' => 'Perjanjian Kinerja Format Pisah| Tahun Anggaran ' . $input['tahun_anggaran'],
    'content' => '[perjanjian_kinerja_publik tahun_anggaran=' . $input['tahun_anggaran'] . ']',
    'show_header' => 1,
    'post_status' => 'publish'
));
$url_pk_pisah_page = $pk_pisah_page['url'] . "&id_skpd=" . $id_skpd;
$data_skpd = $this->get_data_unit_by_id_skpd_tahun_anggaran($id_skpd, $input['tahun_anggaran']);
?>
<style>
    .tr-tujuan {
        background: #0000ff1f;
    }

    .tr-sasaran {
        background: #ffff0059;
    }

    .tr-program,
    .tr-ind-program {
        background: #baffba;
    }

    .tr-kegiatan,
    .tr-ind-kegiatan {
        background: #13d0d03d;
    }

    .bawah {
        border-bottom: 1px solid #000;
    }

    .kiri {
        border-left: 1px solid #000;
    }

    .kanan {
        border-right: 1px solid #000;
    }

    .atas {
        border-top: 1px solid #000;
    }

    .text_tengah {
        text-align: center;
    }

    .text_kiri {
        text-align: left;
    }

    .text_kanan {
        text-align: right;
    }

    .text_blok {
        font-weight: bold;
    }

    #monev-body-renstra {
        word-break: break-word;
    }

    .table thead th {
        vertical-align: middle;
    }

    .table-sticky thead {
        position: sticky;
        top: -6px;
        background: #ffc491;
    }

    /* Mild Green (Pastel Success) - For achievement >= 75% */
    .bg-success-mild {
        background-color: #d4edda;
        color: #155724;
    }

    /* Mild Yellow (Pastel Warning) - For achievement >= 50% and < 75% */
    .bg-warning-mild {
        background-color: #fff3cd;
        color: #856404;
    }

    /* Mild Red (Pastel Danger) - For achievement < 50% */
    .bg-danger-mild {
        background-color: #f8d7da;
        color: #721c24;
    }

    :root {
        --zoom-scale: 1.0;
    }

    #main-content-pk {
        transform: scale(var(--zoom-scale));
        transform-origin: top left;
        transition: transform 0.2s ease-out;
    }

    #monev-body-renstra {
        word-break: break-word;
    }

    table th,
    #mod-monev th {
        vertical-align: middle;
    }

    .no_border tr,
    .no_border td,
    .no_border th,
    .no_border table {
        border: none !important;
    }

    body {
        overflow: auto;
    }

    td[contenteditable="true"] {
        background: #ff00002e;
        max-width: 300px;
    }

    td.target_realisasi[contenteditable="true"] {
        max-width: 150px;
    }

    th#bobotKinerja[contenteditable="true"] {
        background: #ff00002e;
        max-width: 600px;
    }

    .negatif {
        color: #ff0000;
    }

    .persentase {
        color: #9d00ff;
    }

    .nilai_akhir {
        color: #28bb00;
    }

    .renstra_kegiatan,
    .indikator_renstra {
        display: none;
    }

    #mod-monev table {
        margin: 0;
    }

    .edit-monev-file {
        padding: 3px 2px 3px 2px;
        margin: 0;
    }

    #data-file-monev th {
        vertical-align: top;
    }

    .edit-monev-file-danger {
        color: red;
    }

    .edit-monev-file-grey {
        color: grey;
    }

    .edit-monev-file-danger:hover {
        background: red;
        color: #fff;
    }

    .edit-monev-file-grey:hover {
        background: grey;
        color: #fff;
    }

    .display-indikator-renstra {
        display: none;
    }

    #tabel-monev-renja {
        font-size: 70%;
        border: solid black 1 px;
        table-layout: fixed;
    }

    #tabel-monev-renja thead {
        position: sticky;
        top: -6px;
        background: #ffc491;
    }

    #tabel-monev-renja tfoot {
        position: sticky;
        bottom: -6px;
        background: #ffc491;
    }


    .hover-shadow-lg {
        transition: box-shadow 0.3s ease-in-out;
    }

    .hover-shadow-lg:hover {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }

    .transition-shadow {
        transition: transform 0.3s ease;
    }

    .transition-shadow:hover {
        transform: translateY(-3px);
    }

    #tabel-monev-renja tr td {
        vertical-align: top;
    }
</style>
<div id="action-section" class="text-center m-4"></div>
<h1 class="text-center">Pemantauan Rencana Aksi<br><?php echo $data_skpd['nama_skpd']; ?><br> Tahun Anggaran <?php echo $input['tahun_anggaran']; ?></h1>
<div id="cetak" title="Laporan Rencana Aksi" style="padding: 5px; overflow: auto; max-height: 80vh;">
    <table id="tabel-monev-renja" cellpadding="2" cellspacing="0" contenteditable="false">
        <thead>
            <tr>
                <th rowspan="3" style="width: 60px;" class="atas kiri kanan bawah text_tengah text_blok">No</th>
                <th rowspan="3" style="width: 200px;" class="atas kiri kanan bawah text_tengah text_blok">Sasaran Strategis</th>
                <th rowspan="3" style="width: 200px;" class="atas kiri kanan bawah text_tengah text_blok">Indikator</th>
                <th rowspan="3" style="width: 200px;" class="atas kiri kanan bawah text_tengah text_blok">Satuan</th>
                <th rowspan="3" style="width: 200px;" class="atas kiri kanan bawah text_tengah text_blok">Target Kinerja Tahunan</th>
                <th colspan="4" rowspan="2" style="width: 250px;" class="atas kiri kanan bawah text_tengah text_blok">Target Kinerja Triwulanan</th>
                <th colspan="4" rowspan="2" style="width: 250px;" class="atas kiri kanan bawah text_tengah text_blok">Realisasi Kinerja</th>
                <th rowspan="3" style="width: 200px;" class="atas kiri kanan bawah text_tengah text_blok">Realisasi Kinerja</th>
                <th rowspan="3" style="width: 200px;" class="atas kiri kanan bawah text_tengah text_blok">Capaian Terhadap Target Tahun<br><small>%</small></th>
                <th rowspan="3" style="width: 200px;" class="atas kiri kanan bawah text_tengah text_blok">Predikat Capaian</th>

                <th rowspan="3" style="width: 125px;" class="atas kanan bawah text_tengah text_blok">Kode</th>
                <th rowspan="3" style="width: 300px;" class="atas kanan bawah text_tengah text_blok">Program, Kegiatan, Sub Kegiatan</th>
                <th rowspan="3" style="width: 200px;" class="atas kanan bawah text_tengah text_blok">Indikator Program / Kegiatan / Sub Kegiatan</th>
                <th rowspan="2" colspan="2" style="width: 300px;" class="atas kanan bawah text_tengah text_blok">
                    Target kinerja SKPD Tahun <?php echo $input['tahun_anggaran']; ?> yang dievaluasi
                </th>
                <th colspan="8" style="width: 1200px;" class="atas kanan bawah text_tengah text_blok">Realisasi Target dan Capaian Anggaran Pada Triwulan</th>
                <th rowspan="2" colspan="3" style="width: 300px;" class="atas kanan bawah text_tengah text_blok">
                    Capaian Realisasi Target dan Anggaran Tahun <?php echo $input['tahun_anggaran']; ?>
                </th>
                <th rowspan="3" style="width: 200px;" class="atas kanan bawah text_tengah text_blok">Penanggung Jawab</th>
                <th rowspan="3" style="width: 200px;" class="atas kanan bawah text_tengah text_blok">Faktor Pendukung</th>
                <th rowspan="3" style="width: 200px;" class="atas kanan bawah text_tengah text_blok">Faktor Penghambat</th>
                <th rowspan="3" style="width: 200px;" class="atas kanan bawah text_tengah text_blok">Catatan Verifikator / Rekomendasi</th>
                <th rowspan="3" style="width: 200px;" class="atas kiri kanan bawah text_tengah text_blok">Capaian Serapan Anggaran<br><small>%</small></th>
                <th rowspan="3" style="width: 200px;" class="atas kiri kanan bawah text_tengah text_blok">Capaian Kinerja Program<br><small>%</small></th>
                <th rowspan="3" style="width: 200px;" class="atas kiri kanan bawah text_tengah text_blok">Capaian Realisasi Fisik<br><small>%</small></th>
            </tr>

            <tr>
                <th colspan="2" class="atas kanan bawah text_tengah text_blok">I</th>
                <th colspan="2" class="atas kanan bawah text_tengah text_blok">II</th>
                <th colspan="2" class="atas kanan bawah text_tengah text_blok">III</th>
                <th colspan="2" class="atas kanan bawah text_tengah text_blok">IV</th>
            </tr>

            <tr>
                <th class="atas kanan bawah text_tengah text_blok">I</th>
                <th class="atas kanan bawah text_tengah text_blok">II</th>
                <th class="atas kanan bawah text_tengah text_blok">III</th>
                <th class="atas kanan bawah text_tengah text_blok">IV</th>
                <th class="atas kanan bawah text_tengah text_blok">I</th>
                <th class="atas kanan bawah text_tengah text_blok">II</th>
                <th class="atas kanan bawah text_tengah text_blok">III</th>
                <th class="atas kanan bawah text_tengah text_blok">IV</th>
                <th class="atas kanan bawah text_tengah text_blok">Target</th>
                <th class="atas kanan bawah text_tengah text_blok">Satuan</th>
                <th class="atas kanan bawah text_tengah text_blok">Realisasi Target</th>
                <th class="atas kanan bawah text_tengah text_blok">Capaian Anggaran (%)</th>
                <th class="atas kanan bawah text_tengah text_blok">Realisasi Target</th>
                <th class="atas kanan bawah text_tengah text_blok">Capaian Anggaran (%)</th>
                <th class="atas kanan bawah text_tengah text_blok">Realisasi Target</th>
                <th class="atas kanan bawah text_tengah text_blok">Capaian Anggaran (%)</th>
                <th class="atas kanan bawah text_tengah text_blok">Realisasi Target</th>
                <th class="atas kanan bawah text_tengah text_blok">Capaian Anggaran (%)</th>
                <th class="atas kanan bawah text_tengah text_blok">Realisasi Target</th>
                <th class="atas kanan bawah text_tengah text_blok">Capaian Target (%)</th>
                <th class="atas kanan bawah text_tengah text_blok">Capaian Anggaran (%)</th>
            </tr>

            <tr>
                <?php for ($i = 0; $i <= 38; $i++): ?>
                    <?php if ($i == 13) : ?>
                        <th class="kiri atas kanan bawah text_tengah text_blok"><?= $i ?> = (9+10+11+12)</th>
                    <?php elseif ($i == 14) : ?>
                        <th class="kiri atas kanan bawah text_tengah text_blok"><?= $i ?> = (13/4)*100</th>
                    <?php elseif ($i == 29) : ?>
                        <th class="kiri atas kanan bawah text_tengah text_blok"><?= $i ?> = (21+23+25+27)</th>
                    <?php elseif ($i == 30) : ?>
                        <th class="kiri atas kanan bawah text_tengah text_blok"><?= $i ?> = (29/19)*100</th>
                    <?php else : ?>
                        <th class="kiri atas kanan bawah text_tengah text_blok"><?= $i ?></th>
                    <?php endif; ?>
                <?php endfor; ?>
            </tr>
        </thead>

        <tbody id="body-pk">
        </tbody>
    </table>
</div>
<div class="hide-print" id="catatan" style="max-width: 900px; margin: 40px auto; padding: 20px; border: 1px solid #e5e5e5; border-radius: 8px; background-color: #f9f9f9;">
    <h4 style="font-weight: bold; margin-bottom: 20px; color: #333;">Catatan</h4>
    <ul style="list-style-type: disc; padding-left: 20px; line-height: 1.6; color: #555;">
        <li>Kolom nomor 15 (Predikat Capaian) warna <strong class="bg-success-mild">Hijau, Sangat Berhasil</strong> adalah <strong>( >= 100% )</strong></li>
        <li>Kolom nomor 15 (Predikat Capaian) warna <strong class="bg-success-mild">Hijau, Berhasil</strong> adalah <strong>( 75% s.d 99% )</strong></li>
        <li>Kolom nomor 15 (Predikat Capaian) warna <strong class="bg-warning-mild">Kuning, Cukup Berhasil</strong> adalah <strong>( 55% s.d 74% )</strong>
        </li>
        <li>Kolom nomor 15 (Predikat Capaian) warna <strong class="bg-danger-mild">Merah, Kurang Berhasil</strong> adalah <strong>( < 55% )</strong>
        </li>
        <li>Kolom nomor 16 s.d 34 adalah data MONEV RENJA Program, Kegiatan dan Sub Kegiatan. dibedakan melalui warna :</li>
        <li class="ml-5">Baris warna <strong class="tr-program">Hijau</strong>, merupakan baris Program.</li>
        <li class="ml-5">Baris warna <strong class="tr-kegiatan">Biru</strong>, merupakan baris Kegiatan.</li>
        <li class="ml-5">Baris warna <strong>Putih</strong>, merupakan baris Sub Kegiatan.</li>
        <li>Kolom nomor 36 (Capaian Serapan Anggaran) warna <strong class="bg-success-mild">Hijau</strong> adalah <strong>( 75% s.d 100% )</strong></li>
        <li>Kolom nomor 36 (Capaian Serapan Anggaran) warna <strong class="bg-warning-mild">Kuning</strong> adalah <strong>( 50% s.d 74% )</strong>
        </li>
        <li>Kolom nomor 36 (Capaian Serapan Anggaran) warna <strong class="bg-danger-mild">Merah</strong> adalah <strong>( < 50% )</strong>
        </li>
    </ul>
</div>
<script>
    jQuery(document).ready(() => {
        getDataTable()
        let extend_action = '';
        extend_action += '<a class="btn btn-primary mr-2" href="<?php echo $url_pk_pisah_page; ?>" target="_blank" style="text-decoration: none;"><span class="dashicons dashicons-controls-forward"></span> Perjanjian Kinerja</a>';

        jQuery('#action-section').append(extend_action);
    });

    function get_penanggung_jawab() {
        return jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_penanggung_jawab',
                id_skpd: <?php echo $id_skpd; ?>,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>
            },
            dataType: 'json',
        });
    }

    function get_html_pk_publik() {
        return jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_data_capaian_kinerja_publik_baru',
                id_skpd: <?php echo $id_skpd; ?>,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>
            },
            dataType: 'json',
        });
    }

    async function getDataTable() {
        jQuery('#wrap-loading').show();

        try {
            const [penanggung_jawab, html_pk_publik] = await Promise.all([
                get_penanggung_jawab(),
                get_html_pk_publik()
            ]);

            if (html_pk_publik.status === 'success') {
                jQuery('#body-pk').html(html_pk_publik.html);

                if (penanggung_jawab.status && penanggung_jawab.data) {
                    renderPenanggungJawab(penanggung_jawab.data);
                }
                if (penanggung_jawab.status && penanggung_jawab.table) {
                    jQuery('#tableDataOpd tbody').html(penanggung_jawab.table);
                    jQuery('[data-toggle="tooltip"]').tooltip();
                }
            } else {
                jQuery('#body-pk').html('<h1 class="text-center">Gagal memuat data.</h1>');
            }
        } catch (error) {
            console.error('Terjadi kesalahan saat memuat data:', error);
            jQuery('#body-pk').html('<h1 class="text-center text-danger">Terjadi kesalahan.</h1>');
        } finally {
            jQuery('#wrap-loading').hide();
        }
    }

    function renderPenanggungJawab(data) {
        Object.entries(data).forEach(([kodeCascading, daftarPegawai]) => {
            const elements = jQuery(`[data-kode-progkeg="${kodeCascading}"]`);

            if (elements.length > 0) {
                const listPJ = daftarPegawai
                    .map(pj => `<li><b>${pj.nama}</b> (${pj.jabatan})</li>`)
                    .join('');

                elements.each(function() {
                    jQuery(this).append(`<div class="mt-1"><ul>${listPJ}</ul></div>`);
                });
            }
        });
    }
</script>