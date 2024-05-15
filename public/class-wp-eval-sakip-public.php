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

	public function desain_lke_sakip($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-desain-lke-sakip.php';
	}

	public function pengisian_lke_sakip($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-pengisian-lke-sakip.php';
	}

	public function pengisian_lke_sakip_per_skpd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-pengisian-lke-sakip-per-skpd.php';
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

	public function get_data_penjadwalan()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'data'	=> array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				$params = $columns = $totalRecords = array();
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

				if (!empty($_POST['tahun_anggaran'])) {
					$where .= $wpdb->prepare(" AND tahun_anggaran = %d", $_POST['tahun_anggaran']);
				}

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
						$desain_lke_page = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Desain LKE SAKIP ' . $recVal['nama_jadwal'],
							'content' => '[desain_lke_sakip id_jadwal=' . $recVal['id'] . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));

						$report = '<a class="btn btn-sm btn-primary mr-2" style="text-decoration: none;" onclick="report(\'' . $recVal['id'] . '\'); return false;" href="#" title="Cetak Laporan"><i class="dashicons dashicons-printer"></i></a>';

						$edit	= '';
						$delete	= '';
						$lock	= '';
						$lke	= '';
						if ($recVal['status'] == 1) {
							$checkOpenedSchedule++;

							$lke = '<div class="btn-group mr-2" role="group">';
							$lke .= '<a class="btn btn-sm btn-info" style="text-decoration: none;" onclick="set_desain_lke(\'' . $desain_lke_page['url'] . '\'); return false;" href="#" title="Set Desain LKE"><i class="dashicons dashicons-editor-table"></i></a>';
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
							0 => '<span class="badge badge-dark"> Dihapus </span>',
							1 => '<span class="badge badge-success">Aktif</span>',
							2 => '<span class="badge badge-secondary">Dikunci</span>'
						);

						$queryRecords[$recKey]['started_at']	= date('d-m-Y H:i', strtotime($recVal['started_at']));
						$queryRecords[$recKey]['end_at']	= date('d-m-Y H:i', strtotime($recVal['end_at']));
						$queryRecords[$recKey]['aksi'] = $report . $lke . $lock . $edit . $delete;
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
					$jenis_jadwal		= trim(htmlspecialchars($_POST['jenis_jadwal']));
					$arr_jadwal = ['usulan', 'penetapan'];
					$jenis_jadwal = in_array($jenis_jadwal, $arr_jadwal) ? $jenis_jadwal : 'usulan';

					$id_jadwal_sebelumnya = $wpdb->get_var(
						$wpdb->prepare("
							SELECT MAX(id)
							FROM esakip_data_jadwal
							WHERE tipe='LKE'
							  AND tahun_anggaran=%d
						", $tahun_anggaran)
					);

					$get_jadwal = $wpdb->get_results(
						$wpdb->prepare("
							SELECT 
								* 
							FROM esakip_data_jadwal
							WHERE tipe='LKE'
							  AND tahun_anggaran=%d
							  AND status != 0
						", $tahun_anggaran),
						ARRAY_A
					);

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

					//insert data jadwal
					$data_jadwal = array(
						'nama_jadwal' => $nama_jadwal,
						'started_at' => $jadwal_mulai,
						'end_at' => $jadwal_selesai,
						'tahun_anggaran' => $tahun_anggaran,
						'status' => 1,
						'tahun_anggaran' => $tahun_anggaran,
						'jenis_jadwal' => $jenis_jadwal,
						'tipe' => 'LKE',
						'lama_pelaksanaan' => 1,
					);

					$wpdb->insert('esakip_data_jadwal', $data_jadwal);
					$id_jadwal_baru = $wpdb->insert_id;

					// Tambahkan data komponen jika ada ID jadwal sebelumnya
					if (!empty($id_jadwal_sebelumnya)) {
						$data_komponen = $wpdb->get_results(
							$wpdb->prepare("
                        		SELECT * 
								FROM esakip_komponen 
								WHERE id_jadwal = %d
                    		", $id_jadwal_sebelumnya),
							ARRAY_A
						);

						if (!empty($data_komponen)) {
							foreach ($data_komponen as $komponen) {
								$data_komponen_baru = array(
									'id_jadwal' => $id_jadwal_baru,
									'nomor_urut' => $komponen['nomor_urut'],
									'id_user_penilai' => $komponen['id_user_penilai'],
									'nama' => $komponen['nama'],
									'bobot' => $komponen['bobot'],
								);
								$wpdb->insert('esakip_komponen', $data_komponen_baru);
								$id_komponen_baru = $wpdb->insert_id;

								$data_subkomponen = $wpdb->get_results(
									$wpdb->prepare("
										SELECT * 
										FROM esakip_subkomponen 
										WHERE id_komponen = %d
									", $komponen['id']),
									ARRAY_A
								);
								foreach ($data_subkomponen as $subkomponen) {
									$data_subkomponen_baru = array(
										'id_komponen' => $id_komponen_baru,
										'nomor_urut' => $subkomponen['nomor_urut'],
										'id_user_penilai' => $subkomponen['id_user_penilai'],
										'nama' => $subkomponen['nama'],
										'bobot' => $subkomponen['bobot'],
									);
									$wpdb->insert('esakip_subkomponen', $data_subkomponen_baru);
									$id_subkomponen_baru = $wpdb->insert_id;

									$data_komponen_penilaian = $wpdb->get_results(
										$wpdb->prepare("
											SELECT * 
											FROM esakip_komponen_penilaian 
											WHERE id_subkomponen = %d
										", $subkomponen['id']),
										ARRAY_A
									);
									foreach ($data_komponen_penilaian as $penilaian) {
										$data_komponen_penilaian_baru = array(
											'id_subkomponen' => $id_subkomponen_baru,
											'nomor_urut' => $penilaian['nomor_urut'],
											'nama' => $penilaian['nama'],
											'tipe' => $penilaian['tipe'],
											'keterangan' => $penilaian['keterangan'],
										);
										$wpdb->insert('esakip_komponen_penilaian', $data_komponen_penilaian_baru);
									}
								}
							}
						}
					} else {
						// buat script dalam bentuk array nilai default design LKE
						$design = array(
							array(
								'id_jadwal' => $id_jadwal_baru,
								'nama' => 'PERENCANAAN KINERJA DEFAULT',
								'bobot' => '30',
								'nomor_urut' => '1.00',
								'id_user_penilai' => '1',
								'data' => array(
									array(
										'nama' => 'PEMENUHAN',
										'bobot' => '6',
										'nomor_urut' => '1.00',
										'id_user_penilai' => '1',
										'data' => array(
											array('nama' => 'Renstra telah disusun', 'tipe' => '1', 'keterangan' => 'Renstra OPD (2024-2026)', 'nomor_urut' => '1.00'),
											array('nama' => 'Dokumen perencanaan kinerja tahunan (Renja) telah disusun', 'tipe' => '1', 'keterangan' => 'Renja 2023 dan 2024', 'nomor_urut' => '2.00'),
											array('nama' => 'Terdapat dokumen perencanaan anggaran yang mendukung kinerja', 'tipe' => '1', 'keterangan' => '- Renja 2023 dan 2024, - DPA 2024 dan DPPA 2023 (di dokumen lainnya)', 'nomor_urut' => '3.00'),
											array('nama' => 'Perjanjian Kinerja (PK) telah disusun', 'tipe' => '1', 'keterangan' => 'PK 2023 dan 2024', 'nomor_urut' => '4.00'),
											array('nama' => 'PK telah menyajikan Indikator Tujuan/ Sasaran', 'tipe' => '2', 'keterangan' => 'PK 2023 dan 2024', 'nomor_urut' => '5.00'),
											array('nama' => 'Terdapat dokumen Rencana Aksi', 'tipe' => '1', 'keterangan' => 'Rencana Aksi 2023 dan 2024', 'nomor_urut' => '6.00')
										)
									),
									array(
										'nama' => 'KUALITAS RENSTRA',
										'bobot' => '9',
										'nomor_urut' => '2.00',
										'id_user_penilai' => '1',
										'data' => array(
											array('nama' => 'Dokumen Perencanaan Kinerja telah diformalkan.', 'tipe' => '1', 'keterangan' => 'Renstra dan Renja', 'nomor_urut' => '1.00'),
											array('nama' => 'Renstra telah dipublikasikan tepat waktu', 'tipe' => '1', 'keterangan' => 'Screenshoot Renstra 2024-2026 di website OPD, esr dan aplikasi SAKIP', 'nomor_urut' => '2.00'),
											array('nama' => 'Renja telah dipublikasikan tepat waktu', 'tipe' => '1', 'keterangan' => 'Screenshoot Renja 2023 dan 2024 di website OPD, esr dan aplikasi SAKIP', 'nomor_urut' => '3.00'),
											array('nama' => 'Perjanjian Kinerja telah dipublikasikan tepat waktu', 'tipe' => '1', 'keterangan' => 'Screenshoot PK 2023 dan 2024 di website OPD, esr dan aplikasi SAKIP', 'nomor_urut' => '4.00'),
											array('nama' => 'Tujuan telah berorientasi hasil', 'tipe' => '2', 'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024', 'nomor_urut' => '5.00'),
											array('nama' => 'Ukuran Keberhasilan (Indikator Kinerja) Tujuan telah memenuhi kriteria SMART.', 'tipe' => '2', 'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024', 'nomor_urut' => '6.00'),
											array('nama' => 'Sasaran telah jelas berorientasi hasil', 'tipe' => '2', 'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024', 'nomor_urut' => '7.00'),
											array('nama' => 'Ukuran Keberhasilan (Indikator Kinerja) Sasaran telah memenuhi kriteria SMART.', 'tipe' => '2', 'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024', 'nomor_urut' => '8.00'),
											array('nama' => 'Indikator Kinerja Tujuan telah menggambarkan kondisi Tujuan yang harus dicapai, tertuang secara berkelanjutan (sustainable - tidak sering diganti dalam 1 periode Perencanaan Strategis).', 'tipe' => '2', 'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024', 'nomor_urut' => '9.00'),
											array('nama' => 'Indikator Kinerja Sasaran telah menggambarkan kondisi Sasaran yang harus dicapai, tertuang secara berkelanjutan (sustainable - tidak sering diganti dalam 1 periode Perencanaan Strategis).', 'tipe' => '2', 'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024', 'nomor_urut' => '10.00'),
											array('nama' => 'Target yang ditetapkan dalam Perencanaan Kinerja dapat dicapai (achievable) dan realistis.', 'tipe' => '2', 'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024', 'nomor_urut' => '11.00'),
											array('nama' => 'Setiap Dokumen Perencanaan Kinerja (Renstra, Renja, PK) telah menggambarkan hubungan yang berkesinambungan, serta selaras antara Kondisi/Hasil yang akan dicapai di setiap level jabatan (Cascading Kinerja).', 'tipe' => '2', 'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024,  cascading dan pohon kinerja (di dokumen lainnya)', 'nomor_urut' => '12.00')
										)
									),
									array(
										'nama' => 'IMPLEMENTASI',
										'bobot' => '15',
										'nomor_urut' => '3.00',
										'id_user_penilai' => '1',
										'data' => array(
											array('nama' => 'Dokumen Renstra digunakan sebagai acuan penyusunan Dokumen Rencana Kerja dan Anggaran', 'tipe' => '2', 'keterangan' => '- Renstra OPD (2024-2026), Renja 2023 dan 2024, Rencana Aksi 2023 dan 2024 - DPA 2024 dan DPPA 2023 (di dokumen lainnya)', 'nomor_urut' => '1.00'),
											array('nama' => 'Target jangka menengah dalam Renstra telah dimonitor pencapaiannya sampai dengan tahun berjalan', 'tipe' => '2', 'keterangan' => 'Renstra OPD (2024-2026), Laporan Kinerja 2023', 'nomor_urut' => '2.00'),
											array('nama' => 'Anggaran yang ditetapkan telah mengacu pada Kinerja yang ingin dicapai', 'tipe' => '2', 'keterangan' => '- Renstra OPD (2024-2026), Renja 2023 dan 2024, Rencana Aksi 2023 dan 2024 - DPA 2024 dan DPPA 2023 (di dokumen lainnya)', 'nomor_urut' => '3.00'),
											array('nama' => 'Aktivitas yang dilaksanakan telah mendukung Kinerja', 'tipe' => '2', 'keterangan' => 'Rencana aksi 2023 dan 2024', 'nomor_urut' => '4.00'),
											array('nama' => 'Target kinerja yang diperjanjikan pada Perjanjian Kinerja telah digunakan untuk mengukur keberhasilan', 'tipe' => '2', 'keterangan' => '- Perjanjian Kinerja 2023 dan 2024, SKP 2023 dan 2024 - SK dan bukti pemberian reward punishment (dokumen lainnya)', 'nomor_urut' => '5.00'),
											array('nama' => 'Setiap pegawai memahami dan peduli serta berkomitmen dalam mencapai kinerja yang telah direncanakan dalam Sasaran Kinerja Pegawai (SKP)', 'tipe' => '2', 'keterangan' => 'SKP 2023 dan 2024, PK 2023 dan 2024', 'nomor_urut' => '6.00'),
											array('nama' => 'Dokumen Renstra telah direviu secara berkala', 'tipe' => '2', 'keterangan' => 'Screenshoot aplikasi e-monev E-80 dan E-81 (dokumen lainnya)', 'nomor_urut' => '7.00')
										)
									)
								),
							),
							array(
								'id_jadwal' => $id_jadwal_baru,
								'nama' => 'PENGUKURAN KINERJA',
								'bobot' => '30',
								'nomor_urut' => '2.00',
								'id_user_penilai' => '2',
								'data' => array(
									array(
										'nama' => 'PELAKSANAAN PENGUKURAN KINERJA',
										'bobot' => '6',
										'nomor_urut' => '1.00',
										'id_user_penilai' => '2',
										'data' => array(
											array('nama' => 'Telah terdapat indikator kinerja utama (IKU) sebagai ukuran kinerja secara formal', 'tipe' => '1', 'keterangan' => 'Dokumen IKU 2024-2026', 'nomor_urut' => '1.00'),
											array('nama' => 'Terdapat Definisi Operasional yang jelas atas kinerja dan cara mengukur indikator kinerja.', 'tipe' => '2', 'keterangan' => 'Dokumen IKU 2024-2026', 'nomor_urut' => '2.00'),
											array('nama' => 'Terdapat mekanisme yang jelas terhadap pengumpulan data kinerja yang dapat diandalkan.', 'tipe' => '2', 'keterangan' => 'SOP pengumpulan data kinerja (di dokumen lainnya)', 'nomor_urut' => '3.00'),
										)
									),
									array(
										'nama' => 'KUALITAS PENGUKURAN',
										'bobot' => '9',
										'nomor_urut' => '2.00',
										'id_user_penilai' => '2',
										'data' => array(
											array('nama' => 'Pimpinan selalu terlibat sebagai pengambil keputusan (Decision Maker) dalam mengukur capaian kinerja.', 'tipe' => '2', 'keterangan' => 'Dokumen pengukuran kinerja Tahun 2023 dan 2024, SKP 2023 dan 2024', 'nomor_urut' => '1.00'),
											array('nama' => 'Data kinerja yang dikumpulkan telah relevan untuk mengukur capaian kinerja yang diharapkan.', 'tipe' => '2', 'keterangan' => 'Dokumen pengukuran kinerja Tahun 2023 dan 2024, SKP 2023 dan 2024,  Laporan Kinerja 2023', 'nomor_urut' => '2.00'),
											array('nama' => 'Data kinerja yang dikumpulkan telah mendukung capaian kinerja yang diharapkan.', 'tipe' => '2', 'keterangan' => 'Dokumen pengukuran kinerja Tahun 2023 dan 2024, SKP 2023 dan 2024,  Laporan Kinerja 2023', 'nomor_urut' => '3.00'),
											array('nama' => 'Pengumpulan data kinerja atas Rencana Aksi dilakukan secara berkala (bulanan/triwulanan/semester)', 'tipe' => '1', 'keterangan' => 'Pengukuran rencana aksi Tahun 2023 dan 2024', 'nomor_urut' => '4.00'),
											array('nama' => 'Pengukuran kinerja sudah dilakukan secara berjenjang', 'tipe' => '2', 'keterangan' => 'Dokumen pengukuran kinerja Tahun 2023 dan 2024, SKP 2023 dan 2024', 'nomor_urut' => '5.00'),
											array('nama' => 'Pengumpulan data kinerja telah memanfaatkan Teknologi Informasi (Aplikasi).', 'tipe' => '1', 'keterangan' => 'Screenshoot aplikasi EP3, E-monev (dokumen lainnya)', 'nomor_urut' => '6.00'),
											array('nama' => 'Pengukuran capaian kinerja telah memanfaatkan Teknologi Informasi (Aplikasi).', 'tipe' => '1', 'keterangan' => 'Screenshoot aplikasi EP3, E-monev (dokumen lainnya)', 'nomor_urut' => '7.00'),
										)
									),
									array(
										'nama' => 'IMPLEMENTASI PENGUKURAN',
										'bobot' => '15',
										'nomor_urut' => '3.00',
										'id_user_penilai' => '2',
										'data' => array(
											array('nama' => 'Pengukuran Kinerja telah menjadi dasar dalam penyesuaian (pemberian/pengurangan) tunjangan kinerja/penghasilan.', 'tipe' => '2', 'keterangan' => '- Pengukuran Kinerja 2023 dan 2024,  - Screenshoot EP3 (dokumen lainnya)', 'nomor_urut' => '1.00'),
											array('nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian (Refocusing) Organisasi.', 'tipe' => '2', 'keterangan' => '- Pengukuran Kinerja 2023 dan 2024 - Screenshoot EP3 (dokumen lainnya)', 'nomor_urut' => '2.00'),
											array('nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Strategi dalam mencapai kinerja.', 'tipe' => '2', 'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; Rencana Aksi 2024', 'nomor_urut' => '3.00'),
											array('nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Kebijakan dalam mencapai kinerja.', 'tipe' => '2', 'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; Rencana Aksi 2024', 'nomor_urut' => '4.00'),
											array('nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Aktivitas dalam mencapai kinerja.', 'tipe' => '2', 'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; Rencana Aksi 2024', 'nomor_urut' => '5.00'),
											array('nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Anggaran dalam mencapai kinerja.', 'tipe' => '2', 'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; DPA 2024 dan DPPA 2023', 'nomor_urut' => '6.00'),
											array('nama' => 'Terdapat efisiensi atas penggunaan anggaran dalam mencapai kinerja.', 'tipe' => '2', 'keterangan' => '- Laporan Kinerja 2023;  - DPPA 2023 (di dokumen lainnya)', 'nomor_urut' => '7.00'),
											array('nama' => 'Setiap unit/satuan kerja memahami dan peduli atas hasil pengukuran kinerja.', 'tipe' => '2', 'keterangan' => NULL, 'nomor_urut' => '8.00'),
											array('nama' => 'Setiap pegawai memahami dan peduli atas hasil pengukuran kinerja.', 'tipe' => '2', 'keterangan' => NULL, 'nomor_urut' => '9.00'),
										)
									)
								)
							),
							array(
								'id_jadwal' => $id_jadwal_baru,
								'nama' => 'PELAPORAN KINERJA',
								'bobot' => '15',
								'nomor_urut' => '3.00',
								'id_user_penilai' => '3',
								'data' => array(
									array(
										'nama' => 'PEMENUHAN PELAPORAN',
										'bobot' => '3',
										'nomor_urut' => '1.00',
										'id_user_penilai' => '3',
										'data' => array(
											array('nama' => 'Dokumen Laporan Kinerja telah disusun.', 'tipe' => '1', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '1.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah disusun secara berkala.', 'tipe' => '1', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '2.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah diformalkan.', 'tipe' => '1', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '3.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah direviu.', 'tipe' => '1', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '4.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah dipublikasikan.', 'tipe' => '1', 'keterangan' => 'Screenshoot Laporan Kinerja 2023 pada website, esr, aplikasi sakip kab madiun (dokumen lainnya)', 'nomor_urut' => '5.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah disampaikan tepat waktu.', 'tipe' => '1', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '6.00'),
										)

									),
									array(
										'nama' => 'PENYAJIAN INFORMASI KINERJA',
										'bobot' => '4.5',
										'nomor_urut' => '2.00',
										'id_user_penilai' => '3',
										'data' => array(
											array('nama' => 'Dokumen Laporan Kinerja disusun secara berkualitas sesuai dengan standar.', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '1.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah mengungkap seluruh informasi tentang pencapaian kinerja.', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '2.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah menginfokan perbandingan realisasi kinerja dengan target tahunan.', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '3.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah menginfokan perbandingan realisasi kinerja dengan target jangka menengah.', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '4.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah menginfokan perbandingan realisasi kinerja dengan realisasi kinerja tahun-tahun sebelumnya.', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '5.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah menyajikan informasi keuangan yang terkait dengan pencapaian sasaran kinerja instansi.', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '6.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah menginfokan kualitas atas capaian kinerja beserta upaya nyata dan/atau hambatannya.', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '7.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah menginfokan efisiensi atas penggunaan sumber daya dalam mencapai kinerja.', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '8.00'),
											array('nama' => 'Dokumen Laporan Kinerja telah menginfokan upaya perbaikan dan penyempurnaan kinerja ke depan (Rekomendasi perbaikan kinerja).', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '9.00'),
										)

									),
									array(
										'nama' => 'PEMANFAATAN INFORMASI KINERJA',
										'bobot' => '7.5',
										'nomor_urut' => '3.00',
										'id_user_penilai' => '3',
										'data' => array(
											array('nama' => 'Informasi dalam laporan kinerja selalu menjadi perhatian utama pimpinan (Bertanggung Jawab).', 'tipe' => '1', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '1.00'),
											array('nama' => 'Penyajian informasi dalam laporan kinerja menjadi kepedulian seluruh pegawai.', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '2.00'),
											array('nama' => 'Informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian aktivitas untuk mencapai kinerja.', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023', 'nomor_urut' => '3.00'),
											array('nama' => 'Informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian penggunaan anggaran untuk mencapai kinerja.', 'tipe' => '2', 'keterangan' => '- Laporan Kinerja 2023,  - DPA 2024 (dokumen lainnya)', 'nomor_urut' => '4.00'),
											array('nama' => 'Informasi dalam laporan kinerja telah digunakan dalam evaluasi pencapaian keberhasilan kinerja.', 'tipe' => '2', 'keterangan' => 'Laporan Kinerja 2023, Evaluasi Internal 2023', 'nomor_urut' => '5.00'),
											array('nama' => 'Informasi dalam laporan kinerja telah digunakan dalam penyesuaian perencanaan kinerja yang akan dihadapi berikutnya.', 'tipe' => '2', 'keterangan' => '- Laporan Kinerja 2023, rencana aksi 2024,  - DPA 2024 (dokumen lainnya)', 'nomor_urut' => '6.00'),
											array('nama' => 'Informasi dalam laporan kinerja selalu mempengaruhi perubahan budaya kinerja organisasi.', 'tipe' => '2', 'keterangan' => '- Laporan Kinerja 2023, rencana aksi 2024, evaluasi internal 2024 dan 2023 - DPA 2024 (dokumen lainnya)', 'nomor_urut' => '7.00'),
										)

									),
								)
							),
							array(
								'id_jadwal' => $id_jadwal_baru,
								'nama' => 'EVALUASI AKUNTABILITAS KINERJA INTERNAL',
								'bobot' => '25',
								'nomor_urut' => '4.00',
								'id_user_penilai' => '1',
								'data' => array(
									array(
										'nama' => 'PELAKSANAAN EVALUASI AKUNTABILITAS KINERJA',
										'bobot' => '5',
										'nomor_urut' => '1.00',
										'id_user_penilai' => '1',
										'data' => array(
											array('nama' => 'Telah dilaksanakan Evaluasi Akuntabilitas Kinerja secara berkala.', 'tipe' => '1', 'keterangan' => 'Evaluasi Internal 2023 dan 2024', 'nomor_urut' => '1.00'),
										)
									),
									array(
										'nama' => 'KUALITAS EVALUASI',
										'bobot' => '7.5',
										'nomor_urut' => '2.00',
										'id_user_penilai' => '1',
										'data' => array(
											array('nama' => 'Evaluasi Akuntabilitas Kinerja telah dilaksanakan secara berjenjang.', 'tipe' => '2', 'keterangan' => 'Evaluasi Internal 2023 dan 2024', 'nomor_urut' => '1.00'),
											array('nama' => 'Evaluasi Akuntabilitas Kinerja telah dilaksanakan dengan pendalaman yang memadai.', 'tipe' => '2', 'keterangan' => 'Evaluasi Internal 2023 dan 2024', 'nomor_urut' => '2.00'),
											array('nama' => 'Evaluasi Akuntabilitas Kinerja telah dilaksanakan pada seluruh bidang di OPD.', 'tipe' => '2', 'keterangan' => 'Evaluasi Internal 2023 dan 2024', 'nomor_urut' => '3.00'),
											array('nama' => 'Evaluasi Akuntabilitas Kinerja telah dilaksanakan menggunakan Teknologi Informasi (Aplikasi).', 'tipe' => '2', 'keterangan' => 'Evaluasi Internal 2023 dan 2024', 'nomor_urut' => '4.00'),
										)
									),
									array(
										'nama' => 'PEMANFAATAN EVALUASI',
										'bobot' => '12.5',
										'nomor_urut' => '3.00',
										'id_user_penilai' => '1',
										'data' => array(
											array('nama' => 'Seluruh rekomendasi atas hasil evaluasi akuntabilitas kinerja (internal dan LHE SAKIP OPD) telah ditindaklanjuti.', 'tipe' => '2', 'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023', 'nomor_urut' => '1.00'),
											array('nama' => 'Telah terjadi peningkatan implementasi SAKIP  (internal dan LHE SAKIP OPD) dengan melaksanakan tindak lanjut atas rekomendasi hasil evaluasi akuntabilitas kinerja.', 'tipe' => '2', 'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024', 'nomor_urut' => '2.00'),
											array('nama' => 'Hasil Evaluasi Akuntabilitas Kinerja  (internal dan LHE SAKIP OPD) telah dimanfaatkan untuk perbaikan dan peningkatan akuntabilitas kinerja.', 'tipe' => '2', 'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024', 'nomor_urut' => '3.00'),
											array('nama' => 'Hasil dari Evaluasi Akuntabilitas Kinerja  (internal dan LHE SAKIP OPD)telah dimanfaatkan dalam mendukung efektivitas dan efisiensi kinerja.', 'tipe' => '2', 'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024, DPA 2024', 'nomor_urut' => '4.00'),
											array('nama' => 'Telah terjadi perbaikan dan peningkatan kinerja dengan memanfaatkan hasil evaluasi akuntabilitas kinerja  (internal dan LHE SAKIP OPD).', 'tipe' => '2', 'keterangan' => '- Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024, DPA 2024 - Dokumen lainnya (Inovasi, Prestasi)', 'nomor_urut' => '5.00')
										)
									)
								)
							)
						);

						foreach ($design as $komponen) {
							$komponen_baru = array(
								'id_jadwal' => $id_jadwal_baru,
								'nomor_urut' => $komponen['nomor_urut'],
								'id_user_penilai' => $komponen['id_user_penilai'],
								'nama' => $komponen['nama'],
								'bobot' => $komponen['bobot'],
							);
							$wpdb->insert('esakip_komponen', $komponen_baru);
							$id_komponen_baru = $wpdb->insert_id;

							foreach ($komponen['data'] as $subkomponen) {
								$subkomponen_baru = array(
									'id_komponen' => $id_komponen_baru,
									'nomor_urut' => $subkomponen['nomor_urut'],
									'id_user_penilai' => $subkomponen['id_user_penilai'],
									'nama' => $subkomponen['nama'],
									'bobot' => $subkomponen['bobot'],
								);
								$wpdb->insert('esakip_subkomponen', $subkomponen_baru);
								$id_subkomponen_baru = $wpdb->insert_id;

								foreach ($subkomponen['data'] as $penilaian) {
									$komponen_penilaian_baru = array(
										'id_subkomponen' => $id_subkomponen_baru,
										'nomor_urut' => $penilaian['nomor_urut'],
										'nama' => $penilaian['nama'],
										'tipe' => $penilaian['tipe'],
										'keterangan' => $penilaian['keterangan'],
									);
									$wpdb->insert('esakip_komponen_penilaian', $komponen_penilaian_baru);
								}
							}
						}
					}

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

	public function delete_data_lokal_history($nama_tabel = 'esakip_pengisian_lke', $id_jadwal = 1)
	{
		global $wpdb;
		$return = array(
			'status' => 'error',
			'message'	=> 'Format tidak sesuai!'
		);

		$nama_tabel_history = $nama_tabel . "_history";

		$delete = $wpdb->delete($nama_tabel_history, array('id_jadwal' => $id_jadwal));
		if ($delete == false) {
			$return = array(
				'status' 	=> 'error',
				'message'	=> 'Delete error, harap hubungi admin!'
			);
		}

		return $return;
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

							$delete_lokal_history = $this->delete_data_lokal_history('esakip_pengisian_lke', $data_this_id['id']);

							$columns_1 = array(
								'id_user',
								'id_skpd',
								'id_user_penilai',
								'id_komponen',
								'id_subkomponen',
								'id_komponen_penilaian',
								'nilai_usulan',
								'nilai_penetapan',
								'keterangan',
								'keterangan_penilai',
								'bukti_dukung',
								'create_at',
								'update_at'
							);


							$sql_backup_esakip_pengisian_lke =  "
								INSERT INTO esakip_pengisian_lke" . $prefix . "_history (" . implode(', ', $columns_1) . ",id_asli,id_jadwal)
								SELECT " . implode(', ', $columns_1) . ", " . $data_this_id['id'] . "," . $id . "
											FROM esakip_pengisian_lke";

							$queryRecords1 = $wpdb->query($sql_backup_esakip_pengisian_lke);

							$return = array(
								'status' => 'success',
								'message'	=> 'Berhasil!',
								'data_input' => $queryRecords1,
								'sql' => $wpdb->last_query
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

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
						$detail_dokumen_lainnya = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen dokumen_lainnya ' . $tahun_anggaran,
							'content' => '[dokumen_detail_dokumen_lainnya tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
						$detail_perjanjian_kinerja = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Perjanjian Kinerja ' . $tahun_anggaran,
							'content' => '[dokumen_detail_perjanjian_kinerja tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
						$detail_rencana_aksi = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Rencana Aksi ' . $tahun_anggaran,
							'content' => '[dokumen_detail_rencana_aksi tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
						$detail_iku = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Rencana Aksi ' . $tahun_anggaran,
							'content' => '[dokumen_detail_iku tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
						$detail_evaluasi_internal = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Evaluasi Internal ' . $tahun_anggaran,
							'content' => '[dokumen_detail_evaluasi_internal tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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
				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
						$detail_pengukuran_kinerja = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Pengukuran Kinerja ' . $tahun_anggaran,
							'content' => '[dokumen_detail_pengukuran_kinerja tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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
				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
						$detail_pengukuran_rencana_aksi = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Rencana Aksi ' . $tahun_anggaran,
							'content' => '[dokumen_detail_pengukuran_rencana_aksi tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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
				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
						$detail_laporan_kinerja = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Laporan Kinerja ' . $tahun_anggaran,
							'content' => '[dokumen_detail_laporan_kinerja tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
						$detail_renja = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen RENJA/RKT ' . $tahun_anggaran,
							'content' => '[dokumen_detail_renja_rkt tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

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

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
						$detail_skp = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen SKP ' . $tahun_anggaran,
							'content' => '[dokumen_detail_skp tahun=' . $tahun_anggaran . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
					", $tahun_anggaran_sakip),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_renstra = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen RENSTRA ' . $id_jadwal,
							'content' => '[upload_dokumen_renstra periode=' . $id_jadwal . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

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
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_renstra['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
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
				}
				if (!empty($_POST['tahun_anggaran'])) {
					$tahun_anggaran = $_POST['tahun_anggaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
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

				$jadwal = $wpdb->get_results(
					$wpdb->prepare("
					SELECT 
						*
					FROM esakip_data_jadwal
					WHERE id=%d
					  AND status != 0
					", $id_jadwal),
					ARRAY_A
				);

				if (!empty($unit)) {
					$tbody = '';
					$counter = 1;
					foreach ($unit as $kk => $vv) {
						$detail_pengisian_lke = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Pengisian LKE ' . $vv['nama_skpd'] . ' ' . $jadwal['nama_jadwal'],
							'content' => '[pengisian_lke_sakip_per_skpd id_jadwal=' . $id_jadwal . ']',
							'show_header' => 1,
							'post_status' => 'private'
						));

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$nilai_usulan = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(nilai_usulan)
								FROM esakip_pengisian_lke
								WHERE id_skpd = %d
								  AND active = 1
								",
								$vv['id_skpd']
							)
						);
						$nilai_penetapan = $wpdb->get_var(
							$wpdb->prepare(
								"
								SELECT 
									COUNT(nilai_penetapan)
								FROM esakip_pengisian_lke
								WHERE id_skpd = %d
								  AND active = 1
								",
								$vv['id_skpd']
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_pengisian_lke['url'] . '&id_skpd=' . $vv['id_skpd']. '&id_jadwal=' . $id_jadwal . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $nilai_usulan . "</td>";
						$tbody .= "<td class='text-center'>" . $nilai_penetapan . "</td>";
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
				if (!empty($_POST['id_jadwal'])) {
					$id_jadwal = $_POST['id_jadwal'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
				}
				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id SKPD kosong!';
				}
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
				$current_user = wp_get_current_user();
				$admin_roles = array(
					'1' => 'administrator',
					'2' => 'admin_bappeda',
					'3' => 'admin_ortala'
				);

				$intersected_roles = array_intersect($admin_roles, $current_user->roles);
	
				$user_penilai = $this->get_user_penilai();
				$user_penilai[''] = '-';

				$tbody = '';
				if (!empty($data_komponen)) {
					$can_verify = false;
					//jika user adalah admin atau skpd
					if (
						in_array("admin_ortala", $current_user->roles) ||
						in_array("admin_bappeda", $current_user->roles) ||
						in_array("administrator", $current_user->roles)
					) {
						$can_verify = true;
					}

					$counter = 'A';
					foreach ($data_komponen as $komponen) {
						//jika user penilai komponen sudah diset
						$disabled = 'disabled';
						if (
							array_key_exists($komponen['id_user_penilai'], $intersected_roles) ||
							empty($komponen['id_user_penilai'])
						) {
							$disabled = '';
						}

						$sum_nilai_sub = 0;
						$sum_nilai_sub_penetapan = 0;
						if ($sum_nilai_sub > 0) {
							$persentase_kom = $sum_nilai_sub / $komponen['bobot'];
						}
						if ($sum_nilai_sub_penetapan > 0) {
							$persentase_kom_penetapan = $sum_nilai_sub_penetapan / $komponen['bobot'];
						}

						//tbody komponen
						$counter_isi = 1;
						$counter_sub = 'a';
						$tbody .= "<tr>";
						$tbody .= "<td class='text-left'>" . $counter++ . "</td>";
						$tbody .= "<td class='text-left' colspan='3'><b>" . $komponen['nama'] . "</b></td>";
						$tbody .= "<td class='text-center'>" . $komponen['bobot'] . "</td>";
						$tbody .= "<td class='text-left'></td>";
						$tbody .= "<td class='text-center'>" . number_format($sum_nilai_sub, 2) . "</td>";
						$tbody .= "<td class='text-center'>" . number_format($persentase_kom * 100, 2) . "%" . "</td>";
						$tbody .= "<td class='text-center'></td>";
						$tbody .= "<td class='text-center'></td>";
						$tbody .= "<td class='text-center'></td>";
						$tbody .= "<td class='text-center'>" . number_format($sum_nilai_sub_penetapan, 2) .  "</td>";
						$tbody .= "<td class='text-center'>" . number_format($persentase_kom_penetapan * 100, 2) . "%" . "</td>";
						$tbody .= "<td class='text-left' colspan='2'>User Penilai: <b>" . $user_penilai[$komponen['id_user_penilai']] . "</b></td>";
						$tbody .= "</tr>";

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
								$sum_nilai_usulan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT SUM(nilai_usulan)
										FROM esakip_pengisian_lke
										WHERE id_subkomponen = %d
										  AND id_skpd = %d
									", $subkomponen['id'], $id_skpd)
								);
								$count_nilai_usulan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT COUNT(id)
										FROM esakip_pengisian_lke
										WHERE id_subkomponen = %d
										  AND id_skpd = %d
									", $subkomponen['id'], $id_skpd)
								);
								$sum_nilai_penetapan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT SUM(nilai_penetapan)
										FROM esakip_pengisian_lke
										WHERE id_subkomponen = %d
										  AND id_skpd = %d
									", $subkomponen['id'], $id_skpd)
								);
								$count_nilai_penetapan = $wpdb->get_var(
									$wpdb->prepare("
										SELECT COUNT(id)
										FROM esakip_pengisian_lke
										WHERE id_subkomponen = %d
										  AND id_skpd = %d
									", $subkomponen['id'], $id_skpd)
								);

								if ($count_nilai_usulan > 0) {
									$persentase = $sum_nilai_usulan / $count_nilai_usulan;
									$total_nilai_sub = $persentase * $subkomponen['bobot'];
									$sum_nilai_sub += $total_nilai_sub;
								}

								if ($count_nilai_penetapan > 0) {
									$persentase_penetapan = $sum_nilai_penetapan / $count_nilai_penetapan;
									$total_nilai_sub_penetapan = $persentase_penetapan * $subkomponen['bobot'];
									$sum_nilai_sub_penetapan += $total_nilai_sub_penetapan;
								}

								//tbody subkomponen
								$tbody .= "<tr>";
								$tbody .= "<td class='text-left'></td>";
								$tbody .= "<td class='text-left'>" . $counter_sub++ . "</td>";
								$tbody .= "<td class='text-left' colspan='2'><b>" . $subkomponen['nama'] . "</b></td>";
								$tbody .= "<td class='text-center'>" . $subkomponen['bobot'] . "</td>";
								$tbody .= "<td class='text-left'></td>";
								$tbody .= "<td class='text-center'>" . number_format($total_nilai_sub, 2) . "</td>";
								$tbody .= "<td class='text-center'>" . number_format($persentase * 100, 2) . "%" . "</td>";
								$tbody .= "<td class='text-center'></td>";
								$tbody .= "<td class='text-center'></td>";
								$tbody .= "<td class='text-center'></td>";
								$tbody .= "<td class='text-center'>" . number_format($total_nilai_sub_penetapan, 2) . "</td>";
								$tbody .= "<td class='text-center'>" . number_format($persentase_penetapan * 100, 2) . "%" . "</td>";
								$tbody .= "<td class='text-left' colspan='2'>User Penilai: <b>" . $user_penilai[$subkomponen['id_user_penilai']] . "</b></td>";
								$tbody .= "</tr>";


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
										WHERE kp.id_subkomponen = %d
										  AND kp.active = 1
										ORDER BY kp.nomor_urut ASC
									",  $id_skpd, $subkomponen['id']),
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
										//nilai usulan
										if (isset($penilaian['pl_nilai_usulan'])) {
											$nilai_usulan = $penilaian['pl_nilai_usulan'];
										} else {
											$nilai_usulan = "0.00";
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

										//nilai penetapan
										if (isset($penilaian['pl_nilai_penetapan'])) {
											$nilai_penetapan = $penilaian['pl_nilai_penetapan'];
										} else {
											$nilai_penetapan = "0.00";
										}

										//tbody isi
										$tbody .= "<tr>";
										$tbody .= "<td class='text-left'></td>";
										$tbody .= "<td class='text-left'></td>";
										$tbody .= "<td class='text-left'>" . $counter_isi++ . "</td>";
										$tbody .= "<td class='text-left'>" . $penilaian['kp_nama'] . "<br><small class='text-muted'>" . $penilaian['kp_keterangan'] . "</small></td>";
										$tbody .= "<td class='text-center'>-</td>";
										switch ($can_verify) {
											case false:
												$btn_save = "<button class='btn btn-primary' onclick='simpanPerubahan(" . $penilaian['kp_id'] . ")' title='Simpan Perubahan'><span class='dashicons dashicons-saved' ></span></button>";

												$tbody .= "<td class='text-center'><select id='opsiUsulan" . $penilaian['kp_id'] . "'>" . $opsi . "</select></td>";
												$tbody .= "<td class='text-center'>" . $nilai_usulan . "</td>";
												$tbody .= "<td class='text-center'></td>";
												$tbody .= "<td class='text-center'><input type='text' id='buktiDukung" . $penilaian['kp_id'] . "' value='" . $penilaian['pl_bukti_dukung'] . "'></input></td>";
												$tbody .= "<td class='text-center'><textarea id='keteranganUsulan" . $penilaian['kp_id'] . "'>" . $penilaian['pl_keterangan'] . "</textarea></td>";
												$tbody .= "<td class='text-center'><select id='opsiPenetapan" . $penilaian['kp_id'] . "' disabled>" . $opsi_penetapan . "</select></td>";
												$tbody .= "<td class='text-center'>" . $nilai_penetapan . "</td>";
												$tbody .= "<td class='text-center'></td>";
												$tbody .= "<td class='text-center'><textarea id='keteranganPenetapan" . $penilaian['kp_id'] . "' disabled>" . $penilaian['pl_keterangan_penilai'] . "</textarea></td>";
												$tbody .= "<td class='text-center'>" . $btn_save . "</td>";
												break;
											case true:
												$btn_save_penetapan = "<button class='btn btn-info' onclick='simpanPerubahanPenetapan(" . $penilaian['kp_id'] . ")' title='Simpan Perubahan Penetapan'><span class='dashicons dashicons-saved' ></span></button>";

												$tbody .= "<td class='text-center'><select id='opsiUsulan" . $penilaian['kp_id'] . "' disabled>" . $opsi . "</select></td>";
												$tbody .= "<td class='text-center'>" . $nilai_usulan . "</td>";
												$tbody .= "<td class='text-center'></td>";
												$tbody .= "<td class='text-center'><input type='text' id='buktiDukung" . $penilaian['kp_id'] . "' value='" . $penilaian['pl_bukti_dukung'] . "' disabled></input></td>";
												$tbody .= "<td class='text-center'><textarea id='keteranganUsulan" . $penilaian['kp_id'] . "' disabled>" . $penilaian['pl_keterangan'] . "</textarea></td>";
												$tbody .= "<td class='text-center'><select id='opsiPenetapan" . $penilaian['kp_id'] . "' ". $disabled .">" . $opsi_penetapan . "</select></td>";
												$tbody .= "<td class='text-center'>" . $nilai_penetapan . "</td>";
												$tbody .= "<td class='text-center'></td>";
												$tbody .= "<td class='text-center'><textarea id='keteranganPenetapan" . $penilaian['kp_id'] . "'". $disabled .">" . $penilaian['pl_keterangan_penilai'] . "</textarea></td>";
												if (!$disabled) {
													$tbody .= "<td class='text-center'>" . $btn_save_penetapan . "</td>";
												} else {
													$tbody .= "<td class='text-center'></td>";
												}
												break;
										}
										$tbody .= "</tr>";
									}
								}
							}
						}
					}
				} else {
					$tbody = "<tr><td colspan='4' class='text-center'>Tidak ada data tersedia</td></tr>";
				}

				$ret['data'] = $tbody;
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

	public function get_user_penilai()
	{
		return array(
			'1' => 'Admin Inspektorat',
			'2' => 'Admin Perencanaan',
			'3' => 'Admin Ortala'
		);
	}

	public function get_table_desain_lke()
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
				}
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

				$total_bobot_komponen = $wpdb->get_var(
					$wpdb->prepare("
						SELECT SUM(bobot)
						FROM esakip_komponen
						WHERE id_jadwal = %d
						AND active = 1
					", $id_jadwal)
				);
 
				$user_penilai = $this->get_user_penilai();
				$user_penilai[''] = '-';
				$tbody = '';
				if (!empty($data_komponen)) {
					$counter = 'A';
					foreach ($data_komponen as $komponen) {
						$btn = '';
						$counter_isi = 1;
						$counter_sub = 'a';
						$bobot_komponen = $komponen['bobot'];
						$total_bobot_subkomponen = $wpdb->get_var(
							$wpdb->prepare("
								SELECT SUM(bobot)
								FROM esakip_subkomponen
								WHERE id_komponen = %d
								AND active = 1
							", $komponen['id'])
						);

						$btn .= '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-warning' onclick='edit_data_komponen(\"" . $komponen['id'] . "\");' title='Edit Data Komponen'><span class='dashicons dashicons-edit'></span></button>";
						if ($total_bobot_subkomponen < $bobot_komponen) {
							$btn .= "<button class='btn btn-primary' onclick='tambah_subkomponen(\"" . $komponen['id'] . "\");' title='Tambah Subkomponen'><span class='dashicons dashicons-plus'></span></button>";
						}
						$btn .= "<button class='btn btn-danger' onclick='hapus_data_komponen(\"" . $komponen['id'] . "\");' title='Hapus Data Komponen'><span class='dashicons dashicons-trash'></span>";
						$btn .= '</div>';

						$tbody .= "<tr>";
						$tbody .= "<td class='text-left'><b>" . $counter++ . "</b></td>";
						$tbody .= "<td class='text-left' colspan='3'><b>" . $komponen['nama'] . "</b></td>";
						$tbody .= "<td class='text-center'>" . $komponen['bobot'] . "</td>";
						$tbody .= "<td class='text-left'></td>";
						$tbody .= "<td class='text-left'>User Penilai: <b>" . $user_penilai[$komponen['id_user_penilai']] . "</b></td>";
						$tbody .= "<td class='text-center'>" . $btn . "</td>";
						$tbody .= "</tr>";

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
								$btn = '';

								$btn .= '<div class="btn-action-group">';
								$btn .= "<button class='btn btn-warning' onclick='edit_data_subkomponen(\"" . $subkomponen['id'] . "\");' title='Edit Data Subkomponen'><span class='dashicons dashicons-edit'></span></button>";
								$btn .= "<button class='btn btn-primary' onclick='tambah_komponen_penilaian(\"" . $subkomponen['id'] . "\");' title='Tambah Komponen Penilaian'><span class='dashicons dashicons-plus'></span></button>";
								$btn .= "<button class='btn btn-danger' onclick='hapus_data_subkomponen(\"" . $subkomponen['id'] . "\");' title='Hapus Data Subkomponen'><span class='dashicons dashicons-trash'></span>";
								$btn .= '</div>';


								$tbody .= "<tr>";
								$tbody .= "<td class='text-left'></td>";
								$tbody .= "<td class='text-left'><b>" . $counter_sub++ . "</b></td>";
								$tbody .= "<td class='text-left' colspan='2'><b>" . $subkomponen['nama'] . "</b></td>";
								$tbody .= "<td class='text-center'>" . $subkomponen['bobot'] . "</td>";
								$tbody .= "<td class='text-left'></td>";
								$tbody .= "<td class='text-left'>User Penilai: <b>" . $user_penilai[$subkomponen['id_user_penilai']] . "</b></td>";
								$tbody .= "<td class='text-center'>" . $btn . "</td>";
								$tbody .= "</tr>";

								$data_komponen_penilaian = $wpdb->get_results(
									$wpdb->prepare("
										SELECT * 
										FROM esakip_komponen_penilaian
										WHERE id_subkomponen = %d 
										  AND active = 1
										ORDER BY nomor_urut ASC
									", $subkomponen['id']),
									ARRAY_A
								);

								if (!empty($data_komponen_penilaian)) {
									foreach ($data_komponen_penilaian as $penilaian) {
										$btn = '';

										$btn .= '<div class="btn-action-group">';
										$btn .= "<button class='btn btn-warning' onclick='edit_data_komponen_penilaian(\"" . $penilaian['id'] . "\");' title='Edit Data'><span class='dashicons dashicons-edit'></span></button>";
										$btn .= "<button class='btn btn-danger' onclick='hapus_data_komponen_penilaian(\"" . $penilaian['id'] . "\");' title='Hapus Data'><span class='dashicons dashicons-trash'></span>";
										$btn .= '</div>';

										$tbody .= "<tr>";
										$tbody .= "<td class='text-left'></td>";
										$tbody .= "<td class='text-left'></td>";
										$tbody .= "<td class='text-left'>" . $counter_isi++ . "</td>";
										$tbody .= "<td class='text-left'>" . $penilaian['nama'] . "</td>";
										$tbody .= "<td class='text-center'></td>";
										if ($penilaian['tipe'] == 1) {
											$tbody .= "<td class='text-center'>Y/T</td>";
										} else if ($penilaian['tipe'] == 2) {
											$tbody .= "<td class='text-center'>A/B/C/D/E</td>";
										}
										$tbody .= "<td class='text-left'>" . $penilaian['keterangan'] . "</td>";
										$tbody .= "<td class='text-center'>" . $btn . "</td>";
										$tbody .= "</tr>";
									}
								}
							}
						}
					}
				} else {
					$tbody = "<tr><td colspan='8' class='text-center'>Tidak ada data tersedia</td></tr>";
				}

				if ($total_bobot_komponen != 100) {
					$tbody .= "<tr>";
					$tbody .= "<td colspan='8' class='text-center'>";
					$tbody .= "<button class='btn btn-primary btn-lg btn-block' onclick='tambah_komponen_utama(\"" . $id_jadwal . "\")'><span class='dashicons dashicons-plus'></span> Tambah Komponen Utama</button>";
					$tbody .= "</td>";
					$tbody .= "</tr>";
				}

				$ret['data'] = $tbody;
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
												<th class="text-center">Waktu Upload</th>
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

						$tbodyUnset .= "<td class='text-center'>" . $vv['tanggal_upload'] . "</td>";
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
		$periode_renstra_skpd = '';
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

			$renstra_skpd = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen RENSTRA ' . $jadwal_periode_item['id'],
				'content' => '[upload_dokumen_renstra periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_renstra = 'Halaman Detail Dokumen RENSTRA ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
			$renstra_skpd['url'] .= '&id_skpd=ganti';
			$periode_renstra_skpd .= '<li><a target="_blank" href="' . $renstra_skpd['url'] . '" class="btn btn-primary">' . $title_renstra . '</a></li>';
		}


		$get_jadwal_lke = $wpdb->get_results(
			"
			SELECT 
				id,
				nama_jadwal,
				tahun_anggaran,
				lama_pelaksanaan
			FROM esakip_data_jadwal
			WHERE tipe = 'LKE'
			  AND status = 1
			ORDER BY started_at DESC LIMIT 1",
			ARRAY_A
		);
		$pengisian_lke = '';
		$pengisian_lke_per_skpd_page = '';
		foreach ($get_jadwal_lke as $get_jadwal_lke_sakip) {
			$tahun_anggaran_selesai = $get_jadwal_lke_sakip['tahun_anggaran'] + $get_jadwal_lke_sakip['lama_pelaksanaan'];

			$lke = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Pengisian LKE ' . $get_jadwal_lke_sakip['nama_jadwal'].' '.$get_jadwal_lke_sakip['tahun_anggaran'],
				'content' => '[pengisian_lke_sakip id_jadwal=' . $get_jadwal_lke_sakip['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$lke['url'] .= '&id_jadwal=' . $get_jadwal_lke_sakip['id'];
			$pengisian_lke .= '<li><a target="_blank" href="' . $lke['url'] . '" class="btn btn-primary">' . $lke['title'] . '</a></li>';
		}

		if (empty($pengisian_lke)) {
			$pengisian_lke = '<li><a return="false" href="#" class="btn btn-secondary">Pengisian LKE kosong atau belum dibuat</a></li>';
		}
		if (empty($periode_rpjmd)) {
			$periode_rpjmd = '<li><a return="false" href="#" class="btn btn-secondary">Periode RPJMD kosong atau belum dibuat</a></li>';
		}

		if (empty($periode_renstra)) {
			$periode_renstra = '<li><a return="false" href="#" class="btn btn-secondary">Periode RPJMD kosong atau belum dibuat</a></li>';
		}

		if (empty($periode_renstra_skpd)) {
			$periode_renstra = '<li><a return="false" href="#" class="btn btn-secondary">Periode RPJMD kosong atau belum dibuat</a></li>';
		}
		$halaman_lke = '
			<div class="accordion">
				<h5 class="esakip-header-tahun" data-id="lke" style="margin: 0;">Halaman Pengisian LKE</h5>
				<div class="esakip-body-tahun" data-id="lke">
					<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
						' . $pengisian_lke . '
					</ul>
				</div>
			</div>';
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
				'nama_page' => 'Halaman Dokumen Pemda Lainnya Tahun ' . $_GET['tahun'],
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
				<ul class="daftar-menu-sakip">
					<li>' . $halaman_lke . '</li>
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

			foreach ($get_jadwal_lke as $get_jadwal_lke_sakip) {
				$pengisian_lke_per_skpd = $this->functions->generatePage(array(
					'nama_page' => 'Halaman Pengisian LKE ' . $get_jadwal_lke_sakip['nama_jadwal'].' '.$get_jadwal_lke_sakip['tahun_anggaran'],
					'content' => '[pengisian_lke_sakip_per_skpd id_jadwal=' . $get_jadwal_lke_sakip['id'] . ']',
					'show_header' => 1,
					'post_status' => 'private'
				));
				$pengisian_lke_per_skpd_page .= '<li><a target="_blank" href="' . $pengisian_lke_per_skpd['url'] . '&id_skpd=' . $skpd_db['id_skpd'] . '" class="btn btn-primary">' . $pengisian_lke_per_skpd['title'] . '</a></li>';
			}
			if (empty($pengisian_lke_per_skpd_page)) {
				$pengisian_lke_per_skpd_page = '<li><a return="false" href="#" class="btn btn-secondary">Pengisian LKE kosong atau belum dibuat</a></li>';
			}
			$halaman_lke_per_skpd = '
			<div class="accordion">
				<h5 class="esakip-header-tahun" data-id="lke" style="margin: 0;">Halaman Pengisian LKE</h5>
				<div class="esakip-body-tahun" data-id="lke">
					<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
						' . $pengisian_lke_per_skpd_page . '
					</ul>
				</div>
			</div>';

			$halaman_renstra_skpd = '
				<div class="accordion">
					<h5 class="esakip-header-tahun" data-id="renstra" style="margin: 0;">Periode Upload Dokumen RENSTRA</h5>
					<div class="esakip-body-tahun" data-id="renstra">
						<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
							' . str_replace('&id_skpd=ganti', '&id_skpd=' . $skpd_db['id_skpd'], $periode_renstra_skpd) . '
						</ul>
					</div>
				</div>';
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
				<h2 class="text-center">' . $skpd_db['nama_skpd'] . '</h2>
				<ul class="daftar-menu-sakip">
				<li>' . $halaman_renstra_skpd . '</li>
				<li>' . $halaman_lke_per_skpd . '</li>
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

	public function tambah_komponen_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah data!',
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$id_komponen = null;

				if (!empty($_POST['id'])) {
					$id_komponen = $_POST['id'];
					$ret['message'] = 'Berhasil edit data!';
				}

				if (!empty($_POST['id_jadwal'])) {
					$id_jadwal = $_POST['id_jadwal'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
				}
				if (!empty($_POST['nama_komponen'])) {
					$nama_komponen = $_POST['nama_komponen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nama Komponen kosong!';
				}
				if (!empty($_POST['bobot_komponen'])) {
					$bobot_komponen = $_POST['bobot_komponen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Bobot Komponen kosong!';
				}
				if (!empty($_POST['nomor_urut'])) {
					$nomor_urut = $_POST['nomor_urut'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nomor Urut kosong!';
				}
				if (!empty($_POST['user_penilai'])) {
					$user_penilai = $_POST['user_penilai'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'User Penilai kosong!';
				}

				if ($ret['status'] === 'success') {
					if (!empty($id_komponen)) {
						$old_bobot = $wpdb->get_var(
							$wpdb->prepare("
								SELECT bobot 
								FROM esakip_komponen 
								WHERE id = %d 
								  AND active = 1
							", $id_komponen)
						);

						$total_bobot = $wpdb->get_var(
							$wpdb->prepare("
								SELECT SUM(bobot) 
								FROM esakip_komponen 
								WHERE id_jadwal = %d 
								  AND active = 1
							", $id_jadwal)
						) - $old_bobot + $bobot_komponen;
					} else {
						$total_bobot = $wpdb->get_var(
							$wpdb->prepare("
								SELECT SUM(bobot) 
								FROM esakip_komponen 
								WHERE id_jadwal = %d 
								  AND active = 1
							", $id_jadwal)
						) + $bobot_komponen;
					}
					$bobot_sub = $wpdb->get_var(
						$wpdb->prepare("
							SELECT SUM(bobot) 
							FROM esakip_subkomponen 
							WHERE id_komponen = %d 
							  AND active = 1
						", $id_komponen)
					);

					if ($total_bobot > 100) {
						$ret = array(
							'status' => 'error',
							'message' => 'Total bobot komponen melebihi 100!'
						);
					} else if ($bobot_sub > $bobot_komponen) {
						$ret = array(
							'status' => 'error',
							'message' => 'Bobot Sub Komponen melebihi bobot Komponen Induknya!'
						);
					} else {
						if (!empty($id_komponen)) {
							$wpdb->update(
								'esakip_komponen',
								array(
									'nama' => $nama_komponen,
									'bobot' => $bobot_komponen,
									'nomor_urut' => $nomor_urut,
									'id_user_penilai' => $user_penilai,
								),
								array('id' => $id_komponen),
								array('%s', '%f', '%f'),
								array('%d')
							);
						} else {
							$wpdb->insert(
								'esakip_komponen',
								array(
									'id_jadwal' => $id_jadwal,
									'nama' => $nama_komponen,
									'bobot' => $bobot_komponen,
									'nomor_urut' => $nomor_urut,
									'id_user_penilai' => $user_penilai,
									'active' => 1,
								),
								array('%d', '%s', '%f', '%f', '%d')
							);
						}
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
	public function tambah_subkomponen_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah data!',
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$id_subkomponen = null;

				if (!empty($_POST['id'])) {
					$id_subkomponen = $_POST['id'];
					$ret['message'] = 'Berhasil edit data!';
				}

				if (!empty($_POST['id_komponen'])) {
					$id_komponen = $_POST['id_komponen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Komponen kosong!';
				}
				if (!empty($_POST['nama_subkomponen'])) {
					$nama_subkomponen = $_POST['nama_subkomponen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nama Subkomponen kosong!';
				}
				if (!empty($_POST['bobot_subkomponen'])) {
					$bobot_subkomponen = $_POST['bobot_subkomponen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Bobot Sub Komponen kosong!';
				}
				if (!empty($_POST['nomor_urut'])) {
					$nomor_urut = $_POST['nomor_urut'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nomor Urut kosong!';
				}
				if (!empty($_POST['user_penilai'])) {
					$user_penilai = $_POST['user_penilai'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'User Penilai kosong!';
				}

				if ($ret['status'] === 'success') {
					if (!empty($id_subkomponen)) {
						$old_bobot = $wpdb->get_var(
							$wpdb->prepare("
								SELECT bobot 
								FROM esakip_subkomponen
								WHERE id = %d 
							", $id_subkomponen)
						);

						$total_bobot = $wpdb->get_var(
							$wpdb->prepare("
								SELECT SUM(bobot) 
								FROM esakip_subkomponen
								WHERE id_komponen = %d 
								  AND active = 1
							", $id_komponen)
						) - $old_bobot + $bobot_subkomponen;
					} else {
						$total_bobot = $wpdb->get_var(
							$wpdb->prepare("
								SELECT SUM(bobot) 
								FROM esakip_subkomponen
								WHERE id_komponen = %d 
								  AND active = 1
							", $id_komponen)
						) + $bobot_subkomponen;
					}
					$bobot_komponen = $wpdb->get_var(
						$wpdb->prepare("
							SELECT bobot
							FROM esakip_komponen
							WHERE id = %d
							AND active = 1
						", $id_komponen)
					);
					if ($total_bobot > $bobot_komponen) {
						$ret = array(
							'status' => 'error',
							'message' => 'Total bobot Sub Komponen Komponen Induknya!'
						);
					} else {
						if (!empty($id_subkomponen)) {
							$wpdb->update(
								'esakip_subkomponen',
								array(
									'nama' => $nama_subkomponen,
									'bobot' => $bobot_subkomponen,
									'nomor_urut' => $nomor_urut,
									'id_user_penilai' => $user_penilai,
								),
								array('id' => $id_subkomponen),
								array('%s', '%f', '%f'),
								array('%d')
							);
						} else {
							$wpdb->insert(
								'esakip_subkomponen',
								array(
									'id_komponen' => $id_komponen,
									'nama' => $nama_subkomponen,
									'bobot' => $bobot_subkomponen,
									'nomor_urut' => $nomor_urut,
									'id_user_penilai' => $user_penilai,
									'active' => 1,
								),
								array('%d', '%s', '%f', '%f', '%d')
							);
						}
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
	public function tambah_komponen_penilaian_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah data!',
		);
		if (!empty($_POST)) {

			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$id_komponen_penilaian = null;

				if (!empty($_POST['id'])) {
					$id_komponen_penilaian = $_POST['id'];
					$ret['message'] = 'Berhasil edit data!';
				}

				if (!empty($_POST['id_subkomponen'])) {
					$id_subkomponen = $_POST['id_subkomponen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Subkomponen kosong!';
				}
				if (!empty($_POST['nama_komponen_penilaian'])) {
					$nama_komponen_penilaian = $_POST['nama_komponen_penilaian'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nama Komponen Penilaian kosong!';
				}
				if (!empty($_POST['tipe_komponen_penilaian'])) {
					$tipe_komponen_penilaian = $_POST['tipe_komponen_penilaian'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe Komponen Penilaian kosong!';
				}
				if (!empty($_POST['keterangan'])) {
					$keterangan = $_POST['keterangan'];
				}
				if (!empty($_POST['nomor_urut'])) {
					$nomor_urut = $_POST['nomor_urut'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nomor Urut kosong!';
				}

				if ($ret['status'] === 'success') {
					if (!empty($id_komponen_penilaian)) {
						$wpdb->update(
							'esakip_komponen_penilaian',
							array(
								'id_subkomponen' => $id_subkomponen,
								'nama' => $nama_komponen_penilaian,
								'tipe' => $tipe_komponen_penilaian,
								'nomor_urut' => $nomor_urut,
								'keterangan' => $keterangan,
							),
							array('id' => $id_komponen_penilaian),
							array('%d', '%s', '%s', '%f', '%s'),
							array('%d')
						);
					} else {
						$wpdb->insert(
							'esakip_komponen_penilaian',
							array(
								'id_subkomponen' => $id_subkomponen,
								'nama' => $nama_komponen_penilaian,
								'tipe' => $tipe_komponen_penilaian,
								'nomor_urut' => $nomor_urut,
								'keterangan' => $keterangan,
								'active' => 1,
							),
							array('%d', '%s', '%s', '%f', '%s', '%d')
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

	public function get_detail_komponen_lke_by_id()
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
							FROM esakip_komponen
							WHERE id = %d
						", $_POST['id']),
						ARRAY_A
					);
					if ($data) {
						$default_urutan = $wpdb->get_var(
							$wpdb->prepare("
							SELECT MAX(nomor_urut)
							FROM esakip_komponen
							WHERE id_jadwal = %d
						", $_POST['id'])
						);

						if ($default_urutan === null) {
							$default_urutan = 0;
						}

						$ret['data'] = $data + ['default_urutan' => $default_urutan];
					} else {
						$ret = array(
							'status' => 'error',
							'message'   => 'Data Tidak Ditemukan!'
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
	public function get_detail_subkomponen_lke_by_id()
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
							FROM esakip_subkomponen
							WHERE id = %d
						", $_POST['id']),
						ARRAY_A
					);

					if ($data) {
						$data_komponen = $wpdb->get_row(
							$wpdb->prepare("
								SELECT 
									nama,
									bobot
								FROM esakip_komponen
								WHERE id = %d
							", $_POST['id']),
							ARRAY_A
						);

						$default_urutan = $wpdb->get_var(
							$wpdb->prepare("
								SELECT MAX(nomor_urut)
								FROM esakip_subkomponen
								WHERE id_komponen = %d
							", $data['id_komponen'])
						);

						if ($default_urutan === null) {
							$default_urutan = 0;
						}

						$ret['data'] = $data + ['komponen' => $data_komponen] + ['default_urutan' => $default_urutan];
					} else {
						$ret = array(
							'status' => 'error',
							'message' => 'Data Komponen tidak ditemukan!'
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
	public function get_detail_penilaian_lke_by_id()
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
					$data_subkomponen = $wpdb->get_row(
						$wpdb->prepare("
								SELECT 
									nama,
									id_komponen,
									bobot
								FROM esakip_subkomponen
								WHERE id = %d
							", $_POST['id']),
						ARRAY_A
					);
					if (!empty($data_subkomponen)) {
						$data_komponen = $wpdb->get_row(
							$wpdb->prepare("
								SELECT 
									nama,
									bobot
								FROM esakip_komponen
								WHERE id = %d
							", $data_subkomponen['id_komponen']),
							ARRAY_A
						);

						$default_urutan = $wpdb->get_var(
							$wpdb->prepare("
								SELECT MAX(nomor_urut)
								FROM esakip_komponen_penilaian
								WHERE id_subkomponen = %d
							", $_POST['id'])
						);

						if ($default_urutan === null) {
							$default_urutan = 0;
						}

						$ret['data'] = ['subkomponen' => $data_subkomponen] + ['default_urutan' => $default_urutan] + ['komponen' => $data_komponen];
					} else {
						$ret = array(
							'status' => 'error',
							'message' => 'Data Sub Komponen tidak ditemukan!'
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
	public function get_komponen_lke_by_id()
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
							FROM esakip_komponen
							WHERE id = %d
						", $_POST['id']),
						ARRAY_A
					);
					if ($data) {
						$default_urutan = $wpdb->get_var(
							$wpdb->prepare("
							SELECT MAX(nomor_urut)
							FROM esakip_komponen
							WHERE id_jadwal = %d
						", $_POST['id'])
						);

						if ($default_urutan === null) {
							$default_urutan = 0;
						}

						$ret['data'] = $data + ['default_urutan' => $default_urutan];
					} else {
						$ret = array(
							'status' => 'error',
							'message'   => 'Data Tidak Ditemukan!'
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
	public function get_subkomponen_lke_by_id()
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
							FROM esakip_subkomponen
							WHERE id = %d
						", $_POST['id']),
						ARRAY_A
					);

					if ($data) {
						$data_komponen = $wpdb->get_row(
							$wpdb->prepare("
								SELECT 
									nama,
									bobot
								FROM esakip_komponen
								WHERE id = %d
							", $data['id_komponen']),
							ARRAY_A
						);

						$default_urutan = $wpdb->get_var(
							$wpdb->prepare("
								SELECT MAX(nomor_urut)
								FROM esakip_subkomponen
								WHERE id_komponen = %d
							", $data['id_komponen'])
						);

						if ($default_urutan === null) {
							$default_urutan = 0;
						}

						$ret['data'] = $data + ['komponen' => $data_komponen] + ['default_urutan' => $default_urutan];
					} else {
						$ret = array(
							'status' => 'error',
							'message' => 'Data Komponen tidak ditemukan!'
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
	public function get_komponen_penilaian_lke_by_id()
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
							FROM esakip_komponen_penilaian
							WHERE id = %d
						", $_POST['id']),
						ARRAY_A
					);
					if ($data) {
						$data_subkomponen = $wpdb->get_row(
							$wpdb->prepare("
								SELECT 
									nama,
									id_komponen,
									bobot
								FROM esakip_subkomponen
								WHERE id = %d
							", $data['id_subkomponen']),
							ARRAY_A
						);

						$data_komponen = $wpdb->get_row(
							$wpdb->prepare("
								SELECT 
									nama,
									bobot
								FROM esakip_komponen
								WHERE id = %d
							", $data_subkomponen['id_komponen']),
							ARRAY_A
						);

						$ret['data'] = $data + ['subkomponen' => $data_subkomponen] + ['komponen' => $data_komponen];
					} else {
						$ret = array(
							'status' => 'error',
							'message' => 'Data Sub Komponen tidak ditemukan!'
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

	public function hapus_komponen_lke()
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
					$cek_id = $wpdb->get_var(
						$wpdb->prepare("
							SELECT id_komponen
							FROM esakip_subkomponen
							WHERE id_komponen=%d
						", $_POST['id'])
					);
					if (empty($cek_id)) {
						$ret['data'] = $wpdb->update(
							'esakip_komponen',
							array('active' => 0),
							array('id' => $_POST['id'])
						);
					} else {
						$ret = array(
							'status' => 'error',
							'message'   => 'Data dengan ID = ' . $cek_id . ' memiliki Subkomponen Aktif!'
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
	public function hapus_komponen_penilaian_lke()
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
					$ret['data'] = $wpdb->update(
						'esakip_komponen_penilaian',
						array('active' => 0),
						array('id' => $_POST['id'])
					);
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
	public function hapus_subkomponen_lke()
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
					$cek_id = $wpdb->get_var(
						$wpdb->prepare("
							SELECT id_subkomponen
							FROM esakip_komponen_penilaian
							WHERE id_subkomponen=%d
						", $_POST['id'])
					);
					if (empty($cek_id)) {
						$ret['data'] = $wpdb->update(
							'esakip_subkomponen',
							array('active' => 0),
							array('id' => $_POST['id'])
						);
					} else {
						$ret = array(
							'status' => 'error',
							'message'   => 'Data dengan ID = ' . $cek_id . ' memiliki Komponen Penilaian Aktif!'
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

	public function tambah_nilai_penetapan_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah nilai penetapan!',
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['id_komponen_penilaian'])) {
					$id_komponen_penilaian = $_POST['id_komponen_penilaian'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Komponen Penilaian kosong!';
				}
				if (isset($_POST['nilai_penetapan'])) {
					$nilai_penetapan = $_POST['nilai_penetapan'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nilai Penetapan kosong!';
				}
				if (isset($_POST['ket_penetapan'])) {
					$ket_penetapan = $_POST['ket_penetapan'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Keterangan Penetapan kosong!';
				}

				$current_user = wp_get_current_user();
				$allowed_roles = array('admin_ortala', 'admin_perencanaan', 'administrator');
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
					", $id_skpd, $id_komponen_penilaian)
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
						$ret['message'] = "Berhasil tambah nilai penetapan!";
					} else {
						$ret['status'] = 'error';
						$ret['message'] = "Gagal melakukan update nilai penetapan: " . $wpdb->last_error;
					}
				} else {
					$ret = array(
						'status' => 'error',
						'message' => 'Id Penilaian Tidak Ditemukan!'
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

	public function tambah_nilai_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah nilai!',
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_skpd'])) {
					$id_skpd = $_POST['id_skpd'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id SKPD kosong!';
				}
				if (!empty($_POST['id_komponen_penilaian'])) {
					$id_komponen_penilaian = $_POST['id_komponen_penilaian'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Komponen Penilaian kosong!';
				}
				if (isset($_POST['nilai_usulan'])) {
					$nilai_usulan = $_POST['nilai_usulan'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Nilai Usulan kosong!';
				}
				if (!empty($_POST['ket_usulan'])) {
					$ket_usulan = $_POST['ket_usulan'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Keterangan Usulan kosong!';
				}
				if (!empty($_POST['bukti_usulan'])) {
					$bukti_usulan = $_POST['bukti_usulan'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Bukti Usulan kosong!';
				}
				$current_user = wp_get_current_user();
				$allowed_roles = array('admin_ortala', 'admin_perencanaan', 'administrator');
				if (!empty(array_intersect($allowed_roles, $current_user->roles))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Akses ditolak - hanya pengguna dengan peran tertentu yang dapat mengakses fitur ini!';
					die(json_encode($ret));
				}
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

				$allowed_roles = array();
				$existing_data = $wpdb->get_var(
					$wpdb->prepare("
						SELECT 
							id
						FROM esakip_pengisian_lke
						WHERE id_skpd = %d
						  AND id_komponen_penilaian = %d
					", $id_skpd, $id_komponen_penilaian)
				);
				if ($existing_data) {
					$updated = $wpdb->update(
						'esakip_pengisian_lke',
						array(
							'nilai_usulan' => $nilai_usulan,
							'keterangan' => $ket_usulan,
							'bukti_dukung' => $bukti_usulan,
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
							'bukti_dukung' => $bukti_usulan,
							'create_at' => current_time('mysql')
						),
						array('%d', '%d', '%d', '%s', '%f', '%s', '%s', '%s', '%s'),
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

	public function list_perangkat_daerah()
	{
		global $wpdb;

		try {
			if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

					$all_skpd = array();
					$user_id = um_user('ID');
					$user_meta = get_userdata($user_id);
					$list_skpd_options = '<option value="">Pilih Unit Kerja</option><option value="all">Semua Unit Kerja</option>';
					$nama_skpd = "";
					if (
						in_array("pa", $user_meta->roles)
						|| in_array("kpa", $user_meta->roles)
						|| in_array("plt", $user_meta->roles)
					) {
						$nipkepala = get_user_meta($user_id, '_nip');
						$skpd_db = $wpdb->get_results($wpdb->prepare("
							SELECT 
								nama_skpd, 
								id_skpd, 
								kode_skpd,
								is_skpd
							from esakip_data_unit 
							where nipkepala=%s 
								and tahun_anggaran=%d
							group by id_skpd", $nipkepala[0], $_POST['tahun_anggaran']), ARRAY_A);
						foreach ($skpd_db as $skpd) {
							$nama_skpd = '<br>' . $skpd['kode_skpd'] . ' ' . $skpd['nama_skpd'];
							$all_skpd[] = $skpd;
							$list_skpd_options .= '<option value="' . $skpd['id_skpd'] . '">' . $skpd['kode_skpd'] . ' ' . $skpd['nama_skpd'] . '</option>';
							if ($skpd['is_skpd'] == 1) {
								$sub_skpd_db = $wpdb->get_results($wpdb->prepare("
									SELECT 
										nama_skpd, 
										id_skpd, 
										kode_skpd,
										is_skpd
									from esakip_data_unit 
									where id_unit=%d 
										and tahun_anggaran=%d
										and is_skpd=0
									group by id_skpd", $skpd['id_skpd'], $_POST['tahun_anggaran']), ARRAY_A);
								foreach ($sub_skpd_db as $sub_skpd) {
									$all_skpd[] = $sub_skpd;
									$list_skpd_options .= '<option value="' . $sub_skpd['id_skpd'] . '">-- ' . $sub_skpd['kode_skpd'] . ' ' . $sub_skpd['nama_skpd'] . '</option>';
								}
							}
						}
					} else if (
						in_array("administrator", $user_meta->roles)
						|| in_array("admin_bappeda", $user_meta->roles)
						|| in_array("admin_ortala", $user_meta->roles)
					) {
						$skpd_mitra = $wpdb->get_results($wpdb->prepare("
							SELECT 
								nama_skpd, 
								id_skpd, 
								kode_skpd,
								is_skpd 
							from esakip_data_unit 
							where active=1 
								and tahun_anggaran=%d
							group by id_skpd
							order by id_unit ASC, kode_skpd ASC", $_POST['tahun_anggaran']), ARRAY_A);
						foreach ($skpd_mitra as $k => $v) {
							$all_skpd[] = $v;

							if($v['is_skpd'] == 1){
								$list_skpd_options .= '<option value="'.$v['id_skpd'].'">'.$v['kode_skpd'].' '.$v['nama_skpd'].'</option>';
							}
						}
					}
					echo json_encode([
						'status' => true,
						'list_skpd_options' => $list_skpd_options
					]);
					exit();
				} else {
					throw new Exception("Api key tidak sesuai", 1);
				}
			} else {
				throw new Exception("Format tidak sesuai", 1);
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
