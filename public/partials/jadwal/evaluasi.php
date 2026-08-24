<?php
global $wpdb;

if (!defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'tahun_anggaran' => ''
), $atts);

if (!empty($_GET) && !empty($_GET['tahun_anggaran'])) {
	$input['tahun_anggaran'] = $wpdb->prepare('%d', $_GET['tahun_anggaran']);
}
$tahun_anggaran = $input['tahun_anggaran'];

$url_cek_dokumen = '';
$cek_dokumen = $this->functions->generatePage(array(
	'nama_page'   => 'Laporan Monitor Upload Dokumen | ' . $tahun_anggaran,
	'content'	  => '[halaman_cek_dokumen tahun_anggaran="' . $tahun_anggaran . '"]',
	'show_header' => 1,
	'no_key' 	  => 1,
	'post_status' => 'private'
));
$url_cek_dokumen .= $cek_dokumen['url'];

$data_jadwal = $wpdb->get_results(
	$wpdb->prepare('
		SELECT
			id, 
			nama_jadwal,
			started_at,
			end_at,
			status,
			tahun_anggaran
		FROM esakip_data_jadwal
		WHERE tipe = %s
		  AND status != 0 
		ORDER BY started_at ASC
	', 'LKE'),
	ARRAY_A
);

$option_jadwal = '';
if (!empty($data_jadwal)) {
	foreach ($data_jadwal as $v) {
		// Menentukan status jadwal
		$status_text = '';
		if ($v['status'] == 1) {
			$status_text = ' - [AKTIF]';
		} elseif ($v['status'] == 2) {
			$status_text = ' - [DIKUNCI]';
		}

		$option_jadwal .= '<option value="' . htmlspecialchars($v['id']) . '">'
			. htmlspecialchars($v['nama_jadwal']) . ' | ' . htmlspecialchars($v['tahun_anggaran'])
			. ' (' . date("d M Y", strtotime($v['started_at'])) . ' - ' . date("d M Y", strtotime($v['end_at'])) . ')'
			. $status_text
			. '</option>';
	}
}

?>
<style>
	.bulk-action {
		padding: .45rem;
		border-color: #eaeaea;
		vertical-align: middle;
	}

	.btn-group {
		display: inline-block;
		margin-bottom: 5px;
		margin-right: 5px;
	}
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<div class="cetak">
	<div style="padding: 10px;margin:0 0 3rem 0;">
		<h1 class="text-center" style="margin:3rem;">Halaman Penjadwalan Lembar Kerja Evaluasi SAKIP <br>Tahun <?php echo $input['tahun_anggaran']; ?></h1>
		<div style="margin-bottom: 25px;">
			<button class="btn btn-primary tambah_jadwal" onclick="tambah_jadwal();">
				<span class="dashicons dashicons-plus"></span> Tambah Jadwal
			</button>
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
					<th class="text-center">Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<div class="hide-display-print container mt-4 p-4 mb-4 border rounded bg-light">
	<h4 class="font-weight-bold mb-3 text-dark">Catatan Jadwal LKE:</h4>
	<ul class="pl-3 text-muted">
		<li class="text-danger font-weight-bold">⚠️ Hanya <strong>satu jadwal</strong> yang dapat aktif dalam satu Tahun Anggaran!</li>
		<li>Jenis jadwal secara default adalah <strong>"Usulan"</strong>. Untuk mengubahnya menjadi <strong>"Penetapan"</strong>, gunakan tombol <strong>Edit</strong>, ubah jenis jadwal, lalu simpan.</li>
		<li>Setelah jadwal dibuat, desain pertanyaan LKE akan otomatis diset berdasarkan <strong>template yang dipilih</strong> (template dari jadwal sebelumnya di tahun yang sama atau jadwal sebelumnya yang pernah dibuat).</li>
		<li>Jika tidak ada template yang dipilih, maka sistem akan menggunakan <strong>template default</strong> untuk desain pertanyaan.</li>
	</ul>
</div>


<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalTambahJadwalLabel">Tambah Penjadwalan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="tambahJadwalForm">
					<div class="form-group">
						<label for="nama_jadwal">Nama Jadwal</label>
						<input type="text" id="nama_jadwal" class="form-control" placeholder="Masukkan Nama Jadwal">
					</div>
					<div class="form-group">
						<label for="jadwal_tanggal">Jadwal Pelaksanaan</label>
						<input type="text" id="jadwal_tanggal" name="datetimes" class="form-control">
					</div>
					<div class="form-group">
						<label for="jenis_jadwal">Pilih Jenis Jadwal</label>
						<select class="form-control" id="jenis_jadwal">
							<option value="usulan" selected>Usulan</option>
							<option value="penetapan">Penetapan</option>
						</select>
						<small class="text-muted">Untuk mengisi nilai penetapan, ubah jenis jadwal dari <strong>Usulan</strong> ke <strong>Penetapan</strong></small>
					</div>
					<div class="form-group template_desain">
						<label for="template_desain">Pilih template Desain LKE</label>
						<select class="form-control" id="template_desain">
							<option value="" selected>Default</option>
							<?php echo $option_jadwal; ?>
						</select>
						<small class="text-muted">Default atau template desain LKE dalam jadwal yang pernah dibuat sebelumnya.</small>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="1" id="tampilNilaiPenetapan">
						<label class="form-check-label" for="tampilNilaiPenetapan">
							Tampilkan Nilai Penetapan
						</label>
					</div>
					<small class="text-muted">Hapus ceklis untuk menyembunyikan nilai penetapan ke user SKPD.</small>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary submitBtn" onclick="submitTambahJadwalForm()">Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="report">
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
	jQuery(document).ready(function() {
		globalThis.tahun_anggaran = "<?php echo $tahun_anggaran; ?>"
		globalThis.tipe = 'LKE'
		get_data_penjadwalan_lke();

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

	function get_data_penjadwalan_lke() {
		jQuery("#wrap-loading").show();
		globalThis.penjadwalanTable = jQuery('#data_penjadwalan_table').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: esakip.url,
				type: "post",
				data: {
					'action': "get_data_penjadwalan_lke",
					'api_key': esakip.api_key,
					'tipe': tipe,
					'tahun_anggaran': tahun_anggaran
				}
			},
			"initComplete": function(settings, json) {
				jQuery("#wrap-loading").hide();
				if (json.checkOpenedSchedule != 'undefined' && json.checkOpenedSchedule > 0) {
					jQuery(".tambah_jadwal").prop('hidden', true);
				} else {
					jQuery(".tambah_jadwal").prop('hidden', false);
				}
			},
			"columns": [{
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

	function get_jadwal_lke(id) {
		return new Promise(function(resolve, reject) {
			jQuery("#wrap-loading").show()
			jQuery.ajax({
				url: esakip.url,
				type: "post",
				data: {
					'action': "get_data_jadwal_lke",
					'api_key': esakip.api_key,
					'id': id
				},
				dataType: "json",
				success: function(response) {
					jQuery("#wrap-loading").hide()
					return resolve(response);
				}
			})
		})
	}

	function tambah_jadwal() {
		jQuery("#modalTambahJadwal .modal-title").html("Tambah Penjadwalan");
		jQuery('#nama_jadwal').val('').prop('disabled', false);
		jQuery('#jadwal_tanggal').val('')
		jQuery('#jenis_jadwal').val('usulan')
		jQuery('.template_desain').show()
		jQuery('#template_desain').val('')
		jQuery("#modalTambahJadwal .submitBtn")
			.attr("onclick", 'submitTambahJadwalForm()')
			.attr("disabled", false)
			.text("Simpan");
		jQuery('#modalTambahJadwal').modal('show');
	}

	function submitTambahJadwalForm() {
		let this_tahun_anggaran = tahun_anggaran;

		let nama_jadwal = jQuery('#nama_jadwal').val()

		let template_desain = jQuery('#template_desain').val()
		let tampilNilaiPenetapan = jQuery('input[id="tampilNilaiPenetapan"]:checked').val();
		if (tampilNilaiPenetapan != 1) {
			tampilNilaiPenetapan = 0
		}
		let jadwalMulai = jQuery("#jadwal_tanggal").data('daterangepicker').startDate.format('YYYY-MM-DD HH:mm:ss')
		let jadwalSelesai = jQuery("#jadwal_tanggal").data('daterangepicker').endDate.format('YYYY-MM-DD HH:mm:ss')
		let jenis_jadwal = 1;
		jenis_jadwal = jQuery("#jenis_jadwal").val();
		if (nama_jadwal.trim() == '' || jadwalMulai == '' || jadwalSelesai == '' || jenis_jadwal == '') {
			alert("Ada yang kosong, Harap diisi semua")
			return false
		} else {
			jQuery("#wrap-loading").show()
			jQuery.ajax({
				url: esakip.url,
				type: 'post',
				dataType: 'json',
				data: {
					'action': 'submit_jadwal_lke',
					'api_key': esakip.api_key,
					'nama_jadwal': nama_jadwal,
					'jadwal_mulai': jadwalMulai,
					'jadwal_selesai': jadwalSelesai,
					'jenis_jadwal': jenis_jadwal,
					'tipe': tipe,
					'template_desain': template_desain,
					'tampil_nilai_penetapan': tampilNilaiPenetapan,
					'tahun_anggaran': this_tahun_anggaran
				},
				beforeSend: function() {
					jQuery('.submitBtn').attr('disabled', 'disabled')
				},
				success: function(response) {
					jQuery('#modalTambahJadwal').modal('hide')
					jQuery('#wrap-loading').hide()
					if (response.status == 'success') {
						alert('Data berhasil ditambahkan')
						penjadwalanTable.ajax.reload()
						jQuery(".tambah_jadwal").prop('hidden', true);
					} else {
						alert(response.message)
					}
				}
			})
		}
		jQuery('#modalTambahJadwal').modal('hide');
	}

	function edit_data_penjadwalan(id) {
		jQuery("#modalTambahJadwal .modal-title").html("Edit Penjadwalan");
		jQuery("#modalTambahJadwal .submitBtn")
			.attr("onclick", 'submitEditJadwalForm(' + id + ')')
			.attr("disabled", false)
			.text("Simpan");
		jQuery("#wrap-loading").show()
		jQuery.ajax({
			url: esakip.url,
			type: "post",
			data: {
				'action': "get_data_jadwal_lke",
				'api_key': esakip.api_key,
				'id': id
			},
			dataType: "json",
			success: function(response) {
				jQuery("#wrap-loading").hide()
				jQuery('input[type=checkbox]').prop('checked', false);

				jQuery("#nama_jadwal").val(response.data.nama_jadwal);
				jQuery("#jenis_jadwal").val(response.data.jenis_jadwal).change();

				jQuery('#jadwal_tanggal').data('daterangepicker').setStartDate(moment(response.data.started_at).format('DD-MM-YYYY HH:mm'));
				jQuery('#jadwal_tanggal').data('daterangepicker').setEndDate(moment(response.data.end_at).format('DD-MM-YYYY HH:mm'));

				if (response.data.tampil_nilai_penetapan == 1) {
					jQuery('#tampilNilaiPenetapan').prop('checked', true)
				} else {
					jQuery('#tampilNilaiPenetapan').prop('checked', false)
				}

				jQuery('.template_desain').hide()
				jQuery('#modalTambahJadwal').modal('show');
			}
		})
	}

	function submitEditJadwalForm(id) {
		jQuery("#wrap-loading").show()
		let this_tahun_anggaran = tahun_anggaran;
		let nama_jadwal = jQuery('#nama_jadwal').val()
		let jadwalMulai = jQuery("#jadwal_tanggal").data('daterangepicker').startDate.format('YYYY-MM-DD HH:mm:ss')
		let jadwalSelesai = jQuery("#jadwal_tanggal").data('daterangepicker').endDate.format('YYYY-MM-DD HH:mm:ss')
		let tampilNilaiPenetapan = jQuery('input[id="tampilNilaiPenetapan"]:checked').val();
		if (tampilNilaiPenetapan != 1) {
			tampilNilaiPenetapan = 0
		}
		let jenis_jadwal = jQuery("#jenis_jadwal").val();
		if (nama_jadwal.trim() == '' || jadwalMulai == '' || jadwalSelesai == '' || jenis_jadwal == '') {
			jQuery("#wrap-loading").hide()
			alert("Ada yang kosong, Harap diisi semua")
			return false
		} else {
			jQuery.ajax({
				url: esakip.url,
				type: 'post',
				dataType: 'json',
				data: {
					'action': 'submit_edit_jadwal_lke',
					'api_key': esakip.api_key,
					'nama_jadwal': nama_jadwal,
					'jadwal_mulai': jadwalMulai,
					'jadwal_selesai': jadwalSelesai,
					'jenis_jadwal': jenis_jadwal,
					'tampil_nilai_penetapan': tampilNilaiPenetapan,
					'id': id,
					'tipe': tipe,
					'tahun_anggaran': this_tahun_anggaran
				},
				beforeSend: function() {
					jQuery('.submitBtn').attr('disabled', 'disabled')
				},
				success: function(response) {
					jQuery('#modalTambahJadwal').modal('hide')
					jQuery('#wrap-loading').hide()
					if (response.status == 'success') {
						alert('Data berhasil diperbarui')
						penjadwalanTable.ajax.reload()
					} else {
						alert(`GAGAL! \n${response.message}`)
					}
				}
			})
		}
		jQuery('#modalTambahJadwal').modal('hide');
	}

	function hapus_data_penjadwalan(id) {
		let confirmDelete = confirm("Apakah anda yakin akan menghapus penjadwalan?");
		if (confirmDelete) {
			jQuery('#wrap-loading').show();
			jQuery.ajax({
				url: esakip.url,
				type: 'post',
				data: {
					'action': 'delete_jadwal_lke',
					'api_key': esakip.api_key,
					'id': id
				},
				dataType: 'json',
				success: function(response) {
					jQuery('#wrap-loading').hide();
					if (response.status == 'success') {
						alert('Data berhasil dihapus!.');
						penjadwalanTable.ajax.reload();
						jQuery(".tambah_jadwal").prop('hidden', false);
					} else {
						alert(`GAGAL! \n${response.message}`);
					}
				}
			});
		}
	}

	function lock_data_penjadwalan(id) {
		let confirmLocked = confirm("Apakah anda yakin akan mengunci penjadwalan?");
		if (confirmLocked) {
			jQuery('#wrap-loading').show();
			jQuery.ajax({
				url: esakip.url,
				type: 'post',
				data: {
					'action': 'lock_jadwal_lke',
					'api_key': esakip.api_key,
					'id': id
				},
				dataType: 'json',
				success: function(response) {
					jQuery('#wrap-loading').hide();
					if (response.status == 'success') {
						alert('Data berhasil dikunci!.');
						penjadwalanTable.ajax.reload();
						jQuery(".tambah_jadwal").prop('hidden', false);
					} else {
						alert(`GAGAL! \n${response.message}`);
					}
				}
			});
		}
	}

	function cannot_change_schedule(jenis) {
		if (jenis == 'kunci') {
			alert('Tidak bisa kunci karena penjadwalan sudah dikunci');
		} else if (jenis == 'edit') {
			alert('Tidak bisa edit karena penjadwalan sudah dikunci');
		} else if (jenis == 'hapus') {
			alert('Tidak bisa hapus karena penjadwalan sudah dikunci');
		}
	}

	function afterSubmitForm() {
		jQuery("#nama").val("")
		jQuery("#tahun_anggaran").val("")
		jQuery("#jadwal_tanggal").val("")
	}

	function set_desain_lke(url) {
		window.open(url, '_blank');
	}


	// function report(id) {
	// 	let modal = `
	// 		<div class="modal fade" id="modal-report" tab-index="-1" role="dialog" aria-hidden="true">
	// 		  <div class="modal-dialog modal-lg" role="document" style="min-width:1450px">
	// 		    <div class="modal-content">
	// 		      <div class="modal-header">
	// 		        <h5 class="modal-title">Laporan</h5>
	// 		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	// 		          <span aria-hidden="true">&times;</span>
	// 		        </button>
	// 		      </div>
			      
	// 		      <div class="modal-body">
	// 			    <div class="container-fluid">
	// 				    <div class="row">
	// 					    <div class="col-md-2">Unit Kerja</div>
	// 					    <div class="col-md-6">
	// 					    	<select class="form-control list_perangkat_daerah" id="list_perangkat_daerah"></select>
	// 					    </div>
	// 				    </div>
	// 				    <br/>
	// 				    <div class="row">
	// 				    	<div class="col-md-2">Jenis Laporan</div>
	// 				    	<div class="col-md-6">
	// 				      		<select class="form-control jenis" id="jenis">
	// 				      			<option value="-">Pilih Jenis</option>
	// 								<option value="halaman_cek_dokumen">Monitor Upload Dokumen</option>
	// 			      			</select>
	// 				    	</div>
	// 				    </div>
	// 				    <br/>
	// 				    <div class="row">
	// 				    	<div class="col-md-2"></div>
	// 				    	<div class="col-md-6 action-footer">
	// 				      		<button type="button" class="btn btn-success btn-preview" onclick="cek('${id}')">Proses</button>
	// 				    	</div>
	// 				    </div>
	// 				</div>
	// 		      </div>
	// 		      <div class="modal-cek" style="padding:10px"></div>
	// 		    </div>
	// 		  </div>
	// 		</div>`;
	// 	jQuery("body .report").html(modal);
	// 	get_jadwal_lke(id)
	// 		.then(function(response) {
	// 			list_perangkat_daerah()
	// 				.then(function() {
	// 					jQuery("#modal-report").modal('show');
	// 					jQuery('.jenis').select2({
	// 						width: '100%'
	// 					});
	// 				})
	// 		});
	// }

	function list_perangkat_daerah() {
		return new Promise(function(resolve, reject) {
			if (typeof list_perangkat_daerah_global == 'undefined') {
				jQuery('#wrap-loading').show();
				jQuery.ajax({
					url: esakip.url,
					type: 'post',
					dataType: 'json',
					data: {
						action: 'list_perangkat_daerah_lke',
						tahun_anggaran: tahun_anggaran,
						'api_key': esakip.api_key,
					},
					success: function(response) {
						jQuery('#wrap-loading').hide();
						if (response.status) {
							list_perangkat_daerah_global = response.list_skpd_options;
							jQuery("#list_perangkat_daerah").html(list_perangkat_daerah_global);
							jQuery('#list_perangkat_daerah').select2({
								width: '100%'
							});
							return resolve();
						}

						alert('Oops ada kesalahan load data Unit kerja');
						return resolve();
					}
				});
			} else {
				jQuery("#list_perangkat_daerah").html(list_perangkat_daerah_global);
				jQuery('#list_perangkat_daerah').select2({
					width: '100%'
				});
				return resolve();
			}
		})
	}

	function cek(id) {
		let jenis = jQuery("#jenis").val();
		let id_unit = jQuery("#list_perangkat_daerah").val();

		if (id_unit == '' || id_unit == 'undefined') {
			alert('Unit kerja belum dipilih');
			return;
		}
		switch (jenis) {
			case 'halaman_cek_dokumen':
				window.open('<?php echo $url_cek_dokumen; ?>', '_blank');
				break;

			case '-':
				alert('Jenis laporan belum dipilih');
				break;
		}
	}
</script>