<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

global $wpdb;

$input = shortcode_atts(array(
	'periode' => '',
), $atts);
if(!empty($_GET) && !empty($_GET['id_jadwal'])){
	$input['periode'] = $_GET['id_jadwal'];
}

$id_skpd = false;
$nama_skpd = '';
$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
    $skpd = $wpdb->get_row(
	    $wpdb->prepare("
	    SELECT 
	        nama_skpd
	    FROM esakip_data_unit
	    WHERE id_skpd=%d
	      AND tahun_anggaran=%d
	      AND active = 1
	", $id_skpd, $tahun_anggaran_sakip),
	    ARRAY_A
	);
	$nama_skpd = $skpd['nama_skpd'].'<br>';
}

$periode = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
		*
    FROM esakip_data_jadwal
    WHERE id=%d
", $input['periode']),
    ARRAY_A
);

if(!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1){
    $tahun_periode = $periode['tahun_selesai_anggaran'];
}else{
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

$table = 'esakip_pohon_kinerja';
$where_skpd = '';
if($tipe == 'opd'){
	$table = 'esakip_pohon_kinerja_opd';
	$where_skpd = $wpdb->prepare('AND id_skpd=%d', $id_skpd);
}

$pohon_kinerja_level = $wpdb->get_var($wpdb->prepare("
	SELECT 
		level 
	FROM $table 
	WHERE id=%d
		AND active=1 
		AND id_jadwal=%d 
		$where_skpd
	ORDER BY nomor_urut
", $_GET['id'], $input['periode']));

$data_all = array('data' => $this->get_pokin(array(
	'id' => $_GET['id'],
	'level' => $pohon_kinerja_level,
	'periode' => $input['periode'],
	'tipe' => $tipe,
	'id_skpd' => $id_skpd
)));
// print_r($data_all); die();

$style0 = 'level0';
$style1 = 'class="level1"';
$style2 = 'class="level2"';
$style3 = 'class="level3"';
$style4 = 'class="level4"';
$style5 = 'class="level5"';

$view_kinerja_asal = $this->functions->generatePage(array(
	'nama_page' => 'View Pohon Kinerja OPD'.$nama_skpd,
	'content' => '[view_pohon_kinerja_opd periode='. $input['periode'] .']',
	'show_header' => 1,
	'post_status' => 'private'
));

$data_temp= [];
if(!empty($data_all['data'])){

	foreach ($data_all['data'] as $keylevel1 => $level_1) {
		$data_temp[$keylevel1][0] = (object)[
	      'v' => $level_1['id'],
	      'f' => "<div class=\"".$style0." label1\">".trim($level_1['label'])."</div>",
	    ];

	    if(!empty($level_1['indikator'])){
		    foreach ($level_1['indikator'] as $keyindikatorlevel1 => $indikator) {
		        $data_temp[$keylevel1][0]->f.="<div ".$style1.">IK: ".$indikator['label_indikator_kinerja']."</div>";
		    }
	    }

	    if(!empty($level_1['data'])){

		    foreach ($level_1['data'] as $keylevel2 => $level_2) {
		        $data_temp[$keylevel2][0] = (object)[
		          	'v' => $level_2['id'],
		          	'f' => "<div class=\"".$style0." label2\">".trim($level_2['label'])."</div>",
		        ];

		        if(!empty($level_2['indikator'])){
			        foreach ($level_2['indikator'] as $keyindikatorlevel2 => $indikator) {
			            $data_temp[$keylevel2][0]->f.="<div ".$style2.">IK: ".$indikator['label_indikator_kinerja']."</div>";
			        }
		        }

				// croscutting level 2
				if(!empty($level_2['croscutting'])){
					$data_temp[$keylevel2][0]->f.="<div class='croscutting-2 tampil_croscutting'>CROSCUTTING</div>";
					foreach ($level_2['croscutting'] as $keyCross => $valCross) {
							$nama_skpd_all = array();
							$class_cc_opd_lain = '';
							if($valCross['croscutting_opd_lain'] == 1){
								$class_cc_opd_lain = 'cc-opd-lain';
							}
							
							$show_nama_skpd = $valCross['nama_skpd'];
							$label_parent = $valCross['label_parent'];
							if(!empty($valCross['id_level_1_parent']) && $valCross['is_lembaga_lainnya'] != 1){
								$show_nama_skpd = "<a href='" . $view_kinerja_asal['url'] . "&id_skpd=" . $valCross['id_skpd_view_pokin']  . "&id=" . $valCross['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $valCross['nama_skpd'] . "</a>";
							}
							
							$class_cc_vertikal = '';
							if($valCross['is_lembaga_lainnya'] == 1){
								$label_parent = "?";
								$class_cc_vertikal = "croscutting-lembaga-vertikal";
							}
							$data_temp[$keylevel2][0]->f.="<div class='croscutting tampil_croscutting ". $class_cc_opd_lain ." ". $class_cc_vertikal ."'><div>". $label_parent ." <a href='javascript:void(0)' data-id='". $valCross['id'] ."' class='detail-cc' onclick='detail_cc(" . $valCross['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a></div><div class='cros-opd'>".$show_nama_skpd."</div></div>";
					}
				}

		        if(!empty($level_2['data'])){

			        foreach ($level_2['data'] as $keylevel3 => $level_3) {
			            $data_temp[$keylevel3][0] = (object)[
			              'v' => $level_3['id'],
			              'f' => "<div class=\"".$style0." label3\">".trim($level_3['label'])."</div>",
			            ];

			            if(!empty($level_3['indikator'])){
				            foreach ($level_3['indikator'] as $keyindikatorlevel3 => $indikator) {
				                $data_temp[$keylevel3][0]->f.="<div ".$style3.">IK: ".$indikator['label_indikator_kinerja']."</div>";
				            }
			            }

						// croscutting level 3
						if(!empty($level_3['croscutting'])){
							$data_temp[$keylevel3][0]->f.="<div class='croscutting-2 tampil_croscutting'>CROSCUTTING</div>";
							foreach ($level_3['croscutting'] as $keyCross => $valCross) {
								$nama_skpd_all = array();
								$class_cc_opd_lain = '';
								if($valCross['croscutting_opd_lain'] == 1){
									$class_cc_opd_lain = 'cc-opd-lain';
								}
								
								$show_nama_skpd = $valCross['nama_skpd'];
								$label_parent = $valCross['label_parent'];
								if(!empty($valCross['id_level_1_parent']) && $valCross['is_lembaga_lainnya'] != 1){
									$show_nama_skpd = "<a href='" . $view_kinerja_asal['url'] . "&id_skpd=" . $valCross['id_skpd_view_pokin']  . "&id=" . $valCross['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $valCross['nama_skpd'] . "</a>";
								}

								$class_cc_vertikal = '';
								if($valCross['is_lembaga_lainnya'] == 1){
									$label_parent = "?";
									$class_cc_vertikal = "croscutting-lembaga-vertikal";
								}
								$data_temp[$keylevel3][0]->f.="<div class='croscutting tampil_croscutting ". $class_cc_opd_lain ." ". $class_cc_vertikal ."'><div>". $label_parent ." <a href='javascript:void(0)' data-id='". $valCross['id'] ."' class='detail-cc' onclick='detail_cc(" . $valCross['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a></div><div class='cros-opd'>".$show_nama_skpd."</div></div>";
							}
						}

			            if(!empty($level_3['data'])){

		            		foreach ($level_3['data'] as $keylevel4 => $level_4) {
			            		$data_temp[$keylevel4][0] = (object)[
					              'v' => $level_4['id'],
					              'f' => "<div class=\"".$style0." label4\">".trim($level_4['label'])."</div>",
					            ];

					            if(!empty($level_4['indikator'])){
				            		foreach ($level_4['indikator'] as $keyindikatorlevel4 => $indikator) {
						                $data_temp[$keylevel4][0]->f.="<div ".$style4.">IK: ".$indikator['label_indikator_kinerja']."</div>";
						            }
					            }

								// croscutting level 4
								if(!empty($level_4['croscutting'])){
									$data_temp[$keylevel4][0]->f.="<div class='croscutting-2 tampil_croscutting'>CROSCUTTING</div>";
				            		foreach ($level_4['croscutting'] as $keyCross => $valCross) {
										$nama_skpd_all = array();
										$class_cc_opd_lain = '';
										if($valCross['croscutting_opd_lain'] == 1){
											$class_cc_opd_lain = 'cc-opd-lain';
										}
										
										$show_nama_skpd = $valCross['nama_skpd'];
										$label_parent = $valCross['label_parent'];
										if(!empty($valCross['id_level_1_parent']) && $valCross['is_lembaga_lainnya'] != 1){
											$show_nama_skpd = "<a href='" . $view_kinerja_asal['url'] . "&id_skpd=" . $valCross['id_skpd_view_pokin']  . "&id=" . $valCross['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $valCross['nama_skpd'] . "</a>";
										}

										$class_cc_vertikal = '';
										if($valCross['is_lembaga_lainnya'] == 1){
											$label_parent = "?";
											$class_cc_vertikal = "croscutting-lembaga-vertikal";
										}
						                $data_temp[$keylevel4][0]->f.="<div class='croscutting tampil_croscutting ". $class_cc_opd_lain ." ". $class_cc_vertikal ."'><div>". $label_parent ." <a href='javascript:void(0)' data-id='". $valCross['id'] ."' class='detail-cc' onclick='detail_cc(" . $valCross['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a></div><div class='cros-opd'>".$show_nama_skpd."</div></div>";
						            }
					            }

					            if(!empty($level_4['data'])){

									foreach ($level_4['data'] as $keylevel5 => $level_5) {
										$data_temp[$keylevel5][0] = (object)[
										  'v' => $level_5['id'],
										  'f' => "<div class=\"".$style0." label5\">".trim($level_5['label'])."</div>",
										];
		
										if(!empty($level_5['indikator'])){
											foreach ($level_5['indikator'] as $keyindikatorlevel5 => $indikator) {
												$data_temp[$keylevel5][0]->f.="<div ".$style5.">IK: ".$indikator['label_indikator_kinerja']."</div>";
											}
										}

										// croscutting level 5
										if(!empty($level_5['croscutting'])){
											$data_temp[$keylevel5][0]->f.="<div class='croscutting-2 tampil_croscutting'>CROSCUTTING</div>";
											foreach ($level_5['croscutting'] as $keyCross => $valCross) {
												$nama_skpd_all = array();
												$class_cc_opd_lain = '';
												if($valCross['croscutting_opd_lain'] == 1){
													$class_cc_opd_lain = 'cc-opd-lain';
												}
												
												$show_nama_skpd = $valCross['nama_skpd'];
												$label_parent = $valCross['label_parent'];
												if(!empty($valCross['id_level_1_parent']) && $valCross['is_lembaga_lainnya'] != 1){
													$show_nama_skpd = "<a href='" . $view_kinerja_asal['url'] . "&id_skpd=" . $valCross['id_skpd_view_pokin']  . "&id=" . $valCross['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $valCross['nama_skpd'] . "</a>";
												}

												$class_cc_vertikal = '';
												if($valCross['is_lembaga_lainnya'] == 1){
													$label_parent = "?";
													$class_cc_vertikal = "croscutting-lembaga-vertikal";
												}
												$data_temp[$keylevel5][0]->f.="<div class='croscutting tampil_croscutting ". $class_cc_opd_lain ." ". $class_cc_vertikal ."'><div>". $label_parent ." <a href='javascript:void(0)' data-id='". $valCross['id'] ."' class='detail-cc' onclick='detail_cc(" . $valCross['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a></div><div class='cros-opd'>".$show_nama_skpd."</div></div>";
											}
										}
		
										$data_temp[$keylevel5][1] = $level_4['id'];
										$data_temp[$keylevel5][2] = $level_5['id'];
									}
								}

					            $data_temp[$keylevel4][1] = $level_3['id'];
					            $data_temp[$keylevel4][2] = '';
					        }
			            }

			            $data_temp[$keylevel3][1] = $level_2['id'];
			            $data_temp[$keylevel3][2] = '';
			        }
		        }

			    $data_temp[$keylevel2][1] = $level_1['id'];
			    $data_temp[$keylevel2][2] = '';
			}
		}

		$data_temp[$keylevel1][1] = '';
		$data_temp[$keylevel1][2] = '';
	}
}

// echo '<pre>'; print_r($data_temp); echo '</pre>';die();

?>

<style type="text/css">
  	.google-visualization-orgchart-node{
    	border-radius: 5px;
    	border:0;
    	padding: 0;
    	vertical-align: top;
  	}
  	#chart_div .google-visualization-orgchart-connrow-medium{
    	height: 20px;
  	}
  	#chart_div .google-visualization-orgchart-linebottom {
    	border-bottom: 4px solid #f84d4d;
  	}

  	#chart_div .google-visualization-orgchart-lineleft {
    	border-left: 4px solid #f84d4d;
  	}

  	#chart_div .google-visualization-orgchart-lineright {
    	border-right: 4px solid #f84d4d;
  	}

  	#chart_div .google-visualization-orgchart-linetop {
    	border-top: 4px solid #f84d4d;
  	}
  	.level0 {
  		color:#0d0909;
  		font-size:13px;
  		font-weight:600;
  		padding:10px;
  		min-height:80px;
  		min-width: 200px;
  	}
  	.label1 {
  		background: #efd655; 
  		border-radius: 5px 5px 0 0;
  	}
  	.level1 {
  		color: #0d0909; 
  		font-size:11px; 
  		font-weight:600;
  		font-style:italic; 
  		padding:10px; 
  		min-height:70px;
  	}
  	.label2 {
  		background: #fe7373; 
  		border-radius: 5px 5px 0 0;
  	}
  	.level2 {
  		color:#0d0909; 
  		font-size:11px; 
  		font-weight:600; 
  		font-style:italic;
  		padding:10px; 
  		min-height:70px;
  	}
  	.label3 {
  		background: #57b2ec; 
  		border-radius: 5px 5px 0 0;
  	}
  	.level3 {
  		color: #0d0909; 
  		font-size:11px; 
  		font-weight:600;
  		font-style:italic; 
  		padding:10px; 
  		min-height:70px;
  	}
  	.label4 {
  		background: #c979e3; 
  		border-radius: 5px 5px 0 0;
  	}
  	.level4 {
  		color: #0d0909; 
  		font-size:11px; 
  		font-weight:600;
  		font-style:italic; 
  		padding:10px; 
  		min-height:70px;
  	}
	.label5 {
  		background: #28a745; 
  		border-radius: 5px 5px 0 0;
  	}
  	.level5 {
  		color: #0d0909; 
  		font-size:11px; 
  		font-weight:600;
  		font-style:italic; 
  		padding:10px; 
  		min-height:70px;
  	}
  	#action-sakip {
  		padding-top: 20px;
  	}
  	@media print {
  		#cetak {
  			max-width: auto !important;
  			height: auto !important;
  		}
  		@page {size: landscape;}
  		#action-sakip, .site-header, .site-footer {
  			display: none;
  		}

		.detail-cc {
			display: none;
		}

		.tampil_croscutting a {
			text-decoration: none !important;
			color: #0d0909 !important;
		}
  	}
  	#val-range {
  		width: 80px;
	    height: 35px;
	    text-align: center;
	    font-size: 20px;
	    padding: 0;
	    vertical-align: middle;
	    font-weight: bold;
	    margin-top: 20px;
  	}
	.croscutting {
		color: #0d0909; 
  		font-size:12px; 
  		font-weight:600;
  		font-style:italic; 
  		padding:10px; 
  		min-height:70px;
		background: #FFC6FF;
	}

	.croscutting .cros-opd {
		margin: 0px -10px;
		font-size: 12px;
		font-style: normal;
	}

	.croscutting.cc-opd-lain {
		background-color: #9BF6FF;
	}

	.croscutting-2 {
		color: #0d0909; 
  		font-size:13px; 
  		font-weight:600; 
  		padding:10px; 
		background: #FFC6FF;
	}

	.croscutting-lembaga-vertikal{
		background-color: #f1b82a;
	}

	.tampil_croscutting {
		display: none;
	}

	.detail_crocutting{
		background-color: #f4f6f8 !important;
		color: #111827 !important;
	}

	.detail-cc .dashicons{
		text-decoration: none; 
		color: black;
		vertical-align: text-bottom !important;
	}
</style>
<div class="text-center" id="action-sakip">
	<button class="btn btn-primary btn-large" onclick="window.print();"><i class="dashicons dashicons-printer"></i> Cetak / Print</button><br>
	<?php if($tipe == 'opd'): ?>
		<div class="custom-control custom-checkbox mt-4">
			<input type="checkbox" class="custom-control-input" id="show_croscutting">
			<label class="custom-control-label" for="show_croscutting">Tampilkan Croscutting</label>
		</div>
	<?php endif; ?>
	Perkecil (-) <input title="Perbesar/Perkecil Layar" id="test" min="1" max="15" value='10' step="1" onchange="showVal(this.value)" type="range" style="max-width: 400px; margin-top: 40px;" /> (+) Perbesar
	<br>
	<textarea id="val-range" disabled>100%</textarea>
</div>
<h1 style="text-align: center; margin-top: 30px; font-weight: bold;">Pohon Kinerja<br><?php echo $nama_skpd.$periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h1><br>
<div id="cetak" title="Laporan Pohon Kinerja" style="padding: 5px; overflow: auto; max-width: 100vw;">
    <div id="chart_div" ></div>
</div>

<!-- Modal detail -->
<div class="modal fade mt-5" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detailLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-detailLabel">Detail Croscutting</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Label Pengusul</label>
						<textarea class="form-control detail_crocutting" id="label-pengusul" rows="3" disabled></textarea>
					</div>
					<div class="form-group">
						<label>Perangkat Daerah Pengusul</label>
						<input class="form-control detail_crocutting" type="text" id="perangkat-pengusul" disabled>
					</div>
					<div class="form-group">
						<label for="">Keterangan Pengusul</label>
						<textarea class="form-control detail_crocutting" id="keterangan-pengusul" rows="3" disabled></textarea>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Label Tujuan</label>
						<textarea class="form-control detail_crocutting" id="label-tujuan" rows="3" disabled></textarea>
					</div>
					<div class="form-group">
						<label>Perangkat Daerah Tujuan</label>
						<input class="form-control detail_crocutting" type="text" id="perangkat-tujuan" disabled>
					</div>
					<div class="form-group">
						<label for="">Keterangan Tujuan</label>
						<textarea class="form-control detail_crocutting" id="keterangan-tujuan" rows="3" disabled></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 d-flex justify-content-center">
					<div class="form-group text-center">
						<label for="">Status</label>
						<button type="button" class="btn btn-success d-block"">Disetujui</button>
					</div>
				</div>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {packages:["orgchart"]});
google.charts.setOnLoadCallback(drawChart);

<?php if($tipe == 'opd'): ?>
function detail_cc(id){
	jQuery("#wrap-loading").show();
	if(id == undefined){
		alert("Id tidak ditemukan")
	}

	jQuery.ajax({
		method:'POST',
		url:esakip.url,
		data:{
			"action": "detail_croscutting_by_id",
			"api_key": esakip.api_key,
			'id': id,
			'tipe_pokin': "opd",
			'id_skpd': <?php echo $id_skpd; ?>
		},
		dataType:'json',
		success:function(response){
			jQuery("#wrap-loading").hide();
			if(response.status){
				jQuery("#label-pengusul").val(response.data_croscutting.label_parent)
				jQuery("#perangkat-pengusul").val(response.data_croscutting.nama_perangkat)
				jQuery("#keterangan-pengusul").val(response.data_croscutting.keterangan)
				jQuery("#perangkat-tujuan").val(response.data_croscutting.nama_perangkat_tujuan)
				jQuery("#label-tujuan").val(response.data_croscutting.label_parent_tujuan)
				jQuery("#keterangan-tujuan").val(response.data_croscutting.keterangan_tujuan)
				jQuery("#modal-detail").modal('show');
			}
		}
	});
};

jQuery("#show_croscutting").on('click', function(){
	if(this.checked) {
		jQuery(".tampil_croscutting").show();
	}else{
		jQuery(".tampil_croscutting").hide();
	}
});
<?php endif; ?>

function drawChart() {
   	window.data_all = <?php echo json_encode(array_values($data_temp)); ?>;
   	console.log(data_all);
  	window.data = new google.visualization.DataTable();
    data.addColumn('string', 'Name');
    data.addColumn('string', 'Manager');
    data.addColumn('string', 'ToolTip');
    data.addRows(data_all);
    data.setRowProperty(2, 'selectedStyle');
   
    // Create the chart.
    window.chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
    // Draw the chart, setting the allowHtml option to true for the tooltips.
    chart.draw(data, {
      'allowHtml':true,
      'allowCollapse': true
    });
}

function setZoom(zoom,el) {
  
  transformOrigin = [0,0];
    el = el || instance.getContainer();
    var p = ["webkit", "moz", "ms", "o"],
        s = "scale(" + zoom + ")",
        oString = (transformOrigin[0] * 100) + "% " + (transformOrigin[1] * 100) + "%";

    for (var i = 0; i < p.length; i++) {
        el.style[p[i] + "Transform"] = s;
        el.style[p[i] + "TransformOrigin"] = oString;
    }

    el.style["transform"] = s;
    el.style["transformOrigin"] = oString;
      
}

function showVal(val){
	jQuery('#val-range').val((val*10)+'%');
	setZoom(val/10, document.getElementById('chart_div'));
}
</script>