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
            <h1 class="text-center" style="margin:3rem;">Rencana Aksi <?php echo $periode['nama_jadwal'] . ' ' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ''; ?></h1>
            <div class="wrap-table">
                <table id="table_dokumen_renaksi" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
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
</script>