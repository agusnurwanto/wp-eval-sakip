<?php
global $wpdb;

if (!defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'periode' => ''
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
	$tahun_anggaran_selesai = $val['tahun_anggaran'] + $val['lama_pelaksanaan'];
	$selected = '';
	if (!empty($input['id']) && $val['id'] == $input['periode']) {
		$selected = 'selected';
	}
	$tahun .= "<option value='$val[id]' $selected>$val[nama_jadwal] Periode $val[tahun_anggaran] -  $tahun_anggaran_selesai</option>";
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
	#table_dokumen_skpd thead{
        position: sticky;
        top: -6px;
    }
	#table_dokumen_skpd thead th{
        vertical-align: middle;
    }
	#table_dokumen_skpd tfoot{
        position: sticky;
        bottom: 0;
    }
	#table_dokumen_skpd tbody td{
        vertical-align: middle;
    }
	.status-verifikasi {
		width: 7rem;
	}
</style>
<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0;">
			<h1 class="text-center table-title">Dokumen RENSTRA <br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h1>
			<div class="wrap-table">
				<table id="table_dokumen_skpd" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
					<thead style="background: #ffc491;">
						<tr>
							<th class="text-center" rowspan="2">No</th>
							<th class="text-center" rowspan="2">Nama Perangkat Daerah</th>
							<th class="text-center" colspan="4">Status Dokumen</th>
							<th class="text-center" rowspan="2">Jumlah Dokumen</th>
							<th class="text-center kolom-integrasi-esr" rowspan="2" style="width: 10rem;">Jumlah Dokumen Integrasi ESR</th>
							<th class="text-center" rowspan="2">Aksi</th>
						</tr>
						<tr>
							<th class="text-center status-verifikasi">Draft</th>
							<th class="text-center status-verifikasi">Menunggu</th>
							<th class="text-center status-verifikasi">Disetujui</th>
							<th class="text-center status-verifikasi">Ditolak</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot style="background: #ffc491;">
						<tr>
							<th class="text-center" colspan="2">Jumlah</th>
							<th class="text-center" id="total_draft">0</th>
							<th class="text-center" id="total_menunggu">0</th>
							<th class="text-center" id="total_disetujui">0</th>
							<th class="text-center" id="total_ditolak">0</th>
							<th class="text-center" id="total_dokumen">0</th>
							<th class="text-center kolom-integrasi-esr" id="total_dokumen_integrasi">0</th>
							<th class="text-center"></th>
						</tr>
					</tfoot>
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
				<h5 class="modal-title" id="tahunModalLabel">Pilih Tahun Periode</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="tahunForm">
					<div class="form-group">
						<label for="tahunPeriode">Tahun Periode:</label>
						<select class="form-control" id="tahunPeriode" name="tahunPeriode">
							<?php echo $tahun; ?>
						</select>
						<input type="hidden" id="idDokumen" value="">
					</div>
					<button type="submit" class="btn btn-primary" onclick="submit_tahun_renstra(); return false">Simpan</button>
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
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_table_tahun_renstra',
				api_key: esakip.api_key,
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
				action: 'get_table_skpd_renstra',
				api_key: esakip.api_key,
				id_periode: <?php echo $input['periode']; ?>,
				tahun_anggaran: <?php echo $periode['tahun_anggaran']; ?>,
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				console.log(response);
				if (response.status === 'success') {
					jQuery('#table_dokumen_skpd tbody').html(response.data);
					jQuery('#total_draft').html(response.total_draft);
					jQuery('#total_menunggu').html(response.total_menunggu);
					jQuery('#total_disetujui').html(response.total_disetujui);
					jQuery('#total_ditolak').html(response.total_ditolak);
					jQuery('#total_dokumen').html(response.total_dokumen);
					jQuery('#total_dokumen_integrasi').html(response.total_integrasi);

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

	function submit_tahun_renstra() {
		let id = jQuery("#idDokumen").val();
		if (id == '') {
			return alert('id tidak boleh kosong');
		}

		let tahunPeriode = jQuery("#tahunPeriode").val();
		if (tahunPeriode == '') {
			return alert('Tahun Periode tidak boleh kosong');
		}

		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'submit_tahun_renstra',
				id: id,
				tahunPeriode: tahunPeriode,
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


	function detail_dokumen() {
		jQuery('#uploadForm').modal('show');

	}

    function hapus_tahun_dokumen_renstra(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_tahun_dokumen_renstra',
                api_key: esakip.api_key,
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