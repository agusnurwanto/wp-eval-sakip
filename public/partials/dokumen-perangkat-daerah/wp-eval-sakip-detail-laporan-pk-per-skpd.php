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
        if(empty($data_atasan['status_kepala'])){
            $path = 'api/pegawai/'.$data_atasan['nip_baru'].'/jabatan';
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
    // $logo_pemda = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20200%20200%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_194359332f4%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_194359332f4%22%3E%3Crect%20width%3D%22200%22%20height%3D%22200%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2274.421875%22%20y%3D%22104.5%22%3E200x200%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';
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
                <p class="title-pk-2"><?php echo strtoupper($skpd['nama_skpd']); ?></p>
                <p class="title-pk-1"><?php echo $skpd['alamat_kantor'] ?></p>
            </div>
            <div class="col-1"></div>
        </div>
        <p class="title-laporan mt-3 mb-2">PERJANJIAN KINERJA TAHUN <?php echo $input['tahun']; ?></p>
        <p class="text-left f-12 mt-5">Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil, kami yang bertanda tangan dibawah ini :</p>
        <table id="table-1" class="text-left f-12">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><?php echo $data_satker['nama_pegawai']; ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td><?php echo $data_satker['jabatan']; ?> <?php echo $data_satker['nama_bidang']; ?></td>
            </tr>
            <tr>
                <td colspan="3">Selanjutnya disebut pihak pertama</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><?php echo $data_atasan['nama_pegawai']; ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <?php if(empty($data_atasan['status_kepala'])) : ?>
                    <td><?php echo $data_atasan['jabatan']; ?> <?php echo $data_atasan['nama_bidang']; ?></td>
                <?php else : ?>
                    <td><?php echo $data_atasan['jabatan']; ?></td>
                <?php endif; ?>
            </tr>
        </table>
        <p class="text-left f-12">Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian target fahéijl tersebutmenjadi tanggung jawab kami.</p>
        </br>
        <p class="text-left f-12">Pihak kedua akan memberikan supervisi yang diperlukan serta akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan Adiam rangka memberikan penghargaan dan sanksi</p>
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
                        <?php echo $data_atasan['nama_pegawai']; ?>
                    </td>
                    <td class="ttd-pejabat">
                        <?php echo $data_satker['nama_pegawai']; ?>
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
                            NIP. <?php echo $data_atasan['nip_baru']; ?>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 0;">
                        NIP. <?php echo $data_satker['nip_baru']; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="break-print"></div>
    <div class="page-print mt-5 text-center">
        <p class="title-laporan mt-3">PERJANJIAN KINERJA TAHUN <?php echo $input['tahun']; ?></p>
        <p class="title-laporan"><?php echo $data_satker['nama_bidang']; ?></p>
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
                            <?php echo $data_atasan['nama_pegawai']; ?>
                        </td>
                        <td class="ttd-pejabat">
                            <?php echo $data_satker['nama_pegawai']; ?>
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
                                NIP. <?php echo $data_atasan['nip_baru']; ?>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 0;">
                            NIP. <?php echo $data_satker['nip_baru']; ?>
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