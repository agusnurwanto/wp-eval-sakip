<?php 
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $wpdb;
$tahun_anggaran= get_option('_crb_tahun_wpsipd');
$api_key = get_option('_crb_apikey_esakip');

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
			<td><input type="text" value="'.$nama_skpd_sakip.'" id="_nama_skpd_sakip_'.$vv['id_skpd'].'" class="form-control"></td>
			<td class="text-center"><button class="btn btn-primary" onclick="proses_mapping_skpd(\''.$vv['id_skpd'].'\');">Proses</button></td>
		</tr>
	';
}
?>
<div id="wrap-table">
	<h1 class="text-center">Mapping Perangkat Daerah Tahun <?php echo $tahun_anggaran; ?></h1>
	<table>
		<thead>
			<tr>
				<th class="text-center">Kode Perangkat Daerah SIPD</th>
				<th class="text-center" style="width: 500px;">Nama Perangkat Daerah SIPD</th>
				<th class="text-center" style="width: 500px;">Nama dan NIP</th>
				<th class="text-center" style="width: 500px;">Nama Perangkat Daerah SAKIP</th>
				<th class="text-center">Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $html; ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
function proses_mapping_skpd(id_skpd) {
	var nama_skpd_sakip = jQuery('#_nama_skpd_sakip_'+id_skpd).val();
	
    jQuery('#wrap-loading').show();
	jQuery.ajax({
        method: 'post',
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        dataType: 'json',
        data: {
            'action': 'mapping_skpd',
            'api_key':'<?php echo $api_key; ?>',
            'id_skpd' : id_skpd,
            'nama_skpd_sakip' : nama_skpd_sakip
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
</script>