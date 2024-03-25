<?php 
if ( ! defined( 'WPINC' ) ) {
	die;
}
$input = shortcode_atts( array(
	'tahun' => '2022'
), $atts );

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
foreach ($unit as $kk => $vv) {

}
?>
<div id="wrap-table">
	<h1 class="text-center">Dokumen RENJA / RKT Tahun <?php echo $input['tahun']; ?></h1>
	<table>
		<thead>
			<tr>
				<th>Kode SKPD</th>
				<th>Nama SKPD</th>
				<th>Jumlah Dokumen</th>
				<th>Aksi</th>
			</tr>
		</thead>
	</table>
	<h3 class="text-center">Dokumen yang belum disetting tahun anggaran</h3>
	<table>
		<thead>
			<tr>
				<th>Nama Dokumen</th>
				<th>Aksi</th>
			</tr>
		</thead>
	</table>
</div>