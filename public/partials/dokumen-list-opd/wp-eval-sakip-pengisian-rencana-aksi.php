<?php
global $wpdb;

if (!defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'tahun' => '2022'
), $atts);

$idtahun = $wpdb->get_results(
	"
		SELECT DISTINCT 
			tahun_anggaran 
		FROM esakip_data_unit        
		ORDER BY tahun_anggaran DESC",
	ARRAY_A
);
$tahun = '<option value="0">Pilih Tahun</option>';

foreach ($idtahun as $val) {
	if($val['tahun_anggaran'] == $input['tahun']){
		continue;
	}
	$selected = '';
	if($val['tahun_anggaran'] == $input['tahun']-1){
		$selected = 'selected';
	}
	$tahun .= '<option value="'. $val['tahun_anggaran']. '" '. $selected .'>'. $val['tahun_anggaran'] .'</option>';
}
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
			<h1 class="text-center table-title">Pengisian Rencana Hasil Kerja</br>Tahun Anggaran <?php echo $input['tahun']; ?></h1>
			<div id="action" class="action-section hide-excel"></div>
			<div class="wrap-table">
				<table id="cetak" title="Rekapitulasi Rencana Hasil Kerja Perangkat Daerah" class="table table-bordered table_dokumen_skpd" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;">
					<thead style="background: #ffc491;">
						<tr>
							<th class="text-center">Nama Perangkat Daerah</th>
							<th class="text-center" width="100px">Kegiatan Utama</th>
							<th class="text-center" width="100px">Rencana Hasil Kerja</th>
							<th class="text-center" width="100px">Uraian Kegiatan Rencana Hasil Kerja</th>
							<th class="text-center" width="100px">Uraian Teknis Kegiatan</th>
							<th class="text-center" width="100px">Rencana Pagu</th>
							<th class="text-center" width="100px">Pagu Rincin</th>
							<th class="text-center" width="100px">Realisasi Pagu</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot style="background: #ffc491;">
						<tr>
							<th class="text-center">Jumlah</th>
							<th class="text-center" id="total_kegiatan_utama">0</th>
							<th class="text-center" id="total_rencana_aksi">0</th>
							<th class="text-center" id="total_uraian_kegiatan_rencana_aksi">0</th>
							<th class="text-center" id="total_uraian_teknis_kegiatan">0</th>
							<th class="text-right" id="total_rencana_pagu">0</th>
							<th class="text-right" id="total_alokasi_pagu">0</th>
							<th class="text-right" id="total_realisasi_pagu">0</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal" data-backdrop="static"  role="dialog" aria-labelledby="modal-label" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
		        <h5 class="modal-title">Modal title</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          	<span aria-hidden="true">&times;</span>
		        </button>
	      	</div>
	      	<div class="modal-body">
	      	</div>
	      	<div class="modal-footer"></div>
    	</div>
  	</div>
</div>

<script>
	jQuery(document).ready(function() {
		let listSkpd;
    	run_download_excel_sakip();
		jQuery('#action-sakip').prepend('<button style="margin-right: 10px;" class="btn btn-danger" onclick="copyRhk();"><i class="dashicons dashicons-admin-page"></i> Copy Data RHK</button>');
		getTableSkpd();
	});

	function copyRhk(){
			let tbody = '';
			let tahun = '<?php echo $tahun; ?>';
		    listSkpd.forEach(function(value, index){
		        tbody += ''
			        +'<tr>'
			        	+'<td class="text-center"><input type="checkbox" value="'+value.id_skpd+'"></td>'
			        	+'<td>'+value.nama_skpd+'</td>'
			        +'</tr>';
		    })
		jQuery("#modal").find('.modal-title').html('Copy Data Rencana Hasil Kerja');
		jQuery("#modal").find('.modal-body').html(`
			<div class="form-group row">
				<label for="staticEmail" class="col-sm-3 col-form-label">Tahun Anggaran Sumber RHK</label>
				<div class="col-sm-9 d-flex align-items-center justify-content-center">
					<select id="tahunAnggaranCopy" class="form-control">
						${tahun}
					</select>
				</div>
			</div>
			<table class="table table-bordered table-sticky table-modal-rhk">
				<thead>
					<tr>
						<th class="text-center"><input type="checkbox" class="check_all" onclick="check_all(this);"></th>
						<th class="text-center">Nama OPD</th>
					</tr>
				</thead>
				<tbody>
					${tbody}
				</tbody>
			</table>
		`);
		jQuery("#modal").find('.modal-footer').html(`
			<button type="button" class="btn btn-warning" data-dismiss="modal">
				Tutup
			</button>
			<button type="button" class="btn btn-danger" onclick="submitCopyRhk()">
				Copy Data
			</button>`);
		jQuery("#modal").find('.modal-dialog').css('maxWidth','700');
		jQuery("#modal").modal('show');
	}

	function check_all(that){
		if(jQuery(that).is(':checked')){
			jQuery(that).closest('table').find('tbody input[type="checkbox"]').prop('checked', true);
		}else{
			jQuery(that).closest('table').find('tbody input[type="checkbox"]').prop('checked', false);
		}
	}
	
	function submitCopyRhk(){
		if (!confirm('Apakah anda yakin akan copy data RENCANA HASIL KERJA? \nData yang sudah ada akan ditimpa oleh data baru hasil copy data RENCANA HASIL KERJA!')) {
            return;
        }
		var tahun_anggaran = jQuery("#tahunAnggaranCopy").val();
		var value = '';

		value = [];
		jQuery('.table-modal-rhk tbody input[type="checkbox"]').map(function(i, b){
			if(jQuery(b).is(":checked")){
				value.push(jQuery(b).val());
			}
		});
		if(value.length == 0){
			return alert('OPD belum dipilih!');
		}

		jQuery('#wrap-loading').show();
		var last = value.length-1;
        value.reduce(function(sequence, nextData){
            return sequence.then(function(current_data){
                return new Promise(function(resolve_reduce, reject_reduce){
                	var nama_opd = jQuery('.table-modal-rhk tbody input[type="checkbox"][value="'+current_data+'"]').closest('tr').find('td').eq(1).text();
                	pesan_loading('Copy Data RHK OPD '+nama_opd);
					ajax_copy_rhk({
						tahun_anggaran: tahun_anggaran,
						id_skpd: current_data
					})
					.then(function(){
						return resolve_reduce(nextData);
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
        }, Promise.resolve(value[last]))
        .then(function(data_last){
            alert('Berhasil Copy Data Rencana Hasil Kerja.');
            jQuery('#wrap-loading').hide();
			jQuery('#pesan-loading').html('');
        })
        .catch(function(err){
            console.log('err', err);
            alert('Ada kesalahan sistem!');
            jQuery('#wrap-loading').hide();
			jQuery('#pesan-loading').html('');
        });
	}

	function ajax_copy_rhk(options){
		return new Promise(function(resolve, reject){
		    jQuery.ajax({
		        url: esakip.url,
		        type: 'POST',
		        data: {
		            action: 'copy_data_rencana_aksi',
		            api_key: esakip.api_key,
		            tahun_anggaran_sumber_rhk: options.tahun_anggaran,
		            id_skpd: options.id_skpd,
					tahun_anggaran_tujuan: <?php echo $input['tahun']; ?>
		        },
		        dataType: 'json',
		        success: function(response) {
		            resolve();
		        },
		        error: function(xhr, status, error) {
		    	    console.log('error', error);
		            resolve();
		        }
		    });
		});
	}

	function getTableSkpd() {
		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_table_skpd_pengisian_rencana_aksi',
				api_key: esakip.api_key,
				tahun_anggaran: <?php echo $input['tahun']; ?>,
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('#total_kegiatan_utama').html(response.total_level_1);
					jQuery('#total_rencana_aksi').html(response.total_level_2);
					jQuery('#total_uraian_kegiatan_rencana_aksi').html(response.total_level_3);
					jQuery('#total_uraian_teknis_kegiatan').html(response.total_level_4);
					jQuery('#total_rencana_pagu').html(response.total_pagu);
					jQuery('.table_dokumen_skpd tbody').html(response.data);
					jQuery('.table_dokumen_skpd').dataTable({
						 aLengthMenu: [
					        [5, 10, 25, 100, -1],
					        [5, 10, 25, 100, "All"]
					    ],
					    iDisplayLength: -1
					});
					listSkpd = response.list_skpd;
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