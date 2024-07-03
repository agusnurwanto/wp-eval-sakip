<?php

use GuzzleHttp\Psr7\Query;

require_once ESAKIP_PLUGIN_PATH . "/public/class-wp-eval-sakip-public-monev-kinerja.php";
class Wp_Eval_Sakip_Pohon_Kinerja extends Wp_Eval_Sakip_Monev_Kinerja
{
	public function penyusunan_pohon_kinerja($atts)
	{
		if (!empty($_GET) && !empty($_GET['post'])) {
			return '';
		}

		require_once ESAKIP_PLUGIN_PATH . 'public/partials/pohon-kinerja/wp-eval-sakip-penyusunan-pohon-kinerja.php';
	}
	public function penyusunan_pohon_kinerja_opd($atts)
	{
		if (!empty($_GET) && !empty($_GET['post'])) {
			return '';
		}

		require_once ESAKIP_PLUGIN_PATH . 'public/partials/pohon-kinerja/wp-eval-sakip-penyusunan-pohon-kinerja-opd.php';
	}
	public function list_penyusunan_pohon_kinerja_opd($atts)
	{
		if (!empty($_GET) && !empty($_GET['post'])) {
			return '';
		}

		require_once ESAKIP_PLUGIN_PATH . 'public/partials/dokumen-list-opd/wp-eval-sakip-list-penyusunan-pohon-kinerja-opd.php';
	}
	public function view_pohon_kinerja($atts)
	{
		if (!empty($_GET) && !empty($_GET['post'])) {
			return '';
		}

		require_once ESAKIP_PLUGIN_PATH . 'public/partials/pohon-kinerja/wp-eval-sakip-view-pohon-kinerja.php';
	}

	public function view_pohon_kinerja_opd($atts)
	{
		if (!empty($_GET) && !empty($_GET['post'])) {
			return '';
		}

		require_once ESAKIP_PLUGIN_PATH . 'public/partials/pohon-kinerja/wp-eval-sakip-view-pohon-kinerja-opd.php';
	}

	public function cascading_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/pohon-kinerja/wp-eval-sakip-cascading-pemda.php';
	}

	public function get_data_pokin()
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
								FROM esakip_pohon_kinerja'. $_prefix_opd .' 
								WHERE id=a.id '. $_where_opd .'
							) label_parent_1';
							break;

						case '3':
							$label_parent = '
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja'. $_prefix_opd .' 
									WHERE id=a.id '. $_where_opd .'
								) '. $_where_opd .'
							) label_parent_1,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja'. $_prefix_opd .' 
								WHERE id=a.id '. $_where_opd .'
							) label_parent_2';
							break;

						case '4':
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
										FROM esakip_pohon_kinerja'. $_prefix_opd .' 
										WHERE id=a.id '. $_where_opd .'
									) '. $_where_opd .'
								) '. $_where_opd .'
							) label_parent_1,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja'. $_prefix_opd .' 
									WHERE id=a.id '. $_where_opd .'
								) '. $_where_opd .'
							) label_parent_2,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja'. $_prefix_opd .' 
								WHERE id=a.id '. $_where_opd .'
							) label_parent_3';
							break;

						case '5':
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
											FROM esakip_pohon_kinerja'. $_prefix_opd .' 
											WHERE id=a.id '. $_where_opd .'
										) '. $_where_opd .'
									) '. $_where_opd .'
								) '. $_where_opd .'
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
										FROM esakip_pohon_kinerja'. $_prefix_opd .' 
										WHERE id=a.id '. $_where_opd .'
									) '. $_where_opd .'
								) '. $_where_opd .'
							) label_parent_2,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja'. $_prefix_opd .' 
									WHERE id=a.id '. $_where_opd .'
								) '. $_where_opd .'
							) label_parent_3,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja'. $_prefix_opd .' 
								WHERE id=a.id '. $_where_opd .'
							) label_parent_4';
							break;

						default:
							$label_parent = '';
							break;
					}

					if ($_prefix_opd == '') {
						$dataPokin = $wpdb->get_results($wpdb->prepare(
							"
							SELECT 
								a.id,
								a.label,
								a.parent,
								a.active,
								b.id AS id_indikator,
								b.label_indikator_kinerja
							FROM esakip_pohon_kinerja a
								LEFT JOIN esakip_pohon_kinerja b 
									ON a.id=b.parent AND a.level=b.level 
							WHERE 
								a.id_jadwal=%d AND 
								a.parent=%d AND 
								a.level=%d AND 
								a.active=%d 
							ORDER BY a.id",
							$_POST['id_jadwal'],
							$_POST['parent'],
							$_POST['level'],
							1
						), ARRAY_A);
					}else if($_prefix_opd == '_opd'){
						$dataPokin = $wpdb->get_results($wpdb->prepare("
							SELECT 
								a.id,
								a.label,
								a.parent,
								a.active,
								b.id AS id_indikator,
								b.label_indikator_kinerja
							FROM esakip_pohon_kinerja_opd a
								LEFT JOIN esakip_pohon_kinerja_opd b 
									ON a.id=b.parent AND a.level=b.level 
							WHERE 
								a.id_jadwal=%d AND 
								a.parent=%d AND 
								a.level=%d AND 
								a.active=%d AND 
								a.id_skpd=%d
							ORDER BY a.id",
							$_POST['id_jadwal'],
							$_POST['parent'],
							$_POST['level'],
							1,
							$id_skpd
						), ARRAY_A);
					}

					$dataParent = array();
					if($_prefix_opd == ''){
						if(!empty($label_parent)){
							$dataParent = $wpdb->get_results($wpdb->prepare("
									SELECT 
										".$label_parent."
									FROM esakip_pohon_kinerja a 
									WHERE 
										a.id_jadwal=%d AND 
										a.id=%d AND
										a.active=%d
									ORDER BY a.id", 
									$_POST['id_jadwal'], 
									$_POST['parent'], 
									1
								), ARRAY_A);
						}
					}else if($_prefix_opd == '_opd'){
						if(!empty($label_parent)){
							$dataParent = $wpdb->get_results($wpdb->prepare("
									SELECT 
										".$label_parent."
									FROM esakip_pohon_kinerja_opd a 
									WHERE 
										a.id_jadwal=%d AND 
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
					foreach ($dataPokin as $key => $pokin) {
						if(empty($data['data'][$pokin['id']])){
							$data['data'][$pokin['id']] = [
								'id' => $pokin['id'],
								'label' => $pokin['label'],
								'parent' => $pokin['parent'],
								'label_parent_1' => $pokin['label_parent_1'],
								'indikator' => []
							];
						}

						if (!empty($pokin['id_indikator'])) {
							if (empty($data['data'][$pokin['id']]['indikator'][$pokin['id_indikator']])) {
								$data['data'][$pokin['id']]['indikator'][$pokin['id_indikator']] = [
									'id' => $pokin['id_indikator'],
									'label' => $pokin['label_indikator_kinerja']
								];
							}
						}
					}

					foreach ($dataParent as $v_parent) {
						
						if(empty($data['parent'][$v_parent['label_parent_1']])){
							$data['parent'][$v_parent['label_parent_1']] = $v_parent['label_parent_1'];
						}

						if(empty($data['parent'][$v_parent['label_parent_2']])){
							$data['parent'][$v_parent['label_parent_2']] = $v_parent['label_parent_2'];
						}

						if(empty($data['parent'][$v_parent['label_parent_3']])){
							$data['parent'][$v_parent['label_parent_3']] = $v_parent['label_parent_3'];
						}

						if(empty($data['parent'][$v_parent['label_parent_4']])){
							$data['parent'][$v_parent['label_parent_4']] = $v_parent['label_parent_4'];
						}
					}

					echo json_encode([
						'status' => true,
						'data' => array_values($data['data']),
						'parent' => array_values($data['parent'])
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

	public function create_pokin()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = '';
					$input = json_decode(stripslashes($_POST['data']), true);

					if(!empty($_POST['tipe_pokin'])){
						if(!empty($_POST['id_skpd'])){
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_pokin'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_pokin'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja$_prefix_opd 
						WHERE label=%s 
							AND parent=%d 
							AND level=%d 
							AND active=%d $_where_opd 
						ORDER BY id
					", trim($input['label']), $input['parent'], $input['level'], 1),  ARRAY_A);

					if (!empty($id)) {
						throw new Exception("Data sudah ada!", 1);
					}

					if($_prefix_opd == ""){
						// untuk pokin pepmda //////////////////////////////////////////////////////////
						$data = $wpdb->insert('esakip_pohon_kinerja', [
							'label' => trim($input['label']),
							'parent' => $input['parent'],
							'level' => $input['level'],
							'id_jadwal' => $input['id_jadwal'],
							'active' => 1
						]);
					}else{
						// untuk pokin opd //////////////////////////////////////////////////////////
						$data = $wpdb->insert('esakip_pohon_kinerja'.$_prefix_opd, [
							'label' => trim($input['label']),
							'parent' => $input['parent'],
							'level' => $input['level'],
							'id_jadwal' => $input['id_jadwal'],
							'active' => 1,
							'id_skpd' => $id_skpd
						]);
					}

					echo json_encode([
		    			'status' => true,
		    			'message' => 'Sukses simpan pohon kinerja!'
		    		]);exit();
				}else{
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

	public function edit_pokin()
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

					$data = $wpdb->get_row($wpdb->prepare("
						SELECT 
							id, 
							parent, 
							level, 
							label 
						FROM esakip_pohon_kinerja$_prefix_opd 
						WHERE id=%d 
							AND active=%d$_where_opd
					", $_POST['id'], 1),  ARRAY_A);

					$data_croscutting = array();
					if(!empty($_prefix_opd) && $_prefix_opd == "_opd" && !empty($data)){
						$data_croscutting = $wpdb->get_results($wpdb->prepare("
								SELECT 
									*
								FROM esakip_croscutting_opd
								WHERE parent_pohon_kinerja=%d 
									AND active=%d
							", $_POST['id'], 1),  ARRAY_A);
					}

					$table_croscutting = '';
					$no = 1;
					if(!empty($data_croscutting)){
						foreach ($data_croscutting as $k_cross => $v_cross) {

							$nama_skpd = $wpdb->get_row(
								$wpdb->prepare("
									SELECT 
										nama_skpd
									FROM esakip_data_unit 
									WHERE active=1 
									AND is_skpd=1 
									AND id_skpd=%d
									ORDER BY kode_skpd ASC
								", $v_cross['id_skpd_croscutting']),
								ARRAY_A
							);
							
							switch ($v_cross['status_croscutting']) {
								case '1':
									$status_croscutting = 'disetujui';
									break;
		
								case '2':
									$status_croscutting = 'ditolak';
									break;
		
								default:
									$status_croscutting = 'menunggu';
									break;
							}

							$table_croscutting .= '<tr>';

							$table_croscutting .= '<td>' . $no++. '</td>';
							$table_croscutting .= '<td>' . $v_cross['keterangan'] . '</td>';
							$table_croscutting .= '<td>' . $v_cross['keterangan_croscutting'] . '</td>';
							$table_croscutting .= '<td>' . $nama_skpd['nama_skpd']. '</td>';
							$table_croscutting .= '<td>' . $status_croscutting . '</td>';

							$aksi = '';
							$aksi .= '<a href="javascript:void(0)" class="btn btn-sm btn-warning edit-croscutting" data-id="' . $v_cross['id'] . '" href="#" title="Edit Croscutting"><span class="dashicons dashicons-edit"></span></a>';
							$aksi .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger delete-croscutting" data-id="' . $v_cross['id'] . '" style="margin-left: 5px;" href="#" title="Hapus Croscutting"><span class="dashicons dashicons-trash"></span></a>';

							$table_croscutting .= '<td>'. $aksi .'</td>';

							$table_croscutting .= '</tr>';
						}
					}

					if(empty($data)){
						throw new Exception("Data tidak ditemukan!", 1);
					}

					echo json_encode([
		    			'status' => true,
		    			'data' => $data,
						'data_croscutting' => $table_croscutting
		    		]);exit();
				}else{
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

    public function update_pokin(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {
					$input = json_decode(stripslashes($_POST['data']), true);

					$_prefix_opd = $_where_opd = $id_skpd = '';
					if(!empty($_POST['tipe_pokin'])){
						if(!empty($_POST['id_skpd'])){
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_pokin'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_pokin'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}


					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja$_prefix_opd 
						WHERE label=%s 
							AND id!=%d 
							AND parent=%d 
							AND level=%d 
							AND active=%d$_where_opd
						", trim($input['label']), $input['id'], $input['parent'], $input['level'], 1),  ARRAY_A);

					if (!empty($id)) {
						throw new Exception("Data sudah ada!", 1);
					}

					if($_prefix_opd == ''){
						// untuk pokin pemda //////////////////////////////////////////////////////////////////////////////
						$data = $wpdb->update('esakip_pohon_kinerja', [
							'label' => trim($input['label'])
						], [
							'id' => $input['id']
						]);

						$child = $wpdb->query($wpdb->prepare("
							UPDATE esakip_pohon_kinerja 
							SET label=%s 
							WHERE parent=%d 
								AND label_indikator_kinerja IS NOT NULL
						", trim($input['label']), $input['id']));
					}else{
						// untuk pokin opd  //////////////////////////////////////////////////////////////////////////////
						$data = $wpdb->update('esakip_pohon_kinerja'.$_prefix_opd, [
							'label' => trim($input['label'])
						], [
							'id' => $input['id'],
							'id_skpd' => $id_skpd
						]);

						$child = $wpdb->query($wpdb->prepare("
							UPDATE esakip_pohon_kinerja$_prefix_opd 
							SET label=%s 
							WHERE parent=%d 
								AND label_indikator_kinerja IS NOT NULL
								AND id_skpd=%d
						", trim($input['label']), $input['id'], $id_skpd));
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses ubah pohon kinerja!'
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

	public function delete_pokin()
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

					$indikator = $wpdb->get_row($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja$_prefix_opd 
						WHERE parent=%d 
							AND label_indikator_kinerja IS NOT NULL 
							AND level=%d 
							AND active=%d$_where_opd
					", $_POST['id'], $_POST['level'], 1),  ARRAY_A);

					if (!empty($indikator)) {
						throw new Exception("Indikator harus dihapus dulu!", 1);
					}

					$child = $wpdb->get_row($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja$_prefix_opd 
						WHERE parent=%d 
							AND level=%d 
							AND active=%d$_where_opd
					", $_POST['id'], (intval($_POST['level']) + 1), 1),  ARRAY_A);

					if (!empty($child)) {
						throw new Exception("Pohon kinerja level " . (intval($_POST['level']) + 1) . " harus dihapus dulu!", 1);
					}

					if ($_prefix_opd == '') {
						// untuk pemda
						$data = $wpdb->delete('esakip_pohon_kinerja', [
							'id' => $_POST['id']
						]);
					} else {
						// untuk opd
						$data = $wpdb->delete('esakip_pohon_kinerja' . $_prefix_opd, [
							'id' => $_POST['id'],
							'id_skpd' => $id_skpd
						]);
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses hapus pohon kinerja!'
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

	public function create_indikator_pokin()
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

					$input = json_decode(stripslashes($_POST['data']), true);

					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja$_prefix_opd 
						WHERE label_indikator_kinerja=%s 
							AND parent=%d 
							AND level=%d 
							AND active=%d$_where_opd
					", trim($input['indikator_label']), $input['parent'], $input['level'], 1),  ARRAY_A);

					if (!empty($id)) {
						throw new Exception("Data sudah ada!", 1);
					}

					if ($_prefix_opd == '') {
						$data = $wpdb->insert('esakip_pohon_kinerja', [
							// 'label' => trim($input['label']),
							'label_indikator_kinerja' => trim($input['indikator_label']),
							'parent' => $input['parent'],
							'level' => $input['level'],
							'id_jadwal' => $input['id_jadwal'],
							'active' => 1
						]);
					} else {
						$data = $wpdb->insert('esakip_pohon_kinerja' . $_prefix_opd, [
							// 'label' => trim($input['label']),
							'label_indikator_kinerja' => trim($input['indikator_label']),
							'parent' => $input['parent'],
							'level' => $input['level'],
							'id_jadwal' => $input['id_jadwal'],
							'active' => 1,
							'id_skpd' => $id_skpd
						]);
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses simpan indikator!'
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

	public function edit_indikator_pokin()
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

					$data = $wpdb->get_row($wpdb->prepare(
						"
						SELECT 
							a.id, 
							a.label, 
							a.parent, 
							a.label_indikator_kinerja, 
							a.level,
							b.parent AS parent_all 
						FROM 
							esakip_pohon_kinerja$_prefix_opd a
						LEFT JOIN esakip_pohon_kinerja$_prefix_opd b ON b.id=a.parent 
						WHERE 
							a.id=%d AND 
							a.active=%d",
						$_POST['id'],
						1
					),  ARRAY_A);

					if (empty($data)) {
						throw new Exception("Data tidak ditemukan!", 1);
					}

					echo json_encode([
						'status' => true,
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

	public function update_indikator_pokin()
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

					$input = json_decode(stripslashes($_POST['data']), true);

					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja$_prefix_opd 
						WHERE id!=%d 
							AND parent=%d 
							AND level=%d 
							AND active=%d 
							AND label_indikator_kinerja=%s$_where_opd
					", $input['id'], $input['parent'], $input['level'], 1, trim($input['indikator_label'])),  ARRAY_A);

					if (!empty($id)) {
						throw new Exception("Data sudah ada!", 1);
					}

					if ($_prefix_opd == '') {
						// untuk pemda
						$data = $wpdb->update('esakip_pohon_kinerja', [
							'label_indikator_kinerja' => trim($input['indikator_label']),
						], [
							'id' => $input['id'],
							'parent' => $input['parent'],
							'level' => $input['level'],
						]);
					} else {
						$data = $wpdb->update('esakip_pohon_kinerja' . $_prefix_opd, [
							'label_indikator_kinerja' => trim($input['indikator_label']),
						], [
							'id' => $input['id'],
							'parent' => $input['parent'],
							'level' => $input['level'],
							'id_skpd' => $id_skpd
						]);
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses ubah indikator!'
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

	public function delete_indikator_pokin()
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
						// untuk pemda
						$data = $wpdb->delete('esakip_pohon_kinerja', [
							'id' => $_POST['id']
						]);
					} else {
						// untuk opd
						$data = $wpdb->delete('esakip_pohon_kinerja' . $_prefix_opd, [
							'id' => $_POST['id'],
							'id_skpd' => $id_skpd
						]);
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses hapus indikator!'
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

	public function get_table_cascading()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

				$get_tujuan = $wpdb->get_results("
                    SELECT 
                    	* 
                    FROM esakip_rpd_tujuan
                    WHERE id_unik_indikator IS NULL
                     	AND active = 1
                ", ARRAY_A);

				if (!empty($get_tujuan)) {
					$counter = 1;
					$tbody = '';

					foreach ($get_tujuan as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['nama_cascading'] . "</td>";
						$tbody .= "<td>" . $vv['tujuan_teks'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="view_cascading(\'' . $vv['id'] . '\'); return false;" href="#" title="View"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_cascading_pemda(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $btn . "</td>";
						$tbody .= "</tr>";
					}

					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='8' class='text-center'>Tidak ada data tersedia</td></tr>";
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

	public function edit_cascading_pemda()
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
					$data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								*
							FROM esakip_rpd_tujuan
							WHERE id = %d
						", $_POST['id']),
						ARRAY_A
					);
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

	public function submit_edit_cascading()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil edit data!',
			'data' => array()
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id kosong!';
					die(json_encode($ret));
				} else if (empty($_POST['nama_cascading'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Nama Cascading kosong!';
					die(json_encode($ret));
				} else {
					$nama_cascading = $_POST['nama_cascading'];
					$data = array(
						'nama_cascading' => $nama_cascading,
						'update_at' => current_time('mysql')
					);
					$wpdb->update('esakip_rpd_tujuan', $data, array('id' => $_POST['id']));
				}
			} else {
				$ret['status']  = 'error';
				$ret['message'] = 'Api key tidak ditemukan!';
			}
		} else {
			$ret['status']  = 'error';
			$ret['message'] = 'Format Salah!';
		}

		die(json_encode($ret));
	}

	public function view_cascading_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'id Jadwal kosong!';
				} else if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'id kosong!';
				}

				if ($ret['status'] != 'error') {
					$id_jadwal = $_POST['id_jadwal'];
					$tujuan = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								*
							FROM esakip_rpd_tujuan
							WHERE id = %d
								AND active=1
						", $_POST['id']),
						ARRAY_A
					);
					$indikator_tujuan = $wpdb->get_results(
						$wpdb->prepare("
							SELECT 
								*
							FROM esakip_rpd_tujuan
							WHERE id_unik = %s
								AND active=1
								AND id_unik_indikator IS NOT NULL
						", $tujuan['id_unik']),
						ARRAY_A
					);
					if ($tujuan['id_isu'] != 0) {
						//cari misi berdasarkan isu
						$id_isu_rpjpd = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									id_kebijakan
								FROM esakip_rpjpd_isu
								WHERE id = %d
							", $tujuan['id_isu'])
						);
						$id_kebijakan_rpjpd = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									id_saspok
								FROM esakip_rpjpd_kebijakan
								WHERE id = %d
							", $id_isu_rpjpd)
						);
						$sasaran_rpjpd = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									id_misi
								FROM esakip_rpjpd_sasaran
								WHERE id = %d
							", $id_kebijakan_rpjpd)
						);
						$misi_rpjpd = $wpdb->get_results(
							$wpdb->prepare("
								SELECT 
									*
								FROM esakip_rpjpd_misi
								WHERE id = %d
							", $sasaran_rpjpd),
							ARRAY_A
						);
					}

					// misi rpjpd
					$misi_rpjpd_html = '';
					foreach ($misi_rpjpd as $misi) {
						$misi_rpjpd_html .= $misi['misi_teks'];
					}

					// sasaran rpd
					$sasaran = $wpdb->get_results(
						$wpdb->prepare("
						SELECT 
							*
						FROM esakip_rpd_sasaran
						WHERE kode_tujuan = %s
							AND active=1
							AND id_unik_indikator IS NULL
						", $tujuan['id_unik']),
						ARRAY_A
					);
					$jml_sasaran = count($sasaran);
					if ($jml_sasaran != 0) {
						$width_sasaran = 100 / $jml_sasaran;
						$width_ind_sasaran = 100 / $jml_sasaran;
						$colspan_sasaran = 100 / $jml_sasaran;
					}

					$indikator_sasaran_html = '
					<table>
						<tbody>';
					$data_sasaran = '';
					$indikator_sasarans = array();
					$skpd_programs = array();
					foreach ($sasaran as $sas) {
						// indikator sasaran sasaran rpd
						$indikator_sasaran = $wpdb->get_results(
							$wpdb->prepare("
								SELECT 
									*
								FROM esakip_rpd_sasaran
								WHERE id_unik = %s
								AND id_unik_indikator IS NOT NULL
								AND active=1
							", $sas['id_unik']),
							ARRAY_A
						);
						$width_ind_sasaran = $width_sasaran / count($indikator_sasaran);
						foreach ($indikator_sasaran as $ind) {
							$data_sasaran .= '<td class="text-center" width="' . $width_ind_sasaran . '%"><button class="btn btn-lg btn-warning">' . $ind['indikator_teks'] . '</button></td>';

							// indikator sasaran sasaran rpd
							$skpd_program = $wpdb->get_results(
								$wpdb->prepare("
									SELECT 
										*
									FROM esakip_rpd_program
									WHERE kode_sasaran = %s
										AND 1=1
										AND id_unik_indikator IS NOT NULL
										AND active=1
								", $sas['id_unik']),
								ARRAY_A
							);
						}
						if (empty($data_sasaran)) {
							$data_sasaran = '<td class="text-center" width="' . $width_ind_sasaran . '%"><button class="btn btn-lg btn-warning"></button></td>';
						}
						$indikator_sasarans = array_merge($indikator_sasarans, $indikator_sasaran);
						$skpd_programs = array_merge($skpd_programs, $skpd_program);
					}
					$indikator_sasaran_html .= $data_sasaran . '
						</tbody>
					</table>';

					$jml_ind_sasaran = count($indikator_sasarans);

					$colspan_tujuan = $jml_ind_sasaran;
					if ($jml_ind_sasaran % 2 == 1) {
						$colspan_sasaran;
					}

					// indikator tujuan rpd
					$indikator_tujuan_html = '';
					$data = '';
					foreach ($indikator_tujuan as $ind) {
						$data .= '<td class="text-center" colspan="' . $colspan_tujuan . '"><button class="btn btn-lg btn-warning">' . $ind['indikator_teks'] . '</button></td>';
					}
					if (empty($data)) {
						$data = '<td class="text-center" colspan="' . $colspan_tujuan . '"><button class="btn btn-lg btn-warning"></button></td>';
					}
					$indikator_tujuan_html .= $data;

					$sasaran_html = '
					<table>
						<tbody>';
					$data = '';
					foreach ($sasaran as $sas) {
						$data .= '<td class="text-center" width="' . $width_sasaran . '%"><button class="btn btn-lg btn-warning">' . $sas['sasaran_teks'] . '</button></td>';
					}
					if (empty($data)) {
						$data = '<td class="text-center"><button class="btn btn-lg btn-warning"></button></td>';
					}
					$sasaran_html .= $data . '
						</tbody>
					</table>';

					$skpd_program_html = '
					<table>
						<tbody>';
					$data = '';
					foreach ($skpd_programs as $skpd) {
						$data .= '<td class="text-center"><button class="btn btn-lg btn-warning">' . $skpd['nama_skpd'] . '</button></td>';
					}
					if (empty($data)) {
						$data = '<td class="text-center"><button class="btn btn-lg btn-warning"></button></td>';
					}
					$skpd_program_html .= $data . '
						</tbody>
					</table>';

					// render html
					$html = '
						<h1 class="text-center">' . $tujuan['nama_cascading'] . '</h1>
						<table id="tabel-cascading">
							<tbody>
								<tr>
									<td class="text-center" style="width: 200px;"><button class="btn btn-lg btn-info">MISI RPJPD</button></td>
									<td class="text-center" colspan="' . $colspan_tujuan . '"><button class="btn btn-lg btn-warning">' . $misi_rpjpd_html . '</button></td>
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">TUJUAN RPD</button></td>
									<td class="text-center" colspan="' . $colspan_tujuan . '"><button class="btn btn-lg btn-warning">' . $tujuan['tujuan_teks'] . '</button></td>
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">INDIKATOR TUJUAN RPD</button></td>
									' . $indikator_tujuan_html . '
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">SASARAN RPD</button></td>
									<td class="text-center">' . $sasaran_html . '</td>
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">INDIKATOR SASARAN RPD</button></td>
									<td class="text-center">' . $indikator_sasaran_html . '</td>
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">URUSAN PENGAMPU</button></td>
									<td class="text-center">' . $skpd_program_html . '</td>
								</tr>
							</tbody>
						</table>
						';
					$ret['html'] = $html;
				}
			} else {
				$ret['status']  = 'error';
				$ret['message'] = 'Api key tidak ditemukan!';
			}
		} else {
			$ret['status']  = 'error';
			$ret['sql']  = $wpdb->last_query;
			$ret['message'] = 'Format Salah!';
		}

		die(json_encode($ret));
	}

	public function get_table_skpd_pohon_kinerja()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_periode'])) {
					$id_jadwal = $_POST['id_periode'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				$penyusunan_pohon_kinerja_opd = false;
				if (!empty($_POST['penyusunan_pohon_kinerja_opd'])) {
					$penyusunan_pohon_kinerja_opd = ($_POST['penyusunan_pohon_kinerja_opd']) ?: false;
				}

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

				$unit = $wpdb->get_results(
					$wpdb->prepare("
						SELECT 
							nama_skpd, 
							id_skpd, 
							kode_skpd, 
							nipkepala 
						FROM esakip_data_unit 
						WHERE tahun_anggaran=%d
						AND active=1 
						AND is_skpd=1 
						ORDER BY kode_skpd ASC
					", $tahun_anggaran_sakip),
					ARRAY_A
				);

				$periode = $wpdb->get_row(
					$wpdb->prepare("
					SELECT 
						*
					FROM esakip_data_jadwal
					WHERE id=%d
					  AND status = 1
				", $id_jadwal),
					ARRAY_A
				);

				if (!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1) {
					$tahun_periode_selesai = $periode['tahun_selesai_anggaran'];
				} else {
					$tahun_periode_selesai = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
				}

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {

						if ($penyusunan_pohon_kinerja_opd == false) {
							$detail_pohon_kinerja = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Detail Dokumen Pohon Kinerja dan Cascading | ' . $periode['nama_jadwal'] . ' ' . 'Periode ' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode_selesai  . ' Perangkat Daerah',
								'content' => '[dokumen_detail_pohon_kinerja_dan_cascading periode=' . $id_jadwal . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));

							$tbody .= "<tr>";
							$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
							$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

							$jumlah_dokumen = $wpdb->get_var(
								$wpdb->prepare("
									SELECT 
										COUNT(id)
									FROM esakip_pohon_kinerja_dan_cascading
									WHERE id_skpd = %d
									  AND id_jadwal = %d
									  AND active = 1
								", $vv['id_skpd'], $id_jadwal)
							);

							$btn = '<div class="btn-action-group">';
							$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_pohon_kinerja['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
							$btn .= '</div>';

							$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
							$tbody .= "<td>" . $btn . "</td>";

							$tbody .= "</tr>";
						} else if ($penyusunan_pohon_kinerja_opd == true) {
							$detail_penyusunan_pohon_kinerja_opd = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Detail Dokumen Pohon Kinerja Perangkat Daerah | ' . $periode['nama_jadwal'] . ' ' . 'Periode ' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode_selesai  . ' Perangkat Daerah',
								'content' => '[penyusunan_pohon_kinerja_opd periode=' . $id_jadwal . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));

							$tbody .= "<tr>";
							$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
							$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

							$jumlah_dokumen = $wpdb->get_var(
								$wpdb->prepare("
									SELECT 
										COUNT(id)
									FROM esakip_pohon_kinerja_opd
									WHERE parent=0 
										AND level=1 
										AND active=1
										AND id_skpd = %d
										AND id_jadwal = %d
								", $vv['id_skpd'], $id_jadwal)
							);

							$btn = '<div class="btn-action-group">';
							$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_penyusunan_pohon_kinerja_opd['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
							$btn .= '</div>';

							$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
							$tbody .= "<td>" . $btn . "</td>";

							$tbody .= "</tr>";
						}
					}
					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
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

	public function submit_tahun_pohon_kinerja()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah data!',
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id'])) {
					$id = $_POST['id'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id kosong!';
				}
				if (!empty($_POST['id_jadwal'])) {
					$tahun_periode = $_POST['id_jadwal'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Periode kosong!';
				}

				if (!empty($id) && !empty($tahun_periode)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pohon_kinerja_dan_cascading 
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_pohon_kinerja_dan_cascading',
							array(
								'id_jadwal' => $tahun_periode,
							),
							array('id' => $id),
							array('%d'),
						);

						if ($update_result === false) {
							$ret = array(
								'status' => 'error',
								'message' => 'Gagal memperbarui data di dalam tabel!'
							);
						}
					} else {
						$ret = array(
							'status' => 'error',
							'message' => 'Data dengan ID yang diberikan tidak ditemukan!'
						);
					}
				} else {
					$ret = array(
						'status' => 'error',
						'message' => 'ID atau tahun anggaran tidak valid!'
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

	public function create_croscutting()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = $id_skpd_croscutting = $keterangan_croscutting = '';
					$input = json_decode(stripslashes($_POST['data']), true);

					if(!empty($_POST['tipe_pokin'])){
						if(!empty($_POST['id_skpd'])){
							if(!empty($input['skpdCroscutting']) && !empty($input['keteranganCroscutting'])){
								$id_skpd_croscutting = $input['skpdCroscutting'];
								$keterangan_croscutting = $input['keteranganCroscutting'];
							}else{
								throw new Exception("Input Croscutting wajib diisi!", 1);		
							}

							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_pokin'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_pokin'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					$parent_pokin_id = $input['parentCroscutting'];

					$data_cek_croscutting = $wpdb->get_row($wpdb->prepare("
						SELECT *
						FROM esakip_croscutting_opd
						WHERE parent_pohon_kinerja=%d
						AND id_skpd_croscutting=%d
						AND keterangan=%s
					", $parent_pokin_id, $id_skpd_croscutting, $keterangan_croscutting)
					, ARRAY_A);

					if(empty($data_cek_croscutting)){
						$insert_crocutting = $wpdb->insert('esakip_croscutting_opd', [
							'parent_pohon_kinerja' => $parent_pokin_id,
							'keterangan' => trim($keterangan_croscutting),
							'id_skpd_croscutting' => $id_skpd_croscutting,
							'active' => 1,
							'status_croscutting' => 0,
							'created_at' => current_time('mysql'),
							'updated_at' => current_time('mysql')
						]);
					}else{
						throw new Exception("Data Croscutting sudah ada!", 1);
					}

					echo json_encode([
		    			'status' => true,
		    			'message' => 'Sukses Croscuting!',
		    		]);exit();
				}else{
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

	public function edit_croscutting()
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

					$data_croscutting = array();
					if(!empty($_prefix_opd) && $_prefix_opd == "_opd"){
						$data_croscutting = $wpdb->get_row($wpdb->prepare("
								SELECT 
									keterangan,
									parent_pohon_kinerja,
									id_skpd_croscutting
								FROM esakip_croscutting_opd
								WHERE id=%d 
									AND active=%d
							", $_POST['id'], 1),  ARRAY_A);
					}

					if(empty($data_croscutting)){
						throw new Exception("Data tidak ditemukan!", 1);
					}

					echo json_encode([
		    			'status' => true,
						'data_croscutting' => $data_croscutting
		    		]);exit();
				}else{
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

	public function update_croscutting(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {
					$input = json_decode(stripslashes($_POST['data']), true);

					$_prefix_opd = $_where_opd = $id_skpd = $id_skpd_croscutting = $keterangan_croscutting = '';
					if(!empty($_POST['tipe_pokin'])){
						if(!empty($_POST['id_skpd'])){
							if(!empty($input['skpdCroscutting']) && !empty($input['keteranganCroscutting'])){
								$id_skpd_croscutting = $input['skpdCroscutting'];
								$keterangan_croscutting = $input['keteranganCroscutting'];
							}else{
								throw new Exception("Input Croscutting wajib diisi!", 1);		
							}
							
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_pokin'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_pokin'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}
					
					$data_cek_croscutting = $wpdb->get_row($wpdb->prepare("
						SELECT *
						FROM esakip_croscutting_opd
						WHERE parent_pohon_kinerja =%d
							AND id !=%d
							AND id_skpd_croscutting =%d
							AND keterangan =%s
					", $input['idParentCroscutting'], $input['id'], $id_skpd_croscutting, $keterangan_croscutting)
					, ARRAY_A);

					if(empty($data_cek_croscutting)){
						$update_crocutting = $wpdb->update('esakip_croscutting_opd', 
							array(
								'keterangan' => trim($keterangan_croscutting),
								'id_skpd_croscutting' => $id_skpd_croscutting,
								'active' => 1,
								'updated_at' => current_time('mysql')
							),
							array(
								'id' => $input['id']
							)
						);

						if($update_crocutting === false){
							error_log("Error updating croscutting: " . $wpdb->last_error);
						}
					}else{
						throw new Exception("Data Sudah Ada", 1);
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses ubah Croscutting!'
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

	public function delete_croscutting()
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

					// untuk opd
					$data = $wpdb->delete('esakip_croscutting_opd', [
						'id' => $_POST['id']
					]);

					echo json_encode([
						'status' => true,
						'message' => 'Sukses hapus Croscutting!'
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
