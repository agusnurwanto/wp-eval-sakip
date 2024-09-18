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
if(
    empty($response)
    || empty($data_jadwal_wpsipd)
){
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
            <h1 class="text-center" style="margin:3rem;">CASCADING <br><?php echo $skpd['nama_skpd'] ?><br><?php echo $nama_jadwal; ?></h1>
            <div id="action" class="action-section text-center">
                <a style="margin-right: 10px;" id="singkron-cascading-renstra" href="#" class="btn btn-primary"><i class="dashicons dashicons-download"></i> Ambil dari Data RENSTRA</a>
            </div>
        </div>
        <table id="tabel-cascading">
            <tbody>
                <tr>
                    <td class="text-center" style="width: 200px;"><button class="btn btn-lg btn-info">TUJUAN</button></td>
                    <td class="text-center" colspan="0"><button class="btn btn-lg btn-warning" style="text-transform:uppercase;"></button></td>
                </tr>
                <tr>
                    <td class="text-center"><button class="btn btn-lg btn-info">SASARAN</button></td>
                    <td class="text-center" colspan="0"><button class="btn btn-lg btn-warning" style="text-transform:uppercase;"></button></td>
                </tr>
                <tr>
                    <td class="text-center"><button class="btn btn-lg btn-info">PROGRAM</button></td>
                    <td class="text-center" colspan="0"><button class="btn btn-lg btn-warning"></button></td>
                </tr>
            </tbody>
        </table>
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
        jQuery('#singkron-cascading-renstra').on('click', function(e){
            e.preventDefault();
            if(confirm('Apakah anda yakin untuk mengambil data CASCADING dari RENSTRA? Data lama akan diupdate dengan data baru!')){
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    url: esakip.url,
                    type: 'POST',
                    data: {
                        action: 'get_cascading_pd_from_renstra',
                        api_key: esakip.api_key,
                        id_jadwal_wpsipd: <?php echo $input['periode']; ?>,
                        id_skpd: <?php echo $id_skpd; ?>
                    },
                    dataType: 'json',
                    success: function(response) {
                        jQuery('#wrap-loading').hide();
                        console.log(response);
                        if (response.status === 'success') {
                            getTableCascading();
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
        });
    });

    function getTableCascading() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_cascading_pd',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#tabel-cascading tbody').html(response.data);
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