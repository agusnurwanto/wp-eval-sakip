<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'id_jadwal' => '',
), $atts);

$jadwal = $wpdb->get_row(
    $wpdb->prepare("
        SELECT
            *
        FROM esakip_data_jadwal
        WHERE id=%d
          AND status=1
          AND tipe='LKE'
    ", $input['id_jadwal']),
    ARRAY_A
);

if (empty($jadwal)) {
    die("jadwal tidak tersedia");
}

$user_penilai = $this->get_user_penilai();
$user_penilai_html = '<option value="">Pilih user</option>';
foreach ($user_penilai as $key => $val) {
    $user_penilai_html .= '<option value="' . $key . '">' . $val . '</option>';
}
?>
<style>
    .transparent-button {
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

    /* Sembunyikan tombol panah (spinner) pada input nomor */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
        /* Untuk Firefox */
    }
</style>
<div class="container-md">
    <div style="padding: 10px;margin:0 0 3rem 0;">
        <h1 class="text-center" style="margin:3rem;">Desain LKE SAKIP<br><?php echo $jadwal['nama_jadwal']; ?> (<?php echo $jadwal['tahun_anggaran']; ?>)</h1>
        <div class="wrap-table">
            <table id="table_desain_sakip" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" colspan="4" style="vertical-align: middle;">Komponen/Sub Komponen</th>
                        <th class="text-center" style="vertical-align: middle;">Bobot</th>
                        <th class="text-center">Format Penilaian</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Kerangka Logis</th>
                        <th class="text-center" style="vertical-align: middle;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal tambah komponen -->
<div class="modal fade" id="tambahKomponenModal" tabindex="-1" role="dialog" aria-labelledby="tambahKomponenModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahKomponenModalLabel">Tambah Komponen Utama</h5>
                <h5 class="modal-title" id="editKomponenModalLabel">Edit Komponen Utama</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTambahKomponen">
                    <input type="hidden" value="" id="idKomponen">
                    <input type="hidden" value="<?php echo $input['id_jadwal'] ?>" id="idJadwal">
                    <div class="form-group">
                        <div class="alert alert-info text-sm-left" role="alert">
                            Bobot Maksimal 100
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="namaKomponen">Nama Komponen</label>
                            <input type="text" class="form-control" id="namaKomponen" name="namaKomponen" required>
                        </div>
                        <div class="form-group col-md-6" style="display: none;">
                            <label for="userPenilai">User Penilai</label>
                            <select class="form-control" id="userPenilai" name="userPenilai" required>
                                <?php echo $user_penilai_html; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="bobotKomponen">Bobot Komponen</label>
                            <input type="number" class="form-control" id="bobotKomponen" name="bobotKomponen" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nomorUrutKomponen">Nomor Urut</label>
                            <input type="number" class="form-control" id="nomorUrutKomponen" name="nomorUrutKomponen">
                            <small class="text-muted text-sm-left" id="defaultTextInfoKomponen"> Default Nomor Urut</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submit_komponen(); return false">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menambah subkomponen -->
<div class="modal fade" id="tambahSubkomponenModal" tabindex="-1" role="dialog" aria-labelledby="tambahSubkomponenModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahSubkomponenModalLabel">Tambah Subkomponen</h5>
                <h5 class="modal-title" id="editSubkomponenModalLabel">Edit Subkomponen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTambahSubkomponen">
                    <input type="hidden" value="" id="idSubKomponen">
                    <input type="hidden" value="" id="idKomponen_sub">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="alert alert-info text-sm-left" role="alert" id="alertSub">
                            </div>
                            <div class="alert alert-info text-sm-left" role="alert" id="alertBobotSub">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="namaSubKomponen">Nama Subkomponen</label>
                            <input type="text" class="form-control" id="namaSubKomponen" name="namaSubKomponen" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="userPenilai_sub">User Penilai</label>
                            <select class="form-control" id="userPenilai_sub" name="userPenilai_sub" required>
                                <?php echo $user_penilai_html; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="bobotSubKomponen">Bobot Subkomponen</label>
                            <input type="number" class="form-control" id="bobotSubKomponen" name="bobotSubKomponen" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nomorUrutSubkomponen">Nomor Urut</label>
                            <input type="number" class="form-control" id="nomorUrutSubkomponen" name="nomorUrutSubkomponen" required>
                            <small class="text-muted text-sm-left" id="defaultTextInfoSub"> Default Nomor Urut</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submit_subkomponen(); return false">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menambah komponen penilaian -->
<div class="modal fade" id="tambahPenilaianModal" tabindex="-1" role="dialog" aria-labelledby="tambahPenilaianModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPenilaianModalLabel">Tambah Komponen Penilaian</h5>
                <h5 class="modal-title" id="editPenilaianModalLabel">Edit Komponen Penilaian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTambahPenilaian">
                    <input type="hidden" value="" id="idKomponenPenilaian">
                    <input type="hidden" value="" id="idSubKomponen_penilaian">
                    <div class="form-group">
                        <div class="alert alert-info text-sm-left" role="alert" id="alertKomponen_penilaian"></div>
                        <div class="alert alert-info text-sm-left" role="alert" id="alertSub_penilaian"></div>
                    </div>
                    <div class="form-group">
                        <label for="namaPenilaian">Nama Komponen Penilaian</label>
                        <input type="text" class="form-control" id="namaPenilaian" name="namaPenilaian" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tipeJawaban">Tipe Jawaban</label>
                            <select class="form-control" id="tipeJawaban" name="tipeJawaban" required>
                                <option value="" selected disabled>Pilih Tipe Jawaban</option>
                                <option value="1">Y/T</option>
                                <option value="2">A/B/C/D/E</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nomorUrutPenilaian">Nomor Urut</label>
                            <input type="number" class="form-control" id="nomorUrutPenilaian" name="nomorUrutPenilaian">
                            <small class="text-muted text-sm-left" id="defaultTextInfoPenilaian"> Default Nomor Urut</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Pilih Jenis Bukti Dukung <small class="text-secondary">( minimal 1 atau bisa lebih dari 1 )</small></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_renstra" id="renstra">
                                    <label class="form-check-label" for="renstra">
                                        RENSTRA
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_renja_rkt" id="renja-rkt">
                                    <label class="form-check-label" for="renja-rkt">
                                        RENJA/RKT
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_perjanjian_kinerja" id="perjanjian-kinerja">
                                    <label class="form-check-label" for="perjanjian-kinerja">
                                        Perjanjian Kinerja
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_rencana_aksi" id="rencana-aksi">
                                    <label class="form-check-label" for="rencana-aksi">
                                        Rencana Aksi
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_dokumen_lainnya" id="dokumen-lainnya">
                                    <label class="form-check-label" for="dokumen-lainnya">
                                        Dokumen Lainnya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_dpa" id="dpa">
                                    <label class="form-check-label" for="dpa">
                                        DPA
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_iku" id="iku">
                                    <label class="form-check-label" for="iku">
                                        IKU
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_skp" id="skp">
                                    <label class="form-check-label" for="skp">
                                        SKP
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_pengukuran_kinerja" id="pengukuran-kinerja">
                                    <label class="form-check-label" for="pengukuran-kinerja">
                                        Pengukuran Kinerja
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_pengukuran_rencana_aksi" id="pengukuran-rencana-aksi">
                                    <label class="form-check-label" for="pengukuran-rencana-aksi">
                                        Pengukuran Rencana Aksi
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_laporan_kinerja" id="laporan-kinerja">
                                    <label class="form-check-label" for="laporan-kinerja">
                                        Laporan Kinerja
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="esakip_evaluasi_internal" id="evaluasi-internal">
                                    <label class="form-check-label" for="evaluasi-internal">
                                        Evaluasi Internal
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submit_komponen_penilaian(); return false">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menambah kerangka logis komponen penilaian -->
<div class="modal fade bd-example-modal-lg" id="tambahKerangkaLogisModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahKerangkaLogisModalLabel">Tambah Kerangka Logis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="tableKerangkaLogis" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Jenis Kerangka Logis</th>
                            <th class="text-center">Sub Komponen/Komponen Penilaian Pembanding</th>
                            <th class="text-center">Pesan Kesalahan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <form id="formTambahKerangkaLogis">
                    <input type="hidden" value="" id="idKomponenPenilaian">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="jenisKerangkaLogis">Jenis Kerangka Logis</label>
                            <select class="form-control" id="jenisKerangkaLogis" name="jenisKerangkaLogis" required>
                                <option value="" selected disabled>Pilih Jenis Kerangka Logis</option>
                                <option value="1">Rata-Rata</option>
                                <option value="2">Nilai</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="subkomponenPembandingContainer" style="display:none;">
                            <label for="subkomponenPembanding">Pilih Subkomponen Pembanding</label>
                            <select class="form-control" id="subkomponenPembanding" name="subkomponenPembanding">
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="komponenPenilaianPembandingContainer" style="display:none;">
                            <label for="komponenPenilaianPembanding">Pilih Komponen Penilaian Pembanding</label>
                            <select class="form-control" id="komponenPenilaianPembanding" name="komponenPenilaianPembanding">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pesanKerangkaLogis">Pesan Kesalahan</label>
                        <textarea class="form-control" id="pesanKerangkaLogis" name="pesanKerangkaLogis" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submit_kerangka_logis(); return false">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function() {
        get_table_desain_sakip();
        get_option_sub_pembanding();
        get_option_penilaian_pembanding();

        jQuery('#subkomponenPembandingContainer').hide();
        jQuery('#komponenPenilaianPembandingContainer').hide();

        // Handle change event kerangka logis
        jQuery('#jenisKerangkaLogis').on('change', function() {
            let jenisKerangkaLogis = jQuery(this).val();
            if (jenisKerangkaLogis == '1') {
                jQuery('#subkomponenPembandingContainer').show();
                jQuery('#komponenPenilaianPembandingContainer').hide();
            } else if (jenisKerangkaLogis == '2') {
                jQuery('#komponenPenilaianPembandingContainer').show();
                jQuery('#subkomponenPembandingContainer').hide();
            } else {
                jQuery('#subkomponenPembandingContainer').hide();
                jQuery('#komponenPenilaianPembandingContainer').hide();
            }
        });
    })

    function get_table_desain_sakip() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_desain_lke',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['id_jadwal']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_desain_sakip tbody').html(response.data);
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

    function get_table_kerangka_logis(id) {
        return new Promise((resolve, reject) => {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_table_kerangka_logis',
                    api_key: esakip.api_key,
                    id_komponen_penilaian: id,
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    console.log(response);
                    if (response.status === 'success') {
                        jQuery('#tableKerangkaLogis tbody').html(response.data);
                        resolve()
                    } else {
                        alert(response.message);
                        reject(response.message)
                    }
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat memuat tabel!');
                    reject(xhr.responseText);
                }
            });
        });
    }

    function tambah_komponen_utama(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            type: "POST",
            url: esakip.url,
            data: {
                action: 'get_detail_komponen_lke_by_id',
                api_key: esakip.api_key,
                id: id
            },
            dataType: "json",
            success: function(response) {
                let data = response.data;
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#idKomponen').val('');
                    jQuery('#namaKomponen').val('');
                    jQuery('#tambahKomponenModalLabel').show();
                    jQuery('#editKomponenModalLabel').hide();
                    jQuery('#defaultTextInfoKomponen').show();
                    jQuery('#userPenilai').val('');
                    jQuery('#bobotKomponen').val('');
                    jQuery('#nomorUrutKomponen').val(parseFloat(data.default_urutan) + 1.00);
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error('AJAX Error:', status, error);
            }
        });
        jQuery('#tambahKomponenModal').modal('show');
    }

    function tambah_subkomponen(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            type: "POST",
            url: esakip.url,
            data: {
                action: 'get_detail_subkomponen_lke_by_id',
                api_key: esakip.api_key,
                id: id
            },
            dataType: "json",
            success: function(response) {
                let data = response.data;
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#idKomponen_sub').val(id);
                    jQuery('#idSubKomponen').val('');
                    jQuery('#tambahSubkomponenModalLabel').show();
                    jQuery('#editSubkomponenModalLabel').hide();
                    jQuery('#defaultTextInfoSub').show();
                    jQuery('#alertSub').text('Nama Komponen = ' + data.komponen.nama);
                    jQuery('#alertBobotSub').text('Bobot Max Komponen = ' + data.komponen.bobot);
                    jQuery('#namaSubKomponen').val('');
                    jQuery('#userPenilai').val('');
                    jQuery('#bobotSubKomponen').val('');
                    jQuery('#nomorUrutSubkomponen').val(parseFloat(data.default_urutan) + 1.00);
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error('AJAX Error:', status, error);
            }
        });
        jQuery('#tambahSubkomponenModal').modal('show');
    }

    function tambah_komponen_penilaian(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            type: "POST",
            url: esakip.url,
            data: {
                action: 'get_detail_penilaian_lke_by_id',
                api_key: esakip.api_key,
                id: id
            },
            dataType: "json",
            success: function(response) {
                let data = response.data;
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#idSubKomponen_penilaian').val(id);
                    jQuery('#idKomponenPenilaian').val('');
                    jQuery('#tambahPenilaianModalLabel').show();
                    jQuery('#editPenilaianModalLabel').hide();
                    jQuery('#defaultTextInfoPenilaian').show();
                    jQuery('#alertKomponen_penilaian').text('Nama Komponen = ' + data.komponen.nama);
                    jQuery('#alertSub_penilaian').text('Nama Sub Komponen = ' + data.subkomponen.nama);
                    jQuery('#namaPenilaian').val('');
                    jQuery('#tipeJawaban').val('');
                    jQuery('input[type=checkbox]').prop('checked', false);
                    jQuery("#keterangan").val('');
                    jQuery('#nomorUrutPenilaian').val(parseFloat(data.default_urutan) + 1.00);
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error('AJAX Error:', status, error);
            }
        });
        jQuery('#tambahPenilaianModal').modal('show');
    }

    function tambah_kerangka_logis(id) {
        get_table_kerangka_logis(id).then(() => {
            jQuery('#idKomponenPenilaian').val(id);
            jQuery('#jenisKerangkaLogis').val('');
            jQuery('#subkomponenPembanding').val('');
            jQuery('#pesanKerangkaLogis').val('');
            jQuery('#komponenPenilaianPembanding').val('');

            jQuery('#subkomponenPembandingContainer').hide();
            jQuery('#komponenPenilaianPembandingContainer').hide();
            jQuery('#tambahKerangkaLogisModal').modal('show');
        }).catch((error) => {
            console.error('Error loading table:', error);
        });
    }

    function edit_data_komponen(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_komponen_lke_by_id',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                let data = response.data;
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#tambahKomponenModalLabel').hide();
                    jQuery('#editKomponenModalLabel').show();
                    jQuery('#defaultTextInfoKomponen').hide();
                    jQuery("#idKomponen").val(data.id);
                    jQuery("#namaKomponen").val(data.nama);
                    jQuery("#bobotKomponen").val(data.bobot);
                    jQuery("#userPenilai").val(data.id_user_penilai);
                    jQuery('#nomorUrutKomponen').val(data.nomor_urut);
                    jQuery('#tambahKomponenModal').modal('show');
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

    function edit_data_subkomponen(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_subkomponen_lke_by_id',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                let data = response.data;
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#tambahSubkomponenModalLabel').hide();
                    jQuery('#editSubkomponenModalLabel').show();
                    jQuery('#defaultTextInfoSub').hide();
                    jQuery('#alertSub').text('Nama Komponen = ' + data.komponen.nama);
                    jQuery('#alertBobotSub').text('Bobot Max Komponen = ' + data.komponen.bobot);
                    jQuery("#idSubKomponen").val(data.id);
                    jQuery("#idKomponen_sub").val(data.id_komponen);
                    jQuery("#namaSubKomponen").val(data.nama);
                    jQuery("#bobotSubKomponen").val(data.bobot);
                    jQuery("#userPenilai_sub").val(data.id_user_penilai);
                    jQuery('#nomorUrutSubkomponen').val(data.nomor_urut);
                    jQuery('#tambahSubkomponenModal').modal('show');
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

    function setCheckBoxes(selectedBuktiDukung) {
        jQuery('input[type=checkbox]').prop('checked', false);

        // Iterate over the selectedBuktiDukung array and check the corresponding checkboxes
        if (selectedBuktiDukung && selectedBuktiDukung.length > 0) {
            selectedBuktiDukung.forEach(function(bukti) {
                jQuery('input[value="' + bukti + '"]').prop('checked', true);
            });
        }
    }

    function edit_data_komponen_penilaian(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_komponen_penilaian_lke_by_id',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    let data = response.data;
                    jQuery('#tambahPenilaianModalLabel').hide();
                    jQuery('#editPenilaianModalLabel').show();
                    jQuery('#defaultTextInfoPenilaian').hide();
                    jQuery('#alertKomponen_penilaian').text('Nama Komponen = ' + data.komponen.nama + ' (' + data.komponen.bobot + ')');
                    jQuery('#alertSub_penilaian').text('Nama Sub Komponen = ' + data.subkomponen.nama + ' (' + data.subkomponen.bobot + ')');
                    jQuery("#idKomponenPenilaian").val(data.data.id);
                    jQuery("#idSubKomponen_penilaian").val(data.data.id_subkomponen);
                    jQuery("#namaPenilaian").val(data.data.nama);
                    jQuery("#tipeJawaban").val(data.data.tipe);
                    jQuery("#keterangan").val(data.data.keterangan);
                    jQuery('#nomorUrutPenilaian').val(data.data.nomor_urut);
                    setCheckBoxes(data.data.jenis_bukti_dukung);
                    jQuery('#tambahPenilaianModal').modal('show');
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

    function hapus_data_komponen(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus Komponen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_komponen_lke',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    get_table_desain_sakip();
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

    function hapus_data_subkomponen(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus Sub Komponen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_subkomponen_lke',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    get_table_desain_sakip();
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

    function hapus_data_komponen_penilaian(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus Komponen Penilaian ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_komponen_penilaian_lke',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    get_table_desain_sakip();
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

    function submit_komponen() {
        let id_komponen = jQuery("#idKomponen").val();

        let idJadwal = jQuery("#idJadwal").val();
        if (idJadwal == '') {
            return alert('Id Jadwal tidak boleh kosong');
        }

        let namaKomponen = jQuery("#namaKomponen").val();
        if (namaKomponen == '') {
            return alert('Nama Komponen tidak boleh kosong');
        }

        let bobotKomponen = jQuery("#bobotKomponen").val();
        if (bobotKomponen == '') {
            return alert('Bobot Komponen tidak boleh kosong');
        }

        let nomorUrutKomponen = jQuery("#nomorUrutKomponen").val();
        if (nomorUrutKomponen == '') {
            return alert('Nomor Urut Komponen tidak boleh kosong');
        }

        // let userPenilai = jQuery("#userPenilai").val();
        // if (userPenilai == '') {
        //     return alert('User Penilai tidak boleh kosong');
        // }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'tambah_komponen_lke',
                id: id_komponen,
                id_jadwal: idJadwal,
                nama_komponen: namaKomponen,
                bobot_komponen: bobotKomponen,
                nomor_urut: nomorUrutKomponen,
                // user_penilai: userPenilai,
                api_key: esakip.api_key
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery('#tambahKomponenModal').modal('hide');
                    get_table_desain_sakip();
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

    function submit_subkomponen() {
        let id_subkomponen = jQuery("#idSubKomponen").val();

        let idKomponen_sub = jQuery("#idKomponen_sub").val();
        if (idKomponen_sub == '') {
            return alert('Id Komponen tidak boleh kosong');
        }

        let namaSubKomponen = jQuery("#namaSubKomponen").val();
        if (namaSubKomponen == '') {
            return alert('Nama Sub Komponen tidak boleh kosong');
        }

        let bobotSubKomponen = jQuery("#bobotSubKomponen").val();
        if (bobotSubKomponen == '') {
            return alert('Bobot Sub Komponen tidak boleh kosong');
        }

        let nomorUrutSubkomponen = jQuery("#nomorUrutSubkomponen").val();
        if (nomorUrutSubkomponen == '') {
            return alert('Nomor Urut Sub Komponen tidak boleh kosong');
        }

        let userPenilai_sub = jQuery("#userPenilai_sub").val();
        if (userPenilai_sub == '') {
            return alert('User Penilai Sub Komponen tidak boleh kosong');
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'tambah_subkomponen_lke',
                id: id_subkomponen,
                id_komponen: idKomponen_sub,
                nama_subkomponen: namaSubKomponen,
                bobot_subkomponen: bobotSubKomponen,
                nomor_urut: nomorUrutSubkomponen,
                user_penilai: userPenilai_sub,
                api_key: esakip.api_key
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery('#tambahSubkomponenModal').modal('hide');
                    get_table_desain_sakip();
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

    function submit_komponen_penilaian() {
        let id_komponen_penilaian = jQuery("#idKomponenPenilaian").val();

        let idSubKomponen_penilaian = jQuery("#idSubKomponen_penilaian").val();
        if (idSubKomponen_penilaian == '') {
            return alert('Id Sub Komponen tidak boleh kosong');
        }

        let namaPenilaian = jQuery("#namaPenilaian").val();
        if (namaPenilaian == '') {
            return alert('Nama Penilaian tidak boleh kosong');
        }

        let tipeJawaban = jQuery("#tipeJawaban").val();
        if (tipeJawaban == '') {
            return alert('Tipe Penilaian tidak boleh kosong');
        }

        let keterangan = jQuery("#keterangan").val();

        let nomorUrutPenilaian = jQuery("#nomorUrutPenilaian").val();
        if (nomorUrutPenilaian == '') {
            return alert('Nomor Urut Penilaian tidak boleh kosong');
        }

        let selectedBuktiDukung = [];
        jQuery('input[type=checkbox]:checked').each(function() {
            selectedBuktiDukung.push(jQuery(this).val());
        });

        if (selectedBuktiDukung.length === 0) {
            return alert('Minimal satu bukti dukung harus dipilih');
        }

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'tambah_komponen_penilaian_lke',
                id: id_komponen_penilaian,
                id_subkomponen: idSubKomponen_penilaian,
                nama_komponen_penilaian: namaPenilaian,
                tipe_komponen_penilaian: tipeJawaban,
                keterangan: keterangan,
                bukti_dukung: JSON.stringify(selectedBuktiDukung),
                nomor_urut: nomorUrutPenilaian,
                api_key: esakip.api_key
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery('#tambahPenilaianModal').modal('hide');
                    get_table_desain_sakip();
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

    function submit_kerangka_logis() {
        let idKomponenPenilaian = jQuery("#idKomponenPenilaian").val();
        if (idKomponenPenilaian == '') {
            return alert('Id Komponen Penilaian tidak boleh kosong');
        }
        let jenisKerangkaLogis = jQuery("#jenisKerangkaLogis").val();
        if (jenisKerangkaLogis == '') {
            return alert('Jenis Kerangka Logis tidak boleh kosong');
        }
        let pesanKerangkaLogis = jQuery("#pesanKerangkaLogis").val();
        if (pesanKerangkaLogis == '') {
            return alert('Pesan Kesalahan tidak boleh kosong');
        }

        let komponenPembanding = '';
        if (jenisKerangkaLogis == 1) {
            komponenPembanding = jQuery("#subkomponenPembanding").val();
            if (komponenPembanding == '') {
                return alert('Sub Komponen Pembanding tidak boleh kosong');
            }
        } else if (jenisKerangkaLogis == 2) {
            komponenPembanding = jQuery("#komponenPenilaianPembanding").val();
            if (komponenPembanding == '') {
                return alert('Komponen Pembanding tidak boleh kosong');
            }
        }

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'tambah_kerangka_logis_penilaian_lke',
                id: idKomponenPenilaian,
                jenis_kerangka_logis: jenisKerangkaLogis,
                komponen_pembanding: komponenPembanding,
                pesan_kesalahan: pesanKerangkaLogis,
                api_key: esakip.api_key
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery('#tambahKerangkaLogisModal').modal('hide');
                    get_table_desain_sakip();
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

    function get_option_sub_pembanding() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "POST",
            data: {
                'action': "get_subkomponen_pembanding",
                'api_key': esakip.api_key,
                'id_jadwal': <?php echo $input['id_jadwal']; ?>,
            },
            dataType: "json",
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#subkomponenPembanding').html(response.data)
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengirim data!');
            }
        })
    }

    function get_option_penilaian_pembanding() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "POST",
            data: {
                'action': "get_komponen_penilaian_pembanding",
                'api_key': esakip.api_key,
                'id_jadwal': <?php echo $input['id_jadwal']; ?>,
            },
            dataType: "json",
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#komponenPenilaianPembanding').html(response.data)
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengirim data!');
            }
        })
    }

    function hapus_kerangka_logis(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus Kerangka Logis ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_kerangka_logis',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery('#tambahKerangkaLogisModal').modal('hide');
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