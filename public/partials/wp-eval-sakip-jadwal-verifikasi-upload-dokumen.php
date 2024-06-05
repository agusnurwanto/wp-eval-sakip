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
$tahun_anggaran = $input['tahun_anggaran'];

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
	<h1 class="text-center" style="margin:3rem;">Halaman Penjadwalan Verifikasi Upload Dokumen <br>Tahun <?php echo $input['tahun_anggaran']; ?></h1>
	<!-- <div style="margin-bottom: 25px;">
		<button class="btn btn-primary tambah_jadwal" onclick="tambah_jadwal();">Tambah Jadwal</button>
	</div> -->
	<table id="data_penjadwalan_table" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
		<thead id="data_header">
			<tr>
				<th class="text-center">Nama Jadwal</th>
				<th class="text-center">Status</th>
				<th class="text-center">Jadwal Mulai</th>
				<th class="text-center">Jadwal Selesai</th>
				<th class="text-center">Tahun Anggaran</th>
				<th class="text-center">Langsung Verifikasi</th>
				<th class="text-center">Keterangan</th>
				<th class="text-center">Aksi</th>
			</tr>
		</thead>
		<tbody id="data_body">
		</tbody>
	</table>
	</div>
</div>

<!-- Modal Edit Jadwal -->
<div class="modal fade" id="modalJadwal" tabindex="-1" role="dialog" aria-labelledby="modalJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalJadwalLabel">Tambah Penjadwalan Verifikasi Upload Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<div class="form-group">
						<label for='nama_jadwal' style='display:inline-block'>Nama Jadwal</label>
						<input type='text' id='nama_jadwal' style='display:block;width:100%;' placeholder='Nama Jadwal'>
					</div>
					<div class="form-group">
						<label for='jadwal_tanggal' style='display:inline-block'>Jadwal Pelaksanaan</label>
						<input type="text" id='jadwal_tanggal' name="datetimes" style='display:block;width:100%;' />
					</div>
					<div class="form-group">
						<label for='keterangan' style='display:inline-block'>Keterangan</label>
						<textarea class="form-control" id="keterangan" name="keterangan"  style='display:block;width:100%;' rows="3"></textarea>
					</div>
					<div class="form-group">
						<label class="d-block">Langsung Verifikasi Upload Dokumen</label>
						<tr>
							<td>
								<div class="custom-control custom-radio custom-control-inline">
									<input class="custom-control-input" type="radio" name="langsung_verifikasi" id="langsung_verifikasi_iya" value="iya">
									<label class="custom-control-label" for="langsung_verifikasi_iya">Iya</label>
								</div>
							</td>
							<td>
								<div class="custom-control custom-radio custom-control-inline">
									<input class="custom-control-input" type="radio" name="langsung_verifikasi" id="langsung_verifikasi_tidak" value="tidak">
									<label class="custom-control-label" for="langsung_verifikasi_tidak">Tidak</label>
								</div>
							</td>
						</tr>
						<small class="d-block form-text text-muted">*Pengaturan ini menentukan langsung verifikasi atau tidak saat upload dokumen.</small>
					</div>
                </form>
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

		window.thisAjaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>"
		window.tahun_anggaran = "<?php echo $tahun_anggaran; ?>"

		get_data_jadwal();
		
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

	/** get data jadwal*/
	function get_data_jadwal(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data:{
				'action' 		: "get_data_penjadwalan_verifikasi_upload_dokumen",
				'api_key' 		: esakip.api_key,
				'tahun_anggaran': tahun_anggaran
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('#data_penjadwalan_table tbody').html(response.data);
					if (response.cekJadwalTerbuka != 'undefined' && response.cekJadwalTerbuka > 0) {
						jQuery(".tambah_jadwal").prop('hidden', true);
					} else {
						jQuery(".tambah_jadwal").prop('hidden', false);
					}
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
	
	function tambah_jadwal() {
		jQuery("#modalJadwal .modal-title").html("Tambah Penjadwalan");
		jQuery('#nama_jadwal').val('').prop('disabled', false);
		jQuery("#modalJadwal .submitBtn")
			.attr("onclick", 'submitTambahJadwalForm()')
			.attr("disabled", false)
			.text("Simpan");
		jQuery('#modalJadwal').modal('show');
	}
	
	function submitTambahJadwalForm() {
		jQuery("#wrap-loading").show()
		let nama_jadwal = jQuery('#nama_jadwal').val()
		let jadwal_mulai = jQuery("#jadwal_tanggal").data('daterangepicker').startDate.format('YYYY-MM-DD HH:mm:ss')
		let jadwal_selesai = jQuery("#jadwal_tanggal").data('daterangepicker').endDate.format('YYYY-MM-DD HH:mm:ss')
		let langsung_verifikasi = jQuery("input[name='langsung_verifikasi']:checked").val();
		if (nama_jadwal.trim() == '' || jadwal_mulai == '' || jadwal_selesai == '' || langsung_verifikasi == '') {
			jQuery("#wrap-loading").hide()
			alert("Ada yang kosong, Harap diisi semua")
			return false
		} else {
			jQuery.ajax({
				url: esakip.url,
				type: 'post',
				dataType: 'json',
				data: {
					'action': 'submit_jadwal_verifikasi_upload_dokumen',
					'api_key': esakip.api_key,
					'nama_jadwal': nama_jadwal,
					'jadwal_mulai': jadwal_mulai,
					'jadwal_selesai': jadwal_selesai,
					'tahun_anggaran': tahun_anggaran,
					'langsung_verifikasi':langsung_verifikasi
				},
				beforeSend: function() {
					jQuery('.submitBtn').attr('disabled', 'disabled')
				},
				success: function(response) {
					jQuery('#modalJadwal').modal('hide')
					jQuery('#wrap-loading').hide()
					if (response.status == 'success') {
						alert('Data berhasil ditambahkan')
						get_data_jadwal()
						jQuery(".tambah_jadwal").prop('hidden', true);
					} else {
						alert(response.message)
					}
				}
			})
		}
		jQuery('#modalJadwal').modal('hide');
	}

	function edit_data_penjadwalan(id) {
		jQuery('#modalJadwal').modal('show');
		jQuery("#modalJadwal .modal-title").html("Edit Penjadwalan");
		jQuery("#modalJadwal .submitBtn")
			.attr("onclick", 'submitEditJadwalForm(' + id + ')')
			.attr("disabled", false)
			.text("Simpan");
		jQuery("#wrap-loading").show()
		jQuery.ajax({
			url: esakip.url,
			type: "post",
			data: {
				'action': "get_data_jadwal_by_id_verifikasi_upload_dokumen",
				'api_key': esakip.api_key,
				'id': id
			},
			dataType: "json",
			success: function(response) {
				jQuery("#wrap-loading").hide()
				jQuery("#nama_jadwal").val(response.data.nama_jadwal);
				jQuery("#keterangan").val(response.data.keterangan);
				jQuery('#jadwal_tanggal').data('daterangepicker').setStartDate(moment(response.data.started_at).format('DD-MM-YYYY HH:mm'));
				jQuery('#jadwal_tanggal').data('daterangepicker').setEndDate(moment(response.data.end_at).format('DD-MM-YYYY HH:mm'));
				let pengaturan_verifikasi = (response.data.default_verifikasi_upload == 1) ? "iya" : "tidak";
				jQuery("input[name=langsung_verifikasi][value='"+pengaturan_verifikasi+"']").prop("checked",true);
			}
		})
	}

	function submitEditJadwalForm(id) {
		jQuery("#wrap-loading").show()
		let nama_jadwal = jQuery('#nama_jadwal').val()
		let keterangan = jQuery('#keterangan').val()
		let jadwal_mulai = jQuery("#jadwal_tanggal").data('daterangepicker').startDate.format('YYYY-MM-DD HH:mm:ss')
		let jadwal_selesai = jQuery("#jadwal_tanggal").data('daterangepicker').endDate.format('YYYY-MM-DD HH:mm:ss')
		let verifikasi_upload = jQuery("input[name='langsung_verifikasi']:checked").val();

		if (nama_jadwal.trim() == '' || jadwal_mulai == '' || jadwal_selesai == '' || verifikasi_upload == '') {
			jQuery("#wrap-loading").hide()
			alert("Ada yang kosong, Harap diisi semua")
			return false
		} else {
			jQuery.ajax({
				url: esakip.url,
				type: 'post',
				dataType: 'json',
				data: {
					'action': 'submit_edit_jadwal_verifikasi_upload_dokumen',
					'api_key': esakip.api_key,
					'nama_jadwal': nama_jadwal,
					'jadwal_mulai': jadwal_mulai,
					'jadwal_selesai': jadwal_selesai,
					'id': id,
					'tahun_anggaran': tahun_anggaran,
					'keterangan'	: keterangan,
					'verifikasi_upload': verifikasi_upload
				},
				beforeSend: function() {
					jQuery('.submitBtn').attr('disabled', 'disabled')
				},
				success: function(response) {
					jQuery('#modalJadwal').modal('hide')
					jQuery('#wrap-loading').hide()
					if (response.status == 'success') {
						alert('Data berhasil diperbarui')
						get_data_jadwal()
					} else {
						alert(`GAGAL! \n${response.message}`)
					}
				}
			})
		}
		jQuery('#modalJadwal').modal('hide');
	}

	function hapus_data_penjadwalan(id) {
		let confirmDelete = confirm("Apakah anda yakin akan menghapus penjadwalan?");
		if (confirmDelete) {
			jQuery('#wrap-loading').show();
			jQuery.ajax({
				url: esakip.url,
				type: 'post',
				data: {
					'action': 'delete_jadwal_verifikasi_upload_dokumen',
					'api_key': esakip.api_key,
					'id': id
				},
				dataType: 'json',
				success: function(response) {
					jQuery('#wrap-loading').hide();
					if (response.status == 'success') {
						alert('Data berhasil dihapus!.');
						get_data_jadwal()
						jQuery(".tambah_jadwal").prop('hidden', false);
					} else {
						alert(`GAGAL! \n${response.message}`);
					}
				}
			});
		}
	}

	// function lock_data_penjadwalan(id) {
	// 	let confirmLocked = confirm("Apakah anda yakin akan mengunci penjadwalan?");
	// 	if (confirmLocked) {
	// 		jQuery('#wrap-loading').show();
	// 		jQuery.ajax({
	// 			url: esakip.url,
	// 			type: 'post',
	// 			data: {
	// 				'action': 'lock_jadwal',
	// 				'api_key': esakip.api_key,
	// 				'id': id
	// 			},
	// 			dataType: 'json',
	// 			success: function(response) {
	// 				jQuery('#wrap-loading').hide();
	// 				if (response.status == 'success') {
	// 					alert('Data berhasil dikunci!.');
	// 					penjadwalanTable.ajax.reload();
	// 					jQuery(".tambah_jadwal").prop('hidden', false);
	// 				} else {
	// 					alert(`GAGAL! \n${response.message}`);
	// 				}
	// 			}
	// 		});
	// 	}
	// }

	function afterSubmitForm(){
		jQuery("#keterangan").val("")
	}

</script> 
