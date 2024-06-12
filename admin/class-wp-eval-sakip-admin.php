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
						|| $_POST['type'] == 'input_perencanaan_rpjmd'
						|| $_POST['type'] == 'input_pohon_kinerja_pemda'
						|| $_POST['type'] == 'input_pohon_kinerja_opd'
						|| $_POST['type'] == 'cascading_pemda'
						|| $_POST['type'] == 'cascading_pd'
						|| $_POST['type'] == 'croscutting_pemda'
						|| $_POST['type'] == 'croscutting_pd'
					)
				) {
					$jadwal_periode = $wpdb->get_results(
						$wpdb->prepare(
							"
							SELECT 
								*
							FROM esakip_data_jadwal
							WHERE tipe = %s
							  AND status = 1",
							'RPJMD'
						),
						ARRAY_A
					);

					$body_pemda = '<ol>';
					foreach ($jadwal_periode as $jadwal_periode_item) {
						// Cek setting tahun anggaran selesai
						if(!empty($jadwal_periode_item['tahun_selesai_anggaran']) && $jadwal_periode_item['tahun_selesai_anggaran'] > 1){
							$tahun_anggaran_selesai = $jadwal_periode_item['tahun_selesai_anggaran'];
						}else{
							$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
						}

						if (!empty($_POST['type']) && $_POST['type'] == 'input_pohon_kinerja_pemda') {
							$input_pokin = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Input Pohon Kinerja ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[penyusunan_pohon_kinerja periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
								<li><a target="_blank" href="' . $input_pokin['url'] . '">' . $input_pokin['title'] . '</a></li>
									</ul>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'input_pohon_kinerja_opd') {
							$input_pokin = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Input Pohon Kinerja Perangkat Daerah ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[penyusunan_pohon_kinerja_pd periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
								<li><a target="_blank" href="' . $input_pokin['url'] . '">' . $input_pokin['title'] . '</a></li>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'cascading_pemda') {
							$cascading = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Input Cascading Pemerintah Daerah ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[cascading_pemda periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
								<li><a target="_blank" href="' . $cascading['url'] . '">' . $cascading['title'] . '</a></li>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'cascading_pd') {
							$cascading = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Input Cascading Perangkat Daerah ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[cascading_pd periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
								<li><a target="_blank" href="' . $cascading['url'] . '">' . $cascading['title'] . '</a></li>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'croscutting_pemda') {
							$croscutting = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Input Croscutting Pemerintah Daerah ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[croscutting_pemda periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
								<li><a target="_blank" href="' . $croscutting['url'] . '">' . $croscutting['title'] . '</a></li>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'croscutting_pd') {
							$croscutting = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Input Croscutting Perangkat Daerah ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[croscutting_pd periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
								<li><a target="_blank" href="' . $croscutting['url'] . '">' . $croscutting['title'] . '</a></li>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'renstra') {
							$renstra = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen RENSTRA ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[renstra periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
							<li><a target="_blank" href="' . $renstra['url'] . '">' . $renstra['title'] . '</a></li>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'rpjmd') {
							$rpjmd = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Upload Dokumen RPJMD ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[upload_dokumen_rpjmd periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
							<li><a target="_blank" href="' . $rpjmd['url'] . '">' . $rpjmd['title'] . '</a></li>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'input_perencanaan_rpjmd') {
							$perencanaan_rpjmd = $this->functions->generatePage(array(
								'nama_page' => 'Input RPJMD ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[input_rpjmd periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							// $perencanaan_rpjmd['url'] .= '&id_periode_rpjmd=' . $jadwal_periode_item['id'];
							$body_pemda .= '
							<li><a target="_blank" href="' . $perencanaan_rpjmd['url'] . '">' . $perencanaan_rpjmd['title'] . '</a></li>';
						}
					}
					$body_pemda .= '</ol>';
					$ret['message'] .= $body_pemda;
				} else if (
					!empty($_POST['type'])
					&& $_POST['type'] == 'pengisian_lke'
				) {
					$jadwal_evaluasi = $wpdb->get_results(
						$wpdb->prepare(
							"
							SELECT 
								id,
								nama_jadwal,
								tahun_anggaran,
								lama_pelaksanaan
							FROM esakip_data_jadwal
							WHERE tipe = %s
							  AND status =1",
							'LKE'
						),
						ARRAY_A
					);

					$body_pemda = '<ol>';
					foreach ($jadwal_evaluasi as $jadwal_evaluasi_item) {
						if (!empty($_POST['type']) && $_POST['type'] == 'pengisian_lke') {
							$pengisian_lke_sakip = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Pengisian LKE | ' . $jadwal_evaluasi_item['tahun_anggaran'],
								'content' => '[pengisian_lke_sakip id_jadwal=' . $jadwal_evaluasi_item['id'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
							$pengisian_lke_sakip['url'] .= '&id_jadwal=' . $jadwal_evaluasi_item['id'];
							$body_pemda .= '
								<li><a target="_blank" href="' . $pengisian_lke_sakip['url'] . '">' . $pengisian_lke_sakip['title'] . '</a></li>';
						}
					}
					$body_pemda .= '</ol>';
					$ret['message'] .= $body_pemda;
				} else if (
					!empty($_POST['type'])
					&& (
						$_POST['type'] == 'rpjpd'
						|| $_POST['type'] == 'input_perencanaan_rpjpd'
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
							WHERE tipe = %s
							  AND status = 1",
							'RPJPD'
						),
						ARRAY_A
					);

					$body_pemda = '<ol>';
					foreach ($jadwal_periode as $jadwal_periode_item) {
						$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
						if (!empty($_POST['type']) && $_POST['type'] == 'rpjpd') {
							$rpjpd = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Upload Dokumen RPJPD ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[upload_dokumen_rpjpd periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
							<li><a target="_blank" href="' . $rpjpd['url'] . '">' . $rpjpd['title'] . '</a></li>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'input_perencanaan_rpjpd') {
							$perencanaan_rpjpd = $this->functions->generatePage(array(
								'nama_page' => 'Input RPJPD ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[input_rpjpd periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							// $perencanaan_rpjpd['url'] .= '&id_periode_rpjpd=' . $jadwal_periode_item['id'];
							$body_pemda .= '
							<li><a target="_blank" href="' . $perencanaan_rpjpd['url'] . '">' . $perencanaan_rpjpd['title'] . '</a></li>';
						}
					}
					$body_pemda .= '</ol>';
					$ret['message'] .= $body_pemda;
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
						} else if (!empty($_POST['type']) && $_POST['type'] == 'dpa') {
							$dpa = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen DPA Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dpa tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $dpa['url'] . '">' . $dpa['title'] . '</a></li>
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
						} else if (!empty($_POST['type']) && $_POST['type'] == 'dokumen_lainnya_pemda') {
							$dokumen_lainnya_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Lainnya Pemda Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_dokumen_lainnya_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $dokumen_lainnya_pemda['url'] . '">' . $dokumen_lainnya_pemda['title'] . '</a></li>
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
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pohon_kinerja_dan_cascading') {
							$pohon_kinerja_cascading = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pohon Kinerja dan Cascading Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[pohon_kinerja_dan_cascading tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pohon_kinerja_cascading['url'] . '">' . $pohon_kinerja_cascading['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'lhe_akip_internal') {
							$lhe_akip_internal = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen LHE AKIP Internal Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[lhe_akip_internal tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $lhe_akip_internal['url'] . '">' . $lhe_akip_internal['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'tl_lhe_akip_internal') {
							$tl_lhe_akip_internal = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen TL LHE AKIP Internal Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[tl_lhe_akip_internal tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $tl_lhe_akip_internal['url'] . '">' . $tl_lhe_akip_internal['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'tl_lhe_akip_kemenpan') {
							$tl_lhe_akip_kemenpan = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen TL LHE AKIP Kemenpan Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[tl_lhe_akip_kemenpan tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $tl_lhe_akip_kemenpan['url'] . '">' . $tl_lhe_akip_kemenpan['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'laporan_monev_renaksi') {
							$laporan_monev_renaksi = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Monev Renaksi Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[laporan_monev_renaksi tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $laporan_monev_renaksi['url'] . '">' . $laporan_monev_renaksi['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pedoman_teknis_perencanaan') {
							$pedoman_teknis_perencanaan = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Pedoman Teknis Perencanaan Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[pedoman_teknis_perencanaan tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pedoman_teknis_perencanaan['url'] . '">' . $pedoman_teknis_perencanaan['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja') {
							$pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja['url'] . '">' . $pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pedoman_teknis_evaluasi_internal') {
							$pedoman_teknis_evaluasi_internal = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Pedoman Teknis Evaluasi Internal Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[pedoman_teknis_evaluasi_internal tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pedoman_teknis_evaluasi_internal['url'] . '">' . $pedoman_teknis_evaluasi_internal['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'skp_pemda') {
							$skp_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda SKP Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_skp_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $skp_pemda['url'] . '">' . $skp_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'rencana_aksi_pemda') {
							$rencana_aksi_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda Rencana Aksi Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_rencana_aksi_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $rencana_aksi_pemda['url'] . '">' . $rencana_aksi_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'iku_pemda') {
							$iku_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda IKU Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_iku_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $iku_pemda['url'] . '">' . $iku_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'laporan_kinerja_pemda') {
							$laporan_kinerja_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda Laporan Kinerja Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_laporan_kinerja_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $laporan_kinerja_pemda['url'] . '">' . $laporan_kinerja_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'dpa_pemda') {
							$dpa_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda DPA Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_dpa_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $dpa_pemda['url'] . '">' . $dpa_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'evaluasi_internal_pemda') {
							$evaluasi_internal_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda Evaluasi Internal Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_evaluasi_internal_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $evaluasi_internal_pemda['url'] . '">' . $evaluasi_internal_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'dokumen_lainnya_pemda') {
							$dokumen_lainnya_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda Lain Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_dokumen_lainnya_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $dokumen_lainnya_pemda['url'] . '">' . $dokumen_lainnya_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'perjanjian_kinerja_pemda') {
							$perjanjian_kinerja_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda Perjanjian Kinerja Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_perjanjian_kinerja_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $perjanjian_kinerja_pemda['url'] . '">' . $perjanjian_kinerja_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'rkpd_pemda') {
							$rkpd_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda RKPD Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_rkpd_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $rkpd_pemda['url'] . '">' . $rkpd_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'lkjip_pemda') {
							$lkjip_lppd_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda LKJIP/LPPD  ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_lkjip_lppd_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $lkjip_lppd_pemda['url'] . '">' . $lkjip_lppd_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pohon_kinerja_dan_cascading_pemda') {
							$pohon_kinerja_cascading_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda Pohon Kinerja dan Cascading Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_pohon_kinerja_dan_cascading_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pohon_kinerja_cascading_pemda['url'] . '">' . $pohon_kinerja_cascading_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'lhe_akip_internal_pemda') {
							$lhe_akip_internal_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda LHE AKIP Internal Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_lhe_akip_internal_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $lhe_akip_internal_pemda['url'] . '">' . $lhe_akip_internal_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'tl_lhe_akip_internal_pemda') {
							$tl_lhe_akip_internal_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda TL LHE AKIP Internal Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_tl_lhe_akip_internal_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $tl_lhe_akip_internal_pemda['url'] . '">' . $tl_lhe_akip_internal_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'tl_lhe_akip_kemenpan_pemda') {
							$tl_lhe_akip_kemenpan_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda TL LHE AKIP Kemenpan Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_tl_lhe_akip_kemenpan_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $tl_lhe_akip_kemenpan_pemda['url'] . '">' . $tl_lhe_akip_kemenpan_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'laporan_monev_renaksi_pemda') {
							$laporan_monev_renaksi_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda Monev Renaksi Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_laporan_monev_renaksi_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $laporan_monev_renaksi_pemda['url'] . '">' . $laporan_monev_renaksi_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pedoman_teknis_perencanaan_pemda') {
							$pedoman_teknis_perencanaan_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda Pedoman Teknis Perencanaan Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_pedoman_teknis_perencanaan_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pedoman_teknis_perencanaan_pemda['url'] . '">' . $pedoman_teknis_perencanaan_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda') {
							$pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda Pedoman Teknis Pengukuran Dan Pengumpulan Data Kinerja Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda['url'] . '">' . $pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pedoman_teknis_evaluasi_internal_pemda') {
							$pedoman_teknis_evaluasi_internal_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda Pedoman Teknis Evaluasi Internal Tahun ' . $tahun_item['tahun_anggaran'],
								'content' => '[dokumen_detail_pedoman_teknis_evaluasi_internal_pemda tahun=' . $tahun_item["tahun_anggaran"] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pedoman_teknis_evaluasi_internal_pemda['url'] . '">' . $pedoman_teknis_evaluasi_internal_pemda['title'] . '</a></li>
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

		$halaman_mapping_skpd = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Mapping Perangkat Daerah',
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
				Field::make('text', 'crb_maksimal_upload_dokumen_esakip', 'Maksimal Upload Dokumen')
					->set_default_value(10)
					->set_help_text('Wajib diisi. Setting batas ukuran maksimal untuk upload dokumen. Ukuran dalam MB'),
				Field::make('text', 'crb_nama_pemda', 'Nama Pemerintah Daerah')
					->set_help_text('Wajib diisi.'),
			));

		Container::make('theme_options', __('Pengaturan Perangkat Daerah'))
			->set_page_parent($basic_options_container)
			->add_fields(array(
				Field::make('html', 'crb_esakip_halaman_terkait')
					->set_html('
					<h4>HALAMAN TERKAIT</h4>
	            	<ol>
	            		<li><a href="' . $halaman_mapping_skpd['url'] . '">' . $halaman_mapping_skpd['title'] . '</a></li>
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
					->set_help_text('Data user active yang ada di table data unit akan digenerate menjadi user wordpress.'),
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

		Container::make('theme_options', __('Menu Setting'))
			->set_page_parent($basic_options_container)
			->add_fields($this->generate_menu());

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
			->add_fields(array(
				Field::make('html', 'crb_rpjpd_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'rpjpd')));

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

		Container::make('theme_options', __('IKU'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_iku_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'iku_pemda')));

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
			->add_fields($this->get_ajax_field(array('type' => 'rkpd_pemda')));

		Container::make('theme_options', __('Perjanjian Kinerja'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_perjanjian_kinerja_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'perjanjian_kinerja_pemda')));

		Container::make('theme_options', __('Laporan Kinerja'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_laporan_kinerja_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'laporan_kinerja_pemda')));

		Container::make('theme_options', __('DPA'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_renja_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'dpa_pemda')));

		Container::make('theme_options', __('Pohon Kinerja dan Cascading'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_pohon_kinerja_dan_cascading_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'pohon_kinerja_dan_cascading_pemda')));

		Container::make('theme_options', __('LHE AKIP Internal'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_lhe_akip_internal_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'lhe_akip_internal_pemda')));

		Container::make('theme_options', __('TL LHE AKIP Internal'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_tl_lhe_akip_internal_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'tl_lhe_akip_internal_pemda')));

		Container::make('theme_options', __('TL LHE AKIP Kemenpan'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_tl_lhe_akip_kemenpan_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'tl_lhe_akip_kemenpan_pemda')));

		Container::make('theme_options', __('Laporan Monev Renaksi'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_laporan_monev_renaksi_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'laporan_monev_renaksi_pemda')));

		Container::make('theme_options', __('Pedoman Teknis Perencanaan'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_pedoman_teknis_perencanaan_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'pedoman_teknis_perencanaan_pemda')));

		Container::make('theme_options', __('Pedoman Teknis Pengukuran dan Pengumpulan Data Kinerja'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_pemda')));

		Container::make('theme_options', __('Pedoman Teknis Evaluasi Internal'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_pedoman_teknis_evaluasi_internal_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'pedoman_teknis_evaluasi_internal_pemda')));

		Container::make('theme_options', __('Rencana Aksi'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_rencana_aksi_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'rencana_aksi_pemda')));

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
			->add_fields($this->get_ajax_field(array('type' => 'lkjip_pemda')));

		Container::make('theme_options', __('Dokumen Lainnya'))
			->set_page_parent($dokumen_pemda_menu)
			->add_fields(array(
				Field::make('html', 'crb_dokumen_lainnya_pemda_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'dokumen_lainnya_pemda')));

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

		Container::make('theme_options', __('DPA'))
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
			->add_fields($this->get_ajax_field(array('type' => 'dpa')));

		Container::make('theme_options', __('Pohon Kinerja dan Cascading'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_pohon_kinerja_dan_cascading_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'pohon_kinerja_dan_cascading')));

		Container::make('theme_options', __('LHE AKIP Internal'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_lhe_akip_internal_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'lhe_akip_internal')));

		Container::make('theme_options', __('TL LHE AKIP Internal'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_tl_lhe_akip_internal_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'tl_lhe_akip_internal')));

		Container::make('theme_options', __('Laporan Monev Renaksi'))
			->set_page_parent($dokumen_menu)
			->add_fields(array(
				Field::make('html', 'crb_laporan_monev_renaksi_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'laporan_monev_renaksi')));

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
		// Container::make('theme_options', __('Pengukuran Kinerja'))
		// 	->set_page_parent($dokumen_menu)
		// 	->add_fields(array(
		// 		Field::make('html', 'crb_pengukuran_kinerja_hide_sidebar')
		// 			->set_html('
		// 				<style>
		// 					.postbox-container { display: none; }
		// 					#poststuff #post-body.columns-2 { margin: 0 !important; }
		// 				</style>
		// 			')
		// 	))
		// 	->add_fields($this->get_ajax_field(array('type' => 'pengukuran_kinerja')));
		// Container::make('theme_options', __('Pengukuran Rencana Aksi'))
		// 	->set_page_parent($dokumen_menu)
		// 	->add_fields(array(
		// 		Field::make('html', 'crb_pengukuran_rencana_aksi_hide_sidebar')
		// 			->set_html('
		// 			<style>
		// 				.postbox-container { display: none; }
		// 				#poststuff #post-body.columns-2 { margin: 0 !important; }
		// 			</style>
		// 		')
		// 	))
		// 	->add_fields($this->get_ajax_field(array('type' => 'pengukuran_rencana_aksi')));

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

		// Container::make('theme_options', __('TL LHE AKIP Kemenpan'))
		// ->set_page_parent($dokumen_menu)
		// ->add_fields(array(
		// 	Field::make('html', 'crb_tl_lhe_akip_kemenpan_hide_sidebar')
		// 		->set_html('
		// 			<style>
		// 				.postbox-container { display: none; }
		// 				#poststuff #post-body.columns-2 { margin: 0 !important; }
		// 			</style>
		// 		')
		// ))
		// ->add_fields($this->get_ajax_field(array('type' => 'tl_lhe_akip_kemenpan')));

		// Container::make('theme_options', __('Laporan Monev Renaksi'))
		// ->set_page_parent($dokumen_menu)
		// ->add_fields(array(
		// 	Field::make('html', 'crb_laporan_monev_renaksi_hide_sidebar')
		// 		->set_html('
		// 			<style>
		// 				.postbox-container { display: none; }
		// 				#poststuff #post-body.columns-2 { margin: 0 !important; }
		// 			</style>
		// 		')
		// ))
		// ->add_fields($this->get_ajax_field(array('type' => 'laporan_monev_renaksi')));

		// Container::make('theme_options', __('Pedoman Teknis Perencanaan'))
		// ->set_page_parent($dokumen_menu)
		// ->add_fields(array(
		// 	Field::make('html', 'crb_pedoman_teknis_perencanaan_hide_sidebar')
		// 		->set_html('
		// 			<style>
		// 				.postbox-container { display: none; }
		// 				#poststuff #post-body.columns-2 { margin: 0 !important; }
		// 			</style>
		// 		')
		// ))
		// ->add_fields($this->get_ajax_field(array('type' => 'pedoman_teknis_perencanaan')));

		// Container::make('theme_options', __('Pedoman Teknis Pengukuran dan Pengumpulan Data Kinerja'))
		// ->set_page_parent($dokumen_menu)
		// ->add_fields(array(
		// 	Field::make('html', 'crb_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja_hide_sidebar')
		// 		->set_html('
		// 			<style>
		// 				.postbox-container { display: none; }
		// 				#poststuff #post-body.columns-2 { margin: 0 !important; }
		// 			</style>
		// 		')
		// ))
		// ->add_fields($this->get_ajax_field(array('type' => 'pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja')));

		// Container::make('theme_options', __('Pedoman Teknis Evaluasi Internal'))
		// ->set_page_parent($dokumen_menu)
		// ->add_fields(array(
		// 	Field::make('html', 'crb_pedoman_teknis_evaluasi_internal_hide_sidebar')
		// 		->set_html('
		// 			<style>
		// 				.postbox-container { display: none; }
		// 				#poststuff #post-body.columns-2 { margin: 0 !important; }
		// 			</style>
		// 		')
		// ))
		// ->add_fields($this->get_ajax_field(array('type' => 'pedoman_teknis_evaluasi_internal')));

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

		$pengisian_lke_menu = Container::make('theme_options', __('Pengisian LKE SAKIP'))
			->set_page_menu_position(3.3)
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

		$pengisian_pokin_menu = Container::make('theme_options', __('Pohon Kinerja'))
			->set_page_menu_position(3.4)
			->set_icon('dashicons-edit-page')
			->add_fields(array(
				Field::make('html', 'crb_pengisian_pokin_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	')
			))
			->add_fields($this->get_ajax_field(array('type' => 'input_pohon_kinerja_pemda')));

		Container::make('theme_options', __('Pohon Kinerja Pemerintah Daerah'))
			->set_page_parent($pengisian_pokin_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengisian_pokin_pemda_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'input_pohon_kinerja_pemda')));

		Container::make('theme_options', __('Cascading Pemerintah Daerah'))
			->set_page_parent($pengisian_pokin_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengisian_cascading_pemda_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'cascading_pemda')));

		Container::make('theme_options', __('Croscutting Pemerintah Daerah'))
			->set_page_parent($pengisian_pokin_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengisian_croscutting_pemda_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'croscutting_pemda')));

		Container::make('theme_options', __('Pohon Kinerja Perangkat Daerah'))
			->set_page_parent($pengisian_pokin_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengisian_pokin_pd_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'input_pohon_kinerja_opd')));

		Container::make('theme_options', __('Cascading Perangkat Daerah'))
			->set_page_parent($pengisian_pokin_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengisian_cascading_pd_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'cascading_pd')));

		Container::make('theme_options', __('Croscutting Perangkat Daerah'))
			->set_page_parent($pengisian_pokin_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengisian_croscutting_pd_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'croscutting_pd')));

		$monev_ren_aksi_menu = Container::make('theme_options', __('MONEV Rencana Aksi'))
			->set_page_menu_position(3.5)
			->set_icon('dashicons-edit-page')
			->add_fields(array(
				Field::make('html', 'crb_monev_pokin_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	')
			));

		Container::make('theme_options', __('MONEV Rencana Aksi Pemerintah Daerah'))
			->set_page_parent($monev_ren_aksi_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengisian_monev_pemda_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'monev_rencana_aksi_pemda')));

		Container::make('theme_options', __('MONEV Rencana Aksi Perangkat Daerah'))
			->set_page_parent($monev_ren_aksi_menu)
			->add_fields(array(
				Field::make('html', 'crb_pengisian_monev_pd_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'monev_rencana_aksi_opd')));

		Container::make('theme_options', __('Input RPJPD'))
			->set_page_menu_position(3.6)
			->add_fields(array(
				Field::make('html', 'crb_perencanaan_rpjpd_hide_sidebar')
					->set_html('
						<style>
							.postbox-container { display: none; }
							#poststuff #post-body.columns-2 { margin: 0 !important; }
						</style>
					')
			))
			->add_fields($this->get_ajax_field(array('type' => 'input_perencanaan_rpjpd')));

		Container::make('theme_options', __('Input RPJMD'))
			->set_page_menu_position(3.7)
			->add_fields(array(
				Field::make('html', 'crb_perencanaan_rpjmd_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'input_perencanaan_rpjmd')));

		// Container::make('theme_options', __('RKPD'))
		// 	->set_page_parent($dokumen_pemda_menu)
		// 	->add_fields(array(
		// 		Field::make('html', 'crb_rkpd_hide_sidebar')
		// 			->set_html('
		// 				<style>
		// 					.postbox-container { display: none; }
		// 					#poststuff #post-body.columns-2 { margin: 0 !important; }
		// 				</style>
		// 			')
		// 	))
		// 	->add_fields($this->get_ajax_field(array('type' => 'rkpd')));

		// Container::make('theme_options', __('LKJIP/LPPD'))
		// 	->set_page_parent($dokumen_pemda_menu)
		// 	->add_fields(array(
		// 		Field::make('html', 'crb_lkjip_lppd_hide_sidebar')
		// 			->set_html('
		// 				<style>
		// 					.postbox-container { display: none; }
		// 					#poststuff #post-body.columns-2 { margin: 0 !important; }
		// 				</style>
		// 			')
		// 	))
		// 	->add_fields($this->get_ajax_field(array('type' => 'lkjip')));
	}

	public function generate_menu()
	{
		global $wpdb;
		$get_tahun = $wpdb->get_results('select tahun_anggaran from esakip_data_unit group by tahun_anggaran order by tahun_anggaran ASC', ARRAY_A);
		$list_data = '';

		foreach ($get_tahun as $k => $v) {
			$jadwal_evaluasi = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Pengaturan Menu Tahun Anggaran | ' . $v['tahun_anggaran'],
				'content' => '[pengaturan_menu tahun_anggaran="' . $v["tahun_anggaran"] . '"]',
				'show_header' => 1,
				'no_key' => 1,
				'post_status' => 'private'
			));
			$list_data .= '<li><a target="_blank" href="' . $jadwal_evaluasi['url'] . '">' . $jadwal_evaluasi['title'] . '</a></li>';
		}

		$label = array(
			Field::make('html', 'crb_pengaturan_menu')
				->set_html('
					<ol>' . $list_data . '</ol>
				')
		);
		return $label;
	}

	public function generate_jadwal()
	{
		global $wpdb;
		$get_tahun = $wpdb->get_results('select tahun_anggaran from esakip_data_unit group by tahun_anggaran order by tahun_anggaran ASC', ARRAY_A);
		$list_data = '';

		// jadwal rpjpd
		$jadwal_rpjpd = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Jadwal RPJPD',
			'content' => '[jadwal_rpjpd]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));
		$list_data .= '<li><a target="_blank" href="' . $jadwal_rpjpd['url'] . '">' . $jadwal_rpjpd['title'] . '</a></li>';

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
				'nama_page' => 'Halaman Jadwal LKE Tahun Anggaran | ' . $v['tahun_anggaran'],
				'content' => '[jadwal_evaluasi_sakip tahun_anggaran="' . $v["tahun_anggaran"] . '"]',
				'show_header' => 1,
				'no_key' => 1,
				'post_status' => 'private'
			));
			$list_data .= '<li><a target="_blank" href="' . $jadwal_evaluasi['url'] . '">' . $jadwal_evaluasi['title'] . '</a></li>';
		}

		// jadwal verifikasi upload dokumen
		$no = 0;
		foreach ($get_tahun as $k => $v) {
			$jadwal_verifikasi = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Jadwal Verifikasi Upload Dokumen Tahun Anggaran | ' . $v['tahun_anggaran'],
				'content' => '[jadwal_verifikasi_upload_dokumen tahun_anggaran="' . $v["tahun_anggaran"] . '"]',
				'show_header' => 1,
				'no_key' => 1,
				'post_status' => 'private'
			));
			$list_data .= '<li><a target="_blank" href="' . $jadwal_verifikasi['url'] . '">' . $jadwal_verifikasi['title'] . '</a></li>';
		}

		//sementara dipending dahulu 
		// $jadwal_verifikasi_renstra = $this->functions->generatePage(array(
		// 	'nama_page' => 'Halaman Jadwal Verifikasi Upload Dokumen | RENSTRA',
		// 	'content' => '[jadwal_verifikasi_upload_dokumen_renstra]',
		// 	'show_header' => 1,
		// 	'no_key' => 1,
		// 	'post_status' => 'private'
		// ));
		// $list_data .= '<li><a target="_blank" href="' . $jadwal_verifikasi_renstra['url'] . '">' . $jadwal_verifikasi_renstra['title'] . '</a></li>';

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
		$this->functions->allow_access_private_post();
	}
}
