<?php
global $wpdb;

if (!defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'tahun_anggaran' => '2022'
), $atts);

$dokumen = $wpdb->get_results($wpdb->prepare("
	SELECT
		*
	FROM esakip_menu_dokumen
	WHERE tahun_anggaran=%d
		AND active=1
		AND jenis_role=2
", $input['tahun_anggaran']), ARRAY_A);
$dok_html = "";
foreach($dokumen as $dok){
	$dok_html .= "<th class='text-center'>".$dok['nama_dokumen']."</th>";
}

$unit = $wpdb->get_results($wpdb->prepare("
	SELECT
		*
	FROM esakip_data_unit
	WHERE tahun_anggaran=%d
		AND active=1
		AND is_skpd=1
", $input['tahun_anggaran']), ARRAY_A);
$unit_html = "";
$no = 0;
foreach($unit as $opd){
	$no++;
	$dok_html_opd = "";
	foreach($dokumen as $dok){
		$jml_dokumen = $wpdb->get_var($wpdb->prepare("
			SELECT 
				count(id)
			FROM $dok[nama_tabel]
			WHERE id_skpd=%d
				AND active=1
		", $opd['id_skpd']));
		$warning = "bg-success";
		if($jml_dokumen == 0){
			$warning="bg-danger";
		}
		$dok_html_opd .= "<td class='text-center $warning'>$jml_dokumen</td>";
	}
	$unit_html .= "
	<tr>
		<td class='text-center'>".$no."</td>
		<td>".$opd['nama_skpd']."</td>
		$dok_html_opd
	</tr>";
}
?>

<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Monitor Upload Dokumen Tahun <?php echo $input['tahun_anggaran']; ?></h1>
			<table>
				<thead>
					<tr>
						<th>No</th>
						<th>Perangkat Daerah</th>
					<?php echo $dok_html; ?>
					</tr>
				</thead>
				<tbody>
				<?php echo $unit_html; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>