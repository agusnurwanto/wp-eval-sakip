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

$id_jadwal = $wpdb->get_var("
    SELECT 
        id
    FROM esakip_data_jadwal
    WHERE tipe = 'RPJMD'
    ORDER by status DESC, id DESC
");

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

    #modal-renaksi .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    #table_dokumen_rencana_aksi thead {
        position: sticky;
        top: -6px;
        background: #ffc491;
    }
</style>

<!-- Table -->
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Rencana Aksi <br><?php echo $skpd['nama_skpd'] ?><br> Tahun Anggaran <?php echo $input['tahun']; ?></h1>
            <?php if (!$is_admin_panrb && $hak_akses_user): ?>
            <div id="action" style="text-align: center; margin-top:30px; margin-bottom: 30px;">
                    <a style="margin-left: 10px;" id="tambah-rencana-aksi" onclick="return false;" href="#" class="btn btn-success">Tambah Data</a>
            </div>
            <?php endif; ?>
            <div class="wrap-table">
                <table id="table_dokumen_rencana_aksi" cellpadding="2" cellspacing="0" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="2" style="width: 40px;">No</th>
                            <th class="text-center" rowspan="2">KEGIATAN UTAMA</th>
                            <th class="text-center" rowspan="2">INDIKATOR KEGIATAN UTAMA</th>
                            <th class="text-center" rowspan="2">RENCANA AKSI</th>
                            <th class="text-center" rowspan="2">OUTCOME/OUTPUT</th>
                            <th class="text-center" rowspan="2">URAIAN KEGIATAN RENCANA AKSI</th>
                            <th class="text-center" rowspan="2">SATUAN</th>
                            <th class="text-center" colspan="6">TARGET KEGIATAN PER TRIWULAN</th>
                            <th class="text-center" rowspan="2">JUMLAH ANGGARAN</th>
                        </tr>
                        <tr>
                            <th>AWAL</th>
                            <th>TW-I</th>
                            <th>TW-II</th>
                            <th>TW-III</th>
                            <th>TW-IV</th>
                            <th>AKHIR</th>
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
    <div class="modal-dialog" style="max-width: 1200px;" role="document">
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


<script>
jQuery(document).ready(function() {
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

    jQuery(document).on('click', '#tambah-kegiatan-utama', function(){
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
                "id_jadwal": <?php echo $id_jadwal; ?>,
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
                        +`<div class="form-group">`
    						+`<label for="pokin-level-1">Pilih Pokin Level 1</label>`
    						+`<select class="form-control" name="pokin-level-1" id="pokin-level-1" onchange="get_data_pokin(this.value, 2, 'pokin-level-2')">`
                                +html
    						+`</select>`
    					+`</div>`
                        +`<div class="form-group">`
                            +`<label for="pokin-level-2">Pilih Pokin Level 2</label>`
                            +`<select class="form-control" name="pokin-level-2" id="pokin-level-2">`
                            +`</select>`
                        +`</div>`
                        +`<div class="form-group">`
                                +`<textarea class="form-control" name="label" id="kegiatan-utama" placeholder="Tuliskan Kegiatan Utama..."></textarea>`
                        +`</div>`
                    +`</form>`);
                jQuery("#modal-crud").find('.modal-footer').html(''
                    +'<button type="button" class="btn btn-danger" data-dismiss="modal">'
                        +'Tutup'
                    +'</button>'
                    +'<button type="button" class="btn btn-success" id="simpan-data-renaksi" data-action="create_renaksi" data-view="kegiatanUtama">'
                        +'Simpan'
                    +'</button>');
                jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
                jQuery("#modal-crud").find('.modal-dialog').css('width','');
                jQuery("#modal-crud").modal('show');
                jQuery('#pokin-level-1').select2({width: '100%'});
                jQuery('#pokin-level-2').select2({width: '100%'});
            }
        });
    });

    jQuery(document).on('click', '#simpan-data-renaksi', function(){
        var id_pokin_1 = jQuery('#pokin-level-1').val();
        var id_pokin_2 = jQuery('#pokin-level-2').val();
        var label_pokin_1 = jQuery('#pokin-level-1 option:selected').text();
        var label_pokin_2 = jQuery('#pokin-level-2 option:selected').text();;
        var kegiatan_utama = jQuery('#kegiatan-utama').val();
        if(kegiatan_utama == ''){
            return alert('Kegiatan Utama tidak boleh kosong!')
        }
        if(id_pokin_2 == ''){
            return alert('Level 2 pohon kinerja tidak boleh kosong!')
        }
        jQuery('#wrap-loading').show();
        var action = jQuery('#simpan-data-renaksi').attr('data-action');
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": action,
                "api_key": esakip.api_key,
                "tipe_pokin": "opd",
                "id_pokin_1": id_pokin_1,
                "id_pokin_2": id_pokin_2,
                "label_pokin_1": label_pokin_1,
                "label_pokin_2": label_pokin_2,
                "kegiatan_utama": kegiatan_utama,
                "tahun_anggaran": <?php echo $input['tahun']; ?>,
                "id_jadwal": <?php echo $id_jadwal; ?>,
                "id_skpd": <?php echo $id_skpd; ?>
            },
            dataType: "json",
            success: function(res){
                jQuery('#wrap-loading').hide();
                alert(res.message);
                if(res.status=='success'){
                    jQuery("#modal-crud").modal('hide');
                    kegiatanUtama();
                }
            }
        });
    });

    jQuery(document).on('click', '#simpan-indikator-renaksi', function(){
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
        var action = jQuery('#simpan-indikator-renaksi').attr('data-action');
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": action,
                "api_key": esakip.api_key,
                "tipe_pokin": "opd",
                "id_label_indikator": id,
                "id_label": id_label,
                "indikator": indikator,
                "satuan": satuan,
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
                    kegiatanUtama();
                    getTablePengisianRencanaAksi(1);
                }
            }
        });
    });
});

function get_data_pokin(parent, level, tag){
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
                "id_jadwal": <?php echo $id_jadwal; ?>,
                "id_skpd": <?php echo $id_skpd; ?>
            },
            dataType: "json",
            success: function(res){
                var html = '<option value="">Pilih Pokin Level '+level+'</option>';
                res.data.map(function(value, index){
                    html += '<option value="'+value.id+'">'+value.label+'</option>';
                });
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
                        +`<button type="button" class="btn btn-success mb-2" id="tambah-kegiatan-utama"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
                    +`</div>`
                    +`<table class="table" id="kegiatanUtama">`
                        +`<thead>`
                            +`<tr>`
                                +`<th class="text-center" style="width:40px;">No</th>`
                                +`<th class="text-center" style="width:300px;">Label Pokin</th>`
                                +`<th class="text-center">Kegiatan Utama</th>`
                                +`<th class="text-center" style="width:200px;">Aksi</th>`
                            +`</tr>`
                        +`</thead>`
                        +`<tbody>`;
                            res.data.map(function(value, index){
                                kegiatanUtama += ``
                                    +`<tr id="kegiatan_utama_${value.id}">`
                                        +`<td class="text-center">${index+1}</td>`
                                        +`<td class="label_pokin">${value.label_pokin_2}</td>`
                                        +`<td class="label_renaksi">${value.label}</td>`
                                        +`<td class="text-center">`
                                            +`<a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="tambah_indiaktor_rencana_aksi(${value.id}, 1)" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
                                            +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-kegiatan-utama-level2" title="Lihat Rencana Aksi"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
                                            +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-primary edit-kegiatan-utama" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
                                            +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger hapus-kegiatan-utama" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
                                        +`</td>`
                                    +`</tr>`;

                                let indikator = value.indikator;
                                if(indikator.length > 0){
                                    kegiatanUtama += ``
                                    +'<td colspan="4">'
                                        +`<table class="table" id="kegiatanUtama">`
                                            +`<thead>`
                                                +`<tr>`
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
                jQuery('#table_dokumen_rencana_aksi tbody').html(response.data);
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

function tambah_indiaktor_rencana_aksi(id, tipe){
    var title = '';
    if(tipe == 1){
        title = 'Indikator Kegiatan Utama';
    }else if(tipe == 2){
        title = 'Indikator Rencana Aksi';
    }else if(tipe == 3){
        title = 'Uraian Kegiatan Rencana Aksi';
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
        +'<button type="button" class="btn btn-success" id="simpan-indikator-renaksi" data-action="create_indikator_renaksi" data-view="kegiatanUtama">'
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
                tambah_indiaktor_rencana_aksi(response.data.id_renaksi, tipe);
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
            }else if(response.status == 'error'){
                alert(response.message);
            }
            jQuery('#wrap-loading').hide();
        }
    });
}

function hapus_indikator(id, tipe){
    var title = '';
    if(tipe == 1){
        title = 'Kegiatan Utama';
    }else if(tipe == 2){
        title = 'Rencana Aksi';
    }else if(tipe == 3){
        title = 'Uraian Kegiatan Rencana Aksi';
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
</script>