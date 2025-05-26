<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '',
), $atts);

?>
<style>
    .wrap-table {
        overflow: auto;
        max-height: 100vh;
        width: 100%;
    }

    .transparent-button {
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
    <div style="padding: 10px;margin:0 0 3rem 0;">
        <h1 class="text-center" style="margin:3rem;">Input Kuesioner Mendagri<br><?php echo $input['tahun']; ?></h1>
        <div class="wrap-table">
            <table id="table_kuesioner_mendagri" cellpadding="2" cellspacing="0" style="collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" colspan="4" style="vertical-align: middle;">Nama Kuesioner</th>
                        <th class="text-center" style="vertical-align: middle;">Variabel</th>
                        <th class="text-center" style="vertical-align: middle;">Indikator</th>
                        <th class="text-center" style="vertical-align: middle;">Penjelasan</th>
                        <th class="text-center" style="vertical-align: middle;">Data Dukung</th>
                        <th class="text-center" style="vertical-align: middle;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function() {
    })
</script>