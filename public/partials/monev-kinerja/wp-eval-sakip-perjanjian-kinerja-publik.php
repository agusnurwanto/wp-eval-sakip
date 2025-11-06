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
</style>
<div id="body-iku">
    <h1 class="text-center">Memuat halaman ...</h1>
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
                action: 'get_table_data_capaian_kinerja_publik',
                id_skpd: <?php echo $id_skpd; ?>,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#body-iku').html(response.html);
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