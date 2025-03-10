<?php
global $wpdb;

if (!defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'tahun' => '2022',
    'periode'   => ''
), $atts);

$periode = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
		*
    FROM esakip_data_jadwal
    WHERE id=%d
      AND status = 1
", $input['periode']),
    ARRAY_A
);

if(!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1){
    $tahun_periode = $periode['tahun_selesai_anggaran'];
}else{
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

$idtahun = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT 
            *
        FROM esakip_data_jadwal
        WHERE tipe = %s",
        'RPJMD'
    ),
    ARRAY_A
);

$tahun = "<option value='-1'>Pilih Tahun Periode</option>";

foreach ($idtahun as $val) {
    if(!empty($val['tahun_selesai_anggaran']) && $val['tahun_selesai_anggaran'] > 1){
        $tahun_anggaran_selesai = $val['tahun_selesai_anggaran'];
    }else{
        $tahun_anggaran_selesai = $val['tahun_anggaran'] + $val['lama_pelaksanaan'];
    }
    $selected = '';
    if (!empty($input['id']) && $val['id'] == $input['periode']) {
        $selected = 'selected';
    }
    $tahun .= "<option value='$val[id]' $selected>$val[nama_jadwal] Periode $val[tahun_anggaran] -  $tahun_anggaran_selesai</option>";
}

$tipe_dokumen = "pohon_kinerja_dan_cascading";
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
			<h1 class="text-center table-title">Dokumen Pohon Kinerja dan Cascading <br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h1>
			<div class="wrap-table">
				<table id="table_dokumen_skpd" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
					<thead>
						<tr>
							<th class="text-center">No</th>
							<th class="text-center">Nama Perangkat Daerah</th>
							<th class="text-center">Jumlah Dokumen</th>
							<th class="text-center" style="width: 10rem;">Jumlah Dokumen Integrasi ESR</th>
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

<!-- set dokumen yang belum memiliki tahun anggaran -->
<div class="modal fade" id="tahunModal" tabindex="-1" role="dialog" aria-labelledby="tahunModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tahunModalLabel">Pilih ID Jadwal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="tahunForm">
					<div class="form-group">
						<label for="id_jadwal">ID Jadwal:</label>
						<select class="form-control" id="id_jadwal" name="id_jadwal">
							<?php echo $tahun; ?>
						</select>
						<input type="hidden" id="idDokumen" value="">
					</div>
					<button type="submit" class="btn btn-primary" onclick="submit_tahun_dokumen(); return false">Simpan</button>
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

	function set_tahun_dokumen(id) {
		jQuery('#tahunModal').modal('show');
		jQuery('#idDokumen').val(id);
	}

	function getTableTahun() {
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_table_tahun_pohon_kinerja',
				api_key: esakip.api_key,
				tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
				id_periode: <?php echo $input['periode']; ?>,
			},
			dataType: 'json',
			success: function(response) {
				console.log(response);
				if (response.status === 'success') {
					jQuery('#tahunContainer').html(response.data);
				} else {
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
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
				action: 'get_table_skpd_pohon_kinerja',
				api_key: esakip.api_key,
				tahun_anggaran: <?php echo $input['tahun']; ?>,
				id_periode: <?php echo $input['periode']; ?>,
				tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('#table_dokumen_skpd tbody').html(response.data);
					if(response.status_mapping == 1){
						jQuery('.kolom-integrasi-esr').show();
					}else{
						jQuery('.kolom-integrasi-esr').hide();
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

	function lihatDokumen(dokumen) {
		let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/'; ?>' + dokumen;
		window.open(url, '_blank');
	}

	function toDetailUrl(url) {
		window.open(url, '_blank');
	}

	function submit_tahun_dokumen() {
		let id = jQuery("#idDokumen").val();
		if (id == '') {
			return alert('id tidak boleh kosong');
		}

		let id_jadwal = jQuery("#id_jadwal").val();
		if (id_jadwal == '') {
			return alert('ID Jadwal tidak boleh kosong');
		}

		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'submit_tahun_pohon_kinerja',
				id: id,
                id_jadwal: id_jadwal,
				api_key: esakip.api_key,
				tipe_dokumen: '<?php echo $tipe_dokumen; ?>',
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

    function hapus_dokumen_pohon_kinerja(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_dokumen',
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