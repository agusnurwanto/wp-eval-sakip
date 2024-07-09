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

		$tipe = 'pemda';
		require_once ESAKIP_PLUGIN_PATH . 'public/partials/pohon-kinerja/wp-eval-sakip-view-pohon-kinerja.php';
	}

	public function view_pohon_kinerja_opd($atts)
	{
		if (!empty($_GET) && !empty($_GET['post'])) {
			return '';
		}

		$tipe = 'opd';
		require_once ESAKIP_PLUGIN_PATH . 'public/partials/pohon-kinerja/wp-eval-sakip-view-pohon-kinerja.php';
	}

	public function view_crosscutting_pemda($atts)
	{
		if (!empty($_GET) && !empty($_GET['post'])) {
			return '';
		}

		$tipe = 'pemda';
		require_once ESAKIP_PLUGIN_PATH . 'public/partials/pohon-kinerja/wp-eval-sakip-view_crosscutting_pemda.php';
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
								FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
								WHERE id=a.id ' . $_where_opd . '
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
									FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
									WHERE id=a.id ' . $_where_opd . '
								) ' . $_where_opd . '
							) label_parent_1,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
								WHERE id=a.id ' . $_where_opd . '
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
										FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
										WHERE id=a.id ' . $_where_opd . '
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
									WHERE id=a.id ' . $_where_opd . '
								) ' . $_where_opd . '
							) label_parent_2,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja' . $_prefix_opd . ' 
								WHERE id=a.id ' . $_where_opd . '
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
					} else if ($_prefix_opd == '_opd') {
						$dataPokin = $wpdb->get_results($wpdb->prepare(
							"
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
					if ($_prefix_opd == '') {
						if (!empty($label_parent)) {
							$dataParent = $wpdb->get_results($wpdb->prepare(
								"
									SELECT 
										" . $label_parent . "
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
					} else if ($_prefix_opd == '_opd') {
						if (!empty($label_parent)) {
							$dataParent = $wpdb->get_results($wpdb->prepare(
								"
									SELECT 
										" . $label_parent . "
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
						if (empty($data['data'][$pokin['id']])) {
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

					if (!empty($_POST['tipe_pokin'])) {
						if (!empty($_POST['id_skpd'])) {
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

					if ($_prefix_opd == "") {
						// untuk pokin pepmda //////////////////////////////////////////////////////////
						$data = $wpdb->insert('esakip_pohon_kinerja', [
							'label' => trim($input['label']),
							'parent' => $input['parent'],
							'level' => $input['level'],
							'id_jadwal' => $input['id_jadwal'],
							'active' => 1
						]);
					} else {
						// untuk pokin opd //////////////////////////////////////////////////////////
						$data = $wpdb->insert('esakip_pohon_kinerja' . $_prefix_opd, [
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
					if (!empty($_prefix_opd) && $_prefix_opd == "_opd" && !empty($data)) {
						$data_croscutting = $wpdb->get_results($wpdb->prepare("
								SELECT 
									*
								FROM esakip_croscutting_opd
								WHERE parent_pohon_kinerja=%d 
									AND active=%d
							", $_POST['id'], 1),  ARRAY_A);

						$data_croscutting_pengusul = $wpdb->get_results($wpdb->prepare("
							SELECT 
								cc.*,
								pk.id_skpd as id_skpd_parent
							FROM esakip_croscutting_opd as cc
							JOIN esakip_pohon_kinerja_opd as pk
							ON cc.parent_pohon_kinerja = pk.id
							WHERE cc.id_skpd_croscutting=%d
								AND cc.status_croscutting=1 
								AND cc.active=1
								AND cc.parent_croscutting=%d
						", $id_skpd, $_POST['id']),  ARRAY_A);
						if(!empty($data_croscutting) && !empty($data_croscutting_pengusul)){
							$data_croscutting = array_merge($data_croscutting,$data_croscutting_pengusul);
						}
					}

					$table_croscutting = '';
					$no = 1;
					if (!empty($data_croscutting)) {
						$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
						foreach ($data_croscutting as $k_cross => $v_cross) {
							if ($v_cross['is_lembaga_lainnya'] == 1) {
								$data_perangkat = $wpdb->get_row(
									$wpdb->prepare("
										SELECT 
											nama_lembaga as nama_perangkat,
											id,
											tahun_anggaran
										FROM esakip_data_lembaga_lainnya 
										WHERE active=1
										AND id=%d
										AND tahun_anggaran=%d
										GROUP BY id
										ORDER BY nama_lembaga ASC
									", $v_cross['id_skpd_croscutting'], $tahun_anggaran_sakip),
									ARRAY_A
								);
							} else {
								$data_perangkat = $wpdb->get_row(
									$wpdb->prepare("
										SELECT 
											nama_skpd as nama_perangkat,
											id_skpd,
											tahun_anggaran
										FROM esakip_data_unit 
										WHERE active=1 
										AND is_skpd=1 
										AND id_skpd=%d
										AND tahun_anggaran=%d
										GROUP BY id_skpd
										ORDER BY kode_skpd ASC
									", $v_cross['id_skpd_croscutting'], $tahun_anggaran_sakip),
									ARRAY_A
								);
							}

							if(!empty($v_cross['id_skpd_parent'])){
								$this_data_id_skpd = $v_cross['id_skpd_parent'];
							}else{
								$this_data_id_skpd = $id_skpd;
							}

							$this_data_perangkat = $wpdb->get_row(
								$wpdb->prepare("
									SELECT 
										nama_skpd as nama_perangkat,
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

							$table_croscutting .= '<td>' . $no++ . '</td>';
							$table_croscutting .= '<td>' . $this_data_perangkat['nama_perangkat'] . '</td>';
							$table_croscutting .= '<td>' . $v_cross['keterangan'] . '</td>';
							$table_croscutting .= '<td>' . $v_cross['keterangan_croscutting'] . '</td>';
							$table_croscutting .= '<td>' . $data_perangkat['nama_perangkat'] . '</td>';
							$table_croscutting .= '<td>' . $status_croscutting . '</td>';

							$aksi = '';
							if($status_croscutting == 'disetujui'){
								$aksi .= '<a href="javascript:void(0)" class="btn btn-sm btn-primary" data-id="' . $v_cross['id'] . '" href="#" title="Croscutting Disetujui">Disetujui</a>';
							}else{
								$aksi .= '<a href="javascript:void(0)" class="btn btn-sm btn-warning edit-croscutting" data-id="' . $v_cross['id'] . '" href="#" title="Edit Croscutting"><span class="dashicons dashicons-edit"></span></a>';
								$aksi .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger delete-croscutting" data-id="' . $v_cross['id'] . '" style="margin-left: 5px;" href="#" title="Hapus Croscutting"><span class="dashicons dashicons-trash"></span></a>';
							}

							if(!empty($v_cross['id_skpd_parent'])){
								$aksi = '<a href="javascript:void(0)" class="btn btn-sm btn-success verifikasi-croscutting" data-id="' . $v_cross['id'] . '" data-skpd-asal="'. $this_data_perangkat['nama_perangkat'] .'" data-keterangan-asal="'. $v_cross['keterangan'] .'" href="#" title="Verifikasi Croscutting"><span class="dashicons dashicons-yes"></span></a>';
							}

							$table_croscutting .= '<td class="text-center">' . $aksi . '</td>';

							$table_croscutting .= '</tr>';
						}
					}

					if (empty($data)) {
						throw new Exception("Data tidak ditemukan!", 1);
					}

					echo json_encode([
						'status' => true,
						'data' => $data,
						'data_croscutting' => $table_croscutting
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

	public function update_pokin()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					$input = json_decode(stripslashes($_POST['data']), true);

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

					if ($_prefix_opd == '') {
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
					} else {
						// untuk pokin opd  //////////////////////////////////////////////////////////////////////////////
						$data = $wpdb->update('esakip_pohon_kinerja' . $_prefix_opd, [
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

					// cek croscutting
					$croscutting = $wpdb->get_row($wpdb->prepare("
						SELECT 
							pk.id as id_pokin,
							cc.id as id_croscutting
						FROM esakip_pohon_kinerja$_prefix_opd as pk
						JOIN esakip_croscutting$_prefix_opd as cc
						ON pk.id = cc.parent_pohon_kinerja
						WHERE pk.id=%d  
							AND pk.level=%d 
							AND cc.active=%d
							AND pk.active=%d$_where_opd
					", $_POST['id'], $_POST['level'], 1, 1),  ARRAY_A);

					if (!empty($croscutting)) {
						throw new Exception("Croscutting harus dihapus dulu!", 1);
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

	public function get_table_crosscutting_pemda()
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
					$ret['message'] = 'Id periode kosong!';
					die(json_encode($ret));
				}
				$id_jadwal = $_POST['id_jadwal'];
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
						$detail_crosscutting_pemda = $this->functions->generatePage(array(
							'nama_page' => 'Crosscutting Pemerintah Daerah | ' . $periode['nama_jadwal'] . ' ' . 'Periode ' . $periode['tahun_anggaran'] . ' - ' . $periode['tahun_anggaran_selesai']  . ' Perangkat Daerah',
							'content' => '[detail_croscutting_pemda periode=' . $id_jadwal . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['nama_crosscutting'] . "</td>";
						$tbody .= "<td>" . $vv['tujuan_teks'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="editCrosscuttingPemda(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-secondary" onclick="toDetailUrl(\'' . $detail_crosscutting_pemda['url'] . '&id_tujuan=' . $vv['id_unik'] . '\'); return false;" href="#" title="Detail Crosscutting"><span class="dashicons dashicons-controls-forward"></span></button>';
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

	public function edit_crosscutting_pemda_tujuan()
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

	public function submit_edit_crosscutting_pemda()
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
				} else if (empty($_POST['nama_crosscutting'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Nama Crosscutting kosong!';
					die(json_encode($ret));
				} else {
					$nama_crosscutting = $_POST['nama_crosscutting'];
					$data = array(
						'nama_crosscutting' => $nama_crosscutting,
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
					}

					$indikator_sasaran_html = '
					<table>
						<tbody>';
					$data_sasaran = '';
					$data_program = '';
					$indikator_sasarans = array();
					$skpd_program_html = '
					<table>
						<tbody>';
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
							$data_sasaran .= '<td class="text-center" width="' . $width_ind_sasaran . '%"><button class="btn btn-lg btn-warning" style="text-transform:uppercase;">' . $ind['indikator_teks'] . '</button></td>';
							$data_program .= '<td class="text-center" width="' . $width_ind_sasaran . '%"><ul class="list-skpd" style="list-style-type: none; margin: 0; padding: 0;">';

							// indikator sasaran sasaran rpd
							$skpd_program = $wpdb->get_results(
								$wpdb->prepare("
									SELECT 
										*
									FROM esakip_rpd_program
									WHERE kode_sasaran = %s
									  AND id_unik_indikator_sasaran =%s
									  AND id_unik_indikator IS NOT NULL
									  AND active=1
								", $ind['id_unik'], $ind['id_unik_indikator']),
								ARRAY_A
							);
							foreach ($skpd_program as $prog) {
								$data_program .= '<li style="margin-bottom:14px;"><button class="btn btn-lg btn-warning" style="text-transform:uppercase;">' . $prog['nama_skpd'] . '</button></li>';
							}
							if (empty($skpd_program)) {
								$data_program .= '<li style="margin-bottom:14px;"><button class="btn btn-lg btn-warning"></button></li>';
							}
							$data_program .= '</ul></td>';
						}
						if (empty($data_sasaran)) {
							$data_sasaran = '<td class="text-center" width="' . $width_ind_sasaran . '%"><button class="btn btn-lg btn-warning"></button></td>';
							$data_program = '<td class="text-center" width="' . $width_ind_sasaran . '%"><button class="btn btn-lg btn-warning"></button></td>';
						}
						$indikator_sasarans = array_merge($indikator_sasarans, $indikator_sasaran);
					}
					$indikator_sasaran_html .= $data_sasaran . '
						</tbody>
					</table>';
					$skpd_program_html .= $data_program . '
						</tbody>
					</table>';

					$jml_ind_sasaran = count($indikator_sasarans);

					$colspan_tujuan = $jml_ind_sasaran;

					// indikator tujuan rpd
					$indikator_tujuan_html = '';
					$data = '';
					foreach ($indikator_tujuan as $ind) {
						$data .= '<td class="text-center" colspan="' . $colspan_tujuan . '"><button class="btn btn-lg btn-warning" style="text-transform:uppercase;">' . $ind['indikator_teks'] . '</button></td>';
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
						$data .= '<td class="text-center" width="' . $width_sasaran . '%"><button class="btn btn-lg btn-warning" style="text-transform:uppercase;">' . $sas['sasaran_teks'] . '</button></td>';
					}
					if (empty($data)) {
						$data = '<td class="text-center"><button class="btn btn-lg btn-warning"></button></td>';
					}
					$sasaran_html .= $data . '
						</tbody>
					</table>';
					// die(print_r($skpd_programs));

					// $data = '';
					// foreach ($sasaran as $sas) {
					// 	$data .= '<td class="text-center" style="width: ' . $width_ind_sasaran . '%">';
					// 	if (!empty($skpd_programs[0]['id_unik'])) {
					// 		$data .= '<ul class="list-skpd" style="list-style-type: none;">';
					// 		foreach ($skpd_programs as $k => $v) {
					// 			$data .= '<li style="margin-bottom:14px"><button class="btn btn-lg btn-warning">' . $v['nama_skpd'] . '</button></li>';
					// 		}
					// 		$data .= '</ul>';
					// 	} else {
					// 		$data .= '<button class="btn btn-lg btn-warning">belum</button>';
					// 	}
					// 	$data .= '</td>';
					// }

					// render html
					$html = '
					<div class="text-center" id="action-sakip">
						<button class="btn btn-primary btn-large" onclick="window.print();"><i class="dashicons dashicons-printer"></i> Cetak / Print</button>
					</div>
						<h1 class="text-center">' . $tujuan['nama_cascading'] . '</h1>
						<table id="tabel-cascading">
							<tbody>
								<tr>
									<td class="text-center" style="width: 200px;"><button class="btn btn-lg btn-info">MISI RPJPD</button></td>
									<td class="text-center" colspan="' . $colspan_tujuan . '"><button class="btn btn-lg btn-warning" style="text-transform:uppercase;">' . $misi_rpjpd_html . '</button></td>
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">TUJUAN RPD</button></td>
									<td class="text-center" colspan="' . $colspan_tujuan . '"><button class="btn btn-lg btn-warning" style="text-transform:uppercase;">' . $tujuan['tujuan_teks'] . '</button></td>
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">INDIKATOR TUJUAN RPD</button></td>
									' . $indikator_tujuan_html . '
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">SASARAN RPD</button></td>
									<td class="text-center" colspan=' . $colspan_tujuan . '>' . $sasaran_html . '</td>
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">INDIKATOR SASARAN RPD</button></td>
									<td class="text-center" colspan=' . $colspan_tujuan . '>' . $indikator_sasaran_html . '</td>
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">URUSAN PENGAMPU</button></td>
									<td class="text-center" colspan=' . $colspan_tujuan . '>' . $skpd_program_html . '</td>
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

					if (!empty($_POST['tipe_pokin'])) {
						if (!empty($_POST['id_skpd'])) {
							if (!empty($input['keteranganCroscutting'])) {
								if (!empty($input['skpdCroscutting']) || !empty($input['lembagaLainnyaCroscutting'])) {
									$id_skpd_croscutting = $input['skpdCroscutting'];
									$id_lembaga_lainnya = $input['lembagaLainnyaCroscutting'];
									$keterangan_croscutting = $input['keteranganCroscutting'];
								} else {
									throw new Exception("Input Croscutting wajib diisi!", 1);
								}
							} else {
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

					// input skpd
					if (!empty($id_skpd_croscutting)) {
						foreach ($id_skpd_croscutting as $k_skpd => $v_skpd) {
							$data_cek_croscutting = $wpdb->get_row(
								$wpdb->prepare("
								SELECT *
								FROM esakip_croscutting_opd
								WHERE parent_pohon_kinerja=%d
								AND id_skpd_croscutting=%d
								AND keterangan=%s
							", $parent_pokin_id, $v_skpd, $keterangan_croscutting),
								ARRAY_A
							);

							if (empty($data_cek_croscutting)) {
								$insert_crocutting = $wpdb->insert('esakip_croscutting_opd', [
									'parent_pohon_kinerja' => $parent_pokin_id,
									'keterangan' => trim($keterangan_croscutting),
									'id_skpd_croscutting' => $v_skpd,
									'active' => 1,
									'status_croscutting' => 0,
									'created_at' => current_time('mysql'),
									'updated_at' => current_time('mysql')
								]);
							} else {
								throw new Exception("Data Croscutting sudah ada!", 1);
							}
						}
					}

					// input lembaga vertikal
					if (!empty($id_lembaga_lainnya)) {
						foreach ($id_lembaga_lainnya as $k_lainnya => $v_lainnya) {
							$data_cek_croscutting_lainnya = $wpdb->get_row(
								$wpdb->prepare("
								SELECT *
								FROM esakip_croscutting_opd
								WHERE parent_pohon_kinerja=%d
								AND id_skpd_croscutting=%d
								AND keterangan=%s
								AND is_lembaga_lainnya=1
							", $parent_pokin_id, $v_lainnya, $keterangan_croscutting),
								ARRAY_A
							);

							if (empty($data_cek_croscutting_lainnya)) {
								$insert_crocutting = $wpdb->insert('esakip_croscutting_opd', [
									'parent_pohon_kinerja' => $parent_pokin_id,
									'keterangan' => trim($keterangan_croscutting),
									'id_skpd_croscutting' => $v_lainnya,
									'active' => 1,
									'status_croscutting' => 1,
									'created_at' => current_time('mysql'),
									'updated_at' => current_time('mysql'),
									'is_lembaga_lainnya' => 1
								]);
							} else {
								throw new Exception("Data Croscutting sudah ada!", 1);
							}
						}
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses Croscuting!',
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
					if (!empty($_prefix_opd) && $_prefix_opd == "_opd") {
						$data_croscutting = $wpdb->get_row($wpdb->prepare("
								SELECT 
									keterangan,
									parent_pohon_kinerja,
									id_skpd_croscutting,
									is_lembaga_lainnya
								FROM esakip_croscutting_opd
								WHERE id=%d 
									AND active=%d
							", $_POST['id'], 1),  ARRAY_A);
					}

					if (empty($data_croscutting)) {
						throw new Exception("Data tidak ditemukan!", 1);
					}

					echo json_encode([
						'status' => true,
						'data_croscutting' => $data_croscutting
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

	public function update_croscutting()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					$input = json_decode(stripslashes($_POST['data']), true);

					$_prefix_opd = $_where_opd = $id_skpd = $id_skpd_croscutting = $keterangan_croscutting = $id_lembaga_lainnya = '';
					if (!empty($_POST['tipe_pokin'])) {
						if (!empty($_POST['id_skpd'])) {
							if (!empty($input['keteranganCroscutting'])) {
								if (!empty($input['skpdCroscutting']) || !empty($input['lembagaLainnyaCroscutting'])) {
									$id_skpd_croscutting = $input['skpdCroscutting'];
									$id_lembaga_lainnya = $input['lembagaLainnyaCroscutting'];
									$keterangan_croscutting = $input['keteranganCroscutting'];
								} else {
									throw new Exception("Input Croscutting wajib diisi!", 1);
								}
							} else {
								throw new Exception("Input Croscutting wajib diisi!", 1);
							}

							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_pokin'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_pokin'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					// update skpd
					if (!empty($id_skpd_croscutting)) {
						$data_cek_croscutting = $wpdb->get_row(
							$wpdb->prepare("
							SELECT *
							FROM esakip_croscutting_opd
							WHERE parent_pohon_kinerja =%d
								AND id !=%d
								AND id_skpd_croscutting =%d
								AND keterangan =%s
								AND active=1
						", $input['idParentCroscutting'], $input['id'], $id_skpd_croscutting, $keterangan_croscutting),
							ARRAY_A
						);

						if (empty($data_cek_croscutting)) {
							$update_crocutting = $wpdb->update(
								'esakip_croscutting_opd',
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

							$cek = $wpdb->last_query;

							if ($update_crocutting === false) {
								error_log("Error updating croscutting: " . $wpdb->last_error);
							}
						} else {
							throw new Exception("Data Sudah Ada", 1);
						}
					}

					// update lembaga vertikal
					if (!empty($id_lembaga_lainnya)) {
						$data_cek_croscutting = $wpdb->get_row(
							$wpdb->prepare("
							SELECT *
							FROM esakip_croscutting_opd
							WHERE parent_pohon_kinerja =%d
								AND id !=%d
								AND id_skpd_croscutting =%d
								AND keterangan =%s
								AND active=1
								AND is_lembaga_lainnya=1
						", $input['idParentCroscutting'], $input['id'], $id_lembaga_lainnya, $keterangan_croscutting),
							ARRAY_A
						);

						if (empty($data_cek_croscutting)) {
							$update_crocutting = $wpdb->update(
								'esakip_croscutting_opd',
								array(
									'keterangan' => trim($keterangan_croscutting),
									'id_skpd_croscutting' => $id_lembaga_lainnya,
									'active' => 1,
									'updated_at' => current_time('mysql')
								),
								array(
									'id' => $input['id']
								)
							);

							$cek2 = $wpdb->last_query;

							if ($update_crocutting === false) {
								error_log("Error updating croscutting: " . $wpdb->last_error);
							}
						} else {
							throw new Exception("Data Sudah Ada", 1);
						}
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
	
	public function verify_croscutting()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					$input = json_decode(stripslashes($_POST['data']), true);

					$_prefix_opd = $_where_opd = $id_skpd = $id_skpd_croscutting = $keterangan_croscutting = $id_lembaga_lainnya = $keterangan_tolak = '';
					if(!empty($input['idCroscutting'])){
						if (!empty($_POST['tipe_pokin'])) {
							if (!empty($_POST['id_skpd'])) {
								if (!empty($input['verify_cc']) || $input['verify_cc'] == 0) {
									$status_verify = $input['verify_cc'] == 1 ? 1 : 2;
									
									if(!empty($input['levelPokinCroscutting']) && $input['verify_cc'] == 1){
										$keterangan_croscutting = $input['keterangan_cc'];
									}else if(!empty($input['keterangan_cc_tolak']) && $input['verify_cc'] == 0){
										$keterangan_tolak = $input['keterangan_cc_tolak'];
									}else{
										throw new Exception("Ada yang kosong, wajib diisi!", 1);	
									}
								} else {
									throw new Exception("Verifikasi Croscutting Ditolak!", 1);
								}
	
								$id_skpd = $_POST['id_skpd'];
								$_prefix_opd = $_POST['tipe_pokin'] == "opd" ? "_opd" : "";
								$_where_opd = $_POST['tipe_pokin'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
							} else {
								throw new Exception("Id SKPD tidak ditemukan!", 1);
							}
						}
					}else{
						throw new Exception("Ada data yang kosong!", 1);
					}

					$data_verify_croscutting = $wpdb->get_row(
						$wpdb->prepare("
						SELECT *
						FROM esakip_croscutting_opd
						WHERE id=%d
							AND active=1
					", $input['idCroscutting']),
						ARRAY_A
					);

					$cek_pohon_kinerja = $wpdb->get_row(
						$wpdb->prepare("
						SELECT *
						FROM esakip_pohon_kinerja_opd
						WHERE id=%d
							AND active=1
					", $input['levelPokinCroscutting']),
						ARRAY_A
					);

					if(empty($cek_pohon_kinerja) && $input['verify_cc'] == 1){
						throw new Exception("Verifikasi gagal!", 1);		
					}

					if(!empty($data_verify_croscutting)){
						$opsi = array(
							'keterangan_croscutting' => trim($keterangan_croscutting),
							'status_croscutting' => $status_verify,
							'keterangan_tolak' => $keterangan_tolak,
							'updated_at' => current_time('mysql')
						);
						if(!empty($cek_pohon_kinerja) && $input['verify_cc'] == 1){
							$opsi['parent_croscutting'] = $input['levelPokinCroscutting'];
						}
						
						$verify_crocutting = $wpdb->update(
							'esakip_croscutting_opd',
							$opsi,
							array(
								'id' => $input['idCroscutting']
							)
						);

						if ($verify_crocutting === false) {
							error_log("Error updating croscutting: " . $wpdb->last_error);
						}
					}else{
						throw new Exception("Verifikasi gagal!", 1);	
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses Verifikasi Croscutting!'
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

	function get_pokin($opsi)
	{
		global $wpdb;
		$data_ret = array();
		$table = 'esakip_pohon_kinerja';
		$where_skpd = '';
		if ($opsi['tipe'] == 'opd') {
			$table = 'esakip_pohon_kinerja_opd';
			$where_skpd = $wpdb->prepare('AND id_skpd=%d', $opsi['id_skpd']);
		}
		if ($opsi['level'] == 1) {
			$pohon_kinerja = $wpdb->get_results($wpdb->prepare("
				SELECT 
					* 
				FROM $table 
				WHERE id=%d
					AND parent=0 
					AND level=%d 
					AND active=1 
					AND id_jadwal=%d 
					$where_skpd
				ORDER BY id
			", $opsi['id'], $opsi['level'], $opsi['periode']), ARRAY_A);
		} else {
			$pohon_kinerja = $wpdb->get_results($wpdb->prepare("
				SELECT 
					* 
				FROM $table 
				WHERE parent=%d
					AND level=%d 
					AND active=1 
					AND id_jadwal=%d
					$where_skpd
				ORDER BY id
			", $opsi['id'], $opsi['level'], $opsi['periode']), ARRAY_A);
		}
		if (!empty($pohon_kinerja)) {
			foreach ($pohon_kinerja as $level) {
				if (empty($data_ret[trim($level['label'])])) {
					$data_ret[trim($level['label'])] = [
						'id' => $level['id'],
						'label' => $level['label'],
						'level' => $level['level'],
						'indikator' => [],
						'data' => [],
						'croscutting' => []
					];
				}

				$indikator_pohon_kinerja_level = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM $table 
					WHERE parent=%d 
						AND level=%d 
						AND active=1 
						AND id_jadwal=%d
						$where_skpd
					ORDER BY id
				", $level['id'], $level['level'], $opsi['periode']), ARRAY_A);
				if (!empty($indikator_pohon_kinerja_level)) {
					foreach ($indikator_pohon_kinerja_level as $indikator_level) {
						if (!empty($indikator_level['label_indikator_kinerja'])) {
							if (empty($data_ret[trim($level['label'])]['indikator'][(trim($indikator_level['label_indikator_kinerja']))])) {
								$data_ret[trim($level['label'])]['indikator'][(trim($indikator_level['label_indikator_kinerja']))] = [
									'id' => $indikator_level['id'],
									'parent' => $indikator_level['parent'],
									'label_indikator_kinerja' => $indikator_level['label_indikator_kinerja'],
									'level' => $indikator_level['level']
								];
							}
						}
					}
				}
				// data croscutting
				$data_croscutting_level = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_croscutting_opd
					WHERE parent_pohon_kinerja=%d 
						AND active=1 
					ORDER BY id
				", $level['id']), ARRAY_A);

				$data_croscutting_level_pengusul = $wpdb->get_results($wpdb->prepare("
					SELECT 
						cc.*,
						pk.id_skpd as id_skpd_parent
					FROM esakip_croscutting_opd as cc
					JOIN esakip_pohon_kinerja_opd as pk
					ON cc.parent_pohon_kinerja = pk.id
					WHERE cc.id_skpd_croscutting=%d
						AND cc.status_croscutting=1 
						AND cc.active=1
						AND cc.parent_croscutting=%d
				", $opsi['id_skpd'], $level['id']),  ARRAY_A);

				if(!empty($data_croscutting_level) && !empty($data_croscutting_level_pengusul)){
					$data_croscutting_level = array_merge($data_croscutting_level,$data_croscutting_level_pengusul);
				}

				if (!empty($data_croscutting_level)) {
					$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
					foreach ($data_croscutting_level as $key_croscutting_level => $croscutting_level) {
						$nama_perangkat = '';
						if ($croscutting_level['is_lembaga_lainnya'] == 1) {
							$nama_lembaga = $wpdb->get_row(
								$wpdb->prepare("
									SELECT 
										nama_lembaga,
										id,
										tahun_anggaran
									FROM esakip_data_lembaga_lainnya 
									WHERE active=1 
									AND id=%d
									AND tahun_anggaran=%d
									GROUP BY id
									ORDER BY nama_lembaga ASC
								", $croscutting_level['id_skpd_croscutting'], $tahun_anggaran_sakip),
								ARRAY_A
							);
							$nama_perangkat = $nama_lembaga['nama_lembaga'];
						}else{
							if(!empty($croscutting_level['id_skpd_parent'])){
								$this_data_id_skpd = $croscutting_level['id_skpd_parent'];
							}else{
								$this_data_id_skpd = $croscutting_level['id_skpd_croscutting'];
							}

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
							$nama_perangkat = $nama_skpd['nama_skpd'];
						}

						if (!empty($croscutting_level['keterangan'])) {
							if (empty($data_ret[trim($level['label'])]['croscutting'][(trim($croscutting_level['keterangan']))])) {
								$data_ret[trim($level['label'])]['croscutting'][(trim($croscutting_level['keterangan']))] = [
									'id' => $croscutting_level['id'],
									'keterangan' => $croscutting_level['keterangan'],
									'data' => array()
								];
							}

							if(!empty($croscutting_level['id_skpd_parent'])){
								$croscutting_opd_lain = 1;
							}else{
								$croscutting_opd_lain = 0;
							}		

							$data_ret[trim($level['label'])]['croscutting'][(trim($croscutting_level['keterangan']))]['data'][$key_croscutting_level] = [
								'id' => $croscutting_level['id'],
								'parent_pohon_kinerja' => $croscutting_level['parent_pohon_kinerja'],
								'keterangan' => $croscutting_level['keterangan'],
								'nama_skpd' => $nama_perangkat,
								'croscutting_opd_lain' => $croscutting_opd_lain
							];
						}
					}
				}
				if (
					(
						$level['level'] <= 4
						&& $opsi['tipe'] == 'pemda'
					)
					|| (
						$level['level'] <= 5
						&& $opsi['tipe'] == 'opd'
					)
				) {
					$opsi['id'] = $level['id'];
					$opsi['level'] = $level['level'] + 1;
					$data_ret[trim($level['label'])]['data'] = $this->get_pokin($opsi);
				}
			}
		}
		return $data_ret;
	}

	function get_crosscutting_pemda($opsi)
	{
		global $wpdb;
		$data_ret = array();
		$table = 'esakip_croscutting';
		$where_skpd = '';
		if ($opsi['tipe'] == 'opd') {
			$table = 'esakip_croscutting_opd';
			$where_skpd = $wpdb->prepare('AND id_skpd=%d', $opsi['id_skpd']);
		}
		if ($opsi['level'] == 1) {
			$crosscutting = $wpdb->get_results($wpdb->prepare("
				SELECT 
					* 
				FROM $table 
				WHERE id=%d
				  AND parent=0 
				  AND level=%d 
				  AND active=1 
				  AND id_jadwal=%d 
				  AND id_unik_tujuan=%s
				  $where_skpd
				ORDER BY id
			", $opsi['id'], $opsi['level'], $opsi['periode'], $opsi['id_tujuan']), ARRAY_A);
		} else {
			$crosscutting = $wpdb->get_results($wpdb->prepare("
				SELECT 
					* 
				FROM $table 
				WHERE parent=%d
				  AND level=%d 
				  AND active=1 
				  AND id_jadwal=%d
				  AND id_unik_tujuan=%s
				  $where_skpd
				ORDER BY id
			", $opsi['id'], $opsi['level'], $opsi['periode'], $opsi['id_tujuan']), ARRAY_A);
		}
		if (!empty($crosscutting)) {
			foreach ($crosscutting as $level) {
				if (empty($data_ret[trim($level['label'])])) {
					$data_ret[trim($level['label'])] = [
						'id' => $level['id'],
						'label' => $level['label'],
						'level' => $level['level'],
						'indikator' => [],
						'data' => [],
					];
				}

				$indikator_crosscutting_level = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM $table 
					WHERE parent=%d 
					  AND level=%d 
					  AND active=1 
					  AND id_jadwal=%d
					  AND id_unik_tujuan=%s
					  $where_skpd
					ORDER BY id
				", $level['id'], $level['level'], $opsi['periode'], $opsi['id_tujuan']), ARRAY_A);
				if (!empty($indikator_crosscutting_level)) {
					foreach ($indikator_crosscutting_level as $indikator_level) {
						if (!empty($indikator_level['label_id_skpd'])) {
							if (empty($data_ret[trim($level['label'])]['indikator'][(trim($indikator_level['label_id_skpd']))])) {
								$data_ret[trim($level['label'])]['indikator'][(trim($indikator_level['label_id_skpd']))] = [
									'id' => $indikator_level['id'],
									'parent' => $indikator_level['parent'],
									'label_id_skpd' => $indikator_level['label_id_skpd'],
									'label_nama_skpd' => $indikator_level['label'],
									'level' => $indikator_level['level']
								];
							}
						}
					}
				}
				if (
					(
						$level['level'] <= 4
						&& $opsi['tipe'] == 'pemda'
					)
					|| (
						$level['level'] <= 5
						&& $opsi['tipe'] == 'opd'
					)
				) {
					$opsi['id'] = $level['id'];
					$opsi['level'] = $level['level'] + 1;
					$data_ret[trim($level['label'])]['data'] = $this->get_crosscutting_pemda($opsi);
				}
			}
		}
		return $data_ret;
	}

	public function get_data_crosscutting_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = '';
					if (!empty($_POST['tipe_crosscutting'])) {
						if (!empty($_POST['id_skpd'])) {
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_crosscutting'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_crosscutting'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
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
								FROM esakip_croscutting' . $_prefix_opd . ' 
								WHERE id=a.id ' . $_where_opd . '
							) label_parent_1';
							break;

						case '3':
							$label_parent = '
							(
								SELECT 
									label 
								FROM esakip_croscutting' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_croscutting' . $_prefix_opd . ' 
									WHERE id=a.id ' . $_where_opd . '
								) ' . $_where_opd . '
							) label_parent_1,
							(
								SELECT 
									label 
								FROM esakip_croscutting' . $_prefix_opd . ' 
								WHERE id=a.id ' . $_where_opd . '
							) label_parent_2';
							break;

						case '4':
							$label_parent = '
							(
								SELECT 
									label 
								FROM esakip_croscutting' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_croscutting' . $_prefix_opd . ' 
									WHERE id=(
										SELECT 
											parent 
										FROM esakip_croscutting' . $_prefix_opd . ' 
										WHERE id=a.id ' . $_where_opd . '
									) ' . $_where_opd . '
								) ' . $_where_opd . '
							) label_parent_1,
							(
								SELECT 
									label 
								FROM esakip_croscutting' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_croscutting' . $_prefix_opd . ' 
									WHERE id=a.id ' . $_where_opd . '
								) ' . $_where_opd . '
							) label_parent_2,
							(
								SELECT 
									label 
								FROM esakip_croscutting' . $_prefix_opd . ' 
								WHERE id=a.id ' . $_where_opd . '
							) label_parent_3';
							break;

						case '5':
							$label_parent = '
							(
								SELECT 
									label 
								FROM esakip_croscutting' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_croscutting' . $_prefix_opd . ' 
									WHERE id=(
										SELECT 
											parent 
										FROM esakip_croscutting' . $_prefix_opd . ' 
										WHERE id=(
											SELECT 
												parent 
											FROM esakip_croscutting' . $_prefix_opd . ' 
											WHERE id=a.id ' . $_where_opd . '
										) ' . $_where_opd . '
									) ' . $_where_opd . '
								) ' . $_where_opd . '
							) label_parent_1,
							(
								SELECT 
									label 
								FROM esakip_croscutting' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_croscutting' . $_prefix_opd . ' 
									WHERE id=(
										SELECT 
											parent 
										FROM esakip_croscutting' . $_prefix_opd . ' 
										WHERE id=a.id ' . $_where_opd . '
									) ' . $_where_opd . '
								) ' . $_where_opd . '
							) label_parent_2,
							(
								SELECT 
									label 
								FROM esakip_croscutting' . $_prefix_opd . ' 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_croscutting' . $_prefix_opd . ' 
									WHERE id=a.id ' . $_where_opd . '
								) ' . $_where_opd . '
							) label_parent_3,
							(
								SELECT 
									label 
								FROM esakip_croscutting' . $_prefix_opd . ' 
								WHERE id=a.id ' . $_where_opd . '
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
								b.label_id_skpd,
								b.label as nama_skpd
							FROM esakip_croscutting a
								LEFT JOIN esakip_croscutting b 
									ON a.id=b.parent AND a.level=b.level 
							WHERE 
								a.id_jadwal=%d AND 
								a.id_unik_tujuan=%s AND 
								a.parent=%d AND 
								a.level=%d AND 
								a.active=%d 
							ORDER BY a.id",
							$_POST['id_jadwal'],
							$_POST['id_tujuan'],
							$_POST['parent'],
							$_POST['level'],
							1
						), ARRAY_A);
					} else if ($_prefix_opd == '_opd') {
						$dataPokin = $wpdb->get_results($wpdb->prepare(
							"
							SELECT 
								a.id,
								a.label,
								a.parent,
								a.active,
								b.id AS id_indikator,
								b.label_id_skpd
							FROM esakip_croscutting_opd a
								LEFT JOIN esakip_croscutting_opd b 
									ON a.id=b.parent AND a.level=b.level 
							WHERE 
								a.id_jadwal=%d AND 
								a.id_unik_tujuan=%s AND 
								a.parent=%d AND 
								a.level=%d AND 
								a.active=%d AND 
								a.id_skpd=%d
							ORDER BY a.id",
							$_POST['id_jadwal'],
							$_POST['id_tujuan'],
							$_POST['parent'],
							$_POST['level'],
							1,
							$id_skpd
						), ARRAY_A);
					}

					$dataParent = array();
					if ($_prefix_opd == '') {
						if (!empty($label_parent)) {
							$dataParent = $wpdb->get_results($wpdb->prepare(
								"
									SELECT 
										" . $label_parent . "
									FROM esakip_croscutting a 
									WHERE 
										a.id_jadwal=%d AND 
										a.id_unik_tujuan=%s AND 
										a.id=%d AND
										a.active=%d
									ORDER BY a.id",
								$_POST['id_jadwal'],
								$_POST['id_tujuan'],
								$_POST['parent'],
								1
							), ARRAY_A);
						}
					} else if ($_prefix_opd == '_opd') {
						if (!empty($label_parent)) {
							$dataParent = $wpdb->get_results($wpdb->prepare(
								"
									SELECT 
										" . $label_parent . "
									FROM esakip_croscutting_opd a 
									WHERE 
										a.id_jadwal=%d AND 
										a.id_unik_tujuan=%s AND 
										a.id=%d AND
										a.active=%d AND 
										a.id_skpd=%d
									ORDER BY a.id",
								$_POST['id_jadwal'],
								$_POST['id_tujuan'],
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
					foreach ($dataPokin as $key => $crosscutting) {
						if (empty($data['data'][$crosscutting['id']])) {
							$data['data'][$crosscutting['id']] = [
								'id' => $crosscutting['id'],
								'label' => $crosscutting['label'],
								'parent' => $crosscutting['parent'],
								'label_parent_1' => $crosscutting['label_parent_1'],
								'indikator' => []
							];
						}

						if (!empty($crosscutting['id_indikator'])) {
							if (empty($data['data'][$crosscutting['id']]['indikator'][$crosscutting['id_indikator']])) {
								$data['data'][$crosscutting['id']]['indikator'][$crosscutting['id_indikator']] = [
									'id' => $crosscutting['id_indikator'],
									'label' => $crosscutting['label_id_skpd'],
									'label_nama' => $crosscutting['nama_skpd']
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
	public function create_crosscutting_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = '';
					$input = json_decode(stripslashes($_POST['data']), true);

					if (!empty($_POST['tipe_crosscutting'])) {
						if (!empty($_POST['id_skpd'])) {
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_crosscutting'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_crosscutting'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_croscutting$_prefix_opd 
						WHERE label=%s 
							AND parent=%d 
							AND level=%d 
							AND active=%d $_where_opd 
						ORDER BY id
					", trim($input['label']), $input['parent'], $input['level'], 1),  ARRAY_A);

					if (!empty($id)) {
						throw new Exception("Data sudah ada!", 1);
					}

					if ($_prefix_opd == "") {
						// untuk crosscutting pepmda //////////////////////////////////////////////////////////
						$data = $wpdb->insert('esakip_croscutting', [
							'label' => trim($input['label']),
							'parent' => $input['parent'],
							'level' => $input['level'],
							'id_jadwal' => $input['id_jadwal'],
							'id_unik_tujuan' => $input['id_tujuan'],
							'active' => 1
						]);
					} else {
						// untuk crosscutting opd //////////////////////////////////////////////////////////
						$data = $wpdb->insert('esakip_croscutting' . $_prefix_opd, [
							'label' => trim($input['label']),
							'parent' => $input['parent'],
							'level' => $input['level'],
							'id_jadwal' => $input['id_jadwal'],
							'id_unik_tujuan' => $input['id_tujuan'],
							'active' => 1,
							'id_skpd' => $id_skpd
						]);
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses simpan crosscutting!'
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

	public function edit_crosscutting_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = '';
					if (!empty($_POST['tipe_crosscutting'])) {
						if (!empty($_POST['id_skpd'])) {
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_crosscutting'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_crosscutting'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
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
						FROM esakip_croscutting$_prefix_opd 
						WHERE id=%d 
							AND active=%d$_where_opd
					", $_POST['id'], 1),  ARRAY_A);

					$data_croscutting = array();
					if (!empty($_prefix_opd) && $_prefix_opd == "_opd" && !empty($data)) {
						$data_croscutting = $wpdb->get_results($wpdb->prepare("
								SELECT 
									*
								FROM esakip_croscutting_opd
								WHERE parent_croscutting=%d 
									AND active=%d
							", $_POST['id'], 1),  ARRAY_A);
					}

					$table_croscutting = '';
					$no = 1;
					if (!empty($data_croscutting)) {
						$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
						foreach ($data_croscutting as $k_cross => $v_cross) {

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
								", $v_cross['id_skpd_croscutting'], $tahun_anggaran_sakip),
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

							$table_croscutting .= '<td>' . $no++ . '</td>';
							$table_croscutting .= '<td>' . $v_cross['keterangan'] . '</td>';
							$table_croscutting .= '<td>' . $v_cross['keterangan_croscutting'] . '</td>';
							$table_croscutting .= '<td>' . $nama_skpd['nama_skpd'] . '</td>';
							$table_croscutting .= '<td>' . $status_croscutting . '</td>';

							$aksi = '';
							$aksi .= '<a href="javascript:void(0)" class="btn btn-sm btn-warning edit-croscutting" data-id="' . $v_cross['id'] . '" href="#" title="Edit Croscutting"><span class="dashicons dashicons-edit"></span></a>';
							$aksi .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger delete-croscutting" data-id="' . $v_cross['id'] . '" style="margin-left: 5px;" href="#" title="Hapus Croscutting"><span class="dashicons dashicons-trash"></span></a>';

							$table_croscutting .= '<td>' . $aksi . '</td>';

							$table_croscutting .= '</tr>';
						}
					}

					if (empty($data)) {
						throw new Exception("Data tidak ditemukan!", 1);
					}

					echo json_encode([
						'status' => true,
						'data' => $data,
						'data_croscutting' => $table_croscutting
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

	public function update_crosscutting_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					$input = json_decode(stripslashes($_POST['data']), true);

					$_prefix_opd = $_where_opd = $id_skpd = '';
					if (!empty($_POST['tipe_crosscutting'])) {
						if (!empty($_POST['id_skpd'])) {
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_crosscutting'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_crosscutting'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}


					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_croscutting$_prefix_opd 
						WHERE label=%s 
						  AND id!=%d 
						  AND parent=%d 
						  AND level=%d 
						  AND active=%d$_where_opd
						", trim($input['label']), $input['id'], $input['parent'], $input['level'], 1),  ARRAY_A);

					if (!empty($id)) {
						throw new Exception("Data sudah ada!", 1);
					}

					if ($_prefix_opd == '') {
						// untuk crosscutting pemda //////////////////////////////////////////////////////////////////////////////
						$data = $wpdb->update('esakip_croscutting', [
							'label' => trim($input['label'])
						], [
							'id' => $input['id']
						]);

						// $child = $wpdb->query($wpdb->prepare("
						// 	UPDATE esakip_croscutting 
						// 	SET label=%s 
						// 	WHERE parent=%d 
						// 	  AND label_id_skpd IS NOT NULL
						// ", trim($input['label']), $input['id']));
					} else {
						// untuk crosscutting opd  //////////////////////////////////////////////////////////////////////////////
						$data = $wpdb->update('esakip_croscutting' . $_prefix_opd, [
							'label' => trim($input['label'])
						], [
							'id' => $input['id'],
							'id_skpd' => $id_skpd
						]);

						// $child = $wpdb->query($wpdb->prepare("
						// 	UPDATE esakip_croscutting$_prefix_opd 
						// 	SET label=%s 
						// 	WHERE parent=%d 
						// 		AND label_id_skpd IS NOT NULL
						// 		AND id_skpd=%d
						// ", trim($input['label']), $input['id'], $id_skpd));
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses ubah crosscutting!'
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

	public function delete_crosscutting_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = '';
					if (!empty($_POST['tipe_crosscutting'])) {
						if (!empty($_POST['id_skpd'])) {
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_crosscutting'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_crosscutting'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					$indikator = $wpdb->get_row($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_croscutting$_prefix_opd 
						WHERE parent=%d 
							AND label_id_skpd IS NOT NULL 
							AND level=%d 
							AND active=%d$_where_opd
					", $_POST['id'], $_POST['level'], 1),  ARRAY_A);

					if (!empty($indikator)) {
						throw new Exception("Indikator harus dihapus dulu!", 1);
					}

					$child = $wpdb->get_row($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_croscutting$_prefix_opd 
						WHERE parent=%d 
							AND level=%d 
							AND active=%d$_where_opd
					", $_POST['id'], (intval($_POST['level']) + 1), 1),  ARRAY_A);

					if (!empty($child)) {
						throw new Exception("Crosscutting level " . (intval($_POST['level']) + 1) . " harus dihapus dulu!", 1);
					}

					if ($_prefix_opd == '') {
						// untuk pemda
						$data = $wpdb->delete('esakip_croscutting', [
							'id' => $_POST['id']
						]);
					} else {
						// untuk opd
						$data = $wpdb->delete('esakip_croscutting' . $_prefix_opd, [
							'id' => $_POST['id'],
							'id_skpd' => $id_skpd
						]);
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses hapus crosscutting!'
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

	public function create_indikator_crosscutting_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = '';
					if (!empty($_POST['tipe_crosscutting'])) {
						if (!empty($_POST['id_skpd'])) {
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_crosscutting'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_crosscutting'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					$input = json_decode(stripslashes($_POST['data']), true);
					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_croscutting$_prefix_opd 
						WHERE label_id_skpd=%d 
						  AND parent=%d 
						  AND level=%d 
						  AND active=%d$_where_opd
					", trim($input['skpd-label']), $input['parent'], $input['level'], 1),  ARRAY_A);

					if (!empty($id)) {
						throw new Exception("Data sudah ada!", 1);
					}
					$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
					if ($input['skpd-label'] != 0) {
						$nama_skpd = $wpdb->get_var($wpdb->prepare("
							SELECT
								nama_skpd
							FROM esakip_data_unit
							WHERE active = 1
							  AND id_skpd = %d
							  AND tahun_anggaran = %d
						", $input['skpd-label'], $tahun_anggaran_sakip));
					} else {
						$nama_skpd =  "Seluruh Perangkat Daerah";
					}

					if ($_prefix_opd == '') {
						$data = $wpdb->insert('esakip_croscutting', [
							'label_id_skpd' => trim($input['skpd-label']),
							'label' => $nama_skpd,
							'parent' => $input['parent'],
							'level' => $input['level'],
							'id_jadwal' => $input['id_jadwal'],
							'id_unik_tujuan' => $input['id_tujuan'],
							'active' => 1
						]);
					} else {
						$data = $wpdb->insert('esakip_croscutting' . $_prefix_opd, [
							'label_id_skpd' => trim($input['skpd-label']),
							'label' => $nama_skpd,
							'parent' => $input['parent'],
							'level' => $input['level'],
							'id_jadwal' => $input['id_jadwal'],
							'id_unik_tujuan' => $input['id_tujuan'],
							'active' => 1,
							'id_skpd' => $id_skpd
						]);
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses simpan Perangkat Daerah!'
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

	public function edit_indikator_crosscutting_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = '';
					if (!empty($_POST['tipe_crosscutting'])) {
						if (!empty($_POST['id_skpd'])) {
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_crosscutting'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_crosscutting'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
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
							a.label_id_skpd, 
							a.level,
							b.parent AS parent_all 
						FROM 
							esakip_croscutting$_prefix_opd a
						LEFT JOIN esakip_croscutting$_prefix_opd b ON b.id=a.parent 
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

	public function update_indikator_crosscutting_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = '';
					if (!empty($_POST['tipe_crosscutting'])) {
						if (!empty($_POST['id_skpd'])) {
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_crosscutting'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_crosscutting'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					$input = json_decode(stripslashes($_POST['data']), true);

					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_croscutting$_prefix_opd 
						WHERE id!=%d 
							AND parent=%d 
							AND level=%d 
							AND active=%d 
							AND label_id_skpd=%d$_where_opd
					", $input['id'], $input['parent'], $input['level'], 1, trim($input['skpd-label'])),  ARRAY_A);

					if (!empty($id)) {
						throw new Exception("Data sudah ada!", 1);
					}
					$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

					$nama_skpd = $wpdb->get_var($wpdb->prepare("
						SELECT
							nama_skpd
						FROM esakip_data_unit
						WHERE active = 1
						  AND is_skpd = 1
						  AND id_skpd = %d
						  AND tahun_anggaran = %d
					", $input['skpd-label'], $tahun_anggaran_sakip));

					if ($_prefix_opd == '') {
						// untuk pemda
						$data = $wpdb->update('esakip_croscutting', [
							'label' => $nama_skpd,
							'label_id_skpd' => trim($input['skpd-label']),
						], [
							'id' => $input['id'],
							'parent' => $input['parent'],
							'level' => $input['level'],
						]);
					} else {
						$data = $wpdb->update('esakip_croscutting' . $_prefix_opd, [
							'label' => $nama_skpd,
							'label_id_skpd' => trim($input['skpd-label']),
						], [
							'id' => $input['id'],
							'parent' => $input['parent'],
							'level' => $input['level'],
							'id_skpd' => $id_skpd
						]);
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses ubah Perangkat Daerah!'
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

	public function delete_indikator_crosscutting_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_opd = $_where_opd = $id_skpd = '';
					if (!empty($_POST['tipe_crosscutting'])) {
						if (!empty($_POST['id_skpd'])) {
							$id_skpd = $_POST['id_skpd'];
							$_prefix_opd = $_POST['tipe_crosscutting'] == "opd" ? "_opd" : "";
							$_where_opd = $_POST['tipe_crosscutting'] == "opd" ? ' AND id_skpd=' . $id_skpd : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					if ($_prefix_opd == '') {
						// untuk pemda
						$data = $wpdb->delete('esakip_croscutting', [
							'id' => $_POST['id']
						]);
					} else {
						// untuk opd
						$data = $wpdb->delete('esakip_croscutting' . $_prefix_opd, [
							'id' => $_POST['id'],
							'id_skpd' => $id_skpd
						]);
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses hapus Perangkat Daerah!'
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
