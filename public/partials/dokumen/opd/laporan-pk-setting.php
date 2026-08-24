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
$upload_dir = ESAKIP_PLUGIN_URL . 'public/media/dokumen/';
$path_logo_pemda_laporan_pk = get_option('_logo_pemda_laporan_pk');
$user = wp_get_current_user();
$tahun_anggaran = get_option('_crb_tahun_wpsipd');
if (in_array('admin_panrb', $user->roles)) {
	$tahun_anggaran = isset($_GET['tahun']) ? sanitize_text_field($_GET['tahun']) : $input['tahun'];
}
$id = isset($_GET['id']) ? sanitize_text_field($_GET['id']) : '';
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
	.table_list_skpd thead{
		position: sticky;
        top: -6px;
	}.table_list_skpd thead th{
		vertical-align: middle;
	}
	.table_list_skpd tfoot{
        position: sticky;
        bottom: 0;
    }

	.table_list_skpd tfoot th{
		vertical-align: middle;
	}

	.input-logo-pemda{
		margin: 0 0 3rem 0;
		padding: 1rem;
	}

	.input-logo-pemda .input-file-logo {
		width: 20rem;
		margin: 0 auto;
	}
</style>
<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Profile Perangkat Daerah</h1>
			<div id="action" class="action-section hide-excel"></div>
			<!-- <div style="margin-bottom: 25px;" class="d-flex">
                <button class="btn btn-success mx-auto" onclick="tambah_logo_pemda();"><i class="dashicons dashicons-plus"></i> Tambah Logo Pemda</button>
            </div> -->
			<div class="wrap-table mt-2">
				<table id="cetak" title="Profile Perangkat Daerah" class="table table-bordered table_list_skpd" cellpadding="2" cellspacing="0">
					<thead style="background: #ffc491;">
						<tr>
							<th class="text-center">Nama Perangkat Daerah</th>
							<th class="text-center">NIP</th>
							<th class="text-center">Nama Kepala</th>
							<th class="text-center" style="width: 50rem;">Alamat</th>
							<th class="text-center" style="width: 5rem;">Aksi</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<!-- Modal Upload -->
<!-- <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" style="max-width: 22rem;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Tambah Logo Pemda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<div class="card mx-auto mb-3" style="width: 18rem;">
					<?php if(!empty($path_logo_pemda_laporan_pk)): ?>
						<img class="card-img-top logo_pemda" src="<?php echo $upload_dir.''.$path_logo_pemda_laporan_pk ?>" alt="Card image cap">
					<?php endif; ?>
				</div>
                <form enctype="multipart/form-data">
					<div class="input-group input-file-logo text-left">
						<div class="custom-file">
							<input id="fileUpload" type="file" class="custom-file-input" id="inputGroupFile04">
							<label class="custom-file-label" for="inputGroupFile04">Pilih File</label>
						</div>
					</div>
                </form>
            </div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary d-flex text-right" onclick="submit_logo_pemda(this); return false">Unggah</button>
			</div>
        </div>
    </div>
</div> -->

<!-- Modal Form Input -->
<div class="modal fade" id="settingLaporanPK" tabindex="-1" role="dialog" aria-labelledby="settingLaporanPKLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Edit Detail Perangkat Daerah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" value="" id="id_skpd">
					<div class="form-group">
                        <label for="alamat">Alamat Kantor</label>
                        <textarea type="text" class="form-control" id="alamat" name="alamat" required></textarea>
                    </div>
                </form>
            </div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary d-flex text-right" onclick="submit_edit_setting_laporan_pk(this); return false">Unggah</button>
			</div>
        </div>
    </div>
</div>

<script>
	jQuery(document).ready(function() {
		getTableSkpd();

		jQuery('input[type="file"]').change(function(e){
			var fileName = e.target.files[0].name;
			jQuery('.custom-file-label').html(fileName);
		});
	});

	function tambah_logo_pemda(){
		jQuery("#uploadModal").modal('show');
		jQuery("#fileUpload").val('');
	}

	function getTableSkpd(destroy) {
		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_table_skpd_laporan_pk_setting',
				api_key: esakip.api_key,
				tahun_anggaran: <?php echo $tahun_anggaran; ?>,
				id_login: '<?php echo $id; ?>'
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					if(destroy == 1){
						laporan_pk_table.fnDestroy();
					}
					jQuery('.table_list_skpd tbody').html(response.data);
					window.laporan_pk_table = jQuery('.table_list_skpd').dataTable({
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

	function edit_setting_laporan_pk(id_skpd) {
        jQuery('#wrap-loading').show();
		jQuery("#id_skpd").val(id_skpd);
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_detail_setting_laporan_pk_by_id',
                api_key: esakip.api_key,
                id_skpd: id_skpd
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
					if(response.data != null || response.data != undefined){
						let data = response.data;
						jQuery("#alamat").val(data.alamat_kantor);
						jQuery("#telephone").val(data.nomor_telephone_kantor);
						jQuery("#email").val(data.email_kantor);
						jQuery("#faks").val(data.nomor_faks_kantor);
					}
					jQuery('#settingLaporanPK').modal('show');
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
	
    function submit_edit_setting_laporan_pk(that){
        let id_skpd = jQuery("#id_skpd").val();
        if (id_skpd == '') {
            return alert('Id SKPD tidak boleh kosong');
        }
        let alamat = jQuery("#alamat").val();
        if (alamat == '') {
            return alert('Alamat kantor tidak boleh kosong');
        }

        let form_data = new FormData();
        form_data.append('action', 'submit_edit_laporan_pk_setting');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id_skpd', id_skpd);
        form_data.append('alamat', alamat);

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
                    jQuery('#settingLaporanPK').modal('hide');
                    alert(response.message);
					getTableSkpd(1);
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
	
    function submit_logo_pemda(that) {
        let fileDokumen = jQuery("#fileUpload").prop('files')[0];
        if (fileDokumen == '') {
            return alert('File Upload tidak boleh kosong');
        }

        let form_data = new FormData();
        form_data.append('action', 'tambah_logo_pemda_laporan_pk');
        form_data.append('api_key', esakip.api_key);
        form_data.append('fileUpload', fileDokumen);

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
					let link_logo = '<?php echo $upload_dir ?>';
					jQuery(".logo_pemda").attr("src", link_logo+''+response.nama_logo)
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

	function toDetailUrl(url) {
		window.open(url, '_blank');
	}

	function login_to_profile(user_id, tahun, nip_kepala) {
	    jQuery('#wrap-loading').show();
	    
	    jQuery.ajax({
	        url: esakip.url,
	        type: 'POST',
	        data: {
	            action: 'coba_auto_login',
	            api_key: esakip.api_key,
	            user_id: user_id,
	            id: 'sakip',
	            url: 'view_profile|' + user_id + '|' + tahun
	        },
	        dataType: 'json',
	        success: function(response) {
	            jQuery('#wrap-loading').hide();
	            console.log(response);
	            if (response.status === 'success') {
	                if (response.url_login) {
	                    window.open(response.url_login, '_blank');
	                } else {
	                    alert('URL login tidak ditemukan!');
	                }
	            } else {
	                alert(response.message);
	            }
	        },
	        error: function(xhr, status, error) {
	            jQuery('#wrap-loading').hide();
	            console.error(xhr.responseText);
	            alert('Terjadi kesalahan saat melakukan login!');
	        }
	    });
	}
</script>