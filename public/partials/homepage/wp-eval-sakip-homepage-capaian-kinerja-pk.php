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

    /* Mild Green (Pastel Success) - For achievement >= 75% */
    .bg-success-mild {
        background-color: #d4edda;
        color: #155724;
    }

    /* Mild Yellow (Pastel Warning) - For achievement >= 50% and < 75% */
    .bg-warning-mild {
        background-color: #fff3cd;
        color: #856404;
    }

    /* Mild Red (Pastel Danger) - For achievement < 50% */
    .bg-danger-mild {
        background-color: #f8d7da;
        color: #721c24;
    }

    :root {
        --zoom-scale: 1.0; 
    }

    #main-content-pk {
        transform: scale(var(--zoom-scale));
        transform-origin: top left;
        transition: transform 0.2s ease-out;
    }
</style>
<div class="mb-5 text-center hide_print">
    <h1 class="fw-bold my-4">
        Capaian Perjanjian Kinerja (PK)
        <br>
        <?php echo get_option('_crb_nama_pemda') . '<br>Tahun Anggaran ' . $tahun_anggaran; ?>
    </h1>
    <div id="zoom-controls" class="btn-group" role="group" aria-label="Zoom controls">
        <button id="zoom-out" type="button" class="btn btn-secondary">-</button>
        <button id="zoom-label" type="button" class="btn btn-info disabled" style="width: 70px;">100%</button>
        <button id="zoom-in" type="button" class="btn btn-secondary">+</button>
    </div>
</div>
<div class="p-1" id="main-content-pk">
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

    <div class="hide-print" id="catatan" style="max-width: 900px; margin: 40px auto; padding: 20px; border: 1px solid #e5e5e5; border-radius: 8px; background-color: #f9f9f9;">
        <h4 style="font-weight: bold; margin-bottom: 20px; color: #333;">Catatan</h4>
        <ul style="list-style-type: disc; padding-left: 20px; line-height: 1.6; color: #555;">
            <li>Kolom capaian warna <strong class="bg-success-mild">Hijau</strong> berarti persentase melebihi atau sama dengan 75% <strong>( >= 75% )</strong></li>
            <li>Kolom capaian warna <strong class="bg-warning-mild">Kuning</strong> berarti persentase kurang dari 75% <strong>( < 75% )</strong></li>
            <li>Kolom capaian warna <strong class="bg-danger-mild">Merah</strong> berarti persentase kurang dari 50% <strong>( < 50% )</strong></li>
        </ul>
    </div>
</div>
<script>
    jQuery(document).ready(() => {
        getTable();
    });

    function getTable() {
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
                    jQuery('[data-toggle="tooltip"]').tooltip();
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

    const zoomInBtn = document.getElementById('zoom-in');
    const zoomOutBtn = document.getElementById('zoom-out');
    const zoomLabel = document.getElementById('zoom-label');
    const rootElement = document.documentElement; // Untuk mengakses variabel CSS :root
    const mainContent = document.getElementById('main-content-pk');

    let currentScale = 0.7;
    const scaleStep = 0.1; // 10% per langkah
    const minScale = 0.5;  // Zoom minimum 50%
    const maxScale = 2.0;  // Zoom maksimum 200%

    function updateZoom(newScale) {
        newScale = Math.min(Math.max(newScale, minScale), maxScale);
        currentScale = newScale;
        rootElement.style.setProperty('--zoom-scale', currentScale);
        
        // Update label persentase
        const percentage = Math.round(currentScale * 100);
        zoomLabel.textContent = `${percentage}%`;

        const newPhysicalSize = 1 / currentScale;
        mainContent.style.width = `calc(100% * ${newPhysicalSize})`;
        // console.log('newPhysicalSize', newPhysicalSize);
    }

    zoomInBtn.addEventListener('click', () => {
        updateZoom(currentScale + scaleStep);
    });

    zoomOutBtn.addEventListener('click', () => {
        updateZoom(currentScale - scaleStep);
    });

    setTimeout(function(){
        updateZoom(currentScale);
    }, 5000);
</script>