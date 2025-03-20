<?php

class Wp_Eval_Sakip_Monev_Kinerja
{
	public function pengisian_rencana_aksi_setting($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/setting-menu/wp-eval-sakip-setting-rencana-aksi.php';
	}

	public function detail_pengisian_rencana_aksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/monev-kinerja/wp-eval-sakip-detail-pengisian-rencana-aksi-per-skpd.php';
	}

	public function tagging_rincian_sakip($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/monev-kinerja/wp-eval-sakip-tagging-rincian.php';
	}

	public function input_rencana_aksi_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/monev-kinerja/wp-eval-sakip-input-rencana-aksi-pemda.php';
	}

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
							$tahun_anggaran = $_POST['tahun_anggaran'];
							$_prefix_opd = $_POST['tipe_pokin'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_pokin'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					if ($_prefix_opd == '') {
						$data_renaksi = array();
					} else if ($_prefix_opd == '_opd') {
						$data_renaksi = $wpdb->get_results($wpdb->prepare(
							"
							SELECT 
								a.*
							FROM esakip_data_rencana_aksi_opd a
							WHERE 
								a.parent=%d AND 
								a.level=%d AND 
								a.active=%d AND 
								a.id_skpd=%d AND
								a.active=1 AND
								a.tahun_anggaran=%d
							ORDER BY a.id
						",
							$_POST['parent'],
							$_POST['level'],
							1,
							$id_skpd,
							$tahun_anggaran
						), ARRAY_A);
					}

					foreach ($data_renaksi as $key => $val) {
						$data_renaksi[$key]['pokin'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									o.id_pokin,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_opd AS o
						        INNER JOIN esakip_pohon_kinerja_opd AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_opd = %d
						            AND o.level_rhk_opd = %d
						            AND o.level_pokin = 2
									AND o.active=1
									AND p.active=1
						    ", $val['id'], $val['level']),
							ARRAY_A
						);
						$data_renaksi[$key]['pokin_3'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									o.id_pokin,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_opd AS o
						        INNER JOIN esakip_pohon_kinerja_opd AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_opd = %d
						            AND o.level_rhk_opd = %d
						            AND o.level_pokin = 3
									AND o.active=1
									AND p.active=1
						    ", $val['id'], $val['level']),
							ARRAY_A
						);
						$data_renaksi[$key]['pokin_4'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									o.id_pokin,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_opd AS o
						        INNER JOIN esakip_pohon_kinerja_opd AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_opd = %d
						            AND o.level_rhk_opd = %d
						            AND o.level_pokin = 4
									AND o.active=1
									AND p.active=1
						    ", $val['id'], $val['level']),
							ARRAY_A
						);
						$data_renaksi[$key]['pokin_5'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									o.id_pokin,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_opd AS o
						        INNER JOIN esakip_pohon_kinerja_opd AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_opd = %d
						            AND o.level_rhk_opd = %d
						            AND o.level_pokin = 5
									AND o.active=1
									AND p.active=1
						    ", $val['id'], $val['level']),
							ARRAY_A
						);

						$data_renaksi[$key]['detail_pegawai'] = $wpdb->get_row($wpdb->prepare(
							"
							SELECT
								*
							FROM esakip_data_pegawai_simpeg
							WHERE nip_baru = %d
							AND active=1
						",
							$val['nip']
						), ARRAY_A);
						$data_renaksi[$key]['detail_satker'] = $wpdb->get_row($wpdb->prepare(
							"
							SELECT
								*
							FROM esakip_data_satker_simpeg
							WHERE satker_id = %d
							AND active=1
						",
							$val['satker_id']
						), ARRAY_A);
						// print_r($get_pegawai); die($wpdb->last_query);
						// mengambil data dari level 4 untuk menampilkan di level / tipe 3
						$data_renaksi[$key]['get_data_dasar_4'] = $wpdb->get_results($wpdb->prepare("
					        SELECT
					            *
					        FROM esakip_data_rencana_aksi_opd
					        WHERE parent=%d
					          AND level=4
					          AND active=1
					    ", $val['id']));
						if (!empty($data_renaksi[$key]['get_data_dasar_4'])) {
							foreach ($data_renaksi[$key]['get_data_dasar_4'] as $key4 => $val4) {
								$data_renaksi[$key]['get_data_dasar_4'][$key4]->get_pagu_4 = $wpdb->get_results($wpdb->prepare("
						            SELECT
						                SUM(rencana_pagu) as total_pagu
						            FROM esakip_data_rencana_aksi_indikator_opd 
						            WHERE id_renaksi=%d
						                AND active = 1
						        ", $val4->id));
							}
							$data_renaksi[$key]['total_pagu_4'] = array_sum(array_map(function ($item) {
								return isset($item->get_pagu_4[0]->total_pagu) ? $item->get_pagu_4[0]->total_pagu : 0;
							}, $data_renaksi[$key]['get_data_dasar_4']));
						}
						// mengambil data dari level 3 untuk menampilkan di level / tipe 2
						$data_renaksi[$key]['get_dasar_level_3'] = $wpdb->get_results($wpdb->prepare("
						    SELECT
						        *
						    FROM esakip_data_rencana_aksi_opd
						    WHERE parent=%d
						      AND level=3
						      AND active=1
						", $val['id']));

						foreach ($data_renaksi[$key]['get_dasar_level_3'] as $key3 => $val3) {
							$data_renaksi[$key]['get_dasar_level_3'][$key3]->get_dasar_level_4 = $wpdb->get_results($wpdb->prepare("
						        SELECT
						            *
						        FROM esakip_data_rencana_aksi_opd
						        WHERE parent=%d
						          AND level=4
						          AND active=1
						    ", $val3->id));

							if (!empty($data_renaksi[$key]['get_dasar_level_3'][$key3]->get_dasar_level_4)) {
								foreach ($data_renaksi[$key]['get_dasar_level_3'][$key3]->get_dasar_level_4 as $keypagu3 => $valpagu3) {
									$data_renaksi[$key]['get_dasar_level_3'][$key3]->get_dasar_level_4[$keypagu3]->get_pagu_3 = $wpdb->get_results($wpdb->prepare("
						                SELECT
						                    SUM(rencana_pagu) as total_pagu
						                FROM esakip_data_rencana_aksi_indikator_opd
						                WHERE id_renaksi=%d
						                  AND active = 1
						            ", $valpagu3->id));
								}
							}
						}

						$data_renaksi[$key]['total_pagu_3'] = array_sum(array_map(function ($item) {
							return isset($item->get_dasar_level_4) ? array_sum(array_map(function ($subitem) {
								return isset($subitem->get_pagu_3[0]->total_pagu) ? $subitem->get_pagu_3[0]->total_pagu : 0;
							}, $item->get_dasar_level_4)) : 0;
						}, $data_renaksi[$key]['get_dasar_level_3']));

						// mengambil data dari level 4 untuk menampilkan di level / tipe 1
						$data_renaksi[$key]['get_dasar_2'] = $wpdb->get_results($wpdb->prepare("
						    SELECT 
						    	*
						    FROM esakip_data_rencana_aksi_opd
						    WHERE parent=%d
						      AND level=2
						      AND active=1
						", $val['id']));

						if (!empty($data_renaksi[$key]['get_dasar_2'])) {
							foreach ($data_renaksi[$key]['get_dasar_2'] as $key2 => $val2) {
								$data_renaksi[$key]['get_dasar_2'][$key2]->get_dasar_to_level_3 = $wpdb->get_results($wpdb->prepare("
						            SELECT 
						            	*
						            FROM esakip_data_rencana_aksi_opd
						            WHERE parent=%d
						              AND level=3
						              AND active=1
						        ", $val2->id));

								if (!empty($data_renaksi[$key]['get_dasar_2'][$key2]->get_dasar_to_level_3)) {
									foreach ($data_renaksi[$key]['get_dasar_2'][$key2]->get_dasar_to_level_3 as $key3 => $val3) {
										$data_renaksi[$key]['get_dasar_2'][$key2]->get_dasar_to_level_3[$key3]->get_dasar_to_level_4 = $wpdb->get_results($wpdb->prepare("
						                    SELECT 
						                    	*
						                    FROM esakip_data_rencana_aksi_opd
						                    WHERE parent=%d
						                      AND level=4
						                      AND active=1
						                ", $val3->id));

										if (!empty($data_renaksi[$key]['get_dasar_2'][$key2]->get_dasar_to_level_3[$key3]->get_dasar_to_level_4)) {
											foreach ($data_renaksi[$key]['get_dasar_2'][$key2]->get_dasar_to_level_3[$key3]->get_dasar_to_level_4 as $keypagu2 => $valpagu2) {
												$data_renaksi[$key]['get_dasar_2'][$key2]->get_dasar_to_level_3[$key3]->get_dasar_to_level_4[$keypagu2]->get_pagu_2 = $wpdb->get_results($wpdb->prepare("
									                SELECT
									                    SUM(rencana_pagu) as total_pagu
									                FROM esakip_data_rencana_aksi_indikator_opd
									                WHERE id_renaksi=%d
									                  AND active = 1
									            ", $valpagu2->id));
											}
										}
									}
								}
							}
						}

						$data_renaksi[$key]['total_pagu_2'] = array_sum(array_map(function ($item) {
							return isset($item->get_dasar_to_level_4) ? array_sum(array_map(function ($subitem) {
								return isset($subitem->get_pagu_2[0]->total_pagu) ? $subitem->get_pagu_2[0]->total_pagu : 0;
							}, $item->get_dasar_to_level_4)) : 0;
						}, $data_renaksi[$key]['get_dasar_2']));
						$data_renaksi[$key]['indikator'] = $wpdb->get_results($wpdb->prepare("
						    SELECT
						        *
						    FROM esakip_data_rencana_aksi_indikator_opd 
						    WHERE id_renaksi=%d
						        AND active = 1
						", $val['id']));

						if (!empty($data_renaksi[$key]['indikator'])) {
							foreach ($data_renaksi[$key]['indikator'] as $indikator_key => $indikator) {
								$data_renaksi[$key]['indikator'][$indikator_key]->bulanan = $wpdb->get_results($wpdb->prepare("
						            SELECT
						                *
						            FROM esakip_data_bulanan_rencana_aksi_opd 
						            WHERE id_indikator_renaksi_opd=%d
						                AND active = 1
						            ORDER BY bulan ASC
						        ", $indikator->id));
							}
						}

						$data_renaksi[$key]['nama_sub_skpd'] = '';
						if (!empty($val['id_sub_skpd_cascading'])) {
							$sub_skpd = $wpdb->get_row(
								$wpdb->prepare(
									"
								SELECT 
									kode_skpd,
									nama_skpd
								FROM esakip_data_unit
								WHERE id_skpd=%d
								AND tahun_anggaran=%d
								AND active = 1
							",
									$val['id_sub_skpd_cascading'],
									get_option(ESAKIP_TAHUN_ANGGARAN)
								),
								ARRAY_A
							);

							if (!empty($sub_skpd)) {
								$data_renaksi[$key]['nama_sub_skpd'] = $sub_skpd['nama_skpd'];
								$data_renaksi[$key]['kode_sub_skpd'] = $sub_skpd['kode_skpd'];
							}
						}
					}
					switch ($_POST['level']) {
						case '2':
							$label_parent = '
							(
								SELECT 
									label 
								FROM esakip_data_rencana_aksi_opd 
								WHERE id=a.id
							) label_parent_1';
							break;

						case '3':
							$label_parent = '
							(
								SELECT 
									label 
								FROM esakip_data_rencana_aksi_opd 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_data_rencana_aksi_opd 
									WHERE id=a.id 
								) 
							) label_parent_1,
							(
								SELECT 
									label 
								FROM esakip_data_rencana_aksi_opd 
								WHERE id=a.id 
							) label_parent_2';
							break;

						case '4':
							$label_parent = '
							(
								SELECT 
									label 
								FROM esakip_data_rencana_aksi_opd 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_data_rencana_aksi_opd 
									WHERE id=(
										SELECT 
											parent 
										FROM esakip_data_rencana_aksi_opd 
										WHERE id=a.id 
									) 
								) 
							) label_parent_1,
							(
								SELECT 
									label 
								FROM esakip_data_rencana_aksi_opd 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_data_rencana_aksi_opd 
									WHERE id=a.id 
								) 
							) label_parent_2,
							(
								SELECT 
									label 
								FROM esakip_data_rencana_aksi_opd 
								WHERE id=a.id 
							) label_parent_3';
							break;

						default:
							$label_parent = '';
							break;
					}

					$dataParent = array();
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
								ORDER BY a.id ASC",
							$_POST['parent'],
							1,
							$id_skpd
						), ARRAY_A);
					}

					$data_parent = array();
					foreach ($dataParent as $v_parent) {

						if (empty($data_parent[$v_parent['label_parent_1']])) {
							$data_parent[$v_parent['label_parent_1']] = $v_parent['label_parent_1'];
						}

						if (empty($data_parent[$v_parent['label_parent_2']])) {
							$data_parent[$v_parent['label_parent_2']] = $v_parent['label_parent_2'];
						}

						if (empty($data_parent[$v_parent['label_parent_3']])) {
							$data_parent[$v_parent['label_parent_3']] = $v_parent['label_parent_3'];
						}

						if (empty($data_parent[$v_parent['label_parent_4']])) {
							$data_parent[$v_parent['label_parent_4']] = $v_parent['label_parent_4'];
						}
					}
					die(json_encode([
						'status' => true,
						'data' => $data_renaksi,
						'data_parent' => array_values($data_parent),
						'sql' => $wpdb->last_query
					]));
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

	function create_renaksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['level'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_pokin_1'])) {
					$ret['status'] = 'error';
					if ($_POST['id_pokin_1'] && ($_POST['level'] == 1)) {
						$ret['message'] = 'Level 1 POKIN tidak boleh kosong!';
					} else if ($_POST['id_pokin_1'] && ($_POST['level'] == 2)) {
						$ret['message'] = 'Level 3 POKIN tidak boleh kosong!';
					} else if ($_POST['id_pokin_1'] && ($_POST['level'] == 3)) {
						$ret['message'] = 'Level 4 POKIN tidak boleh kosong!';
					} else if ($_POST['id_pokin_1'] && ($_POST['level'] == 4)) {
						$ret['message'] = 'Level 5 POKIN tidak boleh kosong!';
					} else if ($_POST['id_pokin_2']) {
						$ret['message'] = 'Level 2 POKIN tidak boleh kosong!';
					}
				} else if ($ret['status'] != 'error' && empty($_POST['label_renaksi'])) {
					$ret['status'] = 'error';
					if ($_POST['level'] == 1) {
						$ret['message'] = 'Kegiatan Utama | RHK LEVEL 1 tidak boleh kosong!';
					} else if ($_POST['level'] == 2) {
						$ret['message'] = 'Rencana Hasil Kerja | RHK LEVEL 2 tidak boleh kosong!';
					} else if ($_POST['level'] == 3) {
						$ret['message'] = 'Uraian Kegiatan | RHK LEVEL 3 tidak boleh kosong!';
					} else if ($_POST['level'] == 4) {
						$ret['message'] = 'Uraian Teknis Kegiatan | RHK LEVEL 4 tidak boleh kosong!';
					}
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

				$kode_cascading_renstra = !empty($_POST['kode_cascading_renstra']) || $_POST['kode_cascading_renstra'] != NULL ? $_POST['kode_cascading_renstra'] : NULL;
				$label_cascading_renstra = !empty($_POST['label_cascading_renstra']) || $_POST['label_cascading_renstra'] != NULL ? $_POST['label_cascading_renstra'] : NULL;


				$kode_cascading_renstra = !empty($_POST['kode_cascading_renstra']) ? $_POST['kode_cascading_renstra'] : NULL;
				$label_cascading_renstra = !empty($_POST['label_cascading_renstra']) ? $_POST['label_cascading_renstra'] : NULL;
				$id_indikator_renaksi_pemda = isset($_POST['id_indikator_renaksi_pemda']) ? intval($_POST['id_indikator_renaksi_pemda']) : 0;
				$id_data_renaksi_pemda = isset($_POST['id_data_renaksi_pemda']) ? intval($_POST['id_data_renaksi_pemda']) : 0;
				$checklist_renaksi_opd = isset($_POST['checklist_renaksi_opd']) ? $_POST['checklist_renaksi_opd'] : [];
				$id_skpd = isset($_POST['id_skpd']) ? intval($_POST['id_skpd']) : 0;
				$id_label_renaksi_opd = isset($_POST['id_label_renaksi_opd']) ? $_POST['id_label_renaksi_opd'] : [];
				$id_indikator = isset($_POST['id_indikator']) ? $_POST['id_indikator'] : [];
				$id_sub_skpd_cascading = !empty($_POST['id_sub_skpd_cascading']) ? $_POST['id_sub_skpd_cascading'] : null;
				$pagu_cascading = !empty($_POST['pagu_cascading']) ? $_POST['pagu_cascading'] : null;


				$get_dasar_pelaksanaan = $_POST['get_dasar_pelaksanaan'];
				if (!empty($label_cascading_renstra) && $_POST['level'] != 1) {
					$label = explode(' ', $label_cascading_renstra);
					unset($label[0]);

					$label_cascading_renstra = implode(' ', $label);
				}
				if (
					$_POST['level'] == 1
					&& $ret['status'] != 'error'
					&& empty($_POST['id_pokin_2'])
				) {
					$ret['status'] = 'error';
					$ret['message'] = 'Level 2 POKIN tidak boleh kosong!';
				}

				if (
					(
						$_POST['level'] == 2
						|| $_POST['level'] == 3
						|| $_POST['level'] == 4
					)
					&& $ret['status'] != 'error'
					&& empty($_POST['parent'])
				) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID parent rencana aksi tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$data = array(
						'label' => $_POST['label_renaksi'],
						'id_skpd' => $_POST['id_skpd'],
						'id_jadwal' => $_POST['id_jadwal'],
						'level' => $_POST['level'],
						'nip' => $_POST['nip'],
						'id_jabatan' => $_POST['satker_id_pegawai'],
						'satker_id' => $_POST['satker_id'],
						'active' => 1,
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						'created_at' => current_time('mysql'),
						'mandatori_pusat' => isset($get_dasar_pelaksanaan['mandatori_pusat']) ? $get_dasar_pelaksanaan['mandatori_pusat'] : 0,
						'inisiatif_kd' => isset($get_dasar_pelaksanaan['inisiatif_kd']) ? $get_dasar_pelaksanaan['inisiatif_kd'] : 0,
						'musrembang' => isset($get_dasar_pelaksanaan['musrembang']) ? $get_dasar_pelaksanaan['musrembang'] : 0,
						'pokir' => isset($get_dasar_pelaksanaan['pokir']) ? $get_dasar_pelaksanaan['pokir'] : 0,
						'id_sub_skpd_cascading' => $id_sub_skpd_cascading,
						'pagu_cascading' => $pagu_cascading
					);
					if ($_POST['level'] == 1) {
						$data['kode_cascading_sasaran'] = $kode_cascading_renstra;
						$data['label_cascading_sasaran'] = $label_cascading_renstra;
						$data['nip'] = $_POST['nip'];
						$data['satker_id'] = $_POST['satker_id'];
					} else if ($_POST['level'] == 2) {
						$data['parent'] = $_POST['parent'];
						$data['kode_cascading_program'] = $kode_cascading_renstra;
						$data['label_cascading_program'] = $label_cascading_renstra;
						$data['nip'] = $_POST['nip'];
						$data['satker_id'] = $_POST['satker_id'];
					} else if ($_POST['level'] == 3) {
						$data['parent'] = $_POST['parent'];
						$data['kode_cascading_kegiatan'] = $kode_cascading_renstra;
						$data['label_cascading_kegiatan'] = $label_cascading_renstra;
						$data['nip'] = $_POST['nip'];
						$data['satker_id'] = $_POST['satker_id'];
					} else if ($_POST['level'] == 4) {
						$data['parent'] = $_POST['parent'];
						$data['kode_cascading_sub_kegiatan'] = $kode_cascading_renstra;
						$data['label_cascading_sub_kegiatan'] = $label_cascading_renstra;
						$data['kode_sbl'] = $_POST['kode_sbl'];
						$data['nip'] = $_POST['nip'];
						$data['satker_id'] = $_POST['satker_id'];
					}
					if (!empty($_POST['id'])) {
						$cek_id = $_POST['id'];
					} else {
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_opd
							WHERE label=%s
								AND active=0
								AND tahun_anggaran=%d
								AND id_skpd=%d
								AND level=%d
								AND id_sub_skpd_cascading=%d
						", $_POST['kegiatan_utama'], $_POST['tahun_anggaran'], $_POST['id_skpd'], $_POST['level'], $id_sub_skpd_cascading));
					}
					if (empty($cek_id)) {
						$wpdb->insert('esakip_data_rencana_aksi_opd', $data);
						$cek_id = $wpdb->insert_id;
					} else {
						$status_update = true;
						if ($_POST['level'] == 2 || $_POST['level'] == 3) {
							/** Untuk validasi agar cascading parent tetap sama dengan cascading child yang telah dipilih */
							$status_update = false;
							$level_child = $_POST['level'] + 1;
							$nama_kolom = array(
								'3' => 'kode_cascading_kegiatan',
								'4' => 'kode_cascading_sub_kegiatan'
							);
							$nama_kolom = $nama_kolom[$level_child];

							$cek_cascading_child = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT
										id,
										id_sub_skpd_cascading,
										$nama_kolom
									FROM 
										esakip_data_rencana_aksi_opd
									WHERE 
										parent=%d
										AND tahun_anggaran=%d
										AND id_skpd=%d
										AND level=%d
										AND active=1
									GROUP BY $nama_kolom
							",
									$_POST['id'],
									$_POST['tahun_anggaran'],
									$_POST['id_skpd'],
									$level_child
								),
								ARRAY_A
							);

							if (!empty($cek_cascading_child)) {
								if (count($cek_cascading_child) == 1 && $cek_cascading_child[0][$nama_kolom] == NULL) {
									$status_update = true;
								} else {
									foreach ($cek_cascading_child as $cek_cas) {
										if (strpos($cek_cas[$nama_kolom], $kode_cascading_renstra) === 0 && $cek_cas['id_sub_skpd_cascading'] === $id_sub_skpd_cascading) {
											$status_update = true; // Jika ada yang cocok, set menjadi true
											break;
										}
									}
								}
							} else {
								$status_update = true;
							}
						}


						if ($status_update) {
							$wpdb->update('esakip_data_rencana_aksi_opd', $data, array('id' => $cek_id));
						} else {
							$nama_kolom = array(
								'2' => 'Program',
								'3' => 'Kegiatan'
							);
							$ret = array(
								'status' => 'error',
								'message'   => 'Data ' . $nama_kolom[$_POST['level']] . ' Cascading Tidak Dapat Diubah! Harap Hapus / Kosongkan / Perbarui Data Cascading Di RHK Level Bawahnya!.'
							);
							die(json_encode($ret));
						}
					}

					$wpdb->update(
						'esakip_data_label_rencana_aksi',
						array(
							'active' => 0,
						),
						array(
							'parent_renaksi_opd' => $cek_id,
							'tahun_anggaran' => $_POST['tahun_anggaran'],
							'id_skpd' => $_POST['id_skpd'],
							'active' => 1
						)
					);
					if (!empty($_POST['checklistDataPemda'])) {
						foreach ($_POST['checklistDataPemda'] as $checklist) {
							$cek = $wpdb->get_var($wpdb->prepare("
	                    		SELECT
									id
								FROM esakip_data_label_rencana_aksi
								WHERE id_skpd=%d
									AND tahun_anggaran=%d
									AND parent_renaksi_opd=%d
									AND parent_renaksi_pemda=%d
									AND parent_indikator_renaksi_pemda=%d
							", $_POST['id_skpd'], $_POST['tahun_anggaran'], $cek_id, $ceklist['id_data_renaksi_pemda'], $ceklist['id_indikator']));
							if (empty($cek)) {
								$wpdb->insert('esakip_data_label_rencana_aksi', array(
									'parent_renaksi_pemda' => $checklist['id_data_renaksi_pemda'],
									'parent_indikator_renaksi_pemda' => $checklist['id_data_indikator'],
									'parent_renaksi_opd' => $cek_id,
									'tahun_anggaran' => $_POST['tahun_anggaran'],
									'id_skpd' => $_POST['id_skpd'],
									'active' => 1,
								));
							} else {
								$wpdb->update(
									'esakip_data_label_rencana_aksi',
									array(
										'active' => 1,
									),
									array('id' => $cek)
								);
							}
						}
					}
					// die($wpdb->last_query);
					$get_id_pokin_1 = $_POST['id_pokin_1'];
					$get_id_pokin_2 = $_POST['id_pokin_2'];

					$wpdb->update(
						'esakip_data_pokin_rhk_opd',
						array('active' => 0),
						array(
							'tahun_anggaran' => $_POST['tahun_anggaran'],
							'id_skpd' => $_POST['id_skpd'],
							'level_rhk_opd' => $_POST['level'],
							'id_rhk_opd' => $cek_id
						)
					);
					foreach ($get_id_pokin_1 as $id_pokin_lvl_1) {
						if ($_POST['level'] == 2) {
							$level = 3;
						} else if ($_POST['level'] == 3) {
							$level = 4;
						} else if ($_POST['level'] == 4) {
							$level = 5;
						} else {
							$level = 1;
						}

						$cek_id_pokin = $wpdb->get_var(
							$wpdb->prepare("
	                            SELECT 
	                                id 
	                            FROM esakip_data_pokin_rhk_opd 
	                            WHERE tahun_anggaran = %d 
	                                AND id_skpd = %s 
	                                AND level_rhk_opd = %s 
	                                AND id_rhk_opd = %s 
	                            	AND id_pokin = %d
	                        ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $_POST['level'], $cek_id, $id_pokin_lvl_1)
						);

						$data = array(
							'id_rhk_opd' => $cek_id,
							'id_pokin' => $id_pokin_lvl_1,
							'level_pokin' => $level,
							'level_rhk_opd' => $_POST['level'],
							'tahun_anggaran' => $_POST['tahun_anggaran'],
							'id_skpd' => $_POST['id_skpd'],
							'active' => 1,
							'update_at' => current_time('mysql')
						);

						if (!empty($cek_id_pokin)) {
							$wpdb->update(
								'esakip_data_pokin_rhk_opd',
								$data,
								array('id' => $cek_id_pokin)
							);
							$ret['message'] = "Berhasil update data.";
						} else {
							$data['created_at'] = current_time('mysql');
							$wpdb->insert('esakip_data_pokin_rhk_opd', $data);
							$ret['message'] = "Berhasil menyimpan data.";
						}
					}
					if (!empty($get_id_pokin_2)) {
						foreach ($get_id_pokin_2 as $id_pokin_lvl_2) {
							$cek_id_pokin = $wpdb->get_var(
								$wpdb->prepare("
		                            SELECT 
		                                id 
		                            FROM esakip_data_pokin_rhk_opd 
		                            WHERE tahun_anggaran = %d 
		                                AND id_skpd = %s 
		                                AND level_rhk_opd = %s 
		                                AND id_rhk_opd = %s 
										AND id_pokin = %d
		                        ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $_POST['level'], $cek_id, $id_pokin_lvl_2)
							);

							$data = array(
								'id_rhk_opd' => $cek_id,
								'id_pokin' => $id_pokin_lvl_2,
								'level_pokin' => 2,
								'level_rhk_opd' => $_POST['level'],
								'tahun_anggaran' => $_POST['tahun_anggaran'],
								'id_skpd' => $_POST['id_skpd'],
								'active' => 1,
								'update_at' => current_time('mysql')
							);

							if (!empty($cek_id_pokin)) {
								$wpdb->update(
									'esakip_data_pokin_rhk_opd',
									$data,
									array('id' => $cek_id_pokin)
								);
								$ret['message'] = "Berhasil update data.";
							} else {
								$data['created_at'] = current_time('mysql');
								$wpdb->insert('esakip_data_pokin_rhk_opd', $data);
								$ret['message'] = "Berhasil menyimpan data.";
							}
						}
					}
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


	function get_rencana_aksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil mendapatkan rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID rencana aksi tidak boleh kosong!';
				} elseif (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} else {
					$ret['data'] = $wpdb->get_row($wpdb->prepare('
	                     SELECT
	                        r.*
	                    FROM esakip_data_rencana_aksi_opd AS r
	                    WHERE r.id = %d 
	                        AND r.tahun_anggaran = %s
	                ', $_POST['id'], $_POST['tahun_anggaran']), ARRAY_A);

					if (!empty($ret['data'])) {
						$ret['data']['renaksi_pemda'] = $wpdb->get_results($wpdb->prepare("
		                	SELECT
		                        p.id AS id_pemda,
		                        p.label AS label_uraian_kegiatan,
		                        p.tahun_anggaran AS tahun_anggaran_pemda,
		                        i.id AS id_indikator,
		                        i.indikator AS label_indikator_uraian_kegiatan,
		                        i.satuan AS satuan_pemda,
		                        i.target_akhir AS target_akhir_pemda,
		                        i.tahun_anggaran AS tahun_anggaran_indikator,
	                        	l.id AS id_label
		                    FROM esakip_data_rencana_aksi_pemda AS p
		                    INNER JOIN esakip_data_rencana_aksi_indikator_pemda AS i ON i.id_renaksi = p.id
	                    	LEFT JOIN esakip_data_label_rencana_aksi AS l ON l.parent_indikator_renaksi_pemda = i.id
	                    		AND l.active=i.active
	                    		AND l.tahun_anggaran=i.tahun_anggaran
	                    		AND l.parent_renaksi_opd=%d
	                    		AND l.id_skpd=i.id_skpd
		                    WHERE i.id_skpd=%d
		                    	AND i.active=1
		                    	AND i.tahun_anggaran=%d
		                ", $ret['data']['id'], $ret['data']['id_skpd'], $_POST['tahun_anggaran']), ARRAY_A);
						$ret['data']['jabatan'] = $wpdb->get_row($wpdb->prepare('
		                	SELECT
		                		*
		                	FROM esakip_data_satker_simpeg
		                	WHERE satker_id = %d		                		
		                ', $ret['data']['satker_id']), ARRAY_A);
						$ret['data']['pegawai'] = $wpdb->get_row($wpdb->prepare('
		                	SELECT
		                		*
		                	FROM esakip_data_pegawai_simpeg
		                	WHERE nip_baru = %d		                		
		                ', $ret['data']['nip']), ARRAY_A);
						$ret['data']['pokin'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									p.id,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_opd AS o
						        INNER JOIN esakip_pohon_kinerja_opd AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_opd = %d
						            AND o.level_rhk_opd = %d
						            AND o.level_pokin = 1
									AND o.active=1
									AND p.active=1
						    ", $ret['data']['id'], $ret['data']['level']),
							ARRAY_A
						);
						$ret['data']['pokin_2'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									p.id,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_opd AS o
						        INNER JOIN esakip_pohon_kinerja_opd AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_opd = %d
						            AND o.level_rhk_opd = %d
						            AND o.level_pokin = 2
									AND o.active=1
									AND p.active=1
						    ", $ret['data']['id'], $ret['data']['level']),
							ARRAY_A
						);
						$ret['data']['pokin_3'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									p.id,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_opd AS o
						        INNER JOIN esakip_pohon_kinerja_opd AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_opd = %d
						            AND o.level_rhk_opd = %d
						            AND o.level_pokin = 3
									AND o.active=1
									AND p.active=1
						    ", $ret['data']['id'], $ret['data']['level']),
							ARRAY_A
						);
						$ret['data']['pokin_4'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									p.id,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_opd AS o
						        INNER JOIN esakip_pohon_kinerja_opd AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_opd = %d
						            AND o.level_rhk_opd = %d
						            AND o.level_pokin = 4
									AND o.active=1
									AND p.active=1
						    ", $ret['data']['id'], $ret['data']['level']),
							ARRAY_A
						);
						$ret['data']['pokin_5'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									p.id,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_opd AS o
						        INNER JOIN esakip_pohon_kinerja_opd AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_opd = %d
						            AND o.level_rhk_opd = %d
						            AND o.level_pokin = 5
									AND o.active=1
									AND p.active=1
						    ", $ret['data']['id'], $ret['data']['level']),
							ARRAY_A
						);
						$data_skpd_cascading = $wpdb->get_row(
							$wpdb->prepare(
								"SELECT 
									kode_skpd,
									nama_skpd
								FROM 
									esakip_data_unit
								WHERE 
									id_skpd=%d
									AND tahun_anggaran=%d
									AND active = 1
							",
								$ret['data']['id_sub_skpd_cascading'],
								get_option(ESAKIP_TAHUN_ANGGARAN)
							),
							ARRAY_A
						);
						$ret['data']['nama_skpd_cascading'] = '';
						if (!empty($data_skpd_cascading)) {
							$ret['data']['nama_skpd_cascading'] = $data_skpd_cascading['nama_skpd'];
						}
					} else {
						$ret['data']['renaksi_pemda'] = array();
						$ret['data']['jabatan'] = array();
						$ret['data']['pegawai'] = array();
						$ret['data']['pokin'] = array();
						$ret['data']['pokin_2'] = array();
						$ret['data']['pokin_3'] = array();
						$ret['data']['pokin_4'] = array();
						$ret['data']['pokin_5'] = array();
					}

					if (empty($ret['data'])) {
						$ret['status'] = 'error';
						$ret['message'] = 'Data tidak ditemukan!';
					}
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message' => 'Api Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message' => 'Format tidak sesuai!'
			);
		}

		die(json_encode($ret));
	}

	function get_indikator_rencana_aksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get indikator rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID indikator tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$ret['data'] = $wpdb->get_row(
						$wpdb->prepare('
							SELECT
								*
							FROM esakip_data_rencana_aksi_indikator_opd
							WHERE id=%d
							  AND tahun_anggaran=%d
						', $_POST['id'], $_POST['tahun_anggaran']),
						ARRAY_A
					);
					$ret['data']['sumber_dana'] = $wpdb->get_results(
						$wpdb->prepare('
							SELECT
								*
							FROM esakip_sumber_dana_indikator
							WHERE id_indikator = %d
							  AND active = 1
						', $_POST['id']),
						ARRAY_A
					);
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

	function hapus_indikator_rencana_aksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus indikator rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID indikator tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$wpdb->update('esakip_data_rencana_aksi_indikator_opd', array(
						'active' => 0
					), array('id' => $_POST['id']));
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

	function hapus_finalisasi_laporan_pk()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus finalisasi laporan PK!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$cek_id = $wpdb->get_var(
					$wpdb->prepare('
						SELECT 
							id
						FROM esakip_finalisasi_tahap_laporan_pk
						WHERE id = %d
						  AND active = 1
					', $_POST['id_tahap'])
				);
				if (empty($cek_id)) {
					$ret['status'] = 'error';
					$ret['message'] = 'Data finalisasi tidak ditemukan!';
					die(json_encode($ret));
				}
				$wpdb->update(
					'esakip_finalisasi_tahap_laporan_pk',
					array('active' => 0),
					array(
						'id' => $cek_id,
						'active' => 1
					)
				);
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

	function edit_finalisasi_laporan_pk()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil edit finalisasi laporan PK!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$cek_id = $wpdb->get_var(
					$wpdb->prepare('
						SELECT 
							id
						FROM esakip_finalisasi_tahap_laporan_pk
						WHERE id = %d
						  AND active = 1
					', $_POST['id_data'])
				);
				if (empty($cek_id)) {
					$ret['status'] = 'error';
					$ret['message'] = 'Data finalisasi tidak ditemukan!';
					die(json_encode($ret));
				}
				$wpdb->update(
					'esakip_finalisasi_tahap_laporan_pk',
					array(
						'nama_tahapan' => $_POST['nama_tahap'],
						'tanggal_dokumen' => $_POST['tanggal_tahap']
					),
					array(
						'id' => $cek_id,
						'active' => 1
					)
				);
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

	function hapus_rencana_aksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID rencana aksi tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tipe'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				$child_level = $_POST['tipe'] + 1;
				if ($ret['status'] != 'error') {
					$cek_child = $wpdb->get_results(
						$wpdb->prepare("
							SELECT
								*
							FROM esakip_data_rencana_aksi_opd
							WHERE level = %d
							  AND parent = %d
							  AND active = 1
						", $child_level, $_POST['id']),
						ARRAY_A
					);
					$cek_indikator = $wpdb->get_results(
						$wpdb->prepare("
							SELECT
								*
							FROM esakip_data_rencana_aksi_indikator_opd
							WHERE id_renaksi = %d
							  AND tahun_anggaran = %d
							  AND active = 1
						", $_POST['id'], $_POST['tahun_anggaran']),
						ARRAY_A
					);
					if (!empty($cek_indikator)) {
						$ret['status'] = 'error';
						$ret['message'] = 'Gagal menghapus, RHK memiliki indikator aktif, Mohon indikator dihapus terlebih dahulu!';
						die(json_encode($ret));
					}
					if (empty($cek_child)) {
						$wpdb->update(
							'esakip_data_rencana_aksi_opd',
							array('active' => 0),
							array('id' => $_POST['id'])
						);
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Gagal menghapus, data di level data di level ' . $child_level . ' harus dihapus dahulu!';
						$ret['child'] = $cek_child;
					}
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

	function create_indikator_renaksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan indikator!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_label'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID label tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['indikator'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Indikator tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['satuan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Satuan tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && !isset($_POST['target_awal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Target awal tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && !isset($_POST['target_akhir'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Target akhir tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && !isset($_POST['target_tw_1'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Target triwulan 1 tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && !isset($_POST['target_tw_2'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Target triwulan 2 tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && !isset($_POST['target_tw_3'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Target triwulan 3 tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && !isset($_POST['target_tw_4'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Target triwulan 4 tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID SKPD tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$data = array(
						'id_renaksi' => $_POST['id_label'],
						'indikator' => $_POST['indikator'],
						'satuan' => $_POST['satuan'],
						'rencana_pagu' => $_POST['rencana_pagu'],
						'realisasi_pagu' => $_POST['realisasi_pagu'],
						'target_awal' => $_POST['target_awal'],
						'target_akhir' => $_POST['target_akhir'],
						'target_1' => $_POST['target_tw_1'],
						'target_2' => $_POST['target_tw_2'],
						'target_3' => $_POST['target_tw_3'],
						'target_4' => $_POST['target_tw_4'],
						'id_skpd' => $_POST['id_skpd'],
						'active' => 1,
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						'created_at' => current_time('mysql'),
						'aspek_rhk' => $_POST['aspek_rhk'],
						'rumus_indikator' => $_POST['rumus_indikator']
					);

					if ($_POST['set_target_teks'] == 1) {
						$data['set_target_teks'] = $_POST['set_target_teks'];
						$data['target_teks_awal'] = $_POST['target_teks_awal'];
						$data['target_teks_akhir'] = $_POST['target_teks_akhir'];
						$data['target_teks_1'] = $_POST['target_teks_tw_1'];
						$data['target_teks_2'] = $_POST['target_teks_tw_2'];
						$data['target_teks_3'] = $_POST['target_teks_tw_3'];
						$data['target_teks_4'] = $_POST['target_teks_tw_4'];
					} else {
						$data['set_target_teks'] = 0;
						$data['target_teks_awal'] = NULL;
						$data['target_teks_akhir'] = NULL;
						$data['target_teks_1'] = NULL;
						$data['target_teks_2'] = NULL;
						$data['target_teks_3'] = NULL;
						$data['target_teks_4'] = NULL;
					}
					if (empty($_POST['id_label_indikator'])) {
						$total_pagu_renaksi = $wpdb->get_var($wpdb->prepare("
					        SELECT 
					        	SUM(rencana_pagu)
					        FROM esakip_data_rencana_aksi_indikator_opd
					        WHERE id_renaksi = %d 
					        	AND tahun_anggaran = %d 
					        	AND id_skpd = %d 
					        	AND active = 1
					    ", $_POST['id_label'], $_POST['tahun_anggaran'], $_POST['id_skpd']));

						$ret['total_pagu'] = $_POST['rencana_pagu_tk'];
						$ret['total_pagu_sebelum_perubahan'] = $total_pagu_renaksi;
						$total_pagu_renaksi += $_POST['rencana_pagu'];

						$ret['total_pagu_setelah_perubahan'] = $total_pagu_renaksi;
						$ret['total_all_pagu'] = $ret['total_pagu_sebelum_perubahan'] - $ret['total_pagu'];

						if (!empty($_POST['rencana_pagu_tk']) && ($total_pagu_renaksi > $_POST['rencana_pagu_tk'])) {
							$ret['status'] = 'error';
							$ret['message'] = 'Total rencana pagu tidak boleh melebihi 100% atau total pagu tersisa setelah diinput adalah  ' . $ret['total_all_pagu'] . '';
						}
						if ($ret['status'] == 'success') {
							$cek_id = $wpdb->get_var($wpdb->prepare("
								SELECT
									id
								FROM esakip_data_rencana_aksi_indikator_opd
								WHERE indikator=%s
									AND active=0
									AND tahun_anggaran=%d
									AND id_skpd=%d
							", $_POST['indikator'], $_POST['tahun_anggaran'], $_POST['id_skpd']));
						}
					} else {
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_indikator_opd
							WHERE id=%d
						", $_POST['id_label_indikator']));
						$ret['message'] = "Berhasil edit indikator!";
					}
					if ($ret['status'] == 'success') {
						$total_rencana_pagu = 0;
						if (empty($cek_id)) {
							// Insert data indikator baru
							$wpdb->insert('esakip_data_rencana_aksi_indikator_opd', $data);
							$id_indikator_baru = $wpdb->insert_id;

							// Input sumber dana dan rencana pagu (jika subkeg)
							if ($_POST['tipe'] == 4) {
								if (isset($_POST['sumber_danas'])) {
									foreach ($_POST['sumber_danas'] as $v) {
										$nama_dana = $v['nama_dana'];
										$parts = explode(' ', $nama_dana, 2);
										$kode = $parts[0];
										$nama = isset($parts[1]) ? $parts[1] : '';

										$data_sumber_dana = [
											'id_indikator'   => $id_indikator_baru,
											'id_sumber_dana' => $v['id_dana'],
											'kode_dana'      => $kode,
											'nama_dana'      => $nama,
											'rencana_pagu'   => $v['pagu'],
											'tahun_anggaran' => $_POST['tahun_anggaran']
										];
										$total_rencana_pagu += $v['pagu'];
										$wpdb->insert('esakip_sumber_dana_indikator', $data_sumber_dana);
									}
									$wpdb->update(
										'esakip_data_rencana_aksi_indikator_opd',
										['rencana_pagu' => $total_rencana_pagu],
										['id' => $id_indikator_baru]
									);
								}
							}
						} else {
							$wpdb->update(
								'esakip_data_rencana_aksi_indikator_opd',
								$data,
								['id' => $cek_id]
							);

							if ($_POST['tipe'] == 4) {
								$wpdb->update(
									'esakip_sumber_dana_indikator',
									['active' => 0],
									['id_indikator' => $cek_id]
								);

								// Input sumber dana dan rencana pagu yang baru
								if (!empty($_POST['sumber_danas'])) {
									foreach ($_POST['sumber_danas'] as $v) {
										$nama_dana = $v['nama_dana'];
										$parts = explode(' ', $nama_dana, 2);
										$kode = $parts[0];
										$nama = isset($parts[1]) ? $parts[1] : '';

										$data_sumber_dana = [
											'id_indikator'   => $cek_id,
											'id_sumber_dana' => $v['id_dana'],
											'kode_dana'      => $kode,
											'nama_dana'      => $nama,
											'rencana_pagu'   => $v['pagu'],
											'tahun_anggaran' => $_POST['tahun_anggaran']
										];
										$total_rencana_pagu += $v['pagu'];

										$wpdb->insert('esakip_sumber_dana_indikator', $data_sumber_dana);
									}
									$wpdb->update(
										'esakip_data_rencana_aksi_indikator_opd',
										['rencana_pagu' => $total_rencana_pagu],
										['id' => $cek_id]
									);
								}
							}
						}
					}
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

	function get_table_input_rencana_aksi()
	{
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
				if ($ret['status'] != 'error') {
					$data = $wpdb->get_results($wpdb->prepare("
						SELECT
							*
						FROM esakip_data_rencana_aksi_opd
						WHERE id_skpd=%d
							AND tahun_anggaran=%d
							AND active=1
							AND level=1
					", $_POST['id_skpd'], $_POST['tahun_anggaran']), ARRAY_A);
					$html = '';
					$data_all = array(
						'total' => 0,
						'data' => array()
					);
					// kegiatan utama
					foreach ($data as $v) {
						$indikator = $wpdb->get_results($wpdb->prepare("
							SELECT
								*
							FROM esakip_data_rencana_aksi_indikator_opd
							WHERE id_renaksi=%d
								AND active=1
						", $v['id']), ARRAY_A);
						$data_all['data'][$v['id']] = array(
							'detail' => $v,
							'data' => array(),
							'indikator' => $indikator
						);
						$data2 = $wpdb->get_results($wpdb->prepare("
							SELECT
								*
							FROM esakip_data_rencana_aksi_opd
							WHERE id_skpd=%d
								AND tahun_anggaran=%d
								AND active=1
								AND level=2
								AND parent=%d
						", $_POST['id_skpd'], $_POST['tahun_anggaran'], $v['id']), ARRAY_A);

						// rencana aksi
						foreach ($data2 as $v2) {
							$indikator = $wpdb->get_results($wpdb->prepare("
								SELECT
									*
								FROM esakip_data_rencana_aksi_indikator_opd
								WHERE id_renaksi=%d
									AND active=1
							", $v2['id']), ARRAY_A);
							$data_all['data'][$v['id']]['data'][$v2['id']] = array(
								'detail' => $v2,
								'data' => array(),
								'indikator' => $indikator
							);
							$data3 = $wpdb->get_results($wpdb->prepare("
								SELECT
									*
								FROM esakip_data_rencana_aksi_opd
								WHERE id_skpd=%d
									AND tahun_anggaran=%d
									AND active=1
									AND level=3
									AND parent=%d
							", $_POST['id_skpd'], $_POST['tahun_anggaran'], $v2['id']), ARRAY_A);

							// uraian rencana aksi
							foreach ($data3 as $v3) {
								$indikator = $wpdb->get_results($wpdb->prepare("
									SELECT
										*
									FROM esakip_data_rencana_aksi_indikator_opd
									WHERE id_renaksi=%d
										AND active=1
								", $v3['id']), ARRAY_A);
								$data_all['data'][$v['id']]['data'][$v2['id']]['data'][$v3['id']] = array(
									'detail' => $v3,
									'data' => array(),
									'indikator' => $indikator
								);
								$data4 = $wpdb->get_results($wpdb->prepare("
									SELECT
										*
									FROM esakip_data_rencana_aksi_opd
									WHERE id_skpd=%d
										AND tahun_anggaran=%d
										AND active=1
										AND level=4
										AND parent=%d
								", $_POST['id_skpd'], $_POST['tahun_anggaran'], $v3['id']), ARRAY_A);

								// uraian teknis kegiatan
								foreach ($data4 as $v4) {
									$indikator = $wpdb->get_results($wpdb->prepare("
										SELECT
											*
										FROM esakip_data_rencana_aksi_indikator_opd
										WHERE id_renaksi=%d
											AND active=1
									", $v4['id']), ARRAY_A);
									$data_all['data'][$v['id']]['data'][$v2['id']]['data'][$v3['id']]['data'][$v4['id']] = array(
										'detail' => $v4,
										'data' => array(),
										'indikator' => $indikator
									);
								}
							}
						}
					}

					$rincian_tagging = $this->functions->generatePage(array(
						'nama_page' => 'Halaman Tagging Rincian Belanja',
						'content' => '[tagging_rincian_sakip]',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$no = 0;
					$no_renaksi = 0;
					$no_uraian_renaksi = 0;
					$no_uraian_teknis = 0;
					foreach ($data_all['data'] as $v) {
						$no++;
						$no_renaksi = 0;
						$indikator_html = array();
						$satuan_html = array();
						$target_awal_html = array();
						$target_akhir_html = array();
						$target_1_html = array();
						$target_2_html = array();
						$target_3_html = array();
						$target_4_html = array();
						$rencana_pagu_html = array();
						$realisasi_pagu_html = array();
						$realisasi_1_html = array();
						$realisasi_2_html = array();
						$realisasi_3_html = array();
						$realisasi_4_html = array();
						$capaian_realisasi = array();
						$total_harga_tagging_rincian = 0;
						$total_realisasi_tagging_rincian = 0;

						$set_pagu_renaksi = get_option('_crb_set_pagu_renaksi');
						foreach ($v['indikator'] as $key => $ind) {
							$indikator_html[$key] = '<a href="' . $this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $_POST['tahun_anggaran'] . '&id_skpd=' . $_POST['id_skpd'] . '&id_indikator=' . $ind['id']) . '" target="_blank">' . $ind['indikator'] . '</a>';
							$satuan_html[$key] = $ind['satuan'];
							$target_awal_html[$key] = $ind['target_awal'];
							$target_akhir_html[$key] = $ind['target_akhir'];
							$target_1_html[$key] = $ind['target_1'];
							$target_2_html[$key] = $ind['target_2'];
							$target_3_html[$key] = $ind['target_3'];
							$target_4_html[$key] = $ind['target_4'];
							$rencana_pagu_html[$key] = ($set_pagu_renaksi == 1) ? 0 : (!empty($ind['rencana_pagu']) ? number_format((float)$ind['rencana_pagu'], 0, ",", ".") : 0);
							$realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
							$realisasi_1_html[$key] = !empty($ind['realisasi_tw_1']) ? $ind['realisasi_tw_1'] : 0;
							$realisasi_2_html[$key] = !empty($ind['realisasi_tw_2']) ? $ind['realisasi_tw_2'] : 0;
							$realisasi_3_html[$key] = !empty($ind['realisasi_tw_3']) ? $ind['realisasi_tw_3'] : 0;
							$realisasi_4_html[$key] = !empty($ind['realisasi_tw_4']) ? $ind['realisasi_tw_4'] : 0;
							$total_realisasi_tw = $ind['realisasi_tw_1'] + $ind['realisasi_tw_2'] + $ind['realisasi_tw_3'] + $ind['realisasi_tw_4'];
							if(!empty($total_realisasi_tw) && !empty($ind['target_akhir'])){
								$capaian_realisasi[$key] = number_format(($total_realisasi_tw / $ind['target_akhir']) * 100, 0 ) . "%";
							}else{
								$capaian_realisasi[$key] = "0%";
							}
							
							$data_tagging = $wpdb->get_results(
								$wpdb->prepare("
									SELECT * 
									FROM esakip_tagging_rincian_belanja 
									WHERE active = 1 
									  AND id_skpd = %d
									  AND id_indikator = %d
									  AND kode_sbl = %s
								", $v['detail']['id_skpd'], $ind['id'], $v['detail']['kode_sbl']),
								ARRAY_A
							);

							if(!empty($data_tagging)){
								foreach ($data_tagging as $value) {
									$harga_satuan = $value['harga_satuan'];
									$volume = $value['volume'];
									$total_harga_tagging_rincian += $volume * $harga_satuan;
									$total_realisasi_tagging_rincian += $value['realisasi'];
								}
							}
						}
						$indikator_html = implode('<br>', $indikator_html);
						$satuan_html = implode('<br>', $satuan_html);
						$target_awal_html = implode('<br>', $target_awal_html);
						$target_akhir_html = implode('<br>', $target_akhir_html);
						$target_1_html = implode('<br>', $target_1_html);
						$target_2_html = implode('<br>', $target_2_html);
						$target_3_html = implode('<br>', $target_3_html);
						$target_4_html = implode('<br>', $target_4_html);
						$rencana_pagu_html = implode('<br>', $rencana_pagu_html);
						$realisasi_pagu_html = implode('<br>', $realisasi_pagu_html);
						$keterangan = '';
						$realisasi_1_html = implode('<br>', $realisasi_1_html);
						$realisasi_2_html = implode('<br>', $realisasi_2_html);
						$realisasi_3_html = implode('<br>', $realisasi_3_html);
						$realisasi_4_html = implode('<br>', $realisasi_4_html);
						$capaian_realisasi = implode('<br>', $capaian_realisasi);

						//UPDATE PEGAWAI YANG TIDAK SESUAI SATKER UTAMA NYA
						$satker_id_utama = substr($v['detail']['satker_id'], 0, 2);
						$get_pegawai = $wpdb->get_var($wpdb->prepare("
						    SELECT 
						    	id
						    FROM esakip_data_pegawai_simpeg
						    WHERE nip_baru = %s
						    	AND satker_id LIKE %s
						", $v['detail']['nip'], $satker_id_utama . '%'));

						if (empty($get_pegawai)) {
							$wpdb->update(
								'esakip_data_rencana_aksi_opd',
								array('nip' => ''),
								array('id' => $v['detail']['id'])
							);
							$v['detail']['nip'] = '';
						}

						if (empty($v['detail']['satker_id'])) {
							$keterangan .= '<li>Satuan Kerja Belum Dipilih</li>';
						}
						if (empty($v['detail']['nip'])) {
							$keterangan .= '<li>Pegawai Pelaksana Belum Dipilih</li>';
						}

						if (empty($v['detail']['kode_cascading_sasaran'])) {
							$keterangan .= '<li>Cascading Sasaran Belum dipilih</li>';
						}

						$label_cascading = '';
						if ($v['detail']['label_cascading_sasaran']) {
							$label_cascading = $v['detail']['kode_cascading_sasaran'] .' '. $v['detail']['label_cascading_sasaran'];
						}
						$html .= '
						<tr class="keg-utama">
							<td>' . $no . '</td>
							<td class="ket_rhk">' . $keterangan . '</td>
							<td class="kiri kanan bawah text_blok kegiatan_utama">' . $v['detail']['label'] . '
							    <a href="javascript:void(0)" data-id="' . $v['detail']['id'] . '" data-tipe="1" 
								   class="help-rhk" onclick="help_rhk(' . $v['detail']['id'] . ', 1); return false;" title="Detail">
								   <i class="dashicons dashicons-editor-help"></i>
								</a>
							</td>
							<td class="kiri kanan bawah text_blok indikator_kegiatan_utama">' . $indikator_html . '</td>
							<td class="kiri kanan bawah text_blok recana_aksi"></td>
							<td class="indikator_renaksi"></td>
							<td class="urian_renaksi"></td>
							<td class="indikator_uraian_renaksi"></td>
							<td class="uraian_teknis_kegiatan"></td>
							<td class="indikator_uraian_teknis_kegiatan"></td>
							<td class="text-center satuan_renaksi">' . $satuan_html . '</td>
							<td class="text-center target_awal_urian_renaksi">' . $target_awal_html . '</td>
							<td class="text-center target_tw1_urian_renaksi">' . $target_1_html . '</td>
							<td class="text-center target_tw2_urian_renaksi">' . $target_2_html . '</td>
							<td class="text-center target_tw3_urian_renaksi">' . $target_3_html . '</td>
							<td class="text-center target_tw4_urian_renaksi">' . $target_4_html . '</td>
							<td class="text-center target_akhir_urian_renaksi">' . $target_akhir_html . '</td>
							<td class="text-center">' . $realisasi_1_html . '</td>
							<td class="text-center">' . $realisasi_2_html . '</td>
							<td class="text-center">' . $realisasi_3_html . '</td>
							<td class="text-center">' . $realisasi_4_html . '</td>
							<td class="text-center">' . $capaian_realisasi . '</td>
							<td class="text-right">' . $rencana_pagu_html . '</td>
							<td class="text-right">' . number_format((float)$total_harga_tagging_rincian, 0, ",", ".") . '</td>
							<td class="text-right">' . number_format((float)$total_realisasi_tagging_rincian, 0, ",", ".") . '</td>
							<td class=""></td>
							<td class="text-right"></td>
							<td class="text-left">' . $label_cascading . '</td>
						</tr>
						';

						foreach ($v['data'] as $renaksi) {
							$no_renaksi++;
							$no_uraian_renaksi = 0;
							$indikator_html = array();
							$satuan_html = array();
							$target_awal_html = array();
							$target_akhir_html = array();
							$target_1_html = array();
							$target_2_html = array();
							$target_3_html = array();
							$target_4_html = array();
							$rencana_pagu_html = array();
							$realisasi_pagu_html = array();
							$realisasi_1_html = array();
							$realisasi_2_html = array();
							$realisasi_3_html = array();
							$realisasi_4_html = array();
							$capaian_realisasi = array();
							$set_pagu_renaksi = get_option('_crb_set_pagu_renaksi');
							$total_harga_tagging_rincian = 0;
							$total_realisasi_tagging_rincian = 0;
							foreach ($renaksi['indikator'] as $key => $ind) {
								$indikator_html[$key] = '<a href="' . $this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $_POST['tahun_anggaran'] . '&id_skpd=' . $_POST['id_skpd'] . '&id_indikator=' . $ind['id']) . '" target="_blank">' . $ind['indikator'] . '</a>';
								$satuan_html[$key] = $ind['satuan'];
								$target_awal_html[$key] = $ind['target_awal'];
								$target_akhir_html[$key] = $ind['target_akhir'];
								$target_1_html[$key] = $ind['target_1'];
								$target_2_html[$key] = $ind['target_2'];
								$target_3_html[$key] = $ind['target_3'];
								$target_4_html[$key] = $ind['target_4'];
								$rencana_pagu_html[$key] = ($set_pagu_renaksi == 1) ? 0 : (!empty($ind['rencana_pagu']) ? number_format((float)$ind['rencana_pagu'], 0, ",", ".") : 0);
								$realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
								$realisasi_1_html[$key] = !empty($ind['realisasi_tw_1']) ? $ind['realisasi_tw_1'] : 0;
								$realisasi_2_html[$key] = !empty($ind['realisasi_tw_2']) ? $ind['realisasi_tw_2'] : 0;
								$realisasi_3_html[$key] = !empty($ind['realisasi_tw_3']) ? $ind['realisasi_tw_3'] : 0;
								$realisasi_4_html[$key] = !empty($ind['realisasi_tw_4']) ? $ind['realisasi_tw_4'] : 0;
								$total_realisasi_tw = $ind['realisasi_tw_1'] + $ind['realisasi_tw_2'] + $ind['realisasi_tw_3'] + $ind['realisasi_tw_4'];
								if(!empty($total_realisasi_tw) && !empty($ind['target_akhir'])){
									$capaian_realisasi[$key] = number_format(($total_realisasi_tw / $ind['target_akhir']) * 100, 0 ) . "%";
								}else{
									$capaian_realisasi[$key] = "0%";
								}
								
								$data_tagging = $wpdb->get_results(
									$wpdb->prepare("
										SELECT * 
										FROM esakip_tagging_rincian_belanja 
										WHERE active = 1 
										  AND id_skpd = %d
										  AND id_indikator = %d
										  AND kode_sbl = %s
									", $renaksi['detail']['id_skpd'], $ind['id'], $renaksi['detail']['kode_sbl']),
									ARRAY_A
								);

								if(!empty($data_tagging)){
									foreach ($data_tagging as $value) {
										$harga_satuan = $value['harga_satuan'];
										$volume = $value['volume'];
										$total_harga_tagging_rincian += $volume * $harga_satuan;
										$total_realisasi_tagging_rincian += $value['realisasi'];
									}
								}
							}
							$indikator_html = implode('<br>', $indikator_html);
							$satuan_html = implode('<br>', $satuan_html);
							$target_awal_html = implode('<br>', $target_awal_html);
							$target_akhir_html = implode('<br>', $target_akhir_html);
							$target_1_html = implode('<br>', $target_1_html);
							$target_2_html = implode('<br>', $target_2_html);
							$target_3_html = implode('<br>', $target_3_html);
							$target_4_html = implode('<br>', $target_4_html);
							$rencana_pagu_html = implode('<br>', $rencana_pagu_html);
							$realisasi_pagu_html = implode('<br>', $realisasi_pagu_html);
							$keterangan = '';
							$realisasi_1_html = implode('<br>', $realisasi_1_html);
							$realisasi_2_html = implode('<br>', $realisasi_2_html);
							$realisasi_3_html = implode('<br>', $realisasi_3_html);
							$realisasi_4_html = implode('<br>', $realisasi_4_html);
							$capaian_realisasi = implode('<br>', $capaian_realisasi);

							//UPDATE PEGAWAI YANG TIDAK SESUAI SATKER UTAMA NYA
							$satker_id_utama = substr($renaksi['detail']['satker_id'], 0, 2);
							$get_pegawai = $wpdb->get_var(
								$wpdb->prepare("
									SELECT 
										id
									FROM esakip_data_pegawai_simpeg
									WHERE nip_baru = %s
									  AND satker_id LIKE %s
								", $renaksi['detail']['nip'], $satker_id_utama . '%')
							);

							if (empty($get_pegawai)) {
								$wpdb->update(
									'esakip_data_rencana_aksi_opd',
									array('nip' => ''),
									array('id' => $renaksi['detail']['id'])
								);
								$renaksi['detail']['nip'] = '';
							}

							if (empty($renaksi['detail']['satker_id'])) {
								$keterangan .= '<li>Satuan Kerja Belum Dipilih</li>';
							}
							if (empty($renaksi['detail']['nip'])) {
								$keterangan .= '<li>Pegawai Pelaksana Belum Dipilih</li>';
							}
							if (empty($renaksi['detail']['kode_cascading_program'])) {
								$keterangan .= '<li>Cascading Program Belum dipilih</li>';
							}

							$get_renaksi_pemda = $wpdb->get_results(
								$wpdb->prepare("
									SELECT 
										l.*,
										r.*,
										r.id AS id_renaksi,
										i.*,
										i.id AS id_indikator
									FROM esakip_data_label_rencana_aksi AS l
							        INNER JOIN esakip_data_rencana_aksi_pemda AS r
							            ON r.id = l.parent_renaksi_pemda
							            AND r.tahun_anggaran = l.tahun_anggaran
							            AND r.active = l.active
							        INNER JOIN esakip_data_rencana_aksi_indikator_pemda AS i
							            ON i.id = l.parent_indikator_renaksi_pemda
							            AND i.tahun_anggaran = l.tahun_anggaran
							            AND i.active = l.active
									WHERE l.parent_renaksi_opd = %d 
								", $renaksi['detail']['id']),
								ARRAY_A
							);

							$renaksi_html = '';
							$indikator_renaksi_html = '';
							$satuan_renaksi_html = '';
							$target_renaksi_html = '';
							$target_awal_renaksi_html = '';
							$target_1_renaksi_html = '';
							$target_2_renaksi_html = '';
							$target_3_renaksi_html = '';
							$target_4_renaksi_html = '';
							if (!empty($get_renaksi_pemda)) {
								foreach ($get_renaksi_pemda as $renaksi_pemda) {
									$label_renaksi_pemda = !empty($renaksi_pemda['label']) ? $renaksi_pemda['label'] : '<br>';
									$label_indikator_renaksi_pemda = !empty($renaksi_pemda['indikator']) ? $renaksi_pemda['indikator'] : '<br>';
									$label_satuan_renaksi_pemda = !empty($renaksi_pemda['satuan']) ? $renaksi_pemda['satuan'] : '<br>';
									$label_target_renaksi_pemda = !empty($renaksi_pemda['target_akhir']) ? $renaksi_pemda['target_akhir'] : '<br>';
									$label_target_awal_renaksi_pemda = !empty($renaksi_pemda['target_awal']) ? $renaksi_pemda['target_awal'] : '<br>';
									$label_target_1_renaksi_pemda = !empty($renaksi_pemda['target_1']) ? $renaksi_pemda['target_1'] : '<br>';
									$label_target_2_renaksi_pemda = !empty($renaksi_pemda['target_2']) ? $renaksi_pemda['target_2'] : '<br>';
									$label_target_3_renaksi_pemda = !empty($renaksi_pemda['target_3']) ? $renaksi_pemda['target_3'] : '<br>';
									$label_target_4_renaksi_pemda = !empty($renaksi_pemda['target_4']) ? $renaksi_pemda['target_4'] : '<br>';

									$background_primary = !empty($renaksi_pemda['label']) ? 'bg-primary' : '';
									$renaksi_html .= '<div class="d-flex align-items-center mb-2">';
									$renaksi_html .= '<span class="badge ' . $background_primary . ' text-white text-center" style="margin: auto;">' . $label_renaksi_pemda . '</span>';
									$renaksi_html .= '</div>';

									$background_primary = !empty($renaksi_pemda['indikator']) ? 'bg-primary' : '';
									$indikator_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
									$indikator_renaksi_html .= '<span class="badge ' . $background_primary . ' text-white text-center" style="margin: auto;">' . $label_indikator_renaksi_pemda . '</span>';
									$indikator_renaksi_html .= '</div>';

									$background_primary = !empty($renaksi_pemda['satuan']) ? 'bg-primary' : '';
									$satuan_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
									$satuan_renaksi_html .= '<span class="badge ' . $background_primary . ' text-white text-center" style="margin: auto;">' . $label_satuan_renaksi_pemda . '</span>';
									$satuan_renaksi_html .= '</div>';

									$background_primary = !empty($renaksi_pemda['target_akhir']) ? 'bg-primary' : '';
									$target_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
									$target_renaksi_html .= '<span class="badge ' . $background_primary . ' text-white text-center" style="margin: auto;">' . $label_target_renaksi_pemda . '</span>';
									$target_renaksi_html .= '</div>';

									$background_primary = !empty($renaksi_pemda['target_awal']) ? 'bg-primary' : '';
									$target_awal_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
									$target_awal_renaksi_html .= '<span class="badge ' . $background_primary . ' text-white text-center" style="margin: auto;">' . $label_target_awal_renaksi_pemda . '</span>';
									$target_awal_renaksi_html .= '</div>';

									$background_primary = !empty($renaksi_pemda['target_1']) ? 'bg-primary' : '';
									$target_1_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
									$target_1_renaksi_html .= '<span class="badge ' . $background_primary . ' text-white text-center" style="margin: auto;">' . $label_target_1_renaksi_pemda . '</span>';
									$target_1_renaksi_html .= '</div>';

									$background_primary = !empty($renaksi_pemda['target_2']) ? 'bg-primary' : '';
									$target_2_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
									$target_2_renaksi_html .= '<span class="badge ' . $background_primary . ' text-white text-center" style="margin: auto;">' . $label_target_2_renaksi_pemda . '</span>';
									$target_2_renaksi_html .= '</div>';

									$background_primary = !empty($renaksi_pemda['target_3']) ? 'bg-primary' : '';
									$target_3_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
									$target_3_renaksi_html .= '<span class="badge ' . $background_primary . ' text-white text-center" style="margin: auto;">' . $label_target_3_renaksi_pemda . '</span>';
									$target_3_renaksi_html .= '</div>';

									$background_primary = !empty($renaksi_pemda['target_4']) ? 'bg-primary' : '';
									$target_4_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
									$target_4_renaksi_html .= '<span class="badge ' . $background_primary . ' text-white text-center" style="margin: auto;">' . $label_target_4_renaksi_pemda . '</span>';
									$target_4_renaksi_html .= '</div>';
								}
							}

							$label_cascading = '';
							if ($renaksi['detail']['label_cascading_program']) {
								$label_cascading = $renaksi['detail']['kode_cascading_program'] .' '. $renaksi['detail']['label_cascading_program'];
							}

							$html .= '
							    <tr class="re-naksi">
							        <td>' . $no . '.' . $no_renaksi . '</td>
							        <td class="ket">' . $keterangan . '</td>
							        <td class="kiri kanan bawah text_blok kegiatan_utama"></td>
							        <td class="kiri kanan bawah text_blok indikator_kegiatan_utama"></td>
							        <td class="kiri kanan bawah text_blok recana_aksi">' . $renaksi_html . '' . $renaksi['detail']['label'] . '
							        	<a href="javascript:void(0)" data-id="' . $renaksi['detail']['id'] . '" data-tipe="2" 
										   class="help-rhk" onclick="help_rhk(' . $renaksi['detail']['id'] . ', 2); return false;" title="Detail">
										   <i class="dashicons dashicons-editor-help"></i>
										</a>
							        </td>
							        <td class="indikator_renaksi">' . $indikator_renaksi_html . '' . $indikator_html . '</td>
							        <td class="urian_renaksi"></td>
							        <td class="indikator_uraian_renaksi"></td>
							        <td class="uraian_teknis_kegiatan"></td>
							        <td class="indikator_uraian_teknis_kegiatan"></td>
							        <td class="text-center satuan_renaksi">' . $satuan_renaksi_html . '' . $satuan_html . '</td>
							        <td class="text-center target_awal_urian_renaksi">' . $target_awal_renaksi_html . '' . $target_awal_html . '</td>
							        <td class="text-center target_tw1_urian_renaksi">' . $target_1_renaksi_html . '' . $target_1_html . '</td>
							        <td class="text-center target_tw2_urian_renaksi">' . $target_2_renaksi_html . '' . $target_2_html . '</td>
							        <td class="text-center target_tw3_urian_renaksi">' . $target_3_renaksi_html . '' . $target_3_html . '</td>
							        <td class="text-center target_tw4_urian_renaksi">' . $target_4_renaksi_html . '' . $target_4_html . '</td>
							        <td class="text-center target_akhir_urian_renaksi">' . $target_renaksi_html . '' . $target_akhir_html . '</td>
							        <td class="text-center">' . $realisasi_1_html . '</td>
							        <td class="text-center">' . $realisasi_2_html . '</td>
							        <td class="text-center">' . $realisasi_3_html . '</td>
							        <td class="text-center">' . $realisasi_4_html . '</td>
							        <td class="text-center">' . $capaian_realisasi . '</td>
									<td class="text-right">' . $rencana_pagu_html . '</td>
							        <td class="text-right">' . number_format((float)$total_harga_tagging_rincian, 0, ",", ".") . '</td>
							        <td class="text-right">' . number_format((float)$total_realisasi_tagging_rincian, 0, ",", ".") . '</td>
							        <td class=""></td>
									<td class="text-right">' . number_format((float)$renaksi['detail']['pagu_cascading'], 0, ",", ".") . '</td>
							        <td class="text-left">' . $label_cascading . '</td>
							    </tr>
							';


							foreach ($renaksi['data'] as $uraian_renaksi) {
								$no_uraian_renaksi++;
								$no_uraian_teknis = 0;
								$indikator_html = array();
								$satuan_html = array();
								$target_awal_html = array();
								$target_akhir_html = array();
								$target_1_html = array();
								$target_2_html = array();
								$target_3_html = array();
								$target_4_html = array();
								$rencana_pagu_html = array();
								$realisasi_pagu_html = array();
								$realisasi_1_html = array();
								$realisasi_2_html = array();
								$realisasi_3_html = array();
								$realisasi_4_html = array();
								$capaian_realisasi = array();
								$set_pagu_renaksi = get_option('_crb_set_pagu_renaksi');
								$total_harga_tagging_rincian = 0;
								$total_realisasi_tagging_rincian = 0;
								foreach ($uraian_renaksi['indikator'] as $key => $ind) {
									$indikator_html[$key] = '<a href="' . $this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $_POST['tahun_anggaran'] . '&id_skpd=' . $_POST['id_skpd'] . '&id_indikator=' . $ind['id']) . '" target="_blank">' . $ind['indikator'] . '</a>';
									$satuan_html[$key] = $ind['satuan'];
									$target_awal_html[$key] = $ind['target_awal'];
									$target_akhir_html[$key] = $ind['target_akhir'];
									$target_1_html[$key] = $ind['target_1'];
									$target_2_html[$key] = $ind['target_2'];
									$target_3_html[$key] = $ind['target_3'];
									$target_4_html[$key] = $ind['target_4'];
									$rencana_pagu_html[$key] = ($set_pagu_renaksi == 1) ? 0 : (!empty($ind['rencana_pagu']) ? number_format((float)$ind['rencana_pagu'], 0, ",", ".") : 0);
									$realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
									$realisasi_1_html[$key] = !empty($ind['realisasi_tw_1']) ? $ind['realisasi_tw_1'] : 0;
									$realisasi_2_html[$key] = !empty($ind['realisasi_tw_2']) ? $ind['realisasi_tw_2'] : 0;
									$realisasi_3_html[$key] = !empty($ind['realisasi_tw_3']) ? $ind['realisasi_tw_3'] : 0;
									$realisasi_4_html[$key] = !empty($ind['realisasi_tw_4']) ? $ind['realisasi_tw_4'] : 0;
									$total_realisasi_tw = $ind['realisasi_tw_1'] + $ind['realisasi_tw_2'] + $ind['realisasi_tw_3'] + $ind['realisasi_tw_4'];
									if(!empty($total_realisasi_tw) && !empty($ind['target_akhir'])){
										$capaian_realisasi[$key] = number_format(($total_realisasi_tw / $ind['target_akhir']) * 100, 0 ) . "%";
									}else{
										$capaian_realisasi[$key] = "0%";
									}
									
									$data_tagging = $wpdb->get_results(
										$wpdb->prepare("
											SELECT * 
											FROM esakip_tagging_rincian_belanja 
											WHERE active = 1 
											  AND id_skpd = %d
											  AND id_indikator = %d
											  AND kode_sbl = %s
										", $uraian_renaksi['detail']['id_skpd'], $ind['id'], $uraian_renaksi['detail']['kode_sbl']),
										ARRAY_A
									);

									if(!empty($data_tagging)){
										foreach ($data_tagging as $value) {
											$harga_satuan = $value['harga_satuan'];
											$volume = $value['volume'];
											$total_harga_tagging_rincian += $volume * $harga_satuan;
											$total_realisasi_tagging_rincian += $value['realisasi'];
										}
									}
								}
								$indikator_html = implode('<br>', $indikator_html);
								$satuan_html = implode('<br>', $satuan_html);
								$target_awal_html = implode('<br>', $target_awal_html);
								$target_akhir_html = implode('<br>', $target_akhir_html);
								$target_1_html = implode('<br>', $target_1_html);
								$target_2_html = implode('<br>', $target_2_html);
								$target_3_html = implode('<br>', $target_3_html);
								$target_4_html = implode('<br>', $target_4_html);
								$rencana_pagu_html = implode('<br>', $rencana_pagu_html);
								$realisasi_pagu_html = implode('<br>', $realisasi_pagu_html);
								$realisasi_1_html = implode('<br>', $realisasi_1_html);
								$realisasi_2_html = implode('<br>', $realisasi_2_html);
								$realisasi_3_html = implode('<br>', $realisasi_3_html);
								$realisasi_4_html = implode('<br>', $realisasi_4_html);
								$capaian_realisasi = implode('<br>', $capaian_realisasi);

								$label_pokin = $uraian_renaksi['detail']['label_pokin_5'];
								if (empty($label_pokin)) {
									$label_pokin = $uraian_renaksi['detail']['label_pokin_4'];
								}
								$keterangan = '';

								//UPDATE PEGAWAI YANG TIDAK SESUAI SATKER UTAMA NYA
								$satker_id_utama = substr($uraian_renaksi['detail']['satker_id'], 0, 2);
								$get_pegawai = $wpdb->get_var(
									$wpdb->prepare("
										SELECT 
											id
										FROM esakip_data_pegawai_simpeg
										WHERE nip_baru = %s
										  AND satker_id LIKE %s
									", $uraian_renaksi['detail']['nip'], $satker_id_utama . '%')
								);

								if (empty($get_pegawai)) {
									$wpdb->update(
										'esakip_data_rencana_aksi_opd',
										array('nip' => ''),
										array('id' => $uraian_renaksi['detail']['id'])
									);
									$uraian_renaksi['detail']['nip'] = '';
								}
								if (empty($uraian_renaksi['detail']['satker_id'])) {
									$keterangan .= '<li>Satuan Kerja Belum Dipilih</li>';
								}
								if (empty($uraian_renaksi['detail']['nip'])) {
									$keterangan .= '<li>Pegawai Pelaksana Belum Dipilih</li>';
								}
								if (empty($uraian_renaksi['detail']['kode_cascading_kegiatan'])) {
									$keterangan .= '<li>Cascading Kegiatan Belum dipilih</li>';
								}
								$label_cascading = '';
								if ($uraian_renaksi['detail']['label_cascading_kegiatan']) {
									$label_cascading = $uraian_renaksi['detail']['kode_cascading_kegiatan'] .' '. $uraian_renaksi['detail']['label_cascading_kegiatan'];
								}
								$html .= '
								<tr class="ur-kegiatan">
									<td>' . $no . '.' . $no_renaksi . '.' . $no_uraian_renaksi . '</td>
									<td class="">' . $keterangan . '</td>
									<td class="kiri kanan bawah text_blok kegiatan_utama"></td>
									<td class="kiri kanan bawah text_blok indikator_kegiatan_utama"></td>
									<td class="kiri kanan bawah text_blok recana_aksi"></td>
									<td class="kiri kanan bawah text_blok indikator_renaksi"></td>
									<td class="urian_renaksi">' . $uraian_renaksi['detail']['label'] . '
										<a href="javascript:void(0)" data-id="' . $uraian_renaksi['detail']['id'] . '" data-tipe="3" 
										   class="help-rhk" onclick="help_rhk(' . $uraian_renaksi['detail']['id'] . ', 3); return false;" title="Detail">
										   <i class="dashicons dashicons-editor-help"></i>
										</a>
									</td>
									<td class="indikator_uraian_renaksi">' . $indikator_html . '</td>
									<td class="uraian_teknis_kegiatan"></td>
									<td class="indikator_uraian_teknis_kegiatan"></td>
									<td class="text-center satuan_renaksi">' . $satuan_html . '</td>
									<td class="text-center target_awal_urian_renaksi">' . $target_awal_html . '</td>
									<td class="text-center target_tw1_urian_renaksi">' . $target_1_html . '</td>
									<td class="text-center target_tw2_urian_renaksi">' . $target_2_html . '</td>
									<td class="text-center target_tw3_urian_renaksi">' . $target_3_html . '</td>
									<td class="text-center target_tw4_urian_renaksi">' . $target_4_html . '</td>
									<td class="text-center target_akhir_urian_renaksi">' . $target_akhir_html . '</td>
									<td class="text-center">' . $realisasi_1_html . '</td>
									<td class="text-center">' . $realisasi_2_html . '</td>
									<td class="text-center">' . $realisasi_3_html . '</td>
									<td class="text-center">' . $realisasi_4_html . '</td>
									<td class="text-center">' . $capaian_realisasi . '</td>	
									<td class="text-right">' . $rencana_pagu_html . '</td>
									<td class="text-right">' . number_format((float)$total_harga_tagging_rincian, 0, ",", ".") . '</td>
									<td class="text-right">' . number_format((float)$total_realisasi_tagging_rincian, 0, ",", ".") . '</td>
									<td class=""></td>
									<td class="text-right">' . number_format((float)$uraian_renaksi['detail']['pagu_cascading'], 0, ",", ".") . '</td>
									<td class="text-left">' . $label_cascading . '</td>
								</tr>
								';

								foreach ($uraian_renaksi['data'] as $uraian_teknis_kegiatan) {
									$no_uraian_teknis++;
									$indikator_html = array();
									$satuan_html = array();
									$target_awal_html = array();
									$target_akhir_html = array();
									$target_1_html = array();
									$target_2_html = array();
									$target_3_html = array();
									$target_4_html = array();
									$realisasi_1_html = array();
									$realisasi_2_html = array();
									$realisasi_3_html = array();
									$realisasi_4_html = array();
									$capaian_realisasi = array();
									$rencana_pagu_html = array();
									$realisasi_pagu_html = array();
									$set_pagu_renaksi = get_option('_crb_set_pagu_renaksi');
									$total_harga_tagging_rincian = 0;
									$total_realisasi_tagging_rincian = 0;
									foreach ($uraian_teknis_kegiatan['indikator'] as $key => $ind) {
										$indikator_html[$key] = '<a href="' . $this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $_POST['tahun_anggaran'] . '&id_skpd=' . $_POST['id_skpd'] . '&id_indikator=' . $ind['id']) . '" target="_blank">' . $ind['indikator'] . '</a>';
										$satuan_html[$key] = $ind['satuan'];
										$target_awal_html[$key] = $ind['target_awal'];
										$target_akhir_html[$key] = $ind['target_akhir'];
										$target_1_html[$key] = $ind['target_1'];
										$target_2_html[$key] = $ind['target_2'];
										$target_3_html[$key] = $ind['target_3'];
										$target_4_html[$key] = $ind['target_4'];
										$rencana_pagu_html[$key] = ($set_pagu_renaksi == 1) ? 0 : (!empty($ind['rencana_pagu']) ? number_format((float)$ind['rencana_pagu'], 0, ",", ".") : 0);;
										$realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
										$realisasi_1_html[$key] = !empty($ind['realisasi_tw_1']) ? $ind['realisasi_tw_1'] : 0;
										$realisasi_2_html[$key] = !empty($ind['realisasi_tw_2']) ? $ind['realisasi_tw_2'] : 0;
										$realisasi_3_html[$key] = !empty($ind['realisasi_tw_3']) ? $ind['realisasi_tw_3'] : 0;
										$realisasi_4_html[$key] = !empty($ind['realisasi_tw_4']) ? $ind['realisasi_tw_4'] : 0;
										$total_realisasi_tw = $ind['realisasi_tw_1'] + $ind['realisasi_tw_2'] + $ind['realisasi_tw_3'] + $ind['realisasi_tw_4'];
										if(!empty($total_realisasi_tw) && !empty($ind['target_akhir'])){
											$capaian_realisasi[$key] = number_format(($total_realisasi_tw / $ind['target_akhir']) * 100, 0) . "%";
										}else{
											$capaian_realisasi[$key] = "0%";
										}

										$data_tagging = $wpdb->get_results(
											$wpdb->prepare("
												SELECT * 
												FROM esakip_tagging_rincian_belanja 
												WHERE active = 1 
												  AND id_skpd = %d
												  AND id_indikator = %d
												  AND kode_sbl = %s
											", $uraian_teknis_kegiatan['detail']['id_skpd'], $ind['id'], $uraian_teknis_kegiatan['detail']['kode_sbl']),
											ARRAY_A
										);

										if(!empty($data_tagging)){
											foreach ($data_tagging as $value) {
												$harga_satuan = $value['harga_satuan'];
												$volume = $value['volume'];
												$total_harga_tagging_rincian += $volume * $harga_satuan;
												$total_realisasi_tagging_rincian += $value['realisasi'];
											}
										}
									}
									$indikator_html = implode('<br>', $indikator_html);
									$satuan_html = implode('<br>', $satuan_html);
									$target_awal_html = implode('<br>', $target_awal_html);
									$target_akhir_html = implode('<br>', $target_akhir_html);
									$target_1_html = implode('<br>', $target_1_html);
									$target_2_html = implode('<br>', $target_2_html);
									$target_3_html = implode('<br>', $target_3_html);
									$target_4_html = implode('<br>', $target_4_html);
									$rencana_pagu_html = implode('<br>', $rencana_pagu_html);
									$realisasi_pagu_html = implode('<br>', $realisasi_pagu_html);
									$realisasi_1_html = implode('<br>', $realisasi_1_html);
									$realisasi_2_html = implode('<br>', $realisasi_2_html);
									$realisasi_3_html = implode('<br>', $realisasi_3_html);
									$realisasi_4_html = implode('<br>', $realisasi_4_html);
									$capaian_realisasi = implode('<br>', $capaian_realisasi);

									$label_pokin = $uraian_teknis_kegiatan['detail']['label_pokin_5'];
									if (empty($label_pokin)) {
										$label_pokin = $uraian_teknis_kegiatan['detail']['label_pokin_4'];
									}
									$keterangan = '';

									//UPDATE PEGAWAI YANG TIDAK SESUAI SATKER UTAMA NYA
									$satker_id_utama = substr($uraian_teknis_kegiatan['detail']['satker_id'], 0, 2);
									$get_pegawai = $wpdb->get_var(
										$wpdb->prepare("
											SELECT 
												id
											FROM esakip_data_pegawai_simpeg
											WHERE nip_baru = %s
											  AND satker_id LIKE %s
										", $uraian_teknis_kegiatan['detail']['nip'], $satker_id_utama . '%')
									);

									if (empty($get_pegawai)) {
										$wpdb->update(
											'esakip_data_rencana_aksi_opd',
											array('nip' => ''),
											array('id' => $uraian_teknis_kegiatan['detail']['id'])
										);
										$uraian_teknis_kegiatan['detail']['nip'] = '';
									}
									if (empty($uraian_teknis_kegiatan['detail']['satker_id'])) {
										$keterangan .= '<li>Satuan Kerja Belum Dipilih</li>';
									}
									if (empty($uraian_teknis_kegiatan['detail']['nip'])) {
										$keterangan .= '<li>Pegawai Pelaksana Belum Dipilih</li>';
									}
									if (empty($uraian_teknis_kegiatan['detail']['kode_cascading_sub_kegiatan'])) {
										$keterangan .= '<li>Cascading Sub Kegiatan Belum dipilih</li>';
									}
									$label_cascading = '';
									if ($uraian_teknis_kegiatan['detail']['label_cascading_sub_kegiatan']) {
										$nama_subkeg = implode(" ", array_slice(explode(" ", $uraian_teknis_kegiatan['detail']['label_cascading_sub_kegiatan']), 1));
										$label_cascading = $uraian_teknis_kegiatan['detail']['kode_cascading_sub_kegiatan'] .' '. $nama_subkeg;
									}
									$html .= '
									<tr>
										<td>' . $no . '.' . $no_renaksi . '.' . $no_uraian_renaksi . '.' . $no_uraian_teknis . '</td>
										<td class="">' . $keterangan . '</td>
										<td class="kegiatan_utama"></td>
										<td class="indikator_kegiatan_utama"></td>
										<td class="recana_aksi"></td>
										<td class="indikator_renaksi"></td>
										<td class="urian_renaksi"></td>
										<td class="indikator_uraian_renaksi"></td>
										<td class="uraian_teknis_kegiatan">' . $uraian_teknis_kegiatan['detail']['label'] . '
											<a href="javascript:void(0)" data-id="' . $uraian_teknis_kegiatan['detail']['id'] . '" data-tipe="4" 
											   class="help-rhk" onclick="help_rhk(' . $uraian_teknis_kegiatan['detail']['id'] . ', 4); return false;" title="Detail">
											   <i class="dashicons dashicons-editor-help"></i>
											</a>
										</td>
										<td class="indikator_uraian_teknis_kegiatan">' . $indikator_html . '</td>
										<td class="text-center satuan_renaksi">' . $satuan_html . '</td>
										<td class="text-center target_awal_urian_renaksi">' . $target_awal_html . '</td>
										<td class="text-center target_tw1_urian_renaksi">' . $target_1_html . '</td>
										<td class="text-center target_tw2_urian_renaksi">' . $target_2_html . '</td>
										<td class="text-center target_tw3_urian_renaksi">' . $target_3_html . '</td>
										<td class="text-center target_tw4_urian_renaksi">' . $target_4_html . '</td>
										<td class="text-center target_akhir_urian_renaksi">' . $target_akhir_html . '</td>
										<td class="text-center">' . $realisasi_1_html . '</td>
										<td class="text-center">' . $realisasi_2_html . '</td>
										<td class="text-center">' . $realisasi_3_html . '</td>
										<td class="text-center">' . $realisasi_4_html . '</td>
										<td class="text-center">' . $capaian_realisasi . '</td>
										<td class="text-right">' . $rencana_pagu_html . '</td>
										<td class="text-right">' . number_format((float)$total_harga_tagging_rincian, 0, ",", ".") . '</td>
										<td class="text-right">' . number_format((float)$total_realisasi_tagging_rincian, 0, ",", ".") . '</td>
										<td class=""></td>
										<td class="text-right">' . number_format((float)$uraian_teknis_kegiatan['detail']['pagu_cascading'], 0, ",", ".") . '</td>
										<td class="text-left">' . $label_cascading . '</td>
									</tr>
									';
								}
							}
						}
					}
					if (empty($html)) {
						$html = '<tr><td class="text-center" colspan="18">Data masih kosong!</td></tr>';
					}
					$ret['data'] = $html;

					$renaksi_pemda = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
					            p.*,
					            p.id AS id_data_renaksi_pemda,
					            i.*,
					            i.id AS id_data_indikator,
					            u.*,
					            u.id AS id_data_unit,
					            l.*,
					            l.id AS id_label
					        FROM esakip_data_rencana_aksi_pemda AS p
					        INNER JOIN esakip_data_rencana_aksi_indikator_pemda AS i
					            ON p.id = i.id_renaksi
					            AND p.tahun_anggaran = i.tahun_anggaran
					            AND p.active = i.active
					        INNER JOIN esakip_data_unit AS u
					            ON i.id_skpd = u.id_skpd
					            AND i.tahun_anggaran = u.tahun_anggaran
					            AND i.active = u.active
					        LEFT JOIN esakip_data_label_rencana_aksi AS l
					            ON l.parent_indikator_renaksi_pemda = i.id 
					        WHERE i.id_skpd = %d
					        AND l.id IS NULL
					    ", $_POST['id_skpd']),
						ARRAY_A
					);
					$html_renaksi_pemda = '';

					if (!empty($renaksi_pemda)) {
						foreach ($renaksi_pemda as $k_renaksi_pemda => $v_renaksi_pemda) {

							$aksi = '<a href="javascript:void(0)" class="btn btn-sm btn-success verifikasi-renaksi-pemda" data-label="' . esc_attr($v_renaksi_pemda['label']) . '" data-id_renaksi_pemda="' . esc_attr($v_renaksi_pemda['id_data_renaksi_pemda']) . '" data-id_indikator="' . esc_attr($v_renaksi_pemda['id_data_indikator']) . '" data-indikator="' . esc_attr($v_renaksi_pemda['indikator']) . '" data-satuan="' . esc_attr($v_renaksi_pemda['satuan']) . '" data-target_akhir="' . esc_attr($v_renaksi_pemda['target_akhir']) . '" title="Verifikasi Rencana Aksi"><span class="dashicons dashicons-yes"></span></a>';

							$html_renaksi_pemda .= '
					            <tr>
					                <td class="text-left">' . esc_html($v_renaksi_pemda['label']) . '</td>
					                <td class="text-left">' . esc_html($v_renaksi_pemda['indikator']) . '</td>
					                <td class="text-center">' . esc_html($v_renaksi_pemda['satuan']) . '</td>
					                <td class="text-center">' . esc_html($v_renaksi_pemda['target_akhir']) . '</td>
					                <td>' . $aksi . '</td>
					            </tr>';
						}
					}
					$ret['data_pemda'] = $html_renaksi_pemda;
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

	function get_data_pengaturan_rencana_aksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data pengaturan rencana aksi!',
			'data'  => array(),
			'option_renstra_wpsipd' => ''
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {

					$data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT
								pr.*
							FROM esakip_pengaturan_upload_dokumen as pr
							JOIN esakip_data_jadwal as jj
							ON pr.id_jadwal_rpjpd = jj.id
							WHERE pr.tahun_anggaran=%d
								AND pr.active=1
						", $_POST['tahun_anggaran']),
						ARRAY_A
					);

					if (!empty($data)) {
						$ret['data'] = $data;
					}

					//jadwal renstra wpsipd
					$api_params = array(
						'action' => 'get_data_jadwal_wpsipd',
						'api_key'	=> get_option('_crb_apikey_wpsipd'),
						'tipe_perencanaan' => 'monev_renstra'
					);

					$response = wp_remote_post(get_option('_crb_url_server_sakip'), array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));

					$response = wp_remote_retrieve_body($response);

					$data_jadwal_wpsipd = json_decode($response);

					if (!empty($data_jadwal_wpsipd)) {
						$option_renstra_wpsipd = '<option>Pilih Jadwal RENSTRA WP-SIPD</option>';
						if (!empty($data_jadwal_wpsipd->data)) {
							foreach ($data_jadwal_wpsipd->data as $jadwal_periode_item_wpsipd) {
								if (!empty($jadwal_periode_item_wpsipd->tahun_akhir_anggaran) && $jadwal_periode_item_wpsipd->tahun_akhir_anggaran > 1) {
									$tahun_anggaran_selesai = $jadwal_periode_item_wpsipd->tahun_akhir_anggaran;
								} else {
									$tahun_anggaran_selesai = $jadwal_periode_item_wpsipd->tahun_anggaran + $jadwal_periode_item_wpsipd->lama_pelaksanaan;
								}

								$option_renstra_wpsipd .= '<option value="' . $jadwal_periode_item_wpsipd->id_jadwal_lokal . '">' . $jadwal_periode_item_wpsipd->nama . ' ' . 'Periode ' . $jadwal_periode_item_wpsipd->tahun_anggaran . ' - ' . $tahun_anggaran_selesai . '</option>';
							}
						}
					}

					$ret['option_renstra_wpsipd'] = $option_renstra_wpsipd;
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

	function submit_pengaturan_rencana_aksi()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					if (empty($_POST['tahun_anggaran'])) {
						throw new Exception("Ada data yang kosong!", 1);
					}

					$tahun_anggaran = $_POST['tahun_anggaran'];
					$id_jadwal_renstra_wpsipd = $_POST['id_jadwal_renstra_wpsipd'];
					$input_renaksi = $_POST['input_renaksi'];
					$set_pagu_renaksi = $_POST['set_pagu_renaksi'];

					// pengaturan rencana aksi
					$cek_data_pengaturan = $wpdb->get_var(
						$wpdb->prepare("
						SELECT 
							id
						FROM 
							esakip_pengaturan_upload_dokumen
						WHERE tahun_anggaran=%d
						AND active=1
					", $tahun_anggaran)
					);

					$data = array(
						'id_jadwal_wp_sipd' => $id_jadwal_renstra_wpsipd,
						'tahun_anggaran' => $tahun_anggaran,
						'active' => 1,
						'created_at' => current_time('mysql'),
						'update_at' => current_time('mysql')
					);

					if (empty($cek_data_pengaturan)) {
						$wpdb->insert('esakip_pengaturan_upload_dokumen', $data);
						$message = "Sukses tambah data";
					} else {
						$wpdb->update('esakip_pengaturan_upload_dokumen', $data, array('id' => $cek_data_pengaturan));
						$message = "Sukses edit data";
					}

					update_option('_crb_input_renaksi', $input_renaksi);
					update_option('_crb_set_pagu_renaksi', $set_pagu_renaksi);

					echo json_encode([
						'status' => true,
						'message' => $message,
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

	function copy_data_rencana_aksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil copy data rencana hasil kerja!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran_sumber_rhk'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran Sumber RHK Tidak Boleh Kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Opd Tidak Boleh Kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran_tujuan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran Halaman Ini Tidak Boleh Kosong!';
				}

				if ($ret['status'] != 'error') {
					$this_tahun_anggaran = $_POST['tahun_anggaran_tujuan'];
					$id_skpd = $_POST['id_skpd'];
					$tahun_anggaran_sumber_rhk = $_POST['tahun_anggaran_sumber_rhk'];

					/** Kosongkan tabel data yang akan disii data baru hasil copy */
					$wpdb->update(
						'esakip_data_rencana_aksi_opd',
						array(
							'active' => 0
						),
						array(
							'tahun_anggaran' => $this_tahun_anggaran,
							'id_skpd' => $id_skpd
						)
					);

					$wpdb->update(
						'esakip_data_rencana_aksi_indikator_opd',
						array(
							'active' => 0
						),
						array(
							'tahun_anggaran' => $this_tahun_anggaran,
							'id_skpd' => $id_skpd
						)
					);

					$wpdb->update(
						'esakip_data_pokin_rhk_opd',
						array(
							'active' => 0
						),
						array(
							'tahun_anggaran' => $this_tahun_anggaran,
							'id_skpd' => $id_skpd
						)
					);

					$data_rhk_sumber = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT
								*
							FROM
								esakip_data_rencana_aksi_opd
							WHERE
								id_skpd=%d
								AND tahun_anggaran=%d
								AND active=%d
							',
							$id_skpd,
							$tahun_anggaran_sumber_rhk,
							1
						),
						ARRAY_A
					);

					// copy data rhk
					$tampungan_parent = array();
					if (!empty($data_rhk_sumber)) {
						foreach ($data_rhk_sumber as $ks => $valsum) {
							$parent = $valsum['parent'];
							if ($valsum['level'] > 1 && $valsum['parent'] > 0) {
								$parent = $tampungan_parent[$valsum['parent']];
							}
							$data_param_rhk = array(
								'label' => $valsum['label'],
								'id_skpd' => $valsum['id_skpd'],
								'id_jadwal' => $valsum['id_jadwal'],
								'level' => $valsum['level'],
								'nip' => $valsum['nip'],
								'parent' => $parent,
								'satker_id' => $valsum['satker_id'],
								'mandatori_pusat' => $valsum['mandatori_pusat'],
								'inisiatif_kd' => $valsum['inisiatif_kd'],
								'musrembang' => $valsum['musrembang'],
								'pokir' => $valsum['pokir'],
								'id_sub_skpd_cascading' => $valsum['id_sub_skpd_cascading'],
								'pagu_cascading' => $valsum['pagu_cascading'],
								'kode_cascading_sasaran' => $valsum['kode_cascading_sasaran'],
								'label_cascading_sasaran' => $valsum['label_cascading_sasaran'],
								'kode_cascading_program' => $valsum['kode_cascading_program'],
								'label_cascading_program' => $valsum['label_cascading_program'],
								'kode_cascading_kegiatan' => $valsum['kode_cascading_kegiatan'],
								'label_cascading_kegiatan' => $valsum['label_cascading_kegiatan'],
								'kode_cascading_sub_kegiatan' => $valsum['kode_cascading_sub_kegiatan'],
								'label_cascading_sub_kegiatan' => $valsum['label_cascading_sub_kegiatan'],
								'kode_sbl' => $valsum['kode_sbl'],
								'active' => 1,
								'tahun_anggaran' => $this_tahun_anggaran,
								'created_at' => current_time('mysql'),
								'update_at' => current_time('mysql')
							);

							$wpdb->insert(
								'esakip_data_rencana_aksi_opd',
								$data_param_rhk
							);

							$id_rhk_baru = $wpdb->insert_id;
							$tampungan_parent[$valsum['id']] = $id_rhk_baru;

							// copy pokin rhk
							$data_pokin_rhk_sumber = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT 
										* 
									FROM 
										esakip_data_pokin_rhk_opd 
									WHERE 
										id_rhk_opd=%d
										AND tahun_anggaran=%d 
										AND id_skpd=%d 
										AND active=%d
								",
									$valsum['id'],
									$tahun_anggaran_sumber_rhk,
									$id_skpd,
									1
								),
								ARRAY_A
							);

							if (!empty($data_pokin_rhk_sumber)) {
								foreach ($data_pokin_rhk_sumber as $kp => $valpok) {
									$data_param_pokin = array(
										'id_rhk_opd' => $id_rhk_baru,
										'id_pokin' => $valpok['id_pokin'],
										'level_pokin' => $valpok['level_pokin'],
										'level_rhk_opd' => $valpok['level_rhk_opd'],
										'tahun_anggaran' => $this_tahun_anggaran,
										'id_skpd' => $valpok['id_skpd'],
										'active' => 1,
										'update_at' => current_time('mysql'),
										'created_at' => current_time('mysql')
									);

									$wpdb->insert('esakip_data_pokin_rhk_opd', $data_param_pokin);
								}
							}

							// copy indikator rhk
							$data_indikator_rhk_sumber = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT
										*
									FROM
										esakip_data_rencana_aksi_indikator_opd
									WHERE
										id_renaksi=%d
										AND tahun_anggaran=%d
										AND id_skpd=%d
										AND active=%d
									",
									$valsum['id'],
									$tahun_anggaran_sumber_rhk,
									$id_skpd,
									1
								),
								ARRAY_A
							);

							if (!empty($data_indikator_rhk_sumber)) {
								foreach ($data_indikator_rhk_sumber as $ki => $valin) {
									$data_param_indikator = array(
										'id_renaksi' => $id_rhk_baru,
										'indikator' => $valin['indikator'],
										'satuan' => $valin['satuan'],
										'rencana_pagu' => $valin['rencana_pagu'],
										'realisasi_pagu' => $valin['realisasi_pagu'],
										'target_awal' => $valin['target_awal'],
										'target_akhir' => $valin['target_akhir'],
										'target_1' => $valin['target_1'],
										'target_2' => $valin['target_2'],
										'target_3' => $valin['target_3'],
										'target_4' => $valin['target_4'],
										'aspek_rhk' => $valin['aspek_rhk'],
										'rumus_indikator' => $valin['rumus_indikator'],
										'set_target_teks' => $valin['set_target_teks'],
										'target_teks_awal' => $valin['target_teks_awal'],
										'target_teks_akhir' => $valin['target_teks_akhir'],
										'target_teks_1' => $valin['target_teks_1'],
										'target_teks_2' => $valin['target_teks_2'],
										'target_teks_3' => $valin['target_teks_3'],
										'target_teks_4' => $valin['target_teks_4'],
										'id_skpd' => $valin['id_skpd'],
										'active' => 1,
										'tahun_anggaran' => $this_tahun_anggaran,
										'created_at' => current_time('mysql'),
										'update_at' => current_time('mysql')
									);

									$wpdb->insert('esakip_data_rencana_aksi_indikator_opd', $data_param_indikator);
								}
							}
						}
					}
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

	function get_table_input_iku()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data iku!',
			'data'  => ''
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				
				if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID OPD tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_jadwal_wpsipd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID jadwal tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$skpd = $wpdb->get_row(
						$wpdb->prepare("
						SELECT 
							nama_skpd,
							nipkepala
						FROM esakip_data_unit
						WHERE id_skpd=%d
						AND tahun_anggaran=%d
						AND active = 1
					", $_POST['id_skpd'], get_option(ESAKIP_TAHUN_ANGGARAN)),
						ARRAY_A
					);

					$current_user = wp_get_current_user();
					$nip_kepala = $current_user->data->user_login;
					$user_roles = $current_user->roles;
					$is_admin_panrb = in_array('admin_panrb', $user_roles);
					$is_administrator = in_array('administrator', $user_roles);

					$admin_role_pemda = array(
						'admin_bappeda',
						'admin_ortala'
					);

					$this_jenis_role = (array_intersect($admin_role_pemda, $user_roles)) ? 1 : 2;

					$data_iku = $wpdb->get_results($wpdb->prepare("
						SELECT
							*
						FROM esakip_data_iku_opd
						WHERE id_skpd=%d
							AND id_jadwal_wpsipd=%d
							AND active=1
					", $_POST['id_skpd'], $_POST['id_jadwal_wpsipd']), ARRAY_A);

					$html = '';
					$no = 0;
					if (!empty($data_iku)) {
						foreach ($data_iku as $v) {
							$no++;
							$indikator = explode(" \n- ", $v['label_indikator']);
							$indikator = implode("</br>- ", $indikator);
							$html .= '
							<tr>
								<td class="atas kanan bawah kiri">' . $no . '</td>
								<td class="text-left tujuan-sasaran atas kanan bawah kiri">' . $v['label_sasaran'] . '</td>
								<td class="text-left indikator_sasaran atas kanan bawah kiri">' . $indikator . '</td>
								<td class="text-left formulasi atas kanan bawah kiri">' . $v['formulasi'] . '</td>
								<td class="text-left sumber_data atas kanan bawah kiri">' . $v['sumber_data'] . '</td>
								<td class="text-left penanggung_jawab atas kanan bawah kiri">' . $v['penanggung_jawab'] . '</td>
							';

							$btn = '<div class="btn-action-group">';
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Edit IKU"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Hapus IKU"><span class="dashicons dashicons-trash"></span></button>';
							$btn .= '</div>';
							$hak_akses_user = ($nip_kepala == $skpd['nipkepala'] || $is_administrator || $this_jenis_role == 1) ? true : false;

							if (!$hak_akses_user) {
								$btn = '';
							}

							$html .= "<td class='text-center atas kanan bawah kiri hide-excel'>" . $btn . "</td>";
							$html .= '</tr>';
						}
					}
					if (empty($html)) {
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

	function tambah_iku()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan Iku!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['kode_sasaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tujuan/Sasaran tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['label_indikator'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Indikator tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['formulasi'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Definisi Operasional/Formulasi boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['sumber_data'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Sumber Data tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['penanggung_jawab'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Penanggung_jawab tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID OPD tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_jadwal_wpsipd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Jadwal tidak boleh kosong!';
				}

				if ($ret['status'] != 'error') {
					$data = array(
						'kode_sasaran' => $_POST['kode_sasaran'],
						'label_sasaran' => $_POST['label_tujuan_sasaran'],
						'label_indikator' => $_POST['label_indikator'],
						'formulasi' => $_POST['formulasi'],
						'sumber_data' => $_POST['sumber_data'],
						'penanggung_jawab' => $_POST['penanggung_jawab'],
						'id_skpd' => $_POST['id_skpd'],
						'id_jadwal_wpsipd' => $_POST['id_jadwal_wpsipd'],
						'active' => 1,
						'updated_at' => current_time('mysql'),
					);

					if (!empty($_POST['id_iku'])) {
						$cek_id = $_POST['id_iku'];
						$data_cek_iku = $wpdb->get_results($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_iku_opd
							WHERE id=%d
						", $cek_id), ARRAY_A);

						$cek_id = !empty($data_cek_iku) ? $cek_id : null;
					}

					if (empty($cek_id)) {
						$data['created_at'] = current_time('mysql');

						$wpdb->insert('esakip_data_iku_opd', $data);
					} else {
						$wpdb->update('esakip_data_iku_opd', $data, array('id' => $cek_id));
					}
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


	function get_iku_by_id()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id'])) {
					if (!empty($_POST['tipe']) && $_POST['tipe'] == "pemda") {
						$data = $wpdb->get_row(
							$wpdb->prepare("
								SELECT *
								FROM esakip_data_iku_pemda
								WHERE id = %d
							", $_POST['id']),
							ARRAY_A
						);
					} else {
						$data = $wpdb->get_row(
							$wpdb->prepare("
								SELECT *
								FROM esakip_data_iku_opd
								WHERE id = %d
							", $_POST['id']),
							ARRAY_A
						);
					}
					$ret['data'] = $data;
				} else {
					$ret = array(
						'status' => 'error',
						'message'   => 'Id Kosong!'
					);
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

	public function hapus_iku()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id'])) {
					if (!empty($_POST['tipe']) && $_POST['tipe'] == "pemda") {
						$data_iku_lama = $wpdb->get_var(
							$wpdb->prepare("
								SELECT
									id
								FROM esakip_data_iku_pemda
								WHERE id=%d
							", $_POST['id'])
						);

						if (!empty($data_iku_lama)) {
							$ret['data'] = $wpdb->update(
								'esakip_data_iku_pemda',
								array('active' => 0),
								array('id' => $_POST['id'])
							);
						}
					} else {
						$data_iku_lama = $wpdb->get_var(
							$wpdb->prepare("
								SELECT
									id
								FROM esakip_data_iku_opd
								WHERE id=%d
							", $_POST['id'])
						);

						if (!empty($data_iku_lama)) {
							$ret['data'] = $wpdb->update(
								'esakip_data_iku_opd',
								array('active' => 0),
								array('id' => $_POST['id'])
							);
						}
					}

					if ($wpdb->rows_affected == 0) {
						error_log("Error Hapus IKU: " . $wpdb->last_error);
					}
				} else {
					$ret = array(
						'status' => 'error',
						'message'   => 'Id Kosong!'
					);
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

	function get_sasaran_rpjmd()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$data = $wpdb->get_results(
					$wpdb->prepare("
							SELECT 
								*
							FROM 
								esakip_rpd_sasaran
							WHERE 
								active=1
							order by sasaran_no_urut
						"),
					ARRAY_A
				);
				if (!empty($data)) {
					$ret['data'] = $data;
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
	function get_table_input_iku_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data iku!',
			'data'  => ''
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				
				if ($ret['status'] != 'error' && empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID jadwal tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {

					$data_iku = $wpdb->get_results($wpdb->prepare("
						SELECT
							*
						FROM esakip_data_iku_pemda
						WHERE id_jadwal=%d
							AND active=1
					", $_POST['id_jadwal']), ARRAY_A);

					$html = '';
					$no = 0;
					if (!empty($data_iku)) {
						foreach ($data_iku as $v) {
							$no++;
							$indikator = explode(" \n- ", $v['label_indikator']);
							$indikator = implode("</br>- ", $indikator);
							$html .= '
							<tr>
								<td class="atas kanan bawah kiri">' . $no . '</td>
								<td class="text-left tujuan-sasaran atas kanan bawah kiri">' . $v['label_sasaran'] . '</td>
								<td class="text-left indikator_sasaran atas kanan bawah kiri">' . $indikator . '</td>
								<td class="text-left formulasi atas kanan bawah kiri">' . $v['formulasi'] . '</td>
								<td class="text-left sumber_data atas kanan bawah kiri">' . $v['sumber_data'] . '</td>
								<td class="text-left penanggung_jawab atas kanan bawah kiri">' . $v['penanggung_jawab'] . '</td>
							';

							$btn = '<div class="btn-action-group">';
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Edit IKU"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Hapus IKU"><span class="dashicons dashicons-trash"></span></button>';
							$btn .= '</div>';

							$html .= "<td class='text-center atas kanan bawah kiri hide-excel'>" . $btn . "</td>";
							$html .= '</tr>';
						}
					}
					if (empty($html)) {
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
	function tambah_iku_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan Iku!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_sasaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Sasaran tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_indikator'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Indikator tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['formulasi'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Definisi Operasional/Formulasi boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['sumber_data'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Sumber Data tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['penanggung_jawab'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Penanggung_jawab tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Jadwal tidak boleh kosong!';
				}

				if ($ret['status'] != 'error') {
					$data = array(
						'id_sasaran' => $_POST['id_sasaran'],
						'label_sasaran' => $_POST['label_tujuan_sasaran'],
						'id_unik_indikator' => $_POST['id_indikator'],
						'label_indikator' => $_POST['label_indikator'],
						'formulasi' => $_POST['formulasi'],
						'sumber_data' => $_POST['sumber_data'],
						'penanggung_jawab' => $_POST['penanggung_jawab'],
						'id_jadwal' => $_POST['id_jadwal'],
						'active' => 1,
						'updated_at' => current_time('mysql'),
					);

					if (!empty($_POST['id_iku'])) {
						$cek_id = $_POST['id_iku'];
						$data_cek_iku = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_iku_pemda
							WHERE id=%d
						", $cek_id));

						$cek_id = !empty($data_cek_iku) ? $cek_id : null;
					}

					if (empty($cek_id)) {
						$data['created_at'] = current_time('mysql');

						$wpdb->insert('esakip_data_iku_pemda', $data);
					} else {
						$wpdb->update('esakip_data_iku_pemda', $data, array('id' => $cek_id));
					}
					$ret['data'] = $data;
					$ret['sql'] = $wpdb->last_query;
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

	function get_table_input_rencana_aksi_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data rencana aksi!',
			'data'  => ''
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} elseif ($ret['status'] != 'error' && empty($_POST['id_tujuan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Tujuan tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$data = $wpdb->get_results($wpdb->prepare("
						SELECT
							*
						FROM esakip_data_rencana_aksi_pemda
						WHERE id_tujuan=%d
							AND tahun_anggaran=%d
							AND active=1
							AND level=1
					", $_POST['id_tujuan'], $_POST['tahun_anggaran']), ARRAY_A);
					$html = '';
					$data_all = array(
						'total' => 0,
						'data' => array()
					);
					// kegiatan utama
					foreach ($data as $v) {
						$indikator = $wpdb->get_results($wpdb->prepare("
							SELECT
								*
							FROM esakip_data_rencana_aksi_indikator_pemda
							WHERE id_renaksi=%d
								AND active=1
						", $v['id']), ARRAY_A);
						$data_all['data'][$v['id']] = array(
							'detail' => $v,
							'data' => array(),
							'indikator' => $indikator
						);
						$data2 = $wpdb->get_results($wpdb->prepare("
							SELECT
								*
							FROM esakip_data_rencana_aksi_pemda
							WHERE id_tujuan=%d
								AND tahun_anggaran=%d
								AND active=1
								AND level=2
								AND parent=%d
						", $_POST['id_tujuan'], $_POST['tahun_anggaran'], $v['id']), ARRAY_A);

						// rencana aksi
						foreach ($data2 as $v2) {
							$indikator = $wpdb->get_results($wpdb->prepare("
								SELECT
									*
								FROM esakip_data_rencana_aksi_indikator_pemda
								WHERE id_renaksi=%d
									AND active=1
							", $v2['id']), ARRAY_A);
							$data_all['data'][$v['id']]['data'][$v2['id']] = array(
								'detail' => $v2,
								'data' => array(),
								'indikator' => $indikator
							);
							$data3 = $wpdb->get_results($wpdb->prepare("
								SELECT
									*
								FROM esakip_data_rencana_aksi_pemda
								WHERE id_tujuan=%d
									AND tahun_anggaran=%d
									AND active=1
									AND level=3
									AND parent=%d
							", $_POST['id_tujuan'], $_POST['tahun_anggaran'], $v2['id']), ARRAY_A);

							// uraian rencana aksi
							foreach ($data3 as $v3) {
								$indikator = $wpdb->get_results($wpdb->prepare("
							        SELECT
							            s.*,
							            u.nama_skpd,
							            u.id_skpd
							        FROM esakip_data_rencana_aksi_indikator_pemda AS s
							        INNER JOIN esakip_data_unit as u on s.id_skpd=u.id_skpd
					            		AND s.tahun_anggaran=u.tahun_anggaran
					            		AND s.active=u.active
							        WHERE s.id_renaksi = %d
							            AND s.active=u.active
							    ", $v3['id']), ARRAY_A);

								$data_all['data'][$v['id']]['data'][$v2['id']]['data'][$v3['id']] = array(
									'detail' => $v3,
									'data' => array(),
									'indikator' => $indikator
								);
							}
						}
					}


					$no = 0;
					foreach ($data_all['data'] as $v) {
						$no++;
						$no_renaksi = 0;
						$indikator_html = array();
						$satuan_html = array();
						$target_awal_html = array();
						$target_akhir_html = array();
						$target_1_html = array();
						$target_2_html = array();
						$target_3_html = array();
						$target_4_html = array();

						foreach ($v['indikator'] as $key => $ind) {
							$indikator_html[$key] = $ind['indikator'];
							$satuan_html[$key] = $ind['satuan'];
							$target_awal_html[$key] = $ind['target_awal'];
							$target_akhir_html[$key] = $ind['target_akhir'];
							$target_1_html[$key] = $ind['target_1'];
							$target_2_html[$key] = $ind['target_2'];
							$target_3_html[$key] = $ind['target_3'];
							$target_4_html[$key] = $ind['target_4'];
						}

						$indikator_html = implode('<br>', $indikator_html);
						$satuan_html = implode('<br>', $satuan_html);
						$target_awal_html = implode('<br>', $target_awal_html);
						$target_akhir_html = implode('<br>', $target_akhir_html);
						$target_1_html = implode('<br>', $target_1_html);
						$target_2_html = implode('<br>', $target_2_html);
						$target_3_html = implode('<br>', $target_3_html);
						$target_4_html = implode('<br>', $target_4_html);

						$html .= '
					    <tr class="keg-utama">
					        <td>' . $no . '</td>
							<td class="kiri kanan bawah text_blok kegiatan_utama"><b>' . $v['detail']['label'] . '
							    <a href="javascript:void(0)" data-id="' . $v['detail']['id'] . '" data-tipe="1" 
								   class="help-rhk-pemda" onclick="help_rhk_pemda(' . $v['detail']['id'] . ', 1); return false;" title="Detail">
								   <i class="dashicons dashicons-editor-help"></i>
								</a>
							</td>
					        <td class="indikator_kegiatan_utama"><b>' . $indikator_html . '</td>
					        <td class="recana_aksi"><b></td>
					        <td class="urian_renaksi"><b></td>
					        <td class="text-center satuan_renaksi"><b>' . $satuan_html . '</td>
					        <td class="indikator_renaksi"><b></td>
					        <td class="text-right target_akhir_urian_renaksi"><b>' . $target_awal_html . '</td>
					        <td class="text-right target_akhir_urian_renaksi"><b>' . $target_akhir_html . '</td>
					        <td class="text-right target_tw1_urian_renaksi"><b>' . $target_1_html . '</td>
					        <td class="text-right target_tw2_urian_renaksi"><b>' . $target_2_html . '</td>
					        <td class="text-right target_tw3_urian_renaksi"><b>' . $target_3_html . '</td>
					        <td class="text-right target_tw4_urian_renaksi"><b>' . $target_4_html . '</td>
					        <td class="text-right target_akhir_urian_renaksi"><b>' . $target_akhir_html . '</td>
					        <td class="text-right rencana_pagu"></td>
					        <td class="text-left nama_skpd"></td>
					        <td class="text-left mitra_bidang"></td>
					    </tr>
					    ';

						foreach ($v['data'] as $renaksi) {
							$no_renaksi++;
							$no_uraian_renaksi = 0;
							$indikator_html = array();
							$satuan_html = array();
							$target_awal_html = array();
							$target_akhir_html = array();
							$target_1_html = array();
							$target_2_html = array();
							$target_3_html = array();
							$target_4_html = array();
							$rencana_pagu_html = array();
							$realisasi_pagu_html = array();

							foreach ($renaksi['indikator'] as $key => $ind) {
								$indikator_html[$key] = $ind['indikator'];
								$satuan_html[$key] = $ind['satuan'];
								$target_awal_html[$key] = $ind['target_awal'];
								$target_akhir_html[$key] = $ind['target_akhir'];
								$target_1_html[$key] = $ind['target_1'];
								$target_2_html[$key] = $ind['target_2'];
								$target_3_html[$key] = $ind['target_3'];
								$target_4_html[$key] = $ind['target_4'];
							}

							$indikator_html = implode('<br>', $indikator_html);
							$satuan_html = implode('<br>', $satuan_html);
							$target_awal_html = implode('<br>', $target_awal_html);
							$target_akhir_html = implode('<br>', $target_akhir_html);
							$target_1_html = implode('<br>', $target_1_html);
							$target_2_html = implode('<br>', $target_2_html);
							$target_3_html = implode('<br>', $target_3_html);
							$target_4_html = implode('<br>', $target_4_html);

							$html .= '
					        <tr class="re-naksi">
					            <td>' . $no . '.' . $no_renaksi . '</td>
					            <td class="kegiatan_utama"><b><i></td>
					            <td class="indikator_kegiatan_utama"><b><i></td>
								<td class="kiri kanan bawah text_blok recana_aksi"><b><i>' . $renaksi['detail']['label'] . '
								    <a href="javascript:void(0)" data-id="' . $renaksi['detail']['id'] . '" data-tipe="2" 
									   class="help-rhk-pemda" onclick="help_rhk_pemda(' . $renaksi['detail']['id'] . ', 2); return false;" title="Detail">
									   <i class="dashicons dashicons-editor-help"></i>
									</a>
								</td>
					            <td class="urian_renaksi"><b><i></td>
					            <td class="text-center satuan_renaksi"><b><i>' . $satuan_html . '</td>
					            <td class="indikator_renaksi"><b><i>' . $indikator_html . '</td>
					            <td class="text-right target_akhir_urian_renaksi"><b><i>' . $target_akhir_html . '</td>
					            <td class="text-right target_akhir_urian_renaksi"><b><i>' . $target_awal_html . '</td>
					            <td class="text-right target_tw1_urian_renaksi"><b><i>' . $target_1_html . '</td>
					            <td class="text-right target_tw2_urian_renaksi"><b><i>' . $target_2_html . '</td>
					            <td class="text-right target_tw3_urian_renaksi"><b><i>' . $target_3_html . '</td>
					            <td class="text-right target_tw4_urian_renaksi"><b><i>' . $target_4_html . '</td>
					            <td class="text-right target_akhir_urian_renaksi"><b><i>' . $target_akhir_html . '</td>
					            <td class="text-right rencana_pagu"></td>`
					            <td class="text-left nama_skpd"></td>
					            <td class="text-left mitra_bidang"></td>
					        </tr>
					        ';

							foreach ($renaksi['data'] as $uraian_renaksi) {
								$no_uraian_renaksi++;
								$indikator_html = array();
								$satuan_html = array();
								$target_awal_html = array();
								$target_akhir_html = array();
								$target_1_html = array();
								$target_2_html = array();
								$target_3_html = array();
								$target_4_html = array();
								$rencana_pagu_html = array();
								$mitra_bidang_html = array();
								$nama_skpd_html = array();

								$label_pokin = false;

								foreach ($uraian_renaksi['indikator'] as $key => $ind) {
									$indikator_html[$key] = $ind['indikator'];
									$satuan_html[$key] = $ind['satuan'];
									$target_awal_html[$key] = $ind['target_awal'];
									$target_akhir_html[$key] = $ind['target_akhir'];
									$target_1_html[$key] = $ind['target_1'];
									$target_2_html[$key] = $ind['target_2'];
									$target_3_html[$key] = $ind['target_3'];
									$target_4_html[$key] = $ind['target_4'];
									$rencana_pagu_html[$key] = $ind['rencana_pagu'];
									$mitra_bidang_html[$key] = $ind['mitra_bidang'];
									$nama_skpd_html[$key] = $ind['nama_skpd'];
								}

								$indikator_html = implode('<br>', $indikator_html);
								$satuan_html = implode('<br>', $satuan_html);
								$target_awal_html = implode('<br>', $target_awal_html);
								$target_akhir_html = implode('<br>', $target_akhir_html);
								$target_1_html = implode('<br>', $target_1_html);
								$target_2_html = implode('<br>', $target_2_html);
								$target_3_html = implode('<br>', $target_3_html);
								$target_4_html = implode('<br>', $target_4_html);
								$rencana_pagu = !empty($rencana_pagu_html) ? implode('<br>', array_map(function ($item) {
									return number_format((float) $item, 0, ",", ".");
								}, $rencana_pagu_html)) : 0;
								$mitra_bidang_html = implode('<br>', $mitra_bidang_html);
								$nama_skpd_html = implode('<br>', $nama_skpd_html);

								$label_pokin = $uraian_renaksi['detail']['label_pokin_5'];
								if (empty($label_pokin)) {
									$label_pokin = $uraian_renaksi['detail']['label_pokin_4'];
								}

								foreach ($uraian_renaksi['indikator'] as $i => $ind) {
									$label_html = '';
									$label_html =  $uraian_renaksi['detail']['label'];
									if (!$label_pokin_shown) {
										$label_pokin_shown = true;
									}

									$cek = $wpdb->get_var(
										$wpdb->prepare("
											SELECT 
												id
											FROM esakip_data_label_rencana_aksi
											WHERE parent_indikator_renaksi_pemda = %d 
												AND active=1
												AND tahun_anggaran=%d
										", $ind['id'], $_POST['tahun_anggaran'])
									);

									// print_r($cek); die($wpdb->last_query);

									$detail_pengisian_rencana_aksi = $this->functions->generatePage(array(
										'nama_page' => 'Halaman Detail Pengisian Rencana Aksi ' . $_POST['tahun_anggaran'],
										'content' => '[detail_pengisian_rencana_aksi tahun=' . $_POST['tahun_anggaran'] . ']',
										'show_header' => 1,
										'post_status' => 'private'
									));
									$bg = '';
									$link = '';

									if ($cek) {
										$link = "<a href='" . $detail_pengisian_rencana_aksi['url'] . "&id_skpd=" . $ind['id_skpd'] . "' target='_blank'>" . $ind['nama_skpd'] . "</a>";
										$bg = '';
									} else {
										$bg = 'background-color: #ff00002e;';
										$link = $ind['nama_skpd'];
									}
									$html .= '
							        <tr>
							            <td>' . $no . '.' . $no_uraian_renaksi . '.' . ($i + 1) . '</td>
							            <td class="kegiatan_utama"></td>
							            <td class="indikator_kegiatan_utama"></td>
							            <td class="recana_aksi"></td>
							            <td class="kiri kanan bawah text_blok urian_renaksi">' . $uraian_renaksi['detail']['label'] . '
										    <a href="javascript:void(0)" data-id="' . $uraian_renaksi['detail']['id'] . '" data-tipe="3" 
											   class="help-rhk-pemda" onclick="help_rhk_pemda(' . $uraian_renaksi['detail']['id'] . ', 3); return false;" title="Detail">
											   <i class="dashicons dashicons-editor-help"></i>
											</a>
										</td>
							            <td class="text-center">' . $ind['satuan'] . '</td>
							            <td>' . $ind['indikator'] . '</td>
							            <td class="text-center">' . $ind['target_akhir'] . '</td>
							            <td class="text-center">' . $ind['target_awal'] . '</td>
							            <td class="text-center">' . $ind['target_1'] . '</td>
							            <td class="text-center">' . $ind['target_2'] . '</td>
							            <td class="text-center">' . $ind['target_3'] . '</td>
							            <td class="text-center">' . $ind['target_4'] . '</td>
							            <td class="text-center">' . $ind['target_akhir'] . '</td>
									    <td class="text-right">' . number_format((float)$ind['rencana_pagu'], 0, ",", ".") . '</td>
									    <td class="text-center" style="' . $bg . '">' . $link . '</td>
									    <td class="text-left mitra_bidang">' . $ind['mitra_bidang'] . '</td>
							        </tr>';
								}
							}
						}
					}

					if (empty($html)) {
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

	public function get_data_rekening_akun_wp_sipd()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					// if()
					if (!empty($_POST['id_skpd'])) {
						$id_skpd = $_POST['id_skpd'];
					} else {
						throw new Exception("Id Skpd Kosong!", 1);
					}
					if (!empty($_POST['tahun_anggaran'])) {
						$tahun_anggaran = $_POST['tahun_anggaran'];
					} else {
						throw new Exception("Tahun Anggaran Kosong!", 1);
					}
					if (!empty($_POST['kode_sbl'])) {
						$kode_sbl = $_POST['kode_sbl'];
					} else {
						throw new Exception("Kode Sub Kegiatan Kosong!", 1);
					}

					$api_params = array(
						'action' 			=> 'get_rka_sub_keg_akun',
						'api_key'			=> get_option('_crb_apikey_wpsipd'),
						'tahun_anggaran' 	=> $tahun_anggaran,
						'kode_sbl' 			=> $kode_sbl,
						'jenis_data'		=> 'sakip'
					);

					$response = wp_remote_post(get_option('_crb_url_server_sakip'), array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));

					$response = wp_remote_retrieve_body($response);

					if (is_wp_error($response)) {
						echo json_encode([
							'status' => 'gagal',
							'data' => $response,
							'cek' => $api_params
						]);

						exit();
					}

					$response = json_decode($response);

					$data = $response;

					echo json_encode([
						'status' => 'success',
						'data' => $data
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

	public function get_data_rincian_belanja()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					// if()
					if (!empty($_POST['kode_sbl'])) {
						$kode_sbl = $_POST['kode_sbl'];
					} else {
						throw new Exception("Kode Sbl Kosong!", 1);
					}
					if (!empty($_POST['tahun_anggaran'])) {
						$tahun_anggaran = $_POST['tahun_anggaran'];
					} else {
						throw new Exception("Tahun Anggaran Kosong!", 1);
					}
					if (!empty($_POST['kode_akun'])) {
						$kode_akun = $_POST['kode_akun'];
					} else {
						throw new Exception("Kode Akun Kosong!", 1);
					}

					$api_params = array(
						'action' 			=> 'get_data_rincian_belanja_rka',
						'api_key'			=> get_option('_crb_apikey_wpsipd'),
						'tahun_anggaran' 	=> $tahun_anggaran,
						'kode_sbl' 			=> $kode_sbl,
						'kode_akun'			=> $kode_akun
					);

					$response = wp_remote_post(get_option('_crb_url_server_sakip'), array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));

					$response = wp_remote_retrieve_body($response);

					if (is_wp_error($response)) {
						echo json_encode([
							'status' => 'gagal',
							'data' => $response,
							'cek' => $api_params
						]);

						exit();
					}

					$response = json_decode($response);

					$data = $response->data;

					echo json_encode([
						'status' => 'success',
						'data' => $data
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

	public function crate_tagging_rincian_belanja()
	{
		global $wpdb;

		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					if (!empty($_POST['id_indikator_teknis_kegiatan'])) {
						$id_indikator_teknis_kegiatan = $_POST['id_indikator_teknis_kegiatan'];
					} else {
						throw new Exception("Indikator Teknis Kegiatan Tidak Boleh Kosong!", 1);
					}
					if (!empty($_POST['id_uraian_teknis_kegiatan'])) {
						$id_uraian_teknis_kegiatan = $_POST['id_uraian_teknis_kegiatan'];
					} else {
						throw new Exception("Indikator Teknis Kegiatan Tidak Boleh Kosong!", 1);
					}
					if (!empty($_POST['kode_sbl'])) {
						$kode_sbl = $_POST['kode_sbl'];
					} else {
						throw new Exception("Kode Sbl Tidak Boleh kosong!", 1);
					}
					if (!empty($_POST['tahun_anggaran'])) {
						$tahun_anggaran = $_POST['tahun_anggaran'];
					} else {
						throw new Exception("Tahun Anggaran Tidak Boleh Kosong!", 1);
					}
					if (!empty($_POST['id_skpd'])) {
						$id_skpd = $_POST['id_skpd'];
					} else {
						throw new Exception("ID OPD Tidak Boleh Kosong!", 1);
					}

					$data = json_decode(stripslashes($_POST['data']), true);

					$cek = [];
					foreach ($data as $key => $data_tagging) {
						$data = array(
							'id_uraian_teknis_kegiatan' => $id_uraian_teknis_kegiatan,
							'id_indikator_teknis_kegiatan' => $id_indikator_teknis_kegiatan,
							'kode_sbl' => $kode_sbl,
							'nama_komponen_tagging_rincian' => $data_tagging['uraian_tagging'],
							'koefisien_tagging_rincian' => $data_tagging['volume_satuan_tagging'],
							'rincian_tagging_rincian' => $data_tagging['nilai_tagging'],
							'active' => 1,
							'jenis_tagging' => $data_tagging['jenis_tagging'],
							'tahun_anggaran' => $tahun_anggaran,
							'id_skpd' => $id_skpd,
							'created_at' => current_time('mysql'),
							'update_at' => current_time('mysql')
						);
						// if(!empty($_POST['id'])){
						// 	$cek_id = $_POST['id'];
						// }else{
						// 	$cek_id = $wpdb->get_var($wpdb->prepare("
						// 		SELECT
						// 			id
						// 		FROM esakip_data_rencana_aksi_opd
						// 		WHERE label=%s
						// 			AND active=0
						// 			AND tahun_anggaran=%d
						// 			AND id_skpd=%d
						// 			AND id_pokin_2=%d
						// 	", $_POST['kegiatan_utama'], $_POST['tahun_anggaran'], $_POST['id_skpd'], $_POST['id_pokin_2']));
						// }
						// if(empty($cek_id)){
						$insert = $wpdb->insert('esakip_data_tagging_rincian_belanja', $data);
						array_push($cek, $insert);
						// }else{
						// 	$wpdb->update('esakip_data_rencana_aksi_opd', $data, array('id' => $cek_id));
						// }
					}

					echo json_encode([
						'status' => 'success',
						'data' => $data,
						'cek' => $cek,
						'message' => "Berhasil Menambahkan Tagging Rincian Belanja"
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

	public function get_data_renaksi_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_pemda = '';
					if (!empty($_POST['tipe_pokin'])) {
						$_prefix_pemda = $_POST['tipe_pokin'] == "pemda" ? "_pemda" : "";
					} elseif (empty($_POST['id_tujuan'])) {
						$ret['status'] = 'error';
						$ret['message'] = 'ID Tujuan tidak boleh kosong!';
						die(json_encode($ret));
					}

					$data_renaksi = ($_prefix_pemda == '_pemda') ? $wpdb->get_results($wpdb->prepare("
	                    SELECT 
	                        a.*
	                    FROM esakip_data_rencana_aksi_pemda a
	                    WHERE 
	                        a.parent=%d AND 
	                        a.level=%d AND 
	                        a.active=%d AND 
	                        a.id_tujuan=%d
	                    ORDER BY a.id
	                ", $_POST['parent'], $_POST['level'], 1, $_POST['id_tujuan']), ARRAY_A) : [];

					foreach ($data_renaksi as $key => $val) {
						$data_renaksi[$key]['pokin'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									o.id_pokin,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_pemda AS o
						        INNER JOIN esakip_pohon_kinerja AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_pemda = %d
						            AND o.level_rhk_pemda = %d
						            AND o.level_pokin = 2
									AND o.active=1
									AND p.active=1
						    ", $val['id'], $val['level']),
							ARRAY_A
						);
						// print_r($data_renaksi[$key]['pokin']); die($wpdb->last_query);
						$data_renaksi[$key]['pokin_3'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									o.id_pokin,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_pemda AS o
						        INNER JOIN esakip_pohon_kinerja AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_pemda = %d
						            AND o.level_rhk_pemda = %d
						            AND o.level_pokin = 3
									AND o.active=1
									AND p.active=1
						    ", $val['id'], $val['level']),
							ARRAY_A
						);
						$data_renaksi[$key]['pokin_4'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									o.id_pokin,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_pemda AS o
						        INNER JOIN esakip_pohon_kinerja AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_pemda = %d
						            AND o.level_rhk_pemda = %d
						            AND o.level_pokin = 4
									AND o.active=1
									AND p.active=1
						    ", $val['id'], $val['level']),
							ARRAY_A
						);
						$data_renaksi[$key]['pokin_5'] = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT
									o.id_pokin,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_pemda AS o
						        INNER JOIN esakip_pohon_kinerja AS p 
						            ON o.id_pokin = p.id
						                AND o.level_pokin = p.level
						        WHERE o.id_rhk_pemda = %d
						            AND o.level_rhk_pemda = %d
						            AND o.level_pokin = 5
									AND o.active=1
									AND p.active=1
						    ", $val['id'], $val['level']),
							ARRAY_A
						);
						$data_renaksi[$key]['indikator'] = $wpdb->get_results($wpdb->prepare("
	                        SELECT
	                            s.*,	
						        u.id_skpd,
						        u.nama_skpd,
						        u.active,
						        u.tahun_anggaran
	                        FROM esakip_data_rencana_aksi_indikator_pemda AS s
	                        LEFT JOIN esakip_data_unit as u ON s.id_skpd=u.id_skpd
	                            AND s.tahun_anggaran=u.tahun_anggaran
	                            AND s.active=u.active
	                        WHERE s.id_renaksi = %d
	                            AND s.active=%d
	                    ", $val['id'], 1));
					}

					switch ($_POST['level']) {
						case '2':
							$label_parent = '
	                        (
	                            SELECT 
	                                label 
	                            FROM esakip_data_rencana_aksi_pemda 
	                            WHERE id=a.id
	                        ) label_parent_1';
							break;

						case '3':
							$label_parent = '
	                        (
	                            SELECT 
	                                label 
	                            FROM esakip_data_rencana_aksi_pemda 
	                            WHERE id=(SELECT parent FROM esakip_data_rencana_aksi_pemda WHERE id=a.id)
	                        ) label_parent_1,
	                        (
	                            SELECT 
	                                label 
	                            FROM esakip_data_rencana_aksi_pemda 
	                            WHERE id=a.id
	                        ) label_parent_2';
							break;

						case '4':
							$label_parent = '
	                        (
	                            SELECT 
	                                label 
	                            FROM esakip_data_rencana_aksi_pemda 
	                            WHERE id=(SELECT parent FROM esakip_data_rencana_aksi_pemda WHERE id=(SELECT parent FROM esakip_data_rencana_aksi_pemda WHERE id=a.id))
	                        ) label_parent_1,
	                        (
	                            SELECT 
	                                label 
	                            FROM esakip_data_rencana_aksi_pemda 
	                            WHERE id=(SELECT parent FROM esakip_data_rencana_aksi_pemda WHERE id=a.id)
	                        ) label_parent_2,
	                        (
	                            SELECT 
	                                label 
	                            FROM esakip_data_rencana_aksi_pemda 
	                            WHERE id=a.id
	                        ) label_parent_3';
							break;

						default:
							$label_parent = '';
							break;
					}

					$dataParent = !empty($label_parent) ? $wpdb->get_results($wpdb->prepare(
						"
	                        SELECT 
	                            " . $label_parent . "
	                        FROM esakip_data_rencana_aksi_pemda a 
	                        WHERE  
	                            a.id=%d AND
	                            a.active=%d
	                        ORDER BY a.id ASC",
						$_POST['parent'],
						1
					), ARRAY_A) : [];

					$data_parent = [];
					foreach ($dataParent as $v_parent) {
						if (!empty($v_parent['label_parent_1'])) $data_parent[$v_parent['label_parent_1']] = $v_parent['label_parent_1'];
						if (!empty($v_parent['label_parent_2'])) $data_parent[$v_parent['label_parent_2']] = $v_parent['label_parent_2'];
						if (!empty($v_parent['label_parent_3'])) $data_parent[$v_parent['label_parent_3']] = $v_parent['label_parent_3'];
					}

					die(json_encode([
						'status' => true,
						'data' => $data_renaksi,
						'data_parent' => array_values($data_parent),
						'sql' => $wpdb->last_query
					]));
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


	function tambah_renaksi_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['label_renaksi'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Kegiatan Utama tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Jadwal tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['level'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Level tidak boleh kosong!';
				}
				if (
					$_POST['level'] == 1
					&& $ret['status'] != 'error'
					&& empty($_POST['id_pokin_2'])
				) {
					$ret['status'] = 'error';
					$ret['message'] = 'Level 2 POKIN tidak boleh kosong!';
				}

				if (
					(
						$_POST['level'] == 2
						|| $_POST['level'] == 3
						|| $_POST['level'] == 4
					)
					&& $ret['status'] != 'error'
					&& empty($_POST['parent'])
				) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID parent rencana aksi tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$data = array(
						'label' => $_POST['label_renaksi'],
						'id_jadwal' => $_POST['id_jadwal'],
						'active' => 1,
						'level' => $_POST['level'],
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						'id_tujuan' => $_POST['id_tujuan'],
						'parent' => $_POST['parent'],
						'created_at' => current_time('mysql'),
					);
					if ($_POST['level'] == 1) {
					} else if ($_POST['level'] == 2) {
						$data['parent'] = $_POST['parent'];
					} else if ($_POST['level'] == 3) {
						$data['parent'] = $_POST['parent'];
					} else if ($_POST['level'] == 4) {
						$data['parent'] = $_POST['parent'];
					}
					if (!empty($_POST['id'])) {
						$cek_id = $_POST['id'];
					} else {
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_pemda
							WHERE label=%s
								AND active=0
								AND tahun_anggaran=%d
								AND id_tujuan=%d
						", $_POST['kegiatan_utama'], $_POST['tahun_anggaran'], $_POST['id_tujuan']));
					}
					if (empty($cek_id)) {
						$wpdb->insert('esakip_data_rencana_aksi_pemda', $data);
						$cek_id = $wpdb->insert_id;
					} else {
						$wpdb->update('esakip_data_rencana_aksi_pemda', $data, array('id' => $cek_id));
					}
					$get_id_pokin_1 = $_POST['id_pokin_1'];
					$get_id_pokin_2 = $_POST['id_pokin_2'];

					$wpdb->update(
						'esakip_data_pokin_rhk_pemda',
						array('active' => 0),
						array(
							'tahun_anggaran' => $_POST['tahun_anggaran'],
							'level_rhk_pemda' => $_POST['level'],
							'id_rhk_pemda' => $cek_id
						)
					);
					foreach ($get_id_pokin_1 as $id_pokin_lvl_1) {
						if ($_POST['level'] == 2) {
							$level = 3;
						} else if ($_POST['level'] == 3) {
							$level = 4;
						} else if ($_POST['level'] == 4) {
							$level = 5;
						} else {
							$level = 1;
						}

						$cek_id_pokin = $wpdb->get_var(
							$wpdb->prepare("
	                            SELECT 
	                                id 
	                            FROM esakip_data_pokin_rhk_pemda 
	                            WHERE tahun_anggaran = %d 
	                                AND level_rhk_pemda = %s 
	                                AND id_rhk_pemda = %s 
	                            	AND id_pokin = %d
	                        ", $_POST['tahun_anggaran'], $_POST['level'], $cek_id, $id_pokin_lvl_1)
						);

						$data = array(
							'id_rhk_pemda' => $cek_id,
							'id_pokin' => $id_pokin_lvl_1,
							'level_pokin' => $level,
							'level_rhk_pemda' => $_POST['level'],
							'tahun_anggaran' => $_POST['tahun_anggaran'],
							'active' => 1,
							'update_at' => current_time('mysql')
						);

						if (!empty($cek_id_pokin)) {
							$wpdb->update(
								'esakip_data_pokin_rhk_pemda',
								$data,
								array('id' => $cek_id_pokin)
							);
							$ret['message'] = "Berhasil update data.";
						} else {
							$data['created_at'] = current_time('mysql');
							$wpdb->insert('esakip_data_pokin_rhk_pemda', $data);
							$ret['message'] = "Berhasil menyimpan data.";
						}
					}
					if (!empty($get_id_pokin_2)) {
						foreach ($get_id_pokin_2 as $id_pokin_lvl_2) {
							$cek_id_pokin = $wpdb->get_var(
								$wpdb->prepare("
		                            SELECT 
		                                id 
		                            FROM esakip_data_pokin_rhk_pemda 
		                            WHERE tahun_anggaran = %d 
		                                AND level_rhk_pemda = %s 
		                                AND id_rhk_pemda = %s 
										AND id_pokin = %d
		                        ", $_POST['tahun_anggaran'], $_POST['level'], $cek_id, $id_pokin_lvl_2)
							);

							$data = array(
								'id_rhk_pemda' => $cek_id,
								'id_pokin' => $id_pokin_lvl_2,
								'level_pokin' => 2,
								'level_rhk_pemda' => $_POST['level'],
								'tahun_anggaran' => $_POST['tahun_anggaran'],
								'active' => 1,
								'update_at' => current_time('mysql')
							);

							if (!empty($cek_id_pokin)) {
								$wpdb->update(
									'esakip_data_pokin_rhk_pemda',
									$data,
									array('id' => $cek_id_pokin)
								);
								$ret['message'] = "Berhasil update data.";
							} else {
								$data['created_at'] = current_time('mysql');
								$wpdb->insert('esakip_data_pokin_rhk_pemda', $data);
								$ret['message'] = "Berhasil menyimpan data.";
							}
						}
					}
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

	function tambah_indikator_renaksi_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan indikator!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_label'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID label tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['indikator']) && $_POST['tipe'] == 1 && $_POST['tipe'] == 2) {
					$ret['status'] = 'error';
					$ret['message'] = 'Indikator tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['satuan']) && $_POST['tipe'] == 2 && $_POST['tipe'] == 3) {
					$ret['status'] = 'error';
					$ret['message'] = 'Satuan tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_skpd']) && $_POST['tipe'] == 3) {
					$ret['status'] = 'error';
					$ret['message'] = 'SKPD tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$data = array(
						'id_renaksi' => $_POST['id_label'],
						'indikator' => $_POST['indikator'],
						'satuan' => $_POST['satuan'],
						'rencana_pagu' => $_POST['rencana_pagu'],
						'realisasi_pagu' => $_POST['realisasi_pagu'],
						'target_awal' => $_POST['target_awal'],
						'target_akhir' => $_POST['target_akhir'],
						'target_1' => $_POST['target_tw_1'],
						'target_2' => $_POST['target_tw_2'],
						'target_3' => $_POST['target_tw_3'],
						'target_4' => $_POST['target_tw_4'],
						'active' => 1,
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						'id_skpd' => $_POST['id_skpd'],
						'mitra_bidang' => $_POST['mitra_bidang'],
						'created_at' => current_time('mysql'),
						'id_tujuan' => $_POST['id_tujuan'],
					);
					if (empty($_POST['id_label_indikator'])) {
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_indikator_pemda
							WHERE indikator=%s
								AND active=0
								AND tahun_anggaran=%d
								AND id_tujuan=%d
						", $_POST['indikator'], $_POST['tahun_anggaran'], $_POST['id_tujuan']));
					} else {
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_indikator_pemda
							WHERE id=%d
						", $_POST['id_label_indikator']));
						$ret['message'] = "Berhasil edit indikator!";
					}
					if (empty($cek_id)) {
						$wpdb->insert('esakip_data_rencana_aksi_indikator_pemda', $data);
					} else {
						$wpdb->update('esakip_data_rencana_aksi_indikator_pemda', $data, array('id' => $cek_id));
					}
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

	function hapus_rencana_aksi_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID rencana aksi tidak boleh kosong!';
				} else if (empty($_POST['tipe'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe tidak boleh kosong!';
				} else if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} else if (empty($_POST['id_tujuan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Tujuan tidak boleh kosong!';
				} else {
					$child_level = $_POST['tipe'] + 1;
					$level_name = ($child_level == 2) ? "Rencana Aksi" : "Uraian Kegiatan Rencana Aksi";

					$cek_id = $wpdb->get_var($wpdb->prepare("
					SELECT 
						i.*,
						l.*
					FROM esakip_data_rencana_aksi_indikator_pemda AS i
					INNER JOIN esakip_data_label_rencana_aksi AS l
						ON l.parent_indikator_renaksi_pemda=i.id
					WHERE i.id_renaksi = %d 
				", $_POST['id']));
					// print_r($cek_id); die($wpdb->last_query);

					if ($cek_id) {
						$ret['status'] = 'error';
						$ret['message'] = 'Rencana Aksi tidak bisa dihapus karena beberapa indikator sudah terkoneksi dengan Rencana Aksi Perangkat Daerah.';
					} else {
						$ret['data'] = $wpdb->update(
							'esakip_data_rencana_aksi_indikator_pemda',
							array('active' => 0),
							array('id' => $_POST['id'])
						);

						$cek_child = $wpdb->get_results($wpdb->prepare("
						SELECT *
						FROM esakip_data_rencana_aksi_pemda
						WHERE level = %d AND parent = %d AND active = 1
					", $child_level, $_POST['id']), ARRAY_A);

						if (empty($cek_child)) {
							$wpdb->update('esakip_data_rencana_aksi_pemda', array('active' => 0), array('id' => $_POST['id']));
							$wpdb->update('esakip_data_pokin_rhk_pemda', array('active' => 0), array('id_rhk_pemda' => $_POST['id']));
						} else {
							$ret['status'] = 'error';
							$ret['child'] = $cek_child;
							$ret['message'] = 'Gagal menghapus, data di ' . $level_name . ' harus dihapus dahulu!';
						}
					}
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


	function get_rencana_aksi_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID rencana aksi tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_tujuan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Tujuan tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$ret['data'] = $wpdb->get_row($wpdb->prepare('
						SELECT
							*
						FROM esakip_data_rencana_aksi_pemda
						WHERE id=%d
							AND tahun_anggaran=%d
							AND id_tujuan=%d
					', $_POST['id'], $_POST['tahun_anggaran'], $_POST['id_tujuan']), ARRAY_A);
				}
				if (!empty($ret['data'])) {
					$ret['data']['pokin'] = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
								p.id,
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_pemda AS o
					        INNER JOIN esakip_pohon_kinerja AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_pemda = %d
					            AND o.level_rhk_pemda = %d
					            AND o.level_pokin = 1
								AND o.active=1
								AND p.active=1
					    ", $ret['data']['id'], $ret['data']['level']),
						ARRAY_A
					);
					$ret['data']['pokin_2'] = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
								p.id,
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_pemda AS o
					        INNER JOIN esakip_pohon_kinerja AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_pemda = %d
					            AND o.level_rhk_pemda = %d
					            AND o.level_pokin = 2
								AND o.active=1
								AND p.active=1
					    ", $ret['data']['id'], $ret['data']['level']),
						ARRAY_A
					);
					$ret['data']['pokin_3'] = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
								p.id,
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_pemda AS o
					        INNER JOIN esakip_pohon_kinerja AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_pemda = %d
					            AND o.level_rhk_pemda = %d
					            AND o.level_pokin = 3
								AND o.active=1
								AND p.active=1
					    ", $ret['data']['id'], $ret['data']['level']),
						ARRAY_A
					);
					$ret['data']['pokin_4'] = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
								p.id,
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_pemda AS o
					        INNER JOIN esakip_pohon_kinerja AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_pemda = %d
					            AND o.level_rhk_pemda = %d
					            AND o.level_pokin = 4
								AND o.active=1
								AND p.active=1
					    ", $ret['data']['id'], $ret['data']['level']),
						ARRAY_A
					);
				} else {
					$ret['data']['pokin'] = array();
					$ret['data']['pokin_2'] = array();
					$ret['data']['pokin_3'] = array();
					$ret['data']['pokin_4'] = array();
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

	function get_indikator_rencana_aksi_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get indikator rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID indikator tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_tujuan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Tujuan tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$ret['data'] = $wpdb->get_row($wpdb->prepare('
						SELECT
							*
						FROM esakip_data_rencana_aksi_indikator_pemda
						WHERE id=%d
							AND tahun_anggaran=%d
					', $_POST['id'], $_POST['tahun_anggaran'], $_POST['id_tujuan']), ARRAY_A);
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

	function hapus_indikator_rencana_aksi_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus indikator rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$id = trim(htmlspecialchars($_POST['id']));

				if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_tujuan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Tujuan tidak boleh kosong!';
				}
				$cek_id = $wpdb->get_var($wpdb->prepare("
					SELECT 
						i.*,
						l.*
					FROM esakip_data_rencana_aksi_indikator_pemda AS i
					INNER JOIN esakip_data_label_rencana_aksi AS l
						ON l.parent_indikator_renaksi_pemda=i.id
					WHERE i.id = %d 
				", $id));
				if ($cek_id) {
					$ret['status'] = 'confirm';
					$ret['message'] = 'Rencana Aksi tidak bisa dihapus karena sudah sudah terkoneksi dengan Rencana Aksi Perangkat Daerah.';
				}

				if ($ret['status'] != 'confirm') {
					$ret['data'] = $wpdb->update('esakip_data_rencana_aksi_indikator_pemda', array('active' => 0), array(
						'id' => $id
					));
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

	public function get_skpd_renaksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil mengambil data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$data_skpd = $wpdb->get_results($wpdb->prepare('
	                SELECT *
	                FROM esakip_data_unit
	                WHERE tahun_anggaran = %d 
	                	AND active = 1
	            ', $_POST['tahun']), ARRAY_A);

				$ret['data'] = $data_skpd;
			} else {
				$ret['status']  = 'error';
				$ret['message'] = 'API key tidak valid!';
			}
		} else {
			$ret['status']  = 'error';
			$ret['message'] = 'Permintaan tidak valid!';
		}

		echo json_encode($ret);
		wp_die();
	}

	function simpan_renaksi_pemda()
	{
		global $wpdb;

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

				$id_indikator_renaksi_pemda = isset($_POST['id_indikator_renaksi_pemda']) ? intval($_POST['id_indikator_renaksi_pemda']) : 0;
				$id_renaksi_pemda = isset($_POST['id_renaksi_pemda']) ? intval($_POST['id_renaksi_pemda']) : 0;
				$checklist_renaksi_opd = isset($_POST['checklist_renaksi_opd']) ? $_POST['checklist_renaksi_opd'] : [];
				$id_skpd = isset($_POST['id_skpd']) ? intval($_POST['id_skpd']) : 0;
				$id_label_renaksi_opd = isset($_POST['id_label_renaksi_opd']) ? $_POST['id_label_renaksi_opd'] : [];
				$id_indikator = isset($_POST['id_indikator']) ? $_POST['id_indikator'] : [];

				if (!empty($checklist_renaksi_opd) && !empty($id_label_renaksi_opd)) {
					foreach ($checklist_renaksi_opd as $index => $label_renaksi_opd) {
						$parent_renaksi_opd = isset($id_label_renaksi_opd[$index]) ? intval($id_label_renaksi_opd[$index]) : 0;

						$data = array(
							'parent_renaksi_opd' => $parent_renaksi_opd,
							'parent_renaksi_pemda' => $id_renaksi_pemda,
							'parent_indikator_renaksi_pemda' => $id_indikator_renaksi_pemda,
							'id_skpd' => $id_skpd,
							'active' => 1
						);

						$wpdb->insert(
							'esakip_data_label_rencana_aksi',
							$data
						);
					}
					wp_send_json_success('Berhasil simpan rencana aksi.');
				} else {
					wp_send_json_error('Tidak ada label yang dipilih, silahkan pilih minimal 1 .');
				}
			} else {
				wp_send_json_error('API Key tidak sesuai!');
			}
		} else {
			wp_send_json_error('Format data tidak sesuai!');
		}
		die();
	}

	function simpan_bulanan_renaksi_opd()
	{
		global $wpdb;

		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan indikator!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} else if (empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID SKPD tidak boleh kosong!';
				} else if (empty($_POST['bulan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Bulan tidak boleh kosong!';
				} else {
					$data = array(
						'id_indikator_renaksi_opd' => $_POST['id_indikator_renaksi_opd'],
						'id_skpd' => $_POST['id_skpd'],
						'bulan' => $_POST['bulan'],
						'volume' => $_POST['volume'],
						'rencana_aksi' => $_POST['rencana_aksi'],
						'satuan_bulan' => $_POST['satuan_bulan'],
						'realisasi' => $_POST['realisasi'],
						'keterangan' => $_POST['keterangan'],
						'capaian' => $_POST['capaian'],
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						'active' => 1,
						'created_at' => current_time('mysql'),
					);

					// Cek apakah data sudah ada
					$cek_id = $wpdb->get_var($wpdb->prepare("
	                    SELECT id 
	                    FROM esakip_data_bulanan_rencana_aksi_opd
	                    WHERE active = 1
	                        AND id_indikator_renaksi_opd = %d
	                        AND tahun_anggaran = %d
	                        AND id_skpd = %d
	                        AND bulan = %d
	                ", $_POST['id_indikator_renaksi_opd'], $_POST['tahun_anggaran'], $_POST['id_skpd'], $_POST['bulan']));

					if (empty($cek_id)) {
						$wpdb->insert('esakip_data_bulanan_rencana_aksi_opd', $data);
						$ret['message'] = "Berhasil simpan bulanan!";
					} else {
						$wpdb->update(
							'esakip_data_bulanan_rencana_aksi_opd',
							$data,
							array('id' => $cek_id)
						);
						$ret['message'] = "Berhasil update bulanan!";
					}
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'Api Key tidak sesuai!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format tidak sesuai!';
		}

		die(json_encode($ret));
	}

	function simpan_triwulan_renaksi_opd()
	{
		global $wpdb;

		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan indikator!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} else if (empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID SKPD tidak boleh kosong!';
				} else {
					$triwulan = intval($_POST['triwulan']);
					$realisasi = "realisasi_tw_{$triwulan}";
					$keterangan = "ket_tw_{$triwulan}";
					$data = array(
						'id_skpd' => $_POST['id_skpd'],
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						$realisasi => $_POST['realisasi'],
						$keterangan => $_POST['keterangan'],
						'active' => 1,
						'created_at' => current_time('mysql'),
					);

					// Cek apakah data sudah ada
					$cek_id = $wpdb->get_var($wpdb->prepare("
	                    SELECT 
	                        id 
	                    FROM esakip_data_rencana_aksi_indikator_opd
	                    WHERE active = 1
	                        AND id = %d
	                        AND tahun_anggaran = %d
	                        AND id_skpd = %d
	                ", $_POST['id'], $_POST['tahun_anggaran'], $_POST['id_skpd']));

					if (!empty($cek_id)) {
						// Hanya update kolom yang diizinkan
						$wpdb->update(
							'esakip_data_rencana_aksi_indikator_opd',
							$data,
							array('id' => $cek_id)
						);
						$ret['message'] = "Berhasil update triwulan!";
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Data tidak ditemukan!';
					}
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'Api Key tidak sesuai!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format tidak sesuai!';
		}

		die(json_encode($ret));
	}
	function simpan_total_bulanan()
	{
		global $wpdb;

		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan indikator!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} else if (empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID SKPD tidak boleh kosong!';
				} else {
					$data = array(
						'id_skpd' => $_POST['id_skpd'],
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						'realisasi_akhir' => $_POST['realisasi_akhir'],
						'ket_total' => $_POST['ket_total'],
						'active' => 1,
						'created_at' => current_time('mysql'),
					);

					// Cek apakah data sudah ada
					$cek_id = $wpdb->get_var($wpdb->prepare("
	                    SELECT 
	                        id 
	                    FROM esakip_data_rencana_aksi_indikator_opd
	                    WHERE active = 1
	                        AND id = %d
	                        AND tahun_anggaran = %d
	                        AND id_skpd = %d
	                ", $_POST['id'], $_POST['tahun_anggaran'], $_POST['id_skpd']));

					if (!empty($cek_id)) {
						// Hanya update kolom yang diizinkan
						$wpdb->update(
							'esakip_data_rencana_aksi_indikator_opd',
							$data,
							array('id' => $cek_id)
						);
						$ret['message'] = "Berhasil update total!";
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Data tidak ditemukan!';
					}
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'Api Key tidak sesuai!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format tidak sesuai!';
		}

		die(json_encode($ret));
	}

	function get_rencana_hasil_kerja()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					$nip = $tahun_anggaran = '';
					if (!empty($_POST['nip'])) {
						$nip = $_POST['nip'];
					} else {
						throw new Exception("NIP Pegawai Tidak Boleh Kosong!", 1);
					}
					if (!empty($_POST['tahun_anggaran'])) {
						$tahun_anggaran = $_POST['tahun_anggaran'];
					} else {
						throw new Exception("Tahun Anggaran Tidak Boleh Kosong!", 1);
					}

					$data = $wpdb->get_results($wpdb->prepare("
						SELECT
							id,
							label,
							id_skpd,
							parent,
							level,
							tahun_anggaran,
							id_jadwal,
							active,
							created_at,
							update_at,
							label_pokin_1,
							id_pokin_1,
							label_pokin_2,
							id_pokin_2,
							label_pokin_3,
							id_pokin_3,
							label_pokin_4,
							id_pokin_4,
							label_pokin_5,
							id_pokin_5,
							kode_cascading_sasaran,
							kode_cascading_program,
							kode_cascading_kegiatan,
							kode_cascading_sub_kegiatan,
							label_cascading_sasaran,
							label_cascading_program,
							label_cascading_kegiatan,
							label_cascading_sub_kegiatan,
							kode_sbl,
							mandatori_pusat,
							inisiatif_kd,
							musrembang,
							pokir,
							nip,
							satker_id
						FROM esakip_data_rencana_aksi_opd
						WHERE tahun_anggaran=%d
							AND active=1
							AND nip=%s
					", $tahun_anggaran, $nip), ARRAY_A);

					$data_all = array(
						'total' => 0,
						'action' => $_POST['action'],
						'data' => array(),
						'status' => true,
						'message' => 'Berhasil get data rencana hasil kerja!',
					);
					// kegiatan utama
					foreach ($data as $v) {
						$data_all['total']++;
						$indikator = $wpdb->get_results($wpdb->prepare("
							SELECT
								*,
								CASE 
									WHEN aspek_rhk = 1 THEN 'Kuantitas'
									WHEN aspek_rhk = 2 THEN 'Kualitas'
									WHEN aspek_rhk = 3 THEN 'Waktu'
									WHEN aspek_rhk = 4 THEN 'Biaya'
										ELSE 'Kuntitas'
									END AS aspek_rhk_teks
							FROM esakip_data_rencana_aksi_indikator_opd
							WHERE id_renaksi=%d
								AND active=1
						", $v['id']), ARRAY_A);
						$rhk_parent = array();
						if (!empty($v['parent'])) {
							$rhk_parent = $this->get_rhk_parent($v['parent'], $tahun_anggaran);
						}
						$data_all['data'][$v['id']] = array(
							'detail' => $v,
							'indikator' => $indikator,
							'detail_atasan' => $rhk_parent
						);
					}

					echo json_encode($data_all);
					exit;
				} else {
					throw new Exception('Api key tidak sesuai');
				}
			} else {
				throw new Exception('Format tidak sesuai');
			}
		} catch (Exception $e) {
			echo json_encode([
				'status' => false,
				'action' => $_POST['action'],
				'data' => array(),
				'message' => $e->getMessage()
			]);
			exit;
		}
	}

	function get_rhk_parent($parent, $tahun_anggaran, $all_data = array()){
		global $wpdb;
		$rhk_parent = $wpdb->get_row($wpdb->prepare("
			SELECT
				*
			FROM esakip_data_rencana_aksi_opd
			WHERE tahun_anggaran=%d
				AND active=1
				AND id=%d
		", $tahun_anggaran, $parent), ARRAY_A);
		if(!empty($rhk_parent)){
			$all_data[$rhk_parent['level']] = $rhk_parent;
			if(empty($rhk_parent['parent'])){
				return $all_data;
			}
			return $this->get_rhk_parent($rhk_parent['parent'], $tahun_anggaran, $all_data);
		}else{
			return $all_data;
		}
	}

	function get_sub_keg_rka_wpsipd()
	{
		global $wpdb;
		$ret = array(
			'status'    => 'success',
			'message'   => 'Berhasil get data rka wp-sipd!',
			'data'      => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran kosong!';
				}
				if (empty($_POST['kode_sbl'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Kode Sbl kosong!';
				}
				if (empty($_POST['id_indikator']) && empty($_POST['sumber_dana'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'id indikator kosong!';
				}
				if ($ret['status'] != 'error') {
					// Params post
					$api_params = array(
						'action'         => 'get_sub_keg_rka_sipd',
						'api_key'        => get_option('_crb_apikey_wpsipd'),
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						'kode_sbl'       => $_POST['kode_sbl']
					);

					// Post to wp-sipd
					$post = wp_remote_post(
						get_option('_crb_url_server_sakip'),
						array(
							'timeout'    => 1000,
							'sslverify'  => false,
							'body'       => $api_params
						)
					);

					// Retrieve data
					$response = wp_remote_retrieve_body($post);
					$data_rka = json_decode($response, true); // Decode JSON to associative array

					if (empty($_POST['sumber_dana'])) {
						if (isset($data_rka['data']) && is_array($data_rka['data'])) {
							foreach ($data_rka['data'] as &$item) {
								$id_rinci_sub_bl = $item['id_rinci_sub_bl'];

								// Ambil semua data labels yang sesuai
								$labels = $wpdb->get_results(
									$wpdb->prepare('
										SELECT 
											id_indikator,
											id_rinci_sub_bl,
											volume,
											realisasi,
											keterangan
										FROM esakip_tagging_rincian_belanja
										WHERE tahun_anggaran = %d
										  AND tipe = 2
										  AND kode_sbl = %s
										  AND id_rinci_sub_bl = %d
										  AND active = 1
									', $_POST['tahun_anggaran'], $_POST['kode_sbl'], $id_rinci_sub_bl),
									ARRAY_A
								);

								// Inisialisasi labels dan is_checked
								$item['labels'] = [];
								$item['is_checked'] = false;

								if (!empty($labels)) {
									foreach ($labels as $label) {
										$data_indikator = $wpdb->get_row(
											$wpdb->prepare('
												SELECT *
												FROM esakip_data_rencana_aksi_indikator_opd
												WHERE id = %d
												  AND active = 1
											', $label['id_indikator']),
											ARRAY_A
										);
										$label['nama_indikator'] = $data_indikator['indikator'];

										// Ambil nama RHK terkait
										$nama_rhk = $wpdb->get_row(
											$wpdb->prepare('
												SELECT *
												FROM esakip_data_rencana_aksi_opd
												WHERE id = %d
												  AND active = 1
											', $data_indikator['id_renaksi']),
											ARRAY_A
										);
										$label['nama_rhk'] = $nama_rhk['label'];

										// jika id_indikator sama dengan halaman saat ini
										if ($label['id_indikator'] == $_POST['id_indikator']) {
											$item['is_checked'] = true;
										}

										// Tambahkan label ke array labels
										$item['labels'][] = $label;
									}
								}
							}

							$ret['data'] = $data_rka['data'];
						} else {
							$ret['status'] = 'error';
							$ret['message'] = 'Data dari API kosong atau tidak valid!';
						}
					} else {
						$sumber_danas = [];
						foreach ($data_rka['data'] as $item) {
							if (isset($item['sumber_dana']) && isset($item['sumber_dana']['id_sumber_dana'])) {
								$id_sumber_dana = $item['sumber_dana']['id_sumber_dana'];
								if (!in_array($id_sumber_dana, $sumber_danas)) {
									$ret['data'][] = $item['sumber_dana'];
									$sumber_danas[] = $id_sumber_dana;
								}
							}
						}
					}
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message' => 'Api Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message' => 'Format tidak sesuai!'
			);
		}

		die(json_encode($ret));
	}

	function get_data_akun()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'results' => array(),
			'pagination' => array(
				"more" => false
			)
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$tahun_anggaran = intval($_POST['tahun_anggaran']);
				$page = isset($_POST['page']) ? intval($_POST['page']) : 0;

				$search = '';
				if (!empty($_POST['search'])) {
					$search_term = '%' . $wpdb->esc_like($_POST['search']) . '%';
					$search = $wpdb->prepare("AND (kode_akun LIKE %s OR nama_akun LIKE %s)", $search_term, $search_term);
				}

				$data_akun = $wpdb->get_results(
					$wpdb->prepare("
						SELECT 
							id_akun,
							kode_akun,
							nama_akun 
						FROM esakip_data_rekening_akun 
						WHERE tahun_anggaran = %d
						  AND kode_akun LIKE '5.%'
						  AND active = 1
						$search
						LIMIT %d, 20
					", $tahun_anggaran, $page * 20),
					ARRAY_A
				);

				foreach ($data_akun as $key => $value) {
					$return['results'][] = array(
						'id' 	=> $value['kode_akun'],
						'text' 	=> $value['kode_akun'] . ' - ' . $value['nama_akun']
					);
				}

				if (count($data_akun) == 20) {
					$return['pagination']['more'] = true;
				}
			} else {
				$return['status'] = 'error';
				$return['message'] = 'Api Key tidak sesuai!';
			}
		} else {
			$return['status'] = 'error';
			$return['message'] = 'Format tidak sesuai!';
		}

		die(json_encode($return));
	}

	function simpan_rinci_bl_tagging()
	{
		global $wpdb;
		$ret = array(
			'status'  => 'success',
			'message' => 'Berhasil menyimpan data tagging rincian belanja!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran kosong!';
					die(json_encode($ret));
				}
				if (empty($_POST['kode_sbl'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Kode SBL kosong!';
					die(json_encode($ret));
				}
				if (empty($_POST['id_indikator'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Indikator kosong!';
					die(json_encode($ret));
				}
				if (empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id SKPD kosong!';
					die(json_encode($ret));
				}

				// Decode rincian_belanja_ids dan data_rinci dari JSON
				$rincianBelanjaIds = json_decode(stripslashes($_POST['rincian_belanja_ids']), true);
				$dataRinci = json_decode(stripslashes($_POST['data_rinci']), true);

				if (empty($rincianBelanjaIds) || !is_array($rincianBelanjaIds) || empty($dataRinci) || !is_array($dataRinci)) {
					$ret['status'] = 'error';
					$ret['message'] = 'Format rincian belanja tidak valid!';
					die(json_encode($ret));
				}

				//validasi realisasi
				foreach ($rincianBelanjaIds as $index => $rinciId) {
					$rinci = isset($dataRinci[$index]) ? $dataRinci[$index] : null;

					if (!is_array($rinci)) {
						$ret['status'] = 'error';
						$ret['message'] = 'Rincian data tidak sesuai!';
						die(json_encode($ret));
					}

					$total_harga = $rinci['harga_satuan'] * $rinci['volume'];
					if ($rinci['realisasi'] > $total_harga) {
						$ret['status'] = 'error';
						$ret['message'] = 'Realisasi lebih besar dari Total Harga!';
						die(json_encode($ret));
					}
				}

				//set active = 0 semua sebelum ditambah
				$wpdb->update(
					'esakip_tagging_rincian_belanja',
					array('active' => 0),
					array(
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						'kode_sbl' 		 => $_POST['kode_sbl'],
						'id_indikator'   => $_POST['id_indikator'],
						'id_skpd' 		 => $_POST['id_skpd'],
						'tipe' 		 	 => 2,
						'active' 		 => 1
					)
				);
				// Insert atau update data ke database
				foreach ($rincianBelanjaIds as $index => $rinciId) {
					// Ambil rincian berdasarkan index
					$rinci = isset($dataRinci[$index]) ? $dataRinci[$index] : null;

					if (!is_array($rinci)) {
						$ret['status'] = 'error';
						$ret['message'] = 'Rincian data tidak sesuai!';
						die(json_encode($ret));
					}

					$volume = isset($rinci['volume']) ? sanitize_text_field($rinci['volume']) : '';
					$realisasi = isset($rinci['realisasi']) ? sanitize_text_field($rinci['realisasi']) : '';
					$keterangan = isset($rinci['keterangan']) ? sanitize_textarea_field($rinci['keterangan']) : '';
					$data = array(
						'id_skpd'         => sanitize_text_field($_POST['id_skpd']),
						'id_indikator'    => sanitize_text_field($_POST['id_indikator']),
						'kode_sbl'        => sanitize_text_field($_POST['kode_sbl']),
						'tipe'            => 2,
						'kode_akun'       => sanitize_text_field($rinci['kode_akun']),
						'nama_akun'       => sanitize_text_field($rinci['nama_akun']),
						'subs_bl_teks'    => sanitize_text_field($rinci['subs']),
						'ket_bl_teks'     => sanitize_text_field($rinci['ket']),
						'id_rinci_sub_bl' => sanitize_text_field($rinci['id_rincian']),
						'nama_komponen'   => sanitize_text_field($rinci['nama_komponen']),
						'volume'          => $volume,
						'satuan'          => sanitize_text_field($rinci['satuan']),
						'harga_satuan'    => sanitize_text_field($rinci['harga_satuan']),
						'keterangan'      => $keterangan,
						'tahun_anggaran'  => sanitize_text_field($_POST['tahun_anggaran']),
						'realisasi'       => $realisasi,
						'active'          => 1,
					);

					$wpdb->insert(
						'esakip_tagging_rincian_belanja',
						$data
					);
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API key tidak ditemukan!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format salah!';
		}

		die(json_encode($ret));
	}

	function simpan_rinci_bl_tagging_manual()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil menyimpan data tagging rincian belanja manual!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$postData = $_POST;

				// Define validation rules
				$validationRules = [
					'tahun_anggaran' => 'required',
					'kode_akun' 	 => 'required',
					'subs_bl_teks' 	 => 'required',
					'ket_bl_teks' 	 => 'required',
					'nama_komponen'  => 'required',
					'volume' 		 => 'required|numeric',
					'satuan' 		 => 'required',
					'harga_satuan' 	 => 'required|numeric'
				];

				// Validate data
				$errors = $this->validate($postData, $validationRules);

				if (!empty($errors)) {
					$ret['status'] = 'error';
					$ret['message'] = implode(" \n ", $errors);
					die(json_encode($ret));
				}

				if (!empty($postData['realisasi'])) {
					$total_harga = $postData['volume'] * $postData['harga_satuan'];
					if ($postData['realisasi'] > $total_harga) {
						$ret['status'] = 'error';
						$ret['message'] = 'Realisasi Melebihi Total Harga!';
						die(json_encode($ret));
					}
				}

				$nama_akun = $wpdb->get_var(
					$wpdb->prepare('
						SELECT nama_akun
						FROM esakip_data_rekening_akun
						WHERE tahun_anggaran = %d
						  AND kode_akun = %s
						  AND active = 1
					', $postData['tahun_anggaran'], $postData['kode_akun'])
				);

				if (empty($nama_akun)) {
					$ret['status'] = 'error';
					$ret['message'] = 'Akun tidak ditemukan!';
					die(json_encode($ret));
				}

				// Prepare data for insertion or update
				$data = [
					'id_skpd' 		 => $postData['id_skpd'],
					'id_indikator'   => $postData['id_indikator'],
					'kode_sbl' 		 => $postData['kode_sbl'],
					'tipe' 			 => 1,
					'kode_akun' 	 => $postData['kode_akun'],
					'nama_akun' 	 => $nama_akun,
					'subs_bl_teks' 	 => '[#] ' . $postData['subs_bl_teks'],
					'ket_bl_teks' 	 => '[-] ' . $postData['ket_bl_teks'],
					'nama_komponen'  => $postData['nama_komponen'],
					'volume' 		 => $postData['volume'],
					'satuan' 		 => $postData['satuan'],
					'harga_satuan'   => $postData['harga_satuan'],
					'realisasi'      => $postData['realisasi'],
					'keterangan' 	 => $postData['keterangan'],
					'tahun_anggaran' => $postData['tahun_anggaran']
				];

				if (!empty($postData['id_data'])) {
					// Update existing record
					$wpdb->update(
						'esakip_tagging_rincian_belanja',
						$data,
						['id' => $postData['id_data']]
					);
					$ret['message'] = 'Berhasil mengedit data tagging rincian belanja manual!';
				} else {
					// Insert new record
					$wpdb->insert('esakip_tagging_rincian_belanja', $data);
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API key tidak ditemukan!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format salah!';
		}

		die(json_encode($ret));
	}

	function delete_rincian_tagging_by_id_rinci_bl()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus tagging rincian!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$wpdb->update(
					'esakip_tagging_rincian_belanja',
					array('active' => 0),
					array('id' => $_POST['id'])
				);
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API key tidak ditemukan!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format salah!';
		}

		die(json_encode($ret));
	}

	function get_rinci_tagging_by_id()
	{
		global $wpdb;
		$ret = array(
			'status'  => 'success',
			'message' => 'Berhasil get data tagging rincian by id!',
			'data' 	  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$data = $wpdb->get_row(
					$wpdb->prepare('
						SELECT *
						FROM esakip_tagging_rincian_belanja
						WHERE id = %d
						  AND active = 1
					', $_POST['id']),
					ARRAY_A
				);

				if ($data) {
					// Hapus kode [#] dari subs_bl_teks
					if (!empty($data['subs_bl_teks'])) {
						$data['subs_bl_teks'] = preg_replace('/^\[\#\]\s*/', '', $data['subs_bl_teks']);
					}

					// Hapus kode [-] dari ket_bl_teks
					if (!empty($data['ket_bl_teks'])) {
						$data['ket_bl_teks'] = preg_replace('/^\[\-\]\s*/', '', $data['ket_bl_teks']);
					}

					$ret['data'] = $data;
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Data tidak ditemukan!';
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API key tidak ditemukan!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format salah!';
		}

		die(json_encode($ret));
	}


	function validate($data, $rules)
	{
		$errors = [];

		foreach ($rules as $field => $ruleSet) {
			$rulesArray = explode('|', $ruleSet);

			foreach ($rulesArray as $rule) {
				if ($rule == 'required' && (!isset($data[$field]))) {
					$errors[] = "$field is required";
				}

				if ($rule == 'string' && isset($data[$field]) && !is_string($data[$field])) {
					$errors[] = "$field must be a string";
				}

				if ($rule == 'numeric' && isset($data[$field]) && !is_numeric($data[$field])) {
					$errors[] = "$field must be numeric";
				}

				if (strpos($rule, 'min:') === 0) {
					$min = (int)explode(':', $rule)[1];
					if (isset($data[$field]) && strlen($data[$field]) < $min) {
						$errors[] = "$field must be at least $min characters";
					}
				}

				if (strpos($rule, 'max:') === 0) {
					$max = (int)explode(':', $rule)[1];
					if (isset($data[$field]) && strlen($data[$field]) > $max) {
						$errors[] = "$field cannot exceed $max characters";
					}
				}

				if (strpos($rule, 'in:') === 0) {
					$allowed = explode(',', explode(':', $rule)[1]);
					if (isset($data[$field]) && !in_array($data[$field], $allowed)) {
						$errors[] = "$field must be one of: " . implode(', ', $allowed);
					}
				}
			}
		}

		return $errors;
	}

	public function get_pegawai_rhk()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$satker_id = $_POST['satker_id'];

				$data_pegawai = $wpdb->get_results(
					$wpdb->prepare("
						SELECT 
							id,
							nama_pegawai,
							nip_baru
						FROM esakip_data_pegawai_simpeg
						WHERE satker_id = %d
						  AND active = 1
						ORDER BY satker_id ASC, tipe_pegawai_id ASC, nama_pegawai ASC
					", $satker_id),
					ARRAY_A
				);

				$ret['sql'] = $wpdb->last_query;

				foreach ($data_pegawai as $value) {
					$ret['data'][] = array(
						'id'        => $value['id'],
						'nama'      => $value['nip_baru'] . ' | ' . $value['nama_pegawai'],
						'nip_baru'  => $value['nip_baru']
					);
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API key tidak valid!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format tidak valid!';
		}

		die(json_encode($ret));
	}

	public function help_rhk()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil mendapatkan data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id'])) {
					$id = $_POST['id'];
				} else if (!empty($_POST['tipe'])) {
					$tipe = $_POST['tipe'];
				}

				$data = $wpdb->get_row(
					$wpdb->prepare("
                    	SELECT 
                    		* 
                    	FROM esakip_data_rencana_aksi_opd
                    	WHERE id = %d
                    ", $id),
					ARRAY_A
				);
				if ($data) {
					$get_pokin_1 = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_opd AS o
					        INNER JOIN esakip_pohon_kinerja_opd AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_opd = %d
					            AND o.level_rhk_opd = %d
					            AND o.level_pokin = 1
					    ", $data['id'], $data['level']),
						ARRAY_A
					);

					// print_r($get_pokin_1); die($wpdb->last_query);
					$get_pokin_2 = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_opd AS o
					        INNER JOIN esakip_pohon_kinerja_opd AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_opd = %d
					            AND o.level_rhk_opd = %d
					            AND o.level_pokin = 2
					    ", $data['id'], $data['level']),
						ARRAY_A
					);
					$get_pokin_3 = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_opd AS o
					        INNER JOIN esakip_pohon_kinerja_opd AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_opd = %d
					            AND o.level_rhk_opd = %d
					            AND o.level_pokin = 3
					    ", $data['id'], $data['level']),
						ARRAY_A
					);
					$get_pokin_4 = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_opd AS o
					        INNER JOIN esakip_pohon_kinerja_opd AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_opd = %d
					            AND o.level_rhk_opd = %d
					            AND o.level_pokin = 4
					    ", $data['id'], $data['level']),
						ARRAY_A
					);
					$get_pokin_5 = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_opd AS o
					        INNER JOIN esakip_pohon_kinerja_opd AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_opd = %d
					            AND o.level_rhk_opd = %d
					            AND o.level_pokin = 5
					    ", $data['id'], $data['level']),
						ARRAY_A
					);
					$get_satker = $wpdb->get_row($wpdb->prepare('
	                	SELECT
	                		*
	                	FROM esakip_data_satker_simpeg
	                	WHERE satker_id=%d
	                ', $data['satker_id']), ARRAY_A);
					$get_pegawai = $wpdb->get_row($wpdb->prepare('
	                	SELECT
	                		*
	                	FROM esakip_data_pegawai_simpeg
	                	WHERE nip_baru=%d
	                ', $data['nip']), ARRAY_A);
					$ret['get_pokin_1'] = $get_pokin_1;
					$ret['get_pokin_2'] = $get_pokin_2;
					$ret['get_pokin_3'] = $get_pokin_3;
					$ret['get_pokin_4'] = $get_pokin_4;
					$ret['get_pokin_5'] = $get_pokin_5;
					$ret['get_satker'] = $get_satker;
					$ret['get_pegawai'] = $get_pegawai;
					$ret['data'] = $data;
				} else {
					$ret = array(
						'status' => 'error',
						'message' => 'Data tidak ditemukan!'
					);
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message' => 'API Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message' => 'Format tidak sesuai!'
			);
		}

		die(json_encode($ret));
	}

	function get_serapan_anggaran_capaian_kinerja()
	{
		global $wpdb;
		$ret = array(
			'status'  => 'success',
			'message' => 'Berhasil get data serapan anggaran dan capaian kinerja!',
			'data' 	  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$id_skpd = $wpdb->get_var(
					$wpdb->prepare('
						SELECT id_skpd
						FROM esakip_data_mapping_unit_sipd_simpeg
						WHERE id_satker_simpeg = %d
						  AND tahun_anggaran = %d
						  AND active = 1
					', $_POST['id_satker_simpeg'], $_POST['tahun_anggaran'])
				);

				if (empty($id_skpd) || empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Data tidak ditemukan!';
					die(json_encode($ret));
				}

				$url_wpsipd = get_option(ESAKIP_URL_WPSIPD);
				$api_key_wpsipd = get_option(ESAKIP_APIKEY_WPSIPD);

				// data to send in API request
				$api_params = array(
					'action' 		 => 'get_serapan_anggaran_capaian_kinerja',
					'id_skpd' 		 => $id_skpd,
					'api_key'		 => $api_key_wpsipd,
					'tahun_anggaran' => $_POST['tahun_anggaran']
				);

				$response = wp_remote_post(
					$url_wpsipd,
					array(
						'timeout' 	=> 1000,
						'sslverify' => false,
						'body' 		=> $api_params
					)
				);

				$response_body = wp_remote_retrieve_body($response);
				$response_data = json_decode($response_body, true);

				if (isset($response_data['data'])) {
					$ret['data'] = $response_data['data'];
				} else {
					$ret['data'] = 'data tidak ditemukan!';
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API key tidak ditemukan!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format salah!';
		}

		die(json_encode($ret));
	}

	function get_pk_html($options)
	{
		global $wpdb;

		$ret = array(
			'html_sasaran' => '',
			'html_program' => '',
			'html_kegiatan' => '',
			'html_sub_kegiatan' => '',
			'error_msg' => array()
		);
		$id_skpd = $options['id_skpd'];

		// hasil ploting di halaman RHK
		$data_ploting_rhk = $wpdb->get_results(
			$wpdb->prepare("
				SELECT 
					id,
					label,
					level,
					satker_id
				FROM esakip_data_rencana_aksi_opd
				WHERE id_skpd = %d 
				  AND tahun_anggaran = %d 
				  AND nip = %d 
				  AND id_jabatan = %s 
				  AND active = 1
				ORDER BY level ASC
			", $id_skpd, $options['tahun'], $options['nip_baru'], $options['satker_id']),
			ARRAY_A
		);

		$data_anggaran = array(
			'sasaran'       => array(),
			'program'       => array(),
			'kegiatan'      => array(),
			'sub_kegiatan'  => array()
		);
		$no_2 = 0;
		if (!empty($data_ploting_rhk)) {
			foreach ($data_ploting_rhk as $v_rhk) {
				$data_indikator_ploting_rhk = $wpdb->get_results(
					$wpdb->prepare("
						SELECT
							id,
							indikator,
							satuan,
							target_awal,
							target_akhir
						FROM esakip_data_rencana_aksi_indikator_opd
						WHERE id_renaksi = %d 
						  AND active = 1
					", $v_rhk['id']),
					ARRAY_A
				);

				$html_indikator = '';
				$p_i = count($data_indikator_ploting_rhk);
				$no_2++;

				if (!empty($data_indikator_ploting_rhk)) {
					foreach ($data_indikator_ploting_rhk as $index => $v_indikator) {
						$html_indikator .= '<tr id-indikator="' . $v_indikator['id'] . '">';

						if ($index === 0) {
							$rowspan = $p_i > 1 ? 'rowspan="' . $p_i . '"' : '';
							$html_indikator .= '<td ' . $rowspan . ' class="text-center">' . $no_2 . '</td>';
							$html_indikator .= '<td ' . $rowspan . ' class="text-left">' . $v_rhk['label'] . '</td>';
						}

						$html_indikator .= '<td class="text-left">' . $v_indikator['indikator'] . '</td>';
						$html_indikator .= '<td class="text-left">' . $v_indikator['target_akhir'] . ' ' . $v_indikator['satuan'] . '</td>';
						$html_indikator .= '</tr>';
					}
				} else {
					$html_indikator .= '<tr>
						<td class="text-center">' . $no_2 . '</td>
						<td class="text-left">' . $v_rhk['label'] . '</td>
						<td></td>
						<td></td>
					</tr>';
				}

				$ret['html_sasaran'] .= $html_indikator;

				$data_rhk_child = $wpdb->get_results(
					$wpdb->prepare("
						SELECT *
						FROM esakip_data_rencana_aksi_opd 
						WHERE parent = %d 
						  AND level = %d 
						  AND id_skpd = %d
						  AND active = 1
						ORDER BY kode_cascading_sub_kegiatan
					", $v_rhk['id'], $v_rhk['level'] + 1, $id_skpd),
					ARRAY_A
				);

				$jenis_level = array(
					'1' => 'sasaran',
					'2' => 'program',
					'3' => 'kegiatan',
					'4' => 'sub_kegiatan'
				);
				$no = 1;
				if (!empty($data_rhk_child)) {
					foreach ($data_rhk_child as $v_rhk_child) {
						if (empty($data_anggaran[$jenis_level[$v_rhk_child['level']]][$v_rhk_child['kode_cascading_sub_kegiatan']])) {
							$data_anggaran[$jenis_level[$v_rhk_child['level']]][$v_rhk_child['kode_cascading_sub_kegiatan']] = array();
						}

						$rencana_pagu = 0;
						$sumber_dana = array();
						if ($v_rhk_child['level'] == '4') {
							$data_indikator_anggaran = $wpdb->get_results(
								$wpdb->prepare("
									SELECT
										id,
										rencana_pagu
									FROM esakip_data_rencana_aksi_indikator_opd 
									WHERE id_renaksi=%d 
										AND active = 1
								", $v_rhk_child['id']),
								ARRAY_A
							);
							if (!empty($data_indikator_anggaran)) {
								foreach ($data_indikator_anggaran as $v_indikator_anggaran) {
									$rencana_pagu += $v_indikator_anggaran['rencana_pagu'];
									$data_sumber_dana = $wpdb->get_results(
										$wpdb->prepare("
											SELECT 
												nama_dana
											FROM esakip_sumber_dana_indikator 
											WHERE id_indikator = %d 
												AND active = 1
										", $v_indikator_anggaran['id']),
										ARRAY_A
									);
									if (!empty($data_sumber_dana)) {
										foreach ($data_sumber_dana as $sd) {
											$sumber_dana[$sd['nama_dana']] = $sd['nama_dana'];
										}
									}
								}
							}
						} else if ($v_rhk_child['level'] == '3') {
							$rhk_lv_4 = $wpdb->get_results(
								$wpdb->prepare("
									SELECT 
										id
									FROM esakip_data_rencana_aksi_opd 
									WHERE parent = %d 
									  AND level = %d 
									  AND id_skpd = %d
									  AND active = 1
									ORDER BY kode_cascading_sub_kegiatan
								", $v_rhk_child['id'], 4, $id_skpd),
								ARRAY_A
							);
							if (!empty($rhk_lv_4)) {
								foreach ($rhk_lv_4 as $v_rhk_child_4) {
									$data_indikator_anggaran = $wpdb->get_results(
										$wpdb->prepare("
											SELECT
												id,
												rencana_pagu
											FROM esakip_data_rencana_aksi_indikator_opd 
											WHERE id_renaksi=%d 
												AND active = 1
										", $v_rhk_child_4['id']),
										ARRAY_A
									);
									if (!empty($data_indikator_anggaran)) {
										foreach ($data_indikator_anggaran as $v_indikator_anggaran) {
											$rencana_pagu += $v_indikator_anggaran['rencana_pagu'];
											$data_sumber_dana = $wpdb->get_results(
												$wpdb->prepare("
													SELECT 
														nama_dana
													FROM esakip_sumber_dana_indikator 
													WHERE id_indikator = %d 
														AND active = 1
												", $v_indikator_anggaran['id']),
												ARRAY_A
											);
											if (!empty($data_sumber_dana)) {
												foreach ($data_sumber_dana as $sd) {
													$sumber_dana[$sd['nama_dana']] = $sd['nama_dana'];
												}
											}
										}
									}
								}
							}
						} else if ($v_rhk_child['level'] == '2') {
							$rhk_lv_3 = $wpdb->get_results(
								$wpdb->prepare("
									SELECT 
										id
									FROM esakip_data_rencana_aksi_opd 
									WHERE parent = %d 
									  AND level = %d 
									  AND id_skpd = %d
									  AND active = 1
									ORDER BY kode_cascading_sub_kegiatan
								", $v_rhk_child['id'], 3, $id_skpd),
								ARRAY_A
							);
							if (!empty($rhk_lv_3)) {
								foreach ($rhk_lv_3 as $v_rhk_child_3) {
									$rhk_lv_4 = $wpdb->get_results(
										$wpdb->prepare("
											SELECT 
												id
											FROM esakip_data_rencana_aksi_opd 
											WHERE parent = %d 
											AND level = %d 
											AND id_skpd = %d
											AND active = 1
											ORDER BY kode_cascading_sub_kegiatan
										", $v_rhk_child_3['id'], 4, $id_skpd),
										ARRAY_A
									);
									if (!empty($rhk_lv_4)) {
										foreach ($rhk_lv_4 as $v_rhk_child_4) {
											$data_indikator_anggaran = $wpdb->get_results(
												$wpdb->prepare("
													SELECT
														id,
														rencana_pagu
													FROM esakip_data_rencana_aksi_indikator_opd 
													WHERE id_renaksi=%d 
														AND active = 1
												", $v_rhk_child_4['id']),
												ARRAY_A
											);
											if (!empty($data_indikator_anggaran)) {
												foreach ($data_indikator_anggaran as $v_indikator_anggaran) {
													$rencana_pagu += $v_indikator_anggaran['rencana_pagu'];
													$data_sumber_dana = $wpdb->get_results(
														$wpdb->prepare("
															SELECT 
																nama_dana
															FROM esakip_sumber_dana_indikator 
															WHERE id_indikator = %d 
																AND active = 1
														", $v_indikator_anggaran['id']),
														ARRAY_A
													);
													if (!empty($data_sumber_dana)) {
														foreach ($data_sumber_dana as $sd) {
															$sumber_dana[$sd['nama_dana']] = $sd['nama_dana'];
														}
													}
												}
											}
										}
									}
								}
							}
						}
						$data_anggaran[$jenis_level[$v_rhk_child['level']]][$v_rhk_child['kode_cascading_sub_kegiatan']][] = array(
							'nama'           => $v_rhk_child['label_cascading_' . $jenis_level[$v_rhk_child['level']]],
							'kode'           => $v_rhk_child['kode_cascading_' . $jenis_level[$v_rhk_child['level']]],
							'sumber_dana'    => implode(', ', $sumber_dana),
							'total_anggaran' => $rencana_pagu,
							'urut' 			 => $no_2,
							'id' 			 => $v_rhk_child['id']
						);
					}
				}
			}

			$cek_urut = 0;
			foreach ($data_anggaran as $jenis => $cascading) {
				foreach ($cascading as $multi_cascading) {
					foreach ($multi_cascading as $v) {
						if ($cek_urut != $v['urut']) {
							$cek_urut = $v['urut'];
							$no_cascading = 0;
						}
						$no_cascading++;
						if ($jenis == 'program') {
							$ret['html_program'] .= '<tr data-id="' . $v['id'] . '">
								<td class="text-center">' . $v['urut'] . '.' . $no_cascading . '</td>
								<td class="text-left">' . $v['kode'] . ' ' . $v['nama'] . '</td>
								<td class="text-right">' . number_format($v['total_anggaran'], 0, ",", ".") . '</td>
								<td class="text-left">' . $v['sumber_dana'] . '</td>
							</tr>';
						} else if ($jenis == 'kegiatan') {
							$ret['html_kegiatan'] .= '<tr data-id="' . $v['id'] . '">
								<td class="text-center">' . $v['urut'] . '.' . $no_cascading . '</td>
								<td class="text-left">' . $v['kode'] . ' ' . $v['nama'] . '</td>
								<td class="text-right">' . number_format($v['total_anggaran'], 0, ",", ".") . '</td>
								<td class="text-left">' . $v['sumber_dana'] . '</td>
							</tr>';
						} else if ($jenis == 'sub_kegiatan') {
							$parts = explode(" ", $v['nama'], 2);
							$ret['html_sub_kegiatan'] .= '<tr data-id="' . $v['id'] . '">
								<td class="text-center">' . $v['urut'] . '.' . $no_cascading . '</td>
								<td class="text-left">' . $v['kode'] . ' ' . $parts[1] . '</td>
								<td class="text-right">' . number_format($v['total_anggaran'], 0, ",", ".") . '</td>
								<td class="text-left">' . $v['sumber_dana'] . '</td>
							</tr>';
						}
					}
				}
			}
		}
		return $ret;
	}

	function simpan_finalisasi_laporan_pk()
	{
		global $wpdb;
		$ret = array(
			'status'  => 'success',
			'message' => 'Berhasil finalisasi laporan PK!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['data_pk']['nama_tahapan']) || empty($_POST['data_pk']['tanggal_dokumen'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Nama tahapan dan tanggal dokumen wajib diisi!';
					die(json_encode($ret));
				}

				$data_pk = $_POST['data_pk'];

				$pihak_pertama = $wpdb->get_row(
					$wpdb->prepare('
						SELECT 
							p.*,
							ds.nama AS nama_bidang
						FROM esakip_data_pegawai_simpeg p
						LEFT JOIN esakip_data_satker_simpeg ds
							   ON ds.satker_id = p.satker_id
						WHERE p.nip_baru=%d
						  AND p.satker_id = %s
						  AND p.active = 1
					', $_POST['nip_pertama'], $_POST['id_satker_pertama']),
					ARRAY_A
				);

				//jika ada status plt plh maka tambahkan
				$jabatan_pertama = (!empty($_POST['status_pertama']) ? $_POST['status_pertama'] . ' ' . $pihak_pertama['jabatan'] : $pihak_pertama['jabatan']);

				$skpd = $wpdb->get_row(
					$wpdb->prepare("
						SELECT 
							u.nama_skpd,
							d.alamat_kantor
						FROM esakip_data_unit u
						LEFT JOIN esakip_detail_data_unit d
							   ON d.id_skpd = u.id_skpd
						WHERE u.id_skpd=%d
						  AND u.tahun_anggaran=%d
						  AND u.active = 1
					", $_POST['id_skpd'], $_POST['tahun_anggaran']),
					ARRAY_A
				);

				//jika nip kedua kosong berarti atasan langsung bupati
				$data_atasan = array();
				if (!empty($_POST['nip_kedua'])) {
					//atasan ASN
					$pihak_kedua = $wpdb->get_row(
						$wpdb->prepare('
							SELECT 
								p.*,
								ds.nama AS nama_bidang
							FROM esakip_data_pegawai_simpeg p
							LEFT JOIN esakip_data_satker_simpeg ds
								   ON ds.satker_id = p.satker_id
							WHERE p.nip_baru=%d
							  AND p.satker_id = %s
							  AND p.active = 1
						', $_POST['nip_kedua'], $_POST['id_satker_kedua']),
						ARRAY_A
					);
					$data_atasan = $pihak_kedua;

					//gelar depan atau belakang tambahkan
					$data_atasan['nama_pegawai'] = $pihak_kedua['gelar_depan'] . ' ' . $pihak_kedua['nama_pegawai'] . ', ' . $pihak_kedua['gelar_belakang'];

					//jika ada status plt plh maka tambahkan
					$data_atasan['jabatan'] = (!empty($_POST['status_kedua']) ? $_POST['status_kedua'] . ' ' . $pihak_kedua['jabatan'] . ' ' . $pihak_kedua['nama_bidang'] : $pihak_kedua['jabatan'] . ' ' . $pihak_kedua['nama_bidang']);
				} else {
					//atasan Kepala Daerah
					$nama_kepala_daerah = get_option('_crb_kepala_daerah');
					$status_jabatan_kepala_daerah = get_option('_crb_status_jabatan_kepala_daerah');

					$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);
					$pemda = explode(" ", $nama_pemda);
					array_shift($pemda);
					$pemda = implode(" ", $pemda);

					$data_atasan = [
						'nama_pegawai'  => $nama_kepala_daerah,
						'jabatan'       => $status_jabatan_kepala_daerah . ' ' . $pemda,
						'status_kepala' => 'kepala_daerah'
					];
				}

				//nama dan gelar pihak pertama
				$nama_pihak_pertama = $pihak_pertama['gelar_depan'] . ' ' . $pihak_pertama['nama_pegawai'] . ', ' . $pihak_pertama['gelar_belakang'];

				//data rhk (sasaran, program, kegiatan, subkegiatan)
				$html_rhk = $this->get_pk_html($_POST['options']);

				$data = array(
					'id_skpd' 				 => $_POST['id_skpd'],
					'alamat_kantor' 		 => $skpd['alamat_kantor'],
					'nama_skpd' 			 => $skpd['nama_skpd'],
					'id_satker' 			 => $pihak_pertama['satker_id'],
					'satuan_kerja' 			 => $pihak_pertama['nama_bidang'],

					'nama_tahapan' 			 => $data_pk['nama_tahapan'],
					'tanggal_dokumen' 		 => $data_pk['tanggal_dokumen'],

					'nip' 					 => $pihak_pertama['nip_baru'],
					'pangkat_pegawai' 		 => $pihak_pertama['pangkat'],
					'nama_pegawai' 			 => $nama_pihak_pertama,
					'jabatan_pegawai' 		 => $jabatan_pertama,

					'nama_pegawai_atasan' 	 => $data_atasan['nama_pegawai'],
					'jabatan_pegawai_atasan' => $data_atasan['jabatan'],

					'html_sasaran' 			 => $html_rhk['html_sasaran'],
					'html_program' 			 => $html_rhk['html_program'],
					'html_kegiatan' 		 => $html_rhk['html_kegiatan'],
					'html_subkegiatan' 		 => $html_rhk['html_sub_kegiatan'],

					'tahun_anggaran' 		 => $_POST['tahun_anggaran']
				);

				//jika nip kedua tidak kosong, berarti atasan ASN
				if (!empty($_POST['nip_kedua'])) {
					$data['nip_pegawai_atasan'] 	= $data_atasan['nip_baru'];
					$data['pangkat_pegawai_atasan'] = $data_atasan['pangkat'];
				}

				$wpdb->insert('esakip_finalisasi_tahap_laporan_pk', $data);
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API key tidak ditemukan!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format salah!';
		}
		die(json_encode($ret));
	}

	function get_laporan_pk_by_id()
	{
		global $wpdb;
		$ret = array(
			'status'  => 'success',
			'message' => 'Berhasil get laporan PK by id!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$ret['data'] = $wpdb->get_row(
					$wpdb->prepare('
						SELECT
							*
						FROM esakip_finalisasi_tahap_laporan_pk
						WHERE id = %d
						  AND active = 1
					', $_POST['id_tahap']),
					ARRAY_A
				);
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API key tidak ditemukan!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format salah!';
		}
		die(json_encode($ret));
	}

	public function help_rhk_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil mendapatkan data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id'])) {
					$id = $_POST['id'];
				} else if (!empty($_POST['tipe'])) {
					$tipe = $_POST['tipe'];
				}

				$data = $wpdb->get_row(
					$wpdb->prepare("
                    	SELECT 
                    		* 
                    	FROM esakip_data_rencana_aksi_pemda
                    	WHERE id = %d
                    ", $id),
					ARRAY_A
				);
				if ($data) {
					$get_pokin_1 = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_pemda AS o
					        INNER JOIN esakip_pohon_kinerja AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_pemda = %d
					            AND o.level_rhk_pemda = %d
					            AND o.level_pokin = 1
					    ", $data['id'], $data['level']),
						ARRAY_A
					);

					$get_pokin_2 = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_pemda AS o
					        INNER JOIN esakip_pohon_kinerja AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_pemda = %d
					            AND o.level_rhk_pemda = %d
					            AND o.level_pokin = 2
					    ", $data['id'], $data['level']),
						ARRAY_A
					);
					// print_r($get_pokin_2); die($wpdb->last_query);
					$get_pokin_3 = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_pemda AS o
					        INNER JOIN esakip_pohon_kinerja AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_pemda = %d
					            AND o.level_rhk_pemda = %d
					            AND o.level_pokin = 3
					    ", $data['id'], $data['level']),
						ARRAY_A
					);
					$get_pokin_4 = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT
					            p.label AS pokin_label
					        FROM esakip_data_pokin_rhk_pemda AS o
					        INNER JOIN esakip_pohon_kinerja AS p 
					            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					        WHERE o.id_rhk_pemda = %d
					            AND o.level_rhk_pemda = %d
					            AND o.level_pokin = 4
					    ", $data['id'], $data['level']),
						ARRAY_A
					);
					$ret['get_pokin_1'] = $get_pokin_1;
					$ret['get_pokin_2'] = $get_pokin_2;
					$ret['get_pokin_3'] = $get_pokin_3;
					$ret['get_pokin_4'] = $get_pokin_4;
					$ret['data'] = $data;
				} else {
					$ret = array(
						'status' => 'error',
						'message' => 'Data tidak ditemukan!'
					);
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message' => 'API Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message' => 'Format tidak sesuai!'
			);
		}

		die(json_encode($ret));
	}

	function simpan_finalisasi_iku()
	{
	    global $wpdb;
	    $ret = array(
	        'status'  => 'success',
	        'message' => 'Berhasil finalisasi IKU!'
	    );

	    if (!empty($_POST)) {
	        if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
	            if (empty($_POST['data_iku']['nama_tahapan']) || empty($_POST['data_iku']['tanggal_dokumen'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Nama tahapan dan tanggal dokumen wajib diisi!';
	                die(json_encode($ret));
	            }

	            $data_iku = $_POST['data_iku'];
	            $data_simpan = $_POST['data_simpan'];
	            $id_skpd = $_POST['id_skpd'];
	            $nama_tahapan = $data_iku['nama_tahapan'];
	            $tanggal_dokumen = $data_iku['tanggal_dokumen'];

	            $cek = $wpdb->get_var(
	                $wpdb->prepare("
	                	SELECT 
	                    	* 
	                    FROM esakip_finalisasi_tahap_iku_opd 
	                    WHERE id_skpd = %d 
		                     AND nama_tahapan = %s 
		                     AND tanggal_dokumen = %s 
		                     AND active = 1
		                ", $id_skpd, $nama_tahapan, $tanggal_dokumen
	                )
	            );

	            if ($cek > 0) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Nama Tahapan dan Dokumen sudah ada!';
	                die(json_encode($ret));
	            }

	            $insert_data = array(
	                'id_skpd'         => $id_skpd,
	                'nama_tahapan'    => $nama_tahapan,
	                'tanggal_dokumen' => $tanggal_dokumen,
	                'id_jadwal_wpsipd' => isset($data_simpan[0]['id_jadwal_wpsipd']) ? $data_simpan[0]['id_jadwal_wpsipd'] : null,
	                'active'          => 1,
	            );
	            
	            $wpdb->insert('esakip_finalisasi_tahap_iku_opd', $insert_data);
	            $id_tahap = $wpdb->insert_id; 
	            
	            if ($id_tahap) {
	                foreach ($data_simpan as $data) {
	                    $wpdb->insert('esakip_finalisasi_iku_opd', array(
	                        'id_tahap'         => $id_tahap,
	                        'id_skpd'          => $id_skpd,
	                        'kode_sasaran'     => $data['kode_sasaran'],
	                        'label_sasaran'    => $data['label_sasaran'],
	                        'id_unik_indikator'=> $data['id_unik_indikator'],
	                        'label_indikator'  => $data['label_indikator'],
	                        'formulasi'        => $data['formulasi'],
	                        'sumber_data'      => $data['sumber_data'],
	                        'penanggung_jawab' => $data['penanggung_jawab'],
	                        'id_jadwal_wpsipd' => $data['id_jadwal_wpsipd'],
	                        'active'           => 1,
	                    ));
	                }
	            }
	        } else {
	            $ret['status'] = 'error';
	            $ret['message'] = 'API key tidak ditemukan!';
	        }
	    } else {
	        $ret['status'] = 'error';
	        $ret['message'] = 'Format salah!';
	    }
	    die(json_encode($ret));
	}

	function hapus_finalisasi_iku_opd()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus finalisasi IKU!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$cek_id = $wpdb->get_var(
					$wpdb->prepare('
						SELECT 
							id
						FROM esakip_finalisasi_tahap_iku_opd
						WHERE id = %d
						  AND active = 1
					', $_POST['id_tahap'])
				);
				if (empty($cek_id)) {
					$ret['status'] = 'error';
					$ret['message'] = 'Data finalisasi tidak ditemukan!';
					die(json_encode($ret));
				}
				$wpdb->update(
					'esakip_finalisasi_tahap_iku_opd',
					array('active' => 0),
					array(
						'id' => $cek_id,
						'active' => 1
					)
				);
				$wpdb->update(
					'esakip_finalisasi_iku_opd',
					array('active' => 0),
					array(
						'id_tahap' => $cek_id,
						'active' => 1
					)
				);
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

    function get_finalisasi_iku_by_id()
    {
        global $wpdb;
        $ret = array(
            'status'  => 'success',
            'message' => 'Berhasil get finalisasi!',
            'data' => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if ($ret['status'] != 'error') {
                    $ret['data'] = $wpdb->get_results(
					    $wpdb->prepare("
					        SELECT 
					            id,
					            nama_tahapan,
					            tanggal_dokumen
					        FROM esakip_finalisasi_tahap_iku_opd
					        WHERE id = %d
					          AND active = 1
					    ", $_POST['id_tahap']),
					    ARRAY_A
					);
                    // print_r($data_finalisasi_iku); die($wpdb->last_query);
                    foreach ($ret['data'] as $tahapan){
                    	$data_finalisasi_iku = $wpdb->get_results(
						    $wpdb->prepare("
						        SELECT 
						            *
						        FROM esakip_finalisasi_iku_opd
						        WHERE id_tahap = %d
						          AND active = 1
						    ", $tahapan['id']),
						    ARRAY_A
						);
						// print_r($data_finalisasi_iku); die($wpdb->last_query);
		                $html = '';
		                $no = 0;
		                if (!empty($data_finalisasi_iku)) {
						    foreach ($data_finalisasi_iku as $v) {
						        $no++;
						        $html .= '
						        <tr>
						            <td style="border: 1px solid black; text-align: center;">' . $no . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['label_sasaran'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['label_indikator'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['formulasi'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['sumber_data'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['penanggung_jawab'] . '</td>
						        </tr>';
						    }
						}
		                $ret['nama_tahapan'] = $tahapan['nama_tahapan'];
		                $ret['tanggal_dokumen'] = $tahapan['tanggal_dokumen'];
                    }
                    if (empty($html)) {
                        $html = '<tr><td class="text-center" colspan="18">Data masih kosong!</td></tr>';
                    }
                    $ret['html'] = $html;
                }
                
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'API key tidak ditemukan!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format salah!';
        }
        die(json_encode($ret));
    }
    
    function edit_finalisasi_iku() {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil edit finalisasi laporan PK!'
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

                if (empty($_POST['id_data'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'ID tidak valid atau kosong!';
                    die(json_encode($ret));
                }

                if (empty($_POST['id_skpd'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'ID OPD tidak boleh kosong!';
                    die(json_encode($ret));
                }

                if (empty($_POST['id_jadwal_wpsipd'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'ID jadwal tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $cek_id = $wpdb->get_results($wpdb->prepare("
                    SELECT 
                        * 
                    FROM esakip_finalisasi_tahap_iku_opd 
                    WHERE id = %d
                      AND active = 1 
                      AND id_skpd = %d
                ", $_POST['id_data'], $_POST['id_skpd']));

                if (empty($cek_id)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Data finalisasi tidak ditemukan!';
                    die(json_encode($ret));
                }

                foreach ($cek_id as $id) {
                    $wpdb->update(
                        'esakip_finalisasi_tahap_iku_opd',
                        array(
                            'nama_tahapan' => $_POST['nama_tahap'],
                            'tanggal_dokumen' => $_POST['tanggal_tahap']
                        ),
                        array(
                            'id' => $id->id,
                            'active' => 1
                        )
                    );
                }

            } else {
                $ret = array(
                    'status' => 'error',
                    'message' => 'Api Key tidak sesuai!'
                );
            }
        } else {
            $ret = array(
                'status' => 'error',
                'message' => 'Format tidak sesuai!'
            );
        }
        die(json_encode($ret));
    }

	
	function get_data_perbulan_ekinerja($opsi_param = array())
	{
		global $wpdb;
		$ret = array(
			'status' => true,
			'message' => 'Tidak Ada Update Data Dari Aplikasi E-Kinerja!',
			'data' => array(),
		);

		try {
			if (!get_option('_crb_url_api_ekinerja') || !get_option('_crb_api_key_ekinerja')) {
				throw new Exception("Pengaturan URL API E-Kinerja belum diisi!", 1);
			}

			if (!get_option('_crb_api_ekinerja_status')) {
				throw new Exception("Pengaturan Status API E-Kinerja ditutup!", 1);
			}

			if(get_option('_crb_input_renaksi') != 1){
				throw new Exception("Pengaturan Rencana Aksi Bulanan dan realisasi Triwulan Secara Manual!", 1);
			}

			if (empty($opsi_param['tahun'])) {
				throw new Exception("Tahun anggaran kosong!", 1);
			}

			if (empty($opsi_param['satker_id'])) {
				throw new Exception("Satker id kosong!", 1);
			}

			if (empty($opsi_param['tipe'])) {
				throw new Exception("Tipe Get Data kosong!", 1);
			}

			if (empty($opsi_param['id_skpd'])) {
				throw new Exception("Id Perangkat Daerah kosong!", 1);
			}

			if($opsi_param['tipe'] == 'indikator'){
				if (empty($opsi_param['nip'])) {
					throw new Exception("NIP kosong!", 1);
				}
				
				if (empty($opsi_param['id_rhk'])) {
					throw new Exception("Id Rencana Hasil Kerja kosong!", 1);
				}

				if (empty($opsi_param['id_indikator'])) {
					throw new Exception("Id Indikator kosong!", 1);
				}
			}

			$tahun = $opsi_param['tahun'];
			$satker_id = $opsi_param['satker_id'];
			$id_skpd = $opsi_param['id_skpd'];
			$nip = !empty($opsi_param['nip']) ? $opsi_param['nip'] : null;
			$id_rhk = !empty($opsi_param['id_rhk']) ? $opsi_param['id_rhk'] : null;
			$id_indikator = !empty($opsi_param['id_indikator']) ? $opsi_param['id_indikator'] : null;

			$body_param = array(
				'tahun' => $tahun,
				'satker_id' => $satker_id
			);

			if($opsi_param['tipe'] == 'indikator'){
				$body_param['nip'] = $nip;
			}

			$response = wp_remote_post(get_option('_crb_url_api_ekinerja') . 'dev/api/kinerjarhk', [
				'headers' => array(
					'X-api-key' => get_option('_crb_api_key_ekinerja'),
				),
				'body' => $body_param
			]);

			if (is_wp_error($response)) {
				$error_message = $response->get_error_message();
				$ret = array(
					'status'  => false,
					'is_error' => true,
					'message' => "Something went wrong: $error_message"
				);
				return json_encode($ret);
			}

			$data_ekin = json_decode(wp_remote_retrieve_body($response), true);

			if (isset($data_ekin['error'])) {
				if (is_array($response)) {
					$response = json_encode($response);
				}
				$ret = array(
					'status'  => false,
					'is_error' => true,
					'message' => "Gagal Mendapatkan Data E-Kinerja, Coba lagi! " . $response
				);
				return json_encode($ret);
			}
			if($data_ekin['status']){
				if(!empty($data_ekin['data'])){
					foreach($data_ekin['data'] as $k_ekin => $v_ekin){
						if(!empty($v_ekin['rencana_hasil_kerja'])){
							foreach ($v_ekin['rencana_hasil_kerja'] as $k_rhk => $v_rhk) {
								if($opsi_param['tipe'] == 'indikator'){
									if($v_rhk['rhk_id'] != $id_rhk){
										continue;
									}
								}

								if(!empty($v_rhk['indikator'])){
									foreach ($v_rhk['indikator'] as $k_indikator => $v_indikator) {
										if($opsi_param['tipe'] == 'indikator'){
											if($v_indikator['indikator_rhk_id'] != $id_indikator){
												continue;
											}
										}

										if(!empty($v_indikator['kinerja_bulan'])){
											foreach ($v_indikator['kinerja_bulan'] as $k_k_bulan => $v_k_bulan) {
												if(!empty($v_k_bulan['kinerja'])){
													$nama_aspek = array('kuantitas','kualitas','waktu','biaya');
													if(!empty($v_indikator['aspek_rhk'])){
														$nama_aspek = $nama_aspek[$v_indikator['aspek_rhk']-1];
													}else{
														$nama_aspek = $nama_aspek[0];
													}

													$volume_api_string = $rencana_aksi_api_string = $satuan_bulan_api_string = $realisasi_api_string = $keterangan_api_string = $capaian_api_string = '';
													$volume_api = $rencana_aksi_api = $satuan_bulan_api = $realisasi_api = $keterangan_api = $capaian_api = array();
													foreach ($v_k_bulan['kinerja'] as $k_kinerja => $v_kinerja) {
														array_push($volume_api,$v_kinerja['target_'.$nama_aspek]);
														array_push($rencana_aksi_api,$v_kinerja['kegiatan']);
														array_push($satuan_bulan_api,$v_kinerja['satuan']);
														array_push($realisasi_api,$v_kinerja['realisasi_'.$nama_aspek]);
														array_push($keterangan_api,$v_kinerja['catatan']);
														array_push($capaian_api,$v_kinerja['capaian_'.$nama_aspek]);
													}
													$volume_api_string = serialize($volume_api);
													$rencana_aksi_api_string = serialize($rencana_aksi_api);
													$satuan_bulan_api_string = serialize($satuan_bulan_api);
													$realisasi_api_string = serialize($realisasi_api);
													$keterangan_api_string = serialize($keterangan_api);
													$capaian_api_string = serialize($capaian_api);

													$data_option = array(
														'id_indikator_renaksi_opd' => $v_indikator['indikator_rhk_id'],
														'id_skpd' => $id_skpd,
														'bulan' => $v_k_bulan['bulan'],
														'volume' => $volume_api_string,
														'rencana_aksi' => $rencana_aksi_api_string,
														'satuan_bulan' => $satuan_bulan_api_string,
														'realisasi' => $realisasi_api_string,
														'keterangan' => $keterangan_api_string,
														'capaian' => $capaian_api_string,
														'tahun_anggaran' => $tahun,
														'active' => 1,
														'created_at' => current_time('mysql'),
													);
			
													$cek_id_indikator = $wpdb->get_var($wpdb->prepare(
														"SELECT
															id
														FROM
															esakip_data_rencana_aksi_indikator_opd
														WHERE
															id=%d
															AND id_renaksi=%d
															AND tahun_anggaran=%d
															AND id_skpd=%d
															AND active=1",
														$v_indikator['indikator_rhk_id'], $v_rhk['rhk_id'], $tahun, $id_skpd
													));
			
													if(!empty($cek_id_indikator)){
														// Cek apakah data sudah ada
														$cek_id = $wpdb->get_var($wpdb->prepare("
															SELECT id 
															FROM esakip_data_bulanan_rencana_aksi_opd
															WHERE id_indikator_renaksi_opd = %d
																AND tahun_anggaran = %d
																AND id_skpd = %d
																AND bulan = %d
																AND active = %d
														", $v_indikator['indikator_rhk_id'], $tahun, $id_skpd, $v_k_bulan['bulan'], 1));

														if (empty($cek_id)) {
															$wpdb->insert('esakip_data_bulanan_rencana_aksi_opd', $data_option);
															$ret['message'] = "Berhasil simpan target dan realisasi bulanan dari data Aplikasi E-Kinerja!";
														} else {
															$wpdb->update(
																'esakip_data_bulanan_rencana_aksi_opd',
																$data_option,
																array('id' => $cek_id)
															);
															$ret['message'] = "Berhasil update target dan realisasi bulanan dari data Aplikasi E-Kinerja!";
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
				}else{
					throw new Exception("Respone API Kosong!", 1);
				}
			}else{
				throw new Exception("Message: ".$data_ekin['message'], 1);
			}
		} catch (Exception $e) {
			$ret = array(
				'status'  => false,
				'message' => $e->getMessage()
			);
		}
		return json_encode($ret);
	}

	function simpan_finalisasi_iku_pemda()
	{
	    global $wpdb;
	    $ret = array(
	        'status'  => 'success',
	        'message' => 'Berhasil finalisasi IKU!'
	    );

	    if (!empty($_POST)) {
	        if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
	            if (empty($_POST['data_iku']['nama_tahapan']) || empty($_POST['data_iku']['tanggal_dokumen'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Nama tahapan dan tanggal dokumen wajib diisi!';
	                die(json_encode($ret));
	            }

	            $data_iku = $_POST['data_iku'];
	            $data_simpan = $_POST['data_simpan'];
	            $nama_tahapan = $data_iku['nama_tahapan'];
	            $tanggal_dokumen = $data_iku['tanggal_dokumen'];

	            $cek = $wpdb->get_var(
	                $wpdb->prepare("
	                	SELECT 
	                    	* 
	                    FROM esakip_finalisasi_tahap_iku_pemda 
	                    WHERE nama_tahapan = %s 
		                     AND tanggal_dokumen = %s 
		                     AND active = 1
		                ", $nama_tahapan, $tanggal_dokumen
	                )
	            );

	            if ($cek > 0) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Nama Tahapan dan Dokumen sudah ada!';
	                die(json_encode($ret));
	            }

	            $insert_data = array(
	                'nama_tahapan'    => $nama_tahapan,
	                'tanggal_dokumen' => $tanggal_dokumen,
	                'id_jadwal' => isset($data_simpan[0]['id_jadwal']) ? $data_simpan[0]['id_jadwal'] : null,
	                'active'          => 1,
	            );
	            
	            $wpdb->insert('esakip_finalisasi_tahap_iku_pemda', $insert_data);
	            $id_tahap = $wpdb->insert_id; 
	            
	            if ($id_tahap) {
	                foreach ($data_simpan as $data) {
	                    $wpdb->insert('esakip_finalisasi_iku_pemda', array(
	                        'id_tahap'         => $id_tahap,
	                        'kode_sasaran'     => $data['kode_sasaran'],
	                        'label_sasaran'    => $data['label_sasaran'],
	                        'id_unik_indikator'=> $data['id_unik_indikator'],
	                        'label_indikator'  => $data['label_indikator'],
	                        'formulasi'        => $data['formulasi'],
	                        'sumber_data'      => $data['sumber_data'],
	                        'penanggung_jawab' => $data['penanggung_jawab'],
	                        'id_jadwal' => $data['id_jadwal'],
	                        'active'           => 1,
	                    ));
	                }
	            }
	        } else {
	            $ret['status'] = 'error';
	            $ret['message'] = 'API key tidak ditemukan!';
	        }
	    } else {
	        $ret['status'] = 'error';
	        $ret['message'] = 'Format salah!';
	    }
	    die(json_encode($ret));
	}

	function hapus_finalisasi_iku_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus finalisasi IKU!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$cek_id = $wpdb->get_var(
					$wpdb->prepare('
						SELECT 
							id
						FROM esakip_finalisasi_tahap_iku_pemda
						WHERE id = %d
						  AND active = 1
					', $_POST['id_tahap'])
				);
				if (empty($cek_id)) {
					$ret['status'] = 'error';
					$ret['message'] = 'Data finalisasi tidak ditemukan!';
					die(json_encode($ret));
				}
				$wpdb->update(
					'esakip_finalisasi_tahap_iku_pemda',
					array('active' => 0),
					array(
						'id' => $cek_id,
						'active' => 1
					)
				);
				$wpdb->update(
					'esakip_finalisasi_iku_pemda',
					array('active' => 0),
					array(
						'id_tahap' => $cek_id,
						'active' => 1
					)
				);
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


    function get_finalisasi_iku_pemda_by_id()
    {
        global $wpdb;
        $ret = array(
            'status'  => 'success',
            'message' => 'Berhasil get finalisasi!',
            'data' => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if ($ret['status'] != 'error') {
                    $ret['data'] = $wpdb->get_results(
					    $wpdb->prepare("
					        SELECT 
					            id,
					            nama_tahapan,
					            tanggal_dokumen
					        FROM esakip_finalisasi_tahap_iku_pemda
					        WHERE id = %d
					          AND active = 1
					    ", $_POST['id_tahap']),
					    ARRAY_A
					);
                    // print_r($data_finalisasi_iku_pemda); die($wpdb->last_query);
                    foreach ($ret['data'] as $tahapan){
                    	$data_finalisasi_iku_pemda = $wpdb->get_results(
						    $wpdb->prepare("
						        SELECT 
						            *
						        FROM esakip_finalisasi_iku_pemda
						        WHERE id_tahap = %d
						          AND active = 1
						    ", $tahapan['id']),
						    ARRAY_A
						);
						// print_r($data_finalisasi_iku_pemda); die($wpdb->last_query);
		                $html = '';
		                $no = 0;
		                if (!empty($data_finalisasi_iku_pemda)) {
		                    foreach ($data_finalisasi_iku_pemda as $v) {
		                        $no++;
		                        $html .= '
						        <tr>
						            <td style="border: 1px solid black; text-align: center;">' . $no . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['label_sasaran'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['label_indikator'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['formulasi'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['sumber_data'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['penanggung_jawab'] . '</td>
						        </tr>';
		                    }
		                }
		                $ret['nama_tahapan'] = $tahapan['nama_tahapan'];
		                $ret['tanggal_dokumen'] = $tahapan['tanggal_dokumen'];
                    }
                    if (empty($html)) {
                        $html = '<tr><td class="text-center" colspan="18">Data masih kosong!</td></tr>';
                    }
                    $ret['html'] = $html;
                }
                
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'API key tidak ditemukan!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format salah!';
        }
        die(json_encode($ret));
    }
    
    function edit_finalisasi_iku_pemda() {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil edit finalisasi laporan PK!'
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

                if (empty($_POST['id_data'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'ID tidak valid atau kosong!';
                    die(json_encode($ret));
                }

                if (empty($_POST['id_jadwal'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'ID jadwal tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $cek_id = $wpdb->get_results($wpdb->prepare("
                    SELECT 
                        * 
                    FROM esakip_finalisasi_tahap_iku_pemda 
                    WHERE id = %d
                      AND active = 1 
                ", $_POST['id_data']));

                if (empty($cek_id)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Data finalisasi tidak ditemukan!';
                    die(json_encode($ret));
                }

                foreach ($cek_id as $id) {
                    $wpdb->update(
                        'esakip_finalisasi_tahap_iku_pemda',
                        array(
                            'nama_tahapan' => $_POST['nama_tahap'],
                            'tanggal_dokumen' => $_POST['tanggal_tahap']
                        ),
                        array(
                            'id' => $id->id,
                            'active' => 1
                        )
                    );
                }

            } else {
                $ret = array(
                    'status' => 'error',
                    'message' => 'Api Key tidak sesuai!'
                );
            }
        } else {
            $ret = array(
                'status' => 'error',
                'message' => 'Format tidak sesuai!'
            );
        }
        die(json_encode($ret));
    }

	function get_data_target_bulanan_ekinerja()
	{
	    global $wpdb;
	    $ret = array(
	        'status'  => 'success',
	        'message' => 'Tidak Ada Update Data Dari Aplikasi E-Kinerja!',
			'show_alert_bulanan' => 0
	    );

	    if (!empty($_POST)) {
	        if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
	            if (empty($_POST['tahun'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Tahun Anggaran Kosong!';
	            }

				if (empty($_POST['satker_id'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Satker ID Kosong!';
	            }

				if (empty($_POST['id_skpd'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'ID SKPD Kosong!';
	            }

				if (empty($_POST['tipe'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Tipe Data Kosong!';
	            }

	            $tahun = $_POST['tahun'];
	            $satker_id = $_POST['satker_id'];
	            $id_skpd = $_POST['id_skpd'];
	            $tipe = $_POST['tipe'];

	            if($ret['status'] == 'success'){
					$opsi_param = array(
						'tahun' => $tahun,
						'satker_id' => $satker_id,
						'id_skpd' => $id_skpd,
						'tipe' => $tipe
					);
				
					$data_ekin = $this->get_data_perbulan_ekinerja($opsi_param);
					$data_ekin_terbaru = json_decode($data_ekin, true);
					$ret['message'] = $data_ekin_terbaru['message'];
					if(!empty($data_ekin_terbaru['is_error']) && $data_ekin_terbaru['is_error']){
						$ret['show_alert_bulanan'] = 1;
					}
				}
	        } else {
	            $ret['status'] = 'error';
	            $ret['message'] = 'API key tidak ditemukan!';
	        }
	    } else {
	        $ret['status'] = 'error';
	        $ret['message'] = 'Format salah!';
	    }
	    die(json_encode($ret));
	}

	function get_data_pokin_rhk($id_rhk, $level_rhk, $type_rhk = 'opd', $pokin_level_1 = false){
		global $wpdb;
		$html_label_pokin = $table_rhk_prefix = $table_pokin_prefix = '';

		if(!empty($id_rhk) && !empty($level_rhk)){
			if($type_rhk == 'opd'){
				$table_rhk_prefix = '_opd';
				$table_pokin_prefix = '_opd';
			}elseif ($type_rhk == 'pemda') {
				$table_rhk_prefix = '_pemda';
				$table_pokin_prefix = '';
			}

			// ----- untuk mendapatkan pokin level 1 ----- //
			$level_pokin = $pokin_level_1 ? $level_rhk : $level_rhk+1;
			
			$data_pokin = $wpdb->get_results(
				$wpdb->prepare("
					SELECT
						o.id_pokin,
						p.label AS pokin_label
					FROM esakip_data_pokin_rhk$table_rhk_prefix AS o
					INNER JOIN esakip_pohon_kinerja$table_pokin_prefix AS p 
						ON o.id_pokin = p.id
							AND o.level_pokin = p.level
					WHERE o.id_rhk$table_rhk_prefix = %d
						AND o.level_rhk$table_rhk_prefix = %d
						AND o.level_pokin = %d
						AND o.active=1
						AND p.active=1
				", $id_rhk, $level_rhk, $level_pokin),
				ARRAY_A
			);
			
			if(!empty($data_pokin)){
				$html_label_pokin = '<ul style="margin: 0; list-style-type: circle;">';
					foreach ($data_pokin as $k_label_pokin => $v_label_pokin) {
						$html_label_pokin .= '<li>' . $v_label_pokin['pokin_label'] . '</li>';
					}
					$html_label_pokin .= '</ul>';
			}
		}
		
		return $html_label_pokin;
	}
}
