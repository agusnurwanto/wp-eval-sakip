<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}
$input = shortcode_atts(array(
    'tahun_anggaran' => ''
), $atts);
$id_skpd = $_GET['id_skpd'];

?>
<style>
    .tr-tujuan {
        background: #0000ff1f;
    }

    .tr-sasaran {
        background: #ffff0059;
    }

    .tr-program,
    .tr-ind-program {
        background: #baffba;
    }

    .tr-kegiatan,
    .tr-ind-kegiatan {
        background: #13d0d03d;
    }

    .bawah {
        border-bottom: 1px solid #000;
    }

    .kiri {
        border-left: 1px solid #000;
    }

    .kanan {
        border-right: 1px solid #000;
    }

    .atas {
        border-top: 1px solid #000;
    }

    .text_tengah {
        text-align: center;
    }

    .text_kiri {
        text-align: left;
    }

    .text_kanan {
        text-align: right;
    }

    .text_blok {
        font-weight: bold;
    }

    #monev-body-renstra {
        word-break: break-word;
    }

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
<div id="body-iku">
    <h1 class="text-center">Memuat halaman ...</h1>
</div>
<script>
    jQuery(document).ready(() => {
        getDataTable()
    });

    function get_penanggung_jawab() {
        return jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_penanggung_jawab',
                id_skpd: <?php echo $id_skpd; ?>,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>
            },
            dataType: 'json',
        });
    }

    function get_html_pk_publik() {
        return jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_data_capaian_kinerja_publik',
                id_skpd: <?php echo $id_skpd; ?>,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>
            },
            dataType: 'json',
        });
    }

    async function getDataTable() {
        jQuery('#wrap-loading').show();

        try {
            const [penanggung_jawab, html_pk_publik] = await Promise.all([
                get_penanggung_jawab(),
                get_html_pk_publik()
            ]);

            if (html_pk_publik.status === 'success') {
                jQuery('#body-iku').html(html_pk_publik.html);

                if (penanggung_jawab.status && penanggung_jawab.data) {
                    renderPenanggungJawab(penanggung_jawab.data);
                }
                if (penanggung_jawab.status && penanggung_jawab.table) {
                    jQuery('#tableDataOpd tbody').html(penanggung_jawab.table);
                    jQuery('[data-toggle="tooltip"]').tooltip();
                }
            } else {
                jQuery('#body-iku').html('<h1 class="text-center">Gagal memuat data.</h1>');
            }
        } catch (error) {
            console.error('Terjadi kesalahan saat memuat data:', error);
            jQuery('#body-iku').html('<h1 class="text-center text-danger">Terjadi kesalahan.</h1>');   
        } finally {
            jQuery('#wrap-loading').hide();
        }
    }

    function renderPenanggungJawab(data) {
        Object.entries(data).forEach(([kodeCascading, daftarPegawai]) => {
            const elements = jQuery(`[data-kode-progkeg="${kodeCascading}"]`);

            if (elements.length > 0) {
                const listPJ = daftarPegawai
                    .map(pj => `<li><b>${pj.nama}</b> (${pj.jabatan})</li>`)
                    .join('');

                elements.each(function() {
                    jQuery(this).append(`<div class="mt-1"><ul>${listPJ}</ul></div>`);
                });
            }
        });
    }
</script>