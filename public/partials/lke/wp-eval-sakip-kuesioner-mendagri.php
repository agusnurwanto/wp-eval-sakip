<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2025',
), $atts);

$id_skpd = !empty($_GET['id_skpd']) ? intval($_GET['id_skpd']) : 0;

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$skpd = $wpdb->get_row(
    $wpdb->prepare("
    SELECT
        nama_skpd
    FROM esakip_data_unit
    WHERE id_skpd=%d
        AND tahun_anggaran=%d
        AND active = 1
    ", $id_skpd, $tahun_anggaran_sakip),
    ARRAY_A
);

$idtahun = $wpdb->get_results("
    SELECT DISTINCT tahun_anggaran
    FROM esakip_data_unit
    ORDER BY tahun_anggaran DESC
    ", ARRAY_A);

$tahun = "<option value='-1'>Pilih Tahun</option>";

foreach ($idtahun as $val) {
    $selected = '';
    if (!empty($input['tahun']) && $val['tahun_anggaran'] == $input['tahun']) {
        $selected = 'selected';
    }
    $tahun .= "<option value='$val[tahun_anggaran]' $selected>$val[tahun_anggaran]</option>";
}
$current_user = wp_get_current_user();
$ret['debug_roles'] = $current_user->roles;
$user_roles = $current_user->roles;
$is_admin = in_array('administrator', $user_roles) || in_array('admin_panrb', $user_roles) || in_array("admin_bappeda", $current_user->roles) || in_array("administrator", $current_user->roles);

$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);

$html = '
<div class="action-section" class="hide-print"></div>
<div id="cetak" class="container-md">
    <h1 style="text-align: center !important; text-transform: uppercase">
        HASIL PENILAIAN TINGKAT KEMATANGAN INDIVIDU<br>PERANGKAT DAERAH ' . strtoupper($nama_pemda) . ' TAHUN ' . $input['tahun'] . '<br>' . $skpd['nama_skpd'] . '
    </h1>';
$html .= '
<div style="padding: 0 5px;">
    <table class="table table-bordered" style="width:100%; margin-bottom:10px;">
';
$html .= '
<thead>
    <tr>
        <th rowspan="2" style="text-align: center; vertical-align: middle; width: 30px;">No</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">Variabel</th>
        <th colspan="2" style="text-align: center;">Hasil Awal</th>
        <th colspan="2" style="text-align: center;">Hasil Akhir</th>
    </tr>
    <tr>
        <th style="text-align: center; width: 60px;">Tingkat</th>
        <th style="text-align: center; width: 60px;">Skor</th>
        <th style="text-align: center; width: 60px;">Tingkat</th>
        <th style="text-align: center; width: 60px;">Skor</th>
    </tr>
</thead>';

$data_kuesioner = $wpdb->get_results($wpdb->prepare("
    SELECT 
        * 
    FROM esakip_kuesioner_mendagri 
    WHERE tahun_anggaran = %d 
        AND active = 1 
    ORDER BY nomor_urut ASC
    ", $input['tahun']), 
    ARRAY_A
);

$no = 1;
$tbody = '';
$romawi = array(
    '1' => 'I',
    '2' => 'II',
    '3' => 'III',
    '4' => 'IV',
    '5' => 'V'
);
$total_skor_awal = 0;   // total skor dari kolom hasil awal (angka)
$total_skor_akhir = 0;  // total skor dari kolom hasil akhir (angka)

foreach ($data_kuesioner as $row) {
    $id_kuesioner = intval($row['id']);

    $pengisian = $wpdb->get_row($wpdb->prepare("
        SELECT 
            id_kuesioner_mendagri_detail,
            id_level,
            nilai_akhir,
            ket_verifikator
        FROM esakip_pengisian_kuesioner_mendagri
        WHERE id_kuesioner = %d
            AND id_skpd = %d
            AND tahun_anggaran = %d
            AND active = 1
    ", $id_kuesioner, $id_skpd, $input['tahun']),
    ARRAY_A
);
    $id_kuesioner_mendagri_detail = !empty($pengisian['id_kuesioner_mendagri_detail']) ? intval($pengisian['id_kuesioner_mendagri_detail']) : 0;
    $id_level = !empty($pengisian['id_level']) ? $pengisian['id_level'] : 0;
    $nilai_akhir = isset($pengisian['nilai_akhir']) ? $pengisian['nilai_akhir'] : null;
    $ket_verifikator = !empty($pengisian['ket_verifikator']) ? esc_html($pengisian['ket_verifikator']) : '-';
    $label_tingkat = $id_level != '0' ? $romawi[$id_level] : 'Belum Diisi';
    $label_nilai = ($nilai_akhir === null) 
        ? 'Belum Dinilai' 
        : $romawi[$nilai_akhir];
    $nilai_akhir_print = ($nilai_akhir === null) ? 0 : $nilai_akhir;

    $total_skor_awal += $id_level;
    $total_skor_akhir += $nilai_akhir_print;

    $kategori_range = [
    "SANGAT TINGGI" => [46, 55],
    "TINGGI"        => [38, 45],
    "SEDANG"        => [29, 37],
    "RENDAH"        => [20, 28],
    "SANGAT RENDAH" => [1, 19]
    ];

    $kategori_awal = "-";
    foreach ($kategori_range as $label => $range) {
        if ($total_skor_awal >= $range[0] && $total_skor_awal <= $range[1]) {
            $kategori_awal = $label;
            break;
        }
    }

    $kategori_final = "-";
    foreach ($kategori_range as $label => $range) {
        if ($total_skor_akhir >= $range[0] && $total_skor_akhir <= $range[1]) {
            $kategori_final = $label;
            break;
        }
    }

    // Ambil jenis_bukti_dukung dari data_dukung
    $jenis_bukti_dukung = '-';
    if ($id_kuesioner_mendagri_detail > 0) {
        $bukti_list = $wpdb->get_results($wpdb->prepare("
            SELECT jenis_bukti_dukung 
            FROM esakip_data_dukung_kuesioner_mendagri 
            WHERE id_kuesioner_mendagri_detail = %d
        ", $id_kuesioner_mendagri_detail));

        if (!empty($bukti_list)) {
            $list = array();
            foreach ($bukti_list as $bukti) {
                if (!empty($bukti->jenis_bukti_dukung)) {
                    $list[] = esc_html($bukti->jenis_bukti_dukung);
                }
            }
            if (!empty($list)) {
                $jenis_bukti_dukung = implode('<br>', $list); 
            }
        }
    }

    $ket_html = '';
    if (!empty($ket_verifikator) && $ket_verifikator !== '-') {
        $ket_html = "
            <br>
            <br>
            <span>
                Keterangan Verifikator :
                <br>
                <b>$ket_verifikator</b>
            </span>
        ";
    }

    $tbody .= '<tr>'; 
    $tbody .= '<td style="text-align: center; vertical-align: middle;">' . $no++ . '</td>'; 
    $tbody .= "
        <td class='text-left' style='vertical-align: middle;'>
            <span>" . $row['deskripsi'] . "</span>
            <br>
            <br>
            <span>$jenis_bukti_dukung</span>
            $ket_html
        </td>";

    $tbody .= "<td style='text-align: center; vertical-align: middle;'>" . $label_tingkat . "</td>";      
    $tbody .= "<td style='text-align: center; vertical-align: middle;'>" . $id_level . "</td>";      
	$tbody .= "<td style='text-align: center; vertical-align: middle;'>" . $label_nilai . "</td>";
	$tbody .= "<td style='text-align: center; vertical-align: middle;'>" . $nilai_akhir_print . "</td>";
    $tbody .= '</tr>'; 
}

    $tbody .= '
        <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; font-size: 15px;">
                SKOR KEMATANGAN : ' . $total_skor_awal . ' , KATEGORI : ' . $kategori_awal . '
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; font-size: 15px;">
                SKOR KEMATANGAN FINAL : ' . $total_skor_akhir . ' , KATEGORI : ' . $kategori_final . '
            </td>
        </tr>';


$html .= '<tbody>' . $tbody . '</tbody></table></div>';

$laporan_kuesioner = $this->functions->modifyGetParameter(false, 'laporan', 'kuesioner');

$laporan = false;
if(!empty($_GET['laporan'])){
    $laporan = $_GET['laporan'];
}
$html .= '</div>';

?>
<style>
    .wrap-table {
        overflow: auto;
        max-height: 100vh;
        width: 100%;
    }

    .trasparent-button {
        width: 100%;
    }

    .btn-action-group {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .btn-action-group .btn {
        margin: 0 5px;
    }

    .align-middle {
        vertical-align: middle !important;
    }
</style>
<?php if(empty($laporan)): ?>
<div class="container-md">
    <div style="padding: 10px;margin:0 0 3rem 0;">
        <h1 class="text-center" style="margin:3rem;">Kuesioner Mendagri<br>
            <?php echo strtoupper($skpd['nama_skpd']); ?><br>
            Tahun Anggaran <?php echo $input['tahun']; ?>
        </h1>
        <div id="action-sakip" class="hide-print">
                <div id="action-sakip" class="hide-print">
                    <a href="<?php echo $laporan_kuesioner; ?>" target="_blank" class="btn btn-success">Laporan Kuesioner</a>
                </div>
        </div>
        <div class="wrap-table">
            <table id="table_kuesioner_pengisian_mendagri" cellpadding="2" cellspacing="0" style="collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" colspan="3" rowspan="2" style="vertical-align: middle;">Kuesioner/Indikator</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle;">Keterangan Perangkat Daerah</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle;">Keterangan Verifikator</th>
                        <th class="text-center" colspan="2" style="vertical-align: middle;">Nilai Awal</th>
                        <th class="text-center" colspan="2" style="vertical-align: middle;">Nilai Akhir</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle;">Aksi</th>
                    </tr>
                    <tr>
                        <th class="text-center">Tingkat</th>
                        <th class="text-center">Skor</th>
                        <th class="text-center">Tingkat</th>
                        <th class="text-center">Skor</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal tambah bukti dukung -->
<div class="modal fade bd-example-modal-xl" id="tambahBuktiDukung" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahBuktiDukungLabel">Pilih Bukti Dukung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="text-center">Upload Bukti Dukung</h5>
                <div id="uploadBuktiDukung" class="text-center" style="margin-bottom: 20px;"></div>
                <table id="tableBuktiDukung" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">Jenis Bukti Dukung</th>
                            <th class="text-center">Nama Dokumen</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Tanggal Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <input type="hidden" id="id_detail">
                <input type="hidden" id="idKuesionerPertanyaanDetail">
                <input type="hidden" id="jenis_bukti_dukung_json">
                <input type="hidden" id="idKuesioner">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submit_bukti_dukung(); return false">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Tingkat kematangan -->
<div class="modal fade" id="tambahKuesionerDetailModal" tabindex="-1" role="dialog" aria-labelledby="tambahKuesionerDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="tambahKuesionerDetailModalLabel">Pilih Tingkat Kematangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="idKuesionerPertanyaan">
                <input type="hidden" id="id_detail_level">
                <input type="hidden" id="id_skpd" value="<?php echo (int) $id_skpd; ?>">

                <div class="form-group col-md-12">
                    <label for="level">Tingkat Kematangan</label>
                    <select class="form-control" id="level" name="level"  <?php echo $is_admin ? 'disabled' : ''; ?>>
                    </select>
                </div>

                <div id="wrap-indikator-bukti-dukung" style="display:none;">
                    <div class="form-group col-md-12">
                        <label>Indikator</label>
                        <input type="text" class="form-control" id="indikator" name="indikator" readonly>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Penjelasan</label>
                        <input type="text" class="form-control" id="penjelasan" name="penjelasan" readonly>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Jenis Bukti Dukung</label>
                        <textarea class="form-control" id="jenis_bukti_dukung" rows="3" readonly></textarea>
                    </div>
                </div>                                   
                
                <div class="form-group col-md-12">
                    <label for="KeteranganOpd">Keterangan Perangkat Daerah</label>
                    <textarea class="form-control" id="KeteranganOpd" rows="3" <?php echo $is_admin ? 'readonly' : ''; ?>></textarea>
                </div>

                <div class="form-group col-md-12">
                    <label for="NilaiAkhir">Nilai Akhir</label>
                    <select class="form-control" id="NilaiAkhir" name="NilaiAkhir"  <?php echo !$is_admin ? 'disabled' : ''; ?>>
                        <option value=""> Pilih Nilai Akhir </option>
                            <option value="1">Tingkat I</option>
                            <option value="2">Tingkat II</option>
                            <option value="3">Tingkat III</option>
                            <option value="4">Tingkat IV</option>
                            <option value="5">Tingkat V</option>
                    </select>
                </div>
                
                <div class="form-group col-md-12">
                    <label for="KeteranganVerif">Keterangan Verifikator</label>
                    <textarea class="form-control" id="KeteranganVerif" rows="3" <?php echo $is_admin ? '' : 'readonly'; ?>></textarea>
                </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="submit_tingkat(); return false">Simpan</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if($laporan == 'kuesioner'): ?>   
    <?php echo $html; ?>
<?php endif; ?>
<script>
    jQuery(document).ready(function() {
        run_download_excel_sakip();
        <?php if(empty($laporan)): ?>
            get_table_variabel_pengisian_mendagri();

            jQuery(document).on('change', '#level', function() {
                getIndikatorDanBuktiDukung();
            });
        <?php endif; ?>
    });

    <?php if(empty($laporan)): ?>
    function get_table_variabel_pengisian_mendagri() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_variabel_pengisian_mendagri',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_kuesioner_pengisian_mendagri tbody').html(response.data);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat tabel!');
            }
        });
    }

    function getIndikatorDanBuktiDukung() {
        const id_kuesioner = jQuery('#idKuesionerPertanyaan').val();
        const selectedOption = jQuery('#level option:selected');
        const id_detail_level = selectedOption.val();
        const id_skpd = jQuery('#id_skpd').val();
        const level = jQuery('#level').val();
        //console.log("Kirim get_indikator_bukti_by_level => id_detail_level:", id_detail_level);
        if (level) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_indikator_bukti_by_level',
                    api_key: esakip.api_key,
                    id_kuesioner_mendagri_detail: id_detail_level,
                    tahun_anggaran: <?php echo $input['tahun']; ?>,
                    id_skpd: id_skpd
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        jQuery('#wrap-loading').hide();
                        jQuery('#indikator').val(response.data.indikator);
                        jQuery('#jenis_bukti_dukung').val(response.data.jenis_bukti_dukung);
                        jQuery('#penjelasan').val(response.data.penjelasan);
                        jQuery('#KeteranganOpd').val(response.data.ket_opd);
                        jQuery('#KeteranganVerif').val(response.data.ket_verifikator);
                        jQuery('#id_detail_level').val(response.data.id_detail_level);
                        jQuery('#NilaiAkhir').val(response.data.nilai_akhir);
                    } else {
                        alert(response.message);
                        jQuery('#wrap-loading').hide();
                        jQuery('#indikator').val('');
                        jQuery('#jenis_bukti_dukung').val('');
                        jQuery('#penjelasan').val('');
                    }
                },

                error: function() {
                    jQuery('#indikator').val('');
                    jQuery('#jenis_bukti_dukung').val('');
                }
            });
        } else {
            jQuery('#indikator').val('');
            jQuery('#jenis_bukti_dukung').val('');
        }
    }

    function getAllLevelByIdVariabel(idVariabel) {
        return jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_all_level_by_id_variabel',
                api_key: esakip.api_key,
                id_variabel: idVariabel
            },
            dataType: 'json'
        });
    }

    async function tambahKuesionerDetail(id_variabel, id_level = '') {
        try {
            jQuery('#wrap-loading').show();
            jQuery('#idKuesionerPertanyaan').val(id_variabel);

            // Ambil semua level
            const response = await getAllLevelByIdVariabel(id_variabel);

            let allLevel = [];
            if (response.status) {
                allLevel = response.data;
            } else {
                throw new Error(response.message || 'Gagal mendapatkan data level.');
            }

            // Konversi angka ke romawi
            const romawi = ['', 'I', 'II', 'III', 'IV', 'V'];

            // Buat isi dropdown
            let options = `<option value="">Pilih Tingkat Kematangan</option>`;
            allLevel.forEach(function(val) {
                const label = romawi[val.level] || val.level;
                options += `<option value="${val.id}" data-level="${val.level}">Tingkat ${label}</option>`;
            });

            // Cari ID detail level dari level angka (misal 1, 2, 3)
            let id_detail_level = '';
            if (id_level !== '') {
                const found = allLevel.find(item => item.level == id_level);
                if (found) {
                    id_detail_level = found.id;
                }
            }
            
            jQuery('#level').html(options).val(id_detail_level);

            // Kosongkan field lain
            jQuery('#indikator').val('');
            jQuery('#jenis_bukti_dukung').val('');
            jQuery('#penjelasan').val('');
            jQuery('#KeteranganOpd').val('');
            jQuery('#KeteranganVerif').val('');
            jQuery('#id_detail_level').val('');
            jQuery('#NilaiAkhir').val('');

            // Jika sudah ada level, ambil indikator & bukti
            if (id_level !== '') {
                await getIndikatorDanBuktiDukung();
            } else {
                jQuery('#wrap-loading').hide();
            }

            // Tampilkan modal
            jQuery('#wrap-indikator-bukti-dukung').show();
            jQuery('#tambahKuesionerDetailModal').modal('show');
            
        } catch (err) {
            console.error('Terjadi kesalahan saat memuat data tingkat kematangan:', err);
            alert('Gagal memuat data tingkat kematangan. Silakan coba lagi.');
        }
    }

    function submit_tingkat() {
        const id_kuesioner = jQuery('#idKuesionerPertanyaan').val();
        const selectedOption = jQuery('#level option:selected');
        const id_detail_level = selectedOption.val();
        const level = selectedOption.data('level'); 
        const indikator = jQuery('#indikator').val();
        const penjelasan = jQuery('#penjelasan').val();
        const ket_opd= jQuery('#KeteranganOpd').val();
        const ket_verifikator= jQuery('#KeteranganVerif').val();
        const nilai_akhir= jQuery('#NilaiAkhir').val();
        const id_skpd = jQuery('#id_skpd').val();
        if (!id_detail_level || !level) {
            return alert('Tingkat kematangan harus dipilih!');
        }

        if (confirm('Apakah anda yakin untuk menyimpan data ini?')) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: "submit_detail_kuesioner",
                    api_key: esakip.api_key,
                    tahun_anggaran: <?php echo $input['tahun']; ?>,
                    id_variabel: id_kuesioner,
                    id_detail_level: id_detail_level,
                    id_skpd: id_skpd,
                    level: level,
                    indikator: indikator,
                    penjelasan: penjelasan,
                    ket_opd: ket_opd,
                    ket_verifikator: ket_verifikator,
                    nilai_akhir: nilai_akhir
                },
                dataType: "json",
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if (response.status) {
                        alert('Berhasil disimpan!');
                        jQuery('#tambahKuesionerDetailModal').modal('hide');
                        jQuery('#wrap-indikator-bukti-dukung input, #wrap-indikator-bukti-dukung textarea').val('');
                        get_table_variabel_pengisian_mendagri();
                    } else {
                        alert('Gagal: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(error);
                    alert('Terjadi kesalahan saat menyimpan tingkat.');
                }
            });
        }
    }

    function tambahDokumenBuktiDukung(id_detail_pengisian, id_jenis_data_dukung) {
        var id_skpd = jQuery('#id_skpd').val();
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_dokumen_bukti_dukung_kuesioner',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id_detail_pengisian: id_detail_pengisian,
                id_jenis_data_dukung: id_jenis_data_dukung,
                id_skpd: id_skpd
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();

                if (response.status == 'success') {
                    var html = '';
                    var url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/'; ?>';
                    var list_bukti_dukung = {};
                    response.data_existing.map(function(b, i) {
                        list_bukti_dukung[b] = b;
                    });
                    for (var i in response.data) {
                        response.data[i].map(function(data, ii) {
                            var checked = '';
                            var namaFile = data.dokumen;
                            //console.log('Dokumen:', data.dokumen);
                            //console.log('Nama file:', namaFile);
                            //console.log('data_existing =', response.data_existing);

                            if (response.data_existing.includes(data.dokumen)) {
                                checked = 'checked';
                            }

                            var disabled = response.is_admin ? 'disabled' : '';

                            html += '' +
                                '<tr>' +
                                '<td class="text-center"><input type="checkbox" ' + checked + ' ' + disabled + ' class="list-dokumen" value="' + data.dokumen + '"/></td>' +
                                '<td>' + i + '</td>' +
                                '<td><a href="' + url + data.dokumen + '" target="_blank">' + data.dokumen + '</a></td>' +
                                '<td>' + data.keterangan + '</td>' +
                                '<td class="text-center">' + data.created_at + '</td>' +
                                '</tr>';
                        });
                    };
                    jQuery('#tableBuktiDukung tbody').html(html);
                    jQuery('#uploadBuktiDukung').html(response.upload_bukti_dukung);
                    jQuery('#jenis_bukti_dukung_json').val(id_jenis_data_dukung);
                    jQuery('#id_detail_pengisian_hidden').val(id_detail_pengisian);
                    jQuery('#tambahBuktiDukung').modal('show');

                    if (response.is_admin) {
                        jQuery('#tambahBuktiDukung .btn-primary').prop('disabled', true); // tombol Simpan disable
                    } else {
                        jQuery('#tambahBuktiDukung .btn-primary').prop('disabled', false); // enable untuk user yang bisa edit
                    }
                    
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengambil data!');
            }
        });
    }

    function submit_bukti_dukung() {
        var jenis_bukti_dukung = jQuery('#jenis_bukti_dukung_json').val();
        console.log('jenis_bukti_dukung:', jenis_bukti_dukung);
        var id_skpd = jQuery('#id_skpd').val();
        var id_kuesioner_mendagri_detail = jQuery('#idKuesionerPertanyaanDetail').val();
        var id_detail_pengisian = jQuery('#id_detail_pengisian_hidden').val();
        var bukti_dukung = [];

        jQuery('#tableBuktiDukung tbody .list-dokumen').map(function(i, b) {
            if (jQuery(b).is(':checked')) {
                bukti_dukung.push(jQuery(b).val());
            }
        });

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'submit_bukti_dukung_kuesioner',
                api_key: esakip.api_key,
                id_jenis_bukti_dukung: jenis_bukti_dukung,
                id_skpd: id_skpd,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id_detail_pengisian: id_detail_pengisian,
                dokumen_upload: bukti_dukung
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status == 'error') {
                    alert(response.message);
                } else {
                    var url = esakip.plugin_url + 'public/media/dokumen/';
                    var html = '';
                    if (Array.isArray(response.data)) {
                        response.data.map(function(b, i) {
                            html += '<a href="' + url + b + '" target="_blank">' + b + '</a>';
                        });
                    }
                    alert(response.message);
                    jQuery('#tambahBuktiDukung').modal('hide');
                    get_table_variabel_pengisian_mendagri();
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                alert("An error occurred: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    <?php endif; ?>
</script>