<?php
// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'tahun_anggaran' => '2024',
	'periode' => '',
), $atts);

global $wpdb;
$data_all = [
	'data' => array()
];

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$periode = $wpdb->get_row(
	$wpdb->prepare("
    SELECT 
		*
    FROM esakip_data_jadwal
    WHERE id=%d
      AND status = 1
", $input['periode']),
	ARRAY_A
);
if (empty($periode)) {
	die('<h1 class="text-center">Jadwal periode RPJMD/RPD tidak ditemukan!</h1>');
}

if (!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1) {
	$tahun_periode = $periode['tahun_selesai_anggaran'];
} else {
	$tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

// pokin level 1
$pohon_kinerja_level_1 = $wpdb->get_results($wpdb->prepare("
	SELECT 
		* 
	FROM esakip_pohon_kinerja 
	WHERE parent=0 
		AND level=1 
		AND active=1 
		AND id_jadwal=%d 
	ORDER BY nomor_urut
", $input['periode']), ARRAY_A);
if (!empty($pohon_kinerja_level_1)) {
	foreach ($pohon_kinerja_level_1 as $level_1) {
		if (empty($data_all['data'][$level_1['id']])) {
			$data_all['data'][$level_1['id']] = [
				'id' => $level_1['id'],
				'label' => $level_1['label'],
				'level' => $level_1['level'],
				'indikator' => array(),
				'data' => array()
			];
			if (empty($level_1['nomor_urut'])) {
				$level_1['nomor_urut'] = count($data_all['data']);
				$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $level_1['nomor_urut']), array(
					'id' => $level_1['id']
				));
			}
		}

		// indikator pokin level 1
		$indikator_pohon_kinerja_level_1 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_pohon_kinerja 
			WHERE parent=%d 
				AND level=1 
				AND active=1 
				AND id_jadwal=%d 
			ORDER BY nomor_urut
		", $level_1['id'], $input['periode']), ARRAY_A);
		if (!empty($indikator_pohon_kinerja_level_1)) {
			foreach ($indikator_pohon_kinerja_level_1 as $indikator_level_1) {
				if (!empty($indikator_level_1['label_indikator_kinerja'])) {
					if (empty($data_all['data'][$level_1['id']]['indikator'][$indikator_level_1['id']])) {
						$data_all['data'][$level_1['id']]['indikator'][$indikator_level_1['id']] = [
							'id' => $indikator_level_1['id'],
							'parent' => $indikator_level_1['parent'],
							'label_indikator_kinerja' => $indikator_level_1['label_indikator_kinerja'],
							'level' => $indikator_level_1['level']
						];
						if (empty($indikator_level_1['nomor_urut'])) {
							$indikator_level_1['nomor_urut'] = count($data_all['data'][$level_1['id']]['indikator']);
							$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $indikator_level_1['nomor_urut']), array(
								'id' => $indikator_level_1['id']
							));
						}
					}
				}
			}
		}

		// koneksi pokin pemda dan opd
		// untuk mendapatkan koneksi pokin level 4 pemda
		$koneksi_pokin_pemda_level_1 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				koneksi.* ,
				pk.id as id_parent,
				pk.level as level_parent,
				pk.label as label_parent,
				pk.label_indikator_kinerja
			FROM esakip_koneksi_pokin_pemda_opd koneksi
			LEFT JOIN esakip_pohon_kinerja_opd as pk ON koneksi.parent_pohon_kinerja_koneksi = pk.id
			WHERE koneksi.parent_pohon_kinerja=%d 
				AND koneksi.active=1 
			ORDER BY koneksi.id
		", $level_1['id']), ARRAY_A);

		if (!empty($koneksi_pokin_pemda_level_1)) {
			foreach ($koneksi_pokin_pemda_level_1 as $key_koneksi_pokin_1 => $koneksi_pokin_level_1) {
				$nama_perangkat_koneksi = '';

				$this_data_id_skpd = $koneksi_pokin_level_1['id_skpd_koneksi'];
				if ($koneksi_pokin_level_1['tipe'] == 1 || $koneksi_pokin_level_1['tipe'] == 3) {
					$nama_skpd = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								nama_skpd,
								id_skpd,
								tahun_anggaran
							FROM esakip_data_unit 
							WHERE active = 1 
							  AND id_skpd = %d
							  AND tahun_anggaran = %d
						", $this_data_id_skpd, $tahun_anggaran_sakip),
						ARRAY_A
					);
					$nama_perangkat_koneksi = $nama_skpd['nama_skpd'] ?? 'Nama PD tidak ditemukan.';
					$id_skpd_view_pokin = $koneksi_pokin_level_1['id_skpd_koneksi'];
				} elseif ($koneksi_pokin_level_1['tipe'] == 4) {
					$nama_perangkat_koneksi = $koneksi_pokin_level_1['nama_desa'];
				} elseif ($koneksi_pokin_level_1['tipe'] == 2) {
					$lembaga_lainnya = $wpdb->get_var(
						$wpdb->prepare("
							SELECT 
								nama_lembaga
							FROM esakip_data_lembaga_lainnya 
							WHERE active = 1 
							  AND id = %d
						", $this_data_id_skpd)
					);
					$nama_perangkat_koneksi = $lembaga_lainnya ?: 'Nama Lembaga tidak ditemukan.';
				}
				$data_parent_tujuan = array();
				$data_pokin_opd = array();
				$id_level_1_parent = 0;
				$indikator_opd = array();
				if ($koneksi_pokin_level_1['status_koneksi'] == 1 && $koneksi_pokin_level_1['tipe'] == 1) {
					// untuk mendapatkan id parent level 1 suatu opd
					$data_parent_tujuan = $this->get_parent_1_koneksi_pokin_pemda_opd(
						array(
							'id' => $koneksi_pokin_level_1['id'],
							'level' => $koneksi_pokin_level_1['level_parent'],
							'periode' => $input['periode'],
							'tipe' => 'opd',
							'id_parent' => $koneksi_pokin_level_1['id_parent'],
							'id_skpd' => $id_skpd_view_pokin,
							'keterangan_tolak' => $koneksi_pokin_level_1['keterangan_tolak']
						)
					);
					$data_pokin_opd = $this->get_pokin(array(
						'id' => $koneksi_pokin_level_1['id_parent'],
						'level' => $koneksi_pokin_level_1['level_parent'] + 1,
						'periode' => $input['periode'],
						'tipe' => 'opd',
						'id_skpd' => $id_skpd_view_pokin
					));

					$indikator_opd_db = $wpdb->get_results($wpdb->prepare("
						SELECT 
							label_indikator_kinerja
						FROM esakip_pohon_kinerja_opd koneksi
						WHERE parent = %d
							AND level = %d
							AND active = 1
					", $koneksi_pokin_level_1['id_parent'], $koneksi_pokin_level_1['level_parent']), ARRAY_A);

					$indikator_opd = array_column($indikator_opd_db, 'label_indikator_kinerja');
				}

				if (!empty($data_parent_tujuan)) {
					$id_level_1_parent = $data_parent_tujuan['id'];
				}

				if (empty($data_all['data'][$level_1['id']]['koneksi_pokin'][$key_koneksi_pokin_1])) {
					$data_all['data'][$level_1['id']]['koneksi_pokin'][$key_koneksi_pokin_1] = [
						'id' => $koneksi_pokin_level_1['id'],
						'parent_pohon_kinerja' => $koneksi_pokin_level_1['parent_pohon_kinerja'],
						'status_koneksi' => $koneksi_pokin_level_1['status_koneksi'],
						'label_parent' => $koneksi_pokin_level_1['label_parent'],
						'keterangan_tolak' => $koneksi_pokin_level_1['keterangan_tolak'],
						'nama_skpd' => $nama_perangkat_koneksi,
						'id_skpd_view_pokin' => $id_skpd_view_pokin,
						'id_level_1_parent' => $id_level_1_parent,
						'id_level_2_parent' => $koneksi_pokin_level_1['id_parent'],
						'pokin_opd_turunan' => $data_pokin_opd,
						'label_indikator_kinerja' => $indikator_opd
					];
				}
			}
		}

		// pokin level 2 
		$pohon_kinerja_level_2 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_pohon_kinerja 
			WHERE parent=%d 
				AND level=2
				AND active=1 
				AND id_jadwal=%d 
			ORDER by nomor_urut
		", $level_1['id'], $input['periode']), ARRAY_A);
		if (!empty($pohon_kinerja_level_2)) {
			foreach ($pohon_kinerja_level_2 as $level_2) {
				if (empty($data_all['data'][$level_1['id']]['data'][$level_2['id']])) {
					$data_all['data'][$level_1['id']]['data'][$level_2['id']] = [
						'id' => $level_2['id'],
						'label' => $level_2['label'],
						'level' => $level_2['level'],
						'indikator' => array(),
						'data' => array()
					];
					if (empty($level_2['nomor_urut'])) {
						$level_2['nomor_urut'] = count($data_all['data'][$level_1['id']]['data']);
						$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $level_2['nomor_urut']), array(
							'id' => $level_2['id']
						));
					}
				}

				// indikator pokin level 2
				$indikator_pohon_kinerja_level_2 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_pohon_kinerja 
					WHERE parent=%d 
						AND level=2 
						AND active=1 
						AND id_jadwal=%d 
					ORDER BY nomor_urut
				", $level_2['id'], $input['periode']), ARRAY_A);
				if (!empty($indikator_pohon_kinerja_level_2)) {
					foreach ($indikator_pohon_kinerja_level_2 as $indikator_level_2) {
						if (!empty($indikator_level_2['label_indikator_kinerja'])) {
							if (empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['indikator'][$indikator_level_2['id']])) {
								$data_all['data'][$level_1['id']]['data'][$level_2['id']]['indikator'][$indikator_level_2['id']] = [
									'id' => $indikator_level_2['id'],
									'parent' => $indikator_level_2['parent'],
									'label_indikator_kinerja' => $indikator_level_2['label_indikator_kinerja'],
									'level' => $indikator_level_2['level']
								];
								if (empty($indikator_level_2['nomor_urut'])) {
									$indikator_level_2['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['indikator']);
									$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $indikator_level_2['nomor_urut']), array(
										'id' => $indikator_level_2['id']
									));
								}
							}
						}
					}
				}

				// koneksi pokin pemda dan opd
				// untuk mendapatkan koneksi pokin level 4 pemda
				$koneksi_pokin_pemda_level_2 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						koneksi.* ,
						pk.id as id_parent,
						pk.level as level_parent,
						pk.label as label_parent,
						pk.label_indikator_kinerja
					FROM esakip_koneksi_pokin_pemda_opd koneksi
					LEFT JOIN esakip_pohon_kinerja_opd as pk ON koneksi.parent_pohon_kinerja_koneksi = pk.id
					WHERE koneksi.parent_pohon_kinerja=%d 
						AND koneksi.active=1 
					ORDER BY koneksi.id
				", $level_2['id']), ARRAY_A);

				if (!empty($koneksi_pokin_pemda_level_2)) {
					foreach ($koneksi_pokin_pemda_level_2 as $key_koneksi_pokin_2 => $koneksi_pokin_level_2) {
						$nama_perangkat_koneksi = '';

						$this_data_id_skpd = $koneksi_pokin_level_2['id_skpd_koneksi'];
						if ($koneksi_pokin_level_2['tipe'] == 1 || $koneksi_pokin_level_2['tipe'] == 3) {
							$nama_skpd = $wpdb->get_row(
								$wpdb->prepare("
									SELECT 
										nama_skpd,
										id_skpd,
										tahun_anggaran
									FROM esakip_data_unit 
									WHERE active = 1 
									AND id_skpd = %d
									AND tahun_anggaran = %d
								", $this_data_id_skpd, $tahun_anggaran_sakip),
								ARRAY_A
							);
							$nama_perangkat_koneksi = $nama_skpd['nama_skpd'] ?? 'Nama PD tidak ditemukan.';
							$id_skpd_view_pokin = $koneksi_pokin_level_2['id_skpd_koneksi'];
						} elseif ($koneksi_pokin_level_2['tipe'] == 4) {
							$nama_perangkat_koneksi = $koneksi_pokin_level_2['nama_desa'];
						} elseif ($koneksi_pokin_level_1['tipe'] == 2) {
							$lembaga_lainnya = $wpdb->get_var(
								$wpdb->prepare("
									SELECT 
										nama_lembaga
									FROM esakip_data_lembaga_lainnya 
									WHERE active = 1 
									AND id = %d
								", $this_data_id_skpd)
							);
							$nama_perangkat_koneksi = $lembaga_lainnya ?: 'Nama Lembaga tidak ditemukan.';
						}

						$data_parent_tujuan = array();
						$data_pokin_opd = array();
						$id_level_1_parent = 0;
						$indikator_opd = array();
						if ($koneksi_pokin_level_2['status_koneksi'] == 1 && $koneksi_pokin_level_2['tipe'] == 1) {
							// untuk mendapatkan id parent level 1 suatu opd
							$data_parent_tujuan = $this->get_parent_1_koneksi_pokin_pemda_opd(
								array(
									'id' => $koneksi_pokin_level_2['id'],
									'level' => $koneksi_pokin_level_2['level_parent'],
									'periode' => $input['periode'],
									'tipe' => 'opd',
									'id_parent' => $koneksi_pokin_level_2['id_parent'],
									'id_skpd' => $id_skpd_view_pokin,
									'keterangan_tolak' => $koneksi_pokin_level_2['keterangan_tolak']
								)
							);
							$data_pokin_opd = $this->get_pokin(array(
								'id' => $koneksi_pokin_level_2['id_parent'],
								'level' => $koneksi_pokin_level_2['level_parent'] + 1,
								'periode' => $input['periode'],
								'tipe' => 'opd',
								'id_skpd' => $id_skpd_view_pokin
							));

							$indikator_opd_db = $wpdb->get_results($wpdb->prepare("
								SELECT 
									label_indikator_kinerja
								FROM esakip_pohon_kinerja_opd koneksi
								WHERE parent = %d
									AND level = %d
									AND active = 1
							", $koneksi_pokin_level_2['id_parent'], $koneksi_pokin_level_2['level_parent']), ARRAY_A);

							$indikator_opd = array_column($indikator_opd_db, 'label_indikator_kinerja');
							// print_r($indikator_opd); die();
						}

						if (!empty($data_parent_tujuan)) {
							$id_level_1_parent = $data_parent_tujuan['id'];
						}

						if (empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['koneksi_pokin'][$key_koneksi_pokin_2])) {
							$data_all['data'][$level_1['id']]['data'][$level_2['id']]['koneksi_pokin'][$key_koneksi_pokin_2] = [
								'id' => $koneksi_pokin_level_2['id'],
								'parent_pohon_kinerja' => $koneksi_pokin_level_2['parent_pohon_kinerja'],
								'status_koneksi' => $koneksi_pokin_level_2['status_koneksi'],
								'label_parent' => $koneksi_pokin_level_2['label_parent'],
								'keterangan_tolak' => $koneksi_pokin_level_2['keterangan_tolak'],
								'nama_skpd' => $nama_perangkat_koneksi,
								'id_skpd_view_pokin' => $id_skpd_view_pokin,
								'id_level_1_parent' => $id_level_1_parent,
								'id_level_2_parent' => $koneksi_pokin_level_2['id_parent'],
								'pokin_opd_turunan' => $data_pokin_opd,
								'label_indikator_kinerja' => $indikator_opd
							];
						}
					}
				}

				// pokin level 3
				$pohon_kinerja_level_3 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_pohon_kinerja 
					WHERE parent=%d 
						AND level=3 
						AND active=1 
						AND id_jadwal=%d 
					ORDER by nomor_urut
				", $level_2['id'], $input['periode']), ARRAY_A);
				if (!empty($pohon_kinerja_level_3)) {
					foreach ($pohon_kinerja_level_3 as $level_3) {
						if (empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']])) {
							$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']] = [
								'id' => $level_3['id'],
								'label' => $level_3['label'],
								'level' => $level_3['level'],
								'indikator' => array(),
								'data' => array()
							];
							if (empty($level_3['nomor_urut'])) {
								$level_3['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data']);
								$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $level_3['nomor_urut']), array(
									'id' => $level_3['id']
								));
							}
						}

						// indikator pokin level 3
						$indikator_pohon_kinerja_level_3 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pohon_kinerja 
							WHERE parent=%d 
								AND level=3 
								AND active=1 
								AND id_jadwal=%d
							ORDER BY nomor_urut
						", $level_3['id'], $input['periode']), ARRAY_A);
						if (!empty($indikator_pohon_kinerja_level_3)) {
							foreach ($indikator_pohon_kinerja_level_3 as $indikator_level_3) {
								if (!empty($indikator_level_3['label_indikator_kinerja'])) {
									if (empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['indikator'][$indikator_level_3['id']])) {
										$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['indikator'][$indikator_level_3['id']] = [
											'id' => $indikator_level_3['id'],
											'parent' => $indikator_level_3['parent'],
											'label_indikator_kinerja' => $indikator_level_3['label_indikator_kinerja'],
											'level' => $indikator_level_3['level']
										];
										if (empty($indikator_level_3['nomor_urut'])) {
											$indikator_level_3['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['indikator']);
											$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $indikator_level_3['nomor_urut']), array(
												'id' => $indikator_level_3['id']
											));
										}
									}
								}
							}
						}

						// koneksi pokin pemda dan opd
						// untuk mendapatkan koneksi pokin level 4 pemda
						$koneksi_pokin_pemda_level_3 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								koneksi.* ,
								pk.id as id_parent,
								pk.level as level_parent,
								pk.label as label_parent,
								pk.label_indikator_kinerja
							FROM esakip_koneksi_pokin_pemda_opd koneksi
							LEFT JOIN esakip_pohon_kinerja_opd as pk ON koneksi.parent_pohon_kinerja_koneksi = pk.id
							WHERE koneksi.parent_pohon_kinerja=%d 
								AND koneksi.active=1 
							ORDER BY koneksi.id
						", $level_3['id']), ARRAY_A);

						if (!empty($koneksi_pokin_pemda_level_3)) {
							foreach ($koneksi_pokin_pemda_level_3 as $key_koneksi_pokin_3 => $koneksi_pokin_level_3) {
								$nama_perangkat_koneksi = '';

								$this_data_id_skpd = $koneksi_pokin_level_3['id_skpd_koneksi'];
								if ($koneksi_pokin_level_3['tipe'] == 1 || $koneksi_pokin_level_3['tipe'] == 3) {
									$nama_skpd = $wpdb->get_row(
										$wpdb->prepare("
											SELECT 
												nama_skpd,
												id_skpd,
												tahun_anggaran
											FROM esakip_data_unit 
											WHERE active = 1 
											  AND id_skpd = %d
											  AND tahun_anggaran = %d
										", $this_data_id_skpd, $tahun_anggaran_sakip),
										ARRAY_A
									);
									$nama_perangkat_koneksi = $nama_skpd['nama_skpd'] ?? 'Nama PD tidak ditemukan.';
									$id_skpd_view_pokin = $koneksi_pokin_level_3['id_skpd_koneksi'];
								} elseif ($koneksi_pokin_level_3['tipe'] == 4) {
									$nama_perangkat_koneksi = $koneksi_pokin_level_3['nama_desa'];
								} elseif ($koneksi_pokin_level_1['tipe'] == 2) {
									$lembaga_lainnya = $wpdb->get_var(
										$wpdb->prepare("
											SELECT 
												nama_lembaga
											FROM esakip_data_lembaga_lainnya 
											WHERE active = 1 
											  AND id = %d
										", $this_data_id_skpd)
									);
									$nama_perangkat_koneksi = $lembaga_lainnya ?: 'Nama Lembaga tidak ditemukan.';
								}

								$data_parent_tujuan = array();
								$data_pokin_opd = array();
								$id_level_1_parent = 0;
								$indikator_opd = array();
								if ($koneksi_pokin_level_3['status_koneksi'] == 1 && $koneksi_pokin_level_3['tipe'] == 1) {
									// untuk mendapatkan id parent level 1 suatu opd
									$data_parent_tujuan = $this->get_parent_1_koneksi_pokin_pemda_opd(
										array(
											'id' => $koneksi_pokin_level_3['id'],
											'level' => $koneksi_pokin_level_3['level_parent'],
											'periode' => $input['periode'],
											'tipe' => 'opd',
											'id_parent' => $koneksi_pokin_level_3['id_parent'],
											'id_skpd' => $id_skpd_view_pokin,
											'keterangan_tolak' => $koneksi_pokin_level_3['keterangan_tolak']
										)
									);
									$data_pokin_opd = $this->get_pokin(array(
										'id' => $koneksi_pokin_level_3['id_parent'],
										'level' => $koneksi_pokin_level_3['level_parent'] + 1,
										'periode' => $input['periode'],
										'tipe' => 'opd',
										'id_skpd' => $id_skpd_view_pokin
									));

									$indikator_opd_db = $wpdb->get_results($wpdb->prepare("
										SELECT 
											label_indikator_kinerja
										FROM esakip_pohon_kinerja_opd koneksi
										WHERE parent = %d
											AND level = %d
											AND active = 1
									", $koneksi_pokin_level_3['id_parent'], $koneksi_pokin_level_3['level_parent']), ARRAY_A);

									$indikator_opd = array_column($indikator_opd_db, 'label_indikator_kinerja');
								}

								if (!empty($data_parent_tujuan)) {
									$id_level_1_parent = $data_parent_tujuan['id'];
								}

								if (empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['koneksi_pokin'][$key_koneksi_pokin_3])) {
									$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['koneksi_pokin'][$key_koneksi_pokin_3] = [
										'id' => $koneksi_pokin_level_3['id'],
										'parent_pohon_kinerja' => $koneksi_pokin_level_3['parent_pohon_kinerja'],
										'status_koneksi' => $koneksi_pokin_level_3['status_koneksi'],
										'label_parent' => $koneksi_pokin_level_3['label_parent'],
										'keterangan_tolak' => $koneksi_pokin_level_3['keterangan_tolak'],
										'nama_skpd' => $nama_perangkat_koneksi,
										'id_skpd_view_pokin' => $id_skpd_view_pokin,
										'id_level_1_parent' => $id_level_1_parent,
										'id_level_2_parent' => $koneksi_pokin_level_3['id_parent'],
										'pokin_opd_turunan' => $data_pokin_opd,
										'label_indikator_kinerja' => $indikator_opd
									];
								}
							}
						}

						// pokin level 4
						$pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pohon_kinerja 
							WHERE parent=%d 
								AND level=4
								AND active=1 
								AND id_jadwal=%d
							ORDER by nomor_urut
						", $level_3['id'], $input['periode']), ARRAY_A);
						if (!empty($pohon_kinerja_level_4)) {
							foreach ($pohon_kinerja_level_4 as $level_4) {
								if (empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']])) {
									$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']] = [
										'id' => $level_4['id'],
										'label' => $level_4['label'],
										'level' => $level_4['level'],
										'indikator' => array()
									];
									if (empty($level_4['nomor_urut'])) {
										$level_4['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data']);
										$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $level_4['nomor_urut']), array(
											'id' => $level_4['id']
										));
									}
								}

								// indikator pokin level 4
								$indikator_pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
									SELECT 
										* 
									FROM esakip_pohon_kinerja 
									WHERE parent=%d 
										AND level=4 
										AND active=1 
										AND id_jadwal=%d
									ORDER BY nomor_urut
								", $level_4['id'], $input['periode']), ARRAY_A);
								if (!empty($indikator_pohon_kinerja_level_4)) {
									foreach ($indikator_pohon_kinerja_level_4 as $indikator_level_4) {
										if (!empty($indikator_level_4['label_indikator_kinerja'])) {
											if (empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['indikator'][$indikator_level_4['id']])) {
												$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['indikator'][$indikator_level_4['id']] = [
													'id' => $indikator_level_4['id'],
													'parent' => $indikator_level_4['parent'],
													'label_indikator_kinerja' => $indikator_level_4['label_indikator_kinerja'],
													'level' => $indikator_level_4['level']
												];
												if (empty($indikator_level_4['nomor_urut'])) {
													$indikator_level_4['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['indikator']);
													$wpdb->update('esakip_pohon_kinerja', array('nomor_urut' => $indikator_level_4['nomor_urut']), array(
														'id' => $indikator_level_4['id']
													));
												}
											}
										}
									}
								}

								// koneksi pokin pemda dan opd
								// untuk mendapatkan koneksi pokin level 4 pemda
								$koneksi_pokin_pemda_level_4 = $wpdb->get_results($wpdb->prepare("
									SELECT 
										koneksi.* ,
										pk.id as id_parent,
										pk.level as level_parent,
										pk.label as label_parent,
										pk.label_indikator_kinerja
									FROM esakip_koneksi_pokin_pemda_opd koneksi
									LEFT JOIN esakip_pohon_kinerja_opd as pk ON koneksi.parent_pohon_kinerja_koneksi = pk.id
									WHERE koneksi.parent_pohon_kinerja=%d 
										AND koneksi.active=1 
									ORDER BY koneksi.id
								", $level_4['id']), ARRAY_A);

								if (!empty($koneksi_pokin_pemda_level_4)) {
									foreach ($koneksi_pokin_pemda_level_4 as $key_koneksi_pokin_4 => $koneksi_pokin_level_4) {
										$nama_perangkat_koneksi = '';
										$this_data_id_skpd = $koneksi_pokin_level_4['id_skpd_koneksi'];
										if ($koneksi_pokin_level_4['tipe'] == 1 || $koneksi_pokin_level_4['tipe'] == 3) {
											$nama_skpd = $wpdb->get_row(
												$wpdb->prepare("
													SELECT 
														nama_skpd,
														id_skpd,
														tahun_anggaran
													FROM esakip_data_unit 
													WHERE active = 1 
													  AND id_skpd = %d
													  AND tahun_anggaran = %d
												", $this_data_id_skpd, $tahun_anggaran_sakip),
												ARRAY_A
											);
											$nama_perangkat_koneksi = $nama_skpd['nama_skpd'] ?? 'Nama PD tidak ditemukan.';
											$id_skpd_view_pokin = $koneksi_pokin_level_4['id_skpd_koneksi'];
										} elseif ($koneksi_pokin_level_4['tipe'] == 4) {
											$nama_perangkat_koneksi = $koneksi_pokin_level_4['nama_desa'];
										} elseif ($koneksi_pokin_level_1['tipe'] == 2) {
											$lembaga_lainnya = $wpdb->get_var(
												$wpdb->prepare("
													SELECT 
														nama_lembaga
													FROM esakip_data_lembaga_lainnya 
													WHERE active = 1 
													  AND id = %d
												", $this_data_id_skpd)
											);
											$nama_perangkat_koneksi = $lembaga_lainnya ?: 'Nama Lembaga tidak ditemukan.';
										}

										$data_parent_tujuan = array();
										$data_pokin_opd = array();
										$id_level_1_parent = 0;
										$indikator_opd = array();
										if ($koneksi_pokin_level_4['status_koneksi'] == 1 && $koneksi_pokin_level_4['tipe'] == 1) {
											// untuk mendapatkan id parent level 1 suatu opd
											$data_parent_tujuan = $this->get_parent_1_koneksi_pokin_pemda_opd(
												array(
													'id' => $koneksi_pokin_level_4['id'],
													'level' => $koneksi_pokin_level_4['level_parent'],
													'periode' => $input['periode'],
													'tipe' => 'opd',
													'id_parent' => $koneksi_pokin_level_4['id_parent'],
													'id_skpd' => $id_skpd_view_pokin,
													'keterangan_tolak' => $koneksi_pokin_level_4['keterangan_tolak']
												)
											);
											$data_pokin_opd = $this->get_pokin(array(
												'id' => $koneksi_pokin_level_4['id_parent'],
												'level' => $koneksi_pokin_level_4['level_parent'] + 1,
												'periode' => $input['periode'],
												'tipe' => 'opd',
												'id_skpd' => $id_skpd_view_pokin
											));

											$indikator_opd_db = $wpdb->get_results($wpdb->prepare("
												SELECT 
													label_indikator_kinerja
												FROM esakip_pohon_kinerja_opd koneksi
												WHERE parent = %d
													AND level = %d
													AND active = 1
											", $koneksi_pokin_level_4['id_parent'], $koneksi_pokin_level_4['level_parent']), ARRAY_A);

											$indikator_opd = array_column($indikator_opd_db, 'label_indikator_kinerja');
											// print_r($indikator_opd); die();
										}

										if (!empty($data_parent_tujuan)) {
											$id_level_1_parent = $data_parent_tujuan['id'];
										}

										if (empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['koneksi_pokin'][$key_koneksi_pokin_4])) {
											$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['koneksi_pokin'][$key_koneksi_pokin_4] = [
												'id' => $koneksi_pokin_level_4['id'],
												'parent_pohon_kinerja' => $koneksi_pokin_level_4['parent_pohon_kinerja'],
												'status_koneksi' => $koneksi_pokin_level_4['status_koneksi'],
												'label_parent' => $koneksi_pokin_level_4['label_parent'],
												'keterangan_tolak' => $koneksi_pokin_level_4['keterangan_tolak'],
												'nama_skpd' => $nama_perangkat_koneksi,
												'id_skpd_view_pokin' => $id_skpd_view_pokin,
												'id_level_1_parent' => $id_level_1_parent,
												'id_level_2_parent' => $koneksi_pokin_level_4['id_parent'],
												'pokin_opd_turunan' => $data_pokin_opd,
												'label_indikator_kinerja' => $indikator_opd
											];
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

$view_kinerja = $this->functions->generatePage(array(
	'nama_page' => 'View Pohon Kinerja',
	'content' => '[view_pohon_kinerja]',
	'show_header' => 1,
	'post_status' => 'private'
));

$view_kinerja_pokin_opd = $this->functions->generatePage(array(
	'nama_page' => 'View Pohon Kinerja OPD',
	'content' => '[view_pohon_kinerja_opd periode=' . $input['periode'] . ']',
	'show_header' => 1,
	'post_status' => 'private'
));

$html = '';
foreach ($data_all['data'] as $key1 => $level_1) {
	$indikator = array();
	foreach ($level_1['indikator'] as $indikatorlevel1) {
		$indikator[] = $indikatorlevel1['label_indikator_kinerja'];
	}
	if (!isset($level_1['koneksi_pokin']) || !is_array($level_1['koneksi_pokin'])) {
		$level_1['koneksi_pokin'] = array();
	}
	$data_koneksi = array(
		'status' => '',
		'data' => []
	);
	$koneksi_pokin = array();
	$koneksi_indikator_pokin = array();
	$koneksi_pokin_turunan = array();
	foreach ($level_1['koneksi_pokin'] as $koneksi_pokin_level_1) {

		$nama_skpd = $koneksi_pokin_level_1['nama_skpd'];
		if ($koneksi_pokin_level_1['id_level_1_parent'] !== 0) {
			$nama_skpd = "<a href='" . $view_kinerja_pokin_opd['url'] . "&id_skpd=" . $koneksi_pokin_level_1['id_skpd_view_pokin']  . "&id=" . $koneksi_pokin_level_1['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $koneksi_pokin_level_1['nama_skpd'] . "</a>";
		}

		switch ($koneksi_pokin_level_1['status_koneksi']) {
			case '1':
				$status_koneksi = 'Disetujui';
				$label_color = 'success text-white';
				break;
			case '2':
				$status_koneksi = 'Ditolak';
				$label_color = 'danger text-white';
				break;

			default:
				$status_koneksi = 'Menunggu';
				$label_color = 'secondary text-white';
				break;
		}
		$data_koneksi['status'] = $status_koneksi;

		$nama_skpd_koneksi = '
			<span class="font-weight-bold">' . $nama_skpd . '</span>
			<span class="badge bg-' . $label_color . '">' . $status_koneksi . '</span>
		';

		$keterangan_tolak_koneksi = !empty($koneksi_pokin_level_1['keterangan_tolak']) ? "( ket: " . $koneksi_pokin_level_1['keterangan_tolak'] . " )" : '';

		$data_koneksi['data'][] = $nama_skpd_koneksi;
		$koneksi_pokin[] = '
			<div>' . $koneksi_pokin_level_1['label_parent'] . '</div>
			<div class="text-muted">' . $keterangan_tolak_koneksi . '</div>
		';
		$koneksi_indikator_pokin[] = '<div>' . implode("<hr/>", $koneksi_pokin_level_1['label_indikator_kinerja']) . '</div>';
		$koneksi_pokin_turunan[] = $koneksi_pokin_level_1['pokin_opd_turunan'];
	}

	$indikator_html = '<div>' . implode('</div><div class="mt-1 pt-1 border-top">', array_map('htmlspecialchars', $indikator)) . '</div>';
	$html .= '
		<tr>
			<td class="level1 align-middle d-flex justify-content-between align-items-center">
				<span class="font-weight-bold">
					<a href="' . $view_kinerja['url'] . '&id=' . $level_1['id'] . '&id_jadwal=' . $input['periode'] . '" target="_blank">' . htmlspecialchars($level_1['label']) . '</a>
				</span>
				<button class="btn btn-sm btn-primary ml-2 hide-print" onclick="handleDetailPokin(' . $level_1['id'] . '); return false;" title="Detail Pohon Kinerja">
					<span class="dashicons dashicons-info"></span>
				</button>
			</td>
			<td class="indikator text-muted align-middle">' . $indikator_html . '</td>
			<td colspan="15" class="hide-crosscutting"></td>
		</tr>';
	if (!empty($data_koneksi['data'])) {
		foreach ($data_koneksi['data'] as $i => $koneksi_skpd) {
			if ($data_koneksi['status'] == 'Menunggu') {
				$class = '';
				$class_indikator = '';
			} else {
				$class = 'koneksi font-weight-bold';
				$class_indikator = 'indikator-koneksi text-muted';
			}
			$html .= '
				<tr class="hide-crosscutting">
					<td colspan="8"></td>
					<td class="koneksi align-middle d-flex justify-content-between align-items-center">' . $koneksi_skpd . '</td>
					<td class="' . $class . '">' . ($koneksi_pokin[$i] ? $koneksi_pokin[$i] : '-') . '</td>
					<td class="' . $class_indikator . '">' . ($koneksi_indikator_pokin[$i] ? $koneksi_indikator_pokin[$i] : '-') . '</td>
					<td colspan="6" class="detail-pokin hide-crosscutting"></td>
				</tr>';

			foreach ($koneksi_pokin_turunan[$i] as $turunan3) {
				$indikator_label_3 = array_column($turunan3['indikator'], 'label_indikator_kinerja');
				$html .= '
					<tr class="detail-pokin hide-crosscutting"></td>
						<td colspan="11"></td>
						<td class="koneksi font-weight-bold">' . $turunan3['label'] . '</td>
						<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_3) . '</td>
						<td colspan="4"></td>
					</tr>';

				foreach ($turunan3['data'] as $turunan4) {
					$indikator_label_4 = array_column($turunan4['indikator'], 'label_indikator_kinerja');
					$html .= '
						<tr class="detail-pokin hide-crosscutting"></td>
							<td colspan="13"></td>
							<td class="koneksi font-weight-bold">' . $turunan4['label'] . '</td>
							<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_4) . '</td>
							<td colspan="2"></td>
						</tr>';

					foreach ($turunan4['data'] as $turunan5) {
						$indikator_label_5 = array_column($turunan5['indikator'], 'label_indikator_kinerja');
						$html .= '
							<tr class="detail-pokin hide-crosscutting"></td>
								<td colspan="15"></td>
								<td class="koneksi font-weight-bold">' . $turunan5['label'] . '</td>
								<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_5) . '</td>
							</tr>';
					}
				}
			}
		}
	}
	foreach (array_values($level_1['data']) as $key2 => $level_2) {
		$indikator = array();
		foreach ($level_2['indikator'] as $indikatorlevel2) {
			$indikator[] = $indikatorlevel2['label_indikator_kinerja'];
		}
		if (!isset($level_2['koneksi_pokin']) || !is_array($level_2['koneksi_pokin'])) {
			$level_2['koneksi_pokin'] = array();
		}
		$data_koneksi = array(
			'status' => '',
			'data' => []
		);
		$koneksi_pokin = array();
		$koneksi_indikator_pokin = array();
		$koneksi_pokin_turunan = array();
		foreach ($level_2['koneksi_pokin'] as $koneksi_pokin_level_2) {

			$nama_skpd = $koneksi_pokin_level_2['nama_skpd'];
			if ($koneksi_pokin_level_2['id_level_1_parent'] !== 0) {
				$nama_skpd = "<a href='" . $view_kinerja_pokin_opd['url'] . "&id_skpd=" . $koneksi_pokin_level_2['id_skpd_view_pokin']  . "&id=" . $koneksi_pokin_level_2['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $koneksi_pokin_level_2['nama_skpd'] . "</a>";
			}

			switch ($koneksi_pokin_level_2['status_koneksi']) {
				case '1':
					$status_koneksi = 'Disetujui';
					$label_color = 'success text-white';
					break;
				case '2':
					$status_koneksi = 'Ditolak';
					$label_color = 'danger text-white';
					break;

				default:
					$status_koneksi = 'Menunggu';
					$label_color = 'secondary text-white';
					break;
			}
			$data_koneksi['status'] = $status_koneksi;

			$nama_skpd_koneksi = '
				<span class="font-weight-bold">' . $nama_skpd . '</span>
				<span class="badge bg-' . $label_color . '">' . $status_koneksi . '</span>
			';

			$keterangan_tolak_koneksi = !empty($koneksi_pokin_level_2['keterangan_tolak']) ? "( ket: " . $koneksi_pokin_level_2['keterangan_tolak'] . " )" : '';

			$data_koneksi['data'][] = $nama_skpd_koneksi;
			$koneksi_pokin[] = '
				<div>' . $koneksi_pokin_level_2['label_parent'] . '</div>
				<div class="text-muted">' . $keterangan_tolak_koneksi . '</div>
			';
			$koneksi_indikator_pokin[] = '<div>' . implode("<hr/>", $koneksi_pokin_level_2['label_indikator_kinerja']) . '</div>';
			$koneksi_pokin_turunan[] = $koneksi_pokin_level_2['pokin_opd_turunan'];
		}

		$indikator_html = '<div>' . implode('</div><div class="mt-1 pt-1 border-top">', array_map('htmlspecialchars', $indikator)) . '</div>';
		$html .= '
			<tr>
				<td colspan="2"></td>
				<td class="level2 align-middle d-flex justify-content-between align-items-center">
					<span class="font-weight-bold">' . htmlspecialchars($level_2['label']) . '</span>
					<button class="btn btn-sm btn-primary ml-2" onclick="handleDetailPokin(' . $level_2['id'] . '); return false;" title="Detail Pohon Kinerja">
						<span class="dashicons dashicons-info"></span>
					</button>
				</td>
				<td class="indikator text-muted align-middle">' . $indikator_html . '</td>
				<td colspan="13" class="hide-crosscutting"></td>
			</tr>';
		if (!empty($data_koneksi['data'])) {
			foreach ($data_koneksi['data'] as $i => $koneksi_skpd) {
				if ($data_koneksi['status'] == 'Menunggu') {
					$class = '';
					$class_indikator = '';
				} else {
					$class = 'koneksi font-weight-bold';
					$class_indikator = 'indikator-koneksi text-muted';
				}
				$html .= '
					<tr class="hide-crosscutting">
						<td colspan="8"></td>
						<td class="koneksi align-middle d-flex justify-content-between align-items-center">' . $koneksi_skpd . '</td>
						<td class="' . $class . '">' . ($koneksi_pokin[$i] ? $koneksi_pokin[$i] : '-') . '</td>
						<td class="' . $class_indikator . '">' . ($koneksi_indikator_pokin[$i] ? $koneksi_indikator_pokin[$i] : '-') . '</td>
						<td colspan="6" class="detail-pokin hide-crosscutting"></td>
					</tr>';

				foreach ($koneksi_pokin_turunan[$i] as $turunan3) {
					$indikator_label_3 = array_column($turunan3['indikator'], 'label_indikator_kinerja');
					$html .= '
						<tr class="detail-pokin hide-crosscutting"></td>
							<td colspan="11"></td>
							<td class="koneksi font-weight-bold">' . $turunan3['label'] . '</td>
							<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_3) . '</td>
							<td colspan="4"></td>
						</tr>';

					foreach ($turunan3['data'] as $turunan4) {
						$indikator_label_4 = array_column($turunan4['indikator'], 'label_indikator_kinerja');
						$html .= '
							<tr class="detail-pokin hide-crosscutting"></td>
								<td colspan="13"></td>
								<td class="koneksi font-weight-bold">' . $turunan4['label'] . '</td>
								<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_4) . '</td>
								<td colspan="2"></td>
							</tr>';

						foreach ($turunan4['data'] as $turunan5) {
							$indikator_label_5 = array_column($turunan5['indikator'], 'label_indikator_kinerja');
							$html .= '
								<tr class="detail-pokin hide-crosscutting"></td>
									<td colspan="15"></td>
									<td class="koneksi font-weight-bold">' . $turunan5['label'] . '</td>
									<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_5) . '</td>
								</tr>';
						}
					}
				}
			}
		}

		foreach (array_values($level_2['data']) as $key3 => $level_3) {
			$indikator = array();
			foreach ($level_3['indikator'] as $indikatorlevel3) {
				$indikator[] = $indikatorlevel3['label_indikator_kinerja'];
			}
			if (!isset($level_3['koneksi_pokin']) || !is_array($level_3['koneksi_pokin'])) {
				$level_3['koneksi_pokin'] = array();
			}
			$data_koneksi = array(
				'status' => '',
				'data' => []
			);
			$koneksi_pokin = array();
			$koneksi_indikator_pokin = array();
			$koneksi_pokin_turunan = array();
			foreach ($level_3['koneksi_pokin'] as $koneksi_pokin_level_3) {

				$nama_skpd = $koneksi_pokin_level_3['nama_skpd'];
				if ($koneksi_pokin_level_3['id_level_1_parent'] !== 0) {
					$nama_skpd = "<a href='" . $view_kinerja_pokin_opd['url'] . "&id_skpd=" . $koneksi_pokin_level_3['id_skpd_view_pokin']  . "&id=" . $koneksi_pokin_level_3['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $koneksi_pokin_level_3['nama_skpd'] . "</a>";
				}

				switch ($koneksi_pokin_level_3['status_koneksi']) {
					case '1':
						$status_koneksi = 'Disetujui';
						$label_color = 'success text-white';
						break;
					case '2':
						$status_koneksi = 'Ditolak';
						$label_color = 'danger text-white';
						break;

					default:
						$status_koneksi = 'Menunggu';
						$label_color = 'secondary text-white';
						break;
				}
				$data_koneksi['status'] = $status_koneksi;

				$nama_skpd_koneksi = '
					<span class="font-weight-bold">' . $nama_skpd . '</span>
					<span class="badge bg-' . $label_color . '">' . $status_koneksi . '</span>';


				$keterangan_tolak_koneksi = !empty($koneksi_pokin_level_3['keterangan_tolak']) ? "( ket: " . $koneksi_pokin_level_3['keterangan_tolak'] . " )" : '';

				$data_koneksi['data'][] = $nama_skpd_koneksi;
				$koneksi_pokin[] = '
					<div>' . $koneksi_pokin_level_3['label_parent'] . '</div>
					<div class="text-muted">' . $keterangan_tolak_koneksi . '</div>';
				$koneksi_indikator_pokin[] = '
					<div>' . implode("<hr/>", $koneksi_pokin_level_3['label_indikator_kinerja']) . '</div>';
				$koneksi_pokin_turunan[] = $koneksi_pokin_level_3['pokin_opd_turunan'];
			}

			$indikator_html = '<div>' . implode('</div><div class="mt-1 pt-1 border-top">', array_map('htmlspecialchars', $indikator)) . '</div>';
			$html .= '
				<tr>
					<td colspan="4"></td>
					<td class="level3 align-middle d-flex justify-content-between align-items-center">
						<span class="font-weight-bold">' . htmlspecialchars($level_3['label']) . '</span>
						<button class="btn btn-sm btn-primary ml-2" onclick="handleDetailPokin(' . $level_3['id'] . '); return false;" title="Detail Pohon Kinerja">
							<span class="dashicons dashicons-info"></span>
						</button>
					</td>
					<td class="indikator text-muted align-middle">' . $indikator_html . '</td>
					<td colspan="11" class="hide-crosscutting"></td>
				</tr>';

			if (!empty($data_koneksi['data'])) {
				foreach ($data_koneksi['data'] as $i => $koneksi_skpd) {
					if ($data_koneksi['status'] == 'Menunggu') {
						$class = '';
						$class_indikator = '';
					} else {
						$class = 'koneksi font-weight-bold';
						$class_indikator = 'indikator-koneksi text-muted';
					}
					$html .= '
						<tr class="hide-crosscutting">
							<td colspan="8"></td>
							<td class="koneksi align-middle d-flex justify-content-between align-items-center">' . $koneksi_skpd . '</td>
							<td class="' . $class . '">' . ($koneksi_pokin[$i] ? $koneksi_pokin[$i] : '-') . '</td>
							<td class="' . $class_indikator . '">' . ($koneksi_indikator_pokin[$i] ? $koneksi_indikator_pokin[$i] : '-') . '</td>
							<td colspan="6" class="detail-pokin hide-crosscutting"></td>
						</tr>';

					foreach ($koneksi_pokin_turunan[$i] as $turunan3) {
						$indikator_label_3 = array_column($turunan3['indikator'], 'label_indikator_kinerja');
						$html .= '
							<tr class="detail-pokin hide-crosscutting"></td>
								<td colspan="11"></td>
								<td class="koneksi font-weight-bold">' . $turunan3['label'] . '</td>
								<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_3) . '</td>
								<td colspan="4"></td>
							</tr>';

						foreach ($turunan3['data'] as $turunan4) {
							$indikator_label_4 = array_column($turunan4['indikator'], 'label_indikator_kinerja');
							$html .= '
								<tr class="detail-pokin hide-crosscutting"></td>
									<td colspan="13"></td>
									<td class="koneksi font-weight-bold">' . $turunan4['label'] . '</td>
									<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_4) . '</td>
									<td colspan="2"></td>
								</tr>';

							foreach ($turunan4['data'] as $turunan5) {
								$indikator_label_5 = array_column($turunan5['indikator'], 'label_indikator_kinerja');
								$html .= '
									<tr class="detail-pokin hide-crosscutting"></td>
										<td colspan="15"></td>
										<td class="koneksi font-weight-bold">' . $turunan5['label'] . '</td>
										<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_5) . '</td>
									</tr>';
							}
						}
					}
				}
			}


			foreach (array_values($level_3['data']) as $key4 => $level_4) {
				$indikator = array();
				foreach ($level_4['indikator'] as $indikatorlevel4) {
					$indikator[] = $indikatorlevel4['label_indikator_kinerja'];
				}
				if (!isset($level_4['koneksi_pokin']) || !is_array($level_4['koneksi_pokin'])) {
					$level_4['koneksi_pokin'] = array();
				}
				$data_koneksi = array(
					'status' => '',
					'data' => []
				);
				$koneksi_pokin = array();
				$koneksi_indikator_pokin = array();
				$koneksi_pokin_turunan = array();
				foreach ($level_4['koneksi_pokin'] as $koneksi_pokin_level_4) {

					$nama_skpd = $koneksi_pokin_level_4['nama_skpd'];
					if ($koneksi_pokin_level_4['id_level_1_parent'] !== 0) {
						$nama_skpd = "<a href='" . $view_kinerja_pokin_opd['url'] . "&id_skpd=" . $koneksi_pokin_level_4['id_skpd_view_pokin']  . "&id=" . $koneksi_pokin_level_4['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $koneksi_pokin_level_4['nama_skpd'] . "</a>";
					}

					switch ($koneksi_pokin_level_4['status_koneksi']) {
						case '1':
							$status_koneksi = 'Disetujui';
							$label_color = 'success text-white';
							break;
						case '2':
							$status_koneksi = 'Ditolak';
							$label_color = 'danger text-white';
							break;

						default:
							$status_koneksi = 'Menunggu';
							$label_color = 'secondary text-white';
							break;
					}
					$data_koneksi['status'] = $status_koneksi;

					$nama_skpd_koneksi = '
						<span class="font-weight-bold">' . $nama_skpd . '</span>
						<span class="badge bg-' . $label_color . '">' . $status_koneksi . '</span>
					';

					$keterangan_tolak_koneksi = !empty($koneksi_pokin_level_4['keterangan_tolak']) ? "( ket: " . $koneksi_pokin_level_4['keterangan_tolak'] . " )" : '';

					$data_koneksi['data'][] = $nama_skpd_koneksi;
					$koneksi_pokin[] = '
						<div>' . $koneksi_pokin_level_4['label_parent'] . '</div>
						<div class="text-muted">' . $keterangan_tolak_koneksi . '</div>
					';
					$koneksi_indikator_pokin[] = '<div>' . implode("<hr/>", $koneksi_pokin_level_4['label_indikator_kinerja']) . '</div>';
					$koneksi_pokin_turunan[] = $koneksi_pokin_level_4['pokin_opd_turunan'];
				}
				$indikator_html = '<div>' . implode('</div><div class="mt-1 pt-1 border-top">', array_map('htmlspecialchars', $indikator)) . '</div>';
				$html .= '
					<tr>
						<td colspan="6"></td>
						<td class="level4 align-middle d-flex justify-content-between align-items-center">
							<span class="font-weight-bold">' . htmlspecialchars($level_4['label']) . '</span>
							<button class="btn btn-sm btn-primary ml-2" onclick="handleDetailPokin(' . $level_4['id'] . '); return false;" title="Detail Pohon Kinerja">
								<span class="dashicons dashicons-info"></span>
							</button>
						</td>
						<td class="indikator text-muted align-middle">' . $indikator_html . '</td>
						<td colspan="9" class="hide-crosscutting"></td>
					</tr>';
				if (!empty($data_koneksi['data'])) {
					foreach ($data_koneksi['data'] as $i => $koneksi_skpd) {
						if ($data_koneksi['status'] == 'Menunggu') {
							$class = '';
							$class_indikator = '';
						} else {
							$class = 'koneksi font-weight-bold';
							$class_indikator = 'indikator-koneksi text-muted';
						}
						$html .= '
							<tr class="hide-crosscutting">
								<td colspan="8"></td>
								<td class="koneksi align-middle d-flex justify-content-between align-items-center">' . $koneksi_skpd . '</td>
								<td class="' . $class . '">' . ($koneksi_pokin[$i] ? $koneksi_pokin[$i] : '-') . '</td>
								<td class="' . $class_indikator . '">' . ($koneksi_indikator_pokin[$i] ? $koneksi_indikator_pokin[$i] : '-') . '</td>
								<td colspan="6" class="detail-pokin hide-crosscutting"></td>
							</tr>';

						foreach ($koneksi_pokin_turunan[$i] as $turunan3) {
							$indikator_label_3 = array_column($turunan3['indikator'], 'label_indikator_kinerja');
							$html .= '
								<tr class="detail-pokin hide-crosscutting"></td>
									<td colspan="11"></td>
									<td class="koneksi font-weight-bold">' . $turunan3['label'] . '</td>
									<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_3) . '</td>
									<td colspan="4"></td>
								</tr>';

							foreach ($turunan3['data'] as $turunan4) {
								$indikator_label_4 = array_column($turunan4['indikator'], 'label_indikator_kinerja');
								$html .= '
									<tr class="detail-pokin hide-crosscutting"></td>
										<td colspan="13"></td>
										<td class="koneksi font-weight-bold">' . $turunan4['label'] . '</td>
										<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_4) . '</td>
										<td colspan="2"></td>
									</tr>';

								foreach ($turunan4['data'] as $turunan5) {
									$indikator_label_5 = array_column($turunan5['indikator'], 'label_indikator_kinerja');
									$html .= '
										<tr class="detail-pokin hide-crosscutting"></td>
											<td colspan="15"></td>
											<td class="koneksi font-weight-bold">' . $turunan5['label'] . '</td>
											<td class="indikator-koneksi text-muted">' . implode('<hr>', $indikator_label_5) . '</td>
										</tr>';
								}
							}
						}
					}
				}
			}
		}
	}
}

$unit_koneksi = $wpdb->get_results(
	$wpdb->prepare("
		SELECT 
			nama_skpd, 
			id_skpd, 
			kode_skpd, 
			nipkepala,
			tahun_anggaran 
		FROM esakip_data_unit 
		WHERE active=1 
			AND is_skpd=1 
			AND tahun_anggaran=%d
		GROUP BY id_skpd
		ORDER BY kode_skpd ASC
	", $tahun_anggaran_sakip),
	ARRAY_A
);

$option_skpd = "";
if (!empty($unit_koneksi)) {
	foreach ($unit_koneksi as $v_unit) {
		$option_skpd .= "<option value='" . $v_unit['id_skpd'] . "'>" . $v_unit['nama_skpd'] . "</option>";
	}
}

$lembaga = $wpdb->get_results(
	$wpdb->prepare("
		SELECT *
		FROM esakip_data_lembaga_lainnya 
		WHERE active=1 
		  AND tahun_anggaran=%d
	", $tahun_anggaran_sakip),
	ARRAY_A
);
$option_lembaga = "";
if (!empty($lembaga)) {
	foreach ($lembaga as $v) {
		$option_lembaga .= "<option value='" . $v['id'] . "'>" . $v['nama_lembaga'] . "</option>";
	}
}

$api_params = array(
	'action' => 'get_pemdes_alamat_all',
	'api_key'	=> get_option('_crb_apikey_wpsipd'),
	'tahun_anggaran' => $tahun_anggaran_sakip
);
$response = wp_remote_post(get_option('_crb_url_server_sakip'), array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));
$response = wp_remote_retrieve_body($response);
$data_desa = json_decode($response, true);

$desa_opsi = '';
if ($data_desa['status'] == 'success') {
	if ($data_desa['tipe'] == 1) {
		foreach ($data_desa['data'] as $kab) {
			foreach ($kab['data'] as $kec) {
				foreach ($kec['desa'] as $desa) {
					$desa .= "<option value='" . $kab['id_kab'] . "-" . $kec['id_kec'] . "-" . $desa['id_kel'] . "'>" . $desa['desa'] . ' Kecamatan ' . $kec['kecamatan'] . ' ' . $kab['kabkot'] . '</option>';
				}
			}
		}
	} else if ($data_desa['tipe'] == 2) {
		foreach ($data_desa['data'] as $kec) {
			foreach ($kec['desa'] as $desa) {
				$desa_opsi .= "<option value='" . $kec['id_kec'] . "-" . $desa['id_kel'] . "'>" . $desa['desa'] . ' Kecamatan ' . $kec['kecamatan'] . '</option>';
			}
		}
	}
}

$uptd = $wpdb->get_results(
	$wpdb->prepare("
		SELECT 
			nama_skpd, 
			id_skpd, 
			kode_skpd, 
			nipkepala,
			tahun_anggaran 
		FROM esakip_data_unit 
		WHERE active=1 
			AND is_skpd=0 
			AND tahun_anggaran=%d
		GROUP BY id_skpd
		ORDER BY kode_skpd ASC
	", $tahun_anggaran_sakip),
	ARRAY_A
);
$option_uptd = "";
if (!empty($uptd)) {
	foreach ($uptd as $v) {
		$option_uptd .= "<option value='" . $v['id_skpd'] . "'>" . $v['nama_skpd'] . "</option>";
	}
}

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
?>

<style type="text/css">
	.level1 {
		background: #efd655;
		text-decoration: underline;
	}

	.level2 {
		background: #fe7373;
	}

	.level3 {
		background: #57b2ec;
	}

	.level4 {
		background: #c979e3;
	}

	.level5 {
		background: #CAFFBF;
	}

	.koneksi {
		background: #CAFFBF;
	}

	.indikator-koneksi {
		background: #b5d9ea;
	}

	.indikator {
		background: #b5d9ea;
	}

	.penyusunan_pohon_kinerja thead {
		position: sticky;
		top: -6px;
		background: #ffc491;
	}

	#modal-pokin .modal-body {
		max-height: 90vh;
		overflow-y: auto;
	}

	.table-responsive {
		display: block;
		width: 100%;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
	}

	.table {
		width: 100%;
		max-width: 100%;
		margin-bottom: 1rem;
		background-color: transparent;
	}

	.label-koneksi-pokin {
		padding: 0 .7em .7em;
	}

	.koneksi-pokin-1 {
		margin: 0px -16px 0px;
		padding: .5em .9em;
		border-width: 1px 0 0;
		border-style: solid;
		border-color: gray;
	}

	.detail-koneksi-pokin .dashicons {
		text-decoration: none;
		vertical-align: text-bottom !important;
		font-size: 23px !important;
	}

	/* sidebar */
	.sidebar-modal {
		position: fixed;
		top: 0;
		right: 0;
		width: 600px;
		height: 100%;
		z-index: 1050;
		background-color: #fff;
		box-shadow: -5px 0px 15px rgba(0, 0, 0, 0.15);
		transform: translateX(100%);
		transition: transform 0.3s ease-in-out;
	}

	.sidebar-modal.show {
		transform: translateX(0);
	}

	.sidebar-backdrop {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.4);
		z-index: 1040;
		opacity: 0;
		visibility: hidden;
		transition: opacity 0.3s ease-in-out, visibility 0.3s;
	}

	.sidebar-backdrop.show {
		opacity: 1;
		visibility: visible;
	}

	/* ================================ */

	.sidebar-header {
		background-color: #57b2ec;
		color: white;
		padding: 1rem 1.25rem;
	}

	.sidebar-header .close-btn {
		color: white;
		opacity: 0.9;
		font-size: 1.5rem;
		background: none;
		border: none;
	}

	.sidebar-header .close-btn:hover {
		opacity: 1;
	}

	.sidebar-body {
		padding: 1.5rem;
		overflow-y: auto;
		height: calc(100% - 62px);
	}

	.info-section {
		margin-bottom: 1.75rem;
	}

	.info-section h6 {
		font-weight: 600;
		font-size: 0.9rem;
		color: #555;
		margin-bottom: 0.5rem;
	}

	.info-section h6 i {
		margin-right: 10px;
		color: #6c757d;
		width: 20px;
		text-align: center;
	}

	.info-section p {
		font-size: 1rem;
		color: #333;
		margin-left: 30px;
		margin-bottom: 0;
	}
</style>
<h3 style="text-align: center; margin-top: 10px; font-weight: bold;">Penyusunan Pohon Kinerja<br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h3><br>
<div style="text-align: center;">
	<label class="mr-2">
		<input type="checkbox" id="check-tampilkan-detail">
		Tampilkan Detail Pokin Perangkat Daerah
	</label>
	<label class="mr-2">
		<input type="checkbox" id="check-tampilkan-cc">
		Tampilkan Crosscutting
	</label>
</div>
<?php if (!$is_admin_panrb): ?>
	<div id="action" class="action-section"></div>
<?php endif; ?>

<div class="sidebar-modal" id="rincianSidebar">
	<div class="sidebar-header d-flex justify-content-between align-items-center">
		<h5 class="mb-0 text-light">Rincian</h5>
		<button type="button" class="close-btn" onclick="toggleSidebar()">
			<span>&times;</span>
		</button>
	</div>

	<div class="sidebar-body">
		<div class="info-section text-left">
			<h6><i class="dashicons dashicons-tag"></i> Kinerja</h6>
			<p id="label"></p>
		</div>

		<div class="info-section text-left">
			<h6><i class="dashicons dashicons-chart-bar"></i> Indikator Kinerja</h6>
			<p id="indikator"></p>
		</div>

		<div class="info-section text-left">
			<h6><i class="dashicons dashicons-admin-users"></i> Pelaksana</h6>
			<p id="pelaksana"></p>
		</div>

		<div class="info-section text-left">
			<h6><i class="dashicons dashicons-clipboard"></i> Bentuk Kegiatan</h6>
			<p id="bentuk_kegiatan"></p>
		</div>

		<div class="info-section text-left">
			<h6><i class="dashicons dashicons-chart-line"></i> Outcome</h6>
			<p id="outcome"></p>
		</div>

		<div class="info-section text-left">
			<h6><i class="dashicons dashicons-groups"></i> Crosscutting Dengan</h6>
			<p id="crosscutting"></p>
		</div>
	</div>
</div>

<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

<div style="padding: 5px; overflow: auto; height: 100vh;">
	<table id="cetak" title="Penyusunan Pohon Kinerja Pemerintah Daerah" class="table table-bordered penyusunan_pohon_kinerja">
		<thead style="background: #ffc491;">
			<tr>
				<th style="min-width: 200px;" class="text-center">Level 1</th>
				<th style="min-width: 200px;" class="text-center">Indikator Kinerja</th>
				<th style="min-width: 200px;" class="text-center">Level 2</th>
				<th style="min-width: 200px;" class="text-center">Indikator Kinerja</th>
				<th style="min-width: 200px;" class="text-center">Level 3</th>
				<th style="min-width: 200px;" class="text-center">Indikator Kinerja</th>
				<th style="min-width: 200px;" class="text-center">Level 4</th>
				<th style="min-width: 200px;" class="text-center">Indikator Kinerja</th>
				<th style="min-width: 200px;" class="text-center hide-crosscutting">Crosscutting</th>
				<th style="min-width: 200px;" class="text-center hide-crosscutting">Pokin OPD</th>
				<th style="min-width: 200px;" class="text-center hide-crosscutting">Indikator Kinerja</th>
				<th style="min-width: 200px;" class="text-center detail-pokin hide-crosscutting">Pokin OPD</th>
				<th style="min-width: 200px;" class="text-center detail-pokin hide-crosscutting">Indikator Kinerja</th>
				<th style="min-width: 200px;" class="text-center detail-pokin hide-crosscutting">Pokin OPD</th>
				<th style="min-width: 200px;" class="text-center detail-pokin hide-crosscutting">Indikator Kinerja</th>
				<th style="min-width: 200px;" class="text-center detail-pokin hide-crosscutting">Pokin OPD</th>
				<th style="min-width: 200px;" class="text-center detail-pokin hide-crosscutting">Indikator Kinerja</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $html; ?>
		</tbody>
	</table>
</div>

<div class="hide-print container mt-4 p-4 mb-4 border rounded bg-light">
	<h4 class="font-weight-bold mb-3 text-dark">💡 Petunjuk Penggunaan Modul Pohon Kinerja</h4>
	<p class="text-muted">Berikut adalah panduan dan beberapa catatan penting dalam penggunaan modul Pohon Kinerja (Pokin):</p>
	<ul class="pl-3">
		<li class="mb-2">
			Visualisasi Pohon Kinerja dapat diakses melalui <strong class="text-primary">"tautan teks pada tiap level 1"</strong>, dengan syarat pengisian data kinerja telah mencapai minimal <strong>Level 2</strong>.
		</li>
		<li class="mb-2">
			Pohon Kinerja tersusun atas <strong>4 level hierarki</strong>. Setiap level dapat memiliki lebih dari satu indikator kinerja yang relevan.
		</li>
		<li class="mb-2">
			Setiap level pada Pohon Kinerja dapat ditambahkan dependensi atau keterkaitan dengan unit kerja lain melalui fitur <strong><em>Crosscutting</em></strong>.
			<ul class="mt-2 pl-4">
				<li><strong>Apa itu <em>Crosscutting</em>?</strong> <em>Crosscutting</em> adalah mekanisme untuk menghubungkan Pohon Kinerja dari satu unit kerja ke unit kerja lain yang relevan, untuk mendukung pencapaian tujuan bersama.</li>
				<li>Penambahan <em>Crosscutting</em> dilakukan setelah Pokin dibuat, yaitu dengan menekan tombol <strong>Edit</strong> pada data Pokin yang ingin dihubungkan.</li>
			</ul>
		</li>
		<li class="mb-2">
			Terdapat dua jenis mekanisme persetujuan untuk <em>Crosscutting</em>:
			<ul class="mt-2 pl-4">
				<li><strong>Memerlukan Persetujuan:</strong> Diterapkan apabila unit kerja yang dituju merupakan pengguna aktif dalam aplikasi. Status <em>crosscutting</em> akan menunggu validasi dari user terkait di unit kerja tersebut.</li>
				<li><strong>Otomatis Disetujui:</strong> Diterapkan untuk unit kerja yang tidak terdaftar sebagai pengguna dalam sistem. Status <em>crosscutting</em> akan secara otomatis disetujui oleh sistem.</li>
			</ul>
		</li>
		<li>
			Untuk menampilkan detail Pohon Kinerja dari hasil <em>crosscutting</em> (termasuk seluruh turunan kinerjanya) aktifkan kotak centang <strong class="text-primary">"Tampilkan Detail Pokin Perangkat Daerah"</strong>.
		</li>
		<li class="mb-2">
			Visualisasi Pohon Kinerja hasil <em>crosscutting</em> dapat diakses melalui <strong class="text-primary">"tautan teks pada tiap <em>crosscutting</em>"</strong>, dengan syarat <strong><em>crosscutting</em> telah disetujui</strong>.
		</li>
	</ul>
</div>

<div class="modal fade" id="modal-pokin" role="dialog" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header bgpanel-theme">
				<h4 style="margin: 0;" class="modal-title">Data Pohon Kinerja</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span><i class="dashicons dashicons-dismiss"></i></span></button>
			</div>
			<div class="modal-body">
				<nav>
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<a class="nav-item nav-link" id="nav-level-1-tab" data-toggle="tab" href="#nav-level-1" role="tab" aria-controls="nav-level-1" aria-selected="false">Level 1</a>
						<a class="nav-item nav-link" id="nav-level-2-tab" data-toggle="tab" href="#nav-level-2" role="tab" aria-controls="nav-level-2" aria-selected="false">Level 2</a>
						<a class="nav-item nav-link" id="nav-level-3-tab" data-toggle="tab" href="#nav-level-3" role="tab" aria-controls="nav-level-3" aria-selected="false">Level 3</a>
						<a class="nav-item nav-link" id="nav-level-4-tab" data-toggle="tab" href="#nav-level-4" role="tab" aria-controls="nav-level-4" aria-selected="false">Level 4</a>
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					<div class="tab-pane fade show active" id="nav-level-1" role="tabpanel" aria-labelledby="nav-level-1-tab"></div>
					<div class="tab-pane fade" id="nav-level-2" role="tabpanel" aria-labelledby="nav-level-2-tab">
						<div class="text-center text-muted text-sm m-3">Mohon pilih Pohon Kinerja level 1 terlebih dahulu.</div>
					</div>
					<div class="tab-pane fade" id="nav-level-3" role="tabpanel" aria-labelledby="nav-level-3-tab">
						<div class="text-center text-muted text-sm m-3">Mohon pilih Pohon Kinerja level 2 terlebih dahulu.</div>
					</div>
					<div class="tab-pane fade" id="nav-level-4" role="tabpanel" aria-labelledby="nav-level-4-tab">
						<div class="text-center text-muted text-sm m-3">Mohon pilih Pohon Kinerja level 3 terlebih dahulu.</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal crud -->
<div class="modal fade" id="modal-crud" data-backdrop="static" role="dialog" aria-labelledby="modal-crud-label" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>

<!-- Modal crud koneksi -->
<div class="modal fade" id="modal-koneksi" data-backdrop="static" role="dialog" aria-labelledby="modal-koneksi-label" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		run_download_excel_sakip();
		jQuery('#action-sakip').prepend('<div onclick="getDataPokinByLevelAndParent(1,0);" class="btn btn-primary mr-2"><i class="dashicons dashicons-plus"></i> Tambah Data</div>');

		// check view crosscutting
		jQuery('#check-tampilkan-cc').on('change', function() {
			var isMasterChecked = jQuery(this).prop('checked');

			jQuery('.hide-crosscutting').toggle(isMasterChecked);

			if (!isMasterChecked) {
				if (jQuery('#check-tampilkan-cc').prop('checked')) {
					jQuery('#check-tampilkan-cc').prop('checked', false).trigger('change');
				}
			} else if (!jQuery('#check-tampilkan-cc').prop('checked') && isMasterChecked) {
				jQuery('.detail-pokin').hide();
			}
		});

		//check view detail pokin opd
		jQuery('#check-tampilkan-detail').on('change', function() {
			var isDetailChecked = jQuery(this).prop('checked');

			jQuery('.detail-pokin').toggle(isDetailChecked);

			if (isDetailChecked) {
				if (!jQuery('#check-tampilkan-detail').prop('checked')) {
					jQuery('#check-tampilkan-detail').prop('checked', true).trigger('change');
				}
			}
		});

		jQuery('#check-tampilkan-cc').trigger('change');
		jQuery('#check-tampilkan-detail').trigger('change');
	});

	function toggleSidebar() {
		jQuery('#rincianSidebar').toggleClass('show');
		jQuery('#sidebarBackdrop').toggleClass('show');
	}

	function handleDetailPokin(idPokin) {
		jQuery.ajax({
			method: 'POST',
			url: esakip.url,
			data: {
				"action": "edit_pokin",
				"api_key": esakip.api_key,
				'id': idPokin
			},
			dataType: 'json',
			success: function(response) {
				if (!response.status) {
					alert(response.message);
					return;
				}
				jQuery("#label").text(response.data.label || '-');

				if (response.indikator && response.indikator.length > 0) {
					jQuery("#indikator").html(
						response.indikator.join(', <br> '));
				} else {
					jQuery("#indikator").text('-');
				}

				jQuery("#pelaksana").text(response.data.pelaksana || '-');
				jQuery("#bentuk_kegiatan").text(response.data.bentuk_kegiatan || '-');
				jQuery("#outcome").text(response.data.outcome || '-');
				
				if (!response.data_koneksi_croscutting_pemda || response.data_koneksi_croscutting_pemda.length === 0) {
					jQuery("#crosscutting").text('-');
				} else {
					jQuery("#crosscutting").html(response.data_koneksi_croscutting_pemda);
				}

				toggleSidebar();
			},
			error: function(e) {
				jQuery("#wrap-loading").hide();
				alert(e.responseJSON.message || 'Terjadi kesalahan saat mengambil data Pohon Kinerja.');
			}
		});
	}

	function handleDeletePokin(id, parentId, level) {
		if (confirm(`Data Pohon Kinerja akan dihapus?`)) {
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method: 'POST',
				url: esakip.url,
				data: {
					'action': 'delete_pokin',
					'api_key': esakip.api_key,
					'id': id
				},
				dataType: 'json',
				success: function(response) {
					if (response.status) {
						getDataPokinByLevelAndParent(level, parentId).then(function() {
							alert(response.message);
							jQuery("#wrap-loading").hide();
						});
					} else {
						alert(response.message);
						jQuery('#wrap-loading').hide();
					}
				}
			})
		}
	}

	function handleDeleteKoneksiPokin(idKoneksi, idPokin, idParentPokin, level) {
		if (confirm(`Data Koneksi Pokin akan dihapus?`)) {
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method: 'POST',
				url: esakip.url,
				data: {
					'action': 'delete_koneksi_pokin',
					'api_key': esakip.api_key,
					'id': idKoneksi,
				},
				dataType: 'json',
				success: function(response) {
					handleFormEditPokin(idPokin).then(() => {
						getDataPokinByLevelAndParent(level, idParentPokin).then(function() {
							alert(response.message);
							jQuery('#wrap-loading').hide();
						});
					})
				}
			})
		}
	}

	function handleCreateKoneksiPokin(idPokin, level, idParentPokin) {
		jQuery('#wrap-loading').show();
		let form = getFormData(jQuery("#form-koneksi"));
		form.nama_desa = jQuery('#skpdKoneksiDesa option:selected').toArray().map(item => item.text);

		jQuery.ajax({
			method: 'POST',
			url: esakip.url,
			dataType: 'JSON',
			data: {
				'action': 'create_koneksi_pokin',
				'api_key': esakip.api_key,
				'data': JSON.stringify(form),
			},
			success: function(response) {
				if (response.status) {
					handleFormEditPokin(idPokin).then(() => {
						alert(response.message);
						getDataPokinByLevelAndParent(level, idParentPokin).then(() => {
							jQuery('#wrap-loading').hide();
							jQuery("#modal-koneksi").modal('hide');
						})
					})
				} else {
					alert(response.message);
					jQuery('#wrap-loading').hide();
				}
			}
		})
	}

	function handleUpdateKoneksiPokin(idPokin) {
		jQuery('#wrap-loading').show();
		let form = getFormData(jQuery("#form-koneksi"));

		jQuery.ajax({
			method: 'POST',
			url: esakip.url,
			dataType: 'JSON',
			data: {
				'action': 'update_koneksi_pokin',
				'api_key': esakip.api_key,
				'data': JSON.stringify(form),
			},
			success: function(response) {
				if (response.status) {
					handleFormEditPokin(idPokin).then(() => {
						alert(response.message);
						jQuery('#wrap-loading').hide();
						jQuery("#modal-koneksi").modal('hide');
					})
				} else {
					alert(response.message);
					jQuery('#wrap-loading').hide();
				}
			}
		})
	}

	function handleFormEditKoneksiPokin(idKoneksi, idPokin, that) {
		let $row = jQuery(that).closest('tr');
		let $tds = $row.find('td');

		let namaPD = $tds.eq(1).text().trim();
		let informasiKegiatan = $tds.eq(3).text().trim();
		jQuery("#modal-koneksi").find('.modal-title').html('Edit Crosscutting Pohon Kinerja');
		jQuery("#modal-koneksi").find('.modal-body').html(`
			<form id="form-koneksi">
				<input type="hidden" name="id" value="${idKoneksi}">
				<div class="form-group">
					<label for="opd">Nama PD/UPT/Lembaga/Desa</label>
					<input type="text" class="form-control" name="opd" id="opd-koneksi" value="${namaPD}" disabled>
				</div>
				<div class="form-group">
					<label for="informasi_kegiatan">Informasi kegiatan</label>
					<textarea class="form-control" name="informasi_kegiatan" id="informasi_kegiatan">${informasiKegiatan}</textarea>
				</div>
			</form>`);
		jQuery("#modal-koneksi").find(`.modal-footer`).html(`
			<button type="button" class="btn btn-secondary" data-dismiss="modal">
				Tutup
			</button>
			<button type="button" class="btn btn-success" onclick="handleUpdateKoneksiPokin(${idPokin})">
				Update
			</button>`);
		jQuery("#modal-koneksi").modal('show');
	}

	function generateFormPokin(idPokin, data_koneksi, response) {
		return `
		<input type="hidden" name="id" value="${response.data.id}">
		<input type="hidden" name="parent" value="${response.data.parent}">
		<input type="hidden" name="level" value="${response.data.level}">
		<div class="form-group">
            <label for="label-pokin">Uraian Pohon Kinerja</label>
			<textarea class="form-control" id="label-pokin" name="label">${response.data.label}</textarea>
		</div>
        <div class="form-group">
            <label>Nomor Urut</label>
            <input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">
        </div>
        <div class="form-group">
            <label for="pelaku">Pelaksana</label>
            <input type="text" class="form-control" name="pelaku" id="pelaku" value="${response.data.pelaksana ?? ''}">
        </div>
        <div class="form-group">
            <label for="bentuk_kegiatan">Bentuk Kegiatan</label>
            <textarea class="form-control" name="bentuk_kegiatan" id="bentuk_kegiatan">${response.data.bentuk_kegiatan ?? ''}</textarea>
        </div>
        <div class="form-group">
            <label for="outcome">Outcome</label>
            <textarea class="form-control" name="outcome" id="outcome">${response.data.outcome ?? ''}</textarea>
        </div>
		<div class="mt-2">
			<button type="button" class="btn btn-success mb-2 mt-2" onclick="handleFormKoneksiPokin(${idPokin}, ${response.data.level}, ${response.data.parent})">
				<i class="dashicons dashicons-plus"></i>Tambah Crosscutting / Pelaksana Kegiatan
			</button>
		</div>
		<div class="wrap-table">
			<table id="table_koneksi_pokin" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center">No</th>
						<th class="text-center">PD/UPT/Lembaga/Desa</th>
						<th class="text-center">Status</th>
						<th class="text-center">Informasi Kegiatan</th>
						<th class="text-center" style="width: 100px;">Aksi</th>
					</tr>
				</thead>
				<tbody>${data_koneksi}</tbody>
			</table>
			<small class="text-muted">
				<ul>
					<li>Jika akan membatalkan koneksi pokin, pastikan perangkat daerah terkait membatalkan koneksi pokin terlebih dahulu!</li>
					<li>Koneksi Pokin terhadap <strong>Desa</strong> atau <strong>Lembaga Lainnya</strong> langsung berstatus <strong>Disetujui</strong>, dan langsung terhapus jika dihapus koneksi</li>
				</ul>
			</small>
		</div>`;
	}

	function handleFormCreateIndikatorPokin(namaPokin, idPokin, level, lastUrutan, idParentPokin) {
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
		jQuery("#modal-crud").find('.modal-body').html(`
			<form id="form-pokin">
				<input type="hidden" name="parent" value="${idPokin}">
				<input type="hidden" name="label" value="${namaPokin}">
				<input type="hidden" name="level" value="${level}">
				<div class="form-group">
					<label for="indikator-label">${namaPokin}</label>
					<textarea class="form-control" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>
				</div>
                <div class="form-group">
                    <label>Nomor Urut</label>
                    <input type="number" class="form-control" name="nomor_urut" value="${lastUrutan+1}">
                </div>
			</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(`
			<button type="button" class="btn btn-secondary" data-dismiss="modal">
				Tutup
			</button>
			<button type="button" class="btn btn-success" onclick="handleCreateIndikatorPokin(${level}, ${idParentPokin})">
				Simpan
			</button>
		`);
		jQuery("#modal-crud").modal('show');
	}

	function handleFormEditIndikatorPokin(idIndikator, idParentPokin) {
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method: 'POST',
			url: esakip.url,
			data: {
				"action": "edit_indikator_pokin",
				"api_key": esakip.api_key,
				'id': idIndikator
			},
			dataType: 'json',
			success: function(response) {
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(`
					<form id="form-pokin">
						<input type="hidden" name="id" value="${response.data.id}">
						<input type="hidden" name="parent" value="${response.data.parent}">
						<input type="hidden" name="level" value="${response.data.level}">
						<div class="form-group">
							<label for="indikator-label">${response.data.label ? response.data.label : 'Label Indikator'}</label>
							<textarea class="form-control" name="indikator_label">${response.data.label_indikator_kinerja}</textarea>
						</div>
                        <div class="form-group">
                            <label>Nomor Urut</label>
                            <input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">
                        </div>
					</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(`
					<button type="button" class="btn btn-secondary" data-dismiss="modal">">
						Tutup
					</button>
					<button type="button" class="btn btn-success" onclick="handleUpdateIndikatorPokin(${response.data.level}, ${idParentPokin})">
						Update
					</button>`);
				jQuery("#modal-crud").modal('show');
			}
		});
	}

	function handleFormKoneksiPokin(idPokin, level, idParentPokin) {
		jQuery("#modal-koneksi").find('.modal-title').html('Tambah Crosscutting / Pelaksana Kegiatan');
		jQuery("#modal-koneksi").find('.modal-body').html(`
			<form id="form-koneksi">				
				<input type="hidden" name="parentKoneksi" value="${idPokin}">
				<div class="form-group">
					<label for="skpdKoneksi">Pilih Perangkat Daerah</label>
					<select class="form-control" name="skpdKoneksi[]" multiple="multiple" id="skpdKoneksi">
					<?php echo $option_skpd; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="skpdKoneksiLainnya">Pilih Lembaga Lainnya</label>
					<select class="form-control" name="skpdKoneksiLainnya[]" multiple="multiple" id="skpdKoneksiLainnya">
					<?php echo $option_lembaga; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="skpdKoneksiUptd">Pilih UPTD / Sub Unit</label>
					<select class="form-control" name="skpdKoneksiUptd[]" multiple="multiple" id="skpdKoneksiUptd">
					<?php echo $option_uptd; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="skpdKoneksiDesa">Pilih Desa / Kelurahan</label>
					<select class="form-control" name="skpdKoneksiDesa[]" multiple="multiple" id="skpdKoneksiDesa">
					<?php echo $desa_opsi; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="keterangan_koneksi">Informasi kegiatan</label>
					<textarea class="form-control" name="keterangan_koneksi"></textarea>
				</div>
			</form>`);
		jQuery("#modal-koneksi").find('.modal-footer').html(`
			<button type="button" class="btn btn-secondary" data-dismiss="modal">
				Tutup
			</button>
			<button type="button" class="btn btn-success" onclick="handleCreateKoneksiPokin(${idPokin}, ${level}, ${idParentPokin})">
				Simpan
			</button>`);
		jQuery('#skpdKoneksi').select2({
			dropdownParent: jQuery('#skpdKoneksi').closest('.modal'),
			width: '100%'
		});
		jQuery('#skpdKoneksiLainnya').select2({
			dropdownParent: jQuery('#skpdKoneksiLainnya').closest('.modal'),
			width: '100%'
		});
		jQuery('#skpdKoneksiUptd').select2({
			dropdownParent: jQuery('#skpdKoneksiUptd').closest('.modal'),
			width: '100%'
		});
		jQuery('#skpdKoneksiDesa').select2({
			dropdownParent: jQuery('#skpdKoneksiDesa').closest('.modal'),
			width: '100%'
		});
		getSkpdByParentPokin(idPokin).then(function() {
			jQuery("#modal-koneksi").modal('show');
			jQuery("#wrap-loading").hide();
		});
	}

	function handleDeleteIndikatorPokin(idIndikator, level, idParentPokin) {
		if (confirm(`Data akan dihapus?`)) {
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method: 'POST',
				url: esakip.url,
				data: {
					'action': 'delete_indikator_pokin',
					'api_key': esakip.api_key,
					'id': idIndikator
				},
				dataType: 'json',
				success: function(response) {
					if (response.status) {
						getDataPokinByLevelAndParent(level, idParentPokin).then(function() {
							alert(response.message);
							jQuery("#wrap-loading").hide();
						});
					} else {
						alert(response.message);
						jQuery('#wrap-loading').hide();
					}
				}
			})
		}
	};

	function getSkpdByParentPokin(idParentPokin) {
		jQuery("#wrap-loading").show();
		return new Promise(function(resolve, reject) {
			jQuery.ajax({
				method: 'POST',
				url: esakip.url,
				data: {
					'action': 'get_skpd_koneksi_pokin_by_id',
					'api_key': esakip.api_key,
					'id_parent_pokin': idParentPokin,
				},
				dataType: 'json',
				success: function(response) {
					response.data.map(function(value, index) {
						jQuery('#skpdKoneksi option[value="' + value.id_skpd_koneksi + '"]').attr('disabled', true).trigger('change');
						jQuery("#wrap-loading").hide();
					})
					resolve();
				}
			})
		});
	};

	function getDataPokinByLevelAndParent(level, idParent) {
		jQuery("#wrap-loading").show();
		return new Promise(function(resolve, reject) {
			jQuery.ajax({
				url: esakip.url,
				type: "POST",
				data: {
					"action": "get_data_pokin",
					"level": level,
					"parent": idParent,
					"id_jadwal": '<?php echo $input['periode']; ?>',
					"api_key": esakip.api_key
				},
				dataType: "JSON",
				success: function(res) {
					jQuery('#wrap-loading').hide();
					var nomor_urut = 0;
					if (res.data.length >= 1) {
						nomor_urut = Math.floor(res.data[res.data.length - 1].nomor_urut);
					}

					let html = '';
					if (level != 1) {
						html += `
						<div class="card bg-light shadow-md m-3 p-2">
							<table class="borderless-table">
								<tbody>`;

						res.parent.map(function(value, index) {
							if (value != null) {
								html += ` 
									<tr>
										<td class="text-left" style="width: 150px;">
											<strong>Level ${(index+1)}</strong>
										</td>
										<td class="text-center" style="width: 20px;">
											<strong>:</strong>
										</td>
										<td class="text-left text-muted">
											${value}
										</td>
									</tr>`;
							}
						});

						html += `
								</tbody>
							</table>
						</div>
						<div class="my-3">
							<button type="button" class="btn btn-success" onclick="handleFormCreatePokin(${level}, ${idParent}, ${nomor_urut})">
								<i class="dashicons dashicons-plus"></i> Tambah Data
							</button>
						</div>`;
					} else {
						html += `
							<div class="my-3">
								<button type="button" class="btn btn-success" onclick="handleFormCreatePokin(${level}, ${idParent}, ${nomor_urut})">
									<i class="dashicons dashicons-plus"></i> Tambah Data
								</button>
							</div>
						`;
					}

					html += `
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead class="thead-light">
								<tr class="text-center">
									<th style="width:50px">No</th>
									<th>Uraian Pohon Kinerja</th>
									<th style="width:200px">Pelaksana</th>
									<th style="width:300px">Crosscutting</th>
									<th style="width:190px">Aksi</th>
								</tr>
							</thead>
							<tbody>`;

					if (res.data.length != 0) {
						res.data.map(function(value, index) {
							let indikator = Object.values(value.indikator);
							var last_urutan = 0;
							if (indikator.length > 0) {
								last_urutan = Math.floor(indikator[indikator.length - 1].nomor_urut);
							}

							html += `
							<tr class="bg-light">
								<td class="text-center align-middle font-weight-bold">${index + 1}.</td>
								<td class="align-middle font-weight-bold">${value.label}</td>
								<td class="align-middle">${value.pelaksana}</td>
								<td class="align-top" rowspan="${indikator.length + 1}">
									<ul class="mb-0 pl-3">
										${value.crosscutting.map(item => `<li>${item}</li>`).join('')}
									</ul>
								</td>
								<td class="text-center align-middle">
									<div class="btn-group d-flex flex-wrap justify-content-center">
										<button class="btn btn-sm btn-success mr-1 mb-1" title="Tambah Indikator" onclick="handleFormCreateIndikatorPokin('${value.label}', ${value.id}, ${level}, ${last_urutan}, ${idParent})">
											<i class="dashicons dashicons-plus"></i>
										</button>`;

							if (level != 4) {
								html += `
										<button class="btn btn-sm btn-warning mr-1 mb-1" title="Lihat pohon kinerja level ${level + 1}" onclick="getDataPokinByLevelAndParent(${level + 1}, ${value.id})">
											<i class="dashicons dashicons-menu-alt"></i>
										</button>`;
							}
							html += `
										<button class="btn btn-sm btn-primary mr-1 mb-1" title="Edit" onclick="handleFormEditPokin(${value.id})">
											<i class="dashicons dashicons-edit"></i>
										</button>
										<button class="btn btn-sm btn-danger mb-1" title="Hapus" onclick="handleDeletePokin(${value.id}, ${idParent}, ${level})">
											<i class="dashicons dashicons-trash"></i>
										</button>
									</div>
								</td>
							</tr>`;

							if (indikator.length > 0) {
								indikator.map(function(indikator_value, indikator_index) {
									html += `
									<tr>
										<td></td>
										<td colspan="2" class="text-left text-muted">
											${index + 1}.${indikator_index + 1} ${indikator_value.label}
										</td>
										<td class="text-center">
											<div class="btn-group justify-content-center">
												<button class="btn btn-sm btn-primary mr-1" title="Edit Indikator" onclick="handleFormEditIndikatorPokin(${indikator_value.id}, ${idParent})">
													<i class="dashicons dashicons-edit"></i>
												</button>
												<button class="btn btn-sm btn-danger" title="Hapus Indikator" onclick="handleDeleteIndikatorPokin(${indikator_value.id}, ${level}, ${idParent})">
													<i class="dashicons dashicons-trash"></i>
												</button>
											</div>
										</td>
									</tr>`;
								});
							}
						});
					} else {
						html += `
						<tr>
							<td colspan="5" class="text-center text-muted">
								Belum ada data Pohon Kinerja pada level ${level} ini.
							</td>
						</tr>`;
					}

					html += `
							</tbody>
						</table>
					</div>`;

					jQuery(`#nav-level-${level}`).html(html);
					jQuery(`.nav-tabs a[href="#nav-level-${level}"]`).tab('show');

					jQuery('#modal-pokin').modal('show');
					resolve();
				}

			});
		});
	}

	function handleFormCreatePokin(level, idParent, Urutan) {
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
		jQuery("#modal-crud").find('.modal-body').html(`
			<form id="form-pokin">				
				<input type="hidden" name="parent" value="${idParent}">
				<input type="hidden" name="level" value="${level}">
                <div class="form-group">
                    <label for="label-pokin">Uraian Pohon Kinerja</label>
                    <textarea class="form-control" id="label-pokin" name="label" placeholder="Tuliskan pohon kinerja level ${level}..."></textarea>
                </div>
                <div class="form-group">
                    <label>Nomor Urut</label>
                    <input type="number" class="form-control" name="nomor_urut" value="${Urutan + 1}">
                </div>
			</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(`
			<button type="button" class="btn btn-secondary" data-dismiss="modal">
				Tutup
			</button>
			<button type="button" class="btn btn-success" onclick="handleCreatePokin(${level}, ${idParent})">
				Simpan
			</button>`);
		jQuery("#modal-crud").modal('show');
	}

	function handleFormEditPokin(id) {
		jQuery("#wrap-loading").show();
		return new Promise(function(resolve, reject) {
			jQuery.ajax({
				method: 'POST',
				url: esakip.url,
				data: {
					"action": "edit_pokin",
					"api_key": esakip.api_key,
					'id': id
				},
				dataType: 'json',
				success: function(response) {
					jQuery("#wrap-loading").hide();
					let table_data_koneksi_pokin = '';
					if (response.data_koneksi_pokin == "" || response.data_koneksi_pokin == undefined) {
						table_data_koneksi_pokin = `
							<tr>
								<td colspan="5" class="text-center">Data tidak ditemukan!</td>
							</tr>`;
					} else {
						table_data_koneksi_pokin = response.data_koneksi_pokin;
					}
					jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
					jQuery("#modal-crud").find('.modal-body').html(`
						<form id="form-pokin">` +
						generateFormPokin(response.data.id, table_data_koneksi_pokin, response) + `
						</form>`
					);
					jQuery("#modal-crud").find(`.modal-footer`).html(`
						<button type="button" class="btn btn-secondary" data-dismiss="modal">
							Tutup
						</button>
						<button type="button" class="btn btn-success" onclick="handleUpdatePokin(${response.data.level},${response.data.parent})">
							Update
						</button>`);
					jQuery("#modal-crud").modal('show');
					resolve();
				}
			});
		});
	}

	function handleCreatePokin(level, idParent) {
		jQuery('#wrap-loading').show();
		let form = getFormData(jQuery("#form-pokin"));
		jQuery.ajax({
			method: 'POST',
			url: esakip.url,
			dataType: 'json',
			data: {
				'action': 'create_pokin',
				'api_key': esakip.api_key,
				'id_jadwal': <?php echo $input['periode']; ?>,
				'data': JSON.stringify(form),
			},
			success: function(response) {
				if (response.status) {
					getDataPokinByLevelAndParent(level, idParent).then(() => {
						alert(response.message);
						jQuery('#wrap-loading').hide();
						jQuery("#modal-crud").modal('hide');
					})
				} else {
					alert(response.message);
					jQuery('#wrap-loading').hide();
				}
			}
		})
	}

	function handleCreateIndikatorPokin(level, idParent) {
		jQuery('#wrap-loading').show();
		let form = getFormData(jQuery("#form-pokin"));
		jQuery.ajax({
			method: 'POST',
			url: esakip.url,
			dataType: 'json',
			data: {
				'action': 'create_indikator_pokin',
				'api_key': esakip.api_key,
				'id_jadwal': <?php echo $input['periode']; ?>,
				'data': JSON.stringify(form),
			},
			success: function(response) {
				if (response.status) {
					getDataPokinByLevelAndParent(level, idParent).then(() => {
						alert(response.message);
						jQuery('#wrap-loading').hide();
						jQuery("#modal-crud").modal('hide');
					})
				} else {
					alert(response.message);
					jQuery('#wrap-loading').hide();
				}
			}
		})
	}

	function handleUpdatePokin(level, idParent) {
		jQuery('#wrap-loading').show();
		let form = getFormData(jQuery("#form-pokin"));
		jQuery.ajax({
			method: 'POST',
			url: esakip.url,
			dataType: 'json',
			data: {
				'action': 'update_pokin',
				'api_key': esakip.api_key,
				'data': JSON.stringify(form),
			},
			success: function(response) {
				if (response.status) {
					getDataPokinByLevelAndParent(level, idParent).then(() => {
						alert(response.message);
						jQuery('#wrap-loading').hide();
						jQuery("#modal-crud").modal('hide');
					})
				} else {
					alert(response.message);
					jQuery('#wrap-loading').hide();
				}
			}
		})
	}

	function handleUpdateIndikatorPokin(level, idParent) {
		jQuery('#wrap-loading').show();
		let form = getFormData(jQuery("#form-pokin"));
		jQuery.ajax({
			method: 'POST',
			url: esakip.url,
			dataType: 'json',
			data: {
				'action': 'update_indikator_pokin',
				'api_key': esakip.api_key,
				'data': JSON.stringify(form),
			},
			success: function(response) {
				if (response.status) {
					getDataPokinByLevelAndParent(level, idParent).then(() => {
						alert(response.message);
						jQuery('#wrap-loading').hide();
						jQuery("#modal-crud").modal('hide');
					})
				} else {
					alert(response.message);
					jQuery('#wrap-loading').hide();
				}
			}
		})
	}

	function getFormData($form) {
		let unindexed_array = $form.serializeArray();
		let indexed_array = {};

		jQuery.map(unindexed_array, function(n, i) {
			var nama_baru = n['name'].split('[');
			if (nama_baru.length > 1) {
				nama_baru = nama_baru[0];
				if (!indexed_array[nama_baru]) {
					indexed_array[nama_baru] = [];
				}
				indexed_array[nama_baru].push(n['value']);
			} else {
				indexed_array[n['name']] = n['value'];
			}
		});

		return indexed_array;
	}
</script>