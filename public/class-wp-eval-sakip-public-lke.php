<?php

require_once ESAKIP_PLUGIN_PATH . "/public/class-wp-eval-sakip-public-pohon-kinerja.php";
class Wp_Eval_Sakip_LKE extends Wp_Eval_Sakip_Pohon_Kinerja
{

	public function desain_lke_sakip($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/lke/wp-eval-sakip-desain-lke-sakip.php';
	}

	public function pengisian_lke_sakip($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/lke/wp-eval-sakip-pengisian-lke-sakip.php';
	}

	public function pengisian_lke_sakip_per_skpd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/lke/wp-eval-sakip-pengisian-lke-sakip-per-skpd.php';
	}

	public function list_kuesioner_menpan($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/lke/wp-eval-sakip-list-kuesioner-menpan.php';
	}

	public function list_kuesioner_mendagri($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/lke/wp-eval-sakip-list-kuesioner-mendagri.php';
	}

	public function kuesioner_menpan($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/lke/wp-eval-sakip-kuesioner-menpan.php';
	}

	public function kuesioner_mendagri($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/lke/wp-eval-sakip-kuesioner-mendagri.php';
	}

	public function input_kuesioner_menpan($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/lke/wp-eval-sakip-input-kuesioner-menpan.php';
	}

	public function input_kuesioner_mendagri($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/lke/wp-eval-sakip-input-kuesioner-mendagri.php';
	}

	public function list_kuesioner_dokumen($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-list_dokumen_kuesioner.php';
	}

	public function get_table_skpd_pengisian_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_jadwal'])) {
					$id_jadwal = $_POST['id_jadwal'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
					die(json_encode($ret));
				}

				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
					die(json_encode($ret));
				}

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

				// Retrieve unit data
				$units = $wpdb->get_results(
					$wpdb->prepare("
						SELECT 
							nama_skpd, 
							id_skpd, 
							kode_skpd
						FROM esakip_data_unit 
						WHERE tahun_anggaran=%d
						AND active=1 
						AND is_skpd=1 
						ORDER BY kode_skpd ASC
					", $tahun_anggaran_sakip),
					ARRAY_A
				);

				// Retrieve jadwal data
				$jadwal = $wpdb->get_row(
					$wpdb->prepare("
						SELECT *
						FROM esakip_data_jadwal
						WHERE id=%d
						AND status != 0
					", $id_jadwal),
					ARRAY_A
				);

				if (!empty($units) && !empty($jadwal)) {
					$tbody = '';
					$counter = 1;
					$total_nilai_usulan = 0;
					$total_nilai_penetapan = 0;

					// Retrieve komponen data once
					$komponen_list = $wpdb->get_results(
						$wpdb->prepare("
							SELECT * 
							FROM esakip_komponen
							WHERE id_jadwal = %d
								AND active = 1
							ORDER BY nomor_urut ASC
						", $id_jadwal),
						ARRAY_A
					);

					foreach ($units as $unit) {
						$nilai_usulan = 0;
						$nilai_penetapan = 0;
						$nilai_komponen = [];

						foreach ($komponen_list as $komponen) {
							$komponen_id = $komponen['id'];
							$nilai_komponen[$komponen_id] = 0;

							// Retrieve subkomponen data
							$subkomponen_list = $wpdb->get_results(
								$wpdb->prepare("
									SELECT * 
									FROM esakip_subkomponen
									WHERE id_komponen = %d
										AND active = 1
									ORDER BY nomor_urut ASC
								", $komponen_id),
								ARRAY_A
							);

							foreach ($subkomponen_list as $subkomponen) {
								$subkomponen_id = $subkomponen['id'];

								// Calculate nilai usulan
								$sum_nilai_usulan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT SUM(p.nilai_usulan)
										FROM esakip_pengisian_lke p
										INNER JOIN esakip_komponen_penilaian k on p.id_komponen_penilaian = k.id
										WHERE p.id_subkomponen = %d
											AND p.id_skpd = %d
											AND p.tahun_anggaran = %d
											AND k.active=1
									", $subkomponen_id, $unit['id_skpd'], $tahun_anggaran)
								);

								$count_nilai_usulan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT COUNT(k.id)
										FROM esakip_komponen_penilaian k
										WHERE k.id_subkomponen = %d
											AND k.active = 1
									", $subkomponen_id)
								);

								// Calculate nilai penetapan
								$sum_nilai_penetapan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT SUM(p.nilai_penetapan)
										FROM esakip_pengisian_lke p
										INNER JOIN esakip_komponen_penilaian k on p.id_komponen_penilaian = k.id
										WHERE p.id_subkomponen = %d
											AND p.id_skpd = %d
											AND p.tahun_anggaran = %d
											AND k.active=1
									", $subkomponen_id, $unit['id_skpd'], $tahun_anggaran)
								);

								$count_nilai_penetapan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT COUNT(k.id)
										FROM esakip_komponen_penilaian k
										WHERE k.id_subkomponen = %d
											AND k.active = 1
									", $subkomponen_id)
								);

								// rata-rata
								if ($subkomponen['metode_penilaian'] == 1) {
									if ($count_nilai_usulan > 0) {
										$nilai_usulan += ($sum_nilai_usulan / $count_nilai_usulan) * $subkomponen['bobot'];
									}

									if ($count_nilai_penetapan > 0) {
										$nilai_komponen[$komponen_id] += ($sum_nilai_penetapan / $count_nilai_penetapan) * $subkomponen['bobot'];
									}
									// akumulasi
								} else if ($subkomponen['metode_penilaian'] == 2) {
									if ($count_nilai_usulan > 0) {
										$nilai_usulan += $sum_nilai_usulan;
									}

									if ($count_nilai_penetapan > 0) {
										$nilai_komponen[$komponen_id] += $sum_nilai_penetapan;
									}
								}
							}
						}

						// Sum the component values for total nilai penetapan
						$total_nilai_penetapan_unit = array_sum($nilai_komponen);

						// Add the values to the overall totals
						$total_nilai_usulan += $nilai_usulan;
						$total_nilai_penetapan += $total_nilai_penetapan_unit;

						foreach ($nilai_komponen as $komponen_id => $nilai) {
							if (!isset($total_nilai_komponen[$komponen_id])) {
								$total_nilai_komponen[$komponen_id] = 0;
							}
							$total_nilai_komponen[$komponen_id] += $nilai;
						}

						// Generate detail page
						$detail_pengisian_lke = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Pengisian LKE ' . $unit['nama_skpd'] . ' ' . $jadwal['nama_jadwal'],
							'content' => '[pengisian_lke_sakip_per_skpd id_jadwal=' . $id_jadwal . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

						// Prepare action button
						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_pengisian_lke['url'] . '&id_skpd=' . $unit['id_skpd'] . '&id_jadwal=' . $id_jadwal . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						// Render tbody
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $unit['nama_skpd'] . "</td>";
						$tbody .= "<td class='text-center'>" . number_format($nilai_usulan, 2) . "</td>";
						foreach ($nilai_komponen as $komponen_id => $nilai) {
							$tbody .= "<td class='text-center'>" . number_format($nilai, 2) . "</td>";
						}
						$tbody .= "<td class='text-center'>" . number_format($total_nilai_penetapan_unit, 2) . "</td>";
						$tbody .= "<td>" . $btn . "</td>";
						$tbody .= "</tr>";
					}

					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
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


	public function get_table_pengisian_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Perangkat Daerah kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran kosong!';
				}

				if ($ret['status'] == 'error') {
					die(json_encode($ret));
				}
				$id_skpd = $_POST['id_skpd'];
				$id_jadwal = $_POST['id_jadwal'];
				$tahun_anggaran = $_POST['tahun_anggaran'];

				//get jadwal
				date_default_timezone_set('Asia/Jakarta'); // Adjust this if your server is set to a different timezone
				$dateTime = new DateTime();

				$data_jadwal = $wpdb->get_row(
					$wpdb->prepare("
						SELECT *
						FROM esakip_data_jadwal
						WHERE id=%d
					", $id_jadwal),
					ARRAY_A
				);
				if (!empty($data_jadwal)) {
					$started_at = trim($data_jadwal['started_at']);
					$end_at = trim($data_jadwal['end_at']);

					$started_at_dt = new DateTime($started_at);
					$end_at_dt = new DateTime($end_at);
					$jenis_jadwal = $data_jadwal['jenis_jadwal'];
					$tampil_nilai_penetapan = $data_jadwal['tampil_nilai_penetapan'];
					if ($data_jadwal['status'] == 2) {
						$prefix_history = '_history';
					} else {
						$prefix_history = null;
					}
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Data jadwal tidak ditemukan!';
					die(json_encode($ret));
				}

				//user authorize
				$current_user = wp_get_current_user();
				$admin_roles = array(
					'1' => 'administrator',
					'2' => 'admin_bappeda',
					'3' => 'admin_ortala'
				);
				$intersected_roles = array_intersect($admin_roles, $current_user->roles);
				$user_penilai = $this->get_user_penilai();
				$user_penilai[''] = '-';

				if (empty($prefix_history)) {
					$data_komponen = $wpdb->get_results(
						$wpdb->prepare("
						SELECT * 
						FROM esakip_komponen
						WHERE id_jadwal = %d
						  AND active = 1
						ORDER BY nomor_urut ASC
						", $id_jadwal),
						ARRAY_A
					);
				} else if (!empty($prefix_history)) {
					$data_komponen = $wpdb->get_results(
						$wpdb->prepare("
						SELECT * 
						FROM esakip_komponen_history
						WHERE id_jadwal = %d
						ORDER BY nomor_urut ASC
						", $id_jadwal),
						ARRAY_A
					);
				}

				$merged_data = array('debug' => array());

				if (!empty($data_komponen)) {
					$tbody = '';
					//jika user adalah admin atau skpd
					$can_verify = false;
					if (
						in_array("admin_ortala", $current_user->roles) ||
						in_array("admin_bappeda", $current_user->roles) ||
						in_array("administrator", $current_user->roles)
					) {
						$can_verify = true;
					}
					$total_nilai = 0;
					$total_nilai_penetapan = 0;
					$counter = 'A';

					foreach ($data_komponen as $komponen) {
						$tbody2 = "";
						$counter_isi = 1;
						$counter_sub = 'a';

						$total_nilai_kom = 0;
						$total_nilai_kom_penetapan = 0;
						$persentase_kom = 0;
						$persentase_kom_penetapan = 0;
						if (empty($prefix_history)) {
							$data_subkomponen = $wpdb->get_results(
								$wpdb->prepare("
								SELECT * 
								FROM esakip_subkomponen
								WHERE id_komponen = %d
								  AND active = 1
								ORDER BY nomor_urut ASC
								", $komponen['id']),
								ARRAY_A
							);
						} else if (!empty($prefix_history)) {
							$data_subkomponen = $wpdb->get_results(
								$wpdb->prepare("
								SELECT * 
								FROM esakip_subkomponen_history
								WHERE id_komponen = %d
								  AND id_jadwal = %d
								ORDER BY nomor_urut ASC
								", $komponen['id_asli'], $id_jadwal),
								ARRAY_A
							);
						}
						if (!empty($data_subkomponen)) {
							foreach ($data_subkomponen as $subkomponen) {
								$disabled = 'disabled';
								// Jika jadwal masih buka
								if ($dateTime > $started_at_dt && $dateTime < $end_at_dt && $data_jadwal['status'] == 1) {
									// Jika jadwal penetapan
									if ($jenis_jadwal == 'penetapan') {
										// dan user adalah evaluator
										if ($can_verify == true) {
											// Hanya jika user penilai sesuai dengan subkomponen
											if (array_key_exists($subkomponen['id_user_penilai'], $intersected_roles)) {
												$disabled = '';
											} else {
												$disabled = 'disabled';
											}
										} else {
											// Jika bukan evaluator, tidak bisa input saat penetapan
											$disabled = 'disabled';
										}
										// Jika jadwal usulan
									} else if ($jenis_jadwal == 'usulan') {
										// dan user bukan evaluator
										if ($can_verify == false) {
											$disabled = '';
										} else {
											$disabled = 'disabled';
										}
									}
								} else {
									// Jika jadwal sudah tutup
									$disabled = 'disabled';
								}
								if (empty($prefix_history)) {
									$sum_nilai_usulan = $wpdb->get_var(
										$wpdb->prepare("
											SELECT SUM(nilai_usulan)
											FROM esakip_pengisian_lke p
											INNER JOIN esakip_komponen_penilaian k on p.id_komponen_penilaian=k.id
											WHERE p.id_subkomponen = %d
											  AND p.id_skpd = %d
											  AND p.tahun_anggaran = %d
											  AND k.active=1
										", $subkomponen['id'], $id_skpd, $tahun_anggaran)
									);
									$count_nilai_usulan = $wpdb->get_var(
										$wpdb->prepare("
											SELECT COUNT(id)
											FROM esakip_komponen_penilaian
											WHERE id_subkomponen = %d
											  AND active = 1
										", $subkomponen['id'])
									);
									$sum_nilai_penetapan = $wpdb->get_var(
										$wpdb->prepare("
											SELECT SUM(nilai_penetapan)
											FROM esakip_pengisian_lke p
											INNER JOIN esakip_komponen_penilaian k on p.id_komponen_penilaian=k.id
											WHERE p.id_subkomponen = %d
											  AND p.id_skpd = %d
											  AND p.tahun_anggaran = %d
											  AND k.active=1
										", $subkomponen['id'], $id_skpd, $tahun_anggaran)
									);
									$count_nilai_penetapan = $wpdb->get_var(
										$wpdb->prepare("
											SELECT COUNT(id)
											FROM esakip_komponen_penilaian
											WHERE id_subkomponen = %d
											  AND active = 1
										", $subkomponen['id'])
									);
								} else if (!empty($prefix_history)) {
									$sum_nilai_usulan = $wpdb->get_var(
										$wpdb->prepare("
											SELECT SUM(nilai_usulan)
											FROM esakip_pengisian_lke_history p
											INNER JOIN esakip_komponen_penilaian_history k on p.id_komponen_penilaian=k.id_asli
											WHERE p.id_subkomponen = %d
											  AND p.id_skpd = %d
											  AND p.tahun_anggaran = %d
											  AND p.id_jadwal =%d
											  AND k.id_jadwal =%d
										", $subkomponen['id_asli'], $id_skpd, $tahun_anggaran, $id_jadwal, $id_jadwal)
									);
									$count_nilai_usulan = $wpdb->get_var(
										$wpdb->prepare("
											SELECT COUNT(id)
											FROM esakip_komponen_penilaian_history
											WHERE id_subkomponen = %d
											  AND id_jadwal = %d
										", $subkomponen['id_asli'], $id_jadwal)
									);
									$sum_nilai_penetapan = $wpdb->get_var(
										$wpdb->prepare("
											SELECT SUM(nilai_penetapan)
											FROM esakip_pengisian_lke_history p
											INNER JOIN esakip_komponen_penilaian_history k on p.id_komponen_penilaian=k.id_asli
											WHERE p.id_subkomponen = %d
											  AND p.id_skpd = %d
											  AND p.tahun_anggaran = %d
											  AND p.id_jadwal = %d
											  AND k.id_jadwal = %d
										", $subkomponen['id_asli'], $id_skpd, $tahun_anggaran, $id_jadwal, $id_jadwal)
									);
									$count_nilai_penetapan = $wpdb->get_var(
										$wpdb->prepare("
											SELECT COUNT(id)
											FROM esakip_komponen_penilaian_history
											WHERE id_subkomponen = %d
											  AND id_jadwal =%d
										", $subkomponen['id_asli'], $id_jadwal)
									);
								}

								//jumlah nilai sub
								$total_nilai_sub = 0;
								$total_nilai_sub_penetapan = 0;
								$persentase_sub = 0;
								$persentase_sub_penetapan = 0;
								if ($count_nilai_usulan > 0) {
									// nilai akumulasi sub
									if ($subkomponen['metode_penilaian'] == 2) {
										$persentase_sub = $sum_nilai_usulan / $subkomponen['bobot'];
										$total_nilai_sub = $sum_nilai_usulan;
										// nilai rata-rata sub
									} else if ($subkomponen['metode_penilaian'] == 1) {
										$persentase_sub = $sum_nilai_usulan / $count_nilai_usulan;
										$total_nilai_sub = $persentase_sub * $subkomponen['bobot'];
									}
								}
								$total_nilai_kom += $total_nilai_sub;

								if ($count_nilai_penetapan > 0) {
									// nilai akumulasi sub
									if ($subkomponen['metode_penilaian'] == 2) {
										$persentase_sub_penetapan = $sum_nilai_penetapan / $subkomponen['bobot'];
										$total_nilai_sub_penetapan += $sum_nilai_penetapan;
										// nilai rata-rata sub
									} else if ($subkomponen['metode_penilaian'] == 1) {
										$persentase_sub_penetapan = $sum_nilai_penetapan / $count_nilai_penetapan;
										$total_nilai_sub_penetapan = $persentase_sub_penetapan * $subkomponen['bobot'];
									}
								}
								$total_nilai_kom_penetapan += $total_nilai_sub_penetapan;

								//tbody subkomponen
								$tbody2 .= "<tr class='table-active'>";
								$tbody2 .= "<td class='text-left'></td>";
								$tbody2 .= "<td class='text-left'>" . $counter_sub++ . "</td>";
								$tbody2 .= "<td class='text-left' colspan='2'><b>" . $subkomponen['nama'] . "</b></td>";
								if (
									empty($_POST['excel'])
									|| $_POST['excel'] == 'usulan'
									|| $_POST['excel'] == 'usulan_penetapan'
								) {
									$tbody2 .= "<td class='text-center'>" . $subkomponen['bobot'] . "</td>";
									$tbody2 .= "<td class='text-left'></td>";
									$tbody2 .= "<td class='text-center'>" . number_format($total_nilai_sub, 2) . "</td>";
									$tbody2 .= "<td class='text-center'>" . number_format($persentase_sub * 100, 2) . "%" . "</td>";
									$tbody2 .= "<td class='text-center' colspan='3'></td>";
								}

								// kolom bukti dukung di laporan penetapan
								if ($_POST['excel'] == 'penetapan') {
									$tbody2 .= "<td class='text-center'></td>";
								}
								if (
									empty($_POST['excel'])
									|| $_POST['excel'] == 'penetapan'
									|| $_POST['excel'] == 'usulan_penetapan'
								) {
									$tbody2 .= "<td class='text-center'></td>";
									if ($tampil_nilai_penetapan == 1 || $can_verify == true) {
										$tbody2 .= "<td class='text-center'>" . number_format($total_nilai_sub_penetapan, 2) . "</td>";
										$tbody2 .= "<td class='text-center'>" . number_format($persentase_sub_penetapan * 100, 2) . "%" . "</td>";
									} else {
										$tbody2 .= "<td class='text-center'> - </td>";
										$tbody2 .= "<td class='text-center'> - </td>";
									}
									$tbody2 .= "<td class='text-left' colspan='3'>User Penilai: <b>" . $user_penilai[$subkomponen['id_user_penilai']] . "</b></td>";
								}
								$tbody2 .= "</tr>";

								if (empty($prefix_history)) {
									$data_komponen_penilaian = $wpdb->get_results(
										$wpdb->prepare("
											SELECT 
												kp.id AS kp_id,
												kp.id_subkomponen AS kp_id_subkomponen,
												kp.nomor_urut AS kp_nomor_urut,
												kp.nama AS kp_nama,
												kp.tipe AS kp_tipe,
												kp.bobot AS kp_bobot,
												kp.keterangan AS kp_keterangan,
												kp.penjelasan AS kp_penjelasan,
												kp.langkah_kerja AS kp_langkah_kerja,
												kp.active AS kp_active,
												pl.id AS pl_id,
												pl.id_user AS pl_id_user,
												pl.id_skpd AS pl_id_skpd,
												pl.id_user_penilai AS pl_id_user_penilai,
												pl.id_komponen AS pl_id_komponen,
												pl.id_subkomponen AS pl_id_subkomponen,
												pl.id_komponen_penilaian AS pl_id_komponen_penilaian,
												pl.nilai_usulan AS pl_nilai_usulan,
												pl.nilai_penetapan AS pl_nilai_penetapan,
												pl.keterangan AS pl_keterangan,
												pl.keterangan_penilai AS pl_keterangan_penilai,
												pl.bukti_dukung AS pl_bukti_dukung,
												pl.create_at AS pl_create_at,
												pl.update_at AS pl_update_at,
												pl.active AS pl_active
											FROM esakip_komponen_penilaian AS kp
											LEFT JOIN esakip_pengisian_lke AS pl 
												ON kp.id = pl.id_komponen_penilaian 
												AND pl.id_skpd = %d
												AND pl.tahun_anggaran=%d
											WHERE kp.id_subkomponen = %d
											  AND kp.active = 1
											ORDER BY kp.nomor_urut ASC
										",  $id_skpd, $tahun_anggaran, $subkomponen['id']),
										ARRAY_A
									);
								} else if (!empty($prefix_history)) {
									$data_komponen_penilaian = $wpdb->get_results(
										$wpdb->prepare("
											SELECT 
												kp.id AS kp_id,
												kp.id_asli AS kp_id_asli,
												kp.id_subkomponen AS kp_id_subkomponen,
												kp.nomor_urut AS kp_nomor_urut,
												kp.nama AS kp_nama,
												kp.tipe AS kp_tipe,
												kp.bobot AS kp_bobot,
												kp.keterangan AS kp_keterangan,
												kp.penjelasan AS kp_penjelasan,
												kp.langkah_kerja AS kp_langkah_kerja,
												kp.active AS kp_active,
												pl.id AS pl_id,
												pl.id_user AS pl_id_user,
												pl.id_skpd AS pl_id_skpd,
												pl.id_user_penilai AS pl_id_user_penilai,
												pl.id_komponen AS pl_id_komponen,
												pl.id_subkomponen AS pl_id_subkomponen,
												pl.id_komponen_penilaian AS pl_id_komponen_penilaian,
												pl.nilai_usulan AS pl_nilai_usulan,
												pl.nilai_penetapan AS pl_nilai_penetapan,
												pl.keterangan AS pl_keterangan,
												pl.keterangan_penilai AS pl_keterangan_penilai,
												pl.bukti_dukung AS pl_bukti_dukung,
												pl.create_at AS pl_create_at,
												pl.update_at AS pl_update_at
											FROM esakip_komponen_penilaian_history AS kp
											LEFT JOIN esakip_pengisian_lke_history AS pl 
												ON kp.id_asli = pl.id_komponen_penilaian 
												AND pl.id_skpd = %d
												AND pl.tahun_anggaran=%d
												AND pl.id_jadwal=%d
											WHERE kp.id_subkomponen = %d
											  AND kp.active = 1
											ORDER BY kp.nomor_urut ASC
										",  $id_skpd, $tahun_anggaran, $id_jadwal, $subkomponen['id_asli']),
										ARRAY_A
									);
								}

								if (!empty($data_komponen_penilaian)) {
									foreach ($data_komponen_penilaian as $penilaian) {
										if (empty($prefix_history)) {
											$opsi_custom = $wpdb->get_results(
												$wpdb->prepare("
													SELECT *
													FROM esakip_penilaian_custom
													WHERE id_komponen_penilaian =%d	
													  AND active = 1
													ORDER BY nomor_urut ASC
												", $penilaian['kp_id']),
												ARRAY_A
											);
										} else if (!empty($prefix_history)) {
											$opsi_custom = $wpdb->get_results(
												$wpdb->prepare("
													SELECT *
													FROM esakip_penilaian_custom_history
													WHERE id_komponen_penilaian =%d
													  AND id_jadwal = %d
													ORDER BY nomor_urut ASC
												", $penilaian['kp_id'], $id_jadwal),
												ARRAY_A
											);
										}

										//opsi jawaban usulan
										$opsi = "<option value=''>Pilih Jawaban</option>";
										if (isset($penilaian['pl_nilai_usulan'])) {
											if ($penilaian['kp_tipe'] == 1) {
												$opsi .= "<option value='1' class='text-center'" . ($penilaian['pl_nilai_usulan'] == 1 ? " selected" : "") . ">Y</option>";
												$opsi .= "<option value='0' class='text-center'" . ($penilaian['pl_nilai_usulan'] == 0 ? " selected" : "") . ">T</option>";
											} else if ($penilaian['kp_tipe'] == 2) {
												$opsi .= "<option value='1' class='text-center'" . ($penilaian['pl_nilai_usulan'] == 1 ? " selected" : "") . ">A</option>";
												$opsi .= "<option value='0.75' class='text-center'" . ($penilaian['pl_nilai_usulan'] == 0.75 ? " selected" : "") . ">B</option>";
												$opsi .= "<option value='0.5' class='text-center'" . ($penilaian['pl_nilai_usulan'] == 0.5 ? " selected" : "") . ">C</option>";
												$opsi .= "<option value='0.25' class='text-center'" . ($penilaian['pl_nilai_usulan'] == 0.25 ? " selected" : "") . ">D</option>";
												$opsi .= "<option value='0' class='text-center'" . ($penilaian['pl_nilai_usulan'] == 0 ? " selected" : "") . ">E</option>";
											} else if ($penilaian['kp_tipe'] == 3) {
												if (!empty($opsi_custom)) {
													foreach ($opsi_custom as $custom) {
														$opsi .= "<option value='" . $custom['nilai'] . "' class='text-center'" . ($penilaian['pl_nilai_usulan'] == $custom['nilai'] ? " selected" : "") . ">" . $custom['nama'] . "</option>";
													}
												}
											}
										} else {
											$opsi = "<option value=''>Pilih Jawaban</option>";
											if ($penilaian['kp_tipe'] == 1) {
												$opsi .= "<option value='1' class='text-center'>Y</option>";
												$opsi .= "<option value='0' class='text-center'>T</option>";
											} else if ($penilaian['kp_tipe'] == 2) {
												$opsi .= "<option value='1' class='text-center'>A</option>";
												$opsi .= "<option value='0.75' class='text-center'>B</option>";
												$opsi .= "<option value='0.5' class='text-center'>C</option>";
												$opsi .= "<option value='0.25' class='text-center'>D</option>";
												$opsi .= "<option value='0' class='text-center'>E</option>";
											} else if ($penilaian['kp_tipe'] == 3) {
												if (!empty($opsi_custom)) {
													foreach ($opsi_custom as $custom) {
														$opsi .= "<option value='" . $custom['nilai'] . "' class='text-center')>" . $custom['nama'] . "</option>";
													}
												}
											}
										}

										//opsi jawaban penetapan
										$opsi_penetapan = "<option value=''>Pilih Jawaban</option>";
										if (isset($penilaian['pl_nilai_penetapan'])) {
											if ($penilaian['kp_tipe'] == 1) {
												$opsi_penetapan .= "<option value='1' class='text-center'" . ($penilaian['pl_nilai_penetapan'] == 1 ? " selected" : "") . ">Y</option>";
												$opsi_penetapan .= "<option value='0' class='text-center'" . ($penilaian['pl_nilai_penetapan'] == 0 ? " selected" : "") . ">T</option>";
											} else if ($penilaian['kp_tipe'] == 2) {
												$opsi_penetapan .= "<option value='1' class='text-center'" . ($penilaian['pl_nilai_penetapan'] == 1 ? " selected" : "") . ">A</option>";
												$opsi_penetapan .= "<option value='0.75' class='text-center'" . ($penilaian['pl_nilai_penetapan'] == 0.75 ? " selected" : "") . ">B</option>";
												$opsi_penetapan .= "<option value='0.5' class='text-center'" . ($penilaian['pl_nilai_penetapan'] == 0.5 ? " selected" : "") . ">C</option>";
												$opsi_penetapan .= "<option value='0.25' class='text-center'" . ($penilaian['pl_nilai_penetapan'] == 0.25 ? " selected" : "") . ">D</option>";
												$opsi_penetapan .= "<option value='0' class='text-center'" . ($penilaian['pl_nilai_penetapan'] == 0 ? " selected" : "") . ">E</option>";
											} else if ($penilaian['kp_tipe'] == 3) {
												if (!empty($opsi_custom)) {
													foreach ($opsi_custom as $custom) {
														$opsi_penetapan .= "<option value='" . $custom['nilai'] . "' class='text-center'" . ($penilaian['pl_nilai_penetapan'] == $custom['nilai'] ? " selected" : "") . ">" . $custom['nama'] . "</option>";
													}
												}
											}
										} else {
											$opsi_penetapan = "<option value=''>Pilih Jawaban</option>";
											if ($penilaian['kp_tipe'] == 1) {
												$opsi_penetapan .= "<option value='1' class='text-center'>Y</option>";
												$opsi_penetapan .= "<option value='0' class='text-center'>T</option>";
											} else if ($penilaian['kp_tipe'] == 2) {
												$opsi_penetapan .= "<option value='1' class='text-center'>A</option>";
												$opsi_penetapan .= "<option value='0.75' class='text-center'>B</option>";
												$opsi_penetapan .= "<option value='0.5' class='text-center'>C</option>";
												$opsi_penetapan .= "<option value='0.25' class='text-center'>D</option>";
												$opsi_penetapan .= "<option value='0' class='text-center'>E</option>";
											} else if ($penilaian['kp_tipe'] == 3) {
												if (!empty($opsi_custom)) {
													foreach ($opsi_custom as $custom) {
														$opsi_penetapan .= "<option value='" . $custom['nilai'] . "' class='text-center')>" . $custom['nama'] . "</option>";
													}
												}
											}
										}


										//nilai usulan
										if (isset($penilaian['pl_nilai_usulan'])) {
											$nilai_usulan = $penilaian['pl_nilai_usulan'];
										} else {
											$nilai_usulan = "0.00";
										}

										//nilai penetapan
										if (isset($penilaian['pl_nilai_penetapan'])) {
											$nilai_penetapan = $penilaian['pl_nilai_penetapan'];
										} else {
											$nilai_penetapan = "0.00";
										}

										// Ambil data kerangka logis yang aktif berdasarkan id_komponen_penilaian
										if (empty($prefix_history)) {
											$data_kerangka_logis = $wpdb->get_results(
												$wpdb->prepare("
													SELECT *
													FROM esakip_kontrol_kerangka_logis
													WHERE id_komponen_penilaian = %d
													  AND active = 1
												", $penilaian['pl_id_komponen_penilaian']),
												ARRAY_A
											);
										} else if (!empty($prefix_history)) {
											$data_kerangka_logis = $wpdb->get_results(
												$wpdb->prepare("
													SELECT *
													FROM esakip_kontrol_kerangka_logis_history
													WHERE id_komponen_penilaian = %d
													  AND id_jadwal = %d
												", $penilaian['pl_id_komponen_penilaian'], $id_jadwal),
												ARRAY_A
											);
										}

										// Default pesan kerangka logis
										$kerangka_logis = "<td class='text-center table-warning'>Belum Diisi</td>";
										$kerangka_logis_penetapan = "<td class='text-center table-warning'>Belum Diisi</td>";

										//kerangka logis nilai usulan
										if (isset($penilaian['pl_nilai_usulan'])) {
											$pesan_kesalahan = [];

											foreach ($data_kerangka_logis as $kl) {
												if ($kl['jenis_kerangka_logis'] == 1) {
													// Rata-rata
													if (empty($prefix_history)) {
														$avg_nilai_sub = $wpdb->get_var(
															$wpdb->prepare("
															SELECT AVG(nilai_usulan)
															FROM esakip_pengisian_lke
															WHERE id_subkomponen = %d
															  AND id_skpd = %d
															  AND tahun_anggaran = %d
															  AND active = 1
														", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran)
														);
													} else if (!empty($prefix_history)) {
														$avg_nilai_sub = $wpdb->get_var(
															$wpdb->prepare("
															SELECT AVG(nilai_usulan)
															FROM esakip_pengisian_lke_history
															WHERE id_subkomponen = %d
															  AND id_skpd = %d
															  AND tahun_anggaran = %d
															  AND id_jadwal = %d
														", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran, $id_jadwal)
														);
													}
													if ($avg_nilai_sub < $penilaian['pl_nilai_usulan']) {
														$pesan_kesalahan[] = $kl['pesan_kesalahan'];
													}
												} else if ($kl['jenis_kerangka_logis'] == 2) {
													// Nilai
													if (empty($prefix_history)) {
														$nilai_komponen_penilaian = $wpdb->get_var(
															$wpdb->prepare("
															SELECT nilai_usulan
															FROM esakip_pengisian_lke
															WHERE id_komponen_penilaian = %d
															  AND id_skpd = %d
															  AND tahun_anggaran = %d
														", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran)
														);
													} else if (!empty($prefix_history)) {
														$nilai_komponen_penilaian = $wpdb->get_var(
															$wpdb->prepare("
															SELECT nilai_usulan
															FROM esakip_pengisian_lke_history
															WHERE id_komponen_penilaian = %d
															  AND id_skpd = %d
															  AND tahun_anggaran = %d
															  AND id_jadwal = %d
														", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran, $id_jadwal)
														);
													}

													if ($penilaian['pl_nilai_usulan'] > $nilai_komponen_penilaian) {
														$pesan_kesalahan[] = $kl['pesan_kesalahan'];
													}
												}
											}

											if (!empty($pesan_kesalahan)) {
												$kerangka_logis = "<td class='text-center table-danger'><ul><li>" . implode("</li><li>", $pesan_kesalahan) . "</li></ul></td>";
											} else {
												$kerangka_logis = "<td class='text-center table-success'>OK</td>";
											}
										}



										//kerangka logis nilai penetapan
										if ($tampil_nilai_penetapan == 1 || $can_verify == true) {
											if (isset($penilaian['pl_nilai_penetapan'])) {
												$pesan_kesalahan_penetapan = [];

												foreach ($data_kerangka_logis as $kl) {
													if ($kl['jenis_kerangka_logis'] == 1) {
														// Rata-rata
														if (empty($prefix_history)) {
															$avg_nilai_sub_penetapan = $wpdb->get_var(
																$wpdb->prepare("
																SELECT AVG(nilai_penetapan)
																FROM esakip_pengisian_lke
																WHERE id_subkomponen = %d
																  AND id_skpd = %d
																  AND tahun_anggaran=%d
																  AND active=1
															", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran)
															);
														} else if (!empty($prefix_history)) {
															$avg_nilai_sub_penetapan = $wpdb->get_var(
																$wpdb->prepare("
																SELECT AVG(nilai_penetapan)
																FROM esakip_pengisian_lke_history
																WHERE id_subkomponen = %d
																  AND id_skpd = %d
																  AND tahun_anggaran=%d
																  AND id_jadwal=%d
															", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran, $id_jadwal)
															);
														}

														if ($avg_nilai_sub_penetapan < $penilaian['pl_nilai_penetapan']) {
															$pesan_kesalahan_penetapan[] = $kl['pesan_kesalahan'];
														}
													} else if ($kl['jenis_kerangka_logis'] == 2) {
														// Nilai
														if (empty($prefix_history)) {
															$nilai_komponen_penilaian_penetapan = $wpdb->get_var(
																$wpdb->prepare("
																SELECT nilai_penetapan
																FROM esakip_pengisian_lke
																WHERE id_komponen_penilaian = %d
																  AND id_skpd = %d
																  AND tahun_anggaran=%d
															", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran)
															);
														} else if (!empty($prefix_history)) {
															$nilai_komponen_penilaian_penetapan = $wpdb->get_var(
																$wpdb->prepare("
																SELECT nilai_penetapan
																FROM esakip_pengisian_lke_history
																WHERE id_komponen_penilaian = %d
																  AND id_skpd = %d
																  AND tahun_anggaran=%d
																  AND id_jadwal=%d
															", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran, $id_jadwal)
															);
														}
														if ($penilaian['pl_nilai_penetapan'] > $nilai_komponen_penilaian_penetapan) {
															$pesan_kesalahan_penetapan[] = $kl['pesan_kesalahan'];
														}
													}
												}

												if (!empty($pesan_kesalahan_penetapan)) {
													$kerangka_logis_penetapan = "<td class='text-center table-danger'><ul><li>" . implode("</li><li>", $pesan_kesalahan_penetapan) . "</li></ul></td>";
												} else {
													$kerangka_logis_penetapan = "<td class='text-center table-success'>OK</td>";
												}
											}
										}

										//jika metode penilaian nilai dinamis maka tampilkan bobot penilaian
										if ($subkomponen['metode_penilaian'] == 2) {
											$bobot_komponen_penilaian = $penilaian['kp_bobot'];
										} else if ($subkomponen['metode_penilaian'] == 1) {
											$bobot_komponen_penilaian = '-';
											$nilai_usulan = '-';
											$nilai_penetapan = '-';
										}

										$bukti_dukung = json_decode(stripslashes($penilaian['pl_bukti_dukung']), true);
										if (!empty($bukti_dukung)) {
											foreach ($bukti_dukung as $k => $bukti) {
												$bukti_dukung[$k] = '<a href="' . ESAKIP_PLUGIN_URL . 'public/media/dokumen/' . $bukti . '" target="_blank">' . $bukti . '</a>';
											}
											$bukti_dukung = implode(', ', $bukti_dukung);
										}
										$tombol_bukti = "";
										if ($disabled == '') {
											$tombol_bukti = "<button type='button' class='btn btn-primary btn-sm' title='Tambah bukti dukung' onclick='tambahBuktiDukung(" . $id_skpd . "," . $penilaian['kp_id'] . ")' id='buktiDukung" . $penilaian['kp_id'] . "' title='Tambah Bukti Dukung'><i class='dashicons dashicons-plus'></i></button>";
										}
										//tbody isi
										$tbody2 .= "<tr kp-id='" . $penilaian['kp_id'] . "'>";
										$tbody2 .= "<td class='text-left'></td>";
										$tbody2 .= "<td class='text-left'></td>";
										$tbody2 .= "<td class='text-left'>" . $counter_isi++ . "</td>";
										$tbody2 .= "<td class='text-left'>" . $penilaian['kp_nama'] . "<br><small class='text-muted'> Keterangan : " . $penilaian['kp_keterangan'] . "</small></td>";

										$tbody2 .= "<td class='text-center'>" . $bobot_komponen_penilaian . "</td>";

										switch ($can_verify) {
											case false:
												if (!$this->is_admin_panrb()) {
													$btn_save = "<button class='btn btn-primary' onclick='simpanPerubahan(" . $penilaian['kp_id'] . ")' title='Simpan Perubahan'><span class='dashicons dashicons-saved' ></span></button>";
												}
												if (!$this->is_admin_panrb()) {
													$tbody2 .= "<td class='text-center'><select id='opsiUsulan" . $penilaian['kp_id'] . "' $disabled>" . $opsi . "</select></td>";
												} else {
													$tbody2 .= "<td class='text-center'><select id='opsiUsulan" . $penilaian['kp_id'] . "' disabled>" . $opsi . "</select></td>";
												}
												if (
													empty($_POST['excel'])
													|| $_POST['excel'] == 'usulan'
													|| $_POST['excel'] == 'usulan_penetapan'
												) {
													$tbody2 .= "<td class='text-center'>" . $nilai_usulan . "</td>";
												}
												if (empty($_POST['excel'])) {
													$tbody2 .= "<td class='text-center'><button class='btn btn-secondary' onclick='infoPenjelasan(" . $penilaian['kp_id'] . ")' title='Info Penjelasan'><span class='dashicons dashicons-info'></span></button></td>";
												} else {
													$tbody2 .= "<td class='text-center'></td>";
												}

												if (!$this->is_admin_panrb()) {
													$tbody2 .= "<td class='text-center'><div class='bukti-dukung-view' kp-id='" . $penilaian['kp_id'] . "'>" . $bukti_dukung . "</div>" . $tombol_bukti . "</td>";
													if (
														empty($_POST['excel'])
														|| $_POST['excel'] == 'usulan'
														|| $_POST['excel'] == 'usulan_penetapan'
													) {
														$tbody2 .= "<td class='text-center'><textarea id='keteranganUsulan" . $penilaian['kp_id'] . "' $disabled>" . $penilaian['pl_keterangan'] . "</textarea></td>";
													}
												} else {
													$tbody2 .= "<td class='text-center'><div class='bukti-dukung-view' kp-id='" . $penilaian['kp_id'] . "'>" . $bukti_dukung . "</div></td>";
													if (
														empty($_POST['excel'])
														|| $_POST['excel'] == 'usulan'
														|| $_POST['excel'] == 'usulan_penetapan'
													) {
														$tbody2 .= "<td class='text-center'><textarea id='keteranganUsulan" . $penilaian['kp_id'] . "' disabled>" . $penilaian['pl_keterangan'] . "</textarea></td>";
													}
												}
												if (
													empty($_POST['excel'])
													|| $_POST['excel'] == 'usulan'
													|| $_POST['excel'] == 'usulan_penetapan'
												) {
													$tbody2 .= $kerangka_logis;
												}
												if (
													empty($_POST['excel'])
													|| $_POST['excel'] == 'penetapan'
													|| $_POST['excel'] == 'usulan_penetapan'
												) {
													if ($tampil_nilai_penetapan == 1 || $can_verify == true) {
														$tbody2 .= "<td class='text-center' ><select id='opsiPenetapan" . $penilaian['kp_id'] . "' disabled>" . $opsi_penetapan . "</select></td>";
														$tbody2 .= "<td class='text-center'>" . $nilai_penetapan . "</td>";
													} else {
														$tbody2 .= "<td class='text-center'><select id='opsiPenetapan" . $penilaian['kp_id'] . "' disabled><option selected> - </option></select></td>";
														$tbody2 .= "<td class='text-center'> - </td>";
													}
													$tbody2 .= "<td class='text-center'></td>";
													if ($tampil_nilai_penetapan == 1 || $can_verify == true) {
														$tbody2 .= "<td class='text-center'><textarea id='keteranganPenetapan" . $penilaian['kp_id'] . "'" . $disabled . ">" . $penilaian['pl_keterangan_penilai'] . "</textarea></td>";
													} else {
														$tbody2 .= "<td class='text-center'><textarea id='keteranganPenetapan" . $penilaian['kp_id'] . "'" . $disabled . "> - </textarea></td>";
													}
													$tbody2 .= $kerangka_logis_penetapan;
												}
												if (empty($_POST['excel'])) {
													if ($disabled == '') {
														if (!$this->is_admin_panrb()) {
															$tbody2 .= "<td class='text-center'>" . $btn_save . "</td>";
														} else {
															$tbody2 .= "<td class='text-center'></td>";
														}
													} else {
														$tbody2 .= "<td class='text-center'></td>";
													}
												}
												break;
											case true:
												if (!$this->is_admin_panrb()) {
													$btn_save_penetapan = "<button class='btn btn-info' onclick='simpanPerubahanPenetapan(" . $penilaian['kp_id'] . ")' title='Simpan Perubahan Penetapan'><span class='dashicons dashicons-saved' ></span></button>";
												}

												if (
													empty($_POST['excel'])
													|| $_POST['excel'] == 'usulan'
													|| $_POST['excel'] == 'usulan_penetapan'
												) {
													$tbody2 .= "<td class='text-center'><select id='opsiUsulan" . $penilaian['kp_id'] . "' disabled>" . $opsi . "</select></td>";
													$tbody2 .= "<td class='text-center'>" . $nilai_usulan . "</td>";
												}
												if (empty($_POST['excel'])) {
													$tbody2 .= "<td class='text-center'><button class='btn btn-secondary' onclick='infoPenjelasan(" . $penilaian['kp_id'] . ")' title='Info Penjelasan'><span class='dashicons dashicons-info'></span></button></td>";
												} else {
													$tbody2 .= "<td class='text-center'></td>";
												}
												$tbody2 .= "<td class='text-center'><div class='bukti-dukung-view' kp-id='" . $penilaian['kp_id'] . "'>" . $bukti_dukung . "</div></td>";
												if (
													empty($_POST['excel'])
													|| $_POST['excel'] == 'usulan'
													|| $_POST['excel'] == 'usulan_penetapan'
												) {
													$tbody2 .= "<td class='text-center'><textarea id='keteranganUsulan" . $penilaian['kp_id'] . "' disabled>" . $penilaian['pl_keterangan'] . "</textarea></td>";
													$tbody2 .= $kerangka_logis;
												}
												if (
													empty($_POST['excel'])
													|| $_POST['excel'] == 'penetapan'
													|| $_POST['excel'] == 'usulan_penetapan'
												) {
													$tbody2 .= "<td class='text-center'><select id='opsiPenetapan" . $penilaian['kp_id'] . "' " . $disabled . ">" . $opsi_penetapan . "</select></td>";
													$tbody2 .= "<td class='text-center'>" . $nilai_penetapan . "</td>";
													$tbody2 .= "<td class='text-center'></td>";
													$tbody2 .= "<td class='text-center'><textarea id='keteranganPenetapan" . $penilaian['kp_id'] . "'" . $disabled . ">" . $penilaian['pl_keterangan_penilai'] . "</textarea></td>";
													$tbody2 .= $kerangka_logis_penetapan;
												}
												if (empty($_POST['excel'])) {
													if ($disabled == '') {
														if (!$this->is_admin_panrb()) {
															$tbody2 .= "<td class='text-center'>" . $btn_save_penetapan . "</td>";
														} else {
															$tbody2 .= "<td class='text-center'></td>";
														}
													} else {
														$tbody2 .= "<td class='text-center'></td>";
													}
												}
												break;
										}
										$tbody2 .= "</tr>";
									}
								}
							}
						}

						if ($total_nilai_kom > 0) {
							$persentase_kom = $total_nilai_kom / $komponen['bobot'];
						}
						if ($total_nilai_kom_penetapan > 0) {
							$persentase_kom_penetapan = $total_nilai_kom_penetapan / $komponen['bobot'];
						}

						$total_nilai += $total_nilai_kom;
						$total_nilai_penetapan += $total_nilai_kom_penetapan;


						//tbody komponen
						$tbody .= "<tr class='table-active'>";
						$tbody .= "<td class='text-left'>" . $counter++ . "</td>";
						$tbody .= "<td class='text-left' colspan='3'><b>" . $komponen['nama'] . "</b></td>";
						$tbody .= "<td class='text-center'>" . $komponen['bobot'] . "</td>";
						if (
							empty($_POST['excel'])
							|| $_POST['excel'] == 'usulan'
							|| $_POST['excel'] == 'usulan_penetapan'
						) {
							$tbody .= "<td class='text-left'></td>";
							$tbody .= "<td class='text-center'>" . number_format($total_nilai_kom, 2) . "</td>";
							$tbody .= "<td class='text-center'>" . number_format($persentase_kom * 100, 2) . "%" . "</td>";
							$tbody .= "<td class='text-center' colspan='3'></td>";
						}

						// kolom bukti dukung di laporan penetapan
						if ($_POST['excel'] == 'penetapan') {
							$tbody2 .= "<td class='text-center'></td>";
						}
						if (
							empty($_POST['excel'])
							|| $_POST['excel'] == 'penetapan'
							|| $_POST['excel'] == 'usulan_penetapan'
						) {
							$tbody .= "<td class='text-center'></td>";
							if ($tampil_nilai_penetapan == 1 || $can_verify == true) {
								$tbody .= "<td class='text-center'>" . number_format($total_nilai_kom_penetapan, 2) .  "</td>";
								$tbody .= "<td class='text-center'>" . number_format($persentase_kom_penetapan * 100, 2) . "%" . "</td>";
							} else {
								$tbody .= "<td class='text-center'> - </td>";
								$tbody .= "<td class='text-center'> - </td>";
							}
						}
						if (
							empty($_POST['excel'])
							|| $_POST['excel'] == 'penetapan'
							|| $_POST['excel'] == 'usulan_penetapan'
						) {
							$tbody .= "<td class='text-center' colspan='3'></td>";
						}
						$tbody .= "</tr>";
						$tbody .= $tbody2;

						//nilai usulan selalu muncul
						$merged_data['total_nilai'] = number_format($total_nilai, 2);

						//nilai penetapan diset dimenu jadwal
						if ($tampil_nilai_penetapan == 1 && $can_verify == true) {
							$merged_data['total_nilai_penetapan'] = number_format($total_nilai_penetapan, 2);
						} else if ($tampil_nilai_penetapan == 0 && $can_verify == false) {
							$merged_data['total_nilai_penetapan'] = '-';
						} else if ($tampil_nilai_penetapan == 0 && $can_verify == true) {
							$merged_data['total_nilai_penetapan'] = number_format($total_nilai_penetapan, 2) . '<br><small class="text-muted">disembunyikan</>';
						} else {
							$merged_data['total_nilai_penetapan'] = number_format($total_nilai_penetapan, 2);
						}
					}
				} else {
					$tbody = "<tr><td colspan='4' class='text-center'>Tidak ada data tersedia</td></tr>";
				}

				$merged_data['tbody'] = $tbody;
				$ret['data'] = $merged_data;
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

	public function tambah_nilai_penetapan_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah nilai penetapan!',
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Perangkat Daerah kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_komponen_penilaian'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Komponen Penilaian kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Jadwal Penetapan kosong!';
				} else if ($ret['status'] != 'error' && !isset($_POST['nilai_penetapan']) || $_POST['nilai_penetapan'] === '') {
					$ret['status'] = 'error';
					$ret['message'] = 'Nilai Penetapan kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['ket_penetapan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Keterangan Penetapan kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran kosong!';
				}

				if ($ret['status'] == 'error') {
					die(json_encode($ret));
				}

				$id_skpd = $_POST['id_skpd'];
				$id_komponen_penilaian = $_POST['id_komponen_penilaian'];
				$id_jadwal = $_POST['id_jadwal'];
				$nilai_penetapan = $_POST['nilai_penetapan'];
				$ket_penetapan = $_POST['ket_penetapan'];
				$tahun_anggaran = $_POST['tahun_anggaran'];

				//validasi jadwal
				date_default_timezone_set('Asia/Jakarta'); // Adjust this if your server is set to a different timezone
				$dateTime = new DateTime();
				$data_jadwal = $wpdb->get_row(
					$wpdb->prepare("
						SELECT *
						FROM esakip_data_jadwal
						WHERE id=%d
							and status = 1
					", $id_jadwal),
					ARRAY_A
				);
				if ($data_jadwal) {
					$started_at = trim($data_jadwal['started_at']);
					$end_at = trim($data_jadwal['end_at']);

					$started_at_dt = new DateTime($started_at);
					$end_at_dt = new DateTime($end_at);
				} else {
					error_log('Data jadwal active tidak ditemukan.');
				}
				if ($dateTime > $started_at_dt && $dateTime > $end_at_dt) {
					$ret['status'] = 'error';
					$ret['message'] = 'Jadwal Sudah Selesai!';
					die(json_encode($ret));
				}

				//cari id kom dan subkom ketika insert baru
				$id_subkomponen = $wpdb->get_var(
					$wpdb->prepare("
						SELECT 
							id_subkomponen
						FROM esakip_komponen_penilaian
						WHERE id = %d
					", $id_komponen_penilaian)
				);

				// //validasi nilai
				// $metode_penilaian = $wpdb->get_var(
				// 	$wpdb->prepare("
				// 		SELECT 
				// 			metode_penilaian
				// 		FROM esakip_subkomponen
				// 		WHERE id = %d
				// 	", $id_subkomponen)
				// );
				// if ($metode_penilaian == 2) {
				// 	//validasi nilai custom
				// 	$valid_values_custom = $wpdb->get_results(
				// 		$wpdb->prepare("
				// 			SELECT nilai
				// 			FROM esakip_penilaian_custom
				// 			WHERE id_komponen_penilaian=%d
				// 		", $id_komponen_penilaian),
				// 		ARRAY_A
				// 	);
				// 	// $valid_values_custom_flat = array_column($valid_values_custom, 'nilai');

				// 	if (!in_array($nilai_penetapan, $valid_values_custom)) {
				// 		$ret['status'] = 'error';
				// 		$ret['message'] = 'Aksi ditolak - nilai yang dimasukkan tidak valid!';
				// 		die(json_encode($ret));
				// 	}
				// } else {
				// 	// validasi nilai rata rata
				// 	$valid_values = [0, 0.25, 0.5, 0.75, 1];
				// 	if (!in_array($nilai_penetapan, $valid_values)) {
				// 		$ret['status'] = 'error';
				// 		$ret['message'] = 'Aksi ditolak - nilai yang dimasukkan tidak valid!';
				// 		die(json_encode($ret));
				// 	}
				// }

				//validasi user
				$current_user = wp_get_current_user();
				$allowed_roles = array('admin_ortala', 'admin_bappeda', 'administrator');
				if (empty(array_intersect($allowed_roles, $current_user->roles))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Akses ditolak - hanya pengguna dengan peran tertentu yang dapat mengakses fitur ini!';
					die(json_encode($ret));
				}

				$existing_data = $wpdb->get_var(
					$wpdb->prepare("
						SELECT 
							id
						FROM esakip_pengisian_lke
						WHERE id_skpd = %d
						  AND id_komponen_penilaian = %d
						  AND tahun_anggaran=%d
					", $id_skpd, $id_komponen_penilaian, $tahun_anggaran)
				);
				if ($existing_data) {
					$updated = $wpdb->update(
						'esakip_pengisian_lke',
						array(
							'id_user_penilai' => $current_user->ID,
							'nilai_penetapan' => $nilai_penetapan,
							'keterangan_penilai' => $ket_penetapan,
							'update_at' => current_time('mysql')
						),
						array('id' => $existing_data),
						array('%d', '%f', '%s', '%s'),
					);

					if ($updated !== false) {
						$ret['message'] = "Berhasil edit nilai penetapan!";
					} else {
						$ret['status'] = 'error';
						$ret['message'] = "Gagal melakukan update nilai penetapan: " . $wpdb->last_error;
					}
				} else {
					if (!empty($id_subkomponen)) {
						$id_komponen = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									id_komponen
								FROM esakip_subkomponen
								WHERE id = %d
							", $id_subkomponen)
						);
						if (empty($id_subkomponen)) {
							$ret['status'] = 'error';
							$ret['message'] = 'ID Komponen kosong!';
						}
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'ID Subomponen kosong!';
					}
					//jika sukses insert
					if ($ret['status'] = 'success') {
						$wpdb->insert(
							'esakip_pengisian_lke',
							array(
								'id_skpd' => $id_skpd,
								'id_user_penilai' => $current_user->ID,
								'nilai_penetapan' => $nilai_penetapan,
								'id_komponen' => $id_komponen,
								'id_subkomponen' => $id_subkomponen,
								'id_komponen_penilaian' => $id_komponen_penilaian,
								'keterangan_penilai' => $ket_penetapan,
								'tahun_anggaran' => $tahun_anggaran,
								'create_at' => current_time('mysql')
							),
							array('%d', '%d', '%f', '%d', '%d', '%d', '%s', '%d', '%s'),
						);
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

	public function tambah_nilai_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah nilai!',
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Perangkat Daerah kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_komponen_penilaian'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Komponen Penilaian kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Jadwal Usulan kosong!';
				} else if ($ret['status'] != 'error' && !isset($_POST['nilai_usulan']) || $_POST['nilai_usulan'] === '') {
					$ret['status'] = 'error';
					$ret['message'] = 'Nilai Usulan kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['ket_usulan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Keterangan Usulan kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran kosong!';
				}
				if ($ret['status'] == 'error') {
					die(json_encode($ret));
				}
				$tahun_anggaran = $_POST['tahun_anggaran'];
				$id_skpd = $_POST['id_skpd'];
				$id_komponen_penilaian = $_POST['id_komponen_penilaian'];
				$id_jadwal = $_POST['id_jadwal'];
				$nilai_usulan = $_POST['nilai_usulan'];
				$ket_usulan = $_POST['ket_usulan'];

				//validasi jadwal
				date_default_timezone_set('Asia/Jakarta'); // Adjust this if your server is set to a different timezone
				$dateTime = new DateTime();
				$data_jadwal = $wpdb->get_row(
					$wpdb->prepare("
						SELECT *
						FROM esakip_data_jadwal
						WHERE id=%d
							AND status = 1
					", $id_jadwal),
					ARRAY_A
				);
				if ($data_jadwal) {
					$started_at = trim($data_jadwal['started_at']);
					$end_at = trim($data_jadwal['end_at']);

					$started_at_dt = new DateTime($started_at);
					$end_at_dt = new DateTime($end_at);
				} else {
					error_log('Data jadwal active tidak ditemukan.');
				}
				if ($dateTime > $started_at_dt && $dateTime > $end_at_dt) {
					$ret['status'] = 'error';
					$ret['message'] = 'Jadwal Sudah Selesai!';
					die(json_encode($ret));
				}

				//cari id kom dan subkom ketika insert baru
				$id_subkomponen = $wpdb->get_var(
					$wpdb->prepare("
						SELECT 
							id_subkomponen
						FROM esakip_komponen_penilaian
						WHERE id = %d
					", $id_komponen_penilaian)
				);

				// //validasi nilai
				// $metode_penilaian = $wpdb->get_var(
				// 	$wpdb->prepare("
				// 		SELECT 
				// 			metode_penilaian
				// 		FROM esakip_subkomponen
				// 		WHERE id = %d
				// 	", $id_subkomponen)
				// );
				// if ($metode_penilaian == 2) {
				// 	//validasi nilai custom
				// 	$valid_values_custom = $wpdb->get_results(
				// 		$wpdb->prepare("
				// 			SELECT nilai
				// 			FROM esakip_penilaian_custom
				// 			WHERE id_komponen_penilaian=%d
				// 		", $id_komponen_penilaian),
				// 		ARRAY_A
				// 	);
				// 	$valid_values_custom_flat = array_column($valid_values_custom, 'nilai');

				// 	if (!in_array($nilai_usulan, $valid_values_custom_flat)) {
				// 		$ret['status'] = 'error';
				// 		$ret['message'] = 'Aksi ditolak - nilai yang dimasukkan tidak valid!';
				// 		die(json_encode($ret));
				// 	}
				// } else {
				// 	// validasi nilai rata rata
				// 	$valid_values = [0, 0.25, 0.5, 0.75, 1];
				// 	if (!in_array($nilai_usulan, $valid_values)) {
				// 		$ret['status'] = 'error';
				// 		$ret['message'] = 'Aksi ditolak - nilai yang dimasukkan tidak valid!';
				// 		die(json_encode($ret));
				// 	}
				// }

				//validasi user
				$current_user = wp_get_current_user();
				$allowed_roles = array('admin_ortala', 'admin_bappeda', 'administrator');
				if (!empty(array_intersect($allowed_roles, $current_user->roles))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Akses ditolak - hanya pengguna dengan peran tertentu yang dapat mengakses fitur ini!';
					die(json_encode($ret));
				}

				$allowed_roles = array();
				$existing_data = $wpdb->get_var(
					$wpdb->prepare("
						SELECT 
							id
						FROM esakip_pengisian_lke
						WHERE id_skpd = %d
						  AND id_komponen_penilaian = %d
						  AND tahun_anggaran = %d
					", $id_skpd, $id_komponen_penilaian, $tahun_anggaran)
				);
				if ($existing_data) {
					$updated = $wpdb->update(
						'esakip_pengisian_lke',
						array(
							'nilai_usulan' => $nilai_usulan,
							'keterangan' => $ket_usulan,
							'update_at' => current_time('mysql')
						),
						array('id' => $existing_data),
						array('%f', '%s', '%s', '%s'),
					);

					if ($updated !== false) {
						$ret['message'] = "Berhasil update nilai usulan!";
					} else {
						$ret['status'] = 'error';
						$ret['message'] = "Gagal melakukan update nilai usulan: " . $wpdb->last_error;
					}
				} else {
					if (!empty($id_subkomponen)) {
						$id_komponen = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									id_komponen
								FROM esakip_subkomponen
								WHERE id = %d
							", $id_subkomponen)
						);
						if (empty($id_subkomponen)) {
							$ret['status'] = 'error';
							$ret['message'] = 'ID Komponen kosong!';
						}
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'ID Subomponen kosong!';
					}

					//jika sukses insert
					if ($ret['status'] = 'success') {
						$wpdb->insert(
							'esakip_pengisian_lke',
							array(
								'id_user' => $current_user->ID,
								'id_skpd' => $id_skpd,
								'id_komponen' => $id_komponen,
								'id_subkomponen' => $id_subkomponen,
								'id_komponen_penilaian' => $id_komponen_penilaian,
								'keterangan' => $ket_usulan,
								'nilai_usulan' => $nilai_usulan,
								'tahun_anggaran' => $tahun_anggaran,
								'create_at' => current_time('mysql')
							),
							array('%d', '%d', '%d', '%s', '%f', '%s', '%s', '%s', '%d', '%s'),
						);
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

	function get_dokumen_bukti_dukung()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil ambil data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID OPD tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['kp_id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Komponen penilai tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$existing_data = $wpdb->get_row($wpdb->prepare("
						SELECT 
							*
						FROM esakip_pengisian_lke
						WHERE id_skpd = %d
						  AND id_komponen_penilaian = %d
						  AND tahun_anggaran = %d
					", $_POST['id_skpd'], $_POST['kp_id'], $_POST['tahun_anggaran']), ARRAY_A);
					$bukti_dukung_existing = json_decode(stripslashes($existing_data['bukti_dukung']), true);
					if (empty($bukti_dukung_existing)) {
						$bukti_dukung_existing = array();
					}

					$jenis_bukti_dukung_db = $wpdb->get_var($wpdb->prepare("
						SELECT
							jenis_bukti_dukung
						FROM esakip_komponen_penilaian
						WHERE id=%d
					", $_POST['kp_id']));
					$all_dokumen = array();
					$jenis_bukti_dukung = json_decode(stripslashes($jenis_bukti_dukung_db), true);
					if (json_last_error() !== JSON_ERROR_NONE) {
						$jenis_bukti_dukung = array();
					}
					$ret['upload_bukti_dukung'] = '';
					foreach ($jenis_bukti_dukung as $v) {
						$sql = $wpdb->prepare("
							SELECT
								*
							FROM {$v}
							WHERE id_skpd=%d
								AND active=1
						", $_POST['id_skpd']);

						// dikecualikan karena dokumen ini tidak berdasarkan tahun anggaran, tapi per periode
						if (
							$v != 'esakip_renstra'
							&& $v != 'esakip_rpjmd'
							&& $v != 'esakip_rpjpd'
							&& $v != 'esakip_pohon_kinerja_dan_cascading'
						) {
							$sql .= $wpdb->prepare(' AND tahun_anggaran=%d', $_POST['tahun_anggaran']);
						}
						$all_dokumen[$v] = $wpdb->get_results($sql, ARRAY_A);
						if (
							$v == 'esakip_rpjmd'
							|| $v == 'esakip_rpjpd'
							|| $v == 'esakip_rkpd'
							|| $v == 'esakip_lkjip_lppd'
							|| $v == 'esakip_other_file'
						) {
							foreach ($all_dokumen[$v] as $key => $dok) {
								$all_dokumen[$v][$key]['dokumen'] = 'dokumen_pemda/' . $dok['dokumen'];
							}
						}

						if ($v == 'esakip_renstra') {
							$jadwal_periode = $wpdb->get_results(
								$wpdb->prepare("
									SELECT 
										id,
										nama_jadwal,
										tahun_anggaran,
										lama_pelaksanaan
									FROM esakip_data_jadwal
									WHERE tipe = %s
									  	AND status = 1
									ORDER BY id ASC
								", 'RPJMD'),
								ARRAY_A
							);
							foreach ($jadwal_periode as $jadwal_periode_item) {
								$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
								if ($v == 'esakip_renstra') {
									$dokumen = $this->functions->generatePage(array(
										'nama_page' => 'RENSTRA | ' . $jadwal_periode_item['id'],
										'content' => '[upload_dokumen_renstra periode=' . $jadwal_periode_item['id'] . ']',
										'show_header' => 1,
										'post_status' => 'private'
									));
									$dokumen['url'] .= '&id_skpd=' . $_POST['id_skpd'];
									$title_renstra = 'Dokumen RENSTRA | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
									$ret['upload_bukti_dukung'] .= '<a style="margin-left: 5px;" class="btn btn-warning" target="_blank" href="' . $dokumen['url'] . '">' . $title_renstra . '</a>';
								}
							}
							continue;
						}

						if ($v == 'esakip_pohon_kinerja_dan_cascading') {
							$jadwal_periode = $wpdb->get_results(
								$wpdb->prepare("
									SELECT 
										id,
										nama_jadwal,
										tahun_anggaran,
										lama_pelaksanaan
									FROM esakip_data_jadwal
									WHERE tipe = %s
									  	AND status = 1
									ORDER BY id ASC
								", 'RPJMD'),
								ARRAY_A
							);
							foreach ($jadwal_periode as $jadwal_periode_item) {
								$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
								if ($v == 'esakip_pohon_kinerja_dan_cascading') {
									$dokumen = $this->functions->generatePage(array(
										'nama_page' => 'Pohon Kinerja dan Cascading | ' . $jadwal_periode_item['id'],
										'content' => '[dokumen_detail_pohon_kinerja_dan_cascading periode=' . $jadwal_periode_item['id'] . ']',
										'show_header' => 1,
										'post_status' => 'private'
									));

									$dokumen['url'] .= '&id_skpd=' . $_POST['id_skpd'];
									$title_pokin = 'Dokumen Pohon Kinerja dan Cascading | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
									$ret['upload_bukti_dukung'] .= '<a style="margin-left: 5px;" class="btn btn-warning" target="_blank" href="' . $dokumen['url'] . '">' . $title_pokin . '</a>';
								}
							}
							continue;
						}

						if ($v == 'esakip_renja_rkt') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'RENJA / RKT-' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_renja_rkt tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_skp') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'SKP ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_skp tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_rencana_aksi') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Rencana Aksi ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_rencana_aksi tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_iku') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'IKU ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_iku tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_pengukuran_kinerja') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Pengukuran Kinerja ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_pengukuran_kinerja tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_laporan_kinerja') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Laporan Kinerja ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_laporan_kinerja tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_dpa') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'DPA ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_dpa tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_evaluasi_internal') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Evaluasi Internal ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_evaluasi_internal tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_dokumen_lainnya') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Lainnya ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_dokumen_lainnya tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_perjanjian_kinerja') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Perjanjian Kinerja ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_perjanjian_kinerja tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_lhe_akip_internal') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'LHE AKIP Internal' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_lhe_akip_internal tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_tl_lhe_akip_internal') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'TL LHE AKIP Internal' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_tl_lhe_akip_internal tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_laporan_monev_renaksi') {
							$laporan_monev_renaksi_skpd = $this->functions->generatePage(array(
								'nama_page' => 'Laporan Monev Renaksi' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_laporan_monev_renaksi tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						}
						if (!empty($dokumen)) {
							$dokumen['url'] .= '&id_skpd=' . $_POST['id_skpd'];
							$ret['upload_bukti_dukung'] .= '<a style="margin-left: 5px;" class="btn btn-warning" target="_blank" href="' . $dokumen['url'] . '">' . $dokumen['title'] . '</a>';
						}
					}

					$ret['data'] = $all_dokumen;
					$ret['data_existing'] = $bukti_dukung_existing;
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

	function submit_bukti_dukung()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID OPD tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['kp_id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Komponen penilai tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				// else if ($ret['status'] != 'error' && !isset($_POST['bukti_dukung'])) {
				// 	$ret['status'] = 'error';
				// 	$ret['message'] = 'Bukti dukung tidak boleh kosong!';
				// }
				if ($ret['status'] != 'error') {

					$current_user = wp_get_current_user();
					$allowed_roles = array('admin_ortala', 'admin_bappeda', 'administrator', 'admin_panrb');
					if (!empty(array_intersect($allowed_roles, $current_user->roles))) {
						$ret['status'] = 'error';
						$ret['message'] = 'Akses ditolak - hanya pengguna dengan peran tertentu yang dapat mengakses fitur ini!';
						die(json_encode($ret));
					}
					$id_skpd = $_POST['id_skpd'];
					$id_komponen_penilaian = $_POST['kp_id'];
					$tahun_anggaran = $_POST['tahun_anggaran'];
					$bukti_dukung = $_POST['bukti_dukung'];
					$existing_data = $wpdb->get_row($wpdb->prepare("
						SELECT 
							*
						FROM esakip_pengisian_lke
						WHERE id_skpd = %d
						  AND id_komponen_penilaian = %d
						  AND tahun_anggaran = %d
					", $id_skpd, $id_komponen_penilaian, $tahun_anggaran), ARRAY_A);
					if ($existing_data) {
						// $bukti_dukung_existing = json_decode(stripslashes($existing_data['bukti_dukung']), true);
						// $bukti_dukung = json_encode(array_merge($bukti_dukung_existing, $bukti_dukung));
						$bukti_dukung = json_encode($bukti_dukung);
						$updated = $wpdb->update(
							'esakip_pengisian_lke',
							array(
								'bukti_dukung' => $bukti_dukung,
								'update_at' => current_time('mysql')
							),
							array('id' => $existing_data['id'])
						);

						if ($updated !== false) {
							$ret['message'] = "Berhasil update bukti dukung usulan!";
						} else {
							$ret['status'] = 'error';
							$ret['message'] = "Gagal melakukan update nilai usulan: " . $wpdb->last_error;
						}
					} else {
						$bukti_dukung = json_encode($bukti_dukung);
						//cari id kom dan subkom ketika insert baru
						$id_subkomponen = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									id_subkomponen
								FROM esakip_komponen_penilaian
								WHERE id = %d
							", $id_komponen_penilaian)
						);
						if (!empty($id_subkomponen)) {
							$id_komponen = $wpdb->get_var(
								$wpdb->prepare("
									SELECT 
										id_komponen
									FROM esakip_subkomponen
									WHERE id = %d
								", $id_subkomponen)
							);
							if (empty($id_subkomponen)) {
								$ret['status'] = 'error';
								$ret['message'] = 'ID Komponen kosong!';
							}
						} else {
							$ret['status'] = 'error';
							$ret['message'] = 'ID Subomponen kosong!';
						}

						//jika sukses insert
						if ($ret['status'] = 'success') {
							$wpdb->insert(
								'esakip_pengisian_lke',
								array(
									'id_user' => $current_user->ID,
									'id_skpd' => $id_skpd,
									'id_komponen' => $id_komponen,
									'id_subkomponen' => $id_subkomponen,
									'id_komponen_penilaian' => $id_komponen_penilaian,
									'bukti_dukung' => $bukti_dukung,
									'tahun_anggaran' => $tahun_anggaran,
									'create_at' => current_time('mysql')
								)
							);
						}
					}
					$ret['data'] = json_decode($bukti_dukung, true);
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

	function get_penjelasan_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$status_jadwal = $wpdb->get_var(
					$wpdb->prepare("
						SELECT status
						FROM esakip_data_jadwal
						WHERE id =%d
					", $_POST['id_jadwal'])
				);
				// die(print_r($status_jadwal));

				if ($status_jadwal == 2) {
					$penjelasans = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								penjelasan,
								langkah_kerja
							FROM esakip_komponen_penilaian_history
							WHERE id_asli =%d
							  AND id_jadwal = %d
						", $_POST['id'], $_POST['id_jadwal']),
						ARRAY_A
					);
					// die(print_r($penjelasans));
				} else {
					$penjelasans = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								penjelasan,
								langkah_kerja
							FROM esakip_komponen_penilaian
							WHERE id =%d
						", $_POST['id']),
						ARRAY_A
					);
				}

				if ($penjelasans) {
					$tbody = "
					<tr class='text-left'>
						<td>
							<textarea class='textPenjelasan form-control' disabled style='background: transparent; min-height: 100px; border: 0;'>" . htmlspecialchars($penjelasans['penjelasan']) . "</textarea>
						</td>
						<td>
							<textarea class='textPenjelasan form-control' disabled style='background: transparent; min-height: 100px; border: 0;'>" . htmlspecialchars($penjelasans['langkah_kerja']) . "</textarea>
						</td>
					</tr>";
				} else {
					$tbody = "<tr class='text-center'><td colspan='2'>tidak ada data tersedia</td></tr>";
				}
				$ret['data'] = $tbody;
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

	public function get_table_skpd_kuesioner_menpan()
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

                $tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

                if ($ret['status'] == 'success') {
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
							$halaman_kuesioner_menpan = $this->functions->generatePage(array(
                                'nama_page' => 'Halaman Kuesioner Menpan ' . $tahun_anggaran,
                                'content' => '[kuesioner_menpan tahun_anggaran=' . $tahun_anggaran . ']',
                                'show_header' => 1,
                                'post_status' => 'private'
                            ));

                            $tbody .= "<tr>";
                            $tbody .= "<td style='text-transform: uppercase;'><a href='" . $halaman_kuesioner_menpan['url'] . "&id_skpd=" . $vv['id_skpd'] . "' target='_blank'>" . $vv['kode_skpd'] . " " . $vv['nama_skpd'] . "</a></td>";
                            $tbody .= "<td style='text-transform: uppercase;'></td>";
                            $tbody .= "<td style='text-transform: uppercase;'></td>";
                            $tbody .= "<td style='text-transform: uppercase;'></td>";
                            $tbody .= "<td style='text-transform: uppercase;'></td>";	
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

	public function get_table_skpd_kuesioner_mendagri()
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

                $tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

                if ($ret['status'] == 'success') {
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
							$halaman_kuesioner_mendagri = $this->functions->generatePage(array(
                                'nama_page' => 'Halaman Kuesioner Mendagri ' . $tahun_anggaran,
                                'content' => '[kuesioner_mendagri tahun_anggaran=' . $tahun_anggaran . ']',
                                'show_header' => 1,
                                'post_status' => 'private'
                            ));

                            $tbody .= "<tr>";
                            $tbody .= "<td style='text-transform: uppercase;'><a href='" . $halaman_kuesioner_mendagri['url'] . "&id_skpd=" . $vv['id_skpd'] . "' target='_blank'>" . $vv['kode_skpd'] . " " . $vv['nama_skpd'] . "</a></td>";
                            $tbody .= "<td style='text-transform: uppercase;'></td>";
                            $tbody .= "<td style='text-transform: uppercase;'></td>";
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

    public function tambah_kuesioner_menpan()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah data!',
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$id_kuesioner = null;

				if (!empty($_POST['id'])) {
					$id_kuesioner = $_POST['id'];
					$ret['message'] = 'Berhasil edit data!';
				}

				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				if (!empty($_POST['nama_kuesioner'])) {
					$nama_kuesioner = $_POST['nama_kuesioner'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nama Komponen kosong!';
				}
				if (!empty($_POST['nomor_urut'])) {
					$nomor_urut = $_POST['nomor_urut'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nomor Urut kosong!';
				}

				if ($ret['status'] === 'success') {

					if (!empty($id_kuesioner)) {
						$wpdb->update(
							'esakip_kuesioner_menpan',
							array(
								'nama_kuesioner' => $nama_kuesioner,
								'nomor_urut' => $nomor_urut,
							),
							array('id' => $id_kuesioner),
							array('%s', '%f', '%f'),
							array('%d')
						);
					} else {
						$wpdb->insert(
							'esakip_kuesioner_menpan',
							array(
								'tahun_anggaran' => $tahun_anggaran,
								'nama_kuesioner' => $nama_kuesioner,
								'nomor_urut' => $nomor_urut,
								'active' => 1,
							),
							array('%d', '%s', '%f', '%f', '%d')
						);
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

	public function get_table_kuesioner_menpan()
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
				$data_kuesioner = $wpdb->get_results(
					$wpdb->prepare("
						SELECT 
							* 
						FROM esakip_kuesioner_menpan
						WHERE tahun_anggaran = %d
						  AND active = 1
						ORDER BY nomor_urut ASC
						", $tahun_anggaran),
					ARRAY_A
				);

				$tbody = '';
				if (!empty($data_kuesioner)) {
					$counter = 1;
					foreach ($data_kuesioner as $kuesioner) {
						$btn = '';
						$counter2 = 1;

						$get_total_bobot_kuesioner_detail = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									SUM(bobot)
								FROM esakip_kuesioner_menpan_detail
								WHERE id_kuesioner = %d
								AND active = 1
								AND tipe_jawaban = 1
									OR tipe_jawaban = 5
							", $kuesioner['id'])
						);

						if ($get_total_bobot_kuesioner_detail >= 500 && $get_total_bobot_kuesioner_detail < 600) {
						    $total_bobot_kuesioner_detail = 500;
						} elseif ($total_bobot_kuesioner_detail < 100) {
						    $total_bobot_kuesioner_detail = round($get_total_bobot_kuesioner_detail * 2) / 2; 
						} else {
						    $total_bobot_kuesioner_detail = round($get_total_bobot_kuesioner_detail); 
						}

						
						$btn .= '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-primary' onclick='tambah_pertanyaan_kuesioner_menpan(\"" . $kuesioner['id'] . "\");' title='Tambah Pertanyaan'><span class='dashicons dashicons-plus'></span></button>";
						$btn .= "<button class='btn btn-warning' onclick='edit_data_kuesioner_menpan(\"" . $kuesioner['id'] . "\");' title='Edit Data Kuesioner'><span class='dashicons dashicons-edit'></span></button>";
						$btn .= "<button class='btn btn-danger' onclick='hapus_data_kuesioner_menpan(\"" . $kuesioner['id'] . "\");' title='Hapus Data Kuesioner'><span class='dashicons dashicons-trash'></span>";
						$btn .= '</div>';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-left'><b>" . $counter++ . "</b></td>";
						$tbody .= "<td class='text-left' colspan='3'><b>" . $kuesioner['nama_kuesioner'] . "</b></td>";
						$tbody .= "<td class='text-center'>".$total_bobot_kuesioner_detail."</td>";
						$tbody .= "<td class='text-center'></td>";
						$tbody .= "<td class='text-center'></td>";
						$tbody .= "<td class='text-center'>" . $btn . "</td>";
						$tbody .= "</tr>";

						$data_kuesioner_detail = $wpdb->get_results(
						    $wpdb->prepare("
						        SELECT 
						            * 
						        FROM esakip_kuesioner_menpan_detail
						        WHERE id_kuesioner = %d
						          AND active = 1
						        ORDER BY nomor_urut ASC
						    ", $kuesioner['id']),
						    ARRAY_A
						);

						if (!empty($data_kuesioner_detail)) {
						    $group = [];
						    foreach ($data_kuesioner_detail as $kuesioner_detail) {
						        $group[$kuesioner_detail['pertanyaan']][] = $kuesioner_detail;
						    }

						    foreach ($group as $pertanyaan => $rows) {
						        $rowspan = count($rows);
						        $get_id = array_column($rows, 'id');
						        $id = implode(',', $get_id);

						        foreach ($rows as $index => $row) {
						            $tbody .= "<tr>";

						            if ($index === 0) {
						                $btn = '<div class="btn-action-group">';
						                $btn .= "<button class='btn btn-warning' onclick='edit_data_kuesioner_menpan_detail([{$id}]);' title='Edit Data Pertanyaan'><span class='dashicons dashicons-edit'></span></button>";
						                $btn .= "<button class='btn btn-danger' onclick='hapus_data_kuesioner_menpan_detail([{$id}]);' title='Hapus Data Pertanyaan'><span class='dashicons dashicons-trash'></span></button>";
						                $btn .= '</div>';

						                $tbody .= "<td class='text-left' rowspan='{$rowspan}' style='vertical-align: middle;'></td>";
						                $tbody .= "<td class='text-left' rowspan='{$rowspan}' style='vertical-align: middle;'><b>" . $counter2++ . "</b></td>";
						                $tbody .= "<td class='text-left' colspan='2' rowspan='{$rowspan}' style='vertical-align: middle;'><b>" . $pertanyaan . "</b></td>";
						            }

						            $tbody .= "<td class='text-center'>" . $row['bobot'] . "</td>";
						            $tbody .= "<td class='text-left'>" . $row['jawaban'] . "</td>";

						            if ($index === 0) {
						                $tbody .= "<td class='text-left' rowspan='{$rowspan}' style='vertical-align: middle;'>" . nl2br($row['penjelasan']) . "</td>";
						                $tbody .= "<td class='text-center' rowspan='{$rowspan}' style='vertical-align: middle;'>" . $btn . "</td>";
						            }

						            $tbody .= "</tr>";
						        }
						    }
						}

					}
				} else {
					$tbody = "<tr><td colspan='8' class='text-center'>Tidak ada data tersedia</td></tr>";
				}

				$ret['data'] = $tbody;
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

	public function get_kuesioner_menpan_by_id()
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
							SELECT *
							FROM esakip_kuesioner_menpan
							WHERE id = %d
						", $_POST['id']),
						ARRAY_A
					);
					if ($data) {
						$default_urutan = $wpdb->get_var(
							$wpdb->prepare("
							SELECT MAX(nomor_urut)
							FROM esakip_kuesioner_menpan
							WHERE tahun_anggaran = %d
						", $_POST['id'])
						);

						if ($default_urutan === null) {
							$default_urutan = 0;
						}

						$ret['data'] = $data + ['default_urutan' => $default_urutan];
					} else {
						$ret = array(
							'status' => 'error',
							'message'   => 'Data Tidak Ditemukan!'
						);
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

	public function hapus_data_kuesioner_menpan()
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
					$cek_id = $wpdb->get_var(
						$wpdb->prepare("
							SELECT 
								id_kuesioner
							FROM esakip_kuesioner_menpan_detail
							WHERE id_kuesioner=%d
							  AND active = 1
						", $_POST['id'])
					);
					$cek_nama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT 
								nama_kuesioner
							FROM esakip_kuesioner_menpan
							WHERE id=%d
							  AND active = 1
						", $_POST['id'])
					);
					if (empty($cek_id)) {
						$ret['data'] = $wpdb->update(
							'esakip_kuesioner_menpan',
							array('active' => 0),
							array('id' => $_POST['id'])
						);
					} else {
						$ret = array(
							'status' => 'error',
							'message'   => 'Data ' . $cek_nama . ' memiliki Pertanyaan Kuesioner Aktif!'
						);
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

	public function get_detail_pertanyaan_mendagri()
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
	                $data_kuesioner = $wpdb->get_row(
	                    $wpdb->prepare("
	                        SELECT 
	                        	nama_kuesioner
	                        FROM esakip_kuesioner_mendagri
	                        WHERE id = %d
	                    ", $_POST['id']),
	                    ARRAY_A
	                );

	                if (!empty($data_kuesioner)) {
	                    $get_level = $wpdb->get_results(
	                        $wpdb->prepare("
	                            SELECT 
	                            	level
	                            FROM esakip_kuesioner_mendagri_detail
	                            WHERE id_kuesioner = %d
	                            	AND active = 1
	                            ORDER BY level ASC
	                        ", $_POST['id']),
	                        ARRAY_A
	                    );

	                    $existing_level = array();
	                    foreach ($get_level as $row) {
	                        if (isset($row['level'])) {
	                            $existing_level[] = $row['level'];
	                        }
	                    }

	                    $level = 1;
	                    if (!empty($existing_level)) {
	                        for ($i = 1; $i <= max($existing_level) + 1; $i++) {
	                            if (!in_array($i, $existing_level)) {
	                                $level = $i;
	                                break;
	                            }
	                        }
	                    }

	                    $ret['data'] = [
	                        'level' => $level,
	                        'kuesioner' => $data_kuesioner
	                    ];
	                } else {
	                    $ret = array(
	                        'status' => 'error',
	                        'message' => 'Data Pertanyaan tidak ditemukan!'
	                    );
	                }
	            } else {
	                $ret = array(
	                    'status' => 'error',
	                    'message' => 'Id Kosong!'
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

	public function generate_data_menpan()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil generate data!',
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

				$json_path = ESAKIP_PLUGIN_URL . 'public/media/input_kuesioner.json';
				if (empty($json_path)) {
					$ret['status'] = 'error';
					$ret['message'] = 'File Json tidak ditemukan!';
				}

				$json_data = file_get_contents($json_path);
				$json_kuesioner = json_decode($json_data, true);
				if (!is_array($json_kuesioner)) {
					$ret['status'] = 'error';
					$ret['message'] = 'Format Json tidak valid!';
				}

				$table_kuesioner = 'esakip_kuesioner_menpan';
				$table_kuesioner_detail = 'esakip_kuesioner_menpan_detail';

				foreach ($json_kuesioner as $data_kuesioner) {
					$k = $data_kuesioner['kuesioner'];

					$get_data_kuesioner = $wpdb->get_row($wpdb->prepare("
						SELECT 
							id 
						FROM $table_kuesioner 
						WHERE nama_kuesioner = %s 
								AND tahun_anggaran = %d
						", $k['nama_kuesioner'], $tahun_anggaran
					));

					$data = array(
						'nama_kuesioner' => $k['nama_kuesioner'],
						'bobot' => $k['bobot'],
						'nomor_urut' => $k['no_urut'],
						'tahun_anggaran' => $tahun_anggaran,
						'active' => 1,
						'update_at' => current_time('mysql')
					);

					if ($get_data_kuesioner) {
						$wpdb->update(
							$table_kuesioner,
							$data,
							array('id' => $get_data_kuesioner->id)
						);
						$id_kuesioner = $get_data_kuesioner->id;
					} else {
						$data['create_at'] = current_time('mysql');
						
						$wpdb->insert($table_kuesioner, $data);
						$id_kuesioner = $wpdb->insert_id;
					}

					if (!$id_kuesioner) {
						continue;
					}


					foreach ($k['soal'] as $soal) {
						foreach ($soal['jawaban'] as $jawaban) {

							$get_data_kuesioner_detail = $wpdb->get_row($wpdb->prepare("
								SELECT 
									id 
								FROM $table_kuesioner_detail 
								WHERE id_kuesioner = %d 
									AND pertanyaan = %s 
									AND jawaban = %s
								",$id_kuesioner, $soal['soal'], $jawaban['jawaban']
							));

							$data = array(
								'id_kuesioner' => $id_kuesioner,
								'pertanyaan' => $soal['soal'],
								'nomor_urut' => floatval($soal['no_urut']),
								'penjelasan' => $soal['penjelasan'],
								'tipe_soal' => $soal['tipe_soal'],
								'jawaban' => $jawaban['jawaban'],
								'bobot' => floatval($jawaban['bobot']),
								'tipe_jawaban' => $jawaban['tipe_jawaban'],
								'tahun_anggaran' => $tahun_anggaran,
								'active' => 1
							);

							if ($get_data_kuesioner_detail) {
								$wpdb->update(
									$table_kuesioner_detail,
									$data,
									array('id' => $get_data_kuesioner_detail->id)
								);
							} else {
								$wpdb->insert($table_kuesioner_detail, $data);
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

	public function submit_kuesioner_pertanyaan_menpan()
	{
	    global $wpdb;
	    $ret = array(
	        'status' => 'success',
	        'message' => 'Berhasil menyimpan data pertanyaan kuesioner!',
	        'data' => array()
	    );

	    if (!empty($_POST)) {
	        if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
	            if (empty($_POST['id_detail'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'ID kuesioner kosong!';
	            } else if (empty($_POST['nama_pertanyaan'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Pertanyaan tidak boleh kosong!';
	            } else if (!isset($_POST['penjelasan'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Penjelasan harus ada!';
	            } else if (empty($_POST['tahun_anggaran'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Tahun anggaran tidak boleh kosong!';
	            }

	            if ($ret['status'] != 'error') {
	                $tipe_soal = isset($_POST['tipe_jawaban']) ? $_POST['tipe_jawaban'] : '0';
	                $id_detail = isset($_POST['id_detail']) ? $_POST['id_detail'] : '0';
	                $nomor_urut = isset($_POST['nomor_urut']) ? floatval($_POST['nomor_urut']) : 0;
	                $nama_pertanyaan = sanitize_text_field($_POST['nama_pertanyaan']);
	                $penjelasan = sanitize_textarea_field($_POST['penjelasan']);
	                $tahun_anggaran = sanitize_text_field($_POST['tahun_anggaran']);

	                $id_kuesioner = isset($_POST['id_pertanyaan']) ? explode(',', $_POST['id_pertanyaan']) : array(); 
	                if (empty($id_kuesioner)) {
	                    $id_kuesioner = $wpdb->insert_id;
	                } else {
	                    $wpdb->update('esakip_kuesioner_menpan_detail', array(
	                        'tahun_anggaran' => $tahun_anggaran
	                    ), array('id' => $id_kuesioner));
	                }

	                if ($tipe_soal == '0') {
					    // Esai
					    if (is_array($id_kuesioner) && count($id_kuesioner) > 0) {
					        foreach ($id_kuesioner as $id) {
					            $data = $wpdb->get_var($wpdb->prepare("
					            	SELECT 
					            		COUNT(*) 
					            	FROM esakip_kuesioner_menpan_detail 
					            	WHERE id = %d 
					            		AND tahun_anggaran = %d
					            	",$id, $tahun_anggaran
					            ));

					            if ($data) {
					                $wpdb->update('esakip_kuesioner_menpan_detail', array(
					                    'nomor_urut' => $nomor_urut,
					                    'pertanyaan' => $nama_pertanyaan,
					                    'penjelasan' => $penjelasan,
					                    'tipe_soal' => '0',
					                    'bobot' => $_POST['bobot_esai'],
					                    'tipe_jawaban' => 5,
					                    'active' => 1
					                ), array('id' => $id));
					            } else {
					                $wpdb->insert('esakip_kuesioner_menpan_detail', array(
					                    'id_kuesioner' => $id_detail,
					                    'nomor_urut' => $nomor_urut,
					                    'pertanyaan' => $nama_pertanyaan,
					                    'penjelasan' => $penjelasan,
					                    'tipe_soal' => '0',
					                    'tipe_jawaban' => 5,
					                    'bobot' => $_POST['bobot_esai'],
					                    'tahun_anggaran' => $tahun_anggaran,
					                    'active' => 1
					                ));
					            }
					        }
					    }
					} elseif ($tipe_soal == '1') {
					    if (!empty($_POST['daftar_jawaban']) && is_array($_POST['daftar_jawaban'])) {
					        $i = 0;
					        foreach ($_POST['daftar_jawaban'] as $row) {
					            $jawaban = isset($row['jawaban']) ? sanitize_text_field($row['jawaban']) : '';
					            $bobot = isset($row['bobot']) ? floatval($row['bobot']) : 0;
					            $tipe_jawaban = isset($row['tipe_jawaban']) ? intval($row['tipe_jawaban']) : 0;
					            $id = isset($id_kuesioner[$i]) ? intval($id_kuesioner[$i]) : 0;

					            $data = $wpdb->get_var($wpdb->prepare("
					            	SELECT 
					            		COUNT(*) 
					            	FROM esakip_kuesioner_menpan_detail 
					            	WHERE id = %d 
					            		AND tahun_anggaran = %d
					            	",$id, $tahun_anggaran
					            ));

					            if ($data) {
					                $wpdb->update('esakip_kuesioner_menpan_detail', array(
					                    'nomor_urut' => $nomor_urut,
					                    'pertanyaan' => $nama_pertanyaan,
					                    'jawaban' => $jawaban,
					                    'bobot' => $bobot,
					                    'tipe_soal' => '1',
					                    'penjelasan' => $penjelasan,
					                    'tipe_jawaban' => $tipe_jawaban,
					                    'active' => 1
					                ), array('id' => $id));
					            } else {
					                $wpdb->insert('esakip_kuesioner_menpan_detail', array(
					                    'id_kuesioner' => $id_detail,
					                    'nomor_urut' => $nomor_urut,
					                    'pertanyaan' => $nama_pertanyaan,
					                    'jawaban' => $jawaban,
					                    'bobot' => $bobot,
					                    'tipe_soal' => '1',
					                    'penjelasan' => $penjelasan,
					                    'tahun_anggaran' => $tahun_anggaran,
					                    'tipe_jawaban' => $tipe_jawaban,
					                    'active' => 1
					                ));
					            }

					            $i++;
					        }
					    } else {
					        $ret['status'] = 'error';
					        $ret['message'] = 'Data pilihan ganda tidak ditemukan!';
					    }
					}
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
	public function get_kuesioner_menpan_detail_by_id()
	{
	    global $wpdb;
	    $ret = array(
	        'status' => 'success',
	        'message' => 'Berhasil mengambil data!',
	        'data' => array()
	    );

	    if (!empty($_POST)) {
	        if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
	            if (empty($_POST['id'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'ID kuesioner kosong!';
	                die(json_encode($ret));
	            } else if (empty($_POST['tahun_anggaran'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Tahun anggaran tidak boleh kosong!';
	                die(json_encode($ret));
	            }

	            $id = is_array($_POST['id']) ? $_POST['id'] : [$_POST['id']];

	            $query = "
	                SELECT 
	                	* 
	                FROM esakip_kuesioner_menpan_detail
	                WHERE id IN (" . implode(',', array_map('intval', $id)) . ")
	                  AND active = 1
	                ORDER BY nomor_urut ASC
	            ";
	            $get_results = $wpdb->get_results($query, ARRAY_A);

	            if ($get_results) {
	                $get_data = [];
	                $jawaban_map = [];

	                foreach ($get_results as $row) {
	                    if ($row['tipe_soal'] == '1') {
	                        $jawaban_map[$row['tipe_jawaban']] = array(
	                            'jawaban' => $row['jawaban'],
	                            'bobot' => $row['bobot']
	                        );
	                    }

	                    $get_data[] = array(
	                        'id_pertanyaan' => $row['id'],
	                        'id_kuesioner' => $row['id_kuesioner'],
	                        'bobot' => $row['bobot'],
	                        'pertanyaan' => $row['pertanyaan'],
	                        'jawaban' => $row['jawaban'],
	                        'penjelasan' => $row['penjelasan'],
	                        'nomor_urut' => $row['nomor_urut'],
	                        'tipe_soal' => $row['tipe_soal'],
	                        'tipe_jawaban' => $row['tipe_jawaban']
	                    );
	                }

	                $data_kuesioner = $wpdb->get_row(
	                    $wpdb->prepare("
	                        SELECT 
	                        	nama_kuesioner
	                        FROM esakip_kuesioner_menpan
	                        WHERE id = %d
	                    ", $get_data[0]['id_kuesioner']),
	                    ARRAY_A
	                );

	                $ret['data'] = $get_data;
	                $ret['kuesioner'] = $data_kuesioner;
	                $ret['jawaban_map'] = $jawaban_map;
	            } else {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Data tidak ditemukan.';
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

	function hapus_data_kuesioner_menpan_detail()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus pertanyaan!'
		);

		if (!empty($_POST)) {
	        if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
	            if (empty($_POST['id'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'ID kuesioner kosong!';
	            }
	            $ids = is_array($_POST['id']) ? $_POST['id'] : [$_POST['id']];
				$ids = array_map('intval', $ids);
				foreach ($ids as $id) {
	                $wpdb->update(
	                    'esakip_kuesioner_menpan_detail',
	                    array('active' => 0),
	                    array('id' => $id),
	                    array('%d'),
	                    array('%d')
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

	function copy_data_kuesioner_menpan()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil copy data kuesioner menpan!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran_sumber_kuesioner'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran Sumber Kuesioner Tidak Boleh Kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran_tujuan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran Halaman Ini Tidak Boleh Kosong!';
				}

				if ($ret['status'] != 'error') {
					$this_tahun_anggaran = $_POST['tahun_anggaran_tujuan'];
					$tahun_anggaran_sumber_kuesioner = $_POST['tahun_anggaran_sumber_kuesioner'];

					/** Kosongkan tabel data yang akan disii data baru hasil copy */
					$wpdb->update(
						'esakip_kuesioner_menpan',
						array(
							'active' => 0
						),
						array(
							'tahun_anggaran' => $this_tahun_anggaran
						)
					);

					$wpdb->update(
						'esakip_kuesioner_menpan_detail',
						array(
							'active' => 0
						),
						array(
							'tahun_anggaran' => $this_tahun_anggaran
						)
					);

					$data_sumber = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT
								*
							FROM
								esakip_kuesioner_menpan
							WHERE tahun_anggaran=%d
								AND active=%d
							',
							$tahun_anggaran_sumber_kuesioner,
							1
						),
						ARRAY_A
					);

					// copy data kuesioner menpan
					$id_kuesioner = array();
					if (!empty($data_sumber)) {
						foreach ($data_sumber as $k => $kuesioner) {
							$data_kuesioner = array(
								'nama_kuesioner' => $kuesioner['nama_kuesioner'],
								'nomor_urut' => $kuesioner['nomor_urut'],
								'bobot' => $kuesioner['bobot'],
								'active' => 1,
								'tahun_anggaran' => $this_tahun_anggaran
							);

							$wpdb->insert(
								'esakip_kuesioner_menpan',
								$data_kuesioner
							);

							$id_kuesioner_baru = $wpdb->insert_id;
							$id_kuesioner[$kuesioner['id']] = $id_kuesioner_baru;

							// copy data kuesioner menpan
							$data_kuesioner_sumber_detail = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT
										*
									FROM
										esakip_kuesioner_menpan_detail
									WHERE
										id_kuesioner=%d
										AND tahun_anggaran=%d
										AND active=%d
									",
									$kuesioner['id'],
									$tahun_anggaran_sumber_kuesioner,
									1
								),
								ARRAY_A
							);
							// print_r($data_kuesioner_sumber_detail); die($wpdb->last_query);
							if (!empty($data_kuesioner_sumber_detail)) {
								foreach ($data_kuesioner_sumber_detail as $kk => $kuesioner_detail) {
									$data_kuesioner_detail = array(
										'id_kuesioner' => $id_kuesioner_baru,
					                    'nomor_urut' => $kuesioner_detail['nomor_urut'],
					                    'jawaban' => $kuesioner_detail['jawaban'],
					                    'pertanyaan' => $kuesioner_detail['pertanyaan'],
					                    'penjelasan' => $kuesioner_detail['penjelasan'],
					                    'tipe_soal' => $kuesioner_detail['tipe_soal'],
					                    'tipe_jawaban' => $kuesioner_detail['tipe_jawaban'],
					                    'bobot' => $kuesioner_detail['bobot'],
										'active' => 1,
										'tahun_anggaran' => $this_tahun_anggaran
									);

									$wpdb->insert('esakip_kuesioner_menpan_detail', $data_kuesioner_detail);
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

	public function get_table_kuesioner_mendagri()
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
	            $data_kuesioner = $wpdb->get_results(
	                $wpdb->prepare("
	                    SELECT 
	                        * 
	                    FROM esakip_kuesioner_mendagri
	                    WHERE tahun_anggaran = %d
	                      AND active = 1
	                    ORDER BY nomor_urut ASC
	                    ", $tahun_anggaran),
	                ARRAY_A
	            );

	            $tbody = '';
	            if (!empty($data_kuesioner)) {
	                $counter = 1;
	                foreach ($data_kuesioner as $kuesioner) {
	                    $btn = '';
	                    $counter2 = 1;
	                    $data_level = $wpdb->get_results(
						    $wpdb->prepare("
						        SELECT 
						        	level 
						        FROM esakip_kuesioner_mendagri_detail
						        WHERE id_kuesioner = %d 
						        	AND active = 1
						    ", $kuesioner['id']),
						    ARRAY_A
						);

						$get_level = array();
						foreach ($data_level as $row) {
						    $get_level[] = intval($row['level']);
						}

						$angka = [1, 2, 3, 4, 5];
						$true = count(array_diff($angka, $get_level)) > 0;

						$btn = '<div class="btn-action-group">';
						if ($true) {
						    $btn .= "<button class='btn btn-primary' onclick='tambah_pertanyaan_kuesioner_mendagri(\"" . $kuesioner['id'] . "\");' title='Tambah Pertanyaan'><span class='dashicons dashicons-plus'></span></button>";
						}
	                    $btn .= "<button class='btn btn-warning' onclick='edit_data_kuesioner_mendagri(\"" . $kuesioner['id'] . "\");' title='Edit Data Kuesioner'><span class='dashicons dashicons-edit'></span></button>";
	                    $btn .= "<button class='btn btn-danger' onclick='hapus_data_kuesioner_mendagri(\"" . $kuesioner['id'] . "\");' title='Hapus Data Kuesioner'><span class='dashicons dashicons-trash'></span>";
	                    $btn .= '</div>';

	                    $tbody .= "<tr>";
	                    $tbody .= "<td class='text-left'><b>" . $counter++ . "</b></td>";
	                    $tbody .= "<td class='text-left' colspan='2' style='vertical-align: middle;'><b>" . $kuesioner['nama_kuesioner'] . "</b></td>";
	                    $tbody .= "<td class='text-left'>".$kuesioner['deskripsi']."</td>";
	                    $tbody .= "<td class='text-center'></td>";
	                    $tbody .= "<td class='text-center'></td>";
	                    $tbody .= "<td class='text-center'>" . $btn . "</td>";
	                    $tbody .= "</tr>";

	                    $data_kuesioner_detail = $wpdb->get_results(
	                        $wpdb->prepare("
	                            SELECT 
	                                * 
	                            FROM esakip_kuesioner_mendagri_detail
	                            WHERE id_kuesioner = %d
	                              AND active = 1
	                            ORDER BY level ASC
	                        ", $kuesioner['id']),
	                        ARRAY_A
	                    );

	                    if (!empty($data_kuesioner_detail)) {
	                        foreach ($data_kuesioner_detail as $kuesioner_detail) {
	                            $tbody .= "<tr>";

	                            $btn  = '<div class="btn-action-group">';
								$btn .= "<button class='btn btn-success' onclick='handleTambahBuktiDukung(\"" . $kuesioner_detail['id'] . "\");' title='Tambah Bukti Dukung'><span class='dashicons dashicons-insert'></span></button>";
	                            $btn .= "<button class='btn btn-warning' onclick='edit_data_kuesioner_mendagri_detail(\"" . $kuesioner_detail['id'] . "\");' title='Edit Data Pertanyaan'><span class='dashicons dashicons-edit'></span></button>";
	                            $btn .= "<button class='btn btn-danger' onclick='hapus_data_kuesioner_mendagri_detail(\"" . $kuesioner_detail['id'] . "\");' title='Hapus Data'><span class='dashicons dashicons-trash'></span></button>";
	                            $btn .= '</div>';

	                            $tbody .= "<td class='text-left' style='vertical-align: middle;'></td>";
	                            $tbody .= "<td class='text-left' style='vertical-align: middle;'><b>" . $counter2++ . "</b></td>";
	                            $tbody .= "<td class='text-left' style='vertical-align: middle;'>" . $kuesioner_detail['indikator'] . "</td>";
	                            $tbody .= "<td></td>";

	                            if ($kuesioner_detail['level'] == 1) {
						            $level = 'I';
						        } else if ($kuesioner_detail['level'] == 2) {
						            $level = 'II';
						        }  else if ($kuesioner_detail['level'] == 3) {
						            $level = 'III';
						        }  else if ($kuesioner_detail['level'] == 4) {
						            $level = 'IV';
						        }  else if ($kuesioner_detail['level'] == 5) {
						            $level = 'V';
						        } else {
						            $level = $kuesioner_detail['level'];
						        }
						        $tbody .= "<td class='text-center'>" . $level . "</td>";

	                            $tbody .= "<td class='text-left' style='vertical-align: middle;'>" . nl2br($kuesioner_detail['penjelasan']) . "</td>";
	                            $tbody .= "<td class='text-center' style='vertical-align: middle;'>" . $btn . "</td>";
	                            $tbody .= "</tr>";

								$data_dukung = $wpdb->get_results(
									$wpdb->prepare("
									SELECT
										*
									FROM esakip_data_dukung_kuesioner_mendagri
									WHERE id_kuesioner_mendagri_detail = %d
										AND tahun_anggaran = %d
										AND active = 1
									", $kuesioner_detail['id'], $tahun_anggaran),
									ARRAY_A
								);	
						
								if(!empty($data_dukung)) {
									$no_bukti = 1;
									foreach ($data_dukung as $bukti) {
										
										//Tombol Aksi Bukti Dukung
										$btn  = '<div class="btn-action-group">';								
										$btn .= "<button class='btn btn-warning' onclick='handleEditBuktiDukung(\"" . $bukti['id'] . "\");' title='Edit Data Dukung'><span class='dashicons dashicons-edit'></span></button>";
										$btn .= "<button class='btn btn-danger' onclick='handleHapusBuktiDukung(\"" . $bukti['id'] . "\");' title='Hapus Data'><span class='dashicons dashicons-trash'></span></button>";
										$btn .= '</div>';
										
										//Kolom Tabel Bukti Dukung
										$tbody .= "<tr class='bukti-dukung'>";
										$tbody .= "<td></td>"; //no urut utama
										$tbody .= "<td></td>"; //no urut indikator
										$tbody .= "<td colspan='3'>" . $no_bukti++ .". " . esc_html($bukti['jenis_bukti_dukung']) . "</td>";
										$tbody .= "<td>" . esc_html($bukti['dokumen_upload']) . "</td>"; //dokumen upload
										$tbody .= "<td class='text-center'>{$btn}</td>";
										$tbody .= "</tr>";
									}
								}
	                        }
	                    }
	                }
	            } else {
	                $tbody = "<tr><td colspan='10' class='text-center'>Tidak ada data tersedia</td></tr>";
	            }

	            $ret['data'] = $tbody;
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


	public function tambah_kuesioner_mendagri()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah data!',
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$id_kuesioner = null;

				if (!empty($_POST['id'])) {
					$id_kuesioner = $_POST['id'];
					$ret['message'] = 'Berhasil edit data!';
				}

				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				if (!empty($_POST['nama_kuesioner'])) {
					$nama_kuesioner = $_POST['nama_kuesioner'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nama Komponen kosong!';
				}
				if (!empty($_POST['deskripsi'])) {
					$deskripsi = $_POST['deskripsi'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Deskripsi kosong!';
				}
				if (!empty($_POST['nomor_urut'])) {
					$nomor_urut = $_POST['nomor_urut'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nomor Urut kosong!';
				}

				if ($ret['status'] === 'success') {

					if (!empty($id_kuesioner)) {
						$wpdb->update(
							'esakip_kuesioner_mendagri',
							array(
								'nama_kuesioner' => $nama_kuesioner,
								'deskripsi' => $deskripsi,
								'nomor_urut' => $nomor_urut,
							),
							array('id' => $id_kuesioner),
							array('%s', '%s', '%f'),
							array('%d')
						);
					} else {
						$wpdb->insert(
							'esakip_kuesioner_mendagri',
							array(
								'tahun_anggaran' => $tahun_anggaran,
								'nama_kuesioner' => $nama_kuesioner,
								'deskripsi' => $deskripsi,
								'nomor_urut' => $nomor_urut,
								'active' => 1,
							),
							array('%d', '%s', '%s', '%f', '%d')
						);
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
	public function get_kuesioner_mendagri_by_id()
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
							SELECT *
							FROM esakip_kuesioner_mendagri
							WHERE id = %d
						", $_POST['id']),
						ARRAY_A
					);
					if ($data) {
						$default_urutan = $wpdb->get_var(
							$wpdb->prepare("
							SELECT MAX(nomor_urut)
							FROM esakip_kuesioner_mendagri
							WHERE tahun_anggaran = %d
						", $_POST['id'])
						);

						if ($default_urutan === null) {
							$default_urutan = 0;
						}

						$ret['data'] = $data + ['default_urutan' => $default_urutan];
					} else {
						$ret = array(
							'status' => 'error',
							'message'   => 'Data Tidak Ditemukan!'
						);
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
	public function hapus_data_kuesioner_mendagri()
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
					$cek_id = $wpdb->get_var(
						$wpdb->prepare("
							SELECT 
								id_kuesioner
							FROM esakip_kuesioner_mendagri_detail
							WHERE id_kuesioner=%d
							  AND active = 1
						", $_POST['id'])
					);
					$cek_nama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT 
								nama_kuesioner
							FROM esakip_kuesioner_mendagri
							WHERE id=%d
							  AND active = 1
						", $_POST['id'])
					);
					if (empty($cek_id)) {
						$ret['data'] = $wpdb->update(
							'esakip_kuesioner_mendagri',
							array('active' => 0),
							array('id' => $_POST['id'])
						);
					} else {
						$ret = array(
							'status' => 'error',
							'message'   => 'Data ' . $cek_nama . ' memiliki Pertanyaan Kuesioner Aktif!'
						);
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
	public function submit_kuesioner_pertanyaan_mendagri()
	{
	    global $wpdb;
	    $ret = array(
	        'status' => 'success',
	        'message' => 'Berhasil menyimpan data pertanyaan kuesioner!',
	        'data' => array()
	    );

	    if (!empty($_POST)) {
	        if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
	            if (empty($_POST['id_detail'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'ID kuesioner kosong!';
	            } else if (empty($_POST['indikator'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Indikator tidak boleh kosong!';
	            } else if (!isset($_POST['penjelasan'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Penjelasan tidak boleh kosong!';
	            } else if (empty($_POST['tahun_anggaran'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Tahun anggaran tidak boleh kosong!';
	            }

	            $id_detail = intval($_POST['id_detail']);
	            $id_pertanyaan = isset($_POST['id_pertanyaan']) && $_POST['id_pertanyaan'] !== '' ? intval($_POST['id_pertanyaan']) : 0;
	            $indikator = sanitize_text_field($_POST['indikator']);
	            $penjelasan = sanitize_textarea_field($_POST['penjelasan']);
	            $tahun_anggaran = sanitize_text_field($_POST['tahun_anggaran']);
	            $level = intval($_POST['level']);

	            if ($id_pertanyaan <= 0) {
	                $wpdb->insert('esakip_kuesioner_mendagri_detail', array(
	                    'id_kuesioner' => $id_detail,
	                    'level' => $level,
	                    'indikator' => $indikator,
	                    'penjelasan' => $penjelasan,
	                    'tahun_anggaran' => $tahun_anggaran,
	                    'active' => 1
	                ));
	            } else {
	                $wpdb->update('esakip_kuesioner_mendagri_detail', array(
	                    'level' => $level,
	                    'indikator' => $indikator,
	                    'penjelasan' => $penjelasan,
	                    'tahun_anggaran' => $tahun_anggaran,
	                    'active' => 1
	                ), array('id' => $id_pertanyaan));
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
	public function get_kuesioner_mendagri_detail_by_id()
	{
	    global $wpdb;
	    $ret = array(
	        'status' => 'success',
	        'message' => 'Berhasil mengambil data!',
	        'data' => array()
	    );

	    if (!empty($_POST)) {
	        if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
	            if (empty($_POST['id'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'ID kuesioner kosong!';
	                die(json_encode($ret));
	            } else if (empty($_POST['tahun_anggaran'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Tahun anggaran tidak boleh kosong!';
	                die(json_encode($ret));
	            }

	            $get_kuesioner = $wpdb->get_row(
	                $wpdb->prepare("
	                    SELECT 
	                        * 
	                    FROM esakip_kuesioner_mendagri_detail
	                    WHERE id = %d 
	                        AND active = 1
	                ", $_POST['id']),
	                ARRAY_A
	            );

	            if ($get_kuesioner) {
	                $id_kuesioner = $get_kuesioner['id_kuesioner']; 

	                $get_data = array(
	                    'id_pertanyaan' => $get_kuesioner['id'],
	                    'id_kuesioner' => $id_kuesioner,
	                    'indikator' => $get_kuesioner['indikator'],
	                    'penjelasan' => $get_kuesioner['penjelasan'],
	                    'jenis_bukti_dukung' => $get_kuesioner['jenis_bukti_dukung'],
	                    'level' => $get_kuesioner['level']
	                );

	                $data_kuesioner = $wpdb->get_row(
	                    $wpdb->prepare("
	                        SELECT nama_kuesioner
	                        FROM esakip_kuesioner_mendagri
	                        WHERE id = %d
	                    ", $id_kuesioner),
	                    ARRAY_A
	                );

	                $ret['data'] = $get_data;
	                $ret['kuesioner'] = $data_kuesioner ?: array('nama_kuesioner' => '-');
	            } else {
	                $ret['status'] = 'error';
	                $ret['message'] = 'Data tidak ditemukan.';
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

	function hapus_data_kuesioner_mendagri_detail()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus pertanyaan!'
		);

		if (!empty($_POST)) {
	        if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
	            if (empty($_POST['id'])) {
	                $ret['status'] = 'error';
	                $ret['message'] = 'ID kuesioner kosong!';
	            }
                $wpdb->update(
                    'esakip_kuesioner_mendagri_detail',
                    array('active' => 0),
                    array('id' => $_POST['id']),
                    array('%d')
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

	function copy_data_kuesioner_mendagri()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil copy data kuesioner mendagri!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran_sumber_kuesioner'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran Sumber Kuesioner Tidak Boleh Kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran_tujuan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran Halaman Ini Tidak Boleh Kosong!';
				}

				if ($ret['status'] != 'error') {
					$this_tahun_anggaran = $_POST['tahun_anggaran_tujuan'];
					$tahun_anggaran_sumber_kuesioner = $_POST['tahun_anggaran_sumber_kuesioner'];

					/** Kosongkan tabel data yang akan disii data baru hasil copy */
					$wpdb->update(
						'esakip_kuesioner_mendagri',
						array(
							'active' => 0
						),
						array(
							'tahun_anggaran' => $this_tahun_anggaran
						)
					);

					$wpdb->update(
						'esakip_kuesioner_mendagri_detail',
						array(
							'active' => 0
						),
						array(
							'tahun_anggaran' => $this_tahun_anggaran
						)
					);

					$data_sumber = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT
								*
							FROM
								esakip_kuesioner_mendagri
							WHERE tahun_anggaran=%d
								AND active=%d
							',
							$tahun_anggaran_sumber_kuesioner,
							1
						),
						ARRAY_A
					);

					// copy data kuesioner mendagri
					$id_kuesioner = array();
					if (!empty($data_sumber)) {
						foreach ($data_sumber as $k => $kuesioner) {
							$data_kuesioner = array(
								'nama_kuesioner' => $kuesioner['nama_kuesioner'],
								'deskripsi' => $kuesioner['deskripsi'],
								'nomor_urut' => $kuesioner['nomor_urut'],
								'active' => 1,
								'tahun_anggaran' => $this_tahun_anggaran
							);

							$wpdb->insert(
								'esakip_kuesioner_mendagri',
								$data_kuesioner
							);

							$id_kuesioner_baru = $wpdb->insert_id;
							$id_kuesioner[$kuesioner['id']] = $id_kuesioner_baru;

							// copy data kuesioner mendagri
							$data_kuesioner_sumber_detail = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT
										*
									FROM
										esakip_kuesioner_mendagri_detail
									WHERE
										id_kuesioner=%d
										AND tahun_anggaran=%d
										AND active=%d
									",
									$kuesioner['id'],
									$tahun_anggaran_sumber_kuesioner,
									1
								),
								ARRAY_A
							);
							// print_r($data_kuesioner_sumber_detail); die($wpdb->last_query);
							if (!empty($data_kuesioner_sumber_detail)) {
								foreach ($data_kuesioner_sumber_detail as $kk => $kuesioner_detail) {
									$data_kuesioner_detail = array(
										'id_kuesioner' => $id_kuesioner_baru,
					                    'level' => $kuesioner_detail['level'],
					                    'indikator' => $kuesioner_detail['indikator'],
					                    'penjelasan' => $kuesioner_detail['penjelasan'],
					                    'jenis_bukti_dukung' => $kuesioner_detail['jenis_bukti_dukung'],
										'active' => 1,
										'tahun_anggaran' => $this_tahun_anggaran
									);

									$wpdb->insert('esakip_kuesioner_mendagri_detail', $data_kuesioner_detail);
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
	public function generate_data_mendagri()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil generate data!',
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

				$json_path = ESAKIP_PLUGIN_URL . 'public/media/input_kuesioner_mendagri.json';
				if (empty($json_path)) {
					$ret['status'] = 'error';
					$ret['message'] = 'File Json tidak ditemukan!';
				}

				$json_data = file_get_contents($json_path);
				$json_kuesioner = json_decode($json_data, true);
				if (!is_array($json_kuesioner)) {
					$ret['status'] = 'error';
					$ret['message'] = 'Format Json tidak valid!';
				}

				$table_kuesioner = 'esakip_kuesioner_mendagri';
				$table_kuesioner_detail = 'esakip_kuesioner_mendagri_detail';

				foreach ($json_kuesioner as $data_kuesioner) {
					$k = $data_kuesioner['kuesioner'];

					$get_data_kuesioner = $wpdb->get_row($wpdb->prepare("
						SELECT 
							id 
						FROM $table_kuesioner 
						WHERE nama_kuesioner = %s 
								AND tahun_anggaran = %d
						", $k['nama'], $tahun_anggaran
					));

					$data = array(
						'nama_kuesioner' => $k['nama'],
						'nomor_urut' => $k['nomor_urut'],
						'deskripsi' => $k['deskripsi'],
						'tahun_anggaran' => $tahun_anggaran,
						'active' => 1,
						'update_at' => current_time('mysql')
					);

					if ($get_data_kuesioner) {
						$wpdb->update(
							$table_kuesioner,
							$data,
							array('id' => $get_data_kuesioner->id)
						);
						$id_kuesioner = $get_data_kuesioner->id;
					} else {
						$data['create_at'] = current_time('mysql');
						
						$wpdb->insert($table_kuesioner, $data);
						$id_kuesioner = $wpdb->insert_id;
					}

					if (!$id_kuesioner) {
						continue;
					}


					foreach ($k['soal'] as $soal) {
						$get_data_kuesioner_detail = $wpdb->get_row($wpdb->prepare("
							SELECT 
								id 
							FROM $table_kuesioner_detail 
							WHERE id_kuesioner = %d 
								AND indikator = %s 
								AND level = %s
							",$id_kuesioner, $soal['indikator'], $soal['level']
						));


						$data = array(
							'id_kuesioner' => $id_kuesioner,
							'indikator' => $soal['indikator'],
							'level' => $soal['level'],
							'penjelasan' => $soal['penjelasan'],
							'jenis_bukti_dukung' => str_replace("\\n", "\n", $soal['bukti_dukung']),
							'dokumen_upload' => json_encode([]),
							'tahun_anggaran' => $tahun_anggaran,
							'active' => 1
						);

						if ($get_data_kuesioner_detail) {
							$wpdb->update(
								$table_kuesioner_detail,
								$data,
								array('id' => $get_data_kuesioner_detail->id)
							);
							$id_detail = $get_data_kuesioner_detail->id;
						} else {
							$wpdb->insert($table_kuesioner_detail, $data);
							$id_detail = $wpdb->insert_id;
						}
						$wpdb->delete('esakip_data_dukung_kuesioner_mendagri', array(
							'id_kuesioner_mendagri_detail' => $id_detail,
							'tahun_anggaran' => $tahun_anggaran
						));

						$bukti_list = explode("\n", str_replace("\\n", "\n", $soal['bukti_dukung']));
						foreach($bukti_list as $bukti) {
							$bukti = trim($bukti);
							if (!$bukti) continue;

							$wpdb->insert('esakip_data_dukung_kuesioner_mendagri', array(
								'id_kuesioner_mendagri_detail' => $id_detail,
								'jenis_bukti_dukung' => $bukti,
								'tahun_anggaran' => $tahun_anggaran,
								'created_at' => current_time('mysql'),
								'updated_at' => current_time('mysql'),
								'active' => 1
							));
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
	public function tambah_bukti_dukung_mendagri() 
	{
    	global $wpdb;
	    $ret = array(
			'status' => 'success',
			'message' => 'Berhasil menyimpan data bukti dukung!',
		);	

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$id_bukti = null;

				if (!empty($_POST['id_bukti'])) {
					$id_bukti = $_POST['id_bukti'];
					$ret['message'] = 'Berhasil edit bukti dukung!';
				}

				if (!empty($_POST['id_detail'])) {
					$id_detail = $_POST['id_detail'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'ID detail kosong!';
				}

				if (!empty($_POST['jenis_bukti_dukung'])) {
					$jenis_bukti_dukung = sanitize_text_field($_POST['jenis_bukti_dukung']);
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Jenis bukti dukung tidak boleh kosong!';
				}

				$dokumen_upload = !empty($_POST['dokumen_upload']) ? json_encode($_POST['dokumen_upload']) : '';

				if ($ret['status'] === 'success') {
					$tahun_anggaran = date('Y');

					if (!empty($id_bukti)) {
						$wpdb->update(
							'esakip_data_dukung_kuesioner_mendagri',
							array(
								'jenis_bukti_dukung' => $jenis_bukti_dukung,
								'dokumen_upload' => $dokumen_upload,
								'updated_at' => current_time('mysql'),
							),
							array('id' => $id_bukti),
							array('%s', '%s', '%s'),
							array('%d')
						);
					} else {
						$wpdb->insert(
							'esakip_data_dukung_kuesioner_mendagri',
							array(
								'id_kuesioner_mendagri_detail' => $id_detail,
								'jenis_bukti_dukung' => $jenis_bukti_dukung,
								'dokumen_upload' => $dokumen_upload,	
								'tahun_anggaran' => $tahun_anggaran,
								'created_at' => current_time('mysql'),
								'updated_at' => current_time('mysql'),
								'active' => 1
							),
							array('%d', '%s', '%s', '%d', '%s', '%s', '%d')
						);
					}
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message' => 'API key tidak sesuai!'
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
	public function hapus_bukti_dukung_mendagri()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus bukti dukung!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID bukti dukung kosong';
				} else {
					$wpdb->update(
						'esakip_data_dukung_kuesioner_mendagri',
						array('active' => 0),
						array('id' => intval($_POST['id'])),
						array('%d'),
						array('%d')
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
	public function get_bukti_dukung_kuesioner_by_id()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil mengambil data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID bukti kosong!';
					die(json_encode($ret));
				} else if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
					die(json_encode($ret));
				}

				$get_kuesioner = $wpdb->get_row(
					$wpdb->prepare("
					SELECT
						*
					FROM esakip_data_dukung_kuesioner_mendagri
					WHERE id = %d
						AND	active = 1
					", $_POST['id']),
					ARRAY_A
				);

				if ($get_kuesioner) {
					$get_data = array(
						'id_bukti' => $get_kuesioner['id'],
						'id_detail' => $get_kuesioner['id_kuesioner_mendagri_detail'],
						'jenis_bukti_dukung' => $get_kuesioner['jenis_bukti_dukung'],
						'tahun_anggaran' => $get_kuesioner['tahun_anggaran'],
						'dokumen_upload' => json_decode($get_kuesioner['dokumen_upload'], true),
					);

					$ret['data']= $get_data;
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Data tidak ditemukan.';
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message' => 'API key tida sesuai!'
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
	public function get_table_variabel_pengisian_mendagri()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);
		
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
					die(json_encode($ret));
				}
				if (empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'id_skpd tidak boleh kosong!';
					die(json_encode($ret));
				}
				$tahun = $_POST['tahun_anggaran'];
				$id_skpd =  $_POST['id_skpd'];
				$data_variabel = $wpdb->get_results(
					$wpdb->prepare("
						SELECT 
							k.*,
							k.id as id_variable,
							d.*,
							p.*,
							d.id AS id_kuesioner_mendagri_detail
						FROM esakip_kuesioner_mendagri k
						LEFT JOIN esakip_pengisian_kuesioner_mendagri p ON k.id=p.id_kuesioner_mendagri_detail
							AND k.tahun_anggaran=p.tahun_anggaran
							AND k.active=p.active
							AND p.id_skpd=%d
						LEFT JOIN esakip_kuesioner_mendagri_detail d ON d.level=p.id_level
							AND d.id_kuesioner=k.id
							AND d.tahun_anggaran=p.tahun_anggaran
							AND d.active=p.active
						WHERE k.tahun_anggaran = %d
							AND k.active = 1
						ORDER BY k.nomor_urut ASC
					", $id_skpd, $tahun),
					ARRAY_A
				);
				$ret['sql'] = $wpdb->last_query;

				if (!empty($data_variabel)) {
					$counter = 1;
					$tbody = '';
					$sebelumnya = '';
					$romawi = array(
						'1' => 'I', 
						'2' => 'II', 
						'3' => 'III', 
						'4' => 'IV', 
						'5' => 'V'
					);

					foreach ($data_variabel as $variabel) {
						$btn = "<button class='btn btn-warning' onclick='tambahKuesionerDetail(\"" . $variabel['id_variable'] . "\", \"" . $variabel['id_level'] . "\");' title='Pilih tingkat kematangan'><span class='dashicons dashicons-edit'></span></button>";

						// variabel
						$tbody .= "<tr>";
							$tbody .= "<td class='text-center'><b>" . $counter++ . "</b></td>";       // nomor //
							$tbody .= "
								<td class='text-left'  colspan='2' style='vertical-align: middle;'>
									<b>" . $variabel['nama_kuesioner'] . "</b>
									<br>
									<span>" . $variabel['deskripsi'] . "</span>
								</td>";           // nama Variabel //						
							$tbody .= "<td class='text-center'>".$variabel['ket_opd']."</td>";      // ket opd // 
							$tbody .= "<td class='text-center'>".$variabel['ket_verifikator']."</td>";      // ket verif // 
							$tbody .= "<td class='text-center'>" . $btn ."</td>";      // aksi // 
	                    $tbody .= "</tr>";

						if(!empty($variabel['indikator'])){
							$data_dukung = $wpdb->get_results(
								$wpdb->prepare("
									SELECT 
										*
									FROM esakip_data_dukung_kuesioner_mendagri
									WHERE tahun_anggaran = %d
										AND active = 1
										AND id_kuesioner_mendagri_detail=%d
									ORDER BY jenis_bukti_dukung ASC
								", $tahun, $variabel['id_kuesioner_mendagri_detail']),
								ARRAY_A
							);

							$all_data_dukung = array();
							$all_data_dukung_html = '
							<table class="table table-bordered">
								<tbody>
									<tr>
										<td>Jenis Bukti Dukung</td>
										<td>Nama Dokumen</td>
										<td>Aksi</td>
									</tr>
							';
							if(!empty($data_dukung)){
								foreach($data_dukung as $val){
									$all_data_dukung[] = $val['jenis_bukti_dukung'];
									$btn = "<button class='btn btn-warning' onclick='tambahDokumenBuktiDukung(" 
 										. $val['id'] . ", " 
 										. $id_skpd . ", " 
 										. json_encode($val['dokumen_upload']) . ");' title='Edit Dokumen Bukti'><span class='dashicons dashicons-edit'></span></button>";
									$nama_dokumen = '-';
									$id_kuesioner = $variabel['id_kuesioner_mendagri_detail']; 

									$data_pengisian = $wpdb->get_var($wpdb->prepare("
										SELECT nama_dokumen
										FROM esakip_pengisian_kuesioner_mendagri_detail
										WHERE id_kuesioner = %d
										  AND id_jenis_bukti_dukung = %d
										  AND tahun_anggaran = %d
									", $id_kuesioner, $val['id'], $tahun));

									if (!empty($data_pengisian)) {
										$decoded_dokumen = json_decode($data_pengisian, true);
										if (is_array($decoded_dokumen)) {
											$nama_dokumen = '';
											foreach ($decoded_dokumen as $i => $dokumen) {
												$url = plugins_url('public/media/dokumen/' . $dokumen, __FILE__);
												$nama_dokumen .= '<a href="' . $url . '" target="_blank">' . $dokumen . '</a>';
												if ($i < count($decoded_dokumen) - 1) {
													$nama_dokumen .= ', ';
												}
											}
										}
									}
									$all_data_dukung_html .='
										<tr>
											<td style="text-align: left;">'.$val['jenis_bukti_dukung'].'</td>
											<td>'.$nama_dokumen.'</td> 
											<td>'.$btn.'</td>
										</tr>
									';
								}
							}
							$all_data_dukung_html .= '
								</tbody>
							</table>';
							
							// indikator
							$tbody .= "<tr>
								<td class='text-center' colspan='6'>
									<table class='table table-bordered'>
										<tbody>
											<tr>
												<td>Tingkat Kematangan</td>
												<td>Indikator</td>
												<td>Penjelasan</td>
												<td>Jenis Dokumen</td>
											</tr>
											<tr>
												<td>Tingkat ".$romawi[$variabel['id_level']]."</td>
												<td style='text-align: left;'>".$variabel['indikator']."</td>
												<td style='text-align: left;'>".$variabel['penjelasan']."</td>
												<td style='text-align: left;'>".implode('<br>', $all_data_dukung)."</td>
											</tr>
										</tbody>
									</table>
									".$all_data_dukung_html."
								</td>
							</tr>";
						}
					}

					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='8' class='text-center'>Tidak ada data tersedia.</td></tr>";
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API key tidak sesuai!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format tidak sesuai!';
		}
		die(json_encode($ret));
	}
	public function get_indikator_bukti_by_level() 
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id_kuesioner'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Detail kosong!';
					die(json_encode($ret));
				}

				if (empty($_POST['level'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Level kosong!';
					die(json_encode($ret));
				}

				$id_kuesioner = intval($_POST['id_kuesioner']);
				$level = intval($_POST['level']);

				$id_kuesioner = $wpdb->get_var(
					$wpdb->prepare("
					SELECT id_kuesioner
					FROM esakip_kuesioner_mendagri_detail
					WHERE id = %d
					AND active = 1
					", $id_kuesioner)
				);

				// ambil indikator //
				$data_detail= $wpdb->get_row(
					$wpdb->prepare("
					SELECT 
						*
					FROM esakip_kuesioner_mendagri_detail
					WHERE id_kuesioner = (
						SELECT id_kuesioner
						FROM esakip_kuesioner_mendagri_detail
						WHERE id= %d
						AND active = 1
					)
					AND level = %s
					AND active = 1
					LIMIT 1
					", $id_kuesioner, $level),
					ARRAY_A
				);

				if (!$data_detail) {
					$ret['status'] = 'error';
					$ret['message'] = 'Indikator tidak ditemukan!';
					die(json_encode($ret));
				}

				$id_detail_level = $data_detail['id'];

				// Ambil jenis data dukung //
				$data_bukti= $wpdb->get_results(
					$wpdb->prepare("
					SELECT jenis_bukti_dukung	
					FROM esakip_data_dukung_kuesioner_mendagri
					WHERE id_kuesioner_mendagri_detail = %d
						AND tahun_anggaran =  %d
						AND active = 1
					", $id_detail_level, get_option(ESAKIP_TAHUN_ANGGARAN)),
					ARRAY_A
				);
				
				// data awal //
				$ret['data']['indikator'] = '';
				$ret['data']['jenis_bukti_dukung'] = '';
				$ret['data']['id_detail_level'] = $data_detail['id'];
				$ret['data']['penjelasan'] = $data_detail['penjelasan'] ?? '';

				if (!empty($data_detail['indikator'])) {
					$ret['data']['indikator'] = $data_detail['indikator'];
				}

				if (!empty($data_bukti)) {
					$jenis_bukti_list = array_column($data_bukti, 'jenis_bukti_dukung');
					$ret['data']['jenis_bukti_dukung'] = implode("\n", $jenis_bukti_list);	
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API key tidak sesuai!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format tidak sesuai!';
		}

		die(json_encode($ret));
	}
	public function submit_detail_kuesioner()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil menyimpan data'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] === get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id_kuesioner'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID detail kuesioner kosong!';
					die(json_encode($ret));
				}

				if (empty($_POST['level'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tingkat Kematangan tidak boleh kosong!';
					die(json_encode($ret));
				}

				if (empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID SKPD kosong!';
					die(json_encode($ret));
				}

				if (empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
					die(json_encode($ret));
				}

				$id_kuesioner_mendagri_detail = intval($_POST['id_kuesioner']);
            	$level = $_POST['level'];
            	$id_skpd = intval($_POST['id_skpd']);
            	$tahun_anggaran = sanitize_text_field($_POST['tahun_anggaran']);
            	$ket_opd = '';
				if(!empty($_POST['ket_opd'])){
					$ket_opd = $_POST['ket_opd'];
				}
            	$ket_verifikator = '';
				$data = array(
					'id_kuesioner_mendagri_detail' => $id_kuesioner_mendagri_detail,
					'id_level' => $level,
					'id_skpd' => $id_skpd,
					'tahun_anggaran' => $tahun_anggaran,
					'ket_opd' => $ket_opd,
					'ket_verifikator' => $ket_verifikator,
					'active' => 1
				);

				$cek_id = $wpdb->get_var($wpdb->prepare("
					SELECT
						id
					FROM esakip_pengisian_kuesioner_mendagri
					WHERE id_kuesioner_mendagri_detail = %d
						AND id_skpd=%d
						AND tahun_anggaran=%d
				", $id_kuesioner_mendagri_detail, $id_skpd, $tahun_anggaran));

				if(!empty($cek_id)){
					$wpdb->update('esakip_pengisian_kuesioner_mendagri', $data, array(
						'id' => $cek_id
					));
				}else{
					$wpdb->insert('esakip_pengisian_kuesioner_mendagri', $data);
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'API Key tidak sesuai!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format tidak sesuai!';
		}

		die(json_encode($ret));
	}
	function get_dokumen_bukti_dukung_kuesioner()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil ambil data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'id tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$data_existing = $wpdb->get_var($wpdb->prepare("
						SELECT nama_dokumen
						FROM esakip_pengisian_kuesioner_mendagri_detail
						WHERE id_kuesioner = %d
							AND id_jenis_bukti_dukung = %d
							AND tahun_anggaran = %d
							AND active = 1
					", $_POST['id_kuesioner'], $_POST['id'], $_POST['tahun_anggaran']));

						$bukti_dukung_existing = array();
						if (!empty($data_existing)) {
							$bukti_dukung_existing = json_decode(stripslashes($data_existing), true);
							if (!is_array($bukti_dukung_existing)) {
								$bukti_dukung_existing = array();
							}
						}

					$jenis_bukti_dukung = $_POST['jenis_bukti_dukung'] ?? array();
					if (!is_array($jenis_bukti_dukung)) {
						$jenis_bukti_dukung = array();
					}

					$jenis_bukti_dukung_db = $wpdb->get_var($wpdb->prepare("
						SELECT
							dokumen_upload
						FROM esakip_data_dukung_kuesioner_mendagri
						WHERE id= %d
					", $_POST['id']));
					$all_dokumen = array();
					$jenis_bukti_dukung = json_decode(stripslashes($jenis_bukti_dukung_db), true);
					if (json_last_error() !== JSON_ERROR_NONE) {
						$jenis_bukti_dukung = array();
					}
					
					$all_dokumen = array();
					$ret['upload_bukti_dukung'] = '';
					foreach ($jenis_bukti_dukung as $v) {
						$dokumen = null;
						$sql = $wpdb->prepare("
							SELECT
								*
							FROM {$v}
							WHERE id_skpd=%d
								AND active=1
						", $_POST['id_skpd']);

						// dikecualikan karena dokumen ini tidak berdasarkan tahun anggaran, tapi per periode
						if (
							$v != 'esakip_renstra'
							&& $v != 'esakip_rpjmd'
							&& $v != 'esakip_rpjpd'
							&& $v != 'esakip_pohon_kinerja_dan_cascading'
						) {
							$sql .= $wpdb->prepare(' AND tahun_anggaran=%d', $_POST['tahun_anggaran']);
						}
						$all_dokumen[$v] = $wpdb->get_results($sql, ARRAY_A);
						if (
							$v == 'esakip_rpjmd'
							|| $v == 'esakip_rpjpd'
							|| $v == 'esakip_rkpd'
							|| $v == 'esakip_lkjip_lppd'
							|| $v == 'esakip_other_file'
						) {
							foreach ($all_dokumen[$v] as $key => $dok) {
								$all_dokumen[$v][$key]['dokumen'] = 'dokumen_pemda/' . $dok['dokumen'];
							}
						}

						if ($v == 'esakip_renstra') {
							$jadwal_periode = $wpdb->get_results(
								$wpdb->prepare("
									SELECT 
										id,
										nama_jadwal,
										tahun_anggaran,
										lama_pelaksanaan
									FROM esakip_data_jadwal
									WHERE tipe = %s
									  	AND status = 1
									ORDER BY id ASC
								", 'RPJMD'),
								ARRAY_A
							);
							foreach ($jadwal_periode as $jadwal_periode_item) {
								$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
								if ($v == 'esakip_renstra') {
									$dokumen = $this->functions->generatePage(array(
										'nama_page' => 'RENSTRA | ' . $jadwal_periode_item['id'],
										'content' => '[upload_dokumen_renstra periode=' . $jadwal_periode_item['id'] . ']',
										'show_header' => 1,
										'post_status' => 'private'
									));
									$dokumen['url'] .= '&id_skpd=' . $_POST['id_skpd'];
									$title_renstra = 'Dokumen RENSTRA | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
									$ret['upload_bukti_dukung'] .= '<a style="margin-left: 5px;" class="btn btn-warning" target="_blank" href="' . $dokumen['url'] . '">' . $title_renstra . '</a>';
								}
							}
							continue;
						}

						if ($v == 'esakip_pohon_kinerja_dan_cascading') {
							$jadwal_periode = $wpdb->get_results(
								$wpdb->prepare("
									SELECT 
										id,
										nama_jadwal,
										tahun_anggaran,
										lama_pelaksanaan
									FROM esakip_data_jadwal
									WHERE tipe = %s
									  	AND status = 1
									ORDER BY id ASC
								", 'RPJMD'),
								ARRAY_A
							);
							foreach ($jadwal_periode as $jadwal_periode_item) {
								$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
								if ($v == 'esakip_pohon_kinerja_dan_cascading') {
									$dokumen = $this->functions->generatePage(array(
										'nama_page' => 'Pohon Kinerja dan Cascading | ' . $jadwal_periode_item['id'],
										'content' => '[dokumen_detail_pohon_kinerja_dan_cascading periode=' . $jadwal_periode_item['id'] . ']',
										'show_header' => 1,
										'post_status' => 'private'
									));

									$dokumen['url'] .= '&id_skpd=' . $_POST['id_skpd'];
									$title_pokin = 'Dokumen Pohon Kinerja dan Cascading | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
									$ret['upload_bukti_dukung'] .= '<a style="margin-left: 5px;" class="btn btn-warning" target="_blank" href="' . $dokumen['url'] . '">' . $title_pokin . '</a>';
								}
							}
							continue;
						}

						if ($v == 'esakip_renja_rkt') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'RENJA / RKT-' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_renja_rkt tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_skp') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'SKP ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_skp tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_rencana_aksi') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Rencana Aksi ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_rencana_aksi tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_iku') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'IKU ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_iku tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_pengukuran_kinerja') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Pengukuran Kinerja ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_pengukuran_kinerja tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_laporan_kinerja') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Laporan Kinerja ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_laporan_kinerja tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_dpa') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'DPA ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_dpa tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_evaluasi_internal') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Evaluasi Internal ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_evaluasi_internal tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_dokumen_lainnya') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Lainnya ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_dokumen_lainnya tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_perjanjian_kinerja') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Perjanjian Kinerja ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_perjanjian_kinerja tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_lhe_akip_internal') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'LHE AKIP Internal' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_lhe_akip_internal tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_tl_lhe_akip_internal') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'TL LHE AKIP Internal' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_tl_lhe_akip_internal tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_laporan_monev_renaksi') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Laporan Monev Renaksi' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_detail_laporan_monev_renaksi tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
						}
						if (!empty($dokumen)) {
							$dokumen['url'] .= '&id_skpd=' . $_POST['id_skpd'];
							$ret['upload_bukti_dukung'] .= '<a style="margin-left: 5px;" class="btn btn-warning" target="_blank" href="' . $dokumen['url'] . '">' . $dokumen['title'] . '</a>';
						}
					}

					$ret['data'] = $all_dokumen;					
					$ret['data_existing'] = $bukti_dukung_existing;
					$ret['sql'] = $wpdb->last_query;
					$ret['raw_data_existing'] = $data_existing;
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
	function submit_bukti_dukung_kuesioner() 
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil simpan data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if ($ret['status'] != 'error' && empty($_POST['id_skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID OPD tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['id_jenis_bukti_dukung'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'ID jenis bukti dukung tidak boleh kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun anggaran tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
					$id_skpd                 = $_POST['id_skpd'];
					$id_jenis_bukti_dukung   = $_POST['id_jenis_bukti_dukung'];
					$tahun_anggaran          = $_POST['tahun_anggaran'];
					$dokumen_upload          = $_POST['dokumen_upload'] ? $_POST['dokumen_upload'] : array();
					
					if (!is_array($dokumen_upload)) {
						 $dokumen_upload = array();
					}

					$nama_dokumen = json_encode($dokumen_upload);

						$id_kuesioner = $wpdb->get_var($wpdb->prepare("
						SELECT id_kuesioner_mendagri_detail
						FROM esakip_data_dukung_kuesioner_mendagri
						WHERE id = %d
					", $id_jenis_bukti_dukung));

					if (empty($id_kuesioner)) {
						$ret['status'] = 'error';
						$ret['message'] = 'Data kuesioner tidak ditemukan!';
						die(json_encode($ret));
					}

					$existing_data = $wpdb->get_row($wpdb->prepare("
						SELECT 
							*
						FROM esakip_pengisian_kuesioner_mendagri_detail
						WHERE id_kuesioner = %d
						  AND id_jenis_bukti_dukung = %d
						  AND tahun_anggaran = %d
					", $id_kuesioner, $id_jenis_bukti_dukung, $tahun_anggaran), ARRAY_A);

					if ($existing_data) {
						$updated = $wpdb->update(
							'esakip_pengisian_kuesioner_mendagri_detail',
							array(
								'nama_dokumen' => $nama_dokumen,
								'updated_at' => current_time('mysql')
							),
							array('id' => $existing_data['id'])
						);

						if ($updated !== false) {
							$ret['message'] = "Berhasil update bukti dukung usulan!";
						} else {
							$ret['status'] = 'error';
							$ret['message'] = "Gagal melakukan update nilai usulan: " . $wpdb->last_error;
						}
					} else {
						$inserted = $wpdb->insert(
							'esakip_pengisian_kuesioner_mendagri_detail',
							array(
							   'id_kuesioner' => $id_kuesioner,
							   'id_jenis_bukti_dukung' => $id_jenis_bukti_dukung,
							   'nama_dokumen' => $nama_dokumen,
							   'tahun_anggaran' => $tahun_anggaran,
							   'created_at' => current_time('mysql'),
							   'updated_at' => current_time('mysql'),
							   'active' => 1
							)
						);								
						if ($inserted === false) {
							$ret['status'] = 'error';
							$ret['message'] = 'Gagal insert data baru: ' . $wpdb->last_error;
						}
					}
					$ret['data'] = json_decode($nama_dokumen, true);	
				} 
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'Api Key tidak sesuai!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Api Key tidak sesuai!';
		}
		die(json_encode($ret));
	}
}
