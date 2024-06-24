<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

$input = shortcode_atts(array(
	'periode' => '',
), $atts);
if(!empty($_GET) && !empty($_GET['id_jadwal'])){
	$input['periode'] = $_GET['id_jadwal'];
}

if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

global $wpdb;

$data_all = [
	'data' => []
];

$periode = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
		*
    FROM esakip_data_jadwal
    WHERE id=%d
", $input['periode']),
    ARRAY_A
);

if(empty($periode)){
	die('<h1 class="text-center">Jadwal periode RPJMD/RPD tidak ditemukan!</h1>');
}

if(!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1){
    $tahun_periode = $periode['tahun_selesai_anggaran'];
}else{
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

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

// pokin level 1
$pohon_kinerja_level_1 = $wpdb->get_results($wpdb->prepare("
	SELECT 
		* 
	FROM esakip_pohon_kinerja_opd 
	WHERE id=%d 
		AND parent=0 
		AND level=1 
		AND active=1 
		AND id_jadwal=%d 
		AND id_skpd=%d 
	ORDER BY id
", $_GET['id'], $input['periode'], $id_skpd), ARRAY_A);
if(!empty($pohon_kinerja_level_1)){
	foreach ($pohon_kinerja_level_1 as $level_1) {
		if(empty($data_all['data'][trim($level_1['label'])])){
			$data_all['data'][trim($level_1['label'])] = [
				'id' => $level_1['id'],
				'label' => $level_1['label'],
				'level' => $level_1['level'],
				'indikator' => [],
				'data' => []
			];
		}

		// indikator pokin level 1
		$indikator_pohon_kinerja_level_1 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_pohon_kinerja_opd 
			WHERE parent=%d 
				AND level=1 
				AND active=1 
				AND id_jadwal=%d 
				AND id_skpd=%d 
			ORDER BY id
		", $level_1['id'], $input['periode'], $id_skpd), ARRAY_A);
		if(!empty($indikator_pohon_kinerja_level_1)){
			foreach ($indikator_pohon_kinerja_level_1 as $indikator_level_1) {
				if(!empty($indikator_level_1['label_indikator_kinerja'])){
					if(empty($data_all['data'][trim($level_1['label'])]['indikator'][(trim($indikator_level_1['label_indikator_kinerja']))])){
						$data_all['data'][trim($level_1['label'])]['indikator'][(trim($indikator_level_1['label_indikator_kinerja']))] = [
							'id' => $indikator_level_1['id'],
							'parent' => $indikator_level_1['parent'],
							'label_indikator_kinerja' => $indikator_level_1['label_indikator_kinerja'],
							'level' => $indikator_level_1['level']
						];
					}
				}
			}
		}

		// pokin level 2 
		$pohon_kinerja_level_2 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_pohon_kinerja_opd 
			WHERE parent=%d 
				AND level=2 
				AND active=1 
				AND id_jadwal=%d 
				AND id_skpd=%d 
			ORDER by id
		", $level_1['id'], $input['periode'], $id_skpd), ARRAY_A);
		if(!empty($pohon_kinerja_level_2)){
			foreach ($pohon_kinerja_level_2 as $level_2) {
				if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])])){
					$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])] = [
						'id' => $level_2['id'],
						'label' => $level_2['label'],
						'level' => $level_2['level'],
						'indikator' => [],
						'data' => []
					];
				}

				// indikator pokin level 2
				$indikator_pohon_kinerja_level_2 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_pohon_kinerja_opd 
					WHERE parent=%d 
						AND level=2 
						AND active=1 
						AND id_jadwal=%d 
					ORDER BY id
				", $level_2['id'], $input['periode'], $id_skpd), ARRAY_A);
				if(!empty($indikator_pohon_kinerja_level_2)){
					foreach ($indikator_pohon_kinerja_level_2 as $indikator_level_2) {
						if(!empty($indikator_level_2['label_indikator_kinerja'])){
							if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['indikator'][(trim($indikator_level_2['label_indikator_kinerja']))])){
								$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['indikator'][(trim($indikator_level_2['label_indikator_kinerja']))] = [
									'id' => $indikator_level_2['id'],
									'parent' => $indikator_level_2['parent'],
									'label_indikator_kinerja' => $indikator_level_2['label_indikator_kinerja'],
									'level' => $indikator_level_2['level']
								];
							}
						}
					}
				}

				// pokin level 3
				$pohon_kinerja_level_3 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_pohon_kinerja_opd 
					WHERE parent=%d 
						AND level=3 
						AND active=1 
						AND id_jadwal=%d 
						AND id_skpd=%d 
					ORDER by id
				", $level_2['id'], $input['periode'], $id_skpd), ARRAY_A);
				if(!empty($pohon_kinerja_level_3)){
					foreach ($pohon_kinerja_level_3 as $level_3) {
						if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])])){
							$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])] = [
								'id' => $level_3['id'],
								'label' => $level_3['label'],
								'level' => $level_3['level'],
								'indikator' => [],
								'data' => []
							];
						}

						// indikator pokin level 3
						$indikator_pohon_kinerja_level_3 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pohon_kinerja_opd 
							WHERE parent=%d 
								AND level=3 
								AND active=1 
								AND id_jadwal=%d 
								AND id_skpd=%d 
							ORDER BY id
						", $level_3['id'], $input['periode'], $id_skpd), ARRAY_A);
						if(!empty($indikator_pohon_kinerja_level_3)){
							foreach ($indikator_pohon_kinerja_level_3 as $indikator_level_3) {
								if(!empty($indikator_level_3['label_indikator_kinerja'])){
									if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['indikator'][(trim($indikator_level_3['label_indikator_kinerja']))])){
										$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['indikator'][(trim($indikator_level_3['label_indikator_kinerja']))] = [
											'id' => $indikator_level_3['id'],
											'parent' => $indikator_level_3['parent'],
											'label_indikator_kinerja' => $indikator_level_3['label_indikator_kinerja'],
											'level' => $indikator_level_3['level']
										];
									}
								}
							}
						}

						// pokin level 4
						$pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pohon_kinerja_opd 
							WHERE parent=%d 
								AND level=4 
								AND active=1
								AND id_jadwal=%d 
								AND id_skpd=%d 
							ORDER by id
						", $level_3['id'], $input['periode'], $id_skpd), ARRAY_A);
						if(!empty($pohon_kinerja_level_4)){
							foreach ($pohon_kinerja_level_4 as $level_4) {
								if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])])){
									$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])] = [
										'id' => $level_4['id'],
										'label' => $level_4['label'],
										'level' => $level_4['level'],
										'indikator' => [],
										'data' => []
									];
								}

								// indikator pokin level 4
								$indikator_pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
									SELECT 
										* 
									FROM esakip_pohon_kinerja_opd 
									WHERE parent=%d 
										AND level=4 
										AND active=1 
										AND id_jadwal=%d 
										AND id_skpd=%d 
									ORDER BY id
								", $level_4['id'], $input['periode'], $id_skpd), ARRAY_A);
								if(!empty($indikator_pohon_kinerja_level_4)){
									foreach ($indikator_pohon_kinerja_level_4 as $indikator_level_4) {
										if(!empty($indikator_level_4['label_indikator_kinerja'])){
											if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['indikator'][(trim($indikator_level_4['label_indikator_kinerja']))])){
												$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['indikator'][(trim($indikator_level_4['label_indikator_kinerja']))] = [
													'id' => $indikator_level_4['id'],
													'parent' => $indikator_level_4['parent'],
													'label_indikator_kinerja' => $indikator_level_4['label_indikator_kinerja'],
													'level' => $indikator_level_4['level']
												];
											}
										}
									}
								}

								// pokin level 5
								$pohon_kinerja_level_5 = $wpdb->get_results($wpdb->prepare("
									SELECT 
										* 
									FROM esakip_pohon_kinerja_opd 
									WHERE parent=%d 
										AND level=5 
										AND active=1
										AND id_jadwal=%d 
										AND id_skpd=%d 
									ORDER by id
								", $level_4['id'], $input['periode'], $id_skpd), ARRAY_A);
								if(!empty($pohon_kinerja_level_5)){
									foreach ($pohon_kinerja_level_5 as $level_5) {
										if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['data'][trim($level_5['label'])])){
											$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['data'][trim($level_5['label'])] = [
												'id' => $level_5['id'],
												'label' => $level_5['label'],
												'level' => $level_5['level'],
												'indikator' => []
											];
										}

										// indikator pokin level 5
										$indikator_pohon_kinerja_level_5 = $wpdb->get_results($wpdb->prepare("
											SELECT 
												* 
											FROM esakip_pohon_kinerja_opd 
											WHERE parent=%d 
												AND level=5 
												AND active=1 
												AND id_jadwal=%d 
												AND id_skpd=%d 
											ORDER BY id
										", $level_5['id'], $input['periode'], $id_skpd), ARRAY_A);
										if(!empty($indikator_pohon_kinerja_level_5)){
											foreach ($indikator_pohon_kinerja_level_5 as $indikator_level_5) {
												if(!empty($indikator_level_5['label_indikator_kinerja'])){
													if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['data'][trim($level_5['label'])]['indikator'][(trim($indikator_level_5['label_indikator_kinerja']))])){
														$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['data'][trim($level_5['label'])]['indikator'][(trim($indikator_level_5['label_indikator_kinerja']))] = [
															'id' => $indikator_level_5['id'],
															'parent' => $indikator_level_5['parent'],
															'label_indikator_kinerja' => $indikator_level_5['label_indikator_kinerja'],
															'level' => $indikator_level_5['level']
														];
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

$style0 = 'level0';
$style1 = 'class="level1"';
$style2 = 'class="level2"';
$style3 = 'class="level3"';
$style4 = 'class="level4"';
$style5 = 'class="level5"';

$data_temp= [];
if(!empty($data_all['data'])){

	foreach ($data_all['data'] as $keylevel1 => $level_1) {
		$data_temp[$keylevel1][0] = (object)[
	      'v' => $level_1['label'],
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
		          	'v' => $level_2['label'],
		          	'f' => "<div class=\"".$style0." label2\">".trim($level_2['label'])."</div>",
		        ];

		        if(!empty($level_2['indikator'])){
			        foreach ($level_2['indikator'] as $keyindikatorlevel2 => $indikator) {
			            $data_temp[$keylevel2][0]->f.="<div ".$style2.">IK: ".$indikator['label_indikator_kinerja']."</div>";
			        }
		        }

		        if(!empty($level_2['data'])){

			        foreach ($level_2['data'] as $keylevel3 => $level_3) {
			            $data_temp[$keylevel3][0] = (object)[
			              'v' => $level_3['label'],
			              'f' => "<div class=\"".$style0." label3\">".trim($level_3['label'])."</div>",
			            ];

			            if(!empty($level_3['indikator'])){
				            foreach ($level_3['indikator'] as $keyindikatorlevel3 => $indikator) {
				                $data_temp[$keylevel3][0]->f.="<div ".$style3.">IK: ".$indikator['label_indikator_kinerja']."</div>";
				            }
			            }

			            $data_temp[$keylevel3][1] = $level_2['label'];
			            $data_temp[$keylevel3][2] = $level_3['label'];

			            if(!empty($level_3['data'])){

		            		foreach ($level_3['data'] as $keylevel4 => $level_4) {
			            		$data_temp[$keylevel4][0] = (object)[
					              'v' => $level_4['label'],
					              'f' => "<div class=\"".$style0." label4\">".trim($level_4['label'])."</div>",
					            ];

					            if(!empty($level_4['indikator'])){
				            		foreach ($level_4['indikator'] as $keyindikatorlevel4 => $indikator) {
						                $data_temp[$keylevel4][0]->f.="<div ".$style4.">IK: ".$indikator['label_indikator_kinerja']."</div>";
						            }
					            }

					            $data_temp[$keylevel4][1] = $level_3['label'];
					            $data_temp[$keylevel4][2] = $level_4['label'];

								if(!empty($level_4['data'])){

									foreach ($level_4['data'] as $keylevel5 => $level_5) {
										$data_temp[$keylevel5][0] = (object)[
										  'v' => $level_5['label'],
										  'f' => "<div class=\"".$style0." label5\">".trim($level_5['label'])."</div>",
										];
		
										if(!empty($level_5['indikator'])){
											foreach ($level_5['indikator'] as $keyindikatorlevel5 => $indikator) {
												$data_temp[$keylevel5][0]->f.="<div ".$style5.">IK: ".$indikator['label_indikator_kinerja']."</div>";
											}
										}
		
										$data_temp[$keylevel5][1] = $level_4['label'];
										$data_temp[$keylevel5][2] = $level_5['label'];
									}
								}
					        }
			            }
			        }
		        }

			    $data_temp[$keylevel2][1] = $level_1['label'];
			    $data_temp[$keylevel2][2] = $level_2['label'];
			}
		}

		$data_temp[$keylevel1][1] = '';
		$data_temp[$keylevel1][2] = $level_1['label'];
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
  		min-width: 250px;
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
  		background: #c979e3; 
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
</style>

<h4 style="text-align: center; margin: 0; font-weight: bold;">Pohon Kinerja <br><?php echo $skpd['nama_skpd'] ?><br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h4><br>
<div id="cetak" title="Laporan Pohon Kinerja" style="padding: 5px; overflow: auto; height: 100vh;">
    <div id="chart_div" ></div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    google.charts.load('current', {packages:["orgchart"]});
    google.charts.setOnLoadCallback(drawChart);

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
</script>