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

$jadwal_rpjmd = $this->get_rpjmd_by_tahun($tahun_anggaran);

if (empty($jadwal_rpjmd)) {
    die('Jadwal RPJMD/RENSTRA terbuka tidak tersedia!');
}
$error_message = array();
$tahun_renstra_wp_sipd = $this->get_renstra_by_rpjmd_tahun($jadwal_rpjmd['id'], $tahun_anggaran);
if (empty($tahun_renstra_wp_sipd['id_jadwal_wp_sipd'])) {
    $tahun_renstra_wp_sipd['id_jadwal_wp_sipd'] = 0;
    array_push($error_message, 'Jadwal RENSTRA belum diset, Mohon hubungi admin!');
}
?>
<style>
    #tabel-cascading,
    #tabel-cascading td,
    #tabel-cascading table {
        padding: 0;
        border: 4px solid white;
        margin: 0;
        vertical-align: top;
    }

    #tabel-cascading>tbody>tr>td {
        padding: 10px;
    }

    #tabel-cascading button.btn.btn-lg.btn-info,
    #tabel-cascading button.btn.btn-lg.btn-warning {
        width: 100%;
        min-height: 75px;
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
        .hide_print,
        .site-header,
        .site-footer,
        #ast-scroll-top {
            display: none;
        }
    }
</style>

<body>
    <div class="mb-5 text-center hide_print">
        <h1 class="fw-bold my-4">Cascading <br>
            <?php echo $jadwal_rpjmd['nama_jadwal']; ?>
            ( <?php echo $jadwal_rpjmd['tahun_anggaran'] . ' - ' . $jadwal_rpjmd['tahun_selesai_anggaran']; ?> )
        </h1>
    </div>

    <div class="p-4">
        <!-- Error Message -->
        <?php if (!empty($error_message) && is_array($error_message)) : ?>
            <div class="alert alert-danger mt-3 hide_print">
                <ul class="mb-0">
                    <?php echo implode('', array_map(fn($msg) => "<li>{$msg}</li>", $error_message)); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="hide_print mb-5">
            <h2 class="text-center m-2">Cascading Pemerintah Daerah</h2>
            <table id="tableDataPemda" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 30px;">No</th>
                        <th class="text-center">Judul</th>
                        <th class="text-center">Tujuan RPJMD/RPD</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div id="display_chart" class="p-3 mb-4" style="display: none;">
            <div id="cetak" title="Laporan Cascading" style="overflow: auto;">
                <div id="chart_div"></div>
            </div>
        </div>

        <div class="hide_print">
            <h2 class="text-center m-2">Cascading Perangkat Daerah</h2>
            <table id="tableDataOpd" class="table table-bordered">
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
</body>

<script>
    jQuery(document).ready(() => {
        //untuk fitur hide
        window.id_tujuan_global = '';
        getDataTable()
    });

    function getDataTable() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_datatable_cascading_publish',
                tahun_anggaran: <?php echo $tahun_anggaran; ?>,
                id_jadwal: <?php echo $jadwal_rpjmd['id']; ?>,
                id_jadwal_wp_sipd: <?php echo $tahun_renstra_wp_sipd['id_jadwal_wp_sipd']; ?>
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
                        "destroy": true,
                        "language": {
                            "emptyTable": "Tidak ada data yang tersedia"
                        }
                    });


                    jQuery('#tableDataPemda tbody').html(response.data_pemda);
                    jQuery('#tableDataPemda').DataTable({
                        "pageLength": -1,
                        "lengthMenu": [
                            [-1, 10, 25, 50],
                            ["All", 10, 25, 50]
                        ],
                        "destroy": true,
                        "language": {
                            "emptyTable": "Tidak ada data yang tersedia"
                        }
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

    function view_cascading(id_tujuan) {
        //pencet tombol lagi untuk hide
        if (id_tujuan == id_tujuan_global) {
            id_tujuan_global = '';
            jQuery('#display_chart').hide();
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'view_cascading_pemda',
                id_jadwal: <?php echo $jadwal_rpjmd['id']; ?>,
                id: id_tujuan,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#chart_div').html(response.html);
                    if (id_tujuan_global == '') {
                        id_tujuan_global = id_tujuan;
                        jQuery('#display_chart').show();
                    } else {
                        id_tujuan_global = id_tujuan;
                        jQuery('#display_chart').show();
                    }
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