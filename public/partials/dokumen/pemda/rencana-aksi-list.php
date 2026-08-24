<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2022',
    'periode' => ''
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

$data_temp = [''];
$pokin = $wpdb->get_results($wpdb->prepare('
    SELECT 
        *
    FROM esakip_pohon_kinerja
    WHERE id_jadwal=%d
        AND level=1
        AND parent=0
', $input['periode']), ARRAY_A);
$select_pokin = '<option value="">Pilih Pohon Kinerja</option>';
foreach($pokin as $get_pokin){
    $select_pokin .= '<option value="'.$get_pokin['id'].'">'.$get_pokin['label'].'</option>';
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

    #table_dokumen_renaksi th {
        vertical-align: middle;
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
        #container-table-rencanaaksi,
        #ast-scroll-top {
            display: none;
        }
    }
</style>

<!-- Table -->
<div class="container-md" id="container-table-rencanaaksi">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Rencana Hasil Kerja Tahun Anggaran <?php echo $input['tahun']; ?><br><?php echo $periode['nama_jadwal'] . ' ' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ''; ?></h1>
            <div class="wrap-table">
                <table id="table_dokumen_renaksi" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10px;">No</th>
                            <th class="text-center" style="width: 30px;">Judul Cascading</th>
                            <th class="text-center" style="width: 40%;">Tujuan RPJMD/RPD</th>
                            <th class="text-center" style="width: 20%;">Pohon Kinerja</th>
                            <th class="text-center" style="width: 10%;">Pagu Anggaran</th>
                            <th class="text-center" style="width: 10%;">Aksi</th>
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
<div class="modal fade mt-4" id="modalpokin" tabindex="-1" role="dialog" aria-labelledby="modalpokinLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalpokinLabel">Edit Rencana Hasil Kerja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" id="id">
                <div class="form-group">
                    <label for="judul_cascading">Judul Cascading</label>
                    <input type="text" class="form-control" id="judul_cascading" name="judul_cascading" disabled>
                </div>
                <div class="form-group">
                    <label for="tujuan_rpd">Tujuan RPJMD / RPD</label>
                    <input type="text" class="form-control" id="tujuan_rpd" name="tujuan_rpd" disabled>
                </div>
                <div class="form-group">
                    <label for="id_pokin">Pohon Kinerja Level 1</label>
                    <select class="form-control" id="id_pokin" name="id_pokin" multiple><?php echo $select_pokin; ?></select>
                </div>
                <div class="form-group">
                    <label for="pagu">Pagu Anggaran</label>
                    <input type="text" class="form-control" id="pagu" name="pagu" disabled value="0">
                </div>
            </div> 
            <div class="modal-footer">
                <button class="btn btn-primary submitBtn" onclick="submit_data()">Simpan</button>
                <button type="submit" class="components-button btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        getTableRencanaAksi();

    });

    function getTableRencanaAksi(id_tujuan) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_renaksi_pemda',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
                tahun_anggaran: <?php echo $input['tahun']; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen_renaksi tbody').html(response.data);
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

    function toDetailUrl(url) {
        window.open(url, '_blank');
    }

    function edit_pokin_pemda(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_pokin_renaksi_by_id',
                api_key: esakip.api_key,
                id: id,
                tahun_anggaran: <?php echo $input['tahun']; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();

                if (response.status === 'error') {
                    alert(response.message);
                    return;
                }

                if (response.data != null) {
                    jQuery('#id').val(id);
                    jQuery('#judul_cascading').val(response.data.nama_cascading || '');
                    jQuery('#tujuan_rpd').val(response.data.tujuan_teks || '');
                    let selected_pokin = [];
                    response.data.pokin.map(function(b) {
                        selected_pokin.push(b.id);
                    });
                    jQuery('#id_pokin').val(selected_pokin);
                    jQuery("#id_pokin").trigger('change'); 

                    jQuery('#modalpokin').modal('show');
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data!');
            }
        });

        jQuery('#id_pokin').select2({
            width: '100%'
        });
    }

    function submit_data() {
        var id = jQuery('#id').val();

        var id_pokin = jQuery('#id_pokin').val();
        if(id_pokin == ''){
            return alert('Data Pohon Kinerja tidak boleh kosong!');
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": 'tambah_pokin_renaksi',
                "api_key": esakip.api_key,
                "id": id,
                "id_pokin": id_pokin,
                "level": 1,
                "tahun_anggaran": <?php echo $input['tahun']; ?>,
            },
            dataType: "json",
            success: function(res) {
                jQuery('#wrap-loading').hide();
                alert(res.message);
                if (res.status == 'success') {
                    jQuery("#modalpokin").modal('hide');
                    getTableRencanaAksi();
                }
            }
        });
    }
</script>
