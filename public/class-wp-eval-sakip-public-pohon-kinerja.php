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

	public function cascading_pemda($atts)
	{
		// untuk disable render shortcode di halaman edit page/post
		if (!empty($_GET) && !empty($_GET['POST'])) {
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/pohon-kinerja/wp-eval-sakip-cascading-pemda.php';
	}

    public function get_data_pokin(){
    	global $wpdb;
    	try {
    		if (!empty($_POST)) {
				if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option( ESAKIP_APIKEY )) {

					switch ($_POST['level']) {
						case '2':
							$label_parent = ',
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja 
								WHERE id=a.parent
							) label_parent_1';
							break;

						case '3':
							$label_parent = ',
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja 
									WHERE id=a.parent
								)
							) label_parent_1,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja 
								WHERE id=a.parent
							) label_parent_2';
							break;

						case '4':
							$label_parent = ',
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja 
									WHERE id=(
										SELECT 
											parent 
										FROM esakip_pohon_kinerja 
										WHERE id=a.parent
									)
								)
							) label_parent_1,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja 
								WHERE id=(
									SELECT 
										parent 
									FROM esakip_pohon_kinerja 
									WHERE id=a.parent
								)
							) label_parent_2,
							(
								SELECT 
									label 
								FROM esakip_pohon_kinerja 
								WHERE id=a.parent
							) label_parent_3';
							break;
						
						default:
							$label_parent = '';
							break;
					}
					
					$dataPokin = $wpdb->get_results($wpdb->prepare("
						SELECT 
							a.id,
							a.label,
							a.parent,
							a.active,
							b.id AS id_indikator,
							b.label_indikator_kinerja
							".$label_parent."
						FROM esakip_pohon_kinerja a
							LEFT JOIN esakip_pohon_kinerja b 
								ON a.id=b.parent AND a.level=b.level 
						WHERE 
							a.id_jadwal=%d AND 
							a.parent=%d AND 
							a.level=%d AND 
							a.active=%d 
						ORDER BY a.id", 
						$_POST['id_jadwal'], 
						$_POST['parent'], 
						$_POST['level'], 
						1
					), ARRAY_A);

					$data = [
						'data' => [],
						'parent' => []
					];
					foreach ($dataPokin as $key => $pokin) {

						if(empty($data['parent'][$pokin['label_parent_1']])){
							$data['parent'][$pokin['label_parent_1']] = $pokin['label_parent_1'];
						}

						if(empty($data['parent'][$pokin['label_parent_2']])){
							$data['parent'][$pokin['label_parent_2']] = $pokin['label_parent_2'];
						}

						if(empty($data['parent'][$pokin['label_parent_3']])){
							$data['parent'][$pokin['label_parent_3']] = $pokin['label_parent_3'];
						}

						if(empty($data['data'][$pokin['id']])){
							$data['data'][$pokin['id']] = [
								'id' => $pokin['id'],
								'label' => $pokin['label'],
								'parent' => $pokin['parent'],
								'label_parent_1' => $pokin['label_parent_1'],
								'indikator' => []
							];
						}

						if(!empty($pokin['id_indikator'])){
							if(empty($data['data'][$pokin['id']]['indikator'][$pokin['id_indikator']])){
								$data['data'][$pokin['id']]['indikator'][$pokin['id_indikator']] = [
									'id' => $pokin['id_indikator'],
									'label' => $pokin['label_indikator_kinerja']
								];
							}
						}
					}

					echo json_encode([
		    			'status' => true,
		    			'data' => array_values($data['data']),
		    			'parent' => array_values($data['parent'])
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

					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja 
						WHERE label=%s 
							AND parent=%d 
							AND level=%d 
							AND active=%d 
						ORDER BY id
					", trim($input['label']), $input['parent'], $input['level'], 1),  ARRAY_A);

					if(!empty($id)){
						throw new Exception("Data sudah ada!", 1);
					}

					$data = $wpdb->insert('esakip_pohon_kinerja', [
						'label' => trim($input['label']),
						'parent' => $input['parent'],
						'level' => $input['level'],
						'id_jadwal' => $input['id_jadwal'],
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

					$data = $wpdb->get_row($wpdb->prepare("
						SELECT 
							id, 
							parent, 
							level, 
							label 
						FROM esakip_pohon_kinerja 
						WHERE id=%d 
							AND active=%d
					", $_POST['id'], 1),  ARRAY_A);

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

					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja 
						WHERE label=%s 
							AND id!=%d 
							AND parent=%d 
							AND level=%d 
							AND active=%d
						", trim($input['label']), $input['id'], $input['parent'], $input['level'], 1),  ARRAY_A);

					if(!empty($id)){
						throw new Exception("Data sudah ada!", 1);
					}

					$data = $wpdb->update('esakip_pohon_kinerja', [
						'label' => trim($input['label'])
					], [
						'id' => $input['id']
					]);

					$child = $wpdb->query($wpdb->prepare("
						UPDATE esakip_pohon_kinerja 
						SET label=%s 
						WHERE parent=%d 
							AND label_indikator_kinerja IS NOT NULL
					", trim($input['label']), $input['id']));

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

					$indikator = $wpdb->get_row($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja 
						WHERE parent=%d 
							AND label_indikator_kinerja IS NOT NULL 
							AND level=%d 
							AND active=%d
					", $_POST['id'], $_POST['level'], 1),  ARRAY_A);

					if(!empty($indikator)){
						throw new Exception("Indikator harus dihapus dulu!", 1);
					}

					$child = $wpdb->get_row($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja 
						WHERE parent=%d 
							AND level=%d 
							AND active=%d
					", $_POST['id'], (intval($_POST['level'])+1), 1),  ARRAY_A);

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

					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja 
						WHERE label_indikator_kinerja=%s 
							AND parent=%d 
							AND level=%d 
							AND active=%d
					", trim($input['indikator_label']), $input['parent'], $input['level'], 1),  ARRAY_A);

					if(!empty($id)){
						throw new Exception("Data sudah ada!", 1);
					}

					$data = $wpdb->insert('esakip_pohon_kinerja', [
						// 'label' => trim($input['label']),
						'label_indikator_kinerja' => trim($input['indikator_label']),
						'parent' => $input['parent'],
						'level' => $input['level'],
						'id_jadwal' => $input['id_jadwal'],
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

					$id = $wpdb->get_var($wpdb->prepare("
						SELECT 
							id 
						FROM esakip_pohon_kinerja 
						WHERE id!=%d 
							AND parent=%d 
							AND level=%d 
							AND active=%d 
							AND label_indikator_kinerja=%s
					", $input['id'], $input['parent'], $input['level'], 1, trim($input['indikator_label'])),  ARRAY_A);

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

	public function get_table_cascading()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {

				$get_tujuan = $wpdb->get_results("
                    SELECT 
                    	* 
                    FROM esakip_rpd_tujuan
                    WHERE id_unik_indikator IS NULL
                     	AND active = 1
                ", ARRAY_A);

				if (!empty($get_tujuan)) {
					$counter = 1;
					$tbody = '';

					foreach ($get_tujuan as $kk => $vv) {
						$tbody .= "<tr>";
						$tbody .= "<td class='text-center'>" . $counter++ . "</td>";
						$tbody .= "<td>" . $vv['nama_cascading'] . "</td>";
						$tbody .= "<td>" . $vv['tujuan_teks'] . "</td>";

						$btn = '<div class="btn-action-group">';
						$btn .= '<button class="btn btn-sm btn-info" onclick="view_cascading(\'' . $vv['id'] . '\'); return false;" href="#" title="View"><span class="dashicons dashicons-visibility"></span></button>';
						$btn .= '<button class="btn btn-sm btn-warning" onclick="edit_cascading_pemda(\'' . $vv['id'] . '\'); return false;" href="#" title="Edit"><span class="dashicons dashicons-edit"></span></button>';
						$btn .= '</div>';

						$tbody .= "<td class='text-center'>" . $btn . "</td>";
						$tbody .= "</tr>";
					}

					$ret['data'] = $tbody;
				} else {
					$ret['data'] = "<tr><td colspan='8' class='text-center'>Tidak ada data tersedia</td></tr>";
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message'   => 'Format tidak sesuai!'
			);
		}
		die(json_encode($ret));
	}

	public function edit_cascading_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data'  => array()
		);

		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (!empty($_POST['id'])) {
					$data = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								*
							FROM esakip_rpd_tujuan
							WHERE id = %d
						", $_POST['id']),
						ARRAY_A
					);
					$ret['data'] = $data;
				} else {
					$ret = array(
						'status' => 'error',
						'message'   => 'Id Kosong!'
					);
				}
			} else {
				$ret = array(
					'status' => 'error',
					'message'   => 'Api Key tidak sesuai!'
				);
			}
		} else {
			$ret = array(
				'status' => 'error',
				'message'   => 'Format tidak sesuai!'
			);
		}
		die(json_encode($ret));
	}

	public function submit_edit_cascading()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil edit data!',
			'data' => array()
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Id kosong!';
					die(json_encode($ret));
				} else if (empty($_POST['nama_cascading'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'Nama Cascading kosong!';
					die(json_encode($ret));
				} else {
					$nama_cascading = $_POST['nama_cascading'];
					$data = array(
						'nama_cascading' => $nama_cascading,
						'update_at' => current_time('mysql')
					);
					$wpdb->update('esakip_rpd_tujuan', $data, array('id' => $_POST['id']));
				}
			} else {
				$ret['status']  = 'error';
				$ret['message'] = 'Api key tidak ditemukan!';
			}
		} else {
			$ret['status']  = 'error';
			$ret['message'] = 'Format Salah!';
		}

		die(json_encode($ret));
	}

	public function view_cascading_pemda()
	{
		global $wpdb;
		$ret = array(
			'status' => 'success',
			'message' => 'Berhasil get data!',
			'data' => array()
		);
		if (!empty($_POST)) {
			if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option(ESAKIP_APIKEY)) {
				if (empty($_POST['id_jadwal'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'id Jadwal kosong!';
				} else if(empty($_POST['id'])) {
					$ret['status'] = 'error';
					$ret['message'] = 'id kosong!';
				}

				if($ret['status'] != 'error'){
					$id_jadwal = $_POST['id_jadwal'];
					$tujuan = $wpdb->get_row(
						$wpdb->prepare("
							SELECT 
								*
							FROM esakip_rpd_tujuan
							WHERE id = %d
								AND active=1
						", $_POST['id']),
						ARRAY_A
					);
					$indikator_tujuan = $wpdb->get_results(
						$wpdb->prepare("
							SELECT 
								*
							FROM esakip_rpd_tujuan
							WHERE id_unik = %s
								AND active=1
								AND id_unik_indikator IS NOT NULL
						", $tujuan['id_unik']),
						ARRAY_A
					);
					$indikator_tujuan_html = '
					<table>
						<tbody>
							<tr>
					';
					$data = '';
					foreach($indikator_tujuan as $ind){
						$data .= '<td class="text-center"><button class="btn btn-lg btn-warning">'.$ind['indikator_teks'].'</button></td>';
					}
					if(empty($data)){
						$data = '<td class="text-center"><button class="btn btn-lg btn-warning"></button></td>';
					}
					$indikator_tujuan_html .= $data.'
							</tr>
						</tbody>
					</table>
					';
					$sasaran = $wpdb->get_results(
						$wpdb->prepare("
							SELECT 
								*
							FROM esakip_rpd_sasaran
							WHERE kode_tujuan = %s
								AND active=1
								AND id_unik_indikator IS NULL
						", $tujuan['kode_tujuan']),
						ARRAY_A
					);
					$sasaran_html = '
					<table>
						<tbody>
							<tr>
					';
					$data = '';
					foreach($sasaran as $sas){
						$data .= '<td class="text-center"><button class="btn btn-lg btn-warning">'.$sas['sasaran_teks'].'</button></td>';
					}
					if(empty($data)){
						$data = '<td class="text-center"><button class="btn btn-lg btn-warning"></button></td>';
					}
					$sasaran_html .= $data.'
							</tr>
						</tbody>
					</table>
					';
					$indikator_sasaran = $wpdb->get_results(
						$wpdb->prepare("
							SELECT 
								*
							FROM esakip_rpd_sasaran
							WHERE id_unik = %s
								AND active=1
								AND id_unik_indikator IS NOT NULL
						", $sasaran['id_unik']),
						ARRAY_A
					);
					$indikator_sasaran_html = '
					<table>
						<tbody>
							<tr>
					';
					$data = '';
					foreach($indikator_sasaran as $ind){
						$data .= '<td class="text-center"><button class="btn btn-lg btn-warning">'.$ind['indikator_teks'].'</button></td>';
					}
					if(empty($data)){
						$data = '<td class="text-center"><button class="btn btn-lg btn-warning"></button></td>';
					}
					$indikator_sasaran_html .= $data.'
							</tr>
						</tbody>
					</table>
					';
					$html = '
					<h1 class="text-center">'.$tujuan['nama_cascading'].'</h1>
					<table id="tabel-cascading">
						<tbody>
							<tr>
								<td class="text-center" style="width: 200px;"><button class="btn btn-lg btn-info">MISI RPJPD</button></td>
								<td class="text-center"><button class="btn btn-lg btn-warning"></button></td>
							</tr>
							<tr>
								<td class="text-center"><button class="btn btn-lg btn-info">TUJUAN RPD</button></td>
								<td class="text-center"><button class="btn btn-lg btn-warning">'.$tujuan['tujuan_teks'].'</button></td>
							</tr>
							<tr>
								<td class="text-center"><button class="btn btn-lg btn-info">INDIKATOR TUJUAN RPD</button></td>
								<td class="text-center">'.$indikator_tujuan_html.'</td>
							</tr>
							<tr>
								<td class="text-center"><button class="btn btn-lg btn-info">SASARAN RPD</button></td>
								<td class="text-center">'.$sasaran_html.'</td>
							</tr>
							<tr>
								<td class="text-center"><button class="btn btn-lg btn-info">INDIKATOR SASARAN RPD</button></td>
								<td class="text-center">'.$indikator_sasaran_html.'</td>
							</tr>
							<tr>
								<td class="text-center"><button class="btn btn-lg btn-info">URUSAN PENGAMPU</button></td>
								<td class="text-center"><button class="btn btn-lg btn-warning"></button></td>
							</tr>
						</tbody>
					</table>
					';
					$ret['html'] = $html;
				}

			} else {
				$ret['status']  = 'error';
				$ret['message'] = 'Api key tidak ditemukan!';
			}
		} else {
			$ret['status']  = 'error';
			$ret['sql']  = $wpdb->last_query;
			$ret['message'] = 'Format Salah!';
		}

		die(json_encode($ret));
	}
}