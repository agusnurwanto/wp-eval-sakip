<?php

require_once ESAKIP_PLUGIN_PATH . "/public/class-wp-eval-sakip-public-lke.php";
class Wp_Eval_Sakip_Verify_Dokumen extends Wp_Eval_Sakip_LKE
{   
	public function jadwal_verifikasi_upload_dokumen($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/jadwal/wp-eval-sakip-jadwal-verifikasi-upload-dokumen.php';
	}

    public function jadwal_verifikasi_upload_dokumen_renstra($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/jadwal/wp-eval-sakip-jadwal-verifikasi-upload-dokumen_renstra.php';
	}

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
				if (!empty($_POST['tahun_anggaran']) && !empty($_POST['tipe'])) {
                    $tahun_anggaran = $_POST['tahun_anggaran'];
                    $cek = $this->generate_pengaturan_menu($tahun_anggaran,$_POST['tipe']);

                    $where_jenis_user = '';
                    if(!empty($_POST['tipe'])){
                        $tipe = $_POST['tipe'];
                        $where_jenis_user = $wpdb->prepare(" AND user_role=%s", $tipe);
                    }
                    $dokumen_menu = $wpdb->get_results($wpdb->prepare("
                        SELECT 
                            *
                        FROM esakip_menu_dokumen 
                        WHERE tahun_anggaran =%d 
                            $where_jenis_user
                        ORDER BY nomor_urut ASC
                    ", $tahun_anggaran), ARRAY_A);

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

                            $badge_verifikasi_upload = 'dashicons-dismiss';
                            $text_badge_verifikasi_upload = 'Tidak';
                            if ($menu['verifikasi_upload_dokumen'] == 1) {
                                $badge_verifikasi_upload = 'dashicons-yes-alt';
                                $text_badge_verifikasi_upload = 'Iya';
                            }

                            $akses_user = "<ul>";
                            if($menu['jenis_role'] == 1){
                                $akses_user .= "<li>Pemerintah Daerah</li>";
                            }else if($menu['jenis_role'] == 2){
                                $akses_user .= "<li>Perangkat Daerah</li>";
                            }else if($menu['jenis_role'] == 3){
                                $akses_user .= "<li>Pemerintah Daerah</li>
                                                <li>Perangkat Daerah</li>";
                            }
                            $akses_user .="</ul>";

                            $tbody .= "<tr>";
                            $tbody .= "<td class='text-center'>" . $counter++ . "</td>";
                            $tbody .= "<td class='text-left'>" . $menu['nama_tabel'] . "</td>";
                            $tbody .= "<td class='text-left'>" . $menu['nama_dokumen'] . "</td>";
                            $tbody .= "<td class='text-left'>" . $akses_user . "</td>";
                            if($tipe == 'perangkat_daerah'){
                                $tbody .= "<td class='text-center'><span class='dashicons " . $badge_verifikasi_upload . "'></span> " . $text_badge_verifikasi_upload . "</td>";
                            }
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

    public function get_data_pengaturan_menu_khusus()
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

                    $set_html_pemda = get_option('sakip_menu_khusus_set_html_pemda'.$tahun_anggaran);
                    $set_html_opd = get_option('sakip_menu_khusus_set_html_opd'.$tahun_anggaran);

                    $tbody = '';
                    $tbody .= "<tr>";
                    $tbody .= "<td class='text-center'>1</td>";
                    $tbody .= "<td class='text-left'>
                                    Pemerintah Daerah
                            </td>";
                    $tbody .= "<td class='text-left'>
                                <textarea class='form-control' id='set_html_menu_khusus_pemda' rows='3'>". stripslashes(htmlspecialchars_decode($set_html_pemda)) ."</textarea>
                                </td>";
                    $tbody .= "<td class='text-center'>";
                    $tbody .= "<button class='btn btn-primary' onclick='simpan_menu_khusus(\"pemda\"); return false;' href='#' title='Simpan Data'><span class='dashicons dashicons-saved'></span></button>";
                    $tbody .= "</td>";    
                    $tbody .= "</tr>";

                    $tbody .= "<tr>";
                    $tbody .= "<td class='text-center'>2</td>";
                    $tbody .= "<td class='text-left'>
                                    Perangkat Daerah
                            </td>";
                    $tbody .= "<td class='text-left'>
                                <textarea class='form-control' id='set_html_menu_khusus_opd' rows='3'>". stripslashes(htmlspecialchars_decode($set_html_opd)) ."</textarea>
                                </td>";
                    $tbody .= "<td class='text-center'>";
                    $tbody .= "<button class='btn btn-primary' onclick='simpan_menu_khusus(\"opd\"); return false;' href='#' title='Simpan Data'><span class='dashicons dashicons-saved'></span></button>";
                    $tbody .= "</td>";    
                    $tbody .= "</tr>";
                    
                    $ret['data'] = $tbody;
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

    public function simpan_perubahan_menu_khusus()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil Simpan Pengaturan Menu Khusus!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				
                $set_html = !empty($_POST['set_html']) ? $_POST['set_html'] : '';

                if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
                if (!empty($_POST['tipe'])) {
					$tipe = $_POST['tipe'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe kosong!';
				}
                
				if ($ret['status'] == 'success') {
                    update_option('sakip_menu_khusus_set_html_'. $tipe .''.$tahun_anggaran, trim(htmlspecialchars($set_html)));
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

    public function generate_pengaturan_menu($tahun_anggaran,$tipe)
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
                if (!empty($tahun_anggaran) && !empty($tipe)) {
                    $cek_menu_all_db = $wpdb->get_results($wpdb->prepare("
                        SELECT 
                            *
                        FROM esakip_menu_dokumen 
                        WHERE tahun_anggaran =%d
                            AND user_role=%s
                    ", $tahun_anggaran, $tipe), ARRAY_A);
                    $cek_menu_all = array();
                    foreach($cek_menu_all_db as $m){
                        $cek_menu_all[$m['nama_tabel']] = $m;
                    }
                    if($tipe == 'pemerintah_daerah'){
                        $design_menu = array(
                            array(
                                'nama_dokumen'  => 'RPJPD',
                                'nama_tabel'    => 'esakip_rpjpd',
                                'user_role'     => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'RPJMD',
                                'nama_tabel' => 'esakip_rpjmd',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'IKU',
                                'nama_tabel' => 'esakip_iku_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'RKPD',
                                'nama_tabel' => 'esakip_rkpd_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'LKJIP/LPPD',
                                'nama_tabel' => 'esakip_lkjip_lppd_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'Perjanjian Kinerja',
                                'nama_tabel' => 'esakip_perjanjian_kinerja_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'Rencana Aksi',
                                'nama_tabel' => 'esakip_rencana_aksi_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'Laporan Kinerja',
                                'nama_tabel' => 'esakip_laporan_kinerja_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'DPA',
                                'nama_tabel' => 'esakip_dpa_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'Pohon Kinerja dan Cascading',
                                'nama_tabel' => 'esakip_pohon_kinerja_dan_cascading_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'TL LHE AKIP Kemenpan',
                                'nama_tabel' => 'esakip_tl_lhe_akip_kemenpan_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'LHE AKIP Internal',
                                'nama_tabel' => 'esakip_lhe_akip_internal_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'TL LHE AKIP Internal',
                                'nama_tabel' => 'esakip_tl_lhe_akip_internal_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'Laporan Monev Renaksi',
                                'nama_tabel' => 'esakip_laporan_monev_renaksi_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'Pedoman Teknis Perencanaan',
                                'nama_tabel' => 'esakip_pedoman_teknis_perencanaan_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja',
                                'nama_tabel' => 'esakip_pedoman_teknis_pengukuran_dan_p_d_k_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'Pedoman Teknis Evaluasi Internal',
                                'nama_tabel' => 'esakip_pedoman_teknis_evaluasi_internal_pemda',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'Dokumen Lainnya',
                                'nama_tabel' => 'esakip_other_file',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            ),
                            array(
                                'nama_dokumen' => 'Penyusunan Pohon Kinerja',
                                'nama_tabel' => 'esakip_pohon_kinerja',
                                'user_role' => 'pemerintah_daerah',
                                'jenis_role'    => 1
                            )
                        );
                        $nomor_urut = 1.00;
                        foreach ($design_menu as $menu) {
                            if(
                                empty($cek_menu_all[$menu['nama_tabel']])
                            ){
                                if($menu['user_role'] == 'pemerintah_daerah'){
                                    $jenis_role = 1;
                                }else if($menu['user_role'] == 'perangkat_daerah'){
                                    $jenis_role = 2;
                                }
        
                                if(!empty($menu['verifikasi_upload_dokumen'])){
                                    $verifikasi_upload = 1;
                                }else{
                                    $verifikasi_upload = 0;
                                }
        
                                $default_menu = array(
                                    'nama_tabel' => $menu['nama_tabel'],
                                    'nama_dokumen' => $menu['nama_dokumen'],
                                    'user_role' => $menu['user_role'],
                                    'jenis_role'    => $jenis_role,
                                    'verifikasi_upload_dokumen' => $verifikasi_upload,
                                    'active' => 1,
                                    'tahun_anggaran' => $tahun_anggaran,
                                    'nomor_urut' => $nomor_urut++
                                );
                                if ($wpdb->insert('esakip_menu_dokumen', $default_menu) === false) {
                                    error_log("Error inserting into esakip_menu_dokumen: " . $wpdb->last_error);
                                    continue;
                                }
                            }
                            unset($cek_menu_all[$menu['nama_tabel']]);
                        }
                        foreach($cek_menu_all as $m){
                            $wpdb->delete('esakip_menu_dokumen', array('id' => $m['id']));
                        }
                    }else if($tipe == 'perangkat_daerah'){
                        $design_menu = array(
                            array(
                                'nama_dokumen' => 'RENSTRA',
                                'nama_tabel' => 'esakip_renstra',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2,
                                'verifikasi_upload_dokumen' => 1
                            ),
                            array(
                                'nama_dokumen' => 'IKU',
                                'nama_tabel' => 'esakip_iku',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2
                            ),
                            array(
                                'nama_dokumen' => 'RENJA/RKT',
                                'nama_tabel' => 'esakip_renja_rkt',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2,
                                'verifikasi_upload_dokumen' => 1
                            ),
                            array(
                                'nama_dokumen' => 'Perjanjian Kinerja',
                                'nama_tabel' => 'esakip_perjanjian_kinerja',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2,
                                'verifikasi_upload_dokumen' => 1
                            ),
                            array(
                                'nama_dokumen' => 'Rencana Aksi',
                                'nama_tabel' => 'esakip_rencana_aksi',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2
                            ),
                            array(
                                'nama_dokumen' => 'Laporan Kinerja',
                                'nama_tabel' => 'esakip_laporan_kinerja',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2,
                                'verifikasi_upload_dokumen' => 1
                            ),
                            array(
                                'nama_dokumen' => 'DPA',
                                'nama_tabel' => 'esakip_dpa',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2,
                                'verifikasi_upload_dokumen' => 1
                            ),
                            array(
                                'nama_dokumen' => 'Pohon Kinerja dan Cascading',
                                'nama_tabel' => 'esakip_pohon_kinerja_dan_cascading',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2
                            ),
                            array(
                                'nama_dokumen' => 'LHE AKIP Internal',
                                'nama_tabel' => 'esakip_lhe_akip_internal',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2
                            ),
                            array(
                                'nama_dokumen' => 'TL LHE AKIP Internal',
                                'nama_tabel' => 'esakip_tl_lhe_akip_internal',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2
                            ),
                            array(
                                'nama_dokumen' => 'Laporan Monev Renaksi',
                                'nama_tabel' => 'esakip_laporan_monev_renaksi',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2
                            ),
                            array(
                                'nama_dokumen' => 'SKP',
                                'nama_tabel' => 'esakip_skp',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2
                            ),
                            array(
                                'nama_dokumen' => 'Evaluasi Internal',
                                'nama_tabel' => 'esakip_evaluasi_internal',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2
                            ),
                            array(
                                'nama_dokumen' => 'Pengukuran Kinerja',
                                'nama_tabel' => 'esakip_pengukuran_kinerja',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2
                            ),
                            array(
                                'nama_dokumen' => 'Dokumen Lainnya',
                                'nama_tabel' => 'esakip_dokumen_lainnya',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2
                            ),
                            array(
                                'nama_dokumen' => 'Penyusunan Pohon Kinerja',
                                'nama_tabel' => 'esakip_pohon_kinerja_opd',
                                'user_role' => 'perangkat_daerah',
                                'jenis_role'    => 2
                            )
                        );
    
                        $nomor_urut = 1.00;
                        foreach ($design_menu as $menu) {
                            if(
                                empty($cek_menu_all[$menu['nama_tabel']])
                            ){
                                if($menu['user_role'] == 'pemerintah_daerah'){
                                    $jenis_role = 1;
                                }else if($menu['user_role'] == 'perangkat_daerah'){
                                    $jenis_role = 2;
                                }
        
                                if(!empty($menu['verifikasi_upload_dokumen'])){
                                    $verifikasi_upload = 1;
                                }else{
                                    $verifikasi_upload = 0;
                                }
        
                                $default_menu = array(
                                    'nama_tabel' => $menu['nama_tabel'],
                                    'nama_dokumen' => $menu['nama_dokumen'],
                                    'user_role' => $menu['user_role'],
                                    'jenis_role'    => $jenis_role,
                                    'verifikasi_upload_dokumen' => $verifikasi_upload,
                                    'active' => 1,
                                    'tahun_anggaran' => $tahun_anggaran,
                                    'nomor_urut' => $nomor_urut++
                                );
                                if ($wpdb->insert('esakip_menu_dokumen', $default_menu) === false) {
                                    error_log("Error inserting into esakip_menu_dokumen: " . $wpdb->last_error);
                                    continue;
                                }
                            }
                            unset($cek_menu_all[$menu['nama_tabel']]);
                        }
                        foreach($cek_menu_all as $m){
                            $wpdb->delete('esakip_menu_dokumen', array('id' => $m['id']));
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
                            $default_urutan = $data['nomor_urut'];
                            if($default_urutan == 0.00 || empty($default_urutan)){
                                $default_urutan = $wpdb->get_var(
                                    $wpdb->prepare("
                                        SELECT MAX(nomor_urut)
                                        FROM esakip_menu_dokumen
                                        WHERE user_role = %s
                                            AND tahun_anggaran = %d
                                    ", $data['user_role'], $data['tahun_anggaran'])
                                );

                                if (empty($default_urutan)) {
                                    $default_urutan = 0.00;
                                }
                            }

                            $data['default_urutan'] = $default_urutan;
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
                if (!empty($_POST['nomor_urutan'])) {
					$nomor_urutan = $_POST['nomor_urutan'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nomor Urutan kosong!';
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

                if (!empty($_POST['akses_user'])) {
					$pengaturan_akses_user = $_POST['akses_user'];
					$input_akses_user = 0;
					if ($pengaturan_akses_user == 'pemda') {
						$input_akses_user = 1;
					} else if ($pengaturan_akses_user == 'pd') {
						$input_akses_user = 2;
					} else if ($pengaturan_akses_user == 'semua') {
						$input_akses_user = 3;
					}
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Ada yang error! Harap ulangi edit pengaturan menu';
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
                            'updated_at' => current_time('mysql'),
                            'jenis_role' => $input_akses_user,
                            'nomor_urut' => $nomor_urutan
						);

						$wpdb->update(
							'esakip_menu_dokumen',
							$opsi,
							array('id' => $id_dokumen),
							array('%d', '%s', '%s', '%d', '%f'),
							array('%d')
						);

                        $ret['check'] = $wpdb->rows_affected;
                        $ret['opsi'] = $opsi;
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

    // Setting langsung Verifikasi Upload Dokumen
    public function get_data_penjadwalan_verifikasi_upload_dokumen()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array(),
            'cekJadwalTerbuka' => 0
		);

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if (!empty($_POST['tahun_anggaran'])) {
                    $tahun_anggaran = $_POST['tahun_anggaran'];

                    // auto tambah data jika tidak ada data
                    
                    $cek_jadwal_verifikasi = $wpdb->get_results($wpdb->prepare("
                        SELECT 
                            *
                        FROM esakip_data_jadwal 
                        WHERE tipe = 'verifikasi_upload_dokumen'
                            AND status !=0
                            AND tahun_anggaran=%d
                    ", $tahun_anggaran), ARRAY_A);

                    $started_date = current_time('mysql');
                    $time = strtotime($started_date);
                    $ended_date = date("Y-m-d H:i:s", strtotime("+1 month", $time));

                    if(empty($cek_jadwal_verifikasi)){
                        $default_menu = array(
                            'nama_jadwal'   => 'langsung verifikasi',
                            'started_at'    => $started_date,
                            'end_at'        => $ended_date,
                            'status'        => 1,
                            'tipe'          => 'verifikasi_upload_dokumen',
                            'lama_pelaksanaan' => 1,
                            'tahun_anggaran'    => $tahun_anggaran,
                            'default_verifikasi_upload' => 0
                        );
                        if ($wpdb->insert('esakip_data_jadwal', $default_menu) === false) {
                            error_log("Error inserting into esakip_menu_dokumen: " . $wpdb->last_error);
                        }
                    }

                    $data_jadwal_verifikasi = $wpdb->get_results($wpdb->prepare("
                        SELECT 
                            *
                        FROM esakip_data_jadwal 
                        WHERE tipe = 'verifikasi_upload_dokumen'
                            AND status !=0
                            AND tahun_anggaran=%d
                    ", $tahun_anggaran), ARRAY_A);

                    if (!empty($data_jadwal_verifikasi)) {
                        $tbody = '';
                        $status = '';
                        $cekJadwalTerbuka = 0;
                        foreach ($data_jadwal_verifikasi as $menu) {
                            if($menu['status'] == 0){
                                $status = '<span class="badge badge-dark"> Dihapus </span>';
                            }else if($menu['status'] == 1){
                                $status = '<span class="badge badge-success"> Aktif </span>';
                            }else if($menu['status'] == 2){
                                $status = '<span class="badge badge-secondary"> Dikunci </span>';
                            }

                            if($menu['default_verifikasi_upload'] == 1){
                                $badge_verifikasi_upload = 'dashicons-yes-alt';
                                $langsung_verifikasi = "Iya";
                            }else{
                                $badge_verifikasi_upload = 'dashicons-dismiss';
                                $langsung_verifikasi = "Tidak";
                            }
                            $tbody .= "<tr>";
                            $tbody .= "<td class='text-center'>" . $menu['nama_jadwal'] . "</td>";
                            $tbody .= "<td class='text-center'>" . $status . "</td>";
                            $tbody .= "<td class='text-center'>" . date('d-m-Y H:i', strtotime($menu['started_at'])) . "</td>";
                            $tbody .= "<td class='text-center'>" . date('d-m-Y H:i', strtotime($menu['end_at'])) . "</td>";
                            $tbody .= "<td class='text-center'>" . $tahun_anggaran . "</td>";
                            $tbody .= "<td class='text-center'><span class='dashicons " . $badge_verifikasi_upload . "'></span> " . $langsung_verifikasi . "</td>";
                            $tbody .= "<td class='text-center'>" . $menu['keterangan'] . "</td>";
                            
                            if ($menu['status'] == 1) {
                                $cekJadwalTerbuka++;

                                $edit = '<div class="btn-group mr-2" role="group">
                                            <a class="btn btn-sm btn-warning" style="text-decoration: none;" onclick="edit_data_penjadwalan(\'' . $menu['id'] . '\'); return false;" href="#" title="Edit data penjadwalan"><i class="dashicons dashicons-edit"></i></a>
                                        </div>';
                            }

                            $aksi = $edit;
                            $tbody .= "<td class='text-center'>" . $aksi . "</td>";

                            $tbody .= "</tr>";
                        }
                        $ret['data'] = $tbody;
                        $ret['cekJadwalTerbuka'] = $cekJadwalTerbuka;
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
    
	/** Submit data penjadwalan verifikasi */
	public function submit_jadwal_verifikasi_upload_dokumen()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				if (!empty($_POST['nama_jadwal']) && !empty($_POST['jadwal_mulai']) && !empty($_POST['jadwal_selesai']) && !empty($_POST['tahun_anggaran']) && !empty($_POST['langsung_verifikasi'])) {
					$nama_jadwal		= trim(htmlspecialchars($_POST['nama_jadwal']));
					$jadwal_mulai		= trim(htmlspecialchars($_POST['jadwal_mulai']));
					$jadwal_mulai		= date('Y-m-d H:i:s', strtotime($jadwal_mulai));
					$jadwal_selesai		= trim(htmlspecialchars($_POST['jadwal_selesai']));
					$jadwal_selesai		= date('Y-m-d H:i:s', strtotime($jadwal_selesai));
					$tahun_anggaran		= trim(htmlspecialchars($_POST['tahun_anggaran']));
                    $langsung_verifikasi= trim(htmlspecialchars($_POST['langsung_verifikasi']));

					$get_jadwal = $wpdb->get_results(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_data_jadwal
							WHERE tipe='verifikasi_upload_dokumen'
							  AND tahun_anggaran=%d
							  AND status != 0
						", $tahun_anggaran),
						ARRAY_A
					);

					// cek jadwal lama
					foreach ($get_jadwal as $jadwal) {
						if ($jadwal['status'] != 2) {
							$return = array(
								'status' => 'error',
								'message'	=> 'Masih ada jadwal yang terbuka!'
							);
							die(json_encode($return));
						}
						if ($jadwal_mulai > $jadwal['started_at'] && $jadwal_mulai < $jadwal['end_at'] || $jadwal_selesai > $jadwal['started_at'] && $jadwal_selesai < $jadwal['end_at']) {
							$return = array(
								'status' => 'error',
								'message'	=> 'Waktu sudah dipakai jadwal lain!'
							);
							die(json_encode($return));
						}
					}

                    $setting_langsung_verifikasi = 0;
                    if($langsung_verifikasi == "iya"){
                        $setting_langsung_verifikasi=1;
                    }

					//insert data jadwal
					$data_jadwal = array(
						'nama_jadwal' => $nama_jadwal,
						'started_at' => $jadwal_mulai,
						'end_at' => $jadwal_selesai,
						'status' => 1,
						'tahun_anggaran' => $tahun_anggaran,
						'tipe' => 'verifikasi_upload_dokumen',
						'lama_pelaksanaan' => 1,
                        'default_verifikasi_upload'=>$setting_langsung_verifikasi
					);

					$insert_data = $wpdb->insert(
                        'esakip_data_jadwal', 
                        $data_jadwal,
                        array('%s', '%s', '%s', '%d', '%s', '%s', '%d')
                    );

                    $return = array(
                        'status'		=> 'success',
                        'message'		=> 'Berhasil!',
                        'data_jadwal'	=> $data_jadwal
                    );
				} else {
					$return = array(
						'status' => 'error',
						'message'	=> 'Harap diisi semua,tidak boleh ada yang kosong!'
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
		die(json_encode($return));
	}
    
	public function get_data_jadwal_by_id_verifikasi_upload_dokumen()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				$ret['data'] = $wpdb->get_row($wpdb->prepare('
                    SELECT 
                        *
                    FROM esakip_data_jadwal
                    WHERE id=%d
                ', $_POST['id']), ARRAY_A);
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
    
	public function submit_edit_jadwal_verifikasi_upload_dokumen()
	{
		global $wpdb;
		$user_id = um_user('ID');
		$user_meta = get_userdata($user_id);
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				if (!empty($_POST['id']) && !empty($_POST['nama_jadwal']) && !empty($_POST['jadwal_mulai']) && !empty($_POST['jadwal_selesai']) && !empty($_POST['verifikasi_upload'])) {
					$id = trim(htmlspecialchars($_POST['id']));
					$nama_jadwal	= trim(htmlspecialchars($_POST['nama_jadwal']));
					$jadwal_mulai	= trim(htmlspecialchars($_POST['jadwal_mulai']));
					$jadwal_mulai	= date('Y-m-d H:i:s', strtotime($jadwal_mulai));
					$jadwal_selesai	= trim(htmlspecialchars($_POST['jadwal_selesai']));
					$jadwal_selesai	= date('Y-m-d H:i:s', strtotime($jadwal_selesai));
					$tahun_anggaran	= trim(htmlspecialchars($_POST['tahun_anggaran']));
                    $keterangan = trim(htmlspecialchars($_POST['keterangan']));
                    $verifikasi_upload = trim(htmlspecialchars($_POST['verifikasi_upload']));

                    $verifikasi_upload = ($verifikasi_upload == 'iya') ? 1 : 0;

					$data_this_id = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_data_jadwal WHERE id = %d', $id), ARRAY_A);

					if (!empty($data_this_id)) {
						$status_check = array(1, NULL);
						if (in_array($data_this_id['status'], $status_check)) {
							//update data penjadwalan
							$data_jadwal = array(
								'nama_jadwal' 			=> $nama_jadwal,
								'started_at'			=> $jadwal_mulai,
								'end_at'				=> $jadwal_selesai,
								'tahun_anggaran'		=> $tahun_anggaran,
								'keterangan'            => $keterangan,
                                'default_verifikasi_upload'     => $verifikasi_upload
							);

							$wpdb->update(
                                'esakip_data_jadwal', 
                                $data_jadwal, 
                                array(
								    'id'	=> $id
                                ),
                                array('%s', '%s', '%s', '%d', '%s'),
                                array('%d')
                            );

							$return = array(
								'status'		=> 'success',
								'message'		=> 'Berhasil!',
								'data_jadwal'	=> $data_jadwal
							);
						} else {
							$return = array(
								'status' => 'error',
								'message'	=> "User tidak diijinkan!\nData sudah dikunci!",
							);
						}
					} else {
						$return = array(
							'status' => 'error',
							'message'	=> "Data tidak ditemukan!",
						);
					}
				} else {
					$return = array(
						'status' => 'error',
						'message'	=> 'Harap diisi semua,tidak boleh ada yang kosong!'
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
		die(json_encode($return));
	}

    // Setting langsung Verifikasi Upload Dokumen
    public function get_data_penjadwalan_verifikasi_upload_dokumen_renstra()
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil get data!',
            'data' => array(),
            'cekJadwalTerbuka' => 0
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if (!empty($_POST['tahun_anggaran'])) {
                    $tahun_anggaran = $_POST['tahun_anggaran'];

                    // auto tambah data jika tidak ada data
                    
                    $cek_jadwal_verifikasi = $wpdb->get_results($wpdb->prepare("
                        SELECT 
                            *
                        FROM esakip_data_jadwal 
                        WHERE tipe = 'verifikasi_upload_dokumen'
                            AND status !=0
                            AND tahun_anggaran=%d
                    ", $tahun_anggaran), ARRAY_A);

                    $started_date = current_time('mysql');
                    $time = strtotime($started_date);
                    $ended_date = date("Y-m-d H:i:s", strtotime("+1 month", $time));

                    if(empty($cek_jadwal_verifikasi)){
                        $default_menu = array(
                            'nama_jadwal'   => 'langsung verifikasi',
                            'started_at'    => $started_date,
                            'end_at'        => $ended_date,
                            'status'        => 1,
                            'tipe'          => 'verifikasi_upload_dokumen',
                            'lama_pelaksanaan' => 1,
                            'tahun_anggaran'    => $tahun_anggaran,
                            'default_verifikasi_upload' => 0
                        );
                        if ($wpdb->insert('esakip_data_jadwal', $default_menu) === false) {
                            error_log("Error inserting into esakip_menu_dokumen: " . $wpdb->last_error);
                        }
                    }

                    $data_jadwal_verifikasi = $wpdb->get_results($wpdb->prepare("
                        SELECT 
                            *
                        FROM esakip_data_jadwal 
                        WHERE tipe = 'verifikasi_upload_dokumen'
                            AND status !=0
                            AND tahun_anggaran=%d
                    ", $tahun_anggaran), ARRAY_A);

                    if (!empty($data_jadwal_verifikasi)) {
                        $tbody = '';
                        $status = '';
                        $cekJadwalTerbuka = 0;
                        foreach ($data_jadwal_verifikasi as $menu) {
                            if($menu['status'] == 0){
                                $status = '<span class="badge badge-dark"> Dihapus </span>';
                            }else if($menu['status'] == 1){
                                $status = '<span class="badge badge-success"> Aktif </span>';
                            }else if($menu['status'] == 2){
                                $status = '<span class="badge badge-secondary"> Dikunci </span>';
                            }

                            if($menu['default_verifikasi_upload'] == 1){
                                $badge_verifikasi_upload = 'dashicons-yes-alt';
                                $langsung_verifikasi = "Iya";
                            }else{
                                $badge_verifikasi_upload = 'dashicons-dismiss';
                                $langsung_verifikasi = "Tidak";
                            }
                            $tbody .= "<tr>";
                            $tbody .= "<td class='text-center'>" . $menu['nama_jadwal'] . "</td>";
                            $tbody .= "<td class='text-center'>" . $status . "</td>";
                            $tbody .= "<td class='text-center'>" . date('d-m-Y H:i', strtotime($menu['started_at'])) . "</td>";
                            $tbody .= "<td class='text-center'>" . date('d-m-Y H:i', strtotime($menu['end_at'])) . "</td>";
                            $tbody .= "<td class='text-center'>" . $tahun_anggaran . "</td>";
                            $tbody .= "<td class='text-center'><span class='dashicons " . $badge_verifikasi_upload . "'></span> " . $langsung_verifikasi . "</td>";
                            $tbody .= "<td class='text-center'>" . $menu['keterangan'] . "</td>";
                            
                            if ($menu['status'] == 1) {
                                $cekJadwalTerbuka++;

                                $edit = '<div class="btn-group mr-2" role="group">
                                            <a class="btn btn-sm btn-warning" style="text-decoration: none;" onclick="edit_data_penjadwalan(\'' . $menu['id'] . '\'); return false;" href="#" title="Edit data penjadwalan"><i class="dashicons dashicons-edit"></i></a>
                                        </div>';
                            }

                            $aksi = $edit;
                            $tbody .= "<td class='text-center'>" . $aksi . "</td>";

                            $tbody .= "</tr>";
                        }
                        $ret['data'] = $tbody;
                        $ret['cekJadwalTerbuka'] = $cekJadwalTerbuka;
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
    
    /** Submit data penjadwalan verifikasi */
    public function submit_jadwal_verifikasi_upload_dokumen_renstra()
    {
        global $wpdb;
        $return = array(
            'status' => 'success',
            'data'	=> array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
                if (!empty($_POST['nama_jadwal']) && !empty($_POST['jadwal_mulai']) && !empty($_POST['jadwal_selesai']) && !empty($_POST['tahun_anggaran']) && !empty($_POST['langsung_verifikasi'])) {
                    $nama_jadwal		= trim(htmlspecialchars($_POST['nama_jadwal']));
                    $jadwal_mulai		= trim(htmlspecialchars($_POST['jadwal_mulai']));
                    $jadwal_mulai		= date('Y-m-d H:i:s', strtotime($jadwal_mulai));
                    $jadwal_selesai		= trim(htmlspecialchars($_POST['jadwal_selesai']));
                    $jadwal_selesai		= date('Y-m-d H:i:s', strtotime($jadwal_selesai));
                    $tahun_anggaran		= trim(htmlspecialchars($_POST['tahun_anggaran']));
                    $langsung_verifikasi= trim(htmlspecialchars($_POST['langsung_verifikasi']));

                    $get_jadwal = $wpdb->get_results(
                        $wpdb->prepare("
                            SELECT 
                                * 
                            FROM esakip_data_jadwal
                            WHERE tipe='verifikasi_upload_dokumen'
                                AND tahun_anggaran=%d
                                AND status != 0
                        ", $tahun_anggaran),
                        ARRAY_A
                    );

                    // cek jadwal lama
                    foreach ($get_jadwal as $jadwal) {
                        if ($jadwal['status'] != 2) {
                            $return = array(
                                'status' => 'error',
                                'message'	=> 'Masih ada jadwal yang terbuka!'
                            );
                            die(json_encode($return));
                        }
                        if ($jadwal_mulai > $jadwal['started_at'] && $jadwal_mulai < $jadwal['end_at'] || $jadwal_selesai > $jadwal['started_at'] && $jadwal_selesai < $jadwal['end_at']) {
                            $return = array(
                                'status' => 'error',
                                'message'	=> 'Waktu sudah dipakai jadwal lain!'
                            );
                            die(json_encode($return));
                        }
                    }

                    $setting_langsung_verifikasi = 0;
                    if($langsung_verifikasi == "iya"){
                        $setting_langsung_verifikasi=1;
                    }

                    //insert data jadwal
                    $data_jadwal = array(
                        'nama_jadwal' => $nama_jadwal,
                        'started_at' => $jadwal_mulai,
                        'end_at' => $jadwal_selesai,
                        'status' => 1,
                        'tahun_anggaran' => $tahun_anggaran,
                        'tipe' => 'verifikasi_upload_dokumen',
                        'lama_pelaksanaan' => 1,
                        'default_verifikasi_upload'=>$setting_langsung_verifikasi
                    );

                    $insert_data = $wpdb->insert(
                        'esakip_data_jadwal', 
                        $data_jadwal,
                        array('%s', '%s', '%s', '%d', '%s', '%s', '%d')
                    );

                    $return = array(
                        'status'		=> 'success',
                        'message'		=> 'Berhasil!',
                        'data_jadwal'	=> $data_jadwal
                    );
                } else {
                    $return = array(
                        'status' => 'error',
                        'message'	=> 'Harap diisi semua,tidak boleh ada yang kosong!'
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
        die(json_encode($return));
    }
    
    public function get_data_jadwal_by_id_verifikasi_upload_dokumen_renstra()
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil get data!',
            'data' => array()
        );
        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
                $ret['data'] = $wpdb->get_row($wpdb->prepare('
                    SELECT 
                        *
                    FROM esakip_data_jadwal
                    WHERE id=%d
                ', $_POST['id']), ARRAY_A);
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
    
    public function submit_edit_jadwal_verifikasi_upload_dokumen_renstra()
    {
        global $wpdb;
        $user_id = um_user('ID');
        $user_meta = get_userdata($user_id);
        $return = array(
            'status' => 'success',
            'data'	=> array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
                if (!empty($_POST['id']) && !empty($_POST['nama_jadwal']) && !empty($_POST['jadwal_mulai']) && !empty($_POST['jadwal_selesai']) && !empty($_POST['verifikasi_upload'])) {
                    $id = trim(htmlspecialchars($_POST['id']));
                    $nama_jadwal	= trim(htmlspecialchars($_POST['nama_jadwal']));
                    $jadwal_mulai	= trim(htmlspecialchars($_POST['jadwal_mulai']));
                    $jadwal_mulai	= date('Y-m-d H:i:s', strtotime($jadwal_mulai));
                    $jadwal_selesai	= trim(htmlspecialchars($_POST['jadwal_selesai']));
                    $jadwal_selesai	= date('Y-m-d H:i:s', strtotime($jadwal_selesai));
                    $tahun_anggaran	= trim(htmlspecialchars($_POST['tahun_anggaran']));
                    $keterangan = trim(htmlspecialchars($_POST['keterangan']));
                    $verifikasi_upload = trim(htmlspecialchars($_POST['verifikasi_upload']));

                    $verifikasi_upload = ($verifikasi_upload == 'iya') ? 1 : 0;

                    $data_this_id = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_data_jadwal WHERE id = %d', $id), ARRAY_A);

                    if (!empty($data_this_id)) {
                        $status_check = array(1, NULL);
                        if (in_array($data_this_id['status'], $status_check)) {
                            //update data penjadwalan
                            $data_jadwal = array(
                                'nama_jadwal' 			=> $nama_jadwal,
                                'started_at'			=> $jadwal_mulai,
                                'end_at'				=> $jadwal_selesai,
                                'tahun_anggaran'		=> $tahun_anggaran,
                                'keterangan'            => $keterangan,
                                'default_verifikasi_upload'     => $verifikasi_upload
                            );

                            $wpdb->update(
                                'esakip_data_jadwal', 
                                $data_jadwal, 
                                array(
                                    'id'	=> $id
                                ),
                                array('%s', '%s', '%s', '%d', '%s'),
                                array('%d')
                            );

                            $return = array(
                                'status'		=> 'success',
                                'message'		=> 'Berhasil!',
                                'data_jadwal'	=> $data_jadwal
                            );
                        } else {
                            $return = array(
                                'status' => 'error',
                                'message'	=> "User tidak diijinkan!\nData sudah dikunci!",
                            );
                        }
                    } else {
                        $return = array(
                            'status' => 'error',
                            'message'	=> "Data tidak ditemukan!",
                        );
                    }
                } else {
                    $return = array(
                        'status' => 'error',
                        'message'	=> 'Harap diisi semua,tidak boleh ada yang kosong!'
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
        die(json_encode($return));
    }

    function setting_verifikasi_upload($data)
    {
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($data)) {
			if (!empty($data['id_dokumen']) && !empty($data['id_skpd']) && !empty($data['tahun_anggaran']) && !empty($data['nama_tabel'])) {
                //setting untuk verifikasi upload dokumen
                $current_user = wp_get_current_user();
                
                $cek_langsung_verifikasi = $wpdb->get_var($wpdb->prepare('
                    SELECT 
                        default_verifikasi_upload
                    FROM esakip_data_jadwal
                    WHERE tipe="verifikasi_upload_dokumen"
                        AND tahun_anggaran=%d
                        AND status != 0
                ', $data['tahun_anggaran']));

                $return['cek'] = $cek_langsung_verifikasi;
                if(!empty($cek_langsung_verifikasi)){
                    if($cek_langsung_verifikasi == 1){
                        $wpdb->insert(
                            'esakip_keterangan_verifikator',
                            array(
                                'id_dokumen' => $data['id_dokumen'],
                                'status_verifikasi' => 1,
                                'active' => 1,
                                'user_id' => $current_user->ID,
                                'id_skpd' => $data['id_skpd'],
                                'tahun_anggaran' => $data['tahun_anggaran'],
                                'created_at' => current_time('mysql'),
                                'nama_tabel_dokumen' => $data['nama_tabel']
                            ),
                            array('%d', '%d', '%d', '%d', '%d', '%d', '%s', '%s')
                        );
        
                        if (!$wpdb->insert_id) {
                            error_log("Error inserting into esakip_keterangan_verifikator: " . $wpdb->last_error);
                            $return = array(
                                'status' => 'error',
                                'message' => 'Gagal menyimpan data ke database!'
                            );
                        }
                    }
                }
            }else {
                $return = array(
                    'status' => 'error',
                    'message'	=> 'Ada Data Yang Kosong!'
                );
            }
        } else {
            $return = array(
                'status' => 'error',
                'message'	=> 'Data Tidak Ada!'
            );
        }
        return $return;
    }

    function get_data_upload_dokumen(){
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil get data pengaturan rencana aksi!',
            'data'  => array(),
            'option_renstra' => ''
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if ($ret['status'] != 'error' && empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Tahun anggaran tidak boleh kosong!';
                }
                if ($ret['status'] != 'error'){

                    $jadwal_periode = $wpdb->get_results(
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
                    
                    $option_rpjmd = '<option>Pilih Jadwal RPJMD/RPD</option>';
                    $option_renstra = '<option>Pilih Jadwal RENSTRA</option>';
                    if(!empty($jadwal_periode)){
                        foreach ($jadwal_periode as $jadwal_periode_item) {
                            if (!empty($jadwal_periode_item['tahun_selesai_anggaran']) && $jadwal_periode_item['tahun_selesai_anggaran'] > 1) {
                                $tahun_anggaran_selesai = $jadwal_periode_item['tahun_selesai_anggaran'];
                            } else {
                                $tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
                            }
                            
                            $option_rpjmd .= '<option value="' . $jadwal_periode_item['id'] . '">' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . '</option>';

                            $option_renstra .= '<option value="' . $jadwal_periode_item['id'] . '">' . $jadwal_periode_item['nama_jadwal_renstra'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . '</option>';
                        }
                    }

                    $jadwal_periode_rpjpd = $wpdb->get_results(
                        "
                        SELECT 
                            id,
                            nama_jadwal,
                            nama_jadwal_renstra,
                            tahun_anggaran,
                            lama_pelaksanaan,
                            tahun_selesai_anggaran
                        FROM esakip_data_jadwal
                        WHERE tipe = 'RPJPD'
                          AND status = 1
                            ORDER BY tahun_anggaran DESC",
                        ARRAY_A
                    );

                    $option_rpjpd = '<option>Pilih Jadwal RPJPD</option>';
                    if(!empty($jadwal_periode_rpjpd)){
                        foreach ($jadwal_periode_rpjpd as $jadwal_periode_item_rpjpd) {
                            if (!empty($jadwal_periode_item_rpjpd['tahun_selesai_anggaran']) && $jadwal_periode_item_rpjpd['tahun_selesai_anggaran'] > 1) {
                                $tahun_anggaran_selesai = $jadwal_periode_item_rpjpd['tahun_selesai_anggaran'];
                            } else {
                                $tahun_anggaran_selesai = $jadwal_periode_item_rpjpd['tahun_anggaran'] + $jadwal_periode_item_rpjpd['lama_pelaksanaan'];
                            }
                    
                            $option_rpjpd .= '<option value="' . $jadwal_periode_item_rpjpd['id'] . '">' . $jadwal_periode_item_rpjpd['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item_rpjpd['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . '</option>';
                        }
                    }

                    $data = $wpdb->get_row($wpdb->prepare("
                                SELECT
                                    p.*
                                FROM esakip_pengaturan_upload_dokumen as p
                                JOIN esakip_data_jadwal as j
                                ON p.id_jadwal_rpjmd = j.id
                                WHERE p.tahun_anggaran=%d
                                    AND p.active=1
                            ", $_POST['tahun_anggaran']), ARRAY_A);
                    
                    if(!empty($data)){
                        $ret['data'] = $data;
                    }
                    $ret['option_rpjpd'] = $option_rpjpd;
                    $ret['option_rpjmd'] = $option_rpjmd;
                    $ret['option_renstra'] = $option_renstra;
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

    function submit_pengaturan_upload_dokumen(){
        global $wpdb;
        try {
            if (!empty($_POST)) {
                if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                    if (empty($_POST['tahun_anggaran']) || empty($_POST['id_jadwal_rpjpd']) || empty($_POST['id_jadwal_rpjmd'])) {
                        throw new Exception("Ada data yang kosong!", 1);
                    }

                    $tahun_anggaran = $_POST['tahun_anggaran'];
                    $id_jadwal_rpjpd = $_POST['id_jadwal_rpjpd'];
                    $id_jadwal_rpjmd = $_POST['id_jadwal_rpjmd'];

                    $cek_data_jadwal = $wpdb->get_var(
                        $wpdb->prepare("
                        SELECT 
                            id
                        FROM 
                            esakip_data_jadwal
                        WHERE id=%d
                        AND tipe IN ('RPJPD','RPJMD')
                        AND status=1
                    ", $id_jadwal_rpjpd, $id_jadwal_rpjmd));

                    if (empty($cek_data_jadwal)) {
                        throw new Exception("Id Jadwal tidak cocok!", 1);
                    }

                    $cek_data_pengaturan = $wpdb->get_var(
                        $wpdb->prepare("
                        SELECT 
                            id
                        FROM 
                            esakip_pengaturan_upload_dokumen
                        WHERE tahun_anggaran=%d
                        AND active=1
                    ", $tahun_anggaran));

                    $data = array(
                        'id_jadwal_rpjpd' => $id_jadwal_rpjpd,
                        'id_jadwal_rpjmd' => $id_jadwal_rpjmd,
                        'tahun_anggaran' => $tahun_anggaran,
                        'active' => 1,
                        'created_at' => current_time('mysql'),
                        'update_at' => current_time('mysql')
                    );

                    if (empty($cek_data_pengaturan)) {
                        $wpdb->insert('esakip_pengaturan_upload_dokumen', $data);
                        $message = "Sukses tambah data";
                    } else {
                        $wpdb->update('esakip_pengaturan_upload_dokumen', $data, array('id' => $cek_data_pengaturan));
                        $message = "Sukses edit data";
                    }

                    echo json_encode([
                        'status' => true,
                        'message' => $message,
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