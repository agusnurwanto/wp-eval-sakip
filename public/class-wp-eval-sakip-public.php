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

	public function rpjmd()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-rpjmd.php';
	}

	public function rkpd()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-rkpd.php';
	}

	public function lkjip_lppd()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-lkjip-lppd.php';
	}

	public function dokumen_pemda_lainnya()
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


	public function mapping_skpd()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil memproses data!'
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
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
						update_option('_nama_skpd_sakip_' . $skpd['id_skpd'], $nama_skpd_sakip);
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

				if ($ret['status'] == 'success') {
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
						$wpdb->update(
							'esakip_renja_rkt',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
							),
							array('id' => $id_dokumen),
							array('%s', '%s', '%s', '%s', '%d'),
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

				if ($ret['status'] == 'success') {
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
						$wpdb->update(
							'esakip_perjanjian_kinerja',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
							),
							array('id' => $id_dokumen),
							array('%s', '%s', '%s', '%s', '%d'),
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

				if ($ret['status'] == 'success') {
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
						$wpdb->update(
							'esakip_evaluasi_internal',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
							),
							array('id' => $id_dokumen),
							array('%s', '%s', '%s', '%s', '%d'),
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

				if ($ret['status'] == 'success') {
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
						$wpdb->update(
							'esakip_laporan_kinerja',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
							),
							array('id' => $id_dokumen),
							array('%s', '%s', '%s', '%s', '%d'),
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

				if ($ret['status'] == 'success') {
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
						$wpdb->update(
							'esakip_iku',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
							),
							array('id' => $id_dokumen),
							array('%s', '%s', '%s', '%s', '%d'),
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

				if ($ret['status'] == 'success') {
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
						$wpdb->update(
							'esakip_dokumen_lainnya',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
							),
							array('id' => $id_dokumen),
							array('%s', '%s', '%s', '%s', '%d'),
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

				if ($ret['status'] == 'success') {
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
						$wpdb->update(
							'esakip_rencana_aksi',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
							),
							array('id' => $id_dokumen),
							array('%s', '%s', '%s', '%s', '%d'),
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

				if ($ret['status'] == 'success') {
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
						$wpdb->update(
							'esakip_pengukuran_kinerja',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
							),
							array('id' => $id_dokumen),
							array('%s', '%s', '%s', '%s', '%d'),
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

				if ($ret['status'] == 'success') {
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
						$wpdb->update(
							'esakip_pengukuran_rencana_aksi',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
							),
							array('id' => $id_dokumen),
							array('%s', '%s', '%s', '%s', '%d'),
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

				if ($ret['status'] == 'success') {
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
						$wpdb->update(
							'esakip_skp',
							array(
								'opd' => $skpd,
								'id_skpd' => $idSkpd,
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'tahun_anggaran' => $tahunAnggaran,
							),
							array('id' => $id_dokumen),
							array('%s', '%s', '%s', '%s', '%d'),
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
}
