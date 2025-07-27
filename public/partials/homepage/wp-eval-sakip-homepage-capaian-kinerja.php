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

$jadwal_rpjmd = $this->get_rpjmd_setting_by_tahun_anggaran($tahun_anggaran);

if (empty($jadwal_rpjmd)) {
    die('Jadwal RPJMD/RENSTRA terbuka tidak tersedia!');
}

$thead = '';
$thead_capaian = '';
for($i=1; $i<=$jadwal_rpjmd['lama_pelaksanaan']; $i++){
    $thead .= '
        <th class="text-center" colspan="2" style="width: 200px;">'.($jadwal_rpjmd['tahun_selesai_anggaran']-($jadwal_rpjmd['lama_pelaksanaan']-$i)).'</th>
    ';
    $thead_capaian .= '
        <th class="text-center" style="width: 100px;">Target</th>
        <th class="text-center" style="width: 100px;">Realisasi</th>
    ';
}
?>
<style>
    .table thead th{
        vertical-align: middle;
    }
</style>
<div class="mb-5 text-center hide_print">
    <h1 class="fw-bold my-4">Capaian Indikator Kinerja Utama (IKU)<br> <?php echo $jadwal_rpjmd['nama_jadwal']; ?>
        ( <?php echo $jadwal_rpjmd['tahun_anggaran'] . ' - ' . $jadwal_rpjmd['tahun_selesai_anggaran']; ?> )
    </h1>
</div>
<div class="p-4">
    <h2 class="text-center">Pemerintah Daerah</h2>
    <div class="mb-5">
        <table id="tableDataPemda" class="table table-bordered wrap-table">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" style="width: 30px;">No</th>
                    <th rowspan="2" class="text-center">Kinerja</th>
                    <th rowspan="2" class="text-center">Indikator</th>
                    <th rowspan="2" class="text-center">Satuan</th>
                    <?php echo $thead; ?>
                </tr>
                <tr>
                    <?php echo $thead_capaian; ?>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <h2 class="text-center">Perangkat Daerah</h2>
    <div>
        <table id="tableDataOpd" class="table table-bordered wrap-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">No</th>
                    <th class="text-center">Perangkat Daerah</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

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
                action: 'get_datatable_iku_publish',
                tahun_anggaran: <?php echo $tahun_anggaran; ?>,
                id_jadwal: <?php echo $jadwal_rpjmd['id']; ?>,
                lama_pelaksanaan: <?php echo $jadwal_rpjmd['lama_pelaksanaan']; ?>,
                id_jadwal_wp_sipd: <?php echo $jadwal_rpjmd['id_jadwal_wp_sipd']; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#tableDataOpd tbody').html(response.data);
                    jQuery('#tableDataPemda tbody').html(response.data_pemda);
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