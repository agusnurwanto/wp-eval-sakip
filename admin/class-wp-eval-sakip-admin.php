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
		wp_localize_script($this->plugin_name, 'esakip', array(
			'api_key' => get_option(ESAKIP_APIKEY)
		));
	}

	public function crb_attach_esakip_options()
	{
		$basic_options_container = Container::make('theme_options', 'E-SAKIP Options')
			->set_page_menu_position(3)
			->add_tab('📄 Halaman Terkait', $this->generate_fields_options_halaman_terkait())
			->add_tab('⚙️ Konfigurasi Umum', $this->generate_fields_options_konfigurasi_umum())
			->add_tab('🏛️ Identitas Pemda', $this->generate_fields_options_identitas_pemda())
			->add_tab('🔌 API SIMPEG', $this->generate_fields_options_api_simpeg())
			->add_tab('🔌 API ESR', $this->generate_fields_options_api_esr())
			->add_tab('🔌 API E-Kinerja', $this->generate_fields_options_api_ekin());

		Container::make('theme_options', __('Pengaturan Perangkat Daerah'))
			->set_page_parent($basic_options_container)
			->add_tab('🏢 Perangkat Daerah', $this->generate_fields_perangkat_daerah_options())
			->add_tab('🏦 Lembaga Lainnya', $this->generate_fields_lembaga_lainnya_options())
			->add_tab('🗃️ Mapping Jenis Dokumen', $this->generate_fields_mapping_jenis_dokumen_options());

		Container::make('theme_options', __('Jadwal'))
			->set_page_parent($basic_options_container)
			->add_tab('🏙️ RPJPD dan RPJMD/RPD/Renstra', $this->generate_fields_jadwal('rpjpd_rpjmd'))
			->add_tab('📋 LKE (Lembar Kerja Evaluasi)', $this->generate_fields_jadwal('lke'))
			->add_tab('📄 Verifikasi Upload Dokumen', $this->generate_fields_jadwal('upload_dokumen'));

		Container::make('theme_options', __('Menu Setting'))
			->set_page_parent($basic_options_container)
			->add_fields($this->generate_fields_options_menu_settings());

		Container::make('theme_options', __('Tampilan Beranda'))
			->set_page_parent($basic_options_container)
			->add_tab(__('🏠 Frontpage / Halaman Depan'), $this->generate_fields_front_page())
			->add_tab(__('👤 Menu Profile User'), $this->generate_fields_profile_user());

		Container::make('theme_options', __('Auto Login'))
			->set_page_parent($basic_options_container)
			->add_fields($this->generate_fields_options_auto_login());

		$dokumen_pemda_menu = Container::make('theme_options', __('Dokumen Pemda'))
			->set_page_menu_position(3.1)
			->set_icon('dashicons-bank')
			->add_fields(array(
				Field::make('html', 'crb_renstra_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	')
			));

		Container::make('theme_options', __('RPJPD'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_rpjpd_pemda());

		Container::make('theme_options', __('RPJMD'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_rpjmd_pemda());

		Container::make('theme_options', __('IKU'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_iku_pemda());

		Container::make('theme_options', __('RKPD'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_rkpd_pemda());

		Container::make('theme_options', __('Perjanjian Kinerja'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_pk_pemda());

		Container::make('theme_options', __('Laporan Kinerja'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_laporan_kinerja_pemda());

		Container::make('theme_options', __('DPA'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_dpa_pemda());

		Container::make('theme_options', __('Pohon Kinerja dan Cascading'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_pokin_cascading_pemda());

		Container::make('theme_options', __('LHE AKIP Internal'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_lhe_internal_pemda());

		Container::make('theme_options', __('TL LHE AKIP Internal'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_tl_lhe_internal_pemda());

		Container::make('theme_options', __('TL LHE AKIP Kemenpan'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_tl_lhe_kemenpan_pemda());

		Container::make('theme_options', __('Laporan Monev Renaksi'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_monev_renaksi_pemda());

		Container::make('theme_options', __('Pedoman Teknis Perencanaan'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_pedoman_teknis_perencanaan_pemda());

		Container::make('theme_options', __('Pedoman Teknis Pengukuran dan Pengumpulan Data Kinerja'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_pedoman_teknis_pengukuran_pengumpulan_kinerja_pemda());

		Container::make('theme_options', __('Pedoman Teknis Evaluasi Internal'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_pedoman_teknis_evaluasi_internal_pemda());

		Container::make('theme_options', __('Rencana Aksi'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_renaksi_pemda());

		Container::make('theme_options', __('LKJIP/LPPD'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_lkjip_pemda());

		Container::make('theme_options', __('Dokumen Lainnya'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields($this->generate_fields_dokumen_lain_pemda());

		$dokumen_menu = Container::make('theme_options', __('Dokumen Perangkat Daerah'))
			->set_page_menu_position(3.2)
			->set_icon('dashicons-media-default')
			->add_fields(array(
				Field::make('html', 'crb_renstra_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	')
			));

		Container::make('theme_options', __('RENSTRA'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_renstra_opd());

		Container::make('theme_options', __('IKU'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_iku_opd());

		Container::make('theme_options', __('RENJA/RKT'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_renja_opd());

		Container::make('theme_options', __('Perjanjian Kinerja'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_pk_opd());

		Container::make('theme_options', __('Laporan Kinerja'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_laporan_kinerja_opd());

		Container::make('theme_options', __('DPA'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_dpa_opd());

		Container::make('theme_options', __('Pohon Kinerja dan Cascading'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_pokin_cascading_opd());

		Container::make('theme_options', __('LHE AKIP Internal'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_lhe_internal_opd());

		Container::make('theme_options', __('TL LHE AKIP Internal'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_tl_lhe_internal_opd());

		Container::make('theme_options', __('Laporan Monev Renaksi'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_laporan_monev_renaksi_opd());

		Container::make('theme_options', __('Rencana Aksi'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_renaksi_opd());

		Container::make('theme_options', __('SKP'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_skp_opd());

		Container::make('theme_options', __('Evaluasi Internal'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_evaluasi_internal_opd());

		Container::make('theme_options', __('Dokumen Lainnya'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->generate_fields_dokumen_dokumen_lain_opd());

		Container::make('theme_options', __('Pengisian LKE SAKIP'))
			->set_page_menu_position(3.3)
			->set_icon('dashicons-edit-page')
			->add_fields($this->generate_fields_pengisian_lke_sakip());

		$pengisian_pokin_menu = Container::make('theme_options', __('Pohon Kinerja'))
			->set_page_menu_position(3.4)
			->set_icon('dashicons-networking');

		Container::make('theme_options', __('Pohon Kinerja Pemerintah Daerah'))
			->set_page_parent($pengisian_pokin_menu)
			->add_fields($this->generate_fields_pokin_pemda());

		Container::make('theme_options', __('Cascading Pemerintah Daerah'))
			->set_page_parent($pengisian_pokin_menu)
			->add_fields($this->generate_fields_cascading_pemda());

		Container::make('theme_options', __('Crosscutting Pemerintah Daerah'))
			->set_page_parent($pengisian_pokin_menu)
			->add_fields($this->generate_fields_crosscutting_pemda());

		Container::make('theme_options', __('Pohon Kinerja Perangkat Daerah'))
			->set_page_parent($pengisian_pokin_menu)
			->add_fields($this->generate_fields_pokin_opd());

		Container::make('theme_options', __('Cascading Perangkat Daerah'))
			->set_page_parent($pengisian_pokin_menu)
			->add_fields($this->generate_fields_cascading_opd());

		$monev_renaksi_menu = Container::make('theme_options', __('Rencana Aksi'))
			->set_page_menu_position(3.5)
			->set_icon('dashicons-analytics');

		Container::make('theme_options', __('Rencana Aksi Pemerintah Daerah'))
			->set_page_parent($monev_renaksi_menu)
			->add_fields($this->generate_fields_renaksi_pemda());

		Container::make('theme_options', __('Rencana Aksi Perangkat Daerah'))
			->set_page_parent($monev_renaksi_menu)
			->add_fields($this->generate_fields_renaksi_opd());

		Container::make('theme_options', __('Rencana Aksi Setting'))
			->set_page_parent($monev_renaksi_menu)
			->add_fields($this->generate_fields_renaksi_settings());

		$input_iku_menu = Container::make('theme_options', __('Input IKU'))
			->set_page_menu_position(3.6)
			->set_icon('dashicons-edit-page');

		Container::make('theme_options', __('Input IKU Pemerintah Daerah'))
			->set_page_parent($input_iku_menu)
			->add_fields($this->generate_fields_input_iku_pemda());

		Container::make('theme_options', __('Input IKU Perangkat Daerah'))
			->set_page_parent($input_iku_menu)
			->add_fields($this->generate_fields_input_iku_opd());

		Container::make('theme_options', __('Input RPJPD'))
			->set_page_menu_position(3.7)
			->set_icon('dashicons-welcome-write-blog')
			->add_fields($this->generate_fields_input_rpjpd());

		Container::make('theme_options', __('Input RPJMD'))
			->set_page_menu_position(3.8)
			->set_icon('dashicons-welcome-write-blog')
			->add_fields($this->generate_fields_input_rpjmd());

		$laporan_pk_menu = Container::make('theme_options', __('Laporan PK'))
			->set_page_menu_position(3.9)
			->set_icon('dashicons-media-default');

		Container::make('theme_options', __('Laporan PK Pemerintah Daerah'))
			->set_page_parent($laporan_pk_menu)
			->add_fields($this->generate_fields_laporan_pk_pemda());

		Container::make('theme_options', __('Laporan PK Perangkat Daerah'))
			->set_page_parent($laporan_pk_menu)
			->add_fields($this->generate_fields_laporan_pk_opd());

		$kuesioner = Container::make('theme_options', __('Kuesioner'))
			->set_page_menu_position(3.10)
			->set_icon('dashicons-media-default')
			->add_fields($this->generate_fields_kuesioner());

		Container::make('theme_options', __('Kuesioner Menpan'))
			->set_page_parent($kuesioner)
			->add_fields($this->generate_fields_kuesioner_menpan());

		Container::make('theme_options', __('Kuesioner Mendagri'))
			->set_page_parent($kuesioner)
			->add_fields($this->generate_fields_kuesioner_mendagri());

		Container::make('theme_options', __('Dokumen Kuesioner'))
			->set_page_parent($kuesioner)
			->add_fields($this->generate_fields_dokumen_kuesioner());
	}

	function get_jadwal_renstra_wp_sipd()
	{
		if (empty(get_option(ESAKIP_APIKEY_WPSIPD)) && empty(get_option(ESAKIP_URL_WPSIPD))) {
			return [];
		}

		$api_params = array(
			'action' 	=> 'get_data_jadwal_wpsipd',
			'api_key'	=> get_option(ESAKIP_APIKEY_WPSIPD),
			'tipe_perencanaan' => 'monev_renstra'
		);

		$response = wp_remote_post(
			get_option(ESAKIP_URL_WPSIPD),
			array(
				'timeout' 	=> 1000,
				'sslverify' => false,
				'body' 		=> $api_params
			)
		);

		$response = wp_remote_retrieve_body($response);

		return json_decode($response);
	}

	function get_rpjpd_settings()
	{
		global $wpdb;

		$data = $wpdb->get_results(
			$wpdb->prepare("
				SELECT 
					j.id,
					j.nama_jadwal,
					j.nama_jadwal_renstra,
					j.tahun_anggaran,
					j.lama_pelaksanaan,
					j.tahun_selesai_anggaran,
					j.status
				FROM esakip_data_jadwal j
				INNER JOIN esakip_pengaturan_upload_dokumen r 
				   		ON r.id_jadwal_rpjpd = j.id
				WHERE j.tipe = %s
				  AND j.status != 0
				GROUP BY j.id
				ORDER BY j.tahun_anggaran DESC
			", 'RPJPD'),
			ARRAY_A
		);

		return $data;
	}

	function get_rpjmd_settings()
	{
		global $wpdb;

		$data = $wpdb->get_results(
			$wpdb->prepare("
				SELECT 
					j.id,
					j.nama_jadwal,
					j.nama_jadwal_renstra,
					j.tahun_anggaran,
					j.lama_pelaksanaan,
					j.tahun_selesai_anggaran,
					j.status,
					r.id_jadwal_rpjmd,
					r.tahun_anggaran as tahun_anggaran_setting
				FROM esakip_data_jadwal j
				LEFT JOIN esakip_pengaturan_upload_dokumen r
					   ON r.id_jadwal_rpjmd = j.id
				WHERE j.tipe = %s
				  AND j.status != 0
				ORDER BY j.tahun_anggaran DESC
			", 'RPJMD'),
			ARRAY_A
		);

		return $data;
	}

	function get_rpjmd()
	{
		global $wpdb;

		$data = $wpdb->get_results(
			$wpdb->prepare("
				SELECT 
					id,
					nama_jadwal,
					nama_jadwal_renstra,
					tahun_anggaran,
					status,
					lama_pelaksanaan,
					jenis_jadwal_khusus,
					tahun_selesai_anggaran
				FROM esakip_data_jadwal
				WHERE tipe = %s
				  AND status != 0
				ORDER BY tahun_anggaran DESC
			", 'RPJMD'),
			ARRAY_A
		);

		return $data;
	}

	function get_tahun()
	{

		global $wpdb;
		$data = $wpdb->get_results('
			SELECT 
				tahun_anggaran 
			FROM esakip_data_unit 
			GROUP BY tahun_anggaran 
			ORDER BY tahun_anggaran DESC', ARRAY_A);

		return $data;
	}

	function get_jadwal_evaluasi()
	{
		global $wpdb;
		$data = $wpdb->get_results(
			$wpdb->prepare("
				SELECT 
					id,
					nama_jadwal,
					tahun_anggaran,
					lama_pelaksanaan,
					status
				FROM esakip_data_jadwal
				WHERE tipe = %s
				  AND status != 0
				ORDER BY tahun_anggaran DESC, status ASC
			", 'LKE'),
			ARRAY_A
		);

		return $data;
	}

	//GENERATE FIELDS MENU DOKUMEN PEMDA
	public function generate_fields_dokumen_rpjpd_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_rpjpd.php') {
			return array();
		}

		$rpjpd_settings = $this->get_rpjpd_settings();

		$html = '';
		if (!empty($rpjpd_settings)) {
			foreach ($rpjpd_settings as $v) {
				$tahun_anggaran_selesai = $v['tahun_anggaran'] + $v['lama_pelaksanaan'];
				$dokumen_rpjpd_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Dokumen RPJPD | ' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )',
					'content' 		=> '[upload_dokumen_rpjpd periode=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $dokumen_rpjpd_page['url'] . '">' . $dokumen_rpjpd_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RPJPD Settings tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_rpjpd_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_rpjpd_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_rpjmd_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_rpjmd.php') {
			return array();
		}

		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$dokumen_rpjmd_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Dokumen RPJMD | ' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ' )',
					'content' 		=> '[upload_dokumen_rpjmd periode=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));
				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $dokumen_rpjmd_page['url'] . '">' . $dokumen_rpjmd_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
			<li>
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RPJMD tidak tersedia
				</span>
			</li>';
		}

		return [
			Field::make('html', 'crb_dokumen_rpjmd_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_rpjmd_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_iku_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_iku.php') {
			return array();
		}

		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$dokumen_iku_pemda_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'IKU Pemerintah Daerah | ' . $v['nama_jadwal'] . ' (' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ')',
					'content' 		=> '[dokumen_detail_iku_pemda id_jadwal=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $dokumen_iku_pemda_page['url'] . '">' . $dokumen_iku_pemda_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
			<li>
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RPJMD tidak tersedia
				</span>
			</li>';
		}

		return [
			Field::make('html', 'crb_pengisian_lke_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_pengisian_lke_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_rkpd_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_rkpd.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$dokumen_rkpd_page = $this->functions->generatePage(array(
					'nama_page'    => 'Halaman RKPD Tahun ' . $v['tahun_anggaran'],
					'content' 	   => '[rkpd tahun=' . $v["tahun_anggaran"] . ']',
					'show_header'  => 1,
					'no_key' 	   => 1,
					'post_status'  => 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $dokumen_rkpd_page['url'] . '">' . $dokumen_rkpd_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_rkpd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_rkpd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_pk_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_perjanjian_kinerja.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$dokumen_perjanjian_kinerja_pemda_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Pemda Perjanjian Kinerja Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_detail_perjanjian_kinerja_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $dokumen_perjanjian_kinerja_pemda_page['url'] . '">' . $dokumen_perjanjian_kinerja_pemda_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_pk_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_pk_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_laporan_kinerja_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_laporan_kinerja.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$laporan_kinerja_pemda_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Pemda Laporan Kinerja Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_detail_laporan_kinerja_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $laporan_kinerja_pemda_page['url'] . '">' . $laporan_kinerja_pemda_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_laporan_kinerja_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_laporan_kinerja_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_dpa_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_dpa.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$dokumen_dpa_pemda_page = $this->functions->generatePage(array(
					'nama_page' => 'Halaman Dokumen Pemda DPA Tahun ' . $v['tahun_anggaran'],
					'content' => '[dokumen_detail_dpa_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' => 1,
					'no_key' => 1,
					'post_status' => 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $dokumen_dpa_pemda_page['url'] . '">' . $dokumen_dpa_pemda_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dpa_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dpa_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_pokin_cascading_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_pohon_kinerja_dan_cascading.php') {
			return array();
		}

		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$dokumen_pohon_kinerja_cascading_pemda_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Dokumen Pemda Pohon Kinerja dan Cascading | ' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ' )',
					'content' 		=> '[dokumen_detail_pohon_kinerja_dan_cascading_pemda periode=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $dokumen_pohon_kinerja_cascading_pemda_page['url'] . '">' . $dokumen_pohon_kinerja_cascading_pemda_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RPJMD tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_pokin_cascading_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_pokin_cascading_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_lhe_internal_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_lhe_akip_internal.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$dokumen_lhe_akip_internal_pemda_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Pemda LHE AKIP Internal Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_detail_lhe_akip_internal_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $dokumen_lhe_akip_internal_pemda_page['url'] . '">' . $dokumen_lhe_akip_internal_pemda_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_lhe_internal_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_lhe_internal_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_tl_lhe_internal_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_tl_lhe_akip_internal.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$tl_lhe_akip_internal_pemda = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Pemda TL LHE AKIP Internal Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_detail_tl_lhe_akip_internal_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $tl_lhe_akip_internal_pemda['url'] . '">' . $tl_lhe_akip_internal_pemda['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_tl_lhe_internal_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_tl_lhe_internal_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_tl_lhe_kemenpan_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_tl_lhe_akip_kemenpan.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$tl_lhe_akip_kemenpan_pemda = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Pemda TL LHE AKIP Kemenpan Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_detail_tl_lhe_akip_kemenpan_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $tl_lhe_akip_kemenpan_pemda['url'] . '">' . $tl_lhe_akip_kemenpan_pemda['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_tl_lhe_kemenpan_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_tl_lhe_kemenpan_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_monev_renaksi_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_laporan_monev_renaksi.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$laporan_monev_renaksi_pemda = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Pemda Monev Renaksi Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_detail_laporan_monev_renaksi_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $laporan_monev_renaksi_pemda['url'] . '">' . $laporan_monev_renaksi_pemda['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_monev_renaksi_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_monev_renaksi_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_pedoman_teknis_perencanaan_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_pedoman_teknis_perencanaan.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$pedoman_teknis_perencanaan_pemda = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Pemda Pedoman Teknis Perencanaan Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_detail_pedoman_teknis_perencanaan_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $pedoman_teknis_perencanaan_pemda['url'] . '">' . $pedoman_teknis_perencanaan_pemda['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_pedoman_teknis_perencanaan_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_pedoman_teknis_perencanaan_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_pedoman_teknis_pengukuran_pengumpulan_kinerja_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja['url'] . '">' . $pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_pengisian_lke_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_pengisian_lke_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_pedoman_teknis_evaluasi_internal_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_pedoman_teknis_evaluasi_internal.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$pedoman_teknis_evaluasi_internal_pemda = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Pemda Pedoman Teknis Evaluasi Internal Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_detail_pedoman_teknis_evaluasi_internal_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $pedoman_teknis_evaluasi_internal_pemda['url'] . '">' . $pedoman_teknis_evaluasi_internal_pemda['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_pedoman_teknis_evaluasi_internal_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_pedoman_teknis_evaluasi_internal_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_renaksi_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_rencana_aksi.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$rencana_aksi_pemda = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Pemda Rencana Aksi Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_detail_rencana_aksi_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $rencana_aksi_pemda['url'] . '">' . $rencana_aksi_pemda['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_rencana_aksi_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_rencana_aksi_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_lkjip_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_lkjiplppd.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$lkjip_lppd_pemda = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Pemda LKJIP/LPPD  ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_detail_lkjip_lppd_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $lkjip_lppd_pemda['url'] . '">' . $lkjip_lppd_pemda['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_lkjip_lppd_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_lkjip_lppd_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_lain_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_dokumen_lainnya.php') {
			return array();
		}

		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$dokumen_lainnya_pemda = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Lainnya Pemda Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_detail_dokumen_lainnya_pemda tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $dokumen_lainnya_pemda['url'] . '">' . $dokumen_lainnya_pemda['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_lainnya_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_lainnya_pemda_menu')
				->set_html($html)
		];
	}
	//- END - GENERATE FIELDS MENU DOKUMEN PEMDA

	//- START - GENERATE FIELDS MENU DOKUMEN PERANGKAT DAERAH
	public function generate_fields_dokumen_renstra_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_renstra.php') {
			return array();
		}
		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$renstra_opd_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'RENSTRA | ' . $v['nama_jadwal_renstra'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ' )',
					'content' 		=> '[renstra periode=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal_renstra'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $renstra_opd_page['url'] . '">' . $renstra_opd_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RPJMD tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_renstra_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_renstra_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_iku_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_iku1.php') {
			return array();
		}
		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$iku_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'IKU Perangkat Daerah | ' . $v['nama_jadwal_renstra'] . ' (' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ')',
					'content' 		=> '[iku id_jadwal=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal_renstra'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $iku_page['url'] . '">' . $iku_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RENSTRA tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_iku_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_iku_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_renja_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_renjarkt.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$renja_rkt_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'RENJA/RKT Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[renja_rkt tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $renja_rkt_page['url'] . '">' . $renja_rkt_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_renja_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_renja_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_pk_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_perjanjian_kinerja1.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$perjanjian_kinerja_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Perjanjian Kinerja Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[perjanjian_kinerja tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $perjanjian_kinerja_page['url'] . '">' . $perjanjian_kinerja_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_pk_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_pk_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_laporan_kinerja_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_laporan_kinerja1.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$laporan_kinerja_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Laporan Kinerja Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[laporan_kinerja tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $laporan_kinerja_page['url'] . '">' . $laporan_kinerja_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_laporan_kinerja_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_laporan_kinerja_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_dpa_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_dpa1.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$dpa_opd_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'DPA Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dpa tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $dpa_opd_page['url'] . '">' . $dpa_opd_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_dpa_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_dpa_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_pokin_cascading_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_pohon_kinerja_dan_cascading1.php') {
			return array();
		}

		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$pohon_kinerja_cascading_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Pohon Kinerja dan Cascading | ' . $v['nama_jadwal_renstra'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ' )',
					'content' 		=> '[pohon_kinerja_dan_cascading periode=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal_renstra'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $v['tahun_selesai_anggaran'] . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $pohon_kinerja_cascading_page['url'] . '">' . $pohon_kinerja_cascading_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RENSTRA tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_pokin_cascading_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_pokin_cascading_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_lhe_internal_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_lhe_akip_internal1.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$lhe_akip_internal_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'LHE AKIP Internal Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[lhe_akip_internal tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $lhe_akip_internal_page['url'] . '">' . $lhe_akip_internal_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_lhe_internal_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_lhe_internal_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_tl_lhe_internal_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_tl_lhe_akip_internal1.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$tl_lhe_akip_internal_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen TL LHE AKIP Internal Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[tl_lhe_akip_internal tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $tl_lhe_akip_internal_page['url'] . '">' . $tl_lhe_akip_internal_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_tl_lhe_internal_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_tl_lhe_internal_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_laporan_monev_renaksi_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_laporan_monev_renaksi1.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$laporan_monev_renaksi_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Monev Renaksi Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[laporan_monev_renaksi tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $laporan_monev_renaksi_page['url'] . '">' . $laporan_monev_renaksi_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_laporan_monev_renaksi_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_laporan_monev_renaksi_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_renaksi_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_rencana_aksi1.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$rencana_aksi_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Rencana Aksi Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[rencana_aksi tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $rencana_aksi_page['url'] . '">' . $rencana_aksi_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_renaksi_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_renaksi_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_skp_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_skp.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$skp_page = $this->functions->generatePage(array(
					'nama_page' => 'Halaman Dokumen SKP Tahun ' . $v['tahun_anggaran'],
					'content' => '[skp tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' => 1,
					'no_key' => 1,
					'post_status' => 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $skp_page['url'] . '">' . $skp_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_skp_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_skp_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_evaluasi_internal_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_evaluasi_internal.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$evaluasi_internal_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Evaluasi Internal Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[evaluasi_internal tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $evaluasi_internal_page['url'] . '">' . $evaluasi_internal_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_evaluasi_internal_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_evaluasi_internal_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_dokumen_lain_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_dokumen_lainnya1.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$dokumen_lainnya_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Dokumen Lain Tahun ' . $v['tahun_anggaran'],
					'content' 		=> '[dokumen_lainnya tahun=' . $v["tahun_anggaran"] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $dokumen_lainnya_page['url'] . '">' . $dokumen_lainnya_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_dokumen_lainnya_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_lainnya_opd_menu')
				->set_html($html)
		];
	}
	//- END - GENERATE FIELDS MENU DOKUMEN PERANGKAT DAERAH


	public function generate_fields_options_menu_settings()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_menu_setting.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$menu_settings_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Pengaturan Menu Tahun Anggaran | ' . $v['tahun_anggaran'],
					'content' 		=> '[pengaturan_menu tahun_anggaran="' . $v["tahun_anggaran"] . '"]',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $menu_settings_page['url'] . '">' . $menu_settings_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_menu_settings_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_pengaturan_menu')
				->set_html($html)
		];
	}

	public function generate_fields_options_auto_login()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_auto_login.php') {
			return array();
		}

		$this->functions->generatePage(array(
			'nama_page' 	=> 'SSO Login',
			'content' 		=> '[sso_login]',
			'show_header' 	=> 1,
			'no_key' 		=> 1,
			'post_status' 	=> 'public'
		), false, '[sso_login]');

		return [
			Field::make('complex', 'crb_auto_login', __('Setting Auto Login'))
				->add_fields([
					Field::make('text', 'id_login', __('Nama / ID unik'))
						->set_default_value('xxxxx')
						->set_help_text('Harus diisi unik, tidak boleh kosong dan sama.')
						->set_required(true),
					Field::make('text', 'app_url', __('Domain / URL wordpress tujuan'))
						->set_default_value('http://localhost')
						->set_help_text('Alamat situs tujuan yang akan dibuat login otomatis.')
						->set_required(true),
					Field::make('text', 'api_key', __('API Key'))
						->set_default_value('xxxxxxxxxxxxxxxxxx')
						->set_help_text('Kode unik untuk validasi user login dari website tujuan.')
						->set_required(true),
					Field::make('html', 'crb_halaman_terkait_bkk_infrastruktur')
						->set_html('
							<a onclick="coba_auto_login(this); return false;" href="#" class="button button-primary">Coba login</a>
							<br>
							<h4 style="display: inline-block;">Shortcode untuk menampilkan tombol login dimana saja:</h4> <h3 style="display: inline-block;" class="set_id_sso">[sso_login id="" url=""]</h3>
							<br>
							Catatan:
							<ol>
								<li>Hati-hati dalam menambahkan shortcode ini, karena bisa diakses oleh siapa saja</li>
								<li>id berisi nama atau id unik settingan auto login</li>
								<li>url berisi link yang diakses setelah berhasil login</li>
							</ol>
						')
				])
		];
	}

	public function generate_fields_options_halaman_terkait()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_e-sakip_options.php') {
			return array();
		}
		$tahun_all = $this->get_tahun();

		$html = '';
		if (!empty($tahun_all)) {
			foreach ($tahun_all as $v) {
				$monitor_upload_dokumen = $this->functions->generatePage(array(
					'nama_page' 	=> 'Laporan Upload Dokumen' . $v['tahun_anggaran'],
					'content' 		=> '[halaman_cek_dokumen tahun_anggaran=' . $v['tahun_anggaran'] . ']',
					'show_header' 	=> 1,
					'post_status' 	=> 'private'
				));
				$html .= '<a target="_blank" href="' . $monitor_upload_dokumen['url'] . '" class="button button-secondary">Laporan Monitor Upload Dokumen Tahun ' . $v['tahun_anggaran'] . ' </a>';
			}
		} else {
			$html .= '<span class="button button-secondary">Tahun Anggaran tidak tersedia.</span>';
		}

		return [
			Field::make('html', 'crb_esakip_halaman_terkait')
				->set_html(
					'
					<h4>HALAMAN TERKAIT</h4>
					<div style="display: flex; flex-direction: column; gap: 8px;">
					' . $html . '
					</div>'
				)
		];
	}

	public function generate_fields_options_konfigurasi_umum()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_e-sakip_options.php') {
			return array();
		}

		return [
			Field::make('text', 'crb_apikey_esakip', 'API KEY')
				->set_default_value($this->functions->generateRandomString())
				->set_help_text('Wajib diisi. API KEY digunakan untuk integrasi data.'),
			Field::make('text', 'crb_maksimal_upload_dokumen_esakip', 'Maksimal Upload Dokumen')
				->set_default_value(10)
				->set_help_text('Wajib diisi. Ukuran dalam MB'),
			Field::make('html', 'crb_sql_migrate')
				->set_html('<a onclick="sql_migrate_esakip(); return false;" href="#" class="button button-primary button-large">SQL Migrate</a>')
				->set_help_text('Tombol untuk memperbaiki struktur database E-SAKIP.'),
		];
	}

	public function generate_fields_options_identitas_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_e-sakip_options.php') {
			return array();
		}

		return [
			Field::make('text', 'crb_nama_pemda', 'Nama Pemerintah Daerah')
				->set_help_text('Wajib diisi.'),
			Field::make('text', 'crb_alamat_pemda', 'Alamat Pemerintah Daerah')
				->set_help_text('Wajib diisi.'),
			Field::make('text', 'crb_telepon_pemda', 'Telepon Pemda')
				->set_width(33.33),
			Field::make('text', 'crb_faks_pemda', 'Faksimile Pemda')
				->set_width(33.33),
			Field::make('text', 'crb_kode_pos', 'Kode Pos')
				->set_width(33.33),
			Field::make('image', 'crb_logo_garuda', 'Logo Garuda')
				->set_value_type('url'),
			Field::make('image', 'crb_logo_dashboard', 'Logo Pemerintah Daerah')
				->set_value_type('url'),
			Field::make('text', 'crb_kepala_daerah', 'Kepala Daerah')
				->set_help_text('Wajib diisi.'),
			Field::make('select', 'crb_status_jabatan_kepala_daerah', 'Status Jabatan Kepala Daerah')
				->add_options([
					'Gubernur' => 'Gubernur',
					'Bupati' => 'Bupati',
					'Walikota' => 'Walikota',
					'Pj Gubernur' => 'Pj Gubernur',
					'Plt Gubernur' => 'Plt Gubernur',
					'Plh Gubernur' => 'Plh Gubernur',
					'Pj Bupati' => 'Pj Bupati',
					'Plt Bupati' => 'Plt Bupati',
					'Plh Bupati' => 'Plh Bupati',
					'Pj Walikota' => 'Pj Walikota',
					'Plt Walikota' => 'Plt Walikota',
					'Plh Walikota' => 'Plh Walikota'
				])
				->set_default_value('Bupati'),
		];
	}

	public function generate_fields_options_api_simpeg()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_e-sakip_options.php') {
			return array();
		}

		return [
			Field::make('radio', 'crb_api_simpeg_status', 'Status API Kepegawaian')
				->add_options(['0' => 'Dikunci', '1' => 'Dibuka'])
				->set_default_value('0'),
			Field::make('text', 'crb_url_api_simpeg', 'Url API Kepegawaian')
				->set_help_text('Wajib diisi.'),
			Field::make('text', 'crb_authorization_api_simpeg', 'Authorization API Kepegawaian')
				->set_help_text('Wajib diisi. Basic Auth encrypted.'),
		];
	}

	public function generate_fields_options_api_esr()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_e-sakip_options.php') {
			return array();
		}

		$url_api_esr = get_option('_crb_url_api_esr');
		$url_testing_esr = "";
		if (!empty($url_api_esr)) {
			$url_testing_esr = '(<b> ' . $url_api_esr . 'get_user_id | Content-Type: application/json; charset=utf-8 | Authorization: Basic ' . base64_encode(get_option('_crb_username_api_esr') . ':' . get_option('_crb_password_api_esr')) . ' </b>)';
		}

		return [
			Field::make('radio', 'crb_api_esr_status', 'Status API ESR')
				->add_options(['0' => 'Dikunci', '1' => 'Dibuka'])
				->set_default_value('1'),
			Field::make('text', 'crb_url_api_esr', 'URL API ESR')
				->set_help_text('Wajib diisi. ' . $url_testing_esr),
			Field::make('text', 'crb_username_api_esr', 'Username API ESR')
				->set_help_text('Auth Type: Basic Auth'),
			Field::make('text', 'crb_password_api_esr', 'Password API ESR')
				->set_help_text('Auth Type: Basic Auth'),
			Field::make('text', 'crb_expired_time_esr_lokal', 'Expired Time Data ESR Lokal')
				->set_default_value('60')
				->set_help_text('Minimal 1 detik. Waktu cache data lokal ESR.'),
		];
	}

	public function generate_fields_options_api_ekin()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_e-sakip_options.php') {
			return array();
		}

		return [
			Field::make('radio', 'crb_api_ekinerja_status', 'Status API E-Kinerja')
				->add_options(['0' => 'Dikunci', '1' => 'Dibuka'])
				->set_default_value('1'),
			Field::make('text', 'crb_url_api_ekinerja', 'URL API E-Kinerja')
				->set_help_text('Wajib diisi.'),
			Field::make('text', 'crb_api_key_ekinerja', 'API KEY E-Kinerja')
				->set_help_text('Wajib diisi.'),
		];
	}

	public function generate_fields_perangkat_daerah_options()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_pengaturan_perangkat_daerah.php') {
			return array();
		}

		$halaman_mapping_skpd = $this->functions->generatePage(array(
			'nama_page' 	=> 'Halaman Mapping Perangkat Daerah',
			'content' 		=> '[halaman_mapping_skpd]',
			'show_header' 	=> 1,
			'no_key' 		=> 1,
			'post_status' 	=> 'private'
		));
		$halaman_mapping_sipd_simpeg = $this->functions->generatePage(array(
			'nama_page' 	=> 'Halaman Mapping Perangkat Daerah SIPD-SIMPEG',
			'content' 		=> '[halaman_mapping_sipd_simpeg]',
			'show_header' 	=> 1,
			'no_key' 		=> 1,
			'post_status' 	=> 'private'
		));
		$halaman_mapping_user_esr = $this->functions->generatePage(array(
			'nama_page' 	=> 'Halaman Mapping User ESR',
			'content' 		=> '[halaman_mapping_user_esr]',
			'show_header' 	=> 1,
			'no_key' 		=> 1,
			'post_status' 	=> 'private'
		));
		$list_skpd_laporan_pk_setting = $this->functions->generatePage(array(
			'nama_page' 	=> 'Laporan PK Setting',
			'content' 		=> '[halaman_laporan_pk_setting]',
			'show_header' 	=> 1,
			'post_status' 	=> 'private'
		));

		return [
			Field::make('html', 'crb_esakip_halaman_terkait')
				->set_html('
				<h4>HALAMAN TERKAIT</h4>
				<div style="display: flex; flex-direction: column; gap: 8px;">
					<a href="' . $halaman_mapping_skpd['url'] . '" class="button button-secondary" target="_blank">' . $halaman_mapping_skpd['title'] . '</a>
					<a href="' . $halaman_mapping_sipd_simpeg['url'] . '" class="button button-secondary" target="_blank">' . $halaman_mapping_sipd_simpeg['title'] . '</a>
					<a href="' . $halaman_mapping_user_esr['url'] . '" class="button button-secondary" target="_blank">' . $halaman_mapping_user_esr['title'] . '</a>
					<a href="' . $list_skpd_laporan_pk_setting['url'] . '" class="button button-secondary" target="_blank">Halaman Profile Perangkat Daerah</a>
				</div>'),
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
				->set_html('<a id="generate_user_esakip" onclick="return false;" href="#" class="button button-primary button-large">Generate User SIPD By DB Lokal</a>')
				->set_help_text('Data user active yang ada di table data unit akan digenerate menjadi user wordpress.'),
			Field::make('html', 'crb_generate_user_pegawai_simpeg')
				->set_html('<a id="generate_user_esakip_pegawai_simpeg" onclick="return false;" href="#" class="button button-primary button-large">Generate User SIMPEG By DB Lokal</a>')
				->set_help_text('Data user active yang ada di table data pegawai simpeg akan digenerate menjadi user wordpress.'),
		];
	}

	public function generate_fields_mapping_jenis_dokumen_options()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_pengaturan_perangkat_daerah.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();
		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$mapping_jenis_dokumen_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Mapping Jenis Dokumen Tahun Anggaran | ' . $v['tahun_anggaran'],
					'content' 		=> '[halaman_mapping_jenis_dokumen tahun_anggaran="' . $v['tahun_anggaran'] . '"]',
					'show_header' 	=> 1,
					'no_key'		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
					<div class="accordion">
						<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
						<div class="esakip-body-tahun" tahun="' . ($index + 1) . '">
							<ul style="margin-left: 20px;">
								<li><a target="_blank" href="' . $mapping_jenis_dokumen_page['url'] . '">' . $mapping_jenis_dokumen_page['title'] . '</a></li>
							</ul>
						</div>
					</div>';
			}
		} else {
			$html = '
					<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
						Tahun Anggaran tidak tersedia
					</span>';
		}

		return [
			Field::make('html', 'crb_mapping_jenis_dokumen_menu')
				->set_html($html)
		];
	}

	public function generate_fields_lembaga_lainnya_options()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_pengaturan_perangkat_daerah.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();
		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $index => $v) {
				$halaman_lembaga_lainnya = $this->functions->generatePage(array(
					'nama_page' 	=> 'Halaman Lembaga Lainnya Tahun Anggaran | ' . $v['tahun_anggaran'],
					'content' 		=> '[halaman_lembaga_lainnya tahun_anggaran="' . $v['tahun_anggaran'] . '"]',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
					<div class="accordion">
						<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
						<div class="esakip-body-tahun" tahun="' . ($index + 1) . '">
							<ul style="margin-left: 20px;">
								<li><a target="_blank" href="' . $halaman_lembaga_lainnya['url'] . '">' . $halaman_lembaga_lainnya['title'] . '</a></li>
							</ul>
						</div>
					</div>';
			}
		} else {
			$html = '
					<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
						Tahun Anggaran tidak tersedia
					</span>';
		}

		return [
			Field::make('html', 'crb_lembaga_lainnya_menu')
				->set_html($html)
		];
	}

	public function generate_fields_front_page()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_tampilan_beranda.php') {
			return array();
		}

		return [
			Field::make('html', 'crb_menu_depan_note')
				->set_html('<p style="font-weight: bold;">Tampilan Beranda dapat diaktifkan melalui shortcode <code>[menu_depan]</code>.</p>'),

			Field::make('image', 'crb_icon_pohon_kinerja', __('Ikon Pohon Kinerja'))
				->set_value_type('url')
				->set_help_text('Upload ikon untuk Pohon Kinerja.'),

			Field::make('image', 'crb_icon_cascading', __('Ikon Cascading'))
				->set_value_type('url')
				->set_help_text('Upload ikon untuk Cascading.'),

			Field::make('image', 'crb_icon_capaian_kinerja', __('Ikon Capaian Kinerja'))
				->set_value_type('url')
				->set_help_text('Upload ikon untuk Capaian Kinerja.'),

			Field::make('text', 'crb_icon_size', __('Ukuran Ikon (px)'))
				->set_attribute('type', 'number')
				->set_default_value(150)
				->set_help_text('Tentukan ukuran ikon dalam pixel. Contoh: 150 (default). Pastikan nilai yang dimasukkan adalah angka.'),
		];
	}

	public function generate_fields_profile_user()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_tampilan_beranda.php') {
			return array();
		}

		return array(
			Field::make('html', 'crb_menu_user_note')
				->set_html('<ol><li style="font-weight: bold;">Tampilan <i>Menu User Profile</i> dapat diaktifkan melalui shortcode <code>[menu_eval_sakip]</code>.</li>
				<li style="font-weight: bold;">Tampilan <i>Background (Latar Belakang)</i> dapat diaktifkan melalui shortcode <code>[background_menu]</code>. Khusus shortcode ini disesuaikan hanya untuk halaman <code>Login dan Account (Ultimate Member)</code></li></ol>'),

			Field::make('image', 'crb_bg_menu_user', __('Background Menu (Latar Belakang)'))
				->set_value_type('url')
				->set_help_text('Upload background untuk menu user.'),
		);
	}

	public function generate_fields_jadwal($type)
	{
		if (empty($_GET) || empty($type) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_jadwal.php') {
			return array();
		}
		//type based menu depends which type is called ($type is required)
		$get_tahun = $this->get_tahun();

		$html = '';
		switch ($type) {
			case 'rpjpd_rpjmd':
				// add here if u want to add more pages. the_title refference to the TITLE of the ACCORDION
				// the foreach is NOT designed to use for TAHUN ANGGARAN or JADWAL based foreach
				$pages = [
					[
						'the_title' => 'RPJPD',
						'nama_page' => 'Halaman Jadwal RPJPD',
						'content' 	=> '[jadwal_rpjpd_sakip]'
					],
					[
						'the_title' => 'RPJMD / RPD / Renstra',
						'nama_page' => 'Halaman Jadwal RPJMD / RPD ',
						'content' 	=> '[jadwal_rpjmd]'
					]
				];

				foreach ($pages as $index => $page) {
					$gen_page = $this->functions->generatePage(array(
						'nama_page' 	=> $page['nama_page'],
						'content' 		=> $page['content'],
						'show_header' 	=> 1,
						'no_key' 		=> 1,
						'post_status' 	=> 'private'
					));

					$html .= '
						<div class="accordion">
							<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . '">' . $page['the_title'] . '</h3>
							<div class="esakip-body-tahun" tahun="' . ($index + 1) . '">
								<ul style="margin-left: 20px;">
									<li><a target="_blank" href="' . $gen_page['url'] . '">' . $gen_page['title'] . '</a></li>
								</ul>
							</div>
						</div>';
				}
				break;

			case 'lke':
				if (!empty($get_tahun)) {
					foreach ($get_tahun as $index => $v) {
						$jadwal_evaluasi_page = $this->functions->generatePage(array(
							'nama_page' 	=> 'Halaman Jadwal LKE Tahun Anggaran | ' . $v['tahun_anggaran'],
							'content' 		=> '[jadwal_evaluasi_sakip tahun_anggaran="' . $v["tahun_anggaran"] . '"]',
							'show_header' 	=> 1,
							'no_key' 		=> 1,
							'post_status' 	=> 'private'
						));

						$html .= '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . ($index + 1) . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $jadwal_evaluasi_page['url'] . '">' . $jadwal_evaluasi_page['title'] . '</a></li>
									</ul>
								</div>
							</div>';
					}
				} else {
					$html = '
							<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
								Tahun Anggaran tidak tersedia
							</span>';
				}
				break;

			case 'upload_dokumen':
				if (!empty($get_tahun)) {
					foreach ($get_tahun as $index => $v) {
						$jadwal_verifikasi_page = $this->functions->generatePage(array(
							'nama_page' 	=> 'Halaman Jadwal Verifikasi Upload Dokumen Tahun Anggaran | ' . $v['tahun_anggaran'],
							'content' 		=> '[jadwal_verifikasi_upload_dokumen tahun_anggaran="' . $v["tahun_anggaran"] . '"]',
							'show_header' 	=> 1,
							'no_key' 		=> 1,
							'post_status' 	=> 'private'
						));

						$html .= '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . ($index + 1) . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $jadwal_verifikasi_page['url'] . '">' . $jadwal_verifikasi_page['title'] . '</a></li>
									</ul>
								</div>
							</div>';
					}
				} else {
					$html = '
							<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
								Tahun Anggaran tidak tersedia
							</span>';
				}
				break;
		}


		return [
			Field::make('html', 'crb_' . $type . '')
				->set_html($html),
			Field::make('html', 'crb_' . $type . '_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
		];
	}

	public function generate_fields_pengisian_lke_sakip()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_pengisian_lke_sakip.php') {
			return array();
		}

		$jadwal_evaluasi = $this->get_jadwal_evaluasi();

		$html = '';
		if (!empty($jadwal_evaluasi)) {
			foreach ($jadwal_evaluasi as $index => $v) {
				$pengisian_lke_sakip = $this->functions->generatePage(array(
					'nama_page'   => 'Pengisian LKE | ' . $v['nama_jadwal'] . ' - ' . $v['tahun_anggaran'],
					'content'     => '[pengisian_lke_sakip id_jadwal=' . $v['id'] . ']',
					'show_header' => 1,
					'post_status' => 'private'
				));

				$pengisian_lke_sakip['url'] .= '&id_jadwal=' . $v['id'];

				$status_text = '[TIDAK DIKETAHUI]';
				if ($v['status'] == 1) {
					$status_text = '[AKTIF]';
				} elseif ($v['status'] == 2) {
					$status_text = '[DIKUNCI]';
				}

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal'] . ' - ' . $v['tahun_anggaran'] . ' '  . $status_text . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $pengisian_lke_sakip['url'] . '">' . $pengisian_lke_sakip['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal Evaluasi tidak tersedia
				</span>
				';
		}

		return [
			Field::make('html', 'crb_pengisian_lke_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_pengisian_lke_menu')
				->set_html($html)
		];
	}

	public function generate_fields_input_iku_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_input_iku_perangkat_daerah.php') {
			return array();
		}

		$html_info = '
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label>URL Server WP-SIPD</label>
					</th>
					<td>
						<input type="text" style="width: 30em;" value="' . get_option(ESAKIP_URL_WPSIPD) . '" disabled>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label>API KEY WP-SIPD</label>
					</th>
					<td>
						<input type="text" style="width: 30em;" value="' . get_option(ESAKIP_APIKEY_WPSIPD) . '" disabled>
						<p>Setting Url Server dan Api Key ada di menu pengaturan perangkat daerah</p>
					</td>
				</tr>
			</tbody>
		</table>
		';

		$jadwal_renstra_wp_sipd = $this->get_jadwal_renstra_wp_sipd();

		$html = '';
		if (!empty($jadwal_renstra_wp_sipd->data)) {
			foreach ($jadwal_renstra_wp_sipd->data as $index => $v) {

				if (!empty($v->tahun_akhir_anggaran) && $v->tahun_akhir_anggaran > 1) {
					$tahun_anggaran_selesai = $v->tahun_akhir_anggaran;
				} else {
					$tahun_anggaran_selesai = $v->tahun_anggaran + $v->lama_pelaksanaan;
				}

				$input_iku_opd_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Pengisian IKU | ' . $v->nama . ' ( ' . $v->tahun_anggaran . ' - ' . $tahun_anggaran_selesai . ' )',
					'content' 		=> '[list_input_iku periode=' . $v->id_jadwal_lokal . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));
				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v->tahun_anggaran . '">' . $v->nama . ' ( ' . $v->tahun_anggaran . ' - ' . $tahun_anggaran_selesai . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v->tahun_anggaran . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $input_iku_opd_page['url'] . '">' . $input_iku_opd_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal Renstra WP-SIPD tidak tersedia
				</span>
				';
		}

		return [
			Field::make('html', 'crb_input_iku_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_input_iku_opd_info_menu')
				->set_html($html_info),
			Field::make('html', 'crb_input_iku_opd_menu')
				->set_html($html),
			Field::make('html', 'crb_input_iku_opd_note')
				->set_html('<p style="font-weight: bold;"><p>Data Jadwal diambil dari aplikasi <b>WP-SIPD</b>.</p>'),
		];
	}

	public function generate_fields_renaksi_pemda()
	{
	    if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_rencana_aksi_pemerintah_daerah.php') {
	        return array();
	    }

	    $jadwal_rpjmd_settings = $this->get_rpjmd_settings();

	    $html = '';

	    if (!empty($jadwal_rpjmd_settings)) {
	        $group_by_jadwal = [];
	        foreach ($jadwal_rpjmd_settings as $item) {
	            if (!empty($item['id_jadwal_rpjmd'])) {
	                $id = $item['id_jadwal_rpjmd'];
	                if (!isset($group_by_jadwal[$id])) {
	                    $group_by_jadwal[$id] = [
	                        'jadwal_info' => $item,
	                        'tahun_list' => [],
	                    ];
	                }
	                if (!in_array($item['tahun_anggaran_setting'], $group_by_jadwal[$id]['tahun_list'])) {
	                    $group_by_jadwal[$id]['tahun_list'][] = $item['tahun_anggaran_setting'];
	                }
	            }
	        }

	        foreach ($group_by_jadwal as $id_jadwal => $group) {
	            $v = $group['jadwal_info'];

	            $tahun_anggaran_selesai = (!empty($v['tahun_selesai_anggaran']) && $v['tahun_selesai_anggaran'] > 1)
	                ? $v['tahun_selesai_anggaran']
	                : $v['tahun_anggaran'] + $v['lama_pelaksanaan'];

	            $html .= '
	                <div class="accordion">
	                    <h3 class="esakip-header-jadwal" jadwal="' . $id_jadwal . '">
	                        ' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )
	                    </h3>
	                    <div class="accordion-body-jadwal" jadwal="' . $id_jadwal . '">
	            ';

	            foreach ($group['tahun_list'] as $tahun_setting) {
	                $tahun_key = $id_jadwal . '_' . $tahun_setting;

	                $rencana_aksi_pemda = $this->functions->generatePage([
	                    'nama_page'     => 'Rencana Aksi Pemerintah Daerah Tahun ' . $tahun_setting . ' | ' . $v['nama_jadwal'] .' ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
	                    'content'       => '[list_pengisian_rencana_aksi_pemda tahun=' . $tahun_setting . ' periode=' . $v['id'] . ' ]',
	                    'show_header'   => 1,
	                    'no_key'        => 1,
	                    'post_status'   => 'private'
	                ]);

	                $rencana_aksi_pemda_baru = $this->functions->generatePage([
	                    'nama_page'     => 'Rencana Aksi Pemerintah Daerah Baru Tahun ' . $tahun_setting . ' | ' . $v['nama_jadwal'] .' ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
	                    'content'       => '[list_pengisian_rencana_aksi_pemda_baru tahun=' . $tahun_setting . ' periode=' . $v['id'] . ' ]',
	                    'show_header'   => 1,
	                    'no_key'        => 1,
	                    'post_status'   => 'private'
	                ]);

	                $html .= '
	                    <div class="accordion">
	                        <h4 class="esakip-header-tahun" tahun="' . $tahun_key . '">
	                            Tahun Anggaran ' . $tahun_setting . '
	                        </h4>
	                        <div class="esakip-body-tahun" tahun="' . $tahun_key . '">
	                            <ul style="margin-left: 20px; margin-bottom: 20px">
	                                <li><a target="_blank" href="' . $rencana_aksi_pemda['url'] . '">' . $rencana_aksi_pemda['title'] . '</a></li>
	                                <li><a target="_blank" href="' . $rencana_aksi_pemda_baru['url'] . '">' . $rencana_aksi_pemda_baru['title'] . '</a></li>
	                            </ul>
	                        </div>
	                    </div>
	                ';
	            }

	            $html .= '</div></div>';
	        }

	        foreach ($jadwal_rpjmd_settings as $v) {
	            if (empty($v['id_jadwal_rpjmd'])) {
	                $tahun_anggaran_selesai = (!empty($v['tahun_selesai_anggaran']) && $v['tahun_selesai_anggaran'] > 1)
	                    ? $v['tahun_selesai_anggaran']
	                    : $v['tahun_anggaran'] + $v['lama_pelaksanaan'];

	                $html .= '<p style="margin-left: 10px">' . $v['nama_jadwal'] . ' Periode ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' (Periode belum diset di pengaturan menu)</p>';
	            }
	        }
	    } else {
	        $html = '
	            <span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
	                Jadwal RPJMD / Tahun Anggaran tidak tersedia
	            </span>';
	    }

	    return [
	        Field::make('html', 'crb_renaksi_pemda_hide_sidebar')->set_html('
	            <style>
	                .postbox-container { display: none; }
	                #poststuff #post-body.columns-2 { margin: 0 !important; }
	            </style>
	        '),
	        Field::make('html', 'crb_renaksi_pemda_menu')->set_html($html)
	    ];
	}

	public function generate_fields_renaksi_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_rencana_aksi_perangkat_daerah.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $v) {
				$list_skpd_pengisian_rencana_aksi_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Rencana Aksi Perangkat Daerah | ' . $v['tahun_anggaran'],
					'content' 		=> '[list_pengisian_rencana_aksi tahun=' . $v['tahun_anggaran'] . ']',
					'show_header' 	=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $list_skpd_pengisian_rencana_aksi_page['url'] . '">' . $list_skpd_pengisian_rencana_aksi_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_renaksi_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_renaksi_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_renaksi_settings()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_rencana_aksi_setting.php') {
			return array();
		}

		global $wpdb;

		$html_info = '
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label>URL Server WP-SIPD</label>
						</th>
						<td>
							<input type="text" style="width: 30em;" value="' . get_option(ESAKIP_URL_WPSIPD) . '" disabled>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>API KEY WP-SIPD</label>
						</th>
						<td>
							<input type="text" style="width: 30em;" value="' . get_option(ESAKIP_APIKEY_WPSIPD) . '" disabled>
							<p>Setting Url Server dan Api Key ada di menu pengaturan perangkat daerah</p>
						</td>
					</tr>
				</tbody>
			</table>
		';

		$get_tahun = $this->get_tahun();
		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $v) {
				$rencana_aksi_setting_page = $this->functions->generatePage(array(
					'nama_page'   => 'Pengaturan Rencana Aksi | ' . $v['tahun_anggaran'],
					'content' 	  => '[pengisian_rencana_aksi_setting tahun_anggaran=' . $v['tahun_anggaran'] . ']',
					'show_header' => 1,
					'post_status' => 'private'
				));

				$url = $rencana_aksi_setting_page['url'] . '&tahun_anggaran="' . $v['tahun_anggaran'] . '"';
				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $url . '">' . $rencana_aksi_setting_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_renaksi_settings_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_renaksi_settings_info_menu')
				->set_html($html_info),
			Field::make('html', 'crb_renaksi_settings_menu')
				->set_html($html)
		];
	}

	public function generate_fields_input_iku_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_input_iku_pemerintah_daerah.php') {
			return array();
		}

		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$tahun_awal = $v['tahun_anggaran'];
				$tahun_akhir = $tahun_awal + $v['lama_pelaksanaan'] - 1;

				$input_iku_pemda_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Pengisian IKU Pemerintah Daerah | ' . $v['nama_jadwal'] . ' ( ' . $tahun_awal . ' - ' . $tahun_akhir . ' )',
					'content' 		=> '[input_iku_pemda id_periode=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));
				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal'] . ' ( ' . $tahun_awal . ' - ' . $tahun_akhir . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $input_iku_pemda_page['url'] . '">' . $input_iku_pemda_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RPJMD tidak tersedia
				</span>
			';
		}

		return [
			Field::make('html', 'crb_input_iku_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_input_iku_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_input_rpjpd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_input_rpjpd.php') {
			return array();
		}

		$jadwal_rpjpd_settings = $this->get_rpjpd_settings();

		$html = '';
		if (!empty($jadwal_rpjpd_settings)) {
			foreach ($jadwal_rpjpd_settings as $index => $v) {
				$tahun_anggaran_selesai = $v['tahun_selesai_anggaran'] ?? ($v['tahun_anggaran'] + $v['lama_pelaksanaan'] - 1);

				$input_rpjpd_page = $this->functions->generatePage(array(
					'nama_page'   => 'Input RPJPD | ' . $v['nama_jadwal'] . ' Periode ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
					'content'     => '[input_rpjpd periode=' . $v['id'] . ']',
					'show_header' => 1,
					'no_key'      => 1,
					'post_status' => 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $input_rpjpd_page['title'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $input_rpjpd_page['url'] . '">' . $input_rpjpd_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal Input RPJPD tidak tersedia
				</span>
				';
		}

		return [
			Field::make('html', 'crb_input_rpjpd_hide_sidebar')
				->set_html('
				<style>
					.postbox-container { display: none; }
					#poststuff #post-body.columns-2 { margin: 0 !important; }
				</style>
			'),
			Field::make('html', 'crb_pengisian_lke_menu')
				->set_html($html)
		];
	}

	public function generate_fields_input_rpjmd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_input_rpjmd.php') {
			return array();
		}

		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$tahun_anggaran_selesai = $v['tahun_selesai_anggaran'] ?? ($v['tahun_anggaran'] + $v['lama_pelaksanaan'] - 1);

				$input_rpjmd_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Input RPJMD | ' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )',
					'content' 		=> '[input_rpjmd periode=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $input_rpjmd_page['url'] . '">' . $input_rpjmd_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RPJMD tidak tersedia
				</span>
			';
		}

		return [
			Field::make('html', 'crb_input_rpjmd_hide_sidebar')
				->set_html('
				<style>
					.postbox-container { display: none; }
					#poststuff #post-body.columns-2 { margin: 0 !important; }
				</style>
			'),
			Field::make('html', 'crb_pengisian_lke_menu')
				->set_html($html)
		];
	}

	public function generate_fields_cascading_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_cascading_perangkat_daerah.php') {
			return array();
		}

		$html_info = '
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label>URL Server WP-SIPD</label>
					</th>
					<td>
						<input type="text" style="width: 30em;" value="' . get_option(ESAKIP_URL_WPSIPD) . '" disabled>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label>API KEY WP-SIPD</label>
					</th>
					<td>
						<input type="text" style="width: 30em;" value="' . get_option(ESAKIP_APIKEY_WPSIPD) . '" disabled>
						<p>Setting Url Server dan Api Key ada di menu pengaturan perangkat daerah</p>
					</td>
				</tr>
			</tbody>
		</table>
		';

		$jadwal_renstra_wp_sipd = $this->get_jadwal_renstra_wp_sipd();

		$html = '';
		if (!empty($jadwal_renstra_wp_sipd->data)) {
			foreach ($jadwal_renstra_wp_sipd->data as $index => $v) {

				if (!empty($v->tahun_akhir_anggaran) && $v->tahun_akhir_anggaran > 1) {
					$tahun_anggaran_selesai = $v->tahun_akhir_anggaran;
				} else {
					$tahun_anggaran_selesai = $v->tahun_anggaran + $v->lama_pelaksanaan;
				}

				$cascading_opd_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Cascading Perangkat Daerah | ' . $v->nama . ' ( ' . $v->tahun_anggaran . ' - ' . $tahun_anggaran_selesai . ' )',
					'content' 		=> '[cascading_pd periode=' . $v->id_jadwal_lokal . ']',
					'show_header' 	=> 1,
					'post_status' 	=> 'private'
				));
				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v->tahun_anggaran . '">' . $v->nama . ' ( ' . $v->tahun_anggaran . ' - ' . $tahun_anggaran_selesai . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v->tahun_anggaran . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $cascading_opd_page['url'] . '">' . $cascading_opd_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal Renstra WP-SIPD tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_cascading_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_cascading_opd_info_menu')
				->set_html($html_info),
			Field::make('html', 'crb_cascading_opd_menu')
				->set_html($html),
			Field::make('html', 'crb_cascading_opd_note')
				->set_html('<p style="font-weight: bold;"><p>Data Jadwal diambil dari aplikasi <b>WP-SIPD</b>.</p>'),
		];
	}

	public function generate_fields_pokin_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_pohon_kinerja_pemerintah_daerah.php') {
			return array();
		}

		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$tahun_anggaran_selesai = $v['tahun_selesai_anggaran'] ?? ($v['tahun_anggaran'] + $v['lama_pelaksanaan'] - 1);

				$pokin_pemda_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Pohon Kinerja Pemerintah Daerah | ' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )',
					'content' 		=> '[penyusunan_pohon_kinerja periode=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $pokin_pemda_page['url'] . '">' . $pokin_pemda_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RPJMD tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_pokin_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_pokin_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_cascading_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_cascading_pemerintah_daerah.php') {
			return array();
		}

		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$tahun_anggaran_selesai = $v['tahun_selesai_anggaran'] ?? ($v['tahun_anggaran'] + $v['lama_pelaksanaan'] - 1);

				$cascading_pemda_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Cascading Pemerintah Daerah | ' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )',
					'content' 		=> '[cascading_pemda periode=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $cascading_pemda_page['url'] . '">' . $cascading_pemda_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RPJMD tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_cascading_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_cascading_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_pokin_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_pohon_kinerja_perangkat_daerah.php') {
			return array();
		}

		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$tahun_anggaran_selesai = $v['tahun_selesai_anggaran'] ?? ($v['tahun_anggaran'] + $v['lama_pelaksanaan'] - 1);

				$pohon_kinerja_opd_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Pohon Kinerja Perangkat Daerah | ' . $v['nama_jadwal_renstra'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )',
					'content' 		=> '[list_penyusunan_pohon_kinerja_opd periode=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal_renstra'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $pohon_kinerja_opd_page['url'] . '">' . $pohon_kinerja_opd_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RENSTRA tidak tersedia
				</span>
			';
		}

		return [
			Field::make('html', 'crb_crosscutting_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_crosscutting_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_crosscutting_pemda()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_crosscutting_pemerintah_daerah.php') {
			return array();
		}

		$jadwal_rpjmd = $this->get_rpjmd();

		$html = '';
		if (!empty($jadwal_rpjmd)) {
			foreach ($jadwal_rpjmd as $index => $v) {
				$tahun_anggaran_selesai = $v['tahun_selesai_anggaran'] ?? ($v['tahun_anggaran'] + $v['lama_pelaksanaan'] - 1);

				$crosscutting_pemda_page = $this->functions->generatePage(array(
					'nama_page' 	=> 'Croscutting Pemerintah Daerah | ' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )',
					'content' 		=> '[croscutting_pemda periode=' . $v['id'] . ']',
					'show_header' 	=> 1,
					'no_key' 		=> 1,
					'post_status' 	=> 'private'
				));

				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )' . '</h3>
					<div class="esakip-body-tahun" tahun="' . ($index + 1) . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $crosscutting_pemda_page['url'] . '">' . $crosscutting_pemda_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html .= '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Jadwal RPJMD tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_crosscutting_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_crosscutting_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_laporan_pk_pemda()
	{
	    if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_laporan_pk_pemerintah_daerah.php') {
	        return array();
	    }

	    $jadwal_rpjmd_settings = $this->get_rpjmd_settings();

	    $html = '';

	    if (!empty($jadwal_rpjmd_settings)) {
	        $group_by_jadwal = [];
	        foreach ($jadwal_rpjmd_settings as $item) {
	            if (!empty($item['id_jadwal_rpjmd'])) {
	                $id = $item['id_jadwal_rpjmd'];
	                if (!isset($group_by_jadwal[$id])) {
	                    $group_by_jadwal[$id] = [
	                        'jadwal_info' => $item,
	                        'tahun_list' => [],
	                    ];
	                }
	                if (!in_array($item['tahun_anggaran_setting'], $group_by_jadwal[$id]['tahun_list'])) {
	                    $group_by_jadwal[$id]['tahun_list'][] = $item['tahun_anggaran_setting'];
	                }
	            }
	        }

	        foreach ($group_by_jadwal as $id_jadwal => $group) {
	            $v = $group['jadwal_info'];

	            $tahun_anggaran_selesai = (!empty($v['tahun_selesai_anggaran']) && $v['tahun_selesai_anggaran'] > 1)
	                ? $v['tahun_selesai_anggaran']
	                : $v['tahun_anggaran'] + $v['lama_pelaksanaan'];

	            $html .= '
	                <div class="accordion">
	                    <h3 class="esakip-header-jadwal" jadwal="' . $id_jadwal . '">
	                        ' . $v['nama_jadwal'] . ' ( ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' )
	                    </h3>
	                    <div class="accordion-body-jadwal" jadwal="' . $id_jadwal . '">
	            ';

	            foreach ($group['tahun_list'] as $tahun_setting) {
	                $tahun_key = $id_jadwal . '_' . $tahun_setting;

	                $list_page = $this->functions->generatePage([
	                    'nama_page'     => 'Laporan PK Pemerintah Daerah Tahun ' . $tahun_setting . ' | ' . $v['nama_jadwal'] .' ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
	                    'content'       => '[halaman_laporan_pk_pemda tahun=' . $tahun_setting . ' periode=' . $v['id'] . ' ]',
	                    'show_header'   => 1,
	                    'no_key'        => 1,
	                    'post_status'   => 'private'
	                ]);

	                $html .= '
	                    <div class="accordion">
	                        <h4 class="esakip-header-tahun" tahun="' . $tahun_key . '">
	                            Tahun Anggaran ' . $tahun_setting . '
	                        </h4>
	                        <div class="esakip-body-tahun" tahun="' . $tahun_key . '">
	                            <ul style="margin-left: 20px; margin-bottom: 20px">
	                                <li><a target="_blank" href="' . $list_page['url'] . '">' . $list_page['title'] . '</a></li>
	                            </ul>
	                        </div>
	                    </div>
	                ';
	            }

	            $html .= '</div></div>';
	        }

	        foreach ($jadwal_rpjmd_settings as $v) {
	            if (empty($v['id_jadwal_rpjmd'])) {
	                $tahun_anggaran_selesai = (!empty($v['tahun_selesai_anggaran']) && $v['tahun_selesai_anggaran'] > 1)
	                    ? $v['tahun_selesai_anggaran']
	                    : $v['tahun_anggaran'] + $v['lama_pelaksanaan'];

	                $html .= '<p style="margin-left: 10px">' . $v['nama_jadwal'] . ' Periode ' . $v['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' (Periode belum diset di pengaturan menu)</p>';
	            }
	        }
	    } else {
	        $html = '
	            <span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
	                Jadwal RPJMD / Tahun Anggaran tidak tersedia
	            </span>';
	    }

		return [
			Field::make('html', 'crb_laporan_pk_pemda_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_laporan_pk_pemda_menu')
				->set_html($html)
		];
	}

	public function generate_fields_laporan_pk_opd()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_laporan_pk_perangkat_daerah.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $v) {
				$list_skpd_laporan_pk_page = $this->functions->generatePage(array(
					'nama_page'   => 'Laporan PK Perangkat Daerah - ' . $v['tahun_anggaran'],
					'content' 	  => '[list_halaman_laporan_pk tahun=' . $v['tahun_anggaran'] . ']',
					'show_header' => 1,
					'post_status' => 'private'
				));
				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $list_skpd_laporan_pk_page['url'] . '">' . $list_skpd_laporan_pk_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}

		return [
			Field::make('html', 'crb_laporan_pk_opd_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
			'),
			Field::make('html', 'crb_laporan_pk_opd_menu')
				->set_html($html)
		];
	}

	public function generate_fields_kuesioner()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_kuesioner.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $v) {
				$input_kuesioner_menpan_page = $this->functions->generatePage(array(
					'nama_page' => 'Input Kuesioner Menpan tahun ' . $v['tahun_anggaran'],
					'content' => '[input_kuesioner_menpan tahun=' . $v['tahun_anggaran'] . ']',
					'show_header' => 1,
					'post_status' => 'private'
				));
				$input_kuesioner_mendagri_page = $this->functions->generatePage(array(
					'nama_page' => 'Input Kuesioner Mendagri tahun ' . $v['tahun_anggaran'],
					'content' => '[input_kuesioner_mendagri tahun=' . $v['tahun_anggaran'] . ']',
					'show_header' => 1,
					'post_status' => 'private'
				));
				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $input_kuesioner_menpan_page['url'] . '">' . $input_kuesioner_menpan_page['title'] . '</a></li>
							<li><a target="_blank" href="' . $input_kuesioner_mendagri_page['url'] . '">' . $input_kuesioner_mendagri_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}


		return [
			Field::make('html', 'crb_kuesioner_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
			'),
			Field::make('html', 'crb_kuesioner_menu')
				->set_html($html)
		];
	}

	public function generate_fields_kuesioner_menpan()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_kuesioner_menpan.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $v) {
				$list_skpd_kuesioner_menpan_page = $this->functions->generatePage(array(
					'nama_page'   => 'Kuesioner Menpan tahun ' . $v['tahun_anggaran'],
					'content' 	  => '[list_kuesioner_menpan tahun=' . $v['tahun_anggaran'] . ']',
					'show_header' => 1,
					'post_status' => 'private'
				));
				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $list_skpd_kuesioner_menpan_page['url'] . '">' . $list_skpd_kuesioner_menpan_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}


		return [
			Field::make('html', 'crb_kuesioner_menpan_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_kuesioner_menpan_menu')
				->set_html($html)
		];
	}

	public function generate_fields_kuesioner_mendagri()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_kuesioner_mendagri.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $v) {
				$list_skpd_kuesioner_mendagri_page = $this->functions->generatePage(array(
					'nama_page' => 'Kuesioner Mendagri tahun ' . $v['tahun_anggaran'],
					'content' => '[list_kuesioner_mendagri tahun=' . $v['tahun_anggaran'] . ']',
					'show_header' => 1,
					'post_status' => 'private'
				));
				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $list_skpd_kuesioner_mendagri_page['url'] . '">' . $list_skpd_kuesioner_mendagri_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}


		return [
			Field::make('html', 'crb_kuesioner_mendagri_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_kuesioner_mendagri_menu')
				->set_html($html)
		];
	}

	public function generate_fields_dokumen_kuesioner()
	{
		if (empty($_GET) || empty($_GET['page']) || $_GET['page'] != 'crb_carbon_fields_container_dokumen_kuesioner.php') {
			return array();
		}

		$get_tahun = $this->get_tahun();

		$html = '';
		if (!empty($get_tahun)) {
			foreach ($get_tahun as $v) {
				$list_skpd_dokumen_kuesioner_page = $this->functions->generatePage(array(
					'nama_page' => 'Kuesioner dokumen tahun ' . $v['tahun_anggaran'],
					'content' => '[list_kuesioner_dokumen tahun=' . $v['tahun_anggaran'] . ']',
					'show_header' => 1,
					'post_status' => 'private'
				));
				$html .= '
				<div class="accordion">
					<h3 class="esakip-header-tahun" tahun="' . $v['tahun_anggaran'] . '">Tahun Anggaran ' . $v['tahun_anggaran'] . '</h3>
					<div class="esakip-body-tahun" tahun="' . $v['tahun_anggaran'] . '">
						<ul style="margin-left: 20px;">
							<li><a target="_blank" href="' . $list_skpd_dokumen_kuesioner_page['url'] . '">' . $list_skpd_dokumen_kuesioner_page['title'] . '</a></li>
						</ul>
					</div>
				</div>';
			}
		} else {
			$html = '
				<span class="badge" style="display:inline-block; padding:5px 10px; background:#ccc; border-radius:5px;">
					Tahun Anggaran tidak tersedia
				</span>';
		}


		return [
			Field::make('html', 'crb_dokumen_kuesioner_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				'),
			Field::make('html', 'crb_dokumen_kuesioner_menu')
				->set_html($html)
		];
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

	function gen_user_esakip($user = array(), $update_pass = false)
	{
		global $wpdb;
		if (!empty($user)) {
			$username = $user['loginname'];
			if (!empty($user['emailteks'])) {
				$email = $user['emailteks'];
			} else {
				$email = $username . '@sakiplocal.com';
			}
			$user['jabatan'] = strtolower($user['jabatan']);
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
					return $insert_user;
				}
			} else {
				$user_meta = get_userdata($insert_user);
				if (!in_array($user['jabatan'], $user_meta->roles)) {
					$user_meta->add_role($user['jabatan']);
				}
				// Untuk memastikan ada update gelar belakang dari api simpeg
				if (!empty($user['gelar_belakang'])) {
					wp_update_user(array('ID' => $insert_user, 'first_name' => $user['nama'], 'display_name' => $user['nama']));
				}
			}

			if (!empty($update_pass)) {
				wp_set_password($user['pass'], $insert_user);
			}

			$meta = array(
				'description' => 'User dibuat dari generate sistem aplikasi WP-Eval-SAKIP'
			);
			if (!empty($user['nip'])) {
				$meta['nip'] = $user['nip'];
			}
			if (!empty($user['id_sub_skpd'])) {
				$skpd = $wpdb->get_var(
					$wpdb->prepare("
						SELECT nama_skpd 
						FROM esakip_data_unit 
						WHERE id_skpd=%d 
						  AND active=%d
						  ", $user['id_sub_skpd'], 1)
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

	function generate_user_esakip()
	{
		global $wpdb;
		$ret = array();
		$ret['status'] = 'success';
		$ret['message'] = 'Berhasil Generate User Wordpress dari DB Lokal SIPD';
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				$users_pa = $wpdb->get_results(
					$wpdb->prepare("
						SELECT 
						* 
						FROM esakip_data_unit 
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
						$this->gen_user_esakip($user, $update_pass);
					}

					// admin bappeda
					$args = array(
						'role'    => 'admin_bappeda',
						'orderby' => 'user_nicename',
						'order'   => 'ASC'
					);
					$users_bappeda = get_users($args);
					$user_data = array();
					$user_data['pass'] = $_POST['pass'];
					$user_data['jabatan'] = 'admin_bappeda';
					if (empty($user_exist)) {
						$user_data['loginname'] = 'admin_perencanaan';
						$user_data['nama'] = 'Admin Perencanaan';
						$this->gen_user_esakip($user_data, $update_pass);
					} else {
						foreach ($users_bappeda as $user_exist) {
							$user_data['loginname'] = $user_exist->user_login;
							$user_data['nama'] = $user_exist->display_name;
						}
						$this->gen_user_esakip($user_data, $update_pass);
					}

					// admin review
					$args = array(
						'role'    => 'admin_panrb',
						'orderby' => 'user_nicename',
						'order'   => 'ASC'
					);
					$users_fanrb = get_users($args);
					$user_data = array();
					$user_data['pass'] = $_POST['pass'];
					$user_data['jabatan'] = 'admin_panrb';
					if (empty($user_exist)) {
						$user_data['loginname'] = 'admin_panrb';
						$user_data['nama'] = 'Admin Review';
						$this->gen_user_esakip($user_data, $update_pass);
					} else {
						foreach ($users_fanrb as $user_exist) {
							$user_data['loginname'] = $user_exist->user_login;
							$user_data['nama'] = $user_exist->display_name;
						}
						$this->gen_user_esakip($user_data, $update_pass);
					}

					// admin ortala
					$args = array(
						'role'    => 'admin_ortala',
						'orderby' => 'user_nicename',
						'order'   => 'ASC'
					);
					$users_ortala = get_users($args);
					$user_data = array();
					$user_data['pass'] = $_POST['pass'];
					$user_data['jabatan'] = 'admin_ortala';
					if (empty($user_exist)) {
						$user_data['loginname'] = 'admin_organisasi';
						$user_data['nama'] = 'Admin Organisasi';
						$this->gen_user_esakip($user_data, $update_pass);
					} else {
						foreach ($users_ortala as $user_exist) {
							$user_data['loginname'] = $user_exist->user_login;
							$user_data['nama'] = $user_exist->display_name;
						}
						$this->gen_user_esakip($user_data, $update_pass);
					}
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Data user PA/KPA kosong. Harap lakukan singkronisasi data Perangkat Daerah dulu!';
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

	function generate_user_esakip_pegawai_simpeg()
	{
		global $wpdb;
		$ret = array();
		$ret['status'] = 'success';
		$ret['message'] = 'Berhasil Generate User Wordpress dari DB Lokal SIMPEG';
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {

				// dimatikan dulu karena ada kasus keamanan. agus 24-06-2025
				die(json_encode($ret));

				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
				$pass = !empty($_POST['pass']) ? $_POST['pass'] : '';
				$update_pass = !empty($_POST['update_pass']) ? $_POST['update_pass'] : '';
				$mulai = !empty($_POST['mulai']) ? $_POST['mulai'] : 0;
				$limit = !empty($_POST['limit']) ? $_POST['limit'] : 0;

				$data_pegawai = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT
							*
						FROM
							esakip_data_pegawai_simpeg
						WHERE 
							active = %d
							LIMIT %d , %d
						",
						1,
						$mulai,
						$limit
					),
					ARRAY_A
				);

				$status_update_pass = false;
				if (
					!empty($update_pass)
					&& $update_pass == 'true'
				) {
					$status_update_pass = true;
				}

				if (!empty($data_pegawai)) {
					foreach ($data_pegawai as $kp => $v_pegawai) {
						$satker_mapping = substr($v_pegawai['satker_id'], 0, 2);

						$id_skpd = $wpdb->get_var(
							$wpdb->prepare(
								"SELECT
									id_skpd
								FROM
									esakip_data_mapping_unit_sipd_simpeg
								WHERE
									id_satker_simpeg = %d
									AND active = %d
									AND tahun_anggaran = %d
								",
								$satker_mapping,
								1,
								$tahun_anggaran_sakip
							)
						);

						$id_skpd = !empty($id_skpd) ? $id_skpd : 0;

						$gelar_depan = !empty($v_pegawai['gelar_depan']) ? $v_pegawai['gelar_depan'] . ". " : '';
						$gelar_belakang = !empty($v_pegawai['gelar_belakang']) ? ", " . $v_pegawai['gelar_belakang'] : '';

						$v_pegawai['pass'] = $pass;
						$v_pegawai['loginname'] = $v_pegawai['nip_baru'];
						$v_pegawai['jabatan'] = 'pegawai';
						$v_pegawai['nama'] = $gelar_depan . '' . $v_pegawai['nama_pegawai'] . '' . $gelar_belakang;
						$v_pegawai['nip'] = $v_pegawai['nip_baru'];
						$v_pegawai['id_sub_skpd'] = $id_skpd;
						$v_pegawai['gelar_depan'] = $gelar_depan;
						$v_pegawai['gelar_belakang'] = $gelar_belakang;
						$this->gen_user_esakip($v_pegawai, $status_update_pass);
					}
				} else {
					$ret['status'] = 'error';
					$ret['message'] = 'Data Pegawai Kosong. Pastikan Sudah Sinkron Data Pegawai Simpeg!';
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

	function get_data_total_pegawai_simpeg()
	{
		global $wpdb;
		$ret = array();
		$ret['status'] = 'success';
		$ret['message'] = 'Berhasil Get Data Unit Mapping SIMPEG!';
		$ret['total_pegawai_simpeg'] = 0;
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_apikey_esakip')) {
				$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

				$unit = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT 
								*
							FROM 
								esakip_data_unit 
							WHERE 
								active=1 
							AND is_skpd=1 
							AND tahun_anggaran=%d
							ORDER BY kode_skpd ASC
						",
						$tahun_anggaran_sakip
					),
					ARRAY_A
				);

				if (!empty($unit)) {
					foreach ($unit as $ku => $v_unit) {
						// Untuk mendapatkan data total pegawai yang skpdnya sudah termapping
						$get_mapping = $wpdb->get_var($wpdb->prepare('
								SELECT 
									u.id_satker_simpeg
								FROM esakip_data_mapping_unit_sipd_simpeg AS u
								WHERE u.tahun_anggaran = %d
									AND u.id_skpd = %d
									AND u.active = 1
							', $tahun_anggaran_sakip, $v_unit['id_skpd']));

						if (!empty($get_mapping)) {
							$total_pegawai = $wpdb->get_var($wpdb->prepare(
								'SELECT 
										COUNT(*)
									FROM 
										esakip_data_pegawai_simpeg
									WHERE 
										satker_id LIKE %s
										AND active = %d
								',
								$get_mapping . '%',
								1
							));

							if (!empty($total_pegawai)) {
								$ret['total_pegawai_simpeg'] += $total_pegawai;
							}
						}
					}
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
		$ret = array(
			'status'  => 'success',
			'message' => 'Berhasil Get Data Unit WP-SIPD!'
		);
		if (empty($_POST['server'])) {
			$ret['status'] 	= 'error';
			$ret['message'] = 'URL Server Tidak Boleh Kosong';
			die(json_encode($ret));
		} else if (empty($_POST['tahun_anggaran'])) {
			$ret['status'] 	= 'error';
			$ret['message'] = 'Tahun Anggaran Tidak Boleh Kosong';
			die(json_encode($ret));
		} else if (empty($_POST['api_key'])) {
			$ret['status'] 	= 'error';
			$ret['message'] = 'API Key Tidak Boleh Kosong';
			die(json_encode($ret));
		}

		// data to send in API request
		$api_params_get_skpd = array(
			'action' 		 => 'get_skpd',
			'api_key'		 => $_POST['api_key'],
			'tahun_anggaran' => $_POST['tahun_anggaran']
		);

		$api_params_get_rekening = array(
			'action' 		 => 'get_rekening_akun',
			'api_key'		 => $_POST['api_key'],
			'tahun_anggaran' => $_POST['tahun_anggaran']
		);

		$api_params_get_satuan = array(
			'action' 		 => 'get_data_satuan_ssh',
			'api_key'		 => $_POST['api_key'],
			'tahun_anggaran' => $_POST['tahun_anggaran'],
			'no_option' 	 => true
		);

		$response_get_skpd = wp_remote_post(
			$_POST['server'],
			array(
				'timeout' 	=> 1000,
				'sslverify' => false,
				'body' 		=> $api_params_get_skpd
			)
		);

		$response_get_rekening = wp_remote_post(
			$_POST['server'],
			array(
				'timeout' 	=> 1000,
				'sslverify' => false,
				'body' 		=> $api_params_get_rekening
			)
		);

		$response_get_satuan = wp_remote_post(
			$_POST['server'],
			array(
				'timeout' 	=> 1000,
				'sslverify' => false,
				'body' 		=> $api_params_get_satuan
			)
		);

		$response_get_skpd 		= wp_remote_retrieve_body($response_get_skpd);
		$response_get_rekening 	= wp_remote_retrieve_body($response_get_rekening);
		$response_get_satuan 	= wp_remote_retrieve_body($response_get_satuan);

		$data_get_skpd 		= json_decode($response_get_skpd);
		$data_get_rekening 	= json_decode($response_get_rekening);
		$data_get_satuan	= json_decode($response_get_satuan);

		$esakip_data_unit 			= $data_get_skpd->data;
		$esakip_data_rekening_akun 	= $data_get_rekening->items;
		$esakip_data_satuan 		= $data_get_satuan->data;

		if ($data_get_skpd->status == 'success' && !empty($esakip_data_unit)) {
			$wpdb->update(
				'esakip_data_unit',
				array('active' => 0),
				array('tahun_anggaran' => $api_params_get_skpd['tahun_anggaran'])
			);
			foreach ($esakip_data_unit as $vdata) {
				$cek = $wpdb->get_var(
					$wpdb->prepare('
						SELECT id 
						FROM esakip_data_unit 
						WHERE id_skpd = %d
						  AND tahun_anggaran = %d
					', $vdata->id_skpd, $vdata->tahun_anggaran)
				);
				$data = array(
					'id_setup_unit'  => $vdata->id_setup_unit,
					'id_unit' 		 => $vdata->id_unit,
					'is_skpd' 		 => $vdata->is_skpd,
					'kode_skpd' 	 => $vdata->kode_skpd,
					'kunci_skpd' 	 => $vdata->kunci_skpd,
					'nama_skpd' 	 => $vdata->nama_skpd,
					'posisi' 		 => $vdata->posisi,
					'status' 		 => $vdata->status,
					'id_skpd' 		 => $vdata->id_skpd,
					'bidur_1' 		 => $vdata->bidur_1,
					'bidur_2' 		 => $vdata->bidur_2,
					'bidur_3' 		 => $vdata->bidur_3,
					'idinduk' 		 => $vdata->idinduk,
					'ispendapatan' 	 => $vdata->ispendapatan,
					'isskpd' 		 => $vdata->isskpd,
					'kode_skpd_1' 	 => $vdata->kode_skpd_1,
					'kode_skpd_2' 	 => $vdata->kode_skpd_2,
					'kodeunit' 		 => $vdata->kodeunit,
					'komisi' 		 => $vdata->komisi,
					'namabendahara'  => $vdata->namabendahara,
					'namakepala' 	 => $vdata->namakepala,
					'namaunit' 		 => $vdata->namaunit,
					'nipbendahara' 	 => $vdata->nipbendahara,
					'nipkepala' 	 => $vdata->nipkepala,
					'pangkatkepala'  => $vdata->pangkatkepala,
					'setupunit' 	 => $vdata->setupunit,
					'statuskepala' 	 => $vdata->statuskepala,
					'update_at' 	 => $vdata->update_at,
					'tahun_anggaran' => $vdata->tahun_anggaran,
					'active' 		 => $vdata->active
				);
				if (empty($cek)) {
					$wpdb->insert(
						'esakip_data_unit',
						$data
					);
				} else {
					$wpdb->update(
						'esakip_data_unit',
						$data,
						array('id' => $cek)
					);
				}
			}
		} else {
			$ret['status'] 	= 'error';
			$ret['message'] = 'Data Unit gagal untuk didapatkan!';
			die(json_encode($ret));
		}

		if ($data_get_rekening->status == true && !empty($esakip_data_rekening_akun)) {
			$wpdb->update(
				'esakip_data_rekening_akun',
				array('active' => 0),
				array('tahun_anggaran' => $api_params_get_rekening['tahun_anggaran'])
			);
			foreach ($esakip_data_rekening_akun as $vdata) {
				$cek = $wpdb->get_var(
					$wpdb->prepare('
						SELECT id 
						FROM esakip_data_rekening_akun 
						WHERE id_akun = %d
						  AND kode_akun = %s
						  AND tahun_anggaran = %d
					', $vdata->id_akun, $vdata->kode_akun, $vdata->tahun_anggaran)
				);
				$data = array(
					'id_akun'		 => $vdata->id_akun,
					'kode_akun'		 => $vdata->kode_akun,
					'nama_akun'		 => $vdata->nama_akun,
					'tahun_anggaran' => $api_params_get_rekening['tahun_anggaran'],
					'active' 		 => 1
				);
				if (empty($cek)) {
					$wpdb->insert(
						'esakip_data_rekening_akun',
						$data
					);
				} else {
					$wpdb->update(
						'esakip_data_rekening_akun',
						$data,
						array('id' => $cek)
					);
				}
			}
		} else {
			$ret['status'] 	= 'error';
			$ret['message'] = 'Data Rekening gagal untuk didapatkan!';
			die(json_encode($ret));
		}

		if ($data_get_satuan->status == true && !empty($esakip_data_satuan)) {
			$wpdb->update(
				'esakip_data_satuan',
				array('active' => 0),
				array('tahun_anggaran' => $api_params_get_rekening['tahun_anggaran'])
			);
			foreach ($esakip_data_satuan as $vdata) {
				$cek = $wpdb->get_var(
					$wpdb->prepare('
						SELECT id 
						FROM esakip_data_satuan 
						WHERE tahun_anggaran = %d
						  AND id_satuan = %d
					', $vdata->id_satuan, $vdata->tahun_anggaran)
				);

				$data = array(
					'id_satuan'		 => $vdata->id_satuan,
					'nama_satuan'	 => $vdata->nama_satuan,
					'tahun_anggaran' => $api_params_get_rekening['tahun_anggaran'],
					'active' 		 => 1
				);
				if (empty($cek)) {
					$wpdb->insert(
						'esakip_data_satuan',
						$data
					);
				} else {
					$wpdb->update(
						'esakip_data_satuan',
						$data,
						array('id' => $cek)
					);
				}
			}
		} else {
			$ret['status'] 	= 'error';
			$ret['message'] = 'Data Satuan gagal untuk didapatkan!';
			die(json_encode($ret));
		}

		die(json_encode($ret));
	}

	function allow_access_private_post()
	{
		$this->functions->allow_access_private_post();
	}

	function coba_auto_login()
	{
		global $wpdb;
		$ret = array(
			'status'    => 'success',
			'message'   => 'Berhasil cek lisensi aktif!'
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (is_user_logged_in() == false) {
					$ret['status'] = 'error';
					$ret['message'] = 'Anda tidak dapat akses ke halaman ini!';
				} else {
					if (!empty($_POST['id'])) {
						$user = wp_get_current_user();
						$ret['url_login'] = $this->functions->login_to_other_site(array(
							'user' => $user,
							'id_login' => $_POST['id'],
							'url_asli' => $_POST['url']
						));
					} else {
						if (empty($_POST['domain'])) {
							$ret['status'] = 'error';
							$ret['message'] = 'Domain tidak boleh kosong!';
						} elseif (empty($_POST['api_key_tujuan'])) {
							$ret['status'] = 'error';
							$ret['message'] = 'API KEY tujuan tidak boleh kosong!';
						} else {
							$user = wp_get_current_user();
							$ret['url_login'] = $this->functions->login_to_other_site(array(
								'user' => $user,
								'domain' => $_POST['domain'],
								'api_key' => $_POST['api_key_tujuan']
							));
						}
					}
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

	function handle_sso_login()
	{
		return $this->functions->handle_sso_login();
	}
}
