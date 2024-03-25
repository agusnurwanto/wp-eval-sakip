<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/agusnurwanto/
 * @since      1.0.0
 *
 * @package    Wp_Eval_Sakip
 * @subpackage Wp_Eval_Sakip/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Eval_Sakip
 * @subpackage Wp_Eval_Sakip/admin
 * @author     Agus Nurwanto <agusnurwantomuslim@gmail.com>
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Wp_Eval_Sakip_Admin
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
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-eval-sakip-admin.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . 'bootstrap', plugin_dir_url(__FILE__) . 'public/css/bootstrap.min.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-eval-sakip-admin.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . 'bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', array('jquery'), $this->version, false);
		wp_localize_script($this->plugin_name, 'esakip', array(
			'api_key' => get_option(ESAKIP_APIKEY)
		));
	}

	public function get_ajax_field($options = array('type' => null))
	{
		$ret = array();
		$load_ajax_field = Field::make('html', 'crb_load_ajax_field')
			->set_html('
        		<div id="esakip_load_ajax_carbon" data-type="' . $options['type'] . '"></div>
        	');
		$ret[] = $load_ajax_field;
		return $ret;
	}

	public function esakip_load_ajax_carbon()
	{
		global $wpdb;
		$ret = array(
			'status'    => 'success',
			'message'   => ''
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				$tahun = $wpdb->get_results(
					$wpdb->prepare("
                    SELECT 
                        tahun_anggaran 
                    FROM esakip_data_unit 
                    GROUP BY tahun_anggaran
                "),
					ARRAY_A
				);
				foreach ($tahun as $tahun_item) {
					if (!empty($_POST['type']) && $_POST['type'] == 'renja_rkt') {
						$renja_rkt = $this->functions->generatePage(array(
							'nama_page' => 'Halaman Dokumen RENJA/RKT Tahun '. $tahun_item['tahun_anggaran'],
							'content' => '[renja_rkt tahun='.$tahun_item["tahun_anggaran"].']',
							'show_header' => 1,
							'no_key' => 1,
							'post_status' => 'private'
						));
						$body_pemda = '
						<div class="accordion">
							<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
							<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
								<ul style="margin-left: 20px;">
									<li><a target="_blank" href="' . $renja_rkt['url'] . '">' .$renja_rkt['title'].'</a></li>
								</ul>
							</div>
						</div>';
						$ret['message'] .= $body_pemda;
					}
				}
			}
		}
		die(json_encode($ret));
	}


	public function crb_attach_esakip_options()
	{
		global $wpdb;

		$desain_lke_sakip = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Desain LKE SAKIP',
			'content' => '[desain_lke_sakip]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$jadwal_evaluasi = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Jadwal Evaluasi',
			'content' => '[jadwal_evaluasi]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$pengisian_lke_sakip = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Pengisian LKE',
			'content' => '[pengisian_lke_sakip]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$renstra = $this->functions->generatePage(array(
			'nama_page' => 'Halaman RENSTRA',
			'content' => '[renstra]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$renja_rkt = $this->functions->generatePage(array(
			'nama_page' => 'Halaman RENJA RKT',
			'content' => '[renja_rkt]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$perjanjian_kinerja = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Perjanjian Kinerja',
			'content' => '[perjanjian_kinerja]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$rencana_aksi = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Rencana Aksi',
			'content' => '[rencana_aksi]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$iku = $this->functions->generatePage(array(
			'nama_page' => 'Halaman IKU',
			'content' => '[iku]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$skp = $this->functions->generatePage(array(
			'nama_page' => 'Halaman SKP',
			'content' => '[skp]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$pengukuran_kinerja = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Pengukuran Kinerja',
			'content' => '[pengukuran_kinerja]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$pengukuran_rencana_aksi = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Pengukuran Rencana Aksi',
			'content' => '[pengukuran_rencana_aksi]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$laporan_kinerja = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Laporan Kinerja',
			'content' => '[laporan_kinerja]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$evaluasi_internal = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Evaluasi Internal',
			'content' => '[evaluasi_internal]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$dokumen_lainnya = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Dokumen Lainnya',
			'content' => '[dokumen_lainnya]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$rpjmd = $this->functions->generatePage(array(
			'nama_page' => 'Halaman RPJMD',
			'content' => '[rpjmd]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$rkpd = $this->functions->generatePage(array(
			'nama_page' => 'Halaman RKPD',
			'content' => '[rkpd]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$lkjip_lppd = $this->functions->generatePage(array(
			'nama_page' => 'Halaman LKJIP/LPPD',
			'content' => '[lkjip_lppd]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$dokumen_pemda_lainnya = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Dokumen Lainnya',
			'content' => '[dokumen_pemda_lainnya]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$basic_options_container = Container::make('theme_options', __('E-SAKIP Options'))
			->set_page_menu_position(3)
			->add_fields(array(
				Field::make('html', 'crb_esakip_halaman_terkait')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            	</ol>'),
				Field::make('text', 'crb_apikey_esakip', 'API KEY')
					->set_default_value($this->functions->generateRandomString())
					->set_help_text('Wajib diisi. API KEY digunakan untuk integrasi data.'),
				Field::make('html', 'crb_sql_migrate')
					->set_html('<a onclick="sql_migrate_esakip(); return false;" href="#" class="button button-primary button-large">SQL Migrate</a>')
					->set_help_text('Tombol untuk memperbaiki struktur database E-SAKIP.'),
			));

		Container::make('theme_options', __('Pengaturan Perangkat Daerah'))
			->set_page_parent($basic_options_container)
			->add_fields(array(
				Field::make('text', 'crb_url_server_sakip', 'URL Server WP-SIPD')
					->set_default_value(admin_url('admin-ajax.php'))
					->set_required(true),
				Field::make('text', 'crb_apikey_wpsipd', 'API KEY WP-SIPD')
					->set_default_value($this->functions->generateRandomString())
					->set_help_text('Wajib diisi. API KEY digunakan untuk integrasi data.'),
				Field::make('text', 'crb_tahun_wpsipd', 'Tahun Anggaran WP-SIPD')
					->set_default_value(date('Y'))
					->set_help_text('Wajib diisi.'),
				Field::make('html', 'crb_html_data_unit')
					->set_html('<a href="#" class="button button-primary" onclick="get_data_unit_wpsipd(); return false;">Tarik Data Unit dari WP SIPD</a>')
					->set_help_text('Tombol untuk menarik data Unit dari WP SIPD.'),
				Field::make('html', 'crb_generate_user')
					->set_html('<a id="generate_user" onclick="return false;" href="#" class="button button-primary button-large">Generate User By DB Lokal</a>')
					->set_help_text('Data user active yang ada di table data unit akan digenerate menjadi user wordpress.'),
			));
		Container::make('theme_options', __('Desain LKE SAKIP'))
			->set_page_parent($basic_options_container)
			->add_fields(array(
				Field::make('html', 'crb_desain_lke_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_desain_lke')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $desain_lke_sakip['url'] . '">' . $desain_lke_sakip['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('Jadwal Evaluasi'))
			->set_page_parent($basic_options_container)
			->add_fields(array(
				Field::make('html', 'crb_jadwal_evaluasi_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_jadwal_evaluasi')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $jadwal_evaluasi['url'] . '">' . $jadwal_evaluasi['title'] . '</a></li>
	            	</ol>
		        	')
			));

		$pengisian_lke_menu = Container::make('theme_options', __('Pengisian LKE SAKIP'))
			->set_page_menu_position(3.1)
			->set_icon('dashicons-edit-page')
			->add_fields(array(
				Field::make('html', 'crb_pengisian_lke_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_pengisian_lke')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $pengisian_lke_sakip['url'] . '">' . $pengisian_lke_sakip['title'] . '</a></li>
	            	</ol>
		        	'),
				Field::make('select', 'crb_jadwal_pengisian_lke', 'Jadwal Pengisian LKE')
					->add_options(array(
						'option1' => 'Option 1',
						'option2' => 'Option 2',
						'option3' => 'Option 3',
					))
					->set_width(50)
			));

		$dokumen_menu = Container::make('theme_options', __('Dokumen'))
			->set_page_menu_position(3.2)
			->set_icon('dashicons-media-default');

		Container::make('theme_options', __('RENSTRA'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_renstra_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_renstra')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $renstra['url'] . '">' . $renstra['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('RENJA/RKT'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_renja_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'renja_rkt')));
		Container::make('theme_options', __('Perjanjian Kinerja'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_perjanjian_kinerja_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_perjanjian_kinerja')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $perjanjian_kinerja['url'] . '">' . $perjanjian_kinerja['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('Rencana Aksi'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_rencana_aksi_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_rencana_aksi')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $rencana_aksi['url'] . '">' . $rencana_aksi['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('IKU'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_iku_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_iku')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $iku['url'] . '">' . $iku['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('SKP'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_skp_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_skp')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $skp['url'] . '">' . $skp['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('Pengukuran Kinerja'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengukuran_kinerja_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_pengukuran_kinerja')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $pengukuran_kinerja['url'] . '">' . $pengukuran_kinerja['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('Pengukuran Rencana Aksi'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengukuran_rencana_aksi_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_pengukuran_rencana_aksi')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $pengukuran_rencana_aksi['url'] . '">' . $pengukuran_rencana_aksi['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('Laporan Kinerja'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_laporan_kinerja_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_laporan_kinerja')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $laporan_kinerja['url'] . '">' . $laporan_kinerja['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('Evaluasi Internal'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_evaluasi_internal_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_evaluasi_internal')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $evaluasi_internal['url'] . '">' . $evaluasi_internal['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('Dokumen Lainnya'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_dokumen_lainnya_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_dokumen_lainnya')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $dokumen_lainnya['url'] . '">' . $dokumen_lainnya['title'] . '</a></li>
	            	</ol>
		        	')
			));

		$dokumen_pemda_menu = Container::make('theme_options', __('Dokumen Pemda'))
			->set_page_menu_position(3.3)
			->set_icon('dashicons-bank');

		Container::make('theme_options', __('RPJMD'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_rpjmd_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_rpjmd')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $rpjmd['url'] . '">' . $rpjmd['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('RKPD'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_rkpd_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_rkpd')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $rkpd['url'] . '">' . $rkpd['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('LKJIP/LPPD'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_lkjip_lppd_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_lkjip_lppd')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $lkjip_lppd['url'] . '">' . $lkjip_lppd['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('Dokumen Lainnya'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_dokumen_pemda_lainnya_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	'),
				Field::make('html', 'crb_siks_halaman_terkait_dokumen_pemda_lainnya')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $dokumen_pemda_lainnya['url'] . '">' . $dokumen_pemda_lainnya['title'] . '</a></li>
	            	</ol>
		        	')
			));
	}

	function sql_migrate_esakip()
	{
		global $wpdb;
		$ret = array(
			'status'	=> 'success',
			'message'	=> 'Berhasil menjalankan SQL migrate!'
		);
		$file = 'table.sql';
		$ret['value'] = $file . ' (tgl: ' . date('Y-m-d H:i:s') . ')';
		$path = ESAKIP_PLUGIN_PATH . '/' . $file;
		if (file_exists($path)) {
			$sql = file_get_contents($path);
			$ret['sql'] = $sql;
			if ($file == 'table.sql') {
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				$wpdb->hide_errors();
				$rows_affected = dbDelta($sql);
				if (empty($rows_affected)) {
					$ret['status'] = 'error';
					$ret['message'] = $wpdb->last_error;
				} else {
					$ret['message'] = implode(' | ', $rows_affected);
				}
			} else {
				$wpdb->hide_errors();
				$res = $wpdb->query($sql);
				if (empty($res)) {
					$ret['status'] = 'error';
					$ret['message'] = $wpdb->last_error;
				} else {
					$ret['message'] = $res;
				}
			}
			if ($ret['status'] == 'success') {
				$ret['version'] = $this->version;
				update_option('_last_update_sql_migrate_esakip', $ret['value']);
				update_option('_wp_sipd_db_version_esakip', $this->version);
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'File ' . $path . ' tidak ditemukan!';
		}
		die(json_encode($ret));
	}

	function gen_user_sipd_merah($user = array(), $update_pass = false)
	{
		global $wpdb;
		if (!empty($user)) {
			$username = $user['loginname'];
			if (!empty($user['emailteks'])) {
				$email = $user['emailteks'];
			} else {
				$email = $username . '@sipdlocal.com';
			}
			$role = get_role($user['jabatan']);
			if (empty($role)) {
				add_role($user['jabatan'], $user['jabatan'], array(
					'read' => true,
					'edit_posts' => false,
					'delete_posts' => false
				));
			}
			$insert_user = username_exists($username);
			if (!$insert_user) {
				$option = array(
					'user_login' => $username,
					'user_pass' => $user['pass'],
					'user_email' => $email,
					'first_name' => $user['nama'],
					'display_name' => $user['nama'],
					'role' => $user['jabatan']
				);
				$insert_user = wp_insert_user($option);

				if (is_wp_error($insert_user)) {
					//print_r($option);die();
					return $insert_user;
				}
			}

			if (!empty($update_pass)) {
				wp_set_password($user['pass'], $insert_user);
			}

			$meta = array(
				'_nip' => $user['nip'],
				'description' => 'User dibuat dari data SIPD Merah'
			);
			if (!empty($user['id_sub_skpd'])) {
				$skpd = $wpdb->get_var(
					$wpdb->prepare("
						SELECT nama_skpd 
						FROM esakip_data_unit 
						WHERE id_skpd=" . $user['id_sub_skpd'] . " 
						  AND active=1")
				);
				$meta['_crb_nama_skpd'] = $skpd;
				$meta['_id_sub_skpd'] = $user['id_sub_skpd'];
			}
			if (!empty($user['iduser'])) {
				$meta['id_user_sipd'] = $user['iduser'];
			}
			foreach ($meta as $key => $val) {
				update_user_meta($insert_user, $key, $val);
			}
		}
	}

	function generate_user_sipd_merah()
	{
		global $wpdb;
		$ret = array();
		$ret['status'] = 'success';
		$ret['message'] = 'Berhasil Generate User Wordpress dari DB Lokal SIPD Merah';
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				$users_pa = $wpdb->get_results(
					$wpdb->prepare("
						SELECT 
						* 
						FROM data_unit 
						WHERE active=1
						"),
					ARRAY_A
				);
				$update_pass = false;
				if (
					!empty($_POST['update_pass'])
					&& $_POST['update_pass'] == 'true'
				) {
					$update_pass = true;
				}
				if (!empty($users_pa)) {
					foreach ($users_pa as $k => $user) {
						$user['pass'] = $_POST['pass'];
						$user['loginname'] = $user['nipkepala'];
						$user['jabatan'] = $user['statuskepala'];
						$user['nama'] = $user['namakepala'];
						$user['id_sub_skpd'] = $user['id_skpd'];
						$user['nip'] = $user['nipkepala'];
						$this->gen_user_sipd_merah($user, $update_pass);
					}
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Data user PA/KPA kosong. Harap lakukan singkronisasi data SKPD dulu!';
				}
			} else {
				$ret['status'] = 'error';
				$ret['message'] = 'APIKEY tidak sesuai!';
			}
		} else {
			$ret['status'] = 'error';
			$ret['message'] = 'Format Salah!';
		}
		die(json_encode($ret));
	}

	function get_data_unit_wpsipd()
	{
		global $wpdb;

		if (empty($_POST['server'])) {
			$data = array(
				'status' => 'error',
				'message' => 'URL Server Tidak Boleh Kosong'
			);
			$response = json_encode($data);
			die($response);
		}else if (empty($_POST['tahun_anggaran'])) {
			$data = array(
				'status' => 'error',
				'message' => 'Tahun Tidak Boleh Kosong'
			);
			$response = json_encode($data);
			die($response);
		}else if (empty($_POST['api_key'])) {
			$data = array(
				'status' => 'error',
				'message' => 'API Key Tidak Boleh Kosong'
			);
			$response = json_encode($data);
			die($response);
		}

		// data to send in our API request
		$api_params = array(
			'action' => 'get_skpd',
			'api_key'	=> $_POST['api_key'],
			'tahun_anggaran' => $_POST['tahun_anggaran']
		);

		$response = wp_remote_post($_POST['server'], array('timeout' => 10, 'sslverify' => false, 'body' => $api_params));

		$response = wp_remote_retrieve_body($response);

		$data = json_decode($response);

		$esakip_data_unit = $data->data;

		if ($data->status == 'success' && !empty($esakip_data_unit)) {
			$wpdb->update('esakip_data_unit', array('active' => 0), array('tahun_anggaran' => $api_params['tahun_anggaran']));
			foreach ($esakip_data_unit as $vdata) {
				$cek = $wpdb->get_var($wpdb->prepare(
					'
					select 
						id 
					from esakip_data_unit 
					where id_skpd = %d
						and tahun_anggaran = %d',
					$vdata->id_skpd,
					$vdata->tahun_anggaran
				));
				$opsi = array(
					'id_setup_unit' => $vdata->id_setup_unit,
					'id_unit' => $vdata->id_unit,
					'is_skpd' => $vdata->is_skpd,
					'kode_skpd' => $vdata->kode_skpd,
					'kunci_skpd' => $vdata->kunci_skpd,
					'nama_skpd' => $vdata->nama_skpd,
					'posisi' => $vdata->posisi,
					'status' => $vdata->status,
					'id_skpd' => $vdata->id_skpd,
					'bidur_1' => $vdata->bidur_1,
					'bidur_2' => $vdata->bidur_2,
					'bidur_3' => $vdata->bidur_3,
					'idinduk' => $vdata->idinduk,
					'ispendapatan' => $vdata->ispendapatan,
					'isskpd' => $vdata->isskpd,
					'kode_skpd_1' => $vdata->kode_skpd_1,
					'kode_skpd_2' => $vdata->kode_skpd_2,
					'kodeunit' => $vdata->kodeunit,
					'komisi' => $vdata->komisi,
					'namabendahara' => $vdata->namabendahara,
					'namakepala' => $vdata->namakepala,
					'namaunit' => $vdata->namaunit,
					'nipbendahara' => $vdata->nipbendahara,
					'nipkepala' => $vdata->nipkepala,
					'pangkatkepala' => $vdata->pangkatkepala,
					'setupunit' => $vdata->setupunit,
					'statuskepala' => $vdata->statuskepala,
					'update_at' => $vdata->update_at,
					'tahun_anggaran' => $vdata->tahun_anggaran,
					'active' => $vdata->active
				);
				if (empty($cek)) {
					$wpdb->insert('esakip_data_unit', $opsi);
				} else {
					$wpdb->update('esakip_data_unit', $opsi, array('id' => $cek));
				}
			}
		}

		$response = json_encode($data);

		die($response);
	}
}