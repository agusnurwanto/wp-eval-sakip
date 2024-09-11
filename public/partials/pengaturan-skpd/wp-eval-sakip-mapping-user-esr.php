<?php 
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $wpdb;
$tahun_anggaran= get_option('_crb_tahun_wpsipd');
$api_key = get_option('_crb_apikey_esakip');

$users_esr = $wpdb->get_results($wpdb->prepare("
	SELECT 
		user_id,
		usr,
		unit_kerja
	FROM esakip_data_user_esr
	WHERE role_id=%d
	ORDER BY id
", 22), ARRAY_A);

$selectUserEsr='<select class="form-control select2" name="user_esr"><option value="">Pilih User ESR</option>';
foreach ($users_esr as $key => $user) {
	$selectUserEsr.='<option value="'.$user['user_id'].'">'.$user['unit_kerja'].'</option>';
}
$selectUserEsr.='</select>';

$unit = $wpdb->get_results("
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
foreach ($unit as $kk => $vv) {
	$nama_skpd_sakip = get_option('_nama_skpd_sakip_'.$vv['id_skpd']);
	$html .= '
		<tr>
			<td>'.$vv['kode_skpd'].'</td>
			<td>'.$vv['nama_skpd'].'</td>
			<td class="text-center">'.$vv['namakepala'].'<br>'.$vv['nipkepala'].'</td>
			<td>'.$selectUserEsr.'</td>
			<td class="text-center"><button class="btn btn-primary" onclick="proses_mapping_user_esr(\''.$vv['id_skpd'].'\');">Proses</button></td>
		</tr>
	';
}
?>
<div id="wrap-table" style="padding: 10px">
	<h1 class="text-center">Mapping User ESR Menpanrb</h1>
	<div style="margin-bottom: 25px;">
        <button class="btn btn-success" onclick="sync_user_from_esr();"><i class="dashicons dashicons-arrow-down-alt"></i> Tarik Data User ESR</button>
    </div>
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
	function proses_mapping_user_esr(id_skpd) {
		var nama_skpd_sakip = jQuery('#_nama_skpd_sakip_'+id_skpd).val();
		
	    jQuery('#wrap-loading').show();
		jQuery.ajax({
	        method: 'post',
	        url: '<?php echo admin_url('admin-ajax.php'); ?>',
	        dataType: 'json',
	        data: {
	            'action': 'mapping_user_esr',
	            'api_key':'<?php echo $api_key; ?>',
	     	},
	        success: function(res) {
	            alert(res.message);
	            if (res.status == 'success') {
	                jQuery('#wrap-table').modal('hide');
	                jQuery('#wrap-loading').hide();
	            } 
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