<?php
global $wpdb;

if (!defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'tahun' => '2022',
	'periode' => '1'
), $atts);

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

if (!empty($data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran']) && $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'] > 1) {
	$tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'];
} else {
	$tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_anggaran'] + $data_jadwal_wpsipd['data'][0]['lama_pelaksanaan'];
}

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
	.table_dokumen_skpd thead{
		position: sticky;
        top: -6px;
	}.table_dokumen_skpd thead th{
		vertical-align: middle;
	}
	.table_dokumen_skpd tfoot{
        position: sticky;
        bottom: 0;
    }

	.table_dokumen_skpd tfoot th{
		vertical-align: middle;
	}
</style>
<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Pengisian IKU</br><?php echo $nama_jadwal; ?></h1>
			<div id="action" class="action-section hide-excel"></div>
			<div class="wrap-table">
				<table id="cetak" title="Rekapitulasi IKU Perangkat Daerah" class="table table-bordered table_dokumen_skpd" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;">
					<thead style="background: #ffc491;">
						<tr>
							<th class="text-center">Nama Perangkat Daerah</th>
							<th class="text-center">IKU</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function() {
    	run_download_excel_sakip();
		getTableSkpd();
	});

	function getTableSkpd() {
		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_table_skpd_input_iku',
				api_key: esakip.api_key,
				id_jadwal_wpsipd: <?php echo $input['periode']; ?>,
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('.table_dokumen_skpd tbody').html(response.data);
					jQuery('.table_dokumen_skpd').dataTable({
						 aLengthMenu: [
					        [5, 10, 25, 100, -1],
					        [5, 10, 25, 100, "All"]
					    ],
					    iDisplayLength: -1
					});
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

	function toDetailUrl(url) {
		window.open(url, '_blank');
	}
</script>