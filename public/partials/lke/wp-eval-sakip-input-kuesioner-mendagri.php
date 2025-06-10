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
        <h1 class="text-center" style="margin:3rem;">Input Kuesioner Mendagri<br><?php echo $input['tahun']; ?></h1>
        <div style="margin-bottom: 25px;">
            <button class="btn btn-primary" onclick="tambah_kuesioner();"><i class="dashicons dashicons-plus"></i> Tambah Data</button>
            <button class="btn btn-success" onclick="generate_data();">Generate Data Awal</button>
            <button class="btn btn-danger" onclick="copy_data();"><i class="dashicons dashicons-admin-page"></i> Copy Data</button>
        </div>
        <div class="wrap-table">
            <table id="table_kuesioner_mendagri" cellpadding="2" cellspacing="0" style="collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" colspan="3" style="vertical-align: middle;">Kuesioner/Indikator</th>
                        <th class="text-center" style="vertical-align: middle;">Deskripsi</th>
                        <th class="text-center" style="vertical-align: middle;">Level</th>
                        <th class="text-center" style="vertical-align: middle;">Penjelasan</th>
                        <th class="text-center" style="vertical-align: middle;">Data Dukung</th>
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
                            <label for="deskripsi">Deskripsi</label>
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi" required>
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
                <h5 class="modal-title" id="tambahPertanyaanModalLabel">Tambah Kuesioner</h5>
                <h5 class="modal-title d-none" id="editPertanyaanModalLabel">Edit Kuesioner</h5>
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
                        <div class="alert alert-info text-sm-left" role="alert" id="alertKuesionerLevel"></div>
                    </div>

                    <div class="form-group">
                        <label for="indikator">Indikator</label>
                        <input type="text" class="form-control" id="indikator" name="indikator" required>
                    </div>

                    <div class="form-group">
                        <label for="penjelasan">Penjelasan</label>
                        <textarea class="form-control" id="penjelasan" name="penjelasan" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="jenis_bukti_dukung">Data Dukung</label>
                        <textarea class="form-control" id="jenis_bukti_dukung" name="jenis_bukti_dukung" required></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submit_kuesioner_pertanyaan_mendagri(); return false">Simpan</button>
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
        jQuery("#modal").find('.modal-title').html('Copy Data Kuesioner Mendagri');
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
        if (!confirm('Apakah anda yakin akan copy data Kuesioner Mendagri? \nData yang sudah ada akan ditimpa oleh data baru hasil copy data!')) {
            return;
        }

        var tahun_anggaran = jQuery("#tahunAnggaranCopy").val();

        jQuery('#wrap-loading').show();

        ajax_copy_data({
            tahun_anggaran: tahun_anggaran
        })
        .then(function() {
            alert('Berhasil Copy Data Kuesioner Mendagri.');
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
                    action: 'copy_data_kuesioner_mendagri',
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
                action: 'generate_data_mendagri',
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
                action: 'get_table_kuesioner_mendagri',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun']; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_kuesioner_mendagri tbody').html(response.data);
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
        jQuery('#deskripsi').val('');
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

        let deskripsi = jQuery("#deskripsi").val();
        if (deskripsi == '') {
            return alert('Deskripsi Kuesioner tidak boleh kosong');
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'tambah_kuesioner_mendagri',
                api_key: esakip.api_key,
                id: id_kuesioner,
                tahun_anggaran: <?php echo $input['tahun']; ?>, 
                nama_kuesioner: namaKuesioner,
                deskripsi: deskripsi,
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
    function edit_data_kuesioner_mendagri(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_kuesioner_mendagri_by_id',
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
                    jQuery('#deskripsi').val(data.deskripsi);
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
    function hapus_data_kuesioner_mendagri(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus Kuesioner ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_data_kuesioner_mendagri',
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
    function tambah_pertanyaan_kuesioner_mendagri(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            type: "POST",
            url: esakip.url,
            data: {
                action: 'get_detail_pertanyaan_mendagri',
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
                    
                    let levelRomawi = toRoman(data.level || 1);
                    jQuery('#alertKuesionerLevel')
                        .text('Level ' + levelRomawi)
                        .data('level', data.level || 1);

                    jQuery('#indikator').val('');
                    jQuery('#penjelasan').val('');
                    jQuery('#jenis_bukti_dukung').val('');
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

    function toRoman(num) {
        const roman = {
            M:1000,CM:900,D:500,CD:400,C:100,XC:90,L:50,
            XL:40,X:10,IX:9,V:5,IV:4,I:1
        };
        let str = '';
        for (let i in roman) {
            while (num >= roman[i]) {
                str += i;
                num -= roman[i];
            }
        }
        return str;
    }

    function romanToInt(roman) {
        const romanMap = {I:1, V:5, X:10, L:50, C:100, D:500, M:1000};
        let sum = 0;
        for (let i = 0; i < roman.length; i++) {
            const current = romanMap[roman[i]];
            const next = romanMap[roman[i + 1]];
            sum += next && current < next ? -current : current;
        }
        return sum;
    }

    function submit_kuesioner_pertanyaan_mendagri() {
        const tipe = jQuery('#tipeJawaban').val();
        const indikator = jQuery('#indikator').val();
        const penjelasan = jQuery('#penjelasan').val().trim();
        const id_detail = jQuery('#idKuesionerPertanyaanDetail').val();
        const id_pertanyaan = jQuery('#idKuesionerPertanyaan').val();
        const jenis_bukti_dukung = jQuery('#jenis_bukti_dukung').val().trim();
        
        let level = jQuery('#alertKuesionerLevel').data('level');

        if (confirm('Apakah anda yakin untuk menyimpan data ini?')) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    action: "submit_kuesioner_pertanyaan_mendagri",
                    api_key: esakip.api_key,
                    tahun_anggaran: <?php echo $input['tahun']; ?>,
                    id_detail,
                    id_pertanyaan,
                    indikator,
                    penjelasan,
                    jenis_bukti_dukung,
                    level 
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


    function edit_data_kuesioner_mendagri_detail(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                action: "get_kuesioner_mendagri_detail_by_id",
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id: id
            },
            dataType: "json",
            success: function (res) {
                jQuery('#wrap-loading').hide();
                if (res.status === 'success') {
                    let data = res.data;
                    let kuesioner = res.kuesioner;

                    jQuery('#tambahPertanyaanModalLabel').hide();
                    jQuery('#editPertanyaanModalLabel').show();
                    jQuery('#alertKuesionerPertanyaan').text('Nama Kuesioner = ' + kuesioner.nama_kuesioner);
                    let levelRomawi = toRoman(data.level || 1);
                    jQuery('#alertKuesionerLevel')
                        .text('Level ' + levelRomawi)
                        .data('level', data.level || 1);
                    jQuery('#idKuesionerPertanyaan').val(id);
                    jQuery('#idKuesionerPertanyaanDetail').val(data.id_pertanyaan);
                    jQuery('#indikator').val(data.indikator);
                    jQuery('#penjelasan').val(data.penjelasan);
                    jQuery('#jenis_bukti_dukung').val(data.jenis_bukti_dukung);

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

    function hapus_data_kuesioner_mendagri_detail(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_data_kuesioner_mendagri_detail',
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