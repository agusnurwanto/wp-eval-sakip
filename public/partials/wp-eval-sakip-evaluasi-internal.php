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
		FROM esakip_data_unit",
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

$detail_evaluasi_internal = $this->functions->generatePage(array(
    'nama_page' => 'Halaman Detail Dokumen Evaluasi Internal ' . $input['tahun'],
    'content' => '[dokumen_detail_evaluasi_internal tahun=' . $input["tahun"] . ']',
    'show_header' => 1,
    'no_key' => 1,
    'post_status' => 'private'
));
$detail_evaluasi_internal['url'] .= '?1=1';

$unit = $wpdb->get_results($wpdb->prepare("
	SELECT 
		nama_skpd, 
		id_skpd, 
		kode_skpd, 
		nipkepala 
	FROM esakip_data_unit 
	WHERE active=1 
	  AND tahun_anggaran=%d
	  AND is_skpd=1 
	ORDER BY kode_skpd ASC
", $input['tahun']), ARRAY_A);

$dokumen_unset = $wpdb->get_results("
	SELECT 
		*
	FROM esakip_evaluasi_internal 
	WHERE tahun_anggaran IS NULL
	  AND active = 1
", ARRAY_A);

$tbody = '';
$tbodyUnset = '';
$counter = 1;
$counterUnset = 1;

foreach ($unit as $kk => $vv) {
    $tbody .= "<tr>";
    $tbody .= "<td class='text-center'>" . $counter++ . "</td>";
    $tbody .= "<td>" . $vv['kode_skpd'] . "</td>";
    $tbody .= "<td style='text-transform: uppercase;'><a target='_blank' href='" . $detail_evaluasi_internal['url'] . '&id_skpd=' . $vv['id_skpd'] . "'>" . $vv['nama_skpd'] . "</a></td>";

    $jumlah_dokumen = $wpdb->get_var(
        $wpdb->prepare(
            "
			SELECT 
				COUNT(id)
			FROM esakip_evaluasi_internal 
			WHERE id_skpd = %d
			  AND tahun_anggaran = %d
			  AND active = 1
			",
            $vv['id_skpd'],
            $input['tahun']
        )
    );

    $tbody .= "<td>" . $jumlah_dokumen . "</td>";
    $tbody .= "</tr>";
}

foreach ($dokumen_unset as $kk => $vv) {
    $tbodyUnset .= "<tr>";
    $tbodyUnset .= "<td class='text-center'>" . $counterUnset++ . "</td>";
    $tbodyUnset .= "<td>" . $vv['opd'] . "</td>";
    $tbodyUnset .= "<td>" . $vv['dokumen'] . "</td>";
    $tbodyUnset .= "<td>" . $vv['keterangan'] . "</td>";

    $aksiUnset = "<button class='btn btn-success' onclick='set_tahun_dokumen(" . $vv['id'] . ")'><span class='dashicons dashicons-insert'></span></button>";
    $tbodyUnset .= "<td class='text-center'>" . $aksiUnset . "</td>";

    $tbodyUnset .= "</tr>";
}
?>
<style type="text/css">
    .wrap-table {
        overflow: auto;
        max-height: 100vh;
        width: 100%;
    }
</style>
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center table-title">Dokumen Evaluasi Internal Tahun <?php echo $input['tahun']; ?></h1>
            <div class="wrap-table">
                <table cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode SKPD</th>
                            <th class="text-center">Nama SKPD</th>
                            <th class="text-center">Jumlah Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $tbody; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h3 class="text-center">Dokumen yang belum disetting Tahun Anggaran</h3>
            <div class="wrap-table">
                <table cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Perangkat Daerah</th>
                            <th class="text-center">Nama Dokumen</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $tbodyUnset; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
                    <button type="submit" class="btn btn-primary" onclick="submit_tahun_evaluasi_internal(); return false">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function set_tahun_dokumen(id) {
        jQuery('#tahunModal').modal('show');
        jQuery('#idDokumen').val(id);
    }

    function submit_tahun_evaluasi_internal() {
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
                action: 'submit_tahun_evaluasi_internal',
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
</script>