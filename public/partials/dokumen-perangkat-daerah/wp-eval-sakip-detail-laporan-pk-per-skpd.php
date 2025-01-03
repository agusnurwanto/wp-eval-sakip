<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2022',
), $atts);

$id_skpd = $nip = '';
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}
if (!empty($_GET) && !empty($_GET['nip'])) {
    $nip = $_GET['nip'];
}
$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$skpd = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        u.nama_skpd,
        d.alamat_kantor
    FROM 
        esakip_data_unit u
    LEFT JOIN 
        esakip_detail_data_unit d
    ON d.id_skpd = u.id_skpd
    WHERE u.id_skpd=%d
      AND u.tahun_anggaran=%d
      AND u.active = 1
", $id_skpd, $tahun_anggaran_sakip),
    ARRAY_A
);

$alamat = '';
if(!empty($nip)){
    $data_satker = $wpdb->get_row(
        $wpdb->prepare("
        SELECT 
            *
        FROM 
            esakip_data_pegawai_simpeg
        WHERE nip_baru=%d
          AND active = 1
    ", $nip),
        ARRAY_A
    );

    if(!empty($data_satker)){
        $alamat = $data_satker['alamat_kantor'];
    }
}

$current_user = wp_get_current_user();
$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);

?>
<style type="text/css">
    body{
        /* font-size: 18px; */
        font-size: 12pt;
    }
    @media print {
  		#cetak {
  			max-width: auto !important;
  			height: auto !important;
  		}
  		@page {size: portrait;}
  		#action-sakip, .site-header, .site-footer {
  			display: none;
  		}
  	}

    #action-sakip {
  		padding-top: 20px;
  	}

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
    #table_dokumen_perjanjian_kinerja th {
        vertical-align: middle;
    }
    
    #laporan_pk { 
        /* font-family:'Times New Roman'; */
        font-family: Arial, Helvetica, sans-serif;
        margin-right: auto;
        margin-left: auto;
        background-color: var(--white-color);
        padding: 70px;
        /* font-size:12pt;  */
        /* width: 210mm;
        height: 330mm; */
    }

    #laporan_pk p {
        margin: 0pt;
    }

    #laporan_pk table, td {
        border: none;
    }

    #table-1 tr td:first-child {
        padding-left: 0;
    }

    .ttd-pejabat {
        padding: 0; 
        font-weight: 700; 
        text-decoration:underline; 
        width: 50%;
    }
</style>

<!-- Table -->
<div class="container-md">
    <div class="text-center" id="action-sakip">
        <button class="btn btn-primary btn-large" onclick="window.print();"><i class="dashicons dashicons-printer"></i> Cetak / Print</button><br>
    </div>
    <div id="cetak" class="d-flex justify-content-center" style="padding: 10px;margin:0 0 3rem 0;">
        <div id="laporan_pk" class="text-center">
            <!-- <div class="card-body"> -->
                <p>PEMERINTAH <?php echo strtoupper($nama_pemda); ?></p>
                <p><?php echo strtoupper($skpd['nama_skpd']); ?></p>
                <?php echo $alamat ?>
                <br>
                <br>
                <p>PERJANJIAN KINERJA TAHUN <?php echo $input['tahun']; ?></p>
                <p class="text-left">Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil, kami yang bertanda tangan dibawah ini :</p>
                <table id="table-1" class="text-left">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>VERAWATI SETYONINGRUM, S.STP., M.Si</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>VKEPALA BAGIAN ORGANISASI</td>
                    </tr>
                    <tr>
                        <td colspan="3">Selanjutnya disebut pihak pertama</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>SUCI LESTARI, S.H.</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>ASISTEN ADMINISTRASI UMUM SETDA KAB.MAGETAN</td>
                    </tr>
                </table>
                <p class="text-left">Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian target fahéijl tersebutmenjadi tanggung jawab kami.</p>
                </br>
                <p class="text-left">Pihak kedua akan memberikan supervisi yang diperlukan serta akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan Adiam rangka memberikan penghargaan dan sanksi</p>
                <table id="table_data_pejabat">
                    <thead>
                        <tr class="text-center">
                            <td></td>
                            <td>Magetan, Januari 2024</td>
                        </tr>
                        <tr class="text-center">
                            <td>Pihak Kedua,</td>
                            <td>Pihak Pertama,</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="height: 7em;">
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="text-center">
                            <td class="ttd-pejabat">
                                SUCI LESTARI, S.H.
                            </td>
                            <td class="ttd-pejabat">
                                VERAWATI SETYONINGRUM, S.STP., M.Si
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td style="padding: 0;">
                                Pembina Utama Muda
                            </td>
                            <td style="padding: 0;">
                                Pembina
                            </td>
                        </tr>
                    </tbody>
                </table>
            <!-- </div> -->
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function() {
        // getLaporanPerjanjianKinerja();
    });

    function getLaporanPerjanjianKinerja() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_perjanjian_kinerja',
                api_key: esakip.api_key,
                id_skpd: <?php echo $id_skpd; ?>,
                tahun_anggaran: '<?php echo $input['tahun'] ?>'
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if(
                    response.data_esr 
                    && response.data_esr.status == 'error'
                ){
                    alert(response.data_esr.message);
                }
                if(response.status_mapping_esr){
                    tahun_anggaran_periode_dokumen = response.tahun_anggaran_periode_dokumen;
                    let body_non_esr_lokal=``;
                    if(response.non_esr_lokal.length > 0){
                        response.non_esr_lokal.forEach((value, index) => {
                            body_non_esr_lokal+=`
                                <tr>
                                    <td class="text-center" data-upload-id="${value.upload_id}">${index+1}.</td>
                                    <td>${value.nama_file}</td>
                                    <td>${value.keterangan}</td>
                                    <td class="text-center"><a class="btn btn-sm btn-info" href="${value.path}" title="Lihat Dokumen" target="_blank"><span class="dashicons dashicons-visibility"></span></a></td>
                                </tr>
                            `;
                        });
                        jQuery("#table_non_esr_lokal tbody").html(body_non_esr_lokal);
                    }
                    jQuery("#btn-sync-to-esr").show();
                    jQuery("#check-list-esr").show();
                    jQuery("#non_esr_lokal").show();
                }
                if (response.status === 'success') {
                    jQuery('#table_dokumen_perjanjian_kinerja tbody').html(response.data);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data Perjanjian Kinerja!');
            }
        });
    }
</script>