<?php 
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $wpdb;
$tahun_anggaran= get_option('_crb_tahun_wpsipd');
$api_key = get_option('_crb_apikey_esakip');

$users_esr_pemda = $wpdb->get_results($wpdb->prepare("
	SELECT 
		user_id,
		usr,
		unit_kerja
	FROM esakip_data_user_esr
	WHERE role_id=%d
	ORDER BY unit_kerja ASC
", 21), ARRAY_A);

$pemda = str_replace(" ","_",get_option('_crb_nama_pemda'));
$selectUserEsrPemda='<select class="form-control" name="user_esr"><option value="">Pilih User ESR</option>';
foreach ($users_esr_pemda as $key => $user) {
	$selected='';
	if(get_option('_user_esr_' . $pemda)==$user['user_id']){
		$selected='selected';
	}
	$selectUserEsrPemda.='<option value="'.$user['user_id'].'" '.$selected.'> '.$user['usr'].' ~ '.$user['unit_kerja'].'</option>';
}
$selectUserEsrPemda.='</select>';

$users_esr = $wpdb->get_results($wpdb->prepare("
	SELECT 
		user_id,
		usr,
		unit_kerja
	FROM esakip_data_user_esr
	WHERE role_id=%d
	ORDER BY unit_kerja ASC
", 22), ARRAY_A);  


$units = $wpdb->get_results("
	SELECT 
		nama_skpd, 
		id_skpd, 
		kode_skpd, 
		namakepala , 
		nipkepala 
	from esakip_data_unit 
	where active=1
		and is_skpd=1 
		and tahun_anggaran=$tahun_anggaran
	group by id_skpd
	order by kode_skpd ASC
", ARRAY_A);

$html = '';
$selectUserEsr = '';
foreach ($units as $unit) {
	$selectUserEsr='<select class="form-control" name="user_esr"><option value="">Pilih User ESR</option>';
	foreach ($users_esr as $key => $user) {
		$selected='';
		if(get_option('_user_esr_' . $unit['id_skpd'])==$user['user_id']){
			$selected='selected';
		}
		$selectUserEsr.='<option value="'.$user['user_id'].'" '.$selected.'> '.$user['usr'].' ~ '.$user['unit_kerja'].'</option>';
	}
	$selectUserEsr.='</select>';

	$html .= '
		<tr id="row_'.$unit['id_skpd'].'">
			<td>'.$unit['kode_skpd'].'</td>
			<td>'.$unit['nama_skpd'].'</td>
			<td class="text-center">'.$unit['namakepala'].'<br>'.$unit['nipkepala'].'</td>
			<td>'.$selectUserEsr.'</td>
			<td class="text-center"><button class="btn btn-primary" onclick="proses_mapping_user_esr(\''.$unit['id_skpd'].'\', 22);">Proses</button></td>
		</tr>
	';
}
?>
<div id="wrap-table" style="padding: 10px">
	<h1 class="text-center">Mapping User ESR MENPANRB</h1>
	<div style="margin-bottom: 25px;">
        <button class="btn btn-success" onclick="sync_user_from_esr();"><i class="dashicons dashicons-arrow-down-alt"></i> Tarik Data User ESR</button>
    </div>
    <table>
		<thead>
			<tr>
				<th class="text-center" style="width: 500px;">Nama Pemerintah Daerah</th>
				<th class="text-center" style="width: 500px;">User ESR</th>
				<th class="text-center">Aksi</th>
			</tr>
		</thead>
		<tbody>
			<tr id="row_<?php echo $pemda; ?>">
				<td><?php echo get_option('_crb_nama_pemda'); ?></td>
				<td><?php echo $selectUserEsrPemda;?></td>
				<td class="text-center"><button class="btn btn-primary" onclick="proses_mapping_user_esr('<?php echo $pemda; ?>', 21)">Proses</button></td>
			</tr>
		</tbody>
	</table>
	<table>
		<thead>
			<tr>
				<th class="text-center">Kode Perangkat Daerah SIPD</th>
				<th class="text-center" style="width: 500px;">Nama Perangkat Daerah SIPD</th>
				<th class="text-center" style="width: 500px;">Nama dan NIP</th>
				<th class="text-center" style="width: 500px;">User ESR</th>
				<th class="text-center">Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $html; ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	function proses_mapping_user_esr(id_skpd, type_skpd) {
		var user_esr = jQuery('#row_'+id_skpd+' select[name=user_esr]').val();

		jQuery('#wrap-loading').show();
		jQuery.ajax({
	        method: 'post',
	        url: '<?php echo admin_url('admin-ajax.php'); ?>',
	        dataType: 'json',
	        data: {
	            'action': 'mapping_user_esr',
	            'api_key':'<?php echo $api_key; ?>',
	            'id_skpd':id_skpd,
	            'type_skpd':type_skpd,
	            'user_esr':user_esr,
	     	},
	        success: function(res) {
	            jQuery('#wrap-loading').hide();
	            alert(res.message);
	        },
	        error:function(xhr, status, error){
	        	jQuery('#wrap-loading').hide();
	            alert('Terjadi kesalahan saat mapping data!');
	        }
	    });
	}

	function sync_user_from_esr(){
	    jQuery('#wrap-loading').show();
	    jQuery.ajax({
	        url: esakip.url,
	        type: 'POST',
	        data: {
	            action: 'sync_user_from_esr',
	            api_key: esakip.api_key,
	        },
	        dataType: 'json',
	        success: function(response) {
	            jQuery('#wrap-loading').hide();
	            alert(response.message);
	        },
	        error: function(xhr, status, error) {
	    	    jQuery('#wrap-loading').hide();
	            alert('Terjadi kesalahan saat ambil data!');
	        }
	    }); 
	}
</script>