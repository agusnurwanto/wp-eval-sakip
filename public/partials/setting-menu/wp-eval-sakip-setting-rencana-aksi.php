<?php
// If this file is called directly, abort.
if (! defined('WPINC')) {
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
<div class="cetak">
	<div style="padding: 10px;margin:0 0 3rem 0;">
		<input type="hidden" value="<?php echo get_option('_crb_apikey_esakip'); ?>" id="api_key">
		<h1 class="text-center" style="margin:3rem;">Halaman Pengaturan Rencana Hasil Kerja<br>Tahun Anggaran <?php echo $tahun_anggaran; ?></h1>
		<div class="d-flex justify-content-center">
			<div class="card" style="width: 50%;">
				<div class="card-body">
					<form>
						<div class="form-group">
							<label for="jadwal-rpjmd">Pilih Jadwal RPJMD/RPD</label>
							<select class="form-control" id="jadwal-rpjmd" disabled>
							</select>
							<small class="form-text text-muted">Silahkan setting di halaman monitor upload dokumen untuk mendapatkan periode jadwal Pohon Kinerja yang nantinya akan digunakan menginput Rencana Hasil Kerja.</small>
						</div>
						<div class="form-group">
							<label for="jadwal-renstra">Pilih Jadwal RENSTRA WP-SIPD</label>
							<select class="form-control" id="jadwal-renstra-wpsipd">
							</select>
							<small class="form-text text-muted">Untuk mendapatkan Sasaran Cascading di WP-SIPD sesuai jadwal yang digunakan di input Rencana Hasil Kerja.</small>
						</div>
						<div class="form-group d-flex">
							<button onclick="submit_pengaturan_menu(); return false;" class="btn btn-primary ml-auto">Simpan</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function() {
		globalThis.tahun_anggaran = "<?php echo $tahun_anggaran; ?>"

		get_data_upload_dokumen();
		get_data_pengaturan_rencana_aksi();
	});

	/** get data pengaturan */
	function get_data_upload_dokumen() {
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				'action': "get_data_upload_dokumen",
				'api_key': esakip.api_key,
				'tahun_anggaran': tahun_anggaran
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				if (response.status === 'success') {
					jQuery('#jadwal-rpjmd').html(response.option_rpjmd)
					if (response.data.length != 0) {
						jQuery('#jadwal-rpjmd').val(response.data.id_jadwal_rpjmd);
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

	/** get data pengaturan */
	function get_data_pengaturan_rencana_aksi() {
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				'action': "get_data_pengaturan_rencana_aksi",
				'api_key': esakip.api_key,
				'tahun_anggaran': tahun_anggaran
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('#jadwal-renstra-wpsipd').html(response.option_renstra_wpsipd)
					if (response.data.length != 0) {
						console.log(response.data.id_jadwal)
						if (response.data.id_jadwal_wp_sipd !== null) {
							jQuery('#jadwal-renstra-wpsipd').val(response.data.id_jadwal_wp_sipd);
						}
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

	function submit_pengaturan_menu() {
		let id_jadwal_renstra = jQuery("#jadwal-renstra").val();
		let id_jadwal_renstra_wpsipd = jQuery("#jadwal-renstra-wpsipd").val();
		if (id_jadwal_renstra == '' || id_jadwal_renstra_wpsipd == '') {
			return alert('Ada data yang kosong!');
		}

		if (confirm("Apakah kamu yakin untuk mengubah data pokin di Rencana Hasil Kerja?\n\nPokin di Rencana Hasil Kerja yang sudah ada juga harus diperbarui!")) {
			jQuery('#wrap-loading').show();
			jQuery.ajax({
				method: 'POST',
				url: esakip.url,
				dataType: 'json',
				data: {
					'action': 'submit_pengaturan_rencana_aksi',
					'api_key': esakip.api_key,
					'id_jadwal_renstra': id_jadwal_renstra,
					'id_jadwal_renstra_wpsipd': id_jadwal_renstra_wpsipd,
					'tahun_anggaran': tahun_anggaran
				},
				success: function(response) {
					console.log(response);
					jQuery('#wrap-loading').hide();
					if (response.status === 'success') {
						alert(response.message);
						get_data_pengaturan_rencana_aksi();
						afterSubmitForm();
					} else {
						alert(response.message);
					}
				},
				error: function(xhr, status, error) {
					console.error(xhr.responseText);
					alert('Terjadi kesalahan saat mengirim data!');
					jQuery('#wrap-loading').hide();
				}
			});
		}
	}

	function afterSubmitForm() {
		jQuery("#keterangan").val("")
	}
</script>