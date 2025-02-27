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

$id_jadwal_rpjmd = $this->get_rpjmd_by_tahun($tahun_anggaran);
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
        WHERE id = %d
          AND status != 0
    ", $id_jadwal_rpjmd), //ganti
    ARRAY_A
);
if (empty($jadwal_rpjmd)) {
    die('Jadwal RPJMD/RENSTRA terbuka tidak tersedia!');
}
?>
<body>
    <div class="mb-5 text-center hide_print">
        <h1 class="fw-bold my-4">Pohon Kinerja</h1>
        <h2 class="fw-semibold text-uppercase mb-2">
            <?php echo $jadwal_rpjmd['nama_jadwal']; ?>
        </h2>
        <h3 class="text-muted fst-italic">
            ( <?php echo $jadwal_rpjmd['tahun_anggaran'] . ' - ' . $jadwal_rpjmd['tahun_selesai_anggaran']; ?> )
        </h3>
    </div>

    <div class="d-flex flex-lg-column align-items-center card-container">
        <div class="card shadow-lg p-3 mb-4 w-90">
            <h5 class="text-center m-2">Pemerintah Daerah</h5>
            <div class="table-container">
                <table id="tableDataPemda" class="table table-bordered wrap-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 30px;">No</th>
                            <th class="text-center">Pokin Level 1</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-lg p-3 mb-4 w-90">
            <h5 class="text-center m-2">Perangkat Daerah</h5>
            <div class="table-container">
                <table id="tableDataOpd" class="table table-bordered wrap-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 30px;">No</th>
                            <th class="text-center">Perangkat Daerah</th>
                            <th class="text-center">Pokin Level 1</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
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