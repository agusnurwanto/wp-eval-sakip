<?php

global $wpdb;

$api_key = get_option('_crb_apikey_esakip');
$input = shortcode_atts(array(
	'tahun_anggaran' => ''
), $atts);

if (!empty($_GET) && !empty($_GET['tahun_anggaran'])) {
	$input['tahun_anggaran'] = $wpdb->prepare('%d', $_GET['tahun_anggaran']);
}
$tahun_anggaran = $input['tahun_anggaran'];

$dokumen_esr = $wpdb->get_results($wpdb->prepare("
	SELECT 
		* 
	FROM esakip_data_jenis_dokumen_esr 
	WHERE tahun_anggaran=%d 
		AND active=%d 
		ORDER BY id
", $tahun_anggaran, 1), ARRAY_A);

$dokumen_menu = $wpdb->get_results($wpdb->prepare("
	SELECT 
		a.id,
		a.nama_tabel,
		a.nama_dokumen,
		a.user_role,
		b.jenis_dokumen_esr_id
	FROM esakip_menu_dokumen a 
	LEFT JOIN esakip_data_mapping_jenis_dokumen_esr b ON a.id=b.esakip_menu_dokumen_id 
		AND a.tahun_anggaran=b.tahun_anggaran 
	WHERE a.tahun_anggaran=%d 
		AND a.user_role=%s 
		AND a.active=1
	ORDER BY a.nomor_urut ASC, a.id ASC
", $tahun_anggaran, 'pemerintah_daerah'), ARRAY_A);

$body='';
$i=1;
foreach ($dokumen_menu as $dokumen) {
	$select='<select class="form-control" onchange="mappingJenisDokumenEsr(\''.$dokumen['id'].'\', this, '.$tahun_anggaran.')"><option value="">Pilih Jenis Dokumen ESR</option>';
	foreach ($dokumen_esr as $dok_esr) {
		$selected='';
		if($dokumen['jenis_dokumen_esr_id']==$dok_esr['jenis_dokumen_esr_id']){
			$selected='selected';
		}
		$select.='<option value="'.$dok_esr['jenis_dokumen_esr_id'].'" '.$selected.'>'.$dok_esr['nama'].'</option>';
	}
	$select.='</select>';
	$body.='<tr>
		<td class="text-center">'.$i.'</td>
		<td>'.$dokumen['nama_tabel'].'</td>
		<td>'.$dokumen['nama_dokumen'].'</td>
		<td class="text-center">'.$dokumen['jenis_dokumen_esr_id'].'</td>
		<td>'.$select.'</td>
	</tr>';
	$i++;
}

$dokumen_menu = $wpdb->get_results($wpdb->prepare("
	SELECT 
		a.id,
		a.nama_tabel,
		a.nama_dokumen,
		a.user_role,
		b.jenis_dokumen_esr_id
	FROM esakip_menu_dokumen a 
	LEFT JOIN esakip_data_mapping_jenis_dokumen_esr b ON a.id=b.esakip_menu_dokumen_id 
		AND a.tahun_anggaran=b.tahun_anggaran 
	WHERE a.tahun_anggaran=%d 
		AND a.user_role=%s 
		AND a.active=1
	ORDER BY a.nomor_urut ASC, a.id ASC
", $tahun_anggaran, 'perangkat_daerah'), ARRAY_A);

$body_opd='';
$i=1;
foreach ($dokumen_menu as $dokumen) {
	$select='<select class="form-control" onchange="mappingJenisDokumenEsr(\''.$dokumen['id'].'\', this, '.$tahun_anggaran.')"><option value="">Pilih Jenis Dokumen ESR</option>';
	foreach ($dokumen_esr as $dok_esr) {
		$selected='';
		if($dokumen['jenis_dokumen_esr_id']==$dok_esr['jenis_dokumen_esr_id']){
			$selected='selected';
		}
		$select.='<option value="'.$dok_esr['jenis_dokumen_esr_id'].'" '.$selected.'>'.$dok_esr['nama'].'</option>';
	}
	$select.='</select>';
	$body_opd.='<tr>
		<td class="text-center">'.$i.'</td>
		<td>'.$dokumen['nama_tabel'].'</td>
		<td>'.$dokumen['nama_dokumen'].'</td>
		<td class="text-center">'.$dokumen['jenis_dokumen_esr_id'].'</td>
		<td>'.$select.'</td>
	</tr>';
	$i++;
}
?>

<div id="wrap-table" style="padding: 10px;">
	<h1 class="text-center">Mapping Jenis Dokumen Tahun Anggaran <?php echo $tahun_anggaran ?></h1>
	<div class="aksi-sakip text-center">
		<button class="btn btn-success mb-4" onclick="generateMasterJenisDokumenEsr('<?php echo $tahun_anggaran; ?>')">Generate Master Jenis Dokumen ESR</button>
	</div>
	<h3 class="text-center">Dokumen Pemerintah Daerah</h3>
	<table>
		<thead>
			<tr>
				<th class="text-center" style="width:10px">No.</th>
				<th class="text-center" style="width: 500px;">Tabel Dokumen Lokal</th>
				<th class="text-center" style="width: 500px;">Nama Dokumen Lokal</th>
				<th class="text-center" style="width: 100px;">ID Dokumen ESR</th>
				<th class="text-center" style="width: 500px;">Nama Dokumen ESR</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $body; ?>
		</tbody>
	</table>
	<h3 class="text-center">Dokumen Perangkat Daerah</h3>
	<table>
		<thead>
			<tr>
				<th class="text-center" style="width:10px">No.</th>
				<th class="text-center" style="width: 500px;">Tabel Dokumen Lokal</th>
				<th class="text-center" style="width: 500px;">Nama Dokumen Lokal</th>
				<th class="text-center" style="width: 100px;">ID Dokumen ESR</th>
				<th class="text-center" style="width: 500px;">Nama Dokumen ESR</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $body_opd; ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	function mappingJenisDokumenEsr(esakip_menu_dokumen_id, that, tahun_anggaran){
		jQuery('#wrap-loading').show();
		jQuery.ajax({
	        method: 'post',
	        url: '<?php echo admin_url('admin-ajax.php'); ?>',
	        dataType: 'json',
	        data: {
	            'action': 'mapping_jenis_dokumen_esr',
	            'api_key':'<?php echo $api_key; ?>',
	            'esakip_menu_dokumen_id' : esakip_menu_dokumen_id,
	            'jenis_dokumen_esr_id' : jQuery(that).val(),
	            'tahun_anggaran': tahun_anggaran
	     	},
	        success: function(res) {
	            alert(res.message);
	            jQuery('#wrap-loading').hide();
	        }
	    });
	}

	function generateMasterJenisDokumenEsr(tahun_anggaran){
		jQuery('#wrap-loading').show();
		jQuery.ajax({
	        method: 'post',
	        url: '<?php echo admin_url('admin-ajax.php'); ?>',
	        dataType: 'json',
	        data: {
	            'action': 'generate_master_jenis_dokumen_esr',
	            'api_key':'<?php echo $api_key; ?>',
	            'tahun_anggaran': tahun_anggaran
	     	},
	        success: function(res) {
	            alert(res.message);
	            jQuery('#wrap-loading').hide();
	            location.reload();
	        }
	    });
	}
</script>