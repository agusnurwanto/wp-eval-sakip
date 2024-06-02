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
$tahun = "<option value='-1'>Pilih Tahun</option>";

foreach ($idtahun as $val) {
	$selected = '';
	if (!empty($input['tahun_anggaran']) && $val['tahun_anggaran'] == $input['tahun_anggaran']) {
		$selected = 'selected';
	}
	$tahun .= "<option value='$val[tahun_anggaran]' $selected>$val[tahun_anggaran]</option>";
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
</style>
<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Dokumen Laporan Monev Renaksi Tahun <?php echo $input['tahun']; ?></h1>
			<div class="wrap-table">
				<table id="table_dokumen_skpd" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
					<thead>
						<tr>
							<th class="text-center">No</th>
							<th class="text-center">Nama Perangkat Daerah</th>
							<th class="text-center">Jumlah Dokumen</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="tahunContainer" class="container-md">
</div>

<div class="modal fade" id="tahunModal" tabindex="-1" role="dialog" aria-labelledby="tahunModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tahunModalLabel">Pilih Tahun Anggaran</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="tahunForm">
					<div class="form-group">
						<label for="tahunAnggaran">Tahun Anggaran:</label>
						<select class="form-control" id="tahunAnggaran" name="tahunAnggaran">
							<?php echo $tahun; ?>
						</select>
						<input type="hidden" id="idDokumen" value="">
					</div>
					<button type="submit" class="btn btn-primary" onclick="submit_tahun_laporan_monev_renaksi(); return false">Simpan</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function() {
		getTableTahun();
		getTableSkpd();
	});

	function getTableTahun() {
		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_table_tahun_laporan_monev_renaksi',
				api_key: esakip.api_key,
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('#tahunContainer').html(response.data);
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

	function getTableSkpd() {
		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_table_skpd_laporan_monev_renaksi',
				api_key: esakip.api_key,
				tahun_anggaran: <?php echo $input['tahun']; ?>,
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('#table_dokumen_skpd tbody').html(response.data);
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

	function set_tahun_dokumen(id) {
		jQuery('#tahunModal').modal('show');
		jQuery('#idDokumen').val(id);
	}

	function lihatDokumen(dokumen) {
		let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/'; ?>' + dokumen;
		window.open(url, '_blank');
	}

	function toDetailUrl(url) {
		window.open(url, '_blank');
	}

	function submit_tahun_laporan_monev_renaksi() {
		let id = jQuery("#idDokumen").val();
		if (id == '') {
			return alert('id tidak boleh kosong');
		}

		let tahunAnggaran = jQuery("#tahunAnggaran").val();
		if (tahunAnggaran == '') {
			return alert('Tahun Anggaran tidak boleh kosong');
		}

		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'submit_tahun_laporan_monev_renaksi',
				id: id,
				tahunAnggaran: tahunAnggaran,
				api_key: esakip.api_key
			},
			dataType: 'json',
			success: function(response) {
				console.log(response);
				jQuery('#wrap-loading').hide();
				if (response.status === 'success') {
					alert(response.message);
					jQuery('#tahunModal').modal('hide');
					getTableTahun();
					getTableSkpd();
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

    function hapus_tahun_dokumen_tipe(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_tahun_dokumen_tipe',
                api_key: esakip.api_key,
				tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableSkpd();
                    getTableTahun();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                jQuery('#wrap-loading').hide();
                alert('Terjadi kesalahan saat mengirim data!');
            }
        });
    }
</script>