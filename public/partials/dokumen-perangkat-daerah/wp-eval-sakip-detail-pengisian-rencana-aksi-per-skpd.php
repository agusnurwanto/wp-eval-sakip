<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2022',
), $atts);

if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}
$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$data_id_jadwal = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        id_jadwal,
        id_jadwal_wp_sipd
    FROM esakip_pengaturan_rencana_aksi
    WHERE tahun_anggaran =%d
    AND active=1
", $input['tahun']), ARRAY_A);

if(empty($data_id_jadwal['id_jadwal'])){
    $id_jadwal = 0;
}else{
    $id_jadwal = $data_id_jadwal['id_jadwal'];
}
$cek_id_jadwal = empty($data_id_jadwal['id_jadwal']) ? 0 : 1;

if(empty($data_id_jadwal['id_jadwal_wp_sipd'])){
    $id_jadwal_wpsipd = 0;
}else{
    $id_jadwal_wpsipd = $data_id_jadwal['id_jadwal_wp_sipd'];
}
$cek_id_jadwal_wpsipd = empty($data_id_jadwal['id_jadwal_wp_sipd']) ? 0 : 1;

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

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
$is_administrator = in_array('administrator', $user_roles);

$admin_role_pemda = array(
    'admin_bappeda',
    'admin_ortala'
);

$this_jenis_role = (in_array($user_roles[0], $admin_role_pemda)) ? 1 : 2 ;

$cek_settingan_menu = $wpdb->get_var(
    $wpdb->prepare(
    "SELECT 
        jenis_role
    FROM esakip_menu_dokumen 
    WHERE nama_dokumen='Rencana Aksi'
      AND user_role='perangkat_daerah' 
      AND active = 1
      AND tahun_anggaran=%d
", $input['tahun'])
);

$hak_akses_user = ($cek_settingan_menu == $this_jenis_role || $cek_settingan_menu == 3 || $is_administrator) ? true : false;
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

    #modal-renaksi thead th {
        font-size: medium !important;
    }

    #modal-renaksi .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    .table_dokumen_rencana_aksi {
        font-family:'Open Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; 
        border-collapse: collapse; 
        width: 2900px; 
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
</style>

<!-- Table -->
<div class="container-md">
    <div id="cetak" title="Rencana Aksi Perangkat Daerah">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Rencana Aksi <br><?php echo $skpd['nama_skpd'] ?><br> Tahun Anggaran <?php echo $input['tahun']; ?></h1>
            <?php if (!$is_admin_panrb && $hak_akses_user): ?>
                <div id="action" class="action-section hide-excel"></div>
            <?php endif; ?>
            <div class="wrap-table">
                <table cellpadding="2" cellspacing="0" class="table_dokumen_rencana_aksi table table-bordered">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="text-center" rowspan="2" style="width: 85px;">No</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">KEGIATAN UTAMA</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">INDIKATOR KEGIATAN UTAMA</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">RENCANA AKSI</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">OUTCOME/OUTPUT</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">URAIAN KEGIATAN RENCANA AKSI</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">INDIKATOR</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">URAIAN TEKNIS KEGIATAN</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">INDIKATOR</th>
                            <th class="text-center" rowspan="2" style="width: 100px;">SATUAN</th>
                            <th class="text-center" colspan="6" style="width: 400px;">TARGET KEGIATAN PER TRIWULAN</th>
                            <th class="text-center" colspan="4" style="width: 250px;">REALISASI KEGIATAN PER TRIWULAN</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">CAPAIAN REALISASI (%)</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">RENCANA PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">ALOKASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">REALISASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">CAPAIAN REALISASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">PAGU SUB KEGIATAN</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">KETERANGAN</th>
                        </tr>
                        <tr>
                            <th>AWAL</th>
                            <th>TW-I</th>
                            <th>TW-II</th>
                            <th>TW-III</th>
                            <th>TW-IV</th>
                            <th>AKHIR</th>
                            <th>TW-I</th>
                            <th>TW-II</th>
                            <th>TW-III</th>
                            <th>TW-IV</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tahun Tabel -->
<div id="tahunContainer" class="container-md">
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Tambah Dokumen</h5>
                <h5 class="modal-title" id="editModalLabel">Edit Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $id_skpd; ?>" id="idSkpd">
                    <input type="hidden" value="<?php echo $input['tahun']; ?>" id="tahunAnggaran">
                    <input type="hidden" value="" id="idDokumen">
                    <div class="form-group">
                        <label for="perangkatDaerah">Perangkat Daerah</label>
                        <input type="text" class="form-control" id="perangkatDaerah" name="perangkatDaerah" style="text-transform: uppercase;" value="<?php echo $skpd['nama_skpd']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="fileUpload">Pilih File</label>
                        <input type="file" class="form-control-file" id="fileUpload" name="fileUpload" accept="application/pdf" required>
                        <div style="padding-top: 10px; padding-bottom: 10px;"><a id="fileUploadExisting" target="_blank"></a></div>
                    </div>
                    <div class="alert alert-warning mt-2" role="alert">
                        Maksimal ukuran file: <?php echo get_option('_crb_maksimal_upload_dokumen_esakip'); ?> MB. Format file yang diperbolehkan: PDF.
                    </div>
                    <div class="form-group">
                        <label for="nama_file">Nama Dokumen</label>
                        <input type="text" class="form-control" id="nama_file" name="nama_file" rows="3" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="submit_dokumen(this); return false">Unggah</button>
                </form>
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

<!-- Modal Renaksi -->
<div class="modal fade" id="modal-renaksi" role="dialog" data-backdrop="static" aria-hidden="true">'
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
                        <a class="nav-item nav-link" id="nav-level-4-tab" data-toggle="tab" href="#nav-level-4" role="tab" aria-controls="nav-level-4" aria-selected="false">Uraian Teknis Kegiatan</a>
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

    let id_jadwal = <?php echo $cek_id_jadwal; ?>;
    let id_jadwal_wpsipd = <?php echo $cek_id_jadwal_wpsipd; ?>;
    if(id_jadwal == 0 || id_jadwal_wpsipd == 0){
        alert("Jadwal RENSTRA untuk data Pokin belum disetting.\nSetting Jadwal RENSTRA ada di admin dashboard di menu Monev Rencana Aksi -> Monev Rencana Aksi Setting")
    }

    window.id_jadwal = <?php echo $id_jadwal; ?>;

    window.id_jadwal_wpsipd = <?php echo $id_jadwal_wpsipd; ?>;

    getTablePengisianRencanaAksi();
    jQuery("#fileUpload").on('change', function() {
        var id_dokumen = jQuery('#idDokumen').val();
        if (id_dokumen == '') {
            var name = jQuery("#fileUpload").prop('files')[0].name;
            jQuery('#nama_file').val(name);
        }
    });

    jQuery("#tambah-rencana-aksi").on('click', function(){
        kegiatanUtama();
    });
});

function simpan_indikator_renaksi(tipe){
    var id = jQuery('#id_label_indikator').val();
    var id_label = jQuery('#id_label').val();
    var indikator = jQuery('#indikator').val();
    if(indikator == ''){
        return alert('Indikator tidak boleh kosong!')
    }
    var satuan = jQuery('#satuan_indikator').val();
    if(satuan == ''){
        return alert('Satuan tidak boleh kosong!')
    }
    var rencana_pagu = jQuery('#rencana_pagu').val();
    if(rencana_pagu == ''){
        rencana_pagu = 0;
    }
    // var realisasi_pagu = jQuery('#realisasi_pagu').val();
    // if(realisasi_pagu == ''){
    //     realisasi_pagu = 0;
    // }
    var target_awal = jQuery('#target_awal').val();
    if(target_awal == ''){
        return alert('Target awal tidak boleh kosong!')
    }
    var target_akhir = jQuery('#target_akhir').val();
    if(target_akhir == ''){
        return alert('Target akhir tidak boleh kosong!')
    }
    var target_tw_1 = jQuery('#target_tw_1').val();
    if(target_tw_1 == ''){
        return alert('Target triwulan 1 tidak boleh kosong!')
    }
    var target_tw_2 = jQuery('#target_tw_2').val();
    if(target_tw_2 == ''){
        return alert('Target triwulan 2 tidak boleh kosong!')
    }
    var target_tw_3 = jQuery('#target_tw_3').val();
    if(target_tw_3 == ''){
        return alert('Target triwulan 3 tidak boleh kosong!')
    }
    var target_tw_4 = jQuery('#target_tw_4').val();
    if(target_tw_4 == ''){
        return alert('Target triwulan 4 tidak boleh kosong!')
    }
    jQuery('#wrap-loading').show();
    jQuery.ajax({
        url: esakip.url,
        type: "post",
        data: {
            "action": 'create_indikator_renaksi',
            "api_key": esakip.api_key,
            "tipe_pokin": "opd",
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
            "tahun_anggaran": <?php echo $input['tahun']; ?>,
            "id_skpd": <?php echo $id_skpd; ?>
        },
        dataType: "json",
        success: function(res){
            jQuery('#wrap-loading').hide();
            alert(res.message);
            if(res.status=='success'){
                jQuery("#modal-crud").modal('hide');
                if(tipe == 1){
                    kegiatanUtama();
                }else if(tipe == 2){
                    var parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
                    var parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
                    var parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
                    lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading);
                }else if(tipe == 3){
                    var parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
                    var parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
                    var parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
                    lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading);
                }else if(tipe == 4){
                    var parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
                    var parent_pokin = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_pokin');
                    var parent_cascading = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_cascading');
                    lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading);
                }
                getTablePengisianRencanaAksi(1);
            }
        }
    });
};

function tambah_rencana_aksi(){
    return get_tujuan_sasaran_cascading('sasaran')
    .then(function(){
        return new Promise(function(resolve, reject){
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": "get_data_pokin",
                    "level": 1,
                    "parent": 0,
                    "api_key": esakip.api_key,
                    "tipe_pokin": "opd",
                    "id_jadwal": id_jadwal,
                    "id_skpd": <?php echo $id_skpd; ?>
                },
                dataType: "json",
                success: function(res){
                    var html = '<option value="">Pilih Pokin Level 1</option>';
                    res.data.map(function(value, index){
                        html += '<option value="'+value.id+'">'+value.label+'</option>';
                    });
                    jQuery('#wrap-loading').hide();
                    jQuery("#modal-crud").find('.modal-title').html('Tambah Rencana Aksi');
                    jQuery("#modal-crud").find('.modal-body').html(''
                        +`<form id="form-renaksi">`
                            +'<input type="hidden" id="id_renaksi" value=""/>'
                            +`<div class="form-group">`
                                +`<label for="pokin-level-1">Pilih Pokin Level 1</label>`
                                +`<select class="form-control" name="pokin-level-1" id="pokin-level-1" onchange="get_data_pokin_2(this.value, 2, 'pokin-level-2')">`
                                    +html
                                +`</select>`
                            +`</div>`
                            +`<div class="form-group">`
                                +`<label for="pokin-level-2">Pilih Pokin Level 2</label>`
                                +`<select class="form-control" name="pokin-level-2" id="pokin-level-2">`
                                +`</select>`
                            +`</div>`
                            +`<div class="form-group">`
                                +`<textarea class="form-control" name="label" id="label_renaksi" placeholder="Tuliskan Kegiatan Utama..."></textarea>`
                            +`</div>`
                            +`<div class="form-group">`
                                +`<label for="sasaran-cascading">Pilih Sasaran Cascading</label>`
                                +`<select class="form-control" name="cascading-renstra" id="cascading-renstra">`
                                +`</select>`
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
                    jQuery('#pokin-level-1').select2({width: '100%'});
                    jQuery('#pokin-level-2').select2({width: '100%',placeholder: "Pilih Pokin Level 2"}).place;

                    var key = 'sasaran'+'-'+'x.xx';
                    if(data_sasaran_cascading[key] != undefined){
                        let html_cascading = '<option value="">Pilih Sasaran Cascading</option>';
                        if(data_sasaran_cascading[key].data !== null){
                            data_sasaran_cascading[key].data.map(function(value, index){
                                if(value.id_unik_indikator == null){
                                    html_cascading += '<option value="'+value.kode_bidang_urusan+'">'+value.sasaran_teks+'</option>';
                                }
                            });
                        }
                        jQuery("#cascading-renstra").html(html_cascading);
                        jQuery('#cascading-renstra').select2({width: '100%'});
                    }

                    resolve();
                }
            });
        });
    });
}

function get_tujuan_sasaran_cascading(jenis='sasaran', parent_cascading='x.xx'){
    return new Promise(function(resolve, reject){
        if(typeof data_sasaran_cascading == 'undefined'){
            data_sasaran_cascading = {};
        }
        if(typeof data_program_cascading == 'undefined'){
            data_program_cascading = {};
        }
        if(typeof data_kegiatan_cascading == 'undefined'){
            data_kegiatan_cascading = {};
        }
        if(typeof data_sub_kegiatan_cascading == 'undefined'){
            data_sub_kegiatan_cascading = {};
        }
        var key = jenis+'-'+parent_cascading;
        if(jenis == 'sasaran'){
            if(typeof data_sasaran_cascading[key] == 'undefined'){
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    url: esakip.url,
                    type: "post",
                    data: {
                        "action": 'get_tujuan_sasaran_cascading',
                        "api_key": esakip.api_key,
                        "id_skpd": <?php echo $id_skpd; ?>,
                        "tahun_anggaran": <?php echo $input['tahun']; ?>,
                        "jenis": jenis,
                        "id_jadwal_wpsipd": id_jadwal_wpsipd
                    },
                    dataType: "json",
                    success: function(response){
                        if(response.status){
                            window.data_sasaran_cascading[key] = response;
                        }else{
                            alert("Data cascading tidak ditemukan");
                        }
                        resolve();
                    }
                });
            }else{
                resolve();
            }
        }else if(jenis == 'program'){
            if(typeof data_program_cascading[key] == 'undefined'){
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    url: esakip.url,
                    type: "post",
                    data: {
                        "action": 'get_tujuan_sasaran_cascading',
                        "api_key": esakip.api_key,
                        "id_skpd": <?php echo $id_skpd; ?>,
                        "tahun_anggaran": <?php echo $input['tahun']; ?>,
                        "jenis": jenis,
                        "parent_cascading": parent_cascading
                    },
                    dataType: "json",
                    success: function(response){
                        if(response.status){
                            window.data_program_cascading[key] = response;
                        }else{
                            alert("Data cascading tidak ditemukan");
                        }
                        resolve();
                    }
                });
            }else{
                resolve();
            }
        }else if(jenis == 'kegiatan'){
            if(typeof data_kegiatan_cascading[key] == 'undefined'){
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    url: esakip.url,
                    type: "post",
                    data: {
                        "action": 'get_tujuan_sasaran_cascading',
                        "api_key": esakip.api_key,
                        "id_skpd": <?php echo $id_skpd; ?>,
                        "tahun_anggaran": <?php echo $input['tahun']; ?>,
                        "jenis": jenis,
                        "parent_cascading": parent_cascading
                    },
                    dataType: "json",
                    success: function(response){
                        if(response.status){
                            window.data_kegiatan_cascading[key] = response;
                        }else{
                            alert("Data cascading tidak ditemukan");
                        }
                        resolve();
                    }
                });
            }else{
                resolve();
            }
        }else if(jenis == 'sub_kegiatan'){
            if(typeof data_sub_kegiatan_cascading[key] == 'undefined'){
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    url: esakip.url,
                    type: "post",
                    data: {
                        "action": 'get_tujuan_sasaran_cascading',
                        "api_key": esakip.api_key,
                        "id_skpd": <?php echo $id_skpd; ?>,
                        "tahun_anggaran": <?php echo $input['tahun']; ?>,
                        "jenis": jenis,
                        "parent_cascading": parent_cascading
                    },
                    dataType: "json",
                    success: function(response){
                        if(response.status){
                            window.data_sub_kegiatan_cascading[key] = response;
                        }else{
                            alert("Data cascading tidak ditemukan");
                        }
                        resolve();
                    }
                });
            }else{
                resolve();
            }
        }else{
            resolve();
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
                    action: 'get_rencana_aksi',
                    api_key: esakip.api_key,
                    id: id,
                    tahun_anggaran: '<?php echo $input['tahun'] ?>'
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if(response.status == 'error'){
                        alert(response.message)
                    }else if(response.data != null){
                        jQuery('#id_renaksi').val(id);
                        jQuery("#modal-crud").find('.modal-title').html('Edit Kegiatan Utama');
                        jQuery('#pokin-level-2').attr('val-id', response.data.id_pokin_2);
                        jQuery('#pokin-level-1').val(response.data.id_pokin_1).trigger('change');
                        jQuery('#label_renaksi').val(response.data.label);
                        jQuery('#cascading-renstra').val(response.data.kode_cascading_sasaran).trigger('change');
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
                    action: 'get_rencana_aksi',
                    api_key: esakip.api_key,
                    id: id,
                    tahun_anggaran: '<?php echo $input['tahun'] ?>'
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
                            jQuery('#cascading-renstra').val(response.data.kode_cascading_program).trigger('change');
                        }else if(tipe == 3){
                            jQuery("#modal-crud").find('.modal-title').html('Edit Uraian Rencana Aksi');
                            jQuery('#pokin-level-2').attr('val-id', response.data.id_pokin_5);
                            jQuery('#pokin-level-1').val(response.data.id_pokin_4).trigger('change');
                            jQuery('#cascading-renstra').val(response.data.kode_cascading_kegiatan).trigger('change');
                        }else if(tipe == 4){
                            jQuery("#modal-crud").find('.modal-title').html('Edit Uraian Teknis Kegiatan');
                            jQuery('#pokin-level-2').attr('val-id', response.data.id_pokin_5);
                            jQuery('#pokin-level-1').val(response.data.id_pokin_5).trigger('change');
                            jQuery('#cascading-renstra').val(response.data.kode_cascading_sub_kegiatan).trigger('change');
                        }
                        jQuery('#label_renaksi').val(response.data.label);
                    }
                }
            });
        });
    }
}

function get_data_pokin_2(parent, level, tag){
    jQuery('#wrap-loading').show();
    return new Promise(function(resolve, reject){
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": "get_data_pokin",
                "level": level,
                "parent": parent,
                "api_key": esakip.api_key,
                "tipe_pokin": "opd",
                "id_jadwal": id_jadwal,
                "id_skpd": <?php echo $id_skpd; ?>
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
                // reset default value
                jQuery('#'+tag).attr('val-id', '');

                jQuery('#'+tag).html(html).trigger('change');
                jQuery('#wrap-loading').hide();
                resolve();
            }
        });
    });
}

function kegiatanUtama(){
    jQuery("#wrap-loading").show();
    return new Promise(function(resolve, reject){
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": "get_data_renaksi",
                "level": 1,
                "parent": 0,
                "api_key": esakip.api_key,
                "tipe_pokin": "opd",
                "id_skpd": <?php echo $id_skpd; ?>
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
                                +`<th class="text-center">Sasaran Cascading</th>`
                                +`<th class="text-center" style="width:200px;">Aksi</th>`
                            +`</tr>`
                        +`</thead>`
                        +`<tbody>`;
                            res.data.map(function(value, index){
                                let label_cascading = value.label_cascading_sasaran != null ? value.label_cascading_sasaran : '-';
                                kegiatanUtama += ``
                                    +`<tr id="kegiatan_utama_${value.id}">`
                                        +`<td class="text-center">${index+1}</td>`
                                        +`<td class="label_pokin">${value.label_pokin_2}</td>`
                                        +`<td class="label_renaksi">${value.label}</td>`
                                        +`<td class="label_cascading">${label_cascading}</td>`
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
                            kegiatanUtama+=''
                        +`<tbody>`
                    +`</table>`;

                jQuery("#nav-level-1").html(kegiatanUtama);
                jQuery('.nav-tabs a[href="#nav-level-1"]').tab('show');
                jQuery('#modal-renaksi').modal('show');
                resolve();
            }
        });
    });
}

function submit_tahun_rencana_aksi() {
    let id = jQuery("#idDokumen").val();
    if (id == '') {
        return alert('id tidak boleh kosong');
    }

    let tahunAnggaran = jQuery("#tahunAnggaran").val();
    if (tahunAnggaran == '') {
        return alert('Tahun Anggaran tidak boleh kosong');
    }

    jQuery('#wrap-loading').show();
    jQuery.ajax({
        url: esakip.url,
        type: 'POST',
        data: {
            action: 'c',
            id: id,
            tahunAnggaran: tahunAnggaran,
            api_key: esakip.api_key
        },
        dataType: 'json',
        success: function(response) {
            console.log(response);
            jQuery('#wrap-loading').hide();
            if (response.status === 'success') {
                alert(response.message);
                jQuery('#tahunModal').modal('hide');
                getTableTahun();
                getTableRencanaAksi();
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            jQuery('#wrap-loading').hide();
            console.error(xhr.responseText);
            alert('Terjadi kesalahan saat mengirim data!');
        }
    });
}

function getTablePengisianRencanaAksi(no_loading=false) {
    if(no_loading == false){
        jQuery('#wrap-loading').show();
    }
    jQuery.ajax({
        url: esakip.url,
        type: 'POST',
        data: {
            action: 'get_table_input_rencana_aksi',
            api_key: esakip.api_key,
            id_skpd: <?php echo $id_skpd; ?>,
            tahun_anggaran: '<?php echo $input['tahun'] ?>'
        },
        dataType: 'json',
        success: function(response) {
            if(no_loading == false){
                jQuery('#wrap-loading').hide();
            }
            console.log(response);
            if (response.status === 'success') {
                jQuery('.table_dokumen_rencana_aksi tbody').html(response.data);
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

function tambah_indikator_rencana_aksi(id, tipe){
    var title = '';
    let input_pagu = '';
    if(tipe == 1){
        title = 'Indikator Kegiatan Utama';
    }else if(tipe == 2){
        title = 'Indikator Rencana Aksi';
    }else if(tipe == 3){
        title = 'Uraian Kegiatan Rencana Aksi';
    }else if(tipe == 4){
        title = 'Uraian Teknis Kegiatan';
        input_pagu = ''
            +`<div class="form-group row">`
                +'<div class="col-md-2">'
                    +`<label for="satuan_indikator">Rencana Pagu</label>`
                +'</div>'
                +'<div class="col-md-10">'
                    +`<input type="number" class="form-control" id="rencana_pagu"/>`
                +'</div>'
            +`</div>`;
            // +`<div class="form-group row">`
            //     +'<div class="col-md-2">'
            //         +`<label for="satuan_indikator">Realisasi Pagu</label>`
            //     +'</div>'
                // +'<div class="col-md-10">'
                //     +`<input type="number" class="form-control" id="realisasi_pagu"/>`
                // +'</div>'
            // +`</div>`;
    }
    var tr = jQuery('#kegiatan_utama_'+id);
    var label_pokin = tr.find('.label_pokin').text();
    var label_renaksi = tr.find('.label_renaksi').text();
    jQuery("#modal-crud").find('.modal-title').html('Tambah '+title);
    jQuery("#modal-crud").find('.modal-body').html(''
        +`<form id="form-renaksi">`
            +'<input type="hidden" value="" id="id_label_indikator">'
            +'<input type="hidden" value="'+id+'" id="id_label">'
            +`<div class="form-group row">`
                +'<div class="col-md-2">'
                    +`<label for="label_pokin_indikator">Label Pohon Kinerja</label>`
                +'</div>'
                +'<div class="col-md-10">'
                    +'<input class="form-control" type="text" disabled id="label_pokin_indikator" value="'+label_pokin+'"/>'
                +'</div>'
            +`</div>`
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
                    +'<label for="indikator">'+title+'</label>'
                +'</div>'
                +'<div class="col-md-10">'
                    +`<textarea class="form-control" name="label" id="indikator" placeholder="Tuliskan Indikator Kegiatan Utama..."></textarea>`
                +'</div>'
            +`</div>`
            +`<div class="form-group row">`
                +'<div class="col-md-2">'
                    +`<label for="satuan_indikator">Satuan</label>`
                +'</div>'
                +'<div class="col-md-10">'
                    +`<input type="text" class="form-control" id="satuan_indikator"/>`
                +'</div>'
            +`</div>`
            +`${input_pagu}`
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
}

function edit_indikator(id, tipe){
    jQuery('#wrap-loading').show();
    jQuery.ajax({
        url: esakip.url,
        type: 'POST',
        data: {
            action: 'get_indikator_rencana_aksi',
            api_key: esakip.api_key,
            id: id,
            tahun_anggaran: '<?php echo $input['tahun'] ?>'
        },
        dataType: 'json',
        success: function(response) {
            if(
                response.status == 'success' 
                && response.data != null
            ){
                
                let rencana_pagu = response.data.rencana_pagu != null ? response.data.rencana_pagu : 0;
                // let realisasi_pagu = response.data.realisasi_pagu != null ? response.data.realisasi_pagu : 0;
                tambah_indikator_rencana_aksi(response.data.id_renaksi, tipe);
                jQuery("#modal-crud").find('.modal-title').text(jQuery("#modal-crud").find('.modal-title').text().replace('Tambah', 'Edit'));
                jQuery('#id_label_indikator').val(id);
                jQuery('#indikator').val(response.data.indikator);
                jQuery('#satuan_indikator').val(response.data.satuan);
                jQuery('#target_awal').val(response.data.target_awal);
                jQuery('#target_akhir').val(response.data.target_akhir);
                jQuery('#target_tw_1').val(response.data.target_1);
                jQuery('#target_tw_2').val(response.data.target_2);
                jQuery('#target_tw_3').val(response.data.target_3);
                jQuery('#target_tw_4').val(response.data.target_4);
                jQuery('#rencana_pagu').val(response.data.rencana_pagu);
                // jQuery('#realisasi_pagu').val(response.data.realisasi_pagu);
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
    var parent_cascading = 0;
    if(tipe == 1){
        title = 'Kegiatan Utama';
    }else if(tipe == 2){
        title = 'Rencana Aksi';
        var parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
        var parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
        var parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
    }else if(tipe == 3){
        title = 'Uraian Kegiatan Rencana Aksi';
        var parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
        var parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
        var parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
    }else if(tipe == 4){
        title = 'Uraian Teknis Kegiatan';
        var parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
        var parent_pokin = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_pokin');
        var parent_cascading = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_cascading');
    }
    if(confirm('Apakah kamu yakin untuk menghapus indikator '+title+'?')){
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_indikator_rencana_aksi',
                api_key: esakip.api_key,
                id: id,
                tahun_anggaran: '<?php echo $input['tahun'] ?>'
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
                        lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading);
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

function hapus_rencana_aksi(id, tipe){
    var title = '';
    var parent_pokin = 0;
    var parent_renaksi = 0;
    var parent_cascading = 0;
    if(tipe == 1){
        title = 'Kegiatan Utama';
    }else if(tipe == 2){
        title = 'Rencana Aksi';
        parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
        parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
        parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
    }else if(tipe == 3){
        title = 'Uraian Kegiatan Rencana Aksi';
        parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
        parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
        parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
    }else if(tipe == 4){
        title = 'Uraian Teknis Kegiatan';
        parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
        parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
        parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
    }

    if(confirm('Apakah kamu yakin untuk menghapus '+title+'?')){
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_rencana_aksi',
                api_key: esakip.api_key,
                id: id,
                tipe: tipe,
                tahun_anggaran: '<?php echo $input['tahun'] ?>'
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
                        lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading)
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

function lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading){
    jQuery("#wrap-loading").show();
    return new Promise(function(resolve, reject){
        var title = '';
        var fungsi_tambah = '';
        var id_tabel = '';
        let header_pagu = '';
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
            title_cascading = 'Program Cascading';
        }else if(tipe == 3){
            id_tabel = 'tabel_uraian_rencana_aksi';
            title = 'Uraian Kegiatan Rencana Aksi';
            fungsi_tambah = 'tambah_renaksi_2';
            title_cascading = 'Kegiatan Cascading';
        }else if(tipe == 4){
            id_tabel = 'tabel_uraian_teknis_kegiatan';
            title = 'Uraian Teknis Kegiatan';
            fungsi_tambah = 'tambah_renaksi_2';
            title_cascading = 'Sub Kegiatan Cascading';
            header_pagu = ''
                +`<th class="text-center" style="width:50px;">Rencana Pagu</th>`;
                // +`<th class="text-center" style="width:50px;">Realisasi Pagu</th>`;
        }
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": "get_data_renaksi",
                "level": tipe,
                "parent": parent_renaksi,
                "api_key": esakip.api_key,
                "tipe_pokin": "opd",
                "id_skpd": <?php echo $id_skpd; ?>
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
                    +'<table class="table" id="'+id_tabel+'" parent_renaksi="'+parent_renaksi+'" parent_pokin="'+parent_pokin+'" parent_cascading="'+parent_cascading+'">'
                        +`<thead>`
                            +`<tr class="table-secondary">`
                                +`<th class="text-center" style="width:40px;">No</th>`
                                +`<th class="text-center" style="width:300px;">Label Pokin</th>`
                                +`<th class="text-center">`+title+`</th>`
                                +`<th class="text-center">`+title_cascading+`</th>`
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
                                var data_tagging_rincian = '';
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
                                    // if(value['id_pokin_5'] != ''){
                                    //     label_pokin = value['label_pokin_5'];
                                    //     id_pokin = value['id_pokin_5'];
                                    // }
                                    tombol_detail = ''
                                        +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning" onclick="lihat_rencana_aksi(${value.id}, `+(tipe+1)+`, `+id_pokin+`, '`+id_parent_cascading+`')" title="Lihat Uraian Teknis Kegiatan"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `;
                                }else if(tipe == 4){
                                    label_pokin = value['label_pokin_5'];
                                    id_pokin = value['id_pokin_5'];
                                    label_cascading = value['label_cascading_sub_kegiatan'] != null ? value['kode_cascading_sub_kegiatan']+' '+value['label_cascading_sub_kegiatan'] : '-';
                                    data_tagging_rincian = '<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning" title="Lihat Data Tagging Rincian Belanja"><i class="dashicons dashicons dashicons-arrow-down-alt2"></i></a> ';
                                }
                                renaksi += ``
                                    +`<tr id="kegiatan_utama_${value.id}">`
                                        +`<td class="text-center">${index+1}</td>`
                                        +`<td class="label_pokin">`+label_pokin+`</td>`
                                        +`<td class="label_renaksi">${value.label}</td>`
                                        +`<td class="label_renaksi">${label_cascading}</td>`
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
                                        +`<table class="table" style="margin: 0 0 2rem;">`
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
                                                    +`<th class="text-center" style="width:200px">Aksi</th>`
                                                +`</tr>`
                                            +`</thead>`
                                            +`<tbody>`;
                                    indikator.map(function(b, i){
                                        let rencana_pagu = b.rencana_pagu != null ? b.rencana_pagu : 0;
                                        let realisasi_pagu = b.realisasi_pagu != null ? b.realisasi_pagu : 0;
                                        let val_pagu = '';
                                        let html_akun = '';
                                        if(tipe == 4){
                                            val_pagu = ''    
                                                +`<td class="text-center">${rencana_pagu}</td>`;
                                                // +`<td class="text-center">${realisasi_pagu}</td>`;
                                            html_akun = `<a href="javascript:void(0)" class="btn btn-sm btn-info" onclick="tambah_rincian_belanja_rencana_aksi(${b.id},${value.id},'${value.kode_sbl}')" title="Tambah Rincian Belanja"><i class="dashicons dashicons-plus"></i></a> `;
                                        }
                                        renaksi += ``
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
                                                +`${val_pagu}`
                                                +`<td class="text-center">`
                                                    +`${html_akun}`
                                                    +data_tagging_rincian
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

function detail_parent(tipe_parent) {
    jQuery('.nav-tabs a[href="#nav-level-'+tipe_parent+'"]').tab('show');
}

function tambah_renaksi_2(tipe){
    let jenis = '';
    let parent_cascading = '';
    let jenis_cascading = '';
    switch (tipe) {
        case 3:
            jenis = "kegiatan";
            jenis_cascading = "Kegiatan";
            parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
            break;

        case 4:
            jenis = "sub_kegiatan";
            jenis_cascading = "Sub Kegiatan";
            parent_cascading = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_cascading');
            break;
    
        default:
            jenis = "program";
            jenis_cascading = "Program";
            parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
            break;
    }

    return get_tujuan_sasaran_cascading(jenis, parent_cascading)
    .then(function(){
        return new Promise(function(resolve, reject){
            var parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
            var parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
            var level_pokin = 3;
            var title = 'Rencana Aksi';
            var key = jenis+'-'+parent_cascading;
            let data_cascading = data_program_cascading[key];
            var key = jenis+'-'+parent_cascading;
            if(tipe == 3){
                level_pokin = 4;
                title = 'Uraian Rencana Aksi';
                parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
                parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
                data_cascading = data_kegiatan_cascading[key];
            }else if(tipe == 4){
                level_pokin = 5;
                title = 'Uraian Teknis Kegiatan';
                parent_pokin = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_pokin');
                parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
                data_cascading = data_sub_kegiatan_cascading[key];
            }
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": "get_data_pokin",
                    "level": level_pokin,
                    "parent": parent_pokin,
                    "api_key": esakip.api_key,
                    "tipe_pokin": "opd",
                    "id_jadwal": id_jadwal,
                    "id_skpd": <?php echo $id_skpd; ?>
                },
                dataType: "json",
                success: function(res){
                    var html = '<option value="">Pilih Pokin Level '+level_pokin+'</option>';
                    res.data.map(function(value, index){
                        html += '<option value="'+value.id+'">'+value.label+'</option>';
                    });
                    jQuery('#wrap-loading').hide();
                    jQuery("#modal-crud").find('.modal-title').html('Tambah '+title);
                    var pokin_5 = '';
                    var onchange_pokin = '';
                    jQuery("#modal-crud").find('.modal-body').html(''
                        +'<form>'
                            +'<input type="hidden" id="id_renaksi" value=""/>'
                            +'<div class="form-group">'
                                +'<label for="pokin-level-1">Pilih Pokin Level '+level_pokin+'</label>'
                                +'<select class="form-control" name="pokin-level-1" id="pokin-level-1" '+onchange_pokin+'>'
                                    +html
                                +'</select>'
                            +'</div>'
                            +pokin_5
                            +'<div class="form-group">'
                                +'<textarea class="form-control" name="label" id="label_renaksi" placeholder="Tuliskan '+title+'..."></textarea>'
                            +'</div>'
                            +'<div class="form-group">'
                                +'<label for="sasaran-cascading">Pilih '+jenis_cascading+' Cascading</label>'
                                +'<select class="form-control" name="cascading-renstra" id="cascading-renstra">'
                                +'</select>'
                            +'</div>'
                        +'</form>');
                    jQuery("#modal-crud").find('.modal-footer').html(''
                        +'<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>'
                        +'<button type="button" class="btn btn-success" onclick="simpan_data_renaksi('+tipe+')">'
                            +'Simpan'
                        +'</button>');
                    jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
                    jQuery("#modal-crud").find('.modal-dialog').css('width','');
                    jQuery("#modal-crud").modal('show');
                    jQuery('#pokin-level-1').select2({width: '100%'});
                    if(tipe == 3){
                        jQuery('#pokin-level-2').select2({width: '100%'});
                    }

                    if(data_cascading.data != undefined){
                        let html_cascading = '<option value="">Pilih '+jenis_cascading+' Cascading</option>';
                        data_cascading.data.map(function(value, index){
                            if(value.id_unik_indikator == null){
                                switch (tipe) {
                                    case 3:
                                        html_cascading += '<option value="'+value.kode_giat+'">'+value.kode_giat+' '+value.nama_giat+'</option>';
                                        break;

                                    case 4:
                                        var nama_sub_giat = value.kode_sub_giat+' '+value.nama_sub_giat.replace(value.kode_sub_giat, '');
                                        html_cascading += '<option data-kodesbl="'+value.kode_sbl+'" value="'+value.kode_sub_giat+'">'+nama_sub_giat+'</option>';
                                        break;
                                
                                    default:
                                        html_cascading += '<option value="'+value.kode_program+'">'+value.kode_program+' '+value.nama_program+'</option>';
                                        break;
                                }
                            }
                        });
                        jQuery("#cascading-renstra").html(html_cascading);
                        jQuery('#cascading-renstra').select2({width: '100%'});
                    }else{
                        alert("Data Cascading Kosong!")
                    }
                    resolve();
                }
            });
        });
    })
}

function simpan_data_renaksi(tipe){
    var parent_pokin = 0;
    var parent_renaksi = 0;
    var parent_cascading = 0;
    switch (tipe) {
        case 2 :
            parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
            parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
            parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
            break;
        case 3 :
            parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
            parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
            parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
            break;
        case 4 :
            parent_pokin = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_pokin');
            parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
            parent_cascading = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_cascading');
            break;
        default:
            parent_pokin = 0;
            parent_renaksi = 0;
            parent_cascading = 0;
        }
    
    var id_pokin_1 = jQuery('#pokin-level-1').val();
    var id_pokin_2 = jQuery('#pokin-level-2').val();
    var label_pokin_1 = jQuery('#pokin-level-1 option:selected').text();
    var label_pokin_2 = jQuery('#pokin-level-2 option:selected').text();
    var label_renaksi = jQuery('#label_renaksi').val();
    var kode_cascading_renstra = jQuery('#cascading-renstra').val();
    var label_cascading_renstra = jQuery('#cascading-renstra option:selected').text();
    var kode_sbl = '';
    if(tipe == 4){
        kode_sbl = jQuery('#cascading-renstra option:selected').data('kodesbl');;
    }
    if(label_renaksi == ''){
        return alert('Kegiatan Utama tidak boleh kosong!')
    }
    if(id_pokin_1 == ''){
        return alert('Level 2 pohon kinerja tidak boleh kosong!')
    }
    jQuery('#wrap-loading').show();
    jQuery.ajax({
        url: esakip.url,
        type: "post",
        data: {
            "action": 'create_renaksi',
            "api_key": esakip.api_key,
            "tipe_pokin": "opd",
            "id": jQuery('#id_renaksi').val(),
            "id_pokin_1": id_pokin_1,
            "id_pokin_2": id_pokin_2,
            "label_pokin_1": label_pokin_1,
            "label_pokin_2": label_pokin_2,
            "label_renaksi": label_renaksi,
            "level": tipe,
            "parent": parent_renaksi,
            "tahun_anggaran": <?php echo $input['tahun']; ?>,
            "id_jadwal": id_jadwal,
            "id_skpd": <?php echo $id_skpd; ?>,
            'kode_cascading_renstra': kode_cascading_renstra,
            'label_cascading_renstra': label_cascading_renstra,
            'kode_sbl': kode_sbl
        },
        dataType: "json",
        success: function(res){
            jQuery('#wrap-loading').hide();
            alert(res.message);
            if(res.status=='success'){
                jQuery("#modal-crud").modal('hide');
                lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin,parent_cascading);
            }
        }
    });
}

function tambah_rincian_belanja_rencana_aksi(id_indikator, id_uraian_teknis_kegiatan, kode_sbl){
    if(kode_sbl == '' || kode_sbl=='null'){
        alert("Harap perbarui data Uraian Teknis Kegiatan\nCukup \"Edit\" lalu \"Simpan\" jika tidak ada perubahan.")
        return;
    }
    get_data_rekening_akun_wp_sipd(kode_sbl)
    .then(function(){
        jQuery("#modal-crud").find('.modal-title').html('Tambah Tagging Rincian Belanja');
        jQuery("#modal-crud").find('.modal-body').html(''
            +'<form id="form-tagging-renaksi">'
                +'<div class="form-group row">'
                    +'<label class="d-block col-sm-3">Jenis Tagging</label>'
                    +'<div class="col-sm-9">'
                        +'<label for="rka_dpa"><input onclick="set_jenis_tagging(this.value)" type="radio" class="ml-2 jenis_tagging" id="rka_dpa" name="jenis_tagging" value="rka_dpa" checked> Rincian Belanja RKA/DPA</label>'
                        +'<label style="margin-left: 30px;" for="manual"><input onclick="set_jenis_tagging(this.value)" type="radio" class="jenis_tagging" id="manual" name="jenis_tagging" value="manual"> Rincian Belanja Manual</label>'
                    +'</div>'
                +'</div>'
                // +'<div class="form-group row">'
                //     +'<div class="col-md-3">'
                //         +'<label for="label_rekening_belanja">Rekening Belanja</label>'
                //     +'</div>'
                //     +'<div class="col-md-9" id="html_rekening_akun">'
                //         // +'<select class="form-control" name="rekening_akun" id="rekening_akun" onchange="get_data_rincian_belanja(this.value,\''+ kode_sbl +'\')">'
                //         //     +'<option value="">Pilih Rekening</option>'
                //         // +'</select>'
                //     +'</div>'
                //     +'<div class="col-md-10" id="form_rincian_belanja">'
                //     +'</div>'
                // +'</div>'
                // +'<div class="form-group set_manual" style="display: none;" id="form_input_rincian_manual">'
                //     +'<div class="form-group row">'
                //         +'<div class="col-md-3">'
                //             +'<label for="uraian_tagging_manual">Uraian Tagging</label>'
                //         +'</div>'
                //         +'<div class="col-md-9">'
                //             +'<input type="text" class="form-control" id="uraian_tagging_manual" name="uraian_tagging_manual" required>'
                //         +'</div>'
                //     +'</div>'
                //     +'<div class="form-group row">'
                //         +'<div class="col-md-3">'
                //             +'<label for="volume_satuan_tagging">Volume Satuan Tagging</label>'
                //         +'</div>'
                //         +'<div class="col-md-9">'
                //             +'<input type="text" class="form-control" id="volume_satuan_tagging" name="volume_satuan_tagging" required>'
                //         +'</div>'
                //     +'</div>'
                //     +'<div class="form-group row">'
                //         +'<div class="col-md-3">'
                //             +'<label for="nilai_tagging">Nilai Tagging</label>'
                //         +'</div>'
                //         +'<div class="col-md-9">'
                //             +'<input type="number" class="form-control" id="nilai_tagging" name="nilai_tagging" required>'
                //         +'</div>'
                //     +'</div>'
                // +'</div>'
                +'<div class="form-group" id="html_rekening_akun">'
                +'</div>'
                +'<div class="form-group set_rka_dpa" id="form_input_rka_dpa">'
                +'</div>'
            +'</form>');
        jQuery("#modal-crud").find('.modal-footer').html(''
            +'<button type="button" class="btn btn-danger" data-dismiss="modal">'
                +'Tutup'
            +'</button>'
            +'<button type="button" class="btn btn-success" onclick="simpan_rincian_belanja_renaksi('+id_indikator+','+id_uraian_teknis_kegiatan+',\''+kode_sbl+'\')" data-view="kegiatanUtama">'
                +'Simpan'
            +'</button>');
        jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','1400px');
        jQuery("#modal-crud").find('.modal-dialog').css('width','');
        jQuery("#modal-crud").modal('show');

        if(data_rekening_akun[kode_sbl] != undefined){
            let html_rekening_belanja = '';
            let no = 0;
            html_rekening_belanja +=``
                +`<table class="table" style="margin: 0 0 2rem;">`
                    +`<thead>`
                        +`<tr class="table-secondary">`
                            +`<th class="text-center" style="width:20px">`
                                +`<div class="form-check">`
                                    +`<input class="form-check-input" type="checkbox" name="input_rekening_belanja_all" id="rekening-belanja-all" onchange="check_all_rekening();">`
                                +`</div>`
                            +`</th>`
                            +`<th class="text-center">Kode Rekening</th>`
                            +`<th class="text-center">Nama Rekening</th>`
                            +`<th class="text-center">Nilai Rincian</th>`
                            +`<th class="text-center">Nilai Tertagging</th>`
                            +`<th class="text-center set_manual" style="display: none;">Uraian Tagging</th>`
                            +`<th class="text-center set_manual" style="display: none;">Volume Satuan Tagging</th>`
                            +`<th class="text-center set_manual" style="display: none;">Nilai Tagging</th>`
                            // +`<th class="text-center">Aksi</th>`
                        +`</tr>`
                    +`</thead>`
                    +`<tbody>`;
            for(var i in data_rekening_akun[kode_sbl].akun){
                var b = data_rekening_akun[kode_sbl].akun[i];
                number = no++;
                html_rekening_belanja +=``
                +`<tr>`
                    +`<td>`
                        +`<div class="form-check">`
                            +`<input class="form-check-input input_checkbox_rekening" type="checkbox" name="input_rekening_belanja_${number}" value="${b.kode_akun}" id="rekening-belanja-${number}" number="${number}">`
                        +`</div>`
                    +`</td>`
                    +`<td>`
                        +`<div class="form-group">`
                            +b.kode_akun
                            +`<input type="hidden" class="form-control" id="kode-rekening-${number}" name="kode_rekening_${number}" required disabled value="${b.kode_akun}">`
                        +`</div>`
                    +`</td>`
                    +`<td>`
                        +`<div class="form-group">`
                            +b.nama_akun.replace(b.kode_akun+' ', '')
                            +`<textarea class="form-control hide" rows="2" cols="50" id="nama-rekening-${number}" required disabled>`
                                +`${b.nama_akun.replace(b.kode_akun+' ', '')}`
                            +`</textarea>`
                        +`</div>`
                    +`</td>`
                    +`<td>`
                        +`<div class="form-group">`
                            +b.total
                            +`<input type="hidden" class="form-control text-right" id="nilai-rincian-${number}" required disabled value="${b.total}">`
                        +`</div>`
                    +`</td>`
                    +`<td>`
                        +`<div class="form-group">`
                            +`<input type="text" class="form-control text-right" id="nilai-tertaggin-${number}" value="0" required disabled>`
                        +`</div>`
                    +`</td>`
                    +`<td class="set_manual" style="display: none;">`
                        +`<div class="form-group">`
                            +`<textarea class="form-control" rows="2" cols="50" id="uraian-tagging-${number}" name="uraian_tagging_${number}" required></textarea>`
                        +`</div>`
                    +`</td>`
                    +`<td class="set_manual" style="display: none;">`
                        +`<div class="form-group">`
                            +`<input type="text" class="form-control" id="volume-satuan-tagging-${number}" name="volume_satuan_tagging_${number}" required>`
                        +`</div>`
                    +`</td>`
                    +`<td class="set_manual" style="display: none;">`
                        +`<div class="form-group">`
                            +`<input type="number" class="form-control" id="nilai-tagging-${number}" name="nilai_tagging_${number}" required>`
                        +`</div>`
                    +`</td>`
                    // +`<td>`
                    //     +`<div class="form-group">`
                    //         // +`<input type="text" class="form-control" id="aksi-${number}" name="nilai_rincian" value="0" required disabled>`
                    //     +`</div>`
                    // +`</td>`
                +`</tr>`;
            };
            html_rekening_belanja +=``
                    +`</tbody>`
                +`</table>`
            jQuery('#html_rekening_akun').html(html_rekening_belanja);
        }else{
            alert("Data Rekening Akun Kosong!")
        }
    });
}

function check_all_rekening(){
    const allChecked = jQuery('.input_checkbox_rekening:checked').length === jQuery('.input_checkbox_rekening').length;

    // Toggle all checkboxes
    jQuery('.input_checkbox_rekening').prop('checked', !allChecked);
}

function get_data_rekening_akun_wp_sipd(kode_sbl='0'){
    return new Promise(function(resolve, reject){
        if(typeof data_rekening_akun == 'undefined'){
            window.data_rekening_akun = {};
        }
        if(typeof data_rekening_akun[kode_sbl] == 'undefined'){
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": 'get_data_rekening_akun_wp_sipd',
                    "api_key": esakip.api_key,
                    "id_skpd": <?php echo $id_skpd; ?>,
                    "tahun_anggaran": <?php echo $input['tahun']; ?>,
                    "kode_sbl": kode_sbl
                },
                dataType: "json",
                success: function(response){
                    jQuery('#wrap-loading').hide();
                    if(response.status == 'success'){
                        data_rekening_akun[kode_sbl] = response;
                    }else{
                        alert("Error get data dari DPA, "+response.message);
                    }
                    resolve();
                }
            });
        }else{
            resolve();
        }
    });
}

function get_data_rincian_belanja(kode_akun, kode_sbl){
    let val_check = jQuery("input[name='jenis_tagging']:checked").val()
    // form_input_rka_dpa
    if(val_check == 'rka_dpa'){
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": "get_data_rincian_belanja",
                "api_key": esakip.api_key,
                "tahun_anggaran": <?php echo $input['tahun']; ?>,
                "id_skpd": <?php echo $id_skpd; ?>,
                "kode_sbl": kode_sbl,
                "kode_akun": kode_akun
            },
            dataType: "json",
            success: function(response) {
                jQuery('#wrap-loading').hide();
                // menampilkan popup
                if (response.status == 'success') {
                    let html_rincian_belanja = '';
                    let no = 0;
                    html_rincian_belanja +=``
                        +`<table class="table" style="margin: 0 0 2rem;">`
                            +`<thead>`
                                +`<tr class="table-secondary">`
                                    +`<th class="text-center" style="width:20px">`
                                        +`<div class="form-check">`
                                            +`<input class="form-check-input" type="checkbox" name="input_rincian_belanja_all" id="rincian-belanja-all">`
                                        +`</div>`
                                    +`</th>`
                                    +`<th class="text-center">Uraian Rka</th>`
                                    +`<th class="text-center">Volume Satuan</th>`
                                    +`<th class="text-center">Nilai Rka</th>`
                                    +`<th class="text-center">Uraian Tagging</th>`
                                    +`<th class="text-center">Volume Satuan Tagging</th>`
                                    +`<th class="text-center">Nilai Tagging</th>`
                                +`</tr>`
                            +`</thead>`
                            +`<tbody>`;
                    response.data.map(function(b, i){
                        number = no++;
                        html_rincian_belanja +=``
                        +`<tr>`
                            +`<td>`
                                +`<div class="form-check">`
                                    +`<input class="form-check-input" type="checkbox" name="input_rincian_belanja" value="${b.id_rinci_sub_bl}" id="rincian-belanja-${number}" number="${number}" onchange="copy_rincian(${number});">`
                                +`</div>`
                            +`</td>`
                            +`<td>`
                                +`<div class="form-group">`
                                    +`<textarea class="form-control" rows="2" cols="50" id="uraian-rka-${number}" name="uraian_rka" required disabled>`
                                        +`${b.nama_komponen} ${b.spek_komponen}`
                                    +`</textarea>`
                                +`</div>`
                            +`</td>`
                            +`<td>`
                                +`<div class="form-group">`
                                    +`<input type="text" class="form-control" id="volume-satuan-rka-${number}" name="volume_satuan_rka" value="${b.koefisien}" required disabled>`
                                +`</div>`
                            +`</td>`
                            +`<td>`
                                +`<div class="form-group">`
                                    +`<input type="text" class="form-control" id="nilai-rka-${number}" name="nilai_rka" value="${parseFloat(b.rincian)}" required disabled>`
                                +`</div>`
                            +`</td>`
                            +`<td>`
                                +`<div class="form-group">`
                                    +`<textarea rows="2" cols="50" id="uraian-tagging-${number}" name="uraian_tagging" required></textarea>`
                                +`</div>`
                            +`</td>`
                            +`<td>`
                                +`<div class="form-group">`
                                    +`<input type="text" class="form-control" id="volume-satuan-tagging-${number}" name="volume_satuan_tagging" required>`
                                +`</div>`
                            +`</td>`
                            +`<td>`
                                +`<div class="form-group">`
                                    +`<input type="text" class="form-control" id="nilai-tagging-${number}" name="nilai_tagging" required>`
                                +`</div>`
                            +`</td>`
                        +`</tr>`;
                    });
                    html_rincian_belanja +=``
                            +`</tbody>`
                        +`</table>`
                    jQuery('#form_input_rka_dpa').html(html_rincian_belanja);
                }
            }
        });
    }
}

function simpan_rincian_belanja_renaksi(id_indikator,id_uraian_teknis_kegiatan, kode_sbl){
    if(confirm('Apakah anda yakin untuk menyimpan data ini?')){
        jQuery('#wrap-loading').show();
        let form = getFormData(jQuery("#form-tagging-renaksi"));
        try {
            if(Object.keys(form).length === 0){
                alert("Inputan Kosong!")
                jQuery('#wrap-loading').hide();
                throw new Error("Inputan Kosong!");
                return;
            }
            Object.entries(form).forEach(([key, value]) => {
                Object.entries(value).forEach(([k_rincian, v_rincian]) => {
                    if(v_rincian === undefined || v_rincian === ""){
                        alert("Pastikan semua inputan terisi!")
                        jQuery('#wrap-loading').hide();
                        throw new Error("Harap Semua Inputan Yang Terchecklist Terisi!");
                        return;
                    }
                })    
            })
        
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": "crate_tagging_rincian_belanja",
                    "api_key": esakip.api_key,
                    "tahun_anggaran": <?php echo $input['tahun']; ?>,
                    "id_skpd": <?php echo $id_skpd; ?>,
                    "id_indikator_teknis_kegiatan": id_indikator,
                    "id_uraian_teknis_kegiatan": id_uraian_teknis_kegiatan,
                    "kode_sbl": kode_sbl,
                    "data": JSON.stringify(form)
                },
                dataType: "json",
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if(response.status == 'success'){
                        alert(response.message)
                        jQuery("#modal-crud").modal('hide');
                    }else{
                        alert(response.message)
                    }
                }
            });
        } catch (error) {
            console.error(error.message);
        }
    }
}

function getFormData($form){
    let unindexed_array = $form.serializeArray();
    var data = {};
    let terceklist = [];
    let jenis_tagging = '';
    unindexed_array.map(function(b, i){
        if(b.name === 'jenis_tagging'){
            jenis_tagging = b.value
        }
        let nama_baru = b.name.split('_');
        let number = nama_baru[nama_baru.length - 1];
        nama_baru.pop(); // Remove the last element
        nama_baru =  nama_baru.join('_');   
        if(nama_baru === 'input_rekening_belanja'){
            terceklist.push(number);
        }

        if(terceklist.includes(number)){
            if(!data[number]){
                data[number] = {};
            }
            data[number][nama_baru] = b.value ;
            data[number]['jenis_tagging'] = jenis_tagging ;
        }
    })
    console.log('data', data);
    return data;
}

function copy_rincian(that){
    if(jQuery("#rincian-belanja-"+that).is(':checked')) {
        uraian_rka = jQuery("#uraian-rka-"+that).val();
        jQuery("#uraian-tagging-"+that).val(uraian_rka);
        volume_satuan_rka = jQuery("#volume-satuan-rka-"+that).val();
        jQuery("#volume-satuan-tagging-"+that).val(volume_satuan_rka);
        nilai_rka = jQuery("#nilai-rka-"+that).val();
        jQuery("#nilai-tagging-"+that).val(nilai_rka);
    }else{
        jQuery("#uraian-tagging-"+that).val("");
        jQuery("#volume-satuan-tagging-"+that).val("");
        jQuery("#nilai-tagging-"+that).val("");
    }
}

function set_jenis_tagging(jenis_tagging){
    let val_check = jQuery("input[name='jenis_tagging']:checked").val()
    if(val_check == 'rka_dpa'){
        jQuery(".set_rka_dpa").show();
        jQuery(".set_manual").hide();
    }else{
        jQuery(".set_rka_dpa").hide();
        jQuery(".set_manual").show();
    }
}
</script>