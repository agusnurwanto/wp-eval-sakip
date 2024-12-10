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
		and tahun_anggaran=$tahun_anggaran
	group by id_skpd
	order by kode_skpd ASC
", ARRAY_A);
$html = '';
foreach ($unit as $kk => $vv) {
	$html .= '
		<tr>
			<td>'.$vv['kode_skpd'].'</td>
			<td>'.$vv['nama_skpd'].'</td>
			<td class="text-center">'.$vv['namakepala'].'<br>'.$vv['nipkepala'].'</td>
			<td></td>
		</tr>
	';
}
?>
<div id="wrap-table" style="padding: 10px">
	<h1 class="text-center">Mapping Perangkat Daerah SIPD-SIMPEG </br>Tahun <?php echo $tahun_anggaran; ?></h1>
	<div style="margin-bottom: 25px;">
        <button class="btn btn-success" onclick="get_satker_simpeg();"><i class="dashicons dashicons-arrow-down-alt"></i> Tarik Data Satker Simpeg</button>
        <button class="btn btn-success" onclick="get_pegawai_simpeg();"><i class="dashicons dashicons-arrow-down-alt"></i> Tarik Data Pegawai Simpeg</button>
    </div>
	<table>
		<thead>
			<tr>
				<th class="text-center">Kode Perangkat Daerah SIPD</th>
				<th class="text-center" style="width: 500px;">Nama Perangkat Daerah SIPD</th>
				<th class="text-center" style="width: 500px;">Nama dan NIP</th>
				<th class="text-center" style="width: 500px;">Nama Perangkat Daerah SIMPEG</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $html; ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	function get_satker_simpeg(){
	    jQuery('#wrap-loading').show();
	    jQuery.ajax({
	        url: esakip.url,
	        type: 'POST',
	        data: {
	            action: 'get_satker_simpeg',
	            api_key: esakip.api_key,
	            tahun_anggaran:'<?php echo $tahun_anggaran ?>'
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

	function get_pegawai_simpeg(){
		alert('masih dikerjakan');
	}
</script>