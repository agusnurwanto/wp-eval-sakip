<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '',
), $atts);

$idtahun = $wpdb->get_results(
    "
        SELECT DISTINCT 
            tahun_anggaran 
        FROM esakip_data_unit        
        ORDER BY tahun_anggaran DESC",
    ARRAY_A
);
$tahun = '<option value="0">Pilih Tahun</option>';

foreach ($idtahun as $val) {
    if($val['tahun_anggaran'] == $input['tahun']){
        continue;
    }
    $selected = '';
    if($val['tahun_anggaran'] == $input['tahun']-1){
        $selected = 'selected';
    }
    $tahun .= '<option value="'. $val['tahun_anggaran']. '" '. $selected .'>'. $val['tahun_anggaran'] .'</option>';
}
?>
<style>
    .wrap-table {
        overflow: auto;
        max-height: 100vh;
        width: 100%;
    }

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

    .align-middle {
        vertical-align: middle !important;
    }

</style>
<div class="container-md">
    <div style="padding: 10px;margin:0 0 3rem 0;">
        <h1 class="text-center" style="margin:3rem;">Input Kuesioner Menpan<br><?php echo $input['tahun']; ?></h1>
        <div style="margin-bottom: 25px;">
            <button class="btn btn-primary" onclick="tambah_kuesioner();"><i class="dashicons dashicons-plus"></i> Tambah Data</button>
            <button class="btn btn-success" onclick="generate_data();">Generate Data Awal</button>
            <button class="btn btn-danger" onclick="copy_data();"><i class="dashicons dashicons-admin-page"></i> Copy Data</button>
        </div>
        <div class="wrap-table">
            <table id="table_kuesioner_menpan" cellpadding="2" cellspacing="0" style="collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" colspan="4" style="vertical-align: middle;">Kuesioner/Nama Kuesioner</th>
                        <th class="text-center" style="vertical-align: middle;">Bobot</th>
                        <th class="text-center" style="vertical-align: middle;">Jawaban</th>
                        <th class="text-center" style="vertical-align: middle; width: 700px;">Penjelasan</th>
                        <th class="text-center" style="vertical-align: middle;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal tambah kuesioner -->
<div class="modal fade" id="tambahKuesionerModal" tabindex="-1" role="dialog" aria-labelledby="tambahKuesionerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKuesionerModalLabel">Tambah Kuesioner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
             <div class="modal-body">
                <form id="formTambahKuesioner">
                    <input type="hidden" value="" id="idKuesioner">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="namaKuesioner">Nama Kuesioner</label>
                            <input type="text" class="form-control" id="namaKuesioner" name="namaKuesioner" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="nomorUrutKuesioner">Nomor Urut</label>
                            <input type="number" class="form-control" id="nomorUrutKuesioner" name="nomorUrutKuesioner">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submit_kuesioner(); return false">Simpan</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal untuk menambah pertanyaan -->
<div class="modal fade" id="tambahPertanyaanModal" tabindex="-1" role="dialog" aria-labelledby="tambahPertanyaanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPertanyaanModalLabel">Tambah Kuesioner Pertanyaan</h5>
                <h5 class="modal-title d-none" id="editPertanyaanModalLabel">Edit Kuesioner Pertanyaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTambahPertanyaan">
                    <input type="hidden" value="" id="idKuesionerPertanyaan">
                    <input type="hidden" value="" id="idKuesionerPertanyaanDetail">

                    <div class="form-group">
                        <div class="alert alert-info text-sm-left" role="alert" id="alertKuesionerPertanyaan"></div>
                    </div>

                    <div class="form-group">
                        <label for="namaPertanyaan">Nama Kuesioner Pertanyaan</label>
                        <input type="text" class="form-control" id="namaPertanyaan" name="namaPertanyaan" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tipeJawaban">Tipe Jawaban</label>
                            <select class="form-control" id="tipeJawaban" name="tipeJawaban" required>
                                <option value="">-- Pilih Tipe Jawaban --</option>
                                <option value="0">Esai</option>
                                <option value="1">Pilihan Ganda</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nomorUrutPertanyaan">Nomor Urut</label>
                            <input type="number" class="form-control" id="nomorUrutPertanyaan" name="nomorUrutPertanyaan">
                        </div>
                    </div>

                   <div class="form-group" id="tabel_daftar_jawaban" style="display: none;">
                        <label>Daftar Jawaban</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2">Jawaban</th>
                                    <th style="width: 200px;">Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 70px;"><label>STS :</label></td>
                                    <td><input type="text" name="jawaban_sts" class="form-control" /></td>
                                    <td><input type="number" name="bobot_sts" class="form-control" placeholder="Bobot" /></td>
                                </tr>
                                <tr>
                                    <td style="width: 70px;"><label>TS :</label></td>
                                    <td><input type="text" name="jawaban_ts" class="form-control" /></td>
                                    <td><input type="number" name="bobot_ts" class="form-control" placeholder="Bobot" /></td>
                                </tr>
                                <tr>
                                    <td style="width: 70px;"><label>S :</label></td>
                                    <td><input type="text" name="jawaban_s" class="form-control" /></td>
                                    <td><input type="number" name="bobot_s" class="form-control" placeholder="Bobot" /></td>
                                </tr>
                                <tr>
                                    <td style="width: 70px;"><label>SS :</label></td>
                                    <td><input type="text" name="jawaban_ss" class="form-control" /></td>
                                    <td><input type="number" name="bobot_ss" class="form-control" placeholder="Bobot" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group" id="tabel_bobot_esai">
                        <label for="bobot_esai">Bobot</label>
                        <textarea class="form-control" id="bobot_esai" name="bobot_esai" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="penjelasan">Penjelasan</label>
                        <textarea class="form-control" id="penjelasan" name="penjelasan" required></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submit_kuesioner_pertanyaan_menpan(); return false">Simpan</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal" data-backdrop="static"  role="dialog" aria-labelledby="modal-label" aria-hidden="true">
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
<script>
    jQuery(document).ready(function() {
        get_table_kuesioner();

        jQuery('#tipeJawaban').on('change', function () {
            const val = jQuery(this).val();
            jQuery('#tabel_daftar_jawaban').hide();
            jQuery('#tabel_bobot_esai').show();
            jQuery('#daftar_jawaban tbody').empty();

            if (val === '1') {
                jQuery('#tabel_bobot_esai').hide();
                jQuery('#tabel_daftar_jawaban').show();
            }
        });
    })
    function copy_data(){
            let tbody = '';
            let tahun = '<?php echo $tahun; ?>';
        jQuery("#modal").find('.modal-title').html('Copy Data Kuesioner Menpan');
        jQuery("#modal").find('.modal-body').html(`
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Tahun Anggaran Sumber Kuesioner</label>
                <div class="col-sm-9 d-flex align-items-center justify-content-center">
                    <select id="tahunAnggaranCopy" class="form-control">
                        ${tahun}
                    </select>
                </div>
            </div>
        `);
        jQuery("#modal").find('.modal-footer').html(`
            <button type="button" class="btn btn-warning" data-dismiss="modal">
                Tutup
            </button>
            <button type="button" class="btn btn-danger" onclick="submitCopyData()">
                Copy Data
            </button>`);
        jQuery("#modal").find('.modal-dialog').css('maxWidth','700');
        jQuery("#modal").modal('show');
    }
    function submitCopyData() {
        if (!confirm('Apakah anda yakin akan copy data Kuesioner Menpan? \nData yang sudah ada akan ditimpa oleh data baru hasil copy data!')) {
            return;
        }

        var tahun_anggaran = jQuery("#tahunAnggaranCopy").val();

        jQuery('#wrap-loading').show();

        ajax_copy_data({
            tahun_anggaran: tahun_anggaran
        })
        .then(function() {
            alert('Berhasil Copy Data Kuesioner Menpan.');
            jQuery("#modal").modal('hide');
            jQuery('#wrap-loading').hide();
            get_table_kuesioner();
        })
        .catch(function(err) {
            console.log('err', err);
            alert('Ada kesalahan sistem!');
            jQuery('#wrap-loading').hide();
        });
    }

    function ajax_copy_data(options){
        return new Promise(function(resolve, reject){
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'copy_data_kuesioner_menpan',
                    api_key: esakip.api_key,
                    tahun_anggaran_sumber_kuesioner: options.tahun_anggaran,
                    tahun_anggaran_tujuan: <?php echo $input['tahun']; ?>
                },
                dataType: 'json',
                success: function(response) {
                    resolve();
                },
                error: function(xhr, status, error) {
                    console.log('error', error);
                    resolve();
                }
            });
        });
    }
    function generate_data() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                api_key: esakip.api_key,
                action: 'generate_data_menpan',
                tahun_anggaran: <?php echo $input['tahun']; ?>
            },
            dataType: 'json',
            success: function(response) {
                get_table_kuesioner();
                jQuery('#wrap-loading').hide();
                console.log(response);
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat tabel!');
            }
        });
    }

    function get_table_kuesioner() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_kuesioner_menpan',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun']; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_kuesioner_menpan tbody').html(response.data);
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
    function tambah_kuesioner() {
        jQuery('#tambahKuesionerModalLabel').hide();
        jQuery('#editKuesionerModalLabel').show();
        jQuery('#defaultTextInfoKuesioner').hide();
        jQuery("#idKuesioner").val('');
        jQuery("#namaKuesioner").val('');
        jQuery('#nomorUrutKuesioner').val('');
        jQuery('#nomorUrutKuesioner').val(parseFloat(0.00 + 1.00).toFixed(2));
        jQuery('#tambahKuesionerModal').modal('show');
    }
    function submit_kuesioner() {
        let id_kuesioner = jQuery("#idKuesioner").val();
        let namaKuesioner = jQuery("#namaKuesioner").val();
        if (namaKuesioner == '') {
            return alert('Nama Kuesioner tidak boleh kosong');
        }

        let nomorUrutKuesioner = jQuery("#nomorUrutKuesioner").val();
        if (nomorUrutKuesioner == '') {
            return alert('Nomor Urut Kuesioner tidak boleh kosong');
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'tambah_kuesioner_menpan',
                api_key: esakip.api_key,
                id: id_kuesioner,
                tahun_anggaran: <?php echo $input['tahun']; ?>, 
                nama_kuesioner: namaKuesioner,
                nomor_urut: nomorUrutKuesioner
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery('#tambahKuesionerModal').modal('hide');
                    get_table_kuesioner();
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
    function edit_data_kuesioner_menpan(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_kuesioner_menpan_by_id',
                api_key: esakip.api_key,
                id: id,
                tahun_anggaran: <?php echo $input['tahun']; ?>
            },
            dataType: 'json',
            success: function(response) {
                let data = response.data;
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#tambahKuesionerModalLabel').hide();
                    jQuery('#editKuesionerModalLabel').show();
                    jQuery('#defaultTextInfoKuesioner').hide();
                    jQuery("#idKuesioner").val(data.id);
                    jQuery("#namaKuesioner").val(data.nama_kuesioner);
                    jQuery("#bobotKuesioner").val(data.bobot);
                    jQuery('#nomorUrutKuesioner').val(data.nomor_urut);
                    jQuery('#tambahKuesionerModal').modal('show');
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
    function hapus_data_kuesioner_menpan(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus Kuesioner ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_data_kuesioner_menpan',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    get_table_kuesioner();
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
    function tambah_pertanyaan_kuesioner_menpan(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            type: "POST",
            url: esakip.url,
            data: {
                action: 'get_detail_pertanyaan_menpan',
                api_key: esakip.api_key,
                id: id
            },
            dataType: "json",
            success: function(response) {
                let data = response.data;
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#idKuesionerPertanyaanDetail').val(id);
                    jQuery('#idKuesionerPertanyaan').val('');
                    jQuery('#tambahPertanyaanModalLabel').show();
                    jQuery('#editPertanyaanModalLabel').hide();
                    jQuery('#alertKuesionerPertanyaan').text('Nama Kuesioner = ' + data.kuesioner.nama_kuesioner);
                    jQuery('#namaPertanyaan').val('');
                    jQuery('#penjelasan').val('');
                    jQuery('#nomorUrutPertanyaan').val(parseFloat(data.default_urutan) + 1.00);
                    jQuery('#tipeJawaban').val('').trigger('change'); 
                    jQuery('input[name="jawaban_sts"]').val('');
                    jQuery('input[name="bobot_sts"]').val('');
                    jQuery('input[name="jawaban_ts"]').val('');
                    jQuery('input[name="bobot_ts"]').val('');
                    jQuery('input[name="jawaban_s"]').val('');
                    jQuery('input[name="bobot_s"]').val('');
                    jQuery('input[name="jawaban_ss"]').val('');
                    jQuery('input[name="bobot_ss"]').val('');
                    jQuery('#bobot_esai').val(''); 
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error('AJAX Error:', status, error);
            }
        });
        jQuery('#tambahPertanyaanModal').modal('show');
    }

    function submit_kuesioner_pertanyaan_menpan() {
        var tipe = jQuery('#tipeJawaban').val();
        var nama = jQuery('#namaPertanyaan').val().trim();
        var penjelasan = jQuery('#penjelasan').val().trim();
        var nomor_urut = jQuery('#nomorUrutPertanyaan').val().trim();
        var id_detail = jQuery('#idKuesionerPertanyaanDetail').val();
        var id_pertanyaan = jQuery('#idKuesionerPertanyaan').val();
        var bobot_esai = jQuery('#bobot_esai').val();

        if (tipe == '') {
            return alert('Tipe jawaban harus diisi');
        }
        if (nama == '') {
            return alert('Nama pertanyaan harus diisi');
        }
        if (nomor_urut == '') {
            return alert('Nomor urut harus diisi');
        }

        if (tipe === '0') {
            // Esai
            if (penjelasan == '') {
                return alert('Penjelasan harus diisi untuk esai');
            }
            if (bobot_esai == '') {
                return alert('Bobot harus diisi untuk esai');
            }
        }

        let daftar_jawaban = [];
        if (tipe === '1') {
            let jawabanInputs = [
                { label: "STS", tipe_jawaban: 1 },
                { label: "TS", tipe_jawaban: 2 },
                { label: "S", tipe_jawaban: 3 },
                { label: "SS", tipe_jawaban: 4 }
            ];

            let valid = true;

            jawabanInputs.forEach((item) => {
                let jawaban = jQuery(`input[name="jawaban_${item.label.toLowerCase()}"]`).val().trim();
                let bobot = jQuery(`input[name="bobot_${item.label.toLowerCase()}"]`).val().trim();

                if (!jawaban || !bobot) {
                    valid = false;
                } else {
                    daftar_jawaban.push({
                        jawaban: `${jawaban}`,
                        bobot: parseFloat(bobot),
                        tipe_jawaban: item.tipe_jawaban
                    });
                }
            });

            if (penjelasan == '') {
                return alert('Penjelasan harus diisi untuk pilihan ganda');
            }

            if (valid == '') {
                return alert('Semua input jawaban dan bobot untuk pilihan ganda harus diisi');
            }
        }

        if (confirm('Apakah anda yakin untuk menyimpan data ini?')) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    action: "submit_kuesioner_pertanyaan_menpan",
                    api_key: esakip.api_key,
                    tahun_anggaran: <?php echo $input['tahun']; ?>,
                    id_detail,
                    id_pertanyaan,
                    tipe_jawaban: tipe,
                    nama_pertanyaan: nama,
                    penjelasan,
                    nomor_urut,
                    bobot_esai,
                    daftar_jawaban
                },
                dataType: "json",
                success: function (res) {
                    jQuery('#wrap-loading').hide();
                    if (res.status === 'success') {
                        alert('Berhasil disimpan!');
                        jQuery('#tambahPertanyaanModal').modal('hide');
                        get_table_kuesioner();
                    } else {
                        alert('Gagal: ' + res.message);
                    }
                },
                error: function (xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(error);
                    alert('Terjadi kesalahan saat menyimpan.');
                }
            });
        }
    }

    function edit_data_kuesioner_menpan_detail(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                action: "get_kuesioner_menpan_detail_by_id",
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id: id
            },
            dataType: "json",
            success: function (res) {
                jQuery('#wrap-loading').hide();
                if (res.status === 'success') {
                    let data = res.data[0];
                    let kuesioner = res.kuesioner;

                    jQuery('#tambahPertanyaanModalLabel').hide();
                    jQuery('#editPertanyaanModalLabel').show();
                    jQuery('#alertKuesionerPertanyaan').text('Nama Kuesioner = ' + kuesioner.nama_kuesioner);

                    jQuery('#idKuesionerPertanyaan').val(id);
                    jQuery('#idKuesionerPertanyaanDetail').val(data.id_pertanyaan);
                    jQuery('#namaPertanyaan').val(data.pertanyaan);
                    jQuery('#nomorUrutPertanyaan').val(data.nomor_urut);
                    jQuery('#penjelasan').val(data.penjelasan);
                    jQuery('#bobot_esai').val(data.bobot);
                    jQuery('#tipeJawaban').val(data.tipe_soal).trigger('change');

                    if (data.tipe_soal == '1') {
                        jQuery('#tabel_daftar_jawaban').show();

                        let map = res.jawaban_map || {};

                        jQuery('input[name="jawaban_sts"]').val(map['1'] ? map['1'].jawaban : '');
                        jQuery('input[name="bobot_sts"]').val(map['1'] ? map['1'].bobot : '');
                        jQuery('input[name="jawaban_ts"]').val(map['2'] ? map['2'].jawaban : '');
                        jQuery('input[name="bobot_ts"]').val(map['2'] ? map['2'].bobot : '');
                        jQuery('input[name="jawaban_s"]').val(map['3'] ? map['3'].jawaban : '');
                        jQuery('input[name="bobot_s"]').val(map['3'] ? map['3'].bobot : '');
                        jQuery('input[name="jawaban_ss"]').val(map['4'] ? map['4'].jawaban : '');
                        jQuery('input[name="bobot_ss"]').val(map['4'] ? map['4'].bobot : '');
                    } else {
                        jQuery('#tabel_daftar_jawaban').hide();
                    }

                    jQuery('#tambahPertanyaanModal').modal('show');
                } else {
                    alert(res.message);
                }
            },
            error: function (xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(error);
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }

    function hapus_data_kuesioner_menpan_detail(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_data_kuesioner_menpan_detail',
                api_key: esakip.api_key,
                id: id
                
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    get_table_kuesioner();
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