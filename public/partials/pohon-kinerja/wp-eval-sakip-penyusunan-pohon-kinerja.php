<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

$input = shortcode_atts(array(
    'tahun_anggaran' => '2024',
	'periode' => '',
), $atts);

global $wpdb;
$data_all = [
	'data' => array()
];

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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

// pokin level 1
$pohon_kinerja_level_1 = $wpdb->get_results($wpdb->prepare("
	SELECT 
		* 
	FROM esakip_pohon_kinerja 
	WHERE parent=0 
		AND level=1 
		AND active=1 
		AND id_jadwal=%d 
	ORDER BY nomor_urut
", $input['periode']), ARRAY_A);
if(!empty($pohon_kinerja_level_1)){
	foreach ($pohon_kinerja_level_1 as $level_1) {
		if(empty($data_all['data'][$level_1['id']])){
			$data_all['data'][$level_1['id']] = [
				'id' => $level_1['id'],
				'label' => $level_1['label'],
				'level' => $level_1['level'],
				'indikator' => array(),
				'data' => array()
			];
			if(empty($level_1['nomor_urut'])){
				$level_1['nomor_urut'] = count($data_all['data']);
				$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $level_1['nomor_urut']), array(
					'id' => $level_1['id']));
			}
		}

		// indikator pokin level 1
		$indikator_pohon_kinerja_level_1 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_pohon_kinerja 
			WHERE parent=%d 
				AND level=1 
				AND active=1 
				AND id_jadwal=%d 
			ORDER BY nomor_urut
		", $level_1['id'], $input['periode']), ARRAY_A);
		if(!empty($indikator_pohon_kinerja_level_1)){
			foreach ($indikator_pohon_kinerja_level_1 as $indikator_level_1) {
				if(!empty($indikator_level_1['label_indikator_kinerja'])){
					if(empty($data_all['data'][$level_1['id']]['indikator'][$indikator_level_1['id']])){
						$data_all['data'][$level_1['id']]['indikator'][$indikator_level_1['id']] = [
							'id' => $indikator_level_1['id'],
							'parent' => $indikator_level_1['parent'],
							'label_indikator_kinerja' => $indikator_level_1['label_indikator_kinerja'],
							'level' => $indikator_level_1['level']
						];
						if(empty($indikator_level_1['nomor_urut'])){
							$indikator_level_1['nomor_urut'] = count($data_all['data'][$level_1['id']]['indikator']);
							$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $indikator_level_1['nomor_urut']), array(
								'id' => $indikator_level_1['id']));
						}
					}
				}
			}
		}

		// pokin level 2 
		$pohon_kinerja_level_2 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_pohon_kinerja 
			WHERE parent=%d 
				AND level=2
				AND active=1 
				AND id_jadwal=%d 
			ORDER by nomor_urut
		", $level_1['id'], $input['periode']), ARRAY_A);
		if(!empty($pohon_kinerja_level_2)){
			foreach ($pohon_kinerja_level_2 as $level_2) {
				if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']])){
					$data_all['data'][$level_1['id']]['data'][$level_2['id']] = [
						'id' => $level_2['id'],
						'label' => $level_2['label'],
						'level' => $level_2['level'],
						'indikator' => array(),
						'data' => array()
					];
					if(empty($level_2['nomor_urut'])){
						$level_2['nomor_urut'] = count($data_all['data'][$level_1['id']]['data']);
						$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $level_2['nomor_urut']), array(
							'id' => $level_2['id']));
					}
				}

				// indikator pokin level 2
				$indikator_pohon_kinerja_level_2 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_pohon_kinerja 
					WHERE parent=%d 
						AND level=2 
						AND active=1 
						AND id_jadwal=%d 
					ORDER BY nomor_urut
				", $level_2['id'], $input['periode']), ARRAY_A);
				if(!empty($indikator_pohon_kinerja_level_2)){
					foreach ($indikator_pohon_kinerja_level_2 as $indikator_level_2) {
						if(!empty($indikator_level_2['label_indikator_kinerja'])){
							if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['indikator'][$indikator_level_2['id']])){
								$data_all['data'][$level_1['id']]['data'][$level_2['id']]['indikator'][$indikator_level_2['id']] = [
									'id' => $indikator_level_2['id'],
									'parent' => $indikator_level_2['parent'],
									'label_indikator_kinerja' => $indikator_level_2['label_indikator_kinerja'],
									'level' => $indikator_level_2['level']
								];
								if(empty($indikator_level_2['nomor_urut'])){
									$indikator_level_2['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['indikator']);
									$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $indikator_level_2['nomor_urut']), array(
										'id' => $indikator_level_2['id']));
								}
							}
						}
					}
				}

				// pokin level 3
				$pohon_kinerja_level_3 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_pohon_kinerja 
					WHERE parent=%d 
						AND level=3 
						AND active=1 
						AND id_jadwal=%d 
					ORDER by nomor_urut
				", $level_2['id'], $input['periode']), ARRAY_A);
				if(!empty($pohon_kinerja_level_3)){
					foreach ($pohon_kinerja_level_3 as $level_3) {
						if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']])){
							$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']] = [
								'id' => $level_3['id'],
								'label' => $level_3['label'],
								'level' => $level_3['level'],
								'indikator' => array(),
								'data' => array()
							];
							if(empty($level_3['nomor_urut'])){
								$level_3['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data']);
								$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $level_3['nomor_urut']), array(
									'id' => $level_3['id']));
							}
						}

						// indikator pokin level 3
						$indikator_pohon_kinerja_level_3 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pohon_kinerja 
							WHERE parent=%d 
								AND level=3 
								AND active=1 
								AND id_jadwal=%d
							ORDER BY nomor_urut
						", $level_3['id'], $input['periode']), ARRAY_A);
						if(!empty($indikator_pohon_kinerja_level_3)){
							foreach ($indikator_pohon_kinerja_level_3 as $indikator_level_3) {
								if(!empty($indikator_level_3['label_indikator_kinerja'])){
									if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['indikator'][$indikator_level_3['id']])){
										$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['indikator'][$indikator_level_3['id']] = [
											'id' => $indikator_level_3['id'],
											'parent' => $indikator_level_3['parent'],
											'label_indikator_kinerja' => $indikator_level_3['label_indikator_kinerja'],
											'level' => $indikator_level_3['level']
										];
										if(empty($indikator_level_3['nomor_urut'])){
											$indikator_level_3['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['indikator']);
											$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $indikator_level_3['nomor_urut']), array(
												'id' => $indikator_level_3['id']));
										}
									}
								}
							}
						}

						// pokin level 4
						$pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pohon_kinerja 
							WHERE parent=%d 
								AND level=4
								AND active=1 
								AND id_jadwal=%d
							ORDER by nomor_urut
						", $level_3['id'], $input['periode']), ARRAY_A);
						if(!empty($pohon_kinerja_level_4)){
							foreach ($pohon_kinerja_level_4 as $level_4) {
								if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']])){
									$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']] = [
										'id' => $level_4['id'],
										'label' => $level_4['label'],
										'level' => $level_4['level'],
										'indikator' => array()
									];
									if(empty($level_4['nomor_urut'])){
										$level_4['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data']);
										$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $level_4['nomor_urut']), array(
											'id' => $level_4['id']));
									}
								}

								// indikator pokin level 4
								$indikator_pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
									SELECT 
										* 
									FROM esakip_pohon_kinerja 
									WHERE parent=%d 
										AND level=4 
										AND active=1 
										AND id_jadwal=%d
									ORDER BY nomor_urut
								", $level_4['id'], $input['periode']), ARRAY_A);
								if(!empty($indikator_pohon_kinerja_level_4)){
									foreach ($indikator_pohon_kinerja_level_4 as $indikator_level_4) {
										if(!empty($indikator_level_4['label_indikator_kinerja'])){
											if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['indikator'][$indikator_level_4['id']])){
												$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['indikator'][$indikator_level_4['id']] = [
													'id' => $indikator_level_4['id'],
													'parent' => $indikator_level_4['parent'],
													'label_indikator_kinerja' => $indikator_level_4['label_indikator_kinerja'],
													'level' => $indikator_level_4['level']
												];
												if(empty($indikator_level_4['nomor_urut'])){
													$indikator_level_4['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['indikator']);
													$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $indikator_level_4['nomor_urut']), array(
														'id' => $indikator_level_4['id']));
												}
											}
										}
									}
								}

								// koneksi pokin pemda dan opd
								// untuk mendapatkan koneksi pokin level 4 pemda
								$koneksi_pokin_pemda_level_4 = $wpdb->get_results($wpdb->prepare("
									SELECT 
										koneksi.* ,
										pk.id as id_parent,
										pk.level as level_parent,
										pk.label as label_parent,
										pk.label_indikator_kinerja
									FROM esakip_koneksi_pokin_pemda_opd koneksi
									LEFT JOIN esakip_pohon_kinerja_opd as pk ON koneksi.parent_pohon_kinerja_koneksi = pk.id
									WHERE koneksi.parent_pohon_kinerja=%d 
										AND koneksi.active=1 
									ORDER BY koneksi.id
								", $level_4['id']), ARRAY_A);

								if(!empty($koneksi_pokin_pemda_level_4)){
									foreach ($koneksi_pokin_pemda_level_4 as $key_koneksi_pokin => $koneksi_pokin_level_4) {
										$nama_perangkat_koneksi = '';
										$this_data_id_skpd = $koneksi_pokin_level_4['id_skpd_koneksi'];

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
											", $this_data_id_skpd, $tahun_anggaran_sakip),
											ARRAY_A
										);
										$nama_perangkat_koneksi = $nama_skpd['nama_skpd'];
										$koneksi_pokin_opd_lain = 0;
										$id_skpd_view_pokin = $koneksi_pokin_level_4['id_skpd_koneksi'];

										$data_parent_tujuan = array();
										$data_pokin_opd = array();
										$id_level_1_parent = 0;
										$indikator_opd = array();
										if($koneksi_pokin_level_4['status_koneksi'] == 1){
											// untuk mendapatkan id parent level 1 suatu opd
											$data_parent_tujuan = $this->get_parent_1_koneksi_pokin_pemda_opd(
												array(
													'id' => $koneksi_pokin_level_4['id'],
													'level' => $koneksi_pokin_level_4['level_parent'],
													'periode' => $input['periode'],
													'tipe' => 'opd',
													'id_parent' => $koneksi_pokin_level_4['id_parent'],
													'id_skpd' => $id_skpd_view_pokin,
													'keterangan_tolak' => $koneksi_pokin_level_4['keterangan_tolak']
												)
											);
											$data_pokin_opd = $this->get_pokin(array(
												'id' => $koneksi_pokin_level_4['id_parent'],
												'level' => $koneksi_pokin_level_4['level_parent']+1,
												'periode' => $input['periode'],
												'tipe' => 'opd',
												'id_skpd' => $id_skpd_view_pokin
											));

											$indikator_opd_db = $wpdb->get_results($wpdb->prepare("
												SELECT 
													label_indikator_kinerja
												FROM esakip_pohon_kinerja_opd koneksi
												WHERE parent = %d
													AND level = %d
													AND active = 1
											", $koneksi_pokin_level_4['id_parent'], $koneksi_pokin_level_4['level_parent']), ARRAY_A);

											$indikator_opd = array_column($indikator_opd_db, 'label_indikator_kinerja');
											// print_r($indikator_opd); die();
										}

										if(!empty($data_parent_tujuan)){
											$id_level_1_parent = $data_parent_tujuan['id'];
										}	

										if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['koneksi_pokin'][$key_koneksi_pokin])){
											$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['koneksi_pokin'][$key_koneksi_pokin] = [
												'id' => $koneksi_pokin_level_4['id'],
												'parent_pohon_kinerja' => $koneksi_pokin_level_4['parent_pohon_kinerja'],
												'status_koneksi' => $koneksi_pokin_level_4['status_koneksi'],
												'label_parent' => $koneksi_pokin_level_4['label_parent'],
												'keterangan_tolak' => $koneksi_pokin_level_4['keterangan_tolak'],
												'nama_skpd' => $nama_perangkat_koneksi,
												'id_skpd_view_pokin' => $id_skpd_view_pokin,
												'id_level_1_parent' => $id_level_1_parent,
												'id_level_2_parent' => $koneksi_pokin_level_4['id_parent'],
												'pokin_opd_turunan' => $data_pokin_opd,
												'label_indikator_kinerja' => $indikator_opd
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

// echo '<pre>'; print_r(array_values($data_all['data'])); echo '</pre>';die();

$view_kinerja = $this->functions->generatePage(array(
	'nama_page' => 'View Pohon Kinerja',
	'content' => '[view_pohon_kinerja]',
	'show_header' => 1,
	'post_status' => 'private'
));

$view_kinerja_pokin_opd = $this->functions->generatePage(array(
	'nama_page' => 'View Pohon Kinerja OPD',
	'content' => '[view_pohon_kinerja_opd periode='. $input['periode'] .']',
	'show_header' => 1,
	'post_status' => 'private'
));

$html = '';
foreach ($data_all['data'] as $key1 => $level_1) {
	$indikator=array();
	foreach ($level_1['indikator'] as $indikatorlevel1) {
		$indikator[]=$indikatorlevel1['label_indikator_kinerja'];
	}
	$html.='
	<tr>
		<td class="level1" style="background: #efd655;"><a href="'.$view_kinerja['url'].'&id='.$level_1['id'].'&id_jadwal='.$input['periode'].'" target="_blank">'.$level_1['label'].'</a></td>
		<td class="indikator" style="background: #b5d9ea;">'.implode("<hr/>", $indikator).'</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
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
			<td class="level2" style="background: #fe7373;">'.$level_2['label'].'</td>
			<td class="indikator" style="background: #b5d9ea;">'.implode("<hr/>", $indikator).'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
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
				<td class="level3" style="background: #57b2ec;">'.$level_3['label'].'</td>
				<td class="indikator" style="background: #b5d9ea;">'.implode("<hr/>", $indikator).'</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
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
				if (!isset($level_4['koneksi_pokin']) || !is_array($level_4['koneksi_pokin'])) {
				    $level_4['koneksi_pokin'] = array();
				}
				$show_skpd = array();
				$koneksi_pokin = array();
				$koneksi_indikator_pokin = array();
				$koneksi_pokin_turunan = array();
				foreach ($level_4['koneksi_pokin'] as $koneksi_pokin_level_4) {
					$class_pengusul = "";
		
					$nama_skpd = $koneksi_pokin_level_4['nama_skpd'];
					if($koneksi_pokin_level_4['id_level_1_parent'] !== 0){
						$nama_skpd = "<a href='" . $view_kinerja_pokin_opd['url'] . "&id_skpd=" . $koneksi_pokin_level_4['id_skpd_view_pokin']  . "&id=" . $koneksi_pokin_level_4['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $koneksi_pokin_level_4['nama_skpd'] . "</a>";
					}
					
					switch ($koneksi_pokin_level_4['status_koneksi']) {
						case '1':
							$status_koneksi = 'Disetujui';
							$label_color = 'success text-white';
							break;
						case '2':
							$status_koneksi = 'Ditolak';
							$label_color = 'danger text-white';
							break;
						
						default:
							$status_koneksi = 'Menunggu';
							$label_color = 'secondary text-white';
							break;
					}
		
					$show_nama_skpd = $nama_skpd . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_koneksi.'</span> ';
					
					$class_koneksi_pokin_vertikal = '';
		
					$detail = "<a href='javascript:void(0)' data-id='". $koneksi_pokin_level_4['id'] ."' class='detail-koneksi-pokin text-primary' onclick='detail_koneksi_pokin(" . $koneksi_pokin_level_4['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a>";

					$keterangan_tolak_koneksi = !empty($koneksi_pokin_level_4['keterangan_tolak']) ? "( ket: ". $koneksi_pokin_level_4['keterangan_tolak'] ." )" : '';

					$show_skpd[]= '<div class="koneksi-pokin-isi '. $class_pengusul .' '. $class_koneksi_pokin_vertikal .'"><div style="font-weight: 500;">'. $show_nama_skpd .'</div></div>';
					$koneksi_pokin[]= '<div class="koneksi-pokin-isi '. $class_pengusul .' '. $class_koneksi_pokin_vertikal .'"><div>'. $koneksi_pokin_level_4['label_parent'] .'</div><div style="font-weight: 500;"></div>' . $keterangan_tolak_koneksi . '</div>';
					$koneksi_indikator_pokin[]= '<div>'. implode("<hr/>", $koneksi_pokin_level_4['label_indikator_kinerja']).'</div><div style="font-weight: 500;"></div>' . $keterangan_tolak_koneksi . '</div>';
					$koneksi_pokin_turunan[] = $koneksi_pokin_level_4['pokin_opd_turunan'];
				}
		
				$html.='
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="level4" style="background: #c979e3;">'.$level_4['label'].'</td>
					<td class="indikator" style="background: #b5d9ea;">'.implode("<hr/>", $indikator).'</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>';
				if (!empty($show_skpd)) {
					$rowspan = count($show_skpd);
					foreach ($show_skpd as $i => $koneksi_skpd) {
						$html .= '<tr>';
							$html .= '<td></td>';
							$html .= '<td></td>';
							$html .= '<td></td>';
							$html .= '<td></td>';
							$html .= '<td></td>';
							$html .= '<td></td>';
							$html .= '<td></td>';
							$html .= '<td></td>';
							$html .= '<td>' . $koneksi_skpd . '</td>';
							$html .= '<td>' . ($koneksi_pokin[$i] ?? '') . '</td>';
							$html .= '<td style="background: #b5d9ea;">' . ($koneksi_indikator_pokin[$i] ?? '') . '</td>';
							$html .= '<td></td>';
							$html .= '<td></td>';
							$html .= '<td></td>';
							$html .= '<td></td>';
							$html .= '<td></td>';
							$html .= '<td></td>';
						$html .= '</tr>';
						foreach ($koneksi_pokin_turunan[$i] as $turunan3) {
						    $indikator_label_3 = array_column($turunan3['indikator'], 'label_indikator_kinerja');
						    $html .= '<tr class="detail-rhk">';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td>' . $turunan3['label'] . '</td>';
						        $html .= '<td style="background: #b5d9ea;">' . implode('<hr>', $indikator_label_3) . '</td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						        $html .= '<td></td>';
						    $html .= '</tr>';

						    foreach ($turunan3['data'] as $turunan4) {
						        $indikator_label_4 = array_column($turunan4['indikator'], 'label_indikator_kinerja');
						        $html .= '<tr class="detail-rhk">';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						            $html .= '<td>' . $turunan4['label'] . '</td>';
						            $html .= '<td style="background: #b5d9ea;">' . implode('<hr>', $indikator_label_4) . '</td>';
						            $html .= '<td></td>';
						            $html .= '<td></td>';
						        $html .= '</tr>';

						        foreach ($turunan4['data'] as $turunan5) {
						            $indikator_label_5 = array_column($turunan5['indikator'], 'label_indikator_kinerja');
						            $html .= '<tr class="detail-rhk">';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td></td>';
						                $html .= '<td>' . $turunan5['label'] . '</td>';
						                $html .= '<td style="background: #b5d9ea;">' . implode('<hr>', $indikator_label_5) . '</td>';
						            $html .= '</tr>';
						        }
						    }
						}

					}
				}
			}
		}
	}
}

$unit_koneksi = $wpdb->get_results(
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
		AND tahun_anggaran=%d
		GROUP BY id_skpd
		ORDER BY kode_skpd ASC
	", $tahun_anggaran_sakip),
	ARRAY_A
);

$option_skpd = "<option value=''>Pilih Perangkat Daerah</option>";
if(!empty($unit_koneksi)){
	foreach ($unit_koneksi as $v_unit) {
		$option_skpd .="<option value='" . $v_unit['id_skpd'] . "'>" . $v_unit['nama_skpd'] . "</option>";
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
	.indikator {
		background: #b5d9ea;
	}
	.penyusunan_pohon_kinerja thead{
        position: sticky;
        top: -6px;
        background: #ffc491;
    }
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

	.label-koneksi-pokin {
		padding: 0 .7em .7em;
	}
	.koneksi-pokin-1 {
		margin: 0px -16px 0px;
		padding:  .5em .9em;
		border-width: 1px 0 0;
		border-style: solid;
		border-color: gray;
	}
	
	.detail-koneksi-pokin .dashicons{
		text-decoration: none;
		vertical-align: text-bottom !important;
		font-size: 23px !important;
	}
</style>
<h3 style="text-align: center; margin-top: 10px; font-weight: bold;">Penyusunan Pohon Kinerja<br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h3><br>
<div style="text-align: center;">
  <label>
    <input type="checkbox" onclick="detail_rhk(this);">
    Tampilkan Detail Pokin Perangkat Daerah
  </label>
</div>
<?php if (!$is_admin_panrb): ?>
<div id="action" class="action-section"></div>
<?php endif; ?>
<div style="padding: 5px; overflow: auto; height: 100vh;">
	<table id="cetak" title="Penyusunan Pohon Kinerja Pemerintah Daerah" class="table table-bordered penyusunan_pohon_kinerja">
		<thead style="background: #ffc491;">
			<tr>
				<th style="min-width: 200px;">Level 1</th>
				<th style="min-width: 200px;">Indikator Kinerja</th>
				<th style="min-width: 200px;">Level 2</th>
				<th style="min-width: 200px;">Indikator Kinerja</th>
				<th style="min-width: 200px;">Level 3</th>
				<th style="min-width: 200px;">Indikator Kinerja</th>
				<th style="min-width: 200px;">Level 4</th>
				<th style="min-width: 200px;">Indikator Kinerja</th>
				<th style="min-width: 200px;">Perangkat Daerah</th>
				<th style="min-width: 200px;">Pokin OPD</th>
				<th style="min-width: 200px;">Indikator Kinerja</th>
				<th style="min-width: 200px;">Pokin OPD</th>
				<th style="min-width: 200px;">Indikator Kinerja</th>
				<th style="min-width: 200px;">Pokin OPD</th>
				<th style="min-width: 200px;">Indikator Kinerja</th>
				<th style="min-width: 200px;">Pokin OPD</th>
				<th style="min-width: 200px;">Indikator Kinerja</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $html; ?>
		</tbody>
	</table>
</div>
<div class="hide-print" id="catatan_dokumentasi" style="max-width: 1200px; margin: auto;">
	<h4 style="margin: 30px 0 10px; font-weight: bold;">Catatan Dokumentasi:</h4>
	<ul>
		<li>Pohon kinerja bisa dilihat ketika data terisi minimal sampai dengan level ke-2.</li>
	</ul>
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
				  	</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
				  	<div class="tab-pane fade show active" id="nav-level-1" role="tabpanel" aria-labelledby="nav-level-1-tab"></div>
				  	<div class="tab-pane fade" id="nav-level-2" role="tabpanel" aria-labelledby="nav-level-2-tab"></div>
				  	<div class="tab-pane fade" id="nav-level-3" role="tabpanel" aria-labelledby="nav-level-3-tab"></div>
				  	<div class="tab-pane fade" id="nav-level-4" role="tabpanel" aria-labelledby="nav-level-4-tab"></div>
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

<!-- Modal crud koneksi -->
<div class="modal fade" id="modal-koneksi" data-backdrop="static"  role="dialog" aria-labelledby="modal-koneksi-label" aria-hidden="true">
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

<script type="text/javascript">
jQuery(document).ready(function(){
    run_download_excel_sakip();
    jQuery('#action-sakip').prepend('<a style="margin-right: 10px;" id="tambah-pohon-kinerja" onclick="return false;" href="#" class="btn btn-primary"><i class="dashicons dashicons-plus"></i> Tambah Data</a>');

	jQuery("#tambah-pohon-kinerja").on('click', function(){
		pokinLevel1().then(function(){
			// jQuery("#pokinLevel1").DataTable();
		});
	});

	jQuery(document).on('click', '#tambah-pokin-level1', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(''
			+'<form id="form-pokin">'
				+'<input type="hidden" name="parent" value="0">'
				+'<input type="hidden" name="level" value="1">'
                +`<div class="form-group">`
                    +'<label for="label-pokin">Label POKIN</label>'
                    +`<textarea class="form-control" id="label-pokin" name="label" placeholder="Tuliskan pohon kinerja level 1..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
                +`</div>`
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
		  		'id':jQuery(this).data('id')
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
                            +'<label for="label-pokin">Label POKIN</label>'
							+`<textarea class="form-control" id="label-pokin" name="label">${response.data.label}</textarea>`
						+`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
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
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel1().then(function(){
							// jQuery("#pokinLevel1").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level1', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level1').text()}">`
				+`<input type="hidden" name="level" value="1">`
				+`<div class="form-group">`
					+`<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level1').text()}</label>`
					+`<textarea class="form-control" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
				+`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
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
	  			'id':jQuery(this).data('id')
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
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
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
					'id':jQuery(this).data('id')
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel1().then(function(){
							// jQuery("#pokinLevel1").DataTable();
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
			// jQuery("#pokinLevel2").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level2', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="2">`
                +`<div class="form-group">`
                    +'<label for="label-pokin">Label POKIN</label>'
                    +`<textarea class="form-control" id="label-pokin" name="label" placeholder="Tuliskan pohon kinerja level 2..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
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
		  		'id':jQuery(this).data('id')
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
                            +'<label for="label-pokin">Label POKIN</label>'
							+`<textarea class="form-control" id="label-pokin" name="label">${response.data.label}</textarea>`
						+`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
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
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel2({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel2").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level2', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
        var last_urutan = +jQuery(this).attr('last-urutan');
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
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
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
	  			'id':jQuery(this).data('id')
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
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
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
					'id':jQuery(this).data('id')
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel2({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel2").DataTable();
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
			// jQuery("#pokinLevel3").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level3', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="3">`
                +`<div class="form-group">`
                    +'<label for="label-pokin">Label POKIN</label>'
                    +`<textarea class="form-control" id="label-pokin" name="label" placeholder="Tuliskan pohon kinerja level 3..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
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
		  		'id':jQuery(this).data('id')
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
                            +'<label for="label-pokin">Label POKIN</label>'
							+`<textarea class="form-control" id="label-pokin" name="label">${response.data.label}</textarea>`
						+`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
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
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel3({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel3").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level3', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
        var last_urutan = +jQuery(this).attr('last-urutan');
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
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
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
	  			'id':jQuery(this).data('id')
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
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
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
					'id':jQuery(this).data('id')
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel3({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel3").DataTable();
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
			// jQuery("#pokinLevel4").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level4', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="4">`
                +`<div class="form-group">`
                    +'<label for="label-pokin">Label POKIN</label>'
                    +`<textarea class="form-control" id="label-pokin" name="label" placeholder="Tuliskan pohon kinerja level 4..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
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
		  		'id':jQuery(this).data('id')
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				let table_data_koneksi_pokin = '';
				if(response.data_koneksi_pokin == "" || response.data_koneksi_pokin == undefined){
					table_data_koneksi_pokin = ``
					+`<tr>`
						+`<td colspan="5" class="text-center">Data tidak ditemukan!</td>`
					+`</tr>`;
				}else{
					table_data_koneksi_pokin = response.data_koneksi_pokin;
				}
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
                            +'<label for="label-pokin">Label POKIN</label>'
							+`<textarea class="form-control" id="label-pokin" name="label">${response.data.label}</textarea>`
						+`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
                        +`</div>`
						+`<div class="setting-koneksi" style="margin-top:10px">`
							+`<button type="button" data-setting-koneksi="false" data-parent-koneksi="${response.data.id}" class="btn btn-success mb-2" id="tambah-koneksi"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
						+`</div>`
						+`<div class="wrap-table setting-koneksi">`
							+`<table id="table_koneksi_pokin" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Pohon Kinerja Perangkat Daerah</th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center">Keterangan</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${table_data_koneksi_pokin}</tbody>`
							+`</table>`
							+`<small class="text-body-secondary">Jika akan membatalkan koneksi pokin, pastikan perangkat daerah terkait membatalkan koneksi pokin terlebih dahulu!</small>`
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
			}
		});
	})

	jQuery(document).on('click', '#tambah-koneksi', function(){
		let parent_koneksi = jQuery(this).data('parent-koneksi');
		jQuery("#modal-koneksi").find('.modal-title').html('Tambah Koneksi Pohon Kinerja');
		jQuery("#modal-koneksi").find('.modal-body').html(``
			+`<form id="form-koneksi">`				
				+`<input type="hidden" name="parentKoneksi" value="${jQuery(this).data('parent-koneksi')}">`
				// +`<input type="hidden" name="settingKoneksi" value="${jQuery(this).data('setting-koneksi')}">`
				+`<div class="form-group" id="showSkpdKoneksi">`
					+`<label for="skpdKoneksi">Pilih Perangkat Daerah</label>`
					+`<select class="form-control" name="skpdKoneksi[]" multiple="multiple" id="skpdKoneksi">`
					+`<?php echo $option_skpd; ?>`
					+`</select>`
				+`</div>`
			+`</form>`);
			jQuery("#modal-koneksi").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-koneksi" data-action="create_koneksi_pokin">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-koneksi").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-koneksi").find('.modal-dialog').css('width','');
		jQuery("#modal-koneksi").modal('show');
		// let data_skpd = ;
		jQuery('#skpdKoneksi').select2({width: '100%'});
		getSkpdById(parent_koneksi).then(function(){
		});
	})

	jQuery(document).on('click', '#simpan-data-koneksi', function(){
		jQuery('#wrap-loading').show();
		let modal = jQuery("#modal-koneksi");
		let action = jQuery(this).data('action');
		let view = jQuery(this).data('view');
		let form = getFormData(jQuery("#form-koneksi"));

		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			dataType:'json',
			data:{
				'action': action,
        		'api_key': esakip.api_key,
				'data': JSON.stringify(form),
			},
			success:function(response){
				jQuery('#wrap-loading').hide();
				alert(response.message);
				if(response.status){
					runFunction(view, [form])
					modal.modal('hide');
					jQuery("#modal-crud").modal('hide');
				}
			}
		})
	});
		
	jQuery(document).on('click', '.delete-koneksi-pokin', function(){
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_koneksi_pokin',
					'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					jQuery("#modal-crud").modal('hide');
				}
			})
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
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel4({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel4").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level4', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
        var last_urutan = +jQuery(this).attr('last-urutan');
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
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
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
	  			'id':jQuery(this).data('id')
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
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
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
					'id':jQuery(this).data('id')
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel4({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel4").DataTable();
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

function getSkpdById(id_parent_pokin){
	jQuery("#wrap-loading").show();
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
				'action': 'get_skpd_koneksi_pokin_by_id',
				'api_key': esakip.api_key,
				'id_parent_pokin':id_parent_pokin,
			},
			dataType:'json',
			success:function(response){
				response.data.map(function(value, index){
					jQuery('#skpdKoneksi option[value="'+value.id_skpd_koneksi+'"]').attr('disabled', true).trigger('change');
				})
				jQuery("#wrap-loading").hide();
				
				resolve();
			}
		})
	});
};

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
	      		"api_key": esakip.api_key
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
                var nomor_urut = 0;
                if(res.data.length >= 1){
                    nomor_urut = Math.floor(res.data[res.data.length-1].nomor_urut);
                }
          		let level1 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" class="btn btn-success mb-2" id="tambah-pokin-level1" last-urutan="${nomor_urut}"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table" id="pokinLevel1">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:50px">No</th>`
	          					+`<th class="text-center">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:250px">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
                                let indikator = Object.values(value.indikator);
                                var last_urutan = 0;
                                if(indikator.length > 0){
                                    var last_urutan = Math.floor(indikator[indikator.length-1].nomor_urut);
                                }
			          			level1 += ``
				          			+`<tr id="pokinLevel1_${value.id}">`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level1">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-success tambah-indikator-pokin-level1" title="Tambah Indikator" last-urutan="${last_urutan}"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level2" title="Lihat pohon kinerja level 2"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-primary edit-pokin-level1" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger hapus-pokin-level1" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

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
	      		"api_key": esakip.api_key
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
                var nomor_urut = 0;
                if(res.data.length >= 1){
                    nomor_urut = Math.floor(res.data[res.data.length-1].nomor_urut);
                }
          		let level2 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level2"  last-urutan="${nomor_urut}"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
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
	          					+`<th class="text-center" style="width:50px">No</th>`
	          					+`<th class="text-center">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:250px">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
                                let indikator = Object.values(value.indikator);
                                var last_urutan = 0;
                                if(indikator.length > 0){
                                    var last_urutan = Math.floor(indikator[indikator.length-1].nomor_urut);
                                }
			          			level2 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level2">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level2" title="Tambah Indikator" last-urutan="${last_urutan}"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level3" title="Lihat pohon kinerja level 3"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level2" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level2" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

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
	      		"api_key": esakip.api_key
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
                var nomor_urut = 0;
                if(res.data.length >= 1){
                    nomor_urut = Math.floor(res.data[res.data.length-1].nomor_urut);
                }
          		let level3 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level3" last-urutan="${nomor_urut}"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
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
	          					+`<th class="text-center" style="width:50px">No</th>`
	          					+`<th class="text-center">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:250px">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
                                let indikator = Object.values(value.indikator);
                                var last_urutan = 0;
                                if(indikator.length > 0){
                                    var last_urutan = Math.floor(indikator[indikator.length-1].nomor_urut);
                                }
			          			level3 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level3">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level3" title="Tambah Indikator" last-urutan="${last_urutan}"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level4" title="Lihat pohon kinerja level 4"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level3" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level3" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

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
	      		"api_key": esakip.api_key
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
                var nomor_urut = 0;
                if(res.data.length >= 1){
                    nomor_urut = Math.floor(res.data[res.data.length-1].nomor_urut);
                }
          		let level4 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level4" last-urutan="${nomor_urut}"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
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
	          					+`<th class="text-center" style="width:50px">No</th>`
	          					+`<th class="text-center">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:250px">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
                                let indikator = Object.values(value.indikator);
                                var last_urutan = 0;
                                if(indikator.length > 0){
                                    var last_urutan = Math.floor(indikator[indikator.length-1].nomor_urut);
                                }
			          			level4 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level4">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level4" title="Tambah Indikator" last-urutan="${last_urutan}"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level4" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level4" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

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

function runFunction(name, arguments){
    var fn = window[name];
    if(typeof fn !== 'function')
        return;

    var run = fn.apply(window, arguments);
    run.then(function(){
 		// jQuery("#"+name).DataTable();
	});
}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    jQuery.map(unindexed_array, function (n, i) {
		var nama_baru = n['name'].split('[');
            if(nama_baru.length > 1){
                nama_baru = nama_baru[0];
                if(!indexed_array[nama_baru]){
                    indexed_array[nama_baru] = [];
                }
                indexed_array[nama_baru].push(n['value']);
            }else{
				indexed_array[n['name']] = n['value'];
            }
    });

    return indexed_array;
}
function detail_rhk(checkbox) {
    var detailRows = document.querySelectorAll('.detail-rhk');
    detailRows.forEach(function(row) {
        row.style.display = checkbox.checked ? '' : 'none';
    });
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelector('input[type="checkbox"][onclick="detail_rhk(this);"]').checked = true;
    detail_rhk(document.querySelector('input[type="checkbox"][onclick="detail_rhk(this);"]'));
});
</script>