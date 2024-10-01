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
	<h1 class="text-center" style="margin:3rem;">Halaman Pengaturan Menu Upload Dokumen <br>Pemerintah Daerah<br>Tahun <?php echo $input['tahun_anggaran']; ?></h1>
		<table id="data_pengaturan_menu_pemda_table" cellpadding="2" cellspacing="0">
			<thead id="data_header">
				<tr>
					<th class="text-center">No</th>
					<th class="text-center">Nama Tabel Database</th>
					<th class="text-center">Nama Dokumen</th>
					<th class="text-center">Akses User</th>
					<th class="text-center">Status</th>
					<th class="text-center">Keterangan</th>
					<th class="text-center" style="width: 150px;">Aksi</th>
				</tr>
			</thead>
			<tbody id="data_body">
			</tbody>
		</table>

	<h1 class="text-center" style="margin:10rem 0 0;">Halaman Pengaturan Menu Upload Dokumen <br>Perangkat Daerah<br>Tahun <?php echo $input['tahun_anggaran']; ?></h1>
		<table id="data_pengaturan_menu_perangkat_daerah_table" cellpadding="2" cellspacing="0">
			<thead id="data_header">
				<tr>
					<th class="text-center">No</th>
					<th class="text-center">Nama Tabel Database</th>
					<th class="text-center">Nama Dokumen</th>
					<th class="text-center">Akses User</th>
					<th class="text-center">Verifikasi Upload Dokumen</th>
					<th class="text-center">Status</th>
					<th class="text-center">Keterangan</th>
					<th class="text-center" style="width: 150px;">Aksi</th>
				</tr>
			</thead>
			<tbody id="data_body">
			</tbody>
		</table>

	<h1 class="text-center" style="margin:10rem 0 0;">Halaman Pengaturan Menu Khusus<br>Tahun <?php echo $input['tahun_anggaran']; ?></h1>
		<table id="data_pengaturan_menu_khusus_table" cellpadding="2" cellspacing="0">
			<thead id="data_header">
				<tr>
					<th class="text-center" style="width: 4rem;">No</th>
					<th class="text-center" style="width: 30rem;">Penempatan Menu</th>
					<th class="text-center">Set Html</th>
					<th class="text-center" style="width: 150px;">Aksi</th>
				</tr>
			</thead>
			<tbody id="data_body">
			</tbody>
		</table>
	</div>
</div>

<!-- Modal Edit Pengaturan Menu -->
<div class="modal fade" id="editPengaturanMenu" tabindex="-1" role="dialog" aria-labelledby="editPengaturanMenuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPengaturanMenuLabel">Edit Pengaturan Menu Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" value="<?php echo $input['tahun_anggaran']; ?>" id="tahunAnggaran">
                    <input type="hidden" value="" id="idDokumen">
					<input type="hidden" value="" id="tipeDokumen">
					<div class="form-group">
						<label class="d-block">Pengaturan Menu Upload Dokumen</label>
						<tr>
							<td>
								<div class="custom-control custom-radio custom-control-inline">
									<input class="custom-control-input" type="radio" name="menu_dokumen" id="menu_dokumen_tampil" value="tampil">
									<label class="custom-control-label" for="menu_dokumen_tampil">Tampilkan</label>
								</div>
							</td>
							<td>
								<div class="custom-control custom-radio custom-control-inline">
									<input class="custom-control-input" type="radio" name="menu_dokumen" id="menu_dokumen_sembunyi" value="sembunyi">
									<label class="custom-control-label" for="menu_dokumen_sembunyi">Sembunyikan</label>
								</div>
							</td>
						</tr>
						<small class="d-block form-text text-muted">Setting Menu Upload Dokumen Yang Tampil Di Dashboard User</small>
					</div>
					<div class="form-group" id="setting_hak_akses_user" style="margin: 1.5em 0 0;">
						<label class="d-block">Pengaturan Akses User Upload Dokumen</label>
						<tr>
							<td>
								<div class="custom-control custom-radio custom-control-inline">
									<input class="custom-control-input" type="radio" name="akses_user_upload_dokumen" id="akses_user_upload_dokumen_pemda" value="pemda">
									<label class="custom-control-label" for="akses_user_upload_dokumen_pemda">Pemerintah Daerah</label>
								</div>
							</td>
							<td>
								<div class="custom-control custom-radio custom-control-inline">
									<input class="custom-control-input" type="radio" name="akses_user_upload_dokumen" id="akses_user_upload_dokumen_pd" value="pd">
									<label class="custom-control-label" for="akses_user_upload_dokumen_pd">Peragkat Daerah</label>
								</div>
							</td>
							<td>
								<div class="custom-control custom-radio custom-control-inline">
									<input class="custom-control-input" type="radio" name="akses_user_upload_dokumen" id="verifikasi_upload_dokumen_semua" value="semua">
									<label class="custom-control-label" for="verifikasi_upload_dokumen_semua">Pemerintah Daerah dan Perangkat Daerah</label>
								</div>
							</td>
						</tr>
						<small class="d-block form-text text-muted">Setting User Yang Bisa Mengakses Upload Dokumen</small>
					</div>
                    <div class="form-group" style="margin: 1.5em 0 0;">
                        <label for="no_urut">No Urut</label>
                        <input type="text" name="no_urut_menu" id="no_urut">
                    </div>
					<div class="form-group" style="margin: 1.5em 0 0;">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
					<div class="form-group" style="margin: 1.5rem 0 0;" id="keterangan_hak_akses_user">
						<small class="d-block form-text text-muted">Setting Hak Akses Upload Dokumen "RENSTRA" dan "Pohon Kinerja dan Cascading" berada di setting penjadwalan RPJMD.</small>
					</div>
                    <button type="submit" class="btn btn-primary" style="margin: 1.5em 0 0;" onclick="submit_pengaturan_menu(this); return false">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> -->
<script>
	jQuery(document).ready(function(){

		globalThis.thisAjaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>"
		globalThis.tahun_anggaran = "<?php echo $tahun_anggaran; ?>"

		get_data_pengaturan_menu_pemda();
		get_data_pengaturan_menu_perangkat_daerah();
		get_data_pengaturan_menu_khusus();
		jQuery('#keterangan_hak_akses_user').hide();
	});

	/** get data pengaturan menu pemda*/
	function get_data_pengaturan_menu_pemda(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data:{
				'action' 		: "get_data_pengaturan_menu",
				'api_key' 		: esakip.api_key,
				'tahun_anggaran': tahun_anggaran,
				'tipe'			: 'pemerintah_daerah'
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('#data_pengaturan_menu_pemda_table tbody').html(response.data);
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
	
	/** get data pengaturan menu perangkat daerah */
	function get_data_pengaturan_menu_perangkat_daerah(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data:{
				'action' 		: "get_data_pengaturan_menu",
				'api_key' 		: esakip.api_key,
				'tahun_anggaran': tahun_anggaran,
				'tipe'			: 'perangkat_daerah'
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('#data_pengaturan_menu_perangkat_daerah_table tbody').html(response.data);
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

	/** get data pengaturan menu khusus */
	function get_data_pengaturan_menu_khusus(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data:{
				'action' 		: "get_data_pengaturan_menu_khusus",
				'api_key' 		: esakip.api_key,
				'tahun_anggaran': tahun_anggaran
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('#data_pengaturan_menu_khusus_table tbody').html(response.data);
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

	function simpan_menu_khusus(tipe) {
		let set_html = jQuery('#set_html_menu_khusus_'+tipe).val();

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                'action': 'simpan_perubahan_menu_khusus',
                'api_key': esakip.api_key,
                'tahun_anggaran': tahun_anggaran,
				'set_html': set_html,
				'tipe': tipe
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    get_data_pengaturan_menu_khusus();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengirim data!');
            }
        });
    }

    function edit_pengaturan_menu(id,tipe){
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_pengaturan_menu_by_id',
                api_key: esakip.api_key,
                id: id,
                tipe: tipe
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    let data = response.data;
                    if(data.length !== 0 || data.active != null){
                        let pengaturan_menu = (data.active == 1) ? "tampil" : "sembunyi";
                        jQuery("input[name=menu_dokumen][value='"+pengaturan_menu+"']").prop("checked",true);
                        jQuery("#keterangan").val(data.keterangan);
						let akses_user = '';
						if(data.jenis_role == 1){
							akses_user = 'pemda';
						}else if(data.jenis_role == 2){
							akses_user = 'pd';
						}else if(data.jenis_role == 3){
							akses_user = 'semua';
						}
                        jQuery("input[name=akses_user_upload_dokumen][value='"+akses_user+"']").prop("checked",true);
						jQuery("#no_urut").val(data.default_urutan);
						if(data.nama_tabel == 'esakip_renstra' || data.nama_tabel == 'esakip_pohon_kinerja_dan_cascading' || data.nama_tabel == 'esakip_pohon_kinerja' || data.nama_tabel == 'esakip_pohon_kinerja_opd'){
							jQuery('#setting_hak_akses_user').hide();
							jQuery('#keterangan_hak_akses_user').show();
						}else{
							jQuery('#setting_hak_akses_user').show();
							jQuery('#keterangan_hak_akses_user').hide();
						}
					}
                    jQuery("#idDokumen").val(id);
					jQuery("#tipeDokumen").val(tipe);
                    jQuery("#editPengaturanMenu").modal('show');
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data!');
            }
        });
    }

    function submit_pengaturan_menu(that){
        let id_dokumen = jQuery("#idDokumen").val();
        if (id_dokumen == '') {
            return alert('Id Dokumen kosong, harap refresh halaman!');
        }

		let tipe_dokumen = jQuery("#tipeDokumen").val();
        if (tipe_dokumen == '') {
            return alert('Tipe Dokumen kosong, harap refresh halaman!');
        }

		let keterangan = jQuery("#keterangan").val();

        let tahun_anggaran = jQuery("#tahunAnggaran").val();
        if (tahun_anggaran == '') {
            return alert('Tahun Anggaran tidak boleh kosong!');
        }
        let menu_dokumen = jQuery("input[name='menu_dokumen']:checked").val();

        if (menu_dokumen == '' || menu_dokumen == undefined) {
            return alert('Pengaturan menu tidak boleh kosong!');
        }

		let akses_user = jQuery("input[name='akses_user_upload_dokumen']:checked").val();

        // if (akses_user == '' || akses_user == undefined) {
        //     return alert('Pengaturan akses user tidak boleh kosong!');
        // }
		
		let nomor_urutan = jQuery("#no_urut").val();;
        if (nomor_urutan == '') {
            return alert('Nomor Urut tidak boleh kosong!');
        }

        let form_data = new FormData();
        form_data.append('action', 'submit_edit_pengaturan_menu_dokumen');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id_dokumen', id_dokumen);
        form_data.append('keterangan', keterangan);
        form_data.append('tahun_anggaran', tahun_anggaran);
        form_data.append('menu_dokumen', menu_dokumen);
        form_data.append('tipe_dokumen', tipe_dokumen);
		form_data.append('akses_user', akses_user);
		form_data.append('nomor_urutan', nomor_urutan);

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            contentType: false,
            processData: false,
            data: form_data,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#editPengaturanMenu').modal('hide');
                    alert(response.message);
					if(tipe_dokumen == 'pemerintah_daerah'){
						get_data_pengaturan_menu_pemda();
					}else{
						get_data_pengaturan_menu_perangkat_daerah()
					}
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

	function afterSubmitForm(){
		jQuery("#keterangan").val("")
	}

</script> 
