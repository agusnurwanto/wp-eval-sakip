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
    $data_detail = array(
        'nama_pegawai' => '',
        'nip_pegawai' => '',
        'bidang_pegawai' => '',
        'jabatan_pegawai' => '',
        'nama_golruang' => '',
        'gelar_depan' => '',
        'gelar_belakang' => ''
    );
    $data_detail_atasan = array(
        'nama_pegawai_atasan' => '',
        'jabatan_pegawai_atasan' => '',
        'nip_pegawai_atasan' => '',
        'nama_golruang_atasan' => '',
        'gelar_depan' => '',
        'gelar_belakang' => ''
    );
    if(!empty($data_satker)){
        $data_detail['nama_pegawai'] = $data_satker['nama_pegawai'];
        $data_detail['nip_pegawai'] = $data_satker['nip_baru'];
        $data_detail['bidang_pegawai'] = $data_satker['nama_bidang'];
        $data_detail['jabatan_pegawai'] = $data_satker['jabatan'].' '.$data_satker['nama_bidang'];

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
        $data_detail_atasan['nama_pegawai_atasan'] = (!empty($data_atasan['nama_pegawai'])) ? $data_atasan['nama_pegawai'] : '';
        $data_detail_atasan['nip_pegawai_atasan'] = (!empty($data_atasan['nip_baru'])) ? $data_atasan['nip_baru'] : '';
        if(!empty($data_atasan['status_kepala']) && !empty($data_atasan['jabatan'])){
            $data_detail_atasan['jabatan_pegawai_atasan'] = $data_atasan['jabatan'];
        }else if(!empty($data_atasan['jabatan'])){
            $data_detail_atasan['jabatan_pegawai_atasan'] = $data_atasan['jabatan'].' '.$data_atasan['nama_bidang'];
        }

        if(empty($data_atasan['status_kepala'])){
            $path = 'api/pegawai/'.$data_detail_atasan['nip_pegawai_atasan'].'/jabatan';
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
                $data_detail_atasan['nama_golruang_atasan'] = $dataPegawaiAtasan[0]['nmgolruang'];
                $data_detail_atasan['gelar_depan'] = $dataPegawaiAtasan[0]['gelar_depan'];
                $data_detail_atasan['gelar_belakang'] = $dataPegawaiAtasan[0]['gelar_belakang'];
            }
        }

        // hasil ploting di halaman RHK
        $data_ploting_rhk = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT 
                    id,
                    label,
                    level
                FROM
                    esakip_data_rencana_aksi_opd
                WHERE
                    id_skpd=%d AND
                    tahun_anggaran=%d AND
                    nip=%d AND
                    active=%d",
                $id_skpd, $input['tahun'], $data_satker['nip_baru'],1
            ), ARRAY_A
        );

        $html_lembar_2 = '';
        $html_sasaran = '';
        $html_program = '';
        $html_kegiatan = '';
        $html_sub_kegiatan = '';
        $data_anggaran = array(
            'sasaran' => array(),
            'program' => array(),
            'kegiatan' => array(),
            'sub_kegiatan' => array()
        );
        $no_2 = 1;
        if(!empty($data_ploting_rhk)){
            foreach ($data_ploting_rhk as $v_rhk) {
                $data_indikator_ploting_rhk = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT
                            indikator,
                            satuan,
                            target_awal,
                            target_akhir
                        FROM 
                            esakip_data_rencana_aksi_indikator_opd
                        WHERE 
                            id_renaksi=%d AND
                            active=%d",
                            $v_rhk['id'], 1
                    ), ARRAY_A
                );
                $html_indikator = '';
                $p_i = 1;
                if(!empty($data_indikator_ploting_rhk)){
                    if(count($data_indikator_ploting_rhk) > 0){
                        $p_i = count($data_indikator_ploting_rhk)+1;
                    }
                    foreach ($data_indikator_ploting_rhk as $v_indikator) {
                        $html_indikator .='<tr>
                            <td class="text-left">'.$v_indikator['indikator'].'</td>
                            <td class="text-left">'.$v_indikator['target_akhir'].' '.$v_indikator['satuan'].'</td></tr>';
                    }
                }
                $html_indikator_if = !empty($html_indikator) ? '' : "<td></td><td></td>";
                $html_lembar_2 .='<tr>
                    <td rowspan="'.$p_i.'">'.$no_2++.'</td>
                    <td rowspan="'.$p_i.'" class="text-left" style="max-width: 30rem;">'.$v_rhk['label'].'</td>
                    '.$html_indikator_if.'
                    </tr>';
                    
                $html_lembar_2 .= $html_indikator;

                $data_rhk_child = $wpdb->get_results($wpdb->prepare(
                    "SELECT 
                        *
                    FROM 
                        esakip_data_rencana_aksi_opd 
                    WHERE 
                        parent=%d AND 
                        level=%d AND
                        active=%d AND 
                        id_skpd=%d
                    ORDER BY id
                ",
                    $v_rhk['id'],
                    $v_rhk['level']+1,
                    1,
                    $id_skpd
                ), ARRAY_A);

                $jenis_level = array(
                    '1' => 'sasaran',
                    '2' => 'program',
                    '3' => 'kegiatan',
                    '4' => 'sub_kegiatan'
                );
                $no = 1;
                if(!empty($data_rhk_child)){
                    foreach ($data_rhk_child as $v_rhk_child) {
                        if(empty($data_anggaran[$jenis_level[$v_rhk_child['level']]][$v_rhk_child['kode_cascading_sub_kegiatan']])){
                            $data_anggaran[$jenis_level[$v_rhk_child['level']]][$v_rhk_child['kode_cascading_sub_kegiatan']] = array(
                                'nama' => $v_rhk_child['label_cascading_'.$jenis_level[$v_rhk_child['level']]],
                                'kode' => $v_rhk_child['kode_cascading_'.$jenis_level[$v_rhk_child['level']]],
                                'total_anggaran' => 0,
                            );
                        }

                        $data_indikator_anggaran = $wpdb->get_results($wpdb->prepare(
                            "SELECT
						        rencana_pagu
						    FROM 
                                esakip_data_rencana_aksi_indikator_opd 
						    WHERE 
                                id_renaksi=%d AND 
                                active = 1
						", $v_rhk_child['id']), ARRAY_A);
                        if(!empty($data_indikator_anggaran)){
                            foreach ($data_indikator_anggaran as $v_indikator_anggaran) {
                                $data_anggaran[$jenis_level[$v_rhk_child['level']]][$v_rhk_child['kode_cascading_sub_kegiatan']]['total_anggaran'] += $v_indikator_anggaran['rencana_pagu'];
                            }
                        }
                    }
                }

                if(!empty($data_anggaran['sasaran'])){
                    $html_sasaran = '<table class="table_data_anggaran" style="margin-top: 2rem;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sasaran</th>
                                <th>Anggaran</th>
                                <th>Ket</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $no=1;
                    foreach ($data_anggaran['sasaran'] as $v_sasaran) {
                        $html_sub_kegiatan .= '<tr>
                            <td>'.$no++.'</td>
                            <td class="text-left">'.$v_sasaran['nama'].'</td>
                            <td class="text-right">'.number_format($v_sasaran['total_anggaran'], 0, ",", ".").'</td>
                            <td></td></tr>';
                    }
                    $html_sasaran .='</tbody></table>';
                }
                
                if(!empty($data_anggaran['program'])){
                    $html_program = '<table class="table_data_anggaran" style="margin-top: 2rem;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Program</th>
                                <th>Anggaran</th>
                                <th>Ket</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $no=1;
                    foreach ($data_anggaran['program'] as $v_program) {
                        $html_program .= '<tr>
                            <td>'.$no++.'</td>
                            <td class="text-left">'.$v_program['nama'].'</td>
                            <td class="text-right">'.number_format($v_program['total_anggaran'], 0, ",", ".").'</td>
                            <td></td></tr>';
                    }
                    $html_program .='</tbody></table>';
                }
                
                if(!empty($data_anggaran['kegiatan'])){
                    $html_kegiatan = '<table class="table_data_anggaran" style="margin-top: 2rem;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kegiatan</th>
                                <th>Anggaran</th>
                                <th>Ket</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $no=1;
                    foreach ($data_anggaran['kegiatan'] as $v_kegiatan) {
                        $html_kegiatan .= '<tr>
                            <td>'.$no++.'</td>
                            <td class="text-left">'.$v_kegiatan['nama'].'</td>
                            <td class="text-right">'.number_format($v_kegiatan['total_anggaran'], 0, ",", ".").'</td>
                            <td></td></tr>';
                    }
                    $html_kegiatan .='</tbody></table>';
                }

                if(!empty($data_anggaran['sub_kegiatan'])){
                    $html_sub_kegiatan = '<table class="table_data_anggaran" style="margin-top: 2rem;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sub Kegiatan</th>
                                <th>Anggaran</th>
                                <th>Ket</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $no=1;
                    foreach ($data_anggaran['sub_kegiatan'] as $v_sub_kegiatan) {
                        $html_sub_kegiatan .= '<tr>
                            <td>'.$no++.'</td>
                            <td class="text-left">'.$v_sub_kegiatan['nama'].'</td>
                            <td class="text-right">'.number_format($v_sub_kegiatan['total_anggaran'], 0, ",", ".").'</td>
                            <td></td></tr>';
                    }
                    $html_sub_kegiatan .='</tbody></table>';
                }
            }
        }
        // end
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
        $data_detail['nama_golruang'] = $dataPegawai[0]['nmgolruang'];
        $data_detail['gelar_depan'] = $dataPegawai[0]['gelar_depan'];
        $data_detail['gelar_belakang'] = $dataPegawai[0]['gelar_belakang'];
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

    #table_data_sasaran tr, #table_data_sasaran td, #table_data_sasaran th {
        border: solid 1px #000;
    }

    .table_data_anggaran tr, .table_data_anggaran td, .table_data_anggaran th {
        border: solid 1px #000;
    }

    #table_data_sasaran tr td:first-child, .table_data_anggaran tr td:first-child{
        width: 3rem;
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
                <td><?php echo $data_detail['gelar_depan'].' '.$data_detail['nama_pegawai'].' '.$data_detail['gelar_belakang']; ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td><?php echo $data_detail['jabatan_pegawai']; ?></td>
            </tr>
            <tr>
                <td colspan="3">Selanjutnya disebut pihak pertama</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><?php echo $data_detail_atasan['gelar_depan'].' '.$data_detail_atasan['nama_pegawai_atasan'].' '.$data_detail_atasan['gelar_belakang']; ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td><?php echo $data_detail_atasan['jabatan_pegawai_atasan']; ?></td>
            </tr>
            <tr>
                <td colspan="3">Selaku atasan langsung pihak pertama, selanjutnya disebut pihak kedua</td>
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
                        <?php echo $data_detail_atasan['gelar_depan'].' '.$data_detail_atasan['nama_pegawai_atasan'].' '.$data_detail_atasan['gelar_belakang']; ?>
                    </td>
                    <td class="ttd-pejabat">
                        <?php echo $data_detail['gelar_depan'].' '.$data_detail['nama_pegawai'].' '.$data_detail['gelar_belakang']; ?>
                    </td>
                </tr>
                <tr class="text-center">
                    <td style="padding: 0;">
                        <?php if(empty($data_atasan['status_kepala'])) : ?>
                            <?php echo $data_detail_atasan['nama_golruang_atasan']; ?>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 0;">
                        <?php echo $data_detail['nama_golruang']; ?>
                    </td>
                </tr>
                <tr class="text-center">
                    <td style="padding: 0;">
                        <?php if(empty($data_atasan['status_kepala'])) : ?>
                            NIP. <?php echo $data_detail_atasan['nip_pegawai_atasan']; ?>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 0;">
                        NIP. <?php echo $data_detail['nip_pegawai']; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="break-print"></div>
    <div class="page-print mt-5 text-center">
        <p class="title-laporan mt-3">PERJANJIAN KINERJA TAHUN <?php echo $input['tahun']; ?></p>
        <p class="title-laporan"><?php echo $data_detail['bidang_pegawai']; ?></p>
        <?php if($html_lembar_2 != '') : ?>
            <table id="table_data_sasaran" style="margin-top: 2rem;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Sasaran</th>
                        <th>Indikator</th>
                        <th>Target</th>
                    </tr>
                </thead>
                <tbody>
                        <?php echo $html_lembar_2; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <?php echo $html_sasaran; ?>
        <?php echo $html_program; ?>
        <?php echo $html_kegiatan; ?>
        <?php echo $html_sub_kegiatan; ?>
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
                            <?php echo $data_detail_atasan['gelar_depan'].' '.$data_detail_atasan['nama_pegawai_atasan'].' '.$data_detail_atasan['gelar_belakang']; ?>
                        </td>
                        <td class="ttd-pejabat">
                            <?php echo $data_detail['gelar_depan'].' '.$data_detail['nama_pegawai'].' '.$data_detail['gelar_belakang']; ?>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td style="padding: 0;">
                            <?php if(empty($data_atasan['status_kepala'])) : ?>
                                <?php echo $data_detail_atasan['nama_golruang_atasan']; ?>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 0;">
                            <?php echo $data_detail['nama_golruang']; ?>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td style="padding: 0;">
                            <?php if(empty($data_atasan['status_kepala'])) : ?>
                                NIP. <?php echo $data_detail_atasan['nip_pegawai_atasan']; ?>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 0;">
                            NIP. <?php echo $data_detail['nip_pegawai']; ?>
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