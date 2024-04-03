<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $wpdb;
$input = shortcode_atts(array(
    'tahun_anggaran' => ''
), $atts);
if (!empty($_GET) && !empty($_GET['tahun_anggaran'])) {
    $input['tahun_anggaran'] = $wpdb->prepare('%d', $_GET['tahun_anggaran']);
}

$body = '';
?>
<style>
.bulk-action {
	padding: .45rem;
	border-color: #eaeaea;
	vertical-align: middle;
}
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<div class="cetak">
	<div style="padding: 10px;margin:0 0 3rem 0;">
		<input type="hidden" value="<?php echo get_option( '_crb_apikey_esakip' ); ?>" id="api_key">
	<h1 class="text-center" style="margin:3rem;">Halaman Penjadwalan Tahun <?php echo $input['tahun_anggaran']; ?></h1>
		<div style="margin-bottom: 25px;">
			<button class="btn btn-primary tambah_jadwal" onclick="tambah_jadwal();">Tambah Jadwal</button>
		</div>
		<table id="data_penjadwalan_table" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
			<thead id="data_header">
				<tr>
					<th class="text-center">Nama Jadwal</th>
					<th class="text-center">Status</th>
					<th class="text-center">Jadwal Mulai</th>
					<th class="text-center">Jadwal Selesai</th>
					<th class="text-center">Jenis Jadwal</th>
					<th class="text-center">Tahun Anggaran</th>
					<th class="text-center" style="width: 150px;">Aksi</th>
				</tr>
			</thead>
			<tbody id="data_body">
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade mt-4" id="modalTambahJadwal" tabindex="-1" role="dialog" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalTambahJadwalLabel">Tambah Penjadwalan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div>
					<label for='nama_jadwal' style='display:inline-block'>Nama Jadwal</label>
					<input type='text' id='nama_jadwal' style='display:block;width:100%;' placeholder='Nama Jadwal'>
				</div>
				<div>
					<label for='jadwal_tanggal' style='display:inline-block'>Jadwal Pelaksanaan</label>
					<input type="text" id='jadwal_tanggal' name="datetimes" style='display:block;width:100%;'/>
				</div>
				<div>
					<label for="jenis_jadwal" style='display:inline-block'>Pilih Jenis Jadwal</label>
					<select id="jenis_jadwal" style='display:block;width: 100%;'>
						<option value="usulan" selected>Usulan</option>
						<option value="penetapan">Penetapan</option>
					</select>
				</div>
				<!-- <div>
					<label for='lama_pelaksanaan' style='display:block'>Lama Pelaksanaan</label>
					<input type="number" id='lama_pelaksanaan' name="lama_pelaksanaan" value="1" style='display:inline-block;width:50%;' placeholder="1"/> Tahun
				</div> -->
			</div> 
			<div class="modal-footer">
				<button class="btn btn-primary submitBtn" onclick="submitTambahJadwalForm()">Simpan</button>
				<button type="submit" class="components-button btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
	jQuery(document).ready(function(){

		globalThis.thisAjaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>"
		globalThis.tahun_anggaran = "<?php echo $input['tahun_anggaran']; ?>"
		globalThis.tipe = 'LKE'
		get_data_penjadwalan();
		jQuery('#selectYears').on('change', function(e) {
			let selectedVal = jQuery(this).find('option:selected').val();
			if (selectedVal != '') {
				window.location = selectedVal;
			}
		});

		jQuery('#jadwal_tanggal').daterangepicker({
			timePicker: true,
			timePicker24Hour: true,
			startDate: moment().startOf('hour'),
			endDate: moment().startOf('hour').add(32, 'hour'),
			locale: {
				format: 'DD-MM-YYYY HH:mm'
			}
		});

	});

	/** get data penjadwalan */
	function get_data_penjadwalan(){
		jQuery("#wrap-loading").show();
		globalThis.penjadwalanTable = jQuery('#data_penjadwalan_table').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: thisAjaxUrl,
				type:"post",
				data:{
					'action' 		: "get_data_penjadwalan",
					'api_key' 		: jQuery("#api_key").val(),
					'tipe'			: tipe,
                    'tahun_anggaran': '<?php echo $input['tahun_anggaran']; ?>'
				}
			},
			"initComplete":function( settings, json){
				jQuery("#wrap-loading").hide();
				if (json.checkOpenedSchedule != 'undefined' && json.checkOpenedSchedule > 0) {
					jQuery(".tambah_jadwal").prop('hidden', true);
				} else {
					jQuery(".tambah_jadwal").prop('hidden', false);
				}
			},
			"columns": [
				{ 
					"data": "nama_jadwal",
					className: "text-center"
				},
				{ 
					"data": "status",
					className: "text-center"
				},
				{ 
					"data": "started_at",
					className: "text-center"
				},
				{ 
					"data": "end_at",
					className: "text-center"
				},
				{ 
					"data": "jenis_jadwal",
					className: "text-center"
				},
				{ 
					"data": "tahun_anggaran",
					className: "text-center"
				},
				{ 
					"data": "aksi",
					className: "text-center"
				}
			]
		});
	}

	/** show modal tambah jadwal */
	function tambah_jadwal(){
		jQuery("#modalTambahJadwal .modal-title").html("Tambah Penjadwalan");
		jQuery("#modalTambahJadwal .submitBtn")
			.attr("onclick", 'submitTambahJadwalForm()')
			.attr("disabled", false)
			.text("Simpan");
		jQuery('#modalTambahJadwal').modal('show');
		// jQuery.ajax({
		// 	url: thisAjaxUrl,
		// 	type:"post",
		// 	data:{
		// 		'action' 			: "get_lama_pelaksanaan",
		// 		'api_key' 			: jQuery("#api_key").val(),
		// 		'tipe' 				: tipe
		// 		'tahun_anggaran'	: <? echo $input['tahun_anggaran']; ?>
		// 	},
		// 	dataType: "json",
		// 	success:function(response){
		// 		jQuery("#lama_pelaksanaan").val(response.data.lama_pelaksanaan);
		// 	}
		// })
	}

	/** Submit tambah jadwal */
	function submitTambahJadwalForm(){
		jQuery("#wrap-loading").show()
		let this_tahun_anggaran = tahun_anggaran;
		let nama_jadwal = jQuery('#nama_jadwal').val()
		let jadwalMulai = jQuery("#jadwal_tanggal").data('daterangepicker').startDate.format('YYYY-MM-DD HH:mm:ss')
		let jadwalSelesai = jQuery("#jadwal_tanggal").data('daterangepicker').endDate.format('YYYY-MM-DD HH:mm:ss')
		let jenis_jadwal = 1;
		jenis_jadwal = jQuery("#jenis_jadwal").val();
		if(nama_jadwal.trim() == '' || jadwalMulai == '' || jadwalSelesai == '' || jenis_jadwal == ''){
			jQuery("#wrap-loading").hide()
			alert("Ada yang kosong, Harap diisi semua")
			return false
		}else{
			jQuery.ajax({
				url: thisAjaxUrl,
				type: 'post',
				dataType: 'json',
				data:{
					'action'			: 'submit_jadwal',
					'api_key'			: jQuery("#api_key").val(),
					'nama_jadwal'		: nama_jadwal,
					'jadwal_mulai'		: jadwalMulai,
					'jadwal_selesai'	: jadwalSelesai,
					'jenis_jadwal'		: jenis_jadwal,
					'tahun_anggaran'	: this_tahun_anggaran
				},
				beforeSend: function() {
					jQuery('.submitBtn').attr('disabled','disabled')
				},
				success: function(response){
					jQuery('#modalTambahJadwal').modal('hide')
					jQuery('#wrap-loading').hide()
					if(response.status == 'success'){
						alert('Data berhasil ditambahkan')
						penjadwalanTable.ajax.reload()
						jQuery(".tambah_jadwal").prop('hidden', true);
					}else{
						alert(response.message)
					}
				}
			})
		}
		jQuery('#modalTambahJadwal').modal('hide');
	}

	/** edit akun ssh usulan */
	function edit_data_penjadwalan(id){
		jQuery('#modalTambahJadwal').modal('show');
		jQuery("#modalTambahJadwal .modal-title").html("Edit Penjadwalan");
		jQuery("#modalTambahJadwal .submitBtn")
			.attr("onclick", 'submitEditJadwalForm('+id+')')
			.attr("disabled", false)
			.text("Simpan");
		jQuery("#wrap-loading").show()
		jQuery.ajax({
			url: thisAjaxUrl,
			type:"post",
			data:{
				'action' 			: "get_data_jadwal_by_id",
				'api_key' 			: jQuery("#api_key").val(),
				'id' 	: id
			},
			dataType: "json",
			success:function(response){
				jQuery("#wrap-loading").hide()
				jQuery("#nama_jadwal").val(response.data.nama_jadwal);
					jQuery("#jenis_jadwal").val(response.data.jenis_jadwal).change();
				jQuery('#jadwal_tanggal').data('daterangepicker').setStartDate(moment(response.data.started_at).format('DD-MM-YYYY HH:mm'));
				jQuery('#jadwal_tanggal').data('daterangepicker').setEndDate(moment(response.data.end_at).format('DD-MM-YYYY HH:mm'));
			}
		})
	}

	function submitEditJadwalForm(id){
		jQuery("#wrap-loading").show()
		let this_tahun_anggaran = tahun_anggaran;
		let nama_jadwal = jQuery('#nama_jadwal').val()
		let jadwalMulai = jQuery("#jadwal_tanggal").data('daterangepicker').startDate.format('YYYY-MM-DD HH:mm:ss')
		let jadwalSelesai = jQuery("#jadwal_tanggal").data('daterangepicker').endDate.format('YYYY-MM-DD HH:mm:ss')
		let jenis_jadwal = jQuery("#jenis_jadwal").val();
		if(nama_jadwal.trim() == '' || jadwalMulai == '' || jadwalSelesai == '' || jenis_jadwal == ''){
			jQuery("#wrap-loading").hide()
			alert("Ada yang kosong, Harap diisi semua")
			return false
		}else{
			jQuery.ajax({
				url: thisAjaxUrl,
				type: 'post',
				dataType: 'json',
				data:{
					'action'			: 'submit_edit_jadwal',
					'api_key'			: jQuery("#api_key").val(),
					'nama_jadwal'		: nama_jadwal,
					'jadwal_mulai'		: jadwalMulai,
					'jadwal_selesai'	: jadwalSelesai,
					'jenis_jadwal'		: jenis_jadwal,
					'tipe'				: tipe,
					'tahun_anggaran'	: this_tahun_anggaran
				},
				beforeSend: function() {
					jQuery('.submitBtn').attr('disabled','disabled')
				},
				success: function(response){
					jQuery('#modalTambahJadwal').modal('hide')
					jQuery('#wrap-loading').hide()
					if(response.status == 'success'){
						alert('Data berhasil diperbarui')
						penjadwalanTable.ajax.reload()
					}else{
						alert(`GAGAL! \n${response.message}`)
					}
				}
			})
		}
		jQuery('#modalTambahJadwal').modal('hide');
	}

	function hapus_data_penjadwalan(id){
		let confirmDelete = confirm("Apakah anda yakin akan menghapus penjadwalan?");
		if(confirmDelete){
			jQuery('#wrap-loading').show();
			jQuery.ajax({
				url: thisAjaxUrl,
				type:'post',
				data:{
					'action' 	: 'delete_jadwal',
					'api_key'	: jQuery("#api_key").val(),
					'id'		: id
				},
				dataType: 'json',
				success:function(response){
					jQuery('#wrap-loading').hide();
					if(response.status == 'success'){
						alert('Data berhasil dihapus!.');
						penjadwalanTable.ajax.reload();	
						jQuery(".tambah_jadwal").prop('hidden', false);
					}else{
						alert(`GAGAL! \n${response.message}`);
					}
				}
			});
		}
	}

	function lock_data_penjadwalan(id){
		let confirmLocked = confirm("Apakah anda yakin akan mengunci penjadwalan?");
		if(confirmLocked){
			jQuery('#wrap-loading').show();
			jQuery.ajax({
				url: thisAjaxUrl,
				type:'post',
				data:{
					'action' 				: 'lock_jadwal',
					'api_key'				: jQuery("#api_key").val(),
					'id'		: id
				},
				dataType: 'json',
				success:function(response){
					jQuery('#wrap-loading').hide();
					if(response.status == 'success'){
						alert('Data berhasil dikunci!.');
						penjadwalanTable.ajax.reload();
						jQuery(".tambah_jadwal").prop('hidden', false);
					}else{
						alert(`GAGAL! \n${response.message}`);
					}
				}
			});
		}
	}

	function cannot_change_schedule(jenis){
		if(jenis == 'kunci'){
			alert('Tidak bisa kunci karena penjadwalan sudah dikunci');
		}else if(jenis == 'edit'){
			alert('Tidak bisa edit karena penjadwalan sudah dikunci');
		}else if(jenis == 'hapus'){
			alert('Tidak bisa hapus karena penjadwalan sudah dikunci');
		}
	}

	function afterSubmitForm(){
		jQuery("#nama").val("")
		jQuery("#tahun_anggaran").val("")
		jQuery("#jadwal_tanggal").val("")
	}

</script> 
