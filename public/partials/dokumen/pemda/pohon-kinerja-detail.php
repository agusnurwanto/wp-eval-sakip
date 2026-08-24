<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2022',
    'periode' => ''
), $atts);

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

if (!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1) {
    $tahun_periode = $periode['tahun_selesai_anggaran'];
} else {
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

$idtahun = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT 
            *
        FROM esakip_data_jadwal
        WHERE tipe = %s
            AND status=1",
        'RPJMD'
    ),
    ARRAY_A
);

$tahun = "<option value='-1'>Pilih Tahun Periode</option>";

foreach ($idtahun as $val) {
    if (!empty($val['tahun_selesai_anggaran']) && $val['tahun_selesai_anggaran'] > 1) {
        $tahun_anggaran_selesai = $val['tahun_selesai_anggaran'];
    } else {
        $tahun_anggaran_selesai = $val['tahun_anggaran'] + $val['lama_pelaksanaan'];
    }

    $selected = '';
    if (!empty($input['tahun_anggaran']) && $val['tahun_anggaran'] == $input['tahun_anggaran']) {
        $selected = 'selected';
    }
    $tahun .= "<option value='$val[tahun_anggaran]' $selected>$val[tahun_anggaran]</option>";
}
$tipe_dokumen = "pohon_kinerja_dan_cascading_pemda";


$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
$is_administrator = in_array('administrator', $user_roles);
$status_api_esr = get_option('_crb_api_esr_status');

$admin_role_pemda = array(
    'admin_bappeda',
    'admin_ortala'
);

$this_jenis_role = (array_intersect($admin_role_pemda, $user_roles)) ? 1 : 2;

$cek_settingan_menu = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT 
            jenis_role
        FROM esakip_menu_dokumen 
        WHERE nama_dokumen='Pohon Kinerja dan Cascading'
          AND user_role='pemerintah_daerah' 
          AND active = 1
          AND id_jadwal=%d
    ",
        $input['periode']
    )
);

$hak_akses_user = ($cek_settingan_menu == $this_jenis_role || $cek_settingan_menu == 3 || $is_administrator) ? true : false;
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
            <h1 class="text-center" style="margin:3rem;">Dokumen Pohon Kinerja dan Cascading <br>Pemerintah Daerah<br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h1>
            <?php if (!$is_admin_panrb): ?>
                <div style="margin-bottom: 25px;">
                    <button class="btn btn-primary" onclick="tambah_dokumen();"><i class="dashicons dashicons-plus"></i> Tambah Data</button>
                    <?php
                    if ($status_api_esr) {
                        echo '<button class="btn btn-warning" onclick="sync_to_esr();" id="btn-sync-to-esr" style="display:none"><i class="dashicons dashicons-arrow-up-alt"></i> Kirim Data ke ESR</button>';
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
                            if (!$is_admin_panrb && $hak_akses_user):
                                if ($status_api_esr) {
                                    echo '<th class="text-center" rowspan="2" id="check-list-esr" style="display:none">Checklist ESR</th>';
                                }
                            endif;
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

<script>
    jQuery(document).ready(function() {
        getTableDokumen();
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
                action: 'get_table_pohon_kinerja',
                api_key: esakip.api_key,
                tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
                id_periode: <?php echo $input['periode']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status_mapping_esr) {
                    tahun_anggaran_periode_dokumen = response.tahun_anggaran_periode_dokumen;
                    let body_non_esr_lokal = ``;
                    if (response.non_esr_lokal.length > 0) {
                        response.non_esr_lokal.forEach((value, index) => {
                            body_non_esr_lokal += `
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

    function edit_dokumen_pohon_kinerja(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_detail_pohon_kinerja_by_id',
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
                    let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/dokumen_pemda/'; ?>' + data.dokumen;
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
        form_data.append('action', 'tambah_dokumen_pohon_kinerja_pemda');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id_dokumen', id_dokumen);
        form_data.append('keterangan', keterangan);
        form_data.append('tahunAnggaran', tahunAnggaran);
        form_data.append('fileUpload', fileDokumen);
        form_data.append('tipe_dokumen', tipe_dokumen);
        form_data.append('namaDokumen', namaDokumen);
        form_data.append('id_periode', <?php echo $input['periode']; ?>);

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
        let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/dokumen_pemda/'; ?>' + dokumen;
        window.open(url, '_blank');
    }

    function hapus_dokumen_pohon_kinerja(id) {
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

    function sync_to_esr() {
        let list = jQuery("input:checkbox[name=checklist_esr]:checked")
            .map(function() {
                return jQuery(this).val();
            }).toArray();

        if (list.length) {
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
                    tahun_anggaran: tahun_anggaran_periode_dokumen,
                    id_periode: <?php echo $input['periode']; ?>,
                    nama_tabel_database: 'esakip_pohon_kinerja_dan_cascading_pemda'
                },
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    jQuery('#wrap-loading').hide();
                    if (response.status) {
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    alert('Terjadi kesalahan saat kirim data!');
                }
            });
        } else {
            alert('Checklist ESR belum dipilih!');
        }
    }
</script>