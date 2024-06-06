<?php
global $wpdb;

if (!defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'tahun_anggaran' => '2022'
), $atts);

// Perangkat Daerah
$dokumen = $wpdb->get_results($wpdb->prepare("
	SELECT
		*
	FROM esakip_menu_dokumen
	WHERE tahun_anggaran=%d
		AND active=1
		AND jenis_role=2
		AND nama_dokumen NOT LIKE 'RENSTRA'
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
				AND tahun_anggaran=%d
		", $opd['id_skpd'], $input['tahun_anggaran']));
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

// Pemerintah Daerah
$dokumen_pemda = $wpdb->get_results($wpdb->prepare("
	SELECT
		*
	FROM esakip_menu_dokumen
	WHERE tahun_anggaran=%d
		AND active=1
		AND jenis_role=1
		AND nama_dokumen NOT IN ('RPJPD', 'RPJMD')
", $input['tahun_anggaran']), ARRAY_A);
$get_dok_html_pemda = "";
foreach($dokumen_pemda as $dok_pemda){
	$get_dok_html_pemda .= "<th class='text-center'>".$dok_pemda['nama_dokumen']."</th>";
}

$unit_html_pemda = "";
$no_pemda = 1;
$dok_html_pemda = "";
foreach($dokumen_pemda as $dok_pemda){
	$jml_dokumen_pemda = $wpdb->get_var($wpdb->prepare("
		SELECT 
			count(id)
		FROM $dok_pemda[nama_tabel]
		WHERE active=1
			AND tahun_anggaran=%d
	", $input['tahun_anggaran']));
	$warning = "bg-success";
	if($jml_dokumen_pemda == 0){
		$warning="bg-danger";
	}
	$dok_html_pemda .= "<td class='text-center $warning'>$jml_dokumen_pemda</td>";
}
$unit_html_pemda .= "
<tr>
	<td class='text-center'>".$no_pemda."</td>
	$dok_html_pemda
</tr>";

?>

<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Monitor Upload Dokumen Perangkat Daerah Tahun <?php echo $input['tahun_anggaran']; ?></h1>
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

<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Monitor Upload Dokumen Pemerintah Daerah Tahun <?php echo $input['tahun_anggaran']; ?></h1>
			<table>
				<thead>
					<tr>
						<th>No</th>
						<?php echo $get_dok_html_pemda; ?>
					</tr>
				</thead>
				<tbody>
					<?php echo $unit_html_pemda; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
