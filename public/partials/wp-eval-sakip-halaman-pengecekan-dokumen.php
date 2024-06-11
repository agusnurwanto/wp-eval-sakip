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
		AND user_role= 'perangkat_daerah'
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

// RENSTRA
$dokumen_renstra = $wpdb->get_results(
	"
	SELECT 
		id,
		nama_jadwal,
		tahun_anggaran,
		lama_pelaksanaan
	FROM esakip_data_jadwal
	WHERE tipe = 'RPJMD'
	  AND status = 1",
	ARRAY_A
);
$get_dok_html_renstra = "";
foreach($dokumen_renstra as $dok_renstra){
	$tahun_anggaran_selesai = $dok_renstra['tahun_anggaran'] + $dok_renstra['lama_pelaksanaan'];
	$get_dok_html_renstra .= "<th class='text-center'>RENSTRA | ".$dok_renstra['nama_jadwal']." Periode ".$dok_renstra['tahun_anggaran']." - ".$tahun_anggaran_selesai."</th>";
}
$unit_html_renstra = "";
$no_renstra = 0;
foreach($unit as $opd_renstra){
	$no_renstra++;
	$dok_html_renstra = "";
	foreach($dokumen_renstra as $dok_renstra){
		$jml_dokumen_renstra = $wpdb->get_var($wpdb->prepare("
			SELECT 
				count(id)
			FROM esakip_renstra
			WHERE id_skpd=%d
				AND active=1
				AND id_jadwal=%d
		", $opd_renstra['id_skpd'], $dok_renstra['id']));
	$warning = "bg-success";
	if($jml_dokumen_renstra == 0){
		$warning="bg-danger";
	}
		$dok_html_renstra .= "<td class='text-center $warning'>$jml_dokumen_renstra</td>";
	}
	$unit_html_renstra .= "
	<tr>
		<td class='text-center'>".$no_renstra."</td>
		<td>".$opd_renstra['nama_skpd']."</td>
		$dok_html_renstra
	</tr>";
}

// Pemerintah Daerah
$dokumen_pemda = $wpdb->get_results($wpdb->prepare("
	SELECT
		*
	FROM esakip_menu_dokumen
	WHERE tahun_anggaran=%d
		AND active=1
		AND user_role='pemerintah_daerah'
		AND nama_dokumen NOT IN ('RPJPD', 'RPJMD')
", $input['tahun_anggaran']), ARRAY_A);
$get_dok_html_pemda = "";
foreach($dokumen_pemda as $dok_pemda){
	$get_dok_html_pemda .= "<th class='text-center'>".$dok_pemda['nama_dokumen']."</th>";
}

$unit_html_pemda = "";
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
	$dok_html_pemda
</tr>";

// RPJPD DAN RPJM

$dokumen_rpjpd_rpjmd = $wpdb->get_results($wpdb->prepare("
	SELECT 
		id,
		nama_jadwal,
		tahun_anggaran,
		tipe,
		lama_pelaksanaan
	FROM esakip_data_jadwal
	WHERE tipe IN ('RPJPD','RPJMD')
		AND status = 1
	ORDER BY tipe DESC, id ASC
",), ARRAY_A);

$get_dok_html_rpjpd_rpjmd = "";
$unit_html_rpjpd_rpjmd = "";
$dok_html_rpjpd_rpjmd = "";
foreach($dokumen_rpjpd_rpjmd as $dok_rpjpd_rpjmd){
	$tahun_anggaran_selesai_rpjpd_rpjmd = $dok_rpjpd_rpjmd['tahun_anggaran'] + $dok_rpjpd_rpjmd['lama_pelaksanaan'];
	$get_dok_html_rpjpd_rpjmd .= "<th class='text-center'>".$dok_rpjpd_rpjmd['tipe']." | ".$dok_rpjpd_rpjmd['nama_jadwal']." Periode ".$dok_rpjpd_rpjmd['tahun_anggaran']." - ".$tahun_anggaran_selesai_rpjpd_rpjmd."</th>";
	$tabel = "";
	if ($dok_rpjpd_rpjmd['tipe'] == 'RPJPD' ){
		$tabel = 'esakip_rpjpd';
	} else if($dok_rpjpd_rpjmd['tipe'] == 'RPJMD'){
		$tabel = 'esakip_rpjmd';
	}
	$jml_dokumen_rpjpd_rpjmd = $wpdb->get_var($wpdb->prepare("
		SELECT 
			count(id)
		FROM $tabel
		WHERE active=1
			AND id_jadwal=%d
	", $dok_rpjpd_rpjmd['id']));
	$warning = "bg-success";
	if($jml_dokumen_rpjpd_rpjmd == 0){
		$warning="bg-danger";
	}
	$dok_html_rpjpd_rpjmd .= "<td class='text-center $warning'>$jml_dokumen_rpjpd_rpjmd</td>";
}

$unit_html_rpjpd_rpjmd .= "
<tr>
	$dok_html_rpjpd_rpjmd
</tr>";

?>

<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Monitor Upload Dokumen RPJPD dan RPJMD</h1>
			<table>
				<thead>
					<tr>
						<?php echo $get_dok_html_rpjpd_rpjmd; ?>
					</tr>
				</thead>
				<tbody>
					<?php echo $unit_html_rpjpd_rpjmd; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Monitor Upload Dokumen RENSTRA</h1>
			<table>
				<thead>
					<tr>
						<th>No</th>
						<th>Perangkat Daerah</th>
						<?php echo $get_dok_html_renstra; ?>
					</tr>
				</thead>
				<tbody>
					<?php echo $unit_html_renstra; ?>
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
