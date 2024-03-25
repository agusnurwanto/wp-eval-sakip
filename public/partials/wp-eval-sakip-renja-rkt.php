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

$detail_renja = $this->functions->generatePage(array(
	'nama_page' => 'Halaman Detail Dokumen RENJA/RKT ' . $input['tahun'],
	'content' => '[dokumen_detail_renja_rkt tahun=' . $input["tahun"] . ']',
	'show_header' => 1,
	'no_key' => 1,
	'post_status' => 'private'
));
$detail_renja['url'] .= '?1=1';

foreach ($unit as $kk => $vv) {
	$tbody .= "<tr>";
	$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
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

	$aksi = "<a class='custom-button' target='_blank' href='" . $detail_renja['url'] . '&id_skpd=' . $vv['id_skpd'] . "'><span class='dashicons dashicons-arrow-right-alt2'></span></a>";
	$tbody .= "<td>" . $jumlah_dokumen . "</td>";
	$tbody .= "<td class='text-center'>" . $aksi . "</td>";
	$tbody .= "</tr>";
}

foreach ($dokumen_unset as $kk => $vv) {
	$tbodyUnset .= "<tr>";
	$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
	$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
	$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
	$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

	$aksiUnset = "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . ")'><span class='dashicons dashicons-insert'></span></button>";
	$tbodyUnset .= "<td class='text-center'>" . $aksiUnset . "</td>";

	$tbodyUnset .= "</tr>";
}
?>

<style type="text/css">
	a.custom-button {
		color: inherit;
		text-decoration: none;
		background-color: transparent;
		cursor: pointer;
	}
</style>
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
						<th class="text-center">Perangkat Daerah</th>
						<th class="text-center">Nama Dokumen</th>
						<th class="text-center">Keterangan</th>
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

<div class="modal fade" id="tahunModal" tabindex="-1" role="dialog" aria-labelledby="tahunModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tahunModalLabel">Pilih Tahun Anggaran</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="tahunForm">
					<div class="form-group">
						<label for="tahunAnggaran">Pilih Tahun Anggaran:</label>
						<select class="form-control" id="tahunAnggaran" name="tahunAnggaran">
							<option value="2022">2022</option>
							<option value="2023">2023</option>
							<option value="2024">2024</option>
						</select>
					</div>
					<button type="submit" class="btn btn-primary">Simpan</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	function set_tahun_dokumen() {
		jQuery('#tahunModal').modal('show');
	}

	function detail_dokumen() {
		jQuery('#uploadForm').modal('show');

	}
</script>