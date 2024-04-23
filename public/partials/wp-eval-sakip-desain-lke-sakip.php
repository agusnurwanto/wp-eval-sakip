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
    ", $input['id_jadwal']),
    ARRAY_A
);

if (empty($jadwal)) {
    die("jadwal tidak tersedia");
}
?>
<style>
    .transparent-button {
        background-color: transparent;
        border: none;
        color: #000;
        cursor: pointer;
        transition: color 0.3s;
        width: 100%;
        outline: none;
    }

    .transparent-button:hover,
    .transparent-button:focus,
    .transparent-button:focus-visible {
        color: #000;
        background-color: #E5E1DA;
        border-color: transparent;
        outline: none;
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTambahKomponen">
                    <input type="hidden" value="<?php echo $input['id_jadwal'] ?>" id="idJadwal">
                    <div class="form-group">
                        <label for="namaKomponen">Nama Komponen</label>
                        <input type="text" class="form-control" id="namaKomponen" name="namaKomponen" required>
                    </div>
                    <div class="form-group">
                        <label for="bobotKomponen">Bobot Komponen</label>
                        <input type="number" class="form-control" id="bobotKomponen" name="bobotKomponen" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTambahSubkomponen">
                    <input type="hidden" value="" id="idKomponen">
                    <div class="form-group">
                        <label for="namaSubkomponen">Nama Subkomponen</label>
                        <input type="text" class="form-control" id="namaSubkomponen" name="namaSubkomponen" required>
                    </div>
                    <div class="form-group">
                        <label for="bobotSubkomponen">Bobot Subkomponen</label>
                        <input type="number" class="form-control" id="bobotSubkomponen" name="bobotSubkomponen" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTambahPenilaian">
                    <input type="hidden" value="" id="idSubKomponen">
                    <div class="form-group">
                        <label for="namaPenilaian">Nama Komponen Penilaian</label>
                        <input type="text" class="form-control" id="namaPenilaian" name="namaPenilaian" required>
                    </div>
                    <div class="form-group">
                        <label for="tipeJawaban">Tipe Jawaban</label>
                        <select class="form-control" id="tipeJawaban" name="tipeJawaban" required>
                            <option value="">Pilih Tipe Jawaban</option>
                            <option value="1">Y/T</option>
                            <option value="2">A/B/C/D/E</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submit_komponen_penilaian(); return false">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function() {
        get_table_desain_sakip();
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

    function tambah_komponen_utama() {
        jQuery('#namaKomponen').val('');
        jQuery('#bobotKomponen').val('');
        jQuery('#tambahKomponenModal').modal('show');
    }

    function tambah_subkomponen(id) {
        jQuery('#namaSubkomponen').val('');
        jQuery('#bobotSubkomponen').val('');
        jQuery('#tambahSubkomponenModal').modal('show');
    }

    function tambah_komponen_penilaian(id) {
        jQuery('#namaPenilaian').val('');
        jQuery('#tipePenilaian').val('');
        jQuery('#tambahPenilaianModal').modal('show');
    }

    function edit_data_komponen(id) {
        alert('berhasil' + id)
    }

    function edit_data_subkomponen(id) {
        alert('berhasil' + id)
    }

    function edit_data_komponen_penilaian(id) {
        alert('berhasil' + id)
    }

    function hapus_data_komponen(id) {
        alert('berhasil' + id)
    }

    function hapus_subkomponen(id) {
        alert('berhasil' + id)
    }

    function hapus_data_komponen_penilaian(id) {
        alert('berhasil' + id)
    }

    function submit_komponen() {
        let id = jQuery("#idKomponen").val();

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
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'tambah_komponen_lke',
                id: id,
                id_jadwal: idJadwal,
                nama_komponen: namaKomponen,
                bobot_komponen: bobotKomponen,
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
</script>