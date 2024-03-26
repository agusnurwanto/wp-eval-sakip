<?php
global $wpdb;

if (!defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'tahun' => '2022'
), $atts);

?>
<div class="container">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center" style="margin:3rem;">Dokumen SKP (Sasaran Kinerja Pegawai) <br> Tahun Anggaran <?php echo $input['tahun']; ?></h1>
			<div style="margin-bottom: 25px;">
				<button class="btn btn-primary" onclick="tambah_data_skp();"><i class="dashicons dashicons-plus"></i>Tambah Data</button>
			</div>
			<div class="wrap-table">
				<table id="management_data_table" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
					<thead>
						<tr>
							<th class="text-center">No</th>
							<th class="text-center">Perangkat Daerah</th>
							<th class="text-center">Nama Dokumen</th>
							<th class="text-center">Keterangan</th>
							<th class="text-center">Waktu Upload</th>
							<th class="text-center" style="width: 150px;">Aksi</th>
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
	function tambah_data_pencairan_bhrd() {}
</script>