<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

$input = shortcode_atts(array(
  	'tahun_anggaran' => '2024',
	'periode' => '',
), $atts);

if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

global $wpdb;
$data_all = [
	'data' => array()
];

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

$unit_croscutting = $wpdb->get_results(
	$wpdb->prepare("
		SELECT 
			nama_skpd, 
			id_skpd, 
			kode_skpd, 
			nipkepala,
			tahun_anggaran 
		FROM esakip_data_unit 
		WHERE active=1 
		AND is_skpd=1 
		AND id_skpd!=%d
		AND tahun_anggaran=%d
		GROUP BY id_skpd
		ORDER BY kode_skpd ASC
	", $id_skpd, $tahun_anggaran_sakip),
	ARRAY_A
);

$option_skpd = "<option value='seluruh'>Pilih Perangkat Daerah</option>";
if(!empty($unit_croscutting)){
	foreach ($unit_croscutting as $v_unit) {
		$option_skpd .="<option value='" . $v_unit['id_skpd'] . "'>" . $v_unit['nama_skpd'] . "</option>";
	}
}

// pokin level 1
$pohon_kinerja_level_1 = $wpdb->get_results($wpdb->prepare("
	SELECT 
		* 
	FROM esakip_pohon_kinerja_opd 
	WHERE parent=0 
		AND level=1 
		AND active=1 
		AND id_jadwal=%d 
		AND id_skpd=%d
	ORDER BY id
", $input['periode'], $id_skpd), ARRAY_A);
if(!empty($pohon_kinerja_level_1)){
	foreach ($pohon_kinerja_level_1 as $level_1) {
		if(empty($data_all['data'][trim($level_1['label'])])){
			$data_all['data'][trim($level_1['label'])] = [
				'id' => $level_1['id'],
				'label' => $level_1['label'],
				'level' => $level_1['level'],
				'indikator' => array(),
				'data' => array()
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
						'indikator' => array(),
						'data' => array()
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
						AND id_skpd=%d
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
								'indikator' => array(),
								'data' => array()
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
										'indikator' => array(),
										'data' => array(),
										'croscutting' => array()
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
								
								// croscutting pokin level 4
								$croscutting_pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
									SELECT 
										* 
									FROM esakip_croscutting_opd 
									WHERE parent_pohon_kinerja=%d 
										AND active=1 
									ORDER BY id
								", $level_4['id']), ARRAY_A);
								if(!empty($croscutting_pohon_kinerja_level_4)){
									foreach ($croscutting_pohon_kinerja_level_4 as $key_croscutting_level_4 => $croscutting_level_4) {
										$nama_skpd = $wpdb->get_row(
											$wpdb->prepare("
												SELECT 
													nama_skpd,
													id_skpd,
													tahun_anggaran
												FROM esakip_data_unit 
												WHERE active=1 
												AND is_skpd=1 
												AND id_skpd=%d
												AND tahun_anggaran=%d
												GROUP BY id_skpd
												ORDER BY kode_skpd ASC
											", $croscutting_level_4['id_skpd_croscutting'], $tahun_anggaran_sakip),
											ARRAY_A
										);

										if(!empty($croscutting_level_4['keterangan'])){
											if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['croscutting'][(trim($croscutting_level_4['keterangan']))])){
												$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['croscutting'][(trim($croscutting_level_4['keterangan']))] = [
													'id' => $croscutting_level_4['id'],
													'keterangan' => $croscutting_level_4['keterangan'],
													'data' => array()
												];
											}

											$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['croscutting'][(trim($croscutting_level_4['keterangan']))]['data'][$key_croscutting_level_4] = [
												'id' => $croscutting_level_4['id'],
												'parent_pohon_kinerja' => $croscutting_level_4['parent_pohon_kinerja'],
												'keterangan' => $croscutting_level_4['keterangan'],
												'nama_skpd' => $nama_skpd['nama_skpd'],
												'status_croscutting' => $croscutting_level_4['status_croscutting']
											];
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
												'indikator' => array(),
												'croscutting' => array()
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

										// croscutting pokin level 5
										$croscutting_pohon_kinerja_level_5 = $wpdb->get_results($wpdb->prepare("
											SELECT 
												* 
											FROM esakip_croscutting_opd 
											WHERE parent_pohon_kinerja=%d 
												AND active=1 
											ORDER BY id
										", $level_5['id']), ARRAY_A);
										if(!empty($croscutting_pohon_kinerja_level_5)){
											foreach ($croscutting_pohon_kinerja_level_5 as $key_croscutting_level_5 => $croscutting_level_5) {
												$nama_skpd = $wpdb->get_row(
													$wpdb->prepare("
														SELECT 
															nama_skpd,
															id_skpd,
															tahun_anggaran
														FROM esakip_data_unit 
														WHERE active=1 
														AND is_skpd=1 
														AND id_skpd=%d
														AND tahun_anggaran=%d
														GROUP BY id_skpd
														ORDER BY kode_skpd ASC
													", $croscutting_level_5['id_skpd_croscutting'], $tahun_anggaran_sakip),
													ARRAY_A
												);

												if(!empty($croscutting_level_5['keterangan'])){
													if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['data'][trim($level_5['label'])]['croscutting'][(trim($croscutting_level_5['keterangan']))])){
														$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['data'][trim($level_5['label'])]['croscutting'][(trim($croscutting_level_5['keterangan']))] = [
															'id' => $croscutting_level_5['id'],
															'keterangan' => $croscutting_level_5['keterangan'],
															'data' => array()
														];
													}

													$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['data'][trim($level_5['label'])]['croscutting'][(trim($croscutting_level_5['keterangan']))]['data'][$key_croscutting_level_5] = [
														'id' => $croscutting_level_5['id'],
														'parent_pohon_kinerja' => $croscutting_level_5['parent_pohon_kinerja'],
														'keterangan' => $croscutting_level_5['keterangan'],
														'nama_skpd' => $nama_skpd['nama_skpd'],
														'status_croscutting' => $croscutting_level_5['status_croscutting']
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

$view_kinerja = $this->functions->generatePage(array(
	'nama_page' => 'View Pohon Kinerja OPD'.$skpd['nama_skpd'],
	'content' => '[view_pohon_kinerja_opd periode='. $input['periode'] .']',
	'show_header' => 1,
	'post_status' => 'private'
));
$view_kinerja['url'] .= '&id_skpd=' . $id_skpd;
$html = '';
foreach ($data_all['data'] as $key1 => $level_1) {
	$indikator=array();
	foreach ($level_1['indikator'] as $indikatorlevel1) {
		$indikator[]=$indikatorlevel1['label_indikator_kinerja'];
	}
	$html.='
	<tr>
		<td class="level1"><a href="'.$view_kinerja['url'].'&id='.$level_1['id'].'&id_jadwal='.$input['periode'].'" target="_blank">'.$level_1['label'].'</a></td>
		<td class="indikator">'.implode("</br>", $indikator).'</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
    <td></td>
    <td></td>
	</tr>';
	foreach (array_values($level_1['data']) as $key2 => $level_2) {
		$indikator=array();
		foreach ($level_2['indikator'] as $indikatorlevel2) {
			$indikator[]=$indikatorlevel2['label_indikator_kinerja'];
		}
		$html.='
		<tr>
			<td></td>
			<td></td>
			<td class="level2">'.$level_2['label'].'</td>
			<td class="indikator">'.implode("</br>", $indikator).'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>';
		foreach (array_values($level_2['data']) as $key3 => $level_3) {
			$indikator=array();
			foreach ($level_3['indikator'] as $indikatorlevel3) {
				$indikator[]=$indikatorlevel3['label_indikator_kinerja'];
			}
			$html.='
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="level3">'.$level_3['label'].'</td>
				<td class="indikator">'.implode("</br>", $indikator).'</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';
			foreach (array_values($level_3['data']) as $key4 => $level_4) {
				$indikator=array();
				foreach ($level_4['indikator'] as $indikatorlevel4) {
					$indikator[]=$indikatorlevel4['label_indikator_kinerja'];
				}
				$croscutting = array();
				foreach ($level_4['croscutting'] as $croscuttinglevel4) {
					$nama_skpd_all = array();
					foreach ($croscuttinglevel4['data'] as $k_cross_4 => $v_cross_4) {
						switch ($v_cross_4['status_croscutting']) {
							case '1':
								$status_croscutting = 'Disetujui';
								$label_color = 'success text-white';
								break;
							case '2':
								$status_croscutting = 'Ditolak';
								$label_color = 'danger text-white';
								break;
							
							default:
								$status_croscutting = 'Menunggu';
								$label_color = 'secondary text-white';
								break;
						}
						$nama_skpd_all[] = $v_cross_4['nama_skpd'] . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_croscutting.'</span> ';
					}
					$croscutting[]= '<div>'. ucfirst($croscuttinglevel4['keterangan']) ."</div><div style='margin-top: 10px;font-weight: 500;'>". implode(', ', $nama_skpd_all) .'</div>';
				}
				
				$show_croscutting = '';
				if(!empty($croscutting)){
					$show_croscutting .='<div class="text-center label-croscutting">CROSCUTTING</div>';
					$show_croscutting .='<div class="croscutting-1">'.implode('</div><div class="croscutting-2">', $croscutting).'</div>';
				}
				$html.='
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="level4">' . $level_4['label'] . '</td>
					<td class="indikator">'.implode("</br>", $indikator).'</td>
					<td></td>
					<td></td>
				</tr>';
				
				if(!empty($show_croscutting)){
					$html.='
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="croscutting" colspan="2">' . $show_croscutting . '</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>';
				}
				
				foreach (array_values($level_4['data']) as $key5 => $level_5) {
					$indikator=array();
					foreach ($level_5['indikator'] as $indikatorlevel5) {
						$indikator[]=$indikatorlevel5['label_indikator_kinerja'];
					}
					$html.='
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="level5">'.$level_5['label'].'</td>
						<td class="indikator">'.implode("</br>", $indikator).'</td>
					</tr>';

					// show croscutting
					$croscutting5 = array();
					foreach ($level_5['croscutting'] as $croscuttinglevel5) {
						$nama_skpd_all = array();
						foreach ($croscuttinglevel5['data'] as $k_cross_5 => $v_cross_5) {
							switch ($v_cross_5['status_croscutting']) {
								case '1':
									$status_croscutting = 'Disetujui';
									$label_color = 'success text-white';
									break;
								case '2':
									$status_croscutting = 'Ditolak';
									$label_color = 'danger text-white';
									break;
								
								default:
									$status_croscutting = 'Menunggu';
									$label_color = 'secondary text-white';
									break;
							}
							$nama_skpd_all[] = $v_cross_5['nama_skpd'] . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_croscutting.'</span> ';
						}
						$croscutting5[]= '<div>'. ucfirst($croscuttinglevel5['keterangan']) ."</div><div style='margin-top: 10px;font-weight: 500;'>". implode(', ', $nama_skpd_all) .'</div>';
					}

					$show_croscutting5 = '';
					if(!empty($croscutting5)){
						$show_croscutting5 .='<div class="text-center label-croscutting">CROSCUTTING</div>';
						$show_croscutting5 .='<div class="croscutting-1">'.implode('</div><div class="croscutting-2">', $croscutting5).'</div>';
					}

					if(!empty($show_croscutting5)){
						$html.='
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="croscutting" colspan="2">' . $show_croscutting5 . '</td>
							<td></td>
						</tr>';
					}

				}
			}
		}
	}
}

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
?>

<style type="text/css">
	.level1 {
		background: #efd655;
		text-decoration: underline;
	}
	.level2 {
		background: #fe7373;
	}
	.level3 {
		background: #57b2ec;
	}
	.level4 {
		background: #c979e3;
	}
  .level5 {
		background: #CAFFBF;
	}
	.indikator {
		background: #b5d9ea;
	}

	.croscutting {
		background-color: #FFC6FF;
	}

	.label-croscutting {
		padding: 0 .7em .7em;
	}
	.croscutting-1 {
		margin: 0px -16px 0px;
		padding:  .5em .9em;
		border-width: 1px 0 0;
		border-style: solid;
		border-color: gray;
	}
	.croscutting-2 {
		margin: 0px -16px;
		padding: .5em .9em;
		border-width: 1px 0 0;
		border-style: solid;
		border-color: gray;
	}
</style>
<h3 style="text-align: center; margin-top: 10px; font-weight: bold;">Penyusunan Pohon Kinerja <br><?php echo $skpd['nama_skpd'] ?><br><?php echo $periode['nama_jadwal_renstra'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h3><br>
<?php if (!$is_admin_panrb): ?>
<div id="action" style="text-align: center; margin-top:30px; margin-bottom: 30px;">
		<a style="margin-left: 10px;" id="tambah-pohon-kinerja" onclick="return false;" href="#" class="btn btn-success">Tambah Data</a>
</div>
<?php endif; ?>
<div id="cetak" title="Penyusunan Pohon Kinerja" style="padding: 5px; overflow: auto; height: 100vh;">
	<table>
		<thead>
			<tr>
				<th>Level 1</th>
				<th>Indikator Kinerja</th>
				<th>Level 2</th>
				<th>Indikator Kinerja</th>
				<th>Level 3</th>
				<th>Indikator Kinerja</th>
				<th>Level 4</th>
				<th>Indikator Kinerja</th>
				<th>Level 5</th>
				<th>Indikator Kinerja</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $html; ?>
		</tbody>
	</table>
	
	<div class="hide-print" id="catatan_dokumentasi" style="max-width: 1200px; margin: auto;">
		<h4 style="margin: 30px 0 10px; font-weight: bold;">Catatan Dokumentasi:</h4>
		<ul>
			<li>Pohon kinerja bisa dilihat ketika data terisi minimal sampai dengan level ke-2.</li>
		</ul>
	</div>
</div>

<div class="modal fade" id="modal-pokin" role="dialog" data-backdrop="static" aria-hidden="true">'
    <div class="modal-dialog" style="max-width: 1200px;" role="document">
        <div class="modal-content">
            <div class="modal-header bgpanel-theme">
                <h4 style="margin: 0;" class="modal-title">Data Pohon Kinerja</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span><i class="dashicons dashicons-dismiss"></i></span></button>
            </div>
            <div class="modal-body">
            	<nav>
				  	<div class="nav nav-tabs" id="nav-tab" role="tablist">
					    <a class="nav-item nav-link" id="nav-level-1-tab" data-toggle="tab" href="#nav-level-1" role="tab" aria-controls="nav-level-1" aria-selected="false">Level 1</a>
					    <a class="nav-item nav-link" id="nav-level-2-tab" data-toggle="tab" href="#nav-level-2" role="tab" aria-controls="nav-level-2" aria-selected="false">Level 2</a>
					    <a class="nav-item nav-link" id="nav-level-3-tab" data-toggle="tab" href="#nav-level-3" role="tab" aria-controls="nav-level-3" aria-selected="false">Level 3</a>
					    <a class="nav-item nav-link" id="nav-level-4-tab" data-toggle="tab" href="#nav-level-4" role="tab" aria-controls="nav-level-4" aria-selected="false">Level 4</a>
					    <a class="nav-item nav-link" id="nav-level-5-tab" data-toggle="tab" href="#nav-level-5" role="tab" aria-controls="nav-level-5" aria-selected="false">Level 5</a>
				  	</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
				  	<div class="tab-pane fade show active" id="nav-level-1" role="tabpanel" aria-labelledby="nav-level-1-tab"></div>
				  	<div class="tab-pane fade" id="nav-level-2" role="tabpanel" aria-labelledby="nav-level-2-tab"></div>
				  	<div class="tab-pane fade" id="nav-level-3" role="tabpanel" aria-labelledby="nav-level-3-tab"></div>
				  	<div class="tab-pane fade" id="nav-level-4" role="tabpanel" aria-labelledby="nav-level-4-tab"></div>
				  	<div class="tab-pane fade" id="nav-level-5" role="tabpanel" aria-labelledby="nav-level-5-tab"></div>
				</div>
			</div>
		</div>
    </div>
</div>

<!-- Modal crud -->
<div class="modal fade" id="modal-crud" data-backdrop="static"  role="dialog" aria-labelledby="modal-crud-label" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-scrollable" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
		        <h5 class="modal-title">Modal title</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          	<span aria-hidden="true">&times;</span>
		        </button>
	      	</div>
	      	<div class="modal-body">
	      	</div>
	      	<div class="modal-footer"></div>
    	</div>
  	</div>
</div>

<!-- Modal crud croscutting -->
<div class="modal fade" id="modal-croscutting" data-backdrop="static"  role="dialog" aria-labelledby="modal-croscutting-label" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-scrollable" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
		        <h5 class="modal-title">Modal title</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          	<span aria-hidden="true">&times;</span>
		        </button>
	      	</div>
	      	<div class="modal-body">
	      	</div>
	      	<div class="modal-footer"></div>
    	</div>
  	</div>
</div>

<style>
    #modal-pokin .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }

    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
    }
</style>
<script type="text/javascript">
jQuery(document).ready(function(){

	jQuery("#tambah-pohon-kinerja").on('click', function(){
		pokinLevel1().then(function(){
			jQuery("#pokinLevel1").DataTable();
		});
	});

	jQuery(document).on('click', '#tambah-pokin-level1', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
		jQuery("#modal-crud").find('.modal-body').html(''
			+'<form id="form-pokin">'
				+'<input type="hidden" name="parent" value="0">'
				+'<input type="hidden" name="level" value="1">'
				+'<div class="form-group">'
						+'<textarea class="form-control" name="label" placeholder="Tuliskan pohon kinerja level 1..."></textarea>'
				+'</div>'
			+'</form>');
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel1">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level1', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<textarea class="form-control" name="label">${response.data.label}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel1">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level1', function(){
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':1,
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel1().then(function(){
							jQuery("#pokinLevel1").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level1', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level1').text()}">`
				+`<input type="hidden" name="level" value="1">`
				+`<div class="form-group">`
					+`<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level1').text()}</label>`
					+`<textarea class="form-control" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel1">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-indikator-pokin-level1', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<label for="indikator-label">${response.data.label}</label>`
							+`<textarea class="form-control" name="indikator_label">${response.data.label_indikator_kinerja}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel1">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level1', function(){
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel1().then(function(){
							jQuery("#pokinLevel1").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.view-pokin-level2', function(){
		pokinLevel2({
			'parent':jQuery(this).data('id')
		}).then(function(){
			jQuery("#pokinLevel2").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level2', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="2">`
				+`<div class="form-group">`
						+`<textarea class="form-control" name="label" placeholder="Tuliskan pohon kinerja level 2..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel2">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level2', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<textarea class="form-control" name="label">${response.data.label}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel2">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level2', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':2,
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel2({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel2").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level2', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level2').text()}">`
				+`<input type="hidden" name="level" value="2">`
				+`<div class="form-group">`
					+`<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level2').text()}</label>`
					+`<textarea class="form-control" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel2">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-indikator-pokin-level2', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent_all" value="${response.data.parent_all}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<label for="indikator-label">${response.data.label}</label>`
							+`<textarea class="form-control" name="indikator_label">${response.data.label_indikator_kinerja}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel2">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level2', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel2({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel2").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.view-pokin-level3', function(){
		pokinLevel3({
			'parent':jQuery(this).data('id')
		}).then(function(){
			jQuery("#pokinLevel3").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level3', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="3">`
				+`<div class="form-group">`
						+`<textarea class="form-control" name="label" placeholder="Tuliskan pohon kinerja level 3..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel3">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level3', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<textarea class="form-control" name="label">${response.data.label}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel3">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level3', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':3,
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel3({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel3").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level3', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level3').text()}">`
				+`<input type="hidden" name="level" value="3">`
				+`<div class="form-group">`
					+`<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level3').text()}</label>`
					+`<textarea class="form-control" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel3">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-indikator-pokin-level3', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent_all" value="${response.data.parent_all}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<label for="indikator-label">${response.data.label}</label>`
							+`<textarea class="form-control" name="indikator_label">${response.data.label_indikator_kinerja}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel3">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level3', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel3({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel3").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.view-pokin-level4', function(){
		pokinLevel4({
			'parent':jQuery(this).data('id'),
		}).then(function(){
			jQuery("#pokinLevel4").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level4', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="4">`
				+`<div class="form-group">`
						+`<textarea class="form-control" name="label" placeholder="Tuliskan pohon kinerja level 4..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel4">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level4', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<textarea class="form-control" name="label">${response.data.label}</textarea>`
						+`</div>`
						+`<div class="custom-control custom-checkbox">`
							+`<input type="checkbox" class="custom-control-input" name="settingCroscutting" value="false" id="settingCroscutting">`
							+`<label class="custom-control-label" for="settingCroscutting">Setting Croscutting</label>`
						+`</div>`
						+`<div class="setting-croscutting" style="margin-top:10px">`
							+`<button type="button" data-setting-croscutting="false" data-parent-croscutting="${response.data.id}" class="btn btn-success mb-2" id="tambah-croscuting-level"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
						+`</div>`
						+`<div class="wrap-table setting-croscutting">`
							+`<table id="table_croscutting" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Keterangan Pengusul</th>`
										+`<th class="text-center">Keterangan Tujuan</th>`
										+`<th class="text-center">Perangkat Daerah Tujuan</th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${response.data_croscutting}</tbody>`
							+`</table>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel4">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','800px');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
				
				if(response.data_croscutting == "" || response.data_croscutting == undefined){
					jQuery('#settingCroscutting').prop('checked', false);
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'false');
					jQuery(".setting-croscutting").hide()
				}else{
					jQuery('#settingCroscutting').prop('checked', true);
					jQuery(".setting-croscutting").show()
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'true');
				}
			}
		});
	})

	jQuery(document).on('change', '#settingCroscutting', function(){
        if(this.checked) {
			jQuery(".setting-croscutting").show()
        }else{
			jQuery(".setting-croscutting").hide()
		}
    });

	jQuery(document).on('click', '.hapus-pokin-level4', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':4,
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel4({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel4").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level4', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level4').text()}">`
				+`<input type="hidden" name="level" value="4">`
				+`<div class="form-group">`
					+`<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level4').text()}</label>`
					+`<textarea class="form-control" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel4">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	});

	jQuery(document).on('click', '.edit-indikator-pokin-level4', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent_all" value="${response.data.parent_all}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<label for="indikator-label">${response.data.label}</label>`
							+`<textarea class="form-control" name="indikator_label">${response.data.label_indikator_kinerja}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel4">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level4', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel4({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel4").DataTable();
						});
					}
				}
			})
		}
	});
  
	jQuery(document).on('click', '.view-pokin-level5', function(){
		pokinLevel5({
			'parent':jQuery(this).data('id'),
		}).then(function(){
			jQuery("#pokinLevel5").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level5', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="5">`
				+`<div class="form-group">`
						+`<textarea class="form-control" name="label" placeholder="Tuliskan pohon kinerja level 5..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel5">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level5', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<textarea class="form-control" name="label">${response.data.label}</textarea>`
						+`</div>`
						+`<div class="custom-control custom-checkbox">`
							+`<input type="checkbox" class="custom-control-input" name="settingCroscutting" value="false" id="settingCroscutting">`
							+`<label class="custom-control-label" for="settingCroscutting">Setting Croscutting</label>`
						+`</div>`
						+`<div class="setting-croscutting" style="margin-top:10px">`
							+`<button type="button" data-setting-croscutting="false" data-parent-croscutting="${response.data.id}" class="btn btn-success mb-2" id="tambah-croscuting-level"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
						+`</div>`
						+`<div class="wrap-table setting-croscutting">`
							+`<table id="table_croscutting" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Keterangan Pengusul</th>`
										+`<th class="text-center">Keterangan Tujuan</th>`
										+`<th class="text-center">Perangkat Daerah Tujuan</th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${response.data_croscutting}</tbody>`
							+`</table>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel5">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','800px');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
				
				if(response.data_croscutting == "" || response.data_croscutting == undefined){
					jQuery('#settingCroscutting').prop('checked', false);
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'false');
					jQuery(".setting-croscutting").hide()
				}else{
					jQuery('#settingCroscutting').prop('checked', true);
					jQuery(".setting-croscutting").show()
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'true');
				}
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level5', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
          			'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':5,
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel5({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel5").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level5', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level5').text()}">`
				+`<input type="hidden" name="level" value="5">`
				+`<div class="form-group">`
					+`<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level5').text()}</label>`
					+`<textarea class="form-control" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel5">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	});

	jQuery(document).on('click', '.edit-indikator-pokin-level5', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent_all" value="${response.data.parent_all}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<label for="indikator-label">${response.data.label}</label>`
							+`<textarea class="form-control" name="indikator_label">${response.data.label_indikator_kinerja}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel5">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level5', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
          			'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel5({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel5").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '#simpan-data-pokin', function(){
		jQuery('#wrap-loading').show();
		let modal = jQuery("#modal-crud");
		let action = jQuery(this).data('action');
		let view = jQuery(this).data('view');
		let form = getFormData(jQuery("#form-pokin"));
		form['id_jadwal'] = '<?php echo $input['periode']; ?>';
		
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			dataType:'json',
			data:{
				'action': action,
        		'api_key': esakip.api_key,
				'data': JSON.stringify(form),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			success:function(response){
				jQuery('#wrap-loading').hide();
				alert(response.message);
				if(response.status){
					runFunction(view, [form])
					modal.modal('hide');
				}
			}
		})
	});

	jQuery(document).on('click', '#simpan-data-croscutting', function(){
		jQuery('#wrap-loading').show();
		let modal = jQuery("#modal-croscutting");
		let action = jQuery(this).data('action');
		let view = jQuery(this).data('view');
		let form = getFormData(jQuery("#form-croscutting"));
		form['id_jadwal'] = '<?php echo $input['periode']; ?>';

		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			dataType:'json',
			data:{
				'action': action,
        		'api_key': esakip.api_key,
				'data': JSON.stringify(form),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			success:function(response){
				jQuery('#wrap-loading').hide();
				alert(response.message);
				if(response.status){
					runFunction(view, [form])
					modal.modal('hide');
				}
			}
		})
	});
});

jQuery(document).on('click', '.edit-croscutting', function(){
	jQuery("#wrap-loading").show();
	let id = jQuery(this).data('id');
	jQuery.ajax({
		method:'POST',
		url:esakip.url,
		data:{
			"action": "edit_croscutting",
			"api_key": esakip.api_key,
			'id':id,
			'tipe_pokin': "opd",
			'id_skpd': <?php echo $id_skpd; ?>
		},
		dataType:'json',
		success:function(response){
			let keterangan = '';
			if(response.data_croscutting.keterangan != ''){
				keterangan = response.data_croscutting.keterangan;
			}
			let id_skpd_croscutting = '';
			if(response.data_croscutting.id_skpd_croscutting != ''){
				id_skpd_croscutting = response.data_croscutting.id_skpd_croscutting;
			}
			jQuery("#wrap-loading").hide();
			jQuery("#modal-croscutting").find('.modal-title').html('Edit Croscutting');
			jQuery("#modal-croscutting").find('.modal-body').html(``
				+`<form id="form-croscutting">`				
					+`<input type="hidden" name="id" value="${id}">`
					+`<input type="hidden" name="idParentCroscutting" value="${response.data_croscutting.parent_pohon_kinerja}">`
					+`<div class="form-group" id="showSkpdCroscutting">`
						+`<label for="skpdCroscutting">Pilih Perangkat Daerah</label>`
						+`<select class="form-control" name="skpdCroscutting[]" multiple="multiple" id="skpdCroscutting">`
						+`<?php echo $option_skpd; ?>`
						+`</select>`
					+`</div>`
					+`<div class="form-group" id="showKeteranganCroscutting">`
						+`<label for="keteranganCroscutting">Keterangan Croscutting</label>`
						+`<textarea class="form-control" id="keteranganCroscutting" name="keteranganCroscutting">${keterangan}</textarea>`
					+`</div>`
				+`</form>`);
			// jQuery(`#skpdCroscutting option[value="${id_skpd_croscutting}"]`).prop('selected', true);
			jQuery("#modal-croscutting").find(`.modal-footer`).html(``
				+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
					+`Tutup`
				+`</button>`
				+`<button type="button" class="btn btn-success" id="simpan-data-croscutting" data-action="update_croscutting">`
					+`Update`
				+`</button>`);
			jQuery("#modal-croscutting").find('.modal-dialog').css('maxWidth','');
			jQuery("#modal-croscutting").find('.modal-dialog').css('width','');
			jQuery("#modal-croscutting").modal('show');
			jQuery('#skpdCroscutting').select2({
							width: '100%'
						});
			// let id_skpd = [];
			// id_skpd_croscutting.map(function(value, index){
			// 	id_skpd.push(value.id_label_giat);
			// })
			jQuery('#skpdCroscutting').val(id_skpd_croscutting).trigger('change');
		}
	});
})

jQuery(document).on('click', '#tambah-croscuting-level', function(){
	jQuery("#modal-croscutting").find('.modal-title').html('Tambah croscutting');
	jQuery("#modal-croscutting").find('.modal-body').html(``
		+`<form id="form-croscutting">`				
			+`<input type="hidden" name="parentCroscutting" value="${jQuery(this).data('parent-croscutting')}">`
			+`<input type="hidden" name="settingCroscutting" value="${jQuery(this).data('setting-croscutting')}">`
			+`<div class="form-group" id="showSkpdCroscutting">`
				+`<label for="skpdCroscutting">Pilih Perangkat Daerah</label>`
				+`<select class="form-control" name="skpdCroscutting[]" multiple="multiple" id="skpdCroscutting">`
				+`<?php echo $option_skpd; ?>`
				+`</select>`
			+`</div>`
			+`<div class="form-group" id="showKeteranganCroscutting">`
				+`<label for="keteranganCroscutting">Keterangan Croscutting</label>`
				+`<textarea class="form-control" id="keteranganCroscutting" name="keteranganCroscutting"></textarea>`
			+`</div>`
		+`</form>`);
		jQuery("#modal-croscutting").find('.modal-footer').html(''
		+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
			+'Tutup'
		+'</button>'
		+'<button type="button" class="btn btn-success" id="simpan-data-croscutting" data-action="create_croscutting">'
			+'Simpan'
		+'</button>');
	jQuery("#modal-croscutting").find('.modal-dialog').css('maxWidth','');
	jQuery("#modal-croscutting").find('.modal-dialog').css('width','');
	jQuery("#modal-croscutting").modal('show');
	jQuery('#skpdCroscutting').select2({
							width: '100%'
						});
})

	
jQuery(document).on('click', '.delete-croscutting', function(){
	if(confirm(`Data akan dihapus?`)){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
				'action': 'delete_croscutting',
				'api_key': esakip.api_key,
				'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				alert(response.message);
			}
		})
	}
});

function pokinLevel1(){
	jQuery("#wrap-loading").show();
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 1,
	      		"parent": 0,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key,
            	"tipe_pokin": "opd",
				"id_skpd": <?php echo $id_skpd; ?>
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
          		let level1 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" class="btn btn-success mb-2" id="tambah-pokin-level1"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table" id="pokinLevel1">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:20%">No</th>`
	          					+`<th class="text-center" style="width:60%">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:20%">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
			          			level1 += ``
				          			+`<tr id="pokinLevel1_${value.id}">`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level1">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-success tambah-indikator-pokin-level1" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level2" title="Lihat pohon kinerja level 2"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-primary edit-pokin-level1" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger hapus-pokin-level1" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	let indikator = Object.values(value.indikator);
					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level1 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level1" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-danger hapus-indikator-pokin-level1" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level1+=`<tbody>`
          			+`</table>`;

          		jQuery("#nav-level-1").html(level1);
				jQuery('.nav-tabs a[href="#nav-level-1"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function pokinLevel2(params){
	jQuery("#wrap-loading").show();
	let parent = params.parent_all ?? params.parent;	
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 2,
	      		"parent": parent,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key,
            	"tipe_pokin": "opd",
				"id_skpd": <?php echo $id_skpd; ?>
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
          		let level2 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level2"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table">`
      					+`<thead>`;
      						res.parent.map(function(value, index){
      							if(value!=null){
		          					level2 += ``
				          				+`<tr>`
				          					+`<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>`
				          					+`<th>${value}</th>`
				          				+`</tr>`;
      							}
	          				});
      					level2+=`</thead>`
      				+`</table>`
	          		+`<table class="table" id="pokinLevel2">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:20%">No</th>`
	          					+`<th class="text-center" style="width:60%">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:20%">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
			          			level2 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level2">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level2" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level3" title="Lihat pohon kinerja level 3"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level2" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level2" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	let indikator = Object.values(value.indikator);
					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level2 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level2" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-indikator-pokin-level2" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level2+=`<tbody>`
          			+`</table>`;

				jQuery("#nav-level-2").html(level2);
				jQuery('.nav-tabs a[href="#nav-level-2"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function pokinLevel3(params){
	jQuery("#wrap-loading").show();
	let parent = params.parent_all ?? params.parent;
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 3,
	      		"parent": parent,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key,
            	"tipe_pokin": "opd",
				"id_skpd": <?php echo $id_skpd; ?>
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
          		let level3 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level3"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table">`
      					+`<thead>`;
      						res.parent.map(function(value, index){
      							if(value!=null){
		          					level3 += ``
				          				+`<tr>`
				          					+`<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>`
				          					+`<th>${value}</th>`
				          				+`</tr>`;
      							}
	          				});
      					level3+=`</thead>`
      				+`</table>`
	          		+`<table class="table" id="pokinLevel3">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:20%">No</th>`
	          					+`<th class="text-center" style="width:60%">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:20%">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
			          			level3 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level3">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level3" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level4" title="Lihat pohon kinerja level 4"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level3" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level3" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	let indikator = Object.values(value.indikator);
					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level3 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level3" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-indikator-pokin-level3" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level3+=`<tbody>`
          			+`</table>`;

				jQuery("#nav-level-3").html(level3);
				jQuery('.nav-tabs a[href="#nav-level-3"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function pokinLevel4(params){
	jQuery("#wrap-loading").show();
	let parent = params.parent_all ?? params.parent;
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 4,
	      		"parent": parent,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key,
            	"tipe_pokin": "opd",
				"id_skpd": <?php echo $id_skpd; ?>
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
          		let level4 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level4"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table">`
      					+`<thead>`;
      						res.parent.map(function(value, index){
      							if(value!=null){
		          					level4 += ``
				          				+`<tr>`
				          					+`<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>`
				          					+`<th>${value}</th>`
				          				+`</tr>`;
      							}
	          				});
      					level4+=`</thead>`
      				+`</table>`
	          		+`<table class="table" id="pokinLevel4">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:20%">No</th>`
	          					+`<th class="text-center" style="width:60%">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:20%">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
			          			level4 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level4">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level4" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
											+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level5" title="Lihat pohon kinerja level 5"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level4" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level4" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	let indikator = Object.values(value.indikator);
					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level4 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level4" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-indikator-pokin-level4" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level4+=`<tbody>`
          			+`</table>`;

				jQuery("#nav-level-4").html(level4);
				jQuery('.nav-tabs a[href="#nav-level-4"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function pokinLevel5(params){
	jQuery("#wrap-loading").show();
	let parent = params.parent_all ?? params.parent;
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 5,
	      		"parent": parent,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key,
            	"tipe_pokin": "opd",
				"id_skpd": <?php echo $id_skpd; ?>
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
          		let level5 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level5"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table">`
      					+`<thead>`;
      						res.parent.map(function(value, index){
      							if(value!=null){
		          					level5 += ``
				          				+`<tr>`
				          					+`<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>`
				          					+`<th>${value}</th>`
				          				+`</tr>`;
      							}
	          				});
      					level5+=`</thead>`
      				+`</table>`
	          		+`<table class="table" id="pokinLevel5">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:20%">No</th>`
	          					+`<th class="text-center" style="width:60%">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:20%">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
			          			level5 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level5">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level5" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level5" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level5" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	let indikator = Object.values(value.indikator);
					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level5 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level5" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-indikator-pokin-level5" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level5+=`<tbody>`
          			+`</table>`;

				jQuery("#nav-level-5").html(level5);
				jQuery('.nav-tabs a[href="#nav-level-5"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function runFunction(name, arguments){
    var fn = window[name];
    if(typeof fn !== 'function')
        return;

    var run = fn.apply(window, arguments);
    run.then(function(){
 		jQuery("#"+name).DataTable();
	});
}

</script>