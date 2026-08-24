<?php
global $wpdb;

if (!defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'tahun' => '2022',
	'periode' => '1'
), $atts);

//jadwal renstra wpsipd
$api_params = array(
	'action' => 'get_data_jadwal_wpsipd',
	'api_key'	=> get_option('_crb_apikey_wpsipd'),
	'tipe_perencanaan' => 'monev_renstra',
	'id_jadwal' => $input['periode']
);

$response = wp_remote_post(get_option('_crb_url_server_sakip'), array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));

$response = wp_remote_retrieve_body($response);

$data_jadwal_wpsipd = json_decode($response, true);

if (!empty($data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran']) && $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'] > 1) {
	$tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'];
} else {
	$tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_anggaran'] + $data_jadwal_wpsipd['data'][0]['lama_pelaksanaan'];
}

$nama_jadwal = $data_jadwal_wpsipd['data'][0]['nama'] . ' ' . '(' . $data_jadwal_wpsipd['data'][0]['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ')';

?>
<style type="text/css">
	.wrap-table {
		overflow: auto;
		max-height: 100vh;
		width: 100%;
	}

	.btn-action-group {
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.btn-action-group .btn {
		margin: 0 5px;
	}
	.table_dokumen_skpd thead{
		position: sticky;
        top: -6px;
	}.table_dokumen_skpd thead th{
		vertical-align: middle;
	}
	.table_dokumen_skpd tfoot{
        position: sticky;
        bottom: 0;
    }

	.table_dokumen_skpd tfoot th{
		vertical-align: middle;
	}
</style>
<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Pengisian Cascading OPD</br><?php echo $nama_jadwal; ?></h1>
			<div id="action" class="action-section hide-excel"></div>
			<div class="wrap-table">
				<table id="cetak" title="Rekapitulasi Pengisian Cascading OPD" class="table table-bordered table_dokumen_skpd" cellpadding="2" cellspacing="0">
					<thead style="background: #ffc491;">
						<tr>
							<th class="text-center"><input type="checkbox" id="cek_all"></th>
							<th class="text-center">Nama Perangkat Daerah</th>
							<th class="text-center">Jumlah Tujuan</th>
							<th class="text-center">Jumlah Sasaran</th>
							<th class="text-center">Jumlah Program</th>
							<th class="text-center">Jumlah Kegiatan</th>
							<th class="text-center">Jumlah Sub Kegiatan</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function() {
    	run_download_excel_sakip();
    	jQuery('#action-sakip').prepend('<a style="margin-right: 10px;" id="singkron-cascading-renstra" href="#" class="btn btn-primary"><i class="dashicons dashicons-download"></i> Ambil dari Data RENSTRA</a>');
		getTableSkpd();

		jQuery('#cek_all').on('click', function(e){
			jQuery('.table_dokumen_skpd tbody .nama-opd').prop('checked', jQuery(this).is(':checked'));
		});

		jQuery('#singkron-cascading-renstra').on('click', function(e){
            e.preventDefault();
            var id_skpd_all = [];
            jQuery('.table_dokumen_skpd tbody .nama-opd').map(function(i, b){
            	if(jQuery(b).is(':checked')){
	            	id_skpd_all.push({
	            		id: jQuery(b).val(),
	            		nama_skpd: jQuery(b).closest('tr').find('.nama-opd-asli').text()
	            	});
	            }
            });
            if(id_skpd_all.length == 0){
            	return alert('Pilih perangkat daerah dulu!');
            }
            if(confirm('Apakah anda yakin untuk mengambil data CASCADING dari RENSTRA? Data lama akan diupdate dengan data baru!')){
                jQuery('#wrap-loading').show();
                jQuery('#persen-loading').attr('persen', 0);
				jQuery('#persen-loading').html('0%');
                var last = id_skpd_all.length-1;
				id_skpd_all.reduce(function(sequence, nextData){
		            return sequence.then(function(current_data){
		        		return new Promise(function(resolve_reduce, reject_reduce){
							jQuery('#pesan-loading').html('Singkron CASCADING '+current_data.nama_skpd);
			                jQuery.ajax({
			                    url: esakip.url,
			                    type: 'POST',
			                    data: {
			                        action: 'get_cascading_pd_from_renstra',
			                        api_key: esakip.api_key,
			                        id_jadwal_wpsipd: <?php echo $input['periode']; ?>,
			                        id_skpd: current_data.id
			                    },
			                    dataType: 'json',
			                    success: function(response) {
				        			var c_persen = +jQuery('#persen-loading').attr('persen');
									c_persen++;
									jQuery('#persen-loading').attr('persen', c_persen);
									jQuery('#persen-loading').html(((c_persen/id_skpd_all.length)*100).toFixed(2)+'%'+'<br>'+current_data.nama_skpd);
			                        resolve_reduce(nextData);
			                    },
			                    error: function(xhr, status, error) {
			                        console.error(xhr.responseText);
			                        resolve_reduce(nextData);
			                    }
			                });
		        		})
		                .catch(function(e){
		                    console.log(e);
		                    return Promise.resolve(nextData);
		                });
		            })
		            .catch(function(e){
		                console.log(e);
		                return Promise.resolve(nextData);
		        	});
		        }, Promise.resolve(id_skpd_all[last]))
		        .then(function(data_last){
			        jQuery('#wrap-loading').hide();
		        	getTableSkpd(1);
		        });
            }
        });
	});

	function getTableSkpd(destroy) {
		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_table_skpd_input_cascading',
				api_key: esakip.api_key,
				periode: <?php echo $input['periode']; ?>,
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					if(destroy == 1){
						table_cascading.fnDestroy();
					}
					jQuery('.table_dokumen_skpd tbody').html(response.data);
					window.table_cascading = jQuery('.table_dokumen_skpd').dataTable({
						 aLengthMenu: [
					        [5, 10, 25, 100, -1],
					        [5, 10, 25, 100, "All"]
					    ],
					    iDisplayLength: -1
					});
				} else {
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
				jQuery('#wrap-loading').hide();
				console.error(xhr.responseText);
				alert('Terjadi kesalahan saat memuat tabel!');
			}
		});
	}

	function toDetailUrl(url) {
		window.open(url, '_blank');
	}
</script>