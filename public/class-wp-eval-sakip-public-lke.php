<?php

require_once ESAKIP_PLUGIN_PATH . "/public/class-wp-eval-sakip-public-pohon-kinerja.php";
class Wp_Eval_Sakip_LKE extends Wp_Eval_Sakip_Pohon_Kinerja
{

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

				$unit = $wpdb->get_results(
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

				$jadwal = $wpdb->get_row(
					$wpdb->prepare("
                    SELECT 
                        *
                    FROM esakip_data_jadwal
                    WHERE id=%d
                        AND status != 0
                ", $id_jadwal),
					ARRAY_A
				);
				if (!empty($unit) && !empty($jadwal)) {
					$tbody = '';
					$counter = 1;
					$total_nilai_usulan = 0;
					$total_nilai_penetapan = 0;

					// Initialize total values for all components dynamically
					$total_nilai_komponen = array();

					foreach ($unit as $kk => $vv) {
						$nilai_usulan = 0;
						$nilai_penetapan = 0;

						// Initialize individual component values for current unit
						$nilai_komponen = array();

						$get_komponen = $wpdb->get_results(
							$wpdb->prepare("
	                            SELECT * 
	                            FROM esakip_komponen
	                            WHERE id_jadwal = %d
	                              AND active = 1
	                            ORDER BY nomor_urut ASC
	                        ", $id_jadwal),
							ARRAY_A
						);

						foreach ($get_komponen as $komponen) {
							$komponen_id = $komponen['id'];
							$nilai_komponen[$komponen_id] = 0;

							$get_subkomponen = $wpdb->get_results(
								$wpdb->prepare("
	                                SELECT * 
	                                FROM esakip_subkomponen
	                                WHERE id_komponen = %d
	                                  AND active = 1
	                                ORDER BY nomor_urut ASC
	                            ", $komponen_id),
								ARRAY_A
							);

							foreach ($get_subkomponen as $subkomponen) {
								$subkomponen_id = $subkomponen['id'];

								$sum_nilai_usulan = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT SUM(nilai_usulan)
	                                    FROM esakip_pengisian_lke
	                                    WHERE id_subkomponen = %d
	                                      AND id_skpd = %d
	                                      AND tahun_anggaran = %d
	                                ", $subkomponen['id'], $vv['id_skpd'], $tahun_anggaran)
								);

								$count_nilai_usulan = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT COUNT(id)
	                                    FROM esakip_komponen_penilaian
	                                    WHERE id_subkomponen = %d
	                                ", $subkomponen_id)
								);

								$sum_nilai_penetapan = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT SUM(nilai_penetapan)
	                                    FROM esakip_pengisian_lke
	                                    WHERE id_subkomponen = %d
	                                      AND id_skpd = %d
	                                      AND tahun_anggaran = %d
	                                ", $subkomponen['id'], $vv['id_skpd'], $tahun_anggaran)
								);

								$count_nilai_penetapan = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT COUNT(id)
	                                    FROM esakip_komponen_penilaian
	                                    WHERE id_subkomponen = %d
	                                ", $subkomponen['id'])
								);

								$sum_nilai_komponen_a = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT SUM(nilai_penetapan)
	                                    FROM esakip_pengisian_lke
	                                    WHERE id_subkomponen = %d
	                                      AND id_komponen = 1
	                                      AND id_skpd = %d
	                                      AND tahun_anggaran = %d
	                                ", $subkomponen['id'], $vv['id_skpd'], $tahun_anggaran)
								);

								$count_nilai_komponen_a = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT COUNT(id)
	                                    FROM esakip_komponen_penilaian
	                                    WHERE id_subkomponen = %d
	                                ", $subkomponen['id'])
								);

								$sum_nilai_komponen_b = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT SUM(nilai_penetapan)
	                                    FROM esakip_pengisian_lke
	                                    WHERE id_subkomponen = %d
	                                      AND id_komponen = 2
	                                      AND id_skpd = %d
	                                      AND tahun_anggaran = %d
	                                ", $subkomponen['id'], $vv['id_skpd'], $tahun_anggaran)
								);

								$count_nilai_komponen_b = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT COUNT(id)
	                                    FROM esakip_komponen_penilaian
	                                    WHERE id_subkomponen = %d
	                                ", $subkomponen['id'])
								);

								$sum_nilai_komponen_c = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT SUM(nilai_penetapan)
	                                    FROM esakip_pengisian_lke
	                                    WHERE id_subkomponen = %d
	                                      AND id_komponen = 3
	                                      AND id_skpd = %d
	                                      AND tahun_anggaran = %d
	                                ", $subkomponen['id'], $vv['id_skpd'], $tahun_anggaran)
								);

								$count_nilai_komponen_c = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT COUNT(id)
	                                    FROM esakip_komponen_penilaian
	                                    WHERE id_subkomponen = %d
	                                ", $subkomponen['id'])
								);

								$sum_nilai_komponen_d = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT SUM(nilai_penetapan)
	                                    FROM esakip_pengisian_lke
	                                    WHERE id_subkomponen = %d
	                                      AND id_komponen = 4
	                                      AND id_skpd = %d
	                                      AND tahun_anggaran = %d
	                                ", $subkomponen['id'], $vv['id_skpd'], $tahun_anggaran)
								);

								$count_nilai_komponen_d = $wpdb->get_var(
									$wpdb->prepare("
	                                    SELECT COUNT(id)
	                                    FROM esakip_komponen_penilaian
	                                    WHERE id_subkomponen = %d
	                                ", $subkomponen['id'])
								);

								$total_nilai_sub = 0;
								$total_nilai_sub_penetapan = 0;

								if ($count_nilai_usulan > 0) {
									$persentase_sub = $sum_nilai_usulan / $count_nilai_usulan;
									$total_nilai_sub = $persentase_sub * $subkomponen['bobot'];
									$nilai_usulan += $total_nilai_sub;
								}
								if ($count_nilai_penetapan > 0) {
									$persentase_sub_penetapan = $sum_nilai_penetapan / $count_nilai_penetapan;
									$total_nilai_sub_penetapan = $persentase_sub_penetapan * $subkomponen['bobot'];
									$nilai_penetapan += $total_nilai_sub_penetapan;
									$persentase_sub_komponen = $sum_nilai_penetapan / $count_nilai_penetapan;
									$total_nilai_sub_komponen = $persentase_sub_komponen * $subkomponen['bobot'];
									$nilai_komponen[$komponen_id] += $total_nilai_sub_komponen;
								}
							}
						}

						$total_nilai_usulan += $nilai_usulan;
						$total_nilai_penetapan += $nilai_penetapan;

						foreach ($nilai_komponen as $komponen_id => $nilai) {
							if (!isset($total_nilai_komponen[$komponen_id])) {
								$total_nilai_komponen[$komponen_id] = 0;
							}
							$total_nilai_komponen[$komponen_id] += $nilai;
						}

						$detail_pengisian_lke = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Pengisian LKE ' . $vv['nama_skpd'] . ' ' . $jadwal['nama_jadwal'],
							'content' => '[pengisian_lke_sakip_per_skpd id_jadwal=' . $id_jadwal . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_pengisian_lke['url'] . '&id_skpd=' . $vv['id_skpd'] . '&id_jadwal=' . $id_jadwal . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</td>";
						$tbody .= "<td class='text-center'>" . number_format($nilai_usulan, 2) . "</td>";

						foreach ($nilai_komponen as $komponen_id => $nilai) {
							$tbody .= "<td class='text-center'>" . number_format($nilai, 2) . "</td>";
						}

						$tbody .= "<td class='text-center'>" . number_format($nilai_penetapan, 2) . "</td>";
						$tbody .= "<td>" . $btn . "</td>";
						$tbody .= "</tr>";
					}

					$ret['data'] = $tbody;
					$ret['nilai_usulan'] = $total_nilai_usulan;
					$ret['nilai_penetapan'] = $total_nilai_penetapan;
					$ret['nilai_komponen'] = $total_nilai_komponen;
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
					$ret['message'] = 'Id SKPD kosong!';
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

						$sum_nilai_sub = 0;
						$sum_nilai_sub_penetapan = 0;
						$persentase_kom = 0;
						$persentase_kom_penetapan = 0;

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
						if (!empty($data_subkomponen)) {
							foreach ($data_subkomponen as $subkomponen) {
								$disabled = 'disabled';
								// Jika jadwal masih buka
								if ($dateTime > $started_at_dt && $dateTime < $end_at_dt) {
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

								$sum_nilai_usulan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT SUM(nilai_usulan)
										FROM esakip_pengisian_lke
										WHERE id_subkomponen = %d
										  	AND id_skpd = %d
                                      		AND tahun_anggaran = %d
									", $subkomponen['id'], $id_skpd, $tahun_anggaran)
								);
								$count_nilai_usulan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT COUNT(id)
										FROM esakip_komponen_penilaian
										WHERE id_subkomponen = %d
									", $subkomponen['id'])
								);
								$sum_nilai_penetapan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT SUM(nilai_penetapan)
										FROM esakip_pengisian_lke
										WHERE id_subkomponen = %d
										  	AND id_skpd = %d
                                      		AND tahun_anggaran = %d
									", $subkomponen['id'], $id_skpd, $tahun_anggaran)
								);
								$count_nilai_penetapan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT COUNT(id)
										FROM esakip_komponen_penilaian
										WHERE id_subkomponen = %d
									", $subkomponen['id'])
								);

								//jumlah nilai sub
								$total_nilai_sub = 0;
								$total_nilai_sub_penetapan = 0;
								$persentase_sub = 0;
								$persentase_sub_penetapan = 0;
								if ($count_nilai_usulan > 0) {
									$persentase_sub = $sum_nilai_usulan / $count_nilai_usulan;
									$total_nilai_sub = $persentase_sub * $subkomponen['bobot'];
									$sum_nilai_sub += $total_nilai_sub;;
								}

								if ($count_nilai_penetapan > 0) {
									$persentase_sub_penetapan = $sum_nilai_penetapan / $count_nilai_penetapan;
									$total_nilai_sub_penetapan = $persentase_sub_penetapan * $subkomponen['bobot'];
									$sum_nilai_sub_penetapan += $total_nilai_sub_penetapan;
								}

								//tbody subkomponen
								$tbody2 .= "<tr class='table-active'>";
								$tbody2 .= "<td class='text-left'></td>";
								$tbody2 .= "<td class='text-left'>" . $counter_sub++ . "</td>";
								$tbody2 .= "<td class='text-left' colspan='2'><b>" . $subkomponen['nama'] . "</b></td>";
								$tbody2 .= "<td class='text-center'>" . $subkomponen['bobot'] . "</td>";
								$tbody2 .= "<td class='text-left'></td>";
								$tbody2 .= "<td class='text-center'>" . number_format($total_nilai_sub, 2) . "</td>";
								$tbody2 .= "<td class='text-center'>" . number_format($persentase_sub * 100, 2) . "%" . "</td>";
								$tbody2 .= "<td class='text-center' colspan='3'></td>";
								$tbody2 .= "<td class='text-center'></td>";
								$tbody2 .= "<td class='text-center'>" . number_format($total_nilai_sub_penetapan, 2) . "</td>";
								$tbody2 .= "<td class='text-center'>" . number_format($persentase_sub_penetapan * 100, 2) . "%" . "</td>";
								$tbody2 .= "<td class='text-left' colspan='3'>User Penilai: <b>" . $user_penilai[$subkomponen['id_user_penilai']] . "</b></td>";
								$tbody2 .= "</tr>";


								$data_komponen_penilaian = $wpdb->get_results(
									$wpdb->prepare("
										SELECT 
											kp.id AS kp_id,
											kp.id_subkomponen AS kp_id_subkomponen,
											kp.nomor_urut AS kp_nomor_urut,
											kp.nama AS kp_nama,
											kp.tipe AS kp_tipe,
											kp.keterangan AS kp_keterangan,
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
											ON kp.id = pl.id_komponen_penilaian AND pl.id_skpd = %d
											AND pl.tahun_anggaran=%d
										WHERE kp.id_subkomponen = %d
										  AND kp.active = 1
										ORDER BY kp.nomor_urut ASC
									",  $id_skpd, $tahun_anggaran, $subkomponen['id']),
									ARRAY_A
								);

								if (!empty($data_komponen_penilaian)) {
									foreach ($data_komponen_penilaian as $penilaian) {
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
										$data_kerangka_logis = $wpdb->get_results(
											$wpdb->prepare("
												SELECT *
												FROM esakip_kontrol_kerangka_logis
												WHERE id_komponen_penilaian = %d
												  AND active = 1
											", $penilaian['pl_id_komponen_penilaian']),
											ARRAY_A
										);

										// Default pesan kerangka logis
										$kerangka_logis = "<td class='text-center table-warning'>Belum Diisi</td>";
										$kerangka_logis_penetapan = "<td class='text-center table-warning'>Belum Diisi</td>";

										//kerangka logis nilai usulan
										if (isset($penilaian['pl_nilai_usulan'])) {
											$pesan_kesalahan = [];

											foreach ($data_kerangka_logis as $kl) {
												if ($kl['jenis_kerangka_logis'] == 1) {
													// Rata-rata
													$avg_nilai_sub = $wpdb->get_var(
														$wpdb->prepare("
															SELECT AVG(nilai_usulan)
															FROM esakip_pengisian_lke
															WHERE id_subkomponen = %d
																AND id_skpd = %d
																AND tahun_anggaran = %d
														", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran)
													);

													if ($avg_nilai_sub < $penilaian['pl_nilai_usulan']) {
														$pesan_kesalahan[] = $kl['pesan_kesalahan'];
													}
												} else if ($kl['jenis_kerangka_logis'] == 2) {
													// Nilai
													$nilai_komponen_penilaian = $wpdb->get_var(
														$wpdb->prepare("
															SELECT nilai_usulan
															FROM esakip_pengisian_lke
															WHERE id_komponen_penilaian = %d
																AND id_skpd = %d
																AND tahun_anggaran = %d
														", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran)
													);

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
										if (isset($penilaian['pl_nilai_penetapan'])) {
											$pesan_kesalahan_penetapan = [];

											foreach ($data_kerangka_logis as $kl) {
												if ($kl['jenis_kerangka_logis'] == 1) {
													// Rata-rata
													$avg_nilai_sub_penetapan = $wpdb->get_var(
														$wpdb->prepare("
															SELECT AVG(nilai_penetapan)
															FROM esakip_pengisian_lke
															WHERE id_subkomponen = %d
																AND id_skpd = %d
																AND tahun_anggaran=%d
														", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran)
													);

													if ($avg_nilai_sub_penetapan < $penilaian['pl_nilai_penetapan']) {
														$pesan_kesalahan_penetapan[] = $kl['pesan_kesalahan'];
													}
												} else if ($kl['jenis_kerangka_logis'] == 2) {
													// Nilai
													$nilai_komponen_penilaian_penetapan = $wpdb->get_var(
														$wpdb->prepare("
															SELECT nilai_penetapan
															FROM esakip_pengisian_lke
															WHERE id_komponen_penilaian = %d
																AND id_skpd = %d
																AND tahun_anggaran=%d
														", $kl['id_komponen_pembanding'], $id_skpd, $tahun_anggaran)
													);
													// die(print_r($wpdb->last_query));

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


										$bukti_dukung = json_decode(stripslashes($penilaian['pl_bukti_dukung']), true);
										if (!empty($bukti_dukung)) {
											foreach ($bukti_dukung as $k => $bukti) {
												$bukti_dukung[$k] = '<a href="' . ESAKIP_PLUGIN_URL . 'public/media/dokumen/' . $bukti . '" target="_blank">' . $bukti . '</a>';
											}
											$bukti_dukung = implode(', ', $bukti_dukung);
										}
										$tombol_bukti = "";
										if ($disabled == '') {
											$tombol_bukti = "<button type='button' class='btn btn-primary btn-sm' title='Tambah bukti dukung' onclick='tambahBuktiDukung(" . $id_skpd . "," . $penilaian['kp_id'] . ")' id='buktiDukung" . $penilaian['kp_id'] . "'><i class='dashicons dashicons-plus'></i></button>";
										}
										//tbody isi
										$tbody2 .= "<tr kp-id='" . $penilaian['kp_id'] . "'>";
										$tbody2 .= "<td class='text-left'></td>";
										$tbody2 .= "<td class='text-left'></td>";
										$tbody2 .= "<td class='text-left'>" . $counter_isi++ . "</td>";
										$tbody2 .= "<td class='text-left'>" . $penilaian['kp_nama'] . "<br><small class='text-muted'>" . $penilaian['kp_keterangan'] . "</small></td>";
										$tbody2 .= "<td class='text-center'>-</td>";
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
												$tbody2 .= "<td class='text-center'>" . $nilai_usulan . "</td>";
												$tbody2 .= "<td class='text-center'></td>";
												if (!$this->is_admin_panrb()) {
													$tbody2 .= "<td class='text-center'><div class='bukti-dukung-view' kp-id='" . $penilaian['kp_id'] . "'>" . $bukti_dukung . "</div>" . $tombol_bukti . "</td>";
													$tbody2 .= "<td class='text-center'><textarea id='keteranganUsulan" . $penilaian['kp_id'] . "' $disabled>" . $penilaian['pl_keterangan'] . "</textarea></td>";
												} else {
													$tbody2 .= "<td class='text-center'><div class='bukti-dukung-view' kp-id='" . $penilaian['kp_id'] . "'>" . $bukti_dukung . "</div></td>";
													$tbody2 .= "<td class='text-center'><textarea id='keteranganUsulan" . $penilaian['kp_id'] . "' disabled>" . $penilaian['pl_keterangan'] . "</textarea></td>";
												}
												$tbody2 .= $kerangka_logis;
												$tbody2 .= "<td class='text-center'><select id='opsiPenetapan" . $penilaian['kp_id'] . "' disabled>" . $opsi_penetapan . "</select></td>";
												$tbody2 .= "<td class='text-center'>" . $nilai_penetapan . "</td>";
												$tbody2 .= "<td class='text-center'></td>";
												$tbody2 .= "<td class='text-center'><textarea id='keteranganPenetapan" . $penilaian['kp_id'] . "' disabled>" . $penilaian['pl_keterangan_penilai'] . "</textarea></td>";
												$tbody2 .= $kerangka_logis;
												if ($disabled == '') {
													if (!$this->is_admin_panrb()) {
														$tbody2 .= "<td class='text-center'>" . $btn_save . "</td>";
													} else {
														$tbody2 .= "<td class='text-center'></td>";
													}
												} else {
													$tbody2 .= "<td class='text-center'></td>";
												}
												break;
											case true:
												if (!$this->is_admin_panrb()) {
													$btn_save_penetapan = "<button class='btn btn-info' onclick='simpanPerubahanPenetapan(" . $penilaian['kp_id'] . ")' title='Simpan Perubahan Penetapan'><span class='dashicons dashicons-saved' ></span></button>";
												}

												$tbody2 .= "<td class='text-center'><select id='opsiUsulan" . $penilaian['kp_id'] . "' disabled>" . $opsi . "</select></td>";
												$tbody2 .= "<td class='text-center'>" . $nilai_usulan . "</td>";
												$tbody2 .= "<td class='text-center'></td>";
												$tbody2 .= "<td class='text-center'><div class='bukti-dukung-view' kp-id='" . $penilaian['kp_id'] . "'>" . $bukti_dukung . "</div></td>";
												$tbody2 .= "<td class='text-center'><textarea id='keteranganUsulan" . $penilaian['kp_id'] . "' disabled>" . $penilaian['pl_keterangan'] . "</textarea></td>";
												$tbody2 .= $kerangka_logis;
												$tbody2 .= "<td class='text-center'><select id='opsiPenetapan" . $penilaian['kp_id'] . "' " . $disabled . ">" . $opsi_penetapan . "</select></td>";
												$tbody2 .= "<td class='text-center'>" . $nilai_penetapan . "</td>";
												$tbody2 .= "<td class='text-center'></td>";
												$tbody2 .= "<td class='text-center'><textarea id='keteranganPenetapan" . $penilaian['kp_id'] . "'" . $disabled . ">" . $penilaian['pl_keterangan_penilai'] . "</textarea></td>";
												$tbody2 .= $kerangka_logis_penetapan;
												if ($disabled == '') {
													if (!$this->is_admin_panrb()) {
														$tbody2 .= "<td class='text-center'>" . $btn_save_penetapan . "</td>";
													} else {
														$tbody2 .= "<td class='text-center'></td>";
													}
												} else {
													$tbody2 .= "<td class='text-center'></td>";
												}
												break;
										}
										$tbody2 .= "</tr>";
									}
								}
							}
						}

						//jumlah nilai kom
						if ($sum_nilai_sub > 0) {
							$persentase_kom = $sum_nilai_sub / $komponen['bobot'];
						}
						if ($sum_nilai_sub_penetapan > 0) {
							$persentase_kom_penetapan = $sum_nilai_sub_penetapan / $komponen['bobot'];
						}

						$total_nilai += $sum_nilai_sub;
						$total_nilai_penetapan += $sum_nilai_sub_penetapan;

						//tbody komponen
						$tbody .= "<tr class='table-active'>";
						$tbody .= "<td class='text-left'>" . $counter++ . "</td>";
						$tbody .= "<td class='text-left' colspan='3'><b>" . $komponen['nama'] . "</b></td>";
						$tbody .= "<td class='text-center'>" . $komponen['bobot'] . "</td>";
						$tbody .= "<td class='text-left'></td>";
						$tbody .= "<td class='text-center'>" . number_format($sum_nilai_sub, 2) . "</td>";
						$tbody .= "<td class='text-center'>" . number_format($persentase_kom * 100, 2) . "%" . "</td>";
						$tbody .= "<td class='text-center' colspan='3'></td>";
						$tbody .= "<td class='text-center'></td>";
						$tbody .= "<td class='text-center'>" . number_format($sum_nilai_sub_penetapan, 2) .  "</td>";
						$tbody .= "<td class='text-center'>" . number_format($persentase_kom_penetapan * 100, 2) . "%" . "</td>";
						$tbody .= "<td class='text-left' colspan='3'></td>";
						$tbody .= "</tr>";
						$tbody .= $tbody2;

						$merged_data['tbody'] = $tbody;
						$merged_data['total_nilai'] = number_format($total_nilai, 2);
						$merged_data['total_nilai_penetapan'] = number_format($total_nilai_penetapan, 2);
					}
				} else {
					$tbody = "<tr><td colspan='4' class='text-center'>Tidak ada data tersedia</td></tr>";
				}

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
					$ret['message'] = 'Id SKPD kosong!';
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
				$dateTime = new DateTime();
				$data_jadwal = $wpdb->get_row(
					$wpdb->prepare("
						SELECT *
						FROM esakip_data_jadwal
						WHERE id=%d
					", $id_jadwal),
					ARRAY_A
				);
				if ($data_jadwal) {
					$started_at = trim($data_jadwal['started_at']);
					$end_at = trim($data_jadwal['end_at']);

					$started_at_dt = new DateTime($started_at);
					$end_at_dt = new DateTime($end_at);
				} else {
					error_log('Data jadwal tidak ditemukan.');
				}
				if ($dateTime > $started_at_dt && $dateTime > $end_at_dt) {
					$ret['status'] = 'error';
					$ret['message'] = 'Jadwal Sudah Selesai!';
					die(json_encode($ret));
				}

				//validasi nilai
				$valid_values = [0, 0.25, 0.5, 0.75, 1];
				if (!in_array($nilai_penetapan, $valid_values)) {
					$ret['status'] = 'error';
					$ret['message'] = 'Aksi ditolak - nilai yang dimasukkan tidak valid!';
					die(json_encode($ret));
				}

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
					$ret['message'] = 'Id SKPD kosong!';
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
				$dateTime = new DateTime();
				$data_jadwal = $wpdb->get_row(
					$wpdb->prepare("
						SELECT *
						FROM esakip_data_jadwal
						WHERE id=%d
					", $id_jadwal),
					ARRAY_A
				);
				if ($data_jadwal) {
					$started_at = trim($data_jadwal['started_at']);
					$end_at = trim($data_jadwal['end_at']);

					$started_at_dt = new DateTime($started_at);
					$end_at_dt = new DateTime($end_at);
				} else {
					error_log('Data jadwal tidak ditemukan.');
				}
				if ($dateTime > $started_at_dt && $dateTime > $end_at_dt) {
					$ret['status'] = 'error';
					$ret['message'] = 'Jadwal Sudah Selesai!';
					die(json_encode($ret));
				}

				//validasi nilai
				$valid_values = [0, 0.25, 0.5, 0.75, 1];
				if (!in_array($nilai_usulan, $valid_values)) {
					$ret['status'] = 'error';
					$ret['message'] = 'Aksi ditolak - nilai yang dimasukkan tidak valid!';
					die(json_encode($ret));
				}

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
						if ($v == 'esakip_rpjpd') {
							$jadwal_periode = $wpdb->get_results(
								$wpdb->prepare(
									"
									SELECT 
										id,
										nama_jadwal,
										tahun_anggaran,
										lama_pelaksanaan
									FROM esakip_data_jadwal
									WHERE tipe = %s
									  AND status = 1",
									'RPJPD'
								),
								ARRAY_A
							);
							foreach ($jadwal_periode as $jadwal_periode_item) {
								$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
								$dokumen = $this->functions->generatePage(array(
									'nama_page' => 'Halaman Upload Dokumen RPJPD ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
									'content' => '[upload_dokumen_rpjpd periode=' . $jadwal_periode_item['id'] . ']',
									'show_header' => 1,
									'no_key' => 1,
									'post_status' => 'private'
								));
							}
						} else if (
							$v == 'esakip_rpjmd'
							|| $v == 'esakip_renstra'
						) {
							$jadwal_periode = $wpdb->get_results(
								$wpdb->prepare(
									"
									SELECT 
										id,
										nama_jadwal,
										tahun_anggaran,
										lama_pelaksanaan
									FROM esakip_data_jadwal
									WHERE tipe = %s
									  AND status = 1",
									'RPJMD'
								),
								ARRAY_A
							);
							foreach ($jadwal_periode as $jadwal_periode_item) {
								$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
								if ($v == 'esakip_renstra') {
									$dokumen = $this->functions->generatePage(array(
										'nama_page' => 'Halaman Dokumen RENSTRA ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
										'content' => '[renstra periode=' . $jadwal_periode_item['id'] . ']',
										'show_header' => 1,
										'no_key' => 1,
										'post_status' => 'private'
									));
								} else if ($v == 'esakip_rpjmd') {
									$dokumen = $this->functions->generatePage(array(
										'nama_page' => 'Halaman Upload Dokumen RPJMD ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
										'content' => '[upload_dokumen_rpjmd periode=' . $jadwal_periode_item['id'] . ']',
										'show_header' => 1,
										'no_key' => 1,
										'post_status' => 'private'
									));
								}
							}
						} else if ($v == 'esakip_renja_rkt') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen RENJA/RKT Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[renja_rkt tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_skp') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen SKP Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[skp tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_rencana_aksi') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Rencana Aksi Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[rencana_aksi tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_iku') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen IKU Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[iku tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_pengukuran_kinerja') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pengukuran Kinerja Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[pengukuran_kinerja tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_pengukuran_rencana_aksi') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pengukuran Rencana Aksi Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[pengukuran_rencana_aksi tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_laporan_kinerja') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Laporan Kinerja Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[laporan_kinerja tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_dpa') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen DPA Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[dpa tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_evaluasi_internal') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Evaluasi Internal Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[evaluasi_internal tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_dokumen_lainnya') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Lain Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_lainnya tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_perjanjian_kinerja') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Perjanjian Kinerja Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[perjanjian_kinerja tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_rkpd') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman RKPD Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[rkpd tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_other_file') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Lainnya Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[dokumen_pemda_lainnya tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_lkjip_lppd') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman LKJIP/LPPD  ' . $_POST['tahun_anggaran'],
								'content' => '[lkjip_lppd tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_pohon_kinerja_dan_cascading') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pohon Kinerja dan Cascading Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[pohon_kinerja_dan_cascading tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_lhe_akip_internal') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen LHE AKIP Internal Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[lhe_akip_internal tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_tl_lhe_akip_internal') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen TL LHE AKIP Internal Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[tl_lhe_akip_internal tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_tl_lhe_akip_kemenpan') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen TL LHE AKIP Kemenpan Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[tl_lhe_akip_kemenpan tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_laporan_monev_renaksi') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Monev Renaksi Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[laporan_monev_renaksi tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_pedoman_teknis_perencanaan') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Pedoman Teknis Perencanaan Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[pedoman_teknis_perencanaan tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						} else if ($v == 'esakip_pedoman_teknis_evaluasi_internal') {
							$dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Pedoman Teknis Evaluasi Internal Tahun ' . $_POST['tahun_anggaran'],
								'content' => '[pedoman_teknis_evaluasi_internal tahun=' . $_POST['tahun_anggaran'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
						}
						$ret['upload_bukti_dukung'] .= '<a class="btn btn-warning" target="_blank" href="' . $dokumen['url'] . '">' . $dokumen['title'] . '</a>';
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
				} else if ($ret['status'] != 'error' && empty($_POST['bukti_dukung'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Bukti dukung tidak boleh kosong!';
				}
				if ($ret['status'] != 'error') {
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
							$ret['message'] = "Berhasil update nilai usulan!";
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
}
