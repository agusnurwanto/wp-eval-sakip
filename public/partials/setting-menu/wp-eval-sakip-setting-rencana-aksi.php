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
<div class="cetak">
	<div style="padding: 10px;margin:0 0 3rem 0;">
	<input type="hidden" value="<?php echo get_option( '_crb_apikey_esakip' ); ?>" id="api_key">
	<h1 class="text-center" style="margin:3rem;">Halaman Pengaturan Rencana Aksi Pemerintah Daerah<br>Tahun Anggaran <?php echo $tahun_anggaran; ?></h1>
		<div class="d-flex justify-content-center">
			<div class="card" style="width: 50%;">
				  <div class="card-body">
					<form>
						<div class="form-group">
							<label for="jadwal-renstra">Pilih Jadwal RPJMD/RPD SI KSATRIA</label>
							<select class="form-control" id="jadwal-rpjmd">
							</select>
							<small class="form-text text-muted">Untuk mendapatkan Pohon Kinerja sesuai jadwal RPJMD/RPD. digunakan diinput Rencana Aksi Pemerintah Daerah.</small>
						</div>
						<div class="form-group d-flex">
							<button onclick="submit_pengaturan_menu_pemda(); return false;" class="btn btn-primary ml-auto">Simpan</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="cetak">
	<div style="padding: 10px;margin:0 0 3rem 0;">
	<input type="hidden" value="<?php echo get_option( '_crb_apikey_esakip' ); ?>" id="api_key">
	<h1 class="text-center" style="margin:3rem;">Halaman Pengaturan Rencana Aksi Perangkat Daerah<br>Tahun Anggaran <?php echo $tahun_anggaran; ?></h1>
		<div class="d-flex justify-content-center">
			<div class="card" style="width: 50%;">
				  <div class="card-body">
					<form>
						<div class="form-group">
							<label for="jadwal-renstra">Pilih Jadwal RENSTRA SI KSATRIA</label>
							<select class="form-control" id="jadwal-renstra">
							</select>
							<small class="form-text text-muted">Untuk mendapatkan Pohon Kinerja sesuai jadwal RENSTRA. digunakan diinput Rencana Aksi Perangkat Daerah.</small>
						</div>
						<div class="form-group">
							<label for="jadwal-renstra">Pilih Jadwal RENSTRA WP-SIPD</label>
							<select class="form-control" id="jadwal-renstra-wpsipd">
							</select>
							<small class="form-text text-muted">Untuk mendapatkan Sasaran Cascading di WP-SIPD sesuai jadwal yang digunakan di input Rencana Aksi Perangkat Daerah.</small>
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
	jQuery(document).ready(function(){
		globalThis.tahun_anggaran = "<?php echo $tahun_anggaran; ?>"

		get_data_pengaturan_rencana_aksi();
	});

	/** get data pengaturan rencana aksi*/
	function get_data_pengaturan_rencana_aksi(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data:{
				'action' 		: "get_data_pengaturan_rencana_aksi",
				'api_key' 		: esakip.api_key,
				'tahun_anggaran': tahun_anggaran
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('#jadwal-renstra').html(response.option_renstra)
					jQuery('#jadwal-renstra-wpsipd').html(response.option_renstra_wpsipd)
					jQuery('#jadwal-rpjmd').html(response.option_rpjmd)
					if(response.data.length != 0){
						console.log(response.data.id_jadwal)
						jQuery('#jadwal-renstra').val(response.data.id_jadwal);
						jQuery('#jadwal-rpjmd').val(response.data.id_jadwal);
						if(response.data.id_jadwal_wp_sipd !== null){
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

    function submit_pengaturan_menu(){
		let id_jadwal_renstra = jQuery("#jadwal-renstra").val();
		let id_jadwal_renstra_wpsipd = jQuery("#jadwal-renstra-wpsipd").val();
        if (id_jadwal_renstra == '' || id_jadwal_renstra_wpsipd == '') {
            return alert('Ada data yang kosong!');
        }

		if(confirm("Apakah kamu yakin untuk mengubah data pokin di Rencana Aksi?\n\nPokin di Rencana Aksi yang sudah ada juga harus diperbarui!")){
			jQuery('#wrap-loading').show();
			jQuery.ajax({
				method: 'POST',
				url: esakip.url,
				dataType: 'json',
				data:{
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

    function submit_pengaturan_menu_pemda(){
		let id_jadwal_rpjmd = jQuery("#jadwal-rpjmd").val();
        if (id_jadwal_rpjmd == '') {
            return alert('Ada data yang kosong!');
        }

		if(confirm("Apakah kamu yakin untuk mengubah data pokin di Rencana Aksi?\n\nPokin di Rencana Aksi yang sudah ada juga harus diperbarui!")){
			jQuery('#wrap-loading').show();
			jQuery.ajax({
				method: 'POST',
				url: esakip.url,
				dataType: 'json',
				data:{
					'action': 'submit_pengaturan_rencana_aksi_pemda',
					'api_key': esakip.api_key,
					'id_jadwal_rpjmd': id_jadwal_rpjmd,
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

	function afterSubmitForm(){
		jQuery("#keterangan").val("")
	}

</script> 
