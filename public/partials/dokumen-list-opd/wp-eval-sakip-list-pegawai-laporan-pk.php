<?php
if (!defined('WPINC')) {
    die;
}
global $wpdb;
    
$input = shortcode_atts(array(
    'tahun_anggaran' => '2000'
), $atts);

$id_skpd = '';
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
} else {
    die('Parameter tidak lengkap!');
}

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
$nama_skpd = $this->get_data_skpd_by_id($id_skpd, $tahun_anggaran_sakip);

$id_satker = $wpdb->get_var(
    $wpdb->prepare("
        SELECT 
            id_satker_simpeg
        FROM esakip_data_mapping_unit_sipd_simpeg
        WHERE id_skpd = %d
          AND tahun_anggaran = %d
          AND active = 1
    ", $id_skpd, $tahun_anggaran_sakip)
);

$halaman_pegawai_skpd = $this->functions->generatePage(array(
    'nama_page' => 'List Perjanjian Kinerja ' . $input['tahun_anggaran'],
    'content' => '[list_perjanjian_kinerja tahun_anggaran=' . $input['tahun_anggaran'] . ']',
    'show_header' => 1,
    'post_status' => 'private'
));
?>
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center">Daftar Pegawai</br><?php echo $nama_skpd['nama_skpd'] ?></br>Tahun Anggaran <?php echo $input['tahun_anggaran']; ?></h1>
            <h3 class="text-center">Aktif = <span id="p_aktif">0</span> Pegawai || Tidak Aktif = <span id="p_non_aktif">0</span> Pegawai</h3>
            <div id="action" class="action-section hide-excel"></div>
            <table id="cetak" title="List Pegawai Laporan Perjanjian Kinerja Perangkat Daerah" class="table table-bordered table_list_pegawai" cellpadding="2" cellspacing="0">
                <thead style="background: #ffc491;">
                    <tr>
                        <th class="text-center" style="width: 30px;" title="Input Rencana Hasil Kerja (RHK) / Rencana Aksi">Aktif<br><input type="checkbox" id="cek_all"></th>
                        <th class="text-center">Satker ID</th>
                        <th class="text-center">Satuan Kerja</th>
                        <th class="text-center">Tipe Pegawai</th>
                        <th class="text-center">NIP</th>
                        <th class="text-center">Nama Pegawai</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Atasan</th>
                        <th class="text-center" style="width: 70px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function() {
        run_download_excel_sakip();
        var tombol_aksi = `
            <a id="singkron-pegawai" onclick="ajax_get_pegawai(); return false;" href="#" class="btn btn-danger"><span class="dashicons dashicons-update-alt"></span>Singkron Pegawai Dengan Data SIMPEG</a>
            <a target="_blank" href="<?php echo $halaman_pegawai_skpd['url'] . "&id_skpd=" . $id_skpd; ?>" class="btn btn-warning"><span class="dashicons dashicons-groups"></span>Daftar Perjanjian Kinerja Pegawai</a>
            <br><a onclick="simpan_pegawai(); return false;" href="#" class="btn btn-primary" style="margin-top: 5px;"><span class="dashicons dashicons-saved"></span>Simpan Pegawai Aktif</a>
        `;
        jQuery('#action-sakip').append(tombol_aksi);
        getTablePegawai();

        jQuery('#cek_all').on('change', function(){
            if(jQuery(this).is(':checked')){
                jQuery('.input_rhk').prop('checked', true);
            }else{
                jQuery('.input_rhk').prop('checked', false);
            }
        });
    });

    function ajax_get_pegawai(){
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_pegawai_simpeg',
                api_key: esakip.api_key,
                type: 'unor',
                value: '<?php echo $id_satker; ?>',
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if(response.status == true){
                    alert('Berhasil singkron data pegawai SIMPEG!');
                    getTablePegawai(1);
                }else{
                    alert('Gagal singkron data pegawai SIMPEG!');
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan ajax!');
            }
        });
    }

    function simpan_pegawai(){
        var data = {};
        jQuery('.input_rhk').map(function(i, b){
            var id = jQuery(b).val();
            if(jQuery(b).is(':checked')){
                data[id] = 1;
            }else{
                data[id] = 0;
            }
        });
        if(Object.keys(data).length === 0){
            return alert('Data pegawai tidak boleh kosong!');
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'simpan_pegawai_simpeg',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>,
                id_skpd: <?php echo $id_skpd; ?>,
                ids: data
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                alert(response.message);
                if(response.status == 'success'){
                    getTablePegawai(1);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan!');
            }
        });
    }

    function getTablePegawai(destroy) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_pegawai_simpeg',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#p_aktif').text(response.aktif);
                    jQuery('#p_non_aktif').text(response.non_aktif);
                    if (destroy == 1) {
                        laporan_pk_table.fnDestroy();
                    }
                    jQuery('.table_list_pegawai tbody').html(response.data);
                    window.laporan_pk_table = jQuery('.table_list_pegawai').dataTable({
                        aLengthMenu: [
                            [5, 10, 25, 50, 100, -1], 
                            [5, 10, 25, 50, 100, "All"]
                        ],
                        iDisplayLength: 50,
                        order: [],
                        aoColumnDefs: [
                            { bSortable: false, aTargets: [ 0, 8 ] }, 
                            { bSearchable: false, aTargets: [ 0, 8 ] }
                        ] 
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat tabel!');
            }
        });
    }
</script>