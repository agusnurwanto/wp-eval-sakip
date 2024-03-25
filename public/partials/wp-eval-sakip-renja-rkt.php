<?php
if (!defined('WPINC')) {
	die;
}
$input = shortcode_atts(array(
	'tahun' => '2022'
), $atts);

global $wpdb;

$unit = $wpdb->get_results($wpdb->prepare("
	SELECT 
		nama_skpd, 
		id_skpd, 
		kode_skpd, 
		nipkepala 
	from data_unit 
	where active=1 
		and tahun_anggaran=%d
		and is_skpd=1 
	order by kode_skpd ASC
", $input['tahun']), ARRAY_A);

$dokumen_unset = $wpdb->get_results("
    SELECT 
        *
    FROM esakip_renja_rkt 
    WHERE tahun_anggaran IS NULL
      AND active = 1
", ARRAY_A);

$tbody = '';
$tbodyUnset = '';
$counter = 1;
$counterUnset = 1;

foreach ($unit as $kk => $vv) {
	$tbody .= "<tr>";
	$tbody .= "<td>" . $counter++ . "</td>";
	$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
	$tbody .= "<td>" . $vv['nama_skpd'] . "</td>";

	$jumlah_dokumen = $wpdb->get_var(
		$wpdb->prepare(
			"
			SELECT 
				COUNT(id)
			FROM esakip_renja_rkt 
			WHERE id_skpd = %d
			  AND tahun_anggaran = %d
			  AND active = 1
			",
			$vv['id_skpd'],
			$input['tahun']
		)
	);

	$aksi = "<div class='d-flex justify-content-center'><button class='btn btn-warning' onclick='detail_dokumen(" . $vv['id_skpd'] . ")'><span class='dashicons dashicons-ellipsis'></span></button></div>";
	$tbody .= "<td>" . $jumlah_dokumen . "</td>";
	$tbody .= "<td>" . $aksi . "</td>";
	$tbody .= "</tr>";
}

foreach ($dokumen_unset as $kk => $vv) {
	$tbodyUnset .= "<tr>";
	$tbodyUnset .= "<td>" . $counterUnset++ . "</td>";
	$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";

	$aksiUnset = "<div class='d-flex justify-content-center'><button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . ")'><span class='dashicons dashicons-insert'></span></button><div class='d-flex justify-content-center'>";
	$tbodyUnset .= "<td>" . $aksiUnset . "</td>";

	$tbodyUnset .= "</tr>";
}
?>
<div class="container-md">
	<div class="table-responsive">
		<div class="table-container p-5 rounded shadow">
			<h1 class="text-center">Dokumen RENJA / RKT Tahun <?php echo $input['tahun']; ?></h1>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center">No</th>
						<th class="text-center">Kode SKPD</th>
						<th class="text-center">Nama SKPD</th>
						<th class="text-center">Jumlah Dokumen</th>
						<th class="text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $tbody; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="container-md">
	<div class="table-responsive">
		<div class="table-container p-5 rounded shadow">
			<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center">No</th>
						<th class="text-center">Nama Dokumen</th>
						<th class="text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $tbodyUnset; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	function detail_dokumen() {
		alert("oke")
	}

	function set_tahun_dokumen() {
		alert("oke s")
	}
</script>