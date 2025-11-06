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
	protected $functions;

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
		$this->loader->add_action('wp_ajax_generate_user_esakip_pegawai_simpeg', $plugin_admin, 'generate_user_esakip_pegawai_simpeg');
		$this->loader->add_action('wp_ajax_get_data_total_pegawai_simpeg', $plugin_admin, 'get_data_total_pegawai_simpeg');
		$this->loader->add_action('wp_ajax_coba_auto_login',  $plugin_admin, 'coba_auto_login');
		$this->loader->add_action('wp_ajax_handle_sql_migrate_ajax',  $plugin_admin, 'handle_sql_migrate_ajax');
		
		$this->loader->add_action('carbon_fields_register_fields', $plugin_admin, 'crb_attach_esakip_options');
		$this->loader->add_action('template_redirect', $plugin_admin, 'allow_access_private_post', 0);
		$this->loader->add_action('template_redirect', $plugin_admin, 'handle_sso_login', 0);
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

		$this->loader->add_filter('upload_mimes', $plugin_public, 'batasi_upload_gambar');

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_mapping_skpd', $plugin_public, 'mapping_skpd' );

		$this->loader->add_action('wp_ajax_get_data_penjadwalan_lke', $plugin_public, 'get_data_penjadwalan_lke');
		$this->loader->add_action('wp_ajax_submit_jadwal_lke',  $plugin_public, 'submit_jadwal_lke');
		$this->loader->add_action('wp_ajax_submit_edit_jadwal_lke',  $plugin_public, 'submit_edit_jadwal_lke');
		$this->loader->add_action('wp_ajax_delete_jadwal_lke',  $plugin_public, 'delete_jadwal_lke');
		$this->loader->add_action('wp_ajax_lock_jadwal_lke',  $plugin_public, 'lock_jadwal_lke');
		$this->loader->add_action('wp_ajax_get_data_jadwal_lke',  $plugin_public, 'get_data_jadwal_lke');

		$this->loader->add_action('wp_ajax_get_data_penjadwalan_rpjmd', $plugin_public, 'get_data_penjadwalan_rpjmd');
		$this->loader->add_action('wp_ajax_get_lama_pelaksanaan_rpjmd', $plugin_public, 'get_lama_pelaksanaan_rpjmd');
		$this->loader->add_action('wp_ajax_submit_jadwal_rpjmd',  $plugin_public, 'submit_jadwal_rpjmd');
		$this->loader->add_action('wp_ajax_delete_jadwal_rpjmd',  $plugin_public, 'delete_jadwal_rpjmd');
		$this->loader->add_action('wp_ajax_submit_edit_jadwal_rpjmd',  $plugin_public, 'submit_edit_jadwal_rpjmd');
		$this->loader->add_action('wp_ajax_get_data_jadwal_by_id_rpjmd', $plugin_public, 'get_data_jadwal_by_id_rpjmd');
		$this->loader->add_action('wp_ajax_get_data_visi_misi', $plugin_public, 'get_data_visi_misi');
		$this->loader->add_action('wp_ajax_tambah_visi_rpjmd', $plugin_public, 'tambah_visi_rpjmd');
		$this->loader->add_action('wp_ajax_get_visi_rpjmd', $plugin_public, 'get_visi_rpjmd');
		$this->loader->add_action('wp_ajax_hapus_visi_rpjmd', $plugin_public, 'hapus_visi_rpjmd');
		$this->loader->add_action('wp_ajax_get_misi_rpjmd', $plugin_public, 'get_misi_rpjmd');
		$this->loader->add_action('wp_ajax_tambah_misi_rpjmd', $plugin_public, 'tambah_misi_rpjmd');
		$this->loader->add_action('wp_ajax_hapus_misi_rpjmd', $plugin_public, 'hapus_misi_rpjmd');
		$this->loader->add_action('wp_ajax_esakip_get_rpjmd', $plugin_public, 'esakip_get_rpjmd');

		$this->loader->add_action('wp_ajax_get_data_penjadwalan_rpjpd', $plugin_public, 'get_data_penjadwalan_rpjpd');
		$this->loader->add_action('wp_ajax_submit_jadwal_rpjpd',  $plugin_public, 'submit_jadwal_rpjpd');
		$this->loader->add_action('wp_ajax_delete_jadwal_rpjpd',  $plugin_public, 'delete_jadwal_rpjpd');
		$this->loader->add_action('wp_ajax_submit_edit_jadwal_rpjpd',  $plugin_public, 'submit_edit_jadwal_rpjpd');
		$this->loader->add_action('wp_ajax_get_data_jadwal_by_id_rpjpd', $plugin_public, 'get_data_jadwal_by_id_rpjpd');
		
		$this->loader->add_action('wp_ajax_get_data_penjadwalan_verifikasi_upload_dokumen', $plugin_public, 'get_data_penjadwalan_verifikasi_upload_dokumen');
		$this->loader->add_action('wp_ajax_submit_jadwal_verifikasi_upload_dokumen',  $plugin_public, 'submit_jadwal_verifikasi_upload_dokumen');
		$this->loader->add_action('wp_ajax_submit_edit_jadwal_verifikasi_upload_dokumen',  $plugin_public, 'submit_edit_jadwal_verifikasi_upload_dokumen');
		$this->loader->add_action('wp_ajax_delete_jadwal_verifikasi_upload_dokumen',  $plugin_public, 'delete_jadwal_verifikasi_upload_dokumen');
		$this->loader->add_action('wp_ajax_lock_jadwal_verifikasi_upload_dokumen',  $plugin_public, 'lock_jadwal_verifikasi_upload_dokumen');
		$this->loader->add_action('wp_ajax_get_data_jadwal_by_id_verifikasi_upload_dokumen',  $plugin_public, 'get_data_jadwal_by_id_verifikasi_upload_dokumen');

		$this->loader->add_action('wp_ajax_get_data_penjadwalan_verifikasi_upload_dokumen_renstra', $plugin_public, 'get_data_penjadwalan_verifikasi_upload_dokumen_renstra');
		$this->loader->add_action('wp_ajax_submit_jadwal_verifikasi_upload_dokumen_renstra',  $plugin_public, 'submit_jadwal_verifikasi_upload_dokumen_renstra');
		$this->loader->add_action('wp_ajax_submit_edit_jadwal_verifikasi_upload_dokumen_renstra',  $plugin_public, 'submit_edit_jadwal_verifikasi_upload_dokumen_renstra');
		$this->loader->add_action('wp_ajax_delete_jadwal_verifikasi_upload_dokumen_renstra',  $plugin_public, 'delete_jadwal_verifikasi_upload_dokumen_renstra');
		$this->loader->add_action('wp_ajax_lock_jadwal_verifikasi_upload_dokumen_renstra',  $plugin_public, 'lock_jadwal_verifikasi_upload_dokumen_renstra');
		$this->loader->add_action('wp_ajax_get_data_jadwal_by_id_verifikasi_upload_dokumen_renstra',  $plugin_public, 'get_data_jadwal_by_id_verifikasi_upload_dokumen_renstra');
		
		$this->loader->add_action('wp_ajax_nopriv_get_data_jadwal_by_type_ajax',  $plugin_public, 'get_data_jadwal_by_type_ajax');

		$this->loader->add_action('wp_ajax_get_detail_dpa_by_id', $plugin_public, 'get_detail_dpa_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_dpa', $plugin_public, 'tambah_dokumen_dpa');
		$this->loader->add_action('wp_ajax_submit_tahun_dpa', $plugin_public, 'submit_tahun_dpa');
		$this->loader->add_action('wp_ajax_hapus_dokumen_dpa', $plugin_public, 'hapus_dokumen_dpa');
		$this->loader->add_action('wp_ajax_get_table_dpa', $plugin_public, 'get_table_dpa');


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
		$this->loader->add_action('wp_ajax_unggah_draft_dokumen', $plugin_public, 'unggah_draft_dokumen');
		
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
		$this->loader->add_action('wp_ajax_finalisasi_iku', $plugin_public, 'finalisasi_iku');
		$this->loader->add_action('wp_ajax_simpan_finalisasi_iku', $plugin_public, 'simpan_finalisasi_iku');
		$this->loader->add_action('wp_ajax_hapus_finalisasi_iku_opd', $plugin_public, 'hapus_finalisasi_iku_opd');
		$this->loader->add_action('wp_ajax_get_finalisasi_iku_by_id', $plugin_public, 'get_finalisasi_iku_by_id');
		$this->loader->add_action('wp_ajax_edit_finalisasi_iku', $plugin_public, 'edit_finalisasi_iku');
		$this->loader->add_action('wp_ajax_get_data_iku_all',  $plugin_public, 'get_data_iku_all');
		$this->loader->add_action('wp_ajax_nopriv_get_data_iku_all',  $plugin_public, 'get_data_iku_all');
		
		$this->loader->add_action('wp_ajax_get_table_cascading_pd', $plugin_public, 'get_table_cascading_pd');
		$this->loader->add_action('wp_ajax_nopriv_get_table_cascading_pd', $plugin_public, 'get_table_cascading_pd');
		$this->loader->add_action('wp_ajax_get_kegiatan_by_program', $plugin_public, 'get_kegiatan_by_program');
		$this->loader->add_action('wp_ajax_nopriv_get_kegiatan_by_program', $plugin_public, 'get_kegiatan_by_program');
		$this->loader->add_action('wp_ajax_get_tujuan_cascading', $plugin_public, 'get_tujuan_cascading');
		$this->loader->add_action('wp_ajax_get_sasaran_cascading', $plugin_public, 'get_sasaran_cascading');
		$this->loader->add_action('wp_ajax_get_program_cascading', $plugin_public, 'get_program_cascading');
		$this->loader->add_action('wp_ajax_get_kegiatan_cascading', $plugin_public, 'get_kegiatan_cascading');
		$this->loader->add_action('wp_ajax_get_sub_giat_cascading', $plugin_public, 'get_sub_giat_cascading');
		$this->loader->add_action('wp_ajax_get_jabatan_cascading', $plugin_public, 'get_jabatan_cascading');
		$this->loader->add_action('wp_ajax_nopriv_get_jabatan_cascading', $plugin_public, 'get_jabatan_cascading');
		$this->loader->add_action('wp_ajax_submit_pegawai_cascading', $plugin_public, 'submit_pegawai_cascading');

		$this->loader->add_action('wp_ajax_get_detail_dokumen_by_id', $plugin_public, 'get_detail_dokumen_by_id');
		$this->loader->add_action('wp_ajax_submit_tambah_dokumen', $plugin_public, 'submit_tambah_dokumen');
		$this->loader->add_action('wp_ajax_hapus_dokumen', $plugin_public, 'hapus_dokumen');
		$this->loader->add_action('wp_ajax_get_table_dokumen', $plugin_public, 'get_table_dokumen');

		$this->loader->add_action('wp_ajax_get_detail_dokumen_by_id_pemerintah_daerah', $plugin_public, 'get_detail_dokumen_by_id_pemerintah_daerah');
		$this->loader->add_action('wp_ajax_get_table_dokumen_pemerintah_daerah', $plugin_public, 'get_table_dokumen_pemerintah_daerah');
		$this->loader->add_action('wp_ajax_submit_tambah_dokumen_pemerintah_daerah', $plugin_public, 'submit_tambah_dokumen_pemerintah_daerah');
		$this->loader->add_action('wp_ajax_hapus_dokumen_pemerintah_daerah', $plugin_public, 'hapus_dokumen_pemerintah_daerah');

		$this->loader->add_action('wp_ajax_get_detail_laporan_kinerja_by_id', $plugin_public, 'get_detail_laporan_kinerja_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_laporan_kinerja', $plugin_public, 'tambah_dokumen_laporan_kinerja');
		$this->loader->add_action('wp_ajax_submit_tahun_laporan_kinerja', $plugin_public, 'submit_tahun_laporan_kinerja');
		$this->loader->add_action('wp_ajax_hapus_dokumen_laporan_kinerja', $plugin_public, 'hapus_dokumen_laporan_kinerja');
		$this->loader->add_action('wp_ajax_get_table_laporan_kinerja', $plugin_public, 'get_table_laporan_kinerja');
		$this->loader->add_action('wp_ajax_get_verifikasi_dokumen_by_id', $plugin_public, 'get_verifikasi_dokumen_by_id');
		$this->loader->add_action('wp_ajax_submit_verifikasi_dokumen', $plugin_public, 'submit_verifikasi_dokumen');
		
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

		$this->loader->add_action('wp_ajax_get_detail_pohon_kinerja_by_id', $plugin_public, 'get_detail_pohon_kinerja_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_pohon_kinerja', $plugin_public, 'tambah_dokumen_pohon_kinerja');
		$this->loader->add_action('wp_ajax_tambah_dokumen_pohon_kinerja_pemda', $plugin_public, 'tambah_dokumen_pohon_kinerja_pemda');
		$this->loader->add_action('wp_ajax_hapus_dokumen_pohon_kinerja', $plugin_public, 'hapus_dokumen_pohon_kinerja');
		$this->loader->add_action('wp_ajax_submit_tahun_pohon_kinerja', $plugin_public, 'submit_tahun_pohon_kinerja');
		$this->loader->add_action('wp_ajax_get_table_pohon_kinerja', $plugin_public, 'get_table_pohon_kinerja');
		$this->loader->add_action('wp_ajax_get_table_tahun_pohon_kinerja', $plugin_public, 'get_table_tahun_pohon_kinerja');
		$this->loader->add_action('wp_ajax_get_table_skpd_pohon_kinerja', $plugin_public, 'get_table_skpd_pohon_kinerja');

		$this->loader->add_action('wp_ajax_upsert_lembaga_lainnya', $plugin_public, 'upsert_lembaga_lainnya');
		$this->loader->add_action('wp_ajax_hapus_lembaga_lainnya_by_id', $plugin_public, 'hapus_lembaga_lainnya_by_id');
		$this->loader->add_action('wp_ajax_get_lembaga_lainnya_by_id', $plugin_public, 'get_lembaga_lainnya_by_id');
		$this->loader->add_action('wp_ajax_copy_data_lembaga_lainnya', $plugin_public, 'copy_data_lembaga_lainnya');
		$this->loader->add_action('wp_ajax_get_tahun_anggaran_data_unit', $plugin_public, 'get_tahun_anggaran_data_unit');

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
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_rpjmd', $plugin_public, 'hapus_tahun_dokumen_rpjmd');

		$this->loader->add_action('wp_ajax_get_detail_rpjpd_by_id', $plugin_public, 'get_detail_rpjpd_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_rpjpd', $plugin_public, 'tambah_dokumen_rpjpd');
		$this->loader->add_action('wp_ajax_hapus_dokumen_rpjpd', $plugin_public, 'hapus_dokumen_rpjpd');
		$this->loader->add_action('wp_ajax_get_table_rpjpd', $plugin_public, 'get_table_rpjpd');
		$this->loader->add_action('wp_ajax_get_table_tahun_rpjpd', $plugin_public, 'get_table_tahun_rpjpd');
		$this->loader->add_action('wp_ajax_submit_tahun_rpjpd', $plugin_public, 'submit_tahun_rpjpd');
 
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
		
		$this->loader->add_action('wp_ajax_get_table_tahun_dpa', $plugin_public, 'get_table_tahun_dpa');
		$this->loader->add_action('wp_ajax_get_table_skpd_dpa', $plugin_public, 'get_table_skpd_dpa');		
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
		$this->loader->add_action('wp_ajax_get_table_tahun_dokumen', $plugin_public, 'get_table_tahun_dokumen');
		$this->loader->add_action('wp_ajax_get_table_skpd_dokumen', $plugin_public, 'get_table_skpd_dokumen');
		$this->loader->add_action('wp_ajax_submit_tahun_dokumen', $plugin_public, 'submit_tahun_dokumen');

		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_lainnya', $plugin_public, 'hapus_tahun_dokumen_lainnya');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_other_file', $plugin_public, 'hapus_tahun_dokumen_other_file');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_rpjmd', $plugin_public, 'hapus_tahun_dokumen_rpjmd');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_renstra', $plugin_public, 'hapus_tahun_dokumen_renstra');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_laporan_monev_renaksi', $plugin_public, 'hapus_tahun_dokumen_laporan_monev_renaksi');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_perjanjian_kinerja', $plugin_public, 'hapus_tahun_dokumen_perjanjian_kinerja');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_rencana_aksi', $plugin_public, 'hapus_tahun_dokumen_rencana_aksi');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_iku', $plugin_public, 'hapus_tahun_dokumen_iku');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_evaluasi_internal', $plugin_public, 'hapus_tahun_dokumen_evaluasi_internal');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_pengukuran_kinerja', $plugin_public, 'hapus_tahun_dokumen_pengukuran_kinerja');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_pengukuran_rencana_aksi', $plugin_public, 'hapus_tahun_dokumen_pengukuran_rencana_aksi');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_laporan_kinerja', $plugin_public, 'hapus_tahun_dokumen_laporan_kinerja');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_rkpd', $plugin_public, 'hapus_tahun_dokumen_rkpd');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_lkjip_lppd', $plugin_public, 'hapus_tahun_dokumen_lkjip_lppd');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_dpa', $plugin_public, 'hapus_tahun_dokumen_dpa');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_renja_rkt', $plugin_public, 'hapus_tahun_dokumen_renja_rkt');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_skp', $plugin_public, 'hapus_tahun_dokumen_skp');
		$this->loader->add_action('wp_ajax_hapus_tahun_dokumen_tipe', $plugin_public, 'hapus_tahun_dokumen_tipe');

		$this->loader->add_action('wp_ajax_get_table_tahun_renstra', $plugin_public, 'get_table_tahun_renstra');
		$this->loader->add_action('wp_ajax_get_table_skpd_renstra', $plugin_public, 'get_table_skpd_renstra');
		$this->loader->add_action('wp_ajax_get_table_tahun_rpjmd', $plugin_public, 'get_table_tahun_rpjmd');
		$this->loader->add_action('wp_ajax_submit_tahun_rpjmd', $plugin_public, 'submit_tahun_rpjmd');
		
		$this->loader->add_action('wp_ajax_get_table_desain_lke', $plugin_public, 'get_table_desain_lke');
		$this->loader->add_action('wp_ajax_tambah_komponen_lke', $plugin_public, 'tambah_komponen_lke');
		$this->loader->add_action('wp_ajax_tambah_subkomponen_lke', $plugin_public, 'tambah_subkomponen_lke');
		$this->loader->add_action('wp_ajax_tambah_komponen_penilaian_lke', $plugin_public, 'tambah_komponen_penilaian_lke');
		$this->loader->add_action('wp_ajax_get_komponen_lke_by_id', $plugin_public, 'get_komponen_lke_by_id');
		$this->loader->add_action('wp_ajax_get_subkomponen_lke_by_id', $plugin_public, 'get_subkomponen_lke_by_id');
		$this->loader->add_action('wp_ajax_get_komponen_penilaian_lke_by_id', $plugin_public, 'get_komponen_penilaian_lke_by_id');
		$this->loader->add_action('wp_ajax_get_detail_komponen_lke_by_id', $plugin_public, 'get_detail_komponen_lke_by_id');
		$this->loader->add_action('wp_ajax_get_detail_subkomponen_lke_by_id', $plugin_public, 'get_detail_subkomponen_lke_by_id');
		$this->loader->add_action('wp_ajax_get_detail_penilaian_lke_by_id', $plugin_public, 'get_detail_penilaian_lke_by_id');
		$this->loader->add_action('wp_ajax_hapus_komponen_lke', $plugin_public, 'hapus_komponen_lke');
		$this->loader->add_action('wp_ajax_hapus_komponen_penilaian_lke', $plugin_public, 'hapus_komponen_penilaian_lke');
		$this->loader->add_action('wp_ajax_hapus_subkomponen_lke', $plugin_public, 'hapus_subkomponen_lke');
		$this->loader->add_action('wp_ajax_get_komponen_penilaian_pembanding', $plugin_public, 'get_komponen_penilaian_pembanding');
		$this->loader->add_action('wp_ajax_get_subkomponen_pembanding', $plugin_public, 'get_subkomponen_pembanding');
		$this->loader->add_action('wp_ajax_tambah_kerangka_logis_penilaian_lke', $plugin_public, 'tambah_kerangka_logis_penilaian_lke');
		$this->loader->add_action('wp_ajax_get_table_kerangka_logis', $plugin_public, 'get_table_kerangka_logis');
		$this->loader->add_action('wp_ajax_hapus_kerangka_logis', $plugin_public, 'hapus_kerangka_logis');
		$this->loader->add_action('wp_ajax_get_table_opsi_custom', $plugin_public, 'get_table_opsi_custom');
		$this->loader->add_action('wp_ajax_hapus_opsi_custom', $plugin_public, 'hapus_opsi_custom');
		$this->loader->add_action('wp_ajax_tambah_opsi_custom', $plugin_public, 'tambah_opsi_custom');
		$this->loader->add_action('wp_ajax_get_option_custom_by_id', $plugin_public, 'get_option_custom_by_id');
		
		$this->loader->add_action('wp_ajax_tambah_nilai_lke', $plugin_public, 'tambah_nilai_lke');
		$this->loader->add_action('wp_ajax_tambah_nilai_penetapan_lke', $plugin_public, 'tambah_nilai_penetapan_lke');

		$this->loader->add_action('wp_ajax_get_table_skpd_pengisian_lke', $plugin_public, 'get_table_skpd_pengisian_lke');
		$this->loader->add_action('wp_ajax_get_table_pengisian_lke', $plugin_public, 'get_table_pengisian_lke');
		$this->loader->add_action('wp_ajax_list_perangkat_daerah_lke',  $plugin_public, 'list_perangkat_daerah_lke');
		$this->loader->add_action('wp_ajax_get_dokumen_bukti_dukung',  $plugin_public, 'get_dokumen_bukti_dukung');
 
		$this->loader->add_action('wp_ajax_get_detail_laporan_monev_renaksi_by_id', $plugin_public, 'get_detail_laporan_monev_renaksi_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_laporan_monev_renaksi', $plugin_public, 'tambah_dokumen_laporan_monev_renaksi');
		$this->loader->add_action('wp_ajax_hapus_dokumen_laporan_monev_renaksi', $plugin_public, 'hapus_dokumen_laporan_monev_renaksi');
		$this->loader->add_action('wp_ajax_get_table_laporan_monev_renaksi', $plugin_public, 'get_table_laporan_monev_renaksi');
		$this->loader->add_action('wp_ajax_get_table_skpd_laporan_monev_renaksi', $plugin_public, 'get_table_skpd_laporan_monev_renaksi');
		$this->loader->add_action('wp_ajax_get_table_tahun_laporan_monev_renaksi', $plugin_public, 'get_table_tahun_laporan_monev_renaksi');
		$this->loader->add_action('wp_ajax_submit_tahun_laporan_monev_renaksi', $plugin_public, 'submit_tahun_laporan_monev_renaksi');
		$this->loader->add_action('wp_ajax_submit_bukti_dukung', $plugin_public, 'submit_bukti_dukung');
		$this->loader->add_action('wp_ajax_get_penjelasan_lke', $plugin_public, 'get_penjelasan_lke');
		
		$this->loader->add_action('wp_ajax_get_all_kke_ajax', $plugin_public, 'get_all_kke_ajax');
 
		$this->loader->add_action('wp_ajax_get_detail_pedoman_teknis_perencanaan_by_id', $plugin_public, 'get_detail_pedoman_teknis_perencanaan_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_pedoman_teknis_perencanaan', $plugin_public, 'tambah_dokumen_pedoman_teknis_perencanaan');
		$this->loader->add_action('wp_ajax_hapus_dokumen_pedoman_teknis_perencanaan', $plugin_public, 'hapus_dokumen_pedoman_teknis_perencanaan');
		$this->loader->add_action('wp_ajax_get_table_pedoman_teknis_perencanaan', $plugin_public, 'get_table_pedoman_teknis_perencanaan');
		$this->loader->add_action('wp_ajax_get_table_skpd_pedoman_teknis_perencanaan', $plugin_public, 'get_table_skpd_pedoman_teknis_perencanaan');
		$this->loader->add_action('wp_ajax_get_table_tahun_pedoman_teknis_perencanaan', $plugin_public, 'get_table_tahun_pedoman_teknis_perencanaan');
		$this->loader->add_action('wp_ajax_submit_tahun_pedoman_teknis_perencanaan', $plugin_public, 'submit_tahun_pedoman_teknis_perencanaan');
 
		$this->loader->add_action('wp_ajax_get_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_by_id', $plugin_public, 'get_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja', $plugin_public, 'tambah_dokumen_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja');
		$this->loader->add_action('wp_ajax_hapus_dokumen_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja', $plugin_public, 'hapus_dokumen_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja');
		$this->loader->add_action('wp_ajax_get_table_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja', $plugin_public, 'get_table_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja');
		$this->loader->add_action('wp_ajax_get_table_skpd_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja', $plugin_public, 'get_table_skpd_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja');
		$this->loader->add_action('wp_ajax_get_table_tahun_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja', $plugin_public, 'get_table_tahun_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja');
		$this->loader->add_action('wp_ajax_submit_tahun_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja', $plugin_public, 'submit_tahun_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja');
 
		$this->loader->add_action('wp_ajax_get_detail_pedoman_teknis_evaluasi_internal_by_id', $plugin_public, 'get_detail_pedoman_teknis_evaluasi_internal_by_id');
		$this->loader->add_action('wp_ajax_tambah_dokumen_pedoman_teknis_evaluasi_internal', $plugin_public, 'tambah_dokumen_pedoman_teknis_evaluasi_internal');
		$this->loader->add_action('wp_ajax_hapus_dokumen_pedoman_teknis_evaluasi_internal', $plugin_public, 'hapus_dokumen_pedoman_teknis_evaluasi_internal');
		$this->loader->add_action('wp_ajax_get_table_pedoman_teknis_evaluasi_internal', $plugin_public, 'get_table_pedoman_teknis_evaluasi_internal');
		$this->loader->add_action('wp_ajax_get_table_skpd_pedoman_teknis_evaluasi_internal', $plugin_public, 'get_table_skpd_pedoman_teknis_evaluasi_internal');
		$this->loader->add_action('wp_ajax_get_table_tahun_pedoman_teknis_evaluasi_internal', $plugin_public, 'get_table_tahun_pedoman_teknis_evaluasi_internal');
		$this->loader->add_action('wp_ajax_submit_tahun_pedoman_teknis_evaluasi_internal', $plugin_public, 'submit_tahun_pedoman_teknis_evaluasi_internal');
		
		$this->loader->add_action('wp_ajax_get_pokin_renaksi_by_id', $plugin_public, 'get_pokin_renaksi_by_id');
		$this->loader->add_action('wp_ajax_get_pokin_renaksi', $plugin_public, 'get_pokin_renaksi');
		$this->loader->add_action('wp_ajax_tambah_pokin_renaksi', $plugin_public, 'tambah_pokin_renaksi');
		
		$this->loader->add_action('wp_ajax_get_data_pokin',  $plugin_public, 'get_data_pokin');
		$this->loader->add_action('wp_ajax_get_data_pokin_all',  $plugin_public, 'get_data_pokin_all');
		$this->loader->add_action('wp_ajax_nopriv_get_data_pokin_all',  $plugin_public, 'get_data_pokin_all');
		$this->loader->add_action('wp_ajax_create_pokin',  $plugin_public, 'create_pokin');
		$this->loader->add_action('wp_ajax_edit_pokin',  $plugin_public, 'edit_pokin');
		$this->loader->add_action('wp_ajax_nopriv_edit_pokin',  $plugin_public, 'edit_pokin');
		$this->loader->add_action('wp_ajax_update_pokin',  $plugin_public, 'update_pokin');
		$this->loader->add_action('wp_ajax_delete_pokin',  $plugin_public, 'delete_pokin');
		$this->loader->add_action('wp_ajax_create_indikator_pokin',  $plugin_public, 'create_indikator_pokin');
		$this->loader->add_action('wp_ajax_edit_indikator_pokin',  $plugin_public, 'edit_indikator_pokin');
		$this->loader->add_action('wp_ajax_update_indikator_pokin',  $plugin_public, 'update_indikator_pokin');
		$this->loader->add_action('wp_ajax_delete_indikator_pokin',  $plugin_public, 'delete_indikator_pokin');
		
		$this->loader->add_action('wp_ajax_get_data_crosscutting_pemda',  $plugin_public, 'get_data_crosscutting_pemda');
		$this->loader->add_action('wp_ajax_create_crosscutting_pemda',  $plugin_public, 'create_crosscutting_pemda');
		$this->loader->add_action('wp_ajax_edit_crosscutting_pemda',  $plugin_public, 'edit_crosscutting_pemda');
		$this->loader->add_action('wp_ajax_update_crosscutting_pemda',  $plugin_public, 'update_crosscutting_pemda');
		$this->loader->add_action('wp_ajax_delete_crosscutting_pemda',  $plugin_public, 'delete_crosscutting_pemda');
		$this->loader->add_action('wp_ajax_create_indikator_crosscutting_pemda',  $plugin_public, 'create_indikator_crosscutting_pemda');
		$this->loader->add_action('wp_ajax_edit_indikator_crosscutting_pemda',  $plugin_public, 'edit_indikator_crosscutting_pemda');
		$this->loader->add_action('wp_ajax_update_indikator_crosscutting_pemda',  $plugin_public, 'update_indikator_crosscutting_pemda');
		$this->loader->add_action('wp_ajax_delete_indikator_crosscutting_pemda',  $plugin_public, 'delete_indikator_crosscutting_pemda');
		$this->loader->add_action('wp_ajax_get_crosscutting_pemda',  $plugin_public, 'get_crosscutting_pemda');
		
		$this->loader->add_action('wp_ajax_create_croscutting',  $plugin_public, 'create_croscutting');
		$this->loader->add_action('wp_ajax_edit_croscutting',  $plugin_public, 'edit_croscutting');
		$this->loader->add_action('wp_ajax_update_croscutting',  $plugin_public, 'update_croscutting');
		$this->loader->add_action('wp_ajax_delete_croscutting',  $plugin_public, 'delete_croscutting');
		$this->loader->add_action('wp_ajax_verify_croscutting',  $plugin_public, 'verify_croscutting');
		$this->loader->add_action('wp_ajax_edit_verify_croscutting',  $plugin_public, 'edit_verify_croscutting');
		$this->loader->add_action('wp_ajax_detail_croscutting_by_id',  $plugin_public, 'detail_croscutting_by_id');
		$this->loader->add_action('wp_ajax_nopriv_detail_croscutting_by_id',  $plugin_public, 'detail_croscutting_by_id');

		$this->loader->add_action('wp_ajax_get_data_koneksi_pokin',  $plugin_public, 'get_data_koneksi_pokin');
		$this->loader->add_action('wp_ajax_create_koneksi_pokin',  $plugin_public, 'create_koneksi_pokin');
		$this->loader->add_action('wp_ajax_delete_koneksi_pokin',  $plugin_public, 'delete_koneksi_pokin');
		$this->loader->add_action('wp_ajax_update_koneksi_pokin',  $plugin_public, 'update_koneksi_pokin');
		$this->loader->add_action('wp_ajax_get_skpd_koneksi_pokin_by_id',  $plugin_public, 'get_skpd_koneksi_pokin_by_id');
		$this->loader->add_action('wp_ajax_verify_koneksi_pokin_pemda_opd',  $plugin_public, 'verify_koneksi_pokin_pemda_opd');
		$this->loader->add_action('wp_ajax_edit_verify_koneksi_pokin_pemda',  $plugin_public, 'edit_verify_koneksi_pokin_pemda');

		$this->loader->add_action('wp_ajax_get_data_pengaturan_menu',  $plugin_public, 'get_data_pengaturan_menu');
		$this->loader->add_action('wp_ajax_get_pengaturan_menu_by_id',  $plugin_public, 'get_pengaturan_menu_by_id');
		$this->loader->add_action('wp_ajax_submit_edit_pengaturan_menu_dokumen',  $plugin_public, 'submit_edit_pengaturan_menu_dokumen');
		$this->loader->add_action('wp_ajax_get_data_pengaturan_menu_khusus',  $plugin_public, 'get_data_pengaturan_menu_khusus');
		$this->loader->add_action('wp_ajax_simpan_perubahan_menu_khusus',  $plugin_public, 'simpan_perubahan_menu_khusus');
		$this->loader->add_action('wp_ajax_get_html_menu_khusus_opd_by_menu',  $plugin_public, 'get_html_menu_khusus_opd_by_menu');
		$this->loader->add_action('wp_ajax_get_html_menu_khusus_pemda_by_menu',  $plugin_public, 'get_html_menu_khusus_pemda_by_menu');
		
		$this->loader->add_action('wp_ajax_esakip_simpan_rpjpd',  $plugin_public, 'esakip_simpan_rpjpd');
		$this->loader->add_action('wp_ajax_esakip_get_rpjpd',  $plugin_public, 'esakip_get_rpjpd');
		$this->loader->add_action('wp_ajax_esakip_simpan_rpjpd',  $plugin_public, 'esakip_simpan_rpjpd');
		$this->loader->add_action('wp_ajax_esakip_hapus_rpjpd',  $plugin_public, 'esakip_hapus_rpjpd');
		
		$this->loader->add_action('wp_ajax_esakip_hapus_rpd',  $plugin_public, 'esakip_hapus_rpd');
		$this->loader->add_action('wp_ajax_esakip_simpan_rpd',  $plugin_public, 'esakip_simpan_rpd');
		$this->loader->add_action('wp_ajax_esakip_get_rpd',  $plugin_public, 'esakip_get_rpd');
		
		$this->loader->add_action('wp_ajax_nopriv_get_all_rpd_by_id_jadwal_ajax',  $plugin_public, 'get_all_rpd_by_id_jadwal_ajax');
		$this->loader->add_action('wp_ajax_nopriv_get_all_rpjmd_by_id_jadwal_ajax',  $plugin_public, 'get_all_rpjmd_by_id_jadwal_ajax');

		$this->loader->add_action('wp_ajax_esakip_get_bidang_urusan',  $plugin_public, 'esakip_get_bidang_urusan');
		$this->loader->add_action('wp_ajax_get_indikator_sasaran',  $plugin_public, 'get_indikator_sasaran');
		$this->loader->add_action('wp_ajax_get_sasaran_sebelum',  $plugin_public, 'get_sasaran_sebelum');

		$this->loader->add_action('wp_ajax_get_table_cascading',  $plugin_public, 'get_table_cascading');
		$this->loader->add_action('wp_ajax_edit_cascading_pemda',  $plugin_public, 'edit_cascading_pemda');
		$this->loader->add_action('wp_ajax_submit_edit_cascading',  $plugin_public, 'submit_edit_cascading');
		$this->loader->add_action('wp_ajax_view_cascading_pemda',  $plugin_public, 'view_cascading_pemda');
		$this->loader->add_action('wp_ajax_nopriv_view_cascading_pemda',  $plugin_public, 'view_cascading_pemda');

		$this->loader->add_action('wp_ajax_get_table_crosscutting_pemda',  $plugin_public, 'get_table_crosscutting_pemda');
		$this->loader->add_action('wp_ajax_edit_crosscutting_pemda_tujuan',  $plugin_public, 'edit_crosscutting_pemda_tujuan');
		$this->loader->add_action('wp_ajax_submit_edit_crosscutting_pemda',  $plugin_public, 'submit_edit_crosscutting_pemda');

		$this->loader->add_action('wp_ajax_get_table_skpd_pengisian_rencana_aksi', $plugin_public, 'get_table_skpd_pengisian_rencana_aksi');
		$this->loader->add_action('wp_ajax_get_data_renaksi', $plugin_public, 'get_data_renaksi');
		$this->loader->add_action('wp_ajax_create_renaksi', $plugin_public, 'create_renaksi');
		$this->loader->add_action('wp_ajax_cek_validasi_input_rencana_pagu', $plugin_public, 'cek_validasi_input_rencana_pagu');
		$this->loader->add_action('wp_ajax_get_table_input_rencana_aksi', $plugin_public, 'get_table_input_rencana_aksi');
		$this->loader->add_action('wp_ajax_create_indikator_renaksi', $plugin_public, 'create_indikator_renaksi');
		$this->loader->add_action('wp_ajax_hapus_indikator_rencana_aksi', $plugin_public, 'hapus_indikator_rencana_aksi');
		$this->loader->add_action('wp_ajax_get_indikator_rencana_aksi', $plugin_public, 'get_indikator_rencana_aksi');
		$this->loader->add_action('wp_ajax_get_rencana_aksi', $plugin_public, 'get_rencana_aksi');
		$this->loader->add_action('wp_ajax_hapus_rencana_aksi', $plugin_public, 'hapus_rencana_aksi');
		$this->loader->add_action('wp_ajax_get_data_rekening_akun_wp_sipd', $plugin_public, 'get_data_rekening_akun_wp_sipd');
		$this->loader->add_action('wp_ajax_get_data_rincian_belanja', $plugin_public, 'get_data_rincian_belanja');
		$this->loader->add_action('wp_ajax_simpan_bulanan_renaksi_opd', $plugin_public, 'simpan_bulanan_renaksi_opd');
		$this->loader->add_action('wp_ajax_simpan_triwulan_renaksi_opd', $plugin_public, 'simpan_triwulan_renaksi_opd');
		$this->loader->add_action('wp_ajax_simpan_total_bulanan', $plugin_public, 'simpan_total_bulanan');
		$this->loader->add_action('wp_ajax_get_pegawai_rhk', $plugin_public, 'get_pegawai_rhk');
		$this->loader->add_action('wp_ajax_help_rhk', $plugin_public, 'help_rhk');
		$this->loader->add_action('wp_ajax_copy_data_rencana_aksi', $plugin_public, 'copy_data_rencana_aksi');
		$this->loader->add_action('wp_ajax_get_data_target_bulanan_ekinerja', $plugin_public, 'get_data_target_bulanan_ekinerja');	

		$this->loader->add_action('wp_ajax_get_rencana_hasil_kerja', $plugin_public, 'get_rencana_hasil_kerja');
		$this->loader->add_action('wp_ajax_nopriv_get_rencana_hasil_kerja', $plugin_public, 'get_rencana_hasil_kerja');

		$this->loader->add_action('wp_ajax_crate_tagging_rincian_belanja', $plugin_public, 'crate_tagging_rincian_belanja');

		$this->loader->add_action('wp_ajax_get_table_input_iku', $plugin_public, 'get_table_input_iku');
		$this->loader->add_action('wp_ajax_tambah_iku', $plugin_public, 'tambah_iku');
		$this->loader->add_action('wp_ajax_hapus_iku', $plugin_public, 'hapus_iku');
		$this->loader->add_action('wp_ajax_get_iku_by_id', $plugin_public, 'get_iku_by_id');
		
		$this->loader->add_action('wp_ajax_get_sasaran_rpjmd', $plugin_public, 'get_sasaran_rpjmd');
		$this->loader->add_action('wp_ajax_get_table_input_iku_pemda', $plugin_public, 'get_table_input_iku_pemda');
		$this->loader->add_action('wp_ajax_tambah_iku_pemda', $plugin_public, 'tambah_iku_pemda');

		$this->loader->add_action('wp_ajax_finalisasi_iku_pemda', $plugin_public, 'finalisasi_iku_pemda');
		$this->loader->add_action('wp_ajax_simpan_finalisasi_iku_pemda', $plugin_public, 'simpan_finalisasi_iku_pemda');
		$this->loader->add_action('wp_ajax_hapus_finalisasi_iku_pemda', $plugin_public, 'hapus_finalisasi_iku_pemda');
		$this->loader->add_action('wp_ajax_get_finalisasi_iku_pemda_by_id', $plugin_public, 'get_finalisasi_iku_pemda_by_id');
		$this->loader->add_action('wp_ajax_edit_finalisasi_iku_pemda', $plugin_public, 'edit_finalisasi_iku_pemda');
		
		$this->loader->add_action('wp_ajax_get_table_skpd_input_iku', $plugin_public, 'get_table_skpd_input_iku');
		$this->loader->add_action('wp_ajax_get_table_skpd_input_cascading', $plugin_public, 'get_table_skpd_input_cascading');

		$this->loader->add_action('wp_ajax_get_data_pengaturan_rencana_aksi', $plugin_public, 'get_data_pengaturan_rencana_aksi');
		$this->loader->add_action('wp_ajax_submit_pengaturan_rencana_aksi', $plugin_public, 'submit_pengaturan_rencana_aksi');
		$this->loader->add_action('wp_ajax_submit_pengaturan_rencana_aksi_pemda', $plugin_public, 'submit_pengaturan_rencana_aksi_pemda');

		$this->loader->add_action('wp_ajax_simpan_copy_data_pokin', $plugin_public, 'simpan_copy_data_pokin');
		$this->loader->add_action('wp_ajax_get_table_capaian_indikator',  $plugin_public, 'get_table_capaian_indikator');

		$this->loader->add_action('wp_ajax_get_tujuan_sasaran_cascading',  $plugin_public, 'get_tujuan_sasaran_cascading');
		
		$this->loader->add_action('wp_ajax_get_cascading_pd_from_renstra',  $plugin_public, 'get_cascading_pd_from_renstra');
		
		$this->loader->add_action('wp_ajax_sync_from_esr',  $plugin_public, 'sync_from_esr');
		$this->loader->add_action('wp_ajax_sync_to_esr',  $plugin_public, 'sync_to_esr');
		$this->loader->add_action('wp_ajax_sync_user_from_esr',  $plugin_public, 'sync_user_from_esr');
		
		$this->loader->add_action('wp_ajax_mapping_user_esr',  $plugin_public, 'mapping_user_esr');

		$this->loader->add_action('wp_ajax_get_table_renaksi_pemda',  $plugin_public, 'get_table_renaksi_pemda');
		$this->loader->add_action('wp_ajax_get_table_input_rencana_aksi_pemda', $plugin_public, 'get_table_input_rencana_aksi_pemda');
		$this->loader->add_action('wp_ajax_get_table_rencana_aksi_pemda_baru', $plugin_public, 'get_table_rencana_aksi_pemda_baru');
		$this->loader->add_action('wp_ajax_get_data_renaksi_pemda_baru', $plugin_public, 'get_data_renaksi_pemda_baru');
		$this->loader->add_action('wp_ajax_get_edit_data_renaksi_pemda_baru', $plugin_public, 'get_edit_data_renaksi_pemda_baru');
		$this->loader->add_action('wp_ajax_submit_edit_renaksi_pemda', $plugin_public, 'submit_edit_renaksi_pemda');
		$this->loader->add_action('wp_ajax_get_data_renaksi_pemda', $plugin_public, 'get_data_renaksi_pemda');
		$this->loader->add_action('wp_ajax_get_tujuan_sasaran_cascading_pemda', $plugin_public, 'get_tujuan_sasaran_cascading_pemda');
		$this->loader->add_action('wp_ajax_get_data_pokin_pemda', $plugin_public, 'get_data_pokin_pemda');
		$this->loader->add_action('wp_ajax_tambah_renaksi_pemda', $plugin_public, 'tambah_renaksi_pemda');
		$this->loader->add_action('wp_ajax_tambah_indikator_renaksi_pemda', $plugin_public, 'tambah_indikator_renaksi_pemda');
		$this->loader->add_action('wp_ajax_hapus_rencana_aksi_pemda', $plugin_public, 'hapus_rencana_aksi_pemda');
		$this->loader->add_action('wp_ajax_get_rencana_aksi_pemda', $plugin_public, 'get_rencana_aksi_pemda');
		$this->loader->add_action('wp_ajax_get_indikator_rencana_aksi_pemda', $plugin_public, 'get_indikator_rencana_aksi_pemda');
		$this->loader->add_action('wp_ajax_hapus_indikator_rencana_aksi_pemda', $plugin_public, 'hapus_indikator_rencana_aksi_pemda');
		$this->loader->add_action('wp_ajax_help_rhk_pemda', $plugin_public, 'help_rhk_pemda');
		$this->loader->add_action('wp_ajax_get_skpd_renaksi', $plugin_public, 'get_skpd_renaksi');
		$this->loader->add_action('wp_ajax_generatePokin', $plugin_public, 'generatePokin');
		
		$this->loader->add_action('wp_ajax_simpan_renaksi_pemda', $plugin_public, 'simpan_renaksi_pemda');
		$this->loader->add_action('wp_ajax_get_data_capaian_indikator', $plugin_public, 'get_data_capaian_indikator');
		$this->loader->add_action('wp_ajax_simpan_data_capaian_indikator', $plugin_public, 'simpan_data_capaian_indikator');
		$this->loader->add_action('wp_ajax_edit_capaian_indikator', $plugin_public, 'edit_capaian_indikator');
		$this->loader->add_action('wp_ajax_hapus_capaian_indikator', $plugin_public, 'hapus_capaian_indikator');

		$this->loader->add_action('wp_ajax_mapping_jenis_dokumen_esr',  $plugin_public, 'mapping_jenis_dokumen_esr');
		$this->loader->add_action('wp_ajax_generate_master_jenis_dokumen_esr',  $plugin_public, 'generate_master_jenis_dokumen_esr');
		
		$this->loader->add_action('wp_ajax_get_data_upload_dokumen',  $plugin_public, 'get_data_upload_dokumen');
		$this->loader->add_action('wp_ajax_submit_pengaturan_upload_dokumen',  $plugin_public, 'submit_pengaturan_upload_dokumen');
		
		$this->loader->add_action('wp_ajax_get_satker_simpeg',  $plugin_public, 'get_satker_simpeg');
		$this->loader->add_action('wp_ajax_get_list_satker_simpeg',  $plugin_public, 'get_list_satker_simpeg');
		$this->loader->add_action('wp_ajax_get_pegawai_simpeg',  $plugin_public, 'get_pegawai_simpeg');
		$this->loader->add_action('wp_ajax_mapping_unit_sipd_simpeg',  $plugin_public, 'mapping_unit_sipd_simpeg');
		$this->loader->add_action('wp_ajax_update_atasan_pegawai_ajax',  $plugin_public, 'update_atasan_pegawai_ajax');
		$this->loader->add_action('wp_ajax_get_data_pegawai_simpeg_by_id_ajax',  $plugin_public, 'get_data_pegawai_simpeg_by_id_ajax');
		$this->loader->add_action('wp_ajax_get_data_pegawai_simpeg_by_satker_id_ajax',  $plugin_public, 'get_data_pegawai_simpeg_by_satker_id_ajax');

		$this->loader->add_action('wp_ajax_simpan_finalisasi_laporan_pk', $plugin_public, 'simpan_finalisasi_laporan_pk');
		$this->loader->add_action('wp_ajax_hapus_finalisasi_laporan_pk', $plugin_public, 'hapus_finalisasi_laporan_pk');
		$this->loader->add_action('wp_ajax_edit_finalisasi_laporan_pk', $plugin_public, 'edit_finalisasi_laporan_pk');
		$this->loader->add_action('wp_ajax_get_laporan_pk_by_id', $plugin_public, 'get_laporan_pk_by_id');
		$this->loader->add_action('wp_ajax_get_table_skpd_laporan_pk', $plugin_public, 'get_table_skpd_laporan_pk');
		$this->loader->add_action('wp_ajax_get_table_skpd_laporan_pk_setting', $plugin_public, 'get_table_skpd_laporan_pk_setting');
		$this->loader->add_action('wp_ajax_get_detail_setting_laporan_pk_by_id', $plugin_public, 'get_detail_setting_laporan_pk_by_id');
		$this->loader->add_action('wp_ajax_submit_edit_laporan_pk_setting', $plugin_public, 'submit_edit_laporan_pk_setting');
		$this->loader->add_action('wp_ajax_tambah_logo_pemda_laporan_pk', $plugin_public, 'tambah_logo_pemda_laporan_pk');
		$this->loader->add_action('wp_ajax_get_table_pegawai_simpeg_pk', $plugin_public, 'get_table_pegawai_simpeg_pk');
		$this->loader->add_action('wp_ajax_get_table_pk_pemda', $plugin_public, 'get_table_pk_pemda');
		$this->loader->add_action('wp_ajax_get_alamat_kantor', $plugin_public, 'get_alamat_kantor');
		$this->loader->add_action('wp_ajax_submit_alamat_kantor', $plugin_public, 'submit_alamat_kantor');
		$this->loader->add_action('wp_ajax_simpan_finalisasi_pk_pemda', $plugin_public, 'simpan_finalisasi_pk_pemda');
		$this->loader->add_action('wp_ajax_hapus_finalisasi_pk_pemda', $plugin_public, 'hapus_finalisasi_pk_pemda');
		$this->loader->add_action('wp_ajax_get_finalisasi_pk_pemda_by_id', $plugin_public, 'get_finalisasi_pk_pemda_by_id');
		$this->loader->add_action('wp_ajax_edit_finalisasi_pk_pemda', $plugin_public, 'edit_finalisasi_pk_pemda');
		$this->loader->add_action('wp_ajax_get_data_pk_pemda_by_id_ajax', $plugin_public, 'get_data_pk_pemda_by_id_ajax');
		$this->loader->add_action('wp_ajax_get_all_data_pk_pemda_by_tahun_and_id_jadwal_ajax', $plugin_public, 'get_all_data_pk_pemda_by_tahun_and_id_jadwal_ajax');
		$this->loader->add_action('wp_ajax_submit_data_pk_pemda', $plugin_public, 'submit_data_pk_pemda');
		$this->loader->add_action('wp_ajax_hapus_sasaran_pk', $plugin_public, 'hapus_sasaran_pk');
		
		$this->loader->add_action('wp_ajax_get_table_pegawai_simpeg', $plugin_public, 'get_table_pegawai_simpeg');
		$this->loader->add_action('wp_ajax_simpan_pegawai_simpeg', $plugin_public, 'simpan_pegawai_simpeg');
		
		$this->loader->add_action('wp_ajax_nopriv_get_serapan_anggaran_capaian_kinerja',  $plugin_public, 'get_serapan_anggaran_capaian_kinerja');

		$this->loader->add_action('wp_ajax_get_sub_keg_rka_wpsipd',  $plugin_public, 'get_sub_keg_rka_wpsipd');
		$this->loader->add_action('wp_ajax_get_data_akun',  $plugin_public, 'get_data_akun');
		$this->loader->add_action('wp_ajax_get_data_satuan',  $plugin_public, 'get_data_satuan');
		$this->loader->add_action('wp_ajax_simpan_rinci_bl_tagging',  $plugin_public, 'simpan_rinci_bl_tagging');
		$this->loader->add_action('wp_ajax_simpan_rinci_bl_tagging_manual',  $plugin_public, 'simpan_rinci_bl_tagging_manual');
		$this->loader->add_action('wp_ajax_get_rinci_tagging_by_id',  $plugin_public, 'get_rinci_tagging_by_id');
		$this->loader->add_action('wp_ajax_delete_rincian_tagging_by_id_rinci_bl',  $plugin_public, 'delete_rincian_tagging_by_id_rinci_bl');
		$this->loader->add_action('wp_ajax_get_table_tl_lhe_akip_internal',  $plugin_public, 'get_table_tl_lhe_akip_internal');
		$this->loader->add_action('wp_ajax_hapus_dokumen_tl_lhe_akip_internal',  $plugin_public, 'hapus_dokumen_tl_lhe_akip_internal');
		$this->loader->add_action('wp_ajax_tambah_dokumen_tl_lhe_akip_internal',  $plugin_public, 'tambah_dokumen_tl_lhe_akip_internal');
		$this->loader->add_action('wp_ajax_get_detail_tl_lhe_akip_internal_by_id',  $plugin_public, 'get_detail_tl_lhe_akip_internal_by_id');
		
		$this->loader->add_action('wp_ajax_get_datatable_cascading_publish',  $plugin_public, 'get_datatable_cascading_publish');
		$this->loader->add_action('wp_ajax_nopriv_get_datatable_cascading_publish',  $plugin_public, 'get_datatable_cascading_publish');
		$this->loader->add_action('wp_ajax_get_datatable_pokin_publish',  $plugin_public, 'get_datatable_pokin_publish');
		$this->loader->add_action('wp_ajax_nopriv_get_datatable_pokin_publish',  $plugin_public, 'get_datatable_pokin_publish');
		$this->loader->add_action('wp_ajax_get_datatable_iku_publish',  $plugin_public, 'get_datatable_iku_publish');
		$this->loader->add_action('wp_ajax_nopriv_get_datatable_iku_publish',  $plugin_public, 'get_datatable_iku_publish');
		$this->loader->add_action('wp_ajax_nopriv_get_table_pk_publish',  $plugin_public, 'get_table_pk_publish');
		$this->loader->add_action('wp_ajax_get_table_pk_publish',  $plugin_public, 'get_table_pk_publish');
		$this->loader->add_action('wp_ajax_get_datatable_iku_publish_opd',  $plugin_public, 'get_datatable_iku_publish_opd');
		$this->loader->add_action('wp_ajax_nopriv_get_datatable_iku_publish_opd',  $plugin_public, 'get_datatable_iku_publish_opd');
		
		$this->loader->add_action('wp_ajax_get_table_laporan_rencana_aksi',  $plugin_public, 'get_table_laporan_rencana_aksi');
		$this->loader->add_action('wp_ajax_cek_input_pagu_parent',  $plugin_public, 'cek_input_pagu_parent');

		$this->loader->add_action('wp_ajax_get_table_skpd_kuesioner_menpan',  $plugin_public, 'get_table_skpd_kuesioner_menpan');
		$this->loader->add_action('wp_ajax_tambah_kuesioner_menpan',  $plugin_public, 'tambah_kuesioner_menpan');
		$this->loader->add_action('wp_ajax_get_table_kuesioner_menpan',  $plugin_public, 'get_table_kuesioner_menpan');
		$this->loader->add_action('wp_ajax_get_table_data_capaian_kinerja_publik',  $plugin_public, 'get_table_data_capaian_kinerja_publik');
		$this->loader->add_action('wp_ajax_nopriv_get_table_data_capaian_kinerja_publik',  $plugin_public, 'get_table_data_capaian_kinerja_publik');
		$this->loader->add_action('wp_ajax_get_kuesioner_menpan_by_id',  $plugin_public, 'get_kuesioner_menpan_by_id');
		$this->loader->add_action('wp_ajax_hapus_data_kuesioner_menpan',  $plugin_public, 'hapus_data_kuesioner_menpan');
		$this->loader->add_action('wp_ajax_get_detail_pertanyaan_menpan',  $plugin_public, 'get_detail_pertanyaan_menpan');
		$this->loader->add_action('wp_ajax_generate_data_menpan',  $plugin_public, 'generate_data_menpan');
		$this->loader->add_action('wp_ajax_submit_kuesioner_pertanyaan_menpan',  $plugin_public, 'submit_kuesioner_pertanyaan_menpan');
		$this->loader->add_action('wp_ajax_get_kuesioner_menpan_detail_by_id',  $plugin_public, 'get_kuesioner_menpan_detail_by_id');
		$this->loader->add_action('wp_ajax_hapus_data_kuesioner_menpan_detail',  $plugin_public, 'hapus_data_kuesioner_menpan_detail');
		$this->loader->add_action('wp_ajax_copy_data_kuesioner_menpan',  $plugin_public, 'copy_data_kuesioner_menpan');
		$this->loader->add_action('wp_ajax_tambah_penilaian_kuesioner_menpan',  $plugin_public, 'tambah_penilaian_kuesioner_menpan');
		$this->loader->add_action('wp_ajax_get_table_penilaian_kuesioner_menpan',  $plugin_public, 'get_table_penilaian_kuesioner_menpan');

		$this->loader->add_action('wp_ajax_get_table_skpd_kuesioner_mendagri',  $plugin_public, 'get_table_skpd_kuesioner_mendagri');
		$this->loader->add_action('wp_ajax_get_table_kuesioner_mendagri',  $plugin_public, 'get_table_kuesioner_mendagri');
		$this->loader->add_action('wp_ajax_tambah_kuesioner_mendagri',  $plugin_public, 'tambah_kuesioner_mendagri');
		$this->loader->add_action('wp_ajax_get_kuesioner_mendagri_by_id',  $plugin_public, 'get_kuesioner_mendagri_by_id');
		$this->loader->add_action('wp_ajax_hapus_data_kuesioner_mendagri',  $plugin_public, 'hapus_data_kuesioner_mendagri');
		$this->loader->add_action('wp_ajax_get_detail_pertanyaan_mendagri',  $plugin_public, 'get_detail_pertanyaan_mendagri');
		$this->loader->add_action('wp_ajax_generate_data_mendagri',  $plugin_public, 'generate_data_mendagri');
		$this->loader->add_action('wp_ajax_submit_kuesioner_pertanyaan_mendagri',  $plugin_public, 'submit_kuesioner_pertanyaan_mendagri');
		$this->loader->add_action('wp_ajax_get_kuesioner_mendagri_detail_by_id',  $plugin_public, 'get_kuesioner_mendagri_detail_by_id');
		$this->loader->add_action('wp_ajax_hapus_data_kuesioner_mendagri_detail',  $plugin_public, 'hapus_data_kuesioner_mendagri_detail');
		$this->loader->add_action('wp_ajax_copy_data_kuesioner_mendagri',  $plugin_public, 'copy_data_kuesioner_mendagri');
		$this->loader->add_action('wp_ajax_tambah_bukti_dukung_mendagri',  $plugin_public, 'tambah_bukti_dukung_mendagri');
		$this->loader->add_action('wp_ajax_get_bukti_dukung_kuesioner_by_id',  $plugin_public, 'get_bukti_dukung_kuesioner_by_id');
		$this->loader->add_action('wp_ajax_hapus_bukti_dukung_mendagri',  $plugin_public, 'hapus_bukti_dukung_mendagri');

		$this->loader->add_action('wp_ajax_get_table_variabel_pengisian_mendagri',  $plugin_public, 'get_table_variabel_pengisian_mendagri');
		$this->loader->add_action('wp_ajax_get_indikator_bukti_by_level',  $plugin_public, 'get_indikator_bukti_by_level');
		$this->loader->add_action('wp_ajax_get_all_level_by_id_variabel',  $plugin_public, 'get_all_level_by_id_variabel');
		$this->loader->add_action('wp_ajax_submit_detail_kuesioner',  $plugin_public, 'submit_detail_kuesioner');
		$this->loader->add_action('wp_ajax_get_dokumen_bukti_dukung_kuesioner',  $plugin_public, 'get_dokumen_bukti_dukung_kuesioner');
		$this->loader->add_action('wp_ajax_submit_bukti_dukung_kuesioner',  $plugin_public, 'submit_bukti_dukung_kuesioner');

		$this->loader->add_action('wp_ajax_handle_view_pokin',  $plugin_public, 'handle_view_pokin');
		$this->loader->add_action('wp_ajax_nopriv_handle_view_pokin',  $plugin_public, 'handle_view_pokin');
		$this->loader->add_action('wp_ajax_generate_fields_dokumen_kuesioner',  $plugin_public, 'generate_fields_dokumen_kuesioner');
		$this->loader->add_action('wp_ajax_get_table_skpd_dokumen_kuesioner',  $plugin_public, 'get_table_skpd_dokumen_kuesioner');
		$this->loader->add_action('wp_ajax_get_table_dokumen_kuesioner',  $plugin_public, 'get_table_dokumen_kuesioner');
		$this->loader->add_action('wp_ajax_tambah_dokumen_kuesioner',  $plugin_public, 'tambah_dokumen_kuesioner');
		$this->loader->add_action('wp_ajax_get_detail_kuesioner_by_id',  $plugin_public, 'get_detail_kuesioner_by_id');
		$this->loader->add_action('wp_ajax_hapus_dokumen_kuesioner',  $plugin_public, 'hapus_dokumen_kuesioner');
		
		$this->loader->add_action('wp_ajax_get_table_jadwal_kuesioner',  $plugin_public, 'get_table_jadwal_kuesioner');
		$this->loader->add_action('wp_ajax_get_data_jadwal_kuesioner_by_id',  $plugin_public, 'get_data_jadwal_kuesioner_by_id');
		$this->loader->add_action('wp_ajax_submit_jadwal_kuesioner',  $plugin_public, 'submit_jadwal_kuesioner');


		$this->loader->add_action('wp_ajax_get_table_iku_pemda',  $plugin_public, 'get_table_iku_pemda');
		$this->loader->add_action('wp_ajax_get_table_iku_opd',  $plugin_public, 'get_table_iku_opd');

		add_shortcode('jadwal_verifikasi_upload_dokumen', array($plugin_public, 'jadwal_verifikasi_upload_dokumen'));
		add_shortcode('jadwal_verifikasi_upload_dokumen_renstra', array($plugin_public, 'jadwal_verifikasi_upload_dokumen'));
		add_shortcode('jadwal_evaluasi_sakip', array($plugin_public, 'jadwal_evaluasi_sakip'));
		add_shortcode('halaman_mapping_skpd', array($plugin_public, 'halaman_mapping_skpd'));
		
		add_shortcode('upload_dokumen_renstra', array($plugin_public, 'upload_dokumen_renstra'));
		add_shortcode('upload_dokumen_rpjmd', array($plugin_public, 'upload_dokumen_rpjmd'));
		add_shortcode('upload_dokumen_rpjpd', array($plugin_public, 'upload_dokumen_rpjpd'));
		
		add_shortcode('renstra', array($plugin_public, 'renstra'));
		add_shortcode('dpa', array($plugin_public, 'dpa'));
		add_shortcode('renja_rkt', array($plugin_public, 'renja_rkt'));
		add_shortcode('perjanjian_kinerja', array($plugin_public, 'perjanjian_kinerja'));
		add_shortcode('rencana_aksi', array($plugin_public, 'rencana_aksi'));
		add_shortcode('list_pengisian_rencana_aksi_pemda', array($plugin_public, 'list_pengisian_rencana_aksi_pemda'));
		add_shortcode('list_pengisian_rencana_aksi_pemda_baru', array($plugin_public, 'list_pengisian_rencana_aksi_pemda_baru'));
		add_shortcode('iku', array($plugin_public, 'iku'));
		add_shortcode('skp', array($plugin_public, 'skp'));
		add_shortcode('pengukuran_kinerja', array($plugin_public, 'pengukuran_kinerja'));
		add_shortcode('pengukuran_rencana_aksi', array($plugin_public, 'pengukuran_rencana_aksi'));
		add_shortcode('laporan_kinerja', array($plugin_public, 'laporan_kinerja'));
		add_shortcode('evaluasi_internal', array($plugin_public, 'evaluasi_internal'));
		add_shortcode('dokumen_lainnya', array($plugin_public, 'dokumen_lainnya'));
		add_shortcode('list_pengisian_rencana_aksi', array($plugin_public, 'list_pengisian_rencana_aksi'));
		add_shortcode('detail_pengisian_rencana_aksi', array($plugin_public, 'detail_pengisian_rencana_aksi'));
		add_shortcode('pengisian_rencana_aksi_setting',array($plugin_public,'pengisian_rencana_aksi_setting'));

		add_shortcode('list_input_iku', array($plugin_public, 'list_input_iku'));
		add_shortcode('detail_input_iku', array($plugin_public, 'detail_input_iku'));
		add_shortcode('input_iku_pemda', array($plugin_public, 'input_iku_pemda'));

		add_shortcode('rpjmd', array($plugin_public, 'rpjmd'));
		add_shortcode('rkpd', array($plugin_public, 'rkpd'));
		add_shortcode('lkjip_lppd', array($plugin_public, 'lkjip_lppd'));
		add_shortcode('pohon_kinerja_dan_cascading', array($plugin_public, 'pohon_kinerja_dan_cascading'));
		add_shortcode('lhe_akip_internal', array($plugin_public, 'lhe_akip_internal'));
		add_shortcode('tl_lhe_akip_internal', array($plugin_public, 'tl_lhe_akip_internal'));
		add_shortcode('tl_lhe_akip_kemenpan', array($plugin_public, 'tl_lhe_akip_kemenpan'));
		add_shortcode('laporan_monev_renaksi', array($plugin_public, 'laporan_monev_renaksi'));
		add_shortcode('pedoman_teknis_perencanaan', array($plugin_public, 'pedoman_teknis_perencanaan'));
		add_shortcode('pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja', array($plugin_public, 'pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja'));
		add_shortcode('pedoman_teknis_evaluasi_internal', array($plugin_public, 'pedoman_teknis_evaluasi_internal'));

		add_shortcode('jadwal_rpjmd', array($plugin_public, 'jadwal_rpjmd'));
		add_shortcode('jadwal_rpjpd_sakip', array($plugin_public, 'jadwal_rpjpd_sakip'));

		add_shortcode('pengaturan_menu', array($plugin_public, 'pengaturan_menu'));

		add_shortcode('dokumen_detail_pohon_kinerja_dan_cascading', array($plugin_public, 'dokumen_detail_pohon_kinerja_dan_cascading'));
		add_shortcode('dokumen_detail_lhe_akip_internal', array($plugin_public, 'dokumen_detail_lhe_akip_internal'));
		add_shortcode('dokumen_detail_tl_lhe_akip_internal', array($plugin_public, 'dokumen_detail_tl_lhe_akip_internal'));
		add_shortcode('dokumen_detail_tl_lhe_akip_kemenpan', array($plugin_public, 'dokumen_detail_tl_lhe_akip_kemenpan'));
		add_shortcode('dokumen_detail_dpa', array($plugin_public, 'dokumen_detail_dpa'));
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
		add_shortcode('dokumen_detail_laporan_monev_renaksi', array($plugin_public, 'dokumen_detail_laporan_monev_renaksi'));
		add_shortcode('dokumen_detail_pedoman_teknis_perencanaan', array($plugin_public, 'dokumen_detail_pedoman_teknis_perencanaan'));
		add_shortcode('dokumen_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja', array($plugin_public, 'dokumen_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja'));
		add_shortcode('dokumen_detail_pedoman_teknis_evaluasi_internal', array($plugin_public, 'dokumen_detail_pedoman_teknis_evaluasi_internal'));
		
		add_shortcode('desain_lke_sakip', array($plugin_public, 'desain_lke_sakip'));
		add_shortcode('pengisian_lke_sakip', array($plugin_public, 'pengisian_lke_sakip'));
		add_shortcode('pengisian_lke_sakip_per_skpd', array($plugin_public, 'pengisian_lke_sakip_per_skpd'));

		add_shortcode('format_kke_1', array($plugin_public, 'format_kke_1'));
		add_shortcode('format_kke_2', array($plugin_public, 'format_kke_2'));
		add_shortcode('format_kke_3', array($plugin_public, 'format_kke_3'));
		add_shortcode('format_kke_4', array($plugin_public, 'format_kke_4'));
		add_shortcode('format_kke_5', array($plugin_public, 'format_kke_5'));
		add_shortcode('format_kke_6', array($plugin_public, 'format_kke_6'));

		add_shortcode('menu_depan', array($plugin_public, 'menu_depan'));
		add_shortcode('background_menu', array($plugin_public, 'background_menu'));
		add_shortcode('pohon_kinerja_publish', array($plugin_public, 'pohon_kinerja_publish'));
		add_shortcode('cascading_publish', array($plugin_public, 'cascading_publish'));
		add_shortcode('view_cascading_publish', array($plugin_public, 'view_cascading_publish'));
		add_shortcode('capaian_kinerja_publish', array($plugin_public, 'capaian_kinerja_publish'));
		add_shortcode('capaian_kinerja_pk_publish', array($plugin_public, 'capaian_kinerja_pk_publish'));
		
		add_shortcode('menu_eval_sakip', array($plugin_public, 'menu_eval_sakip'));
		add_shortcode('penyusunan_pohon_kinerja',array($plugin_public,'penyusunan_pohon_kinerja'));
		add_shortcode('view_pohon_kinerja',array($plugin_public,'view_pohon_kinerja'));

		add_shortcode('dokumen_detail_dpa_pemda', array($plugin_public, 'dokumen_detail_dpa_pemda'));
		add_shortcode('dokumen_detail_rencana_aksi_pemda', array($plugin_public, 'dokumen_detail_rencana_aksi_pemda'));
		add_shortcode('dokumen_detail_perjanjian_kinerja_pemda', array($plugin_public, 'dokumen_detail_perjanjian_kinerja_pemda'));
		add_shortcode('dokumen_detail_iku_pemda', array($plugin_public, 'dokumen_detail_iku_pemda'));
		add_shortcode('dokumen_detail_laporan_kinerja_pemda', array($plugin_public, 'dokumen_detail_laporan_kinerja_pemda'));
		add_shortcode('dokumen_detail_dokumen_lainnya_pemda', array($plugin_public, 'dokumen_detail_dokumen_lainnya_pemda'));
		add_shortcode('dokumen_detail_pohon_kinerja_dan_cascading_pemda', array($plugin_public, 'dokumen_detail_pohon_kinerja_dan_cascading_pemda'));
		add_shortcode('dokumen_detail_lhe_akip_internal_pemda', array($plugin_public, 'dokumen_detail_lhe_akip_internal_pemda'));
		add_shortcode('dokumen_detail_tl_lhe_akip_internal_pemda', array($plugin_public, 'dokumen_detail_tl_lhe_akip_internal_pemda'));
		add_shortcode('dokumen_detail_tl_lhe_akip_kemenpan_pemda', array($plugin_public, 'dokumen_detail_tl_lhe_akip_kemenpan_pemda'));
		add_shortcode('dokumen_detail_laporan_monev_renaksi_pemda', array($plugin_public, 'dokumen_detail_laporan_monev_renaksi_pemda'));
		add_shortcode('dokumen_detail_pedoman_teknis_perencanaan_pemda', array($plugin_public, 'dokumen_detail_pedoman_teknis_perencanaan_pemda'));
		add_shortcode('dokumen_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda', array($plugin_public, 'dokumen_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda'));
		add_shortcode('dokumen_detail_pedoman_teknis_evaluasi_internal_pemda', array($plugin_public, 'dokumen_detail_pedoman_teknis_evaluasi_internal_pemda'));
		add_shortcode('dokumen_detail_lkjip_lppd_pemda', array($plugin_public, 'dokumen_detail_lkjip_lppd_pemda'));
		add_shortcode('dokumen_detail_rkpd_pemda', array($plugin_public, 'dokumen_detail_rkpd_pemda'));
		add_shortcode('halaman_laporan_pk_pemda', array($plugin_public, 'halaman_laporan_pk_pemda'));
		add_shortcode('penyusunan_pohon_kinerja_opd', array($plugin_public, 'penyusunan_pohon_kinerja_opd'));
		add_shortcode('view_pohon_kinerja_opd',array($plugin_public,'view_pohon_kinerja_opd'));
		add_shortcode('list_penyusunan_pohon_kinerja_opd', array($plugin_public, 'list_penyusunan_pohon_kinerja_opd'));
		
		add_shortcode('input_rpjpd', array($plugin_public, 'input_rpjpd'));
		add_shortcode('input_rpjmd', array($plugin_public, 'input_rpjmd'));
		add_shortcode('halaman_cek_dokumen', array($plugin_public, 'halaman_cek_dokumen'));
		add_shortcode('cascading_pemda', array($plugin_public, 'cascading_pemda'));
		
		add_shortcode('croscutting_pemda', array($plugin_public, 'crosscutting_pemda'));
		add_shortcode('detail_croscutting_pemda', array($plugin_public, 'detail_crosscutting_pemda'));
		add_shortcode('view_crosscutting_pemda',array($plugin_public,'view_crosscutting_pemda'));

		add_shortcode('cascading_pd', array($plugin_public, 'cascading_pd'));
		add_shortcode('detail_input_cascading_pd', array($plugin_public, 'detail_input_cascading_pd'));

		add_shortcode('halaman_mapping_user_esr', array($plugin_public, 'halaman_mapping_user_esr'));
		add_shortcode('input_rencana_aksi_pemda', array($plugin_public, 'input_rencana_aksi_pemda'));
		add_shortcode('halaman_mapping_jenis_dokumen', array($plugin_public, 'halaman_mapping_jenis_dokumen'));
		
		add_shortcode('tagging_rincian_sakip', array($plugin_public, 'tagging_rincian_sakip'));

		add_shortcode('halaman_mapping_sipd_simpeg', array($plugin_public, 'halaman_mapping_sipd_simpeg'));

		add_shortcode('list_halaman_laporan_pk', array($plugin_public, 'list_halaman_laporan_pk'));
		add_shortcode('detail_laporan_pk', array($plugin_public, 'detail_laporan_pk'));
		add_shortcode('halaman_laporan_pk_setting', array($plugin_public, 'halaman_laporan_pk_setting'));
		add_shortcode('list_pegawai_laporan_pk', array($plugin_public, 'list_pegawai_laporan_pk'));
		add_shortcode('list_perjanjian_kinerja', array($plugin_public, 'list_perjanjian_kinerja'));
		add_shortcode('perjanjian_kinerja_publik', array($plugin_public, 'perjanjian_kinerja_publik'));

		add_shortcode('detail_laporan_rhk', array($plugin_public, 'detail_laporan_rhk'));
		add_shortcode('sso_login', array($plugin_public, 'sso_login'));
		add_shortcode('halaman_lembaga_lainnya', array($plugin_public, 'halaman_lembaga_lainnya'));

		add_shortcode('list_kuesioner_menpan', array($plugin_public, 'list_kuesioner_menpan'));
		add_shortcode('kuesioner_menpan', array($plugin_public, 'kuesioner_menpan'));
		add_shortcode('input_kuesioner_menpan', array($plugin_public, 'input_kuesioner_menpan'));

		add_shortcode('list_kuesioner_mendagri', array($plugin_public, 'list_kuesioner_mendagri'));
		add_shortcode('kuesioner_mendagri', array($plugin_public, 'kuesioner_mendagri'));
		add_shortcode('input_kuesioner_mendagri', array($plugin_public, 'input_kuesioner_mendagri'));

		add_shortcode('jadwal_kuesioner', array($plugin_public, 'jadwal_kuesioner'));

		add_shortcode('list_kuesioner_dokumen', array($plugin_public, 'list_kuesioner_dokumen'));
		add_shortcode('dokumen_detail_kuesioner', array($plugin_public, 'dokumen_detail_kuesioner'));
		add_shortcode('capaian_iku_opd', array($plugin_public, 'capaian_iku_opd'));

		add_shortcode('new_view_pohon_kinerja', array($plugin_public, 'new_view_pohon_kinerja'));
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
