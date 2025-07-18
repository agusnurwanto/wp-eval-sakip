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
}else{
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
    if (!empty($input['tahun_anggaran']) && $val['tahun_anggaran'] == $input['tahun_anggaran']) {
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
            <table id="table_kuesioner_mendagri" cellpadding="2" cellspacing="0" style="collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
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

<!-- Modal Pilih Bukti Dukung -->
<div class="modal fade" id="tambahBuktiDukungModal" tabindex="-1" role="dialog" aria-labelledby="tambahBuktiDukungModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="tambahBuktiDukungModalLabel">Pilih Bukti Dukung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="idKuesionerPertanyaan">                
                <input type="hidden" id="idKuesionerPertanyaanDetail">             
                <input type="hidden" id="id_detail_level">
                <input type="hidden" id="id_skpd" value="<?php echo (int) $id_skpd; ?>">

                <div class="form-group col-md-12">
                     <label for="level">Tingkat Kematangan</label>
                     <select class="form-control" id="level" name="level" required>
                        <option value="1">Tingkat I</option>
                        <option value="2">Tingkat II</option>
                        <option value="3">Tingkat III</option>
                        <option value="4">Tingkat IV</option>
                        <option value="5">Tingkat V</option>
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
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submit_bukti(); return false">Simpan</button>
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
                api_key:  esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id_skpd: <?php echo $id_skpd; ?>
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
    function getIndikatorDanBuktiDukung() {
        const id_detail = jQuery('#idKuesionerPertanyaanDetail').val();
        const level = jQuery('#level').val();

        if (id_detail && level) {
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_indikator_bukti_by_level',
                    api_key: esakip.api_key,
                    id_detail: id_detail,
                    level: level
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        jQuery('#indikator').val(response.data.indikator);
                        jQuery('#jenis_bukti_dukung').val(response.data.jenis_bukti_dukung);
                        jQuery('#penjelasan').val(response.data.penjelasan);
                        jQuery('#id_detail_level').val(response.data.id_detail_level);
                    } else {
                        alert(response.message);
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
    function tambahBuktiDukung(id_detail, id_pertanyaan) {
        const level = 1;    
        jQuery('#idKuesionerPertanyaanDetail').val(id_detail);
        jQuery('#idKuesionerPertanyaan').val(id_pertanyaan);
        jQuery('#level').val('');
        jQuery('#indikator').val('');
        jQuery('#jenis_bukti_dukung').val('');
        jQuery('#penjelasan').val('');
        jQuery('#id_detail_level').val('');

        jQuery('#wrap-indikator-bukti-dukung').show();
        jQuery('#tambahBuktiDukungModal').modal('show');
    }
    function submit_bukti() {
        const id_pertanyaan = jQuery('#idKuesionerPertanyaan').val();
        const id_kuesioner_mendagri_detail = jQuery('#idKuesionerPertanyaanDetail').val();
        const id_kuesioner_mendagri = jQuery('#idKuesioner').val();
        const level = jQuery('#level').val();
        const indikator = jQuery('#indikator').val();
        const penjelasan = jQuery('#penjelasan').val();
        const id_skpd = jQuery('#id_skpd').val();

        if (confirm('Apakah anda yakin untuk menyimpan data ini?')) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: "submit_detail_kuesioner",
                    api_key: esakip.api_key,
                    tahun_anggaran: <?php echo $input['tahun']; ?>,
                    id_kuesioner_mendagri_detail,
                    id_pertanyaan,
                    id_skpd,
                    level,
                    indikator,
                    penjelasan,
                },
                dataType: "json",
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if(response.status === 'success') {
                        alert('Berhasil disimpan!');
                        jQuery('#tambahBuktiDukungModal').modal('hide');
                        jQuery('#wrap-indikator-bukti-dukung input, #wrap-indikator-bukti-dukung textarea').val('');
                        console.log('ID DETAIL:', id_kuesioner_mendagri_detail);
                        afterSimpan(id_kuesioner_mendagri, id_kuesioner_mendagri_detail);
                    } else {
                        alert('Gagal: ' + response.message);
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
    function afterSimpan(id_kuesioner, id_detail) {
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'get_detail_by_id',
                api_key: esakip.api_key,
                id_kuesioner: id_kuesioner,
                id_kuesioner_mendagri_detail: id_detail
            },
            success: function(response) {
                 console.log(response); 
                if (response.status === 'success') {
                    get_table_variabel_pengisian_mendagri()
                } else {
                    alert('gagal mengambil data detail.');
                }
            },
            error: function(xhr, status, error) {
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }
</script>