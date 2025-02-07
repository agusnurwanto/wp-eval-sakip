<?php
if (!defined('WPINC')) {
	die;
}
global $wpdb;

$input = shortcode_atts(array(
	'tahun_anggaran' => '2000'
), $atts);

$id_skpd = '';
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
	$id_skpd = $_GET['id_skpd'];
} else {
	die('Parameter tidak lengkap!');
}

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
$nama_skpd = $this->get_data_skpd_by_id($id_skpd, $tahun_anggaran_sakip);

$id_satker = $wpdb->get_var(
	$wpdb->prepare("
		SELECT 
			id_satker_simpeg
		FROM esakip_data_mapping_unit_sipd_simpeg
		WHERE id_skpd = %d
		  AND tahun_anggaran = %d
		  AND active = 1
	", $id_skpd, $tahun_anggaran_sakip)
);

//get data simpeg unor
$error_message = array();
$simpeg_unor = $this->get_pegawai_simpeg('unor', $id_satker);
$response = json_decode($simpeg_unor, true);
if (!isset($response['status']) || $response['status'] === false) {
	array_push($error_message, $response['message']);
}
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

	.table_list_skpd thead {
		position: sticky;
		top: -6px;
	}

	.table_list_skpd thead th {
		vertical-align: middle;
	}

	.table_list_skpd tfoot {
		position: sticky;
		bottom: 0;
	}

	.table_list_skpd tfoot th {
		vertical-align: middle;
	}
</style>
<!-- Error Message -->
<?php if (!empty($error_message) && is_array($error_message)) : ?>
	<div class="container-md mx-auto" style="width: 900px;">
		<div class="alert alert-danger mt-3">
			<ul class="mb-0">
				<?php echo implode('', array_map(fn($msg) => "<li>{$msg}</li>", $error_message)); ?>
			</ul>
		</div>
	</div>
<?php endif; ?>

<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Laporan Perjanjian Kinerja</br><?php echo $nama_skpd['nama_skpd'] ?></br>Tahun Anggaran <?php echo $input['tahun_anggaran']; ?></h1>
			<div id="action" class="action-section hide-excel"></div>
			<div class="wrap-table mt-2">
				<table id="cetak" title="List Pegawai Laporan Perjanjian Kinerja Perangkat Daerah" class="table table-bordered table_list_pegawai" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;">
					<thead style="background: #ffc491;">
						<tr>
							<th class="text-center">Satker ID</th>
							<th class="text-center">Satuan Kerja</th>
							<th class="text-center">Tipe Pegawai</th>
							<th class="text-center">NIP</th>
							<th class="text-center">Nama Pegawai</th>
							<th class="text-center">Jabatan</th>
							<th class="text-center">Jumlah Dokumen Finalisasi</th>
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
		getTablePegawai();
	});

	function getTablePegawai(destroy) {
		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_table_pegawai_simpeg_pk',
				api_key: esakip.api_key,
				tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>,
				id_skpd: <?php echo $id_skpd; ?>
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					if (destroy == 1) {
						laporan_pk_table.fnDestroy();
					}
					jQuery('.table_list_pegawai tbody').html(response.data);
					window.laporan_pk_table = jQuery('.table_list_pegawai').dataTable({
						aLengthMenu: [
							[5, 10, 25, 100, -1],
							[5, 10, 25, 100, "All"]
						],
						iDisplayLength: -1,
						order: []
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
</script>