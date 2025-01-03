<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
	'tahun' => '2024',
	'periode' => '1'
), $atts);

if (!empty($_GET) && !empty($_GET['id_periode'])) {
    $input['periode'] = $_GET['id_periode'];
}
$id_skpd = 0;
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

//jadwal renstra wpsipd
$api_params = array(
	'action' => 'get_data_jadwal_wpsipd',
	'api_key'	=> get_option('_crb_apikey_wpsipd'),
	'tipe_perencanaan' => 'monev_renstra',
	'id_jadwal' => $input['periode']
);

$response = wp_remote_post(get_option('_crb_url_server_sakip'), array('timeout' => 1000, 'sslverify' => false, 'body' => $api_params));

$response = wp_remote_retrieve_body($response);

$data_jadwal_wpsipd = json_decode($response, true);
if(
    empty($response)
    || empty($data_jadwal_wpsipd)
){
	die('<h1 class="text-center">Jadwal periode WP-SIPD tidak ditemukan!</h1>');
}else if(empty($id_skpd)){
	die('<h1 class="text-center">ID OPD tidak boleh kosong!</h1>');
}

if (!empty($data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran']) && $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'] > 1) {
	$tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_akhir_anggaran'];
} else {
	$tahun_anggaran_selesai = $data_jadwal_wpsipd['data'][0]['tahun_anggaran'] + $data_jadwal_wpsipd['data'][0]['lama_pelaksanaan'];
}

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

$nama_jadwal = $data_jadwal_wpsipd['data'][0]['nama'] . ' ' . '(' . $data_jadwal_wpsipd['data'][0]['tahun_anggaran'] . ' - ' . $tahun_anggaran_selesai . ')';


$get_satker = $wpdb->get_results($wpdb->prepare('
    SELECT 
        u.id_satker_simpeg,
        u.active,
        u.tahun_anggaran,
        u.id_skpd,
        s.id,
        s.satker_id,
        s.active
    FROM esakip_data_mapping_unit_sipd_simpeg AS u
    LEFT JOIN esakip_data_satker_simpeg AS s
        ON s.id = u.id_satker_simpeg
        AND s.active = u.active
    WHERE u.tahun_anggaran = %d
        AND u.id_skpd = %d
        AND u.active = 1
', $tahun_anggaran_sakip, $id_skpd), ARRAY_A);
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

    #tabel-cascading,
    #tabel-cascading td,
    #tabel-cascading table {
        padding: 0;
        border: 4px solid white;
        margin: 0;
        vertical-align: top;
    }

    #tabel-cascading>tbody>tr>td {
        padding: 2px;
    }

    #tabel-cascading div.btn.btn-lg.btn-info,
    #tabel-cascading div.btn.btn-lg.btn-warning,
    #tabel-cascading div.btn.btn-lg.btn-success,
    #tabel-cascading div.btn.btn-lg.btn-danger {
        width: 100%;
        min-height: 450px;
        font-weight: bold;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center; 
        text-align: center; 
    }

    #tabel-cascading div hr {
        background: #fff;
        width: 100%; 
        margin: 10px 0; 
    }

    #tabel-cascading div span.indikator {
        font-size: 14px;
        margin-top: 10px; 
        display: block; 
    }

    #tabel-cascading-kegiatan,
    #tabel-cascading-kegiatan td,
    #tabel-cascading-kegiatan table {
        padding: 0;
        border: 4px solid white;
        margin: 0;
        vertical-align: top;
    }

    #tabel-cascading-kegiatan>tbody>tr>td {
        padding: 2px;
    }

    #tabel-cascading-kegiatan div.btn.btn-lg.btn-info,
    #tabel-cascading-kegiatan div.btn.btn-lg.btn-warning,
    #tabel-cascading-kegiatan div.btn.btn-lg.btn-success,
    #tabel-cascading-kegiatan div.btn.btn-lg.btn-secondary,
    #tabel-cascading-kegiatan div.btn.btn-lg.btn-primary,
    #tabel-cascading-kegiatan div.btn.btn-lg.btn-danger {
        width: 100%;
        min-height: 450px;
        font-weight: bold;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center; 
        text-align: center; 
    }

    #tabel-cascading-kegiatan div hr {
        background: #fff;
        width: 100%; 
        margin: 10px 0; 
    }

    #tabel-cascading-kegiatan div span.indikator {
        font-size: 14px;
        margin-top: 10px; 
        display: block; 
    }

    .indikator {
        font-size: 14px;
    }

    @media print {
        #cetak {
            max-width: auto !important;
            height: auto !important;
        }

        #action-sakip,
        .site-header,
        .site-footer,
        #ast-scroll-top {
            display: none;
        }
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    td {
        border: 1px solid #ccc; 
        padding: 0; 
        height: 100px; 
    }
    .button-container {
        display: flex;
        align-items: stretch;
        height: 100%;
    }
    .get_button {
        flex: 1;
        padding: 10px;
    }

    .view-kegiatan-button {
        background-color: transparent;
        border: none;
        cursor: pointer;
        padding: 5px;
    }

    .view-kegiatan-button i {
        font-size: 2rem;
        color: #fff; 
    }

    .view-kegiatan-button:hover i {
        color: #f0f0f0; 
    }

    .edit-pegawai-button {
        background-color: transparent;
        border: none;
        cursor: pointer;
        padding: 5px;
    }

    .edit-pegawai-button i {
        font-size: 2rem;
        color: #fff; 
    }

    .edit-pegawai-button:hover i {
        color: #f0f0f0; 
    }
</style>

<!-- Table -->
<div class="container-md" id="container-table-cascading">
    <div class="cetak">
        <div style="padding: 10px; margin: 0 0 3rem 0;">
            <h1 class="text-center">CASCADING <br><?php echo $skpd['nama_skpd']; ?><br><?php echo $nama_jadwal; ?></h1>
            <div id="action-sakip" class="action-section text-center">
                <a style="margin-right: 10px;" id="singkron-cascading-renstra" href="#" class="btn btn-primary"><i class="dashicons dashicons-download"></i> Ambil dari Data RENSTRA</a>
                <a style="margin-right: 10px;" onclick="window.print();" href="#" class="btn btn-success"><i class="dashicons dashicons-printer"></i> CETAK / PRINT</a>
            </div>
        </div>
        <div style="overflow-x: auto; max-width: 100%;">
            <table id="tabel-cascading" style="min-width: 600px;">
                <tbody>
                    <tr>
                        <td class="text-center" style="width: 200px;"><div class="btn btn-lg btn-info">TUJUAN</div></td>
                        <td class="text-center" colspan="0"><div class="btn btn-lg btn-warning" style="text-transform:uppercase;"></div></td>
                    </tr>
                    <tr>
                        <td class="text-center"><div class="btn btn-lg btn-info">SASARAN</div></td>
                        <td class="text-center" colspan="0"><div class="btn btn-lg btn-warning" style="text-transform:uppercase;"></div></td>
                    </tr>
                    <tr>
                        <td class="text-center"><div class="btn btn-lg btn-info">PROGRAM</div></td>
                        <td class="text-center" colspan="0"><div class="btn btn-lg btn-warning"></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="overflow-x: auto; max-width: 100%;">
            <table id="tabel-cascading-kegiatan" style="min-width: 600px;">
                <h2 class="text-center get-nama-program">Cascading Kegiatan dan Sub Kegiatan<br>PROGRAM: Program belum dipilih </h2>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade mt-4" id="modalEditCascading" tabindex="-1" role="dialog" aria-labelledby="modalEditCascadingLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditCascadingLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="<?php echo $input['periode']; ?>" id="id_jadwal">
                <input type="hidden" value="" id="id">
                <div class="form-group">
                    <label for="nama_cascading">Nama Cascading</label>
                    <input type="text" class="form-control" id="nama_cascading" name="nama_cascading" required>
                </div>
                <div class="form-group">
                    <label for="tujuan_teks">Tujuan RPJMD/RPD</label>
                    <input type="text" class="form-control" id="tujuan_teks" name="tujuan_teks" disabled>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary submitBtn" onclick="submit_edit_cascading()">Simpan</button>
                <button type="submit" class="components-button btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Pegawai -->
<div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" aria-labelledby="modalUploadLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Data Cascading</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-jabatan">
                    <input type="hidden" value="" id="id_data">
                    <input type="hidden" value="" id="tipe">
                    <div class="form-group">
                        <label>Tujuan</label>
                        <input type="text" id="tujuan_cascading" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="sasaran_cascading">Sasaran</label>
                        <input type="text" id="sasaran_cascading" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="program_cascading">Program</label>
                        <input type="text" id="program_cascading" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="kegiatan_cascading">Kegiatan</label>
                        <input type="text" id="kegiatan_cascading" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="sub_giat_cascading">Sub Kegiatan</label>
                        <input type="text" id="sub_giat_cascading" class="form-control" disabled>
                    </div>
                    <div id="daftar_jabatan" class="form-group">
                        <label for="satker_id">Pilih Jabatan</label>
                        <select class="form-control select2" id="satker_id" name="satker_id"></select>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="submit_pegawai_cascading(this); return false;">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        getTableCascading();
        jQuery('#singkron-cascading-renstra').on('click', function(e){
            e.preventDefault();
            if(confirm('Apakah anda yakin untuk mengambil data CASCADING dari RENSTRA? Data lama akan diupdate dengan data baru!')){
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    url: esakip.url,
                    type: 'POST',
                    data: {
                        action: 'get_cascading_pd_from_renstra',
                        api_key: esakip.api_key,
                        id_jadwal_wpsipd: <?php echo $input['periode']; ?>,
                        id_skpd: <?php echo $id_skpd; ?>
                    },
                    dataType: 'json',
                    success: function(response) {
                        jQuery('#wrap-loading').hide();
                        console.log(response);
                        if (response.status === 'success') {
                            getTableCascading();
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
        });
        jQuery('#nama_pegawai').select2({
            
        });

        jQuery("#satker_id").select2({
            ajax: {
                url: esakip.url,
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        action: 'get_jabatan_cascading',
                        api_key: esakip.api_key,
                        id_skpd: <?php echo $id_skpd; ?>,
                        type:'search',
                        q: params.term,
                      };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                       results: data.data,
                       pagination: {
                         more: (params.page * 30) < data.total_count
                       }
                     };
                },
                cache: true
            },
            placeholder: 'Cari satuan kerja / jabatan',
            minimumInputLength: 5,
            templateResult: function (response) {
                if (response.loading) {
                    return response.text;
                }

                var $container = jQuery(
                "<div class='select2-result-repository clearfix'>" +
                  "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'></div>" +
                  "</div>" +
                "</div>"
                );

                $container.find(".select2-result-repository__title").text(response.nama);

                return $container;
            },
            templateSelection: function(response) {
                return response.nama || response.text;
            },
            'width': '100%',
            dropdownParent: jQuery('#modalUpload') 
        });

    });

    function getTableCascading() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_cascading_pd',
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#tabel-cascading tbody').html(response.data);
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

    function view_kegiatan(button, id, program, sasaran, tujuan) {
        let icon = jQuery(button).find('.visibility-icon');
        let body = jQuery('#tabel-cascading-kegiatan tbody');

        jQuery('.view-kegiatan-button').not(button).each(function() {
            let otherIcon = jQuery(this).find('.visibility-icon');
            if (jQuery(this).data('loaded')) {
                body.hide();
                otherIcon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
                jQuery(this).data('loaded', false);
            }
        });

        jQuery('#wrap-loading').show();

        if (icon.hasClass('dashicons-visibility')) {
            let value_program = jQuery(`#program-ke-${id}`).attr('data-nama-program') || 'Program belum dipilih';

            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_kegiatan_by_program',
                    api_key: esakip.api_key,
                    id: id,
                    tujuan: tujuan,
                    sasaran: sasaran,
                    program: program
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if (response.status === 'success') {
                        jQuery(".get-nama-program").html('Cascading Kegiatan dan Sub Kegiatan<br>PROGRAM: ' + value_program);
                        body.html(response.data).show();
                        jQuery(button).data('loaded', true);
                        icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
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
        } else {
            if (body.is(':visible')) {
                body.hide();
                icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            } else {
                body.show();
                icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            }

            jQuery(".get-nama-program").html(function() {
                if (!body.is(':visible')) {
                    return 'Cascading Kegiatan dan Sub Kegiatan<br>PROGRAM: Program belum dipilih';
                }
                return jQuery(".get-nama-program").html();
            });

            jQuery('#wrap-loading').hide();
        }
    }
    function get_tujuan_cascading(button, id, tujuan) {
        jQuery('#wrap-loading').show();

        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_tujuan_cascading',
                api_key: esakip.api_key,
                id: id,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#id_data').val(response.data.id);
                    jQuery('#modalUpload').modal('show');
                    jQuery('#tujuan_cascading').val(tujuan); 
                    jQuery('label[for="sasaran_cascading"]').hide();
                    jQuery('#sasaran_cascading').hide();
                    jQuery('label[for="program_cascading"]').hide();
                    jQuery('#program_cascading').hide();
                    jQuery('label[for="kegiatan_cascading"]').hide();
                    jQuery('#kegiatan_cascading').hide();
                    jQuery('label[for="sub_giat_cascading"]').hide();
                    jQuery('#sub_giat_cascading').hide();
                    if(response.jabatan && response.jabatan.id_satker){
                        jQuery('#satker_id').html('<option value="'+response.jabatan.id_satker+'">'+response.jabatan.nama_satker+'</option>').trigger('change');
                    }else{
                        jQuery('#satker_id').html('<option value=""></option>').trigger('change');
                    }
                    jQuery('#tipe').val(1);
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
    function get_sasaran_cascading(button, id, sasaran, tujuan) {
        jQuery('#wrap-loading').show();

        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_sasaran_cascading',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#id_data').val(response.data.id);
                    jQuery('#modalUpload').modal('show');
                    jQuery('#tujuan_cascading').val(tujuan); 
                    jQuery('#sasaran_cascading').val(sasaran); 
                    jQuery('label[for="sasaran_cascading"]').show();
                    jQuery('#sasaran_cascading').show();
                    jQuery('label[for="program_cascading"]').hide();
                    jQuery('#program_cascading').hide();
                    jQuery('label[for="kegiatan_cascading"]').hide();
                    jQuery('#kegiatan_cascading').hide();
                    jQuery('label[for="sub_giat_cascading"]').hide();
                    jQuery('#sub_giat_cascading').hide();
                    jQuery('#tipe').val(2);
                    if(response.jabatan && response.jabatan.id_satker){
                        jQuery('#satker_id').html('<option value="'+response.jabatan.id_satker+'">'+response.jabatan.nama_satker+'</option>').trigger('change');
                    }else{
                        jQuery('#satker_id').html('<option value=""></option>').trigger('change');
                    }
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
    function get_program_cascading(button, id, program, sasaran, tujuan) {
        jQuery('#wrap-loading').show();

        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_program_cascading',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#id_data').val(response.data.id);
                    jQuery('#modalUpload').modal('show');
                    jQuery('#tujuan_cascading').val(tujuan); 
                    jQuery('#sasaran_cascading').val(sasaran); 
                    jQuery('#program_cascading').val(program); 
                    jQuery('label[for="sasaran_cascading"]').show();
                    jQuery('#sasaran_cascading').show();
                    jQuery('label[for="program_cascading"]').show();
                    jQuery('#program_cascading').show();
                    jQuery('label[for="kegiatan_cascading"]').hide();
                    jQuery('#kegiatan_cascading').hide();
                    jQuery('label[for="sub_giat_cascading"]').hide();
                    jQuery('#sub_giat_cascading').hide();
                    jQuery('#tipe').val(3);
                    if(response.jabatan && response.jabatan.id_satker){
                        jQuery('#satker_id').html('<option value="'+response.jabatan.id_satker+'">'+response.jabatan.nama_satker+'</option>').trigger('change');
                    }else{
                        jQuery('#satker_id').html('<option value=""></option>').trigger('change');
                    }
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
    function get_kegiatan_cascading(button, id, kegiatan, program, sasaran, tujuan) {
        jQuery('#wrap-loading').show();

        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_kegiatan_cascading',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#id_data').val(response.data.id);
                    jQuery('#modalUpload').modal('show');
                    jQuery('#tujuan_cascading').val(tujuan); 
                    jQuery('#sasaran_cascading').val(sasaran); 
                    jQuery('#program_cascading').val(program); 
                    jQuery('#kegiatan_cascading').val(kegiatan); 
                    jQuery('label[for="sasaran_cascading"]').show();
                    jQuery('#sasaran_cascading').show();
                    jQuery('label[for="program_cascading"]').show();
                    jQuery('#program_cascading').show();
                    jQuery('label[for="kegiatan_cascading"]').show();
                    jQuery('#kegiatan_cascading').show();
                    jQuery('label[for="sub_giat_cascading"]').hide();
                    jQuery('#sub_giat_cascading').hide();
                    jQuery('#tipe').val(4);
                    if(response.jabatan && response.jabatan.id_satker){
                        jQuery('#satker_id').html('<option value="'+response.jabatan.id_satker+'">'+response.jabatan.nama_satker+'</option>').trigger('change');
                    }else{
                        jQuery('#satker_id').html('<option value=""></option>').trigger('change');
                    }
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
    function get_sub_giat_cascading(button, id, sub_giat, kegiatan, program, sasaran, tujuan) {
        jQuery('#wrap-loading').show();

        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_sub_giat_cascading',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#id_data').val(response.data.id);
                    jQuery('#modalUpload').modal('show');
                    jQuery('#tujuan_cascading').val(tujuan); 
                    jQuery('#sasaran_cascading').val(sasaran); 
                    jQuery('#program_cascading').val(program); 
                    jQuery('#kegiatan_cascading').val(kegiatan); 
                    jQuery('#sub_giat_cascading').val(sub_giat); 
                    jQuery('label[for="sasaran_cascading"]').show();
                    jQuery('#sasaran_cascading').show();
                    jQuery('label[for="program_cascading"]').show();
                    jQuery('#program_cascading').show();
                    jQuery('label[for="kegiatan_cascading"]').show();
                    jQuery('#kegiatan_cascading').show();
                    jQuery('label[for="sub_giat_cascading"]').show();
                    jQuery('#sub_giat_cascading').show();
                    jQuery('#tipe').val(5);
                    if(response.jabatan && response.jabatan.id_satker){
                        jQuery('#satker_id').html('<option value="'+response.jabatan.id_satker+'">'+response.jabatan.nama_satker+'</option>').trigger('change');
                    }else{
                        jQuery('#satker_id').html('<option value=""></option>').trigger('change');
                    }
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

    function submit_pegawai_cascading(button) {
        jQuery('#wrap-loading').show();
        let id_data = jQuery('#id_data').val();
        let tipe = jQuery('#tipe').val();
        if (tipe == '') {
            return alert('Tipe tidak boleh kosong');
        }
        let satker_id = jQuery('#satker_id').val();
        if (satker_id == '') {
            return alert('ID Satker tidak boleh kosong');
        }
        let nama_satker = jQuery('#satker_id').select2('data')[0].nama;
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                action: 'submit_pegawai_cascading',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $tahun_anggaran_sakip; ?>,
                id_skpd: <?php echo $id_skpd; ?>,
                id_data: id_data,
                tipe: tipe,
                satker_id: satker_id,
                nama_satker: nama_satker
            },
            dataType: "json",
            success: function(res) {
                jQuery('#wrap-loading').hide();
                alert(res.message);
                if (res.status === 'success') {
                    jQuery('#modalUpload').modal('hide');
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat menyimpan data!');
            }
        });
    }

</script>