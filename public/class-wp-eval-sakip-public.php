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

	public function jadwal_evaluasi_sakip($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-jadwal-evaluasi.php';
	}

	public function upload_dokumen_renstra($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}

		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-renstra-per-skpd.php';
	}

	public function renstra($atts)
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

	public function upload_dokumen_rpjmd($atts)
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

	public function dokumen_detail_dokumen_lainnya($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-dokumen-lain-per-skpd.php';
	}
	
	public function pengisian_lke_sakip($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-pengisian-lke-sakip.php';
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

	public function get_detail_dokumen_lain_by_id()
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

	public function submit_tahun_renstra()
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
				if (!empty($_POST['id_jadwal'])) {
					$tahun_periode = $_POST['id_jadwal'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Periode kosong!';
				}

				if (!empty($id) && !empty($tahun_periode)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_renstra 
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_renstra',
							array(
								'id_jadwal' => $tahun_periode,
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

	public function submit_tahun_rpjmd()
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
				if (!empty($_POST['tahunPeriode'])) {
					$tahun_periode = $_POST['tahunPeriode'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Periode kosong!';
				}

				if (!empty($id) && !empty($tahun_periode)) {
					$existing_data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_rpjmd 
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_rpjmd',
							array(
								'id_jadwal' => $tahun_periode,
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

	public function submit_tahun_dokumen_lain()
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

	public function submit_tahun_lkjip_lppd()
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
							FROM esakip_lkjip_lppd
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_lkjip_lppd',
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

	public function submit_tahun_rkpd()
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
							FROM esakip_rkpd
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_rkpd',
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

	public function submit_tahun_dokumen_pemda_lain()
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
							FROM esakip_other_file
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_other_file',
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

	public function tambah_dokumen_lain()
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

	public function tambah_dokumen_pemda_lain()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
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
				if (!empty($_POST['id_jadwal'])) {
					$id_jadwal = $_POST['id_jadwal'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
				}
				if (!empty($_POST['keterangan'])) {
					$keterangan = $_POST['keterangan'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Keterangan kosong!';
				}
				if (empty($_FILES['fileUpload']) && empty($id_dokumen)) {
					$ret['status'] = 'error';
					$ret['message'] = 'File Dokumen kosong!';
				}
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
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
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'id_jadwal' => $id_jadwal,
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
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
				if (!empty($_POST['id_jadwal'])) {
					$id_jadwal = $_POST['id_jadwal'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
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
								'id_jadwal' => $id_jadwal,
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
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
                    WHERE tahun_anggaran = %d 
                      AND active = 1
                ", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($lkjip_lppds)) {
					$counter = 1;
					$tbody = '';

					foreach ($lkjip_lppds as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_lkjip(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_lkjip(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $btn . "</td>";
						$tbody .= "</tr>";
					}

					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
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
                    WHERE tahun_anggaran = %d 
                      AND active = 1
                ", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($dokumen_pemda_lains)) {
					$counter = 1;
					$tbody = '';

					foreach ($dokumen_pemda_lains as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_pemda_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_pemda_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $btn . "</td>";
						$tbody .= "</tr>";
					}

					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
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
				if (!empty($_POST['id_periode'])) {
					$id_jadwal = $_POST['id_periode'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
				}
				$rpjmds = $wpdb->get_results(
					$wpdb->prepare("
						SELECT * 
						FROM esakip_rpjmd
						WHERE id_jadwal = %d 
						  AND active = 1
					", $id_jadwal),
					ARRAY_A
				);

				if (!empty($rpjmds)) {
					$counter = 1;
					$tbody = '';

					foreach ($rpjmds as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
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
					$ret['data'] = "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
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
						WHERE tahun_anggaran = %d 
						  AND active = 1
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($rkpds)) {
					$counter = 1;
					$tbody = '';

					foreach ($rkpds as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
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
					$ret['data'] = "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
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
				if (!empty($_POST['id_periode'])) {
					$id_jadwal = $_POST['id_periode'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
				}
				$renstras = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_renstra
                    WHERE id_skpd = %d 
                      AND id_jadwal = %d 
                      AND active = 1
                ", $id_skpd, $id_jadwal),
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

	public function hapus_dokumen_lain()
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
					4 => 'jenis_jadwal',
					5 => 'tahun_anggaran',
					6 => 'id',
				);
				$where = $sqlTot = $sqlRec = "";
				$where = " WHERE tipe = 'LKE' AND status != 0";

				// check search value exist
				if (!empty($params['search']['value'])) {
					$where .= " AND ( 
						nama_jadwal LIKE " . $wpdb->prepare('%s', "%" . $params['search']['value'] . "%") . " 
						OR started_at LIKE " . $wpdb->prepare('%s', "%" . $params['search']['value'] . "%") . "
						OR jenis_jadwal LIKE " . $wpdb->prepare('%s', "%" . $params['search']['value'] . "%") . '
					)';
				}

				// getting total number records without any search
				$sqlTot = "SELECT count(id) as jml FROM `esakip_data_jadwal`";
				$sqlRec = "SELECT " . implode(', ', $columns) . " FROM `esakip_data_jadwal`";
				$sqlTot .= $where;
				$sqlRec .= $where;

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
						$lke	= '';
						if ($recVal['status'] == 1) {
							$checkOpenedSchedule++;
							$lke = '<div class="btn-group mr-2" role="group">';
							$lke .= '<a class="btn btn-sm btn-info" style="text-decoration: none;" onclick="set_desain_lke(\'' . $recVal['id'] . '\'); return false;" href="#" title="Set Desain LKE"><i class="dashicons dashicons-editor-table"></i></a>';
							$lke .= '</div>';

							$lock = '<div class="btn-group mr-2" role="group">';
							$lock .= '<a class="btn btn-sm btn-success" style="text-decoration: none;" onclick="lock_data_penjadwalan(\'' . $recVal['id'] . '\'); return false;" href="#" title="Kunci data penjadwalan"><i class="dashicons dashicons-unlock"></i></a>';
							$lock .= '</div>';

							$edit = '<div class="btn-group mr-2" role="group">';
							$edit .= '<a class="btn btn-sm btn-warning" style="text-decoration: none;" onclick="edit_data_penjadwalan(\'' . $recVal['id'] . '\'); return false;" href="#" title="Edit data penjadwalan"><i class="dashicons dashicons-edit"></i></a>';
							$edit .= '</div>';

							$delete = '<div class="btn-group" role="group">';
							$delete .= '<a class="btn btn-sm btn-danger" style="text-decoration: none;" onclick="hapus_data_penjadwalan(\'' . $recVal['id'] . '\'); return false;" href="#" title="Hapus data penjadwalan"><i class="dashicons dashicons-trash"></i></a>';
							$delete .= '</div>';
						} else if ($recVal['status'] == 2) {
							$lock = '<div class="btn-group" role="group">';
							$lock .= '<a class="btn btn-sm btn-success disabled" style="text-decoration: none;" onclick="cannot_change_schedule(\'kunci\'); return false;" href="#" title="Kunci data penjadwalan" aria-disabled="true"><i class="dashicons dashicons-lock"></i></a>';
							$lock .= '</div>';
						}

						$status = array(
							0 => 'Dihapus',
							1 => 'Active',
							2 => 'Dikunci'
						);

						$queryRecords[$recKey]['started_at']	= date('d-m-Y H:i', strtotime($recVal['started_at']));
						$queryRecords[$recKey]['end_at']	= date('d-m-Y H:i', strtotime($recVal['end_at']));
						$queryRecords[$recKey]['aksi'] = $lke . $lock . $edit . $delete;
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
				if (!empty($_POST['nama_jadwal']) && !empty($_POST['jenis_jadwal']) && !empty($_POST['tahun_anggaran'])) {
					$nama_jadwal		= trim(htmlspecialchars($_POST['nama_jadwal']));
					$jadwal_mulai		= trim(htmlspecialchars($_POST['jadwal_mulai']));
					$jadwal_mulai		= date('Y-m-d H:i:s', strtotime($jadwal_mulai));
					$jadwal_selesai		= trim(htmlspecialchars($_POST['jadwal_selesai']));
					$jadwal_selesai		= date('Y-m-d H:i:s', strtotime($jadwal_selesai));
					$tahun_anggaran		= trim(htmlspecialchars($_POST['tahun_anggaran']));
					$tipe 	= trim(htmlspecialchars($_POST['tipe']));
					$jenis_jadwal		= trim(htmlspecialchars($_POST['jenis_jadwal']));

					$arr_jadwal = ['usulan', 'penetapan'];
					$jenis_jadwal = in_array($jenis_jadwal, $arr_jadwal) ? $jenis_jadwal : 'usulan';

					$get_jadwal = $wpdb->get_results($wpdb->prepare("
						SELECT 
							* 
						FROM `esakip_data_jadwal` 
						WHERE id=%d
					", $id), ARRAY_A);
					foreach ($get_jadwal as $jadwal) {
						if ($jadwal['status'] != 2) {
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
						'end_at'			=> $jadwal_selesai,
						'tahun_anggaran'	=> $tahun_anggaran,
						'status'			=> 1,
						'tahun_anggaran'	=> $tahun_anggaran,
						'jenis_jadwal'	=> $jenis_jadwal,
						'tipe'	=> 'LKE',
						'lama_pelaksanaan'	=> 1,
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
				if (!empty($_POST['id']) && !empty($_POST['nama_jadwal']) && !empty($_POST['jadwal_mulai']) && !empty($_POST['jadwal_selesai']) && !empty($_POST['jenis_jadwal'])) {
					$id = trim(htmlspecialchars($_POST['id']));
					$nama_jadwal	= trim(htmlspecialchars($_POST['nama_jadwal']));
					$jadwal_mulai	= trim(htmlspecialchars($_POST['jadwal_mulai']));
					$jadwal_mulai	= date('Y-m-d H:i:s', strtotime($jadwal_mulai));
					$jadwal_selesai	= trim(htmlspecialchars($_POST['jadwal_selesai']));
					$jadwal_selesai	= date('Y-m-d H:i:s', strtotime($jadwal_selesai));
					$tahun_anggaran	= trim(htmlspecialchars($_POST['tahun_anggaran']));
					$tipe 	= trim(htmlspecialchars($_POST['tipe']));
					$jenis_jadwal 	= trim(htmlspecialchars($_POST['jenis_jadwal']));


					$data_this_id = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_data_jadwal WHERE id = %d', $id), ARRAY_A);

					if (!empty($data_this_id)) {
						$status_check = array(1, NULL);
						if (in_array($data_this_id['status'], $status_check)) {
							//update data penjadwalan
							$data_jadwal = array(
								'nama_jadwal' 			=> $nama_jadwal,
								'started_at'			=> $jadwal_mulai,
								'end_at'				=> $jadwal_selesai,
								'jenis_jadwal'			=> $jenis_jadwal,
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
						$status_check = array(1, NULL, 2);
						if (in_array($data_this_id['status'], $status_check)) {
							$wpdb->update('esakip_data_jadwal', array('status' => 0), array(
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
						'message'	=> 'ID tidak ditemukan!'
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
						$status_check = array(1, NULL, 2);
						if (in_array($data_this_id['status'], $status_check)) {

							//lock data penjadwalan
							$wpdb->update('esakip_data_jadwal', array('end_at' => $time_now, 'status' => 2), array(
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
					$where = " WHERE tipe = 'RPJMD' AND status != 0";

					// check search value exist
					if (!empty($params['search']['value'])) {
						$where .= " AND ( nama LIKE " . $wpdb->prepare('%s', "%" . $params['search']['value'] . "%");
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
						'status'					=> '1',
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

	public function delete_jadwal_rpjmd()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				$id = trim(htmlspecialchars($_POST['id']));
				// Periksa apakah data dengan ID yang akan dihapus ada di tabel esakip_renstra
				$cek_id_jadwal_renstra = $wpdb->get_var($wpdb->prepare('
                    SELECT id_jadwal
                    FROM esakip_renstra
                    WHERE id_jadwal=%d
                    	AND active=1
                ', $id));
				if ($cek_id_jadwal_renstra) {
					// Jika data dengan ID yang sama ditemukan di tabel lain, tampilkan pesan
					$ret['status'] = 'confirm';
					$ret['message'] = 'Jadwal tidak bisa dihapus karena sudah terpakai di dokumen RENSTRA.';
				}
				$cek_id_jadwal_rpjmd = $wpdb->get_var($wpdb->prepare('
                    SELECT id_jadwal
                    FROM esakip_rpjmd
                    WHERE id_jadwal=%d
                    	AND active=1
                ', $id));
				if ($cek_id_jadwal_rpjmd) {
					// Jika data dengan ID yang sama ditemukan di tabel lain, tampilkan pesan
					$ret['status'] = 'confirm';
					$ret['message'] = 'Jadwal tidak bisa dihapus karena sudah terpakai di dokumen RPJMD / RPD.';
				}
				if ($ret['status'] != 'confirm') {
					// Jika tidak ada data dengan ID yang sama di tabel lain, lanjutkan penghapusan seperti biasa
					$ret['data'] = $wpdb->update('esakip_data_jadwal', array('status' => 0), array(
						'id' => $id
					));
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

	public function get_table_skpd_dokumen_lainnya()
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
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_dokumen_lainnya = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen dokumen_lainnya/RKT ' . $tahun_anggaran,
							'content' => '[dokumen_detail_dokumen_lainnya tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$detail_dokumen_lainnya['url'] .= '?1=1';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(id)
								FROM esakip_dokumen_lainnya 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_dokumen_lainnya['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
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

	public function get_table_tahun_dokumen_lainnya()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_dokumen_lainnya 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_tahun_dokumen_pemda_lain()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_other_file
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_tahun_rpjmd()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'id_jadwal IS NULL';

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_rpjmd 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Periode</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_tahun_renstra()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'id_jadwal IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_renstra 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Periode</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_skpd_perjanjian_kinerja()
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
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_perjanjian_kinerja = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Perjanjian Kinerja ' . $tahun_anggaran,
							'content' => '[dokumen_detail_perjanjian_kinerja tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$detail_perjanjian_kinerja['url'] .= '?1=1';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(id)
								FROM esakip_perjanjian_kinerja 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_perjanjian_kinerja['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
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

	public function get_table_tahun_perjanjian_kinerja()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_perjanjian_kinerja 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_skpd_rencana_aksi()
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
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_rencana_aksi = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Rencana Aksi ' . $tahun_anggaran,
							'content' => '[dokumen_detail_rencana_aksi tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$detail_rencana_aksi['url'] .= '?1=1';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(id)
								FROM esakip_rencana_aksi 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_rencana_aksi['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
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

	public function get_table_tahun_rencana_aksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_rencana_aksi 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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
	public function get_table_skpd_iku()
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
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_iku = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Rencana Aksi ' . $tahun_anggaran,
							'content' => '[dokumen_detail_iku tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$detail_iku['url'] .= '?1=1';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(id)
								FROM esakip_iku 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_iku['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
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

	public function get_table_tahun_iku()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_iku 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_skpd_evaluasi_internal()
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
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_evaluasi_internal = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Evaluasi Internal ' . $tahun_anggaran,
							'content' => '[dokumen_detail_evaluasi_internal tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$detail_evaluasi_internal['url'] .= '?1=1';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(id)
								FROM esakip_evaluasi_internal 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_evaluasi_internal['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
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

	public function get_table_tahun_evaluasi_internal()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_evaluasi_internal 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_skpd_pengukuran_kinerja()
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
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_pengukuran_kinerja = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Pengukuran Kinerja ' . $tahun_anggaran,
							'content' => '[dokumen_detail_pengukuran_kinerja tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$detail_pengukuran_kinerja['url'] .= '?1=1';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(id)
								FROM esakip_pengukuran_kinerja 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_pengukuran_kinerja['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
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

	public function get_table_tahun_pengukuran_kinerja()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_pengukuran_kinerja 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_skpd_pengukuran_rencana_aksi()
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
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_pengukuran_rencana_aksi = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Rencana Aksi ' . $tahun_anggaran,
							'content' => '[dokumen_detail_pengukuran_rencana_aksi tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$detail_pengukuran_rencana_aksi['url'] .= '?1=1';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(id)
								FROM esakip_pengukuran_rencana_aksi 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_pengukuran_rencana_aksi['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
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

	public function get_table_tahun_pengukuran_rencana_aksi()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_pengukuran_rencana_aksi 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_skpd_laporan_kinerja()
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
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_laporan_kinerja = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Laporan Kinerja ' . $tahun_anggaran,
							'content' => '[dokumen_detail_laporan_kinerja tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$detail_laporan_kinerja['url'] .= '?1=1';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(id)
								FROM esakip_laporan_kinerja 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_laporan_kinerja['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
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

	public function get_table_tahun_laporan_kinerja()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_laporan_kinerja 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_tahun_rkpd()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_rkpd 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_tahun_lkjip()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_lkjip_lppd
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_skpd_renja()
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
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_renja = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen RENJA/RKT ' . $tahun_anggaran,
							'content' => '[dokumen_detail_renja_rkt tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$detail_renja['url'] .= '?1=1';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(id)
								FROM esakip_renja_rkt 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_renja['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
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

	public function get_table_skpd_skp()
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
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_skp = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen SKP ' . $tahun_anggaran,
							'content' => '[dokumen_detail_skp tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$detail_skp['url'] .= '?1=1';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(id)
								FROM esakip_skp 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_skp['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
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

	public function get_table_tahun_renja()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_renja_rkt 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_tahun_skp()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'tahun_anggaran IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_skp 
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
								<div class="wrap-table">
									<table id="table_dokumen_tahun" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	public function get_table_skpd_renstra()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_periode'])) {
					$id_jadwal = $_POST['id_periode'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				}

				$unit = $wpdb->get_results(
					$wpdb->prepare("
					SELECT 
						nama_skpd, 
						id_skpd, 
						kode_skpd, 
						nipkepala 
					FROM esakip_data_unit 
					WHERE tahun_anggaran=%d
					  AND active=1 
					  AND is_skpd=1 
					ORDER BY kode_skpd ASC
					", $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_rensra = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen RENSTRA ' . $id_jadwal,
							'content' => '[upload_dokumen_renstra periode=' . $id_jadwal . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$detail_rensra['url'] .= '?1=1';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(id)
								FROM esakip_renstra
								WHERE id_skpd = %d
								  AND id_jadwal = %d
								  AND active = 1
								",
								$vv['id_skpd'],
								$id_jadwal
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_rensra['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $jumlah_dokumen . "</td>";
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

	public function get_table_periode_renstra()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where = 'id_jadwal IS NULL';

				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
					$where .= " AND id_skpd = $id_skpd";
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_renstra
					WHERE $where
					  AND active = 1
					",
					ARRAY_A
				);

				$counterUnset = 1;
				$tbodyUnset = '';
				if (!empty($dokumen_unset)) {
					$tbodyUnset .= '
						<div class="cetak">
							<div style="padding: 10px;margin:0 0 3rem 0;">
								<h3 class="text-center">Dokumen yang belum disetting Tahun Periode</h3>
								<div class="wrap-table">
									<table id="table_dokumen_periode" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Perangkat Daerah</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Keterangan</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>';
					foreach ($dokumen_unset as $kk => $vv) {
						$tbodyUnset .= "<tr>";
						$tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
						$tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
						$tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-success' onclick='set_periode_dokumen(" . $vv['id'] . "); return false;' title='Set Periode Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						$btn .= '<button class="btn btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '</div>';

						$tbodyUnset .= "<td class='text-center'>" . $btn . "</td>";

						$tbodyUnset .= "</tr>";
					}
					$tbodyUnset .= '</tbody>
								</table>
							</div>
						</div>
					';

					$ret['data'] = $tbodyUnset;
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

	function menu_eval_sakip()
	{
		global $wpdb;
		$user_id = um_user('ID');
		$user_meta = get_userdata($user_id);
		$html = '';
		if (!empty($_GET) && !empty($_GET['tahun'])) {
			echo '<h1 class="text-center">TAHUN ANGGARAN TERPILIH<br>' . $_GET['tahun'] . '</h1>';
		}
		if (empty($user_meta->roles)) {
			return 'User ini tidak dapat akses sama sekali :)';
		}

		$this->pilih_tahun_anggaran();
		if (empty($_GET) || empty($_GET['tahun'])) {
			return;
		}

		$jadwal_periode = $wpdb->get_results(
			"
			SELECT 
				id,
				nama_jadwal,
				tahun_anggaran,
				lama_pelaksanaan
			FROM esakip_data_jadwal
			WHERE tipe = 'RPJMD'
			  AND status = 1",
			ARRAY_A
		);
		$periode_rpjmd = '';
		$periode_renstra = '';
		foreach ($jadwal_periode as $jadwal_periode_item) {
			$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];

			$rpjmd = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Upload Dokumen RPJMD ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[upload_dokumen_rpjmd periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$periode_rpjmd .= '<li><a target="_blank" href="' . $rpjmd['url'] . '" class="btn btn-primary">' . $rpjmd['title'] . '</a></li>';

			$renstra = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen RENSTRA ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[renstra periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$periode_renstra .= '<li><a target="_blank" href="' . $renstra['url'] . '" class="btn btn-primary">' . $renstra['title'] . '</a></li>';
		}

		if (empty($periode_rpjmd)) {
			$periode_rpjmd = '<li><a return="false" href="#" class="btn btn-secondary">Periode RPJMD kosong atau belum dibuat</a></li>';
		}

		if (empty($periode_renstra)) {
			$periode_renstra = '<li><a return="false" href="#" class="btn btn-secondary">Periode RPJMD kosong atau belum dibuat</a></li>';
		}
		$halaman_rpjmd = '
			<div class="accordion">
				<h5 class="esakip-header-tahun" data-id="rpjmd" style="margin: 0;">Periode Upload Dokumen RPJMD</h5>
				<div class="esakip-body-tahun" data-id="rpjmd">
					<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
						' . $periode_rpjmd . '
					</ul>
				</div>
			</div>';
		$halaman_renstra = '
			<div class="accordion">
				<h5 class="esakip-header-tahun" data-id="renstra" style="margin: 0;">Periode Upload Dokumen RENSTRA</h5>
				<div class="esakip-body-tahun" data-id="renstra">
					<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
						' . $periode_renstra . '
					</ul>
				</div>
			</div>';
		if (
			in_array("administrator", $user_meta->roles)
			|| in_array("admin_bappeda", $user_meta->roles)
			|| in_array("admin_ortala", $user_meta->roles)
		) {
			$renja_rkt = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen RENJA/RKT Tahun ' . $_GET['tahun'],
				'content' => '[renja_rkt tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$skp = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen SKP Tahun ' . $_GET['tahun'],
				'content' => '[skp tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$rencana_aksi = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Rencana Aksi Tahun ' . $_GET['tahun'],
				'content' => '[rencana_aksi tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$iku = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen IKU Tahun ' . $_GET['tahun'],
				'content' => '[iku tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$pengukuran_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Pengukuran Kinerja Tahun ' . $_GET['tahun'],
				'content' => '[pengukuran_kinerja tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$pengukuran_rencana_aksi = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Pengukuran Rencana Aksi Tahun ' . $_GET['tahun'],
				'content' => '[pengukuran_rencana_aksi tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$laporan_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Laporan Kinerja Tahun ' . $_GET['tahun'],
				'content' => '[laporan_kinerja tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$evaluasi_internal = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Evaluasi Internal Tahun ' . $_GET['tahun'],
				'content' => '[evaluasi_internal tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$dokumen_lainnya = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Lain Tahun ' . $_GET['tahun'],
				'content' => '[dokumen_lainnya tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$perjanjian_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Perjanjian Kinerja Tahun ' . $_GET['tahun'],
				'content' => '[perjanjian_kinerja tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$rkpd = $this->functions->generatePage(array(
				'nama_page' => 'Halaman RKPD Tahun ' . $_GET['tahun'],
				'content' => '[rkpd tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$dokumen_pemda_lainnya = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Lainnya Tahun ' . $_GET['tahun'],
				'content' => '[dokumen_pemda_lainnya tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$lkjip_lppd = $this->functions->generatePage(array(
				'nama_page' => 'Halaman LKJIP/LPPD  ' . $_GET['tahun'],
				'content' => '[lkjip_lppd tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			echo '
				<ul class="daftar-tahun text_tengah">
					<li>' . $halaman_rpjmd . '</li>
					<li>' . $halaman_renstra . '</li>
					<li><a href="' . $renja_rkt['url'] . '" target="_blank" class="btn btn-primary">' . $renja_rkt['title'] . '</a></li>
					<li><a href="' . $skp['url'] . '" target="_blank" class="btn btn-primary">' . $skp['title'] . '</a></li>
					<li><a href="' . $rencana_aksi['url'] . '" target="_blank" class="btn btn-primary">' . $rencana_aksi['title'] . '</a></li>
					<li><a href="' . $iku['url'] . '" target="_blank" class="btn btn-primary">' . $iku['title'] . '</a></li>
					<li><a href="' . $pengukuran_kinerja['url'] . '" target="_blank" class="btn btn-primary">' . $pengukuran_kinerja['title'] . '</a></li>
					<li><a href="' . $pengukuran_rencana_aksi['url'] . '" target="_blank" class="btn btn-primary">' . $pengukuran_rencana_aksi['title'] . '</a></li>
					<li><a href="' . $laporan_kinerja['url'] . '" target="_blank" class="btn btn-primary">' . $laporan_kinerja['title'] . '</a></li>
					<li><a href="' . $evaluasi_internal['url'] . '" target="_blank" class="btn btn-primary">' . $evaluasi_internal['title'] . '</a></li>
					<li><a href="' . $dokumen_lainnya['url'] . '" target="_blank" class="btn btn-primary">' . $dokumen_lainnya['title'] . '</a></li>
					<li><a href="' . $perjanjian_kinerja['url'] . '" target="_blank" class="btn btn-primary">' . $perjanjian_kinerja['title'] . '</a></li>
					<li><a href="' . $rkpd['url'] . '" target="_blank" class="btn btn-primary">' . $rkpd['title'] . '</a></li>
					<li><a href="' . $dokumen_pemda_lainnya['url'] . '" target="_blank" class="btn btn-primary">' . $dokumen_pemda_lainnya['title'] . '</a></li>
					<li><a href="' . $lkjip_lppd['url'] . '" target="_blank" class="btn btn-primary">' . $lkjip_lppd['title'] . '</a></li>
				</ul>';
		} else if (
			in_array("pa", $user_meta->roles)
			|| in_array("kpa", $user_meta->roles)
			|| in_array("plt", $user_meta->roles)
		) {
			$nipkepala = get_user_meta($user_id, '_nip');
			$skpd_db = $wpdb->get_row($wpdb->prepare("
				SELECT 
					nama_skpd, 
					id_skpd, 
					kode_skpd,
					is_skpd
				from esakip_data_unit 
				where nipkepala=%s 
					and tahun_anggaran=%d
				group by id_skpd", $nipkepala[0], $_GET['tahun']), ARRAY_A);
			$detail_renja = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen RENJA/RKT ' . $_GET['tahun'],
				'content' => '[dokumen_detail_renja_rkt tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_renja['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_skp = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen SKP ' . $_GET['tahun'],
				'content' => '[dokumen_detail_skp tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_skp['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_rencana_aksi = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen Rencana Aksi ' . $_GET['tahun'],
				'content' => '[dokumen_detail_rencana_aksi tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_rencana_aksi['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_iku = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen IKU ' . $_GET['tahun'],
				'content' => '[dokumen_detail_iku tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_iku['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_pengukuran_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen Pengukuran Kinerja ' . $_GET['tahun'],
				'content' => '[dokumen_detail_pengukuran_kinerja tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_pengukuran_kinerja['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_laporan_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen Laporan Kinerja ' . $_GET['tahun'],
				'content' => '[dokumen_detail_laporan_kinerja tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_laporan_kinerja['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_evaluasi_internal = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen Evaluasi Internal ' . $_GET['tahun'],
				'content' => '[dokumen_detail_evaluasi_internal tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_evaluasi_internal['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_dokumen_lain = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen Lain ' . $_GET['tahun'],
				'content' => '[dokumen_detail_dokumen_lain tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_dokumen_lain['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_perjanjian_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen Perjanjian Kinerja ' . $_GET['tahun'],
				'content' => '[dokumen_detail_perjanjian_kinerja tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_perjanjian_kinerja['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			echo '
				<ul class="daftar-menu-sakip">
					<li>' . $halaman_renstra . '</li>
					<li><a href="' . $detail_renja['url'] . '" target="_blank" class="btn btn-primary">' . $detail_renja['title'] . '</a></li>
					<li><a href="' . $detail_skp['url'] . '" target="_blank" class="btn btn-primary">' . $detail_skp['title'] . '</a></li>
					<li><a href="' . $detail_rencana_aksi['url'] . '" target="_blank" class="btn btn-primary">' . $detail_rencana_aksi['title'] . '</a></li>
					<li><a href="' . $detail_iku['url'] . '" target="_blank" class="btn btn-primary">' . $detail_iku['title'] . '</a></li>
					<li><a href="' . $detail_pengukuran_kinerja['url'] . '" target="_blank" class="btn btn-primary">' . $detail_pengukuran_kinerja['title'] . '</a></li>
					<li><a href="' . $detail_laporan_kinerja['url'] . '" target="_blank" class="btn btn-primary">' . $detail_laporan_kinerja['title'] . '</a></li>
					<li><a href="' . $detail_evaluasi_internal['url'] . '" target="_blank" class="btn btn-primary">' . $detail_evaluasi_internal['title'] . '</a></li>
					<li><a href="' . $detail_dokumen_lain['url'] . '" target="_blank" class="btn btn-primary">' . $detail_dokumen_lain['title'] . '</a></li>
					<li><a href="' . $detail_perjanjian_kinerja['url'] . '" target="_blank" class="btn btn-primary">' . $detail_perjanjian_kinerja['title'] . '</a></li>
				</ul>';
		}
	}

	public function pilih_tahun_anggaran()
	{
		global $wpdb;
		$tahun_aktif = false;
		$class_hide = '';
		if (!empty($_GET) && !empty($_GET['tahun'])) {
			$tahun_aktif = $_GET['tahun'];
			$class_hide = 'display: none;';
		}
		$tahun = $wpdb->get_results('select tahun_anggaran from esakip_data_unit group by tahun_anggaran', ARRAY_A);
		echo "
		<h5 class='text-center' style='" . $class_hide . "'>PILIH TAHUN ANGGARAN</h5>
		<ul class='daftar-tahun-sakip text-center'>";
		foreach ($tahun as $k => $v) {
			$class = 'btn-primary';
			if ($tahun_aktif == $v['tahun_anggaran']) {
				$class = 'btn-success';
			}
			echo "<li><a href='?tahun=" . $v['tahun_anggaran'] . "' class='btn " . $class . "'>" . $v['tahun_anggaran'] . "</a></li>";
		}
		echo "</ul>";
	}
}
