<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'id_jadwal' => '',
), $atts);

if(!empty($_GET['id_jadwal'])){
    $id_jadwal = $_GET['id_jadwal'];
} else {
    die('JADWAL KOSONG !');
}

$jadwal = $wpdb->get_row(
    $wpdb->prepare("
        SELECT
            *
        FROM esakip_data_jadwal
        WHERE id=%d
          AND status!=0
    ", $id_jadwal),
    ARRAY_A
);

if (empty($jadwal)) {
    die("jadwal tidak tersedia");
}

if (!empty($jadwal)) {
    $tahun_anggaran = $jadwal['tahun_anggaran'];
    $jenis_jadwal = $jadwal['jenis_jadwal'];
    $nama_jadwal = $jadwal['nama_jadwal'];
    $mulai_jadwal = $jadwal['started_at'];
    $selesai_jadwal = $jadwal['end_at'];
    $lama_pelaksanaan = $jadwal['lama_pelaksanaan'];
} else {
    $tahun_anggaran = '2024';
    $jenis_jadwal = '-';
    $nama_jadwal = '-';
    $mulai_jadwal = '-';
    $selesai_jadwal = '-';
    $lama_pelaksanaan = 1;
}

$timezone = get_option('timezone_string');

$get_nama_komponen = $wpdb->get_results(
    $wpdb->prepare("
        SELECT 
            nama
        FROM esakip_komponen
        WHERE id_jadwal = %d
          AND active = 1
        ORDER BY nomor_urut ASC
    ", $input['id_jadwal']),
    ARRAY_A
);

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
<div class="container-md" id="cetak" title="Pengisian LKE SAKIP (<?php echo $jadwal['tahun_anggaran']; ?>)">
    <div style="padding: 10px;margin:0 0 3rem 0;">
        <h1 class="text-center table-title">Pengisian LKE SAKIP<br><?php echo $jadwal['nama_jadwal']; ?> (<?php echo $jadwal['tahun_anggaran']; ?>)</h1>
    <div class="action-section">
</div>
<div class="wrap-table">
    <table id="table_dokumen_skpd" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">N0</th>
                <th class="text-center">NAMA SKPD</th>
                <th class="text-center">NILAI USULAN</th>
                <?php
                    if (!empty($get_nama_komponen)) {
                        foreach ($get_nama_komponen as $komponen) {
                            echo '<th class="text-center">' . $komponen['nama'] . '</th>';
                        }
                    }
                ?>
                <th class="text-center">NILAI PENETAPAN</th>
                <th class="text-center">AKSI</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    jQuery(document).ready(function() {
        getTableSkpd();
        run_download_excel_sakip();
        let dataHitungMundur = {
            'jenisJadwal': <?php echo json_encode(ucwords($jenis_jadwal)); ?>,
            'namaJadwal': <?php echo json_encode(ucwords($nama_jadwal)); ?>,
            'mulaiJadwal': <?php echo json_encode($mulai_jadwal); ?>,
            'selesaiJadwal': <?php echo json_encode($selesai_jadwal); ?>,
            'thisTimeZone': <?php echo json_encode($timezone); ?>
        };
        penjadwalanHitungMundur(dataHitungMundur);
    });

    function getTableSkpd() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_skpd_pengisian_lke',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $id_jadwal; ?>,
                tahun_anggaran: <?php echo $jadwal['tahun_anggaran']; ?>,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#table_dokumen_skpd tbody').html(response.data);
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