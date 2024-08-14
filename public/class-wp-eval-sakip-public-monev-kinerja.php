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
			'data'  => ''
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				if ($ret['status'] != 'error'){
					// $ret['data']['id_periode'] = 36;

					$jadwal_renstra = $wpdb->get_results(
						"
						SELECT 
							id,
							nama_jadwal,
							nama_jadwal_renstra,
							tahun_anggaran,
							lama_pelaksanaan,
							tahun_selesai_anggaran
						FROM esakip_data_jadwal
						WHERE tipe = 'RPJMD'
						  AND status = 1
							ORDER BY tahun_anggaran DESC",
						ARRAY_A
					);
					$opsion_jadwal_renstra = "<option value=''>Pilih Periode</option>";

					foreach ($jadwal_renstra as $v_renstra) {
						if (!empty($v_renstra['tahun_selesai_anggaran']) && $v_renstra['tahun_selesai_anggaran'] > 1) {
							$tahun_anggaran_selesai = $v_renstra['tahun_selesai_anggaran'];
						} else {
							$tahun_anggaran_selesai = $v_renstra['tahun_anggaran'] + $v_renstra['lama_pelaksanaan'];
						}

						$opsion_jadwal_renstra .= "<option value='". $v_renstra['id'] ."'>". $v_renstra['nama_jadwal_renstra'] ." Periode ". $v_renstra['tahun_anggaran'] ." - ". $tahun_anggaran_selesai ."</option>";
					}

					$data = $wpdb->get_results($wpdb->prepare("
								SELECT
									pr.*
								FROM esakip_pengaturan_rencana_aksi as pr
								JOIN esakip_data_jadwal as jj
								ON pr.id_jadwal = jj.id
								WHERE tahun_anggaran=%d
									AND active=1
							", $_POST['tahun_anggaran']), ARRAY_A);
					
							$html = '';
							foreach ($data as $v_renaksi) {
								$html .='
									<tr>
										<td>
											<select class="form-control" id="pilih_jadwal_renstra_renaksi" name="jadwal_renstra_">
												'. $opsion_jadwal_renstra .'
											</select>
										</td>
										<td></td>
										<td></td>
										<td></td>
										<td>
											<a class="btn btn-sm btn-success mr-2" style="text-decoration: none;" onclick="simpan_data_pengaturan(\'' . $v_renaksi['id'] . '\'); return false;" href="#" title="Simpan Data Pengaturan"><i class="dashicons dashicons-saved"></i></a>
										</td>
									</tr>';
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
}