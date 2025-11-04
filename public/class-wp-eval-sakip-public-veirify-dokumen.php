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

    public function list_halaman_laporan_pk($atts)
    {
        // untuk disable render shortcode di halaman edit page/post
        if (!empty($_GET) && !empty($_GET['POST'])) {
            return '';
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-laporan-pk.php';
    }

    public function detail_laporan_pk($atts)
    {
        // untuk disable render shortcode di halaman edit page/post
        if (!empty($_GET) && !empty($_GET['POST'])) {
            return '';
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-laporan-pk-per-skpd.php';
    }

    public function halaman_laporan_pk_setting($atts)
    {
        // untuk disable render shortcode di halaman edit page/post
        if (!empty($_GET) && !empty($_GET['POST'])) {
            return '';
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-laporan-pk-setting.php';
    }

    public function list_pegawai_laporan_pk($atts)
    {
        // untuk disable render shortcode di halaman edit page/post
        if (!empty($_GET) && !empty($_GET['POST'])) {
            return '';
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-list-pegawai-laporan-pk.php';
    }

    public function list_perjanjian_kinerja($atts)
    {
        // untuk disable render shortcode di halaman edit page/post
        if (!empty($_GET) && !empty($_GET['POST'])) {
            return '';
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/monev-kinerja/wp-eval-sakip-list-pegawai-perjanjian-kinerja.php';
    }

    public function perjanjian_kinerja_publik($atts)
    {
        // untuk disable render shortcode di halaman edit page/post
        if (!empty($_GET) && !empty($_GET['POST'])) {
            return '';
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/monev-kinerja/wp-eval-sakip-perjanjian-kinerja-publik.php';
    }

    public function detail_laporan_rhk($atts)
    {
        // untuk disable render shortcode di halaman edit page/post
        if (!empty($_GET) && !empty($_GET['POST'])) {
            return '';
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/monev-kinerja/wp-eval-sakip-detail-laporan-rhk-pegawai.php';
    }

    public function dokumen_detail_kuesioner($atts)
    {
        // untuk disable render shortcode di halaman edit page/post
        if (!empty($_GET) && !empty($_GET['POST'])) {
            return '';
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-dokumen-kuesioner.php';
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
                    $cek = $this->generate_pengaturan_menu($tahun_anggaran, $_POST['tipe']);

                    $where_jenis_user = '';
                    if (!empty($_POST['tipe'])) {
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
                            if ($menu['jenis_role'] == 1) {
                                $akses_user .= "<li>Pemerintah Daerah</li>";
                            } else if ($menu['jenis_role'] == 2) {
                                $akses_user .= "<li>Perangkat Daerah</li>";
                            } else if ($menu['jenis_role'] == 3) {
                                $akses_user .= "<li>Pemerintah Daerah</li>
                                                <li>Perangkat Daerah</li>";
                            }
                            $akses_user .= "</ul>";

                            $tbody .= "<tr>";
                            $tbody .= "<td class='text-center'>" . $counter++ . "</td>";
                            $tbody .= "<td class='text-left'>" . $menu['nama_tabel'] . "</td>";
                            $tbody .= "<td class='text-left'>" . $menu['nama_dokumen'] . "</td>";
                            $tbody .= "<td class='text-left'>" . $akses_user . "</td>";
                            if ($tipe == 'perangkat_daerah') {
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

                    $menu_list = ['PERENCANAAN', 'PENGUKURAN_KINERJA', 'PELAPORAN', 'EVALUASI'];
                    $menu_pemda_terpilih = get_option('sakip_menu_khusus_menu_terpilih_pemda_' . $tahun_anggaran);
                    $menu_opd_terpilih = get_option('sakip_menu_khusus_menu_terpilih_opd_' . $tahun_anggaran);
                    if (!$menu_pemda_terpilih) $menu_pemda_terpilih = 'PERENCANAAN';
                    if (!$menu_opd_terpilih) $menu_opd_terpilih = 'PERENCANAAN';

                    $set_html_pemda = get_option('sakip_menu_khusus_set_html_pemda_' . $menu_pemda_terpilih . '_' . $tahun_anggaran);
                    $set_html_opd = get_option('sakip_menu_khusus_set_html_opd_' . $menu_opd_terpilih . '_' . $tahun_anggaran);

                    $tbody = '';

                    // Pemda
                    $tbody .= "<tr>";
                    $tbody .= "<td class='text-center'>1</td>";
                    $tbody .= "<td class='text-left'>Pemerintah Daerah</td>";
                    $tbody .= "<td class='text-left'>";

                    foreach ($menu_list as $menu) {
                        $menu_html = get_option('sakip_menu_khusus_set_html_pemda_' . $menu . '_' . $tahun_anggaran);
                        $tbody .= "<div style='margin-bottom: 10px;'>
                                    <label><strong>$menu :</strong></label>
                                    <textarea class='form-control' id='set_html_menu_khusus_pemda_$menu' rows='2'>" . stripslashes(htmlspecialchars_decode($menu_html)) . "</textarea>
                                   </div>";
                    }

                    $tbody .= "</td>";
                    $tbody .= "<td class='text-center'>
                                <button class='btn btn-primary' onclick='simpan_menu_khusus(\"pemda\"); return false;' title='Simpan Data'>
                                    <span class='dashicons dashicons-saved'></span>
                                </button>
                              </td>";
                    $tbody .= "</tr>";

                    // OPD
                    $tbody .= "<tr>";
                    $tbody .= "<td class='text-center'>2</td>";
                    $tbody .= "<td class='text-left'>Perangkat Daerah</td>";
                    $tbody .= "<td class='text-left'>";

                    foreach ($menu_list as $menu) {
                        $menu_html = get_option('sakip_menu_khusus_set_html_opd_' . $menu . '_' . $tahun_anggaran);
                        $tbody .= "<div style='margin-bottom: 10px;'>
                                    <label><strong>$menu :</strong></label>
                                    <textarea class='form-control' id='set_html_menu_khusus_opd_$menu' rows='2'>" . stripslashes(htmlspecialchars_decode($menu_html)) . "</textarea>
                                   </div>";
                    }

                    $tbody .= "</td>";
                    $tbody .= "<td class='text-center'>
                                <button class='btn btn-primary' onclick='simpan_menu_khusus(\"opd\"); return false;' title='Simpan Data'>
                                    <span class='dashicons dashicons-saved'></span>
                                </button>
                              </td>";
                    $tbody .= "</tr>";

                    $ret['data'] = $tbody;
                } else {
                    $ret = array(
                        'status' => 'error',
                        'message' => 'Ada data yang kosong!'
                    );
                }
            } else {
                $ret = array(
                    'status' => 'error',
                    'message' => 'Api Key tidak sesuai!'
                );
            }
        } else {
            $ret = array('status' => 'error', 'message' => 'Format tidak sesuai!');
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
                $menu_data = !empty($_POST['menu_data']) ? $_POST['menu_data'] : array();
                $tahun_anggaran = !empty($_POST['tahun_anggaran']) ? $_POST['tahun_anggaran'] : '';
                $tipe = !empty($_POST['tipe']) ? $_POST['tipe'] : '';
                
                if (empty($tahun_anggaran)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Tahun Anggaran kosong!';
                } elseif (empty($tipe)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Tipe kosong!';
                } elseif (empty($menu_data)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Data menu kosong!';
                }
                
                if ($ret['status'] == 'success') {
                    foreach ($menu_data as $menu => $set_html) {
                        $menu_key = strtoupper($menu);
                        if ($tipe == 'opd') {
                            update_option('sakip_menu_khusus_set_html_opd_' . $menu_key . '_' . $tahun_anggaran, trim(htmlspecialchars($set_html)));
                        } else if ($tipe == 'pemda') {
                            update_option('sakip_menu_khusus_set_html_pemda_' . $menu_key . '_' . $tahun_anggaran, trim(htmlspecialchars($set_html)));
                        }
                    }
                }
            } else {
                $ret = array('status' => 'error', 'message' => 'Api Key tidak sesuai!');
            }
        } else {
            $ret = array('status' => 'error', 'message' => 'Format tidak sesuai!');
        }
        
        die(json_encode($ret));
    }

    // public function get_html_menu_khusus_opd_by_menu()
    // {
    //     global $wpdb;
    //     $ret = array(
    //         'status' => 'success',
    //         'message' => 'Berhasil get data!'
    //     );

    //     if (!empty($_POST)) {
    //         if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
    //             $tahun = $_POST['tahun_anggaran'];
    //             $menu = strtolower($_POST['menu']);
    //             $data = get_option('sakip_menu_khusus_set_html_opd_' . $menu . '_' . $tahun);

    //             $ret = [
    //                 'status' => 'success',
    //                 'message' => 'Berhasil memuat data',
    //                 'data' => stripslashes(htmlspecialchars_decode($data))
    //             ];
    //         } else {
    //             $ret = array(
    //                 'status' => 'error',
    //                 'message'   => 'Api Key tidak sesuai!'
    //             );
    //         }
    //     } else {
    //         $ret = array(
    //             'status' => 'error',
    //             'message'   => 'Format tidak sesuai!'
    //         );
    //     }
    //     die(json_encode($ret));
    // }

    // public function get_html_menu_khusus_pemda_by_menu()
    // {
    //     global $wpdb;
    //     $ret = array(
    //         'status' => 'success',
    //         'message' => 'Berhasil get data!'
    //     );

    //     if (!empty($_POST)) {
    //         if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
    //             $tahun = $_POST['tahun_anggaran'];
    //             $menu = strtolower($_POST['menu']);
    //             $data = get_option('sakip_menu_khusus_set_html_pemda_' . $menu . '_' . $tahun);

    //             $ret = [
    //                 'status' => 'success',
    //                 'message' => 'Berhasil memuat data',
    //                 'data' => stripslashes(htmlspecialchars_decode($data))
    //             ];
    //         } else {
    //             $ret = array(
    //                 'status' => 'error',
    //                 'message'   => 'Api Key tidak sesuai!'
    //             );
    //         }
    //     } else {
    //         $ret = array(
    //             'status' => 'error',
    //             'message'   => 'Format tidak sesuai!'
    //         );
    //     }
    //     die(json_encode($ret));
    // }

    public function generate_pengaturan_menu($tahun_anggaran, $tipe)
    {
        global $wpdb;
        $return = array(
            'status' => 'success',
            'data'    => array()
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
                    foreach ($cek_menu_all_db as $m) {
                        $cek_menu_all[$m['nama_tabel']] = $m;
                    }
                    if ($tipe == 'pemerintah_daerah') {
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
                            if (
                                empty($cek_menu_all[$menu['nama_tabel']])
                            ) {
                                if ($menu['user_role'] == 'pemerintah_daerah') {
                                    $jenis_role = 1;
                                } else if ($menu['user_role'] == 'perangkat_daerah') {
                                    $jenis_role = 2;
                                }

                                if (!empty($menu['verifikasi_upload_dokumen'])) {
                                    $verifikasi_upload = 1;
                                } else {
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
                        foreach ($cek_menu_all as $m) {
                            $wpdb->delete('esakip_menu_dokumen', array('id' => $m['id']));
                        }
                    } else if ($tipe == 'perangkat_daerah') {
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
                            if (
                                empty($cek_menu_all[$menu['nama_tabel']])
                            ) {
                                if ($menu['user_role'] == 'pemerintah_daerah') {
                                    $jenis_role = 1;
                                } else if ($menu['user_role'] == 'perangkat_daerah') {
                                    $jenis_role = 2;
                                }

                                if (!empty($menu['verifikasi_upload_dokumen'])) {
                                    $verifikasi_upload = 1;
                                } else {
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
                        foreach ($cek_menu_all as $m) {
                            $wpdb->delete('esakip_menu_dokumen', array('id' => $m['id']));
                        }
                    }
                } else {
                    $return = array(
                        'status' => 'error',
                        'message'    => 'Ada Data Yang Kosong!'
                    );
                }
            } else {
                $return = array(
                    'status' => 'error',
                    'message'    => 'Api Key tidak sesuai!'
                );
            }
        } else {
            $return = array(
                'status' => 'error',
                'message'    => 'Format tidak sesuai!'
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
                            if ($default_urutan == 0.00 || empty($default_urutan)) {
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

                    if (!empty($data_menu)) {
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
                    } else {
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

                    if (empty($cek_jadwal_verifikasi)) {
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
                            if ($menu['status'] == 0) {
                                $status = '<span class="badge badge-dark"> Dihapus </span>';
                            } else if ($menu['status'] == 1) {
                                $status = '<span class="badge badge-success"> Aktif </span>';
                            } else if ($menu['status'] == 2) {
                                $status = '<span class="badge badge-secondary"> Dikunci </span>';
                            }

                            if ($menu['default_verifikasi_upload'] == 1) {
                                $badge_verifikasi_upload = 'dashicons-yes-alt';
                                $langsung_verifikasi = "Iya";
                            } else {
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
            'data'    => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
                if (!empty($_POST['nama_jadwal']) && !empty($_POST['jadwal_mulai']) && !empty($_POST['jadwal_selesai']) && !empty($_POST['tahun_anggaran']) && !empty($_POST['langsung_verifikasi'])) {
                    $nama_jadwal        = trim(htmlspecialchars($_POST['nama_jadwal']));
                    $jadwal_mulai        = trim(htmlspecialchars($_POST['jadwal_mulai']));
                    $jadwal_mulai        = date('Y-m-d H:i:s', strtotime($jadwal_mulai));
                    $jadwal_selesai        = trim(htmlspecialchars($_POST['jadwal_selesai']));
                    $jadwal_selesai        = date('Y-m-d H:i:s', strtotime($jadwal_selesai));
                    $tahun_anggaran        = trim(htmlspecialchars($_POST['tahun_anggaran']));
                    $langsung_verifikasi = trim(htmlspecialchars($_POST['langsung_verifikasi']));

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
                                'message'    => 'Masih ada jadwal yang terbuka!'
                            );
                            die(json_encode($return));
                        }
                        if ($jadwal_mulai > $jadwal['started_at'] && $jadwal_mulai < $jadwal['end_at'] || $jadwal_selesai > $jadwal['started_at'] && $jadwal_selesai < $jadwal['end_at']) {
                            $return = array(
                                'status' => 'error',
                                'message'    => 'Waktu sudah dipakai jadwal lain!'
                            );
                            die(json_encode($return));
                        }
                    }

                    $setting_langsung_verifikasi = 0;
                    if ($langsung_verifikasi == "iya") {
                        $setting_langsung_verifikasi = 1;
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
                        'default_verifikasi_upload' => $setting_langsung_verifikasi
                    );

                    $insert_data = $wpdb->insert(
                        'esakip_data_jadwal',
                        $data_jadwal,
                        array('%s', '%s', '%s', '%d', '%s', '%s', '%d')
                    );

                    $return = array(
                        'status'        => 'success',
                        'message'        => 'Berhasil!',
                        'data_jadwal'    => $data_jadwal
                    );
                } else {
                    $return = array(
                        'status' => 'error',
                        'message'    => 'Harap diisi semua,tidak boleh ada yang kosong!'
                    );
                }
            } else {
                $return = array(
                    'status' => 'error',
                    'message'    => 'Api Key tidak sesuai!'
                );
            }
        } else {
            $return = array(
                'status' => 'error',
                'message'    => 'Format tidak sesuai!'
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
            'data'    => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
                if (!empty($_POST['id']) && !empty($_POST['nama_jadwal']) && !empty($_POST['jadwal_mulai']) && !empty($_POST['jadwal_selesai']) && !empty($_POST['verifikasi_upload'])) {
                    $id = trim(htmlspecialchars($_POST['id']));
                    $nama_jadwal    = trim(htmlspecialchars($_POST['nama_jadwal']));
                    $jadwal_mulai    = trim(htmlspecialchars($_POST['jadwal_mulai']));
                    $jadwal_mulai    = date('Y-m-d H:i:s', strtotime($jadwal_mulai));
                    $jadwal_selesai    = trim(htmlspecialchars($_POST['jadwal_selesai']));
                    $jadwal_selesai    = date('Y-m-d H:i:s', strtotime($jadwal_selesai));
                    $tahun_anggaran    = trim(htmlspecialchars($_POST['tahun_anggaran']));
                    $keterangan = trim(htmlspecialchars($_POST['keterangan']));
                    $verifikasi_upload = trim(htmlspecialchars($_POST['verifikasi_upload']));

                    $verifikasi_upload = ($verifikasi_upload == 'iya') ? 1 : 0;

                    $data_this_id = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_data_jadwal WHERE id = %d', $id), ARRAY_A);

                    if (!empty($data_this_id)) {
                        $status_check = array(1, NULL);
                        if (in_array($data_this_id['status'], $status_check)) {
                            //update data penjadwalan
                            $data_jadwal = array(
                                'nama_jadwal'             => $nama_jadwal,
                                'started_at'            => $jadwal_mulai,
                                'end_at'                => $jadwal_selesai,
                                'tahun_anggaran'        => $tahun_anggaran,
                                'keterangan'            => $keterangan,
                                'default_verifikasi_upload'     => $verifikasi_upload
                            );

                            $wpdb->update(
                                'esakip_data_jadwal',
                                $data_jadwal,
                                array(
                                    'id'    => $id
                                ),
                                array('%s', '%s', '%s', '%d', '%s'),
                                array('%d')
                            );

                            $return = array(
                                'status'        => 'success',
                                'message'        => 'Berhasil!',
                                'data_jadwal'    => $data_jadwal
                            );
                        } else {
                            $return = array(
                                'status' => 'error',
                                'message'    => "User tidak diijinkan!\nData sudah dikunci!",
                            );
                        }
                    } else {
                        $return = array(
                            'status' => 'error',
                            'message'    => "Data tidak ditemukan!",
                        );
                    }
                } else {
                    $return = array(
                        'status' => 'error',
                        'message'    => 'Harap diisi semua,tidak boleh ada yang kosong!'
                    );
                }
            } else {
                $return = array(
                    'status' => 'error',
                    'message'    => 'Api Key tidak sesuai!'
                );
            }
        } else {
            $return = array(
                'status' => 'error',
                'message'    => 'Format tidak sesuai!'
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

                    if (empty($cek_jadwal_verifikasi)) {
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
                            if ($menu['status'] == 0) {
                                $status = '<span class="badge badge-dark"> Dihapus </span>';
                            } else if ($menu['status'] == 1) {
                                $status = '<span class="badge badge-success"> Aktif </span>';
                            } else if ($menu['status'] == 2) {
                                $status = '<span class="badge badge-secondary"> Dikunci </span>';
                            }

                            if ($menu['default_verifikasi_upload'] == 1) {
                                $badge_verifikasi_upload = 'dashicons-yes-alt';
                                $langsung_verifikasi = "Iya";
                            } else {
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
            'data'    => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
                if (!empty($_POST['nama_jadwal']) && !empty($_POST['jadwal_mulai']) && !empty($_POST['jadwal_selesai']) && !empty($_POST['tahun_anggaran']) && !empty($_POST['langsung_verifikasi'])) {
                    $nama_jadwal        = trim(htmlspecialchars($_POST['nama_jadwal']));
                    $jadwal_mulai        = trim(htmlspecialchars($_POST['jadwal_mulai']));
                    $jadwal_mulai        = date('Y-m-d H:i:s', strtotime($jadwal_mulai));
                    $jadwal_selesai        = trim(htmlspecialchars($_POST['jadwal_selesai']));
                    $jadwal_selesai        = date('Y-m-d H:i:s', strtotime($jadwal_selesai));
                    $tahun_anggaran        = trim(htmlspecialchars($_POST['tahun_anggaran']));
                    $langsung_verifikasi = trim(htmlspecialchars($_POST['langsung_verifikasi']));

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
                                'message'    => 'Masih ada jadwal yang terbuka!'
                            );
                            die(json_encode($return));
                        }
                        if ($jadwal_mulai > $jadwal['started_at'] && $jadwal_mulai < $jadwal['end_at'] || $jadwal_selesai > $jadwal['started_at'] && $jadwal_selesai < $jadwal['end_at']) {
                            $return = array(
                                'status' => 'error',
                                'message'    => 'Waktu sudah dipakai jadwal lain!'
                            );
                            die(json_encode($return));
                        }
                    }

                    $setting_langsung_verifikasi = 0;
                    if ($langsung_verifikasi == "iya") {
                        $setting_langsung_verifikasi = 1;
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
                        'default_verifikasi_upload' => $setting_langsung_verifikasi
                    );

                    $insert_data = $wpdb->insert(
                        'esakip_data_jadwal',
                        $data_jadwal,
                        array('%s', '%s', '%s', '%d', '%s', '%s', '%d')
                    );

                    $return = array(
                        'status'        => 'success',
                        'message'        => 'Berhasil!',
                        'data_jadwal'    => $data_jadwal
                    );
                } else {
                    $return = array(
                        'status' => 'error',
                        'message'    => 'Harap diisi semua,tidak boleh ada yang kosong!'
                    );
                }
            } else {
                $return = array(
                    'status' => 'error',
                    'message'    => 'Api Key tidak sesuai!'
                );
            }
        } else {
            $return = array(
                'status' => 'error',
                'message'    => 'Format tidak sesuai!'
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
            'data'    => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
                if (!empty($_POST['id']) && !empty($_POST['nama_jadwal']) && !empty($_POST['jadwal_mulai']) && !empty($_POST['jadwal_selesai']) && !empty($_POST['verifikasi_upload'])) {
                    $id = trim(htmlspecialchars($_POST['id']));
                    $nama_jadwal    = trim(htmlspecialchars($_POST['nama_jadwal']));
                    $jadwal_mulai    = trim(htmlspecialchars($_POST['jadwal_mulai']));
                    $jadwal_mulai    = date('Y-m-d H:i:s', strtotime($jadwal_mulai));
                    $jadwal_selesai    = trim(htmlspecialchars($_POST['jadwal_selesai']));
                    $jadwal_selesai    = date('Y-m-d H:i:s', strtotime($jadwal_selesai));
                    $tahun_anggaran    = trim(htmlspecialchars($_POST['tahun_anggaran']));
                    $keterangan = trim(htmlspecialchars($_POST['keterangan']));
                    $verifikasi_upload = trim(htmlspecialchars($_POST['verifikasi_upload']));

                    $verifikasi_upload = ($verifikasi_upload == 'iya') ? 1 : 0;

                    $data_this_id = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_data_jadwal WHERE id = %d', $id), ARRAY_A);

                    if (!empty($data_this_id)) {
                        $status_check = array(1, NULL);
                        if (in_array($data_this_id['status'], $status_check)) {
                            //update data penjadwalan
                            $data_jadwal = array(
                                'nama_jadwal'             => $nama_jadwal,
                                'started_at'            => $jadwal_mulai,
                                'end_at'                => $jadwal_selesai,
                                'tahun_anggaran'        => $tahun_anggaran,
                                'keterangan'            => $keterangan,
                                'default_verifikasi_upload'     => $verifikasi_upload
                            );

                            $wpdb->update(
                                'esakip_data_jadwal',
                                $data_jadwal,
                                array(
                                    'id'    => $id
                                ),
                                array('%s', '%s', '%s', '%d', '%s'),
                                array('%d')
                            );

                            $return = array(
                                'status'        => 'success',
                                'message'        => 'Berhasil!',
                                'data_jadwal'    => $data_jadwal
                            );
                        } else {
                            $return = array(
                                'status' => 'error',
                                'message'    => "User tidak diijinkan!\nData sudah dikunci!",
                            );
                        }
                    } else {
                        $return = array(
                            'status' => 'error',
                            'message'    => "Data tidak ditemukan!",
                        );
                    }
                } else {
                    $return = array(
                        'status' => 'error',
                        'message'    => 'Harap diisi semua,tidak boleh ada yang kosong!'
                    );
                }
            } else {
                $return = array(
                    'status' => 'error',
                    'message'    => 'Api Key tidak sesuai!'
                );
            }
        } else {
            $return = array(
                'status' => 'error',
                'message'    => 'Format tidak sesuai!'
            );
        }
        die(json_encode($return));
    }

    function setting_verifikasi_upload($data)
    {
        global $wpdb;
        $return = array(
            'status' => 'success',
            'data'    => array()
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
                if (!empty($cek_langsung_verifikasi)) {
                    if ($cek_langsung_verifikasi == 1) {
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
                } else {
                    $wpdb->insert(
                        'esakip_keterangan_verifikator',
                        array(
                            'id_dokumen' => $data['id_dokumen'],
                            'status_verifikasi' => 3,
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
            } else {
                $return = array(
                    'status' => 'error',
                    'message'    => 'Ada Data Yang Kosong!'
                );
            }
        } else {
            $return = array(
                'status' => 'error',
                'message'    => 'Data Tidak Ada!'
            );
        }
        return $return;
    }

    function get_data_upload_dokumen()
    {
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
                if ($ret['status'] != 'error') {

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
                    if (!empty($jadwal_periode)) {
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
                    if (!empty($jadwal_periode_rpjpd)) {
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

                    if (!empty($data)) {
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

    function submit_pengaturan_upload_dokumen()
    {
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

                    $cek_data_rpjpd = $wpdb->get_var(
                        $wpdb->prepare("
                        SELECT 
                            id
                        FROM 
                            esakip_data_jadwal
                        WHERE id=%d
                        AND tipe IN ('RPJPD')
                        AND status=1
                    ", $id_jadwal_rpjpd)
                    );

                    if (empty($cek_data_rpjpd)) {
                        throw new Exception("Id Jadwal RPJPD tidak ditemukan!", 1);
                    }
                    $cek_data_rpjmd = $wpdb->get_var(
                        $wpdb->prepare("
                        SELECT 
                            id
                        FROM 
                            esakip_data_jadwal
                        WHERE id=%d
                        AND tipe IN ('RPJMD')
                        AND status=1
                    ", $id_jadwal_rpjmd)
                    );

                    if (empty($cek_data_rpjmd)) {
                        throw new Exception("Id Jadwal RPJMD tidak ditemukan!", 1);
                    }

                    $cek_data_pengaturan = $wpdb->get_var(
                        $wpdb->prepare("
                        SELECT 
                            id
                        FROM 
                            esakip_pengaturan_upload_dokumen
                        WHERE tahun_anggaran=%d
                        AND active=1
                    ", $tahun_anggaran)
                    );

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

    public function get_table_skpd_laporan_pk()
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

                        $halaman_pegawai_skpd = $this->functions->generatePage(array(
                            'nama_page' => 'List Pegawai Laporan PK ' . $tahun_anggaran,
                            'content' => '[list_pegawai_laporan_pk tahun_anggaran=' . $tahun_anggaran . ']',
                            'show_header' => 1,
                            'post_status' => 'private'
                        ));

                        foreach ($unit as $kk => $vv) {
                            $total_pegawai_all = 0;

                            $mapping_unit_simpeg = $wpdb->get_row(
                                $wpdb->prepare("
                                    SELECT 
                                        a.*,
                                        b.satker_id
                                    FROM esakip_data_mapping_unit_sipd_simpeg a 
                                    LEFT JOIN esakip_data_satker_simpeg b 
                                           ON b.satker_id = a.id_satker_simpeg 
                                          AND b.tahun_anggaran = a.tahun_anggaran 
                                          AND b.active = 1
                                    WHERE a.tahun_anggaran = %d 
                                      AND a.id_skpd = %d
                                ", $tahun_anggaran, $vv['id_skpd']),
                                ARRAY_A
                            );

                            $satker_id = 0;
                            if (!empty($mapping_unit_simpeg)) {
                                $satker_id = $mapping_unit_simpeg['satker_id'];
                                $data_pegawai = $wpdb->get_row(
                                    $wpdb->prepare("
                                        SELECT 
                                            COUNT(id) AS total_pegawai
                                        FROM esakip_data_pegawai_simpeg
                                        WHERE satker_id LIKE %s 
                                          AND active = %d
                                    ", $satker_id . '%', 1),
                                    ARRAY_A
                                );
                                if (!empty($data_pegawai)) {
                                    $total_pegawai_all = $data_pegawai['total_pegawai'];
                                }
                            }

                            $count_finalisasi = $wpdb->get_var(
                                $wpdb->prepare("
                                    SELECT 
                                        COUNT(id)
                                    FROM esakip_finalisasi_tahap_laporan_pk 
                                    WHERE active = 1 
                                      AND tahun_anggaran = %d
                                      AND id_skpd = %d
                                ", $tahun_anggaran, $vv['id_skpd'])
                            );

                            $count_rhk = $wpdb->get_var(
                                $wpdb->prepare("
                                    SELECT 
                                        COUNT(id)
                                    FROM esakip_data_rencana_aksi_opd 
                                    WHERE active = 1 
                                      AND tahun_anggaran = %d
                                      AND id_skpd = %d
                                ", $tahun_anggaran, $vv['id_skpd'])
                            );

                            $tbody .= "<tr>";
                            $tbody .= "<td style='text-transform: uppercase;'><a href='" . $halaman_pegawai_skpd['url'] . "&id_skpd=" . $vv['id_skpd'] . "' target='_blank'>" . $vv['kode_skpd'] . " " . $vv['nama_skpd'] . "</a></td>";
                            $tbody .= "<td class='text-center'>" . number_format($total_pegawai_all, 0, ",", ".") . "</td>";
                            $tbody .= "<td class='text-center'>" . $count_rhk . "</td>";
                            $tbody .= "<td class='text-center'>" . $count_finalisasi . "</td>";
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

    public function simpan_pegawai_simpeg()
    {
        global $wpdb;
        $ret = array(
            'status'  => 'success',
            'message' => 'Berhasil ubah data!'
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if (empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Tahun anggaran tidak boleh kosong!';
                    die(json_encode($ret));
                } else if (empty($_POST['id_skpd'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'ID SKPD tidak boleh kosong!';
                    die(json_encode($ret));
                } else if (empty($_POST['ids'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Data pegawai tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
                $unit = $this->get_data_skpd_by_id($_POST['id_skpd'], $tahun_anggaran_sakip);
                if (empty($unit)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Unit aktif tidak ditemukan!';
                    die(json_encode($ret));
                }

                $mapping_satker_id = $wpdb->get_var(
                    $wpdb->prepare("
                        SELECT
                            b.satker_id
                        FROM esakip_data_mapping_unit_sipd_simpeg a 
                        LEFT JOIN esakip_data_satker_simpeg b 
                               ON b.satker_id = a.id_satker_simpeg 
                              AND b.tahun_anggaran = a.tahun_anggaran 
                              AND b.active = 1
                        WHERE a.tahun_anggaran = %d 
                          AND a.id_skpd = %d;
                    ", $_POST['tahun_anggaran'], $unit['id_skpd'])
                );

                if (empty($mapping_satker_id)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Mapping unit tidak ditemukan!';
                    die(json_encode($ret));
                }
                $satker_id = $mapping_satker_id;

                $pegawai_aktif = array();
                $pegawai_non_aktif = array();
                foreach ($_POST['ids'] as $id => $val) {
                    $id = $wpdb->prepare('%d', $id);
                    if ($val == 1) {
                        $pegawai_aktif[] = $id;
                    } else {
                        $pegawai_non_aktif[] = $id;
                    }
                }

                if (!empty($pegawai_aktif)) {
                    $pegawai_aktif = implode(',', $pegawai_aktif);
                    $sql = '
                        UPDATE esakip_data_pegawai_simpeg
                        SET active_rhk=1
                        WHERE id IN (' . $pegawai_aktif . ')
                            AND satker_id like \'' . $satker_id . '%\'
                    ';
                    $ret['sql_aktif'] = $sql;
                    $wpdb->query($sql);
                }

                if (!empty($pegawai_non_aktif)) {
                    $pegawai_non_aktif = implode(',', $pegawai_non_aktif);
                    $sql = '
                        UPDATE esakip_data_pegawai_simpeg
                        SET active_rhk=0
                        WHERE id IN (' . $pegawai_non_aktif . ')
                            AND satker_id like \'' . $satker_id . '%\'
                    ';
                    $ret['sql_non_aktif'] = $sql;
                    $wpdb->query($sql);
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

    public function get_table_pegawai_simpeg()
    {
        global $wpdb;
        $ret = array(
            'status'  => 'success',
            'message' => 'Berhasil get data!',
            'data'    => '<tr><td colspan="6" class="text-center">Tidak ada data ditemukan</td></tr>',
            'aktif'   => 0,
            'non_aktif'   => 0
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if (empty($_POST['tahun_anggaran']) || empty($_POST['id_skpd'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Parameter tidak valid!';
                    die(json_encode($ret));
                }

                $tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
                $unit = $this->get_data_skpd_by_id($_POST['id_skpd'], $tahun_anggaran_sakip);
                if (empty($unit)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Unit aktif tidak ditemukan!';
                    die(json_encode($ret));
                }

                $tbody = '';
                $mapping_satker_id = $wpdb->get_var(
                    $wpdb->prepare("
                        SELECT
                            b.satker_id
                        FROM esakip_data_mapping_unit_sipd_simpeg a 
                        LEFT JOIN esakip_data_satker_simpeg b 
                               ON b.satker_id = a.id_satker_simpeg 
                              AND b.tahun_anggaran = a.tahun_anggaran 
                              AND b.active = 1
                        WHERE a.tahun_anggaran = %d 
                          AND a.id_skpd = %d;
                    ", $_POST['tahun_anggaran'], $unit['id_skpd'])
                );

                if (empty($mapping_satker_id)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Mapping unit tidak ditemukan!';
                    die(json_encode($ret));
                }

                $data_pegawai_all = array();
                $satker_id = $mapping_satker_id;
                $data_pegawai = $wpdb->get_results(
                    $wpdb->prepare("
                        SELECT 
                            p.id,
                            p.active_rhk,
                            p.nip_baru,
                            p.nama_pegawai,
                            p.satker_id,
                            p.jabatan,
                            p.tipe_pegawai,
                            p.tipe_pegawai_id,
                            p.active,
                            p.id_atasan,
                            s.nama AS nama_bidang,
                            p.custom_jabatan
                        FROM esakip_data_pegawai_simpeg p
                        LEFT JOIN esakip_data_satker_simpeg s
                            ON s.satker_id = p.satker_id
                        WHERE p.satker_id LIKE %s 
                          AND p.active = 1
                          AND s.tahun_anggaran = %d
                        ORDER BY p.satker_id, p.tipe_pegawai_id, p.berakhir DESC, p.nama_pegawai
                    ", $satker_id . '%', $_POST['tahun_anggaran']),
                    ARRAY_A
                );
                $all_atasan = array();
                if (!empty($data_pegawai)) {
                    foreach ($data_pegawai as $v_1) {
                        if (
                            strtoupper(trim($v_1['jabatan'])) == 'KEPALA'
                            && $v_1['satker_id'] == $satker_id
                        ) {
                            array_unshift($data_pegawai_all, $v_1);
                        } else {
                            $data_pegawai_all[] = $v_1;
                        }
                        if (!empty($v_1['id_atasan'])) {
                            $all_atasan[$v_1['id_atasan']] = $v_1['id_atasan'];
                        }
                    }
                }

                if (empty($data_pegawai_all)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Pegawai tidak ditemukan!';
                    die(json_encode($ret));
                }

                if (!empty($all_atasan)) {
                    $q_atasan = implode(',', $all_atasan);
                    $data_atasan = $wpdb->get_results(
                        $wpdb->prepare("
                        SELECT 
                            p.id,
                            p.active_rhk,
                            p.nip_baru,
                            p.nama_pegawai,
                            p.satker_id,
                            p.jabatan,
                            p.tipe_pegawai,
                            p.tipe_pegawai_id,
                            p.active,
                            p.id_atasan,
                            s.nama AS nama_bidang
                        FROM esakip_data_pegawai_simpeg p
                        LEFT JOIN esakip_data_satker_simpeg s
                            ON s.satker_id = p.satker_id
                        WHERE p.id IN (" . $q_atasan . ") 
                          AND p.active = 1
                          AND s.tahun_anggaran = %d
                        ORDER BY p.satker_id, p.tipe_pegawai_id, p.berakhir DESC, p.nama_pegawai
                    ", $_POST['tahun_anggaran']),
                        ARRAY_A
                    );
                    foreach ($data_atasan as $atasan) {
                        $all_atasan[$atasan['id']] = $atasan['nip_baru'] . ' ' . $atasan['nama_pegawai'] . ' | ' . $atasan['jabatan'] . ' ' . $atasan['nama_bidang'];
                    }
                }

                $tbody = '';
                $option_pegawai = '<option value="">-- Pilih Pegawai Atasan--</option>';
                foreach ($data_pegawai_all as $key => $v_pgw) {
                    $checked = '';
                    if ($v_pgw['active_rhk'] == 1) {
                        $checked = 'checked';
                        $ret['aktif']++;
                    } else {
                        $ret['non_aktif']++;
                    }
                    $atasan = 'Definitif';
                    if (
                        !empty($v_pgw['id_atasan'])
                        && !empty($all_atasan[$v_pgw['id_atasan']])
                    ) {
                        $atasan = $all_atasan[$v_pgw['id_atasan']];
                    }
                    $custom_nama_jabatan = '';
                    if (!empty($v_pgw['custom_jabatan'])) {
                        $custom_nama_jabatan = '<hr class="mt-1 mb-1"><span class="text-muted">' . $v_pgw['custom_jabatan'] . '</span>';
                    }
                    $tbody .= "
                    <tr data-id='" . $v_pgw['id'] . "'>
                        <td class='text-center'><input type='checkbox' class='input_rhk' value='" . $v_pgw['id'] . "' " . $checked . "></td>
                        <td class='text-left'>" . $v_pgw['satker_id'] . "</td>
                        <td class='text-left'>" . $v_pgw['nama_bidang'] . "</td>
                        <td class='text-left'>" . $v_pgw['tipe_pegawai'] . "</td>
                        <td class='text-left'>" . $v_pgw['nip_baru'] . "</td>
                        <td class='text-left'>" . $v_pgw['nama_pegawai'] . "</td>
                        <td class='text-left'>" . $v_pgw['jabatan'] . $custom_nama_jabatan . "</td>
                        <td class='text-left'>" . $atasan . "</td>
                        <td class='text-center'>
                            <button class='btn-sm btn-warning' title='Edit Pegawai'><i class='dashicons dashicons-edit' onclick='handleEditPegawai(" . $v_pgw['id'] . ")'></i></button>
                        </td>
                    </tr>";

                    if ($v_pgw['active_rhk'] == 1) {
                        $option_pegawai .= '<option value="' . $v_pgw['id'] . '">' . $v_pgw['nip_baru'] . ' - ' . $v_pgw['nama_pegawai'] . ' | ' . $v_pgw['jabatan'] . ' ' . $v_pgw['nama_bidang'] . '</option>';
                    }
                }
                $ret['data'] = $tbody;
                $ret['option_pegawai'] = $option_pegawai;
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

    public function get_table_pegawai_simpeg_pk()
    {
        global $wpdb;
        $ret = array(
            'status'  => 'success',
            'message' => 'Berhasil get data!',
            'data'    => '<tr><td colspan="6" class="text-center">Tidak ada data ditemukan</td></tr>'
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if (empty($_POST['tahun_anggaran']) || empty($_POST['id_skpd'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Parameter tidak valid!';
                    die(json_encode($ret));
                }

                $tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
                $unit = $this->get_data_skpd_by_id($_POST['id_skpd'], $tahun_anggaran_sakip);
                if (empty($unit)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Unit aktif tidak ditemukan!';
                    die(json_encode($ret));
                }

                $tbody = '';
                $mapping_satker_id = $wpdb->get_var(
                    $wpdb->prepare("
                        SELECT
                            b.satker_id
                        FROM esakip_data_mapping_unit_sipd_simpeg a 
                        LEFT JOIN esakip_data_satker_simpeg b 
                               ON b.satker_id = a.id_satker_simpeg 
                              AND b.tahun_anggaran = a.tahun_anggaran 
                              AND b.active = 1
                        WHERE a.tahun_anggaran = %d 
                          AND a.id_skpd = %d;
                    ", $_POST['tahun_anggaran'], $unit['id_skpd'])
                );

                if (empty($mapping_satker_id)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Mapping unit tidak ditemukan!';
                    die(json_encode($ret));
                }

                $data_pegawai_all = array();
                $satker_id = $mapping_satker_id;
                if(!empty($_POST['nip'])){                    
                    $data_pegawai = $wpdb->get_results(
                        $wpdb->prepare("
                            SELECT 
                                p.id,
                                p.nip_baru,
                                p.nama_pegawai,
                                p.satker_id,
                                p.jabatan,
                                p.id_jabatan,
                                p.tipe_pegawai,
                                p.tipe_pegawai_id,
                                p.active,
                                p.custom_jabatan,
                                s.nama AS nama_bidang
                            FROM esakip_data_pegawai_simpeg p
                            LEFT JOIN esakip_data_satker_simpeg s
                                   ON s.satker_id = p.satker_id
                            WHERE p.satker_id LIKE %s 
                              AND p.nip_baru = %d
                              AND p.active = 1
                              AND p.active_rhk = 1
                              AND s.tahun_anggaran = %d
                            ORDER BY p.satker_id, p.tipe_pegawai_id, p.berakhir DESC, p.nama_pegawai
                        ", $satker_id . '%', $_POST['nip'], $_POST['tahun_anggaran']),
                        ARRAY_A
                    );
                } else {
                    $data_pegawai = $wpdb->get_results(
                        $wpdb->prepare("
                            SELECT 
                                p.id,
                                p.nip_baru,
                                p.nama_pegawai,
                                p.satker_id,
                                p.jabatan,
                                p.id_jabatan,
                                p.tipe_pegawai,
                                p.tipe_pegawai_id,
                                p.active,
                                p.custom_jabatan,
                                s.nama AS nama_bidang
                            FROM esakip_data_pegawai_simpeg p
                            LEFT JOIN esakip_data_satker_simpeg s
                                   ON s.satker_id = p.satker_id
                            WHERE p.satker_id LIKE %s 
                              AND p.active = 1
                              AND p.active_rhk = 1
                              AND s.tahun_anggaran = %d
                            ORDER BY p.satker_id, p.tipe_pegawai_id, p.berakhir DESC, p.nama_pegawai
                        ", $satker_id . '%', $_POST['tahun_anggaran']),
                        ARRAY_A
                    );
                }
                if (!empty($data_pegawai)) {
                    foreach ($data_pegawai as $v_1) {
                        if (
                            strtoupper(trim($v_1['jabatan'])) == 'KEPALA'
                            && $v_1['satker_id'] == $satker_id
                        ) {
                            array_unshift($data_pegawai_all, $v_1);
                        } else {
                            $data_pegawai_all[] = $v_1;
                        }
                    }
                }

                if (empty($data_pegawai_all)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Pegawai tidak ditemukan!';
                    die(json_encode($ret));
                }

                $detail_laporan_pk = $this->functions->generatePage(array(
                    'nama_page'   => 'Halaman Detail Laporan PK ' . $_POST['tahun_anggaran'],
                    'content'     => '[detail_laporan_pk tahun=' . $_POST['tahun_anggaran'] . ']',
                    'show_header' => 1,
                    'post_status' => 'private'
                ));

                $detail_laporan_rhk = $this->functions->generatePage(array(
                    'nama_page'   => 'Halaman Detail Laporan RHK ' . $_POST['tahun_anggaran'],
                    'content'     => '[detail_laporan_rhk tahun=' . $_POST['tahun_anggaran'] . ']',
                    'show_header' => 1,
                    'post_status' => 'private'
                ));

                foreach ($data_pegawai_all as $key => $v_pgw) {
                    //update rhk where if id jabatan(satker_id for pk) is null
                    $cek_null_value = $wpdb->get_var(
                        $wpdb->prepare("
                            SELECT COUNT(*) 
                            FROM esakip_data_rencana_aksi_opd 
                            WHERE active = 1 
                              AND tahun_anggaran = %d 
                              AND id_skpd = %s 
                              AND nip = %s 
                              AND id_jabatan IS NULL
                        ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $v_pgw['nip_baru'])
                    );

                    if ($cek_null_value > 0) {
                        $wpdb->update(
                            'esakip_data_rencana_aksi_opd',
                            array('id_jabatan' => $v_pgw['satker_id']),
                            array(
                                'active'         => 1,
                                'tahun_anggaran' => $_POST['tahun_anggaran'],
                                'id_skpd'        => $_POST['id_skpd'],
                                'nip'            => $v_pgw['nip_baru'],
                                'id_jabatan'     => null
                            ),
                            array('%s'),
                            array('%d', '%s', '%s')
                        );
                    }

                    $count_finalisasi = $wpdb->get_var(
                        $wpdb->prepare("
                            SELECT 
                                COUNT(id)
                            FROM esakip_finalisasi_tahap_laporan_pk 
                            WHERE active = 1 
                              AND tahun_anggaran = %d
                              AND id_skpd = %d
                              AND nip = %d 
                              AND id_satker = %d 
                        ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $v_pgw['nip_baru'], $v_pgw['satker_id'])
                    );

                    if(empty($v_pgw['id_jabatan'])){
                        $count_rhk = $wpdb->get_var(
                            $wpdb->prepare("
                                SELECT 
                                    COUNT(id)
                                FROM esakip_data_rencana_aksi_opd 
                                WHERE active = 1 
                                  AND tahun_anggaran = %d
                                  AND id_skpd = %d
                                  AND nip = %s 
                                  AND id_jabatan = %s 
                            ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $v_pgw['nip_baru'], $v_pgw['satker_id'])
                        );
                    }else {
                        $count_rhk = $wpdb->get_var(
                            $wpdb->prepare("
                                SELECT 
                                    COUNT(id)
                                FROM esakip_data_rencana_aksi_opd 
                                WHERE active = 1 
                                  AND tahun_anggaran = %d
                                  AND id_skpd = %d
                                  AND nip = %s 
                                  AND id_jabatan = %s 
                                  AND id_jabatan_asli = %s 
                            ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $v_pgw['nip_baru'], $v_pgw['satker_id'], $v_pgw['id_jabatan'])
                        );

                    }
                    $custom_nama_jabatan = '';
                    if (!empty($v_pgw['custom_jabatan'])) {
                        $custom_nama_jabatan = '<hr class="mt-1 mb-1"><span class="text-muted">' . $v_pgw['custom_jabatan'] . '</span>';
                    }
                    $tbody .= "<tr>";
                    $tbody .= "<td class='text-left'>" . $v_pgw['satker_id'] . "</td>";
                    $tbody .= "<td class='text-left'>" . $v_pgw['nama_bidang'] . "</td>";
                    $tbody .= "<td class='text-left'>" . $v_pgw['tipe_pegawai'] . "</td>";
                    $tbody .= "<td class='text-left' title='Halaman Detail Perjanjian Kinerja'><a href='" . $detail_laporan_pk['url'] . "&id_skpd=" . $unit['id_skpd'] . "&nip=" . $v_pgw['nip_baru'] . "&satker_id=" . $v_pgw['satker_id'] . "&id_jabatan=" . $v_pgw['id_jabatan'] . "&id_pegawai=" . $v_pgw['id'] . "' target='_blank'>" . $v_pgw['nip_baru'] . "</a></td>";
                    $tbody .= "<td class='text-left'>" . $v_pgw['nama_pegawai'] . "</td>";
                    $tbody .= "<td class='text-left'>" . $v_pgw['jabatan'].$custom_nama_jabatan. "</td>";
                    $tbody .= "<td class='text-center' title='Halaman Detail Rencana Hasil Kerja'><a href='" . $detail_laporan_rhk['url'] . "&id_skpd=" . $unit['id_skpd'] . "&nip=" . $v_pgw['nip_baru'] . "&satker_id=" . $v_pgw['satker_id'] . "' target='_blank'>" . $count_rhk . "</a></td>";
                    $tbody .= "<td class='text-center'>" . $count_finalisasi . "</td>";
                    $tbody .= "</tr>";
                }
                $ret['data'] = $tbody;

                $data_finalisasi = $wpdb->get_results(
                    $wpdb->prepare("
                        SELECT 
                            p.id, 
                            pk.nip, 
                            pk.nama_pegawai, 
                            pk.satuan_kerja, 
                            pk.id_skpd, 
                            pk.nama_skpd, 
                            pk.jabatan_pegawai,
                            p.satker_id,
                            p.tipe_pegawai
                        FROM esakip_finalisasi_tahap_laporan_pk pk
                        LEFT JOIN esakip_data_pegawai_simpeg p 
                               ON pk.nip = p.nip_baru 
                        WHERE pk.tahun_anggaran = %d
                          AND pk.id_skpd = %d
                          AND p.satker_id LIKE %s
                          AND pk.active = 1
                          AND p.active = 0
                    ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $mapping_satker_id . '%'),
                    ARRAY_A
                );
                $ret['sql'] = $wpdb->last_query;

                $tbody_nonaktif = '';
                if (!empty($data_finalisasi)) {
                    foreach ($data_finalisasi as $v) {
                        $count_finalisasi_2 = $wpdb->get_var(
                            $wpdb->prepare("
                                SELECT 
                                    COUNT(id)
                                FROM esakip_finalisasi_tahap_laporan_pk 
                                WHERE active = 1 
                                  AND tahun_anggaran = %d
                                  AND id_skpd = %d
                                  AND nip = %s
                                  AND id_satker = %s
                            ", $_POST['tahun_anggaran'], $_POST['id_skpd'], $v['nip'], $v['satker_id'])
                        );

                        $tbody_nonaktif .= "<tr>";
                        $tbody_nonaktif .= "<td class='text-left'>" . $v['satker_id'] . "</td>";
                        $tbody_nonaktif .= "<td class='text-left'>" . $v['nama_skpd'] . "</td>";
                        $tbody_nonaktif .= "<td class='text-left'>" . $v['satuan_kerja'] . "</td>";
                        $tbody_nonaktif .= "<td class='text-center' title='Halaman Detail Perjanjian Kinerja'><a href='" . $detail_laporan_pk['url'] . "&id_skpd=" . $unit['id_skpd'] . "&nip=" . $v['nip'] . "&satker_id=" . $v['satker_id'] . "&id_pegawai=" . $v['id'] . "&inactive=1' target='_blank'>" . $v['nip'] . "</a></td>";
                        $tbody_nonaktif .= "<td class='text-left'>" . $v['nama_pegawai'] . "</td>";
                        $tbody_nonaktif .= "<td class='text-left'>" . $v['jabatan_pegawai'] . "</td>";
                        $tbody_nonaktif .= "<td class='text-center'>" . $count_finalisasi_2 . "</td>";
                        $tbody_nonaktif .= "</tr>";
                    }

                    $ret['data_non_aktif'] = $tbody_nonaktif;
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

    public function get_table_skpd_laporan_pk_setting()
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

                $user_id = um_user('ID');
                $user_meta = get_userdata($user_id);

                $tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

                if ($ret['status'] == 'success') {
                    $unit = $wpdb->get_results(
                        $wpdb->prepare("
                        SELECT
                            nama_skpd, 
                            id_skpd, 
                            kode_skpd, 
                            nipkepala,
                            namakepala 
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
                            $data_detail = $wpdb->get_row(
                                $wpdb->prepare(
                                    "SELECT
                                        *
                                    FROM 
                                        esakip_detail_data_unit
                                    WHERE
                                        id_skpd = %d
                                ",
                                    $vv['id_skpd']
                                ),
                                ARRAY_A
                            );

                            $alamat_kantor = !empty($data_detail['alamat_kantor']) ? $data_detail['alamat_kantor'] : '';
                            $nip_kepala = !empty($vv['nipkepala']) ? $vv['nipkepala'] : '';
                            $nama_kepala = !empty($vv['namakepala']) ? $vv['namakepala'] : '';

                            $tbody .= "<tr>";
                            $tbody .= "<td>" . $vv['kode_skpd'] . " " . $vv['nama_skpd'] . "</td>";
                            $tbody .= "<td class='text-center'>" . $nip_kepala . "</td>";
                            $tbody .= "<td class='text-left'>" . $nama_kepala . "</td>";
                            $tbody .= "<td class='text-left'>" . $alamat_kantor . "</td>";

                            $btn = '';
                            if (in_array('administrator', $user_meta->roles)) {
                                $btn .= '
                                    <button class="btn btn-sm btn-warning" onclick="edit_setting_laporan_pk(\'' . $vv['id_skpd'] . '\'); return false;" title="Edit Setting laporan PK">
                                        <span class="dashicons dashicons-edit"></span>
                                    </button>
                                ';
                            }

                            $id_by_nip = $wpdb->get_row($wpdb->prepare("
                                SELECT
                                    user_id 
                                FROM {$wpdb->prefix}usermeta 
                                WHERE meta_key = 'nip'
                                    AND meta_value = %s 
                                LIMIT 1
                            ", $nip_kepala),ARRAY_A);

                            $id_kepala = !empty($id_by_nip['user_id']) ? $id_by_nip['user_id'] : '';

                            if (!empty($id_kepala) && in_array('admin_panrb', $user_meta->roles) || in_array('administrator', $user_meta->roles)) {
                                $btn .= '
                                    <button class="btn btn-sm btn-secondary" onclick="login_to_profile(\'' . $id_kepala . '\', \'' . $tahun_anggaran . '\', \'' . $nip_kepala . '\'); return false;" title="Login ke User"><span class="dashicons dashicons-controls-forward"></span>
                                    </button>
                                ';
                            }

                            $tbody .= "<td class='text-center'>" . $btn . "</td>";
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

    public function tambah_logo_pemda_laporan_pk()
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil tambah data!',
            'nama_logo' => ''
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                $id_dokumen = null;

                if (empty($_FILES['fileUpload']) && empty($id_dokumen)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'File Dokumen kosong!';
                }

                $upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
                if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
                    $nama_file = 'logo_pemda_' . date('Y-m-d-H-i-s') . '_' . $_FILES['fileUpload']['name'];
                    $upload = $this->functions->uploadFile(
                        $_POST['api_key'],
                        $upload_dir,
                        $_FILES['fileUpload'],
                        array('png', 'jpeg', 'jpg'),
                        1048576 * 5,
                        $nama_file
                    );
                    if ($upload['status'] == false) {
                        $ret = array(
                            'status' => 'error',
                            'message' => $upload['message']
                        );
                    }
                }

                if ($ret['status'] == 'success') {
                    $path_logo_pemda_laporan_pk = get_option('_logo_pemda_laporan_pk');
                    if (empty($path_logo_pemda_laporan_pk)) {
                        update_option('_logo_pemda_laporan_pk', $upload['filename']);
                    } else {
                        if (!empty($_FILES['fileUpload'])) {
                            $opsi['dokumen'] = $upload['filename'];

                            if (is_file($upload_dir . $path_logo_pemda_laporan_pk)) {
                                unlink($upload_dir . $path_logo_pemda_laporan_pk);
                            }
                        }
                        update_option('_logo_pemda_laporan_pk', $upload['filename']);
                    }

                    $ret['nama_logo'] = get_option("_logo_pemda_laporan_pk");
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

    public function submit_edit_laporan_pk_setting()
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil Edit laporan PK Setting!'
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if (!empty($_POST['id_skpd'])) {
                    $id_skpd = $_POST['id_skpd'];
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Id Perangkat Daerah kosong!';
                }
                if (!empty($_POST['alamat'])) {
                    $alamat = $_POST['alamat'];
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Alamat kosong!';
                }

                if ($ret['status'] == 'success') {

                    $tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

                    $unit = $wpdb->get_row(
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
                            AND id_skpd=%d
                        ORDER BY kode_skpd ASC
                        ", $tahun_anggaran_sakip, $id_skpd),
                        ARRAY_A
                    );

                    $cek_data = $wpdb->get_row($wpdb->prepare(
                        "SELECT
                            id
                        FROM 
                            esakip_detail_data_unit
                        WHERE
                            id_skpd=%d
                            AND active=1
                        ",
                        $id_skpd
                    ), ARRAY_A);

                    $opsi = array(
                        'id_skpd' => $unit['id_skpd'],
                        'nama_skpd' => $unit['nama_skpd'],
                        'alamat_kantor' => $alamat,
                        'active' => 1,
                        'updated_at' => current_time('mysql')
                    );

                    if (empty($cek_data)) {
                        $opsi['created_at'] = current_time('mysql');
                        $send_data = $wpdb->insert(
                            'esakip_detail_data_unit',
                            $opsi,
                            array('%d', '%s', '%s', '%d', '%s', '%s')
                        );
                    } else {
                        $send_data = $wpdb->update(
                            'esakip_detail_data_unit',
                            $opsi,
                            array('id_skpd' => $id_skpd, 'active' => 1),
                            array('%d', '%s', '%s', '%d', '%s'),
                            array('%d', '%d')
                        );
                    }

                    if ($wpdb->rows_affected == 0) {
                        $ret = array(
                            'status' => 'error',
                            'message' => 'Gagal memperbarui data ke database!'
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

    public function get_detail_setting_laporan_pk_by_id()
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil get data!',
            'data'  => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if (!empty($_POST['id_skpd'])) {

                    $tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

                    $data = $wpdb->get_row(
                        $wpdb->prepare(
                            "SELECT 
                                a.id,
                                a.id_skpd,
                                a.alamat_kantor
							FROM 
                                esakip_detail_data_unit a
                            JOIN 
                                esakip_data_unit b
                            ON b.id_skpd=a.id_skpd 
                                AND b.tahun_anggaran=%d
							WHERE 
                                a.id_skpd = %d
						",
                            $tahun_anggaran_sakip,
                            $_POST['id_skpd']
                        ),
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
    public function get_alamat_kantor()
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil get data!',
            'data'  => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if (!empty($_POST['id_skpd'])) {

                    $tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

                    $data = $wpdb->get_row(
                        $wpdb->prepare(
                            "SELECT 
                                a.id,
                                a.id_skpd,
                                a.alamat_kantor
                            FROM 
                                esakip_detail_data_unit a
                            JOIN 
                                esakip_data_unit b
                            ON b.id_skpd=a.id_skpd 
                                AND b.tahun_anggaran=%d
                            WHERE 
                                a.id_skpd = %d
                        ",
                            $tahun_anggaran_sakip,
                            $_POST['id_skpd']
                        ),
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

    public function submit_alamat_kantor()
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil Edit Alamat!'
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if (!empty($_POST['id_skpd'])) {
                    $id_skpd = $_POST['id_skpd'];
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Id Perangkat Daerah kosong!';
                }
                if (!empty($_POST['alamat'])) {
                    $alamat = $_POST['alamat'];
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Alamat kosong!';
                }

                if ($ret['status'] == 'success') {

                    $tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

                    $unit = $wpdb->get_row(
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
                            AND id_skpd=%d
                        ORDER BY kode_skpd ASC
                        ", $tahun_anggaran_sakip, $id_skpd),
                        ARRAY_A
                    );

                    $cek_data = $wpdb->get_row($wpdb->prepare(
                        "SELECT
                            id
                        FROM 
                            esakip_detail_data_unit
                        WHERE
                            id_skpd=%d
                            AND active=1
                        ",
                        $id_skpd
                    ), ARRAY_A);

                    $opsi = array(
                        'id_skpd' => $unit['id_skpd'],
                        'nama_skpd' => $unit['nama_skpd'],
                        'alamat_kantor' => $alamat,
                        'active' => 1,
                        'updated_at' => current_time('mysql')
                    );

                    if (empty($cek_data)) {
                        $opsi['created_at'] = current_time('mysql');
                        $send_data = $wpdb->insert(
                            'esakip_detail_data_unit',
                            $opsi,
                            array('%d', '%s', '%s', '%d', '%s', '%s')
                        );
                    } else {
                        $send_data = $wpdb->update(
                            'esakip_detail_data_unit',
                            $opsi,
                            array('id_skpd' => $id_skpd, 'active' => 1),
                            array('%d', '%s', '%s', '%d', '%s'),
                            array('%d', '%d')
                        );
                    }

                    if ($wpdb->rows_affected == 0) {
                        $ret = array(
                            'status' => 'error',
                            'message' => 'Gagal memperbarui data ke database!'
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
    public function get_table_skpd_dokumen_kuesioner()
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

                if ($ret['status'] != 'error') {
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
                        $counter = 1;

                        foreach ($unit as $kk => $vv) {
                            $detail_dokumen_kuesioner = $this->functions->generatePage(array(
                                'nama_page' => 'Halaman Detail Dokumen Kuesioner ' . $tahun_anggaran,
                                'content' => '[dokumen_detail_kuesioner tahun=' . $tahun_anggaran . ']',
                                'show_header' => 1,
                                'post_status' => 'private'
                            ));

                            $tbody .= "<tr>";
                            $tbody .= "<td class='text-center'>" . $counter++ . "</td>";
                            $tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

                            $jumlah_dokumen = $wpdb->get_var(
                                $wpdb->prepare(
                                    "
									SELECT 
										COUNT(id)
									FROM esakip_dokumen_kuesioner 
									WHERE id_skpd = %d
									AND tahun_anggaran = %d
									AND active = 1
									",
                                    $vv['id_skpd'],
                                    $tahun_anggaran
                                )
                            );

                            $btn = '<div class="btn-action-group">';
                            $btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_dokumen_kuesioner['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
                            $btn .= '</div>';

                            $tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
                            $tbody .= "<td>" . $btn . "</td>";

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

    public function get_table_dokumen_kuesioner()
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil get data!',
            'data' => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if (!empty($_POST['id_skpd'])) {
                    $id_skpd = $_POST['id_skpd'];
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Id Perangkat Daerah kosong!';
                }
                if (!empty($_POST['tahun_anggaran'])) {
                    $tahun_anggaran = $_POST['tahun_anggaran'];
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Tahun Anggaran kosong!';
                }
                $dokumen_kuesioner = $wpdb->get_results(
                    $wpdb->prepare("
                    SELECT * 
                    FROM esakip_dokumen_kuesioner
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
                    ARRAY_A
                );

                if (!empty($dokumen_kuesioner)) {
                    $counter = 1;
                    $tbody = '';

                    foreach ($dokumen_kuesioner as $kk => $vv) {
                        $tbody .= "<tr>";
                        $tbody .= "<td class='text-center'>" . $counter++ . "</td>";
                        $tbody .= "<td>" . $vv['opd'] . "</td>";
                        $tbody .= "<td>" . $vv['dokumen'] . "</td>";
                        $tbody .= "<td>" . $vv['keterangan'] . "</td>";
                        $tbody .= "<td>" . $vv['created_at'] . "</td>";

                        $btn = '<div class="btn-action-group">';
                        $btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
                        $btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_kuesioner(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
                        $btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_kuesioner(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
                        $btn .= '</div>';

                        $tbody .= "<td class='text-center'>" . $btn . "</td>";
                        $tbody .= "</tr>";
                    }

                    $ret['data'] = $tbody;
                } else {
                    $ret['data'] = "<tr><td colspan='6' class='text-center'>Tidak ada data tersedia</td></tr>";
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
    public function tambah_dokumen_kuesioner()
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil tambah data!',
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                $id_dokumen = null;

                if (!empty($_POST['id_dokumen'])) {
                    $id_dokumen = $_POST['id_dokumen'];
                    $ret['message'] = 'Berhasil edit data!';
                }
                if (!empty($_POST['skpd'])) {
                    $skpd = $_POST['skpd'];
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Perangkat Daerah kosong!';
                }
                if (!empty($_POST['idSkpd'])) {
                    $idSkpd = $_POST['idSkpd'];
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Id Perangkat Daerah kosong!';
                }
                if (!empty($_POST['keterangan'])) {
                    $keterangan = $_POST['keterangan'];
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Keterangan kosong!';
                }
                if (!empty($_POST['tahunAnggaran'])) {
                    $tahunAnggaran = $_POST['tahunAnggaran'];
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Tahun Anggaran kosong!';
                }
                if (empty($_FILES['fileUpload']) && empty($id_dokumen)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'File Dokumen kosong!';
                }
                if (empty($_POST['namaDokumen']) && empty($id_dokumen)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'File Dokumen kosong!';
                }
                if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
                }

                $upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
                if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
                    $maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
                    $upload = $this->functions->uploadFile(
                        $_POST['api_key'],
                        $upload_dir,
                        $_FILES['fileUpload'],
                        array('pdf'),
                        1048576 * $maksimal_upload,
                        $_POST['namaDokumen']
                    );
                    if ($upload['status'] == false) {
                        $ret = array(
                            'status' => 'error',
                            'message' => $upload['message']
                        );
                    }
                } else if ($ret['status'] != 'error' && !empty($_POST['namaDokumen'])) {
                    $dokumen_lama = $wpdb->get_var($wpdb->prepare("
						SELECT
							dokumen
						FROM esakip_dokumen_kuesioner
						WHERE id=%d
					", $id_dokumen));
                    if ($dokumen_lama != $_POST['namaDokumen']) {
                        $ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
                        if ($ret_rename['status'] != 'error') {
                            $wpdb->update(
                                'esakip_dokumen_kuesioner',
                                array('dokumen' => $_POST['namaDokumen']),
                                array('id' => $id_dokumen),
                            );
                        } else {
                            $ret = $ret_rename;
                        }
                    }
                }

                if ($ret['status'] == 'success') {
                    if (empty($id_dokumen)) {
                        $wpdb->insert(
                            'esakip_dokumen_kuesioner',
                            array(
                                'opd' => $skpd,
                                'id_skpd' => $idSkpd,
                                'dokumen' => $upload['filename'],
                                'keterangan' => $keterangan,
                                'tahun_anggaran' => $tahunAnggaran,
                                'created_at' => current_time('mysql'),
                                'tanggal_upload' => current_time('mysql')
                            ),
                            array('%s', '%s', '%s', '%s', '%d')
                        );

                        if (!$wpdb->insert_id) {
                            $ret = array(
                                'status' => 'error',
                                'message' => 'Gagal menyimpan data ke database!'
                            );
                        }
                    } else {
                        $opsi = array(
                            'keterangan' => $keterangan,
                            'created_at' => current_time('mysql'),
                            'tanggal_upload' => current_time('mysql')
                        );
                        if (!empty($_FILES['fileUpload'])) {
                            $opsi['dokumen'] = $upload['filename'];
                            $dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_dokumen_kuesioner
								WHERE id=%d
							", $id_dokumen));
                            if (is_file($upload_dir . $dokumen_lama)) {
                                unlink($upload_dir . $dokumen_lama);
                            }
                        }
                        $wpdb->update(
                            'esakip_dokumen_kuesioner',
                            $opsi,
                            array('id' => $id_dokumen),
                            array('%s', '%s'),
                            array('%d')
                        );

                        if ($wpdb->rows_affected == 0) {
                            $ret = array(
                                'status' => 'error',
                                'message' => 'Gagal memperbarui data ke database!'
                            );
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
    public function hapus_dokumen_kuesioner()
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
                    $get_data = $wpdb->get_var(
                        $wpdb->prepare("
                            SELECT
                                *
                            FROM esakip_dokumen_kuesioner
                            WHERE id= %d
                        ", $_POST['id'])
                    );

                    if ($get_data) {
                        $ret['data'] = $wpdb->update(
                            'esakip_dokumen_kuesioner',
                            array('active' => 0),
                            array('id' => $_POST['id'])
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
                    'massage'  => 'Api Key tidak sesuai!'
                );
            }
        } else {
            $ret = array(
                'status' => 'error',
                'massage' => 'Format tidak sesuai!'
            );
        }
        die(json_encode($ret));
    }

    public function get_detail_kuesioner_by_id()
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'berhasil get data!',
            'data' => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
                if (!empty($_POST['id'])) {
                    $data = $wpdb->get_row(
                        $wpdb->prepare("
                            SELECT *
                            FROM esakip_dokumen_kuesioner
                            WHERE id = %d
                            ", $_POST['id']),
                        ARRAY_A
                    );
                    $ret['data'] = $data;
                } else {
                    $ret = array(
                        'status' => 'error',
                        'massage' => 'Id Kosong!'
                    );
                }
            } else {
                $ret = array(
                    'status' => 'error',
                    'masage'  => 'Api Key tidak sesuai!'
                );
            }
        } else {
            $ret = array(
                'status' => 'error',
                'massage'  => 'Format tidak sesuai!'
            );
        }
        die(json_encode($ret));
    }
}
