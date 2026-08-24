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
    die('Jadwal RPJMD/RPD di tahun anggaran belum diset!');
}

$selected_jadwal = '';
if (!empty($_GET['id_jadwal'])) {
    $selected_jadwal = $_GET['id_jadwal'];
    $jadwal_rpjmd = $this->get_rpjmd_setting_by_id_jadwal($selected_jadwal);
    if (empty($jadwal_rpjmd)) {
        die('Jadwal RPJMD/RPD terpilih tidak tersedia!');
    }
}

$error_message = array();
if (empty($jadwal_rpjmd['id_jadwal_wp_sipd'])) {
    $jadwal_rpjmd['id_jadwal_wp_sipd'] = 0;
    array_push($error_message, 'Jadwal WP-SIPD belum diset, Mohon hubungi admin!');
}

$setting_jadwal_ids = $this->get_carbon_multiselect('crb_daftar_jadwal_rpjm_' . $tahun_anggaran);

if (!empty($setting_jadwal_ids[0]['value']) && empty($selected_jadwal)) {
    $button_html = '
        <button class="menu-btn btn-primary-custom" onclick="selectJadwal(' . esc_attr($jadwal_rpjmd['id']) . ')">
            ' . esc_html($jadwal_rpjmd['nama_jadwal']) . ' (' . esc_html($jadwal_rpjmd['tahun_anggaran']) . ' - ' . esc_html($jadwal_rpjmd['tahun_selesai_anggaran']) . ')
        </button>
    ';

    $all_jadwal_data = [];

    foreach ($setting_jadwal_ids as $id_jadwal) {
        $data_jadwal = $this->get_data_jadwal_by_id($id_jadwal);

        if (!empty($data_jadwal)) {
            $all_jadwal_data[] = $data_jadwal;
        }
    }

    if (!empty($all_jadwal_data)) {
        foreach ($all_jadwal_data as $v) {
            $button_html .= '
            <button class="menu-btn btn-primary-custom" onclick="selectJadwal(' . esc_attr($v['id']) . ')">
                ' . esc_html($v['nama_jadwal']) . ' (' . esc_html($v['tahun_anggaran']) . ' - ' . esc_html($v['tahun_selesai_anggaran']) . ')
            </button>';
        }
    }

    $page_cascading_publish = $this->functions->generatePage([
        'nama_page'   => 'Cascading',
        'content'     => '[cascading_publish]',
        'show_header' => 1,
        'post_status' => 'publish'
    ]);

    echo '
        <style>
            .menu-container {
                min-height: 90vh;
                background-color: #f8f9fa;
            }
            
            .menu-card {
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                padding: 3rem 2rem;
                max-width: 400px;
                width: 100%;
            }
            
            .menu-title {
                color: #2c3e50;
                font-weight: 600;
                margin-bottom: 2rem;
                font-size: 1.5rem;
            }
            
            .menu-btn {
                width: 100%;
                padding: 1rem;
                margin-bottom: 1rem;
                border: none;
                border-radius: 8px;
                font-size: 1rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            
            .btn-primary-custom {
                background-color: #3498db;
                color: white;
            }
            
            .btn-primary-custom:hover {
                background-color: #2980b9;
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
            }
        </style>
        <div class="container-fluid menu-container d-flex justify-content-center align-items-center">
            <div class="menu-card text-center">
                <h2 class="menu-title">Pilih Jadwal RPJM/RPD</h2>
                <div class="d-grid gap-3">
                    ' . $button_html . ' 
                </div>
            </div>
        </div>
        <script>
            function selectJadwal(id) {
                window.open(\'' . $page_cascading_publish['url'] . "&tahun=" . $tahun_anggaran . "&id_jadwal=" . '\' + id);
            }
        </script>';
        die();
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
                    <th class="text-center">Tujuan <?php echo strtoupper($jadwal_rpjmd['jenis_jadwal_khusus']); ?></th>
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
                id_jadwal_wp_sipd: <?php echo $jadwal_rpjmd['id_jadwal_wp_sipd']; ?>
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