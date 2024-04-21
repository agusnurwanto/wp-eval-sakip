<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/agusnurwanto/
 * @since      1.0.0
 *
 * @package    Wp_Eval_Sakip
 * @subpackage Wp_Eval_Sakip/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Eval_Sakip
 * @subpackage Wp_Eval_Sakip/includes
 * @author     Agus Nurwanto <agusnurwantomuslim@gmail.com>
 */
class Wp_Eval_Sakip {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Eval_Sakip_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_EVAL_SAKIP_VERSION' ) ) {
			$this->version = WP_EVAL_SAKIP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-eval-sakip';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Eval_Sakip_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Eval_Sakip_i18n. Defines internationalization functionality.
	 * - Wp_Eval_Sakip_Admin. Defines all hooks for the admin area.
	 * - Wp_Eval_Sakip_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-eval-sakip-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-eval-sakip-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-eval-sakip-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-eval-sakip-public.php';

		$this->loader = new Wp_Eval_Sakip_Loader();

		// Functions tambahan
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wp-eval-sakip-functions.php';

		$this->functions = new Esakip_Functions( $this->plugin_name, $this->version );

		$this->loader->add_action('template_redirect', $this->functions, 'allow_access_private_post', 0);

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Eval_Sakip_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Eval_Sakip_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Eval_Sakip_Admin( $this->get_plugin_name(), $this->get_version(), $this->functions );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action('wp_ajax_sql_migrate_esakip', $plugin_admin, 'sql_migrate_esakip');
		$this->loader->add_action('wp_ajax_generate_user_sipd_merah', $plugin_admin, 'generate_user_sipd_merah');
		$this->loader->add_action('wp_ajax_generate_user_esakip', $plugin_admin, 'generate_user_esakip');
		$this->loader->add_action('wp_ajax_get_data_unit', $plugin_admin, 'get_data_unit');
		$this->loader->add_action('wp_ajax_get_data_unit_wpsipd', $plugin_admin, 'get_data_unit_wpsipd');
		$this->loader->add_action('wp_ajax_esakip_load_ajax_carbon', $plugin_admin, 'esakip_load_ajax_carbon');
		
		$this->loader->add_action('carbon_fields_register_fields', $plugin_admin, 'crb_attach_esakip_options');
		$this->loader->add_action('template_redirect', $plugin_admin, 'allow_access_private_post', 0);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Eval_Sakip_Public( $this->get_plugin_name(), $this->get_version(), $this->functions );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_mapping_skpd', $plugin_public, 'mapping_skpd' );

		$this->loader->add_action('wp_ajax_get_data_penjadwalan', $plugin_public, 'get_data_penjadwalan');
		$this->loader->add_action('wp_ajax_submit_jadwal',  $plugin_public, 'submit_jadwal');
		$this->loader->add_action('wp_ajax_submit_edit_jadwal',  $plugin_public, 'submit_edit_jadwal');
		$this->loader->add_action('wp_ajax_delete_jadwal',  $plugin_public, 'delete_jadwal');
		$this->loader->add_action('wp_ajax_lock_jadwal',  $plugin_public, 'lock_jadwal');
		$this->loader->add_action('wp_ajax_get_data_jadwal_by_id',  $plugin_public, 'get_data_jadwal_by_id');

		$this->loader->add_action('wp_ajax_get_data_penjadwalan_rpjmd', $plugin_public, 'get_data_penjadwalan_rpjmd');
		$this->loader->add_action('wp_ajax_get_lama_pelaksanaan_rpjmd', $plugin_public, 'get_lama_pelaksanaan_rpjmd');
		$this->loader->add_action('wp_ajax_submit_jadwal_rpjmd',  $plugin_public, 'submit_jadwal_rpjmd');
		$this->loader->add_action('wp_ajax_delete_jadwal_rpjmd',  $plugin_public, 'delete_jadwal_rpjmd');
		$this->loader->add_action('wp_ajax_submit_edit_jadwal_rpjmd',  $plugin_public, 'submit_edit_jadwal_rpjmd');

		$this->loader->add_action('wp_ajax_get_detail_renja_rkt_by_id', $plugin_public, 'get_detail_renja_rkt_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_renja', $plugin_public, 'tambah_dokumen_renja');
		$this->loader->add_action('wp_ajax_submit_tahun_renja_rkt', $plugin_public, 'submit_tahun_renja_rkt');
		$this->loader->add_action('wp_ajax_hapus_dokumen_renja', $plugin_public, 'hapus_dokumen_renja');
		$this->loader->add_action('wp_ajax_get_table_renja', $plugin_public, 'get_table_renja');
		
		$this->loader->add_action('wp_ajax_get_detail_skp_by_id', $plugin_public, 'get_detail_skp_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_skp', $plugin_public, 'tambah_dokumen_skp');
		$this->loader->add_action('wp_ajax_submit_tahun_skp', $plugin_public, 'submit_tahun_skp');
		$this->loader->add_action('wp_ajax_hapus_dokumen_skp', $plugin_public, 'hapus_dokumen_skp');
		$this->loader->add_action('wp_ajax_get_table_skp', $plugin_public, 'get_table_skp');
		
		$this->loader->add_action('wp_ajax_get_detail_perjanjian_kinerja_by_id', $plugin_public, 'get_detail_perjanjian_kinerja_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_perjanjian_kinerja', $plugin_public, 'tambah_dokumen_perjanjian_kinerja');
		$this->loader->add_action('wp_ajax_submit_tahun_perjanjian_kinerja', $plugin_public, 'submit_tahun_perjanjian_kinerja');
		$this->loader->add_action('wp_ajax_hapus_dokumen_perjanjian_kinerja', $plugin_public, 'hapus_dokumen_perjanjian_kinerja');
		$this->loader->add_action('wp_ajax_get_table_perjanjian_kinerja', $plugin_public, 'get_table_perjanjian_kinerja');
		
		$this->loader->add_action('wp_ajax_get_detail_rencana_aksi_by_id', $plugin_public, 'get_detail_rencana_aksi_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_rencana_aksi', $plugin_public, 'tambah_dokumen_rencana_aksi');
		$this->loader->add_action('wp_ajax_submit_tahun_rencana_aksi', $plugin_public, 'submit_tahun_rencana_aksi');
		$this->loader->add_action('wp_ajax_hapus_dokumen_rencana_aksi', $plugin_public, 'hapus_dokumen_rencana_aksi');
		$this->loader->add_action('wp_ajax_get_table_rencana_aksi', $plugin_public, 'get_table_rencana_aksi');
		
		$this->loader->add_action('wp_ajax_get_detail_pengukuran_kinerja_by_id', $plugin_public, 'get_detail_pengukuran_kinerja_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_pengukuran_kinerja', $plugin_public, 'tambah_dokumen_pengukuran_kinerja');
		$this->loader->add_action('wp_ajax_submit_tahun_pengukuran_kinerja', $plugin_public, 'submit_tahun_pengukuran_kinerja');
		$this->loader->add_action('wp_ajax_hapus_dokumen_pengukuran_kinerja', $plugin_public, 'hapus_dokumen_pengukuran_kinerja');
		$this->loader->add_action('wp_ajax_get_table_pengukuran_kinerja', $plugin_public, 'get_table_pengukuran_kinerja');

		$this->loader->add_action('wp_ajax_get_detail_pengukuran_rencana_aksi_by_id', $plugin_public, 'get_detail_pengukuran_rencana_aksi_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_pengukuran_rencana_aksi', $plugin_public, 'tambah_dokumen_pengukuran_rencana_aksi');
		$this->loader->add_action('wp_ajax_submit_tahun_pengukuran_rencana_aksi', $plugin_public, 'submit_tahun_pengukuran_rencana_aksi');
		$this->loader->add_action('wp_ajax_hapus_dokumen_pengukuran_rencana_aksi', $plugin_public, 'hapus_dokumen_pengukuran_rencana_aksi');
		$this->loader->add_action('wp_ajax_get_table_pengukuran_rencana_aksi', $plugin_public, 'get_table_pengukuran_rencana_aksi');
		
		$this->loader->add_action('wp_ajax_get_detail_iku_by_id', $plugin_public, 'get_detail_iku_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_iku', $plugin_public, 'tambah_dokumen_iku');
		$this->loader->add_action('wp_ajax_submit_tahun_iku', $plugin_public, 'submit_tahun_iku');
		$this->loader->add_action('wp_ajax_hapus_dokumen_iku', $plugin_public, 'hapus_dokumen_iku');
		$this->loader->add_action('wp_ajax_get_table_iku', $plugin_public, 'get_table_iku');
		
		$this->loader->add_action('wp_ajax_get_detail_laporan_kinerja_by_id', $plugin_public, 'get_detail_laporan_kinerja_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_laporan_kinerja', $plugin_public, 'tambah_dokumen_laporan_kinerja');
		$this->loader->add_action('wp_ajax_submit_tahun_laporan_kinerja', $plugin_public, 'submit_tahun_laporan_kinerja');
		$this->loader->add_action('wp_ajax_hapus_dokumen_laporan_kinerja', $plugin_public, 'hapus_dokumen_laporan_kinerja');
		$this->loader->add_action('wp_ajax_get_table_laporan_kinerja', $plugin_public, 'get_table_laporan_kinerja');
		
		$this->loader->add_action('wp_ajax_get_detail_dokumen_lain_by_id', $plugin_public, 'get_detail_dokumen_lain_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_lain', $plugin_public, 'tambah_dokumen_lain');
		$this->loader->add_action('wp_ajax_submit_tahun_dokumen_lain', $plugin_public, 'submit_tahun_dokumen_lain');
		$this->loader->add_action('wp_ajax_hapus_dokumen_lain', $plugin_public, 'hapus_dokumen_lain');
		$this->loader->add_action('wp_ajax_get_table_dokumen_lain', $plugin_public, 'get_table_dokumen_lain');
		
		$this->loader->add_action('wp_ajax_get_detail_evaluasi_internal_by_id', $plugin_public, 'get_detail_evaluasi_internal_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_evaluasi_internal', $plugin_public, 'tambah_dokumen_evaluasi_internal');
		$this->loader->add_action('wp_ajax_submit_tahun_evaluasi_internal', $plugin_public, 'submit_tahun_evaluasi_internal');
		$this->loader->add_action('wp_ajax_hapus_dokumen_evaluasi_internal', $plugin_public, 'hapus_dokumen_evaluasi_internal');
		$this->loader->add_action('wp_ajax_get_table_evaluasi_internal', $plugin_public, 'get_table_evaluasi_internal');
		
		$this->loader->add_action('wp_ajax_get_detail_renstra_by_id', $plugin_public, 'get_detail_renstra_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_renstra', $plugin_public, 'tambah_dokumen_renstra');
		$this->loader->add_action('wp_ajax_hapus_dokumen_renstra', $plugin_public, 'hapus_dokumen_renstra');
		$this->loader->add_action('wp_ajax_submit_tahun_renstra', $plugin_public, 'submit_tahun_renstra');
		$this->loader->add_action('wp_ajax_get_table_renstra', $plugin_public, 'get_table_renstra');
 
		$this->loader->add_action('wp_ajax_get_detail_rkpd_by_id', $plugin_public, 'get_detail_rkpd_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_rkpd', $plugin_public, 'tambah_dokumen_rkpd');
		$this->loader->add_action('wp_ajax_hapus_dokumen_rkpd', $plugin_public, 'hapus_dokumen_rkpd');
		$this->loader->add_action('wp_ajax_get_table_rkpd', $plugin_public, 'get_table_rkpd');
		$this->loader->add_action('wp_ajax_get_table_tahun_dokumen_pemda_lain', $plugin_public, 'get_table_tahun_dokumen_pemda_lain');
		$this->loader->add_action('wp_ajax_submit_tahun_dokumen_pemda_lain', $plugin_public, 'submit_tahun_dokumen_pemda_lain');
 
		$this->loader->add_action('wp_ajax_get_detail_rpjmd_by_id', $plugin_public, 'get_detail_rpjmd_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_rpjmd', $plugin_public, 'tambah_dokumen_rpjmd');
		$this->loader->add_action('wp_ajax_hapus_dokumen_rpjmd', $plugin_public, 'hapus_dokumen_rpjmd');
		$this->loader->add_action('wp_ajax_get_table_rpjmd', $plugin_public, 'get_table_rpjmd');
 
		$this->loader->add_action('wp_ajax_get_detail_lkjip_lppd_by_id', $plugin_public, 'get_detail_lkjip_lppd_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_lkjip_lppd', $plugin_public, 'tambah_dokumen_lkjip_lppd');
		$this->loader->add_action('wp_ajax_hapus_dokumen_lkjip_lppd', $plugin_public, 'hapus_dokumen_lkjip_lppd');
		$this->loader->add_action('wp_ajax_get_table_lkjip_lppd', $plugin_public, 'get_table_lkjip_lppd');
		$this->loader->add_action('wp_ajax_get_table_tahun_lkjip_lppd', $plugin_public, 'get_table_tahun_lkjip_lppd');
		$this->loader->add_action('wp_ajax_submit_tahun_lkjip_lppd', $plugin_public, 'submit_tahun_lkjip_lppd');
 
		$this->loader->add_action('wp_ajax_get_detail_dokumen_pemda_lain_by_id', $plugin_public, 'get_detail_dokumen_pemda_lain_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_pemda_lain', $plugin_public, 'tambah_dokumen_pemda_lain');
		$this->loader->add_action('wp_ajax_hapus_dokumen_pemda_lain', $plugin_public, 'hapus_dokumen_pemda_lain');
		$this->loader->add_action('wp_ajax_get_table_dokumen_pemda_lain', $plugin_public, 'get_table_dokumen_pemda_lain');
		$this->loader->add_action('wp_ajax_get_table_tahun_dokumen_pemda_lain', $plugin_public, 'get_table_tahun_dokumen_pemda_lain');
		$this->loader->add_action('wp_ajax_submit_tahun_dokumen_pemda_lain', $plugin_public, 'submit_tahun_dokumen_pemda_lain');
		
		$this->loader->add_action('wp_ajax_get_table_tahun_renja', $plugin_public, 'get_table_tahun_renja');
		$this->loader->add_action('wp_ajax_get_table_skpd_renja', $plugin_public, 'get_table_skpd_renja');
		$this->loader->add_action('wp_ajax_get_table_tahun_dokumen_lainnya', $plugin_public, 'get_table_tahun_dokumen_lainnya');
		$this->loader->add_action('wp_ajax_get_table_skpd_dokumen_lainnya', $plugin_public, 'get_table_skpd_dokumen_lainnya');
		$this->loader->add_action('wp_ajax_get_table_tahun_skp', $plugin_public, 'get_table_tahun_skp');
		$this->loader->add_action('wp_ajax_get_table_skpd_skp', $plugin_public, 'get_table_skpd_skp');
		$this->loader->add_action('wp_ajax_get_table_tahun_perjanjian_kinerja', $plugin_public, 'get_table_tahun_perjanjian_kinerja');
		$this->loader->add_action('wp_ajax_get_table_skpd_perjanjian_kinerja', $plugin_public, 'get_table_skpd_perjanjian_kinerja');
		$this->loader->add_action('wp_ajax_get_table_tahun_rencana_aksi', $plugin_public, 'get_table_tahun_rencana_aksi');
		$this->loader->add_action('wp_ajax_get_table_skpd_rencana_aksi', $plugin_public, 'get_table_skpd_rencana_aksi');
		$this->loader->add_action('wp_ajax_get_table_tahun_pengukuran_kinerja', $plugin_public, 'get_table_tahun_pengukuran_kinerja');
		$this->loader->add_action('wp_ajax_get_table_skpd_pengukuran_kinerja', $plugin_public, 'get_table_skpd_pengukuran_kinerja');
		$this->loader->add_action('wp_ajax_get_table_tahun_pengukuran_rencana_aksi', $plugin_public, 'get_table_tahun_pengukuran_rencana_aksi');
		$this->loader->add_action('wp_ajax_get_table_skpd_pengukuran_rencana_aksi', $plugin_public, 'get_table_skpd_pengukuran_rencana_aksi');
		$this->loader->add_action('wp_ajax_get_table_tahun_iku', $plugin_public, 'get_table_tahun_iku');
		$this->loader->add_action('wp_ajax_get_table_skpd_iku', $plugin_public, 'get_table_skpd_iku');
		$this->loader->add_action('wp_ajax_get_table_tahun_evaluasi_internal', $plugin_public, 'get_table_tahun_evaluasi_internal');
		$this->loader->add_action('wp_ajax_get_table_skpd_evaluasi_internal', $plugin_public, 'get_table_skpd_evaluasi_internal');
		$this->loader->add_action('wp_ajax_get_table_tahun_laporan_kinerja', $plugin_public, 'get_table_tahun_laporan_kinerja');
		$this->loader->add_action('wp_ajax_get_table_skpd_laporan_kinerja', $plugin_public, 'get_table_skpd_laporan_kinerja');
		$this->loader->add_action('wp_ajax_get_table_tahun_rkpd', $plugin_public, 'get_table_tahun_rkpd');
		$this->loader->add_action('wp_ajax_get_table_skpd_rkpd', $plugin_public, 'get_table_skpd_rkpd');
		$this->loader->add_action('wp_ajax_get_table_tahun_lkjip', $plugin_public, 'get_table_tahun_lkjip');
		$this->loader->add_action('wp_ajax_get_table_skpd_lkjip', $plugin_public, 'get_table_skpd_lkjip');

		$this->loader->add_action('wp_ajax_get_table_tahun_renstra', $plugin_public, 'get_table_tahun_renstra');
		$this->loader->add_action('wp_ajax_get_table_skpd_renstra', $plugin_public, 'get_table_skpd_renstra');
		$this->loader->add_action('wp_ajax_get_table_tahun_rpjmd', $plugin_public, 'get_table_tahun_rpjmd');
		$this->loader->add_action('wp_ajax_submit_tahun_rpjmd', $plugin_public, 'submit_tahun_rpjmd');
		
		$this->loader->add_action('wp_ajax_get_table_desain_lke', $plugin_public, 'get_table_desain_lke');
		$this->loader->add_action('wp_ajax_get_table_skpd_pengisian_lke', $plugin_public, 'get_table_skpd_pengisian_lke');
		$this->loader->add_action('wp_ajax_get_table_pengisian_lke', $plugin_public, 'get_table_pengisian_lke');
		
		add_shortcode('jadwal_evaluasi_sakip', array($plugin_public, 'jadwal_evaluasi_sakip'));
		add_shortcode('halaman_mapping_skpd', array($plugin_public, 'halaman_mapping_skpd'));
		
		add_shortcode('upload_dokumen_renstra', array($plugin_public, 'upload_dokumen_renstra'));
		add_shortcode('upload_dokumen_rpjmd', array($plugin_public, 'upload_dokumen_rpjmd'));
		
		add_shortcode('renstra', array($plugin_public, 'renstra'));
		add_shortcode('renja_rkt', array($plugin_public, 'renja_rkt'));
		add_shortcode('perjanjian_kinerja', array($plugin_public, 'perjanjian_kinerja'));
		add_shortcode('rencana_aksi', array($plugin_public, 'rencana_aksi'));
		add_shortcode('iku', array($plugin_public, 'iku'));
		add_shortcode('skp', array($plugin_public, 'skp'));
		add_shortcode('pengukuran_kinerja', array($plugin_public, 'pengukuran_kinerja'));
		add_shortcode('pengukuran_rencana_aksi', array($plugin_public, 'pengukuran_rencana_aksi'));
		add_shortcode('laporan_kinerja', array($plugin_public, 'laporan_kinerja'));
		add_shortcode('evaluasi_internal', array($plugin_public, 'evaluasi_internal'));
		add_shortcode('dokumen_lainnya', array($plugin_public, 'dokumen_lainnya'));

		add_shortcode('rpjmd', array($plugin_public, 'rpjmd'));
		add_shortcode('rkpd', array($plugin_public, 'rkpd'));
		add_shortcode('lkjip_lppd', array($plugin_public, 'lkjip_lppd'));
		add_shortcode('dokumen_pemda_lainnya', array($plugin_public, 'dokumen_pemda_lainnya'));

		add_shortcode('jadwal_rpjmd', array($plugin_public, 'jadwal_rpjmd'));

		add_shortcode('dokumen_detail_renja_rkt', array($plugin_public, 'dokumen_detail_renja_rkt'));
		add_shortcode('dokumen_detail_skp', array($plugin_public, 'dokumen_detail_skp'));
		add_shortcode('dokumen_detail_perjanjian_kinerja', array($plugin_public, 'dokumen_detail_perjanjian_kinerja'));
		add_shortcode('dokumen_detail_rencana_aksi', array($plugin_public, 'dokumen_detail_rencana_aksi'));
		add_shortcode('dokumen_detail_pengukuran_kinerja', array($plugin_public, 'dokumen_detail_pengukuran_kinerja'));
		add_shortcode('dokumen_detail_pengukuran_rencana_aksi', array($plugin_public, 'dokumen_detail_pengukuran_rencana_aksi'));
		add_shortcode('dokumen_detail_dokumen_lainnya', array($plugin_public, 'dokumen_detail_dokumen_lainnya'));
		add_shortcode('dokumen_detail_evaluasi_internal', array($plugin_public, 'dokumen_detail_evaluasi_internal'));
		add_shortcode('dokumen_detail_iku', array($plugin_public, 'dokumen_detail_iku'));
		add_shortcode('dokumen_detail_laporan_kinerja', array($plugin_public, 'dokumen_detail_laporan_kinerja'));
		add_shortcode('dokumen_detail_laporan_kinerja', array($plugin_public, 'dokumen_detail_laporan_kinerja'));
		
		add_shortcode('desain_lke_sakip', array($plugin_public, 'desain_lke_sakip'));
		add_shortcode('pengisian_lke_sakip', array($plugin_public, 'pengisian_lke_sakip'));
		add_shortcode('pengisian_lke_sakip_per_skpd', array($plugin_public, 'pengisian_lke_sakip_per_skpd'));

		add_shortcode('menu_eval_sakip', array($plugin_public, 'menu_eval_sakip'));
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Eval_Sakip_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
