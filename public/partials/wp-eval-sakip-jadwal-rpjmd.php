<?php
global $wpdb;
// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

$select_rpjpd = '';
$data_rpjpd = $wpdb->get_results(
	"
		SELECT
			id,
			nama_jadwal
		FROM
			esakip_data_jadwal
		WHERE
			tipe='RPJPD'
			and status!=0",
	ARRAY_A
);

if (!empty($data_rpjpd)) {
	foreach ($data_rpjpd as $val_rpjpd) {
		$select_rpjpd .= '<option value="' . $val_rpjpd['id'] . '">' . $val_rpjpd['nama_jadwal'] . '</option>';
	}
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
		<input type="hidden" value="<?php echo get_option('_crb_apikey_esakip'); ?>" id="api_key">
		<h1 class="text-center" style="margin:3rem;">Halaman Jadwal RPJMD / RPD</h1>
		<div style="margin-bottom: 25px;">
			<button class="btn btn-primary" onclick="tambah_jadwal_rpmd();">Tambah Jadwal</button>
		</div>
		<table id="data_penjadwalan_table" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
			<thead id="data_header">
				<tr>
					<th class="text-center">Nama Jadwal</th>
					<th class="text-center">Keterangan</th>
					<th class="text-center">Tahun Mulai</th>
					<th class="text-center">Tahun Akhir</th>
					<th class="text-center">Jenis Jadwal</th>
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
				<div class="form-group">
					<label for='nama_jadwal' style='display:inline-block'>Nama Jadwal</label>
					<input type='text' id='nama_jadwal' style='display:block;width:100%;' placeholder='Input Nama Jadwal'>
				</div>
				<div class="form-group">
					<label for='tahun_anggaran' style='display:inline-block'>Tahun Mulai Anggaran</label>
					<input type="number" id='tahun_anggaran' name="tahun_anggaran" style='display:block;width:100%;' placeholder="Tahun Mulai Anggaran" />
				</div>
				<div class="form-group">
					<label for='tahun_selesai_anggaran' style='display:inline-block'>Tahun Selesai Anggaran</label>
					<input type="number" id='tahun_selesai_anggaran' name="tahun_selesai_anggaran" style='display:block;width:100%;' placeholder="Tahun Selesai Anggaran" />
				</div>
				<div class="form-group">
					<label for='lama_pelaksanaan' style='display:block'>Lama Pelaksanaan</label>
					<input type="number" id='lama_pelaksanaan' name="lama_pelaksanaan" value="5" style='display:inline-block;width:50%;' placeholder="5" /> Tahun
				</div>
				<div class="form-group">
					<label for='keterangan' style='display:inline-block'>Keterangan</label>
					<input type='text' id='keterangan' style='display:block;width:100%;' placeholder='Input Keterangan'>
				</div>
				<div class="form-group">
					<label class="d-block">Jenis Jadwal</label>
					<tr>
						<td>
							<div class="custom-control custom-radio custom-control-inline">
								<input class="custom-control-input" type="radio" name="jenis_khusus_rpjmd" id="jenis_khusus_rpjmd_rpjmd" value="rpjmd">
								<label class="custom-control-label" for="jenis_khusus_rpjmd_rpjmd">RPJMD</label>
							</div>
						</td>
						<td>
							<div class="custom-control custom-radio custom-control-inline">
								<input class="custom-control-input" type="radio" name="jenis_khusus_rpjmd" id="jenis_khusus_rpjmd_rpd" value="rpd">
								<label class="custom-control-label" for="jenis_khusus_rpjmd_rpd">RPD</label>
							</div>
						</td>
					</tr>
					<small class="d-block form-text text-muted">Catatan: Jenis RPD tidak ada kolom visi dan misi. Jenis RPJMD ada kolom visi dan misi.</small>
				</div>
				<div>
					<label for="relasi_rpjpd" style='display:inline-block'>Pilih Jadwal RPJPD</label>
					<select id="relasi_rpjpd" style='display:block;width: 100%;'>
						<option value="">Pilih RPJPD</option>
						<?php echo $select_rpjpd; ?>
					</select>
				</div>
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
	jQuery(document).ready(function() {

		globalThis.thisAjaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>"

		globalThis.tipe = 'RPJMD'
		get_data_penjadwalan_rpjmd();

	});

	/** get data penjadwalan */
	function get_data_penjadwalan_rpjmd() {
		jQuery("#wrap-loading").show();
		globalThis.penjadwalanTable = jQuery('#data_penjadwalan_table').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: thisAjaxUrl,
				type: "post",
				data: {
					'action': "get_data_penjadwalan_rpjmd",
					'api_key': jQuery("#api_key").val(),
					'tipe': tipe
				}
			},
			"initComplete": function(settings, json) {
				jQuery("#wrap-loading").hide();
			},
			"columns": [{
					"data": "nama_jadwal",
					className: "text-center"
				},
				{
					"data": "keterangan",
					className: "text-center"
				},
				{
					"data": "tahun_anggaran",
					className: "text-center"
				},
				{
					"data": "tahun_anggaran_selesai",
					className: "text-center"
				},
				{
					"data": "jenis_jadwal_khusus",
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
	function tambah_jadwal_rpmd() {
		jQuery("#modalTambahJadwal .modal-title").html("Tambah Penjadwalan");
		jQuery('#lama_pelaksanaan').val(jQuery('#lama_pelaksanaan'));
		jQuery('#relasi_rpjpd').val('');
		jQuery('#nama_jadwal').val('').prop('disabled', false);
		jQuery('#keterangan').val('').prop('disabled', false);
		jQuery('#tahun_anggaran').val('').prop('disabled', false);
		jQuery("#modalTambahJadwal .submitBtn")
			.attr("onclick", 'submitTambahJadwalForm()')
			.attr("disabled", false)
			.text("Simpan");
		jQuery('#modalTambahJadwal').modal('show');
		jQuery.ajax({
			url: thisAjaxUrl,
			type: "post",
			data: {
				'action': "get_lama_pelaksanaan_rpjmd",
				'api_key': jQuery("#api_key").val(),
				'tipe': tipe
			},
			dataType: "json",
			success: function(response) {
				jQuery("#lama_pelaksanaan").val(response.data.lama_pelaksanaan);
			}
		})
	}

	/** Submit tambah jadwal */
	function submitTambahJadwalForm() {
		jQuery("#wrap-loading").show()
		let nama_jadwal = jQuery('#nama_jadwal').val()
		let keterangan = jQuery("#keterangan").val()
		let relasi_rpjpd = jQuery("#relasi_rpjpd").val()
		let tahun_anggaran = jQuery("#tahun_anggaran").val()
		let lama_pelaksanaan = jQuery("#lama_pelaksanaan").val()
		let tahun_selesai_anggaran = jQuery("#tahun_selesai_anggaran").val()
		let jenis_khusus_rpjmd = jQuery("input[name='jenis_khusus_rpjmd']:checked").val()
		if (nama_jadwal.trim() == '' || keterangan == '' || tahun_anggaran == '' || lama_pelaksanaan == '' || jenis_khusus_rpjmd == '') {
			jQuery("#wrap-loading").hide()
			alert("Ada yang kosong, Harap diisi semua")
			return false
		} else {
			jQuery.ajax({
				url: thisAjaxUrl,
				type: 'post',
				dataType: 'json',
				data: {
					'action': 'submit_jadwal_rpjmd',
					'api_key': jQuery("#api_key").val(),
					'nama_jadwal': nama_jadwal,
					'tahun_anggaran': tahun_anggaran,
					'relasi_rpjpd': relasi_rpjpd,
					'keterangan': keterangan,
					'tipe': tipe,
					'lama_pelaksanaan': lama_pelaksanaan,
					'jenis_khusus_rpjmd': jenis_khusus_rpjmd,
					'tahun_selesai_anggaran': tahun_selesai_anggaran
				},
				beforeSend: function() {
					jQuery('.submitBtn').attr('disabled', 'disabled')
				},
				success: function(response) {
					jQuery('#modalTambahJadwal').modal('hide')
					jQuery('#wrap-loading').hide()
					if (response.status == 'success') {
						alert('Jadwal berhasil ditambahkan')
						penjadwalanTable.ajax.reload()
						afterSubmitForm()
					} else {
						alert(response.message)
					}
				}
			})
		}
		jQuery('#modalTambahJadwal').modal('hide');
	}

	/** edit akun ssh usulan */
	function edit_data_penjadwalan(id) {
		jQuery('#modalTambahJadwal').modal('show');
		jQuery("#modalTambahJadwal .modal-title").html("Edit Penjadwalan");
		jQuery("#modalTambahJadwal .submitBtn")
			.attr("onclick", 'submitEditJadwalForm(' + id + ')')
			.attr("disabled", false)
			.text("Simpan");
		jQuery("#wrap-loading").show()
		jQuery("#wrap-loading").show()
		jQuery.ajax({
			url: thisAjaxUrl,
			type: "post",
			data: {
				'action': "get_data_jadwal_by_id_rpjmd",
				'api_key': jQuery("#api_key").val(),
				'id': id
			},
			dataType: "json",
			success: function(response) {
				jQuery("#wrap-loading").hide()
				jQuery("#relasi_rpjpd").val(response.data.relasi_perencanaan).change();
				jQuery("#nama_jadwal").val(response.data.nama_jadwal);
				jQuery("#keterangan").val(response.data.keterangan);
				jQuery("#tahun_anggaran").val(response.data.tahun_anggaran);
				jQuery("#tahun_selesai_anggaran").val(response.data.tahun_selesai_anggaran);
				let jenis_khusus_rpjmd = (response.data.jenis_jadwal_khusus == 'rpd') ? "rpd" : "rpjmd";
				jQuery("input[name=jenis_khusus_rpjmd][value='"+jenis_khusus_rpjmd+"']").prop("checked",true);
				jQuery("#lama_pelaksanaan").val(response.data.lama_pelaksanaan);
			}
		})
	}

	function submitEditJadwalForm(id) {
		jQuery("#wrap-loading").show()
		let nama_jadwal = jQuery('#nama_jadwal').val()
		let keterangan = jQuery("#keterangan").val()
		let tahun_anggaran = jQuery("#tahun_anggaran").val()
		let lama_pelaksanaan = jQuery("#lama_pelaksanaan").val()
		let tahun_selesai_anggaran = jQuery("#tahun_selesai_anggaran").val()
		let jenis_khusus_rpjmd = jQuery("input[name='jenis_khusus_rpjmd']:checked").val()
		if (nama_jadwal.trim() == '' || keterangan == '' || tahun_anggaran == '' || lama_pelaksanaan == '' || jenis_khusus_rpjmd == '') {
			jQuery("#wrap-loading").hide()
			alert("Ada yang kosong, Harap diisi semua")
			return false
		} else {
			jQuery.ajax({
				url: thisAjaxUrl,
				type: 'post',
				dataType: 'json',
				data: {
					'action': 'submit_edit_jadwal_rpjmd',
					'api_key': jQuery("#api_key").val(),
					'nama_jadwal': nama_jadwal,
					'id': id,
					'keterangan': keterangan,
					'tahun_anggaran': tahun_anggaran,
					'tipe': tipe,
					'lama_pelaksanaan': lama_pelaksanaan,
					'jenis_khusus_rpjmd': jenis_khusus_rpjmd,
					'tahun_selesai_anggaran': tahun_selesai_anggaran
				},
				beforeSend: function() {
					jQuery('.submitBtn').attr('disabled', 'disabled')
				},
				success: function(response) {
					jQuery('#modalTambahJadwal').modal('hide')
					jQuery('#wrap-loading').hide()
					if (response.status == 'success') {
						alert('Jadwal berhasil diperbarui')
						penjadwalanTable.ajax.reload()
						afterSubmitForm()
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
				url: thisAjaxUrl,
				type: 'post',
				data: {
					'action': 'delete_jadwal_rpjmd',
					'api_key': jQuery("#api_key").val(),
					'id': id
				},
				dataType: 'json',
				success: function(response) {
					jQuery('#wrap-loading').hide();
					if (response.status == 'success') {
						alert('Jadwal berhasil dihapus!.');
						penjadwalanTable.ajax.reload();
					} else {
						alert(`GAGAL! \n${response.message}`);
					}
				}
			});
		}
	}

	function afterSubmitForm() {
		jQuery("#nama_jadwal").val("")
		jQuery("#keterangan").val("")
		jQuery("#tahun_anggaran").val("")
		jQuery("#tipe").val("")
		jQuery("#lama_pelaksanaan").val("")
	}
</script>