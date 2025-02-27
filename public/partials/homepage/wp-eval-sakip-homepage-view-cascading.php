<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2024',
    'periode' => '1'
), $atts);

if (!empty($_GET) && !empty($_GET['id_jadwal'])) {
    $input['periode'] = $_GET['id_jadwal'];
}
$id_skpd = 0;
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

//jadwal renstra wpsipd
$api_params = array(
    'action'            => 'get_data_jadwal_wpsipd',
    'api_key'           => get_option('_crb_apikey_wpsipd'),
    'tipe_perencanaan'  => 'monev_renstra',
    'id_jadwal'         => $input['periode']
);

$response = wp_remote_post(
    get_option('_crb_url_server_sakip'),
    array(
        'timeout'   => 1000,
        'sslverify' => false,
        'body'      => $api_params
    )
);

$response = wp_remote_retrieve_body($response);

$data_jadwal_wpsipd = json_decode($response, true);
if (
    empty($response)
    || empty($data_jadwal_wpsipd)
) {
    die('<h1 class="text-center">Jadwal periode WP-SIPD tidak ditemukan!</h1>');
} else if (empty($id_skpd)) {
    die('<h1 class="text-center">ID OPD tidak boleh kosong!</h1>');
}

if (
    !empty($data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'])
    && $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'] > 1
) {
    $tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'];
} else {
    $tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_anggaran'] + $data_jadwal_wpsipd['data'][0]['lama_pelaksanaan'];
}

$skpd = $wpdb->get_row(
    $wpdb->prepare("
        SELECT 
            nama_skpd
        FROM esakip_data_unit
        WHERE id_skpd=%d
        AND tahun_anggaran=%d
        AND active = 1
    ", $id_skpd, $tahun_anggaran_sakip),
    ARRAY_A
);

$nama_jadwal = $data_jadwal_wpsipd['data'][0]['nama'] . ' ' . '(' . $data_jadwal_wpsipd['data'][0]['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ')';


$get_satker = $wpdb->get_results(
    $wpdb->prepare('
        SELECT 
            u.id_satker_simpeg,
            u.active,
            u.tahun_anggaran,
            u.id_skpd,
            s.id,
            s.satker_id,
            s.active
        FROM esakip_data_mapping_unit_sipd_simpeg AS u
        LEFT JOIN esakip_data_satker_simpeg AS s
               ON s.id = u.id_satker_simpeg
              AND s.active = u.active
        WHERE u.tahun_anggaran = %d
          AND u.id_skpd = %d
          AND u.active = 1
    ', $tahun_anggaran_sakip, $id_skpd),
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

    #tabel-cascading,
    #tabel-cascading td,
    #tabel-cascading table {
        padding: 0;
        border: 4px solid white;
        margin: 0;
        vertical-align: top;
    }

    #tabel-cascading>tbody>tr>td {
        padding: 2px;
    }

    #tabel-cascading div.btn.btn-lg.btn-info,
    #tabel-cascading div.btn.btn-lg.btn-warning,
    #tabel-cascading div.btn.btn-lg.btn-success,
    #tabel-cascading div.btn.btn-lg.btn-danger {
        width: 100%;
        min-height: 450px;
        font-weight: bold;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    #tabel-cascading div hr {
        background: #fff;
        width: 100%;
        margin: 10px 0;
    }

    #tabel-cascading div span.indikator {
        font-size: 14px;
        margin-top: 10px;
        display: block;
        width: 100%;
        max-width: 300px;
    }

    #tabel-cascading div span.nama_satker {
        font-size: 14px;
        margin-top: 10px;
        display: block;
        width: 100%;
        max-width: 300px;
    }

    #tabel-cascading-kegiatan,
    #tabel-cascading-kegiatan td,
    #tabel-cascading-kegiatan table {
        padding: 0;
        border: 4px solid white;
        margin: 0;
        vertical-align: top;
    }

    #tabel-cascading-kegiatan>tbody>tr>td {
        padding: 2px;
    }

    #tabel-cascading-kegiatan div.btn.btn-lg.btn-info,
    #tabel-cascading-kegiatan div.btn.btn-lg.btn-warning,
    #tabel-cascading-kegiatan div.btn.btn-lg.btn-success,
    #tabel-cascading-kegiatan div.btn.btn-lg.btn-secondary,
    #tabel-cascading-kegiatan div.btn.btn-lg.btn-primary,
    #tabel-cascading-kegiatan div.btn.btn-lg.btn-danger {
        width: 100%;
        min-height: 450px;
        font-weight: bold;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    #tabel-cascading-kegiatan div hr {
        background: #fff;
        width: 100%;
        margin: 10px 0;
    }

    #tabel-cascading-kegiatan div span.indikator {
        font-size: 14px;
        margin-top: 10px;
        display: block;
        width: 100%;
        max-width: 300px;
    }

    .indikator {
        font-size: 14px;
    }

    #tabel-cascading-kegiatan div span.nama_satker {
        font-size: 14px;
        margin-top: 10px;
        display: block;
        width: 100%;
        max-width: 300px;
    }

    .nama_satker {
        font-size: 14px;
    }

    @media print {
        #cetak {
            max-width: auto !important;
            height: auto !important;
        }

        #action-sakip,
        .site-header,
        .site-footer,
        #ast-scroll-top {
            display: none;
        }
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        border: 1px solid #ccc;
        padding: 0;
        height: 100px;
    }

    .button-container {
        display: flex;
        align-items: stretch;
        height: 100%;
    }

    .get_button {
        flex: 1;
        padding: 10px;
    }

    .view-kegiatan-button {
        background-color: transparent;
        border: none;
        cursor: pointer;
        padding: 5px;
    }

    .view-kegiatan-button i {
        font-size: 2rem;
        color: #fff;
    }

    .view-kegiatan-button:hover i {
        color: #f0f0f0;
    }
</style>

<!-- Table -->
<div class="container-md" id="container-table-cascading">
    <div class="cetak">
        <div class="mb-5 text-center hide_print">
            <h1 class="fw-bold my-4">Cascading</h1>
            <h2 class="fw-semibold text-uppercase mb-2">
                <?php echo $skpd['nama_skpd']; ?>
            </h2>
            <h3 class="fw-medium text-muted fst-italic">
                <?php echo $nama_jadwal; ?>
            </h3>

            <div class="mt-3" id="action-sakip">
                <a onclick="window.print();" href="#" class="btn btn-info">
                    <span class="dashicons dashicons-printer"></span> Cetak
                </a>
            </div>
        </div>

        <div style="overflow-x: auto; max-width: 100%;">
            <table id="tabel-cascading" style="min-width: 600px;">
                <tbody>
                    <tr>
                        <td class="text-center" style="width: 200px;">
                            <div class="btn btn-lg btn-info">TUJUAN</div>
                        </td>
                        <td class="text-center" colspan="0">
                            <div class="btn btn-lg btn-warning" style="text-transform:uppercase;"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <div class="btn btn-lg btn-info">SASARAN</div>
                        </td>
                        <td class="text-center" colspan="0">
                            <div class="btn btn-lg btn-warning" style="text-transform:uppercase;"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <div class="btn btn-lg btn-info">PROGRAM</div>
                        </td>
                        <td class="text-center" colspan="0">
                            <div class="btn btn-lg btn-warning"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="overflow-x: auto; max-width: 100%;">
            <table id="tabel-cascading-kegiatan" style="min-width: 600px;">
                <h2 class="text-center get-nama-program">Cascading Kegiatan dan Sub Kegiatan<br>PROGRAM: Program belum dipilih </h2>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        getTableCascading();
    });

    function getTableCascading() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_cascading_pd',
                id_jadwal: <?php echo $input['periode']; ?>,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#tabel-cascading tbody').html(response.data);
                    jQuery('.edit-pegawai-button').hide();
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

    function view_kegiatan(button, id, program, sasaran, tujuan) {
        let icon = jQuery(button).find('.visibility-icon');
        let body = jQuery('#tabel-cascading-kegiatan tbody');

        jQuery('.view-kegiatan-button').not(button).each(function() {
            let otherIcon = jQuery(this).find('.visibility-icon');
            if (jQuery(this).data('loaded')) {
                body.hide();
                otherIcon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
                jQuery(this).data('loaded', false);
            }
        });

        jQuery('#wrap-loading').show();

        if (icon.hasClass('dashicons-visibility')) {
            let value_program = jQuery(`#program-ke-${id}`).attr('data-nama-program') || 'Program belum dipilih';

            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_kegiatan_by_program',
                    id: id,
                    id_skpd: <?php echo $id_skpd; ?>,
                    tujuan: tujuan,
                    sasaran: sasaran,
                    program: program
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if (response.status === 'success') {
                        jQuery(".get-nama-program").html('Cascading Kegiatan dan Sub Kegiatan<br>PROGRAM: ' + value_program);
                        body.html(response.data).show();
                        jQuery(button).data('loaded', true);
                        icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
                        jQuery('.edit-pegawai-button').hide();
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
        } else {
            if (body.is(':visible')) {
                body.hide();
                icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            } else {
                body.show();
                icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            }

            jQuery(".get-nama-program").html(function() {
                if (!body.is(':visible')) {
                    return 'Cascading Kegiatan dan Sub Kegiatan<br>PROGRAM: Program belum dipilih';
                }
                return jQuery(".get-nama-program").html();
            });

            jQuery('#wrap-loading').hide();
        }
    }
</script>