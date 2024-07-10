<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'periode' => '',
), $atts);$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$periode = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        *
    FROM esakip_data_jadwal
    WHERE id=%d
      AND status = 1
", $input['periode']),
    ARRAY_A
);

$tahun_periode = $periode['tahun_selesai_anggaran'];
$tahun_mulai_anggaran = ($periode['tahun_selesai_anggaran'] - $periode['lama_pelaksanaan']) + 1;

$colspan = $periode['lama_pelaksanaan']*2;
$header_tahun = '<tr>';
$header_target = '<tr><th class="text-center">'.$tahun_mulai_anggaran.'</th>';
for($i=$tahun_mulai_anggaran; $i<=$tahun_periode; $i++){
    $header_tahun .= '
        <th colspan ="2" class="text-center">'.$i.'</th>
    ';
    $header_target.='
        <th class="text-center">Target</th>
        <th class="text-center">Capaian</th>
    ';
}
$header_tahun .= '</tr>';
$header_target .= '</tr>';

$idtahun = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT 
            *
        FROM esakip_data_jadwal
        WHERE tipe = %s",
        'RPJMD'
    ),
    ARRAY_A
);

$tahun = "<option value='-1'>Pilih Tahun Periode</option>";

foreach ($idtahun as $val) {
    $tahun_anggaran_selesai = $val['tahun_anggaran'] + $val['lama_pelaksanaan'];
    $selected = '';
    if (!empty($input['id']) && $val['id'] == $input['periode']) {
        $selected = 'selected';
    }
    $tahun .= "<option value='$val[id]' $selected>$val[nama_jadwal] Periode $val[tahun_anggaran] -  $tahun_anggaran_selesai</option>";
}

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$is_admin_panrb = in_array('admin_panrb', $user_roles);

$status_iku = $wpdb->get_row(
    $wpdb->prepare("
        SELECT  *
        FROM esakip_menu_dokumen
        WHERE nama_tabel='esakip_capaian_iku_pemda'
            AND id_jadwal=%d"
        ,$input['periode'])
        , ARRAY_A
);

?>
<style type="text/css">
    .wrap-table {
        overflow: auto;
        max-height: 100vh;
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
    #table_capaian_indikator_mikro th, 
    #table_capaian_indikator_mikro td {
        text-align: center;
        vertical-align: middle;
    }
    #table_capaian_indikator_mikro thead{
        position: sticky;
        top: -6px;
        background: #ffc491;
    }
    #table_capaian_indikator_mikro tfoot{
        position: sticky;
        bottom: -6px;
        background: #ffc491;
    }
</style>

<!-- Table -->
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Dokumen RPJMD <br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h1>
            <?php if (!$is_admin_panrb): ?>
                <div style="margin-bottom: 25px;">
                    <button class="btn btn-primary" onclick="tambah_dokumen_rpjmd();"><i class="dashicons dashicons-plus"></i> Tambah Data</button>
                </div>
            <?php endif; ?>
            <div class="wrap-table">
                <table id="table_dokumen_rpjmd" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                    <tr>
                            <th class="text-center" rowspan="2">No</th>
                            <th class="text-center" rowspan="2">Nama Dokumen</th>
                            <th class="text-center" rowspan="2">Keterangan</th>
                            <th class="text-center" rowspan="2">Waktu Upload</th>
                            <th class="text-center" rowspan="2" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if(!empty($status_iku['active']) AND $status_iku['active'] == 1): ?>
            <div style="padding: 10px;margin:0 0 3rem 0;">
                <h1 class="text-center" style="margin:3rem;">Capaian Indikator Makro <br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h1>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Capaian Indikator Makro</h1>
            <div class="wrap-table">
                <table id="table_capaian_indikator_mikro" cellpadding="2" cellspacing="0" style="font-family: 'Open Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="3" class="text-center" style="width: 50px;">No</th>
                            <th rowspan="3" class="text-center">Aspek/Fokus/ Bidang/Urusan/ Indikator Kinerja Pembangunan Daerah</th>
                            <th rowspan="3" class="text-center">Satuan</th>
                            <th colspan="1" rowspan="2" class="text-center">Kondisi kinerja pada awal periode RPJMD</th>
                            <th rowspan="3" class="text-center">Target Akhir P-RPJMD</th>
                            <th colspan="<?php echo $colspan; ?>" class="text-center">Capaian IKU </th>
                            <th rowspan="3" class="text-center">Sumber Data</th>
                            <th rowspan="3" class="text-center">Keterangan</th>
                        </tr>
                        <?php echo $header_tahun.$header_target; ?>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div id="chart-capaian-indikator" class="row"></div>
        </div>
    </div>
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Tambah Dokumen</h5>
                <h5 class="modal-title" id="editModalLabel">Edit Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $input['periode']; ?>" id="id_jadwal">
                    <input type="hidden" value="" id="idDokumen">
                    <div class="form-group">
                        <label for="fileUpload">Pilih File</label>
                        <input type="file" class="form-control-file" id="fileUpload" name="fileUpload" accept="application/pdf" required>
                        <div style="padding-top: 10px; padding-bottom: 10px;"><a id="fileUploadExisting" target="_blank"></a></div>
                    </div>
                    <div class="alert alert-warning mt-2" role="alert">
                        Maksimal ukuran file: <?php echo get_option('_crb_maksimal_upload_dokumen_esakip'); ?> MB. Format file yang diperbolehkan: PDF.
                    </div>
                    <div class="form-group">
                        <label for="nama_file">Nama Dokumen</label>
                        <input type="text" class="form-control" id="nama_file" name="nama_file" rows="3" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="submit_dokumen(this); return false">Unggah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tahun -->
<div class="modal fade" id="tahunModal" tabindex="-1" role="dialog" aria-labelledby="tahunModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tahunModalLabel">Pilih ID Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tahunForm">
                    <div class="form-group">
                        <label for="id_jadwal">ID Jadwal:</label>
                        <select class="form-control" id="id_jadwal" name="id_jadwal">
                            <?php echo $tahun; ?>
                        </select>
                        <input type="hidden" id="idDokumen" value="">
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="submit_tahun_rpjmd(); return false">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tahun Tabel -->
<div id="tahunContainer" class="container-md">
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    jQuery(document).ready(function() {
        getTableRpjmd();
        getTableCapaianIndikator();
        getTableTahun();
        jQuery("#fileUpload").on('change', function() {
            var id_dokumen = jQuery('#idDokumen').val();
            if (id_dokumen == '') {
                var name = jQuery("#fileUpload").prop('files')[0].name;
                jQuery('#nama_file').val(name);
            }
        });
    });

    function drawColColors() {
        json_chart.map(function(b, i){
            var id_cart = 'chart-capaian-indikator-'+b.id;
            var html = '<div id="'+id_cart+'" style="margin-buttom: 20px; min-height: 400px;" class="col-md-6"></div>';
            jQuery('#chart-capaian-indikator').append(html);
            var data_cart = [
                ['Tahun', 'Target', 'Realisasi'],
                ['Tahun Awal', +b.kondisi_awal, 0],
                ['Tahun 1', +b.target_bps_tahun_1, +b.bps_tahun_1],
                ['Tahun 2', +b.target_bps_tahun_2, +b.bps_tahun_2],
                ['Tahun 3', +b.target_bps_tahun_3, +b.bps_tahun_3],
                ['Tahun 4', +b.target_bps_tahun_4, +b.bps_tahun_4],
                ['Tahun 5', +b.target_bps_tahun_5, +b.bps_tahun_5],
                ['Tahun Akhir', +b.target_akhir_p_rpjmd, 0],
            ];
            console.log('data_cart', data_cart);
            
            var data = new google.visualization.arrayToDataTable(data_cart);

            var options = {
                title: b.indikator_kinerja,
                colors: ['#9575cd', '#33ac71'],
                hAxis: {
                    title: b.satuan,
                    minValue: 0
                },
                vAxis: {
                    title: 'Nilai'
                }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById(id_cart));
            chart.draw(data, options);
        });
    }

    function getTableCapaianIndikator() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_capaian_indikator',
                api_key: esakip.api_key,
                id_periode: <?php echo $input['periode']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_capaian_indikator_mikro tbody').html(response.data);

                    window.json_chart = response.json;
                    google.charts.load('current', {packages: ['corechart', 'bar']});
                    google.charts.setOnLoadCallback(drawColColors);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data Rpjmd!');
            }
        });
    }

    function getTableRpjmd() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_rpjmd',
                api_key: esakip.api_key,
                id_periode: <?php echo $input['periode']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen_rpjmd tbody').html(response.data);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data Rpjmd!');
            }
        });
    }

    function getTableTahun() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_tahun_rpjmd',
                api_key: esakip.api_key,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#tahunContainer').html(response.data);
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

    function lihatDokumen(dokumen) {
        let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/'; ?>' + dokumen;
        window.open(url, '_blank');
    }

    function set_tahun_dokumen(id) {
        jQuery('#tahunModal').modal('show');
        jQuery('#idDokumen').val(id);
    }

    function tambah_dokumen_rpjmd() {
        jQuery("#editModalLabel").hide();
        jQuery("#uploadModalLabel").show();
        jQuery('#nama_file').val('');
        jQuery("#idDokumen").val('');
        jQuery("#fileUpload").val('');
        jQuery("#keterangan").val('');
        jQuery('#fileUploadExisting').removeAttr('href').empty();
        jQuery("#uploadModal").modal('show');
    }

    function edit_dokumen_rpjmd(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_detail_rpjmd_by_id',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    let data = response.data;
                    let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/'; ?>' + data.dokumen;
                    jQuery("#idDokumen").val(data.id);
                    jQuery("#fileUpload").val('');
                    jQuery('#nama_file').val(data.dokumen);
                    jQuery('#fileUploadExisting').attr('href', url).html(data.dokumen);
                    jQuery("#keterangan").val(data.keterangan);
                    jQuery("#uploadModalLabel").hide();
                    jQuery("#editModalLabel").show();
                    jQuery('#uploadModal').modal('show');
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data!');
            }
        });
    }

    function submit_dokumen(that) {
        let id_dokumen = jQuery("#idDokumen").val();

        let keterangan = jQuery("#keterangan").val();
        if (keterangan == '') {
            return alert('Keterangan tidak boleh kosong');
        }
        let id_jadwal = jQuery("#id_jadwal").val();
        if (id_jadwal == '') {
            return alert('ID Jadwal tidak boleh kosong');
        }
        let fileDokumen = jQuery("#fileUpload").prop('files')[0];
        if (fileDokumen == '') {
            return alert('File Upload tidak boleh kosong');
        }
        let namaDokumen = jQuery("#nama_file").val();
        if (namaDokumen == '') {
            return alert('Nama Dokumen tidak boleh kosong');
        }

        let form_data = new FormData();
        form_data.append('action', 'tambah_dokumen_rpjmd');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id_dokumen', id_dokumen);
        form_data.append('keterangan', keterangan);
        form_data.append('id_jadwal', id_jadwal);
        form_data.append('fileUpload', fileDokumen);
        form_data.append('namaDokumen', namaDokumen);

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#uploadModal').modal('hide');
                    alert(response.message);
                    getTableRpjmd();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengirim data!');
                jQuery('#wrap-loading').hide();
            }
        });
    }

    function submit_tahun_rpjmd() {
        let id = jQuery("#idDokumen").val();
        if (id == '') {
            return alert('id tidak boleh kosong');
        }

        let id_jadwal = jQuery("#id_jadwal").val();
        if (id_jadwal == '') {
            return alert('ID Jadwal tidak boleh kosong');
        }

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'submit_tahun_rpjmd',
                id: id,
                id_jadwal: id_jadwal,
                api_key: esakip.api_key
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery('#tahunModal').modal('hide');
                    getTableTahun();
                    getTableRpjmd();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengirim data!');
            }
        });
    }

    function lihatDokumen(dokumen) {
        let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/'; ?>' + dokumen;
        window.open(url, '_blank');
    }


    function hapus_dokumen_rpjmd(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_dokumen_rpjmd',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableRpjmd();
                    getTableTahun();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                jQuery('#wrap-loading').hide();
                alert('Terjadi kesalahan saat mengirim data!');
            }
        });
    }

    function hapus_tahun_dokumen_rpjmd(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_tahun_dokumen_rpjmd',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableRpjmd();
                    getTableTahun();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                jQuery('#wrap-loading').hide();
                alert('Terjadi kesalahan saat mengirim data!');
            }
        });
    }
</script>