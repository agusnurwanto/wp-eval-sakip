<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/agusnurwanto/
 * @since      1.0.0
 *
 * @package    Wp_Eval_Sakip
 * @subpackage Wp_Eval_Sakip/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Eval_Sakip
 * @subpackage Wp_Eval_Sakip/public
 * @author     Agus Nurwanto <agusnurwantomuslim@gmail.com>
 */
class Wp_Eval_Sakip_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $functions;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version, $functions)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->functions = $functions;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Eval_Sakip_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Eval_Sakip_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style('dashicons');
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-eval-sakip-public.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . 'bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . 'datatables', plugin_dir_url(__FILE__) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Eval_Sakip_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Eval_Sakip_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-eval-sakip-public.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . 'bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . 'datatables', plugin_dir_url(__FILE__) . 'js/jquery.dataTables.min.js', array('jquery'), $this->version, false);
		wp_localize_script($this->plugin_name, 'esakip', array(
			'api_key' => get_option(ESAKIP_APIKEY),
			'url' => admin_url('admin-ajax.php')
		));
	}

	public function desain_lke_sakip()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-desain-lke-sakip.php';
	}

	public function jadwal_evaluasi()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-jadwal-evaluasi.php';
	}

	public function renstra()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-renstra.php';
	}

	public function renja_rkt($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-renja-rkt.php';
	}

	public function perjanjian_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-perjanjian-kinerja.php';
	}

	public function rencana_aksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-rencana-aksi.php';
	}

	public function iku($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-iku.php';
	}

	public function skp($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-skp.php';
	}

	public function pengukuran_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-pengukuran-kinerja.php';
	}

	public function pengukuran_rencana_aksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-pengukuran-rencana-aksi.php';
	}

	public function laporan_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-laporan-kinerja.php';
	}

	public function evaluasi_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-evaluasi-internal.php';
	}

	public function dokumen_lainnya($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-dokumen_lainnya.php';
	}

	public function rpjmd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-rpjmd.php';
	}

	public function rkpd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-rkpd.php';
	}

	public function lkjip_lppd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-lkjip-lppd.php';
	}

	public function dokumen_pemda_lainnya($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-dokumen-pemda-lainnya.php';
	}

	public function halaman_mapping_skpd()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-mapping-skpd.php';
	}

	public function jadwal_rpjmd()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-jadwal-rpjmd.php';
	}


    public function mapping_skpd()
    {
        global $wpdb;
        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil memproses data!'
        );
        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
                if (empty($_POST['nama_skpd_sakip'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Nama SKPD SAKIP tidak boleh kosong!';
                } else {
                    $nama_skpd_sakip = $_POST['nama_skpd_sakip'];
                    $skpd = $wpdb->get_row($wpdb->prepare('
                        SELECT
                        	*
                        FROM esakip_data_unit
                        WHERE id_skpd=%s
                        	AND active=1
                        order by tahun_anggaran DESC
                    ', $_POST['id_skpd']), ARRAY_A);
                    if (empty($skpd)) {
	                    $ret['status'] = 'error';
	                    $ret['message'] = 'ID SKPD tidak ditemukans!';
                    } else {
                    	update_option('_nama_skpd_sakip_'.$skpd['id_skpd'], $nama_skpd_sakip);
                    	$data = array(
                    		'opd' => $skpd['nama_skpd'],
                    		'id_skpd' => $skpd['id_skpd']
                    	);
                        $wpdb->update('esakip_renja_rkt', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                        $wpdb->update('esakip_dokumen_lainnya', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                        $wpdb->update('esakip_evaluasi_internal', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                        $wpdb->update('esakip_iku', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                        $wpdb->update('esakip_laporan_kinerja', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                        $wpdb->update('esakip_lhe_opd', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                        $wpdb->update('esakip_pengukuran_kinerja', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                        $wpdb->update('esakip_pengukuran_rencana_aksi', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                        $wpdb->update('esakip_perjanjian_kinerja', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                        $wpdb->update('esakip_rencana_aksi', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                        $wpdb->update('esakip_renstra', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                        $wpdb->update('esakip_skp', $data, array(
                            'opd' => $nama_skpd_sakip
                        ));
                    }
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
	
	public function dokumen_detail_renja_rkt($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-renja-rkt-per-skpd.php';
	}

	public function dokumen_detail_skp($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-skp-per-skpd.php';
	}

	public function dokumen_detail_rencana_aksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-rencana-aksi-per-skpd.php';
	}

	public function dokumen_detail_perjanjian_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-perjanjian-kinerja-per-skpd.php';
	}

	public function dokumen_detail_pengukuran_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-pengukuran-kinerja-per-skpd.php';
	}

	public function dokumen_detail_pengukuran_rencana_aksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-pengukuran-rencana-aksi-per-skpd.php';
	}

	public function dokumen_detail_evaluasi_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-evaluasi-internal-per-skpd.php';
	}

	public function dokumen_detail_iku($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-iku-per-skpd.php';
	}

	public function dokumen_detail_laporan_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-laporan-kinerja-per-skpd.php';
	}

	public function dokumen_detail_dokumen_lain($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-dokumen-lain-per-skpd.php';
	}

	public function get_detail_renja_rkt_by_id()
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
							FROM esakip_renja_rkt
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

	public function get_detail_rencana_aksi_by_id()
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
							FROM esakip_rencana_aksi
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

	public function get_detail_dokumen_pemda_lain_by_id()
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
							FROM esakip_other_file
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

	public function get_detail_renstra_by_id()
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
							FROM esakip_renstra
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

	public function get_detail_lkjip_lppd_by_id()
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
							FROM esakip_lkjip_lppd
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

	public function get_detail_rpjmd_by_id()
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
							FROM esakip_rpjmd
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

	public function get_detail_rkpd_by_id()
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
							FROM esakip_rkpd
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

	public function get_detail_evaluasi_internal_by_id()
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
							FROM esakip_evaluasi_internal
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

	public function get_detail_iku_by_id()
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
							FROM esakip_iku
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

	public function get_detail_laporan_kinerja_by_id()
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
							FROM esakip_laporan_kinerja
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

	public function get_detail_dokumen_lainnya_by_id()
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
							FROM esakip_dokumen_lainnya
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

	public function get_detail_perjanjian_kinerja_by_id()
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
							FROM esakip_perjanjian_kinerja
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

	public function get_detail_skp_by_id()
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
							FROM esakip_skp
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

	public function get_detail_pengukuran_kinerja_by_id()
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
							FROM esakip_pengukuran_kinerja
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

	public function get_detail_pengukuran_rencana_aksi_by_id()
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
							FROM esakip_pengukuran_rencana_aksi
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

	public function submit_tahun_renja_rkt()
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
				if (!empty($_POST['tahunAnggaran'])) {
					$tahun_anggaran = $_POST['tahunAnggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				if (!empty($id) && !empty($tahun_anggaran)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_renja_rkt 
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_renja_rkt',
							array(
								'tahun_anggaran' => $tahun_anggaran,
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

	public function submit_tahun_rencana_aksi()
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
				if (!empty($_POST['tahunAnggaran'])) {
					$tahun_anggaran = $_POST['tahunAnggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				if (!empty($id) && !empty($tahun_anggaran)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_rencana_aksi
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_rencana_aksi',
							array(
								'tahun_anggaran' => $tahun_anggaran,
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

	public function submit_tahun_laporan_kinerja()
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
				if (!empty($_POST['tahunAnggaran'])) {
					$tahun_anggaran = $_POST['tahunAnggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				if (!empty($id) && !empty($tahun_anggaran)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_laporan_kinerja
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_laporan_kinerja',
							array(
								'tahun_anggaran' => $tahun_anggaran,
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

	public function submit_tahun_evaluasi_internal()
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
				if (!empty($_POST['tahunAnggaran'])) {
					$tahun_anggaran = $_POST['tahunAnggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				if (!empty($id) && !empty($tahun_anggaran)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_evaluasi_internal
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_evaluasi_internal',
							array(
								'tahun_anggaran' => $tahun_anggaran,
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

	public function submit_tahun_iku()
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
				if (!empty($_POST['tahunAnggaran'])) {
					$tahun_anggaran = $_POST['tahunAnggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				if (!empty($id) && !empty($tahun_anggaran)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_iku
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_iku',
							array(
								'tahun_anggaran' => $tahun_anggaran,
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

	public function submit_tahun_dokumen_lainnya()
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
				if (!empty($_POST['tahunAnggaran'])) {
					$tahun_anggaran = $_POST['tahunAnggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				if (!empty($id) && !empty($tahun_anggaran)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_dokumen_lainnya
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_dokumen_lainnya',
							array(
								'tahun_anggaran' => $tahun_anggaran,
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

	public function submit_tahun_perjanjian_kinerja()
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
				if (!empty($_POST['tahunAnggaran'])) {
					$tahun_anggaran = $_POST['tahunAnggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				if (!empty($id) && !empty($tahun_anggaran)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_perjanjian_kinerja 
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_perjanjian_kinerja',
							array(
								'tahun_anggaran' => $tahun_anggaran,
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

	public function submit_tahun_pengukuran_kinerja()
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
				if (!empty($_POST['tahunAnggaran'])) {
					$tahun_anggaran = $_POST['tahunAnggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				if (!empty($id) && !empty($tahun_anggaran)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pengukuran_kinerja 
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_pengukuran_kinerja',
							array(
								'tahun_anggaran' => $tahun_anggaran,
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

	public function submit_tahun_pengukuran_rencana_aksi()
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
				if (!empty($_POST['tahunAnggaran'])) {
					$tahun_anggaran = $_POST['tahunAnggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				if (!empty($id) && !empty($tahun_anggaran)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pengukuran_rencana_aksi 
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_pengukuran_rencana_aksi',
							array(
								'tahun_anggaran' => $tahun_anggaran,
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

	public function submit_tahun_skp()
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
				if (!empty($_POST['tahunAnggaran'])) {
					$tahun_anggaran = $_POST['tahunAnggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				if (!empty($id) && !empty($tahun_anggaran)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_skp 
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_skp',
							array(
								'tahun_anggaran' => $tahun_anggaran,
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

	public function tambah_dokumen_renja()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_renja_rkt',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_renja_rkt
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_renja_rkt',
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

	public function tambah_dokumen_perjanjian_kinerja()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_perjanjian_kinerja',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_perjanjian_kinerja
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_perjanjian_kinerja',
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

	public function tambah_dokumen_evaluasi_internal()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_evaluasi_internal',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_evaluasi_internal
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_evaluasi_internal',
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

	public function tambah_dokumen_laporan_kinerja()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_laporan_kinerja',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_laporan_kinerja
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_laporan_kinerja',
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

	public function tambah_dokumen_iku()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_iku',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_iku
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_iku',
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

	public function tambah_dokumen_dokumen_lainnya()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_dokumen_lainnya',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_dokumen_lainnya
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_dokumen_lainnya',
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

	public function tambah_dokumen_rencana_aksi()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_rencana_aksi',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_rencana_aksi
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_rencana_aksi',
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

	public function tambah_dokumen_pengukuran_kinerja()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_pengukuran_kinerja',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_pengukuran_kinerja
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_pengukuran_kinerja',
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

	public function tambah_dokumen_pengukuran_rencana_aksi()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_pengukuran_rencana_aksi',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_pengukuran_rencana_aksi
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_pengukuran_rencana_aksi',
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

	public function tambah_dokumen_dokumen_pemda_lain()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_other_file',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_other_file
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_other_file',
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

	public function tambah_dokumen_rkpd()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_rkpd',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_rkpd
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_rkpd',
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

	public function tambah_dokumen_rpjmd()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_rpjmd',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_rpjmd
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_rpjmd',
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

	public function tambah_dokumen_lkjip_lppd()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_lkjip_lppd',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_lkjip_lppd
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_lkjip_lppd',
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

	public function tambah_dokumen_renstra()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_renstra',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_renstra
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_renstra',
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

	public function tambah_dokumen_skp()
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
					$ret['message'] = 'Id SKPD kosong!';
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
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * 10
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					if (empty($id_dokumen)) {
						$wpdb->insert(
							'esakip_skp',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
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
							'keterangan' => $keterangan
						);
						if (!empty($_FILES['fileUpload'])) {
							$opsi['dokumen'] = $upload['filename'];
							$dokumen_lama = $wpdb->get_var($wpdb->prepare("
								SELECT
									dokumen
								FROM esakip_skp
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_skp',
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

	public function get_table_renja()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$renjas = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_renja_rkt 
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($renjas)) {
					$counter = 1;
					$tbody = '';

					foreach ($renjas as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_renja(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_renja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_rencana_aksi()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$rencana_aksis = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_rencana_aksi
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($rencana_aksis)) {
					$counter = 1;
					$tbody = '';

					foreach ($rencana_aksis as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_rencana_aksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_rencana_aksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_perjanjian_kinerja()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$perjanjian_kinerjas = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_perjanjian_kinerja
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($perjanjian_kinerjas)) {
					$counter = 1;
					$tbody = '';

					foreach ($perjanjian_kinerjas as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_perjanjian_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_perjanjian_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_laporan_kinerja()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$laporan_kinerjas = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_laporan_kinerja
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($laporan_kinerjas)) {
					$counter = 1;
					$tbody = '';

					foreach ($laporan_kinerjas as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_laporan_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_laporan_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_iku()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$ikus = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_iku
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($ikus)) {
					$counter = 1;
					$tbody = '';

					foreach ($ikus as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_iku(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_iku(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_dokumen_lain()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$dokumen_lains = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_dokumen_lainnya
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($dokumen_lains)) {
					$counter = 1;
					$tbody = '';

					foreach ($dokumen_lains as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_dokumen_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_dokumen_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_evaluasi_internal()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$evaluasi_internals = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_evaluasi_internal
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($evaluasi_internals)) {
					$counter = 1;
					$tbody = '';

					foreach ($evaluasi_internals as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_evaluasi_internal(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_evaluasi_internal(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
	public function get_table_pengukuran_kinerja()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$pengukuran_kinerjas = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_pengukuran_kinerja
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($pengukuran_kinerjas)) {
					$counter = 1;
					$tbody = '';

					foreach ($pengukuran_kinerjas as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_pengukuran_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_pengukuran_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_pengukuran_rencana_aksi()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$pengukuran_rencana_aksis = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_pengukuran_rencana_aksi
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($pengukuran_rencana_aksis)) {
					$counter = 1;
					$tbody = '';

					foreach ($pengukuran_rencana_aksis as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_pengukuran_rencana_aksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_pengukuran_rencana_aksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_lkjip_lppd()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$lkjip_lppds = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_lkjip_lppd
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($lkjip_lppds)) {
					$counter = 1;
					$tbody = '';

					foreach ($lkjip_lppds as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_lkjip_lppd(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_lkjip_lppd(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_dokumen_pemda_lain()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$dokumen_pemda_lains = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_other_file
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($dokumen_pemda_lains)) {
					$counter = 1;
					$tbody = '';

					foreach ($dokumen_pemda_lains as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_dokumen_pemda_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_dokumen_pemda_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_rpjmd()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$rpjmds = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_rpjmd
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($rpjmds)) {
					$counter = 1;
					$tbody = '';

					foreach ($rpjmds as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_rpjmd(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_rpjmd(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_rkpd()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$rkpds = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_rkpd
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($rkpds)) {
					$counter = 1;
					$tbody = '';

					foreach ($rkpds as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_rkpd(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_rkpd(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_renstra()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$renstras = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_renstra
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($renstras)) {
					$counter = 1;
					$tbody = '';

					foreach ($renstras as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_renstra(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_renstra(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_skp()
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
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}
				$skp = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_skp 
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($skp)) {
					$counter = 1;
					$tbody = '';

					foreach ($skp as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_skp(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_skp(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function hapus_dokumen_renja()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_renja_rkt
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_renja_rkt',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_perjanjian_kinerja()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_perjanjian_kinerja
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_perjanjian_kinerja',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_rencana_aksi()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_rencana_aksi
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_rencana_aksi',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_iku()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_iku
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_iku',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_skp()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_skp
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_skp',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_pengukuran_kinerja()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_pengukuran_kinerja
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_pengukuran_kinerja',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_pengukuran_rencana_aksi()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_pengukuran_rencana_aksi
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_pengukuran_rencana_aksi',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_laporan_kinerja()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_laporan_kinerja
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_laporan_kinerja',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_evaluasi_internal()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_evaluasi_internal
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_evaluasi_internal',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_dokumen_lainnya()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_dokumen_lainnya
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_dokumen_lainnya',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_renstra()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_renstra
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_renstra',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_rpjmd()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_rpjmd
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_rpjmd',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_rkpd()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_rkpd
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_rkpd',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_lkjip_lppd()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_lkjip_lppd
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_lkjip_lppd',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

	public function hapus_dokumen_pemda_lain()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_other_file
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_other_file',
						array('active' => 0),
						array('id' => $_POST['id'])
					);

					if ($wpdb->rows_affected > 0) {
						if (is_file($upload_dir . $dokumen_lama)) {
							unlink($upload_dir . $dokumen_lama);
						}
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

    public function get_data_jadwal_by_id()
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

	/** Ambil data penjadwalan */
	public function get_data_penjadwalan()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
					$params = $columns = $totalRecords = $data = array();
					$params = $_REQUEST;
					$columns = array(
						0 => 'nama_jadwal',
						1 => 'status',
						2 => 'started_at',
						3 => 'end_at',
						4 => 'tahun_anggaran',
						5 => 'id',
					);
					$where = $sqlTot = $sqlRec = "";
					$where = " WHERE tipe IS NULL ";

					// check search value exist
					if (!empty($params['search']['value'])) {
						$where .= " AND ( nama_jadwal LIKE " . $wpdb->prepare('%s', "%" . $params['search']['value'] . "%");
						$where .= " OR started_at LIKE " . $wpdb->prepare('%s', "%" . $params['search']['value'] . "%");
						$where .= " OR end_at LIKE " . $wpdb->prepare('%s', "%" . $params['search']['value'] . "%");
					}

					if (!empty($_POST['tahun_anggaran'])) {
						$where .= $wpdb->prepare(" AND tahun_anggaran = %d", $_POST['tahun_anggaran']);
					}

					// getting total number records without any search
	                $sql_tot = "SELECT count(id) as jml FROM `esakip_data_jadwal`";
	                $sqlRec = "SELECT " . implode(', ', $columns) . " FROM `esakip_data_jadwal`";
					if (isset($where) && $where != '') {
						$sqlTot .= $where;
						$sqlRec .= $where;
					}

					$sqlRec .=  $wpdb->prepare(" ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT %d ,%d ", $params['start'], $params['length']);

					$queryTot = $wpdb->get_results($sqlTot, ARRAY_A);
					$totalRecords = $queryTot[0]['jml'];
					$queryRecords = $wpdb->get_results($sqlRec, ARRAY_A);

					$checkOpenedSchedule = 0;
					if (!empty($queryRecords)) {
						foreach ($queryRecords as $recKey => $recVal) {
							// $report = '<a class="btn btn-sm btn-primary mr-2" style="text-decoration: none;" onclick="report(\'' . $recVal['id'] . '\'); return false;" href="#" title="Cetak Laporan"><i class="dashicons dashicons-printer"></i></a>';
							$edit	= '';
							$delete	= '';
							$lock	= '';
							if ($recVal['status'] == 0) {
							$checkOpenedSchedule++;
							$lock	= '<a class="btn btn-sm btn-success mr-2" style="text-decoration: none;" onclick="lock_data_penjadwalan(\'' . $recVal['id'] . '\'); return false;" href="#" title="Kunci data penjadwalan"><i class="dashicons dashicons-unlock"></i></a>';
							$edit	= '<a class="btn btn-sm btn-warning mr-2" style="text-decoration: none;" onclick="edit_data_penjadwalan(\'' . $recVal['id'] . '\'); return false;" href="#" title="Edit data penjadwalan"><i class="dashicons dashicons-edit"></i></a>';
							$delete	= '<a class="btn btn-sm btn-danger" style="text-decoration: none;" onclick="hapus_data_penjadwalan(\'' . $recVal['id'] . '\'); return false;" href="#" title="Hapus data penjadwalan"><i class="dashicons dashicons-trash"></i></a>';
							} else if ($recVal['status'] == 1) {
								$lock	= '<a class="btn btn-sm btn-success disabled" style="text-decoration: none;" onclick="cannot_change_schedule(\'kunci\'); return false;" href="#" title="Kunci data penjadwalan" aria-disabled="true"><i class="dashicons dashicons-lock"></i></a>'; 
						}

							$status = array(
								0 => 'terbuka',
								1 => 'dikunci',
								2 => 'selesai'
							);

							$queryRecords[$recKey]['started_at']	= date('d-m-Y H:i', strtotime($recVal['started_at']));
							$queryRecords[$recKey]['end_at']	= date('d-m-Y H:i', strtotime($recVal['end_at']));
							$queryRecords[$recKey]['aksi'] = $report . $lock . $edit . $delete;
							$queryRecords[$recKey]['nama_jadwal'] = ucfirst($recVal['nama_jadwal']);
							$queryRecords[$recKey]['status'] = $status[$recVal['status']];
						}

						$json_data = array(
							"draw"            => intval($params['draw']),
							"recordsTotal"    => intval($totalRecords),
							"recordsFiltered" => intval($totalRecords),
							"data"            => $queryRecords,
							"checkOpenedSchedule" => $checkOpenedSchedule
						);

						die(json_encode($json_data));
					} else {
						$json_data = array(
							"draw"            => intval($params['draw']),
							"recordsTotal"    => 0,
							"recordsFiltered" => 0,
							"data"            => array(),
							"checkOpenedSchedule" => $checkOpenedSchedule,
							"message"			=> "Data tidak ditemukan!"
						);

						die(json_encode($json_data));
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

	/** Submit data penjadwalan */
	public function submit_jadwal()
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
				
				if (
					!empty($_POST['nama_jadwal'])
					&& !empty($_POST['jadwal_mulai'])
					&& !empty($_POST['jadwal_selesai'])
					&& !empty($_POST['tahun_anggaran'])
				) {
					$nama_jadwal		= trim(htmlspecialchars($_POST['nama_jadwal']));
					$jadwal_mulai		= trim(htmlspecialchars($_POST['jadwal_mulai']));
					$jadwal_mulai		= date('Y-m-d H:i:s', strtotime($jadwal_mulai));
					$jadwal_selesai		= trim(htmlspecialchars($_POST['jadwal_selesai']));
					$jadwal_selesai		= date('Y-m-d H:i:s', strtotime($jadwal_selesai));
					$tahun_anggaran		= trim(htmlspecialchars($_POST['tahun_anggaran']));
                    $id = $_POST['id'];

					$arr_jadwal = ['usulan', 'penetapan'];

					$get_jadwal = $wpdb->get_results($wpdb->prepare("
						SELECT 
							* 
						FROM `esakip_data_jadwal` 
						WHERE id=%d
					", $id), ARRAY_A);
					foreach ($get_jadwal as $jadwal) {
						if ($jadwal['status'] != 1) {
							$return = array(
								'status' => 'error',
								'message'	=> 'Masih ada penjadwalan yang terbuka!'
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

					//insert data penjadwalan
					$data_jadwal = array(
						'nama_jadwal' 		=> $nama_jadwal,
						'started_at'		=> $jadwal_mulai,
						'end_at'		=> $jadwal_selesai,
						'tahun_anggaran'	=> $tahun_anggaran,
						'status'			=> 0,
						'tahun_anggaran'	=> $tahun_anggaran,
					);

					$wpdb->insert('esakip_data_jadwal', $data_jadwal);

					$return = array(
						'status'		=> 'success',
						'message'		=> 'Berhasil!',
						'data_jadwal'	=> $data_jadwal,
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

	/** Submit data penjadwalan */
	public function submit_edit_jadwal()
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
				if (!empty($_POST['id']) && !empty($_POST['nama_jadwal']) && !empty($_POST['jadwal_mulai']) && !empty($_POST['jadwal_selesai']) && !empty($_POST['tahun_anggaran'])) {
					$id = trim(htmlspecialchars($_POST['id']));
					$nama_jadwal	= trim(htmlspecialchars($_POST['nama_jadwal']));
					$jadwal_mulai	= trim(htmlspecialchars($_POST['jadwal_mulai']));
					$jadwal_mulai	= date('Y-m-d H:i:s', strtotime($jadwal_mulai));
					$jadwal_selesai	= trim(htmlspecialchars($_POST['jadwal_selesai']));
					$jadwal_selesai	= date('Y-m-d H:i:s', strtotime($jadwal_selesai));
					$tahun_anggaran	= trim(htmlspecialchars($_POST['tahun_anggaran']));


					$data_this_id = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_data_jadwal WHERE id = %d', $id), ARRAY_A);

					if (!empty($data_this_id)) {
						$status_check = array(0, NULL, 2);
						if (in_array($data_this_id['status'], $status_check)) {
							//update data penjadwalan
							$data_jadwal = array(
								'nama_jadwal' 			=> $nama_jadwal,
								'started_at'			=> $jadwal_mulai,
								'end_at'				=> $jadwal_selesai,
								'tahun_anggaran'		=> $tahun_anggaran
							);

							$wpdb->update('esakip_data_jadwal', $data_jadwal, array(
								'id'	=> $id
							));

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

	/** Submit delete data jadwal */
	public function delete_jadwal()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		$user_id = um_user('ID');
		$user_meta = get_userdata($user_id);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				if (!empty($_POST['id'])) {
					$id = trim(htmlspecialchars($_POST['id']));

					$data_this_id = $wpdb->get_row($wpdb->prepare('
						SELECT 
							* 
						FROM esakip_data_jadwal 
						WHERE id = %d
					', $id), ARRAY_A);

					if (!empty($data_this_id)) {
						$status_check = array(0, NULL, 2);
						if (in_array($data_this_id['status'], $status_check)) {
							$wpdb->delete('esakip_data_jadwal', array(
								'id' => $id
							), array('%d'));

							$return = array(
								'status' => 'success',
								'message'	=> 'Berhasil!',
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

	/** Submit lock data jadwal RPJM */
	public function lock_jadwal()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				if (!empty($_POST['id'])) {
					$id = trim(htmlspecialchars($_POST['id']));

					$data_this_id 	= $wpdb->get_row($wpdb->prepare('
						SELECT 
							* 
						FROM esakip_data_jadwal 
						WHERE id = %d
					', $id), ARRAY_A);

					$timezone = get_option('timezone_string');
					if (preg_match("/Asia/i", $timezone)) {
						date_default_timezone_set($timezone);
					} else {
						$return = array(
							'status' => 'error',
							'message'	=> "Pengaturan timezone salah. Pilih salah satu kota di zona waktu yang sama dengan anda, antara lain:  \'Jakarta\',\'Makasar\',\'Jayapura\'",
						);
						die(json_encode($return));
					}

					$dateTime = new DateTime();
					$time_now = $dateTime->format('Y-m-d H:i:s');
					if ($time_now > $data_this_id['started_at']) {
						$status_check = array(0, NULL, 2);
						if (in_array($data_this_id['status'], $status_check)) {

							//lock data penjadwalan
							$wpdb->update('esakip_data_jadwal', array('end_at' => $time_now, 'status' => 1), array(
								'id'	=> $id
							));

							$return = array(
								'status' => 'success',
								'message'	=> 'Berhasil!',
								'data_input' => $queryRecords1
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
							'message'	=> "Penjadwalan belum dimulai!",
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

	/** Ambil data penjadwalan RPJMD */
	public function get_data_penjadwalan_rpjmd()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				if (!empty($_POST['tipe'])) {
					$params = $columns = $totalRecords = $data = array();
					$params = $_REQUEST;
					$columns = array(
						0 => 'nama_jadwal',
						1 => 'keterangan',
						2 => 'tahun_anggaran',
						3 => 'lama_pelaksanaan',
						4 => 'tipe',
						5 => 'id',
					);
					$where = $sqlTot = $sqlRec = "";
					$where = " WHERE tipe='RPJMD' ";

					// check search value exist
					if (!empty($params['search']['value'])) {
						$where .= " AND ( nama LIKE " . $wpdb->prepare('%s', "%" . $params['search']['value'] . "%");
					}

					/** Search id tipe */

					if (!empty($_POST['tahun_anggaran'])) {
						$where .= $wpdb->prepare(" AND tahun_anggaran = %d", $_POST['tahun_anggaran']);
					}

					// getting total number records without any search
					$sqlTot = "SELECT count(*) as jml FROM `esakip_data_jadwal`";
					$sqlRec = "SELECT " . implode(', ', $columns) . " FROM `esakip_data_jadwal`";
					if (isset($where) && $where != '') {
						$sqlTot .= $where;
						$sqlRec .= $where;
					}

					$sqlRec .=  $wpdb->prepare(" ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT %d ,%d ", $params['start'], $params['length']);

					$queryTot = $wpdb->get_results($sqlTot, ARRAY_A);
					$totalRecords = $queryTot[0]['jml'];
					$queryRecords = $wpdb->get_results($sqlRec, ARRAY_A);

					if (!empty($queryRecords)) {
						foreach ($queryRecords as $recKey => $recVal) {
							$edit	= '';
							$delete	= '';
							$edit	= '<a class="btn btn-sm btn-warning mr-2" style="text-decoration: none;" onclick="edit_data_penjadwalan(\'' . $recVal['id'] . '\'); return false;" href="#" title="Edit data penjadwalan"><i class="dashicons dashicons-edit"></i></a>';
							$delete	= '<a class="btn btn-sm btn-danger" style="text-decoration: none;" onclick="hapus_data_penjadwalan(\'' . $recVal['id'] . '\'); return false;" href="#" title="Hapus data penjadwalan"><i class="dashicons dashicons-trash"></i></a>';

							$tahun_anggaran_selesai = $recVal['tahun_anggaran'] + $recVal['lama_pelaksanaan'];

							$queryRecords[$recKey]['aksi'] = $edit . $delete;
							$queryRecords[$recKey]['nama_jadwal'] = ucfirst($recVal['nama_jadwal']);
							$queryRecords[$recKey]['tahun_anggaran_selesai'] = $tahun_anggaran_selesai;

						}

						$json_data = array(
							"draw"            => intval($params['draw']),
							"recordsTotal"    => intval($totalRecords),
							"recordsFiltered" => intval($totalRecords),
							"data"            => $queryRecords,
						);

						die(json_encode($json_data));
					} else {
						$json_data = array(
							"draw"            => intval($params['draw']),
							"recordsTotal"    => 0,
							"recordsFiltered" => 0,
							"data"            => array(),
							"message"			=> "Data tidak ditemukan!"
						);

						die(json_encode($json_data));
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

	/** Submit data penjadwalan */
	public function submit_jadwal_rpjmd()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				if (!empty($_POST['nama_jadwal']) && !empty($_POST['lama_pelaksanaan']) && !empty($_POST['keterangan']) && !empty($_POST['tahun_anggaran'])) {
					$nama_jadwal	= trim(htmlspecialchars($_POST['nama_jadwal']));
					$tahun_anggaran	= trim(htmlspecialchars($_POST['tahun_anggaran']));
					$keterangan		= trim(htmlspecialchars($_POST['keterangan']));
					$lama_pelaksanaan 	= trim(htmlspecialchars($_POST['lama_pelaksanaan']));
					$tipe 	= trim(htmlspecialchars($_POST['tipe']));


					$data_this_id = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_data_jadwal WHERE id = %d', $id), ARRAY_A);

					//update data penjadwalan
					$data_jadwal = array(
						'nama_jadwal' 			=> $nama_jadwal,
						'tahun_anggaran'		=> $tahun_anggaran,
						'keterangan'			=> $keterangan,
						'tipe'					=> 'RPJMD',
						'lama_pelaksanaan'		=> $lama_pelaksanaan
					);

					$wpdb->insert('esakip_data_jadwal', $data_jadwal);

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

	/** get data default lama pelaksanaan by id */
	public function get_lama_pelaksanaan_rpjmd()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				$tipe = $_POST['tipe'];

				$lama_pelaksanaan = $wpdb->get_results($wpdb->prepare('SELECT * FROM esakip_data_jadwal WHERE tipe = %s', $tipe), ARRAY_A);

				$return = array(
					'status' 						=> 'success',
					'data' 							=> $lama_pelaksanaan[0]
				);
			} else {
				$return = array(
					'status'	=> 'error',
					'message'	=> 'Api Key tidak sesuai!'
				);
			}
		} else {
			$return = array(
				'status'	=> 'error',
				'message'	=> 'Format tidak sesuai!'
			);
		}
		die(json_encode($return));
	}

	/** Submit data penjadwalan */
	public function submit_edit_jadwal_rpjmd()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				if (!empty($_POST['id']) && !empty($_POST['nama_jadwal']) && !empty($_POST['lama_pelaksanaan']) && !empty($_POST['keterangan']) && !empty($_POST['tahun_anggaran'])) {
					$id = trim(htmlspecialchars($_POST['id']));
					$nama_jadwal	= trim(htmlspecialchars($_POST['nama_jadwal']));
					$tahun_anggaran	= trim(htmlspecialchars($_POST['tahun_anggaran']));
					$keterangan		= trim(htmlspecialchars($_POST['keterangan']));
					$lama_pelaksanaan 	= trim(htmlspecialchars($_POST['lama_pelaksanaan']));
					$tipe 	= trim(htmlspecialchars($_POST['tipe']));


					$data_this_id = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_data_jadwal WHERE id = %d', $id), ARRAY_A);

					//update data penjadwalan
					$data_jadwal = array(
						'nama_jadwal' 			=> $nama_jadwal,
						'tahun_anggaran'		=> $tahun_anggaran,
						'keterangan'			=> $keterangan,
						'tipe'					=> 'RPJMD',
						'lama_pelaksanaan'		=> $lama_pelaksanaan
					);

					$wpdb->update('esakip_data_jadwal', $data_jadwal, array(
						'id'	=> $id
					));

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

	/** Submit delete data jadwal */
	public function delete_jadwal_rpjmd()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				if (!empty($_POST['id'])) {
					$id = trim(htmlspecialchars($_POST['id']));

					$data_this_id = $wpdb->get_row($wpdb->prepare('
						SELECT 
							* 
						FROM esakip_data_jadwal 
						WHERE id = %d
					', $id), ARRAY_A);

					if (!empty($data_this_id)) {
							$wpdb->delete('esakip_data_jadwal', array(
								'id' => $id
							), array('%d'));

							$return = array(
								'status' => 'success',
								'message'	=> 'Berhasil!',
							);
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
}
