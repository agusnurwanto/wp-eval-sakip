<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'periode' => '',
), $atts);

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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
if (!empty($periode['tahun_selesai_anggaran'])) {
    $tahun_periode = $periode['tahun_selesai_anggaran'];
} else {
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

$data_temp = [''];

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

    #table_dokumen_cascading th {
        vertical-align: middle;
    }

    .google-visualization-orgchart-node {
        border-radius: 5px;
        border: 0;
        padding: 0;
    }

    #chart_div .google-visualization-orgchart-connrow-medium {
        height: 20px;
    }

    #chart_div .google-visualization-orgchart-linebottom {
        border-bottom: 4px solid #f84d4d;
    }

    #chart_div .google-visualization-orgchart-lineleft {
        border-left: 4px solid #f84d4d;
    }

    #chart_div .google-visualization-orgchart-lineright {
        border-right: 4px solid #f84d4d;
    }

    #chart_div .google-visualization-orgchart-linetop {
        border-top: 4px solid #f84d4d;
    }

    .level0 {
        color: #0d0909;
        font-size: 13px;
        font-weight: 600;
        padding: 10px;
        min-height: 80px;
        min-width: 200px;
    }

    .label1 {
        background: #efd655;
        border-radius: 5px 5px 0 0;
    }

    .level1 {
        color: #0d0909;
        font-size: 11px;
        font-weight: 600;
        font-style: italic;
        padding: 10px;
        min-height: 70px;
    }

    .label2 {
        background: #fe7373;
        border-radius: 5px 5px 0 0;
    }

    .level2 {
        color: #0d0909;
        font-size: 11px;
        font-weight: 600;
        font-style: italic;
        padding: 10px;
        min-height: 70px;
    }

    .label3 {
        background: #57b2ec;
        border-radius: 5px 5px 0 0;
    }

    .level3 {
        color: #0d0909;
        font-size: 11px;
        font-weight: 600;
        font-style: italic;
        padding: 10px;
        min-height: 70px;
    }

    .label4 {
        background: #c979e3;
        border-radius: 5px 5px 0 0;
    }

    .level4 {
        color: #0d0909;
        font-size: 11px;
        font-weight: 600;
        font-style: italic;
        padding: 10px;
        min-height: 70px;
    }
</style>

<!-- Table -->
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Cascading <?php echo $periode['nama_jadwal'] . ' ' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ''; ?></h1>
            <div class="wrap-table">
                <table id="table_dokumen_cascading" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">No</th>
                            <th class="text-center">Judul Cascading</th>
                            <th class="text-center" style="width: 50%;">Tujuan RPJMD/RPD</th>
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

<div id="view_cascading">
    <h4 style="text-align: center; margin: 0; font-weight: bold;">Cascading</h4><br>
    <div id="cetak" title="Laporan Pohon Kinerja" style="padding: 5px; overflow: auto; height: 100vh;">
        <div id="chart_div"></div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade mt-4" id="modalEditCascading" tabindex="-1" role="dialog" aria-labelledby="modalEditCascadingLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditCascadingLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="<?php echo $input['periode']; ?>" id="id_jadwal">
                <input type="hidden" value="" id="id">
                <div class="form-group">
                    <label for="nama_cascading">Nama Cascading</label>
                    <input type="text" class="form-control" id="nama_cascading" name="nama_cascading" required>
                </div>
                <div class="form-group">
                    <label for="tujuan_teks">Tujuan RPJMD/RPD</label>
                    <input type="text" class="form-control" id="tujuan_teks" name="tujuan_teks" disabled>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary submitBtn" onclick="submit_edit_cascading()">Simpan</button>
                <button type="submit" class="components-button btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        getTableCascading();
       
    });

    function getDataChart() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_chart_cascading',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen_cascading tbody').html(response.data);
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

    function getTableCascading() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_cascading',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen_cascading tbody').html(response.data);
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

    function edit_cascading_pemda(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'edit_cascading_pemda',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
                id: id,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    let data = response.data;
                    jQuery('#id').val(data.id);
                    jQuery('#tujuan_teks').val(data.tujuan_teks);
                    if (data.nama_cascading) {
                        jQuery('#nama_cascading').val(data.nama_cascading);
                    } else {
                        jQuery('#nama_cascading').val(data.tujuan_teks);
                    }
                    jQuery('#modalEditCascading .send_data').show();
                    jQuery('#modalEditCascading').modal('show');
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

    function submit_edit_cascading() {
        let id = jQuery("#id").val();
        if (id == '') {
            return alert('Id tidak boleh kosong');
        }
        var tujuan_teks = jQuery('#tujuan_teks').val();
        if (tujuan_teks == '') {
            return alert('Data tujuan_teks tidak boleh kosong!');
        }
        var nama_cascading = jQuery('#nama_cascading').val();
        if (nama_cascading == '') {
            return alert('Data nama_cascading tidak boleh kosong!');
        }

        let form_data = new FormData();
        form_data.append('action', 'submit_edit_cascading');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id', id);
        form_data.append('nama_cascading', nama_cascading);
        form_data.append('tujuan_teks', tujuan_teks);

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
                    jQuery('#modalEditCascading').modal('hide');
                    alert(response.message);
                    getTableCascading();
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
</script>