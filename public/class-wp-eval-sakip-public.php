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
class Wp_Eval_Sakip_Public {

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
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-eval-sakip-public.css', array(), $this->version, 'all' );
		wp_enqueue_style($this->plugin_name . 'bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . 'datatables', plugin_dir_url(__FILE__) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-eval-sakip-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script($this->plugin_name . 'bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . 'datatables', plugin_dir_url(__FILE__) . 'js/jquery.dataTables.min.js', array('jquery'), $this->version, false);
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

	public function perjanjian_kinerja()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-perjanjian-kinerja.php';
	}

	public function rencana_aksi()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-rencana-aksi.php';
	}

	public function iku()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-iku.php';
	}

	public function skp()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-skp.php';
	}

	public function pengukuran_kinerja()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-pengukuran-kinerja.php';
	}

	public function pengukuran_rencana_aksi()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-pengukuran-rencana-aksi.php';
	}

	public function laporan_kinerja()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-laporan-kinerja.php';
	}

	public function evaluasi_internal()
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/wp-eval-sakip-evaluasi-internal.php';
	}

	public function dokumen_lainnya()
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
}
