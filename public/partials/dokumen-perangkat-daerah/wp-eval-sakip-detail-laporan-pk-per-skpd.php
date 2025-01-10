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
$current_user = wp_get_current_user();
$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);
$pemda = explode(" ", $nama_pemda);
$pemda = end($pemda);

$skpd = array();
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
$nama_skpd = (!empty($skpd)) ? $skpd['nama_skpd'] : '';
$alamat_kantor = (!empty($skpd)) ? $skpd['alamat_kantor'] : '';

$error_api = array(
    'status' => 0,
    'message' => 'Berhasil Get API'
);
if(!empty($nip)){
    $data_satker = $wpdb->get_row(
        $wpdb->prepare("
        SELECT 
            p.*,
            ds.nama AS nama_bidang
        FROM 
            esakip_data_pegawai_simpeg p
        LEFT JOIN
            esakip_data_satker_simpeg ds
        ON ds.satker_id = p.satker_id
        WHERE p.nip_baru=%d
          AND p.active = 1
    ", $nip),
        ARRAY_A
    );

    $data_atasan = array();
    $cek_kepala_skpd = 0;
    $cek_nama_kepala_daerah = 0;
    $dataPegawai = array();
    $nama_golruang = '';
    $nama_golruang_atasan = '';
    if(!empty($data_satker)){
        $nama_pegawai = (!empty($data_satker)) ? $data_satker['nama_pegawai'] : '';
        $nip_pegawai = (!empty($data_satker)) ? $data_satker['nip_baru'] : '';
        $bidang_pegawai = (!empty($data_satker)) ? $data_satker['nama_bidang'] : '';
        $jabatan_pegawai = (!empty($data_satker)) ? $data_satker['jabatan'].' '.$data_satker['nama_bidang'] : '';

        $cek_kepala = strlen($data_satker['satker_id']);
        if($cek_kepala == 2 && $data_satker['tipe_pegawai_id'] == 11){
            $cek_kepala_skpd = 1;
            $nama_kepala_daerah = get_option('_crb_kepala_daerah');
            if(!empty($nama_kepala_daerah)){
                $cek_nama_kepala_daerah = 1;
            }
            $cek_status = stripos($nama_pemda, 'kabupaten');
            if ($cek_status !== false) {
                $jabatan_kepala = 'BUPATI';
            }else{
                $jabatan_kepala = 'WALIKOTA';
            }
            $data_atasan = [
                'nama_pegawai' => $nama_kepala_daerah,
                'jabatan' => $jabatan_kepala.' '.strtoupper($pemda),
                'status_kepala' => 'kepala_daerah'
            ];
        }
        if(empty($data_atasan)){
            if($data_satker['tipe_pegawai_id'] == 11){
                $satker_id_atasan = substr($data_satker['satker_id'], 0, -2);
                $data_atasan = $wpdb->get_row($wpdb->prepare("
                    SELECT
                        p.*,
                        ds.nama AS nama_bidang
                    FROM
                        esakip_data_pegawai_simpeg p
                    LEFT JOIN 
                        esakip_data_satker_simpeg ds
                    ON 
                        ds.satker_id = p.satker_id
                    WHERE
                        p.satker_id=%s AND 
                        p.tipe_pegawai_id=%d AND 
                        p.active=1
                ", $satker_id_atasan, 11), ARRAY_A);
            }else{
                $data_atasan = $wpdb->get_row($wpdb->prepare("
                    SELECT
                        p.*,
                        ds.nama AS nama_bidang
                    FROM
                        esakip_data_pegawai_simpeg p
                    LEFT JOIN
                        esakip_data_satker_simpeg ds
                    ON
                        ds.satker_id = p.satker_id
                    WHERE
                        p.satker_id=%s AND 
                        p.tipe_pegawai_id=%d AND 
                        p.active=1
                ", $data_satker['satker_id'], 11), ARRAY_A);
            }
        }
        $nama_pegawai_atasan = (!empty($data_atasan['nama_pegawai'])) ? $data_atasan['nama_pegawai'] : '';
        $nip_pegawai_atasan = (!empty($data_atasan['nip_baru'])) ? $data_atasan['nip_baru'] : '';
        if(!empty($data_atasan['status_kepala']) && !empty($data_atasan['jabatan'])){
            $jabatan_pegawai_atasan = $data_atasan['jabatan'];
        }else if(!empty($data_atasan['jabatan'])){
            $jabatan_pegawai_atasan = $data_atasan['jabatan'].' '.$data_atasan['nama_bidang'];
        }else{
            $jabatan_pegawai_atasan = '';
        }

        if(empty($data_atasan['status_kepala'])){
            $path = 'api/pegawai/'.$nip_pegawai_atasan.'/jabatan';
            $option = array(
                'url' => get_option('_crb_url_api_simpeg').$path,
                'type' => 'get',
                'header' => array('Authorization: Basic ' . get_option('_crb_authorization_api_simpeg'))
            );

            $response = $this->functions->curl_post($option);

            if(empty($response)){
                $error_api = array(
                    'status' => 1,
                    'message' => 'Respon API kosong!'
                );
            }else if($response == 'Unauthorized'){
                $error_api = array(
                    'status' => 1,
                    'message' => $response.' '.json_encode($opsi)
                );
            }

            $dataPegawaiAtasan = json_decode($response, true);
            if(!empty($dataPegawaiAtasan[0]['nmgolruang'])){
                $nama_golruang_atasan = $dataPegawaiAtasan[0]['nmgolruang'];
            }
        }
    }else{
        echo "Data Pegawai Tidak Ditemukan!";
        die();   
    }

    $path = 'api/pegawai/'.$nip.'/jabatan';
    $option = array(
        'url' => get_option('_crb_url_api_simpeg').$path,
        'type' => 'get',
        'header' => array('Authorization: Basic ' . get_option('_crb_authorization_api_simpeg'))
    );

    $response = $this->functions->curl_post($option);

    if(empty($response)){
        $error_api = array(
            'status' => 1,
            'message' => 'Respon API kosong!'
        );
    }else if($response == 'Unauthorized'){
        $error_api = array(
            'status' => 1,
            'message' => $response.' '.json_encode($opsi)
        );
    }

    $dataPegawai = json_decode($response, true);
    if(!empty($dataPegawai[0]['nmgolruang'])){
        $nama_golruang = $dataPegawai[0]['nmgolruang'];
    }

    if (json_last_error() !== JSON_ERROR_NONE) {
        $error_api = array(
            'status' => 1,
            'message' => "Terjadi kesalahan ketika mengakses API, Error : ". json_last_error_msg()
        );
    }
}

$logo_pemda = get_option('_crb_logo_dashboard');
if(empty($logo_pemda)){
    $logo_pemda = '';
}

?>
<style type="text/css">
    body{
        font-size: 16px;
        line-height: 24px;
    }
    @media print {
  		.page-print {
  			max-width: 900px !important;
  			height: auto !important;
            margin: 0 auto;
            /* font-size: 12pt; */
  		}

        .f-12 {
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }
  		@page {size: portrait;}
  		#action-sakip, .site-header, .site-footer {
  			display: none;
  		}

        .break-print {
			break-after: page;
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
    
    .page-print { 
        font-family: Arial, Helvetica, sans-serif;
        margin-right: auto;
        margin-left: auto;
        background-color: var(--white-color);
        padding: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15)
    }

    .page-print p {
        margin: 0pt;
    }

    .page-print table, td {
        border: none;
    }

    #table-1 tr td:first-child {
        padding-left: 0;
    }

    #table-1 td:nth-child(1){
        width: 130px;
    }

    #table-1 td:nth-child(2){
        width: 0%;
    }

    tr, td {
        vertical-align: top;
    }

    .ttd-pejabat {
        padding: 0; 
        font-weight: 700; 
        text-decoration:underline; 
        width: 50%;
    }
    .title-laporan {
        font-weight: 700;
        font-size: 16pt;
    }
    .title-pk-1 {
        font-size: 14pt;
    }
    .title-pk-2 {
        font-size: 16pt;
        font-weight: 700;
    }

</style>

<div class="container-md mx-auto" style="width: 900px;">
    <div class="text-center" id="action-sakip">
        <button class="btn btn-primary btn-large" onclick="window.print();"><i class="dashicons dashicons-printer"></i> Cetak / Print</button><br>
    </div>
    <div id="laporan_pk" class="text-center page-print">
        <div class="row" style="border-bottom: 7px solid;">
            <div class="col-2" style="display: flex; align-items: center; height: 200px;">
                <?php if(!empty($logo_pemda)) : ?>
                    <img style="max-width: 100%; height: auto;" src="<?php echo $logo_pemda; ?>" alt="Logo Pemda">
                <?php endif; ?>
            </div>
            <div class="col my-auto">
                <p class="title-pk-1">PEMERINTAH <?php echo strtoupper($nama_pemda); ?></p>
                <p class="title-pk-2"><?php echo strtoupper($nama_skpd); ?></p>
                <p class="title-pk-1"><?php echo $alamat_kantor ?></p>
            </div>
            <div class="col-1"></div>
        </div>
        <p class="title-laporan mt-3 mb-2">PERJANJIAN KINERJA TAHUN <?php echo $input['tahun']; ?></p>
        <p class="text-left f-12 mt-5">Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil, kami yang bertanda tangan dibawah ini :</p>
        <table id="table-1" class="text-left f-12">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><?php echo $nama_pegawai; ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td><?php echo $jabatan_pegawai; ?></td>
            </tr>
            <tr>
                <td colspan="3">Selanjutnya disebut pihak pertama</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><?php echo $nama_pegawai_atasan; ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td><?php echo $jabatan_pegawai_atasan; ?></td>
            </tr>
        </table>
        <p class="text-left f-12">Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian target tersebut menjadi tanggung jawab kami.</p>
        </br>
        <p class="text-left f-12">Pihak kedua akan memberikan supervisi yang diperlukan serta akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka memberikan penghargaan dan sanksi.</p>
        <table id="table_data_pejabat" class="f-12">
            <thead>
                <tr class="text-center">
                    <td></td>
                    <td><?php echo $pemda; ?>,&emsp;&emsp;-&emsp;&emsp;&emsp;&emsp;&emsp;-&ensp;<?php echo $input['tahun']; ?></td>
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
                        <?php echo $nama_pegawai_atasan; ?>
                    </td>
                    <td class="ttd-pejabat">
                        <?php echo $nama_pegawai; ?>
                    </td>
                </tr>
                <tr class="text-center">
                    <td style="padding: 0;">
                        <?php if(empty($data_atasan['status_kepala'])) : ?>
                            <?php echo $nama_golruang_atasan; ?>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 0;">
                        <?php echo $nama_golruang; ?>
                    </td>
                </tr>
                <tr class="text-center">
                    <td style="padding: 0;">
                        <?php if(empty($data_atasan['status_kepala'])) : ?>
                            NIP. <?php echo $nip_pegawai_atasan; ?>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 0;">
                        NIP. <?php echo $nip_pegawai; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="break-print"></div>
    <div class="page-print mt-5 text-center">
        <p class="title-laporan mt-3">PERJANJIAN KINERJA TAHUN <?php echo $input['tahun']; ?></p>
        <p class="title-laporan"><?php echo $bidang_pegawai; ?></p>
        <table id="table_data_pejabat" class="f-12 mt-5">
                <thead>
                    <tr class="text-center">
                        <td></td>
                        <td><?php echo $pemda; ?>,&emsp;&emsp;-&emsp;&emsp;&emsp;&emsp;&emsp;-&ensp;<?php echo $input['tahun']; ?></td>
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
                            <?php echo $nama_pegawai_atasan; ?>
                        </td>
                        <td class="ttd-pejabat">
                            <?php echo $nama_pegawai; ?>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td style="padding: 0;">
                            <?php if(empty($data_atasan['status_kepala'])) : ?>
                                <?php echo $nama_golruang_atasan; ?>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 0;">
                            <?php echo $nama_golruang; ?>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td style="padding: 0;">
                            <?php if(empty($data_atasan['status_kepala'])) : ?>
                                NIP. <?php echo $nip_pegawai_atasan; ?>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 0;">
                            NIP. <?php echo $nip_pegawai; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

<script>
    jQuery(document).ready(function() {
        let cek_kepala_skpd = <?php echo $cek_kepala_skpd; ?>;
        let cek_nama_kepala_daerah = <?php echo $cek_nama_kepala_daerah; ?>;
        if(cek_kepala_skpd == 1 && (cek_nama_kepala_daerah == 0)){
            alert("Harap Isi Nama Kepala Daerah Di Esakip Options!");
        }
        let status_error_api = <?php echo $error_api['status']; ?>;
        if(status_error_api == 1){
            console.log("<?php echo $error_api['message']; ?>");
        }
    });
</script>