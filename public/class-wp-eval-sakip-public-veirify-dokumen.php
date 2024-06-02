<?php

require_once ESAKIP_PLUGIN_PATH . "/public/class-wp-eval-sakip-public-lke.php";
class Wp_Eval_Sakip_Verify_Dokumen extends Wp_Eval_Sakip_LKE
{

	public function get_data_pengaturan_menu()
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
                    $this->generate_menu($tahun_anggaran);

                    $where_jenis_user = '';
                    if(!empty($_POST['tipe'])){
                        $tipe = $_POST['tipe'];
                        $where_jenis_user = " AND user_role='".$tipe."'";
                    }
                    $dokumen_menu = $wpdb->get_results(
                        "
                        SELECT 
                            *
                        FROM esakip_menu_dokumen 
                        WHERE tahun_anggaran =".$tahun_anggaran.''.$where_jenis_user,
                        ARRAY_A
                    );

                    if (!empty($dokumen_menu)) {
                        $tbody = '';
                        $counter = 1;
                        foreach ($dokumen_menu as $menu) {
                            
                            $color_badge_verify = 'secondary';
                            $text_badge = 'Tidak Aktif';
                            if ($menu['active'] == 1) {
                                $color_badge_verify = 'success';
                                $text_badge = 'Aktif';
                            } else if ($menu['active'] == 0) {
                                $color_badge_verify = 'secondary';
                                $text_badge = 'Tidak Aktif';
                            }

                            $tbody .= "<tr>";
                            $tbody .= "<td class='text-center'>" . $counter++ . "</td>";
                            $tbody .= "<td class='text-left'>" . $menu['nama_tabel'] . "</td>";
                            $tbody .= "<td class='text-left'>" . $menu['nama_dokumen'] . "</td>";
                            $tbody .= "<td class='text-center'><span class='badge badge-" . $color_badge_verify . "' style='padding: .5em 1.4em;'>" . $text_badge . "</span></td>";
                            $tbody .= "<td class='text-center'>" . $menu['keterangan'] . "</td>";
    
                            $btn = '<div class="btn-action-group">';
                            $btn .= '<button class="btn btn-warning" onclick="edit_pengaturan_menu(\'' . $menu['id'] . '\',\'' . $tipe . '\'); return false;" href="#" title="Edit Data"><span class="dashicons dashicons-edit"></span></button>';
                            $btn .= '</div>';

                            $tbody .= "<td class='text-center'>" . $btn . "</td>";
    
                            $tbody .= "</tr>";
                        }
                        $ret['data'] = $tbody;
                    } else {
                        $ret['data'] = "<tr><td colspan='7' class='text-center'>Tidak ada data tersedia</td></tr>";
                    }
                } else {
                    $ret = array(
                        'status' => 'error',
                        'message'   => 'Ada data yang kosong!'
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

    public function generate_menu($tahun_anggaran)
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
                if (!empty($tahun_anggaran)) {
                    $tahun_anggaran = $_POST['tahun_anggaran'];

                    $design_menu = array(
                        array(
                            'nama_dokumen' => 'RPJPD',
                            'nama_tabel' => 'esakip_rpjpd',
                            'user_role' => 'pemerintah_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'RPJMD',
                            'nama_tabel' => 'esakip_rpjmd',
                            'user_role' => 'pemerintah_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'RKPD',
                            'nama_tabel' => 'esakip_rkpd',
                            'user_role' => 'pemerintah_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'LKJIP/LPPD',
                            'nama_tabel' => 'esakip_lkjip_lppd',
                            'user_role' => 'pemerintah_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'Dokumen Lainnya',
                            'nama_tabel' => 'esakip_lainnya',
                            'user_role' => 'pemerintah_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'RENSTRA',
                            'nama_tabel' => 'esakip_renstra',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'IKU',
                            'nama_tabel' => 'esakip_iku',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'RENJA/RKT',
                            'nama_tabel' => 'esakip_renja_rkt',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'Perjanjian Kinerja',
                            'nama_tabel' => 'esakip_perjanjian_kinerja',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'Laporan Kinerja',
                            'nama_tabel' => 'esakip_laporan_kinerja',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'DPA',
                            'nama_tabel' => 'esakip_dpa',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'Pohon Kinerja dan Cascading',
                            'nama_tabel' => 'esakip_pohon_kinerja_dan_cascading',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'LHE AKIP Internal',
                            'nama_tabel' => 'esakip_lhe_akip_internal',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'TL LHE AKIP Internal',
                            'nama_tabel' => 'esakip_tl_lhe_akip_internal',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'Laporan Monev Renaksi',
                            'nama_tabel' => 'esakip_laporan_monev_renaksi',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'Dokumen Lainnya',
                            'nama_tabel' => 'esakip_dokumen_lainnya',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'Rencana Aksi',
                            'nama_tabel' => 'esakip_rencana_aksi',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'SKP',
                            'nama_tabel' => 'esakip_skp',
                            'user_role' => 'perangkat_daerah'
                        ),
                        array(
                            'nama_dokumen' => 'Evaluasi Internal',
                            'nama_tabel' => 'esakip_evaluasi_internal',
                            'user_role' => 'perangkat_daerah'
                        )
                    );

                    foreach ($design_menu as $menu) {
                        $cek_menu = $wpdb->get_results(
                            "
                            SELECT 
                                *
                            FROM esakip_menu_dokumen 
                            WHERE tahun_anggaran =".$tahun_anggaran."
                                AND nama_tabel='".$menu['nama_tabel']."'",
                            ARRAY_A
                        );
                        $default_menu = array(
                            'nama_tabel' => $menu['nama_tabel'],
                            'nama_dokumen' => $menu['nama_dokumen'],
                            'user_role' => $menu['user_role'],
                            'active' => 1,
                            'tahun_anggaran' => $tahun_anggaran
                        );
                        if(empty($cek_menu)){
                            if ($wpdb->insert('esakip_menu_dokumen', $default_menu) === false) {
                                error_log("Error inserting into esakip_menu_dokumen: " . $wpdb->last_error);
                                continue;
                            }
                        }else{
                            $update_menu = $wpdb->update(
                                'esakip_menu_dokumen', 
                                array('nama_dokumen' => $menu['nama_dokumen'],'user_role' => $menu['user_role']),
                                array('nama_tabel' => $menu['nama_tabel'],'tahun_anggaran' => $tahun_anggaran),
							    array('%s')
                            );
                            if ($update_menu === false) {
                                error_log("Error updating into esakip_menu_dokumen: " . $wpdb->last_error);
                                continue;
                            }
                        }
                        
                    }
                } else {
                    $return = array(
                        'status' => 'error',
                        'message'	=> 'Ada Data Yang Kosong!'
                    );
                }
            } else {
                $return = array(
                    'status' => 'error',
                    'message'	=> 'Api Key tidak sesuai!'
                );
            }
        } else {
            $return = array(
                'status' => 'error',
                'message'	=> 'Format tidak sesuai!'
            );
        }
        return $return;
    }

    public function get_pengaturan_menu_by_id()
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
					if (!empty($_POST['tipe'])) {
						$tipe_menu = $_POST['tipe'];

						$data = $wpdb->get_row(
							$wpdb->prepare("
								SELECT *
								FROM esakip_menu_dokumen
								WHERE id = %d
							", $_POST['id']),
							ARRAY_A
						);
						if (!empty($data)) {
							$ret['data'] = $data;
						} else {
							$ret = array(
								'status' => 'error',
								'message'   => 'Data Tidak Ditemukan!'
							);
						}
					} else {
						$ret = array(
							'status' => 'error',
							'message'   => 'Tipe Kosong!'
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

    
	public function submit_edit_pengaturan_menu_dokumen()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil Edit Pengaturan Menu Dokumen!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_dokumen'])) {
					$id_dokumen = $_POST['id_dokumen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Dokumen kosong!';
				}

                $keterangan = $_POST['keterangan'];

				if (!empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe Dokumen kosong!';
				}
                if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				if (!empty($_POST['menu_dokumen'])) {
					$pengaturan_menu_dokumen = $_POST['menu_dokumen'];
					$input_pengaturan_menu = 0;
					if ($pengaturan_menu_dokumen == 'tampil') {
						$input_pengaturan_menu = 1;
					} else if ($pengaturan_menu_dokumen == 'sembunyi') {
						$input_pengaturan_menu = 0;
					}
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Pengaturan Menu Dokumen kosong!';
				}

				if ($ret['status'] == 'success') {
					// Cek data verifikasi yg sudah ada
					$data_menu = $wpdb->get_row(
						$wpdb->prepare("
							SELECT *
							FROM esakip_menu_dokumen
							WHERE id = %d
								AND tahun_anggaran=%d
						", $id_dokumen, $tahun_anggaran),
						ARRAY_A
					);

                    if(!empty($data_menu)){
						$opsi = array(
							'active' => $input_pengaturan_menu,
							'keterangan' => $keterangan,
                            'updated_at' => current_time('mysql')
						);

						$wpdb->update(
							'esakip_menu_dokumen',
							$opsi,
							array('id' => $id_dokumen),
							array('%d', '%s', '%s'),
							array('%d')
						);

						if ($wpdb->rows_affected == 0) {
							$ret = array(
								'status' => 'error',
								'message' => 'Gagal memperbarui data ke database!'
							);
						}
                    }else{
                        $ret = array(
                            'status' => 'error',
                            'message'   => 'Data tidak ditemukan!'
                        );
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