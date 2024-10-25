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

$tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];

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

    #table_dokumen_rpjpd th {
        vertical-align: middle;
    }
</style>

<!-- Table -->
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Dokumen RPJPD <br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h1>
            <?php if (!$is_admin_panrb): ?>
                <div style="margin-bottom: 25px;">
                    <button class="btn btn-primary" onclick="tambah_dokumen_rpjpd();"><i class="dashicons dashicons-plus"></i> Tambah Data</button>
                    <?php
                    if($status_api_esr){
                        echo '<button class="btn btn-warning" onclick="sync_to_esr();" id="btn-sync-to-esr" style="display:none"><i class="dashicons dashicons-arrow-up-alt"></i> Kirim Data ke ESR</button>';
                    }
                    ?>
                </div>
            <?php endif; ?>
            <div class="wrap-table">
                <table id="table_dokumen_rpjpd" cellpadding="2" cellspacing="0" style="font-family:'Open Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
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
                    <button type="submit" class="btn btn-primary" onclick="submit_tahun_rpjpd(); return false">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tahun Tabel -->
<div id="tahunContainer" class="container-md">
</div>

<script>
    var tahun_anggaran_periode_dokumen = null;
    jQuery(document).ready(function() {
        getTableRpjpd();
        getTableTahun();
        jQuery("#fileUpload").on('change', function() {
            var id_dokumen = jQuery('#idDokumen').val();
            if (id_dokumen == '') {
                var name = jQuery("#fileUpload").prop('files')[0].name;
                jQuery('#nama_file').val(name);
            }
        });
    });

    function getTableRpjpd() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_rpjpd',
                api_key: esakip.api_key,
                id_periode: <?php echo $input['periode']; ?>
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
                    jQuery('#table_dokumen_rpjpd tbody').html(response.data);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data Rpjpd!');
            }
        });
    }

    function getTableTahun() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_tahun_rpjpd',
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
        console.log(url);
        window.open(url, '_blank');
    }

    function set_tahun_dokumen(id) {
        jQuery('#tahunModal').modal('show');
        jQuery('#idDokumen').val(id);
    }

    function tambah_dokumen_rpjpd() {
        jQuery("#editModalLabel").hide();
        jQuery("#uploadModalLabel").show();
        jQuery('#nama_file').val('');
        jQuery("#idDokumen").val('');
        jQuery("#fileUpload").val('');
        jQuery("#keterangan").val('');
        jQuery('#fileUploadExisting').removeAttr('href').empty();
        jQuery("#uploadModal").modal('show');
    }

    function edit_dokumen_rpjpd(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_detail_rpjpd_by_id',
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
        form_data.append('action', 'tambah_dokumen_rpjpd');
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
                    getTableRpjpd();
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

    function submit_tahun_rpjpd() {
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
                action: 'submit_tahun_rpjpd',
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
                    getTableRpjpd();
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
        let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/dokumen_pemda/'; ?>' + dokumen;
        window.open(url, '_blank');
    }


    function hapus_dokumen_rpjpd(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_dokumen_rpjpd',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableRpjpd();
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

    function hapus_tahun_dokumen_rpjpd(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_tahun_dokumen_rpjpd',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableRpjpd();
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

    function sync_from_esr(){
       jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'sync_from_esr',
                    api_key: esakip.api_key,
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    alert(response.message);
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    alert('Terjadi kesalahan saat ambil data!');
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
                    nama_tabel_database:'esakip_rpjpd'
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