<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

$input = shortcode_atts(array(
  	'tahun_anggaran' => '2024',
	'periode' => '',
), $atts);

if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}
global $wpdb;

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
$data_notifikasi_croscutting = array();
$data_notifikasi_koneksi_pokin_pemda = array();
$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
$is_admin_bappeda = in_array('admin_bappeda', $user_roles);
if($is_admin_bappeda == false){
    $is_admin_bappeda = in_array('administrator', $user_roles);
}

$data_all = [
	'data' => array()
];

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
if(empty($periode)){
	die('<h1 class="text-center">Jadwal periode RPJMD/RPD tidak ditemukan!</h1>');
}

if(!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1){
    $tahun_periode = $periode['tahun_selesai_anggaran'];
}else{
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

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

$unit_croscutting = $wpdb->get_results(
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
		AND id_skpd!=%d
		AND tahun_anggaran=%d
		GROUP BY id_skpd
		ORDER BY kode_skpd ASC
	", $id_skpd, $tahun_anggaran_sakip),
	ARRAY_A
);

$option_skpd = "<option value=''>Pilih Perangkat Daerah</option>";
if(!empty($unit_croscutting)){
	foreach ($unit_croscutting as $v_unit) {
		$option_skpd .="<option value='" . $v_unit['id_skpd'] . "'>" . $v_unit['nama_skpd'] . "</option>";
	}
}

$lembaga_lainnya_croscutting = $wpdb->get_results("
	SELECT 
		nama_lembaga, 
		id,
		tahun_anggaran 
	FROM esakip_data_lembaga_lainnya 
	WHERE active=1 
	GROUP BY id
	ORDER BY nama_lembaga ASC
", ARRAY_A);

$option_lainnya = "<option value=''>Pilih Lembaga Vertikal</option>";
if(!empty($lembaga_lainnya_croscutting)){
	foreach ($lembaga_lainnya_croscutting as $v_lainnya) {
		$option_lainnya .="<option value='" . $v_lainnya['id'] . "'>" . $v_lainnya['nama_lembaga'] . "</option>";
	}
}

// pokin level 1
$pohon_kinerja_level_1 = $wpdb->get_results($wpdb->prepare("
	SELECT 
		* 
	FROM esakip_pohon_kinerja_opd 
	WHERE parent=0 
		AND level=1 
		AND active=1 
		AND id_jadwal=%d 
		AND id_skpd=%d
    ORDER BY nomor_urut ASC
", $input['periode'], $id_skpd), ARRAY_A);
if(!empty($pohon_kinerja_level_1)){
	foreach ($pohon_kinerja_level_1 as $level_1) {
		if(empty($data_all['data'][$level_1['id']])){
			$data_all['data'][$level_1['id']] = [
				'id' => $level_1['id'],
				'label' => $level_1['label'],
				'level' => $level_1['level'],
				'indikator' => array(),
				'data' => array()
			];
            if(empty($level_1['nomor_urut'])){
                $level_1['nomor_urut'] = count($data_all['data']);
                $wpdb->update('esakip_pohon_kinerja_opd', array('nomor_urut' => $level_1['nomor_urut']), array(
                    'id' => $level_1['id']));
            }
		}

		// indikator pokin level 1
		$indikator_pohon_kinerja_level_1 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_pohon_kinerja_opd 
			WHERE parent=%d 
				AND level=1 
				AND active=1 
				AND id_jadwal=%d  
				AND id_skpd=%d
            ORDER BY nomor_urut ASC
		", $level_1['id'], $input['periode'], $id_skpd), ARRAY_A);
		if(!empty($indikator_pohon_kinerja_level_1)){
			foreach ($indikator_pohon_kinerja_level_1 as $indikator_level_1) {
				if(!empty($indikator_level_1['label_indikator_kinerja'])){
					if(empty($data_all['data'][$level_1['id']]['indikator'][$indikator_level_1['id']])){
						$data_all['data'][$level_1['id']]['indikator'][$indikator_level_1['id']] = [
							'id' => $indikator_level_1['id'],
							'parent' => $indikator_level_1['parent'],
							'label_indikator_kinerja' => $indikator_level_1['label_indikator_kinerja'],
							'level' => $indikator_level_1['level']
						];
                        if(empty($indikator_level_1['nomor_urut'])){
                            $indikator_level_1['nomor_urut'] = count($data_all['data'][$level_1['id']]['indikator']);
                            $wpdb->update('esakip_pohon_kinerja_opd', array('nomor_urut' => $indikator_level_1['nomor_urut']), array(
                                'id' => $indikator_level_1['id']));
                        }
					}
				}
			}
		}

		/**
		 * koneksi pokin pemda dan opd
		 */
		$koneksi_pohon_kinerja_pemda_level_1 = $wpdb->get_results($wpdb->prepare("
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
		", $level_1['id']), ARRAY_A);

		if(!empty($koneksi_pohon_kinerja_pemda_level_1)){
			foreach ($koneksi_pohon_kinerja_pemda_level_1 as $key_koneksi_level_1 => $koneksi_pokin_level_1) {
					$data_parent_tujuan = array();
					$id_level_1_parent = 0;
					if($koneksi_pokin_level_1['status_koneksi'] == 1){
						// untuk mendapatkan id parent ke 1 pemda
						$data_parent_tujuan = array('data' => $this->get_parent_1_koneksi_pokin_pemda_opd(array(
							'id' => $koneksi_pokin_level_1['id'],
							'level' => $koneksi_pokin_level_1['level_parent'],
							'periode' => $input['periode'],
							'id_parent' => $koneksi_pokin_level_1['id_parent']
						)));
					}

					if(!empty($data_parent_tujuan)){
						$id_level_1_parent = $data_parent_tujuan['data'];
					}

					// get indikator pokin this koneksi pokin
					$data_label_indikator_pokin_pemda = $wpdb->get_results($wpdb->prepare("
						SELECT 
							pk.label_indikator_kinerja as indikator_parent_pemda
						FROM 
							esakip_pohon_kinerja as pk
						WHERE pk.parent=%d
						AND active=1 
					", $koneksi_pokin_level_1['id_parent'])
					,ARRAY_A);

					if(empty($data_all['data'][$level_1['id']]['koneksi_pokin'][$key_koneksi_level_1])){
						$data_all['data'][$level_1['id']]['koneksi_pokin'][$key_koneksi_level_1] = [
							'id' => $koneksi_pokin_level_1['id'],
							'parent_pohon_kinerja' => $koneksi_pokin_level_1['parent_pohon_kinerja'],
							'status_koneksi' => $koneksi_pokin_level_1['status_koneksi'],
							'label_parent' => $koneksi_pokin_level_1['label_parent'],
							'id_level_1_parent' => $id_level_1_parent,
							'data_indikator_pokin_pemda' => $data_label_indikator_pokin_pemda
						];
					}
			}
		}

		// pokin level 2 
		$pohon_kinerja_level_2 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_pohon_kinerja_opd 
			WHERE parent=%d 
				AND level=2
				AND active=1 
				AND id_jadwal=%d 
				AND id_skpd=%d 
            ORDER BY nomor_urut ASC
		", $level_1['id'], $input['periode'], $id_skpd), ARRAY_A);
		if(!empty($pohon_kinerja_level_2)){
			foreach ($pohon_kinerja_level_2 as $level_2) {
				if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']])){
					$data_all['data'][$level_1['id']]['data'][$level_2['id']] = [
						'id' => $level_2['id'],
						'label' => $level_2['label'],
						'level' => $level_2['level'],
						'indikator' => array(),
						'croscutting' => array(),
						'data' => array()
					];
                    if(empty($level_2['nomor_urut'])){
                        $level_2['nomor_urut'] = count($data_all['data'][$level_1['id']]['data']);
                        $wpdb->update('esakip_pohon_kinerja_opd', array('nomor_urut' => $level_2['nomor_urut']), array(
                            'id' => $level_2['id']));
                    }
				}

				// indikator pokin level 2
				$indikator_pohon_kinerja_level_2 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_pohon_kinerja_opd 
					WHERE parent=%d 
						AND level=2 
						AND active=1 
						AND id_jadwal=%d  
						AND id_skpd=%d
                    ORDER BY nomor_urut ASC
				", $level_2['id'], $input['periode'], $id_skpd), ARRAY_A);
				if(!empty($indikator_pohon_kinerja_level_2)){
					foreach ($indikator_pohon_kinerja_level_2 as $indikator_level_2) {
						if(!empty($indikator_level_2['label_indikator_kinerja'])){
							if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['indikator'][$indikator_level_2['id']])){
								$data_all['data'][$level_1['id']]['data'][$level_2['id']]['indikator'][$indikator_level_2['id']] = [
									'id' => $indikator_level_2['id'],
									'parent' => $indikator_level_2['parent'],
									'label_indikator_kinerja' => $indikator_level_2['label_indikator_kinerja'],
									'level' => $indikator_level_2['level']
								];
                                if(empty($indikator_level_2['nomor_urut'])){
                                    $indikator_level_2['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['indikator']);
                                    $wpdb->update('esakip_pohon_kinerja_opd', array('nomor_urut' => $indikator_level_2['nomor_urut']), array(
                                        'id' => $indikator_level_2['id']));
                                }
							}
						}
					}
				}

				// croscutting pokin level 2
				// untuk mendapatkan croscutting pengusul
				$croscutting_pohon_kinerja_level_2 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						cc.* ,
						pk.id as id_parent,
						pk.level as level_parent,
						pk.label as label_parent
					FROM esakip_croscutting_opd cc
					LEFT JOIN esakip_pohon_kinerja_opd as pk ON cc.parent_croscutting = pk.id
					WHERE cc.parent_pohon_kinerja=%d 
						AND cc.active=1 
					ORDER BY cc.id
				", $level_2['id']), ARRAY_A);
				
				// untuk mendapatkan croscutting yang diusulkan
				$croscutting_pohon_kinerja_level_2_pengusul = $wpdb->get_results($wpdb->prepare("
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
				", $id_skpd, $level_2['id']),  ARRAY_A);
				if(!empty($croscutting_pohon_kinerja_level_2) && !empty($croscutting_pohon_kinerja_level_2_pengusul)){
					$croscutting_pohon_kinerja_level_2 = array_merge($croscutting_pohon_kinerja_level_2,$croscutting_pohon_kinerja_level_2_pengusul);
				}else if(empty($croscutting_pohon_kinerja_level_2) && !empty($croscutting_pohon_kinerja_level_2_pengusul)){
					$croscutting_pohon_kinerja_level_2 = $croscutting_pohon_kinerja_level_2_pengusul;
				}

				if(!empty($croscutting_pohon_kinerja_level_2)){
					foreach ($croscutting_pohon_kinerja_level_2 as $key_croscutting_level_2 => $croscutting_level_2) {
						$nama_perangkat = '';
						if($croscutting_level_2['is_lembaga_lainnya'] == 1){
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
								", $croscutting_level_2['id_skpd_croscutting'], $tahun_anggaran_sakip),
								ARRAY_A
							);
							$nama_perangkat = $nama_lembaga['nama_lembaga'];
						}else{
							if(!empty($croscutting_level_2['id_skpd_parent'])){
								$this_data_id_skpd = $croscutting_level_2['id_skpd_parent'];
							}else{
								$this_data_id_skpd = $croscutting_level_2['id_skpd_croscutting'];
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

						if(!empty($croscutting_level_2['keterangan'])){
							if(!empty($croscutting_level_2['id_skpd_parent'])){
								$croscutting_opd_lain = 1;
								$id_skpd_view_pokin = $croscutting_level_2['id_skpd_parent'];
							}else{
								$croscutting_opd_lain = 0;
								$id_skpd_view_pokin = $croscutting_level_2['id_skpd_croscutting'];
							}

							$data_parent_tujuan = array();
							$id_level_1_parent = 0;
							if($croscutting_level_2['status_croscutting'] == 1){
								// untuk mendapatkan id parent level 1 suatu opd
								$data_parent_tujuan = array('data' => $this->get_parent_1(array(
									'id' => $croscutting_level_2['id'],
									'level' => $croscutting_level_2['level_parent'],
									'periode' => $input['periode'],
									'tipe' => 'opd',
									'id_parent' => $croscutting_level_2['id_parent']
								)));
							}

							if(!empty($data_parent_tujuan)){
								$id_level_1_parent = $data_parent_tujuan['data'];
							}	

							if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['croscutting'][$key_croscutting_level_2])){
								$data_all['data'][$level_1['id']]['data'][$level_2['id']]['croscutting'][$key_croscutting_level_2] = [
									'id' => $croscutting_level_2['id'],
									'parent_pohon_kinerja' => $croscutting_level_2['parent_pohon_kinerja'],
									'keterangan' => $croscutting_level_2['keterangan'],
									'is_lembaga_lainnya' => $croscutting_level_2['is_lembaga_lainnya'],
									'status_croscutting' => $croscutting_level_2['status_croscutting'],
									'label_parent' => $croscutting_level_2['label_parent'],
									'croscutting_opd_lain' => $croscutting_opd_lain,
									'nama_skpd' => $nama_perangkat,
									'id_skpd_view_pokin' => $id_skpd_view_pokin,
									'id_level_1_parent' => $id_level_1_parent
								];
							}
						}
					}
				}

				/**
				 * koneksi pokin pemda dan opd
				 */
				$koneksi_pohon_kinerja_pemda_level_2 = $wpdb->get_results($wpdb->prepare("
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
				", $level_2['id']), ARRAY_A);

				if(!empty($koneksi_pohon_kinerja_pemda_level_2)){
					foreach ($koneksi_pohon_kinerja_pemda_level_2 as $key_koneksi_level_2 => $koneksi_pokin_level_2) {
						$data_parent_tujuan = array();
						$id_level_1_parent = 0;
						if($koneksi_pokin_level_2['status_koneksi'] == 1){
							// untuk mendapatkan id parent ke 1 pemda
							$data_parent_tujuan = array('data' => $this->get_parent_1_koneksi_pokin_pemda_opd(array(
								'id' => $koneksi_pokin_level_2['id'],
								'level' => $koneksi_pokin_level_2['level_parent'],
								'periode' => $input['periode'],
								'id_parent' => $koneksi_pokin_level_2['id_parent']
							)));
						}

						if(!empty($data_parent_tujuan)){
							$id_level_1_parent = $data_parent_tujuan['data'];
						}	

						// get indikator pokin this koneksi pokin
						$data_label_indikator_pokin_pemda = $wpdb->get_results($wpdb->prepare("
							SELECT 
								pk.label_indikator_kinerja as indikator_parent_pemda
							FROM 
								esakip_pohon_kinerja as pk
							WHERE pk.parent=%d
							AND active=1 
						", $koneksi_pokin_level_2['id_parent'])
						,ARRAY_A);

						if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['koneksi_pokin'][$key_koneksi_level_2])){
							$data_all['data'][$level_1['id']]['data'][$level_2['id']]['koneksi_pokin'][$key_koneksi_level_2] = [
								'id' => $koneksi_pokin_level_2['id'],
								'parent_pohon_kinerja' => $koneksi_pokin_level_2['parent_pohon_kinerja'],
								'status_koneksi' => $koneksi_pokin_level_2['status_koneksi'],
								'label_parent' => $koneksi_pokin_level_2['label_parent'],
								'id_level_1_parent' => $id_level_1_parent,
								'data_indikator_pokin_pemda' => $data_label_indikator_pokin_pemda
							];
						}
					}
				}

				// pokin level 3
				$pohon_kinerja_level_3 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_pohon_kinerja_opd 
					WHERE parent=%d 
						AND level=3 
						AND active=1 
						AND id_jadwal=%d  
						AND id_skpd=%d
                    ORDER BY nomor_urut ASC
				", $level_2['id'], $input['periode'], $id_skpd), ARRAY_A);
				if(!empty($pohon_kinerja_level_3)){
					foreach ($pohon_kinerja_level_3 as $level_3) {
						if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']])){
							$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']] = [
								'id' => $level_3['id'],
								'label' => $level_3['label'],
								'level' => $level_3['level'],
								'indikator' => array(),
								'croscutting' => array(),
								'data' => array()
							];
                            if(empty($level_3['nomor_urut'])){
                                $level_3['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data']);
                                $wpdb->update('esakip_pohon_kinerja_opd', array('nomor_urut' => $level_3['nomor_urut']), array(
                                    'id' => $level_3['id']));
                            }
						}

						// indikator pokin level 3
						$indikator_pohon_kinerja_level_3 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pohon_kinerja_opd 
							WHERE parent=%d 
								AND level=3 
								AND active=1 
								AND id_jadwal=%d 
								AND id_skpd=%d
                            ORDER BY nomor_urut ASC
						", $level_3['id'], $input['periode'], $id_skpd), ARRAY_A);
						if(!empty($indikator_pohon_kinerja_level_3)){
							foreach ($indikator_pohon_kinerja_level_3 as $indikator_level_3) {
								if(!empty($indikator_level_3['label_indikator_kinerja'])){
									if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['indikator'][$indikator_level_3['label_indikator_kinerja']])){
										$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['indikator'][$indikator_level_3['label_indikator_kinerja']] = [
											'id' => $indikator_level_3['id'],
											'parent' => $indikator_level_3['parent'],
											'label_indikator_kinerja' => $indikator_level_3['label_indikator_kinerja'],
											'level' => $indikator_level_3['level']
										];
                                        if(empty($indikator_level_3['nomor_urut'])){
                                            $indikator_level_3['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['indikator']);
                                            $wpdb->update('esakip_pohon_kinerja_opd', array('nomor_urut' => $indikator_level_3['nomor_urut']), array(
                                                'id' => $indikator_level_3['id']));
                                        }
									}
								}
							}
						}

						// croscutting pokin level 3
						$croscutting_pohon_kinerja_level_3 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								cc.* ,
								pk.id as id_parent,
								pk.level as level_parent,
								pk.label as label_parent
							FROM esakip_croscutting_opd cc
							LEFT JOIN esakip_pohon_kinerja_opd as pk ON cc.parent_croscutting = pk.id
							WHERE cc.parent_pohon_kinerja=%d 
								AND cc.active=1 
							ORDER BY cc.id
						", $level_3['id']), ARRAY_A);
						
						$croscutting_pohon_kinerja_level_3_pengusul = $wpdb->get_results($wpdb->prepare("
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
						", $id_skpd, $level_3['id']),  ARRAY_A);
						if(!empty($croscutting_pohon_kinerja_level_3) && !empty($croscutting_pohon_kinerja_level_3_pengusul)){
							$croscutting_pohon_kinerja_level_3 = array_merge($croscutting_pohon_kinerja_level_3,$croscutting_pohon_kinerja_level_3_pengusul);
						}else if(empty($croscutting_pohon_kinerja_level_3) && !empty($croscutting_pohon_kinerja_level_3_pengusul)){
							$croscutting_pohon_kinerja_level_3 = $croscutting_pohon_kinerja_level_3_pengusul;
						}

						if(!empty($croscutting_pohon_kinerja_level_3)){
							foreach ($croscutting_pohon_kinerja_level_3 as $key_croscutting_level_3 => $croscutting_level_3) {
								$nama_perangkat = '';
								if($croscutting_level_3['is_lembaga_lainnya'] == 1){
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
										", $croscutting_level_3['id_skpd_croscutting'], $tahun_anggaran_sakip),
										ARRAY_A
									);
									$nama_perangkat = $nama_lembaga['nama_lembaga'];
								}else{
									if(!empty($croscutting_level_3['id_skpd_parent'])){
										$this_data_id_skpd = $croscutting_level_3['id_skpd_parent'];
									}else{
										$this_data_id_skpd = $croscutting_level_3['id_skpd_croscutting'];
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

								if(!empty($croscutting_level_3['keterangan'])){
									if(!empty($croscutting_level_3['id_skpd_parent'])){
										$croscutting_opd_lain = 1;
										$id_skpd_view_pokin = $croscutting_level_3['id_skpd_parent'];
									}else{
										$croscutting_opd_lain = 0;
										$id_skpd_view_pokin = $croscutting_level_3['id_skpd_croscutting'];
									}

									$data_parent_tujuan = array();
									$id_level_1_parent = 0;
									if($croscutting_level_3['status_croscutting'] == 1){
										// untuk mendapatkan id parent ke 1 suatu opd
										$data_parent_tujuan = array('data' => $this->get_parent_1(array(
											'id' => $croscutting_level_3['id'],
											'level' => $croscutting_level_3['level_parent'],
											'periode' => $input['periode'],
											'tipe' => 'opd',
											'id_parent' => $croscutting_level_3['id_parent'],
											'id_skpd' => $id_skpd_view_pokin
										)));
									}

									if(!empty($data_parent_tujuan)){
										$id_level_1_parent = $data_parent_tujuan['data'];
									}	

									if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['croscutting'][$key_croscutting_level_3])){
										$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['croscutting'][$key_croscutting_level_3] = [
											'id' => $croscutting_level_3['id'],
											'parent_pohon_kinerja' => $croscutting_level_3['parent_pohon_kinerja'],
											'keterangan' => $croscutting_level_3['keterangan'],
											'is_lembaga_lainnya' => $croscutting_level_3['is_lembaga_lainnya'],
											'status_croscutting' => $croscutting_level_3['status_croscutting'],
											'label_parent' => $croscutting_level_3['label_parent'],
											'croscutting_opd_lain' => $croscutting_opd_lain,
											'nama_skpd' => $nama_perangkat,
											'id_skpd_view_pokin' => $id_skpd_view_pokin,
											'id_level_1_parent' => $id_level_1_parent
										];
									}
								}
							}
						}

						/**
						 * koneksi pokin pemda dan opd
						 */
						$koneksi_pohon_kinerja_pemda_level_3 = $wpdb->get_results($wpdb->prepare("
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
						", $level_3['id']), ARRAY_A);

						if(!empty($koneksi_pohon_kinerja_pemda_level_3)){
							foreach ($koneksi_pohon_kinerja_pemda_level_3 as $key_koneksi_level_3 => $koneksi_pokin_level_3) {
								$data_parent_tujuan = array();
								$id_level_1_parent = 0;
								if($koneksi_pokin_level_3['status_koneksi'] == 1){
									// untuk mendapatkan id parent ke 1 pemda
									$data_parent_tujuan = array('data' => $this->get_parent_1_koneksi_pokin_pemda_opd(array(
										'id' => $koneksi_pokin_level_3['id'],
										'level' => $koneksi_pokin_level_3['level_parent'],
										'periode' => $input['periode'],
										'id_parent' => $koneksi_pokin_level_3['id_parent']
									)));
								}

								if(!empty($data_parent_tujuan)){
									$id_level_1_parent = $data_parent_tujuan['data'];
								}	

								// get indikator pokin this koneksi pokin
								$data_label_indikator_pokin_pemda = $wpdb->get_results($wpdb->prepare("
									SELECT 
										pk.label_indikator_kinerja as indikator_parent_pemda
									FROM 
										esakip_pohon_kinerja as pk
									WHERE pk.parent=%d
									AND active=1 
								", $koneksi_pokin_level_3['id_parent'])
								,ARRAY_A);

								if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['koneksi_pokin'][$key_koneksi_level_3])){
									$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['koneksi_pokin'][$key_koneksi_level_3] = [
										'id' => $koneksi_pokin_level_3['id'],
										'parent_pohon_kinerja' => $koneksi_pokin_level_3['parent_pohon_kinerja'],
										'status_koneksi' => $koneksi_pokin_level_3['status_koneksi'],
										'label_parent' => $koneksi_pokin_level_3['label_parent'],
										'id_level_1_parent' => $id_level_1_parent,
										'data_indikator_pokin_pemda' => $data_label_indikator_pokin_pemda
									];
								}
							}
						}

						// pokin level 4
						$pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pohon_kinerja_opd 
							WHERE parent=%d 
								AND level=4
								AND active=1 
								AND id_jadwal=%d 
								AND id_skpd=%d
                            ORDER BY nomor_urut ASC
						", $level_3['id'], $input['periode'], $id_skpd), ARRAY_A);
						if(!empty($pohon_kinerja_level_4)){
							foreach ($pohon_kinerja_level_4 as $level_4) {
								if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']])){
									$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']] = [
										'id' => $level_4['id'],
										'label' => $level_4['label'],
										'level' => $level_4['level'],
										'indikator' => array(),
										'data' => array(),
										'croscutting' => array()
									];
                                    if(empty($level_4['nomor_urut'])){
                                        $level_4['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data']);
                                        $wpdb->update('esakip_pohon_kinerja_opd', array('nomor_urut' => $level_4['nomor_urut']), array(
                                            'id' => $level_4['id']));
                                    }
								}

								// indikator pokin level 4
								$indikator_pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
									SELECT 
										* 
									FROM esakip_pohon_kinerja_opd 
									WHERE parent=%d 
										AND level=4 
										AND active=1 
										AND id_jadwal=%d 
										AND id_skpd=%d
                                    ORDER BY nomor_urut ASC
								", $level_4['id'], $input['periode'], $id_skpd), ARRAY_A);
								if(!empty($indikator_pohon_kinerja_level_4)){
									foreach ($indikator_pohon_kinerja_level_4 as $indikator_level_4) {
										if(!empty($indikator_level_4['label_indikator_kinerja'])){
											if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['indikator'][$indikator_level_4['id']])){
												$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['indikator'][$indikator_level_4['id']] = [
													'id' => $indikator_level_4['id'],
													'parent' => $indikator_level_4['parent'],
													'label_indikator_kinerja' => $indikator_level_4['label_indikator_kinerja'],
													'level' => $indikator_level_4['level']
												];
                                                if(empty($indikator_level_4['nomor_urut'])){
                                                    $indikator_level_4['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['indikator']);
                                                    $wpdb->update('esakip_pohon_kinerja_opd', array('nomor_urut' => $indikator_level_4['nomor_urut']), array(
                                                        'id' => $indikator_level_4['id']));
                                                }
											}
										}
									}
								}
								
								// croscutting pokin level 4
								$croscutting_pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
									SELECT 
										cc.* ,
										pk.id as id_parent,
										pk.level as level_parent,
										pk.label as label_parent
									FROM esakip_croscutting_opd cc
									LEFT JOIN esakip_pohon_kinerja_opd as pk ON cc.parent_croscutting = pk.id
									WHERE cc.parent_pohon_kinerja=%d 
										AND cc.active=1 
									ORDER BY cc.id
								", $level_4['id']), ARRAY_A);

								$croscutting_pohon_kinerja_level_4_pengusul = $wpdb->get_results($wpdb->prepare("
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
								", $id_skpd, $level_4['id']),  ARRAY_A);
								
								if(!empty($croscutting_pohon_kinerja_level_4) && !empty($croscutting_pohon_kinerja_level_4_pengusul)){
									$croscutting_pohon_kinerja_level_4 = array_merge($croscutting_pohon_kinerja_level_4,$croscutting_pohon_kinerja_level_4_pengusul);
								}else if(empty($croscutting_pohon_kinerja_level_4) && !empty($croscutting_pohon_kinerja_level_4_pengusul)){
									$croscutting_pohon_kinerja_level_4 = $croscutting_pohon_kinerja_level_4_pengusul;
								}

								if(!empty($croscutting_pohon_kinerja_level_4)){
									foreach ($croscutting_pohon_kinerja_level_4 as $key_croscutting_level_4 => $croscutting_level_4) {
										$nama_perangkat = '';
										if($croscutting_level_4['is_lembaga_lainnya'] == 1){
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
												", $croscutting_level_4['id_skpd_croscutting'], $tahun_anggaran_sakip),
												ARRAY_A
											);
											$nama_perangkat = $nama_lembaga['nama_lembaga'];
										}else{
											if(!empty($croscutting_level_4['id_skpd_parent'])){
												$this_data_id_skpd = $croscutting_level_4['id_skpd_parent'];
											}else{
												$this_data_id_skpd = $croscutting_level_4['id_skpd_croscutting'];
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

										if(!empty($croscutting_level_4['keterangan'])){
											if(!empty($croscutting_level_4['id_skpd_parent'])){
												$croscutting_opd_lain = 1;
												$id_skpd_view_pokin = $croscutting_level_4['id_skpd_parent'];
											}else{
												$croscutting_opd_lain = 0;
												$id_skpd_view_pokin = $croscutting_level_4['id_skpd_croscutting'];
											}

											$data_parent_tujuan = array();
											$id_level_1_parent = 0;
											if($croscutting_level_4['status_croscutting'] == 1){
												// untuk mendapatkan id parent level 1 suatu opd
												$data_parent_tujuan = array('data' => $this->get_parent_1(array(
													'id' => $croscutting_level_4['id'],
													'level' => $croscutting_level_4['level_parent'],
													'periode' => $input['periode'],
													'tipe' => 'opd',
													'id_parent' => $croscutting_level_4['id_parent'],
													'id_skpd' => $id_skpd_view_pokin
												)));
											}

											if(!empty($data_parent_tujuan)){
												$id_level_1_parent = $data_parent_tujuan['data'];
											}

											if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['croscutting'][$key_croscutting_level_4])){
												$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['croscutting'][$key_croscutting_level_4] = [
													'id' => $croscutting_level_4['id'],
													'parent_pohon_kinerja' => $croscutting_level_4['parent_pohon_kinerja'],
													'keterangan' => $croscutting_level_4['keterangan'],
													'is_lembaga_lainnya' => $croscutting_level_4['is_lembaga_lainnya'],
													'status_croscutting' => $croscutting_level_4['status_croscutting'],
													'label_parent' => $croscutting_level_4['label_parent'],
													'croscutting_opd_lain' => $croscutting_opd_lain,
													'nama_skpd' => $nama_perangkat,
													'id_skpd_view_pokin' => $id_skpd_view_pokin,
													'id_level_1_parent' => $id_level_1_parent
												];
											}
										}
									}
								}

								/**
								 * koneksi pokin pemda dan opd
								 */
								$koneksi_pohon_kinerja_pemda_level_4 = $wpdb->get_results($wpdb->prepare("
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
								", $level_4['id']), ARRAY_A);

								if(!empty($koneksi_pohon_kinerja_pemda_level_4)){
									foreach ($koneksi_pohon_kinerja_pemda_level_4 as $key_koneksi_level_4 => $koneksi_pokin_level_4) {
										$data_parent_tujuan = array();
										$id_level_1_parent = 0;
										if($koneksi_pokin_level_4['status_koneksi'] == 1){
											// untuk mendapatkan id parent ke 1 pemda
											$data_parent_tujuan = array('data' => $this->get_parent_1_koneksi_pokin_pemda_opd(array(
												'id' => $koneksi_pokin_level_4['id'],
												'level' => $koneksi_pokin_level_4['level_parent'],
												'periode' => $input['periode'],
												'id_parent' => $koneksi_pokin_level_4['id_parent']
											)));
										}

										if(!empty($data_parent_tujuan)){
											$id_level_1_parent = $data_parent_tujuan['data'];
										}	

										// get indikator pokin this koneksi pokin
										$data_label_indikator_pokin_pemda = $wpdb->get_results($wpdb->prepare("
											SELECT 
												pk.label_indikator_kinerja as indikator_parent_pemda
											FROM 
												esakip_pohon_kinerja as pk
											WHERE pk.parent=%d
											AND active=1 
										", $koneksi_pokin_level_4['id_parent'])
										,ARRAY_A);

										if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['koneksi_pokin'][$key_koneksi_level_4])){
											$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['koneksi_pokin'][$key_koneksi_level_4] = [
												'id' => $koneksi_pokin_level_4['id'],
												'parent_pohon_kinerja' => $koneksi_pokin_level_4['parent_pohon_kinerja'],
												'status_koneksi' => $koneksi_pokin_level_4['status_koneksi'],
												'label_parent' => $koneksi_pokin_level_4['label_parent'],
												'id_level_1_parent' => $id_level_1_parent,
												'data_indikator_pokin_pemda' => $data_label_indikator_pokin_pemda
											];
										}
									}
								}

								// pokin level 5
								$pohon_kinerja_level_5 = $wpdb->get_results($wpdb->prepare("
									SELECT 
										* 
									FROM esakip_pohon_kinerja_opd 
									WHERE parent=%d 
										AND level=5
										AND active=1 
										AND id_jadwal=%d 
										AND id_skpd=%d
                                    ORDER BY nomor_urut ASC
									", $level_4['id'], $input['periode'], $id_skpd), ARRAY_A);
								if(!empty($pohon_kinerja_level_5)){
									foreach ($pohon_kinerja_level_5 as $level_5) {
										if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']])){
											$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']] = [
												'id' => $level_5['id'],
												'label' => $level_5['label'],
												'level' => $level_5['level'],
												'indikator' => array(),
												'croscutting' => array()
											];
                                            if(empty($level_5['nomor_urut'])){
                                                $level_5['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data']);
                                                $wpdb->update('esakip_pohon_kinerja_opd', array('nomor_urut' => $level_5['nomor_urut']), array(
                                                    'id' => $level_5['id']));
                                            }
										}

										// indikator pokin level 5
										$indikator_pohon_kinerja_level_5 = $wpdb->get_results($wpdb->prepare("
											SELECT 
												* 
											FROM esakip_pohon_kinerja_opd 
											WHERE parent=%d 
												AND level=5 
												AND active=1 
												AND id_jadwal=%d 
												AND id_skpd=%d
											ORDER BY nomor_urut ASC
											", $level_5['id'], $input['periode'], $id_skpd), ARRAY_A);
										if(!empty($indikator_pohon_kinerja_level_5)){
											foreach ($indikator_pohon_kinerja_level_5 as $indikator_level_5) {
												if(!empty($indikator_level_5['label_indikator_kinerja'])){
													if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']]['indikator'][$indikator_level_5['id']])){
														$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']]['indikator'][$indikator_level_5['id']] = [
    														'id' => $indikator_level_5['id'],
    														'parent' => $indikator_level_5['parent'],
    														'label_indikator_kinerja' => $indikator_level_5['label_indikator_kinerja'],
    														'level' => $indikator_level_5['level']
														];
                                                        if(empty($indikator_level_5['nomor_urut'])){
                                                            $indikator_level_5['nomor_urut'] = count($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']]['indikator']);
                                                            $wpdb->update('esakip_pohon_kinerja_opd', array('nomor_urut' => $indikator_level_5['nomor_urut']), array(
                                                                'id' => $indikator_level_5['id']));
                                                        }
													}
												}
											}
										}

										// croscutting pokin level 5
										$croscutting_pohon_kinerja_level_5 = $wpdb->get_results($wpdb->prepare("
											SELECT 
												cc.* ,
												pk.id as id_parent,
												pk.level as level_parent,
												pk.label as label_parent
											FROM esakip_croscutting_opd cc
											LEFT JOIN esakip_pohon_kinerja_opd as pk ON cc.parent_croscutting = pk.id
											WHERE cc.parent_pohon_kinerja=%d 
												AND cc.active=1 
											ORDER BY cc.id
										", $level_5['id']), ARRAY_A);
										
										$croscutting_pohon_kinerja_level_5_pengusul = $wpdb->get_results($wpdb->prepare("
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
										", $id_skpd, $level_5['id']),  ARRAY_A);
										if(!empty($croscutting_pohon_kinerja_level_5) && !empty($croscutting_pohon_kinerja_level_5_pengusul)){
											$croscutting_pohon_kinerja_level_5 = array_merge($croscutting_pohon_kinerja_level_5,$croscutting_pohon_kinerja_level_5_pengusul);
										}else if(empty($croscutting_pohon_kinerja_level_5) && !empty($croscutting_pohon_kinerja_level_5_pengusul)){
											$croscutting_pohon_kinerja_level_5 = $croscutting_pohon_kinerja_level_5_pengusul;
										}

										if(!empty($croscutting_pohon_kinerja_level_5)){
											foreach ($croscutting_pohon_kinerja_level_5 as $key_croscutting_level_5 => $croscutting_level_5) {
												$nama_perangkat = '';
												if($croscutting_level_5['is_lembaga_lainnya'] == 1){
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
														", $croscutting_level_5['id_skpd_croscutting'], $tahun_anggaran_sakip),
														ARRAY_A
													);
													$nama_perangkat = $nama_lembaga['nama_lembaga'];
												}else{
													if(!empty($croscutting_level_5['id_skpd_parent'])){
														$this_data_id_skpd = $croscutting_level_5['id_skpd_parent'];
													}else{
														$this_data_id_skpd = $croscutting_level_5['id_skpd_croscutting'];
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

												if(!empty($croscutting_level_5['keterangan'])){
													if(!empty($croscutting_level_5['id_skpd_parent'])){
														$croscutting_opd_lain = 1;
														$id_skpd_view_pokin = $croscutting_level_5['id_skpd_parent'];
													}else{
														$croscutting_opd_lain = 0;
														$id_skpd_view_pokin = $croscutting_level_5['id_skpd_croscutting'];
													}		

													$data_parent_tujuan = array();
													$id_level_1_parent = 0;
													if($croscutting_level_5['status_croscutting'] == 1){
														// untuk mendapatkan id parent ke 1 suatu opd
														$data_parent_tujuan = array('data' => $this->get_parent_1(array(
															'id' => $croscutting_level_5['id'],
															'level' => $croscutting_level_5['level_parent'],
															'periode' => $input['periode'],
															'tipe' => 'opd',
															'id_parent' => $croscutting_level_5['id_parent'],
															'id_skpd' => $id_skpd_view_pokin
														)));
													}

													if(!empty($data_parent_tujuan)){
														$id_level_1_parent = $data_parent_tujuan['data'];
					
													}

													if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']]['croscutting'][$key_croscutting_level_5])){
														$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']]['croscutting'][$key_croscutting_level_5] = [
															'id' => $croscutting_level_5['id'],
															'parent_pohon_kinerja' => $croscutting_level_5['parent_pohon_kinerja'],
															'keterangan' => $croscutting_level_5['keterangan'],
															'is_lembaga_lainnya' => $croscutting_level_5['is_lembaga_lainnya'],
															'status_croscutting' => $croscutting_level_5['status_croscutting'],
															'label_parent' => $croscutting_level_5['label_parent'],
															'croscutting_opd_lain' => $croscutting_opd_lain,
															'nama_skpd' => $nama_perangkat,
															'id_skpd_view_pokin' => $id_skpd_view_pokin,
															'id_level_1_parent' => $id_level_1_parent
														];
													}
												}
											}
										}

										/**
										 * koneksi pokin pemda dan opd
										 */
										$koneksi_pohon_kinerja_pemda_level_5 = $wpdb->get_results($wpdb->prepare("
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
										", $level_5['id']), ARRAY_A);

										if(!empty($koneksi_pohon_kinerja_pemda_level_5)){
											foreach ($koneksi_pohon_kinerja_pemda_level_5 as $key_koneksi_level_5 => $koneksi_pokin_level_5) {
												$data_parent_tujuan = array();
												$id_level_1_parent = 0;
												if($koneksi_pokin_level_5['status_koneksi'] == 1){
													// untuk mendapatkan id parent ke 1 pemda
													$data_parent_tujuan = array('data' => $this->get_parent_1_koneksi_pokin_pemda_opd(array(
														'id' => $koneksi_pokin_level_5['id'],
														'level' => $koneksi_pokin_level_5['level_parent'],
														'periode' => $input['periode'],
														'id_parent' => $koneksi_pokin_level_5['id_parent']
													)));
												}

												if(!empty($data_parent_tujuan)){
													$id_level_1_parent = $data_parent_tujuan['data'];
												}	
												
												// get indikator pokin this koneksi pokin
												$data_label_indikator_pokin_pemda = $wpdb->get_results($wpdb->prepare("
													SELECT 
														pk.label_indikator_kinerja as indikator_parent_pemda
													FROM 
														esakip_pohon_kinerja as pk
													WHERE pk.parent=%d
													AND active=1 
												", $koneksi_pokin_level_5['id_parent'])
												,ARRAY_A);

												if(empty($data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']]['koneksi_pokin'][$key_koneksi_level_5])){
													$data_all['data'][$level_1['id']]['data'][$level_2['id']]['data'][$level_3['id']]['data'][$level_4['id']]['data'][$level_5['id']]['koneksi_pokin'][$key_koneksi_level_5] = [
														'id' => $koneksi_pokin_level_5['id'],
														'parent_pohon_kinerja' => $koneksi_pokin_level_5['parent_pohon_kinerja'],
														'status_koneksi' => $koneksi_pokin_level_5['status_koneksi'],
														'label_parent' => $koneksi_pokin_level_5['label_parent'],
														'id_level_1_parent' => $id_level_1_parent,
														'data_indikator_pokin_pemda' => $data_label_indikator_pokin_pemda
													];
												}
											}
										}
										// end of foreach data level 5
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
	'nama_page' => 'View Pohon Kinerja OPD'.$skpd['nama_skpd'],
	'content' => '[view_pohon_kinerja_opd periode='. $input['periode'] .']',
	'show_header' => 1,
	'post_status' => 'private'
));
$view_kinerja_asal = $view_kinerja;
$view_kinerja['url'] .= '&id_skpd=' . $id_skpd;

$view_kinerja_pokin_pemda = $this->functions->generatePage(array(
	'nama_page' => 'View Pohon Kinerja',
	'content' => '[view_pohon_kinerja]',
	'show_header' => 1,
	'post_status' => 'private'
));
$html = '';
// echo "<pre>";
// print_r($data_all['data']);
// echo "</pre>";
foreach ($data_all['data'] as $key1 => $level_1) {
	$indikator=array();
	foreach ($level_1['indikator'] as $indikatorlevel1) {
		$indikator[]=$indikatorlevel1['label_indikator_kinerja'];
	}
	$koneksi_pokin_pemda = array();

	foreach ($level_1['koneksi_pokin'] as $koneksi_pokin_level_1) {
		$class_pengusul = "";

		$indikator_pokin_pemda = array();
		$show_indikator_pokin_pemda = '';
		if(!empty($koneksi_pokin_level_1['data_indikator_pokin_pemda'])){
			$no = 1;
			foreach ($koneksi_pokin_level_1['data_indikator_pokin_pemda'] as $key => $v_indikator_pemda) {
				$indikator_pokin_pemda[]= $v_indikator_pemda['indikator_parent_pemda'];
				$no++;
			}
		}
		if(!empty($indikator_pokin_pemda)){
			$show_indikator_pokin_pemda .= "( ". implode(", ", $indikator_pokin_pemda) . " )";
		}

		if($koneksi_pokin_level_1['id_level_1_parent'] !== 0){
			$nama_skpd = "<a href='" . $view_kinerja_pokin_pemda['url'] . "&id=" . $koneksi_pokin_level_1['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>". ucfirst($koneksi_pokin_level_1['label_parent']) ." " . $show_indikator_pokin_pemda . "</a>";
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

		$show_nama_skpd = $nama_skpd . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_koneksi.'</span> ';
		
		$class_koneksi_pokin_vertikal = '';

		// $detail = "<a href='javascript:void(0)' data-id='". $koneksi_pokin_level_1['id'] ."' class='detail-koneksi-pokin text-primary' onclick='detail_koneksi_pokin(" . $koneksi_pokin_level_1['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a>";

		$koneksi_pokin[]= '<div class="koneksi-pokin-isi '. $class_pengusul .' '. $class_koneksi_pokin_vertikal .'"><div style="margin-top: 10px;font-weight: 500;">'. $show_nama_skpd .'</div></div>';
	}

	$show_koneksi_pokin = '';
	if(!empty($koneksi_pokin)){
		$show_koneksi_pokin .='<div class="text-center label-koneksi-pokin">KONEKSI POKIN ' . strtoupper($nama_pemda) . '</div>';
		$show_koneksi_pokin .=implode("", $koneksi_pokin);
	}
	$html.='
	<tr>
		<td class="level1" style="background: #efd655;"><a href="'.$view_kinerja['url'].'&id='.$level_1['id'].'&id_jadwal='.$input['periode'].'" target="_blank">'.$level_1['label'].'</a></td>
		<td class="indikator" style="background: #b5d9ea;">'.implode("<hr/>", $indikator).'</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
    <td></td>
    <td></td>
	</tr>';

	if(!empty($show_koneksi_pokin)){
		$html.='
		<tr>
			<td class="koneksi-pokin" style="background-color: #FDFFB6;" colspan="2">' . $show_koneksi_pokin . '</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>';
	}
	foreach (array_values($level_1['data']) as $key2 => $level_2) {
		$indikator=array();
		foreach ($level_2['indikator'] as $indikatorlevel2) {
			$indikator[]=$indikatorlevel2['label_indikator_kinerja'];
		}
		$croscutting = array();
		foreach ($level_2['croscutting'] as $croscuttinglevel2) {
			$class_pengusul = "";
			// $link_pengusul = $croscuttinglevel2['nama_skpd'];
			if($croscuttinglevel2['croscutting_opd_lain'] == 1){
				$class_pengusul = "croscutting-pengusul";
			}	

			$nama_skpd = $croscuttinglevel2['nama_skpd'];
			if($croscuttinglevel2['id_level_1_parent'] !== 0 && $croscuttinglevel2['is_lembaga_lainnya'] != 1){
				$nama_skpd = "<a href='" . $view_kinerja_asal['url'] . "&id_skpd=" . $croscuttinglevel2['id_skpd_view_pokin']  . "&id=" . $croscuttinglevel2['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $croscuttinglevel2['nama_skpd'] . "</a>";
			}
			
			switch ($croscuttinglevel2['status_croscutting']) {
				case '1':
					$status_croscutting = 'Disetujui';
					$label_color = 'success text-white';
					break;
				case '2':
					$status_croscutting = 'Ditolak';
					$label_color = 'danger text-white';
					break;
				
				default:
					$status_croscutting = 'Menunggu';
					$label_color = 'secondary text-white';
					break;
			}

			$show_nama_skpd = $nama_skpd . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_croscutting.'</span> ';
			
			$class_cc_vertikal = '';
			if($croscuttinglevel2['is_lembaga_lainnya'] == 1){
				// $label_parent = "?";
				$class_cc_vertikal = "croscutting-lembaga-vertikal";
			}

			$detail = "<a href='javascript:void(0)' data-id='". $croscuttinglevel2['id'] ."' class='detail-cc text-primary' onclick='detail_cc(" . $croscuttinglevel2['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a>";

			$croscutting[]= '<div class="croscutting-isi '. $class_pengusul .' '. $class_cc_vertikal .'"><div>'. ucfirst($croscuttinglevel2['label_parent']) ."</div><div style='margin-top: 10px;font-weight: 500;'>". $show_nama_skpd .' '. $detail .'</div></div>';
		}

		$show_croscutting = '';
		if(!empty($croscutting)){
			$show_croscutting .='<div class="text-center label-croscutting">CROSCUTTING</div>';
			$show_croscutting .=implode("", $croscutting);
		}

		// show koneksi pokin opd pemda
		$koneksi_pokin = array();
		foreach ($level_2['koneksi_pokin'] as $koneksi_pokin_level_2) {
			$class_pengusul = "";
			
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
			
			$indikator_pokin_pemda = array();
			$show_indikator_pokin_pemda = '';
			if(!empty($koneksi_pokin_level_2['data_indikator_pokin_pemda'])){
				$no = 1;
				foreach ($koneksi_pokin_level_2['data_indikator_pokin_pemda'] as $key => $v_indikator_pemda) {
					$indikator_pokin_pemda[]= $v_indikator_pemda['indikator_parent_pemda'];
					$no++;
				}
			}
			if(!empty($indikator_pokin_pemda)){
				$show_indikator_pokin_pemda .= "( ". implode(", ", $indikator_pokin_pemda) . " )";
			}

			if($koneksi_pokin_level_2['id_level_1_parent'] !== 0){
				$nama_skpd = "<a href='" . $view_kinerja_pokin_pemda['url'] . "&id=" . $koneksi_pokin_level_2['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>". ucfirst($koneksi_pokin_level_2['label_parent']) ." " . $show_indikator_pokin_pemda . "</a>";
			}

			$show_nama_skpd = $nama_skpd . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_koneksi.'</span> ';
			
			$class_koneksi_pokin_vertikal = '';

			// $detail = "<a href='javascript:void(0)' data-id='". $koneksi_pokin_level_2['id'] ."' class='detail-koneksi-pokin text-primary' onclick='detail_koneksi_pokin(" . $koneksi_pokin_level_2['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a>";

			$koneksi_pokin[]= '<div class="koneksi-pokin-isi '. $class_pengusul .' '. $class_koneksi_pokin_vertikal .'"><div style="margin-top: 10px;font-weight: 500;">'. $show_nama_skpd .'</div></div>';
		}

		$show_koneksi_pokin = '';
		if(!empty($koneksi_pokin)){
			$show_koneksi_pokin .='<div class="text-center label-koneksi-pokin">KONEKSI POKIN ' . strtoupper($nama_pemda) . '</div>';
			$show_koneksi_pokin .=implode("", $koneksi_pokin);
		}

		$html.='
		<tr>
			<td></td>
			<td></td>
			<td class="level2" style="background: #fe7373;">'.$level_2['label'].'</td>
			<td class="indikator" style="background: #b5d9ea;">'.implode("<hr/>", $indikator).'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>';

		if(!empty($show_croscutting)){
			$html.='
			<tr>
				<td></td>
				<td></td>
				<td class="croscutting" style="background-color: #FFC6FF;" colspan="2">' . $show_croscutting . '</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';
		}
		if(!empty($show_koneksi_pokin)){
			$html.='
			<tr>
				<td></td>
				<td></td>
				<td class="koneksi-pokin" style="background-color: #FDFFB6;" colspan="2">' . $show_koneksi_pokin . '</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';
		}
		foreach (array_values($level_2['data']) as $key3 => $level_3) {
			$indikator=array();
			foreach ($level_3['indikator'] as $indikatorlevel3) {
				$indikator[]=$indikatorlevel3['label_indikator_kinerja'];
			}
			$croscutting = array();
			foreach ($level_3['croscutting'] as $croscuttinglevel3) {
				$class_pengusul = "";
				if($croscuttinglevel3['croscutting_opd_lain'] == 1){
					$class_pengusul = "croscutting-pengusul";
				}	
				$nama_skpd = $croscuttinglevel3['nama_skpd'];
				if($croscuttinglevel3['id_level_1_parent'] !== 0 && $croscuttinglevel3['is_lembaga_lainnya'] != 1){
					$nama_skpd = "<a href='" . $view_kinerja_asal['url'] . "&id_skpd=" . $croscuttinglevel3['id_skpd_view_pokin']  . "&id=" . $croscuttinglevel3['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $croscuttinglevel3['nama_skpd'] . "</a>";
				}
				switch ($croscuttinglevel3['status_croscutting']) {
					case '1':
						$status_croscutting = 'Disetujui';
						$label_color = 'success text-white';
						break;
					case '2':
						$status_croscutting = 'Ditolak';
						$label_color = 'danger text-white';
						break;
					
					default:
						$status_croscutting = 'Menunggu';
						$label_color = 'secondary text-white';
						break;
				}
				$show_nama_skpd = $nama_skpd . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_croscutting.'</span> ';
				
				$class_cc_vertikal = '';
				if($croscuttinglevel3['is_lembaga_lainnya'] == 1){
					// $label_parent = "?";
					$class_cc_vertikal = "croscutting-lembaga-vertikal";
				}

				$detail = "<a href='javascript:void(0)' data-id='". $croscuttinglevel3['id'] ."' class='detail-cc text-primary' onclick='detail_cc(" . $croscuttinglevel3['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a>";

				$croscutting[]= '<div class="croscutting-isi '. $class_pengusul .' '. $class_cc_vertikal .'"><div>'. ucfirst($croscuttinglevel3['label_parent']) ."</div><div style='margin-top: 10px;font-weight: 500;'>". $show_nama_skpd .' '. $detail .'</div></div>';
			}
			
			$show_croscutting = '';
			if(!empty($croscutting)){
				$show_croscutting .='<div class="text-center label-croscutting">CROSCUTTING</div>';
				$show_croscutting .=implode("", $croscutting);
			}

			// show koneksi pokin opd pemda
			$koneksi_pokin = array();
			foreach ($level_3['koneksi_pokin'] as $koneksi_pokin_level_3) {
				$class_pengusul = "";

				$indikator_pokin_pemda = array();
				$show_indikator_pokin_pemda = '';
				if(!empty($koneksi_pokin_level_3['data_indikator_pokin_pemda'])){
					$no = 1;
					foreach ($koneksi_pokin_level_3['data_indikator_pokin_pemda'] as $key => $v_indikator_pemda) {
						$indikator_pokin_pemda[]= $v_indikator_pemda['indikator_parent_pemda'];
						$no++;
					}
				}
				if(!empty($indikator_pokin_pemda)){
					$show_indikator_pokin_pemda .= "( ". implode(", ", $indikator_pokin_pemda) . " )";
				}

				if($koneksi_pokin_level_3['id_level_1_parent'] !== 0){
					$nama_skpd = "<a href='" . $view_kinerja_pokin_pemda['url'] . "&id=" . $koneksi_pokin_level_3['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>". ucfirst($koneksi_pokin_level_3['label_parent']) ." " . $show_indikator_pokin_pemda . "</a>";
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

				$show_nama_skpd = $nama_skpd . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_koneksi.'</span> ';
				
				$class_koneksi_pokin_vertikal = '';

				// $detail = "<a href='javascript:void(0)' data-id='". $koneksi_pokin_level_3['id'] ."' class='detail-koneksi-pokin text-primary' onclick='detail_koneksi_pokin(" . $koneksi_pokin_level_3['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a>";

				$koneksi_pokin[]= '<div class="koneksi-pokin-isi '. $class_pengusul .' '. $class_koneksi_pokin_vertikal .'"><div style="margin-top: 10px;font-weight: 500;">'. $show_nama_skpd .'</div></div>';
			}

			$show_koneksi_pokin = '';
			if(!empty($koneksi_pokin)){
				$show_koneksi_pokin .='<div class="text-center label-koneksi-pokin">KONEKSI POKIN ' . strtoupper($nama_pemda) . '</div>';
				$show_koneksi_pokin .=implode("", $koneksi_pokin);
			}
			$html.='
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="level3" style="background: #57b2ec;">'.$level_3['label'].'</td>
				<td class="indikator" style="background: #b5d9ea;">'.implode("<hr/>", $indikator).'</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';

			if(!empty($show_croscutting)){
				$html.='
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="croscutting" style="background-color: #FFC6FF;" colspan="2">' . $show_croscutting . '</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>';
			}
			if(!empty($show_koneksi_pokin)){
				$html.='
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="koneksi-pokin" style="background-color: #FDFFB6;" colspan="2">' . $show_koneksi_pokin . '</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>';
			}
			foreach (array_values($level_3['data']) as $key4 => $level_4) {
				$indikator=array();
				foreach ($level_4['indikator'] as $indikatorlevel4) {
					$indikator[]=$indikatorlevel4['label_indikator_kinerja'];
				}
				$croscutting = array();
				foreach ($level_4['croscutting'] as $croscuttinglevel4) {
					$class_pengusul = "";
					if($croscuttinglevel4['croscutting_opd_lain'] == 1){
						$class_pengusul = "croscutting-pengusul";
					}
					$nama_skpd = $croscuttinglevel4['nama_skpd'];
					if($croscuttinglevel4['id_level_1_parent'] !== 0 && $croscuttinglevel4['is_lembaga_lainnya'] != 1){
						$nama_skpd = "<a href='" . $view_kinerja_asal['url'] . "&id_skpd=" . $croscuttinglevel4['id_skpd_view_pokin']  . "&id=" . $croscuttinglevel4['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $croscuttinglevel4['nama_skpd'] . "</a>";
					}
					switch ($croscuttinglevel4['status_croscutting']) {
						case '1':
							$status_croscutting = 'Disetujui';
							$label_color = 'success text-white';
							break;
						case '2':
							$status_croscutting = 'Ditolak';
							$label_color = 'danger text-white';
							break;
						
						default:
							$status_croscutting = 'Menunggu';
							$label_color = 'secondary text-white';
							break;
					}
					$show_nama_skpd = $nama_skpd . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_croscutting.'</span> ';
					
					$class_cc_vertikal = '';
					if($croscuttinglevel4['is_lembaga_lainnya'] == 1){
						// $label_parent = "?";
						$class_cc_vertikal = "croscutting-lembaga-vertikal";
					}

					$detail = "<a href='javascript:void(0)' data-id='". $croscuttinglevel4['id'] ."' class='detail-cc text-primary' onclick='detail_cc(" . $croscuttinglevel4['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a>";

					$croscutting[]= '<div class="croscutting-isi '. $class_pengusul .' '. $class_cc_vertikal .'"><div>'. ucfirst($croscuttinglevel4['label_parent']) ."</div><div style='margin-top: 10px;font-weight: 500;'>". $show_nama_skpd .' '. $detail .'</div></div>';
				}
				
				$show_croscutting = '';
				if(!empty($croscutting)){
					$show_croscutting .='<div class="text-center label-croscutting">CROSCUTTING</div>';
					$show_croscutting .=implode("", $croscutting);
				}

				// show koneksi pokin opd pemda
				$koneksi_pokin = array();
				foreach ($level_4['koneksi_pokin'] as $koneksi_pokin_level_4) {
					$class_pengusul = "";

					$indikator_pokin_pemda = array();
					$show_indikator_pokin_pemda = '';
					if(!empty($koneksi_pokin_level_4['data_indikator_pokin_pemda'])){
						$no = 1;
						foreach ($koneksi_pokin_level_4['data_indikator_pokin_pemda'] as $key => $v_indikator_pemda) {
							$indikator_pokin_pemda[]= $v_indikator_pemda['indikator_parent_pemda'];
							$no++;
						}
					}
					if(!empty($indikator_pokin_pemda)){
						$show_indikator_pokin_pemda .= "( ". implode(", ", $indikator_pokin_pemda) . " )";
					}

					if($koneksi_pokin_level_4['id_level_1_parent'] !== 0){
						$nama_skpd = "<a href='" . $view_kinerja_pokin_pemda['url'] . "&id=" . $koneksi_pokin_level_4['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>". ucfirst($koneksi_pokin_level_4['label_parent']) ." " . $show_indikator_pokin_pemda . "</a>";
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

					$show_nama_skpd = $nama_skpd . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_koneksi.'</span> ';
					
					$class_koneksi_pokin_vertikal = '';

					// $detail = "<a href='javascript:void(0)' data-id='". $koneksi_pokin_level_4['id'] ."' class='detail-koneksi-pokin text-primary' onclick='detail_koneksi_pokin(" . $koneksi_pokin_level_4['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a>";

					$koneksi_pokin[]= '<div class="koneksi-pokin-isi '. $class_pengusul .' '. $class_koneksi_pokin_vertikal .'"><div style="margin-top: 10px;font-weight: 500;">'. $show_nama_skpd .'</div></div>';
				}

				$show_koneksi_pokin = '';
				if(!empty($koneksi_pokin)){
					$show_koneksi_pokin .='<div class="text-center label-koneksi-pokin">KONEKSI POKIN ' . strtoupper($nama_pemda) . '</div>';
					$show_koneksi_pokin .=implode("", $koneksi_pokin);
				}
				$html.='
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="level4" style="background: #c979e3;">' . $level_4['label'] . '</td>
					<td class="indikator" style="background: #b5d9ea;">'.implode("<hr/>", $indikator).'</td>
					<td></td>
					<td></td>
				</tr>';
				
				if(!empty($show_croscutting)){
					$html.='
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="croscutting" style="background-color: #FFC6FF;" colspan="2">' . $show_croscutting . '</td>
						<td></td>
						<td></td>
					</tr>';
				}
				if(!empty($show_koneksi_pokin)){
					$html.='
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="koneksi-pokin" style="background-color: #FDFFB6;" colspan="2">' . $show_koneksi_pokin . '</td>
						<td></td>
						<td></td>
					</tr>';
				}
				
				foreach (array_values($level_4['data']) as $key5 => $level_5) {
					$indikator=array();
					foreach ($level_5['indikator'] as $indikatorlevel5) {
						$indikator[]=$indikatorlevel5['label_indikator_kinerja'];
					}
					$html.='
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="level5" style="background: #CAFFBF;">'.$level_5['label'].'</td>
						<td class="indikator" style="background: #b5d9ea;">'.implode("<hr/>", $indikator).'</td>
					</tr>';

					// show croscutting
					$croscutting5 = array();
					foreach ($level_5['croscutting'] as $croscuttinglevel5) {
						$class_pengusul = "";
						if($croscuttinglevel5['croscutting_opd_lain'] == 1){
							$class_pengusul = "croscutting-pengusul";
						}
							
						$nama_skpd = $croscuttinglevel5['nama_skpd'];
						if($croscuttinglevel5['id_level_1_parent'] !== 0 && $croscuttinglevel5['is_lembaga_lainnya'] != 1){
							$nama_skpd = "<a href='" . $view_kinerja_asal['url'] . "&id_skpd=" . $croscuttinglevel5['id_skpd_view_pokin']  . "&id=" . $croscuttinglevel5['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>" . $croscuttinglevel5['nama_skpd'] . "</a>";
						}
						switch ($croscuttinglevel5['status_croscutting']) {
							case '1':
								$status_croscutting = 'Disetujui';
								$label_color = 'success text-white';
								break;
							case '2':
								$status_croscutting = 'Ditolak';
								$label_color = 'danger text-white';
								break;
							
							default:
								$status_croscutting = 'Menunggu';
								$label_color = 'secondary text-white';
								break;
						}
						$show_nama_skpd = $nama_skpd . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_croscutting.'</span> ';
						
						$class_cc_vertikal = '';
						if($croscuttinglevel5['is_lembaga_lainnya'] == 1){
							// $label_parent = "?";
							$class_cc_vertikal = "croscutting-lembaga-vertikal";
						}

						$detail = "<a href='javascript:void(0)' data-id='". $croscuttinglevel5['id'] ."' class='detail-cc text-primary' onclick='detail_cc(" . $croscuttinglevel5['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a>";

						$croscutting5[]= '<div class="croscutting-isi '. $class_pengusul .' '. $class_cc_vertikal .'"><div>'. ucfirst($croscuttinglevel5['label_parent']) ."</div><div style='margin-top: 10px;font-weight: 500;'>". $show_nama_skpd .' '. $detail .'</div></div>';
					}

					$show_croscutting5 = '';
					if(!empty($croscutting5)){
						$show_croscutting5 .='<div class="text-center label-croscutting">CROSCUTTING</div>';
						$show_croscutting5 .=implode('', $croscutting5);
					}

					if(!empty($show_croscutting5)){
						$html.='
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="croscutting" style="background-color: #FFC6FF;" colspan="2">' . $show_croscutting5 . '</td>
						</tr>';
					}

					// show koneksi pokin opd pemda
					$koneksi_pokin = array();
					foreach ($level_5['koneksi_pokin'] as $koneksi_pokin_level_5) {
						$class_pengusul = "";

						$indikator_pokin_pemda = array();
						$show_indikator_pokin_pemda = '';
						if(!empty($koneksi_pokin_level_5['data_indikator_pokin_pemda'])){
							$no = 1;
							foreach ($koneksi_pokin_level_5['data_indikator_pokin_pemda'] as $key => $v_indikator_pemda) {
								$indikator_pokin_pemda[]= $v_indikator_pemda['indikator_parent_pemda'];
								$no++;
							}
						}
						if(!empty($indikator_pokin_pemda)){
							$show_indikator_pokin_pemda .= "( ". implode(", ", $indikator_pokin_pemda) . " )";
						}

						if($koneksi_pokin_level_5['id_level_1_parent'] !== 0){
							$nama_skpd = "<a href='" . $view_kinerja_pokin_pemda['url'] . "&id=" . $koneksi_pokin_level_5['id_level_1_parent'] . "&id_jadwal=" . $input['periode'] . "' target='_blank'>". ucfirst($koneksi_pokin_level_5['label_parent']) ."" . $show_indikator_pokin_pemda . "</a>";
						}
						
						switch ($koneksi_pokin_level_5['status_koneksi']) {
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

						$show_nama_skpd = $nama_skpd . ' <span class="badge bg-'. $label_color .'" style="padding: .5em;">'. $status_koneksi.'</span> ';
						
						$class_koneksi_pokin_vertikal = '';

						// $detail = "<a href='javascript:void(0)' data-id='". $koneksi_pokin_level_5['id'] ."' class='detail-koneksi-pokin text-primary' onclick='detail_koneksi_pokin(" . $koneksi_pokin_level_5['id'] . "); return false;'  title='Detail'><i class='dashicons dashicons-info'></i></a>";

						$koneksi_pokin[]= '<div class="koneksi-pokin-isi '. $class_pengusul .' '. $class_koneksi_pokin_vertikal .'"><div style="margin-top: 10px;font-weight: 500;">'. $show_nama_skpd .'</div></div>';
					}

					$show_koneksi_pokin = '';
					if(!empty($koneksi_pokin)){
						$show_koneksi_pokin .='<div class="text-center label-koneksi-pokin">KONEKSI POKIN ' . strtoupper($nama_pemda) . '</div>';
						$show_koneksi_pokin .=implode("", $koneksi_pokin);
					}

					if(!empty($show_koneksi_pokin)){
						$html.='
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="koneksi-pokin" style="background-color: #FDFFB6;" colspan="2">' . $show_koneksi_pokin . '</td>
						</tr>';
					}

				}
			}
		}
	}
}

$data_notifikasi_croscutting = $wpdb->get_results($wpdb->prepare("
	SELECT 
		cc.*,
		pk.id_skpd as id_skpd_asal 
	FROM 
		esakip_croscutting_opd as cc 
	JOIN 
		esakip_pohon_kinerja_opd as pk 
	ON 
		cc.parent_pohon_kinerja=pk.id 
	WHERE pk.id_jadwal=%d 
	AND cc.active=1 
	AND pk.active=1
	AND cc.status_croscutting=0
	AND cc.id_skpd_croscutting=%d
",$input['periode'],$id_skpd)
,ARRAY_A);

$html_notifikasi_cc = '';
$no_notif_cc = 1;
if(!empty($data_notifikasi_croscutting)){
	foreach ($data_notifikasi_croscutting as $k_notif_cc => $v_notif_cc) {
		$nama_perangkat = '';
		if($v_notif_cc['is_lembaga_lainnya'] == 1){
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
				", $v_notif_cc['id_skpd_asal'], $tahun_anggaran_sakip),
				ARRAY_A
			);
			$nama_perangkat = $nama_lembaga['nama_lembaga'];
		}else{
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
				", $v_notif_cc['id_skpd_asal'], $tahun_anggaran_sakip),
				ARRAY_A
			);
			$nama_perangkat = $nama_skpd['nama_skpd'];
		}
		$aksi = '<a href="javascript:void(0)" class="btn btn-sm btn-success verifikasi-croscutting" data-id="' . $v_notif_cc['id'] . '" data-skpd-asal="'. $nama_perangkat .'" data-keterangan-asal="'. $v_notif_cc['keterangan'] .'" href="#" title="Verifikasi Croscutting"><span class="dashicons dashicons-yes"></span></a>';

		$html_notifikasi_cc .='
			<tr>
				<td>'. $no_notif_cc++ .'</td>
				<td>'. $v_notif_cc['keterangan'] .'</td>
				<td>'. $nama_perangkat .'</td>
				<td>'. $aksi .'</td>
			</tr>';
	}
}

$data_notifikasi_koneksi_pokin_pemda = $wpdb->get_results($wpdb->prepare("
	SELECT 
		koneksi.*,
		pk.id as id_parent_pemda,
		pk.label as label_parent_pemda
	FROM 
		esakip_koneksi_pokin_pemda_opd as koneksi
	LEFT JOIN esakip_pohon_kinerja as pk
		ON koneksi.parent_pohon_kinerja = pk.id
	WHERE koneksi.active=1 
	AND koneksi.status_koneksi=0
	AND koneksi.id_skpd_koneksi=%d
	AND pk.active=1
", $id_skpd)
,ARRAY_A);

$html_notifikasi_koneksi_pokin = '';
$no_notif_koneksi_pokin = 1;
if(!empty($data_notifikasi_koneksi_pokin_pemda)){
	foreach ($data_notifikasi_koneksi_pokin_pemda as $k_notif_koneksi => $v_notif_koneksi) {
		$data_label_indikator_pokin_pemda = $wpdb->get_results($wpdb->prepare("
			SELECT 
				pk.label_indikator_kinerja as indikator_parent_pemda
			FROM 
				esakip_pohon_kinerja as pk
			WHERE pk.parent=%d
			AND active=1 
		", $v_notif_koneksi['id_parent_pemda'])
		,ARRAY_A);

		$indikator_pokin_pemda = array();
		$show_indikator_pokin_pemda = '';
		if(!empty($data_label_indikator_pokin_pemda)){
			$no = 1;
			foreach ($data_label_indikator_pokin_pemda as $key => $v_indikator_pemda) {
				$class_indikator_pemda = count($data_label_indikator_pokin_pemda) > 1 ? 'koneksi-indikator-pemda-isi' : '';
				$class_indikator_pemda_1 = $no == 1 && $class_indikator_pemda != '' ? 'border-style: none;margin-top: -8px;' : '';
				$indikator_pokin_pemda[]= '<div class="'. $class_indikator_pemda .'" style="'. $class_indikator_pemda_1 .'">'. $v_indikator_pemda['indikator_parent_pemda'] .'</div>';
				$no++;
			}
		}
		if(!empty($indikator_pokin_pemda)){
			$show_indikator_pokin_pemda .=implode("", $indikator_pokin_pemda);
		}


		$aksi = '<a href="javascript:void(0)" class="btn btn-sm btn-success verifikasi-koneksi-pokin-pemda" data-id="' . $v_notif_koneksi['id'] . '" href="#" title="Verifikasi Koneksi Pohon Kinerja"><span class="dashicons dashicons-yes"></span></a>';

		$html_notifikasi_koneksi_pokin .='
			<tr>
				<td>'. $no_notif_koneksi_pokin++ .'</td>
				<td>'. $v_notif_koneksi['label_parent_pemda'] .'</td>
				<td>'. $show_indikator_pokin_pemda .'</td>
				<td>'. $aksi .'</td>
			</tr>';
	}
}

$data_level_pokin = $wpdb->get_results(
	$wpdb->prepare("
		SELECT 
			* 
		FROM 
			esakip_pohon_kinerja_opd 
		WHERE 
			id_jadwal=%d 
		AND active=1 
		AND id_skpd=%d 
		AND level IN (1,2,3,4,5) 
		AND label_indikator_kinerja IS NULL
		ORDER BY level
	", $input['periode'],$id_skpd),
	ARRAY_A
);

$option_tautan_pokin = "<option value=''>Pilih Tautan Level Pokin</option>";
if(!empty($data_level_pokin)){
	foreach ($data_level_pokin as $v_pokin) {
		if($v_pokin['level'] == 1){
			continue;
		}
		$option_tautan_pokin .="<option value='" . $v_pokin['id'] . "'>Level " . $v_pokin['level'] . " | " . $v_pokin['label'] . "</option>";
	}
}

$option_tautan_pokin_koneksi = "<option value=''>Pilih Tautan Level Pokin</option>";
if(!empty($data_level_pokin)){
	foreach ($data_level_pokin as $v_pokin) {
		$option_tautan_pokin_koneksi .="<option value='" . $v_pokin['id'] . "'>Level " . $v_pokin['level'] . " | " . $v_pokin['label'] . "</option>";
	}
}

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
$is_administrator = in_array('administrator', $user_roles);

    $admin_role_pemda = array(
        'admin_bappeda',
        'admin_ortala'
    );

    $this_jenis_role = (in_array($user_roles[0], $admin_role_pemda)) ? true : false ;

$cek_settingan_menu = $wpdb->get_var(
	$wpdb->prepare(
	"SELECT 
		jenis_role
	FROM esakip_menu_dokumen 
	WHERE nama_dokumen='Penyusunan Pohon Kinerja'
	  AND active = 1
	  AND id_jadwal=%d
",$input['periode'])
);

$hak_akses_user = ($this_jenis_role || $cek_settingan_menu == 3 || $is_administrator) ? 1 : 0;

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
	.indikator {
		background: #b5d9ea;
	}

	.croscutting {
		background-color: #FFC6FF;
		padding-bottom: 0;
	}

	.croscutting-pengusul {
		background-color: #9BF6FF;
	}

	.label-croscutting {
		padding: 0 .7em .7em;
	}
	.croscutting-1 {
		margin: 0px -16px 0px;
		padding:  .5em .9em;
		border-width: 1px 0 0;
		border-style: solid;
		border-color: gray;
	}
	.croscutting-isi {
		margin: 0px -16px;
		padding: .7em .9em;
		border-width: 1px 0 0;
		border-style: solid;
		border-color: gray;
	}

	a.btn{
		text-decoration: none !important;
	}

	.penyusunan_pohon_kinerja_opd thead{
        position: sticky;
        top: -6px;
        background: #ffc491;
    }

	.croscutting-lembaga-vertikal{
		background-color: #f1b82a;
	}

	.detail-cc .dashicons{
		text-decoration: none;
		vertical-align: text-bottom !important;
		font-size: 23px !important;
	}

	.detail_crocutting{
		background-color: #f4f6f8 !important;
		color: #111827 !important;
	}
    #modal-pokin .modal-body {
        max-height: 70vh;
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
		padding:  .5em .9em;
		border-width: 1px 0 0;
		border-style: solid;
		border-color: gray;
	}
	.koneksi-pokin-isi {
		margin: 0px -16px;
		padding: .7em .9em;
		border-width: 1px 0 0;
		border-style: solid;
		border-color: gray;
	}
	
	.detail-koneksi-pokin .dashicons{
		text-decoration: none;
		vertical-align: text-bottom !important;
		font-size: 23px !important;
	}
	.koneksi-indikator-pemda-isi {
		margin: 0px -10px;
		padding: .7em .9em;
		border-width: 1px 0 0;
		border-style: solid;
		border-color: gray;
	}
</style>
<h3 style="text-align: center; margin-top: 10px; font-weight: bold;">Penyusunan Pohon Kinerja <br><?php echo $skpd['nama_skpd'] ?><br><?php echo $periode['nama_jadwal_renstra'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h3><br>

<?php if(!empty($data_notifikasi_croscutting)): ?>
<h4 style="text-align: center; margin-top: 10px; font-weight: bold;margin-bottom: .5em;">Notifikasi Croscutting</h4>
<div title="Notifikasi Croscutting" style="padding: 5px; overflow: auto; display:flex; justify-content:center;">
	<table id="table_notifikasi_croscutting" style="width: 50em;text-align: center;">
		<thead>
			<tr>
				<th>No</th>
				<th>Keterangan</th>
				<th>Perangkat Dearah Asal</th>
				<th style="min-width: 10em;">Aksi</th>
			</tr>
		</thead>
		<?php echo $html_notifikasi_cc; ?>
		<tbody>
		</tbody>
	</table>
</div>
<?php endif; ?>

<?php if(!empty($data_notifikasi_koneksi_pokin_pemda)): ?>
<h4 style="text-align: center; margin-top: 80px; font-weight: bold;margin-bottom: .5em;">Notifikasi Koneksi Pohon Kinerja <?php echo $nama_pemda; ?></h4>
<div title="Notifikasi Koneksi Pohon Kinerja <?php echo $nama_pemda?>" style="padding: 5px; overflow: auto; display:flex; justify-content:center;">
	<table id="table_notifikasi_koneksi_pokin_pemda" style="width: 50em;text-align: center;">
		<thead>
			<tr>
				<th>No</th>
				<th>Label Pohon Kinerja</th>
				<th>Indikator Pohon Kinerja</th>
				<th style="min-width: 10em;">Aksi</th>
			</tr>
		</thead>
		<?php echo $html_notifikasi_koneksi_pokin; ?>
		<tbody>
		</tbody>
	</table>
</div>
<?php endif; ?>

<?php if (!$is_admin_panrb): ?>
<div id="action" class="action-section"></div>
<?php endif; ?>
<div style="padding: 5px; overflow: auto; height: 100vh;">
	<table id="cetak" title="Penyusunan Pohon Kinerja Perangkat Daerah" class="penyusunan_pohon_kinerja_opd">
		<thead style="background: #ffc491;">
			<tr>
				<th>Level 1</th>
				<th>Indikator Kinerja</th>
				<th>Level 2</th>
				<th>Indikator Kinerja</th>
				<th>Level 3</th>
				<th>Indikator Kinerja</th>
				<th>Level 4</th>
				<th>Indikator Kinerja</th>
				<th>Level 5</th>
				<th>Indikator Kinerja</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $html; ?>
		</tbody>
	</table>
</div>
<div class="hide-print" id="catatan_dokumentasi" style="max-width: 1200px; margin: auto;">
	<h4 style="margin: 30px 0 10px; font-weight: bold;">Catatan Dokumentasi:</h4>
	<ul>
		<li>Pohon kinerja bisa dilihat ketika data terisi minimal sampai dengan level ke-2.</li>
	</ul>
</div>

<div class="modal fade" id="modal-pokin" role="dialog" data-backdrop="static" aria-hidden="true">'
    <div class="modal-dialog" style="max-width: 1200px;" role="document">
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
					    <a class="nav-item nav-link" id="nav-level-5-tab" data-toggle="tab" href="#nav-level-5" role="tab" aria-controls="nav-level-5" aria-selected="false">Level 5</a>
				  	</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
				  	<div class="tab-pane fade show active" id="nav-level-1" role="tabpanel" aria-labelledby="nav-level-1-tab"></div>
				  	<div class="tab-pane fade" id="nav-level-2" role="tabpanel" aria-labelledby="nav-level-2-tab"></div>
				  	<div class="tab-pane fade" id="nav-level-3" role="tabpanel" aria-labelledby="nav-level-3-tab"></div>
				  	<div class="tab-pane fade" id="nav-level-4" role="tabpanel" aria-labelledby="nav-level-4-tab"></div>
				  	<div class="tab-pane fade" id="nav-level-5" role="tabpanel" aria-labelledby="nav-level-5-tab"></div>
				</div>
			</div>
		</div>
    </div>
</div>

<!-- Modal crud -->
<div class="modal fade" id="modal-crud" data-backdrop="static"  role="dialog" aria-labelledby="modal-crud-label" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-scrollable" role="document">
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

<!-- Modal crud croscutting -->
<div class="modal fade" id="modal-croscutting" data-backdrop="static"  role="dialog" aria-labelledby="modal-croscutting-label" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-scrollable" role="document">
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
						<button type="button" id="status-croscutting" class="btn btn-success d-block"">Disetujui</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 d-flex justify-content-center">
					<div class="form-group text-center" style="display: none;" id="alasan-ditolak">
						<label for="">Alasan</label>
						<textarea class="form-control detail_crocutting" style="width: 400px;" id="keterangan-alasan-ditolak" rows="3" disabled></textarea>
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

<script type="text/javascript">
jQuery(document).ready(function(){
    run_download_excel_sakip();
	let hak_akses_user = <?php echo $hak_akses_user ?>;
	if(hak_akses_user == 1){
		jQuery('#action-sakip').prepend('<a style="margin-right: 10px;" id="tambah-pohon-kinerja" onclick="return false;" href="#" class="btn btn-primary"><i class="dashicons dashicons-plus"></i> Tambah Data</a>');
	}

	jQuery('#table_notifikasi_croscutting').dataTable({   
		pageLength : 5,
    	lengthMenu: [[5, 10, 20], [5, 10, 20]]
	});
	
	jQuery('#table_notifikasi_koneksi_pokin_pemda').dataTable({   
		pageLength : 5,
    	lengthMenu: [[5, 10, 20], [5, 10, 20]]
	});

	jQuery("#tambah-pohon-kinerja").on('click', function(){
		pokinLevel1().then(function(){
			// jQuery("#pokinLevel1").DataTable();
		});
	});

	jQuery(document).on('click', '#tambah-pokin-level1', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(''
			+'<form id="form-pokin">'
				+'<input type="hidden" name="parent" value="0">'
				+'<input type="hidden" name="level" value="1">'
                +`<div class="form-group">`
                    +'<label for="label-pokin">Label POKIN</label>'
                    +`<textarea class="form-control" id="label-pokin" name="label" placeholder="Tuliskan pohon kinerja level 1..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
                +`</div>`
			+'</form>');
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel1">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level1', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
                            +'<label for="label-pokin">Label POKIN</label>'
							+`<textarea class="form-control" id="label-pokin" name="label">${response.data.label}</textarea>`
						+`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
                        +`</div>`
						+`<div class="setting-koneksi-pokin">`
							+`<h5 style="margin: 30px 0 5px 0;">Setting Koneksi Pohon Kinerja <?php echo $nama_pemda ?></h5>`
							+`<table id="table_koneksi_pokin" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Label Pokin <?php echo $nama_pemda ?></th>`
										+`<th class="text-center">Indikator Pokin <?php echo $nama_pemda ?></th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${response.data_koneksi_pokin}</tbody>`
							+`</table>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel1">`
						+`Update`
					+`</button>`);
				
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');

				if(response.data_koneksi_pokin == "" || response.data_koneksi_pokin == undefined){
					jQuery(".setting-koneksi-pokin").hide()
					jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				}else{
					jQuery(".setting-koneksi-pokin").show()
					jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','1100px');
				}
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level1', function(){
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':1,
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel1().then(function(){
							// jQuery("#pokinLevel1").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level1', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level1').text()}">`
				+`<input type="hidden" name="level" value="1">`
                +`<div class="form-group">`
                    +'<label for="indikator_label">Label POKIN: '+jQuery(this).parent().parent().find('.label-level1').text()+'</label>'
                    +`<textarea class="form-control" id="indikator_label" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
                +`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel1">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-indikator-pokin-level1', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
                        +`<div class="form-group">`
                            +'<label for="indikator_label">Label POKIN: '+response.data.label+'</label>'
                            +`<textarea class="form-control" id="indikator_label" name="indikator_label" placeholder="Tuliskan indikator...">${response.data.label_indikator_kinerja}</textarea>`
                        +`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
                        +`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel1">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level1', function(){
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel1().then(function(){
							// jQuery("#pokinLevel1").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.view-pokin-level2', function(){
		pokinLevel2({
			'parent':jQuery(this).data('id')
		}).then(function(){
			// jQuery("#pokinLevel2").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level2', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="2">`
                +`<div class="form-group">`
                    +'<label for="label-pokin">Label POKIN</label>'
                    +`<textarea class="form-control" id="label-pokin" name="label" placeholder="Tuliskan pohon kinerja level 2..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
                +`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel2">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level2', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
                            +'<label for="label-pokin">Label POKIN</label>'
							+`<textarea class="form-control" id="label-pokin" name="label">${response.data.label}</textarea>`
						+`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
                        +`</div>`
						+`<div class="custom-control custom-checkbox">`
							+`<input type="checkbox" class="custom-control-input" name="settingCroscutting" value="false" id="settingCroscutting">`
							+`<label class="custom-control-label" for="settingCroscutting">Setting Croscutting</label>`
						+`</div>`
						+`<div class="setting-croscutting" style="margin-top:10px">`
							+`<button type="button" data-setting-croscutting="false" data-parent-croscutting="${response.data.id}" class="btn btn-success mb-2" id="tambah-croscuting-level"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
						+`</div>`
						+`<div class="wrap-table setting-croscutting">`
							+`<table id="table_croscutting" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Perangkat Pengusul</th>`
										+`<th class="text-center">Keterangan Pengusul</th>`
										+`<th class="text-center">Keterangan Tujuan</th>`
										+`<th class="text-center">Perangkat Daerah Tujuan</th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${response.data_croscutting}</tbody>`
							+`</table>`
						+`</div>`
						+`<div class="setting-koneksi-pokin">`
							+`<h5 style="margin: 30px 0 5px 0;">Setting Koneksi Pohon Kinerja <?php echo $nama_pemda; ?></h5>`
							+`<table id="table_koneksi_pokin" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Label Pokin <?php echo $nama_pemda ?></th>`
										+`<th class="text-center">Indikator Pokin <?php echo $nama_pemda ?></th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${response.data_koneksi_pokin}</tbody>`
							+`</table>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel2">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','1100px');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
				
				if(response.data_croscutting == "" || response.data_croscutting == undefined){
					jQuery('#settingCroscutting').prop('checked', false);
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'false');
					jQuery(".setting-croscutting").hide()
				}else{
					jQuery('#settingCroscutting').prop('checked', true);
					jQuery(".setting-croscutting").show()
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'true');
				}

				if(response.data_koneksi_pokin == "" || response.data_koneksi_pokin == undefined){
					jQuery(".setting-koneksi-pokin").hide()
				}else{
					jQuery(".setting-koneksi-pokin").show()
				}
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level2', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':2,
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel2({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel2").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level2', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level2').text()}">`
				+`<input type="hidden" name="level" value="2">`
                +`<div class="form-group">`
                    +'<label for="indikator_label">Label POKIN: '+jQuery(this).parent().parent().find('.label-level2').text()+'</label>'
                    +`<textarea class="form-control" id="indikator_label" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
                +`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel2">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-indikator-pokin-level2', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent_all" value="${response.data.parent_all}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
                        +`<div class="form-group">`
                            +'<label for="indikator_label">Label POKIN: '+response.data.label+'</label>'
                            +`<textarea class="form-control" id="indikator_label" name="indikator_label" placeholder="Tuliskan indikator...">${response.data.label_indikator_kinerja}</textarea>`
                        +`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
                        +`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel2">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level2', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel2({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel2").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.view-pokin-level3', function(){
		pokinLevel3({
			'parent':jQuery(this).data('id')
		}).then(function(){
			// jQuery("#pokinLevel3").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level3', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="3">`
                +`<div class="form-group">`
                    +'<label for="label-pokin">Label POKIN</label>'
                    +`<textarea class="form-control" id="label-pokin" name="label" placeholder="Tuliskan pohon kinerja level 3..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
                +`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel3">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level3', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
                        +`<div class="form-group">`
                            +'<label for="label-pokin">Label POKIN</label>'
                            +`<textarea class="form-control" id="label-pokin" name="label">${response.data.label}</textarea>`
                        +`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
                        +`</div>`
						+`<div class="custom-control custom-checkbox">`
							+`<input type="checkbox" class="custom-control-input" name="settingCroscutting" value="false" id="settingCroscutting">`
							+`<label class="custom-control-label" for="settingCroscutting">Setting Croscutting</label>`
						+`</div>`
						+`<div class="setting-croscutting" style="margin-top:10px">`
							+`<button type="button" data-setting-croscutting="false" data-parent-croscutting="${response.data.id}" class="btn btn-success mb-2" id="tambah-croscuting-level"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
						+`</div>`
						+`<div class="wrap-table setting-croscutting">`
							+`<table id="table_croscutting" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Perangkat Pengusul</th>`
										+`<th class="text-center">Keterangan Pengusul</th>`
										+`<th class="text-center">Keterangan Tujuan</th>`
										+`<th class="text-center">Perangkat Daerah Tujuan</th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${response.data_croscutting}</tbody>`
							+`</table>`
						+`</div>`
						+`<div class="setting-koneksi-pokin">`
							+`<h5 style="margin: 30px 0 5px 0;">Setting Koneksi Pohon Kinerja <?php echo $nama_pemda; ?></h5>`
							+`<table id="table_koneksi_pokin" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Label Pokin <?php echo $nama_pemda ?></th>`
										+`<th class="text-center">Indikator Pokin <?php echo $nama_pemda ?></th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${response.data_koneksi_pokin}</tbody>`
							+`</table>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel3">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','1100px');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');

				
				if(response.data_croscutting == "" || response.data_croscutting == undefined){
					jQuery('#settingCroscutting').prop('checked', false);
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'false');
					jQuery(".setting-croscutting").hide()
				}else{
					jQuery('#settingCroscutting').prop('checked', true);
					jQuery(".setting-croscutting").show()
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'true');
				}

				if(response.data_koneksi_pokin == "" || response.data_koneksi_pokin == undefined){
					jQuery(".setting-koneksi-pokin").hide()
				}else{
					jQuery(".setting-koneksi-pokin").show()
				}
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level3', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':3,
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel3({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel3").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level3', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level3').text()}">`
				+`<input type="hidden" name="level" value="3">`
                +`<div class="form-group">`
                    +'<label for="indikator_label">Label POKIN: '+jQuery(this).parent().parent().find('.label-level3').text()+'</label>'
                    +`<textarea class="form-control" id="indikator_label" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
                +`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel3">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-indikator-pokin-level3', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent_all" value="${response.data.parent_all}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
                        +`<div class="form-group">`
                            +'<label for="indikator_label">Label POKIN: '+response.data.label+'</label>'
                            +`<textarea class="form-control" id="indikator_label" name="indikator_label" placeholder="Tuliskan indikator...">${response.data.label_indikator_kinerja}</textarea>`
                        +`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
                        +`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel3">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level3', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel3({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel3").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.view-pokin-level4', function(){
		pokinLevel4({
			'parent':jQuery(this).data('id'),
		}).then(function(){
			// jQuery("#pokinLevel4").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level4', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="4">`
                +`<div class="form-group">`
                    +'<label for="label-pokin">Label POKIN</label>'
                    +`<textarea class="form-control" id="label-pokin" name="label" placeholder="Tuliskan pohon kinerja level 4..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
                +`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel4">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level4', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
                        +`<div class="form-group">`
                            +'<label for="label-pokin">Label POKIN</label>'
                            +`<textarea class="form-control" id="label-pokin" name="label">${response.data.label}</textarea>`
                        +`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
                        +`</div>`
						+`<div class="custom-control custom-checkbox">`
							+`<input type="checkbox" class="custom-control-input" name="settingCroscutting" value="false" id="settingCroscutting">`
							+`<label class="custom-control-label" for="settingCroscutting">Setting Croscutting</label>`
						+`</div>`
						+`<div class="setting-croscutting" style="margin-top:10px">`
							+`<button type="button" data-setting-croscutting="false" data-parent-croscutting="${response.data.id}" class="btn btn-success mb-2" id="tambah-croscuting-level"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
						+`</div>`
						+`<div class="wrap-table setting-croscutting">`
							+`<table id="table_croscutting" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Perangkat Pengusul</th>`
										+`<th class="text-center">Keterangan Pengusul</th>`
										+`<th class="text-center">Keterangan Tujuan</th>`
										+`<th class="text-center">Perangkat Daerah Tujuan</th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${response.data_croscutting}</tbody>`
							+`</table>`
						+`</div>`
						+`<div class="setting-koneksi-pokin">`
							+`<h5 style="margin: 30px 0 5px 0;">Setting Koneksi Pohon Kinerja <?php echo $nama_pemda; ?></h5>`
							+`<table id="table_koneksi_pokin" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Label Pokin <?php echo $nama_pemda ?></th>`
										+`<th class="text-center">Indikator Pokin <?php echo $nama_pemda ?></th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${response.data_koneksi_pokin}</tbody>`
							+`</table>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel4">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','1100px');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
				
				if(response.data_croscutting == "" || response.data_croscutting == undefined){
					jQuery('#settingCroscutting').prop('checked', false);
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'false');
					jQuery(".setting-croscutting").hide()
				}else{
					jQuery('#settingCroscutting').prop('checked', true);
					jQuery(".setting-croscutting").show()
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'true');
				}

				if(response.data_koneksi_pokin == "" || response.data_koneksi_pokin == undefined){
					jQuery(".setting-koneksi-pokin").hide()
				}else{
					jQuery(".setting-koneksi-pokin").show()
				}
			}
		});
	})

	jQuery(document).on('change', '#settingCroscutting', function(){
        if(this.checked) {
			jQuery(".setting-croscutting").show()
        }else{
			jQuery(".setting-croscutting").hide()
		}
    });

	jQuery(document).on('click', '.hapus-pokin-level4', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':4,
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel4({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel4").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level4', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level4').text()}">`
				+`<input type="hidden" name="level" value="4">`
                +`<div class="form-group">`
                    +'<label for="indikator_label">Label POKIN: '+jQuery(this).parent().parent().find('.label-level4').text()+'</label>'
                    +`<textarea class="form-control" id="indikator_label" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
                +`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel4">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	});

	jQuery(document).on('click', '.edit-indikator-pokin-level4', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent_all" value="${response.data.parent_all}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
                        +`<div class="form-group">`
                            +'<label for="indikator_label">Label POKIN: '+response.data.label+'</label>'
                            +`<textarea class="form-control" id="indikator_label" name="indikator_label" placeholder="Tuliskan indikator...">${response.data.label_indikator_kinerja}</textarea>`
                        +`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
                        +`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel4">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level4', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel4({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel4").DataTable();
						});
					}
				}
			})
		}
	});
  
	jQuery(document).on('click', '.view-pokin-level5', function(){
		pokinLevel5({
			'parent':jQuery(this).data('id'),
		}).then(function(){
			// jQuery("#pokinLevel5").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level5', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="5">`
                +`<div class="form-group">`
                    +'<label for="label-pokin">Label POKIN</label>'
                    +`<textarea class="form-control" id="label-pokin" name="label" placeholder="Tuliskan pohon kinerja level 5..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
                +`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel5">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level5', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
                        +`<div class="form-group">`
                            +'<label for="label-pokin">Label POKIN</label>'
                            +`<textarea class="form-control" id="label-pokin" name="label">${response.data.label}</textarea>`
                        +`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
                        +`</div>`
						+`<div class="custom-control custom-checkbox">`
							+`<input type="checkbox" class="custom-control-input" name="settingCroscutting" value="false" id="settingCroscutting">`
							+`<label class="custom-control-label" for="settingCroscutting">Setting Croscutting</label>`
						+`</div>`
						+`<div class="setting-croscutting" style="margin-top:10px">`
							+`<button type="button" data-setting-croscutting="false" data-parent-croscutting="${response.data.id}" class="btn btn-success mb-2" id="tambah-croscuting-level"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
						+`</div>`
						+`<div class="wrap-table setting-croscutting">`
							+`<table id="table_croscutting" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Perangkat Pengusul</th>`
										+`<th class="text-center">Keterangan Pengusul</th>`
										+`<th class="text-center">Keterangan Tujuan</th>`
										+`<th class="text-center">Perangkat Daerah Tujuan</th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${response.data_croscutting}</tbody>`
							+`</table>`
						+`</div>`
						+`<div class="setting-koneksi-pokin">`
							+`<h5 style="margin: 30px 0 5px 0;">Setting Koneksi Pohon Kinerja <?php $nama_pemda; ?></h5>`
							+`<table id="table_koneksi_pokin" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">`
								+`<thead>`
									+`<tr>`
										+`<th class="text-center">No</th>`
										+`<th class="text-center">Label Pokin <?php echo $nama_pemda ?></th>`
										+`<th class="text-center">Indikator Pokin <?php echo $nama_pemda ?></th>`
										+`<th class="text-center">Status</th>`
										+`<th class="text-center" style="width: 150px;">Aksi</th>`
									+`</tr>`
								+`</thead>`
								+`<tbody>${response.data_koneksi_pokin}</tbody>`
							+`</table>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel5">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','1100px');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
				
				if(response.data_croscutting == "" || response.data_croscutting == undefined){
					jQuery('#settingCroscutting').prop('checked', false);
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'false');
					jQuery(".setting-croscutting").hide()
				}else{
					jQuery('#settingCroscutting').prop('checked', true);
					jQuery(".setting-croscutting").show()
					jQuery('#tambah-croscuting-level').attr('data-setting-croscutting', 'true');
				}

				if(response.data_koneksi_pokin == "" || response.data_koneksi_pokin == undefined){
					jQuery(".setting-koneksi-pokin").hide()
				}else{
					jQuery(".setting-koneksi-pokin").show()
				}
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level5', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
          			'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':5,
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel5({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel5").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level5', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
        var last_urutan = +jQuery(this).attr('last-urutan');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level5').text()}">`
				+`<input type="hidden" name="level" value="5">`
                +`<div class="form-group">`
                    +'<label for="indikator_label">Label POKIN: '+jQuery(this).parent().parent().find('.label-level5').text()+'</label>'
                    +`<textarea class="form-control" id="indikator_label" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
                +`</div>`
                +`<div class="form-group">`
                    +'<label>Nomor Urut</label>'
                    +`<input type="number" class="form-control" name="nomor_urut" value="${last_urutan+1}">`
                +`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel5">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	});

	jQuery(document).on('click', '.edit-indikator-pokin-level5', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent_all" value="${response.data.parent_all}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
                        +`<div class="form-group">`
                            +'<label for="indikator_label">Label POKIN: '+response.data.label+'</label>'
                            +`<textarea class="form-control" id="indikator_label" name="indikator_label" placeholder="Tuliskan indikator...">${response.data.label_indikator_kinerja}</textarea>`
                        +`</div>`
                        +`<div class="form-group">`
                            +'<label>Nomor Urut</label>'
                            +`<input type="number" class="form-control" name="nomor_urut" value="${response.data.nomor_urut}">`
                        +`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel5">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level5', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
          			'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'tipe_pokin': "opd",
					'id_skpd': <?php echo $id_skpd; ?>
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel5({
							'parent':parent
						}).then(function(){
							// jQuery("#pokinLevel5").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '#simpan-data-pokin', function(){
		jQuery('#wrap-loading').show();
		let modal = jQuery("#modal-crud");
		let action = jQuery(this).data('action');
		let view = jQuery(this).data('view');
		let form = getFormData(jQuery("#form-pokin"));
		form['id_jadwal'] = '<?php echo $input['periode']; ?>';
		
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			dataType:'json',
			data:{
				'action': action,
        		'api_key': esakip.api_key,
				'data': JSON.stringify(form),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			success:function(response){
				jQuery('#wrap-loading').hide();
				alert(response.message);
				if(response.status){
					runFunction(view, [form])
					modal.modal('hide');
				}
			}
		})
	});

	jQuery(document).on('click', '#simpan-data-croscutting', function(){
		jQuery('#wrap-loading').show();
		let modal = jQuery("#modal-croscutting");
		let action = jQuery(this).data('action');
		let view = jQuery(this).data('view');
		let form = getFormData(jQuery("#form-croscutting"));
		form['id_jadwal'] = '<?php echo $input['periode']; ?>';

		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			dataType:'json',
			data:{
				'action': action,
        		'api_key': esakip.api_key,
				'data': JSON.stringify(form),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			success:function(response){
				jQuery('#wrap-loading').hide();
				alert(response.message);
				if(response.status){
					runFunction(view, [form])
					modal.modal('hide');
					jQuery("#modal-crud").modal('hide');
				}
			}
		})
	});

	jQuery(document).on('click', '#simpan-data-koneksi-pokin-pemda', function(){
		jQuery('#wrap-loading').show();
		let modal = jQuery("#modal-croscutting");
		let action = jQuery(this).data('action');
		let view = jQuery(this).data('view');
		let form = getFormData(jQuery("#form-koneksi-pokin-pemda"));

		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			dataType:'json',
			data:{
				'action': action,
        		'api_key': esakip.api_key,
				'data': JSON.stringify(form),
			},
			success:function(response){
				jQuery('#wrap-loading').hide();
				alert(response.message);
				if(response.status){
					runFunction(view, [form])
					modal.modal('hide');
					jQuery("#modal-crud").modal('hide');
					refresh_page();
				}
			}
		})
	});
});

function detail_cc(id){
	jQuery("#wrap-loading").show();
	if(id == undefined){
		alert("Id tidak ditemukan")
	}

	jQuery.ajax({
		method:'POST',
		url:esakip.url,
		data:{
			"action": "detail_croscutting_by_id",
			"api_key": esakip.api_key,
			'id': id,
			'tipe_pokin': "opd",
			'id_skpd': <?php echo $id_skpd; ?>
		},
		dataType:'json',
		success:function(response){
			jQuery("#wrap-loading").hide();
			if(response.status){
				jQuery("#label-pengusul").val(response.data_croscutting.label_parent)
				jQuery("#perangkat-pengusul").val(response.data_croscutting.nama_perangkat)
				jQuery("#keterangan-pengusul").val(response.data_croscutting.keterangan)
				jQuery("#perangkat-tujuan").val(response.data_croscutting.nama_perangkat_tujuan)
				jQuery("#label-tujuan").val(response.data_croscutting.label_parent_tujuan)
				jQuery("#keterangan-tujuan").val(response.data_croscutting.keterangan_tujuan)

				jQuery("#alasan-ditolak").hide();
				switch (response.data_croscutting.status_croscutting) {
					case '1':
						jQuery("#status-croscutting").html("Disetujui").removeClass().addClass("btn btn-success d-block");
						break;
					case '2':
						jQuery("#status-croscutting").html("Ditolak").removeClass().addClass("btn btn-danger d-block");
						jQuery("#alasan-ditolak").show();
						jQuery("#keterangan-alasan-ditolak").val(response.data_croscutting.keterangan_tolak)
						break;
					default:
						jQuery("#status-croscutting").html("Menunggu").removeClass().addClass("btn btn-secondary d-block");
					}
				jQuery("#modal-detail").modal('show');
			}
		}
	});
};

jQuery(document).on('click', '.edit-croscutting', function(){
	jQuery("#wrap-loading").show();
	let id = jQuery(this).data('id');
	jQuery.ajax({
		method:'POST',
		url:esakip.url,
		data:{
			"action": "edit_croscutting",
			"api_key": esakip.api_key,
			'id':id,
			'tipe_pokin': "opd",
			'id_skpd': <?php echo $id_skpd; ?>
		},
		dataType:'json',
		success:function(response){
			let keterangan = '';
			if(response.data_croscutting.keterangan != ''){
				keterangan = response.data_croscutting.keterangan;
			}
			let id_skpd_croscutting = '';
			let is_lembaga_lainnya = 0;
			if(response.data_croscutting.id_skpd_croscutting != ''){
				id_skpd_croscutting = response.data_croscutting.id_skpd_croscutting;
				if(response.data_croscutting.is_lembaga_lainnya == 1){
					is_lembaga_lainnya = 1
				}
			}

			let keterangan_tolak = ``
			if(response.data_croscutting.status_croscutting == 2){
				keterangan_tolak = ``
				+`<div class="form-group" id="showKeteranganTolakCroscutting">`
					+`<label for="keteranganTolakCroscutting">Keterangan Ditolak Croscutting</label>`
					+`<textarea class="form-control" id="keteranganTolakCroscutting" name="keteranganTolakCroscutting" disabled>${response.data_croscutting.keterangan_tolak}</textarea>`
				+`</div>`;
			}
			jQuery("#wrap-loading").hide();
			jQuery("#modal-croscutting").find('.modal-title').html('Edit Croscutting');
			jQuery("#modal-croscutting").find('.modal-body').html(``
				+`<form id="form-croscutting">`				
					+`<input type="hidden" name="id" value="${id}">`
					+`<input type="hidden" name="idParentCroscutting" value="${response.data_croscutting.parent_pohon_kinerja}">`
					+`<div class="form-group" id="showSkpdCroscutting">`
						+`<label for="skpdCroscutting">Pilih Perangkat Daerah</label>`
						+`<select class="form-control" name="skpdCroscutting" id="skpdCroscutting">`
						+`<?php echo $option_skpd; ?>`
						+`</select>`
					+`</div>`
					+`<div class="form-group" id="showLembagaLainnyaCroscutting">`
						+`<label for="lembagaLainnyaCroscutting">Pilih Lembaga Vertikal</label>`
						+`<select class="form-control" name="lembagaLainnyaCroscutting" id="lembagaLainnyaCroscutting">`
						+`<?php echo $option_lainnya; ?>`
						+`</select>`
					+`</div>`
					+`<div class="form-group" id="showKeteranganCroscutting">`
						+`<label for="keteranganCroscutting">Keterangan Croscutting</label>`
						+`<textarea class="form-control" id="keteranganCroscutting" name="keteranganCroscutting">${keterangan}</textarea>`
					+`</div>`
					+`${keterangan_tolak}`
				+`</form>`);
			// jQuery(`#skpdCroscutting option[value="${id_skpd_croscutting}"]`).prop('selected', true);
			jQuery("#modal-croscutting").find(`.modal-footer`).html(``
				+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
					+`Tutup`
				+`</button>`
				+`<button type="button" class="btn btn-success" id="simpan-data-croscutting" data-action="update_croscutting">`
					+`Update`
				+`</button>`);
			jQuery("#modal-croscutting").find('.modal-dialog').css('maxWidth','');
			jQuery("#modal-croscutting").find('.modal-dialog').css('width','');
			jQuery("#modal-croscutting").modal('show');
			jQuery('#skpdCroscutting').select2({
							width: '100%'
						});
			jQuery('#lembagaLainnyaCroscutting').select2({
							width: '100%'
						});
			// let id_skpd = [];
			// id_skpd_croscutting.map(function(value, index){
			// 	id_skpd.push(value.id_label_giat);
			// })
			if(is_lembaga_lainnya == 1){
				jQuery('#lembagaLainnyaCroscutting').val(id_skpd_croscutting).trigger('change');
				jQuery('#showSkpdCroscutting').hide();
			}else{
				jQuery('#skpdCroscutting').val(id_skpd_croscutting).trigger('change');
				jQuery('#showLembagaLainnyaCroscutting').hide();
			}
		}
	});
})

jQuery(document).on('click', '#tambah-croscuting-level', function(){
	jQuery("#modal-croscutting").find('.modal-title').html('Tambah croscutting');
	jQuery("#modal-croscutting").find('.modal-body').html(``
		+`<form id="form-croscutting">`				
			+`<input type="hidden" name="parentCroscutting" value="${jQuery(this).data('parent-croscutting')}">`
			+`<input type="hidden" name="settingCroscutting" value="${jQuery(this).data('setting-croscutting')}">`
			+`<div class="form-group" id="showSkpdCroscutting">`
				+`<label for="skpdCroscutting">Pilih Perangkat Daerah</label>`
				+`<select class="form-control" name="skpdCroscutting[]" multiple="multiple" id="skpdCroscutting">`
				+`<?php echo $option_skpd; ?>`
				+`</select>`
			+`</div>`
			+`<div class="form-group" id="showLembagaLainnyaCroscutting">`
				+`<label for="lembagaLainnyaCroscutting">Pilih Lembaga Vertikal</label>`
				+`<select class="form-control" name="lembagaLainnyaCroscutting[]" multiple="multiple" id="lembagaLainnyaCroscutting">`
				+`<?php echo $option_lainnya; ?>`
				+`</select>`
			+`</div>`
			+`<div class="form-group" id="showKeteranganCroscutting">`
				+`<label for="keteranganCroscutting">Keterangan Croscutting</label>`
				+`<textarea class="form-control" id="keteranganCroscutting" name="keteranganCroscutting"></textarea>`
			+`</div>`
		+`</form>`);
		jQuery("#modal-croscutting").find('.modal-footer').html(''
		+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
			+'Tutup'
		+'</button>'
		+'<button type="button" class="btn btn-success" id="simpan-data-croscutting" data-action="create_croscutting">'
			+'Simpan'
		+'</button>');
	jQuery("#modal-croscutting").find('.modal-dialog').css('maxWidth','');
	jQuery("#modal-croscutting").find('.modal-dialog').css('width','');
	jQuery("#modal-croscutting").modal('show');
	jQuery('#skpdCroscutting').select2({
							width: '100%'
						});
	jQuery('#lembagaLainnyaCroscutting').select2({
							width: '100%'
						});
})

	
jQuery(document).on('click', '.delete-croscutting', function(){
	if(confirm(`Data akan dihapus?`)){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
				'action': 'delete_croscutting',
				'api_key': esakip.api_key,
				'id':jQuery(this).data('id'),
				'tipe_pokin': "opd",
				'id_skpd': <?php echo $id_skpd; ?>
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				alert(response.message);
				jQuery("#modal-crud").modal('hide');
			}
		})
	}
});

jQuery(document).on('click', '.verifikasi-croscutting', function(){
	let id = jQuery(this).data('id');
	jQuery("#modal-croscutting").find('.modal-title').html('Verifikasi Croscutting');
	jQuery("#modal-croscutting").find('.modal-body').html(``
		+`<form id="form-croscutting">`				
			+`<input type="hidden" name="idCroscutting" value="${id}">`
			+`<div class="form-group">`
			+`<label>Perangkat Daerah Asal</label>`
			+`<input type="text" value="${jQuery(this).data('skpd-asal')}" disabled>`
			+`</div>`
			+`<div class="form-group">`
			+`<label>Keterangan Asal</label>`
			+`<input type="text" value="${jQuery(this).data('keterangan-asal')}" class="mt-1" disabled>`
			+`</div>`
			+`<table>`
			+`<tr>`
			+`<td><input id='verify-cc-yes' name='verify_cc' value='1' type='radio' style="margin-right: .5em;" checked><label for='verify-cc-yes'>Terima</label></td>`
			+`<td><input id='verify-cc-no' name='verify_cc' value='0' type='radio'  style="margin-right: .5em;"><label for='verify-cc-no'>Tolak</label></td>`
			+`</tr>`
			+`</table>`
			+`<div class="form-group showCroscutting" id="showKeterangan">`
				+`<label for="keteranganCroscutting">Keterangan Croscutting</label>`
				+`<textarea class="form-control" name="keterangan_cc" id="keteranganCroscutting"></textarea>`
			+`</div>`
			+`<div class="form-group showCroscuttingTolak" id="showKeteranganTolak" style="display: none;">`
				+`<label for="keteranganCroscuttingTolak">Alasan</label>`
				+`<textarea class="form-control" name="keterangan_cc_tolak" id="keteranganCroscuttingTolak"></textarea>`
			+`</div>`
			+`<div class="form-group showCroscutting" id="showLevelPokinCroscutting">`
				+`<label for="levelPokinCroscutting">Tautkan dengan Level Pohon Kinerja</label>`
				+`<select class="form-control" name="levelPokinCroscutting" id="levelPokinCroscutting">`
				+`<?php echo $option_tautan_pokin; ?>`
				+`</select>`
			+`</div>`
		+`</form>`);
		jQuery("#modal-croscutting").find('.modal-footer').html(''
		+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
			+'Tutup'
		+'</button>'
		+'<button type="button" class="btn btn-success" id="simpan-data-croscutting" data-action="verify_croscutting">'
			+'Simpan'
		+'</button>');
	jQuery("#modal-croscutting").find('.modal-dialog').css('maxWidth','');
	jQuery("#modal-croscutting").find('.modal-dialog').css('width','');
	jQuery("#modal-croscutting").modal('show');
	jQuery('#levelPokinCroscutting').select2({
							width: '100%'
						});
})

jQuery(document).on('click', '.edit-verifikasi-croscutting', function(){
	jQuery("#wrap-loading").show();
	let id = jQuery(this).data('id');
	jQuery.ajax({
		method:'POST',
		url:esakip.url,
		data:{
			"action": "edit_verify_croscutting",
			"api_key": esakip.api_key,
			'id':id,
			'tipe_pokin': "opd",
			'id_skpd': <?php echo $id_skpd; ?>
		},
		dataType:'json',
		success:function(response){
			jQuery("#wrap-loading").hide();
			jQuery("#modal-croscutting").find('.modal-title').html('Verifikasi Croscutting');
			jQuery("#modal-croscutting").find('.modal-body').html(``
				+`<form id="form-croscutting">`				
					+`<input type="hidden" name="idCroscutting" value="${id}">`
					+`<div class="form-group">`
					+`<label>Perangkat Daerah Asal</label>`
					+`<input type="text" value="${response.data_croscutting.nama_perangkat_parent}" disabled>`
					+`</div>`
					+`<div class="form-group">`
					+`<label>Keterangan Asal</label>`
					+`<input type="text" value="${response.data_croscutting.keterangan}" class="mt-1" disabled>`
					+`</div>`
					+`<table>`
					+`<tr>`
					+`<td><input id='verify-cc-yes' name='verify_cc' value='1' type='radio' style="margin-right: .5em;" checked><label for='verify-cc-yes'>Terima</label></td>`
					+`<td><input id='verify-cc-no' name='verify_cc' value='0' type='radio'  style="margin-right: .5em;"><label for='verify-cc-no'>Tolak</label></td>`
					+`</tr>`
					+`</table>`
					+`<div class="form-group showCroscutting" id="showKeterangan">`
						+`<label for="keteranganCroscutting">Keterangan Croscutting</label>`
						+`<textarea class="form-control" name="keterangan_cc" id="keteranganCroscutting">${response.data_croscutting.keterangan_croscutting}</textarea>`
					+`</div>`
					+`<div class="form-group showCroscuttingTolak" id="showKeteranganTolak" style="display: none;">`
						+`<label for="keteranganCroscuttingTolak">Alasan</label>`
						+`<textarea class="form-control" name="keterangan_cc_tolak" id="keteranganCroscuttingTolak"></textarea>`
					+`</div>`
					+`<div class="form-group showCroscutting" id="showLevelPokinCroscutting">`
						+`<label for="levelPokinCroscutting">Tautkan dengan Level Pohon Kinerja</label>`
						+`<select class="form-control" name="levelPokinCroscutting" id="levelPokinCroscutting">`
						+`<?php echo $option_tautan_pokin; ?>`
						+`</select>`
					+`</div>`
				+`</form>`);
				jQuery("#modal-croscutting").find('.modal-footer').html(''
				+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
					+'Tutup'
				+'</button>'
				+'<button type="button" class="btn btn-success" id="simpan-data-croscutting" data-action="verify_croscutting">'
					+'Simpan'
				+'</button>');
			jQuery("#modal-croscutting").find('.modal-dialog').css('maxWidth','');
			jQuery("#modal-croscutting").find('.modal-dialog').css('width','');
			jQuery("#modal-croscutting").modal('show');
			jQuery('#levelPokinCroscutting').select2({
									width: '100%'
								});
			jQuery('#levelPokinCroscutting').val(response.data_croscutting.parent_croscutting).trigger('change');
			if(response.data_croscutting.status_croscutting == 1){
				jQuery( "#verify-cc-yes" ).prop( "checked", true );
				jQuery( "#verify-cc-no" ).prop( "checked", false );
			}else{
				jQuery( "#verify-cc-yes" ).prop( "checked", false );
				jQuery( "#verify-cc-no" ).prop( "checked", true );
			}
		}
	});
})

jQuery(document).on('click', '#verify-cc-no', function(){
	var check = jQuery(this).is(':checked');
	if (check) {
		jQuery(".showCroscutting").hide();
		jQuery(".showCroscuttingTolak").show();
	}
});
jQuery(document).on('click', '#verify-cc-yes', function(){
	var check = jQuery(this).is(':checked');
	if (check) {
		jQuery(".showCroscutting").show();
		jQuery(".showCroscuttingTolak").hide();
	}
});

function pokinLevel1(){
	jQuery("#wrap-loading").show();
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 1,
	      		"parent": 0,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key,
            	"tipe_pokin": "opd",
				"id_skpd": <?php echo $id_skpd; ?>
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
                var nomor_urut = 0;
                if(res.data.length >= 1){
                    nomor_urut = Math.floor(res.data[res.data.length-1].nomor_urut);
                }
          		let level1 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" class="btn btn-success mb-2" id="tambah-pokin-level1" last-urutan="${nomor_urut}"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table" id="pokinLevel1">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:50px">No</th>`
	          					+`<th class="text-center">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:250px">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
                                let indikator = Object.values(value.indikator);
                                var last_urutan = 0;
                                if(indikator.length > 0){
                                    var last_urutan = Math.floor(indikator[indikator.length-1].nomor_urut);
                                }
			          			level1 += ``
				          			+`<tr id="pokinLevel1_${value.id}">`
					          			+`<td class="text-center">${index+1}</td>`
					          			+`<td class="label-level1">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-success tambah-indikator-pokin-level1" title="Tambah Indikator" last-urutan="${last_urutan}"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level2" title="Lihat pohon kinerja level 2"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-primary edit-pokin-level1" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger hapus-pokin-level1" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level1 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level1" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-danger hapus-indikator-pokin-level1" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level1+=`<tbody>`
          			+`</table>`;

          		jQuery("#nav-level-1").html(level1);
				jQuery('.nav-tabs a[href="#nav-level-1"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function pokinLevel2(params){
	jQuery("#wrap-loading").show();
	let parent = params.parent_all ?? params.parent;	
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 2,
	      		"parent": parent,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key,
            	"tipe_pokin": "opd",
				"id_skpd": <?php echo $id_skpd; ?>
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
                var nomor_urut = 0;
                if(res.data.length >= 1){
                    nomor_urut = Math.floor(res.data[res.data.length-1].nomor_urut);
                }
                var level2 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level2" last-urutan="${nomor_urut}"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table">`
      					+`<thead>`;
      						res.parent.map(function(value, index){
      							if(value!=null){
		          					level2 += ``
				          				+`<tr>`
				          					+`<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>`
				          					+`<th>${value}</th>`
				          				+`</tr>`;
      							}
	          				});
      					level2+=`</thead>`
      				+`</table>`
	          		+`<table class="table" id="pokinLevel2">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:50px">No</th>`
	          					+`<th class="text-center">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:250px">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
					          	let indikator = Object.values(value.indikator);
                                var last_urutan = 0;
                                if(indikator.length > 0){
                                    var last_urutan = Math.floor(indikator[indikator.length-1].nomor_urut);
                                }
			          			level2 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}</td>`
					          			+`<td class="label-level2">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level2" title="Tambah Indikator" last-urutan="${last_urutan}"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level3" title="Lihat pohon kinerja level 3"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level2" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level2" title="Hapus"><i class="dashicons dashicons-trash"></i></a> `
                                            <?php if($is_admin_bappeda == true): ?>
                                                +'<a href="javascript:void(0)" data-id="'+value.id+'" data-parent="'+value.parent+'" class="btn btn-sm btn-warning" onclick="copy_data_pokin(this)" title="Copy Data Ke Semua OPD"><i class="dashicons dashicons-images-alt"></i></a>'
                                            <?php endif; ?>
					          			+`</td>`
					          		+`</tr>`;

					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level2 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level2" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-indikator-pokin-level2" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level2+=`<tbody>`
          			+`</table>`;

				jQuery("#nav-level-2").html(level2);
				jQuery('.nav-tabs a[href="#nav-level-2"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function pokinLevel3(params){
	jQuery("#wrap-loading").show();
	let parent = params.parent_all ?? params.parent;
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 3,
	      		"parent": parent,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key,
            	"tipe_pokin": "opd",
				"id_skpd": <?php echo $id_skpd; ?>
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
                var nomor_urut = 0;
                if(res.data.length >= 1){
                    nomor_urut = Math.floor(res.data[res.data.length-1].nomor_urut);
                }
                var level3 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level3" last-urutan="${nomor_urut}"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table">`
      					+`<thead>`;
      						res.parent.map(function(value, index){
      							if(value!=null){
		          					level3 += ``
				          				+`<tr>`
				          					+`<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>`
				          					+`<th>${value}</th>`
				          				+`</tr>`;
      							}
	          				});
      					level3+=`</thead>`
      				+`</table>`
	          		+`<table class="table" id="pokinLevel3">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:50px">No</th>`
	          					+`<th class="text-center">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:250px">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
                                let indikator = Object.values(value.indikator);
                                var last_urutan = 0;
                                if(indikator.length > 0){
                                    var last_urutan = Math.floor(indikator[indikator.length-1].nomor_urut);
                                }
			          			level3 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}</td>`
					          			+`<td class="label-level3">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level3" title="Tambah Indikator" last-urutan="${last_urutan}"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level4" title="Lihat pohon kinerja level 4"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level3" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level3" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level3 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level3" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-indikator-pokin-level3" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level3+=`<tbody>`
          			+`</table>`;

				jQuery("#nav-level-3").html(level3);
				jQuery('.nav-tabs a[href="#nav-level-3"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function pokinLevel4(params){
	jQuery("#wrap-loading").show();
	let parent = params.parent_all ?? params.parent;
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 4,
	      		"parent": parent,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key,
            	"tipe_pokin": "opd",
				"id_skpd": <?php echo $id_skpd; ?>
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
                var nomor_urut = 0;
                if(res.data.length >= 1){
                    nomor_urut = Math.floor(res.data[res.data.length-1].nomor_urut);
                }
                var level4 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level4" last-urutan="${nomor_urut}"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table">`
      					+`<thead>`;
      						res.parent.map(function(value, index){
      							if(value!=null){
		          					level4 += ``
				          				+`<tr>`
				          					+`<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>`
				          					+`<th>${value}</th>`
				          				+`</tr>`;
      							}
	          				});
      					level4+=`</thead>`
      				+`</table>`
	          		+`<table class="table" id="pokinLevel4">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:50px">No</th>`
	          					+`<th class="text-center">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:250px">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
                                let indikator = Object.values(value.indikator);
                                var last_urutan = 0;
                                if(indikator.length > 0){
                                    var last_urutan = Math.floor(indikator[indikator.length-1].nomor_urut);
                                }
			          			level4 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}</td>`
					          			+`<td class="label-level4">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level4" title="Tambah Indikator" last-urutan="${last_urutan}"><i class="dashicons dashicons-plus"></i></a> `
											+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level5" title="Lihat pohon kinerja level 5"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level4" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level4" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level4 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level4" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-indikator-pokin-level4" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level4+=`<tbody>`
          			+`</table>`;

				jQuery("#nav-level-4").html(level4);
				jQuery('.nav-tabs a[href="#nav-level-4"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function pokinLevel5(params){
	jQuery("#wrap-loading").show();
	let parent = params.parent_all ?? params.parent;
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 5,
	      		"parent": parent,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key,
            	"tipe_pokin": "opd",
				"id_skpd": <?php echo $id_skpd; ?>
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
                var nomor_urut = 0;
                if(res.data.length >= 1){
                    nomor_urut = Math.floor(res.data[res.data.length-1].nomor_urut);
                }
                var level5 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level5" last-urutan="${nomor_urut}"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table">`
      					+`<thead>`;
      						res.parent.map(function(value, index){
      							if(value!=null){
		          					level5 += ``
				          				+`<tr>`
				          					+`<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>`
				          					+`<th>${value}</th>`
				          				+`</tr>`;
      							}
	          				});
      					level5+=`</thead>`
      				+`</table>`
	          		+`<table class="table" id="pokinLevel5">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:50px">No</th>`
	          					+`<th class="text-center">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:250px">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
                                let indikator = Object.values(value.indikator);
                                var last_urutan = 0;
                                if(indikator.length > 0){
                                    var last_urutan = Math.floor(indikator[indikator.length-1].nomor_urut);
                                }
			          			level5 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}</td>`
					          			+`<td class="label-level5">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level5" title="Tambah Indikator" last-urutan="${last_urutan}"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level5" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level5" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level5 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level5" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-indikator-pokin-level5" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level5+=`<tbody>`
          			+`</table>`;

				jQuery("#nav-level-5").html(level5);
				jQuery('.nav-tabs a[href="#nav-level-5"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function runFunction(name, arguments){
    var fn = window[name];
    if(typeof fn !== 'function')
        return;

    var run = fn.apply(window, arguments);
    run.then(function(){
 		// jQuery("#"+name).DataTable();
	});
}

<?php if($is_admin_bappeda == true): ?>
function copy_data_pokin(that){
    var id = jQuery(that).attr('data-id');
    var parent = jQuery(that).attr('data-parent');
    jQuery("#modal-crud").find('.modal-title').html('Copy Data Pohon Kinerja');
    jQuery("#modal-crud").find('.modal-body').html(''
        +'<form id="form-copy-pokin">'
            +'<input type="hidden" id="parent_pokin_copy" value="'+parent+'">'
            +'<input type="hidden" id="id_pokin_copy" value="'+id+'">'
            +'<div class="form-group">'
                +'<textarea class="form-control" id="copy_rubah_kata" name="label" placeholder="Tuliskan kata yang mau dirubah" style="height: 200px;">[Bappeda Litbang|nama_opd]</textarea>'
                +'<ul>'
                    +'<li>Variable <b>nama_opd</b> untuk nama OPD</li>'
                    +'<li>Cara penulisannya didalam kotak dan dipisah garis lurus. Jika lebih dari satu maka dipisah dengan koma.</li>'
                +'</ul>'
            +'</div>'
        +'</form>');
    jQuery("#modal-crud").find('.modal-footer').html(''
        +'<button type="button" class="btn btn-danger" data-dismiss="modal">'
            +'Tutup'
        +'</button>'
        +'<button type="button" class="btn btn-success" onclick="simpan_copy_data_pokin();">'
            +'Simpan'
        +'</button>');
    jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
    jQuery("#modal-crud").find('.modal-dialog').css('width','');
    jQuery("#modal-crud").modal('show');
}

function simpan_copy_data_pokin(){
    if(confirm('Apakah anda yakin melakukan copy data POKIN level 2 ke semua OPD? Data level dibawahnya akan ikut tercopy.')){
        jQuery('#wrap-loading').show();
        var parent = jQuery('#parent_pokin_copy').val();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": "simpan_copy_data_pokin",
                "parent": parent,
                "id": jQuery('#id_pokin_copy').val(),
                "copy_rubah_kata": jQuery('#copy_rubah_kata').val(),
                "id_jadwal": <?php echo $input['periode']; ?>,
                "api_key": esakip.api_key
            },
            dataType: "json",
            success: function(res){
                alert(res.message);
                jQuery('#wrap-loading').hide();
                if(res.status=='success'){
                    jQuery("#modal-crud").modal('hide');
                    pokinLevel2({parent: parent});
                }
            }
        });
    }
}
<?php endif; ?>

jQuery(document).on('click', '.verifikasi-koneksi-pokin-pemda', function(){
	let id = jQuery(this).data('id');
	jQuery("#modal-croscutting").find('.modal-title').html('Verifikasi Koneksi Pohon Kinerja <?php $nama_pemda; ?>');
	jQuery("#modal-croscutting").find('.modal-body').html(``
		+`<form id="form-koneksi-pokin-pemda">`				
			+`<input type="hidden" name="idKoneksiPokin" value="${id}">`
			+`<table>`
			+`<tr>`
			+`<td><input id='verify-koneksi-yes' name='verify_koneksi_pokin' value='1' type='radio' style="margin-right: .5em;" checked><label for='verify-koneksi-yes'>Terima</label></td>`
			+`<td><input id='verify-koneksi-no' name='verify_koneksi_pokin' value='0' type='radio'  style="margin-right: .5em;"><label for='verify-koneksi-no'>Tolak</label></td>`
			+`</tr>`
			+`</table>`
			+`<div class="form-group showKoneksiPokinTolak" id="showKeteranganTolak" style="display: none;">`
				+`<label for="keteranganKoneksiPokinTolak">Alasan</label>`
				+`<textarea class="form-control" name="keterangan_koneksi_tolak" id="keteranganKoneksiPokinTolak"></textarea>`
			+`</div>`
			+`<div class="form-group showKoneksiPokin" id="showLevelPokinKoneksi">`
				+`<label for="levelPokinKoneksi">Tautkan dengan Level Pohon Kinerja</label>`
				+`<select class="form-control" name="levelPokinKoneksi" id="levelPokinKoneksi">`
				+`<?php echo $option_tautan_pokin_koneksi; ?>`
				+`</select>`
			+`</div>`
		+`</form>`);
		jQuery("#modal-croscutting").find('.modal-footer').html(''
		+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
			+'Tutup'
		+'</button>'
		+'<button type="button" class="btn btn-success" id="simpan-data-koneksi-pokin-pemda" data-action="verify_koneksi_pokin_pemda_opd">'
			+'Simpan'
		+'</button>');
	jQuery("#modal-croscutting").find('.modal-dialog').css('maxWidth','');
	jQuery("#modal-croscutting").find('.modal-dialog').css('width','');
	jQuery("#modal-croscutting").modal('show');
	jQuery('#levelPokinKoneksi').select2({ width: '100%' });
})

jQuery(document).on('click', '.edit-verifikasi-koneksi-pokin-pemda', function(){
	jQuery("#wrap-loading").show();
	let id = jQuery(this).data('id');
	jQuery.ajax({
		method:'POST',
		url:esakip.url,
		data:{
			"action": "edit_verify_koneksi_pokin_pemda",
			"api_key": esakip.api_key,
			'id':id,
			'tipe_pokin': "opd",
			'id_skpd': <?php echo $id_skpd; ?>
		},
		dataType:'json',
		success:function(response){
			jQuery("#wrap-loading").hide();
			jQuery("#modal-croscutting").find('.modal-title').html('Verifikasi Koneksi Pohon Kinerja <?php $nama_pemda; ?>');
			jQuery("#modal-croscutting").find('.modal-body').html(``
				+`<form id="form-koneksi-pokin-pemda">`				
					+`<input type="hidden" name="idKoneksiPokin" value="${id}">`
					+`<table>`
					+`<tr>`
					+`<td><input id='verify-koneksi-yes' name='verify_koneksi_pokin' value='1' type='radio' style="margin-right: .5em;" checked><label for='verify-koneksi-yes'>Terima</label></td>`
					+`<td><input id='verify-koneksi-no' name='verify_koneksi_pokin' value='0' type='radio'  style="margin-right: .5em;"><label for='verify-koneksi-no'>Tolak</label></td>`
					+`</tr>`
					+`</table>`
					+`<div class="form-group showKoneksiPokinTolak" id="showKeteranganTolak" style="display: none;">`
						+`<label for="keteranganKoneksiPokinTolak">Alasan</label>`
						+`<textarea class="form-control" name="keterangan_koneksi_tolak" id="keteranganKoneksiPokinTolak"></textarea>`
					+`</div>`
					+`<div class="form-group showKoneksiPokin" id="showLevelPokinKoneksi">`
						+`<label for="levelPokinKoneksi">Tautkan dengan Level Pohon Kinerja</label>`
						+`<select class="form-control" name="levelPokinKoneksi" id="levelPokinKoneksi">`
						+`<?php echo $option_tautan_pokin_koneksi; ?>`
						+`</select>`
					+`</div>`
				+`</form>`);
				jQuery("#modal-croscutting").find('.modal-footer').html(''
				+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
					+'Tutup'
				+'</button>'
				+'<button type="button" class="btn btn-success" id="simpan-data-koneksi-pokin-pemda" data-action="verify_koneksi_pokin_pemda_opd">'
					+'Simpan'
				+'</button>');
			jQuery("#modal-croscutting").find('.modal-dialog').css('maxWidth','');
			jQuery("#modal-croscutting").find('.modal-dialog').css('width','');
			jQuery("#modal-croscutting").modal('show');
			jQuery('#levelPokinKoneksi').select2({
									width: '100%'
								});
			jQuery('#levelPokinKoneksi').val(response.data_koneksi_pokin.parent_pohon_kinerja_koneksi).trigger('change');
			if(response.data_koneksi_pokin.status_koneksi == 1){
				jQuery( "#verify-koneksi-yes" ).prop( "checked", true );
				jQuery( "#verify-koneksi-no" ).prop( "checked", false );
			}else{
				jQuery( "#verify-koneksi-yes" ).prop( "checked", false );
				jQuery( "#verify-koneksi-no" ).prop( "checked", true );
			}
		}
	});
})

jQuery(document).on('change', 'input[type=radio][name=verify_koneksi_pokin]', function(){
        var selectedValue = jQuery(this).val();
		if(selectedValue == 1){
			jQuery(".showKoneksiPokin").show();
			jQuery(".showKoneksiPokinTolak").hide();
		}else{
			jQuery(".showKoneksiPokin").hide();
			jQuery(".showKoneksiPokinTolak").show();
		}
});

function refresh_page() {
	if (confirm('Ada data yang berubah, apakah mau merefresh halaman ini?')) {
		window.location = "";
	}
    }
</script>