<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
	'tahun' => '2022',
	'periode' => '1'
), $atts);

if (!empty($_GET) && !empty($_GET['id_periode'])) {
    $input['periode'] = $_GET['id_periode'];
}
$id_skpd = 0;
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

//jadwal renstra wpsipd
$api_params = array(
	'action' => 'get_data_jadwal_wpsipd',
	'api_key'	=> get_option('_crb_apikey_wpsipd'),
	'tipe_perencanaan' => 'monev_renstra',
	'id_jadwal' => $input['periode']
);

$response = wp_remote_post(get_option('_crb_url_server_sakip'), array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));

$response = wp_remote_retrieve_body($response);

$data_jadwal_wpsipd = json_decode($response, true);
if(empty($data_jadwal_wpsipd)){
	die('<h1 class="text-center">Jadwal periode WP-SIPD tidak ditemukan!</h1>');
}else if(empty($id_skpd)){
	die('<h1 class="text-center">ID OPD tidak boleh kosong!</h1>');
}

if (!empty($data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran']) && $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'] > 1) {
	$tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'];
} else {
	$tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_anggaran'] + $data_jadwal_wpsipd['data'][0]['lama_pelaksanaan'];
}

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

$nama_jadwal = $data_jadwal_wpsipd['data'][0]['nama'] . ' ' . '(' . $data_jadwal_wpsipd['data'][0]['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ')';

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

    #table_dokumen_cascading th {
        vertical-align: middle;
    }

    #tabel-cascading,
    #tabel-cascading td,
    #tabel-cascading table {
        padding: 0;
        border: 4px solid white;
        margin: 0;
        vertical-align: top;
    }

    #tabel-cascading>tbody>tr>td {
        padding: 10px;
    }

    #tabel-cascading button.btn.btn-lg.btn-info,
    #tabel-cascading button.btn.btn-lg.btn-warning {
        width: 100%;
        min-height: 75px;
    }

    @media print {
        #cetak {
            max-width: auto !important;
            height: auto !important;
        }

        @page {
            size: landscape;
        }

        #action-sakip,
        .site-header,
        .site-footer,
        #container-table-cascading,
        #ast-scroll-top {
            display: none;
        }
    }
</style>

<!-- Table -->
<div class="container-md" id="container-table-cascading">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Cascading <br><?php echo $skpd['nama_skpd'] ?><br><?php echo $nama_jadwal; ?></h1>
        </div>
    </div>
</div>

<div id="view_cascading">
    <div id="cetak" title="Laporan Pohon Kinerja" style="padding: 5px; overflow: auto; height: 100vh;">
        <div id="chart_div"></div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade mt-4" id="modalEditCascading" tabindex="-1" role="dialog" aria-labelledby="modalEditCascadingLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditCascadingLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="<?php echo $input['periode']; ?>" id="id_jadwal">
                <input type="hidden" value="" id="id">
                <div class="form-group">
                    <label for="nama_cascading">Nama Cascading</label>
                    <input type="text" class="form-control" id="nama_cascading" name="nama_cascading" required>
                </div>
                <div class="form-group">
                    <label for="tujuan_teks">Tujuan RPJMD/RPD</label>
                    <input type="text" class="form-control" id="tujuan_teks" name="tujuan_teks" disabled>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary submitBtn" onclick="submit_edit_cascading()">Simpan</button>
                <button type="submit" class="components-button btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        getTableCascading();

    });

    function getDataChart() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_chart_cascading_pd',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen_cascading tbody').html(response.data);
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

    function getTableCascading() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_cascading_pd',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen_cascading tbody').html(response.data);
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

    function edit_cascading_opd(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'edit_cascading_opd',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
                id: id,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    let data = response.data;
                    jQuery('#id').val(data.id);
                    jQuery('#tujuan_teks').val(data.tujuan_teks);
                    if (data.nama_cascading) {
                        jQuery('#nama_cascading').val(data.nama_cascading);
                    } else {
                        jQuery('#nama_cascading').val(data.tujuan_teks);
                    }
                    jQuery('#modalEditCascading .send_data').show();
                    jQuery('#modalEditCascading').modal('show');
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

    function submit_edit_cascading_opd() {
        let id = jQuery("#id").val();
        if (id == '') {
            return alert('Id tidak boleh kosong');
        }
        var tujuan_teks = jQuery('#tujuan_teks').val();
        if (tujuan_teks == '') {
            return alert('Data tujuan_teks tidak boleh kosong!');
        }
        var nama_cascading = jQuery('#nama_cascading').val();
        if (nama_cascading == '') {
            return alert('Data nama_cascading tidak boleh kosong!');
        }

        let form_data = new FormData();
        form_data.append('action', 'submit_edit_cascading_opd');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id', id);
        form_data.append('nama_cascading', nama_cascading);
        form_data.append('tujuan_teks', tujuan_teks);

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
                    jQuery('#modalEditCascading').modal('hide');
                    alert(response.message);
                    getTableCascading();
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

    function view_cascading(id_tujuan) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'view_cascading_opd',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
                id: id_tujuan,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#chart_div').html(response.html);
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
</script>