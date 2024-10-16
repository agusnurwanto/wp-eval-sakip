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
						$data_renaksi[$key]['indikator'] = $wpdb->get_results($wpdb->prepare("
							SELECT
								*
							FROM esakip_data_rencana_aksi_indikator_opd
							WHERE id_renaksi=%d
								AND active=1
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
					}else{
						$wpdb->update('esakip_data_rencana_aksi_opd', $data, array('id' => $cek_id));
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

	function get_rencana_aksi(){
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
				}
				if ($ret['status'] != 'error'){
					$ret['data'] = $wpdb->get_row($wpdb->prepare('
						SELECT
							*
						FROM esakip_data_rencana_aksi_opd
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

					$no = 0;
					$no_renaksi = 0;
					$no_uraian_renaksi = 0;
					$no_uraian_teknis = 0;
					foreach($data_all['data'] as $v){
						$no++;
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
						<tr>
							<td>'.$no.'</td>
							<td class="kegiatan_utama"><span class="badge bg-success text-white">'.$v['detail']['label_pokin_2'].'</span><br>'.$v['detail']['label'].'</td>
							<td class="indikator_kegiatan_utama">'.$indikator_html.'</td>
							<td class="recana_aksi"></td>
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

							$html .= '
							<tr>
								<td>'.$no.'.'.$no_renaksi.'</td>
								<td class="kegiatan_utama"></td>
								<td class="indikator_kegiatan_utama"></td>
								<td class="recana_aksi"><span class="badge bg-success text-white">'.$renaksi['detail']['label_pokin_3'].'</span><br>'.$renaksi['detail']['label'].'</td>
								<td class="indikator_renaksi">'.$indikator_html.'</td>
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

							foreach($renaksi['data'] as $uraian_renaksi){
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
								<tr>
									<td>'.$no.'.'.$no_renaksi.'.'.$no_uraian_renaksi.'</td>
									<td class="kegiatan_utama"></td>
									<td class="indikator_kegiatan_utama"></td>
									<td class="recana_aksi"></td>
									<td class="indikator_renaksi"></td>
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
									<td>'.$no.'</td>
									<td class="text-left tujuan-sasaran">'. $v['label_sasaran'] .'</td>
									<td class="text-left indikator_sasaran">'. $v['label_indikator'] .'</td>
									<td class="text-left formulasi">'. $v['formulasi'] .'</td>
									<td class="text-left sumber_data">'. $v['sumber_data'] .'</td>
									<td class="text-left penanggung_jawab">'. $v['penanggung_jawab'] .'</td>
								';
	
								$btn = '<div class="btn-action-group">';
								$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Edit IKU"><span class="dashicons dashicons-edit"></span></button>';
								$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Hapus IKU"><span class="dashicons dashicons-trash"></span></button>';
								$btn .= '</div>';
	
								$html .= "<td class='text-center'>" . $btn . "</td>";
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
									<td>'.$no.'</td>
									<td class="text-left tujuan-sasaran">'. $v['label_sasaran'] .'</td>
									<td class="text-left indikator_sasaran">'. $indikator .'</td>
									<td class="text-left formulasi">'. $v['formulasi'] .'</td>
									<td class="text-left sumber_data">'. $v['sumber_data'] .'</td>
									<td class="text-left penanggung_jawab">'. $v['penanggung_jawab'] .'</td>
								';
	
								$btn = '<div class="btn-action-group">';
								$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Edit IKU"><span class="dashicons dashicons-edit"></span></button>';
								$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_iku(\'' . $v['id'] . '\'); return false;" href="#" title="Hapus IKU"><span class="dashicons dashicons-trash"></span></button>';
								$btn .= '</div>';

								$hak_akses_user = ($nip_kepala == $skpd['nipkepala'] || $is_administrator || $this_jenis_role == 1) ? true : false;

								if(!$hak_akses_user){
									$btn = '';
								}
	
								$html .= "<td class='text-center'>" . $btn . "</td>";
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
							            u.*
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
					        <td class="kegiatan_utama"><b>'.$v['detail']['label'].'</td>
					        <td class="indikator_kegiatan_utama"><b>'.$indikator_html.'</td>
					        <td class="recana_aksi"><b></td>
					        <td class="urian_renaksi"><b></td>
					        <td class="text-center satuan_renaksi"><b>'.$satuan_html.'</td>
					        <td class="indikator_renaksi"><b></td>
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
					            <td class="recana_aksi"><b><i>'.$renaksi['detail']['label'].'</td>
					            <td class="urian_renaksi"><b><i></td>
					            <td class="text-center satuan_renaksi"><b><i>'.$satuan_html.'</td>
					            <td class="indikator_renaksi"><b><i>'.$indikator_html.'</td>
					            <td class="text-right target_akhir_urian_renaksi"><b><i>'.$target_akhir_html.'</td>
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

					        foreach($renaksi['data'] as $uraian_renaksi){
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
					            $realisasi_pagu_html = array();
					            $mitra_bidang_html = array();
					            $nama_skpd_html = array();

					            foreach($uraian_renaksi['indikator'] as $key => $ind){
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
								$rencana_pagu = !empty($rencana_pagu_html) ? implode('<br>', array_map(function($item) { return number_format((float) $item, 0, ",", ".");
								}, $rencana_pagu_html)) : 0;
					            $mitra_bidang_html = implode('<br>', $mitra_bidang_html);
					            $nama_skpd_html = implode('<br>', $nama_skpd_html);

					            $label_pokin = $uraian_renaksi['detail']['label_pokin_5'];
					            if(empty($label_pokin)){
					                $label_pokin = $uraian_renaksi['detail']['label_pokin_4'];
					            }
					            $html .= '
					            <tr>
					                <td>'.$no.'.'.$no_renaksi.'.'.$no_uraian_renaksi.'</td>
					                <td class="kegiatan_utama"></td>
					                <td class="indikator_kegiatan_utama"></td>
					                <td class="recana_aksi"></td>
					                <td class="urian_renaksi">'.$uraian_renaksi['detail']['label'].'</td>
					                <td class="text-center satuan_renaksi">'.$satuan_html.'</td>	
					                <td class="indikator_uraian_renaksi">'.$indikator_html.'</td>
					                <td class="text-right target_akhir_urian_renaksi">'.$target_akhir_html.'</td>
					                <td class="text-right target_tw1_urian_renaksi">'.$target_1_html.'</td>
					                <td class="text-right target_tw2_urian_renaksi">'.$target_2_html.'</td>
					                <td class="text-right target_tw3_urian_renaksi">'.$target_3_html.'</td>
					                <td class="text-right target_tw4_urian_renaksi">'.$target_4_html.'</td>
					                <td class="text-right target_akhir_urian_renaksi">'.$target_akhir_html.'</td>
					                <td class="text-right rencana_pagu">'.$rencana_pagu.'</td>
					                <td class="text-left nama_skpd">'.$nama_skpd_html.'</td>
					                <td class="text-left mitra_bidang">'.$mitra_bidang_html.'</td>
					            </tr>
					            ';
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

	public function get_data_renaksi_pemda()
	{
	    global $wpdb;
	    try {
	        if (!empty($_POST)) {
	            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

	                $_prefix_pemda = '';
	                if (!empty($_POST['tipe_pokin'])) {
	                    $_prefix_pemda = $_POST['tipe_pokin'] == "pemda" ? "_pemda" : "";
	                }elseif (empty($_POST['id_tujuan'])) {
						$ret['status'] = 'error';
						$ret['message'] = 'ID Tujuan tidak boleh kosong!';
					}

	                if ($_prefix_pemda == '') {
	                    $data_renaksi = array();
	                } else if ($_prefix_pemda == '_pemda') {
	                    $data_renaksi = $wpdb->get_results($wpdb->prepare("
	                        SELECT 
	                            a.*
	                        FROM esakip_data_rencana_aksi_pemda a
	                        WHERE 
	                            a.parent=%d AND 
	                            a.level=%d AND 
	                            a.active=%d AND 
	                            a.id_tujuan=%d
	                        ORDER BY a.id
	                    ",
	                    $_POST['parent'],
	                    $_POST['level'],
	                    1,
	                    $_POST['id_tujuan']
	                    ), ARRAY_A);
	                }

	                // Ambil indikator terkait untuk setiap data rencana aksi
	                foreach($data_renaksi as $key => $val){
	                    $data_renaksi[$key]['indikator'] = $wpdb->get_results($wpdb->prepare("
	                        SELECT
	                            *
	                        FROM esakip_data_rencana_aksi_indikator_pemda
	                        WHERE id_renaksi=%d
	                            AND active=1
	                    ", $val['id']));
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
	                            WHERE id=(
	                                SELECT 
	                                    parent 
	                                FROM esakip_data_rencana_aksi_pemda 
	                                WHERE id=a.id 
	                            ) 
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
	                            WHERE id=(
	                                SELECT 
	                                    parent 
	                                FROM esakip_data_rencana_aksi_pemda 
	                                WHERE id=(
	                                    SELECT 
	                                        parent 
	                                    FROM esakip_data_rencana_aksi_pemda 
	                                    WHERE id=a.id 
	                                ) 
	                            ) 
	                        ) label_parent_1,
	                        (
	                            SELECT 
	                                label 
	                            FROM esakip_data_rencana_aksi_pemda 
	                            WHERE id=(
	                                SELECT 
	                                    parent 
	                                FROM esakip_data_rencana_aksi_pemda 
	                                WHERE id=a.id 
	                            ) 
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

	                $dataParent = array();
	                if(!empty($label_parent)){
	                    $dataParent = $wpdb->get_results($wpdb->prepare(
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

					if ($child_level == 2) {
						$level_name = "Rencana Aksi";
					} else if ($child_level == 3) {
						$level_name = "Uraian Kegiatan Rencana Aksi";
					} 

					$cek_child = $wpdb->get_results($wpdb->prepare("
						SELECT
							*
						FROM esakip_data_rencana_aksi_pemda
						WHERE level=%d
							AND parent=%d
							AND active=1
					", $child_level, $_POST['id']), ARRAY_A);

					if (empty($cek_child)) {
						$wpdb->update('esakip_data_rencana_aksi_pemda', array(
							'active' => 0
						), array('id' => $_POST['id']));
					} else {
						$ret['status'] = 'error';
						$ret['child'] = $cek_child;
						$ret['message'] = 'Gagal menghapus, data di ' . $level_name . ' harus dihapus dahulu!';
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
					$wpdb->update('esakip_data_rencana_aksi_indikator_pemda', array(
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
}