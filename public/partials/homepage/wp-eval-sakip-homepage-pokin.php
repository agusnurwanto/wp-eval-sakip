<?php
// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}
if (empty($_GET) && empty($_GET['tahun'])) {
    die('Parameter tidak valid!');
}
global $wpdb;
$tahun_anggaran = intval($_GET['tahun']);

$jadwal_rpjmd = $wpdb->get_row(
    $wpdb->prepare("
        SELECT 
            id,
            nama_jadwal,
            nama_jadwal_renstra,
            tahun_anggaran,
            lama_pelaksanaan,
            tahun_selesai_anggaran
        FROM esakip_data_jadwal
        WHERE tipe = %s
          AND status = %d
          AND tahun_anggaran <= %d
          AND tahun_selesai_anggaran >= %d
    ", 'RPJMD', 1, $tahun_anggaran, $tahun_anggaran),
    ARRAY_A
);
if (empty($jadwal_rpjmd)) {
    die('Jadwal RPJMD/RENSTRA terbuka tidak tersedia!');
}
?>

<body>
    <div class="pb-4 mb-5 text-center">
        <h1 class="fw-bold my-4">Pohon Kinerja</h1>
        <h2 class="fw-semibold text-uppercase mb-2"><?php echo $jadwal_rpjmd['nama_jadwal']; ?></h2>
        <h3 class="text-muted fst-italic">( <?php echo $jadwal_rpjmd['tahun_anggaran'] . ' - ' . $jadwal_rpjmd['tahun_selesai_anggaran']; ?> )</h3>
    </div>
    <div class="wrap-table m-4">
        <h5 class="text-center">Pemerintah Daerah</h5>
        <table id="tableDataPemda">
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">No</th>
                    <th class="text-center">Pokin Level 1</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="wrap-table m-4">
        <h5 class="text-center">Perangkat Daerah</h5>
        <table id="tableDataOpd">
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">No</th>
                    <th class="text-center">Perangkat Daerah</th>
                    <th class="text-center">Pokin Level 1</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</body>
<script>
    jQuery(document).ready(() => {
        getDataTable()
    });

    function getDataTable() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_datatable_pokin_publish',
                tahun_anggaran: <?php echo $tahun_anggaran; ?>,
                id_jadwal: <?php echo $jadwal_rpjmd['id']; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#tableDataOpd tbody').html(response.data);
                    jQuery('#tableDataOpd').DataTable({
                        "pageLength": -1, 
                        "lengthMenu": [
                            [-1, 10, 25, 50],
                            ["All", 10, 25, 50]
                        ], 
                        "destroy": true
                    });
                    jQuery('#tableDataPemda tbody').html(response.data_pemda);
                    jQuery('#tableDataPemda').DataTable({
                        "pageLength": -1, 
                        "lengthMenu": [
                            [-1, 10, 25, 50],
                            ["All", 10, 25, 50]
                        ], 
                        "destroy": true
                    });
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
</script>