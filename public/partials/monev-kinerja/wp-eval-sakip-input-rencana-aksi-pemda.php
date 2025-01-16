<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$id_tujuan = isset($_GET['id_tujuan']) ? intval($_GET['id_tujuan']) : 0;
$id_pokin = isset($_GET['id_pokin']) ? intval($_GET['id_pokin']) : 0;

$input = shortcode_atts(array(
    'tahun' => '2024',
    'id_skpd' => 0,
    'periode' => '',
    'id_tujuan' => $id_tujuan,
    'id_pokin' => $id_pokin
), $atts);

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$rpd = $wpdb->get_row($wpdb->prepare('
    SELECT 
        * 
    FROM esakip_rpd_tujuan
    WHERE id=%d
',$input['id_tujuan']), ARRAY_A);

$periode = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        *
    FROM esakip_data_jadwal
    WHERE id=%d
      AND status = 1
", $input['periode']),
    ARRAY_A
);
if (!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1) {
    $tahun_periode = $periode['tahun_selesai_anggaran'];
} else {
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

$data_id_jadwal_rpjmd = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        id_jadwal_rpjmd
    FROM esakip_pengaturan_upload_dokumen
    WHERE tahun_anggaran =%d
    AND active=1
", $input['tahun']), ARRAY_A);

if(empty($data_id_jadwal_rpjmd['id_jadwal_rpjmd'])){
    $id_jadwal_rpjmd = 0;
}else{
    $id_jadwal_rpjmd = $data_id_jadwal_rpjmd['id_jadwal_rpjmd'];
}
$cek_id_jadwal_rpjmd = empty($data_id_jadwal_rpjmd['id_jadwal_rpjmd']) ? 0 : 1;

$idtahun = $wpdb->get_results(
    "
        SELECT DISTINCT 
            tahun_anggaran 
        FROM esakip_data_unit        
        ORDER BY tahun_anggaran DESC",
    ARRAY_A
);
$tahun = "<option value='-1'>Pilih Tahun</option>";

foreach ($idtahun as $val) {
    $selected = '';
    if (!empty($input['tahun_anggaran']) && $val['tahun_anggaran'] == $input['tahun_anggaran']) {
        $selected = 'selected';
    }
    $tahun .= "<option value='$val[tahun_anggaran]' $selected>$val[tahun_anggaran]</option>";
}

$skpd = $wpdb->get_results($wpdb->prepare('
    SELECT 
        *
    FROM esakip_data_unit
    WHERE tahun_anggaran=%d
        AND active=1
    ORDER BY kode_skpd ASC
', $input['tahun']), ARRAY_A);
$select_skpd = '<option value="">Pilih SKPD</option>';
foreach($skpd as $get_skpd){
    $select = $get_skpd['id_skpd'] == $input['id_skpd'] ? 'selected' : '';
    $select_skpd .= '<option value="'.$get_skpd['id_skpd'].'" '.$select.'>'.$get_skpd['nama_skpd'].'</option>';
}

$pokin_level_1 = $wpdb->get_row($wpdb->prepare('
    SELECT 
        *
    FROM esakip_pohon_kinerja
    WHERE tahun_anggaran=%d
        AND id_jadwal=%d
        AND id=%d
        AND parent=0
        AND level=1
', $input['tahun'], $input['periode'], $input['id_pokin']), ARRAY_A);

$label_pokin_1 = '-';
if(!empty($pokin_level_1)){
    $indikator = array();
    $ind_pokin_level_1 = $wpdb->get_results($wpdb->prepare('
        SELECT 
            *
        FROM esakip_pohon_kinerja
        WHERE tahun_anggaran=%d
            AND id_jadwal=%d
            AND id=%d
            AND parent=%d
            AND level=1
            AND active=1
    ', $input['tahun'], $input['periode'], $input['id_pokin'], $input['id_pokin']), ARRAY_A);
    foreach($ind_pokin_level_1 as $ind){
        $indikator[] = $ind['label_indikator_kinerja'];
    }
    if(!empty($indikator)){
        $label_pokin_1 = $pokin_level_1['label'].' ( '.implode(', ', $indikator).' )';
    }else{
        $label_pokin_1 = $pokin_level_1['label'];
    }
}

$pokin = $wpdb->get_results($wpdb->prepare('
    SELECT 
        *
    FROM esakip_pohon_kinerja
    WHERE tahun_anggaran=%d
        AND id_jadwal=%d
        AND parent=%d
        AND level=2
        AND active=1
', $input['tahun'], $input['periode'], $input['id_pokin']), ARRAY_A);

$select_pokin = '<option value="">Pilih Pohon Kinerja</option>';
foreach($pokin as $get_pokin){
    $select_pokin .= '<option value="'.$get_pokin['id'].'">'.$get_pokin['label'].'</option>';
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

    #modal-renaksi-pemda thead th {
        font-size: medium !important;
    }

    #modal-renaksi-pemda .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    .table_dokumen_rencana_aksi_pemda {
        font-family:'Open Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; 
        border-collapse: collapse; 
        width: 2900px; 
        table-layout: fixed; 
        overflow-wrap: break-word; 
        font-size: 90%;
    }
    .table_dokumen_rencana_aksi_pemda thead {
        position: sticky;
        top: -6px;
    }
    .table_dokumen_rencana_aksi_pemda .badge {
        white-space: normal;
        line-height: 1.3;
    }
</style>
<div class="container-md">
    <div id="cetak" style="padding: 5px;">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 style="margin-top: 20px;" class="text-center">Rencana Aksi <?php echo $periode['nama_jadwal'] . ' ' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ''; ?><br>Pemerintah Daerah<br> Tahun Anggaran <?php echo $input['tahun']; ?></h1 style="margin-top: 20px;">
            <div class="text-center" style="margin-bottom: 25px;">
                <div id="action" class="action-section hide-excel"></div>
            </div>
            <table cellpadding="2" cellspacing="0" class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="width: 200px;">JUDUL CASCADING</td>
                        <td class="text-center" style="width: 30px;">:</td>
                        <td><?php echo $rpd['nama_cascading']; ?></td>
                    </tr>
                    <tr>
                        <td>TUJUAN <?php echo $periode['nama_jadwal']; ?></td>
                        <td class="text-center" style="width: 30px;">:</td>
                        <td><?php echo $rpd['tujuan_teks']; ?></td>
                    </tr>
                    <tr>
                        <td>POKIN LEVEL 1</td>
                        <td class="text-center" style="width: 30px;">:</td>
                        <td><?php echo $label_pokin_1; ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="wrap-table">
                <table cellpadding="2" cellspacing="0" class="table_dokumen_rencana_aksi_pemda table table-bordered">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="atas kiri bawah kanan text-center" rowspan="2" style="width: 85px;">No</th>
                            <th class="atas kiri bawah kanan text-center" rowspan="2" style="width: 300px;">KEGIATAN UTAMA</th>
                            <th class="atas kiri bawah kanan text-center" rowspan="2" style="width: 300px;">INDIKATOR KEGIATAN UTAMA</th>
                            <th class="atas kiri bawah kanan text-center" rowspan="2" style="width: 400px;">RENCANA AKSI</th>
                            <th class="atas kiri bawah kanan text-center" rowspan="2" style="width: 300px;">URAIAN KEGIATAN RENCANA AKSI</th>
                            <th class="atas kiri bawah kanan text-center" colspan="2" style="width: 400px;">OUTCOME/OUTPUT</th>
                            <th class="atas kiri bawah kanan text-center" rowspan="2" style="width: 140px;">TARGET</th>
                            <th class="atas kiri bawah kanan text-center" colspan="6" style="width: 400px;">TARGET KEGIATAN PER TRIWULAN</th>
                            <th class="atas kiri bawah kanan text-center" rowspan="2" style="width: 140px;">JUMLAH ANGGARAN</th>
                            <th class="atas kiri bawah kanan text-center" rowspan="2" style="width: 300px;">NAMA PERANGKAT DAERAH</th>
                            <th class="atas kiri bawah kanan text-center" rowspan="2" style="width: 140px;">MITRA BIDANG</th>
                        </tr>
                        <tr>
                            <th class="atas kiri bawah kanan text-center" style="width: 50px;">SATUAN</th>
                            <th class="atas kiri bawah kanan text-center" style="width: 400px;">INDIKATOR</th>
                            <th class="atas kiri bawah kanan text-right" style="width: 200px;">AWAL</th>
                            <th class="atas kiri bawah kanan text-right" style="width: 200px;">TW-I</th>
                            <th class="atas kiri bawah kanan text-right" style="width: 200px;">TW-II</th>
                            <th class="atas kiri bawah kanan text-right" style="width: 200px;">TW-III</th>
                            <th class="atas kiri bawah kanan text-right" style="width: 200px;">TW-IV</th>
                            <th class="atas kiri bawah kanan text-right" style="width: 200px;">AKHIR</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tahun -->
<div class="modal fade" id="tahunModal" tabindex="-1" role="dialog" aria-labelledby="tahunModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tahunModalLabel">Pilih Tahun Anggaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tahunForm">
                    <div class="form-group">
                        <label for="tahunAnggaran">Tahun Anggaran:</label>
                        <select class="form-control" id="tahunAnggaran" name="tahunAnggaran">
                            <?php echo $tahun; ?>
                        </select>
                        <input type="hidden" id="idDokumen" value="">
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="submit_tahun_renja_rkt(); return false">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="hide-print" id="catatan" style="max-width: 900px; margin: 40px auto; padding: 20px; border: 1px solid #e5e5e5; border-radius: 8px; background-color: #f9f9f9;">
    <h4 style="font-weight: bold; margin-bottom: 20px; color: #333;">Catatan</h4>
    <ul style="list-style-type: disc; padding-left: 20px; line-height: 1.6; color: #555;">
        <li>Nama Perangkat Daerah Dengan Background Warna <strong>Merah</strong> Belum Terkoneksi Dengan Rencana Aksi Perangkat Daerah</li>
    </ul>
</div>

<!-- Modal Renaksi -->
<div class="modal fade" id="modal-renaksi-pemda" role="dialog" data-backdrop="static" aria-hidden="true">'
    <div class="modal-dialog" style="max-width: 1500px;" role="document">
        <div class="modal-content">
            <div class="modal-header bgpanel-theme">
                <h4 style="margin: 0;" class="modal-title">Data Rencana Aksi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span><i class="dashicons dashicons-dismiss"></i></span></button>
            </div>
            <div class="modal-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link" id="nav-level-1-tab" data-toggle="tab" href="#nav-level-1" role="tab" aria-controls="nav-level-1" aria-selected="false">Kegiatan Utama</a>
                        <a class="nav-item nav-link" id="nav-level-2-tab" data-toggle="tab" href="#nav-level-2" role="tab" aria-controls="nav-level-2" aria-selected="false">Rencana Aksi</a>
                        <a class="nav-item nav-link" id="nav-level-3-tab" data-toggle="tab" href="#nav-level-3" role="tab" aria-controls="nav-level-3" aria-selected="false">Uraian Kegiatan Rencana Aksi</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-level-1" role="tabpanel" aria-labelledby="nav-level-1-tab"></div>
                    <div class="tab-pane fade" id="nav-level-2" role="tabpanel" aria-labelledby="nav-level-2-tab"></div>
                    <div class="tab-pane fade" id="nav-level-3" role="tabpanel" aria-labelledby="nav-level-3-tab"></div>
                    <div class="tab-pane fade" id="nav-level-4" role="tabpanel" aria-labelledby="nav-level-4-tab"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal crud -->
<div class="modal fade" id="modal-crud" data-backdrop="static"  role="dialog" aria-labelledby="modal-crud-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    run_download_excel_sakip();
    jQuery('#action-sakip').prepend('<a style="margin-right: 10px;" id="tambah-rencana-aksi" onclick="return false;" href="#" class="btn btn-primary hide-print"><i class="dashicons dashicons-plus"></i> Tambah Data</a>');

    let id_jadwal = <?php echo $cek_id_jadwal_rpjmd; ?>;
    if(id_jadwal == 0){
        alert("Jadwal RENSTRA untuk data Pokin belum disetting.\nSetting Jadwal RENSTRA ada di admin dashboard di menu Monev Rencana Aksi -> Monev Rencana Aksi Setting")
    }

    window.id_jadwal = <?php echo $id_jadwal_rpjmd; ?>;
    getTablePengisianRencanaAksiPemda();

    jQuery("#tambah-rencana-aksi").on('click', function(){
        kegiatanUtama();
    });
    jQuery('#skpd').select2({
        'width': '100%'
    });
});

function getTablePengisianRencanaAksiPemda(no_loading=false) {
    if(no_loading == false){
        jQuery('#wrap-loading').show();
    }
    jQuery.ajax({
        url: esakip.url,
        type: 'POST',
        data: {
            action: 'get_table_input_rencana_aksi_pemda',
            api_key: esakip.api_key,
            tahun_anggaran: '<?php echo $input['tahun'] ?>',
            id_tujuan: '<?php echo $input['id_tujuan'] ?>'
        },
        dataType: 'json',
        success: function(response) {
            if(no_loading == false){
                jQuery('#wrap-loading').hide();
            }
            console.log(response);
            if (response.status === 'success') {
                jQuery('.table_dokumen_rencana_aksi_pemda tbody').html(response.data);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            jQuery('#wrap-loading').hide();
            console.error(xhr.responseText);
            alert('Terjadi kesalahan saat memuat data Rencana Aksi!');
        }
    });
}

function kegiatanUtama(){
    jQuery("#wrap-loading").show();
    return new Promise(function(resolve, reject){
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": "get_data_renaksi_pemda",
                "level": 1,
                "parent": 0,
                "api_key": esakip.api_key,
                "tipe_pokin": "pemda",
                "id_tujuan": <?php echo $input['id_tujuan'] ?>
            },
            dataType: "json",
            success: function(res){
                jQuery('#wrap-loading').hide();
                let kegiatanUtama = `` 
                    +`<div style="margin-top:10px">`
                        +`<button type="button" class="btn btn-success mb-2" onclick="tambah_rencana_aksi();"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data Kegiatan Utama</button>`
                    +`</div>`
                    +`<table class="table" id="kegiatanUtama">`
                        +`<thead>`
                            +`<tr class="table-secondary">`
                                +`<th class="text-center" style="width:40px;">No</th>`
                                +`<th class="text-center" style="width:300px;">Label Pokin</th>`
                                +`<th class="text-center">Kegiatan Utama</th>`
                                +`<th class="text-center" style="width:200px;">Aksi</th>`
                            +`</tr>`
                        +`</thead>`
                        +`<tbody>`;

                res.data.filter(function(item) {
                    return item.id_tujuan == <?php echo $input['id_tujuan']; ?>;
                }).map(function(value, index){
                    let label_cascading = value.label_cascading_sasaran != null ? value.label_cascading_sasaran : '-';
                    kegiatanUtama += `` 
                        +`<tr id="kegiatan_utama_${value.id}">`
                            +`<td class="text-center">${index+1}</td>`
                            +`<td class="label_pokin">${value.label_pokin_2}</td>`
                            +`<td class="label_renaksi">${value.label}</td>`
                            +`<td class="text-center">`
                                +`<a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="tambah_indikator_rencana_aksi(${value.id}, 1)" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
                                +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning" onclick="lihat_rencana_aksi(${value.id}, 2, ${value.id_pokin_2}, '${value.kode_cascading_sasaran}')" title="Lihat Rencana Aksi"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
                                +`<a href="javascript:void(0)" onclick="edit_rencana_aksi(${value.id}, 1)" data-id="${value.id}" class="btn btn-sm btn-primary edit-kegiatan-utama" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
                                +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger" onclick="hapus_rencana_aksi(${value.id}, 1)" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
                            +`</td>`
                        +`</tr>`;

                    let indikator = value.indikator;
                    if(indikator.length > 0){
                        kegiatanUtama += `` 
                        +'<td colspan="5" style="padding: 0;">'
                            +`<table class="table" id="indikatorKegiatanUtama" style="margin: .5rem 0 2rem;">`
                                +`<thead>`
                                    +`<tr class="table-secondary">`
                                        +`<th class="text-center" style="width:20px">No</th>`
                                        +`<th class="text-center">Indikator</th>`
                                        +`<th class="text-center" style="width:120px;">Satuan</th>`
                                        +`<th class="text-center" style="width:50px;">Target Awal</th>`
                                        +`<th class="text-center" style="width:50px;">Target Akhir</th>`
                                        +`<th class="text-center" style="width:50px;">Target TW 1</th>`
                                        +`<th class="text-center" style="width:50px;">Target TW 2</th>`
                                        +`<th class="text-center" style="width:50px;">Target TW 3</th>`
                                        +`<th class="text-center" style="width:50px;">Target TW 4</th>`
                                        +`<th class="text-center" style="width:110px">Aksi</th>`
                                    +`</tr>`
                                +`</thead>`
                                +`<tbody>`;

                        indikator.map(function(b, i){
                            kegiatanUtama += `` 
                                +`<tr>`
                                    +`<td class="text-center">${index+1}.${i+1}</td>`
                                    +`<td>${b.indikator}</td>`
                                    +`<td class="text-center">${b.satuan}</td>`
                                    +`<td class="text-center">${b.target_awal}</td>`
                                    +`<td class="text-center">${b.target_akhir}</td>`
                                    +`<td class="text-center">${b.target_1}</td>`
                                    +`<td class="text-center">${b.target_2}</td>`
                                    +`<td class="text-center">${b.target_3}</td>`
                                    +`<td class="text-center">${b.target_4}</td>`
                                    +`<td class="text-center">`
                                        +`<a href="javascript:void(0)" data-id="${b.id}" class="btn btn-sm btn-primary" onclick="edit_indikator(${b.id}, 1)" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
                                        +`<a href="javascript:void(0)" data-id="${b.id}" class="btn btn-sm btn-danger" onclick="hapus_indikator(${b.id}, 1);" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
                                    +`</td>`
                                +`</tr>`;
                        });

                        kegiatanUtama += `` 
                                +'</tbody>'
                            +'</table>'
                        +'</td>';
                    }
                });

                kegiatanUtama += `` 
                    +`<tbody>`
                +`</table>`;

                jQuery("#nav-level-1").html(kegiatanUtama);
                jQuery('.nav-tabs a[href="#nav-level-1"]').tab('show');
                jQuery('#modal-renaksi-pemda').modal('show');
                resolve();
            }
        });
    });
}

function tambah_rencana_aksi(){
    return new Promise(function(resolve, reject){
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": "get_data_pokin_pemda",
                "level": 1,
                "parent": 0,
                "api_key": esakip.api_key,
                "tipe_pokin": "pemda",
                "id_jadwal": id_jadwal,
            },
            dataType: "json",
            success: function(res){
                jQuery('#wrap-loading').hide();
                jQuery("#modal-crud").find('.modal-title').html('Tambah Rencana Aksi');
                jQuery("#modal-crud").find('.modal-body').html(''
                    +`<form id="form-renaksi">`
                        +'<input type="hidden" id="id_renaksi" value=""/>'
                        +`<div class="form-group">`
                            +`<label for="pokin-level-2">Pilih Pokin Level 2</label>`
                            + `<select class="form-control" name="pokin-level-2" id="pokin-level-2"><?php echo $select_pokin; ?></select>`
                            +`</select>`
                        +`</div>`
                        +`<div class="form-group">`
                            +`<textarea class="form-control" name="label" id="label_renaksi" placeholder="Tuliskan Kegiatan Utama..."></textarea>`
                        +`</div>`
                    +`</form>`);
                jQuery("#modal-crud").find('.modal-footer').html(''
                    +'<button type="button" class="btn btn-danger" data-dismiss="modal">'
                        +'Tutup'
                    +'</button>'
                    +'<button type="button" class="btn btn-success" onclick="simpan_data_renaksi(1)">'
                        +'Simpan'
                    +'</button>');
                jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
                jQuery("#modal-crud").find('.modal-dialog').css('width','');
                jQuery("#modal-crud").modal('show');

                resolve();
            }
        });
    });
}

function tambah_indikator_rencana_aksi(id, tipe){
    var title = '';
    let input_pagu = '';
    let input_skpd = '';
    let input_mitra_bidang = '';
    if(tipe == 1){
        title = 'Indikator Kegiatan Utama';
    }else if(tipe == 2){
        title = 'Indikator Rencana Aksi';
    }else if(tipe == 3){
        title = 'Uraian Kegiatan Rencana Aksi';
        input_skpd = ''
            + `<div class="form-group row">`
                + '<div class="col-md-2">'
                    + `<label for="id_skpd">Nama Perangkat Daerah</label>`
                + '</div>'
                + '<div class="col-md-10">'
                    + `<select class="form-control select2" id="id_skpd" name="id_skpd" onchange="get_skpd();"><?php echo $select_skpd; ?></select>`
                + '</div>'
            + `</div>`;
        input_mitra_bidang = ''
            +`<div class="form-group row">`
                +'<div class="col-md-2">'
                    +`<label for="mitra_bidang">Mitra Bidang</label>`
                +'</div>'
                +'<div class="col-md-10">'
                    +`<input type="text" class="form-control" id="mitra_bidang"/>`
                +'</div>'
            +`</div>`
        input_pagu = ''
            +`<div class="form-group row">`
                +'<div class="col-md-2">'
                    +`<label for="rencana_pagu">Rencana Pagu</label>`
                +'</div>'
                +'<div class="col-md-10">'
                    +`<input type="text" class="form-control" id="rencana_pagu"/>`
                +'</div>'
            +`</div>`
    }
    var tr = jQuery('#kegiatan_utama_'+id);
    var label_renaksi = tr.find('.label_renaksi').text();
    jQuery("#modal-crud").find('.modal-title').html('Tambah '+title);
    jQuery("#modal-crud").find('.modal-body').html(''
        +`<form id="form-renaksi">`
            +'<input type="hidden" value="" id="id_label_indikator">'
            +'<input type="hidden" value="'+id+'" id="id_label">'
            +`<div class="form-group row">`
                +'<div class="col-md-2">'
                    +`<label for="kegiatan_utama_indikator">`+title.replace('Indikator ', '')+`</label>`
                +'</div>'
                +'<div class="col-md-10">'
                    +'<input type="text" disabled class="form-control" id="kegiatan_utama_indikator" value="'+label_renaksi+'"/>'
                +'</div>'
            +`</div>`
            +`<div class="form-group row">`
                +'<div class="col-md-2">'
                    +`<label for="indikator">`+title+`</label>`
                +'</div>'
                +'<div class="col-md-10">'
                    +`<textarea class="form-control" name="label" id="indikator" placeholder="Tuliskan Indikator..."></textarea>`
                +'</div>'
            +`</div>`
            +`<div class="form-group row">`
                +'<div class="col-md-10">'
                    +`<input type="hidden" class="form-control" id="tahun_anggaran" name="tahun_anggaran" value="<?php echo $input['tahun']; ?>"/>`
                +'</div>'
            +`</div>`

            +`${input_skpd}`
            +`${input_mitra_bidang}`
            +`<div class="form-group row">`
                +'<div class="col-md-2">'
                    +`<label for="satuan_indikator">Satuan</label>`
                +'</div>'
                +'<div class="col-md-10">'
                    +`<input type="text" class="form-control" id="satuan_indikator"/>`
                +'</div>'
            +`</div>`
            +`<div class="form-group row">`
                +'<div class="col-md-2">'
                    +`<label for="target_awal">Target Awal</label>`
                +'</div>'
                +'<div class="col-md-4">'
                    +`<input type="number" class="form-control" id="target_awal"/>`
                +'</div>'
                +'<div class="col-md-2">'
                    +`<label for="target_akhir">Target Akhir</label>`
                +'</div>'
                +'<div class="col-md-4">'
                    +`<input type="number" class="form-control" id="target_akhir"/>`
                +'</div>'
            +`</div>`
            +`<div class="form-group row">`
                +'<div class="col-md-2">'
                    +`<label for="target_tw_1">TW 1</label>`
                +'</div>'
                +'<div class="col-md-4">'
                    +`<input type="number" class="form-control" id="target_tw_1"/>`
                +'</div>'
                +'<div class="col-md-2">'
                    +`<label for="target_tw_2">TW 2</label>`
                +'</div>'
                +'<div class="col-md-4">'
                    +`<input type="number" class="form-control" id="target_tw_2"/>`
                +'</div>'
            +`</div>`
            +`<div class="form-group row">`
                +'<div class="col-md-2">'
                    +`<label for="target_tw_3">TW 3</label>`
                +'</div>'
                +'<div class="col-md-4">'
                    +`<input type="number" class="form-control" id="target_tw_3"/>`
                +'</div>'
                +'<div class="col-md-2">'
                    +`<label for="target_tw_4">TW 4</label>`
                +'</div>'
                +'<div class="col-md-4">'
                    +`<input type="number" class="form-control" id="target_tw_4"/>`
                +'</div>'
            +`</div>`
            +`${input_pagu}`
        +`</form>`);
    jQuery("#modal-crud").find('.modal-footer').html(''
        +'<button type="button" class="btn btn-danger" data-dismiss="modal">'
            +'Tutup'
        +'</button>'
        +'<button type="button" class="btn btn-success" onclick="simpan_indikator_renaksi('+tipe+')" data-view="kegiatanUtama">'
            +'Simpan'
        +'</button>');
    jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
    jQuery("#modal-crud").find('.modal-dialog').css('width','');
    jQuery("#modal-crud").modal('show');
    jQuery('#id_skpd').select2({
        width: '100%', 
        placeholder: 'Pilih Perangkat Daerah',
        allowClear: true
    });
}

function simpan_indikator_renaksi(tipe) {
    var id = jQuery('#id_label_indikator').val();
    var id_label = jQuery('#id_label').val();
    var indikator = jQuery('#indikator').val();
    if ((tipe == 1 || tipe == 2) && indikator == '') {
        return alert('Indikator tidak boleh kosong!');
    }
    var mitra_bidang = jQuery('#mitra_bidang').val();
    if (mitra_bidang == '') {
        return alert('Mitra Bidang tidak boleh kosong!');
    }
    var rencana_pagu = jQuery('#rencana_pagu').val();
    if (rencana_pagu == '') {
        rencana_pagu = 0;
    }

    var satuan = jQuery('#satuan_indikator').val();
    if ((tipe == 2 || tipe == 3) && satuan == '') {
        return alert('Satuan tidak boleh kosong!');
    }

    var target_awal = jQuery('#target_awal').val();

    var target_akhir = jQuery('#target_akhir').val();

    var target_tw_1 = jQuery('#target_tw_1').val();

    var target_tw_2 = jQuery('#target_tw_2').val();

    var target_tw_3 = jQuery('#target_tw_3').val();

    var target_tw_4 = jQuery('#target_tw_4').val();    

    var id_skpd = jQuery('#id_skpd').val();
    
    if ((tipe == 3) && id_skpd == '') {
        return alert('Pilih SKPD dulu!');
    }

    jQuery('#wrap-loading').show();
    jQuery.ajax({
        url: esakip.url,
        type: "post",
        data: {
            "action": 'tambah_indikator_renaksi_pemda',
            "api_key": esakip.api_key,
            "tipe_pokin": "pemda",
            "id_label_indikator": id,
            "id_label": id_label,
            "indikator": indikator,
            "satuan": satuan,
            "rencana_pagu": rencana_pagu,
            "target_awal": target_awal,
            "target_akhir": target_akhir,
            "target_tw_1": target_tw_1,
            "target_tw_2": target_tw_2,
            "target_tw_3": target_tw_3,
            "target_tw_4": target_tw_4,
            "mitra_bidang": mitra_bidang,
            "id_skpd": id_skpd,
            "tahun_anggaran": <?php echo $input['tahun']; ?>,
            "id_tujuan": <?php echo $input['id_tujuan'] ?>
        },
        dataType: "json",
        success: function(res) {
            jQuery('#wrap-loading').hide();
            alert(res.message);
            if (res.status == 'success') {
                jQuery("#modal-crud").modal('hide');
                if (tipe == 1) {
                    kegiatanUtama();
                } else if (tipe == 2) {
                    var parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
                    var parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
                    lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin);
                } else if (tipe == 3) {
                    var parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
                    var parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
                    lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin);
                }
                getTablePengisianRencanaAksiPemda(1);
            }
        }
    });
}


function lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin){
    jQuery("#wrap-loading").show();
    return new Promise(function(resolve, reject){
        var title = '';
        var fungsi_tambah = '';
        var id_tabel = '';
        let header_pagu = '';
        let header_skpd = '';
        let title_cascading = '';

        // rencana aksi
        if(tipe ==1){
            id_tabel = 'kegiatanUtama';
            title = 'Kegiatan Utama';
            fungsi_tambah = 'tambah_rencana_aksi';
        }else if(tipe == 2){
            id_tabel = 'tabel_rencana_aksi';
            title = 'Rencana Aksi';
            fungsi_tambah = 'tambah_renaksi_2';
        }else if(tipe == 3){
            id_tabel = 'tabel_uraian_rencana_aksi';
            title = 'Uraian Kegiatan Rencana Aksi';
            fungsi_tambah = 'tambah_renaksi_2';
            header_pagu = ''
                +`<th class="text-center" style="width:50px;">Rencana Pagu</th>`;
            header_skpd = ''
                +`<th class="text-center">Perangkat Daerah</th>`;
        }
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": "get_data_renaksi_pemda",
                "level": tipe,
                "parent": parent_renaksi,
                "api_key": esakip.api_key,
                "tipe_pokin": "pemda",
                "id_tujuan": <?php echo $input['id_tujuan'] ?>,
            },
            dataType: "json",
            success: function(res){
                jQuery('#wrap-loading').hide();
                let renaksi = ``
                    +`<div style="margin-top:10px">`
                        +`<button type="button" class="btn btn-success mb-2" onclick="`+fungsi_tambah+`(`+tipe+`);"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data `+title+`</button>`
                    +`</div>`
                    +`<table class="table">`
                        +`<thead>`;
                            res.data_parent.map(function(value, index){
                                if(value!=null){
                                    let label_parent = '';
                                    switch (index+1) {
                                        case 1:
                                            label_parent = "Kegiatan Utama"
                                            break;

                                        case 2:
                                            label_parent = "Rencana Aksi"
                                            break;
                                        
                                        case 3:
                                            label_parent = "Uraian Kegiatan Rencana Aksi"
                                            break;

                                        default:
                                            label_parent = "-";
                                            break;
                                    }

                                    let tipe_parent = tipe-1;
                                    let detail_parent = "<a href='javascript:void(0)' onclick='detail_parent("+tipe_parent+"); return false;' title='Detail Parent'><i class='dashicons dashicons-info'></i></a>";
                                    renaksi += ``
                                        +`<tr>`
                                            +`<th class="text-center" style="width: 160px;">${label_parent}</th>`
                                            +`<th class="text-left">${value} ${detail_parent}</th>`
                                        +`</tr>`;
                                }
                            });
                        renaksi+=`</thead>`
                    +`</table>`
                    +'<table class="table" id="'+id_tabel+'" parent_renaksi="'+parent_renaksi+'" parent_pokin="'+parent_pokin+'">'
                        +`<thead>`
                            +`<tr class="table-secondary">`
                                +`<th class="text-center" style="width:40px;">No</th>`
                                +`<th class="text-center" style="width:300px;">Label Pokin</th>`
                                +`<th class="text-center">`+title+`</th>`
                                +`<th class="text-center" style="width:200px;">Aksi</th>`
                            +`</tr>`
                        +`</thead>`
                        +`<tbody>`;
                            res.data.map(function(value, index){
                                var label_pokin = '';
                                var id_pokin = 0;
                                var tombol_detail = '';
                                var id_parent_cascading = 0;
                                var label_cascading = '';
                                if(tipe == 1){
                                    label_pokin = value['label_pokin_2'];
                                    id_pokin = value['id_pokin_2'];
                                    id_parent_cascading = value['kode_cascading_sasaran'];
                                    label_cascading = value['label_cascading_sasaran'] != null ? value['label_cascading_sasaran'] : '-';
                                    tombol_detail = ''
                                        +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning" onclick="lihat_rencana_aksi(${value.id}, `+(tipe+1)+`, `+id_pokin+`, '`+id_parent_cascading+`')" title="Lihat Rencana Aksi"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `;
                                }else if(tipe == 2){
                                    label_pokin = value['label_pokin_3'];
                                    id_pokin = value['id_pokin_3'];
                                    id_parent_cascading = value['kode_cascading_program'];
                                    label_cascading = value['label_cascading_program'] != null ? value['kode_cascading_program']+' '+value['label_cascading_program'] : '-';
                                    tombol_detail = ''
                                        +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning" onclick="lihat_rencana_aksi(${value.id}, `+(tipe+1)+`, `+id_pokin+`, '`+id_parent_cascading+`')" title="Lihat Uraian Kegiatan Rencana Aksi"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `;
                                }else if(tipe == 3){
                                    label_pokin = value['label_pokin_4'];
                                    id_parent_cascading = value['kode_cascading_kegiatan'];
                                    label_cascading = value['label_cascading_kegiatan'] != null ? value['kode_cascading_kegiatan']+' '+value['label_cascading_kegiatan'] : '-';
                                    id_pokin = value['id_pokin_4'];
                                }
                                renaksi += ``
                                    +`<tr id="kegiatan_utama_${value.id}">`
                                        +`<td class="text-center">${index+1}</td>`
                                        +`<td class="label_pokin">`+label_pokin+`</td>`
                                        +`<td class="label_renaksi">${value.label}</td>`
                                        +`<td class="text-center">`
                                            +`<a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="tambah_indikator_rencana_aksi(${value.id}, ${tipe})" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
                                            +tombol_detail
                                            +`<a href="javascript:void(0)" onclick="edit_rencana_aksi(${value.id}, `+tipe+`)" data-id="${value.id}" class="btn btn-sm btn-primary edit-kegiatan-utama" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
                                            +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger" onclick="hapus_rencana_aksi(${value.id}, ${tipe})" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
                                        +`</td>`
                                    +`</tr>`;

                                let indikator = value.indikator;
                                if(indikator.length > 0){
                                    renaksi += ``
                                    +'<td colspan="5" style="padding: 0;">'
                                        +`<table class="table" style="margin: .5rem 0 2rem;">`
                                            +`<thead>`
                                                +`<tr class="table-secondary">`
                                                    +`<th class="text-center" style="width:20px">No</th>`
                                                    +`<th class="text-center">Indikator</th>`
                                                    +`<th class="text-center" style="width:120px;">Satuan</th>`
                                                    +`<th class="text-center" style="width:50px;">Target Awal</th>`
                                                    +`<th class="text-center" style="width:50px;">Target Akhir</th>`
                                                    +`<th class="text-center" style="width:50px;">Target TW 1</th>`
                                                    +`<th class="text-center" style="width:50px;">Target TW 2</th>`
                                                    +`<th class="text-center" style="width:50px;">Target TW 3</th>`
                                                    +`<th class="text-center" style="width:50px;">Target TW 4</th>`
                                                    +`${header_pagu}`
                                                    +`${header_skpd}`
                                                    +`<th class="text-center" style="width:110px">Aksi</th>`
                                                +`</tr>`
                                            +`</thead>`
                                            +`<tbody>`;
                                    indikator.map(function(b, i){
                                        let rencana_pagu = b.rencana_pagu != null ? b.rencana_pagu : 0;
                                        let nama_skpd = b.nama_skpd != null ? b.nama_skpd : 0;
                                        let realisasi_pagu = b.realisasi_pagu != null ? b.realisasi_pagu : 0;
                                        let val_pagu = '';
                                        let skpd = '';
                                        if(tipe == 3){
                                            val_pagu = ''    
                                                +`<td class="text-center">${rencana_pagu}</td>`;
                                            skpd = ''    
                                                +`<td class="text-left">${nama_skpd}</td>`;
                                        }
                                        renaksi += ``
                                            +`<tr>`
                                                +`<td class="text-center">${index+1}.${i+1}</td>`
                                                +`<td>${b.indikator}</td>`
                                                +`<td class="text-center">${b.satuan}</td>`
                                                +`<td class="text-right">${b.target_awal}</td>`
                                                +`<td class="text-right">${b.target_akhir}</td>`
                                                +`<td class="text-right">${b.target_1}</td>`
                                                +`<td class="text-right">${b.target_2}</td>`
                                                +`<td class="text-right">${b.target_3}</td>`
                                                +`<td class="text-right">${b.target_4}</td>`
                                                +`${val_pagu}`
                                                +`${skpd}`
                                                +`<td class="text-center">`
                                                    +`<a href="javascript:void(0)" data-id="${b.id}" class="btn btn-sm btn-primary" onclick="edit_indikator(${b.id}, `+tipe+`)" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
                                                    +`<a href="javascript:void(0)" data-id="${b.id}" class="btn btn-sm btn-danger" onclick="hapus_indikator(${b.id}, `+tipe+`);" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
                                                +`</td>`
                                            +`</tr>`;
                                    });
                                    renaksi += ``
                                            +'</tbody>'
                                        +'</table>'
                                    +'</td>';
                                }
                            });
                            renaksi+=''
                        +`<tbody>`
                    +`</table>`;

                jQuery("#nav-level-"+tipe).html(renaksi);
                jQuery('.nav-tabs a[href="#nav-level-'+tipe+'"]').tab('show');
                resolve();
            }
        });
    });
}

function tambah_renaksi_2(tipe) {
    return new Promise(function(resolve, reject) {
        setTimeout(function() {
            if (tipe) {
                resolve();  
            } else {
                reject('Invalid tipe');
            }
        }, 1000);

        var parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
        var parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
        var title = 'Rencana Aksi';
        var level_pokin = 3;
            var title = 'Rencana Aksi';
            if(tipe == 3){
                level_pokin = 4;
                title = 'Uraian Rencana Aksi';
                parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
                parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
            }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": "get_data_pokin_pemda",
                "level": level_pokin,
                "parent": parent_pokin,
                "api_key": esakip.api_key,
                "tipe_pokin": "pemda",
                "id_jadwal": id_jadwal,
            },
            dataType: "json",
            success: function(res){
                var html = '<option value="">Pilih Pokin Level ' + level_pokin + '</option>';
                res.data.map(function(value, index) {
                    html += '<option value="' + value.id + '">' + value.label + '</option>';
                });
                jQuery('#wrap-loading').hide();
                jQuery("#modal-crud").find('.modal-title').html('Tambah ' + title);
                var onchange_pokin = '';
                jQuery("#modal-crud").find('.modal-body').html(''
                    + '<form>'
                        + '<input type="hidden" id="id_renaksi" value=""/>'
                        + '<div class="form-group">'
                            + '<label for="pokin-level-1">Pilih Pokin Level ' + level_pokin + '</label>'
                            + '<select class="form-control" name="pokin-level-1" id="pokin-level-1" ' + onchange_pokin + '>'
                                + html
                            + '</select>'
                        + '</div>'
                        + '<div class="form-group">'
                            + '<textarea class="form-control" name="label" id="label_renaksi" placeholder="Tuliskan ' + title + '..."></textarea>'
                        + '</div>'
                    + '</form>');
                jQuery("#modal-crud").find('.modal-footer').html(''
                    + '<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>'
                    + '<button type="button" class="btn btn-success" onclick="simpan_data_renaksi(' + tipe + ')">'
                        + 'Simpan'
                    + '</button>');
                jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
                jQuery("#modal-crud").find('.modal-dialog').css('width', '');
                jQuery("#modal-crud").modal('show');
                jQuery('#pokin-level-1').select2({ width: '100%' });
                if (tipe == 3) {
                    jQuery('#pokin-level-2').select2({ width: '100%' });
                }
            }
        });
    });
}


function hapus_rencana_aksi(id, tipe){
    var title = '';
    var parent_pokin = 0;
    var parent_renaksi = 0;
    if(tipe == 1){
        title = 'Kegiatan Utama';
    }else if(tipe == 2){
        title = 'Rencana Aksi';
        parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
        parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
    }else if(tipe == 3){
        title = 'Uraian Kegiatan Rencana Aksi';
        parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
        parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
    }

    if(confirm('Apakah kamu yakin untuk menghapus '+title+'?')){
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_rencana_aksi_pemda',
                api_key: esakip.api_key,
                id: id,
                tipe: tipe,
                tahun_anggaran: '<?php echo $input['tahun'] ?>',
                id_tujuan: '<?php echo $input['id_tujuan'] ?>'
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                alert(response.message);
                if (response.status === 'success') {
                    if(tipe==1){
                        kegiatanUtama();
                    }else{
                        lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin)
                    }
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data Rencana Aksi!');
            }
        });
    }
}

function simpan_data_renaksi(tipe) {
    var parent_pokin = 0;
    var parent_renaksi = 0;
    switch (tipe) {
        case 2:
            parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
            parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
            break;
        case 3:
            parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
            parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
            break;
        default:
            parent_pokin = 0;
            parent_renaksi = 0;
    }

    var label_renaksi = jQuery('#label_renaksi').val();
    var id_pokin_1 = jQuery('#pokin-level-1').val();
    var id_pokin_2 = jQuery('#pokin-level-2').val();

    var label_pokin_1 = (id_pokin_1 !== '') ? jQuery('#pokin-level-1 option:selected').text() : '';
    var label_pokin_2 = (id_pokin_2 !== '') ? jQuery('#pokin-level-2 option:selected').text() : '';

    if (label_renaksi.trim() === '') {
        return alert('Kegiatan Utama tidak boleh kosong!');
    }

    jQuery('#wrap-loading').show();
    jQuery.ajax({
        url: esakip.url,
        type: "post",
        data: {
            "action": 'tambah_renaksi_pemda',
            "api_key": esakip.api_key,
            "tipe_pokin": "pemda",
            "id": jQuery('#id_renaksi').val(),
            "id_pokin_1": id_pokin_1 || null, 
            "id_pokin_2": id_pokin_2 || null, 
            "label_pokin_1": label_pokin_1,   
            "label_pokin_2": label_pokin_2,   
            "label_renaksi": label_renaksi,
            "level": tipe,
            "parent": parent_renaksi,
            "tahun_anggaran": <?php echo $input['tahun']; ?>,
            "id_jadwal": id_jadwal,
            "id_tujuan": <?php echo $input['id_tujuan']; ?>
        },
        dataType: "json",
        success: function(res) {
            jQuery('#wrap-loading').hide();
            alert(res.message);
            if (res.status === 'success') {
                jQuery("#modal-crud").modal('hide');
                lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin);
            }
        }
    });
}


function edit_rencana_aksi(id, tipe){
    if(tipe==1){
        tambah_rencana_aksi().then(function(){
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_rencana_aksi_pemda',
                    api_key: esakip.api_key,
                    id: id,
                    tahun_anggaran: '<?php echo $input['tahun'] ?>',
                    id_tujuan: '<?php echo $input['id_tujuan'] ?>'
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if(response.status == 'error'){
                        alert(response.message)
                    }else if(response.data != null){
                        jQuery('#id_renaksi').val(id);
                        jQuery("#modal-crud").find('.modal-title').html('Edit Kegiatan Utama');
                        jQuery('#pokin-level-2').val(response.data.id_pokin_2).trigger('change');
                        jQuery('#label_renaksi').val(response.data.label);
                    }
                }
            });
        });
    }else{
        tambah_renaksi_2(tipe).then(function(){
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_rencana_aksi_pemda',
                    api_key: esakip.api_key,
                    id: id,
                    tahun_anggaran: '<?php echo $input['tahun'] ?>',
                    id_tujuan: '<?php echo $input['id_tujuan'] ?>'
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if(response.status == 'error'){
                        alert(response.message)
                    }else if(response.data != null){
                        jQuery('#id_renaksi').val(id);
                        if(tipe == 2){
                            jQuery("#modal-crud").find('.modal-title').html('Edit Rencana Aksi');
                            jQuery('#pokin-level-1').val(response.data.id_pokin_3).trigger('change');
                        }else if(tipe == 3){
                            jQuery("#modal-crud").find('.modal-title').html('Edit Uraian Rencana Aksi');
                            jQuery('#pokin-level-1').val(response.data.id_pokin_4).trigger('change');
                        }
                        jQuery('#label_renaksi').val(response.data.label);
                    }
                }
            });
        });
    }
}

function edit_indikator(id, tipe){
    jQuery('#wrap-loading').show();
    jQuery.ajax({
        url: esakip.url,
        type: 'POST',
        data: {
            action: 'get_indikator_rencana_aksi_pemda',
            api_key: esakip.api_key,
            id: id,
            tahun_anggaran: '<?php echo $input['tahun'] ?>',
            id_tujuan: '<?php echo $input['id_tujuan'] ?>'
        },
        dataType: 'json',
        success: function(response) {
            if(
                response.status == 'success' 
                && response.data != null
            ){
                
                let rencana_pagu = response.data.rencana_pagu != null ? response.data.rencana_pagu : 0;
                tambah_indikator_rencana_aksi(response.data.id_renaksi, tipe);
                jQuery("#modal-crud").find('.modal-title').text(jQuery("#modal-crud").find('.modal-title').text().replace('Tambah', 'Edit'));
                jQuery('#id_label_indikator').val(id);
                jQuery('#indikator').val(response.data.indikator);
                jQuery('#id_skpd').val(response.data.id_skpd).trigger('change').prop('disabled', false);
                jQuery('#satuan_indikator').val(response.data.satuan);
                jQuery('#target_akhir').val(response.data.target_akhir);
                jQuery('#target_awal').val(response.data.target_awal);
                jQuery('#target_tw_1').val(response.data.target_1);
                jQuery('#target_tw_2').val(response.data.target_2);
                jQuery('#target_tw_3').val(response.data.target_3);
                jQuery('#target_tw_4').val(response.data.target_4);
                jQuery('#rencana_pagu').val(response.data.rencana_pagu);
                jQuery('#mitra_bidang').val(response.data.mitra_bidang);
            }else if(response.status == 'error'){
                alert(response.message);
            }
            jQuery('#wrap-loading').hide();
        }
    });
}

function hapus_indikator(id, tipe){
    var title = '';
    var parent_renaksi = 0;
    var parent_pokin = 0;
    if(tipe == 1){
        title = 'Kegiatan Utama';
    }else if(tipe == 2){
        title = 'Rencana Aksi';
        var parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
        var parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
    }else if(tipe == 3){
        title = 'Uraian Kegiatan Rencana Aksi';
        var parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
        var parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
    }
    if(confirm('Apakah kamu yakin untuk menghapus indikator '+title+'?')){
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_indikator_rencana_aksi_pemda',
                api_key: esakip.api_key,
                id: id,
                tahun_anggaran: '<?php echo $input['tahun'] ?>',
                id_tujuan: '<?php echo $input['id_tujuan'] ?>'
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                alert(response.message);
                if (response.status === 'success') {
                    if(tipe==1){
                        kegiatanUtama();
                    }else{
                        lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin);
                    }
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data Rencana Aksi!');
            }
        });
    }
}

function get_skpd(no_loading=false) {
    return new Promise(function(resolve, reject){
        var id_skpd = jQuery('#id_skpd').val();
        if(id_skpd == ''){
            jQuery('#daftar_skpd tbody').html('');
            return;
        }
        if(!no_loading){
            jQuery("#wrap-loading").show();
        }
        if(typeof global_response_skpd == 'undefined'){
            global_response_skpd = {};
        }
        if(!global_response_skpd[id_skpd]){
            jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type:'post',
                data:{
                    'action' : 'get_skpd_renaksi',
                    api_key: esakip.api_key,
                    'id_skpd': id_skpd
                },
                dataType: 'json',
                success:function(response){
                    if(!no_loading){
                        jQuery("#wrap-loading").hide();
                    }
                    if(response.status == 'success'){
                        window.global_response_skpd[id_skpd] = response;
                        jQuery('#daftar_skpd tbody');
                        jQuery('#id_skpd_1').html(global_response_skpd[id_skpd]);
                        jQuery('#id_skpd_1').select2({'width': '100%'});
                        return resolve();
                    }else{
                        alert(`GAGAL! \n${response.message}`);
                    }
                }
            });
        }else{
            jQuery('#id_skpd').html(global_response_skpd[id_skpd].html).trigger('change');
            return resolve();
        }
    });
}

function get_pokin_renaksi(parent, level, tag){
    jQuery('#wrap-loading').show();
    return new Promise(function(resolve, reject){
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": "get_data_pokin_pemda",
                "level": level,
                "parent": <?php echo $input['id_pokin']; ?>,
                "api_key": esakip.api_key,
                "tipe_pokin": "pemda",
                "id_jadwal": id_jadwal,
            },
            dataType: "json",
            success: function(res){
                var default_value = jQuery('#'+tag).attr('val-id');
                var html = '<option value="">Pilih Pokin Level '+level+'</option>';
                res.data.map(function(value, index){
                    var selected = '';
                    if(value.id==default_value){
                        selected = 'selected';
                    }
                    html += '<option value="'+value.id+'" '+selected+'>'+value.label+'</option>';
                });
                jQuery('#'+tag).attr('val-id', '');

                jQuery('#'+tag).html(html).trigger('change');
                jQuery('#wrap-loading').hide();
                resolve();
            }
        });
    });
}
</script>
