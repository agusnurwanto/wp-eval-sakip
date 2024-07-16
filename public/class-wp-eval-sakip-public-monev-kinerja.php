<?php

class Wp_Eval_Sakip_Monev_Kinerja
{
	public function get_data_renaksi()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = '';
					if (!empty($_POST['tipe_pokin'])) {
						if (!empty($_POST['id_skpd'])) {
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_pokin'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_pokin'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					switch ($_POST['level']) {
						case '2':
							$label_parent = '
							(
								SELECT 
									label 
								FROM esakip_data_rencana_aksi' . $_prefix_opd . ' 
								WHERE id=a.id ' . $_where_opd . '
							) label_parent_1';
							break;

						// case '3':
						// 	$label_parent = '
						// 	(
						// 		SELECT 
						// 			label 
						// 		FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
						// 		WHERE id=(
						// 			SELECT 
						// 				parent 
						// 			FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
						// 			WHERE id=a.id ' . $_where_opd . '
						// 		) ' . $_where_opd . '
						// 	) label_parent_1,
						// 	(
						// 		SELECT 
						// 			label 
						// 		FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
						// 		WHERE id=a.id ' . $_where_opd . '
						// 	) label_parent_2';
						// 	break;

						// case '4':
						// 	$label_parent = '
						// 	(
						// 		SELECT 
						// 			label 
						// 		FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
						// 		WHERE id=(
						// 			SELECT 
						// 				parent 
						// 			FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
						// 			WHERE id=(
						// 				SELECT 
						// 					parent 
						// 				FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
						// 				WHERE id=a.id ' . $_where_opd . '
						// 			) ' . $_where_opd . '
						// 		) ' . $_where_opd . '
						// 	) label_parent_1,
						// 	(
						// 		SELECT 
						// 			label 
						// 		FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
						// 		WHERE id=(
						// 			SELECT 
						// 				parent 
						// 			FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
						// 			WHERE id=a.id ' . $_where_opd . '
						// 		) ' . $_where_opd . '
						// 	) label_parent_2,
						// 	(
						// 		SELECT 
						// 			label 
						// 		FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
						// 		WHERE id=a.id ' . $_where_opd . '
						// 	) label_parent_3';
						// 	break;

						// case '5':
							$label_parent = '
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
									WHERE id=(
										SELECT 
											parent 
										FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
										WHERE id=(
											SELECT 
												parent 
											FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
											WHERE id=a.id ' . $_where_opd . '
										) ' . $_where_opd . '
									) ' . $_where_opd . '
								) ' . $_where_opd . '
							) label_parent_1,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
									WHERE id=(
										SELECT 
											parent 
										FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
										WHERE id=a.id ' . $_where_opd . '
									) ' . $_where_opd . '
								) ' . $_where_opd . '
							) label_parent_2,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
									WHERE id=a.id ' . $_where_opd . '
								) ' . $_where_opd . '
							) label_parent_3,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
								WHERE id=a.id ' . $_where_opd . '
							) label_parent_4';
							break;

						default:
							$label_parent = '';
							break;
					}

					if ($_prefix_opd == '') {
						$data_renaksi = array();
					} else if ($_prefix_opd == '_opd') {
						$data_renaksi = $wpdb->get_results($wpdb->prepare(
							"
							SELECT 
								a.id,
								a.label,
								a.parent,
								a.active,
								b.id AS id_indikator,
								b.label_indikator_kinerja
							FROM esakip_data_rencana_aksi_opd a
								LEFT JOIN esakip_data_rencana_aksi_opd b 
									ON a.id=b.parent AND a.level=b.level 
							WHERE 
								a.parent=%d AND 
								a.level=%d AND 
								a.active=%d AND 
								a.id_skpd=%d
							ORDER BY a.id",
							$_POST['parent'],
							$_POST['level'],
							1,
							$id_skpd
						), ARRAY_A);
					}

					$dataParent = array();
					if ($_prefix_opd == '') {
						if (!empty($label_parent)) {
							$dataParent = '';
						}
					} else if ($_prefix_opd == '_opd') {
						if (!empty($label_parent)) {
							$dataParent = $wpdb->get_results($wpdb->prepare(
								"
									SELECT 
										" . $label_parent . "
									FROM esakip_data_rencana_aksi_opd a 
									WHERE 
										a.id=%d AND
										a.active=%d AND 
										a.id_skpd=%d
									ORDER BY a.id",
								$_POST['id_jadwal'],
								$_POST['parent'],
								1,
								$id_skpd
							), ARRAY_A);
						}
					}

					$data = [
						'data' => [],
						'parent' => []
					];
					foreach ($data_renaksi as $key => $renaksi) {
						if (empty($data['data'][$renaksi['id']])) {
							$data['data'][$renaksi['id']] = [
								'id' => $renaksi['id'],
								'label' => $renaksi['label'],
								'parent' => $renaksi['parent'],
								'label_parent_1' => $renaksi['label_parent_1'],
								'indikator' => []
							];
						}

						if (!empty($pokin['id_indikator'])) {
							if (empty($data['data'][$pokin['id']]['indikator'][$pokin['id_indikator']])) {
								$data['data'][$pokin['id']]['indikator'][$pokin['id_indikator']] = [
									'id' => $pokin['id_indikator'],
									'label' => $pokin['label_indikator']
								];
							}
						}
					}

					foreach ($dataParent as $v_parent) {

						if (empty($data['parent'][$v_parent['label_parent_1']])) {
							$data['parent'][$v_parent['label_parent_1']] = $v_parent['label_parent_1'];
						}

						if (empty($data['parent'][$v_parent['label_parent_2']])) {
							$data['parent'][$v_parent['label_parent_2']] = $v_parent['label_parent_2'];
						}

						if (empty($data['parent'][$v_parent['label_parent_3']])) {
							$data['parent'][$v_parent['label_parent_3']] = $v_parent['label_parent_3'];
						}

						if (empty($data['parent'][$v_parent['label_parent_4']])) {
							$data['parent'][$v_parent['label_parent_4']] = $v_parent['label_parent_4'];
						}
					}

					echo json_encode([
						'status' => true,
						'data' => array_values($data['data']),
						'parent' => array_values($data['parent']),
						'sql' => $wpdb->last_query
					]);
					exit();
				} else {
					throw new Exception("API tidak ditemukan!", 1);
				}
			} else {
				throw new Exception("Format tidak sesuai!", 1);
			}
		} catch (Exception $e) {
			echo json_encode([
				'status' => false,
				'message' => $e->getMessage()
			]);
			exit();
		}
	}

	function create_renaksi(){
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_pokin_1'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Level 1 POKIN tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_pokin_2'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Level 2 POKIN tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['kegiatan_utama'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Kegiatan Utama tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID jadwal tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID OPD tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				if ($ret['status'] != 'error'){
					$data = array(
						'label' => $_POST['kegiatan_utama'],
						'id_pokin_1' => $_POST['id_pokin_1'],
						'id_pokin_2' => $_POST['id_pokin_2'],
						'label_pokin_1' => $_POST['label_pokin_1'],
						'label_pokin_2' => $_POST['label_pokin_2'],
						'id_skpd' => $_POST['id_skpd'],
						'id_jadwal' => $_POST['id_jadwal'],
						'level' => 1,
						'active' => 1,
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						'created_at' => current_time('mysql'),
					);
					$wpdb->insert('esakip_data_rencana_aksi_opd', $data);
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message'   => 'Format tidak sesuai!'
			);
		}
		die(json_encode($ret));
	}

	function get_table_input_rencana_aksi(){
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data rencana aksi!',
			'data'  => ''
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID OPD tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				if ($ret['status'] != 'error'){
					$data = $wpdb->get_results($wpdb->prepare("
						SELECT
							*
						FROM esakip_data_rencana_aksi_opd
						WHERE id_skpd=%d
							AND tahun_anggaran=%d
							AND active=1
					", $_POST['id_skpd'], $_POST['tahun_anggaran']), ARRAY_A);
					$html = '';
					$no = 0;
					foreach($data as $v){
						$no++;
						$html .= '
						<tr>
							<td class="text-center">'.$no.'</td>
							<td><span class="badge bg-success text-white">'.$v['label_pokin_2'].'</span><br>'.$v['label'].'</td>
							<td class="indikator_kegiatan_utama"></td>
							<td class="target_kegiatan_utama"></td>
							<td class="realisasi_target_kegiatan_utama"></td>
							<td class="recana_aksi"></td>
							<td class="satuan_indikator_renaksi"></td>
							<td class="indikator_renaksi"></td>
							<td class="target_indikator_renaksi"></td>
							<td class="realisasi_target_indikator_renaksi"></td>
							<td class="urian_renaksi"></td>
							<td class="satuan_urian_renaksi"></td>
							<td class="target_tw1_urian_renaksi"></td>
							<td class="target_tw2_urian_renaksi"></td>
							<td class="target_tw3_urian_renaksi"></td>
							<td class="target_tw4_urian_renaksi"></td>
							<td class="target_akhir_urian_renaksi"></td>
							<td class="anggaran_urian_renaksi"></td>
						</tr>
						';
					}
					if(empty($html)){
						$html = '<tr><td class="text-center" colspan="18">Data masih kosong!</td></tr>';
					}
					$ret['data'] = $html;
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message'   => 'Format tidak sesuai!'
			);
		}
		die(json_encode($ret));
	}
}