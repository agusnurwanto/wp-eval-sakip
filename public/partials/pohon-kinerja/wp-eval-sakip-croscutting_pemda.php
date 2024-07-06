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
if (!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1) {
    $tahun_periode = $periode['tahun_selesai_anggaran'];
} else {
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
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

    @media print {
        #cetak {
            max-width: auto !important;
            height: auto !important;
        }

        @page {
            size: landscape;
        }

        #action-sakip,
        .site-header,
        .site-footer,
        #container-table-crosscutting,
        #ast-scroll-top {
            display: none;
        }
    }
</style>

<!-- Table -->
<div class="container-md" id="container-table-crosscutting">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Crosscutting <?php echo $periode['nama_jadwal'] . ' ' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ''; ?></h1>
            <div class="wrap-table">
                <table id="table_dokumen_crosscutting" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">No</th>
                            <th class="text-center">Judul Crosscutting</th>
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

<!-- Modal Edit -->
<div class="modal fade mt-4" id="modalEditCrosscutting" tabindex="-1" role="dialog" aria-labelledby="modalEditCrosscuttingLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditCrosscuttingLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="<?php echo $input['periode']; ?>" id="id_jadwal">
                <input type="hidden" value="" id="id">
                <div class="form-group">
                    <label for="nama_crosscutting">Nama Crosscutting</label>
                    <input type="text" class="form-control" id="nama_crosscutting" name="nama_crosscutting" required>
                </div>
                <div class="form-group">
                    <label for="tujuan_teks">Tujuan RPJMD/RPD</label>
                    <input type="text" class="form-control" id="tujuan_teks" name="tujuan_teks" disabled>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary submitBtn" onclick="submit_edit_crosscutting()">Simpan</button>
                <button type="submit" class="components-button btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        getTableCrosscutting();

    });

    function getDataChart() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_chart_crosscutting',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen_crosscutting tbody').html(response.data);
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

    function getTableCrosscutting() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_crosscutting',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen_crosscutting tbody').html(response.data);
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

    function editCrosscuttingPemda(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'edit_crosscutting_pemda',
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
                    if (data.nama_crosscutting) {
                        jQuery('#nama_crosscutting').val(data.nama_crosscutting);
                    } else {
                        jQuery('#nama_crosscutting').val(data.tujuan_teks);
                    }
                    jQuery('#modalEditCrosscutting .send_data').show();
                    jQuery('#modalEditCrosscutting').modal('show');
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

    function submit_edit_crosscutting() {
        let id = jQuery("#id").val();
        if (id == '') {
            return alert('Id tidak boleh kosong');
        }
        var tujuan_teks = jQuery('#tujuan_teks').val();
        if (tujuan_teks == '') {
            return alert('Data tujuan_teks tidak boleh kosong!');
        }
        var nama_crosscutting = jQuery('#nama_crosscutting').val();
        if (nama_crosscutting == '') {
            return alert('Data nama crosscutting tidak boleh kosong!');
        }

        let form_data = new FormData();
        form_data.append('action', 'submit_edit_crosscutting');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id', id);
        form_data.append('nama_crosscutting', nama_crosscutting);
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
                    jQuery('#modalEditCrosscutting').modal('hide');
                    alert(response.message);
                    getTableCrosscutting();
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
</script>