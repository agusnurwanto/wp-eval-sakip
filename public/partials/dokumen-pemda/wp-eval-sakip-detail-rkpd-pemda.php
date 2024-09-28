<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2022',
), $atts);

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$idtahun = $wpdb->get_results(
    "
		SELECT DISTINCT 
			tahun_anggaran 
		FROM esakip_data_unit        
        ORDER BY tahun_anggaran DESC",
    ARRAY_A
);
$tahun = "<option value='-1'>Pilih Tahun</option>";

foreach ($idtahun as $val) {
    $selected = '';
    if (!empty($input['tahun_anggaran']) && $val['tahun_anggaran'] == $input['tahun_anggaran']) {
        $selected = 'selected';
    }
    $tahun .= "<option value='$val[tahun_anggaran]' $selected>$val[tahun_anggaran]</option>";
}
$tipe_dokumen = "rkpd";

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
</style>

<!-- Table -->
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Dokumen RKPD <br>Pemerintah Daerah<br> Tahun Anggaran <?php echo $input['tahun']; ?></h1>
            <?php if (!$is_admin_panrb): ?>
            <div style="margin-bottom: 25px;">
                <button class="btn btn-primary" onclick="tambah_dokumen();"><i class="dashicons dashicons-plus"></i> Tambah Data</button>
                <?php
                if($status_api_esr==2){
                    echo '<button class="btn btn-warning" onclick="sync_to_esr();"><i class="dashicons dashicons-arrow-up-alt"></i> Kirim Data ke ESR</button>';
                }
                ?>
            </div>
            <?php endif; ?>
            <div class="wrap-table">
                <table id="table_dokumen" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <?php
                                if($status_api_esr==2){
                                    echo '<th class="text-center">Checklist ESR</th>';
                                }
                            ?>
                            <th class="text-center">Nama Dokumen</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Waktu Upload</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
                    <input type="hidden" value="<?php echo $input['tahun']; ?>" id="tahunAnggaran">
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
                    <button type="submit" class="btn btn-primary" onclick="submit_dokumen(this); return false;">Unggah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tahun -->
<!-- <div class="modal fade" id="tahunModal" tabindex="-1" role="dialog" aria-labelledby="tahunModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tahunModalLabel">Pilih Tahun Anggaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tahunForm">
                    <div class="form-group">
                        <label for="tahunAnggaran">Tahun Anggaran:</label>
                        <select class="form-control" id="tahunAnggaran" name="tahunAnggaran">
                            <?php echo $tahun; ?>
                        </select>
                        <input type="hidden" id="idDokumen" value="">
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="submit_tahun_dokumen(); return false">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div> -->

<!-- Tahun Tabel -->
<div id="tahunContainer" class="container-md">
</div>

<script>
    jQuery(document).ready(function() {
        getTableDokumen();
        // getTableTahun();
        jQuery("#fileUpload").on('change', function() {
            var id_dokumen = jQuery('#idDokumen').val();
            if (id_dokumen == '') {
                var name = jQuery("#fileUpload").prop('files')[0].name;
                jQuery('#nama_file').val(name);
            }
        });
    });

    function getTableDokumen() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_dokumen_pemerintah_daerah',
                api_key: esakip.api_key,
                tahun_anggaran: '<?php echo $input['tahun'] ?>',
                tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen tbody').html(response.data);
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

    // function getTableTahun() {
    //     jQuery('#wrap-loading').show();
    //     jQuery.ajax({
    //         url: esakip.url,
    //         type: 'POST',
    //         data: {
    //             action: 'get_table_tahun_dokumen',
    //             api_key: esakip.api_key,
    //             tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
    //         },
    //         dataType: 'json',
    //         success: function(response) {
    //             jQuery('#wrap-loading').hide();
    //             console.log(response);
    //             if (response.status === 'success') {
    //                 jQuery('#tahunContainer').html(response.data);
    //             } else {
    //                 alert(response.message);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             jQuery('#wrap-loading').hide();
    //             console.error(xhr.responseText);
    //             alert('Terjadi kesalahan saat memuat tabel!');
    //         }
    //     });
    // }

    function tambah_dokumen() {
        jQuery("#editModalLabel").hide();
        jQuery("#uploadModalLabel").show();
        jQuery("#idDokumen").val('');
        jQuery("#nama_file").val('');
        jQuery("#fileUpload").val('');
        jQuery("#keterangan").val('');
        jQuery('#fileUploadExisting').removeAttr('href').empty();
        jQuery("#uploadModal").modal('show');
    }

    // function set_tahun_dokumen(id) {
    //     jQuery('#tahunModal').modal('show');
    //     jQuery('#idDokumen').val(id);
    // }

    // function submit_tahun_dokumen() {
    //     let id = jQuery("#idDokumen").val();
    //     if (id == '') {
    //         return alert('id tidak boleh kosong');
    //     }

    //     let tahunAnggaran = jQuery("#tahunAnggaran").val();
    //     if (tahunAnggaran == '') {
    //         return alert('Tahun Anggaran tidak boleh kosong');
    //     }

    //     jQuery('#wrap-loading').show();
    //     jQuery.ajax({
    //         url: esakip.url,
    //         type: 'POST',
    //         data: {
    //             action: 'submit_tahun_dokumen',
    //             id: id,
    //             tahunAnggaran: tahunAnggaran,
    //             api_key: esakip.api_key,
	// 			tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
    //         },
    //         dataType: 'json',
    //         success: function(response) {
    //             console.log(response);
    //             jQuery('#wrap-loading').hide();
    //             if (response.status === 'success') {
    //                 alert(response.message);
    //                 jQuery('#tahunModal').modal('hide');
    //                 getTableTahun();
    //                 getTableDokumen();
    //             } else {
    //                 alert(response.message);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             jQuery('#wrap-loading').hide();
    //             console.error(xhr.responseText);
    //             alert('Terjadi kesalahan saat mengirim data!');
    //         }
    //     });
    // }

    function edit_dokumen(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_detail_dokumen_by_id_pemerintah_daerah',
                api_key: esakip.api_key,
                id: id,
				tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
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
                    jQuery("#nama_file").val(data.dokumen);
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
        let tahunAnggaran = jQuery("#tahunAnggaran").val();
        if (tahunAnggaran == '') {
            return alert('Tahun Anggaran tidak boleh kosong');
        }
        let fileDokumen = jQuery("#fileUpload").prop('files')[0];
        if (fileDokumen == '') {
            return alert('File Upload tidak boleh kosong');
        }
        let namaDokumen = jQuery("#nama_file").val();
        if (namaDokumen == '') {
            return alert('Nama Dokumen tidak boleh kosong');
        }
        let tipe_dokumen = '<?php echo $tipe_dokumen; ?>';

        let form_data = new FormData();
        form_data.append('action', 'submit_tambah_dokumen_pemerintah_daerah');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id_dokumen', id_dokumen);
        form_data.append('keterangan', keterangan);
        form_data.append('tahunAnggaran', tahunAnggaran);
        form_data.append('fileUpload', fileDokumen);
        form_data.append('tipe_dokumen', tipe_dokumen);
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
                    getTableDokumen();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengirim data!');
                jQuery('#wrap-loading').hide();
            }
        });
    }

    function lihatDokumen(dokumen) {
        let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/'; ?>' + dokumen;
        window.open(url, '_blank');
    }


    function hapus_dokumen(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_dokumen_pemerintah_daerah',
                api_key: esakip.api_key,
                id: id,
                tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
                
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableDokumen();
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

    // function hapus_tahun_dokumen_tipe(id) {
    //     if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
    //         return;
    //     }
    //     jQuery('#wrap-loading').show();
    //     jQuery.ajax({
    //         url: esakip.url,
    //         type: 'POST',
    //         data: {
    //             action: 'hapus_tahun_dokumen_tipe',
    //             api_key: esakip.api_key,
    //             tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
    //             id: id
    //         },
    //         dataType: 'json',
    //         success: function(response) {
    //             console.log(response);
    //             jQuery('#wrap-loading').hide();
    //             if (response.status === 'success') {
    //                 alert(response.message);
    //                 getTableDokumen();
    //                 getTableTahun();
    //             } else {
    //                 alert(response.message);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error(xhr.responseText);
    //             jQuery('#wrap-loading').hide();
    //             alert('Terjadi kesalahan saat mengirim data!');
    //         }
    //     });
    // }

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
                    action: 'sync_rkpd_to_esr',
                    api_key: esakip.api_key,
                    list: list
                },
                dataType: 'json',
                success: function(response) {
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