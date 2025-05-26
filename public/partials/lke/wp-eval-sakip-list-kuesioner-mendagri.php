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

    .table_dokumen_skpd thead {
        position: sticky;
        top: -6px;
    }

    .table_dokumen_skpd thead th {
        vertical-align: middle;
    }

    .table_dokumen_skpd tfoot {
        position: sticky;
        bottom: 0;
    }

    .table_dokumen_skpd tfoot th {
        vertical-align: middle;
    }
</style>

<body>
    <div class="container-md">
        <div class="cetak">
            <div style="padding: 10px;margin:0 0 3rem 0;">
                <h1 class="text-center table-title">Kuesioner Mendagri </br>Tahun Anggaran <?php echo $input['tahun']; ?></h1>
                <div id="action" class="action-section hide-excel"></div>
                <div class="wrap-table">
                    <table id="cetak" title="Kuesioner Mendagri Perangkat Daerah" class="table table-bordered table_dokumen_skpd" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;">
                        <thead style="background: #ffc491;">
                            <tr>
                                <th class="text-center">Nama Perangkat Daerah</th>
                                <th class="text-center">Skor</th>
                                <th class="text-center">Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    jQuery(document).ready(function() {
        getTableSkpd();
    });

    function getTableSkpd() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_skpd_kuesioner_mendagri',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('.table_dokumen_skpd tbody').html(response.data);
                    jQuery('.table_dokumen_skpd').dataTable({
                        aLengthMenu: [
                            [5, 10, 25, 100, -1],
                            [5, 10, 25, 100, "All"]
                        ],
                        iDisplayLength: -1
                    });
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

    function toDetailUrl(url) {
        window.open(url, '_blank');
    }
</script>