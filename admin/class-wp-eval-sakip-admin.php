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
				if (
					!empty($_POST['type'])
					&& (
						$_POST['type'] == 'renstra'
						|| $_POST['type'] == 'rpjmd'

					)
				) {
					$jadwal_periode = $wpdb->get_results(
						$wpdb->prepare(
							"
							SELECT 
								id,
								nama_jadwal,
								tahun_anggaran,
								lama_pelaksanaan
							FROM esakip_data_jadwal
							WHERE tipe = 'RPJMD'
							  AND status = 1
							GROUP BY tahun_anggaran"
						),
						ARRAY_A
					);

					$jadwal_evaluasi = $wpdb->get_results(
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
							GROUP BY tahun_anggaran"
						),
						ARRAY_A
					);
					foreach ($jadwal_periode as $jadwal_periode_item) {
						$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
						if (!empty($_POST['type']) && $_POST['type'] == 'renstra') {
							$renstra = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Upload Dokumen RENSTRA ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[upload_dokumen_renstra periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
							<h3 style="text-transform:uppercase;" class="esakip-header-tahun" tahun="' . $jadwal_periode_item['tahun_anggaran'] . '">' . $jadwal_periode_item['nama_jadwal'] . ' ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . '</h3>
								<div class="esakip-body-tahun" tahun="' . $jadwal_periode_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $renstra['url'] . '">' . $renstra['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'rpjmd') {
							$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
							$rpjmd = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Upload Dokumen RPJMD ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[upload_dokumen_rpjmd periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 style="text-transform:uppercase;" class="esakip-header-tahun" tahun="' . $jadwal_periode_item['tahun_anggaran'] . '">' . $jadwal_periode_item['nama_jadwal'] . ' ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . '</h3>
								<div class="esakip-body-tahun" tahun="' . $jadwal_periode_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $rpjmd['url'] . '">' . $rpjmd['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						}
						$ret['message'] .= $body_pemda;
					}
					foreach ($jadwal_evaluasi as $jadwal_evaluasi_item) {
						$tahun_anggaran_selesai = $jadwal_evaluasi_item['tahun_anggaran'] + $jadwal_evaluasi_item['lama_pelaksanaan'];
						if (!empty($_POST['type']) && $_POST['type'] == 'pengisian_lke') {
							$pengisian_lke_sakip = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Pengisian LKE ' . $jadwal_evaluasi_item['nama_jadwal'] . ' ' . 'Jadwal ' . $jadwal_evaluasi_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[pengisian_lke_sakip]',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
							<h3 style="text-transform:uppercase;" class="esakip-header-tahun" tahun="' . $jadwal_evaluasi_item['tahun_anggaran'] . '">' . $jadwal_evaluasi_item['nama_jadwal'] . ' ' . $jadwal_evaluasi_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . '</h3>
								<div class="esakip-body-tahun" tahun="' . $jadwal_evaluasi_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pengisian_lke_sakip['url'] . '">' . $pengisian_lke_sakip['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						}
						$ret['message'] .= $body_pemda;
					}
				} else {
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
								'nama_page' => 'Halaman Dokumen RENJA/RKT Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[renja_rkt tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $renja_rkt['url'] . '">' . $renja_rkt['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'skp') {
							$skp = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen SKP Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[skp tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $skp['url'] . '">' . $skp['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'rencana_aksi') {
							$rencana_aksi = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Rencana Aksi Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[rencana_aksi tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $rencana_aksi['url'] . '">' . $rencana_aksi['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'iku') {
							$iku = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen IKU Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[iku tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $iku['url'] . '">' . $iku['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pengukuran_kinerja') {
							$pengukuran_kinerja = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pengukuran Kinerja Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[pengukuran_kinerja tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pengukuran_kinerja['url'] . '">' . $pengukuran_kinerja['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pengukuran_rencana_aksi') {
							$pengukuran_rencana_aksi = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pengukuran Rencana Aksi Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[pengukuran_rencana_aksi tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pengukuran_rencana_aksi['url'] . '">' . $pengukuran_rencana_aksi['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'laporan_kinerja') {
							$laporan_kinerja = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Laporan Kinerja Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[laporan_kinerja tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $laporan_kinerja['url'] . '">' . $laporan_kinerja['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'evaluasi_internal') {
							$evaluasi_internal = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Evaluasi Internal Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[evaluasi_internal tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $evaluasi_internal['url'] . '">' . $evaluasi_internal['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'dokumen_lainnya') {
							$dokumen_lainnya = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Lain Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_lainnya tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $dokumen_lainnya['url'] . '">' . $dokumen_lainnya['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'perjanjian_kinerja') {
							$perjanjian_kinerja = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Perjanjian Kinerja Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[perjanjian_kinerja tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $perjanjian_kinerja['url'] . '">' . $perjanjian_kinerja['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'rkpd') {
							$rkpd = $this->functions->generatePage(array(
								'nama_page' => 'Halaman RKPD Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[rkpd tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $rkpd['url'] . '">' . $rkpd['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'dokumen_pemda_lainnya') {
							$dokumen_pemda_lainnya = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Lainnya Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_pemda_lainnya tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $dokumen_pemda_lainnya['url'] . '">' . $dokumen_pemda_lainnya['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'lkjip') {
							$lkjip_lppd = $this->functions->generatePage(array(
								'nama_page' => 'Halaman LKJIP/LPPD  ' . $tahun_item['tahun_anggaran'],
								'content' => '[lkjip_lppd tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $lkjip_lppd['url'] . '">' . $lkjip_lppd['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						}
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

		$halaman_mapping_skpd = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Mapping SKPD',
			'content' => '[halaman_mapping_skpd]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));

		$basic_options_container = Container::make('theme_options', __('E-SAKIP Options'))
			->set_page_menu_position(3)
			->add_fields(array(
				Field::make('html', 'crb_esakip_halaman_terkait')
					->set_html('
					<h4>HALAMAN TERKAIT</h4>
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
				Field::make('html', 'crb_esakip_halaman_terkait')
					->set_html('
					<h4>HALAMAN TERKAIT</h4>
	            	<ol>
	            		<li><a href="' . $halaman_mapping_skpd['url'] . '" target="_blank">' . $halaman_mapping_skpd['title'] . '</a></li>
	            	</ol>'),
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
					->set_html('<a id="generate_user_esakip" onclick="return false;" href="#" class="button button-primary button-large">Generate User By DB Lokal</a>')
					->set_help_text('Data user active yang ada di table esakip_data_unit akan digenerate menjadi user wordpress.'),
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
				Field::make('html', 'crb_halaman_terkait_desain_lke')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            		<li><a target="_blank" href="' . $desain_lke_sakip['url'] . '">' . $desain_lke_sakip['title'] . '</a></li>
	            	</ol>
		        	')
			));
		Container::make('theme_options', __('Jadwal'))
			->set_page_parent($basic_options_container)
			->add_fields(array(
				Field::make('html', 'crb_jadwal_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->generate_jadwal());

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
		        	')
			))
			->add_fields($this->get_ajax_field(array('type' => 'pengisian_lke')));

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
		        	')
			))
			->add_fields($this->get_ajax_field(array('type' => 'renstra')));

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
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'perjanjian_kinerja')));
		Container::make('theme_options', __('Rencana Aksi'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_rencana_aksi_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'rencana_aksi')));
		Container::make('theme_options', __('IKU'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_iku_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'iku')));
		Container::make('theme_options', __('SKP'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_skp_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'skp')));
		Container::make('theme_options', __('Pengukuran Kinerja'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengukuran_kinerja_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'pengukuran_kinerja')));
		Container::make('theme_options', __('Pengukuran Rencana Aksi'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengukuran_rencana_aksi_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'pengukuran_rencana_aksi')));
		Container::make('theme_options', __('Laporan Kinerja'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_laporan_kinerja_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'laporan_kinerja')));
		Container::make('theme_options', __('Evaluasi Internal'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_evaluasi_internal_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'evaluasi_internal')));
		Container::make('theme_options', __('Dokumen Lainnya'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_dokumen_lainnya_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'dokumen_lainnya')));

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
		        	')
			))
			->add_fields($this->get_ajax_field(array('type' => 'rpjmd')));

		Container::make('theme_options', __('RKPD'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_rkpd_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	')
			))
			->add_fields($this->get_ajax_field(array('type' => 'rkpd')));

		Container::make('theme_options', __('LKJIP/LPPD'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_lkjip_lppd_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	')
			))
			->add_fields($this->get_ajax_field(array('type' => 'lkjip')));

		Container::make('theme_options', __('Dokumen Lainnya'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_dokumen_pemda_lainnya_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	')
			))
			->add_fields($this->get_ajax_field(array('type' => 'dokumen_pemda_lainnya')));

	}

	public function generate_jadwal()
	{
		global $wpdb;
		$get_tahun = $wpdb->get_results('select tahun_anggaran from esakip_data_unit group by tahun_anggaran', ARRAY_A);
		$list_data = '';

		$jadwal_rpjmd = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Jadwal RPJMD / RPD ',
				'content' => '[jadwal_rpjmd]',
				'show_header' => 1,
				'no_key' => 1,
				'post_status' => 'private'
			));
			$list_data .= '<li><a target="_blank" href="' . $jadwal_rpjmd['url'] . '">' . $jadwal_rpjmd['title'] . '</a></li>';

		$no = 0;
		foreach ($get_tahun as $k => $v) {
			$jadwal_evaluasi = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Jadwal LKE Tahun Anggaran| ' . $v['tahun_anggaran'],
				'content' => '[jadwal_evaluasi_sakip tahun_anggaran="' . $v["tahun_anggaran"] . '"]',
				'show_header' => 1,
				'no_key' => 1,
				'post_status' => 'private'
			));
			$list_data .= '<li><a target="_blank" href="' . $jadwal_evaluasi['url'] . '">' . $jadwal_evaluasi['title'] . '</a></li>';
		}
		$label = array(
			Field::make('html', 'crb_jadwal')
				->set_html('
            		<ol>' . $list_data . '</ol>
            	')
		);
		return $label;
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
				$email = $username . '@sipdlocal.com';
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
			}else{
				$user_meta = get_userdata( $insert_user );
				if(!in_array($user['jabatan'], $user_meta->roles)){
					$user_meta->add_role( $user['jabatan'] );
				}
			}

			if (!empty($update_pass)) {
				wp_set_password($user['pass'], $insert_user);
			}

			$meta = array(
				'_nip' => $user['nip'],
				'description' => 'User dibuat dari data WP-SIPD'
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

	function generate_user_esakip()
	{
		global $wpdb;
		$ret = array();
		$ret['status'] = 'success';
		$ret['message'] = 'Berhasil Generate User Wordpress dari DB Lokal';
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
		} else if (empty($_POST['tahun_anggaran'])) {
			$data = array(
				'status' => 'error',
				'message' => 'Tahun Tidak Boleh Kosong'
			);
			$response = json_encode($data);
			die($response);
		} else if (empty($_POST['api_key'])) {
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

		$response = wp_remote_post($_POST['server'], array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));

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

	function allow_access_private_post()
	{
		if (
			!empty($_GET)
			&& !empty($_GET['key'])
		) {
			$key = base64_decode($_GET['key']);
			$key_db = md5(get_option(ESAKIP_APIKEY));
			$key = explode($key_db, $key);
			$valid = 0;
			if (
				!empty($key[1])
				&& $key[0] == $key[1]
				&& is_numeric($key[1])
			) {
				$tgl1 = new DateTime();
				$date = substr($key[1], 0, strlen($key[1]) - 3);
				$tgl2 = new DateTime(date('Y-m-d', $date));
				$valid = $tgl2->diff($tgl1)->days + 1;
			}
			if ($valid == 1) {
				global $wp_query;
				// print_r($wp_query);
				// print_r($wp_query->queried_object); die('tes');
				if (!empty($wp_query->queried_object)) {
					if ($wp_query->queried_object->post_status == 'private') {
						wp_update_post(array(
							'ID'    =>  $wp_query->queried_object->ID,
							'post_status'   =>  'publish'
						));
						if (!empty($_GET['private'])) {
							die('<script>window.location =  window.location.href;</script>');
						} else {
							die('<script>window.location =  window.location.href+"&private=1";</script>');
						}
					} else if (!empty($_GET['private'])) {
						wp_update_post(array(
							'ID'    =>  $wp_query->queried_object->ID,
							'post_status'   =>  'private'
						));
					}
				} else if ($wp_query->found_posts >= 1) {
					global $wpdb;
					$sql = $wp_query->request;
					$post = $wpdb->get_results($sql, ARRAY_A);
					if (!empty($post)) {
						if (empty($post[0]['post_status'])) {
							return;
						}
						if ($post[0]['post_status'] == 'private') {
							wp_update_post(array(
								'ID'    =>  $post[0]['ID'],
								'post_status'   =>  'publish'
							));
							if (!empty($_GET['private'])) {
								die('<script>window.location =  window.location.href;</script>');
							} else {
								die('<script>window.location =  window.location.href+"&private=1";</script>');
							}
						} else if (!empty($_GET['private'])) {
							wp_update_post(array(
								'ID'    =>  $post[0]['ID'],
								'post_status'   =>  'private'
							));
						}
					}
				}
			}
		}
	}
}
