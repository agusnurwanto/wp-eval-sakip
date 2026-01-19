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

	public function halaman_laporan_pk_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-laporan-pk-pemda.php';
	}

	public function capaian_iku_opd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/monev-kinerja/wp-eval-sakip-iku-opd.php';
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
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					if ($_POST['tipe_pokin'] == '') {
						$data_renaksi = array();
					} else if ($_POST['tipe_pokin'] == 'opd') {
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
							ORDER BY kode_cascading_sasaran, 
								id_sub_skpd_cascading,
								kode_cascading_program, 
								kode_cascading_kegiatan, 
								kode_cascading_sub_kegiatan,
								id
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
									p.level,
									o.id_pokin,
						            p.label AS pokin_label
						        FROM esakip_data_pokin_rhk_opd AS o
						        INNER JOIN esakip_pohon_kinerja_opd AS p 
						            ON o.id_pokin = p.id
					                AND o.level_pokin = p.level
					                AND o.active = p.active
						        WHERE o.id_rhk_opd = %d
						            AND o.level_rhk_opd = %d
									AND o.active=1
						    ", $val['id'], $val['level']),
							ARRAY_A
						);

						if (empty($val['id_jabatan_asli'])) {
							$data_renaksi[$key]['detail_pegawai'] = $wpdb->get_row($wpdb->prepare(
								"
								SELECT
									*
								FROM esakip_data_pegawai_simpeg
								WHERE nip_baru = %d
									AND satker_id = %d
								ORDER BY active DESC
							",
								$val['nip'],
								$val['id_jabatan']
							), ARRAY_A);
						} else {
							$data_renaksi[$key]['detail_pegawai'] = $wpdb->get_row($wpdb->prepare(
								"
								SELECT
									*
								FROM esakip_data_pegawai_simpeg
								WHERE nip_baru = %d
									AND satker_id = %d
									AND id_jabatan = %d
								ORDER BY active DESC
							",
								$val['nip'],
								$val['id_jabatan'],
								$val['id_jabatan_asli']
							), ARRAY_A);
						}
						$data_renaksi[$key]['detail_satker'] = $wpdb->get_row($wpdb->prepare(
							"
							SELECT
								*
							FROM esakip_data_satker_simpeg
							WHERE satker_id = %d
								AND active=1
								AND tahun_anggaran = %d
						",
							$val['satker_id'], $val['tahun_anggaran'] 
						), ARRAY_A);

						// mengambil data dasar pelaksanaan dan nilai rencana pagu RHK
						$data_renaksi[$key]['get_data_dasar'] = array($val);
						$data_renaksi[$key]['total_pagu'] = 0;
						if ($val['input_rencana_pagu_level'] != 1) {
							$ids_rhk_parent_end = array();
							$data_renaksi[$key]['rhk_input_pagu'] = array();
							if ($_POST['level'] == 3) {
								$ids_rhk_parent_end = array($val['id']);
							} elseif ($_POST['level'] == 2) {
								$data_renaksi[$key]['get_dasar_level_3'] = $wpdb->get_results($wpdb->prepare("
								    SELECT
								        *
								    FROM esakip_data_rencana_aksi_opd
								    WHERE parent=%d
								      AND level=3
								      AND active=1
								", $val['id']), ARRAY_A);

								foreach ($data_renaksi[$key]['get_dasar_level_3'] as $key3 => $val3) {
									if ($val3['input_rencana_pagu_level'] == 1) {
										$data_renaksi[$key]['rhk_input_pagu'][] = $val3;
										continue;
									}
									$ids_rhk_parent_end[] = $val3['id'];
								}
							} elseif ($_POST['level'] == 1) {
								$data_renaksi[$key]['get_dasar_level_2'] = $wpdb->get_results($wpdb->prepare("
								    SELECT
								        *
								    FROM esakip_data_rencana_aksi_opd
								    WHERE parent=%d
								      AND level=2
								      AND active=1
								", $val['id']), ARRAY_A);

								foreach ($data_renaksi[$key]['get_dasar_level_2'] as $key2 => $val2) {
									if ($val2['input_rencana_pagu_level'] == 1) {
										$data_renaksi[$key]['rhk_input_pagu'][] = $val2;
										continue;
									}
									$data_renaksi[$key]['get_dasar_level_3'][$key2] = $wpdb->get_results($wpdb->prepare("
									    SELECT
									        *
									    FROM esakip_data_rencana_aksi_opd
									    WHERE parent=%d
									      AND level=3
									      AND active=1
									", $val2['id']), ARRAY_A);

									foreach ($data_renaksi[$key]['get_dasar_level_3'][$key2] as $key3 => $val3) {
										if ($val3['input_rencana_pagu_level'] == 1) {
											$data_renaksi[$key]['rhk_input_pagu'][] = $val3;
											continue;
										}
										$ids_rhk_parent_end[] = $val3['id'];
									}
								}
							}
							if (!empty($ids_rhk_parent_end)) {
								$ids_rhk_parent_end = implode(',', $ids_rhk_parent_end);
								$data_renaksi[$key]['get_data_dasar'] = $wpdb->get_results("
							        SELECT
							            *
							        FROM esakip_data_rencana_aksi_opd
							        WHERE parent IN ($ids_rhk_parent_end)
							          AND level=4
							          AND active=1
							    ", ARRAY_A);
							}
							if (!empty($data_renaksi[$key]['rhk_input_pagu'])) {
								$data_renaksi[$key]['get_data_dasar'] = array_merge($data_renaksi[$key]['rhk_input_pagu'], $data_renaksi[$key]['get_data_dasar']);
							}
						}

						// mendapatkan nilai total
						if (!empty($data_renaksi[$key]['get_data_dasar'])) {
							foreach ($data_renaksi[$key]['get_data_dasar'] as $key4 => $val4) {
								$data_renaksi[$key]['total_pagu'] += $wpdb->get_var($wpdb->prepare("
									SELECT
										SUM(rencana_pagu) as total_pagu
									FROM esakip_data_rencana_aksi_indikator_opd 
									WHERE id_renaksi=%d
										AND active = 1
								", $val4['id']));
							}
						}

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
					$parent_sql = '';
					if (!empty($label_parent)) {
						$dataParent = $wpdb->get_row($wpdb->prepare(
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
						$parent_sql = $wpdb->last_query;
					}

					die(json_encode([
						'status' => true,
						'data' => $data_renaksi,
						'data_parent' => array_values($dataParent),
						'parent_sql' => $parent_sql,
						'last_sql' => $wpdb->last_query
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

	function cek_validasi_input_rencana_pagu($return)
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil cek rencana pagu RHK!',
            'rencana_pagu'  => 0,
            'ids' => array(),
            'ids_indikator' => array(),
            'data_rhk'  => 0,
            'data_rhk_parent'  => 0
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                $cek_rhk_level_turunan = $this->get_rhk_child(array(
                    'id' => $_POST['id'],
                    'tahun' => $_POST['tahun_anggaran'],
                    'check_input_pagu' => true
                ));
                $ret['cek_rhk_level_turunan'] = $cek_rhk_level_turunan;
                $rencana_pagu = 0;
                foreach ($cek_rhk_level_turunan as $level => $rhk_all) {
                    foreach ($rhk_all as $rhk) {
                        $pagu_all = $wpdb->get_results($wpdb->prepare("
                            SELECT
                                i.id as id_indikator,
                                s.id,
                                s.rencana_pagu
                            FROM esakip_data_rencana_aksi_indikator_opd as i
                            INNER JOIN esakip_sumber_dana_indikator as s on i.id=s.id_indikator
                                AND s.active=i.active
                                AND s.tahun_anggaran=i.tahun_anggaran
                            WHERE i.id_renaksi=%d
                                AND i.active=1
                                AND i.tahun_anggaran=%d
                        ", $rhk['id'], $rhk['tahun_anggaran']), ARRAY_A);
                        foreach ($pagu_all as $pagu) {
                            $ret['rencana_pagu'] += $pagu['rencana_pagu'];
                            $ret['ids'][] = $pagu['id'];
                            $ret['ids_indikator'][$pagu['id_indikator']] = $pagu['id_indikator'];
                        }
                    }
                }
                $data_rhk = $wpdb->get_results($wpdb->prepare("
                    SELECT
                        *
                    FROM esakip_data_rencana_aksi_opd
                    WHERE id=%d
                        AND active=1
                ", $_POST['id']), ARRAY_A);
                if (!empty($data_rhk)) {
                    $ret['data_rhk'] = $data_rhk;
                }
                $data_rhk_parent = $wpdb->get_results($wpdb->prepare("
                    SELECT
                        *
                    FROM esakip_data_rencana_aksi_opd
                    WHERE id=%d
                        AND active=1
                ", $_POST['id_parent']), ARRAY_A);
                if (!empty($data_rhk_parent)) {
                    $ret['data_rhk_parent'] = $data_rhk_parent;
                }

                if (!empty($ret['rencana_pagu'])) {
                    // Untuk validasi agar setting input rencana pagu tetap di level RHK paling akhir
                    $ret['status'] = 'error';
                    $ret['message'] = "Rencana pagu RHK sebesar Rp " . number_format($ret['rencana_pagu'], 0, ",", ".") . " sudah diinput di level bawahnya. Nilai pagu RHK level dibawah RHK ini akan di 0 kan atau dipindah ke RHK yang saat ini. Untuk rincian belanja perlu dipindahkan manual. Apakah kamu yakin untuk melanjutkan proses ini?";
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
        if (!empty($return)) {
            return $ret;
        }
        die(json_encode($ret));
    }

	function cek_input_pagu_parent($return)
	{
		global $wpdb;
		if (empty($return)) {
			$return = array(
				'status' => 'success',
				'message' => 'Berhasil cek input pagu parent RHK!',
				'data' => array(),
				'input_pagu' => 0
			);
		}
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$rhk = $wpdb->get_row($wpdb->prepare("
					SELECT
						*
					FROM esakip_data_rencana_aksi_opd
					WHERE id=%d
				", $_POST['id_parent']), ARRAY_A);
				if (!empty($rhk)) {
					if (
						$rhk['input_rencana_pagu_level'] == 0
						&& !empty($rhk['parent'])
					) {
						$return['data'][$rhk['level']] = $rhk;
						$_POST['id_parent'] = $rhk['parent'];
						$return = $this->cek_input_pagu_parent($return);
					} else {
						if ($rhk['input_rencana_pagu_level'] == 1) {
							$return['input_pagu'] = 1;
							$return['level'] = $rhk['level'];
						}

						// jika ini reverse function terkahir
						if (!empty($return['data'])) {
							return $return;
						}

						$return['data'][$rhk['level']] = $rhk;
					}

					// jika hasil select kosong dan ini reverse function terkahir
				} else if (!empty($return['data'])) {
					return $return;
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
				}

				// else if ($ret['status'] != 'error' && empty($_POST['id_pokin_1'])) {
				// 	$ret['status'] = 'error';
				// 	if ($_POST['id_pokin_1'] && ($_POST['level'] == 1)) {
				// 		$ret['message'] = 'Level 1 POKIN tidak boleh kosong!';
				// 	} else if ($_POST['id_pokin_1'] && ($_POST['level'] == 2)) {
				// 		$ret['message'] = 'Level 3 POKIN tidak boleh kosong!';
				// 	} else if ($_POST['id_pokin_1'] && ($_POST['level'] == 3)) {
				// 		$ret['message'] = 'Level 4 POKIN tidak boleh kosong!';
				// 	} else if ($_POST['id_pokin_1'] && ($_POST['level'] == 4)) {
				// 		$ret['message'] = 'Level 5 POKIN tidak boleh kosong!';
				// 	} else if ($_POST['id_pokin_2']) {
				// 		$ret['message'] = 'Level 2 POKIN tidak boleh kosong!';
				// 	}
				// } else 
				if ($ret['status'] != 'error' && empty($_POST['label_renaksi'])) {
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

				$is_tujuan = isset($_POST['is_tujuan']) ? sanitize_text_field($_POST['is_tujuan']) : '0';
				$id_uraian_cascading = !empty($_POST['id_uraian_cascading']) ? $_POST['id_uraian_cascading'] : NULL;
				$kode_cascading_renstra = !empty($_POST['kode_cascading_renstra']) || $_POST['kode_cascading_renstra'] != NULL ? $_POST['kode_cascading_renstra'] : NULL;
				$label_cascading_renstra = !empty($_POST['label_cascading_renstra']) || $_POST['label_cascading_renstra'] != NULL ? $_POST['label_cascading_renstra'] : NULL;

				$kode_cascading_renstra_program = !empty($_POST['kode_cascading_renstra_program']) || $_POST['kode_cascading_renstra_program'] != NULL ? $_POST['kode_cascading_renstra_program'] : NULL;
				$label_cascading_renstra_program = !empty($_POST['label_cascading_renstra_program']) || $_POST['label_cascading_renstra_program'] != NULL ? $_POST['label_cascading_renstra_program'] : NULL;

				$kode_cascading_renstra_kegiatan = !empty($_POST['kode_cascading_renstra_kegiatan']) || $_POST['kode_cascading_renstra_kegiatan'] != NULL ? $_POST['kode_cascading_renstra_kegiatan'] : NULL;
				$label_cascading_renstra_kegiatan = !empty($_POST['label_cascading_renstra_kegiatan']) || $_POST['label_cascading_renstra_kegiatan'] != NULL ? $_POST['label_cascading_renstra_kegiatan'] : NULL;

				$kode_cascading_renstra_sub_kegiatan = !empty($_POST['kode_cascading_renstra_sub_kegiatan']) || $_POST['kode_cascading_renstra_sub_kegiatan'] != NULL ? $_POST['kode_cascading_renstra_sub_kegiatan'] : NULL;
				$label_cascading_renstra_sub_kegiatan = !empty($_POST['label_cascading_renstra_sub_kegiatan']) || $_POST['label_cascading_renstra_sub_kegiatan'] != NULL ? $_POST['label_cascading_renstra_sub_kegiatan'] : NULL;

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
				$status_input_rencana_pagu = isset($_POST['status_input_rencana_pagu']) ? intval($_POST['status_input_rencana_pagu']) : 0;
				if ($_POST['level'] == 4) {
					$setting_input_rencana_pagu = 1;
				} else {
					$setting_input_rencana_pagu = !empty($_POST['setting_input_rencana_pagu']) ? 1 : 0;
				}

				if ($ret['status'] != 'error' && $setting_input_rencana_pagu == 1 && $_POST['level'] != 4 && $status_input_rencana_pagu == 0) {
	                $kode_sub_kegiatan = isset($_POST['kode_cascading_renstra_sub_kegiatan']) ? trim($_POST['kode_cascading_renstra_sub_kegiatan']) : '';
	                
	                if (empty($kode_sub_kegiatan)) {
	                    $ret['status'] = 'error';
	                    $ret['message'] = 'Jika Pengaturan Input Rencana Pagu dicentang, Sub Kegiatan Cascading wajib diisi!';
	                }
	            }
	            
	            if ($ret['status'] != 'error' && $setting_input_rencana_pagu == 1 && $status_input_rencana_pagu == 0) {
	                $label_renaksi = isset($_POST['label_renaksi']) ? trim($_POST['label_renaksi']) : '';
	                $id_uraian_cascading = isset($_POST['id_uraian_cascading']) ? intval($_POST['id_uraian_cascading']) : 0;
	                
	                if (empty($label_renaksi) || 
	                    $label_renaksi == '' || 
	                    $id_uraian_cascading == 0) {
	                    $ret['status'] = 'error';
	                    $ret['message'] = 'Jika Pengaturan Input Rencana Pagu dicentang, wajib memilih Uraian Cascading dari Sub Kegiatan!';
	                }
	            }
	            
	            if ($ret['status'] != 'error' && $setting_input_rencana_pagu == 0 && $status_input_rencana_pagu == 0) {
	                if ($_POST['level'] == 1 && $_POST['level'] == 1) {
	                    if (empty($_POST['kode_cascading_renstra'])) {
	                        $ret['status'] = 'error';
	                        $ret['message'] = 'Sasaran Cascading tidak boleh kosong!';
	                    }
	                } else if ($_POST['level'] == 2) {
	                    if (empty($_POST['kode_cascading_renstra'])) {
	                        $ret['status'] = 'error';
	                        $ret['message'] = 'Program Cascading tidak boleh kosong!';
	                    }
	                    $id_uraian_cascading = isset($_POST['id_uraian_cascading']) ? intval($_POST['id_uraian_cascading']) : 0;
	                    if ($id_uraian_cascading == 0) {
	                        $ret['status'] = 'error';
	                        $ret['message'] = 'Uraian Cascading Program wajib dipilih!';
	                    }
	                } else if ($_POST['level'] == 3) {
	                    if (empty($_POST['kode_cascading_renstra'])) {
	                        $ret['status'] = 'error';
	                        $ret['message'] = 'Kegiatan Cascading tidak boleh kosong!';
	                    }
	                    $id_uraian_cascading = isset($_POST['id_uraian_cascading']) ? intval($_POST['id_uraian_cascading']) : 0;
	                    if ($id_uraian_cascading == 0) {
	                        $ret['status'] = 'error';
	                        $ret['message'] = 'Uraian Cascading Kegiatan wajib dipilih!';
	                    }
	                }
	            }


				$get_dasar_pelaksanaan = $_POST['get_dasar_pelaksanaan'];
				if (!empty($label_cascading_renstra) && $_POST['level'] != 1) {
					$label = explode(' ', $label_cascading_renstra);
					unset($label[0]);

					$label_cascading_renstra = implode(' ', $label);
				}

				// --- untuk input cascading saat ada input rencana pagu di rhk --- //
				if (!empty($label_cascading_renstra_program)) {
					$label = explode(' ', $label_cascading_renstra_program);
					unset($label[0]);

					$label_cascading_renstra_program = implode(' ', $label);
				}
				if (!empty($label_cascading_renstra_kegiatan)) {
					$label = explode(' ', $label_cascading_renstra_kegiatan);
					unset($label[0]);

					$label_cascading_renstra_kegiatan = implode(' ', $label);
				}
				if (!empty($label_cascading_renstra_sub_kegiatan)) {
					$label = explode(' ', $label_cascading_renstra_sub_kegiatan);
					unset($label[0]);

					$label_cascading_renstra_sub_kegiatan = implode(' ', $label);
				}

				// if (
				// 	$_POST['level'] == 1
				// 	&& $ret['status'] != 'error'
				// 	&& empty($_POST['id_pokin_2'])
				// ) {
				// 	$ret['status'] = 'error';
				// 	$ret['message'] = 'Level 2 POKIN tidak boleh kosong!';
				// }

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
						'id_jabatan_asli' => $_POST['id_jabatan_asli'],
						'satker_id' => $_POST['satker_id'],
						'active' => 1,
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						'created_at' => current_time('mysql'),
						'mandatori_pusat' => isset($get_dasar_pelaksanaan['mandatori_pusat']) ? $get_dasar_pelaksanaan['mandatori_pusat'] : 0,
						'inisiatif_kd' => isset($get_dasar_pelaksanaan['inisiatif_kd']) ? $get_dasar_pelaksanaan['inisiatif_kd'] : 0,
						'musrembang' => isset($get_dasar_pelaksanaan['musrembang']) ? $get_dasar_pelaksanaan['musrembang'] : 0,
						'pokir' => isset($get_dasar_pelaksanaan['pokir']) ? $get_dasar_pelaksanaan['pokir'] : 0,
						'id_sub_skpd_cascading' => $id_sub_skpd_cascading,
						'pagu_cascading' => $pagu_cascading,
						'input_rencana_pagu_level' => $setting_input_rencana_pagu,
						'id_cascading' => $id_uraian_cascading,
						'status_input_rencana_pagu' => $status_input_rencana_pagu,
						'cascading_pk' => $_POST['cascading_pk'],
						'is_tujuan' => $is_tujuan,
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

					if ($setting_input_rencana_pagu == 1) {
						if (!empty($kode_cascading_renstra_program)) {
							$data['kode_cascading_program'] = $kode_cascading_renstra_program;
							$data['label_cascading_program'] = $label_cascading_renstra_program;
						}
						if (!empty($kode_cascading_renstra_kegiatan)) {
							$data['kode_cascading_kegiatan'] = $kode_cascading_renstra_kegiatan;
							$data['label_cascading_kegiatan'] = $label_cascading_renstra_kegiatan;
						}
						if (!empty($kode_cascading_renstra_sub_kegiatan)) {
							$data['kode_cascading_sub_kegiatan'] = $kode_cascading_renstra_sub_kegiatan;
							$data['label_cascading_sub_kegiatan'] = $label_cascading_renstra_sub_kegiatan;
							$data['kode_sbl'] = $_POST['kode_sbl'];
						}
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
						$status_update = false;
						if (
							$_POST['level'] == 2
							|| $_POST['level'] == 3
						) {
							/** Untuk validasi agar cascading parent tetap sama dengan cascading child yang telah dipilih */
							$level_child = $_POST['level'] + 1;
							$nama_kolom = array(
								'3' => 'kode_cascading_kegiatan',
								'4' => 'kode_cascading_sub_kegiatan'
							);
							$nama_kolom = $nama_kolom[$level_child];

							$cek_cascading_child = $wpdb->get_results(
								$wpdb->prepare(
									"
									SELECT
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
								foreach ($cek_cascading_child as $cek_cas) {
									if (
										!empty($cek_cas[$nama_kolom])
										&& (
											strpos(trim($cek_cas[$nama_kolom]), trim($kode_cascading_renstra)) !== 0
											|| $cek_cas['id_sub_skpd_cascading'] != $id_sub_skpd_cascading
										)
									) {
										if (empty($status_update)) {
											$status_update = array();
										}
										$status_update[] = $nama_kolom . ' ' . $cek_cas[$nama_kolom] . ' dengan Id SKPD ' . $id_sub_skpd_cascading . ' tidak ditemukan!';
									}
								}
							} else {
								$status_update = false;
							}
						}

						if (empty($status_update)) {
							$wpdb->update('esakip_data_rencana_aksi_opd', $data, array('id' => $cek_id));
						} else {
							$nama_kolom = array(
								'2' => 'Program',
								'3' => 'Kegiatan'
							);
							$ret = array(
								'status' => 'error',
								'message'   => 'Data ' . $nama_kolom[$_POST['level']] . ' Cascading tidak dapat dirubah menjadi ' . $kode_cascading_renstra . ' dengan ID SKPD ' . $id_sub_skpd_cascading . '! Harap hapus/kosongkan/perbarui data Cascading di RHK level bawahnya! ' . implode(' | ', $status_update)
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
							", $_POST['id_skpd'], $_POST['tahun_anggaran'], $cek_id, $ceklist['id_data_renaksi_pemda']));
							if (empty($cek)) {
								$wpdb->insert('esakip_data_label_rencana_aksi', array(
									'parent_renaksi_pemda' => $checklist['id_data_renaksi_pemda'],
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
					$get_id_pokin_3 = $_POST['id_pokin_3'];
					$get_id_pokin_4 = $_POST['id_pokin_4'];
					$get_id_pokin_5 = $_POST['id_pokin_5'];

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

						// karena jika input pagu tidak diceklist maka, semua pokin dimasukan ke pokin level 1
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
							$ret['message'] = "Berhasil menyimpan data.";
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
							$level_pokin_2 = 2;
							$data = array(
								'id_rhk_opd' => $cek_id,
								'id_pokin' => $id_pokin_lvl_2,
								'level_pokin' => $level_pokin_2,
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
								$ret['message'] = "Berhasil menyimpan data.";
							} else {
								$data['created_at'] = current_time('mysql');
								$wpdb->insert('esakip_data_pokin_rhk_opd', $data);
								$ret['message'] = "Berhasil menyimpan data.";
							}
						}
					}
					if (!empty($get_id_pokin_3)) {
						foreach ($get_id_pokin_3 as $id_pokin_lvl_3) {
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
		                        ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $_POST['level'], $cek_id, $id_pokin_lvl_3)
							);
							$level_pokin_3 = 3;
							$data = array(
								'id_rhk_opd' => $cek_id,
								'id_pokin' => $id_pokin_lvl_3,
								'level_pokin' => $level_pokin_3,
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
								$ret['message'] = "Berhasil menyimpan data.";
							} else {
								$data['created_at'] = current_time('mysql');
								$wpdb->insert('esakip_data_pokin_rhk_opd', $data);
								$ret['message'] = "Berhasil menyimpan data.";
							}
						}
					}
					if (!empty($get_id_pokin_4)) {
						foreach ($get_id_pokin_4 as $id_pokin_lvl_4) {
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
		                        ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $_POST['level'], $cek_id, $id_pokin_lvl_4)
							);

							$level_pokin_4 = 4;
							$data = array(
								'id_rhk_opd' => $cek_id,
								'id_pokin' => $id_pokin_lvl_4,
								'level_pokin' => $level_pokin_4,
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
								$ret['message'] = "Berhasil menyimpan data.";
							} else {
								$data['created_at'] = current_time('mysql');
								$wpdb->insert('esakip_data_pokin_rhk_opd', $data);
								$ret['message'] = "Berhasil menyimpan data.";
							}
						}
					}
					if (!empty($get_id_pokin_5)) {
						foreach ($get_id_pokin_5 as $id_pokin_lvl_5) {
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
		                        ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $_POST['level'], $cek_id, $id_pokin_lvl_5)
							);

							$level_pokin_5 = 5;
							$data = array(
								'id_rhk_opd' => $cek_id,
								'id_pokin' => $id_pokin_lvl_5,
								'level_pokin' => $level_pokin_5,
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
								$ret['message'] = "Berhasil menyimpan data.";
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
						$data_renaksi = $wpdb->get_results($wpdb->prepare("
						    SELECT 
						    	*
						    FROM esakip_detail_rencana_aksi_pemda
						    WHERE active = 1
						        AND id_skpd = %d
						        AND tahun_anggaran = %d
						", $ret['data']['id_skpd'], $ret['data']['tahun_anggaran']), ARRAY_A);

						$ret['data']['renaksi_pemda'] = array();
						if (!empty($data_renaksi)) {
							$id_pk = array();
							foreach ($data_renaksi as $item) {
								$id_pk[] = $item['id_pk'];
							}

							$get_id_pk = implode(',', $id_pk);

							if (!empty($get_id_pk)) {
								$ret['data']['renaksi_pemda'] = $wpdb->get_results("
								    SELECT 
								        pk.*,
								        ik.label_sasaran as ik_label_sasaran,
								        ik.label_indikator as ik_label_indikator,
								        l.id AS id_label,
								        d.id AS id_renaksi
								    FROM esakip_laporan_pk_pemda pk
								    LEFT JOIN esakip_data_iku_pemda ik
								        ON pk.id_iku = ik.id 
								        AND pk.id_jadwal = ik.id_jadwal
								    LEFT JOIN esakip_detail_rencana_aksi_pemda d
								        ON d.id_pk = pk.id 
								        AND d.active = 1 
								        AND d.id_skpd = {$ret['data']['id_skpd']} 
								        AND d.tahun_anggaran = {$ret['data']['tahun_anggaran']}
								    LEFT JOIN esakip_data_label_rencana_aksi l
								        ON l.parent_renaksi_pemda = d.id  -- ini penting!
								        AND l.active = 1 
								        AND l.parent_renaksi_opd = {$ret['data']['id']}
								    WHERE pk.active = 1
								        AND pk.id IN ($get_id_pk)
								", ARRAY_A);
							}
						}
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

						// kondisi jika parent RHK ada yang input pagu, maka yang ditampilkan adalah pokin milik parent
						$id_renaksi = $ret['data']['id'];
						$id_level = $ret['data']['level'];
						if (!empty($_POST['parent_id_input_pagu'])) {
							$id_renaksi = $_POST['parent_id_input_pagu'];
							$id_level = $_POST['parent_level_input_pagu'];
						}
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
						    ", $id_renaksi, $id_level),
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
						    ", $id_renaksi, $id_level),
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
						    ", $id_renaksi, $id_level),
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
						    ", $id_renaksi, $id_level),
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
						    ", $id_renaksi, $id_level),
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
						$data_rhk_parent = $wpdb->get_results($wpdb->prepare("
		                    SELECT
		                        *
		                    FROM esakip_data_rencana_aksi_opd
		                    WHERE id=%d
		                        AND active=1
		                ", $ret['data']['parent']), ARRAY_A);
		                if (!empty($data_rhk_parent)) {
		                    $ret['data']['data_rhk_parent'] = $data_rhk_parent;
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
						$ret['data']['data_rhk_parent'] = array();
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
                    
                    // PERBAIKAN: Pastikan id_indikator_cascading dan id_satuan_cascading ter-return
                    if ($ret['data']) {
                        $ret['data']['id_indikator_cascading'] = $ret['data']['id_indikator_cascading'] ?? null;
                        $ret['data']['id_satuan_cascading'] = $ret['data']['id_satuan_cascading'] ?? null;
                    }
                    
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

                    $cek_input_pagu = false;
                    if (!empty($ret['data'])) {
                        $ret['data']['data_rhk_khusus'] = $wpdb->get_row(
                            $wpdb->prepare('
                                SELECT
                                    id,
                                    input_rencana_pagu_level
                                FROM esakip_data_rencana_aksi_opd
                                WHERE id=%d
                                  AND tahun_anggaran=%d
                            ', $ret['data']['id_renaksi'], $_POST['tahun_anggaran']),
                            ARRAY_A
                        );
                        if ($ret['data']['data_rhk_khusus']['input_rencana_pagu_level']) {
                            $cek_input_pagu = $wpdb->get_var($wpdb->prepare("
                                SELECT
                                    sum(s.rencana_pagu)
                                FROM esakip_data_rencana_aksi_indikator_opd i
                                INNER JOIN esakip_sumber_dana_indikator s ON i.id=s.id_indikator
                                    AND s.active=i.active
                                    AND s.tahun_anggaran=i.tahun_anggaran
                                WHERE i.id_renaksi=%d
                                    AND i.active=1
                                    AND i.tahun_anggaran=%d
                            ", $ret['data']['id_renaksi'], $_POST['tahun_anggaran']));
                        }
                    }

                    // get total pagu rhk
                    if (!empty($cek_input_pagu)) {
                        $ret['data']['total_pagu'] = $cek_input_pagu;
                        $ret['data']['cek_pagu'] = array('message' => 'RHK input pagu');
                    } else {
                        $_POST['id'] = $ret['data']['id_renaksi'];
                        $cek_pagu_child = $this->cek_validasi_input_rencana_pagu(1);
                        $ret['data']['total_pagu'] = $cek_pagu_child['rencana_pagu'];
                        $ret['data']['cek_pagu'] = $cek_pagu_child;
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
					$wpdb->update('esakip_sumber_dana_indikator', array(
						'active' => 0
					), array('id_indikator' => $_POST['id']));
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
				} else if ($ret['status'] != 'error' && empty($_POST['rumus_capaian_kinerja'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'rumus capaian kinerja tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$_POST['id'] = $_POST['id_label'];

					$cek_input_pagu = $wpdb->get_var($wpdb->prepare("
				        SELECT 
				        	input_rencana_pagu_level
				        FROM esakip_data_rencana_aksi_opd
				        WHERE id = %d 
				        	AND tahun_anggaran = %d 
				        	AND id_skpd = %d 
				        	AND active = 1
				    ", $_POST['id_label'], $_POST['tahun_anggaran'], $_POST['id_skpd']));

					// kosongkan pagu child rhk jika rhk existing input pagu
					if ($cek_input_pagu == 1) {
						$cek_pagu_child = $this->cek_validasi_input_rencana_pagu(1);
						if (
							$cek_pagu_child['status'] == 'error'
							&& !empty($cek_pagu_child['rencana_pagu'])
						) {
							// update pagu indikator
							$wpdb->query('
								UPDATE esakip_data_rencana_aksi_indikator_opd 
								set rencana_pagu=0 
								WHERE id IN (' . implode(',', $cek_pagu_child['ids_indikator']) . ')
							');

							// UPDATE pagu di tabel sumber dana
							$wpdb->query('
								UPDATE esakip_sumber_dana_indikator 
								set rencana_pagu=0 
								WHERE id IN (' . implode(',', $cek_pagu_child['ids']) . ')
							');
						}

						// jika input pagu rencana aksi tidak dicheklist maka cek validasi pagu child dengan total indikator baru
					} else {
						if (empty($_POST['rencana_pagu_tk'])) {
							$_POST['rencana_pagu_tk'] = 0;
						}
						$ret['total_pagu'] = $_POST['rencana_pagu_tk'];
						if (!empty($_POST['id_label_indikator'])) {
							$total_pagu_renaksi = $wpdb->get_var($wpdb->prepare("
						        SELECT 
						        	SUM(rencana_pagu)
						        FROM esakip_data_rencana_aksi_indikator_opd
						        WHERE id_renaksi = %d 
						        	AND tahun_anggaran = %d 
						        	AND id_skpd = %d 
						        	AND active = 1
						        	AND id!=%d
						    ", $_POST['id_label'], $_POST['tahun_anggaran'], $_POST['id_skpd'], $_POST['id_label_indikator']));
						} else {
							$total_pagu_renaksi = $wpdb->get_var($wpdb->prepare("
						        SELECT 
						        	SUM(rencana_pagu)
						        FROM esakip_data_rencana_aksi_indikator_opd
						        WHERE id_renaksi = %d 
						        	AND tahun_anggaran = %d 
						        	AND id_skpd = %d 
						        	AND active = 1
						    ", $_POST['id_label'], $_POST['tahun_anggaran'], $_POST['id_skpd']));
						}
						$total_pagu_renaksi += $_POST['rencana_pagu'];
						$ret['sisa_pagu'] = $ret['total_pagu'] - $total_pagu_renaksi;
						if ($total_pagu_renaksi > $ret['total_pagu']) {
							$ret['status'] = 'error';
							$ret['message'] = 'Total rencana pagu tidak boleh melebihi 100% . Sisa rencana pagu setelah diinput adalah  ' . $ret['sisa_pagu'] . '';
							die(json_encode($ret));
						}
					}

					$data = array(
						'id_renaksi' => $_POST['id_label'],
						'id_indikator_cascading' => $_POST['id_indikator_cascading'],
						'id_satuan_cascading' => $_POST['id_satuan_cascading'],
						'indikator' => $_POST['indikator'],
						'satuan' => $_POST['satuan'],
						'rencana_pagu' => $_POST['rencana_pagu'],
						'rencana_pagu_rhk' => $_POST['rencana_pagu_tk'],
						'realisasi_pagu' => $_POST['realisasi_pagu'],
						'target_awal' => $_POST['target_awal'],
						'target_akhir' => $_POST['target_akhir'],
						'target_1' => $_POST['target_tw_1'],
						'target_2' => $_POST['target_tw_2'],
						'target_3' => $_POST['target_tw_3'],
						'target_4' => $_POST['target_tw_4'],
						'id_skpd' => $_POST['id_skpd'],
						'rumus_capaian_kinerja' => $_POST['rumus_capaian_kinerja'],
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

					// get id indikator existing
					if (empty($_POST['id_label_indikator'])) {
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_indikator_opd
							WHERE indikator=%s
								AND active=0
								AND tahun_anggaran=%d
								AND id_skpd=%d
						", $_POST['indikator'], $_POST['tahun_anggaran'], $_POST['id_skpd']));
					} else {
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_indikator_opd
							WHERE id=%d
						", $_POST['id_label_indikator']));
						if (empty($cek_id)) {
							$ret['status'] = 'error';
							$ret['message'] = 'Id Indikator ' . $_POST['id_label_indikator'] . ' tidak ditemukan!';
							die(json_encode($ret));
						}
						$ret['message'] = "Berhasil edit indikator!";
					}

					$total_rencana_pagu = 0;
					if (empty($cek_id)) {
						// Insert data indikator baru
						$wpdb->insert('esakip_data_rencana_aksi_indikator_opd', $data);
						$id_indikator_baru = $wpdb->insert_id;

						// Input sumber dana dan rencana pagu (jika subkeg)
						if (!empty($_POST['sumber_danas'])) {
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
								array('rencana_pagu' => $total_rencana_pagu),
								array('id' => $id_indikator_baru)
							);
						}
					} else {
						$wpdb->update(
							'esakip_data_rencana_aksi_indikator_opd',
							$data,
							array('id' => $cek_id)
						);

						$wpdb->update(
							'esakip_sumber_dana_indikator',
							array('active' => 0),
							array('id_indikator' => $cek_id)
						);

						// Input sumber dana dan rencana pagu yang baru
						if (!empty($_POST['sumber_danas'])) {
							$sumber_dana_existing = $wpdb->get_results($wpdb->prepare("
								SELECT
									*
								FROM esakip_sumber_dana_indikator
								WHERE id_indikator=%d
							", $cek_id), ARRAY_A);
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

								if (!empty($sumber_dana_existing[$k])) {
									$wpdb->update('esakip_sumber_dana_indikator', $data_sumber_dana, array(
										'id' => $sumber_dana_existing[$k]['id']
									));
								} else {
									$wpdb->insert('esakip_sumber_dana_indikator', $data_sumber_dana);
								}
							}
							$wpdb->update(
								'esakip_data_rencana_aksi_indikator_opd',
								array('rencana_pagu' => $total_rencana_pagu),
								array('id' => $cek_id)
							);
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

	function update_pagu_existing($opsi)
	{
		global $wpdb;
		if (
			$opsi['input_rencana_pagu_level'] != 1
			&& $opsi['rencana_pagu_rhk'] != $opsi['total_rhk_existing']
		) {
			if (!empty($opsi['rencana_pagu_rhk'])) {
				$persen = ($opsi['rencana_pagu'] / $opsi['rencana_pagu_rhk']) * 100;
				$opsi['rencana_pagu'] = ($persen / 100) * $opsi['total_rhk_existing'];
			} else if ($opsi['total_rhk_existing'] == 0) {
				$opsi['rencana_pagu'] = 0;
			}

			$wpdb->update('esakip_data_rencana_aksi_indikator_opd', array(
				'rencana_pagu' => $opsi['rencana_pagu'],
				'rencana_pagu_rhk' => $opsi['total_rhk_existing']
			), array('id' => $opsi['id']));

			// print_r($opsi); die($wpdb->last_query);
		}
		return number_format((float)$opsi['rencana_pagu'], 0, ",", ".");
	}

	function format_rupiah_tanpa_rp($angka)
	{
		$hasil_format = number_format((float)$angka, 0, ",", ".");

		return $hasil_format;
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
                        'total_sd' => 0,
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
                            'total' => 0,
                            'total_sd' => 0,
                            'data' => array(),
                            'indikator' => $indikator
                        );
                        if ($v['input_rencana_pagu_level']) {
                            if (!empty($indikator)) {
                                foreach ($indikator as $k => $ind) {
                                    $pagu_sd = $wpdb->get_var($wpdb->prepare("
                                        SELECT
                                            sum(rencana_pagu)
                                        FROM esakip_sumber_dana_indikator 
                                        WHERE id_indikator=%d
                                            AND active=1
                                            AND tahun_anggaran=%d
                                    ", $ind['id'], $ind['tahun_anggaran']));

                                    // reset rencana pagu sama dengan jumlah pagu sumber dana
                                    if ($ind['rencana_pagu'] != $pagu_sd) {
                                        $wpdb->update(
                                            'esakip_data_rencana_aksi_indikator_opd',
                                            array('rencana_pagu' => $pagu_sd),
                                            array('id' => $ind['id'])
                                        );
                                        $ind['rencana_pagu'] = $pagu_sd;
                                        $data_all['data'][$v['id']]['indikator'][$k] = $ind;
                                    }

                                    $data_all['total'] += $ind['rencana_pagu'];
                                    $data_all['data'][$v['id']]['total'] += $ind['rencana_pagu'];

                                    $data_all['total_sd'] += $pagu_sd;
                                    $data_all['data'][$v['id']]['total_sd'] += $pagu_sd;
                                }
                            }
                        }
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
                                'total' => 0,
                                'total_sd' => 0,
                                'data' => array(),
                                'indikator' => $indikator
                            );
                            if (
                                empty($v1['input_rencana_pagu_level'])
                                && $v2['input_rencana_pagu_level']
                            ) {
                                if (!empty($indikator)) {
                                    foreach ($indikator as $k => $ind2) {
                                        $pagu_sd = $wpdb->get_var($wpdb->prepare("
                                            SELECT
                                                sum(rencana_pagu)
                                            FROM esakip_sumber_dana_indikator 
                                            WHERE id_indikator=%d
                                                AND active=1
                                                AND tahun_anggaran=%d
                                        ", $ind2['id'], $ind2['tahun_anggaran']));

                                        // reset rencana pagu sama dengan jumlah pagu sumber dana
                                        if ($ind2['rencana_pagu'] != $pagu_sd) {
                                            $wpdb->update(
                                                'esakip_data_rencana_aksi_indikator_opd',
                                                array('rencana_pagu' => $pagu_sd),
                                                array('id' => $ind2['id'])
                                            );
                                            $ind2['rencana_pagu'] = $pagu_sd;
                                            $data_all['data'][$v['id']]['data'][$v2['id']]['indikator'][$k] = $ind2;
                                        }

                                        $data_all['total'] += $ind2['rencana_pagu'];
                                        $data_all['data'][$v['id']]['total'] += $ind2['rencana_pagu'];
                                        $data_all['data'][$v['id']]['data'][$v2['id']]['total'] += $ind2['rencana_pagu'];

                                        $data_all['total_sd'] += $pagu_sd;
                                        $data_all['data'][$v['id']]['total_sd'] += $pagu_sd;
                                        $data_all['data'][$v['id']]['data'][$v2['id']]['total_sd'] += $pagu_sd;
                                    }
                                }
                            }
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
                                    'total' => 0,
                                    'total_sd' => 0,
                                    'data' => array(),
                                    'indikator' => $indikator
                                );
                                if (
                                    empty($v1['input_rencana_pagu_level'])
                                    && empty($v2['input_rencana_pagu_level'])
                                    && $v3['input_rencana_pagu_level']
                                ) {
                                    if (!empty($indikator)) {
                                        foreach ($indikator as $k => $ind3) {
                                            $pagu_sd = $wpdb->get_var($wpdb->prepare("
                                                SELECT
                                                    sum(rencana_pagu)
                                                FROM esakip_sumber_dana_indikator 
                                                WHERE id_indikator=%d
                                                    AND active=1
                                                    AND tahun_anggaran=%d
                                            ", $ind3['id'], $ind3['tahun_anggaran']));

                                            // reset rencana pagu sama dengan jumlah pagu sumber dana
                                            if ($ind3['rencana_pagu'] != $pagu_sd) {
                                                $wpdb->update(
                                                    'esakip_data_rencana_aksi_indikator_opd',
                                                    array('rencana_pagu' => $pagu_sd),
                                                    array('id' => $ind3['id'])
                                                );
                                                $ind3['rencana_pagu'] = $pagu_sd;
                                                $data_all['data'][$v['id']]['data'][$v2['id']]['data'][$v3['id']]['indikator'][$k] = $ind3;
                                            }

                                            $data_all['total'] += $ind3['rencana_pagu'];
                                            $data_all['data'][$v['id']]['total'] += $ind3['rencana_pagu'];
                                            $data_all['data'][$v['id']]['data'][$v2['id']]['total'] += $ind3['rencana_pagu'];
                                            $data_all['data'][$v['id']]['data'][$v2['id']]['data'][$v3['id']]['total'] += $ind3['rencana_pagu'];

                                            $data_all['total_sd'] += $pagu_sd;
                                            $data_all['data'][$v['id']]['total_sd'] += $pagu_sd;
                                            $data_all['data'][$v['id']]['data'][$v2['id']]['total_sd'] += $pagu_sd;
                                            $data_all['data'][$v['id']]['data'][$v2['id']]['data'][$v3['id']]['total_sd'] += $pagu_sd;
                                        }
                                    }
                                }
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
                                        'total' => 0,
                                        'total_sd' => 0,
                                        'indikator' => $indikator
                                    );

                                    if (!empty($indikator)) {
                                        foreach ($indikator as $k => $ind4) {
                                            $pagu_sd = $wpdb->get_var($wpdb->prepare("
                                                SELECT
                                                    sum(rencana_pagu)
                                                FROM esakip_sumber_dana_indikator 
                                                WHERE id_indikator=%d
                                                    AND active=1
                                                    AND tahun_anggaran=%d
                                            ", $ind4['id'], $ind4['tahun_anggaran']));

                                            // reset rencana pagu sama dengan jumlah pagu sumber dana
                                            if ($ind4['rencana_pagu'] != $pagu_sd) {
                                                $wpdb->update(
                                                    'esakip_data_rencana_aksi_indikator_opd',
                                                    array('rencana_pagu' => $pagu_sd),
                                                    array('id' => $ind4['id'])
                                                );
                                                $ind4['rencana_pagu'] = $pagu_sd;
                                                $data_all['data'][$v['id']]['data'][$v2['id']]['data'][$v3['id']]['indikator'][$k] = $ind4;
                                            }

                                            if (
                                                empty($v1['input_rencana_pagu_level'])
                                                && empty($v2['input_rencana_pagu_level'])
                                                && empty($v3['input_rencana_pagu_level'])
                                            ) {
                                                $data_all['total'] += $ind4['rencana_pagu'];
                                                $data_all['data'][$v['id']]['total'] += $ind4['rencana_pagu'];
                                                $data_all['data'][$v['id']]['data'][$v2['id']]['total'] += $ind4['rencana_pagu'];
                                                $data_all['data'][$v['id']]['data'][$v2['id']]['data'][$v3['id']]['total'] += $ind4['rencana_pagu'];

                                                $data_all['total_sd'] += $pagu_sd;
                                                $data_all['data'][$v['id']]['total_sd'] += $pagu_sd;
                                                $data_all['data'][$v['id']]['data'][$v2['id']]['total_sd'] += $pagu_sd;
                                                $data_all['data'][$v['id']]['data'][$v2['id']]['data'][$v3['id']]['total_sd'] += $pagu_sd;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $ret['data_all'] = $data_all;
                    // die(json_encode($ret));

                    $data_all_wpsipd = array();
                    $rincian_tagging = $this->functions->generatePage(array(
                        'nama_page' => 'Halaman Tagging Rincian Belanja',
                        'content' => '[tagging_rincian_sakip]',
                        'show_header' => 1,
                        'post_status' => 'private'
                    ));
                    $set_pagu_renaksi = get_option('_crb_set_pagu_renaksi');
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
                        $total_realisasi_tagging_rincian_html = array();
                        $total_harga_tagging_rincian_html = array();

                        foreach ($v['indikator'] as $key => $ind) {
                            $total_harga_tagging_rincian = 0;
                            $total_realisasi_tagging_rincian = 0;
                            $indikator_html[$key] = '<a href="' . $this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $_POST['tahun_anggaran'] . '&id_skpd=' . $_POST['id_skpd'] . '&id_indikator=' . $ind['id']) . '" target="_blank">' . $ind['indikator'] . '</a>';
                            $satuan_html[$key] = $ind['satuan'];
                            $target_awal_html[$key] = $ind['target_awal'];
                            $target_akhir_html[$key] = $ind['target_akhir'];
                            $target_1_html[$key] = $ind['target_1'];
                            $target_2_html[$key] = $ind['target_2'];
                            $target_3_html[$key] = $ind['target_3'];
                            $target_4_html[$key] = $ind['target_4'];
                            $rencana_pagu_html[$key] = 0;
                            if (
                                $set_pagu_renaksi != 1
                                && !empty($ind['rencana_pagu'])
                            ) {
                                $ind['total_rhk_existing'] = $v['total_sd'];
                                $ind['input_rencana_pagu_level'] = $v['detail']['input_rencana_pagu_level'];
                                $rencana_pagu_html[$key] = $this->update_pagu_existing($ind);
                            }
                            $realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
                            $realisasi_1_html[$key] = !empty($ind['realisasi_tw_1']) ? $ind['realisasi_tw_1'] : 0;
                            $realisasi_2_html[$key] = !empty($ind['realisasi_tw_2']) ? $ind['realisasi_tw_2'] : 0;
                            $realisasi_3_html[$key] = !empty($ind['realisasi_tw_3']) ? $ind['realisasi_tw_3'] : 0;
                            $realisasi_4_html[$key] = !empty($ind['realisasi_tw_4']) ? $ind['realisasi_tw_4'] : 0;

                            $target_tahunan = (float) str_replace(',', '.', $ind['target_akhir'] ?? 0);
                            $all_realisasi = [
                                'realisasi_1' => (float) str_replace(',', '.', $ind['realisasi_tw_1'] ?? 0),
                                'realisasi_2' => (float) str_replace(',', '.', $ind['realisasi_tw_2'] ?? 0),
                                'realisasi_3' => (float) str_replace(',', '.', $ind['realisasi_tw_3'] ?? 0),
                                'realisasi_4' => (float) str_replace(',', '.', $ind['realisasi_tw_4'] ?? 0)
                            ];

                            $capaian_realisasi[$key] = $this->get_capaian_realisasi_tahunan_by_type(
                                $ind['rumus_capaian_kinerja'],
                                $target_tahunan,
                                $all_realisasi,
                                $ind['tahun_anggaran']
                            );
                            $capaian_realisasi[$key] = ($capaian_realisasi[$key] === false) ? 'N/A' : $capaian_realisasi[$key] . '%';

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

                            if (!empty($data_tagging)) {
                                foreach ($data_tagging as $value) {
                                    $harga_satuan = $value['harga_satuan'];
                                    $volume = $value['volume'];
                                    $total = $volume * $harga_satuan;

                                    $total_harga_tagging_rincian += $total;
                                    $total_realisasi_tagging_rincian += $value['realisasi'];
                                }
                            }
                            $total_harga_tagging_rincian_html[$key] = $this->format_rupiah_tanpa_rp($total_harga_tagging_rincian) ?? 0;
                            $total_realisasi_tagging_rincian_html[$key] = $this->format_rupiah_tanpa_rp($total_realisasi_tagging_rincian) ?? 0;
                        }

                        $indikator_html = implode('<br>', $indikator_html);
                        $satuan_html = implode('<br>', $satuan_html);
                        $target_awal_html = implode('<br>', $target_awal_html);
                        $target_akhir_html = implode('<br>', $target_akhir_html);
                        $target_1_html = implode('<br>', $target_1_html);
                        $target_2_html = implode('<br>', $target_2_html);
                        $target_3_html = implode('<br>', $target_3_html);
                        $target_4_html = implode('<br>', $target_4_html);
                        $total_harga_tagging_rincian_html = implode('<br>', $total_harga_tagging_rincian_html);
                        $total_realisasi_tagging_rincian_html = implode('<br>', $total_realisasi_tagging_rincian_html);
                        $rencana_pagu_html = implode('<br>', $rencana_pagu_html);
                        $realisasi_pagu_html = implode('<br>', $realisasi_pagu_html);
                        $realisasi_1_html = implode('<br>', $realisasi_1_html);
                        $realisasi_2_html = implode('<br>', $realisasi_2_html);
                        $realisasi_3_html = implode('<br>', $realisasi_3_html);
                        $realisasi_4_html = implode('<br>', $realisasi_4_html);
                        $capaian_realisasi = implode('<br>', $capaian_realisasi);

                        $keterangan = '';
                        if (empty($v['detail']['satker_id'])) {
                            $keterangan .= '<li>Satuan Kerja Belum Dipilih</li>';
                        }
                        if (empty($v['detail']['nip'])) {
                            $keterangan .= '<li>Pegawai Pelaksana Belum Dipilih</li>';
                        } else {
                            // Cek NIP apakah existing sesuai satker
                            $satker_id_utama = substr($v['detail']['satker_id'], 0, 2);
                            if (!empty($v['detail']['id_jabatan_asli'])) {
                                $get_pegawai = $wpdb->get_row(
                                    $wpdb->prepare("
                                        SELECT 
                                            *
                                        FROM esakip_data_pegawai_simpeg
                                        WHERE nip_baru = %s
                                            AND satker_id LIKE %s
                                            AND id_jabatan = %s
                                        ORDER by active DESC
                                    ", $v['detail']['nip'], $satker_id_utama . '%', $v['detail']['id_jabatan_asli']),
                                    ARRAY_A
                                );
                            } else {
                                $get_pegawai = $wpdb->get_row(
                                    $wpdb->prepare("
                                        SELECT 
                                            *
                                        FROM esakip_data_pegawai_simpeg
                                        WHERE nip_baru = %s
                                            AND satker_id LIKE %s
                                        ORDER by active DESC
                                    ", $v['detail']['nip'], $satker_id_utama . '%'),
                                    ARRAY_A
                                );
                            }

                            if (empty($get_pegawai)) {
                                $keterangan .= '<li>Pegawai pelaksana dengan NIP = ' . $v['detail']['nip'] . ' dan satker_id = ' . $v['detail']['satker_id'] . ' tidak ditemukan</li>';
                            } else if ($get_pegawai['active'] == 0) {
                                $keterangan .= '<li>Pegawai atas nama ' . $get_pegawai['nama_pegawai'] . ', jabatan ' . $get_pegawai['jabatan'] . ', satker_id ' . $get_pegawai['satker_id'] . ' sudah tidak aktif!</li>';
                            } else {
                                if (empty($v['detail']['id_jabatan_asli'])) {
                                    $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                        'id_jabatan_asli' => $get_pegawai['id_jabatan']
                                    ), array(
                                        'id' => $v['detail']['id']
                                    ));
                                }
                            }
                        }

                        // rhk level 1 cascading sasaran
                        if ($v['detail']['input_rencana_pagu_level'] == 1) {
                            if (empty($v['detail']['kode_cascading_sub_kegiatan'])) {
                                $keterangan .= '<li>Cascading Sub Kegiatan Belum dipilih</li>';
                            } else {
                                if (empty($data_all_wpsipd[$v['detail']['kode_cascading_kegiatan']])) {
                                    $_POST['jenis'] = 'sub_kegiatan';
                                    $_POST['parent_cascading'] = $v['detail']['kode_cascading_kegiatan'];
                                    $data_all_wpsipd[$v['detail']['kode_cascading_kegiatan']] = $this->get_tujuan_sasaran_cascading(true);
                                }
                                foreach ($data_all_wpsipd[$v['detail']['kode_cascading_kegiatan']]['data'] as $val) {
                                    if (
                                        $v['detail']['kode_cascading_sub_kegiatan'] == $val->kode_sub_giat
                                        && $v['detail']['pagu_cascading'] != $val->pagu
                                    ) {
                                        $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                            'pagu_cascading' => $val->pagu
                                        ), array(
                                            'id' => $v['detail']['id']
                                        ));
                                    }
                                }
                            }
                        } else {
                            if (empty($v['detail']['kode_cascading_sasaran'])) {
                                $keterangan .= '<li>Cascading Sasaran Belum dipilih</li>';
                            } else {
                                if (empty($data_all_wpsipd[$v['detail']['kode_cascading_sasaran']])) {
                                    $_POST['jenis'] = 'program';
                                    $_POST['parent_cascading'] = $v['detail']['kode_cascading_sasaran'];
                                    $data_all_wpsipd[$v['detail']['kode_cascading_sasaran']] = $this->get_tujuan_sasaran_cascading(true);
                                }
                            }
                        }

                        $label_cascading = '';
                        if ($v['detail']['input_rencana_pagu_level'] == 1) {
                            if ($v['detail']['label_cascading_sasaran']) {
                                $label_cascading .= $v['detail']['kode_cascading_sasaran'] . ' ' . $v['detail']['label_cascading_sasaran'];
                            }
                            if ($v['detail']['label_cascading_program']) {
                                $label_cascading .= '<br>' . $v['detail']['kode_cascading_program'] . ' ' . $v['detail']['label_cascading_program'];
                            }
                            if ($v['detail']['label_cascading_kegiatan']) {
                                $label_cascading .= '<br>' . $v['detail']['kode_cascading_kegiatan'] . ' ' . $v['detail']['label_cascading_kegiatan'];
                            }
                            if ($v['detail']['label_cascading_sub_kegiatan']) {
                                $label_cascading .= '<br>' . $v['detail']['kode_cascading_sub_kegiatan'] . ' ' . $v['detail']['label_cascading_sub_kegiatan'];
                            } else {
                                $v['detail']['pagu_cascading'] = 0;
                            }
                        } else {
                            if ($v['detail']['label_cascading_sasaran']) {
                                $label_cascading = $v['detail']['kode_cascading_sasaran'] . ' ' . $v['detail']['label_cascading_sasaran'];
                            }
                        }
                        $html .= '
                        <tr class="keg-utama">
                            <td>' . $no . '</td>
                            <td class="ket_rhk">' . $keterangan . '</td>
                            <td class="text-left">' . $label_cascading . '</td>
                            <td class="kegiatan_utama">' . $v['detail']['label'] . '
                                <a href="javascript:void(0)" data-id="' . $v['detail']['id'] . '" data-tipe="1" 
                                   class="help-rhk" onclick="help_rhk(' . $v['detail']['id'] . ', 1); return false;" title="Detail">
                                   <i class="dashicons dashicons-editor-help"></i>
                                </a>
                            </td>
                            <td class="indikator_kegiatan_utama">' . $indikator_html . '</td>
                            <td class="recana_aksi"></td>
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
                            <td class="text-right anggaran_column">' . $total_harga_tagging_rincian_html . '</td>
                            <td class="text-right anggaran_column">' . $total_realisasi_tagging_rincian_html . '</td>
                            <td class="text-right anggaran_column"></td>
                            <td class="text-right"></td>
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
                            $total_realisasi_tagging_rincian_html = array();
                            $total_harga_tagging_rincian_html = array();
                            foreach ($renaksi['indikator'] as $key => $ind) {
                                $total_harga_tagging_rincian = 0;
                                $total_realisasi_tagging_rincian = 0;
                                $indikator_html[$key] = '<a href="' . $this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $_POST['tahun_anggaran'] . '&id_skpd=' . $_POST['id_skpd'] . '&id_indikator=' . $ind['id']) . '" target="_blank">' . $ind['indikator'] . '</a>';
                                $satuan_html[$key] = $ind['satuan'];
                                $target_awal_html[$key] = $ind['target_awal'];
                                $target_akhir_html[$key] = $ind['target_akhir'];
                                $target_1_html[$key] = $ind['target_1'];
                                $target_2_html[$key] = $ind['target_2'];
                                $target_3_html[$key] = $ind['target_3'];
                                $target_4_html[$key] = $ind['target_4'];
                                $rencana_pagu_html[$key] = 0;
                                if (
                                    $set_pagu_renaksi != 1
                                    && !empty($ind['rencana_pagu'])
                                ) {
                                    $ind['total_rhk_existing'] = $renaksi['total_sd'];
                                    $ind['input_rencana_pagu_level'] = $renaksi['detail']['input_rencana_pagu_level'];
                                    $rencana_pagu_html[$key] = $this->update_pagu_existing($ind);
                                }
                                $realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
                                $realisasi_1_html[$key] = !empty($ind['realisasi_tw_1']) ? $ind['realisasi_tw_1'] : 0;
                                $realisasi_2_html[$key] = !empty($ind['realisasi_tw_2']) ? $ind['realisasi_tw_2'] : 0;
                                $realisasi_3_html[$key] = !empty($ind['realisasi_tw_3']) ? $ind['realisasi_tw_3'] : 0;
                                $realisasi_4_html[$key] = !empty($ind['realisasi_tw_4']) ? $ind['realisasi_tw_4'] : 0;

                                $target_tahunan = (float) str_replace(',', '.', $ind['target_akhir'] ?? 0);
                                $all_realisasi = [
                                    'realisasi_1' => (float) str_replace(',', '.', $ind['realisasi_tw_1'] ?? 0),
                                    'realisasi_2' => (float) str_replace(',', '.', $ind['realisasi_tw_2'] ?? 0),
                                    'realisasi_3' => (float) str_replace(',', '.', $ind['realisasi_tw_3'] ?? 0),
                                    'realisasi_4' => (float) str_replace(',', '.', $ind['realisasi_tw_4'] ?? 0)
                                ];

                                $capaian_realisasi[$key] = $this->get_capaian_realisasi_tahunan_by_type(
                                    $ind['rumus_capaian_kinerja'],
                                    $target_tahunan,
                                    $all_realisasi,
                                    $ind['tahun_anggaran']
                                );
                                $capaian_realisasi[$key] = ($capaian_realisasi[$key] === false) ? 'N/A' : $capaian_realisasi[$key] . '%';

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

                                if (!empty($data_tagging)) {
                                    foreach ($data_tagging as $value) {
                                        $harga_satuan = $value['harga_satuan'];
                                        $volume = $value['volume'];
                                        $total = $volume * $harga_satuan;

                                        $total_harga_tagging_rincian += $total;
                                        $total_realisasi_tagging_rincian += $value['realisasi'];
                                    }
                                }
                                $total_harga_tagging_rincian_html[$key] = $this->format_rupiah_tanpa_rp($total_harga_tagging_rincian) ?? 0;
                                $total_realisasi_tagging_rincian_html[$key] = $this->format_rupiah_tanpa_rp($total_realisasi_tagging_rincian) ?? 0;
                            }
                            $indikator_html = implode('<br>', $indikator_html);
                            $satuan_html = implode('<br>', $satuan_html);
                            $target_awal_html = implode('<br>', $target_awal_html);
                            $target_akhir_html = implode('<br>', $target_akhir_html);
                            $target_1_html = implode('<br>', $target_1_html);
                            $target_2_html = implode('<br>', $target_2_html);
                            $target_3_html = implode('<br>', $target_3_html);
                            $target_4_html = implode('<br>', $target_4_html);
                            $total_harga_tagging_rincian_html = implode('<br>', $total_harga_tagging_rincian_html);
                            $total_realisasi_tagging_rincian_html = implode('<br>', $total_realisasi_tagging_rincian_html);
                            $rencana_pagu_html = implode('<br>', $rencana_pagu_html);
                            $realisasi_pagu_html = implode('<br>', $realisasi_pagu_html);
                            $keterangan = '';
                            $realisasi_1_html = implode('<br>', $realisasi_1_html);
                            $realisasi_2_html = implode('<br>', $realisasi_2_html);
                            $realisasi_3_html = implode('<br>', $realisasi_3_html);
                            $realisasi_4_html = implode('<br>', $realisasi_4_html);
                            $capaian_realisasi = implode('<br>', $capaian_realisasi);

                            if (empty($renaksi['detail']['satker_id'])) {
                                $keterangan .= '<li>Satuan Kerja Belum Dipilih</li>';
                            }
                            if (empty($renaksi['detail']['nip'])) {
                                $keterangan .= '<li>Pegawai Pelaksana Belum Dipilih</li>';
                            } else {
                                // Cek NIP apakah existing sesuai satker
                                $satker_id_utama = substr($renaksi['detail']['satker_id'], 0, 2);
                                if (!empty($renaksi['detail']['id_jabatan_asli'])) {
                                    $get_pegawai = $wpdb->get_row(
                                        $wpdb->prepare("
                                            SELECT 
                                                *
                                            FROM esakip_data_pegawai_simpeg
                                            WHERE nip_baru = %s
                                                AND satker_id LIKE %s
                                                AND id_jabatan = %s
                                            ORDER by active DESC
                                        ", $renaksi['detail']['nip'], $satker_id_utama . '%', $renaksi['detail']['id_jabatan_asli']),
                                        ARRAY_A
                                    );
                                } else {
                                    $get_pegawai = $wpdb->get_row(
                                        $wpdb->prepare("
                                            SELECT 
                                                *
                                            FROM esakip_data_pegawai_simpeg
                                            WHERE nip_baru = %s
                                                AND satker_id LIKE %s
                                            ORDER by active DESC
                                        ", $renaksi['detail']['nip'], $satker_id_utama . '%'),
                                        ARRAY_A
                                    );
                                }

                                if (empty($get_pegawai)) {
                                    $keterangan .= '<li>Pegawai pelaksana dengan NIP = ' . $renaksi['detail']['nip'] . ' dan satker_id = ' . $renaksi['detail']['satker_id'] . ' tidak ditemukan</li>';
                                } else if ($get_pegawai['active'] == 0) {
                                    $keterangan .= '<li>Pegawai atas nama ' . $get_pegawai['nama_pegawai'] . ', jabatan ' . $get_pegawai['jabatan'] . ', satker_id ' . $get_pegawai['satker_id'] . ' sudah tidak aktif!</li>';
                                } else {
                                    if (empty($renaksi['detail']['id_jabatan_asli'])) {
                                        $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                            'id_jabatan_asli' => $get_pegawai['id_jabatan']
                                        ), array(
                                            'id' => $renaksi['detail']['id']
                                        ));
                                    }
                                }
                            }

                            // rhk level 2 cascading program
                            if ($v['detail']['input_rencana_pagu_level'] == 1) {
                                // jika parent sudah input sub keg, maka hapus cascading di rhk child nya
                                if (
                                    !empty($renaksi['detail']['kode_cascading_sasaran'])
                                    || !empty($renaksi['detail']['kode_cascading_program'])
                                    || !empty($renaksi['detail']['kode_cascading_kegiatan'])
                                    || !empty($renaksi['detail']['kode_cascading_sub_kegiatan'])
                                    || !empty($renaksi['detail']['pagu_cascading'])
                                ) {
                                    $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                        'kode_cascading_sasaran' => '',
                                        'kode_cascading_program' => '',
                                        'kode_cascading_kegiatan' => '',
                                        'kode_cascading_sub_kegiatan' => '',
                                        'label_cascading_sasaran' => '',
                                        'label_cascading_program' => '',
                                        'label_cascading_kegiatan' => '',
                                        'label_cascading_sub_kegiatan' => '',
                                        'pagu_cascading' => '0'
                                    ), array(
                                        'id' => $renaksi['detail']['id']
                                    ));
                                    $renaksi['detail']['label_cascading_program'] = '';
                                }
                            } else {
                                if ($renaksi['detail']['input_rencana_pagu_level'] == 1) {
                                    if (empty($renaksi['detail']['kode_cascading_sub_kegiatan'])) {
                                        $keterangan .= '<li>Cascading Sub Kegiatan Belum dipilih</li>';
                                    } else {
                                        if (empty($data_all_wpsipd[$renaksi['detail']['kode_cascading_kegiatan']])) {
                                            $_POST['jenis'] = 'sub_kegiatan';
                                            $_POST['parent_cascading'] = $renaksi['detail']['kode_cascading_kegiatan'];
                                            $data_all_wpsipd[$renaksi['detail']['kode_cascading_kegiatan']] = $this->get_tujuan_sasaran_cascading(true);
                                        }
                                        // update pagu sub kegiatan jika tidak sama dengan wp-sipd
                                        foreach ($data_all_wpsipd[$renaksi['detail']['kode_cascading_kegiatan']]['data'] as $val) {
                                            if (
                                                $renaksi['detail']['kode_cascading_sub_kegiatan'] == $val->kode_sub_giat
                                                && $renaksi['detail']['pagu_cascading'] != $val->pagu
                                            ) {
                                                $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                                    'pagu_cascading' => $val->pagu
                                                ), array(
                                                    'id' => $renaksi['detail']['id']
                                                ));
                                            }
                                        }
                                    }
                                } else {
                                    if (empty($renaksi['detail']['kode_cascading_program'])) {
                                        $keterangan .= '<li>Cascading Program Belum dipilih</li>';
                                    } else {
                                        // update pagu program jika tidak sama dengan wp-sipd
                                        if (!empty($data_all_wpsipd[$v['detail']['kode_cascading_sasaran']])) {
                                            foreach ($data_all_wpsipd[$v['detail']['kode_cascading_sasaran']]['data'] as $val) {
                                                if (
                                                    $renaksi['detail']['kode_cascading_program'] == $val->kode_program
                                                    && $renaksi['detail']['pagu_cascading'] != $val->pagu
                                                ) {
                                                    $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                                        'pagu_cascading' => $val->pagu
                                                    ), array(
                                                        'id' => $renaksi['detail']['id']
                                                    ));
                                                }
                                            }
                                        }

                                        if (empty($data_all_wpsipd[$renaksi['detail']['kode_cascading_program']])) {
                                            $_POST['jenis'] = 'kegiatan';
                                            $_POST['parent_cascading'] = $renaksi['detail']['kode_cascading_program'];
                                            $data_all_wpsipd[$renaksi['detail']['kode_cascading_program']] = $this->get_tujuan_sasaran_cascading(true);
                                        }
                                    }
                                }
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
                            if ($renaksi['detail']['input_rencana_pagu_level'] == 1) {
                                if ($renaksi['detail']['label_cascading_program']) {
                                    $label_cascading .= $renaksi['detail']['kode_cascading_program'] . ' ' . $renaksi['detail']['label_cascading_program'];
                                }
                                if ($renaksi['detail']['label_cascading_kegiatan']) {
                                    $label_cascading .= '<br>' . $renaksi['detail']['kode_cascading_kegiatan'] . ' ' . $renaksi['detail']['label_cascading_kegiatan'];
                                }
                                if ($renaksi['detail']['label_cascading_sub_kegiatan']) {
                                    $label_cascading .= '<br>' . $renaksi['detail']['kode_cascading_sub_kegiatan'] . ' ' . $renaksi['detail']['label_cascading_sub_kegiatan'];
                                } else {
                                    $renaksi['detail']['pagu_cascading'] = 0;
                                }
                            } else {
                                if ($renaksi['detail']['label_cascading_program']) {
                                    $label_cascading = $renaksi['detail']['kode_cascading_program'] . ' ' . $renaksi['detail']['label_cascading_program'];
                                }
                            }

                            $html .= '
                                <tr class="re-naksi">
                                    <td>' . $no . '.' . $no_renaksi . '</td>
                                    <td class="ket">' . $keterangan . '</td>
                                    <td class="text-left">' . $label_cascading . '</td>
                                    <td class="kegiatan_utama"></td>
                                    <td class="indikator_kegiatan_utama"></td>
                                    <td class="recana_aksi">' . $renaksi_html . '' . $renaksi['detail']['label'] . '
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
                                    <td class="text-right anggaran_column">' . $total_harga_tagging_rincian_html . '</td>
                                    <td class="text-right anggaran_column">' . $total_realisasi_tagging_rincian_html . '</td>
                                    <td class="text-right anggaran_column"></td>
                                    <td class="text-right">' . number_format((float)$renaksi['detail']['pagu_cascading'], 0, ",", ".") . '</td>
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
                                $total_realisasi_tagging_rincian_html = array();
                                $total_harga_tagging_rincian_html = array();
                                foreach ($uraian_renaksi['indikator'] as $key => $ind) {
                                    $total_harga_tagging_rincian = 0;
                                    $total_realisasi_tagging_rincian = 0;
                                    $indikator_html[$key] = '<a href="' . $this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $_POST['tahun_anggaran'] . '&id_skpd=' . $_POST['id_skpd'] . '&id_indikator=' . $ind['id']) . '" target="_blank">' . $ind['indikator'] . '</a>';
                                    $satuan_html[$key] = $ind['satuan'];
                                    $target_awal_html[$key] = $ind['target_awal'];
                                    $target_akhir_html[$key] = $ind['target_akhir'];
                                    $target_1_html[$key] = $ind['target_1'];
                                    $target_2_html[$key] = $ind['target_2'];
                                    $target_3_html[$key] = $ind['target_3'];
                                    $target_4_html[$key] = $ind['target_4'];
                                    $rencana_pagu_html[$key] = 0;
                                    if (
                                        $set_pagu_renaksi != 1
                                        && !empty($ind['rencana_pagu'])
                                    ) {
                                        $ind['total_rhk_existing'] = $uraian_renaksi['total_sd'];
                                        $ind['input_rencana_pagu_level'] = $uraian_renaksi['detail']['input_rencana_pagu_level'];
                                        $rencana_pagu_html[$key] = $this->update_pagu_existing($ind);
                                    }
                                    $realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
                                    $realisasi_1_html[$key] = !empty($ind['realisasi_tw_1']) ? $ind['realisasi_tw_1'] : 0;
                                    $realisasi_2_html[$key] = !empty($ind['realisasi_tw_2']) ? $ind['realisasi_tw_2'] : 0;
                                    $realisasi_3_html[$key] = !empty($ind['realisasi_tw_3']) ? $ind['realisasi_tw_3'] : 0;
                                    $realisasi_4_html[$key] = !empty($ind['realisasi_tw_4']) ? $ind['realisasi_tw_4'] : 0;

                                    $target_tahunan = (float) str_replace(',', '.', $ind['target_akhir'] ?? 0);
                                    $all_realisasi = [
                                        'realisasi_1' => (float) str_replace(',', '.', $ind['realisasi_tw_1'] ?? 0),
                                        'realisasi_2' => (float) str_replace(',', '.', $ind['realisasi_tw_2'] ?? 0),
                                        'realisasi_3' => (float) str_replace(',', '.', $ind['realisasi_tw_3'] ?? 0),
                                        'realisasi_4' => (float) str_replace(',', '.', $ind['realisasi_tw_4'] ?? 0)
                                    ];

                                    $capaian_realisasi[$key] = $this->get_capaian_realisasi_tahunan_by_type(
                                        $ind['rumus_capaian_kinerja'],
                                        $target_tahunan,
                                        $all_realisasi,
                                        $ind['tahun_anggaran']
                                    );
                                    $capaian_realisasi[$key] = ($capaian_realisasi[$key] === false) ? 'N/A' : $capaian_realisasi[$key] . '%';

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

                                    if (!empty($data_tagging)) {
                                        foreach ($data_tagging as $value) {
                                            $harga_satuan = $value['harga_satuan'];
                                            $volume = $value['volume'];
                                            $total = $volume * $harga_satuan;

                                            $total_harga_tagging_rincian += $total;
                                            $total_realisasi_tagging_rincian += $value['realisasi'];
                                        }
                                    }
                                    $total_harga_tagging_rincian_html[$key] = $this->format_rupiah_tanpa_rp($total_harga_tagging_rincian) ?? 0;
                                    $total_realisasi_tagging_rincian_html[$key] = $this->format_rupiah_tanpa_rp($total_realisasi_tagging_rincian) ?? 0;
                                }
                                $indikator_html = implode('<br>', $indikator_html);
                                $satuan_html = implode('<br>', $satuan_html);
                                $target_awal_html = implode('<br>', $target_awal_html);
                                $target_akhir_html = implode('<br>', $target_akhir_html);
                                $target_1_html = implode('<br>', $target_1_html);
                                $target_2_html = implode('<br>', $target_2_html);
                                $target_3_html = implode('<br>', $target_3_html);
                                $target_4_html = implode('<br>', $target_4_html);
                                $total_harga_tagging_rincian_html = implode('<br>', $total_harga_tagging_rincian_html);
                                $total_realisasi_tagging_rincian_html = implode('<br>', $total_realisasi_tagging_rincian_html);
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
                                if (empty($uraian_renaksi['detail']['satker_id'])) {
                                    $keterangan .= '<li>Satuan Kerja Belum Dipilih</li>';
                                }
                                if (empty($uraian_renaksi['detail']['nip'])) {
                                    $keterangan .= '<li>Pegawai Pelaksana Belum Dipilih</li>';
                                } else {
                                    // Cek NIP apakah existing sesuai satker
                                    $satker_id_utama = substr($uraian_renaksi['detail']['satker_id'], 0, 2);
                                    if (!empty($uraian_renaksi['detail']['id_jabatan_asli'])) {
                                        $get_pegawai = $wpdb->get_row(
                                            $wpdb->prepare("
                                                SELECT 
                                                    *
                                                FROM esakip_data_pegawai_simpeg
                                                WHERE nip_baru = %s
                                                    AND satker_id LIKE %s
                                                    AND id_jabatan = %s
                                                ORDER by active DESC
                                            ", $uraian_renaksi['detail']['nip'], $satker_id_utama . '%', $uraian_renaksi['detail']['id_jabatan_asli']),
                                            ARRAY_A
                                        );
                                    } else {
                                        $get_pegawai = $wpdb->get_row(
                                            $wpdb->prepare("
                                                SELECT 
                                                    *
                                                FROM esakip_data_pegawai_simpeg
                                                WHERE nip_baru = %s
                                                    AND satker_id LIKE %s
                                                ORDER by active DESC
                                            ", $uraian_renaksi['detail']['nip'], $satker_id_utama . '%'),
                                            ARRAY_A
                                        );
                                    }

                                    if (empty($get_pegawai)) {
                                        $keterangan .= '<li>Pegawai pelaksana dengan NIP = ' . $uraian_renaksi['detail']['nip'] . ' dan satker_id = ' . $uraian_renaksi['detail']['satker_id'] . ' tidak ditemukan</li>';
                                    } else if ($get_pegawai['active'] == 0) {
                                        $keterangan .= '<li>Pegawai atas nama ' . $get_pegawai['nama_pegawai'] . ', jabatan ' . $get_pegawai['jabatan'] . ', satker_id ' . $get_pegawai['satker_id'] . ' sudah tidak aktif!</li>';
                                    } else {
                                        if (empty($uraian_renaksi['detail']['id_jabatan_asli'])) {
                                            $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                                'id_jabatan_asli' => $get_pegawai['id_jabatan']
                                            ), array(
                                                'id' => $uraian_renaksi['detail']['id']
                                            ));
                                        }
                                    }
                                }

                                // rhk level 3 cascading kegiatan
                                if (
                                    $v['detail']['input_rencana_pagu_level'] == 1
                                    || $renaksi['detail']['input_rencana_pagu_level'] == 1
                                ) {
                                    // jika parent sudah input sub keg, maka hapus cascading di rhk child nya
                                    if (
                                        !empty($uraian_renaksi['detail']['kode_cascading_sasaran'])
                                        || !empty($uraian_renaksi['detail']['kode_cascading_program'])
                                        || !empty($uraian_renaksi['detail']['kode_cascading_kegiatan'])
                                        || !empty($uraian_renaksi['detail']['kode_cascading_sub_kegiatan'])
                                        || !empty($uraian_renaksi['detail']['pagu_cascading'])
                                    ) {
                                        $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                            'kode_cascading_sasaran' => '',
                                            'kode_cascading_program' => '',
                                            'kode_cascading_kegiatan' => '',
                                            'kode_cascading_sub_kegiatan' => '',
                                            'label_cascading_sasaran' => '',
                                            'label_cascading_program' => '',
                                            'label_cascading_kegiatan' => '',
                                            'label_cascading_sub_kegiatan' => '',
                                            'pagu_cascading' => '0'
                                        ), array(
                                            'id' => $uraian_renaksi['detail']['id']
                                        ));
                                        $uraian_renaksi['detail']['label_cascading_kegiatan'] = '';
                                    }
                                } else {
                                    if ($uraian_renaksi['detail']['input_rencana_pagu_level'] == 1) {
                                        if (empty($uraian_renaksi['detail']['kode_cascading_sub_kegiatan'])) {
                                            $keterangan .= '<li>Cascading Sub Kegiatan Belum dipilih</li>';
                                        } else {
                                            if (empty($data_all_wpsipd[$uraian_renaksi['detail']['kode_cascading_kegiatan']])) {
                                                $_POST['jenis'] = 'sub_kegiatan';
                                                $_POST['parent_cascading'] = $uraian_renaksi['detail']['kode_cascading_kegiatan'];
                                                $data_all_wpsipd[$uraian_renaksi['detail']['kode_cascading_kegiatan']] = $this->get_tujuan_sasaran_cascading(true);
                                            }
                                            // update pagu sub kegiatan jika tidak sama dengan wp-sipd
                                            foreach ($data_all_wpsipd[$uraian_renaksi['detail']['kode_cascading_kegiatan']]['data'] as $val) {
                                                if (
                                                    $uraian_renaksi['detail']['kode_cascading_sub_kegiatan'] == $val->kode_sub_giat
                                                    && $uraian_renaksi['detail']['pagu_cascading'] != $val->pagu
                                                ) {
                                                    $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                                        'pagu_cascading' => $val->pagu
                                                    ), array(
                                                        'id' => $uraian_renaksi['detail']['id']
                                                    ));
                                                }
                                            }
                                        }
                                    } else {
                                        if (empty($uraian_renaksi['detail']['kode_cascading_kegiatan'])) {
                                            $keterangan .= '<li>Cascading Kegiatan Belum dipilih</li>';
                                        } else {
                                            // update pagu kegiatan jika tidak sama dengan wp-sipd
                                            if (!empty($data_all_wpsipd[$renaksi['detail']['kode_cascading_program']])) {
                                                foreach ($data_all_wpsipd[$renaksi['detail']['kode_cascading_program']]['data'] as $val) {
                                                    if (
                                                        $uraian_renaksi['detail']['kode_cascading_kegiatan'] == $val->kode_giat
                                                        && $uraian_renaksi['detail']['pagu_cascading'] != $val->pagu
                                                    ) {
                                                        $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                                            'pagu_cascading' => $val->pagu
                                                        ), array(
                                                            'id' => $uraian_renaksi['detail']['id']
                                                        ));
                                                    }
                                                }
                                            }

                                            if (empty($data_all_wpsipd[$uraian_renaksi['detail']['kode_cascading_kegiatan']])) {
                                                $_POST['jenis'] = 'sub_kegiatan';
                                                $_POST['parent_cascading'] = $uraian_renaksi['detail']['kode_cascading_kegiatan'];
                                                $all_keg_sipd = $this->get_tujuan_sasaran_cascading(true);
                                            }
                                        }
                                    }
                                }

                                $label_cascading = '';
                                if ($uraian_renaksi['detail']['input_rencana_pagu_level'] == 1) {
                                    if ($uraian_renaksi['detail']['label_cascading_kegiatan']) {
                                        $label_cascading .= $uraian_renaksi['detail']['kode_cascading_kegiatan'] . ' ' . $uraian_renaksi['detail']['label_cascading_kegiatan'];
                                    }
                                    if ($uraian_renaksi['detail']['label_cascading_sub_kegiatan']) {
                                        $label_cascading .= '<br>' . $uraian_renaksi['detail']['kode_cascading_sub_kegiatan'] . ' ' . $uraian_renaksi['detail']['label_cascading_sub_kegiatan'];
                                    } else {
                                        $uraian_renaksi['detail']['pagu_cascading'] = 0;
                                    }
                                } else {
                                    if ($uraian_renaksi['detail']['label_cascading_kegiatan']) {
                                        $label_cascading = $uraian_renaksi['detail']['kode_cascading_kegiatan'] . ' ' . $uraian_renaksi['detail']['label_cascading_kegiatan'];
                                    }
                                }
                                $html .= '
                                <tr class="ur-kegiatan">
                                    <td>' . $no . '.' . $no_renaksi . '.' . $no_uraian_renaksi . '</td>
                                    <td class="">' . $keterangan . '</td>
                                    <td class="text-left">' . $label_cascading . '</td>
                                    <td class="kegiatan_utama"></td>
                                    <td class="indikator_kegiatan_utama"></td>
                                    <td class="recana_aksi"></td>
                                    <td class="indikator_renaksi"></td>
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
                                    <td class="text-right anggaran_column">' . $total_harga_tagging_rincian_html . '</td>
                                    <td class="text-right anggaran_column">' . $total_realisasi_tagging_rincian_html . '</td>
                                    <td class="text-right anggaran_column"></td>
                                    <td class="text-right">' . number_format((float)$uraian_renaksi['detail']['pagu_cascading'], 0, ",", ".") . '</td>
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
                                    $total_realisasi_tagging_rincian_html = array();
                                    $total_harga_tagging_rincian_html = array();
                                    $capaian_realisasi = array();
                                    $rencana_pagu_html = array();
                                    $realisasi_pagu_html = array();
                                    foreach ($uraian_teknis_kegiatan['indikator'] as $key => $ind) {
                                        $total_harga_tagging_rincian = 0;
                                        $total_realisasi_tagging_rincian = 0;
                                        $indikator_html[$key] = '<a href="' . $this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $_POST['tahun_anggaran'] . '&id_skpd=' . $_POST['id_skpd'] . '&id_indikator=' . $ind['id']) . '" target="_blank">' . $ind['indikator'] . '</a>';
                                        $satuan_html[$key] = $ind['satuan'];
                                        $target_awal_html[$key] = $ind['target_awal'];
                                        $target_akhir_html[$key] = $ind['target_akhir'];
                                        $target_1_html[$key] = $ind['target_1'];
                                        $target_2_html[$key] = $ind['target_2'];
                                        $target_3_html[$key] = $ind['target_3'];
                                        $target_4_html[$key] = $ind['target_4'];
                                        $rencana_pagu_html[$key] = 0;
                                        if (!empty($ind['rencana_pagu'])) {
                                            $rencana_pagu_html[$key] = number_format((float)$ind['rencana_pagu'], 0, ",", ".");
                                        }
                                        $realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
                                        $realisasi_1_html[$key] = !empty($ind['realisasi_tw_1']) ? $ind['realisasi_tw_1'] : 0;
                                        $realisasi_2_html[$key] = !empty($ind['realisasi_tw_2']) ? $ind['realisasi_tw_2'] : 0;
                                        $realisasi_3_html[$key] = !empty($ind['realisasi_tw_3']) ? $ind['realisasi_tw_3'] : 0;
                                        $realisasi_4_html[$key] = !empty($ind['realisasi_tw_4']) ? $ind['realisasi_tw_4'] : 0;

                                        $target_tahunan = (float) str_replace(',', '.', $ind['target_akhir'] ?? 0);
                                        $all_realisasi = [
                                            'realisasi_1' => (float) str_replace(',', '.', $ind['realisasi_tw_1'] ?? 0),
                                            'realisasi_2' => (float) str_replace(',', '.', $ind['realisasi_tw_2'] ?? 0),
                                            'realisasi_3' => (float) str_replace(',', '.', $ind['realisasi_tw_3'] ?? 0),
                                            'realisasi_4' => (float) str_replace(',', '.', $ind['realisasi_tw_4'] ?? 0)
                                        ];

                                        $capaian_realisasi[$key] = $this->get_capaian_realisasi_tahunan_by_type(
                                            $ind['rumus_capaian_kinerja'],
                                            $target_tahunan,
                                            $all_realisasi,
                                            $ind['tahun_anggaran']
                                        );
                                        $capaian_realisasi[$key] = ($capaian_realisasi[$key] === false) ? 'N/A' : $capaian_realisasi[$key] . '%';

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

                                        if (!empty($data_tagging)) {
                                            foreach ($data_tagging as $value) {
                                                $harga_satuan = $value['harga_satuan'];
                                                $volume = $value['volume'];
                                                $total = $volume * $harga_satuan;

                                                $total_harga_tagging_rincian += $total;
                                                $total_realisasi_tagging_rincian += $value['realisasi'];
                                            }
                                        }
                                        $total_harga_tagging_rincian_html[$key] = $this->format_rupiah_tanpa_rp($total_harga_tagging_rincian) ?? 0;
                                        $total_realisasi_tagging_rincian_html[$key] = $this->format_rupiah_tanpa_rp($total_realisasi_tagging_rincian) ?? 0;
                                    }
                                    $indikator_html = implode('<br>', $indikator_html);
                                    $satuan_html = implode('<br>', $satuan_html);
                                    $target_awal_html = implode('<br>', $target_awal_html);
                                    $target_akhir_html = implode('<br>', $target_akhir_html);
                                    $target_1_html = implode('<br>', $target_1_html);
                                    $target_2_html = implode('<br>', $target_2_html);
                                    $target_3_html = implode('<br>', $target_3_html);
                                    $target_4_html = implode('<br>', $target_4_html);
                                    $total_harga_tagging_rincian_html = implode('<br>', $total_harga_tagging_rincian_html);
                                    $total_realisasi_tagging_rincian_html = implode('<br>', $total_realisasi_tagging_rincian_html);
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
                                    if (empty($uraian_teknis_kegiatan['detail']['satker_id'])) {
                                        $keterangan .= '<li>Satuan Kerja Belum Dipilih</li>';
                                    }
                                    if (empty($uraian_teknis_kegiatan['detail']['nip'])) {
                                        $keterangan .= '<li>Pegawai Pelaksana Belum Dipilih</li>';
                                    } else {
                                        // Cek NIP apakah existing sesuai satker
                                        $satker_id_utama = substr($uraian_teknis_kegiatan['detail']['satker_id'], 0, 2);
                                        if (!empty($uraian_teknis_kegiatan['detail']['id_jabatan_asli'])) {
                                            $get_pegawai = $wpdb->get_row(
                                                $wpdb->prepare("
                                                    SELECT 
                                                        *
                                                    FROM esakip_data_pegawai_simpeg
                                                    WHERE nip_baru = %s
                                                        AND satker_id LIKE %s
                                                        AND id_jabatan = %s
                                                    ORDER by active DESC
                                                ", $uraian_teknis_kegiatan['detail']['nip'], $satker_id_utama . '%', $uraian_teknis_kegiatan['detail']['id_jabatan_asli']),
                                                ARRAY_A
                                            );
                                        } else {
                                            $get_pegawai = $wpdb->get_row(
                                                $wpdb->prepare("
                                                    SELECT 
                                                        *
                                                    FROM esakip_data_pegawai_simpeg
                                                    WHERE nip_baru = %s
                                                        AND satker_id LIKE %s
                                                    ORDER by active DESC
                                                ", $uraian_teknis_kegiatan['detail']['nip'], $satker_id_utama . '%'),
                                                ARRAY_A
                                            );
                                        }

                                        if (empty($get_pegawai)) {
                                            $keterangan .= '<li>Pegawai pelaksana dengan NIP = ' . $uraian_teknis_kegiatan['detail']['nip'] . ' dan satker_id = ' . $uraian_teknis_kegiatan['detail']['satker_id'] . ' tidak ditemukan</li>';
                                        } else if ($get_pegawai['active'] == 0) {
                                            $keterangan .= '<li>Pegawai atas nama ' . $get_pegawai['nama_pegawai'] . ', jabatan ' . $get_pegawai['jabatan'] . ', satker_id ' . $get_pegawai['satker_id'] . ' sudah tidak aktif!</li>';
                                        } else {
                                            if (empty($uraian_teknis_kegiatan['detail']['id_jabatan_asli'])) {
                                                $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                                    'id_jabatan_asli' => $get_pegawai['id_jabatan']
                                                ), array(
                                                    'id' => $uraian_teknis_kegiatan['detail']['id']
                                                ));
                                            }
                                        }
                                    }

                                    // rhk level 4 cascading sub kegiatan
                                    if (
                                        $v['detail']['input_rencana_pagu_level'] == 1
                                        || $renaksi['detail']['input_rencana_pagu_level'] == 1
                                        || $uraian_renaksi['detail']['input_rencana_pagu_level'] == 1
                                    ) {
                                        // jika parent sudah input sub keg, maka hapus cascading di rhk child nya
                                        if (
                                            !empty($uraian_teknis_kegiatan['detail']['kode_cascading_sasaran'])
                                            || !empty($uraian_teknis_kegiatan['detail']['kode_cascading_program'])
                                            || !empty($uraian_teknis_kegiatan['detail']['kode_cascading_kegiatan'])
                                            || !empty($uraian_teknis_kegiatan['detail']['kode_cascading_sub_kegiatan'])
                                            || !empty($uraian_teknis_kegiatan['detail']['pagu_cascading'])
                                        ) {
                                            $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                                'kode_cascading_sasaran' => '',
                                                'kode_cascading_program' => '',
                                                'kode_cascading_kegiatan' => '',
                                                'kode_cascading_sub_kegiatan' => '',
                                                'label_cascading_sasaran' => '',
                                                'label_cascading_program' => '',
                                                'label_cascading_kegiatan' => '',
                                                'label_cascading_sub_kegiatan' => '',
                                                'pagu_cascading' => '0'
                                            ), array(
                                                'id' => $uraian_teknis_kegiatan['detail']['id']
                                            ));
                                            $uraian_teknis_kegiatan['detail']['label_cascading_sub_kegiatan'] = '';
                                        }
                                    } else {
                                        if (empty($uraian_teknis_kegiatan['detail']['kode_cascading_sub_kegiatan'])) {
                                            $keterangan .= '<li>Cascading Sub Kegiatan Belum dipilih</li>';
                                        } else {
                                            // update pagu sub kegiatan jika tidak sama dengan wp-sipd
                                            if (!empty($data_all_wpsipd[$uraian_renaksi['detail']['kode_cascading_kegiatan']])) {
                                                foreach ($data_all_wpsipd[$uraian_renaksi['detail']['kode_cascading_kegiatan']]['data'] as $val) {
                                                    if (
                                                        $uraian_teknis_kegiatan['detail']['kode_cascading_sub_kegiatan'] == $val->kode_sub_giat
                                                        && $uraian_teknis_kegiatan['detail']['pagu_cascading'] != $val->pagu
                                                    ) {
                                                        $wpdb->update('esakip_data_rencana_aksi_opd', array(
                                                            'pagu_cascading' => $val->pagu
                                                        ), array(
                                                            'id' => $uraian_teknis_kegiatan['detail']['id']
                                                        ));
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $label_cascading = '';
                                    if ($uraian_teknis_kegiatan['detail']['label_cascading_sub_kegiatan']) {
                                        $label_cascading = $v['detail']['kode_cascading_sub_kegiatan'] . ' ' . $uraian_teknis_kegiatan['detail']['label_cascading_sub_kegiatan'];
                                    }
                                    $html .= '
                                    <tr>
                                        <td>' . $no . '.' . $no_renaksi . '.' . $no_uraian_renaksi . '.' . $no_uraian_teknis . '</td>
                                        <td class="">' . $keterangan . '</td>
                                        <td class="text-left">' . $label_cascading . '</td>
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
                                        <td class="text-right anggaran_column">' . $total_harga_tagging_rincian_html . '</td>
                                        <td class="text-right anggaran_column">' . $total_realisasi_tagging_rincian_html . '</td>
                                        <td class="text-right anggaran_column"></td>
                                        <td class="text-right">' . number_format((float)$uraian_teknis_kegiatan['detail']['pagu_cascading'], 0, ",", ".") . '</td>
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

                    $renaksi_pemda = $wpdb->get_results($wpdb->prepare("
                        SELECT 
                            r.*,
                            l.id AS id_label
                        FROM esakip_detail_rencana_aksi_pemda AS r
                        LEFT JOIN esakip_data_label_rencana_aksi AS l
                            ON l.parent_renaksi_pemda = r.id 
                            AND l.active = r.active 
                        WHERE l.id IS NULL
                            AND r.active = 1
                            AND r.id_skpd = %d
                            AND r.tahun_anggaran = %d
                    ", $_POST['id_skpd'], $_POST['tahun_anggaran']), ARRAY_A);

                    $get_data_pemda = array();

                    if (!empty($renaksi_pemda)) {
                        $id_renaksi = 0;
                        $id_pk = array();
                        foreach ($renaksi_pemda as $item) {
                            $id_pk[] = $item['id_pk'];
                            $id_renaksi = $item['id'];
                        }

                        $get_id_pk = implode(',', $id_pk);

                        if (!empty($get_id_pk)) {
                            $get_data_pemda = $wpdb->get_results("
                                SELECT 
                                    pk.*,
                                    ik.label_sasaran as ik_label_sasaran,
                                    ik.label_indikator as ik_label_indikator
                                FROM esakip_laporan_pk_pemda pk
                                LEFT JOIN esakip_data_iku_pemda ik
                                    ON pk.id_iku = ik.id 
                                    AND pk.id_jadwal = ik.id_jadwal
                                WHERE pk.active = 1
                                    AND pk.id IN ($get_id_pk)
                            ", ARRAY_A);
                        }
                    }
                    $html_get_data_pemda = '';

                    if (!empty($get_data_pemda)) {
                        foreach ($get_data_pemda as $k_get_data_pemda => $v_get_data_pemda) {

                            if (!empty($v_get_data_pemda['id_iku']) && !empty($v_get_data_pemda['ik_label_sasaran'])) {
                                $label_sasaran = $v_get_data_pemda['ik_label_sasaran'];
                                $label_indikator = $v_get_data_pemda['ik_label_indikator'];
                            } else {
                                $label_sasaran = $v_get_data_pemda['label_sasaran'] ?? '';
                                $label_indikator = $v_get_data_pemda['label_indikator'] ?? '';
                            }

                            $aksi = '<a href="javascript:void(0)" class="btn btn-sm btn-success verifikasi-renaksi-pemda" data-label-sasaran="' . $label_sasaran . '" data-label-indikator="' . $label_indikator . '" data-id_renaksi_pemda="' . $id_renaksi . '" title="Verifikasi Rencana Aksi"><span class="dashicons dashicons-yes"></span></a>';
                            $html_get_data_pemda .= '
                                <tr>
                                    <td class="text-left">' . $label_sasaran . '</td>
                                    <td class="text-left">' . $label_indikator . '</td>
                                    <td>' . $aksi . '</td>
                                </tr>';
                        }
                    }
                    $ret['data_pemda'] = $html_get_data_pemda;
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

	function cek_uraian_cascading_rhk($id_renaksi, $data_cascading, $id_uraian_cascading_saved, $label_rhk, $status_input_rencana_pagu) {
	    global $wpdb;
	    
	    if ($status_input_rencana_pagu == 0) {
		    if (!isset($data_cascading->get_transformasi_cascading) || 
		        !is_array($data_cascading->get_transformasi_cascading)) {
		        return;
		    }
		    
		    $is_valid = false;
		    $matched_uraian = null;
		    
		    foreach ($data_cascading->get_transformasi_cascading as $trans) {
		        if (!isset($trans->induk) || !isset($trans->induk->uraian_cascading)) {
		            continue;
		        }
		        
		        if ($id_uraian_cascading_saved == $trans->id_uraian_cascading) {
		            if (trim($label_rhk) === trim($trans->induk->uraian_cascading)) {
		                $is_valid = true;
		                break;
		            } else {
		                $matched_uraian = $trans;
		                break;
		            }
		        }
		        
		        // Cek berdasarkan label saja jika ID tidak cocok
		        if (trim($label_rhk) === trim($trans->induk->uraian_cascading)) {
		            $matched_uraian = $trans;
		        }
		    }

	    } elseif ($status_input_rencana_pagu == 1) {
		    if (!isset($data_cascading->get_transformasi_cascading_pelaksana) || 
		        !is_array($data_cascading->get_transformasi_cascading_pelaksana)) {
		        return;
		    }
		    
		    $is_valid = false;
		    $matched_uraian = null;
		    
		    foreach ($data_cascading->get_transformasi_cascading_pelaksana as $trans) {
		        if (!isset($trans->induk) || !isset($trans->induk->uraian_cascading)) {
		            continue;
		        }
		        
		        if ($id_uraian_cascading_saved == $trans->id_uraian_cascading) {
		            if (trim($label_rhk) === trim($trans->induk->uraian_cascading)) {
		                $is_valid = true;
		                break;
		            } else {
		                $matched_uraian = $trans;
		                break;
		            }
		        }
		        
		        if (trim($label_rhk) === trim($trans->induk->uraian_cascading)) {
		            $matched_uraian = $trans;
		        }
		    }

	    }
	    
	    // Jika tidak valid, update status_renaksi
	    if (!$is_valid) {
	        $update_data = array('status_renaksi' => 1);
	        
	        // Jika ada uraian yang cocok dengan label, update juga id_uraian_cascading
	        if ($matched_uraian) {
	            $update_data['id_cascading'] = $matched_uraian->id_uraian_cascading;	            
	            error_log("RHK ID {$id_renaksi}: ID cascading tidak sesuai. Diupdate dari {$id_uraian_cascading_saved} ke {$matched_uraian->id_uraian_cascading}");
	        } else {
	            error_log("RHK ID {$id_renaksi}: Tidak ditemukan uraian cascading yang sesuai dengan label: {$label_rhk}");
	        }
	        
	        $wpdb->update(
	            'esakip_data_rencana_aksi_opd',
	            $update_data,
	            array('id' => $id_renaksi)
	        );
	    } else if ($is_valid) {
	        $wpdb->update(
	            'esakip_data_rencana_aksi_opd',
	            array('status_renaksi' => 0),
	            array('id' => $id_renaksi)
	        );
	    }
	}

	function cek_indikator_satuan_cascading($id_indikator, $data_cascading, $id_indikator_cascading_saved, $id_satuan_cascading_saved, $label_indikator, $label_satuan) {
	    global $wpdb;
	    
	    if (!isset($data_cascading->get_transformasi_cascading) || 
	        !is_array($data_cascading->get_transformasi_cascading)) {
	        return;
	    }
	    
	    $is_indikator_valid = false;
	    $is_satuan_valid = false;
	    $matched_indikator = null;
	    $matched_satuan = null;
	    
	    foreach ($data_cascading->get_transformasi_cascading as $trans) {
	        if (!isset($trans->induk) || !isset($trans->induk->indikator)) {
	            continue;
	        }
	        
	        if (!is_array($trans->induk->indikator)) {
	            continue;
	        }
	        
	        foreach ($trans->induk->indikator as $indikator) {
	            if ($id_indikator_cascading_saved == $indikator->id) {
	                if (trim($label_indikator) === trim($indikator->indikator)) {
	                    $is_indikator_valid = true;
	                } else {
	                    $matched_indikator = $indikator;
	                }
	            }
	            
	            if (!$is_indikator_valid && trim($label_indikator) === trim($indikator->indikator)) {
	                $matched_indikator = $indikator;
	            }
	            
	            if ($is_indikator_valid || $matched_indikator) {
	                if (isset($indikator->satuan) && is_array($indikator->satuan)) {
	                    foreach ($indikator->satuan as $satuan) {
	                        if ($id_satuan_cascading_saved == $satuan->id) {
	                            if (trim($label_satuan) === trim($satuan->satuan)) {
	                                $is_satuan_valid = true;
	                            } else {
	                                $matched_satuan = $satuan;
	                            }
	                        }
	                        
	                        if (!$is_satuan_valid && trim($label_satuan) === trim($satuan->satuan)) {
	                            $matched_satuan = $satuan;
	                        }
	                    }
	                }
	            }
	            
	            if ($is_indikator_valid) {
	                break;
	            }
	        }
	        
	        if ($is_indikator_valid) {
	            break;
	        }
	    }
	    
	    $update_data = array();
	    
	    if (!$is_indikator_valid) {
	        $update_data['status_indikator_cascading'] = 1;
	        
	        if ($matched_indikator) {
	            $update_data['id_indikator_cascading'] = $matched_indikator->id;
	            error_log("Indikator ID {$id_indikator}: ID cascading tidak sesuai. Diupdate dari {$id_indikator_cascading_saved} ke {$matched_indikator->id}");
	        } else {
	            error_log("Indikator ID {$id_indikator}: Tidak ditemukan indikator cascading yang sesuai dengan label: {$label_indikator}");
	        }
	    } else {
	        $update_data['status_indikator_cascading'] = 0;
	    }
	    
	    if (!$is_satuan_valid) {
	        $update_data['status_satuan_cascading'] = 1;
	        
	        if ($matched_satuan) {
	            $update_data['id_satuan_cascading'] = $matched_satuan->id;
	            error_log("Indikator ID {$id_indikator}: ID satuan cascading tidak sesuai. Diupdate dari {$id_satuan_cascading_saved} ke {$matched_satuan->id}");
	        } else {
	            error_log("Indikator ID {$id_indikator}: Tidak ditemukan satuan cascading yang sesuai dengan label: {$label_satuan}");
	        }
	    } else {
	        $update_data['status_satuan_cascading'] = 0;
	    }
	    
	    if (!empty($update_data)) {
	        $wpdb->update(
	            'esakip_data_rencana_aksi_indikator_opd',
	            $update_data,
	            array('id' => $id_indikator)
	        );
	    }
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
					$id_jadwal_rpjmd_rhk = !empty($_POST['id_jadwal_rpjmd_rhk']) ? $_POST['id_jadwal_rpjmd_rhk'] : null;
					$input_renaksi = $_POST['input_renaksi'];
					$set_pagu_renaksi = $_POST['set_pagu_renaksi'];
					$set_tabel_individu = $_POST['set_tabel_individu'];

					// Pengaturan rencana aksi
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
						'id_jadwal_rpjmd_rhk' => $id_jadwal_rpjmd_rhk,
						'tahun_anggaran' => $tahun_anggaran,
						'active' => 1,
						'update_at' => current_time('mysql')
					);

					if (empty($cek_data_pengaturan)) {
						$data['created_at'] = current_time('mysql');
						$wpdb->insert('esakip_pengaturan_upload_dokumen', $data);
						$message = "Sukses tambah data";
					} else {
						$wpdb->update('esakip_pengaturan_upload_dokumen', $data, array('id' => $cek_data_pengaturan));
						$message = "Sukses edit data";
					}

					update_option('_crb_input_renaksi', $input_renaksi);
					update_option('_crb_set_pagu_renaksi', $set_pagu_renaksi);
					update_option('_crb_set_tabel_individu', $set_tabel_individu);

					echo json_encode([
						'status' => 'success',
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
				'status' => 'error',
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

							$indikator = $v['label_indikator'];

							if (strpos($indikator, ' - ') !== false) {
								$indikator_array = explode(' - ', $indikator);
								$indikator = '- ' . implode('<br/>- ', $indikator_array);
							} else if (strpos($indikator, "\n- ") !== false) {
								$indikator_array = explode("\n- ", $indikator);
								$indikator = implode("<br/>- ", $indikator_array);
							}

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
			'status' 	=> 'success',
			'message' 	=> 'Berhasil simpan Iku!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_unik'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tujuan/Sasaran tidak boleh kosong!';
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
					$label_indikator = '';
					if (!empty($_POST['label_indikator'])) {
						if (is_array($_POST['label_indikator'])) {
							$get_indikator = array();
							foreach ($_POST['label_indikator'] as $indikator) {
								$indikator = trim($indikator);
								if (!empty($indikator)) {
									$get_indikator[] = '- ' . $indikator;
								}
							}
							$label_indikator = implode("\n", $get_indikator);
						} else {
							$indikator_array = explode(' - ', $_POST['label_indikator']);
							$get_indikator = array();
							foreach ($indikator_array as $indikator) {
								$indikator = trim($indikator);
								if (!empty($indikator)) {
									$get_indikator[] = '- ' . $indikator;
								}
							}
							$label_indikator = implode("\n", $get_indikator);
						}
					}

					$formulasi = isset($_POST['formulasi']) ? stripslashes($_POST['formulasi']) : '';

					$data = array(
						'kode_sasaran' 		=> $_POST['id_unik'],
						'label_sasaran' 	=> $_POST['label_tujuan_sasaran'],
						'id_unik_indikator' => $_POST['id_unik_indikators'],
						'label_indikator' 	=> $label_indikator,
						'formulasi' 		=> $formulasi,
						'sumber_data' 		=> $_POST['sumber_data'],
						'penanggung_jawab' 	=> $_POST['penanggung_jawab'],
						'id_skpd' 			=> $_POST['id_skpd'],
						'id_jadwal_wpsipd' 	=> $_POST['id_jadwal_wpsipd'],
						'active' 			=> 1,
					);

					if (!empty($_POST['id_iku'])) {
						$cek_id = $_POST['id_iku'];
						$data_cek_iku = $wpdb->get_var(
							$wpdb->prepare("
	                            SELECT id
	                            FROM esakip_data_iku_opd
	                            WHERE id=%d
	                        ", $cek_id)
						);
					}

					if (empty($data_cek_iku)) {
						$wpdb->insert(
							'esakip_data_iku_opd',
							$data
						);
					} else {
						$ret['message'] = 'Berhasil Update Data!';

						$wpdb->update(
							'esakip_data_iku_opd',
							$data,
							array('id' => $data_cek_iku)
						);
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
			'data'  => array(),
			'data_tujuan'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$data_tujuan = $wpdb->get_results(
					$wpdb->prepare("
							SELECT 
								*
							FROM 
								esakip_rpd_tujuan
							WHERE active=1
								AND id_jadwal=%d
							order by no_urut
						", $_POST['id_jadwal']),
					ARRAY_A
				);
				$data = $wpdb->get_results(
					$wpdb->prepare("
							SELECT 
								*
							FROM 
								esakip_rpd_sasaran
							WHERE active=1
								AND id_jadwal=%d
							order by sasaran_no_urut
						", $_POST['id_jadwal']),
					ARRAY_A
				);
				if (!empty($data)) {
					$ret['data'] = $data;
				}
				if (!empty($data_tujuan)) {
					$ret['data_tujuan'] = $data_tujuan;
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

	function get_sasaran_sebelum()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'sasaran'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

				$id_sasaran = isset($_POST['id_sasaran']) ? intval($_POST['id_sasaran']) : 0;
				$sasaran = $wpdb->get_row(
					$wpdb->prepare("
						SELECT 
							*
						FROM esakip_rpd_sasaran
						WHERE id = %d 
							AND active = 1
					", $id_sasaran),
					ARRAY_A
				);
				if (!empty($sasaran)) {
					$ret['sasaran_sebelum'] = $sasaran['sasaran_teks'];
					$ret['id_sasaran_murni'] = $sasaran['id'];
				}
				// print_r($sasaran); die($wpdb->last_query);
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

				if (empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID jadwal tidak boleh kosong!';
					die(json_encode($ret));
				}

				$periode = $wpdb->get_row($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_data_jadwal
					WHERE id=%d 
						AND status=1
				", $_POST['id_jadwal']), ARRAY_A);

				if (!$periode) {
					$ret['status'] = 'error';
					$ret['message'] = 'Data jadwal tidak ditemukan!';
					die(json_encode($ret));
				}

				$lama_pelaksanaan = $periode['lama_pelaksanaan'];
				$id_jadwal_murni = $periode['id_jadwal_murni'];

				$data_iku = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_data_iku_pemda
					WHERE id_jadwal=%d 
						AND active=1
				", $_POST['id_jadwal']), ARRAY_A);

				$html = '';
				$no = 0;

				$group = [];
				foreach ($data_iku as $v) {
					$key = $v['id_sasaran_murni'];
					if (!isset($group[$key])) {
						$group[$key] = 0;
					}
					$group[$key]++;
				}
				$id_sasaran_murni = [];

				if (!empty($data_iku)) {
					foreach ($data_iku as $v) {
						$no++;
						$indikator = explode(" \n- ", $v['label_indikator']);
						$indikator = implode("</br>- ", $indikator);

						$html .= '<tr>';
						$html .= '<td class="atas kanan bawah kiri">' . $no . '</td>';

						if (!empty($id_jadwal_murni)) {
							$id_sasaran = $v['id_sasaran_murni'];
							if ($id_sasaran != 0 && !in_array($id_sasaran, $id_sasaran_murni)) {
								$sasaran_existing = $wpdb->get_row($wpdb->prepare("
									SELECT 
										* 
									FROM esakip_rpd_sasaran
									WHERE id=%d AND active=1
								", $id_sasaran), ARRAY_A);

								$sasaran_teks = $sasaran_existing ? $sasaran_existing['sasaran_teks'] : '-';
								$rowspan = $group[$id_sasaran];
								$html .= '<td class="text-left atas kanan bawah kiri" rowspan="' . $rowspan . '" style="vertical-align: middle;">' . $sasaran_teks . '</td>';

								$id_sasaran_murni[] = $id_sasaran;
							} elseif ($id_sasaran == 0) {
								$html .= '<td class="text-left atas kanan bawah kiri">-</td>';
							}
						}

						$html .= '<td class="text-left atas kanan bawah kiri">' . $v['label_sasaran'] . '</td>';
						$html .= '<td class="text-left atas kanan bawah kiri">' . $indikator . '</td>';
						$html .= '<td class="text-left atas kanan bawah kiri">' . wp_kses_post($v['formulasi']) . '</td>';
						$html .= '<td class="text-left atas kanan bawah kiri">' . $v['sumber_data'] . '</td>';
						$html .= '<td class="text-left atas kanan bawah kiri">' . $v['penanggung_jawab'] . '</td>';
						$html .= '<td class="text-left atas kanan bawah kiri">' . $v['satuan'] . '</td>';

						for ($i = 1; $i <= $lama_pelaksanaan; $i++) {
							$key_target = 'target_' . $i;
							$key_realisasi = 'realisasi_' . $i;
							$target = !empty($v[$key_target]) ? $v[$key_target] : 0;
							$realisasi = !empty($v[$key_realisasi]) ? $v[$key_realisasi] : 0;
							$html .= '<td class="text-center atas kanan bawah kiri">' . $target . '</td>';
							$html .= '<td class="text-center atas kanan bawah kiri">' . $realisasi . '</td>';
						}

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_iku(\'' . $v['id'] . '\'); return false;" title="Edit IKU"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_iku(\'' . $v['id'] . '\'); return false;" title="Hapus IKU"><span class="dashicons dashicons-trash"></span></button>';
						$btn .= '</div>';

						$html .= "<td class='text-center atas kanan bawah kiri hide-excel'>" . $btn . "</td>";
						$html .= '</tr>';
					}
				}

				if (empty($html)) {
					$colspan = 9 + ($lama_pelaksanaan * 2);
					$html = '<tr><td class="text-center" colspan="' . $colspan . '">Data masih kosong!</td></tr>';
				}

				$ret['data'] = $html;
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

	function tambah_iku_pemda()
	{
		global $wpdb;

		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan Iku!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id_unik'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Unik tidak boleh kosong!';
				} else if (empty($_POST['id_indikator'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Indikator tidak boleh kosong!';
				} else if (empty($_POST['formulasi'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Definisi Operasional/Formulasi tidak boleh kosong!';
				} else if (empty($_POST['sumber_data'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Sumber Data tidak boleh kosong!';
				} else if (empty($_POST['penanggung_jawab'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Penanggung Jawab tidak boleh kosong!';
				} else if (empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Jadwal tidak boleh kosong!';
				} else if (empty($_POST['rumus_capaian_kinerja'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Rumus Capaian Kinerja tidak boleh kosong!';
				}

				if ($ret['status'] != 'error') {
					$formulasi = isset($_POST['formulasi']) ? stripslashes($_POST['formulasi']) : '';

					$data = array(
						'id_sasaran' => $_POST['id_unik'],
						'label_sasaran' => $_POST['label_tujuan_sasaran'],
						'id_unik_indikator' => $_POST['id_indikator'],
						'label_indikator' => $_POST['label_indikator'],
						'formulasi' => $formulasi,
						'sumber_data' => $_POST['sumber_data'],
						'penanggung_jawab' => $_POST['penanggung_jawab'],
						'id_jadwal' => $_POST['id_jadwal'],
						'satuan' => $_POST['satuan'],
						'target_1' => $_POST['target_1'],
						'target_2' => $_POST['target_2'],
						'target_3' => $_POST['target_3'],
						'target_4' => $_POST['target_4'],
						'target_5' => $_POST['target_5'],
						'realisasi_1' => $_POST['realisasi_1'],
						'realisasi_2' => $_POST['realisasi_2'],
						'realisasi_3' => $_POST['realisasi_3'],
						'realisasi_4' => $_POST['realisasi_4'],
						'realisasi_5' => $_POST['realisasi_5'],
						'id_sasaran_murni' => $_POST['id_sasaran_murni'],
						'rumus_capaian_kinerja' => $_POST['rumus_capaian_kinerja'],
						'active' => 1,
						'updated_at' => current_time('mysql'),
					);

					$cek_id = null;

					if (!empty($_POST['id_iku'])) {
						$cek_id = $_POST['id_iku'];
						$data_cek_iku = $wpdb->get_var($wpdb->prepare("
	                        SELECT id FROM esakip_data_iku_pemda WHERE id = %d AND active = 1
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
									return number_format((float)$item, 0, ",", ".");
								}, $rencana_pagu_html)) : 0;
								$mitra_bidang_html = implode('<br>', $mitra_bidang_html);
								$nama_skpd_html = implode('<br>', $nama_skpd_html);

								$label_pokin = $uraian_renaksi['detail']['label_pokin_5'] ?: $uraian_renaksi['detail']['label_pokin_4'];
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

								if (!empty($cek)) {
									$link = "<a href='" . $detail_pengisian_rencana_aksi['url'] . "&id_skpd=" . $ind['id_skpd'] . "' target='_blank'>" . $ind['nama_skpd'] . "</a>";
									$bg = '';
								} else {
									$bg = 'background-color: #ff00002e;';
									$link = $ind['nama_skpd'];
								}

								$html .= '
							        <tr>
							            <td>' . $no . '.' . $no_uraian_renaksi . '</td>
							            <td class="kegiatan_utama"></td>
							            <td class="indikator_kegiatan_utama"></td>
							            <td class="recana_aksi"></td>
							            <td class="kiri kanan bawah text_blok urian_renaksi">' . $uraian_renaksi['detail']['label'] . '
							                <a href="javascript:void(0)" data-id="' . $uraian_renaksi['detail']['id'] . '" data-tipe="3" 
							                   class="help-rhk-pemda" onclick="help_rhk_pemda(' . $uraian_renaksi['detail']['id'] . ', 3); return false;" title="Detail">
							                   <i class="dashicons dashicons-editor-help"></i>
							                </a>
							            </td>
							            <td class="text-center">' . $satuan_html . '</td>
							            <td>' . $indikator_html . '</td>
							            <td class="text-center">' . $target_akhir_html . '</td>
							            <td class="text-center">' . $target_awal_html . '</td>
							            <td class="text-center">' . $target_1_html . '</td>
							            <td class="text-center">' . $target_2_html . '</td>
							            <td class="text-center">' . $target_3_html . '</td>
							            <td class="text-center">' . $target_4_html . '</td>
							            <td class="text-center">' . $target_akhir_html . '</td>
							            <td class="text-right">' . $rencana_pagu . '</td>
									    <td class="text-center" style="' . $bg . '">' . $link . '</td>
							            <td class="text-left mitra_bidang">' . $mitra_bidang_html . '</td>
							        </tr>';
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

				$id_renaksi_pemda = isset($_POST['id_renaksi_pemda']) ? intval($_POST['id_renaksi_pemda']) : 0;
				$checklist_renaksi_opd = isset($_POST['checklist_renaksi_opd']) ? $_POST['checklist_renaksi_opd'] : [];
				$id_skpd = isset($_POST['id_skpd']) ? intval($_POST['id_skpd']) : 0;
				$id_label_renaksi_opd = isset($_POST['id_label_renaksi_opd']) ? $_POST['id_label_renaksi_opd'] : [];
				$tahun = isset($_POST['tahun']) ? intval($_POST['tahun']) : 0;

				if (!empty($checklist_renaksi_opd) && !empty($id_label_renaksi_opd)) {
					foreach ($checklist_renaksi_opd as $index => $label_renaksi_opd) {
						$parent_renaksi_opd = isset($id_label_renaksi_opd[$index]) ? intval($id_label_renaksi_opd[$index]) : 0;

						$data = array(
							'parent_renaksi_opd' => $parent_renaksi_opd,
							'parent_renaksi_pemda' => $id_renaksi_pemda,
							'id_skpd' => $id_skpd,
							'tahun_anggaran' => $tahun,
							'active' => 1
						);

						$cek_id = $wpdb->get_var($wpdb->prepare("
		                    SELECT 
		                    	id 
		                    FROM esakip_data_label_rencana_aksi
		                    WHERE active = 1
		                        AND id = %d
		                        AND tahun_anggaran = %d
		                        AND id_skpd = %d
		                ", $id_pk, $tahun_anggaran, $id_skpd));

						if (empty($cek_id)) {
							$wpdb->insert('esakip_data_label_rencana_aksi', $data);
							$ret['message'] = "Berhasil simpan data!";
						} else {
							$wpdb->update(
								'esakip_data_label_rencana_aksi',
								$data,
								array('id' => $cek_id)
							);
							$ret['message'] = "Berhasil update data!";
						}
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
						ORDER BY kode_cascading_sasaran, kode_cascading_program, id_sub_skpd_cascading, kode_cascading_kegiatan, kode_cascading_sub_kegiatan, level, id ASC
					", $tahun_anggaran, $nip), ARRAY_A);

					$data_all = array(
						'total' => 0,
						'action' => $_POST['action'],
						'data' => array(),
						'status' => true,
						'message' => 'Berhasil get data rencana hasil kerja!',
					);
					$rhk_unik = $this->get_rhk_unik($data);
					foreach ($rhk_unik as $v) {
						$data_all['total']++;
						$v['id'] = implode('|', $v['ids']);
						$indikator = array();
						foreach ($v['indikator'] as $vv) {
							$vv['id'] = implode('|', $vv['ids']);
							$vv['data'][0]['id'] = $vv['id'];
							$vv['data'][0]['id_renaksi'] = $v['id'];
							$indikator[] = $vv['data'][0];
						}
						$rhk_parent = array();
						if (!empty($v['parent'])) {
							$rhk_parent = $this->get_rhk_parent($v['parent'], $tahun_anggaran);
						}
						$v['data'][0]['id'] = $v['id'];
						$data_all['data'][$v['id']] = array(
							'detail' => $v['data'][0],
							'indikator' => $indikator,
							'detail_atasan' => $rhk_parent
						);
					}
					// $data_all['sql'] = $wpdb->last_query;
					die(json_encode($data_all));
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

	function get_rhk_parent($parent, $tahun_anggaran, $all_data = array())
	{
		global $wpdb;
		/**
		 * Fungsi ini digunakan di beberapa tempat,
		 * get_rencana_hasil_kerja(), get_data_renaksi()
		 */
		$rhk_parent = $wpdb->get_row($wpdb->prepare("
			SELECT
				*
			FROM esakip_data_rencana_aksi_opd
			WHERE tahun_anggaran=%d
				AND active=1
				AND id=%d
		", $tahun_anggaran, $parent), ARRAY_A);
		if (!empty($rhk_parent)) {
			$all_data[$rhk_parent['level']] = $rhk_parent;
			if (empty($rhk_parent['parent'])) {
				return $all_data;
			}
			return $this->get_rhk_parent($rhk_parent['parent'], $tahun_anggaran, $all_data);
		} else {
			return $all_data;
		}
	}

	function get_rhk_child($opsi)
	{
		global $wpdb;
		$rhk_child = $wpdb->get_results($wpdb->prepare("
			SELECT
				*
			FROM esakip_data_rencana_aksi_opd
			WHERE tahun_anggaran=%d
				AND active=1
				AND parent IN (" . $opsi['id'] . ")
		", $opsi['tahun']), ARRAY_A);

		if (empty($opsi['all_data'])) {
			$opsi['all_data'] = array();
		}
		if (!empty($rhk_child)) {
			$level = $rhk_child[0]['level'];
			$opsi['all_data'][$level] = array();
			$all_ids = array();
			foreach ($rhk_child as $v) {
				$opsi['all_data'][$level][] = $v;

				if (
					!empty($opsi['check_input_pagu'])
					&& $v['input_rencana_pagu_level'] != 1
				) {
					$all_ids[] = $v['id'];
				}
			}
			if (empty($all_ids)) {
				return $opsi['all_data'];
			} else {
				$opsi['id'] = implode(',', $all_ids);
				return $this->get_rhk_child($opsi);
			}
		}
		return $opsi['all_data'];
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
	                		AND active=1
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

	function get_rencana_pagu_rhk($opsi)
	{
		global $wpdb;
		$opsi['data_ind'] = array();
		$opsi['total'] = 0;

		// jika level rhk adalah yang terkahir maka tidak perlu cek rhk child
		if ($opsi['level'] == 4) {
			return $opsi;
		}

		$ids = implode(',', $opsi['ids']);
		$data_rhk_existing = $wpdb->get_row(
			$wpdb->prepare("
				SELECT *
				FROM esakip_data_rencana_aksi_opd 
				WHERE id IN ($ids) 
				  AND level = %d 
				  AND id_skpd = %d
				  AND active = 1
				ORDER BY kode_cascading_program, 
					kode_cascading_kegiatan, 
					kode_cascading_sub_kegiatan
			", $opsi['level'], $opsi['id_skpd']),
			ARRAY_A
		);

		if ($data_rhk_existing['input_rencana_pagu_level'] == 1) {
			$data_rhk_child = array($data_rhk_existing);
		} else {
			$data_rhk_child = $wpdb->get_results(
				$wpdb->prepare("
					SELECT *
					FROM esakip_data_rencana_aksi_opd 
					WHERE parent IN ($ids) 
					  AND level = %d 
					  AND id_skpd = %d
					  AND active = 1
					ORDER BY kode_cascading_program, 
						kode_cascading_kegiatan, 
						kode_cascading_sub_kegiatan
				", $opsi['level'] + 1, $opsi['id_skpd']),
				ARRAY_A
			);
		}

		$jenis_level = array(
			'1' => 'sasaran',
			'2' => 'program',
			'3' => 'kegiatan',
			'4' => 'sub_kegiatan'
		);
		if (!empty($data_rhk_child)) {
			foreach ($data_rhk_child as $v_rhk_child) {
				// die(print_r($data_rhk_child));

				$index_level = $jenis_level[$v_rhk_child['level']];
				if ($v_rhk_child['input_rencana_pagu_level'] == 1 && $v_rhk_child['cascading_pk'] == 3) {
					$index = $v_rhk_child['kode_cascading_sub_kegiatan'];
					$index_level = 'sub_kegiatan';
				} elseif ($v_rhk_child['input_rencana_pagu_level'] == 1 && $v_rhk_child['cascading_pk'] == 2) {
					$index = $v_rhk_child['kode_cascading_kegiatan'];
					$index_level = 'kegiatan';
				}  elseif ($v_rhk_child['input_rencana_pagu_level'] == 1 && $v_rhk_child['cascading_pk'] == 1) {
					$index = $v_rhk_child['kode_cascading_program'];
					$index_level = 'program';
				} elseif ($v_rhk_child['level'] == 2) {
					$index = $v_rhk_child['kode_cascading_program'];
				} elseif ($v_rhk_child['level'] == 3) {
					$index = $v_rhk_child['kode_cascading_kegiatan'];
				} elseif ($v_rhk_child['level'] == 4) {
					$index = $v_rhk_child['kode_cascading_sub_kegiatan'];
				}

				if (empty($index)) {
					continue;
				}

				$opsi['data_ind'][$v_rhk_child['id']] = array();
				if (empty($opsi['data_anggaran'][$index_level][$index])) {
					$opsi['data_anggaran'][$index_level][$index] = array(
						'ids' 			=> array(),
						'sumber_dana' 	=> array(),
						'total' 		=> 0,
						'data' 			=> array()
					);
				}

				$rencana_pagu = 0;
				$sumber_dana = array();
				// di level 4 tidak perlu check input rencana pagu karena level terakhir
				if ($v_rhk_child['level'] == '4') {
					$data_indikator_anggaran = $wpdb->get_results(
						$wpdb->prepare("
							SELECT
								id,
								rencana_pagu
							FROM esakip_data_rencana_aksi_indikator_opd 
							WHERE id_renaksi = %d 
							  AND active = 1
						", $v_rhk_child['id']),
						ARRAY_A
					);
					if (!empty($data_indikator_anggaran)) {
						foreach ($data_indikator_anggaran as $v_indikator_anggaran) {
							$rencana_pagu += $v_indikator_anggaran['rencana_pagu'];
							$opsi['data_ind'][$v_rhk_child['id']][$v_indikator_anggaran['id']] = $v_indikator_anggaran;
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
					//CHECK IF CURRENT LEVEL IS SET TO LAST LEVEL THEN COUNT RENCANA PAGU
					if ($v_rhk_child['input_rencana_pagu_level'] == 1) {
						$data_indikator_anggaran = $wpdb->get_results(
							$wpdb->prepare("
								SELECT
									id,
									rencana_pagu
								FROM esakip_data_rencana_aksi_indikator_opd 
								WHERE id_renaksi = %d 
								  AND active = 1
							", $v_rhk_child['id']),
							ARRAY_A
						);

						if (!empty($data_indikator_anggaran)) {
							foreach ($data_indikator_anggaran as $v_indikator_anggaran) {
								$rencana_pagu += $v_indikator_anggaran['rencana_pagu'];
								$opsi['data_ind'][$v_rhk_child['id']][$v_indikator_anggaran['id']] = $v_indikator_anggaran;
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
					} else {
						$rhk_lv_4 = $wpdb->get_results(
							$wpdb->prepare("
								SELECT 
									*
								FROM esakip_data_rencana_aksi_opd 
								WHERE parent = %d 
								  AND level = %d 
								  AND id_skpd = %d
								  AND active = 1
								ORDER BY kode_cascading_sub_kegiatan
							", $v_rhk_child['id'], 4, $opsi['id_skpd']),
							ARRAY_A
						);
						if (!empty($rhk_lv_4)) {
							foreach ($rhk_lv_4 as $v_rhk_child_4) {
								$v_rhk_child['kode_cascading_sub_kegiatan'] = $v_rhk_child_4['kode_cascading_sub_kegiatan'];
								$v_rhk_child['label_cascading_sub_kegiatan'] = $v_rhk_child_4['label_cascading_sub_kegiatan'];
								$data_indikator_anggaran = $wpdb->get_results(
									$wpdb->prepare("
										SELECT
											id,
											rencana_pagu
										FROM esakip_data_rencana_aksi_indikator_opd 
										WHERE id_renaksi = %d 
										  AND active = 1
									", $v_rhk_child_4['id']),
									ARRAY_A
								);

								if (!empty($data_indikator_anggaran)) {
									foreach ($data_indikator_anggaran as $v_indikator_anggaran) {
										$rencana_pagu += $v_indikator_anggaran['rencana_pagu'];
										$opsi['data_ind'][$v_rhk_child['id']][$v_indikator_anggaran['id']] = $v_indikator_anggaran;
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
				} else if ($v_rhk_child['level'] == '2') {
					//CHECK IF CURRENT LEVEL IS SET TO LAST LEVEL THEN COUNT RENCANA PAGU
					if ($v_rhk_child['input_rencana_pagu_level'] == 1) {
						$data_indikator_anggaran = $wpdb->get_results(
							$wpdb->prepare("
								SELECT
									id,
									rencana_pagu
								FROM esakip_data_rencana_aksi_indikator_opd 
								WHERE id_renaksi = %d 
								  AND active = 1
							", $v_rhk_child['id']),
							ARRAY_A
						);
						if (!empty($data_indikator_anggaran)) {
							foreach ($data_indikator_anggaran as $v_indikator_anggaran) {
								$rencana_pagu += $v_indikator_anggaran['rencana_pagu'];
								$opsi['data_ind'][$v_rhk_child['id']][$v_indikator_anggaran['id']] = $v_indikator_anggaran;
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
					} else {
						$rhk_lv_3 = $wpdb->get_results(
							$wpdb->prepare("
								SELECT 
									*
								FROM esakip_data_rencana_aksi_opd 
								WHERE parent = %d 
								  AND level = %d 
								  AND id_skpd = %d
								  AND active = 1
								ORDER BY kode_cascading_sub_kegiatan
							", $v_rhk_child['id'], 3, $opsi['id_skpd']),
							ARRAY_A
						);
						if (!empty($rhk_lv_3)) {
							foreach ($rhk_lv_3 as $v_rhk_child_3) {
								$v_rhk_child['kode_cascading_kegiatan'] = $v_rhk_child_3['kode_cascading_kegiatan'];
								$v_rhk_child['label_cascading_kegiatan'] = $v_rhk_child_3['label_cascading_kegiatan'];

								//CHECK IF CURRENT LEVEL IS SET TO LAST LEVEL THEN COUNT RENCANA PAGU
								if ($v_rhk_child_3['input_rencana_pagu_level'] == 1) {
									$v_rhk_child['kode_cascading_sub_kegiatan'] = $v_rhk_child_3['kode_cascading_sub_kegiatan'];
									$v_rhk_child['label_cascading_sub_kegiatan'] = $v_rhk_child_3['label_cascading_sub_kegiatan'];
									$data_indikator_anggaran = $wpdb->get_results(
										$wpdb->prepare("
											SELECT
												id,
												rencana_pagu
											FROM esakip_data_rencana_aksi_indikator_opd 
											WHERE id_renaksi = %d 
											AND active = 1
										", $v_rhk_child_3['id']),
										ARRAY_A
									);
									if (!empty($data_indikator_anggaran)) {
										foreach ($data_indikator_anggaran as $v_indikator_anggaran) {
											$rencana_pagu += $v_indikator_anggaran['rencana_pagu'];
											$opsi['data_ind'][$v_rhk_child['id']][$v_indikator_anggaran['id']] = $v_indikator_anggaran;
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
								} else {
									$rhk_lv_4 = $wpdb->get_results(
										$wpdb->prepare("
											SELECT 
												*
											FROM esakip_data_rencana_aksi_opd 
											WHERE parent = %d 
											  AND level = %d 
											  AND id_skpd = %d
											  AND active = 1
											ORDER BY kode_cascading_sub_kegiatan
										", $v_rhk_child_3['id'], 4, $opsi['id_skpd']),
										ARRAY_A
									);
									if (!empty($rhk_lv_4)) {
										foreach ($rhk_lv_4 as $v_rhk_child_4) {
											$v_rhk_child['kode_cascading_sub_kegiatan'] = $v_rhk_child_4['kode_cascading_sub_kegiatan'];
											$v_rhk_child['label_cascading_sub_kegiatan'] = $v_rhk_child_4['label_cascading_sub_kegiatan'];

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
													$opsi['data_ind'][$v_rhk_child['id']][$v_indikator_anggaran['id']] = $v_indikator_anggaran;
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
					}
				}
				$opsi['data_anggaran'][$index_level][$index]['data'][] = array(
					'nama'           => $v_rhk_child['label_cascading_' . $index_level],
					'kode'           => $v_rhk_child['kode_cascading_' . $index_level],
					'sumber_dana'    => implode(', ', $sumber_dana),
					'total_anggaran' => $rencana_pagu,
					'urut' 			 => $opsi['no_urut_rhk'],
					'id' 			 => $v_rhk_child['id']
				);

				$opsi['data_anggaran'][$index_level][$index]['ids'][] = $v_rhk_child['id'];
				$opsi['data_anggaran'][$index_level][$index]['total'] += $rencana_pagu;
				$opsi['total'] += $rencana_pagu;

				foreach ($sumber_dana as $sd) {
					if (!in_array($sd, $opsi['data_anggaran'][$index_level][$index]['sumber_dana'])) {
						$opsi['data_anggaran'][$index_level][$index]['sumber_dana'][$sd] = $sd;
					}
				}

				// setting anggaran all
				if (!empty($v_rhk_child['kode_cascading_program'])) {
					if (empty($opsi['data_anggaran_all']['program'][$v_rhk_child['kode_cascading_program']])) {
						$opsi['data_anggaran_all']['program'][$v_rhk_child['kode_cascading_program']] = array(
							'ids' 			=> array(),
							'sumber_dana' 	=> array(),
							'total' 		=> 0,
							'data' 			=> array()
						);
					}
					$opsi['data_anggaran_all']['program'][$v_rhk_child['kode_cascading_program']]['data'][] = array(
						'nama'           => $v_rhk_child['label_cascading_program'],
						'kode'           => $v_rhk_child['kode_cascading_program'],
						'sumber_dana'    => implode(', ', $sumber_dana),
						'total_anggaran' => $rencana_pagu,
						'urut' 			 => $opsi['no_urut_rhk'],
						'id' 			 => $v_rhk_child['id']
					);
					$opsi['data_anggaran_all']['program'][$v_rhk_child['kode_cascading_program']]['ids'][] = $v_rhk_child['id'];
					$opsi['data_anggaran_all']['program'][$v_rhk_child['kode_cascading_program']]['total'] += $rencana_pagu;
				}

				if (!empty($v_rhk_child['kode_cascading_kegiatan'])) {
					if (empty($opsi['data_anggaran_all']['kegiatan'][$v_rhk_child['kode_cascading_kegiatan']])) {
						$opsi['data_anggaran_all']['kegiatan'][$v_rhk_child['kode_cascading_kegiatan']] = array(
							'ids' 			=> array(),
							'sumber_dana' 	=> array(),
							'total' 		=> 0,
							'data' 			=> array()
						);
					}
					$opsi['data_anggaran_all']['kegiatan'][$v_rhk_child['kode_cascading_kegiatan']]['data'][] = array(
						'nama'           => $v_rhk_child['label_cascading_kegiatan'],
						'kode'           => $v_rhk_child['kode_cascading_kegiatan'],
						'sumber_dana'    => implode(', ', $sumber_dana),
						'total_anggaran' => $rencana_pagu,
						'urut' 			 => $opsi['no_urut_rhk'],
						'id' 			 => $v_rhk_child['id']
					);
					$opsi['data_anggaran_all']['kegiatan'][$v_rhk_child['kode_cascading_kegiatan']]['ids'][] = $v_rhk_child['id'];
					$opsi['data_anggaran_all']['kegiatan'][$v_rhk_child['kode_cascading_kegiatan']]['total'] += $rencana_pagu;
				}

				if (!empty($v_rhk_child['kode_cascading_sub_kegiatan'])) {
					if (empty($opsi['data_anggaran_all']['sub_kegiatan'][$v_rhk_child['kode_cascading_sub_kegiatan']])) {
						$opsi['data_anggaran_all']['sub_kegiatan'][$v_rhk_child['kode_cascading_sub_kegiatan']] = array(
							'ids' 			=> array(),
							'sumber_dana' 	=> array(),
							'total' 		=> 0,
							'data' 			=> array()
						);
					}
					$opsi['data_anggaran_all']['sub_kegiatan'][$v_rhk_child['kode_cascading_sub_kegiatan']]['data'][] = array(
						'nama'           => $v_rhk_child['label_cascading_sub_kegiatan'],
						'kode'           => $v_rhk_child['kode_cascading_sub_kegiatan'],
						'sumber_dana'    => implode(', ', $sumber_dana),
						'total_anggaran' => $rencana_pagu,
						'urut' 			 => $opsi['no_urut_rhk'],
						'id' 			 => $v_rhk_child['id']
					);
					$opsi['data_anggaran_all']['sub_kegiatan'][$v_rhk_child['kode_cascading_sub_kegiatan']]['ids'][] = $v_rhk_child['id'];
					$opsi['data_anggaran_all']['sub_kegiatan'][$v_rhk_child['kode_cascading_sub_kegiatan']]['total'] += $rencana_pagu;
				}

				foreach ($sumber_dana as $sd) {
					if (
						!empty($v_rhk_child['kode_cascading_program'])
						&& !in_array($sd, $opsi['data_anggaran_all']['program'][$v_rhk_child['kode_cascading_program']]['sumber_dana'])
					) {
						$opsi['data_anggaran_all']['program'][$v_rhk_child['kode_cascading_program']]['sumber_dana'][$sd] = $sd;
					}
					if (
						!empty($v_rhk_child['kode_cascading_kegiatan'])
						&& !in_array($sd, $opsi['data_anggaran_all']['kegiatan'][$v_rhk_child['kode_cascading_kegiatan']]['sumber_dana'])
					) {
						$opsi['data_anggaran_all']['kegiatan'][$v_rhk_child['kode_cascading_kegiatan']]['sumber_dana'][$sd] = $sd;
					}
					if (
						!empty($v_rhk_child['kode_cascading_sub_kegiatan'])
						&& !in_array($sd, $opsi['data_anggaran_all']['sub_kegiatan'][$v_rhk_child['kode_cascading_sub_kegiatan']]['sumber_dana'])
					) {
						$opsi['data_anggaran_all']['sub_kegiatan'][$v_rhk_child['kode_cascading_sub_kegiatan']]['sumber_dana'][$sd] = $sd;
					}
				}
			}
		}

		// echo '<pre>'; print_r($data_rhk_child); print_r($opsi); echo '</pre>'; die();
		return $opsi;
	}

	function get_rhk_unik($data_ploting_rhk)
	{
		global $wpdb;
		$rhk_unik = array();
		foreach ($data_ploting_rhk as $v_rhk) {
			$id_unik = strtolower(trim($v_rhk['label']));
			if (empty($rhk_unik[$id_unik])) {
				$rhk_unik[$id_unik] = array(
					'ids' 		=> array(),
					'indikator' => array(),
					'data' 		=> array()
				);
			}
			$rhk_unik[$id_unik]['ids'][] = $v_rhk['id'];
			$rhk_unik[$id_unik]['data'][] = $v_rhk;

			$data_indikator_ploting_rhk = $wpdb->get_results(
				$wpdb->prepare("
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
					WHERE id_renaksi = %d 
					  AND active = 1
				", $v_rhk['id']),
				ARRAY_A
			);
			if (!empty($data_indikator_ploting_rhk)) {
				foreach ($data_indikator_ploting_rhk as $index => $v_indikator) {
					$id_unik_indikator = strtolower(trim($v_indikator['indikator']) . trim($v_indikator['target_akhir']) . trim($v_indikator['satuan']));
					if (empty($rhk_unik[$id_unik]['indikator'][$id_unik_indikator])) {
						$rhk_unik[$id_unik]['indikator'][$id_unik_indikator] = array(
							'ids' => array(),
							'data' => array()
						);
					}
					$rhk_unik[$id_unik]['indikator'][$id_unik_indikator]['ids'][] = $v_indikator['id'];
					$rhk_unik[$id_unik]['indikator'][$id_unik_indikator]['data'][] = $v_indikator;
				}
			}
		}
		return $rhk_unik;
	}

	function get_pk_html($options)
	{
		global $wpdb;

		$ret = array(
			'html_sasaran' 		=> '',
			'html_program' 		=> '',
			'html_kegiatan' 	=> '',
			'html_sub_kegiatan' => '',
			'rhk_unik' 			=> array()
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
				ORDER BY kode_cascading_sasaran, kode_cascading_program, id_sub_skpd_cascading, kode_cascading_kegiatan, kode_cascading_sub_kegiatan, level, id ASC
			", $id_skpd, $options['tahun'], $options['nip_baru'], $options['satker_id']),
			ARRAY_A
		);

		$data_anggaran = array(
			'sasaran'       => array(),
			'program'       => array(),
			'kegiatan'      => array(),
			'sub_kegiatan'  => array()
		);

		$data_anggaran_all = array(
			'program'       => array(),
			'kegiatan'      => array(),
			'sub_kegiatan'  => array()
		);
		$no_2 = 0;
		if (!empty($data_ploting_rhk)) {
			$ret['rhk_unik'] = $this->get_rhk_unik($data_ploting_rhk);

			foreach ($ret['rhk_unik'] as $v) {
				$v_rhk = $v['data'][0];
				$v_rhk['id'] = implode('|', $v['ids']);
				$data_indikator_ploting_rhk = array();
				foreach ($v['indikator'] as $vv) {
					$indikator = $vv['data'][0];
					$indikator['id'] = implode('|', $vv['ids']);
					$data_indikator_ploting_rhk[] = $indikator;
				}

				$html_indikator = '';
				$p_i = count($data_indikator_ploting_rhk);
				$no_2++;

				if (!empty($data_indikator_ploting_rhk)) {
					foreach ($data_indikator_ploting_rhk as $index => $v_indikator) {
						$html_indikator .= '<tr id-rhk="' . $v_rhk['id'] . '" id-indikator="' . $v_indikator['id'] . '">';

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
					$html_indikator .= '<tr id-rhk="' . $v_rhk['id'] . '">
						<td class="text-center">' . $no_2 . '</td>
						<td class="text-left">' . $v_rhk['label'] . '</td>
						<td></td>
						<td></td>
					</tr>';
				}

				$ret['html_sasaran'] .= $html_indikator;

				$anggaran = $this->get_rencana_pagu_rhk(array(
					'ids' 			=> $v['ids'],
					'level' 		=> $v_rhk['level'],
					'id_skpd' 		=> $id_skpd,
					'data_anggaran' => $data_anggaran,
					'data_anggaran_all' => $data_anggaran_all,
					'no_urut_rhk' 	=> $no_2
				));
				$data_anggaran = $anggaran['data_anggaran'];
				$data_anggaran_all = $anggaran['data_anggaran_all'];
			}

			// die(json_encode($data_anggaran_all));

			if (
				empty($options['format_halaman_kedua'])
				|| $options['format_halaman_kedua'] == 'gabungan'
			) {
				$cek_urut = 0;
				foreach ($data_anggaran as $jenis => $cascading) {
					foreach ($cascading as $multi_cascading) {

						$v = $multi_cascading['data'][0];
						if ($cek_urut != $v['urut']) {
							$cek_urut = $v['urut'];
							$no_cascading = 0;
						}

						$no_cascading++;
						if ($jenis == 'program') {
							$ret['html_program'] .= '<tr data-id="' . implode('|', $multi_cascading['ids']) . '">
								<td class="text-center">' . $v['urut'] . '.' . $no_cascading . '</td>
								<td class="text-left">' . $v['kode'] . ' ' . $v['nama'] . '</td>
								<td class="text-right">' . number_format($multi_cascading['total'], 0, ",", ".") . '</td>
								<td class="text-left">' . implode(', ', $multi_cascading['sumber_dana']) . '</td>
							</tr>';
						} else if ($jenis == 'kegiatan') {
							$ret['html_kegiatan'] .= '<tr data-id="' . implode('|', $multi_cascading['ids']) . '">
								<td class="text-center">' . $v['urut'] . '.' . $no_cascading . '</td>
								<td class="text-left">' . $v['kode'] . ' ' . $v['nama'] . '</td>
								<td class="text-right">' . number_format($multi_cascading['total'], 0, ",", ".") . '</td>
								<td class="text-left">' . implode(', ', $multi_cascading['sumber_dana']) . '</td>
							</tr>';
						} else if ($jenis == 'sub_kegiatan') {
							$parts = explode(" ", $v['nama'], 2);
							$ret['html_sub_kegiatan'] .= '<tr data-id="' . implode('|', $multi_cascading['ids']) . '">
								<td class="text-center">' . $v['urut'] . '.' . $no_cascading . '</td>
								<td class="text-left">' . $v['kode'] . ' ' . $parts[1] . '</td>
								<td class="text-right">' . number_format($multi_cascading['total'], 0, ",", ".") . '</td>
								<td class="text-left">' . implode(', ', $multi_cascading['sumber_dana']) . '</td>
							</tr>';
						}
					}
				}
			} else {
				$cek_urut = 0;
				foreach ($data_anggaran_all as $jenis => $cascading) {
					foreach ($cascading as $multi_cascading) {

						$v = $multi_cascading['data'][0];
						if ($cek_urut != $v['urut']) {
							$cek_urut = $v['urut'];
							$no_cascading = 0;
						}

						$no_cascading++;
						if (
							$jenis == 'program'
							&& $jenis == $options['format_halaman_kedua']
						) {
							$ret['html_program'] .= '<tr data-id="' . implode('|', $multi_cascading['ids']) . '">
								<td class="text-center">' . $v['urut'] . '.' . $no_cascading . '</td>
								<td class="text-left">' . $v['kode'] . ' ' . $v['nama'] . '</td>
								<td class="text-right">' . number_format($multi_cascading['total'], 0, ",", ".") . '</td>
								<td class="text-left">' . implode(', ', $multi_cascading['sumber_dana']) . '</td>
							</tr>';
						} else if (
							$jenis == 'kegiatan'
							&& $jenis == $options['format_halaman_kedua']
						) {
							$ret['html_kegiatan'] .= '<tr data-id="' . implode('|', $multi_cascading['ids']) . '">
								<td class="text-center">' . $v['urut'] . '.' . $no_cascading . '</td>
								<td class="text-left">' . $v['kode'] . ' ' . $v['nama'] . '</td>
								<td class="text-right">' . number_format($multi_cascading['total'], 0, ",", ".") . '</td>
								<td class="text-left">' . implode(', ', $multi_cascading['sumber_dana']) . '</td>
							</tr>';
						} else if (
							$jenis == 'sub_kegiatan'
							&& $jenis == $options['format_halaman_kedua']
						) {
							$parts = explode(" ", $v['nama'], 2);
							$ret['html_sub_kegiatan'] .= '<tr data-id="' . implode('|', $multi_cascading['ids']) . '">
								<td class="text-center">' . $v['urut'] . '.' . $no_cascading . '</td>
								<td class="text-left">' . $v['kode'] . ' ' . $parts[1] . '</td>
								<td class="text-right">' . number_format($multi_cascading['total'], 0, ",", ".") . '</td>
								<td class="text-left">' . implode(', ', $multi_cascading['sumber_dana']) . '</td>
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
						WHERE p.id=%d
						  AND p.active = 1
					', $_POST['id_pertama']),
					ARRAY_A
				);

				//jika ada status plt plh maka tambahkan
				if (!empty($pihak_pertama['custom_jabatan'])) {
					$jabatan_pertama = $pihak_pertama['custom_jabatan'];
				} else {
					$jabatan_pertama = trim($pihak_pertama['jabatan'] . ' ' . $pihak_pertama['nama_bidang']);
				}

				if (!empty($pihak_pertama['plt_plh']) && !empty($pihak_pertama['plt_plh_teks'])) {
					$jabatan_pertama = $pihak_pertama['plt_plh_teks'] . ' ' . $jabatan_pertama;
				}

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
				if (!empty($_POST['id_kedua'])) {
					//atasan ASN
					$pihak_kedua = $wpdb->get_row(
						$wpdb->prepare('
							SELECT 
								p.*,
								ds.nama AS nama_bidang
							FROM esakip_data_pegawai_simpeg p
							LEFT JOIN esakip_data_satker_simpeg ds
								   ON ds.satker_id = p.satker_id
							WHERE p.id=%d
							  AND p.active = 1
						', $_POST['id_kedua']),
						ARRAY_A
					);
					$data_atasan = $pihak_kedua;

					//gelar depan atau belakang tambahkan
					$data_atasan['nama_pegawai'] = $pihak_kedua['gelar_depan'] . ' ' . $pihak_kedua['nama_pegawai'] . ', ' . $pihak_kedua['gelar_belakang'];

					//jika ada status plt plh maka tambahkan
					if (!empty($pihak_kedua['custom_jabatan'])) {
						$jabatan_kedua = $pihak_kedua['custom_jabatan'];
					} else {
						$jabatan_kedua = trim($pihak_kedua['jabatan'] . ' ' . $pihak_kedua['nama_bidang']);
					}

					if (!empty($pihak_kedua['plt_plh']) && !empty($pihak_kedua['plt_plh_teks'])) {
						$jabatan_kedua = $pihak_kedua['plt_plh_teks'] . ' ' . $jabatan_kedua;
					}

					$data_atasan['jabatan'] = $jabatan_kedua;
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
				if (!empty($_POST['id_kedua'])) {
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
					$wpdb->prepare(
						"
	                	SELECT 
	                    	* 
	                    FROM esakip_finalisasi_tahap_iku_opd 
	                    WHERE id_skpd = %d 
		                     AND nama_tahapan = %s 
		                     AND tanggal_dokumen = %s 
		                     AND active = 1
		                ",
						$id_skpd,
						$nama_tahapan,
						$tanggal_dokumen
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
							'id_unik_indikator' => $data['id_unik_indikator'],
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
					foreach ($ret['data'] as $tahapan) {
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
						$html = '';
						$no = 0;
						if (!empty($data_finalisasi_iku)) {
							foreach ($data_finalisasi_iku as $v) {
								$no++;

								$indikator = $v['label_indikator'];

								if (strpos($indikator, ' - ') !== false) {
									$indikator_array = explode(' - ', $indikator);
									$indikator = '- ' . implode('<br/>- ', $indikator_array);
								} else if (strpos($indikator, "\n- ") !== false) {
									$indikator_array = explode("\n- ", $indikator);
									$indikator = implode("<br/>- ", $indikator_array);
								}

								$html .= '
						        <tr>
						            <td style="border: 1px solid black; text-align: center;">' . $no . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['label_sasaran'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $indikator . '</td>
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

	function edit_finalisasi_iku()
	{
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
			'debug' => array()
		);

		try {
			if (!get_option('_crb_url_api_ekinerja') || !get_option('_crb_api_key_ekinerja')) {
				throw new Exception("Pengaturan URL API E-Kinerja belum diisi!", 1);
			}

			if (!get_option('_crb_api_ekinerja_status')) {
				throw new Exception("Pengaturan Status API E-Kinerja ditutup!", 1);
			}

			if (get_option('_crb_input_renaksi') != 1) {
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

			if ($opsi_param['tipe'] == 'indikator') {
				if (empty($opsi_param['nip'])) {
					throw new Exception("NIP kosong!", 1);
				}

				if (empty($opsi_param['id_rhk']) || (is_array($opsi_param['id_rhk']) && count($opsi_param['id_rhk']) == 0)) {
					throw new Exception("Id Rencana Hasil Kerja kosong!", 1);
				}

				if (empty($opsi_param['id_indikator']) || (is_array($opsi_param['id_indikator']) && count($opsi_param['id_indikator']) == 0)) {
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

			if ($opsi_param['tipe'] == 'indikator') {
				$body_param['nip'] = $nip;
			}

			$_POST['debug'] = true;
			if (!empty($_POST['debug'])) {
				$startTimeOp1 = microtime(true);
			}

			$url_api = get_option('_crb_url_api_ekinerja') . 'api/kinerjarhk';
			$response = wp_remote_post($url_api, [
				'headers' => array(
					'X-api-key' => get_option('_crb_api_key_ekinerja'),
				),
				'body' => $body_param
			]);

			if (!empty($_POST['debug'])) {
				$endTimeOp1 = microtime(true);
				$durationOp1 = $endTimeOp1 - $startTimeOp1;
				$ret['debug'][] = "Operasi 1 (" . $url_api . ") selesai dalam " . number_format($durationOp1, 4) . " detik<br>";
			}

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

			if (!empty($_POST['debug'])) {
				$startTimeOp1 = microtime(true);
			}
			if ($data_ekin['status']) {
				if (!empty($data_ekin['data'])) {
					foreach ($data_ekin['data'] as $k_ekin => $v_ekin) {
						$nama_pegawai = !empty($v_ekin['nama']) ? $v_ekin['nama'] : '';
						$nip_pegawai = !empty($v_ekin['nip']) ? $v_ekin['nip'] : '';
						if (!empty($v_ekin['rencana_hasil_kerja'])) {
							foreach ($v_ekin['rencana_hasil_kerja'] as $k_rhk => $v_rhk) {

								$get_id_rhk_ekin = explode('|', $v_rhk['rhk_id']);
								
								$id_rhk_valid = null;
								foreach ($get_id_rhk_ekin as $id_for_rhk) {
									$id_for_rhk = trim($id_for_rhk);
									
									$cek_id_rhk = $wpdb->get_var(
										$wpdb->prepare("
										SELECT 
											id 
										FROM esakip_data_rencana_aksi_opd
										WHERE id = %d 
											AND tahun_anggaran = %d 
											AND id_skpd = %d 
											AND active = 1
										", $id_for_rhk, $tahun, $id_skpd)
									);
									
									if (!empty($cek_id_rhk)) {
										$id_rhk_valid = $id_for_rhk;
										break;
									}
								}
								
								// Skip jika tidak ada ID RHK yang ada
								if (empty($id_rhk_valid)) {
									continue;
								}
								
								if ($opsi_param['tipe'] == 'indikator') {
									$list_rhk = is_array($id_rhk) ? $id_rhk : array($id_rhk);
									if (!in_array($id_rhk_valid, $list_rhk)) {
										continue;
									}
								}
								
								if (!empty($v_rhk['indikator'])) {
									foreach ($v_rhk['indikator'] as $k_indikator => $v_indikator) {

										if ($opsi_param['tipe'] == 'indikator') {
											$list_indikator = is_array($id_indikator) ? $id_indikator : array($id_indikator);
											if (!in_array($v_indikator['indikator_rhk_id'], $list_indikator)) {
												continue;
											}
										}

										// Cek apakah indikator ada di ID RHK yang ada
										$cek_id_indikator = $wpdb->get_var(
											$wpdb->prepare("
											SELECT 
												id 
											FROM esakip_data_rencana_aksi_indikator_opd
											WHERE id=%d 
												AND id_renaksi=%d 
												AND tahun_anggaran=%d 
												AND id_skpd=%d 
												AND active=1
											", $v_indikator['indikator_rhk_id'], $id_rhk_valid, $tahun, $id_skpd)
										);
										
										// Jika indikator tidak ada di ID RHK yang ada, cek di ID RHK lainnya
										if (empty($cek_id_indikator)) {
											$skip_indikator = false;
											foreach ($get_id_rhk_ekin as $id_for_ind) {
												$id_for_ind = trim($id_for_ind);
												if ($id_for_ind == $id_rhk_valid) {
													continue;
												}
												
												// Cek apakah indikator ada di ID RHK lain
												$cek_indikator_lain = $wpdb->get_var(
													$wpdb->prepare("
													SELECT 
														id 
													FROM esakip_data_rencana_aksi_indikator_opd
													WHERE id=%d 
														AND id_renaksi=%d 
														AND tahun_anggaran=%d 
														AND id_skpd=%d 
														AND active=1
													", $v_indikator['indikator_rhk_id'], $id_for_ind, $tahun, $id_skpd)
												);
												
												// Jika ditemukan di ID RHK lain, skip indikator ini
												if (!empty($cek_indikator_lain)) {
													$skip_indikator = true;
													break;
												}
											}
											
											// Skip jika indikator sudah ada di ID RHK lain
											if ($skip_indikator) {
												foreach ($get_id_rhk_ekin as $id_rhk_nonaktif) {
													$id_rhk_nonaktif = trim($id_rhk_nonaktif);
													
													$wpdb->update(
														'esakip_data_rhk_individu',
														array('active' => 0),
														array(
															'id_rhk' => $id_rhk_nonaktif,
															'id_indikator_rhk' => $v_indikator['indikator_rhk_id'],
															'nip' => $nip_pegawai,
															'tahun_anggaran' => $tahun
														)
													);
												}
												continue;
											}
										}

										if (empty($cek_id_indikator)) {
										    $existing_data = $wpdb->get_row($wpdb->prepare("
										    	SELECT 
										    		id 
										    	FROM esakip_data_rhk_individu 
										        	WHERE id_rhk = %d 
										        	AND id_indikator_rhk = %d 
										        	AND nip = %s 
										        	AND tahun_anggaran = %d 
										        	AND active = 1", $id_rhk_valid, $v_indikator['indikator_rhk_id'], $nip_pegawai, $tahun
										    ));
										    
										    if (empty($existing_data)) {
										        $json_data = array(
										            'satuan' => !empty($v_indikator['satuan']) ? $v_indikator['satuan'] : null,
										            'aspek_rhk' => !empty($v_indikator['aspek_rhk']) ? $v_indikator['aspek_rhk'] : null,
										            'aspek_rhk_teks' => !empty($v_indikator['aspek_rhk_teks']) ? $v_indikator['aspek_rhk_teks'] : null,
										            'kinerja_bulan' => !empty($v_indikator['kinerja_bulan']) ? $v_indikator['kinerja_bulan'] : array(),
										            'kinerja_triwulan' => !empty($v_indikator['kinerja_triwulan']) ? $v_indikator['kinerja_triwulan'] : array(),
										            'kinerja_tahunan' => !empty($v_indikator['kinerja_tahunan']) ? $v_indikator['kinerja_tahunan'] : array()
										        );
										        
										        $data_rhk_individu = array(
										            'id_rhk' => $id_rhk_valid,
										            'id_indikator_rhk' => $v_indikator['indikator_rhk_id'],
										            'label_rhk' => !empty($v_rhk['rencana_hasil_kerja']) ? $v_rhk['rencana_hasil_kerja'] : null,
										            'label_indikator_rhk' => !empty($v_indikator['indikator']) ? $v_indikator['indikator'] : null,
										            'nama' => $nama_pegawai,
										            'nip' => $nip_pegawai,
										            'id_skpd' => $id_skpd,
										            'json' => json_encode($json_data),
										            'tahun_anggaran' => $tahun,
										            'active' => 1,
										            'created_at' => current_time('mysql')
										        );
										        
										        $wpdb->insert('esakip_data_rhk_individu', $data_rhk_individu);
										    }
										} else {
										    $wpdb->update(
										        'esakip_data_rhk_individu',
										        array('active' => 0),
										        array(
										            'id_rhk' => $id_rhk_valid,
										            'id_indikator_rhk' => $v_indikator['indikator_rhk_id'],
										            'nip' => $nip_pegawai,
										            'tahun_anggaran' => $tahun
										        )
										    );
										}

										if (!empty($v_indikator['kinerja_triwulan'])) {
											$data_triwulan = array();
											foreach ($v_indikator['kinerja_triwulan'] as $k_tw => $v_tw) {
												if (!empty($v_tw['kinerja'])) {
													$tw = intval(str_replace('tw', '', strtolower($v_tw['triwulan'])));
													$data_triwulan['realisasi_tw_' . $tw] = 0;
													if (!empty($v_tw['kinerja']['realisasi_kuantitas'])) {
														$data_triwulan['realisasi_tw_' . $tw] = $v_tw['kinerja']['realisasi_kuantitas'];
													}
													$data_triwulan['ket_tw_' . $tw] = $v_tw['kinerja']['catatan'];
												}
											}
											if (!empty($data_triwulan)) {

												$data_triwulan['update_at'] = current_time('mysql');

												if (!empty($cek_id_indikator)) {
													$wpdb->update(
														'esakip_data_rencana_aksi_indikator_opd',
														$data_triwulan,
														array('id' => $cek_id_indikator)
													);
												}
											}
										}

										if (!empty($v_indikator['kinerja_bulan'])) {
											foreach ($v_indikator['kinerja_bulan'] as $k_k_bulan => $v_k_bulan) {
												if (!empty($v_k_bulan['kinerja'])) {
													$nama_aspek = 'kuantitas';
													$volume_api = $rencana_aksi_api = $satuan_bulan_api = $realisasi_api = $keterangan_api = $capaian_api = array();
													foreach ($v_k_bulan['kinerja'] as $v_kinerja) {
														$volume_api[] = $v_kinerja['target_' . $nama_aspek];
														$rencana_aksi_api[] = $v_kinerja['kegiatan'];
														$satuan_bulan_api[] = $v_kinerja['satuan'];
														$realisasi_api[] = $v_kinerja['realisasi_' . $nama_aspek];
														$keterangan_api[] = $v_kinerja['catatan'];
														$capaian_api[] = $v_kinerja['capaian_' . $nama_aspek];
													}

													$data_option = array(
														'id_indikator_renaksi_opd' => $v_indikator['indikator_rhk_id'],
														'id_skpd' => $id_skpd,
														'bulan' => $v_k_bulan['bulan'],
														'volume' => serialize($volume_api),
														'rencana_aksi' => serialize($rencana_aksi_api),
														'satuan_bulan' => serialize($satuan_bulan_api),
														'realisasi' => serialize($realisasi_api),
														'keterangan' => serialize($keterangan_api),
														'capaian' => serialize($capaian_api),
														'tahun_anggaran' => $tahun,
														'active' => 1,
														'created_at' => current_time('mysql'),
													);

													// print_r($cek_id_indikator); die($wpdb->last_query);
													if (!empty($cek_id_indikator)) {
														$cek_id = $wpdb->get_var($wpdb->prepare("
															SELECT 
																id 
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
				} else {
					throw new Exception("Respone API Kosong!", 1);
				}
			} else {
				throw new Exception("Message: " . $data_ekin['message'], 1);
			}

			if (!empty($_POST['debug'])) {
				$endTimeOp1 = microtime(true);
				$durationOp1 = $endTimeOp1 - $startTimeOp1;
				$ret['debug'][] = "Operasi 2 (Cek dan Update DB RHK) selesai dalam " . number_format($durationOp1, 4) . " detik<br>";
			}
		} catch (Exception $e) {
			$ret = array(
				'status'  => false,
				'message' => $e->getMessage()
			);
		}
		return json_encode($ret);
	}

	public function get_capaian_realisasi_by_type(int $type, array $target, array $realisasi, int $tahun_anggaran)
	{
		$current_year = (int)date('Y');

		// Jika tahun anggaran sudah lewat, hitung semua 4 triwulan.
		// Jika tahun anggaran adalah tahun ini, hitung sampai triwulan saat ini.
		// Jika tahun anggaran di masa depan, capaian 0.
		if ($tahun_anggaran < $current_year) {
			$limit_quarter = 4;
		} elseif ($tahun_anggaran == $current_year) {
			$current_month = (int)date('n');
			$limit_quarter = (int)ceil($current_month / 3);
		} else {
			return 0.0; // Anggaran di masa depan, belum ada realisasi.
		}

		$penyebut = 0.0;
		$pembilang = 0.0;

		switch ($type) {
			case 1: // Indikator Tren Positif (Kumulatif)
				for ($i = 1; $i <= $limit_quarter; $i++) {
					$pembilang += empty($realisasi['realisasi_' . $i]) ? 0 : $realisasi['realisasi_' . $i];
					$penyebut += empty($target['target_' . $i]) ? 0 : $target['target_' . $i];
				}
				break;

			case 2: // Nilai Akhir (Mengambil nilai pada triwulan terakhir)
				$pembilang = empty($realisasi['realisasi_' . $limit_quarter]) ? 0 : $realisasi['realisasi_' . $limit_quarter];
				$penyebut = empty($target['target_' . $limit_quarter]) ? 0 : $target['target_' . $limit_quarter];
				break;

			case 3: // Indikator Tren Negatif (Kumulatif)
				for ($i = 1; $i <= $limit_quarter; $i++) {
					$pembilang += empty($target['target_' . $i]) ? 0 : $target['target_' . $i];
					$penyebut += empty($realisasi['realisasi_' . $i]) ? 0 : $realisasi['realisasi_' . $i];
				}
				break;

			default:
				return false; // unknown type
		}

		if ($penyebut == 0) {
			if ($type === 3) {
				// Jika Realisasi adalah 0, kita cek Targetnya ('pembilang').
				// Jika Target juga 0, maka capaian 100%. Jika tidak, capaian 0%.
				return ($pembilang === 0) ? 100.0 : 0.0;
			}
			return 0.0; // Menghindari pembagian dengan nol
		}

		$hasil = ($pembilang / $penyebut) * 100;

		return round($hasil, 2);
	}

	public function get_capaian_realisasi_tahunan_by_type(int $type, float $target_tahunan, array $realisasi, int $tahun_anggaran, ?int $triwulan = null)
	{
		$limit_quarter = 0;
		$targetUntukPerhitungan = 0.0;

		if ($triwulan !== null) {
			// KASUS 1: 'triwulan' DIISI -> Hitung spesifik untuk triwulan tersebut.

			if ($triwulan < 1 || $triwulan > 4) {
				return false;
			}

			$limit_quarter = $triwulan;
			$targetUntukPerhitungan = $target_tahunan;
		} else {
			// KASUS 2: 'triwulan' TIDAK DIISI -> Gunakan logika waktu (default).

			$current_year = (int)date('Y');
			if ($tahun_anggaran < $current_year) {
				$limit_quarter = 4;
			} elseif ($tahun_anggaran == $current_year) {
				$current_month = (int)date('n');
				$limit_quarter = (int)ceil($current_month / 3);
			} else {
				return 0.0; // capaian masa depan
			}

			$targetUntukPerhitungan = $target_tahunan;
		}

		$pembilang = 0.0;
		$penyebut = 0.0;

		$realisasi_kumulatif = 0.0;
		$realisasi_nilai_akhir = 0.0;
		for ($i = 1; $i <= $limit_quarter; $i++) {
			$realisasi_kumulatif += $realisasi['realisasi_' . $i] ?? 0;

			// nilai akhir diambil dari triwulan pertama dulu
			if (!empty($realisasi['realisasi_' . $i])) {
				$realisasi_nilai_akhir = $realisasi['realisasi_' . $i];
			}
		}

		switch ($type) {
			case 1: // tren positif
				$pembilang = $realisasi_kumulatif;
				$penyebut = $targetUntukPerhitungan;
				break;
			case 2: // nilai akhir
				$pembilang = $realisasi_nilai_akhir ?? 0;
				$penyebut = $targetUntukPerhitungan;
				break;
			case 3: // tren negatif
				$pembilang = $targetUntukPerhitungan;
				$penyebut = $realisasi_kumulatif;
				break;
			default:
				return false;
		}

		if ($penyebut == 0) {
			if ($type === 3) {
				return ($pembilang == 0) ? 100.0 : 0.0;
			}
			return 0.0;
		}

		$hasil = 0.0;
		if ($type === 3) {
			$hasil = (($pembilang - $penyebut) / $pembilang) * 100;
		} else {
			$hasil = ($pembilang / $penyebut) * 100;
		}

		return round($hasil, 2);
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
					$wpdb->prepare(
						"
	                	SELECT 
	                    	* 
	                    FROM esakip_finalisasi_tahap_iku_pemda 
	                    WHERE nama_tahapan = %s 
		                     AND tanggal_dokumen = %s 
		                     AND active = 1
		                ",
						$nama_tahapan,
						$tanggal_dokumen
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

						$formulasi = isset($data['formulasi']) ? stripslashes($data['formulasi']) : '';
						$wpdb->insert('esakip_finalisasi_iku_pemda', array(
							'id_tahap'         => $id_tahap,
							'id_sasaran_murni'     => $data['id_sasaran_murni'],
							'kode_sasaran'     => $data['kode_sasaran'],
							'label_sasaran'    => $data['label_sasaran'],
							'id_unik_indikator' => $data['id_unik_indikator'],
							'label_indikator'  => $data['label_indikator'],
							'formulasi'        => $formulasi,
							'sumber_data'      => $data['sumber_data'],
							'penanggung_jawab' => $data['penanggung_jawab'],
							'id_jadwal' => $data['id_jadwal'],
							'satuan' => $data['satuan'],
							'target_1' => $data['target_1'],
							'target_2' => $data['target_2'],
							'target_3' => $data['target_3'],
							'target_4' => $data['target_4'],
							'target_5' => $data['target_5'],
							'realisasi_1' => $data['realisasi_1'],
							'realisasi_2' => $data['realisasi_2'],
							'realisasi_3' => $data['realisasi_3'],
							'realisasi_4' => $data['realisasi_4'],
							'realisasi_5' => $data['realisasi_5'],
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
			'data' => ''
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
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

				$periode = $wpdb->get_row($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_data_jadwal
					WHERE id = %d 
						AND status = 1
				", $_POST['id_jadwal']), ARRAY_A);

				if (!$periode) {
					$ret['status'] = 'error';
					$ret['message'] = 'Data jadwal tidak ditemukan!';
					die(json_encode($ret));
				}

				$id_jadwal_murni = $periode['id_jadwal_murni'];
				$lama_pelaksanaan = $periode['lama_pelaksanaan'];

				foreach ($ret['data'] as $tahapan) {
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

					$html = '';
					$no = 0;

					if (!empty($data_finalisasi_iku_pemda)) {
						$group = [];
						foreach ($data_finalisasi_iku_pemda as $row) {
							$key = $row['id_sasaran_murni'];
							if (!isset($group[$key])) {
								$group[$key] = 0;
							}
							$group[$key]++;
						}

						$id_sasaran_murni = [];

						foreach ($data_finalisasi_iku_pemda as $v) {
							$no++;
							$html .= '<tr>';
							$html .= '<td style="border:1px solid black;">' . $no . '</td>';

							if (!empty($id_jadwal_murni)) {
								$id_sasaran = $v['id_sasaran_murni'];
								if ($id_sasaran != 0 && !in_array($id_sasaran, $id_sasaran_murni)) {
									$sasaran_existing = $wpdb->get_row($wpdb->prepare("
										SELECT 
											* 
										FROM esakip_rpd_sasaran
										WHERE id=%d AND active=1
									", $id_sasaran), ARRAY_A);

									$sasaran_teks = $sasaran_existing ? $sasaran_existing['sasaran_teks'] : '-';
									$rowspan = $group[$id_sasaran];
									$html .= '<td style="border:1px solid black;" rowspan="' . $rowspan . '">' . $sasaran_teks . '</td>';

									$id_sasaran_murni[] = $id_sasaran;
								} elseif ($id_sasaran == 0) {
									$html .= '<td style="border:1px solid black;">-</td>';
								}
							}

							$html .= '<td style="border:1px solid black;">' . $v['label_sasaran'] . '</td>';
							$html .= '<td style="border:1px solid black;">' . $v['label_indikator'] . '</td>';
							$html .= '<td style="border:1px solid black;">' . wp_kses_post($v['formulasi']) . '</td>';
							$html .= '<td style="border:1px solid black;">' . $v['sumber_data'] . '</td>';
							$html .= '<td style="border:1px solid black;">' . $v['penanggung_jawab'] . '</td>';
							$html .= '<td style="border:1px solid black;">' . $v['satuan'] . '</td>';

							for ($i = 1; $i <= $lama_pelaksanaan; $i++) {
								$key_target = 'target_' . $i;
								$target = !empty($v[$key_target]) ? $v[$key_target] : 0;
								$key_realisasi = 'realisasi_' . $i;
								$realisasi = !empty($v[$key_realisasi]) ? $v[$key_realisasi] : 0;
								$html .= '<td style="border:1px solid black;">' . $target . '</td>';
								$html .= '<td style="border:1px solid black;">' . $realisasi . '</td>';
							}

							$html .= '</tr>';
						}
					}

					if (empty($html)) {
						$colspan = 8 + $lama_pelaksanaan;
						$html = '<tr><td class="text-center" colspan="' . $colspan . '">Data masih kosong!</td></tr>';
					}

					$ret['nama_tahapan'] = $tahapan['nama_tahapan'];
					$ret['tanggal_dokumen'] = $tahapan['tanggal_dokumen'];
					$ret['data'] = $html;
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

	function edit_finalisasi_iku_pemda()
	{
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

				if ($ret['status'] == 'success') {
					$opsi_param = array(
						'tahun' => $tahun,
						'satker_id' => $satker_id,
						'id_skpd' => $id_skpd,
						'tipe' => $tipe
					);

					$data_ekin = $this->get_data_perbulan_ekinerja($opsi_param);
					$data_ekin_terbaru = json_decode($data_ekin, true);
					$ret['message'] = $data_ekin_terbaru['message'];
					$ret['debug'] = $data_ekin_terbaru['debug'];
					if (!empty($data_ekin_terbaru['is_error']) && $data_ekin_terbaru['is_error']) {
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

	function get_data_pokin_rhk($id_rhk, $level_rhk, $type_rhk = 'opd', $pokin_level_1 = false)
	{
		global $wpdb;

		if (!empty($id_rhk) && !empty($level_rhk)) {
			$level_pokin = $pokin_level_1 ? $level_rhk : $level_rhk + 1;

			if ($type_rhk === 'opd') {
				$params = '';

				if ($level_rhk == 1) {
					$params = "
						AND o.level_rhk_opd = 1
						AND (
							o.level_pokin = 1
							OR o.level_pokin = 2
						)
					";
				} elseif ($level_rhk == 2) {
					$params = "
						AND o.level_rhk_opd = 2
						AND (
							o.level_pokin = 3
							OR o.level_pokin = 4
							OR o.level_pokin = 5
						)
					";
				} elseif ($level_rhk == 3) {
					$params = "
						AND o.level_rhk_opd = 3
						AND (
							o.level_pokin = 4
							OR o.level_pokin = 5
						)
					";
				} elseif ($level_rhk == 4) {
					$params = "
						AND o.level_rhk_opd = 4
						AND o.level_pokin = 5
					";
				} else {
					die('level tidak valid');
				}

				$sql = "
					SELECT
						o.id_pokin,
						p.label AS pokin_label,
						p.level
					FROM esakip_data_pokin_rhk_opd AS o
					INNER JOIN esakip_pohon_kinerja_opd AS p 
							ON o.id_pokin = p.id
						   AND o.level_pokin = p.level
					WHERE o.id_rhk_opd = %d
					$params
					  AND o.active = 1
					  AND p.active = 1
				";

				$data_pokin = $wpdb->get_results(
					$wpdb->prepare($sql, $id_rhk),
					ARRAY_A
				);

				$datas = array();

				if (!empty($data_pokin)) {
					foreach ($data_pokin as $row) {
						$level = $row['level'];
						$label = $row['pokin_label'];

						if (!isset($datas[$level])) {
							$datas[$level] = [];
						}

						$datas[$level][] = $label;
					}
				}
			} elseif ($type_rhk === 'pemda') {
				$sql = "
					SELECT
						o.id_pokin,
						p.label AS pokin_label
					FROM esakip_data_pokin_rhk_pemda AS o
					INNER JOIN esakip_pohon_kinerja AS p 
							ON o.id_pokin = p.id
						   AND o.level_pokin = p.level
					WHERE o.id_rhk_pemda = %d
					  AND o.level_rhk_pemda = %d
					  AND o.level_pokin = %d
					  AND o.active = 1
					  AND p.active = 1
				";

				$data_pokin = $wpdb->get_results(
					$wpdb->prepare($sql, $id_rhk, $level_rhk, $level_pokin),
					ARRAY_A
				);

				$datas = '';
				if (!empty($data_pokin)) {
					$datas = '<ul class="m-0">';
					foreach ($data_pokin as $v_label_pokin) {
						$datas .= '<li>' . $v_label_pokin['pokin_label'] . '</li>';
					}
					$datas .= '</ul>';
				}
			}
		}

		return $datas;
	}

	function get_table_laporan_rencana_aksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data rencana aksi!',
			'data'  => ''
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID OPD tidak boleh kosong!';
				} elseif (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				} elseif (empty($_POST['nip'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'NIP kosong!';
				} elseif (empty($_POST['satker_id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Satker ID kosong!';
				} elseif (empty($_POST['level'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Level kosong!';
				}

				if ($ret['status'] != 'error') {
					$data = $wpdb->get_results(
						$wpdb->prepare("
						SELECT 
							* 
						FROM esakip_data_rencana_aksi_opd
						WHERE id_skpd = %d
						  AND tahun_anggaran = %d
						  AND nip = %s
						  AND satker_id = %s
						  AND level = %d
						  AND active = 1
					", $_POST['id_skpd'], $_POST['tahun_anggaran'], $_POST['nip'], $_POST['satker_id'], $_POST['level']),
						ARRAY_A
					);

					foreach ($data as $key => $val) {
						$id = $val['id'];
						$level = $val['level'];

						$data[$key]['indikator'] = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_data_rencana_aksi_indikator_opd
							WHERE id_renaksi = %d 
								AND active = 1
						", $id));

						// Ambil level 3 jika level 4
						if ($level >= 4) {
							$parent_id_3 = $wpdb->get_var($wpdb->prepare("
								SELECT 
									parent 
								FROM esakip_data_rencana_aksi_opd
								WHERE id = %d
							", $id));

							if ($parent_id_3) {
								$parent_3 = $wpdb->get_row($wpdb->prepare("
										SELECT 
											* 
										FROM esakip_data_rencana_aksi_opd
										WHERE id = %d AND level = 3 
											AND active = 1
								", $parent_id_3));
								if ($parent_3) {
									$data[$key]['parent_level_3'] = $parent_3;
									$data[$key]['parent_level_3_indikator'] = $wpdb->get_results($wpdb->prepare("
										SELECT 
											* 
										FROM esakip_data_rencana_aksi_indikator_opd
										WHERE id_renaksi = %d 
											AND active = 1
									", $parent_3->id));
									$id = $parent_3->id;
								}
							}
						}

						// Ambil level 2 jika level 3
						if ($level >= 3 && $id) {
							$parent_id_2 = $wpdb->get_var($wpdb->prepare("
								SELECT 
									parent 
								FROM esakip_data_rencana_aksi_opd
								WHERE id = %d
							", $id));

							if ($parent_id_2) {
								$parent_2 = $wpdb->get_row($wpdb->prepare("
									SELECT 
										* 
									FROM esakip_data_rencana_aksi_opd
									WHERE id = %d 
										AND level = 2 
										AND active = 1
								", $parent_id_2));
								if ($parent_2) {
									$data[$key]['parent_level_2'] = $parent_2;
									$data[$key]['parent_level_2_indikator'] = $wpdb->get_results($wpdb->prepare("
										SELECT 
											* 
										FROM esakip_data_rencana_aksi_indikator_opd
										WHERE id_renaksi = %d 
											AND active = 1
									", $parent_2->id));
									$id = $parent_2->id;
								}
							}
						}

						// Ambil level 1 jika level 2
						if ($level >= 2 && $id) {
							$parent_id_1 = $wpdb->get_var($wpdb->prepare("
								SELECT 
									parent 
								FROM esakip_data_rencana_aksi_opd
								WHERE id = %d
							", $id));

							if ($parent_id_1) {
								$parent_1 = $wpdb->get_row($wpdb->prepare("
									SELECT 
										* 
									FROM esakip_data_rencana_aksi_opd
									WHERE id = %d 
										AND level = 1 
										AND active = 1
								", $parent_id_1));
								if ($parent_1) {
									$data[$key]['parent_level_1'] = $parent_1;
									$data[$key]['parent_level_1_indikator'] = $wpdb->get_results($wpdb->prepare("
										SELECT 
											* 
										FROM esakip_data_rencana_aksi_indikator_opd
										WHERE id_renaksi = %d 
											AND active = 1
									", $parent_1->id));
									$id = $parent_1->id;
								}
							}
						}
					}
					$html = '';
					$no = 0;

					foreach ($data_all['data'] as $level1) {
						$no++;
						$no_renaksi = 0;

						$indikator_html = [];
						if (!empty($level1['indikator'])) {
							foreach ($level1['indikator'] as $ind) {
								$indikator_html[] = $ind['label'] ?? '';
							}
						}
						$indikator_html = implode('<br>', $indikator_html);

						$html .= '
					    <tr class="keg-utama">
					        <td>' . $no . '</td>
					        <td class="kiri kanan bawah text_blok kegiatan_utama">' . ($level1['detail']['label'] ?? '') . '<br>' . $indikator_html . '</td>
					    </tr>
					    ';

						if (!empty($level1['data'])) {
							foreach ($level1['data'] as $level2) {
								$no_renaksi++;
								$no_uraian_renaksi = 0;

								$indikator_html = [];
								if (!empty($level2['indikator'])) {
									foreach ($level2['indikator'] as $ind) {
										$indikator_html[] = $ind['label'] ?? '';
									}
								}
								$indikator_html = implode('<br>', $indikator_html);

								$html .= '
					            <tr class="re-naksi">
					                <td>' . $no . '.' . $no_renaksi . '</td>
					                <td class="kiri kanan bawah text_blok recana_aksi">' . ($level2['detail']['label'] ?? '') . '<br>' . $indikator_html . '</td>
					            </tr>
					            ';

								if (!empty($level2['data'])) {
									foreach ($level2['data'] as $level3) {
										$no_uraian_renaksi++;
										$no_uraian_teknis = 0;

										$indikator_html = [];
										if (!empty($level3['indikator'])) {
											foreach ($level3['indikator'] as $ind) {
												$indikator_html[] = $ind['label'] ?? '';
											}
										}
										$indikator_html = implode('<br>', $indikator_html);

										$html .= '
					                    <tr class="ur-kegiatan">
					                        <td>' . $no . '.' . $no_renaksi . '.' . $no_uraian_renaksi . '</td>
					                        <td class="urian_renaksi">' . ($level3['detail']['label'] ?? '') . '<br>' . $indikator_html . '</td>
					                    </tr>
					                    ';

										if (!empty($level3['data'])) {
											foreach ($level3['data'] as $level4) {
												$no_uraian_teknis++;

												$indikator_html = [];
												if (!empty($level4['indikator'])) {
													foreach ($level4['indikator'] as $ind) {
														$indikator_html[] = $ind['label'] ?? '';
													}
												}
												$indikator_html = implode('<br>', $indikator_html);

												$html .= '
					                            <tr class="urt-kegiatan">
					                                <td>' . $no . '.' . $no_renaksi . '.' . $no_uraian_renaksi . '.' . $no_uraian_teknis . '</td>
					                                <td class="uraian_teknis_kegiatan">' . ($level4['detail']['label'] ?? '') . '<br>' . $indikator_html . '</td>
					                                <td></td>
					                            </tr>
					                            ';
											}
										}
									}
								}
							}
						}
					}

					if (empty($html)) {
						$html = '<tr><td class="text-center" colspan="18">Data masih kosong!</td></tr>';
					}
					$ret['data'] = $html;
					die(json_encode([
						'status' => true,
						'data' => $data,
						'message' => 'Berhasil ambil data rencana aksi!'
					]));
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

	function submit_data_pk_pemda()
	{
		try {
			$this->functions->validate($_POST, [
				'api_key'               => 'required|string',
				'id_jadwal'             => 'required|numeric',
				'tahun_anggaran'        => 'required|numeric',
				'data'        			=> 'required',
			]);

			if ($_POST['api_key'] !== get_option(ESAKIP_APIKEY)) {
				throw new Exception("API key tidak valid!", 401);
			}

			$all_data = $_POST['data'];

			$post_data = [
				'id_jadwal'             => (int) $_POST['id_jadwal'],
				'tahun_anggaran'        => (int) $_POST['tahun_anggaran'],

				'id_data'               => $all_data['id_data'] ?? null,
				'id_iku'                => $all_data['id_iku'] ?? null,
				'rumus_capaian_kinerja' => (int) $all_data['rumus_capaian_kinerja'] ?? 1, // Default 1 (Positif)
				'is_target_teks'        => (int) ($all_data['is_target_teks'] == 1) ? 1 : 2, // Default 2 (False)
				'is_hidden'             => (int) ($all_data['is_hidden'] ?? 2), // Default 2 (False/Tertampil)
				'pagu'                  => $all_data['pagu'] ?? null,

				'label_sasaran'         => $all_data['label_sasaran'] ?? null,
				'label_indikator'       => $all_data['label_indikator'] ?? null,
				'satuan'                => $all_data['satuan'] ?? null,
				'target'                => $all_data['target'] ?? null,
				'realisasi_1'           => isset($all_data['realisasi_1']) && $all_data['realisasi_1'] !== '' ? $all_data['realisasi_1'] : null,
				'realisasi_2'           => isset($all_data['realisasi_2']) && $all_data['realisasi_2'] !== '' ? $all_data['realisasi_2'] : null,
				'realisasi_3'           => isset($all_data['realisasi_3']) && $all_data['realisasi_3'] !== '' ? $all_data['realisasi_3'] : null,
				'realisasi_4'           => isset($all_data['realisasi_4']) && $all_data['realisasi_4'] !== '' ? $all_data['realisasi_4'] : null,
				'target_teks'           => $all_data['target_teks'] ?? null,
				'realisasi_teks_1'      => $all_data['realisasi_1_teks'] ?? null,
				'realisasi_teks_2'      => $all_data['realisasi_2_teks'] ?? null,
				'realisasi_teks_3'      => $all_data['realisasi_3_teks'] ?? null,
				'realisasi_teks_4'      => $all_data['realisasi_4_teks'] ?? null,
			];

			$result = $this->upsert_pk_pemda($post_data);

			$message = $result['action'] === 'inserted' ? 'Data berhasil ditambahkan.' : 'Data berhasil diperbarui.';
			echo json_encode([
				'status'  => true,
				'message' => $message,
				'data'    => [
					'id'     => $result['id'],
					'action' => $result['action'],
					'data' => $post_data
				]
			]);
		} catch (Exception $e) {
			$code = is_int($e->getCode()) && $e->getCode() !== 0 ? $e->getCode() : 500;
			http_response_code($code);
			echo json_encode(['status'  => false, 'message' => $e->getMessage()]);
		}
		wp_die();
	}

	function upsert_pk_pemda(array $data)
	{
		global $wpdb;
		$table_name = 'esakip_laporan_pk_pemda';

		$db_data = [
			'id_iku'                => !empty($data['id_iku']) ? (int) $data['id_iku'] : null,
			'id_jadwal'             => $data['id_jadwal'],
			'tahun_anggaran'        => $data['tahun_anggaran'],
			'rumus_capaian_kinerja' => $data['rumus_capaian_kinerja'],
			'is_target_teks'        => $data['is_target_teks'],
			'is_hidden'             => $data['is_hidden'],
			'pagu'                  => !empty($data['pagu']) ? (float) $data['pagu'] : null,
		];

		// Jika BUKAN dari IKU (id_iku kosong), maka izinkan insert/update kolom-kolom ini.
		if (empty($data['id_iku'])) {
			$db_data['label_sasaran']   = $data['label_sasaran'];
			$db_data['label_indikator'] = $data['label_indikator'];
			$db_data['satuan']          = $data['satuan'];
		}

		$db_data['target']      = !empty($data['target']) ? (float) $data['target'] : null;
		$db_data['realisasi_1'] = $data['realisasi_1'];
		$db_data['realisasi_2'] = $data['realisasi_2'];
		$db_data['realisasi_3'] = $data['realisasi_3'];
		$db_data['realisasi_4'] = $data['realisasi_4'];
		if ($data['is_target_teks'] == 1) {
			$db_data['target_teks']      = $data['target_teks'];
			$db_data['realisasi_teks_1'] = $data['realisasi_teks_1'];
			$db_data['realisasi_teks_2'] = $data['realisasi_teks_2'];
			$db_data['realisasi_teks_3'] = $data['realisasi_teks_3'];
			$db_data['realisasi_teks_4'] = $data['realisasi_teks_4'];
		} else {
			$db_data['target_teks']      = null;
			$db_data['realisasi_teks_1'] = null;
			$db_data['realisasi_teks_2'] = null;
			$db_data['realisasi_teks_3'] = null;
			$db_data['realisasi_teks_4'] = null;
		}

		if (!empty($data['id_data'])) {
			$where = ['id' => (int) $data['id_data']];
			$result = $wpdb->update($table_name, $db_data, $where);

			if (false === $result) {
				throw new Exception("Gagal memperbarui data di database.", 500);
			}
			return [
				'success' => true,
				'id' => (int) $data['id_data'],
				'action' => 'updated'
			];
		} else {
			$result = $wpdb->insert($table_name, $db_data);

			if (false === $result) {
				throw new Exception("Gagal menambahkan data baru ke database.", 500);
			}
			return [
				'success' => true,
				'id' => $wpdb->insert_id,
				'action' => 'inserted'
			];
		}
	}

	function get_table_pk_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran Tidak Boleh Kosong!';
				} else if (empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Jadwal Tidak Boleh Kosong!';
				}

				if ($ret['status'] != 'error') {
					$tahun_anggaran = $_POST['tahun_anggaran'];
					$id_jadwal = $_POST['id_jadwal'];
					$detail_jadwal = $wpdb->get_row(
						$wpdb->prepare('
							SELECT 
								* 
							FROM esakip_data_jadwal 
							WHERE id_jadwal=%d
						', $id_jadwal),
						ARRAY_A
					);

					$get_data_iku = $wpdb->get_results(
						$wpdb->prepare('
							SELECT 
								* 
							FROM esakip_data_iku_pemda 
							WHERE id_jadwal=%d
							  AND active = 1
						', $id_jadwal),
						ARRAY_A
					);

					if (!empty($get_data_iku)) {
						foreach ($get_data_iku as $iku) {
							$id_iku = $iku['id'];
							$tahun_rpjm = $tahun_anggaran - $detail_jadwal['tahun_amggaran'];
							if ($tahun_rpjm <= 0) {
								$tahun_rpjm = 0;
							} else if ($tahun_rpjm >= 4) {
								$tahun_rpjm = 4;
							}
							$key_target = 'target_' . ($tahun_rpjm + 1);

							$get_data_pk = $wpdb->get_row(
								$wpdb->prepare("
									SELECT 
										* 
									FROM esakip_laporan_pk_pemda 
									WHERE id_iku = %d
									  AND tahun_anggaran = %d
								", $id_iku, $tahun_anggaran),
								ARRAY_A
							);

							if (!empty($get_data_pk)) {
								$update_data = array();
								if ($get_data_pk['active'] != $iku['active']) {
									$update_data['active'] = $iku['active'];
								}
								if ($get_data_pk['satuan'] != $iku['satuan']) {
									$update_data['satuan'] = $iku['satuan'];
								}
								if ($get_data_pk['target'] != $iku[$key_target]) {
									$update_data['target'] = $iku[$key_target];
								}

								if (!empty($update_data)) {
									$wpdb->update(
										'esakip_laporan_pk_pemda',
										$update_data,
										array('id_iku' => $id_iku)
									);
								}
								// Insert baru jika belum ada
							} else {
								$data = array(
									'id_iku' => $id_iku,
									'satuan' => $iku['satuan'],
									'target' => $iku[$key_target],
									'id_jadwal' => $id_jadwal,
									'active' => $iku['active'],
									'tahun_anggaran' => $tahun_anggaran
								);
								$wpdb->insert('esakip_laporan_pk_pemda', $data);
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

	function simpan_finalisasi_pk_pemda_ajax()
	{
		try {
			$this->functions->validate($_POST, [
				'api_key'       	=> 'required|string',
				'id_jadwal'      	=> 'required|numeric',
				'tahun_anggaran'    => 'required|numeric',
			]);

			if ($_POST['api_key'] !== get_option(ESAKIP_APIKEY)) {
				throw new Exception("API key tidak valid!", 401);
			}

			$data_pk = $_POST['data_pk'];
			if (empty($data_pk['nama_tahapan']) || empty($data_pk['tanggal_dokumen'])) {
				throw new Exception("Nama tahapan dan tanggal dokumen wajib diisi!", 400);
			}

			$data_simpan = $_POST['data_simpan'];

			$id_tahap_baru = $this->simpan_finalisasi_pk_pemda($data_pk, $data_simpan, $_POST['id_jadwal'], $_POST['tahun_anggaran']);

			echo json_encode([
				'status'  => true,
				'message' => 'Berhasil finalisasi PK!',
				'data'    => [
					'id_tahap' => $id_tahap_baru
				]
			]);
		} catch (Exception $e) {
			$code = is_int($e->getCode()) && $e->getCode() !== 0 ? $e->getCode() : 500;
			http_response_code($code);
			echo json_encode(['status'  => false, 'message' => $e->getMessage()]);
		}
		wp_die();
	}

	function simpan_finalisasi_pk_pemda(array $data_pk, array $data_simpan, int $id_jadwal, int $tahun_anggaran)
	{
		global $wpdb;

		$nama_tahapan = $data_pk['nama_tahapan'];
		$tanggal_dokumen = $data_pk['tanggal_dokumen'];

		$table_tahap = 'esakip_finalisasi_tahap_pk_pemda';
		$table_detail = 'esakip_finalisasi_pk_pemda';

		$cek = $wpdb->get_var($wpdb->prepare(
			"SELECT id FROM {$table_tahap} WHERE nama_tahapan = %s AND tanggal_dokumen = %s AND active = 1 AND id_jadwal = %d AND tahun_anggaran = %d",
			$nama_tahapan,
			$tanggal_dokumen,
			$id_jadwal,
			$tahun_anggaran
		));

		if ($cek) {
			throw new Exception('Nama Tahapan dan Dokumen pada tanggal tersebut sudah ada!', 409);
		}

		$wpdb->query('START TRANSACTION');

		try {
			$tahap_data = [
				'nama_tahapan'    => $nama_tahapan,
				'tanggal_dokumen' => $tanggal_dokumen,
				'id_jadwal'       => $id_jadwal,
				'tahun_anggaran'  => $tahun_anggaran,
			];

			$wpdb->insert($table_tahap, $tahap_data);
			$id_tahap = $wpdb->insert_id;

			if (!$id_tahap) {
				throw new Exception('Gagal menyimpan data tahap finalisasi.', 500);
			}

			foreach ($data_simpan as $data) {
				// Validasi data
				if ($data['is_hidden' == 1]) {
					continue; // Lewati data yang disembunyikan
				}

				$target = $data['target'];
				$realisasi_1 = $data['realisasi_1'];
				$realisasi_2 = $data['realisasi_2'];
				$realisasi_3 = $data['realisasi_3'];
				$realisasi_4 = $data['realisasi_4'];

				if ($data['is_target_teks'] == 1) {
					$target_teks = $data['target_teks'];
					$realisasi_1_teks = $data['realisasi_1_teks'];
					$realisasi_2_teks = $data['realisasi_2_teks'];
					$realisasi_3_teks = $data['realisasi_3_teks'];
					$realisasi_4_teks = $data['realisasi_4_teks'];
				} else {
					$target_teks = null;
					$realisasi_1_teks = null;
					$realisasi_2_teks = null;
					$realisasi_3_teks = null;
					$realisasi_4_teks = null;
				}

				if (!empty($data['id_iku'])) {
					$label_sasaran = $data['ik_label_sasaran'];
					$label_indikator = $data['ik_label_indikator'];
					$satuan = $data['ik_satuan'];
				} else {
					$label_sasaran = $data['label_sasaran'];
					$label_indikator = $data['label_indikator'];
					$satuan = $data['satuan'];
				}

				$detail_data = [
					'id_tahap'        => $id_tahap,
					'label_sasaran'   => $label_sasaran,
					'label_indikator' => $label_indikator,
					'satuan'          => $satuan,
					'is_target_teks'  => $data['is_target_teks'],
					'target'          => $target,
					'realisasi_1'    => $realisasi_1,
					'realisasi_2'    => $realisasi_2,
					'realisasi_3'    => $realisasi_3,
					'realisasi_4'    => $realisasi_4,
					'target_teks'    => $target_teks,
					'realisasi_1_teks'    => $realisasi_1_teks,
					'realisasi_2_teks'    => $realisasi_2_teks,
					'realisasi_3_teks'    => $realisasi_3_teks,
					'realisasi_4_teks'    => $realisasi_4_teks,
					'pagu'            => $data['pagu'],
					'id_jadwal'       => $id_jadwal,
					'tahun_anggaran'  => $tahun_anggaran,
				];

				$result = $wpdb->insert($table_detail, $detail_data);

				if (!$result) {
					throw new Exception('Gagal menyimpan salah satu data detail PK.', 500);
				}
			}

			$wpdb->query('COMMIT');

			return $id_tahap;
		} catch (Exception $e) {
			$wpdb->query('ROLLBACK');
			throw $e;
		}
	}

	function hapus_finalisasi_pk_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus finalisasi PK!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$cek_id = $wpdb->get_var(
					$wpdb->prepare('
						SELECT 
							id
						FROM esakip_finalisasi_tahap_pk_pemda
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
					'esakip_finalisasi_tahap_pk_pemda',
					array('active' => 0),
					array(
						'id' => $cek_id,
						'active' => 1
					)
				);
				$wpdb->update(
					'esakip_finalisasi_pk_pemda',
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

	function get_finalisasi_pk_pemda_by_id()
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
					        FROM esakip_finalisasi_tahap_pk_pemda
					        WHERE id = %d
					          AND active = 1
					    ", $_POST['id_tahap']),
						ARRAY_A
					);
					// print_r($data_finalisasi_pk_pemda); die($wpdb->last_query);
					foreach ($ret['data'] as $tahapan) {
						$data_finalisasi_pk_pemda = $wpdb->get_results(
							$wpdb->prepare("
						        SELECT 
						            *
						        FROM esakip_finalisasi_pk_pemda
						        WHERE id_tahap = %d
						          AND active = 1
						    ", $tahapan['id']),
							ARRAY_A
						);
						// print_r($data_finalisasi_pk_pemda); die($wpdb->last_query);
						$html = '';
						$no = 0;
						if (!empty($data_finalisasi_pk_pemda)) {
							foreach ($data_finalisasi_pk_pemda as $v) {
								$no++;
								$html .= '
						        <tr>
						            <td style="border: 1px solid black; text-align: center;">' . $no . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['label_sasaran'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['label_indikator'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['target'] . '</td>
						            <td style="border: 1px solid black; text-align: left;">' . $v['pagu'] . '</td>
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

	function edit_finalisasi_pk_pemda()
	{
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

				if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran tidak boleh kosong!';
					die(json_encode($ret));
				}

				$cek_id = $wpdb->get_results($wpdb->prepare("
                    SELECT 
                        * 
                    FROM esakip_finalisasi_tahap_pk_pemda 
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
						'esakip_finalisasi_tahap_pk_pemda',
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

	function get_data_pk_pemda_by_id_ajax()
	{
		try {
			$this->functions->validate($_POST, [
				'api_key' => 'required|string',
				'id' 	  => 'required'
			]);

			if ($_POST['api_key'] !== get_option(ESAKIP_APIKEY)) {
				throw new Exception("API key tidak valid atau tidak ditemukan!", 401);
			}

			$data = $this->get_data_pk_pemda_by_id($_POST['id']);

			echo json_encode([
				'status'  => true,
				'message' => 'Data berhasil ditemukan.',
				'data'    => $data
			]);
		} catch (Exception $e) {
			$code = is_int($e->getCode()) && $e->getCode() !== 0 ? $e->getCode() : 500;
			http_response_code($code);
			echo json_encode([
				'status'  => false,
				'message' => $e->getMessage()
			]);
		}
		wp_die();
	}

	function get_data_pk_pemda_by_id(int $id)
	{
		global $wpdb;

		$data = $wpdb->get_row(
			$wpdb->prepare("
				SELECT 
					pk.*,
					ik.label_sasaran as ik_label_sasaran,
					ik.label_indikator as ik_label_indikator,
					ik.satuan as ik_satuan
				FROM esakip_laporan_pk_pemda pk
				LEFT JOIN esakip_data_iku_pemda ik
					   ON pk.id_iku = ik.id
				WHERE pk.active = 1
				  AND pk.id = %d
			", $id),
			ARRAY_A
		);

		return $data;
	}

	function get_all_data_pk_pemda_by_tahun_and_id_jadwal_ajax()
	{
		try {
			$this->functions->validate($_POST, [
				'api_key'        => 'required|string',
				'tahun_anggaran' => 'required|numeric',
				'id_jadwal'      => 'required|numeric',
			]);

			if ($_POST['api_key'] !== get_option(ESAKIP_APIKEY)) {
				throw new Exception("API key tidak valid atau tidak ditemukan!", 401);
			}

			$tahun_anggaran = (int) $_POST['tahun_anggaran'];
			$id_jadwal      = (int) $_POST['id_jadwal'];
			$data           = $this->get_all_data_pk_pemda_by_tahun_and_id_jadwal($tahun_anggaran, $id_jadwal);

			echo json_encode([
				'status'  => true,
				'message' => 'Data berhasil diambil.',
				'data'    => $data
			]);
		} catch (Exception $e) {
			$code = is_int($e->getCode()) && $e->getCode() !== 0 ? $e->getCode() : 500;
			http_response_code($code);

			echo json_encode([
				'status'  => false,
				'message' => $e->getMessage()
			]);
		}

		wp_die();
	}

	function get_penanggung_jawab()
	{
		try {
			$this->functions->validate($_POST, [
				'tahun_anggaran' => 'required|numeric',
				'id_skpd'        => 'required|numeric',
			]);

			$tahun_anggaran = (int) $_POST['tahun_anggaran'];
			$id_skpd        = (int) $_POST['id_skpd'];

			$data = $this->get_all_rencana_aksi_by_id_skpd_tahun_anggaran($tahun_anggaran, $id_skpd);

			$penanggung_jawab_cascading = [];

			if (!empty($data)) {
				foreach ($data as $v) {
					if (empty($v['nip'])) {
						continue;
					}

					switch ($v['level']) {
						case 1:
							$key = $v['kode_cascading_sasaran'];
							break;
						case 2:
							$key = $v['kode_cascading_program'];
							break;
						case 3:
							$key = $v['kode_cascading_kegiatan'];
							break;
						case 4:
							$key = $v['kode_cascading_sub_kegiatan'];
							break;
					}

					if (empty($penanggung_jawab_cascading[$key])) {
						$penanggung_jawab_cascading[$key] = [];
					}

					$unique_key = $v['nip'] . '-' . $v['id_jabatan_asli'];

					if (empty($penanggung_jawab_cascading[$key][$unique_key])) {
						$penanggung_jawab_cascading[$key][$unique_key] = [
							'nip'        => $v['nip'],
							'id_jabatan' => $v['id_jabatan_asli']
						];
					}
				}

				// Ambil data pegawai berdasarkan satker
				$satker = $this->get_satker_id_by_id_skpd($id_skpd, $tahun_anggaran);
				$satker_id = $satker['id_satker_simpeg'] ?? null;

				$all_pegawai_by_satker = $satker_id
					? $this->get_all_pegawai_by_satker_id($satker_id, $tahun_anggaran)
					: [];

				$pegawai_lookup = [];
				if (!empty($all_pegawai_by_satker)) {
					foreach ($all_pegawai_by_satker as $pegawai) {
						$pegawai_lookup[$pegawai['nip_baru']][$pegawai['id_jabatan']] = $pegawai;
					}
				}

				// Gabungkan dengan data pegawai
				foreach ($penanggung_jawab_cascading as $kode_cascading => $arrPegawai) {
					$penanggung_jawab_cascading[$kode_cascading] = array_values($arrPegawai);

					$pegawai_ditemukan = [];

					foreach ($arrPegawai as $pj) {
						$nip = $pj['nip'];
						$id_jabatan = $pj['id_jabatan'];

						$pegawai = $pegawai_lookup[$nip][$id_jabatan] ?? null;

						if ($pegawai) {
							if (!empty($pegawai['custom_jabatan'])) {
								$jabatan = $pegawai['custom_jabatan'];
							} else {
								if ($pegawai['plt_plh'] == 1) {
									$jabatan = "{$pegawai['plt_plh_teks']} {$pegawai['jabatan']} {$pegawai['nama_satker']}";
								} else {
									$jabatan = "{$pegawai['jabatan']} {$pegawai['nama_satker']}";
								}
							}

							$nama_pegawai = "{$pegawai['gelar_depan']} {$pegawai['nama_pegawai']} {$pegawai['gelar_belakang']}";

							$pegawai_ditemukan[] = [
								'nama'       => $nama_pegawai,
								'jabatan'    => $jabatan,
							];
						}
					}

					if (empty($pegawai_ditemukan)) {
						unset($penanggung_jawab_cascading[$kode_cascading]);
					} else {
						$penanggung_jawab_cascading[$kode_cascading] = $pegawai_ditemukan;
					}
				}
			}

			$data_skpd = $this->get_data_unit_by_id_skpd_tahun_anggaran($id_skpd, $tahun_anggaran);

			$table = [];
			if (!empty($data_skpd)) {
				$table = $this->process_tbody_capaian_kinerja_pk_publik($data_skpd, $tahun_anggaran);
			}

			echo json_encode([
				'status'  => true,
				'message' => 'Data berhasil diambil.',
				'data'    => $penanggung_jawab_cascading,
				'table'    => $table,
			]);
		} catch (Exception $e) {
			$code = is_int($e->getCode()) && $e->getCode() !== 0 ? $e->getCode() : 500;
			http_response_code($code);
			echo json_encode([
				'status'  => false,
				'message' => $e->getMessage()
			]);
		}

		wp_die();
	}

	public function get_all_pegawai_by_satker_id($satker_id, $tahun_anggaran)
	{
		global $wpdb;

		$sql = $wpdb->prepare(
			"
			SELECT 
				p.*,
				s.nama as nama_satker
			FROM esakip_data_pegawai_simpeg p
			LEFT JOIN esakip_data_satker_simpeg s
				   ON p.satker_id = s.satker_id
				  AND s.tahun_anggaran = %d
				  AND s.active = 1
			WHERE p.satker_id LIKE %s 
			  AND p.active = 1
			",
			$tahun_anggaran,
			$satker_id . '%'
		);

		$pegawai = $wpdb->get_results($sql, ARRAY_A);

		return $pegawai;
	}


	public function get_satker_id_by_id_skpd(int $id_skpd, int $tahun_anggaran)
	{
		global $wpdb;

		$data = $wpdb->get_row(
			$wpdb->prepare("
				SELECT *
				FROM esakip_data_mapping_unit_sipd_simpeg
				WHERE id_skpd = %d
				  AND tahun_anggaran = %d
			", $id_skpd, $tahun_anggaran),
			ARRAY_A
		);

		return $data;
	}


	public function get_all_rencana_aksi_by_id_skpd_tahun_anggaran(int $tahun_anggaran, int $id_skpd)
	{
		global $wpdb;

		$data = $wpdb->get_results(
			$wpdb->prepare("
				SELECT *
				FROM esakip_data_rencana_aksi_opd
				WHERE tahun_anggaran = %d
				  AND id_skpd = %d
				  AND active = 1
			", $tahun_anggaran, $id_skpd),
			ARRAY_A
		);

		return $data;
	}

	function get_table_data_capaian_kinerja_publik()
	{
		if (empty(get_option(ESAKIP_APIKEY_WPSIPD)) && empty(get_option(ESAKIP_URL_WPSIPD))) {
			return [];
		}

		$api_params = array(
			'action' 	=> 'get_data_capaian_kinerja_publik',
			'api_key'	=> get_option(ESAKIP_APIKEY_WPSIPD),
			'id_skpd' => $_POST['id_skpd'],
			'tahun_anggaran' => $_POST['tahun_anggaran']
		);

		$response = wp_remote_post(
			get_option(ESAKIP_URL_WPSIPD),
			array(
				'timeout' 	=> 1000,
				'sslverify' => false,
				'body' 		=> $api_params
			)
		);

		die($response['body']);
	}

	function generate_html_capaian_gabungan($data_all)
	{
		$data_all = json_decode($data_all, true);
		$body_monev   	 = array();
		$rowspan   	 	 = 1;
		$no_urusan 	  	 = 0;
		$no_bidang 	  	 = 0;
		$no_program   	 = 0;
		$no_kegiatan  	 = 0;
		$no_sub_kegiatan = 0;
		$data_all_js 	 = array();

		foreach ($data_all['data'] as $kd_urusan => $urusan) {
			$no_urusan++;
			foreach ($urusan['data'] as $kd_bidang => $bidang) {
				$no_bidang++;
				foreach ($bidang['data'] as $kd_program_asli => $program) {
					$no_program++;
					$kd_program = explode('.', $kd_program_asli);
					$kd_program = $kd_program[count($kd_program) - 1];
					$capaian = 0;
					if (!empty($program['total_simda'])) {
						$capaian = $this->pembulatan(($program['realisasi'] / $program['total_simda']) * 100);
					}
					$bobot_kinerja_indikator 	= array();
					$capaian_prog_js 			= array();
					$target_capaian_prog_js 	= array();
					$satuan_capaian_prog_js 	= array();
					$realisasi_indikator_tw1_js = array();
					$realisasi_indikator_tw2_js = array();
					$realisasi_indikator_tw3_js = array();
					$realisasi_indikator_tw4_js = array();
					$total_tw_js 				= array();
					$capaian_prog 				= array();
					$target_capaian_prog 		= array();
					$satuan_capaian_prog 		= array();
					$realisasi_indikator_tw1 	= array();
					$realisasi_indikator_tw2 	= array();
					$realisasi_indikator_tw3 	= array();
					$realisasi_indikator_tw4 	= array();
					$capaian_anggaran_tw1 	= array();
					$capaian_anggaran_tw2 	= array();
					$capaian_anggaran_tw3 	= array();
					$capaian_anggaran_tw4 	= array();
					$total_tw 					= array();
					$capaian_realisasi_indikator = array();
					$class_rumus_target 		= array();
					$pendorong_html = array();
					$penghambat_html = array();
					if (!empty($program['indikator'])) {
						$realisasi_indikator = array();
						foreach ($program['realisasi_indikator'] as $k_sub => $v_sub) {
							$realisasi_indikator[$v_sub['id_indikator']] = $v_sub;
						}
						foreach ($program['indikator'] as $k_sub => $v_sub) {
							$target_capaian_prog_js[$k_sub] 	 = $v_sub['targetcapaian'];
							$bobot_kinerja_indikator[$k_sub] 	 = $v_sub['bobot_kinerja'];
							$satuan_capaian_prog_js[$k_sub] 	 = $v_sub['satuancapaian'];
							$target_capaian_prog[$k_sub] 		 = '<span data-id="' . $k_sub . '" bobot="">' . $v_sub['targetcapaian'] . '</span>';
							$satuan_capaian_prog[$k_sub] 		 = '<span data-id="' . $k_sub . '">' . $v_sub['satuancapaian'] . '</span>';
							$target_indikator 					 = $v_sub['targetcapaian'];
							$realisasi_indikator_tw1[$k_sub] 	 = 0;
							$realisasi_indikator_tw2[$k_sub] 	 = 0;
							$realisasi_indikator_tw3[$k_sub] 	 = 0;
							$realisasi_indikator_tw4[$k_sub] 	 = 0;
							$capaian_anggaran_tw1[$k_sub] 	 = !empty($program['rak_triwulan_1']) ? $this->pembulatan(($program['triwulan_1'] / $program['rak_triwulan_1']) * 100) : 0;
							$capaian_anggaran_tw2[$k_sub] 	 = !empty($program['rak_triwulan_2']) ? $this->pembulatan(($program['triwulan_2'] / $program['rak_triwulan_2']) * 100) : 0;
							$capaian_anggaran_tw3[$k_sub] 	 = !empty($program['rak_triwulan_3']) ? $this->pembulatan(($program['triwulan_3'] / $program['rak_triwulan_3']) * 100) : 0;
							$capaian_anggaran_tw4[$k_sub] 	 = !empty($program['rak_triwulan_4']) ? $this->pembulatan(($program['triwulan_4'] / $program['rak_triwulan_4']) * 100) : 0;
							$total_tw[$k_sub] 					 = 0;
							$capaian_realisasi_indikator[$k_sub] = 0;
							$realisasi_indikator_tw1_js[$k_sub]  = 0;
							$realisasi_indikator_tw2_js[$k_sub]  = 0;
							$realisasi_indikator_tw3_js[$k_sub]  = 0;
							$realisasi_indikator_tw4_js[$k_sub]  = 0;
							$total_tw_js[$k_sub] 				 = 0;
							$class_rumus_target[$k_sub] 		 = " positif";
							$pendorong_html[$k_sub] 	= "";
							$penghambat_html[$k_sub] 	= "";

							if (!empty($realisasi_indikator) && !empty($realisasi_indikator[$k_sub])) {
								$rumus_indikator = $realisasi_indikator[$k_sub]['id_rumus_indikator'];
								$max = 0;

								$pendorong_list = [];
								$penghambat_list = [];

								for ($i = 1; $i <= 12; $i++) {
									$pendorong_text = trim($realisasi_indikator[$k_sub]['pendorong_bulan_' . $i] ?? '');
									$penghambat_text = trim($realisasi_indikator[$k_sub]['keterangan_bulan_' . $i] ?? '');

									if ($pendorong_text !== '') {
										$pendorong_list[] = "<li>{$pendorong_text}</li>";
									}
									if ($penghambat_text !== '') {
										$penghambat_list[] = "<li>{$penghambat_text}</li>";
									}

									$realisasi_bulan = $realisasi_indikator[$k_sub]['realisasi_bulan_' . $i];
									if ($max < $realisasi_bulan) {
										$max = $realisasi_bulan;
									}
									$total_tw[$k_sub] += $realisasi_bulan;
									if ($i <= 3) {
										if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
											if ($i == 3) {
												$realisasi_indikator_tw1[$k_sub] = $realisasi_bulan;
											}
										} else {
											$realisasi_indikator_tw1[$k_sub] += $realisasi_bulan;
										}
									} else if ($i <= 6) {
										if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
											if ($i == 6) {
												$realisasi_indikator_tw2[$k_sub] = $realisasi_bulan;
											}
										} else {
											$realisasi_indikator_tw2[$k_sub] += $realisasi_bulan;
										}
									} else if ($i <= 9) {
										if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
											if ($i == 9) {
												$realisasi_indikator_tw3[$k_sub] = $realisasi_bulan;
											}
										} else {
											$realisasi_indikator_tw3[$k_sub] += $realisasi_bulan;
										}
									} else if ($i <= 12) {
										if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
											if ($i == 12) {
												$realisasi_indikator_tw4[$k_sub] = $realisasi_bulan;
											}
										} else {
											$realisasi_indikator_tw4[$k_sub] += $realisasi_bulan;
										}
									}
								}
								if (!empty($pendorong_list)) {
									$pendorong_html[$k_sub] = "<ul>" . implode("", $pendorong_list) . "</ul>";
								} else {
									$pendorong_html[$k_sub] = "-";
								}

								if (!empty($penghambat_list)) {
									$penghambat_html[$k_sub] = "<ul>" . implode("", $penghambat_list) . "</ul>";
								} else {
									$penghambat_html[$k_sub] = "-";
								}

								if ($rumus_indikator == 1) {
									$class_rumus_target[$k_sub] = "positif";
									if (!empty($target_indikator)) {
										$capaian_realisasi_indikator[$k_sub] = $this->pembulatan(($total_tw[$k_sub] / $target_indikator) * 100);
									}
								} else if ($rumus_indikator == 2) {
									$class_rumus_target[$k_sub] = "negatif";
									$total_tw[$k_sub] = $max;
									if (!empty($total_tw[$k_sub])) {
										$capaian_realisasi_indikator[$k_sub] = $this->pembulatan(($target_indikator / $total_tw[$k_sub]) * 100);
									}
								} else if ($rumus_indikator == 3 || $rumus_indikator == 4) {
									if ($rumus_indikator == 3) {
										$class_rumus_target[$k_sub] = "persentase";
									} else if ($rumus_indikator == 4) {
										$class_rumus_target[$k_sub] = "nilai_akhir";
									}
									$total_tw[$k_sub] = $max;
									if (!empty($target_indikator)) {
										$capaian_realisasi_indikator[$k_sub] = $this->pembulatan(($total_tw[$k_sub] / $target_indikator) * 100);
									}
								}
							}
							$capaian_prog_js[] = $v_sub['capaianteks'];
							$realisasi_indikator_tw1_js[$k_sub] = $realisasi_indikator_tw1[$k_sub];
							$realisasi_indikator_tw2_js[$k_sub] = $realisasi_indikator_tw2[$k_sub];
							$realisasi_indikator_tw3_js[$k_sub] = $realisasi_indikator_tw3[$k_sub];
							$realisasi_indikator_tw4_js[$k_sub] = $realisasi_indikator_tw4[$k_sub];
							$total_tw_js[$k_sub] = $total_tw[$k_sub];

							$realisasi_indikator_tw1[$k_sub] = '<span class="realisasi_indikator_tw1-' . $k_sub . '">' . $realisasi_indikator_tw1[$k_sub] . '</span>';
							$realisasi_indikator_tw2[$k_sub] = '<span class="realisasi_indikator_tw2-' . $k_sub . '">' . $realisasi_indikator_tw2[$k_sub] . '</span>';
							$realisasi_indikator_tw3[$k_sub] = '<span class="realisasi_indikator_tw3-' . $k_sub . '">' . $realisasi_indikator_tw3[$k_sub] . '</span>';
							$realisasi_indikator_tw4[$k_sub] = '<span class="realisasi_indikator_tw4-' . $k_sub . '">' . $realisasi_indikator_tw4[$k_sub] . '</span>';

							$capaian_anggaran_tw1[$k_sub] = '<span class="capaian_anggaran_tw1-' . $k_sub . '">' . $capaian_anggaran_tw1[$k_sub] . '</span>';
							$capaian_anggaran_tw2[$k_sub] = '<span class="capaian_anggaran_tw2-' . $k_sub . '">' . $capaian_anggaran_tw2[$k_sub] . '</span>';
							$capaian_anggaran_tw3[$k_sub] = '<span class="capaian_anggaran_tw3-' . $k_sub . '">' . $capaian_anggaran_tw3[$k_sub] . '</span>';
							$capaian_anggaran_tw4[$k_sub] = '<span class="capaian_anggaran_tw4-' . $k_sub . '">' . $capaian_anggaran_tw4[$k_sub] . '</span>';

							$penghambat_html[$k_sub] = '<span class="penghambat_html-' . $k_sub . '">' . $penghambat_html[$k_sub] . '</span>';
							$pendorong_html[$k_sub] = '<span class="pendorong_html-' . $k_sub . '">' . $pendorong_html[$k_sub] . '</span>';

							$total_tw[$k_sub] = '<span class="total_tw-' . $k_sub . ' rumus_indikator ' . $class_rumus_target[$k_sub] . '">' . $total_tw[$k_sub] . '</span>';
							$capaian_realisasi_indikator[$k_sub] = '<span class="capaian_realisasi_indikator-' . $k_sub . ' rumus_indikator ' . $class_rumus_target[$k_sub] . '">' . $this->pembulatan($capaian_realisasi_indikator[$k_sub]) . '</span>';
							$capaian_prog[] = '<span data-id="' . $k_sub . '" class="rumus_indikator ' . $class_rumus_target[$k_sub] . '">' . $v_sub['capaianteks'] . '</span>';
						}
					}

					$data_all_js[] = array(
						'nama' 					  => $kd_program_asli . ' ' . $program['nama'],
						'pagu' 					  => number_format($program['total_simda'], 0, ",", "."),
						'realisasi' 			  => number_format($program['realisasi'], 0, ",", "."),
						'capaian' 				  => $capaian,
						'rak_tw_1' 				  => $program['rak_triwulan_1'],
						'rak_tw_2' 				  => $program['rak_triwulan_2'],
						'rak_tw_3' 				  => $program['rak_triwulan_3'],
						'rak_tw_4' 				  => $program['rak_triwulan_4'],
						'realisasi_tw_1' 		  => $program['triwulan_1'],
						'realisasi_tw_2' 		  => $program['triwulan_2'],
						'realisasi_tw_3' 		  => $program['triwulan_3'],
						'realisasi_tw_4' 		  => $program['triwulan_4'],
						'indikator' 			  => $capaian_prog_js,
						'satuan' 				  => $satuan_capaian_prog_js,
						'bobot_kinerja_indikator' => $bobot_kinerja_indikator,
						'target_indikator' 		  => $target_capaian_prog_js,
						'realisasi_indikator' 	  => $total_tw_js,
						'realisasi_indikator_1'   => $realisasi_indikator_tw1_js,
						'realisasi_indikator_2'   => $realisasi_indikator_tw2_js,
						'realisasi_indikator_3'   => $realisasi_indikator_tw3_js,
						'realisasi_indikator_4'   => $realisasi_indikator_tw4_js,
					);

					$capaian_prog 			 	 = implode('<br>', $capaian_prog);
					$target_capaian_prog 	 	 = implode('<br>', $target_capaian_prog);
					$satuan_capaian_prog 	 	 = implode('<br>', $satuan_capaian_prog);
					$realisasi_indikator_tw1 	 = implode('<br>', $realisasi_indikator_tw1);
					$realisasi_indikator_tw2 	 = implode('<br>', $realisasi_indikator_tw2);
					$realisasi_indikator_tw3 	 = implode('<br>', $realisasi_indikator_tw3);
					$realisasi_indikator_tw4 	 = implode('<br>', $realisasi_indikator_tw4);

					$capaian_anggaran_tw1 	 = implode('<br>', $capaian_anggaran_tw1);
					$capaian_anggaran_tw2 	 = implode('<br>', $capaian_anggaran_tw2);
					$capaian_anggaran_tw3 	 = implode('<br>', $capaian_anggaran_tw3);
					$capaian_anggaran_tw4 	 = implode('<br>', $capaian_anggaran_tw4);
					$total_tw 				 	 = implode('<br>', $total_tw);
					$capaian_realisasi_indikator = implode('<br>', $capaian_realisasi_indikator);

					$pendorong_html = implode('<br>', $pendorong_html);
					$penghambat_html = implode('<br>', $penghambat_html);
					$body_monev[$kd_program_asli] = '
						<tr class="tr-program program" data-kode="' . $kd_urusan . '.' . $kd_bidang . '.' . $kd_program . '" data-bidang-urusan="' . $program['kode_urusan_bidang'] . '">
							<td class="kanan bawah text_blok" data-kode="' . $kd_urusan . '.' . $kd_bidang . '.' . $kd_program . '" data-bidang-urusan="' . $program['kode_urusan_bidang'] . '" style="width: 125px;">' . $kd_program_asli . '</td>
							<td class="kanan bawah text_blok nama" style="width: 300px;">' . $program['nama'] . '</td>
							<td class="kanan bawah text_blok indikator" style="width: 200px;">' . $capaian_prog . '</td>
							<td class="text_tengah kanan bawah text_blok total_renja target_indikator" style="width: 150px;">' . $target_capaian_prog . '</td>
							<td class="text_tengah kanan bawah text_blok total_renja satuan_indikator" style="width: 150px;">' . $satuan_capaian_prog . '</td>
							<td class="text_tengah kanan bawah text_blok triwulan_1" style="width: 150px;">' . $realisasi_indikator_tw1 . '</td>
							<td class="text_tengah kanan bawah text_blok triwulan_1" style="width: 150px;">' . $capaian_anggaran_tw1 . '</td>
							<td class="text_tengah kanan bawah text_blok triwulan_2" style="width: 150px;">' . $realisasi_indikator_tw2 . '</td>
							<td class="text_tengah kanan bawah text_blok triwulan_2" style="width: 150px;">' . $capaian_anggaran_tw2 . '</td>
							<td class="text_tengah kanan bawah text_blok triwulan_3" style="width: 150px;">' . $realisasi_indikator_tw3 . '</td>
							<td class="text_tengah kanan bawah text_blok triwulan_3" style="width: 150px;">' . $capaian_anggaran_tw3 . '</td>
							<td class="text_tengah kanan bawah text_blok triwulan_4" style="width: 150px;">' . $realisasi_indikator_tw4 . '</td>
							<td class="text_tengah kanan bawah text_blok triwulan_4" style="width: 150px;">' . $capaian_anggaran_tw4 . '</td>
							<td class="text_tengah kanan bawah text_blok capaian_renja" style="width: 100px;">' . $total_tw . '</td>
							<td class="text_tengah kanan bawah text_blok capaian_renja" style="width: 100px;">' . $capaian_realisasi_indikator . '</td>
							<td class="text_tengah kanan bawah text_blok capaian_renja" style="width: 100px;">' . $capaian . '</td>
							<td class="kanan bawah text_blok" data-kode-progkeg="' . $kd_bidang . '.' . $kd_program . '" style="width: 200px;"></td>
							<td class="kanan bawah text_blok" style="width: 200px;">' . $pendorong_html . '</td>
							<td class="kanan bawah text_blok" style="width: 200px;">' . $penghambat_html . '</td>
						</tr>
					';

					$rowspan++;
					foreach ($program['data'] as $kd_giat1 => $giat) {
						$no_kegiatan++;
						$kd_giat = explode('.', $kd_giat1);
						$kd_giat = $kd_giat[count($kd_giat) - 2] . '.' . $kd_giat[count($kd_giat) - 1];
						$capaian = 0;
						if (!empty($giat['total_simda'])) {
							$capaian = $this->pembulatan(($giat['realisasi'] / $giat['total_simda']) * 100);
						}
						$output_giat 			 	 = array();
						$target_output_giat 	 	 = array();
						$satuan_output_giat 	 	 = array();
						$realisasi_indikator_tw1 	 = array();
						$realisasi_indikator_tw2 	 = array();
						$realisasi_indikator_tw3 	 = array();
						$realisasi_indikator_tw4 	 = array();
						$capaian_anggaran_tw1 	 = array();
						$capaian_anggaran_tw2 	 = array();
						$capaian_anggaran_tw3 	 = array();
						$capaian_anggaran_tw4 	 = array();
						$total_tw 				 	 = array();
						$capaian_realisasi_indikator = array();
						$class_rumus_target 		 = array();
						$pendorong_html = array();
						$penghambat_html = array();
						if (!empty($giat['indikator'])) {
							$realisasi_indikator = array();
							foreach ($giat['realisasi_indikator'] as $k_sub => $v_sub) {
								$realisasi_indikator[$v_sub['id_indikator']] = $v_sub;
							}
							foreach ($giat['indikator'] as $k_sub => $v_sub) {
								$target_output_giat[$k_sub] = ' <span data-id="' . $k_sub . '">' . $v_sub['targetoutput'] . '</span>';
								$satuan_output_giat[$k_sub] = '<span data-id="' . $k_sub . '">' . $v_sub['satuanoutput'] . '</span>';
								$target_indikator = $v_sub['targetoutput'];
								$realisasi_indikator_tw1[$k_sub] = 0;
								$realisasi_indikator_tw2[$k_sub] = 0;
								$realisasi_indikator_tw3[$k_sub] = 0;
								$realisasi_indikator_tw4[$k_sub] = 0;

								$capaian_anggaran_tw1[$k_sub] 	 = !empty($giat['rak_triwulan_1']) ? $this->pembulatan(($giat['triwulan_1'] / $giat['rak_triwulan_1']) * 100) : 0;
								$capaian_anggaran_tw2[$k_sub] 	 = !empty($giat['rak_triwulan_2']) ? $this->pembulatan(($giat['triwulan_2'] / $giat['rak_triwulan_2']) * 100) : 0;
								$capaian_anggaran_tw3[$k_sub] 	 = !empty($giat['rak_triwulan_3']) ? $this->pembulatan(($giat['triwulan_3'] / $giat['rak_triwulan_3']) * 100) : 0;
								$capaian_anggaran_tw4[$k_sub] 	 = !empty($giat['rak_triwulan_4']) ? $this->pembulatan(($giat['triwulan_4'] / $giat['rak_triwulan_4']) * 100) : 0;
								$total_tw[$k_sub] = 0;
								$capaian_realisasi_indikator[$k_sub] = 0;
								$class_rumus_target[$k_sub] = "positif";
								$pendorong_html[$k_sub] 	= "";
								$penghambat_html[$k_sub] 	= "";

								if (
									!empty($realisasi_indikator)
									&& !empty($realisasi_indikator[$k_sub])
								) {
									$rumus_indikator = $realisasi_indikator[$k_sub]['id_rumus_indikator'];
									$max = 0;
									$pendorong_list = [];
									$penghambat_list = [];
									for ($i = 1; $i <= 12; $i++) {
										$realisasi_bulan = $realisasi_indikator[$k_sub]['realisasi_bulan_' . $i];
										$pendorong_text = trim($realisasi_indikator[$k_sub]['pendorong_bulan_' . $i] ?? '');
										$penghambat_text = trim($realisasi_indikator[$k_sub]['keterangan_bulan_' . $i] ?? '');

										// die(var_dump($realisasi_indikator[$k_sub]));
										if ($pendorong_text !== '') {
											$pendorong_list[] = "<li>{$pendorong_text}</li>";
										}
										if ($penghambat_text !== '') {
											$penghambat_list[] = "<li>{$penghambat_text}</li>";
										}
										if ($max < $realisasi_bulan) {
											$max = $realisasi_bulan;
										}
										$total_tw[$k_sub] += $realisasi_bulan;
										if ($i <= 3) {
											if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
												if ($i == 3) {
													$realisasi_indikator_tw1[$k_sub] = $realisasi_bulan;
												}
											} else {
												$realisasi_indikator_tw1[$k_sub] += $realisasi_bulan;
											}
										} else if ($i <= 6) {
											if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
												if ($i == 6) {
													$realisasi_indikator_tw2[$k_sub] = $realisasi_bulan;
												}
											} else {
												$realisasi_indikator_tw2[$k_sub] += $realisasi_bulan;
											}
										} else if ($i <= 9) {
											if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
												if ($i == 9) {
													$realisasi_indikator_tw3[$k_sub] = $realisasi_bulan;
												}
											} else {
												$realisasi_indikator_tw3[$k_sub] += $realisasi_bulan;
											}
										} else if ($i <= 12) {
											if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
												if ($i == 12) {
													$realisasi_indikator_tw4[$k_sub] = $realisasi_bulan;
												}
											} else {
												$realisasi_indikator_tw4[$k_sub] += $realisasi_bulan;
											}
										}
									}
									if (!empty($pendorong_list)) {
										$pendorong_html[$k_sub] = "<ul>" . implode("", $pendorong_list) . "</ul>";
									} else {
										$pendorong_html[$k_sub] = "-";
									}

									if (!empty($penghambat_list)) {
										$penghambat_html[$k_sub] = "<ul>" . implode("", $penghambat_list) . "</ul>";
									} else {
										$penghambat_html[$k_sub] = "-";
									}
									if ($rumus_indikator == 1) {
										$class_rumus_target[$k_sub] = "positif";
										if (!empty($target_indikator)) {
											$capaian_realisasi_indikator[$k_sub] = $this->pembulatan(($total_tw[$k_sub] / $target_indikator) * 100);
										}
									} else if ($rumus_indikator == 2) {
										$class_rumus_target[$k_sub] = "negatif";
										$total_tw[$k_sub] = $max;
										if (!empty($total_tw[$k_sub])) {
											$capaian_realisasi_indikator[$k_sub] = $this->pembulatan(($target_indikator / $total_tw[$k_sub]) * 100);
										}
									} else if ($rumus_indikator == 3 || $rumus_indikator == 4) {
										if ($rumus_indikator == 3) {
											$class_rumus_target[$k_sub] = "persentase";
										} else if ($rumus_indikator == 4) {
											$class_rumus_target[$k_sub] = "nilai_akhir";
										}
										$total_tw[$k_sub] = $max;
										if (!empty($target_indikator)) {
											$capaian_realisasi_indikator[$k_sub] = $this->pembulatan(($total_tw[$k_sub] / $target_indikator) * 100);
										}
									}
								}

								$realisasi_indikator_tw1[$k_sub] = '<span class="realisasi_indikator_tw1-' . $k_sub . '">' . $realisasi_indikator_tw1[$k_sub] . '</span>';
								$realisasi_indikator_tw2[$k_sub] = '<span class="realisasi_indikator_tw2-' . $k_sub . '">' . $realisasi_indikator_tw2[$k_sub] . '</span>';
								$realisasi_indikator_tw3[$k_sub] = '<span class="realisasi_indikator_tw3-' . $k_sub . '">' . $realisasi_indikator_tw3[$k_sub] . '</span>';
								$realisasi_indikator_tw4[$k_sub] = '<span class="realisasi_indikator_tw4-' . $k_sub . '">' . $realisasi_indikator_tw4[$k_sub] . '</span>';

								$capaian_anggaran_tw1[$k_sub] = '<span class="capaian_anggaran_tw1-' . $k_sub . '">' . $capaian_anggaran_tw1[$k_sub] . '</span>';
								$capaian_anggaran_tw2[$k_sub] = '<span class="capaian_anggaran_tw2-' . $k_sub . '">' . $capaian_anggaran_tw2[$k_sub] . '</span>';
								$capaian_anggaran_tw3[$k_sub] = '<span class="capaian_anggaran_tw3-' . $k_sub . '">' . $capaian_anggaran_tw3[$k_sub] . '</span>';
								$capaian_anggaran_tw4[$k_sub] = '<span class="capaian_anggaran_tw4-' . $k_sub . '">' . $capaian_anggaran_tw4[$k_sub] . '</span>';

								$penghambat_html[$k_sub] = '<span class="penghambat_html-' . $k_sub . '">' . $penghambat_html[$k_sub] . '</span>';
								$pendorong_html[$k_sub] = '<span class="pendorong_html-' . $k_sub . '">' . $pendorong_html[$k_sub] . '</span>';

								$total_tw[$k_sub] = '<span class="total_tw-' . $k_sub . ' rumus_indikator ' . $class_rumus_target[$k_sub] . '">' . $total_tw[$k_sub] . '</span>';
								$capaian_realisasi_indikator[$k_sub] = '<span class="capaian_realisasi_indikator-' . $k_sub . ' rumus_indikator ' . $class_rumus_target[$k_sub] . '">' . $this->pembulatan($capaian_realisasi_indikator[$k_sub]) . '</span>';
								$output_giat[] = '<span data-id="' . $k_sub . '" class="rumus_indikator ' . $class_rumus_target[$k_sub] . '">' . $v_sub['outputteks'] . '</span>';
							}
						}
						$output_giat = implode('<br>', $output_giat);
						$target_output_giat = implode('<br>', $target_output_giat);
						$satuan_output_giat = implode('<br>', $satuan_output_giat);
						$realisasi_indikator_tw1 = implode('<br>', $realisasi_indikator_tw1);
						$realisasi_indikator_tw2 = implode('<br>', $realisasi_indikator_tw2);
						$realisasi_indikator_tw3 = implode('<br>', $realisasi_indikator_tw3);
						$realisasi_indikator_tw4 = implode('<br>', $realisasi_indikator_tw4);

						$capaian_anggaran_tw1 	 = implode('<br>', $capaian_anggaran_tw1);
						$capaian_anggaran_tw2 	 = implode('<br>', $capaian_anggaran_tw2);
						$capaian_anggaran_tw3 	 = implode('<br>', $capaian_anggaran_tw3);
						$capaian_anggaran_tw4 	 = implode('<br>', $capaian_anggaran_tw4);

						$total_tw = implode('<br>', $total_tw);
						$capaian_realisasi_indikator = implode('<br>', $capaian_realisasi_indikator);

						$pendorong_html = implode('<br>', $pendorong_html);
						$penghambat_html = implode('<br>', $penghambat_html);

						$body_monev[$kd_giat1] = '
							<tr class="tr-kegiatan kegiatan" data-kode="' . $kd_urusan . '.' . $kd_bidang . '.' . $kd_program . '.' . $kd_giat . '" data-kode_giat="' . $kd_giat1 . '" data-bidang-urusan="' . $giat['kode_urusan_bidang'] . '">
								<td class="kanan bawah text_blok" data-kode="' . $kd_urusan . '.' . $kd_bidang . '.' . $kd_program . '.' . $kd_giat . '" data-kode_giat="' . $kd_giat1 . '" data-bidang-urusan="' . $giat['kode_urusan_bidang'] . '">' . $kd_giat1 . '</td>
								<td class="kanan bawah text_blok nama">' . $giat['nama'] . '</td>
								<td class="kanan bawah text_blok indikator">' . $output_giat . '</td>
								<td class="text_tengah kanan bawah text_blok total_renja target_indikator">' . $target_output_giat . '</td>
								<td class="text_tengah kanan bawah text_blok total_renja satuan_indikator">' . $satuan_output_giat . '</td>
								<td class="text_tengah kanan bawah text_blok triwulan_1">' . $realisasi_indikator_tw1 . '</td>
								<td class="text_tengah kanan bawah text_blok triwulan_1">' . $capaian_anggaran_tw1 . '</td>
								<td class="text_tengah kanan bawah text_blok triwulan_2">' . $realisasi_indikator_tw2 . '</td>
								<td class="text_tengah kanan bawah text_blok triwulan_2">' . $capaian_anggaran_tw2 . '</td>
								<td class="text_tengah kanan bawah text_blok triwulan_3">' . $realisasi_indikator_tw3 . '</td>
								<td class="text_tengah kanan bawah text_blok triwulan_3">' . $capaian_anggaran_tw3 . '</td>
								<td class="text_tengah kanan bawah text_blok triwulan_4">' . $realisasi_indikator_tw4 . '</td>
								<td class="text_tengah kanan bawah text_blok triwulan_4">' . $capaian_anggaran_tw4 . '</td>
								<td class="text_tengah kanan bawah text_blok capaian_renja">' . $total_tw . '</td>
								<td class="text_tengah kanan bawah text_blok capaian_renja">' . $capaian_realisasi_indikator . '</td>
								<td class="text_tengah kanan bawah text_blok capaian_renja">' . $capaian . '</td>
								<td class="kanan bawah text_blok"></td>
								<td class="kanan bawah text_blok">' . $pendorong_html . '</td>
								<td class="kanan bawah text_blok">' . $penghambat_html . '</td>
							</tr>
							';
						$rowspan++;
						foreach ($giat['data'] as $kd_sub_giat1 => $sub_giat) {
							$no_sub_kegiatan++;
							$kd_sub_giat = explode('.', $kd_sub_giat1);
							$kd_sub_giat = $kd_sub_giat[count($kd_sub_giat) - 1];
							$capaian = 0;
							if (!empty($sub_giat['total_simda'])) {
								$capaian = $this->pembulatan(($sub_giat['realisasi'] / $sub_giat['total_simda']) * 100);
							}
							$output_sub_giat = array();
							$target_output_sub_giat = array();
							$satuan_output_sub_giat = array();
							$realisasi_indikator_tw1 = array();
							$realisasi_indikator_tw2 = array();
							$realisasi_indikator_tw3 = array();
							$realisasi_indikator_tw4 = array();
							$capaian_anggaran_tw1 = array();
							$capaian_anggaran_tw2 = array();
							$capaian_anggaran_tw3 = array();
							$capaian_anggaran_tw4 = array();
							$total_tw = array();
							$capaian_realisasi_indikator = array();
							$class_rumus_target = array();
							$pendorong_html = array();
							$penghambat_html = array();
							if (!empty($sub_giat['indikator'])) {
								$realisasi_indikator = array();
								foreach ($sub_giat['realisasi_indikator'] as $k_sub => $v_sub) {
									$realisasi_indikator[$v_sub['id_indikator']] = $v_sub;
								}
								foreach ($sub_giat['indikator'] as $k_sub => $v_sub) {

									$target_output_sub_giat[] = ' <span data-id="' . $v_sub['idoutputbl'] . '">' . $v_sub['targetoutput'] . '</span>';
									$satuan_output_sub_giat[] = '<span data-id="' . $v_sub['idoutputbl'] . '">' . $v_sub['satuanoutput'] . '</span>';
									$target_indikator = $v_sub['targetoutput'];
									$realisasi_indikator_tw1[$k_sub] = 0;
									$realisasi_indikator_tw2[$k_sub] = 0;
									$realisasi_indikator_tw3[$k_sub] = 0;
									$realisasi_indikator_tw4[$k_sub] = 0;

									$capaian_anggaran_tw1[$k_sub] 	 = !empty($sub_giat['rak_triwulan_1']) ? $this->pembulatan(($sub_giat['triwulan_1'] / $sub_giat['rak_triwulan_1']) * 100) : 0;
									$capaian_anggaran_tw2[$k_sub] 	 = !empty($sub_giat['rak_triwulan_2']) ? $this->pembulatan(($sub_giat['triwulan_2'] / $sub_giat['rak_triwulan_2']) * 100) : 0;
									$capaian_anggaran_tw3[$k_sub] 	 = !empty($sub_giat['rak_triwulan_3']) ? $this->pembulatan(($sub_giat['triwulan_3'] / $sub_giat['rak_triwulan_3']) * 100) : 0;
									$capaian_anggaran_tw4[$k_sub] 	 = !empty($sub_giat['rak_triwulan_4']) ? $this->pembulatan(($sub_giat['triwulan_4'] / $sub_giat['rak_triwulan_4']) * 100) : 0;
									$total_tw[$k_sub] = 0;
									$capaian_realisasi_indikator[$k_sub] = 0;
									$class_rumus_target[$k_sub] = "positif";
									$pendorong_html[$k_sub] 	= "";
									$penghambat_html[$k_sub] 	= "";
									if (
										!empty($realisasi_indikator)
										&& !empty($realisasi_indikator[$v_sub['idoutputbl']])
									) {
										$rumus_indikator = $realisasi_indikator[$v_sub['idoutputbl']]['id_rumus_indikator'];
										$max = 0;

										$pendorong_list = [];
										$penghambat_list = [];
										for ($i = 1; $i <= 12; $i++) {
											$realisasi_bulan = $realisasi_indikator[$v_sub['idoutputbl']]['realisasi_bulan_' . $i];
											if ($max < $realisasi_bulan) {
												$max = $realisasi_bulan;
											}
											$total_tw[$k_sub] += $realisasi_bulan;

											$pendorong_text = trim($realisasi_indikator[$k_sub]['pendorong_bulan_' . $i] ?? '');
											$penghambat_text = trim($realisasi_indikator[$k_sub]['keterangan_bulan_' . $i] ?? '');

											// die(var_dump($realisasi_indikator[$k_sub]));
											if ($pendorong_text !== '') {
												$pendorong_list[] = "<li>{$pendorong_text}</li>";
											}
											if ($penghambat_text !== '') {
												$penghambat_list[] = "<li>{$penghambat_text}</li>";
											}
											if ($i <= 3) {
												if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
													if ($i == 3) {
														$realisasi_indikator_tw1[$k_sub] = $realisasi_bulan;
													}
												} else {
													$realisasi_indikator_tw1[$k_sub] += $realisasi_bulan;
												}
											} else if ($i <= 6) {
												if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
													if ($i == 6) {
														$realisasi_indikator_tw2[$k_sub] = $realisasi_bulan;
													}
												} else {
													$realisasi_indikator_tw2[$k_sub] += $realisasi_bulan;
												}
											} else if ($i <= 9) {
												if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
													if ($i == 9) {
														$realisasi_indikator_tw3[$k_sub] = $realisasi_bulan;
													}
												} else {
													$realisasi_indikator_tw3[$k_sub] += $realisasi_bulan;
												}
											} else if ($i <= 12) {
												if ($rumus_indikator == 3 || $rumus_indikator == 2 || $rumus_indikator == 4) {
													if ($i == 12) {
														$realisasi_indikator_tw4[$k_sub] = $realisasi_bulan;
													}
												} else {
													$realisasi_indikator_tw4[$k_sub] += $realisasi_bulan;
												}
											}
										}

										if (!empty($pendorong_list)) {
											$pendorong_html[$k_sub] = "<ul>" . implode("", $pendorong_list) . "</ul>";
										} else {
											$pendorong_html[$k_sub] = "-";
										}

										if (!empty($penghambat_list)) {
											$penghambat_html[$k_sub] = "<ul>" . implode("", $penghambat_list) . "</ul>";
										} else {
											$penghambat_html[$k_sub] = "-";
										}
										if ($rumus_indikator == 1) {
											$class_rumus_target[$k_sub] = "positif";
											if (
												!empty($target_indikator)
												&& !empty($total_tw[$k_sub])
											) {
												$capaian_realisasi_indikator[$k_sub] = $this->pembulatan(($total_tw[$k_sub] / $target_indikator) * 100);
											}
										} else if ($rumus_indikator == 2) {
											$class_rumus_target[$k_sub] = "negatif";
											$total_tw[$k_sub] = $max;
											if (
												!empty($target_indikator)
												&& !empty($total_tw[$k_sub])
											) {
												$capaian_realisasi_indikator[$k_sub] = $this->pembulatan(($target_indikator / $total_tw[$k_sub]) * 100);
											}
										} else if ($rumus_indikator == 3 || $rumus_indikator == 4) {
											if ($rumus_indikator == 3) {
												$class_rumus_target[$k_sub] = "persentase";
											} else if ($rumus_indikator == 4) {
												$class_rumus_target[$k_sub] = "nilai_akhir";
											}
											$total_tw[$k_sub] = $max;
											if (
												!empty($target_indikator)
												&& !empty($total_tw[$k_sub])
											) {
												$capaian_realisasi_indikator[$k_sub] = $this->pembulatan(($total_tw[$k_sub] / $target_indikator) * 100);
											}
										}
									}
									$output_sub_giat[] = '<span data-id="' . $v_sub['idoutputbl'] . '" class="rumus_indikator ' . $class_rumus_target[$k_sub] . '">' . $v_sub['outputteks'] . '</span>';
									$realisasi_indikator_tw1[$k_sub] = '<span class="realisasi_indikator_tw1-' . $v_sub['idoutputbl'] . '">' . $realisasi_indikator_tw1[$k_sub] . '</span>';
									$realisasi_indikator_tw2[$k_sub] = '<span class="realisasi_indikator_tw2-' . $v_sub['idoutputbl'] . '">' . $realisasi_indikator_tw2[$k_sub] . '</span>';
									$realisasi_indikator_tw3[$k_sub] = '<span class="realisasi_indikator_tw3-' . $v_sub['idoutputbl'] . '">' . $realisasi_indikator_tw3[$k_sub] . '</span>';
									$realisasi_indikator_tw4[$k_sub] = '<span class="realisasi_indikator_tw4-' . $v_sub['idoutputbl'] . '">' . $realisasi_indikator_tw4[$k_sub] . '</span>';

									$capaian_anggaran_tw1[$k_sub] = '<span class="capaian_anggaran_tw1-' . $v_sub['idoutputbl'] . '">' . $capaian_anggaran_tw1[$k_sub] . '</span>';
									$capaian_anggaran_tw2[$k_sub] = '<span class="capaian_anggaran_tw2-' . $v_sub['idoutputbl'] . '">' . $capaian_anggaran_tw1[$k_sub] . '</span>';
									$capaian_anggaran_tw3[$k_sub] = '<span class="capaian_anggaran_tw3-' . $v_sub['idoutputbl'] . '">' . $capaian_anggaran_tw1[$k_sub] . '</span>';
									$capaian_anggaran_tw4[$k_sub] = '<span class="capaian_anggaran_tw4-' . $v_sub['idoutputbl'] . '">' . $capaian_anggaran_tw1[$k_sub] . '</span>';
									$total_tw[$k_sub] = '<span class="total_tw-' . $v_sub['idoutputbl'] . ' rumus_indikator ' . $class_rumus_target[$k_sub] . '">' . $total_tw[$k_sub] . '</span>';
									$capaian_realisasi_indikator[$k_sub] = '<span class="capaian_realisasi_indikator-' . $v_sub['idoutputbl'] . ' rumus_indikator ' . $class_rumus_target[$k_sub] . '">' . $capaian_realisasi_indikator[$k_sub] . '</span>';
								}
							}
							$output_sub_giat = implode('<br>', $output_sub_giat);
							$target_output_sub_giat = implode('<br>', $target_output_sub_giat);
							$satuan_output_sub_giat = implode('<br>', $satuan_output_sub_giat);
							$realisasi_indikator_tw1 = implode('<br>', $realisasi_indikator_tw1);
							$realisasi_indikator_tw2 = implode('<br>', $realisasi_indikator_tw2);
							$realisasi_indikator_tw3 = implode('<br>', $realisasi_indikator_tw3);
							$realisasi_indikator_tw4 = implode('<br>', $realisasi_indikator_tw4);

							$capaian_anggaran_tw1 = implode('<br>', $capaian_anggaran_tw1);
							$capaian_anggaran_tw2 = implode('<br>', $capaian_anggaran_tw2);
							$capaian_anggaran_tw3 = implode('<br>', $capaian_anggaran_tw3);
							$capaian_anggaran_tw4 = implode('<br>', $capaian_anggaran_tw4);
							$total_tw = implode('<br>', $total_tw);
							$capaian_realisasi_indikator = implode('<br>', $capaian_realisasi_indikator);

							$nama_sub = $sub_giat['nama'];

							$pendorong_html = implode('<br>', $pendorong_html);
							$penghambat_html = implode('<br>', $penghambat_html);

							$body_monev[$kd_sub_giat1] = '
								<tr class="tr-sub-kegiatan sub_kegiatan" data-kode="' . $kd_urusan . '.' . $kd_bidang . '.' . $kd_program . '.' . $kd_giat . '.' . $kd_sub_giat . '">
									<td class="kanan bawah" data-kode="' . $kd_urusan . '.' . $kd_bidang . '.' . $kd_program . '.' . $kd_giat . '.' . $kd_sub_giat . '">' . $kd_sub_giat1 . '</td>
									<td class="kanan bawah nama">' . $nama_sub . '</td>
									<td class="kanan bawah indikator">' . $output_sub_giat . '</td>
									<td class="text_tengah kanan bawah total_renja target_indikator">' . $target_output_sub_giat . '</td>
									<td class="text_tengah kanan bawah total_renja satuan_indikator">' . $satuan_output_sub_giat . '</td>
									<td class="text_tengah kanan bawah triwulan_1">' . $realisasi_indikator_tw1 . '</td>
									<td class="text_tengah kanan bawah triwulan_1">' . $capaian_anggaran_tw1 . '</td>
									<td class="text_tengah kanan bawah triwulan_2">' . $realisasi_indikator_tw2 . '</td>
									<td class="text_tengah kanan bawah triwulan_2">' . $capaian_anggaran_tw2 . '</td>
									<td class="text_tengah kanan bawah triwulan_3">' . $realisasi_indikator_tw3 . '</td>
									<td class="text_tengah kanan bawah triwulan_3">' . $capaian_anggaran_tw3 . '</td>
									<td class="text_tengah kanan bawah triwulan_4">' . $realisasi_indikator_tw4 . '</td>
									<td class="text_tengah kanan bawah triwulan_4">' . $capaian_anggaran_tw4 . '</td>
									<td class="text_tengah kanan bawah capaian_renja">' . $total_tw . '</td>
									<td class="text_tengah kanan bawah capaian_renja">' . $capaian_realisasi_indikator . '</td>
									<td class="text_tengah kanan bawah capaian_renja">' . $capaian . '</td>
									<td class="kanan bawah"></td>
									<td class="kanan bawah text_blok">' . $pendorong_html . '</td>
									<td class="kanan bawah text_blok">' . $penghambat_html . '</td>
								</tr>
								';
							$rowspan++;
						}
					}
				}
			}
		}
		$monev_triwulan_all = array(
			'1' => array('update_skpd_at' => '', 'keterangan_skpd' => '', 'catatan_verifikator' => '', 'update_verifikator_at' => ''),
			'2' => array('update_skpd_at' => '', 'keterangan_skpd' => '', 'catatan_verifikator' => '', 'update_verifikator_at' => ''),
			'3' => array('update_skpd_at' => '', 'keterangan_skpd' => '', 'catatan_verifikator' => '', 'update_verifikator_at' => ''),
			'4' => array('update_skpd_at' => '', 'keterangan_skpd' => '', 'catatan_verifikator' => '', 'update_verifikator_at' => '')
		);
		foreach ($data_all['catatan_rekomendasi'] as $k => $v) {
			$monev_triwulan_all[$v['triwulan']]['keterangan_skpd'] = $v['keterangan_skpd'];
			$monev_triwulan_all[$v['triwulan']]['catatan_verifikator'] = $v['catatan_verifikator'];
			$monev_triwulan_all[$v['triwulan']]['update_verifikator_at'] = $v['update_verifikator_at'];
			$monev_triwulan_all[$v['triwulan']]['update_skpd_at'] = $v['update_skpd_at'];
		}

		$return = [
			'data' => $body_monev,
			'catatan_data' => $monev_triwulan_all,
		];
		return $return;
	}

	function generate_html_capaian_gabungan_rencana_aksi($data_unit, $tahun_anggaran, $html_renja)
	{
		global $wpdb;
		if (empty($data_unit)) {
			return "<tr><td class='kiri kanan atas bawah text_tengah' colspan='14'>Tidak ada data tersedia</td></tr>";
		}

		$tbody = '';
		$no = 1;

		$all_sasaran = $this->get_renaksi_kepala(
			$data_unit['id_skpd'],
			$tahun_anggaran
		);

		if (empty($all_sasaran)) {
			$tbody .= "
				<tr>
					<td class='kiri kanan atas bawah text_tengah' colspan='12'>Sasaran belum tersedia</td>
				</tr>";
			$no++;
			return $tbody;
		}

		$processed_sasarans = [];
		$skpd_total_rowspan = 0;
		foreach ($all_sasaran as $sasaran) {
			$indikators = $this->get_renaksi_indikator_by_id_renaksi($sasaran['id']);
			$data_renaksi_prog_keg = $this->get_renaksi_parent_to_child_by_parent_id($sasaran['id']);

			$sasaran_rowspan = count($indikators) > 0 ? count($indikators) : 1;

			$processed_sasarans[] = [
				'sasaran_data' => $sasaran,
				'indikators'   => $indikators,
				'program_data' => $data_renaksi_prog_keg,
				'rowspan'      => $sasaran_rowspan,
			];
			$skpd_total_rowspan += $sasaran_rowspan;
		}
		// echo '<pre>';
		// print_r($processed_sasarans);
		// echo '</pre>';
		// die();

		// get data realisasi pagu, program dan fisik wp-sipd
		$capaian_anggaran = '';
		$capaian_program = '';
		$capaian_fisik = '';
		$url_api = get_option(ESAKIP_URL_WPSIPD);
		$api_key = get_option(ESAKIP_APIKEY_WPSIPD);
		if (
			!empty($url_api)
			&& !empty($api_key)
		) {
			$opsi_param = array(
				'action' 			=> 'get_serapan_anggaran_capaian_kinerja',
				'api_key' 			=> $api_key,
				'tahun_anggaran' 	=> $tahun_anggaran,
				'id_skpd' 			=> $data_unit['id_skpd']
			);
			$response = wp_remote_post($url_api, [
				'body' => $opsi_param
			]);

			if (!is_wp_error($response)) {
				$data_wpsipd = json_decode(wp_remote_retrieve_body($response), true);
				if (
					!empty($data_wpsipd)
					&& $data_wpsipd['status'] == 'success'
				) {
					$capaian_anggaran_string = $data_wpsipd['data']['serapan_anggaran']['total'];
					$capaian_anggaran_value = (float)rtrim($capaian_anggaran_string, '%');
					$background_color = $this->get_class_background_by_persentase_capaian($capaian_anggaran_value);

					$capaian_anggaran = '<b>' . ($data_wpsipd['data']['serapan_anggaran']['total'] == '0%' ? '' : $capaian_anggaran_value . '%') . '</b>';
					$capaian_program = '<b>' . ($data_wpsipd['data']['capaian_kinerja']['total'] == '0%' ? '' : $data_wpsipd['data']['capaian_kinerja']['total']) . '</b>';
					$capaian_fisik = '<b>' . ($data_wpsipd['data']['capaian_fisik']['total'] == '0%' ? '' : $data_wpsipd['data']['capaian_fisik']['total']) . '</b>';
				}
			}
		}


		$is_first_row_for_skpd = true;
		$multi_id_indikator = array();
		$multi_id_rhk = array();

		foreach ($processed_sasarans as $proc_sasaran) {
			$sasaran_data   = $proc_sasaran['sasaran_data'];
			$indikators     = $proc_sasaran['indikators'];
			$sasaran_rowspan = $proc_sasaran['rowspan'];
			$program_data = $proc_sasaran['program_data'];

			// jika indikator sasaran kosong
			if (empty($indikators)) {
				$tbody .= "<tr>";
				if ($is_first_row_for_skpd) {
					$is_first_row_for_skpd = false;
				}
				$tbody .= "<td class='kiri kanan atas bawah text_tengah'>" . $no++ . "</td>";
				$tbody .= "<td class='kiri kanan atas bawah text_kiri'>{$sasaran_data['label']}</td>";
				$tbody .= "<td class='kiri kanan atas bawah text_tengah' colspan='8'>Indikator sasaran belum ada</td>";
				continue;
			}

			// jika cascading program sasaran kosong
			if (empty($program_data)) {
				$tbody .= "<tr>";
				if ($is_first_row_for_skpd) {
					$is_first_row_for_skpd = false;
				}
				$tbody .= "<td class='kiri kanan atas bawah text_tengah'>" . $no++ . "</td>";
				$tbody .= "<td class='kiri kanan atas bawah text_kiri'>{$sasaran_data['label']}</td>";
				$tbody .= "<td class='kiri kanan atas bawah text_tengah' colspan='19' rowspan='{$sasaran_rowspan}'>Program Tidak ditemukan</td>";;
				continue;
			}

			$is_first_indicator = true;
			foreach ($indikators as $indikator) {
				$tbody .= "<tr id-skpd-simpeg='" . $data_unit['id_satker_simpeg'] . "' id-indikator='" . $indikator['id'] . "' id-rhk='" . $indikator['id_renaksi'] . "'>";
				$multi_id_indikator[] = $indikator['id'];
				$multi_id_rhk[$indikator['id_renaksi']] = $indikator['id_renaksi'];

				$tbody_2 = "";
				$tbody_catatan = "";
				if ($is_first_row_for_skpd) {

					$title_rumus = $this->get_rumus_capaian_kinerja_tahunan_by_tipe(1);
					$tbody_2 = "
					<td class='kiri kanan atas bawah text_tengah {$background_color}' data-toggle='tooltip' data-placement='top' title='{$title_rumus}' rowspan='{$skpd_total_rowspan}'>{$capaian_anggaran}</td>
					<td class='kiri kanan atas bawah text_tengah' data-toggle='tooltip' data-placement='top' title='{$title_rumus}' rowspan='{$skpd_total_rowspan}'>{$capaian_program}</td>
					<td class='kiri kanan atas bawah text_tengah' data-toggle='tooltip' data-placement='top' title='{$title_rumus}' rowspan='{$skpd_total_rowspan}'>{$capaian_fisik}</td>";

					$catatan_data = $html_renja['catatan_data'];

					$tbody_catatan = "
					<td class='kanan bawah text_blok atas' rowspan='{$skpd_total_rowspan}'>
						<div style='display: flex; flex-direction: column; gap: 6px;'>
							<div style='background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 4px 6px;'>
								<strong>TW 1:</strong><br>{$catatan_data['1']['catatan_verifikator']}
							</div>
							<div style='background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 4px 6px;'>
								<strong>TW 2:</strong><br>{$catatan_data['2']['catatan_verifikator']}
							</div>
							<div style='background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 4px 6px;'>
								<strong>TW 3:</strong><br>{$catatan_data['3']['catatan_verifikator']}
							</div>
							<div style='background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 4px 6px;'>
								<strong>TW 4:</strong><br>{$catatan_data['4']['catatan_verifikator']}
							</div>
						</div>
					</td>";
				}
				$tbody_program = '</tr>';

				// jika indikator sasaran pertama
				if ($is_first_indicator) {

					$tbody .= "<td class='kiri kanan atas bawah text_tengah' rowspan='{$sasaran_rowspan}'>" . $no++ . "</td>";
					$tbody .= "<td class='kiri kanan atas bawah text_kiri' rowspan='{$sasaran_rowspan}'>{$sasaran_data['label']}</td>";

					$program_rows = $this->flatten_program_tree($program_data, $html_renja);
					// echo "<pre>";
					// print_r($program_data);
					// echo "</pre>";
					// die(json_encode($program_data));
					$tbody_program = $program_rows['data'];
				}

				$target_tahunan = (float) str_replace(',', '.', $indikator['target_akhir'] ?? 0);
				$all_realisasi = [
					'realisasi_1' => (float) str_replace(',', '.', $indikator['realisasi_tw_1'] ?? 0),
					'realisasi_2' => (float) str_replace(',', '.', $indikator['realisasi_tw_2'] ?? 0),
					'realisasi_3' => (float) str_replace(',', '.', $indikator['realisasi_tw_3'] ?? 0),
					'realisasi_4' => (float) str_replace(',', '.', $indikator['realisasi_tw_4'] ?? 0)
				];
				$all_target = [
					'target_1' => (float) str_replace(',', '.', $indikator['target_1'] ?? 0),
					'target_2' => (float) str_replace(',', '.', $indikator['target_2'] ?? 0),
					'target_3' => (float) str_replace(',', '.', $indikator['target_3'] ?? 0),
					'target_4' => (float) str_replace(',', '.', $indikator['target_4'] ?? 0)
				];

				$all_realisasi_display = [
					'realisasi_1' => ($indikator['realisasi_tw_1'] === '' || $indikator['realisasi_tw_1'] === null) ? '' : $indikator['realisasi_tw_1'],
					'realisasi_2' => ($indikator['realisasi_tw_2'] === '' || $indikator['realisasi_tw_2'] === null) ? '' : $indikator['realisasi_tw_2'],
					'realisasi_3' => ($indikator['realisasi_tw_3'] === '' || $indikator['realisasi_tw_3'] === null) ? '' : $indikator['realisasi_tw_3'],
					'realisasi_4' => ($indikator['realisasi_tw_4'] === '' || $indikator['realisasi_tw_4'] === null) ? '' : $indikator['realisasi_tw_4']
				];

				$capaian = $this->get_capaian_realisasi_tahunan_by_type(
					(int) $indikator['rumus_capaian_kinerja'],
					(float) $target_tahunan,
					(array) $all_realisasi,
					(int) $indikator['tahun_anggaran']
				);

				$capaian_display = ($capaian === false) ? 'N/A' : $capaian;

				$predikat_capaian = '-';
				if ($capaian != false) {
					$predikat_capaian = $this->get_predikat_capaian($capaian);
					$class_bg_predikat_capaian = $this->get_class_background_by_persentase_capaian($capaian);
				}

				// jika capaian 0 tampilkan kosong.
				$anti_zero_capaian = ($capaian_display == 0) ? '' : '<b>' . $capaian . '%</b>';

				$title_rumus = $this->get_rumus_capaian_kinerja_tahunan_by_tipe((int) $indikator['rumus_capaian_kinerja']);

				$sum_realisasi = $all_realisasi['realisasi_1'] + $all_realisasi['realisasi_2'] + $all_realisasi['realisasi_3'] + $all_realisasi['realisasi_4'];

				$tbody .= "
					<td class='kiri kanan atas bawah text_kiri'>{$indikator['indikator']}</td>
					<td class='kiri kanan atas bawah text_tengah'>{$indikator['satuan']}</td>
					<td class='kiri kanan atas bawah text_tengah'>{$target_tahunan}</td>
					<td class='kiri kanan atas bawah text_tengah'>{$all_target['target_1']}</td>
					<td class='kiri kanan atas bawah text_tengah'>{$all_target['target_2']}</td>
					<td class='kiri kanan atas bawah text_tengah'>{$all_target['target_3']}</td>
					<td class='kiri kanan atas bawah text_tengah'>{$all_target['target_4']}</td>
					<td class='kiri kanan atas bawah text_tengah'>{$all_realisasi_display['realisasi_1']}</td>
					<td class='kiri kanan atas bawah text_tengah'>{$all_realisasi_display['realisasi_2']}</td>
					<td class='kiri kanan atas bawah text_tengah'>{$all_realisasi_display['realisasi_3']}</td>
					<td class='kiri kanan atas bawah text_tengah'>{$all_realisasi_display['realisasi_4']}</td>
					<td class='kiri kanan atas bawah text_tengah'>{$sum_realisasi}</td>
					<td class='kiri kanan atas bawah text_tengah' data-toggle='tooltip' data-placement='top' title='{$title_rumus}'>{$anti_zero_capaian}</td>
					<td class='kiri kanan atas bawah text_tengah text_blok {$class_bg_predikat_capaian}'>{$predikat_capaian}</td>"
					. '<td rowspan="' . $sasaran_rowspan . '" colspan="19" style="padding:0;">
						<table style="margin: 0;">
							<tbody>
					';

				// jika indikator pertama
				if ($is_first_indicator) {
					foreach ($tbody_program as $col) {
						$tbody .= $col;
					}
					$is_first_indicator = false;
				}
				$tbody .= '
							</tbody>
						</table>';

				if ($is_first_row_for_skpd) {
					$tbody .= $tbody_catatan . $tbody_2;
					$is_first_row_for_skpd = false;
				}
				$tbody .= '
					<td>
				</tr>';
			}
		}
		$no++;

		// perlu melakukan singkron data realisasi kinerja
		if (!empty($multi_id_indikator)) {
			$multi_id_rhk = array_values($multi_id_rhk);
			$selected_rhk = $wpdb->get_row(
				$wpdb->prepare("
					SELECT *
					FROM esakip_data_rencana_aksi_opd
					WHERE id = %d
				", $multi_id_rhk[0]),
				ARRAY_A
			);
			$opsi_param = array(
				'tahun' => $tahun_anggaran,
				'satker_id' => $selected_rhk['satker_id'],
				'nip' => $selected_rhk['nip'],
				'id_indikator' => $multi_id_indikator,
				'id_rhk' => $multi_id_rhk,
				'id_skpd' => $data_unit['id_skpd'],
				'tipe' => 'indikator'
			);
			$this->get_data_perbulan_ekinerja($opsi_param);
		}

		// die(var_dump($tbody));
		return $tbody;
	}

	function flatten_program_tree($node, &$rows = []) {
		if (isset($node['data'])) {
			$rows[] = $node['data'];
		}

		// jika child ada
		if (isset($node['child']) && !empty($node['child'])) {
			foreach ($node['child'] as $child) {
				$this->flatten_program_tree($child, $rows);
			}
		}

		return $rows;
	}

	function get_predikat_capaian(float $value)
	{
		if ($value >= 100) {
			return "Sangat Berhasil";
		} elseif ($value >= 75 && $value < 100) {
			return "Berhasil";
		} elseif ($value >= 55 && $value < 75) {
			return "Cukup Berhasil";
		} else {
			return "Kurang Berhasil";
		}
	}


	function pembulatan($angka)
	{
		$angka = $angka * 100;
		return round($angka) / 100;
	}

	function get_table_data_capaian_kinerja_publik_baru()
	{
		if (empty(get_option(ESAKIP_APIKEY_WPSIPD)) && empty(get_option(ESAKIP_URL_WPSIPD))) {
			return [];
		}

		$api_params = array(
			'action' 	=> 'get_data_capaian_kinerja_publik_baru',
			'api_key'	=> get_option(ESAKIP_APIKEY_WPSIPD),
			'id_skpd' => $_POST['id_skpd'],
			'tahun_anggaran' => $_POST['tahun_anggaran']
		);

		$response = wp_remote_post(
			get_option(ESAKIP_URL_WPSIPD),
			array(
				'timeout' 	=> 1000,
				'sslverify' => false,
				'body' 		=> $api_params
			)
		);

		$ret = array(
			'status' => 'success',
			'message' => 'berhasil get data',
			'html' => ''
		);
		// die($response['body']);
		$data_renja_html = $this->generate_html_capaian_gabungan($response['body']);
		$data_skpd = $this->get_data_unit_by_id_skpd_tahun_anggaran($_POST['id_skpd'], $_POST['tahun_anggaran']);
		$html_rencana_aksi = $this->generate_html_capaian_gabungan_rencana_aksi($data_skpd, $_POST['tahun_anggaran'], $data_renja_html);

		if (!empty($html_rencana_aksi)) {
			$ret['html'] = $html_rencana_aksi;
		} else {
			$ret = array(
				'status' => 'error',
				'message' => 'gagal get data',
				'html' => ''
			);
		}
		die(json_encode($ret));
	}

	function get_all_data_pk_pemda_by_tahun_and_id_jadwal(int $tahun_anggaran, int $id_jadwal)
	{
		global $wpdb;

		$data = $wpdb->get_results(
			$wpdb->prepare("
				SELECT 
					pk.*,
					ik.label_sasaran as ik_label_sasaran,
					ik.label_indikator as ik_label_indikator,
					ik.satuan as ik_satuan
				FROM esakip_laporan_pk_pemda pk
				LEFT JOIN esakip_data_iku_pemda ik
					   ON pk.id_iku = ik.id 
					  AND pk.id_jadwal = ik.id_jadwal
				WHERE pk.active = 1
				  AND pk.tahun_anggaran = %d
				  AND pk.id_jadwal = %d
			", $tahun_anggaran, $id_jadwal),
			ARRAY_A
		);

		return $data;
	}

	function hapus_sasaran_pk()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil menghapus data!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID kosong!';
					die(json_encode($ret));
				}

				$cek_id = $wpdb->get_row($wpdb->prepare("
	                SELECT 
	                	* 
	                FROM esakip_laporan_pk_pemda 
	                WHERE id = %d 
	                	AND active = 1 
	                	AND (id_iku IS NULL OR id_iku = '' OR id_iku = 0)
	            ", $_POST['id']));

				// Validasi cek apakah PK ini digunakan di renaksi opd
				if (!empty($cek_id)) {
					$cek_koneksi = $wpdb->get_row($wpdb->prepare("
	                    SELECT 
	                    	r.*,
	                    	l.* 
	                    FROM esakip_detail_rencana_aksi_pemda AS r
	                    LEFT JOIN esakip_data_label_rencana_aksi AS l
	                        ON l.parent_renaksi_pemda = r.id 
	                        AND l.active = r.active 
	                    WHERE r.id_pk = %d
	                        AND l.active = 1
	                ", $_POST['id']));

					if (!empty($cek_koneksi)) {
						$ret['status'] = 'error';
						$ret['message'] = 'Data ini tidak bisa dihapus dikarenakan sudah terkoneksi dengan rencana aksi perangkat daerah!';
						die(json_encode($ret));
					} else {
						$wpdb->update(
							'esakip_laporan_pk_pemda',
							array('active' => 0),
							array('id' => $_POST['id'])
						);
					}
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Data tidak ditemukan!';
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

	function get_table_rencana_aksi_pemda_baru()
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
				} elseif ($ret['status'] != 'error' && empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Jadwal kosong!';
				}
				if ($ret['status'] != 'error') {
					$data = $wpdb->get_results(
						$wpdb->prepare("
					        SELECT 
					            pk.*,
					            ik.label_sasaran as ik_label_sasaran,
					            ik.label_indikator as ik_label_indikator
					        FROM esakip_laporan_pk_pemda pk
					        LEFT JOIN esakip_data_iku_pemda ik
					            ON pk.id_iku = ik.id 
					            AND pk.id_jadwal = ik.id_jadwal
					        WHERE pk.active = 1
					            AND pk.tahun_anggaran = %d
					            AND pk.id_jadwal = %d
					        ORDER BY pk.id ASC
					    ", $_POST['tahun_anggaran'], $_POST['id_jadwal']),
						ARRAY_A
					);
					$html = '';
					$no = 0;

					$no = 0;
					foreach ($data as $v) {
						$data_renaksi_detail = $wpdb->get_results($wpdb->prepare("
					        SELECT 
					            r.*,
					            l.id AS id_label,
					            l.id_skpd AS id_skpd_label,
					            l.parent_renaksi_opd
					        FROM esakip_detail_rencana_aksi_pemda AS r
					        LEFT JOIN esakip_data_label_rencana_aksi AS l
					            ON l.parent_renaksi_pemda = r.id 
					            AND l.active = r.active 
					            AND l.tahun_anggaran = r.tahun_anggaran 
					        WHERE r.active = 1
					            AND r.id_pk = %d
					            AND r.tahun_anggaran = %d
					    ", $v['id'], $v['tahun_anggaran']), ARRAY_A);

						$group_by_skpd = [];

						if (!empty($data_renaksi_detail)) {
							foreach ($data_renaksi_detail as $vv) {
								$id_skpd = $vv['id_skpd'];
								$id_skpd_label = $vv['id_skpd_label'];

								$nama_skpd = $wpdb->get_var($wpdb->prepare("
					                SELECT nama_skpd 
					                FROM esakip_data_unit 
					                WHERE tahun_anggaran = %d 
					                    AND id_skpd = %d 
					                    AND active = 1
					            ", $vv['tahun_anggaran'], $id_skpd)) ?? '';

								$get_renaksi_opd = $wpdb->get_results($wpdb->prepare("
					                SELECT 
					                    label,
					                    kode_cascading_program,
					                    label_cascading_program,
					                    pagu_cascading
					                FROM esakip_data_rencana_aksi_opd
					                WHERE active = 1
					                    AND id = %d
					                ORDER BY id ASC
					            ", $vv['parent_renaksi_opd']), ARRAY_A);

								$group_by_skpd[$id_skpd]['nama_skpd'] = $nama_skpd;
								$group_by_skpd[$id_skpd]['id_skpd_label'] = $id_skpd_label;

								if (!empty($get_renaksi_opd)) {
									foreach ($get_renaksi_opd as $renaksi) {
										$group_by_skpd[$id_skpd]['renaksi'][] = [
											'label' => $renaksi['label'],
											'label_cascading_program' => $renaksi['label_cascading_program'],
											'kode_cascading_program' => $renaksi['kode_cascading_program'],
											'pagu_cascading' => $renaksi['pagu_cascading']
										];
									}
								} else {
									$group_by_skpd[$id_skpd]['renaksi'][] = [
										'label' => '',
										'label_cascading_program' => '',
										'kode_cascading_program' => '',
										'pagu_cascading' => 0
									];
								}
							}
						} else {
							$group_by_skpd[0] = [
								'nama_skpd' => '',
								'id_skpd_label' => null,
								'renaksi' => [[
									'label' => '',
									'label_cascading_program' => '',
									'kode_cascading_program' => '',
									'pagu_cascading' => 0
								]]
							];
						}

						$rowspan_total = 0;
						foreach ($group_by_skpd as $gr) {
							$rowspan_total += count($gr['renaksi']);
						}

						$row_index = 0;
						$no++;

						foreach ($group_by_skpd as $id_skpd => $group) {
							$jumlah_renaksi = count($group['renaksi']);
							$bg = !empty($group['nama_skpd']) && !($group['id_skpd_label'] == $id_skpd && $id_skpd != 0);

							foreach ($group['renaksi'] as $i => $renaksi) {
								$html .= '<tr>';


								if (!empty($v['id_iku']) && !empty($v['ik_label_sasaran'])) {
									$label_sasaran = $v['ik_label_sasaran'];
									$label_indikator = $v['ik_label_indikator'];
								} else {
									$label_sasaran = $v['label_sasaran'] ?? '';
									$label_indikator = $v['label_indikator'] ?? '';
								}

								if ($row_index === 0) {
									$html .= '<td style="border: 1px solid black; text-align: center;" rowspan="' . $rowspan_total . '">' . $no . '</td>';
									$html .= '<td style="border: 1px solid black;" rowspan="' . $rowspan_total . '">' . $label_sasaran . '</td>';
									$html .= '<td style="border: 1px solid black;" rowspan="' . $rowspan_total . '">' . $label_indikator . '</td>';
								}

								if ($i === 0) {
									$style_bg = $bg ? "background-color: #ff00002e;" : "";
									$html .= '<td style="border: 1px solid black; ' . $style_bg . '" rowspan="' . $jumlah_renaksi . '">' . $group['nama_skpd'] . '</td>';
								}

								$html .= '<td style="border: 1px solid black;">' . $renaksi['label'] . '</td>';
								$html .= '<td style="border: 1px solid black;">' . $renaksi['kode_cascading_program'] . ' ' . $renaksi['label_cascading_program'] . '</td>';
								$html .= '<td style="border: 1px solid black; text-align: right;">' . number_format((float)$renaksi['pagu_cascading'], 0, ",", ".") . '</td>';

								$html .= '</tr>';
								$row_index++;
							}
						}
					}

					if (empty($html)) {
						$html = '<tr><td class="text-center" colspan="7">Data masih kosong!</td></tr>';
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

	public function get_data_renaksi_pemda_baru()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
						$ret['status'] = 'error';
						$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
					} elseif ($ret['status'] != 'error' && empty($_POST['id_jadwal'])) {
						$ret['status'] = 'error';
						$ret['message'] = 'ID Jadwal kosong!';
					}

					$data_renaksi = $wpdb->get_results($wpdb->prepare("
						SELECT 
							pk.*,
							ik.label_sasaran as ik_label_sasaran,
							ik.label_indikator as ik_label_indikator
						FROM esakip_laporan_pk_pemda pk
						LEFT JOIN esakip_data_iku_pemda ik
							ON pk.id_iku = ik.id 
							AND pk.id_jadwal = ik.id_jadwal
						WHERE pk.active = 1
							AND pk.tahun_anggaran = %d
							AND pk.id_jadwal = %d
						ORDER BY pk.id ASC
					", $_POST['tahun_anggaran'], $_POST['id_jadwal']), ARRAY_A);

					foreach ($data_renaksi as $i => $val) {
						$data_renaksi[$i]['renaksi_opd'] = [];

						$detail = $wpdb->get_results($wpdb->prepare("
							SELECT 
								r.*,
								l.id AS id_label,
								l.id_skpd AS id_skpd_label,
								l.parent_renaksi_opd
							FROM esakip_detail_rencana_aksi_pemda AS r
							LEFT JOIN esakip_data_label_rencana_aksi AS l
								ON l.parent_renaksi_pemda = r.id 
								AND l.active = r.active 
							WHERE r.active = 1
								AND r.id_pk = %d
								AND r.tahun_anggaran = %d
						", $val['id'], $val['tahun_anggaran']), ARRAY_A);

						if (empty($detail)) {
							$data_renaksi[$i]['renaksi_opd'][] = [
								'label' => '',
								'label_cascading_program' => '',
								'pagu_cascading' => 0,
								'id_skpd' => 0,
								'id_skpd_label' => null,
								'nama_skpd' => ''
							];
							continue;
						}

						foreach ($detail as $d) {
							$get_renaksi_opd = $wpdb->get_results($wpdb->prepare("
								SELECT 
									label,
									label_cascading_program,
									pagu_cascading
								FROM esakip_data_rencana_aksi_opd
								WHERE active = 1
									AND id = %d
							", $d['parent_renaksi_opd']), ARRAY_A);

							$nama_skpd = $wpdb->get_var($wpdb->prepare("
								SELECT nama_skpd
								FROM esakip_data_unit 
								WHERE tahun_anggaran = %d 
									AND id_skpd = %d 
									AND active = 1
							", $val['tahun_anggaran'], $d['id_skpd'])) ?? '';

							if (!empty($get_renaksi_opd)) {
								foreach ($get_renaksi_opd as $r) {
									$data_renaksi[$i]['renaksi_opd'][] = [
										'label' => $r['label'],
										'label_cascading_program' => $r['label_cascading_program'],
										'pagu_cascading' => $r['pagu_cascading'],
										'id_skpd' => $d['id_skpd'],
										'id_skpd_label' => $d['id_skpd_label'],
										'nama_skpd' => $nama_skpd,
									];
								}
							} else {
								$data_renaksi[$i]['renaksi_opd'][] = [
									'label' => '',
									'label_cascading_program' => '',
									'pagu_cascading' => 0,
									'id_skpd' => $d['id_skpd'],
									'id_skpd_label' => $d['id_skpd_label'],
									'nama_skpd' => $nama_skpd,
								];
							}
						}
					}

					die(json_encode([
						'status' => true,
						'data' => $data_renaksi
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

	function get_edit_data_renaksi_pemda_baru()
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
				} else if ($ret['status'] != 'error' && empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Jadwal kosong!';
				}
				if ($ret['status'] != 'error') {
					$ret['data'] = $wpdb->get_row(
						$wpdb->prepare("
					        SELECT 
					            pk.*,
					            ik.label_sasaran as ik_label_sasaran,
					            ik.label_indikator as ik_label_indikator
					        FROM esakip_laporan_pk_pemda pk
					        LEFT JOIN esakip_data_iku_pemda ik
					            ON pk.id_iku = ik.id 
					            AND pk.id_jadwal = ik.id_jadwal
					        WHERE pk.active = 1
					            AND pk.id = %d
					            AND pk.tahun_anggaran = %d
					            AND pk.id_jadwal = %d
					    ", $_POST['id'], $_POST['tahun_anggaran'], $_POST['id_jadwal']),
						ARRAY_A
					);


					if (!empty($ret['data'])) {
						$ret['data']['renaksi'] = $wpdb->get_results(
							$wpdb->prepare("
							SELECT 
					            *
					        FROM esakip_detail_rencana_aksi_pemda 
					        WHERE active = 1
					            AND id_pk = %d
					            AND tahun_anggaran = %d
					            AND id_jadwal = %d
					    ", $ret['data']['id'], $ret['data']['tahun_anggaran'], $ret['data']['id_jadwal']),
							ARRAY_A
						);
						if (!empty($ret['data']['renaksi'])) {
							$id_skpd = array();
							foreach ($ret['data']['renaksi'] as $renaksi_item) {
								if (!empty($renaksi_item['id_skpd'])) {
									$id_skpd[] = intval($renaksi_item['id_skpd']);
								}
							}

							$id_skpd = $id_skpd;

							$ret['data']['all_skpd'] = $wpdb->get_results($wpdb->prepare('
						        SELECT 
						        	id_skpd, 
						        	nama_skpd
						        FROM esakip_data_unit
						        WHERE tahun_anggaran = %d
						            AND active = 1
						        ORDER BY kode_skpd ASC
						    ', $ret['data']['tahun_anggaran']), ARRAY_A);

							if (!empty($id_skpd)) {
								$id_placeholders = implode(',', array_fill(0, count($id_skpd), '%d'));
								$get_skpd = "
						            SELECT 
						            	id_skpd, 
						            	nama_skpd
						            FROM esakip_data_unit
						            WHERE tahun_anggaran = %d
						                AND id_skpd IN ($id_placeholders)
						                AND active = 1
						            ORDER BY kode_skpd ASC
						        ";
								$where = array_merge(array($ret['data']['tahun_anggaran']), $id_skpd);
								$ret['data']['skpd'] = $wpdb->get_results($wpdb->prepare($get_skpd, $where), ARRAY_A);
							} else {
								$ret['data']['skpd'] = array();
							}
						} else {
							$ret['data']['all_skpd'] = $wpdb->get_results($wpdb->prepare('
						        SELECT 
						        	id_skpd, 
						        	nama_skpd
						        FROM esakip_data_unit
						        WHERE tahun_anggaran = %d
						            AND active = 1
						        ORDER BY kode_skpd ASC
						    ', $ret['data']['tahun_anggaran']), ARRAY_A);
							$ret['data']['skpd'] = array();
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

	function submit_edit_renaksi_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan data PK!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

				if ($ret['status'] != 'error' && empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				$id_pk = intval($_POST['id']);
				$id_skpd_baru = isset($_POST['id_skpd']) ? $_POST['id_skpd'] : [];

				if ($ret['status'] != 'error') {
					$data_pk = $wpdb->get_row($wpdb->prepare("
						SELECT tahun_anggaran, 
							id_jadwal
						FROM esakip_laporan_pk_pemda
						WHERE id = %d 
							AND active = 1
					", $id_pk), ARRAY_A);

					if ($data_pk) {
						$skpd_lama_data = $wpdb->get_results($wpdb->prepare("
							SELECT id_skpd
							FROM esakip_detail_rencana_aksi_pemda
							WHERE id_pk = %d 
								AND active = 1
						", $id_pk), ARRAY_A);

						$skpd_lama = array();
						foreach ($skpd_lama_data as $item) {
							$skpd_lama[] = intval($item['id_skpd']);
						}

						// Nonaktifkan SKPD yang sudah tidak dipilih
						foreach ($skpd_lama as $id_lama) {
							if (!in_array($id_lama, $id_skpd_baru)) {
								$wpdb->update(
									'esakip_detail_rencana_aksi_pemda',
									array('active' => 0),
									array(
										'id_pk' => $id_pk,
										'id_skpd' => $id_lama
									)
								);
							}
						}

						// Aktifkan kembali yang sudah pernah disimpan atau insert baru
						foreach ($id_skpd_baru as $id_baru) {
							$cek = $wpdb->get_var($wpdb->prepare("
								SELECT COUNT(*) 
									FROM esakip_detail_rencana_aksi_pemda
								WHERE id_pk = %d 
									AND id_skpd = %d
							", $id_pk, $id_baru));

							if ($cek > 0) {
								// Sudah ada, aktifkan kembali
								$wpdb->update(
									'esakip_detail_rencana_aksi_pemda',
									array('active' => 1),
									array(
										'id_pk' => $id_pk,
										'id_skpd' => $id_baru
									)
								);
							} else {
								// Belum ada, insert baru
								$wpdb->insert('esakip_detail_rencana_aksi_pemda', array(
									'id_pk' => $id_pk,
									'id_skpd' => intval($id_baru),
									'tahun_anggaran' => intval($data_pk['tahun_anggaran']),
									'id_jadwal' => intval($data_pk['id_jadwal']),
									'active' => 1
								));
							}
						}

						$ret['message'] = 'Data berhasil disimpan.';
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Data PK tidak ditemukan.';
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

	public function get_data_iku_all()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = '';
					if (!empty($_POST['tipe_iku'])) {
						if (!empty($_POST['id_skpd'])) {
							$id_skpd = $_POST['id_skpd'];

							$_prefix_opd = $_POST['tipe_iku'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_iku'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					if ($_prefix_opd == '') {
						$data_iku = $wpdb->get_results($wpdb->prepare("
							SELECT
								*
							FROM esakip_data_iku_pemda
							WHERE id_jadwal=%d
								AND active=1
						", 0), ARRAY_A);
					} else if ($_prefix_opd == '_opd') {
						$data_iku = $wpdb->get_results($wpdb->prepare("
							SELECT
								*
							FROM esakip_data_iku_opd
							WHERE id_skpd=%d
								AND id_jadwal_wpsipd=%d
								AND active=1
						", $_POST['id_skpd'], $_POST['id_jadwal']), ARRAY_A);
					}

					$data = [
						'data' => []
					];
					foreach ($data_iku as $key => $iku) {
						if (empty($data['data'][$iku['id']])) {
							$data['data'][$iku['id']] = [
								'id' => $iku['id'],
								'kode_sasaran' => $iku['kode_sasaran'],
								'id_unik_indikator' => $iku['id_unik_indikator'],
								// pemda
								'id_sasaran' => $iku['id_sasaran'],
								'target_1' => $iku['target_1'] ? $iku['target_1'] : '',
								'target_2' => $iku['target_2'] ? $iku['target_2'] : '',
								'target_3' => $iku['target_3'] ? $iku['target_3'] : '',
								'target_4' => $iku['target_4'] ? $iku['target_4'] : '',
								'target_5' => $iku['target_5'] ? $iku['target_5'] : '',
								'realisasi_1' => $iku['realisasi_1'] ? $iku['realisasi_1'] : '',
								'realisasi_2' => $iku['realisasi_2'] ? $iku['realisasi_2'] : '',
								'realisasi_3' => $iku['realisasi_3'] ? $iku['realisasi_3'] : '',
								'realisasi_4' => $iku['realisasi_4'] ? $iku['realisasi_4'] : '',
								'realisasi_5' => $iku['realisasi_5'] ? $iku['realisasi_5'] : '',
							];
						}
					}

					echo json_encode([
						'status' => true,
						'data' => array_values($data['data']),
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
}
