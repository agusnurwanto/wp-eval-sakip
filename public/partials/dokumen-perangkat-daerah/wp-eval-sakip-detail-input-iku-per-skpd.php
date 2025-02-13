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
	'api_key'	=> get_option('_crb_apikey_wpsipd'),
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

    $this_jenis_role = (in_array($user_roles[0], $admin_role_pemda)) ? 1 : 2 ;

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
    ", $id_skpd, $id_periode),
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
    $wpdb->prepare(
        "
        SELECT 
            *
        FROM esakip_finalisasi_iku_opd
        WHERE id_jadwal_wpsipd = %d
          AND active = 1
        ORDER BY tanggal_dokumen, updated_at DESC
    ", $id_periode),
    ARRAY_A
);

$card = '';
$jumlah_data = array();
$nama_tahapan = array();

if ($data_tahapan) {
    foreach ($data_tahapan as $v) {
        $tanggal_dokumen = $this->format_tanggal_indo($v['tanggal_dokumen']);
        $get_nama_tahapan = $v['nama_tahapan'] . '|' . $tanggal_dokumen . '|' . $v['id_skpd'];

        // Simpan ID yang terkait dengan kombinasi unik
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
        list($nama_tahapan_item, $tanggal_dokumen, $id_skpd) = explode('|', $key);
        $id = implode(',', $get_iku); 

        $card .= '
        <div class="cr-item" id="card-tahap-' . htmlspecialchars($id) . '" title="' . htmlspecialchars($nama_tahapan_item) . '">
            <div class="cr-card">
                <h3 class="truncate-multiline" id="nama-tahapan-' . htmlspecialchars($id) . '">' . htmlspecialchars($nama_tahapan_item) . '</h3>
                <div class="badge badge-sm badge-primary m-0 ml-2 mr-2 text-light text-wrap">' . htmlspecialchars($skpd['nama_skpd']) . '</div>
                <div class="year" id="tanggal-tahapan-' . htmlspecialchars($id) . '">' . htmlspecialchars($tanggal_dokumen) . '</div>
                <div class="cr-actions">
                    <div class="cr-view-btn" id="view-btn-' . htmlspecialchars($id) . '" onclick="viewDokumen(\'' . $id . '\', this)" title="Lihat Dokumen">
                        <span class="dashicons dashicons-visibility"></span>
                    </div>
                    <div class="cr-view-btn-danger" onclick="deleteDokumen(\'' . $id . '\')" title="Hapus Dokumen">
                        <span class="dashicons dashicons-trash"></span>
                    </div>
                </div>
                <div class="badge-container">
                    <span class="badge badge-sm badge-warning badge-sedang-dilihat" id="badge-sedang-dilihat-' . htmlspecialchars($id) . '" style="display:none">
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
</style>

<!-- Table -->
<div class="container-md">
    <div title="Pengisian IKU <?php echo $skpd['nama_skpd'] ?> - <?php echo $nama_jadwal ?>">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 style="text-align: center; margin: 10px auto; min-width: 450px;">Pengisian IKU <br><?php echo $skpd['nama_skpd'] ?><br><?php echo $nama_jadwal ?></h1> 
            <!-- <div class="cr-container hide-display-print">
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

            <div class="text-right m-2">
                <button class="btn btn-sm btn-success hide-display-print" id="finalisasi-btn" onclick="showModalFinalisasi()">
                    <span class="dashicons dashicons-saved" title="Finalisasikan dokumen (Menyimpan dokumen sesuai data terkini)"></span>
                    Finalisasi Data
                </button>
                <button class="btn btn-sm btn-warning hide-display-print" id="edit-btn" onclick="showModalEditFinalisasi()" style="display: none;">
                    <span class="dashicons dashicons-edit" title="Edit Label"></span>
                    Edit Finalisasi Data
                </button>
            </div> -->
            <div id="action" class="action-section hide-excel"></div>
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
        </div>
    </div>
</div>
<!-- <div class="container-md mx-auto"x>
    <div class="text-center" id="action-sakip">
        <button class="btn btn-primary btn-large" onclick="window.print();"><i class="dashicons dashicons-printer"></i> Cetak / Print</button><br>
    </div>

    <?php if (!empty($error_message) && is_array($error_message)) : ?>
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                <?php echo implode('', array_map(fn($msg) => "<li>{$msg}</li>", $error_message)); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($jumlah_data) && is_array($jumlah_data)) : ?>
        <div class="cr-container m-4 hide-display-print">
            <h2 class="cr-title">Jumlah Dokumen Finalisasi Per SKPD</h2>
            <div class="text-center">
                <?php foreach ($jumlah_data as $id_skpd => $v) : ?>
                    <span class="badge badge-info fw-bold d-inline-flex align-items-center p-2 m-1 rounded-pill" style="font-size: 14px;">
                        <i class="dashicons dashicons-building me-1" style="font-size: 16px;"></i>
                        <?php echo $v['nama_skpd']; ?> | <?php echo $v['jumlah']; ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="cr-container m-4 hide-display-print">
        <h2 class="cr-title">Pilih Laporan Perjanjian Kinerja</h2>
        <div class="cr-carousel-wrapper">
            <div id="card-carousel" class="cr-carousel">
                <div class="cr-item" title="Perjanjian Kinerja Real Time">
                    <div class="cr-card">
                        <h3>Perjanjian Kinerja Sekarang</h3>
                        <div class="badge badge-sm badge-primary m-2 text-light text-wrap"><?php echo $skpd['nama_skpd']; ?></div>
                        <div class="year"><?php echo $text_tanggal_hari_ini; ?></div>
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

    <div class="text-center page-print">
        <div class="text-right m-2">
            <button class="btn btn-sm btn-success hide-display-print" id="finalisasi-btn" onclick="showModalFinalisasi()">
                <span class="dashicons dashicons-saved" title="Finalisasikan dokumen (Menyimpan dokumen sesuai data terkini)"></span>
                Finalisasi Dokumen
            </button>
            <button class="btn btn-sm btn-warning hide-display-print" id="edit-btn" onclick="showModalEditFinalisasi()" style="display: none;">
                <span class="dashicons dashicons-edit" title="Edit Label"></span>
                Edit Finalisasi Dokumen
            </button>
        </div>
        
    </div>
</div> -->
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
                <h5 class="modal-title" id="title-label">Finalisasi Indikator Kinerja Utama</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_data" value="">
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <strong>Indikator Kinerja Utama</strong>
                    </div>
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
                        <small class="form-text text-muted">Pastikan data yang tertera benar, laporan yang sudah difinalisasi akan disimpan dan tidak dapat di edit kembali.</small>
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
<script type="text/javascript">
jQuery(document).ready(function() {
    let id_jadwal_wpsipd = <?php echo $cek_id_jadwal_wpsipd; ?>;
    if(id_jadwal_wpsipd == 0){
        alert("Jadwal RENSTRA WP-SIPD untuk data Tujuan/Sasaran belum disetting.\nPastikan Jadwal RENSTRA di WP-SIPD tersedia.")
    }

    window.id_jadwal_wpsipd = <?php echo $id_jadwal_wpsipd; ?>;

    getTableIKU().then(function(){
        run_download_excel_sakip() 

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
    return new Promise(function(resolve, reject){
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_input_iku',
                api_key: esakip.api_key,
                id_skpd: <?php echo $id_skpd; ?>,
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
    })
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
            "id_skpd": <?php echo $id_skpd; ?>,
            "id_iku" : id_iku
        },
        dataType: "json",
        success: function(res){
            jQuery('#wrap-loading').hide();
            alert(res.message);
            if(res.status=='success'){
                jQuery("#modal-iku").modal('hide');
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
                    "id_skpd": <?php echo $id_skpd; ?>,
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

function finalisasi_iku(id) {
    jQuery('#wrap-loading').show();
    jQuery.ajax({
        url: esakip.url,
        type: 'POST',
        data: {
            action: 'finalisasi_iku',
            api_key: esakip.api_key,
            id: id
        },
        dataType: 'json',
        success: function(response) {
            jQuery('#wrap-loading').hide();
            console.log(response);
            if (response.status === 'success') {
                let data = response.data;
                jQuery("#id_data").val(data.id);
                jQuery('#modalFinalisasi').modal('show')
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
            id_skpd: '<?php echo $id_skpd; ?>',
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
function deleteDokumen(getID) {
    let confirmHapus = confirm('Apakah anda yakin ingin menghapus data ini?');
    if (!confirmHapus) {
        return;
    }

    jQuery('#wrap-loading').show();

    jQuery.ajax({
        url: esakip.url,
        method: 'POST',
        data: {
            action: "hapus_finalisasi_iku_opd",
            api_key: esakip.api_key,
            getID: getID 
        },
        dataType: 'json',
        success: function(response) {
            jQuery('#wrap-loading').hide();
            if (response.status === 'success') {
                alert(response.message);
                getID.split(',').forEach(id => jQuery(`#card-tahap-${id}`).hide());

                if (getID.includes(jQuery(`#id_data`).val())) {
                    location.reload();
                }
            } else {
                alert('Terjadi kesalahan: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            jQuery('#wrap-loading').hide();
            console.error('AJAX Error:', error);
            alert('GAGAL: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan.'));
        }
    });
}
function viewDokumen(getID) {
        jQuery('#wrap-loading').show()
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "get_finalisasi_iku_by_id",
                api_key: esakip.api_key,
                getID: getID,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide()
                console.log(response.message);
                if (response.status === 'success') {
                    jQuery(".editable-field").attr("title", "").attr("contenteditable", "false");
                    jQuery(".cr-view-btn").show();
                    jQuery(`#view-btn-${getID}`).hide();

                    jQuery('#id_data').val(response.data.id)
                    jQuery('#nama_tahap_finalisasi').val(response.data.nama_tahapan)
                    jQuery('#tanggal_tahap_finalisasi').val(response.data.tanggal_dokumen)

                    jQuery(`.badge-sedang-dilihat`).hide() 
                    jQuery(`#badge-sedang-dilihat-${response.data.id}`).show() 

                    jQuery('#finalisasi-btn').hide()
                    jQuery('#display-btn-first').show() 
                    jQuery('#edit-btn').show()

                    // Hapus isi tbody sebelum menambahkan data baru
                    jQuery("#table-sasaran-view tbody, #table-program-view tbody, #table-kegiatan-view tbody, #table-subkegiatan-view tbody").empty();

                    let rhkData = response.data.rhk;

                    // Inisialisasi counter untuk nomor urut dalam tabel
                    let countSasaran = 1,
                        countProgram = 1,
                        countKegiatan = 1,
                        countSubKegiatan = 1;

                    // Looping data RHK dan masukkan ke tabel sesuai tipe
                    rhkData.forEach((item) => {
                        let row = "";

                        if (item.tipe == "1") { // Sasaran
                            row = `<tr>
                                    <td class="esakip-text_tengah">${countSasaran++}</td>
                                    <td class="esakip-text_kiri">${item.label}</td>
                                    <td class="esakip-text_kiri">${item.indikator || '-'}</td>
                                    <td class="esakip-text_kiri">${item.target || '-'}</td>
                                </tr>`;
                            jQuery("#table-sasaran-view tbody").append(row);
                        } else if (item.tipe == "2") { // Program
                            row = `<tr>
                                        <td class="esakip-text_tengah">${countProgram++}</td>
                                        <td class="esakip-text_kiri">${item.kode} ${item.label}</td>
                                        <td class="esakip-text_kanan">${formatRupiah(parseInt(item.anggaran))}</td>
                                        <td class="esakip-text_kiri">${item.keterangan || '-'}</td>
                                    </tr>`;
                            jQuery("#table-program-view tbody").append(row);
                        } else if (item.tipe == "3") { // Kegiatan
                            row = `<tr>
                                        <td class="esakip-text_tengah">${countKegiatan++}</td>
                                        <td class="esakip-text_kiri">${item.kode} ${item.label}</td>
                                        <td class="esakip-text_kanan">${formatRupiah(parseInt(item.anggaran))}</td>
                                        <td class="esakip-text_kiri">${item.keterangan || '-'}</td>
                                    </tr>`;
                            jQuery("#table-kegiatan-view tbody").append(row);
                        } else if (item.tipe == "4") { // Subkegiatan
                            row = `<tr>
                                        <td class="esakip-text_tengah">${countSubKegiatan++}</td>
                                        <td class="esakip-text_kiri">${item.kode} ${item.label}</td>
                                        <td class="esakip-text_kanan">${formatRupiah(parseInt(item.anggaran))}</td>
                                        <td class="esakip-text_kiri">${item.keterangan || '-'}</td>
                                    </tr>`;
                            jQuery("#table-subkegiatan-view tbody").append(row);
                        }
                    });

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