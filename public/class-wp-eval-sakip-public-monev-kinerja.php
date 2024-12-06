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
							$_prefix_opd = $_POST['tipe_pokin'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_pokin'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					if ($_prefix_opd == '') {
						$data_renaksi = array();
					} else if ($_prefix_opd == '_opd') {
						$data_renaksi = $wpdb->get_results($wpdb->prepare("
							SELECT 
								a.*
							FROM esakip_data_rencana_aksi_opd a
							WHERE 
								a.parent=%d AND 
								a.level=%d AND 
								a.active=%d AND 
								a.id_skpd=%d
							ORDER BY a.id
						",
						$_POST['parent'],
						$_POST['level'],
						1,
						$id_skpd
						), ARRAY_A);


					}

					foreach($data_renaksi as $key => $val){
						$data_renaksi[$key]['get_data_dasar_4'] = $wpdb->get_results($wpdb->prepare("
					        SELECT
					            *
					        FROM esakip_data_rencana_aksi_opd
					        WHERE parent=%d
					          AND level=4
					          AND active=1
					    ", $val['id']));
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
					    }
					    $data_renaksi[$key]['get_dasar_2'] = $wpdb->get_results($wpdb->prepare("
						    SELECT *
						    FROM esakip_data_rencana_aksi_opd
						    WHERE parent=%d
						      AND level=2
						      AND active=1
						", $val['id']));

						if (!empty($data_renaksi[$key]['get_dasar_2'])) {
						    foreach ($data_renaksi[$key]['get_dasar_2'] as $key2 => $val2) {
						        $data_renaksi[$key]['get_dasar_2'][$key2]->get_dasar_to_level_3 = $wpdb->get_results($wpdb->prepare("
						            SELECT *
						            FROM esakip_data_rencana_aksi_opd
						            WHERE parent=%d
						              AND level=3
						              AND active=1
						        ", $val2->id));

						        if (!empty($data_renaksi[$key]['get_dasar_2'][$key2]->get_dasar_to_level_3)) {
						            foreach ($data_renaksi[$key]['get_dasar_2'][$key2]->get_dasar_to_level_3 as $key3 => $val3) {
						                $data_renaksi[$key]['get_dasar_2'][$key2]->get_dasar_to_level_3[$key3]->get_dasar_to_level_4 = $wpdb->get_results($wpdb->prepare("
						                    SELECT *
						                    FROM esakip_data_rencana_aksi_opd
						                    WHERE parent=%d
						                      AND level=4
						                      AND active=1
						                ", $val3->id));
						            }
						        }
						    }
						}

						$data_renaksi[$key]['indikator'] = $wpdb->get_results($wpdb->prepare("
							SELECT
								i.*,
								o.satuan_bulan,
								o.tahun_anggaran,
								o.id_skpd
							FROM esakip_data_rencana_aksi_indikator_opd AS i
							LEFT JOIN esakip_data_bulanan_rencana_aksi_opd AS o
								ON i.id = o.id_indikator_renaksi_opd
								AND i.id_skpd = o.id_skpd
								AND i.active = o.active
								AND i.tahun_anggaran = o.tahun_anggaran
							WHERE i.id_renaksi=%d
								AND i.active = 1
						", $val['id']));
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
					if(!empty($label_parent)){
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
				} else if ($ret['status'] != 'error' && empty($_POST['label_renaksi'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Kegiatan Utama tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['level'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe tidak boleh kosong!';
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


	            $get_dasar_pelaksanaan = $_POST['get_dasar_pelaksanaan'];
				if(!empty($label_cascading_renstra) && $_POST['level'] != 1){
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
				if ($ret['status'] != 'error'){
					$data = array(
						'label' => $_POST['label_renaksi'],
						'id_skpd' => $_POST['id_skpd'],
						'id_jadwal' => $_POST['id_jadwal'],
						'level' => $_POST['level'],
						'active' => 1,
						'tahun_anggaran' => $_POST['tahun_anggaran'],
						'created_at' => current_time('mysql'),
		                'mandatori_pusat' => isset($get_dasar_pelaksanaan['mandatori_pusat']) ? $get_dasar_pelaksanaan['mandatori_pusat'] : 0,
		                'inisiatif_kd' => isset($get_dasar_pelaksanaan['inisiatif_kd']) ? $get_dasar_pelaksanaan['inisiatif_kd'] : 0,
		                'musrembang' => isset($get_dasar_pelaksanaan['musrembang']) ? $get_dasar_pelaksanaan['musrembang'] : 0,
		                'pokir' => isset($get_dasar_pelaksanaan['pokir']) ? $get_dasar_pelaksanaan['pokir'] : 0
					);
					if($_POST['level'] == 1){
						$data['id_pokin_1'] = $_POST['id_pokin_1'];
						$data['id_pokin_2'] = $_POST['id_pokin_2'];
						$data['label_pokin_1'] = $_POST['label_pokin_1'];
						$data['label_pokin_2'] = $_POST['label_pokin_2'];
						$data['kode_cascading_sasaran'] = $kode_cascading_renstra;
						$data['label_cascading_sasaran'] = $label_cascading_renstra;
					}else if($_POST['level'] == 2){
						$data['parent'] = $_POST['parent'];
						$data['id_pokin_3'] = $_POST['id_pokin_1'];
						$data['label_pokin_3'] = $_POST['label_pokin_1'];
						$data['kode_cascading_program'] = $kode_cascading_renstra;
						$data['label_cascading_program'] = $label_cascading_renstra;
					}else if($_POST['level'] == 3){
						$data['parent'] = $_POST['parent'];
						$data['id_pokin_4'] = $_POST['id_pokin_1'];
						$data['label_pokin_4'] = $_POST['label_pokin_1'];
						$data['kode_cascading_kegiatan'] = $kode_cascading_renstra;
						$data['label_cascading_kegiatan'] = $label_cascading_renstra;
					}else if($_POST['level'] == 4){
						$data['parent'] = $_POST['parent'];
						$data['id_pokin_5'] = $_POST['id_pokin_1'];
						$data['label_pokin_5'] = $_POST['label_pokin_1'];
						$data['kode_cascading_sub_kegiatan'] = $kode_cascading_renstra;
						$data['label_cascading_sub_kegiatan'] = $label_cascading_renstra;
						$data['kode_sbl'] = $_POST['kode_sbl'];
					}
					if(!empty($_POST['id'])){
						$cek_id = $_POST['id'];
					}else{
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_opd
							WHERE label=%s
								AND active=0
								AND tahun_anggaran=%d
								AND id_skpd=%d
								AND id_pokin_2=%d
						", $_POST['kegiatan_utama'], $_POST['tahun_anggaran'], $_POST['id_skpd'], $_POST['id_pokin_2']));
					}
					if(empty($cek_id)){
						$wpdb->insert('esakip_data_rencana_aksi_opd', $data);
						$cek_id = $wpdb->insert_id;
					}else{
						$wpdb->update('esakip_data_rencana_aksi_opd', $data, array('id' => $cek_id));
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
							",$_POST['id_skpd'], $_POST['tahun_anggaran'], $cek_id, $ceklist['id_data_renaksi_pemda'], $ceklist['id_indikator']));
	                    	if(empty($cek)){
		                        $wpdb->insert('esakip_data_label_rencana_aksi', array(
		                            'parent_renaksi_pemda' => $checklist['id_data_renaksi_pemda'],
		                            'parent_indikator_renaksi_pemda' => $checklist['id_data_indikator'],
		                            'parent_renaksi_opd' => $cek_id,
		                            'tahun_anggaran' => $_POST['tahun_anggaran'],
		                            'id_skpd' => $_POST['id_skpd'],
		                            'active' => 1,
		                        ));
	                    	}else{
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


	function get_rencana_aksi(){
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

	                if(!empty($ret['data'])){
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
		            }else{
		            	$ret['data']['renaksi_pemda'] = array();
		            }

	                // print_r($ret['data']); die($wpdb->last_query);

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

	function get_indikator_rencana_aksi(){
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
				if ($ret['status'] != 'error'){
					$ret['data'] = $wpdb->get_row($wpdb->prepare('
						SELECT
							*
						FROM esakip_data_rencana_aksi_indikator_opd
						WHERE id=%d
							AND tahun_anggaran=%d
					',$_POST['id'], $_POST['tahun_anggaran']), ARRAY_A);
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

	function hapus_indikator_rencana_aksi(){
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
				if ($ret['status'] != 'error'){
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

	function hapus_rencana_aksi(){
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
				$child_level = $_POST['tipe']+1;
				if ($ret['status'] != 'error'){
					$cek_child = $wpdb->get_results($wpdb->prepare("
						SELECT
							*
						FROM esakip_data_rencana_aksi_opd
						WHERE level=%d
							AND parent=%d
							AND active=1
					", $child_level, $_POST['id']), ARRAY_A);
					if(empty($cek_child)){
						$wpdb->update('esakip_data_rencana_aksi_opd', array(
							'active' => 0
						), array('id' => $_POST['id']));
					}else{
						$ret['status'] = 'error';
						$ret['child'] = $cek_child;
						$ret['message'] = 'Gagal menghapus, data di level data di level '. $child_level .' harus dihapus dahulu!';
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

	function create_indikator_renaksi(){
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
				if ($ret['status'] != 'error'){
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
					);
					if(empty($_POST['id_label_indikator'])){
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_indikator_opd
							WHERE indikator=%s
								AND active=0
								AND tahun_anggaran=%d
								AND id_skpd=%d
						", $_POST['indikator'], $_POST['tahun_anggaran'], $_POST['id_skpd']));
					}else{
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_indikator_opd
							WHERE id=%d
						", $_POST['id_label_indikator']));
						$ret['message'] = "Berhasil edit indikator!";
					}
					if(empty($cek_id)){
						$wpdb->insert('esakip_data_rencana_aksi_indikator_opd', $data);
					}else{
						$wpdb->update('esakip_data_rencana_aksi_indikator_opd', $data, array('id' => $cek_id));
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
							AND level=1
					", $_POST['id_skpd'], $_POST['tahun_anggaran']), ARRAY_A);
					$html = '';
					$data_all = array(
						'total' => 0,
						'data' => array()
					);
					// kegiatan utama
					foreach($data as $v){
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
						foreach($data2 as $v2){
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
							foreach($data3 as $v3){
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
								foreach($data4 as $v4){
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
					foreach($data_all['data'] as $v){
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
						foreach($v['indikator'] as $key => $ind){
							$indikator_html[$key] = $ind['indikator'];
							$satuan_html[$key] = $ind['satuan'];
							$target_awal_html[$key] = $ind['target_awal'];
							$target_akhir_html[$key] = $ind['target_akhir'];
							$target_1_html[$key] = $ind['target_1'];
							$target_2_html[$key] = $ind['target_2'];
							$target_3_html[$key] = $ind['target_3'];
							$target_4_html[$key] = $ind['target_4'];
							$rencana_pagu_html[$key] = !empty($ind['rencana_pagu']) ? $ind['rencana_pagu'] : 0;
							$realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
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
						$html .= '
						<tr class="keg-utama">
							<td>'.$no.'</td>
							<td class="kiri kanan bawah text_blok kegiatan_utama"><span class="badge bg-success text-white">'.$v['detail']['label_pokin_2'].'</span><br>'.$v['detail']['label'].'</td>
							<td class="kiri kanan bawah text_blok indikator_kegiatan_utama">'.$indikator_html.'</td>
							<td class="kiri kanan bawah text_blok recana_aksi"></td>
							<td class="indikator_renaksi"></td>
							<td class="urian_renaksi"></td>
							<td class="indikator_uraian_renaksi"></td>
							<td class="uraian_teknis_kegiatan"></td>
							<td class="indikator_uraian_teknis_kegiatan"></td>
							<td class="text-center satuan_renaksi">'.$satuan_html.'</td>
							<td class="text-center target_awal_urian_renaksi">'.$target_awal_html.'</td>
							<td class="text-center target_tw1_urian_renaksi">'.$target_1_html.'</td>
							<td class="text-center target_tw2_urian_renaksi">'.$target_2_html.'</td>
							<td class="text-center target_tw3_urian_renaksi">'.$target_3_html.'</td>
							<td class="text-center target_tw4_urian_renaksi">'.$target_4_html.'</td>
							<td class="text-center target_akhir_urian_renaksi">'.$target_akhir_html.'</td>
							<td class=""></td>
							<td class=""></td>
							<td class=""></td>
							<td class=""></td>
							<td class=""></td>
							<td class=""></td>
							<td class=""></td>
							<td class=""></td>
							<td class=""></td>
							<td class=""></td>
							<td class=""></td>
							<td class=""></td>
							<td class="anggaran_urian_renaksi"></td>
						</tr>
						';

						foreach($v['data'] as $renaksi){
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
							foreach($renaksi['indikator'] as $key => $ind){
								$indikator_html[$key] = $ind['indikator'];
								$satuan_html[$key] = $ind['satuan'];
								$target_awal_html[$key] = $ind['target_awal'];
								$target_akhir_html[$key] = $ind['target_akhir'];
								$target_1_html[$key] = $ind['target_1'];
								$target_2_html[$key] = $ind['target_2'];
								$target_3_html[$key] = $ind['target_3'];
								$target_4_html[$key] = $ind['target_4'];
								$rencana_pagu_html[$key] = !empty($ind['rencana_pagu']) ? $ind['rencana_pagu'] : 0;
								$realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
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
					    	// print_r($get_renaksi_pemda); die($wpdb->last_query);
                            // print_r($get_renaksi_pemda); die($wpdb->last_query);
                            $renaksi_html = '';
                            $indikator_renaksi_html = '';
                            $satuan_renaksi_html = '';
                            $target_renaksi_html = '';
                            $target_awal_renaksi_html = '';
                            $target_1_renaksi_html = '';
                            $target_2_renaksi_html = '';
                            $target_3_renaksi_html = '';
                            $target_4_renaksi_html = '';
                            if(!empty($get_renaksi_pemda)){
                                foreach($get_renaksi_pemda as $renaksi_pemda){
                                    // $label_renaksi_pemda = $renaksi_pemda['label'];
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
                                    $renaksi_html .= '<span class="badge '.$background_primary.' text-white text-center" style="margin: auto;">' . $label_renaksi_pemda . '</span>';
                                    $renaksi_html .= '</div>';

                                    $background_primary = !empty($renaksi_pemda['indikator']) ? 'bg-primary' : '';
                                    $indikator_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
                                    $indikator_renaksi_html .= '<span class="badge '.$background_primary.' text-white text-center" style="margin: auto;">' . $label_indikator_renaksi_pemda . '</span>';
                                    $indikator_renaksi_html .= '</div>';

                                    $background_primary = !empty($renaksi_pemda['satuan']) ? 'bg-primary' : '';
                                    $satuan_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
                                    $satuan_renaksi_html .= '<span class="badge '.$background_primary.' text-white text-center" style="margin: auto;">' . $label_satuan_renaksi_pemda . '</span>';
                                    $satuan_renaksi_html .= '</div>';

                                    $background_primary = !empty($renaksi_pemda['target_akhir']) ? 'bg-primary' : '';
                                    $target_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
                                    $target_renaksi_html .= '<span class="badge '.$background_primary.' text-white text-center" style="margin: auto;">' . $label_target_renaksi_pemda . '</span>';
                                    $target_renaksi_html .= '</div>';

                                    $background_primary = !empty($renaksi_pemda['target_awal']) ? 'bg-primary' : '';
                                    $target_awal_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
                                    $target_awal_renaksi_html .= '<span class="badge '.$background_primary.' text-white text-center" style="margin: auto;">' . $label_target_awal_renaksi_pemda . '</span>';
                                    $target_awal_renaksi_html .= '</div>';

                                    $background_primary = !empty($renaksi_pemda['target_1']) ? 'bg-primary' : '';
                                    $target_1_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
                                    $target_1_renaksi_html .= '<span class="badge '.$background_primary.' text-white text-center" style="margin: auto;">' . $label_target_1_renaksi_pemda . '</span>';
                                    $target_1_renaksi_html .= '</div>';

                                    $background_primary = !empty($renaksi_pemda['target_2']) ? 'bg-primary' : '';
                                    $target_2_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
                                    $target_2_renaksi_html .= '<span class="badge '.$background_primary.' text-white text-center" style="margin: auto;">' . $label_target_2_renaksi_pemda . '</span>';
                                    $target_2_renaksi_html .= '</div>';

                                    $background_primary = !empty($renaksi_pemda['target_3']) ? 'bg-primary' : '';
                                    $target_3_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
                                    $target_3_renaksi_html .= '<span class="badge '.$background_primary.' text-white text-center" style="margin: auto;">' . $label_target_3_renaksi_pemda . '</span>';
                                    $target_3_renaksi_html .= '</div>';

                                    $background_primary = !empty($renaksi_pemda['target_4']) ? 'bg-primary' : '';
                                    $target_4_renaksi_html .= '<div class="d-flex align-items-center mb-2">';
                                    $target_4_renaksi_html .= '<span class="badge '.$background_primary.' text-white text-center" style="margin: auto;">' . $label_target_4_renaksi_pemda . '</span>';
                                    $target_4_renaksi_html .= '</div>';
                                }
                            }

							$html .= '
							    <tr class="re-naksi">
							        <td>'.$no.'.'.$no_renaksi.'</td>
							        <td class="kiri kanan bawah text_blok kegiatan_utama"></td>
							        <td class="kiri kanan bawah text_blok indikator_kegiatan_utama"></td>
							        <td class="kiri kanan bawah text_blok recana_aksi">'.$renaksi_html.'<span class="badge bg-success text-white">'.$renaksi['detail']['label_pokin_3'].'</span><br>'.$renaksi['detail']['label'].'</td>
							        <td class="indikator_renaksi">'.$indikator_renaksi_html.''.$indikator_html.'</td>
							        <td class="urian_renaksi"></td>
							        <td class="indikator_uraian_renaksi"></td>
							        <td class="uraian_teknis_kegiatan"></td>
							        <td class="indikator_uraian_teknis_kegiatan"></td>
							        <td class="text-center satuan_renaksi">'.$satuan_renaksi_html.''.$satuan_html.'</td>
							        <td class="text-center target_awal_urian_renaksi">'.$target_awal_renaksi_html.''.$target_awal_html.'</td>
							        <td class="text-center target_tw1_urian_renaksi">'.$target_1_renaksi_html.''.$target_1_html.'</td>
							        <td class="text-center target_tw2_urian_renaksi">'.$target_2_renaksi_html.''.$target_2_html.'</td>
							        <td class="text-center target_tw3_urian_renaksi">'.$target_3_renaksi_html.''.$target_3_html.'</td>
							        <td class="text-center target_tw4_urian_renaksi">'.$target_4_renaksi_html.''.$target_4_html.'</td>
							        <td class="text-center target_akhir_urian_renaksi">'.$target_renaksi_html.''.$target_akhir_html.'</td>
							        <td class=""></td>
							        <td class=""></td>
							        <td class=""></td>
							        <td class=""></td>
							        <td class=""></td>
							        <td class=""></td>
							        <td class=""></td>
							        <td class=""></td>
							        <td class=""></td>
							        <td class=""></td>
							        <td class=""></td>
							        <td class=""></td>
							        <td class="anggaran_urian_renaksi"></td>
							    </tr>
							';


							foreach($renaksi['data'] as $uraian_renaksi){
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
								foreach($uraian_renaksi['indikator'] as $key => $ind){
									$indikator_html[$key] = $ind['indikator'];
									$satuan_html[$key] = $ind['satuan'];
									$target_awal_html[$key] = $ind['target_awal'];
									$target_akhir_html[$key] = $ind['target_akhir'];
									$target_1_html[$key] = $ind['target_1'];
									$target_2_html[$key] = $ind['target_2'];
									$target_3_html[$key] = $ind['target_3'];
									$target_4_html[$key] = $ind['target_4'];
									$rencana_pagu_html[$key] = !empty($ind['rencana_pagu']) ? $ind['rencana_pagu'] : 0;
									$realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
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

								$label_pokin = $uraian_renaksi['detail']['label_pokin_5'];
								if(empty($label_pokin)){
									$label_pokin = $uraian_renaksi['detail']['label_pokin_4'];
								}
								$html .= '
								<tr class="ur-kegiatan">
									<td>'.$no.'.'.$no_renaksi.'.'.$no_uraian_renaksi.'</td>
									<td class="kiri kanan bawah text_blok kegiatan_utama"></td>
									<td class="kiri kanan bawah text_blok indikator_kegiatan_utama"></td>
									<td class="kiri kanan bawah text_blok recana_aksi"></td>
									<td class="kiri kanan bawah text_blok indikator_renaksi"></td>
									<td class="urian_renaksi"><span class="badge bg-success text-white">'.$label_pokin.'</span><br>'.$uraian_renaksi['detail']['label'].'</td>
									<td class="indikator_uraian_renaksi">'.$indikator_html.'</td>
									<td class="uraian_teknis_kegiatan"></td>
									<td class="indikator_uraian_teknis_kegiatan"></td>
									<td class="text-center satuan_renaksi">'.$satuan_html.'</td>
									<td class="text-center target_awal_urian_renaksi">'.$target_awal_html.'</td>
									<td class="text-center target_tw1_urian_renaksi">'.$target_1_html.'</td>
									<td class="text-center target_tw2_urian_renaksi">'.$target_2_html.'</td>
									<td class="text-center target_tw3_urian_renaksi">'.$target_3_html.'</td>
									<td class="text-center target_tw4_urian_renaksi">'.$target_4_html.'</td>
									<td class="text-center target_akhir_urian_renaksi">'.$target_akhir_html.'</td>
									<td class=""></td>
									<td class=""></td>
									<td class=""></td>
									<td class=""></td>
									<td class=""></td>
									<td class=""></td>
									<td class=""></td>
									<td class=""></td>
									<td class=""></td>
									<td class=""></td>
									<td class="anggaran_urian_renaksi"></td>
								</tr>
								';

								foreach($uraian_renaksi['data'] as $uraian_teknis_kegiatan){
									$no_uraian_teknis++;
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
									foreach($uraian_teknis_kegiatan['indikator'] as $key => $ind){
										$indikator_html[$key] = '<a href="'.$this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $_POST['tahun_anggaran'] . '&id_skpd=' . $_POST['id_skpd'].'&id_indikator='.$ind['id']).'" target="_blank">'.$ind['indikator'].'</a>';
										$satuan_html[$key] = $ind['satuan'];
										$target_awal_html[$key] = $ind['target_awal'];
										$target_akhir_html[$key] = $ind['target_akhir'];
										$target_1_html[$key] = $ind['target_1'];
										$target_2_html[$key] = $ind['target_2'];
										$target_3_html[$key] = $ind['target_3'];
										$target_4_html[$key] = $ind['target_4'];
										$rencana_pagu_html[$key] = !empty($ind['rencana_pagu']) ? $ind['rencana_pagu'] : 0;
										$realisasi_pagu_html[$key] = !empty($ind['realisasi_pagu']) ? $ind['realisasi_pagu'] : 0;
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

									$label_pokin = $uraian_teknis_kegiatan['detail']['label_pokin_5'];
									if(empty($label_pokin)){
										$label_pokin = $uraian_teknis_kegiatan['detail']['label_pokin_4'];
									}
									$html .= '
									<tr>
										<td>'.$no.'.'.$no_renaksi.'.'.$no_uraian_renaksi.'.'.$no_uraian_teknis.'</td>
										<td class="kegiatan_utama"></td>
										<td class="indikator_kegiatan_utama"></td>
										<td class="recana_aksi"></td>
										<td class="indikator_renaksi"></td>
										<td class="urian_renaksi"></td>
										<td class="indikator_uraian_renaksi"></td>
										<td class="uraian_teknis_kegiatan"><span class="badge bg-success text-white">'.$label_pokin.'</span><br>'.$uraian_teknis_kegiatan['detail']['label'].'</td>
										<td class="indikator_uraian_teknis_kegiatan">'.$indikator_html.'</td>
										<td class="text-center satuan_renaksi">'.$satuan_html.'</td>
										<td class="text-center target_awal_urian_renaksi">'.$target_awal_html.'</td>
										<td class="text-center target_tw1_urian_renaksi">'.$target_1_html.'</td>
										<td class="text-center target_tw2_urian_renaksi">'.$target_2_html.'</td>
										<td class="text-center target_tw3_urian_renaksi">'.$target_3_html.'</td>
										<td class="text-center target_tw4_urian_renaksi">'.$target_4_html.'</td>
										<td class="text-center target_akhir_urian_renaksi">'.$target_akhir_html.'</td>
										<td class=""></td>
										<td class=""></td>
										<td class=""></td>
										<td class=""></td>
										<td class=""></td>
										<td class="text-right" style="visibility: hidden;">'.$rencana_pagu_html.'</td>
										<td class=""></td>
										<td class="text-right" style="visibility: hidden;">'.$realisasi_pagu_html.'</td>
										<td class=""></td>
										<td class=""></td>
										<td class="anggaran_urian_renaksi"></td>
									</tr>
									';
								}
							}
						}
					}
					if(empty($html)){
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
	
	function get_data_pengaturan_rencana_aksi(){
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
				if ($ret['status'] != 'error'){

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
					ARRAY_A);
					
					if(!empty($data)){
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

					if(!empty($data_jadwal_wpsipd)){
						$option_renstra_wpsipd = '<option>Pilih Jadwal RENSTRA WP-SIPD</option>';
						if(!empty($data_jadwal_wpsipd->data)){
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
	
	function submit_pengaturan_rencana_aksi(){
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					if (empty($_POST['tahun_anggaran'])) {
						throw new Exception("Ada data yang kosong!", 1);
					}

					$tahun_anggaran = $_POST['tahun_anggaran'];
					$id_jadwal_renstra_wpsipd = $_POST['id_jadwal_renstra_wpsipd'];

					// pengaturan rencana aksi
					$cek_data_pengaturan = $wpdb->get_var(
						$wpdb->prepare("
						SELECT 
							id
						FROM 
							esakip_pengaturan_upload_dokumen
						WHERE tahun_anggaran=%d
						AND active=1
					", $tahun_anggaran));

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
	
	function get_table_input_iku(){
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data iku!',
			'data'  => ''
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if(!empty($_POST['tipe']) && $_POST['tipe'] == 'pemda'){
					if ($ret['status'] != 'error' && empty($_POST['id_jadwal'])) {
						$ret['status'] = 'error';
						$ret['message'] = 'ID jadwal tidak boleh kosong!';
					}
					if ($ret['status'] != 'error'){
						$data_iku = $wpdb->get_results($wpdb->prepare("
							SELECT
								*
							FROM esakip_data_iku_pemda
							WHERE
								id_jadwal=%d
								AND active=1
						", $_POST['id_jadwal']), ARRAY_A);
						
						$html = '';
						$no = 0;
						if(!empty($data_iku)){
							foreach($data_iku as $v){
								$no++;
								$html .= '
								<tr>
									<td class="atas kanan bawah kiri">'.$no.'</td>
									<td class="text-left tujuan-sasaran atas kanan bawah kiri">'. $v['label_sasaran'] .'</td>
									<td class="text-left indikator_sasaran atas kanan bawah kiri">'. $v['label_indikator'] .'</td>
									<td class="text-left formulasi atas kanan bawah kiri">'. $v['formulasi'] .'</td>
									<td class="text-left sumber_data atas kanan bawah kiri">'. $v['sumber_data'] .'</td>
									<td class="text-left penanggung_jawab atas kanan bawah kiri">'. $v['penanggung_jawab'] .'</td>
								';
	
								$btn = '<div class="btn-action-group">';
								$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Edit IKU"><span class="dashicons dashicons-edit"></span></button>';
								$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Hapus IKU"><span class="dashicons dashicons-trash"></span></button>';
								$btn .= '</div>';
	
								$html .= "<td class='text-center atas kanan bawah kiri hide-excel'>" . $btn . "</td>";
								$html .='</tr>';
							}
						}
						if(empty($html)){
							$html = '<tr><td class="text-center" colspan="18">Data masih kosong!</td></tr>';
						}
						$ret['data'] = $html;
					}
				}else{
					if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
						$ret['status'] = 'error';
						$ret['message'] = 'ID OPD tidak boleh kosong!';
					} else if ($ret['status'] != 'error' && empty($_POST['id_jadwal_wpsipd'])) {
						$ret['status'] = 'error';
						$ret['message'] = 'ID jadwal tidak boleh kosong!';
					}
					if ($ret['status'] != 'error'){		
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
					
						$this_jenis_role = (in_array($user_roles[0], $admin_role_pemda)) ? 1 : 2 ;

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
						if(!empty($data_iku)){
							foreach($data_iku as $v){
								$no++;
								$indikator = explode(" \n- ", $v['label_indikator']);
								$indikator = implode("</br>- ", $indikator);
								$html .= '
								<tr>
									<td class="atas kanan bawah kiri">'.$no.'</td>
									<td class="text-left tujuan-sasaran atas kanan bawah kiri">'. $v['label_sasaran'] .'</td>
									<td class="text-left indikator_sasaran atas kanan bawah kiri">'. $indikator .'</td>
									<td class="text-left formulasi atas kanan bawah kiri">'. $v['formulasi'] .'</td>
									<td class="text-left sumber_data atas kanan bawah kiri">'. $v['sumber_data'] .'</td>
									<td class="text-left penanggung_jawab atas kanan bawah kiri">'. $v['penanggung_jawab'] .'</td>
								';
	
								$btn = '<div class="btn-action-group">';
								$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Edit IKU"><span class="dashicons dashicons-edit"></span></button>';
								$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Hapus IKU"><span class="dashicons dashicons-trash"></span></button>';
								$btn .= '</div>';

								$hak_akses_user = ($nip_kepala == $skpd['nipkepala'] || $is_administrator || $this_jenis_role == 1) ? true : false;

								if(!$hak_akses_user){
									$btn = '';
								}
	
								$html .= "<td class='text-center atas kanan bawah kiri hide-excel'>" . $btn . "</td>";
								$html .='</tr>';
							}
						}
						if(empty($html)){
							$html = '<tr><td class="text-center" colspan="18">Data masih kosong!</td></tr>';
						}
						$ret['data'] = $html;
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
	
	function tambah_iku(){
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

				if ($ret['status'] != 'error'){
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

					if(!empty($_POST['id_iku'])){
						$cek_id = $_POST['id_iku'];
						$data_cek_iku = $wpdb->get_results($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_iku_opd
							WHERE id=%d
						", $cek_id), ARRAY_A);

						$cek_id = !empty($data_cek_iku) ? $cek_id : null;
					}

					if(empty($cek_id)){
						$data['created_at'] = current_time('mysql');

						$wpdb->insert('esakip_data_iku_opd', $data);
					}else{
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

	
	function get_iku_by_id(){
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id'])) {
					if(!empty($_POST['tipe']) && $_POST['tipe'] == "pemda"){
						$data = $wpdb->get_row(
							$wpdb->prepare("
								SELECT *
								FROM esakip_data_iku_pemda
								WHERE id = %d
							", $_POST['id']),
							ARRAY_A
						);
					}else{
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
					if(!empty($_POST['tipe']) && $_POST['tipe'] == "pemda"){
						$data_iku_lama = $wpdb->get_var(
							$wpdb->prepare("
								SELECT
									id
								FROM esakip_data_iku_pemda
								WHERE id=%d
							", $_POST['id'])
						);
	
						if(!empty($data_iku_lama)){
							$ret['data'] = $wpdb->update(
								'esakip_data_iku_pemda',
								array('active' => 0),
								array('id' => $_POST['id'])
							);
						}
					}else{
						$data_iku_lama = $wpdb->get_var(
							$wpdb->prepare("
								SELECT
									id
								FROM esakip_data_iku_opd
								WHERE id=%d
							", $_POST['id'])
						);
	
						if(!empty($data_iku_lama)){
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

	function get_sasaran_rpjmd(){
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
					if(!empty($data)){
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
	
	function tambah_iku_pemda(){
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

				if ($ret['status'] != 'error'){
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

					if(!empty($_POST['id_iku'])){
						$cek_id = $_POST['id_iku'];
						$data_cek_iku = $wpdb->get_results($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_iku_pemda
							WHERE id=%d
						", $cek_id), ARRAY_A);

						$cek_id = !empty($data_cek_iku) ? $cek_id : null;
					}

					if(empty($cek_id)){
						$data['created_at'] = current_time('mysql');

						$wpdb->insert('esakip_data_iku_pemda', $data);
					}else{
						$wpdb->update('esakip_data_iku_pemda', $data, array('id' => $cek_id));
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

	function get_table_input_rencana_aksi_pemda(){
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
				}elseif ($ret['status'] != 'error' && empty($_POST['id_tujuan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Tujuan tidak boleh kosong!';
				}
				if ($ret['status'] != 'error'){
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
					foreach($data as $v){
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
						foreach($data2 as $v2){
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
							foreach($data3 as $v3) {
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
					foreach($data_all['data'] as $v){
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

					    foreach($v['indikator'] as $key => $ind){
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
					    <tr>
					        <td>'.$no.'</td>
							<td class="kegiatan_utama"><span class="badge bg-success text-white"><b>'.$v['detail']['label_pokin_2'].'</span><br>'.$v['detail']['label'].'</td>
					        <td class="indikator_kegiatan_utama"><b>'.$indikator_html.'</td>
					        <td class="recana_aksi"><b></td>
					        <td class="urian_renaksi"><b></td>
					        <td class="text-center satuan_renaksi"><b>'.$satuan_html.'</td>
					        <td class="indikator_renaksi"><b></td>
					        <td class="text-right target_akhir_urian_renaksi"><b>'.$target_awal_html.'</td>
					        <td class="text-right target_akhir_urian_renaksi"><b>'.$target_akhir_html.'</td>
					        <td class="text-right target_tw1_urian_renaksi"><b>'.$target_1_html.'</td>
					        <td class="text-right target_tw2_urian_renaksi"><b>'.$target_2_html.'</td>
					        <td class="text-right target_tw3_urian_renaksi"><b>'.$target_3_html.'</td>
					        <td class="text-right target_tw4_urian_renaksi"><b>'.$target_4_html.'</td>
					        <td class="text-right target_akhir_urian_renaksi"><b>'.$target_akhir_html.'</td>
					        <td class="text-right rencana_pagu"></td>
					        <td class="text-left nama_skpd"></td>
					        <td class="text-left mitra_bidang"></td>
					    </tr>
					    ';

					    foreach($v['data'] as $renaksi){
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

					        foreach($renaksi['indikator'] as $key => $ind){
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
					        <tr>
					            <td>'.$no.'.'.$no_renaksi.'</td>
					            <td class="kegiatan_utama"><b><i></td>
					            <td class="indikator_kegiatan_utama"><b><i></td>
								<td class="recana_aksi"><span class="badge bg-success text-white">'.$renaksi['detail']['label_pokin_3'].'</span><br><b><i>'.$renaksi['detail']['label'].'</td>
					            <td class="urian_renaksi"><b><i></td>
					            <td class="text-center satuan_renaksi"><b><i>'.$satuan_html.'</td>
					            <td class="indikator_renaksi"><b><i>'.$indikator_html.'</td>
					            <td class="text-right target_akhir_urian_renaksi"><b><i>'.$target_akhir_html.'</td>
					            <td class="text-right target_akhir_urian_renaksi"><b><i>'.$target_awal_html.'</td>
					            <td class="text-right target_tw1_urian_renaksi"><b><i>'.$target_1_html.'</td>
					            <td class="text-right target_tw2_urian_renaksi"><b><i>'.$target_2_html.'</td>
					            <td class="text-right target_tw3_urian_renaksi"><b><i>'.$target_3_html.'</td>
					            <td class="text-right target_tw4_urian_renaksi"><b><i>'.$target_4_html.'</td>
					            <td class="text-right target_akhir_urian_renaksi"><b><i>'.$target_akhir_html.'</td>
					            <td class="text-right rencana_pagu"></td>`
					            <td class="text-left nama_skpd"></td>
					            <td class="text-left mitra_bidang"></td>
					        </tr>
					        ';

					        foreach($renaksi['data'] as $uraian_renaksi) {
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
							    
							    foreach($uraian_renaksi['indikator'] as $key => $ind) {
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
							    $rencana_pagu = !empty($rencana_pagu_html) ? implode('<br>', array_map(function($item) { return number_format((float) $item, 0, ",", "."); }, $rencana_pagu_html)) : 0;
							    $mitra_bidang_html = implode('<br>', $mitra_bidang_html);
							    $nama_skpd_html = implode('<br>', $nama_skpd_html);

							    $label_pokin = $uraian_renaksi['detail']['label_pokin_5'];
							    if (empty($label_pokin)) {
							        $label_pokin = $uraian_renaksi['detail']['label_pokin_4'];
							    }

							    foreach ($uraian_renaksi['indikator'] as $i => $ind) {
							        $label_html = '';
						            $label_html = '<span class="badge bg-success text-white">'.$label_pokin.'</span><br>'.$uraian_renaksi['detail']['label'];
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
							            <td>'.$no.'.'.$no_uraian_renaksi.'.'.($i+1).'</td>
							            <td class="kegiatan_utama"></td>
							            <td class="indikator_kegiatan_utama"></td>
							            <td class="recana_aksi"></td>
							            <td class="urian_renaksi">'.$label_html.'</td>
							            <td class="text-center">'.$ind['satuan'].'</td>
							            <td>'.$ind['indikator'].'</td>
							            <td class="text-center">'.$ind['target_akhir'].'</td>
							            <td class="text-center">'.$ind['target_awal'].'</td>
							            <td class="text-center">'.$ind['target_1'].'</td>
							            <td class="text-center">'.$ind['target_2'].'</td>
							            <td class="text-center">'.$ind['target_3'].'</td>
							            <td class="text-center">'.$ind['target_4'].'</td>
							            <td class="text-center">'.$ind['target_akhir'].'</td>
									    <td class="text-right">'.number_format((float)$ind['rencana_pagu'], 0, ",", ".").'</td>
									    <td class="text-center" style="'.$bg.'">'.$link.'</td>
									    <td class="text-left mitra_bidang">'.$ind['mitra_bidang'].'</td>
							        </tr>';
							    }
							}					    
						}
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

	public function get_data_rekening_akun_wp_sipd(){
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					// if()
					if(!empty($_POST['id_skpd'])){
						$id_skpd = $_POST['id_skpd'];
					}else{
						throw new Exception("Id Skpd Kosong!", 1);
					}
					if(!empty($_POST['tahun_anggaran'])){
						$tahun_anggaran = $_POST['tahun_anggaran'];
					}else{
						throw new Exception("Tahun Anggaran Kosong!", 1);
					}
					if(!empty($_POST['kode_sbl'])){
						$kode_sbl = $_POST['kode_sbl'];
					}else{
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
					
					if(is_wp_error( $response ) ){
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

	public function get_data_rincian_belanja(){
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					// if()
					if(!empty($_POST['kode_sbl'])){
						$kode_sbl = $_POST['kode_sbl'];
					}else{
						throw new Exception("Kode Sbl Kosong!", 1);
					}
					if(!empty($_POST['tahun_anggaran'])){
						$tahun_anggaran = $_POST['tahun_anggaran'];
					}else{
						throw new Exception("Tahun Anggaran Kosong!", 1);
					}
					if(!empty($_POST['kode_akun'])){
						$kode_akun = $_POST['kode_akun'];
					}else{
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
					
					if(is_wp_error( $response ) ){
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

	public function crate_tagging_rincian_belanja(){
		global $wpdb;

		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					if(!empty($_POST['id_indikator_teknis_kegiatan'])){
						$id_indikator_teknis_kegiatan = $_POST['id_indikator_teknis_kegiatan'];
					}else{
						throw new Exception("Indikator Teknis Kegiatan Tidak Boleh Kosong!", 1);
					}
					if(!empty($_POST['id_uraian_teknis_kegiatan'])){
						$id_uraian_teknis_kegiatan = $_POST['id_uraian_teknis_kegiatan'];
					}else{
						throw new Exception("Indikator Teknis Kegiatan Tidak Boleh Kosong!", 1);
					}
					if(!empty($_POST['kode_sbl'])){
						$kode_sbl = $_POST['kode_sbl'];
					}else{
						throw new Exception("Kode Sbl Tidak Boleh kosong!", 1);
					}
					if(!empty($_POST['tahun_anggaran'])){
						$tahun_anggaran = $_POST['tahun_anggaran'];
					}else{
						throw new Exception("Tahun Anggaran Tidak Boleh Kosong!", 1);
					}
					if(!empty($_POST['id_skpd'])){
						$id_skpd = $_POST['id_skpd'];
					}else{
						throw new Exception("ID OPD Tidak Boleh Kosong!", 1);
					}

					$data = json_decode(stripslashes($_POST['data']), true);

					$cek = [];
					foreach ($data as $key => $data_tagging) {
						$data = array(
							'id_uraian_teknis_kegiatan'=> $id_uraian_teknis_kegiatan,
							'id_indikator_teknis_kegiatan'=> $id_indikator_teknis_kegiatan,
							'kode_sbl'=> $kode_sbl,
							'nama_komponen_tagging_rincian'=> $data_tagging['uraian_tagging'],  
							'koefisien_tagging_rincian'=> $data_tagging['volume_satuan_tagging'],
							'rincian_tagging_rincian'=> $data_tagging['nilai_tagging'],
							'active'=> 1,
							'jenis_tagging'=> $data_tagging['jenis_tagging'],
							'tahun_anggaran'=> $tahun_anggaran,
							'id_skpd' => $id_skpd,
							'created_at'=> current_time('mysql'),
							'update_at'=> current_time('mysql') 
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

	                foreach($data_renaksi as $key => $val){
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
	                    $_POST['parent'], 1
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


	function tambah_renaksi_pemda(){
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
				if ($ret['status'] != 'error'){
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
					if($_POST['level'] == 1){
						$data['id_pokin'] = $_POST['id_pokin_1'];
						$data['id_pokin'] = $_POST['id_pokin_2'];
						$data['label_pokin_1'] = $_POST['label_pokin_1'];
						$data['label_pokin_2'] = $_POST['label_pokin_2'];
					}else if($_POST['level'] == 2){
						$data['parent'] = $_POST['parent'];
						$data['id_pokin'] = $_POST['id_pokin_1'];
						$data['label_pokin_3'] = $_POST['label_pokin_1'];
					}else if($_POST['level'] == 3){
						$data['parent'] = $_POST['parent'];
						$data['id_pokin'] = $_POST['id_pokin_1'];
						$data['label_pokin_4'] = $_POST['label_pokin_1'];
					}
					if(!empty($_POST['id'])){
						$cek_id = $_POST['id'];
					}else{
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
					if(empty($cek_id)){
						$wpdb->insert('esakip_data_rencana_aksi_pemda', $data);
					}else{
						$wpdb->update('esakip_data_rencana_aksi_pemda', $data, array('id' => $cek_id));
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

	function tambah_indikator_renaksi_pemda(){
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
				if ($ret['status'] != 'error'){
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
					if(empty($_POST['id_label_indikator'])){
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_indikator_pemda
							WHERE indikator=%s
								AND active=0
								AND tahun_anggaran=%d
								AND id_tujuan=%d
						", $_POST['indikator'], $_POST['tahun_anggaran'], $_POST['id_tujuan']));
					}else{
						$cek_id = $wpdb->get_var($wpdb->prepare("
							SELECT
								id
							FROM esakip_data_rencana_aksi_indikator_pemda
							WHERE id=%d
						", $_POST['id_label_indikator']));
						$ret['message'] = "Berhasil edit indikator!";
					}
					if(empty($cek_id)){
						$wpdb->insert('esakip_data_rencana_aksi_indikator_pemda', $data);
					}else{
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

	function hapus_rencana_aksi_pemda(){
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


	function get_rencana_aksi_pemda(){
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
				if ($ret['status'] != 'error'){
					$ret['data'] = $wpdb->get_row($wpdb->prepare('
						SELECT
							*
						FROM esakip_data_rencana_aksi_pemda
						WHERE id=%d
							AND tahun_anggaran=%d
							AND id_tujuan=%d
					',$_POST['id'], $_POST['tahun_anggaran'], $_POST['id_tujuan']), ARRAY_A);
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

	function get_indikator_rencana_aksi_pemda(){
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
				if ($ret['status'] != 'error'){
					$ret['data'] = $wpdb->get_row($wpdb->prepare('
						SELECT
							*
						FROM esakip_data_rencana_aksi_indikator_pemda
						WHERE id=%d
							AND tahun_anggaran=%d
					',$_POST['id'], $_POST['tahun_anggaran'], $_POST['id_tujuan']), ARRAY_A);
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

	function hapus_indikator_rencana_aksi_pemda(){
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

	public function get_skpd_renaksi(){
	    global $wpdb;
	    $ret = array(
	        'status' => 'success',
	        'message' => 'Berhasil mengambil data!',
	        'data' => array()
	    );

	    if(!empty($_POST)){
	        if(!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
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

	function simpan_renaksi_pemda(){
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

	function simpan_bulanan_renaksi_opd() {
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
	                        AND tahun_anggaran = %d
	                        AND id_skpd = %d
	                        AND bulan = %d
	                ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $_POST['bulan']));

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

	function simpan_triwulan_renaksi_opd() {
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
	function simpan_total_bulanan() {
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
}