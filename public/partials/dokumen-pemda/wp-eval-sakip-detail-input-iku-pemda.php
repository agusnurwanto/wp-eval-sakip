<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'id_periode' => '0'
), $atts);

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

//jadwal rpjmd/rpd sakip
$data_jadwal = $wpdb->get_row(
    $wpdb->prepare("
    SELECT
        *
    FROM
        esakip_data_jadwal
    WHERE
        id=%d
        AND tipe='RPJMD'
        AND status!=0
    ", $input['id_periode']),
    ARRAY_A);

if(empty($data_jadwal)){
    die("JADWAL KOSONG");
}

if(!empty($data_jadwal)){
    $lama_pelaksanaan = $data_jadwal['lama_pelaksanaan'] ?? 4;
    $tahun_anggaran = $data_jadwal['tahun_anggaran'];
    $tahun_awal = $data_jadwal['tahun_anggaran'];
    $tahun_akhir = $tahun_awal + $data_jadwal['lama_pelaksanaan'] - 1;
}

$nama_jadwal = strtoupper($data_jadwal['jenis_jadwal_khusus']) . ' ' . $data_jadwal['nama_jadwal'] . ' ' . '(' . $tahun_awal . ' - ' . $tahun_akhir . ')';

if(empty($input['id_periode'])){
    $id_jadwal = 0;
}else{
    $id_jadwal = $input['id_periode'];
}
$cek_id_jadwal = empty($input['id_periode']) ? 0 : 1;


// $current_user = wp_get_current_user();
// $user_roles = $current_user->roles;
// $is_admin_panrb = in_array('admin_panrb', $user_roles);
// $is_administrator = in_array('administrator', $user_roles);

//     $admin_role_pemda = array(
//         'admin_bappeda',
//         'admin_ortala'
//     );

//     $this_jenis_role = (in_array($user_roles[0], $admin_role_pemda)) ? 1 : 2 ;

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
</style>

<!-- Table -->
<div class="container-md">
    <div id="cetak" title="Pengisian IKU Pemerintah Daerah <?php echo $nama_jadwal ?>">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 style="text-align: center; margin: 10px auto; min-width: 450px;">Pengisian IKU <br>Pemerintah Daerah<br><?php echo $nama_jadwal ?></h1>
            <div id="action" class="action-section hide-excel"></div>
            <br>
            <div class="wrap-table">
                <table cellpadding="2" cellspacing="0" class="table_dokumen_iku table table-bordered">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="text-center atas kanan bawah kiri">No</th>
                            <th class="text-center atas kanan bawah kiri">Sasaran Strategis</th>
                            <th class="text-center atas kanan bawah kiri">Indikator Sasaran</th>
                            <th class="text-center atas kanan bawah kiri">Definisi Operasional/Formulasi</th>
                            <th class="text-center atas kanan bawah kiri">Sumber Data</th>
                            <th class="text-center atas kanan bawah kiri">Penanggung Jawab</th>
                            <th class="text-center  atas kanan bawah kiri hide-excel" style="width: 150px;">Aksi</th>
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
                        <label for="tujuan-sasaran">Sasaran Strategis</label>
                        <select name="" id="tujuan-sasaran"></select>
                    </div>
                    <div class="form-group">
                        <label for="indikator">Indikator Sasaran</label>
                        <select name="" id="indikator"></select>
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
                <button type="button" class="btn btn-success" onclick="simpanDataIkuPemda()">Simpan</button>
            </div>
    	</div>
  	</div>
</div>


<script type="text/javascript">
jQuery(document).ready(function() {
    let id_jadwal = <?php echo $cek_id_jadwal; ?>;
    if(id_jadwal == 0){
        alert("Jadwal RENSTRA untuk data Sasaran belum disetting.\nPastikan Jadwal RENSTRA tersedia.")
    }

    window.id_jadwal = <?php echo $id_jadwal; ?>;

    getTableIKUPemda().then(function(){
        run_download_excel_sakip();
        run_download_word_sakip(jQuery('#cetak').html(), jQuery('#cetak').attr('title'));
        jQuery('#action-sakip').prepend('<a style="margin-right: 10px;" id="tambah-iku-pemda" onclick="return false;" href="#" class="btn btn-primary hide-print"><i class="dashicons dashicons-plus"></i> Tambah Data</a>');

        jQuery("#tambah-iku-pemda").on('click', function(){
            tambahIkuPemda();
        });
    });

});

function getTableIKUPemda() {
    jQuery('#wrap-loading').show();
    return new Promise(function(resolve, reject){
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_input_iku',
                api_key: esakip.api_key,
                id_jadwal: id_jadwal,
                tipe: 'pemda'
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

function tambahIkuPemda(){
    jQuery('#wrap-loading').show();
    return getSasaran()
    .then(function(){
        return new Promise(function(resolve, reject){
            jQuery('#wrap-loading').hide();
            document.input_iku.reset();
            jQuery('#id_iku').val("");
            if(typeof data_sasaran_rpjmd != 'undefined'){
                jQuery('#wrap-loading').hide();
                jQuery("#modal-iku").modal('show');
                jQuery("#modal-iku").find('.modal-title').html('Tambah IKU');
                jQuery("#modal-iku").find('.modal-footer').html(''
                    +'<button type="button" class="btn btn-danger" data-dismiss="modal">'
                        +'Tutup'
                    +'</button>'
                    +'<button type="button" class="btn btn-success" onclick="simpanDataIkuPemda()">'
                        +'Simpan'
                    +'</button>');
    
                if(data_sasaran_rpjmd != undefined){
                    let html_sasaran = '<option value="">Pilih Sasaran Strategis</option>';
                    if(data_sasaran_rpjmd.data !== null){
                        data_sasaran_rpjmd.data.map(function(value, index){
                            if(value.id_unik_indikator == null){
                                html_sasaran += '<option value="'+value.id_unik+'">'+value.sasaran_teks+'</option>';
                            }
                        });
                    }
                    jQuery("#tujuan-sasaran").html(html_sasaran);
                    jQuery('#tujuan-sasaran').select2({width: '100%'});
                    jQuery('#tujuan-sasaran').attr("onchange","getIndikator(this.value)")
                }
             
                resolve();
            }
        })
    })
}

function simpanDataIkuPemda(){
    let id_iku = jQuery('#id_iku').val();
    let id_sasaran = jQuery('#tujuan-sasaran').val();
    let label_tujuan_sasaran = jQuery('#tujuan-sasaran option:selected').text();
    let id_indikator = jQuery('#indikator').val();
    let label_indikator = jQuery('#indikator option:selected').text();
    let formulasi = tinymce.get('formulasi') ? tinymce.get('formulasi').getContent() : jQuery('#formulasi').val();
    let sumber_data = jQuery('#sumber-data').val();
    let penanggung_jawab = jQuery('#penanggung-jawab').val();
    if(id_sasaran == '' || id_indikator == '' || formulasi == '' || sumber_data == '' || penanggung_jawab == ''){
        return alert('Ada Input Data Yang Kosong!')
    }
    jQuery('#wrap-loading').show();
    jQuery.ajax({
        url: esakip.url,
        type: "post",
        data: {
            "action": 'tambah_iku_pemda',
            "api_key": esakip.api_key,
            "tipe_iku": "opd",
            "id_sasaran": id_sasaran,
            "label_tujuan_sasaran": label_tujuan_sasaran,
            "id_indikator": id_indikator,
            "label_indikator": label_indikator,
            "formulasi": formulasi,
            "sumber_data": sumber_data,
            "penanggung_jawab": penanggung_jawab,
            "id_jadwal": id_jadwal,
            "id_iku" : id_iku
        },
        dataType: "json",
        success: function(res){
            jQuery('#wrap-loading').hide();
            alert(res.message);
            if(res.status=='success'){
                jQuery("#modal-iku").modal('hide');
                getTableIKUPemda();
            }
        }
    });
}

function edit_iku(id) {
    tambahIkuPemda().then(function(){
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_iku_by_id',
                api_key: esakip.api_key,
                id: id,
                tipe: 'pemda'
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    let data = response.data;
                    jQuery('#id_iku').val(id);
                    jQuery("#tujuan-sasaran").val(data.id_sasaran).trigger('change');                    
                    tinymce.get('formulasi').setContent(data.formulasi);
                    jQuery("#sumber-data").val(data.sumber_data);
                    jQuery("#penanggung-jawab").val(data.penanggung_jawab);
                    jQuery("#modal-iku").find('.modal-title').html('Edit IKU');
                    jQuery('#modal-iku').modal('show');
                    getIndikator(data.id_sasaran).then(function(){
                        jQuery("#indikator").val(data.id_unik_indikator).trigger('change');
                    })
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
            id: id,
            tipe: "pemda"
        },
        dataType: 'json',
        success: function(response) {
            console.log(response);
            jQuery('#wrap-loading').hide();
            if (response.status === 'success') {
                alert(response.message);
                getTableIKUPemda();
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

function getIndikator(that){
    jQuery('#wrap-loading').show();
    return getSasaran()
    .then(function(){
        return new Promise(function(resolve, reject){
            jQuery('#wrap-loading').hide();
            if(typeof data_sasaran_rpjmd != 'undefined'){
                if(data_sasaran_rpjmd != undefined){
                    let html_indikator = '';
                    if(data_sasaran_rpjmd.data !== null){
                        html_indikator = '<option value="">Pilih Indikator Sasaran</option>';
                        data_sasaran_rpjmd.data.map(function(value, index){
                            if(value.id_unik_indikator != null && value.id_unik == that){
                                html_indikator += '<option value="'+value.id_unik_indikator+'">'+value.indikator_teks+'</option>';
                            }
                        });
                    }
                    jQuery("#indikator").html(html_indikator);
                    jQuery('#indikator').select2({width: '100%'});

                    resolve();
                }
            }
        })
    })
}

function getSasaran() {
    return new Promise(function(resolve, reject){
        if(typeof data_sasaran_rpjmd == 'undefined'){
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": 'get_sasaran_rpjmd',
                    "api_key": esakip.api_key,
                    "id_jadwal": id_jadwal
                },
                dataType: "json",
                success: function(response){
                    if(response.status){
                        window.data_sasaran_rpjmd = response;
                    }else{
                        alert("Data sasaran tidak ditemukan")
                    }
                    resolve();
                }
            });
        }else{
            resolve();
        }
    });
}

</script>