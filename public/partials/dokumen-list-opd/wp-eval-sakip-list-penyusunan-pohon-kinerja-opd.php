<?php
global $wpdb;

if (!defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'tahun' => '2022',
    'periode'   => ''
), $atts);

$periode = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
		*
    FROM esakip_data_jadwal
    WHERE id=%d
      AND status = 1
", $input['periode']),
    ARRAY_A
);

if(!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1){
    $tahun_periode = $periode['tahun_selesai_anggaran'];
}else{
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

$idtahun = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT 
            *
        FROM esakip_data_jadwal
        WHERE tipe = %s",
        'RPJMD'
    ),
    ARRAY_A
);

$tahun = "<option value='-1'>Pilih Tahun Periode</option>";

foreach ($idtahun as $val) {
    if(!empty($val['tahun_selesai_anggaran']) && $val['tahun_selesai_anggaran'] > 1){
        $tahun_anggaran_selesai = $val['tahun_selesai_anggaran'];
    }else{
        $tahun_anggaran_selesai = $val['tahun_anggaran'] + $val['lama_pelaksanaan'];
    }
    $selected = '';
    if (!empty($input['id']) && $val['id'] == $input['periode']) {
        $selected = 'selected';
    }
    $tahun .= "<option value='$val[id]' $selected>$val[nama_jadwal] Periode $val[tahun_anggaran] -  $tahun_anggaran_selesai</option>";
}

$tipe_dokumen = "pohon_kinerja_dan_cascading";
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
    }
	.table_dokumen_skpd thead th{
        vertical-align: middle;
    }
	.table_dokumen_skpd tfoot{
        position: sticky;
        bottom: 0;
    }
</style>
<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Input Pohon Kinerja <br><?php echo $periode['nama_jadwal_renstra'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h1>
			<div id="action" class="action-section"></div>
			<div class="wrap-table">
				<table id="cetak" title="Rekapitulasi Pohon Kinerja Perangkat Daerah" class="table_dokumen_skpd table table-bordered">
					<thead style="background: #ffc491;">
						<tr>
							<th class="text-center">Nama Perangkat Daerah</th>
							<th class="text-center" width="80px">POKIN Level 1</th>
							<th class="text-center" width="80px">POKIN Level 2</th>
							<th class="text-center" width="80px">POKIN Level 3</th>
							<th class="text-center" width="80px">POKIN Level 4</th>
							<th class="text-center" width="80px">POKIN Level 5</th>
							<th class="text-center" width="80px">Croscutting Pengusul</th>
							<th class="text-center" width="80px">Croscutting Lembaga Vertikal</th>
							<th class="text-center" width="80px">Croscutting Dituju</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot style="background: #ffc491;">
						<tr>
							<th class="text-center">Jumlah</th>
							<th class="text-center" id="total_level_1">0</th>
							<th class="text-center" id="total_level_2">0</th>
							<th class="text-center" id="total_level_3">0</th>
							<th class="text-center" id="total_level_4">0</th>
							<th class="text-center" id="total_level_5">0</th>
							<th class="text-center" id="total_crosscutting_usulan">0</th>
							<th class="text-center" id="total_crosscutting_usulan_vertikal">0</th>
							<th class="text-center" id="total_crosscutting_tujuan">0</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="tahunContainer" class="container-md">
</div>

<script>
	jQuery(document).ready(function() {
    	run_download_excel_sakip();
		window.penyusunan_pohon_kinerja_opd = 'true';
		getTableSkpd();
	});

	function set_tahun_dokumen(id) {
		jQuery('#tahunModal').modal('show');
		jQuery('#idDokumen').val(id);
	}

	function getTableSkpd() {
		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_table_skpd_pohon_kinerja',
				api_key: esakip.api_key,
				tahun_anggaran: <?php echo $input['tahun']; ?>,
				id_periode: <?php echo $input['periode']; ?>,
				tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
				penyusunan_pohon_kinerja_opd: penyusunan_pohon_kinerja_opd,
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('.table_dokumen_skpd tbody').html(response.data);
					jQuery('#total_level_1').html(response.total_level_1);
					jQuery('#total_level_2').html(response.total_level_2);
					jQuery('#total_level_3').html(response.total_level_3);
					jQuery('#total_level_4').html(response.total_level_4);
					jQuery('#total_level_5').html(response.total_level_5);
					jQuery('#total_crosscutting_usulan').html(response.total_crosscutting_usulan);
					jQuery('#total_crosscutting_usulan_vertikal').html(response.total_crosscutting_usulan_vertikal);
					jQuery('#total_crosscutting_tujuan').html(response.total_crosscutting_tujuan);
					jQuery('.table_dokumen_skpd').dataTable({
						 aLengthMenu: [
					        [25, 50, 100, 200, -1],
					        [25, 50, 100, 200, "All"]
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