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
$skpd = $wpdb->get_results($wpdb->prepare('
    select 
        * 
    from esakip_data_unit 
    where tahun_anggaran=%d
        AND active=1
        AND is_skpd=1
    order by kode_skpd ASC
', $tahun_anggaran), ARRAY_A);

$pk_pisah_page = $this->functions->generatePage(array(
    'nama_page' => 'Perjanjian Kinerja Format Pisah| Tahun Anggaran ' . $tahun_anggaran,
    'content' => '[perjanjian_kinerja_publik tahun_anggaran=' . $tahun_anggaran . ']',
    'show_header' => 1,
    'post_status' => 'publish'
));

$body = '';
$no = 0;
foreach($skpd as $opd){
    $no++;
    $url_pk_pisah_page = $pk_pisah_page['url'] . "&id_skpd=" . $opd['id_skpd'];
    $body .= '
        <tr>
            <td class="text-center">'.$no.'</td>
            <td>'.$opd['kode_skpd'].' '.$opd['nama_skpd'].'</td>
            <td class="text-center"><a class="btn btn-sm btn-info" href="'.$url_pk_pisah_page.'" target="_blank"><span class="dashicons dashicons-visibility"></span></a></td>
        </tr>
    ';
}
?>
<style>
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

    table.dataTable thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #ffffff; /* Sesuaikan warna background header agar tidak transparan */
    }
</style>

<div class="mb-5 text-center hide_print">
    <h1 class="fw-bold my-4">MONEV Rencana Kerja ( RENJA )<br>Tahun <?php echo $tahun_anggaran; ?></h1>
</div>

<div class="p-4">
    <table id="tableDataOpd" class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th class="text-center">Perangkat Daerah</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody><?php echo $body; ?></tbody>
    </table>
</div>

<script>
    jQuery(document).ready(() => {
        getDataTable()
    });

    function getDataTable() {
        jQuery('#tableDataOpd').DataTable({
            "pageLength": -1,
            "scrollY": "400px",
            "scrollCollapse": true,
            "ordering": false,
            "lengthMenu": [
                [-1, 10, 25, 50],
                ["All", 10, 25, 50]
            ],
            "destroy": true,
            "language": {
                "emptyTable": "Tidak ada data yang tersedia"
            }
        });
    }
</script>