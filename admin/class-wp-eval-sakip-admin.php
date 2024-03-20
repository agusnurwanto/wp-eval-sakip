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

	public function get_ajax_field($options = array('type' => 'dokumen'))
	{
		$ret = array();
		$hide_sidebar = Field::make('html', 'crb_hide_sidebar')
			->set_html('
        		<div id="load_ajax_carbon" data-type="' . $options['type'] . '"></div>
        	');
		$ret[] = $hide_sidebar;
		return $ret;
	}

	public function load_ajax_carbon()
	{
		global $wpdb;
		$ret = array(
			'status'	=> 'success',
			'message'	=> ''
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
				if ($_POST['type'] == 'keu_pemdes'){
					$url_add_new_ssh = $this->generatePage('Data Usulan Standar Satuan Harga (SSH) | ' . $v['tahun_anggaran'], $v['tahun_anggaran'], '[data_ssh_usulan tahun_anggaran="' . $v['tahun_anggaran'] . '"]');
					$body_all .= '<div style="padding:.75rem 0 0 .75rem;"><a style="font-weight: bold;" target="_blank" href="' . $url_add_new_ssh . '">Halaman Data Usulan SSH ' . $v['tahun_anggaran'] . '</a></div>';
				}
			}
			die(json_encode($ret));
		}
	}

	public function crb_attach_esakip_options()
	{
		global $wpdb;

		$basic_options_container = Container::make('theme_options', __('E-SAKIP Options'))
			->set_page_menu_position(3)
			->add_fields(array(
				Field::make('html', 'crb_esakip_halaman_terkait')
					->set_html('
					<h5>HALAMAN TERKAIT</h5>
	            	<ol>
	            	</ol>'),
				Field::make('text', '_crb_apikey_esakip', 'API KEY')
					->set_default_value($this->functions->generateRandomString())
					->set_help_text('Wajib diisi. API KEY digunakan untuk integrasi data.'),
				Field::make('html', 'crb_sql_migrate')
					->set_html('<a onclick="sql_migrate_esakip(); return false;" href="#" class="button button-primary button-large">SQL Migrate</a>')
					->set_help_text('Tombol untuk memperbaiki struktur database E-SAKIP.'),
				Field::make('html', 'crb_generate_user_sipd_merah')
					->set_html('<a id="generate_user_sipd_merah" onclick="return false;" href="#" class="button button-primary button-large">Generate User SIPD Merah By DB Lokal</a>')
					->set_help_text('Data user active yang ada di table data_dewan akan digenerate menjadi user wordpress.'),
			));
		Container::make('theme_options', __('Desain LKE SAKIP'))
			->set_page_parent($basic_options_container)
			->add_fields($this->get_ajax_field(array('type' => 'desain_lke_sakip')));
		Container::make('theme_options', __('Jadwal Evaluasi'))
			->set_page_parent($basic_options_container)
			->add_fields($this->get_ajax_field(array('type' => 'jadwal_evaluasi')));
		Container::make('theme_options', __('Admin Website'))
			->set_page_parent($basic_options_container)
			->add_fields($this->get_ajax_field(array('type' => 'admin_website')));

		$pengisian_lke_menu = Container::make('theme_options', __('Pengisian LKE SAKIP'))
			->set_page_menu_position(3.1)
			->set_icon( 'dashicons-edit-page' );

		Container::make('theme_options', __('SAKIP Perangkat Daerah'))
			->set_page_parent($pengisian_lke_menu)
			->add_fields($this->get_ajax_field(array('type' => 'sakip_perangkat_daerah')));
		Container::make('theme_options', __('SAKIP Pemda'))
			->set_page_parent($pengisian_lke_menu)
			->add_fields($this->get_ajax_field(array('type' => 'sakip_pemda')));

		$dokumen_menu = Container::make('theme_options', __('Dokumen'))
			->set_page_menu_position(3.2)
			->set_icon( 'dashicons-media-default' );

		Container::make('theme_options', __('RENSTRA'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->get_ajax_field(array('type' => 'renstra')));
		Container::make('theme_options', __('RENJA/RKT'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->get_ajax_field(array('type' => 'renja')));
		Container::make('theme_options', __('Perjanjian Kinerja'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->get_ajax_field(array('type' => 'perjanjian_kinerja')));
		Container::make('theme_options', __('Rencana Aksi'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->get_ajax_field(array('type' => 'rencana_aksi')));
		Container::make('theme_options', __('IKU'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->get_ajax_field(array('type' => 'iku')));
		Container::make('theme_options', __('SKP'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->get_ajax_field(array('type' => 'skp')));
		Container::make('theme_options', __('Pengukuran Kinerja'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->get_ajax_field(array('type' => 'pengukuran_kinerja')));
		Container::make('theme_options', __('Pengukuran Rencana Aksi'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->get_ajax_field(array('type' => 'pengukuran_rencana_aksi')));
		Container::make('theme_options', __('Laporan Kinerja'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->get_ajax_field(array('type' => 'laporan_kinerja')));
		Container::make('theme_options', __('Evaluasi Internal'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->get_ajax_field(array('type' => 'evaluasi_internal')));
		Container::make('theme_options', __('Dokumen Lainnya'))
			->set_page_parent($dokumen_menu)
			->add_fields($this->get_ajax_field(array('type' => 'dokumen_lainnya')));

		$dokumen_kab_menu = Container::make('theme_options', __('Dokumen Pemda'))
			->set_page_menu_position(3.3)
			->set_icon( 'dashicons-bank' );

		Container::make('theme_options', __('RPJMD'))
			->set_page_parent($dokumen_kab_menu)
			->add_fields($this->get_ajax_field(array('type' => 'rpjmd')));
		Container::make('theme_options', __('RKPD'))
			->set_page_parent($dokumen_kab_menu)
			->add_fields($this->get_ajax_field(array('type' => 'rkpd')));
		Container::make('theme_options', __('LKJIP/LPPD'))
			->set_page_parent($dokumen_kab_menu)
			->add_fields($this->get_ajax_field(array('type' => 'lkjip')));
		Container::make('theme_options', __('Dokumen Lainnya'))
			->set_page_parent($dokumen_kab_menu)
			->add_fields($this->get_ajax_field(array('type' => 'dokumen_kab_lainnya')));

		$konsultasi_menu = Container::make('theme_options', __('Konsultasi'))
			->set_page_menu_position(3.4)
			->set_icon( 'dashicons-testimonial' )
			->add_fields($this->get_ajax_field(array('type' => 'konsultasi')));
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
						FROM data_unit 
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
}
