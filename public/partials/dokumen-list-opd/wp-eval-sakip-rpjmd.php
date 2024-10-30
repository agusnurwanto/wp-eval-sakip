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


//tabel capaian indikator makro
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

//tabel tambah data capaian indikator makro
$tahun_periode_makro = $periode['tahun_selesai_anggaran'];
$tahun_mulai_anggaran_makro = ($periode['tahun_selesai_anggaran'] - $periode['lama_pelaksanaan']) + 1;

$colspan_makro = $periode['lama_pelaksanaan']*2;
$header_tahun_makro = '<tr>';
$header_target_makro = '<tr><th class="text-center table-secondary">'.$tahun_mulai_anggaran.'</th>';
for($i=$tahun_mulai_anggaran; $i<=$tahun_periode; $i++){
    $header_tahun_makro .= '
        <th colspan ="2" class="text-center table-secondary">'.$i.'</th>
    ';
    $header_target_makro.='
        <th class="text-center table-secondary">Target</th>
        <th class="text-center table-secondary">Capaian</th>
    ';
}
$header_tahun_makro .= '</tr>';
$header_target_makro .= '</tr>';
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
$status_api_esr = get_option('_crb_api_esr_status');

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
    thead th {
        vertical-align: middle !important;
        font-size: small;
        text-align: center;
    }

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

    #modal-tambah-capaian-indikator .modal-body {
        max-height: 90vh;
        overflow-y: auto;
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
                    <?php
                    if($status_api_esr){
                        echo '<button class="btn btn-warning" onclick="sync_to_esr();" id="btn-sync-to-esr" style="display:none"><i class="dashicons dashicons-arrow-up-alt"></i> Kirim Data ke ESR</button>';
                    }
                    ?>
                </div>
            <?php endif; ?>
            <div class="wrap-table">
                <table id="table_dokumen_rpjmd" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                    <tr>
                            <th class="text-center" rowspan="2">No</th>
                            <?php
                            if (!$is_admin_panrb):
                                if($status_api_esr){
                                    echo '<th class="text-center" id="check-list-esr" style="display:none">Checklist ESR</th>';
                                }
                            endif;
                            ?>
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
            <div class="wrap-table" id="non_esr_lokal" style="display:none;">
                <h3 class="text-center" style="margin:3rem;">Dokumen ESR yang tidak ada di Lokal</h3>
                <table id="table_non_esr_lokal" cellpadding="2" cellspacing="0" style="font-family:Open Sans,-apple-system,BlinkMacSystemFont,Segoe UI,sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Dokumen</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="hide-print" id="catatan_dokumentasi" style="max-width: 1000px; margin: 40px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f8f9fa;">
                    <h4 style="font-weight: bold; margin-bottom: 20px; color: #333;">Catatan:</h4>
                    <ul style="list-style-type: disc; padding-left: 20px; line-height: 1.6; color: #555;">
                        <li>Abaikan perbedaan nama atau keterangan jika kedua dokumen PDF (ESR dan LOKAL) masih identik.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Capaian Indikator -->
<div class="container-md">
    <div class="cetak">
        <div>
        <?php if(!empty($status_iku['active']) AND $status_iku['active'] == 1): ?>
            <div>
                <h1 class="text-center" >Capaian Indikator Makro <br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h1>
            </div>
        <?php endif; ?>
            <div class="text-center" style="margin-bottom: 25px;">
                <div id="action" class="action-section hide-excel"></div>
            </div>
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
<!-- Modal Renaksi -->
<div class="modal fade" id="modal-tambah-capaian-indikator" role="dialog" data-backdrop="static" aria-hidden="true">'
    <div class="modal-dialog" style="max-width: 100%;" role="document">
        <div class="modal-content">
            <div class="modal-header bgpanel-theme">
                <h4 style="margin: 0;" class="modal-title">Data Capaian Indikator Makro</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span><i class="dashicons dashicons-dismiss"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="capaian-indikator" role="tabpanel" aria-labelledby="capaian-indikator-tab"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal crud -->
<div class="modal fade" id="modal-crud" data-backdrop="static"  role="dialog" aria-labelledby="modal-crud-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
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

        run_download_excel_sakip();
        <?php if (!$is_admin_panrb): ?>
            jQuery('#action-sakip').prepend('<a style="margin-right: 10px;" id="tambah-capaian-indikator" onclick="return false;" href="#" class="btn btn-primary hide-print"><i class="dashicons dashicons-plus"></i> Tambah Data</a>');
        <?php endif; ?>
        jQuery("#tambah-capaian-indikator").on('click', function(){
            getCapaianIndikator();
        });
    });

    function getCapaianIndikator() {
        jQuery("#wrap-loading").show();
        return new Promise(function(resolve, reject) {
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": "get_data_capaian_indikator",
                    "api_key": esakip.api_key,
                    "id_jadwal": <?php echo $input['periode']; ?>
                },
                dataType: "json",
                success: function(res) {
                    jQuery('#wrap-loading').hide();
                    
                    let getCapaianIndikator = ''
                        + `<div style="margin-top:10px">`
                            + `<button type="button" class="btn btn-success mb-2" onclick="tambah_capaian_indikator();"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
                        + `</div>`
                        + `<table class="table" id="getCapaianIndikator" style="margin: .5rem 0 2rem;">`
                            + `<thead>` 
                                + `<tr class="table-secondary">`
                                    + `<th rowspan="3" class="text-center" style="width:20px ">No</th>`
                                    + `<th rowspan="3" class="text-center">Aspek/Fokus/ Bidang/Urusan/ Indikator Kinerja Pembangunan Daerah</th>`
                                    + `<th rowspan="3" class="text-center">Sumber Data</th>`
                                    + `<th rowspan="3" class="text-center">Keterangan</th>`
                                    + `<th rowspan="3" class="text-center" style="width:120px;">Satuan</th>`
                                    + `<th colspan="1" rowspan="2" class="text-center" style="width:50px;">Kondisi Awal</th>`
                                    + `<th rowspan="3" class="text-center" style="width:50px;">Target Akhir</th>`
                                    + `<th colspan="<?php echo $colspan_makro; ?>" class="text-center">Capaian IKU</th>`
                                    + `<th rowspan="3" class="text-center" style="width:110px">Aksi</th>`
                                + `</tr>`
                                + `<?php echo $header_tahun_makro.$header_target_makro; ?>`
                            + `</thead>`
                            + `<tbody>`;

                    res.data.map(function(value, index) {
                        getCapaianIndikator += ''
                            + `<tr>`
                                + `<td class="text-center">${index+1}</td>`
                                + `<td class="indikator_kinerja">${value.indikator_kinerja}</td>`
                                + `<td class="sumber_data">${value.sumber_data}</td>`
                                + `<td class="keterangan_capaian_indikator">${value.keterangan}</td>`
                                + `<td class="text-center satuan">${value.satuan}</td>`
                                + `<td class="text-center kondisi_awal">${value.kondisi_awal}</td>`
                                + `<td class="text-center target_akhir_p_rpjmd">${value.target_akhir_p_rpjmd}</td>`;

                        for(let i = 1; i <= <?php echo $periode['lama_pelaksanaan']; ?>; i++) {
                            getCapaianIndikator += `
                                <td class="text-center">${value['target_bps_tahun_' + i] || ''}</td>
                                <td class="text-center">${value['bps_tahun_' + i] || ''}</td>`;
                        }

                        getCapaianIndikator += `
                                <td class="text-center">
                                    <a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-primary" onclick="edit_capaian_indikator(${value.id}, 1)" title="Edit"><i class="dashicons dashicons-edit"></i></a> <br>
                                    <a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger" onclick="hapus_capaian_indikator(${value.id}, 1);" title="Hapus" style="margin-top: 10px;"><i class="dashicons dashicons-trash"></i></a>
                                </td>
                            </tr>`;
                    });

                    getCapaianIndikator += ''
                        + `</tbody>`
                        + `</table>`;

                    jQuery("#capaian-indikator").html(getCapaianIndikator);
                    jQuery('.nav-tabs a[href="#capaian-indikator"]').tab('show');
                    jQuery('#modal-tambah-capaian-indikator').modal('show');
                    resolve();
                }
            });
        });
    }

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
                if(response.status_mapping_esr){
                    tahun_anggaran_periode_dokumen = response.tahun_anggaran_periode_dokumen;
                    let body_non_esr_lokal=``;
                    if(response.non_esr_lokal.length > 0){
                        response.non_esr_lokal.forEach((value, index) => {
                            body_non_esr_lokal+=`
                                <tr>
                                    <td class="text-center" data-upload-id="${value.upload_id}">${index+1}.</td>
                                    <td>${value.nama_file}</td>
                                    <td>${value.keterangan}</td>
                                    <td class="text-center"><a class="btn btn-sm btn-info" href="${value.path}" title="Lihat Dokumen" target="_blank"><span class="dashicons dashicons-visibility"></span></a></td>
                                </tr>
                            `;
                        });
                        jQuery("#table_non_esr_lokal tbody").html(body_non_esr_lokal);
                    }

                    jQuery("#btn-sync-to-esr").show();
                    jQuery("#check-list-esr").show();
                    jQuery("#non_esr_lokal").show();
                }
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
        let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/dokumen_pemda/'; ?>' + dokumen;
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
                    let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/dokumen_pemda/'; ?>' + data.dokumen;
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

    function tambah_capaian_indikator(){
        return new Promise(function(resolve, reject){
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": "get_data_capaian_indikator",
                    "api_key": esakip.api_key,
                    "id_jadwal": <?php echo $input['periode']; ?>
                },
                dataType: "json",
                success: function(res){
                    jQuery('#wrap-loading').hide();
                    jQuery("#modal-crud").find('.modal-title').html('Tambah Capaian Indikator');
                    let get_bps = '';
                    
                    <?php for ($i = 1; $i <= $periode['lama_pelaksanaan']; $i++) : ?>
                        get_bps += `
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="target_bps_tahun_<?php echo $i; ?>">Target BPS Tahun <?php echo $i; ?></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="target_bps_tahun_<?php echo $i; ?>" name="target_bps_tahun_<?php echo $i; ?>"/>
                                </div>
                                <div class="col-md-2">
                                    <label for="bps_tahun_<?php echo $i; ?>">Capaian BPS Tahun <?php echo $i; ?></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="bps_tahun_<?php echo $i; ?>" name="bps_tahun_<?php echo $i; ?>"/>
                                </div>
                            </div>`;
                    <?php endfor; ?>

                    jQuery("#modal-crud").find('.modal-body').html(''
                        +`<form id="form-capaian-indikator">`
                            +'<input type="hidden" id="id_capaian_indikator" value=""/>'
                            +`<div class="form-group row">`
                                +'<div class="col-md-2">'
                                    +`<label for="indikator_kinerja">Aspek/Fokus/ Bidang/Urusan/ Indikator Kinerja</label>`
                                +'</div>'
                                +'<div class="col-md-10">'
                                    +`<textarea class="form-control" name="label" id="indikator_kinerja" placeholder="Tuliskan Aspek/Fokus/ Bidang/Urusan/ Indikator Kinerja Pembangunan Daerah..."></textarea>`
                                +'</div>'
                            +`</div>`
                            +`<div class="form-group row">`
                                +'<div class="col-md-2">'
                                    +`<label for="sumber_data">Sumber Data</label>`
                                +'</div>'
                                +'<div class="col-md-10">'
                                    +`<input type="text" class="form-control" id="sumber_data"/>`
                                +'</div>'
                            +`</div>`
                            +`<div class="form-group row">`
                                +'<div class="col-md-2">'
                                    +`<label for="satuan">Satuan</label>`
                                +'</div>'
                                +'<div class="col-md-10">'
                                    +`<input type="text" class="form-control" id="satuan"/>`
                                +'</div>'
                            +`</div>`
                            +`<div class="form-group row">`
                                +'<div class="col-md-2">'
                                    +`<label for="keterangan_capaian_indikator">Keterangan</label>`
                                +'</div>'
                                +'<div class="col-md-10">'
                                    +`<input type="text" class="form-control" id="keterangan_capaian_indikator"/>`
                                +'</div>'
                            +`</div>`
                            +`<div class="form-group row">`
                                +'<div class="col-md-2">'
                                    +`<label for="kondisi_awal">Target Awal</label>`
                                +'</div>'
                                +'<div class="col-md-4">'
                                    +`<input type="text" class="form-control" id="kondisi_awal"/>`
                                +'</div>'
                                +'<div class="col-md-2">'
                                    +`<label for="target_akhir_p_rpjmd">Target Akhir</label>`
                                +'</div>'
                                +'<div class="col-md-4">'
                                    +`<input type="text" class="form-control" id="target_akhir_p_rpjmd"/>`
                                +'</div>'
                            +`</div>`
                            + get_bps 
                        +`</form>`);

                    jQuery("#modal-crud").find('.modal-footer').html(''
                        +'<button type="button" class="btn btn-danger" data-dismiss="modal">'
                            +'Tutup'
                        +'</button>'
                        +'<button type="button" class="btn btn-success" onclick="simpan_data_capaian_indikator(1)">'
                            +'Simpan'
                        +'</button>');
                    jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
                    jQuery("#modal-crud").find('.modal-dialog').css('width','');
                    jQuery("#modal-crud").modal('show');

                    resolve();
                }
            });
        });
    }

    function simpan_data_capaian_indikator() {
        let satuan = jQuery("#satuan").val();
        let keterangan = jQuery("#keterangan_capaian_indikator").val();
        let kondisi_awal = jQuery("#kondisi_awal").val();
        let target_akhir_p_rpjmd = jQuery("#target_akhir_p_rpjmd").val();
        let bps_tahun_1 = jQuery("#bps_tahun_1").val();
        let target_bps_tahun_1 = jQuery("#target_bps_tahun_1").val();
        let bps_tahun_2 = jQuery("#bps_tahun_2").val();
        let target_bps_tahun_2 = jQuery("#target_bps_tahun_2").val();
        let bps_tahun_3 = jQuery("#bps_tahun_3").val();
        let target_bps_tahun_3 = jQuery("#target_bps_tahun_3").val();
        let bps_tahun_4 = jQuery("#bps_tahun_4").val();
        let target_bps_tahun_4 = jQuery("#target_bps_tahun_4").val();
        let bps_tahun_5 = jQuery("#bps_tahun_5").val();
        let target_bps_tahun_5 = jQuery("#target_bps_tahun_5").val();

        let indikator_kinerja = jQuery("#indikator_kinerja").val();
        if (indikator_kinerja == '') {
            return alert('indikator kinerja tidak boleh kosong');
        }

        let sumber_data = jQuery("#sumber_data").val();
        if (sumber_data == '') {
            return alert('sumber data tidak boleh kosong');
        }

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            method: 'post',
            url: esakip.url,
            dataType: 'json',
            data: {
                "action": 'simpan_data_capaian_indikator',
                "api_key": esakip.api_key,
                "id": jQuery('#id_capaian_indikator').val(),
                "indikator_kinerja": indikator_kinerja,
                "sumber_data": sumber_data,
                "satuan": satuan,
                "keterangan": keterangan,
                "kondisi_awal": kondisi_awal,
                "target_akhir_p_rpjmd": target_akhir_p_rpjmd,
                "bps_tahun_1": bps_tahun_1,
                "target_bps_tahun_1": target_bps_tahun_1,
                "bps_tahun_2": bps_tahun_2,
                "target_bps_tahun_2": target_bps_tahun_2,
                "bps_tahun_3": bps_tahun_3,
                "target_bps_tahun_3": target_bps_tahun_3,
                "bps_tahun_4": bps_tahun_4,
                "target_bps_tahun_4": target_bps_tahun_4,
                "bps_tahun_5": bps_tahun_5,
                "target_bps_tahun_5": target_bps_tahun_5,
                "id_jadwal": <?php echo $input['periode']; ?>
            },
            success: function(res) {
                alert(res.message);
                if (res.status == 'success') {
                    jQuery('#modal-crud').modal('hide');
                    getCapaianIndikator();
                } else {
                    jQuery('#wrap-loading').hide();
                }
            }
        });
    }

    function edit_capaian_indikator(id){
        tambah_capaian_indikator().then(function(){
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'edit_capaian_indikator',
                    api_key: esakip.api_key,
                    id: id,
                    id_jadwal: '<?php echo $input["periode"]; ?>' 
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if(response.status == 'error'){
                        alert(response.message)
                    }else if(response.data != null){
                        jQuery('#id_capaian_indikator').val(id);
                        jQuery("#modal-crud").find('.modal-title').html('Edit Capaian Indikator');
                        jQuery('#indikator_kinerja').val(response.data.indikator_kinerja);
                        jQuery('#sumber_data').val(response.data.sumber_data);
                        jQuery('#satuan').val(response.data.satuan);
                        jQuery('#keterangan_capaian_indikator').val(response.data.keterangan);
                        jQuery('#kondisi_awal').val(response.data.kondisi_awal);
                        jQuery('#target_akhir_p_rpjmd').val(response.data.target_akhir_p_rpjmd);
                        jQuery('#bps_tahun_1').val(response.data.bps_tahun_1);
                        jQuery('#target_bps_tahun_1').val(response.data.target_bps_tahun_1);
                        jQuery('#bps_tahun_2').val(response.data.bps_tahun_2);
                        jQuery('#target_bps_tahun_2').val(response.data.target_bps_tahun_2);
                        jQuery('#bps_tahun_3').val(response.data.bps_tahun_3);
                        jQuery('#target_bps_tahun_3').val(response.data.target_bps_tahun_3);
                        jQuery('#bps_tahun_4').val(response.data.bps_tahun_4);
                        jQuery('#target_bps_tahun_4').val(response.data.target_bps_tahun_4);
                        jQuery('#bps_tahun_5').val(response.data.bps_tahun_5);
                        jQuery('#target_bps_tahun_5').val(response.data.target_bps_tahun_5);
                    }
                }
            });
        });    
    }

    function hapus_capaian_indikator(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_capaian_indikator',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getCapaianIndikator();
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

    function sync_to_esr(){
        let list = jQuery("input:checkbox[name=checklist_esr]:checked")
                .map(function (){
                return jQuery(this).val();
        }).toArray();            
            
        if(list.length){
            if (!confirm('Apakah Anda ingin melakukan singkronisasi dokumen ke ESR?')) {
                return;
            }
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'sync_to_esr',
                    api_key: esakip.api_key,
                    list: list,
                    tahun_anggaran:tahun_anggaran_periode_dokumen,
                    nama_tabel_database:'esakip_rpjmd'
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    jQuery('#wrap-loading').hide();
                    alert(response.message);
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    alert('Terjadi kesalahan saat kirim data!');
                }
            });
        }else{
            alert('Checklist ESR belum dipilih!'); 
        }
    }
</script>