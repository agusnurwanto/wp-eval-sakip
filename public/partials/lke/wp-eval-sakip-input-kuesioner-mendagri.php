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
                        <th class="text-center" colspan="4" style="vertical-align: middle;">Nama Kuesioner</th>
                        <th class="text-center" style="vertical-align: middle;">Indikator</th>
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
<script>
    jQuery(document).ready(function() {
        get_table_kuesioner();
    })

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
</script>