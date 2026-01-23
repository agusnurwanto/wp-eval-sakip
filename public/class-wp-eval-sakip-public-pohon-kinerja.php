<?php

use GuzzleHttp\Psr7\Query;
use WpOrg\Requests\Response;

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

	public function new_view_pohon_kinerja($atts)
	{
		if (!empty($_GET) && !empty($_GET['post'])) {
			return '';
		}

		require_once ESAKIP_PLUGIN_PATH . 'public/partials/pohon-kinerja/wp-eval-sakip-new-view-pohon-kinerja.php';
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

	public function cascading_pd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-input-cascading-opd.php';
	}

	public function list_pengisian_rencana_aksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-pengisian-rencana-aksi.php';
	}

	public function list_input_iku($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-input-iku.php';
	}

	public function detail_input_iku($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-input-iku-per-skpd.php';
	}

	public function detail_input_cascading_pd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/pohon-kinerja/wp-eval-sakip-cascading-opd.php';
	}

	public function input_iku_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-input-iku-pemda.php';
	}

	public function halaman_lembaga_lainnya($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/pohon-kinerja/wp-eval-sakip-lembaga-lainnya.php';
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
								a.*,
								b.id AS id_indikator,
								b.label_indikator_kinerja,
								b.nomor_urut as nomor_urut_indikator
							FROM esakip_pohon_kinerja a
							LEFT JOIN esakip_pohon_kinerja b ON a.id=b.parent AND a.level=b.level 
							WHERE 
								a.id_jadwal=%d AND 
								a.parent=%d AND 
								a.level=%d AND 
								a.active=%d 
							ORDER BY a.nomor_urut ASC, b.nomor_urut ASC",
							$_POST['id_jadwal'],
							$_POST['parent'],
							$_POST['level'],
							1
						), ARRAY_A);
					} else if ($_prefix_opd == '_opd') {
						if (is_array($_POST['parent'])) {
							$in_parent = implode(", ", $_POST['parent']);
							// $_where_parent = $wpdb->prepare(' AND a.parent IN (%s) ', $in_parent);
							$_where_parent = ' AND a.parent IN (' . $in_parent . ') ';
						} else {
							$_where_parent = $wpdb->prepare(' AND a.parent=%d ', $_POST['parent']);
						}

						$dataPokin = $wpdb->get_results($wpdb->prepare(
							"
							SELECT 
								a.*,
								b.id AS id_indikator,
								b.label_indikator_kinerja,
								b.nomor_urut as nomor_urut_indikator
							FROM esakip_pohon_kinerja_opd a
							LEFT JOIN esakip_pohon_kinerja_opd b ON a.id=b.parent AND a.level=b.level 
							WHERE 
								a.id_jadwal=%d AND
								a.level=%d AND 
								a.active=%d AND 
								a.id_skpd=%d
								$_where_parent
							ORDER BY a.nomor_urut ASC, b.nomor_urut ASC",
							$_POST['id_jadwal'],
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
									ORDER BY a.nomor_urut ASC",
								$_POST['id_jadwal'],
								$_POST['parent'],
								1
							), ARRAY_A);
						}
					} else if ($_prefix_opd == '_opd') {
						if (!empty($label_parent)) {
							if (is_array($_POST['parent'])) {
								$in_parent = implode(", ", $_POST['parent']);
								// $_where_parent = $wpdb->prepare(' AND a.parent IN (%s) ', $in_parent);
								$_where_parent = ' AND a.id IN (' . $in_parent . ') ';
							} else {
								$_where_parent = $wpdb->prepare(' AND a.id=%d ', $_POST['parent']);
							}

							$dataParent = $wpdb->get_results($wpdb->prepare(
								"
									SELECT 
										" . $label_parent . "
									FROM esakip_pohon_kinerja_opd a 
									WHERE 
										a.id_jadwal=%d AND 
										a.active=%d AND 
										a.id_skpd=%d
										$_where_parent
									ORDER BY a.nomor_urut ASC",
								$_POST['id_jadwal'],
								1,
								$id_skpd
							), ARRAY_A);
						}
					}

					$nama_cross = $this->get_nama_crosscuttin();
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
								'nomor_urut' => $pokin['nomor_urut'],
								'pelaksana' => $pokin['pelaksana'] ? $pokin['pelaksana'] : '',
								'bentuk_kegiatan' => $pokin['bentuk_kegiatan'] ? $pokin['bentuk_kegiatan'] : '',
								'outcome' => $pokin['outcome'] ? $pokin['outcome'] : '',
								'indikator' => [],
								'crosscutting' => array()
							];

							$crosscutting = $wpdb->get_results($wpdb->prepare("
								SELECT
									tipe,
									nama_desa,
									id_skpd_koneksi
								FROM esakip_koneksi_pokin_pemda_opd
								WHERE parent_pohon_kinerja=%d
								  AND active = 1
							", $pokin['id']), ARRAY_A);
							if (!empty($crosscutting)) {
								foreach ($crosscutting as $v) {
									// skpd dan uptd
									if ($v['tipe'] == 1 || $v['tipe'] == 3) {
										if (!empty($nama_cross['skpd'][$v['id_skpd_koneksi']])) {
											$data['data'][$pokin['id']]['crosscutting'][] = $nama_cross['skpd'][$v['id_skpd_koneksi']]['nama_skpd'];
										} else {
											$data['data'][$pokin['id']]['crosscutting'][] = 'ID SKPD ' . $v['id_skpd_koneksi'] . ' tidak ditemukan';
										}
										// lembaga lainnya
									} else if ($v['tipe'] == 2) {
										if (!empty($nama_cross['lembaga'][$v['id_skpd_koneksi']])) {
											$data['data'][$pokin['id']]['crosscutting'][] = $nama_cross['lembaga'][$v['id_skpd_koneksi']]['nama_lembaga'];
										} else {
											$data['data'][$pokin['id']]['crosscutting'][] = 'ID Lembaga ' . $v['id_skpd_koneksi'] . ' tidak ditemukan';
										}
										// desa
									} else if ($v['tipe'] == 4) {
										$data['data'][$pokin['id']]['crosscutting'][] = $v['nama_desa'];
									} else {
										$data['data'][$pokin['id']]['crosscutting'][] = 'Tipe Koneksi tidak diketahui';
									}
								}
							}
						}

						if (!empty($pokin['id_indikator'])) {
							if (empty($data['data'][$pokin['id']]['indikator'][$pokin['id_indikator'] . ' ' . $pokin['nomor_urut_indikator']])) {
								$data['data'][$pokin['id']]['indikator'][$pokin['id_indikator'] . ' ' . $pokin['nomor_urut_indikator']] = [
									'id' => $pokin['id_indikator'],
									'label' => $pokin['label_indikator_kinerja'],
									'nomor_urut' => $pokin['nomor_urut_indikator']
								];
							}
						}
					}

					if (!empty($dataParent)) {
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

	public function handle_view_pokin()
	{
		try {
			$this->functions->validate($_POST, [
				'id' 		=> 'required'
			]);

			$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);
			$tahun_anggaran = get_option(ESAKIP_TAHUN_ANGGARAN);

			$data_pokin = $this->get_pokin_child_by_id($_POST['id']);
			$data_jadwal = $this->get_data_jadwal_by_id($data_pokin['data_child']['data']['id_jadwal']);
			$data_unit = $this->get_data_unit_by_id_skpd_tahun_anggaran($data_pokin['data_child']['data']['id_skpd'], $tahun_anggaran);

			$data_koneksi = null;
			$data_unit_koneksi = null;
			if (!empty($_POST['tipe_koneksi'])) {
				if ($_POST['tipe_koneksi'] == 'opd') {
					$tipe = 'opd';
				} else {
					$tipe = 'pemda';
				}
				$data_koneksi = $this->get_parent_pokin_koneksi_by_id($_POST['id_koneksi_pokin'], $_POST['id'], $tipe);

				if ($tipe == 'opd') {
					$data_unit_koneksi = $this->get_data_unit_by_id_skpd_tahun_anggaran($data_koneksi['data']['id_skpd'], $tahun_anggaran);
				}
			}

			if (!empty($data_pokin['data_level_1'])) {
				$full_pokin_view_page = $this->functions->generatePage(array(
					'nama_page' => 'View Pohon Kinerja OPD ' . $data_unit['nama_skpd'],
					'content' => '[view_pohon_kinerja_opd periode='. $data_jadwal['id'] .']',
					'show_header' => 1,
					'post_status' => 'private'
				));
				
				$url = $full_pokin_view_page['url'] . '&id=' . $data_pokin['data_level_1']['id'] . '&id_jadwal=' . $data_pokin['data_level_1']['id_jadwal'] . '&id_skpd=' . $data_pokin['data_level_1']['id_skpd'];
			} else {
				$url = null;
			}

			echo json_encode([
				'status'  	=> true,
				'message' 	=> 'Data berhasil ditemukan.',
				'data'    	=> $data_pokin['data_child'],
				'info'		=> [
					'data_unit' 		=> $data_unit,
					'url_level_1' 		=> $url,
					'data_unit_koneksi' => $data_unit_koneksi,
					'data_jadwal' 		=> $data_jadwal,
					'nama_pemda' 		=> $nama_pemda,
					'data_koneksi' 		=> $data_koneksi
				],
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

	public function get_data_level_1_pokin(int $id, string $tipe)
	{
		$get_pokin_by_id = ($tipe == 'opd')
			? [$this, 'get_pokin_opd_by_id']
			: [$this, 'get_pokin_pemda_by_id'];

		$current_node = call_user_func($get_pokin_by_id, $id);

		if (empty($current_node)) {
			return null;
		}

		// Terus daki selama level BUKAN 1 DAN masih punya parent
		while ($current_node['level'] != 1 && !empty($current_node['parent'])) {

			$parent_node = call_user_func($get_pokin_by_id, $current_node['parent']);

			if (empty($parent_node)) {
				break;
			}

			$current_node = $parent_node;
		}

		if ($current_node['level'] == 1) {
			return $current_node;
		}

		return null;
	}

	public function get_parent_pokin_koneksi_by_id(int $id_koneksi_pokin, int $id_pokin_opd, string $tipe)
	{
		$current_koneksi = $this->get_koneksi_pokin_by_id_and_tipe($id_koneksi_pokin, $tipe);

		$is_empty = empty($current_koneksi);
		if ($is_empty) {
			return null;
		}

		if ($tipe == 'pemda') {
			$is_not_its_parent = $id_pokin_opd != $current_koneksi['parent_pohon_kinerja_koneksi'];
			if ($is_not_its_parent) {
				return null;
			}

			$current_pokin_data = $this->get_pokin_pemda_by_id($current_koneksi['parent_pohon_kinerja']);
		} else {
			$is_pengusul = $id_pokin_opd == $current_koneksi['parent_pohon_kinerja'];
			$is_dituju = $id_pokin_opd == $current_koneksi['parent_croscutting'];
			if (!($is_pengusul || $is_dituju)) {
				return null;
			}

			// jika pengusul, ambil parent tujuan nya
			// jika dituju, ambil parent pengusulnya
			$parent_pokin_id = $is_pengusul ? $current_koneksi['parent_croscutting'] : $current_koneksi['parent_pohon_kinerja'];

			$current_pokin_data = $this->get_pokin_opd_by_id($parent_pokin_id);
		}

		if (empty($current_pokin_data)) {
			throw new Exception("Parent pokin dengan ID $id_pokin_opd tidak ditemukan!", 400);
		}

		return $this->process_get_parent_pokin_recursive($current_pokin_data, $tipe);
	}

	private function process_get_parent_pokin_recursive(array $current_pokin_data, string $tipe)
	{
		$parent_id = $current_pokin_data['parent'];

		if ($tipe == 'opd') {
			$get_parent = $this->get_pokin_opd_by_id($parent_id);
		} else {
			$get_parent = $this->get_pokin_pemda_by_id($parent_id);
		}

		$grand_parent = [];
		if (!empty($get_parent)) {
			$grand_parent = $this->process_get_parent_pokin_recursive($get_parent, $tipe);
		}

		return [
			'data'      => $current_pokin_data,
			'parent'    => $grand_parent
		];
	}

	public function get_data_unit_by_id_skpd_tahun_anggaran(int $id_skpd, int $tahun_anggaran)
	{
		global $wpdb;

		$data = $wpdb->get_row(
			$wpdb->prepare("
				SELECT *
				FROM esakip_data_unit
				WHERE id_skpd = %d
				  AND tahun_anggaran = %d
			 	  AND active = 1
			", $id_skpd, $tahun_anggaran),
			ARRAY_A
		);

		return $data;
	}

	public function get_data_jadwal_by_id(int $id)
	{
		global $wpdb;

		$data = $wpdb->get_row(
			$wpdb->prepare("
				SELECT *
				FROM esakip_data_jadwal
				WHERE id = %d
			", $id),
			ARRAY_A
		);

		return $data;
	}

	public function get_pokin_child_by_id(int $id)
	{
		$current_pokin = $this->get_pokin_opd_by_id($id);

		if (empty($current_pokin)) {
			throw new Exception("Parent pokin dengan ID $id tidak ditemukan!", 400);
		}

		if ($current_pokin['level'] == 1) {
			$data_level_1 = $current_pokin;
		} else {
			$data_level_1 = $this->get_data_level_1_pokin($id, 'opd');
			if (empty($data_level_1)) {
				throw new Exception("Level 1 pokin untuk ID $id tidak ditemukan!", 400);
			}
		}

		$data = $this->build_subtree_recursive($current_pokin);

		return [
			'data_level_1' => $data_level_1,
			'data_child'   => $data
		];
	}

	private function build_subtree_recursive(array $current_pokin_data)
	{
		$node_id = $current_pokin_data['id'];

		$indicators = $this->get_pokin_indikator_by_parent_id($node_id);

		$child_nodes_data = $this->get_pokin_opd_by_parent_id($node_id);

		$child_subtree = [];
		if (!empty($child_nodes_data)) {
			foreach ($child_nodes_data as $child_data) {
				$child_subtree[] = $this->build_subtree_recursive($child_data);
			}
		}

		return [
			'data'      => $current_pokin_data,
			'indikator' => $indicators,
			'child'     => $child_subtree
		];
	}

	public function get_lembaga_lain_by_id(int $id)
	{
		global $wpdb;

		$data = $wpdb->get_results(
			$wpdb->prepare("
                SELECT *
                FROM esakip_data_lembaga_lainnya
                WHERE id = %d
                  AND active = 1
            ", $id),
			ARRAY_A
		);

		return $data;
	}

	public function get_pokin_opd_by_parent_id(int $id)
	{
		global $wpdb;

		$data = $wpdb->get_results(
			$wpdb->prepare("
                SELECT *
                FROM esakip_pohon_kinerja_opd
                WHERE parent = %d
                  AND active = 1
                  AND (label_indikator_kinerja IS NULL OR label_indikator_kinerja = '')
            ", $id),
			ARRAY_A
		);

		return $data;
	}

	public function get_pokin_pemda_by_id(int $id)
	{
		global $wpdb;

		$data = $wpdb->get_row(
			$wpdb->prepare("
                SELECT *
                FROM esakip_pohon_kinerja
                WHERE id = %d
                  AND active = 1
            ", $id),
			ARRAY_A
		);

		return $data;
	}

	public function get_pokin_opd_by_id(int $id)
	{
		global $wpdb;

		$data = $wpdb->get_row(
			$wpdb->prepare("
                SELECT *
                FROM esakip_pohon_kinerja_opd
                WHERE id = %d
                  AND active = 1
            ", $id),
			ARRAY_A
		);

		return $data;
	}

	public function get_koneksi_pokin_by_id_and_tipe(int $id, string $tipe)
	{
		global $wpdb;

		if ($tipe == 'opd') {
			$table_name = 'esakip_croscutting_opd';
		} else {
			$table_name = 'esakip_koneksi_pokin_pemda_opd';
		}

		$data = $wpdb->get_row(
			$wpdb->prepare("
                SELECT *
                FROM $table_name
                WHERE id = %d
                  AND active = 1
            ", $id),
			ARRAY_A
		);

		return $data;
	}

	public function get_pokin_indikator_by_parent_id(int $id)
	{
		global $wpdb;

		$data = $wpdb->get_results(
			$wpdb->prepare("
                SELECT *
                FROM esakip_pohon_kinerja_opd
                WHERE parent = %d
                  AND active = 1
                  AND label_indikator_kinerja IS NOT NULL 
                  AND label_indikator_kinerja != ''
            ", $id),
			ARRAY_A
		);

		return $data;
	}

	public function get_data_pokin_all()
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
						$dataPokin = $wpdb->get_results($wpdb->prepare(
							"
							SELECT 
								a.*,
								b.id AS id_indikator,
								b.label_indikator_kinerja,
								b.nomor_urut as nomor_urut_indikator
							FROM esakip_pohon_kinerja a
							LEFT JOIN esakip_pohon_kinerja b ON a.id=b.parent 
								AND a.level=b.level 
								AND a.active=b.active 
								AND a.id_jadwal=b.id_jadwal 
								AND a.id_skpd=b.id_skpd 
							WHERE 
								a.id_jadwal=%d AND 
								a.active=%d AND
								a.label_indikator_kinerja is NULL
							ORDER BY a.level ASC, a.nomor_urut ASC, b.nomor_urut ASC",
							$_POST['id_jadwal'],
							1
						), ARRAY_A);
					} else if ($_prefix_opd == '_opd') {
						$dataPokin = $wpdb->get_results($wpdb->prepare(
							"
							SELECT 
								a.*,
								b.id AS id_indikator,
								b.label_indikator_kinerja,
								b.nomor_urut as nomor_urut_indikator
							FROM esakip_pohon_kinerja_opd a
							LEFT JOIN esakip_pohon_kinerja_opd b ON a.id=b.parent 
								AND a.level=b.level 
								AND a.active=b.active 
								AND a.id_jadwal=b.id_jadwal 
								AND a.id_skpd=b.id_skpd 
							WHERE 
								a.id_jadwal=%d AND
								a.active=%d AND 
								a.id_skpd=%d AND
								a.label_indikator_kinerja is NULL
							ORDER BY a.level ASC, a.nomor_urut ASC, b.nomor_urut ASC",
							$_POST['id_jadwal'],
							1,
							$id_skpd
						), ARRAY_A);
					}
					$data = [
						'data' => [],
						'parent' => []
					];
					foreach ($dataPokin as $key => $pokin) {
						if (empty($data['data'][$pokin['id']])) {
							$data['data'][$pokin['id']] = [
								'id' => $pokin['id'],
								'level' => $pokin['level'],
								'label' => $pokin['label'],
								'parent' => $pokin['parent'],
								'nomor_urut' => $pokin['nomor_urut'],
								'pelaksana' => $pokin['pelaksana'] ? $pokin['pelaksana'] : '',
								'bentuk_kegiatan' => $pokin['bentuk_kegiatan'] ? $pokin['bentuk_kegiatan'] : '',
								'outcome' => $pokin['outcome'] ? $pokin['outcome'] : '',
								'indikator' => []
							];
						}

						if (!empty($pokin['id_indikator'])) {
							if (empty($data['data'][$pokin['id']]['indikator'][$pokin['id_indikator'] . ' ' . $pokin['nomor_urut_indikator']])) {
								$data['data'][$pokin['id']]['indikator'][$pokin['id_indikator'] . ' ' . $pokin['nomor_urut_indikator']] = [
									'id' => $pokin['id_indikator'],
									'label' => $pokin['label_indikator_kinerja'],
									'nomor_urut' => $pokin['nomor_urut_indikator']
								];
							}
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

	public function create_pokin()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					$input = json_decode(stripslashes($_POST['data']), true);

					if (empty($_POST['id_jadwal'])) {
						throw new Exception("Id Jadwal Kosong!", 1);
					}

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
							AND parent=%d 
							AND level=%d 
							AND active=%d $_where_opd 
						ORDER BY id
					", trim($input['label']), $input['parent'], $input['level'], 1),  ARRAY_A);

					if (!empty($id)) {
						throw new Exception("Data sudah ada!", 1);
					}

					if ($_prefix_opd == "") {
						$wpdb->insert('esakip_pohon_kinerja', [
							'label' 		=> trim($input['label']),
							'parent' 		=> $input['parent'],
							'level' 		=> $input['level'],
							'id_jadwal' 	=> $_POST['id_jadwal'],
							'nomor_urut' 	=> $input['nomor_urut'],
							'active' 		=> 1
						]);
					} else {
						$wpdb->insert('esakip_pohon_kinerja' . $_prefix_opd, [
							'label' 		=> trim($input['label']),
							'parent' 		=> $input['parent'],
							'level' 		=> $input['level'],
							'id_jadwal' 	=> $_POST['id_jadwal'],
							'nomor_urut' 	=> $input['nomor_urut'],
							'active' 		=> 1,
							'id_skpd' 		=> $id_skpd
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
					if (!empty($_POST['tipe_pokin'] == "opd")) {
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
							*
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
						if (!empty($data_croscutting) && !empty($data_croscutting_pengusul)) {
							$data_croscutting = array_merge($data_croscutting, $data_croscutting_pengusul);
						} else if (empty($data_croscutting) && !empty($data_croscutting_pengusul)) {
							$data_croscutting = $data_croscutting_pengusul;
						}
					}

					$table_croscutting = '';
					$table_koneksi_croscutting_opd = '';
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

							if (!empty($v_cross['id_skpd_parent'])) {
								$this_data_id_skpd = $v_cross['id_skpd_parent'];
							} else {
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
							if ($status_croscutting == 'disetujui' && $v_cross['is_lembaga_lainnya'] != 1) {
								$aksi .= '<a href="javascript:void(0)" class="btn btn-sm btn-primary" data-id="' . $v_cross['id'] . '" href="#" title="Croscutting Disetujui">Disetujui</a>';
							} else {
								$aksi .= '<a href="javascript:void(0)" class="btn btn-sm btn-warning edit-croscutting" data-id="' . $v_cross['id'] . '" href="#" title="Edit Croscutting"><span class="dashicons dashicons-edit"></span></a>';
								$aksi .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger delete-croscutting" data-id="' . $v_cross['id'] . '" style="margin-left: 5px;" href="#" title="Hapus Croscutting"><span class="dashicons dashicons-trash"></span></a>';
							}

							if (!empty($v_cross['id_skpd_parent'])) {
								$aksi = '<a href="javascript:void(0)" class="btn btn-sm btn-success edit-verifikasi-croscutting" data-id="' . $v_cross['id'] . '" data-skpd-asal="' . $this_data_perangkat['nama_perangkat'] . '" data-keterangan-asal="' . $v_cross['keterangan'] . '" href="#" title="Verifikasi Croscutting"><span class="dashicons dashicons-yes"></span></a>';
							}

							$table_croscutting .= '<td class="text-center">' . $aksi . '</td>';

							$table_croscutting .= '</tr>';

							if (!empty($v_cross) && $v_cross['status_croscutting'] == 1) {
								$is_opd_lain_pengusul = $id_skpd != $this_data_id_skpd ? true : false;
								$is_opd_lain_tujuan = $id_skpd != $v_cross['id_skpd_croscutting'] ? true : false;

								if ($v_cross['is_lembaga_lainnya'] == 0 && $is_opd_lain_pengusul) {
									$new_view_pokin_page = $this->functions->generatePage(array(
										'nama_page' => 'Lihat Pohon Kinerja',
										'content' => '[new_view_pohon_kinerja]',
										'show_header' => 1,
										'post_status' => 'publish'
									));

									$link_pengusul_cc = '<a href="' . $new_view_pokin_page['url'] . '&id=' . $v_cross['parent_pohon_kinerja'] . '&tipe_koneksi=opd&id_koneksi_pokin=' . $v_cross['id'] . '" target="_blank">' . $this_data_perangkat['nama_perangkat'] . '</a>';
								} else {
									$link_pengusul_cc = $this_data_perangkat['nama_perangkat'];
								}
								if ($v_cross['is_lembaga_lainnya'] == 0 && $is_opd_lain_tujuan) {
									$new_view_pokin_page = $this->functions->generatePage(array(
										'nama_page' => 'Lihat Pohon Kinerja',
										'content' => '[new_view_pohon_kinerja]',
										'show_header' => 1,
										'post_status' => 'publish'
									));

									$link_tujuan_cc = '<a href="' . $new_view_pokin_page['url'] . '&id=' . $v_cross['parent_croscutting'] . '&tipe_koneksi=opd&id_koneksi_pokin=' . $v_cross['id'] . '" target="_blank">' . $data_perangkat['nama_perangkat'] . '</a>';
								} else {
									$link_tujuan_cc = $data_perangkat['nama_perangkat'];
								}

								$table_koneksi_croscutting_opd .= '
							        <tr style="border: 1px solid black;">
							            <td class="text-left" style="width: 270px; border: 1px solid black; padding: 8px;">' . $link_pengusul_cc . '</td>
							            <td class="text-left" style="width: 230px; border: 1px solid black; padding: 8px;">' . $v_cross['keterangan'] . '</td>
							            <td class="text-left" style="width: 230px; border: 1px solid black; padding: 8px;">' . $v_cross['keterangan_croscutting'] . '</td>
							            <td class="text-left" style="width: 230px; border: 1px solid black; padding: 8px;">' . $link_tujuan_cc . '</td>
							        </tr>
							    ';
							}
						}
					}

					/**
					 * Get Data Koneksi Pokin Pemda dan OPD
					 */
					$data_koneksi_pokin = array();
					if (empty($_prefix_opd) && !empty($data)) {
						$data_koneksi_pokin = $wpdb->get_results($wpdb->prepare("
								SELECT 
									koneksi.*,
									pokin_opd.id as id_pokin_opd,
									pokin_opd.label as label_pokin_opd
								FROM esakip_koneksi_pokin_pemda_opd as koneksi
								LEFT JOIN esakip_pohon_kinerja_opd as pokin_opd
								  ON koneksi.parent_pohon_kinerja_koneksi = pokin_opd.id
								WHERE koneksi.parent_pohon_kinerja=%d 
								  AND koneksi.active=%d
							", $_POST['id'], 1), ARRAY_A);
					} else if (!empty($_prefix_opd) && $_prefix_opd == '_opd' && !empty($data)) {
						// get data koneksi pokin pemda
						$data_koneksi_pokin = $wpdb->get_results($wpdb->prepare("
							SELECT 
								koneksi.*,
								pk.id as id_parent_pemda,
								pk.label as label_pokin_pemda
							FROM esakip_koneksi_pokin_pemda_opd as koneksi
							JOIN esakip_pohon_kinerja as pk
							ON koneksi.parent_pohon_kinerja = pk.id
							WHERE koneksi.id_skpd_koneksi=%d
								AND koneksi.status_koneksi=1 
								AND koneksi.active=1
								AND koneksi.tipe=1
								AND koneksi.parent_pohon_kinerja_koneksi=%d
						", $id_skpd, $_POST['id']),  ARRAY_A);

						if (!empty($data_koneksi_pokin)) {
							foreach ($data_koneksi_pokin as $k_koneksi_pokin => $v_koneksi_pokin) {
								$data_koneksi_pokin[$k_koneksi_pokin]['indikator_pokin_pemda'] = '';
								$data_label_indikator_pokin_pemda = $wpdb->get_results(
									$wpdb->prepare("
									SELECT 
										pk.label_indikator_kinerja as indikator_parent_pemda
									FROM 
										esakip_pohon_kinerja as pk
									WHERE pk.parent=%d
										AND pk.active=1
										AND pk.label_indikator_kinerja IS NOT NULL
								", $v_koneksi_pokin['id_parent_pemda']),
									ARRAY_A
								);

								$indikator_pokin_pemda = array();
								if (!empty($data_label_indikator_pokin_pemda)) {
									$no = 1;
									foreach ($data_label_indikator_pokin_pemda as $key => $v_indikator_pemda) {
										$class_indikator_pemda = '';
										$border_color = '';
										if (count($data_label_indikator_pokin_pemda) > 1) {
											$class_indikator_pemda = 'koneksi-indikator-pemda-isi';
											$border_color = 'border-color: #dee2e6;';
										}
										$class_indikator_pemda_1 = $no == 1 && $class_indikator_pemda != '' ? 'border-style: none;margin-top: -8px;' : '';
										$indikator_pokin_pemda[] = '<div class="' . $class_indikator_pemda . '" style="' . $class_indikator_pemda_1 . ' ' . $border_color . '">' . $v_indikator_pemda['indikator_parent_pemda'] . '</div>';
										$no++;
									}
								}
								if (!empty($indikator_pokin_pemda)) {
									$data_koneksi_pokin[$k_koneksi_pokin]['indikator_pokin_pemda'] .= implode("", $indikator_pokin_pemda);
								}
							}
						}
					}

					$table_koneksi_pokin = '';
					$table_koneksi_croscutting_pemda = '';
					$list_pd_koneksi_pokin = [];
					$no = 1;
					if (!empty($data_koneksi_pokin)) {
						$nama_cross = $this->get_nama_crosscuttin();
						foreach ($data_koneksi_pokin as $k_koneksi_pokin => $v_koneksi_pokin) {
							switch ($v_koneksi_pokin['status_koneksi']) {
								case '1':
									$status_koneksi = 'disetujui';
									$text_color = 'text-success';
									break;

								case '2':
									$status_koneksi = 'ditolak';
									$text_color = 'text-danger';
									break;

								default:
									$status_koneksi = 'menunggu';
									$text_color = 'text-secondary';
									break;
							}

							if (!empty($_prefix_opd) && $_prefix_opd == '_opd' && !empty($data)) {
								$aksi_koneksi = '<a href="javascript:void(0)" class="btn btn-sm btn-success edit-verifikasi-koneksi-pokin-pemda" data-id="' . $v_koneksi_pokin['id'] . '" data-label-pokin-pemda="' . $v_koneksi_pokin['label_pokin_pemda'] . '" href="#" title="Verifikasi Koneksi Pohon Kinerja Pemerintah Daerah"><span class="dashicons dashicons-yes"></span></a>';
								$table_koneksi_pokin .= '
									<tr>
										<td>' . $no++ . '</td>
										<td>' . $v_koneksi_pokin['label_pokin_pemda'] . '</td>
										<td>' . $v_koneksi_pokin['indikator_pokin_pemda'] . '</td>
										<td>' . $v_koneksi_pokin['keterangan_koneksi'] . '</td>
										<td>' . $status_koneksi . '</td>
										<td class="text-center">' . $aksi_koneksi . '</td>
									</tr>';
							} else {
								$nama_perangkat = '';
								// skpd dan uptd
								if ($v_koneksi_pokin['tipe'] == 1 || $v_koneksi_pokin['tipe'] == 3) {
									if (!empty($nama_cross['skpd'][$v_koneksi_pokin['id_skpd_koneksi']])) {
										$nama_perangkat = $nama_cross['skpd'][$v_koneksi_pokin['id_skpd_koneksi']]['nama_skpd'];
									} else {
										$nama_perangkat = 'ID SKPD ' . $v_koneksi_pokin['id_skpd_koneksi'] . ' tidak ditemukan';
									}
									// lembaga lainnya
								} else if ($v_koneksi_pokin['tipe'] == 2) {
									if (!empty($nama_cross['lembaga'][$v_koneksi_pokin['id_skpd_koneksi']])) {
										$nama_perangkat = $nama_cross['lembaga'][$v_koneksi_pokin['id_skpd_koneksi']]['nama_lembaga'];
									} else {
										$nama_perangkat = 'ID Lembaga ' . $v_koneksi_pokin['id_skpd_koneksi'] . ' tidak ditemukan';
									}
									// desa
								} else if ($v_koneksi_pokin['tipe'] == 4) {
									$nama_perangkat = $v_koneksi_pokin['nama_desa'];
								}

								if (empty($nama_perangkat)) {
									$nama_perangkat = 'Tipe Koneksi tidak diketahui';
								}

								$aksi_koneksi = '';
								$keterangan_tolak = (!empty($v_koneksi_pokin['keterangan_tolak'])) ? '<hr><small class="text-muted">Keterangan ditolak : <br>' . $v_koneksi_pokin['keterangan_tolak'] . '</small>' : '';
								$table_koneksi_pokin .= '
									<tr>
										<td class="text-center">' . $no++ . '</td>
										<td class="text-left">' . $nama_perangkat . '</td>
										<td class="text-center font-weight-bold ' . $text_color . '">' . $status_koneksi . '</td>
										<td>' . $v_koneksi_pokin['keterangan_koneksi'] . $keterangan_tolak . '</td>';

								$aksi_koneksi .= '
											<div class="btn btn-sm btn-warning" title="Edit Koneksi" onclick="handleFormEditKoneksiPokin(' . $v_koneksi_pokin['id'] . ', ' . $data['id'] . ', this)">
												<span class="dashicons dashicons-edit"></span>
											</div>
											<div class="btn btn-sm m-1 btn-danger" title="Hapus Koneksi" onclick="handleDeleteKoneksiPokin(' . $v_koneksi_pokin['id'] . ', ' . $data['id'] . ', ' . $data['parent'] . ', ' . $data['level'] . ')">
												<span class="dashicons dashicons-trash"></span>
											</div>';
								// if ($v_koneksi_pokin['tipe'] != 1) {
								// 	if ($status_koneksi == 'disetujui') {
								// 	} else {
								// 		$aksi_koneksi .= '
								// 			<div class="btn btn-sm m-2 btn-danger" title="Hapus Koneksi" onclick="handleDeleteKoneksiPokin(' . $v_koneksi_pokin['id'] . ', ' . $data['id'] . ', ' . $data['parent'] . ', ' . $data['level'] . ')">
								// 				<span class="dashicons dashicons-trash"></span>
								// 			</div>';
								// 	}
								// } else {
								// 	if ($status_koneksi == 'disetujui') {
								// 		$aksi_koneksi .= '<div> - </div>';
								// 	} else {
								// 		$aksi_koneksi .= '
								// 			<div class="btn btn-sm m-2 btn-warning" title="Edit Koneksi" onclick="handleFormEditKoneksiPokin(' . $v_koneksi_pokin['id'] . ', ' . $data['id'] . ', this)">
								// 				<span class="dashicons dashicons-edit"></span>
								// 			</div>
								// 			<div class="btn btn-sm m-2 btn-danger" title="Hapus Koneksi" onclick="handleDeleteKoneksiPokin(' . $v_koneksi_pokin['id'] . ', ' . $data['id'] . ', ' . $data['parent'] . ', ' . $data['level'] . ')">
								// 				<span class="dashicons dashicons-trash"></span>
								// 			</div>';
								// 	}
								// }

								$table_koneksi_pokin .= '
									<td class="text-center">' . $aksi_koneksi . '</td>
								</tr>';
								if (!empty($v_koneksi_pokin) && $v_koneksi_pokin['status_koneksi'] == 1) {
									$pokin_opd_url = '-';
									if ($v_koneksi_pokin['tipe'] == 1) {
										$new_view_pokin_page = $this->functions->generatePage(array(
											'nama_page' => 'Lihat Pohon Kinerja',
											'content' => '[new_view_pohon_kinerja]',
											'show_header' => 1,
											'post_status' => 'publish'
										));
										$pokin_opd_url = '<a href="' . $new_view_pokin_page['url'] . '&id=' . $v_koneksi_pokin['id_pokin_opd'] . '&id_koneksi_pokin=' . $v_koneksi_pokin['id'] . '" target="_blank">' . $v_koneksi_pokin['label_pokin_opd'] . '</a>';
									}
									$table_koneksi_croscutting_pemda .= '
								        <tr style="border: 1px solid black;">
								            <td class="text-left" style="width: 270px; border: 1px solid black; padding: 8px;">' . $nama_perangkat . '</td>
								            <td class="text-left" style="border: 1px solid black; padding: 8px;">' . $pokin_opd_url . '</td>
								            <td class="text-left" style="width: 230px; border: 1px solid black; padding: 8px;">' . $v_koneksi_pokin['keterangan_koneksi'] . '</td>
								        </tr>';
									$list_pd_koneksi_pokin[] = $nama_perangkat;
								}
							}
						}
					}

					if (empty($data)) {
						throw new Exception("Data tidak ditemukan!", 1);
					} else if (empty($data['nomor_urut'])) {
						$data['nomor_urut'] = $data['id'];
					}
					$indikator = $wpdb->get_results($wpdb->prepare("
						SELECT 
							label_indikator_kinerja
						FROM esakip_pohon_kinerja$_prefix_opd 
						WHERE parent=%d 
							AND active=%d
							AND label_indikator_kinerja IS NOT NULL$_where_opd
					", $_POST['id'], 1),  ARRAY_A);
					$list_indikator = [];
					if (!empty($indikator)) {
						foreach ($indikator as $row) {
							$list_indikator[] = $row['label_indikator_kinerja'];
						}
					}
					echo json_encode([
						'status' => true,
						'data' => $data,
						'indikator' => $list_indikator,
						'data_croscutting' => $table_croscutting,
						'data_koneksi_pokin' => $table_koneksi_pokin,
						'data_koneksi_croscutting_pemda' => $table_koneksi_croscutting_pemda,
						'data_koneksi_croscutting_opd' => $table_koneksi_croscutting_opd,
						'list_pd_koneksi_pokin' => $list_pd_koneksi_pokin
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

	public function get_nama_crosscuttin()
	{
		global $wpdb;
		$unit_koneksi = $wpdb->get_results(
			"
				SELECT 
					nama_skpd, 
					id_skpd, 
					kode_skpd, 
					nipkepala,
					tahun_anggaran 
				FROM esakip_data_unit 
				WHERE active=1 
				GROUP BY id_skpd
				ORDER BY kode_skpd ASC
			",
			ARRAY_A
		);
		$all_skpd = array();
		if (!empty($unit_koneksi)) {
			foreach ($unit_koneksi as $v_unit) {
				$all_skpd[$v_unit['id_skpd']] = $v_unit;
			}
		}

		$lembaga = $wpdb->get_results(
			"
				SELECT 
					*
				FROM esakip_data_lembaga_lainnya 
				WHERE active=1 
			",
			ARRAY_A
		);
		$all_lembaga = array();
		if (!empty($lembaga)) {
			foreach ($lembaga as $v) {
				$all_lembaga[$v['id']] = $v;
			}
		}
		return array(
			'skpd' => $all_skpd,
			'lembaga' => $all_lembaga
		);
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

					if (empty($input['no_urut'])) {
						$input['no_urut'] = $input['id'];
					}
					if (empty($input['pelaku'])) {
						$input['pelaku'] = '';
					}
					if (empty($input['bentuk_kegiatan'])) {
						$input['bentuk_kegiatan'] = '';
					}
					if (empty($input['outcome'])) {
						$input['outcome'] = '';
					}
					$data = array(
						'label' => trim($input['label']),
						'nomor_urut' => $input['nomor_urut'],
						'pelaksana' => $input['pelaku'],
						'bentuk_kegiatan' => $input['bentuk_kegiatan'],
						'outcome' => $input['outcome']
					);

					if ($_prefix_opd == '') {
						// untuk pokin pemda //////////////////////////////////////////////////////////////////////////////
						$wpdb->update('esakip_pohon_kinerja', $data, [
							'id' => $input['id']
						]);

						$wpdb->query($wpdb->prepare("
							UPDATE esakip_pohon_kinerja 
							SET label=%s 
							WHERE parent=%d 
								AND label_indikator_kinerja IS NOT NULL
						", trim($input['label']), $input['id']));
					} else {
						// untuk pokin opd  //////////////////////////////////////////////////////////////////////////////
						$data['pelaksana'] = null; // pelaksana di opd di set null karena pasti opd itu sendiri.
						$wpdb->update('esakip_pohon_kinerja' . $_prefix_opd, $data, [
							'id' => $input['id'],
							'id_skpd' => $id_skpd
						]);

						$wpdb->query($wpdb->prepare("
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
							$_where_opd = $_POST['tipe_pokin'] == "opd" ? $wpdb->prepare(' AND id_skpd = %d', $id_skpd) : '';
						} else {
							throw new Exception("Id SKPD tidak ditemukan!", 1);
						}
					}

					$indikator = $wpdb->get_row(
						$wpdb->prepare("
							SELECT id
							FROM esakip_pohon_kinerja$_prefix_opd 
							WHERE parent = %d 
							  AND label_indikator_kinerja IS NOT NULL
							  AND active=%d
							$_where_opd
						", $_POST['id'], 1),
						ARRAY_A
					);

					if (!empty($indikator)) {
						throw new Exception("Indikator harus dihapus dulu!", 1);
					}

					$child = $wpdb->get_results(
						$wpdb->prepare("
							SELECT 
								id,
								level 
							FROM esakip_pohon_kinerja$_prefix_opd 
							WHERE parent = %d 
							  AND active = %d
							  $_where_opd
						", $_POST['id'], 1),
						ARRAY_A
					);

					if (!empty($child)) {
						throw new Exception("Pohon kinerja level " . $child[0]['level'] . " harus dihapus dulu!", 1);
					}

					// cek koneksi croscutting
					$cek_crosscutting = [];
					if ($_prefix_opd == '') {
						$cek_crosscutting = $wpdb->get_row(
							$wpdb->prepare("
								SELECT
									pk.id as id_pokin,
									pk.label as label_pokin,
									cc.id as id_croscutting,
									cc.parent_pohon_kinerja as parent_croscutting
								FROM esakip_pohon_kinerja pk
								JOIN esakip_koneksi_pokin_pemda_opd cc
								  ON cc.parent_pohon_kinerja = pk.id
								WHERE cc.active = 1
								  AND pk.active = 1
								  AND parent_pohon_kinerja = %d
							", $_POST['id']),
							ARRAY_A
						);
					} else {
						$cek_crosscutting = $wpdb->get_results(
							$wpdb->prepare("
								SELECT 
									pk.id AS id_pokin,
									cc.id AS id_koneksi
								FROM esakip_koneksi_pokin_pemda_opd cc
								LEFT JOIN esakip_pohon_kinerja_opd AS pk 
									   ON cc.parent_pohon_kinerja_koneksi = pk.id
								WHERE cc.parent_pohon_kinerja_koneksi = %d 
								  AND cc.active = 1 
								  AND pk.active = 1
								  AND pk.id_skpd = %d
							", $_POST['id'], $id_skpd),
							ARRAY_A
						);
					}

					if (!empty($cek_crosscutting)) {
						throw new Exception("Data pohon kinerja memiliki crosscutting aktif!\nMohon hapus data crosscutting terlebih dahulu!", 1);
					}

					if ($_prefix_opd == '') {
						// untuk pemda
						$wpdb->update(
							'esakip_pohon_kinerja',
							['active' => 0],
							['id' => $_POST['id']]
						);
					} else {
						// untuk opd
						$wpdb->update(
							'esakip_pohon_kinerja_opd',
							['active' => 0],
							[
								'id' => $_POST['id'],
								'id_skpd' => $id_skpd
							]
						);
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

					$input = json_decode(stripslashes($_POST['data']), true);

					if (empty($_POST['id_jadwal'])) {
						throw new Exception("Id Jadwal Kosong!", 1);
					}

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
						WHERE label_indikator_kinerja=%s 
							AND parent=%d 
							AND level=%d 
							AND active=%d$_where_opd
					", trim($input['indikator_label']), $input['parent'], $input['level'], 1),  ARRAY_A);

					if (!empty($id)) {
						throw new Exception("Data sudah ada!", 1);
					}

					if ($_prefix_opd == '') {
						$wpdb->insert('esakip_pohon_kinerja', [
							'label_indikator_kinerja' => trim($input['indikator_label']),
							'parent' 				  => $input['parent'],
							'level' 				  => $input['level'],
							'id_jadwal' 			  => $_POST['id_jadwal'],
							'nomor_urut' 			  => $input['nomor_urut'],
							'active' 				  => 1
						]);
					} else {
						$wpdb->insert('esakip_pohon_kinerja' . $_prefix_opd, [
							'label_indikator_kinerja' => trim($input['indikator_label']),
							'parent' 				  => $input['parent'],
							'level' 				  => $input['level'],
							'id_jadwal' 			  => $_POST['id_jadwal'],
							'nomor_urut' 			  => $input['nomor_urut'],
							'active' 				  => 1,
							'id_skpd' 				  => $id_skpd
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
							a.nomor_urut,
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
							'nomor_urut' => $input['nomor_urut'],
						], [
							'id' => $input['id'],
							'parent' => $input['parent'],
							'level' => $input['level'],
						]);
					} else {
						$data = $wpdb->update('esakip_pohon_kinerja' . $_prefix_opd, [
							'label_indikator_kinerja' => trim($input['indikator_label']),
							'nomor_urut' => $input['nomor_urut'],
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

				$get_tujuan = $wpdb->get_results($wpdb->prepare("
                    SELECT 
                    	* 
                    FROM esakip_rpd_tujuan
                    WHERE id_unik_indikator IS NULL
                      AND active = 1
                      AND id_jadwal=%d
                ", $_POST['id_jadwal']), ARRAY_A);

				if (!empty($get_tujuan)) {
					$counter = 1;
					$tbody = '';

					foreach ($get_tujuan as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['nama_cascading'] . "</td>";
						$tbody .= "<td>" . $vv['tujuan_teks'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="view_cascading(\'' . $vv['id'] . '\'); return false;" href="#" title="View"><span class="dashicons dashicons-visibility get_table_cascading"></span></button>';
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
				$get_tujuan = $wpdb->get_results($wpdb->prepare("
                    SELECT 
                    	* 
                    FROM esakip_rpd_tujuan
                    WHERE id_unik_indikator IS NULL
                      AND active = 1
                      AND id_jadwal=%d
                ", $id_jadwal), ARRAY_A);

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
			if (empty($_POST['id_jadwal'])) {
				$ret['status'] = 'error';
				$ret['message'] = 'id Jadwal kosong!';
			} else if (empty($_POST['id'])) {
				$ret['status'] = 'error';
				$ret['message'] = 'id kosong!';
			}

			if ($ret['status'] != 'error') {
				$jenis_jadwal = $wpdb->get_var(
					$wpdb->prepare("
						SELECT 
							jenis_jadwal_khusus
						FROM esakip_data_jadwal
						WHERE id = %d
						  AND status != 0
					", $_POST['id_jadwal'])
				);
				$tujuan = $wpdb->get_row(
					$wpdb->prepare("
						SELECT *
						FROM esakip_rpd_tujuan
						WHERE id = %d
						  AND active=1
					", $_POST['id']),
					ARRAY_A
				);
				$indikator_tujuan = $wpdb->get_results(
					$wpdb->prepare("
						SELECT *
						FROM esakip_rpd_tujuan
						WHERE id_unik = %s
						  AND active = 1
						  AND id_unik_indikator IS NOT NULL
					", $tujuan['id_unik']),
					ARRAY_A
				);
				$jumlah_indikator_tujuan = count($indikator_tujuan);

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
				$misi_html = '';
				$jenis_misi_teks = '';
				if ($jenis_jadwal == 'rpjmd') {
					$misi_rpjmd = $wpdb->get_results(
						$wpdb->prepare("
				            SELECT 
				            	m.misi
				            FROM esakip_rpjmd_misi_detail d
				            JOIN esakip_rpjmd_misi m ON d.id_misi = m.id
				            	AND d.active = m.active
				            WHERE d.id_tujuan = %s
				              AND d.active = 1
				        ", $tujuan['id_unik']),
						ARRAY_A
					);
					foreach ($misi_rpjmd as $misi) {
						$misi_html .= $misi['misi'] . '<br>';
					}
					$jenis_misi_teks = 'MISI RPJMD';
				} else {
					foreach ($misi_rpjpd as $misi) {
						$misi_html .= $misi['misi_teks'] . '<br>';
					}
					$jenis_misi_teks = 'ISU RPJPD';
				}

				// sasaran rpd
				$sasaran = $wpdb->get_results(
					$wpdb->prepare("
						SELECT *
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
							SELECT *
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

						$mapping_skpd_program = [];
						$nama_bidang_urusan = [];

						//urusan pengampu skpds views once
						$skpd_program = $wpdb->get_results(
							$wpdb->prepare("
								SELECT *
								FROM esakip_rpd_program
								WHERE kode_sasaran = %s
								  AND id_unik_indikator_sasaran IS NULL
								  AND id_unik_indikator IS NULL
								  AND active = 1
							", $ind['id_unik']),
							ARRAY_A
						);

						// indikator sasaran sasaran rpd
						foreach ($skpd_program as $prog) {
							if (!empty($prog['nama_bidang_urusan'])) {
								$nama_bidang = trim($prog['nama_bidang_urusan']);
								if (!isset($nama_bidang_urusan[$nama_bidang])) {
									$nama_bidang_urusan[$nama_bidang] = $prog;
								}
							}
						}

						// Bangun tampilan HTML
						if (!empty($nama_bidang_urusan)) {
							foreach ($nama_bidang_urusan as $bidang) {
								$data_program .= '
									<li style="margin-bottom:14px;">
										<button class="btn btn-lg btn-warning" style="text-transform:uppercase;">' . esc_html($bidang['nama_bidang_urusan']) . '</button>
									</li>';
							}
						} else {
							$data_program .= '
								<li style="margin-bottom:14px;">
									<button class="btn btn-lg btn-warning"></button>
								</li>';
						}

						$data_program .= '</ul></td>';
					}
					if (empty($data_sasaran)) {
						$data_sasaran = '
							<td class="text-center" width="' . $width_ind_sasaran . '%">
								<button class="btn btn-lg btn-warning"></button>
							</td>';
						$data_program = '
							<td class="text-center" width="' . $width_ind_sasaran . '%">
								<button class="btn btn-lg btn-warning"></button>
							</td>';
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
				$colspan_ind_tujuan = $jumlah_indikator_tujuan ? $colspan_tujuan / $jumlah_indikator_tujuan : $colspan_tujuan;
				foreach ($indikator_tujuan as $ind) {
					$data .= '<td class="text-center" colspan="' . $colspan_ind_tujuan . '"><button class="btn btn-lg btn-warning" style="text-transform:uppercase;">' . $ind['indikator_teks'] . '</button></td>';
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

				// render html
				$html = '
					<div class="text-center" id="action-sakip">
						<button class="btn btn-primary btn-large" onclick="window.print();"><i class="dashicons dashicons-printer"></i> Cetak / Print</button>
					</div>
						<h1 class="text-center">' . $tujuan['nama_cascading'] . '</h1>
						<table id="tabel-cascading">
							<tbody>
								<tr>
									<td class="text-center" style="width: 200px;"><button class="btn btn-lg btn-info">' . $jenis_misi_teks . '</button></td>
									<td class="text-center" colspan="' . $colspan_tujuan . '"><button class="btn btn-lg btn-warning" style="text-transform:uppercase;">' . $misi_html . '</button></td>
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">TUJUAN ' . strtoupper($jenis_jadwal) . '</button></td>
									<td class="text-center" colspan="' . $colspan_tujuan . '"><button class="btn btn-lg btn-warning" style="text-transform:uppercase;">' . $tujuan['tujuan_teks'] . '</button></td>
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">INDIKATOR TUJUAN ' . strtoupper($jenis_jadwal) . '</button></td>
									' . $indikator_tujuan_html . '
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">SASARAN ' . strtoupper($jenis_jadwal) . '</button></td>
									<td class="text-center" colspan=' . $colspan_tujuan . '>' . $sasaran_html . '</td>
								</tr>
								<tr>
									<td class="text-center"><button class="btn btn-lg btn-info">INDIKATOR SASARAN ' . strtoupper($jenis_jadwal) . '</button></td>
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

				$penyusunan_pohon_kinerja_opd = false;
				if (!empty($_POST['penyusunan_pohon_kinerja_opd'])) {
					$penyusunan_pohon_kinerja_opd = ($_POST['penyusunan_pohon_kinerja_opd']) ?: false;
				}

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

				if ($ret['status'] != 'error') {
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
						$total_level_1 = 0;
						$total_level_2 = 0;
						$total_level_3 = 0;
						$total_level_4 = 0;
						$total_level_5 = 0;
						$total_crosscutting_usulan = 0;
						$total_crosscutting_usulan_vertikal = 0;
						$total_crosscutting_tujuan = 0;
						$total_integrasi = 0;
						$mapping_jenis_dokumen_esr = [];

						if ($penyusunan_pohon_kinerja_opd == false) {
							/** get data esr */
							$data_dokumen_terintegrasi = array();
							$status_api_esr = get_option('_crb_api_esr_status');
							if ($status_api_esr) {
								$pengaturan_periode_dokumen = $wpdb->get_row($wpdb->prepare(
									"
									SELECT 
										* 
									FROM 
										esakip_pengaturan_upload_dokumen 
									WHERE 
										id_jadwal_rpjmd=%d AND 
										active=%d",
									$id_jadwal,
									1
								), ARRAY_A);

								if (!empty($pengaturan_periode_dokumen)) {
									$data_dokumen_terintegrasi = $this->get_total_integrasi_esr('esakip_pohon_kinerja_dan_cascading', $pengaturan_periode_dokumen['tahun_anggaran']);
								}
							}
						}
						foreach ($unit as $kk => $vv) {

							if ($penyusunan_pohon_kinerja_opd == false) {
								$mapping_jenis_dokumen_esr = $wpdb->get_row($wpdb->prepare("
										SELECT 
												a.*
										FROM 
											esakip_data_mapping_jenis_dokumen_esr a 
										JOIN esakip_menu_dokumen b 
											ON b.id=a.esakip_menu_dokumen_id AND 
												a.tahun_anggaran=b.tahun_anggaran AND b.active=1
					                    JOIN esakip_data_jenis_dokumen_esr c 
					                       	ON c.jenis_dokumen_esr_id=a.jenis_dokumen_esr_id  AND 
					                      		c.tahun_anggaran=a.tahun_anggaran AND c.active=1
					                    where 
					                      	a.tahun_anggaran=%d and
					                        b.nama_tabel=%s;
									", $pengaturan_periode_dokumen['tahun_anggaran'], 'esakip_pohon_kinerja_dan_cascading'), ARRAY_A);
								// print_r($mapping_jenis_dokumen_esr); die($wpdb->last_query);
								if (!empty($mapping_jenis_dokumen_esr)) {
									$tahun_anggaran = $pengaturan_periode_dokumen['tahun_anggaran'];
									$array_data_esr = [];
									$data_esr = $this->data_esr($vv['id_skpd']);
									if ($data_esr['status'] == 'success') {
										$diff_data_esr = intval($data_esr['data_esr_lokal']->diff);
										$data_esr = json_decode($data_esr['data_esr_lokal']->response_json);

										foreach ($data_esr as $key => $esr) {
											if ($esr->dokumen_id == $mapping_jenis_dokumen_esr['jenis_dokumen_esr_id']) {
												$path = explode("/", $esr->path);
												$nama_file = end($path);
												$array_data_esr[] = [
													'upload_id' => $esr->upload_id,
													'nama_file' => $nama_file,
													'keterangan' => $esr->keterangan,
													'path' => $esr->path
												];
											}
										}
									} else {
										$ret['data_esr'] = $data_esr;
									}
								}
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
								$jumlah_upload_id = $wpdb->get_results(
									$wpdb->prepare(
										"
						            	SELECT 
						            		upload_id 
						            	FROM esakip_pohon_kinerja_dan_cascading 
						            	WHERE id_skpd = %d 
							            	AND id_jadwal = %d 
							            	AND active = 1",
										$vv['id_skpd'],
										$id_jadwal
									),
									ARRAY_A
								);

								$jumlah_dokumen_terintegrasi = 0;
								if (!empty($jumlah_upload_id)) {
									foreach ($jumlah_upload_id as $dokumen) {
										if (!empty($array_data_esr)) {
											if (in_array($dokumen['upload_id'], array_column($array_data_esr, 'upload_id'))) {
												$jumlah_dokumen_terintegrasi++;
											}
										}
									}
								}
								$btn = '<div class="btn-action-group">';
								$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_pohon_kinerja['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
								$btn .= '</div>';

								$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
								$tbody .= "<td class='text-center'>" . $jumlah_dokumen_terintegrasi . "</td>";
								$tbody .= "<td>" . $btn . "</td>";

								$tbody .= "</tr>";
							} else if ($penyusunan_pohon_kinerja_opd == true) {
								$detail_penyusunan_pohon_kinerja_opd = $this->functions->generatePage(array(
									'nama_page' => 'Halaman Detail Dokumen Pohon Kinerja Perangkat Daerah | ' . $periode['nama_jadwal'] . ' ' . 'Periode ' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode_selesai  . ' Perangkat Daerah',
									'content' => '[penyusunan_pohon_kinerja_opd periode=' . $id_jadwal . ']',
									'show_header' => 1,
									'post_status' => 'private'
								));

								$jumlah_level_1 = $wpdb->get_var(
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
								$jumlah_level_2 = $wpdb->get_var(
									$wpdb->prepare("
										SELECT 
											COUNT(p.id)
										FROM esakip_pohon_kinerja_opd p
										INNER JOIN esakip_pohon_kinerja_opd parent ON parent.id=p.parent
											AND parent.active=p.active
											AND parent.id_jadwal=p.id_jadwal
											AND parent.level=1
										WHERE p.level=2 
											AND p.active=1
											AND p.id_skpd = %d
											AND p.id_jadwal = %d
									", $vv['id_skpd'], $id_jadwal)
								);
								$jumlah_level_3 = $wpdb->get_var(
									$wpdb->prepare("
										SELECT 
											COUNT(p.id)
										FROM esakip_pohon_kinerja_opd p
										INNER JOIN esakip_pohon_kinerja_opd parent ON parent.id=p.parent
											AND parent.active=p.active
											AND parent.id_jadwal=p.id_jadwal
											AND parent.level=2
										WHERE p.level=3 
											AND p.active=1
											AND p.id_skpd = %d
											AND p.id_jadwal = %d
									", $vv['id_skpd'], $id_jadwal)
								);
								$jumlah_level_4 = $wpdb->get_var(
									$wpdb->prepare("
										SELECT 
											COUNT(p.id)
										FROM esakip_pohon_kinerja_opd p
										INNER JOIN esakip_pohon_kinerja_opd parent ON parent.id=p.parent
											AND parent.active=p.active
											AND parent.id_jadwal=p.id_jadwal
											AND parent.level=3
										WHERE p.level=4 
											AND p.active=1
											AND p.id_skpd = %d
											AND p.id_jadwal = %d
									", $vv['id_skpd'], $id_jadwal)
								);
								$jumlah_level_5 = $wpdb->get_var(
									$wpdb->prepare("
										SELECT 
											COUNT(p.id)
										FROM esakip_pohon_kinerja_opd p
										INNER JOIN esakip_pohon_kinerja_opd parent ON parent.id=p.parent
											AND parent.active=p.active
											AND parent.id_jadwal=p.id_jadwal
											AND parent.level=4
										WHERE p.level=5 
											AND p.active=1
											AND p.id_skpd = %d
											AND p.id_jadwal = %d
									", $vv['id_skpd'], $id_jadwal)
								);

								$croscutting_pohon_kinerja_pengusul = $wpdb->get_var($wpdb->prepare("
									SELECT 
										COUNT(cc.id)
									FROM esakip_croscutting_opd as cc
									INNER JOIN esakip_pohon_kinerja_opd as pk ON cc.parent_pohon_kinerja = pk.id
										AND pk.active=cc.active
									WHERE pk.id_skpd=%d
										AND cc.status_croscutting=1 
										AND cc.active=1
										AND cc.is_lembaga_lainnya=0
										AND pk.id_jadwal=%d
								", $vv['id_skpd'], $id_jadwal));

								$croscutting_pohon_kinerja_pengusul_vertikal = $wpdb->get_var($wpdb->prepare("
									SELECT 
										COUNT(cc.id)
									FROM esakip_croscutting_opd as cc
									INNER JOIN esakip_pohon_kinerja_opd as pk ON cc.parent_pohon_kinerja = pk.id
										AND pk.active=cc.active
									WHERE pk.id_skpd=%d
										AND cc.status_croscutting=1 
										AND cc.is_lembaga_lainnya=1
										AND cc.active=1
										AND pk.id_jadwal=%d
								", $vv['id_skpd'], $id_jadwal));

								$croscutting_pohon_kinerja_dituju = $wpdb->get_var($wpdb->prepare("
									SELECT 
										COUNT(cc.id)
									FROM esakip_croscutting_opd as cc
									INNER JOIN esakip_pohon_kinerja_opd as pk ON cc.parent_croscutting = pk.id
										AND pk.active=cc.active
									WHERE cc.id_skpd_croscutting=%d
										AND cc.status_croscutting=1 
										AND cc.active=1
										AND pk.id_jadwal=%d
								", $vv['id_skpd'], $id_jadwal));

								$tbody .= "<tr>";
								$tbody .= "<td style='text-transform: uppercase;'><a href='" . $detail_penyusunan_pohon_kinerja_opd['url'] . "&id_skpd=" . $vv['id_skpd'] . "' target='_blank'>" . $vv['kode_skpd'] . " " . $vv['nama_skpd'] . "</a></td>";
								$tbody .= "<td class='text-center'>" . $jumlah_level_1 . "</td>";
								$tbody .= "<td class='text-center'>" . $jumlah_level_2 . "</td>";
								$tbody .= "<td class='text-center'>" . $jumlah_level_3 . "</td>";
								$tbody .= "<td class='text-center'>" . $jumlah_level_4 . "</td>";
								$tbody .= "<td class='text-center'>" . $jumlah_level_5 . "</td>";
								$tbody .= "<td class='text-center'>" . $croscutting_pohon_kinerja_pengusul . "</td>";
								$tbody .= "<td class='text-center'>" . $croscutting_pohon_kinerja_pengusul_vertikal . "</td>";
								$tbody .= "<td class='text-center'>" . $croscutting_pohon_kinerja_dituju . "</td>";
								$tbody .= "</tr>";

								$total_level_1 += $jumlah_level_1;
								$total_level_2 += $jumlah_level_2;
								$total_level_3 += $jumlah_level_3;
								$total_level_4 += $jumlah_level_4;
								$total_level_5 += $jumlah_level_5;
								$total_crosscutting_usulan_vertikal += $croscutting_pohon_kinerja_pengusul_vertikal;
								$total_crosscutting_usulan += $croscutting_pohon_kinerja_pengusul;
								$total_crosscutting_tujuan += $croscutting_pohon_kinerja_dituju;
							}
						}
						$ret['data'] = $tbody;
						$ret['total_level_1'] = $total_level_1;
						$ret['total_level_2'] = $total_level_2;
						$ret['total_level_3'] = $total_level_3;
						$ret['total_level_4'] = $total_level_4;
						$ret['total_level_5'] = $total_level_5;
						$ret['total_crosscutting_usulan_vertikal'] = $total_crosscutting_usulan_vertikal;
						$ret['total_crosscutting_usulan'] = $total_crosscutting_usulan;
						$ret['total_crosscutting_tujuan'] = $total_crosscutting_tujuan;
					} else {
						$ret['data'] = "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
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
									is_lembaga_lainnya,
									keterangan_tolak,
									status_croscutting
								FROM esakip_croscutting_opd
								WHERE id=%d 
									AND active=%d
							", $_POST['id'], 1),  ARRAY_A);
					}
					if (!empty($data_croscutting)) {
						$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
						$nama_perangkat = array();
						if ($data_croscutting['is_lembaga_lainnya'] == 1) {
							$nama_perangkat = $wpdb->get_row(
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
								", $data_croscutting['id_skpd_croscutting'], $tahun_anggaran_sakip),
								ARRAY_A
							);
						} else {
							if (!empty($data_croscutting['id_skpd_parent'])) {
								$this_data_id_skpd = $data_croscutting['id_skpd_parent'];
							} else {
								$this_data_id_skpd = $data_croscutting['id_skpd_croscutting'];
							}

							$nama_perangkat = $wpdb->get_row(
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
						}
					} else {
						throw new Exception("Data tidak ditemukan!", 1);
					}
					if (empty($data_croscutting)) {
						throw new Exception("Data tidak ditemukan!", 1);
					}

					echo json_encode([
						'status' => true,
						'data_croscutting' => $data_croscutting,
						'nama_perangkat' => $nama_perangkat
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
									'status_croscutting' => 0,
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

					if ($data === false) {
						error_log("Error deleting croscutting: " . $wpdb->last_error);
					}

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
					if (!empty($input['idCroscutting'])) {
						if (!empty($_POST['tipe_pokin'])) {
							if (!empty($_POST['id_skpd'])) {
								if (!empty($input['verify_cc']) || $input['verify_cc'] == 0) {
									$status_verify = $input['verify_cc'] == 1 ? 1 : 2;

									if (!empty($input['levelPokinCroscutting']) && $input['verify_cc'] == 1) {
										$keterangan_croscutting = $input['keterangan_cc'];
									} else if (!empty($input['keterangan_cc_tolak']) && $input['verify_cc'] == 0) {
										$keterangan_tolak = $input['keterangan_cc_tolak'];
									} else {
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
					} else {
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

					if (empty($cek_pohon_kinerja) && $input['verify_cc'] == 1) {
						throw new Exception("Verifikasi gagal!", 1);
					}

					if (!empty($data_verify_croscutting)) {
						$opsi = array(
							'keterangan_croscutting' => trim($keterangan_croscutting),
							'status_croscutting' => $status_verify,
							'keterangan_tolak' => $keterangan_tolak,
							'updated_at' => current_time('mysql'),
							'parent_croscutting' => 0
						);
						if (!empty($cek_pohon_kinerja) && $input['verify_cc'] == 1) {
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
					} else {
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

	public function edit_verify_croscutting()
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
					$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
					if (!empty($_prefix_opd) && $_prefix_opd == "_opd") {
						$data_croscutting = $wpdb->get_row($wpdb->prepare("
								SELECT 
									cc.keterangan,
									cc.keterangan_croscutting,
									cc.parent_pohon_kinerja,
									cc.id_skpd_croscutting,
									cc.is_lembaga_lainnya,
									cc.parent_croscutting, 
									cc.status_croscutting,
									pk.id_skpd as id_skpd_parent 
								FROM esakip_croscutting_opd as cc 
								JOIN esakip_pohon_kinerja_opd as pk 
								ON cc.parent_pohon_kinerja = pk.id
								WHERE cc.id=%d 
									AND cc.active=%d
							", $_POST['id'], 1),  ARRAY_A);

						if (!empty($data_croscutting)) {
							$data_croscutting['nama_perangkat_parent'] = '';
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
									", $data_croscutting['id_skpd_parent'], $tahun_anggaran_sakip),
								ARRAY_A
							);
							if (!empty($nama_skpd)) {
								$data_croscutting['nama_perangkat_parent'] = $nama_skpd['nama_skpd'];
							}
						}
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

	public function detail_croscutting_by_id()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
									cc.keterangan,
									cc.keterangan_croscutting as keterangan_tujuan,
									cc.is_lembaga_lainnya,
									cc.id_skpd_croscutting as id_skpd_tujuan,
									cc.status_croscutting,
									cc.keterangan_tolak,
									pk.id_skpd as id_skpd_parent,
									pk.label as label_parent,
                                    pkt.label as label_parent_tujuan
								FROM esakip_croscutting_opd as cc
								LEFT JOIN esakip_pohon_kinerja_opd as pk ON cc.parent_pohon_kinerja = pk.id
                                LEFT JOIN esakip_pohon_kinerja_opd as pkt ON cc.parent_croscutting = pkt.id
								WHERE cc.id=%d 
									AND cc.active=%d
							", $_POST['id'], 1),  ARRAY_A);

						if (!empty($data_croscutting)) {
							$nama_skpd_pengusul = $wpdb->get_row(
								$wpdb->prepare("
									SELECT 
										nama_skpd
									FROM esakip_data_unit 
									WHERE active=1 
									AND is_skpd=1 
									AND id_skpd=%d
									AND tahun_anggaran=%d
									GROUP BY id_skpd
									ORDER BY kode_skpd ASC
								", $data_croscutting['id_skpd_parent'], $tahun_anggaran_sakip),
								ARRAY_A
							);

							$data_croscutting['nama_perangkat'] = strtoupper($nama_skpd_pengusul['nama_skpd']);

							$nama_perangkat_tujuan = '';
							if ($data_croscutting['is_lembaga_lainnya'] == 1) {
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
									", $data_croscutting['id_skpd_tujuan'], $tahun_anggaran_sakip),
									ARRAY_A
								);
								$nama_perangkat_tujuan = strtoupper($nama_lembaga['nama_lembaga']);
							} else {
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
									", $data_croscutting['id_skpd_tujuan'], $tahun_anggaran_sakip),
									ARRAY_A
								);
								$nama_perangkat_tujuan = strtoupper($nama_skpd['nama_skpd']);
							}
							$data_croscutting['nama_perangkat_tujuan'] = $nama_perangkat_tujuan;
						} else {
							throw new Exception("Data tidak ditemukan!", 1);
						}
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
				ORDER BY nomor_urut
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
				ORDER BY nomor_urut
			", $opsi['id'], $opsi['level'], $opsi['periode']), ARRAY_A);
		}
		if (!empty($pohon_kinerja)) {
			foreach ($pohon_kinerja as $level) {
				if (empty($data_ret[$level['id']])) {
					$data_ret[$level['id']] = [
						'id' => $level['id'],
						'label' => $level['label'],
						'level' => $level['level'],
						'indikator' => [],
						'data' => [],
						'croscutting' => [],
						'koneksi_pokin' => []
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
					ORDER BY nomor_urut
				", $level['id'], $level['level'], $opsi['periode']), ARRAY_A);
				if (!empty($indikator_pohon_kinerja_level)) {
					foreach ($indikator_pohon_kinerja_level as $indikator_level) {
						if (!empty($indikator_level['label_indikator_kinerja'])) {
							if (empty($data_ret[$level['id']]['indikator'][$indikator_level['id']])) {
								$data_ret[$level['id']]['indikator'][$indikator_level['id']] = [
									'id' => $indikator_level['id'],
									'parent' => $indikator_level['parent'],
									'label_indikator_kinerja' => $indikator_level['label_indikator_kinerja'],
									'level' => $indikator_level['level']
								];
							}
						}
					}
				}

				if ($opsi['tipe'] == 'opd') {
					// data croscutting
					// dapatkan croscutinh pengusulan
					$data_croscutting_level = $wpdb->get_results($wpdb->prepare("
						SELECT 
							cc.*,
							pk.id as id_parent,
							pk.level as level_parent,
							pk.label as label_parent
						FROM esakip_croscutting_opd as cc
						LEFT JOIN esakip_pohon_kinerja_opd as pk ON cc.parent_croscutting = pk.id
						WHERE cc.parent_pohon_kinerja=%d 
							AND cc.active=1 
							AND cc.status_croscutting=1
						ORDER BY cc.id
					", $level['id']), ARRAY_A);

					//  dapatkan croscutting yang diusulkan
					$data_croscutting_level_pengusul = $wpdb->get_results($wpdb->prepare("
						SELECT 
							cc.*,
							pk.id_skpd as id_skpd_parent,
							pk.id as id_parent,
							pk.level as level_parent,
							pk.label as label_parent
						FROM esakip_croscutting_opd as cc
						JOIN esakip_pohon_kinerja_opd as pk ON cc.parent_pohon_kinerja = pk.id
						WHERE cc.id_skpd_croscutting=%d
							AND cc.status_croscutting=1 
							AND cc.active=1
							AND cc.parent_croscutting=%d
					", $opsi['id_skpd'], $level['id']),  ARRAY_A);

					if (!empty($data_croscutting_level) && !empty($data_croscutting_level_pengusul)) {
						$data_croscutting_level = array_merge($data_croscutting_level, $data_croscutting_level_pengusul);
					} else if (empty($data_croscutting_level) && !empty($data_croscutting_level_pengusul)) {
						$data_croscutting_level = $data_croscutting_level_pengusul;
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
								if (!empty($nama_lembaga)) {
									$nama_perangkat = $nama_lembaga['nama_lembaga'];
								}
							} else {
								if (!empty($croscutting_level['id_skpd_parent'])) {
									$this_data_id_skpd = $croscutting_level['id_skpd_parent'];
								} else {
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
								if (!empty($croscutting_level['id_skpd_parent'])) {
									$croscutting_opd_lain = 1;
									$id_skpd_view_pokin = $croscutting_level['id_skpd_parent'];
								} else {
									$croscutting_opd_lain = 0;
									$id_skpd_view_pokin = $croscutting_level['id_skpd_croscutting'];
								}

								$data_parent_tujuan = array();
								$id_level_1_parent = 0;
								if ($croscutting_level['status_croscutting'] == 1) {
									// untuk mendapatkan id parent level 1 suatu opd
									$data_parent_tujuan = array('data' => $this->get_parent_1(array(
										'id' => $croscutting_level['id'],
										'level' => $croscutting_level['level_parent'],
										'periode' => $opsi['periode'],
										'tipe' => $opsi['tipe'],
										'id_parent' => $croscutting_level['id_parent']
									)));
								}

								if (!empty($data_parent_tujuan)) {
									$id_level_1_parent = $data_parent_tujuan['data'];
								}

								if (empty($data_ret[$level['id']]['croscutting'][$key_croscutting_level])) {
									$data_ret[$level['id']]['croscutting'][$key_croscutting_level] = [
										'id' => $croscutting_level['id'],
										'parent_pohon_kinerja' => $croscutting_level['parent_pohon_kinerja'],
										'parent_croscutting' => $croscutting_level['parent_croscutting'],
										'keterangan' => $croscutting_level['keterangan'],
										'is_lembaga_lainnya' => $croscutting_level['is_lembaga_lainnya'],
										'label_parent' => $croscutting_level['label_parent'],
										'croscutting_opd_lain' => $croscutting_opd_lain,
										'nama_skpd' => $nama_perangkat,
										'id_skpd_view_pokin' => $id_skpd_view_pokin,
										'id_level_1_parent' => $id_level_1_parent
									];
								}
							}
						}
					}

					// get data koneksi pokin pemda dan opd
					$data_koneksi_pokin = $wpdb->get_results($wpdb->prepare("
						SELECT 
							koneksi.* ,
							pk.id as id_parent,
							pk.level as level_parent,
							pk.label as label_parent
						FROM esakip_koneksi_pokin_pemda_opd koneksi
						LEFT JOIN esakip_pohon_kinerja as pk ON koneksi.parent_pohon_kinerja = pk.id
						WHERE koneksi.parent_pohon_kinerja_koneksi=%d 
							AND koneksi.active=1 
							AND koneksi.status_koneksi=1
						ORDER BY koneksi.id
					", $level['id']), ARRAY_A);

					if (!empty($data_koneksi_pokin)) {
						$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
						foreach ($data_koneksi_pokin as $key_koneksi => $koneksi_pokin) {

							$data_parent_tujuan = array();
							$id_level_1_parent = 0;
							if ($koneksi_pokin['status_koneksi'] == 1 && $koneksi_pokin['tipe'] == 1) {
								$data_parent_tujuan = $this->get_parent_1_koneksi_pokin_pemda_opd(array(
									'id' => $koneksi_pokin['id'],
									'level' => $koneksi_pokin['level_parent'],
									'periode' => $opsi['periode'],
									'id_parent' => $koneksi_pokin['id_parent']
								));
							}

							if (!empty($data_parent_tujuan)) {
								$id_level_1_parent = $data_parent_tujuan['id'];
							}

							if (empty($data_ret[$level['id']]['koneksi_pokin'][$key_koneksi])) {
								$data_ret[$level['id']]['koneksi_pokin'][$key_koneksi] = [
									'id' => $koneksi_pokin['id'],
									'parent_pohon_kinerja' => $koneksi_pokin['parent_pohon_kinerja'],
									'label_parent' => $koneksi_pokin['label_parent'],
									'id_level_1_parent' => $id_level_1_parent
								];
							}
						}
					}
				} else {
					// get data koneksi pokin pemda dan opd di view pokin pemda
					$data_koneksi_pokin = $wpdb->get_results($wpdb->prepare("
						SELECT 
							koneksi.* ,
							pk.id as id_parent,
							pk.level as level_parent,
							pk.label as label_parent
						FROM esakip_koneksi_pokin_pemda_opd koneksi
						LEFT JOIN esakip_pohon_kinerja_opd as pk ON koneksi.parent_pohon_kinerja_koneksi = pk.id
						WHERE koneksi.parent_pohon_kinerja=%d 
							AND koneksi.active=1 
							AND koneksi.status_koneksi=1
						ORDER BY koneksi.id
					", $level['id']), ARRAY_A);

					if (!empty($data_koneksi_pokin)) {
						$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
						foreach ($data_koneksi_pokin as $key_koneksi => $koneksi_pokin) {

							// OPD
							if ($koneksi_pokin['tipe'] == 1) {
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
									", $koneksi_pokin['id_skpd_koneksi'], $tahun_anggaran_sakip),
									ARRAY_A
								);
								$nama_perangkat = $nama_skpd['nama_skpd'] ?? 'Nama Perangkat Tidak Ditemukan';
							} else {
								// Lembaga Lainnya
								if ($koneksi_pokin['tipe'] == 2) {
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
										", $koneksi_pokin['id_skpd_koneksi'], $tahun_anggaran_sakip),
										ARRAY_A
									);
									if (!empty($nama_lembaga)) {
										$nama_perangkat = $nama_lembaga['nama_lembaga'];
									}
									// SUB SKPD
								} else if ($koneksi_pokin['tipe'] == 3) {
									$nama_skpd = $wpdb->get_row(
										$wpdb->prepare("
											SELECT 
												nama_skpd,
												id_skpd,
												tahun_anggaran
											FROM esakip_data_unit 
											WHERE active=1 
											  AND is_skpd=0 
											  AND id_skpd=%d
											  AND tahun_anggaran=%d
											GROUP BY id_skpd
											ORDER BY kode_skpd ASC
										", $koneksi_pokin['id_skpd_koneksi'], $tahun_anggaran_sakip),
										ARRAY_A
									);
									$nama_perangkat = $nama_skpd['nama_skpd'] ?? 'Nama Perangkat Tidak Ditemukan';
								} else if ($koneksi_pokin['tipe'] == 4) {
									$nama_perangkat = $koneksi_pokin['nama_desa'];
								}

								$koneksi_pokin['label_parent'] = $koneksi_pokin['keterangan_koneksi'];
								if (empty($nama_perangkat)) {
									$nama_perangkat = 'Tipe Koneksi tidak diketahui, Nama Perangkat Tidak Ditemukan';
									$koneksi_pokin['label_parent'] = 'Tipe Koneksi tidak diketahui, Label Tidak Ditemukan';
								}
							}

							$data_parent_tujuan = array();
							$id_level_1_parent = 0;
							if ($koneksi_pokin['status_koneksi'] == 1 && $koneksi_pokin['tipe'] == 1) {
								$data_parent_tujuan = $this->get_parent_1_koneksi_pokin_pemda_opd(array(
									'id' => $koneksi_pokin['id'],
									'level' => $koneksi_pokin['level_parent'],
									'periode' => $opsi['periode'],
									'id_parent' => $koneksi_pokin['id_parent'],
									'tipe' => 'opd',
									'id_skpd' => $opsi['id_skpd']
								));
							}

							if (!empty($data_parent_tujuan)) {
								$id_level_1_parent = $data_parent_tujuan['id'];
							}

							if (empty($data_ret[$level['id']]['koneksi_pokin'][$key_koneksi])) {
								$data_ret[$level['id']]['koneksi_pokin'][$key_koneksi] = [
									'id' => $koneksi_pokin['id'],
									'id_parent' => $koneksi_pokin['id_parent'],
									'level_parent' => $koneksi_pokin['level_parent'],
									'parent_pohon_kinerja' => $koneksi_pokin['parent_pohon_kinerja'],
									'label_parent' => $koneksi_pokin['label_parent'],
									'id_level_1_parent' => $id_level_1_parent,
									'nama_skpd' => $nama_perangkat,
									'tipe' => $koneksi_pokin['tipe'],
									'id_skpd_view_pokin' => $koneksi_pokin['id_skpd_koneksi']
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
					$data_ret[$level['id']]['data'] = $this->get_pokin($opsi);
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

	function get_parent_croscutting($opsi)
	{
		global $wpdb;
		$data_ret = array();
		$table = 'esakip_pohon_kinerja';
		$table_croscutting = 'esakip_croscutting';
		$where_skpd = '';
		$parent_level_1 = 0;
		if ($opsi['tipe'] == 'opd') {
			$table = 'esakip_pohon_kinerja_opd';
			$table_croscutting = 'esakip_croscutting_opd';
			if (empty($opsi['id_skpd'])) {
				return $data_ret;
			}
			$where_skpd = $wpdb->prepare('AND id_skpd=%d', $opsi['id_skpd']);
		}

		// data croscutting
		$data_croscutting = $wpdb->get_row($wpdb->prepare("
			SELECT 
				* 
			FROM $table_croscutting
			WHERE id=%d 
				AND active=1 
			ORDER BY id
		", $opsi['id']), ARRAY_A);

		// $data_ret['cek'] = $data_croscutting;
		$no = 1;
		if (!empty($data_croscutting)) {
			$data_pokin = $wpdb->get_row($wpdb->prepare("
				SELECT 
					* 
				FROM $table
				WHERE id=%d 
					AND active=1 
					AND label_indikator_kinerja IS NULL
					AND level=%d
					AND id_jadwal=%d
				ORDER BY id
			", $opsi['id_parent'], $opsi['level'], $opsi['periode']), ARRAY_A);

			if (!empty($data_pokin)) {
				if (empty($data_ret[trim($data_pokin['level'])])) {
					$data_ret[trim($data_pokin['level'])] = [
						'id' => $data_pokin['id'],
						'label' => $data_pokin['label'],
						'level_pokin_parent' => $data_pokin['level'],
						'data' => []
					];
				}
				if ($data_pokin['level'] == 1) {
					$parent_level_1 = $data_pokin['id'];
				}

				if ($data_pokin['parent'] != 0) {
					$opsi['id_parent'] = $data_pokin['parent'];
					$opsi['level'] = $data_pokin['level'] - 1;
					$data_ret[trim($data_pokin['level'])]['data'] = $this->get_parent_croscutting($opsi);
				}
			}
		}

		return $data_ret;
	}

	public function get_parent_1($opsi)
	{

		$data_ret = $this->get_parent_croscutting($opsi);

		$id = 0;
		foreach ($data_ret as $v) {
			if (!empty($v['data'])) {
				foreach ($v['data'] as $vv) {
					if (!empty($vv['data'])) {
						foreach ($vv['data'] as $key => $vvv) {
							if (!empty($vvv['data'])) {
								foreach ($vvv['data'] as $key => $vvvv) {
									if (!empty($vvvv['data'])) {
										foreach ($vvvv['data'] as $key => $vvvvv) {
											if (!empty($vvvvv['data'])) {
												foreach ($vvvvv['data'] as $key => $vvvvvv) {
													if (!empty($vvvvvv['data'])) {
														foreach ($vvvvvv['data'] as $key => $vvvvvvv) {
															# code...
														}
													} else {
														$id = $vvvvvv['id'];
													}
												}
											} else {
												$id = $vvvvv['id'];
											}
										}
									} else {
										$id = $vvvv['id'];
									}
								}
							} else {
								$id = $vvv['id'];
							}
						}
					} else {
						$id = $vv['id'];
					}
				}
			} else {
				$id = $v['id'];
			}
		}
		return $id;
	}

	public function get_table_skpd_pengisian_rencana_aksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array(),
			'list_skpd' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
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
					WHERE active=1 
					  AND tahun_anggaran=%d
					  AND is_skpd=1 
					ORDER BY kode_skpd ASC
					", $tahun_anggaran_sakip),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$total_level_1 = 0;
					$total_level_2 = 0;
					$total_level_3 = 0;
					$total_level_4 = 0;
					$total_pagu = 0;
					$list_skpd = array();
					$total_all_pagu_rincian = 0;
					$total_all_realisasi = 0;

					foreach ($unit as $kk => $vv) {
						$list_skpd[$kk] = array(
							'id_skpd' => $vv['id_skpd'],
							'nama_skpd' => $vv['nama_skpd']
						);

						$detail_pengisian_rencana_aksi = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Pengisian Rencana Aksi ' . $tahun_anggaran,
							'content' => '[detail_pengisian_rencana_aksi tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'post_status' => 'private'
						)); //dokumen_detail_rencana_aksi

						$jumlah_kegiatan_utama = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									COUNT(id)
								FROM esakip_data_rencana_aksi_opd
								WHERE id_skpd = %d
								  AND tahun_anggaran = %d
								  AND active = 1
								  AND level=1
							", $vv['id_skpd'], $tahun_anggaran)
						);

						$jumlah_rencana_aksi = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									COUNT(id)
								FROM esakip_data_rencana_aksi_opd
								WHERE id_skpd = %d
								  AND tahun_anggaran = %d
								  AND active = 1
								  AND level=2
							", $vv['id_skpd'], $tahun_anggaran)
						);

						$jumlah_uraian_kegiatan_rencana_aksi = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									COUNT(id)
								FROM esakip_data_rencana_aksi_opd
								WHERE id_skpd = %d
								  AND tahun_anggaran = %d
								  AND active = 1
								  AND level=3
							", $vv['id_skpd'], $tahun_anggaran)
						);

						$jumlah_uraian_teknis_kegiatan = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									COUNT(id)
								FROM esakip_data_rencana_aksi_opd
								WHERE id_skpd = %d
								  AND tahun_anggaran = %d
								  AND active = 1
								  AND level=4
							", $vv['id_skpd'], $tahun_anggaran)
						);
						$get_pagu = 0;
						if (get_option('_crb_set_pagu_renaksi') == 1) {
							$get_pagu = 0;
						} else {
							$get_pagu = $wpdb->get_var($wpdb->prepare("
						        SELECT 
						            IFNULL(SUM(i.rencana_pagu), 0)
						        FROM esakip_data_rencana_aksi_indikator_opd i
						        INNER JOIN esakip_data_rencana_aksi_opd r on r.id=i.id_renaksi
						        	AND r.level=1
						        	AND r.active=i.active
						        	AND r.id_skpd=i.id_skpd
						        	AND r.tahun_anggaran=i.tahun_anggaran
						        WHERE i.id_skpd = %d 
						            AND i.tahun_anggaran = %d 
						            AND i.active = 1
						    ", $vv['id_skpd'], $tahun_anggaran));
						}
						$total_pagu_rincian = 0;
						$total_realisasi = 0;
						$get_data_tagging = $wpdb->get_results(
							$wpdb->prepare("
								SELECT 
									* 
								FROM esakip_tagging_rincian_belanja 
								WHERE active = 1 
								  AND id_skpd = %d
							", $vv['id_skpd']),
							ARRAY_A
						);
						foreach ($get_data_tagging as $data_tagging) {
							$total_pagu_rincian += $data_tagging['volume'] * $data_tagging['harga_satuan'];
							$total_realisasi += $data_tagging['realisasi'];
						}
						$tbody .= "<tr>";
						$tbody .= "<td style='text-transform: uppercase;'><a href='" . $detail_pengisian_rencana_aksi['url'] . "&id_skpd=" . $vv['id_skpd'] . "' target='_blank'>" . $vv['kode_skpd'] . " " . $vv['nama_skpd'] . "</a></td>";
						$tbody .= "<td class='text-center' style='text-transform: uppercase;'>" . $jumlah_kegiatan_utama . "</td>";
						$tbody .= "<td class='text-center' style='text-transform: uppercase;'>" . $jumlah_rencana_aksi . "</td>";
						$tbody .= "<td class='text-center' style='text-transform: uppercase;'>" . $jumlah_uraian_kegiatan_rencana_aksi . "</td>";
						$tbody .= "<td class='text-center' style='text-transform: uppercase;'>" . $jumlah_uraian_teknis_kegiatan . "</td>";
						$tbody .= "<td class='text-right' style='text-transform: uppercase;'>" . number_format((float)$get_pagu, 0, ",", ".") . "</td>";
						$tbody .= "<td class='text-right' style='text-transform: uppercase;'>" . number_format((float)$total_pagu_rincian, 0, ",", ".") . "</td>";
						$tbody .= "<td class='text-right' style='text-transform: uppercase;'>" . number_format((float)$total_realisasi, 0, ",", ".") . "</td>";
						$tbody .= "</tr>";

						$total_level_1 += $jumlah_kegiatan_utama;
						$total_level_2 += $jumlah_rencana_aksi;
						$total_level_3 += $jumlah_uraian_kegiatan_rencana_aksi;
						$total_level_4 += $jumlah_uraian_teknis_kegiatan;
						$total_pagu += $get_pagu;
						$total_all_pagu_rincian += $total_pagu_rincian;
						$total_all_realisasi += $total_realisasi;
					}
					$ret['data'] = $tbody;
					$ret['total_level_1'] = $total_level_1;
					$ret['total_level_2'] = $total_level_2;
					$ret['total_level_3'] = $total_level_3;
					$ret['total_level_4'] = $total_level_4;
					$ret['total_pagu'] = number_format((float)$total_pagu, 0, ",", ".");
					$ret['total_all_pagu_rincian'] = number_format((float)$total_all_pagu_rincian, 0, ",", ".");
					$ret['total_all_realisasi'] = number_format((float)$total_all_realisasi, 0, ",", ".");
					$ret['list_skpd'] = $list_skpd;
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

	public function ganti_kata($text, $copy_rubah_kata, $kata_baru)
	{
		$text = trim(preg_replace('/\s+/', ' ', $text));
		$copy_rubah_kata = explode('],[', $copy_rubah_kata);
		foreach ($copy_rubah_kata as $copy) {
			$copy = str_replace('[', '', $copy);
			$copy = str_replace(']', '', $copy);
			$kata = explode('|', $copy);
			if (!empty($kata[1]) && !empty($kata_baru[$kata[1]])) {
				$text = str_replace($kata[0], $kata_baru[$kata[1]], $text);
			}
		}
		return $text;
	}

	public function simpan_copy_data_pokin()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil copy data POKIN!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] == 'error' && empty($_POST['parent'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID parent tidak boleh kosong!';
				} else if ($ret['status'] == 'error' && empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID POKIN tidak boleh kosong!';
				} else if ($ret['status'] == 'error' && empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID jadwal tidak boleh kosong!';
				} else {
					$copy_rubah_kata = '';
					if (!empty($_POST['copy_rubah_kata'])) {
						$copy_rubah_kata = $_POST['copy_rubah_kata'];
					}
					$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
					$data_perangkat = $wpdb->get_results($wpdb->prepare("
						SELECT 
							id_skpd,
							nama_skpd
						FROM esakip_data_unit 
						WHERE active=1 
							AND is_skpd=1
						AND tahun_anggaran=%d
						GROUP BY id_skpd
						ORDER BY kode_skpd ASC
					", $tahun_anggaran_sakip), ARRAY_A);
					$ret['level_2'] = array();
					$ret['level_3'] = array();
					$ret['level_4'] = array();
					$ret['level_5'] = array();

					$level_2_db = $wpdb->get_results($wpdb->prepare("
						SELECT 
							a.id,
							a.label,
							a.parent,
							a.id_skpd,
							b.id AS id_indikator,
							b.label_indikator_kinerja
						FROM esakip_pohon_kinerja_opd a
						LEFT JOIN esakip_pohon_kinerja_opd b ON a.id=b.parent AND a.level=b.level 
						WHERE 
							a.id_jadwal=%d AND 
							a.parent=%d AND 
							a.id=%d AND 
							a.level=2 AND 
							a.active=1
						ORDER BY a.id
					", $_POST['id_jadwal'], $_POST['parent'], $_POST['id']), ARRAY_A);
					$ret['level_2'][$_POST['id']] = $level_2_db;

					$id_parent_all = array();
					foreach ($level_2_db as $level_2) {
						if (empty($id_parent_all[$opd['id_skpd']])) {
							$id_parent_all[$opd['id_skpd']] = array('data' => array());
						}

						// copy data level 2
						foreach ($data_perangkat as $opd) {
							// insert label pokin
							if (empty($id_parent_all[$opd['id_skpd']]['data'][$level_2['id']])) {
								if ($level_2['id_skpd'] == $opd['id_skpd']) {
									$level_1_tujuan = $level_2['parent'];
								} else {
									$level_1_tujuan_db = $wpdb->get_row($wpdb->prepare("
										SELECT 
											a.id
										FROM esakip_pohon_kinerja_opd a
										WHERE 
											a.id_jadwal=%d AND 
											a.id_skpd=%d AND 
											a.parent=0 AND 
											a.level=1 AND 
											a.active=1
										ORDER BY a.id
										LIMIT 1
									", $_POST['id_jadwal'], $opd['id_skpd']), ARRAY_A);
									$level_1_tujuan = $level_1_tujuan_db['id'];
								}
								$new_text = $this->ganti_kata($level_2['label'], $copy_rubah_kata, array('nama_opd' => $opd['nama_skpd']));
								$data = array(
									'label' => $new_text,
									'parent' => $level_1_tujuan,
									'id_skpd' => $opd['id_skpd'],
									'level' => 2,
									'id_jadwal' => $_POST['id_jadwal'],
									'active' => 1,
									'id_asal_copy' => $level_2['id'],
									'update_at' => current_time('mysql')
								);
								if ($level_2['id_skpd'] == $opd['id_skpd']) {
									$cek_id = $level_2['id'];
								} else {
									$cek_id = $wpdb->get_var($wpdb->prepare("
										SELECT 
											a.id
										FROM esakip_pohon_kinerja_opd a
										WHERE 
											a.id_jadwal=%d AND 
											a.id_skpd=%d AND 
											a.level=2 AND 
											a.parent=%d AND 
											a.id_asal_copy=%d
									", $_POST['id_jadwal'], $opd['id_skpd'], $level_1_tujuan_db['id'], $level_2['id']));
								}
								if (empty($cek_id)) {
									$data['created_at'] = current_time('mysql');
									$wpdb->insert('esakip_pohon_kinerja_opd', $data);
									$cek_id = $wpdb->insert_id;
								} else {
									$wpdb->update('esakip_pohon_kinerja_opd', $data, array('id' => $cek_id));
								}
								$id_parent_all[$opd['id_skpd']]['data'][$level_2['id']] = array(
									'id_parent_tujuan' => $cek_id,
									'data' => array()
								);
							}

							// insert indikator
							$id_parent = $id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['id_parent_tujuan'];
							$new_text = $this->ganti_kata($level_2['label_indikator_kinerja'], $copy_rubah_kata, array('nama_opd' => $opd['nama_skpd']));
							$data = array(
								'label_indikator_kinerja' => $new_text,
								'parent' => $id_parent,
								'id_skpd' => $opd['id_skpd'],
								'level' => 2,
								'id_jadwal' => $_POST['id_jadwal'],
								'active' => 1,
								'id_asal_copy' => $level_2['id_indikator'],
								'update_at' => current_time('mysql')
							);
							if ($level_2['id_skpd'] == $opd['id_skpd']) {
								$cek_id = $level_2['id_indikator'];
							} else {
								$cek_id = $wpdb->get_var($wpdb->prepare("
									SELECT 
										a.id
									FROM esakip_pohon_kinerja_opd a
									WHERE 
										a.id_jadwal=%d AND 
										a.id_skpd=%d AND 
										a.level=2 AND 
										a.parent=%d AND 
										a.id_asal_copy=%d
								", $_POST['id_jadwal'], $opd['id_skpd'], $id_parent, $level_2['id_indikator']));
							}
							if (empty($cek_id)) {
								$data['created_at'] = current_time('mysql');
								$wpdb->insert('esakip_pohon_kinerja_opd', $data);
							} else {
								$wpdb->update('esakip_pohon_kinerja_opd', $data, array('id' => $cek_id));
							}
						}

						$level_3_db = $wpdb->get_results($wpdb->prepare("
							SELECT 
								a.id,
								a.label,
								a.parent,
								a.id_skpd,
								b.id AS id_indikator,
								b.label_indikator_kinerja
							FROM esakip_pohon_kinerja_opd a
							LEFT JOIN esakip_pohon_kinerja_opd b ON a.id=b.parent AND a.level=b.level 
							WHERE 
								a.id_jadwal=%d AND 
								a.parent=%d AND 
								a.level=3 AND 
								a.active=1
							ORDER BY a.id
						", $_POST['id_jadwal'], $level_2['id']), ARRAY_A);
						$ret['level_3'][$level_2['id']] = $level_3_db;

						foreach ($level_3_db as $level_3) {
							// copy data level 3
							foreach ($data_perangkat as $opd) {
								// insert label pokin
								if (empty($id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['data'][$level_3['id']])) {
									$level_2_tujuan = $id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['id_parent_tujuan'];
									$new_text = $this->ganti_kata($level_3['label'], $copy_rubah_kata, array('nama_opd' => $opd['nama_skpd']));
									$data = array(
										'label' => $new_text,
										'parent' => $level_2_tujuan,
										'id_skpd' => $opd['id_skpd'],
										'level' => 3,
										'id_jadwal' => $_POST['id_jadwal'],
										'active' => 1,
										'id_asal_copy' => $level_3['id'],
										'update_at' => current_time('mysql')
									);
									if ($level_3['id_skpd'] == $opd['id_skpd']) {
										$cek_id = $level_3['id'];
									} else {
										$cek_id = $wpdb->get_var($wpdb->prepare("
											SELECT 
												a.id
											FROM esakip_pohon_kinerja_opd a
											WHERE 
												a.id_jadwal=%d AND 
												a.id_skpd=%d AND 
												a.level=3 AND 
												a.parent=%d AND 
												a.id_asal_copy=%d
										", $_POST['id_jadwal'], $opd['id_skpd'], $level_2_tujuan, $level_3['id']));
									}
									if (empty($cek_id)) {
										$data['created_at'] = current_time('mysql');
										$wpdb->insert('esakip_pohon_kinerja_opd', $data);
										$cek_id = $wpdb->insert_id;
									} else {
										$wpdb->update('esakip_pohon_kinerja_opd', $data, array('id' => $cek_id));
									}
									$id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['data'][$level_3['id']] = array(
										'id_parent_tujuan' => $cek_id,
										'data' => array()
									);
								}

								// insert indikator
								$id_parent = $id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['data'][$level_3['id']]['id_parent_tujuan'];
								$new_text = $this->ganti_kata($level_3['label_indikator_kinerja'], $copy_rubah_kata, array('nama_opd' => $opd['nama_skpd']));
								$data = array(
									'label_indikator_kinerja' => $new_text,
									'parent' => $id_parent,
									'id_skpd' => $opd['id_skpd'],
									'level' => 3,
									'id_jadwal' => $_POST['id_jadwal'],
									'active' => 1,
									'id_asal_copy' => $level_3['id_indikator'],
									'update_at' => current_time('mysql')
								);
								if ($level_3['id_skpd'] == $opd['id_skpd']) {
									$cek_id = $level_3['id_indikator'];
								} else {
									$cek_id = $wpdb->get_var($wpdb->prepare("
										SELECT 
											a.id
										FROM esakip_pohon_kinerja_opd a
										WHERE 
											a.id_jadwal=%d AND 
											a.id_skpd=%d AND 
											a.level=3 AND 
											a.parent=%d AND 
											a.id_asal_copy=%d
									", $_POST['id_jadwal'], $opd['id_skpd'], $id_parent, $level_3['id_indikator']));
								}
								if (empty($cek_id)) {
									$data['created_at'] = current_time('mysql');
									$wpdb->insert('esakip_pohon_kinerja_opd', $data);
								} else {
									$wpdb->update('esakip_pohon_kinerja_opd', $data, array('id' => $cek_id));
								}
							}

							$level_4_db = $wpdb->get_results($wpdb->prepare("
								SELECT 
									a.id,
									a.label,
									a.parent,
									a.id_skpd,
									b.id AS id_indikator,
									b.label_indikator_kinerja
								FROM esakip_pohon_kinerja_opd a
								LEFT JOIN esakip_pohon_kinerja_opd b ON a.id=b.parent AND a.level=b.level 
								WHERE 
									a.id_jadwal=%d AND 
									a.parent=%d AND 
									a.level=4 AND 
									a.active=1
								ORDER BY a.id
							", $_POST['id_jadwal'], $level_3['id']), ARRAY_A);
							$ret['level_4'][$level_3['id']] = $level_4_db;

							foreach ($level_4_db as $level_4) {
								// copy data level 4
								foreach ($data_perangkat as $opd) {
									// insert label pokin
									if (empty($id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']])) {
										$level_3_tujuan = $id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['data'][$level_3['id']]['id_parent_tujuan'];
										$new_text = $this->ganti_kata($level_4['label'], $copy_rubah_kata, array('nama_opd' => $opd['nama_skpd']));
										$data = array(
											'label' => $new_text,
											'parent' => $level_3_tujuan,
											'id_skpd' => $opd['id_skpd'],
											'level' => 4,
											'id_jadwal' => $_POST['id_jadwal'],
											'active' => 1,
											'id_asal_copy' => $level_4['id'],
											'update_at' => current_time('mysql')
										);
										if ($level_4['id_skpd'] == $opd['id_skpd']) {
											$cek_id = $level_4['id'];
										} else {
											$cek_id = $wpdb->get_var($wpdb->prepare("
												SELECT 
													a.id
												FROM esakip_pohon_kinerja_opd a
												WHERE 
													a.id_jadwal=%d AND 
													a.id_skpd=%d AND 
													a.level=4 AND 
													a.parent=%d AND 
													a.id_asal_copy=%d
											", $_POST['id_jadwal'], $opd['id_skpd'], $level_3_tujuan, $level_4['id']));
										}
										if (empty($cek_id)) {
											$data['created_at'] = current_time('mysql');
											$wpdb->insert('esakip_pohon_kinerja_opd', $data);
											$cek_id = $wpdb->insert_id;
										} else {
											$wpdb->update('esakip_pohon_kinerja_opd', $data, array('id' => $cek_id));
										}
										$id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']] = array(
											'id_parent_tujuan' => $cek_id,
											'data' => array()
										);
									}

									// insert indikator
									$id_parent = $id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['id_parent_tujuan'];
									$new_text = $this->ganti_kata($level_4['label_indikator_kinerja'], $copy_rubah_kata, array('nama_opd' => $opd['nama_skpd']));
									$data = array(
										'label_indikator_kinerja' => $new_text,
										'parent' => $id_parent,
										'id_skpd' => $opd['id_skpd'],
										'level' => 4,
										'id_jadwal' => $_POST['id_jadwal'],
										'active' => 1,
										'id_asal_copy' => $level_4['id_indikator'],
										'update_at' => current_time('mysql')
									);
									if ($level_4['id_skpd'] == $opd['id_skpd']) {
										$cek_id = $level_4['id_indikator'];
									} else {
										$cek_id = $wpdb->get_var($wpdb->prepare("
											SELECT 
												a.id
											FROM esakip_pohon_kinerja_opd a
											WHERE 
												a.id_jadwal=%d AND 
												a.id_skpd=%d AND 
												a.level=4 AND 
												a.parent=%d AND 
												a.id_asal_copy=%d
										", $_POST['id_jadwal'], $opd['id_skpd'], $id_parent, $level_4['id_indikator']));
									}
									if (empty($cek_id)) {
										$data['created_at'] = current_time('mysql');
										$wpdb->insert('esakip_pohon_kinerja_opd', $data);
									} else {
										$wpdb->update('esakip_pohon_kinerja_opd', $data, array('id' => $cek_id));
									}
								}

								$level_5_db = $wpdb->get_results($wpdb->prepare("
									SELECT 
										a.id,
										a.label,
										a.parent,
										a.id_skpd,
										b.id AS id_indikator,
										b.label_indikator_kinerja
									FROM esakip_pohon_kinerja_opd a
									LEFT JOIN esakip_pohon_kinerja_opd b ON a.id=b.parent AND a.level=b.level 
									WHERE 
										a.id_jadwal=%d AND 
										a.parent=%d AND 
										a.level=5 AND 
										a.active=1
									ORDER BY a.id
								", $_POST['id_jadwal'], $level_4['id']), ARRAY_A);
								$ret['level_5'][$level_4['id']] = $level_5_db;

								foreach ($level_5_db as $level_5) {
									// copy data level 5
									foreach ($data_perangkat as $opd) {
										// insert label pokin
										if (empty($id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']])) {
											$level_4_tujuan = $id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['id_parent_tujuan'];
											$new_text = $this->ganti_kata($level_5['label'], $copy_rubah_kata, array('nama_opd' => $opd['nama_skpd']));
											$data = array(
												'label' => $new_text,
												'parent' => $level_4_tujuan,
												'id_skpd' => $opd['id_skpd'],
												'level' => 5,
												'id_jadwal' => $_POST['id_jadwal'],
												'active' => 1,
												'id_asal_copy' => $level_5['id'],
												'update_at' => current_time('mysql')
											);
											if ($level_5['id_skpd'] == $opd['id_skpd']) {
												$cek_id = $level_5['id'];
											} else {
												$cek_id = $wpdb->get_var($wpdb->prepare("
													SELECT 
														a.id
													FROM esakip_pohon_kinerja_opd a
													WHERE 
														a.id_jadwal=%d AND 
														a.id_skpd=%d AND 
														a.level=5 AND 
														a.parent=%d AND 
														a.id_asal_copy=%d
												", $_POST['id_jadwal'], $opd['id_skpd'], $level_4_tujuan, $level_5['id']));
											}
											if (empty($cek_id)) {
												$data['created_at'] = current_time('mysql');
												$wpdb->insert('esakip_pohon_kinerja_opd', $data);
												$cek_id = $wpdb->insert_id;
											} else {
												$wpdb->update('esakip_pohon_kinerja_opd', $data, array('id' => $cek_id));
											}
											$id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']] = array(
												'id_parent_tujuan' => $cek_id,
												'data' => array()
											);
										}

										// insert indikator
										$id_parent = $id_parent_all[$opd['id_skpd']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']]['id_parent_tujuan'];
										$new_text = $this->ganti_kata($level_5['label_indikator_kinerja'], $copy_rubah_kata, array('nama_opd' => $opd['nama_skpd']));
										$data = array(
											'label_indikator_kinerja' => $new_text,
											'parent' => $id_parent,
											'id_skpd' => $opd['id_skpd'],
											'level' => 5,
											'id_jadwal' => $_POST['id_jadwal'],
											'active' => 1,
											'id_asal_copy' => $level_5['id_indikator'],
											'update_at' => current_time('mysql')
										);
										if ($level_5['id_skpd'] == $opd['id_skpd']) {
											$cek_id = $level_5['id_indikator'];
										} else {
											$cek_id = $wpdb->get_var($wpdb->prepare("
												SELECT 
													a.id
												FROM esakip_pohon_kinerja_opd a
												WHERE 
													a.id_jadwal=%d AND 
													a.id_skpd=%d AND 
													a.level=5 AND 
													a.parent=%d AND 
													a.id_asal_copy=%d
											", $_POST['id_jadwal'], $opd['id_skpd'], $id_parent, $level_5['id_indikator']));
										}
										if (empty($cek_id)) {
											$data['created_at'] = current_time('mysql');
											$wpdb->insert('esakip_pohon_kinerja_opd', $data);
										} else {
											$wpdb->update('esakip_pohon_kinerja_opd', $data, array('id' => $cek_id));
										}
									}
								}
							}
						}
					}

					$ret['id_parent_all'] = $id_parent_all;
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

	public function get_tujuan_sasaran_cascading($return_text)
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					if (!empty($_POST['id_skpd'])) {
						$id_skpd = $_POST['id_skpd'];
					} else {
						throw new Exception("Id Skpd Kosong!", 1);
					}
					if (!empty($_POST['jenis'])) {
						$jenis = $_POST['jenis'];
					} else {
						throw new Exception("Jenis Data Kosong!", 1);
					}
					$parent_cascading = '';
					$tahun_anggaran = '';
					if (
						$jenis != 'sasaran'
						&& $jenis != 'program_renstra'
						&& $jenis != 'tujuan'
						&& $jenis != 'kegiatan_renstra'
						&& $jenis != 'sub_giat_renstra'
					) {
						if (!empty($_POST['parent_cascading'])) {
							$parent_cascading = $_POST['parent_cascading'];
						} else {
							throw new Exception("Parent Cascading Data Kosong!", 1);
						}
						if (!empty($_POST['tahun_anggaran'])) {
							$tahun_anggaran = $_POST['tahun_anggaran'];
						} else {
							throw new Exception("Tahun Anggaran Kosong!", 1);
						}
					}

					if ($jenis == 'sasaran' && empty($_POST['id_jadwal_wpsipd'])) {
						throw new Exception("Id Jadwal WpSipd Kosong!", 1);
					} else {
						$id_jadwal_wpsipd = $_POST['id_jadwal_wpsipd'];
						$id_jadwal_rpjmd_rhk = $_POST['id_jadwal_rpjmd_rhk'];
					}

					$api_params = array(
						'action' 		   => 'get_cascading_renstra',
						'api_key'		   => get_option('_crb_apikey_wpsipd'),
						'tahun_anggaran'   => $tahun_anggaran,
						'id_skpd' 		   => $id_skpd,
						'jenis' 		   => $jenis,
						'parent_cascading' => $parent_cascading
					);

					if (
						$jenis == 'sasaran'
						|| $jenis == 'tujuan'
						|| $jenis == 'program_renstra'
						|| $jenis == 'kegiatan_renstra'
						|| $jenis == 'sub_giat_renstra'
						|| $jenis == 'program'
						|| $jenis == 'kegiatan'
						|| $jenis == 'sub_kegiatan'
					) {
						$api_params['id_jadwal'] = $id_jadwal_wpsipd;
						$api_params['id_jadwal_rhk'] = $id_jadwal_rpjmd_rhk;
					}

					$response_asli = wp_remote_post(
						get_option('_crb_url_server_sakip'),
						array(
							'timeout' 	=> 1000,
							'sslverify' => false,
							'body' 		=> $api_params
						)
					);

					$response = wp_remote_retrieve_body($response_asli);
					$response = json_decode($response);
					$data = $response->data;
					$data_transformasi_cascading = $response->data_transformasi_cascading;

					$return = array(
						'status' => 'success',
						'jenis' => $_POST['jenis'],
						'data' => $data,
						'param' => $api_params,
						'response' => $response_asli
					);
					if (!empty($return_text)) {
						return $return;
					} else {
						die(json_encode($return));
					}
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

	public function get_table_skpd_input_iku()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_jadwal_wpsipd'])) {
					$id_jadwal_wpsipd = $_POST['id_jadwal_wpsipd'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Jadwal RENSTRA kosong!';
				}

				if ($ret['status'] == 'success') {
					$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

					$unit = $wpdb->get_results(
						$wpdb->prepare("
						SELECT 
							nama_skpd, 
							id_skpd, 
							kode_skpd, 
							nipkepala 
						FROM esakip_data_unit 
						WHERE active=1 
						  AND tahun_anggaran=%d
						  AND is_skpd=1 
						ORDER BY kode_skpd ASC
						", $tahun_anggaran_sakip),
						ARRAY_A
					);

					if (!empty($unit)) {
						$tbody = '';

						foreach ($unit as $kk => $vv) {
							$detail_input_iku = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Detail Pengisian IKU ',
								'content' => '[detail_input_iku]',
								'show_header' => 1,
								'post_status' => 'private'
							)); //dokumen_detail_input_iku

							$jumlah_iku = $wpdb->get_var(
								$wpdb->prepare("
									SELECT 
										COUNT(id)
									FROM esakip_data_iku_opd
									WHERE id_skpd = %d
									AND id_jadwal_wpsipd = %d 
									AND active = 1
								", $vv['id_skpd'], $id_jadwal_wpsipd)
							);

							$tbody .= "<tr>";
							$tbody .= "<td style='text-transform: uppercase;'><a href='" . $detail_input_iku['url'] . "&id_skpd=" . $vv['id_skpd'] . "&id_periode=" . $id_jadwal_wpsipd . "' target='_blank'>" . $vv['kode_skpd'] . " " . $vv['nama_skpd'] . "</a></td>";
							$tbody .= "<td class='text-center' style='text-transform: uppercase;'>" . $jumlah_iku . "</td>";
							$tbody .= "</tr>";
						}
						$ret['data'] = $tbody;
					} else {
						$ret['data'] = "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
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

	public function get_table_skpd_input_cascading()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['periode'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Jadwal RENSTRA kosong!';
				}

				if ($ret['status'] == 'success') {
					$periode = $_POST['periode'];
					$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

					$unit = $wpdb->get_results(
						$wpdb->prepare("
						SELECT 
							nama_skpd, 
							id_skpd, 
							kode_skpd, 
							nipkepala 
						FROM esakip_data_unit 
						WHERE active=1 
						  AND tahun_anggaran=%d
						  AND is_skpd=1 
						ORDER BY kode_skpd ASC
						", $tahun_anggaran_sakip),
						ARRAY_A
					);

					if (!empty($unit)) {
						$tbody = '';

						$detail_input_cascading = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Pengisian Cascading OPD',
							'content' => '[detail_input_cascading_pd]',
							'show_header' => 1,
							'post_status' => 'private'
						));
						foreach ($unit as $kk => $vv) {
							$jumlah_tujuan = $wpdb->get_var($wpdb->prepare("
								SELECT
        							COUNT(DISTINCT tujuan)
								FROM esakip_cascading_opd_tujuan
								WHERE active=1
									AND id_skpd=%d
									AND id_jadwal=%d
									AND id_tujuan is NULL
							", $vv['id_skpd'], $periode));
							$jumlah_sasaran = $wpdb->get_var($wpdb->prepare("
								SELECT
									COUNT(id)
								FROM esakip_cascading_opd_sasaran
								WHERE active=1
									AND id_skpd=%d
									AND id_jadwal=%d
									AND id_sasaran is NULL
							", $vv['id_skpd'], $periode));
							$jumlah_program = $wpdb->get_var($wpdb->prepare("
								SELECT
									COUNT(id)
								FROM esakip_cascading_opd_program
								WHERE active=1
									AND id_skpd=%d
									AND id_jadwal=%d
									AND id_program is NULL
							", $vv['id_skpd'], $periode));
							$jumlah_kegiatan = $wpdb->get_var($wpdb->prepare("
								SELECT
									COUNT(id)
								FROM esakip_cascading_opd_kegiatan
								WHERE active=1
									AND id_skpd=%d
									AND id_jadwal=%d
									AND id_giat is NULL
							", $vv['id_skpd'], $periode));
							$jumlah_sub_giat = $wpdb->get_var($wpdb->prepare("
								SELECT
									COUNT(id)
								FROM esakip_cascading_opd_sub_giat
								WHERE active=1
									AND id_skpd=%d
									AND id_jadwal=%d
									AND id_sub_giat is NULL
							", $vv['id_skpd'], $periode));
							$tbody .= "
							<tr>
								<td class='text-center'><input class='nama-opd' type='checkbox' value='" . $vv['id_skpd'] . "'></td>
								<td style='text-transform: uppercase;' class='nama-opd-asli'><a href='" . $detail_input_cascading['url'] . "&id_skpd=" . $vv['id_skpd'] . "&id_periode=" . $periode . "' target='_blank'>" . $vv['kode_skpd'] . " " . $vv['nama_skpd'] . "</a></td>
								<td class='text-center'>$jumlah_tujuan</td>
								<td class='text-center'>$jumlah_sasaran</td>
								<td class='text-center'>$jumlah_program</td>
								<td class='text-center'>$jumlah_kegiatan</td>
								<td class='text-center'>$jumlah_sub_giat</td>
							</tr>";
						}
						$ret['data'] = $tbody;
					} else {
						$ret['data'] = "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
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

	public function get_table_cascading_pd()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (empty($_POST['id_jadwal'])) {
				$ret['status'] = 'error';
				$ret['message'] = 'id_jadwal tidak boleh kosong!';
			} else if (empty($_POST['id_skpd'])) {
				$ret['status'] = 'error';
				$ret['message'] = 'id_skpd tidak boleh kosong!';
			}

			if ($ret['status'] == 'success') {
				$id_jadwal = $_POST['id_jadwal'];
				$id_skpd = $_POST['id_skpd'];
				$show_pokin = isset($_POST['show_pokin']) ? $_POST['show_pokin'] : false;
				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

				$tujuan_data = $wpdb->get_results(
					$wpdb->prepare("
                        SELECT 
                            *
                        FROM esakip_cascading_opd_tujuan 
                        WHERE active=1 
                          AND id_jadwal=%d
                          AND id_skpd=%d
                          AND id_tujuan IS NULL
                        ORDER BY no_urut ASC
                        ", $id_jadwal, $id_skpd),
					ARRAY_A
				);

				if (!empty($tujuan_data)) {
					$body_all = array();

					foreach ($tujuan_data as $t) {
						$indikator_tujuan = $wpdb->get_results(
							$wpdb->prepare("
                                SELECT 
                                    indikator
                                FROM esakip_cascading_opd_tujuan 
                                WHERE active=1 
                                  AND indikator IS NOT NULL
                                  AND id_tujuan=%d
                                ORDER BY no_urut ASC
                                ", $t['id']),
							ARRAY_A
						);

						$satker_tujuan = $wpdb->get_results(
							$wpdb->prepare("
                                    SELECT 
                                        TRIM(SUBSTRING_INDEX(nama_satker, '|', -1)) AS nama_satker
                                    FROM esakip_data_pegawai_cascading 
                                    WHERE active = 1 
                                      AND jenis_data = 1
                                      AND id_data = %s
                                      AND id_skpd = %d
                                      AND id_jadwal = %d
                                ", $t['id_unik'], $id_skpd, $id_jadwal),
							ARRAY_A
						);

						$pokin_tujuan = array();
						if ($show_pokin) {
							$pokin_tujuan = $wpdb->get_results(
								$wpdb->prepare("
                                        SELECT 
                                            TRIM(SUBSTRING_INDEX(nama_pokin, '|', -1)) AS nama_pokin,
                                            TRIM(SUBSTRING_INDEX(indikator, '|', -1)) AS indikator
                                        FROM esakip_data_pokin_cascading 
                                        WHERE active = 1 
                                          AND jenis_data = 1
                                          AND id_data = %s
                                          AND id_skpd = %d
                                    ", $t['id_unik'], $id_skpd),
								ARRAY_A
							);
						}

						$ind_tujuan = array();
						$_satker_tujuan = array();
						$_pokin_tujuan = array();

						foreach ($indikator_tujuan as $ind) {
							$indikator_text = trim($ind['indikator']);
							if (!empty($indikator_text)) {
								$ind_tujuan[] = $indikator_text;
							}
						}

						foreach ($satker_tujuan as $satker) {
							$_satker_tujuan[] = '<li>' . $satker['nama_satker'] . '</li>';
						}

						foreach ($pokin_tujuan as $pokin) {
							$_pokin_tujuan[] = '<li>' . $pokin['nama_pokin'] . ' ( ' . $pokin['indikator'] . ' )</li>';
						}

						if (!isset($body_all[$t['tujuan']])) {
							$body_all[$t['tujuan']] = array(
								'colspan_sasaran' => 0,
								'colspan_program' => 0,
								'tujuan' => $t['tujuan'],
								'id' => $t['id'],
								'indikator' => array(),
								'nama_satker' => $_satker_tujuan,
								'nama_pokin' => $_pokin_tujuan,
								'data' => array()
							);
						}

						$body_all[$t['tujuan']]['indikator'] = array_merge($body_all[$t['tujuan']]['indikator'], $ind_tujuan);

						$body_all[$t['tujuan']]['indikator'] = array_unique($body_all[$t['tujuan']]['indikator'], SORT_STRING);

						$sasaran = $wpdb->get_results(
							$wpdb->prepare("
                                SELECT 
                                    *
                                FROM esakip_cascading_opd_sasaran 
                                WHERE active=1 
                                  AND id_tujuan=%d
                                  AND id_sasaran IS NULL
                                ORDER BY no_urut ASC
                                ", $t['id']),
							ARRAY_A
						);

						foreach ($sasaran as $s) {
							$indikator_sasaran = $wpdb->get_results(
								$wpdb->prepare("
                                    SELECT 
                                        indikator
                                    FROM esakip_cascading_opd_sasaran 
                                    WHERE active=1 
                                      AND indikator IS NOT NULL
                                      AND id_sasaran=%d
                                    ORDER BY no_urut ASC
                                    ", $s['id']),
								ARRAY_A
							);

							$satker_sasaran = $wpdb->get_results(
								$wpdb->prepare("
                                        SELECT 
                                            TRIM(SUBSTRING_INDEX(nama_satker, '|', -1)) AS nama_satker
                                        FROM esakip_data_pegawai_cascading 
                                        WHERE active = 1 
                                          AND jenis_data = 2
                                          AND id_data = %s
                                          AND id_skpd = %d
                                    ", $s['id_unik'], $id_skpd),
								ARRAY_A
							);

							$pokin_sasaran = array();
							if ($show_pokin) {
								$pokin_sasaran = $wpdb->get_results(
									$wpdb->prepare("
                                            SELECT 
                                                TRIM(SUBSTRING_INDEX(nama_pokin, '|', -1)) AS nama_pokin,
                                                TRIM(SUBSTRING_INDEX(indikator, '|', -1)) AS indikator
                                            FROM esakip_data_pokin_cascading 
                                            WHERE active = 1 
                                              AND jenis_data = 2
                                              AND id_data = %s
                                              AND id_skpd = %d
                                        ", $s['id_unik'], $id_skpd),
									ARRAY_A
								);
							}

							$ind_sasaran = array();
							$_satker_sasaran = array();
							$_pokin_sasaran = array();
							foreach ($indikator_sasaran as $ind) {
								$ind_sasaran[] = '<li>' . $ind['indikator'] . '</li>';
							}
							foreach ($satker_sasaran as $satker) {
								$_satker_sasaran[] = '<li>' . $satker['nama_satker'] . '</li>';
							}
							foreach ($pokin_sasaran as $pokin) {
								$_pokin_sasaran[] = '<li>' . $pokin['nama_pokin'] . '(' . $pokin['indikator'] . ')</li>';
							}

							if (!isset($body_all[$t['tujuan']]['data'][$s['sasaran']])) {
								$body_all[$t['tujuan']]['data'][$s['sasaran']] = array(
									'colspan_program' => 0,
									'sasaran' => $s['sasaran'],
									'id' => $s['id'],
									'indikator' => $ind_sasaran,
									'nama_satker' => $_satker_sasaran,
									'nama_pokin' => $_pokin_sasaran,
									'data' => array()
								);
							}

							$program = $wpdb->get_results(
								$wpdb->prepare("
                                    SELECT 
                                        *
                                    FROM esakip_cascading_opd_program 
                                    WHERE active=1 
                                      AND id_sasaran=%d
                                      AND id_program IS NULL
                                    ORDER BY no_urut ASC
                                    ", $s['id']),
								ARRAY_A
							);

							foreach ($program as $p) {
								$indikator_program = $wpdb->get_results(
									$wpdb->prepare("
                                        SELECT 
                                            indikator
                                        FROM esakip_cascading_opd_program 
                                        WHERE active=1 
                                          AND indikator IS NOT NULL
                                          AND id_program=%d
                                        ORDER BY no_urut ASC
                                        ", $p['id']),
									ARRAY_A
								);

								$satker_program = $wpdb->get_results(
									$wpdb->prepare("
                                            SELECT 
                                                TRIM(SUBSTRING_INDEX(nama_satker, '|', -1)) AS nama_satker
                                            FROM esakip_data_pegawai_cascading 
                                            WHERE active = 1 
                                              AND jenis_data = 3
                                              AND id_data = %s
                                              AND id_skpd = %d
                                        ", $p['id_unik'], $id_skpd),
									ARRAY_A
								);

								$pokin_program = array();
								if ($show_pokin) {
									$pokin_program = $wpdb->get_results(
										$wpdb->prepare("
                                                SELECT 
                                                    TRIM(SUBSTRING_INDEX(nama_pokin, '|', -1)) AS nama_pokin,
                                                    TRIM(SUBSTRING_INDEX(indikator, '|', -1)) AS indikator
                                                FROM esakip_data_pokin_cascading 
                                                WHERE active = 1 
                                                  AND jenis_data = 3
                                                  AND id_data = %s
                                                  AND id_skpd = %d
                                            ", $p['id_unik'], $id_skpd),
										ARRAY_A
									);
								}

								$ind_prog = array();
								$satker_prog = array();
								$_pokin_program = array();
								foreach ($indikator_program as $ind) {
									$ind_prog[] = '<li>' . $ind['indikator'] . '</li>';
								}
								foreach ($satker_program as $satker) {
									$satker_prog[] = '<li>' . $satker['nama_satker'] . '</li>';
								}
								foreach ($pokin_program as $pokin) {
									$_pokin_program[] = '<li>' . $pokin['nama_pokin'] . '(' . $pokin['indikator'] . ')</li>';
								}
								$body_all[$t['tujuan']]['data'][$s['sasaran']]['data'][$p['program']] = array(
									'program' => $p['program'],
									'id' => $p['id'],
									'indikator' => $ind_prog,
									'nama_satker' => $satker_prog,
									'nama_pokin' => $_pokin_program
								);

								$body_all[$t['tujuan']]['data'][$s['sasaran']]['colspan_program']++;
							}
						}
					}

					foreach ($body_all as $tujuan_key => &$tujuan_data) {
						$total_program_count = 0;
						$total_sasaran_count = 0;

						foreach ($tujuan_data['data'] as $sasaran_key => &$sasaran_data) {
							$program_count = count($sasaran_data['data']);
							$sasaran_data['colspan_program'] = max(1, $program_count);
							$total_program_count += $sasaran_data['colspan_program'];
							$total_sasaran_count++;
						}

						$tujuan_data['colspan_program'] = max(1, $total_program_count);
						$tujuan_data['colspan_sasaran'] = max(1, $total_sasaran_count);
					}

					$tujuan_html = '';
					$sasaran_html = '';
					$program_html = '';

					$pokin_style = $show_pokin ? '' : ' style="display: none;"';

					foreach ($body_all as $t) {
						$indikator_html = array_map(function ($indicator) {
							return '<li>' . $indicator . '</li>';
						}, $t['indikator']);
						$indikator = implode('', $indikator_html);

						$nama_satker = empty($t['nama_satker']) ? '<li>-</li>' : implode('', $t['nama_satker']);
						$nama_pokin = empty($t['nama_pokin']) ? '<li>-</li>' : implode('', $t['nama_pokin']);

						$tujuan_html .= '<td class="text-center" colspan="' . $t['colspan_program'] . '">
                                <div class="button-container">
                                    <div class="btn btn-lg btn-warning get_button" style="text-transform:uppercase;">
                                        ' . $t['tujuan'] . '
                                        <hr/>
                                        <span class="indikator">IND: <ol style="text-align: left;">' . $indikator . '</ol></span>
                                        <hr/>
                                        <span class="nama_pokin"' . $pokin_style . '>Pohon Kinerja : <ol style="text-align: left;">' . $nama_pokin . '</ol></span>
                                        <br />
                                        <span class="nama_satker">Satuan Kerja : <ol style="text-align: left;">' . $nama_satker . '</ol></span>
                                        <br />
                                        
                                    </div>
                                </div>
                            </td>';

						foreach ($t['data'] as $s) {
							$indikator = implode('', $s['indikator']);
							$nama_satker = empty($s['nama_satker']) ? '<li>-</li>' : implode('', $s['nama_satker']);
							$nama_pokin = empty($s['nama_pokin']) ? '<li>-</li>' : implode('', $s['nama_pokin']);
							$sasaran_html .= '<td class="text-center" colspan="' . $s['colspan_program'] . '">
                                    <div class="button-container">
                                        <div class="btn btn-lg btn-success get_button" style="text-transform:uppercase;">
                                            ' . $s['sasaran'] . '
                                            <hr/>
                                            <span class="indikator">IND: <ol style="text-align: left;">' . $indikator . '</ol></span>
                                            <hr/>
                                            <span class="nama_pokin"' . $pokin_style . '>Pohon Kinerja : <ol style="text-align: left;">' . $nama_pokin . '</ol></span>
                                            <br />
                                            <span class="nama_satker">Satuan Kerja : <ol style="text-align: left;">' . $nama_satker . '</ol></span>
                                            <br />
                                        </div>
                                    </div>
                                </td>';
							foreach ($s['data'] as $p) {
								$indikator = implode('', $p['indikator']);
								$nama_satker = empty($p['nama_satker']) ? '<li>-</li>' : implode('', $p['nama_satker']);
								$nama_pokin = empty($p['nama_pokin']) ? '<li>-</li>' : implode('', $p['nama_pokin']);
								$program_html .= '<td class="text-center">
                                        <div class="button-container">
                                            <div class="btn btn-lg btn-danger get_button" id="program-ke-' . $p["id"] . '" data-nama-program="' . $p['program'] . '" style="text-transform:uppercase; position: relative;">
                                                ' . $p['program'] . '
                                                <hr/>
                                                <span class="indikator">IND: <ol style="text-align: left;">' . $indikator . '</ol></span>
                                                <hr/>
                                                <span class="nama_pokin"' . $pokin_style . '>Pohon Kinerja : <ol style="text-align: left;">' . $nama_pokin . '</ol></span>
                                                <br />
                                                <span class="nama_satker">Satuan Kerja : <ol style="text-align: left;">' . $nama_satker . '</ol></span>
                                                <br />
                                                <div style="margin-top: 10px; display: flex; gap: 10px; justify-content: center;">
                                                <button class="btn btn-danger view-kegiatan-button" onclick="view_kegiatan(this, \'' . $p['id'] . '\');"><i style="font-size: 2rem;" class="dashicons dashicons-visibility visibility-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>';
							}
						}
					}

					$tbody = '
                            <tr>
                                <td class="text-center" style="width: 150px;">
                                    <div class="button-container">
                                        <div class="btn btn-lg btn-info">TUJUAN</div>
                                    </div>
                                </td>
                                ' . $tujuan_html . '
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="button-container">
                                        <div class="btn btn-lg btn-info">SASARAN</div>
                                    </div>
                                </td>
                                ' . $sasaran_html . '
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="button-container">
                                        <div class="btn btn-lg btn-info">PROGRAM</div>
                                    </div>
                                </td>
                                ' . $program_html . '
                            </tr>';

					$ret['body_all'] = $body_all;
					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
				}
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message' => 'Format tidak sesuai!'
			);
		}
		die(json_encode($ret));
	}

	public function get_kegiatan_by_program()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil mendapatkan data kegiatan!',
			'data' => ''
		);

		if (!empty($_POST)) {
			if (empty($_POST['id'])) {
				$ret['status'] = 'error';
				$ret['message'] = 'ID tidak boleh kosong!';
			} else if (empty($_POST['id_skpd'])) {
				$ret['status'] = 'error';
				$ret['message'] = 'id_skpd tidak boleh kosong!';
			}

			if ($ret['status'] === 'success') {
				$id = intval($_POST['id']);
				$id_skpd = trim($_POST['id_skpd']);
				$show_pokin = isset($_POST['show_pokin']) ? $_POST['show_pokin'] : false;

				$kegiatan_data = $wpdb->get_results(
					$wpdb->prepare("
                        SELECT 
                            * 
                        FROM esakip_cascading_opd_kegiatan 
                        WHERE active = 1 
                            AND id_program = %d 
                        ORDER BY no_urut ASC
                    ", $id),
					ARRAY_A
				);

				if (!empty($kegiatan_data)) {
					$body_all = array();
					foreach ($kegiatan_data as $k) {
						$indikator_kegiatan = $wpdb->get_results(
							$wpdb->prepare("
                                SELECT 
                                    indikator 
                                FROM esakip_cascading_opd_kegiatan 
                                WHERE active = 1 
                                    AND indikator IS NOT NULL
                                    AND id_giat = %d 
                                ORDER BY no_urut ASC
                            ", $k['id']),
							ARRAY_A
						);

						$satker_kegiatan = $wpdb->get_results(
							$wpdb->prepare("
                                SELECT 
                                    TRIM(SUBSTRING_INDEX(nama_satker, '|', -1)) AS nama_satker
                                FROM esakip_data_pegawai_cascading 
                                WHERE active = 1 
                                  AND jenis_data = 4
                                  AND id_data = %s
                                  AND id_skpd = %d
                            ", $k['id_unik'], $id_skpd),
							ARRAY_A
						);

						$pokin_kegiatan = array();
						if ($show_pokin) {
							$pokin_kegiatan = $wpdb->get_results(
								$wpdb->prepare("
                                    SELECT 
                                        TRIM(SUBSTRING_INDEX(nama_pokin, '|', -1)) AS nama_pokin,
                                        TRIM(SUBSTRING_INDEX(indikator, '|', -1)) AS indikator
                                    FROM esakip_data_pokin_cascading 
                                    WHERE active = 1 
                                      AND jenis_data = 4
                                      AND id_data = %s
                                      AND id_skpd = %d
                                ", $k['id_unik'], $id_skpd),
								ARRAY_A
							);
						}

						$ind_keg = array();
						$satker_keg = array();
						$_pokin_kegiatan = array();
						foreach ($indikator_kegiatan as $ind) {
							$ind_keg[] = '<li>' . $ind['indikator'] . '</li>';
						}
						foreach ($satker_kegiatan as $satker) {
							$satker_keg[] = '<li>' . $satker['nama_satker'] . '</li>';
						}
						foreach ($pokin_kegiatan as $pokin) {
							$_pokin_kegiatan[] = '<li>' . $pokin['nama_pokin'] . '(' . $pokin['indikator'] . ')</li>';
						}
						if (!isset($body_all[$k['kegiatan']])) {
							$body_all[$k['kegiatan']] = array(
								'colspan_sub_giat' => 0,
								'kegiatan' => $k['kegiatan'],
								'id' => $k['id'],
								'indikator' => $ind_keg,
								'nama_satker' => $satker_keg,
								'nama_pokin' => $_pokin_kegiatan,
								'data' => array()
							);
						}

						$sub_giat = $wpdb->get_results(
							$wpdb->prepare("
                                SELECT 
                                    * 
                                FROM esakip_cascading_opd_sub_giat 
                                WHERE active = 1 
                                    AND id_giat = %d 
                                    AND id_sub_giat IS NULL 
                                ORDER BY no_urut ASC
                            ", $k['id']),
							ARRAY_A
						);

						foreach ($sub_giat as $g) {
							$body_all[$k['kegiatan']]['colspan_sub_giat']++;
							$indikator_sub_giat = $wpdb->get_results(
								$wpdb->prepare("
                                    SELECT indikator 
                                    FROM esakip_cascading_opd_sub_giat 
                                    WHERE active = 1 
                                        AND indikator IS NOT NULL
                                        AND id_sub_giat = %d 
                                    ORDER BY no_urut ASC
                                ", $g['id']),
								ARRAY_A
							);
							$satker_sub_giat = $wpdb->get_results(
								$wpdb->prepare("
                                    SELECT 
                                        TRIM(SUBSTRING_INDEX(nama_satker, '|', -1)) AS nama_satker
                                    FROM esakip_data_pegawai_cascading 
                                    WHERE active = 1 
                                      AND jenis_data = 5
                                      AND id_data = %s
                                      AND id_skpd = %d
                                ", $g['id_unik'], $id_skpd),
								ARRAY_A
							);

							$pokin_sub = array();
							if ($show_pokin) {
								$pokin_sub = $wpdb->get_results(
									$wpdb->prepare("
                                        SELECT 
                                            TRIM(SUBSTRING_INDEX(nama_pokin, '|', -1)) AS nama_pokin,
                                            TRIM(SUBSTRING_INDEX(indikator, '|', -1)) AS indikator
                                        FROM esakip_data_pokin_cascading 
                                        WHERE active = 1 
                                          AND jenis_data = 5
                                          AND id_data = %s
                                          AND id_skpd = %d
                                    ", $g['id_unik'], $id_skpd),
									ARRAY_A
								);
							}

							$ind_sub = array();
							$satker_sub = array();
							$_pokin_sub = array();
							foreach ($indikator_sub_giat as $ind) {
								$ind_sub[] = '<li>' . $ind['indikator'] . '</li>';
							}
							foreach ($satker_sub_giat as $satker) {
								$satker_sub[] = '<li>' . $satker['nama_satker'] . '</li>';
							}
							foreach ($pokin_sub as $pokin) {
								$_pokin_sub[] = '<li>' . $pokin['nama_pokin'] . '(' . $pokin['indikator'] . ')</li>';
							}
							$body_all[$k['kegiatan']]['data'][$g['sub_giat']] = array(
								'sub_giat' => $g['sub_giat'],
								'id' => $g['id'],
								'indikator' => $ind_sub,
								'nama_pokin' => $_pokin_sub,
								'nama_satker' => $satker_sub
							);
						}
					}

					$get_program = $wpdb->get_results(
						$wpdb->prepare("
                            SELECT 
                                no_urut
                            FROM esakip_cascading_opd_program 
                            WHERE id = %d
                        ", $id),
						ARRAY_A
					);

					$pokin_style = $show_pokin ? '' : ' style="display: none;"';

					$kegiatan_html = '';
					$sub_giat_html = '';
					$no_urut = isset($get_program[0]['no_urut']) ? $get_program[0]['no_urut'] : '';
					foreach ($body_all as $k) {
						$indikator = implode('', $k['indikator']);
						$nama_satker = empty($k['nama_satker']) ? '<li>-</li>' : implode('', $k['nama_satker']);
						$nama_pokin = empty($k['nama_pokin']) ? '<li>-</li>' : implode('', $k['nama_pokin']);
						$kegiatan_html .= '<td class="text-center" colspan="' . $k['colspan_sub_giat'] . '">
                            <div class="button-container">
                                <div class="btn btn-lg btn-primary get_button" style="text-transform:uppercase;">
                                    ' . $k['kegiatan'] . '
                                    <hr/>
                                    <span class="indikator">IND: <ol class="text-left">' . $indikator . '</ol></span>
                                    <hr/>
                                    <span class="nama_pokin"' . $pokin_style . '>Pohon Kinerja : <ol style="text-align: left;">' . $nama_pokin . '</ol></span>
                                    <br />
                                    <span class="nama_satker">Satuan Kerja : <ol class="text-left">' . $nama_satker . '</ol></span>
                                    <br />							     
                                </div>
                            </div>
                        </td>';
						foreach ($k['data'] as $g) {
							$indikator = implode('', $g['indikator']);
							$nama_satker = empty($g['nama_satker']) ? '<li>-</li>' : implode('', $g['nama_satker']);
							$nama_pokin = empty($g['nama_pokin']) ? '<li>-</li>' : implode('', $g['nama_pokin']);
							$sub_giat_html .= '<td class="text-center">
                                <div class="button-container">
                                    <div class="btn btn-lg btn-secondary get_button" style="text-transform:uppercase;">
                                        ' . $g['sub_giat'] . '
                                        <hr/>
                                        <span class="indikator">IND: <ol class="text-left">' . $indikator . '</ol></span>
                                        <hr/>
                                        <span class="nama_pokin"' . $pokin_style . '>Pohon Kinerja : <ol style="text-align: left;">' . $nama_pokin . '</ol></span>
                                        <br />
                                        <span class="nama_satker">Satuan Kerja : <ol class="text-left">' . $nama_satker . '</ol></span>
                                        <br />							     
                                    </div>
                                </div>
                            </td>';
						}
					}

					$ret['data'] =
						'<tr>
                            <td class="text-center" style="width: 150px;">' .
						'<div class="button-container">
                                <div class="btn btn-lg btn-info">KEGIATAN</div>
                            </div>
                        </td>
                        ' . $kegiatan_html . '
                    </tr>' .
						'<tr>
                            <td class="text-center">' .
						'<div class="button-container">
                                <div class="btn btn-lg btn-info">SUB KEGIATAN</div>
                            </div>
                           </td>
                           ' . $sub_giat_html . '
                    </tr>';
				} else {
					$ret['data'] = "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
				}
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format tidak sesuai!';
		}

		die(json_encode($ret));
	}

	public function get_tujuan_cascading()
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
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'ID kosong!';
					die(json_encode($ret));
				}

				$id_skpd = $_POST['id_skpd'];
				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

				$data = $wpdb->get_row(
					$wpdb->prepare("
                        SELECT 
                            * 
                        FROM esakip_cascading_opd_tujuan
                        WHERE id = %d
                    ", $id),
					ARRAY_A
				);

				if ($data) {
					$jabatan = $wpdb->get_results($wpdb->prepare('
                        SELECT
                            *
                        FROM esakip_data_pegawai_cascading
                        WHERE jenis_data = 1
                            AND id_data = %d
                            AND active = 1
                    ', $id), ARRAY_A);

					$ret['jabatan'] = $jabatan;
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

	public function get_sasaran_cascading()
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
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'ID kosong!';
				}

				$data = $wpdb->get_row(
					$wpdb->prepare("
                    	SELECT 
                    		* 
                    	FROM esakip_cascading_opd_sasaran
                    	WHERE id = %d
                    ", $id),
					ARRAY_A
				);

				if ($data) {
					$jabatan = $wpdb->get_results($wpdb->prepare('
	                	SELECT
	                		*
	                	FROM esakip_data_pegawai_cascading
	                	WHERE jenis_data=2
	                		AND id_data=%d
                            AND active = 1
	                ', $id), ARRAY_A);
					$ret['jabatan'] = $jabatan;
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
	public function get_program_cascading()
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
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'ID kosong!';
				}

				$data = $wpdb->get_row(
					$wpdb->prepare("
                    	SELECT 
                    		* 
                    	FROM esakip_cascading_opd_program
                    	WHERE id = %d
                    ", $id),
					ARRAY_A
				);

				if ($data) {
					$jabatan = $wpdb->get_results($wpdb->prepare('
	                	SELECT
	                		*
	                	FROM esakip_data_pegawai_cascading
	                	WHERE jenis_data=3
	                		AND id_data=%d
                            AND active = 1
	                ', $id), ARRAY_A);
					$ret['jabatan'] = $jabatan;
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
	public function get_kegiatan_cascading()
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
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'ID kosong!';
				}

				$data = $wpdb->get_row(
					$wpdb->prepare("
                    	SELECT 
                    		* 
                    	FROM esakip_cascading_opd_kegiatan
                    	WHERE id = %d
                    ", $id),
					ARRAY_A
				);

				if ($data) {
					$jabatan = $wpdb->get_results($wpdb->prepare('
	                	SELECT
	                		*
	                	FROM esakip_data_pegawai_cascading
	                	WHERE jenis_data=4
	                		AND id_data=%d
                            AND active = 1
	                ', $id), ARRAY_A);
					$ret['jabatan'] = $jabatan;
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
	public function get_sub_giat_cascading()
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
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'ID kosong!';
				}

				$data = $wpdb->get_row(
					$wpdb->prepare("
                    	SELECT 
                    		* 
                    	FROM esakip_cascading_opd_sub_giat
                    	WHERE id = %d
                    ", $id),
					ARRAY_A
				);

				if ($data) {
					$jabatan = $wpdb->get_results($wpdb->prepare('
	                	SELECT
	                		*
	                	FROM esakip_data_pegawai_cascading
	                	WHERE jenis_data=5
	                		AND id_data=%d
                            AND active = 1
	                ', $id), ARRAY_A);
					$kegiatan = $wpdb->get_row($wpdb->prepare('
	                	SELECT
	                		no_urut
	                	FROM esakip_cascading_opd_kegiatan
	                	WHERE id=%d
	                ', $data['id_giat']), ARRAY_A);
					$ret['get_kegiatan'] = $kegiatan;
					$ret['jabatan'] = $jabatan;
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

	public function get_jabatan_cascading()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil mengambil data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$id_skpd = $_POST['id_skpd'];
				$q = $_POST['q'];

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

				$get_mapping = $wpdb->get_var($wpdb->prepare('
	                SELECT 
	                    u.id_satker_simpeg
	                FROM esakip_data_mapping_unit_sipd_simpeg AS u
	                WHERE u.tahun_anggaran = %d
	                    AND u.id_skpd = %d
	                    AND u.active = 1
	            ', $tahun_anggaran_sakip, $id_skpd));
				if (empty($get_mapping)) {
					$ret['message'] = 'ID Satker SIMPEG belum dimapping dengan ID SKPD!';
				}

				$get_satker = $wpdb->get_results($wpdb->prepare('
	                SELECT 
	                    s.id,
	                    s.satker_id,
	                    s.active,
	                    s.nama
	                FROM esakip_data_satker_simpeg AS s
	                WHERE s.satker_id like %s
	                    AND s.nama like %s
	                    AND tahun_anggaran = %d
	                ORDER BY satker_id ASC
	            ', $get_mapping . '%', '%' . $q . '%', $tahun_anggaran_sakip), ARRAY_A);

				$ret['sql'] = $wpdb->last_query;
				$ret['data'] = array();
				foreach ($get_satker as $satker) {
					$ret['data'][] = array(
						'id' => $satker['satker_id'],
						'nama' => $satker['satker_id'] . ' | ' . $satker['nama']
					);
				}
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


	public function submit_pegawai_cascading()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran tidak boleh kosong!';
				} elseif (empty($_POST['tipe'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe tidak boleh kosong!';
				} elseif (empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID SKPD tidak boleh kosong!';
				} elseif (empty($_POST['get_satker']) || !is_array($_POST['get_satker'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Data Satker kosong!';
				} else {
					$tipe = $_POST['tipe'];
					$tahun_anggaran = $_POST['tahun_anggaran'];
					$id_skpd = $_POST['id_skpd'];
					$id_data = $_POST['id_data'];
					$get_satker = $_POST['get_satker'];

					if ($tipe == 1) {
						$wpdb->update(
							'esakip_data_pegawai_cascading',
							array('active' => 0),
							array(
								'tahun_anggaran' => $tahun_anggaran,
								'id_skpd' => $id_skpd,
								'jenis_data' => $tipe
							)
						);
					} else {
						$wpdb->update(
							'esakip_data_pegawai_cascading',
							array('active' => 0),
							array(
								'tahun_anggaran' => $tahun_anggaran,
								'id_skpd' => $id_skpd,
								'jenis_data' => $tipe,
								'id_data' => $id_data
							)
						);
					}

					foreach ($get_satker as $satker) {
						$satker_id = $satker['satker_id'];
						$nama_satker = $satker['nama_satker'];

						if ($tipe == 1) {
							$tujuan_data = $wpdb->get_results(
								$wpdb->prepare("
                                    SELECT 
                                        id, 
                                        tujuan 
                                    FROM esakip_cascading_opd_tujuan 
                                    WHERE indikator IS NULL 
                                        AND tujuan = (
                                            SELECT 
                                                tujuan 
                                            FROM esakip_cascading_opd_tujuan 
                                            WHERE id = %d 
                                                AND indikator IS NULL)
                                ", $id_data),
								ARRAY_A
							);

							foreach ($tujuan_data as $tujuan) {
								$cek_id = $wpdb->get_var(
									$wpdb->prepare("
                                        SELECT 
                                            id 
                                            FROM esakip_data_pegawai_cascading 
                                            WHERE tahun_anggaran = %d 
                                                AND id_skpd = %s 
                                                AND jenis_data = %s 
                                                AND id_data = %s 
                                                AND id_satker = %s
                                    ", $tahun_anggaran, $id_skpd, $tipe, $tujuan['id'], $satker_id)
								);

								if (!empty($cek_id)) {
									$cek_active = $wpdb->get_var(
										$wpdb->prepare("
                                            SELECT 
                                                active 
                                            FROM esakip_data_pegawai_cascading 
                                            WHERE id = %d
                                        ", $cek_id)
									);

									if ($cek_active == 0) {
										$wpdb->update(
											'esakip_data_pegawai_cascading',
											array('active' => 1, 'update_at' => current_time('mysql')),
											array('id' => $cek_id)
										);
										$ret['message'] = "Berhasil update data satker.";
									}
								} else {
									$data = array(
										'id_satker' => $satker_id,
										'nama_satker' => $nama_satker,
										'id_data' => $tujuan['id'],
										'jenis_data' => $tipe,
										'tahun_anggaran' => $tahun_anggaran,
										'id_skpd' => $id_skpd,
										'active' => 1,
										'update_at' => current_time('mysql')
									);

									$wpdb->insert('esakip_data_pegawai_cascading', $data);
									$ret['message'] = "Berhasil menyimpan data satker.";
								}
							}
						} else {
							$cek_id = $wpdb->get_var(
								$wpdb->prepare("
                                    SELECT 
                                        id 
                                    FROM esakip_data_pegawai_cascading 
                                    WHERE tahun_anggaran = %d 
                                        AND id_skpd = %s 
                                        AND jenis_data = %s 
                                        AND id_data = %s 
                                        AND id_satker = %s
                                ", $tahun_anggaran, $id_skpd, $tipe, $id_data, $satker_id)
							);

							if (!empty($cek_id)) {
								$cek_active = $wpdb->get_var(
									$wpdb->prepare("
                                        SELECT 
                                            active 
                                        FROM esakip_data_pegawai_cascading 
                                        WHERE id = %d
                                    ", $cek_id)
								);

								if ($cek_active == 0) {
									$wpdb->update(
										'esakip_data_pegawai_cascading',
										array('active' => 1, 'update_at' => current_time('mysql')),
										array('id' => $cek_id)
									);
									$ret['message'] = "Berhasil update data satker.";
								}
							} else {
								$data = array(
									'id_satker' => $satker_id,
									'nama_satker' => $nama_satker,
									'id_data' => $id_data,
									'jenis_data' => $tipe,
									'tahun_anggaran' => $tahun_anggaran,
									'id_skpd' => $id_skpd,
									'active' => 1,
									'update_at' => current_time('mysql')
								);

								$wpdb->insert('esakip_data_pegawai_cascading', $data);
								$ret['message'] = "Berhasil menyimpan data satker.";
							}
						}
					}
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API Key tidak ditemukan!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format Salah!';
		}

		die(json_encode($ret));
	}

	function get_cascading_pd_from_renstra()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil ambil data CASCADING dari RENSTRA!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_jadwal_wpsipd'])) {
					$id_jadwal = $_POST['id_jadwal_wpsipd'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Jadwal RENSTRA kosong!';
				}

				if ($ret['status'] == 'success') {
					$data_all = array(
						'tujuan' => array(),
						'sasaran' => array(),
						'program' => array(),
						'kegiatan_renstra' => array(),
						'sub_kegiatan_renstra' => array(),
					);
					// TUJUAN
					$_POST['jenis'] = 'tujuan';
					$ret['tujuan'] = $this->get_tujuan_sasaran_cascading(true);

					$wpdb->update('esakip_cascading_opd_tujuan', array('active' => 0), array(
						'active' => 1,
						'id_skpd' => $_POST['id_skpd'],
						'id_jadwal' => $id_jadwal
					));
					$ret['tujuan_error'] = array();
					foreach ($ret['tujuan']['data'] as $t) {
						$data_db = array(
							'id_jadwal'	=> $t->id_jadwal,
							'id_skpd'	=> $t->id_unit,
							'id_unik'	=> $t->id_unik,
							'no_urut'	=> $t->urut_tujuan,
							'tujuan'	=> $t->tujuan_teks,
							'indikator'	=> $t->indikator_teks,
							'active'	=> 1,
							'created_at'	=> current_time('mysql')
						);
						if (empty($t->id_unik_indikator)) {
							$cek_id = $wpdb->get_var($wpdb->prepare("
								SELECT
									id
								FROM esakip_cascading_opd_tujuan
								WHERE id_skpd=%d
									AND id_jadwal=%s
									AND id_unik=%s
									AND id_unik_indikator is NULL
							", $t->id_unit, $t->id_jadwal, $t->id_unik));
							$data_all['tujuan'][$t->id_unik] = array(
								'id' => $cek_id
							);
						} else {
							if (empty($data_all['tujuan'][$t->id_unik]['id'])) {
								$ret['tujuan_error'][] = $t;
								continue;
							}
							$data_db['id_unik_indikator'] = $t->id_unik_indikator;
							$data_db['id_tujuan'] = $data_all['tujuan'][$t->id_unik]['id'];

							$cek_id = $wpdb->get_var($wpdb->prepare("
								SELECT
									id
								FROM esakip_cascading_opd_tujuan
								WHERE id_skpd=%d
									AND id_jadwal=%s
									AND id_unik=%s
									AND id_unik_indikator=%s
							", $t->id_unit, $t->id_jadwal, $t->id_unik, $t->id_unik_indikator));
						}
						if (!empty($cek_id)) {
							$wpdb->update('esakip_cascading_opd_tujuan', $data_db, array('id' => $cek_id));
						} else {
							$wpdb->insert('esakip_cascading_opd_tujuan', $data_db);

							if (empty($t->id_unik_indikator)) {
								$data_all['tujuan'][$t->id_unik]['id'] = $wpdb->insert_id;
							}
						}

						//Simpan data satker dan pokin 
						$wpdb->update('esakip_data_pegawai_cascading', array('active' => 0), array(
							'id_data' => $t->id_unik,
							'jenis_data' => 1,
							'id_skpd' => $_POST['id_skpd'],
							'id_jadwal' => $id_jadwal
						));
						$wpdb->update('esakip_data_pokin_cascading', array('active' => 0), array(
							'id_data' => $t->id_unik,
							'jenis_data' => 1,
							'id_skpd' => $_POST['id_skpd'],
							'id_jadwal' => $id_jadwal
						));

						if (!empty($t->pelaksana_renstra)) {
							foreach ($t->pelaksana_renstra as $pelaksana) {
								$wpdb->insert('esakip_data_pegawai_cascading', array(
									'id_satker' => $pelaksana->id_satker ?? '',
									'nama_satker' => $pelaksana->nama_satker ?? '',
									'jabatan' => $pelaksana->jabatan ?? '',
									'nip' => $pelaksana->nip ?? '',
									'nama' => $pelaksana->nama ?? '',
									'jenis_data' => 1,
									'id_data' => $t->id_unik,
									'id_jadwal' => $id_jadwal,
									'id_skpd' => $_POST['id_skpd'],
									'active' => 1,
									'created_at' => current_time('mysql')
								));
							}
						}
						if (!empty($t->pokin_renstra)) {
							foreach ($t->pokin_renstra as $pokin) {
								$wpdb->insert('esakip_data_pokin_cascading', array(
									'id_pokin'   => $pokin->id_pokin ?? '',
									'nama_pokin' => $pokin->label ?? '',
									'indikator'	 => $pokin->indikator ?? '',
									'jenis_data' => 1,
									'id_data'    => $t->id_unik,
									'id_jadwal'  => $id_jadwal,
									'id_skpd'    => $_POST['id_skpd'],
									'active'     => 1,
									'created_at' => current_time('mysql')
								));
							}
						}
					}
					// SASARAN
					$_POST['jenis'] = 'sasaran';
					$ret['sasaran'] = $this->get_tujuan_sasaran_cascading(true);

					$wpdb->update('esakip_cascading_opd_sasaran', array('active' => 0), array(
						'active' => 1,
						'id_skpd' => $_POST['id_skpd'],
						'id_jadwal' => $id_jadwal
					));
					$ret['sasaran_error'] = array();
					foreach ($ret['sasaran']['data'] as $s) {
						if (empty($data_all['tujuan'][$s->kode_tujuan])) {
							$ret['sasaran_error'][] = $s;
							continue;
						}
						$data_db = array(
							'id_jadwal'	=> $s->id_jadwal,
							'id_skpd'	=> $s->id_unit,
							'id_tujuan'	=> $data_all['tujuan'][$s->kode_tujuan]['id'],
							'id_unik'	=> $s->id_unik,
							'no_urut'	=> $s->urut_sasaran,
							'sasaran'	=> $s->sasaran_teks,
							'indikator'	=> $s->indikator_teks,
							'active'	=> 1,
							'created_at'	=> current_time('mysql')
						);
						if (empty($s->id_unik_indikator)) {
							$cek_id = $wpdb->get_var($wpdb->prepare("
								SELECT
									id
								FROM esakip_cascading_opd_sasaran
								WHERE id_skpd=%d
									AND id_jadwal=%d
									AND id_unik=%s
									AND id_unik_indikator is NULL
							", $s->id_unit, $s->id_jadwal, $s->id_unik));
							$data_all['sasaran'][$s->id_unik] = array(
								'id' => $cek_id
							);
						} else {
							if (empty($data_all['sasaran'][$s->id_unik]['id'])) {
								$ret['sasaran_error'][] = $s;
								continue;
							}
							$data_db['id_unik_indikator'] = $s->id_unik_indikator;
							$data_db['id_sasaran'] = $data_all['sasaran'][$s->id_unik]['id'];

							$cek_id = $wpdb->get_var($wpdb->prepare("
								SELECT
									id
								FROM esakip_cascading_opd_sasaran
								WHERE id_skpd=%d
									AND id_jadwal=%d
									AND id_unik=%s
									AND id_unik_indikator=%s
							", $s->id_unit, $s->id_jadwal, $s->id_unik, $s->id_unik_indikator));
						}
						if (!empty($cek_id)) {
							$wpdb->update('esakip_cascading_opd_sasaran', $data_db, array('id' => $cek_id));
						} else {
							$wpdb->insert('esakip_cascading_opd_sasaran', $data_db);

							if (empty($s->id_unik_indikator)) {
								$data_all['sasaran'][$s->id_unik]['id'] = $wpdb->insert_id;
							}
						}

						//Simpan data satker dan pokin 
						$wpdb->update('esakip_data_pegawai_cascading', array('active' => 0), array(
							'id_data' => $s->id_unik,
							'jenis_data' => 2,
							'id_skpd' => $_POST['id_skpd'],
							'id_jadwal' => $id_jadwal
						));
						$wpdb->update('esakip_data_pokin_cascading', array('active' => 0), array(
							'id_data' => $s->id_unik,
							'jenis_data' => 2,
							'id_skpd' => $_POST['id_skpd'],
							'id_jadwal' => $id_jadwal
						));

						if (!empty($s->pelaksana_renstra)) {
							foreach ($s->pelaksana_renstra as $pelaksana) {
								$wpdb->insert('esakip_data_pegawai_cascading', array(
									'id_satker' => $pelaksana->id_satker ?? '',
									'nama_satker' => $pelaksana->nama_satker ?? '',
									'jabatan' => $pelaksana->jabatan ?? '',
									'nip' => $pelaksana->nip ?? '',
									'nama' => $pelaksana->nama ?? '',
									'jenis_data' => 2,
									'id_data' => $s->id_unik,
									'id_jadwal' => $id_jadwal,
									'id_skpd' => $_POST['id_skpd'],
									'active' => 1,
									'created_at' => current_time('mysql')
								));
							}
						}
						if (!empty($s->pokin_renstra)) {
							foreach ($s->pokin_renstra as $pokin) {
								$wpdb->insert('esakip_data_pokin_cascading', array(
									'id_pokin'   => $pokin->id_pokin ?? '',
									'nama_pokin' => $pokin->label ?? '',
									'indikator'	 => $pokin->indikator ?? '',
									'jenis_data' => 2,
									'id_data' => $s->id_unik,
									'id_jadwal' => $id_jadwal,
									'id_skpd' => $_POST['id_skpd'],
									'active' => 1,
									'created_at' => current_time('mysql')
								));
							}
						}
					}
					// PROGRAM
					$_POST['jenis'] = 'program_renstra';
					$ret['program'] = $this->get_tujuan_sasaran_cascading(true);

					$wpdb->update('esakip_cascading_opd_program', array('active' => 0), array(
						'active' => 1,
						'id_skpd' => $_POST['id_skpd'],
						'id_jadwal' => $id_jadwal
					));
					$ret['program_error'] = array();
					foreach ($ret['program']['data'] as $p) {
						if (empty($data_all['sasaran'][$p->kode_sasaran])) {
							$ret['program_error'][] = $p;
							continue;
						}
						$data_db = array(
							'id_jadwal'	=> $p->id_jadwal,
							'id_skpd'	=> $p->id_unit,
							'id_sasaran'	=> $data_all['sasaran'][$p->kode_sasaran]['id'],
							'id_unik'	=> $p->id_unik,
							'no_urut'	=> $p->kode_program,
							'program'	=> str_replace($p->kode_program, '', $p->nama_program),
							'indikator'	=> $p->indikator,
							'active'	=> 1,
							'created_at'	=> current_time('mysql')
						);
						if (empty($p->id_unik_indikator)) {
							$cek_id = $wpdb->get_var($wpdb->prepare("
								SELECT
									id
								FROM esakip_cascading_opd_program
								WHERE id_skpd=%d
									AND id_jadwal=%d
									AND id_unik=%s
									AND id_unik_indikator is NULL
							", $p->id_unit, $p->id_jadwal, $p->id_unik));
							$data_all['program'][$p->id_unik] = array(
								'id' => $cek_id
							);
						} else {
							if (empty($data_all['program'][$p->id_unik]['id'])) {
								$ret['program_error'][] = $p;
								continue;
							}
							$data_db['id_unik_indikator'] = $p->id_unik_indikator;
							$data_db['id_program'] = $data_all['program'][$p->id_unik]['id'];

							$cek_id = $wpdb->get_var($wpdb->prepare("
								SELECT
									id
								FROM esakip_cascading_opd_program
								WHERE id_skpd=%d
									AND id_jadwal=%d
									AND id_unik=%s
									AND id_unik_indikator=%s
							", $p->id_unit, $p->id_jadwal, $p->id_unik, $p->id_unik_indikator));
						}
						if (!empty($cek_id)) {
							$wpdb->update('esakip_cascading_opd_program', $data_db, array('id' => $cek_id));
						} else {
							$wpdb->insert('esakip_cascading_opd_program', $data_db);

							if (empty($p->id_unik_indikator)) {
								$data_all['program'][$p->id_unik]['id'] = $wpdb->insert_id;
							}
						}

						//Simpan data satker dan pokin 
						$wpdb->update('esakip_data_pegawai_cascading', array('active' => 0), array(
							'id_data' => $p->id_unik,
							'jenis_data' => 3,
							'id_skpd' => $_POST['id_skpd'],
							'id_jadwal' => $id_jadwal
						));
						$wpdb->update('esakip_data_pokin_cascading', array('active' => 0), array(
							'id_data' => $p->id_unik,
							'jenis_data' => 3,
							'id_skpd' => $_POST['id_skpd'],
							'id_jadwal' => $id_jadwal
						));

						if (!empty($p->pelaksana_renstra)) {
							foreach ($p->pelaksana_renstra as $pelaksana) {
								$wpdb->insert('esakip_data_pegawai_cascading', array(
									'id_satker' => $pelaksana->id_satker ?? '',
									'nama_satker' => $pelaksana->nama_satker ?? '',
									'jabatan' => $pelaksana->jabatan ?? '',
									'nip' => $pelaksana->nip ?? '',
									'nama' => $pelaksana->nama ?? '',
									'jenis_data' => 3,
									'id_data' => $p->id_unik,
									'id_jadwal' => $id_jadwal,
									'id_skpd' => $_POST['id_skpd'],
									'active' => 1,
									'created_at' => current_time('mysql')
								));
							}
						}
						if (!empty($p->pokin_renstra)) {
							foreach ($p->pokin_renstra as $pokin) {
								$wpdb->insert('esakip_data_pokin_cascading', array(
									'id_pokin'   => $pokin->id_pokin ?? '',
									'nama_pokin' => $pokin->label ?? '',
									'indikator'	 => $pokin->indikator ?? '',
									'jenis_data' => 3,
									'id_data' => $p->id_unik,
									'id_jadwal' => $id_jadwal,
									'id_skpd' => $_POST['id_skpd'],
									'active' => 1,
									'created_at' => current_time('mysql')
								));
							}
						}
					}

					//KEGIATAN
					$_POST['jenis'] = 'kegiatan_renstra';
					$ret['kegiatan'] = $this->get_tujuan_sasaran_cascading(true);

					$wpdb->update('esakip_cascading_opd_kegiatan', array('active' => 0), array(
						'active' => 1,
						'id_skpd' => $_POST['id_skpd'],
						'id_jadwal' => $id_jadwal
					));
					$ret['kegiatan_error'] = array();
					foreach ($ret['kegiatan']['data'] as $k) {
						if (empty($data_all['program'][$k->kode_program])) {
							$ret['kegiatan_error'][] = $k;
							continue;
						}
						$data_db = array(
							'id_jadwal'	=> $k->id_jadwal,
							'id_skpd'	=> $k->id_unit,
							'id_program'	=> $data_all['program'][$k->kode_program]['id'],
							'id_unik'	=> $k->id_unik,
							'no_urut'	=> $k->kode_giat,
							'kegiatan'	=> str_replace($k->kode_giat, '', $k->nama_giat),
							'indikator'	=> $k->indikator,
							'active'	=> 1,
							'created_at'	=> current_time('mysql')
						);
						if (empty($k->id_unik_indikator)) {
							$cek_id = $wpdb->get_var($wpdb->prepare("
								SELECT
									id
								FROM esakip_cascading_opd_kegiatan
								WHERE id_skpd=%d
									AND id_jadwal=%d
									AND id_unik=%s
									AND id_unik_indikator is NULL
							", $k->id_unit, $k->id_jadwal, $k->id_unik));
							$data_all['kegiatan'][$k->id_unik] = array(
								'id' => $cek_id
							);
						} else {
							if (empty($data_all['kegiatan'][$k->id_unik]['id'])) {
								$ret['kegiatan_error'][] = $k;
								continue;
							}
							$data_db['id_unik_indikator'] = $k->id_unik_indikator;
							$data_db['id_giat'] = $data_all['kegiatan'][$k->id_unik]['id'];

							$cek_id = $wpdb->get_var($wpdb->prepare("
								SELECT
									id
								FROM esakip_cascading_opd_kegiatan
								WHERE id_skpd=%d
									AND id_jadwal=%d
									AND id_unik=%s
									AND id_unik_indikator=%s
							", $k->id_unit, $k->id_jadwal, $k->id_unik, $k->id_unik_indikator));
						}
						if (!empty($cek_id)) {
							$wpdb->update('esakip_cascading_opd_kegiatan', $data_db, array('id' => $cek_id));
						} else {
							$wpdb->insert('esakip_cascading_opd_kegiatan', $data_db);

							if (empty($k->id_unik_indikator)) {
								$data_all['kegiatan'][$k->id_unik]['id'] = $wpdb->insert_id;
							}
						}

						//Simpan data satker dan pokin 
						$wpdb->update('esakip_data_pegawai_cascading', array('active' => 0), array(
							'id_data' => $k->id_unik,
							'jenis_data' => 4,
							'id_skpd' => $_POST['id_skpd'],
							'id_jadwal' => $id_jadwal
						));
						$wpdb->update('esakip_data_pokin_cascading', array('active' => 0), array(
							'id_data' => $k->id_unik,
							'jenis_data' => 4,
							'id_skpd' => $_POST['id_skpd'],
							'id_jadwal' => $id_jadwal
						));

						if (!empty($k->pelaksana_renstra)) {
							foreach ($k->pelaksana_renstra as $pelaksana) {
								$wpdb->insert('esakip_data_pegawai_cascading', array(
									'id_satker' => $pelaksana->id_satker ?? '',
									'nama_satker' => $pelaksana->nama_satker ?? '',
									'jabatan' => $pelaksana->jabatan ?? '',
									'nip' => $pelaksana->nip ?? '',
									'nama' => $pelaksana->nama ?? '',
									'jenis_data' => 4,
									'id_data' => $k->id_unik,
									'id_jadwal' => $id_jadwal,
									'id_skpd' => $_POST['id_skpd'],
									'active' => 1,
									'created_at' => current_time('mysql')
								));
							}
						}
						if (!empty($k->pokin_renstra)) {
							foreach ($k->pokin_renstra as $pokin) {
								$wpdb->insert('esakip_data_pokin_cascading', array(
									'id_pokin'   => $pokin->id_pokin ?? '',
									'nama_pokin' => $pokin->label ?? '',
									'indikator'	 => $pokin->indikator ?? '',
									'jenis_data' => 4,
									'id_data' => $k->id_unik,
									'id_jadwal' => $id_jadwal,
									'id_skpd' => $_POST['id_skpd'],
									'active' => 1,
									'created_at' => current_time('mysql')
								));
							}
						}
					}

					//SUB KEGIATAN
					$_POST['jenis'] = 'sub_giat_renstra';
					$ret['sub_giat'] = $this->get_tujuan_sasaran_cascading(true);

					$wpdb->update('esakip_cascading_opd_sub_giat', array('active' => 0), array(
						'active' => 1,
						'id_skpd' => $_POST['id_skpd'],
						'id_jadwal' => $id_jadwal
					));
					$ret['sub_giat_error'] = array();
					foreach ($ret['sub_giat']['data'] as $g) {
						if (empty($data_all['kegiatan'][$g->kode_kegiatan])) {
							$ret['sub_giat_error'][] = $g;
							continue;
						}
						$data_db = array(
							'id_jadwal'	=> $g->id_jadwal,
							'id_skpd'	=> $g->id_unit,
							'id_giat'	=> $data_all['kegiatan'][$g->kode_kegiatan]['id'],
							'id_unik'	=> $g->id_unik,
							'no_urut'	=> $g->kode_sub_giat,
							'sub_giat'	=> str_replace($g->kode_sub_giat, '', $g->nama_sub_giat),
							'indikator'	=> $g->indikator,
							'active'	=> 1,
							'created_at'	=> current_time('mysql')
						);
						if (empty($g->id_unik_indikator)) {
							$cek_id = $wpdb->get_var($wpdb->prepare("
								SELECT
									id
								FROM esakip_cascading_opd_sub_giat
								WHERE id_skpd=%d
									AND id_jadwal=%d
									AND id_unik=%s
									AND id_unik_indikator is NULL
							", $g->id_unit, $g->id_jadwal, $g->id_unik));
							$data_all['sub_giat'][$g->id_unik] = array(
								'id' => $cek_id
							);
						} else {
							if (empty($data_all['sub_giat'][$g->id_unik]['id'])) {
								$ret['sub_giat_error'][] = $g;
								continue;
							}
							$data_db['id_unik_indikator'] = $g->id_unik_indikator;
							$data_db['id_sub_giat'] = $data_all['sub_giat'][$g->id_unik]['id'];

							$cek_id = $wpdb->get_var($wpdb->prepare("
								SELECT
									id
								FROM esakip_cascading_opd_sub_giat
								WHERE id_skpd=%d
									AND id_jadwal=%d
									AND id_unik=%s
									AND id_unik_indikator=%s
							", $g->id_unit, $g->id_jadwal, $g->id_unik, $g->id_unik_indikator));
						}
						if (!empty($cek_id)) {
							$wpdb->update('esakip_cascading_opd_sub_giat', $data_db, array('id' => $cek_id));
						} else {
							$wpdb->insert('esakip_cascading_opd_sub_giat', $data_db);

							if (empty($g->id_unik_indikator)) {
								$data_all['sub_giat'][$g->id_unik]['id'] = $wpdb->insert_id;
							}
						}

						//Simpan data satker dan pokin 
						$wpdb->update('esakip_data_pegawai_cascading', array('active' => 0), array(
							'id_data' => $g->id_unik,
							'jenis_data' => 5,
							'id_skpd' => $_POST['id_skpd'],
							'id_jadwal' => $id_jadwal
						));
						$wpdb->update('esakip_data_pokin_cascading', array('active' => 0), array(
							'id_data' => $g->id_unik,
							'jenis_data' => 5,
							'id_skpd' => $_POST['id_skpd'],
							'id_jadwal' => $id_jadwal
						));

						if (!empty($g->pelaksana_renstra)) {
							foreach ($g->pelaksana_renstra as $pelaksana) {
								$wpdb->insert('esakip_data_pegawai_cascading', array(
									'id_satker' => $pelaksana->id_satker ?? '',
									'nama_satker' => $pelaksana->nama_satker ?? '',
									'jabatan' => $pelaksana->jabatan ?? '',
									'nip' => $pelaksana->nip ?? '',
									'nama' => $pelaksana->nama ?? '',
									'jenis_data' => 5,
									'id_data' => $g->id_unik,
									'id_jadwal' => $id_jadwal,
									'id_skpd' => $_POST['id_skpd'],
									'active' => 1,
									'created_at' => current_time('mysql')
								));
							}
						}
						if (!empty($g->pokin_renstra)) {
							foreach ($g->pokin_renstra as $pokin) {
								$wpdb->insert('esakip_data_pokin_cascading', array(
									'id_pokin'   => $pokin->id_pokin ?? '',
									'nama_pokin' => $pokin->label ?? '',
									'indikator'	 => $pokin->indikator ?? '',
									'jenis_data' => 5,
									'id_data' => $g->id_unik,
									'id_jadwal' => $id_jadwal,
									'id_skpd' => $_POST['id_skpd'],
									'active' => 1,
									'created_at' => current_time('mysql')
								));
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

	public function get_table_renaksi_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$jadwal_renaksi = $wpdb->get_results($wpdb->prepare("
                SELECT 
                    j.id,
                    j.nama_jadwal,
                    j.nama_jadwal_renstra,
                    j.tahun_anggaran,
                    j.lama_pelaksanaan,
                    j.tahun_selesai_anggaran,
                    r.id_jadwal_rpjmd
                FROM esakip_data_jadwal j
                INNER JOIN esakip_pengaturan_upload_dokumen r
                    ON r.id_jadwal_rpjmd = j.id
                WHERE j.tipe = %s
                  AND j.status = %d
                ORDER BY j.tahun_anggaran DESC", 'RPJMD', 1), ARRAY_A);

				if ($jadwal_renaksi) {
					foreach ($jadwal_renaksi as $jadwal_renaksi_pemda) {
						$tahun_anggaran_selesai = !empty($jadwal_renaksi_pemda['tahun_selesai_anggaran']) && $jadwal_renaksi_pemda['tahun_selesai_anggaran'] > 1
							? $jadwal_renaksi_pemda['tahun_selesai_anggaran']
							: $jadwal_renaksi_pemda['tahun_anggaran'] + $jadwal_renaksi_pemda['lama_pelaksanaan'];
						$input_renaksi_pemda = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Input Rencana Aksi Pemda ' . esc_html($jadwal_renaksi_pemda['nama_jadwal']) . ' Periode ' . $jadwal_renaksi_pemda['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
							'content' => '[input_rencana_aksi_pemda periode=' . intval($jadwal_renaksi_pemda['id']) . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));
					}

					$get_tujuan = $wpdb->get_results("
					    SELECT 
					        r.*,
					        r.id AS id_rpd,
					        p.*
					    FROM esakip_rpd_tujuan r
					    LEFT JOIN esakip_pohon_kinerja p
					        ON r.id_pokin = p.id
					    WHERE r.id_unik_indikator IS NULL
					      AND r.active = 1
					", ARRAY_A);

					if ($get_tujuan) {
						$counter = 1;
						$tbody = '';

						foreach ($get_tujuan as $tujuan) {
							$get_pokin = $wpdb->get_results(
								$wpdb->prepare("
							        SELECT
							            p.id,
							            p.label AS pokin_label
							        FROM esakip_data_pokin_rhk_pemda AS o
							        INNER JOIN esakip_pohon_kinerja AS p 
							            ON o.id_pokin = p.id
							                AND o.level_pokin = p.level
							        WHERE o.id_tujuan = %d
							          AND o.level_rhk_pemda = 1
							          AND o.level_pokin = 1
							          AND o.active = 1
							          AND o.tahun_anggaran = %d
							          AND p.active = 1
							    ", $tujuan['id_rpd'], $tahun_anggaran),
								ARRAY_A
							);

							$label_pokin = '<ul style="margin:0;">';
							$get_id_pokin = [];

							if (!empty($get_pokin)) {
								foreach ($get_pokin as $pokin) {
									$label_pokin .= "<li>" . esc_html($pokin['pokin_label']) . "</li>";
									$get_id_pokin[] = $pokin['id'];
								}
							} else {
								$label_pokin .= "-";
							}
							$label_pokin .= '</ul>';

							$tbody .= "<tr>";
							$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
							$tbody .= "<td>" . esc_html($tujuan['nama_cascading']) . "</td>";
							$tbody .= "<td>" . esc_html($tujuan['tujuan_teks']) . "</td>";
							$tbody .= "<td>" . $label_pokin . "</td>";
							$tbody .= "<td class='text-center'>0</td>";

							$id_pokin = implode(',', $get_id_pokin);

							$btn = '<div class="btn-action-group">';
							$btn .= "<button style='height: 38px; width: 47px;' class='btn btn-sm btn-warning' onclick='edit_pokin_pemda(\"" . $tujuan['id_rpd'] . "\"); return false;' href='#' title='Edit Pohon Kinerja'><span class='dashicons dashicons-edit'></span></button>";
							$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . esc_url($this->functions->add_param_get($input_renaksi_pemda['url'], '&tahun=' . $tahun_anggaran . '&id_tujuan=' . intval($tujuan['id_rpd']) . '&id_pokin=' . esc_attr($id_pokin) . '&id_periode=' . $_POST['id_jadwal'])) . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
							$btn .= '</div>';

							$tbody .= "<td class='text-center'>" . $btn . "</td>";
							$tbody .= "</tr>";
						}

						$ret['data'] = $tbody;
					} else {
						$ret['data'] = "<tr><td colspan='8' class='text-center'>Tidak ada data tersedia</td></tr>";
					}
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

	public function get_tujuan_sasaran_cascading_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					if (!empty($_POST['jenis'])) {
						$jenis = $_POST['jenis'];
					} else {
						throw new Exception("Jenis Data Kosong!", 1);
					}
					$parent_cascading = '';
					if ($jenis != 'sasaran' && $jenis != 'program') {
						if (!empty($_POST['parent_cascading'])) {
							$parent_cascading = $_POST['parent_cascading'];
						} else {
							throw new Exception("Parent Cascading Data Kosong!", 1);
						}

						if (!empty($_POST['tahun_anggaran'])) {
							$tahun_anggaran = $_POST['tahun_anggaran'];
						} else {
							throw new Exception("Tahun Anggaran Kosong!", 1);
						}
					}

					if ($jenis == 'sasaran' && empty($_POST['id_jadwal_rpjmd'])) {
						throw new Exception("Id Jadwal WpSipd Kosong!", 1);
					} else {
						$id_jadwal_rpjmd = $_POST['id_jadwal_rpjmd'];
					}

					$api_params = array(
						'action' => 'get_cascading_renstra',
						'api_key'	=> get_option('_crb_apikey_wpsipd'),
						'tahun_anggaran' => $tahun_anggaran,
						'jenis' => $jenis,
						'parent_cascading' => $parent_cascading
					);

					if ($jenis == 'sasaran') {
						$api_params['id_jadwal'] = $id_jadwal_rpjmd;
					}

					$response = wp_remote_post(get_option('_crb_url_server_sakip'), array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));

					$response = wp_remote_retrieve_body($response);

					$response = json_decode($response);

					$data = $response->data;

					echo json_encode([
						'status' => 'success',
						'jenis' => $_POST['jenis'],
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
	public function get_data_pokin_pemda()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$_prefix_pemda = $_where_pemda = '';
					if (!empty($_POST['tipe_pokin'])) {
						$_prefix_pemda = $_POST['tipe_pokin'] == "pemda" ? "_pemda" : "";
					}

					switch ($_POST['level']) {
						case '2':
							$id_parent = '
							(
								SELECT 
									id 
								FROM esakip_pohon_kinerja
								WHERE id=a.id
							) id_parent_1';
							break;

						case '3':
							$id_parent = '
							(
								SELECT 
									id 
								FROM esakip_pohon_kinerja
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja
									WHERE id=a.id
								)
							) id_parent_1,
							(
								SELECT 
									id 
								FROM esakip_pohon_kinerja
								WHERE id=a.id
							) id_parent_2';
							break;

						case '4':
							$id_parent = '
							(
								SELECT 
									id 
								FROM esakip_pohon_kinerja
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja
										WHERE id=(
											SELECT 
												parent 
											FROM esakip_pohon_kinerja
												WHERE id=a.id
										)
								)
							) id_parent_1,
							(
								SELECT 
									id 
								FROM esakip_pohon_kinerja
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja
									WHERE id=a.id
								)
							) id_parent_2,
							(
								SELECT 
									id 
								FROM esakip_pohon_kinerja
								WHERE id=a.id
							) id_parent_3';
							break;

						case '5':
							$id_parent = '
							(
								SELECT 
									id 
								FROM esakip_pohon_kinerja
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja
									WHERE id=(
										SELECT 
											parent 
										FROM esakip_pohon_kinerja
										WHERE id=(
											SELECT 
												parent 
											FROM esakip_pohon_kinerja
											WHERE id=a.id
										)
									)
								)
							) id_parent_1,
							(
								SELECT 
									id 
								FROM esakip_pohon_kinerja
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja
									WHERE id=(
										SELECT 
											parent 
										FROM esakip_pohon_kinerja
										WHERE id=a.id
									)
								)
							) id_parent_2,
							(
								SELECT 
									id 
								FROM esakip_pohon_kinerja
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja
									WHERE id=a.id
								)
							) id_parent_3,
							(
								SELECT 
									id 
								FROM esakip_pohon_kinerja
								WHERE id=a.id
							) id_parent_4';
							break;

						default:
							$id_parent = '';
							break;
					}

					if (is_array($_POST['parent'])) {
						$in_parent = implode(", ", $_POST['parent']);
						// $_where_parent = $wpdb->prepare(' AND a.parent IN (%s) ', $in_parent);
						$_where_parent = ' AND a.parent IN (' . $in_parent . ') ';
					} else {
						$_where_parent = $wpdb->prepare(' AND a.parent=%d ', $_POST['parent']);
					}

					$dataPokin = $wpdb->get_results($wpdb->prepare(
						"
						SELECT 
							a.id,
							a.label,
							a.parent,
							a.active,
							a.nomor_urut,
							b.id AS id_indikator,
							b.label_indikator_kinerja,
							b.nomor_urut as nomor_urut_indikator
						FROM esakip_pohon_kinerja a
						LEFT JOIN esakip_pohon_kinerja b ON a.id=b.parent AND a.level=b.level 
						WHERE 
							a.id_jadwal=%d AND
							a.level=%d AND 
							a.active=%d
							$_where_parent
						ORDER BY a.nomor_urut ASC, b.nomor_urut ASC",
						$_POST['id_jadwal'],
						$_POST['level'],
						1
					), ARRAY_A);
					$dataParent = array();
					if (!empty($id_parent)) {
						$dataParent = $wpdb->get_results($wpdb->prepare(
							"
								SELECT 
									" . $id_parent . "
								FROM esakip_pohon_kinerja a 
								WHERE 
									a.id_jadwal=%d AND 
									a.id=%d AND
									a.active=%d
								ORDER BY a.nomor_urut ASC",
							$_POST['id_jadwal'],
							$_POST['parent'],
							1
						), ARRAY_A);
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
								'nomor_urut' => $pokin['nomor_urut'],
								'indikator' => []
							];
						}

						if (!empty($pokin['id_indikator'])) {
							if (empty($data['data'][$pokin['id']]['indikator'][$pokin['id_indikator'] . ' ' . $pokin['nomor_urut_indikator']])) {
								$data['data'][$pokin['id']]['indikator'][$pokin['id_indikator'] . ' ' . $pokin['nomor_urut_indikator']] = [
									'id' => $pokin['id_indikator'],
									'label' => $pokin['label_indikator_kinerja'],
									'nomor_urut' => $pokin['nomor_urut_indikator']
								];
							}
						}
					}

					foreach ($dataParent as $v_parent) {
						if (empty($data['parent'][$v_parent['id_parent_1']])) {
							$data['parent'][$v_parent['id_parent_1']] = $v_parent['id_parent_1'];
						}

						if (empty($data['parent'][$v_parent['id_parent_2']])) {
							$data['parent'][$v_parent['id_parent_2']] = $v_parent['id_parent_2'];
						}

						if (empty($data['parent'][$v_parent['id_parent_3']])) {
							$data['parent'][$v_parent['id_parent_3']] = $v_parent['id_parent_3'];
						}

						if (empty($data['parent'][$v_parent['id_parent_4']])) {
							$data['parent'][$v_parent['id_parent_4']] = $v_parent['id_parent_4'];
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

	function get_pokin_renaksi_by_id()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil mendapatkan rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Tujuan tidak boleh kosong!';
				} else {
					$ret['data'] = $wpdb->get_row($wpdb->prepare('
                         SELECT
                            r.*
                        FROM esakip_rpd_tujuan AS r
                        WHERE r.id = %d 
                    ', $_POST['id']), ARRAY_A);

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
                                WHERE o.id_tujuan = %d
                                    AND o.level_rhk_pemda = 1
                                    AND o.level_pokin = 1
                                    AND o.active=1
                                    AND o.tahun_anggaran=%d
                                    AND p.active=1
                            ", $ret['data']['id'], $tahun_anggaran),
							ARRAY_A
						);
					} else {
						$ret['data']['pokin'] = array();
					}

					// print_r($ret['data']['jabatan']); die($wpdb->last_query);

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

	public function get_pokin_renaksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil mengambil data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$data_pokin = $wpdb->get_results($wpdb->prepare('
	                SELECT *
	                FROM esakip_pohon_kinerja
	                WHERE tahun_anggaran = %d 
				        AND id_jadwal=%d
				        AND level=1
				        AND active=1
	            ', $_POST['tahun'], $_POST['periode']), ARRAY_A);

				$ret['data'] = $data_pokin;
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

	function tambah_pokin_renaksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan rencana aksi!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Kegiatan Utama tidak boleh kosong!';
				} else if (empty($_POST['level'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Level kosong!';
				} else if (empty($_POST['id_pokin'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Pohon Kinerja tidak boleh kosong!';
				}

				if ($ret['status'] === 'success') {
					$data = array(
						'id' => $_POST['id'],
						'id_pokin' => $_POST['id_pokin'],
						'active' => 1,
						'update_at' => current_time('mysql'),
					);

					$cek_id = !empty($_POST['id']) ? $_POST['id'] : $wpdb->get_var($wpdb->prepare("
	                    SELECT id FROM esakip_rpd_tujuan WHERE id = %s", $_POST['id']));

					if (empty($cek_id)) {
						$wpdb->insert('esakip_rpd_tujuan', $data);
					} else {
						$wpdb->update('esakip_rpd_tujuan', $data, array('id' => $cek_id));
					}
					$get_id_pokin = $_POST['id_pokin'];

					$wpdb->update(
						'esakip_data_pokin_rhk_pemda',
						array('active' => 0),
						array(
							'tahun_anggaran' => $_POST['tahun_anggaran'],
							'level_rhk_pemda' => $_POST['level'],
							'id_tujuan' => $cek_id
						)
					);
					foreach ($get_id_pokin as $id_pokin_lvl_1) {
						if ($_POST['level'] == 1) {
							$level = 1;
						}

						$cek_id_pokin = $wpdb->get_var(
							$wpdb->prepare("
	                            SELECT 
	                                id 
	                            FROM esakip_data_pokin_rhk_pemda 
	                            WHERE tahun_anggaran = %d 
	                                AND level_rhk_pemda = %s 
	                                AND id_tujuan = %s 
	                            	AND id_pokin = %d
	                        ", $_POST['tahun_anggaran'], $_POST['level'], $cek_id, $id_pokin_lvl_1)
						);

						$data = array(
							'id_tujuan' => $cek_id,
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

	public function create_koneksi_pokin()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$id_skpd_koneksi = '';
					$input = json_decode(stripslashes($_POST['data']), true);

					if (
						empty($input['skpdKoneksi'])
						&& empty($input['skpdKoneksiLainnya'])
						&& empty($input['skpdKoneksiUptd'])
						&& empty($input['skpdKoneksiDesa'])
					) {
						throw new Exception("Data Crosscutting / Pelaksana Kegiatan tidak boleh kosong!", 1);
					}
					$id_skpd_koneksi = 0;
					if (!empty($input['skpdKoneksi'])) {
						$id_skpd_koneksi = $input['skpdKoneksi'];
					}
					$id_lembaga_lainnya_koneksi = 0;
					if (!empty($input['skpdKoneksiLainnya'])) {
						$id_lembaga_lainnya_koneksi = $input['skpdKoneksiLainnya'];
					}
					$id_uptd_koneksi = 0;
					if (!empty($input['skpdKoneksiUptd'])) {
						$id_uptd_koneksi = $input['skpdKoneksiUptd'];
					}
					$id_desa_koneksi = 0;
					if (!empty($input['skpdKoneksiDesa'])) {
						$id_desa_koneksi = $input['skpdKoneksiDesa'];
					}

					$keterangan_koneksi = '';
					if (!empty($input['keterangan_koneksi'])) {
						$keterangan_koneksi = $input['keterangan_koneksi'];
					}
					$parent_pokin_id = $input['parentKoneksi'];

					$inputan_baru = 0;
					$data_baru = array(
						'parent_pohon_kinerja' => $parent_pokin_id,
						'keterangan_koneksi' => $keterangan_koneksi,
						'active' => 1,
						'created_at' => current_time('mysql'),
						'updated_at' => current_time('mysql')
					);
					if (!empty($id_skpd_koneksi)) {
						foreach ($id_skpd_koneksi as $k => $v) {
							$data_cek_koneksi = $wpdb->get_var(
								$wpdb->prepare("
									SELECT 
										id
									FROM esakip_koneksi_pokin_pemda_opd
									WHERE parent_pohon_kinerja=%d
										AND id_skpd_koneksi=%s
										AND tipe=1
								", $parent_pokin_id, $v)
							);
							if (empty($data_cek_koneksi)) {
								$data_baru['id_skpd_koneksi'] = $v;
								$data_baru['tipe'] = 1;
								$data_baru['status_koneksi'] = 0;
								$wpdb->insert('esakip_koneksi_pokin_pemda_opd', $data_baru);
								$inputan_baru++;
							}
						}
					}
					if (!empty($id_lembaga_lainnya_koneksi)) {
						foreach ($id_lembaga_lainnya_koneksi as $k => $v) {
							$data_cek_koneksi = $wpdb->get_var(
								$wpdb->prepare("
									SELECT 
										id
									FROM esakip_koneksi_pokin_pemda_opd
									WHERE parent_pohon_kinerja=%d
										AND id_skpd_koneksi=%s
										AND tipe=2
								", $parent_pokin_id, $v)
							);

							if (empty($data_cek_koneksi)) {
								$data_baru['id_skpd_koneksi'] = $v;
								$data_baru['tipe'] = 2;
								$data_baru['status_koneksi'] = 1;
								$insert_koneksi = $wpdb->insert('esakip_koneksi_pokin_pemda_opd', $data_baru);
								$inputan_baru++;
							}
						}
					}
					if (!empty($id_uptd_koneksi)) {
						foreach ($id_uptd_koneksi as $k => $v) {
							$data_cek_koneksi = $wpdb->get_var(
								$wpdb->prepare("
									SELECT 
										id
									FROM esakip_koneksi_pokin_pemda_opd
									WHERE parent_pohon_kinerja=%d
										AND id_skpd_koneksi=%s
										AND tipe=2
								", $parent_pokin_id, $v)
							);

							if (empty($data_cek_koneksi)) {
								$data_baru['id_skpd_koneksi'] = $v;
								$data_baru['tipe'] = 3;
								$data_baru['status_koneksi'] = 1;
								$insert_koneksi = $wpdb->insert('esakip_koneksi_pokin_pemda_opd', $data_baru);
								$inputan_baru++;
							}
						}
					}
					if (!empty($id_desa_koneksi)) {
						foreach ($id_desa_koneksi as $k => $v) {
							$data_cek_koneksi = $wpdb->get_var(
								$wpdb->prepare("
									SELECT 
										id
									FROM esakip_koneksi_pokin_pemda_opd
									WHERE parent_pohon_kinerja=%d
										AND id_skpd_koneksi=%s
										AND tipe=2
								", $parent_pokin_id, $v)
							);

							if (empty($data_cek_koneksi)) {
								$data_baru['id_skpd_koneksi'] = $v;
								$data_baru['tipe'] = 4;
								$data_baru['nama_desa'] = $input['nama_desa'][$k];
								$data_baru['status_koneksi'] = 1;
								$insert_koneksi = $wpdb->insert('esakip_koneksi_pokin_pemda_opd', $data_baru);
								$inputan_baru++;
							}
						}
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses Koneksi Pohon Kinerja!',
						'success_new_input' => $inputan_baru,
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

	public function delete_koneksi_pokin()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$wpdb->update(
						'esakip_koneksi_pokin_pemda_opd',
						['active' => 0],
						['id' => $_POST['id']]
					);

					if ($data === false) {
						error_log("Error deleting koneksi pokin: " . $wpdb->last_error);
					}

					echo json_encode([
						'status' => true,
						'message' => 'Sukses Hapus Koneksi Pokin!'
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

	public function update_koneksi_pokin()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					$input = json_decode(stripslashes($_POST['data']), true);

					$data = [
						'keterangan_koneksi' 	=> $input['informasi_kegiatan'],
						'updated_at' 			=> current_time('mysql')
					];

					$cek_id = $wpdb->get_var(
						$wpdb->prepare('
							SELECT id
							FROM esakip_koneksi_pokin_pemda_opd
							WHERE id = %d
							  AND active = 1
						', $input['id'])
					);
					if (empty($cek_id)) {
						throw new Exception("Data Koneksi aktif tidak ditemukan!", 1);
					}
					$wpdb->update(
						'esakip_koneksi_pokin_pemda_opd',
						$data,
						['id' => $cek_id]
					);

					echo json_encode([
						'status' => true,
						'message' => 'Sukses Update Koneksi Pokin!'
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

	public function get_skpd_koneksi_pokin_by_id()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$data_skpd_by_parent_id = $wpdb->get_results($wpdb->prepare("
								SELECT 
									id_skpd_koneksi
								FROM esakip_koneksi_pokin_pemda_opd
								WHERE parent_pohon_kinerja=%d 
									AND active=%d
							", $_POST['id_parent_pokin'], 1),  ARRAY_A);

					echo json_encode([
						'status' => true,
						'message' => 'Sukses Mendapatkan Data Skpd Koneksi Pokin By Id!',
						'data' => $data_skpd_by_parent_id
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


	public function verify_koneksi_pokin_pemda_opd()
	{
		global $wpdb;
		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
					$input = json_decode(stripslashes($_POST['data']), true);

					$keterangan_tolak = '';
					if (!empty($input['idKoneksiPokin'])) {
						if (!empty($input['verify_koneksi_pokin']) || $input['verify_koneksi_pokin'] == 0) {
							$status_verify = $input['verify_koneksi_pokin'] == 1 ? 1 : 2;

							if ($input['verify_koneksi_pokin'] == 0) {
								if (!empty($input['keterangan_koneksi_tolak'])) {
									$keterangan_tolak = $input['keterangan_koneksi_tolak'];
								} else {
									throw new Exception("Ada yang kosong, wajib diisi!", 1);
								}
							}
						} else {
							throw new Exception("Verifikasi Gagal!", 1);
						}
					} else {
						throw new Exception("Ada data yang kosong!", 1);
					}

					$data_verify_koneksi_pokin = $wpdb->get_row(
						$wpdb->prepare("
						SELECT *
						FROM esakip_koneksi_pokin_pemda_opd
						WHERE id=%d
							AND active=1
					", $input['idKoneksiPokin']),
						ARRAY_A
					);

					$cek_pohon_kinerja = $wpdb->get_row(
						$wpdb->prepare("
						SELECT *
						FROM esakip_pohon_kinerja_opd
						WHERE id=%d
							AND active=1
					", $input['levelPokinKoneksi']),
						ARRAY_A
					);

					if (empty($cek_pohon_kinerja) && $input['verify_koneksi_pokin'] == 1) {
						throw new Exception("Verifikasi Gagal!", 1);
					}

					if (!empty($data_verify_koneksi_pokin)) {
						$opsi = array(
							'status_koneksi' => $status_verify,
							'updated_at' => current_time('mysql'),
							'parent_pohon_kinerja_koneksi' => 0,
							'keterangan_tolak' => $keterangan_tolak
						);
						if (!empty($cek_pohon_kinerja) && $input['verify_koneksi_pokin'] == 1) {
							$opsi['parent_pohon_kinerja_koneksi'] = $input['levelPokinKoneksi'];
						}

						$verify_koneksi_pokin = $wpdb->update(
							'esakip_koneksi_pokin_pemda_opd',
							$opsi,
							array(
								'id' => $input['idKoneksiPokin']
							)
						);

						if ($verify_koneksi_pokin === false) {
							error_log("Error Updating Koneksi Pokin Pemda dan Perangkat Daerah: " . $wpdb->last_error);
						}
					} else {
						throw new Exception("Verifikasi gagal!", 1);
					}

					echo json_encode([
						'status' => true,
						'message' => "Sukses Verifikasi Koneksi Pokin Pemerintah Daerah Dan Pokin Perangkat Daerah!\n"
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

	function get_parent_koneksi_pokin_pemda_opd($opsi)
	{
		global $wpdb;
		$data_ret = array();
		$table = 'esakip_pohon_kinerja';
		$table_koneksi_pokin = 'esakip_koneksi_pokin_pemda_opd';
		$where_skpd = '';
		$parent_level_1 = 0;
		if (!empty($opsi['tipe']) && $opsi['tipe'] == 'opd') {
			$table = 'esakip_pohon_kinerja_opd';
		}

		// data koneksi pokin
		$data_koneksi_pokin = $wpdb->get_row($wpdb->prepare("
			SELECT 
				* 
			FROM $table_koneksi_pokin
			WHERE id=%d 
				AND active=1 
			ORDER BY id
		", $opsi['id']), ARRAY_A);

		if (!empty($data_koneksi_pokin)) {
			$data_pokin = $wpdb->get_row($wpdb->prepare("
				SELECT 
					* 
				FROM $table
				WHERE id=%d 
					AND active=1 
					AND label_indikator_kinerja IS NULL
					AND level=%d
					AND id_jadwal=%d
				ORDER BY id
			", $opsi['id_parent'], $opsi['level'], $opsi['periode']), ARRAY_A);

			if (!empty($data_pokin)) {
				if (empty($data_ret[trim($data_pokin['level'])])) {
					$data_ret[trim($data_pokin['level'])] = [
						'id' => $data_pokin['id'],
						'label' => $data_pokin['label'],
						'level_pokin_parent' => $data_pokin['level'],
						'data' => []
					];
				}
				if ($data_pokin['level'] == 1) {
					$parent_level_1 = $data_pokin['id'];
				}

				if ($data_pokin['parent'] != 0) {
					$opsi['id_parent'] = $data_pokin['parent'];
					$opsi['level'] = $data_pokin['level'] - 1;
					$data_ret[trim($data_pokin['level'])]['data'] = $this->get_parent_koneksi_pokin_pemda_opd($opsi);
				}
			}
		}

		return $data_ret;
	}

	public function get_parent_1_koneksi_pokin_pemda_opd($opsi)
	{

		$data_ret = $this->get_parent_koneksi_pokin_pemda_opd($opsi);

		foreach ($data_ret as $v) {
			if (!empty($v['data'])) {
				foreach ($v['data'] as $vv) {
					if (!empty($vv['data'])) {
						foreach ($vv['data'] as $key => $vvv) {
							if (!empty($vvv['data'])) {
								foreach ($vvv['data'] as $key => $vvvv) {
									if (!empty($vvvv['data'])) {
										foreach ($vvvv['data'] as $key => $vvvvv) {
											if (!empty($vvvvv['data'])) {
												foreach ($vvvvv['data'] as $key => $vvvvvv) {
													if (!empty($vvvvvv['data'])) {
														foreach ($vvvvvv['data'] as $key => $vvvvvvv) {
															# code...
														}
													} else {
														$id = $vvvvvv['id'];
													}
												}
											} else {
												$id = $vvvvv['id'];
											}
										}
									} else {
										$id = $vvvv['id'];
									}
								}
							} else {
								$id = $vvv['id'];
							}
						}
					} else {
						$id = $vv['id'];
					}
				}
			} else {
				$id = $v['id'];
			}
		}
		return array(
			'id' => $id,
			'data' => $data_ret
		);
	}

	public function edit_verify_koneksi_pokin_pemda()
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

					$data_koneksi_pokin = array();
					$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
					if (!empty($_prefix_opd) && $_prefix_opd == "_opd") {
						$data_koneksi_pokin = $wpdb->get_row($wpdb->prepare("
								SELECT 
									koneksi.parent_pohon_kinerja,
									koneksi.id_skpd_koneksi,
									koneksi.parent_pohon_kinerja_koneksi, 
									koneksi.status_koneksi,
									pk.label as label_pokin_pemda 
								FROM esakip_koneksi_pokin_pemda_opd as koneksi 
								JOIN esakip_pohon_kinerja as pk 
								ON koneksi.parent_pohon_kinerja = pk.id
								WHERE koneksi.id=%d 
									AND koneksi.active=%d
							", $_POST['id'], 1),  ARRAY_A);
					}

					if (empty($data_koneksi_pokin)) {
						throw new Exception("Data tidak ditemukan!", 1);
					}

					echo json_encode([
						'status' => true,
						'data_koneksi_pokin' => $data_koneksi_pokin
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

	function generatePokin()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil generate data!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_pokin_lv_1'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Pokin Level 1 tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_periode'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Periode tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_tujuan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Tujuan tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$get_pokin_lv_1 = $wpdb->get_results($wpdb->prepare("
					    SELECT 
					    	*
					    FROM esakip_pohon_kinerja
					    WHERE id_jadwal = %d
					        AND id IN (" . implode(',', array_fill(0, count($_POST['id_pokin_lv_1']), '%d')) . ")
					        AND parent = 0
					        AND level = 1
					        AND active = 1
					", array_merge([$_POST['id_periode']], $_POST['id_pokin_lv_1'])), ARRAY_A);

					$id_lv_1 = array_column($get_pokin_lv_1, 'id');
					$id_pokin_lv_1 = implode(',', array_fill(0, count($id_lv_1), '%d'));

					$get_pokin_lv_2 = [];
					if (!empty($id_lv_1)) {
						$get_pokin_lv_2 = $wpdb->get_results($wpdb->prepare("
					        SELECT 
					        	*
					        FROM esakip_pohon_kinerja
					        WHERE parent IN ($id_pokin_lv_1)
					            AND level = 2
					            AND active = 1
					    ", $id_lv_1), ARRAY_A);
					}

					foreach ($get_pokin_lv_2 as $pokin_lv_2) {
						// Pokin Level 2 Rencana Aksi Level 1
						$cek_id_lv1 = $wpdb->get_var($wpdb->prepare("
					        SELECT 
					        	id
					        FROM esakip_data_rencana_aksi_pemda
					        WHERE label = %s 
					            AND id_jadwal = %d 
					            AND tahun_anggaran = %d 
					            AND level = 1 
					            AND active = 1
					    ", $pokin_lv_2['label'], $_POST['id_periode'], $_POST['tahun_anggaran']));

						if (!$cek_id_lv1) {
							$wpdb->insert('esakip_data_rencana_aksi_pemda', [
								'label' => $pokin_lv_2['label'],
								'parent' => 0,
								'level' => 1,
								'tahun_anggaran' => $_POST['tahun_anggaran'],
								'id_jadwal' => $_POST['id_periode'],
								'id_tujuan' => $_POST['id_tujuan'],
								'created_at' => current_time('mysql'),
								'update_at' => current_time('mysql'),
								'active' => 1
							]);
							$parent_lv_1 = $wpdb->insert_id;
						} else {
							$parent_lv_1 = $cek_id_lv1;
							$wpdb->update('esakip_data_rencana_aksi_pemda', [
								'label' => $pokin_lv_2['label'],
								'id_tujuan' => $_POST['id_tujuan'],
							], ['id' => $parent_lv_1]);
						}

						// Indikator pokin level 2 rhk level 1 
						$get_indikator_pokin_lv_2 = $wpdb->get_results($wpdb->prepare("
					        SELECT 
					        	*
					        FROM esakip_pohon_kinerja
					        WHERE parent = %d
					            AND level = 2
					            AND active = 1
					            AND label_indikator_kinerja IS NOT NULL
					    ", $pokin_lv_2['id']), ARRAY_A);

						foreach ($get_indikator_pokin_lv_2 as $ind_lv_2) {
							$cek_id_indikator_lv2 = $wpdb->get_var($wpdb->prepare("
						        SELECT 
						        	id
						        FROM esakip_data_rencana_aksi_indikator_pemda
						        WHERE indikator = %s 
						            AND id_tujuan = %d 
						            AND tahun_anggaran = %d 
						            AND active = 1
						    ", $ind_lv_2['label_indikator_kinerja'], $_POST['id_tujuan'], $_POST['tahun_anggaran']));

							if (!$cek_id_indikator_lv2) {
								$parent_indikator_lv_1 = $wpdb->insert_id;
								$wpdb->insert('esakip_data_rencana_aksi_indikator_pemda', [
									'id_renaksi' => $parent_lv_1,
									'indikator' => $ind_lv_2['label_indikator_kinerja'],
									'tahun_anggaran' => $_POST['tahun_anggaran'],
									'id_tujuan' => $_POST['id_tujuan'],
									'target_awal' => 0,
									'target_akhir' => 0,
									'target_1' => 0,
									'target_2' => 0,
									'target_3' => 0,
									'target_4' => 0,
									'created_at' => current_time('mysql'),
									'update_at' => current_time('mysql'),
									'active' => 1
								]);
							} else {
								$parent_indikator_lv_1 = $cek_id_indikator_lv2;
								$wpdb->update('esakip_data_rencana_aksi_indikator_pemda', [
									'indikator' => $ind_lv_2['label_indikator_kinerja'],
									'id_tujuan' => $_POST['id_tujuan'],
									'tahun_anggaran' => $_POST['tahun_anggaran'],
									'active' => 1,
								], ['id' => $parent_indikator_lv_1]);
							}
						}
						$cek_koneksi_pokin_1 = $wpdb->get_var($wpdb->prepare("
					        SELECT 
					        	id
					        FROM esakip_data_pokin_rhk_pemda
					        WHERE id_rhk_pemda = %s 
					            AND id_tujuan = %d 
					            AND tahun_anggaran = %d 
					            AND id_pokin = %d 
					            AND level_rhk_pemda = 1
					            AND level_pokin = 2
					            AND active = 1
					    ", $parent_lv_1, $_POST['id_tujuan'], $_POST['tahun_anggaran'], $pokin_lv_2['id']));
						if (!$cek_koneksi_pokin_1) {
							$id_koneksi_pokin_1 = $wpdb->insert_id;
							$wpdb->insert('esakip_data_pokin_rhk_pemda', [
								'id_rhk_pemda' => $parent_lv_1,
								'level_rhk_pemda' => 1,
								'level_pokin' => 2,
								'id_pokin' => $pokin_lv_2['id'],
								'tahun_anggaran' => $_POST['tahun_anggaran'],
								'id_tujuan' => $_POST['id_tujuan'],
								'created_at' => current_time('mysql'),
								'update_at' => current_time('mysql'),
								'active' => 1
							]);
						} else {
							$id_koneksi_pokin_1 = $cek_koneksi_pokin_1;
							$wpdb->update('esakip_data_pokin_rhk_pemda', [
								'id_rhk_pemda' => $parent_lv_1,
								'level_rhk_pemda' => 1,
								'id_tujuan' => $_POST['id_tujuan'],
								'tahun_anggaran' => $_POST['tahun_anggaran'],
								'active' => 1,
							], ['id' => $id_koneksi_pokin_1]);
						}
						// Pokin Level 3 Rencana Aksi Level 2
						$get_pokin_lv_3 = $wpdb->get_results($wpdb->prepare("
					        SELECT 
					        	*
					        FROM esakip_pohon_kinerja
					        WHERE parent = %d
					            AND level = 3
					            AND active = 1
					    ", $pokin_lv_2['id']), ARRAY_A);

						foreach ($get_pokin_lv_3 as $pokin_lv_3) {
							$cek_id_lv2 = $wpdb->get_var($wpdb->prepare("
					            SELECT 
					            	id
					            FROM esakip_data_rencana_aksi_pemda
					            WHERE label = %s 
					                AND id_jadwal = %d 
					                AND tahun_anggaran = %d 
					                AND level = 2 
					                AND active = 1
					        ", $pokin_lv_3['label'], $_POST['id_periode'], $_POST['tahun_anggaran']));

							if (!$cek_id_lv2) {
								$wpdb->insert('esakip_data_rencana_aksi_pemda', [
									'label' => $pokin_lv_3['label'],
									'parent' => $parent_lv_1,
									'level' => 2,
									'tahun_anggaran' => $_POST['tahun_anggaran'],
									'id_jadwal' => $_POST['id_periode'],
									'id_tujuan' => $_POST['id_tujuan'],
									'created_at' => current_time('mysql'),
									'update_at' => current_time('mysql'),
									'active' => 1
								]);
								$parent_lv_2 = $wpdb->insert_id;
							} else {
								$parent_lv_2 = $cek_id_lv2;
								$wpdb->update('esakip_data_rencana_aksi_pemda', [
									'label' => $pokin_lv_3['label'],
									'id_tujuan' => $_POST['id_tujuan'],
								], ['id' => $parent_lv_2]);
							}

							// Indikator pokin level 3 rhk level 2 
							$get_indikator_pokin_lv_3 = $wpdb->get_results($wpdb->prepare("
						        SELECT 
						        	*
						        FROM esakip_pohon_kinerja
						        WHERE parent = %d
						            AND level = 3
						            AND active = 1
						            AND label_indikator_kinerja IS NOT NULL
						    ", $pokin_lv_3['id']), ARRAY_A);

							foreach ($get_indikator_pokin_lv_3 as $ind_lv_3) {
								$cek_id_indikator_lv3 = $wpdb->get_var($wpdb->prepare("
							        SELECT 
							        	id
							        FROM esakip_data_rencana_aksi_indikator_pemda
							        WHERE indikator = %s 
							            AND id_tujuan = %d 
							            AND tahun_anggaran = %d 
							            AND active = 1
							    ", $ind_lv_3['label_indikator_kinerja'], $_POST['id_tujuan'], $_POST['tahun_anggaran']));

								if (!$cek_id_indikator_lv3) {
									$parent_indikator_lv_2 = $wpdb->insert_id;
									$wpdb->insert('esakip_data_rencana_aksi_indikator_pemda', [
										'id_renaksi' => $parent_lv_2,
										'indikator' => $ind_lv_3['label_indikator_kinerja'],
										'target_awal' => 0,
										'target_akhir' => 0,
										'target_1' => 0,
										'target_2' => 0,
										'target_3' => 0,
										'target_4' => 0,
										'tahun_anggaran' => $_POST['tahun_anggaran'],
										'id_tujuan' => $_POST['id_tujuan'],
										'created_at' => current_time('mysql'),
										'update_at' => current_time('mysql'),
										'active' => 1
									]);
								} else {
									$parent_indikator_lv_2 = $cek_id_indikator_lv3;
									$wpdb->update('esakip_data_rencana_aksi_indikator_pemda', [
										'indikator' => $ind_lv_3['label_indikator_kinerja'],
										'id_tujuan' => $_POST['id_tujuan'],
										'tahun_anggaran' => $_POST['tahun_anggaran'],
										'active' => 1,
									], ['id' => $parent_indikator_lv_2]);
								}
							}


							$cek_koneksi_pokin_2 = $wpdb->get_var($wpdb->prepare("
						        SELECT 
						        	id
						        FROM esakip_data_pokin_rhk_pemda
						        WHERE id_rhk_pemda = %s 
						            AND id_tujuan = %d 
						            AND tahun_anggaran = %d 
						            AND id_pokin = %d 
						            AND level_rhk_pemda = 2
						            AND level_pokin = 3
						            AND active = 1
						    ", $parent_lv_2, $_POST['id_tujuan'], $_POST['tahun_anggaran'], $pokin_lv_3['id']));
							if (!$cek_koneksi_pokin_2) {
								$id_koneksi_pokin_2 = $wpdb->insert_id;
								$wpdb->insert('esakip_data_pokin_rhk_pemda', [
									'id_rhk_pemda' => $parent_lv_2,
									'level_rhk_pemda' => 2,
									'level_pokin' => 3,
									'id_pokin' => $pokin_lv_3['id'],
									'tahun_anggaran' => $_POST['tahun_anggaran'],
									'id_tujuan' => $_POST['id_tujuan'],
									'created_at' => current_time('mysql'),
									'update_at' => current_time('mysql'),
									'active' => 1
								]);
							} else {
								$id_koneksi_pokin_2 = $cek_koneksi_pokin_2;
								$wpdb->update('esakip_data_pokin_rhk_pemda', [
									'id_rhk_pemda' => $parent_lv_2,
									'level_rhk_pemda' => 2,
									'level_pokin' => 3,
									'id_pokin' => $pokin_lv_3['id'],
									'id_tujuan' => $_POST['id_tujuan'],
									'tahun_anggaran' => $_POST['tahun_anggaran'],
									'active' => 1,
								], ['id' => $id_koneksi_pokin_2]);
							}

							// Pokin Level 4 Rencana Aksi Level 3
							$get_pokin_lv_4 = $wpdb->get_results($wpdb->prepare("
					            SELECT 
					            	*
					            FROM esakip_pohon_kinerja
					            WHERE parent = %d
					                AND level = 4
					                AND active = 1
					        ", $pokin_lv_3['id']), ARRAY_A);

							foreach ($get_pokin_lv_4 as $pokin_lv_4) {
								$cek_id_lv3 = $wpdb->get_var($wpdb->prepare("
					                SELECT 
					                	id
					                FROM esakip_data_rencana_aksi_pemda
					                WHERE label = %s 
					                    AND id_jadwal = %d 
					                    AND tahun_anggaran = %d 
					                    AND level = 3 
					                    AND active = 1
					            ", $pokin_lv_4['label'], $_POST['id_periode'], $_POST['tahun_anggaran']));

								if (!$cek_id_lv3) {
									$wpdb->insert('esakip_data_rencana_aksi_pemda', [
										'label' => $pokin_lv_4['label'],
										'parent' => $parent_lv_2,
										'level' => 3,
										'tahun_anggaran' => $_POST['tahun_anggaran'],
										'id_jadwal' => $_POST['id_periode'],
										'id_tujuan' => $_POST['id_tujuan'],
										'created_at' => current_time('mysql'),
										'update_at' => current_time('mysql'),
										'active' => 1
									]);
									$parent_lv_3 = $wpdb->insert_id;
								} else {
									$parent_lv_3 = $cek_id_lv3;
									$wpdb->update('esakip_data_rencana_aksi_pemda', [
										'label' => $pokin_lv_4['label'],
										'id_tujuan' => $_POST['id_tujuan'],
									], ['id' => $parent_lv_3]);
								}

								// Indikator pokin level 4 rhk level 3 
								$get_indikator_pokin_lv_4 = $wpdb->get_results($wpdb->prepare("
							        SELECT 
							        	*
							        FROM esakip_pohon_kinerja
							        WHERE parent = %d
							            AND level = 4
							            AND active = 1
							            AND label_indikator_kinerja IS NOT NULL
							    ", $pokin_lv_4['id']), ARRAY_A);

								$get_koneksi_id_skpd = $wpdb->get_results($wpdb->prepare("
									SELECT 
										koneksi.* ,
										koneksi.id_skpd_koneksi as koneksi_id_skpd,
										pk.*
									FROM esakip_koneksi_pokin_pemda_opd koneksi
									LEFT JOIN esakip_pohon_kinerja_opd as pk ON koneksi.parent_pohon_kinerja_koneksi = pk.id
									WHERE koneksi.parent_pohon_kinerja=%d 
										AND koneksi.active=1 
									ORDER BY koneksi.id
								", $pokin_lv_4['id']), ARRAY_A);
								$id_skpd = '';
								foreach ($get_koneksi_id_skpd as $get_id_skpd) {
									$id_skpd = $get_id_skpd['koneksi_id_skpd'];
								}
								// print_r($id_skpd); die($wpdb->last_query);
								foreach ($get_indikator_pokin_lv_4 as $ind_lv_4) {
									$cek_id_indikator_lv4 = $wpdb->get_var($wpdb->prepare("
								        SELECT 
								        	id
								        FROM esakip_data_rencana_aksi_indikator_pemda
								        WHERE indikator = %s 
								            AND id_tujuan = %d 
								            AND tahun_anggaran = %d 
								            AND active = 1
								    ", $ind_lv_4['label_indikator_kinerja'], $_POST['id_tujuan'], $_POST['tahun_anggaran']));

									if (!$cek_id_indikator_lv4) {
										$parent_indikator_lv_3 = $wpdb->insert_id;
										$wpdb->insert('esakip_data_rencana_aksi_indikator_pemda', [
											'id_renaksi' => $parent_lv_3,
											'indikator' => $ind_lv_4['label_indikator_kinerja'],
											'target_awal' => 0,
											'target_akhir' => 0,
											'target_1' => 0,
											'target_2' => 0,
											'target_3' => 0,
											'target_4' => 0,
											'tahun_anggaran' => $_POST['tahun_anggaran'],
											'id_tujuan' => $_POST['id_tujuan'],
											'id_skpd' => $id_skpd,
											'created_at' => current_time('mysql'),
											'update_at' => current_time('mysql'),
											'active' => 1
										]);
									} else {
										$parent_indikator_lv_3 = $cek_id_indikator_lv4;
										$wpdb->update('esakip_data_rencana_aksi_indikator_pemda', [
											'indikator' => $ind_lv_4['label_indikator_kinerja'],
											'id_tujuan' => $_POST['id_tujuan'],
											'tahun_anggaran' => $_POST['tahun_anggaran'],
											'id_skpd' => $id_skpd,
											'active' => 1,
										], ['id' => $parent_indikator_lv_3]);
									}
								}

								$cek_koneksi_pokin_3 = $wpdb->get_var($wpdb->prepare("
						        SELECT 
						        	id
						        FROM esakip_data_pokin_rhk_pemda
						        WHERE id_rhk_pemda = %s 
						            AND id_tujuan = %d 
						            AND tahun_anggaran = %d 
						            AND id_pokin = %d 
						            AND level_rhk_pemda = 3
						            AND level_pokin = 4
						            AND active = 1
						    ", $parent_lv_3, $_POST['id_tujuan'], $_POST['tahun_anggaran'], $pokin_lv_4['id']));
								if (!$cek_koneksi_pokin_3) {
									$id_koneksi_pokin_3 = $wpdb->insert_id;
									$wpdb->insert('esakip_data_pokin_rhk_pemda', [
										'id_rhk_pemda' => $parent_lv_3,
										'level_rhk_pemda' => 3,
										'level_pokin' => 4,
										'id_pokin' => $pokin_lv_4['id'],
										'tahun_anggaran' => $_POST['tahun_anggaran'],
										'id_tujuan' => $_POST['id_tujuan'],
										'created_at' => current_time('mysql'),
										'update_at' => current_time('mysql'),
										'active' => 1
									]);
								} else {
									$id_koneksi_pokin_3 = $cek_koneksi_pokin_3;
									$wpdb->update('esakip_data_pokin_rhk_pemda', [
										'id_rhk_pemda' => $parent_lv_3,
										'level_rhk_pemda' => 3,
										'level_pokin' => 4,
										'id_pokin' => $pokin_lv_4['id'],
										'id_tujuan' => $_POST['id_tujuan'],
										'tahun_anggaran' => $_POST['tahun_anggaran'],
										'active' => 1,
									], ['id' => $id_koneksi_pokin_3]);
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

	public function upsert_lembaga_lainnya()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah data!',
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				// die(print_r($_POST));

				//jika id kosong insert baru, jika ada cek id ke db, lalu update
				if (!empty($_POST['id'])) {
					$wpdb->update(
						'esakip_data_lembaga_lainnya',
						array(
							'nama_lembaga' => $_POST['nama_lembaga'],
							'tahun_anggaran' => $_POST['tahun_anggaran'],
							'updated_at' => current_time('mysql')
						), //datanya
						array(
							'id' => $_POST['id']
						) //where nya (biasanya pake id)
					);
					$ret['message'] = 'Berhasil edit data!';
				} else {
					$wpdb->insert(
						'esakip_data_lembaga_lainnya',
						array(
							'nama_lembaga' => $_POST['nama_lembaga'],
							'tahun_anggaran' => $_POST['tahun_anggaran'],
							'created_at' => current_time('mysql'),
							'updated_at' => current_time('mysql'),
							'active' => 1
						) //data
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

	public function hapus_lembaga_lainnya_by_id()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => "Berhasil hapus data!"
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'id kosong!';
					die(json_encode($ret));
				}
				// die(print_r($_POST));

				$wpdb->update(
					'esakip_data_lembaga_lainnya',
					array('active' => 0,), //datanya
					array('id' => $_POST['id']) //where nya (biasanya pake id)
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

	public function get_lembaga_lainnya_by_id()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'id kosong!';
					die(json_encode($ret));
				}
				// die(print_r($_POST));
				$ret['data'] = $wpdb->get_row(
					$wpdb->prepare("
						SELECT *
						FROM esakip_data_lembaga_lainnya
						WHERE id = %d
					", $_POST['id']),
					ARRAY_A
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
	public function copy_data_lembaga_lainnya()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => "Data berhasil disalin!"
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$tahun_asal = intval($_POST['tahun_asal']);
				$tahun_tujuan = intval($_POST['tahun_tujuan']);

				if ($tahun_asal == $tahun_tujuan) {
					$ret['status']  = 'error';
					$ret['message'] = 'Tahun asal dan tahun tujuan tidak boleh sama!';
					die(json_encode($ret));
				}

				$table = 'esakip_data_lembaga_lainnya';

				$data_asal = $wpdb->get_results(
					$wpdb->prepare("SELECT nama_lembaga FROM $table WHERE tahun_anggaran = %d AND active = 1", $tahun_asal),
					ARRAY_A
				);

				if (empty($data_asal)) {
					$ret = array(
						'status' => 'error',
						'message' => 'Tidak ada data dari tahun ' . $tahun_asal
					);
					die(json_encode($ret));
				}

				$wpdb->update(
					'esakip_data_lembaga_lainnya',
					[
						'active' => 0,
						'updated_at' => current_time('mysql'),
					],
					['tahun_anggaran' => $tahun_tujuan]
				);

				foreach ($data_asal as $baris) {
					$wpdb->insert(
						'esakip_data_lembaga_lainnya',
						array(
							'nama_lembaga' => $baris['nama_lembaga'],
							'tahun_anggaran' => $tahun_tujuan,
							'created_at' => current_time('mysql'),
							'updated_at' => current_time('mysql'),
							'active' => 1
						)
					);
				}

				$ret['message'] = count($data_asal) . ' data berhasil disalin.';
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

	// =========================================================================
	// METHOD HANDLER RPJMD
	// =========================================================================

	/**
	 * The name of the database table for RPJMD.
	 * RPD (isu rpjpd, tujuan, sasaran, program).
	 * RPJMD (visi, misi, misi detail, tujuan, sasaran, program).
	 * @var string
	 */
	private $table_rpjmd_visi = 'esakip_rpjmd_visi';
	private $table_rpjmd_misi = 'esakip_rpjmd_misi';
	private $table_rpjmd_misi_detail = 'esakip_rpjmd_misi_detail';

	private $table_rpjpd_isu = 'esakip_rpjpd_isu'; // next updates
	private $table_rpd_tujuan = 'esakip_rpd_tujuan';
	private $table_rpd_sasaran = 'esakip_rpd_sasaran';
	private $table_rpd_program = 'esakip_rpd_program';

	public function get_all_rpd_by_id_jadwal($id)
	{
		$tujuan = $this->get_all_tujuan_by_id_jadwal($id);
		$sasaran = $this->get_all_sasaran_by_id_jadwal($id);
		$program = $this->get_all_program_by_id_jadwal($id);

		return [
			'tujuan' 	=> $tujuan,
			'sasaran' 	=> $sasaran,
			'program' 	=> $program
		];
	}

	public function get_all_rpjmd_by_id_jadwal($id)
	{
		$visi = $this->get_all_visi_rpjmd_by_id_jadwal($id);
		$misi = $this->get_all_misi_rpjmd_by_id_jadwal($id);
		$misi_detail = $this->get_all_misi_detail_rpjmd_by_id_jadwal($id);
		$tujuan = $this->get_all_tujuan_by_id_jadwal($id);
		$sasaran = $this->get_all_sasaran_by_id_jadwal($id);
		$program = $this->get_all_program_by_id_jadwal($id);

		return [
			'visi'   	=> $visi,
			'misi'   	=> $misi,
			'misi_detail' => $misi_detail,
			'tujuan' 	=> $tujuan,
			'sasaran' 	=> $sasaran,
			'program' 	=> $program
		];
	}

	private function get_all_visi_rpjmd_by_id_jadwal($id)
	{
		global $wpdb;

		return $wpdb->get_results(
			$wpdb->prepare("
				SELECT *
				FROM {$this->table_rpjmd_visi}
				WHERE id_jadwal = %d
				  AND active = 1
				ORDER BY id ASC
			", $id),
			ARRAY_A
		);
	}

	private function get_all_misi_detail_rpjmd_by_id_jadwal($id)
	{
		global $wpdb;

		return $wpdb->get_results(
			$wpdb->prepare("
				SELECT *
				FROM {$this->table_rpjmd_misi_detail}
				WHERE id_jadwal = %d
				  AND active = 1
				ORDER BY id ASC
			", $id),
			ARRAY_A
		);
	}

	private function get_all_misi_rpjmd_by_id_jadwal($id)
	{
		global $wpdb;

		return $wpdb->get_results(
			$wpdb->prepare("
				SELECT *
				FROM {$this->table_rpjmd_misi}
				WHERE id_jadwal = %d
				  AND active = 1
				ORDER BY id ASC
			", $id),
			ARRAY_A
		);
	}

	private function get_all_tujuan_by_id_jadwal($id)
	{
		global $wpdb;

		return $wpdb->get_results(
			$wpdb->prepare("
				SELECT *
				FROM {$this->table_rpd_tujuan}
				WHERE id_jadwal = %d
				  AND active = 1
				ORDER BY id ASC
			", $id),
			ARRAY_A
		);
	}

	private function get_all_sasaran_by_id_jadwal($id)
	{
		global $wpdb;

		return $wpdb->get_results(
			$wpdb->prepare("
				SELECT *
				FROM {$this->table_rpd_sasaran}
				WHERE id_jadwal = %d
				  AND active = 1
				ORDER BY id ASC
			", $id),
			ARRAY_A
		);
	}

	private function get_all_program_by_id_jadwal($id)
	{
		global $wpdb;

		return $wpdb->get_results(
			$wpdb->prepare("
				SELECT *
				FROM {$this->table_rpd_program}
				WHERE id_jadwal = %d
				  AND active = 1
				ORDER BY id ASC
			", $id),
			ARRAY_A
		);
	}

	/**
	 * API get all data rpd.
	 */
	public function get_all_rpd_by_id_jadwal_ajax()
	{
		try {
			$this->functions->validate($_POST, [
				'api_key'   => 'required|string',
				'id_jadwal' => 'required|string'
			]);

			if ($_POST['api_key'] !== get_option(ESAKIP_APIKEY)) {
				throw new Exception("API key tidak valid atau tidak ditemukan!", 401);
			}

			$valid_jadwal = $this->get_data_jadwal_by_id($_POST['id_jadwal']);
			if (!$valid_jadwal || $valid_jadwal['jenis_jadwal_khusus'] !== 'rpd') {
				throw new Exception("Jadwal tidak temukan didalam sistem!", 404);
			}

			$data_rpd = $this->get_all_rpd_by_id_jadwal($valid_jadwal['id']);

			if ($data_rpd) {
				echo json_encode([
					'status'  => true,
					'message' => 'Data berhasil ditemukan.',
					'data'    => $data_rpd
				]);
			} else {
				throw new Exception("Data RPD dengan id Jadwal = {$valid_jadwal['id']} tidak ditemukan.", 404);
			}
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

	public function get_all_rpjmd_by_id_jadwal_ajax()
	{
		try {
			$this->functions->validate($_POST, [
				'api_key'   => 'required|string',
				'id_jadwal' => 'required|string'
			]);

			if ($_POST['api_key'] !== get_option(ESAKIP_APIKEY)) {
				throw new Exception("API key tidak valid atau tidak ditemukan!", 401);
			}

			$valid_jadwal = $this->get_data_jadwal_by_id($_POST['id_jadwal']);
			if (!$valid_jadwal || $valid_jadwal['jenis_jadwal_khusus'] !== 'rpjmd') {
				throw new Exception("Jadwal tidak temukan didalam sistem!", 404);
			}

			$data = $this->get_all_rpjmd_by_id_jadwal($valid_jadwal['id']);

			if ($data) {
				echo json_encode([
					'status'  => true,
					'message' => 'Data berhasil ditemukan.',
					'data'    => $data
				]);
			} else {
				throw new Exception("Data RPJMD dengan id Jadwal = {$valid_jadwal['id']} tidak ditemukan.", 404);
			}
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
}
