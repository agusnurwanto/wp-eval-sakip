<?php

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

global $wpdb;

$input = shortcode_atts(array(
	'periode' => '',
), $atts);
if (!empty($_GET) && !empty($_GET['id_jadwal'])) {
	$input['periode'] = $_GET['id_jadwal'];
}
$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);

$id_skpd = false;
$nama_skpd = '';
$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
	$id_skpd = $_GET['id_skpd'];
	$skpd = $wpdb->get_row(
		$wpdb->prepare("
	    SELECT 
	        nama_skpd
	    FROM esakip_data_unit
	    WHERE id_skpd=%d
	      AND tahun_anggaran=%d
	      AND active = 1
	", $id_skpd, $tahun_anggaran_sakip),
		ARRAY_A
	);
	$nama_skpd = $skpd['nama_skpd'] . '<br>';
}

$periode = $wpdb->get_row(
	$wpdb->prepare("
    SELECT 
		*
    FROM esakip_data_jadwal
    WHERE id=%d
", $input['periode']),
	ARRAY_A
);

if (!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1) {
	$tahun_periode = $periode['tahun_selesai_anggaran'];
} else {
	$tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

$table = 'esakip_pohon_kinerja';
$where_skpd = '';
if ($tipe == 'opd') {
	$table = 'esakip_pohon_kinerja_opd';
	$where_skpd = $wpdb->prepare('AND id_skpd=%d', $id_skpd);
}

$pohon_kinerja_level = $wpdb->get_var($wpdb->prepare("
	SELECT 
		level 
	FROM $table 
	WHERE id=%d
		AND active=1 
		AND id_jadwal=%d 
		$where_skpd
	ORDER BY nomor_urut
", $_GET['id'], $input['periode']));

$data_all = array('data' => $this->get_pokin(array(
	'id' => $_GET['id'],
	'level' => $pohon_kinerja_level,
	'periode' => $input['periode'],
	'tipe' => $tipe,
	'id_skpd' => $id_skpd
)));
// print_r($data_all); die();

$style0 = 'level0';
$style1 = 'class="level1"';
$style2 = 'class="level2"';
$style3 = 'class="level3"';
$style4 = 'class="level4"';
$style5 = 'class="level5"';

$new_view_pokin_page = $this->functions->generatePage(array(
	'nama_page' => 'Lihat Pohon Kinerja',
	'content' => '[new_view_pohon_kinerja]',
	'show_header' => 1,
	'post_status' => 'publish'
));

$pokin_pemda = $this->functions->generatePage(array(
	'nama_page' => 'Pohon Kinerja Pemerintah Daerah',
	'content' => '[view_pohon_kinerja]',
	'show_header' => 1,
	'post_status' => 'private'
));

$data_all_format_json = array();
$data_temp = array();
$show_nama_skpd = '';
if (!empty($data_all['data'])) {

	foreach ($data_all['data'] as $keylevel1 => $level_1) {
		$data_all_format_json[$level_1['id']] = $level_1;
		unset($data_all_format_json[$level_1['id']]['data']);

		$data_temp[$keylevel1][0] = (object)[
			'v' => $level_1['id'],
			'f' => "<div class=\"" . $style0 . " label1\" data-id=\"" . $level_1['id'] . "\">" . trim($level_1['label']) . "</div>",
		];

		if (!empty($level_1['indikator'])) {
			foreach ($level_1['indikator'] as $keyindikatorlevel1 => $indikator) {
				$data_temp[$keylevel1][0]->f .= '<div class="level1 item-rincian" data-id="' . $level_1['id'] . '">IK: ' . $indikator['label_indikator_kinerja'] . '</div>';
			}
		}

		// croscutting level 1
		if (!empty($level_1['croscutting'])) {
			$data_temp[$keylevel1][0]->f .= "<div class='croscutting-2 tampil_croscutting item-rincian' data-id='" . $level_1["id"] . "'>Crosscutting Dengan:</div>";
			foreach ($level_1['croscutting'] as $keyCross => $valCross) {
				$nama_skpd_all = array();
				$class_cc_opd_lain = '';
				$id_pokin_cc = $valCross['parent_croscutting'];
				if ($valCross['croscutting_opd_lain'] == 1) {
					$class_cc_opd_lain = 'cc-opd-lain';
					$id_pokin_cc = $valCross['parent_croscutting'];
				}

				$show_nama_skpd = $valCross['nama_skpd'];
				$label_parent = $valCross['label_parent'];

				$class_cc_vertikal = '';
				if ($valCross['is_lembaga_lainnya'] == 1) {
					$label_parent = $valCross['keterangan'];
					$class_cc_vertikal = "croscutting-lembaga-vertikal";
				}
				
				if ($valCross['is_lembaga_lainnya'] != 1) {
					$show_nama_skpd = $valCross['nama_skpd'] . "<br><a href='" . $new_view_pokin_page['url'] . "&tipe_koneksi=opd&id_koneksi_pokin=" . $valCross['id'] . "&id=" . $id_pokin_cc . "' target='_blank'>" . $label_parent . "</a>";
				} else {
					$show_nama_skpd = $valCross['nama_skpd'] . "<br>" . $label_parent;
				}

				$data_temp[$keylevel1][0]->f .= "
					<div class='croscutting tampil_croscutting item-rincian " . $class_cc_opd_lain . " " . $class_cc_vertikal . "' data-id='" . $level_1["id"] . "'>
						<div class='cros-opd'>
							" . $show_nama_skpd . "
							<a href='javascript:void(0)' data-id='" . $valCross['id'] . "' class='detail-cc' onclick='event.stopPropagation(); detail_cc(" . $valCross['id'] . "); return false;' title='Detail'>
								<i class='dashicons dashicons-info'></i>
							</a>
						</div>
					</div>
				";
			}
		}

		// data koneksi pokin
		if (!empty($level_1['koneksi_pokin'])) {
			if ($tipe == 'opd') {
				$data_temp[$keylevel1][0]->f .= "<div class='koneksi-pokin-2 tampil_koneksi_pokin item-rincian' data-id='" . $level_1["id"] . "'>Crosscutting " . strtoupper($nama_pemda) . ":</div>";
			} else {
				$data_temp[$keylevel1][0]->f .= "<div class='koneksi-pokin-2 tampil_koneksi_pokin item-rincian' data-id='" . $level_1["id"] . "'>Crosscutting Dengan:</div>";
			}
			foreach ($level_1['koneksi_pokin'] as $key_koneksi => $val_koneksi) {
				if ($tipe == 'opd') {
					$label_parent = $val_koneksi['label_parent'];
					if (!empty($val_koneksi['id_level_1_parent'])) {
						$show_nama_skpd = "<a href='" . $pokin_pemda['url'] . "&id=" . $val_koneksi['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . ucfirst($val_koneksi['label_parent']) . "</a>";
					}
				} else {
					if ($val_koneksi['tipe'] == 1) {
						$show_nama_skpd = $val_koneksi['nama_skpd'] . "<br><a href='" . $new_view_pokin_page['url'] . "&id=" . $val_koneksi['id_parent'] . "&tipe_koneksi=pemda&id_koneksi_pokin=" . $val_koneksi['id'] . "' target='_blank'>" . $val_koneksi['label_parent'] . "</a>";
					} else {
						$show_nama_skpd = "<span>" . $val_koneksi['nama_skpd'] . "<br>(" . $val_koneksi['label_parent'] . ")</span>";
					}
				}
				$data_temp[$keylevel1][0]->f .= "<div class='koneksi-pokin tampil_koneksi_pokin item-rincian' data-id='" . $level_1["id"] . "'><div class='cros-opd'>" . $show_nama_skpd . "</div></div>";
			}
		}

		if (!empty($level_1['data'])) {

			foreach ($level_1['data'] as $keylevel2 => $level_2) {
				$data_all_format_json[$level_2['id']] = $level_2;
				unset($data_all_format_json[$level_2['id']]['data']);

				$data_temp[$keylevel2][0] = (object)[
					'v' => $level_2['id'],
					'f' => "<div class=\"" . $style0 . " label2\" data-id=\"" . $level_2['id'] . "\">" . trim($level_2['label']) . "</div>",
				];

				if (!empty($level_2['indikator'])) {
					foreach ($level_2['indikator'] as $keyindikatorlevel2 => $indikator) {
						$data_temp[$keylevel2][0]->f .= '<div class="level2 item-rincian" data-id="' . $level_2['id'] . '">IK: ' . $indikator['label_indikator_kinerja'] . '</div>';
					}
				}

				// croscutting level 2
				if (!empty($level_2['croscutting'])) {
					$data_temp[$keylevel2][0]->f .= "<div class='croscutting-2 tampil_croscutting item-rincian' data-id='" . $level_2["id"] . "'>Crosscutting Dengan:</div>";
					foreach ($level_2['croscutting'] as $keyCross => $valCross) {
						$nama_skpd_all = array();
						$class_cc_opd_lain = '';
						$id_pokin_cc = $valCross['parent_croscutting'];
						if ($valCross['croscutting_opd_lain'] == 1) {
							$class_cc_opd_lain = 'cc-opd-lain';
							$id_pokin_cc = $valCross['parent_croscutting'];
						}

						$show_nama_skpd = $valCross['nama_skpd'];
						$label_parent = $valCross['label_parent'];
						$class_cc_vertikal = '';
						if ($valCross['is_lembaga_lainnya'] == 1) {
							$label_parent = $valCross['keterangan'];
							$class_cc_vertikal = "croscutting-lembaga-vertikal";
						}
						if ($valCross['is_lembaga_lainnya'] != 1) {
							$show_nama_skpd = $valCross['nama_skpd'] . "<br><a href='" . $new_view_pokin_page['url'] . "&tipe_koneksi=opd&id_koneksi_pokin=" . $valCross['id'] . "&id=" . $id_pokin_cc . "' target='_blank'>" . $label_parent . "</a>";
						} else {
							$show_nama_skpd = $valCross['nama_skpd'] . "<br>" . $label_parent;
						}

						$data_temp[$keylevel2][0]->f .= "
							<div class='croscutting tampil_croscutting  item-rincian " . $class_cc_opd_lain . " " . $class_cc_vertikal . "' data-id='" . $level_2["id"] . "'>
								<div class='cros-opd'>
									" . $show_nama_skpd . "
									<a href='javascript:void(0)' data-id='" . $valCross['id'] . "' class='detail-cc' onclick='event.stopPropagation(); detail_cc(" . $valCross['id'] . "); return false;' title='Detail'>
										<i class='dashicons dashicons-info'></i>
									</a>
								</div>
							</div>
						";
					}
				}

				// data koneksi pokin
				if (!empty($level_2['koneksi_pokin'])) {
					if ($tipe == 'opd') {
						$data_temp[$keylevel2][0]->f .= "<div class='koneksi-pokin-2 tampil_koneksi_pokin item-rincian' data-id='" . $level_2["id"] . "'>Crosscutting " . strtoupper($nama_pemda) . ":</div>";
					} else {
						$data_temp[$keylevel2][0]->f .= "<div class='koneksi-pokin-2 tampil_koneksi_pokin item-rincian' data-id='" . $level_2["id"] . "'>Crosscutting Dengan:</div>";
					}
					foreach ($level_2['koneksi_pokin'] as $key_koneksi => $val_koneksi) {
						if ($tipe == 'opd') {
							$label_parent = $val_koneksi['label_parent'];
							if (!empty($val_koneksi['id_level_1_parent'])) {
								$show_nama_skpd = "<a href='" . $pokin_pemda['url'] . "&id=" . $val_koneksi['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . ucfirst($val_koneksi['label_parent']) . "</a>";
							}
						} else {
							if ($val_koneksi['tipe'] == 1) {
								$show_nama_skpd = $val_koneksi['nama_skpd'] . "<br><a href='" . $new_view_pokin_page['url'] . "&id=" . $val_koneksi['id_parent'] . "&tipe_koneksi=pemda&id_koneksi_pokin=" . $val_koneksi['id'] . "' target='_blank'>" . $val_koneksi['label_parent'] . "</a>";
							} else {
								$show_nama_skpd = "<span>" . $val_koneksi['nama_skpd'] . "<br>(" . $val_koneksi['label_parent'] . ")</span>";
							}
						}
						$data_temp[$keylevel2][0]->f .= "<div class='koneksi-pokin tampil_koneksi_pokin item-rincian' data-id='" . $level_2["id"] . "'><div class='cros-opd'>" . $show_nama_skpd . "</div></div>";
					}
				}

				if (!empty($level_2['data'])) {

					foreach ($level_2['data'] as $keylevel3 => $level_3) {
						$data_all_format_json[$level_3['id']] = $level_3;
						unset($data_all_format_json[$level_3['id']]['data']);

						$data_temp[$keylevel3][0] = (object)[
							'v' => $level_3['id'],
							'f' => "<div class=\"" . $style0 . " label3\" data-id=\"" . $level_3['id'] . "\">" . trim($level_3['label']) . "</div>",
						];

						if (!empty($level_3['indikator'])) {
							foreach ($level_3['indikator'] as $keyindikatorlevel3 => $indikator) {
								$data_temp[$keylevel3][0]->f .= '<div class="level3 item-rincian" data-id="' . $level_3['id'] . '">IK: ' . $indikator['label_indikator_kinerja'] . '</div>';
							}
						}

						// croscutting level 3
						if (!empty($level_3['croscutting'])) {
							$data_temp[$keylevel3][0]->f .= "<div class='croscutting-2 tampil_croscutting item-rincian' data-id='" . $level_3["id"] . "'>Crosscutting Dengan:</div>";
							foreach ($level_3['croscutting'] as $keyCross => $valCross) {
								$nama_skpd_all = array();
								$class_cc_opd_lain = '';
								$id_pokin_cc = $valCross['parent_croscutting'];
								if ($valCross['croscutting_opd_lain'] == 1) {
									$class_cc_opd_lain = 'cc-opd-lain';
									$id_pokin_cc = $valCross['parent_pohon_kinerja'];
								}

								$show_nama_skpd = $valCross['nama_skpd'];
								$label_parent = $valCross['label_parent'];
								$class_cc_vertikal = '';
								if ($valCross['is_lembaga_lainnya'] == 1) {
									$label_parent = $valCross['keterangan'];
									$class_cc_vertikal = "croscutting-lembaga-vertikal";
								}

								if ($valCross['is_lembaga_lainnya'] != 1) {
									$show_nama_skpd = $valCross['nama_skpd'] . "<br><a href='" . $new_view_pokin_page['url'] . "&tipe_koneksi=opd&id_koneksi_pokin=" . $valCross['id'] . "&id=" . $id_pokin_cc . "' target='_blank'>" . $label_parent . "</a>";
								} else {
									$show_nama_skpd = $valCross['nama_skpd'] . "<br>" . $label_parent;
								}
								
								$data_temp[$keylevel3][0]->f .= "
									<div class='croscutting tampil_croscutting item-rincian " . $class_cc_opd_lain . " " . $class_cc_vertikal . "' data-id='" . $level_3["id"] . "'>
										<div class='cros-opd'>
											" . $show_nama_skpd . "
											<a href='javascript:void(0)' data-id='" . $valCross['id'] . "' class='detail-cc' onclick='event.stopPropagation(); detail_cc(" . $valCross['id'] . "); return false;' title='Detail'>
												<i class='dashicons dashicons-info'></i>
											</a>
										</div>
									</div>
								";
							}
						}

						// data koneksi pokin
						if (!empty($level_3['koneksi_pokin'])) {
							if ($tipe == 'opd') {
								$data_temp[$keylevel3][0]->f .= "<div class='koneksi-pokin-2 tampil_koneksi_pokin item-rincian' data-id='" . $level_3["id"] . "'>Crosscutting " . strtoupper($nama_pemda) . ":</div>";
							} else {
								$data_temp[$keylevel3][0]->f .= "<div class='koneksi-pokin-2 tampil_koneksi_pokin item-rincian' data-id='" . $level_3["id"] . "'>Crosscutting Dengan:</div>";
							}
							foreach ($level_3['koneksi_pokin'] as $key_koneksi => $val_koneksi) {
								if ($tipe == 'opd') {
									$label_parent = $val_koneksi['label_parent'];
									if (!empty($val_koneksi['id_level_1_parent'])) {
										$show_nama_skpd = "<a href='" . $pokin_pemda['url'] . "&id=" . $val_koneksi['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . ucfirst($val_koneksi['label_parent']) . "</a>";
									}
								} else {
									if ($val_koneksi['tipe'] == 1) {
										$show_nama_skpd = $val_koneksi['nama_skpd'] . "<br><a href='" . $new_view_pokin_page['url'] . "&id=" . $val_koneksi['id_parent'] . "&tipe_koneksi=pemda&id_koneksi_pokin=" . $val_koneksi['id'] . "' target='_blank'>" . $val_koneksi['label_parent'] . "</a>";
									} else {
										$show_nama_skpd = "<span>" . $val_koneksi['nama_skpd'] . "<br>(" . $val_koneksi['label_parent'] . ")</span>";
									}
								}
								$data_temp[$keylevel3][0]->f .= "<div class='koneksi-pokin tampil_koneksi_pokin item-rincian' data-id='" . $level_3["id"] . "'><div class='cros-opd'>" . $show_nama_skpd . "</div></div>";
							}
						}

						if (!empty($level_3['data'])) {
							foreach ($level_3['data'] as $keylevel4 => $level_4) {
								$data_all_format_json[$level_4['id']] = $level_4;
								unset($data_all_format_json[$level_4['id']]['data']);

								$data_temp[$keylevel4][0] = (object)[
									'v' => $level_4['id'],
									'f' => "<div class=\"" . $style0 . " label4\" data-id=\"" . $level_4['id'] . "\">" . trim($level_4['label']) . "</div>"
								];

								if (!empty($level_4['indikator'])) {
									foreach ($level_4['indikator'] as $keyindikatorlevel4 => $indikator) {
										$data_temp[$keylevel4][0]->f .= '<div class="level4 item-rincian" data-id="' . $level_4['id'] . '">IK: ' . $indikator['label_indikator_kinerja'] . '</div>';
									}
								}

								// croscutting level 4
								if (!empty($level_4['croscutting'])) {

									$data_temp[$keylevel4][0]->f .= "<div class='croscutting-2 tampil_croscutting item-rincian' data-id='" . $level_4["id"] . "'>Crosscutting Dengan:</div>";
									foreach ($level_4['croscutting'] as $keyCross => $valCross) {
										$nama_skpd_all = array();
										$class_cc_opd_lain = '';
										$id_pokin_cc = $valCross['parent_croscutting'];
										if ($valCross['croscutting_opd_lain'] == 1) {
											$class_cc_opd_lain = 'cc-opd-lain';
											$id_pokin_cc = $valCross['parent_pohon_kinerja'];
										}

										$show_nama_skpd = $valCross['nama_skpd'];
										$label_parent = $valCross['label_parent'];
										$class_cc_vertikal = '';
										if ($valCross['is_lembaga_lainnya'] == 1) {
											$label_parent = $valCross['keterangan'];
											$class_cc_vertikal = "croscutting-lembaga-vertikal";
										}
										if ($valCross['is_lembaga_lainnya'] != 1) {
											$show_nama_skpd = $valCross['nama_skpd'] . "<br><a href='" . $new_view_pokin_page['url'] . "&tipe_koneksi=opd&id_koneksi_pokin=" . $valCross['id'] . "&id=" . $id_pokin_cc . "' target='_blank'>" . $label_parent. "</a>";
										} else {
											$show_nama_skpd = $valCross['nama_skpd'] . "<br>" . $label_parent;
										}

										$data_temp[$keylevel4][0]->f .= "
											<div class='croscutting tampil_croscutting item-rincian " . $class_cc_opd_lain . " " . $class_cc_vertikal . "' data-id='" . $level_4["id"] . "'>
												<div class='cros-opd'>
													" . $show_nama_skpd . "
													<a href='javascript:void(0)' data-id='" . $valCross['id'] . "' class='detail-cc' onclick='event.stopPropagation(); detail_cc(" . $valCross['id'] . "); return false;' title='Detail'>
														<i class='dashicons dashicons-info'></i>
													</a>
												</div>
											</div>
										";
									}
								}

								// data koneksi pokin
								if (!empty($level_4['koneksi_pokin'])) {
									if ($tipe == 'opd') {
										$data_temp[$keylevel4][0]->f .= "<div class='koneksi-pokin-2 tampil_koneksi_pokin item-rincian' data-id='" . $level_4["id"] . "'>Crosscutting " . strtoupper($nama_pemda) . ":</div>";
									} else {
										$data_temp[$keylevel4][0]->f .= "<div class='koneksi-pokin-2 tampil_koneksi_pokin item-rincian' data-id='" . $level_4["id"] . "'>Crosscutting Dengan:</div>";
									}
									foreach ($level_4['koneksi_pokin'] as $key_koneksi => $val_koneksi) {
										$label_parent = $val_koneksi['label_parent'];
										if ($tipe == 'opd') {
											$show_nama_skpd = "<a href='" . $pokin_pemda['url'] . "&id=" . $val_koneksi['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . ucfirst($val_koneksi['label_parent']) . "</a>";
										} else {
											if ($val_koneksi['tipe'] == 1) {
												$show_nama_skpd = $val_koneksi['nama_skpd'] . "<br><a href='" . $new_view_pokin_page['url'] . "&id=" . $val_koneksi['id_parent'] . "&tipe_koneksi=pemda&id_koneksi_pokin=" . $val_koneksi['id'] . "' target='_blank'>" . $val_koneksi['label_parent'] . "</a>";
											} else {
												$show_nama_skpd = "<span>" . $val_koneksi['nama_skpd'] . "<br>(" . $val_koneksi['label_parent'] . ")</span>";
											}
											
											// get pokin OPD child yang terkoneksi ke pemda
											$data_all_koneksi = array('data' => $this->get_pokin(array(
												'id' => $val_koneksi['id_parent'],
												'level' => $val_koneksi['level_parent'] + 1,
												'periode' => $input['periode'],
												'tipe' => 'opd',
												'id_skpd' => $val_koneksi['id_skpd_view_pokin']
											)));

											if (!empty($data_all_koneksi['data'])) {
												$data_temp2 = array();
												foreach ($data_all_koneksi['data'] as $keylevel2opd => $level_3_opd) {
													$data_temp2[$keylevel2opd][0] = (object)[
														'v' => $level_3_opd['id'],
														'f' => "<div class=\"{$style0} label5 koneksi-pokin-pemda tampil_koneksi_pokin\">" . trim($level_3_opd['label']) . "</div>",
													];

													if (!empty($level_3_opd['indikator'])) {
														foreach ($level_3_opd['indikator'] as $keyindikatorlevel5 => $indikator) {
															$data_temp2[$keylevel2opd][0]->f .= "<div " . $style5 . " class='koneksi-pokin-pemda tampil_koneksi_pokin'>IK: " . $indikator['label_indikator_kinerja'] . "</div>";
														}
													}
													$data_temp2[$keylevel2opd][1] = $val_koneksi['id_parent'];
													$data_temp2[$keylevel2opd][2] = $level_3_opd['id'];

													if (!empty($level_3_opd['data'])) {
														foreach ($level_3_opd['data'] as $keylevel3opd => $level_4_opd) {
															$data_temp2[$keylevel3opd][0] = (object)[
																'v' => $level_4_opd['id'],
																'f' => "<div class=\"{$style0} label5 koneksi-pokin-pemda tampil_koneksi_pokin\">" . trim($level_4_opd['label']) . "</div>",
															];

															if (!empty($level_4_opd['indikator'])) {
																foreach ($level_4_opd['indikator'] as $keyindikatorlevel5 => $indikator) {
																	$data_temp2[$keylevel3opd][0]->f .= "<div " . $style5 . " class='koneksi-pokin-pemda tampil_koneksi_pokin'>IK: " . $indikator['label_indikator_kinerja'] . "</div>";
																}
															}
															$data_temp2[$keylevel3opd][1] = $level_3_opd['id'];
															$data_temp2[$keylevel3opd][2] = $level_4_opd['id'];

															if (!empty($level_4_opd['data'])) {
																foreach ($level_4_opd['data'] as $keylevel4opd => $level_5_opd) {
																	$data_temp2[$keylevel4opd][0] = (object)[
																		'v' => $level_5_opd['id'],
																		'f' => "<div class=\"{$style0} label5 koneksi-pokin-pemda tampil_koneksi_pokin\">" . trim($level_5_opd['label']) . "</div>",
																	];

																	if (!empty($level_5_opd['indikator'])) {
																		foreach ($level_5_opd['indikator'] as $keyindikatorlevel5 => $indikator) {
																			$data_temp2[$keylevel4opd][0]->f .= "<div " . $style5 . " class='koneksi-pokin-pemda tampil_koneksi_pokin'>IK: " . $indikator['label_indikator_kinerja'] . "</div>";
																		}
																	}
																	$data_temp2[$keylevel4opd][1] = $level_4_opd['id'];
																	$data_temp2[$keylevel4opd][2] = $level_5_opd['id'];
																}
															}
														}
													}
												}
												$data_all_format_json[$level_4['id']]['data_org'] = $data_temp2;
											}
										}
										$data_temp[$keylevel4][0]->f .= "<div class='koneksi-pokin tampil_koneksi_pokin item-rincian' data-id='" . $level_4["id"] . "'><div class='cros-opd'>" . $show_nama_skpd . "</div></div>";
									}
								}

								if (!empty($level_4['data'])) {

									foreach ($level_4['data'] as $keylevel5 => $level_5) {
										$data_all_format_json[$level_5['id']] = $level_5;
										unset($data_all_format_json[$level_5['id']]['data']);

										$data_temp[$keylevel5][0] = (object)[
											'v' => $level_5['id'],
											'f' => "<div class=\"" . $style0 . " label5\" data-id=\"" . $level_5['id'] . "\">" . trim($level_5['label']) . "</div>",
										];

										if (!empty($level_5['indikator'])) {
											foreach ($level_5['indikator'] as $keyindikatorlevel5 => $indikator) {
												$data_temp[$keylevel5][0]->f .= '<div class="level5 item-rincian" data-id="' . $level_5['id'] . '">IK: ' . $indikator['label_indikator_kinerja'] . '</div>';
											}
										}

										// croscutting level 5
										if (!empty($level_5['croscutting'])) {
											$data_temp[$keylevel5][0]->f .= "<div class='croscutting-2 tampil_croscutting item-rincian' data-id='" . $level_5["id"] . "'>Crosscutting Dengan:</div>";
											foreach ($level_5['croscutting'] as $keyCross => $valCross) {
												$nama_skpd_all = array();
												$class_cc_opd_lain = '';
												$id_pokin_cc = $valCross['parent_croscutting'];
												if ($valCross['croscutting_opd_lain'] == 1) {
													$class_cc_opd_lain = 'cc-opd-lain';
													$id_pokin_cc = $valCross['parent_pohon_kinerja'];
												}

												$show_nama_skpd = $valCross['nama_skpd'];
												$label_parent = $valCross['label_parent'];
												
												$class_cc_vertikal = '';
												if ($valCross['is_lembaga_lainnya'] == 1) {
													$label_parent = $valCross['keterangan'];
													$class_cc_vertikal = "croscutting-lembaga-vertikal";
												}

												if ($valCross['is_lembaga_lainnya'] != 1) {
													$show_nama_skpd = $valCross['nama_skpd'] . "<br><a href='" . $new_view_pokin_page['url'] . "&tipe_koneksi=opd&id_koneksi_pokin=" . $valCross['id'] . "&id=" . $id_pokin_cc . "' target='_blank'>" . $label_parent . "</a>";
												} else {
													$show_nama_skpd = $valCross['nama_skpd'] . "<br>" . $label_parent;
												}

												$data_temp[$keylevel5][0]->f .= "
													<div class='croscutting tampil_croscutting  item-rincian " . $class_cc_opd_lain . " " . $class_cc_vertikal . "' data-id='" . $level_5["id"] . "'>
														<div class='cros-opd'>" . $show_nama_skpd . " 
															<a href='javascript:void(0)' data-id='" . $valCross['id'] . "' class='detail-cc' onclick='event.stopPropagation(); detail_cc(" . $valCross['id'] . "); return false;' title='Detail'>
																<i class='dashicons dashicons-info'></i>
															</a>
														</div>
													</div>
												";
											}
										}

										// data koneksi pokin
										if (!empty($level_5['koneksi_pokin'])) {
											$data_temp[$keylevel5][0]->f .= "<div class='koneksi-pokin-2 tampil_koneksi_pokin item-rincian' data-id='" . $level_5["id"] . "'>Crosscutting " . strtoupper($nama_pemda) . ":</div>";
											foreach ($level_5['koneksi_pokin'] as $key_koneksi => $val_koneksi) {

												$label_parent = $val_koneksi['label_parent'];
												if (!empty($val_koneksi['id_level_1_parent'])) {
													$show_nama_skpd = "<a href='" . $pokin_pemda['url'] . "&id=" . $val_koneksi['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . ucfirst($val_koneksi['label_parent']) . "</a>";
												}

												$data_temp[$keylevel5][0]->f .= "<div class='koneksi-pokin tampil_koneksi_pokin item-rincian' data-id='" . $level_5["id"] . "'><div class='cros-opd'>" . $show_nama_skpd . "</div></div>";
											}
										}

										$data_temp[$keylevel5][1] = $level_4['id'];
										$data_temp[$keylevel5][2] = $level_5['id'];
									}
								}

								$data_temp[$keylevel4][1] = $level_3['id'];
								$data_temp[$keylevel4][2] = '';
							}
						}

						$data_temp[$keylevel3][1] = $level_2['id'];
						$data_temp[$keylevel3][2] = '';
					}
				}

				$data_temp[$keylevel2][1] = $level_1['id'];
				$data_temp[$keylevel2][2] = '';
			}
		}

		$data_temp[$keylevel1][1] = '';
		$data_temp[$keylevel1][2] = '';
	}
}

// echo '<pre>'; print_r($data_temp); echo '</pre>';die();

?>

<style type="text/css">
	.google-visualization-orgchart-node {
		border-radius: 5px;
		border: 0;
		padding: 0;
		vertical-align: top;
	}

	#chart_div .google-visualization-orgchart-connrow-medium {
		height: 20px;
	}

	#chart_div .google-visualization-orgchart-linebottom {
		border-bottom: 4px solid #f84d4d;
	}

	#chart_div .google-visualization-orgchart-lineleft {
		border-left: 4px solid #f84d4d;
	}

	#chart_div .google-visualization-orgchart-lineright {
		border-right: 4px solid #f84d4d;
	}

	#chart_div .google-visualization-orgchart-linetop {
		border-top: 4px solid #f84d4d;
	}

	.level0 {
		color: #0d0909;
		font-size: 13px;
		font-weight: 600;
		padding: 10px;
		min-height: 80px;
		min-width: 200px;
	}

	.label1 {
		background: #efd655;
		border-radius: 5px 5px 0 0;
	}

	.level1 {
		color: #0d0909;
		font-size: 11px;
		font-weight: 600;
		font-style: italic;
		padding: 10px;
		min-height: 70px;
	}

	.label2 {
		background: #fe7373;
		border-radius: 5px 5px 0 0;
	}

	.level2 {
		color: #0d0909;
		font-size: 11px;
		font-weight: 600;
		font-style: italic;
		padding: 10px;
		min-height: 70px;
	}

	.label3 {
		background: #57b2ec;
		border-radius: 5px 5px 0 0;
	}

	.level3 {
		color: #0d0909;
		font-size: 11px;
		font-weight: 600;
		font-style: italic;
		padding: 10px;
		min-height: 70px;
	}

	.label4 {
		background: #c979e3;
		border-radius: 5px 5px 0 0;
	}

	.level4 {
		color: #0d0909;
		font-size: 11px;
		font-weight: 600;
		font-style: italic;
		padding: 10px;
		min-height: 70px;
	}

	.label5 {
		background: #28a745;
		border-radius: 5px 5px 0 0;
	}

	.level5 {
		color: #0d0909;
		font-size: 11px;
		font-weight: 600;
		font-style: italic;
		padding: 10px;
		min-height: 70px;
	}

	#action-sakip {
		padding-top: 20px;
	}

	@media print {
		#cetak {
			max-width: auto !important;
			height: auto !important;
		}

		@page {
			size: landscape;
		}

		#action-sakip,
		.site-header,
		.site-footer {
			display: none;
		}

		.detail-cc {
			display: none;
		}

		.tampil_croscutting a {
			text-decoration: none !important;
			color: #0d0909 !important;
		}
	}

	#val-range {
		width: 80px;
		height: 35px;
		text-align: center;
		font-size: 20px;
		padding: 0;
		vertical-align: middle;
		font-weight: bold;
		margin-top: 20px;
	}

	.croscutting {
		color: #0d0909;
		font-size: 12px;
		font-weight: 600;
		font-style: italic;
		padding: 10px;
		min-height: 70px;
		background: #FFC6FF;
	}

	.croscutting .cros-opd {
		margin: 0px -10px;
		font-size: 12px;
		font-style: normal;
	}

	.croscutting.cc-opd-lain {
		background-color: #9BF6FF;
	}

	.croscutting-2 {
		color: #0d0909;
		font-size: 13px;
		font-weight: 600;
		padding: 10px;
		background: #FFC6FF;
	}

	.croscutting-lembaga-vertikal {
		background-color: #f1b82a;
	}

	.tampil_croscutting {
		display: none;
	}

	.detail_crocutting {
		background-color: #f4f6f8 !important;
		color: #111827 !important;
	}

	.detail-cc .dashicons {
		text-decoration: none;
		color: black;
		vertical-align: text-bottom !important;
	}

	.koneksi-pokin {
		color: #0d0909;
		font-size: 12px;
		font-weight: 600;
		font-style: italic;
		padding: 10px;
		min-height: 70px;
		background: #FDFFB6;
	}


	.koneksi-pokin-2 {
		color: #0d0909;
		font-size: 13px;
		font-weight: 600;
		padding: 10px;
		background: #FDFFB6;
	}

	.koneksi-pokin-pemda {
		color: #0d0909;
		font-size: 12px;
		font-weight: 600;
		font-style: italic;
		padding: 10px;
		min-height: 70px;
		background: #28a745;
	}

	.tampil_koneksi_pokin {
		display: none;
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

	table,
	th,
	td {
		border: 1px solid black;
		border-collapse: collapse;
	}

	td {
		padding: 5px;
	}
</style>
<div class="container-fluid" id="action-sakip">
	<div class="row">
		<div class="col-md-6 text-left" style="padding-left: 50px;">
			<h4 style="margin: 30px 0 10px; font-weight: bold;">Catatan:</h4>
			<ul>
				<li>Klik 2x pada judul card atau kotak untuk membuka atau menutup pokin anaknya. Klik pada kotak indikator untuk melihat detail informasi Pohon kinerja terkait.</li>
				<li>Pohon kinerja bisa dilihat ketika data terisi minimal sampai dengan level ke-2.</li>
				<li>Background warna <span class="badge" style="background: #efd655; padding: 5px;">kuning tua</span> adalah baris level ke 1</li>
				<li>Background warna <span class="badge" style="background: #fe7373; padding: 5px;">merah tua</span> adalah baris level ke 2</li>
				<li>Background warna <span class="badge" style="background: #57b2ec; padding: 5px;">biru tua</span> adalah baris level ke 3</li>
				<li>Background warna <span class="badge" style="background: #c979e3; padding: 5px;">ungu tua</span> adalah baris level ke 4</li>
				<?php if ($tipe == 'opd'): ?>
					<li>Background warna <span class="badge" style="background: #28a745; padding: 5px;">hijau</span> adalah baris level ke 5</li>
					<li>Background warna <span class="badge" style="background: #b5d9ea; padding: 5px;">abu-abu</span> adalah baris indikator</li>
					<li>Background warna <span class="badge" style="background: #FDFFB6; padding: 5px;">kuning</span> adalah baris koneksi pokin dengan pemda</li>
					<li>Background warna <span class="badge" style="background: #FFC6FF; padding: 5px;">ungu</span> adalah baris croscutting</li>
					<li>Background warna <span class="badge" style="background: #9BF6FF; padding: 5px;">hijau tosca</span> adalah baris croscutting dari perangkat daerah lain</li>
				<?php else: ?>
					<li>Background warna <span class="badge" style="background: #28a745; padding: 5px;">hijau</span> adalah baris POKIN perangkat daerah yang terkoneksi ke POKIN Pemerintah daerah</li>
				<?php endif; ?>
			</ul>
		</div>
		<div class="col-md-6 text-center">
			<button class="btn btn-primary btn-large" onclick="window.print();"><i class="dashicons dashicons-printer"></i> Cetak / Print</button><br>
			<div style="display: block;">
				<?php if ($tipe == 'opd'): ?>
					<div class="custom-control custom-checkbox custom-control-inline mt-4">
						<input type="checkbox" class="custom-control-input" id="show_croscutting">
						<label class="custom-control-label" for="show_croscutting">Tampilkan Croscutting</label>
					</div>
				<?php endif; ?>
				<div class="custom-control custom-checkbox custom-control-inline mt-4">
					<input type="checkbox" class="custom-control-input" id="show_koneksi_pokin_pemda">
					<label class="custom-control-label" for="show_koneksi_pokin_pemda">Tampilkan Koneksi Pokin <?php echo $nama_pemda; ?></label>
				</div>
				<div class="form-group mt-4">
					<label class="d-block font-weight-bold mb-2" for="pokin_level">Tampilkan Pokin Sampai Level:</label>

					<div class="d-flex flex-wrap justify-content-center">
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="pokin_level_1" name="pokin_level" value="1" class="custom-control-input" checked>
							<label class="custom-control-label" for="pokin_level_1">Level 1</label>
						</div>

						<div class="custom-control custom-radio custom-control-inline mr-3">
							<input type="radio" id="pokin_level_2" name="pokin_level" value="2" class="custom-control-input">
							<label class="custom-control-label" for="pokin_level_2">Level 2</label>
						</div>

						<div class="custom-control custom-radio custom-control-inline mr-3">
							<input type="radio" id="pokin_level_3" name="pokin_level" value="3" class="custom-control-input">
							<label class="custom-control-label" for="pokin_level_3">Level 3</label>
						</div>

						<div class="custom-control custom-radio custom-control-inline mr-3">
							<input type="radio" id="pokin_level_4" name="pokin_level" value="4" class="custom-control-input">
							<label class="custom-control-label" for="pokin_level_4">Level 4</label>
						</div>

						<div class="custom-control custom-radio custom-control-inline mr-3">
							<input type="radio" id="pokin_level_5" name="pokin_level" value="5" class="custom-control-input">
							<label class="custom-control-label" for="pokin_level_5">Level 5</label>
						</div>
					</div>
				</div>
			</div>
			Perkecil (-) <input title="Perbesar/Perkecil Layar" id="test" min="1" max="15" value='10' step="1" onchange="showVal(this.value)" type="range" style="max-width: 400px; margin-top: 40px;" /> (+) Perbesar
			<br>
			<textarea id="val-range" disabled>100%</textarea>
			<br>
			<div class="input-group" style="max-width: 400px; margin: 10px auto 0;">
				<input type="text" class="form-control" placeholder="Cari Pohon Kinerja" title="Cari Pohon Kinerja" aria-label="Search" id="cari_pokin" onkeydown="if(event.keyCode == 13){cari_pokin()};">
				<div class="input-group-append">
					<button class="btn btn-success" type="submit" onclick="cari_pokin();"><span class="dashicons dashicons-search"></span></button>
				</div>
			</div>
		</div>
	</div>
</div>
<h1 style="text-align: center; margin-top: 30px; font-weight: bold;">Pohon Kinerja<br><?php echo $nama_skpd . $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h1><br>
<div id="cetak" title="Laporan Pohon Kinerja" style="padding: 5px; overflow: auto; max-width: 100vw; max-height: 90vh;">
	<div id="chart_div"></div>
</div>

<!-- Modal detail -->
<div class="modal fade mt-5" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detailLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-detailLabel">Detail Croscutting</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Label Pengusul</label>
								<textarea class="form-control detail_crocutting" id="label-pengusul" rows="3" disabled></textarea>
							</div>
							<div class="form-group">
								<label>Perangkat Daerah Pengusul</label>
								<input class="form-control detail_crocutting" type="text" id="perangkat-pengusul" disabled>
							</div>
							<div class="form-group">
								<label for="">Keterangan Pengusul</label>
								<textarea class="form-control detail_crocutting" id="keterangan-pengusul" rows="3" disabled></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Label Tujuan</label>
								<textarea class="form-control detail_crocutting" id="label-tujuan" rows="3" disabled></textarea>
							</div>
							<div class="form-group">
								<label>Perangkat Daerah Tujuan</label>
								<input class="form-control detail_crocutting" type="text" id="perangkat-tujuan" disabled>
							</div>
							<div class="form-group">
								<label for="">Keterangan Tujuan</label>
								<textarea class="form-control detail_crocutting" id="keterangan-tujuan" rows="3" disabled></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 d-flex justify-content-center">
							<div class="form-group text-center">
								<label for="">Status</label>
								<button type="button" class="btn btn-success d-block">Disetujui</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

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
			<?php if ($tipe == 'opd'): ?>
				<div class="wrap-table">
					<table id="croscutting" cellpadding="2" cellspacing="0" class="table table-bordered">
						<thead class="bg-dark text-light">
							<tr>
								<th class="text-center" style="border: 1px solid black;">Perangkat Pengusul</th>
								<th class="text-center" style="border: 1px solid black;">Keterangan Pengusul</th>
								<th class="text-center" style="border: 1px solid black;">Keterangan Tujuan</th>
								<th class="text-center" style="border: 1px solid black;">Perangkat Daerah Tujuan</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<div class="wrap-table">
					<table id="croscutting" cellpadding="2" cellspacing="0" class="table table-bordered">
						<thead class="bg-dark text-light">
							<tr>
								<th class="text-center" style="border: 1px solid black;">PD/UPT/Lembaga/Desa</th>
								<th class="text-center" style="border: 1px solid black;">Pohon Kinerja</th>
								<th class="text-center" style="border: 1px solid black;">Informasi Kegiatan</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	window.data_all_json = <?php echo json_encode($data_all['data']); ?>;
	window.data_all_format_json = <?php echo json_encode($data_all_format_json); ?>;

	jQuery(document).ready(function() {
		jQuery(document).on('click', '.item-rincian', function() {
			var tipe = "<?php echo $tipe; ?>";
			var id_skpd = "<?php echo $id_skpd; ?>";
			var idPokin = jQuery(this).data('id');
			if (idPokin == undefined) {
				alert("Id tidak ditemukan");
				return;
			}
			let ajaxData = {
				"action": "edit_pokin",
				"api_key": esakip.api_key,
				"id": idPokin,
				"tipe_pokin": tipe
			};
			if (tipe === 'opd') {
				ajaxData["id_skpd"] = id_skpd;
			}

			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method: 'POST',
				url: esakip.url,
				data: ajaxData,
				dataType: 'json',
				success: function(response) {
					jQuery("#wrap-loading").hide();
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

					jQuery("#bentuk_kegiatan").text(response.data.bentuk_kegiatan || '-');
					jQuery("#outcome").text(response.data.outcome || '-');
					<?php if ($tipe == 'opd'): ?>

						jQuery("#pelaksana").text('<?php echo $skpd['nama_skpd']; ?>' || '-');
						if (!response.data_koneksi_croscutting_opd || response.data_koneksi_croscutting_opd.length === 0) {
							jQuery("#croscutting tbody").html(`<tr><td colspan="4" class="text-center" style="border: 1px solid black;">Tidak ada data koneksi croscutting</td></tr>`);
						} else {
							jQuery("#croscutting tbody").html(response.data_koneksi_croscutting_opd);
						}

					<?php else: ?>

						jQuery("#pelaksana").text(response.data.pelaksana || '-');
						if (!response.data_koneksi_croscutting_pemda || response.data_koneksi_croscutting_pemda.length === 0) {
							jQuery("#croscutting tbody").html(`<tr><td colspan="4" class="text-center" style="border: 1px solid black;">Tidak ada data koneksi croscutting</td></tr>`);
						} else {
							jQuery("#croscutting tbody").html(response.data_koneksi_croscutting_pemda);
						}

					<?php endif; ?>

					toggleSidebar();
				},
				error: function(e) {
					jQuery("#wrap-loading").hide();
					let msg = 'Terjadi kesalahan saat mengambil data Pohon Kinerja.';
					if (e.responseJSON && e.responseJSON.message) {
						msg = e.responseJSON.message;
					}
					alert(msg);
				}
			});
		});
	});

	google.charts.load('current', {
		packages: ["orgchart"]
	});

	google.charts.setOnLoadCallback(drawChart);

	<?php if ($tipe == 'opd'): ?>

		function detail_cc(id) {
			jQuery("#wrap-loading").show();
			if (id == undefined) {
				alert("Id tidak ditemukan")
			}

			jQuery.ajax({
				method: 'POST',
				url: esakip.url,
				data: {
					"action": "detail_croscutting_by_id",
					"api_key": esakip.api_key,
					'id': id,
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType: 'json',
				success: function(response) {
					jQuery("#wrap-loading").hide();
					if (response.status) {
						jQuery("#label-pengusul").val(response.data_croscutting.label_parent)
						jQuery("#perangkat-pengusul").val(response.data_croscutting.nama_perangkat)
						jQuery("#keterangan-pengusul").val(response.data_croscutting.keterangan)
						jQuery("#perangkat-tujuan").val(response.data_croscutting.nama_perangkat_tujuan)
						jQuery("#label-tujuan").val(response.data_croscutting.label_parent_tujuan)
						jQuery("#keterangan-tujuan").val(response.data_croscutting.keterangan_tujuan)
						jQuery("#modal-detail").modal('show');
					}
				}
			});
		};

		jQuery("#show_croscutting").on('click', function() {
			if (this.checked) {
				jQuery('body').prepend('<style id="custom_style_pokin">.tampil_croscutting{ display: block !important; }</style>');
			} else {
				jQuery("#custom_style_pokin").remove();
			}
		});
	<?php endif; ?>

	jQuery("#show_koneksi_pokin_pemda").on('click', function() {
		var collapse = false;
		if (this.checked) {
			console.log("muncul");
		} else {
			console.log("tutup");
			collapse = true;
		}
		// data_all.map(function(b, i) {
		// 	if (b[0] && b[0].f && b[0].f.indexOf('show-hide-pokin-opd') !== -1) {
		// 		chart.collapse(i, collapse);
		// 	}
		// });
		if (this.checked) {
			jQuery('body').prepend('<style id="custom_style_pokin_pemda">.tampil_koneksi_pokin{ display: block !important; }</style>');
		} else {
			jQuery("#custom_style_pokin_pemda").remove();
		}
		center_div(1);
	});

	function tampil_all_pokin(sampai_level) {
		console.log("Showing Pokin up to Level (after reset):", sampai_level);

		// --- Collapse/Hide ALL items first ---
		data_all.forEach(function(b, i) {
			chart.collapse(i, true);
		});

		let index = sampai_level - 1;

		data_all.forEach(function(b, i) {
			let should_be_visible = false;

			for (let level_to_check = 1; level_to_check <= index; level_to_check++) {

				const target_label = ' label' + level_to_check + '"';

				if (b[0] && b[0].f && b[0].f.indexOf(target_label) !== -1) {
					should_be_visible = true;
					break;
				}
			}

			if (should_be_visible) {
				chart.collapse(i, !true);
			}
		});

		center_div(sampai_level);
	}

	jQuery("[name='pokin_level']").on('change', function() {
		let sampai_level = parseInt(jQuery(this).val(), 10);
		tampil_all_pokin(sampai_level);
	});

	function cari_pokin() {
		var val = jQuery('#cari_pokin').val();
		if (val == '') {
			return;
		}
		var cek = false;
		data_all.forEach(function(b, i) {
			var text = '';
			if (
				!cek &&
				b[0] &&
				b[0].f
			) {
				var html = jQuery('<div>' + b[0].f + '</div>');
				var div1 = jQuery(html.find('div')[0]);
				text += div1.text().toLowerCase();
				text += jQuery(html.find('.item-rincian')[0]).text().toLowerCase();
				var _class = div1.attr('class').replace(/ /g, '.');
				var id = div1.attr('data-id');
				if (text.indexOf(val.toLowerCase()) !== -1) {
					cek = true;
					center_div(false, jQuery('.' + _class + '[data-id="' + id + '"]')[0]);
					cariTeks(val);
				}
			}
		});
		if (!cek) {
			alert('Kata kunci "' + val + '" tidak ditemukan!');
		}
	}

	function cariTeks(query) {
		window.getSelection().removeAllRanges();
		for (let i = 0; i < 2; i++) {
			window.find(query, false, false, true, false, false, false);
		}
	}

	function drawChart() {
		window.data_all = <?php echo json_encode(array_values($data_temp)); ?>;
		console.log(data_all);
		window.data = new google.visualization.DataTable();
		data.addColumn('string', 'Name');
		data.addColumn('string', 'Manager');
		data.addColumn('string', 'ToolTip');
		data.addRows(data_all);
		data.setRowProperty(2, 'selectedStyle');

		// Create the chart.
		window.chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
		// Draw the chart, setting the allowHtml option to true for the tooltips.
		chart.draw(data, {
			'allowHtml': true,
			'size': 'small',
			'compactRows': true,
			'allowCollapse': true
		});

		data_all.map(function(b, i) {
			if (b[0] && b[0] !== '') {
				chart.collapse(i, true); // ditutup
			}
		});
		jQuery("#show_all_pokin").prop('checked', false);
		center_div(1);
	}

	function setZoom(zoom, el) {

		transformOrigin = [0, 0];
		el = el || instance.getContainer();
		var p = ["webkit", "moz", "ms", "o"],
			s = "scale(" + zoom + ")",
			oString = (transformOrigin[0] * 100) + "% " + (transformOrigin[1] * 100) + "%";

		for (var i = 0; i < p.length; i++) {
			el.style[p[i] + "Transform"] = s;
			el.style[p[i] + "TransformOrigin"] = oString;
		}

		el.style["transform"] = s;
		el.style["transformOrigin"] = oString;

	}

	function showVal(val) {
		jQuery('#val-range').val((val * 10) + '%');
		setZoom(val / 10, document.getElementById('chart_div'));
	}

	function toggleSidebar() {
		jQuery('#rincianSidebar').toggleClass('show');
		jQuery('#sidebarBackdrop').toggleClass('show');
	}

	function center_div(level, html = false) {
		if (html) {
			var target = html;
		} else {
			var target = document.getElementsByClassName('label' + level)[0];
		}
		if (target) {
			target.scrollIntoView({
				behavior: 'smooth',
				block: 'center',
				inline: 'center'
			});
		}
	}
</script>