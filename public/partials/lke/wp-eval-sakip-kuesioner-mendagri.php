<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2025',
), $atts);

if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
} else {
    die('<h1 class="text-center">id_skpd tidak boleh kosong!</h1>');
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

$idtahun = $wpdb->get_results("
    SELECT DISTINCT tahun_anggaran
    FROM esakip_data_unit
    ORDER BY tahun_anggaran DESC
    ", ARRAY_A);

$tahun = "<option value='-1'>Pilih Tahun</option>";

foreach ($idtahun as $val) {
    $selected = '';
    if (!empty($input['tahun_anggaran']) && $val['tahun_anggaran'] == $input['tahun']) {
        $selected = 'selected';
    }
    $tahun .= "<option value='$val[tahun_anggaran]' $selected>$val[tahun_anggaran]</option>";
}
?>
<style>
    .wrap-table {
        overflow: auto;
        max-height: 100vh;
        width: 100%;
    }

    .trasparent-button {
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
        <h1 class="text-center" style="margin:3rem;">Kuesioner Mendagri<br>
            <?php echo strtoupper($skpd['nama_skpd']); ?><br>
            Tahun Anggaran <?php echo $input['tahun']; ?>
        </h1>
        <div class="wrap-table">
            <table id="table_kuesioner_pengisian_mendagri" cellpadding="2" cellspacing="0" style="collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" colspan="3" style="vertical-align: middle;">Kuesioner/Indikator</th>
                        <th class="text-center" style="vertical-align: middle;">Keterangan Perangkat Daerah</th>
                        <th class="text-center" style="vertical-align: middle;">Keterangan Verifikator</th>
                        <th class="text-center" style="vertical-align: middle;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal tambah bukti dukung -->
<div class="modal fade bd-example-modal-xl" id="tambahBuktiDukung" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahBuktiDukungLabel">Pilih Bukti Dukung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="text-center">Upload Bukti Dukung</h5>
                <div id="uploadBuktiDukung" class="text-center" style="margin-bottom: 20px;"></div>
                <table id="tableBuktiDukung" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">Jenis Bukti Dukung</th>
                            <th class="text-center">Nama Dokumen</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Tanggal Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <input type="hidden" id="id_detail">
                <input type="hidden" id="idKuesionerPertanyaanDetail">
                <input type="hidden" id="jenis_bukti_dukung_json">
                <input type="hidden" id="idKuesioner">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submit_bukti_dukung(); return false">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Tingkat kematangan -->
<div class="modal fade" id="tambahKuesionerDetailModal" tabindex="-1" role="dialog" aria-labelledby="tambahKuesionerDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="tambahKuesionerDetailModalLabel">Pilih Tingkat Kematangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="idKuesionerPertanyaan">
                <input type="hidden" id="id_detail_level">
                <input type="hidden" id="id_skpd" value="<?php echo (int) $id_skpd; ?>">

                <div class="form-group col-md-12">
                    <label for="level">Tingkat Kematangan</label>
                    <select class="form-control" id="level" name="level" required>
                    </select>
                </div>

                <div id="wrap-indikator-bukti-dukung" style="display:none;">
                    <div class="form-group col-md-12">
                        <label>Indikator</label>
                        <input type="text" class="form-control" id="indikator" name="indikator" readonly>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Penjelasan</label>
                        <input type="text" class="form-control" id="penjelasan" name="penjelasan" readonly>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Jenis Bukti Dukung</label>
                        <textarea class="form-control" id="jenis_bukti_dukung" rows="3" readonly></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="submit_tingkat(); return false">Simpan</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function() {
        get_table_variabel_pengisian_mendagri();

        jQuery(document).on('change', '#level', function() {
            getIndikatorDanBuktiDukung();
        });
    });

    function get_table_variabel_pengisian_mendagri() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_variabel_pengisian_mendagri',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_kuesioner_pengisian_mendagri tbody').html(response.data);
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

    function getIndikatorDanBuktiDukung() {
        const id_kuesioner = jQuery('#idKuesionerPertanyaan').val();
        const selectedOption = jQuery('#level option:selected');
        const id_detail_level = selectedOption.val();
        const level = jQuery('#level').val();
        //console.log("Kirim get_indikator_bukti_by_level => id_detail_level:", id_detail_level);
        if (level) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_indikator_bukti_by_level',
                    api_key: esakip.api_key,
                    id_kuesioner_mendagri_detail: id_detail_level
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        jQuery('#wrap-loading').hide();
                        jQuery('#indikator').val(response.data.indikator);
                        jQuery('#jenis_bukti_dukung').val(response.data.jenis_bukti_dukung);
                        jQuery('#penjelasan').val(response.data.penjelasan);
                        jQuery('#id_detail_level').val(response.data.id_detail_level);
                    } else {
                        alert(response.message);
                        jQuery('#wrap-loading').hide();
                        jQuery('#indikator').val('');
                        jQuery('#jenis_bukti_dukung').val('');
                        jQuery('#penjelasan').val('');
                    }
                },

                error: function() {
                    jQuery('#indikator').val('');
                    jQuery('#jenis_bukti_dukung').val('');
                }
            });
        } else {
            jQuery('#indikator').val('');
            jQuery('#jenis_bukti_dukung').val('');
        }
    }

    function getAllLevelByIdVariabel(idVariabel) {
        return jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_all_level_by_id_variabel',
                api_key: esakip.api_key,
                id_variabel: idVariabel
            },
            dataType: 'json'
        });
    }

    async function tambahKuesionerDetail(id_variabel, id_level = '') {
        try {
            jQuery('#wrap-loading').show();
            jQuery('#idKuesionerPertanyaan').val(id_variabel);

            // Ambil semua level
            const response = await getAllLevelByIdVariabel(id_variabel);

            let allLevel = [];
            if (response.status) {
                allLevel = response.data;
            } else {
                throw new Error(response.message || 'Gagal mendapatkan data level.');
            }

            // Konversi angka ke romawi
            const romawi = ['', 'I', 'II', 'III', 'IV', 'V'];

            // Buat isi dropdown
            let options = `<option value="">Pilih Tingkat Kematangan</option>`;
            allLevel.forEach(function(val) {
                const label = romawi[val.level] || val.level;
                options += `<option value="${val.id}" data-level="${val.level}">Tingkat ${label}</option>`;
            });

            // Cari ID detail level dari level angka (misal 1, 2, 3)
            let id_detail_level = '';
            if (id_level !== '') {
                const found = allLevel.find(item => item.level == id_level);
                if (found) {
                    id_detail_level = found.id;
                }
            }
            
            jQuery('#level').html(options).val(id_detail_level);

            // Kosongkan field lain
            jQuery('#indikator').val('');
            jQuery('#jenis_bukti_dukung').val('');
            jQuery('#penjelasan').val('');
            jQuery('#id_detail_level').val('');

            // Jika sudah ada level, ambil indikator & bukti
            if (id_level !== '') {
                await getIndikatorDanBuktiDukung();
            } else {
                jQuery('#wrap-loading').hide();
            }

            // Tampilkan modal
            jQuery('#wrap-indikator-bukti-dukung').show();
            jQuery('#tambahKuesionerDetailModal').modal('show');
            
        } catch (err) {
            console.error('Terjadi kesalahan saat memuat data tingkat kematangan:', err);
            alert('Gagal memuat data tingkat kematangan. Silakan coba lagi.');
        }
    }

    function submit_tingkat() {
        const id_kuesioner = jQuery('#idKuesionerPertanyaan').val();
        const selectedOption = jQuery('#level option:selected');
        const id_detail_level = selectedOption.val();
        const level = selectedOption.data('level'); 
        const indikator = jQuery('#indikator').val();
        const penjelasan = jQuery('#penjelasan').val();
        const id_skpd = jQuery('#id_skpd').val();
        if (!id_detail_level || !level) {
            return alert('Tingkat kematangan harus dipilih!');
        }

        if (confirm('Apakah anda yakin untuk menyimpan data ini?')) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: "submit_detail_kuesioner",
                    api_key: esakip.api_key,
                    tahun_anggaran: <?php echo $input['tahun']; ?>,
                    id_variabel: id_kuesioner,
                    id_detail_level: id_detail_level,
                    id_skpd: id_skpd,
                    level: level,
                    indikator: indikator,
                    penjelasan: penjelasan,
                },
                dataType: "json",
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if (response.status) {
                        alert('Berhasil disimpan!');
                        jQuery('#tambahKuesionerDetailModal').modal('hide');
                        jQuery('#wrap-indikator-bukti-dukung input, #wrap-indikator-bukti-dukung textarea').val('');
                        get_table_variabel_pengisian_mendagri();
                    } else {
                        alert('Gagal: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(error);
                    alert('Terjadi kesalahan saat menyimpan tingkat.');
                }
            });
        }
    }

    function tambahDokumenBuktiDukung(id_detail_pengisian, id_jenis_data_dukung) {
        var id_skpd = jQuery('#id_skpd').val();
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_dokumen_bukti_dukung_kuesioner',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id_detail_pengisian: id_detail_pengisian,
                id_jenis_data_dukung: id_jenis_data_dukung,
                id_skpd: id_skpd
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();

                if (response.status == 'success') {
                    var html = '';
                    var url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/'; ?>';
                    var list_bukti_dukung = {};
                    response.data_existing.map(function(b, i) {
                        list_bukti_dukung[b] = b;
                    });
                    for (var i in response.data) {
                        response.data[i].map(function(data, ii) {
                            var checked = '';
                            var namaFile = data.dokumen;
                            //console.log('Dokumen:', data.dokumen);
                            //console.log('Nama file:', namaFile);
                            //console.log('data_existing =', response.data_existing);

                            if (response.data_existing.includes(data.dokumen)) {
                                checked = 'checked';
                            }


                            html += '' +
                                '<tr>' +
                                '<td class="text-center"><input type="checkbox" ' + checked + ' class="list-dokumen" value="' + data.dokumen + '"/></td>' +
                                '<td>' + i + '</td>' +
                                '<td><a href="' + url + data.dokumen + '" target="_blank">' + data.dokumen + '</a></td>' +
                                '<td>' + data.keterangan + '</td>' +
                                '<td class="text-center">' + data.created_at + '</td>' +
                                '</tr>';
                        });
                    };
                    jQuery('#tableBuktiDukung tbody').html(html);
                    jQuery('#uploadBuktiDukung').html(response.upload_bukti_dukung);
                    jQuery('#jenis_bukti_dukung_json').val(id_jenis_data_dukung);
                    jQuery('#id_detail_pengisian_hidden').val(id_detail_pengisian);
                    jQuery('#tambahBuktiDukung').modal('show');

                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengambil data!');
            }
        });
    }

    function submit_bukti_dukung() {
        var jenis_bukti_dukung = jQuery('#jenis_bukti_dukung_json').val();
        console.log('jenis_bukti_dukung:', jenis_bukti_dukung);
        var id_skpd = jQuery('#id_skpd').val();
        var id_kuesioner_mendagri_detail = jQuery('#idKuesionerPertanyaanDetail').val();
        var id_detail_pengisian = jQuery('#id_detail_pengisian_hidden').val();
        var bukti_dukung = [];

        jQuery('#tableBuktiDukung tbody .list-dokumen').map(function(i, b) {
            if (jQuery(b).is(':checked')) {
                bukti_dukung.push(jQuery(b).val());
            }
        });

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'submit_bukti_dukung_kuesioner',
                api_key: esakip.api_key,
                id_jenis_bukti_dukung: jenis_bukti_dukung,
                id_skpd: id_skpd,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id_detail_pengisian: id_detail_pengisian,
                dokumen_upload: bukti_dukung
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status == 'error') {
                    alert(response.message);
                } else {
                    var url = esakip.plugin_url + 'public/media/dokumen/';
                    var html = '';
                    if (Array.isArray(response.data)) {
                        response.data.map(function(b, i) {
                            html += '<a href="' + url + b + '" target="_blank">' + b + '</a>';
                        });
                    }
                    alert(response.message);
                    jQuery('#tambahBuktiDukung').modal('hide');
                    get_table_variabel_pengisian_mendagri();
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                alert("An error occurred: " + xhr.status + " " + xhr.statusText);
            }
        });
    }
</script>