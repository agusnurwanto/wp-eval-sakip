<?php

require_once ESAKIP_PLUGIN_PATH . "/public/class-wp-eval-sakip-public-monev-kinerja.php";
class Wp_Eval_Sakip_Pohon_Kinerja extends Wp_Eval_Sakip_Monev_Kinerja
{
    public function penyusunan_pohon_kinerja($atts){
    	if(!empty($_GET) && !empty($_GET['post'])){
			return '';
		}

		require_once ESAKIP_PLUGIN_PATH . 'public/partials/pohon-kinerja/wp-eval-sakip-penyusunan-pohon-kinerja.php';
    }
    public function penyusunan_pohon_kinerja_pd($atts){
    	if(!empty($_GET) && !empty($_GET['post'])){
			return '';
		}

		require_once ESAKIP_PLUGIN_PATH . 'public/partials/pohon-kinerja/wp-eval-sakip-penyusunan-pohon-kinerja-opd.php';
    }
    public function view_pohon_kinerja($atts){
    	if(!empty($_GET) && !empty($_GET['post'])){
			return '';
		}

		require_once ESAKIP_PLUGIN_PATH . 'public/partials/pohon-kinerja/wp-eval-sakip-view-pohon-kinerja.php';
    }

    public function get_data_pokin(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {
					
					$dataPokin = $wpdb->get_results($wpdb->prepare("
						SELECT 
							a.id,
							a.label,
							a.parent,
							a.active,
							b.id AS id_indikator,
							b.label_indikator_kinerja
						FROM esakip_pohon_kinerja a
							LEFT JOIN esakip_pohon_kinerja b 
								ON a.id=b.parent AND a.level=b.level 
						WHERE 
							a.tahun_anggaran=%d AND 
							a.parent=%d AND 
							a.level=%d AND 
							a.active=%d 
						ORDER BY a.id", 
						$_POST['tahun_anggaran'], 
						$_POST['parent'], 
						$_POST['level'], 
						1
					), ARRAY_A);

					$data = [];
					foreach ($dataPokin as $key => $pokin) {
						if(empty($data[$pokin['id']])){
							$data[$pokin['id']] = [
								'id' => $pokin['id'],
								'label' => $pokin['label'],
								'parent' => $pokin['parent'],
								'indikator' => []
							];
						}

						if(!empty($pokin['id_indikator'])){
							if(empty($data[$pokin['id']]['indikator'][$pokin['id_indikator']])){
								$data[$pokin['id']]['indikator'][$pokin['id_indikator']] = [
									'id' => $pokin['id_indikator'],
									'label' => $pokin['label_indikator_kinerja']
								];
							}
						}
					}

					echo json_encode([
		    			'status' => true,
		    			'data' => array_values($data)
		    		]);exit();
				}else{
					throw new Exception("API tidak ditemukan!", 1);
				}
			}else{
				throw new Exception("Format tidak sesuai!", 1);
			}
		} catch (Exception $e) {
    		echo json_encode([
    			'status' => false,
    			'message' => $e->getMessage()
    		]);exit();
    	}
    }

    public function create_pokin(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {

					$input = json_decode(stripslashes($_POST['data']), true);

					$id = $wpdb->get_var($wpdb->prepare("SELECT id FROM esakip_pohon_kinerja WHERE label=%s AND parent=%d AND level=%d AND active=%d ORDER BY id", trim($input['label']), $input['parent'], $input['level'], 1),  ARRAY_A);

					if(!empty($id)){
						throw new Exception("Data sudah ada!", 1);
					}

					$data = $wpdb->insert('esakip_pohon_kinerja', [
						'label' => trim($input['label']),
						'parent' => $input['parent'],
						'level' => $input['level'],
						'tahun_anggaran' => $input['tahun_anggaran'],
						'active' => 1
					]);

					echo json_encode([
		    			'status' => true,
		    			'message' => 'Sukses simpan pohon kinerja!'
		    		]);exit();
				}else{
					throw new Exception("API tidak ditemukan!", 1);
				}
			}else{
				throw new Exception("Format tidak sesuai!", 1);
			}
		} catch (Exception $e) {
    		echo json_encode([
    			'status' => false,
    			'message' => $e->getMessage()
    		]);exit();
    	}
    }

    public function edit_pokin(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {

					$data = $wpdb->get_row($wpdb->prepare("SELECT id, parent, level, label FROM esakip_pohon_kinerja WHERE id=%d AND active=%d", $_POST['id'], 1),  ARRAY_A);

					if(empty($data)){
						throw new Exception("Data tidak ditemukan!", 1);
					}

					echo json_encode([
		    			'status' => true,
		    			'data' => $data
		    		]);exit();
				}else{
					throw new Exception("API tidak ditemukan!", 1);
				}
			}else{
				throw new Exception("Format tidak sesuai!", 1);
			}
		} catch (Exception $e) {
    		echo json_encode([
    			'status' => false,
    			'message' => $e->getMessage()
    		]);exit();
    	}	
    }

    public function update_pokin(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {

					$input = json_decode(stripslashes($_POST['data']), true);

					$id = $wpdb->get_var($wpdb->prepare("SELECT id FROM esakip_pohon_kinerja WHERE label=%s AND id!=%d AND parent=%d AND level=%d AND active=%d", trim($input['label']), $input['id'], $input['parent'], $input['level'], 1),  ARRAY_A);

					if(!empty($id)){
						throw new Exception("Data sudah ada!", 1);
					}

					$data = $wpdb->update('esakip_pohon_kinerja', [
						'label' => trim($input['label'])
					], [
						'id' => $input['id']
					]);

					$child = $wpdb->update('esakip_pohon_kinerja', [
						'label' => trim($input['label'])
					], [
						'parent' => $input['id']
					]);

					echo json_encode([
		    			'status' => true,
		    			'message' => 'Sukses ubah pohon kinerja!'
		    		]);exit();
				}else{
					throw new Exception("API tidak ditemukan!", 1);
				}
			}else{
				throw new Exception("Format tidak sesuai!", 1);
			}
		} catch (Exception $e) {
    		echo json_encode([
    			'status' => false,
    			'message' => $e->getMessage()
    		]);exit();
    	}
    }

    public function delete_pokin(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {

					$indikator = $wpdb->get_row($wpdb->prepare("SELECT id FROM esakip_pohon_kinerja WHERE parent=%d AND label_indikator_kinerja IS NOT NULL AND level=%d AND active=%d", $_POST['id'], $_POST['level'], 1),  ARRAY_A);

					if(!empty($indikator)){
						throw new Exception("Indikator harus dihapus dulu!", 1);
					}

					$child = $wpdb->get_row($wpdb->prepare("SELECT id FROM esakip_pohon_kinerja WHERE parent=%d AND level=%d AND active=%d", $_POST['id'], (intval($_POST['level'])+1), 1),  ARRAY_A);

					if(!empty($child)){
						throw new Exception("Pohon kinerja level ".(intval($_POST['level'])+1)." harus dihapus dulu!", 1);
					}

					$data = $wpdb->delete('esakip_pohon_kinerja', [
						'id' => $_POST['id']
					]);

					echo json_encode([
		    			'status' => true,
		    			'message' => 'Sukses hapus pohon kinerja!'
		    		]);exit();
				}else{
					throw new Exception("API tidak ditemukan!", 1);
				}
			}else{
				throw new Exception("Format tidak sesuai!", 1);
			}
		} catch (Exception $e) {
    		echo json_encode([
    			'status' => false,
    			'message' => $e->getMessage()
    		]);exit();
    	}
    }

    public function create_indikator_pokin(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {

					$input = json_decode(stripslashes($_POST['data']), true);

					$id = $wpdb->get_var($wpdb->prepare("SELECT id FROM esakip_pohon_kinerja WHERE label_indikator_kinerja=%s AND parent=%d AND level=%d AND active=%d", trim($input['indikator_label']), $input['parent'], $input['level'], 1),  ARRAY_A);

					if(!empty($id)){
						throw new Exception("Data sudah ada!", 1);
					}

					$data = $wpdb->insert('esakip_pohon_kinerja', [
						'label' => trim($input['label']),
						'label_indikator_kinerja' => trim($input['indikator_label']),
						'parent' => $input['parent'],
						'level' => $input['level'],
						'tahun_anggaran' => $input['tahun_anggaran'],
						'active' => 1
					]);

					echo json_encode([
		    			'status' => true,
		    			'message' => 'Sukses simpan indikator!'
		    		]);exit();
				}else{
					throw new Exception("API tidak ditemukan!", 1);
				}
			}else{
				throw new Exception("Format tidak sesuai!", 1);
			}
		} catch (Exception $e) {
    		echo json_encode([
    			'status' => false,
    			'message' => $e->getMessage()
    		]);exit();
    	}	
    }

    public function edit_indikator_pokin(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {

					$data = $wpdb->get_row($wpdb->prepare("
						SELECT 
							a.id, 
							a.label, 
							a.parent, 
							a.label_indikator_kinerja, 
							a.level,
							b.parent AS parent_all 
						FROM 
							esakip_pohon_kinerja a
						LEFT JOIN esakip_pohon_kinerja b ON b.id=a.parent 
						WHERE 
							a.id=%d AND 
							a.active=%d", 
						$_POST['id'], 
						1
					),  ARRAY_A);

					if(empty($data)){
						throw new Exception("Data tidak ditemukan!", 1);
					}

					echo json_encode([
		    			'status' => true,
		    			'data' => $data
		    		]);exit();
				}else{
					throw new Exception("API tidak ditemukan!", 1);
				}
			}else{
				throw new Exception("Format tidak sesuai!", 1);
			}
		} catch (Exception $e) {
    		echo json_encode([
    			'status' => false,
    			'message' => $e->getMessage()
    		]);exit();
    	}
    }

    public function update_indikator_pokin(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {

					$input = json_decode(stripslashes($_POST['data']), true);

					$id = $wpdb->get_var($wpdb->prepare("SELECT id FROM esakip_pohon_kinerja WHERE id!=%d AND parent=%d AND level=%d AND active=%d", $input['id'], $input['parent'], $input['level'], 1),  ARRAY_A);

					if(!empty($id)){
						throw new Exception("Data sudah ada!", 1);
					}

					$data = $wpdb->update('esakip_pohon_kinerja', [
						'label_indikator_kinerja' => trim($input['indikator_label']),
					], [
						'id' => $input['id'],
						'parent' => $input['parent'],
						'level' => $input['level'],
					]);

					echo json_encode([
		    			'status' => true,
		    			'message' => 'Sukses ubah indikator!'
		    		]);exit();
				}else{
					throw new Exception("API tidak ditemukan!", 1);
				}
			}else{
				throw new Exception("Format tidak sesuai!", 1);
			}
		} catch (Exception $e) {
    		echo json_encode([
    			'status' => false,
    			'message' => $e->getMessage()
    		]);exit();
    	}
    }

    public function delete_indikator_pokin(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {

					$data = $wpdb->delete('esakip_pohon_kinerja', [
						'id' => $_POST['id']
					]);

					echo json_encode([
		    			'status' => true,
		    			'message' => 'Sukses hapus indikator!'
		    		]);exit();
				}else{
					throw new Exception("API tidak ditemukan!", 1);
				}
			}else{
				throw new Exception("Format tidak sesuai!", 1);
			}
		} catch (Exception $e) {
    		echo json_encode([
    			'status' => false,
    			'message' => $e->getMessage()
    		]);exit();
    	}
    }
}