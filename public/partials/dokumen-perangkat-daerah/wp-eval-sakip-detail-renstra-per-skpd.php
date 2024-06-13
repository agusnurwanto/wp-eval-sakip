<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'periode' => '',
), $atts);

if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}
$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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

if(!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1){
    $tahun_periode = $periode['tahun_selesai_anggaran'];
}else{
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}


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
$is_administrator = in_array('administrator', $user_roles);

    $admin_role_pemda = array(
        'admin_bappeda',
        'admin_ortala'
    );

    $this_jenis_role = (in_array($user_roles[0], $admin_role_pemda)) ? 1 : 2 ;

    $cek_settingan_menu = $wpdb->get_var(
        $wpdb->prepare(
        "SELECT 
            jenis_role
        FROM esakip_menu_dokumen 
        WHERE nama_dokumen='RENSTRA'
          AND user_role='perangkat_daerah' 
          AND active = 1
          AND id_jadwal=%d
    ",$input['periode'])
    );

    print_r($cek_settingan_menu);

    $hak_akses_user = ($cek_settingan_menu == $this_jenis_role || $cek_settingan_menu == 3 || $is_administrator) ? true : false;
    $hak_akses_user = true;

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

    #table_dokumen_renstra th {
        vertical-align: middle;
    }
</style>

<!-- Table -->
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Dokumen RENSTRA <br><?php echo $skpd['nama_skpd'] ?><br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h1>
            <?php if (!$is_admin_panrb 
                && $hak_akses_user
            ): ?>
                <div style="margin-bottom: 25px;">
                    <button class="btn btn-primary" onclick="tambah_dokumen_renstra();"><i class="dashicons dashicons-plus"></i> Tambah Data</button>
                </div>
            <?php endif; ?>
            <div class="wrap-table">
                <table id="table_dokumen_renstra" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                    <tr>
                            <th class="text-center" rowspan="2">No</th>
                            <th class="text-center" rowspan="2">Perangkat Daerah</th>
                            <th class="text-center" rowspan="2">Nama Dokumen</th>
                            <th class="text-center" rowspan="2">Keterangan</th>
                            <th class="text-center" rowspan="2">Waktu Upload</th>
                            <th class="text-center" colspan="2">Verfikasi</th>
                            <th class="text-center" rowspan="2" style="width: 150px;">Aksi</th>
                        </tr>
                        <tr>
                            <th class="text-center">Status</th>
                            <th class="text-center">Keterangan</th>
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
                    <input type="hidden" value="<?php echo $id_skpd; ?>" id="idSkpd">
                    <input type="hidden" value="<?php echo $input['periode']; ?>" id="id_jadwal">
                    <input type="hidden" value="" id="idDokumen">
                    <div class="form-group">
                        <label for="perangkatDaerah">Perangkat Daerah</label>
                        <input type="text" class="form-control" id="perangkatDaerah" name="perangkatDaerah" style="text-transform: uppercase;" value="<?php echo $skpd['nama_skpd']; ?>" disabled>
                    </div>
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
                    <button type="submit" class="btn btn-primary" onclick="submit_tahun_renstra(); return false">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
<div class="modal fade" id="verifikasiModal" tabindex="-1" role="dialog" aria-labelledby="verifikasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifikasiModalLabel">Verifikasi Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" value="<?php echo $id_skpd; ?>" id="idSkpd">
                    <input type="hidden" value="" id="idDokumen">
                    <tr>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="verifikasi_dokumen" id="verifikasi_dokumen_terima" value="terima">
                                <label class="form-check-label" for="verifikasi_dokumen_terima">Terima</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="verifikasi_dokumen" id="verifikasi_dokumen_tolak" value="tolak">
                                <label class="form-check-label" for="verifikasi_dokumen_tolak">Tolak</label>
                            </div>
                        </td>
                    </tr>
                    <div class="form-group">
                        <label for="keterangan_verifikasi">Keterangan</label>
                        <textarea class="form-control" id="keterangan_verifikasi" name="keterangan_verifikasi" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="submit_verifikasi_dokumen(this); return false">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tahun Tabel -->
<div id="tahunContainer" class="container-md">
</div>

<script>
    jQuery(document).ready(function() {
        getTableRenstra();
        getTableTahun();
        jQuery("#fileUpload").on('change', function() {
            var id_dokumen = jQuery('#idDokumen').val();
            if (id_dokumen == '') {
                var name = jQuery("#fileUpload").prop('files')[0].name;
                jQuery('#nama_file').val(name);
            }
        });
        window.tipe_dokumen = "renstra";
    });

    function verifikasi_dokumen(id){
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_verifikasi_dokumen_by_id',
                api_key: esakip.api_key,
                id: id,
                tipe_dokumen: tipe_dokumen,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    let data = response.data;
                    if(data.length !== 0 || data.status_verifikasi != null){
                        let verifikasi = (data.status_verifikasi == 1) ? "terima" : "tolak";
                        jQuery("input[name=verifikasi_dokumen][value='"+verifikasi+"']").prop("checked",true);
                        jQuery("#keterangan_verifikasi").val(data.keterangan_verifikasi);
                    }
                    jQuery("#idDokumen").val(id);
                    jQuery("#verifikasiModal").modal('show');
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

    function submit_verifikasi_dokumen(that){
        let id_dokumen = jQuery("#idDokumen").val();
        if (id_dokumen == '') {
            return alert('Id Dokumen tidak boleh kosong');
        }

        let idSkpd = jQuery("#idSkpd").val();
        if (idSkpd == '') {
            return alert('Id Skpd tidak boleh kosong');
        }
        let keterangan = jQuery("#keterangan_verifikasi").val();
        if (keterangan == '') {
            return alert('Keterangan tidak boleh kosong');
        }
        let verifikasi_dokumen = jQuery("input[name='verifikasi_dokumen']:checked").val();

        if (verifikasi_dokumen == '' || verifikasi_dokumen == undefined) {
            return alert('Verifikasi tidak boleh kosong');
        }

        let form_data = new FormData();
        form_data.append('action', 'submit_verifikasi_dokumen');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id_dokumen', id_dokumen);
        form_data.append('idSkpd', idSkpd);
        form_data.append('keterangan', keterangan);
        form_data.append('verifikasi_dokumen', verifikasi_dokumen);
        form_data.append('tipe_dokumen', tipe_dokumen);

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            contentType: false,
            processData: false,
            data: form_data,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#verifikasiModal').modal('hide');
                    alert(response.message);
                    getTableRenstra();
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

    function getTableRenstra() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_renstra',
                api_key: esakip.api_key,
                id_skpd: <?php echo $id_skpd; ?>,
				id_periode: <?php echo $input['periode']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen_renstra tbody').html(response.data);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data Renstra!');
            }
        });
    }

    function getTableTahun() {
		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_table_tahun_renstra',
				api_key: esakip.api_key,
                id_skpd: <?php echo $id_skpd; ?>,
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

    function tambah_dokumen_renstra() {
        jQuery("#editModalLabel").hide();
        jQuery("#uploadModalLabel").show();
        jQuery('#nama_file').val('');
        jQuery("#idDokumen").val('');
        jQuery("#fileUpload").val('');
        jQuery("#keterangan").val('');
        jQuery('#fileUploadExisting').removeAttr('href').empty();
        jQuery("#uploadModal").modal('show');
    }

    function edit_dokumen_renstra(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_detail_renstra_by_id',
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

        let skpd = jQuery("#perangkatDaerah").val();
        if (skpd == '') {
            return alert('Perangkat Daerah tidak boleh kosong');
        }
        let idSkpd = jQuery("#idSkpd").val();
        if (idSkpd == '') {
            return alert('Id Skpd tidak boleh kosong');
        }
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
        form_data.append('action', 'tambah_dokumen_renstra');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id_dokumen', id_dokumen);
        form_data.append('skpd', skpd);
        form_data.append('idSkpd', idSkpd);
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
                    getTableRenstra();
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

    function submit_tahun_renstra() {
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
                action: 'submit_tahun_renstra',
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
                    getTableRenstra();
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


    function hapus_dokumen_renstra(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_dokumen_renstra',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableRenstra();
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

    function hapus_tahun_dokumen_renstra(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_tahun_dokumen_renstra',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableRenstra();
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