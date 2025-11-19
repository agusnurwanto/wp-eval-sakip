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

$set_renaksi = get_option('_crb_input_renaksi'); 
$set_pagu_renaksi = get_option('_crb_set_pagu_renaksi'); 
$set_tabel_individu = get_option('_crb_set_tabel_individu'); 
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
        <h1 class="text-center" style="margin:3rem;">Halaman Pengaturan Rencana Aksi<br>Tahun Anggaran <?php echo $tahun_anggaran; ?></h1>
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
                            <label for="tampilkan-ganti-jadwal">Ganti Jadwal RPJMD/RPD untuk RHK</label><br>
                            <input type="checkbox" id="tampilkan-ganti-jadwal" name="tampilkan_ganti_jadwal" value="1">
                            <label for="tampilkan-ganti-jadwal">Tampilkan</label>
                            <small class="form-text text-muted d-block">Centang untuk menampilkan pilihan jadwal RPJMD/RPD untuk RHK.</small>
                        </div>
                        <div class="form-group" id="tampil-jadwal-rpjmd-rhk" style="display:none;">
                            <select class="form-control" id="jadwal-rpjmd-rhk">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jadwal-renstra">Pilih Jadwal RENSTRA WP-SIPD</label>
                            <select class="form-control" id="jadwal-renstra-wpsipd">
                            </select>
                            <small class="form-text text-muted">Untuk mendapatkan Sasaran Cascading di WP-SIPD sesuai jadwal yang digunakan di input Rencana Hasil Kerja.</small>
                        </div>
                        <div class="form-group">
                            <label for="input-renaksi">Input Rencana Aksi Bulanan dan realisasi Triwulan</label><br>
                            <input type="radio" id="input-renaksi-0" name="crb_input_renaksi" value="0" <?php echo ($set_renaksi == 0) ? 'checked' : ''; ?>>
                            <label for="input-renaksi-0">Iya</label><br>
                            <input type="radio" id="input-renaksi-1" name="crb_input_renaksi" value="1" <?php echo ($set_renaksi == 1) ? 'checked' : ''; ?>>
                            <label for="input-renaksi-1">Tidak</label>
                            <small class="form-text text-muted">Pilih apakah input Rencana Hasil Kerja bulanan diinput atau didisable.</small>
                        </div>
                        <div class="form-group">
                            <label for="set-pagu-renaksi">Menampilkan Rencana Pagu Rencana Hasil Kerja</label><br>
                            <input type="radio" id="set-pagu-renaksi-0" name="crb_set_pagu_renaksi" value="0" <?php echo ($set_pagu_renaksi == 0) ? 'checked' : ''; ?>>
                            <label for="set-pagu-renaksi-0">Iya</label><br>
                            <input type="radio" id="set-pagu-renaksi-1" name="crb_set_pagu_renaksi" value="1" <?php echo ($set_pagu_renaksi == 1) ? 'checked' : ''; ?>>
                            <label for="set-pagu-renaksi-1">Tidak</label>
                            <small class="form-text text-muted">Pilih apakah Rencana Pagu Rencana Hasil Kerja ditampilkan atau disembunyikan.</small>
                        </div>
                        <div class="form-group">
                            <label for="set-tabel-individu">Menampilkan Tabel Rencana Aksi Individu yang tidak ada di Rencana Aksi OPD</label><br>
                            <input type="radio" id="set-tabel-individu-0" name="crb_set_tabel_individu" value="0" <?php echo ($set_tabel_individu == 0) ? 'checked' : ''; ?>>
                            <label for="set-tabel-individu-0">Iya</label><br>
                            <input type="radio" id="set-tabel-individu-1" name="crb_set_tabel_individu" value="1" <?php echo ($set_tabel_individu == 1) ? 'checked' : ''; ?>>
                            <label for="set-tabel-individu-1">Tidak</label>
                            <small class="form-text text-muted">Pilih apakah tabel Rencana Aksi Individu yang tidak ada di Rencana Aksi OPD ditampilkan atau disembunyikan.</small>
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
		jQuery('#tampilkan-ganti-jadwal').on('change', function() {
            if (jQuery(this).is(':checked')) {
                jQuery('#tampil-jadwal-rpjmd-rhk').show();
            } else {
                jQuery('#tampil-jadwal-rpjmd-rhk').hide();
            }
        });
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
                    jQuery('#jadwal-rpjmd').html(response.option_rpjmd);
                    if (response.data.length != 0) {
                        jQuery('#jadwal-rpjmd').val(response.data.id_jadwal_rpjmd);
                    }
                    
                    jQuery('#jadwal-rpjmd-rhk').html(response.option_rpjmd_rhk);
                    
                    if (response.data_rencana_aksi.length != 0 && response.data_rencana_aksi.id_jadwal_rpjmd_rhk) {
                        jQuery('#tampilkan-ganti-jadwal').prop('checked', true);
                        jQuery('#tampil-jadwal-rpjmd-rhk').show();
                        jQuery('#jadwal-rpjmd-rhk').val(response.data_rencana_aksi.id_jadwal_rpjmd_rhk);
                    } else {
                        jQuery('#tampilkan-ganti-jadwal').prop('checked', false);
                        jQuery('#tampil-jadwal-rpjmd-rhk').hide();
                        
                        if (response.data.length != 0 && response.data.id_jadwal_rpjmd) {
                            jQuery('#jadwal-rpjmd-rhk').val(response.data.id_jadwal_rpjmd);
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
        let input_renaksi = jQuery('input[name="crb_input_renaksi"]:checked').val();
        let set_pagu_renaksi = jQuery('input[name="crb_set_pagu_renaksi"]:checked').val();
        let set_tabel_individu = jQuery('input[name="crb_set_tabel_individu"]:checked').val();
        
        let id_jadwal_rpjmd_rhk = null;
        let tampilkan_ganti = jQuery('#tampilkan-ganti-jadwal').is(':checked');
        
        if (tampilkan_ganti) {
            id_jadwal_rpjmd_rhk = jQuery("#jadwal-rpjmd-rhk").val();
        } else {
            id_jadwal_rpjmd_rhk = jQuery("#jadwal-rpjmd").val();
        }

        if (id_jadwal_renstra_wpsipd == '') {
            return alert('Ada data yang kosong!');
        }

        if (confirm("Apakah kamu yakin?")) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                method: 'POST',
                url: esakip.url,
                dataType: 'json',
                data: {
                    'action': 'submit_pengaturan_rencana_aksi',
                    'api_key': esakip.api_key,
                    'id_jadwal_renstra': id_jadwal_renstra,
                    'id_jadwal_rpjmd_rhk': id_jadwal_rpjmd_rhk,
                    'id_jadwal_renstra_wpsipd': id_jadwal_renstra_wpsipd,
                    'tahun_anggaran': tahun_anggaran,
                    'input_renaksi': input_renaksi, 
                    'set_pagu_renaksi': set_pagu_renaksi, 
                    'set_tabel_individu': set_tabel_individu 
                },
                success: function(response) {
                    console.log(response);
                    jQuery('#wrap-loading').hide();
                    if (response.status === 'success') {
                        alert(response.message);

                        if (input_renaksi == '1') {
                            jQuery('#input-renaksi-0').prop('checked', false);
                            jQuery('#input-renaksi-1').prop('checked', true);
                        } else {
                            jQuery('#input-renaksi-0').prop('checked', true);
                            jQuery('#input-renaksi-1').prop('checked', false);
                        }

                        if (set_pagu_renaksi == '1') {
                            jQuery('#set-pagu-renaksi-0').prop('checked', false);
                            jQuery('#set-pagu-renaksi-1').prop('checked', true);
                        } else {
                            jQuery('#set-pagu-renaksi-0').prop('checked', true);
                            jQuery('#set-pagu-renaksi-1').prop('checked', false);
                        }

                        if (set_tabel_individu == '1') {
                            jQuery('#set-tabel-individu-0').prop('checked', false);
                            jQuery('#set-tabel-individu-1').prop('checked', true);
                        } else {
                            jQuery('#set-tabel-individu-0').prop('checked', true);
                            jQuery('#set-tabel-individu-1').prop('checked', false);
                        }

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