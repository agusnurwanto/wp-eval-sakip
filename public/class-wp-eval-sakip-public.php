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
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/jadwal/wp-eval-sakip-jadwal-evaluasi.php';
	}

	public function upload_dokumen_renstra($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}

		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-renstra-per-skpd.php';
	}

	public function renstra($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-renstra.php';
	}

	public function dpa($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-dpa.php';
	}

	public function renja_rkt($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-renja-rkt.php';
	}

	public function perjanjian_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-perjanjian-kinerja.php';
	}

	public function rencana_aksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-rencana-aksi.php';
	}

	public function iku($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-iku.php';
	}

	public function skp($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-skp.php';
	}

	public function pengukuran_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-pengukuran-kinerja.php';
	}

	public function pengukuran_rencana_aksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-pengukuran-rencana-aksi.php';
	}

	public function laporan_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-laporan-kinerja.php';
	}

	public function evaluasi_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-evaluasi-internal.php';
	}

	public function dokumen_lainnya($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-dokumen_lainnya.php';
	}

	public function upload_dokumen_rpjmd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-rpjmd.php';
	}

	public function upload_dokumen_rpjpd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-rpjpd.php';
	}

	public function rkpd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-rkpd.php';
	}

	public function lkjip_lppd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-lkjip-lppd.php';
	}

	public function laporan_monev_renaksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-laporan-monev-renaksi.php';
	}

	public function pedoman_teknis_perencanaan($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-pedoman-teknis-perencanaan.php';
	}

	public function pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-pedoman-teknis-pengukuran-dan-pengumpulan-data-kinerja.php';
	}

	public function pedoman_teknis_evaluasi_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-pedoman-teknis-evaluasi-internal.php';
	}

	public function halaman_mapping_skpd()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/pengaturan-skpd/wp-eval-sakip-mapping-skpd.php';
	}

	public function jadwal_rpjmd()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/jadwal/wp-eval-sakip-jadwal-rpjmd.php';
	}

	public function jadwal_rpjpd()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/jadwal/wp-eval-sakip-jadwal-rpjpd.php';
	}

	public function pengaturan_menu($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/setting-menu/wp-eval-sakip-pengaturan-menu.php';
	}

	public function input_rpjmd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/input-perencanaan/wp-eval-sakip-input_rpjmd.php';
	}

	public function input_rpjpd($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/input-perencanaan/wp-eval-sakip-input_rpjpd.php';
	}
	
	public function croscutting_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/pohon-kinerja/wp-eval-sakip-croscutting_pemda.php';
	}

	public function detail_croscutting_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/pohon-kinerja/wp-eval-sakip-detail_croscutting_pemda.php';
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
					$ret['message'] = 'Nama Perangkat Daerah SAKIP tidak boleh kosong!';
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
						$ret['message'] = 'ID Perangkat Daerah tidak ditemukans!';
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
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-dpa-per-skpd.php';
	}

	public function dokumen_detail_renja_rkt($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-renja-rkt-per-skpd.php';
	}

	public function dokumen_detail_skp($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-skp-per-skpd.php';
	}

	public function dokumen_detail_rencana_aksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-rencana-aksi-per-skpd.php';
	}

	public function dokumen_detail_perjanjian_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-perjanjian-kinerja-per-skpd.php';
	}

	public function dokumen_detail_pengukuran_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-pengukuran-kinerja-per-skpd.php';
	}

	public function dokumen_detail_pengukuran_rencana_aksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-pengukuran-rencana-aksi-per-skpd.php';
	}

	public function dokumen_detail_evaluasi_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-evaluasi-internal-per-skpd.php';
	}

	public function dokumen_detail_iku($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-iku-per-skpd.php';
	}

	public function dokumen_detail_laporan_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-laporan-kinerja-per-skpd.php';
	}

	public function dokumen_detail_dokumen_lainnya($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-dokumen-lain-per-skpd.php';
	}

	public function dokumen_detail_pohon_kinerja_dan_cascading($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-pohon-kinerja-dan-cascading-per-skpd.php';
	}

	public function dokumen_detail_lhe_akip_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-lhe-akip-internal-per-skpd.php';
	}

	public function dokumen_detail_tl_lhe_akip_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-tl-lhe-akip-internal-per-skpd.php';
	}

	public function dokumen_detail_tl_lhe_akip_kemenpan($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-tl-lhe-akip-kemenpan-per-skpd.php';
	}

	public function pohon_kinerja_dan_cascading($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-pohon-kinerja-dan-cascading.php';
	}

	public function dokumen_detail_laporan_monev_renaksi($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-laporan-monev-renaksi-per-skpd.php';
	}

	public function dokumen_detail_pedoman_teknis_perencanaan($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-pedoman-teknis-perencanaan-per-skpd.php';
	}

	public function dokumen_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-pedoman-teknis-pengukuran-dan-pengumpulan-data-kinerja-per-skpd.php';
	}

	public function dokumen_detail_pedoman_teknis_evaluasi_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-perangkat-daerah/wp-eval-sakip-detail-pedoman-teknis-evaluasi-internal-per-skpd.php';
	}

	public function lhe_akip_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-lhe-akip-internal.php';
	}

	public function tl_lhe_akip_internal($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-tl-lhe-akip-internal.php';
	}

	public function tl_lhe_akip_kemenpan($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-list-opd/wp-eval-sakip-tl-lhe-akip-kemenpan.php';
	}

	public function is_admin_panrb()
	{
		$current_user = wp_get_current_user();
		return in_array('admin_panrb', $current_user->roles);
	}
	public function is_admin_user()
	{
		$current_user = wp_get_current_user();
		$cek = array('admin_ortala', 'administrator', 'admin_bappeda');
		return array_intersect($cek, $current_user->roles);
	}

	public function hak_akses_upload_dokumen($nama_dokumen, $tahun_anggaran)
	{
		global $wpdb;
		$current_user = wp_get_current_user();
		$user_roles = $current_user->roles;
		$is_administrator = in_array('administrator', $user_roles);

		$admin_role_pemda = array(
			'admin_bappeda',
			'admin_ortala'
		);

		$this_jenis_role = (in_array($user_roles[0], $admin_role_pemda)) ? 1 : 2;

		if ($nama_dokumen == 'RENSTRA' || $nama_dokumen == 'Pohon Kinerja dan Cascading') {
			// Khusus di Settingan RENSTRA dan pohon kinerja tahun anggaran diganti dengan id jadwal periode renstra
			$cek_settingan_menu = $wpdb->get_var($wpdb->prepare("
				SELECT 
					jenis_role
				FROM esakip_menu_dokumen 
				WHERE nama_dokumen=%s
					AND user_role='perangkat_daerah' 
					AND active = 1
					AND id_jadwal=%d
			", $nama_dokumen, $tahun_anggaran));
		} else {
			$cek_settingan_menu = $wpdb->get_var($wpdb->prepare("
				  SELECT 
					jenis_role
				  FROM esakip_menu_dokumen 
				  WHERE nama_dokumen=%s
					  AND user_role='perangkat_daerah' 
					  AND active = 1
					  AND tahun_anggaran=%d
			", $nama_dokumen, $tahun_anggaran));
		}

		$hak_akses_user = ($cek_settingan_menu == $this_jenis_role || $cek_settingan_menu == 3 || $is_administrator) ? true : false;
		return $hak_akses_user;
	}

	public function hak_akses_upload_dokumen_renstra($nama_dokumen)
	{
		global $wpdb;
		$current_user = wp_get_current_user();
		$user_roles = $current_user->roles;
		$is_administrator = in_array('administrator', $user_roles);

		$admin_role_pemda = array(
			'admin_bappeda',
			'admin_ortala'
		);

		$this_jenis_role = (in_array($user_roles[0], $admin_role_pemda)) ? 1 : 2;

		$cek_settingan_menu = $wpdb->get_var($wpdb->prepare("
	    	SELECT 
	        	jenis_role
	      	FROM esakip_menu_dokumen 
	      	WHERE nama_dokumen=%s
	      		AND user_role='perangkat_daerah' 
	      		AND active = 1
	    ", $nama_dokumen));

		$hak_akses_user = ($cek_settingan_menu == $this_jenis_role || $cek_settingan_menu == 3 || $is_administrator) ? true : false;
		return $hak_akses_user;
	}

	public function dokumen_detail_dpa_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-dpa-pemda.php';
	}

	public function dokumen_detail_rencana_aksi_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-rencana-aksi-pemda.php';
	}

	public function dokumen_detail_perjanjian_kinerja_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-perjanjian-kinerja-pemda.php';
	}

	public function dokumen_detail_iku_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-iku-pemda.php';
	}

	public function dokumen_detail_laporan_kinerja_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-laporan-kinerja-pemda.php';
	}

	public function dokumen_detail_dokumen_lainnya_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-dokumen-lain-pemda.php';
	}

	public function dokumen_detail_pohon_kinerja_dan_cascading_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-pohon-kinerja-dan-cascading-pemda.php';
	}

	public function dokumen_detail_lhe_akip_internal_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-lhe-akip-internal-pemda.php';
	}

	public function dokumen_detail_tl_lhe_akip_internal_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-tl-lhe-akip-internal-pemda.php';
	}

	public function dokumen_detail_tl_lhe_akip_kemenpan_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-tl-lhe-akip-kemenpan-pemda.php';
	}

	public function dokumen_detail_laporan_monev_renaksi_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-laporan-monev-renaksi-pemda.php';
	}

	public function dokumen_detail_pedoman_teknis_perencanaan_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-pedoman-teknis-perencanaan-pemda.php';
	}

	public function dokumen_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-pedoman-teknis-pengukuran-dan-pengumpulan-data-kinerja-pemda.php';
	}

	public function dokumen_detail_pedoman_teknis_evaluasi_internal_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-pedoman-teknis-evaluasi-internal-pemda.php';
	}

	public function dokumen_detail_lkjip_lppd_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-lkjip-pemda.php';
	}

	public function dokumen_detail_rkpd_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dokumen-pemda/wp-eval-sakip-detail-rkpd-pemda.php';
	}

	public function halaman_cek_dokumen($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/monitor-upload/wp-eval-sakip-halaman-pengecekan-dokumen.php';
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
				if (!empty($_POST['id_jadwal'])) {
					$tahun_periode = $_POST['id_jadwal'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Jadwal Periode kosong!';
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

				if ($ret['status'] != 'error' && !empty($_POST['id_dokumen'])) {
					$ret['message'] = 'Berhasil edit data!';
					$id_dokumen = $_POST['id_dokumen'];
				} else if ($ret['status'] != 'error' && empty($_POST['skpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Perangkat Daerah kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['idSkpd'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Perangkat Daerah kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['keterangan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Keterangan kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahunAnggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				} else if ($ret['status'] != 'error' && empty($_FILES['fileUpload']) && empty($id_dokumen)) {
					$ret['status'] = 'error';
					$ret['message'] = 'File Dokumen kosong!';
				} else if (empty($_POST['namaDokumen']) && empty($id_dokumen)) {
					$ret['status'] = 'error';
					$ret['message'] = 'File Dokumen kosong!';
				} else if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}

				$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';
				if ($ret['status'] != 'error' && !empty($_FILES['fileUpload'])) {
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
						FROM esakip_renja_rkt
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_renja_rkt',
								array('dokumen' => $_POST['namaDokumen']),
								array('id' => $id_dokumen),
							);
						} else {
							$ret = $ret_rename;
						}
					}
				}

				if ($ret['status'] != 'error') {
					$skpd = $_POST['skpd'];
					$idSkpd = $_POST['idSkpd'];
					$keterangan = $_POST['keterangan'];
					$tahunAnggaran = $_POST['tahunAnggaran'];
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
						} else {
							$data = array(
								'id_dokumen'	=> $wpdb->insert_id,
								'id_skpd'		=> $idSkpd,
								'tahun_anggaran' => $tahunAnggaran,
								'nama_tabel'	=> 'esakip_renja_rkt'
							);

							$setting_verifikasi = $this->setting_verifikasi_upload($data);
							if ($setting_verifikasi['status'] == 'success') {
								$ret['setting_verifikasi'] = $setting_verifikasi;
							}
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
						FROM esakip_perjanjian_kinerja
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_perjanjian_kinerja',
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
						} else {
							$data = array(
								'id_dokumen'	=> $wpdb->insert_id,
								'id_skpd'		=> $idSkpd,
								'tahun_anggaran' => $tahunAnggaran,
								'nama_tabel'	=> 'esakip_perjanjian_kinerja'
							);

							$setting_verifikasi = $this->setting_verifikasi_upload($data);
							if ($setting_verifikasi['status'] == 'success') {
								$ret['setting_verifikasi'] = $setting_verifikasi;
							}
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
						FROM esakip_evaluasi_internal
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_evaluasi_internal',
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
						FROM esakip_laporan_kinerja
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_laporan_kinerja',
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
						} else {
							$data = array(
								'id_dokumen'	=> $wpdb->insert_id,
								'id_skpd'		=> $idSkpd,
								'tahun_anggaran' => $tahunAnggaran,
								'nama_tabel'	=> 'esakip_laporan_kinerja'
							);

							$setting_verifikasi = $this->setting_verifikasi_upload($data);
							if ($setting_verifikasi['status'] == 'success') {
								$ret['setting_verifikasi'] = $setting_verifikasi;
							}
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
						FROM esakip_iku
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_iku',
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
						FROM esakip_dokumen_lainnya
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_dokumen_lainnya',
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
						FROM esakip_rencana_aksi
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_rencana_aksi',
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
						FROM esakip_pengukuran_kinerja
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_pengukuran_kinerja',
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
						FROM esakip_pengukuran_rencana_aksi
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_pengukuran_rencana_aksi',
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
				if (empty($_POST['namaDokumen']) && empty($id_dokumen)) {
					$ret['status'] = 'error';
					$ret['message'] = 'File Dokumen kosong!';
				}
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}

				$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
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
						FROM esakip_other_file
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_other_file',
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
				if (empty($_POST['namaDokumen']) && empty($id_dokumen)) {
					$ret['status'] = 'error';
					$ret['message'] = 'File Dokumen kosong!';
				}
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}

				$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
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
						FROM esakip_rkpd
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_rkpd',
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
						FROM esakip_rpjmd
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_rpjmd',
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
				if (empty($_POST['namaDokumen']) && empty($id_dokumen)) {
					$ret['status'] = 'error';
					$ret['message'] = 'File Dokumen kosong!';
				}
				if (empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}

				$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/dokumen_pemda/';
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
						FROM esakip_lkjip_lppd
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_lkjip_lppd',
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						FROM esakip_renstra
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_renstra',
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
						} else {
							$current_user = wp_get_current_user();
							$wpdb->insert(
								'esakip_keterangan_verifikator',
								array(
									'id_dokumen' => $wpdb->insert_id,
									'status_verifikasi' => 1,
									'active' => 1,
									'user_id' => $current_user->ID,
									'id_skpd' => $idSkpd,
									'id_jadwal' => $id_jadwal,
									'created_at' => current_time('mysql'),
									'nama_tabel_dokumen' => 'esakip_renstra'
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
				if (empty($_POST['namaDokumen']) && empty($id_dokumen)) {
					$ret['status'] = 'error';
					$ret['message'] = 'File Dokumen kosong!';
				}
				if (empty($_FILES['fileUpload']) && empty($id_dokumen)) {
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
						FROM esakip_skp
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_skp',
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if ($can_verify) {
							$btn .= '<button class="btn btn-sm btn-success" onclick="verifikasi_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Verifikasi Dokumen"><span class="dashicons dashicons-yes"></span></button>';
						}
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('RENJA/RKT', $tahun_anggaran)) {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Rencana Aksi', $tahun_anggaran)) {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if ($can_verify) {
							$btn .= '<button class="btn btn-sm btn-success" onclick="verifikasi_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Verifikasi Dokumen"><span class="dashicons dashicons-yes"></span></button>';
						}
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Perjanjian Kinerja', $tahun_anggaran)) {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!empty($data_verifikasi)) {
							if ($data_verifikasi['status_verifikasi'] == 1) {
								$color_badge_verify = 'success';
								$text_badge = 'Diterima';
							} else if ($data_verifikasi['status_verifikasi'] == 2) {
								$color_badge_verify = 'danger';
								$text_badge = 'Ditolak';
							}
							$keterangan = '';
							if (!empty($data_verifikasi['keterangan_verifikasi'])) {
								$keterangan = $data_verifikasi['keterangan_verifikasi'];
							}
						}
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['opd'] . "</td>";
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";
						$tbody .= "<td class='text-center'><span class='badge badge-" . $color_badge_verify . "' style='padding: .5em 1.4em;'>" . $text_badge . "</span></td>";
						$tbody .= "<td>" . $keterangan . "</td>";

						$btn = '<div class="btn-action-group">';
						if ($can_verify) {
							$btn .= '<button class="btn btn-sm btn-success" onclick="verifikasi_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Verifikasi Dokumen"><span class="dashicons dashicons-yes"></span></button>';
						}
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Laporan Kinerja', $tahun_anggaran)) {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('IKU', $tahun_anggaran)) {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Dokumen Lainnya', $tahun_anggaran)) {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Evaluasi Internal', $tahun_anggaran)) {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Pengukuran Kinerja', $tahun_anggaran)) {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Pengukuran Rencana Aksi', $tahun_anggaran)) {
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('LKJIP/LPPD', $tahun_anggaran)) {
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Dokumen Lainnya', $tahun_anggaran)) {
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('RKPD', $tahun_anggaran)) {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						// if($can_verify){
						// 	$btn .= '<button class="btn btn-sm btn-success" onclick="verifikasi_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Verifikasi Dokumen"><span class="dashicons dashicons-yes"></span></button>';
						// }
						// if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen_renstra('RENSTRA')) {
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('RENSTRA', $id_jadwal)) {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('SKP', $tahun_anggaran)) {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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

					// untuk mengatur judul halaman sesuai tipe dokumen
					$nama_page = array(
						"pohon_kinerja_dan_cascading" => "Pohon Kinerja dan Cascading",
						"lhe_akip_internal" => "LHE AKIP Internal",
						"tl_lhe_akip_internal" => "TL LHE AKIP Internal",
						"tl_lhe_akip_kemenpan" => "TL LHE AKIP Kemenpan"
					);
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
							if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen($nama_page[$tipe_dokumen], $tahun_anggaran)) {
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


	public function get_table_dokumen_pemerintah_daerah()
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
					// untuk mengatur tabel sesuai tipe dokumen
					$nama_tabel_admin = array(
						"pohon_kinerja_dan_cascading" => "esakip_pohon_kinerja_dan_cascading_pemda",
						"iku" => "esakip_iku_pemda",
						"dpa" => "esakip_dpa_pemda",
						"rencana_aksi" => "esakip_rencana_aksi_pemda",
						"perjanjian_kinerja" => "esakip_perjanjian_kinerja_pemda",
						"laporan_kinerja" => "esakip_laporan_kinerja_pemda",
						"dokumen_lainnya" => "esakip_dokumen_lainnya_pemda",
						"lhe_akip_internal" => "esakip_lhe_akip_internal_pemda",
						"tl_lhe_akip_internal" => "esakip_tl_lhe_akip_internal_pemda",
						"tl_lhe_akip_kemenpan" => "esakip_tl_lhe_akip_kemenpan_pemda",
						"laporan_monev_renaksi" => "esakip_laporan_monev_renaksi_pemda",
						"pedoman_teknis_perencanaan" => "esakip_pedoman_teknis_perencanaan_pemda",
						"pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja" => "esakip_pedoman_teknis_pengukuran_dan_p_d_k_pemda",
						"pedoman_teknis_evaluasi_internal" => "esakip_pedoman_teknis_evaluasi_internal_pemda",
						"lkjip" => "esakip_lkjip_lppd_pemda",
						"rkpd" => "esakip_rkpd_pemda"
					);

					$datas = $wpdb->get_results(
						$wpdb->prepare("
						SELECT * 
						FROM $nama_tabel_admin[$tipe_dokumen]
						WHERE tahun_anggaran = %d 
						  AND active = 1
					", $tahun_anggaran),
						ARRAY_A
					);

					if (!empty($datas)) {
						$counter = 1;
						$tbody = '';

						foreach ($datas as $kk => $vv) {
							$tbody .= "<tr>";
							$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
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

	public function submit_tambah_dokumen_pemerintah_daerah()
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
				if ($ret['status'] != 'error' && empty($_POST['keterangan'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Keterangan kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tahunAnggaran'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tahun Anggaran kosong!';
				} else if ($ret['status'] != 'error' && empty($_FILES['fileUpload']) && empty($id_dokumen)) {
					$ret['status'] = 'error';
					$ret['message'] = 'File Dokumen kosong!';
				} else if ($ret['status'] != 'error' && empty($_POST['tipe_dokumen'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe Dokumen kosong!';
				} else if ($ret['status'] != 'error' && empty(get_option('_crb_maksimal_upload_dokumen_esakip'))) {
					$ret['status'] = 'error';
					$ret['message'] = 'Batas Upload Dokumen Belum Disetting!';
				}

				if ($ret['status'] != 'error') {
					$keterangan = $_POST['keterangan'];
					$tahunAnggaran = $_POST['tahunAnggaran'];
					$tipe_dokumen = $_POST['tipe_dokumen'];
					// untuk mengatur tabel sesuai tipe dokumen
					$nama_tabel = array(
						"pohon_kinerja_dan_cascading" => "esakip_pohon_kinerja_dan_cascading_pemda",
						"iku" => "esakip_iku_pemda",
						"dpa" => "esakip_dpa_pemda",
						"rencana_aksi" => "esakip_rencana_aksi_pemda",
						"perjanjian_kinerja" => "esakip_perjanjian_kinerja_pemda",
						"laporan_kinerja" => "esakip_laporan_kinerja_pemda",
						"dokumen_lainnya" => "esakip_dokumen_lainnya_pemda",
						"lhe_akip_internal" => "esakip_lhe_akip_internal_pemda",
						"tl_lhe_akip_internal" => "esakip_tl_lhe_akip_internal_pemda",
						"tl_lhe_akip_kemenpan" => "esakip_tl_lhe_akip_kemenpan_pemda",
						"laporan_monev_renaksi" => "esakip_laporan_monev_renaksi_pemda",
						"pedoman_teknis_perencanaan" => "esakip_pedoman_teknis_perencanaan_pemda",
						"pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja" => "esakip_pedoman_teknis_pengukuran_dan_p_d_k_pemda",
						"pedoman_teknis_evaluasi_internal" => "esakip_pedoman_teknis_evaluasi_internal_pemda",
						"lkjip" => "esakip_lkjip_lppd_pemda",
						"rkpd" => "esakip_rkpd_pemda"
					);

					$upload_dir = ESAKIP_PLUGIN_PATH . 'public/media/dokumen/';

					if ($ret['status'] != 'error' && !empty($_FILES['fileUpload'])) {
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
							FROM $nama_tabel[$tipe_dokumen]
							WHERE id=%d
						", $id_dokumen));
						if ($dokumen_lama != $_POST['namaDokumen']) {
							$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
							if ($ret_rename['status'] != 'error') {
								$wpdb->update(
									$nama_tabel[$tipe_dokumen],
									array('dokumen' => $_POST['namaDokumen']),
									array('id' => $id_dokumen),
								);
							} else {
								$ret = $ret_rename;
							}
						}
					}

					if ($ret['status'] != "error") {
						// pastikan tambah kolom id user
						$user_id = um_user('ID');

						if (empty($id_dokumen)) {
							$wpdb->insert(
								$nama_tabel[$tipe_dokumen],
								array(
									'dokumen' => $upload['filename'],
									'keterangan' => $keterangan,
									'tahun_anggaran' => $tahunAnggaran,
									'created_at' => current_time('mysql'),
									'tanggal_upload' => current_time('mysql')
								)
							);

							if (!$wpdb->insert_id) {
								$ret = array(
									'status' => 'error',
									'message' => 'Gagal menyimpan data ke database!',
									'sql' => $wpdb->last_query
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
								array('id' => $id_dokumen)
							);

							if ($wpdb->rows_affected == 0) {
								$ret = array(
									'status' => 'error',
									'message' => 'Gagal memperbarui data ke database!'
								);
							}
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

	public function get_detail_dokumen_by_id_pemerintah_daerah()
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
						"pohon_kinerja_dan_cascading" => "esakip_pohon_kinerja_dan_cascading_pemda",
						"iku" => "esakip_iku_pemda",
						"dpa" => "esakip_dpa_pemda",
						"rencana_aksi" => "esakip_rencana_aksi_pemda",
						"perjanjian_kinerja" => "esakip_perjanjian_kinerja_pemda",
						"laporan_kinerja" => "esakip_laporan_kinerja_pemda",
						"dokumen_lainnya" => "esakip_dokumen_lainnya_pemda",
						"lhe_akip_internal" => "esakip_lhe_akip_internal_pemda",
						"tl_lhe_akip_internal" => "esakip_tl_lhe_akip_internal_pemda",
						"tl_lhe_akip_kemenpan" => "esakip_tl_lhe_akip_kemenpan_pemda",
						"laporan_monev_renaksi" => "esakip_laporan_monev_renaksi_pemda",
						"pedoman_teknis_perencanaan" => "esakip_pedoman_teknis_perencanaan_pemda",
						"pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja" => "esakip_pedoman_teknis_pengukuran_dan_p_d_k_pemda",
						"pedoman_teknis_evaluasi_internal" => "esakip_pedoman_teknis_evaluasi_internal_pemda",
						"lkjip" => "esakip_lkjip_lppd_pemda",
						"rkpd" => "esakip_rkpd_pemda",
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


	public function hapus_dokumen_pemerintah_daerah()
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
						"pohon_kinerja_dan_cascading" => "esakip_pohon_kinerja_dan_cascading_pemda",
						"iku" => "esakip_iku_pemda",
						"dpa" => "esakip_dpa_pemda",
						"rencana_aksi" => "esakip_rencana_aksi_pemda",
						"perjanjian_kinerja" => "esakip_perjanjian_kinerja_pemda",
						"laporan_kinerja" => "esakip_laporan_kinerja_pemda",
						"dokumen_lainnya" => "esakip_dokumen_lainnya_pemda",
						"lhe_akip_internal" => "esakip_lhe_akip_internal_pemda",
						"tl_lhe_akip_internal" => "esakip_tl_lhe_akip_internal_pemda",
						"tl_lhe_akip_kemenpan" => "esakip_tl_lhe_akip_kemenpan_pemda",
						"laporan_monev_renaksi" => "esakip_laporan_monev_renaksi_pemda",
						"pedoman_teknis_perencanaan" => "esakip_pedoman_teknis_perencanaan_pemda",
						"pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja" => "esakip_pedoman_teknis_pengukuran_dan_p_d_k_pemda",
						"pedoman_teknis_evaluasi_internal" => "esakip_pedoman_teknis_evaluasi_internal_pemda",
						"lkjip" => "esakip_lkjip_lppd_pemda",
						"rkpd" => "esakip_rkpd_pemda"
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
						"pohon_kinerja_dan_cascading_pemda" => "esakip_pohon_kinerja_dan_cascading_pemda",
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

	public function get_data_jadwal_lke()
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

	public function get_data_penjadwalan_lke()
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

	public function submit_jadwal_lke()
	{
		global $wpdb;
		$user_id = um_user('ID');
		$user_meta = get_userdata($user_id);
		$return = array(
			'status' => 'success',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				if (!empty($_POST['nama_jadwal']) && !empty($_POST['jenis_jadwal']) && !empty($_POST['tahun_anggaran'])) {
					$nama_jadwal = trim(htmlspecialchars($_POST['nama_jadwal']));
					$jadwal_mulai = trim(htmlspecialchars($_POST['jadwal_mulai']));
					$jadwal_mulai = date('Y-m-d H:i:s', strtotime($jadwal_mulai));
					$jadwal_selesai = trim(htmlspecialchars($_POST['jadwal_selesai']));
					$jadwal_selesai = date('Y-m-d H:i:s', strtotime($jadwal_selesai));
					$tahun_anggaran = trim(htmlspecialchars($_POST['tahun_anggaran']));
					$jenis_jadwal = trim(htmlspecialchars($_POST['jenis_jadwal']));
					$tampil_nilai_penetapan = $_POST['tampil_nilai_penetapan'];
					$arr_jadwal = ['usulan', 'penetapan'];
					$jenis_jadwal = in_array($jenis_jadwal, $arr_jadwal) ? $jenis_jadwal : 'usulan';

					$id_jadwal_sebelumnya = $wpdb->get_var(
						$wpdb->prepare("
                        SELECT MAX(id)
                        FROM esakip_data_jadwal
                        WHERE tipe='LKE'
                          AND tahun_anggaran=%d
                          AND status !=0
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

					// cek jadwal lama
					foreach ($get_jadwal as $jadwal) {
						if ($jadwal['status'] != 2) {
							$return = array(
								'status' => 'error',
								'message' => 'Masih ada jadwal yang terbuka!'
							);
							die(json_encode($return));
						}
						if ($jadwal_mulai > $jadwal['started_at'] && $jadwal_mulai < $jadwal['end_at'] || $jadwal_selesai > $jadwal['started_at'] && $jadwal_selesai < $jadwal['end_at']) {
							$return = array(
								'status' => 'error',
								'message' => 'Waktu sudah dipakai jadwal lain!'
							);
							die(json_encode($return));
						}
					}

					// Insert data jadwal
					$data_jadwal = array(
						'nama_jadwal' => $nama_jadwal,
						'started_at' => $jadwal_mulai,
						'end_at' => $jadwal_selesai,
						'tahun_anggaran' => $tahun_anggaran,
						'status' => 1,
						'jenis_jadwal' => $jenis_jadwal,
						'tipe' => 'LKE',
						'tampil_nilai_penetapan' => $tampil_nilai_penetapan,
						'lama_pelaksanaan' => 1,
					);

					$wpdb->insert('esakip_data_jadwal', $data_jadwal);
					$id_jadwal_baru = $wpdb->insert_id;

					// Mapping arrays for old to new IDs
					$komponen_id_mapping = [];
					$subkomponen_id_mapping = [];
					$komponen_penilaian_id_mapping = [];

					// Add data komponen if there is a previous schedule ID
					if (!empty($id_jadwal_sebelumnya)) {
						$data_komponen = $wpdb->get_results(
							$wpdb->prepare("
                            SELECT * 
                            FROM esakip_komponen 
                            WHERE id_jadwal = %d
                              AND active = 1
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
								$komponen_id_mapping[$komponen['id']] = $id_komponen_baru;

								$data_subkomponen = $wpdb->get_results(
									$wpdb->prepare("
                                    SELECT * 
                                    FROM esakip_subkomponen 
                                    WHERE id_komponen = %d
                                      AND active = 1
                                ", $komponen['id']),
									ARRAY_A
								);

								foreach ($data_subkomponen as $subkomponen) {
									$data_subkomponen_baru = array(
										'id_komponen' => $id_komponen_baru,
										'nomor_urut' => $subkomponen['nomor_urut'],
										'id_user_penilai' => $subkomponen['id_user_penilai'],
										'nama' => $subkomponen['nama'],
										'metode_penilaian' => $subkomponen['metode_penilaian'],
										'bobot' => $subkomponen['bobot'],
									);
									$wpdb->insert('esakip_subkomponen', $data_subkomponen_baru);
									$id_subkomponen_baru = $wpdb->insert_id;
									$subkomponen_id_mapping[$subkomponen['id']] = $id_subkomponen_baru;

									$data_komponen_penilaian = $wpdb->get_results(
										$wpdb->prepare("
                                        SELECT * 
                                        FROM esakip_komponen_penilaian 
                                        WHERE id_subkomponen = %d
                                          AND active = 1
                                    ", $subkomponen['id']),
										ARRAY_A
									);

									foreach ($data_komponen_penilaian as $penilaian) {
										$data_komponen_penilaian_baru = array(
											'id_subkomponen' => $id_subkomponen_baru,
											'nomor_urut' => $penilaian['nomor_urut'],
											'nama' => $penilaian['nama'],
											'tipe' => $penilaian['tipe'],
											'bobot' => $penilaian['bobot'],
											'keterangan' => $penilaian['keterangan'],
											'penjelasan' => $penilaian['penjelasan'],
											'langkah_kerja' => $penilaian['langkah_kerja'],
											'jenis_bukti_dukung' => $penilaian['jenis_bukti_dukung']
										);
										$wpdb->insert('esakip_komponen_penilaian', $data_komponen_penilaian_baru);
										$id_komponen_penilaian_baru = $wpdb->insert_id;
										$komponen_penilaian_id_mapping[$penilaian['id']] = $id_komponen_penilaian_baru;

										$update_penilaian_lke = $wpdb->update(
											'esakip_pengisian_lke',
											array(
												'id_komponen' => $id_komponen_baru,
												'id_subkomponen' => $id_subkomponen_baru,
												'id_komponen_penilaian' => $id_komponen_penilaian_baru
											),
											array(
												'id_komponen' => $komponen['id'],
												'id_subkomponen' => $subkomponen['id'],
												'id_komponen_penilaian' => $penilaian['id'],
												'tahun_anggaran' => $tahun_anggaran,
												'active' => 1
											)
										);

										// Check if update data failed, if not then get data from history
										if (!$update_penilaian_lke) {
											$data_penilaian_lke_terbaru_history = $wpdb->get_row(
												$wpdb->prepare("
                                                SELECT *
                                                FROM esakip_pengisian_lke_history
                                                WHERE id_komponen = %d
                                                  AND id_subkomponen = %d 
                                                  AND id_komponen_penilaian = %d
                                                  AND tahun_anggaran = %d
                                                  AND id_jadwal = %d
                                            ", $komponen['id'], $subkomponen['id'], $penilaian['id'], $tahun_anggaran, $id_jadwal_sebelumnya),
												ARRAY_A
											);

											if (!empty($data_penilaian_lke_terbaru_history)) {
												$wpdb->insert('esakip_pengisian_lke', array(
													'id_user' => $data_penilaian_lke_terbaru_history['id_user'],
													'id_skpd' => $data_penilaian_lke_terbaru_history['id_skpd'],
													'id_user_penilai' => $data_penilaian_lke_terbaru_history['id_user_penilai'],
													'id_komponen' => $id_komponen_baru,
													'id_subkomponen' => $id_subkomponen_baru,
													'id_komponen_penilaian' => $id_komponen_penilaian_baru,
													'nilai_usulan' => $data_penilaian_lke_terbaru_history['nilai_usulan'],
													'nilai_penetapan' => $data_penilaian_lke_terbaru_history['nilai_penetapan'],
													'keterangan' => $data_penilaian_lke_terbaru_history['keterangan'],
													'keterangan_penilai' => $data_penilaian_lke_terbaru_history['keterangan_penilai'],
													'bukti_dukung' => $data_penilaian_lke_terbaru_history['bukti_dukung'],
													'create_at' => $data_penilaian_lke_terbaru_history['create_at'],
													'update_at' => $data_penilaian_lke_terbaru_history['update_at'],
													'tahun_anggaran' => $data_penilaian_lke_terbaru_history['tahun_anggaran'],
													'active' => 1
												));
											}
										}

										$data_kerangka_logis = $wpdb->get_results(
											$wpdb->prepare("
                                            SELECT * 
                                            FROM esakip_kontrol_kerangka_logis
                                            WHERE id_komponen_penilaian = %d
											  AND active = 1
                                        ", $penilaian['id']),
											ARRAY_A
										);

										foreach ($data_kerangka_logis as $kerangka_logis) {
											$id_komponen_pembanding_baru = $kerangka_logis['jenis_kerangka_logis'] == 2
												? $komponen_penilaian_id_mapping[$kerangka_logis['id_komponen_pembanding']]
												: $subkomponen_id_mapping[$kerangka_logis['id_komponen_pembanding']];

											$data_kerangka_logis_baru = array(
												'id_komponen_penilaian' => $id_komponen_penilaian_baru,
												'jenis_kerangka_logis' => $kerangka_logis['jenis_kerangka_logis'],
												'id_komponen_pembanding' => $id_komponen_pembanding_baru,
												'pesan_kesalahan' => $kerangka_logis['pesan_kesalahan'],
											);
											$wpdb->insert('esakip_kontrol_kerangka_logis', $data_kerangka_logis_baru);
										}

										$data_penilaian_custom = $wpdb->get_results(
											$wpdb->prepare("
                                            SELECT * 
                                            FROM esakip_penilaian_custom
                                            WHERE id_komponen_penilaian = %d
											  AND active = 1
                                        ", $penilaian['id']),
											ARRAY_A
										);

										foreach ($data_penilaian_custom as $custom) {
											$data_penilaian_custom_baru = array(
												'id_komponen_penilaian' => $id_komponen_penilaian_baru,
												'nama' => $custom['nama'],
												'nilai' => $custom['nilai'],
												'nomor_urut' => $custom['nomor_urut'],
											);
											$wpdb->insert('esakip_penilaian_custom', $data_penilaian_custom_baru);
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
												'keterangan' => 'Renstra Perangkat Daerah (2024-2026)',
												'nomor_urut' => '1.00',
												'penjelasan' => 'cukup jelas',
												'langkah_kerja' => 'Dapatkan dokumen berupa hard copy maupun soft copy',
												'jenis_bukti_dukung' => array(
													'esakip_renstra'
												)
											),
											array( //1
												'nama' => 'Dokumen perencanaan kinerja tahunan (Renja) telah disusun',
												'tipe' => '1',
												'keterangan' => 'Renja 2023 dan 2024',
												'nomor_urut' => '2.00',
												'penjelasan' => 'cukup jelas',
												'langkah_kerja' => 'Dapatkan dokumen berupa hard copy maupun soft copy',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'pesan_kesalahan' => 'SALAH dokumen RENSTRA tidak ada',
														'id_komponen_pembanding' => '0-0-0'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_renja_rkt'
												)
											),
											array( //2
												'nama' => 'Terdapat dokumen perencanaan anggaran yang mendukung kinerja',
												'tipe' => '1',
												'keterangan' => '- Renja 2023 dan 2024, - DPA 2024 dan DPPA 2023 (di dokumen lainnya)',
												'nomor_urut' => '3.00',
												'penjelasan' => 'Dokumen Perencanaan dan Anggaran Perangkat Daerah',
												'langkah_kerja' => 'Dapatkan dokumen berupa hard copy maupun soft copy',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-1',
														'pesan_kesalahan' => 'SALAH, Perencanaan kinerja tahunan tidak ada.'
													),
												),
												'jenis_bukti_dukung' => array(
													'esakip_renja_rkt',
													'esakip_dokumen_lainnya',
													'esakip_dpa'
												)
											),
											array( //3
												'nama' => 'Perjanjian Kinerja (PK) telah disusun',
												'tipe' => '1',
												'keterangan' => 'PK 2023 dan 2024',
												'nomor_urut' => '4.00',
												'penjelasan' => 'Perjanjian Kinerja JPT/Kepala Perangkat Daerah (Eselon II atau Eselon III)	',
												'langkah_kerja' => 'Dapatkan dokumen berupa hard copy maupun soft copy',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-1',
														'pesan_kesalahan' => 'SALAH, Perencanaan kinerja tahunan tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_perjanjian_kinerja'
												)
											),
											array( //4
												'nama' => 'PK telah menyajikan Indikator Tujuan/ Sasaran',
												'tipe' => '2',
												'keterangan' => 'PK 2023 dan 2024',
												'nomor_urut' => '5.00',
												'penjelasan' => 'a. apabila lebih dari 95% Indikator Tujuan/Sasaran telah diperjanjikan dalam PK Pemda/Satuan Kerja b. apabila 80%< Indikator Tujuan/Sasaran yang telah diperjanjikan dalam PK Pemda/Satuan Kerja < 95%; c. apabila 50%< IIndikator Tujuan/Sasaran yang telah diperjanjikan dalam PK Pemda/Satuan Kerja < 80%; d. apabila 10%< Indikator Tujuan/Sasaran yang telah diperjanjikan dalam PK Pemda/Satuan Kerja < 50% e. apabila Indikator Tujuan/Sasaran yang telah diperjanjikan dalam PK Pemda/Satuan Kerja < 10%',
												'langkah_kerja' => 'cek indikator tujuan/sasaran telah diperjanjikan dalam PK',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													),
												),
												'jenis_bukti_dukung' => array(
													'esakip_perjanjian_kinerja'
												)
											),
											array( //5
												'nama' => 'Terdapat dokumen Rencana Aksi',
												'tipe' => '1',
												'keterangan' => 'Rencana Aksi 2023 dan 2024',
												'penjelasan' => 'jika Rencana Aksi (RA) yang dimaksud merupakan penjabaran lebih lanjut dari target2 kinerja yang ada di Perjanjian Kinerja (PK)',
												'langkah_kerja' => 'Dapatkan dokumen berupa hard copy maupun soft copy, kemudian cek RA tsb merupakan penjabaran lebih lanjut dari target2 kinerjayang ada di PK',
												'nomor_urut' => '6.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													),
												),
												'jenis_bukti_dukung' => array(
													'esakip_rencana_aksi'
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
												'penjelasan' => 'Renstra dan Renja telah tersusun dengan ditandatangani pejabat berwenang sebagai penetapan (formal)',
												'langkah_kerja' => '-',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-1',
														'pesan_kesalahan' => 'SALAH, Perencanaan kinerja tahunan tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_renja_rkt',
													'esakip_renstra'
												)
											),
											array( //1
												'nama' => 'Renstra telah dipublikasikan tepat waktu',
												'tipe' => '1',
												'keterangan' => 'Screenshoot Renstra 2024-2026 di website Perangkat Daerah, esr dan aplikasi SAKIP',
												'penjelasan' => 'Dokumen Renstra telah dipublikasikan melalui website resmi Perangkat Daerah, Aplikasi SAKIP Kab Madiun dan website e-SAKIP REVIEW Kemen PAN RB tahun berjalan (batas waktu ditentukan)',
												'langkah_kerja' => '-',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_dokumen_lainnya',
													'esakip_renstra'
												)
											),
											array( //2
												'nama' => 'Renja telah dipublikasikan tepat waktu',
												'tipe' => '1',
												'penjelasan' => 'Dokumen Renja/RKT telah dipublikasikan melalui website resmi Perangkat Daerah, Aplikasi SAKIP dan website e-SAKIP REVIEW Kemen PAN RB tahun berjalan (batas waktu ditentukan)',
												'langkah_kerja' => '-',
												'keterangan' => 'Screenshoot Renja 2023 dan 2024 di website Perangkat Daerah, esr dan aplikasi SAKIP',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_dokumen_lainnya',
													'esakip_renja_rkt'
												)
											),
											array( //3
												'nama' => 'Perjanjian Kinerja telah dipublikasikan tepat waktu',
												'tipe' => '1',
												'keterangan' => 'Screenshoot PK 2023 dan 2024 di website Perangkat Daerah, esr dan aplikasi SAKIP',
												'nomor_urut' => '4.00',
												'penjelasan' => 'Dokumen Perjanjian Kinerja JPT/Kepala Perangkat Daerah (Eselon II)/ Eselon III Kepala OPD telah dipublikasikan melalui website resmi Perangkat Daerah, Aplikasi SAKIP dan website e-SAKIP REVIEW Kemen PAN RB tahun berjalan (batas waktu ditentukan)',
												'langkah_kerja' => '-',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-0-3',
														'pesan_kesalahan' => 'SALAH, PK tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_dokumen_lainnya',
													'esakip_perjanjian_kinerja'
												)
											),
											array( //4
												'nama' => 'Tujuan telah berorientasi hasil',
												'tipe' => '2',
												'keterangan' => 'Renstra Perangkat Daerah (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024',
												'nomor_urut' => '5.00',
												'penjelasan' => 'Kriteria berorientasi hasil: berkualitas outcome atau output penting bukan proses/kegiatan menggambarkan kondisi atau output penting yang ingin diwujudkan atau seharusnya terwujud terkait dengan isu strategis organisasi sesuai dengan tugas dan fungsi organisasi a. apabila tujuan dalam Perencanaan Kinerja telah memenuhi >90% kriteria berorientasi hasil; b. apabila tujuan dalam Perencanaan Kinerja telah memenuhi 75%< kriteria berorientasi hasil ≤90% c. apabila tujuan dalam Perencanaan Kinerja telah memenuhi 40%< kriteria berorientasi hasil ≤75%; d. apabila tujuan dalam Perencanaan Kinerja telah memenuhi 10%< kriteria berorientasi hasil ≤40% e. apabila tujuan dalam Perencanaan Kinerja telah memenuhi ≤10%kriteria berorientasi hasil b. apabila 75%< ukuran keberhasilan SMART ≤90% c. apabila 40%< ukuran keberhasilan SMART ≤75% d. apabila 10%< ukuran keberhasilan SMART ≤40% e. apabila ukuran keberhasilan yang SMART ≤10% Kriteria ukuran keberhasilan yang baik; SMART - Spesific: Tidak berdwimakna - Measureable: Dapat diukur, dapat diidentifikasi satuan atau parameternya - Achievable: Dapat dicapai, relevan dengan tugas fungsinya (domainnya) dan dalam kendalinya (contollable) - Relevance: Terkait langsung dengan (merepresentasikan) apa yang akan diukur - Timebound: Mengacu atau menggambarkan kurun waktu tertentu - Cukup, dari segi jumlah, ukuran keberhasilan yang ada harus cukup mengindikasikan tercapainya tujuan, sasaran dan hasil program',
												'langkah_kerja' => 'a. Lakukan penilaian apakah rumusan Tujuan Strategis telah berorientasi hasil; b. Teliti apakah terdapat kata yang dapat menggambarkan suatu ukuran keberhasilan. Contohnya : meningkatnya, menguatnya, terwujudnya, dll',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'pesan_kesalahan' => 'SALAH dokumen RENSTRA tidak ada',
														'id_komponen_pembanding' => '0-0-0'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_renstra',
													'esakip_renja_rkt',
													'esakip_perjanjian_kinerja'
												)
											),
											array( //5
												'nama' => 'Ukuran Keberhasilan (Indikator Kinerja) Tujuan telah memenuhi kriteria SMART.',
												'tipe' => '2',
												'keterangan' => 'Renstra Perangkat Daerah (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024',
												'nomor_urut' => '6.00',
												'penjelasan' => 'a. apabila lebih dari 90% ukuran keberhasilan tujuan dalam Perencanaan Kinerja telah memenuhi kriteria SMART dan Cukup; b. apabila 75%< ukuran keberhasilan SMART ≤90% c. apabila 40%< ukuran keberhasilan SMART ≤75% d. apabila 10%< ukuran keberhasilan SMART ≤40% e. apabila ukuran keberhasilan yang SMART ≤10% Kriteria ukuran keberhasilan yang baik; SMART Spesific: Tidak berdwimakna Measureable: Dapat diukur, dapat diidentifikasi satuan atau parameternya - Achievable: Dapat dicapai, relevan dengan tugas fungsinya (domainnya) dan dalam kendalinya (contollable) - Relevance: Terkait langsung dengan (merepresentasikan) apa yang akan diukur - Timebound: Mengacu atau menggambarkan kurun waktu tertentu - Cukup, dari segi jumlah, ukuran keberhasilan yang ada harus cukup mengindikasikan tercapainya tujuan, sasaran dan hasil program	',
												'langkah_kerja' => 'a. Lakukan penilaian apakah rumusan indikator tujuan telah memenuhi kriteria SMART; b. Teliti apakah terdapat kata yang dapat menggambarkan suatu ukuran keberhasilan. Contoh Indikator Terukur : jumlah, persentase, kategori, level/tingkatan, rasio, rata-rata, indeks, dll.',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_renstra',
													'esakip_renja_rkt',
													'esakip_perjanjian_kinerja'
												)
											),
											array( //6
												'nama' => 'Sasaran telah jelas berorientasi hasil',
												'tipe' => '2',
												'keterangan' => 'Renstra Perangkat Daerah (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024',
												'nomor_urut' => '7.00',
												'penjelasan' => 'a. apabila lebih dari 90% sasaran dalam renstra, renja telah berorientasi hasil; b. apabila 75%< berorientasi hasil < 90%; c. apabila 40%< berorientasi hasil <75%; d. apabila 10% < berorientasi hasil <40% e. apabila kondisi jangka menengah dan sasaran yg berorientasi hasil < 10%',
												'langkah_kerja' => 'a. Lakukan penilaian apakah rumusan Sasaran Strategis telah berorientasi hasil; b. Teliti apakah terdapat kata yang dapat menggambarkan suatu ukuran keberhasilan. Contohnya : meningkatnya, menguatnya, terwujudnya, dll.',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_renstra',
													'esakip_renja_rkt',
													'esakip_perjanjian_kinerja'
												)
											),
											array( //7
												'nama' => 'Ukuran Keberhasilan (Indikator Kinerja) Sasaran telah memenuhi kriteria SMART.',
												'tipe' => '2',
												'keterangan' => 'Renstra Perangkat Daerah (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024',
												'nomor_urut' => '8.00',
												'penjelasan' => 'a. apabila lebih dari 90% indikator tujuan/sasaran dalam Renstra/Renja telah memenuhi kriteria SMART dan Cukup; b. apabila 75%< Indikator SMART< 90%; c. apabila 40%< Indikator SMART<75%; d. apabila 10%< Indikator SMART<40% e. apabila indikator yang SMART < 10%',
												'langkah_kerja' => 'Lakukan penilaian apakah rumusan indikator Sasaran telah memenuhi kriteria SMART',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_renstra',
													'esakip_renja_rkt',
													'esakip_perjanjian_kinerja',
													'esakip_iku'
												)
											),
											array( //8
												'nama' => 'Indikator Kinerja Tujuan telah menggambarkan kondisi Tujuan yang harus dicapai, tertuang secara berkelanjutan (sustainable - tidak sering diganti dalam 1 periode Perencanaan Strategis).',
												'tipe' => '2',
												'keterangan' => 'Renstra Perangkat Daerah (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024',
												'penjelasan' => 'Jika Indikator Kinerja Sasaran diganti maksimal 1 kali dalam satu periode perencanaan Jika Indikator Kinerja Sasaran diganti maksimal 2 kali dalam satu periode perencanaan Jika Indikator Kinerja Sasaran diganti maksimal 3 kali dalam satu periode perencanaan Jika Indikator Kinerja Sasaran diganti maksimal 4 kali dalam satu periode perencanaan Jika Indikator Kinerja Sasaran diganti maksimal 5 kali dalam satu periode perencanaan',
												'langkah_kerja' => 'cukup jelas',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_renstra',
													'esakip_renja_rkt',
													'esakip_perjanjian_kinerja',
													'esakip_iku'
												)
											),
											array( //9
												'nama' => 'Indikator Kinerja Sasaran telah menggambarkan kondisi Sasaran yang harus dicapai, tertuang secara berkelanjutan (sustainable - tidak sering diganti dalam 1 periode Perencanaan Strategis).',
												'tipe' => '2',
												'keterangan' => 'Renstra Perangkat Daerah (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024, IKU 2024',
												'penjelasan' => 'a. Jika Indikator Kinerja Sasaran diganti maksimal 1 kali dalam satu periode perencanaan b. Jika Indikator Kinerja Sasaran diganti maksimal 2 kali dalam satu periode perencanaan c. Jika Indikator Kinerja Sasaran diganti maksimal 3 kali dalam satu periode perencanaan d. Jika Indikator Kinerja Sasaran diganti maksimal 4 kali dalam satu periode perencanaan e. Jika Indikator Kinerja Sasaran diganti maksimal 5 kali dalam satu periode perencanaan a. apabila lebih dari 90% indikator tujuan/sasaran dalam Renstra/Renja telah memenuhi kriteria SMART dan Cukup; b. apabila 75%< Indikator SMART< 90%; c. apabila 40%< Indikator SMART<75%; d. apabila 10%< Indikator SMART<40% e. apabila indikator yang SMART < 10%',
												'langkah_kerja' => 'Cukup Jelas',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_renstra',
													'esakip_renja_rkt',
													'esakip_perjanjian_kinerja',
													'esakip_iku'
												)
											),
											array( //10
												'nama' => 'Target yang ditetapkan dalam Perencanaan Kinerja dapat dicapai (achievable) dan realistis.',
												'tipe' => '2',
												'keterangan' => 'Renstra Perangkat Daerah (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024',
												'penjelasan' => 'a. apabila > 90% target yang ditetapkan memenuhi kriteria target yang dapat dicapai dan realistis; b. apabila 75% < target yang memenuhi seluruh kriteria < 90%; c. apabila sebagian besar ( > 75%) target yang ditetapkan tidak d. berdasarkan basis data yang memadai dan argumen yang logis; d. apabila sebagian besar ( > 75%) target yang ditetapkan tidak berdasarkan indikator yang SMART; e. apabila sebagian besar ( > 75%) target yang ditetapkan tidak memenuhi kriteria target yang dapat dicapai dan realistis. Kriteria Target yg baik: - Menggambarkan suatu tingkatan tertentu yang seharusnya dicapai (termasuk tingkatan yang standar, generally accepted) - Selaras dengan RPJMN/RPJMD/Renstra; - Berdasarkan (relevan dgn) indikator yg SMART; - Berdasarkan basis data yang memadai Berdasarkan argumen dan perhitungan yang logis',
												'langkah_kerja' => '-',
												'nomor_urut' => '11.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '0-1-7',
														'pesan_kesalahan' => 'SALAH, Indikator Tujuan belum tepat'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_renstra',
													'esakip_renja_rkt',
													'esakip_perjanjian_kinerja'
												)
											),
											array( //11
												'nama' => 'Setiap Dokumen Perencanaan Kinerja (Renstra, Renja, PK) telah menggambarkan hubungan yang berkesinambungan, serta selaras antara Kondisi/Hasil yang akan dicapai di setiap level jabatan (Cascading Kinerja).',
												'tipe' => '2',
												'keterangan' => 'Renstra Perangkat Daerah (2024-2026), Renja 2023 dan 2024, PK 2023 dan 2024,  cascading dan pohon kinerja (di dokumen lainnya)',
												'penjelasan' => 'a. apabila > 90% dokumen perencanaan kinerja telah selaras dengan cascading kinerja; b. apabila > 75% dokumen perencanaan kinerja telah selaras dengan cascading kinerja ≤90% c. apabila > 40% dokumen perencanaan kinerja telah selaras dengan cascading kinerja ≤75% d. apabila > 10% dokumen perencanaan kinerja telah selaras dengan cascading kinerja ≤40% e. apabila ≤10% dokumen perencanaan kinerja telah selaras dengan cascading kinerja;',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_renstra',
													'esakip_renja_rkt',
													'esakip_perjanjian_kinerja',
													'esakip_dokumen_lainnya',
													'esakip_pohon_kinerja_dan_cascading'
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
												'keterangan' => '- Renstra Perangkat Daerah (2024-2026), Renja 2023 dan 2024, Rencana Aksi 2023 dan 2024 - DPA 2024 dan DPPA 2023 (di dokumen lainnya)',
												'penjelasan' => 'a. apabila lebih dari 90% indikator tujuan dan sasaran yang ada di Renstra telah selaras dengan indikator hasil/capaian program yang ada dalam rencana kinerja tahunan; b. apabila 75% < keselarasan indikator tujuan dan sasaran RPJMD/Renstra dengan indikator hasil/capaian program dalam rencana kinerja tahunan < 90%; c. apabila 40% < keselarasan indikator tujuan dan sasaran RPJMD/Renstra dengan indikator hasil/capaian program dalam rencana kinerja tahunan < 75%; d. apabila 10% < keselarasan indikator tujuan dan sasaran RPJMD/Renstra dengan indikator hasil/capaian program dalam rencana kinerja tahunan < 40% e. apabila keselarasan indikator tujuan dan sasaran RPJMD/Renstra dengan indikator hasil/capaian program dalam rencana kinerja tahunan ≤10% Kriteria Selaras atau (dapat) dijadikan acuan: - Target2 kinerja jangka menengah dalam RPJMD/Renstra telah di-breakdown dalam (selaras dengan) target2 kinerja tahunan dalam rencana knerja tahunan - Sasaran2 yang ada di RPJMD/Renstra dijadikan outcome atau hasil2 program yang akan diwujudkan dalam rencana kinerja tahunan - Sasaran, indikator dan target yang ditetapkan dalam perencanaan satuan kerja menjadi penyebab (memiliki hubungan kausalitas) terwujudnya outcome atau hasil2 program yang ada di rencana kinerja tahunan Catatan: pemilihan a/b/c/d/e dengan asumsi indikator tujuan dan sasaran di RPJMD/Renstra telah memenuhi kriteria SMART	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_renstra',
													'esakip_renja_rkt',
													'esakip_rencana_aksi',
													'esakip_dokumen_lainnya',
													'esakip_dpa'
												)
											),
											array( //1
												'nama' => 'Target jangka menengah dalam Renstra telah dimonitor pencapaiannya sampai dengan tahun berjalan',
												'tipe' => '2',
												'keterangan' => 'Renstra Perangkat Daerah (2024-2026), Laporan Kinerja 2023',
												'penjelasan' => 'a. apabila target jangka menengah (JM) telah dimonitor dan memenuhi seluruh kriteria yang disebutkan dibawah; b. apabila target JM telah dimonitor berdasarkan kriteria yang disebutkan c. dibawah, namun belum seluruh rekomendasi ditindaklanjuti; d. apabila target JM telah dimonitor dengan kriteria tersebut namun tidak ada tindak lanjut terhadap rekomendasi yang diberikan e. apabila monitoring target JM dilakukan secara insidentil, tidak terjadual, tanpa SOP atau mekanisme yang jelas; Target JM tidak dimonitor Monitoring target (kinerja) jangka menengah mengacu pada kriteria sbb: - Terdapat breakdown target kinerja jangka menegah kedalam target2 tahunan dan periodik yang selaras dan terukur; - Terdapat pihak atau bagian yang bertanggungjawab untuk melaporkan dan yang memonitor kinerja secara periodik; - Terdapat jadual, mekanisme atau SOP yang jelas tentang mekanisme monitoring Renstra secara periodik; - Terdapat dokumentasi hasil monitoring/ capaian kinerja jangka menengah dilaporkan progressnya dalam laporan kinerja/ evaluasi internal - Terdapat tindak lanjut atas hasil monitoring	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_renstra',
													'esakip_laporan_kinerja'
												)
											),
											array( //2
												'nama' => 'Anggaran yang ditetapkan telah mengacu pada Kinerja yang ingin dicapai',
												'tipe' => '2',
												'keterangan' => '- Renstra Perangkat Daerah (2024-2026), Renja 2023 dan 2024, Rencana Aksi 2023 dan 2024 - DPA 2024 dan DPPA 2023 (di dokumen lainnya)',
												'penjelasan' => 'a. Jika >90 % target-target kinerja sasaran dalam rencana kinerja tahunan menjadi prasyarat dalam pengajuan dan pengaloksian anggaran b. Jika >75 % target-target kinerja sasaran dalam rencana kinerja tahunan menjadi prasyarat dalam pengajuan dan pengaloksian anggaran ≤90% c. Jika >40 % target-target kinerja sasaran dalam rencana kinerja tahunan menjadi prasyarat dalam pengajuan dan pengaloksian anggaran ≤75% d. Jika >10 % target-target kinerja sasaran dalam rencana kinerja tahunan menjadi prasyarat dalam pengajuan dan pengaloksian anggaran ≤40% e. Jika ≤10% target-target kinerja sasaran dalam rencana kinerja tahunan menjadi prasyarat dalam pengajuan dan pengaloksian anggaran',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_renstra',
													'esakip_renja_rkt',
													'esakip_rencana_aksi',
													'esakip_dokumen_lainnya',
													'esakip_dpa'
												)
											),
											array( //3
												'nama' => 'Aktivitas yang dilaksanakan telah mendukung Kinerja',
												'tipe' => '2',
												'keterangan' => 'Rencana aksi 2023 dan 2024',
												'nomor_urut' => '4.00',
												'penjelasan' => 'a. Jika >90% aktivitas yang dilakukan oleh Perangkat Daerah menunjang target kinerja (outcome) b. Jika >75% aktivitas yang dilakukan oleh Perangkat Daerah menunjang target kinerja (outcome) ≤90% c. Jika >40% aktivitas yang dilakukan oleh Perangkat Daerah menunjang target kinerja (outcome) ≤75% d. Jika >10% aktivitas yang dilakukan oleh Perangkat Daerah menunjang target kinerja (outcome) ≤40% e. Jika ≤10% aktivitas yang dilakukan oleh Perangkat Daerah menunjang target kinerja (outcome) Definisi \"Aktivitas\" antara lain : kegiatan, rencana aksi, perencanaan anggaran, rencana strategi, koordinasi, kebijakan, solusi, perumusan, penetapan',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_rencana_aksi'
												)
											),
											array( //4
												'nama' => 'Target kinerja yang diperjanjikan pada Perjanjian Kinerja telah digunakan untuk mengukur keberhasilan',
												'tipe' => '2',
												'keterangan' => '- Perjanjian Kinerja 2023 dan 2024, SKP 2023 dan 2024 - SK dan bukti pemberian reward punishment (dokumen lainnya)',
												'penjelasan' => 'a. apabila terdapat bukti yang cukup bahwa pemanfaatan PK yang di-ttd-i memenuhi seluruh kriteria yang ditetapkan; b. apabila terdapat bukti yang cukup bahwa PK yang di-ttd-i dijadikan dasar untuk mengukur dan menyimpulkan keberhasilan maupun kegagalan ; c. apabila terdapat bukti yang cukup bahwa PK yang di-ttd-i telah diukur dan hasil pengukuran telah diketahui oleh atasan (pemberi amanah); d. apabila PK yang di-ttd-i sebatas telah dilakukan monitoring e. apabila terhadap PK yang ditandatangani tidak dilakukan pengukuran atau monitoring Kriteria Pemanfaatan target kinerja untuk mengukur keberhasilan; - (Capaian) target kinerja dijadikan dasar untuk memberikan penghargaan (reward); - (Capaian) target kinerja dijadikan dasar untuk memilih dan memilah yang berkinerja dengan yang kurang (tidak) berkinerja; - (Capaian) target kinerja digunakan sebagai cara untuk menyimpulkan atau memberikan predikat (baik, cukup, kurang, tercapai, tidak tercapai, berhasil, gagal, dll) suatu kondisi atau keadaan	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_perjanjian_kinerja',
													'esakip_dokumen_lainnya',
													'esakip_skp'
												)
											),
											array( //5
												'nama' => 'Setiap pegawai memahami dan peduli serta berkomitmen dalam mencapai kinerja yang telah direncanakan dalam Sasaran Kinerja Pegawai (SKP)',
												'tipe' => '2',
												'keterangan' => 'SKP 2023 dan 2024, PK 2023 dan 2024',
												'penjelasan' => 'a. Apabila >90% pegawai berkomitmen dalam mencapai kinerja yang telah direncakanan dalam Sasaran Kinerja Pegawai (SKP) b. Apabila >75% pegawai berkomitmen dalam mencapai kinerja yang telah direncakanan dalam Sasaran Kinerja Pegawai (SKP) ≤90% c. Apabila >40% pegawai berkomitmen dalam mencapai kinerja yang telah direncakanan dalam Sasaran Kinerja Pegawai (SKP) ≤75% d. Apabila >10% pegawai berkomitmen dalam mencapai kinerja yang telah direncakanan dalam Sasaran Kinerja Pegawai (SKP) ≤40% e. Apabila ≤10% pegawai berkomitmen dalam mencapai kinerja yang telah direncakanan dalam Sasaran Kinerja Pegawai (SKP)	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_skp',
													'esakip_perjanjian_kinerja'
												)
											),
											array( //6
												'nama' => 'Dokumen Renstra telah direviu secara berkala',
												'tipe' => '2',
												'keterangan' => 'Screenshoot aplikasi e-monev E-80 dan E-81 (dokumen lainnya)',
												'penjelasan' => 'a. apabila Renstra telah direviu dan hasilnya menunjukkan b. kondisi yang lebih baik (terdapat inovasi); c. apabila Renstra telah direviu secara berkala dan hasilnya masih relevan dengan kondisi saat ini; d. apabila Renstra telah direviu, ada upaya perbaikan namun belum ada perbaikan yang signifikan; e apabila Renstra telah direviu Tidak ada reviu/tidak diketahui apakah Renstra masih relevan dengan kondisi saat ini	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_dokumen_lainnya'
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
												'penjelasan' => 'cukup jelas',
												'langkah_kerja' => 'Cek apakah terdapat aturan/pedoman/SOP terkait pengumpulan data kinerja pada satuan kerja.',
												'nomor_urut' => '1.00',
												'jenis_bukti_dukung' => array(
													'esakip_iku'
												)
											),
											array( //1
												'nama' => 'Terdapat Definisi Operasional yang jelas atas kinerja dan cara mengukur indikator kinerja.',
												'tipe' => '2',
												'keterangan' => 'Dokumen IKU 2024-2026',
												'penjelasan' => 'a. Terdapat Definisi Operasional >90% dari Indikator Kinerja dan Pengukuran Indikator Kinerja. b. Terdapat Definisi Operasional >75% dari Indikator Kinerja dan Pengukuran Indikator Kinerja ≤90% c. Terdapat Definisi Operasional >40% dari Indikator Kinerja dan Pengukuran Indikator Kinerja ≤75% d. Terdapat Definisi Operasional >10% dari Indikator Kinerja dan Pengukuran Indikator Kinerja ≤40% e. Terdapat Definisi Operasional ≤10% dari Indikator Kinerja dan Pengukuran Indikator Kinerja.	',
												'langkah_kerja' => '-',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_iku'
												)
											),
											array( //2
												'nama' => 'Terdapat mekanisme yang jelas terhadap pengumpulan data kinerja yang dapat diandalkan.',
												'tipe' => '2',
												'keterangan' => 'SOP pengumpulan data kinerja (di dokumen lainnya)',
												'penjelasan' => 'a. apabila mekanisme pengumpulan data kinerja memenuhi seluruh kriteria yang ditetapkan; b. apabila mekanisme pengumpulan data kinerja memenuhi kriteria yang ditetapkan kecuali penanggung jawab yang jelas; c. apabila > 80% capaian (realisasi) kinerja dapat diyakini validitas datanya; d. apabila realisasi data kinerja kurang dapat diyakini validitasnya (validitas sumber data diragukan) apabila realisasi data kinerja tidak dapat diverifikasi Mekanisme pengumpulan data yang memadai dengan kriteria sbb: - Terdapat pedoman atau SOP tentang pengumpulan data kinerja yang up to date; - Ada kemudahan untuk menelusuri sumber datanya yang valid; - Ada kemudahan untuk mengakses data bagi pihak yang berkepentingan; - Terdapat penanggungjawab yang jelas; - Jelas waktu deliverynya; - Terdapat SOP yang jelas jika terjadi kesalahan data',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_dokumen_lainnya'
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
												'penjelasan' => 'a. apabila pengukuran kinerja telah dilakukan periodik dan memenuhi seluruh kriteria yang disebutkan dibawah; b. apabila pengukuran kinerja telah dilakukan periodik berdasarkan kriteria yang disebutkan dibawah, namun belum seluruh rekomendasi ditindaklanjuti; c. apabila target kinerja telah dilakukan periodik dengan kriteria tersebut namun tidak ada tindak lanjut terhadap rekomendasi yang diberikan; d. apabila pengukuran kinerja dilakukan secara insidentil, tidak terjadual, tanpa SOP atau mekanisme yang jelas; e. Pengukuran kinerja tidak dimonitor. Pengukuran capaian kinerja mengacu pada prasyarat sbb: - Terdapat pengukuran kinerja tahunan yang selanjutnya dapat dilakukan secara triwulan; - Terdapat jadual, mekanisme atau SOP yang jelas tentang mekanisme pengukuran kinerja secara periodik; - Terdapat dokumentasi hasil pengukuran kinerja - Terdapat tindak lanjut atas hasil pengukuran kinerja',
												'langkah_kerja' => '-',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, IKU tidak ada.'
													)

												),
												'jenis_bukti_dukung' => array(
													'esakip_pengukuran_kinerja',
													'esakip_skp'
												)
											),
											array(
												'nama' => 'Data kinerja yang dikumpulkan telah relevan untuk mengukur capaian kinerja yang diharapkan.',
												'tipe' => '2',
												'keterangan' => 'Dokumen pengukuran kinerja Tahun 2023 dan 2024, SKP 2023 dan 2024,  Laporan Kinerja 2023',
												'penjelasan' => 'a. apabila lebih dari 90% data (capaian) kinerja yang dihasilkan telah relevan; b. apabila 75% < data (capaian) kinerja yang telah relevan < 90%; c. apabila 40% < data (capaian) kinerja yang telah relevan < 75%; d. apabila 10% < data (capaian) kinerja yang telah relevan < 40% e. apabila data (capaian) kinerja yang telah relevan < 10%',
												'langkah_kerja' => '-',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, nilai lebih tinggi dari IKU sebagai ukuran kinerja.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_pengukuran_kinerja',
													'esakip_skp',
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Data kinerja yang dikumpulkan telah mendukung capaian kinerja yang diharapkan.',
												'tipe' => '2',
												'keterangan' => 'Dokumen pengukuran kinerja Tahun 2023 dan 2024, SKP 2023 dan 2024,  Laporan Kinerja 2023',
												'penjelasan' => 'a. apabila lebih dari 90% data (capaian) kinerja yang dihasilkan telah mendukung; b. apabila 75% < data (capaian) kinerja yang telah mendukung < 90%; c. apabila 40% < data (capaian) kinerja yang telah mendukung < 75%; d. apabila 10% < data (capaian) kinerja yang telah mendukung < 40% e. apabila data (capaian) kinerja yang telah mendukung < 10%	',
												'langkah_kerja' => '-',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, nilai lebih tinggi dari IKU sebagai ukuran kinerja.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_pengukuran_kinerja',
													'esakip_skp',
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Pengumpulan data kinerja atas Rencana Aksi dilakukan secara berkala (bulanan/triwulanan/semester)',
												'tipe' => '1',
												'keterangan' => 'Pengukuran rencana aksi Tahun 2023 dan 2024',
												'penjelasan' => 'apabila seluruh target yang ada dalam Rencana Aksi telah diukur realisasinya secara berkala (bulanan/triwulanan/ semester)',
												'langkah_kerja' => '-',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, nilai lebih tinggi dari IKU sebagai ukuran kinerja.'
													)
												),
												'jenis_bukti_dukung' => array()
											),
											array(
												'nama' => 'Pengukuran kinerja sudah dilakukan secara berjenjang',
												'tipe' => '2',
												'keterangan' => 'Dokumen pengukuran kinerja Tahun 2023 dan 2024, SKP 2023 dan 2024',
												'penjelasan' => 'a. apabila PD telah melakukan >90% pengukuran kinerja secara berjenjang mulai dari staf, manajerial sampai kepada pimpinan tertinggi b. apabila PD telah melakukan >75% pengukuran kinerja secara berjenjang mulai dari staf, manajerial sampai kepada pimpinan tertinggi ≤90% c. apabila PD telah melakukan >40% pengukuran kinerja secara berjenjang mulai dari staf, manajerial sampai kepada pimpinan tertinggi ≤75% d. apabila PD telah melakukan >10% pengukuran kinerja secara berjenjang mulai dari staf, manajerial sampai kepada pimpinan tertinggi ≤40% e. apabila PD telah melakukan ≤10% pengukuran kinerja secara berjenjang mulai dari staf, manajerial sampai kepada pimpinan tertinggi',
												'langkah_kerja' => '-',
												'nomor_urut' => '5.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '1-0-0',
														'pesan_kesalahan' => 'SALAH, nilai lebih tinggi dari IKU sebagai ukuran kinerja.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_pengukuran_kinerja',
													'esakip_skp'
												)
											),
											array(
												'nama' => 'Pengumpulan data kinerja telah memanfaatkan Teknologi Informasi (Aplikasi).',
												'tipe' => '1',
												'keterangan' => 'Screenshoot aplikasi EP3, E-monev (dokumen lainnya)',
												'penjelasan' => 'cukup jelas',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_dokumen_lainnya'
												)
											),
											array(
												'nama' => 'Pengukuran capaian kinerja telah memanfaatkan Teknologi Informasi (Aplikasi).',
												'tipe' => '1',
												'keterangan' => 'Screenshoot aplikasi EP3, E-monev (dokumen lainnya)',
												'penjelasan' => 'apabila PD/Pemda telah melakukan pengukuran capaian kinerja telah menggunakan bantuan teknologi sehingga capaian kinerja dapat diidentifikasi secara lebih tepat dan cepat	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_dokumen_lainnya'
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
												'penjelasan' => 'Jawaban tentang Implementasi perencanaan kinerja tahunan harus selalu dikaitkan dengan (dipengaruhi oleh) kondisi (jawaban) tentang Pemenuhan dan Kualitas perencanaan kinerja tahunan a. Jika hasil pengukuran capaian kinerja dalam rencana kinerja tahunan telah dimanfaatkan >90% sebagai dasar pemberian reward and punishment b. Jika hasil pengukuran capaian kinerja dalam rencana kinerja tahunan telah dimanfaatkan >75% sebagai dasar pemberian reward and punishment ≤90% c. Jika hasil pengukuran capaian kinerja dalam rencana kinerja tahunan telah dimanfaatkan >40% sebagai dasar pemberian reward and punishment ≤75% d. Jika hasil pengukuran capaian kinerja dalam rencana kinerja tahunan telah dimanfaatkan >10% sebagai dasar pemberian reward and punishment ≤40% e. Jika hasil pengukuran capaian kinerja dalam rencana kinerja tahunan telah dimanfaatkan ≤10% sebagai dasar pemberian reward and punishment hasil pengukuran dikatakan terkait dengan reward & punishment apabila terdapat perbedaan (dapat diidentifikasi) tingkat reward & punishment antara lain: - pejabat/pegawai yang berkinerja dengan yang tidak berkinerja (tidak jelas kinerjanya) - pejabat/pegawai yang mencapai target dengan yang tidak mencapai target - pejabat/pegawai yang selesai tepat waktu dengan yang tidak tepat waktu (tidak selesai) - pejabat/pegawai dengan capaian diatas standar dengan yang standar	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_dokumen_lainnya',
													'esakip_pengukuran_kinerja'
												)
											),
											array(
												'nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian (Refocusing) Organisasi.',
												'tipe' => '2',
												'keterangan' => '- Pengukuran Kinerja 2023 dan 2024 - Screenshoot EP3 (dokumen lainnya)',
												'penjelasan' => 'a. Jika refocusing dilakukan >90% dari tindaklanjut hasil pengukuran capaian kinerja dalam rencana kinerja tahunan b. Jika refocusing dilakukan >75% dari tindaklanjut hasil pengukuran capaian kinerja dalam rencana kinerja tahunan ≤90% c. Jika refocusing dilakukan >40% dari tindaklanjut hasil pengukuran capaian kinerja dalam rencana kinerja tahunan ≤75% d. Jika refocusing dilakukan >10% dari tindaklanjut hasil pengukuran capaian kinerja dalam rencana kinerja tahunan ≤40% e. Jika refocusing dilakukan ≤10% dari tindaklanjut hasil pengukuran capaian kinerja dalam rencana kinerja tahunan	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_dokumen_lainnya',
													'esakip_pengukuran_kinerja'
												)
											),
											array(
												'nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Strategi dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; Rencana Aksi 2024',
												'penjelasan' => 'a. Jika penyesuaian strategi dilakukan >90% dari tindak lanjut hasil pengukuran capaian kinerja dalam rencana kinerja tahunan b. Jika penyesuaian strategi dilakukan >75% dari tindak lanjut hasil pengukuran capaian kinerja dalam rencana kinerja tahunan ≤90% c. Jika penyesuaian strategi dilakukan >40% dari tindak lanjut hasil pengukuran capaian kinerja dalam rencana kinerja tahunan ≤75% d. Jika penyesuaian strategi dilakukan >10% dari tindak lanjut hasil pengukuran capaian kinerja dalam rencana kinerja tahunan ≤40% e. Jika penyesuaian strategi dilakukan ≤10% dari tindak lanjut hasil pengukuran capaian kinerja dalam rencana kinerja tahunan	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_pengukuran_kinerja',
													'esakip_rencana_aksi',
													'esakip_evaluasi_internal'
												)
											),
											array(
												'nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Kebijakan dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; Rencana Aksi 2024',
												'penjelasan' => 'a. Jika kebijakan yang dihasilkan merupakan >90% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan b. Jika kebijakan yang dihasilkan merupakan >75% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan ≤90% c. Jika kebijakan yang dihasilkan merupakan >40% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan ≤75% d. Jika kebijakan yang dihasilkan merupakan >10% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan ≤40% e. Jika kebijakan yang dihasilkan merupakan ≤10% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_pengukuran_kinerja',
													'esakip_rencana_aksi',
													'esakip_evaluasi_internal'
												)
											),
											array(
												'nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Aktivitas dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; Rencana Aksi 2024',
												'penjelasan' => 'a. Jika aktivitas yang dilakukan merupakan >90% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan b. Jika aktivitas yang dilakukan merupakan >75% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan ≤90% c. Jika aktivitas yang dilakukan merupakan >40% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan ≤75% d. Jika aktivitas yang dilakukan merupakan >10% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan ≤40% e. Jika aktivitas yang dilakukan merupakan ≤10% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_pengukuran_kinerja',
													'esakip_rencana_aksi',
													'esakip_evaluasi_internal'
												)
											),
											array(
												'nama' => 'Pengukuran kinerja telah mempengaruhi penyesuaian Anggaran dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Dokumen Pengukuran Kinerja 2023 dan 2024;  Evaluasi Internal 2023 dan 2024; DPA 2024 dan DPPA 2023',
												'penjelasan' => 'a. Jika anggaran yang dibutuhkan merupakan >90% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan b. Jika anggaran yang dibutuhkan merupakan >75% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan ≤90% c. Jika anggaran yang dibutuhkan merupakan >40% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan ≤75% d. Jika anggaran yang dibutuhkan merupakan >10% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan ≤40% e. Jika anggaran yang diutuhkan merupakan ≤10% dari tindak lanjut dari pengukuran capaian kinerja dalam rencana kinerja tahunan	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_pengukuran_kinerja',
													'esakip_dokumen_lainnya',
													'esakip_evaluasi_internal',
													'esakip_dpa'
												)
											),
											array(
												'nama' => 'Terdapat efisiensi atas penggunaan anggaran dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => '- Laporan Kinerja 2023;  - DPPA 2023 (di dokumen lainnya)',
												'penjelasan' => 'a. Jika anggaran yang dibutuhkan >90% dari sasaran kinerja yang telah memuat outcome dan sesuai dengan kapasitas organisasi b. Jika anggaran yang dibutuhkan >75% dari sasaran kinerja yang telah memuat outcome dan sesuai dengan kapasitas organisasi ≤90% c. Jika anggaran yang dibutuhkan >40% dari sasaran kinerja yang telah memuat outcome dan sesuai dengan kapasitas organisasi ≤75% d. Jika anggaran yang dibutuhkan >10% dari sasaran kinerja yang telah memuat outcome dan sesuai dengan kapasitas organisasi ≤40% e. Jika anggaran yang dibutuhkan ≤10% dari sasaran kinerja yang telah memuat outcome dan sesuai dengan kapasitas organisasi	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja',
													'esakip_dokumen_lainnya',
													'esakip_dpa'
												)
											),
											array(
												'nama' => 'Setiap unit/satuan kerja memahami dan peduli atas hasil pengukuran kinerja.',
												'tipe' => '2',
												'keterangan' => NULL,
												'penjelasan' => 'a. Jika pengukuran kinerja mempengaruhi tindaklanjut >90% unit/satuan kerja terhadap pembenahan yang harus dilakukan berdasarkan hasil pengukuran kinerja b. Jika pengukuran kinerja mempengaruhi tindaklanjut >75% unit/satuan kerja terhadap pembenahan yang harus dilakukan berdasarkan hasil pengukuran kinerja ≤90% c. Jika pengukuran kinerja mempengaruhi tindaklanjut >40% unit/satuan kerja terhadap pembenahan yang harus dilakukan berdasarkan hasil pengukuran kinerja ≤750% d. Jika pengukuran kinerja mempengaruhi tindaklanjut >10% unit/satuan kerja terhadap pembenahan yang harus dilakukan berdasarkan hasil pengukuran kinerja ≤40% e. Jika pengukuran kinerja mempengaruhi tindaklanjut ≤10% unit/satuan kerja terhadap pembenahan yang harus dilakukan berdasarkan hasil pengukuran kinerja	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_evaluasi_internal',
													'esakip_dokumen_lainnya'
												)
											),
											array(
												'nama' => 'Setiap pegawai memahami dan peduli atas hasil pengukuran kinerja.',
												'tipe' => '2',
												'keterangan' => NULL,
												'penjelasan' => 'a. Jika pengukuran kinerja mempengaruhi aksi >90% pegawai terhadap tindaklanjut pembenahan yang harus dilakukan berdasarkan hasil pengukuran kinerja b. Jika pengukuran kinerja mempengaruhi aksi >75% pegawai terhadap tindaklanjut pembenahan yang harus dilakukan berdasarkan hasil pengukuran kinerja ≤90% c. Jika pengukuran kinerja mempengaruhi aksi >40% pegawai terhadap tindaklanjut pembenahan yang harus dilakukan berdasarkan hasil pengukuran kinerja ≤75% d. Jika pengukuran kinerja mempengaruhi aksi >10% pegawai terhadap tindaklanjut pembenahan yang harus dilakukan berdasarkan hasil pengukuran kinerja ≤40% e. Jika pengukuran kinerja mempengaruhi aksi ≤10% pegawai terhadap tindaklanjut pembenahan yang harus dilakukan berdasarkan hasil pengukuran kinerja',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_evaluasi_internal',
													'esakip_dokumen_lainnya'
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
												'penjelasan' => 'Cukup jelas',
												'langkah_kerja' => '-',
												'nomor_urut' => '1.00',
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array( //1
												'nama' => 'Dokumen Laporan Kinerja telah disusun secara berkala.',
												'tipe' => '1',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'Jika Laporan Kinerja disusun setiap tahun',
												'langkah_kerja' => '-',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array( //2
												'nama' => 'Dokumen Laporan Kinerja telah diformalkan.',
												'tipe' => '1',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'Jika Laporan Kinerja telah tersusun dengan ditandatangani pejabat berwenang sebagai penetapan (formal)	',
												'langkah_kerja' => '-',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah direviu.',
												'tipe' => '1',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'Cukup jelas',
												'langkah_kerja' => '-',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah dipublikasikan.',
												'tipe' => '1',
												'keterangan' => 'Screenshoot Laporan Kinerja 2023 pada website, esr, aplikasi sakip kab madiun (dokumen lainnya)',
												'penjelasan' => 'Dokumen Renstra telah dipublikasikan melalui website resmi Perangkat Daerah, aplikasi SAKIP dan website e-SAKIP REVIEW Kemen PAN RB tahun berjalan (optional dengan batas waktu yang telah ditentukan)	',
												'langkah_kerja' => '-',
												'nomor_urut' => '5.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah disampaikan tepat waktu.',
												'tipe' => '1',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'jika laporan kinerja disampaikan sesaui dengan batas waktu yang ditetapkan (bulan Pebruari tahun berikutnya)',
												'langkah_kerja' => '-',
												'nomor_urut' => '6.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
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
												'penjelasan' => 'Kriteria standart adalah menyajikan susunan Laporan Kinerja berdasarkan Permenpan RB 53 Tahun 2014 Bab I : Pendahuluan (Profil Organisasi, SOTK, SDM) Bab II : Perencanaan Kinerja ( Ringkasan Renstra, RKT, PK Tahun bersangkutan) Bab III : Akuntabilitas Kinerja A. Capaian Kinerja 1. Perbandingan target realisasi kinerja tahun ini 2. Perbandingan target realisasi kinerja tahun ini dg tahun lalu & beberapa tahun sebelumnya 3. Perbandingan realisasi kinerja tahun ini dg target jangka menengah 4. Perbandingan realisasi kinerja tahun ini dg target nasional (jika ada) 5. Analisa penyebab keberhasilan/ kegagalan kinerja serta solusi yang dilakukan 6. Analisa efisiensi penggunaan sumber daya 7. Analisa program/kegiatan penunjang keberhasilan/ kegagalan capaian kinerja B. Realisasi Anggaran Bab IV : Penutup (Simpulan umum capaian kinerja organisasi dan langkah yang akan dilakukan di masa mendatang untuk meningkatkan kinerja) a. apabila dokumen laporan kinerja sesuai dengan kriteria standar lebih dari 90% b. apabila 75%< sesuai dengan kriteria standar < 90%; c. apabila 40%< sesuai dengan kriteria standar <75%; d. apabila 10%< sesuai dengan kriteria standar <40% e. apabila dokumen laporan kinerja sesuai dengan kriteria standar lebih dari ≤ 10%	',
												'langkah_kerja' => '-',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '2-0-0',
														'pesan_kesalahan' => 'SALAH, Laporan Kinerja tidak ada.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah mengungkap seluruh informasi tentang pencapaian kinerja.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'a. Jika dokumen laporan kinerja telah mengungkap > 90% informasi pencapaian kinerja b. Jika 75% < informasi pencapaian kinerja ≤ 90% c. Jika 40% < informasi pencapaian kinerja ≤ 75% d. Jika 10% < informasi pencapaian kinerja ≤ 40% e. Jika dokumen laporan kinerja telah mengungkap ≤ 10% informasi pencapaian kinerja	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan perbandingan realisasi kinerja dengan target tahunan.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'a. Jika dokumen laporan kinerja telah menginfokan > 90% perbandingan realisasi kinerja dengan target tahunan b. Jika 75% < info perbandingan realisasi kinerja dengan target tahunan ≤ 90% c. Jika 40% < info perbandingan realisasi kinerja dengan target tahunan ≤ 75% d. Jika 10% < info perbandingan realisasi kinerja dengan target tahunan ≤ 40% e. Jika dokumen laporan kinerja telah menginfokan ≤ 10% perbandingan realisasi kinerja dengan target tahunan',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan perbandingan realisasi kinerja dengan target jangka menengah.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'a. Jika dokumen laporan kinerja telah menginfokan > 90% perbandingan realisasi kinerja dengan target jangka menengah b. Jika 75% < info perbandingan realisasi kinerja dengan target jangka menengah ≤ 90% c. Jika 40% < info perbandingan realisasi kinerja dengan target jangka menengah ≤ 75% d. Jika 10% < info perbandingan realisasi kinerja dengan target jangka menengah ≤ 40% e. Jika dokumen laporan kinerja telah menginfokan ≤ 10% perbandingan realisasi kinerja dengan target jangka menengah',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan perbandingan realisasi kinerja dengan realisasi kinerja tahun-tahun sebelumnya.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'a. Jika dokumen laporan kinerja telah menginfokan > 90% perbandingan realisasi kinerja dengan realisasi kinerja tahun-tahun sebelumnya b. Jika 75% < info perbandingan realisasi kinerja dengan realisasi kinerja tahun-tahun sebelumnya ≤ 90% c. Jika 40% < info perbandingan realisasi kinerja dengan realisasi kinerja tahun-tahun sebelumnya ≤ 75% d. Jika 10% < info perbandingan realisasi kinerja dengan realisasi kinerja tahun-tahun sebelumnya ≤ 40% e. Jika dokumen laporan kinerja telah menginfokan ≤ 10% perbandingan realisasi kinerja dengan realisasi kinerja tahun-tahun sebelumnya	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menyajikan informasi keuangan yang terkait dengan pencapaian sasaran kinerja instansi.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'a. apabila Laporan Kinerja mampu menyajikan informasi keuangan yang terkait langsung dengan seluruh pencapaian sasaran (outcome); b. apabila Laporan Kinerja mampu menyajikan informasi keuangan atas > 80% sasaran c. apabila Laporan Kinerja hanya menyajikan informasi keuangan atas > 50% sasaran; d. apabila Laporan Kinerja hanya menyajikan realisasi keuangan atas < 50% sasaran e. apabila tidak ada informasi keuangan yang dapat dikaitkan dengan sasaran atau kinerja tertentu	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan kualitas atas capaian kinerja beserta upaya nyata dan/atau hambatannya.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'a. Jika dokumen laporan kinerja telah menginfokan > 90% kualitas atas capaian kinerja beserta upaya nyata dan/atau hambatannya b. Jika 75% < info kualitas atas capaian kinerja beserta upaya nyata dan/atau hambatannya ≤ 90% c. Jika 40% < info kualitas atas capaian kinerja beserta upaya nyata dan/atau hambatannya ≤ 75% d. Jika 10% < info kualitas atas capaian kinerja beserta upaya nyata dan/atau hambatannya ≤ 40% e. Jika dokumen laporan kinerja telah menginfokan ≤ 10% kualitas atas capaian kinerja beserta upaya nyata dan/atau hambatannya	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan efisiensi atas penggunaan sumber daya dalam mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'a. Jika dokumen laporan kinerja telah menginfokan > 90% efisiensi atas penggunaan sumber daya dalam mencapai kinerja b. Jika 75% < info efisiensi atas penggunaan sumber daya dalam mencapai kinerja ≤ 90% c. Jika 40% < info efisiensi atas penggunaan sumber daya dalam mencapai kinerja ≤ 75% d. Jika 10% < info efisiensi atas penggunaan sumber daya dalam mencapai kinerja ≤ 40% e. Jika dokumen laporan kinerja telah menginfokan ≤ 10% efisiensi atas penggunaan sumber daya dalam mencapai kinerja',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Dokumen Laporan Kinerja telah menginfokan upaya perbaikan dan penyempurnaan kinerja ke depan (Rekomendasi perbaikan kinerja).',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'a. Jika dokumen laporan kinerja telah menginfokan > 90% Rekomendasi perbaikan kinerja b. Jika 75% < info Rekomendasi perbaikan kinerja ≤ 90% c. Jika 40% < info Rekomendasi perbaikan kinerja ≤ 75% d. Jika 10% < info Rekomendasi perbaikan kinerja ≤ 40% e. Jika dokumen laporan kinerja telah menginfokan ≤ 10% Rekomendasi perbaikan kinerja	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
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
												'penjelasan' => 'Jika informasi yang disajikan telah digunakan untuk penilaian kinerja	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' =>
												'Penyajian informasi dalam laporan kinerja menjadi kepedulian seluruh pegawai.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'a. Jika pegawai yang berpartisipasi dalam penyajian informasi laporan kinerja > 90% b. Jika 75% < pegawai yang berpartisipasi dalam penyajian informasi laporan kinerja ≤ 90% c. Jika 40% < pegawai yang berpartisipasi dalam penyajian informasi laporan kinerja ≤ 75% d. Jika 10% < pegawai yang berpartisipasi dalam penyajian informasi laporan kinerja ≤ 40% e. Jika pegawai yang berpartisipasi dalam penyajian informasi laporan kinerja ≤ 10%	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian aktivitas untuk mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023',
												'penjelasan' => 'Jika Informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian aktivitas > 90% Jika 75% < informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian aktivitas ≤ 90% Jika 40% < informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian aktivitas ≤ 75% Jika 10% < informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian aktivitas ≤ 40% Jika Informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian aktivitas ≤ 10%	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja'
												)
											),
											array(
												'nama' => 'Informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian penggunaan anggaran untuk mencapai kinerja.',
												'tipe' => '2',
												'keterangan' => '- Laporan Kinerja 2023,  - DPA 2024 (dokumen lainnya)',
												'penjelasan' => 'a. Jika Informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian penggunaan anggaran > 90% b. Jika 75% < penyesuaian penggunaan anggaran ≤ 90% c. Jika 40% < penyesuaian penggunaan anggaran ≤ 75% d. Jika 10% < penyesuaian penggunaan anggaran ≤ 40% e. Jika Informasi dalam laporan kinerja berkala telah digunakan dalam penyesuaian penggunaan anggaran ≤ 10%	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja',
													'esakip_dokumen_lainnya',
													'esakip_dpa'
												)
											),
											array(
												'nama' => 'Informasi dalam laporan kinerja telah digunakan dalam evaluasi pencapaian keberhasilan kinerja.',
												'tipe' => '2',
												'keterangan' => 'Laporan Kinerja 2023, Evaluasi Internal 2023',
												'penjelasan' => 'a. Jika Informasi dalam laporan kinerja telah digunakan dalam evaluasi pencapaian keberhasilan kinerja > 90% b. Jika 75% < evaluasi pencapaian keberhasilan kinerja ≤ 90% c. Jika 40% < evaluasi pencapaian keberhasilan kinerja ≤ 75% d. Jika 10% < evaluasi pencapaian keberhasilan kinerja ≤ 40% e. Jika Informasi dalam laporan kinerja telah digunakan dalam penyesuaian aktivitas ≤ 10%	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja',
													'esakip_evaluasi_internal'
												)
											),
											array(
												'nama' => 'Informasi dalam laporan kinerja telah digunakan dalam penyesuaian perencanaan kinerja yang akan dihadapi berikutnya.',
												'tipe' => '2',
												'keterangan' => 'a. Jika Informasi dalam laporan kinerja elah digunakan dalam penyesuaian perencanaan kinerja berikutnya > 90% b. Jika 75% < penyesuaian perencanaan kinerja berikutnya ≤ 90% c. Jika 40% < penyesuaian perencanaan kinerja berikutnya ≤ 75% d. Jika 10% < penyesuaian perencanaan kinerja berikutnya ≤ 40% e. Jika Informasi dalam laporan kinerja telah digunakan dalam penyesuaian perencanaan kinerja berikutnya ≤ 10%',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja',
													'esakip_dpa'
												)
											),
											array(
												'nama' => 'Informasi dalam laporan kinerja selalu mempengaruhi perubahan budaya kinerja organisasi.',
												'tipe' => '2',
												'keterangan' => '- Laporan Kinerja 2023, rencana aksi 2024, evaluasi internal 2024 dan 2023 - DPA 2024 (dokumen lainnya)',
												'penjelasan' => 'a. Jika Informasi dalam laporan kinerja mempengaruhi perubahan budaya kinerja organisasi > 90% b. Jika 75% < mempengaruhi perubahan budaya kinerja organisasi ≤ 90% c. Jika 40% < mempengaruhi perubahan budaya kinerja organisasi ≤ 75% d. Jika 10% < mempengaruhi perubahan budaya kinerja organisasi ≤ 40% e. Jika Informasi dalam laporan kinerja mempengaruhi perubahan budaya kinerja organisasi ≤ 10%',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja',
													'esakip_dpa',
													'esakip_rencana_aksi',
													'esakip_dokumen_lainnya',
													'esakip_evaluasi_internal'
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
												'penjelasan' => 'cukup jelas',
												'langkah_kerja' => '-',
												'nomor_urut' => '1.00',
												'jenis_bukti_dukung' => array(
													'esakip_evaluasi_internal'
												)
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
												'penjelasan' => 'a. Jika > 90% Evaluasi Akuntabilitas Kinerja dilaksanakan secara berjenjang b. Jika 75% < SDM berjenjang ≤ 90% c. Jika 40% < SDM berjenjang ≤ 75% d. Jika 10% < SDM berjenjang ≤ 40% e. Jika Evaluasi Akuntabilitas Kinerja dilkasanakan oleh ≤ 10% SDM berjenjang	',
												'langkah_kerja' => '-',
												'nomor_urut' => '1.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi belum dilaksanakan.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_evaluasi_internal'
												)
											),
											array(
												'nama' => 'Evaluasi Akuntabilitas Kinerja telah dilaksanakan dengan pendalaman yang memadai.',
												'tipe' => '2',
												'keterangan' => 'Evaluasi Internal 2023 dan 2024',
												'penjelasan' => 'a. Jika pendalaman Evaluasi Akuntabilitas Kinerja > 90% b. Jika 75% < pendalaman Evaluasi Akuntabilitas Kinerja ≤ 90% c. Jika 40% < pendalaman Evaluasi Akuntabilitas Kinerja ≤ 75% d. Jika 10% < pendalaman Evaluasi Akuntabilitas Kinerja≤ 40% e. Jika pendalaman Evaluasi Akuntabilitas Kinerja ≤ 10%	',
												'langkah_kerja' => '-',
												'nomor_urut' => '2.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi belum dilaksanakan.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_evaluasi_internal'
												)
											),
											array(
												'nama' => 'Evaluasi Akuntabilitas Kinerja telah dilaksanakan pada seluruh bidang di Perangkat Daerah.',
												'tipe' => '2',
												'keterangan' => 'Evaluasi Internal 2023 dan 2024',
												'penjelasan' => 'a. Jika Evaluasi Akuntabilitas Kinerja dilakukan > 90% bidang b. Jika Evaluasi Akuntabilitas Kinerja dilakukan75% < bidang ≤ 90% c. Jika Evaluasi Akuntabilitas Kinerja dilakukan 40% < bidang ≤ 75% d. Jika Evaluasi Akuntabilitas Kinerja dilakukan 10% < bidang ≤ 40% e. Jika Evaluasi Akuntabilitas Kinerja dilakukan ≤ 10% bidang	',
												'langkah_kerja' => '-',
												'nomor_urut' => '3.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi belum dilaksanakan.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_evaluasi_internal'
												)
											),
											array(
												'nama' => 'Evaluasi Akuntabilitas Kinerja telah dilaksanakan menggunakan Teknologi Informasi (Aplikasi).',
												'tipe' => '2',
												'keterangan' => 'Evaluasi Internal 2023 dan 2024',
												'penjelasan' => 'a. Jika > 90% Evaluasi Akuntabilitas Kinerja telah dilaksanakan menggunakan aplikasi b. Jika 75% < menggunakan aplikasi ≤ 90% c. Jika 40% < menggunakan aplikasi ≤ 75% d. Jika 10% < menggunakan aplikasi ≤ 40% e. Jika Evaluasi Akuntabilitas Kinerja telah dilaksanakan menggunakan ≤ 10%',
												'langkah_kerja' => '-',
												'nomor_urut' => '4.00',
												'data' => array(
													array(
														'jenis_kerangka_logis' => '2',
														'id_komponen_pembanding' => '3-0-0',
														'pesan_kesalahan' => 'SALAH, Evaluasi belum dilaksanakan.'
													)
												),
												'jenis_bukti_dukung' => array(
													'esakip_evaluasi_internal'
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
												'nama' => 'Seluruh rekomendasi atas hasil evaluasi akuntabilitas kinerja (internal dan LHE SAKIP Perangkat Daerah) telah ditindaklanjuti.',
												'tipe' => '2',
												'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023',
												'nomor_urut' => '1.00',
												'penjelasan' => 'Jawaban tentang pemanfaatan evaluasi harus selalu dikaitkan dengan (dipengaruhi oleh) kondisi (jawaban) tentang Pemenuhan Evaluasi dan Kualitas Evaluasi a. Jika > 90% rekomendasi hasil evaluasi telah ditindaklanjuti b. Jika 75% < rekomendasi yang ditindaklanjuti ≤ 90% c. Jika 40% < rekomendasi yang ditindaklanjuti ≤ 75% d. Jika 10% < rekomendasi yang ditindaklanjuti ≤ 40% e. Jika rekomendasi yang ditindaklanjuti ≤ 10%	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja',
													'esakip_tl_lhe_akip_internal'
												)
											),
											array(
												'nama' => 'Telah terjadi peningkatan implementasi SAKIP  (internal dan LHE SAKIP Perangkat Daerah) dengan melaksanakan tindak lanjut atas rekomendasi hasil evaluasi akuntabilitas kinerja.',
												'tipe' => '2',
												'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024',
												'penjelasan' => 'a. Jika > 90% rekomendasi hasil evaluasi telah ditindaklanjuti b. Jika 75% < rekomendasi yang ditindaklanjuti ≤ 90% c. Jika 40% < rekomendasi yang ditindaklanjuti ≤ 75% d. Jika 10% < rekomendasi yang ditindaklanjuti ≤ 40% e. Jika rekomendasi yang ditindaklanjuti ≤ 10%	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja',
													'esakip_tl_lhe_akip_internal',
													'esakip_evaluasi_internal',
													'esakip_rencana_aksi'
												)
											),
											array(
												'nama' => 'Hasil Evaluasi Akuntabilitas Kinerja  (internal dan LHE SAKIP Perangkat Daerah) telah dimanfaatkan untuk perbaikan dan peningkatan akuntabilitas kinerja.',
												'tipe' => '2',
												'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024',
												'penjelasan' => 'a. Jika > 90% rekomendasi hasil evaluasi telah ditindaklanjuti b. Jika 75% < rekomendasi yang ditindaklanjuti ≤ 90% c. Jika 40% < rekomendasi yang ditindaklanjuti ≤ 75% d. Jika 10% < rekomendasi yang ditindaklanjuti ≤ 40% e. Jika rekomendasi yang ditindaklanjuti ≤ 10%	',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja',
													'esakip_tl_lhe_akip_internal',
													'esakip_evaluasi_internal',
													'esakip_rencana_aksi'
												)
											),
											array(
												'nama' => 'Hasil dari Evaluasi Akuntabilitas Kinerja  (internal dan LHE SAKIP Perangkat Daerah)telah dimanfaatkan dalam mendukung efektivitas dan efisiensi kinerja.',
												'tipe' => '2',
												'keterangan' => 'Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024, DPA 2024',
												'penjelasan' => 'a. Jika > 90% rekomendasi hasil evaluasi telah ditindaklanjuti b. Jika 75% < rekomendasi yang ditindaklanjuti ≤ 90% c. Jika 40% < rekomendasi yang ditindaklanjuti ≤ 75% d. Jika 10% < rekomendasi yang ditindaklanjuti ≤ 40% e. Jika rekomendasi yang ditindaklanjuti ≤ 10%',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja',
													'esakip_tl_lhe_akip_internal',
													'esakip_evaluasi_internal',
													'esakip_rencana_aksi',
													'esakip_dpa'
												)
											),
											array(
												'nama' => 'Telah terjadi perbaikan dan peningkatan kinerja dengan memanfaatkan hasil evaluasi akuntabilitas kinerja  (internal dan LHE SAKIP Perangkat Daerah).',
												'tipe' => '2',
												'keterangan' => '- Tindak Lanjut LHE SAKIP 2023, Laporan Kinerja 2023, Rencana Aksi 2024, Evaluasi Internal 2024, DPA 2024 - Dokumen lainnya (Inovasi, Prestasi)',
												'penjelasan' => 'a. Jika > 90% rekomendasi hasil evaluasi telah ditindaklanjuti b. Jika 75% < rekomendasi yang ditindaklanjuti ≤ 90% c. Jika 40% < rekomendasi yang ditindaklanjuti ≤ 75% d. Jika 10% < rekomendasi yang ditindaklanjuti ≤ 40% e. Jika rekomendasi yang ditindaklanjuti ≤ 10%',
												'langkah_kerja' => '-',
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
												),
												'jenis_bukti_dukung' => array(
													'esakip_laporan_kinerja',
													'esakip_tl_lhe_akip_internal',
													'esakip_evaluasi_internal',
													'esakip_rencana_aksi',
													'esakip_dokumen_lainnya'
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
										'penjelasan' => $penilaian['penjelasan'],
										'langkah_kerja' => $penilaian['langkah_kerja'],
										'jenis_bukti_dukung' => '[\"' . implode('\",\"', $penilaian['jenis_bukti_dukung']) . '\"]'
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

	public function submit_edit_jadwal_lke()
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
					$tampil_nilai_penetapan	= $_POST['tampil_nilai_penetapan'];
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
								'tahun_anggaran'		=> $tahun_anggaran,
								'tampil_nilai_penetapan' => $tampil_nilai_penetapan
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
	public function delete_jadwal_lke()
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

					//validasi data pengisian
					$data_desain = $wpdb->get_results(
						$wpdb->prepare('
							SELECT 
								* 
							FROM esakip_komponen
							WHERE id_jadwal = %d
							  AND active = 1
						', $id),
						ARRAY_A
					);
					$data_pengisian = array(); // Inisialisasi array untuk menyimpan hasil pengisian

					if (!empty($data_desain)) {
						foreach ($data_desain as $komponen) {
							$pengisian = $wpdb->get_results(
								$wpdb->prepare('
									SELECT 
										* 
									FROM esakip_pengisian_lke
									WHERE id_komponen = %d
									AND active = 1
								', $komponen['id']),
								ARRAY_A
							);

							if (!empty($pengisian)) {
								$data_pengisian = array_merge($data_pengisian, $pengisian);
							}
						}
					}
					if (!empty($data_pengisian)) {
						$ret['status'] = 'error';
						$ret['message'] = 'Jadwal tidak dapat dihapus karena memiliki data pengisian lke aktif!';
						die(json_encode($ret));
					}

					if (!empty($data_this_id)) {
						$status_check = array(1, NULL, 2);
						if (in_array($data_this_id['status'], $status_check)) {
							$wpdb->update(
								'esakip_data_jadwal',
								array('status' => 0),
								array('id' => $id),
								array('%d')
							);

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
			'message' => 'Format tidak sesuai!'
		);

		$nama_tabel_history = $nama_tabel . "_history";

		$delete = $wpdb->delete($nama_tabel_history, array('id_jadwal' => $id_jadwal));
		if ($delete == false) {
			$return = array(
				'status'    => 'error',
				'message'    => 'Delete error, harap hubungi admin!'
			);
		}

		return $return;
	}

	// lock jadwal lke
	public function lock_jadwal_lke()
	{
		global $wpdb;
		$return = array(
			'status' => 'success',
			'message' => 'Berhasil kunci jadwal LKE!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				if (!empty($_POST['id'])) {
					$id = trim(htmlspecialchars($_POST['id']));

					// Ambil data jadwal berdasarkan ID
					$data_jadwal = $wpdb->get_row(
						$wpdb->prepare('
                        SELECT 
                            * 
                        FROM esakip_data_jadwal 
                        WHERE id = %d
                    ', $id),
						ARRAY_A
					);

					// Setel timezone
					$timezone = get_option('timezone_string');
					if (preg_match("/Asia/i", $timezone)) {
						date_default_timezone_set($timezone);
					} else {
						$return = array(
							'status' => 'error',
							'message' => "Pengaturan timezone salah. Pilih salah satu kota di zona waktu yang sama dengan anda, antara lain: 'Jakarta', 'Makasar', 'Jayapura'",
						);
						die(json_encode($return));
					}

					$dateTime = new DateTime();
					$time_now = $dateTime->format('Y-m-d H:i:s');

					// Cek apakah waktu sekarang lebih besar dari waktu mulai
					if ($time_now > $data_jadwal['started_at']) {
						$status_check = array(1, NULL, 2);
						if (in_array($data_jadwal['status'], $status_check)) {
							// Kunci data penjadwalan
							$wpdb->update('esakip_data_jadwal', array('end_at' => $time_now, 'status' => 2), array('id' => $id));

							// Backup komponen
							$this->delete_data_lokal_history('esakip_komponen', $data_jadwal['id']);

							$columns_1 = array(
								'id_jadwal',
								'nomor_urut',
								'id_user_penilai',
								'nama',
								'bobot'
							);

							$sql_backup_esakip_komponen = "
							INSERT INTO esakip_komponen_history (" . implode(', ', $columns_1) . ", id_asli)
							SELECT " . implode(', ', $columns_1) . ", id as id_asli
							FROM esakip_komponen
							WHERE id_jadwal = " . $data_jadwal['id'] . " AND active=1";

							$queryRecords1 = $wpdb->query($sql_backup_esakip_komponen);

							// Backup subkomponen
							$this->delete_data_lokal_history('esakip_subkomponen', $data_jadwal['id']);

							$columns_2 = array(
								'id_komponen',
								'nomor_urut',
								'id_user_penilai',
								'nama',
								'bobot',
								'metode_penilaian'
							);

							$sql_backup_esakip_subkomponen = "
							INSERT INTO esakip_subkomponen_history (" . implode(', ', $columns_2) . ", id_asli, id_jadwal)
							SELECT " . implode(', ', $columns_2) . ", id as id_asli, " . $data_jadwal['id'] . "
							FROM esakip_subkomponen
							WHERE id_komponen IN (
								SELECT id FROM esakip_komponen WHERE id_jadwal = " . $data_jadwal['id'] . " AND active=1
							) AND active=1";

							$queryRecords2 = $wpdb->query($sql_backup_esakip_subkomponen);

							// Backup komponen penilaian
							$this->delete_data_lokal_history('esakip_komponen_penilaian', $data_jadwal['id']);

							$columns_3 = array(
								'id_subkomponen',
								'nomor_urut',
								'nama',
								'tipe',
								'bobot',
								'keterangan',
								'jenis_bukti_dukung'
							);

							$sql_backup_esakip_komponen_penilaian = "
							INSERT INTO esakip_komponen_penilaian_history (" . implode(', ', $columns_3) . ", id_asli, id_jadwal)
							SELECT " . implode(', ', $columns_3) . ", id as id_asli, " . $data_jadwal['id'] . "
							FROM esakip_komponen_penilaian
							WHERE id_subkomponen IN (
								SELECT id FROM esakip_subkomponen WHERE id_komponen IN (
									SELECT id FROM esakip_komponen WHERE id_jadwal = " . $data_jadwal['id'] . " AND active=1
								) AND active=1
							) AND active=1";

							$queryRecords3 = $wpdb->query($sql_backup_esakip_komponen_penilaian);

							// Backup kerangka logis
							$this->delete_data_lokal_history('esakip_kontrol_kerangka_logis', $data_jadwal['id']);

							$columns_4 = array(
								'id_komponen_penilaian',
								'jenis_kerangka_logis',
								'id_komponen_pembanding',
								'pesan_kesalahan'
							);

							$sql_backup_esakip_kontrol_kerangka_logis = "
							INSERT INTO esakip_kontrol_kerangka_logis_history (" . implode(', ', $columns_4) . ", id_asli, id_jadwal)
							SELECT " . implode(', ', $columns_4) . ", id as id_asli, " . $data_jadwal['id'] . "
							FROM esakip_kontrol_kerangka_logis
							WHERE id_komponen_penilaian IN (
								SELECT id FROM esakip_komponen_penilaian WHERE id_subkomponen IN (
									SELECT id FROM esakip_subkomponen WHERE id_komponen IN (
										SELECT id FROM esakip_komponen WHERE id_jadwal = " . $data_jadwal['id'] . " AND active=1
									) AND active=1
								) AND active=1
							) AND active=1";

							$queryRecords4 = $wpdb->query($sql_backup_esakip_kontrol_kerangka_logis);

							// Backup penilaian custom
							$this->delete_data_lokal_history('esakip_penilaian_custom', $data_jadwal['id']);

							$columns_5 = array(
								'id_komponen_penilaian',
								'nama',
								'nilai',
								'nomor_urut'
							);

							$sql_backup_esakip_penilaian_custom = "
							INSERT INTO esakip_penilaian_custom_history (" . implode(', ', $columns_5) . ", id_asli, id_jadwal)
							SELECT " . implode(', ', $columns_5) . ", id as id_asli, " . $data_jadwal['id'] . "
							FROM esakip_penilaian_custom
							WHERE id_komponen_penilaian IN (
								SELECT id FROM esakip_komponen_penilaian WHERE id_subkomponen IN (
									SELECT id FROM esakip_subkomponen WHERE id_komponen IN (
										SELECT id FROM esakip_komponen WHERE id_jadwal = " . $data_jadwal['id'] . " AND active=1
									) AND active=1
								) AND active=1
							) AND active=1";

							$queryRecords5 = $wpdb->query($sql_backup_esakip_penilaian_custom);

							// Backup pengisian LKE
							$this->delete_data_lokal_history('esakip_pengisian_lke', $data_jadwal['id']);

							$columns_6 = array(
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

							$sql_backup_esakip_pengisian_lke = "
							INSERT INTO esakip_pengisian_lke_history (" . implode(', ', $columns_6) . ", id_asli, id_jadwal)
							SELECT " . implode(', ', $columns_6) . ", id as id_asli, " . $data_jadwal['id'] . "
							FROM esakip_pengisian_lke 
							WHERE active=1";

							$queryRecords6 = $wpdb->query($sql_backup_esakip_pengisian_lke);
						} else {
							$return = array(
								'status' => 'error',
								'message' => "User tidak diijinkan!\nData sudah dikunci!",
							);
						}
					} else {
						$return = array(
							'status' => 'error',
							'message' => "Penjadwalan belum dimulai!",
						);
					}
				} else {
					$return = array(
						'status' => 'error',
						'message' => 'Harap diisi semua, tidak boleh ada yang kosong!'
					);
				}
			} else {
				$return = array(
					'status' => 'error',
					'message' => 'API Key tidak sesuai!'
				);
			}
		} else {
			$return = array(
				'status' => 'error',
				'message' => 'Format tidak sesuai!'
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
						1 => 'nama_jadwal_renstra',
						2 => 'keterangan',
						3 => 'tahun_anggaran',
						4 => 'lama_pelaksanaan',
						5 => 'tipe',
						6 => 'id',
						7 => 'tahun_selesai_anggaran',
						8 => 'jenis_jadwal_khusus'
					);
					$where = $sqlTot = $sqlRec = "";
					$where = " WHERE tipe = 'RPJMD' AND status != 0";

					// check search value exist
					if (!empty($params['search']['value'])) {
						$where .= " AND ( nama_jadwal LIKE " . $wpdb->prepare('%s', "%" . $params['search']['value'] . "%");
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

							if (!empty($recVal['tahun_selesai_anggaran']) && $recVal['tahun_selesai_anggaran'] > 1) {
								$tahun_anggaran_selesai = $recVal['tahun_selesai_anggaran'];
							} else {
								$tahun_anggaran_selesai = $recVal['tahun_anggaran'] + $recVal['lama_pelaksanaan'];
							}

							if (!empty($recVal['jenis_jadwal_khusus'])) {
								$jenis_jadwal_khusus = strtoupper($recVal['jenis_jadwal_khusus']);
							} else {
								$jenis_jadwal_khusus = 'RPJMD';
							}

							$queryRecords[$recKey]['aksi'] = $edit . $delete;
							$queryRecords[$recKey]['nama_jadwal'] = ucfirst($recVal['nama_jadwal']);
							$queryRecords[$recKey]['tahun_anggaran_selesai'] = $tahun_anggaran_selesai;
							$queryRecords[$recKey]['jenis_jadwal_khusus'] = $jenis_jadwal_khusus;
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

	public function get_data_jadwal_by_id_rpjmd()
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

				$data_hak_akses = $wpdb->get_results($wpdb->prepare('
                    SELECT 
                        *
                    FROM esakip_menu_dokumen
                    WHERE id_jadwal=%d
                ', $_POST['id']), ARRAY_A);


				$ret['data']['jenis_role'] = '';
				$ret['data']['jenis_role_pohon_kinerja'] = '';
				$ret['data']['hak_akses_renstra'] = '';
				$ret['data']['hak_akses_pohon_kinerja'] = '';
				if (!empty($data_hak_akses)) {
					foreach ($data_hak_akses as $v_hak_akses) {
						if ($v_hak_akses['nama_tabel'] == 'esakip_renstra') {
							$ret['data']['jenis_role'] = !empty($v_hak_akses['jenis_role']) ? $v_hak_akses['jenis_role'] : 2;
							$ret['data']['hak_akses_renstra'] = (!empty($v_hak_akses['active']) && $v_hak_akses['active'] == 1) ? 'tampil' : 'sembunyi';
						} else if ($v_hak_akses['nama_tabel'] == 'esakip_pohon_kinerja_dan_cascading') {
							$ret['data']['jenis_role_pohon_kinerja'] = !empty($v_hak_akses['jenis_role']) ? $v_hak_akses['jenis_role'] : 2;
							$ret['data']['hak_akses_pohon_kinerja'] = (!empty($v_hak_akses['active']) && $v_hak_akses['active'] == 1) ? 'tampil' : 'sembunyi';
						} else if ($v_hak_akses['nama_tabel'] == 'esakip_capaian_iku_pemda') {
							$ret['data']['hak_akses_menu_iku'] = (!empty($v_hak_akses['active']) && $v_hak_akses['active'] == 1) ? 'tampil' : 'sembunyi';
						}
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
				if (!empty($_POST['nama_jadwal']) && !empty($_POST['nama_jadwal_renstra']) && !empty($_POST['lama_pelaksanaan']) && !empty($_POST['keterangan']) && !empty($_POST['tahun_anggaran']) && !empty($_POST['jenis_khusus_rpjmd']) && !empty($_POST['akses_user']) && !empty($_POST['akses_user_pohon_kinerja'])) {
					$nama_jadwal	= trim(htmlspecialchars($_POST['nama_jadwal']));
					$nama_jadwal_renstra	= trim(htmlspecialchars($_POST['nama_jadwal_renstra']));
					$tahun_anggaran	= trim(htmlspecialchars($_POST['tahun_anggaran']));
					$keterangan		= trim(htmlspecialchars($_POST['keterangan']));
					$lama_pelaksanaan 	= trim(htmlspecialchars($_POST['lama_pelaksanaan']));
					$relasi_rpjpd 	= $_POST['relasi_rpjpd'];
					$tipe 	= trim(htmlspecialchars($_POST['tipe']));
					$tahun_selesai_anggaran	= trim(htmlspecialchars($_POST['tahun_selesai_anggaran']));
					$jenis_jadwal_khusus	= trim(htmlspecialchars($_POST['jenis_khusus_rpjmd']));
					$akses_user	= trim(htmlspecialchars($_POST['akses_user']));
					$akses_user_pohon_kinerja	= trim(htmlspecialchars($_POST['akses_user_pohon_kinerja']));
					$menu_dokumen	= trim(htmlspecialchars($_POST['menu_dokumen']));
					$menu_dokumen_pohon_kinerja	= trim(htmlspecialchars($_POST['menu_dokumen_pohon_kinerja']));
					$menu_iku	= trim(htmlspecialchars($_POST['menu_iku']));

					$input_menu_dokumen = 0;
					if ($menu_dokumen == 'tampil') {
						$input_menu_dokumen = 1;
					}

					$input_menu_dokumen_pohon_kinerja = 0;
					if ($menu_dokumen_pohon_kinerja == 'tampil') {
						$input_menu_dokumen_pohon_kinerja = 1;
					}

					$input_menu_iku = 0;
					if ($menu_iku == 'tampil') {
						$input_menu_iku = 1;
					}


					$data_this_id = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_data_jadwal WHERE id = %d', $id), ARRAY_A);

					//update data penjadwalan
					$data_jadwal = array(
						'nama_jadwal' 			=> $nama_jadwal,
						'nama_jadwal_renstra' 	=> $nama_jadwal_renstra,
						'tahun_anggaran'		=> $tahun_anggaran,
						'relasi_perencanaan'	=> $relasi_rpjpd,
						'keterangan'			=> $keterangan,
						'tipe'					=> 'RPJMD',
						'status'				=> '1',
						'lama_pelaksanaan'		=> $lama_pelaksanaan,
						'tahun_selesai_anggaran' => $tahun_selesai_anggaran,
						'jenis_jadwal_khusus'	=> $jenis_jadwal_khusus
					);

					$wpdb->insert('esakip_data_jadwal', $data_jadwal);

					$id_jadwal_new = $wpdb->insert_id;

					// insert hak akses renstra per periode
					$get_akses_user_this_periode = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_menu_dokumen WHERE nama_tabel=%s AND id_jadwal=%d', 'esakip_renstra', $id_jadwal_new), ARRAY_A);
					$input_akses_user = 0;
					if ($akses_user == 'pemda') {
						$input_akses_user = 1;
					} else if ($akses_user == 'pd') {
						$input_akses_user = 2;
					} else if ($akses_user == 'semua') {
						$input_akses_user = 3;
					}

					$opsi = array(
						'nama_tabel' => 'esakip_renstra',
						'nama_dokumen' => 'RENSTRA',
						'user_role'	=> 'perangkat_daerah',
						'verifikasi_upload_dokumen'	=> 1,
						'active' => $input_menu_dokumen,
						'updated_at' => current_time('mysql'),
						'jenis_role' => $input_akses_user,
						'nomor_urut' => 1.00,
						'id_jadwal'	=> $id_jadwal_new
					);

					if (empty($get_akses_user_this_periode)) {
						$insert = $wpdb->insert(
							'esakip_menu_dokumen',
							$opsi,
							array('%s', '%s', '%s', '%d', '%d', '%s', '%d', '%f', '%d')
						);

						if (!$insert) {
							error_log("Error Insert: " . $wpdb->last_error);
						}
					}

					// insert hak akses pohon kinerja per periode
					$get_akses_user_this_periode_pohon_kinerja = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_menu_dokumen WHERE nama_tabel=%s AND id_jadwal=%d', 'esakip_pohon_kinerja_dan_cascading', $id_jadwal_new), ARRAY_A);
					$input_akses_user_pohon_kinerja = 0;
					if ($akses_user_pohon_kinerja == 'pemda') {
						$input_akses_user_pohon_kinerja = 1;
					} else if ($akses_user_pohon_kinerja == 'pd') {
						$input_akses_user_pohon_kinerja = 2;
					} else if ($akses_user_pohon_kinerja == 'semua') {
						$input_akses_user_pohon_kinerja = 3;
					}

					$opsi['nama_tabel']		= 'esakip_pohon_kinerja_dan_cascading';
					$opsi['nama_dokumen']	= 'Pohon Kinerja dan Cascading';
					$opsi['jenis_role']		= $input_akses_user_pohon_kinerja;
					$opsi['active']			= $input_menu_dokumen_pohon_kinerja;
					$opsi['verifikasi_upload_dokumen'] = 0;

					if (empty($get_akses_user_this_periode_pohon_kinerja)) {
						$insert = $wpdb->insert(
							'esakip_menu_dokumen',
							$opsi,
							array('%s', '%s', '%s', '%d', '%d', '%s', '%d', '%f', '%d')
						);

						if (!$insert) {
							error_log("Error Insert: " . $wpdb->last_error);
						}
					}

					// insert hak akses capaian IKU per periode
					$get_akses_user_this_periode_iku = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_menu_dokumen WHERE nama_tabel=%s AND id_jadwal=%d', 'esakip_capaian_iku_pemda', $id_jadwal_new), ARRAY_A);
					$input_akses_user_iku = 1;

					$opsi['nama_tabel']		= 'esakip_capaian_iku_pemda';
					$opsi['nama_dokumen']	= 'Capaian IKU';
					$opsi['jenis_role']		= $input_akses_user_iku;
					$opsi['active']			= $input_menu_iku;
					$opsi['verifikasi_upload_dokumen'] = 0;
					$opsi['user_role']		= 'pemerintah_daerah';

					if (empty($get_akses_user_this_periode_iku)) {
						$insert = $wpdb->insert(
							'esakip_menu_dokumen',
							$opsi,
							array('%s', '%s', '%s', '%d', '%d', '%s', '%d', '%f', '%d')
						);

						if (!$insert) {
							error_log("Error Insert: " . $wpdb->last_error);
						}
					}

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
				if (!empty($_POST['id']) && !empty($_POST['nama_jadwal']) && !empty($_POST['nama_jadwal_renstra']) && !empty($_POST['lama_pelaksanaan']) && !empty($_POST['keterangan']) && !empty($_POST['tahun_anggaran']) && !empty($_POST['jenis_khusus_rpjmd']) && !empty($_POST['akses_user']) && !empty($_POST['akses_user_pohon_kinerja'])) {
					$id = trim(htmlspecialchars($_POST['id']));
					$nama_jadwal	= trim(htmlspecialchars($_POST['nama_jadwal']));
					$nama_jadwal_renstra	= trim(htmlspecialchars($_POST['nama_jadwal_renstra']));
					$tahun_anggaran	= trim(htmlspecialchars($_POST['tahun_anggaran']));
					$keterangan		= trim(htmlspecialchars($_POST['keterangan']));
					$lama_pelaksanaan 	= trim(htmlspecialchars($_POST['lama_pelaksanaan']));
					$tipe 	= trim(htmlspecialchars($_POST['tipe']));
					$tahun_selesai_anggaran	= trim(htmlspecialchars($_POST['tahun_selesai_anggaran']));
					$jenis_khusus_rpjmd	= trim(htmlspecialchars($_POST['jenis_khusus_rpjmd']));
					$akses_user	= trim(htmlspecialchars($_POST['akses_user']));
					$akses_user_pohon_kinerja	= trim(htmlspecialchars($_POST['akses_user_pohon_kinerja']));
					$menu_dokumen	= trim(htmlspecialchars($_POST['menu_dokumen']));
					$menu_dokumen_pohon_kinerja	= trim(htmlspecialchars($_POST['menu_dokumen_pohon_kinerja']));
					$menu_iku	= trim(htmlspecialchars($_POST['menu_iku']));
					// $menu_penyusunan_pohon_kinerja_pemda	= trim(htmlspecialchars($_POST['menu_penyusunan_pohon_kinerja_pemda']));
					// $menu_penyusunan_pohon_kinerja_opd	= trim(htmlspecialchars($_POST['menu_penyusunan_pohon_kinerja_opd']));

					$input_menu_dokumen = 0;
					if ($menu_dokumen == 'tampil') {
						$input_menu_dokumen = 1;
					}

					$input_menu_dokumen_pohon_kinerja = 0;
					if ($menu_dokumen_pohon_kinerja == 'tampil') {
						$input_menu_dokumen_pohon_kinerja = 1;
					}

					// $input_menu_penyusunan_pohon_kinerja_pemda = 0;
					// if ($menu_penyusunan_pohon_kinerja_pemda == 'tampil') {
					// 	$input_menu_penyusunan_pohon_kinerja_pemda = 1;
					// }
					// $input_menu_penyusunan_pohon_kinerja_opd = 0;
					// if ($menu_penyusunan_pohon_kinerja_opd == 'tampil') {
					// 	$input_menu_penyusunan_pohon_kinerja_opd = 1;
					// }


					$input_akses_user = 0;
					if ($akses_user == 'pemda') {
						$input_akses_user = 1;
					} else if ($akses_user == 'pd') {
						$input_akses_user = 2;
					} else if ($akses_user == 'semua') {
						$input_akses_user = 3;
					}

					$input_akses_user_pohon_kinerja = 0;
					if ($akses_user_pohon_kinerja == 'pemda') {
						$input_akses_user_pohon_kinerja = 1;
					} else if ($akses_user_pohon_kinerja == 'pd') {
						$input_akses_user_pohon_kinerja = 2;
					} else if ($akses_user_pohon_kinerja == 'semua') {
						$input_akses_user_pohon_kinerja = 3;
					}

					$input_menu_iku = 0;
					if ($menu_iku == 'tampil') {
						$input_menu_iku = 1;
					}

					$data_this_id = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_data_jadwal WHERE id = %d', $id), ARRAY_A);

					//update data penjadwalan
					$data_jadwal = array(
						'nama_jadwal' 			=> $nama_jadwal,
						'nama_jadwal_renstra' 	=> $nama_jadwal_renstra,
						'tahun_anggaran'		=> $tahun_anggaran,
						'keterangan'			=> $keterangan,
						'tipe'					=> 'RPJMD',
						'lama_pelaksanaan'		=> $lama_pelaksanaan,
						'tahun_selesai_anggaran' => $tahun_selesai_anggaran,
						'jenis_jadwal_khusus'	=> $jenis_khusus_rpjmd
					);

					$wpdb->update('esakip_data_jadwal', $data_jadwal, array(
						'id'	=> $id
					));


					// update hak akses user renstra per periode rpjmd////////////////////////////////////////////////////////////////////////////////////////////////
					$get_akses_user_this_periode = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_menu_dokumen WHERE nama_tabel=%s AND id_jadwal=%d', 'esakip_renstra', $id), ARRAY_A);
					$opsi = array(
						'nama_tabel'	=> 'esakip_renstra',
						'nama_dokumen'	=> 'RENSTRA',
						'user_role'		=> 'perangkat_daerah',
						'verifikasi_upload_dokumen'	=> 1,
						'active'		=> $input_menu_dokumen,
						'updated_at'	=> current_time('mysql'),
						'jenis_role'	=> $input_akses_user,
						'nomor_urut'	=> 1.00,
						'id_jadwal'		=> $id
					);

					if (!empty($get_akses_user_this_periode)) {
						$update = $wpdb->update(
							'esakip_menu_dokumen',
							$opsi,
							array('id' => $get_akses_user_this_periode['id']),
							array('%s', '%s', '%s', '%d', '%d', '%s', '%d', '%f', '%d'),
							array('%d')
						);

						if (!$update) {
							error_log("Error Update: " . $wpdb->last_error);
						}
					} else {
						error_log("Gagal Update Data Tidak Ditemukan");
					}

					// update hak akses user pohon kinerja per periode rpjmd////////////////////////////////////////////////////////////////////////////////////////////////
					$get_akses_user_this_periode_pohon_kinerja = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_menu_dokumen WHERE nama_tabel=%s AND id_jadwal=%d', 'esakip_pohon_kinerja_dan_cascading', $id), ARRAY_A);
					$opsi['nama_tabel']		= 'esakip_pohon_kinerja_dan_cascading';
					$opsi['nama_dokumen']	= 'Pohon Kinerja dan Cascading';
					$opsi['jenis_role']		= $input_akses_user_pohon_kinerja;
					$opsi['active']			= $input_menu_dokumen_pohon_kinerja;
					$opsi['verifikasi_upload_dokumen'] = 0;


					if (!empty($get_akses_user_this_periode_pohon_kinerja)) {
						$update = $wpdb->update(
							'esakip_menu_dokumen',
							$opsi,
							array('id' => $get_akses_user_this_periode_pohon_kinerja['id']),
							array('%s', '%s', '%s', '%d', '%d', '%s', '%d', '%f', '%d'),
							array('%d')
						);

						if (!$update) {
							error_log("Error Update: " . $wpdb->last_error);
						}
					} else {
						error_log("Gagal Update Data Tidak Ditemukan");
					}

					// update hak akses user IKU per periode rpjmd////////////////////////////////////////////////////////////////////////////////////////////////
					$get_akses_user_this_periode_iku = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_menu_dokumen WHERE nama_tabel=%s AND id_jadwal=%d', 'esakip_capaian_iku_pemda', $id), ARRAY_A);
					$opsi['nama_tabel']		= 'esakip_capaian_iku_pemda';
					$opsi['nama_dokumen']	= 'Capaian IKU';
					$opsi['jenis_role']		= 1;
					$opsi['active']			= $input_menu_iku;
					$opsi['verifikasi_upload_dokumen'] = 0;
					$opsi['user_role']		= 'pemerintah_daerah';

					if (!empty($get_akses_user_this_periode_iku)) {
						$update = $wpdb->update(
							'esakip_menu_dokumen',
							$opsi,
							array('id' => $get_akses_user_this_periode_iku['id']),
							array('%s', '%s', '%s', '%d', '%d', '%s', '%d', '%f', '%d'),
							array('%d')
						);

						if (!$update) {
							error_log("Error Update: " . $wpdb->last_error);
						}
					} else {
						$insert = $wpdb->insert(
							'esakip_menu_dokumen',
							$opsi,
							array('%s', '%s', '%s', '%d', '%d', '%s', '%d', '%f', '%d')
						);

						if (!$insert) {
							error_log("Error Update: " . $wpdb->last_error);
						}
						// error_log("Gagal Update Data Tidak Ditemukan");
					}

					// update hak akses menu PENYUSUNAN pohon kinerja ////////////////////////////////////////////////////////////////////////////////////////////////
					// $get_akses_user_this_periode_pohon_kinerja = $wpdb->get_row($wpdb->prepare('SELECT * FROM esakip_menu_dokumen WHERE nama_tabel=%s AND id_jadwal=%d', 'esakip_pohon_kinerja_dan_cascading', $id), ARRAY_A);
					// $opsi['nama_tabel']		= 'esakip_pohon_kinerja_dan_cascading';
					// $opsi['nama_dokumen']	= 'Pohon Kinerja dan Cascading';
					// $opsi['jenis_role']		= $input_akses_user_pohon_kinerja;
					// $opsi['active']			= $input_menu_dokumen_pohon_kinerja;
					// $opsi['verifikasi_upload_dokumen'] = 0;


					// if (!empty($get_akses_user_this_periode_pohon_kinerja)) {
					// 	$update = $wpdb->update(
					// 		'esakip_menu_dokumen',
					// 		$opsi,
					// 		array('id' => $get_akses_user_this_periode_pohon_kinerja['id']),
					// 		array('%s', '%s', '%s', '%d', '%d', '%s', '%d', '%f', '%d'),
					// 		array('%d')
					// 	);

					// 	if (!$update) {
					// 		error_log("Error Update: " . $wpdb->last_error);
					// 	}
					// } else {
					// 	error_log("Gagal Update Data Tidak Ditemukan");
					// }


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

	public function get_data_jadwal_by_id_rpjpd()
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
							'nama_page' => 'Halaman Detail Dokumen Lainnya ' . $tahun_anggaran,
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_lainnya(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_other_file(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_rpjmd(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

				if (!empty($_POST['id_periode'])) {
					$id_jadwal = $_POST['id_periode'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
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
						if (
							!$this->is_admin_panrb()
							&& $this->hak_akses_upload_dokumen('RENSTRA', $id_jadwal)
						) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_renstra(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_laporan_monev_renaksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Laporan Monev Renaksi', $tahun_anggaran)) {
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
				if (empty($_POST['namaDokumen']) && empty($id_dokumen)) {
					$ret['status'] = 'error';
					$ret['message'] = 'File Dokumen kosong!';
				}
				if (empty($_FILES['fileUpload']) && empty($id_dokumen)) {
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
						FROM esakip_laporan_monev_renaksi
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_laporan_monev_renaksi',
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Pedoman Teknis Perencanaan', $tahun_anggaran)) {
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
						FROM esakip_pedoman_teknis_perencanaan
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_pedoman_teknis_perencanaan',
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja', $tahun_anggaran)) {
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
						FROM esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja',
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Pedoman Teknis Evaluasi Internal', $tahun_anggaran)) {
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
						FROM esakip_pedoman_teknis_evaluasi_internal
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_pedoman_teknis_evaluasi_internal',
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_perjanjian_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_rencana_aksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
							'nama_page' => 'Halaman Detail Dokumen IKU ' . $tahun_anggaran,
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_iku(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_evaluasi_internal(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_pengukuran_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_pengukuran_rencana_aksi(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_laporan_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_rkpd(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_lkjip_lppd(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_dpa(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_renja_rkt(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_skp(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
							if (!$this->is_admin_panrb() && !$this->is_admin_user()) {
								$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

								$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_tipe(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
			'3' => 'Admin Organisasi'
		);
	}

	public function get_table_kerangka_logis()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get table!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_komponen_penilaian'])) {
					$id_komponen_penilaian = $_POST['id_komponen_penilaian'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Komponen Penilaian kosong!';
					die(json_encode($ret));
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
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_kerangka_logis(\'' . $kerangka_logis['id'] . '\', \'' . $id_komponen_penilaian . '\'); return false;" href="#" title="Hapus Kerangka Logis"><span class="dashicons dashicons-no-alt"></span></button>';
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

	public function get_table_opsi_custom()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get table!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_komponen_penilaian'])) {
					$id_komponen_penilaian = $_POST['id_komponen_penilaian'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Komponen Penilaian kosong!';
					die(json_encode($ret));
				}
				$opsi_custom_datas = $wpdb->get_results(
					$wpdb->prepare("
						SELECT * 
						FROM esakip_penilaian_custom
						WHERE id_komponen_penilaian = %d
						AND active = 1
						ORDER BY nomor_urut ASC
						", $id_komponen_penilaian),
					ARRAY_A
				);
				$counter = 1;
				$btn_tambah =  '
					<button class="btn btn-primary btn-sm mb-2" onclick="tambahOpsiPenilaianCustom(\'' . $id_komponen_penilaian . '\'); return false">
						<span class="dashicons dashicons-insert"></span>Tambah Opsi Penilaian
					</button>';
				$tbody = '';
				if (!empty($opsi_custom_datas)) {
					foreach ($opsi_custom_datas as $datas) {
						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_opsi_custom(\'' . $datas['id'] . '\', \'' . $id_komponen_penilaian . '\'); return false;" href="#" title="Hapus Opsi Custom"><span class="dashicons dashicons-no-alt"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_opsi_custom(\'' . $datas['id'] . '\', \'' . $id_komponen_penilaian . '\'); return false;" href="#" title="Edit Opsi Custom"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '</div>';

						$tbody .= '<tr>';
						$tbody .= '<td class="text-center">' . $counter++ . '</td>';

						$tbody .= '<td class="text-left">' . $datas['nama'] . '</td>';
						$tbody .= '<td class="text-center">' . $datas['nilai'] . '</td>';
						$tbody .= '<td class="text-center">' . $btn . '</td>';
						$tbody .= '</tr>';
					}
				} else {
					$tbody .= "<tr><td colspan='4' class='text-center'>Tidak ada data tersedia</td></tr>";
				}
				$ret['data'] = $tbody;
				$ret['button'] = $btn_tambah;
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
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
						$tbody .= "<td class='text-center'></td>";
						$tbody .= "<td class='text-center'></td>";
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
								$tbody .= "<td class='text-center'></td>";
								$tbody .= "<td class='text-center'></td>";
								$tbody .= "<td class='text-center'>" . $btn . "</td>";
								$tbody .= "</tr>";

								$data_komponen_penilaian = $wpdb->get_results(
									$wpdb->prepare("
										SELECT 
											kp.id AS kp_id,
											kp.id_subkomponen,
											kp.nomor_urut,
											kp.nama AS kp_nama,
											kp.bobot AS kp_bobot,
											kp.tipe,
											kp.keterangan AS kp_keterangan,
											kp.jenis_bukti_dukung,
											kp.penjelasan,
											kp.langkah_kerja,
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
											'kp_bobot' => $row['kp_bobot'],
											'kp_tipe' => $row['tipe'],
											'kp.penjelasan' => $row['penjelasan'],
											'kp.langkah_kerja' => $row['langkah_kerja'],
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
										$cek_opsi_custom = $wpdb->get_var(
											$wpdb->prepare("
												SELECT id
												FROM esakip_penilaian_custom
												WHERE id_komponen_penilaian=%d
												  AND active =1
											", $penilaian['kp_id'])
										);
										$btn = '';
										$btn .= '<div class="btn-action-group">';
										if ($subkomponen['metode_penilaian'] == 2) {
											$button_class = $cek_opsi_custom ? 'btn btn-success' : 'btn btn-secondary';
											$btn .= "<button class='$button_class' onclick='tambah_opsi_modal(\"" . $penilaian['kp_id'] . "\");' title='Tambah Opsi Penilaian Custom'><span class='dashicons dashicons-insert'></span></button>";
										}
										$btn .= "<button class='btn btn-info' onclick='tambah_kerangka_logis(\"" . $penilaian['kp_id'] . "\");' title='Tambah Kerangka Logis'><span class='dashicons dashicons-admin-generic'></span></button>";
										$btn .= "<button class='btn btn-warning' onclick='edit_data_komponen_penilaian(\"" . $penilaian['kp_id'] . "\");' title='Edit Data'><span class='dashicons dashicons-edit'></span></button>";
										$btn .= "<button class='btn btn-danger' onclick='hapus_data_komponen_penilaian(\"" . $penilaian['kp_id'] . "\");' title='Hapus Data'><span class='dashicons dashicons-trash'></span>";
										$btn .= '</div>';

										$tbody .= "<tr>";
										$tbody .= "<td class='text-left'></td>";
										$tbody .= "<td class='text-left'></td>";
										$tbody .= "<td class='text-left'>" . $counter_isi++ . "</td>";
										$tbody .= "<td class='text-left'>" . $penilaian['kp_nama'] . "</td>";
										if ($subkomponen['metode_penilaian'] == 2) {
											$kp_bobot = !empty($penilaian['kp_bobot']) ? $penilaian['kp_bobot'] : "0";
											$tbody .= "<td class='text-center'>" . $kp_bobot . "</td>";
										} else {
											$tbody .= "<td class='text-center'>-</td>";
										}

										if ($penilaian['kp_tipe'] == 1) {
											$tbody .= "<td class='text-center'>Y/T</td>";
										} else if ($penilaian['kp_tipe'] == 2) {
											$tbody .= "<td class='text-center'>A/B/C/D/E</td>";
										} else if ($penilaian['kp_tipe'] == 3) {
											$tbody .= "<td class='text-center'>Custom</td>";
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

										$tbody .= "<td class='text-center'>" . $penilaian['kp.penjelasan'] . "</td>";
										$tbody .= "<td class='text-center'>" . $penilaian['kp.langkah_kerja'] . "</td>";
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

						$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_renstra(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

		$cek_menu_aktif = $wpdb->get_results($wpdb->prepare("
			SELECT 
				*
			FROM esakip_menu_dokumen 
			WHERE tahun_anggaran =%d
			ORDER BY nomor_urut ASC
		", $_GET['tahun']), ARRAY_A);

		$cek_data = array();
		if (!empty($cek_menu_aktif)) {
			foreach ($cek_menu_aktif as $menu) {
				$cek_data[$menu['user_role']][$menu['nama_dokumen']] = $menu;
			}
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
		$periode_input_rpjpd = '';
		foreach ($jadwal_periode_rpjpd as $jadwal_periode_item_rpjpd) {
			$tahun_anggaran_selesai = $jadwal_periode_item_rpjpd['tahun_anggaran'] + $jadwal_periode_item_rpjpd['lama_pelaksanaan'];

			$rpjpd = $this->functions->generatePage(array(
				'nama_page' => 'RPJPD | ' . $jadwal_periode_item_rpjpd['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item_rpjpd['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[upload_dokumen_rpjpd periode=' . $jadwal_periode_item_rpjpd['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$periode_rpjpd .= '<li><a target="_blank" href="' . $rpjpd['url'] . '" class="btn btn-primary">' . $rpjpd['title'] . '</a></li>';

			$input_rpjpd = $this->functions->generatePage(array(
				'nama_page' => 'Input RPJPD | ' . $jadwal_periode_item_rpjpd['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item_rpjpd['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[input_rpjpd periode=' . $jadwal_periode_item_rpjpd['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$periode_input_rpjpd .= '<li><a target="_blank" href="' . $input_rpjpd['url'] . '" class="btn btn-primary">' . $input_rpjpd['title'] . '</a></li>';
		}

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
		// SAKIP PEMDA
		$halaman_monitor_upload_dokumen = '';
		$renja_detail_pemda = '';
		$iku_detail_pemda = '';
		$skp_detail_pemda = '';
		$rencana_aksi_detail_pemda = '';
		$pengukuran_kinerja_detail_pemda = '';
		$pengukuran_rencana_aksi_detail_pemda = '';
		$laporan_kinerja_detail_pemda = '';
		$evaluasi_internal_detail_pemda = '';
		$dokumen_lainnya_detail_pemda = '';
		$perjanjian_kinerja_detail_pemda = '';
		$rkpd_detail_pemda = '';
		$dokumen_pemda_lainnya_detail_pemda = '';
		$lkjip_lppd_detail_pemda = '';
		$dpa_detail_pemda = '';
		$pohon_kinerja_dan_cascading_detail_pemda = '';
		$lhe_akip_internal_detail_pemda = '';
		$tl_lhe_akip_internal_detail_pemda = '';
		$tl_lhe_akip_kemenpan_detail_pemda = '';
		$laporan_monev_renaksi_detail_pemda = '';
		$pedoman_teknis_perencanaan_detail_pemda = '';
		$pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_detail_pemda = '';
		$pedoman_teknis_evaluasi_internal_detail_pemda = '';
		$halaman_rpjpd = '';
		$halaman_rpjmd = '';
		$halaman_renstra = '';
		$halaman_renstra_opd = '';
		$halaman_renstra_skpd = '';
		$periode_rpjmd = '';
		$periode_input_rpjmd = '';
		$periode_input_pohon_kinerja_pemda = '';
		$list_periode_input_pohon_kinerja_opd = '';
		$periode_input_cascading_pemda = '';
		$periode_input_croscutting_pemda = '';
		$periode_dokumen_pohon_kinerja_pemda = '';

		// SAKIP Perangkat Daerah
		$periode_renstra = '';
		$periode_pohon_kinerja = '';
		$renja_rkt_detail = '';
		$iku_detail = '';
		$skp_detail = '';
		$rencana_aksi_detail = '';
		$pengukuran_kinerja_detail = '';
		$pengukuran_rencana_aksi_detail = '';
		$laporan_kinerja_detail = '';
		$evaluasi_internal_detail = '';
		$dokumen_lainnya_detail = '';
		$perjanjian_kinerja_detail = '';
		$rkpd_detail = '';
		$dokumen_pemda_lainnya_detail = '';
		$lkjip_lppd_detail = '';
		$dpa_detail = '';
		$pohon_kinerja_dan_cascading_detail = '';
		$lhe_akip_internal_detail = '';
		$tl_lhe_akip_internal_detail = '';
		$tl_lhe_akip_kemenpan_detail = '';
		$laporan_monev_renaksi_detail = '';
		$pedoman_teknis_perencanaan_detail = '';
		$pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_detail = '';
		$pedoman_teknis_evaluasi_internal_detail = '';
		$periode_input_cascading = '';
		$periode_input_croscutting = '';

		foreach ($jadwal_periode as $jadwal_periode_item) {
			if (!empty($jadwal_periode_item['tahun_selesai_anggaran']) && $jadwal_periode_item['tahun_selesai_anggaran'] > 1) {
				$tahun_anggaran_selesai = $jadwal_periode_item['tahun_selesai_anggaran'];
			} else {
				$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
			}

			//untuk setting muncul atau sembunyi menu jadwal per perode rpjmd
			$cek_menu_aktif_per_periode = $wpdb->get_results(
				$wpdb->prepare("
					SELECT 
						*
					FROM esakip_menu_dokumen 
					WHERE id_jadwal =%d
					ORDER BY nomor_urut ASC
				", $jadwal_periode_item['id']),
				ARRAY_A
			);

			$cek_data_periode = array();
			if (!empty($cek_menu_aktif_per_periode)) {
				foreach ($cek_menu_aktif_per_periode as $menu) {
					$cek_data_periode[$menu['nama_tabel']] = $menu;
				}
			}

			$rpjmd = $this->functions->generatePage(array(
				'nama_page' => 'RPJMD | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[upload_dokumen_rpjmd periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$periode_rpjmd .= '<li><a target="_blank" href="' . $rpjmd['url'] . '" class="btn btn-primary">' . $rpjmd['title'] . '</a></li>';

			$input_rpjmd = $this->functions->generatePage(array(
				'nama_page' => 'Input RPJMD | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[input_rpjmd periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$periode_input_rpjmd .= '<li><a target="_blank" href="' . $input_rpjmd['url'] . '" class="btn btn-primary">' . $input_rpjmd['title'] . '</a></li>';

			$input_pohon_kinerja_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Input Pohon Kinerja ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[penyusunan_pohon_kinerja periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title = 'Input Pohon Kinerja | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
			$periode_input_pohon_kinerja_pemda .= '<li><a target="_blank" href="' . $input_pohon_kinerja_pemda['url'] . '" class="btn btn-primary">' . $title . '</a></li>';

			$input_cascading_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Input Cascading Pemerintah Daerah ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[cascading_pemda periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title = 'Input Cascading Pemda | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
			$periode_input_cascading_pemda .= '<li><a target="_blank" href="' . $input_cascading_pemda['url'] . '" class="btn btn-primary">' . $title . '</a></li>';

			$input_croscutting_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Input Croscutting Pemerintah Daerah ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[croscutting_pemda periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title = 'Input Croscutting Pemda | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
			$periode_input_croscutting_pemda .= '<li><a target="_blank" href="' . $input_croscutting_pemda['url'] . '" class="btn btn-primary">' . $title . '</a></li>';

			$list_input_pohon_kinerja_opd = $this->functions->generatePage(array(
				'nama_page' => 'List Halaman Input Pohon Kinerja Perangkat Daerah ' . $jadwal_periode_item['nama_jadwal_renstra'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[list_penyusunan_pohon_kinerja_opd periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title = 'Input Pohon Kinerja | ' . $jadwal_periode_item['nama_jadwal_renstra'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
			$list_periode_input_pohon_kinerja_opd .= '<li><a target="_blank" href="' . $list_input_pohon_kinerja_opd['url'] . '" class="btn btn-primary">' . $title . '</a></li>';

			$renstra = $this->functions->generatePage(array(
				'nama_page' => 'RENSTRA | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[renstra periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));

			if (!empty($cek_data_periode['esakip_renstra']) && $cek_data_periode['esakip_renstra']['active'] == 1) {
				$periode_renstra .= '<li><a target="_blank" href="' . $renstra['url'] . '" class="btn btn-primary">' . $renstra['title'] . '</a></li>';
			}

			$pohon_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Pohon Kinerja dan Cascading | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[pohon_kinerja_dan_cascading periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));

			if (!empty($cek_data_periode['esakip_pohon_kinerja_dan_cascading']) && $cek_data_periode['esakip_pohon_kinerja_dan_cascading']['active'] == 1) {
				$periode_pohon_kinerja .= '<li><a style="text-align: left;" target="_blank" href="' . $pohon_kinerja['url'] . '" class="btn btn-primary">' . $pohon_kinerja['title'] . '</a></li>';
			}

			$dokumen_pohon_kinerja_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Pohon Kinerja dan Cascading Pemda | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
				'content' => '[dokumen_detail_pohon_kinerja_dan_cascading_pemda periode=' . $jadwal_periode_item['id'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$periode_dokumen_pohon_kinerja_pemda .= '<li><a target="_blank" href="' . $dokumen_pohon_kinerja_pemda['url'] . '" class="btn btn-primary">' . $dokumen_pohon_kinerja_pemda['title'] . '</a></li>';
		}
		// PEMDA

		$monitor_upload_dokumen = $this->functions->generatePage(array(
			'nama_page' => 'Laporan Upload Dokumen' . $_GET['tahun'],
			'content' => '[halaman_cek_dokumen tahun_anggaran=' . $_GET['tahun'] . ']',
			'show_header' => 1,
			'post_status' => 'private'
		));
		$halaman_monitor_upload_dokumen .= '<a target="_blank" href="' . $monitor_upload_dokumen['url'] . '" class="btn btn-primary"> Laporan Monitor Upload Dokumen </a>';

		if (!empty($cek_data['pemerintah_daerah']['IKU']) && $cek_data['pemerintah_daerah']['IKU']['active'] == 1) {
			$iku_pemda = $this->functions->generatePage(array(
				'nama_page' => 'IKU Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_iku_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_iku = 'IKU';
			$cek_data['pemerintah_daerah']['IKU']['link'] = '<li><a target="_blank" href="' . $iku_pemda['url'] . '"   class="btn btn-info">' .  $title_iku . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['SKP']) && $cek_data['pemerintah_daerah']['SKP']['active'] == 1) {
			$skp_pemda = $this->functions->generatePage(array(
				'nama_page' => 'SKP Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_skp_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_skp = 'SKP';
			$cek_data['pemerintah_daerah']['SKP']['link'] = '<li><a target="_blank" href="' . $skp_pemda['url'] . '"  class="btn btn-info">' .  $title_skp . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['Rencana Aksi']) && $cek_data['pemerintah_daerah']['Rencana Aksi']['active'] == 1) {
			$rencana_aksi_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Rencana Aksi Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_rencana_aksi_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_rencana_aksi = 'Rencana Aksi';
			$cek_data['pemerintah_daerah']['Rencana Aksi']['link'] = '<li><a target="_blank" href="' . $rencana_aksi_pemda['url'] . '"  class="btn btn-info">' .  $title_rencana_aksi . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['Pengukuran Kinerja']) && $cek_data['pemerintah_daerah']['Pengukuran Kinerja']['active'] == 1) {
			$pengukuran_kinerja_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Pengukuran Kinerja Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_pengukuran_kinerja_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_pengukuran_kinerja = 'Pengukuran Kinerja';
			$cek_data['pemerintah_daerah']['Pengukuran Kinerja']['link'] = '<li><a target="_blank" href="' . $pengukuran_kinerja_pemda['url'] . '"  class="btn btn-info">' .  $title_pengukuran_kinerja . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['Pengukuran Rencana Aksi']) && $cek_data['pemerintah_daerah']['Pengukuran Rencana Aksi']['active'] == 1) {
			$pengukuran_rencana_aksi_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Pengukuran Rencana Aksi Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_pengukuran_rencana_aksi_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_pengukuran_rencana_aksi = 'Pengukuran Rencana Aksi';
			$cek_data['pemerintah_daerah']['Pengukuran Rencana Aksi']['link'] = '<li><a target="_blank" href="' . $pengukuran_rencana_aksi_pemda['url'] . '"  class="btn btn-info">' .  $title_pengukuran_rencana_aksi . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['Laporan Kinerja']) && $cek_data['pemerintah_daerah']['Laporan Kinerja']['active'] == 1) {
			$laporan_kinerja_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Laporan Kinerja Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_laporan_kinerja_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_laporan_kinerja = 'Laporan Kinerja';
			$cek_data['pemerintah_daerah']['Laporan Kinerja']['link'] = '<li><a target="_blank" href="' . $laporan_kinerja_pemda['url'] . '"  class="btn btn-info">' .  $title_laporan_kinerja . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['Evaluasi Internal']) && $cek_data['pemerintah_daerah']['Evaluasi Internal']['active'] == 1) {
			$evaluasi_internal_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Evaluasi Internal Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_evaluasi_internal_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_evaluasi_internal = 'Evaluasi Internal';
			$cek_data['pemerintah_daerah']['Evaluasi Internal']['link'] = '<li><a target="_blank" href="' . $evaluasi_internal_pemda['url'] . '"  class="btn btn-info">' .  $title_evaluasi_internal . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['Dokumen Lainnya']) && $cek_data['pemerintah_daerah']['Dokumen Lainnya']['active'] == 1) {
			$dokumen_lainnya_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Lainnya Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_dokumen_lainnya_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_dokumen_lainnya = 'Lainnya';
			$cek_data['pemerintah_daerah']['Dokumen Lainnya']['link'] = '<li><a target="_blank" href="' . $dokumen_lainnya_pemda['url'] . '"  class="btn btn-info">' .  $title_dokumen_lainnya . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['Perjanjian Kinerja']) && $cek_data['pemerintah_daerah']['Perjanjian Kinerja']['active'] == 1) {
			$perjanjian_kinerja_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Perjanjian Kinerja Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_perjanjian_kinerja_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_perjanjian_kinerja = 'Perjanjian Kinerja';
			$cek_data['pemerintah_daerah']['Perjanjian Kinerja']['link'] = '<li><a target="_blank" href="' . $perjanjian_kinerja_pemda['url'] . '"  class="btn btn-info">' .  $title_perjanjian_kinerja . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['RKPD']) && $cek_data['pemerintah_daerah']['RKPD']['active'] == 1) {
			$rkpd_pemda = $this->functions->generatePage(array(
				'nama_page' => 'RKPD Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_rkpd_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_rkpd = 'RKPD';
			$cek_data['pemerintah_daerah']['RKPD']['link'] = '<li><a target="_blank" href="' . $rkpd_pemda['url'] . '"  class="btn btn-info">' .  $title_rkpd . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['LKJIP/LPPD']) && $cek_data['pemerintah_daerah']['LKJIP/LPPD']['active'] == 1) {
			$lkjip_lppd_pemda = $this->functions->generatePage(array(
				'nama_page' => 'LKJIP / LPPD Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_lkjip_lppd_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_lkjip_lppd = 'LKJIP / LPPD';
			$cek_data['pemerintah_daerah']['LKJIP/LPPD']['link'] = '<li><a target="_blank" href="' . $lkjip_lppd_pemda['url'] . '"  class="btn btn-info">' .  $title_lkjip_lppd . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['DPA']) && $cek_data['pemerintah_daerah']['DPA']['active'] == 1) {
			$dpa_pemda = $this->functions->generatePage(array(
				'nama_page' => 'DPA Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_dpa_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_dpa = 'DPA';
			$cek_data['pemerintah_daerah']['DPA']['link'] = '<li><a target="_blank" href="' . $dpa_pemda['url'] . '"  class="btn btn-info">' .  $title_dpa . '</a></li>';
		}

		// if (!empty($cek_data['pemerintah_daerah']['Pohon Kinerja dan Cascading']) && $cek_data['pemerintah_daerah']['Pohon Kinerja dan Cascading']['active'] == 1) {
		// 	$pohon_kinerja_dan_cascading_pemda = $this->functions->generatePage(array(
		// 		'nama_page' => 'Pohon Kinerja dan Cascading Pemda' . $_GET['tahun'],
		// 		'content' => '[dokumen_detail_pohon_kinerja_dan_cascading_pemda tahun=' . $_GET['tahun'] . ']',
		// 		'show_header' => 1,
		// 		'post_status' => 'private'
		// 	));
		// 	$title_pohon_kinerja_dan_cascading = 'Pohon Kinerja dan Cascading';
		// 	$cek_data['pemerintah_daerah']['Pohon Kinerja dan Cascading']['link'] = '<li><a target="_blank" href="' . $pohon_kinerja_dan_cascading_pemda['url'] . '"  class="btn btn-info">' .  $title_pohon_kinerja_dan_cascading . '</a></li>';
		// }

		if (!empty($cek_data['pemerintah_daerah']['LHE AKIP Internal']) && $cek_data['pemerintah_daerah']['LHE AKIP Internal']['active'] == 1) {
			$lhe_akip_internal_pemda = $this->functions->generatePage(array(
				'nama_page' => 'LHE AKIP Internal Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_lhe_akip_internal_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_lhe_akip_internal = 'LHE AKIP Internal';
			$cek_data['pemerintah_daerah']['LHE AKIP Internal']['link'] = '<li><a target="_blank" href="' . $lhe_akip_internal_pemda['url'] . '"  class="btn btn-info">' .  $title_lhe_akip_internal . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['TL LHE AKIP Internal']) && $cek_data['pemerintah_daerah']['TL LHE AKIP Internal']['active'] == 1) {
			$tl_lhe_akip_internal_pemda = $this->functions->generatePage(array(
				'nama_page' => 'TL LHE AKIP Internal Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_tl_lhe_akip_internal_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_tl_lhe_akip_internal = 'TL LHE AKIP Internal';
			$cek_data['pemerintah_daerah']['TL LHE AKIP Internal']['link'] = '<li><a target="_blank" href="' . $tl_lhe_akip_internal_pemda['url'] . '"  class="btn btn-info">' .  $title_tl_lhe_akip_internal . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['TL LHE AKIP Kemenpan']) && $cek_data['pemerintah_daerah']['TL LHE AKIP Kemenpan']['active'] == 1) {
			$tl_lhe_akip_kemenpan_pemda = $this->functions->generatePage(array(
				'nama_page' => 'TL LHE AKIP Kemenpan Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_tl_lhe_akip_kemenpan_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_tl_lhe_akip_kemenpan = 'TL LHE AKIP Kemenpan';
			$cek_data['pemerintah_daerah']['TL LHE AKIP Kemenpan']['link'] = '<li><a target="_blank" href="' . $tl_lhe_akip_kemenpan_pemda['url'] . '"  class="btn btn-info">' .  $title_tl_lhe_akip_kemenpan . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['Laporan Monev Renaksi']) && $cek_data['pemerintah_daerah']['Laporan Monev Renaksi']['active'] == 1) {
			$laporan_monev_renaksi_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Laporan Monev Renaksi Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_laporan_monev_renaksi_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_laporan_monev_renaksi = 'Laporan Monev Renaksi';
			$cek_data['pemerintah_daerah']['Laporan Monev Renaksi']['link'] = '<li><a target="_blank" href="' . $laporan_monev_renaksi_pemda['url'] . '"  class="btn btn-info">' .  $title_laporan_monev_renaksi . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['Pedoman Teknis Perencanaan']) && $cek_data['pemerintah_daerah']['Pedoman Teknis Perencanaan']['active'] == 1) {
			$pedoman_teknis_perencanaan_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Pedoman Teknis Perencanaan Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_pedoman_teknis_perencanaan_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_pedoman_teknis_perencanaan = 'Pedoman Teknis Perencanaan';
			$cek_data['pemerintah_daerah']['Pedoman Teknis Perencanaan']['link'] = '<li><a target="_blank" href="' . $pedoman_teknis_perencanaan_pemda['url'] . '"  class="btn btn-info">' .  $title_pedoman_teknis_perencanaan . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja']) && $cek_data['pemerintah_daerah']['Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja']['active'] == 1) {
			$pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja = 'Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja';
			$cek_data['pemerintah_daerah']['Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja']['link'] = '<li><a target="_blank" href="' . $pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda['url'] . '"  class="btn btn-info">' .  $title_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja . '</a></li>';
		}

		if (!empty($cek_data['pemerintah_daerah']['Pedoman Teknis Evaluasi Internal']) && $cek_data['pemerintah_daerah']['Pedoman Teknis Evaluasi Internal']['active'] == 1) {
			$pedoman_teknis_evaluasi_internal_pemda = $this->functions->generatePage(array(
				'nama_page' => 'Pedoman Teknis Evaluasi Internal Pemda' . $_GET['tahun'],
				'content' => '[dokumen_detail_pedoman_teknis_evaluasi_internal_pemda tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_pedoman_teknis_evaluasi_internal = 'Pedoman Teknis Evaluasi Internal';
			$cek_data['pemerintah_daerah']['Pedoman Teknis Evaluasi Internal']['link'] = '<li><a target="_blank" href="' . $pedoman_teknis_evaluasi_internal_pemda['url'] . '"  class="btn btn-info">' .  $title_pedoman_teknis_evaluasi_internal . '</a></li>';
		}

		//DOKUMEN Perangkat Daerah
		if (!empty($cek_data['perangkat_daerah']['RENJA/RKT']) && $cek_data['perangkat_daerah']['RENJA/RKT']['active'] == 1) {
			$renja_rkt = $this->functions->generatePage(array(
				'nama_page' => 'RENJA / RKT ' . $_GET['tahun'],
				'content' => '[renja_rkt tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$cek_data['perangkat_daerah']['RENJA/RKT']['link'] = '<li><a target="_blank" href="' . $renja_rkt['url'] . '" class="btn btn-primary"> RENJA / RKT </a></li>';
		}

		if (!empty($cek_data['perangkat_daerah']['IKU']) && $cek_data['perangkat_daerah']['IKU']['active'] == 1) {
			$iku = $this->functions->generatePage(array(
				'nama_page' => 'IKU -' . $_GET['tahun'],
				'content' => '[iku tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_iku = 'IKU ';
			$cek_data['perangkat_daerah']['IKU']['link'] = '<li><a target="_blank" href="' . $iku['url'] . '" class="btn btn-primary">' .  $title_iku . '</a></li>';
		}

		if (!empty($cek_data['perangkat_daerah']['Perjanjian Kinerja']) && $cek_data['perangkat_daerah']['Perjanjian Kinerja']['active'] == 1) {
			$perjanjian_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Perjanjian Kinerja -' . $_GET['tahun'],
				'content' => '[perjanjian_kinerja tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_perjanjian_kinerja = 'Perjanjian Kinerja';
			$cek_data['perangkat_daerah']['Perjanjian Kinerja']['link'] = '<li><a target="_blank" href="' . $perjanjian_kinerja['url'] . '" class="btn btn-primary">' .  $title_perjanjian_kinerja . '</a></li>';
		}

		if (!empty($cek_data['perangkat_daerah']['Laporan Kinerja']) && $cek_data['perangkat_daerah']['Laporan Kinerja']['active'] == 1) {
			$laporan_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Laporan Kinerja -' . $_GET['tahun'],
				'content' => '[laporan_kinerja tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_laporan_kinerja = 'Laporan Kinerja';
			$cek_data['perangkat_daerah']['Laporan Kinerja']['link'] = '<li><a target="_blank" href="' . $laporan_kinerja['url'] . '" class="btn btn-primary">' .  $title_laporan_kinerja . '</a></li>';
		}

		if (!empty($cek_data['perangkat_daerah']['DPA']) && $cek_data['perangkat_daerah']['DPA']['active'] == 1) {
			$dpa = $this->functions->generatePage(array(
				'nama_page' => 'DPA -' . $_GET['tahun'],
				'content' => '[dpa tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_dpa = 'DPA';
			$cek_data['perangkat_daerah']['DPA']['link'] = '<li><a target="_blank" href="' . $dpa['url'] . '" class="btn btn-primary">' .  $title_dpa . '</a></li>';
		}

		// if (!empty($cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']) && $cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']['active'] == 1) {
		// 	$pohon_kinerja_dan_cascading = $this->functions->generatePage(array(
		// 		'nama_page' => 'Pohon Kinerja dan Cascading -' . $_GET['tahun'],
		// 		'content' => '[pohon_kinerja_dan_cascading tahun=' . $_GET['tahun'] . ']',
		// 		'show_header' => 1,
		// 		'post_status' => 'private'
		// 	));
		// 	$title_pohon_kinerja_dan_cascading = 'Pohon Kinerja dan Cascading';
		// 	$cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']['link'] = '<li><a target="_blank" href="' . $pohon_kinerja_dan_cascading['url'] . '" class="btn btn-primary">' .  $title_pohon_kinerja_dan_cascading . '</a></li>';
		// }

		if (!empty($cek_data['perangkat_daerah']['LHE AKIP Internal']) && $cek_data['perangkat_daerah']['LHE AKIP Internal']['active'] == 1) {
			$lhe_akip_internal = $this->functions->generatePage(array(
				'nama_page' => 'LHE AKIP Internal -' . $_GET['tahun'],
				'content' => '[lhe_akip_internal tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_lhe_akip_internal = 'LHE AKIP Internal';
			$cek_data['perangkat_daerah']['LHE AKIP Internal']['link'] = '<li><a target="_blank" href="' . $lhe_akip_internal['url'] . '" class="btn btn-primary">' .  $title_lhe_akip_internal . '</a></li>';
		}

		if (!empty($cek_data['perangkat_daerah']['TL LHE AKIP Internal']) && $cek_data['perangkat_daerah']['TL LHE AKIP Internal']['active'] == 1) {
			$tl_lhe_akip_internal = $this->functions->generatePage(array(
				'nama_page' => 'TL LHE AKIP Internal -' . $_GET['tahun'],
				'content' => '[tl_lhe_akip_internal tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_tl_lhe_akip_internal = 'TL LHE AKIP Internal';
			$cek_data['perangkat_daerah']['TL LHE AKIP Internal']['link'] = '<li><a target="_blank" href="' . $tl_lhe_akip_internal['url'] . '" class="btn btn-primary">' .  $title_tl_lhe_akip_internal . '</a></li>';
		}

		if (!empty($cek_data['perangkat_daerah']['Laporan Monev Renaksi']) && $cek_data['perangkat_daerah']['Laporan Monev Renaksi']['active'] == 1) {
			$laporan_monev_renaksi = $this->functions->generatePage(array(
				'nama_page' => 'Laporan Monev Renaksi -' . $_GET['tahun'],
				'content' => '[laporan_monev_renaksi tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_laporan_monev_renaksi = 'Laporan Monev Renaksi';
			$cek_data['perangkat_daerah']['Laporan Monev Renaksi']['link'] = '<li><a target="_blank" href="' . $laporan_monev_renaksi['url'] . '" class="btn btn-primary">' .  $title_laporan_monev_renaksi . '</a></li>';
		}

		if (!empty($cek_data['perangkat_daerah']['Dokumen Lainnya']) && $cek_data['perangkat_daerah']['Dokumen Lainnya']['active'] == 1) {
			$dokumen_lainnya = $this->functions->generatePage(array(
				'nama_page' => 'Lainnya -' . $_GET['tahun'],
				'content' => '[dokumen_lainnya tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_dokumen_lainnya = 'Lainnya';
			$cek_data['perangkat_daerah']['Dokumen Lainnya']['link'] = '<li><a target="_blank" href="' . $dokumen_lainnya['url'] . '" class="btn btn-primary">' .  $title_dokumen_lainnya . '</a></li>';
		}

		//DOKUMEN Perangkat Daerah JIKA DIPAKAI
		if (!empty($cek_data['perangkat_daerah']['SKP']) && $cek_data['perangkat_daerah']['SKP']['active'] == 1) {
			$skp = $this->functions->generatePage(array(
				'nama_page' => 'SKP -' . $_GET['tahun'],
				'content' => '[skp tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_skp = 'SKP';
			$cek_data['perangkat_daerah']['SKP']['link'] = '<li><a target="_blank" href="' . $skp['url'] . '" class="btn btn-primary">' .  $title_skp . '</a></li>';
		}

		if (!empty($cek_data['perangkat_daerah']['Rencana Aksi']) && $cek_data['perangkat_daerah']['Rencana Aksi']['active'] == 1) {
			$rencana_aksi = $this->functions->generatePage(array(
				'nama_page' => 'Rencana Aksi -' . $_GET['tahun'],
				'content' => '[rencana_aksi tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_rencana_aksi = 'Rencana Aksi';
			$cek_data['perangkat_daerah']['Rencana Aksi']['link'] = '<li><a target="_blank" href="' . $rencana_aksi['url'] . '" class="btn btn-primary">' .  $title_rencana_aksi . '</a></li>';
		}

		if (!empty($cek_data['perangkat_daerah']['Pengukuran Kinerja']) && $cek_data['perangkat_daerah']['Pengukuran Kinerja']['active'] == 1) {
			$pengukuran_kinerja = $this->functions->generatePage(array(
				'nama_page' => 'Pengukuran Kinerja -' . $_GET['tahun'],
				'content' => '[pengukuran_kinerja tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_pengukuran_kinerja = 'Pengukuran Kinerja';
			$cek_data['perangkat_daerah']['Pengukuran Kinerja']['link'] = '<li><a target="_blank" href="' . $pengukuran_kinerja['url'] . '" class="btn btn-primary">' .  $title_pengukuran_kinerja . '</a></li>';
		}
		// $pengukuran_rencana_aksi = $this->functions->generatePage(array(
		// 	'nama_page' => 'Pengukuran Rencana Aksi',
		// 	'content' => '[pengukuran_rencana_aksi tahun=' . $_GET['tahun'] . ']',
		// 	'show_header' => 1,
		// 	'post_status' => 'private'
		// ));
		// $title_pengukuran_rencana_aksi = 'Pengukuran Rencana Aksi';
		// $pengukuran_rencana_aksi_detail .= '<li><a target="_blank" href="' . $pengukuran_rencana_aksi['url'] . '" class="btn btn-primary">' .  $title_pengukuran_rencana_aksi . '</a></li>';

		if (!empty($cek_data['perangkat_daerah']['Evaluasi Internal']) && $cek_data['perangkat_daerah']['Evaluasi Internal']['active'] == 1) {
			$evaluasi_internal = $this->functions->generatePage(array(
				'nama_page' => 'Evaluasi Internal -' . $_GET['tahun'],
				'content' => '[evaluasi_internal tahun=' . $_GET['tahun'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$title_evaluasi_internal = 'Evaluasi Internal';
			$cek_data['perangkat_daerah']['Evaluasi Internal']['link'] = '<li><a target="_blank" href="' . $evaluasi_internal['url'] . '" class="btn btn-primary">' .  $title_evaluasi_internal . '</a></li>';
		}

		$get_jadwal_lke = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT 
					id,
					nama_jadwal,
					tahun_anggaran,
					lama_pelaksanaan
				FROM esakip_data_jadwal
				WHERE tipe = 'LKE'
				  AND status = 1
				  AND tahun_anggaran=%d
				ORDER BY started_at DESC LIMIT 1",
				$_GET['tahun']
			),
			ARRAY_A
		);
		$pengisian_lke = '';
		foreach ($get_jadwal_lke as $get_jadwal_lke_sakip) {
			$tahun_anggaran_selesai = $get_jadwal_lke_sakip['tahun_anggaran'] + $get_jadwal_lke_sakip['lama_pelaksanaan'];

			$lke = $this->functions->generatePage(array(
				'nama_page' => 'Pengisian LKE | ' . $get_jadwal_lke_sakip['nama_jadwal'],
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

		if (empty($periode_dokumen_pohon_kinerja_pemda)) {
			$periode_dokumen_pohon_kinerja_pemda = '<li><a return="false" href="#" class="btn btn-secondary">Periode RPJMD kosong atau belum dibuat</a></li>';
		}

		if (empty($periode_renstra)) {
			$periode_renstra = '<li><a return="false" href="#" class="btn btn-secondary">Periode RPJMD kosong atau belum dibuat</a></li>';
		}
		if (empty($periode_pohon_kinerja)) {
			$periode_pohon_kinerja = '<li><a return="false" href="#" class="btn btn-secondary">Periode RPJMD kosong atau belum dibuat</a></li>';
		}
		if (empty($periode_input_rpjpd)) {
			$periode_input_rpjpd = '<li><a return="false" href="#" class="btn btn-secondary">Periode Input RPJPD kosong atau belum dibuat</a></li>';
		}
		if (empty($periode_input_rpjmd)) {
			$periode_input_rpjmd = '<li><a return="false" href="#" class="btn btn-secondary">Periode Input RPJMD kosong atau belum dibuat</a></li>';
		}
		if (empty($periode_input_pohon_kinerja_pemda)) {
			$periode_input_pohon_kinerja_pemda = '<li><a return="false" href="#" class="btn btn-secondary">Periode Input Pohon Kinerja kosong atau belum dibuat</a></li>';
		}
		if (empty($periode_input_cascading_pemda)) {
			$periode_input_cascading_pemda = '<li><a return="false" href="#" class="btn btn-secondary">Periode Input Cascading kosong atau belum dibuat</a></li>';
		}
		if (empty($periode_input_croscutting_pemda)) {
			$periode_input_croscutting_pemda = '<li><a return="false" href="#" class="btn btn-secondary">Periode Input Croscutting kosong atau belum dibuat</a></li>';
		}
		if (empty($list_periode_input_pohon_kinerja_opd)) {
			$list_periode_input_pohon_kinerja_opd = '<li><a return="false" href="#" class="btn btn-secondary">Periode Input Pohon Kinerja kosong atau belum dibuat</a></li>';
		}

		$halaman_lke = '
			<div class="accordion">
				<h5 class="esakip-header-tahun" data-id="lke" style="margin: 0;">Pengisian LKE</h5>
				<div class="esakip-body-tahun" data-id="lke">
					<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
						' . $pengisian_lke . '
					</ul>
				</div>
			</div>';
		if (!empty($cek_data['pemerintah_daerah']['RPJPD']) && $cek_data['pemerintah_daerah']['RPJPD']['active'] == 1) {
			$halaman_rpjpd = '
				<div class="accordion">
					<h5 class="esakip-header-tahun" data-id="rpjpd" style="margin: 0;">Periode Upload Dokumen RPJPD</h5>
					<div class="esakip-body-tahun" data-id="rpjpd">
						<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
							' . $periode_rpjpd . '
						</ul>
					</div>
				</div>';
			$cek_data['pemerintah_daerah']['RPJPD']['link'] = $halaman_rpjpd;
		}

		if (!empty($cek_data['pemerintah_daerah']['RPJMD']) && $cek_data['pemerintah_daerah']['RPJMD']['active'] == 1) {
			$halaman_rpjmd = '
				<div class="accordion">
					<h5 class="esakip-header-tahun" data-id="rpjmd" style="margin: 0;">Periode Upload Dokumen RPJMD</h5>
					<div class="esakip-body-tahun" data-id="rpjmd">
						<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
							' . $periode_rpjmd . '
						</ul>
					</div>
				</div>';
			$cek_data['pemerintah_daerah']['RPJMD']['link'] = $halaman_rpjmd;
		}

		if (!empty($cek_data['pemerintah_daerah']['Pohon Kinerja dan Cascading']) && $cek_data['pemerintah_daerah']['Pohon Kinerja dan Cascading']['active'] == 1) {
			$halaman_pohon_kinerja_pemda = '
				<div class="accordion">
					<h5 class="esakip-header-tahun" data-id="pohon-kinerja-opd" style="margin: 0;">Periode Upload Dokumen Pohon Kinerja Dan Cascading</h5>
					<div class="esakip-body-tahun" data-id="pohon-kinerja-opd">
						<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
							' . $periode_dokumen_pohon_kinerja_pemda . '
						</ul>
					</div>
				</div>';
			$cek_data['pemerintah_daerah']['Pohon Kinerja dan Cascading']['link'] = $halaman_pohon_kinerja_pemda;
		}

		if (!empty($cek_data['perangkat_daerah']['RENSTRA']) && $cek_data['perangkat_daerah']['RENSTRA']['active'] == 1) {
			$halaman_renstra_opd = '
				<div class="accordion">
					<h5 class="esakip-header-tahun" data-id="renstra-opd" style="margin: 0;">Periode Upload Dokumen RENSTRA</h5>
					<div class="esakip-body-tahun" data-id="renstra-opd">
						<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
							' . $periode_renstra . '
						</ul>
					</div>
				</div>';
			$cek_data['perangkat_daerah']['RENSTRA']['link'] = $halaman_renstra_opd;
		}

		if (!empty($cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']) && $cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']['active'] == 1) {
			$halaman_pohon_kinerja_opd = '
				<div class="accordion">
					<h5 class="esakip-header-tahun" data-id="pohon-kinerja-opd" style="margin: 0;">Periode Upload Dokumen Pohon Kinerja Dan Cascading</h5>
					<div class="esakip-body-tahun" data-id="pohon-kinerja-opd">
						<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
							' . $periode_pohon_kinerja . '
						</ul>
					</div>
				</div>';
			$cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']['link'] = $halaman_pohon_kinerja_opd;
		}

		//halaman cascading dan croscutting
		// <li>
		// 	<div class="accordion">
		// 		<h5 class="esakip-header-tahun" data-id="halaman-input-pokin" style="margin: 0;">Input Cascading</h5>
		// 		<div class="esakip-body-tahun" data-id="halaman-input-pokin">
		// 			<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
		// 				' . $halaman_input_cascading_pemda . '
		// 			</ul>
		// 		</div>
		// 	</div>
		// </li>
		// <li>
		// 	<div class="accordion">
		// 		<h5 class="esakip-header-tahun" data-id="halaman-input-pokin" style="margin: 0;">Input Crosscutting</h5>
		// 		<div class="esakip-body-tahun" data-id="halaman-input-pokin">
		// 			<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
		// 				' . $halaman_input_croscutting_pemda . '
		// 			</ul>
		// 		</div>
		// 	</div>
		// </li>

		$halaman_input_rpjpd = $periode_input_rpjpd;
		$halaman_input_rpjmd = $periode_input_rpjmd;
		$halaman_input_pohon_kinerja_pemda = $periode_input_pohon_kinerja_pemda;
		$halaman_input_cascading_pemda = $periode_input_cascading_pemda;
		$halaman_input_croscutting_pemda = $periode_input_croscutting_pemda;
		$halaman_sakip = '
			<div class="accordion">
				<h5 class="esakip-header-tahun" data-id="halaman-sakip" style="margin: 0;">Dokumen SAKIP Pemerintah Daerah</h5>
				<div class="esakip-body-tahun" data-id="halaman-sakip">
					<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">';
		foreach ($cek_data['pemerintah_daerah'] as $data) {
			if (!empty($data['link'])) {
				$halaman_sakip .= $data['link'];
			}
		}

		$set_html_pemda = get_option('sakip_menu_khusus_set_html_pemda' . $_GET['tahun']);
		if (!empty($set_html_pemda)) {
			$halaman_sakip .= stripslashes(htmlspecialchars_decode($set_html_pemda));
		}

		$halaman_sakip .= '
					</ul>
				</div>
			</div>';
		$halaman_sakip_opd = '
			<div class="accordion">
				<h5 class="esakip-header-tahun" data-id="halaman-sakip-opd" style="margin: 0;">Dokumen SAKIP Perangkat Daerah</h5>
				<div class="esakip-body-tahun" data-id="halaman-sakip-opd">
					<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">';
		foreach ($cek_data['perangkat_daerah'] as $data) {
			if (!empty($data['link'])) {
				$halaman_sakip_opd .= $data['link'];
			}
		}

		$set_html_opd = get_option('sakip_menu_khusus_set_html_opd' . $_GET['tahun']);
		if (!empty($set_html_opd)) {
			$halaman_sakip_opd .= stripslashes(htmlspecialchars_decode($set_html_opd));
		}

		$halaman_input_rpjpd_rpjmd = '
			<li>
				<div class="accordion">
					<h5 class="esakip-header-tahun" data-id="halaman-input-rpjpd" style="margin: 0;">Input RPJPD</h5>
					<div class="esakip-body-tahun" data-id="halaman-input-rpjpd">
						<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
							' . $halaman_input_rpjpd . '
						</ul>
					</div>
				</div>
			</li>
			<li>
				<div class="accordion">
					<h5 class="esakip-header-tahun" data-id="halaman-input-rpjmd" style="margin: 0;">Input RPJMD / RPD</h5>
					<div class="esakip-body-tahun" data-id="halaman-input-rpjmd">
						<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
							' . $halaman_input_rpjmd . '
						</ul>
					</div>
				</div>
			</li>';
		if (!empty($cek_data['pemerintah_daerah']['Penyusunan Pohon Kinerja']) && $cek_data['pemerintah_daerah']['Penyusunan Pohon Kinerja']['active'] == 1) {
			$halaman_input_rpjpd_rpjmd .= '
				<li>
					<div class="accordion">
						<h5 class="esakip-header-tahun" data-id="halaman-input-pokin" style="margin: 0;">Input Pohon Kinerja Pemerintah Daerah</h5>
						<div class="esakip-body-tahun" data-id="halaman-input-pokin">
							<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
								' . $halaman_input_pohon_kinerja_pemda . '
							</ul>
						</div>
					</div>
				</li>';
		}
		if (!empty($cek_data['perangkat_daerah']['Penyusunan Pohon Kinerja']) && $cek_data['perangkat_daerah']['Penyusunan Pohon Kinerja']['active'] == 1) {
			$halaman_input_rpjpd_rpjmd .= '
				<li>
					<div class="accordion">
						<h5 class="esakip-header-tahun" data-id="halaman-input-pokin-opd" style="margin: 0;">Input Pohon Kinerja Perangkat Daerah</h5>
						<div class="esakip-body-tahun" data-id="halaman-input-pokin-opd">
							<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
								' . $list_periode_input_pohon_kinerja_opd . '
							</ul>
						</div>
					</div>
				</li>';
		}

		$halaman_input_rpjpd_rpjmd .= '
			<li>
				<div class="accordion">
					<h5 class="esakip-header-tahun" data-id="halaman-input-cascading" style="margin: 0;">Input Cascading</h5>
					<div class="esakip-body-tahun" data-id="halaman-input-cascading">
						<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
							' . $halaman_input_cascading_pemda . '
						</ul>
					</div>
				</div>
			</li>
			<li>
				<div class="accordion">
					<h5 class="esakip-header-tahun" data-id="halaman-input-croscutting" style="margin: 0;">Input Croscutting</h5>
					<div class="esakip-body-tahun" data-id="halaman-input-croscutting">
						<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
							' . $halaman_input_croscutting_pemda . '
						</ul>
					</div>
				</div>
			</li>
			';
		$halaman_sakip_opd .= '
					</ul>
				</div>
			</div>';
		if (
			in_array("administrator", $user_meta->roles)
			|| in_array("admin_bappeda", $user_meta->roles)
			|| in_array("admin_ortala", $user_meta->roles)
			|| in_array("admin_panrb", $user_meta->roles)
		) {
			echo '
				<div class="text-center" style="margin: 0 0 10px 0;">' . $halaman_monitor_upload_dokumen . '</div>
        		<ul class="daftar-menu-sakip">
            		<li>' . $halaman_sakip . '</li>
           			<li>' . $halaman_sakip_opd . '</li>
            		<li>' . $halaman_lke . '</li>';
			if (
				in_array("admin_panrb", $user_meta->roles)
				|| in_array("admin_bappeda", $user_meta->roles)
				|| in_array("administrator", $user_meta->roles)
			) {
				echo $halaman_input_rpjpd_rpjmd;
			}
			echo '</ul>';
		} else if (
			in_array("pa", $user_meta->roles)
			|| in_array("kpa", $user_meta->roles)
			|| in_array("plt", $user_meta->roles)
		) {
			$nipkepala = get_user_meta($user_id, '_nip') ?: get_user_meta($user_id, 'nip');
			$tahun_skpd = get_option('_crb_tahun_wpsipd');
			$skpd_db_datas = $wpdb->get_results($wpdb->prepare("
				SELECT 
					nama_skpd, 
					id_skpd, 
					kode_skpd,
					is_skpd
				from esakip_data_unit 
				where nipkepala=%s 
					and tahun_anggaran=%d
				group by id_skpd", $nipkepala[0], $tahun_skpd), ARRAY_A);

			foreach ($skpd_db_datas as $skpd_db) {
				$pengisian_lke_per_skpd_page = '';
				$periode_input_pohon_kinerja_opd = '';
				$periode_renstra_skpd = '';
				$periode_pohon_kinerja_skpd = '';
				//Perangkat Daerah
				$renja_skpd_detail = '';
				$iku_skpd_detail = '';
				$skp_skpd_detail = '';
				$rencana_aksi_skpd_detail = '';
				$pengukuran_kinerja_skpd_detail = '';
				$laporan_kinerja_skpd_detail = '';
				$evaluasi_internal_skpd_detail = '';
				$dokumen_lain_skpd_detail = '';
				$lhe_akip_internal_skpd_detail = '';
				$tl_lhe_akip_internal_skpd_detail = '';
				$laporan_monev_renaksi_skpd_detail = '';

				$cek_menu_aktif = $wpdb->get_results($wpdb->prepare("
					SELECT 
						*
					FROM esakip_menu_dokumen 
					WHERE tahun_anggaran =%d
					ORDER BY nomor_urut ASC
				", $_GET['tahun']), ARRAY_A);

				$cek_data = array();
				if (!empty($cek_menu_aktif)) {
					foreach ($cek_menu_aktif as $menu) {
						$cek_data[$menu['user_role']][$menu['nama_dokumen']] = $menu;
					}
				}

				foreach ($get_jadwal_lke as $get_jadwal_lke_sakip) {
					$pengisian_lke_per_skpd = $this->functions->generatePage(array(
						'nama_page' => 'Pengisian LKE | ' . $skpd_db['nama_skpd'] . ' ' . $get_jadwal_lke_sakip['nama_jadwal'],
						'content' => '[pengisian_lke_sakip_per_skpd id_jadwal=' . $get_jadwal_lke_sakip['id'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$pengisian_lke_per_skpd_page .= '<li><a target="_blank" href="' . $pengisian_lke_per_skpd['url'] . '&id_skpd=' . $skpd_db['id_skpd'] . '&id_jadwal=' . $get_jadwal_lke_sakip['id'] . '" class="btn btn-primary">Pengisian LKE | ' . $get_jadwal_lke_sakip['nama_jadwal'] . '</a></li>';
				}

				$jadwal_periode_rpjmd_renstra = $wpdb->get_results(
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

				foreach ($jadwal_periode_rpjmd_renstra as $jadwal_periode_item) {
					if (!empty($jadwal_periode_item['tahun_selesai_anggaran']) && $jadwal_periode_item['tahun_selesai_anggaran'] > 1) {
						$tahun_anggaran_selesai = $jadwal_periode_item['tahun_selesai_anggaran'];
					} else {
						$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
					}

					$cek_menu_aktif_per_periode = $wpdb->get_results(
						$wpdb->prepare("
							SELECT 
								*
							FROM esakip_menu_dokumen 
							WHERE id_jadwal =%d
							ORDER BY nomor_urut ASC
						", $jadwal_periode_item['id']),
						ARRAY_A
					);

					$cek_data_periode = array();
					if (!empty($cek_menu_aktif_per_periode)) {
						foreach ($cek_menu_aktif_per_periode as $menu) {
							$cek_data_periode[$menu['nama_tabel']] = $menu;
						}
					}

					$renstra_skpd = $this->functions->generatePage(array(
						'nama_page' => 'RENSTRA | ' . $jadwal_periode_item['id'],
						'content' => '[upload_dokumen_renstra periode=' . $jadwal_periode_item['id'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_renstra = 'Dokumen RENSTRA | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
					$renstra_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];

					if (!empty($cek_data_periode['esakip_renstra']) && $cek_data_periode['esakip_renstra']['active'] == 1) {
						$periode_renstra_skpd .= '<li><a target="_blank" href="' . $renstra_skpd['url'] . '" class="btn btn-primary">' . $title_renstra . '</a></li>';
					}

					$pohon_kinerja_skpd = $this->functions->generatePage(array(
						'nama_page' => 'Pohon Kinerja dan Cascading | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai  . ' Perangkat Daerah',
						'content' => '[dokumen_detail_pohon_kinerja_dan_cascading periode=' . $jadwal_periode_item['id'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_pohon_kinerja = 'Pohon Kinerja dan Cascading | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
					$pohon_kinerja_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					if (!empty($cek_data_periode['esakip_pohon_kinerja_dan_cascading']) && $cek_data_periode['esakip_pohon_kinerja_dan_cascading']['active'] == 1) {
						$periode_pohon_kinerja_skpd .= '<li><a style="text-align: left;" target="_blank" href="' . $pohon_kinerja_skpd['url'] . '" class="btn btn-primary">' . $title_pohon_kinerja . '</a></li>';
					}

					$input_pohon_kinerja_opd = $this->functions->generatePage(array(
						'nama_page' => 'Halaman Input Pohon Kinerja Perangkat Daerah ' . $jadwal_periode_item['nama_jadwal_renstra'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
						'content' => '[penyusunan_pohon_kinerja_opd periode=' . $jadwal_periode_item['id'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_pokin = 'Input Pohon Kinerja | ' . $jadwal_periode_item['nama_jadwal_renstra'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
					$input_pohon_kinerja_opd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$periode_input_pohon_kinerja_opd .= '<li><a style="text-align: left;" target="_blank" href="' . $input_pohon_kinerja_opd['url'] . '" class="btn btn-primary">' . $title_pokin . '</a></li>';
				}

				if (empty($periode_input_pohon_kinerja_opd)) {
					$periode_input_pohon_kinerja_opd = '<li><a return="false" href="#" class="btn btn-secondary">Periode Input Pohon Kinerja kosong atau belum dibuat</a></li>';
				}

				if (empty($periode_renstra_skpd)) {
					$periode_renstra_skpd = '<li><a return="false" href="#" class="btn btn-secondary">Periode RPJMD kosong atau belum dibuat</a></li>';
				}

				if (!empty($cek_data['perangkat_daerah']['RENSTRA']) && $cek_data['perangkat_daerah']['RENSTRA']['active'] == 1) {
					$halaman_renstra_skpd = '
						<div class="accordion">
							<h5 class="esakip-header-tahun" data-id="renstra-skpd" style="margin: 0;">Periode Upload Dokumen RENSTRA</h5>
							<div class="esakip-body-tahun" data-id="renstra-skpd">
								<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
									' . $periode_renstra_skpd . '
								</ul>
							</div>
						</div>';
					$cek_data['perangkat_daerah']['RENSTRA']['link'] = $halaman_renstra_skpd;
				}

				if (empty($periode_pohon_kinerja_skpd)) {
					$periode_pohon_kinerja_skpd = '<li><a return="false" href="#" class="btn btn-secondary">Periode RPJMD kosong atau belum dibuat</a></li>';
				}

				if (!empty($cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']) && $cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']['active'] == 1) {
					$halaman_pohon_kinerja_skpd = '
						<div class="accordion">
							<h5 class="esakip-header-tahun" data-id="pohon-kinerja-skpd" style="margin: 0;">Periode Upload Dokumen Pohon Kinerja dan Cascading</h5>
							<div class="esakip-body-tahun" data-id="pohon-kinerja-skpd">
								<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
									' . $periode_pohon_kinerja_skpd . '
								</ul>
							</div>
						</div>';
					$cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']['link'] = $halaman_pohon_kinerja_skpd;
				}

				if (!empty($cek_data['perangkat_daerah']['RENJA/RKT']) && $cek_data['perangkat_daerah']['RENJA/RKT']['active'] == 1) {
					$renja_skpd = $this->functions->generatePage(array(
						'nama_page' => 'RENJA / RKT-' . $_GET['tahun'],
						'content' => '[dokumen_detail_renja_rkt tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));

					$title_renja = 'RENJA / RKT ';
					$renja_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$renja_skpd_detail .= '<li><a target="_blank" href="' . $renja_skpd['url'] . '" class="btn btn-primary">' . $title_renja . '</a></li>';
					$cek_data['perangkat_daerah']['RENJA/RKT']['link'] = $renja_skpd_detail;
				}

				if (!empty($cek_data['perangkat_daerah']['IKU']) && $cek_data['perangkat_daerah']['IKU']['active'] == 1) {
					$iku_skpd = $this->functions->generatePage(array(
						'nama_page' => 'IKU ' . $_GET['tahun'],
						'content' => '[dokumen_detail_iku tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));

					$title_iku = 'IKU ';
					$iku_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$iku_skpd_detail .= '<li><a target="_blank" href="' . $iku_skpd['url'] . '" class="btn btn-primary">' . $title_iku . '</a></li>';
					$cek_data['perangkat_daerah']['IKU']['link'] = $iku_skpd_detail;
				}

				if (!empty($cek_data['perangkat_daerah']['SKP']) && $cek_data['perangkat_daerah']['SKP']['active'] == 1) {
					$skp_skpd = $this->functions->generatePage(array(
						'nama_page' => 'SKP ' . $_GET['tahun'],
						'content' => '[dokumen_detail_skp tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_skp_skpd = 'SKP';
					$skp_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$skp_skpd_detail .= '<li><a href="' . $skp_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_skp_skpd . '</a></li>';
					$cek_data['perangkat_daerah']['SKP']['link'] = $skp_skpd_detail;
				}

				if (!empty($cek_data['perangkat_daerah']['Rencana Aksi']) && $cek_data['perangkat_daerah']['Rencana Aksi']['active'] == 1) {
					$rencana_aksi_skpd = $this->functions->generatePage(array(
						'nama_page' => 'Rencana Aksi ' . $_GET['tahun'],
						'content' => '[dokumen_detail_rencana_aksi tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_rencana_aksi_skpd = 'Rencana Aksi';
					$rencana_aksi_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$rencana_aksi_skpd_detail .= '<li><a href="' . $rencana_aksi_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_rencana_aksi_skpd . '</a></li>';
					$cek_data['perangkat_daerah']['Rencana Aksi']['link'] = $rencana_aksi_skpd_detail;
				}

				if (!empty($cek_data['perangkat_daerah']['Pengukuran Kinerja']) && $cek_data['perangkat_daerah']['Pengukuran Kinerja']['active'] == 1) {
					$pengukuran_kinerja_skpd = $this->functions->generatePage(array(
						'nama_page' => 'Pengukuran Kinerja ' . $_GET['tahun'],
						'content' => '[dokumen_detail_pengukuran_kinerja tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_pengukuran_kinerja_skpd = 'Pengukuran Kinerja';
					$pengukuran_kinerja_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$pengukuran_kinerja_skpd_detail .= '<li><a href="' . $pengukuran_kinerja_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_pengukuran_kinerja_skpd . '</a></li>';
					$cek_data['perangkat_daerah']['Pengukuran Kinerja']['link'] = $pengukuran_kinerja_skpd_detail;
				}

				if (!empty($cek_data['perangkat_daerah']['Laporan Kinerja']) && $cek_data['perangkat_daerah']['Laporan Kinerja']['active'] == 1) {
					$laporan_kinerja_skpd = $this->functions->generatePage(array(
						'nama_page' => 'Laporan Kinerja ' . $_GET['tahun'],
						'content' => '[dokumen_detail_laporan_kinerja tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_laporan_kinerja_skpd = 'Laporan Kinerja';
					$laporan_kinerja_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$laporan_kinerja_skpd_detail .= '<li><a href="' . $laporan_kinerja_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_laporan_kinerja_skpd . '</a></li>';
					$cek_data['perangkat_daerah']['Laporan Kinerja']['link'] = $laporan_kinerja_skpd_detail;
				}

				if (!empty($cek_data['perangkat_daerah']['Evaluasi Internal']) && $cek_data['perangkat_daerah']['Evaluasi Internal']['active'] == 1) {
					$evaluasi_internal_skpd = $this->functions->generatePage(array(
						'nama_page' => 'Evaluasi Internal ' . $_GET['tahun'],
						'content' => '[dokumen_detail_evaluasi_internal tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_evaluasi_internal_skpd = 'Evaluasi Internal';
					$evaluasi_internal_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$evaluasi_internal_skpd_detail .= '<li><a href="' . $evaluasi_internal_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_evaluasi_internal_skpd . '</a></li>';
					$cek_data['perangkat_daerah']['Evaluasi Internal']['link'] = $evaluasi_internal_skpd_detail;
				}

				if (!empty($cek_data['perangkat_daerah']['Dokumen Lainnya']) && $cek_data['perangkat_daerah']['Dokumen Lainnya']['active'] == 1) {
					$dokumen_lain_skpd = $this->functions->generatePage(array(
						'nama_page' => 'Lainnya ' . $_GET['tahun'],
						'content' => '[dokumen_detail_dokumen_lainnya tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_dokumen_lain_skpd = 'Lainnya';
					$dokumen_lain_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$dokumen_lain_skpd_detail .= '<li><a href="' . $dokumen_lain_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_dokumen_lain_skpd . '</a></li>';
					$cek_data['perangkat_daerah']['Dokumen Lainnya']['link'] = $dokumen_lain_skpd_detail;
				}

				if (!empty($cek_data['perangkat_daerah']['Perjanjian Kinerja']) && $cek_data['perangkat_daerah']['Perjanjian Kinerja']['active'] == 1) {
					$perjanjian_kinerja_skpd = $this->functions->generatePage(array(
						'nama_page' => 'Perjanjian Kinerja ' . $_GET['tahun'],
						'content' => '[dokumen_detail_perjanjian_kinerja tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_perjanjian_kinerja_skpd = 'Perjanjian Kinerja';
					$perjanjian_kinerja_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$cek_data['perangkat_daerah']['Perjanjian Kinerja']['link'] = '<li><a href="' . $perjanjian_kinerja_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_perjanjian_kinerja_skpd . '</a></li>';
				}

				if (!empty($cek_data['perangkat_daerah']['DPA']) && $cek_data['perangkat_daerah']['DPA']['active'] == 1) {
					$dpa_skpd = $this->functions->generatePage(array(
						'nama_page' => 'DPA ' . $_GET['tahun'],
						'content' => '[dokumen_detail_dpa tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_dpa_skpd = 'DPA';
					$dpa_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$cek_data['perangkat_daerah']['DPA']['link'] = '<li><a href="' . $dpa_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_dpa_skpd . '</a></li>';
				}

				// if (!empty($cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']) && $cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']['active'] == 1) {
				// 	$pohon_kinerja_dan_cascading_skpd = $this->functions->generatePage(array(
				// 		'nama_page' => 'Pohon Kinerja dan Cascading' . $_GET['tahun'],
				// 		'content' => '[dokumen_detail_pohon_kinerja_dan_cascading tahun=' . $_GET['tahun'] . ']',
				// 		'show_header' => 1,
				// 		'post_status' => 'private'
				// 	));
				// 	$title_pohon_kinerja_dan_cascading_skpd = 'Pohon Kinerja dan Cascading';
				// 	$pohon_kinerja_dan_cascading_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
				// 	$cek_data['perangkat_daerah']['Pohon Kinerja dan Cascading']['link'] = '<li><a href="' . $pohon_kinerja_dan_cascading_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_pohon_kinerja_dan_cascading_skpd . '</a></li>';
				// }

				if (!empty($cek_data['perangkat_daerah']['LHE AKIP Internal']) && $cek_data['perangkat_daerah']['LHE AKIP Internal']['active'] == 1) {
					$lhe_akip_internal_skpd = $this->functions->generatePage(array(
						'nama_page' => 'LHE AKIP Internal' . $_GET['tahun'],
						'content' => '[dokumen_detail_lhe_akip_internal tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_lhe_akip_internal_skpd = 'LHE AKIP Internal';
					$lhe_akip_internal_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$lhe_akip_internal_skpd_detail .= '<li><a href="' . $lhe_akip_internal_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_lhe_akip_internal_skpd . '</a></li>';
					$cek_data['perangkat_daerah']['LHE AKIP Internal']['link'] = $lhe_akip_internal_skpd_detail;
				}

				if (!empty($cek_data['perangkat_daerah']['TL LHE AKIP Internal']) && $cek_data['perangkat_daerah']['TL LHE AKIP Internal']['active'] == 1) {
					$tl_lhe_akip_internal_skpd = $this->functions->generatePage(array(
						'nama_page' => 'TL LHE AKIP Internal' . $_GET['tahun'],
						'content' => '[dokumen_detail_tl_lhe_akip_internal tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_tl_lhe_akip_internal_skpd = 'TL LHE AKIP Internal';
					$tl_lhe_akip_internal_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$tl_lhe_akip_internal_skpd_detail .= '<li><a href="' . $tl_lhe_akip_internal_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_tl_lhe_akip_internal_skpd . '</a></li>';
					$cek_data['perangkat_daerah']['TL LHE AKIP Internal']['link'] = $tl_lhe_akip_internal_skpd_detail;
				}

				if (!empty($cek_data['perangkat_daerah']['Laporan Monev Renaksi']) && $cek_data['perangkat_daerah']['Laporan Monev Renaksi']['active'] == 1) {
					$laporan_monev_renaksi_skpd = $this->functions->generatePage(array(
						'nama_page' => 'Laporan Monev Renaksi' . $_GET['tahun'],
						'content' => '[dokumen_detail_laporan_monev_renaksi tahun=' . $_GET['tahun'] . ']',
						'show_header' => 1,
						'post_status' => 'private'
					));
					$title_laporan_monev_renaksi_skpd = 'Laporan Monev Renaksi';
					$laporan_monev_renaksi_skpd['url'] .= '&id_skpd=' . $skpd_db['id_skpd'];
					$laporan_monev_renaksi_skpd_detail .= '<li><a href="' . $laporan_monev_renaksi_skpd['url'] . '" target="_blank" class="btn btn-primary">' .  $title_laporan_monev_renaksi_skpd . '</a></li>';
					$cek_data['perangkat_daerah']['Laporan Monev Renaksi']['link'] = $laporan_monev_renaksi_skpd_detail;
				}

				if (empty($pengisian_lke_per_skpd_page)) {
					$pengisian_lke_per_skpd_page = '<li><a return="false" href="#" class="btn btn-secondary">Pengisian LKE kosong atau belum dibuat</a></li>';
				}
				$halaman_lke_per_skpd = '
				<div class="accordion">
					<h5 class="esakip-header-tahun" data-id="lke-' . $skpd_db['id_skpd'] . '" style="margin: 0;">Pengisian LKE</h5>
					<div class="esakip-body-tahun" data-id="lke-' . $skpd_db['id_skpd'] . '">
						<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
							' . $pengisian_lke_per_skpd_page . '
						</ul>
					</div>
				</div>';

				$halaman_sakip_skpd = '
					<div class="accordion">
						<h5 class="esakip-header-tahun" data-id="halaman-sakip-skpd-' . $skpd_db['id_skpd'] . '" style="margin: 0;">Dokumen SAKIP</h5>
						<div class="esakip-body-tahun" data-id="halaman-sakip-skpd-' . $skpd_db['id_skpd'] . '">
							<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">';
				foreach ($cek_data['perangkat_daerah'] as $data) {
					if (!empty($data['link'])) {
						$halaman_sakip_skpd .= $data['link'];
					}
				}

				$set_html_opd = get_option('sakip_menu_khusus_set_html_opd' . $_GET['tahun']);
				if (!empty($set_html_opd)) {
					$halaman_sakip_skpd .= stripslashes(htmlspecialchars_decode($set_html_opd));
				}

				$halaman_sakip_skpd .= '
							</ul>
						</div>
					</div>';

				if (!empty($cek_data['perangkat_daerah']['Penyusunan Pohon Kinerja']) && $cek_data['perangkat_daerah']['Penyusunan Pohon Kinerja']['active'] == 1) {
					$halaman_sakip_pokin_opd = '
							<div class="accordion">
								<h5 class="esakip-header-tahun" data-id="halaman-input-pokin-opd-' . $skpd_db['id_skpd'] . '" style="margin: 0;">Input Pohon Kinerja</h5>
								<div class="esakip-body-tahun" data-id="halaman-input-pokin-opd-' . $skpd_db['id_skpd'] . '">
									<ul style="margin-left: 20px; margin-bottom: 10px; margin-top: 5px;">
									' . $periode_input_pohon_kinerja_opd . '
									</ul>
								</div>
							</div>';
				}

				echo '
					<h2 class="text-center">' . $skpd_db['nama_skpd'] . '</h2>
					<ul class="daftar-menu-sakip" style="margin-bottom: 3rem;">
						<li>' . $halaman_sakip_skpd . '</li>
						<li>' . $halaman_lke_per_skpd . '</li>
						<li>' . $halaman_sakip_pokin_opd . '</li>
					</ul>';
			}
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
		$tahun = $wpdb->get_results('select tahun_anggaran from esakip_data_unit group by tahun_anggaran order by tahun_anggaran asc', ARRAY_A);
		echo "
		<h5 class='text-center' style='" . $class_hide . "'>PILIH TAHUN ANGGARAN</h5>
		<ul class='daftar-tahun-sakip text-center' style='margin: 0 0 10px 0;'>";
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
						die(json_encode($ret));
					} else if ($bobot_sub > $bobot_komponen) {
						$ret = array(
							'status' => 'error',
							'message' => 'Bobot Sub Komponen melebihi bobot Komponen Induknya!'
						);
						die(json_encode($ret));
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
				if (!empty($_POST['metode_penilaian'])) {
					$metode_penilaian = $_POST['metode_penilaian'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'metode penilaian kosong!';
				}

				if ($ret['status'] === 'success') {
					$cek_status = '';
					if ($metode_penilaian == 1) {
						$cek_status = $wpdb->get_var(
							$wpdb->prepare("
							SELECT tipe
							FROM esakip_komponen_penilaian
							WHERE id_subkomponen = %d 
							  AND tipe = 3 
							  AND active = 1;
							", $id_subkomponen)
						);
						if (!empty($cek_status)) {
							$ret = array(
								'status' => 'error',
								'message' => 'Metode Rata - Rata Tidak Dapat Mempunyai Tipe Penilaian Custom!'
							);
							die(json_encode($ret));
						}
					} else if ($metode_penilaian == 2) {
						$cek_status = $wpdb->get_var(
							$wpdb->prepare("
							SELECT tipe
							FROM esakip_komponen_penilaian
							WHERE id_subkomponen = %d 
							  AND (tipe = 1 OR tipe = 2) 
							  AND active = 1;
							", $id_subkomponen)
						);
						if (!empty($cek_status)) {
							$ret = array(
								'status' => 'error',
								'message' => 'Metode Dinamis Hanya Dapat Mempunyai Tipe Penilaian Custom!'
							);
							die(json_encode($ret));
						}
					} else {
						$ret = array(
							'status' => 'error',
							'message' => 'Metode Penilaian Tidak Valid!'
						);
						die(json_encode($ret));
					}

					if (!empty($id_subkomponen)) {
						$old_bobot = $wpdb->get_var(
							$wpdb->prepare("
								SELECT bobot 
								FROM esakip_subkomponen
								WHERE id = %d
								  AND active = 1
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
					$bobot_penilaian = $wpdb->get_var(
						$wpdb->prepare("
							SELECT SUM(bobot) 
							FROM esakip_komponen_penilaian 
							WHERE id_subkomponen = %d 
							  AND active = 1
						", $id_subkomponen)
					);
					if ($total_bobot > $bobot_komponen) {
						$ret = array(
							'status' => 'error',
							'message' => 'Total bobot Sub Komponen Komponen Induknya!'
						);
						die(json_encode($ret));
					} else if ($bobot_subkomponen < $bobot_penilaian) {
						$ret = array(
							'status' => 'error',
							'message' => 'Total bobot Komponen Penilaian Melebihi Subkomponen Induknya!'
						);
						die(json_encode($ret));
					} else {
						if (!empty($id_subkomponen)) {
							$wpdb->update(
								'esakip_subkomponen',
								array(
									'nama' => $nama_subkomponen,
									'bobot' => $bobot_subkomponen,
									'nomor_urut' => $nomor_urut,
									'id_user_penilai' => $user_penilai,
									'metode_penilaian' => $metode_penilaian,
								),
								array('id' => $id_subkomponen),
								array('%s', '%f', '%f', '%d', '%d'),
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
									'metode_penilaian' => $metode_penilaian,
								),
								array('%d', '%s', '%f', '%f', '%d', '%d')
							);
						}
					}
				} else {
					die(json_encode($ret));
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
				if (!empty($_POST['penjelasan'])) {
					$penjelasan = $_POST['penjelasan'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Penjelasan kosong!';
				}
				if (!empty($_POST['langkah_kerja'])) {
					$langkah_kerja = $_POST['langkah_kerja'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Langkah Kerja kosong!';
				}
				if ($tipe_komponen_penilaian == 3) {
					if (!empty($_POST['bobot_penilaian'])) {
						$bobot_penilaian = $_POST['bobot_penilaian'];
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Bobot Penilaian kosong!';
					}
				}
				if ($ret['status'] === 'success') {
					if (!empty($id_komponen_penilaian)) {
						$old_bobot = $wpdb->get_var(
							$wpdb->prepare("
								SELECT bobot 
								FROM esakip_komponen_penilaian
								WHERE id = %d
								  AND active = 1
							", $id_komponen_penilaian)
						);
						$total_bobot = $wpdb->get_var(
							$wpdb->prepare("
								SELECT SUM(bobot) 
								FROM esakip_komponen_penilaian
								WHERE id_subkomponen = %d 
									AND active = 1
							", $id_subkomponen)
						) - $old_bobot + $bobot_penilaian;
					} else {
						$total_bobot = $wpdb->get_var(
							$wpdb->prepare("
								SELECT SUM(bobot) 
								FROM esakip_komponen_penilaian
								WHERE id_subkomponen = %d 
									AND active = 1
							", $id_subkomponen)
						) + $bobot_penilaian;
					}

					$bobot_subkomponen = $wpdb->get_var(
						$wpdb->prepare("
							SELECT bobot
							FROM esakip_subkomponen
							WHERE id = %d
								AND active = 1
						", $id_subkomponen)
					);
					if ($total_bobot > $bobot_subkomponen) {
						$ret = array(
							'status' => 'error',
							'message' => 'Total bobot Komponen Penilaian Melebihi Subkomponen Induknya!'
						);
						die(json_encode($ret));
					} else {
						if (!empty($id_komponen_penilaian)) {
							$wpdb->update(
								'esakip_komponen_penilaian',
								array(
									'id_subkomponen' => $id_subkomponen,
									'nama' => $nama_komponen_penilaian,
									'tipe' => $tipe_komponen_penilaian,
									'nomor_urut' => $nomor_urut,
									'keterangan' => $keterangan,
									'penjelasan' => $penjelasan,
									'langkah_kerja' => $langkah_kerja,
									'jenis_bukti_dukung' => $bukti_dukung,
									'bobot' => $bobot_penilaian,
								),
								array('id' => $id_komponen_penilaian),
								array('%d', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%f'),
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
									'penjelasan' => $penjelasan,
									'langkah_kerja' => $langkah_kerja,
									'jenis_bukti_dukung' => $bukti_dukung,
									'bobot' => $bobot_penilaian,
								),
								array('%d', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%f'),
							);
						}
					}
				} else {
					die(json_encode($ret));
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
				} else {
					die(json_encode($ret));
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

	public function tambah_opsi_custom()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil tambah opsi custom!',
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$id_opsi = '';
				if (!empty($_POST['id'])) {
					$id_opsi = $_POST['id'];
					$ret['message'] = 'Berhasil edit data!';
				}

				if (!empty($_POST['id_penilaian'])) {
					$id_komponen_penilaian = $_POST['id_penilaian'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Komponen Penilaian kosong!';
				}
				if (!empty($_POST['nama_opsi'])) {
					$nama_opsi = $_POST['nama_opsi'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'nama opsi kosong!';
				}
				if (isset($_POST['nilai_opsi'])) {
					$nilai_opsi = $_POST['nilai_opsi'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'nilai opsi kosong!';
				}
				if (!empty($_POST['nomor_urut_opsi'])) {
					$nomor_urut_opsi = $_POST['nomor_urut_opsi'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'nomor urut opsi kosong!';
				}

				if ($ret['status'] === 'success') {

					//validasi bobot penilaian dan nilai opsi
					$bobot_penilaian = $wpdb->get_var(
						$wpdb->prepare("
						SELECT bobot
						FROM esakip_komponen_penilaian
						WHERE id=%d
						", $id_komponen_penilaian)
					);
					if ($bobot_penilaian < $nilai_opsi) {
						$ret['status'] = 'error';
						$ret['message'] = 'Melebihi maksimal bobot penilaian!';
						die(json_encode($ret));
					}

					if (!empty($id_opsi)) {
						$wpdb->update(
							'esakip_penilaian_custom',
							array(
								'nama' => $nama_opsi,
								'nilai' => $nilai_opsi,
								'nomor_urut' => $nomor_urut_opsi,
							),
							array('id' => $id_opsi),
							array('%s', '%f', '%f'),
							array('%d')
						);
					} else {
						$wpdb->insert(
							'esakip_penilaian_custom',
							array(
								'id_komponen_penilaian' => $id_komponen_penilaian,
								'nama' => $nama_opsi,
								'nilai' => $nilai_opsi,
								'nomor_urut' => $nomor_urut_opsi,
							),
							array('%d', '%s', '%f', '%f'),
						);
					}
				} else {
					die(json_encode($ret));
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

	public function get_option_custom_by_id()
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
							FROM esakip_penilaian_custom
							WHERE id = %d
						", $_POST['id']),
						ARRAY_A
					);
					if ($data) {
						$default_urutan = $wpdb->get_var(
							$wpdb->prepare("
							SELECT MAX(nomor_urut)
							FROM esakip_penilaian_custom
							WHERE id_komponen_penilaian = %d
						", $_POST['id_penilaian'])
						);

						if ($default_urutan === null) {
							$default_urutan = 0;
						}

						$ret['data'] = $data;
						$ret['default_urutan'] = $default_urutan;
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
									bobot,
									metode_penilaian
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

						//option dinamis
						$opsi_tipe = '<option val="" selected disabled>Pilih Tipe Jawaban</option>';
						//rata rata
						if ($data_subkomponen['metode_penilaian'] == 1) {
							$opsi_tipe .= '
								<option value="1">Y/T</option>
								<option value="2">A/B/C/D/E</option>
							';
							//nilai dinamis
						} else if ($data_subkomponen['metode_penilaian'] == 2) {
							$opsi_tipe .= '
								<option value="3">Custom</option>
							';
						}

						if ($default_urutan === null) {
							$default_urutan = 0;
						}

						$ret['data'] = ['subkomponen' => $data_subkomponen] + ['default_urutan' => $default_urutan] + ['komponen' => $data_komponen] + ['opsi_tipe' => $opsi_tipe];
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
									bobot,
									metode_penilaian
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
						//option dinamis
						$opsi_tipe = '<option val="" selected disabled>Pilih Tipe Jawaban</option>';
						//rata rata
						if ($data_subkomponen['metode_penilaian'] == 1) {
							$opsi_tipe .= '
								<option value="1">Y/T</option>
								<option value="2">A/B/C/D/E</option>
							';
							//nilai dinamis
						} else if ($data_subkomponen['metode_penilaian'] == 2) {
							$opsi_tipe .= '
								<option value="3">Custom</option>
							';
						}

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
						$merged_data['opsi_tipe'] = $opsi_tipe;

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
							  AND active = 1
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
			'message' => "Berhasil hapus data!\n",
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id'])) {
					$id = intval($_POST['id']);

					// Hapus komponen penilaian
					$update_result = $wpdb->update(
						'esakip_komponen_penilaian',
						array('active' => 0),
						array('id' => $id)
					);

					if ($update_result !== false) {
						$ret['message'] .= " Komponen penilaian berhasil dihapus.\n";

						// Cek dan hapus data berkaitan
						$related_tables = [
							'esakip_pengisian_lke' => 'id_komponen_penilaian',
							'esakip_kontrol_kerangka_logis' => 'id_komponen_penilaian',
							'esakip_penilaian_custom' => 'id_komponen_penilaian',
						];

						foreach ($related_tables as $table => $column) {
							$cek_data = $wpdb->get_results(
								$wpdb->prepare("
                                SELECT id
                                FROM $table
                                WHERE $column = %d 
                                  AND active = 1
                            ", $id)
							);

							if (!empty($cek_data)) {
								$delete_result = $wpdb->update(
									$table,
									array('active' => 0),
									array($column => $id)
								);

								if ($delete_result !== false) {
									$ret['message'] .= " Data di tabel $table berhasil dihapus.\n";
								} else {
									$ret['message'] .= " Gagal menghapus data di tabel $table.\n";
									$ret['status'] = 'error';
								}
							}

							// Jika tabel esakip_kontrol_kerangka_logis, update juga kolom id_pembanding
							if ($table == 'esakip_kontrol_kerangka_logis') {
								$cek_pembanding = $wpdb->get_results(
									$wpdb->prepare("
										SELECT id
										FROM esakip_kontrol_kerangka_logis
										WHERE id_komponen_pembanding = %d
										  AND active = 1
									", $id)
								);
	
								if (!empty($cek_pembanding)) {
									$update_pembanding_result = $wpdb->update(
										'esakip_kontrol_kerangka_logis',
										array('active' => 0),
										array('id_komponen_pembanding' => $id)
									);
	
									if ($update_pembanding_result !== false) {
										$ret['message'] .= " Data di tabel esakip_kontrol_kerangka_logis (id_komponen_pembanding) berhasil dihapus.\n";
									} else {
										$ret['message'] .= " Gagal menghapus data di tabel esakip_kontrol_kerangka_logis (id_komponen_pembanding).\n";
										$ret['status'] = 'error';
									}
								}
							}	
						}
					} else {
						$ret = array(
							'status' => 'error',
							'message'   => 'Gagal menghapus komponen penilaian.'
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


	public function hapus_subkomponen_lke()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => "Berhasil hapus data!.\n",
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
							  AND active = 1
						", $_POST['id'])
					);
					$cek_kl = $wpdb->get_var(
						$wpdb->prepare("
							SELECT id_komponen_pembanding
							FROM esakip_kontrol_kerangka_logis
							WHERE id_komponen_pembanding=%d
							  AND active = 1
						", $_POST['id'])
					);

					if (!empty($cek_kl)) {
						$delete_kl = $wpdb->update(
							'esakip_kontrol_kerangka_logis',
							array('active' => 0),
							array('id_komponen_pembanding' => $_POST['id'])
						);
						if ($delete_kl !== false) {
							$ret['message'] .= " Data di tabel esakip_kontrol_kerangka_logis (id_komponen_pembanding) berhasil dihapus.\n";
						} else {
							$ret['message'] .= " Gagal menghapus data di tabel esakip_kontrol_kerangka_logis (id_komponen_pembanding).\n";
							$ret['status'] = 'error';
						}
					} 
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
						  AND id_jadwal = %d
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
								  AND id_komponen = %d
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
										  AND id_subkomponen = %d
									", $subkomponen['id']),
									ARRAY_A
								);
								if (!empty($penilaians)) {
									foreach ($penilaians as $penilaians) {
										$options .= '<option value="' . $penilaians['id'] . '">' . $penilaians['nama'] . '</option>';
									}
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

	public function hapus_opsi_custom()
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
						'esakip_penilaian_custom',
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

	public function list_perangkat_daerah_lke()
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

							$btn .= '<button class="btn btn-danger" onclick="hapus_tahun_dokumen_rpjpd(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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
						FROM esakip_rpjpd
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_rpjpd',
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
							'esakip_rpjpd',
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
				}
				if (!empty($_POST['keterangan'])) {
					$keterangan = $_POST['keterangan'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Keterangan kosong!';
				}
				if ($_POST['tipe_dokumen'] != 'renstra') {
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
					$ret['message'] = 'Id Perangkat Daerah kosong!';
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
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						if ($can_verify) {
							$btn .= '<button class="btn btn-sm btn-success" onclick="verifikasi_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Verifikasi Dokumen"><span class="dashicons dashicons-yes"></span></button>';
						}
						if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('DPA', $tahun_anggaran)) {
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
						FROM esakip_dpa
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_dpa',
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
						} else {
							$data = array(
								'id_dokumen'	=> $wpdb->insert_id,
								'id_skpd'		=> $idSkpd,
								'tahun_anggaran' => $tahunAnggaran,
								'nama_tabel'	=> 'esakip_dpa'
							);

							$setting_verifikasi = $this->setting_verifikasi_upload($data);
							if ($setting_verifikasi['status'] == 'success') {
								$ret['setting_verifikasi'] = $setting_verifikasi;
							}
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

	public function hapus_tahun_dokumen_rpjmd()
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
							FROM esakip_rpjmd
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_rpjmd', array(
							'id' => $_POST['id']
						));
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
	public function hapus_tahun_dokumen_lainnya()
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
							FROM esakip_lainnya
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_lainnya', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_other_file()
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
							FROM esakip_other_file
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_other_file', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_renstra()
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
							FROM esakip_renstra
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_renstra', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_laporan_monev_renaksi()
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
							FROM esakip_laporan_monev_renaksi
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_laporan_monev_renaksi', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_perjanjian_kinerja()
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
							FROM esakip_perjanjian_kinerja
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_perjanjian_kinerja', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_rencana_aksi()
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
							FROM esakip_rencana_aksi
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_rencana_aksi', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_iku()
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
							FROM esakip_iku
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_iku', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_evaluasi_internal()
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
							FROM esakip_evaluasi_internal
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_evaluasi_internal', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_pengukuran_kinerja()
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
							FROM esakip_pengukuran_kinerja
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_pengukuran_kinerja', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_pengukuran_rencana_aksi()
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
							FROM esakip_pengukuran_rencana_aksi
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_pengukuran_rencana_aksi', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_laporan_kinerja()
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
							FROM esakip_laporan_kinerja
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_laporan_kinerja', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_rkpd()
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
							FROM esakip_rkpd
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_rkpd', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_lkjip_lppd()
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
							FROM esakip_lkjip_lppd
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_lkjip_lppd', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_dpa()
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
							FROM esakip_dpa
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_dpa', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_renja_rkt()
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
							FROM esakip_renja_rkt
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_renja_rkt', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_skp()
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
							FROM esakip_skp
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_skp', array(
							'id' => $_POST['id']
						));
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

	public function hapus_tahun_dokumen_rpjpd()
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
							FROM esakip_rpjpd
							WHERE id=%d
						", $_POST['id'])
					);

					if ($get_data) {
						$ret['data'] = $wpdb->delete('esakip_rpjpd', array(
							'id' => $_POST['id']
						));
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
	public function hapus_tahun_dokumen_tipe()
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
					$tipe_dokumen = sanitize_text_field($_POST['tipe_dokumen']);
					$id = intval($_POST['id']);

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

					if (array_key_exists($tipe_dokumen, $nama_tabel)) {
						$table_name = $nama_tabel[$tipe_dokumen];
						$get_data = $wpdb->get_var(
							$wpdb->prepare("
	                            SELECT COUNT(*) 
	                            FROM $table_name 
	                            WHERE id = %d
	                        ", $id)
						);

						if ($get_data) {
							$deleted = $wpdb->delete($table_name, array('id' => $id));
							if ($deleted !== false) {
								$ret['data'] = $deleted;
							} else {
								$ret = array(
									'status' => 'error',
									'message' => 'Gagal menghapus data!'
								);
							}
						} else {
							$ret = array(
								'status' => 'error',
								'message' => 'Data tidak ditemukan!'
							);
						}
					} else {
						$ret = array(
							'status' => 'error',
							'message' => 'Tipe dokumen tidak valid!'
						);
					}
				} else {
					$ret = array(
						'status' => 'error',
						'message' => 'Id atau tipe dokumen kosong!'
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

	public function esakip_get_rpjpd($res = false, $id_jadwal_rpjpd = false)
	{
		global $wpdb;
		$ret = array(
			'status'    => 'success',
			'message'   => 'Berhasil mengambil data RPJPD!'
		);

		if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
			if ($_POST['table'] == 'all') {
				$sql = "
                SELECT 
                    v.id AS id_visi,
                    v.visi_teks,
                    m.id AS id_misi,
                    m.misi_teks,
                    m.urut_misi,
                    s.id AS id_sasaran,
                    s.saspok_teks,
                    s.urut_saspok,
                    k.id AS id_kebijakan,
                    k.kebijakan_teks,
                    k.urut_kebijakan,
                    i.id AS id_isu,
                    i.isu_teks,
                    i.urut_isu
                FROM 
                    esakip_rpjpd_visi v
                LEFT JOIN 
                    esakip_rpjpd_misi m ON v.id = m.id_visi
                LEFT JOIN 
                    esakip_rpjpd_sasaran s ON m.id = s.id_misi
                LEFT JOIN 
                    esakip_rpjpd_kebijakan k ON s.id = k.id_saspok
                LEFT JOIN 
                    esakip_rpjpd_isu i ON k.id = i.id_kebijakan
            	";
				$ret['data'] = $wpdb->get_results($sql, ARRAY_A);
			} else {
				$table = '';
				$where = ' WHERE 1=1 ';

				if (!empty($id_jadwal_rpjpd)) {
					$where .= " AND id_jadwal = $id_jadwal_rpjpd ";
				}

				switch ($_POST['table']) {
					case 'esakip_rpjpd_visi':
						$table = 'esakip_rpjpd_visi';
						break;
					case 'esakip_rpjpd_misi':
						$table = 'esakip_rpjpd_misi';
						if (!empty($_POST['id_misi'])) {
							$where .= $wpdb->prepare(" AND id = %d", $_POST['id_misi']);
						} else {
							$where .= $wpdb->prepare(" AND id_visi = %d", $_POST['id']);
						}
						break;
					case 'esakip_rpjpd_sasaran':
						$table = 'esakip_rpjpd_sasaran';
						if (!empty($_POST['id_saspok'])) {
							$where .= $wpdb->prepare(" AND id = %d", $_POST['id_saspok']);
						} else {
							$where .= $wpdb->prepare(" AND id_misi = %d", $_POST['id']);
						}
						break;
					case 'esakip_rpjpd_kebijakan':
						$table = 'esakip_rpjpd_kebijakan';
						if (!empty($_POST['id_kebijakan'])) {
							$where .= $wpdb->prepare(" AND id = %d", $_POST['id_kebijakan']);
						} else {
							$where .= $wpdb->prepare(" AND id_saspok = %d", $_POST['id']);
						}
						break;
					case 'esakip_rpjpd_isu':
						$table = 'esakip_rpjpd_isu';
						if (!empty($_POST['id_isu'])) {
							$where .= $wpdb->prepare(" AND id = %d", $_POST['id_isu']);
						} else {
							$where .= $wpdb->prepare(" AND id_kebijakan = %d", $_POST['id']);
						}
						break;
					default:
						$ret = array(
							'status' => 'error',
							'message'   => 'Tabel tidak valid!'
						);
						if (!$res) {
							die(json_encode($ret));
						} else {
							return $ret;
						}
				}

				$sql = "SELECT * FROM $table $where";
				$ret['data'] = $wpdb->get_results($sql, ARRAY_A);

				if ($id_jadwal_rpjpd) {
					foreach ($ret['data'] as $k => $data) {
						$ret['data'][$k]['id'] = $data['id_asli'];
					}
				}
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message'   => 'Api Key tidak sesuai!'
			);
		}

		if (!$res) {
			die(json_encode($ret));
		} else {
			return $ret;
		}
	}


	public function esakip_simpan_rpjpd()
	{
		global $wpdb;
		$ret = array(
			'status'    => 'success',
			'message'   => 'Berhasil simpan data RPJPD!'
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$table = '';
				if ($_POST['table'] == 'esakip_rpjpd_visi') {
					$table = $_POST['table'];
					$data = array(
						'visi_teks' => $_POST['data'],
						'update_at' => date('Y-m-d H:i:s')
					);
					if (!empty($_POST['id'])) {
						$wpdb->update($table, $data, array("id" => $_POST['id']));
						$ret['message'] = 'Berhasil update data RPJPD!';
					} else {
						$cek_id = $wpdb->get_var($wpdb->prepare("
                                select 
                                    id 
                                from $table
                                where visi_teks=%s
                            ", $_POST['data']));
						if (!empty($cek_id)) {
							$ret['status'] = 'error';
							$ret['message'] = 'Visi teks sudah ada!';
						} else {
							$wpdb->insert($table, $data);
						}
					}
				} else if ($_POST['table'] == 'esakip_rpjpd_misi') {
					if (!empty($_POST['id_visi'])) {
						$table = $_POST['table'];
						$data = array(
							'id_visi' => $_POST['id_visi'],
							'misi_teks' => $_POST['data'],
							'update_at' => date('Y-m-d H:i:s')
						);
						if (!empty($_POST['id'])) {
							$wpdb->update($table, $data, array("id" => $_POST['id']));
							$ret['message'] = 'Berhasil update data RPJPD!';
						} else {
							$cek_id = $wpdb->get_var($wpdb->prepare("
                                    select 
                                        id 
                                    from $table
                                    where misi_teks=%s
                                ", $_POST['data']));
							if (!empty($cek_id)) {
								$ret['status'] = 'error';
								$ret['message'] = 'Misi teks sudah ada!';
							} else {
								$wpdb->insert($table, $data);
							}
						}
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'ID visi tidak boleh kosong!';
					}
				} else if ($_POST['table'] == 'esakip_rpjpd_sasaran') {
					if (!empty($_POST['id_misi'])) {
						$table = $_POST['table'];
						$data = array(
							'id_misi' => $_POST['id_misi'],
							'saspok_teks' => $_POST['data'],
							'update_at' => date('Y-m-d H:i:s')
						);
						if (!empty($_POST['id'])) {
							$wpdb->update($table, $data, array("id" => $_POST['id']));
							$ret['message'] = 'Berhasil update data RPJPD!';
						} else {
							$cek_id = $wpdb->get_var($wpdb->prepare("
                                    select 
                                        id 
                                    from $table
                                    where saspok_teks=%s
                                ", $_POST['data']));
							if (!empty($cek_id)) {
								$ret['status'] = 'error';
								$ret['message'] = 'Sasaran teks sudah ada!';
							} else {
								$wpdb->insert($table, $data);
							}
						}
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'ID misi tidak boleh kosong!';
					}
				} else if ($_POST['table'] == 'esakip_rpjpd_kebijakan') {
					if (!empty($_POST['id_saspok'])) {
						$table = $_POST['table'];
						$data = array(
							'id_saspok' => $_POST['id_saspok'],
							'kebijakan_teks' => $_POST['data'],
							'update_at' => date('Y-m-d H:i:s')
						);
						if (!empty($_POST['id'])) {
							$wpdb->update($table, $data, array("id" => $_POST['id']));
							$ret['message'] = 'Berhasil update data RPJPD!';
						} else {
							$cek_id = $wpdb->get_var($wpdb->prepare("
                                    select 
                                        id 
                                    from $table
                                    where kebijakan_teks=%s
                                ", $_POST['data']));
							if (!empty($cek_id)) {
								$ret['status'] = 'error';
								$ret['message'] = 'Kebijakan teks sudah ada!';
							} else {
								$wpdb->insert($table, $data);
							}
						}
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'ID sasaran tidak boleh kosong!';
					}
				} else if ($_POST['table'] == 'esakip_rpjpd_isu') {
					if (!empty($_POST['id_kebijakan'])) {
						$table = $_POST['table'];
						$data = array(
							'id_kebijakan' => $_POST['id_kebijakan'],
							'isu_teks' => $_POST['data'],
							'update_at' => date('Y-m-d H:i:s')
						);
						if (!empty($_POST['id'])) {
							$wpdb->update($table, $data, array("id" => $_POST['id']));
							$ret['message'] = 'Berhasil update data RPJPD!';
						} else {
							$cek_id = $wpdb->get_var($wpdb->prepare("
                                    select 
                                        id 
                                    from $table
                                    where isu_teks=%s
                                ", $_POST['data']));
							if (!empty($cek_id)) {
								$ret['status'] = 'error';
								$ret['message'] = 'Isu teks sudah ada!';
							} else {
								$wpdb->insert($table, $data);
							}
						}
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'ID kebijakan tidak boleh kosong!';
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

	public function esakip_hapus_rpjpd()
	{
		global $wpdb;
		$ret = array(
			'status'    => 'success',
			'message'   => 'Berhasil hapus data RPJPD!'
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$table = '';
				$cek_id = '';
				if ($_POST['table'] == 'esakip_rpjpd_visi') {
					$cek_id = $wpdb->get_var(
						$wpdb->prepare("
							SELECT id_visi
							FROM esakip_rpjpd_misi
							WHERE id_visi = %d
						", $_POST['id'])
					);
					if (empty($cek_id)) {
						$table = $_POST['table'];
						$wpdb->delete($table, array('id' => $_POST['id']));
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Visi memiliki Misi aktif! Mohon hapus Misi turunannya terlebih dahulu!';
						die(json_encode($ret));
					}
				} else if ($_POST['table'] == 'esakip_rpjpd_misi') {
					$cek_id = $wpdb->get_var(
						$wpdb->prepare("
							SELECT id_misi
							FROM esakip_rpjpd_sasaran
							WHERE id_misi = %d
						", $_POST['id'])
					);
					if (empty($cek_id)) {
						$table = $_POST['table'];
						$wpdb->delete($table, array('id' => $_POST['id']));
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Misi memiliki Sasaran aktif! Mohon hapus Sasaran turunannya terlebih dahulu!';
						die(json_encode($ret));
					}
				} else if ($_POST['table'] == 'esakip_rpjpd_sasaran') {
					$cek_id = $wpdb->get_var(
						$wpdb->prepare("
							SELECT id_saspok
							FROM esakip_rpjpd_kebijakan
							WHERE id_saspok = %d
						", $_POST['id'])
					);
					if (empty($cek_id)) {
						$table = $_POST['table'];
						$wpdb->delete($table, array('id' => $_POST['id']));
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Sasaran memiliki Kebijakan aktif! Mohon hapus Kebijakan turunannya terlebih dahulu!';
						die(json_encode($ret));
					}
				} else if ($_POST['table'] == 'esakip_rpjpd_kebijakan') {
					$cek_id = $wpdb->get_var(
						$wpdb->prepare("
							SELECT id_kebijakan
							FROM esakip_rpjpd_isu
							WHERE id_kebijakan = %d
						", $_POST['id'])
					);
					if (empty($cek_id)) {
						$table = $_POST['table'];
						$wpdb->delete($table, array('id' => $_POST['id']));
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Kebijakan memiliki Isu aktif! Mohon hapus Isu turunannya terlebih dahulu!';
						die(json_encode($ret));
					}
				} else if ($_POST['table'] == 'esakip_rpjpd_isu') {
					$table = $_POST['table'];
					$wpdb->delete($table, array('id' => $_POST['id']));
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message'   => 'Format tidak sesuai!'
			);
		}
		die(json_encode($ret));
	}

	function _number_format($number = 0, $mata_uang = '')
	{
		if (!is_numeric($number)) {
			$number = 0;
		}
		$uang = number_format($number, 0, ",", ".");
		if (!empty($mata_uang)) {
			$uang = $mata_uang . ' ' . $uang;
		}
		return $uang;
	}

	public function esakip_get_rpd($cb = false)
	{
		global $wpdb;
		$ret = array(
			'status'  => 'success',
			'message' => 'Berhasil get data RPD!'
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$id_jadwal_rpjpd = "";
				if (!empty($_POST['id_unik_tujuan'])) {
					$jadwal_rpd = $wpdb->get_results("select * from esakip_data_jadwal where tipe='RPJMD' and status!=0", ARRAY_A);
					if (!empty($jadwal_rpd)) {
						$id_jadwal_rpjpd = $jadwal_rpd[0]['relasi_perencanaan'];
					}
				}
				$table = '';
				$where = 'where 1=1 and active=1';
				$type = $_POST['type'];
				if ($type != 1) {
					$where .= 'and active=1';
				}
				if ($_POST['table'] == 'esakip_rpd_tujuan') {
					$table = $_POST['table'];
					if (!empty($_POST['id_unik_tujuan'])) {
						$where .= $wpdb->prepare(' and id_unik=%s', $_POST['id_unik_tujuan']);
					} else if (!empty($_POST['id_unik_tujuan_indikator'])) {
						$where .= $wpdb->prepare(' and id_unik_indikator=%s', $_POST['id_unik_tujuan_indikator']);
					}
					$where .= ' ORDER BY no_urut ASC, id ASC';
				} else if ($_POST['table'] == 'esakip_rpd_sasaran') {
					$table = $_POST['table'];
					if (!empty($_POST['id_unik_tujuan'])) {
						$where .= $wpdb->prepare(' and kode_tujuan=%s', $_POST['id_unik_tujuan']);
					} else if (!empty($_POST['id_unik_sasaran'])) {
						$where .= $wpdb->prepare(' and id_unik=%s', $_POST['id_unik_sasaran']);
					} else if (!empty($_POST['id_unik_sasaran_indikator'])) {
						$where .= $wpdb->prepare(' and id_unik_indikator=%s', $_POST['id_unik_sasaran_indikator']);
					}
					$where .= ' ORDER BY sasaran_no_urut ASC, id ASC';
				} else if ($_POST['table'] == 'esakip_rpd_program') {
					$table = $_POST['table'];
					if (!empty($_POST['id_unik_sasaran'])) {
						$where .= $wpdb->prepare(' and kode_sasaran=%s', $_POST['id_unik_sasaran']);
					} else if (!empty($_POST['id_unik_program'])) {
						$where .= $wpdb->prepare(' and id_unik=%s', $_POST['id_unik_program']);
					} else if (!empty($_POST['id_unik_program_indikator'])) {
						$where .= $wpdb->prepare(' and id_unik_indikator=%s', $_POST['id_unik_program_indikator']);
					}
				} else if ($_POST['table'] == 'data_rpd_tujuan') {
					$table = $_POST['table'];
				}
				if (!empty($table)) {
					$sql = $wpdb->prepare("
                        select 
                            * 
                        from $table
                        $where
                    ");
					// die($sql);
					$ret['data'] = $wpdb->get_results($sql, ARRAY_A);
					$data_all = array();
					if ($_POST['table'] == 'esakip_rpd_tujuan') {
						foreach ($ret['data'] as $tujuan) {
							if (empty($data_all[$tujuan['id_unik']])) {
								if (!empty($_POST['id_unik_tujuan_indikator'])) {
									$_POST['id_unik_tujuan'] = $tujuan['id_unik'];
								}
								$sasaran = $wpdb->get_results($wpdb->prepare("
                                    SELECT 
                                        id_unik
                                    from esakip_rpd_sasaran 
                                    where id_unik_indikator IS NULL
                                        AND active=1
                                        AND kode_tujuan=%s
                                ", $tujuan['id_unik']), ARRAY_A);
								$kd_all_sasaran = array();
								foreach ($sasaran as $sas) {
									$kd_all_sasaran[] = "'" . $sas['id_unik'] . "'";
								}
								$kd_all_sasaran =  implode(',', $kd_all_sasaran);
								if (empty($kd_all_sasaran)) {
									$kd_all_sasaran = 0;
								}

								$program = $wpdb->get_results($wpdb->prepare("
                                    SELECT 
                                        id_unik
                                    from esakip_rpd_program 
                                    where id_unik_indikator IS NULL
                                        AND active=1
                                        AND kode_sasaran in ($kd_all_sasaran)
                                ", $sasaran['id_unik']), ARRAY_A);
								$kd_all_prog = array();
								foreach ($program as $prog) {
									$kd_all_prog[] = "'" . $prog['id_unik'] . "'";
								}
								$kd_all_prog =  implode(',', $kd_all_prog);
								if (empty($kd_all_prog)) {
									$kd_all_prog = 0;
								}

								$pagu = $wpdb->get_row($wpdb->prepare("
                                    SELECT 
                                        sum(pagu_1) as pagu_akumulasi_1,
                                        sum(pagu_2) as pagu_akumulasi_2,
                                        sum(pagu_3) as pagu_akumulasi_3,
                                        sum(pagu_4) as pagu_akumulasi_4,
                                        sum(pagu_5) as pagu_akumulasi_5
                                    from esakip_rpd_program 
                                    where id_unik_indikator IS NOT NULL
                                        AND active=1
                                        AND id_unik in ($kd_all_prog)
                                "), ARRAY_A);
								$data_all[$tujuan['id_unik']] = array(
									'id' => $tujuan['id'],
									'id_unik' => $tujuan['id_unik'],
									'nama' => $tujuan['tujuan_teks'],
									'id_jadwal_rpjpd' => $id_jadwal_rpjpd,
									'pagu_akumulasi_1' => $pagu['pagu_akumulasi_1'],
									'pagu_akumulasi_2' => $pagu['pagu_akumulasi_2'],
									'pagu_akumulasi_3' => $pagu['pagu_akumulasi_3'],
									'pagu_akumulasi_4' => $pagu['pagu_akumulasi_4'],
									'pagu_akumulasi_5' => $pagu['pagu_akumulasi_5'],
									'rpjpd' => array(),
									'detail' => array(),
									'no_urut' => $tujuan['no_urut'],
									'catatan_teks_tujuan' => $tujuan['catatan_teks_tujuan'],
									'indikator_catatan_teks' => $tujuan['indikator_catatan_teks']
								);
								if (!empty($_POST['id_unik_tujuan'])) {
									$_POST['table'] = 'data_rpjpd_isu';
									$_POST['id_isu'] = $tujuan['id_isu'];
									$data_all[$tujuan['id_unik']]['rpjpd']['isu'] = array(
										'data' => $this->esakip_get_rpjpd(true, $id_jadwal_rpjpd),
										'id' => $tujuan['id_isu']
									);

									$_POST['table'] = 'data_rpjpd_kebijakan';
									$_POST['id_kebijakan'] = $data_all[$tujuan['id_unik']]['rpjpd']['isu']['data']['data'][0]['id_kebijakan'];
									$data_all[$tujuan['id_unik']]['rpjpd']['kebijakan'] = array(
										'data' => $this->esakip_get_rpjpd(true, $id_jadwal_rpjpd),
										'id' => $_POST['id_kebijakan']
									);

									$_POST['table'] = 'data_rpjpd_sasaran';
									$_POST['id_saspok'] = $data_all[$tujuan['id_unik']]['rpjpd']['kebijakan']['data']['data'][0]['id_saspok'];
									$data_all[$tujuan['id_unik']]['rpjpd']['sasaran'] = array(
										'data' => $this->esakip_get_rpjpd(true, $id_jadwal_rpjpd),
										'id' => $_POST['id_saspok']
									);

									$_POST['table'] = 'data_rpjpd_misi';
									$_POST['id_misi'] = $data_all[$tujuan['id_unik']]['rpjpd']['sasaran']['data']['data'][0]['id_misi'];
									$data_all[$tujuan['id_unik']]['rpjpd']['misi'] = array(
										'data' => $this->esakip_get_rpjpd(true, $id_jadwal_rpjpd),
										'id' => $_POST['id_misi']
									);

									$_POST['table'] = 'data_rpjpd_visi';
									$_POST['id_visi'] = $data_all[$tujuan['id_unik']]['rpjpd']['misi']['data']['data'][0]['id_visi'];
									$data_all[$tujuan['id_unik']]['rpjpd']['visi'] = array(
										'data' => $this->esakip_get_rpjpd(true, $id_jadwal_rpjpd),
										'id' => $_POST['id_visi']
									);
								}
							}
							if (!empty($tujuan['id_unik_indikator'])) {
								$data_all[$tujuan['id_unik']]['detail'][] = $tujuan;
							}
						}
					} else if ($_POST['table'] == 'esakip_rpd_sasaran') {
						foreach ($ret['data'] as $sasaran) {
							if (empty($data_all[$sasaran['id_unik']])) {
								$program = $wpdb->get_results($wpdb->prepare("
                                    SELECT 
                                        id_unik
                                    from esakip_rpd_program 
                                    where id_unik_indikator IS NULL
                                        AND active=1
                                        AND kode_sasaran=%s
                                ", $sasaran['id_unik']), ARRAY_A);
								$kd_all_prog = array();
								foreach ($program as $prog) {
									$kd_all_prog[] = "'" . $prog['id_unik'] . "'";
								}
								$kd_all_prog =  implode(',', $kd_all_prog);
								if (empty($kd_all_prog)) {
									$kd_all_prog = 0;
								}
								$pagu = $wpdb->get_row($wpdb->prepare("
                                    SELECT 
                                        sum(pagu_1) as pagu_akumulasi_1,
                                        sum(pagu_2) as pagu_akumulasi_2,
                                        sum(pagu_3) as pagu_akumulasi_3,
                                        sum(pagu_4) as pagu_akumulasi_4,
                                        sum(pagu_5) as pagu_akumulasi_5
                                    from esakip_rpd_program 
                                    where id_unik_indikator IS NOT NULL
                                        AND active=1
                                        AND id_unik in ($kd_all_prog)
                                "), ARRAY_A);
								$data_all[$sasaran['id_unik']] = array(
									'id' => $sasaran['id'],
									'id_unik' => $sasaran['id_unik'],
									'nama' => $sasaran['sasaran_teks'],
									'pagu_akumulasi_1' => $pagu['pagu_akumulasi_1'],
									'pagu_akumulasi_2' => $pagu['pagu_akumulasi_2'],
									'pagu_akumulasi_3' => $pagu['pagu_akumulasi_3'],
									'pagu_akumulasi_4' => $pagu['pagu_akumulasi_4'],
									'pagu_akumulasi_5' => $pagu['pagu_akumulasi_5'],
									'sasaran_no_urut' => $sasaran['sasaran_no_urut'],
									'sasaran_catatan' => $sasaran['sasaran_catatan'],
									'indikator_catatan_teks' => $sasaran['indikator_catatan_teks'],
									'detail' => array()
								);
							}
							if (!empty($sasaran['id_unik_indikator'])) {
								$data_all[$sasaran['id_unik']]['detail'][] = $sasaran;
							}
						}
					} else if ($_POST['table'] == 'esakip_rpd_program') {
						foreach ($ret['data'] as $program) {
							if (empty($data_all[$program['id_unik']])) {
								$pagu = $wpdb->get_row($wpdb->prepare("
                                    SELECT 
                                        sum(pagu_1) as pagu_akumulasi_1,
                                        sum(pagu_2) as pagu_akumulasi_2,
                                        sum(pagu_3) as pagu_akumulasi_3,
                                        sum(pagu_4) as pagu_akumulasi_4,
                                        sum(pagu_5) as pagu_akumulasi_5
                                    from esakip_rpd_program 
                                    where id_unik_indikator IS NOT NULL
                                        AND active=1
                                        AND id_unik=%s
                                ", $program['id_unik']), ARRAY_A);
								$data_all[$program['id_unik']] = array(
									'id' => $program['id'],
									'id_unik' => $program['id_unik'],
									'id_program' => $program['id_program'],
									'catatan' => $program['catatan'],
									'nama' => $program['nama_program'],
									'pagu_akumulasi_1' => $pagu['pagu_akumulasi_1'],
									'pagu_akumulasi_2' => $pagu['pagu_akumulasi_2'],
									'pagu_akumulasi_3' => $pagu['pagu_akumulasi_3'],
									'pagu_akumulasi_4' => $pagu['pagu_akumulasi_4'],
									'pagu_akumulasi_5' => $pagu['pagu_akumulasi_5'],
									'detail' => array()
								);
							}
							if (!empty($program['id_unik_indikator'])) {
								$data_all[$program['id_unik']]['detail'][] = $program;
							}
						}
					}
					$ret['data_all'] = $data_all;
				} else {
					$ret = array(
						'status'  => 'error',
						'message' => 'Tabel Kosong!'
					);
				}
			} else {
				$ret = array(
					'status'  => 'error',
					'message' => 'API key tidak valid!'
				);
			}
		} else {
			$ret = array(
				'status'  => 'error',
				'message' => 'POST data tidak boleh kosong!'
			);
		}

		die(json_encode($ret));
	}


	public function esakip_simpan_rpd()
	{
		global $wpdb;
		$ret = array(
			'status'    => 'success',
			'message'   => 'Berhasil simpan data RPD!'
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$table = '';
				if ($_POST['table'] == 'esakip_rpd_tujuan') {
					$table = $_POST['table'];
					// simpan atau edit indikator tujuan
					if (!empty($_POST['id_tujuan'])) {
						$tujuan = $wpdb->get_results($wpdb->prepare("
                                select
                                    id_isu,
                                    tujuan_teks,
                                    id_unik,
                                    no_urut
                                from $table
                                where id_unik=%s
                            ", $_POST['id_tujuan']), ARRAY_A);
						for ($i = 1; $i <= 5; $i++) {
							if (empty($_POST['vol_' . $i]) && empty($_POST['satuan_' . $i])) {
								$_POST['vol_' . $i] = '';
								$_POST['satuan_' . $i] = '';
							}
						}
						$data = array(
							'id_isu' => $tujuan[0]['id_isu'],
							'tujuan_teks' => $tujuan[0]['tujuan_teks'],
							'id_unik' => $tujuan[0]['id_unik'],
							'indikator_teks' => $_POST['data'],
							'target_awal' => $_POST['vol_awal'],
							'target_1' => $_POST['vol_1'],
							'target_2' => $_POST['vol_2'],
							'target_3' => $_POST['vol_3'],
							'target_4' => $_POST['vol_4'],
							'target_5' => $_POST['vol_5'],
							'target_akhir' => $_POST['vol_akhir'],
							'update_at' => date('Y-m-d H:i:s'),
							'no_urut' => $tujuan[0]['no_urut'],
							'indikator_catatan_teks' => $_POST['indikator_catatan_teks'],
							'satuan' => $_POST['satuan'],
							'active' => 1
						);
						if (!empty($_POST['id'])) {
							$data['id_unik_indikator'] = $_POST['id'];
							$wpdb->update($table, $data, array("id_unik_indikator" => $_POST['id']));
							$ret['message'] = 'Berhasil update data RPD!';
						} else {
							$data['id_unik_indikator'] = $this->generateRandomString(5);
							$cek_id = $wpdb->get_var($wpdb->prepare("
                                    select 
                                        id 
                                    from $table
                                    where indikator_teks=%s
                                        and id_unik=%s
                                ", $_POST['data'], $_POST['id_tujuan']));
							if (!empty($cek_id)) {
								$ret['status'] = 'error';
								$ret['message'] = 'Indikator tujuan teks sudah ada!';
							} else {
								$wpdb->insert($table, $data);
							}
						}
						// simpan atau edit tujuan
					} else {
						$data = array(
							'id_isu' => $_POST['id_isu'],
							'tujuan_teks' => $_POST['data'],
							'update_at' => date('Y-m-d H:i:s'),
							'no_urut'   => $_POST['no_urut'],
							'catatan_teks_tujuan'   => $_POST['catatan_teks_tujuan'],
							'active' => 1
						);
						if (!empty($_POST['id'])) {
							$data['id_unik'] = $_POST['id'];
							$wpdb->update($table, $data, array("id_unik" => $_POST['id']));
							$ret['message'] = 'Berhasil update data RPD!';
						} else {
							$data['id_unik'] = $this->generateRandomString(5);
							$cek_id = $wpdb->get_var($wpdb->prepare("
                                    select 
                                        id 
                                    from $table
                                    where tujuan_teks=%s
                                ", $_POST['data']));
							if (!empty($cek_id)) {
								$ret['status'] = 'error';
								$ret['message'] = 'Tujuan teks sudah ada!';
							} else {
								$wpdb->insert($table, $data);
							}
						}
					}
				} else if ($_POST['table'] == 'esakip_rpd_sasaran') {
					$table = $_POST['table'];
					// simpan atau edit indikator sasaran
					if (!empty($_POST['id_sasaran'])) {
						$sasaran = $wpdb->get_results($wpdb->prepare("
                                select
                                    kode_tujuan,
                                    sasaran_teks,
                                    id_unik,
                                    sasaran_no_urut
                                from $table
                                where id_unik=%s
                            ", $_POST['id_sasaran']), ARRAY_A);
						for ($i = 1; $i <= 5; $i++) {
							if (empty($_POST['vol_' . $i]) && empty($_POST['satuan_' . $i])) {
								$_POST['vol_' . $i] = '';
								$_POST['satuan_' . $i] = '';
							}
						}
						$data = array(
							'kode_tujuan' => $sasaran[0]['kode_tujuan'],
							'sasaran_teks' => $sasaran[0]['sasaran_teks'],
							'id_unik' => $sasaran[0]['id_unik'],
							'indikator_teks' => $_POST['data'],
							'target_awal' => $_POST['vol_awal'],
							'target_1' => $_POST['vol_1'],
							'target_2' => $_POST['vol_2'],
							'target_3' => $_POST['vol_3'],
							'target_4' => $_POST['vol_4'],
							'target_5' => $_POST['vol_5'],
							'target_akhir' => $_POST['vol_akhir'],
							'update_at' => date('Y-m-d H:i:s'),
							'sasaran_no_urut' => $sasaran[0]['sasaran_no_urut'],
							'indikator_catatan_teks' => $_POST['indikator_catatan_teks'],
							'satuan' => $_POST['satuan'],
							'active' => 1
						);
						if (!empty($_POST['id'])) {
							$data['id_unik_indikator'] = $_POST['id'];
							$wpdb->update($table, $data, array("id_unik_indikator" => $_POST['id']));
							$ret['message'] = 'Berhasil update data RPD!';
						} else {
							$data['id_unik_indikator'] = $this->generateRandomString(5);
							$cek_id = $wpdb->get_var($wpdb->prepare("
                                    select 
                                        id 
                                    from $table
                                    where indikator_teks=%s
                                        and id_unik=%s
                                ", $_POST['data'], $_POST['id_sasaran']));
							if (!empty($cek_id)) {
								$ret['status'] = 'error';
								$ret['message'] = 'Indikator sasaran teks sudah ada!';
							} else {
								$wpdb->insert($table, $data);
							}
						}
						// simpan atau edit sasaran
					} else {
						$data = array(
							'kode_tujuan' => $_POST['id_tujuan'],
							'sasaran_teks' => $_POST['data'],
							'update_at' => date('Y-m-d H:i:s'),
							'sasaran_no_urut' => $_POST['sasaran_no_urut'],
							'sasaran_catatan' => $_POST['sasaran_catatan'],
							'active' => 1
						);
						if (!empty($_POST['id'])) {
							$data['id_unik'] = $_POST['id'];
							$wpdb->update($table, $data, array("id_unik" => $_POST['id']));
							$ret['message'] = 'Berhasil update data RPD!';
						} else {
							$data['id_unik'] = $this->generateRandomString(5);
							$cek_id = $wpdb->get_var($wpdb->prepare("
                                    select 
                                        id 
                                    from $table
                                    where sasaran_teks=%s
                                ", $_POST['data']));
							if (!empty($cek_id)) {
								$ret['status'] = 'error';
								$ret['message'] = 'Sasaran teks sudah ada!';
							} else {
								$wpdb->insert($table, $data);
							}
						}
					}
				} else if ($_POST['table'] == 'esakip_rpd_program') {
					$table = $_POST['table'];
					// simpan atau edit indikator program
					if (!empty($_POST['id_program'])) {
						$program = $wpdb->get_results($wpdb->prepare("
                                select
                                    kode_sasaran,
                                    nama_program,
                                    id_program,
                                    id_unik
                                from $table
                                where id_unik=%s
                            ", $_POST['id_program']), ARRAY_A);
						for ($i = 1; $i <= 5; $i++) {
							if (empty($_POST['vol_' . $i]) && empty($_POST['satuan_' . $i])) {
								$_POST['vol_' . $i] = '';
								$_POST['satuan_' . $i] = '';
								$_POST['pagu_' . $i] = '';
							}
						}
						$data = array(
							'kode_sasaran' => $program[0]['kode_sasaran'],
							'nama_program' => $program[0]['nama_program'],
							'id_program' => $program[0]['id_program'],
							'id_unik_indikator_sasaran' => $_POST['indikator_sasaran_program'],
							'id_unik' => $program[0]['id_unik'],
							'id_unit' => $_POST['id_skpd'],
							'kode_skpd' => $_POST['kode_skpd'],
							'nama_skpd' => $_POST['nama_skpd'],
							'indikator' => $_POST['data'],
							'target_awal' => $_POST['vol_awal'],
							'target_1' => $_POST['vol_1'],
							'pagu_1' => $_POST['pagu_1'],
							'target_2' => $_POST['vol_2'],
							'pagu_2' => $_POST['pagu_2'],
							'target_3' => $_POST['vol_3'],
							'pagu_3' => $_POST['pagu_3'],
							'target_4' => $_POST['vol_4'],
							'pagu_4' => $_POST['pagu_4'],
							'target_5' => $_POST['vol_5'],
							'pagu_5' => $_POST['pagu_5'],
							'target_akhir' => $_POST['vol_akhir'],
							'catatan' => $_POST['catatan'],
							'satuan' => $_POST['satuan'],
							'update_at' => date('Y-m-d H:i:s'),
							'active' => 1
						);
						if (!empty($_POST['id'])) {
							$data['id_unik_indikator'] = $_POST['id'];
							$wpdb->update($table, $data, array("id_unik_indikator" => $_POST['id']));
							$ret['message'] = 'Berhasil update data RPD!';
						} else {
							$data['id_unik_indikator'] = $this->generateRandomString(5);
							$cek_id = $wpdb->get_var($wpdb->prepare("
                                    select 
                                        id 
                                    from $table
                                    where indikator=%s
                                        and id_unik=%s
                                ", $_POST['data'], $_POST['id_program']));
							if (!empty($cek_id)) {
								$ret['status'] = 'error';
								$ret['message'] = 'Indikator program sudah ada!';
							} else {
								$wpdb->insert($table, $data);
							}
						}
						// simpan atau edit program
					} else {
						$data = array(
							'kode_sasaran' => $_POST['id_sasaran'],
							'nama_program' => $_POST['nama_program'],
							'catatan' => $_POST['catatan'],
							'id_program' => $_POST['data'],
							'update_at' => date('Y-m-d H:i:s'),
							'active' => 1
						);
						if (!empty($_POST['id'])) {
							$data['id_unik'] = $_POST['id'];
							$wpdb->update($table, $data, array("id_unik" => $_POST['id']));
							$ret['message'] = 'Berhasil update data RPD!';
						} else {
							$data['id_unik'] = $this->generateRandomString(5);
							$cek_id = $wpdb->get_var($wpdb->prepare("
                                    select 
                                        id 
                                    from $table
                                    where nama_program=%s
                                ", $_POST['nama_program']));
							if (!empty($cek_id)) {
								$ret['status'] = 'error';
								$ret['message'] = 'Program teks sudah ada!';
							} else {
								$wpdb->insert($table, $data);
							}
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

	public function esakip_hapus_rpd()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil hapus data RPD!',
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] === get_option(ESAKIP_APIKEY)) {
				$table = '';
				switch ($_POST['table']) {
					case 'esakip_rpd_tujuan':
						$table = $_POST['table'];
						if (!empty($_POST['id_unik_tujuan_indikator'])) {
							$wpdb->delete($table, ['id_unik_indikator' => $_POST['id_unik_tujuan_indikator']]);
						} else {
							$cek_id = $wpdb->get_var(
								$wpdb->prepare("
									SELECT kode_tujuan
									FROM esakip_rpd_sasaran
									WHERE kode_tujuan = %d
								", $_POST['id'])
							);
							if (empty($cek_id)) {
								$wpdb->delete($table, ['id_unik' => $_POST['id']]);
							} else {
								$ret['status'] = 'error';
								$ret['message'] = 'Tujuan memiliki Sasaran aktif! Mohon hapus Sasaran turunannya terlebih dahulu!';
								die(json_encode($ret));
							}
						}
						break;
					case 'esakip_rpd_sasaran':
						$table = $_POST['table'];
						if (!empty($_POST['id_unik_sasaran_indikator'])) {
							$wpdb->delete($table, ['id_unik_indikator' => $_POST['id_unik_sasaran_indikator']]);
						} else {
							$cek_id = $wpdb->get_var(
								$wpdb->prepare("
									SELECT kode_sasaran
									FROM esakip_rpd_program
									WHERE kode_sasaran = %d
								", $_POST['id'])
							);
							if (empty($cek_id)) {
								$wpdb->delete($table, ['id_unik' => $_POST['id']]);
							} else {
								$ret['status'] = 'error';
								$ret['message'] = 'Sasaran memiliki Program aktif! Mohon hapus Program turunannya terlebih dahulu!';
								die(json_encode($ret));
							}
						}
						break;
					case 'esakip_rpd_program':
						$table = $_POST['table'];
						if (!empty($_POST['id_unik_program_indikator'])) {
							$wpdb->delete($table, ['id_unik_indikator' => $_POST['id_unik_program_indikator']]);
						} else {
							$wpdb->delete($table, ['id_unik' => $_POST['id']]);
						}
						break;
					default:
						$ret['status'] = 'error';
						$ret['message'] = 'Param table tidak boleh kosong!';
						break;
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message' => 'Api Key tidak sesuai!',
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message'   => 'Format tidak sesuai!'
			);
		}
		die(json_encode($ret));
	}

	public function esakip_get_bidang_urusan()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data bidang urusan dari WP-SIPD!',
			'data' => array(),
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] === get_option(ESAKIP_APIKEY)) {
				if (!empty(get_option(ESAKIP_URL_WPSIPD))) {
					$url = get_option(ESAKIP_URL_WPSIPD);
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'URL WP-SIPD Kosong!';
					die(json_encode($ret));
				}
				if (!empty(get_option(ESAKIP_APIKEY_WPSIPD))) {
					$api_key = get_option(ESAKIP_APIKEY_WPSIPD);
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'API-Key WP-SIPD Kosong!';
					die(json_encode($ret));
				}
				$api_params = array(
					'action' => 'get_bidang_urusan',
					'api_key' => $api_key,
					'type' => $_POST['type']
				);

				$response = wp_remote_post($url, array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));
				$response = wp_remote_retrieve_body($response);
				$data = json_decode($response);

				$ret['data'] = $data;
			} else {
				$ret = array(
					'status' => 'error',
					'message' => 'Api Key tidak sesuai!',
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message'   => 'Format tidak sesuai!'
			);
		}
		die(json_encode($ret));
	}

	public function get_indikator_sasaran()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id_sasaran'])) {
					$id_sasaran = $_POST['id_sasaran'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'ID Sasaran Kosong';
					die(json_encode($ret));
				}

				$indikator_sasaran_all = $wpdb->get_results(
					$wpdb->prepare("
						SELECT id_unik_indikator, indikator_teks 
						FROM esakip_rpd_sasaran 
						WHERE id_unik =%d 
						  AND id_unik_indikator IS NOT NULL 
						  AND id_unik_indikator != '';
					", $id_sasaran),
					ARRAY_A
				);

				$opsi = '<option value="">Pilih Indikator Sasaran</option>';
				if (!empty($indikator_sasaran_all)) {
					foreach ($indikator_sasaran_all as $indikator_sasaran) {
						$opsi .= '<option value="' . $indikator_sasaran['id_unik_indikator'] . '">' . $indikator_sasaran['indikator_teks'] . '</option>';
					}
				}
				$ret['data'] = $opsi;
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message'   => 'Format tidak sesuai!'
			);
		}
		die(json_encode($ret));
	}


	public function generateRandomString($length = 10)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		$randomString = time() . '-' . $randomString;
		return $randomString;
	}

	public function get_table_pohon_kinerja()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				$where_skpd = "";
				if ($_POST['tipe_dokumen'] == 'pohon_kinerja_dan_cascading') {
					if (!empty($_POST['id_skpd'])) {
						$id_skpd = $_POST['id_skpd'];
						$where_skpd = " AND id_skpd=" . $id_skpd;
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Id Perangkat Daerah kosong!';
					}
				}
				if (!empty($_POST['id_periode'])) {
					$id_jadwal = $_POST['id_periode'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
				}
				if (!empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
					$_prefix_tipe = $tipe_dokumen == "pohon_kinerja_dan_cascading" ? "" : "_pemda";
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe dokumen kosong!';
				}

				$data_pohon_kinerja = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT * 
                    FROM esakip_pohon_kinerja_dan_cascading$_prefix_tipe
                    WHERE id_jadwal = %d 
                      AND active = 1 $where_skpd
                ", $id_jadwal),
					ARRAY_A
				);

				if (!empty($data_pohon_kinerja)) {
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

					foreach ($data_pohon_kinerja as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						if ($_prefix_tipe == "") {
							$tbody .= "<td>" . $vv['opd'] . "</td>";
						}
						$tbody .= "<td>" . $vv['dokumen'] . "</td>";
						$tbody .= "<td>" . $vv['keterangan'] . "</td>";
						$tbody .= "<td>" . $vv['created_at'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="lihatDokumen(\'' . $vv['dokumen'] . '\'); return false;" href="#" title="Lihat Dokumen"><span class="dashicons dashicons-visibility"></span></button>';
						// if($can_verify){
						// 	$btn .= '<button class="btn btn-sm btn-success" onclick="verifikasi_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Verifikasi Dokumen"><span class="dashicons dashicons-yes"></span></button>';
						// }
						if ($_prefix_tipe == "_pemda") {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_pohon_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_pohon_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
						} else if (!$this->is_admin_panrb() && $this->hak_akses_upload_dokumen('Pohon Kinerja dan Cascading', $id_jadwal)) {
							$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_dokumen_pohon_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit Dokumen"><span class="dashicons dashicons-edit"></span></button>';
							$btn .= '<button class="btn btn-sm btn-danger" onclick="hapus_dokumen_pohon_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function get_table_tahun_pohon_kinerja()
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
				if (!empty($_POST['id_periode'])) {
					$id_jadwal = $_POST['id_periode'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
				}

				$dokumen_unset = $wpdb->get_results(
					"
					SELECT 
						*
					FROM esakip_pohon_kinerja_dan_cascading 
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
						if (
							!$this->is_admin_panrb()
							&& $this->hak_akses_upload_dokumen('Pohon Kinerja dan Cascading', $id_jadwal)
						) {
							$btn .= "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . "); return false;' title='Set Tahun Dokumen'><span class='dashicons dashicons-insert'></span></button>";

							$btn .= '<button class="btn btn-danger" onclick="hapus_dokumen_pohon_kinerja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Dokumen"><span class="dashicons dashicons-trash"></span></button>';
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

	public function tambah_dokumen_pohon_kinerja()
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
				$where_skpd = "";
				$idSkpd = "";
				if ($_POST['tipe_dokumen'] == 'pohon_kinerja_dan_cascading') {
					if (!empty($_POST['idSkpd'])) {
						$idSkpd = $_POST['idSkpd'];
						$where_skpd = " AND id_skpd=" . $idSkpd;
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Id Perangkat Daerah kosong!';
					}
				}
				$skpd = "";
				if ($_POST['tipe_dokumen'] == 'pohon_kinerja_dan_cascading') {
					if (!empty($_POST['skpd'])) {
						$skpd = $_POST['skpd'];
					} else {
						$ret['status'] = 'error';
						$ret['message'] = 'Perangkat Daerah kosong!';
					}
				}
				if (!empty($_POST['keterangan'])) {
					$keterangan = $_POST['keterangan'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Keterangan kosong!';
				}
				if (!empty($_POST['id_periode'])) {
					$id_jadwal = $_POST['id_periode'];
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Id Jadwal kosong!';
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
				if (!empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
					$_prefix_tipe = $tipe_dokumen == "pohon_kinerja_dan_cascading" ? "" : "_pemda";
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe dokumen kosong!';
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
						FROM esakip_pohon_kinerja_dan_cascading$_prefix_tipe
						WHERE id=%d
					", $id_dokumen));
					if ($dokumen_lama != $_POST['namaDokumen']) {
						$ret_rename = $this->functions->renameFile($upload_dir . $dokumen_lama, $upload_dir . $_POST['namaDokumen']);
						if ($ret_rename['status'] != 'error') {
							$wpdb->update(
								'esakip_pohon_kinerja_dan_cascading' . $_prefix_tipe,
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
						if ($_prefix_tipe == "") {
							$wpdb->insert(
								'esakip_pohon_kinerja_dan_cascading',
								array(
									'opd' => $skpd,
									'id_skpd' => $idSkpd,
									'dokumen' => $upload['filename'],
									'keterangan' => $keterangan,
									'id_jadwal' => $id_jadwal,
									'created_at' => current_time('mysql'),
									'tanggal_upload' => current_time('mysql')
								),
								array('%s', '%d', '%s', '%s', '%d', '%s', '%s')
							);
						} else {
							$wpdb->insert(
								'esakip_pohon_kinerja_dan_cascading_pemda',
								array(
									'dokumen' => $upload['filename'],
									'keterangan' => $keterangan,
									'id_jadwal' => $id_jadwal,
									'created_at' => current_time('mysql'),
									'tanggal_upload' => current_time('mysql')
								),
								array('%s', '%s', '%d', '%s', '%s')
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
								FROM esakip_pohon_kinerja_dan_cascading$_prefix_tipe
								WHERE id=%d
							", $id_dokumen));
							if (is_file($upload_dir . $dokumen_lama)) {
								unlink($upload_dir . $dokumen_lama);
							}
						}
						$wpdb->update(
							'esakip_pohon_kinerja_dan_cascading' . $_prefix_tipe,
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

	public function get_detail_pohon_kinerja_by_id()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['tipe_dokumen'])) {
					$tipe_dokumen = $_POST['tipe_dokumen'];
					$_prefix_tipe = $tipe_dokumen == "pohon_kinerja_dan_cascading" ? "" : "_pemda";
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Tipe dokumen kosong!';
				}

				if (!empty($_POST['id'])) {
					$data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT *
							FROM esakip_pohon_kinerja_dan_cascading$_prefix_tipe
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

	public function hapus_dokumen_pohon_kinerja()
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
							FROM esakip_pohon_kinerja_dan_cascading
							WHERE id=%d
						", $_POST['id'])
					);

					$ret['data'] = $wpdb->update(
						'esakip_pohon_kinerja_dan_cascading',
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

	public function get_table_capaian_indikator()
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
                die(json_encode($ret));
            }

            $get_data = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT 
                        * 
                    FROM esakip_capaian_indikator
                    WHERE id_jadwal = %d 
                      AND active = 1
                ", $id_jadwal),
                ARRAY_A
            );

            if (!empty($get_data)) {
                $counter = 1;
                $tbody = '';

                foreach ($get_data as $kk => $vv) {
                    $tbody .= "<tr>";
                    $tbody .= "<td class='text-center'>" . $counter++ . "</td>";
                    $tbody .= "<td class='text-left'>" . ($vv['indikator_kinerja'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['satuan'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['kondisi_awal'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['target_akhir_p_rpjmd'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['target_bps_tahun_1'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['bps_tahun_1'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['target_bps_tahun_2'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['bps_tahun_2'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['target_bps_tahun_3'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['bps_tahun_3'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['target_bps_tahun_4'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['bps_tahun_4'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['target_bps_tahun_5'] ?: '-') . "</td>";
                    $tbody .= "<td>" . ($vv['bps_tahun_5'] ?: '-') . "</td>";
                    $tbody .= "<td class='text-left'>" . ($vv['sumber_data'] ?: '-') . "</td>";
                    $tbody .= "<td class='text-left'>" . ($vv['keterangan'] ?: '-') . "</td>";
                    $tbody .= "</tr>";
                }

                $ret['data'] = $tbody;
            } else {
                $colspan = 0;
                if (count($get_data) > 0) {
                    $colspan = count(array_keys($get_data[0]));
                } else {
                    $colspan = 13; 
                }
                $ret['data'] = "<tr><td colspan=".$colspan." class='text-center'>Tidak ada data tersedia</td></tr>";
            }
        } else {
            $ret = array(
                'status' => 'error',
                'message'   => 'Api Key tidak sesuai!'
            );
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
