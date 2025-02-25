<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2022'
), $atts);

$id_skpd = 0;
$id_periode = 0;
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}
if (!empty($_GET) && !empty($_GET['id_periode'])) {
    $id_periode = $_GET['id_periode'];
}
$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

//jadwal renstra wpsipd
$api_params = array(
    'action' => 'get_data_jadwal_wpsipd',
    'api_key'   => get_option('_crb_apikey_wpsipd'),
    'tipe_perencanaan' => 'monev_renstra',
    'id_jadwal' => $id_periode
);

$response = wp_remote_post(get_option('_crb_url_server_sakip'), array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));

$response = wp_remote_retrieve_body($response);

$data_jadwal_wpsipd = json_decode($response, true);

if(empty($data_jadwal_wpsipd['data'])){
    echo "Data jadwal WP-SIPD tidak ditemukan!";
    die();
}
if (!empty($data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran']) && $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'] > 1) {
    $tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'];
} else {
    $tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_anggaran'] + $data_jadwal_wpsipd['data'][0]['lama_pelaksanaan'];
}

$nama_jadwal = $data_jadwal_wpsipd['data'][0]['nama'] . ' ' . '(' . $data_jadwal_wpsipd['data'][0]['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ')';

if(empty($id_periode)){
    $id_jadwal_wpsipd = 0;
}else{
    $id_jadwal_wpsipd = $id_periode;
}
$cek_id_jadwal_wpsipd = empty($id_periode) ? 0 : 1;

$skpd = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        nama_skpd,
        id_skpd,
        nipkepala
    FROM esakip_data_unit
    WHERE id_skpd=%d
      AND tahun_anggaran=%d
      AND active = 1
", $id_skpd, $tahun_anggaran_sakip),
    ARRAY_A
);

$current_user = wp_get_current_user();
$nip_kepala = $current_user->data->user_login;
$user_roles = $current_user->roles;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
$is_administrator = in_array('administrator', $user_roles);

    $admin_role_pemda = array(
        'admin_bappeda',
        'admin_ortala'
    );

    $this_jenis_role = (array_intersect($admin_role_pemda, $user_roles)) ? 1 : 2 ;

//     $cek_settingan_menu = $wpdb->get_var(
//         $wpdb->prepare(
//         "SELECT 
//             jenis_role
//         FROM esakip_menu_dokumen 
//         WHERE nama_dokumen='Rencana Aksi'
//           AND user_role='perangkat_daerah' 
//           AND active = 1
//           AND tahun_anggaran=%d
//     ", $input['tahun'])
//     );

//     $hak_akses_user = ($cek_settingan_menu == $this_jenis_role || $cek_settingan_menu == 3 || $is_administrator) ? true : false;
$hak_akses_user = ($nip_kepala == $skpd['nipkepala'] || $is_administrator || $this_jenis_role == 1) ? true : false;
$iku = $wpdb->get_results(
    $wpdb->prepare("
        SELECT
            *
        FROM esakip_data_iku_opd 
        WHERE id_skpd = %d
            AND id_jadwal_wpsipd=%d
            AND active = 1
    ", $skpd['id_skpd'], $id_periode),
    ARRAY_A
);
// print_r($iku); die($wpdb->last_query);
$html_iku = '';
$no_iku = 1;
$data_simpan = [];
if (!empty($iku)) {
    foreach ($iku as $k_iku => $v_iku) {
        $data_simpan[] = [
            'kode_sasaran'    => $v_iku['kode_sasaran'] ?? '-',
            'label_sasaran'    => $v_iku['label_sasaran'] ?? '-',
            'id_unik_indikator'  => $v_iku['id_unik_indikator'] ?? '-',
            'label_indikator'  => $v_iku['label_indikator'] ?? '-',
            'formulasi'        => $v_iku['formulasi'] ?? '-', 
            'sumber_data'      => $v_iku['sumber_data'] ?? '-', 
            'penanggung_jawab' => $v_iku['penanggung_jawab'] ?? '-', 
            'id_jadwal_wpsipd' => $v_iku['id_jadwal_wpsipd'] ?? '-', 
        ];
        $html_iku .= '
            <tr>
                <td class="text-left atas kanan bawah kiri">' . $no_iku++ . '</td>
                <td class="text-left atas kanan bawah kiri">' . $v_iku['label_sasaran'] . '</td>
                <td class="text-left atas kanan bawah kiri">' . $v_iku['label_indikator'] . '</td>
                <td class="text-left atas kanan bawah kiri">' . $v_iku['formulasi'] . '</td>
                <td class="text-left atas kanan bawah kiri">' . $v_iku['sumber_data'] . '</td>
                <td class="text-left atas kanan bawah kiri">' . $v_iku['penanggung_jawab'] . '</td>
            </tr>';
    }
}
$data_tahapan = $wpdb->get_results(
    $wpdb->prepare("
        SELECT 
            t.*,
            f.id_tahap,         
            f.id_skpd,          
            f.kode_sasaran,     
            f.label_sasaran,    
            f.id_unik_indikator,
            f.label_indikator,  
            f.formulasi,        
            f.sumber_data,      
            f.penanggung_jawab, 
            f.id_jadwal_wpsipd
        FROM esakip_finalisasi_tahap_iku_opd AS t
        INNER JOIN esakip_finalisasi_iku_opd AS f ON f.id_tahap = t.id
            AND f.id_skpd = t.id_skpd
        WHERE t.id_jadwal_wpsipd = %d
          AND t.active = 1
          AND t.id_skpd = %d
        ORDER BY t.tanggal_dokumen, t.updated_at DESC
    ", $id_periode, $skpd['id_skpd']),
    ARRAY_A
);
// print_r($data_tahapan); die($wpdb->last_query);
$card = '';
$jumlah_data = array();
$nama_tahapan = array();

if (!empty($data_tahapan)) {
    foreach ($data_tahapan as $v) {
        $tanggal_dokumen = $this->format_tanggal_indo($v['tanggal_dokumen']);
        $get_nama_tahapan = $v['nama_tahapan'] . '|' . $tanggal_dokumen . '|' . $v['id_skpd'] .'|'. $v['id'];

        if (!isset($nama_tahapan[$get_nama_tahapan])) {
            $nama_tahapan[$get_nama_tahapan] = [];
        }
        $nama_tahapan[$get_nama_tahapan][] = $v['id'];

        if (!isset($jumlah_data[$v['id_skpd']])) {
            $jumlah_data[$v['id_skpd']] = [
                'nama_skpd' => $skpd['nama_skpd'],
                'jumlah' => 0
            ];
        }
        $jumlah_data[$v['id_skpd']]['jumlah']++;
    }

    foreach ($nama_tahapan as $key => $get_iku) {
        list($nama_tahapan_item, $tanggal_dokumen, $id_skpd, $id_tahap) = explode('|', $key);

        $card .= '
        <div class="cr-item" id="card-tahap-' . $id_tahap . '" title="' . $nama_tahapan_item . '">
            <div class="cr-card">
                <h3 class="truncate-multiline" id="nama-tahapan-' . $id_tahap . '">' . $nama_tahapan_item . '</h3>
                <div class="badge badge-sm badge-primary m-0 ml-2 mr-2 text-light text-wrap">' . $skpd['nama_skpd'] . '</div>
                <div class="year" id="tanggal-tahapan-' . $id_tahap . '">' . $tanggal_dokumen . '</div>
                <div class="cr-actions">
                    <div class="cr-view-btn" id="view-btn-' . $id_tahap . '" onclick="viewDokumen(\'' . $id_tahap . '\', this)" title="Lihat Dokumen">
                        <span class="dashicons dashicons-visibility"></span>
                    </div>
                    <div class="cr-view-btn-danger" onclick="deleteDokumen(\'' . $id_tahap . '\')" title="Hapus Dokumen">
                        <span class="dashicons dashicons-trash"></span>
                    </div>
                </div>
                <div class="badge-container">
                    <span class="badge badge-sm badge-warning badge-sedang-dilihat" id="badge-sedang-dilihat-' . $id_tahap . '" style="display:none">
                        Sedang Dilihat
                    </span>
                </div>
            </div>
        </div>';
    }
} 


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
    
    a.btn{
        text-decoration: none !important;
    }

    thead th {
        vertical-align: middle !important;
        font-size: small;
        text-align: center;
    }
    .table_dokumen_rencana_aksi {
        font-family:'Open Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; 
        border-collapse: collapse; 
        /* width: 2900px;  */
        table-layout: fixed; 
        overflow-wrap: break-word; 
        font-size: 90%;
    }
    .table_dokumen_rencana_aksi thead {
        position: sticky;
        top: -6px;
    }
    .table_dokumen_rencana_aksi .badge {
        white-space: normal;
        line-height: 1.3;
    }
    /* carousel */
    .cr-container {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }

    .cr-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 24px;
        color: #23282d;
        padding-left: 10px;
    }

    .cr-carousel-wrapper {
        position: relative;
        padding: 0 10px;
    }

    .cr-carousel {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        gap: 20px;
        padding: 10px 0;
    }

    .cr-carousel::-webkit-scrollbar {
        display: none;
    }

    .cr-item {
        flex: 0 0 calc(25% - 15px);
        scroll-snap-align: start;
    }

    .cr-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #dcdcde;
        border-radius: 8px;
        padding: 20px;
        width: 250px;
        /* Atur ukuran card */
        height: 220px;
        /* Atur tinggi card */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .cr-card h3 {
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        margin: 0;
        word-wrap: break-word;
        /* Menghindari teks keluar dari batas */
    }

    .cr-card .year {
        font-size: 14px;
        color: #666;
        margin: 4px 0;
    }

    .cr-actions {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .cr-card .cr-view-btn,
    .cr-card .cr-view-btn-danger {
        background-color: #fff;
        border: 1px solid #dcdcde;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .cr-card .cr-view-btn:hover {
        border-color: #007cba;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-card .cr-view-btn .dashicons {
        font-size: 18px;
        color: #007cba;
    }

    .cr-card .cr-view-btn-danger:hover {
        border-color: #ff686b;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-card .cr-view-btn-danger .dashicons {
        font-size: 18px;
        color: #ff686b;
    }

    .badge-container {
        text-align: center;
    }


    .cr-card:hover {
        border-color: #007cba;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: #fff;
        border: 1px solid #dcdcde;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .cr-scroll-btn:hover {
        border-color: #007cba;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-scroll-btn-left {
        left: -8px;
    }

    .cr-scroll-btn-right {
        right: -8px;
    }
    .atas { border-top: 1px solid black; }
    .kanan { border-right: 1px solid black; }
    .bawah { border-bottom: 1px solid black; }
    .kiri { border-left: 1px solid black; }

</style>

<!-- Table -->
<div class="container-md">
    <div title="Pengisian IKU <?php echo $skpd['nama_skpd'] ?> - <?php echo $nama_jadwal ?>">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 style="text-align: center; margin: 10px auto; min-width: 450px;">Pengisian IKU <br><?php echo $skpd['nama_skpd'] ?><br><?php echo $nama_jadwal ?></h1> 
            <div class="cr-container hide-display-print">
               <h2 class="cr-title">Pilih Pengisian Indikator Kegiatan Utama</h2>
                <div class="cr-carousel-wrapper">
                    <div id="card-carousel" class="cr-carousel">
                        <div class="cr-item" title="Perjanjian Kinerja Real Time">
                            <div class="cr-card">
                                <h3>Indikator Kegiatan Utama Sekarang</h3>
                                <div class="badge badge-sm badge-primary m-2 text-light text-wrap"><?php echo $skpd['nama_skpd']; ?></div>
                                <div class="year"></div>
                                <div class="cr-view-btn" style="display: none;" id="display-btn-first" onclick="location.reload()">
                                    <span class="dashicons dashicons-visibility"></span>
                                </div>
                                <span class="badge badge-info mt-2">
                                    <i class="dashicons dashicons-clock align-middle"></i> Real Time
                                </span>
                                <div class="text-center badge-sedang-dilihat">
                                    <span class='badge badge-sm badge-warning m-2'>Sedang Dilihat</span>
                                </div>
                            </div>
                        </div>
                        <?php echo $card; ?>
                    </div>
                    <div class="cr-scroll-btn cr-scroll-btn-left" onclick="scrollCarousel(-1)">
                        <span class="dashicons dashicons-arrow-left-alt2"></span>
                    </div>
                    <div class="cr-scroll-btn cr-scroll-btn-right" onclick="scrollCarousel(1)">
                        <span class="dashicons dashicons-arrow-right-alt2"></span>
                    </div>
                </div>
            </div>

            <div id="action" class="action-section hide-excel"></div>
            <div class="text-right m-2">
                <button class="btn btn-sm btn-success hide-display-print" id="finalisasi-btn" onclick="showModalFinalisasi()">
                    <span class="dashicons dashicons-saved" title="Finalisasikan dokumen (Menyimpan dokumen sesuai data terkini)"></span>
                    Finalisasi Data
                </button>
                <button class="btn btn-sm btn-warning hide-display-print" id="edit-btn" onclick="showModalEditFinalisasi()" style="display: none;">
                    <span class="dashicons dashicons-edit" title="Edit Label"></span>
                    Edit Finalisasi Data
                </button>
            </div>
            <br>
            <div id="cetak" class="wrap-table">
                <table cellpadding="2" cellspacing="0" class="table_dokumen_iku table table-bordered">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="text-center atas kanan bawah kiri">No</th>
                            <th class="text-center atas kanan bawah kiri">Tujuan/Sasaran</th>
                            <th class="text-center atas kanan bawah kiri">Indikator</th>
                            <th class="text-center atas kanan bawah kiri">Definisi Operasional/Formulasi</th>
                            <th class="text-center atas kanan bawah kiri">Sumber Data</th>
                            <th class="text-center atas kanan bawah kiri">Penanggung Jawab</th>
                            <th class="text-center atas kanan bawah kiri hide-excel" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div id="cetak" class="wrap-table">
                <table cellpadding="2" cellspacing="0" class="table_edit_dokumen_iku table table-bordered" style="display: none;">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="text-center atas kanan bawah kiri">No</th>
                            <th class="text-center atas kanan bawah kiri">Tujuan/Sasaran</th>
                            <th class="text-center atas kanan bawah kiri">Indikator</th>
                            <th class="text-center atas kanan bawah kiri">Definisi Operasional/Formulasi</th>
                            <th class="text-center atas kanan bawah kiri">Sumber Data</th>
                            <th class="text-center atas kanan bawah kiri">Penanggung Jawab</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal crud -->
<div class="modal fade" id="modal-iku" data-backdrop="static"  role="dialog" aria-labelledby="modal-crud-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="input_iku">
                    <input type="hidden" id="id_iku" value="">
                    <div class="form-group">
                        <label for="tujuan-sasaran">Tujuan/Sasaran</label>
                        <select name="" id="tujuan-sasaran"></select>
                    </div>
                    <div class="form-group">
                        <label for="indikator">Indikator</label>
                        <textarea name="" id="indikator" disabled></textarea>
                    </div>
                     <div class="form-group">
                        <label for="formulasi">Definisi Operasional/Formulasi</label>
                        <?php 
                            $content = ''; 
                            $editor_id = 'formulasi';
                            $settings = array(
                                'textarea_name' => 'formulasi',
                                'media_buttons' => false, 
                                'teeny' => false, 
                                'quicktags' => true
                            );
                            wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="sumber-data">Sumber Data</label>
                        <textarea name="" id="sumber-data"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="penanggung-jawab">Penanggung Jawab</label>
                        <textarea name="" id="penanggung-jawab"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" onclick="simpan_data_iku()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal finalisasi -->
<div class="modal fade mt-4" id="modalFinalisasi" tabindex="-1" role="dialog" aria-labelledby="modalFinalisasi" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-label">Finalisasi Indikator Kinerja Utama</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_data" value="">
                <!-- Informasi IKU -->
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <strong>Data Indikator Kinerja Utama</strong>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($html_iku)) : ?>
                            <table class="table_data_anggaran" id="table_sasaran">
                                <thead class="bg-dark text-light">
                                    <tr>
                                        <th class="text-center atas kanan bawah kiri">No</th>
                                        <th class="text-center atas kanan bawah kiri">Tujuan/Sasaran</th>
                                        <th class="text-center atas kanan bawah kiri">Indikator</th>
                                        <th class="text-center atas kanan bawah kiri">Definisi Operasional/Formulasi</th>
                                        <th class="text-center atas kanan bawah kiri">Sumber Data</th>
                                        <th class="text-center atas kanan bawah kiri">Penanggung Jawab</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html_iku; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nama_tahapan">Nama Tahapan</label>
                                <input type="text" class="form-control" id="nama_tahapan" name="nama_tahapan" placeholder="ex : Indikator Kinerja Utama" maxlength="48" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tanggal_dokumen">Tanggal Finalisasi</label>
                                <input type="date" class="form-control" id="tanggal_dokumen" name="tanggal_dokumen" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <small class="form-text text-muted">Pastikan data yang tertera benar, data yang sudah difinalisasi akan disimpan dan tidak dapat di edit kembali.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="simpanFinalisasi()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal edit finalisasi -->
<div class="modal fade mt-4" id="modalEditFinalisasi" tabindex="-1" role="dialog" aria-labelledby="modalEditFinalisasi" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-label">Edit Finalisasi Dokumen Perjanjian Kinerja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_data" name="id_data" value="">

                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <strong>Perjanjian Kinerja</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nama_tahap_finalisasi">Nama Tahapan</label>
                                <input type="text" class="form-control" id="nama_tahap_finalisasi" name="nama_tahap_finalisasi" placeholder="ex : Perjanjian Kinerja" maxlength="48">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tanggal_tahap_finalisasi">Tanggal Dokumen</label>
                                <input type="date" class="form-control" id="tanggal_tahap_finalisasi" name="tanggal_tahap_finalisasi">
                            </div>
                        </div>
                        <small class="form-text text-muted">Dokumen yang sudah difinalisasi hanya dapat diubah nama label nya.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="simpanEditFinalisasi()">Perbarui</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    let id_jadwal_wpsipd = <?php echo $cek_id_jadwal_wpsipd; ?>;
    if(id_jadwal_wpsipd == 0){
        alert("Jadwal RENSTRA WP-SIPD untuk data Tujuan/Sasaran belum disetting.\nPastikan Jadwal RENSTRA di WP-SIPD tersedia.")
    }

    window.id_jadwal_wpsipd = <?php echo $id_jadwal_wpsipd; ?>;

    getTableIKU().then(function(){
        run_download_excel_sakip() 
        run_download_word_sakip(jQuery('#cetak').html(), jQuery('#cetak').attr('title'));
        <?php if($hak_akses_user): ?>
            jQuery('#action-sakip').prepend('<a style="margin-right: 10px;" id="tambah-iku" onclick="return false;" href="#" class="btn btn-primary hide-print"><i class="dashicons dashicons-plus"></i> Tambah Data</a>');
        <?php endif; ?>  

        jQuery("#tambah-iku").on('click', function(){
            tambahIku();
        });
    });

});
    function scrollCarousel(direction) {
        const carousel = jQuery('#card-carousel');
        const scrollAmount = carousel[0].offsetWidth;
        const currentScroll = carousel.scrollLeft();

        carousel.animate({
            scrollLeft: currentScroll + direction * scrollAmount
        }, 500);
    }
    function getTableIKU() {
        jQuery('#wrap-loading').show();
        return new Promise(function(resolve, reject) {
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_table_input_iku',
                    api_key: esakip.api_key,
                    id_skpd: <?php echo $skpd['id_skpd']; ?>,
                    id_jadwal_wpsipd: id_jadwal_wpsipd
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    console.log(response);

                    if (response.status === 'success') {
                        jQuery('.table_dokumen_iku tbody').html(response.data);
                        style_css_download_excel_sakip();
                    } else {
                        alert(response.message);
                    }
                    resolve();
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat memuat data IKU!');
                    resolve();
                }
            });
        });
    }

    function showModalFinalisasi() {
        jQuery('#modalFinalisasi').modal('show')
    }

    function showModalEditFinalisasi() {
        jQuery('#modalEditFinalisasi').modal('show')
    }
    function tambahIku(){
        jQuery('#wrap-loading').show();
        return get_tujuan_sasaran()
        .then(function(){
            return new Promise(function(resolve, reject){
                jQuery('#wrap-loading').hide();
                document.input_iku.reset();
                jQuery('#id_iku').val("");
                if(typeof data_sasaran_cascading != 'undefined'){
                    jQuery('#wrap-loading').hide();
                    jQuery("#modal-iku").modal('show');
                    jQuery("#modal-iku").find('.modal-title').html('Tambah IKU');
                    jQuery("#modal-iku").find('.modal-footer').html(''
                        +'<button type="button" class="btn btn-danger" data-dismiss="modal">'
                            +'Tutup'
                        +'</button>'
                        +'<button type="button" class="btn btn-success" onclick="simpan_data_iku()">'
                            +'Simpan'
                        +'</button>');
        
                    if(data_sasaran_cascading != undefined){
                        let html_cascading = '<option value="">Pilih Tujuan/Sasaran</option>';
                        if(data_sasaran_cascading.data !== null){
                            data_sasaran_cascading.data.map(function(value, index){
                                if(value.id_unik_indikator == null){
                                    html_cascading += '<option value="'+value.kode_bidang_urusan+'">'+value.sasaran_teks+'</option>';
                                }
                            });
                        }
                        jQuery("#tujuan-sasaran").html(html_cascading);
                        jQuery('#tujuan-sasaran').select2({width: '100%'});
                        jQuery('#tujuan-sasaran').attr("onchange","get_indikator(this.value)")
                    }
                 
                    resolve();
                }
            })
        })
    }

    function simpan_data_iku(){
        let id_iku = jQuery('#id_iku').val();
        let kode_sasaran = jQuery('#tujuan-sasaran').val();
        let label_tujuan_sasaran = jQuery('#tujuan-sasaran option:selected').text();
        let label_indikator = jQuery('#indikator').val();
        let formulasi = tinymce.get('formulasi') ? tinymce.get('formulasi').getContent() : jQuery('#formulasi').val();
        let sumber_data = jQuery('#sumber-data').val();
        let penanggung_jawab = jQuery('#penanggung-jawab').val();
        if(kode_sasaran == '' || label_indikator == '' || formulasi == '' || sumber_data == '' || penanggung_jawab == ''){
            return alert('Ada Input Data Yang Kosong!')
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": 'tambah_iku',
                "api_key": esakip.api_key,
                "tipe_iku": "opd",
                "kode_sasaran": kode_sasaran,
                "label_tujuan_sasaran": label_tujuan_sasaran,
                "label_indikator": label_indikator,
                "formulasi": formulasi,
                "sumber_data": sumber_data,
                "penanggung_jawab": penanggung_jawab,
                "id_jadwal_wpsipd": id_jadwal_wpsipd,
                "id_skpd": <?php echo $skpd['id_skpd']; ?>,
                "id_iku" : id_iku
            },
            dataType: "json",
            success: function(res){
                jQuery('#wrap-loading').hide();
                alert(res.message);
                if(res.status=='success'){
                    jQuery("#modal-iku").modal('hide');
                    location.reload();
                    getTableIKU();
                }
            }
        });
    }

    function edit_iku(id) {
        tambahIku().then(function(){
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_iku_by_id',
                    api_key: esakip.api_key,
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    console.log(response);
                    if (response.status === 'success') {
                        let data = response.data;
                        jQuery('#id_iku').val(id);
                        jQuery("#tujuan-sasaran").val(data.kode_sasaran).trigger('change');                   
                        tinymce.get('formulasi').setContent(data.formulasi);
                        jQuery("#sumber-data").val(data.sumber_data);
                        jQuery("#penanggung-jawab").val(data.penanggung_jawab);
                        jQuery("#modal-iku").find('.modal-title').html('Edit IKU');
                        jQuery('#modal-iku').modal('show');
                        get_indikator(data.kode_sasaran);
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
        })
    }

    function hapus_iku(id) {
        if (!confirm('Apakah anda yakin ingin menghapus data ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_iku',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableIKU();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                jQuery('#wrap-loading').hide();
                alert('Terjadi kesalahan saat mengirim data!');
            }
        });
    }

    function get_indikator(that){
        jQuery('#wrap-loading').show();
        get_tujuan_sasaran()
        .then(function(){
            jQuery('#wrap-loading').hide();
            if(typeof data_sasaran_cascading != 'undefined'){
                if(data_sasaran_cascading != undefined){
                    let html_indikator = '';
                    if(data_sasaran_cascading.data !== null){
                        data_sasaran_cascading.data.map(function(value, index){
                            if(value.id_unik_indikator != null){
                                if(value.kode_bidang_urusan == that){
                                    html_indikator += '- '+value.indikator_teks+'\n';
                                }
                            }
                        });
                    }
                    jQuery("#indikator").html(html_indikator);
                }
            }
        })
    }

    function get_tujuan_sasaran() {
        return new Promise(function(resolve, reject){
            if(typeof data_sasaran_cascading == 'undefined'){
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    url: esakip.url,
                    type: "post",
                    data: {
                        "action": 'get_tujuan_sasaran_cascading',
                        "api_key": esakip.api_key,
                        "id_skpd": <?php echo $skpd['id_skpd']; ?>,
                        "tahun_anggaran": <?php echo $input['tahun']; ?>,
                        "jenis": 'sasaran',
                        "id_jadwal_wpsipd": id_jadwal_wpsipd
                    },
                    dataType: "json",
                    success: function(response){
                        if(response.status){
                            window.data_sasaran_cascading = response;
                        }else{
                            alert("Data cascading tidak ditemukan")
                        }
                        resolve();
                    }
                });
            }else{
                resolve();
            }
        });
    }

    function simpanFinalisasi() {
        let confirmFinalisasi = confirm('Apakah anda yakin ingin menyimpan data ini?');
        if (!confirmFinalisasi) {
            return;
        }

        let dataiku = {
            nama_tahapan: jQuery('#nama_tahapan').val(),
            tanggal_dokumen: jQuery('#tanggal_dokumen').val()
        };
        let data_simpan = <?php echo json_encode($data_simpan); ?>;

        if (!Array.isArray(data_simpan) || data_simpan.length === 0) {
            alert('Tidak ada data yang dapat disimpan!');
            return;
        }

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "simpan_finalisasi_iku",
                api_key: esakip.api_key,
                data_iku: dataiku,
                id_skpd: '<?php echo $skpd['id_skpd']; ?>',
                data_simpan: data_simpan
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                    jQuery('#modalFinalisasi').modal('hide');
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error('AJAX Error:', error);
                alert('Gagal menyimpan data. Silakan coba lagi.');
            },
        });
    }
    function simpanEditFinalisasi() {
        let confirmFinalisasi = confirm('Apakah anda yakin ingin perbarui data ini?');
        if (!confirmFinalisasi) {
            return;
        }

        const id_data = jQuery('#id_data').val()
        const namaTahapan = jQuery('#nama_tahap_finalisasi').val()
        const tanggalTahapan = jQuery('#tanggal_tahap_finalisasi').val()

        jQuery('#wrap-loading').show()
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "edit_finalisasi_iku",
                api_key: esakip.api_key,
                id_data: id_data,
                nama_tahap: namaTahapan,
                id_skpd: <?php echo $skpd['id_skpd']; ?>,
                id_jadwal_wpsipd: id_jadwal_wpsipd,
                tanggal_tahap: tanggalTahapan
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide()
                if (response.status === 'success') {
                    alert(response.message);
                    id_data.split(',').forEach(id => {
                        jQuery(`#nama-tahapan-${id}`).text(namaTahapan);
                        jQuery(`#card-tahap-${id}`).attr("title", `${namaTahapan}`);
                        jQuery(`#tanggal-tahapan-${id}`).text(formatTanggalIndonesia(tanggalTahapan));
                    });
                    jQuery('#modalEditFinalisasi').modal('hide')
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
                location.reload();
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide()
                console.error('AJAX Error:', error);
                alert('Gagal menyimpan data. Silakan coba lagi.');
            },
        });
    }
    function deleteDokumen(idTahap) {
        let confirmHapus = confirm('Apakah anda yakin ingin menghapus data ini?');
        if (!confirmHapus) {
            return;
        }
        jQuery('#wrap-loading').show()
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "hapus_finalisasi_iku_opd",
                api_key: esakip.api_key,
                id_tahap: idTahap
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery(`#card-tahap-${idTahap}`).hide()

                    if (idTahap == jQuery(`#id_data`).val()) {
                        location.reload()

                        jQuery(".cr-actions .cr-view-btn, .cr-actions .cr-view-btn-danger").prop("disabled", true).css("pointer-events", "none").css("opacity", "0.5");
                    } else {
                        jQuery('#wrap-loading').hide()
                    }
                } else {
                    jQuery('#wrap-loading').hide()
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide()
                console.error('AJAX Error:', error);
                alert('GAGAL: ' + response.message);
            },
        });
    }
    function viewDokumen(idTahap) {
        jQuery('#wrap-loading').show()
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "get_finalisasi_iku_by_id",
                api_key: esakip.api_key,
                id_skpd: <?php echo $skpd['id_skpd']; ?>,
                id_jadwal_wpsipd: id_jadwal_wpsipd,
                id_tahap: idTahap
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide()
                console.log(response.message);
                if (response.status === 'success') {
                    jQuery(".editable-field").attr("title", "").attr("contenteditable", "false");
                    jQuery(".cr-view-btn").show();
                    jQuery(`#view-btn-${idTahap}`).hide();

                    
                    jQuery('.table_edit_dokumen_iku').show();
                    jQuery('.table_dokumen_iku').hide();
                    jQuery('#tambah-iku').hide();

                    jQuery('.table_edit_dokumen_iku tbody').html(response.html);

                    jQuery('#id_data').val(idTahap);
                    jQuery('#nama_tahap_finalisasi').val(response.nama_tahapan);
                    jQuery('#tanggal_tahap_finalisasi').val(response.tanggal_dokumen);

                    jQuery(`.badge-sedang-dilihat`).hide(); 
                    jQuery(`#badge-sedang-dilihat-${idTahap}`).show();
                    jQuery('#finalisasi-btn').hide();
                    jQuery('#display-btn-first').show(); 
                    jQuery('#edit-btn').show();
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide()
                console.error('AJAX Error:', error);
                alert('Gagal menyimpan data. Silakan coba lagi.');
            },
        });
    }
</script>