<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2022',
), $atts);

if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}
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
        WHERE nama_dokumen='Laporan Kinerja'
          AND user_role='perangkat_daerah' 
          AND active = 1
          AND tahun_anggaran=%d
    ",
        $input['tahun']
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

    #table_dokumen_laporan_kinerja th {
        vertical-align: middle;
    }
</style>

<!-- Table -->
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Dokumen Laporan Kinerja <br><?php echo $skpd['nama_skpd'] ?><br> Tahun Anggaran <?php echo $input['tahun']; ?></h1>
            <?php if (!$is_admin_panrb && $hak_akses_user): ?>
                <div style="margin-bottom: 25px;">
                    <button class="btn btn-primary" onclick="tambah_dokumen_laporan_kinerja();"><i class="dashicons dashicons-plus"></i> Tambah Data</button>
                    <?php
                    if ($status_api_esr) {
                        echo '<button class="btn btn-warning" onclick="sync_to_esr();" id="btn-sync-to-esr" style="display:none"><i class="dashicons dashicons-arrow-up-alt"></i> Kirim Data ke ESR</button>';
                    }
                    ?>
                </div>
            <?php endif; ?>
            <div class="wrap-table">
                <table id="table_dokumen_laporan_kinerja" cellpadding="2" cellspacing="0" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="2">No</th>
                            <?php
                            if (!$is_admin_panrb && $hak_akses_user):
                                if ($status_api_esr) {
                                    echo '<th class="text-center" rowspan="2" id="check-list-esr" style="display:none">Checklist ESR</th>';
                                }
                            endif;
                            ?>
                            <th class="text-center" rowspan="2">Perangkat Daerah</th>
                            <th class="text-center" rowspan="2">Nama Dokumen</th>
                            <th class="text-center" rowspan="2">Keterangan</th>
                            <th class="text-center" rowspan="2">Waktu Upload</th>
                            <th class="text-center" colspan="2">Verifikasi</th>
                            <th class="text-center" rowspan="2" style="width: 150px;">Aksi</th>
                        </tr>
                        <tr>
                            <th class="text-center">Status</th>
                            <th class="text-center">Catatan</th>
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
                    <input type="hidden" value="<?php echo $id_skpd; ?>" id="idSkpd">
                    <input type="hidden" value="<?php echo $input['tahun']; ?>" id="tahunAnggaran">
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
                    <button type="submit" class="btn btn-primary" onclick="submit_tahun_laporan_kinerja(); return false">Simpan</button>
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
                    <input type="hidden" value="<?php echo $input['tahun']; ?>" id="tahunAnggaran">
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
                        <label for="keterangan_verifikasi">Catatan</label>
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
        getTableTahun();
        jQuery("#fileUpload").on('change', function() {
            var id_dokumen = jQuery('#idDokumen').val();
            if (id_dokumen == '') {
                var name = jQuery("#fileUpload").prop('files')[0].name;
                jQuery('#nama_file').val(name);
            }
        });
        window.tipe_dokumen = "laporan_kinerja";
    });

    function verifikasi_dokumen(id) {
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
                jQuery("#idDokumen").val(0);
                jQuery("input[name=verifikasi_dokumen][value='terima']").prop("checked", true);
                jQuery("#keterangan_verifikasi").val("");
                if (response.status === 'success') {
                    let data = response.data;
                    if (data.length !== 0 || data.status_verifikasi != null) {
                        let verifikasi = (data.status_verifikasi == 2) ? "tolak" : "terima";
                        jQuery("input[name=verifikasi_dokumen][value='" + verifikasi + "']").prop("checked", true);
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

    function submit_verifikasi_dokumen(that) {
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
        let tahunAnggaran = jQuery("#tahunAnggaran").val();
        if (tahunAnggaran == '') {
            return alert('Tahun Anggaran tidak boleh kosong');
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
        form_data.append('tahunAnggaran', tahunAnggaran);
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
                    getTableLaporanKinerja();
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

    function getTableLaporanKinerja() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_laporan_kinerja',
                api_key: esakip.api_key,
                id_skpd: <?php echo $id_skpd; ?>,
                tahun_anggaran: '<?php echo $input['tahun'] ?>'
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (
                    response.data_esr &&
                    response.data_esr.status == 'error'
                ) {
                    alert(response.data_esr.message);
                }
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
                    jQuery('#table_dokumen_laporan_kinerja tbody').html(response.data);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data Laporan Kinerja!');
            }
        });
    }

    function getTableTahun() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_tahun_renja',
                api_key: esakip.api_key,
                id_skpd: <?php echo $id_skpd; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                getTableLaporanKinerja();
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

    function set_tahun_dokumen(id) {
        jQuery('#tahunModal').modal('show');
        jQuery('#idDokumen').val(id);
    }

    function submit_tahun_laporan_kinerja() {
        let id = jQuery("#idDokumen").val();
        if (id == '') {
            return alert('id tidak boleh kosong');
        }

        let tahunAnggaran = jQuery("#tahunAnggaran").val();
        if (tahunAnggaran == '') {
            return alert('Tahun Anggaran tidak boleh kosong');
        }

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'submit_tahun_laporan_kinerja',
                id: id,
                tahunAnggaran: tahunAnggaran,
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
                    getTableRenja();
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

    function tambah_dokumen_laporan_kinerja() {
        jQuery("#editModalLabel").hide();
        jQuery("#uploadModalLabel").show();
        jQuery("#idDokumen").val('');
        jQuery("#fileUpload").val('');
        jQuery("#keterangan").val('');
        jQuery("#nama_file").val('');
        jQuery('#fileUploadExisting').removeAttr('href').empty();
        jQuery("#uploadModal").modal('show');
    }

    function edit_dokumen_laporan_kinerja(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_detail_laporan_kinerja_by_id',
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
                    jQuery('#fileUploadExisting').attr('href', url).html(data.dokumen);
                    jQuery("#keterangan").val(data.keterangan);
                    jQuery("#uploadModalLabel").hide();
                    jQuery("#nama_file").val(data.dokumen);
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

        let form_data = new FormData();
        form_data.append('action', 'tambah_dokumen_laporan_kinerja');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id_dokumen', id_dokumen);
        form_data.append('skpd', skpd);
        form_data.append('idSkpd', idSkpd);
        form_data.append('keterangan', keterangan);
        form_data.append('tahunAnggaran', tahunAnggaran);
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
                    getTableLaporanKinerja();
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


    function hapus_dokumen_laporan_kinerja(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_dokumen_laporan_kinerja',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableLaporanKinerja();
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

    function hapus_tahun_dokumen_laporan_kinerja(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_tahun_dokumen_laporan_kinerja',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableLaporanKinerja();
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
                    tahun_anggaran: '<?php echo $input['tahun'] ?>',
                    nama_tabel_database: 'esakip_laporan_kinerja',
                    id_skpd: <?php echo $id_skpd; ?>
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

    function usulkan_dokumen(id_dokumen) {
        if (!confirm('Apakah Anda Yakin Akan Mengusulkan Dokumen Ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'unggah_draft_dokumen',
                api_key: esakip.api_key,
                id_dokumen: id_dokumen,
                id_skpd: <?php echo $id_skpd; ?>,
                tahun_anggaran: '<?php echo $input['tahun'] ?>',
                nama_tabel_database: 'esakip_laporan_kinerja',
                tipe_dokumen: ''
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                alert(response.message);
                getTableLaporanKinerja();
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                alert('Terjadi kesalahan saat kirim data!');
                getTableLaporanKinerja();
            }
        });
    }
</script>