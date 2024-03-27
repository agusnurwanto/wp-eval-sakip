<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2022',
), $atts);

if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}

$skpd = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        kode_skpd,
        nama_skpd
    FROM esakip_data_unit
    WHERE id_skpd=%d
      AND tahun_anggaran=%d
      AND active = 1
", $id_skpd, $input['tahun']),
    ARRAY_A
);

$renjas = $wpdb->get_results(
    $wpdb->prepare("
    SELECT 
        *
    FROM esakip_renja_rkt
    WHERE id_skpd=%d
      AND tahun_anggaran=%d
      AND active = 1
", $id_skpd, $input['tahun']),
    ARRAY_A
);

$counter = 1;
$tbody = null;

foreach ($renjas as $kk => $vv) {
    $tbody .= "<tr>";
    $tbody .= "<td class='text-center'>" . $counter++ . "</td>";
    $tbody .= "<td>" . $vv['opd'] . "</td>";
    $tbody .= "<td>" . $vv['dokumen'] . "</td>";
    $tbody .= "<td>" . $vv['keterangan'] . "</td>";
    $tbody .= "<td>" . $vv['created_at'] . "</td>";

    $btn = '<button class="btn btn-sm btn-warning" onclick="detail_dokumen(\'' . $vv['id'] . '\'); return false;" href="#" title="Detail Data"><span class="dashicons dashicons-visibility"></span></button>';
    $btn .= '<button class="btn btn-sm btn-danger" style="margin-left: 7px;" onclick="hapus_dokumen_renja(\'' . $vv['id'] . '\'); return false;" href="#" title="Hapus Data"><span class="dashicons dashicons-trash"></span></button>';

    $tbody .= "<td class='text-center'>" . $btn . "</td>";
    $tbody .= "</tr>";
}
?>
<style type="text/css">
    .wrap-table {
        overflow: auto;
        max-height: 100vh;
        width: 100%;
    }
</style>

<!-- Table -->
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Dokumen RENJA/RKT <br><?php echo $skpd['kode_skpd'] . ' ' . $skpd['nama_skpd'] ?><br> Tahun Anggaran <?php echo $input['tahun']; ?></h1>
            <div style="margin-bottom: 25px;">
                <button class="btn btn-primary" onclick="tambah_dokumen_renja();"><i class="dashicons dashicons-plus"></i> Tambah Data</button>
            </div>
            <div class="wrap-table">
                <table id="table_dokumen_renja" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Perangkat Daerah</th>
                            <th class="text-center">Nama Dokumen</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Waktu Upload</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
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
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Tambah Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $id_skpd; ?>" id="idSkpd">
                    <input type="hidden" value="<?php echo $input['tahun']; ?>" id="tahunAnggaran">
                    <div class="form-group">
                        <label for="perangkatDaerah">Perangkat Daerah</label>
                        <input type="text" class="form-control" id="perangkatDaerah" name="perangkatDaerah" style="text-transform: uppercase;" value="<?php echo $skpd['nama_skpd']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="fileUpload">Pilih File</label>
                        <input type="file" class="form-control-file" id="fileUpload" name="fileUpload" required>
                    </div>
                    <div class="alert alert-warning mt-2" role="alert">
                        Maksimal ukuran file: 10 MB. Format file yang diperbolehkan: PDF.
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="submit_dokumen(this); return false">Unggah</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function() {
        getTableRenja();
    });

    function getTableRenja() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_renja',
                api_key: esakip.api_key,
                id_skpd: <?php echo $id_skpd; ?>,
                tahun_anggaran: '<?php echo $input['tahun'] ?>'
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen_renja tbody').html(response.data);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data RENJA/RKT!');
            }
        });
    }

    function tambah_dokumen_renja() {
        jQuery("#uploadModal").modal('show');
    }

    function submit_dokumen(that) {
        let skpd = jQuery("#perangkatDaerah").val();
        if (skpd == '') {
            return alert('Perangkat Daerah tidak boleh kosong');
        }
        let idSkpd = jQuery("#idSkpd").val();
        if (idSkpd == '') {
            return alert('Id Skpd tidak boleh kosong');
        }
        let keterangan = jQuery("#keterangan").val();
        if (keterangan == '') {
            return alert('Keterangan tidak boleh kosong');
        }
        let tahunAnggaran = jQuery("#tahunAnggaran").val();
        if (tahunAnggaran == '') {
            return alert('Tahun Anggaran tidak boleh kosong');
        }
        let fileDokumen = jQuery("#fileUpload").prop('files')[0];
        if (fileDokumen == '') {
            return alert('File Upload tidak boleh kosong');
        }

        let form_data = new FormData();
        form_data.append('action', 'tambah_dokumen_renja');
        form_data.append('api_key', esakip.api_key);
        form_data.append('skpd', skpd);
        form_data.append('idSkpd', idSkpd);
        form_data.append('keterangan', keterangan);
        form_data.append('tahunAnggaran', tahunAnggaran);
        form_data.append('fileUpload', fileDokumen);

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#uploadModal').modal('hide');
                    alert(response.message);
                    getTableRenja();
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

    function detail_dokumen() {
        alert("oke");
    }

    function hapus_dokumen_renja(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_dokumen_renja',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableRenja();
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