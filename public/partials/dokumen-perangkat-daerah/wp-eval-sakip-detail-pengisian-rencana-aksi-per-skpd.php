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
                <table id="table_dokumen_rencana_aksi" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="2">KEGIATAN UTAMA</th>
                            <th class="text-center" rowspan="2">INDIKATOR KEGIATAN UTAMA</th>
                            <th class="text-center" rowspan="2">TARGET</th>
                            <th class="text-center" rowspan="2">REALISASI</th>
                            <th class="text-center" rowspan="2">RENCANA AKSI</th>
                            <th class="text-center" colspan="2">OUTCOME/OUTPUT</th>
                            <th class="text-center" rowspan="2">TARGET</th>
                            <th class="text-center" rowspan="2">REALISASI</th>
                            <th class="text-center" colspan="2">URAIAN KEGIATAN RENCANA AKSI</th>
                            <th class="text-center" colspan="5">TARGET KEGIATAN PER TRIWULAN</th>
                            <th class="text-center" rowspan="2">JUMLAH ANGGARAN</th>
                        </tr>
                        <tr>
                            <th>SATUAN</th>
                            <th>INDIKATOR</th>
                            <th>URAIAN KEGIATAN</th>
                            <th>SATUAN</th>
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
  	<div class="modal-dialog modal-dialog-scrollable" role="document">
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
        // getTablePengisianRencanaAksi();
        jQuery("#fileUpload").on('change', function() {
            var id_dokumen = jQuery('#idDokumen').val();
            if (id_dokumen == '') {
                var name = jQuery("#fileUpload").prop('files')[0].name;
                jQuery('#nama_file').val(name);
            }
        });

        jQuery("#tambah-rencana-aksi").on('click', function(){
            kegiatanUtama().then(function(){
                jQuery("#kegiatanUtama").DataTable();
            });
        });
        
        jQuery(document).on('click', '#ll', function(){
            jQuery("#modal-crud").find('.modal-title').html('Tambah Rencana Aksi');
            jQuery("#modal-crud").find('.modal-body').html(''
                +'<form id="form-pokin">'
                +'</form>');
            jQuery("#modal-crud").find('.modal-footer').html(''
                +'<button type="button" class="btn btn-danger" data-dismiss="modal">'
                    +'Tutup'
                +'</button>'
                +'<button type="button" class="btn btn-success" id="simpan-rencana-aksi" data-action="tambah_rencan_aksi">'
                    +'Simpan'
                +'</button>');
            jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
            jQuery("#modal-crud").find('.modal-dialog').css('width','');
            jQuery("#modal-crud").modal('show');
        })

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
                            +`<input type="hidden" name="parent" value="0">`
                            +`<input type="hidden" name="level" value="1">`
                            +`<div class="form-group">`
                                    +`<textarea class="form-control" name="label" placeholder="Tuliskan Kegiatan Utama..."></textarea>`
                            +`</div>`
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
                                    +`<th class="text-center" style="width:20%">No</th>`
                                    +`<th class="text-center" style="width:60%">Label Kegiatan Utama</th>`
                                    +`<th class="text-center" style="width:60%">Target</th>`
                                    +`<th class="text-center" style="width:60%">Realisasi</th>`
                                    +`<th class="text-center" style="width:20%">Aksi</th>`
                                +`</tr>`
                            +`</thead>`
                            +`<tbody>`;
                                // res.data.map(function(value, index){
                                //     level1 += ``
                                //         +`<tr id="pokinLevel1_${value.id}">`
                                //             +`<td class="text-center">${index+1}.</td>`
                                //             +`<td class="label-level1">${value.label}</td>`
                                //             +`<td class="text-center">`
                                //                 +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-success tambah-indikator-pokin-level1" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
                                //                 +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level2" title="Lihat pohon kinerja level 2"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
                                //                 +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-primary edit-pokin-level1" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
                                //                 +`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger hapus-pokin-level1" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
                                //             +`</td>`
                                //         +`</tr>`;

                                //     let indikator = Object.values(value.indikator);
                                //     if(indikator.length > 0){
                                //         indikator.map(function(indikator_value, indikator_index){
                                //             level1 += ``
                                //             +`<tr>`
                                //                 +`<td><span style="display:none">${index+1}</span></td>`
                                //                 +`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
                                //                 +`<td class="text-center">`
                                //                     +`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level1" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
                                //                     +`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-danger hapus-indikator-pokin-level1" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
                                //                 +`</td>`
                                //             +`</tr>`;
                                //         });
                                //     }
                                // });
                                kegiatanUtama+=`<tbody>`
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

    function getTablePengisianRencanaAksi() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_rencana_aksi',
                api_key: esakip.api_key,
                id_skpd: <?php echo $id_skpd; ?>,
                tahun_anggaran: '<?php echo $input['tahun'] ?>'
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
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

    function tambah_dokumen_rencana_aksi() {
        jQuery("#editModalLabel").hide();
        jQuery("#uploadModalLabel").show();
        jQuery("#idDokumen").val('');
        jQuery("#fileUpload").val('');
        jQuery("#keterangan").val('');
        jQuery("#nama_file").val('');
        jQuery('#fileUploadExisting').removeAttr('href').empty();
        jQuery("#uploadModal").modal('show');
    }

    function edit_dokumen_rencana_aksi(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_detail_rencana_aksi_by_id',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    let data = response.data;
                    let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/'; ?>' + data.dokumen;
                    jQuery("#idDokumen").val(data.id);
                    jQuery("#fileUpload").val('');
                    jQuery("#nama_file").val(data.dokumen);
                    jQuery('#fileUploadExisting').attr('href', url).html(data.dokumen);
                    jQuery("#keterangan").val(data.keterangan);
                    jQuery("#uploadModalLabel").hide();
                    jQuery("#editModalLabel").show();
                    jQuery('#uploadModal').modal('show');
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

    function submit_dokumen(that) {
        let id_dokumen = jQuery("#idDokumen").val();

        let skpd = jQuery("#perangkatDaerah").val();
        if (skpd == '') {
            return alert('Perangkat Daerah tidak boleh kosong');
        }
        let idSkpd = jQuery("#idSkpd").val();
        if (idSkpd == '') {
            return alert('Id Skpd tidak boleh kosong');
        }
        let keterangan = jQuery("#keterangan").val();
        if (keterangan == '') {
            return alert('Keterangan tidak boleh kosong');
        }
        let tahunAnggaran = jQuery("#tahunAnggaran").val();
        if (tahunAnggaran == '') {
            return alert('Tahun Anggaran tidak boleh kosong');
        }
        let fileDokumen = jQuery("#fileUpload").prop('files')[0];
        if (fileDokumen == '') {
            return alert('File Upload tidak boleh kosong');
        }
        let namaDokumen = jQuery("#nama_file").val();
        if (namaDokumen == '') {
            return alert('Nama Dokumen tidak boleh kosong');
        }

        let form_data = new FormData();
        form_data.append('action', 'tambah_dokumen_rencana_aksi');
        form_data.append('api_key', esakip.api_key);
        form_data.append('id_dokumen', id_dokumen);
        form_data.append('skpd', skpd);
        form_data.append('idSkpd', idSkpd);
        form_data.append('keterangan', keterangan);
        form_data.append('tahunAnggaran', tahunAnggaran);
        form_data.append('fileUpload', fileDokumen);
        form_data.append('namaDokumen', namaDokumen);

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#uploadModal').modal('hide');
                    alert(response.message);
                    getTableRencanaAksi();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengirim data!');
                jQuery('#wrap-loading').hide();
            }
        });
    }

    function lihatDokumen(dokumen) {
        let url = '<?php echo ESAKIP_PLUGIN_URL . 'public/media/dokumen/'; ?>' + dokumen;
        window.open(url, '_blank');
    }

    function set_tahun_dokumen(id) {
        jQuery('#tahunModal').modal('show');
        jQuery('#idDokumen').val(id);
    }

    function hapus_dokumen_rencana_aksi(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_dokumen_rencana_aksi',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableRencanaAksi();
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

    function hapus_tahun_dokumen_rencana_aksi(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_tahun_dokumen_rencana_aksi',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    getTableRencanaAksi();
                    getTableTahun();
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
</script>