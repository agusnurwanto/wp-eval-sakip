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
?>
<style>
    .table thead th {
        vertical-align: middle;
    }

    .table-sticky thead {
	    position: sticky;
	    top: -6px;
        background: #ffc491;
	}
</style>
<div class="mb-5 text-center hide_print">
    <h1 class="fw-bold my-4">
        Capaian Perjanjian Kinerja (PK)
        <br>
        <?php echo get_option('_crb_nama_pemda') . '<br>Tahun Anggaran ' . $tahun_anggaran; ?>
    </h1>
</div>
<div class="p-1">
    <h2 class="text-center">Perjanjian Kinerja Kepala Daerah</h2>
    <div class="mb-5 wrap-table" style="max-height: 90vh;">
        <table id="tableDataPemda" class="table table-bordered table-sticky" style="width: 2000px;">
            <thead>
                <tr>
                    <th scope="col" rowspan="2" class="text-center" style="width: 30px;">No</th>
                    <th scope="col" rowspan="2" class="text-center">Sasaran</th>
                    <th scope="col" rowspan="2" class="text-center">Indikator</th>
                    <th scope="col" rowspan="2" class="text-center">Satuan</th>
                    <th scope="col" rowspan="2" class="text-center">Target</th>
                    <th scope="col" colspan="4" class="text-center">Realisasi</th>
                    <th scope="col" rowspan="2" class="text-center">Capaian<br><small>%</small></th>
                    <th scope="col" rowspan="2" class="text-center">Perangkat Daerah<br><small>(Penanggung Jawab)</small></th>
                </tr>
                <tr>
                    <th scope="col" class="text-center" style="min-width: 65px;">TW 1</th>
                    <th scope="col" class="text-center" style="min-width: 65px;">TW 2</th>
                    <th scope="col" class="text-center" style="min-width: 65px;">TW 3</th>
                    <th scope="col" class="text-center" style="min-width: 65px;">TW 4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center" colspan="12">Tidak ada data tersedia</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h2 class="text-center">Perjanjian Kinerja Perangkat Daerah</h2>
    <div class="mb-2 wrap-table" style="max-height: 90vh;">
        <table id="tableDataOpd" class="table table-bordered table-sticky" style="width: 2000px;">
            <thead>
                <tr>
                    <th scope="col" rowspan="2" class="text-center">No</th>
                    <th scope="col" rowspan="2" class="text-center">Perangkat Daerah</th>
                    <th scope="col" rowspan="2" class="text-center">Sasaran Strategis</th>
                    <th scope="col" rowspan="2" class="text-center">Indikator Kinerja</th>
                    <th scope="col" rowspan="2" class="text-center">Satuan</th>
                    <th scope="col" rowspan="2" class="text-center">Target</th>
                    <th scope="col" colspan="4" class="text-center">Realisasi</th>
                    <th scope="col" rowspan="2" class="text-center">Capaian Kinerja<br><small>%</small></th>
                    <th scope="col" rowspan="2" class="text-center">Capaian Serapan Anggaran<br><small>%</small></th>
                    <th scope="col" rowspan="2" class="text-center">Capaian Kinerja Program<br><small>%</small></th>
                    <th scope="col" rowspan="2" class="text-center">Capaian Realisasi Fisik<br><small>%</small></th>
                </tr>
                <tr>
                    <th scope="col" class="text-center" style="min-width: 65px;">TW 1</th>
                    <th scope="col" class="text-center" style="min-width: 65px;">TW 2</th>
                    <th scope="col" class="text-center" style="min-width: 65px;">TW 3</th>
                    <th scope="col" class="text-center" style="min-width: 65px;">TW 4</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
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
                action: 'get_table_pk_publish',
                tahun_anggaran: <?php echo $tahun_anggaran; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status) {
                    jQuery('#tableDataOpd tbody').html(response.data);
                    jQuery('#tableDataPemda tbody').html(response.data_kepala_daerah);
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