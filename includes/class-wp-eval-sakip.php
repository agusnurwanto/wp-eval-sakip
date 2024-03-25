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
		$this->loader->add_action('wp_ajax_gen_user_sipd_merah', $plugin_admin, 'gen_user_sipd_merah');
		$this->loader->add_action('wp_ajax_load_ajax_carbon', $plugin_admin, 'load_ajax_carbon');
		$this->loader->add_action('wp_ajax_get_data_unit', $plugin_admin, 'get_data_unit');
		$this->loader->add_action('wp_ajax_get_data_unit_wpsipd', $plugin_admin, 'get_data_unit_wpsipd');
		$this->loader->add_action('wp_ajax_get_api_param_wpsipd', $plugin_admin, 'get_api_param_wpsipd');
		
		
		$this->loader->add_action('carbon_fields_register_fields', $plugin_admin, 'crb_attach_esakip_options');
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

		add_shortcode('desain_lke_sakip', array($plugin_public, 'desain_lke_sakip'));
		add_shortcode('jadwal_evaluasi', array($plugin_public, 'jadwal_evaluasi'));
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
