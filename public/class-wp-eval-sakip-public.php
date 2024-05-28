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

require_once ESAKIP_PLUGIN_PATH . "/public/class-wp-eval-sakip-public-veirify-dokumen.php";
class Wp_Eval_Sakip_Public extends Wp_Eval_Sakip_Verify_Dokumen
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

	public $functions;

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

	public function dpa($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-dpa.php';
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

	public function upload_dokumen_rpjpd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-rpjpd.php';
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

	public function laporan_monev_renaksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-laporan-monev-renaksi.php';
	}

	public function pedoman_teknis_perencanaan($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-pedoman-teknis-perencanaan.php';
	}

	public function pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-pedoman-teknis-pengukuran-dan-pengumpulan-data-kinerja.php';
	}

	public function pedoman_teknis_evaluasi_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-pedoman-teknis-evaluasi-internal.php';
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

	public function jadwal_rpjpd()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-jadwal-rpjpd.php';
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

	public function dokumen_detail_dpa($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-dpa-per-skpd.php';
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

	public function dokumen_detail_pohon_kinerja_dan_cascading($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-pohon-kinerja-dan-cascading-per-skpd.php';
	}

	public function dokumen_detail_lhe_akip_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-lhe-akip-internal-per-skpd.php';
	}

	public function dokumen_detail_tl_lhe_akip_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-tl-lhe-akip-internal-per-skpd.php';
	}

	public function dokumen_detail_tl_lhe_akip_kemenpan($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-tl-lhe-akip-kemenpan-per-skpd.php';
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

	public function pohon_kinerja_dan_cascading($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-pohon-kinerja-dan-cascading.php';
	}

	public function dokumen_detail_laporan_monev_renaksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-laporan-monev-renaksi-per-skpd.php';
	}

	public function dokumen_detail_pedoman_teknis_perencanaan($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-pedoman-teknis-perencanaan-per-skpd.php';
	}

	public function dokumen_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-pedoman-teknis-pengukuran-dan-pengumpulan-data-kinerja-per-skpd.php';
	}

	public function dokumen_detail_pedoman_teknis_evaluasi_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-detail-pedoman-teknis-evaluasi-internal-per-skpd.php';
	}

	public function lhe_akip_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-lhe-akip-internal.php';
	}

	public function tl_lhe_akip_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-tl-lhe-akip-internal.php';
	}

	public function tl_lhe_akip_kemenpan($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-tl-lhe-akip-kemenpan.php';
	}

	public function is_admin_panrb()
	{
		$current_user = wp_get_current_user();
		return in_array('admin_panrb', $current_user->roles);
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

	public function get_detail_dokumen_by_id()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

				if (!empty($_POST['id']) && !empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
					// untuk mengatur tabel sesuai tipe dokumen
					$nama_tabel = array(
						"pohon_kinerja_dan_cascading" => "esakip_pohon_kinerja_dan_cascading",
						"lhe_akip_internal" => "esakip_lhe_akip_internal",
						"tl_lhe_akip_internal" => "esakip_tl_lhe_akip_internal",
						"tl_lhe_akip_kemenpan" => "esakip_tl_lhe_akip_kemenpan"
					);

					$data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT *
							FROM $nama_tabel[$tipe_dokumen]
							WHERE id = %d
						", $_POST['id']),
						ARRAY_A
					);
					$ret['data'] = $data;
				} else {
					$ret = array(
						'status' => 'error',
						'message'   => 'Ada Data Yang Kosong!'
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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

	// public function get_table_dpa()
	// {
	// 	global $wpdb;
	// 	$ret = array(
	// 		'status' => 'success',
	// 		'message' => 'Berhasil get data!',
	// 		'data' => array()
	// 	);

	// 	if (!empty($_POST)) {
	// 		if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
	// 			if (!empty($_POST['id_skpd'])) {
	// 				$id_skpd = $_POST['id_skpd'];
	// 			} else {
	// 				$ret['status'] = 'error';
	// 				$ret['message'] = 'Id SKPD kosong!';
	// 			}
	// 			if (!empty($_POST['tahun_anggaran'])) {
	// 				$tahun_anggaran = $_POST['tahun_anggaran'];
	// 			} else {
	// 				$ret['status'] = 'error';
	// 				$ret['message'] = 'Tahun Anggaran kosong!';
	// 			}
	// 			$get_dpa = $wpdb->get_results(
	// 				$wpdb->prepare("
    //                 SELECT * 
    //                 FROM esakip_dpa 
    //                 WHERE id_skpd = %d 
    //                   AND tahun_anggaran = %d 
    //                   AND active = 1
    //             ", $id_skpd, $tahun_anggaran),
	// 				ARRAY_A
	// 			);

	// 			if (!empty($get_dpa)) {
	// 				$counter = 1;
	// 				$tbody = '';

	// 				foreach ($get_dpa as $kk => $vv) {
	// 					$tbody .= "<tr>";
	// 					$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
	// 					$tbody .= "<td>" . $vv['opd'] . "</td>";
	// 					$tbody .= "<td>" . $vv['dokumen'] . "</td>";
	// 					$tbody .= "<td>" . $vv['keterangan'] . "</td>";
	// 					$tbody .= "<td>" . $vv['created_at'] . "</td>";

	// 					$btn = '<div class="btn-action-group">';
	// 					$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
	// 					if (!$this->is_admin_panrb()) {
	// 						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_dpa(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
	// 						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_dpa(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
	// 					}
	// 					$btn .= '</div>';

	// 					$tbody .= "<td class='text-center'>" . $btn . "</td>";
	// 					$tbody .= "</tr>";
	// 				}

	// 				$ret['data'] = $tbody;
	// 			} else {
	// 				$ret['data'] = "<tr><td colspan='6' class='text-center'>Tidak ada data tersedia</td></tr>";
	// 			}
	// 		} else {
	// 			$ret = array(
	// 				'status' => 'error',
	// 				'message'   => 'Api Key tidak sesuai!'
	// 			);
	// 		}
	// 	} else {
	// 		$ret = array(
	// 			'status' => 'error',
	// 			'message'   => 'Format tidak sesuai!'
	// 		);
	// 	}
	// 	die(json_encode($ret));
	// }

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

					// user authorize
					$current_user = wp_get_current_user();
					//jika user adalah admin atau skpd
					$can_verify = false;
					if (
						in_array("admin_ortala", $current_user->roles) ||
						in_array("admin_bappeda", $current_user->roles) ||
						in_array("administrator", $current_user->roles)
					) {
						$can_verify = true;
					}

					foreach ($renjas as $kk => $vv) {
						$data_verifikasi = $wpdb->get_row($wpdb->prepare('
											SELECT 
												*
											FROM esakip_keterangan_verifikator
											WHERE id_dokumen=%d
												AND active=1
										', $vv['id']), ARRAY_A);

						$color_badge_verify = 'secondary';
						$text_badge = 'Menunggu';
						if($data_verifikasi['status_verifikasi'] == 1){
							$color_badge_verify = 'success';
							$text_badge = 'Diterima';
						}else if($data_verifikasi['status_verifikasi'] == 2){
							$color_badge_verify = 'danger';
							$text_badge = 'Ditolak';
						}

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";
						$tbody .= "<td class='text-center'><span class='badge badge-" . $color_badge_verify . "' style='padding: .5em 1.4em;'>" . $text_badge . "</span></td>";
						$tbody .= "<td>" . $data_verifikasi['keterangan_verifikasi'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if($can_verify){
							$btn .= '<button class="btn btn-sm btn-success" onclick="verifikasi_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Verifikasi Dokumen"><span class="dashicons dashicons-yes"></span></button>';
						}
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_renja(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_renja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $btn . "</td>";
						$tbody .= "</tr>";
					}

					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='8' class='text-center'>Tidak ada data tersedia</td></tr>";
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
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
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_rencana_aksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_rencana_aksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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

					// user authorize
					$current_user = wp_get_current_user();
					//jika user adalah admin atau skpd
					$can_verify = false;
					if (
						in_array("admin_ortala", $current_user->roles) ||
						in_array("admin_bappeda", $current_user->roles) ||
						in_array("administrator", $current_user->roles)
					) {
						$can_verify = true;
					}

					foreach ($perjanjian_kinerjas as $kk => $vv) {
						$data_verifikasi = $wpdb->get_row($wpdb->prepare('
											SELECT 
												*
											FROM esakip_keterangan_verifikator
											WHERE id_dokumen=%d
												AND active=1
										', $vv['id']), ARRAY_A);

						$color_badge_verify = 'secondary';
						$text_badge = 'Menunggu';
						if($data_verifikasi['status_verifikasi'] == 1){
							$color_badge_verify = 'success';
							$text_badge = 'Diterima';
						}else if($data_verifikasi['status_verifikasi'] == 2){
							$color_badge_verify = 'danger';
							$text_badge = 'Ditolak';
						}

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";
						$tbody .= "<td class='text-center'><span class='badge badge-" . $color_badge_verify . "' style='padding: .5em 1.4em;'>" . $text_badge . "</span></td>";
						$tbody .= "<td>" . $data_verifikasi['keterangan_verifikasi'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if($can_verify){
							$btn .= '<button class="btn btn-sm btn-success" onclick="verifikasi_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Verifikasi Dokumen"><span class="dashicons dashicons-yes"></span></button>';
						}
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_perjanjian_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_perjanjian_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $btn . "</td>";
						$tbody .= "</tr>";
					}

					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='8' class='text-center'>Tidak ada data tersedia</td></tr>";
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
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

					// user authorize
					$current_user = wp_get_current_user();
					//jika user adalah admin atau skpd
					$can_verify = false;
					if (
						in_array("admin_ortala", $current_user->roles) ||
						in_array("admin_bappeda", $current_user->roles) ||
						in_array("administrator", $current_user->roles)
					) {
						$can_verify = true;
					}

					foreach ($laporan_kinerjas as $kk => $vv) {
						$data_verifikasi = $wpdb->get_row($wpdb->prepare('
											SELECT 
												*
											FROM esakip_keterangan_verifikator
											WHERE id_dokumen=%d
												AND active=1
										', $vv['id']), ARRAY_A);

						$color_badge_verify = 'secondary';
						$text_badge = 'Menunggu';
						if ($data_verifikasi['status_verifikasi'] == 1) {
							$color_badge_verify = 'success';
							$text_badge = 'Diterima';
						} else if ($data_verifikasi['status_verifikasi'] == 2) {
							$color_badge_verify = 'danger';
							$text_badge = 'Ditolak';
						}
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";
						$tbody .= "<td class='text-center'><span class='badge badge-" . $color_badge_verify . "' style='padding: .5em 1.4em;'>" . $text_badge . "</span></td>";
						$tbody .= "<td>" . $data_verifikasi['keterangan_verifikasi'] . "</td>";

						$btn = '<div class="btn-action-group">';
						if ($can_verify) {
							$btn .= '<button class="btn btn-sm btn-success" onclick="verifikasi_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Verifikasi Dokumen"><span class="dashicons dashicons-yes"></span></button>';
						}
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_laporan_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_laporan_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $btn . "</td>";
						$tbody .= "</tr>";
					}

					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='8' class='text-center'>Tidak ada data tersedia</td></tr>";
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
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
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_iku(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_iku(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_evaluasi_internal(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_evaluasi_internal(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_pengukuran_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_pengukuran_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_pengukuran_rencana_aksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_pengukuran_rencana_aksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_lkjip(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_lkjip(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_pemda_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_pemda_lain(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_rpjmd(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_rpjmd(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_rkpd(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_rkpd(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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

					// user authorize
					$current_user = wp_get_current_user();
					//jika user adalah admin atau skpd
					$can_verify = false;
					if (
						in_array("admin_ortala", $current_user->roles) ||
						in_array("admin_bappeda", $current_user->roles) ||
						in_array("administrator", $current_user->roles)
					) {
						$can_verify = true;
					}

					foreach ($renstras as $kk => $vv) {
						$data_verifikasi = $wpdb->get_row($wpdb->prepare('
											SELECT 
												*
											FROM esakip_keterangan_verifikator
											WHERE id_dokumen=%d
												AND active=1
										', $vv['id']), ARRAY_A);

						$color_badge_verify = 'secondary';
						$text_badge = 'Menunggu';
						if($data_verifikasi['status_verifikasi'] == 1){
							$color_badge_verify = 'success';
							$text_badge = 'Diterima';
						}else if($data_verifikasi['status_verifikasi'] == 2){
							$color_badge_verify = 'danger';
							$text_badge = 'Ditolak';
						}

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";
						$tbody .= "<td class='text-center'><span class='badge badge-" . $color_badge_verify . "' style='padding: .5em 1.4em;'>" . $text_badge . "</span></td>";
						$tbody .= "<td>" . $data_verifikasi['keterangan_verifikasi'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						// if($can_verify){
						// 	$btn .= '<button class="btn btn-sm btn-success" onclick="verifikasi_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Verifikasi Dokumen"><span class="dashicons dashicons-yes"></span></button>';
						// }
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_renstra(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_renstra(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $btn . "</td>";
						$tbody .= "</tr>";
					}

					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='8' class='text-center'>Tidak ada data tersedia</td></tr>";
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
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
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_skp(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_skp(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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

	public function get_table_dokumen()
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
				if (!empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe Dokumen kosong!';
				}

				if ($ret['status'] == 'success') {
					// untuk mengatur tabel sesuai tipe dokumen
					$nama_tabel = array(
						"pohon_kinerja_dan_cascading" => "esakip_pohon_kinerja_dan_cascading",
						"lhe_akip_internal" => "esakip_lhe_akip_internal",
						"tl_lhe_akip_internal" => "esakip_tl_lhe_akip_internal",
						"tl_lhe_akip_kemenpan" => "esakip_tl_lhe_akip_kemenpan"
					);

					$datas = $wpdb->get_results(
						$wpdb->prepare("
						SELECT * 
						FROM $nama_tabel[$tipe_dokumen]
						WHERE id_skpd = %d 
						  AND tahun_anggaran = %d 
						  AND active = 1
					", $id_skpd, $tahun_anggaran),
						ARRAY_A
					);

					if (!empty($datas)) {
						$counter = 1;
						$tbody = '';

						foreach ($datas as $kk => $vv) {
							$tbody .= "<tr>";
							$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
							$tbody .= "<td>" . $vv['opd'] . "</td>";
							$tbody .= "<td>" . $vv['dokumen'] . "</td>";
							$tbody .= "<td>" . $vv['keterangan'] . "</td>";
							$tbody .= "<td>" . $vv['created_at'] . "</td>";

							$btn = '<div class="btn-action-group">';
							$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
							if (!$this->is_admin_panrb()) {
								$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
								$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
							}
							$btn .= '</div>';

							$tbody .= "<td class='text-center'>" . $btn . "</td>";
							$tbody .= "</tr>";
						}

						$ret['data'] = $tbody;
					} else {
						$ret['data'] = "<tr><td colspan='6' class='text-center'>Tidak ada data tersedia</td></tr>";
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

	public function hapus_dokumen()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id']) && !empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
					// untuk mengatur tabel sesuai tipe dokumen
					$nama_tabel = array(
						"pohon_kinerja_dan_cascading" => "esakip_pohon_kinerja_dan_cascading",
						"lhe_akip_internal" => "esakip_lhe_akip_internal",
						"tl_lhe_akip_internal" => "esakip_tl_lhe_akip_internal",
						"tl_lhe_akip_kemenpan" => "esakip_tl_lhe_akip_kemenpan"
					);

					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM $nama_tabel[$tipe_dokumen]
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						$nama_tabel[$tipe_dokumen],
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
						'message'   => 'Ada Data Yang Kosong!'
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
				$report = '';
				if (!empty($queryRecords)) {
					foreach ($queryRecords as $recKey => $recVal) {
						$desain_lke_page = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Desain LKE SAKIP ' . $recVal['nama_jadwal'],
							'content' => '[desain_lke_sakip id_jadwal=' . $recVal['id'] . ']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));

						$report = '<div class="btn-group mr-2" role="group">';
						$report .= '<a class="btn btn-sm btn-primary" style="text-decoration: none;" onclick="report(\'' . $recVal['id'] . '\'); return false;" href="#" title="Cetak Laporan"><i class="dashicons dashicons-printer"></i></a>';
						$report .= '</div>';

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
										$id_komponen_penilaian_baru = $wpdb->insert_id;

										$data_kerangka_logis = $wpdb->get_results(
											$wpdb->prepare("
												SELECT * 
												FROM esakip_kontrol_kerangka_logis
												WHERE id_komponen_penilaian = %d
											", $penilaian['id']),
											ARRAY_A
										);

										foreach ($data_kerangka_logis as $kerangka_logis) {
											$data_kerangka_logis_baru = array(
												'id_komponen_penilaian' => $id_komponen_penilaian_baru,
												'jenis_kerangka_logis' => $kerangka_logis['jenis_kerangka_logis'],
												'id_komponen_pembanding' => $kerangka_logis['id_komponen_pembanding'],
												'pesan_kesalahan' => $kerangka_logis['pesan_kesalahan'],
											);
											$wpdb->insert('esakip_kontrol_kerangka_logis', $data_kerangka_logis_baru);
										}
									}
								}
							}
						}
					} else {
						// buat script dalam bentuk array nilai default design LKE
						$design = array(
							array( //0
								'id_jadwal' => $id_jadwal_baru,
								'nama' => 'PERENCANAAN KINERJA DEFAULT',
								'bobot' => '30',
								'nomor_urut' => '1.00',
								'id_user_penilai' => '1',
								'data' => array(
									array( //0
										'nama' => 'PEMENUHAN',
										'bobot' => '6',
										'nomor_urut' => '1.00',
										'id_user_penilai' => '1',
										'data' => array(
											array( //0
												'nama' => 'Renstra telah disusun',
												'tipe' => '1',
												'keterangan' => 'Renstra OPD (2024-2026)',
												'nomor_urut' => '1.00'
											),
											array( //1
												'nama' => 'Dokumen perencanaan kinerja tahunan (Renja) telah disusun',
												'tipe' => '1',
												'keterangan' => 'Renja 2023 dan 2024',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'pesan_kesalahan' => 'SALAH dokumen RENSTRA tidak ada',
														'id_komponen_pembanding' => '0-0-0'
													)
												)
											),
											array( //2
												'nama' => 'Terdapat dokumen perencanaan anggaran yang mendukung kinerja',
												'tipe' => '1',
												'keterangan' => '- Renja 2023 dan 2024, - DPA 2024 dan DPPA 2023 (di dokumen lainnya)',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-1',
														'pesan_kesalahan' => 'SALAH, Perencanaan kinerja tahunan tidak ada.'
													),
												)
											),
											array( //3
												'nama' => 'Perjanjian Kinerja (PK) telah disusun',
												'tipe' => '1',
												'keterangan' => 'PK 2023 dan 2024',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-1',
														'pesan_kesalahan' => 'SALAH, Perencanaan kinerja tahunan tidak ada.'
													)
												)
											),
											array( //4
												'nama' => 'PK telah menyajikan Indikator Tujuan/ Sasaran',
												'tipe' => '2',
												'keterangan' => 'PK 2023 dan 2024',
												'nomor_urut' => '5.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													),
												)
											),
											array( //5
												'nama' => 'Terdapat dokumen Rencana Aksi',
												'tipe' => '1',
												'keterangan' => 'Rencana Aksi 2023 dan 2024',
												'nomor_urut' => '6.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													),
												)
											)
										)
									),
									array( //1
										'nama' => 'KUALITAS RENSTRA',
										'bobot' => '9',
										'nomor_urut' => '2.00',
										'id_user_penilai' => '1',
										'data' => array(
											array( //0
												'nama' => 'Dokumen Perencanaan Kinerja telah diformalkan.',
												'tipe' => '1',
												'keterangan' => 'Renstra dan Renja',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-1',
														'pesan_kesalahan' => 'SALAH, Perencanaan kinerja tahunan tidak ada.'
													)
												)
											),
											array( //1
												'nama' => 'Renstra telah dipublikasikan tepat waktu',
												'tipe' => '1',
												'keterangan' => 'Screenshoot Renstra 2024-2026 di website OPD, esr dan aplikasi SAKIP',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													)
												)
											),
											array( //2
												'nama' => 'Renja telah dipublikasikan tepat waktu',
												'tipe' => '1',
												'keterangan' => 'Screenshoot Renja 2023 dan 2024 di website OPD, esr dan aplikasi SAKIP',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													)
												)
											),
											array( //3
												'nama' => 'Perjanjian Kinerja telah dipublikasikan tepat waktu',
												'tipe' => '1',
												'keterangan' => 'Screenshoot PK 2023 dan 2024 di website OPD, esr dan aplikasi SAKIP',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													)
												)
											),
											array( //4
												'nama' => 'Tujuan telah berorientasi hasil',
												'tipe' => '2',
												'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024',
												'nomor_urut' => '5.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'pesan_kesalahan' => 'SALAH dokumen RENSTRA tidak ada',
														'id_komponen_pembanding' => '0-0-0'
													)
												)
											),
											array( //5
												'nama' => 'Ukuran Keberhasilan (Indikator Kinerja) Tujuan telah memenuhi kriteria SMART.',
												'tipe' => '2',
												'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024',
												'nomor_urut' => '6.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-1',
														'pesan_kesalahan' => 'SALAH, Dokumen Perencanaan belum diformalkan.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-4',
														'pesan_kesalahan' => 'SALAH, Tujuan tidak ada.'
													)
												)
											),
											array( //6
												'nama' => 'Sasaran telah jelas berorientasi hasil',
												'tipe' => '2',
												'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024',
												'nomor_urut' => '7.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-1',
														'pesan_kesalahan' => 'SALAH, Dokumen Perencanaan belum diformalkan.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-4', 'pesan_kesalahan' => 'SALAH, Tujuan tidak ada.'
													)
												)
											),
											array( //7
												'nama' => 'Ukuran Keberhasilan (Indikator Kinerja) Sasaran telah memenuhi kriteria SMART.',
												'tipe' => '2',
												'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024',
												'nomor_urut' => '8.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-1',
														'pesan_kesalahan' => 'SALAH, Dokumen Perencanaan belum diformalkan.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-4', 'pesan_kesalahan' => 'SALAH, Tujuan tidak ada.'
													)
												)
											),
											array( //8
												'nama' => 'Indikator Kinerja Tujuan telah menggambarkan kondisi Tujuan yang harus dicapai, tertuang secara berkelanjutan (sustainable - tidak sering diganti dalam 1 periode Perencanaan Strategis).',
												'tipe' => '2',
												'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024',
												'nomor_urut' => '9.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-1',
														'pesan_kesalahan' => 'SALAH, Dokumen Perencanaan belum diformalkan.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-7', 'pesan_kesalahan' => 'SALAH, Indikator Tujuan belum SMART.'
													)
												)
											),
											array( //9
												'nama' => 'Indikator Kinerja Sasaran telah menggambarkan kondisi Sasaran yang harus dicapai, tertuang secara berkelanjutan (sustainable - tidak sering diganti dalam 1 periode Perencanaan Strategis).',
												'tipe' => '2',
												'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024',
												'nomor_urut' => '10.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-7', 'pesan_kesalahan' => 'SALAH, Indikator Tujuan belum SMART.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-1',
														'pesan_kesalahan' => ' SALAH, Dokumen Perencanaan belum diformalkan.'
													)
												)
											),
											array( //10
												'nama' => 'Target yang ditetapkan dalam Perencanaan Kinerja dapat dicapai (achievable) dan realistis.',
												'tipe' => '2',
												'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024',
												'nomor_urut' => '11.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-7',
														'pesan_kesalahan' => 'SALAH, Indikator Tujuan belum tepat'
													)
												)
											),
											array( //11
												'nama' => 'Setiap Dokumen Perencanaan Kinerja (Renstra, Renja, PK) telah menggambarkan hubungan yang berkesinambungan, serta selaras antara Kondisi/Hasil yang akan dicapai di setiap level jabatan (Cascading Kinerja).',
												'tipe' => '2',
												'keterangan' => 'Renstra OPD (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024,  cascading dan pohon kinerja (di dokumen lainnya)',
												'nomor_urut' => '12.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'pesan_kesalahan' => 'SALAH dokumen RENSTRA tidak ada',
														'id_komponen_pembanding' => '0-0-0'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '0-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata2 kualitas tujuan/sasaran, indikator, dan target.'
													)
												)
											)
										)
									),
									array( //2
										'nama' => 'IMPLEMENTASI',
										'bobot' => '15',
										'nomor_urut' => '3.00',
										'id_user_penilai' => '1',
										'data' => array(
											array( //0
												'nama' => 'Dokumen Renstra digunakan sebagai acuan penyusunan Dokumen Rencana Kerja dan Anggaran',
												'tipe' => '2',
												'keterangan' => '- Renstra OPD (2024-2026), Renja 2023 dan 2024, Rencana Aksi 2023 dan 2024 - DPA 2024 dan DPPA 2023 (di dokumen lainnya)',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '0-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas RENSTRA.'
													)
												)
											),
											array( //1
												'nama' => 'Target jangka menengah dalam Renstra telah dimonitor pencapaiannya sampai dengan tahun berjalan',
												'tipe' => '2',
												'keterangan' => 'Renstra OPD (2024-2026), Laporan Kinerja 2023',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '0-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas RENSTRA.'
													)
												)
											),
											array( //2
												'nama' => 'Anggaran yang ditetapkan telah mengacu pada Kinerja yang ingin dicapai',
												'tipe' => '2',
												'keterangan' => '- Renstra OPD (2024-2026), Renja 2023 dan 2024, Rencana Aksi 2023 dan 2024 - DPA 2024 dan DPPA 2023 (di dokumen lainnya)',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '0-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas RENSTRA.'
													)
												)
											),
											array( //3
												'nama' => 'Aktivitas yang dilaksanakan telah mendukung Kinerja',
												'tipe' => '2',
												'keterangan' => 'Rencana aksi 2023 dan 2024',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '0-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas RENSTRA.'
													)
												)
											),
											array( //4
												'nama' => 'Target kinerja yang diperjanjikan pada Perjanjian Kinerja telah digunakan untuk mengukur keberhasilan',
												'tipe' => '2',
												'keterangan' => '- Perjanjian Kinerja 2023 dan 2024, SKP 2023 dan 2024 - SK dan bukti pemberian reward punishment (dokumen lainnya)',
												'nomor_urut' => '5.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '0-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas RENSTRA.'
													)
												)
											),
											array( //5
												'nama' => 'Setiap pegawai memahami dan peduli serta berkomitmen dalam mencapai kinerja yang telah direncanakan dalam Sasaran Kinerja Pegawai (SKP)',
												'tipe' => '2',
												'keterangan' => 'SKP 2023 dan 2024, PK 2023 dan 2024',
												'nomor_urut' => '6.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '0-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas RENSTRA.'
													)
												)
											),
											array( //6
												'nama' => 'Dokumen Renstra telah direviu secara berkala',
												'tipe' => '2',
												'keterangan' => 'Screenshoot aplikasi e-monev E-80 dan E-81 (dokumen lainnya)',
												'nomor_urut' => '7.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '0-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas RENSTRA.'
													)
												)
											)
										)
									)
								),
							),
							array( //1
								'id_jadwal' => $id_jadwal_baru,

								'nama' => 'PENGUKURAN KINERJA',
								'bobot' => '30',
								'nomor_urut' => '2.00',
								'id_user_penilai' => '2',
								'data' => array(
									array( //0
										'nama' => 'PELAKSANAAN PENGUKURAN KINERJA',
										'bobot' => '6',
										'nomor_urut' => '1.00',
										'id_user_penilai' => '2',
										'data' => array(
											array( //0
												'nama' => 'Telah terdapat indikator kinerja utama (IKU) sebagai ukuran kinerja secara formal',
												'tipe' => '1',
												'keterangan' => 'Dokumen IKU 2024-2026',
												'nomor_urut' => '1.00'
											),
											array( //1
												'nama' => 'Terdapat Definisi Operasional yang jelas atas kinerja dan cara mengukur indikator kinerja.',
												'tipe' => '2',
												'keterangan' => 'Dokumen IKU 2024-2026',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													)
												)
											),
											array( //2
												'nama' => 'Terdapat mekanisme yang jelas terhadap pengumpulan data kinerja yang dapat diandalkan.',
												'tipe' => '2',
												'keterangan' => 'SOP pengumpulan data kinerja (di dokumen lainnya)',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-1',
														'pesan_kesalahan' => 'SALAH, Nilai lebih tinggi dari nilai Definisi Operasional yang jelas atas kinerja.'
													)
												)
											),
										)
									),
									array(
										'nama' => 'KUALITAS PENGUKURAN',
										'bobot' => '9',
										'nomor_urut' => '2.00',
										'id_user_penilai' => '2',
										'data' => array(
											array(
												'nama' => 'Pimpinan selalu terlibat sebagai pengambil keputusan (Decision Maker) dalam mengukur capaian kinerja.',
												'tipe' => '2',
												'keterangan' => 'Dokumen pengukuran kinerja Tahun 2023 dan 2024, SKP 2023 dan 2024',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													)

												)
											),
											array(
												'nama' => 'Data kinerja yang dikumpulkan telah relevan untuk mengukur capaian kinerja yang diharapkan.',
												'tipe' => '2',
												'keterangan' => 'Dokumen pengukuran kinerja Tahun 2023 dan 2024, SKP 2023 dan 2024,  Laporan Kinerja 2023',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, nilai lebih tinggi dari IKU sebagai ukuran kinerja.'
													)
												)
											),
											array(
												'nama' => 'Data kinerja yang dikumpulkan telah mendukung capaian kinerja yang diharapkan.',
												'tipe' => '2',
												'keterangan' => 'Dokumen pengukuran kinerja Tahun 2023 dan 2024, SKP 2023 dan 2024,  Laporan Kinerja 2023',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, nilai lebih tinggi dari IKU sebagai ukuran kinerja.'
													)
												)
											),
											array(
												'nama' => 'Pengumpulan data kinerja atas Rencana Aksi dilakukan secara berkala (bulanan/triwulanan/semester)',
												'tipe' => '1',
												'keterangan' => 'Pengukuran rencana aksi Tahun 2023 dan 2024',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, nilai lebih tinggi dari IKU sebagai ukuran kinerja.'
													)
												)
											),
											array(
												'nama' => 'Pengukuran kinerja sudah dilakukan secara berjenjang',
												'tipe' => '2',
												'keterangan' => 'Dokumen pengukuran kinerja Tahun 2023 dan 2024, SKP 2023 dan 2024',
												'nomor_urut' => '5.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, nilai lebih tinggi dari IKU sebagai ukuran kinerja.'
													)
												)
											),
											array(
												'nama' => 'Pengumpulan data kinerja telah memanfaatkan Teknologi Informasi (Aplikasi).',
												'tipe' => '1',
												'keterangan' => 'Screenshoot aplikasi EP3, E-monev (dokumen lainnya)',
												'nomor_urut' => '6.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '1-0',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Pelaksanaan Pengukuran Kinerja'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0', 'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													)
												)
											),
											array(
												'nama' => 'Pengukuran capaian kinerja telah memanfaatkan Teknologi Informasi (Aplikasi).',
												'tipe' => '1',
												'keterangan' => 'Screenshoot aplikasi EP3, E-monev (dokumen lainnya)',
												'nomor_urut' => '7.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '1-0',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Pelaksanaan Pengukuran Kinerja'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0', 'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													),
												)
											),
										)
									),
									array(
										'nama' => 'IMPLEMENTASI PENGUKURAN',
										'bobot' => '15',
										'nomor_urut' => '3.00',
										'id_user_penilai' => '2',
										'data' => array(
											array(
												'nama' => 'Pengukuran Kinerja telah menjadi dasar dalam penyesuaian (pemberian/pengurangan) tunjangan kinerja/penghasilan.',
												'tipe' => '2',
												'keterangan' => '- Pengukuran Kinerja 2023 dan 2024,  - Screenshoot EP3 (dokumen lainnya)',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0', 'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '1-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Pengukuran.'
													)
												)
											),
											array(
												'nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian (Refocusing) Organisasi.',
												'tipe' => '2',
												'keterangan' => '- Pengukuran Kinerja 2023 dan 2024 - Screenshoot EP3 (dokumen lainnya)',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0', 'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '1-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Pengukuran.'
													)
												)
											),
											array(
												'nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Strategi dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; Rencana Aksi 2024',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0', 'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '1-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Pengukuran.'
													)
												)
											),
											array(
												'nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Kebijakan dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; Rencana Aksi 2024',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0', 'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '1-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Pengukuran.'
													)
												)
											),
											array(
												'nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Aktivitas dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; Rencana Aksi 2024',
												'nomor_urut' => '5.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0', 'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '1-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Pengukuran.'
													)
												)
											),
											array(
												'nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Anggaran dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; DPA 2024 dan DPPA 2023',
												'nomor_urut' => '6.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0', 'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '1-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Pengukuran.'
													)
												)
											),
											array(
												'nama' => 'Terdapat efisiensi atas penggunaan anggaran dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => '- Laporan Kinerja 2023;  - DPPA 2023 (di dokumen lainnya)',
												'nomor_urut' => '7.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0', 'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '1-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Pengukuran.'
													)
												)
											),
											array(
												'nama' => 'Setiap unit/satuan kerja memahami dan peduli atas hasil pengukuran kinerja.',
												'tipe' => '2',
												'keterangan' => NULL,
												'nomor_urut' => '8.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0', 'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '1-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Pengukuran.'
													)
												)
											),
											array(
												'nama' => 'Setiap pegawai memahami dan peduli atas hasil pengukuran kinerja.',
												'tipe' => '2',
												'keterangan' => NULL,
												'nomor_urut' => '9.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0', 'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '1-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Pengukuran.'
													)
												)
											),
										)
									)
								)
							),
							array( //2
								'id_jadwal' => $id_jadwal_baru,

								'nama' => 'PELAPORAN KINERJA',
								'bobot' => '15',
								'nomor_urut' => '3.00',
								'id_user_penilai' => '3',
								'data' => array(
									array( //0
										'nama' => 'PEMENUHAN PELAPORAN',
										'bobot' => '3',
										'nomor_urut' => '1.00',
										'id_user_penilai' => '3',
										'data' => array(
											array( //0
												'nama' => 'Dokumen Laporan Kinerja telah disusun.',
												'tipe' => '1',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '1.00'
											),
											array( //1
												'nama' => 'Dokumen Laporan Kinerja telah disusun secara berkala.',
												'tipe' => '1',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												)
											),
											array( //2
												'nama' => 'Dokumen Laporan Kinerja telah diformalkan.',
												'tipe' => '1',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah direviu.',
												'tipe' => '1',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah dipublikasikan.',
												'tipe' => '1',
												'keterangan' => 'Screenshoot Laporan Kinerja 2023 pada website, esr, aplikasi sakip kab madiun (dokumen lainnya)',
												'nomor_urut' => '5.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah disampaikan tepat waktu.',
												'tipe' => '1',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '6.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												)
											),
										)

									),
									array(
										'nama' =>
										'PENYAJIAN INFORMASI KINERJA',
										'bobot' => '4.5',
										'nomor_urut' => '2.00',
										'id_user_penilai' => '3',
										'data' => array(
											array(
												'nama' => 'Dokumen Laporan Kinerja disusun secara berkualitas sesuai dengan standar.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah mengungkap seluruh informasi tentang pencapaian kinerja.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-2',
														'pesan_kesalahan' => 'SALAH, Laporan kinerja belum diformalkan.'
													)
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan perbandingan realisasi kinerja dengan target tahunan.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-2',
														'pesan_kesalahan' => 'SALAH, Laporan kinerja tidak disusun secara berkala.'
													)
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan perbandingan realisasi kinerja dengan target jangka menengah.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-2',
														'pesan_kesalahan' => 'SALAH, Laporan kinerja tidak disusun secara berkala.'
													)
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan perbandingan realisasi kinerja dengan realisasi kinerja tahun-tahun sebelumnya.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '5.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-3',
														'pesan_kesalahan' => 'SALAH, Laporan kinerja belum direviu.'
													)
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menyajikan informasi keuangan yang terkait dengan pencapaian sasaran kinerja instansi.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '6.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-3',
														'pesan_kesalahan' => 'SALAH, Laporan kinerja belum direviu.'
													)
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan kualitas atas capaian kinerja beserta upaya nyata dan/atau hambatannya.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '7.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-3',
														'pesan_kesalahan' => 'SALAH, Laporan kinerja belum direviu.'
													)
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan efisiensi atas penggunaan sumber daya dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '8.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-3',
														'pesan_kesalahan' => 'SALAH, Laporan kinerja belum direviu.'
													)
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan upaya perbaikan dan penyempurnaan kinerja ke depan (Rekomendasi perbaikan kinerja).',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '9.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-3',
														'pesan_kesalahan' => 'SALAH, Laporan kinerja belum direviu.'
													)
												)
											),
										)

									),
									array(
										'nama' =>
										'PEMANFAATAN INFORMASI KINERJA',
										'bobot' => '7.5',
										'nomor_urut' => '3.00',
										'id_user_penilai' => '3',
										'data' => array(
											array(
												'nama' => 'Informasi dalam laporan kinerja selalu menjadi perhatian utama pimpinan (Bertanggung Jawab).',
												'tipe' => '1',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '2-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Penyajian Informasi Kinerja.'
													)
												)
											),
											array(
												'nama' =>
												'Penyajian informasi dalam laporan kinerja menjadi kepedulian seluruh pegawai.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '2-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Penyajian Informasi Kinerja.'
													)
												)
											),
											array(
												'nama' => 'Informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian aktivitas untuk mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '2-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Penyajian Informasi Kinerja.'
													)
												)
											),
											array(
												'nama' => 'Informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian penggunaan anggaran untuk mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => '- Laporan Kinerja 2023,  - DPA 2024 (dokumen lainnya)',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '2-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Penyajian Informasi Kinerja.'
													)
												)
											),
											array(
												'nama' => 'Informasi dalam laporan kinerja telah digunakan dalam evaluasi pencapaian keberhasilan kinerja.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023, Evaluasi Internal 2023',
												'nomor_urut' => '5.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '2-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Penyajian Informasi Kinerja.'
													)
												)
											),
											array(
												'nama' => 'Informasi dalam laporan kinerja telah digunakan dalam penyesuaian perencanaan kinerja yang akan dihadapi berikutnya.',
												'tipe' => '2',
												'keterangan' => '- Laporan Kinerja 2023, rencana aksi 2024,  - DPA 2024 (dokumen lainnya)',
												'nomor_urut' => '6.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '2-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Penyajian Informasi Kinerja.'
													)
												)
											),
											array(
												'nama' => 'Informasi dalam laporan kinerja selalu mempengaruhi perubahan budaya kinerja organisasi.',
												'tipe' => '2',
												'keterangan' => '- Laporan Kinerja 2023, rencana aksi 2024, evaluasi internal 2024 dan 2023 - DPA 2024 (dokumen lainnya)',
												'nomor_urut' => '7.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '2-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Penyajian Informasi Kinerja.'
													)
												)
											),
										)

									),
								)
							),
							array( //3
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
											array(
												'nama' => 'Telah dilaksanakan Evaluasi Akuntabilitas Kinerja secara berkala.',
												'tipe' => '1',
												'keterangan' => 'Evaluasi Internal 2023 dan 2024',
												'nomor_urut' => '1.00'
											),
										)
									),
									array(
										'nama' => 'KUALITAS EVALUASI',
										'bobot' => '7.5',
										'nomor_urut' => '2.00',
										'id_user_penilai' => '1',
										'data' => array(
											array(
												'nama' => 'Evaluasi Akuntabilitas Kinerja telah dilaksanakan secara berjenjang.',
												'tipe' => '2',
												'keterangan' => 'Evaluasi Internal 2023 dan 2024',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi belum dilaksanakan.'
													)
												)
											),
											array(
												'nama' => 'Evaluasi Akuntabilitas Kinerja telah dilaksanakan dengan pendalaman yang memadai.',
												'tipe' => '2',
												'keterangan' => 'Evaluasi Internal 2023 dan 2024',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi belum dilaksanakan.'
													)
												)
											),
											array(
												'nama' => 'Evaluasi Akuntabilitas Kinerja telah dilaksanakan pada seluruh bidang di OPD.',
												'tipe' => '2',
												'keterangan' => 'Evaluasi Internal 2023 dan 2024',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi belum dilaksanakan.'
													)
												)
											),
											array(
												'nama' => 'Evaluasi Akuntabilitas Kinerja telah dilaksanakan menggunakan Teknologi Informasi (Aplikasi).',
												'tipe' => '2',
												'keterangan' => 'Evaluasi Internal 2023 dan 2024',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi belum dilaksanakan.'
													)
												)
											),
										)
									),
									array(
										'nama' => 'PEMANFAATAN EVALUASI',
										'bobot' => '12.5',
										'nomor_urut' => '3.00',
										'id_user_penilai' => '1',
										'data' => array(
											array(
												'nama' => 'Seluruh rekomendasi atas hasil evaluasi akuntabilitas kinerja (internal dan LHE SAKIP OPD) telah ditindaklanjuti.',
												'tipe' => '2',
												'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi Akuntabilitas belum dilaksanakan.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '3-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Evaluasi'
													)
												)
											),
											array(
												'nama' => 'Telah terjadi peningkatan implementasi SAKIP  (internal dan LHE SAKIP OPD) dengan melaksanakan tindak lanjut atas rekomendasi hasil evaluasi akuntabilitas kinerja.',
												'tipe' => '2',
												'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi Akuntabilitas belum dilaksanakan.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '3-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Evaluasi'
													)
												)
											),
											array(
												'nama' => 'Hasil Evaluasi Akuntabilitas Kinerja  (internal dan LHE SAKIP OPD) telah dimanfaatkan untuk perbaikan dan peningkatan akuntabilitas kinerja.',
												'tipe' => '2',
												'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi Akuntabilitas belum dilaksanakan.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '3-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Evaluasi'
													)
												)
											),
											array(
												'nama' => 'Hasil dari Evaluasi Akuntabilitas Kinerja  (internal dan LHE SAKIP OPD)telah dimanfaatkan dalam mendukung efektivitas dan efisiensi kinerja.',
												'tipe' => '2',
												'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024, DPA 2024',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi Akuntabilitas belum dilaksanakan.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '3-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Evaluasi'
													)
												)
											),
											array(
												'nama' => 'Telah terjadi perbaikan dan peningkatan kinerja dengan memanfaatkan hasil evaluasi akuntabilitas kinerja  (internal dan LHE SAKIP OPD).',
												'tipe' => '2',
												'keterangan' => '- Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024, DPA 2024 - Dokumen lainnya (Inovasi, Prestasi)',
												'nomor_urut' => '5.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi Akuntabilitas belum dilaksanakan.'
													),
													array(
														'jenis_kerangka_logis' => '1',
														'id_komponen_pembanding' => '3-1',
														'pesan_kesalahan' => 'SALAH, Lebih tinggi dari nilai rata-rata Kualitas Evaluasi'
													)
												)
											)
										)
									)
								)
							)
						);

						foreach ($design as $k => $komponen) {
							$komponen_baru = array(
								'id_jadwal' => $id_jadwal_baru,
								'nomor_urut' => $komponen['nomor_urut'],
								'id_user_penilai' => $komponen['id_user_penilai'],
								'nama' => $komponen['nama'],
								'bobot' => $komponen['bobot'],
							);
							if ($wpdb->insert('esakip_komponen', $komponen_baru) === false) {
								error_log("Error inserting into esakip_komponen: " . $wpdb->last_error);
								continue;
							}
							$id_komponen_baru = $wpdb->insert_id;
							$design[$k]['id'] = $id_komponen_baru;

							foreach ($komponen['data'] as $kk => $subkomponen) {
								$subkomponen_baru = array(
									'id_komponen' => $id_komponen_baru,
									'nomor_urut' => $subkomponen['nomor_urut'],
									'id_user_penilai' => $subkomponen['id_user_penilai'],
									'nama' => $subkomponen['nama'],
									'bobot' => $subkomponen['bobot'],
								);
								if ($wpdb->insert('esakip_subkomponen', $subkomponen_baru) === false) {
									error_log("Error inserting into esakip_subkomponen: " . $wpdb->last_error);
									continue;
								}
								$id_subkomponen_baru = $wpdb->insert_id;
								$design[$k]['data'][$kk]['id'] = $id_subkomponen_baru;

								foreach ($subkomponen['data'] as $kkk => $penilaian) {
									$komponen_penilaian_baru = array(
										'id_subkomponen' => $id_subkomponen_baru,
										'nomor_urut' => $penilaian['nomor_urut'],
										'nama' => $penilaian['nama'],
										'tipe' => $penilaian['tipe'],
										'keterangan' => $penilaian['keterangan'],
									);
									if ($wpdb->insert('esakip_komponen_penilaian', $komponen_penilaian_baru) === false) {
										error_log("Error inserting into esakip_komponen_penilaian: " . $wpdb->last_error);
										continue;
									}
									$id_komponen_penilaian_baru = $wpdb->insert_id;
									$design[$k]['data'][$kk]['data'][$kkk]['id'] = $id_komponen_penilaian_baru;

									if (isset($penilaian['data']) && is_array($penilaian['data'])) {
										// Save kerangka logis
										foreach ($penilaian['data'] as $kkkk => $kerangka_logis) {
											$id_pembanding = explode('-', $kerangka_logis['id_komponen_pembanding']);
											if ($kerangka_logis['jenis_kerangka_logis'] == 1) {
												$id_pembanding = $design[$id_pembanding[0]]['data'][$id_pembanding[1]]['id'];
											} else if ($kerangka_logis['jenis_kerangka_logis'] == 2) {
												$id_pembanding = $design[$id_pembanding[0]]['data'][$id_pembanding[1]]['data'][$id_pembanding[2]]['id'];
											}
											$kerangka_logis_baru = array(
												'id_komponen_penilaian' => $id_komponen_penilaian_baru,
												'jenis_kerangka_logis' => $kerangka_logis['jenis_kerangka_logis'],
												'pesan_kesalahan' => $kerangka_logis['pesan_kesalahan'],
												'id_komponen_pembanding' => $id_pembanding
											);

											if ($wpdb->insert('esakip_kontrol_kerangka_logis', $kerangka_logis_baru) === false) {
												error_log("Error inserting into esakip_kontrol_kerangka_logis: " . $wpdb->last_error);
												error_log("Kerangka logis data: " . print_r($kerangka_logis_baru, true));
											}
										}
									} else {
										error_log("No kerangka logis data for penilaian ID: " . $id_komponen_penilaian_baru);
									}
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
								'tahun_anggaran',
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

	/** Ambil data penjadwalan RPJPD */
	public function get_data_penjadwalan_rpjpd()
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
					$where = " WHERE tipe = 'RPJPD' AND status != 0";

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

	/** Submit data penjadwalan RPJPD */
	public function submit_jadwal_rpjpd()
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
						'tipe'					=> 'RPJPD',
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


	/** Submit edit data penjadwalan RPJPD*/
	public function submit_edit_jadwal_rpjpd()
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
						'tipe'					=> 'RPJPD',
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


	public function delete_jadwal_rpjpd()
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
				// Periksa apakah data dengan ID yang akan dihapus ada di tabel esakip_rpjpd
				$cek_id_jadwal_rpjpd = $wpdb->get_var($wpdb->prepare('
                    SELECT id_jadwal
                    FROM esakip_rpjpd
                    WHERE id_jadwal=%d
                    	AND active=1
                ', $id));
				if ($cek_id_jadwal_rpjpd) {
					// Jika data dengan ID yang sama ditemukan di tabel esakip_rpjpd, tampilkan pesan
					$ret['status'] = 'confirm';
					$ret['message'] = 'Jadwal tidak bisa dihapus karena sudah terpakai di dokumen RPJPD.';
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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

	public function get_table_tahun_laporan_monev_renaksi()
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
					FROM esakip_laporan_monev_renaksi 
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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

	public function get_table_skpd_laporan_monev_renaksi()
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
						$detail_laporan_monev_renaksi = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen Laporan Monev Renaksi ' . $tahun_anggaran,
							'content' => '[dokumen_detail_laporan_monev_renaksi tahun=' . $tahun_anggaran . ']',
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
								FROM esakip_laporan_monev_renaksi 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_laporan_monev_renaksi['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
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

	public function submit_tahun_laporan_monev_renaksi()
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
							FROM esakip_laporan_monev_renaksi 
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_laporan_monev_renaksi',
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

	public function get_table_laporan_monev_renaksi()
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
				$laporan_monev_renaksis = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_laporan_monev_renaksi
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($laporan_monev_renaksis)) {
					$counter = 1;
					$tbody = '';

					foreach ($laporan_monev_renaksis as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_laporan_monev_renaksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_laporan_monev_renaksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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

	public function get_detail_laporan_monev_renaksi_by_id()
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
							FROM esakip_laporan_monev_renaksi
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

	public function tambah_dokumen_laporan_monev_renaksi()
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
							'esakip_laporan_monev_renaksi',
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
								FROM esakip_laporan_monev_renaksi
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_laporan_monev_renaksi',
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

	public function hapus_dokumen_laporan_monev_renaksi()
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
							FROM esakip_laporan_monev_renaksi
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_laporan_monev_renaksi',
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

	public function get_table_pedoman_teknis_perencanaan()
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
				$pedoman_teknis_perencanaans = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_pedoman_teknis_perencanaan
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($pedoman_teknis_perencanaans)) {
					$counter = 1;
					$tbody = '';

					foreach ($pedoman_teknis_perencanaans as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_pedoman_teknis_perencanaan(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_pedoman_teknis_perencanaan(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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

	public function get_detail_pedoman_teknis_perencanaan_by_id()
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
							FROM esakip_pedoman_teknis_perencanaan
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

	public function tambah_dokumen_pedoman_teknis_perencanaan()
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
							'esakip_pedoman_teknis_perencanaan',
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
								FROM esakip_pedoman_teknis_perencanaan
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_pedoman_teknis_perencanaan',
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

	public function hapus_dokumen_pedoman_teknis_perencanaan()
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
							FROM esakip_pedoman_teknis_perencanaan
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_pedoman_teknis_perencanaan',
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

	public function get_table_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja()
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
				$pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerjas = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerjas)) {
					$counter = 1;
					$tbody = '';

					foreach ($pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerjas as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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

	public function get_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_by_id()
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
							FROM esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja
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

	public function tambah_dokumen_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja()
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
							'esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja',
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
								FROM esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja',
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

	public function hapus_dokumen_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja()
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
							FROM esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja',
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

	public function get_table_pedoman_teknis_evaluasi_internal()
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
				$pedoman_teknis_evaluasi_internals = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_pedoman_teknis_evaluasi_internal
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($pedoman_teknis_evaluasi_internals)) {
					$counter = 1;
					$tbody = '';

					foreach ($pedoman_teknis_evaluasi_internals as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_pedoman_teknis_evaluasi_internal(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_pedoman_teknis_evaluasi_internal(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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

	public function get_detail_pedoman_teknis_evaluasi_internal_by_id()
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
							FROM esakip_pedoman_teknis_evaluasi_internal
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

	public function tambah_dokumen_pedoman_teknis_evaluasi_internal()
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
							'esakip_pedoman_teknis_evaluasi_internal',
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
								FROM esakip_pedoman_teknis_evaluasi_internal
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_pedoman_teknis_evaluasi_internal',
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

	public function hapus_dokumen_pedoman_teknis_evaluasi_internal()
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
							FROM esakip_pedoman_teknis_evaluasi_internal
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_pedoman_teknis_evaluasi_internal',
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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

	public function get_table_tahun_dpa()
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
					FROM esakip_dpa
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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

	public function get_table_skpd_dpa()
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
						$detail_dpa = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Detail Dokumen DPA ' . $tahun_anggaran,
							'content' => '[dokumen_detail_dpa tahun=' . $tahun_anggaran . ']',
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
								FROM esakip_dpa 
								WHERE id_skpd = %d
								AND tahun_anggaran = %d
								AND active = 1
								",
								$vv['id_skpd'],
								$tahun_anggaran
							)
						);

						$btn = '<div class="btn-action-group">';
						$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_dpa['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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
						$tbody .= "<td style='text-transform: uppercase;'>" . $vv['nama_skpd'] . "</a></td>";

						$jumlah_dokumen = $wpdb->get_var(
							$wpdb->prepare("
								SELECT 
									COUNT(id)
								FROM esakip_renstra
								WHERE id_skpd = %d
								  AND id_jadwal = %d
								  AND active = 1
							", $vv['id_skpd'], $id_jadwal)
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

	public function get_table_skpd_dokumen()
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
				if (!empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe Dokumen kosong!';
				}

				if ($ret['status'] == 'success') {
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

					// untuk mengatur judul halaman sesuai tipe dokumen
					$nama_page = array(
						"pohon_kinerja_dan_cascading" => "Pohon Kinerja dan Cascading",
						"pedoman_teknis_perencanaan" => "Pedoman Teknis Perencanaan",
						"pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja" => "Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja",
						"pedoman_teknis_evaluasi_internal" => "Pedoman Teknis Evaluasi Internal",
						"lhe_akip_internal" => "LHE AKIP Internal",
						"tl_lhe_akip_internal" => "TL LHE AKIP Internal",
						"tl_lhe_akip_kemenpan" => "TL LHE AKIP Kemenpan",
						"dpa" => "DPA"
					);

					// untuk mengatur tabel sesuai tipe dokumen
					$nama_tabel = array(
						"pohon_kinerja_dan_cascading" => "esakip_pohon_kinerja_dan_cascading",
						"pedoman_teknis_perencanaan" => "esakip_pedoman_teknis_perencanaan",
						"pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja" => "esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja",
						"pedoman_teknis_evaluasi_internal" => "esakip_pedoman_teknis_evaluasi_internal",
						"lhe_akip_internal" => "esakip_lhe_akip_internal",
						"tl_lhe_akip_internal" => "esakip_tl_lhe_akip_internal",
						"tl_lhe_akip_kemenpan" => "esakip_tl_lhe_akip_kemenpan",
						"dpa" => "esakip_dpa"
					);

					if (!empty($unit)) {
						$tbody = '';
						$counter = 1;
						foreach ($unit as $kk => $vv) {
							$detail_dokumen = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Detail Dokumen ' . $nama_page[$tipe_dokumen] . ' ' . $tahun_anggaran,
								'content' => '[dokumen_detail_' . $tipe_dokumen . ' tahun=' . $tahun_anggaran . ']',
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
									FROM $nama_tabel[$tipe_dokumen]
									WHERE id_skpd = %d
									AND tahun_anggaran = %d
									AND active = 1
									",
									$vv['id_skpd'],
									$tahun_anggaran
								)
							);

							$btn = '<div class="btn-action-group">';
							$btn .= "<button class='btn btn-secondary' onclick='toDetailUrl(\"" . $detail_dokumen['url'] . '&id_skpd=' . $vv['id_skpd'] . "\");' title='Detail'><span class='dashicons dashicons-controls-forward'></span></button>";
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

	public function get_table_tahun_dokumen()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe Dokumen kosong!';
				}

				if ($ret['status'] == 'success') {
					// untuk mengatur tabel sesuai tipe dokumen
					$nama_tabel = array(
						"pohon_kinerja_dan_cascading" => "esakip_pohon_kinerja_dan_cascading",
						"pedoman_teknis_perencanaan" => "esakip_pedoman_teknis_perencanaan",
						"pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja" => "esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja",
						"pedoman_teknis_evaluasi_internal" => "esakip_pedoman_teknis_evaluasi_internal",
						"lhe_akip_internal" => "esakip_lhe_akip_internal",
						"tl_lhe_akip_internal" => "esakip_tl_lhe_akip_internal",
						"tl_lhe_akip_kemenpan" => "esakip_tl_lhe_akip_kemenpan",
						"dpa" => "esakip_dpa"
					);

					$where = 'tahun_anggaran IS NULL';

					if (!empty($_POST['id_skpd'])) {
						$id_skpd = $_POST['id_skpd'];
						$where .= " AND id_skpd = $id_skpd";
					}

					$dokumen_unset = $wpdb->get_results(
						"
						SELECT 
							*
						FROM $nama_tabel[$tipe_dokumen] 
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
							if (!$this->is_admin_panrb()) {
								$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
							}
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
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message'   => 'Format tidak sesuai!'
			);
		}
		die(json_encode($ret));
	}

	public function submit_tahun_dokumen()
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
				if (!empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe Dokumen kosong!';
				}

				if ($ret['status'] == 'success') {
					if (!empty($id) && !empty($tahun_anggaran)) {
						// untuk mengatur tabel sesuai tipe dokumen
						$nama_tabel = array(
							"pohon_kinerja_dan_cascading" => "esakip_pohon_kinerja_dan_cascading",
							"pedoman_teknis_perencanaan" => "esakip_pedoman_teknis_perencanaan",
							"pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja" => "esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja",
							"pedoman_teknis_evaluasi_internal" => "esakip_pedoman_teknis_evaluasi_internal",
							"lhe_akip_internal" => "esakip_lhe_akip_internal",
							"tl_lhe_akip_internal" => "esakip_tl_lhe_akip_internal",
							"tl_lhe_akip_kemenpan" => "esakip_tl_lhe_akip_kemenpan",
							"dpa" => "esakip_dpa"
						);

						$existing_data = $wpdb->get_row(
							$wpdb->prepare("
								SELECT 
									* 
								FROM $nama_tabel[$tipe_dokumen]
								WHERE id = %d", $id)
						);

						if (!empty($existing_data)) {
							$update_result = $wpdb->update(
								$nama_tabel[$tipe_dokumen],
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

	public function get_user_penilai()
	{
		return array(
			'1' => 'Admin Inspektorat',
			'2' => 'Admin Perencanaan',
			'3' => 'Admin Ortala'
		);
	}

	public function get_table_kerangka_logis()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_komponen_penilaian'])) {
					$id_komponen_penilaian = $_POST['id_komponen_penilaian'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Komponen Penilaian kosong!';
				}
				$kerangka_logises = $wpdb->get_results(
					$wpdb->prepare("
						SELECT * 
						FROM esakip_kontrol_kerangka_logis
						WHERE id_komponen_penilaian = %d
						  AND active = 1
						", $id_komponen_penilaian),
					ARRAY_A
				);
				$tbody = '';
				$counter = 1;
				if (!empty($kerangka_logises)) {
					foreach ($kerangka_logises as $kerangka_logis) {
						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_kerangka_logis(\'' . $kerangka_logis['id'] . '\'); return false;" href="#" title="Hapus Kerangka Logis"><span class="dashicons dashicons-no-alt"></span></button>';
						$btn .= '</div>';

						$tbody .= '<tr>';
						$tbody .= '<td class="text-left">' . $counter++ . '</td>';
						if ($kerangka_logis['jenis_kerangka_logis'] == 1) {
							$subkomponen = $wpdb->get_var(
								$wpdb->prepare("
									SELECT nama
									FROM esakip_subkomponen
									WHERE id=%d
									AND active = 1
								", $kerangka_logis['id_komponen_pembanding'])
							);
							$tbody .= '<td class="text-left">Rata Rata</td>';
							$tbody .= '<td class="text-left">' . $subkomponen . '</td>';
						} else if ($kerangka_logis['jenis_kerangka_logis'] == 2) {
							$penilaian = $wpdb->get_var(
								$wpdb->prepare("
									SELECT nama	
									FROM esakip_komponen_penilaian
									WHERE id=%d
									  AND active = 1
								", $kerangka_logis['id_komponen_pembanding'])
							);
							$tbody .= '<td class="text-left">Nilai</td>';
							$tbody .= '<td class="text-left">' . $penilaian . '</td>';
						}
						$tbody .= '<td class="text-left">' . $kerangka_logis['pesan_kesalahan'] . '</td>';
						$tbody .= '<td class="text-left">' . $btn . '</td>';
						$tbody .= '</tr>';
					}
				} else {
					$tbody .= "<tr><td colspan='5' class='text-center'>Tidak ada data tersedia</td></tr>";
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
						$tbody .= "<td class='text-left'colspan='2'></td>";
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
								$tbody .= "<td class='text-left' colspan='2'>User Penilai: <b>" . $user_penilai[$subkomponen['id_user_penilai']] . "</b></td>";
								$tbody .= "<td class='text-center'>" . $btn . "</td>";
								$tbody .= "</tr>";

								$data_komponen_penilaian = $wpdb->get_results(
									$wpdb->prepare("
										SELECT 
											kp.id AS kp_id,
											kp.id_subkomponen,
											kp.nomor_urut,
											kp.nama AS kp_nama,
											kp.tipe,
											kp.keterangan AS kp_keterangan,
											kp.jenis_bukti_dukung,
											kp.active AS kp_active,
											kl.id AS kl_id,
											kl.id_komponen_penilaian,
											kl.jenis_kerangka_logis,
											kl.id_komponen_pembanding,
											kl.pesan_kesalahan,
											kl.active AS kl_active
										FROM esakip_komponen_penilaian AS kp
										LEFT JOIN esakip_kontrol_kerangka_logis AS kl
										   ON kp.id = kl.id_komponen_penilaian
										  AND kl.active = 1
										WHERE kp.id_subkomponen = %d 
										  AND kp.active = 1
										ORDER BY kp.nomor_urut ASC
									", $subkomponen['id']),
									ARRAY_A
								);

								// Group kerangka logis data by kp_id
								$grouped_data = array();
								foreach ($data_komponen_penilaian as $row) {
									$kp_id = $row['kp_id'];
									if (!isset($grouped_data[$kp_id])) {
										$grouped_data[$kp_id] = [
											'kp_id' => $row['kp_id'],
											'kp_nama' => $row['kp_nama'],
											'kp_tipe' => $row['tipe'],
											'kp_keterangan' => $row['kp_keterangan'],
											'kerangka_logis' => []
										];
									}
									if (!is_null($row['kl_id'])) {
										$grouped_data[$kp_id]['kerangka_logis'][] = [
											'jenis_kerangka_logis' => $row['jenis_kerangka_logis'],
											'pesan_kesalahan' => $row['pesan_kesalahan']
										];
									}
								}

								// Render the data
								if (!empty($grouped_data)) {
									foreach ($grouped_data as $penilaian) {
										$btn = '';

										$btn .= '<div class="btn-action-group">';
										$btn .= "<button class='btn btn-info' onclick='tambah_kerangka_logis(\"" . $penilaian['kp_id'] . "\");' title='Tambah Kerangka Logis'><span class='dashicons dashicons-admin-generic'></span></button>";
										$btn .= "<button class='btn btn-warning' onclick='edit_data_komponen_penilaian(\"" . $penilaian['kp_id'] . "\");' title='Edit Data'><span class='dashicons dashicons-edit'></span></button>";
										$btn .= "<button class='btn btn-danger' onclick='hapus_data_komponen_penilaian(\"" . $penilaian['kp_id'] . "\");' title='Hapus Data'><span class='dashicons dashicons-trash'></span>";
										$btn .= '</div>';

										$tbody .= "<tr>";
										$tbody .= "<td class='text-left'></td>";
										$tbody .= "<td class='text-left'></td>";
										$tbody .= "<td class='text-left'>" . $counter_isi++ . "</td>";
										$tbody .= "<td class='text-left'>" . $penilaian['kp_nama'] . "</td>";
										$tbody .= "<td class='text-center'></td>";

										if ($penilaian['kp_tipe'] == 1) {
											$tbody .= "<td class='text-center'>Y/T</td>";
										} else if ($penilaian['kp_tipe'] == 2) {
											$tbody .= "<td class='text-center'>A/B/C/D/E</td>";
										}

										$tbody .= "<td class='text-left'>" . $penilaian['kp_keterangan'] . "</td>";

										// Render kerangka logis as ul and li, if any
										$tbody .= "<td class='text-left'><ul>";
										if (!empty($penilaian['kerangka_logis'])) {
											foreach ($penilaian['kerangka_logis'] as $kl) {
												$kerangka_logis_text = $kl['jenis_kerangka_logis'] == 1 ? 'Rata-Rata' : 'Nilai';
												$tbody .= "<li>" . $kerangka_logis_text . " : " . $kl['pesan_kesalahan'] . "</li>";
											}
										} else {
											$tbody .= "<li>Tidak ada kerangka logis</li>";
										}
										$tbody .= "</ul></td>";

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
					$tbody .= "<td colspan='9' class='text-center'>";
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

		$jadwal_periode_rpjpd = $wpdb->get_results(
			"
			SELECT 
				id,
				nama_jadwal,
				tahun_anggaran,
				lama_pelaksanaan
			FROM esakip_data_jadwal
			WHERE tipe = 'RPJPD'
			  AND status = 1",
			ARRAY_A
		);
		$periode_rpjpd = '';
		foreach ($jadwal_periode_rpjpd as $jadwal_periode_item_rpjpd) {
			$tahun_anggaran_selesai = $jadwal_periode_item_rpjpd['tahun_anggaran'] + $jadwal_periode_item_rpjpd['lama_pelaksanaan'];

			$rpjpd = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Upload Dokumen RPJPD ' . $jadwal_periode_item_rpjpd['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item_rpjpd['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[upload_dokumen_rpjpd periode=' . $jadwal_periode_item_rpjpd['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$periode_rpjpd .= '<li><a target="_blank" href="' . $rpjpd['url'] . '" class="btn btn-primary">' . $rpjpd['title'] . '</a></li>';
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
				'nama_page' => 'Halaman Pengisian LKE ' . $get_jadwal_lke_sakip['nama_jadwal'],
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
		if (empty($periode_rpjpd)) {
			$periode_rpjpd = '<li><a return="false" href="#" class="btn btn-secondary">Periode RPJPD kosong atau belum dibuat</a></li>';
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
		$halaman_rpjpd = '
			<div class="accordion">
				<h5 class="esakip-header-tahun" data-id="rpjpd" style="margin: 0;">Periode Upload Dokumen RPJPD</h5>
				<div class="esakip-body-tahun" data-id="rpjpd">
					<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
						' . $periode_rpjpd . '
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
			|| in_array("admin_panrb", $user_meta->roles)
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
				'nama_page' => 'Halaman Dokumen LKJIP/LPPD Tahun ' . $_GET['tahun'],
				'content' => '[lkjip_lppd tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$dpa = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen DPA Tahun ' . $_GET['tahun'],
				'content' => '[dpa tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$pohon_kinerja_dan_cascading = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Pohon Kinerja dan Cascading Tahun ' . $_GET['tahun'],
				'content' => '[pohon_kinerja_dan_cascading tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$lhe_akip_internal = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen LHE AKIP Internal Tahun ' . $_GET['tahun'],
				'content' => '[lhe_akip_internal tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$tl_lhe_akip_internal = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen TL LHE AKIP Internal Tahun ' . $_GET['tahun'],
				'content' => '[tl_lhe_akip_internal tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$tl_lhe_akip_kemenpan = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen TL LHE AKIP Kemenpan Tahun ' . $_GET['tahun'],
				'content' => '[tl_lhe_akip_kemenpan tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$laporan_monev_renaksi = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Monev Renaksi Tahun ' . $_GET['tahun'],
				'content' => '[laporan_monev_renaksi tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$pedoman_teknis_perencanaan = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Pedoman Teknis Perencanaan Tahun ' . $_GET['tahun'],
				'content' => '[pedoman_teknis_perencanaan tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja Tahun ' . $_GET['tahun'],
				'content' => '[pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$pedoman_teknis_evaluasi_internal = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Dokumen Pedoman Teknis Evaluasi Internal Tahun ' . $_GET['tahun'],
				'content' => '[pedoman_teknis_evaluasi_internal tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			echo '
				<ul class="daftar-menu-sakip">
					<li>' . $halaman_lke . '</li>
					<li>' . $halaman_rpjpd . '</li>
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
					<li><a href="' . $dpa['url'] . '" target="_blank" class="btn btn-primary">' . $dpa['title'] . '</a></li>
					<li><a href="' . $pohon_kinerja_dan_cascading['url'] . '" target="_blank" class="btn btn-primary">' . $pohon_kinerja_dan_cascading['title'] . '</a></li>
					<li><a href="' . $lhe_akip_internal['url'] . '" target="_blank" class="btn btn-primary">' . $lhe_akip_internal['title'] . '</a></li>
					<li><a href="' . $tl_lhe_akip_internal['url'] . '" target="_blank" class="btn btn-primary">' . $tl_lhe_akip_internal['title'] . '</a></li>
					<li><a href="' . $tl_lhe_akip_kemenpan['url'] . '" target="_blank" class="btn btn-primary">' . $tl_lhe_akip_kemenpan['title'] . '</a></li>
					<li><a href="' . $laporan_monev_renaksi['url'] . '" target="_blank" class="btn btn-primary">' . $laporan_monev_renaksi['title'] . '</a></li>
					<li><a href="' . $pedoman_teknis_perencanaan['url'] . '" target="_blank" class="btn btn-primary">' . $pedoman_teknis_perencanaan['title'] . '</a></li>
					<li><a href="' . $pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja['url'] . '" target="_blank" class="btn btn-primary">' . $pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja['title'] . '</a></li>
					<li><a href="' . $pedoman_teknis_evaluasi_internal['url'] . '" target="_blank" class="btn btn-primary">' . $pedoman_teknis_evaluasi_internal['title'] . '</a></li>
				</ul>';
		} else if (
			in_array("pa", $user_meta->roles)
			|| in_array("kpa", $user_meta->roles)
			|| in_array("plt", $user_meta->roles)
		) {
			$nipkepala = get_user_meta($user_id, '_nip') ?: get_user_meta($user_id, 'nip');
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
					'nama_page' => 'Halaman Pengisian LKE ' . $skpd_db['nama_skpd'] . ' ' . $get_jadwal_lke_sakip['nama_jadwal'],
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
			$detail_dpa = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen DPA ' . $_GET['tahun'],
				'content' => '[dokumen_dpa tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_dpa['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_pohon_kinerja_dan_cascading = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen Pohon Kinerja dan Cascading  ' . $_GET['tahun'],
				'content' => '[dokumen_detail_pohon_kinerja_dan_cascading tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_pohon_kinerja_dan_cascading['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_lhe_akip_internal = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen LHE AKIP Internal  ' . $_GET['tahun'],
				'content' => '[dokumen_detail_lhe_akip_internal tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_lhe_akip_internal['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_tl_lhe_akip_internal = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen TL LHE AKIP Internal  ' . $_GET['tahun'],
				'content' => '[dokumen_detail_tl_lhe_akip_internal tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_tl_lhe_akip_internal['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_tl_lhe_akip_kemenpan = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen TL LHE AKIP Kemenpan  ' . $_GET['tahun'],
				'content' => '[dokumen_detail_tl_lhe_akip_kemenpan tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_tl_lhe_akip_kemenpan['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_laporan_monev_renaksi = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen Laporan Monev Renaksi  ' . $_GET['tahun'],
				'content' => '[dokumen_detail_laporan_monev_renaksi tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_laporan_monev_renaksi['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_pedoman_teknis_perencanaan = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen Pedoman Teknis Perencanaan  ' . $_GET['tahun'],
				'content' => '[dokumen_detail_pedoman_teknis_perencanaan tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_pedoman_teknis_perencanaan['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja  ' . $_GET['tahun'],
				'content' => '[dokumen_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
			$detail_pedoman_teknis_evaluasi_internal = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Detail Dokumen Pedoman Teknis Evaluasi Internal  ' . $_GET['tahun'],
				'content' => '[dokumen_detail_pedoman_teknis_evaluasi_internal tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$detail_pedoman_teknis_evaluasi_internal['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
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
					<li><a href="' . $detail_dpa['url'] . '" target="_blank" class="btn btn-primary">' . $detail_dpa['title'] . '</a></li>
					<li><a href="' . $detail_pohon_kinerja_dan_cascading['url'] . '" target="_blank" class="btn btn-primary">' . $detail_pohon_kinerja_dan_cascading['title'] . '</a></li>
					<li><a href="' . $detail_lhe_akip_internal['url'] . '" target="_blank" class="btn btn-primary">' . $detail_lhe_akip_internal['title'] . '</a></li>
					<li><a href="' . $detail_tl_lhe_akip_internal['url'] . '" target="_blank" class="btn btn-primary">' . $detail_tl_lhe_akip_internal['title'] . '</a></li>
					<li><a href="' . $detail_tl_lhe_akip_kemenpan['url'] . '" target="_blank" class="btn btn-primary">' . $detail_tl_lhe_akip_kemenpan['title'] . '</a></li>
					<li><a href="' . $detail_laporan_monev_renaksi['url'] . '" target="_blank" class="btn btn-primary">' . $detail_laporan_monev_renaksi['title'] . '</a></li>
					<li><a href="' . $detail_pedoman_teknis_perencanaan['url'] . '" target="_blank" class="btn btn-primary">' . $detail_pedoman_teknis_perencanaan['title'] . '</a></li>
					<li><a href="' . $detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja['url'] . '" target="_blank" class="btn btn-primary">' . $detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja['title'] . '</a></li>
					<li><a href="' . $detail_pedoman_teknis_evaluasi_internal['url'] . '" target="_blank" class="btn btn-primary">' . $detail_pedoman_teknis_evaluasi_internal['title'] . '</a></li>
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
				// if (!empty($_POST['user_penilai'])) {
				// 	$user_penilai = $_POST['user_penilai'];
				// } else {
				// 	$ret['status'] = 'error';
				// 	$ret['message'] = 'User Penilai kosong!';
				// }

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
									// 'id_user_penilai' => $user_penilai,
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
									// 'id_user_penilai' => $user_penilai,
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
				if (!empty($_POST['bukti_dukung'])) {
					$bukti_dukung = $_POST['bukti_dukung'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Jenis Bukti Dukung kosong!';
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
								'jenis_bukti_dukung' => $bukti_dukung,
							),
							array('id' => $id_komponen_penilaian),
							array('%d', '%s', '%s', '%f', '%s', '%s'),
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
								'jenis_bukti_dukung' => $bukti_dukung,
								'active' => 1,
							),
							array('%d', '%s', '%s', '%f', '%s', '%d', '%s')
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

	public function tambah_kerangka_logis_penilaian_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah kerangka logis!',
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id'])) {
					$id_komponen_penilaian = $_POST['id'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Komponen Penilaian kosong!';
				}
				if (!empty($_POST['jenis_kerangka_logis'])) {
					$jenis_kerangka_logis = $_POST['jenis_kerangka_logis'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Jenis Kerangka Logis kosong!';
				}
				if (!empty($_POST['pesan_kesalahan'])) {
					$pesan_kesalahan = $_POST['pesan_kesalahan'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Pesan Kesalahan kosong!';
				}
				if (!empty($_POST['komponen_pembanding'])) {
					$komponen_pembanding = $_POST['komponen_pembanding'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Komponen Pembanding kosong!';
				}
				if ($jenis_kerangka_logis == 2) {
					if ($id_komponen_penilaian == $komponen_pembanding) {
						$ret['status'] = 'error';
						$ret['message'] = 'Tidak dapat dibandingkan dengan nilai penilaian itu sendiri!';
						die(json_encode($ret));
					}
				}
				if ($ret['status'] === 'success') {
					$wpdb->insert(
						'esakip_kontrol_kerangka_logis',
						array(
							'id_komponen_penilaian' => $id_komponen_penilaian,
							'jenis_kerangka_logis' => $jenis_kerangka_logis,
							'pesan_kesalahan' => $pesan_kesalahan,
							'id_komponen_pembanding' => $komponen_pembanding,
						),
						array('%d', '%d', '%s', '%d'),
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
						$jenis_bukti_dukung = $data['jenis_bukti_dukung'];
						if (!empty($jenis_bukti_dukung) && is_string($jenis_bukti_dukung)) {
							$data['jenis_bukti_dukung'] = json_decode(stripslashes($jenis_bukti_dukung), true);
							if (json_last_error() !== JSON_ERROR_NONE) {
								$data['jenis_bukti_dukung'] = array(); // Fallback to empty array if JSON decoding fails
							}
						} else {
							$data['jenis_bukti_dukung'] = array();
						}
						$merged_data['data'] = $data;
						$merged_data['subkomponen'] = $data_subkomponen;
						$merged_data['komponen'] = $data_komponen;

						$ret['data'] = $merged_data;
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

	function get_subkomponen_pembanding()
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
				$options = '<option value="" selected disabled>Pilih Subkomponen</option>';

				$komponens = $wpdb->get_results(
					$wpdb->prepare("
						SELECT id
						FROM esakip_komponen
						WHERE active = 1 
						  AND id_jadwal = %s
					", $id_jadwal),
					ARRAY_A
				);
				if (!empty($komponens)) {
					foreach ($komponens as $komponen) {
						$subkomponens = $wpdb->get_results(
							$wpdb->prepare("
								SELECT 
									id,
									nama
								FROM esakip_subkomponen
								WHERE active = 1 
								  AND id_komponen = %s
							", $komponen['id']),
							ARRAY_A
						);
						if (!empty($subkomponens)) {
							foreach ($subkomponens as $subkomponen) {
								$options .= '<option value="' . $subkomponen['id'] . '">' . $subkomponen['nama'] . '</option>';
							}
						} else {
							$ret['status'] = 'error';
							$ret['message'] = 'Subkomponen aktif tidak ditemukan';
						}
					}
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Komponen aktif tidak ditemukan';
				}

				$ret['data'] = $options;
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

	function get_komponen_penilaian_pembanding()
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
				$options = '<option value="" selected disabled>Pilih Komponen Penilaian</option>';

				$komponens = $wpdb->get_results(
					$wpdb->prepare("
						SELECT id
						FROM esakip_komponen
						WHERE active = 1 
						  AND id_jadwal = %s
					", $id_jadwal),
					ARRAY_A
				);
				if (!empty($komponens)) {
					foreach ($komponens as $komponen) {
						$subkomponens = $wpdb->get_results(
							$wpdb->prepare("
								SELECT 
									id,
									nama
								FROM esakip_subkomponen
								WHERE active = 1 
								  AND id_komponen = %s
							", $komponen['id']),
							ARRAY_A
						);
						if (!empty($subkomponens)) {
							foreach ($subkomponens as $subkomponen) {
								$penilaians = $wpdb->get_results(
									$wpdb->prepare("
										SELECT 
											id,
											nama
										FROM esakip_komponen_penilaian
										WHERE active = 1 
										  AND id_subkomponen = %s
									", $subkomponen['id']),
									ARRAY_A
								);
								if (!empty($penilaians)) {
									foreach ($penilaians as $penilaians) {
										$options .= '<option value="' . $penilaians['id'] . '">' . $penilaians['nama'] . '</option>';
									}
								} else {
									$ret['status'] = 'error';
									$ret['message'] = 'Komponen Penilaian aktif tidak ditemukan';
								}
							}
						} else {
							$ret['status'] = 'error';
							$ret['message'] = 'Subkomponen aktif tidak ditemukan';
						}
					}
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Komponen tidak ditemukan';
				}

				$ret['data'] = $options;
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

	public function hapus_kerangka_logis()
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
						'esakip_kontrol_kerangka_logis',
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
						$nipkepala = get_user_meta($user_id, '_nip') ?: get_user_meta($user_id, 'nip');
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

							if ($v['is_skpd'] == 1) {
								$list_skpd_options .= '<option value="' . $v['id_skpd'] . '">' . $v['kode_skpd'] . ' ' . $v['nama_skpd'] . '</option>';
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

	public function get_table_rpjpd()
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
				$rpjpds = $wpdb->get_results(
					$wpdb->prepare("
						SELECT * 
						FROM esakip_rpjpd
						WHERE id_jadwal = %d 
						  AND active = 1
					", $id_jadwal),
					ARRAY_A
				);

				if (!empty($rpjpds)) {
					$counter = 1;
					$tbody = '';

					foreach ($rpjpds as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_rpjpd(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_rpjpd(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
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


	public function get_table_tahun_rpjpd()
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
					FROM esakip_rpjpd 
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
						if (!$this->is_admin_panrb()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";
						}
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

	public function submit_tambah_dokumen()
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
				if (!empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe Dokumen kosong!';
				}
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
					);
					if ($upload['status'] == false) {
						$ret = array(
							'status' => 'error',
							'message' => $upload['message']
						);
					}
				}

				if ($ret['status'] == 'success') {
					// untuk mengatur tabel sesuai tipe dokumen
					$nama_tabel = array(
						"pohon_kinerja_dan_cascading" => "esakip_pohon_kinerja_dan_cascading",
						"lhe_akip_internal" => "esakip_lhe_akip_internal",
						"tl_lhe_akip_internal" => "esakip_tl_lhe_akip_internal",
						"tl_lhe_akip_kemenpan" => "esakip_tl_lhe_akip_kemenpan"
					);

					if (empty($id_dokumen)) {
						$wpdb->insert(
							$nama_tabel[$tipe_dokumen],
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
								FROM $nama_tabel[$tipe_dokumen]
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							$nama_tabel[$tipe_dokumen],
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

	public function tambah_dokumen_rpjpd()
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
							'esakip_rpjpd',
							array(
								'dokumen' => $upload['filename'],
								'keterangan' => $keterangan,
								'id_jadwal' => $id_jadwal,
								'created_at' => current_time('mysql'),
								'tanggal_upload' => current_time('mysql')
							),
							array('%s', '%s', '%s', '%s', '%s')
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
								FROM esakip_rpjpd
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_rpjpd',
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


	public function get_detail_rpjpd_by_id()
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
							FROM esakip_rpjpd
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


	public function submit_tahun_rpjpd()
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
							FROM esakip_rpjpd 
							WHERE id = %d", $id)
					);

					if (!empty($existing_data)) {
						$update_result = $wpdb->update(
							'esakip_rpjpd',
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


	public function hapus_dokumen_rpjpd()
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
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
					$dokumen_lama = $wpdb->get_var(
						$wpdb->prepare("
							SELECT
								dokumen
							FROM esakip_rpjpd
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_rpjpd',
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

	public function submit_verifikasi_dokumen()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil Verifikasi Dokumen!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_dokumen'])) {
					$id_dokumen = $_POST['id_dokumen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Dokumen kosong!';
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
				if($_POST['tipe_dokumen'] != 'renstra'){
					if (!empty($_POST['tahunAnggaran'])) {
						$tahunAnggaran = $_POST['tahunAnggaran'];
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Tahun Anggaran kosong!';
					}
				}
				if (!empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe Dokumen kosong!';
				}
				if (!empty($_POST['verifikasi_dokumen'])) {
					$verifikasi_dokumen = $_POST['verifikasi_dokumen'];
					$input_verifikasi = 0;
					if ($verifikasi_dokumen == 'terima') {
						$input_verifikasi = 1;
					} else if ($verifikasi_dokumen == 'tolak') {
						$input_verifikasi = 2;
					}
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Verifikasi Dokumen kosong!';
				}

				$current_user = wp_get_current_user();

				if ($ret['status'] == 'success' && $input_verifikasi > 0) {
					// untuk mengatur tabel sesuai tipe dokumen
					$nama_tabel = array(
						"laporan_kinerja" => "esakip_laporan_kinerja",
						"dpa"	=> "esakip_dpa",
						"renja_rkt"	=> "esakip_renja_rkt",
						"renstra"	=> "esakip_renstra",
						"perjanjian_kinerja"	=> "esakip_perjanjian_kinerja"
					);

					// Cek data verifikasi yg sudah ada
					$data_terverifikasi = $wpdb->get_row(
						$wpdb->prepare("
							SELECT *
							FROM esakip_keterangan_verifikator
							WHERE id_dokumen = %d
								AND nama_tabel_dokumen = %s
								AND active=1
								AND tahun_anggaran=%d
						", $id_dokumen, $nama_tabel[$tipe_dokumen], $tahunAnggaran),
						ARRAY_A
					);
					if (empty($data_terverifikasi)) {
						$wpdb->insert(
							'esakip_keterangan_verifikator',
							array(
								'id_dokumen' => $id_dokumen,
								'status_verifikasi' => $input_verifikasi,
								'keterangan_verifikasi' => $keterangan,
								'active' => 1,
								'user_id' => $current_user->ID,
								'id_skpd' => $idSkpd,
								'tahun_anggaran' => $tahunAnggaran,
								'created_at' => current_time('mysql'),
								'nama_tabel_dokumen' => $nama_tabel[$tipe_dokumen]
							),
							array('%d', '%d', '%s', '%d', '%d', '%d', '%d', '%s', '%s')
						);

						if (!$wpdb->insert_id) {
							$ret = array(
								'status' => 'error',
								'message' => 'Gagal menyimpan data ke database!'
							);
						}
					} else {
						$opsi = array(
							'status_verifikasi' => $input_verifikasi,
							'keterangan_verifikasi' => $keterangan,
							'user_id' => $current_user->ID
						);

						$wpdb->update(
							'esakip_keterangan_verifikator',
							$opsi,
							array('id' => $data_terverifikasi['id']),
							array('%d', '%s', '%d'),
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


	public function get_verifikasi_dokumen_by_id()
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
					if (!empty($_POST['tipe_dokumen'])) {
						$tipe_dokumen = $_POST['tipe_dokumen'];
						// untuk mengatur tabel sesuai tipe dokumen
						$nama_tabel = array(
							"laporan_kinerja" => "esakip_laporan_kinerja",
							"dpa"	=> "esakip_dpa",
							"renja_rkt"	=> "esakip_renja_rkt",
							"renstra"	=> "esakip_renstra",
							"perjanjian_kinerja"	=> "esakip_perjanjian_kinerja"
						);

						$data = $wpdb->get_row(
							$wpdb->prepare("
								SELECT *
								FROM $nama_tabel[$tipe_dokumen]
								WHERE id = %d
									AND active=1
							", $_POST['id']),
							ARRAY_A
						);
						if (!empty($data)) {
							$data_verifikasi = $wpdb->get_row(
								$wpdb->prepare("
									SELECT *
									FROM esakip_keterangan_verifikator
									WHERE id_dokumen = %d
										AND nama_tabel_dokumen = %s
										AND active=1
								", $data['id'], $nama_tabel[$tipe_dokumen]),
								ARRAY_A
							);

							$ret['data'] = $data_verifikasi;
							if (empty($data_verifikasi)) {
								$ret = array(
									'cek' => $wpdb->last_query,
									'data' => array(),
									'status' => 'success',
									'message'   => 'Data Belum Diverifikasi!'
								);
							}
						} else {
							$ret = array(
								'status' => 'error',
								'message'   => 'Data Tidak Ditemukan!'
							);
						}
					} else {
						$ret = array(
							'status' => 'error',
							'message'   => 'Tipe Dokumen Kosong!'
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

	public function get_table_dpa()
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
				$dpas = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_dpa
                    WHERE id_skpd = %d 
                      AND tahun_anggaran = %d 
                      AND active = 1
                ", $id_skpd, $tahun_anggaran),
					ARRAY_A
				);

				if (!empty($dpas)) {
					$counter = 1;
					$tbody = '';

					// user authorize
					$current_user = wp_get_current_user();
					//jika user adalah admin atau skpd
					$can_verify = false;
					if (
						in_array("admin_ortala", $current_user->roles) ||
						in_array("admin_bappeda", $current_user->roles) ||
						in_array("administrator", $current_user->roles)
					) {
						$can_verify = true;
					}

					foreach ($dpas as $kk => $vv) {
						$data_verifikasi = $wpdb->get_row($wpdb->prepare('
							SELECT 
								*
							FROM esakip_keterangan_verifikator
							WHERE id_dokumen=%d
								AND active=1
						', $vv['id']), ARRAY_A);

						$color_badge_verify = 'secondary';
						$text_badge = 'Menunggu';
						if($data_verifikasi['status_verifikasi'] == 1){
							$color_badge_verify = 'success';
							$text_badge = 'Diterima';
						}else if($data_verifikasi['status_verifikasi'] == 2){
							$color_badge_verify = 'danger';
							$text_badge = 'Ditolak';
						}

						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";
						$tbody .= "<td class='text-center'><span class='badge badge-" . $color_badge_verify . "' style='padding: .5em 1.4em;'>" . $text_badge . "</span></td>";
						$tbody .= "<td>" . $data_verifikasi['keterangan_verifikasi'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if($can_verify){
							$btn .= '<button class="btn btn-sm btn-success" onclick="verifikasi_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Verifikasi Dokumen"><span class="dashicons dashicons-yes"></span></button>';
						}
                    	if (!$this->is_admin_panrb()) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_dpa(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_dpa(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						}
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $btn . "</td>";
						$tbody .= "</tr>";
					}

					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='8' class='text-center'>Tidak ada data tersedia</td></tr>";
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message'   => 'Format tidak sesuai!'
			);
		}
		die(json_encode($ret));
	}

	public function get_detail_dpa_by_id()
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
							FROM esakip_dpa
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

	public function tambah_dokumen_dpa()
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
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}
				
				if ($ret['status'] == 'success' && !empty($_FILES['fileUpload'])) {
					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
					$maksimal_upload = get_option('_crb_maksimal_upload_dokumen_esakip');
					$upload = $this->functions->uploadFile(
						$_POST['api_key'],
						$upload_dir,
						$_FILES['fileUpload'],
						array('pdf'),
						1048576 * $maksimal_upload
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
							'esakip_dpa',
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
								FROM esakip_dpa
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_dpa',
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

	public function hapus_dokumen_dpa()
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
							FROM esakip_dpa
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_dpa',
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
}
