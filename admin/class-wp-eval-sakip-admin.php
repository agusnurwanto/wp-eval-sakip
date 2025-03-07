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
						|| $_POST['type'] == 'croscutting_pemda'
						|| $_POST['type'] == 'croscutting_pd'
						|| $_POST['type'] == 'pohon_kinerja_dan_cascading'
						|| $_POST['type'] == 'pohon_kinerja_dan_cascading_pemda'
					)
				) {
					$jadwal_periode = $wpdb->get_results(
						$wpdb->prepare(
							"
							SELECT 
						        j.id,
						        j.nama_jadwal,
						        j.nama_jadwal_renstra,
						        j.tahun_anggaran,
						        j.lama_pelaksanaan,
						        j.tahun_selesai_anggaran
						    FROM esakip_data_jadwal j
						    WHERE j.tipe = %s
						      AND j.status = 1
						    ORDER BY j.tahun_anggaran DESC",
							'RPJMD'
						),
						ARRAY_A
					);

					$body_pemda = '<ol>';
					foreach ($jadwal_periode as $jadwal_periode_item) {
						// Cek setting tahun anggaran selesai
						if (!empty($jadwal_periode_item['tahun_selesai_anggaran']) && $jadwal_periode_item['tahun_selesai_anggaran'] > 1) {
							$tahun_anggaran_selesai = $jadwal_periode_item['tahun_selesai_anggaran'];
						} else {
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
								'nama_page' => 'List Halaman Input Pohon Kinerja Perangkat Daerah ' . $jadwal_periode_item['nama_jadwal_renstra'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[list_penyusunan_pohon_kinerja_opd periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$title = 'Halaman Input Pohon Kinerja Perangkat Daerah ' . $jadwal_periode_item['nama_jadwal_renstra'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai;
							$body_pemda .= '
								<li><a target="_blank" href="' . $input_pokin['url'] . '">' . $title . '</a></li>';
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
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pohon_kinerja_dan_cascading') {
							$pohon_kinerja_cascading = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pohon Kinerja dan Cascading Tahun ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[pohon_kinerja_dan_cascading periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
							<li><a target="_blank" href="' . $pohon_kinerja_cascading['url'] . '">' . $pohon_kinerja_cascading['title'] . '</a></li>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'pohon_kinerja_dan_cascading_pemda') {
							$pohon_kinerja_cascading = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Dokumen Pemda Pohon Kinerja dan Cascading ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
								'content' => '[dokumen_detail_pohon_kinerja_dan_cascading_pemda periode=' . $jadwal_periode_item['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
							<li><a target="_blank" href="' . $pohon_kinerja_cascading['url'] . '">Halaman Dokumen Pohon Kinerja dan Cascading ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . '</a></li>';
						}
					}
					$body_pemda .= '</ol>';
					$ret['message'] .= $body_pemda;
				} else if (
					!empty($_POST['type'])
					&&
					$_POST['type'] == 'monev_rencana_aksi_pemda'
				) {

					$jadwal_periode = $wpdb->get_results(
						$wpdb->prepare(
							"
							SELECT 
						        j.id,
						        j.nama_jadwal,
						        j.nama_jadwal_renstra,
						        j.tahun_anggaran,
						        j.lama_pelaksanaan,
						        j.tahun_selesai_anggaran,
						        r.id_jadwal_rpjmd,
						        r.tahun_anggaran as tahun_anggaran_menu
						    FROM esakip_data_jadwal j
						    LEFT JOIN esakip_pengaturan_upload_dokumen r
						        ON r.id_jadwal_rpjmd = j.id
						    WHERE j.tipe = %s
						      AND j.status = 1
						    ORDER BY j.tahun_anggaran DESC",
							'RPJMD'
						),
						ARRAY_A
					);

					$body_pemda = '<ol>';
					foreach ($jadwal_periode as $jadwal_periode_item) {
						// Cek setting tahun anggaran selesai
						if (!empty($jadwal_periode_item['tahun_selesai_anggaran']) && $jadwal_periode_item['tahun_selesai_anggaran'] > 1) {
							$tahun_anggaran_selesai = $jadwal_periode_item['tahun_selesai_anggaran'];
						} else {
							$tahun_anggaran_selesai = $jadwal_periode_item['tahun_anggaran'] + $jadwal_periode_item['lama_pelaksanaan'];
						}

						$tahun = $wpdb->get_results("
							SELECT 
								u.tahun_anggaran
							FROM esakip_data_unit u
							GROUP BY u.tahun_anggaran
							ORDER BY u.tahun_anggaran DESC
						", ARRAY_A);
						foreach ($tahun as $tahun_item) {
							if (
								!empty($jadwal_periode_item['id_jadwal_rpjmd'])
								&& $jadwal_periode_item['tahun_anggaran_menu'] == $tahun_item['tahun_anggaran']
							) {
								$list_pemda_pengisian_rencana_aksi = $this->functions->generatePage(array(
									'nama_page' => 'Input Rencana Hasil Kerja Pemda Tahun ' . $tahun_item['tahun_anggaran'] . ' | ' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai,
									'content' => '[list_pengisian_rencana_aksi_pemda tahun=' . $tahun_item['tahun_anggaran'] . ' periode=' . $jadwal_periode_item['id'] . ' ]',
									'show_header' => 1,
									'no_key' => 1,
									'post_status' => 'private'
								));
								$body_pemda .= '<li><a target="_blank" href="' . $list_pemda_pengisian_rencana_aksi['url'] . '" class="btn btn-primary">' .  $list_pemda_pengisian_rencana_aksi['title'] . '</a></li>';
							}
						}
						if (empty($jadwal_periode_item['id_jadwal_rpjmd'])) {
							$body_pemda .= '<li>' . $jadwal_periode_item['nama_jadwal'] . ' ' . 'Periode ' . $jadwal_periode_item['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ' ( Periode belum diset di pengaturan menu )</li>';
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
							  	AND status =1
							ORDER BY tahun_anggaran DESC",
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
						        j.id,
						        j.nama_jadwal,
						        j.nama_jadwal_renstra,
						        j.tahun_anggaran,
						        j.lama_pelaksanaan,
						        j.tahun_selesai_anggaran,
						        r.id_jadwal_rpjpd
						    FROM esakip_data_jadwal j
						    LEFT JOIN esakip_pengaturan_upload_dokumen r
						        ON r.id_jadwal_rpjpd = j.id
						    WHERE j.tipe = %s
						      AND j.status = 1
						    ORDER BY j.tahun_anggaran DESC",
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
				} else if (
					!empty($_POST['type'])
					&& (
						$_POST['type'] == 'input_iku_opd'
						|| $_POST['type'] == 'cascading_pd'
					)
				) {
					//jadwal renstra wpsipd
					$api_params = array(
						'action' => 'get_data_jadwal_wpsipd',
						'api_key'	=> get_option('_crb_apikey_wpsipd'),
						'tipe_perencanaan' => 'monev_renstra'
					);

					$response = wp_remote_post(get_option('_crb_url_server_sakip'), array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));

					$response = wp_remote_retrieve_body($response);

					$data_jadwal_wpsipd = json_decode($response);

					$body_pemda = '';
					if (!empty($_POST['type']) && $_POST['type'] == 'cascading_pd') {
						$body_pemda = '<h3>Halaman Input Cascading Perangkat Daerah</h3>';
					} else if (!empty($_POST['type']) && $_POST['type'] == 'input_iku_opd') {
						$body_pemda = '<h3>Halaman Input IKU</h3>';
					}
					$body_pemda .= '<ol>';
					if (!empty($data_jadwal_wpsipd->data)) {
						foreach ($data_jadwal_wpsipd->data as $jadwal_periode_item_wpsipd) {
							if (!empty($jadwal_periode_item_wpsipd->tahun_akhir_anggaran) && $jadwal_periode_item_wpsipd->tahun_akhir_anggaran > 1) {
								$tahun_anggaran_selesai = $jadwal_periode_item_wpsipd->tahun_akhir_anggaran;
							} else {
								$tahun_anggaran_selesai = $jadwal_periode_item_wpsipd->tahun_anggaran + $jadwal_periode_item_wpsipd->lama_pelaksanaan;
							}
							if (!empty($_POST['type']) && $_POST['type'] == 'cascading_pd') {
								$input_cascading_opd = $this->functions->generatePage(array(
									'nama_page' => 'Halaman Input Cascading Perangkat Daerah ' . $jadwal_periode_item_wpsipd->nama . ' ' . 'Periode ' . $jadwal_periode_item_wpsipd->tahun_anggaran . ' - ' . $tahun_anggaran_selesai,
									'content' => '[cascading_pd periode=' . $jadwal_periode_item_wpsipd->id_jadwal_lokal . ']',
									'show_header' => 1,
									'post_status' => 'private'
								));
								$title = 'Input Cascading OPD | ' . $jadwal_periode_item_wpsipd->nama . ' ' . 'Periode ' . $jadwal_periode_item_wpsipd->tahun_anggaran . ' - ' . $tahun_anggaran_selesai;
								$body_pemda .= '<li><a target="_blank" href="' . $input_cascading_opd['url'] . '">' . $title . '</a></li>';
							} else if (!empty($_POST['type']) && $_POST['type'] == 'input_iku_opd') {
								$input_iku = $this->functions->generatePage(array(
									'nama_page' => 'List Perangkat Daerah Halaman Pengisian IKU ' . $jadwal_periode_item_wpsipd->nama . ' ' . 'Periode ' . $jadwal_periode_item_wpsipd->tahun_anggaran . ' - ' . $tahun_anggaran_selesai,
									'content' => '[list_input_iku periode=' . $jadwal_periode_item_wpsipd->id_jadwal_lokal . ']',
									'show_header' => 1,
									'no_key' => 1,
									'post_status' => 'private'
								));
								$body_pemda .= '
								<li><a target="_blank" href="' . $input_iku['url'] . '">Halaman Input IKU Perangkat Daerah ' . $jadwal_periode_item_wpsipd->nama . ' ' . 'Periode ' . $jadwal_periode_item_wpsipd->tahun_anggaran . ' - ' . $tahun_anggaran_selesai . '</a></li>';
							}
						}
					}
					$body_pemda .= '</ol>';
					$ret['message'] .= $body_pemda;
				} else if (
					!empty($_POST['type'])
					&& $_POST['type'] == 'input_iku_pemda'
				) {
					//jadwal rpjmd/rpd sakip
					$data_jadwal = $wpdb->get_results(
						$wpdb->prepare("
						SELECT
							*
						FROM
							esakip_data_jadwal
						WHERE
							tipe='RPJMD'
							AND status!=0"),
						ARRAY_A
					);

					if (empty($data_jadwal)) {
						die("JADWAL KOSONG");
					}

					$body_pemda = '<h3>Halaman Input IKU</h3>';
					$body_pemda .= '<ol>';
					if (!empty($data_jadwal)) {
						foreach ($data_jadwal as $jadwal_periode) {
							$lama_pelaksanaan = $jadwal_periode['lama_pelaksanaan'] ?? 4;
							$tahun_anggaran = $jadwal_periode['tahun_anggaran'];
							$tahun_awal = $jadwal_periode['tahun_anggaran'];
							$tahun_akhir = $tahun_awal + $jadwal_periode['lama_pelaksanaan'] - 1;

							$input_iku_pemda = $this->functions->generatePage(array(
								'nama_page' => 'Halaman Pengisian IKU Pemerintah Daerah ' . $jadwal_periode['jenis_jadwal_khusus'] . ' ' . $jadwal_periode['nama_jadwal'] . ' ' . 'Periode ' . $tahun_awal . ' - ' . $tahun_akhir,
								'content' => '[input_iku_pemda id_periode=' . $jadwal_periode['id'] . ']',
								'show_header' => 1,
								'no_key' => 1,
								'post_status' => 'private'
							));
							$body_pemda .= '
							<li><a target="_blank" href="' . $input_iku_pemda['url'] . '">Halaman Input IKU Pemerintah Daerah ' . $jadwal_periode['jenis_jadwal_khusus'] . ' ' . $jadwal_periode['nama_jadwal'] . ' ' . 'Periode ' . $tahun_awal . ' - ' . $tahun_akhir . '</a></li>';
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
							ORDER BY tahun_anggaran DESC
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
						} else if (!empty($_POST['type']) && $_POST['type'] == 'monev_rencana_aksi_opd') {
							$list_skpd_pengisian_rencana_aksi = $this->functions->generatePage(array(
								'nama_page' => 'Input Rencana Hasil Kerja - ' . $tahun_item['tahun_anggaran'],
								'content' => '[list_pengisian_rencana_aksi tahun=' . $tahun_item['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $list_skpd_pengisian_rencana_aksi['url'] . '">' . $list_skpd_pengisian_rencana_aksi['title'] . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'monev_rencana_aksi_setting') {
							$jadwal_renstra = $wpdb->get_results(
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
							$opsion_jadwal_renstra = "<option value=''>Pilih Periode</option>";

							foreach ($jadwal_renstra as $v_renstra) {
								if (!empty($v_renstra['tahun_selesai_anggaran']) && $v_renstra['tahun_selesai_anggaran'] > 1) {
									$tahun_anggaran_selesai = $v_renstra['tahun_selesai_anggaran'];
								} else {
									$tahun_anggaran_selesai = $v_renstra['tahun_anggaran'] + $v_renstra['lama_pelaksanaan'];
								}

								$opsion_jadwal_renstra .= "<option value='" . $v_renstra['id'] . "'>" . $v_renstra['nama_jadwal_renstra'] . " Periode " . $v_renstra['tahun_anggaran'] . " - " . $tahun_anggaran_selesai . "</option>";
							}
							$pengisian_rencana_aksi_setting = $this->functions->generatePage(array(
								'nama_page' => 'Input Rencana Hasil Kerja Setting ',
								'content' => '[pengisian_rencana_aksi_setting]',
								'show_header' => 1,
								'post_status' => 'private'
							));
							$title = 'Rencana Aksi Setting';
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $pengisian_rencana_aksi_setting['url'] . '&tahun_anggaran=' . $tahun_item['tahun_anggaran'] . '">' . $title . '</a></li>
									</ul>
								</div>
							</div>';
						} else if (!empty($_POST['type']) && $_POST['type'] == 'laporan_pk_opd') {
							$list_skpd_laporan_pk = $this->functions->generatePage(array(
								'nama_page' => 'Laporan PK Perangkat Daerah - ' . $tahun_item['tahun_anggaran'],
								'content' => '[list_halaman_laporan_pk tahun=' . $tahun_item['tahun_anggaran'] . ']',
								'show_header' => 1,
								'post_status' => 'private'
							));
							$body_pemda = '
							<div class="accordion">
								<h3 class="esakip-header-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">Tahun Anggaran ' . $tahun_item['tahun_anggaran'] . '</h3>
								<div class="esakip-body-tahun" tahun="' . $tahun_item['tahun_anggaran'] . '">
									<ul style="margin-left: 20px;">
										<li><a target="_blank" href="' . $list_skpd_laporan_pk['url'] . '">' . $list_skpd_laporan_pk['title'] . '</a></li>
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
		$halaman_mapping_sipd_simpeg = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Mapping Perangkat Daerah SIPD-SIMPEG',
			'content' => '[halaman_mapping_sipd_simpeg]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));
		$halaman_mapping_user_esr = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Mapping User ESR',
			'content' => '[halaman_mapping_user_esr]',
			'show_header' => 1,
			'no_key' => 1,
			'post_status' => 'private'
		));
		$list_skpd_laporan_pk_setting = $this->functions->generatePage(array(
			'nama_page' => 'Laporan PK Setting',
			'content' => '[halaman_laporan_pk_setting]',
			'show_header' => 1,
			'post_status' => 'private'
		));

		$get_tahun = $wpdb->get_results('select tahun_anggaran from esakip_data_unit group by tahun_anggaran order by tahun_anggaran ASC', ARRAY_A);
		$list_mapping_jenis_dokumen = '';
		$halaman_monitor_upload_dokumen = '';
		foreach ($get_tahun as $k => $v) {
			$halaman_mapping_jenis_dokumen = $this->functions->generatePage(array(
				'nama_page' => 'Halaman Mapping Jenis Dokumen Tahun Anggaran | ' . $v['tahun_anggaran'],
				'content' => '[halaman_mapping_jenis_dokumen tahun_anggaran="' . $v['tahun_anggaran'] . '"]',
				'show_header' => 1,
				'no_key' => 1,
				'post_status' => 'private'
			));
			$list_mapping_jenis_dokumen .= '<li><a target="_blank" href="' . $halaman_mapping_jenis_dokumen['url'] . '">' . $halaman_mapping_jenis_dokumen['title'] . '</a></li>';

			$monitor_upload_dokumen = $this->functions->generatePage(array(
				'nama_page' => 'Laporan Upload Dokumen' . $v['tahun_anggaran'],
				'content' => '[halaman_cek_dokumen tahun_anggaran=' . $v['tahun_anggaran'] . ']',
				'show_header' => 1,
				'post_status' => 'private'
			));
			$halaman_monitor_upload_dokumen .= '<li><a target="_blank" href="' . $monitor_upload_dokumen['url'] . '" class="btn btn-primary">Laporan Monitor Upload Dokumen Tahun ' . $v['tahun_anggaran'] . ' </a></li>';
		}

		$url_api = get_option('_crb_url_api_esr');
		$url_testing_esr = "";
		if(!empty($url_api)){
			$url_testing_esr = '(<b> '. $url_api . 'get_user_id | Content-Type: application/json; charset=utf-8 | Authorization: Basic ' . base64_encode(get_option('_crb_username_api_esr') . ':' . get_option('_crb_password_api_esr')).' </b>)';
		}

		$basic_options_container = Container::make('theme_options', __('E-SAKIP Options'))
			->set_page_menu_position(3)
			->add_fields(array(
				Field::make('html', 'crb_esakip_halaman_terkait')
					->set_html('
					<h4>HALAMAN TERKAIT</h4>
	            	<ol>
	            		' . $halaman_monitor_upload_dokumen . '
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
				Field::make('image', 'crb_logo_dashboard', __('Logo Pemerintah Daerah'))
					->set_value_type('url'),
				Field::make('text', 'crb_kepala_daerah', 'Kepala Daerah')
					->set_help_text('Wajib diisi.'),
				Field::make('select', 'crb_status_jabatan_kepala_daerah', 'Status Jabatan Kepala Daerah')
					->add_options(array(
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
					))
					->set_default_value('Bupati')
					->set_help_text('Wajib diisi. Untuk kerpeluan status jabatan kepala daerah.'),
				Field::make('radio', 'crb_api_simpeg_status', 'Status API Kepegawaian')
					->add_options(array(
						'0' => __('Dikunci'),
						'1' => __('Dibuka')
					))
					->set_default_value('0')
					->set_help_text('Digunakan untuk mengunci atau membuka akses untuk mengakses data kepegawaian.'),
				Field::make('text', 'crb_url_api_simpeg', 'Url API Kepegawaian')
					->set_help_text('Wajib diisi. Url integrasi ke server SIMPEG.'),
				Field::make('text', 'crb_authorization_api_simpeg', 'Authorization API Kepegawaian')
					->set_help_text('Wajib diisi. Basic Auth encrypted.'),
				Field::make('radio', 'crb_api_esr_status', 'Status API ESR')
					->add_options(array(
						'0' => __('Dikunci'),
						'1' => __('Dibuka')
					))
					->set_default_value('1')
					->set_help_text('Digunakan untuk mengunci atau membuka akses untuk kirim dokumen ke aplikasi ESR Menpan RB'),
				Field::make('text', 'crb_url_api_esr', 'Url API ESR')
					->set_help_text('Wajib diisi. URL integrasi dokumen ke API ESR. '.$url_testing_esr),
				Field::make('text', 'crb_username_api_esr', 'Username API ESR')
					->set_help_text('Wajib diisi. Auth Type : Basic Auth dengan Username yang digunakan untuk integrasi dokumen ke API ESR'),
				Field::make('text', 'crb_password_api_esr', 'Password API ESR')
					->set_help_text('Wajib diisi. Auth Type : Basic Auth dengan Password yang digunakan untuk integrasi dokumen ke API ESR'),
				Field::make('text', 'crb_expired_time_esr_lokal', 'Waktu Expired Akses Data ESR Lokal (Minimal 1 Detik)')
					->set_default_value('60')
					->set_help_text('Wajib diisi. Waktu maksimal system menggunakan data ESR yang disimpan di lokal. Jika melebihi waktu maksimal maka data ESR lokal akan diupdate berdasarkan data ESR terbaru via API.'),
				Field::make('radio', 'crb_api_ekinerja_status', 'Status API E-Kinerja')
					->add_options(array(
						'0' => __('Dikunci'),
						'1' => __('Dibuka')
					))
					->set_default_value('1')
					->set_help_text('Digunakan untuk mengunci atau membuka akses data E-Kinerja'),
				Field::make('text', 'crb_url_api_ekinerja', 'Url API E-Kinerja')
					->set_help_text('Wajib diisi. URL integrasi dokumen ke API E-Kinerja'),
				Field::make('text', 'crb_api_key_ekinerja', 'API KEY E-Kinerja')
					->set_help_text('Wajib diisi. API KEY digunakan untuk integrasi ke API E-Kinerja'),
			));

		Container::make('theme_options', __('Pengaturan Perangkat Daerah'))
			->set_page_parent($basic_options_container)
			->add_fields(array(
				Field::make('html', 'crb_esakip_halaman_terkait')
					->set_html('
					<h4>HALAMAN TERKAIT</h4>
	            	<ol>
	            		<li><a href="' . $halaman_mapping_skpd['url'] . '" target="_blank">' . $halaman_mapping_skpd['title'] . '</a></li>
	            		<li><a href="' . $halaman_mapping_sipd_simpeg['url'] . '" target="_blank">' . $halaman_mapping_sipd_simpeg['title'] . '</a></li>
	            		<li><a href="' . $halaman_mapping_user_esr['url'] . '" target="_blank">' . $halaman_mapping_user_esr['title'] . '</a></li>
						<li><a href="' . $list_skpd_laporan_pk_setting['url'] . '" target="_blank">Halaman Profile Perangkat Daerah</a></li>
	            		' . $list_mapping_jenis_dokumen . '
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
					->set_html('<a id="generate_user_esakip" onclick="return false;" href="#" class="button button-primary button-large">Generate User SIPD By DB Lokal</a>')
					->set_help_text('Data user active yang ada di table data unit akan digenerate menjadi user wordpress.'),
				Field::make('html', 'crb_generate_user_pegawai_simpeg')
					->set_html('<a id="generate_user_esakip_pegawai_simpeg" onclick="return false;" href="#" class="button button-primary button-large">Generate User SIMPEG By DB Lokal</a>')
					->set_help_text('Data user active yang ada di table data pegawai simpeg akan digenerate menjadi user wordpress.'),
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

		Container::make('theme_options', __('Tampilan Beranda'))
			->set_page_parent($basic_options_container)
			->add_tab(__('Frontpage / Halaman Depan'), array(
				Field::make('html', 'crb_menu_depan_note')
					->set_html('<p style="font-weight: bold;">Tampilan Beranda dapat diaktifkan melalui shortcode <code>[menu_depan]</code>.</p>'),
					
				Field::make('image', 'crb_icon_pohon_kinerja', __('Ikon Pohon Kinerja'))
					->set_value_type('url')
					->set_help_text('Upload ikon untuk Pohon Kinerja.'),

				Field::make('image', 'crb_icon_cascading', __('Ikon Cascading'))
					->set_value_type('url')
					->set_help_text('Upload ikon untuk Cascading.'),

				Field::make('text', 'crb_icon_size', __('Ukuran Ikon (px)'))
					->set_attribute('type', 'number')
					->set_default_value(150)
					->set_help_text('Tentukan ukuran ikon dalam pixel. Contoh: 150 (default). Pastikan nilai yang dimasukkan adalah angka.'),
			))
			->add_tab(__('Menu Profile User'), array(
				Field::make('html', 'crb_menu_user_note')
					->set_html('<p style="font-weight: bold;">Tampilan <i>Menu User Profile</i> dapat diaktifkan melalui shortcode <code>[menu_eval_sakip]</code>.</p>'),
					
					Field::make('image', 'crb_bg_menu_user', __('Background Menu (Latar Belakang)'))
					->set_value_type('url')
					->set_help_text('Upload background untuk menu user.'),
			));


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
			->set_icon('dashicons-rest-api')
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

		$monev_ren_aksi_menu = Container::make('theme_options', __('Rencana Hasil Kerja'))
			->set_page_menu_position(3.5)
			->set_icon('dashicons-analytics')
			->add_fields(array(
				Field::make('html', 'crb_monev_pokin_hide_sidebar')
					->set_html('
		        		<style>
		        			.postbox-container { display: none; }
		        			#poststuff #post-body.columns-2 { margin: 0 !important; }
		        		</style>
		        	')
			))
			->add_fields($this->get_ajax_field(array('type' => 'monev_rencana_aksi_pemda')));

		Container::make('theme_options', __('Rencana Hasil Kerja Pemerintah Daerah'))
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

		Container::make('theme_options', __('Rencana Hasil Kerja Perangkat Daerah'))
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

		$api_key_wpspd = get_option('_crb_apikey_wpsipd');
		$url_server_wpspd = get_option('_crb_url_server_sakip');

		Container::make('theme_options', __('Rencana Hasil Kerja Setting'))
			->set_page_parent($monev_ren_aksi_menu)
			->add_fields(array(
				Field::make('html', 'crb_esakip_halaman_terkait')
					->set_html('
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label>URL Server WP-SIPD</label>
							</th>
							<td>
								<input type="text" style="width: 30em;" value="' . $url_server_wpspd . '" disabled>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label>API KEY WP-SIPD</label>
							</th>
							<td>
								<input type="text" style="width: 30em;" value="' . $api_key_wpspd . '" disabled>
								<p>Setting Url Server dan Api Key ada di menu pengaturan perangkat daerah</p>
							</td>
						</tr>
					</tbody>
				</table>')
			))
			->add_fields($this->get_ajax_field(array('type' => 'monev_rencana_aksi_setting')));

		$input_iku_menu = Container::make('theme_options', __('Input IKU'))
			->set_page_menu_position(3.6)
			->set_icon('dashicons-edit-page')
			->add_fields(array(
				Field::make('html', 'crb_input_iku_pd_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row">
									<label>URL Server WP-SIPD</label>
								</th>
								<td>
									<input type="text" style="width: 30em;" value="' . $url_server_wpspd . '" disabled>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label>API KEY WP-SIPD</label>
								</th>
								<td>
									<input type="text" style="width: 30em;" value="' . $api_key_wpspd . '" disabled>
									<p>Setting Url Server dan Api Key ada di menu pengaturan perangkat daerah</p>
								</td>
							</tr>
						</tbody>
					</table>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'input_iku_opd')));

		Container::make('theme_options', __('Input IKU Pemerintah Daerah'))
			->set_page_parent($input_iku_menu)
			->add_fields(array(
				Field::make('html', 'crb_input_iku_pemda_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'input_iku_pemda')));

		Container::make('theme_options', __('Input IKU Perangkat Daerah'))
			->set_page_parent($input_iku_menu)
			->add_fields(array(
				Field::make('html', 'crb_input_iku_pd_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row">
									<label>URL Server WP-SIPD</label>
								</th>
								<td>
									<input type="text" style="width: 30em;" value="' . $url_server_wpspd . '" disabled>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label>API KEY WP-SIPD</label>
								</th>
								<td>
									<input type="text" style="width: 30em;" value="' . $api_key_wpspd . '" disabled>
									<p>Setting Url Server dan Api Key ada di menu pengaturan perangkat daerah</p>
								</td>
							</tr>
						</tbody>
					</table>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'input_iku_opd')));

		Container::make('theme_options', __('Input RPJPD'))
			->set_page_menu_position(3.7)
			->set_icon('dashicons-welcome-write-blog')
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
			->set_page_menu_position(3.8)
			->set_icon('dashicons-welcome-write-blog')
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

		$laporan_pk_menu = Container::make('theme_options', __('Laporan PK'))
			->set_page_menu_position(3.9)
			->set_icon('dashicons-media-default')
			->add_fields(array(
				Field::make('html', 'crb_laporan_pk_pd_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
					<h3>Halaman Laporan PK Perangkat Daerah</h3>
					<table class="form-table">
						<tbody>
						</tbody>
					</table>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'laporan_pk_opd')));

		Container::make('theme_options', __('Laporan PK Pemerintah Daerah'))
			->set_page_parent($laporan_pk_menu)
			->add_fields(array(
				Field::make('html', 'crb_laporan_pk_pemda_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'laporan_pk_pemda')));

		Container::make('theme_options', __('Laporan PK Perangkat Daerah'))
			->set_page_parent($laporan_pk_menu)
			->add_fields(array(
				Field::make('html', 'crb_laporan_pk_pd_hide_sidebar')
					->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
					<h3>Halaman Laporan PK Perangkat Daerah</h3>
					<table class="form-table">
						<tbody>
						</tbody>
					</table>
				')
			))
			->add_fields($this->get_ajax_field(array('type' => 'laporan_pk_opd')));

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
				'),
			Field::make('html', 'crb_menu_settings_hide_sidebar')
				->set_html('
					<style>
						.postbox-container { display: none; }
						#poststuff #post-body.columns-2 { margin: 0 !important; }
					</style>
				')
		);
		return $label;
	}

	public function generate_jadwal()
	{
		global $wpdb;
		$get_tahun = $wpdb->get_results('select tahun_anggaran from esakip_data_unit group by tahun_anggaran order by tahun_anggaran DESC', ARRAY_A);
		$list_data = '';

		// jadwal rpjpd
		$jadwal_rpjpd = $this->functions->generatePage(array(
			'nama_page' => 'Halaman Jadwal RPJPD',
			'content' => '[jadwal_rpjpd_sakip]',
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
}
